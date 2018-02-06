<?php

/**
 * Failed examples.
 * @return mixed
 */
function wfFailedExamples() {
	return $foo = 1;
}

/**
 * Passed examples.
 * @return mixed
 */
function wfPassedExamples() {
	if ( $foo == 1 ) {
		return 1;
	} elseif ( $foo === 1 ) {
		return $foo === 1;
	} else {
		return function () use ( $foo ) {
			$foo = 1;
			return $foo;
		};
	}
}
