<?php
/**
 * Detect and fix the inline comments start or end with multiple asterisks.
 * Fail: /*** Comment here *\/
 * Fail: /*** Comments here again ***\/
 * Pass: /* Your comments here *\/
 */

namespace MediaWiki\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class IllegalSingleLineCommentSniff implements Sniff {

	/**
	 * @return array
	 */
	public function register() {
		return [
			T_COMMENT
		];
	}

	/**
	 * @param File $phpcsFile File object.
	 * @param int $stackPtr The index of current token.
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		$currentToken = $tokens[$stackPtr];

		if ( substr( $currentToken['content'], 0, 2 ) === '/*' ) {
			// Possible inline comment
			if ( strrpos( $currentToken['content'], '*/' ) === false ) {
				// Whether it's a comment across multiple lines
				$numOfTokens = count( $tokens );
				for ( $i = $stackPtr + 1; $i < $numOfTokens; $i++ ) {
					$token = $tokens[$i];
					if ( strpos( $token['content'], '/*' ) === false &&
						strrpos( $token['content'], '*/' ) !== false
					) {
						return;
					}
				}
				$fix = $phpcsFile->addFixableError(
					'Missing proper ending of a single line comment',
					$stackPtr,
					'MissingCommentEndding'
				);
				if ( $fix === true ) {
					$phpcsFile->fixer->replaceToken(
						$stackPtr,
						str_replace( '\n', ' */', rtrim( $currentToken['content'] ) )
					);
				}
			} else {
				// Determine whether multiple "*" appears right after the "/*"
				if ( preg_match( '/\/(\*){2,}/', $currentToken['content'] ) !== 0 ) {
					$fix = $phpcsFile->addFixableWarning(
						'Invalid start of a single line comment',
						$stackPtr,
						'IllegalSingleLineCommentStart'
					);
					if ( $fix === true ) {
						$phpcsFile->fixer->replaceToken(
							$stackPtr,
							preg_replace( '/\/(\*){2,}/', '/*', $currentToken['content'] )
						);
					}
				}
				// Determine whether multiple "*" appears right before the "*/"
				if ( preg_match( '/(\*){2,}\//', $currentToken['content'] ) !== 0 ) {
					$fix = $phpcsFile->addFixableWarning(
						'Invalid end of a single line comment',
						$stackPtr,
						'IllegalSingleLineCommentEnd'
					);
					if ( $fix === true ) {
						$phpcsFile->fixer->replaceToken(
							$stackPtr,
							preg_replace( '/(\*){2,}\//', '*/', $currentToken['content'] )
						);
					}
				}
			}
		}
	}
}
