<?php

// good
try {
	foo();
} catch ( Exception $bar ) {
	baz();
}

try {
	foo();
} catch ( RuntimeException $bar ) {
	baz();
} catch ( Exception $iAmRunningOutOfNames ) {
	justDoSomething();
}

function wfTestFunction() {
	try {
		foo();
	} catch ( Exception $bar ) {
		baz();
	}
}

// bad
try {
	foo();
}
catch ( Exception $bar ) {
	baz();
}

try {
	foo();
}
catch ( RuntimeException $bar ) {
	baz();
}

catch ( Exception $iAmRunningOutOfNames ) {
	justDoSomething();
}

function wfSecondTestFunction() {
	try {
		foo();
	}
	catch ( Exception $bar ) {
		baz();
	}
}
