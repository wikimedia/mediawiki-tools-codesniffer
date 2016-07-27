<?php
/**
 * Report warnings when $dbr->query() is used instead of $dbr->select()
 *
 */

// @codingStandardsIgnoreStart
class MediaWiki_Sniffs_Usage_DbrQueryUsageSniff implements PHP_CodeSniffer_Sniff {
	// @codingStandardsIgnoreEnd

	/**
	 * @return array
	 */
	public function register() {
		return [
			T_VARIABLE,
			T_OBJECT_OPERATOR,
			T_STRING
		];
	}

	/**
	 * @param PHP_CodeSniffer_File $phpcsFile PHP_CodeSniffer_File object.
	 * @param int $stackPtr The current token index.
	 * @return void
	 */
	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		$currToken = $tokens[$stackPtr];

		if ( $currToken['code'] === T_OBJECT_OPERATOR ) {
			$dbrPtr = $phpcsFile->findPrevious( T_VARIABLE, $stackPtr );
			$methodPtr = $phpcsFile->findNext( T_STRING, $stackPtr );

			if ( $tokens[$dbrPtr]['content'] === '$dbr'
				&& $tokens[$methodPtr]['content'] === 'query' ) {
				$phpcsFile->addWarning(
					'Call $dbr->select() wrapper instead of $dbr->query()',
					$stackPtr,
					'DbrQueryFound'
				);
			}
		}
	}
}
