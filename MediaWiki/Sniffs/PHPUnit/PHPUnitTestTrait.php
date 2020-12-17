<?php

namespace MediaWiki\Sniffs\PHPUnit;

use PHP_CodeSniffer\Files\File;

/**
 * Check if a class is a test class
 *
 * @license GPL-2.0-or-later
 */
trait PHPUnitTestTrait {

	/**
	 * Set of PHPUnit base classes, without leading backslash
	 * @var string[]
	 */
	private static $PHPUNIT_CLASSES = [
		// @phan-suppress-previous-line PhanReadOnlyPrivateProperty Traits cannot have constants
		'MediaWikiTestCase' => 'MediaWikiTestCase',
		'MediaWikiUnitTestCase' => 'MediaWikiUnitTestCase',
		'MediaWikiIntegrationTestCase' => 'MediaWikiIntegrationTestCase',
		'PHPUnit_Framework_TestCase' => 'PHPUnit_Framework_TestCase',
		// This class may be 'use'd, but checking for that would be complicated
		'PHPUnit\\Framework\\TestCase' => 'PHPUnit\\Framework\\TestCase',
	];

	/**
	 * @param File $phpcsFile
	 * @param int $stackPtr for T_CLASS token
	 *
	 * @return bool
	 */
	private function inTestClass( File $phpcsFile, $stackPtr ) {
		$extendedClass = ltrim( $phpcsFile->findExtendedClassName( $stackPtr ), '\\' );
		if ( array_key_exists( $extendedClass, self::$PHPUNIT_CLASSES ) ) {
			return true;
		}

		return (bool)preg_match(
			'/(?:Test(?:Case)?(?:Base)?|Suite)$/', $phpcsFile->getDeclarationName( $stackPtr )
		);
	}

}
