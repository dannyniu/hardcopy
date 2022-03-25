<?php
 require_once("hc-0misc.php");
 require_once("hc-0math-0core.php");
 require_once("hc-0math-1eval.php");
 require_once("hc-0math-2comp.php");

 require_once("hc-1toc.php");

 require_once("hc-9postproc.php");

 if( getenv("ocget") === false )
 {
   // ocget stand for "Output-Control parameters set from GET request".
   $arg = "true";
   $OutputControl = getenv("HARDCOPY_OUTPUT_CONTROL");
   
   if( $OutputControl === false )
   {
     $arg = "false";
     putenv("HARDCOPY_OUTPUT_CONTROL=".($_GET["oc"] ?? ""));
   }
   
   $fp = popen("ocget=$arg php ./toc.php", "rb");
   hcPostProc::Proc($fp);
   pclose($fp);
   exit();
 }
 
