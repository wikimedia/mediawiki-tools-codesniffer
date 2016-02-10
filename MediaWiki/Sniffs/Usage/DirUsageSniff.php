<?php
/**
 * Report error when dirname(__FILE__) is used instead of __DIR__
 *
 * Fail: dirname( __FILE__ )
 * Pass: dirname( __FILE__ . "/.." )
 * Pass: dirname( __FILE__, 2 )
 * Pass: dirname( joinpaths( __FILE__, ".." ) )
 * Pass: $abc->dirname( __FILE__ )
 * Pass: parent::dirname( __FILE__ )
 */

// @codingStandardsIgnoreStart
class MediaWiki_Sniffs_Usage_DirUsageSniff implements PHP_CodeSniffer_Sniff {
	// @codingStandardsIgnoreEnd
	public function register() {
		// As per https://www.mediawiki.org/wiki/Manual:Coding_conventions/PHP#Other
		return [ T_STRING ];
	}

	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		$ignore = [
				   T_DOUBLE_COLON    => true,
				   T_OBJECT_OPERATOR => true,
				   T_FUNCTION        => true,
				   T_CONST           => true,
				  ];

		// Check if the function is dirname()
		if ( strtolower( $tokens[$stackPtr]['content'] ) != "dirname" ) {
			return;
		}

		// Check if it's a PHP function
		$prevToken = $phpcsFile->findPrevious( T_WHITESPACE, ( $stackPtr - 1 ), null, true );
		if ( isset( $ignore[$tokens[$prevToken]['code']] ) === true ) {
			return;
		}

		// Find the paranthesis for the function
		$nextToken = $phpcsFile->findNext( T_WHITESPACE, ( $stackPtr + 1 ), null, true );
		if ( $tokens[$nextToken]['code'] !== T_OPEN_PARENTHESIS ) {
			return;
		}

		// Check if __FILE__ is inside it
		$nextToken = $phpcsFile->findNext( T_WHITESPACE, ( $nextToken + 1 ), null, true );
		if ( $tokens[$nextToken]['code'] !== T_FILE ) {
			return;
		}

		// Find close paranthesis
		$nextToken = $phpcsFile->findNext( T_WHITESPACE, ( $nextToken + 1 ), null, true );
		if ( $tokens[$nextToken]['code'] !== T_CLOSE_PARENTHESIS ) {
			return;
		}

		$fix = $phpcsFile->addFixableError(
			'Use __DIR__ constant instead of calling dirname(__FILE__)',
			$stackPtr,
			'FunctionFound'
		);
		if ( $fix === true ) {
			$curToken = $stackPtr;
			while ( $curToken <= $nextToken ) {
				if ( $tokens[$curToken]['code'] === T_FILE ) {
					$phpcsFile->fixer->replaceToken( $curToken, '__DIR__' );
				} else {
					$phpcsFile->fixer->replaceToken( $curToken, '' );
				}
				$curToken += 1;
			}
		}
	}
}
