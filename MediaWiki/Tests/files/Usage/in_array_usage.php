<?php

// Should use array_key_exists
in_array( $key, array_keys( $array ) );
in_array( $key, array_flip( $array ) );
in_array( $key, array_keys );

// Nothing wrong with these
in_array( $key, $array );
array_keys( $array );
array_key_exists( $key, $array );
in_array( $key ) && array_keys( $array );
in_array( array_keys( $key ), $array );

// Nested parenthesis
if ( !in_array( $key, array_keys( $array ) ) ) {
}
