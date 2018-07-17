<?php
// @phpcs:disable Generic.Files.OneObjectStructurePerFile.MultipleFound

namespace {
	class MissingTest extends \PHPUnit\Framework\TestCase {
		public function setUp() {
		}

		public function tearDown() {
		}
	}

	class WrongTest extends \PHPUnit\Framework\TestCase {
		public function setUp() : int {
		}

		public function tearDown() : bool {
		}
	}

	class CorrectTest extends \PHPUnit\Framework\TestCase {
		public function setUp() : void {
		}

		public function tearDown() : void {
		}
	}

	class LegacyMissingTest extends PHPUnit_Framework_TestCase {
		public function setUp() {
		}

		public function tearDown() {
		}
	}

	class LegacyWrongTest extends PHPUnit_Framework_TestCase {
		public function setUp() : string {
		}

		public function tearDown() : object {
		}
	}

	class LegacyCorrectTest extends PHPUnit_Framework_TestCase {
		public function setUp() : void {
		}

		public function tearDown() : void {
		}
	}

	class NotATest1 {
		public function setUp() {
		}

		public function tearDown() {
		}
	}

	class NotATest2 {
		public function setUp() : void {
		}

		public function tearDown() : void {
		}
	}

	class StillNotATest1 extends NotATest1 {
		public function setUp() {
		}

		public function tearDown() {
		}
	}

	class StillNotATest2 extends NotATest2 {
		public function setUp() : void {
		}

		public function tearDown() : void {
		}
	}
}