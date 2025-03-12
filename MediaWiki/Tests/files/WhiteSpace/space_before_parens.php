<?php

/** Invalid examples */
$spacesInCall = rand	    ();
$newLinesInCall = rand

				();

$spaceInNew = new stdClass    ();
$newLinesInNew = new stdClass

				();

function wfSpaceBeforeParensInDecl       () {
}

function wfNewLinesBeforeParensInDecl

		() {
}

/** Valid examples */
function wfFoo() {
}

wfFoo();
$validNew = new stdClass();
