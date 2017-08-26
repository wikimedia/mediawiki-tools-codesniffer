<?php
/**
 * Checks that only one class, trait, and interface is declared per file.
 *
 * @author    Andy Grunwald <andygrunwald@gmail.com>
 * @copyright 2010-2014 Andy Grunwald
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 */

namespace MediaWiki\Sniffs\Files;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class OneClassPerFileSniff implements Sniff {

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return [ T_CLASS, T_INTERFACE, T_TRAIT ];
	}

	/**
	 * Processes this sniff, when one of its tokens is encountered.
	 *
	 * @param File $phpcsFile File being scanned
	 * @param int $stackPtr Position
	 *
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$nextClass = $phpcsFile->findNext( $this->register(), ( $stackPtr + 1 ) );
		if ( $nextClass !== false ) {
			$error = 'Only one class is allowed in a file';
			$phpcsFile->addError( $error, $nextClass, 'MultipleFound' );
		}
	}

}
