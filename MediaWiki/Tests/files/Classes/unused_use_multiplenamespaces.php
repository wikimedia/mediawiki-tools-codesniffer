<?php

namespace Foo;

class Bar {
	public function print() {
		print str_replace( __NAMESPACE__ . '\\', '', get_class( $this ) ) . "\n";
	}
}

( new Bar() )->print();

namespace FooBar;

use Foo\Bar;

class BarFoo extends Bar {
}

( new BarFoo() )->print();
