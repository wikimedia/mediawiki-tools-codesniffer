<?php

/**
 * Failed examples.
 * @return void
 */
function wfFailedExamples() {
	if ( $foo = 1 ) {
		# code...
	} elseif ( $foo += 1 ) {
		# code...
	} elseif ( $foo *= $bar ) {
		# code...
	} elseif ( $foo > 1 || $bar < 1 || $foobar = 1 ) {
		# code...
	} elseif ( $bar = $foo->foo() ) {
		# code...
	}
	while ( $foo = foo() ) {
		# code...
	}
}

/**
 * Passed examples.
 * @return void
 */
function wfPassedExamples() {
	if ( $foo == 1 ) {
		# code...
	} elseif ( $foo === 1 ) {
		# code...
	} elseif ( $foo === [ 1 => 0 ] ) {
		# code...
	} elseif ( $foo > 1 || $bar < 1 ) {
		# code...
	} elseif ( $foo <= 1 ) {
		# code...
	} elseif ( 1 === $foo ) {
		# code...
	}
	while ( $foo !== 1 ) {
		# code...
	}
}
