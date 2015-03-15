<?php
/**
 * Verify alternative syntax is not being used
 */
class MediaWiki_Sniffs_AlternativeSyntax_AlternativeSyntaxSniff implements PHP_CodeSniffer_Sniff {
	public function register() {
		// Per https://www.mediawiki.org/wiki/Manual:Coding_conventions/PHP#Alternative_syntax_for_control_structures
		return array(
			T_ENDDECLARE,
			T_ENDFOR,
			T_ENDFOREACH,
			T_ENDIF,
			T_ENDSWITCH,
			T_ENDWHILE,
		);
	}

	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		$error = 'Alternative syntax such as "%s" should not be used';
		$data = array( $tokens[$stackPtr]['content'] );
		$phpcsFile->addError( $error, $stackPtr, 'AlternativeSyntax', $data );
	}
}
