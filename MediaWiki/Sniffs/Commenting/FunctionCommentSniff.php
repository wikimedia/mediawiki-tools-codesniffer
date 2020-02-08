<?php
/**
 * This file was copied from PHP_CodeSniffer before being modified
 * File: Standards/PEAR/Sniffs/Commenting/FunctionCommentSniff.php
 * From repository: https://github.com/squizlabs/PHP_CodeSniffer
 *
 * Parses and verifies the doc comments for functions.
 *
 * PHP version 5
 *
 * @category PHP
 * @package PHP_CodeSniffer
 * @author Greg Sherwood <gsherwood@squiz.net>
 * @author Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006-2014 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD-3-Clause
 * @link http://pear.php.net/package/PHP_CodeSniffer
 */

namespace MediaWiki\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

class FunctionCommentSniff implements Sniff {

	/**
	 * Standard class methods that
	 * don't require documentation
	 *
	 * @var string[]
	 */
	private $skipStandardMethods = [
		'__toString', '__destruct',
		'__sleep', '__wakeup',
		'__clone'
	];

	/**
	 * Mapping for swap short types
	 *
	 * @var string[]
	 */
	private static $shortTypeMapping = [
		'boolean' => 'bool',
		'boolean[]' => 'bool[]',
		'integer' => 'int',
		'integer[]' => 'int[]',
	];

	/**
	 * @inheritDoc
	 */
	public function register() {
		return [ T_FUNCTION ];
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param File $phpcsFile The file being scanned.
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		if ( substr( $phpcsFile->getFilename(), -8 ) === 'Test.php' ) {
			// Don't check documentation for test cases
			return;
		}

		$funcName = $phpcsFile->getDeclarationName( $stackPtr );
		if ( $funcName === null || in_array( $funcName, $this->skipStandardMethods ) ) {
			// Don't require documentation for an obvious method
			return;
		}

		$this->checkVariadicArgComments( $phpcsFile, $stackPtr );

		$tokens = $phpcsFile->getTokens();
		$find = Tokens::$methodPrefixes;
		$find[] = T_WHITESPACE;
		$commentEnd = $phpcsFile->findPrevious( $find, ( $stackPtr - 1 ), null, true );
		if ( $tokens[$commentEnd]['code'] === T_COMMENT ) {
			// Inline comments might just be closing comments for
			// control structures or functions instead of function comments
			// using the wrong comment type. If there is other code on the line,
			// assume they relate to that code.
			$prev = $phpcsFile->findPrevious( $find, ( $commentEnd - 1 ), null, true );
			if ( $prev !== false && $tokens[$prev]['line'] === $tokens[$commentEnd]['line'] ) {
				$commentEnd = $prev;
			}
		}
		if ( $tokens[$commentEnd]['code'] !== T_DOC_COMMENT_CLOSE_TAG
			&& $tokens[$commentEnd]['code'] !== T_COMMENT
		) {
			// Don't require documentation for functions with no parameters, except getters
			if ( substr( $funcName, 0, 3 ) === 'get' || $phpcsFile->getMethodParameters( $stackPtr ) ) {
				$methodProps = $phpcsFile->getMethodProperties( $stackPtr );
				$phpcsFile->addError(
					'Missing function doc comment',
					$stackPtr,
					// Messages used: MissingDocumentationPublic, MissingDocumentationProtected,
					// MissingDocumentationPrivate
					'MissingDocumentation' . ucfirst( $methodProps['scope'] )
				);
			}
			$phpcsFile->recordMetric( $stackPtr, 'Function has doc comment', 'no' );
			return;
		} else {
			$phpcsFile->recordMetric( $stackPtr, 'Function has doc comment', 'yes' );
		}
		if ( $tokens[$commentEnd]['code'] === T_COMMENT ) {
			$phpcsFile->addError( 'You must use "/**" style comments for a function comment',
			$stackPtr, 'WrongStyle' );
			return;
		}
		if ( $tokens[$commentEnd]['line'] !== ( $tokens[$stackPtr]['line'] - 1 ) ) {
			$error = 'There must be no blank lines after the function comment';
			$phpcsFile->addError( $error, $commentEnd, 'SpacingAfter' );
		}
		$commentStart = $tokens[$commentEnd]['comment_opener'];
		$skipDoc = false;
		foreach ( $tokens[$commentStart]['comment_tags'] as $tag ) {
			$tagText = $tokens[$tag]['content'];
			if ( $tagText === '@see' ) {
				// Make sure the tag isn't empty.
				$string = $phpcsFile->findNext( T_DOC_COMMENT_STRING, $tag, $commentEnd );
				if ( $string === false || $tokens[$string]['line'] !== $tokens[$tag]['line'] ) {
					$error = 'Content missing for @see tag in function comment';
					$phpcsFile->addError( $error, $tag, 'EmptySees' );
				}
			} elseif ( strcasecmp( $tagText, '@inheritDoc' ) === 0 ) {
				$skipDoc = true;
			} elseif ( $tagText === '@deprecated' ) {
				// No need to validate deprecated functions
				$skipDoc = true;
			}
		}

		if ( $skipDoc ) {
			// Don't need to validate anything else
			return;
		}

		$this->processReturn( $phpcsFile, $stackPtr, $commentStart );
		$this->processThrows( $phpcsFile, $commentStart );
		$this->processParams( $phpcsFile, $stackPtr, $commentStart );
	}

