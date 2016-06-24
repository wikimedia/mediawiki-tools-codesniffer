<?php

/**
 * Failed Examples.
 * @return void
 */
function wfFailedExamples() {
	//  	    a comment with tabs instead of a space
	//
	#	 	yet another comment with tabs instead of a space.
	#
	//A comment without a space
	#Yup, no spaces.
	$a = 'Comments should on new line.'; # This is failed.
	$b = 'Also not like this.'; /* This is also failed.
								please attention.*/
}

/**
 * Passed Examples.
 * @return void
 */
function wfPassedExamples() {
	// a valid comment.
	# another valid comment.
	/* a comment no warnings would be raised on */
}
