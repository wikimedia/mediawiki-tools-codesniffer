<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\Sniffs\Commenting;

use MediaWiki\Sniffs\PHPUnit\PHPUnitTestTrait;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Tokens\Collections;

class PhpunitAnnotationsSniff implements Sniff {
	use PHPUnitTestTrait;

	/**
	 * Annotations this sniff should work on
	 * Maybe specify a replacement and the sniff code
	 * - Ignore @author, because it also used outside of tests
	 * - Ignore @codeCoverageIgnore, because it can be used outside of tests
	 * - Ignore @uses, because it is also a PHPDoc annotation
	 *
	 * If an annotation is found outside of a test classes, it is reported.
	 *
	 * @see https://phpunit.de/manual/current/en/appendixes.annotations.html
	 */
	private const ALLOWED_ANNOTATIONS = [
		'@after' => true,
		'@afterClass' => true,

		'@before' => true,
		'@beforeClass' => true,

		'@preCondition' => true,
		'@postCondition' => true,

		'@covers' => true,
		'@cover' => [ '@covers', 'SingularCover' ],

		'@coversDefaultClass' => true,
		'@coverDefaultClass' => [ '@coversDefaultClass', 'SingularCoverDefaultClass' ],

		'@coversNothing' => true,
		'@coverNothing' => [ '@coversNothing', 'SingularCoverNothing' ],

		'@dataProvider' => true,

		'@group' => true,
		'@requires' => true,
		'@depends' => true,

		'@small' => [ '@group small', 'GroupAliasSmall' ],
		'@medium' => [ '@group medium', 'GroupAliasMedium' ],
		'@large' => [ '@group large', 'GroupAliasLarge' ],

		'@testWith' => true,
		'@doesNotPerformAssertions' => true,

		// From johnkary/phpunit-speedtrap
		'@slowThreshold' => true,
	];

	/**
	 * A list of forbidden annotations. True as message will use a default message.
	 *
	 * If an annotation is found outside of a test classes, it is reported with another message.
	 */
	private const FORBIDDEN_ANNOTATIONS = [
		// Name the function with test prefix, some other sniffs depends on that
		'@test' => 'Do not use %s, name the function to begin with "test".',

		'@testdox' => true,
		'@backupGlobals' => true,
		'@backupStaticAttributes' => true,
		'@excludeGlobalVariableFromBackup' => true,
		'@excludeStaticPropertyFromBackup' => true,
		'@preserveGlobalState' => true,

		'@runTestsInSeparateProcesses' => true,
		'@runInSeparateProcess' => true,

		// Removed in PHPUnit 7, T234597
		'@expectedException' => 'Do not use %s, use $this->expectException().',
		'@expectedExceptionCode' => 'Do not use %s, use $this->expectExceptionCode().',
		'@expectedExceptionMessage' => 'Do not use %s, use $this->expectExceptionMessage().',
		'@expectedExceptionMessageRegExp' => 'Do not use %s, use $this->expectExceptionMessageMatches().',
	];

	private const EMPTY_ANNOTATIONS = [
		'@coversNothing',
		'@coverNothing',
		'@doesNotPerformAssertions',
		'@small',
		'@medium',
		'@large',
		'@after',
		'@afterClass',
		'@before',
		'@beforeClass',
		'@preCondition',
		'@postCondition',
	];

	/**
	 * A list of naming patterns for annotations.
	 * Annotations not found here using default test* name
	 */
	private const FUNCTION_NAMING_PATTERN = [
		'@after' => [
			'regex' => '/TearDown$/',
			'message' => 'tearDown functions (*TearDown)',
			'code' => 'NotTearDownFunction',
		],
		'@afterClass' => [
			'regex' => '/TearDownAfterClass$/',
			'message' => 'tearDown functions (*TearDownAfterClass)',
			'code' => 'NotTearDownAfterClassFunction',
		],
		'@before' => [
			'regex' => '/SetUp$/',
			'message' => 'setUp functions (*SetUp)',
			'code' => 'NotSetUpFunction',
		],
		'@beforeClass' => [
			'regex' => '/SetUpBeforeClass$/',
			'message' => 'setUp functions (*SetUpBeforeClass)',
			'code' => 'NotSetUpBeforeClassFunction',
		],
		'@preCondition' => [
			'regex' => '/PreConditions$/',
			'message' => 'assertPreConditions functions (*PreConditions)',
			'code' => 'NotAssertPreConditionsFunction',
		],
		'@postCondition' => [
			'regex' => '/PostConditions$/',
			'message' => 'assertPostConditions functions (*PostConditions)',
			'code' => 'NotAssertPostConditionsFunction',
		],
		'*' => [
			'regex' => '/^(?:test|provide)|Provider$/',
			'message' => 'test functions',
			'code' => 'NotTestFunction',
		],
	];

	private const ABSOLUTE_CLASS_ANNOTATIONS = [
		'@covers' => 'AbsoluteCovers',
		'@coversDefaultClass' => 'AbsoluteCoversDefaultClass',
	];

