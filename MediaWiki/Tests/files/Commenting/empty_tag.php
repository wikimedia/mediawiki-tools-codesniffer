<?php

class EmptySeeExampleTests {

	/**
	 * @see
	 * @var bool
	 */
	private $testFail;

	/**
	 * @see
	 * @return bool
	 */
	public function testFailing() {
		return false;
	}

	/**
	 * @see testFailing for the wrong thing
	 * @var bool
	 */
	private $testPass;

	/**
	 * @see testFailing for the wrong thing
	 * @return bool
	 */
	public function testPassing() {
		return true;
	}

}
