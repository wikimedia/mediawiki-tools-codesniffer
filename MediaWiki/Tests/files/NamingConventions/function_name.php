<?php
/**
 * Just for test.
 */
class Test {
	/**
	 * Lower camel case.
	 * @return void
	 */
	public function FailedExamples() {
		$this->say();
	}

	/**
	 * Lower camel case without under score.
	 * @return void
	 */
	public function failed_Examples() {
	}

	/**
	 * magic method with double under score.
	 */
	public function __construct() {
	}

	/**
	 * Lower camel case.
	 * @return void
	 */
	public function test() {
		$this->say();
	}

	/**
	 * Lower camel case without under score.
	 * @return void
	 */
	public function sayTest() {
	}
}
