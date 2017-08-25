<?php

/**
 * Do not access php superglobals like $__GET,$__POST,$__SERVER.
 * Fail: $_GET['id']
 * Fail: $_POST['user']
 * Fail: $_SERVER['ip']
 */

namespace MediaWiki\Sniffs\Usage;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class SuperGlobalsUsageSniff implements Sniff {

	// The list of forbidden superglobals
	// As per https://www.mediawiki.org/wiki/Manual:Coding_conventions/PHP#Global_objects
	public static $forbiddenList = [
		'$_POST' => true,
		'$_GET' => true
	];
	/**
	 * @return array
	 */
	public function register() {
		return [ T_VARIABLE ];
	}

	/**
	 * @param File $phpcsFile The File object.
	 * @param int $stackPtr The current token index.
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		$currentToken = $tokens[$stackPtr];
		if ( isset( self::$forbiddenList[$currentToken['content']] ) === true ) {
			$error = '"%s" superglobals should not be accessed.';
			$phpcsFile->addError( $error, $stackPtr, 'SuperGlobals', $currentToken['content'] );
		}
	}
}
