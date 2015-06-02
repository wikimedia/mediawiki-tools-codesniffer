# MediaWiki-Codesniffer release history #

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
