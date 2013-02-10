<?php
/**
 * Verify MediaWiki global variable naming convention.
 * A global name must be prefixed with 'wg'.
 */
class MediaWiki_Sniffs_NamingConventions_ValidGlobalNameSniff implements PHP_CodeSniffer_Sniff {

	/**
	 * http://php.net/manual/en/reserved.variables.argv.php
	 */
	protected static $PHPReserved = array(
		'$GLOBALS',
		'$_SERVER',
		'$_GET',
		'$_POST',
		'$_FILES',
		'$_REQUEST',
		'$_SESSION',
		'$_ENV',
		'$_COOKIE',
		'$php_errormsg',
		'$HTTP_RAW_POST_DATA',
		'$http_response_header',
		'$argc',
		'$argv'
	);

	protected static $mediaWikiValid = array(
		'$messageMemc',
		'$parserMemc',
		'$IP',
	);

	public function register() {
		return array( T_GLOBAL );
	}

	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		$token = $tokens[$stackPtr];

		if( $token['code'] !== T_GLOBAL ) {
			return;
		}

		$errorIssued = false;

		$nameIndex  = $phpcsFile->findNext( T_VARIABLE, $stackPtr + 1 );
		$globalName = $tokens[$nameIndex]['content'];

		if( in_array( $globalName, self::$mediaWikiValid ) ||
			in_array( $globalName, self::$PHPReserved )
		) {
			return;
		}

		// skip '$' and forge a valid global variable name
		$expected = '$wg' . ucfirst(substr( $globalName, 1 ));


		// Verify global is prefixed with wg
		if( strpos($globalName, '$wg' ) !== 0 ) {

			$error = 'Global variable "%s" is lacking \'wg\' prefix. Should be "%s".';
			$type = 'wgPrefix';
			$data = array( $globalName, $expected );
			$phpcsFile->addError( $error, $stackPtr, $type, $data );

			$errorIssued = true;
		}

		if( !$errorIssued ) { // no need to warn twice.
			// Verify global is probably CamelCase
			$val = ord( substr( $globalName, 3, 1 ) );
			if( !($val >= 65 && $val <= 90) ) {

				$error = 'Global variable "%s" should use CamelCase: "%s"';
				$type = 'CamelCase';
				$data = array( $globalName, $expected );
				$phpcsFile->addError( $error, $stackPtr, $type, $data );

				$errorIssued = true;
			}
		}

	}//end process()

}

