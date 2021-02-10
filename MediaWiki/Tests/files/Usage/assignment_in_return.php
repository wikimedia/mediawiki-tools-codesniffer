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
 * Failed examples.
 * @return Generator
 */
function wfFailedExamplesYield() {
	yield "test" => $foo = 1;
	yield new class() {
		public function foo() {
			yield "inner" => $foo = 1;
		}
	};
	yield
		from [ 3, $foo = 1 ];
	yield from [ 3, $foo = 1 ];
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
		return static function () use ( $foo ) {
			$foo = 1;
			return $foo;
		};
	}
}

/**
 * Passed examples.
 * @return Generator
 */
function wfPassedExamplesYield() {
	yield "test" => [ 'A', 'b' ];
	yield "test2" => [ 'c', 'D' ];
	yield from [ 3, 4 ];
}
