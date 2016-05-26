<?php

use org\bovigo\vfs\vfsStream;

class MediaWikiTestHelper extends TestHelper {
	public function __construct() {
		parent::__construct();
		$this->vfsRoot = vfsStream::setup( 'root' );
	}

	public function runPhpCbf( $file, $standard = '' ) {
		if ( empty( $standard ) ) {
			$standard = $this->rootDir . '/ruleset.xml';
		}
		$defaults = $this->phpcs->getDefaults();

		if (
			defined( 'PHP_CodeSniffer::VERSION' ) &&
			version_compare( PHP_CodeSniffer::VERSION, '1.5.0' ) != -1
		) {
			$standard = [ $standard ];
		}
		$options = [
			'encoding' => 'utf-8',
			'files' => [ $file ],
			'standard' => $standard,
			'reports' => [ 'diff' => vfsStream::url( 'root/phpcbf-fixed.diff' ) ]
		] + $defaults;

		ob_start();
		$this->phpcs->process( $options );
		ob_end_clean();

		if ( !$this->vfsRoot->hasChild( 'phpcbf-fixed.diff' ) ) {
			// no diff generated, return source file
			return file_get_contents( $file );
		}

		$diff = $this->vfsRoot->getChild( 'phpcbf-fixed.diff' )->getContent();
		if ( empty( trim( $diff ) ) ) {
			return file_get_contents( $file );
		}

		// patch the source file and output to stdout
		$cmd = "patch -p0 -u -o -";
		$descriptorSpec = [
			0 => [ 'pipe', 'r' ],
			1 => [ 'pipe', 'w' ],
			2 => [ 'file', '/dev/null', 'w' ],
		];
		$process = proc_open( $cmd, $descriptorSpec, $pipes );
		if ( !$process ) {
			throw new RuntimeException( "Failed to run $cmd" );
		}

		fwrite( $pipes[0], $diff );
		fclose( $pipes[0] );

		$output = stream_get_contents( $pipes[1] );
		fclose( $pipes[1] );

		$retval = proc_close( $process );

		// test retval?
		return $output;
	}
}
