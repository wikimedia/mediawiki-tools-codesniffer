<?php

// Always use repos own vendor folder
putenv( 'MW_VENDOR_PATH=.' );

$cfg = require __DIR__ . '/../vendor/mediawiki/mediawiki-phan-config/src/config.php';

$cfg['directory_list'] = array_merge(
	$cfg['directory_list'],
	[
		'MediaWiki/',
	]
);

$cfg['exclude_analysis_directory_list'] = array_merge(
	$cfg['exclude_analysis_directory_list'],
	[
		'MediaWiki/Tests/files/',
	]
);

// Upstream param docs are not safe to use with this option
$cfg['null_casts_as_any_type'] = true;

$cfg['dead_code_detection'] = true;
$cfg['dead_code_detection_prefer_false_negative'] = false;
// Sniff classes are loaded dynamic and are unreferenced for static code analyzer
$cfg['suppress_issue_types'][] = 'PhanUnreferencedClass';

// public properties can be set by reflection from ruleset.xml
$cfg['suppress_issue_types'][] = 'PhanReadOnlyPublicProperty';

// FIXME Use constant insteads
$cfg['suppress_issue_types'][] = 'PhanReadOnlyPrivateProperty';
$cfg['suppress_issue_types'][] = 'PhanReadOnlyProtectedProperty';

$cfg['plugins'][] = 'PHPUnitNotDeadCodePlugin';

// Remove the exclusion of the codesniffer files
$cfg['exclude_file_regex'] = preg_replace(
	'@\|(?:mediawiki/mediawiki-codesniffer|squizlabs/php_codesniffer)@',
	'',
	$cfg['exclude_file_regex']
);

return $cfg;
