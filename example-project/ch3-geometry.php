<?php
 require_once(getenv('HARDCOPY_SRCINC_MAIN'));

 $hdr1 = hc_H1('Geometry');

 if( !hcPageBegin() ) return;
?>
<?= $hdr1 ?>

<p>
  The 5 essential axioms of Euclidean geometry
  will be presented in this section.
  Euclidean geometry is the most intuitive geometry
  to work with; there is no curvature in space in
  Euclidean geometry; the norm in Euclidean geometry
  is <var>p</var>-norm with <?= mEval("p = 2") ?>
  due to Pythagorean theorem
</p>

<ol>
  <li> A line can be drawn from a point to any other point. </li>
  <li> A finite line can be extended indefinitely. </li>
  <li> A circle can be drawn, given a center and a radius. </li>
  <li> All right angles are ninety degrees. </li>
  <li> If a line intersects two other lines such that
    the sum of the interior angles on one side of the
    intersecting line is less than the sum of two right angles,
    then the lines meet on that side and not on the other side.
    (also known as the Parallel Postulate) </li>
</ol>

<p>
  Due to the length of text expressing the 5th axiom,
  there were suspicion that it's not an independent axiom
  and could be proved from the other 4, however, no such
  proof was ever successful; also suspected, is that
  when the 5th axiom was stated otherwise, there will be
  a contradiction in the system, attempts to create
  alternative formulations ultimately resulted in
  various systems that're now known as non-Euclidean geometry,
  and each were as self-consistent as Euclidean geometry itself.
</p>
