<?php
/**
 * Copyright (C) 2017 Kunal Mehta <legoktm@member.fsf.org>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 */

namespace MediaWiki\Sniffs\Usage;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class ForbiddenFunctionsSniff implements Sniff {

	/**
	 * Function => Replacement
	 *
	 * @var string[]
	 */
	private $functions = [
		'is_integer' => 'is_int',
		'create_function' => false,
		'extract' => false,
	];

	/**
	 * @return array
	 */
	public function register() {
		return [ T_STRING ];
	}

	/**
	 * @param File $phpcsFile File object.
	 * @param int $stackPtr The current token index.
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		// Check if the function is one of the bad ones
		$funcName = $tokens[$stackPtr]['content'];
		if ( !isset( $this->functions[$funcName] ) ) {
			return;
		}

		$ignore = [
			T_DOUBLE_COLON => true,
			T_OBJECT_OPERATOR => true,
			T_FUNCTION => true,
			T_CONST => true,
		];

		// Check to make sure it's a PHP function (not $this->, etc.)
		$prevToken = $phpcsFile->findPrevious( T_WHITESPACE, ( $stackPtr - 1 ), null, true );
		if ( isset( $ignore[$tokens[$prevToken]['code']] ) ) {
			return;
		}

		$replacement = $this->functions[$funcName];
		if ( $replacement ) {
			$fix = $phpcsFile->addFixableWarning(
				"Use $replacement() instead of $funcName",
				$stackPtr,
				$funcName
			);
			if ( $fix ) {
				$phpcsFile->fixer->replaceToken( $stackPtr, $replacement );
			}
		} else {
			$phpcsFile->addWarning(
				"$funcName should not be used",
				$stackPtr,
				$funcName
			);
		}
	}
}
