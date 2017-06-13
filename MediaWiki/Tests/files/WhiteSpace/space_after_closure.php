<?php
$good = function () {
	// pass
};

$good_with_arg = function ( $arg ) {
	// pass
};

$bad = function() {
	// fail
};

$alsobad = function         () {
	// fail
};

$bad_with_arg = function( $param ) {
	// fail
};
