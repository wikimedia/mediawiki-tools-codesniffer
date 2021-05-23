<?php

// phpcs:disable MediaWiki.PHPUnit.AssertEquals

class AssertionsCountTest extends \PHPUnit\Framework\TestCase {

	/**
	 * @param int $a
	 * @param int $b
	 * @return int
	 */
	private function add( $a, $b = 0 ) {
		return $a + $b;
	}

	/** @coversNothing */
	public function testAssertions() {
		$arr = [ 'a', 'b', 'c' ];
		$c = 3;

		// Simple cases
		$this->assertEquals( 3, count( $arr ) );
		$this->assertEquals( 3, count( $arr ), 'Message' );
		$this->assertSame( 3, count( $arr ) );
		$this->assertSame( 3, count( $arr ), 'Message' );

		// More complex
		$this->assertEquals( 1 + 2, count( $arr ) );
		$this->assertSame( $this->add( 3 ), count( $arr ) );
		$this->assertEquals( [ 0, 1, 2, 3 ][ 3 ], count( $arr ) );
		$this->assertSame( $this->add( 1, 2 ), count( $arr ) );
		$this->assertSame( $c, count( $arr ) );
		$this->assertSame( $this->add( 1, count( [ 1, 2 ] ) ), count( $arr ) );

		// Not replaced
		$this->assertSame( 6, count( $arr ) * 2 );
		$d = Foo::assertEquals;
		$this->assertSame( Foo::assertEquals, count::assertEquals, 'Message' );
		$this->assertSame( count( $arr ), count( $otherArr ) );
	}
}
