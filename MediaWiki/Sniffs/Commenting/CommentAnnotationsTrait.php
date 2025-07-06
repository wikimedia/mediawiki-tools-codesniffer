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

use InvalidArgumentException;

/**
 * Trait with code to deal with annotations in doc comments.
 */
trait CommentAnnotationsTrait {
	/** TODO: Make these constants once we require PHP 8.2+ */
	/**
	 * Annotations allowed for functions. This includes bad annotations that we check for
	 * elsewhere.
	 * @phan-read-only
	 */
	private static array $allowedEverywhere = [
		// Allowed all-lowercase tags
		'@anchor' => true,
		'@code' => true,
		'@deprecated' => true,
		'@endcode' => true,
		'@fixme' => true,
		'@internal' => true,
		'@license' => true,
		'@link' => true,
		'@note' => true,
		'@see' => true,
		'@since' => true,
		'@todo' => true,
		'@uses' => true,
		'@warning' => true,

		// Stable interface policy tags
		// @see https://www.mediawiki.org/wiki/Stable_interface_policy
		'@newable' => true,
		'@stable' => true,
		'@unstable' => true,

		// phan
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

		// T263390
		'@noinspection' => true,

		'@inheritdoc' => '@inheritDoc',

		// Tags to automatically fix
		'@deprecate' => '@deprecated',
		'@warn' => '@warning',
		'@licence' => '@license',
	];

	/**
	 * Annotations allowed for functions. This includes bad annotations that we check for
	 * elsewhere.
	 * @phan-read-only
	 */
	private static array $allowedInFunctions = [
		// Allowed all-lowercase tags
		'@after' => true,
		'@author' => true,
		'@before' => true,
		'@cover' => true,
		'@covers' => true,
		'@depends' => true,
		'@group' => true,
		'@par' => true,
		'@param' => true,
		'@requires' => true,
		'@return' => true,
		'@throws' => true,

		// Automatically replaced
		'@param[in]' => '@param',
		'@param[in,out]' => '@param',
		'@param[out]' => '@param',
		'@params' => '@param',
		'@returns' => '@return',
		'@throw' => '@throws',
		'@exception' => '@throws',

		// private and protected is needed when functions stay public
		// for deprecation or backward compatibility reasons
		// @see https://www.mediawiki.org/wiki/Deprecation_policy#Scope
		'@private' => true,
		'@protected' => true,

		// Special handling
		'@access' => true,

		// phan
		'@phan-param' => true,
		'@phan-return' => true,
		'@phan-assert' => true,
		'@phan-assert-true-condition' => true,
		'@phan-assert-false-condition' => true,
		'@phan-side-effect-free' => true,

		// pseudo-tags from phan-taint-check-plugin
		'@param-taint' => true,
		'@return-taint' => true,

		// psalm
		'@psalm-param' => true,
		'@psalm-return' => true,

		// phpunit tags that are mixed-case - map lowercase to preferred mixed-case
		// phpunit tags that are already all-lowercase, like @after and @before
		// are listed above
		'@afterclass' => '@afterClass',
		'@beforeclass' => '@beforeClass',
		'@precondition' => '@preCondition',
		'@postcondition' => '@postCondition',
		'@codecoverageignore' => '@codeCoverageIgnore',
		'@covernothing' => '@coverNothing',
		'@coversnothing' => '@coversNothing',
		'@dataprovider' => '@dataProvider',
		'@doesnotperformassertions' => '@doesNotPerformAssertions',
		'@testwith' => '@testWith',

		// Other phpunit annotations that we recognize, even if PhpunitAnnotationsSniff
		// complains about them. See T276971
		'@small' => true,
		'@medium' => true,
		'@large' => true,
		'@test' => true,
		'@testdox' => true,
		'@backupglobals' => '@backupGlobals',
		'@backupstaticattributes' => '@backupStaticAttributes',
		'@excludeglobalvariablefrombackup' => '@excludeGlobalVariableFromBackup',
		'@excludestaticpropertyfrombackup' => '@excludeStaticPropertyFromBackup',
		'@runinseparateprocess' => '@runInSeparateProcess',
		'@expectedexception' => '@expectedException',
		'@expectedexceptioncode' => '@expectedExceptionCode',
		'@expectedexceptionmessage' => '@expectedExceptionMessage',
		'@expectedexceptionmessageregexp' => '@expectedExceptionMessageRegExp',

		// Tags to automatically fix
		'@gropu' => '@group',
		'@parma' => '@param',
	];

