<?php

class TestFailedExamples {

	public function testNoDoc( $testVar ) {
		return $testVar;
	}

	/**
	 * @param int $testVar This should start uppercase and end stop.
	 *
	 */
	public function testNeedReturn( $testVar ) {
		return $testVar;
	}

	/**
	 * @param int $testVar 	This is test.
	 * @param int $t 		For test.
	 * @return int $testVar This is test.
	 */
	public function testSingleSpaces( $testVar, $t ) {
		return $testVar;
	}
}

class TestPassedExamples {

	/**
	 * Single Test function.
	 *
	 * @param int $testVar For test.
	 * @return int $testVar For test.
	 */
	public function test( $testVar ) {
		return $testVar;
	}

}
