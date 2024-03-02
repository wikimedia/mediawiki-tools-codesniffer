<?php

use \SomeNamespace\I2;

if ( $a or $b ) {
	$c = $a and $b;
}

$foo = `echo "hi";`;

class  Foo  extends  \Bar  implements  \I1  ,  I2 {

	use   SomeThing;

	const TEST = 'test';

	/**
	 * Do stuff
	 */
	public static function doStuff() {
		Foo::doStuff();
	}

	/**
	 * Test spacing in function and closue declaration
	 * @param ?string $a
	 * @param string $b
	 * @param string $c
	 * @param string $d
	 * @return bool
	 */
	public function func(  ? string $a  ,  $b, $c, $d /*...*/  ) {
		return function ($e,$f) use (  $a  ,  $b  ) {
			return function () use (  $e  ) {
				return true;
			};
		};
	}
}

$s =  $sum/( ( 1<<16 )-1 )+2*-1*3-4^3%10;

$msg = wfMessage( 'message-key' ) -> inContentLanguage()
	-> text();

$str = '1'.$foo.$s.  '2'.
	'LONG LINE'
	.'MORELONGLINE';

f( ... $a );

$arrayWithWrongCommaSpacing = ['a','b'  ,'c',  'd'  ];

list( $a, $b ) = [ 1, 2 ];

$doubleNegative = !!random_int( 0, 1 );
