<?php

// phpcs:disable Generic.WhiteSpace.ScopeIndent.IncorrectExact
// Curly braces not introducing a new scope confuse this sniff.

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

	$allowed = static function () {
		echo 'pass';
	};

	$allowed();
}

$bar = static function () {
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
