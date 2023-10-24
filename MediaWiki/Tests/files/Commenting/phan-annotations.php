<?php

class PhanTests {
	/**
	 * @var MyClass
	 * @phan-var MySpecificClass
	 */
	private $foo;

	/**
	 * @template T
	 * @phan-template T
	 * @param AuthenticationRequest[] $reqs
	 * @phan-param T[] $reqs
	 * @param string $class Class name
	 * @phan-param class-string<T> $class
	 * @param bool $allowSubclasses
	 * @return AuthenticationRequest|null
	 * @phan-return T|null
	 * @phan-side-effect-free
	 */
	public static function getSomething( array $reqs, $class, $allowSubclasses = false ) {
	}

	/**
	 * @phan-type TestData = array{name:string,case:callable}
	 * @param MyClass $a
	 * @param mixed $t
	 * @param mixed $f
	 * @param array $data
	 * @phan-param TestData $data
	 * @phan-assert MySpecificClass $a
	 * @phan-assert-true-condition $t
	 * @phan-assert-false-condition $f
	 */
	public static function assertsTests( $a, $t, $f, $data ) {
	}
}
