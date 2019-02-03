<?php

/**
 * Failed examples.
 * @return mixed
 */
function wfFailedExamples() {
	if ( $foo === 1 ) {
		return $foo = 1;
	} else {
		return new class() {
			public function foo() {
				return $foo = 1;
			}
		};
	}
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
	} elseif ( $foo === 2 ) {
		return new class() extends SkinTemplate {
			public $skinname = 'vector';
		};
	} else {
		return function () use ( $foo ) {
			$foo = 1;
			return $foo;
		};
	}
}
