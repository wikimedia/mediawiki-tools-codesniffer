<?php

namespace MediaWiki\Sniffs\WhiteSpace;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Complain about empty lines between the different `use` statements at the top
 * of a file.
 *
 * We deliberately *do* check for multiple empty lines and remove them all,
 * even though MultipleEmptyLinesSniff takes care of that too.
 *
 * @author DannyS712
 * @license GPL-2.0-or-later
 */
class EmptyLinesBetweenUseSniff implements Sniff {

	/**
	 * @inheritDoc
	 */
	public function register(): array {
		return [ T_USE ];
	}

	/**
	 * @param File $phpcsFile
	 * @param int $stackPtr The current token index.
	 * @return int
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		// In case this is a `use` of a class (or constant or function) within
		// a bracketed namespace rather than in the global scope, update the end
		// accordingly
		$useScopeEnd = $phpcsFile->numTokens;

		if ( !empty( $tokens[$stackPtr]['conditions'] ) ) {
			// We only care about use statements in the global scope, or the
			// equivalent for bracketed namespace (use statements in the namespace
			// and not in any class, etc.)
			$scope = array_key_first( $tokens[$stackPtr]['conditions'] );
			if ( count( $tokens[$stackPtr]['conditions'] ) === 1
				// @phan-suppress-next-line PhanTypeArraySuspiciousNullable False positive
				&& $tokens[$stackPtr]['conditions'][$scope] === T_NAMESPACE
			) {
				$useScopeEnd = $tokens[$scope]['scope_closer'];
			} else {
				return $tokens[$scope]['scope_closer'] ?? $stackPtr;
			}
		}

		// Every `use` after here, if its for imports (rather than using a trait), should
		// be part of this block, so for each T_USE token
		$priorLine = $tokens[$stackPtr]['line'];

		// Tokens for use statements that are not on the subsequent line, to check
		// for issues (not all are due to empty lines, could have comments)
		$toCheck = [];

		$next = $phpcsFile->findNext( T_USE, $stackPtr + 1, $useScopeEnd );
		while ( $next !== false ) {
			$nextNonEmpty = $phpcsFile->findNext( Tokens::$emptyTokens, $next + 1, $useScopeEnd, true );
			if ( $tokens[$stackPtr]['level'] !== $tokens[$next]['level']
				|| $nextNonEmpty === false || $tokens[$nextNonEmpty]['code'] === T_OPEN_PARENTHESIS
			) {
				// We are past the initial `use` statements for imports or closure `use`.
				break;
			}
			if ( $tokens[$next]['line'] !== $priorLine + 1 ) {
				$toCheck[] = $next;
			}
			$priorLine = $tokens[$next]['line'];
			$next = $phpcsFile->findNext( T_USE, $nextNonEmpty + 1, $useScopeEnd );
		}

		if ( !$toCheck ) {
			// No need to process further
			return $useScopeEnd;
		}

		$linesToRemove = [];
		$fix = false;
		foreach ( $toCheck as $checking ) {
			$prior = $checking - 1;
			while ( isset( $tokens[$prior - 1] )
				&& $tokens[$prior]['code'] === T_WHITESPACE
				&& $tokens[$prior]['line'] !== $tokens[$prior - 1]['line']
			) {
				$prior--;
				$linesToRemove[] = $prior;
			}
			if ( $prior !== $checking - 1 ) {
				// We moved back, so there were empty lines
				// $prior is the pointer for the first line break in the series,
				// show the warning on the first empty line
				$fix = $phpcsFile->addFixableWarning(
					'There should not be empty lines between use statements',
					$prior + 1,
					'Found'
				);
			}
		}

		if ( !$fix ) {
			return $useScopeEnd;
		}

		$phpcsFile->fixer->beginChangeset();
		foreach ( $linesToRemove as $linePtr ) {
			$phpcsFile->fixer->replaceToken( $linePtr, '' );
		}
		$phpcsFile->fixer->endChangeset();

		return $useScopeEnd;
	}
}
