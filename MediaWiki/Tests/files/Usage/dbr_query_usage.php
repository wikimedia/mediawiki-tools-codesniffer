<?php

class TestClass {

	/**
	 * This example should fail.
	 * @return void
	 */
	public function testDbrQuery() {
		$sql = "SHOW GLOBAL VARIABLES LIKE 'ft\\_min\\_word\\_len'";
		$dbr = wfGetDB( DB_SLAVE );
		$result = $dbr->query( $sql, __METHOD__ );
		$row = $result->fetchObject();
		$result->free();
	}

	/**
	 * This example should pass.
	 * @return void
	 */
	public function testDbrSelect() {
		$dbr = wfGetDB( DB_SLAVE );

		$oldTitle = Title::makeTitle( NS_USER, $olduser->getName() );

		$result = $dbr->select( 'logging', '*',
			[ 'log_type' => 'renameuser',
				'log_action' => 'renameuser',
				'log_namespace' => NS_USER,
				'log_title' => $oldTitle->getDBkey(),
				'log_params' => $newuser->getName()
			],
			__METHOD__
		);
	}
}
