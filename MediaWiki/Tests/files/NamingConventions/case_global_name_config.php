<?php

// phpcs:set MediaWiki.NamingConventions.ValidGlobalName ignoreList[] $myGlobal
// phpcs:set MediaWiki.NamingConventions.ValidGlobalName allowedPrefixes[] test,code

/**
 * Failed examples.
 * @return void
 */
function wfFailedExamples() {
	global $notMyGlobal;
	$notMyGlobal = 5;
}

/**
 * Passed examples.
 * @return void
 */
function wfPassedExamples() {
	global $myGlobal, $testGlobal, $codeGlobal;
	$myGlobal = 5;
	$testGlobal = 5;
	$codeGlobal = 5;
}
