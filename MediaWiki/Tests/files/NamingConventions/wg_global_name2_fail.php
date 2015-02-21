<?php

function fooFoo () {
	// The first global is fine, the second isn't
	global $wgContLang, $LocalInterwikis;
}
