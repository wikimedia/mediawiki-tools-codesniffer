<?php

// phpcs:disable Generic.Files.OneObjectStructurePerFile

abstract class TestFailedExamples {

	public function __construct( $a ) {
		$this->a = $a;
	}

	public function testNoDoc( $testVar ) {
		return $testVar;
	}

	/**
	 * @param int $testVar This should start uppercase and end stop.
	 *
	 */
	public function testNeedReturn( $testVar ) {
		return $testVar;
	}

	/**
	 * @param int $testVar This should start uppercase and end stop.
	 *
	 */
	public function testNeedReturnFromYield( $testVar ) {
		yield 'test' => [ 'a', $testVar ];
	}

	/**
	 * @param int $testVar 	This is test.
	 * @param int $_ 		For test.
	 * @return int $testVar This is test.
	 */
	public function testSingleSpaces( $testVar, $_ ) {
		return $testVar;
	}

	/**
	 * @param boolean $aBool A bool
	 *   With a long comment
	 * @param integer $anInt An int
	 * @return boolean And some text
	 */
	public function testLongTypes( $aBool, $anInt ) {
		return $aBool;
	}

	/**
	 * @return integer More text
	 */
	public function testIntReturn() {
		return 0;
	}

	/**
	 * There's a return in the body of the function,
	 * and a closure, so a return tag is needed.
	 */
	public function testComplexClosureReturn() {
		$a = static function () {
			return '';
		};

		b( $a );

		return $a();
	}

	/**
	 * @param boolean $aBool
	 * @param integer $anInt
	 * @return boolean
	 */
	public function testLongTypesNoComment( $aBool, $anInt ) {
		return $aBool;
	}

	/**
	 * @param boolean[] $bools A bool array
	 * @param integer[] $ints A int array
	 * @return boolean[] A bool array
	 */
	public function testLongArrayTypes( $bools, $ints ) {
		return $bools;
	}

	/**
	 * @param boolean $aBool A bool
	 * @param integer|string|boolean $mixed A mixed var
	 * @return boolean|integer|string A return value
	 */
	public function testLongTypesMixed( $aBool, $mixed ) {
		return $aBool ? $mixed : 1;
	}

	/**
	 * @param  bool    $aBool    A bool
	 * @param    int    $anInt    An int
	 * @return    bool    And some text
	 * @throws    Exception    And some text
	 */
	public function testTypesSpacing( $aBool, $anInt ) {
		return $aBool;
	}

	/**
	 * @params bool $aBool A bool
	 * @params int $anInt An int
	 * @returns bool And some text
	 * @throw \Exception
	 */
	public function testTagTypos( $aBool, $anInt ) {
		return $aBool;
	}

	/**
	 * @param {bool} [$aBool]: A bool
	 * @param [int] {$anInt}: An int
	 * @param \float[] [$aFloatArray]: A float array
	 * @return {bool}: And some text
	 * @throws {Exception}
	 */
	public function testVariablePunctation( $aBool, $anInt, $aFloatArray ) {
		return $aBool;
	}

	/**
	 * @inheritdoc
	 */
	public function testInheritWrongCase( $stuff, $more, $blah ) {
		$blah = $more . $stuff . $blah;
	}

	/**
	 * @deprecated since begin
	 */
	public function testDeprecated( $stuff, $more, $blah ) {
		$blah = $more . $stuff . $blah;
	}

	/**
	 * @param[in] int $in A comment
	 * @param[out] int &$out A comment
	 * @param[in,out] int &$inOut A comment
	 */
	public function testDirectionParam( $in, &$out, &$inOut ) {
		$out = $in * $inOut;
		$inOut = $in / $out;
	}

	/**
	 * Test with variadic argument in the list
	 * @param string $key A comment
	 * @param string $params,... A comment
	 */
	public function testVariadicArgInArgListWithComment1( $key, $params /* ... */ ) {
	}

	/**
	 * Test with variadic argument in the list
	 * @param string $key A comment
	 * @param string $params,... A comment
	 */
	public function testVariadicArgInArgListWithComment2( $key, ...$params /* ... */ ) {
	}

	/**
	 * Test with variadic argument in the list
	 * @param string $key A comment
	 * @param string ...$params A comment
	 */
	public function testVariadicArgInArgListWithComment3( $key, $params /* ... */ ) {
	}

	/**
	 * Test with variadic argument in the list
	 * @param string $key A comment
	 * @param string ...$params A comment
	 */
	public function testVariadicArgInArgListWithComment4( $key, ...$params /* ... */ ) {
	}

	/**
	 * Test with variadic argument in the list
	 * @param string $key A comment
	 * @param string $params,... A comment
	 */
	public function testVariadicArgInArgListWithoutComment1( $key, $params ) {
	}

	/**
	 * Test with variadic argument in the list
	 * @param string $key A comment
	 * @param string $params,... A comment
	 */
	public function testVariadicArgInArgListWithoutComment2( $key, ...$params ) {
	}

