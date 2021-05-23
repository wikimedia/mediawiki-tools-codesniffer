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

		// Should not be replaced
		$expected = true;
		$this->assertSame( $var, $expected );
		$this->assertEquals( $var, $expected, 'Message' );

		// Cannot yet handle
		$this->assertSame( wfFunctionCall( true ), true );
		$this->assertSame( $var, 246 * 2 );
	}
}