	/**
	 * Process the return comment of this function comment.
	 *
	 * @param File $phpcsFile The file being scanned.
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
	 * @param int $commentStart The position in the stack where the comment started.
	 */
	protected function processReturn( File $phpcsFile, $stackPtr, $commentStart ) {
		$tokens = $phpcsFile->getTokens();
		// Return if no scope_opener.
		if ( !isset( $tokens[$stackPtr]['scope_opener'] ) ) {
			return;
		}

		// Skip constructors
		if ( $phpcsFile->getDeclarationName( $stackPtr ) === '__construct' ) {
			return;
		}

		$endFunction = $tokens[$stackPtr]['scope_closer'];
		$found = false;
		for ( $i = $endFunction - 1; $i > $stackPtr; $i-- ) {
			$token = $tokens[$i];
			if ( isset( $token['scope_condition'] ) && (
				$tokens[$token['scope_condition']]['code'] === T_CLOSURE ||
				$tokens[$token['scope_condition']]['code'] === T_FUNCTION
			) ) {
				// Skip to the other side of the closure/inner function and continue
				$i = $token['scope_condition'];
				continue;
			}
			if ( $token['code'] === T_RETURN ) {
				if ( isset( $tokens[$i + 1] ) && $tokens[$i + 1]['code'] === T_SEMICOLON ) {
					// This is a `return;` so it doesn't need documentation
					continue;
				}
				$found = true;
				break;
			}
		}

		if ( !$found ) {
			return;
		}

		$return = null;
		foreach ( $tokens[$commentStart]['comment_tags'] as $tag ) {
			$tagContent = $tokens[$tag]['content'];
			if ( $tagContent === '@return' || $tagContent === '@returns' ) {
				if ( $return !== null ) {
					$error = 'Only 1 @return tag is allowed in a function comment';
					$phpcsFile->addError( $error, $tag, 'DuplicateReturn' );
					return;
				}
				if ( $tagContent === '@returns' ) {
					$error = 'Use @return tag in function comment instead of @returns';
					$fix = $phpcsFile->addFixableError( $error, $tag, 'PluralReturns' );
					if ( $fix ) {
						$phpcsFile->fixer->replaceToken( $tag, '@return' );
					}
				}
				$return = $tag;
			}
		}
		if ( $return !== null ) {
			$retTypeSpacing = $return + 1;
			if ( $tokens[$retTypeSpacing]['code'] === T_DOC_COMMENT_WHITESPACE ) {
				$expectedSpaces = 1;
				$currentSpaces = strlen( $tokens[$retTypeSpacing]['content'] );
				if ( $currentSpaces !== $expectedSpaces ) {
					$fix = $phpcsFile->addFixableWarning(
						'Expected %s spaces before return type; %s found',
						$retTypeSpacing,
						'SpacingBeforeReturnType',
						[ $expectedSpaces, $currentSpaces ]
					);
					if ( $fix ) {
						$phpcsFile->fixer->replaceToken( $retTypeSpacing, ' ' );
					}
				}
			}
			$retType = $return + 2;
			$content = $tokens[$retType]['content'];
			if ( empty( $content ) || $tokens[$retType]['code'] !== T_DOC_COMMENT_STRING ) {
				$error = 'Return type missing for @return tag in function comment';
				$phpcsFile->addError( $error, $return, 'MissingReturnType' );
			}
			// The first word of the return type is the actual type
			$exploded = explode( ' ', $content, 2 );
			$type = $exploded[0];
			$comment = $exploded[1] ?? null;
			$fixType = false;
			// Check for unneeded punctation
			$matches = [];
			if ( preg_match( '/^(.*)((?:(?![\[\]_{}])\p{P})+)$/', $type, $matches ) ) {
				$fix = $phpcsFile->addFixableError(
					'Return type should not end with punctuation "%s"',
					$retType,
					'NotPunctuationReturn',
					[ $matches[2] ]
				);
				$type = $matches[1];
				if ( $fix ) {
					$fixType = true;
				}
			}
			$matches = [];
			if ( preg_match( '/^([{\[]+)(.*)([\]}]+)$/', $type, $matches ) ) {
				$fix = $phpcsFile->addFixableError(
					'Expected parameter type not wrapped in parenthesis; %s and %s found',
					$retType,
					'NotParenthesisReturnType',
					[ $matches[1], $matches[3] ]
				);
				$type = $matches[2];
				if ( $fix ) {
					$fixType = true;
				}
			}
			// Check the type for short types
			$explodedType = explode( '|', $type );
			foreach ( $explodedType as $index => $singleType ) {
				if ( isset( self::$shortTypeMapping[$singleType] ) ) {
					$newType = self::$shortTypeMapping[$singleType];
					// grep: NotShortIntReturn, NotShortIntArrayReturn,
					// NotShortBoolReturn, NotShortBoolArrayReturn
					$code = 'NotShort' . ucfirst( str_replace( '[]', 'Array', $newType ) ) . 'Return';
					$fix = $phpcsFile->addFixableError(
						'Short type of "%s" should be used for @return tag',
						$retType,
						$code,
						[ $newType ]
					);
					if ( $fix ) {
						$explodedType[$index] = $newType;
						$fixType = true;
					}
				}
			}
			// Check spacing after type
			if ( $comment !== null ) {
				$expectedSpaces = 1;
				$currentSpaces = strspn( $comment, ' ' ) + 1;
				if ( $currentSpaces !== $expectedSpaces ) {
					$fix = $phpcsFile->addFixableWarning(
						'Expected %s spaces after return type; %s found',
						$retType,
						'SpacingAfterReturnType',
						[ $expectedSpaces, $currentSpaces ]
					);
					if ( $fix ) {
						$fixType = true;
						$comment = substr( $comment, $currentSpaces - 1 );
					}
				}
			}
			if ( $fixType ) {
				$phpcsFile->fixer->replaceToken(
					$retType,
					implode( '|', $explodedType ) . ( $comment !== null ? ' ' . $comment : '' )
				);
			}
		} else {
			$error = 'Missing @return tag in function comment';
			$phpcsFile->addError( $error, $tokens[$commentStart]['comment_closer'], 'MissingReturn' );
		}
	}

