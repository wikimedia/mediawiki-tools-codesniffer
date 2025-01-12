<?php
/**
 * Sniff to suppress the use of:
 * Fail: return $a = 0;
 * Pass: return $a == 0
 */

namespace MediaWiki\Sniffs\Usage;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

class AssignmentInReturnSniff implements Sniff {
	/**
	 * @inheritDoc
	 */
	public function register(): array {
		return [
			T_RETURN,
			T_YIELD,
			T_YIELD_FROM,
		];
	}

	/**
	 * @param File $phpcsFile
	 * @param int $stackPtr The current token index.
	 * @return int
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		$searchToken = Tokens::$assignmentTokens + [
			T_CLOSURE,
			T_FUNCTION,
			T_ANON_CLASS,
			T_SEMICOLON,
		];
		$next = $phpcsFile->findNext( $searchToken, $stackPtr + 1 );
		while ( $next !== false ) {
			$code = $tokens[$next]['code'];
			if ( isset( $tokens[$next]['scope_closer'] ) ) {
				// Skip to the end of the closure/inner function and continue
				$next = $phpcsFile->findNext( $searchToken, $tokens[$next]['scope_closer'] + 1 );
				continue;
			}
			if ( $code === T_SEMICOLON ) {
				// End of return statement found
				break;
			}
			// Check if any assignment operator was used. Allow T_DOUBLE_ARROW as that can
			// be used in an array like `return [ 'foo' => 'bar' ]`
			if ( array_key_exists( $code, Tokens::$assignmentTokens )
				&& $code !== T_DOUBLE_ARROW
			) {
				$errorPtr = $stackPtr;
				$content = $tokens[$stackPtr]['content'];
				// "yield from" could have whitespace and comments in the middle. If we only got a `yield`, skip
				// forwards until we find the `from`. See https://github.com/PHPCSStandards/PHP_CodeSniffer/pull/647
				if ( $tokens[$stackPtr]['code'] === T_YIELD_FROM && $content === 'yield' ) {
					$nextNotNoop = $phpcsFile->findNext( [ T_COMMENT, T_WHITESPACE ], $stackPtr + 1, null, true );
					if (
						$nextNotNoop === false ||
						$tokens[$nextNotNoop]['code'] !== T_YIELD_FROM ||
						$tokens[$nextNotNoop]['content'] !== 'from'
					) {
						// Should never happen.
						$stackPtr++;
					} else {
						$content .= ' from';
						$stackPtr = $nextNotNoop + 1;
					}
				} else {
					$stackPtr++;
				}
				// Split by any whitespaces and build better looking content with one space
				$contentPieces = preg_split( '/\s+/', $content );
				$phpcsFile->addError(
					'Assignment expression not allowed within "%s".',
					$errorPtr,
					'AssignmentIn' . implode( '', array_map( 'ucfirst', $contentPieces ) ),
					[ implode( ' ', $contentPieces ) ]
				);
				break;
			}
			$next = $phpcsFile->findNext( $searchToken, $next + 1 );
		}
		// Do not report multiline yield tokens twice
		return $stackPtr;
	}
}
