<?php

$dbr = wfGetDB( DB_REPLICA );
$dbr = wfGetDB( DB_SLAVE );

// Not changed
$str = "DB_SLAVE";

$title->inNamespace( NS_IMAGE );
$title->inNamespace( NS_IMAGE_TALK );
