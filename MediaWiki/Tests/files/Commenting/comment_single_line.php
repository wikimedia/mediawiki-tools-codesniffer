<?php

/**
 * @return void
 */
function wfFailedExamples() {
	/*** This should fail */
	/*** This should fail */
	/* This also should fail **/
}

/**
 * @return void
 */
function wfPassedExamples() {
	/* Correct inline comment */
	/** This is valid */
	/** @var This is valid */
}

/*
 * One asterisk, aligned properly
 */
/*
* One asterisk, misaligned
*/
/**
 * Two asterisks, aligned properly
 */
/**
* Two asterisks, misaligned
*/
