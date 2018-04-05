<?php

/**
 * Failed examples.
 * @return void
 */
function wfFailedExamples() {
	// The global variable is not used
	global $wgSomething;
	global $wgSameLine,
		$wgNextLine;
}

/**
 * Passed examples.
 * @return void
 */
function wfPassedExamples() {
	global $wgNothing;
	global $wgTwo,
		$wgThree,
		$wgFour;

	// global variable used via heredoc.
	$foo = <<<PHP
/**
* foo $wgNothing
*/
PHP;

	// global variable used directly.
	$foo = $wgTwo + 2;

	// global variable used via string.
	$foo = "foo$wgThree";

	$foo = "${wgFour}foo";
}
