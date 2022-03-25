<?php
 require_once(getenv('HARDCOPY_SRCINC_MAIN'));

 $hdr1 = hc_H1('Some Historical Mathematicians');

 if( !hcPageBegin() ) return;
?>
<?= $hdr1 ?>

<ul>
  <li>Euclid of Alexandria</li>
  <li>Leonhard Euler</li>
  <li>Carl Friedrich Gauss</li>
</ul>
