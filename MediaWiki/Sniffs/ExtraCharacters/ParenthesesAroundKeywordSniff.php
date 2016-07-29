<?php
/**
 * Sniff to warn when keywords are used as functions, such as:
 * Pass: clone $obj
 * Fail: clone( $obj )
 * Pass: require 'path/to/file.php';
 * Fail: require( 'path/to/file' );
 *
 * Covers:
 * * clone
 * * require
 * * require_once
 * * include
 * * include_once
 */
// @codingStandardsIgnoreStart
class MediaWiki_Sniffs_ExtraCharacters_ParenthesesAroundKeywordSniff
	implements PHP_CodeSniffer_Sniff {
	// @codingStandardsIgnoreEnd

	/**
	 * @return array
	 */
	public function register() {
		return [
			T_CLONE,
			T_REQUIRE,
			T_REQUIRE_ONCE,
			T_INCLUDE,
			T_INCLUDE_ONCE,
		];
	}

	/**
	 * @param PHP_CodeSniffer_File $phpcsFile PHP_CodeSniffer_File object.
	 * @param int $stackPtr The current token index.
	 * @return void
	 */
	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		$nextToken = $tokens[$stackPtr + 1];
		$nextSecondToken = $tokens[$stackPtr + 2];

		if (
			(
				$nextToken['code'] === T_WHITESPACE &&
				$nextSecondToken['code'] === T_OPEN_PARENTHESIS
			) ||
			$nextToken['code'] === T_OPEN_PARENTHESIS
		) {
			$fix = $phpcsFile->addFixableWarning(
				$tokens[$stackPtr]['content'] . ' keyword must not be used as a function.',
				$stackPtr + 1,
				'ParenthesesAroundKeywords' );
			if ( $fix === true ) {
				if ( $nextToken['code'] === T_OPEN_PARENTHESIS ) {
					$phpcsFile->fixer->replaceToken( $stackPtr + 1, '' );
				} else {
					$phpcsFile->fixer->replaceToken( $stackPtr + 2, '' );
					if ( $tokens[$stackPtr + 3]['code'] === T_WHITESPACE ) {
						$phpcsFile->fixer->replaceToken( $stackPtr + 3, '' );
					}
				}
				// remove the closing parenthesis
				$i = 0;
				while ( $tokens[$stackPtr + $i]['code'] !== T_CLOSE_PARENTHESIS ) {
					$i++;
				}
				$phpcsFile->fixer->replaceToken( $stackPtr + $i, '' );
			}
		}
	}
}
