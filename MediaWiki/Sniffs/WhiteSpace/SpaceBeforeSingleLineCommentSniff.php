<?php
/**
* Verify comments are preceeded by a single space.
*/
// @codingStandardsIgnoreStart
class MediaWiki_Sniffs_WhiteSpace_SpaceBeforeSingleLineCommentSniff
	implements PHP_CodeSniffer_Sniff {
	// @codingStandardsIgnoreEnd
	public function register() {
		return [
			T_COMMENT
		];
	}

	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		$currToken = $tokens[$stackPtr];
		if ( $currToken['code'] === T_COMMENT ) {
			// Accounting for multiple line comments, as single line comments
			// use only '//' and '#'
			// Also ignoring phpdoc comments starting with '///',
			// as there are no coding standards documented for these
			if ( substr( $currToken['content'], 0, 2 ) === '/*'
				|| substr( $currToken['content'], 0, 3 ) === '///'
			) {
				return;
			}

			// Checking whether the comment is an empty one
			if ( ( substr( $currToken['content'], 0, 2 ) === '//'
					&& rtrim( $currToken['content'] ) === '//' )
				|| ( $currToken['content'][0] === '#'
					&& rtrim( $currToken['content'] ) === '#' )
			) {
				$phpcsFile->addWarning( 'Unnecessary empty comment found',
					$stackPtr,
					'EmptyComment'
				);
			// Checking whether there is a space between the comment delimiter
			// and the comment
			} elseif ( substr( $currToken['content'], 0, 2 ) === '//'
				&& $currToken['content'][2] !== ' '
			) {
				$error = 'Single space expected between "//" and comment';
				$fix = $phpcsFile->addFixableWarning( $error, $stackPtr,
					'SingleSpaceBeforeSingleLineComment'
				);
				if ( $fix === true ) {
					$content = $currToken['content'];
					$newContent = preg_replace( '/^\/\/\t?/', '// ', $content );
					$phpcsFile->fixer->replaceToken( $stackPtr, $newContent );
				}
			// Finding what the comment delimiter is and checking whether there is a space
			// between the comment delimiter and the comment.
			} elseif ( $currToken['content'][0] === '#' ) {
				// Find number of `#` used.
				$startComment = 0;
				while ( $currToken['content'][$startComment] === '#' ) {
					$startComment += 1;
				}
				if ( $currToken['content'][$startComment] !== ' ' ) {
					$error = 'Single space expected between "#" and comment';
					$fix = $phpcsFile->addFixableWarning( $error, $stackPtr,
						'SingleSpaceBeforeSingleLineComment'
					);
					if ( $fix === true ) {
						$content = $currToken['content'];
						$delimiter = substr( $currToken['content'], 0, $startComment );
						if ( $content[$startComment+1] === '\t' ) {
							$newContent = preg_replace(
								'/^' . $delimiter . '\t/', $delimiter . ' ', $content
							);
						} else {
							$newContent = preg_replace(
								'/^' . $delimiter . '/', $delimiter . ' ', $content
							);
						}
						$phpcsFile->fixer->replaceToken( $stackPtr, $newContent );
					}
				}
			}
		}
	}
}
