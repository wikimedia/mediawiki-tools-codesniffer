<?php

require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/vendor/squizlabs/php_codesniffer/autoload.php';

// Load Test Helper
require_once __DIR__ . '/MediaWiki/Tests/Helper.php';

// Trigger autoload of tokens
new PHP_CodeSniffer\Util\Tokens();

if ( !defined( 'PHP_CODESNIFFER_IN_TESTS' ) ) {
	define( 'PHP_CODESNIFFER_IN_TESTS', true );
}

if ( !defined( 'PHP_CODESNIFFER_CBF' ) ) {
	define( 'PHP_CODESNIFFER_CBF', false );
}

if ( !defined( 'PHP_CODESNIFFER_VERBOSITY' ) ) {
	define( 'PHP_CODESNIFFER_VERBOSITY', false );
}
