# MediaWiki-Codesniffer release history #

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
