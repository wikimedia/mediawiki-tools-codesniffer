<?php
/**
 * Check to see if there's any character before php open tag <? or <?php
 */
// @codingStandardsIgnoreStart
class MediaWiki_Sniffs_ExtraCharacters_CharacterBeforePHPOpeningTagSniff
	implements PHP_CodeSniffer_Sniff {
	// @codingStandardsIgnoreEnd
	public function register() {
		return array( T_OPEN_TAG );
	}
	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		// do nothing if opening tag is the first character
		if ( $stackPtr == 0 ) {
			return;
		}
		$tokens = $phpcsFile->getTokens();
		$isNotFirstOpenTag = $phpcsFile->findPrevious(
			array( T_OPEN_TAG ),
			( $stackPtr - 1 ),
			null,
			false
		);
		// some other character beginning file
		if ( $isNotFirstOpenTag === false ) {
			$error = 'Extra character found before first <?';
			$phpcsFile->addError( $error, $stackPtr, 'Found' );
		}
	}
}
