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

namespace MediaWiki\Sniffs\WhiteSpace;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Sniff to ensure that `catch` is on the same line as the previous
 * closing bracket.
 *
 * @author Taavi Väänänen <hi@taavi.wtf>
 */
class SameLineCatchSniff implements Sniff {

	/**
	 * @inheritDoc
	 */
	public function register(): array {
		return [ T_CATCH ];
	}

	/**
	 * @param File $phpcsFile
	 * @param int $stackPtr The current token index.
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ): void {
		$tokens = $phpcsFile->getTokens();

		$closingBracket = $phpcsFile->findPrevious( T_CLOSE_CURLY_BRACKET, $stackPtr );
		if ( !$closingBracket || $tokens[$closingBracket]['line'] === $tokens[$stackPtr]['line'] ) {
			return;
		}

		$fix = $phpcsFile->addFixableWarning(
			'Expected catch to be on the same line as previous closing brace',
			$stackPtr,
			'CatchNotOnSameLine'
		);
		if ( $fix ) {
			$phpcsFile->fixer->beginChangeset();
			for ( $i = $closingBracket + 1; $i < $stackPtr; $i++ ) {
				$phpcsFile->fixer->replaceToken( $i, $i === $stackPtr - 1 ? ' ' : '' );
			}
			$phpcsFile->fixer->endChangeset();
		}
	}
}
