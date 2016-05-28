<?php

/**
 * @return void
 */
function wfFooFoo() {
	global $wgSomething;
	$foo = <<<PHP
/**
* foo $wgSomething
*/
PHP;
}
