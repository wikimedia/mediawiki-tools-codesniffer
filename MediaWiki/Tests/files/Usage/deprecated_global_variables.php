<?php

/**
 * No errors should be found here
 */
function wfValidFunction() {
	global $wgFoo, $wgBar;

	$wgTitle = 'fnord';
	echo $wgFoo, $wgBar, $wgTitle;
}

/**
 * This one should fail
 */
function wfInvalidFunction() {
	global $wgFoo, $wgTitle;

	echo $wgTitle, $wgFoo;
}

class Foo {
	/**
	 * No errors should be found here
	 */
	public function validFunction() {
		global $wgFoo, $wgBar;

		$wgTitle = 'fnord';
		echo $wgFoo, $wgBar, $wgTitle;
	}

	/**
	 * This one should fail
	 */
	private function invalidFunction() {
		global $wgFoo, $wgTitle;

		echo $wgFoo, $wgTitle;
	}
}
