<?php

// phpcs:disable Generic.Files.OneObjectStructurePerFile.MultipleFound
// phpcs:disable MediaWiki.Commenting.CommentBeforeClass
// phpcs:disable MediaWiki.Commenting.LicenseComment

/**
 * @var string
 * @param string $bar
 * @return int
 * @throws A
 * @private
 * @gropu medium
 * @warn This should use `warning`
 * @licence xyz
 * @since 1.0
 */
class ClassAnnotationsBadExamples {
}

/**
 * This is a mix of file-level and class comment and should be reported
 * @since 2.0
 * @phan-file-suppress Foo
 */
class TheCommentForThisClassAlsoHasFileLevelAnnotations {
}

/**
 * @since 1.0
 * @stable
 * @see Foo
 * @suppress Bar
 * @phan-template T
 * @author John Doe
 */
class ClassAnnotationsGoodExamples {
}

/**
 * This is actually a file-level comment and should NOT be reported
 * @phan-file-suppress Foo
 */

class ThisClassHasNoComment {
}

/**
 * @var string
 * @param string $bar
 * @return int
 * @throws A
 * @private
 */
class BadAnnotationsAreNotReportedWithoutAGoodOne {
}
