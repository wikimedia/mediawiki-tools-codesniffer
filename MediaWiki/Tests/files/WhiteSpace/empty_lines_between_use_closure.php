<?php

// Test for an edge case with closures in global scope and the closure's `use` as the first thing on its line.
// The sniff should just ignore these.

$firstClosure = static function () use ( $argc ) {
};

$secondClosure = static function ()
	use ( $argc ) {
};
