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
		/**/
		/***/
		/**************/
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

	/** Comment
	 * @param string[] $a A comment
	 *       - OptionA - This is a key option
	 *       - OptionB - This is a deprecated key option
	 *         @deprecated
	 *  @return string A comment
	 */
	public function testSyntaxDocTag2( $a ) {
		return $a;
	}

	/** A text on two
	 lines, but missing a doc star
	 */
	public function testMissingDocStar() {
	}

	/** A text on two
	 lines, but missing a doc star and end comment on same line */
	public function testMissingDocStar2() {
	}

	/** Missing doc star with empty

	 * line in there
	 */
	public function testMissingDocStar3() {
	}

}

class TestPassedExamples {

	/**
	 * Normal comment
	 */
	public function f() {
	}

}
