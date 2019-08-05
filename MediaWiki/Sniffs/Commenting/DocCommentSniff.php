<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class DocCommentSniff implements Sniff {

	/**
	 * @inheritDoc
	 */
	public function register() {
		return [ T_DOC_COMMENT_OPEN_TAG ];
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param File $phpcsFile The file being scanned.
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		$commentStart = $stackPtr;
		$commentEnd = $tokens[$stackPtr]['comment_closer'];
		$isMultiLineDoc = ( $tokens[$commentStart]['line'] !== $tokens[$commentEnd]['line'] );

		// Start token should exact /**
		if ( $tokens[$commentStart]['code'] === T_DOC_COMMENT_OPEN_TAG &&
			$tokens[$commentStart]['content'] !== '/**'
		) {
			$error = 'Comment open tag must be \'/**\'';
			$fix = $phpcsFile->addFixableError( $error, $commentStart, 'SyntaxOpenTag' );
			if ( $fix ) {
				$phpcsFile->fixer->replaceToken( $commentStart, '/**' );
			}
		}
		// Calculate the column to align all doc stars. Use column of /**, add 1 to skip char /
		$columnDocStar = $tokens[$commentStart]['column'] + 1;
		$prevLineDocStar = $tokens[$commentStart]['line'];

		for ( $i = $commentStart; $i <= $commentEnd; $i++ ) {
			$initialStarChars = 0;

			// Star token should exact *
			if ( $tokens[$i]['code'] === T_DOC_COMMENT_STAR ) {
				if ( $tokens[$i]['content'] !== '*' ) {
					$error = 'Comment star must be \'*\'';
					$fix = $phpcsFile->addFixableError( $error, $i, 'SyntaxDocStar' );
					if ( $fix ) {
						$phpcsFile->fixer->replaceToken( $i, '*' );
					}
				}
				// Multi stars in a line are parsed as a new token
				$initialStarChars = strspn( $tokens[$i + 1]['content'], '*' );
				if ( $initialStarChars > 0 ) {
					$error = 'Comment star must be a single \'*\'';
					$fix = $phpcsFile->addFixableError( $error, $i, 'SyntaxMultiDocStar' );
					if ( $fix ) {
						$phpcsFile->fixer->replaceToken(
							$i + 1,
							substr( $tokens[$i + 1]['content'], $initialStarChars )
						);
					}
				}
			}

			// Ensure whitespace after /** or *
			if ( ( $tokens[$i]['code'] === T_DOC_COMMENT_OPEN_TAG ||
				$tokens[$i]['code'] === T_DOC_COMMENT_STAR ) &&
				$tokens[$i + 1]['length'] > 0
			) {
				$commentStarSpacing = $i + 1;
				$expectedSpaces = 1;
				// ignore * removed by SyntaxMultiDocStar and count spaces after that
				$currentSpaces = strspn(
					$tokens[$commentStarSpacing]['content'], ' ', $initialStarChars
				);
				$error = null;
				$code = null;
				if ( $isMultiLineDoc && $currentSpaces < $expectedSpaces ) {
					// be relax for multiline docs, because some line breaks in @param can
					// have more than one space after a doc star
					$error = 'Expected at least %s spaces after doc star; %s found';
					$code = 'SpacingDocStar';
				} elseif ( !$isMultiLineDoc && $currentSpaces !== $expectedSpaces ) {
					$error = 'Expected %s spaces after doc star on single line; %s found';
					$code = 'SpacingDocStarSingleLine';
				}
				if ( $error !== null && $code !== null ) {
					$data = [
						$expectedSpaces,
						$currentSpaces,
					];
					$fix = $phpcsFile->addFixableError(
						$error,
						$commentStarSpacing,
						$code,
						$data
					);
					if ( $fix ) {
						if ( $currentSpaces > $expectedSpaces ) {
							// Remove whitespace
							$content = $tokens[$commentStarSpacing]['content'];
							$phpcsFile->fixer->replaceToken(
								$commentStarSpacing,
								substr( $content, 0, $expectedSpaces - $currentSpaces )
							);
						} else {
							// Add whitespace
							$phpcsFile->fixer->addContent(
								$i, str_repeat( ' ', $expectedSpaces )
							);
						}
					}
				}
			}

			if ( !$isMultiLineDoc ) {
				continue;
			}

			// Ensure one whitespace before @param/@return
			if ( $tokens[$i]['code'] === T_DOC_COMMENT_TAG &&
				$tokens[$i]['line'] === $tokens[$i - 1]['line']
			) {
				$commentTagSpacing = $i - 1;
				$expectedSpaces = 1;
				$currentSpaces = strspn( strrev( $tokens[$commentTagSpacing]['content'] ), ' ' );
				if ( $expectedSpaces !== $currentSpaces ) {
					$data = [
						$expectedSpaces,
						$tokens[$i]['content'],
						$currentSpaces,
					];
					$fix = $phpcsFile->addFixableError(
						'Expected %s spaces before %s; %s found',
						$commentTagSpacing,
						'SpacingDocTag',
						$data
					);
					if ( $fix ) {
						if ( $currentSpaces > $expectedSpaces ) {
							// Remove whitespace
							$content = $tokens[$commentTagSpacing]['content'];
							$phpcsFile->fixer->replaceToken(
								$commentTagSpacing,
								substr( $content, 0, $expectedSpaces - $currentSpaces )
							);
						} else {
							// Add whitespace
							$phpcsFile->fixer->addContentBefore(
								$i, str_repeat( ' ', $expectedSpaces )
							);
						}
					}
				}

				continue;
			}

			// Ensure aligned * or */ for multiline comments
			if ( ( $tokens[$i]['code'] === T_DOC_COMMENT_STAR ||
				$tokens[$i]['code'] === T_DOC_COMMENT_CLOSE_TAG ) &&
				$tokens[$i]['column'] !== $columnDocStar &&
				$tokens[$i]['line'] !== $prevLineDocStar
			) {
				if ( $tokens[$i]['code'] === T_DOC_COMMENT_STAR ) {
					$error = 'Comment star tag not aligned with open tag';
					$code = 'SyntaxAlignedDocStar';
				} else {
					$error = 'Comment close tag not aligned with open tag';
					$code = 'SyntaxAlignedDocClose';
				}
				$fix = $phpcsFile->addFixableError( $error, $i, $code );
				if ( $fix ) {
					$columnOff = $columnDocStar - $tokens[$i]['column'];
					if ( $columnOff < 0 ) {
						$tokenBefore = $i - 1;
						// Ensure to remove only whitespaces
						if ( $tokens[$tokenBefore]['code'] === T_DOC_COMMENT_WHITESPACE ) {
							$columnOff = max( $columnOff, $tokens[$tokenBefore]['length'] * -1 );
							// remove whitespaces
							$phpcsFile->fixer->replaceToken(
								$tokenBefore,
								substr( $tokens[$tokenBefore]['content'], 0, $columnOff )
							);
						}
					} else {
						// Add whitespaces
						$phpcsFile->fixer->addContentBefore( $i, str_repeat( ' ', $columnOff ) );
					}
				}
				$prevLineDocStar = $tokens[$i]['line'];
			}
		}

		// End token should exact */
		if ( $tokens[$commentEnd]['code'] === T_DOC_COMMENT_CLOSE_TAG &&
			$tokens[$commentEnd]['content'] !== '*/'
		) {
			$error = 'Comment close tag must be \'*/\'';
			$fix = $phpcsFile->addFixableError( $error, $commentEnd, 'SyntaxCloseTag' );
			if ( $fix ) {
				$phpcsFile->fixer->replaceToken( $commentEnd, '*/' );
			}
		}

		// For multi line comments the closing tag must have it own line
		if ( $isMultiLineDoc ) {
			$prev = $commentEnd - 1;
			$prevNonWhitespace = $phpcsFile->findPrevious(
				[ T_DOC_COMMENT_WHITESPACE ], $prev, null, true
			);
			if ( $tokens[$prevNonWhitespace]['line'] === $tokens[$commentEnd]['line'] ) {
				$firstWhitespaceOnLine = $phpcsFile->findFirstOnLine(
					[ T_DOC_COMMENT_WHITESPACE ], $prevNonWhitespace
				);
				$error = 'Comment close tag should have own line';
				$fix = $phpcsFile->addFixableError( $error, $commentEnd, 'CloseTagOwnLine' );
				if ( $fix ) {
					$phpcsFile->fixer->beginChangeset();
					$phpcsFile->fixer->addNewline( $prev );
					// Copy the indent of the previous line to the new line
					$phpcsFile->fixer->addContent(
						$prev, $tokens[$firstWhitespaceOnLine]['content']
					);
					$phpcsFile->fixer->endChangeset();
				}
			}
		} else {
			// Ensure a whitespace before the token
			$commentCloseSpacing = $commentEnd - 1;
			$expectedSpaces = 1;
			$currentSpaces = strspn( strrev( $tokens[$commentCloseSpacing]['content'] ), ' ' );
			if ( $currentSpaces !== $expectedSpaces ) {
				$data = [
					$expectedSpaces,
					$currentSpaces,
				];
				$fix = $phpcsFile->addFixableError(
					'Expected %s spaces before close comment tag on single line; %s found',
					$commentCloseSpacing,
					'SpacingSingleLineCloseTag',
					$data
				);
				if ( $fix ) {
					if ( $currentSpaces > $expectedSpaces ) {
						// Remove whitespace
						$content = $tokens[$commentCloseSpacing]['content'];
						$phpcsFile->fixer->replaceToken(
							$commentCloseSpacing, substr( $content, 0, $expectedSpaces - $currentSpaces )
						);
					} else {
						// Add whitespace
						$phpcsFile->fixer->addContentBefore(
							$commentEnd, str_repeat( ' ', $expectedSpaces )
						);
					}
				}
			}
		}
	}
}
