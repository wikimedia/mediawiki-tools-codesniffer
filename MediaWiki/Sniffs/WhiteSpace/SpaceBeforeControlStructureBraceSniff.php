<?php
/**
 * make sure a space between closing parenthesis and opening brace
 * during if,while,for,foreach,switch,catch statement
 * fail: if ( $a == 1 ){
 * fail: if ( $a == 1 )\eol\t{
 * fail: switch ( $a ){
 * pass: if ( $a == 1 ) {
 * pass: switch ( $a ) {
 */

namespace MediaWiki\Sniffs\WhiteSpace;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class SpaceBeforeControlStructureBraceSniff implements Sniff {

	/**
	 * @return array
	 */
	public function register() {
		return [
			T_IF,
			T_ELSEIF,
			T_WHILE,
			T_FOR,
			T_FOREACH,
			T_SWITCH,
			T_CATCH,
		];
	}
	/**
	 * @param File $phpcsFile File object.
	 * @param int $stackPtr The index of current token.
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		if ( !isset( $tokens[$stackPtr]['scope_opener'] ) ) {
			return;
		}
		$openBrace = $tokens[$stackPtr]['scope_opener'];
		if ( $tokens[$openBrace]['content'] !== '{' ) {
			return;
		}
		if ( $tokens[$stackPtr + 1]['code'] !== T_WHITESPACE
			|| $tokens[$stackPtr + 2]['code'] !== T_OPEN_PARENTHESIS
			|| $tokens[$stackPtr + 2]['parenthesis_closer'] === null
		) {
			return;
		}

		$closeBracket = $tokens[$stackPtr + 2]['parenthesis_closer'];
		$closeBracketLine = $tokens[$closeBracket]['line'];
		$openBraceLine = $tokens[$openBrace]['line'];
		$lineDifference = ( $openBraceLine - $closeBracketLine );
		if ( $lineDifference > 0 ) {
			// if brace on new line
			$this->processLineDiff( $phpcsFile, $openBrace, $closeBracket, $stackPtr );
		} else {
			// if brace on the same line as closing parenthesis
			$this->processLineSame( $phpcsFile, $openBrace, $closeBracket, $stackPtr );
		}
		$this->processEmptyLine( $phpcsFile, $closeBracket + 2, $stackPtr );
	}

	/**
	 * Process The close parenthesis on the same line as open brace.
	 *
	 * @param File $phpcsFile File object.
	 * @param int $openBrace The index of open brace.
	 * @param int $closeBracket The index of close bracket.
	 * @param int $stackPtr The index of current token.
	 * @return void
	 */
	protected function processLineDiff( File $phpcsFile, $openBrace,
		$closeBracket, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		$phpcsFile->recordMetric( $stackPtr, 'Control Structs opening brace placement', 'new line' );
		$error = 'Opening brace should be on the same line as the declaration';
		$fix = $phpcsFile->addFixableError( $error, $openBrace, 'BraceOnNewLine' );
		if ( $fix === true ) {
			$phpcsFile->fixer->beginChangeset();
			$i = $closeBracket + 1;
			for ( $i; $i < $openBrace; $i++ ) {
				$phpcsFile->fixer->replaceToken( $i, '' );
			}
			$phpcsFile->fixer->addContent( $closeBracket, ' {' );
			$phpcsFile->fixer->replaceToken( $openBrace, '' );
			$phpcsFile->fixer->addNewLine( $closeBracket + 2 );
			$phpcsFile->fixer->endChangeset();
		}
	}

	/**
	 * Process The close parenthesis on the different line with open brace.
	 *
	 * @param File $phpcsFile File object.
	 * @param int $openBrace The index of open brace.
	 * @param int $closeBracket The index of close bracket.
	 * @param int $stackPtr The index of current token.
	 * @return void
	 */
	protected function processLineSame( File $phpcsFile, $openBrace,
		$closeBracket, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		$content = $phpcsFile->getTokensAsString( $closeBracket + 1, $openBrace - $closeBracket - 1 );
		$length = strlen( $content );
		if ( $length === 1 && $tokens[$closeBracket + 1]['content'] === ' ' ) {
			return;
		} else {
			$warning = 'Expected 1 space between closing parenthesis and opening brace; find %s characters';
			$fix = $phpcsFile->addFixableWarning( $warning, $openBrace, 'SpaceBeforeControl', [ $length ] );
			if ( $fix === true ) {
				$phpcsFile->fixer->beginChangeset();
				$phpcsFile->fixer->replaceToken( $openBrace, '' );
				$phpcsFile->fixer->addContent( $closeBracket, ' {' );
				$next = $phpcsFile->findNext( T_WHITESPACE, $closeBracket + 1, null, true );
				for ( $i = ( $closeBracket + 1 ); $i < $next; $i++ ) {
					$phpcsFile->fixer->replaceToken( $i, '' );
				}
				$phpcsFile->fixer->endChangeset();
			}
		}
	}
	/**
	 * Process empty line after the open brace.
	 *
	 * @param File $phpcsFile File object.
	 * @param int $openBrace The index of open brace.
	 * @param int $stackPtr The index of current token.
	 * @return void
	 */
	protected function processEmptyLine( File $phpcsFile, $openBrace, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		$next = $phpcsFile->findNext( T_WHITESPACE, $openBrace + 2, null, false );
		$found = strpos( $tokens[$next]['content'], $phpcsFile->eolChar );
		if ( $tokens[$next]['code'] === T_WHITESPACE && $found !== false ) {
			$warning = 'empty lines should not exist after brace of "%s"';
			$content = $tokens[$stackPtr]['content'];
			$fix = $phpcsFile->addFixableWarning( $warning, $openBrace, 'EmptyLines', [ $content ] );
			if ( $fix === true ) {
				$phpcsFile->fixer->replaceToken( $next, '' );
			}
		}
	}
}
