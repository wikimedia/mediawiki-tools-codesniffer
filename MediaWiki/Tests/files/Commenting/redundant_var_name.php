<?php

class TestFailedExamples {

	/** @var $failNoType */
	private $failNoType;

	/** @var string $failType */
	private $failType;

	/** @var $failTypeWrongOrder string */
	private $failTypeWrongOrder;

	/** @var string $failStatic */
	private static $failStatic;

	/** @var $failStaticWrongOrder string */
	private static $failStaticWrongOrder;

	/** @var $failOldStyle string */
	var $failOldStyle;

}

class TestPassedExamples {

	/** @var string */
	private $passed;

	/** @var string */
	private static $passedStatic;

	private const CONST = 42;

}
