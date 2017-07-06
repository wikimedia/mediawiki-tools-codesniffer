<?php

class Foo {
	/**
	 * A dummy function
	 */
	public function doStuff() {
		// This is bad
		Hooks::run( 'FooDoStuff', [ &$this, &$this ] );

		// This is weird, but OK
		if ( $foo & $this ) {
			$pass();
		}

		$a = [ &$this->what, &$this['what'], &$this::$what ];
	}
}
