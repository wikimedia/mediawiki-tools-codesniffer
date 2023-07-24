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

namespace MediaWiki\Sniffs\Arrays;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Sniff to enforce the presence only space between elements in inline array
 *
 * <rule ref="MediaWiki.Arrays.OneSpaceInlineArray" />
 */
class OneSpaceInlineArraySniff implements Sniff {

	public function register(): array {
		return [ T_OPEN_SHORT_ARRAY ];
	}

	/**
	 * @param File $phpcsFile
	 * @param int $stackPtr The current token index.
	 * @return void|int
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		$end = $tokens[$stackPtr]['bracket_closer'];

		if ( $tokens[$stackPtr]['line'] !== $tokens[$end]['line'] ) {
			// Multi-line, nothing to do
			return;
		}

		for ( $token = $stackPtr + 1; $token < $end; $token++ ) {
			if (
				$tokens[$token]['code'] === T_COMMA &&
				!in_array( $tokens[$token + 1]['code'], [ T_WHITESPACE, T_CLOSE_SHORT_ARRAY ] )
			) {
				$this->checkWarnAndFix( $phpcsFile, $token );
			}
		}
	}

	/**
	 * Check whether a warning should be emitted,
	 * emit one if necessary, and fix it if requested.
	 *
	 * @param File $phpcsFile
	 * @param int $token
	 */
	private function checkWarnAndFix(
		File $phpcsFile,
		int $token
	): void {
		$fix = $phpcsFile->addFixableWarning(
			'array elements need one space after comma',
			$token,
			'OneSpaceInlineArray'
		);
		if ( !$fix ) {
			return;
		}

		$phpcsFile->fixer->addContent( $token, ' ' );
	}

}
