<?php
$good = static function () {
	// pass
};

$good_with_arg = static function ( $arg ) {
	// pass
};

$bad = static function() {
	// fail
};

$alsobad = static function         () {
	// fail
};

$bad_with_arg = static function( $param ) {
	// fail
};
