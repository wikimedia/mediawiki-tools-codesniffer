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
	 * @var int[] array containing the first locations of namespaces in files that we have seen so far.
	 */
	private static $firstNamespaceLocations = [];

	/**
	 * @var string[] array containing a list of files that contain no namespace statements.
	 */
	private static $noNamespaceFiles = [];

	/**
	 * @param PHP_CodeSniffer_File $phpcsFile
	 * @param int $ptr
	 *
	 * @return bool Does a namespace statement exist before this position in the file?
	 */
	private function tokenIsNamespaced( PHP_CodeSniffer_File $phpcsFile, $ptr ) {

		$fileName = $phpcsFile->getFilename();
		// Check if we already know if the token is namespaced or not and return early if possible.
		if (
			isset( self::$firstNamespaceLocations[$fileName] ) &&
			$ptr > self::$firstNamespaceLocations[$fileName] )
		{
			return true;
		}
		if ( isset( self::$noNamespaceFiles[$fileName] ) ) {
			return false;
		}

		// If not scan the whole file at once looking for namespacing or lack of and set in the statics.
		$tokens = $phpcsFile->getTokens();
		$tokenIndex = max( array_keys( $tokens ) );
		while ( $tokenIndex > 0 ) {
			$token = $tokens[$tokenIndex];
			if ( $token['type'] === "T_NAMESPACE" && !isset( $token['scope_opener'] ) ) {
				// In the format of "namespace Foo;", which applies to everything below
				self::$firstNamespaceLocations[$fileName] = $tokenIndex;
			}
			$tokenIndex--;
		}
		if ( !isset( self::$firstNamespaceLocations[$fileName] ) ) {
			self::$noNamespaceFiles[$fileName] = true;
		}

		// Return if the token was namespaced.
		if (
			isset( self::$firstNamespaceLocations[$fileName] ) &&
			$ptr > self::$firstNamespaceLocations[$fileName] )
		{
			return true;
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
