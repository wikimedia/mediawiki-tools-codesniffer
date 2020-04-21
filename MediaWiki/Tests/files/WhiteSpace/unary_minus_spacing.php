<?php

function wfUnaryMinusExamples() {
	// Subtraction, should be ignored
	$a = 5 - 2;
	$b = $a - 1;
	$c = ( 10 * 2 ) - 1;
	$d = [ 5, 6 ];
	$e = $d[1] - 1;
	$e -= 1;

	// Unary minus, should be flagged
	$a = - 2;
	$b = 5 + - 2;
	$c = ( - 1 );
}
