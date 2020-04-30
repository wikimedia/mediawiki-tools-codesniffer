<?php

class MisleadingGlobalNames {

	public function passedExamples() {
		global $wgFoo;
		$wgFoo = 'bar';
	}

	public function passedExample2() {
		// Only matches if the letter after $wg is uppercase
		$wglowerCase = 'baz';
	}

	public function failedExamples() {
		$wgBar = 'foo';
	}

}
