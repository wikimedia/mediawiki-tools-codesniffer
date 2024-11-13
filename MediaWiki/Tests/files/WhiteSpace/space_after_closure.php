<?php
$good = static function () {
	// pass
};

$good_with_arg = static function ( $arg ) {
	// pass
};

array_filter( $a, static fn ( $val ) => $val === 1 );
// phpcs:ignore MediaWiki.Usage.StaticClosure.StaticClosure
array_filter( $a, fn ( $val ) => $val === 1 );

$bad = static function() {
	// fail
};

$alsobad = static function         () {
	// fail
};

$bad_with_arg = static function( $param ) {
	// fail
};

array_filter( $a, static fn( $val ) => $val === 1 );
// phpcs:ignore MediaWiki.Usage.StaticClosure.StaticClosure
array_filter( $a, fn( $val ) => $val === 1 );
