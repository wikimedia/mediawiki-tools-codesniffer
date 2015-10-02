<?php

class FOO extends BAR {
	function __construct() {
	}

	function dirname( $a ) {
		return BAR::dirname( __FILE__ );
	}

	function foo( $a ) {
		return BAR::foo( __FILE__ );
	}

	function bar( $a ) {
		return BAR::bar( 0 );
	}
}

$foo = FOO();

$tmp = dirname( __FILE__ . "/.." );
$tmp = dirname( joinpaths( __FILE__, ".." ) );
$tmp = $foo->dirname( __FILE__ );

$f = __FILE__;
$tmp = dirname( $f );
$tmp = dirname( $f . "/.." );
$tmp = dirname( joinpaths( $f, ".." ) );
$tmp = $foo->dirname( $f );

echo "Done";
