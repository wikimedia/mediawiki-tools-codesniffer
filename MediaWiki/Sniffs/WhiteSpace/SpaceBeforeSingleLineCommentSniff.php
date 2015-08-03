<?php
/**
* Verify comments are preceeded by a single space.
*/
// @codingStandardsIgnoreStart
class MediaWiki_Sniffs_WhiteSpace_SpaceBeforeSingleLineCommentSniff
	implements PHP_CodeSniffer_Sniff {
	// @codingStandardsIgnoreEnd
	public function register() {
		return array(
			T_COMMENT
		);
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
				&& substr( $currToken['content'], 2, 1 ) !== ' '
			) {
				$error = 'Single space expected between "//" and comment';
				$fix = $phpcsFile->addFixableWarning( $error, $stackPtr,
					'SingleSpaceBeforeSingleLineComment'
				);
				if ( $fix === true ) {
					$content = $currToken['content'];
					if ( substr( $content, 2, 1 ) === '\t' ) {
						$newContent = preg_replace( '/^\/\/\t/', '// ', $content );
					} else {
						$newContent = preg_replace( '/^\/\//', '// ', $content );
					}
					$phpcsFile->fixer->replaceToken( $stackPtr, $newContent );
				}
			} elseif ( substr( $currToken['content'], 0, 1 ) === '#'
				&& substr( $currToken['content'], 1, 1 ) !== ' '
			) {
				$error = 'Single space expected between "#" and comment';
				$fix = $phpcsFile->addFixableWarning( $error, $stackPtr,
					'SingleSpaceBeforeSingleLineComment'
				);
				if ( $fix === true ) {
					$content = $currToken['content'];
					if ( substr( $content, 2, 1 ) === '\t' ) {
						$newContent = preg_replace( '/^#\t/', '# ', $content );
					} else {
						$newContent = preg_replace( '/^#/', '# ', $content );
					}
					$phpcsFile->fixer->replaceToken( $stackPtr, $newContent );
				}
			}
		}
	}
}
