<?php

// phpcs:disable Generic.Arrays.DisallowLongArraySyntax

class AssertionsOrderLongArrayTest extends \PHPUnit\Framework\TestCase {
	/** @coversNothing */
	public function testAssertions() {
		// Should be replaced, the long array() syntax is handled like [] (T336420)
		$this->assertSame( $emptyArray, array() );
		$this->assertSame( $intArray, array( 1, 2, 3 ) );
		$this->assertSame( $result, array( 'something' => 'other' ), 'Message' );
		$this->assertSame( $resultWithValue, array( 'key' => $expectedValue ), 'Message' );
		$this->assertSame( $nestedArray, array( array( 1, 2 ), 3 ), 'Message' );
		$this->assertSame(
			$bigArray,
			array(
				'first' => 'a',
				'second' => $expected,
				'nested' => array( true, false ),
				500
			),
			'Message'
		);

		// Should not be replaced
		$this->assertSame( array( 1, 2 ), $actual );
		$this->assertSame( $var, array( 'a', $actual ) );
		$this->assertSame( $var, array( 'a', getValue() ), 'Message' );
	}
}
