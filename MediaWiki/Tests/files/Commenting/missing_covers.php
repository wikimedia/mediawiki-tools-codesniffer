<?php
// phpcs:disable Generic.Files.OneObjectStructurePerFile
// phpcs:disable MediaWiki.Commenting.FunctionComment

/**
 * @covers FooBar
 */
class ClassCoversTest {
	public function testMethod() {
	}
}

class MethodCoversTest {
	/**
	 * @covers FooBar
	 */
	public function testCovered() {
	}

	public function testUncovered() {
	}

	/**
	 * @dataProvider provideDocBlock
	 */
	public function testDocBlock() {
	}

	public function provideDocBlock() {
	}

	/**
	 * @coversNothing
	 */
	public function testCoversNothing() {
	}
}
