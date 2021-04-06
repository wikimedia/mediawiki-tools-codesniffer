<?php

is_int( 11 );
is_integer( 12 );
FooBar::is_integer( 13 );
$this->is_integer( 14 );
extract( $someArray );
parse_str( 'arg' );
parse_str( 'arg', $someArray['arrayIndex'] );
is_long( 12 );
FooBar::is_long( 13 );
$this->is_long( 14 );
is_float( 15 );
is_double( 16 );
FooBar::is_double( 17 );
$this->is_double( 18 );
is_real( 19 );
FooBar::is_real( 20 );
$this->is_real( 21 );
isset( $x['a'], $x['b'], $x['c'] );
isset( $x['a'] ) && isset( $x['b'] ) && isset( $x['c'] );
parse_str( $x['a'] . ( true ? 1 : 0 ), $someArray );
$a = FooBar::sizeof['b'];
define( 'ALLOWED', 42 );
define( 'FORBIDDEN_CASE_SENSITIVE', 42, true );
