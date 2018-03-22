<?php
/**
 * Detect unused MediaWiki global variable.
 * Unused global variables should be removed.
 */

namespace MediaWiki\Sniffs\VariableAnalysis;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

class UnusedGlobalVariablesSniff implements Sniff {

	/**
	 * @inheritDoc
	 */
	public function register() {
		return [ T_FUNCTION ];
	}

	/**
	 * @param File $phpcsFile
	 * @param int $stackPtr The current token index.
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		if ( !isset( $tokens[$stackPtr]['scope_opener'] ) ) {
			// An interface or abstract function which doesn't have a body
			return;
		}
		$scopeOpener = ++$tokens[$stackPtr]['scope_opener'];
		$scopeCloser = $tokens[$stackPtr]['scope_closer'];

		$globalLine = 0;
		$globalVariables = [];
		$otherVariables = [];
		$matches = [];
		$strVariables = [];

		for ( $i = $scopeOpener; $i < $scopeCloser; $i++ ) {
			if ( $tokens[$i]['code'] === T_GLOBAL ) {
				$globalLine = $tokens[$i]['line'];
			}
			if ( $tokens[$i]['code'] === T_VARIABLE && $tokens[$i]['line'] === $globalLine ) {
				$globalVariables[] = [ $tokens[$i]['content'], $i ];
			}
			if ( $tokens[$i]['code'] === T_VARIABLE && $tokens[$i]['line'] !== $globalLine ) {
				$otherVariables[$tokens[$i]['content']] = null;
			}
			if ( $tokens[$i]['code'] === T_DOUBLE_QUOTED_STRING
				|| $tokens[$i]['code'] === T_HEREDOC
			) {
				preg_match_all( '/\$\w+/', $tokens[$i]['content'], $matches );
				$strVariables = array_merge_recursive( $strVariables, $matches );
			}
		}
		$strVariables = iterator_to_array(
			new RecursiveIteratorIterator( new RecursiveArrayIterator( $strVariables ) ),
			false
		);
		foreach ( $globalVariables as $global ) {
			if ( !array_key_exists( $global[0], $otherVariables )
				&& !in_array( $global[0], $strVariables )
			) {
				$phpcsFile->addWarning(
					'Global ' . $global[0] .' is never used.',
					$global[1],
					'UnusedGlobal' . $global[0]
				);
			}
		}
	}
}
