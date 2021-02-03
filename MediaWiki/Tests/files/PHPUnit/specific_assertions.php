<?php

// @phpcs:disable MediaWiki.PHPUnit.AssertEquals

class SpecificAssertionsTest extends \PHPUnit\Framework\TestCase {

	/** @coversNothing */
	public function testAssertions() {
		$arr = [ 'a', 'b', 'c' ];
		$k = 'd';
		$d = Foo::assertTrue;

		// Replace
		$this->assertTrue( array_key_exists( $k, $arr ) );
		$this->assertTrue( array_key_exists( $k, $arr ), 'Message' );
		$this->assertFalse( array_key_exists( $k, $arr ) );
		$this->assertFalse( array_key_exists( $k, $arr ), 'Message' );

		$this->assertTrue( in_array( $k, $arr ) );
		$this->assertTrue( in_array( $k, $arr, true ) );
		$this->assertTrue( in_array( $k, $arr, false ) );
		$this->assertTrue( in_array( $k, $arr ), 'Message' );
		$this->assertTrue( in_array( $k, $arr, true ), 'Message' );
		$this->assertTrue( in_array( $k, $arr, false ), 'Message' );
		$this->assertFalse( in_array( $k, $arr ) );
		$this->assertFalse( in_array( $k, $arr, true ) );
		$this->assertFalse( in_array( $k, $arr, false ) );
		$this->assertFalse( in_array( $k, $arr ), 'Message' );
		$this->assertFalse( in_array( $k, $arr, true ), 'Message' );
		$this->assertFalse( in_array( $k, $arr, false ), 'Message' );

		$this->assertFalse( strpos( 'foo', 'o' ) );
		$this->assertFalse( strpos( 'foo', 'o' ), 'Message' );
		$this->assertNotFalse( strpos( 'foo', 'o' ) );
		$this->assertNotFalse( strpos( 'foo', 'o' ), 'Message' );

		$this->assertIsInt( strpos( 'foo', 'o' ) );
		$this->assertIsInt( strpos( 'foo', 'o' ), 'Message' );

		// Not replaced
		$this->assertTrue( array_key_exists( $k, $arr ) || true );
		$this->assertFalse( in_array( $k, $arr, true || true ) );
		$this->assertFalse( in_array( $k + ( true ? [] : [ 'key' ] ) + [ 'value' ], $arr, 1 ) );
		$this->assertFalse( strpos( 'foo', 'o', 1 ) );
		$this->assertFalse( strpos( 'foo', 'o', 1 ), 'Message' );
		$this->assertNotFalse( strpos( 'foo', 'o', 1 ) );
		$this->assertNotFalse( strpos( 'foo', 'o', 1 ), 'Message' );
		$this->assertIsInt( strpos( 'foo', 'o', 1 ) );
		$this->assertIsInt( strpos( 'foo', 'o', 1 ), 'Message' );
		$this->assertIsInt( strpos::intProperty, 'Message' );
		$this->assertFalse( strpos( 'foo' ) );
	}
}
