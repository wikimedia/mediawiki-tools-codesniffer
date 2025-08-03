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
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Tokens\Collections;

class PropertyAnnotationsSniff implements Sniff {
	use CommentAnnotationsTrait;

	/**
	 * @inheritDoc
	 */
	public function register(): array {
		return [ T_VARIABLE ];
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

		// Only for class properties
		$scope = array_key_last( $tokens[$stackPtr]['conditions'] );
		if ( isset( $tokens[$stackPtr]['nested_parenthesis'] )
			|| $scope === null
			|| ( $tokens[$scope]['code'] !== T_CLASS &&
				$tokens[$scope]['code'] !== T_TRAIT &&
				$tokens[$scope]['code'] !== T_ANON_CLASS )
		) {
			return;
		}

		$find = Tokens::$emptyTokens;
		$find[] = T_STATIC;
		$find[] = T_NULLABLE;
		$find[] = T_STRING;
		$find[] = T_READONLY;
		$find += Collections::propertyTypeTokens();
		$visibilityPtr = $phpcsFile->findPrevious( $find, $stackPtr - 1, null, true );
		if ( !$visibilityPtr || ( $tokens[$visibilityPtr]['code'] !== T_VAR &&
			!isset( Tokens::$scopeModifiers[ $tokens[$visibilityPtr]['code'] ] ) )
		) {
			return;
		}

		$commentEnd = $phpcsFile->findPrevious( [ T_WHITESPACE, T_FINAL ], $visibilityPtr - 1, null, true );
		if ( !$commentEnd || $tokens[$commentEnd]['code'] !== T_DOC_COMMENT_CLOSE_TAG ) {
			return;
		}

		$commentStart = $tokens[$commentEnd]['comment_opener'];

		foreach ( $tokens[$commentStart]['comment_tags'] as $tag ) {
			$tagContent = $tokens[$tag]['content'];
			$annotation = $this->normalizeAnnotation( $tagContent, T_VARIABLE );
			if ( $annotation === false ) {
				$error = '%s is not a valid property annotation';
				$phpcsFile->addError( $error, $tag, 'UnrecognizedAnnotation', [ $tagContent ] );
			} elseif ( $tagContent !== $annotation ) {
				$fix = $phpcsFile->addFixableWarning(
					'Use %s annotation instead of %s',
					$tag,
					'NonNormalizedAnnotation',
					[ $annotation, $tagContent ]
				);
				if ( $fix ) {
					$phpcsFile->fixer->replaceToken( $tag, $annotation );
				}
			}
		}
	}
}
