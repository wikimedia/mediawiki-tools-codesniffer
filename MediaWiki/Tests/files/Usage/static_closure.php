<?php

class Bar {
	/**
	 * A dummy function
	 * @return bool
	 */
	public function doParentStuff() {
		return true;
	}

	/**
	 * A dummy function
	 * @return bool
	 */
	public static function doStaticParentStuff() {
		return true;
	}
}

class Foo extends Bar {

	private const FAKE = 'fake';

	/** @var int */
	private static $static = 1;

	/** @var int */
	private $fake = 1;

	/**
	 * A dummy function
	 */
	public function doStuff() {
		$a = [ 1, 2 ];

		// Simple cases
		array_filter( $a, static function ( $val ) {
			return $val === 1;
		} );
		array_filter( $a, function ( $val ) {
			return $val === 1;
		} );

		array_filter( $a, static fn ( $val ) => $val === 1 );
		array_filter( $a, fn ( $val ) => $val === 1 );

		// Class context needed
		array_filter( $a, function ( $val ) {
			return parent::doParentStuff();
		} );
		array_filter( $a, function ( $val ) {
			return parent::doStaticParentStuff();
		} );
		array_filter( $a, function ( $val ) {
			return $this->doChildStuff();
		} );
		array_filter( $a, function ( $val ) {
			return self::doChildStuff();
		} );
		array_filter( $a, function ( $val ) {
			return self::doStaticChildStuff();
		} );
		array_filter( $a, static function ( $val ) {
			return parent::doParentStuff();
		} );
		array_filter( $a, static function ( $val ) {
			return $this->doChildStuff();
		} );
		array_filter( $a, static function ( $val ) {
			return self::doStaticChildStuff();
		} );

		array_fill( $a, function ( $val ) {
			return "{$this->fake}" . $val;
		} );

		// Inner closures
		$b = [ 1, [ 2, 3 ] ];
		array_filter( $b, function ( $val ) {
			if ( is_array( $val ) ) {
				return array_filter( $val, static function ( $ival ) {
					wfDebugLog( 'This is ' . static::class );
					return true;
				} );
			}
			return true;
		} );

		// type hints
		$c = function ( self $test ) : self {
			$innerC = function ( self $innerTest ) : self {
				if ( $test->fake === self::$static ) {
					return new self;
				}
				return $innerTest;
			}
			if ( $test->fake === self::FAKE ) {
				return null;
			}
			return $innerC( $test );
		}
		$c( $this );

		// anon class
		$d = static function ( $key ) {
			return new class( $key ) extends AClass {
				protected function aFunction() {
					return "($this->key$*)";
				}
			};
		} );
		$d( 'test' );
	}

	/**
	 * A dummy function
	 * @return bool
	 */
	public function doChildStuff() {
		return true;
	}

	/**
	 * A dummy function
	 * @return bool
	 */
	public static function doStaticChildStuff() {
		return true;
	}

}
