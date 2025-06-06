<?php

namespace SomeExample {
	use FirstInterface;

	use SecondInterface;

	class Demo implements FirstInterface, SecondInterface {

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
