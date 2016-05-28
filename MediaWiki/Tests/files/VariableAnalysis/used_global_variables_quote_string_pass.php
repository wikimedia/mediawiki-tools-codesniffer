<?php

/**
 * @return void
 */
function wfFooFoo() {
	global $wgSomething;
	$foo = "foo$wgSomething";
}
