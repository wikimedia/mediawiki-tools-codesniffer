<?php
/**
 * Normal (non-unit-test) class.
 */
class NotATestCase {
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

	/**
	 * Named like a test, but not actually one.
	 * @return void
	 */
	public function testSomeMethod_foo() {
	}
}

/**
 * Unit tests have different naming conventions.
 */
class FooTest {
	/**
	 * Standard way of naming a test for someMethod when there are multiple tests for it.
	 * @return void
	 */
	public function testSomeMethod_testType() {
	}

	/**
	 * The dataProvider for the previous method.
	 * @return void
	 */
	public function provideSomeMethod_testType() {
	}

	/**
	 * This should still trigger an error.
	 * @return void
	 */
	public function helper_method() {
	}
}
