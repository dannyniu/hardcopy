Hardcopy - Maths Component
==========================

This document describes Hardcopy's functions for displaying maths expressions.

A post-processing stage had been added to Hardcopy rendering process to enable
the verbosity of maths expressions to be decreased.

# Opening, Closing, and Delimiting operators

The opening and closing operators are similar to the opening and closing angle
brackets of HTML tags. The opening operator is `&<*` where `*` specify the
name of the operator function to invoke. The closing operator is `&>`. The
delimiting operator is `&;`. 

These characters are chosen so that they're unlikely to conflict with actual
HTML tags or entities.

In essence, the opening operator introduces a post-processing directive, which
invokes a function to process the actual content. The directives can be nested.
The delimiting operator delimits the arguments to the function. 

## The `$` Directive.

To typeset a simple expression, the easiest way is to do something like this:

```
&<$ f(x) = ax^2 + bx + c &>
```

The `&<` operator introduces a directive, the `$` name is a short-cut for
simple math expression, See `doc/simple-expression.md` for details.

## The `#` and `%` Directive.

The `$` directive actually performs 2 actions: introduce a `span` element with
`math` class, call `mEval` to render the simple expression.

Due to lack of robustness of Hardcopy maths component, it is sometimes 
necessary to invoke the 2 steps separately. For example, to typeset a matrix
without yet using a simple expression; or to typeset a simple expression as
the sub-expression of a compound expression.

The `#` directive outputs a `span` element with `math` class. 

The `%` directive renders a simple expression without implicitly introducing
a math span.

## Functions: `{mSqrt,mCbrt}(string $e, float $hscale=1): string`

The functions `mSqrt` and `mCbrt` surrounds the math expression markup `$e` 
with the square and cubic root radical sign. A scaling is applied by user if 
it can be determined that the height of the expression doesn't match the 
height of a single line of text.

## Functions: `{mSum,mProd}(string $i, string $u, string $e): string`

The functions `mSum` and `mProd` applys the summation and product notation to 
the expression `$e`. The expressions `$i` and `$u` will respectively be placed 
at the bottom and the top of the Sigma or the Pi symbol. 

## Function: `mLim(string $i, string $e): string`

The function produces limit applies limit notation to the expression `$e`. 
The expression `$i` is placed under the limit sign.

## Function: `mInt(string $i, string $u, string $e, $int="&int;"): string`

Produces an integral notation. The default integral sign is the definite 
integral sign, a different one can be specified on the `$int` argument.

The expression `$i` will be placed at the lower right of the integral sign, 
and `$u` at the top right. The expression `$e` will be placed to the right of
the expression.

## Function: `{mSubSup,mSupSub}($sup=null, $ub=null, $align="left"): string`

The function `mSubSup` produces a standalone markup consisting of an optional
superscript stacked on top of an optional subscript. Such markup can be 
suitable for use in atomic notation in chemistry.

The function mSupSub is an alias to mSubSup.

## Function: `mMat(int $rows, int cols, array $array, $style="paren", $lineheight=0, $colmajor=false): string`

Produces markup for a matrix with `$rows` rows and `$cols` columns. The content
of the matrix is passed in `$array` in row-major order unless `$colmajor` is 
set to true.

The paired punctuation surrounding the matrix defaults to parentheses
("paren"), and may be set to "bracket" for squre brackets, "abs" or "det" for
vertical bars, in the `$style` argument.

The height of the matrix must be manually set in the `$lineheight` argument, 
which defaults to 0 meaning take the value from the $rows argument.
