<?php
/**
 * Sniff to add consisntent spacing in typehints - we have
 * the closing parenthesis of the function, no space, the colon,
 * a space, the typehint, and then either a semicolon (for interfaces
 * or abstract methods) or a space and the openening curly bracket:
 *
 * function foo(): returnTypehint {
 * function bar(): returnTypehint;
 *
 * @author DannyS712
 */

namespace MediaWiki\Sniffs\WhiteSpace;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

class FunctionTypehintWhiteSpaceSniff implements Sniff {
	/**
	 * @inheritDoc
	 */
	public function register(): array {
		return [
			T_FUNCTION,
		];
	}

	/**
	 * @param File $phpcsFile
	 * @param int $stackPtr The current token index.
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		if ( !isset( $tokens[$stackPtr]['parenthesis_closer'] ) ) {
			// Live coding
			return;
		}

		$closeParenthesisPtr = $tokens[$stackPtr]['parenthesis_closer'];

		$functionInfo = $phpcsFile->getMethodProperties( $stackPtr );
		if ( $functionInfo['return_type_token'] === false ) {
			// No typehint
			return;
		}
		$returnTypePtr = $functionInfo['return_type_token'];

		// the return_type_token doesn't include the ? for nullability if the return type
		// is nullable, but we want to count that as, so move back one
		if ( $functionInfo['nullable_return_type'] ) {
			$returnTypePtr--;
		}

		// We fix the issues before the typehint *after* the issues after the typehint,
		// to avoid changing the token numbers
		$fixBeforeTypehint = false;
		// Check tokens between close parentheses and start of return typehint
		if ( !(
			// easiest check: correct number token
			// all the others are in case someone has something weird
			// starting from $closeParenthesisPtr, tokens should be
			//   1) colon
			//   2) space
			//   3) typehint
			$returnTypePtr === $closeParenthesisPtr + 3 &&
			isset( $tokens[$closeParenthesisPtr + 1] ) &&
			$tokens[$closeParenthesisPtr + 1]['code'] === T_COLON &&
			isset( $tokens[$closeParenthesisPtr + 2] ) &&
			$tokens[$closeParenthesisPtr + 2]['code'] === T_WHITESPACE &&
			$tokens[$closeParenthesisPtr + 2]['content'] === ' '
		) ) {
			$fixBeforeTypehint = $phpcsFile->addFixableWarning(
				'There should be no space between the closing parenthesis ' .
					'and the colon, and a single space between the colon and ' .
					'the typehint',
				$stackPtr,
				'SpacingBefore'
			);
		}

		// Find out what is after the typehint
		$returnTypeEndPtr = $functionInfo['return_type_end_token'];
		$afterTypehintEnd = $phpcsFile->findNext(
			Tokens::$emptyTokens,
			$returnTypeEndPtr + 1,
			null,
			true
		);

		if ( $afterTypehintEnd !== false ) {
			// Could be false in live coding
			$nextToken = $tokens[$afterTypehintEnd];
			if ( $nextToken['code'] === T_SEMICOLON &&
				$afterTypehintEnd !== ( $returnTypeEndPtr + 1 )
			) {
				// Typehint should be directly followed by semicolon, but it wasn't
				// Should also be covered by Squiz.WhiteSpace.SemicolonSpacing, but
				// whats the harm in double checking, in case that sniff is disabled?
				$fix = $phpcsFile->addFixableWarning(
					'There should be nothing between the typehint and the following ' .
						'semicolon for methods with no body',
					$stackPtr,
					'SpacingAfterNoBody'
				);
				if ( $fix ) {
					$phpcsFile->fixer->beginChangeset();
					for ( $i = $returnTypeEndPtr + 1; $i < $afterTypehintEnd; $i++ ) {
						$phpcsFile->fixer->replaceToken( $i, '' );
					}
					$phpcsFile->fixer->endChangeSet();
				}
			} elseif (
				$nextToken['code'] === T_OPEN_CURLY_BRACKET &&
				!(
					// There should be the typehint, then a single space, then
					// that opening bracket
					$afterTypehintEnd === ( $returnTypeEndPtr + 2 ) &&
					isset( $tokens[ $returnTypeEndPtr + 1 ] ) &&
					$tokens[ $returnTypeEndPtr + 1 ]['code'] === T_WHITESPACE &&
					$tokens[ $returnTypeEndPtr + 1 ]['content'] === ' '
				)
			) {
				$fix = $phpcsFile->addFixableWarning(
					'There should be a single space between the typehint and the ' .
						'opening curly bracket for a function',
					$stackPtr,
					'SpacingAfterWithBody'
				);
				if ( $fix ) {
					$phpcsFile->fixer->beginChangeset();
					// remove everything between the end of the typehint and the {
					for ( $i = $returnTypeEndPtr + 1; $i < $afterTypehintEnd; $i++ ) {
						$phpcsFile->fixer->replaceToken( $i, '' );
					}
					// and add back a single space
					$phpcsFile->fixer->addContentBefore( $afterTypehintEnd, ' ' );
					$phpcsFile->fixer->endChangeSet();
				}
			}
		}

		// Now fix the issues before the typehint
		if ( $fixBeforeTypehint ) {
			$phpcsFile->fixer->beginChangeset();
			// remove everything between the end of the function and the typehint
			for ( $i = $closeParenthesisPtr + 1; $i < $returnTypePtr; $i++ ) {
				$phpcsFile->fixer->replaceToken( $i, '' );
			}
			// and add back the correct content
			$phpcsFile->fixer->addContentBefore( $returnTypePtr, ': ' );
			$phpcsFile->fixer->endChangeSet();
		}
	}
}
