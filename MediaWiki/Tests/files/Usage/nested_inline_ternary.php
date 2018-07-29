<?php

// Test cases posted by Anomie (Brad Jorsch) on 2017-07-24 at
// <https://phabricator.wikimedia.org/T171520>:

// Fail
$x = $foo ? 'A' : $bar ? 'B' : 'C';
$x = $foo->bar()->baz() ? 'A' : $bar->quux()->huh() ? 'B' : 'C';
$x = $foo['bar'] ? 'A' : $foo['baz'] ? 'B' : 'C';
$x = $foo['bar']->baz() ? 'A' : $foo['baz']->baz() ? 'B' : 'C';
$x = $foo->bar()['bar'] ? 'A' : $foo->bar()['baz'] ? 'B' : 'C';
$x = $foo ? 'A' : ( $bar || $baz ) ? 'B' : 'C';
$x = $foo ? 'A' : $bar || $baz ? 'B' : 'C';
$x = ( $foo ? 'A' : $bar ? 'B' : 'C' );

// Fixable warning
$x = $foo ? $bar ? 'B' : 'C' : 'D';

// Pass
$x = [ $foo ? 'A' : 'B', $bar ? 'C' : 'D' ];
$x = ( $foo ? 'A' : $bar ) ? 'B' : 'C';
$x = $foo ? 'A' : ( $bar ? 'B' : 'C' );

// Additional test cases:

/**
 * @param string $letter
 * @return int|null
 */
function wfLetterToDigitBuggy( $letter ) {
	return $letter === 'Q' ? 1 :
		$letter === 'W' ? 2 :
		$letter === 'E' ? 3 :
		$letter === 'R' ? 4 :
		$letter === 'T' ? 5 :
		$letter === 'Y' ? 6 :
		$letter === 'U' ? 7 :
		$letter === 'I' ? 8 :
		$letter === 'O' ? 9 :
		$letter === 'P' ? 0 :
		null;
}

/**
 * @param string $ltr
 * @return int|null
 */
function wfLetterToDigitConfusing( $ltr ) {
	return $ltr !== 'Q' ? $ltr !== 'W' ? $ltr !== 'E' ? $ltr !== 'R' ? $ltr !== 'T'
		? $ltr !== 'Y' ? $ltr !== 'U' ? $ltr !== 'I' ? $ltr !== 'O' ? $ltr !== 'P'
		? null : 0 : 9 : 8 : 7 : 6 : 5 : 4 : 3 : 2 : 1;
}

// Ternary operators inside closures should not be flagged just because the
// closures are part of an inline ternary statement
$x = $foo ?
	function ( $x ) {
		return $x ? 12 : 34;
	} :
	function ( $x ) {
		return $x ? 56 : 78;
	};

// But the bodies of closures should themselves be checked
$x = $foo ?
	function ( $x, $y ) {
		return $x ? $y ? 1 : 2 : 34;
	} :
	function ( $x, $y ) {
		return $x ? 56 : $y ? 7 : 8;
	};

// Likewise for parenthesized subexpressions
$x = $foo ? ( $x ? 12 : 34 ) : ( $x ? 56 : 78 );
$x = $foo ? ( $x ? $y ? 1 : 2 : 34 ) : ( $x ? 56 : $y ? 7 : 8 );

// Likewise for bracketed subexpressions
$x = $foo ? $bar[$x ? 12 : 34] : $bar[$x ? 56 : 78];
$x = $foo ? $bar[$x ? $y ? 1 : 2 : 34] : $bar[$x ? 56 : $y ? 7 : 8];
$x = $foo ? $bar{$x ? 12 : 34} : $bar{$x ? 56 : 78};
$x = $foo ? $bar{$x ? $y ? 1 : 2 : 34} : $bar{$x ? 56 : $y ? 7 : 8};

$x = $foo[$bar ? 12 : 34] ? 56 : 78;
$x = $foo[$bar ? $baz ? 12 : 34 : 56] ? 78 : 90;
$x = $foo[$bar ? 12 : $baz ? 34 : 56] ? 78 : 90;
$foo[$bar ? 12 : 34] = $baz ? 56 : 78;
$foo[$bar ? $baz ? 12 : 34 : 56] = $quux ? 78 : 90;
$foo[$bar ? 12 : $baz ? 34 : 56] = $quux ? 78 : 90;

// Assignment within the "then" part should not cause the "else" part
// to be ignored
$x = $foo ? $baz = 1 : $bar ? $baz = 2 : 3;
$x = $foo ? $baz += 1 : $bar ? $baz += 2 : 3;

// Likewise for logical AND/XOR/OR
$x = $foo ? 1 and 1 : $bar ? 2 and 2 : 3;
$x = $foo ? 1 xor 1 : $bar ? 2 xor 2 : 3;
$x = $foo ? 1 or 1 : $bar ? 2 or 2 : 3;

// And print, etc.
$x = $foo ? print 2 : $bar ? print 3 : 4;
$x = $foo ? 0 + print 2 : $bar ? 0 + print 3 : 4;

// Do not flag assignment of an inline ternary statement within "else" part as
// having possible unintended behavior arising from the left associativity
// of PHP's ternary operator
$errmsg = '';
echo $retval !== false
	? "Value: $retval"
	: 'Error: ' . $errmsg = isset( $errmsgs[$errno] )
		? $errmsgs[$errno] . " (code $errno)"
		: "code $errno";

// Likewise for inline ternary statements on both sides of logical AND/XOR/OR
$a = ( $b ? $c : $d and $e ? $f : $g );
$a = ( $b ? $c : $d xor $e ? $f : $g );
$a = ( $b ? $c : $d or $e ? $f : $g );

// And print, etc. within "else" part
$a = $b ? $c : print $d;
$a = $b ? $c : 0 + print $d;

// Do flag an inline ternary statement within an assignment within "then" part
// as needing parentheses for clarity
$a = $b ? $c = $d ? $e : $f : $g;

// Likewise for inline ternary statements on either or both sides of
// logical AND/XOR/OR
$a = $b ? $c ? $d : $e and $f : $g;
$a = $b ? $c and $d ? $e : $f : $g;
$a = $b ? $c ? $d : $e and $f ? $g : $h : $i;
$a = $b ? $c ? $d : $e xor $f : $g;
$a = $b ? $c xor $d ? $e : $f : $g;
$a = $b ? $c ? $d : $e xor $f ? $g : $h : $i;
$a = $b ? $c ? $d : $e or $f : $g;
$a = $b ? $c or $d ? $e : $f : $g;
$a = $b ? $c ? $d : $e or $f ? $g : $h : $i;

// And print, etc. within "then" part
$a = $b ? print $c ? $d : $e : $f;
$a = $b ? 0 + print $c ? $d : $e : $f;

// Stacking expressions using the short form of the ternary operator is OK
// without parentheses, the left associativity is unlikely to matter
$a = $b ?: $c ?: $d;

// Mixing that with the complete form of the ternary operator is not OK
$a = $b ? $c : $d ?: $e;
$a = $b ?: $c ? $d : $e;
$a = $b ? $c ?: $d : $e;

// Except with parentheses
$a = $b ? $c : ( $d ?: $e );
$a = $b ?: ( $c ? $d : $e );
$a = $b ? ( $c ?: $d ) : $e;
