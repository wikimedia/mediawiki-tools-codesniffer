<?php

// TODO: Enforce `::` and `->` at the beginning of each line.
StaticCall::
	someMethod();
StaticCall
	::someMethod();
$someClass->
	someMethod();
$someClass
	->someMethod();

// TODO: Detect missing indentation
SomeClass
::missingIndentation();
SomeClass::
missingIndentation();
$someObject
->missingIndentation();
$someObject->
missingIndentation();
