<?php

// Indented with tabs
namespace ExampleWithTabs {
	use SecondInterface;
	use FirstInterface;
	use ThirdInterface;
	use FourthInterface;

	class Demo implements FirstInterface, SecondInterface, ThirdInterface, FourthInterface {

	}

}

// Indented with spaces
namespace ExampleWithSpaces {
    use SecondInterface;
    use FirstInterface;
    use ThirdInterface;
    use FourthInterface;

    class Demo implements FirstInterface, SecondInterface, ThirdInterface, FourthInterface {

    }

}

// Bracketed but not indented
namespace ExampleNoIndent {
use SecondInterface;
use FirstInterface;
use ThirdInterface;
use FourthInterface;

class Demo implements FirstInterface, SecondInterface, ThirdInterface, FourthInterface {

}

}

// Passing
namespace AllSorted {
	use FirstInterface;
	use FourthInterface;
	use SecondInterface;
	use ThirdInterface;

	class Demo implements FirstInterface, SecondInterface, ThirdInterface, FourthInterface {

	}

}
