<?php
/**
 * Make sure lower camel function name.
 */

namespace MediaWiki\Sniffs\NamingConventions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class LowerCamelFunctionsNameSniff implements Sniff {

	// Magic methods.
	private static $magicMethods = [
		'__construct' => true,
		'__destruct' => true,
		'__call' => true,
		'__callstatic' => true,
		'__get' => true,
		'__set' => true,
		'__isset' => true,
		'__unset' => true,
		'__sleep' => true,
		'__wakeup' => true,
		'__tostring' => true,
		'__set_state' => true,
		'__clone' => true,
		'__invoke' => true,
		'__debuginfo' => true
	];

	// A list of non-magic methods with double underscore.
	private static $methodsDoubleUnderscore = [
		'__soapcall' => true,
		'__getlastrequest' => true,
		'__getlastresponse' => true,
		'__getlastrequestheaders' => true,
		'__getlastresponseheaders' => true,
		'__getfunctions' => true,
		'__gettypes' => true,
		'__dorequest' => true,
		'__setcookie' => true,
		'__setlocation' => true,
		'__setsoapheaders' => true
	];

	// Scope list.
	private static $scopeList = [
		T_CLASS => true,
		T_INTERFACE => true,
		T_TRAIT => true
	];

	/**
	 * @return array
	 */
	public function register() {
		return [ T_FUNCTION ];
	}

	/**
	 * @param File $phpcsFile File object.
	 * @param int $stackPtr The current token index.
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		$functionContent = $tokens[$stackPtr + 2]['content'];
		$lowerFunctionName = strtolower( $functionContent );
		foreach ( $tokens[$stackPtr]['conditions'] as $scope => $code ) {
			if ( isset( self::$scopeList[$code] ) === true &&
				isset( self::$methodsDoubleUnderscore[$lowerFunctionName] ) !== true &&
				isset( self::$magicMethods[$lowerFunctionName] ) !== true
			) {
				$pos = strpos( $functionContent, '_' );
				if ( $pos !== false ||
					$functionContent[0] !== $lowerFunctionName[0]
				) {
					$error = 'Function name "%s" should use lower camel case.';
					$fix = $phpcsFile->addError(
						$error,
						$stackPtr,
						'FunctionName',
						[ $functionContent ]
					);
				}
			}
		}
	}
}
