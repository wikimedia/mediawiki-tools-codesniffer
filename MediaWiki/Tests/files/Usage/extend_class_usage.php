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

	public function failedConfigGlobal() {
		global $wgMyConfigGlobal;
		return $wgMyConfigGlobal;
	}

	public function passedNonConfigGlobals() {
		global $wgContLang;
		return $wgContLang->getCode();
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

class UserTest extends SpecialPage {

	/**
	 * @return User the user information.
	 */
	public static function passedExample() {
		return $wgUser;
	}

	/**
	 * @return User the user information.
	 */
	public function failedExample() {
		return $wgUser;
	}

	/**
	 * @return User the user information.
	 */
	public function passedExampleTwo() {
		return $this->getUser();
	}
}

abstract class RequestTest extends \ContextSource {

	/**
	 * @return mixed
	 */
	abstract public function abstractFunctionExample();

	/**
	 * @return WebRequest the request information.
	 */
	public static function passedExample() {
		return $wgRequest;
	}

	/**
	 * @return WebRequest the request information.
	 */
	public function failedExample() {
		return $wgRequest;
	}

	/**
	 * @return WebRequest the request information.
	 */
	public function passedExampleTwo() {
		return $this->getRequest();
	}
}

/**
 * Test, if a global not set by config is reported
 */
class FoundTest extends \QueryPage {
	/**
	 * @return mixed the request information.
	 */
	public function foundExampleExtensionSet() {
		global $wgMyNotConfigGlobal;
		return $wgMyNotConfigGlobal;
	}
}

/**
 * Test, if a global set by config is not reported
 * @phpcs:set MediaWiki.Usage.ExtendClassUsage nonConfigGlobals[] $wgMyNotConfigGlobal
 */
class NotFoundTest extends \ContextSource {
	/**
	 * @return mixed the request information.
	 */
	public function notFoundExampleExtensionSet() {
		global $wgMyNotConfigGlobal;
		return $wgMyNotConfigGlobal;
	}
}
