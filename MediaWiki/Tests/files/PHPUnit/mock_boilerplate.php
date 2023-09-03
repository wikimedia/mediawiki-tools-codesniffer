<?php

class MockBoilerplateTest extends \PHPUnit\Framework\TestCase {

	/** @coversNothing */
	public function testWillReplacement() {
		$mock = $this->createMock( FooBar::class );
		$mock->method( 'foo' )->will( $this->returnValue( 1 ) );
		$mock->method( 'bar' )
			->will( $this->returnValue( 2 ) );
		$mock->method( 'arg1' )->will( $this->returnArgument( 0 ) );
		$mock->method( 'ucfirst' )->will( $this->returnCallback( 'ucfirst' ) );
		$mock->method( 'boolIsTrue' )->will( $this->returnValueMap( [
			[ true, true ],
			[ false, false ],
		] ) );
		$mock->method( 'self' )->will( $this->returnSelf() );
		$mock->method( 'error' )->will( $this->throwException( $ex ) );
	}

	/** @coversNothing */
	public function testExactlyShortcut() {
		$mock = $this->createMock( FooBar::class );
		$mock->expects( $this->exactly( 1 ) )->method( 'foo' )->willReturn( 1 );
		$mock->expects( $this->exactly( 0 ) )
			->method( 'bar' );
	}

	/** @coversNothing */
	public function testWithEqualTo() {
		$mock = $this->createMock( FooBar::class );
		$mock->method( 'has' )->with( $this->equalTo( 'value' ) )->willReturn( true );
		$mock->method( 'get' )->with( $this->equalTo( 'value' ) )->willReturn( 1 );
		$mock->method( 'multi' )->with(
			$this->equalTo( 'first' ),
			'second',
			$this->equalTo( 'third' )
		)->willReturn( false );
		// equalTo() with a parameter that includes a comma (function call)
		$mock->method( 'calculated' )->with(
			$this->equalTo( addValues( 1, 2 ) )
		)->willReturn( false );
	}

	/** @coversNothing */
	public function testNotChanged() {
		$mock = $this->createMock( FooBar::class );

		$mock->expects( $this->exactly( 2 ) )->method( 'foo' );
		$mock->expects( $this->once() )->method( 'bar' );

		// equalTo() with a second parameter is skipped
		$mock->method( 'baz' )->with(
			$this->equalTo( 10, 0.1 )
		)->willReturn( false );

		// equalTo() within a logicalNot()
		$mock->method( 'qux' )
			->with(
				$this->logicalNot(
					$this->equalTo( 'quxParam' )
				)
			)
			->willThrowException(
				$this->createMock( InvalidArgumentException::class )
			);
		// equalTo() within a logicalOr()
		$mock->method( 'other' )
			->with(
				$this->logicalOr(
					$this->equalTo( 'other1' ),
					$this->equalTo( 'other2' )
				)
			)
			->willReturn( true );
	}

}
