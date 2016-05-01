<?php
/**
 * Verify MediaWiki global function naming convention.
 * A global function's name must be prefixed with 'wf' or 'ef'.
 * Per https://www.mediawiki.org/wiki/Manual:Coding_conventions/PHP#Naming
 */
// @codingStandardsIgnoreStart
class MediaWiki_Sniffs_NamingConventions_PrefixedGlobalFunctionsSniff
	implements PHP_CodeSniffer_Sniff {
	// @codingStandardsIgnoreEnd

	public $ignoreList = [];

	public function register() {
		return [ T_FUNCTION ];
	}

	/**
	 * @param PHP_CodeSniffer_File $phpcsFile
	 * @param int $ptr
	 * @return bool Does a namespace statement exist before this position in the file?
	 */
	private function tokenIsNamespaced( PHP_CodeSniffer_File $phpcsFile, $ptr ) {
		$tokens = $phpcsFile->getTokens();
		while ( $ptr > 0 ) {
			$token = $tokens[$ptr];
			if ( $token['type'] === "T_NAMESPACE" && !isset( $token['scope_opener'] ) ) {
				// In the format of "namespace Foo;", which applies to the entire file
				return true;
			}
			$ptr--;
		}
		return false;
	}

	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		if ( $this->tokenIsNamespaced( $phpcsFile, $stackPtr ) ) {
			return;
		}
		$tokens = $phpcsFile->getTokens();
		$token = $tokens[$stackPtr];

		// Name of function
		$name = $tokens[$stackPtr + 2]['content'];

		if ( in_array( $name, $this->ignoreList ) ) {
			return;
		}

		// Check if function is global
		if ( $token['level'] == 0 ) {
			$prefix = substr( $name, 0, 2 );

			if ( $prefix !== 'wf' && $prefix !== 'ef' ) {
				// Forge a valid global function name
				$expected = 'wf' . ucfirst( $name );

				$error = 'Global function "%s" is lacking a \'wf\' prefix. Should be "%s".';
				$data = [ $name, $expected ];
				$phpcsFile->addError( $error, $stackPtr, 'wfPrefix', $data );
			}
		}
	}
}
