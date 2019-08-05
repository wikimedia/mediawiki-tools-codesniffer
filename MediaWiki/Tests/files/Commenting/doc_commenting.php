<?php

/**
* Class comment without indent
*/
class TestFailedExamples {

	/**
	* Some text
	  *
	* And other test
	*/
	public function testStarAlignedDoc() {
	}

	/***SomeTextWithoutSpaces
	***
	***AndNoSpacesHere
	***/
	public function testSyntaxDoc() {
	}

	/***  NoSpaceHere
	 ***
	 ***  AndHere
	 ***/
	public function testVerySpacyDoc() {
	}

	/***SingleLineNoSpaceHere***/
	public function testSpacingDocSingleLine() {
	}

	/**   SingleLineSpacy  */
	public function testSpacingDocSingleLineSpacy() {
	}

	/***
	*NewLineNoSpaceHere***/
	public function testCloseTagOwnLine() {
	}

	/** Comment
	 *@param string $a A comment
	 *  @return string A comment
	 *@see List of Syntax
	 */
	public function testSyntaxDocTag( $a ) {
		return $a;
	}

}

class TestPassedExamples {

	/**
	 * Normal comment
	 */
	public function f() {
	}

}
