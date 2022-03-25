<?php
 class hcPostProc
 {
   static $math_nested = 0;
   static $str_buffer = 256;

   static function __parse_1directive($fp)
   {
     $ret = "";
     $name = "";
     $c = "";

     while( !feof($fp) )
     {
       $c = fread($fp, 1);
       if( ctype_space($c) ) break;
       else $name .= $c;
     }

     $subargs = self::__parse_directives($fp);
     if( $name == '%' )
     {
       $ret .= mEval(...$subargs);
     }
     else if( $name == '$' )
     {
       if( 0 == self::$math_nested ) $ret .= "<span class=\"math\">";
       ++self::$math_nested;
       $ret .= mEval(...$subargs);
       --self::$math_nested;
       if( 0 == self::$math_nested ) $ret .= "</span>";
     }
     else if( $name == '#' )
     {
       if( 0 == self::$math_nested ) $ret .= "<span class=\"math\">";
       ++self::$math_nested;
       $ret .= join(' ', $subargs);
       --self::$math_nested;
       if( 0 == self::$math_nested ) $ret .= "</span>";
     }
     else if( $name == ':' ) // ''null'' directive.
     {
       $ret .= join(' ', $subargs);
     }
     else if( $name == "array" )
     {
       $ret = $subargs; // for ``mMat''.
     }
     else $ret .= $name(...$subargs);

     return $ret;
   }

   static function __parse_directives($fp)
   {
     $name = "";
     $args = [];
     $str = "";

     while( !feof($fp) )
     {
       $c = fread($fp, 1);
       if( $c == "&" )
       {
         $c = fread($fp, 1);

         if( $c == "<" ) // ''&<'' opens a post-processing directive.
         {
           $subret = self::__parse_1directive($fp);
           if( is_array($subret) )
           {
             $args[] = $subret;
             $str = null;
           }
           else $str .= $subret;
         }
         else if( $c == ";" ) // ''&;'' delimits an argument.
         {
           if( $str !== null ) $args[] = trim($str);
           $str = "";
         }
         else if( $c == ">" ) // ''&>'' closes a post-proc directive.
         {
           if( $str !== null ) $args[] = trim($str);
           $str = "";

           return $args;
         }
         else if( $str !== null ) $str .= "&".$c;
       }
       else if( $str !== null ) $str .= $c;
     }

     $args[] = $str;
     $str = "";
     return $args;
   }

   static function Proc($fp)
   {
     $str = "";
     $len = 0;
     $c = "";

     while( !feof($fp) )
     {
       $c = fread($fp, 1);
       if( $c == "&" )
       {
         $c = fread($fp, 1);

         if( $c == "<" )
         {
           $s = self::__parse_1directive($fp);
           $str .= $s;
           $len += strlen($str);
         }
         else
         {
           $str .= "&".$c;
           $len += 2;
         }
       }
       else
       {
         $str .= $c;
         $len++;
       }
       
       if( $len >= self::$str_buffer )
       {
         echo $str;
         $str = "";
         $len = 0;
       }
     }

     echo $str;
   }
 }
