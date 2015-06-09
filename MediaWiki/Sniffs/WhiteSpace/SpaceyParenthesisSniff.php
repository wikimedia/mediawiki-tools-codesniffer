<?php
/**
 * Make sure calling functions is spacey:
 * $this->foo( $arg, $arg2 );
 * wfFoo( $arg, $arg2 );
 *
 * But, wfFoo() is ok.
 */
// @codingStandardsIgnoreStart
class MediaWiki_Sniffs_WhiteSpace_SpaceyParenthesisSniff
	implements PHP_CodeSniffer_Sniff {
	// @codingStandardsIgnoreEnd
	public function register() {
		return array(
			T_OPEN_PARENTHESIS,
			T_CLOSE_PARENTHESIS,
		);
	}

	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		$currentToken = $tokens[$stackPtr];

		if ( $currentToken['code'] === T_OPEN_PARENTHESIS
			&& $tokens[$stackPtr - 1]['code'] === T_WHITESPACE
			&& ( $tokens[$stackPtr - 2]['code'] === T_STRING
				|| $tokens[$stackPtr - 2]['code'] === T_ARRAY ) ) {
			// String (or 'array') followed by whitespace followed by
			// opening brace is probably a function call.
			$phpcsFile->addWarning(
				'Space found before opening parenthesis of function call',
				$stackPtr - 1,
				'SpaceBeforeOpeningParenthesis'
			);
		}

		if ( $currentToken['code'] === T_OPEN_PARENTHESIS ) {
			$this->processOpenParenthesis( $phpcsFile, $tokens, $stackPtr );
		} else {
			// T_CLOSE_PARENTHESIS
			$this->processCloseParenthesis( $phpcsFile, $tokens, $stackPtr );
		}
	}

	protected function processOpenParenthesis( PHP_CodeSniffer_File $phpcsFile, $tokens, $stackPtr ) {
		$nextToken = $tokens[$stackPtr + 1];
		if ( $nextToken['code'] === T_CLOSE_PARENTHESIS || $nextToken['code'] === T_WHITESPACE ) {
			return;
		}

		$phpcsFile->addWarning(
			'No space after parenthesis',
			$stackPtr + 1,
			'Open'
		);
	}

	protected function processCloseParenthesis( PHP_CodeSniffer_File $phpcsFile, $tokens, $stackPtr ) {
		$previousToken = $tokens[$stackPtr - 1];
		if ( $previousToken['code'] === T_OPEN_PARENTHESIS
			|| $previousToken['code'] === T_WHITESPACE
			|| ( $previousToken['code'] === T_COMMENT
			&& substr( $previousToken['content'], -1, 1 ) === "\n" ) ) {
			return;
		}

		$phpcsFile->addWarning(
			'No space before parenthesis',
			$stackPtr - 1,
			'Close'
		);

	}
}
