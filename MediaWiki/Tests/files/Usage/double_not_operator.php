<?php

$var = !!true;
$var = !false;
$var = true && !false;
$var = !!$var ?: !true || !false;

if ( !!$var === (bool)$var ) {
	return;
}

$var = ! !true;
$var = !	!$var;
$var = !!!$var;
$var = (int)!!$var;
$var = !!$var instanceof \Title;

if ( !!$var instanceof \Title ) {
	return;
}

if ( !!$var
	instanceof \Title
) {
	return;
}

if ( !!$var ||
	$var instanceof \Title
) {
	return;
}
