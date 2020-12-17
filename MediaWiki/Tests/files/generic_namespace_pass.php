<?php

namespace SomeNamespace;

/**
 * Some comment in a block :D
 *
 * @param bool $outputtype The type of output.
 * @param int $ts The timestamp.
 * @return null
 */
function namespacedFunction( $outputtype = true, $ts = 3 ) {
	if ( $ts === null ) {
		return null;
	} else {
		return wfTimestamp( $outputtype, $ts );
	}
}

class FooBar extends AClass implements SomeInterface {
	/**
	 * @param string $word The test word.
	 * @return string
	 */
	public function feedThePanda( $word ) {
		return 'something' . 'something else' . $word;
	}

	/**
	 * @return void
	 */
	public function makeLegoBlocks() {
		\SomeNamespace\namespacedFunction( false, 5 );
	}

	/**
	 * @return void
	 */
	public function testAssertions() {
		// Shouldn't trigger the sniff because we aren't in a test class
		$arr = [ 'a', 'b', 'c' ];
		$k = 'd';
		$this->assertTrue( array_key_exists( $k, $arr ) );
	}
}
