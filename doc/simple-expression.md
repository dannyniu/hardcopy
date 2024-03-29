SYNOPSIS
========

```php
mEval(string $s): string
```

Evaluates simple expression and produce HTML markup. 

DESCRIPTION
===========

The function evaluates the simple expression passed in ``$s'', and
produces HTML markup that can be embedded in "span" elements with
"math" class, or as a part of another expression.

The 52 upper and lower case Latin, 48 upper and lower case Greek,
and 1 <GREEK SMALL LETTER FINAL SIGMA> U+03C2 are automatically
surrounded with the HTML "var" tag, unless they're in a text escape.
These characters along with ASCII digits [0-9], are what is being
considered as ''alpha-numeral'' characters. 

The <backslash> character (U+005C) is the "text escape" character.
It has 2 forms.

1. `\abcd1234`: A <backslash> is immediately followed by a sequence of
                alpha-numeral characters, and stops immediately at the
                first that is not one.

2. `\{abc-12}`: A <backslash> is immediately followed by a
                <left brace>, then a sequence of characters that isn't
                a <right brace>, and stops at the first <right brace>
                encountered. The content between the braces are interpreted
                as literal text with HTML mark-up.
                Because of this, there cannot be any <right brace> in the
                text, but it can be instead represented as a
                "character reference", e.g. &#x007d; or &rbrace;.

The "text escape" character is intended to enable writers to include
function names that are literal texts (e.g. "AES-256"), that are
not italicized as variables. 

The <CIRCUMFLEX ACCENT> character U+005E is the "super script" character.
The exactly 1 character (measured in a Unicode code point), or exactly
1 grouping following the "super script" character, is surrounded in
the HTML "sup" tag.

The <underscore> character U+005F is the "subscript" character.
The exactly 1 character (measured in a Unicode code point), or exactly
1 grouping following the "subscript" character, is surrounded in the
HTML "sub" tag.

The "{" <left brace> character U+007B is the grouping introducer, and
the "}" <right brace> character U+007D is the grouping terminator.
Anything between the two characters is put into a grouping,
which is then considered as a single unit. 
The "{" must not be preceeded by a <backslash>. The grouping may be
arbitrarily nested. 

Escaping
--------

The special characters mentioned so far (<backslash>, "^", "_", "{", "}")
must be escaped if they're to represent themselves literally. As the
output is HTML, they should be escaped using HTML entities.

However, due to the widespread use of relational operators, the sequences:
"<", ">", "<=", and ">=" are replaced with &lt; &gt; &le; and &ge;
respectively. 

IMPLEMENTATION DETAILS
======================

The function converts a simple expression to HTML code in 3 stages: 

1. Construct grouping tree.
2. Construct token list-tree from grouping tree.
3. Serialize HTML markup from token list-tree.

The initial parsing steps (1 and 2) could have been implemented
in 1 step, but it's decided that let step 2 process a single-level
list and descend recursively is more convenient than having to
implement recursion for grouping alongside tokenization.

Although grouping can nest arbitrarily, the braces in the 2nd form
of text escape cannot be nested. Doing otherwise is neither easy
nor useful.

All subroutines used for implementing mEval operates in UTF-8 encoding;
characters are considered based on their code point in Unicode.
In stage 2, alphabetical letters are considered based on their
code point in Unicode; HTML entities are decoded in this stage to
test their alphabeticalness, but are otherwise preserved to the
next stage.

Because HTML entities are preserved to the serialization stage 3,
and because sub/super-script work by default on the next 1 character,
HTML entities have to be put into grouping for them to be correctly
formatted. If an HTML entity is not put into grouping, then only the
beginning "&" is put into sub/super-script. 

HISTORY
=======

- 2020-03-21, "hc-0math-1eval.php" is being commentated. 
- 2021-09-30, 1 typo is corrected; the file had been converted to MarkDown.