	private array $useClasses = [];

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register(): array {
		return [ T_OPEN_TAG, T_USE, T_DOC_COMMENT_OPEN_TAG ];
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param File $phpcsFile
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		if ( $tokens[$stackPtr]['code'] === T_OPEN_TAG ) {
			// Run into a new file.
			$this->useClasses = [];
		} elseif ( $tokens[$stackPtr]['code'] === T_USE ) {
			$this->processUse( $phpcsFile, $stackPtr );
		} else {
			$this->processDocBlock( $phpcsFile, $stackPtr );
		}
	}

	/**
	 * @param File $phpcsFile
	 * @param int $stackPtr Position of T_USE
	 * @return void
	 */
	private function processUse( File $phpcsFile, int $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		$next = $phpcsFile->findNext( Tokens::$emptyTokens, $stackPtr + 1, null, true );

		$className = '';
		while ( in_array( $tokens[$next]['code'], [ T_STRING, T_NS_SEPARATOR ] ) ) {
			$className .= $tokens[$next++]['content'];
		}
		$className = ltrim( $className, '\\' );

		if (
			// Exclude: not `use` for classes, group of use statements or use of `as`;
			$tokens[$next]['code'] === T_SEMICOLON &&
			// Exclude: classes without namespaces.
			str_contains( $className, '\\' )
		) {
			$this->useClasses[$tokens[$next - 1]['content']] = '\\' . $className;
		}
	}

	/**
	 * @param File $phpcsFile
	 * @param int $stackPtr Position of T_DOC_COMMENT_OPEN_TAG
	 * @return void
	 */
	public function processDocBlock( File $phpcsFile, int $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		$end = $tokens[$stackPtr]['comment_closer'];
		foreach ( $tokens[$stackPtr]['comment_tags'] as $tag ) {
			$this->processDocTag( $phpcsFile, $tokens, $tag, $end );
		}
	}

	/**
	 * @param File $phpcsFile
	 * @param array[] $tokens
	 * @param int $tag Token position of the tag
	 * @param int $end Token position of the end of the comment
	 */
	private function processDocTag( File $phpcsFile, array $tokens, int $tag, int $end ): void {
		$tagText = $tokens[$tag]['content'];
		$forbidden = array_key_exists( $tagText, self::FORBIDDEN_ANNOTATIONS );

		// Check for forbidden annotations
		if ( $forbidden ) {
			$message = self::FORBIDDEN_ANNOTATIONS[$tagText] === true
				? 'The phpunit annotation %s should not be used.'
				: self::FORBIDDEN_ANNOTATIONS[$tagText];
			$phpcsFile->addWarning(
				$message,
				$tag, $this->createSniffCode( 'Forbidden', $tagText ), [ $tagText ]
			);
			return;
		}

		$allowed = array_key_exists( $tagText, self::ALLOWED_ANNOTATIONS );
		if ( !$allowed ) {
			// Nothing to work in this sniff
			return;
		}

		if ( $tokens[$end]['level'] === 0 ) {
			$objectToken = $this->findCommentElement( T_CLASS, $phpcsFile, $tokens, $end );
			if ( !$objectToken ) {
				$phpcsFile->addWarning(
					'The phpunit annotation %s should only be used in class level comments.',
					$tag, 'NotClass', [ $tagText ]
				);
				return;
			}
		} else {
			$objectToken = $this->findObjectStructureTokenFunctionLevel( $tokens, $end );
			if ( !$objectToken ) {
				$phpcsFile->addWarning(
					'The phpunit annotation %s should only be used inside classes or traits.',
					$tag, 'NotInClassTrait', [ $tagText ]
				);
				return;
			}
		}
		if ( $tokens[$objectToken]['code'] === T_CLASS &&
			!$this->isTestClass( $phpcsFile, $objectToken )
		) {
			$phpcsFile->addWarning(
				'The phpunit annotation %s should only be used inside test classes.',
				$tag, 'NotTestClass', [ $tagText ]
			);
			return;
		}

		// Normalize some tags
		if ( is_array( self::ALLOWED_ANNOTATIONS[$tagText] ) ) {
			[ $replacement, $sniffCode ] = self::ALLOWED_ANNOTATIONS[$tagText];
			$fix = $phpcsFile->addFixableWarning(
				'Use %s annotation instead of %s',
				$tag, $sniffCode, [ $replacement, $tagText ]
			);
			if ( $fix ) {
				$phpcsFile->fixer->replaceToken( $tag, $replacement );
			}
		}

		// Check if there is some text behind or not
		if ( !in_array( $tagText, self::EMPTY_ANNOTATIONS ) ) {
			$next = $phpcsFile->findNext( [ T_DOC_COMMENT_WHITESPACE ], $tag + 1, $end, true );
			if ( $tokens[$next]['code'] !== T_DOC_COMMENT_STRING ) {
				$phpcsFile->addWarning(
					'The phpunit annotation %s must be followed by text.',
					$tag, $this->createSniffCode( 'Empty', $tagText ), [ $tagText ]
				);
			} elseif ( isset( self::ABSOLUTE_CLASS_ANNOTATIONS[$tagText] ) ) {
				$coveredClass = explode( '::', $tokens[$next]['content'] )[0];
				$searchClass = ltrim( $coveredClass, '\\' );
				if ( isset( $this->useClasses[$searchClass] ) ) {
					$fix = $phpcsFile->addFixableWarning(
						'Use absolute class name (%s) for %s annotation instead',
						$next, self::ABSOLUTE_CLASS_ANNOTATIONS[$tagText],
						[ $this->useClasses[$searchClass], $tagText ]
					);
					if ( $fix ) {
						$replace = $this->useClasses[$searchClass] .
							substr( $tokens[$next]['content'], strlen( $coveredClass ) );
						$phpcsFile->fixer->replaceToken( $next, $replace );
					}
				}
			} elseif ( $tagText === '@dataProvider' ) {
				$providerName = trim( $tokens[$next]['content'] );
				if ( preg_match( '/^([^\p{P}]*)(\p{P}+)$/', $providerName, $matches ) ) {
					$providerName = $matches[1];
					$fix = $phpcsFile->addFixableWarning(
						'The phpunit annotation @dataProvider should not end with punctuation "%s"',
						$tag,
						'NotPunctuationDataProvider',
						[ $matches[2] ]
					);
					if ( $fix ) {
						$phpcsFile->fixer->replaceToken( $next, $providerName );
					}
				}
				// Ignore data provider in another class
				if ( !str_contains( $providerName, '::' ) &&
					!str_starts_with( $providerName, 'provide' ) &&
					!str_ends_with( $providerName, 'Provider' )
				) {
					$phpcsFile->addWarning(
						'The data provider function "%s" should start with "provide" or end with "Provider".',
						$tag, 'WrongDataProviderName', [ $providerName ]
					);
				}
			}
		}

		// Check the name of the function
		if ( $tokens[$tag]['level'] > 0 ) {
			$namingPattern = self::FUNCTION_NAMING_PATTERN[$tagText] ?? self::FUNCTION_NAMING_PATTERN['*'];

			$functionToken = $this->findCommentElement( T_FUNCTION, $phpcsFile, $tokens, $end );
			if ( !$functionToken ||
				!$this->isFunctionOkay( $phpcsFile, $functionToken, $namingPattern['regex'] )
			) {
				$phpcsFile->addWarning(
					'The phpunit annotation %s should only be used for %s.',
					$tag, $namingPattern['code'], [ $tagText, $namingPattern['message'] ]
				);
			}
		}
	}

