<?php
 require_once(getenv("HARDCOPY_SRCINC_MAIN"));

 $hdr1 = hc_H1("References");

 $refs = [];
 $refs["Dilithium"] = hcNamedAnchor("[Dilithium]", "ref-dilithium17");
 $refs["LPR10"]  = hcNamedAnchor("[LPR10]", "ref-lpr-2010");
 $refs["Lyu11"]  = hcNamedAnchor("[Lyu11]", "ref-Lyu-2011");
 $refs["Kyber"]  = hcNamedAnchor("[Kyber]", "ref-kyber17");
 $refs["PKCS.1"] = hcNamedAnchor("[PKCS#1]", "ref-pkcs1-v22");
 $refs["RSA78"]  = hcNamedAnchor("[RSA78]", "ref-rsa78");
 $refs["Shor95"] = hcNamedAnchor("[Shor95]", "ref-shor95");

 if( !hcPageBegin() ) return;
?>
<?= $hdr1 ?>

<ul id="references">
  <li>
    <?= $refs["Dilithium"] ?>
    Leo Ducas, Tancrede Lepoint, Vadim Lyubashevsky,
    Peter Schwabe, Gregor Seiler, and Damien Stehl;
    <i>CRYSTALS - Dilithium: Digital Signature from Module Lattices</i>
    <?= hcURL("https://ia.cr/2017/633") ?>
  </li>

  <li>
    <?= $refs["LPR10"] ?>
    Vadim Lyubashevsky, Chris Peikert, and Oded Regev;
    <i>On Ideal Lattices and Learning with Errors Over Rings</i>
    <?= hcURL("https://www.iacr.org/archive/eurocrypt2010/66320288/66320288.pdf") ?>
  </li>

  <li>
    <?= $refs["Lyu11"] ?>
    Vadim Lyubashevsky;
    <i>Lattice Signatures Without Trapdoors</i>
    <?= hcURL("https://ia.cr/2011/537") ?>
  </li>

  <li>
    <?= $refs["Kyber"] ?>
    Joppe Bos, Leo Ducas, Eike Kiltz, Tancrede Lepoint, Vadim Lyubashevsky,
    John M. Schanck, Peter Schwabe, Gregor Seiler and Demin Stehle;
    <i>CRYSTALS - Kyber: a CCA-secure module-lattice-based KEM</i>
    <?= hcURL("https://ia.cr/2017/634") ?>
  </li>

  <li>
    <?= $refs["PKCS.1"] ?>
    <i>PKCS #1: RSA Cryptography Specifications Version 2.2</i>
    <?= hcURL("https://datatracker.ietf.org/doc/rfc8017") ?>
  </li>

  <li>
    <?= $refs["RSA78"] ?>
    R.L. Rivest, A.Shamir, and L.Adleman;
    <i>A Method for Obtaining Digital Signatures
      and Public-Key Cryptosystems</i>
    1978 Communications of the ACM;
    Volume: 21, Issue: 2, pp 120-126;
    <?= hcURL("https://doi.org/10.1145/357980.358017") ?>
  </li>

  <li>
    <?= $refs["Shor95"] ?> Peter Shor;
    <i>Polynomial-Time Algorithm for Prime Factorization and
      Discrete Logaraithms on a Quantum Computer</i>
    1995 arXiv: Quantum Physics;
    <?= hcURL("https://doi.org/10.1137/S0097539795293172") ?>
  </li>
</ul>
