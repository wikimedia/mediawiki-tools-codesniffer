<?php

is_null( $var );
!is_null( $var );
is_null( $var->field );
!is_null( $var->field );
is_null( $var->field + 64 );
$var === null;
is_null( \NamespaceName\ClassName::method( $parameter1, $parameter2 ) );
is_null( $this->field( $var + 45 / 7 ) );
is_null( $a ? $b : $c );
is_null( $a ? $b : ( $c ) );
$this->is_null( $var );
is_null[ $var ];
is_null( $a ? $b : some_function( $c ) );
!is_null( $a ? $b : some_function( $c ) );

if ( is_null(
	$this->callSomeSuperLongFunctionToBreakTheLine()
) ) {
	return;
}
$var === is_null( $var );
is_null( $var ) === $var;
is_null( $a ) == is_null( $b );
is_null( $a ) === is_null( $b );
is_null( $a ) != is_null( $b );
is_null( $a ) !== is_null( $b );
