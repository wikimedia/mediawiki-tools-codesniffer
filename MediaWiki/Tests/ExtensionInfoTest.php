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

namespace MediaWiki\Sniffs\Tests;

use MediaWiki\Sniffs\Utils\ExtensionInfo;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MediaWiki\Sniffs\Utils\ExtensionInfo
 */
class ExtensionInfoTest extends TestCase {

	public function testSupportsMediaWikiNorequiredVersion() {
		// @phan-suppress-next-line PhanAccessMethodInternal
		$extensionInfo = new ExtensionInfo( __DIR__ . '/files/Utils/norequiredVersion' );
		$this->assertFalse( $extensionInfo->supportsMediaWiki( '1.33' ) );
		$this->assertFalse( $extensionInfo->supportsMediaWiki( '1.34' ) );
		$this->assertFalse( $extensionInfo->supportsMediaWiki( '1.35' ) );
	}

	public function testSupportsMediaWikiRequiredVersion() {
		// @phan-suppress-next-line PhanAccessMethodInternal
		$extensionInfo = new ExtensionInfo( __DIR__ . '/files/Utils/requiredVersion' );
		$this->assertFalse( $extensionInfo->supportsMediaWiki( '1.33' ) );
		$this->assertTrue( $extensionInfo->supportsMediaWiki( '1.34' ) );
		$this->assertTrue( $extensionInfo->supportsMediaWiki( '1.35' ) );
		// Trigger cache
		$this->assertFalse( $extensionInfo->supportsMediaWiki( '1.33' ) );
	}

	public function testJsonFileNotFound() {
		// @phan-suppress-next-line PhanAccessMethodInternal
		$extensionInfo = new ExtensionInfo( __DIR__ . '/files/Utils/notfound' );
		$this->assertFalse( $extensionInfo->supportsMediaWiki( '1.33' ) );
		$this->assertFalse( $extensionInfo->supportsMediaWiki( '1.34' ) );
		$this->assertFalse( $extensionInfo->supportsMediaWiki( '1.35' ) );
	}
}
