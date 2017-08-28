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

namespace MediaWiki\Sniffs\Files;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class ClassMatchesFilenameSniff implements Sniff {

	/**
	 * Tokens to process
	 *
	 * @return array
	 */
	public function register() {
		return [ T_CLASS, T_INTERFACE, T_TRAIT ];
	}

	/**
	 * Check the class name against the filename
	 *
	 * @param File $phpcsFile File being checked
	 * @param int $stackPtr Position
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$fname = $phpcsFile->getFilename();
		if ( $fname === 'STDIN' ) {
			return;
		}
		$exp = explode( DIRECTORY_SEPARATOR, $fname );
		$base = end( $exp );
		$name = $phpcsFile->getDeclarationName( $stackPtr );
		if ( $base !== "$name.php" ) {
			$wrongCase = strtolower( $base ) === strtolower( "$name.php" );
			$phpcsFile->addError(
				"Class name '$name' does not match filename '$base'",
				$stackPtr,
				$wrongCase ? 'WrongCase' : 'NotMatch'
			);
		}
	}

}
