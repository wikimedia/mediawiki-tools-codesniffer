<?php

use MediaWiki\Hook\RecentChange_saveHook;

class HookHandler implements
	RecentChange_saveHook,
	\MediaWiki\SpecialPage\Hook\SpecialPage_initListHook
{
	/**
	 * This method name comes from the hook and cannot be changed, phpcs should not complain
	 * (the interface for this hook is imported with a `use` statement, and the `implements`
	 * only has the final interface name
	 *
	 * @param RecentChange $recentChange
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onRecentChange_save( $recentChange ) {
		return false;
	}

	/**
	 * This method name comes from the hook and cannot be changed, phpcs should not complain
	 * (the interface for this hook is not imported with a `use` statement, and the `implements`
	 * has the fully qualified name
	 *
	 * @param array &$list List of core special pages
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialPage_initList( &$list ) {
		return false;
	}

	/**
	 * This method is not inherited from an interface, and can be changed, phpcs should complain,
	 * even though it looks like a hook handler
	 *
	 * @param SpecialUpload $upload
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUploadForm_initial( $upload ) {
		return false;
	}

	/**
	 * This method is not inherited from an interface and does not look like a hook handler,
	 * phpcs should complain
	 *
	 * @return bool
	 */
	public function getThe_thing() {
		return false;
	}

}
