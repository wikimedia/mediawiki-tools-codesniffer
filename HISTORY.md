# MediaWiki-Codesniffer release history #

## 0.12.0 / 2017-08-29 ##
* Add sniff to ensure floats have a leading `0` if necessary (Kunal Mehta)
* Add sniff to ensure the class name matches the filename (Kunal Mehta)
* Change bootstrap-ci.php to match PHP CodeSniffer 3.0.0 (Umherirrender)
* Check for unneeded punctation in @param and @return (Umherirrender)
* Check spacing after type in @return (Umherirrender)
* Check spacing before type in @param and @return (Umherirrender)
* Clean up test helpers (Kunal Mehta)
* Do not mess long function comments on composer fix (Umherirrender)
* Enforce "short" type definitions in multi types in function comments (Umherirrender)
* Make it easier to figure out which test failed (Kunal Mehta)
* phpunit: replace deprecated strict=true (Umherirrender)
* Remove GoatSniffer integration (Kunal Mehta)
* Remove unmatched @codingStandardsIgnoreEnd (Umherirrender)
* Rename OpeningKeywordBracketSniff to OpeningKeywordParenthesisSniff (Reedy)
* Use local OneClassPerFile sniff for only one class/interface/trait (Kunal Mehta)

## 0.11.1 / 2017-08-13 ##
* Add GoatSniffer ASCII art (Kunal Mehta)

## 0.11.0 / 2017-08-10 ##
* Added OpeningKeywordBraceSniff (Umherirrender)
* Add sniff to forbid PHP 7 scalar type hints (Kunal Mehta)
* Enable Squiz.WhiteSpace.OperatorSpacing (Umherirrender)
* Enforce "short" type definitions on @param in comments (Umherirrender)
* Fix phpunit test on windows (Umherirrender)
* Fix Undefined offset in FunctionCommentSniff (Umherirrender)

## 0.10.1 / 2017-07-22 ##
* Add .gitattributes (Umherirrender)
* Add Squiz.Classes.SelfMemberReference to ruleset (Kunal Mehta)
* build: Added php-console-highlighter (Umherirrender)
* Don't ignore files or paths with "git" in them, only .git (Kunal Mehta)
* Fix exclude of common folders (Umherirrender)
* Fix "Undefined index: scope_opener" in SpaceBeforeClassBraceSniff (Reedy)
* Forbid backtick operator (Matthew Flaschen)
* Ignore returns in closures for MissingReturn sniff (Kunal Mehta)
* PHP CodeSniffer on CI should only lint HEAD (Antoine Musso)
* Reduce false positives in ReferenceThisSniff (Kunal Mehta)
* Sniff that the short type form is used in @return tags (Kunal Mehta)
* Swap isset() === false to !isset() (Reedy)
* track=1 rather than defaultbranch (Reedy)
* Update PHP_CodeSniffer to 3.0.2 (Kunal Mehta)

## 0.10.0 / 2017-07-01 ##
* Add sniff to prevent against using PHP 7's Unicode escape syntax (Kunal Mehta)
* Add sniff to verify type-casts use the short form (bool, int) (Kunal Mehta)
* Add sniff for `&$this` that causes warnings in PHP 7.1 (Kunal Mehta)
* Clean up DbrQueryUsageSniff (Umherirrender)
* Ensure all FunctionComment sniff codes are standard (Kunal Mehta)
* Exclude common folders (Umherirrender)
* Fix handling of nested parenthesis in ParenthesesAroundKeywordSniff (Kunal Mehta)
* IllegalSingleLineCommentSniff: Check return value of strrpos strictly (Kunal Mehta)
* Improve handling of multi-line class declarations (Kunal Mehta)
* Include sniff warning/error codes in test output (Kunal Mehta)
* Make DisallowEmptyLineFunctionsSniff apply to closures too (Kunal Mehta)
* Use correct notation for UTF-8 (Umherirrender)

