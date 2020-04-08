<?php
 # For documentation and explanation of
 # what mEval and all these functions do,
 # refer to "doc/simple-expression.txt"

 function __mEval_str2groups($str)
 {
   $a = [];
   $stack = [];
   $pos = 0;
   $len = strlen($str);

   // substring anchor.
   $rem = 0;

   for(;;)
   {
     $c = nextc($str, $pos, $len);

     if( $c < 0 ) // end-of-string, flush.
     {
       $a[] = substr($str, $rem, $pos-$rem);
       break;
     }

     if( $c == ord("{") ) // grouping, flush then push.
     {
       $a[] = substr($str, $rem, $pos-$rem-1);
       array_push($stack, $a);
       $a = [];
       $rem = $pos;
     }

     else if( $c == ord("\\") ) // text-escape, consume input.
     {
       $d = nextc($str, $pos, $len);
       if( $d == ord("{") )
         while( $d != ord("}") && $d >= 0 )
           $d = nextc($str, $pos, $len);
     }

     else if( $c == ord("}") ) // end of grouping, flush then pop.
     {
       $a[] = substr($str, $rem, $pos-$rem-1);
       $b = array_pop($stack);
       $b[] = $a;
       $a = $b;
       $rem = $pos;
     }
   }

   return $a;
 }

 function __mEval_groups2tree($a)
 {
   $tree = [];

   $rem = 0;
   $rem1 = 0;
   $pos = 0;
   $pos1 = 0;
   // there could be more rem* and pos*, but none yet now.

   // There are 3 types of token:
   // text, letters, arithmetics.
   foreach( $a as $str )
   {
     $elem = [
       "t"=>null, // token types: t, l, a; or subtree: s.
       "v"=>null, // string or array.
     ];

     if( is_array($str) ) // grounping encountered here.
     {
       $elem["t"] = "s";
       $elem["v"] = __mEval_groups2tree($str);
       array_push($tree, $elem);
       continue;
     }

     $pos = 0;
     $len = strlen($str);
     for(;;)
     {
       $pos1 = $pos;
       $c = nextc($str, $pos, $len); // physical character.
       $cc = $c; // logical character.

       if( $c == ord("&") ) // decode HTML entity into logical character.
       {
         $d = $c;
         $rem1 = $pos1;
         while( $d != ord(";") && $d >= 0 )
           $d = nextc($str, $pos, $len);

         $ent = substr($str, $rem1, $pos-$rem1);
         $ent = html_entity_decode($ent);
         $l = strlen($ent);
         $p = 0;
         $cc = nextc($ent, $p, $l);
       }

       if( $c < 0 )
       {
         // end-of-string,
         // flush then continue in next in ``$a''.

         if( $elem["t"] ) {
           $elem["v"] = substr($str, $rem, $pos1-$rem);
           array_push($tree, $elem);
         }
         break;
       }

       else if( __mIsAlpha($cc) )
       {
         // consume input if true,
         if( $elem["t"] == "t" || $elem["t"] == "l" );

         // else, flush and reset ``$elem''.
         else
         {
           if( $elem["t"] ) {
             $elem["v"] = substr($str, $rem, $pos1-$rem);
             array_push($tree, $elem);
           }
           $rem = $pos1;
           $elem = [
             "t"=>"l",
             "v"=>"",
           ];
         }
       }

       else if( __mIsDigit($cc) )
       {
         // consume input if true,
         if( $elem["t"] == "t" || $elem["t"] == "a" );

         // else, flush and reset ``$elem''.
         else
         {
           if( $elem["t"] ) {
             $elem["v"] = substr($str, $rem, $pos1-$rem);
             array_push($tree, $elem);
           }
           $rem = $pos1;
           $elem = [
             "t"=>"a",
             "v"=>"",
           ];
         }
       }

       else if( $c == ord("\\") ) # not backslash entity.
       {
         if( $elem["t"] ) { // flush previous ``$elem''.
           $elem["v"] = substr($str, $rem, $pos1-$rem);
           array_push($tree, $elem);
         }
         $rem = $pos;
         $elem = [
           "t"=>"t", // Start a "text escape".
           "v"=>"",
         ];
       }

       else if( $c == ord("{") &&
                $elem["t"] == "t" &&
                $pos - $rem == 1 )
       {
         // Process the "text escape".
         $d = $c;
         $rem1 = $pos;
         while( $d != ord("}") && $d >= 0 )
           $d = nextc($str, $pos, $len);

         // Flush the "text escape" and reset ``$elem''.
         $elem["v"] = substr($str, $rem1, $pos-$rem1-1);
         array_push($tree, $elem);
         $elem = [
           "t"=>null,
           "v"=>null,
         ];
       }

       else // symbol (mostly operators) assumed.
       {
         // consume input if true,
         if( $elem["t"] == "a" );

         // else, flush and reset ``$elem''.
         else
         {
           if( $elem["t"] ) {
             $elem["v"] = substr($str, $rem, $pos1-$rem);
             array_push($tree, $elem);
           }
           $rem = $pos1;
           $elem = [
             "t"=>"a",
             "v"=>"",
           ];
         }
       }

     } // -- for(;;)
   } // -- foreach( $a as $str )

   return $tree;
 }

 function __mEval_translate($tree)
 {
   $ret = "";
   $ss = false; // sub/super-script flag.
   $tag = ""; // either "sub" or "sup".

   foreach( $tree as $elem )
   {
     $str = $elem["v"];
     $rem = 0;
     $pos = 0;
     $len = is_string($str) ? strlen($str) : 0;

     if( $ss )
     {
       $ss = false;

       if( $elem["t"] == "s" )
       {
         $ret .= "<$tag>".__mEval_translate($str)."</$tag>";
         continue;
       }

       else
       {
         $c = cp2chr(nextc($str, $pos, $len));
         if( $elem["t"] == "l" )
           $c = "<var>$c</var>";
         $ret .= "<$tag>$c</$tag>";
         $rem = $pos;
       }
     }

     if( $elem["t"] == "s" )
     {
       $ret .= __mEval_translate($str);
     }

     else if( $elem["t"] == "l" )
     {
       $ret .= "<var>".substr($str, $rem)."</var>";
       continue;
     }

     else if( $elem["t"] == "t" )
     {
       $ret .= substr($str, $rem);
       continue;
     }

     else if( $elem["t"] == "a" )
     {
       for(;;)
       {
         $pos1 = $pos;
         $c = nextc($str, $pos, $len);

         if( $c < 0 )
         {
           $ret .= substr($str, $rem, $pos-$rem);
           break;
         }

         if( $c == ord("<") || $c == ord(">") )
         {
           $ret .= substr($str, $rem, $pos1-$rem);
           $pos2 = $pos;
           $c1 = nextc($str, $pos, $len);

           if( $c1 == ord("=") ){
             if( $c == ord("<") ) $ret .= "&le;";
             if( $c == ord(">") ) $ret .= "&ge;";
             $rem = $pos;
             continue;
           }

           else {
             if( $c == ord("<") ) $ret .= "&lt;";
             if( $c == ord(">") ) $ret .= "&gt;";
             $rem = $pos2;
             $pos = $pos2;
             continue;
           }
         } // -- if( $c == "<" || $c == ">" )

         else if( $c == ord("^") || $c == ord("_") )
         {
           $ret .= substr($str, $rem, $pos1-$rem);
           $pos2 = $pos;
           $c1 = nextc($str, $pos, $len);

           $tag = ($c == ord("^") ? "sup" : "sub");

           if( $c1 >= 0 ){
             $ret .= "<$tag>".cp2chr($c1)."</$tag>";
             $rem = $pos;
             continue;
           }

           else { // same as ( $c < 0 )
             $ss = true;
             break;
           }
         } // -- else if( $c == "^" || $c == "_" )
       } // -- for(;;)

       continue;
     } // -- else if( $elem["t"] == "a" )
   } // -- foreach( $tree as $elem )

   return $ret;
 }

 function __mEval_dumptree__($tree, $ilevel = 0)
 {
   $ret = "";

   $ret .= sprintf("%s[\n", str_repeat("\t", $ilevel));
   foreach( $tree as $elem )
   {
     if( $elem["t"] != "s" )
     {
       $ret .= sprintf(
         "%s%s(%s)\n",
         str_repeat("\t", $ilevel+1),
         $elem["t"],
         $elem["v"]);
     }
     else
     {
       $ret .= __mEval_dumptree($elem["v"], $ilevel+1);
     }
   }
   $ret .= sprintf("%s]\n", str_repeat("\t", $ilevel));

   return $ret;
 }

 function __mEval_test__()
 {
   $v = __mEval_str2groups("x^{sum_{\cond_1}(y_i)} = prod_{\{cond}^2}(x^{y_i})");
   // print_r($v);
   $v = __mEval_groups2tree($v);
   echo __mEval_dumptree__($v);
   $v = __mEval_translate($v);
   print_r($v);
 }

 function mEval($s)
 {
   $v = __mEval_str2groups($s);
   $v = __mEval_groups2tree($v);
   $v = __mEval_translate($v);
   return $v;
 }
