<?php

/**
 * Do not access php superglobals like $__GET,$__POST,$__SERVER.
 * Fail: $_GET['id']
 * Fail: $_POST['user']
 * Fail: $_SERVER['ip']
 */

// @codingStandardsIgnoreStart
class MediaWiki_Sniffs_Usage_SuperGlobalsUsageSniff implements PHP_CodeSniffer_Sniff {
	// @codingStandardsIgnoreEnd

	// The list of forbidden superglobals
	// As per https://www.mediawiki.org/wiki/Manual:Coding_conventions/PHP#Global_objects
	public static $forbiddenList = [
		'$_POST' => true,
		'$_GET' => true
	];
	/**
	 * @return  array
	 */
	public function register() {
		return [ T_VARIABLE ];
	}

	/**
	 * @param  PHP_CodeSniffer_File $phpcsFile The PHP_CodeSniffer_File object.
	 * @param int $stackPtr The current token index.
	 * @return void
	 */
	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		$currentToken = $tokens[$stackPtr];
		if ( isset( self::$forbiddenList[$currentToken['content']] ) === true ) {
			$error = '"%s" superglobals should not be accessed.';
			$phpcsFile->addError( $error, $stackPtr, 'SuperGlobals', $currentToken['content'] );
		}
	}
}
