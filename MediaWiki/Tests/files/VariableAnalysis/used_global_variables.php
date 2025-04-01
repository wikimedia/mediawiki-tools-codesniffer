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
	global $wgThisIsNotUsed, $$skipThisButNotThePreviousOne;

	static function () {
		global $wgSomething, $wgClosureUnused;
		return $wgSomething;
	};

	new class() {
		public function foo(): string {
			global $wgSameLine, $wgAnonUnused;
			return $wgSameLine;
		}
	};
}

/**
 * Passed examples.
 * @return void
 */
function wfPassedExamples() {
	global $wgNothing;
	global $wgTwo, $wgClosure,
		$wgThree,
		$wgFour;
	global $$dynamicNameShouldBeSkipped;

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

	static function ( $notAGlobal ) use ( $alsoNotAGlobal ) {
	};

	static function ( $wgFour ) use ( $wgClosure ) {
		global $wgSomething;
		return $wgSomething . $wgFour . $wgClosure;
	};
}
