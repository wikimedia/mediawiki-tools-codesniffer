<?php

use PHPUnit\Framework\TestCase;

class SomethingTest extends TestCase {
	public function main() {
		$this->getMockBuilder( stdClass::class )
			->setMethods( [ 'foo', 'bar' ] )
			->getMock();
		$this->getMockBuilder( stdClass::class )
			->setMethods( [] )
			->getMock();
		$this->getMockBuilder( stdClass::class )
			->setMethods( null )
			->getMock();
		$mockBuilder = $this->getMockBuilder( stdClass::class );
		$mockBuilder->setMethods( [ 'x' ] );
		$this->getCoolMockBuilder()->setMethods( [] );
		// Documented false positive here
		$this->setMethods( 1 );
	}

	private function setMethods( ...$args ) {
		return 'This is not what we want :-/';
	}

	private function getCoolMockBuilder() {
		return $this->getMockBuilder( stdClass::class );
	}
}
