<?php

clone ( $obj );
clone( $obj );
clone($obj);
clone $obj;
require( 'jfrpg' );
require ( 'krpg' );
require('lfrpg');
require 'rkjgjpr';
require_once( 'jfrpg' );
require_once ( 'krpg' );
require_once('lfrpg');
require_once 'rkjgjpr';
include( 'jfrpg' );
include ( 'krpg' );
include('lfrpg');
include 'rkjgjpr';
include_once( 'jfrpg' );
include_once ( 'krpg' );
include_once('lfrpg');
include_once 'rkjgjpr';
require_once ( getenv( 'MW_INSTALL_PATH' ) !== false
	? getenv( 'MW_INSTALL_PATH' ) . '/maintenance/Maintenance.php'
	: __DIR__ . '/../../../maintenance/Maintenance.php' );
require_once( getenv( 'MW_INSTALL_PATH' ) !== false
	? getenv( 'MW_INSTALL_PATH' ) . '/maintenance/Maintenance.php'
	: __DIR__ . '/../../../maintenance/Maintenance.php' );
require( "$wmfConfigDir/CommonSettings-labs.php" );

function wfLoopTest() {
	while ( true ) {
		while ( true ) {
			if ( 1 == 1 ) {
				continue(2);
			}
			break(2);
		}
	}
}
