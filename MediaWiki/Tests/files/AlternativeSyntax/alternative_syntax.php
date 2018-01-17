<?php

/**
 * Failed examples.
 * @return void
 */
function wfFailedExamples() {
	declare ( ticks = 1 ):
	echo "foo";
	enddeclare;

	for ( $i = 0; $i < 5; $i++ ):
		echo $i;
	endfor;

	foreach ( [ 1, 2, 3 ] as $i ):
		echo $i;
	endforeach;

	$x = 1;

	if ( $x < 2 ):
		echo $x;
	elseif ( $x > 10 ):
		echo $i;
	else:
		echo $x . $i;
	endif;

	switch ( $x ):
		case 2:
			echo $x;
			break;
	endswitch;

	while ( $x > 2 ):
		echo $i;
	endwhile;
}

/**
 * Passed examples.
 * @return void
 */
function wfPassedExamples() {
	declare ( ticks = 1 ) {
	echo "foo";
	}

	for ( $i = 0; $i < 5; $i++ ) {
		echo $i;
	}

	foreach ( [ 1, 2, 3 ] as $i ) {
		echo $i;
	}

	$x = 1;

	if ( $x < 2 ) {
		echo $x;
	}

	switch ( $x ) {
		case 2:
			echo $x;
			break;
	}

	while ( $x > 2 ) {
		echo $i;
	}
}
