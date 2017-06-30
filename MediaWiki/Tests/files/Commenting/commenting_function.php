<?php

class TestFailedExamples {

	public function __construct( $a ) {
		$this->a = $a;
	}

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

	/**
	 * @param boolean $aBool A bool
	 * @param integer $anInt An int
	 * @return boolean And some text
	 */
	public function testLongTypes( $aBool, $anInt ) {
		return $aBool;
	}

	/**
	 * @return integer More text
	 */
	public function testIntReturn() {
		return 0;
	}
}

class TestPassedExamples {
	/**
	 * Without return.
	 * @param int $a for test.
	 */
	public function __construct( $a ) {
		$this->test( $a );
	}

	/**
	 * A blank return does not require a return tag
	 */
	public function emptyReturn() {
		if ( $fooBar ) {
			return;
		}

		baz();
	}

	/**
	 * Single Test function.
	 *
	 * @param int &$testVar For test.
	 * @return int $testVar For test.
	 */
	public function test( &$testVar ) {
		return $testVar;
	}

	private function noDocs( $foo, $baz ) {
		// This function has no documentation because
		// it is private
		echo $foo;
	}

	/**
	 * @inheritDoc
	 */
	public function inherit( $stuff, $more, $blah ) {
		$blah = $more . $stuff . $blah;
	}

	public function __toString() {
		return 'no documentation because obvious';
	}

	/**
	 * @param bool $aBool A bool
	 * @param int $anInt An int
	 * @return bool
	 */
	public function testShortTypes( $aBool, $anInt ) {
		return $aBool;
	}
}

class TestSimpleConstructor {
	public function __construct() {
		$this->info = 'no documentation because obvious';
	}
}
