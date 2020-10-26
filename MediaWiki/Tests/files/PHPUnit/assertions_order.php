<?php

class AssertionsOrderTest extends \PHPUnit\Framework\TestCase {
	/** @coversNothing */
	public function testAssertions() {
		// Should be replaced
		$var = 123;
		$this->assertSame( $var, false );
		$this->assertSame( $var, false, 'Message' );

		$this->assertSame( $var, 2 );
		$this->assertEquals( $var, 2, 'Message' );

		$this->assertSame( $var, 'Foo' );
		$this->assertEquals( $var, 'Foo', 'Message' );

		// Should not be replaced
		$expected = true;
		$this->assertSame( $var, $expected );
		$this->assertEquals( $var, $expected, 'Message' );

		// Not just variable
		$this->assertSame( wfFunctionCall( true ), true );
		$this->assertSame( $var * 2, 246 );
	}
}
