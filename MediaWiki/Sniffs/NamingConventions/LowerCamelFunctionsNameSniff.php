<?php
/**
 * Make sure lower camel function name.
 */

namespace MediaWiki\Sniffs\NamingConventions;

use MediaWiki\Sniffs\PHPUnit\PHPUnitTestTrait;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class LowerCamelFunctionsNameSniff implements Sniff {

	use PHPUnitTestTrait;

	// Magic methods.
	private const MAGIC_METHODS = [
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
		'__serialize' => true,
		'__unserialize' => true,
		'__debuginfo' => true
	];

	// A list of non-magic methods with double underscore.
	private const METHOD_DOUBLE_UNDERSCORE = [
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
	private const SCOPE_LIST = [
		T_CLASS => true,
		T_INTERFACE => true,
		T_TRAIT => true
	];

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
		$functionContent = $phpcsFile->getDeclarationName( $stackPtr );
		if ( $functionContent === null ) {
			return;
		}

		$tokens = $phpcsFile->getTokens();
		$lowerFunctionName = strtolower( $functionContent );
		foreach ( $tokens[$stackPtr]['conditions'] as $code ) {
			if ( !isset( self::SCOPE_LIST[$code] ) ||
				isset( self::METHOD_DOUBLE_UNDERSCORE[$lowerFunctionName] ) ||
				isset( self::MAGIC_METHODS[$lowerFunctionName] )
			) {
				continue;
			}

			$pos = strpos( $functionContent, '_' );
			if ( $pos !== false && !$this->isTestFunction( $phpcsFile, $stackPtr ) ||
				$functionContent[0] !== $lowerFunctionName[0]
			) {
				$phpcsFile->addError(
					'Function name "%s" should use lower camel case.',
					$stackPtr,
					'FunctionName',
					[ $functionContent ]
				);
			}
		}
	}
}
