<?php
/**
 * Verify alternative syntax is not being used
 */
// @codingStandardsIgnoreStart
class MediaWiki_Sniffs_AlternativeSyntax_AlternativeSyntaxSniff
	implements PHP_CodeSniffer_Sniff {
	// @codingStandardsIgnoreEnd

	/**
	 * @return array
	 */
	public function register() {
		// Per https://www.mediawiki.org/wiki/Manual:Coding_conventions/PHP
		// section on alternative syntax.
		return [
			T_ENDDECLARE,
			T_ENDFOR,
			T_ENDFOREACH,
			T_ENDIF,
			T_ENDSWITCH,
			T_ENDWHILE,
		];
	}

	/**
	 * @param PHP_CodeSniffer_File $phpcsFile PHP_CodeSniffer_File object.
	 * @param int $stackPtr The current token index.
	 * @return void
	 */
	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		$error = 'Alternative syntax such as "%s" should not be used';
		$data = [ $tokens[$stackPtr]['content'] ];
		$phpcsFile->addWarning( $error, $stackPtr, 'AlternativeSyntax', $data );
	}
}
