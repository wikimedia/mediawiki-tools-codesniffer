<?php

class AssertionsTest extends \PHPUnit\Framework\TestCase {
	/** @coversNothing */
	public function testAssertions() {
		// Expected values that can be confused with false
		$this->assertEquals( null, false );
		$this->assertEquals( null, 0 );
		$this->assertEquals( null, 0.0 );
		$this->assertEquals( null, '' );
		$this->assertEquals( false, null );
		$this->assertEquals( false, 0 );
		$this->assertEquals( false, '' );
		$this->assertEquals( 0, null );
		$this->assertEquals( 0, false );
		$this->assertEquals( 0.0, '0' );
		$this->assertEquals( 00.00, null );
		$this->assertEquals( .0, null );
		$this->assertEquals( 0., null );
		$this->assertEquals( '', null );
		$this->assertEquals( '', false );
		$this->assertEquals( "", false );
		$this->assertEquals( '0', 0.0 );

		// Expected values that can be confused with true
		$this->assertEquals( true, 1 );
		$this->assertEquals( '1', true );
		$this->assertEquals( '01.0', true );

		// Expected values that can be confused with floating point numbers
		$this->assertEquals( ' 2.', 2 );
		$this->assertEquals( ' .5', .5 );

		// Edge-cases
		$this->assertEquals( null );
		$this->assertEquals( null /* oh dear */, null );

		// Stuff that should *not* be reported
		$this->assertEquals( 2, 2 );
		$this->assertEquals( 2.0, 2.0 );
		$this->assertEquals( '9a', '9a' );
		$this->assertEquals( " .a", ' .a' );
	}
}