## 0.9.0 / 2017-06-19 ##
* Add sniff to enforce "function (" for closures (Kunal Mehta)
* Add usage of && in generic_pass (addshore)
* Disallow `and` and `or` (Kunal Mehta)
* Don't require documentation for constructors without parameters (Kunal Mehta)
* Don't require documentation for '__toString' (Kunal Mehta)
* Don't require return/throws/param for doc blocks with @inheritDoc (Kunal Mehta)
* Expand list of standard methods that don't need documentation (Kunal Mehta)
* Fix FunctionComment.Missing sniff code (Kunal Mehta)
* Fix indentation (Umherirrender)
* Fix WhiteSpace/SpaceAfterClosureSniff (Antoine Musso)
* Make sure all files end with a newline (Kunal Mehta)
* test: ensure consistent report width (Antoine Musso)
* Update for CodeSniffer 3.0 (Kunal Mehta)
* Update squizlabs/PHP_CodeSniffer to 3.0.1 (Reedy)
* Use upstream CharacterBeforePHPOpeningTag sniff (Kunal Mehta)

## 0.8.0 / 2017-05-03 ##
* Add sniff for cast operator spacing (Sam Wilson)
* Allow filtering documentation requirements based on visibility (Kunal Mehta)
* Don't require documentation for test cases (Kunal Mehta)
* Don't require @return annotations for plain "return;" (Kunal Mehta)
* Explicitly check for method structure before using (Sam Wilson)
* Fix test result parsing, and correct new errors that were exposed (Sam Wilson)
* Prevent abstract functions being marked eligible (Sam Wilson)
* PHP_CodeSniffer to 2.9.0 (Paladox)

## 0.8.0-alpha.1 / 2016-09-21 ##
* Add detection for calling global functions in target classes. (Tao Xie)
* Add function commenting sniff. (Lethexie)
* Add .idea directory to .gitignore (Florian Schmidt)
* Add sniff to confirm function name using lower camel case. (Lethexie)
* Add test to verify SpaceBeforeClassBraceSniff handles extends (Kunal Mehta)
* Add the SpaceBeforeClassBraceSniff (Lethe)
* Add the SpaceBeforeControlStructureBraceSniff (Lethexie)
* Add usage to forbid superglobals like $_GET,$_POST (Lethe)
* Comments should start with new line. (Lethe)
* Disallow parenthesis around keywords like clone or require (Florian)
* Enable PSR2.Methods.FunctionClosingBrace sniff (Kunal Mehta)
* Fix reference parameters warning and no return function need return tag (Lethe)
* Fix single space expected on single line comment. (Lethexie)
* Make sure no empty line at the begin of the function. (Lethexie)
* Put failed examples and passed examples into a file. (Lethexie)
* Report warnings when $dbr->query() is used instead of $dbr->select(). (Tao Xie)
* Single Line comments no multiple '*'. (Lethe)
* Update squizlabs/php_codesniffer to 2.7.0 (Paladox)

## 0.7.2 / 2016-05-27 ##
* SpaceyParenthesisSniff: Don't remove last argument or array element (Kevin Israel)
* Expect specific output from sniffs (Erik Bernhardson)
* Assert fixers do as intended (Erik Bernhardson)

## 0.7.1 / 2016-05-06 ##
* Fix typo in IfElseStructureSniff (addshore)

## 0.7.0 / 2016-05-06 ##
* Also check for space after elseif in SpaceAfterControlStructureSniff (Lethexie)
* Factor our tokenIsNamespaced method (addshore)
* Make IfElseStructureSniff can detect and fix multiple white spaces after else (Lethexie)
* Make SpaceyParenthesisSniff can fix multiple white spaces between parentheses (Lethexie)
* Make spacey parenthesis sniff work with short array syntax (Kunal Mehta)
* Speed up PrefixedGlobalFunctionsSniff (addshore)
* Update squizlabs/php_codesniffer to 2.6.0 (Paladox)

