<?php

/**
 * Live coding - does not trigger for short array syntax,
 * because single "[" is not tokenized as that
 * @phpcs-require-sorted-array
 */
$a = array(
	'a' => '1',
	'b' => '2',
	'c' => '3',
	'd' => '4',
	'e' => '5',