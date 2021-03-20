<?php
// phpcs:disable MediaWiki.Commenting.MissingCovers.MissingCovers

namespace {
	class SomethingTest extends \PHPUnit\Framework\TestCase {
		/** @var string */
		private static $assertInternalType;

		public function testAssertInternalType() {
			$e = 'foo';
			$a = 'bar';
			$this->assertInternalType( "float", $e );
			$this->assertInternalType( $a, $e );
			$this->assertInternalType( 'boolean', $e );
			$this->assertInternalType( 'wtf', $e );
			$this->assertInternalType();
			$this->assertInternalType( 'string' );
			$this->assertInternalType( "integer", $a );
			self::assertInternalType( 'callable', $e );
			static::assertInternalType( 'iterable', $e );

			$d = self::assertInternalType;
			$e->assertInternalType( 'NOT A PHPUNIT FUNCTION', 'foo' );
		}

		public function testAssertNotInternalType() {
			$e = 'foo';
			$a = 'bar';
			$this->assertNotInternalType( 'float', $e );
			$this->assertNotInternalType( $a, $e );
			$this->assertNotInternalType( 'boolean', $e );
			$this->assertNotInternalType( "wtf", $e );
			$this->assertNotInternalType();
			$this->assertNotInternalType( 'string' );
			$this->assertNotInternalType( 'integer', $a );
			self::assertNotInternalType( 'callable', $e );
			static::assertNotInternalType( "iterable", $e );

			$e->assertNotInternalType( 'NOT A PHPUNIT FUNCTION', 'foo' );
		}

		public function testAssertType() {
			$e = 'foo';
			$a = 'bar';
			$this->assertType( "float", $e );
			$this->assertType( $a, $e );
			$this->assertType( 'boolean', $e );
			$this->assertType( 'wtf', $e );
			$this->assertType();
			$this->assertType( 'string' );
			$this->assertType( "integer", $a );
			$this->assertType( MyClass::class, $a );
			$this->assertType( 'MyClass', $a );

			$e->assertType( 'NOT A PHPUNIT FUNCTION', 'foo' );
		}

		public function testAssertArraySubset() {
			$this->assertArraySubset( [ 1, 2 ], [ 3, 4 ] );
			$this->assertArraySubset();
			self::assertArraySubset( $GLOBALS['foo'], $GLOBALS['baz'] );
			static::assertArraySubset( [ 1, 2 ] );
			$GLOBALS['a']->assertArraySubset( [], [] );
		}

		public function testAttributeMethods() {
			$this->assertAttributeContains( 1, 2 );
			$this->assertAttributeGreaterThanOrEqual( 1, 2 );
			$this->assertAttributeNotInstanceOf( $GLOBALS['c'], 'foo', $this );
			self::getStaticAttribute( $this, 'foo' );
			static::attribute( 'foo', 'baz' );
			$GLOBALS[1]->getObjectAttribute( 'NOT A PHPUNIT METHOD' );
		}
	}
}
