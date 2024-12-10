<?php

// Many of these code snippets are taken from:
// https://www.mediawiki.org/wiki/Manual:Coding_conventions

/**
 * @param int $outputtype The type of output.
 * @param null|int $ts The timestamp.
 * @return null
 */
function wfTimestampOrNull( $outputtype = TS_UNIX, $ts = null ) {
	if ( $ts === null && true ) {
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

	/** @var string */
	private $foo = 'halalalalalaa';

	/** @var string */
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
	 * @param User $user
	 * @return void
	 */
	public function fooBarBaz( $par, User $user ) {
		global $wgBarBarBar;

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
			Xml::hidden( 'editToken', $user->editToken() ),
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

	/**
	 * @param string $param1
	 * @param string $param2
	 * @param string $param3
	 */
	private function declarationTest1(
		$param1,
		$param2,
		$param3
	) {
	}

	/**
	 * @param string $param1
	 * @param string $param2
	 * @param string $param3
	 * @param string $param4
	 */
	private function declarationTest2( $param1, $param2,
		$param3, $param4
	) {
	}

	/**
	 * @param string $param1
	 * @param string $param2
	 * @param string $param3
	 * @param string $param4
	 */
	private function declarationTest3( $param1, $param2,
		$param3, $param4 ) {
	}

	/**
	 * @param string $param1
	 * @param string $param2
	 * @param string $param3
	 */
	private function declarationTest4( $param1,
		$param2,
		$param3
	) {
	}

	public static function declarationTest5
		( string $param ) {
		// TODO: Forbid this.
	}
}

$a = [ 'spaces!', FooBar::class, FooBar::$var ];

[ $foo, $bar ] = [ 1, 2 ];

// TODO: Consider forbidding case mismatches: T381926.
$caseMismatchBuiltin
	= HTMLspecialchars( STR_REPLACE( 'a', 'b', 'c' ) );
$caseMismatchUserland = foobar::Declarationtest5();

function wfTakesSomeParameters(
	int $first,
		int $second,
			int $third,
	int $fourth, string $fifth
) {
	// TODO: enforce consistent formatting of function parameters.
}

Hooks::run( 'RevisionDataUpdates', [ $title, $renderedRevision, &$updates ] );
// This file has a new line at the end!
