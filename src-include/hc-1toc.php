<?php
 $__exts__ = [ "php", "htm", "html", "md.php", "md.html", ];

 $Title = "Untitled";
 $Cover = null;

 $CurrentPage = ""; // Argument ``$p'' to hcAddPage.

 // - counters for -:
 //   h1, h2, h3, h4,
 //   table, figure.
 // for each increment of h<n>, h<n+1...3> will be reset;
 // for each increment of h1, table and figure will be reset.
 $Counters = [ 0, 0, 0, 0, 0, 0 ];

 // - anchor -:
 //   type: h[1-4], table, figure.
 //   page: - copied from ``$CurrentPage'' -.
 //   name: - Argument ``$s'' to hc_H[1-4] and hc_{Table,Figure} -.
 //   prefix: - h[1-4]: counters -;
 //           - {table,figure}: {Table,Figure} n.n -.
 //   title: - prefix name -
 //   id: - type .+ (counter ~= s/\W/-/g) .+ sha1(name).hexdigits()[0:5] -.
 $Anchors = [];

 // Statistics of the ``$Anchors'' array.
 $AnchorsStats = [ "headings"=>0, "tables"=>0, "figures"=>0, ];

 // - page -:
 //   name: - Argument ``$p'' to hcAddPage.
 //   anchorpos: - count($Anchor) when hcAddPage was invoked -.
 //   ext:
 $Pages = [];

 $Target = "";
 $AnchorPos = 0;
 $PageCanBegin = false;

 // 1 $GLOBALS['hcPreprocExit'] defined elsewhere.
 if( !isset($GLOBALS['hcPreprocExit']) )
   $GLOBALS['hcPreprocExit'] = false;

 // - Empty string to output everything in 1 page.
 // - "pagelist/" to list page names line-by-line.
 // - "toc/" to output table of content and index.
 // - "pageframe/" to output multi-page frame viewer.
 // - Any other strings not ending in <slash> to
 //   specify the page to output, in which case,
 //   ".html" will be automatically suffixed.
 $OutputControl = $_GET['oc'] ?? getenv("HARDCOPY_OUTPUT_CONTROL");
 if( $OutputControl === false ) $OutputControl = "";

 function __hc_Hn($s, $n)
 {
   global $CurrentPage, $Counters;
   global $Anchors, $AnchorsStats;
   global $AnchorPos, $PageCanBegin;

   $ap = $AnchorPos++;
   if( $PageCanBegin )
   {
     $anchor = $Anchors[$ap] ?? null;
     if( $anchor === null ) return "";
     $ret = "";
     $ret .= "\n<h$n";
     $ret .= " id=\"".$anchor["id"]."\"";
     $ret .= " data-a-prefix=\"".$anchor["prefix"]."\"";
     $ret .= ">".htmlspecialchars($anchor["name"]);
     $ret .= "</h$n>\n\n";
     return $ret;
   }

   else
   {
     $anchor = [];
     $anchor["type"] = "h$n";
     $anchor["page"] = $CurrentPage;
     $anchor["name"] = $s;

     for($i=$n; $i<4; $i++)
       $Counters[$i] = 0;

     if( $n == 1 )
     {
       $Counters[4] = 0;
       $Counters[5] = 0;
     }

     if( $n == 1 && $Counters[0] === "@" )
       $Counters[0] = "A"; // Not we're in an annex.
     else
       $Counters[$n-1]++; // Numerical chapter numbering.

     $counter = "";
     for($i=0; $i<$n; $i++)
       $counter .= strval($Counters[$i]).".";

     $anchor["prefix"] =
       (is_string($Counters[0]) && $n == 1 ? "Annex " : "").
       "$counter";
     $anchor["title"] = $anchor["prefix"]." ".$anchor["name"];
     $anchor["id"] =
       $anchor["type"]."-".
       preg_replace('/\W/', "-", $counter).
       substr(sha1($anchor["name"]), 0, 5);

     $Anchors[] = $anchor;
     $AnchorsStats["headings"]++;
     return count($Anchors);
   }
 }

 function hc_H1($s){ return __hc_Hn($s, 1); }
 function hc_H2($s){ return __hc_Hn($s, 2); }
 function hc_H3($s){ return __hc_Hn($s, 3); }
 function hc_H4($s){ return __hc_Hn($s, 4); }
 // h5 and h6 are reserved for intra-section unnumbered subtitles.

 function hc_Table($s)
 {
   global $CurrentPage, $Counters;
   global $Anchors, $AnchorsStats;
   global $AnchorPos, $PageCanBegin;

   $ap = $AnchorPos++;
   if( $PageCanBegin )
   {
     $anchor = $Anchors[$ap];
     $ret = "";
     $ret .= "<a";
     $ret .= " id=\"".$anchor["id"]."\"";
     $ret .= " data-a-prefix=\"".$anchor["prefix"]."\"";
     $ret .= ">".htmlspecialchars($anchor["name"]);
     $ret .= "</a>\n";
     return $ret;
   }

   else
   {
     $anchor = [];
     $anchor["type"] = "table";
     $anchor["page"] = $CurrentPage;
     $anchor["name"] = $s;

     $Counters[4]++;
     $counter = strval($Counters[0]).".".strval($Counters[4]).".";

     $anchor["prefix"] = "Table $counter";
     $anchor["title"] = $anchor["prefix"]." ".$anchor["name"];
     $anchor["id"] =
       $anchor["type"]."-".
       preg_replace('/\W/', "-", $counter).
       substr(sha1($anchor["name"]), 0, 5);

     $Anchors[] = $anchor;
     $AnchorsStats["tables"]++;
     return count($Anchors);
   }
 }

 function hc_Figure($s)
 {
   global $CurrentPage, $Counters;
   global $Anchors, $AnchorsStats;
   global $AnchorPos, $PageCanBegin;

   $ap = $AnchorPos++;
   if( $PageCanBegin )
   {
     $anchor = $Anchors[$ap];
     $ret = "";
     $ret .= "<a";
     $ret .= " id=\"".$anchor["id"]."\"";
     $ret .= " data-a-prefix=\"".$anchor["prefix"]."\"";
     $ret .= ">".htmlspecialchars($anchor["name"]);
     $ret .= "</a>\n";
     return $ret;
   }

   else
   {
     $anchor = [];
     $anchor["type"] = "figure";
     $anchor["page"] = $CurrentPage;
     $anchor["name"] = $s;

     $Counters[5]++;
     $counter = strval($Counters[0]).".".strval($Counters[5]).".";

     $anchor["prefix"] = "Figure $counter";
     $anchor["title"] = $anchor["prefix"]." ".$anchor["name"];
     $anchor["id"] =
       $anchor["type"]."-".
       preg_replace('/\W/', "-", $counter).
       substr(sha1($anchor["name"]), 0, 5);

     $Anchors[] = $anchor;
     $AnchorsStats["figures"]++;
     return count($Anchors);
   }
 }

 function hc_StartAnnexes()
 {
   global $Counters;
   $Counters[0] = "@";
 }

 function __pagename2filename($s)
 {
   if( getenv("ocget") === "false" )
     return "toc.php?".http_build_query([ 'oc' => $s ]);
   else return "$s.html";
 }

 function hcNamedSection($s, $type=null)
 {
   // Also usable with tables and figures
   // and whatever else recorded in ``$Anchors''.
   global $Anchors;
   global $Target, $OutputControl;

   foreach( $Anchors as $anchor )
   {
     if( $anchor["name"] !== $s )
       continue;
     if( $type !== null && $anchor["type"] !== $type )
       continue;

     $href = "#".$anchor["id"];
     if( $OutputControl !== "" )
       $href = __pagename2filename($anchor["page"]).$href;
     $href = htmlspecialchars($href);

     return "<a $Target href=\"$href\">".$anchor["title"]."</a>";
   }
   return "";
 }

 function hcPageBegin()
 {
   global $PageCanBegin;
   return $PageCanBegin && !$GLOBALS['hcPreprocExit'];
 }

 function hcAddPages($p)
 {
   global $__exts__;
   global $CurrentPage, $Anchors, $Pages, $OutputControl;

   foreach( $__exts__ as $ext )
   {
     $cand = "$p.$ext";
     if( is_file($cand) )
     {
       $CurrentPage = $p;
       $page = [];
       $page["name"] = $p;
       $page["anchorpos"] = count($Anchors);
       $page["ext"] = $ext;
       $Pages[] = $page;

       if( $OutputControl !== "pagelist/" )
         include($cand);
       break;
     }
   }
   return;
 }

 function __hc_OutputTocIndex($statname, $listhead, $matchtype)
 {
   global $Anchors, $AnchorsStats;
   global $Target, $OutputControl;

   if( $AnchorsStats[$statname] > 0 )
   {
     echo "\n<div class=\"toc-list-head\">$listhead</div>\n\n";
     echo "<ol class=\"toc-list\">\n";

     foreach( $Anchors as $anchor )
     {
       $type = $anchor["type"];
       if( !preg_match($matchtype, $type) ) continue;

       $href = "#".$anchor["id"];
       if( $OutputControl !== "" )
         $href = __pagename2filename($anchor["page"]).$href;
       $href = htmlspecialchars($href);

       echo "<li><a $Target href=\"$href\">".$anchor["title"]."</a></li>\n";
     }

     echo "</ol>\n";
   }
 }

 function __hc_OutputNavbar($pageind, $pagecnt)
 {
   global $Pages, $Target;

   $prev = null;
   if( $pageind > 0 )
   {
     $page = $Pages[$pageind-1];
     $href = __pagename2filename($page["name"]);
     $href = htmlspecialchars($href);
     $prev = " <a $Target href=\"$href\">[Previous]</a> ";
   }

   $next = null;
   if( $pageind+1 < $pagecnt )
   {
     $page = $Pages[$pageind+1];
     $href = __pagename2filename($page["name"]);
     $href = htmlspecialchars($href);
     $next = " <a $Target href=\"$href\">[Next]</a> ";
   }

   $ret = "";
   $toc = "toc.html";

   if( getenv("ocget") !== "true" ) $toc = "toc.php?oc=toc/";

   $ret .= "<nav class=navbar-multipage>";
   $ret .= $prev ?? " <a>(first)</a> ";
   $ret .= " <a target=toc href=\"$toc\">[Table of Contents]</a> ";
   $ret .= $next ?? " <a>(last)</a> ";
   $ret .= "</nav>\n\n";

   return $ret;
 }

 function hcFinish()
 {
   global $__exts__;
   global $Title, $Cover;
   global $Anchors, $AnchorsStats;
   global $Pages, $Target, $AnchorPos, $PageCanBegin, $OutputControl;

   if( $GLOBALS['hcPreprocExit'] === true )
     return;

   echo "\x02"; // ASCII Start Of Text.

   if( $OutputControl === "pagelist/" )
   {
     if( is_string($Cover) ) printf("%s\n", $Cover);
     foreach( $Pages as $page ) printf("%s\n", $page["name"]);
     return;
   }

   if( $OutputControl === "pageframe/" )
   {
     include("template-multipage-frame.php");
     return;
   }

   if( $OutputControl !== "" )
     $Target = "target=\"main\"";

   $title = $Title;
   $pagetitle = null;
   $page = $OutputControl;
   $cnt = count($Anchors);

   foreach( $Anchors as $anchor )
   {
     if( $anchor["page"] === $page )
     {
       $pagetitle = $anchor["name"];
       break;
     }
   }

   if( $pagetitle !== null ) $title = "$title - $pagetitle";

   foreach( glob(getenv("HARDCOPY_SRCINC")."/*.css") as $css )
   {
     $ssn = preg_replace('#^.*/([^/]+)$#', '$1', $css);
     $ssn = getenv("HARDCOPY_SRCBLD")."/$ssn";
     echo "<link rel=stylesheet href=\"".htmlspecialchars($ssn)."\"/>\n";
   }

   foreach( glob("assets/*.css") as $css )
   {
     echo "<link rel=stylesheet href=\"".htmlspecialchars($css)."\"/>\n";
   }

   echo "<title>".htmlspecialchars($title)."</title>\n";
   echo "</head>\n";

   echo "<body>\n\n";

   // Ready to output.
   $AnchorPos = 0;
   $PageCanBegin = true;

   // Output cover page.
   $CoverFile = null;

   if( $OutputControl === "" || $OutputControl === $Cover )
   {
     foreach( $__exts__ as $ext )
     {
       $cand = "$Cover.$ext";
       if( is_file($cand) )
       {
         include($cand);
         echo "\n<div class=\"pagebreak\"></div>\n\n";
         break;
       }
     }
   }

   if( is_string($Cover) && $OutputControl === "toc/" )
   {
     $href = __pagename2filename($Cover);
     $href = htmlspecialchars($href);
     echo "\n<div class=toc-list-head>";
     echo "<a $Target href=\"$href\">Cover Page</a>";
     echo "</div>\n\n";
   }

   // Output table of content and index.
   if( $OutputControl === "" || $OutputControl === "toc/" )
   {
     __hc_OutputTocIndex("headings", "Table of Contents", '/^h[1-4]$/');
     __hc_OutputTocIndex("tables", "Tables", '/^table$/');
     __hc_OutputTocIndex("figures", "Figures", '/^figure$/');
     echo "\n<div class=\"pagebreak\"></div>\n\n";
   }

   echo "\x03"; // ASCII End Of Text.

   // Output document contents.
   $cnt = count($Pages);
   for($i=0; $i<$cnt; $i++)
   {
     $page = $Pages[$i];
     $p = $page["name"];
     $ext = $page["ext"];

     $PageCanBegin = $p === $OutputControl || $OutputControl === "";
     if( $PageCanBegin ) echo "\x02"; // ASCII Start Of Text.

     $navbar = __hc_OutputNavbar($i, $cnt);
     if( $PageCanBegin && $OutputControl !== "" )
       echo $navbar;

     include("$p.$ext");

     if( $PageCanBegin && $OutputControl !== "" )
       echo $navbar;
     echo "\x03"; // ASCII End Of Text.
   }

   echo "\x02"; // ASCII Start Of Text.

   echo "\n</body>\n</html>\n";
   return;
 }

 // * Dict with ID as Key *
 // - NamedAnchor -:
 //   page: - Argument ``$p'' to hcAddPage. -
 //   text: - Argument ``$s'' to hcNamedAnchor. -
 $NamedAnchors = [];

 function hcNamedAnchor($s, $id)
 {
   global $CurrentPage, $NamedAnchors;

   $anchor = [];
   $anchor["page"] = $CurrentPage;
   $anchor["text"] = $s;
   $NamedAnchors[$id] = $anchor;

   return "<a id=\"namedanchor-$id\">$s</a>";
 }

 function hcNamedHref($id)
 {
   global $NamedAnchors;
   global $Target, $OutputControl;

   $anchor = $NamedAnchors[$id] ?? [
     'page' => "",
     'text' => "" ];
   $href = "#namedanchor-$id";
   if( $OutputControl !== "" )
     $href = __pagename2filename($anchor["page"]).$href;
   $href = htmlspecialchars($href);

   return "<a $Target href=\"$href\">".$anchor["text"]."</a>";
 }

 function cite($id)
 {
   return "<sup>".hcNamedHref($id)."</sup>";
 }
