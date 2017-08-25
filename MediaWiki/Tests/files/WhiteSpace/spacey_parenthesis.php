<?php

/**
 * Failed examples.
 * @param int $a Just for test.
 * @param int $b Just for test.
 * @return void
 */
function wfFailedExamples( $a, $b ) {
	$a->foo($b);
	$a->foo(  $b  );
	$a->foo( 	$b  	);
	$c = array( );
	$a = [
		'foo' => 'bar',
		'foo' => ['bar', 'baz']
	];
}

/**
 * Passed examples.
 * @param int $arg For test.
 * @param int $arg1 For test.
 * @return void
 */
function wfPassedExamples( $arg, $arg1 ) {
	$foo = [
		// a comment
		'foo' => 'bar',
		// 'foo' => 'baz',
	];
	(int)$arg->bar();
}
