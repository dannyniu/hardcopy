<?php
 require_once(getenv('HARDCOPY_SRCINC_MAIN'));

 $hdr1 = hc_H1('Functions');
 $tbl1 = hc_Table('Trigonometric Functions');
 $tbl2 = hc_Table('Selected Identities');

 if( !hcPageBegin() ) return;
?>
<?= $hdr1 ?>

<p>
  We'll introduce some trigonometric functions and their properties.
</p>

<p><small><i>
  Tip: use single-quote in mEval
  as "\tan" is being unintentionally interpreted as "&lt;tab&gt;an".
</i></small></p>

<p>
  In a Cartesian coordinates system,
  draw a ray from the origin point to arbitrary direction.
  Let the angle between the positive half of <var>x</var>-axis and the ray
  be <span class=math><?= mEval('&alpha;'); ?></span>.
  Take a point <span class=math><?= mEval('P(x,y)'); ?></span> on the ray,
  let the distance between the origin point
  <span class=math><?= mEval('O(0,0)'); ?></span> be
  <span class=math><?= mEval('h'); ?></span>, ; we have:
</p>

<table>
  <caption><?= $tbl1 ?></caption>

  <tr><td><span class=math>
    <?=m().mEval('\sin &alpha; = ').mFrac(mEval('y'), mEval('h')).m()?>
  </span></td></tr>

  <tr><td><span class=math>
    <?=m().mEval('\cos &alpha; = ').mFrac(mEval('x'), mEval('h')).m()?>
  </span></td></tr>

  <tr><td><span class=math>
    <?=m().mEval('\tan &alpha; = ').mFrac(mEval('y'), mEval('x')).m()?>
  </span></td></tr>

</table>

<table>
  <caption><?= $tbl2 ?></caption>

  <tr><td><span class=math>
    <?=m().
       mEval('&lbrace;\sin,\cos,\tan&rbrace; 2k&pi;+&alpha;').' = '.
       mEval('&lbrace;\sin,\cos,\tan&rbrace; &alpha;').' '.
       mEval('(k&in;&integers;)').
       m()?>
  </span></td></tr>

  <tr><td><span class=math>
    <?=m().mEval('\sin -&alpha; = -\sin &alpha;').m()?>
  </span></td></tr>

  <tr><td><span class=math>
    <?=m().mEval('\cos -&alpha; = \cos &alpha;').m()?>
  </span></td></tr>

  <tr><td><span class=math>
    <?=m().mEval('\tan -&alpha; = -\tan &alpha;').m()?>
  </span></td></tr>

  <tr><td><span class=math>
    <?=m().mEval('\sin(&pi;+&alpha;) = -\sin &alpha;').m()?>
  </span></td></tr>

  <tr><td><span class=math>
    <?=m().mEval('\cos(&pi;+&alpha;) = -\cos &alpha;').m()?>
  </span></td></tr>

  <tr><td><span class=math>
    <?=m().mEval('\tan(&pi;+&alpha;) = \tan &alpha;').m()?>
  </span></td></tr>

</table>

<p>
  Analytic continuation of trigonometric functions
  are also available for complex numbers.
  See <?= hcNamedSection("Numbers", "table") ?>.
</p>
