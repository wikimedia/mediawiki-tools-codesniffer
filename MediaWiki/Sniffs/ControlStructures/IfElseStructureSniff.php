<?php
/**
 * Sniff to warn when else and elseif are formatted incorrectly:
 * Pass: } else {
 * Fail: }  else {
 * Pass: } elseif ( $a == 1 ) {
 * Fail: }\nelseif ( $a == 1 ) {
 */
// @codingStandardsIgnoreStart
class MediaWiki_Sniffs_ControlStructures_IfElseStructureSniff
	implements PHP_CodeSniffer_Sniff {
	// @codingStandardsIgnoreEnd
	public function register() {
		return [
			T_ELSE,
			T_ELSEIF,
		];
	}

	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		$prevToken = $tokens[$stackPtr - 1];
		$nextToken = $tokens[$stackPtr + 1];

		// single space expected before else and elseif structure
		if ( $prevToken['code'] !== T_WHITESPACE
			|| $prevToken['content'] !== " " ) {
			$fix = $phpcsFile->addFixableWarning(
				'Single space expected before "%s"',
				$stackPtr + 1,
				'SpaceBeforeElse',
				[ $tokens[$stackPtr]['content'] ]
			);
			if ( $fix === true ) {
				if ( $prevToken['code'] === T_CLOSE_CURLY_BRACKET ) {
					$phpcsFile->fixer->addContentBefore( $stackPtr, ' ' );
				} else {
					// Replace all previous whitespace with a space
					$phpcsFile->fixer->replaceToken( $stackPtr - 1, ' ' );
					for ( $i = 2; $tokens[$stackPtr - $i]['code'] === T_WHITESPACE; $i++ ) {
						$phpcsFile->fixer->replaceToken( $stackPtr - $i, '' );
					}
				}
			}
		}
		// space after elseif structure be processed in SpaceAfterControlStructureSniff
		if ( $tokens[$stackPtr]['code'] === T_ELSEIF ) {
			return;
		}
		// single space expected after else structure
		if ( $nextToken['code'] !== T_WHITESPACE
			|| $nextToken['content'] !== " " ) {
			$fix = $phpcsFile->addFixableWarning(
				'Single space expected after "%s"',
				$stackPtr + 1,
				'SpaceAfterElse',
				[ $tokens[$stackPtr]['content'] ]
			);
			if ( $fix === true ) {
				if ( $nextToken['code'] === T_OPEN_CURLY_BRACKET ) {
					$phpcsFile->fixer->addContent( $stackPtr, ' ' );
				} else {
					// Replace all after whitespace with a space
					$phpcsFile->fixer->replaceToken( $stackPtr + 1, ' ' );
					for ( $i = 2; $tokens[$stackPtr + $i]['code'] === T_WHITESPACE; $i++ ) {
						$phpcsFile->fixer->replaceToken( $stackPtr + $i, '' );
					}
				}
			}
		}
	}
}
