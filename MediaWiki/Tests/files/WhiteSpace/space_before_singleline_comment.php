<?php

/**
 * Failed Examples.
 * @return void
 */
function wfFailedExamples() {
	//  	    a comment with tabs instead of a space
	$x = 1;
	#	 	yet another comment with tabs instead of a space.
	$x = 1;
	//A comment without a space
	$x = 1;
	#Yup, no spaces.
	$a = 'Comments should on new line.'; # This is failed.
	$b = 'Also not like this.'; /* This is also failed.
								please attention.*/
	$x = 1;
	//       The first line must start with a single space
	//       but the next ones don't have to
	$x = 1;
	#And the same goes
	#       for this kind of comments.
}

/**
 * Passed Examples.
 * @return void
 */
function wfPassedExamples() {
	// a valid comment.
	$x = 1;
	# another valid comment.
	$x = 1;
	/* a comment no warnings would be raised on */
	$x = 1;
	// This one is fine, but one may:
	// 1 - Use a list whose elements
	//    span over multiple lines, or
	// 2 - Add some fancy drawings:
	//      Root
	//     /   \
	//  Node   Node
	//  And it's not nice to fail for those.

	//    not even if there are empty lines in between,
	//      although that's not commonly seen.
	$x = 1;
	# And the same goes
	#   if #-style comments
	#      are used
}
