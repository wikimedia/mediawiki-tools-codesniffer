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
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHPCSUtils\Tokens\Collections;

/**
 * This sniff tries to distinguish between file-level, class, and stray comments, and verifies spacing and style
 * for each. It is based on the following rules:
 *  - Class documentation should use `/**`, without empty lines between the comment and the class.
 *  - File-level comments should contain one of the tags in `FILE_LEVEL_TAGS`, with at least an empty line between
 *    them and the class.
 *  - Other (stray) comments should use `/*` or `//` and have at least an empty line between them and the class.
 */
class CommentBeforeClassSniff implements Sniff {
	private const FILE_LEVEL_TAGS = [
		'@file',
		'@phan-file-suppress',
	];

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
		$classLine = $tokens[$stackPtr]['line'];
		while ( isset( $tokens[$previousRelevant]['attribute_opener'] ) ) {
			$attributeOpener = $tokens[$previousRelevant]['attribute_opener'];
			$previousRelevant = $phpcsFile->findPrevious(
				$skip,
				$attributeOpener - 1,
				null,
				true
			);
			// Consider the attributes as "part of" the class.
			$classLine = $tokens[$attributeOpener]['line'];
		}

		if (
			$tokens[$previousRelevant]['code'] !== T_DOC_COMMENT_CLOSE_TAG &&
			$tokens[$previousRelevant]['code'] !== T_COMMENT
		) {
			// No comment before the class
			return;
		}

		$commentEnd = $previousRelevant;
		$commentEndLine = $tokens[$commentEnd]['line'];

		if ( $tokens[$commentEnd]['code'] === T_COMMENT ) {
			if ( $classLine > $commentEndLine + 1 ) {
				// Stray comment, ignore.
				return;
			}
			$phpcsFile->addError(
				'You must use "/**" style comments for a class comment, or add an empty line after stray comments',
				$commentEnd,
				'StrayStyle'
			);
			return;
		}

		$commentStart = $tokens[$commentEnd]['comment_opener'];

		$isFileLevelComment = false;
		foreach ( $tokens[$commentStart]['comment_tags'] as $tag ) {
			if ( in_array( $tokens[$tag]['content'], self::FILE_LEVEL_TAGS, true ) ) {
				$isFileLevelComment = true;
				break;
			}
		}
		// TODO: Maybe check for presence of GPL license headers to detect file-level comments.

		if ( $isFileLevelComment ) {
			if ( $classLine === $commentEndLine + 1 ) {
				// Do not offer autofix, as people might have mixed class documentation and GPL license headers,
				// or other file-level stuff. TODO: catch these and suggest splitting in that scenario only.
				$error = 'There must be at least a blank line between a file-level comment and a class. ' .
					'Make sure you are not mixing file-level comments (like license headers) and class documentation';
				$phpcsFile->addError( $error, $commentEnd, 'FileSpacingAfter' );
			}
			return;
		}

		if ( $classLine > $commentEndLine + 1 ) {
			$error = 'If this is a class comment, it must have no blank lines after; ' .
				'if it is a stray comment, it must not use "/**" style comments';
			if ( $tokens[$commentStart]['comment_tags'] ) {
				// There might be more file-level comment tags that we didn't think about.
				$error .= '; if it is a file-level comment, file a task in #MediaWiki-Codesniffer';
			}
			$phpcsFile->addError( $error, $commentStart, 'SpacingAfter' );
		}
	}
}
