<?php

// phpcs:disable Generic.Arrays.DisallowLongArraySyntax

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
	$d = array ( 1, 2 );
	$a = [
		'foo' => 'bar',
		'foo' => ['bar', 'baz']
	];
	// Array with leading space - T246636
	$a = [ 
		'foo' => 'bar',
	];
	$unnecessarySpaceInParentheses1 = rand(
	);
	$unnecessarySpaceInParentheses2 = rand(

	);
	$unnecessarySpaceInParentheses3 = rand(
			);

	$unnecessarySpaceInShortArray1 = [     ];

	$unnecessarySpaceInLongArray1 = array(     );
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
	];
	$emptyShortArrayWithNewLine = [
	];
	// TODO: Ideally flag this.
	$unnecessarySpaceInShortArray2 = [

	];
	// TODO: Ideally flag this.
	$unnecessarySpaceInShortArray3 = [
				];
	$emptyLongArrayWithNewLine = array(
	);

	// TODO: Ideally flag this.
	$unnecessarySpaceInLongArray2 = array(

	);
	// TODO: Ideally flag this.
	$unnecessarySpaceInLongArray3 = array(
				);
}
