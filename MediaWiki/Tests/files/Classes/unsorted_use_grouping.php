<?php

namespace SomethingElse;

use Class1;
use const CONST1;
use /* empty token */Class2;
use function function1;
use Const CONST2 /* more empty tokens */ ;
use function function2;
/* empty tokens between statements */
use Class3;

$a = ( new Class1(
	new Class2( CONST2, new Class3() ),
	CONST1
) )->do( function1(), function2 );
