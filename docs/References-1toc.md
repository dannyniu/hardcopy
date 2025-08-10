Hardcopy - Document Structuring Component
=========================================

This document describes Hardcopy's functions for document structuring.

Hardcopy is able to produce multi-page and single-page HTML documents with
table of contents, table and figure indicies, and cross-reference hyperlinks.

As demonstrated in the example project and in `Tutorial.md`, the structure
of the document is defined by the "toc.php" file, which serves as the
entry-point of the project. The build process takes this file and produces
output in 2 phases.

In the 1st phase:

1. "toc.php" tells the build system, the title and optionally the cover page
   of the document, and which chapter content files to include;

2. the chapter files tells the build system what chapters and sections,
   as well as what tables and figures and hyperlink references
   (hence, *anchors*) there are.

3. The build system records the sequence of occurrences of *anchors*, and
   suppresses the output of chapter content files.

In the 2nd phase:

1. The build system takes special code paths to produce cover page,
   table of contents and indicies, and auxiliary files.

2. The build system returns the recorded occurances of *anchors* to the
   chapter files, enable their output, and redirect their output to build
   destinations. The *anchors* are decorated with section numbers, table and
   figure numbers, and anchor IDs.

## Variable: `$Title`

The main title of the document.

## Functions `hc_H[1-4](string $s): string`

Records the section title in the 1st phase;
returns the section with section markup in the 2nd phase.

## Functions: `hc_{Table,Figure}(string $s): string`

Records table and figure name in the 1st phase;
returns anchor element in 2nd phase.

## Function `hc_StartAnnexes(): void`

Indicates that top level section numbering should switch to
alphabetical ones, and that top level section headings should be
prefixed with "Annex ".

## Function: `hcNamedSection(string $s, $type=1): string`

Returns an hyperlink anchor to a known section.

## Function: `hcPageBegin(): bool` (Deprecated)

Suppresses or enables output based on the current build phase.

The use of this function is no longer necessary.

## Function: `hcAddPages(string $p): string`

Records a page in the build system. Filename extension shall be omitted.

## Function: `hcFinish(): void`

Marks the end of the document.
Does little in the 1st phase;
produces output in the 2nd phase.

## Function: `hcNamedAnchor(string $s, string $id): string`

Produces an anchor with ID specified in `$id` and
text content specified in `$s`.

## Function: `hcNamedHref(string $id): string`

Produces a hyperlink to a named anchor produced by `hcNamedAnchor`.
If the named anchor is on a different page than the link,
the relative URL of the page is automatically added.

## Function: `cite(string $id): string`

Surround `hcNamedHref` with `<sup>` tags.
