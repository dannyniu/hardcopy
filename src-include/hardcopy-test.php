<?php require_once("hardcopy.php"); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset=UTF-8>
    <title>HardCopy Basic Math Typesetting Test</title>
    <link rel=stylesheet href=hc-0math.css>
  </head>
  <body>

    <h1>Compound Matrix</h1>

    <?php
     $a = [];
     $a[] = mSqrt(mFrac(mEval("a^2+b^2"), mEval("2ab")), 2);
     $a[] = mSum(mEval("i=1"), "5", mEval("x^i"));
     $a[] = mProd(mEval("i=i"), "5", mEval("f(i)"));
     $a[] = mLim(mEval("x&rarr;0"), mFrac(mEval("\sin{x}"), mEval("x")));
     $a[] = mInt(mEval("a"), mEval("b"), mEval("g(x)\d{x}"));
     $a[] = mCbrt("-1");
     echo "<h2>Matrix Cells</h2>\n";
     echo "<span class=math>\n";
     foreach( $a as $f )
     {
       echo "<div class=p>\n$f\n</div>\n";
     }
     echo "</span>\n";
     echo "<h2>The Matrix</h2>\n";
     echo "<span class=math>\n";
     echo mMat(2, 3, $a, "bracket", 6);
     echo "</span>\n";
    ?>

    <h1>Chemical Formula</h1>

    <?php
     $Carbon = mSubSup(12, 6, "right").mSpan("C");
     $CO2 = mSpan("CO").mSubSup(null, 2);
     $Ammonium = mSpan("NH").mSubSup("+", 4);
     $Hydroxide = mSpan("OH").mSubSup("-",3);
     echo "<span class=math>\n";
     echo "<div class=p>$Carbon</div>\n";
     echo "<div class=p>$Ammonium</div>\n";
     echo "<div class=p>$Hydroxide</div>\n";
     echo "</span>\n";
    ?>
    
  </body>
</html>
