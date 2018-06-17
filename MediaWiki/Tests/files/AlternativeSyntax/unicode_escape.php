<?php

$doubles = [
	"\u{0}",
	"\u{00}",
	"\u{000}",
	"\u{0000}",
	"\u{00000}",
	"\u{000000}",
	"\u{0000000}",
	"\u{00000000}",

	"\u{a0}",
	"\u{A0}",
	"\u{0a0}",
	"\u{0A0}",
	"\u{00a0}",
	"\u{00A0}",
	"\u{000a0}",
	"\u{000A0}",
	"\u{0000a0}",
	"\u{0000A0}",
	"\u{00000a0}",
	"\u{00000A0}",
	"\u{000000a0}",
	"\u{000000A0}",

	"\u{abc}",
	"\u{ABC}",
	"\u{0abc}",
	"\u{0ABC}",
	"\u{00abc}",
	"\u{00ABC}",
	"\u{000abc}",
	"\u{000ABC}",
	"\u{0000abc}",
	"\u{0000ABC}",
	"\u{00000abc}",
	"\u{00000ABC}",

	"\u{abcd}",
	"\u{ABCD}",
	"\u{0abcd}",
	"\u{0ABCD}",
	"\u{00abcd}",
	"\u{00ABCD}",
	"\u{000abcd}",
	"\u{000ABCD}",
	"\u{0000abcd}",
	"\u{0000ABCD}",

	"\u{abcde}",
	"\u{ABCDE}",
	"\u{0abcde}",
	"\u{0ABCDE}",
	"\u{00abcde}",
	"\u{00ABCDE}",
	"\u{000abcde}",
	"\u{000ABCDE}",

	"\u{10ffff}",
	"\u{10FFFF}",
	"\u{010ffff}",
	"\u{010FFFF}",
	"\u{0010ffff}",
	"\u{0010FFFF}",
];

$s = '';
$prefixed = "$s\u{202a}";
$suffixed = "\u{202a}$s";
$prefixedAndSuffixed = "$s\u{202a}$s";
$withBracedVariables = "{$s}\u{202a}{$s}";

$multiline = "
Pok\u{e9}mon
From Wikipedia, the free encyclopedia
";

$multilineDoubleWithPrefix = "$s
Pok\u{e9}mon
From Wikipedia, the free encyclopedia
";

$multilineDoubleWithSuffix = "
Pok\u{e9}mon
From Wikipedia, the free encyclopedia
$s";

$multilineDoubleWithPrefixAndSuffix = "$s
Pok\u{e9}mon
From Wikipedia, the free encyclopedia
$s";

$heredoc = <<<EOD
Pok\u{e9}mon
From Wikipedia, the free encyclopedia
EOD;

$single = '\u{202a}';
$anotherDouble = "\u{202a}";
$multilineSingle = '\u{202a}
\u{202a}
\u{202a}';
$yetAnotherDouble = "\u{202a}";
$nowdoc = <<<'EOD'
Pok\u{e9}mon
From Wikipedia, the free encyclopedia
EOD;

/**
 * @param string $x
 * @return string
 */
function wfMyFunction( $x ) {
	return $x;
}

$myFunction = 'wfMyFunction';
${'\u{202a}'} = '';
${"\u{202a}"} = '';
$doubleWithBracedExpressionWithSingle = "{$myFunction('\u{202a}')}";
$doubleWithBracedExpressionWithDouble = "{$myFunction("\u{202a}")}";
$doubleWithBracedVariableWithSingle = "${'\u{202a}'}";
$doubleWithBracedVariableWithDouble = "${"\u{202a}"}";
$doubleWithMultilineBracedExpressionWithSingle = "{$myFunction(
	'\u{202a}'
)}";
$doubleWithMultilineBracedExpressionWithDouble = "{$myFunction(
	"\u{202a}"
)}";

$heredocWithBracedExpressionWithSingle = <<<EOD
{$myFunction('\u{202a}')}
EOD;
$heredocWithBracedExpressionWithDouble = <<<EOD
{$myFunction("\u{202a}")}
EOD;
$heredocWithBracedVariableWithSingle = <<<EOD
${'\u{202a}'}
EOD;
$heredocWithBracedVariableWithDouble = <<<EOD
${"\u{202a}"}
EOD;
$heredocWithMultilineBracedExpressionWithSingle = <<<EOD
{$myFunction(
	'\u{202a}'
)}
EOD;
$heredocWithMultilineBracedExpressionWithDouble = <<<EOD
{$myFunction(
	"\u{202a}"
)}
EOD;

$singleWithBracedExpressionWithDouble = '{$myFunction("\u{202a}")}';
$singleWithMultilineBracedExpressionWithDouble = '{$myFunction(
	"\u{202a}"
)}';

// The End
