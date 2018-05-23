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

namespace MediaWiki\Sniffs\PHP70Features;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class ScalarTypeHintUsageSniff implements Sniff {

	private static $bad = [
		// PHP 7.0+
		'string', 'int', 'float', 'bool',
		// PHP 7.1+
		'iterable',
		// PHP 7.2+
		'object',
	];

	/**
	 * @inheritDoc
	 */
	public function register() {
		return [
			T_FUNCTION,
			T_RETURN_TYPE,
		];
	}

	/**
	 * If the type-hint is bad, raise an error
	 *
	 * @param File $phpcsFile File
	 * @param int $stackPtr position
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$info = $phpcsFile->getTokens()[$stackPtr];
		if ( $info['code'] === T_FUNCTION ) {
			$params = $phpcsFile->getMethodParameters( $stackPtr );
			foreach ( $params as $param ) {
				if ( $param['type_hint'] !== ''
					&& in_array( $param['type_hint'], self::$bad )
				) {
					$phpcsFile->addError(
						"Type hint of '{$param['type_hint']}' cannot be used",
						$param['token'],
						'Found'
					);
				}
			}
		} else {
			// T_RETURN_TYPE
			if ( in_array( $info['content'], self::$bad ) ) {
				$phpcsFile->addError(
					"Return type hint of {$info['content']} cannot be used",
					$stackPtr,
					'ReturnTypeFound'
				);
			}
		}
	}
}
