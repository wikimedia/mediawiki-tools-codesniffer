<?php
namespace FooBar;

use FooBar\Baz;
use \InvalidArgumentException;
use Something\AFunctionName;
use Something\ClassProp1;
use Something\ClassProp2;
use Something\ClassProp3;
use Something\InAParam;
use Something\InAThrows;
use Something\InAVar;
use Something\InAVar2;
use Something\InAVar3;
use Something\InAVar4;
use Something\InAVar5;
use Something\InAVar6;
use Something\InAVar7;
use Something\InAVar8;
use Something\InAVar9;
use Something\OneTwo as ThreeFour;
use Something\Partial;
use Something\Something;
use Something\That\Is\Unused;
use Something\That\Is\Used;
use Something\UnusedAlias as SomeAlias;
use Something\UsedForPhanVar;
use Something\UsedForPhanVarComplex;
use \Something\UsedForPhanVarForce;
use Something\UsedForVar;
use Used\But\Always\FullyQualified;
use Wikimedia\Database;
use Wikimedia\Rdbms\ILBFactory;
use Wikimedia\Rdbms\LBFactory;
use function FooBar/*comment*/\someFunction;
use const FooBar \ SOME_CONST;

$a = new Baz();
$b = new Used();
$c = new ThreeFour();

class UnusedUseTest {
	/**
	 * @coversNothing
	 * @throws InAThrows
	 * @param InAParam $a A variable
	 * @return Database
	 */
	public function testDatabase( $a ) {
		return $a;
	}
}

class Foo {
	use SomeThing;
	use AnotherThing;

	/**
	 * @var InAVar $thing2 is another property
	 */
	private $thing;

	/**
	 * @var array<int, array<int, InAVar2>>|null $thing2
	 */
	var $thing2;

	/**
	 * @var $thing3 null|InAVar3
	 */
	private ?InAVar3 $thing3;

	/**
	 * @var InAVar4[]
	 */
	private $thing4;

	/**
	 * @var (InAVar5|InAVar6)[]
	 */
	private $thing5;

	/**
	 * @var InAVar7<InAVar8,InAVar9>
	 */
	private $thing6;

	/**
	 * @var Partial\InAVar10|\Unused\NamespaceLooksLikeClass
	 */
	private $thing7;

	/**
	 * @var ILBFactory
	 */
	private $lbFactory;

	/**
	 * @var \Used\But\Always\FullyQualified
	 */
	private $thing8;

	/**
	 * @param ILBFactory $lbFactory
	 */
	public function __construct( ILBFactory $lbFactory ) {
		$this->lbFactory = $lbFactory;
		self::lbFactory();
		someFunction( [ SOME_CONST ] );
	}

	/**
	 * @param array $arr
	 * @return int
	 */
	public function testPhanVar( $arr ) {
		/** @var $exampleVar UsedForVar */
		'@phan-var array<int, array<int, UsedForPhanVar>> $exampleVar';
		'@phan-var InAVar7<InAVar8,UsedForPhanVarComplex> $exampleVar';
		'@phan-var BadCode$bad';
		'@phan-var NonExistClass $nonExist';
		$exampleVar = $arr[1];
		return $exampleVar->getNumber();
	}

	/**
	 * @param array $arr
	 * @return int
	 */
	public function testPhanVarForce( $arr ) {
		'@phan-var-force UsedForPhanVarForce $exampleVar';
		$exampleVar = $arr['key'];
		return $exampleVar->getNumber();
	}

	public function aFunctionName() {
	}
}

$fn = static function () use ( $a ) {
	return $a->methodCall();
};

/**
 * @property ClassProp1 $foo
 * @property-read ClassProp2 $bar
 * @property-write ClassProp3 $bar
 */
class CommentProps {
}
