<?php

namespace SomeNamespace;

/**
 * Some comment in a block :D
 *
 * @param bool $outputtype
 * @param int $ts
 * @return null
 */
function namespacedFunction( $outputtype = true, $ts = 3 ) {
	if ( is_null( $ts ) ) {
		return null;
	} else {
		return wfTimestamp( $outputtype, $ts );
	}
}

class FooBar extends AClass implements SomeInterface {

	public function feedThePanda( $word ) {
		return 'something' . 'something else' . $word;
	}

	public function makeLegoBlocks() {
		\SomeNamespace\namespacedFunction( false, 5 );
	}
}
