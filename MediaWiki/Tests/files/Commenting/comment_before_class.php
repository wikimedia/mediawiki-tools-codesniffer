<?php

// phpcs:disable Generic.Files.OneObjectStructurePerFile

/** This is a correct class comment */
class ClassCommentTest1 {
}

/** @phan-file-suppress This is a file-level coment */

class ClassCommentFileLevel1 {
}

/**
 * Also a file-level comment
 * @file
 */

class ClassCommentFileLevel2 {
}

/** @phan-file-suppress This is a file-level coment without a blank line to follow */
class ClassCommentFileLevel3 {
}

/**
 * If this is a stray comment, it should not use `**` or `//`. Else there should be no empty line after.
 */

class ClassCommentStrayComment1 {
}

/* If this is meant as a stray comment, there should be a line after. If it's meant as a class comment,
  it should use `**`. */
class ClassCommentStrayComment2 {
}

// Like above: line after if stray comment, else use `**`.
class ClassCommentStrayComment3 {
}

/* This is a valid stray comment */

class ClassCommentStrayComment4 {
}

// This is also a valid stray comment

class ClassCommentStrayComment5 {
}

/**
 * Attribute after class comment
 */
#[Attribute]
class ClassCommentAttribute1 {
}

/*
 * Attribute after stray comment
 */
#[Attribute]
class ClassCommentAttribute2 {
}

/**
 * @file
 * Attribute after file-level comment
 */
#[Attribute]
class ClassCommentAttribute3 {
}
