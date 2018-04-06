<?php

/**
 * @code{,COMMA}
 * @endcode
 *
 * @TODO Make awesomer
 * @note: This is awesome!
 * @warn Don't stop at the top
 * @retrun nothing
 */
function wfFoo() {
	echo 'woo';
}

/**
 * @inheritdoc
 */
// phpcs:ignore MediaWiki.NamingConventions.PrefixedGlobalFunctions
function functionPrefixViolation() {
}
