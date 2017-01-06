<?php

class MessageTest extends ContextSource {

	/**
	 * @return string the unescaped message text.
	 */
	public static function passedExample() {
		return wfMessage( 'Test' );
	}

	/**
	 * @return string the unescaped message text.
	 */
	public function failedExample() {
		return wfMessage( 'Test' );
	}

	/**
	 * @return string the unescaped message text.
	 */
	public function passedExampleTwo() {
		return $this->msg( 'Test' );
	}
}

class OtherTest extends OtherExtendClass {

	/**
	 * @return string the unescaped message text.
	 */
	public static function passedExample() {
		return wfMessage( 'Test' );
	}

	/**
	 * @return string the unescaped message text.
	 */
	public function passedExampleOne() {
		return wfMessage( 'Test' );
	}

	/**
	 * @return string the unescaped message text.
	 */
	public function passedExampleTwo() {
		return $this->msg( 'Test' );
	}
}

class UserTest extends ContextSource {

	/**
	 * @return object the user information.
	 */
	public static function passedExample() {
		return $wgUser;
	}

	/**
	 * @return object the user information.
	 */
	public function failedExample() {
		return $wgUser;
	}

	/**
	 * @return object the user information.
	 */
	public function passedExampleTwo() {
		return $this->getUser();
	}
}

abstract class RequestTest extends ContextSource {

	/**
	 * @return mixed
	 */
	abstract public function abstractFunctionExample();

	/**
	 * @return object the request information.
	 */
	public static function passedExample() {
		return $wgRequest;
	}

	/**
	 * @return object the request information.
	 */
	public function failedExample() {
		return $wgRequest;
	}

	/**
	 * @return object the request information.
	 */
	public function passedExampleTwo() {
		return $this->getRequest();
	}
}
