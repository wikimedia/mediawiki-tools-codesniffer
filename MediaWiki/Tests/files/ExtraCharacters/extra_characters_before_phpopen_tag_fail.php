someText<?php
// text before first php open
/**
 * @return void
 */
function wfFoo() {
	# code...
}
?>
T<?php
// This php open tag will in any case be ignored
