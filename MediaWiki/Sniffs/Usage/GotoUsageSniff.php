<?php
/**
 * Report error when `goto` is used
 */

namespace MediaWiki\Sniffs\Usage;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class GotoUsageSniff implements Sniff {

	/**
	 * @return array
	 */
	public function register() {
		// As per https://www.mediawiki.org/wiki/Manual:Coding_conventions/PHP#Other
		return [
			T_GOTO
		];
	}

	/**
	 * @param File $phpcsFile File object.
	 * @param int $stackPtr The current token index.
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$error = 'Control statement "goto" must not be used.';
		$phpcsFile->addError( $error, $stackPtr, 'GotoUsage' );
	}
}