	/**
	 * Test with variadic argument in the list
	 * @param string $key A comment
	 * @param string ...$params A comment
	 */
	public function testVariadicArgInArgListWithoutComment3( $key, $params ) {
	}

	/**
	 * Test with variadic argument in the list
	 * @param string $key A comment
	 * @param string ...$params A comment
	 */
	public function testVariadicArgInArgListWithoutComment4( $key, ...$params ) {
	}

	/**
	 * Test with variadic argument not in the list
	 * @param string $key A comment
	 * @param string $params,... A comment
	 */
	public function testVariadicArgNotInArgList1( $key /* ... */ ) {
	}

	/**
	 * @param string $key A comment
	 * @param string $params,... A comment
	 */
	public function testVariadicArgNotInArgList2( $key /*, ...$params */ ) {
	}

	/**
	 * @param string $key A comment
	 * @param string $params,... A comment
	 */
	public function testVariadicArgNotInArgList3( $key /* ...$params */ ) {
	}

	/**
	 * @param string $key A comment
	 * @param string $params,... A comment
	 */
	public function testVariadicArgNotInArgList4( $key /* $params,... */ ) {
	}

	/**
	 * @param string $key A comment
	 */
	public function testVariadicArgNotDocumented( $key, ...$params ) {
	}

	/**
	 * @param string $key A comment
	 * @param string $params,... A comment
	 */
	public function testVariadicArgWithComments1( /* foo */ $key /* ... */ ) {
	}

	/**
	 * @param string $key A comment
	 * @param string $params,... A comment
	 */
	public function testVariadicArgWithComments2( /* not this... */ $key /* ... */ ) {
	}

	/**
	 * @param string         ...$args
	 */
	public function testVariadicSpacing( ...$args ) {
	}

	/**
	 * @param string         $args,...
	 */
	public function testLegacyVariadicSpacing() {
	}

	/**
	 * Test default null
	 * @param string $key
	 * @param mixed $value
	 * @param mixed|null $extra
	 */
	public function testDefaultNull( $key = null, $value = null, $extra = null ) {
	}

	/**
	 * Test optional key word
	 * @param string[optional] $key
	 * @param string[optional]|null $value
	 */
	public function testOptionalKeyword( $key, $value = null ) {
	}

	/**
	 * @param string $key
	 */
	public function superfluousAnnotation() {
	}

	/**
	 * @param string ...$params
	 */
	public function superfluousAnnotationVariadic() {
	}

	private function noDocsPrivate( $foo, $baz ) {
		echo $foo;
	}

	/**
	 * @param int &$refDoc
	 * @param int $refReal
	 * @param int $refDoesNotMatch Mixed test for ParamNameNoMatch and ParamPassByReference
	 * @param int $refNull
	 */
	public function testPassByReference( $refDoc, &$refReal, &$refMatch, &$refNull = null ) {
	}

	/**
	 * @param int &...$refDoc
	 */
	public function testVarArgsDocPassByReference( ...$refDoc ) {
	}

	/**
	 * @param int ...$refReal
	 */
	public function testVarArgsRealPassByReference( &...$refReal ) {
	}

	/** @param */
	public function testBadReturnAnnotation( $arg ) {
	}

	/** @return */
	public function testBadParamAnnotation() {
		return null;
	}

	/** @throws */
	public function testBadThrowsAnnotation() {
	}

	/**
	 * @param Int $i
	 */
	public function testUppercasePrimitive( $i ) {
	}

	/**
	 * @param String[] $s
	 */
	public function testUppercasePrimitiveArray( $s ) {
	}

	/**
	 * @param object $obj
	 * @return Object[]
	 */
	public function testDocObject( $obj ) {
		return [ $obj ];
	}

	/**
	 * @param int $i
	 */
	public function testReturnTypeHint( $i ): string {
		return '' . $i;
	}

	/**
	 * @param int $i
	 */
	public function testReturnTypeHintVoid( $i ): void {
	}

	/**
	 * @return string
	 * @return int
	 */
	public function testDuplicateReturn() {
		return 42;
	} // function

	public function testCommentDoesNotBelongsToFunction( $needsTest ) {
	}

	/**
	 * @param string $sameCase
	 * @param string $notSameCase
	 */
	public function testBadParamName( $sameCase, $notsamecase ) {
	}

	/**
	 * @return boolean
	 */
	abstract public function testAbstractBadReturnType();

	/**
	 */
	abstract public function testAbstractWithPhpReturnType(): bool;

	/**
	 * @param integer $param
	 */
	abstract public function testAbstractBadParameterType( $param );

	/**
	 * @param int|string $a
	 */
	public function testPartiallyStaticParamTypes( $a, int $b ): void {
	}
}

class TestPassedExamples {
	/**
	 * Without return.
	 * @param int $a for test.
	 */
	public function __construct( $a ) {
		$this->test( $a );
	}

	/**
	 * A blank return does not require a return tag
	 */
	public function emptyReturn() {
		if ( $fooBar ) {
			return;
		}

		baz();
	}

	/**
	 * Single Test function.
	 *
	 * @param int &$testVar For test.
	 * @return int $testVar For test.
	 */
	public function test( &$testVar ) {
		return $testVar;
	}

