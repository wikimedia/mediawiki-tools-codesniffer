<?php

// @phpcs:disable MediaWiki.WhiteSpace

/**
 * Good example
 * @phpcs-require-sorted-array
 */
$a = [
	'a' => '1',
	'b' => '2',
	'c' => '3',
	'd' => '4',
	'e' => '5',
];

/**
 * Empty
 * @phpcs-require-sorted-array
 */
$a = [
];

/**
 * Empty - array same line
 * @phpcs-require-sorted-array
 */
$a = [];

/**
 * Bad example
 * @phpcs-require-sorted-array
 */
$a = [
	'e' => '5',
	'a' => '1',
	'b' => '2',
	'c' => '3',
	'd' => '4',
];

/**
 * Bad example - no trailing comma
 * @phpcs-require-sorted-array
 */
$a = [
	'e' => '5',
	'a' => '1',
	'b' => '2',
	'c' => '3',
	'd' => '4'
];

/**
 * Bad example - no trailing comma
 * @phpcs-require-sorted-array
 */
$a = [
	'a',
	'b',
	'c',
	'e',
	'd'
];

/**
 * Bad example for indent
 * @phpcs-require-sorted-array
 */
$a = [
	// This is a comment
					'e',
				'd' ,
			'c',
		'b',
	'a'
	// This is another comment
];

/**
 * Bad line break
 * @phpcs-require-sorted-array
 */

$a = [
	'a' => '1',
];

// Same line
/** @phpcs-require-sorted-array */$a = [ 'e', 'a', 'b', 'c', 'd' ];

// Same line, no spaces
/** @phpcs-require-sorted-array */$a = ['e','a','b','c','d'];

// Same line, mixed quotes
/** @phpcs-require-sorted-array */$a = ['e',"a",' b',"d",'c'];

// Array on one line
/** @phpcs-require-sorted-array */
$a = [ 'e', 'a', 'b', 'c', 'd' ];

/**
 * Duplicate key
 * @phpcs-require-sorted-array
 */
$a = [
	'a' => '5',
	'c' => '3',
	'c1' => '41',
	'ca' => '4a',
	'c2' => '42',
	'c' => '4',
	'd' => '5',
	'b' => '2',
];
