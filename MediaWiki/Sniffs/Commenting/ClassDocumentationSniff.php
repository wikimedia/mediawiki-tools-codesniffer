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
use PHPCSUtils\Tokens\Collections;

class ClassDocumentationSniff implements Sniff {
	use DocCommentTrait;

	/**
	 * @inheritDoc
	 */
	public function register(): array {
		return [ T_CLASS, T_INTERFACE, T_TRAIT, T_ENUM ];
	}

	/**
	 * @param File $phpcsFile The file being scanned.
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		$skip = array_merge(
			[ T_WHITESPACE ],
			Collections::classModifierKeywords()
		);
		$previousRelevant = $phpcsFile->findPrevious( $skip, $stackPtr - 1, null, true );
		while ( isset( $tokens[$previousRelevant]['attribute_opener'] ) ) {
			$previousRelevant = $phpcsFile->findPrevious(
				$skip,
				$tokens[$previousRelevant]['attribute_opener'] - 1,
				null,
				true
			);
		}

		if ( !$previousRelevant || $tokens[$previousRelevant]['code'] !== T_DOC_COMMENT_CLOSE_TAG ) {
			return;
		}

		$commentEnd = $previousRelevant;
		$commentStart = $tokens[$commentEnd]['comment_opener'];

		$this->checkDocCommentFormatting( $phpcsFile, $commentStart );
	}
}