	/**
	 * Annotations allowed for properties. This includes bad annotations that we check for
	 * elsewhere.
	 * @phan-read-only
	 */
	private static array $allowedInProperties = [
		// Allowed all-lowercase tags
		'@final' => true,
		'@showinitializer' => true,
		'@var' => true,

		// private and protected is needed when properties stay public
		// for deprecation or backward compatibility reasons
		// @see https://www.mediawiki.org/wiki/Deprecation_policy#Scope
		'@private' => true,
		'@protected' => true,

		// phan
		'@phan-var' => true,
		'@phan-read-only' => true,
		'@phan-write-only' => true,

		// psalm
		'@psalm-var' => true,

		// From AlphabeticArraySortSniff sniff
		'@phpcs-require-sorted-array' => true,

		// Used by Wikimedia\DebugInfo\DumpUtils
		'@novardump' => '@noVarDump',
	];

	/**
	 * Annotations allowed for classes (and similar structures). This includes bad annotations that we check for
	 * elsewhere.
	 * @phan-read-only
	 */
	private static array $allowedInClasses = [
		// Allowed all-lowercase tags
		'@author' => true,
		'@brief' => true,
		// TODO: Tag is deprecated, we should probably disallow it
		'@category' => true,
		'@copyright' => true,
		'@cover' => true,
		'@covers' => true,
		'@group' => true,
		'@ingroup' => true,
		'@method' => true,
		'@package' => true,
		'@par' => true,
		'@property' => true,
		'@property-read' => true,
		'@property-write' => true,
		'@requires' => true,
		'@section' => true,

		// phan
		'@phan-property' => true,
		'@phan-read-only' => true,

		// phpunit tags that are mixed-case - map lowercase to preferred mixed-case
		// phpunit tags that are already all-lowercase, like @after and @before
		// are listed above
		'@codecoverageignore' => '@codeCoverageIgnore',
		'@covernothing' => '@coverNothing',
		'@coversnothing' => '@coversNothing',
		'@doesnotperformassertions' => '@doesNotPerformAssertions',

		// Other phpunit annotations that we recognize, even if PhpunitAnnotationsSniff
		// complains about them. See T276971
		'@coversdefaultclass' => '@coversDefaultClass',
		'@small' => true,
		'@medium' => true,
		'@large' => true,
		'@testdox' => true,
		'@backupglobals' => '@backupGlobals',
		'@backupstaticattributes' => '@backupStaticAttributes',
		// Already has a fixer in the PHPUnit sniff
		'@coverdefaultclass' => '@coversDefaultClass',
		'@excludeglobalvariablefrombackup' => '@excludeGlobalVariableFromBackup',
		'@excludestaticpropertyfrombackup' => '@excludeStaticPropertyFromBackup',

		// Tags to automatically fix
		'@gropu' => '@group',
	];

	/**
	 * Normalizes an annotation
	 *
	 * @param string $anno
	 * @param int $elementTok
	 * @return string|false Tag or false if it's not canonical
	 */
	private function normalizeAnnotation( string $anno, int $elementTok ): string|false {
		$allowedAnnotations = self::getAllowedAnnotations( $elementTok );
		$anno = rtrim( $anno, ':' );
		$lower = mb_strtolower( $anno );
		if ( array_key_exists( $lower, $allowedAnnotations ) ) {
			return is_string( $allowedAnnotations[$lower] )
				? $allowedAnnotations[$lower]
				: $lower;
		}

		if ( preg_match( '/^@code{\W?([a-z]+)}$/', $lower, $matches ) ) {
			return '@code{.' . $matches[1] . '}';
		}

		return false;
	}

	private static function getAllowedAnnotations( int $elementTok ): array {
		static $allowed;
		if ( !$allowed ) {
			$allowed = [
				T_FUNCTION => array_merge( self::$allowedEverywhere, self::$allowedInFunctions ),
				T_VARIABLE => array_merge( self::$allowedEverywhere, self::$allowedInProperties ),
				T_CLASS => array_merge( self::$allowedEverywhere, self::$allowedInClasses ),
			];
		}
		return $allowed[$elementTok] ?? throw new InvalidArgumentException( "Invalid token $elementTok" );
	}
}
