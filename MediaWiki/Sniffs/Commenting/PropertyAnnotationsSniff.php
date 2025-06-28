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

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

class PropertyAnnotationsSniff implements Sniff {
	/**
	 * Annotations allowed for properties. This includes bad annotations that we check for
	 * elsewhere.
	 */
	private const ALLOWED_ANNOTATIONS = [
		// Allowed all-lowercase tags
		'@code' => true,
		'@deprecated' => true,
		'@endcode' => true,
		'@final' => true,
		'@fixme' => true,
		'@internal' => true,
		'@license' => true,
		'@link' => true,
		'@note' => true,
		'@see' => true,
		'@since' => true,
		'@showinitializer' => true,
		'@todo' => true,
		'@uses' => true,
		'@var' => true,
		'@warning' => true,

		// private and protected is needed when properties stay public
		// for deprecation or backward compatibility reasons
		// @see https://www.mediawiki.org/wiki/Deprecation_policy#Scope
		'@private' => true,
		'@protected' => true,

		// Stable interface policy tags
		// @see https://www.mediawiki.org/wiki/Stable_interface_policy
		'@newable' => true,
		'@stable' => true,
		'@unstable' => true,

		// phan
		'@phan-var' => true,
		'@phan-suppress-next-line' => true,
		'@phan-suppress-next-next-line' => true,
		'@phan-suppress-previous-line' => true,
		'@suppress' => true,
		'@phan-template' => true,
		'@phan-type' => true,
		// No other consumers for now.
		'@template' => '@phan-template',

		// psalm
		'@psalm-template' => true,
		'@psalm-var' => true,

		// T263390
		'@noinspection' => true,

		'@inheritdoc' => '@inheritDoc',

		// From AlphabeticArraySortSniff sniff
		'@phpcs-require-sorted-array' => true,

		// Used by Wikimedia\DebugInfo\DumpUtils
		'@novardump' => '@noVarDump',

		// Tags to automatically fix
		'@deprecate' => '@deprecated',
		'@warn' => '@warning',
	];

	/**
	 * @inheritDoc
	 */
	public function register(): array {
		return [ T_VARIABLE ];
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
		$tokens = $phpcsFile->getTokens();

		// Only for class properties
		$scope = array_key_last( $tokens[$stackPtr]['conditions'] );
		if ( isset( $tokens[$stackPtr]['nested_parenthesis'] )
			|| $scope === null
			|| ( $tokens[$scope]['code'] !== T_CLASS &&
				$tokens[$scope]['code'] !== T_TRAIT &&
				$tokens[$scope]['code'] !== T_ANON_CLASS )
		) {
			return;
		}

		$find = Tokens::$emptyTokens;
		$find[] = T_STATIC;
		$find[] = T_NULLABLE;
		$find[] = T_STRING;
		$find[] = T_READONLY;
		$visibilityPtr = $phpcsFile->findPrevious( $find, $stackPtr - 1, null, true );
		if ( !$visibilityPtr || ( $tokens[$visibilityPtr]['code'] !== T_VAR &&
			!isset( Tokens::$scopeModifiers[ $tokens[$visibilityPtr]['code'] ] ) )
		) {
			return;
		}

		$commentEnd = $phpcsFile->findPrevious( [ T_WHITESPACE, T_FINAL ], $visibilityPtr - 1, null, true );
		if ( !$commentEnd || $tokens[$commentEnd]['code'] !== T_DOC_COMMENT_CLOSE_TAG ) {
			return;
		}

		$commentStart = $tokens[$commentEnd]['comment_opener'];

		foreach ( $tokens[$commentStart]['comment_tags'] as $tag ) {
			$tagContent = $tokens[$tag]['content'];
			$annotation = $this->normalizeAnnotation( $tagContent );
			if ( $annotation === false ) {
				$error = '%s is not a valid property annotation';
				$phpcsFile->addError( $error, $tag, 'UnrecognizedAnnotation', [ $tagContent ] );
			} elseif ( $tagContent !== $annotation ) {
				$fix = $phpcsFile->addFixableWarning(
					'Use %s annotation instead of %s',
					$tag,
					'NonNormalizedAnnotation',
					[ $annotation, $tagContent ]
				);
				if ( $fix ) {
					$phpcsFile->fixer->replaceToken( $tag, $annotation );
				}
			}
		}
	}

	/**
	 * Normalizes an annotation
	 *
	 * @param string $anno
	 * @return string|false Tag or false if it's not canonical
	 */
	private function normalizeAnnotation( string $anno ) {
		$anno = rtrim( $anno, ':' );
		$lower = mb_strtolower( $anno );
		if ( array_key_exists( $lower, self::ALLOWED_ANNOTATIONS ) ) {
			return is_string( self::ALLOWED_ANNOTATIONS[$lower] )
				? self::ALLOWED_ANNOTATIONS[$lower]
				: $lower;
		}

		if ( preg_match( '/^@code{\W?([a-z]+)}$/', $lower, $matches ) ) {
			return '@code{.' . $matches[1] . '}';
		}

		return false;
	}
}
