<?php
 require_once(getenv("HARDCOPY_SRCINC_MAIN"));

 $hdr1 = hc_H1("Numbers");
 $tbl1 = hc_Table("Numbers");

 if( !hcPageBegin() ) return;
?>
<?= $hdr1 ?>

<p>
  There are many types of numbers, here's some of them:
</p>

<table>
  <caption><?= $tbl1 ?></caption>
  <thead>

    <tr>
      <th>type of number</th>
      <th>set symbol</th>
      <th>examples</th>
    </tr>

  </thead>
  <tbody>

    <tr>
      <td>natural numbers</td>
      <td><span class="math">&naturals;</span></td>
      <td>0, 1, 2, 3, etc.</td>
    </tr>

    <tr>
      <td>integers / whole numbers</td>
      <td><span class="math">&integers;</span></td>
      <td>-5, -3, -2, 0, 1, 4, 6, etc.</td>
    </tr>

    <tr>
      <td>rational numbers</td>
      <td><span class="math">&rationals;</span></td>
      <td>-3, 7/8, 22/7, 9.6, etc.</td>
    </tr>

    <tr>
      <td>real numbers</td>
      <td><span class="math">&reals;</span></td>
      <td>
        <?= mSpan("0, ".mSqrt("2").", &pi;, e"); ?>, etc.
      </td>
    </tr>

    <tr>
      <td>complex numbers</td>
      <td><?= mSpan("&complexes;") ?></td>
      <td>
        <?= mEval("7, 3+2i, ").mSqrt("-1"); ?>, etc.
      </td>
    </tr>

  </tbody>
</table>
