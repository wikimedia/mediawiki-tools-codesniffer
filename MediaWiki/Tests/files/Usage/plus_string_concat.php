<?php

class PlusStringConcatSniffTest {

	/**
	 * @param string $a
	 */
	public function doing( $a ) {
		$b = 'test' + 'test2';
		$c = $b + $a;
		$d = $c + 'string';
		$d += 'string';
	}

}
