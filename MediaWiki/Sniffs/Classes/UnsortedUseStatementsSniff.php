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

namespace MediaWiki\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

class UnsortedUseStatementsSniff implements Sniff {

	/**
	 * @inheritDoc
	 *
	 * @return int[]
	 */
	public function register() : array {
		return [ T_USE ];
	}

	/**
	 * Called when one of the token types that this sniff is listening for
	 * is found.
	 *
	 * @param File $phpcsFile
	 * @param int $stackPtr
	 * @return int|void
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		// Only check use statements in the global scope.
		if ( !empty( $tokens[$stackPtr]['conditions'] ) ) {
			return;
		}

		// Seek to the end of the statement and get the string before the semi colon.
		$semiColon = $phpcsFile->findEndOfStatement( $stackPtr );
		if ( $tokens[$semiColon]['code'] !== T_SEMICOLON ) {
			return;
		}

		$lastUseStatementToken = 0;

		$useStatementList = $this->makeUseStatementList( $phpcsFile, $stackPtr, $lastUseStatementToken );
		$sortedStatements = [
			'classes' => $this->sortStatements( $useStatementList['classes'] ),
			'functions' => $this->sortStatements( $useStatementList['functions'] ),
			'constants' => $this->sortStatements( $useStatementList['constants'] )
		];

		if ( $useStatementList !== $sortedStatements ) {
			$fix = $phpcsFile->addFixableWarning(
				'Use statements are not alphabetically sorted',
				$stackPtr,
				'UnsortedUse'
			);

			if ( $fix ) {
				$phpcsFile->fixer->beginChangeset();

				// Wipe all existing use statements, then insert the newly sorted ones afterwards.
				// This loop terminates at $lastUseStatementToken + 2 to capture the last semi-colon
				// and line ending.
				for ( $i = $stackPtr; $i < $lastUseStatementToken + 2; $i++ ) {
					$phpcsFile->fixer->replaceToken( $i, '' );
				}

				$sortedStatements = array_merge(
					$sortedStatements['classes'],
					$sortedStatements['functions'],
					$sortedStatements['constants']
				);

				foreach ( $sortedStatements as $statement ) {
					$phpcsFile->fixer->addContent( $lastUseStatementToken, "$statement;" );
					$phpcsFile->fixer->addNewline( $lastUseStatementToken );
				}

				$phpcsFile->fixer->endChangeset();
			}
		}

		return $lastUseStatementToken;
	}

	/**
	 * This sorts full qualified class names similar to PHPStorm and other tools.
	 *
	 * @param string[] $statementList
	 * @return string[]
	 */
	private function sortStatements( array $statementList ) : array {
		$map = [];
		foreach ( $statementList as $use ) {
			$map[$use] = strtolower( $use );
		}
		natsort( $map );
		return array_keys( $map );
	}

	/**
	 * @param File $phpcsFile
	 * @param int $stackPtr
	 * @param int &$endPtr
	 * @return array[]
	 */
	private function makeUseStatementList( File $phpcsFile, int $stackPtr, int &$endPtr ) : array {
		$tokens = $phpcsFile->getTokens();
		$useStatementList = [
			'classes' => [],
			'functions' => [],
			'constants' => []
		];

		do {
			// Seek to the end of the statement and get the string before the semi colon.
			$semiColon = $phpcsFile->findEndOfStatement( $stackPtr );
			$endPtr = $semiColon;

			$fqnclass = $phpcsFile->getTokensAsString( $stackPtr, $semiColon - $stackPtr );

			$next = $phpcsFile->findNext( Tokens::$emptyTokens, $stackPtr + 1, null, true );

			// Check if this is an use for a constant or a function.
			if ( $this->isToken( $tokens, $next, T_FUNCTION, 'function' ) ) {
				$useStatementList['functions'][] = $fqnclass;
			} elseif ( $this->isToken( $tokens, $next, T_CONST, 'const' ) ) {
				$useStatementList['constants'][] = $fqnclass;
			} else {
				$useStatementList['classes'][] = $fqnclass;
			}

			$stackPtr = $phpcsFile->findNext( T_USE, $semiColon );
		} while ( $stackPtr !== false && empty( $tokens[$stackPtr]['conditions'] ) );

		return $useStatementList;
	}

	/**
	 * @param array[] $tokens
	 * @param int $stackPtr
	 * @param int $code Token type to compare, e.g. T_FUNCTION or T_CONST
	 * @param string $content Fallback for PHP <7.4
	 * @return bool
	 */
	private function isToken( array $tokens, int $stackPtr, int $code, string $content ) : bool {
		if ( $tokens[$stackPtr]['code'] === $code ) {
			return true;
		}

		// PHP 7.4 tokenizes as T_FUNCTION and T_CONST, but PHP 7.3 tokenizes as T_STRING
		return $tokens[$stackPtr]['code'] === T_STRING &&
			   $tokens[$stackPtr]['content'] === $content &&
			   // Namespace separators must follow T_STRING, so no white space check is required.
			   $tokens[$stackPtr + 1]['code'] !== T_NS_SEPARATOR;
	}
}
