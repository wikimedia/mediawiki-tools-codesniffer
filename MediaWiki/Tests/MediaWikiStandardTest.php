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
class MediaWikiStandardTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var TestHelper
	 */
	private $helper;

	public function setUp() {
		parent::setUp();
		if ( empty( $this->helper ) ) {
			include_once __DIR__ . '/MediaWikiTestHelper.php';
			$this->helper = new MediaWikiTestHelper();
		}
	}

	/**
	 * testFiles
	 *
	 * Run simple syntax checks, comparing the phpcs output for the test
	 * file against an expected output.
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
	 * @param string $file
	 * @param string $standard
	 * @param boolean $expectedOutputFile
	 */
	public function testFile( $file, $standard, $expectedOutputFile ) {
		$outputStr = $this->prepareOutput( $this->helper->runPhpCs( $file, $standard ) );
		$expect = $this->prepareOutput( file_get_contents( $expectedOutputFile ) );
		$this->assertEquals( $expect, $outputStr );
	}

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
	 */
	public function testFix( $file, $standard, $fixedFile ) {
		$outputStr = $this->helper->runPhpCbf( $file, $standard );
		$expect = file_get_contents( $fixedFile );
		$this->assertEquals( $expect, $outputStr );
	}

	/**
	 * strip down the output to only the warnings
	 *
	 * @param string $outputStr phpcs output
	 * @return string
	 */
	private function prepareOutput( $outputStr ) {
		if ( $outputStr ) {
			$outputLines = explode( "\n", $outputStr );
			$outputLines = $this->stripTwoDashLines( $outputLines, true );
			$outputLines = $this->stripTwoDashLines( $outputLines, false );
			$outputStr = implode( "\n", $outputLines );
		}

		return $outputStr;
	}

	/**
	 * @param string[] $lines
	 * @param bool $front When true strip from the front of array. Otherwise the end.
	 * @return string[]
	 */
	private function stripTwoDashLines( array $lines, $front = true ) {
		$dashLines = 0;
		while ( $lines && $dashLines < 2 ) {
			$line = $front ? array_shift( $lines ) : array_pop( $lines );
			if ( strlen( $line ) > 0 && $line[0] === '-' ) {
				$dashLines++;
			}
		}

		return $lines;
	}

}