	/**
	 * Process any throw tags that this function comment has.
	 *
	 * @param File $phpcsFile The file being scanned.
	 * @param int $commentStart The position in the stack where the comment started.
	 */
	protected function processThrows( File $phpcsFile, $commentStart ) {
		$tokens = $phpcsFile->getTokens();
		foreach ( $tokens[$commentStart]['comment_tags'] as $tag ) {
			$tagContent = $tokens[$tag]['content'];
			if ( $tagContent !== '@throws' && $tagContent !== '@throw' ) {
				continue;
			}
			if ( $tagContent === '@throw' ) {
				$error = 'Use @throws tag in function comment instead of @throw';
				$fix = $phpcsFile->addFixableError( $error, $tag, 'SingularThrow' );
				if ( $fix ) {
					$phpcsFile->fixer->replaceToken( $tag, '@throws' );
				}
			}
			$exception = null;
			$comment = null;
			if ( $tokens[( $tag + 2 )]['code'] === T_DOC_COMMENT_STRING ) {
				$matches = [];
				preg_match( '/([^\s]+)(?:\s+(.*))?/', $tokens[( $tag + 2 )]['content'], $matches );
				$exception = $matches[1];
				if ( isset( $matches[2] ) ) {
					$comment = $matches[2];
				}
			}
			if ( $exception === null ) {
				$error = 'Exception type missing for @throws tag in function comment';
				$phpcsFile->addError( $error, $tag, 'InvalidThrows' );
			} else {
				// Check for unneeded parenthesis on exceptions
				$matches = [];
				if ( preg_match( '/^([{\[]+)(.*)([\]}]+)$/', $exception, $matches ) ) {
					$fix = $phpcsFile->addFixableError(
						'Expected parameter type not wrapped in parenthesis; %s and %s found',
						$tag,
						'NotParenthesisException',
						[ $matches[1], $matches[3] ]
					);
					if ( $fix ) {
						$phpcsFile->fixer->replaceToken(
							$tag + 2,
							$matches[2] . ( $comment === null ? '' : ' ' . $comment )
						);
					}
				}
			}
		}
	}

