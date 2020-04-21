<?php

function wfSpaceBeforeBracketTests() {
	// Should work
	$a = [ 0, 1, 2, 3, 4, 5 ];
	$b = [ $a[ 0 ], $a[ 1 ] ];
	[ $c, $d ] = $b;

	// Should fail
	$x = $a [ 0 ];
	$y = $b  [ 1 ];
}
