<?php

class FOO extends BAR {

	/**
	 * @param int $a
	 */
	public function doingClosureWrong( $a ) {
		$c = function ( $b ) use ( $a ) {
			return $this->test( $a, $b, __METHOD__ );
		};
	}

	/**
	 * @param int $a
	 */
	public function doingClosureRight( $a ) {
		$fname = __METHOD__;
		$c = function ( $b ) use ( $a, $fname ) {
			return $this->test( $a, $b, $fname );
		};
	}

	/**
	 * @param int $a
	 */
	public function doingNoClosure( $a ) {
		$c = $this->test( $a, $b, __METHOD__ );
	}

	/**
	 * @param int $a
	 * @param int $b
	 * @param string $fname
	 * @return int
	 */
	private function test( $a, $b, $fname ) {
		return $a * $b;
	}
}
