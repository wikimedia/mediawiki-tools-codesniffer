<?php
/**
 * make sure a space before class open brace.
 * fail: class TestClass\t{
 * fail: class TestClass{
 * fail: class TestClass   {
 * pass: class TestClass {
 */

namespace MediaWiki\Sniffs\WhiteSpace;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class SpaceBeforeClassBraceSniff implements Sniff {
	/**
	 * @return array
	 */
	public function register() {
		return [
			T_CLASS,
			T_INTERFACE,
			T_TRAIT,
		];
	}

	/**
	 * @param  File $phpcsFile File object.
	 * @param  int $stackPtr The index of current token.
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		if ( $tokens[$stackPtr]['scope_opener'] === false ) {
			return;
		}
		$openBrace = $tokens[$stackPtr]['scope_opener'];
		$pre = $phpcsFile->findPrevious( T_WHITESPACE, ( $openBrace -1 ), null, true );
		$warning = 'Expected 1 space before class open brace and should be same line.find %s';
		$spaceCount = 0;
		for ( $start = $pre + 1; $start < $openBrace; $start++ ) {
			$content = $tokens[$start]['content'];
			$contentSize = strlen( $content );
			$spaceCount += $contentSize;
		}

		if ( $spaceCount !== 1 ||
			$tokens[$openBrace]['line'] !== $tokens[$pre]['line']
			) {
			$fix = $phpcsFile->addFixableWarning(
				$warning,
				$openBrace,
				'NoSpaceBeforeBrace',
				[ $spaceCount ]
			);
			if ( $fix === true ) {
				$phpcsFile->fixer->beginChangeset();
				$phpcsFile->fixer->replaceToken( $openBrace, '' );
				$phpcsFile->fixer->addContent( $pre, ' {' );
				for ( $i = ( $pre + 1 ); $i < $openBrace; $i++ ) {
					$phpcsFile->fixer->replaceToken( $i, '' );
				}
				$phpcsFile->fixer->endChangeset();
			}
		}
	}
}