	/**
	 * Process the function parameter comments.
	 *
	 * @param File $phpcsFile The file being scanned.
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
	 * @param int $commentStart The position in the stack where the comment started.
	 */
	protected function processParams( File $phpcsFile, $stackPtr, $commentStart ) {
		$tokens = $phpcsFile->getTokens();
		$params = [];
		foreach ( $tokens[$commentStart]['comment_tags'] as $pos => $tag ) {
			$tagContent = $tokens[$tag]['content'];

			if ( $tagContent === '@params' ) {
				$error = 'Use @param tag in function comment instead of @params';
				$fix = $phpcsFile->addFixableError( $error, $tag, 'PluralParams' );
				if ( $fix ) {
					$phpcsFile->fixer->replaceToken( $tag, '@param' );
				}
			} elseif ( $tagContent === '@param[in]' || $tagContent === '@param[out]' ||
				$tagContent === '@param[in,out]'
			) {
				$error = 'Use @param tag in function comment instead of %s';
				$fix = $phpcsFile->addFixableError( $error, $tag, 'DirectionParam', [ $tagContent ] );
				if ( $fix ) {
					$phpcsFile->fixer->replaceToken( $tag, '@param' );
				}
			} elseif ( $tagContent !== '@param' ) {
				continue;
			}

			$paramSpace = 0;
			$type = '';
			$typeSpace = 0;
			$var = '';
			$varSpace = 0;
			$comment = '';
			$commentFirst = '';
			if ( $tokens[( $tag + 1 )]['code'] === T_DOC_COMMENT_WHITESPACE ) {
				$paramSpace = strlen( $tokens[( $tag + 1 )]['content'] );
			}
			if ( $tokens[( $tag + 2 )]['code'] === T_DOC_COMMENT_STRING ) {
				preg_match( '/^([^&$.]+)(?:((?:\.\.\.)?[&$]\S+)(?:(\s+)(.*))?)?/',
					$tokens[( $tag + 2 )]['content'], $matches );
				$untrimmedType = $matches[1] ?? '';
				$type = rtrim( $untrimmedType );
				$typeSpace = strlen( $untrimmedType ) - strlen( $type );
				if ( isset( $matches[2] ) ) {
					$var = $matches[2];
					if ( isset( $matches[4] ) ) {
						$varSpace = strlen( $matches[3] );
						$commentFirst = $matches[4];
						$comment = $commentFirst;
						// Any strings until the next tag belong to this comment.
						if ( isset( $tokens[$commentStart]['comment_tags'][( $pos + 1 )] ) ) {
							$end = $tokens[$commentStart]['comment_tags'][( $pos + 1 )];
						} else {
							$end = $tokens[$commentStart]['comment_closer'];
						}
						for ( $i = ( $tag + 3 ); $i < $end; $i++ ) {
							if ( $tokens[$i]['code'] === T_DOC_COMMENT_STRING ) {
								$comment .= ' ' . $tokens[$i]['content'];
							}
						}
					}
				} else {
					$phpcsFile->addError( 'Missing parameter name', $tag, 'MissingParamName' );
				}
			} else {
				$phpcsFile->addError( 'Missing parameter type', $tag, 'MissingParamType' );
			}

			$isPassByReference = substr( $var, 0, 1 ) === '&';
			// Remove the pass by reference to allow compare with varargs
			if ( $isPassByReference ) {
				$var = substr( $var, 1 );
			}

			$isLegacyVariadicArg = substr( $var, -4 ) === ',...';
			$isVariadicArg = substr( $var, 0, 4 ) === '...$';
			// Remove the variadic indicator from the doc name to compare it against the real
			// name, so that we can allow both formats.
			if ( $isLegacyVariadicArg ) {
				$var = substr( $var, 0, -4 );
			} elseif ( $isVariadicArg ) {
				$var = substr( $var, 3 );
			}

			$params[] = [
				'tag' => $tag,
				'type' => $type,
				'var' => $var,
				'variadic_arg' => $isVariadicArg,
				'legacy_variadic_arg' => $isLegacyVariadicArg,
				'pass_by_reference' => $isPassByReference,
				'comment' => $comment,
				'comment_first' => $commentFirst,
				'param_space' => $paramSpace,
				'type_space' => $typeSpace,
				'var_space' => $varSpace,
			];
		}
		$realParams = $phpcsFile->getMethodParameters( $stackPtr );
		$foundParams = [];
		// We want to use ... for all variable length arguments, so added
		// this prefix to the variable name so comparisons are easier.
		foreach ( $realParams as $pos => $param ) {
			if ( $param['variable_length'] === true ) {
				$realParams[$pos]['name'] = '...' . $realParams[$pos]['name'];
			}
		}
		foreach ( $params as $pos => $param ) {
			if ( $param['var'] === '' ) {
				continue;
			}
			// Check number of spaces before type (after @param)
			$spaces = 1;
			if ( $param['param_space'] !== $spaces ) {
				$fix = $phpcsFile->addFixableWarning(
					'Expected %s spaces before parameter type; %s found',
					$param['tag'],
					'SpacingBeforeParamType',
					[ $spaces, $param['param_space'] ]
				);
				if ( $fix ) {
					$phpcsFile->fixer->replaceToken( ( $param['tag'] + 1 ), str_repeat( ' ', $spaces ) );
				}
			}
			// Check for unneeded punctation on parameter type
			$matches = [];
			if ( preg_match( '/^([{\[]+)(.*)([\]}]+)$/', $param['type'], $matches ) ) {
				$fix = $phpcsFile->addFixableError(
					'Expected parameter type not wrapped in parenthesis; %s and %s found',
					$param['tag'],
					'NotParenthesisParamType',
					[ $matches[1], $matches[3] ]
				);
				if ( $fix ) {
					$this->replaceParamComment(
						$phpcsFile,
						$param,
						[ 'type' => $matches[2] ]
					);
				}
			}
			// Check number of spaces after the type.
			$spaces = 1;
			if ( $param['type_space'] !== $spaces ) {
				$fix = $phpcsFile->addFixableWarning(
					'Expected %s spaces after parameter type; %s found',
					$param['tag'], 'SpacingAfterParamType',
					[ $spaces, $param['type_space'] ]
				);
				if ( $fix ) {
					$this->replaceParamComment(
						$phpcsFile,
						$param,
						[ 'type_space' => $spaces ]
					);
				}
			}
			$var = $param['var'];
			// Check for unneeded punctation
			$matches = [];
			if ( preg_match( '/^(.*?)((?:(?![\[\]_{}])\p{P})+)$/', $var, $matches ) ) {
				$fix = $phpcsFile->addFixableError(
					'Param name should not end with punctuation "%s"',
					$param['tag'],
					'NotPunctuationParam',
					[ $matches[2] ]
				);
				$var = $matches[1];
				if ( $fix ) {
					$this->replaceParamComment(
						$phpcsFile,
						$param,
						[ 'var' => $var ]
					);
				}
			}
			// Make sure the param name is correct.
			$defaultNull = false;
			if ( isset( $realParams[$pos] ) ) {
				$realName = $realParams[$pos]['name'];
				// If difference is pass by reference, add or remove & from documentation
				if ( $param['pass_by_reference'] !== $realParams[$pos]['pass_by_reference'] ) {
					$fix = $phpcsFile->addFixableError(
						'Pass-by-reference for parameter %s does not match ' .
							'pass-by-reference of variable name %s',
						$param['tag'],
						'ParamPassByReference',
						[ $var, $realName ]
					);
					if ( $fix ) {
						$this->replaceParamComment(
							$phpcsFile,
							$param,
							[ 'pass_by_reference' => $realParams[$pos]['pass_by_reference'] ]
						);
					}
					$param['pass_by_reference'] = $realParams[$pos]['pass_by_reference'];
				}
				if ( $realName !== $var ) {
					if (
						substr( $realName, 0, 4 ) === '...$' &&
						( $param['legacy_variadic_arg'] || $param['variadic_arg'] )
					) {
						// Mark all variants as found
						$foundParams[] = "...$var";
						$foundParams[] = "$var,...";
					} else {
						$code = 'ParamNameNoMatch';
						$error = 'Doc comment for parameter %s does not match ';
						if ( strcasecmp( $var, $realName ) === 0 ) {
							$error .= 'case of ';
							$code = 'ParamNameNoCaseMatch';
						}
						$error .= 'actual variable name %s';
						$phpcsFile->addError( $error, $param['tag'], $code, [ $var, $realName ] );
					}
				}
				if ( isset( $realParams[$pos]['default'] ) ) {
					$defaultNull = $realParams[$pos]['default'] === 'null';
				}
			} elseif ( $param['variadic_arg'] || $param['legacy_variadic_arg'] ) {
				$error = 'Variadic parameter documented but not present in the signature';
				$phpcsFile->addError( $error, $param['tag'], 'VariadicDocNotSignature' );
			} else {
				$error = 'Superfluous parameter comment';
				$phpcsFile->addError( $error, $param['tag'], 'ExtraParamComment' );
			}
			$foundParams[] = $var;
			// Check the short type of boolean and integer
			$explodedType = explode( '|', $param['type'] );
			$nullableDoc = substr( $param['type'], 0, 1 ) === '?';
			$nullFound = false;
			$fixType = false;
			foreach ( $explodedType as $index => $singleType ) {
				if ( isset( self::$shortTypeMapping[$singleType] ) ) {
					$newType = self::$shortTypeMapping[$singleType];
					// grep: NotShortIntParam, NotShortIntArrayParam,
					// NotShortBoolParam, NotShortBoolArrayParam
					$code = 'NotShort' . ucfirst( str_replace( '[]', 'Array', $newType ) ) . 'Param';
					$fix = $phpcsFile->addFixableError(
						'Short type of "%s" should be used for @param tag',
						$param['tag'],
						$code,
						[ $newType ]
					);
					if ( $fix ) {
						$explodedType[$index] = $newType;
						$fixType = true;
					}
				}
				// Either an explicit null, or mixed, which null is a
				// part of (T218324)
				if ( $singleType === 'null' || $singleType === 'mixed' ) {
					$nullFound = true;
				}
				if ( substr( $singleType, -10 ) === '[optional]' ) {
					$fix = $phpcsFile->addFixableError(
						'Key word "[optional]" on "%s" should not be used',
						$param['tag'],
						'NoOptionalKeyWord',
						[ $param['type'] ]
					);
					if ( $fix ) {
						$explodedType[$index] = substr( $singleType, 0, -10 );
						$fixType = true;
					}
				}
			}
			if ( $nullableDoc && $defaultNull ) {
				// Don't offer autofix, as changing a signature is somewhat delicate
				$phpcsFile->addError(
					'Use nullable type("%s") for parameters documented as nullable',
					$realParams[$pos]['token'],
					'PHP71NullableDocOptionalArg',
					[ $type ]
				);
			} elseif ( $defaultNull && !( $nullFound || $nullableDoc ) ) {
				// Check if the default of null is in the type list
				$fix = $phpcsFile->addFixableError(
					'Default of null should be declared in @param tag',
					$param['tag'],
					'DefaultNullTypeParam'
				);
				if ( $fix ) {
					$explodedType[] = 'null';
					$fixType = true;
				}
			}

			if ( $fixType ) {
				$this->replaceParamComment(
					$phpcsFile,
					$param,
					[ 'type' => implode( '|', $explodedType ) ]
				);
			}
			if ( $param['comment'] === '' ) {
				continue;
			}
			// Check number of spaces after the var name.
			$spaces = 1;
			if ( $param['var_space'] !== $spaces &&
				ltrim( $param['comment'] ) !== ''
			) {
				$fix = $phpcsFile->addFixableWarning(
					'Expected %s spaces after parameter name; %s found',
					$param['tag'],
					'SpacingAfterParamName',
					[ $spaces, $param['var_space'] ]
				);
				if ( $fix ) {
					$this->replaceParamComment(
						$phpcsFile,
						$param,
						[ 'var_space' => $spaces ]
					);
				}
			}
			// Warn if the parameter is documented as variadic, but the signature doesn't have
			// the splat operator
			if (
				( $param['variadic_arg'] || $param['legacy_variadic_arg'] ) &&
				isset( $realParams[$pos] ) &&
				$realParams[$pos]['variable_length'] === false
			) {
				$legacyName = $param['legacy_variadic_arg'] ? "$var,..." : "...$var";
				$phpcsFile->addError(
					'Splat operator missing for documented variadic parameter "%s"',
					$realParams[$pos]['token'],
					'MissingSplatVariadicArg',
					[ $legacyName ]
				);
			}
		}
		$realNames = [];
		foreach ( $realParams as $realParam ) {
			$realNames[] = $realParam['name'];
		}
		// Report missing comments.
		$missing = array_diff( $realNames, $foundParams );
		foreach ( $missing as $neededParam ) {
			$error = 'Doc comment for parameter "%s" missing';
			$phpcsFile->addError( $error, $commentStart, 'MissingParamTag', [ $neededParam ] );
		}
	}