	/**
	 * @inheritDoc
	 */
	public function inherit( $stuff, $more, $blah ) {
		$blah = $more . $stuff . $blah;
	}

	/**
	 * {@inheritDoc}
	 */
	public function inherit2( $stuff, $more, $blah ) {
		$blah = $more . $stuff . $blah;
	}

	public function __toString() {
		return 'no documentation because obvious';
	}

	/**
	 * @param bool $aBool A bool
	 * @param int $anInt An int
	 * @return bool
	 */
	public function testShortTypes( $aBool, $anInt ) {
		return $aBool;
	}

	/**
	 * There's a return in the body of the function,
	 * but no return tag is needed.
	 */
	public function testClosureReturn() {
		$a = static function () {
			return '';
		};

		new class() {
			public function foo() {
				return '';
			}
		};
	}

	public function selfDocumenting() {
	}

	public function getButWeCanSeeThereIsNoReturn() {
	}

	public function getReturnMissing() {
		return true;
	}

	abstract public function isAbstractSoWeMustAssumeReturnIsMissing();

	/**
	 * This is fine
	 */
	abstract public function isAbstractButAlreadyCommented();

	abstract public function isFullyDocumented(): bool;

	/**
	 * @param string $key
	 * @param string ...$params
	 */
	public function withVariarg( $key, ...$params ) {
	}

	/**
	 * @param string $key A comment
	 */
	public function testCommentsWithDots( $key /* $this is allowed... */ ) {
	}

	/**
	 * @param $parser Parser {@inheritDoc}
	 * @param $operator: Expected '&' or '|'
	 * @param $oldtext string The content of the revision prior to $content.  When
	 *  null this will be loaded from the database.
	 */
	public function t174761( $parser, $operator, $oldtext = null ) {
	}

	/**
	 * @param ?string $key A comment
	 */
	public function nullableDocWithNullableTypeAndDefaultVal( ?string $key = null ) {
	}

	/**
	 * @param bool $b
	 */
	public function testLowercasePrimative( $b ) {
	}

	/**
	 * @param Foo|Bar $obj
	 * @return Foo|Bar
	 */
	public function testObjectTypehintOkay( object $obj ): object {
		return $obj;
	}

	/**
	 * @param Foo&Bar $obj
	 * @return Foo&Bar
	 */
	public function testObjectTypehintOkayIntersect( object $obj ): object {
		return $obj;
	}

	/**
	 * @param array<int, string> $a
	 * @return array<int, string>
	 */
	public function javaStyle( $a ) {
		return $a;
	}

	/**
	 * This documentation block should still be accepted despite the attribute between
	 * it and the function, test for T306941. Same with the next two cases below.
	 *
	 * @param int $offset
	 * @return mixed
	 */
	#[\ReturnTypeWillChange]
	public function offsetGet( $offset ) {
		if ( !is_numeric( $offset ) || (float)(int)$offset !== (float)$offset ) {
			throw new InvalidArgumentException( 'Offset must be an integer.' );
		}
		if ( $offset < 0 || $offset > count( $this->objects ) ) {
			throw new OutOfBoundsException( 'Offset is out of range.' );
		}
		return $this->objects[$offset];
	}

	/**
	 * @param bool $b
	 */
	#[AttributeFoo]
	#[AttributeBar]
	public function multipleAttributeLines( bool $b ) {
	}

	/**
	 * @param bool $b
	 */
	#[AttributeFoo, AttributeBar]
	public function multipleAttributesSameLine( bool $b ) {
	}

	public function staticParamTypesVoid( bool $b, int $i ): void {
		// when static types for all parameters and the return type are possible,
		// then doc comments are encouraged where useful, but not required:
		// no need to repeat the type in @param or @return when there is nothing else to say
	}

	public function staticParamTypesReturn( bool $b, int $i ): bool {
		return $b;
	}

	public function noParamsVoid(): void {
	}

	public function noParamsReturn(): string {
		return '';
	}

	public function getSomething(): int {
		return 0;
	}

	/** Convert a Message to a MessageValue. */
	public function convertMessage( Message $m ): MessageValue {
		// function documentation without repeating types
	}
}

class TestSimpleConstructor {
	public function __construct() {
		$this->info = 'no documentation because obvious';
	}
}

class TestReturnConstructor {
	/**
	 * With return.
	 * @param int $a for test.
	 * @parma int $b for another test.
	 */
	public function __construct( $a, $b ) {
		if ( $b instanceof TestPassedExamples ) {
			return $b;
		}
		$this->test( $a );
	}

	/* @noPhpDocComment */
	public function noPhpDocComment() {
	}
}

class TestFullyTypedConstructor {
	public function __construct( int $a ) {
	}
}

class TestClassTestCase {

	/**
	 * @dataProvider provideTestData
	 */
	public function testFunctionWithoutDocumentation( $a, $b ) {
	}

	public function provideTestData() {
		return [ [ 'a', 'b' ] ];
	}
}
