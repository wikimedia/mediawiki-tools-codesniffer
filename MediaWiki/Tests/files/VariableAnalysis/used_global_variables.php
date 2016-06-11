<?php

/**
 * Failed examples.
 * @return void
 */
function wfFailedExamples() {
	// The global variable is not used
	global $wgSomething;
}

/**
 * Passed examples.
 * @return void
 */
function wfPassedExamples() {
	global $wgNothing;
	// global variable used via heredoc.
	$foo = <<<PHP
/**
* foo $wgNothing
*/
PHP;
	// global variable used directly.
	$foo = $wgNothing + 2;

	// global variable used via string.
	$foo = "foo$wgNothing";

}
