<?php
// @phpcs:disable Generic.Files.OneObjectStructurePerFile.MultipleFound
class FailedExamples {
	/**
	 * @param string $foo
	 * @param ?MyClass $x
	 */
	public function docNullableArgOptional( $foo, MyClass $x = null ) {
	}

	/**
	 * @param MyClass|null $x
	 * @param string $foo
	 */
	public function nullableBeforeRequired( MyClass $x = null, $foo ) {
	}

	/**
	 * @param MyClass|null $x
	 * @param MyClass|null $y
	 * @param string $foo
	 */
	public function nullablesBeforeRequired( MyClass $x = null, MyClass $y = null, $foo ) {
	}

	/**
	 * @param MyClass|null $x
	 * @param string $foo
	 * @param MyClass|null $y
	 */
	public function mixedNullablesAndRequired1( MyClass $x = null, $foo, MyClass $y = null ) {
	}

	/**
	 * @param MyClass|null $x
	 * @param string $foo
	 * @param MyClass|null $y
	 * @param string $bar
	 */
	public function mixedNullablesAndRequired2( MyClass $x = null, $foo, MyClass $y = null, $bar ) {
	}

	/**
	 * @param MyClass|null $x
	 * @param ?MyClass $y
	 */
	public function mixedNullablesAndRequired3( MyClass $x = null, ?MyClass $y ) {
	}
}

class PassedExamples {
	/**
	 * @param ?MyClass $x
	 * @param string $foo
	 */
	public function genericNullable( ?MyClass $x, $foo ) {
	}

	/**
	 * @param string $foo
	 * @param MyClass|null $x
	 */
	public function nullableAfterRequired( $foo, MyClass $x = null ) {
	}

	/**
	 * @param MyClass|null $x
	 */
	public function nullableOnly( MyClass $x = null ) {
	}

	/**
	 * We allow this per T218816.
	 * @param string $foo
	 * @param MyClass|null $x
	 */
	public function docOptionalArgNullable( $foo, ?MyClass $x ) {
	}

	/**
	 * @param ?MyClass $x
	 * @param string $foo
	 */
	public function nullableWithDefault( ?MyClass $x = null, string $foo ) {
	}
}
