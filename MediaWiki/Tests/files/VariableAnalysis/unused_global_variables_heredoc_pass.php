<?php

function fooFoo () {
	global $wgSomething;
	$foo = <<<PHP
/**
* foo $wgSomething
*/
PHP;
}
