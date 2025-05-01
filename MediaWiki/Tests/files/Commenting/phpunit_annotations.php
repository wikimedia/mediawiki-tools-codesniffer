<?php

// phpcs:disable Generic.Files.OneObjectStructurePerFile.MultipleFound

/**
 * Not a Test file
 * @coversDefaultClass Test
 */
class NonTestExamples {
	/**
	 * not a test function
	 * @cover Test
	 */
	public function noop() {
	}

	/**
	 * @backupGlobals
	 */
	public function testForbiddenAnnotation() {
	}

	/** Bad comment
	 * @coversNothing
	 */
}

/**
 * A Test file
 * @coverDefaultClass Test
 * @small
 */
class ExamplesTest {

	/**
	 * @cover this::testTagTypos()
	 * @covers
	 */
	public function testTagTypos() {
	}

	/**
	 * @coverNothing
	 */
	public function testNothing() {
	}

	/**
	 * @after
	 */
	public function isAfterTest() {
	}

	/**
	 * @dataProvider isAfterTest()
	 */
	public function notATestNamedFunction() {
	}

	/**
	 * @after
	 */
	public function extraTearDown() {
	}

	/**
	 * @afterClass
	 */
	public function extraTearDownAfterClass() {
	}

	/**
	 * @expectedException \Exception
	 * @expectedExceptionMessage Foobar
	 */
	public function expectedAnnotations() {
	}
}

trait TestTrait {
	/**
	 * @dataProvider provideNothing
	 */
	public function testNothing() {
	}
}

/**
 * Not a class comment
 * @group Test
 */

/**
 * interface comment
 * @group Test
 */
interface InterfaceTesting {
	/**
	 * @code{.dot}
	 * @endcode
	 *
	 * @dataProvider provideNothing
	 */
	public function testNothing();
}

/**
 * Not a class comment
 * @coverDefaultClass Test
 */

class Examples2Test {

	/**
	 * Comment does not belong to the function
	 * @group large
	 */

	public function testDoc() {
	}

}

use \Path\To\AnotherClass;
use Path\To\OtherClass;

/**
 * @covers OtherClass
 */
class RelativeCoversTest {

	/**
	 * @covers OtherClass::foo
	 */
	public function testFoo() {
		$instance = new OtherClass();
		$this->assertTrue( $instance->foo() );
	}
}

/**
 * @coversDefaultClass AnotherClass
 */
class RelativeCoversDefaultClassTest {

	/**
	 * @covers ::foo
	 */
	public function testFoo() {
		$instance = new AnotherClass();
		$this->assertTrue( $instance->foo() );
	}
}

/**
 * @covers \OtherClass
 */
class AbsoluteCoversTest {

	/**
	 * @covers \OtherClass::foo
	 */
	public function testFoo() {
		$instance = new \OtherClass();
		$this->assertTrue( $instance->foo() );
	}
}
