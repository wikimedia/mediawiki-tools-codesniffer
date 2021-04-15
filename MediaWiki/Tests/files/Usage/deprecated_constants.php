<?php

$dbr = wfGetDB( DB_REPLICA );
$dbr = wfGetDB( DB_SLAVE );
$dbw = wfGetDB( DB_PRIMARY );
$dbw = wfGetDB( DB_MASTER );

// Not changed
$str = "DB_SLAVE";
$str = "DB_MASTER";

$title->inNamespace( NS_IMAGE );
$title->inNamespace( NS_IMAGE_TALK );