	/**
	 * Replace a @param comment
	 *
	 * @param File $phpcsFile The file being scanned.
	 * @param array $param Array of the @param
	 * @param array $fixParam Array with fixes to @param. Only provide keys to replace
	 */
	protected function replaceParamComment( File $phpcsFile, array $param, array $fixParam ) {
		// Use the old value for unchanged keys
		$fixParam += $param;

		// Build the new line
		$content = $fixParam['type'];
		$content .= str_repeat( ' ', $fixParam['type_space'] );
		if ( $fixParam['pass_by_reference'] ) {
			$content .= '&';
		}
		if ( $fixParam['variadic_arg'] ) {
			$content .= '...';
		}
		$content .= $fixParam['var'];
		if ( $fixParam['legacy_variadic_arg'] ) {
			$content .= ',...';
		}
		$content .= str_repeat( ' ', $fixParam['var_space'] );
		$content .= $fixParam['comment_first'];
		$phpcsFile->fixer->replaceToken( ( $fixParam['tag'] + 2 ), $content );
	}

	/**
	 * Warn if any comment containing hints of a variadic argument is found within the arguments list.
	 * This includes comment only containing "...", or containing variable names preceded by "...",
	 * or ",...".
	 * Actual variadic arguments should be used instead.
	 *
	 * @param File $phpcsFile The file being scanned.
	 * @param int $functionStart The position in the stack where the function declaration starts
	 */
	protected function checkVariadicArgComments( File $phpcsFile, $functionStart ) {
		$tokens = $phpcsFile->getTokens();
		$argsStart = $tokens[$functionStart]['parenthesis_opener'];
		$argsEnd = $tokens[$functionStart]['parenthesis_closer'];

		$variargReg = '/^[, \t]*\.\.\.[ \t]*$|[ \t]*\.\.\.\$|\$[a-z_][a-z0-9_]*,\.\.\./i';

		$commentPos = $phpcsFile->findNext( T_COMMENT, $argsStart, $argsEnd );
		while ( $commentPos !== false ) {
			$comment = $tokens[$commentPos]['content'];
			if ( substr( $comment, 0, 2 ) === '/*' ) {
				$content = substr( $comment, 2, -2 );
				if ( preg_match( $variargReg, $content ) ) {
					// An autofix would be trivial to write, but we shouldn't offer that. Removing the
					// comment is not enough, because people should also add the actual variadic parameter.
					// For some methods, variadic parameters are only documented via this inline comment,
					// hence an autofixer would effectively remove any documentation about them.
					$phpcsFile->addError(
						'Comments indicating variadic arguments are superfluous and should be replaced ' .
							'with actual variadic arguments',
						$commentPos,
						'SuperfluousVariadicArgComment'
					);
				}
			}
			$commentPos = $phpcsFile->findNext( T_COMMENT, $commentPos + 1, $argsEnd );
		}
	}
}
