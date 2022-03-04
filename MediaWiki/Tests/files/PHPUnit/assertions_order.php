<?php

class AssertionsOrderTest extends \PHPUnit\Framework\TestCase {
	/** @coversNothing */
	public function testAssertions() {
		// Should be replaced
		$var = 123;
		$d = Foo::assertEquals;
		$this->assertSame( $var, false );
		$this->assertSame( $var, false, 'Message' );

		$this->assertSame( $var, 2 );
		$this->assertEquals( $var, 2, 'Message' );

		$this->assertSame( $var, 'Foo' );
		$this->assertEquals( $var, 'Foo', 'Message' );

		$this->assertSame( $var * 2, 246 );
		$this->assertSame( $obj->method(), 'Foo' );
		$this->assertSame( $obj->method2()->toString(), 'Foo' );
		$this->assertSame( $arr['key'], 'Foo' );
		$this->assertSame(
			$obj->method2()
				->method3()
				->toString(),
			'bar'
		);

		// Variables named $expected* are assumed to be the expected value
		$expected = true;
		$this->assertSame( $var, $expected );
		$this->assertEquals( $var, $expected, 'Message' );
		$expectedResult = true;
		$this->assertSame( $var, $expectedResult );
		$this->assertEquals( $var, $expectedResult, 'Message' );

		// Should not be replaced
		$otherVariable = false;
		$this->assertSame( $var, $otherVariable );
		$this->assertEquals( $var, $otherVariable, 'Message' );

		// Cannot yet handle
		$this->assertSame( wfFunctionCall( true ), true );
		$this->assertSame( $var, 246 * 2 );
	}
}
