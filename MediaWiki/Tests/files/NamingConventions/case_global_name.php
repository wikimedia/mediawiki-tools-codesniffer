<?php

/**
 * Failed examples.
 * @return void
 */
function wfFailedExamples() {
	// The below should have capital after wg
	// Testing with a valid variable in the middle to ensure that the global after
	// it is still checked, see T279968
	global $wgsomething, $wgValidName, $LocalInterwikis;
	global $thisIsWrong, $$thisShouldBeSkipped;
	$wgsomething = $wgValidName;
	$LocalInterwikis = false;
	echo $thisIsWrong;
}

/**
 * Passed examples.
 * @return void
 */
function wfPassedExamples() {
	global $wgSomething, $wgLocalInterwikis, $wg3dProcessor;
	global $$dynamicNameShouldBeSkipped;
	global $$$$$$thisShouldAlsoBeSkipped;
	$wgSomething = 5;
	$wgLocalInterwikis = false;
	$wg3dProcessor = null;
}
