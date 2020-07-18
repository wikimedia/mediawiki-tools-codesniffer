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
use PHP_CodeSniffer\Reporter;
use PHP_CodeSniffer\Ruleset;

class Helper {

	protected $rootDir;

	public function __construct() {
		$this->rootDir = dirname( __DIR__ );
	}

	/**
	 * Run PHPCS on a file.
	 *
	 * @param string $file To run.
	 * @param string $standard To run against.
	 * @return string $result The output from phpcs.
	 */
	public function runPhpCs( $file, $standard = '' ) {
		if ( empty( $standard ) ) {
			$standard = $this->rootDir . '/ruleset.xml';
		}

		$config = new Config();
		$config->standards = [ $standard ];
		$config->files = [ $file ];
		$config->encoding = 'utf-8';
		$config->reports = [ 'full' => null ];
		$config->colors = false;
		$config->reportWidth = 0;
		$config->showSources = true;
		$config->tabWidth = 4;

		$ruleset = new Ruleset( $config );
		$dummy = new DummyFile( file_get_contents( $file ), $ruleset, $config );
		$reporter = new Reporter( $config );
		$dummy->process();
		$reporter->cacheFileReport( $dummy );
		ob_start();
		$reporter->printReport( 'full' );
		$result = ob_get_contents();
		ob_end_clean();
		return $result;
	}

	/**
	 * @param string $file The path of file.
	 * @param string $standard The standard string.
	 * @return string
	 */
	public function runPhpCbf( $file, $standard = '' ) {
		if ( empty( $standard ) ) {
			$standard = $this->rootDir . '/ruleset.xml';
		}
		$config = new Config();
		$config->standards = [ $standard ];
		$config->files = [ $file ];
		$config->encoding = 'utf-8';
		$config->tabWidth = 4;

		$ruleset = new Ruleset( $config );
		$dummy = new DummyFile( file_get_contents( $file ), $ruleset, $config );

		$dummy->process();
		if ( $dummy->getFixableCount() ) {
			$dummy->fixer->fixFile();
			return $dummy->fixer->getContents();
		} else {
			return file_get_contents( $file );
		}
	}
}
