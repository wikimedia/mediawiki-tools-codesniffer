<?php

if( $a )   {
	# code...
}  elseif   ( $b ){
	# code...
}else{
	// More code
}

try{
	// Code
}   catch{
	// Code
}finally    {
	// Code
}

do{

} while( rand() );

while    ( rand() ){

}

for( ;; )    {

}

switch( 1 )   {
	default:
}

match  (rand()){
	42 => 42
};

function wfFailedConditionalExamples() {
	$a = true;
	if ( $a ) {
		# code...
	}
	elseif ( $a ) {
		# code...
	}
	else{
		# code...
	}

	if ( $a ) {
		# code...
	}elseif ( $b ) {
		# code...
	}else     {
	}

	if ( $a ) {
		# code...
	}
	else
	{
		# code...
	}

	if ( $a ) :
		# code...
	elseif ( $a ) :
		# code...
	else :
		# code...
	endif;
}

/**
 * Passed examples.
 * @return void
 */
function wfPassedConditionalExamples() {
	$a = true;
	if ( $a ) {
		# code...
	} elseif ( $a ) {
		# code...
	} else {
		# code...
	}
	if ( $a ):
		# code...
	elseif ( $a ):
		# code...
	else:
		# code...
	endif;
}

function wfSpaceBeforeControlStructureTest() {
	if ( $a )
	{
		# code...
	} elseif ( $b )
	{
		# code...
	}
	# too many spaces
	switch ( $b )   {
		case 'value':
			# code...
			break;

		default:
			# code...
			break;
	}
	# between closing parenthesis and opening brace is '\t'
	for ( $i = $a; $i < $b; $i++ )	{
		# code...
	}

	try {
	} catch ( \Exception $ex ) {

		// This empty line is *after* the control structure brace. This can't be the responsibility of
		// a sniff with the name "*before* control structure brace".
	}

	try {
		// Here the empty line after the closing brace was mistakenly removed.
	} catch ( \Exception $ex ) {}

	wfFoo();
}

function wfPassedTryCatchExamples() {
	try {
		foo();
	} catch ( Exception $bar ) {
		baz();
	}

	try {
		foo();
	} catch ( RuntimeException $bar ) {
		baz();
	} catch ( Exception $iAmRunningOutOfNames ) {
		justDoSomething();
	}

	$wfTestFunction = static function () {
		try {
			foo();
		} catch ( Exception $bar ) {
			baz();
		}
	};
}

function wfFailedTryCatchExamples() {
	try {
		foo();
	}
	catch ( Exception $bar ) {
		baz();
	}

	try {
		foo();
	}
	catch ( RuntimeException $bar ) {
		baz();
	}

	catch ( Exception $iAmRunningOutOfNames ) {
		justDoSomething();
	}

	$wfSecondTestFunction = static function () {
		try {
			foo();
		}
		catch ( Exception $bar ) {
			baz();
		}
	};
}
