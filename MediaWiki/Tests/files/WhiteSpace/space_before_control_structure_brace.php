<?php

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
