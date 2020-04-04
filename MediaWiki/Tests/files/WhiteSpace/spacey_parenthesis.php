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
	// Array with leading space - T246636
	$a = [ 
		'foo' => 'bar',
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

	$fooArray = [
		// phpcs:disable Generic.Files.LineLength
		[
			'Some',
			'Parameter',
			'For',
			'Testprovider',
		],
		[
			'Some very',
			'lllllllllllllllllllllllloooooooooooooooooooooooooooonnnnnnnnnnnnnnggggggggggggg Parameter',
			'For',
			'Testprovider',
		],
		// phpcs:enable
	];
}
