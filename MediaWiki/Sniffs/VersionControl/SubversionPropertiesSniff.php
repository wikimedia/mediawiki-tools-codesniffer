<?php

/**
 * Test that file has svn:eol-style native
 */
class MediaWiki_Sniffs_VersionControl_SubversionPropertiesSniff extends Generic_Sniffs_VersionControl_SubversionPropertiesSniff implements PHP_CodeSniffer_Sniff
{
	protected $required_properties = array(
		'svn:eol-style' => 'native',
	);

	public function process( PHP_CodeSniffer_File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();

		$prevOpenTag = $phpcsFile->findPrevious(T_OPEN_TAG, ($stackPtr - 1));
		if ($prevOpenTag !== false) {
			return;
		}

		$path       = $phpcsFile->getFileName();
		$properties = $this->getProperties($path);
		if($properties === null) {
			// Not under version control.
			return;
		}
		$properties += $this->required_properties;
		foreach( $properties as $key => $value ) {

			$isPresent   = isset( $properties[$key] );
			$isRequired  = array_key_exists( $key, $this->required_properties );
			$expectValue = isset( $this->required_properties[$key] );

			if( !$isRequired ) {
				// Only check keywords we are interested in
				continue;
			}

			if( !$isPresent ) {
				$phpcsFile->addError(
					'Required subversion property "%s" is not set, should be "%s"',
					$stackPtr,
					'Required',
					array( $key, $properties[$key] )
				);
				continue;
			}

			if( !$expectValue ) {
				continue;
			}

			if( $properties[$key] !== $this->required_properties[$key] ) {
				$phpcsFile->addError(
					'Required subversion property "%s" has incorrect value "%s", should be "%s"',
					$stackPtr,
					'Required',
					array( $key, $properties[$key], $this->required_properties[$key] )
				);
			}
		}//end foreach

	} // end process()
}
