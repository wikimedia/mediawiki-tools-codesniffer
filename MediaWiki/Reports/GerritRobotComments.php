<?php

/**
 * Custom report format for Gerrit robot comments.
 *
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

namespace MediaWiki\Reports;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Reports\Report;

// ignore phan complaining about unused parameters that must be declared to inheritance
// @phan-file-suppress PhanUnusedPublicMethodParameter

/**
 * Custom report format for Gerrit robot comments. Outputs a JSON array of Gerrit
 * RobotCommentInput entities.
 * Relies on the following environment variables:
 * - PHPCS_ROOT_DIR: the repo root compared to which Gerrit expects file paths
 * - GERRIT_ROBOT_ID, GERRIT_ROBOT_RUN_ID, GERRIT_URL: Gerrit robot comment parameters.
 *   Typically these would be the robot username, Jenkins build number and Jenkins
 *   console output URL, respectively.
 *
 * @see https://gerrit.wikimedia.org/r/Documentation/config-robot-comments.html
 * @see https://gerrit.wikimedia.org/r/Documentation/rest-api-changes.html#set-review
 * @see https://gerrit.wikimedia.org/r/Documentation/rest-api-changes.html#robot-comment-info
 */
class GerritRobotComments implements Report {

	private const TYPE_ERROR = 'ERROR';
	// private const TYPE_WARNING = 'WARNING';

	/** @var array[] */
	private $robotComments = [];

	/**
	 * Generate a partial report for a single processed file.
	 *
	 * Function should return TRUE if it printed or stored data about the file
	 * and FALSE if it ignored the file. Returning TRUE indicates that the file and
	 * its data should be counted in the grand totals.
	 *
	 * @param array $report Prepared report data.
	 * @param File $phpcsFile The file being reported on.
	 * @param bool $showSources Show sources?
	 * @param int $width Maximum allowed line width.
	 *
	 * @return bool
	 */
	public function generateFileReport( $report, File $phpcsFile, $showSources = false, $width = 80 ) {
		if ( $report['errors'] === 0 && $report['warnings'] === 0 ) {
			// Nothing to print.
			return false;
		}

		foreach ( $report['messages'] as $line => $lineIssues ) {
			foreach ( $lineIssues as $columnIssues ) {
				foreach ( $columnIssues as $issue ) {
					$this->processIssue( $phpcsFile, $line, $issue['message'], $issue['source'], $issue['type'] );
				}
			}
		}

		return true;
	}

	/**
	 * Generate the actual report.
	 *
	 * @param string $cachedData Any partial report data that was returned from
	 *   generateFileReport during the run.
	 * @param int $totalFiles Total number of files processed during the run.
	 * @param int $totalErrors Total number of errors found during the run.
	 * @param int $totalWarnings Total number of warnings found during the run.
	 * @param int $totalFixable Total number of problems that can be fixed.
	 * @param bool $showSources Show sources?
	 * @param int $width Maximum allowed line width.
	 * @param bool $interactive Are we running in interactive mode?
	 * @param bool $toScreen Is the report being printed to screen?
	 * @return void
	 */
	public function generate(
		$cachedData,
		$totalFiles,
		$totalErrors,
		$totalWarnings,
		$totalFixable,
		$showSources = false,
		$width = 80,
		$interactive = false,
		$toScreen = true
	) {
		if ( !$this->robotComments ) {
			return;
		}

		echo json_encode( $this->robotComments, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) . PHP_EOL;
	}

	/**
	 * @param File $phpcsFile
	 * @param int $line
	 * @param string $message Human-readable error message
	 * @param string $source Sniff name
	 * @param string $type One of the TYPE_* constants
	 * @return void
	 */
	protected function processIssue(
		File $phpcsFile,
		int $line,
		string $message,
		string $source,
		string $type
	) {
		$path = $phpcsFile->path;
		$pathPrefix = getenv( 'PHPCS_ROOT_DIR' ) ?: '';
		$pathPrefix = rtrim( $pathPrefix, '/' ) . '/';
		if ( $pathPrefix && strpos( $path, $pathPrefix ) === 0 ) {
			$path = substr( $path, strlen( $pathPrefix ) );
		}
		$finalMessage = "$message\n($source)";
		$this->robotComments[$path][] = $this->makeRobotComment( $line, $finalMessage, $type );
	}

	/**
	 * Create a RobotCommentInfo, a data structure used by the Gerrit API to create a robot
	 * comment describing a CI error, optionally with an attached fix.
	 * @param int $line
	 * @param string $message
	 * @param string $type
	 * @return array A JSON-compatible data structure.
	 * @see https://gerrit.wikimedia.org/r/Documentation/rest-api-changes.html#robot-comment-info
	 */
	protected function makeRobotComment(
		int $line,
		string $message,
		string $type
	) {
		return [
			'robot_id' => getenv( 'GERRIT_ROBOT_ID' ) ?: 'mediawiki-codesniffer',
			'robot_run_id' => getenv( 'GERRIT_ROBOT_RUN_ID' ) ?: 'unspecified',
			'url' => getenv( 'GERRIT_URL' )
				?: 'https://gerrit.wikimedia.org/r/plugins/gitiles/mediawiki/tools/codesniffer/',
			'line' => $line,
			'message' => $message,
			'unresolved' => $type === self::TYPE_ERROR,
		];
	}

}
