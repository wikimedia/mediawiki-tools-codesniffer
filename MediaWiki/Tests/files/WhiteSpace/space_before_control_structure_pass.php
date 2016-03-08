<?php

/**
 * @param  boolean $a Just for test.
 * @param  boolean $b Just for test.
 * @return void
 */
function wfAOO( $a, $b ) {
	if ( $a ) {
		# code...
	}
	switch ( $b ) {
		case 'value':
			# code...
			break;

		default:
			# code...
			break;
	}
	while ( $a > 0 ) {
		$a = $a - 1;
	}
	for ( $i = $a; $i < $b; $i++ ) {
		# code...
	}
	foreach ( $b as $key => $value ) {
		# code...
	}
	# and so on...
}
