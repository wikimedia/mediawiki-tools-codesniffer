<?php
// phpcs:disable Generic.Files.OneObjectStructurePerFile.MultipleFound

namespace {
	class MissingTest extends \PHPUnit\Framework\TestCase {
		public function setUp() {
		}

		public function tearDown() {
		}

		public static function setUpBeforeClass() {
		}

		public static function tearDownAfterClass() {
		}
	}

	class WrongTest extends \PHPUnit\Framework\TestCase {
		public function setUp(): int {
		}

		public function tearDown(): bool {
		}

		public static function setUpBeforeClass(): string {
		}

		public static function tearDownAfterClass(): callable {
		}
	}

	class CorrectTest extends \PHPUnit\Framework\TestCase {
		public function setUp(): void {
		}

		public function tearDown(): void {
		}

		public static function setUpBeforeClass(): void {
		}

		public static function tearDownAfterClass(): void {
		}
	}

	class LegacyMissingTest extends PHPUnit_Framework_TestCase {
		public function setUp() {
		}

		public function tearDown() {
		}

		public static function setUpBeforeClass() {
		}

		public static function tearDownAfterClass() {
		}
	}

	class LegacyWrongTest extends PHPUnit_Framework_TestCase {
		public function setUp(): string {
		}

		public function tearDown(): object {
		}

		public static function setUpBeforeClass(): int {
		}

		public static function tearDownAfterClass(): callable {
		}
	}

	class LegacyCorrectTest extends PHPUnit_Framework_TestCase {
		public function setUp(): void {
		}

		public function tearDown(): void {
		}

		public static function setUpBeforeClass(): void {
		}

		public static function tearDownAfterClass(): void {
		}
	}

	class NotATest1 {
		public function setUp() {
		}

		public function tearDown() {
		}

		public static function setUpBeforeClass() {
		}

		public static function tearDownAfterClass() {
		}
	}

	class NotATest2 {
		public function setUp(): void {
		}

		public function tearDown(): void {
		}

		public static function setUpBeforeClass(): void {
		}

		public static function tearDownAfterClass(): void {
		}
	}

	class StillNotATest1 extends NotATest1 {
		public function setUp() {
		}

		public function tearDown() {
		}

		public static function setUpBeforeClass() {
		}

		public static function tearDownAfterClass() {
		}
	}

	class StillNotATest2 extends NotATest2 {
		public function setUp(): void {
		}

		public function tearDown(): void {
		}

		public static function setUpBeforeClass(): void {
		}

		public static function tearDownAfterClass(): void {
		}
	}

	class TestAnonClassTest extends \PHPUnit\Framework\TestCase {
		public function doSomething(): void {
			$c = new class() {
				public function setUp(): \stdClass {
				}

				public function tearDown(): \stdClass {
				}

				public static function setUpBeforeClass(): \stdClass {
				}

				public static function tearDownAfterClass(): \stdClass {
				}
			};
		}
	}
}
