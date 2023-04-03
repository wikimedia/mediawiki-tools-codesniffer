<?php
/**
 * Sniff to replace "list()" with "[ ]"
 */

namespace MediaWiki\Sniffs\Usage;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class ShortListSyntaxSniff implements Sniff {
	/**
	 * @inheritDoc
	 */
	public function register(): array {
		return [
			T_LIST,
		];
	}

	/**
	 * @param File $phpcsFile
	 * @param int $stackPtr The current token index.
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$fix = $phpcsFile->addFixableError(
			'Use short array syntax instead of list() statement',
			$stackPtr,
			'Found'
		);

		if ( $fix ) {
			$token = $phpcsFile->getTokens()[$stackPtr];
			if ( !isset( $token['parenthesis_opener'] ) ) {
				// Live coding
				return;
			}

			$phpcsFile->fixer->beginChangeset();
			$phpcsFile->fixer->replaceToken( $stackPtr, '' );
			$phpcsFile->fixer->replaceToken( $token['parenthesis_opener'], '[' );
			$phpcsFile->fixer->replaceToken( $token['parenthesis_closer'], ']' );
			$phpcsFile->fixer->endChangeset();
		}
	}
}
