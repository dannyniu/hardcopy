Introduction
============

Hardcopy is a PHP-based document template system that's designed to
output to HTML with a plain visual style. It can output both 
single-page and multi-age forms that're suitable for both print and
web-browsing.

Hardcopy provides a set of functions aiding creation of documents 
with section headings, hyper-linking, and some commonly-used 
basic mathematic notations.

The single-page output of Hardcopy may be fed to HTML-to-PDF
converter softwares such as WeasyPrint or PrinceXML (which are 
intellectual properties of their respective owner).

Bundled with the default distribution of Hardcopy, is the 
FreeFont fonts from GNU (available at <https://gnu.org/s/freefont">).
GNU FreeFont had been used for typesetting some mathematical expressions,
with TeX Gyre fonts being the now the new default option. You may replace
it with other fonts if you find conflicts in licensing conditions.
For licensing information see LICENSE.md.

Extracting and Installing
=========================

After extracting, the following directorys should be present:

- `hardcopy`
- `hardcopy/doc`
- `hardcopy/example-project`
- `hardcopy/src`
- `hardcopy/src-include`
- `hardcopy/src-include/gnu-freefont`
- `hardcopy/tex-gyre`
- `hardcopy/tex-gyre/math`

To install, copy the directory `hardcopy` to `/usr/local/share`.
**Installation is optional**.

Creating a Project
==================

For an example of a complete project,
see `hardcopy/example-project`.

To prepare a project, do the following steps:

1. Create a project directory.

If Hardcopy codes are not installed in `/usr/share` or `/usr/local/share`,
then create the project directory next to the `src-include` directory.

2. Create the <samp>toc.php</samp> file as the table of contents,
   and as the main file to be invoked during the build process.

3. **[Optional]** If the project requires custom stylesheets, fonts, and/or
   images, create the <samp>assets</samp> directory.

After preparation, we can add contents to the project. **[Note]** In the
following steps, all filenames specified to the template system must 
have their filename extensions (e.g. <samp>.php</samp>, <samp>.html</samp>) 
removed.

1. Add the PHP start tag `<?php` to the first line of `toc.php`

2. `require_once(getenv("HARDCOPY_SRCINC_MAIN"));` to the 2nd line.

3. Set the main title of the entire document by assigning to the
   `$Title` variable.

4. **[Optional]** Specify a cover page (possibly containing book title, 
   abstract, and/or preface, etc.) by assigning to the <code>$Cover</code> 
   variable, the name of the hypertext file.

5. Add chapters (or other types of document fragments) by calling
   `hcAddPages` with the filenames of the chapters.

6. Complete the document by calling `hcFinish`.

Chapter files are typically created like this:

1. Add the PHP start tag `<?php` to the first line of the chapter file
   to begin the chapter declaration block.

2. Add `require_once(getenv("HARDCOPY_SRCINC_MAIN"));` to the 2nd line.

3. Declare headings and table and figure indicies by calling
   `hc_H[1-4]`, `hc_Table`, `hc_Figure` and assigning their 
   return values to variables.

4. End the chapter declaration block with the following statement followed
   by a PHP closing tag: `if( !hcPageBegin() ) return;`

5. Add chapter content to the rest of the file. Note that:

   - To add section heading, echo the variable storing the heading title with
     the `<?=` and `?>` tags, without the surrounding `<h[1-4]>` tags.

   - To add table and figure caption, echo the variable storing the table and
     figure title with the `<?=` and `?>` tags usually surrounded with the
     `<caption>` and the `<figcaption>` tags.

   - There should not be boilerplate elements such as
     `DOCTYPE`, `html`, `head`, `body` or their openning and closing tags.

Building the Project
====================

A project can be built in single-page or multi-page variants. Other variants
may be added in the future.

To build a project in single-page variant, change to the project directory, 
then execute `hc-cmd.sh` from the `src` directory:

```
hc-cmd.sh -s
```

Likewise for multi-page variant:

```
hc-env.sh -m
```

The options `-s` and `-m` are build specifiers. An additional `-v` flag
is supported for turning on verbose diagnostics. For browser preview on
local network, you may run `hc-cmd.sh` without build specifiers.

After building the single-page variant of the project, at least the 
following directory entries should appear:

- `build/singlepage/main.html`
- `build/singlepage/src-include/`

As for the multi-page variant, at least the following should appear:

- `build/multipage/frame.html`
- `build/multipage/toc.html`
- `build/multipage/src-include/`
