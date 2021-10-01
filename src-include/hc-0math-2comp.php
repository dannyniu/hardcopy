<?php
 function mSpan($s)
 {
   $ret = __mPreSpan();
   $ret .= $s;
   return $ret.__mPostSpan();
 }

 function mFrac($num, $den)
 {
   $ret = __mPreSpan();
   $ret .= " <span class=\"frac\">\n";
   $ret .= "  <span class=\"frac-num\">$num</span><br/>\n";
   $ret .= "  <span class=\"frac-den\">$den</span>\n";
   $ret .= " </span> \n";
   return $ret.__mPostSpan();
 }

 function mSqrt($e, $hscale=1.0)
 {
   $ret = __mPreSpan();
   $ret .= " <span class=\"radical\"";
   $ret .= " style=\"transform:scaleY($hscale);\">&radic;</span>";
   $ret .= "<span class=\"radicand\">\n";
   $ret .= "  $e \n";
   $ret .= " </span>\n";
   return $ret.__mPostSpan();
 }

 function mCbrt($e, $hscale=1)
 {
   $ret = __mPreSpan();
   $ret .= " <span class=\"radical\"";
   $ret .= " style=\"transform:scaleY($hscale);\">&#x221b;</span>";
   $ret .= "<span class=\"radicand\">\n";
   $ret .= "  $e \n";
   $ret .= " </span>\n";
   return $ret.__mPostSpan();
 }

 function mSum($i, $u, $e)
 {
   $ret = __mPreSpan();
   $ret .= " <span class=\"deco\">\n";
   $ret .= "  $u<br/>\n";
   $ret .= "  <span class=\"enco\">&sum;</span><br/>\n";
   $ret .= "  $i\n";
   $ret .= " </span> $e ";
   return $ret.__mPostSpan();
 }

 function mProd($i, $u, $e)
 {
   $ret = __mPreSpan();
   $ret .= " <span class=\"deco\">\n";
   $ret .= "  $u<br/>\n";
   $ret .= "  <span class=\"enco\">&prod;</span><br/>\n";
   $ret .= "  $i\n";
   $ret .= " </span> $e ";
   return $ret.__mPostSpan();
 }

 function mLim($i, $e)
 {
   $ret = __mPreSpan();
   $ret .= " <span class=\"deco\"><br/>\n";
   $ret .= "  <span class=\"enco\">lim</span><br>\n";
   $ret .= "  $i";
   $ret .= " </span> $e ";
   return $ret.__mPostSpan();
 }

 function mInt($i, $u, $e, $int="&int;")
 {
   $ret = __mPreSpan();
   $ret .= " <span class=\"deco\">\n";
   $ret .= "  <span class=\"enco2\">$int</span>\n";
   $ret .= "  <span class=\"aleft\">$u<br>\n";
   $ret .= "   &ZeroWidthSpace;<br>\n";
   $ret .= "   &ZeroWidthSpace;<br>\n";
   $ret .= "   $i";
   $ret .= " </span></span> $e \n";
   return $ret.__mPostSpan();
 }

 function mSupSub($sup=null, $sub=null, $align="left")
 {
   $ret = __mPreSpan();
   
   if( $sup === null && $sub === null )
     $ret .= "";

   else if( $sup !== null && $sub === null )
     $ret .= "<sup>$sup</sup>";

   else if( $sup === null && $sub !== null )
     $ret .= "<sub>$sub</sub>";

   else if( $sup !== null && $sub !== null )
     $ret .= "<span class='deco a$align'>$sup<br>$sub</span>";

   return $ret.__mPostSpan();
 }

 function mSubSup($sup=null, $sub=null, $align="left")
 {
   // Useful alias.
   return mSupSub($sup, $sub, $align);
 }

 function mMat($rows, $cols, $array, $style="paren", $lineheight=0, $colmajor=false)
 {
   $ret = __mPreSpan();
   $abs     = [ "&#x23b8;", "&#x23b8;", "&#x23b8;", "&#x23b8;", "&#x23b8;", "&#x23b8;", ];
   $bracket = [ "&#x23a1;", "&#x23a2;", "&#x23a3;", "&#x23a4;", "&#x23a5;", "&#x23a6;", ];
   $paren   = [ "&#x239b;", "&#x239c;", "&#x239d;", "&#x23a7;", "&#x23a8;", "&#x23a9;", ];
   $enclose = null;

   if( !$lineheight ) $lineheight = $rows;
   switch( $style )
   {
     case "abs":
     case "det":
     $enclose = $abs;
     break;

     case "bracket":
     $enclose = $bracket;
     break;

     case "paren":
     default:
     $enclose = $paren;
     break;
   }

   $ret .= "<table class=matrix>\n";
   for($n=0; $n<$rows; $n++)
   {
     $ret .= "<tr>\n";

     if( 0 == $n )
     {
       $ret .= "<td rowspan=$rows>";
       $ret .= $enclose[0]."<br>";
       for($i = 2; $i<$lineheight; $i++)
         $ret .= $enclose[1]."<br>";
       $ret .= $enclose[2]."</td>\n";
     }

     for($m=0; $m<$cols; $m++)
     {
       $index = $colmajor ? $n+$m*$rows : $n*$cols+$m;
       $cell = $array[$index];
       $ret .= " <td>$cell</td>\n";
     }

     if( 0 == $n )
     {
       $ret .= "\n<td rowspan=$rows>";
       $ret .= $enclose[3]."<br>";
       for($i = 2; $i<$lineheight; $i++)
         $ret .= $enclose[4]."<br>";
       $ret .= $enclose[5]."</td>\n";
     }

     $ret .= "</tr>\n";
   }
   $ret .= "</table>\n";

   return $ret.__mPostSpan();
 }
