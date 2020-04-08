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

    <iframe name=toc src="toc.html">
    </iframe>

    <?php $mainsrc = $Cover === null ? "about:blank" : "$Cover.html"; ?>
    <iframe name=main src="<?= htmlspecialchars($mainsrc) ?>">
    </iframe>

  </body>
</html>
