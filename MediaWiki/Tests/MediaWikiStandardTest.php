<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

// This project does not use covers tags:
// phpcs:disable MediaWiki.Commenting.MissingCovers.MissingCovers

namespace MediaWiki\Sniffs\Tests;

class MediaWikiStandardTest extends \PHPUnit\Framework\TestCase {

	/**
	 * @var Helper
	 */
	private $helper;

	public function setUp() : void {
		parent::setUp();
		if ( empty( $this->helper ) ) {
			$this->helper = new Helper();
		}
	}

	/**
	 * Run simple syntax checks, comparing the phpcs output for the test.
	 * file against an expected output.
	 * @return array $tests The test string[].
	 */
	public static function fileDataProvider() {
		$tests = [];

		$standard = dirname( __DIR__ );
		$directoryIterator = new \RecursiveDirectoryIterator( __DIR__ . '/files' );
		$iterator = new \RecursiveIteratorIterator( $directoryIterator );
		foreach ( $iterator as $dir ) {
			if ( $dir->isDir() ) {
				continue;
			}

			$file = $dir->getPathname();
			if ( substr( $file, -4 ) !== '.php' ) {
				continue;
			}
			$tests[$dir->getFilename()] = [
				$file,
				$standard,
				"$file.expect"
			];
		}
		return $tests;
	}

	/**
	 * @dataProvider fileDataProvider
	 *
	 * @param string $file The path string of file.
	 * @param string $standard The standard string.
	 * @param boolean $expectedOutputFile The path of expected file.
	 */
	public function testFile( $file, $standard, $expectedOutputFile ) {
		$outputStr = $this->prepareOutput( $this->helper->runPhpCs( $file, $standard ) );
		$expect = $this->prepareOutput( file_get_contents( $expectedOutputFile ) );
		$this->assertEquals( $expect, $outputStr );
	}

	/**
	 * @return array $tests The array of test.
	 */
	public static function fixDataProvider() {
		$tests = self::fileDataProvider();
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
	 * @dataProvider fixDataProvider
	 * @param string $file The path of file.
	 * @param string $standard The standard string.
	 * @param string $fixedFile The path of fixed file.
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
			// Do a "\r\n" -> "\n" and "\r" -> "\n" transformation for windows machine
			$outputStr = str_replace( [ "\r\n", "\r" ], "\n", $outputStr );

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
// phpcs:enable MediaWiki.Commenting.MissingCovers.MissingCovers
