<?php
/**
 * Make sure calling functions is spacey:
 * $this->foo( $arg, $arg2 );
 * wfFoo( $arg, $arg2 );
 *
 * But, wfFoo() is ok.
 *
 * Also disallow wfFoo( ) and wfFoo(  $param )
 */
// @codingStandardsIgnoreStart
class MediaWiki_Sniffs_WhiteSpace_SpaceyParenthesisSniff
	implements PHP_CodeSniffer_Sniff {
	// @codingStandardsIgnoreEnd
	public function register() {
		return [
			T_OPEN_PARENTHESIS,
			T_CLOSE_PARENTHESIS,
			T_OPEN_SHORT_ARRAY,
			T_CLOSE_SHORT_ARRAY
		];
	}

	private function isOpen( $token ) {
		return $token === T_OPEN_PARENTHESIS
			|| $token === T_OPEN_SHORT_ARRAY;
	}

	private function isClosed( $token ) {
		return $token === T_CLOSE_PARENTHESIS
			|| $token === T_CLOSE_SHORT_ARRAY;
	}

	private function isParenthesis( $token ) {
		return $token === T_OPEN_PARENTHESIS
			|| $token === T_CLOSE_PARENTHESIS;
	}

	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		$currentToken = $tokens[$stackPtr];

		if ( $this->isOpen( $currentToken['code'] )
			&& $tokens[$stackPtr - 1]['code'] === T_WHITESPACE
			&& ( $tokens[$stackPtr - 2]['code'] === T_STRING
				|| $tokens[$stackPtr - 2]['code'] === T_ARRAY ) ) {
			// String (or 'array') followed by whitespace followed by
			// opening brace is probably a function call.
			if ( $this->isParenthesis( $currentToken['code'] ) ) {
				$msg = 'opening parenthesis of function call';
			} else {
				$msg = 'opening bracket of array';
			}
			$fix = $phpcsFile->addFixableWarning(
				'Space found before ' . $msg,
				$stackPtr - 1,
				'SpaceBeforeOpeningParenthesis'
			);
			if ( $fix === true ) {
				$phpcsFile->fixer->replaceToken( $stackPtr - 1, '' );
			}
		}

		// Check for space between parentheses without any arguments
		if ( $this->isOpen( $currentToken['code'] )
			&& $tokens[$stackPtr + 1]['code'] === T_WHITESPACE
			&& $this->isClosed( $tokens[$stackPtr + 2]['code'] ) ) {
			if ( $this->isParenthesis( $currentToken['code'] ) ) {
				$msg = 'parentheses';
			} else {
				$msg = 'brackets';
			}
			$fix = $phpcsFile->addFixableWarning(
				'Unnecessary space found within ' . $msg,
				$stackPtr + 1,
				'UnnecessarySpaceBetweenParentheses'
			);
			if ( $fix === true ) {
				$phpcsFile->fixer->replaceToken( $stackPtr + 1, '' );
			}
			return;
		}

		// Same check as above, but ignore since it was already processed
		if ( $this->isClosed( $currentToken['code'] )
			&& $tokens[$stackPtr - 1]['code'] === T_WHITESPACE
			&& $this->isOpen( $tokens[$stackPtr - 2]['code'] ) ) {
			return;
		}

		if ( $this->isOpen( $currentToken['code'] ) ) {
			$this->processOpenParenthesis( $phpcsFile, $tokens, $stackPtr );
		} else {
			// T_CLOSE_PARENTHESIS
			$this->processCloseParenthesis( $phpcsFile, $tokens, $stackPtr );
		}
	}

	protected function processOpenParenthesis( PHP_CodeSniffer_File $phpcsFile, $tokens, $stackPtr ) {
		$nextToken = $tokens[$stackPtr + 1];
		// No space or not single space
		if ( ( $nextToken['code'] === T_WHITESPACE &&
				strpos( $nextToken['content'], "\n" ) === false
				&& $nextToken['content'] != ' ' )
			|| ( !$this->isClosed( $nextToken['code'] ) && $nextToken['code'] !== T_WHITESPACE ) ) {
			$fix = $phpcsFile->addFixableWarning(
				'Single space expected after opening parenthesis',
				$stackPtr + 1,
				'SingleSpaceAfterOpenParenthesis'
			);
			if ( $fix === true ) {
				if ( $nextToken['code'] === T_WHITESPACE
					&& strpos( $nextToken['content'], "\n" ) === false
					&& $nextToken['content'] != ' ' ) {
					$phpcsFile->fixer->replaceToken( $stackPtr + 1, ' ' );
				} else {
					$phpcsFile->fixer->addContent( $stackPtr, ' ' );
				}
			}
		}
	}

	protected function processCloseParenthesis( PHP_CodeSniffer_File $phpcsFile, $tokens, $stackPtr ) {
		$previousToken = $tokens[$stackPtr - 1];

		if ( $this->isOpen( $previousToken['code'] )
			|| ( $previousToken['code'] === T_WHITESPACE
				&& $previousToken['content'] === ' ' )
			|| ( $previousToken['code'] === T_COMMENT
				&& substr( $previousToken['content'], -1, 1 ) === "\n" ) ) {
			// If previous token was
			// '(' or ' ' or a comment ending with a newline
			return;
		}

		// If any of the whitespace tokens immediately before this token have a newline
		$ptr = $stackPtr - 1;
		while ( $tokens[$ptr]['code'] === T_WHITESPACE ) {
			if ( strpos( $tokens[$ptr]['content'], "\n" ) !== false ) {
				return;
			}
			$ptr--;
		}

		// If the comment before all the whitespaces immediately preceding the ')' ends with a newline
		if ( $tokens[$ptr]['code'] === T_COMMENT
			&& substr( $tokens[$ptr]['content'], -1, 1 ) === "\n" ) {
			return;
		}

		$fix = $phpcsFile->addFixableWarning(
			'Single space expected before closing parenthesis',
			$stackPtr,
			'SingleSpaceBeforeCloseParenthesis'
		);
		if ( $fix === true ) {
			if ( $previousToken['code'] === T_WHITESPACE ) {
				$phpcsFile->fixer->replaceToken( $stackPtr - 1, ' ' );
			} else {
				$phpcsFile->fixer->addContentBefore( $stackPtr, ' ' );
			}
		}
	}
}
