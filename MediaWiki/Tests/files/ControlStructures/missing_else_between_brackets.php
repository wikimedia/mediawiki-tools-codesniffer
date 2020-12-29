<?php

/**
 * Failed examples.
 * @return void
 */
function wfFailedExamples() {
	$a = true;
	$b = true;

	if ( $a ) {
		# code...
	} {
		# other code
	}

	if ( $a ) {
		# code
	}{
		# code
	}

	if ( $a ) {

	}
	{
		# B
	}

	if ( $a ) {
		# Code
	} elseif ( !$b ) {
		# Code
	} {
		# Code
	}
}

function wfPassedExamples() {
	$a = true;
	$b = true;
	$c = 'c';
	$d = 'd';

	if ( $a ) {
		# code...
	}

	if ( $a ) {
		# code...
	} else {
		# more code
	}

	if ( $a ) {
		# code...
	} elseif ( !$b ) {
		# more code
	}

	if ( $a ) {
		# code...
	} elseif ( !$b ) {
		# code...
	} else {
		# code...
	}

	if ( wfFoo( "{{c}} {{d}}" ) ) {
		# code
	}
}
