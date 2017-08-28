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
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006-2014 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
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
	 * @var array
	 */
	private $skipStandardMethods = [
		'__toString', '__destruct',
		'__sleep', '__wakeup',
		'__clone'
	];

	/**
	 * Mapping for swap short types
	 *
	 * @var array
	 */
	private static $shortTypeMapping = [
		'boolean' => 'bool',
		'boolean[]' => 'bool[]',
		'integer' => 'int',
		'integer[]' => 'int[]',
	];

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return [ T_FUNCTION ];
	}
	// end register()

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param File $phpcsFile The file being scanned.
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
	 *
	 *
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		if ( substr( $phpcsFile->getFilename(), -8 ) === 'Test.php' ) {
			// Don't check documentation for test cases
			return;
		}
		$tokens = $phpcsFile->getTokens();
		$funcName = $tokens[$stackPtr + 2];
		if ( in_array( $funcName['content'], $this->skipStandardMethods ) ) {
			// Don't require documentation for an obvious method
			return;
		} elseif ( $funcName['content'] === '__construct'
			&& !$phpcsFile->getMethodParameters( $stackPtr )
		) {
			// Don't require documentation for constructors with no parameters
			return;
		}
		// Identify the visiblity of the function
		$visibility = $phpcsFile->findPrevious( [ T_PUBLIC, T_PROTECTED, T_PRIVATE ], $stackPtr - 1 );
		$visStr = 'Public';
		if ( $visibility ) {
			$visInfo = $tokens[$visibility];
			if ( $visInfo['line'] == $tokens[$stackPtr]['line'] ) {
				if ( $visInfo['code'] == T_PRIVATE ) {
					// Don't check documentation for private functions
					return;
				} elseif ( $visInfo['code'] == T_PROTECTED ) {
					$visStr = 'Protected';
				}
			}
		}

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
			$phpcsFile->addError(
				'Missing function doc comment',
				$stackPtr,
				"MissingDocumentation$visStr"
			);
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
		$inheritDoc = false;
		foreach ( $tokens[$commentStart]['comment_tags'] as $tag ) {
			$tagText = $tokens[$tag]['content'];
			if ( $tagText === '@see' ) {
				// Make sure the tag isn't empty.
				$string = $phpcsFile->findNext( T_DOC_COMMENT_STRING, $tag, $commentEnd );
				if ( $string === false || $tokens[$string]['line'] !== $tokens[$tag]['line'] ) {
					$error = 'Content missing for @see tag in function comment';
					$phpcsFile->addError( $error, $tag, 'EmptySees' );
				}
			} elseif ( $tagText === '@inheritDoc' ) {
				$inheritDoc = true;
			}
		}
		if ( $inheritDoc ) {
			// Don't need to validate anything else
			return;
		}

		$this->processReturn( $phpcsFile, $stackPtr, $commentStart );
		$this->processThrows( $phpcsFile, $stackPtr, $commentStart );
		$this->processParams( $phpcsFile, $stackPtr, $commentStart );
	}
	// end process()

	/**
	 * Process the return comment of this function comment.
	 *
	 * @param File $phpcsFile The file being scanned.
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
	 * @param int $commentStart The position in the stack where the comment started.
	 *
	 * @return void
	 */
	protected function processReturn( File $phpcsFile, $stackPtr, $commentStart ) {
		$tokens = $phpcsFile->getTokens();
		// Return if no scope_opener.
		if ( !isset( $tokens[$stackPtr]['scope_opener'] ) ) {
			return;
		}
		$endFunction = $tokens[$stackPtr]['scope_closer'];
		$found = false;
		for ( $i = $stackPtr + 1; $i < $endFunction; $i++ ) {
			$token = $tokens[$i];
			if ( $token['code'] === T_CLOSURE ) {
				// Skip to the end of the closure and continue
				$i = $tokens[$i]['scope_closer'];
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
			if ( $tokens[$tag]['content'] === '@return' ) {
				if ( $return !== null ) {
					$error = 'Only 1 @return tag is allowed in a function comment';
					$phpcsFile->addError( $error, $tag, 'DuplicateReturn' );
					return;
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
					$data = [
						$expectedSpaces,
						$currentSpaces,
					];
					$fix = $phpcsFile->addFixableError(
						'Expected %s spaces before return type; %s found',
						$retTypeSpacing,
						'SpacingBeforeReturnType',
						$data
					);
					if ( $fix ) {
						$phpcsFile->fixer->replaceToken( $retTypeSpacing, ' ' );
					}
				}
			}
			$retType = $return + 2;
			$content = $tokens[$retType]['content'];
			if ( empty( $content ) === true || $tokens[$retType]['code'] !== T_DOC_COMMENT_STRING ) {
				$error = 'Return type missing for @return tag in function comment';
				$phpcsFile->addError( $error, $return, 'MissingReturnType' );
			}
			// The first word of the return type is the actual type
			$exploded = explode( ' ', $content, 2 );
			$type = $exploded[0];
			$comment = isset( $exploded[1] ) ? $exploded[1] : null;
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
					$data = [
						$expectedSpaces,
						$currentSpaces,
					];
					$fix = $phpcsFile->addFixableError(
						'Expected %s spaces after return type; %s found',
						$retType,
						'SpacingAfterReturnType',
						$data
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
		// end if
	}
	// end processReturn()

	/**
	 * Process any throw tags that this function comment has.
	 *
	 * @param File $phpcsFile The file being scanned.
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
	 * @param int $commentStart The position in the stack where the comment started.
	 *
	 * @return void
	 */
	protected function processThrows( File $phpcsFile, $stackPtr, $commentStart ) {
		$tokens = $phpcsFile->getTokens();
		$throws = [];
		foreach ( $tokens[$commentStart]['comment_tags'] as $tag ) {
			if ( $tokens[$tag]['content'] !== '@throws' ) {
				continue;
			}
			$exception = null;
			$comment = null;
			if ( $tokens[( $tag + 2 )]['code'] === T_DOC_COMMENT_STRING ) {
				$matches = [];
				preg_match( '/([^\s]+)(?:\s+(.*))?/', $tokens[( $tag + 2 )]['content'], $matches );
				$exception = $matches[1];
				if ( isset( $matches[2] ) === true ) {
					$comment = $matches[2];
				}
			}
			if ( $exception === null ) {
				$error = 'Exception type missing for @throws tag in function comment';
				$phpcsFile->addError( $error, $tag, 'InvalidThrows' );
			}
		}
		// end foreach
	}
	// end processThrows()

	/**
	 * Process the function parameter comments.
	 *
	 * @param File $phpcsFile The file being scanned.
	 * @param int $stackPtr The position of the current token in the stack passed in $tokens.
	 * @param int $commentStart The position in the stack where the comment started.
	 *
	 * @return void
	 */
	protected function processParams( File $phpcsFile, $stackPtr, $commentStart ) {
		$tokens = $phpcsFile->getTokens();
		$params = [];
		$maxType = 0;
		$maxVar = 0;
		foreach ( $tokens[$commentStart]['comment_tags'] as $pos => $tag ) {
			if ( $tokens[$tag]['content'] !== '@param' ) {
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
				$matches = [];
				preg_match( '/([^$&.]+)(?:((?:\.\.\.)?(?:\$|&)[^\s]+)(?:(\s+)(.*))?)?/',
					$tokens[( $tag + 2 )]['content'], $matches );
				$typeLen = strlen( $matches[1] );
				$type = trim( $matches[1] );
				$typeSpace = ( $typeLen - strlen( $type ) );
				$typeLen = strlen( $type );
				if ( $typeLen > $maxType ) {
					$maxType = $typeLen;
				}
				if ( isset( $matches[2] ) === true ) {
					$var = $matches[2];
					$varLen = strlen( $var );
					if ( $varLen > $maxVar ) {
						$maxVar = $varLen;
					}
					if ( isset( $matches[4] ) === true ) {
						$varSpace = strlen( $matches[3] );
						$commentFirst = $matches[4];
						$comment = $commentFirst;
						// Any strings until the next tag belong to this comment.
						if ( isset( $tokens[$commentStart]['comment_tags'][( $pos + 1 )] ) === true ) {
							$end = $tokens[$commentStart]['comment_tags'][( $pos + 1 )];
						} else {
							$end = $tokens[$commentStart]['comment_closer'];
						}
						for ( $i = ( $tag + 3 ); $i < $end; $i++ ) {
							if ( $tokens[$i]['code'] === T_DOC_COMMENT_STRING ) {
								$comment .= ' '.$tokens[$i]['content'];
							}
						}
					} else {
						$error = 'Missing parameter comment';
						$phpcsFile->addError( $error, $tag, 'MissingParamComment' );
					}
				} else {
					$error = 'Missing parameter name';
					$phpcsFile->addError( $error, $tag, 'MissingParamName' );
				}
				// end if
			} else {
				$error = 'Missing parameter type';
				$phpcsFile->addError( $error, $tag, 'MissingParamType' );
			}
			// end if
			$params[] = [
				'tag' => $tag,
				'type' => $type,
				'var' => $var,
				'comment' => $comment,
				'comment_first' => $commentFirst,
				'param_space' => $paramSpace,
				'type_space' => $typeSpace,
				'var_space' => $varSpace,
			];
		}
		// end foreach
		$realParams = $phpcsFile->getMethodParameters( $stackPtr );
		$foundParams = [];
		// We want to use ... for all variable length arguments, so added
		// this prefix to the variable name so comparisons are easier.
		foreach ( $realParams as $pos => $param ) {
			if ( $realParams[$pos]['pass_by_reference'] === true ) {
				$realParams[$pos]['name'] = '&'.$realParams[$pos]['name'];
			}
			if ( $param['variable_length'] === true ) {
				$realParams[$pos]['name'] = '...'.$realParams[$pos]['name'];
			}
		}
		foreach ( $params as $pos => $param ) {
			if ( $param['var'] === '' ) {
				continue;
			}
			// Check number of spaces before type (after @param)
			$spaces = 1;
			if ( $param['param_space'] !== $spaces ) {
				$error = 'Expected %s spaces before parameter type; %s found';
				$data = [
					$spaces,
					$param['param_space'],
				];
				$fix = $phpcsFile->addFixableError( $error, $param['tag'], 'SpacingBeforeParamType', $data );
				if ( $fix === true ) {
					$phpcsFile->fixer->replaceToken( ( $param['tag'] + 1 ), str_repeat( ' ', $spaces ) );
				}
			}
			// Check number of spaces after the type.
			// $spaces = ( $maxType - strlen( $param['type'] ) + 1 );
			$spaces = 1;
			if ( $param['type_space'] !== $spaces ) {
				$error = 'Expected %s spaces after parameter type; %s found';
				$data = [
					$spaces,
					$param['type_space'],
				];
				$fix = $phpcsFile->addFixableError( $error, $param['tag'], 'SpacingAfterParamType', $data );
				if ( $fix === true ) {
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
			if ( preg_match( '/^(.*?)((?:(?![\[\]_{}])\p{P})+)(?<!,\.\.\.)$/', $var, $matches ) ) {
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
			if ( isset( $realParams[$pos] ) === true ) {
				$realName = $realParams[$pos]['name'];
				if ( $realName !== $var ) {
					$code = 'ParamNameNoMatch';
					$data = [
						$var,
						$realName,
					];
					$error = 'Doc comment for parameter %s does not match ';
					if ( strtolower( $var ) === strtolower( $realName ) ) {
						$error .= 'case of ';
						$code = 'ParamNameNoCaseMatch';
					}
					$error .= 'actual variable name %s';
					$phpcsFile->addError( $error, $param['tag'], $code, $data );
				}
			} elseif ( substr( $var, -4 ) !== ',...' ) {
				// We must have an extra parameter comment.
				$error = 'Superfluous parameter comment';
				$phpcsFile->addError( $error, $param['tag'], 'ExtraParamComment' );
			}
			$foundParams[] = $var;
			// end if
			// Check the short type of boolean and integer
			$explodedType = explode( '|', $param['type'] );
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
			// $spaces = ( $maxVar - strlen( $param['var'] ) + 1 );
			$spaces = 1;
			if ( $param['var_space'] !== $spaces &&
				ltrim( $param['comment'] ) !== '' ) {
				$error = 'Expected %s spaces after parameter name; %s found';
				$data = [
					$spaces,
					$param['var_space'],
				];
				$fix = $phpcsFile->addFixableError( $error, $param['tag'], 'SpacingAfterParamName', $data );
				if ( $fix === true ) {
					$this->replaceParamComment(
						$phpcsFile,
						$param,
						[ 'var_space' => $spaces ]
					);
				}
			}
		}
		// end foreach
		$realNames = [];
		foreach ( $realParams as $realParam ) {
			$realNames[] = $realParam['name'];
		}
		// Report missing comments.
		$diff = array_diff( $realNames, $foundParams );
		foreach ( $diff as $neededParam ) {
			$error = 'Doc comment for parameter "%s" missing';
			$data = [ $neededParam ];
			$phpcsFile->addError( $error, $commentStart, 'MissingParamTag', $data );
		}
	}
	// end processParams()

	/**
	 * Replace a @param comment
	 *
	 * @param File $phpcsFile The file being scanned.
	 * @param array $param Array of the @param
	 * @param array $fixParam Array with fixes to @param. Only provide keys to replace
	 *
	 * @return void
	 */
	protected function replaceParamComment( File $phpcsFile, array $param, array $fixParam ) {
		// Use the old value for unchanged keys
		$fixParam += $param;

		// Build the new line
		$content  = $fixParam['type'];
		$content .= str_repeat( ' ', $fixParam['type_space'] );
		$content .= $fixParam['var'];
		$content .= str_repeat( ' ', $fixParam['var_space'] );
		$content .= $fixParam['comment_first'];
		$phpcsFile->fixer->replaceToken( ( $fixParam['tag'] + 2 ), $content );
	}
}
// end class
