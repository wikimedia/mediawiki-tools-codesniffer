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

		// Not just a variable and a literal
		$this->assertSame( wfFunctionCall( true ), 'abc' );
		$this->assertSame(
			wfFunctionCall( false )->bar(),
			'xyz'
		);
		$this->assertSame( $var, 246 * 2 );
		$this->assertSame( $var, SomeClass::SOME_CONST );

		// Actual value is an array with no variables/functions (except $expected*),
		// should be the actual
		$this->assertSame( $emptyArray, [], 'Message' );
		$this->assertSame( $result, [ 'something' => 'other' ], 'Message' );
		$this->assertSame( $resultWithValue, [ 'key' => $expectedValue ], 'Message' );
		$this->assertSame( $intArray, [ 1, 2, 3 ], 'Message' );
		$this->assertSame(
			$bigArray,
			[
				'first' => 'a',
				'second' => $expected,
				'nested' => [ true, false ],
				500
			],
			'Message'
		);

		// Simple tests for the Not variants, there are handled the same
		$this->assertNotSame( $var, 'NotSame' );
		$this->assertNotEquals( $var, 'NotEquals' );

		// Should not be replaced
		$otherVariable = false;
		$this->assertSame( $var, $otherVariable );
		$this->assertEquals( $var, $otherVariable, 'Message' );
		$this->assertSame(
			$bigArray,
			[
				'first' => 'a',
				'second' => $actual,
				'nested' => [ true, false ],
				500
			],
			'Message'
		);
	}
}
