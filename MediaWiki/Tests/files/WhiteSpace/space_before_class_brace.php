<?php

// phpcs:disable Generic.Files.OneObjectStructurePerFile.MultipleFound

/**
 * no space before class brace.
 */
class TestFailedExamples{
	# coding...
}

/**
 * too many space before class brace.
 */
class TestFailedExamples1 	{
	# coding...
}

/**
 * brace on the new line.
 */
class TesFailedExamples2
{
	# coding...
}

class TestPassedExamples {
	# coding...
}

class TestShouldFail extends AClass{
	# coding...
}

class TestThisIsOk extends SuperDuperLongClassName
	implements AnotherReallyLongClassName
{
	# code...
}

class TestThisIsNotOk
	extends SuperDuperLongClassName
	implements AnotherReallyLongClassName {
	# code...
}

interface ThisInterfaceIsOK {
}

interface ThisInterfaceShouldFail{
}

trait ThisTraitIsOK {
}

trait ThisTraitShouldFail{
}

$thisIsOK = new class () {
};

$thisShouldFail = new class (){
};

$thisIsOK2 = new class( [
	'foo' => 'bar',
] ) extends AClass {
};
