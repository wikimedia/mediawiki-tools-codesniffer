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

class TestHelper {

	protected $rootDir;

	protected $dirName;

	protected $phpcs;

	public function __construct() {
		$this->rootDir = dirname( __DIR__ );
		$this->dirName = basename( $this->rootDir );
		$this->phpcs = new PHP_CodeSniffer_CLI();
	}

	/**
	 * Run PHPCS on a file.
	 *
	 * @param string $file to run.
	 * @param string $standard to run against
	 * @return string The output from phpcs.
	 */
	public function runPhpCs( $file, $standard = '' ) {
		if ( empty( $standard ) ) {
			$standard = $this->rootDir . '/ruleset.xml';
		}
		$defaults = $this->phpcs->getDefaults();

		if (
			defined( 'PHP_CodeSniffer::VERSION' ) &&
			version_compare( PHP_CodeSniffer::VERSION, '1.5.0' ) != -1
		) {
			$standard = [ $standard ];
		}
		$options = [
				'encoding' => 'utf-8',
				'files' => [ $file ],
				'standard' => $standard,
			] + $defaults;

		ob_start();
		$this->phpcs->process( $options );
		$result = ob_get_contents();
		ob_end_clean();
		return $result;
	}

}
