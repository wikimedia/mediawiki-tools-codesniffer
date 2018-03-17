<?php

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
