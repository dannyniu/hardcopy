<?php
 require_once(getenv("HARDCOPY_SRCINC_MAIN"));

 $hdr1 = hc_H1("Maths Typesetting Demo");
 $hdr2 = hc_H1("Section Reference");

 if( !hcPageBegin() ) return;
?>
<?= $hdr1 ?>

<p>
  This page contains demos for typesetting some everyday maths expressions
  using the hardcopy template.
</p>

<p>
  First, sum of the exponents is equivalent to the product of the powers:
</p>

<p>
  &<# &<% b &><sup>&<mSum &<% i=1 &> &; &<% n &> &; &<% x_i &> &></sup> =
  &<mProd &<% i=1 &> &; &<% n &> &; &<% b^{x_i} &> &> &>
</p>

<div>
  <p>Formula for arithmetic progression</p>

  <p>&<$ a_n = a_1 + (n - 1) &times; d &></p>

  <p>&<# &<% S_n = na_1 + &> &<mFrac &<% n(n - 1) &> &; 2 &>&<% d &> &></p>
</div>

<div>
  <p>Formula for proportional progresssion</p>

  <p>&<$ a_n = a_1 &middot; q^{n-1} &></p>

  <p>&<# &<% S_n = &> &<mFrac &<% a_1(1 - q^n) &> &; &<% 1 - q &> &> &></p>
</div>

<div>
  <p>Formula for calculus</p>

  <p>&<# &<% \sin' x = \cos x &> =
    &<mFrac &delta; &<% \sin x &> &; &<% &delta;x &> &> &>
  </p>

  <p>&<# &<mInt &; &; &<% e^x &delta;x &> &> = &<% e^x + C &> &>
  </p>

  <p>&<# &<% e^t &> = &<mInt -&infin; &; t &; &<% e^x &delta;x &> &> &>
  </p>
</div>

<?= $hdr2 ?>

&<hcNamedSection CRYSTALS: Kyber and Dilithium &>
