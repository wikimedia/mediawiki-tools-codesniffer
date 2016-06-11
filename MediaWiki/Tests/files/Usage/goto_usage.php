<?php

/**
 * Failed examples.
 * @return void
 */
function wfFailedExamples() {
	for ( $i=0; $i < 20; $i++ ) {
		if ( $i == 15 ) {
			goto endloop;
		}
	}
	endloop:
	echo "Done";
}

/**
 * Passed examples.
 * @return void
 */
function wfPassedExamples() {
	for ( $i=0; $i < 20; $i++ ) {
		if ( $i == 15 ) {
			break;
		}
	}
	echo "Done";
}
