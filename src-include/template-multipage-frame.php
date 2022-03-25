<?php
 $toc = "toc.html";
 $cov = "$Cover.html";

 if( $OutputControl == "pageframe/" )
 {
   $exts = [ "php", "htm", "html", ];
   foreach( $exts as $ext )
   {
     if( is_file("toc.$ext") ) $toc = "toc.php?oc=toc/";
     if( is_file("$Cover.$ext") ) $cov = "toc.php?oc=$Cover";
   }
 }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset='UTF-8'>
    <title><?= $Title ?></title>
    <style>
     iframe {
       position:        fixed;
       top:             0;
       height:          100vh;
     }

     iframe[name=toc] {
       left:            0;
       width:           calc(12vw + 120px);
     }

     iframe[name=main] {
       left:            calc(12vw + 120px);
       width:           calc(88vw - 120px);
     }
    </style>
  </head>
  <body>

    <iframe name=toc src="<?= htmlspecialchars($toc) ?>">
    </iframe>

    <?php
     $mainsrc =
       $Cover === null ?
       "about:blank" :
       htmlspecialchars($cov);
    ?>
    <iframe name=main src="<?= htmlspecialchars($mainsrc) ?>">
    </iframe>

  </body>
</html>
