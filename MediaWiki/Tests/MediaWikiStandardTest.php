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

namespace MediaWiki\Sniffs\Tests;

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Files\DummyFile;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Reporter;
use PHP_CodeSniffer\Ruleset;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class MediaWikiStandardTest extends TestCase {

	public static function fileDataProvider() {
		$standard = dirname( __DIR__ );
		$directoryIterator = new RecursiveDirectoryIterator( __DIR__ . '/files' );
		$iterator = new RecursiveIteratorIterator( $directoryIterator );

		/** @var SplFileInfo $dir */
		foreach ( $iterator as $dir ) {
			if ( $dir->isDir() ) {
				continue;
			}

			$file = $dir->getPathname();
			if ( !str_ends_with( $file, '.php' ) ) {
				continue;
			}

			$dirStandard = $dir->getPath() . '/.phpcs.xml';
			if ( !file_exists( $dirStandard ) ) {
				$dirStandard = $standard;
			}

			$fixed = "$file.fixed";
			if ( !file_exists( $fixed ) ) {
				$fixed = $file;
			}

			yield $dir->getFilename() => [
				$file,
				$dirStandard,
				"$file.expect",
				$fixed
			];
		}
	}

	/**
	 * @coversNothing
	 * @dataProvider fileDataProvider
	 */
	public function testFile( string $file, string $standard, string $expectedReport, string $expectedFixed ) {
		$config = new Config();
		$config->standards = [ $standard ];
		$config->files = [ $file ];
		$config->encoding = 'utf-8';
		$config->reports = [ 'full' => null ];
		$config->colors = false;
		$config->reportWidth = 0;
		$config->showSources = true;
		$config->tabWidth = 4;

		// This is like running `phpcs`
		$dummy = new DummyFile( file_get_contents( $file ), new Ruleset( $config ), $config );
		$dummy->process();

		$report = $this->getReport( $dummy, $config );
		$this->assertEquals(
			$this->prepareOutput( file_get_contents( $expectedReport ) ),
			$this->prepareOutput( $report )
		);

		// This is like running `phpcbf`
		$fixed = $this->getFixed( $dummy );
		// No point in comparing a file with itself in case there was nothing to fix
		if ( $fixed !== null ) {
			$this->assertEquals( file_get_contents( $expectedFixed ), $fixed );
		}
	}

	private function getReport( File $phpcsFile, Config $config ): string {
		$reporter = new Reporter( $config );
		$reporter->cacheFileReport( $phpcsFile );

		ob_start();
		$reporter->printReport( 'full' );
		$report = ob_get_contents();
		ob_end_clean();

		return $report;
	}

	private function getFixed( File $phpcsFile ): ?string {
		if ( $phpcsFile->getFixableCount() ) {
			$phpcsFile->fixer->fixFile();
			return $phpcsFile->fixer->getContents();
		}

		return null;
	}

	/**
	 * strip down the output to only the warnings
	 *
	 * @param string $outputStr
	 * @return string $outputStr
	 */
	private function prepareOutput( string $outputStr ): string {
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
