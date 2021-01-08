<?php

// phpcs:disable Squiz.WhiteSpace.FunctionSpacing
// Unexpected behaviour - see https://github.com/squizlabs/PHP_CodeSniffer/issues/2406

/**
 * This thing is here to test for scope checks
 */
function wfBar() {
}

/**
 * Best function evar
 */
function wfFoo() {
	/**
	 * Bad thing
	 */
	function forbidden() {
		echo 'fail!';
	}

	$allowed = function () {
		echo 'pass';
	};

	$allowed();
}

$bar = function () {
	{
		/**
		 * Don't touch this
		 */
		function forbiddenToo() {
			echo 'fail!';
		}
	}
};

$bar();

class Foo {
	public function returnAnonymousClass() {
		return new class() {
			public function baz() {
				echo 'pass';
			}
		};
	}

	public function bar() {
		function forbidden() {
			echo 'fail!';
		}
	}
}
