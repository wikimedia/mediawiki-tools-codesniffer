<?php

class TestPropertyAnnotions {

	/**
	 * @param string
	 * @return string
	 */
	private string $property;

	/** @vra string */
	private string $typo;

	/** @inheritdoc */
	private $inheritedVar;

	/**
	 * @var string
	 * @code{,COMMA}
	 * @endcode
	 * @TODO Make awesomer
	 * @note: This is awesome!
	 * @warn Don't stop at the top
	 */
	public $goodExample;

	/**
	 * @var array
	 * @phan-var array<string,string>
	 */
	var $goodExampleVar;
}
