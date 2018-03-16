<?php

use InvalidArgumentException;
use Something\Something;

class UnusedUseNoNamespaceTest {
	/**
	 * @coversNothing
	 * @param Something $s
	 */
	public function testUse( $s ) {
		$this->setExpectedException( InvalidArgumentException::class );
	}
}
