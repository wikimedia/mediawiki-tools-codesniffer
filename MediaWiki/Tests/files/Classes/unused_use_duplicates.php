<?php
// phpcs:disable MediaWiki.Classes.UnsortedUseStatements.UnsortedUse
namespace FooBar;

// Used names/aliases
use \DuplicateNameUsed;
use \ConflictingNameAliasUsed;
use \A as ConflictingAliasUsed;
use \B as ConflictingAliasNameUsed;
use \C as DuplicateAliasUsed;

// Used names/aliases - duplicates/conflicts
use \DuplicateNameUsed;
use \X as ConflictingNameAliasUsed;
use \Y as ConflictingAliasUsed;
use \ConflictingAliasNameUsed;
use \C as DuplicateAliasUsed;

// Unused names/aliases
use \DuplicateNameUnused;
use \ConflictingNameAliasUnused;
use \A as ConflictingAliasUnused;
use \B as ConflictingAliasNameUnused;
use \C as DuplicateAliasUnused;

// Unused names/aliases - duplicates/conflicts
use \DuplicateNameUnused;
use \X as ConflictingNameAliasUnused;
use \Y as ConflictingAliasUnused;
use \ConflictingAliasNameUnused;
use \C as DuplicateAliasUnused;


var_dump(
	new DuplicateNameUsed,
	new ConflictingNameAliasUsed,
	new ConflictingAliasUsed,
	new ConflictingAliasNameUsed,
	new DuplicateAliasUsed,
);
