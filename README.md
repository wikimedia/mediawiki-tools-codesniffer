# MediaWiki coding conventions #

## Abstract ##
This project implements a set of rules for use with [PHP CodeSniffer](https://pear.php.net/package/PHP_CodeSniffer).

See [MediaWiki conventions](https://www.mediawiki.org/wiki/Manual:Coding_conventions/PHP) on our wiki. :-)


## How to install ##
1. Create a composer.json which adds this project as a dependency:

<pre>
{
	"require-dev": {
		"mediawiki/mediawiki-codesniffer": "0.6.0"
	},
	"scripts": {
		"test": [
			"phpcs -p -s"
		],
		"fix": "phpcbf"
	}
}
</pre>

2. Create a phpcs.xml with our configuration:

<pre>
<?xml version="1.0"?>
<ruleset>
	<rule ref="vendor/mediawiki/mediawiki-codesniffer/MediaWiki"/>
	<file>.</file>
	<arg name="extensions" value="php,php5,inc"/>
	<arg name="encoding" value="utf8"/>
	<exclude-pattern>vendor</exclude-pattern>
</ruleset>
</pre>

3. Install: `composer update`

4. Run: `composer test`

5. Run: `composer fix` to auto-fix some of the errors, others might need manual intervention.

6. Commit!

Note that for most MediaWiki projects, we'd also recommend adding a PHP linter to your `composer.json` – see the [full documentation](https://www.mediawiki.org/wiki/Continuous_integration/Entry_points#PHP) for more details.


## TODO ##
* Migrate the old code-utils/check-vars.php

