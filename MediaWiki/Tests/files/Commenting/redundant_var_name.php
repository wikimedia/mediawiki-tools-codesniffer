<?php

class TestFailedExamples {

	/** @var $failNoType */
	private $failNoType;

	/** @var string $failType */
	private $failType;

	/** @var $failTypeWrongOrder string */
	private $failTypeWrongOrder;

	/** @var $failOldStyle string */
	var $failOldStyle;

}

class TestPassedExamples {

	/** @var string */
	private $passed;

	/** @var string */
	private static $passedStatic;

	private const CONST = 42;

	/** @var string $passStatic */
	private static $passStatic;

}
