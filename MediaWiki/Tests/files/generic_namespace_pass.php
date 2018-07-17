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
}
