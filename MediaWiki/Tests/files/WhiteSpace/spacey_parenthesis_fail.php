<?php

/**
 * @param  int $a Just for test.
 * @param  int $b Just for test.
 * @return void
 */
function wfFooBar( $a, $b ) {
	$a->foo($b);
	$a->foo(  $b  );
	$a->foo( 	$b  	);
	$c = array( );
}

$a = [
	'foo' => 'bar',
	'foo' => ['bar', 'baz'],
];
