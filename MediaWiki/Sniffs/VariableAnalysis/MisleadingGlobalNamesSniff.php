<?php

namespace MediaWiki\Sniffs\VariableAnalysis;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Detect variables named like $wg[A-Z]* that do not refer to globals.
 *
 * This sniff does not apply to uses of $GLOBALS
 *
 * @author DannyS712
 */
class MisleadingGlobalNamesSniff implements Sniff {

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

		$scopeOpener = $tokens[$stackPtr]['scope_opener'] + 1;
		$scopeCloser = $tokens[$stackPtr]['scope_closer'];

		$endOfGlobal = 0;
		$globalVariables = [];
		$misleadingVariables = [];

		for ( $i = $scopeOpener; $i < $scopeCloser; $i++ ) {
			if ( $tokens[$i]['code'] === T_GLOBAL ) {
				$endOfGlobal = $phpcsFile->findEndOfStatement( $i, T_COMMA );
			} elseif ( $tokens[$i]['code'] === T_VARIABLE ) {
				$variableName = $tokens[$i]['content'];
				if ( strncmp( $variableName, '$wg', 3 ) === 0 ) {
					if ( $i < $endOfGlobal ) {
						$globalVariables[$variableName] = null;
					} elseif ( !array_key_exists( $variableName, $globalVariables ) &&
						!isset( $misleadingVariables[$variableName] ) &&
						ctype_upper( substr( $variableName, 3, 1 ) )
					) {
						$misleadingVariables[$variableName] = $i;
					}
				}
			}
		}

		foreach ( $misleadingVariables as $variableName => $stackPtr ) {
			$phpcsFile->addWarning(
				"The 'wg' prefix should only be used with the 'global' keyword",
				$stackPtr,
				'Misleading' . $variableName
			);
		}
	}
}