## 0.6.0 / 2016-02-17 ##
* Add Generic.Arrays.DisallowLongArraySyntax to ruleset, autofix this repo (Kunal Mehta)
* Add sniff to detect consecutive empty lines in a file (Vivek Ghaisas)
* Disable Generic.Functions.CallTimePassByReference.NotAllowed (Kunal Mehta)
* Update squizlabs/php_codesniffer to 2.5.1 (Paladox)

## 0.5.1 / 2015-12-28 ##
* Avoid in_array for performance reasons (Thiemo Mättig)
* build: Pass -s to phpcs for easier debugging (Kunal Mehta)
* Remove dead code from SpaceBeforeSingleLineCommentSniff (Thiemo Mättig)
* Revert "CharacterBeforePHPOpeningTagSniff: Support T_HASHBANG for HHVM >=3.5,<3.7" (Legoktm)
* Simplify existing regular expressions (Thiemo Mättig)
* build: Update phpunit to 4.8.18 (Paladox)
* Update squizlabs/php_codesniffer to 2.5.0 (Paladox)

## 0.5.0 / 2015-10-23 ##
* Add Generic.ControlStructures.InlineControlStructure to ruleset (Kunal Mehta)
* Add IfElseStructureSniff to handle else structures (TasneemLo)
* Handle multiple # comments in Space Before Comment (TasneemLo)
* Sniff to check assignment in while & if (TasneemLo)
* Sniff to warn when using dirname(__FILE__) (TasneemLo)

## 0.4.0 / 2015-09-26 ##
* Use upstream codesniffer 2.3.4 (Kunal Mehta & Paladox)
* Sniff to check for space in single line comments (Smriti.Singh)
* Automatically fix warnings caught by SpaceyParenthesisSniff (Kunal Mehta)
* Automatically fix warnings caught by SpaceAfterControlStructureSniff (Kunal Mehta)
* Add ignore list to PrefixedGlobalFunctionsSniff (Vivek Ghaisas)
* Add ignore list to ValidGlobalNameSniff (Vivek Ghaisas)
* Update jakub-onderka/php-parallel-lint to 0.9.* (Paladox)
* Automatically fix warnings caught by SpaceBeforeSingleLineCommentSniff (Kunal Mehta)

## 0.3.0 / 2015-06-19 ##
* Update README.md code formatting (Vivek Ghaisas)
* Don't require "wf" prefix on functions that are namespaced (Kunal Mehta)
* Simplify PHPUnit boostrap, require usage of composer for running tests (Kunal Mehta)
* SpaceyParenthesis: Check for space before opening parenthesis (Vivek Ghaisas)
* SpaceyParenthesesSniff: Search for extra/unnecessary space (Vivek Ghaisas)
* CharacterBeforePHPOpeningTagSniff: Support T_HASHBANG for HHVM >=3.5,<3.7 (Kunal Mehta)

## 0.2.0 / 2015-06-02 ##
* Fixed sniff that checks globals have a "wg" prefix (Divya)
* New sniff to detect unused global variables (Divya)
* New sniff to detect text before first opening php tag (Sumit Asthana)
* New sniff to detect alternative syntax such as "endif" (Vivek Ghaisas)
* New sniff to detect unprefixed global functions (Vivek Ghaisas)
* New sniff to detect "goto" usage (Harshit Harchani)
* Update ignore with some emacs files. (Mark A. Hershberger)
* Use upstream codesniffer 2.3.0 (Kunal Mehta)
* Make mediawiki/tools/codesniffer pass phpcs (Vivek Ghaisas)
* New sniff to check for spacey use of parentheses (Kunal Mehta)
* Modify generic pass test with a case of not-spacey parentheses (Vivek Ghaisas)
* Make failing tests fail only for specific respective reasons (Vivek Ghaisas)
* Change certain errors to warnings (Vivek Ghaisas)
* Update ExtraCharacters Sniff to allow shebang (Harshit Harchani)

## 0.1.0 / 2015-01-05 ##

* Initial tagged release
