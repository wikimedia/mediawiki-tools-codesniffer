<?php

is_int( 11 );
is_integer( 12 );
FooBar::is_integer( 13 );
$this->is_integer( 14 );
extract( $someArray );
parse_str( 'arg' );
parse_str( 'arg', $someArray['arrayIndex'] );
preg_quote( 'arg' );
preg_quote( 'arg', '/' );
