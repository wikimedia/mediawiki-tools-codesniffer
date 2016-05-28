<?php
/**
 * Report error when `goto` is used
 */
// @codingStandardsIgnoreStart
class MediaWiki_Sniffs_Usage_GotoUsageSniff implements PHP_CodeSniffer_Sniff {
	// @codingStandardsIgnoreEnd

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
	 * @param PHP_CodeSniffer_File $phpcsFile PHP_CodeSniffer_File object.
	 * @param int $stackPtr The current token index.
	 * @return void
	 */
	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		$error = 'Control statement "goto" must not be used.';
		$phpcsFile->addError( $error, $stackPtr, 'GotoUsage' );
	}
}
