<?php

class TestFailedExamples {

	/* @var int */
	private $a;

	/** @var   integer Test short types */
	private $s;

	private $missingPrivate;
	protected $missingProtected;
	public $missingPublic;
	var $missingWithout;

	/** This is a comment */
	public $missingWithComment;

	/**
	 * @var int
	 * @var string
	 */
	private $duplicate;

	/** @var {string} */
	var $punctationString;

	/** @var string[] */
	var $arrayString;

	/** @var int: Some text */
	var $typeWithPuncation;

	/** @var ?callable(DOMNode):(?DOMNode) T268087 */
	public $combinator = null;

	/** @var */
	public $noType;

	/** @var Int */
	public $testUppercasePrimitive;

	/** @var String[] */
	public $testUppercasePrimitiveArray;

	/** @var object */
	public $testObjectTypeHint;

	/** @var Object[]|null */
	public $testObjectArrayTypeHint;
}

class TestPassedExamples {

	/**
	 * @see OtherClass
	 * @var int
	 */
	private $a;

	/** @var int */
	protected $b;

	/** @var int */
	public $c;

	/** @var int */
	var $d;

	/** @deprecated */
	var $dep;

	/** @inheritDoc */
	public $inherit;

	/**
	 * Test should not process local vars
	 * @param int $a1
	 * @param int $a2
	 * @return int
	 */
	public function test( $a1, $a2 ) {
		return $a1 * $a2;
	}
}