	/**
	 * @param string $prefix
	 * @param string $annotation
	 *
	 * @return string
	 */
	private function createSniffCode( string $prefix, string $annotation ): string {
		return $prefix . ucfirst( ltrim( $annotation, '@' ) );
	}

	/**
	 * Find the element (class, function) this comment belongs to. This will allow empty lines between the comment and
	 * the element (since PHPUnit just ignores them), so make sure the comment does actually belong to the element (and
	 * is not say a file or stray comment), for example by verifying that it contains PHPUnit-specific tags.
	 *
	 * @param int $elementTokenType T_CLASS, T_FUNCTION
	 * @param File $phpcsFile
	 * @param array[] $tokens
	 * @param int $commentEnd
	 * @return int|false
	 */
	private function findCommentElement( int $elementTokenType, File $phpcsFile, array $tokens, int $commentEnd ) {
		$find = Tokens::$emptyTokens;
		if ( $elementTokenType === T_FUNCTION ) {
			$find = array_merge( $find, Tokens::$methodPrefixes );
		} elseif ( $elementTokenType === T_CLASS ) {
			$find = array_merge( $find, Collections::classModifierKeywords() );
		}
		$nextCandidate = $phpcsFile->findNext( $find, $commentEnd + 1, null, true );
		while ( isset( $tokens[$nextCandidate]['attribute_closer'] ) ) {
			$nextCandidate = $phpcsFile->findNext(
				Tokens::$emptyTokens,
				$tokens[$nextCandidate]['attribute_closer'] + 1,
				null,
				true
			);
		}

		if ( $tokens[$nextCandidate]['code'] === $elementTokenType ) {
			return $nextCandidate;
		}

		return false;
	}

	/**
	 * Find the class or trait this function level comment depends on.
	 *
	 * @param array[] $tokens
	 * @param int $commentEnd
	 * @return int|false
	 */
	private function findObjectStructureTokenFunctionLevel( array $tokens, int $commentEnd ) {
		foreach ( $tokens[$commentEnd]['conditions'] as $ptr => $type ) {
			if ( $type === T_CLASS || $type === T_TRAIT ) {
				return $ptr;
			}
		}

		return false;
	}

	/**
	 * @param File $phpcsFile
	 * @param int $functionPtr Token position of the function declaration
	 * @param string $pattern Regex to match against the name of the function
	 * @return int
	 */
	private function isFunctionOkay( File $phpcsFile, int $functionPtr, string $pattern ) {
		return preg_match(
			$pattern, $phpcsFile->getDeclarationName( $functionPtr )
		);
	}

}
