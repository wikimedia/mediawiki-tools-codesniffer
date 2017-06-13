<?php
/**
 * This file was copied from CakePhps codesniffer tests before being modified
 * File: http://git.io/vkioq
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
 *  - runPhpCs takes a second parameter $standard to override the default
 */

namespace MediaWiki\Sniffs\Tests;

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Files\DummyFile;
use PHP_CodeSniffer\Reporter;
use PHP_CodeSniffer\Ruleset;

class TestHelper {

	protected $rootDir;

	protected $dirName;

	protected $phpcs;

	public function __construct() {
		$this->rootDir = dirname( __DIR__ );
		$this->dirName = basename( $this->rootDir );
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

}
