<?php
/**
 * This file was copied from CakePhps codesniffer tests before being modified
 * File: http://git.io/vkirb
 * From repository: https://github.com/cakephp/cakephp-codesniffer
 *
 * @license MIT
 * CakePHP(tm) : The Rapid Development PHP Framework (http://cakephp.org)
 * Copyright (c) 2005-2013, Cake Software Foundation, Inc.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * @author Addshore
 * Modifications
 *  - Rename appropriatly
 *  - Adapt $this->helper->runPhpCs call to pass second parameter $standard
 */
use MediaWiki\Sniffs\Tests\MediaWikiTestHelper;

class MediaWikiStandardTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var MediaWikiTestHelper
	 */
	private $helper;

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		if ( empty( $this->helper ) ) {
			include_once __DIR__ . '/MediaWikiTestHelper.php';
			$this->helper = new MediaWikiTestHelper();
		}
	}

	/**
	 * TestFiles
	 *
	 * Run simple syntax checks, comparing the phpcs output for the test.
	 * file against an expected output.
	 * @return  array $tests The test string[].
	 */
	public static function testProvider() {
		$tests = [];

		$standard = dirname( __DIR__ );
		$directoryIterator = new RecursiveDirectoryIterator( __DIR__ . '/files' );
		$iterator = new RecursiveIteratorIterator( $directoryIterator );
		foreach ( $iterator as $dir ) {
			if ( $dir->isDir() ) {
				continue;
			}

			$file = $dir->getPathname();
			if ( substr( $file, -4 ) !== '.php' ) {
				continue;
			}
			$tests[] = [
				$file,
				$standard,
				"$file.expect"
			];
		}
		return $tests;
	}

	/**
	 * _testFile
	 *
	 * @dataProvider testProvider
	 *
	 * @param string $file The path string of file.
	 * @param string $standard The standard string.
	 * @param boolean $expectedOutputFile The path of expected file.
	 * @return void
	 */
	public function testFile( $file, $standard, $expectedOutputFile ) {
		$outputStr = $this->prepareOutput( $this->helper->runPhpCs( $file, $standard ) );
		$expect = $this->prepareOutput( file_get_contents( $expectedOutputFile ) );
		$this->assertEquals( $expect, $outputStr );
	}
	/**
	 * @return array $tests The array of test.
	 */
	public static function testFixProvider() {
		$tests = self::testProvider();
		foreach ( array_keys( $tests ) as $idx ) {
			$fixed = $tests[$idx][0] . ".fixed";
			if ( file_exists( $fixed ) ) {
				$tests[$idx][2] = $fixed;
			} else {
				// no fixes should be applied, assert fixed
				// file matches original
				$tests[$idx][2] = $tests[$idx][0];
			}
		}
		return $tests;
	}

	/**
	 * @dataProvider testFixProvider
	 * @param string $file The path of file.
	 * @param string $standard The standard string.
	 * @param string $fixedFile The path of fixed file.
	 * @return void
	 */
	public function testFix( $file, $standard, $fixedFile ) {
		$outputStr = $this->helper->runPhpCbf( $file, $standard );
		$expect = file_get_contents( $fixedFile );
		$this->assertEquals( $expect, $outputStr );
	}

	/**
	 * strip down the output to only the warnings
	 *
	 * @param string $outputStr PHPCS output.
	 * @return string $outputStr PHPCS output.
	 */
	private function prepareOutput( $outputStr ) {
		if ( $outputStr ) {
			// Remove colors
			$outputStr = preg_replace( '`\033\[[0-9;]+m`', '', $outputStr );
			$outputLines = explode( "\n", $outputStr );
			// Remove lines that are empty or all dashes:
			$outputLines = preg_grep( '/^-*$/', $outputLines, PREG_GREP_INVERT );
			// Remove lines that start with 'Time:', 'FOUND', or 'FILE:':
			$outputLines = preg_grep( '/^(Time:|FOUND|FILE:) .*$/', $outputLines, PREG_GREP_INVERT );
			$outputStr = implode( "\n", $outputLines );
		}

		return $outputStr;
	}

}
