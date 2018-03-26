<?php

/**
 * Failed examples.
 * @return void
 */
function wfFailedExamples() {
	// The below should have capital after wg
	global $wgsomething, $LocalInterwikis;
	$wgsomething = 5;
	$LocalInterwikis = false;
}

/**
 * Passed examples.
 * @return void
 */
function wfPassedExamples() {
	global $wgSomething, $wgLocalInterwikis, $wg3dProcessor;
	$wgSomething = 5;
	$wgLocalInterwikis = false;
	$wg3dProcessor = null;
}
