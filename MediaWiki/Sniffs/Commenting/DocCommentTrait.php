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

/**
 * Trait with code to deal with doc comments.
 */
trait DocCommentTrait {
	/**
	 * Checks the formatting of doc comments, regardless of the element that the doc comment belongs to.
	 */
	private function checkDocCommentFormatting( File $phpcsFile, int $commentStart ): void {
		$tokens = $phpcsFile->getTokens();
		$commentStartLine = $tokens[$commentStart]['line'];
		$commentEnd = $tokens[$commentStart]['comment_closer'];
		$commentEndLine = $tokens[$commentEnd]['line'];

		$firstNonEmpty = $phpcsFile->findNext(
			[ T_DOC_COMMENT_WHITESPACE, T_DOC_COMMENT_STAR ],
			$commentStart + 1,
			$commentEnd,
			true
		);
		if ( $firstNonEmpty === false ) {
			$fixEmpty = $phpcsFile->addFixableWarning(
				'Doc comment must not be empty',
				$commentStart,
				'EmptyComment'
			);
			if ( $fixEmpty ) {
				$phpcsFile->fixer->beginChangeset();
				for ( $delToken = $commentStart; $delToken <= $commentEnd; $delToken++ ) {
					$phpcsFile->fixer->replaceToken( $delToken, '' );
				}
				$tokenAfterComment = $tokens[$commentEnd + 1];
				if (
					$tokenAfterComment['code'] === T_WHITESPACE &&
					$tokenAfterComment['line'] === $tokens[$commentEnd]['line']
				) {
					$phpcsFile->fixer->replaceToken( $commentEnd + 1, '' );
				}
				$phpcsFile->fixer->endChangeset();
			}
			return;
		}

		$firstNonSpace = $phpcsFile->findNext( T_DOC_COMMENT_WHITESPACE, $commentStart + 1, $commentEnd, true );
		if ( $firstNonSpace && $tokens[$firstNonSpace]['code'] === T_DOC_COMMENT_STAR ) {
			$nextNonSpace = $phpcsFile->findNext( T_DOC_COMMENT_WHITESPACE, $firstNonSpace + 1, $commentEnd, true );
			if ( $nextNonSpace && $tokens[$nextNonSpace]['code'] === T_DOC_COMMENT_STAR ) {
				$fixStart = $phpcsFile->addFixableWarning(
					'Doc comments must not start with multiple empty lines',
					$commentStart,
					'EmptyLinesStart'
				);
				if ( $fixStart ) {
					$firstContentToken = $phpcsFile->findNext(
						[ T_DOC_COMMENT_WHITESPACE, T_DOC_COMMENT_STAR ],
						$nextNonSpace + 1,
						$commentEnd,
						true
					);
					$firstContentLine = $tokens[$firstContentToken]['line'];
					$phpcsFile->fixer->beginChangeset();
					for ( $checkToken = $commentStart + 1; $checkToken <= $commentEnd; $checkToken++ ) {
						$curLine = $tokens[$checkToken]['line'];
						if ( $curLine > $commentStartLine && $curLine < $firstContentLine ) {
							$phpcsFile->fixer->replaceToken( $checkToken, '' );
						}
					}
					$phpcsFile->fixer->endChangeset();
				}
			}
		}

		$lastNonSpace = $phpcsFile->findPrevious( T_DOC_COMMENT_WHITESPACE, $commentEnd - 1, $commentStart, true );
		if ( $lastNonSpace && $tokens[$lastNonSpace]['code'] === T_DOC_COMMENT_STAR ) {
			$fixEnd = $phpcsFile->addFixableWarning(
				'Doc comments must not end with multiple empty lines',
				$commentEnd,
				'EmptyLinesEnd'
			);
			if ( $fixEnd ) {
				$lastContentToken = $phpcsFile->findPrevious(
					[ T_DOC_COMMENT_WHITESPACE, T_DOC_COMMENT_STAR ],
					$lastNonSpace - 1,
					$commentStart,
					true
				);
				$lastContentLine = $tokens[$lastContentToken]['line'];
				$phpcsFile->fixer->beginChangeset();
				for ( $checkToken = $commentEnd - 1; $checkToken >= $commentStart; $checkToken-- ) {
					$curLine = $tokens[$checkToken]['line'];
					if ( $curLine < $commentEndLine && $curLine > $lastContentLine ) {
						$phpcsFile->fixer->replaceToken( $checkToken, '' );
					}
				}
				$phpcsFile->fixer->endChangeset();
			}
		}
	}
}
