<?php

class ExampleClass {

	final public function passFinalPublic() {
		// Pass
	}

	final protected function passFinalProtected() {
		// Pass
	}

	final private function failFinalPrivate() {
		// Fail
	}

	final  private function failWithDoubleSpace() {
		// Fail
	}

	final /** comment */ private function failWithComment() {
		// Fail
	}

}
