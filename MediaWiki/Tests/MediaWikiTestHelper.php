<?php

namespace MediaWiki\Sniffs\Tests;

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Files\DummyFile;
use PHP_CodeSniffer\Ruleset;

class MediaWikiTestHelper extends TestHelper {

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
