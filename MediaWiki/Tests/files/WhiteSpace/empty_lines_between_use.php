<?php

// Make sure we can test our sniff properly, and ignore the order
// phpcs:disable MediaWiki.Classes.UnusedUseStatement
// phpcs:disable MediaWiki.Classes.UnsortedUseStatements
// phpcs:disable MediaWiki.WhiteSpace.MultipleEmptyLines

namespace Example;

use Foo;
use Bar;
// Shouldn't trigger issues
use Baz;

use Qux;
use ExampleTrait;



use AfterMultipleEmptyLines;

class Stuff {
	use ExampleTrait;

}
