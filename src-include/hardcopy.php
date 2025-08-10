<?php
 require_once("hc-0misc.php");
 require_once("hc-0math-0core.php");
 require_once("hc-0math-1eval.php");
 require_once("hc-0math-2comp.php");

 // 'ocget' stands for "Output-Control GETenv".
 if( getenv("ocget") === false )
 {
   $GLOBALS['hcPreprocExit'] = true;

   // 2024-04-11: other future potential headers around here.
   require_once("hc-1toc.php");
   // 2024-04-11: other future potential headers around here.

   $ocpl = ($_GET['oc'] ?? getenv("HARDCOPY_OUTPUT_CONTROL")) === "pagelist/";

   if( !$ocpl )
   {
     echo "<!DOCTYPE html>\n";
     echo "<html>\n";
     echo "  <head>\n";
     echo "    <meta charset=\"UTF-8\">\n";

     echo "<template hidden>";
   }
   include("./toc.php");
   if( !$ocpl )
   {
     echo "</template>";
   }

   // - getenv('ocget'):
   //   * Boolean false means pipe the output of pages into the
   //     post-processor.
   //   * String-wise "true" means the output control environment variable
   //     had been set externally for generating static pages.
   //   * String-wise "false" means the output control environment variable
   //     had been set from $_GET['oc'] in this page (i.e. "hardcopy.php"),
   //     and the project is supposedly being viewed from a web browser.
   //
   // - $_GET['oc']:
   //   Dictates which page to output. Passed from the GET parameter
   //   to the ${HARDCOPY_OUTPUT_CONTROL} environment variable in
   //   this page (i.e. "hardcopy.php").
   //
   // - ${HARDCOPY_OUTPUT_CONTROL}:
   //   Dictates which page to output. Passed from environment to
   //   the ``$OutputControl'' lexical variable in "hc-1toc.php".
   //
   $hcArg = "true";
   if( getenv("HARDCOPY_OUTPUT_CONTROL") === false )
   {
     $hcArg = "false";
     putenv("HARDCOPY_OUTPUT_CONTROL=".($_GET["oc"] ?? ""));
   }
   putenv("ocget=$hcArg");

   // 2024-04-11: other future potential headers around here.
   require_once("hc-9postproc.php");
   // 2024-04-11: other future potential headers around here.

   $fp = popen("php ./toc.php", "rb");
   hcPostProc::Proc($fp);
   #fpassthru($fp);

   pclose($fp);
   exit();
 }
 else
 {
   // 2024-04-11: other future potential headers around here.
   require_once("hc-1toc.php");
   require_once("hc-9postproc.php");
   // 2024-04-11: other future potential headers around here.
 }
