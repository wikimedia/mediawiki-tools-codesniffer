<?php

// Many of these code snippets are taken from:
// https://www.mediawiki.org/wiki/Manual:Coding_conventions

/**
 * @param int $outputtype The type of output.
 * @param null|int $ts The timestamp.
 * @return null
 */
function wfTimestampOrNull( $outputtype = TS_UNIX, $ts = null ) {
	if ( is_null( $ts ) && true ) {
		return null;
	} else {
		return wfTimestamp( $outputtype, $ts );
	}
}

$wgAutopromote = [
	'autoconfirmed' => [ '&',
		[ APCOND_EDITCOUNT, &$wgAutoConfirmCount ],
		[ APCOND_AGE, &$wgAutoConfirmAge ],
	],
];

$namespaceNames = [
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Special',
	NS_MAIN             => '',
];

class FooBar extends BarBaz implements SomethingSomewhere {

	private $foo = 'halalalalalaa';

	public $var;
	/**
	 * @param string $word The input string.
	 * @return bool
	 */
	public function iDoCaseStuff( $word ) {
		switch ( $word ) {
			case 'lorem':
			case 'ipsum':
				$bar = 2;
				break;
			case 'dolor':
				$bar = 3;
				break;
			default:
				$bar = 0;
		}
		return strtolower( $bar ) == 'on'
		|| strtolower( $bar ) == 'true'
		|| strtolower( $bar ) == 'yes'
		|| preg_match( "/^\s*[+-]?0*[1-9]/", $bar );
	}

	/**
	 * @param string $word The input string.
	 * @return bool
	 */
	public function iDoCaseStuffTwo( $word ) {
		switch ( $word ) {
			case 'lorem':
			case 'ipsum':
				$bar = 2;
				break;
			case 'dolor':
				$bar = 3;
				break;
			default:
				$bar = 0;
		}
		return (bool)$bar;
	}
	/**
	 * @param string $par The test input.
	 * @return void
	 */
	public function fooBarBaz( $par ) {
		global $wgBarBarBar, $wgUser;

		if ( $par ) {
			return;
		}

		$wgBarBarBar->dobar(
			Xml::fieldset( wfMessage( 'importinterwiki' )->text() ) .
			Xml::openElement( 'form', [ 'method' => 'post', 'action' => $par,
				'id' => 'mw-import-interwiki-form' ] ) .
			wfMessage( 'import-interwiki-text' )->parse() .
			Xml::hidden( 'action', 'submit' ) .
			Xml::hidden( 'source', 'interwiki' ) .
			Xml::hidden( 'editToken', $wgUser->editToken() ),
			'secondArgument',
			wfMessage( 'message-key' )
				->inContentLanguage()
				->title( $this->getTitle() )
				->text(),
			Html::class
		);

		$foo = $par;
		return $foo + $wgBarBarBar + $this->foo;
	}
	/**
	 * @param FooBar $baz The FooBar object.
	 * @return array $cat The merged array.
	 */
	private function someFunction( FooBar $baz ) {
		$foo = [
			$baz,
			'memememememememee',
		];
		$cat = array_merge( $foo, [ 'barn', 'door' ] );
		return $cat;
	}
}

$a = [ 'spaces!', FooBar::class, FooBar::$var ];

Hooks::run( 'SecondaryDataUpdates', [ $title, $old, $recursive, $parserOutput, &$updates ] );
// This file has a new line at the end!
