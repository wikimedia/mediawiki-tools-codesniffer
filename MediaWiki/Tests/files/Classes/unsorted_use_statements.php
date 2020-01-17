<?php

namespace TestSpace;

use MediaWiki\Sniffs\Classes\UnusedUseStatementSniff;
use const M_EULER;
use MediaWiki\Sniffs\Usage\DbrQueryUsageSniff;
use TestTrait;
use function array_values;

use MediaWiki\Sniffs\Usage\AssignmentInReturnSniff as AwesomeSniff;
use MediaWiki\Sniffs\Classes\UnsortedUseStatementsSniff;
use MediaWikiLangTestCase;

/**
 * @file comment
 */

use AfterNonWhitespaceTokens;

class Test extends MediaWikiLangTestCase {
	use TestTrait;

	public function __construct() {
		new UnusedUseStatementSniff();
		new UnsortedUseStatementsSniff();
		new DbrQueryUsageSniff();
		new AwesomeSniff();
		new AfterNonWhitespaceTokens();

		array_values( [ M_EULER ] );
	}
}
