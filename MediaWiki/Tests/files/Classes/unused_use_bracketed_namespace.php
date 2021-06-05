<?php

namespace SomeExample {
	use FirstInterface;
	use SecondInterface;

	class Demo implements FirstInterface {

	}

}

// Make sure that the `use` for SecondInterface in the first case
// gets flagged, its actual use in the second case does not count

namespace AnotherExample {
	use FirstInterface;
	use SecondInterface;

	class Demo implements FirstInterface, SecondInterface {

	}

}

// Make sure the warnings for imports in the same namespace still work

namespace AnotherExample {
	use AnotherExample\Demo;
	use ExampleInterface;

	class Demo2 extends Demo implements ExampleInterface {

	}

}

// Passing: make sure using traits still works, there is some special
// check for conditions on that

namespace UsingTrait {
	use ExampleTrait;

	class Demo {
		use ExampleTrait;

	}

}

// Passing: a bunch of normal ways to use

namespace AllGood {
	use ExampleInterface;
	use ExampleParentClass;
	use ExampleTrait;
	use Something\ParamObj;
	use Something\ReturnObj;

	class Demo extends ExampleParentClass implements ExampleInterface {
		use ExampleTrait;

		/**
		 * @param ParamObj $p
		 */
		public function setParam( ParamObj $i ) {

		}

		/**
		 * @return ReturnObj
		 */
		public function getReturn() : ReturnObj {
			return new ReturnObj;
		}

	}

}