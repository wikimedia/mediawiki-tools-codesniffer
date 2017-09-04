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

namespace MediaWiki\Sniffs\Usage;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class DeprecatedConstantUsageSniff implements Sniff {

	/**
	 * Deprecated constant => Replacement
	 *
	 * @var array
	 */
	private $deprecated = [
		'DB_SLAVE' => 'DB_REPLICA',
		'NS_IMAGE' => 'NS_FILE',
		'NS_IMAGE_TALK' => 'NS_FILE_TALK',
	];

	/**
	 * Tokens to listen for
	 *
	 * @return array
	 */
	public function register() {
		return [
			T_STRING,
		];
	}

	/**
	 * Check for any deprecated constants
	 *
	 * @param File $phpcsFile Current file
	 * @param int $stackPtr Position
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$token = $phpcsFile->getTokens()[$stackPtr];
		$current = $token['content'];
		if ( isset( $this->deprecated[$current] ) ) {
			$fix = $phpcsFile->addFixableWarning(
				"Deprecated constant $current used",
				$stackPtr,
				$current
			);
			if ( $fix ) {
				$phpcsFile->fixer->replaceToken( $stackPtr, $this->deprecated[$current] );
			}
		}
	}

}
