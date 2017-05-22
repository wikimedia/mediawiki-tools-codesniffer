<?php
/**
 * Report warnings when unexpected function or variable used like.
 * Should use $this->msg() rather than wfMessage() on ContextSource extend.
 * Should use $this->getUser() rather than $wgUser() on ContextSource extend.
 * Should use $this->getRequest() rather than $wgRequest on ContextSource extend.
 */

namespace MediaWiki\Sniffs\Usage;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class ExtendClassUsageSniff implements Sniff {

	private $eligableCls = null;

	private $eligableFunc = null;

	public static $msgMap = [
		T_FUNCTION => 'function',
		T_VARIABLE => 'variable'
	];

	public static $checkConfig = [
		// All extended class name.
		'extendsCls' => [
			'ContextSource' => true
		],
		// All details of usage need to be check.
		'checkList' => [
			// Extended class name.
			'ContextSource' => [
				[
					// The check content.
					'content' => 'wfMessage',
					// The content shows on report message.
					'msg_content' => 'wfMessage()',
					// The check content code.
					'code' => T_FUNCTION,
					// The expected content.
					'expect_content' => '$this->msg()',
					// The expected content code.
					'expect_code' => T_FUNCTION
				],
				[
					'content' => '$wgUser',
					'msg_content' => '$wgUser',
					'code' => T_VARIABLE,
					'expect_content' => '$this->getUser()',
					'expect_code' => T_FUNCTION
				],
				[
					'content' => '$wgRequest',
					'msg_content' => '$wgRequest',
					'code' => T_VARIABLE,
					'expect_content' => '$this->getRequest()',
					'expect_code' => T_FUNCTION
				]
			]
		]
	];
	/**
	 * @return array
	 */
	public function register() {
		return [
			T_CLASS,
			T_EXTENDS,
			T_FUNCTION,
			T_VARIABLE,
			T_STRING
		];
	}

	/**
	 * @param File $phpcsFile File object.
	 * @param int $stackPtr The current token index.
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		$currToken = $tokens[$stackPtr];

		if ( $currToken['code'] === T_CLASS ) {
			$extendsPtr = $phpcsFile->findNext( T_EXTENDS, $stackPtr );
			if ( $extendsPtr === false ) {
				// No extends token found
				return;
			}
			$baseClsPtr = $phpcsFile->findNext( T_STRING, $extendsPtr );
			$extClsContent = $tokens[$baseClsPtr]['content'];
			// Here should be replaced with a mechanism that check if
			// the base class is in the list of restricted classes
			if ( !isset( self::$checkConfig['extendsCls'][$extClsContent] ) ) {
				return;
			} else {
				// Retrieve class name.
				$classNamePtr = $phpcsFile->findNext( T_STRING, $stackPtr );
				$this->eligableCls = [
					'name' => $tokens[$classNamePtr]['content'],
					'extendsCls' => $extClsContent,
					'scope_start' => $currToken['scope_opener'],
					'scope_end' => $currToken['scope_closer']
				];
			}
		}

		if ( !empty( $this->eligableCls )
			&& $stackPtr > $this->eligableCls['scope_start']
			&& $stackPtr < $this->eligableCls['scope_end']
		) {
			if ( $currToken['code'] === T_FUNCTION ) {
				// If this is a function, make sure it's eligible
				// (i.e. not static or abstract, and has a body).
				$methodProps = $phpcsFile->getMethodProperties( $stackPtr );
				$isStaticOrAbstract = $methodProps['is_static'] || $methodProps['is_abstract'];
				$hasBody = isset( $currToken['scope_opener'] )
					&& isset( $currToken['scope_closer'] );
				if ( !$isStaticOrAbstract && $hasBody ) {
					$funcNamePtr = $phpcsFile->findNext( T_STRING, $stackPtr );
					$this->eligableFunc = [
						'name' => $tokens[$funcNamePtr]['content'],
						'scope_start' => $currToken['scope_opener'],
						'scope_end' => $currToken['scope_closer']
					];
				}
			}

			if ( !empty( $this->eligableFunc )
				&& $stackPtr > $this->eligableFunc['scope_start']
				&& $stackPtr < $this->eligableFunc['scope_end']
			) {
				// extend class name.
				$extClsContent = $this->eligableCls['extendsCls'];
				$extClsCheckList = self::$checkConfig['checkList'][ $extClsContent ];
				foreach ( $extClsCheckList as $key => $value ) {
					$condition = false;
					if ( $value['code'] === T_FUNCTION
						&& strtolower( $currToken['content'] ) === strtolower( $value['content'] )
					) {
						$condition = true;
					}
					if ( $value['code'] === T_VARIABLE
						&& $currToken['content'] === $value['content']
					) {
						$condition = true;
					}
					if ( $condition ) {
						$warning = 'Should use %s %s rather than %s %s .';
						$expectCodeMsg = self::$msgMap[ $value['expect_code'] ];
						$codeMsg = self::$msgMap[ $value['code'] ];
						$phpcsFile->addWarning(
							$warning,
							$stackPtr,
							'FunctionVarUsage',
							[ $expectCodeMsg, $value['expect_content'], $codeMsg, $value['msg_content'] ]
						);
						return;
					}
				}

				// if reach the end of current function, clear function info
				$scopeEndPtr = $phpcsFile->findNext( T_STRING, $stackPtr );
				if ( $scopeEndPtr > $this->eligableFunc['scope_end'] ) {
					$this->eligableFunc = null;
				}
			}

			// if reach the end of current class, clear class information
			$scopeEndPtr = $phpcsFile->findNext( T_STRING, $stackPtr );
			if ( $scopeEndPtr > $this->eligableCls['scope_end'] ) {
				$this->eligableCls = null;
			}
		}
	}
}
