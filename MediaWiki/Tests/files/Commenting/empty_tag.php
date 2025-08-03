<?php

class EmptySeeExampleTests {

	/**
	 * @see
	 * @var bool
	 */
	private $testFail;

	/**
	 * @see
	 * @var string|false
	 */
	private string|false $testFail2;

	/**
	 * @see
	 * @var string
	 */
	private readonly string $testFail3;

	/**
	 * @see
	 * @var string|null
	 */
	private ?string $testFail4;

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
