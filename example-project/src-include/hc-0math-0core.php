<?php
 function nextc($str, &$pos, &$len)
 {
   if( $pos >= $len ) return -1;

   $c = ord($str[$pos++]);

   if( 0 <= $c && $c <= 0x7f )
   {
     // leave it ascii.
   }

   else if( 0xC2 <= $c && $c <= 0xDF )
   {
     $c &= 0x1F;
     $c = $c << 6 | (ord($str[$pos++]) & 0x3F);
   }

   else if( 0xE0 <= $c && $c <= 0xEF )
   {
     $c &= 0x0F;
     $c = $c << 6 | (ord($str[$pos++]) & 0x3F);
     $c = $c << 6 | (ord($str[$pos++]) & 0x3F);
   }

   else if( 0xF0 <= $c && $c <= 0xF4 )
   {
     $c &= 0x07;
     $c = $c << 6 | (ord($str[$pos++]) & 0x3F);
     $c = $c << 6 | (ord($str[$pos++]) & 0x3F);
     $c = $c << 6 | (ord($str[$pos++]) & 0x3F);
   }

   else $c = 0xFFFD;

   return $c;
 }

 function __mIsAlpha($c)
 {
   $cond1 = 0x41 <= $c && $c <= 0x5A;
   $cond2 = 0x61 <= $c && $c <= 0x7A;
   $cond3 = 0x391 <= $c && $c <= 0x3A9;
   $cond4 = 0x3B1 <= $c && $c <= 0x3C9;
   return $cond1 || $cond2 || $cond3 || $cond4;
 }

 function __mIsDigit($c)
 {
   return 0x30 <= $c && $c <= 0x39;
 }

 function cp2chr($c) // code point to character.
 {
   $ret = "";

   if( $c < 0x80 )
   {
     $ret = chr($c);
   }

   else if( $c < 0x800 )
   {
     $ret .= chr(0xC0 | ($c >> 6 & 0x1F));
     $ret .= chr(0x80 | ($c >> 0 & 0x3F));
   }

   else if( $c < 0x10000 )
   {
     $ret .= chr(0xE0 | ($c >> 12 & 0x0F));
     $ret .= chr(0x80 | ($c >>  6 & 0x3F));
     $ret .= chr(0x80 | ($c >>  0 & 0x3F));
   }

   else if( $c < 0x110000 )
   {
     $ret .= chr(0xF0 | ($c >> 18 & 0x07));
     $ret .= chr(0x80 | ($c >> 12 & 0x3F));
     $ret .= chr(0x80 | ($c >>  6 & 0x3F));
     $ret .= chr(0x80 | ($c >>  0 & 0x3F));
   }

   return $ret;
 }
