<?php
 require_once(getenv("HARDCOPY_SRCINC_MAIN"));

 //if( !hcPageBegin() ) return;
?>

<p>
  This page demonstrates the new version of Hardcopy basic maths expression
  markup usage, by discussion basic symmetric-key cryptography building blocks:
  blockcipher and hash functions; and some well-known public-key algorithms.
</p>

<?= hc_H1("Symmetric-Key Cryptography") ?>

<?= hc_H2("Blockciphers") ?>

<p>
  Blockciphers are keyed pseudo-random permutations of fixed-width data.
  Traditionally blockciphers often had 64-bit block sizes, and 64- or 128- bit
  key sizes. In modern times, blockciphers often have 128-bit block sizes, and
  key sizes ranging from 128 to 256 bits.
</p>

<p>
  Mathematically, a blockcipher is the tuple of 2 functions -
  &<$ E_k(p) &> and &<$ D_k(c) &> each taking a key &<$ k &> and satisfying:
  &<$ D_k(E_k(p)) = p &> and that
  &<$ p, c &in; B^{L_b} &> and
  &<$ k &in; B^{L_k} &> where
  &<$ B &> is the set of values a byte can take, and
  &<$ L_b &> is the block length in bytes, and
  &<$ L_k &> is the key length in bytes.
</p>

<?= hc_H2("Hash Functions") ?>

<p>
  Hash functions take an arbitrary-length message, compress it, and produce
  a fixed-length digest as output. A cryptographic hash function has
  3 properties:
</p>

<ol>
  <li>Collision Resistance:
    It should be cryptographically impossible to find
    2 different messages that hashes to the same digest.
  </li>

  <li>Pre-Image Resistance:
    It should be cryptographically impossible to
    find a message that hash to a particular (pre-chosen) digest.
  </li>

  <li>Second Pre-Image Resistance:
    It should be cryptographically impossible to
    find a different message that hash to the digest of
    another given message.
  </li>
</ol>

<p>
  Mathematically, a hash function &<$ H &> is one such that
  &<$ H(B^*) = B^L &> where
  &<$ B^* &> is the input message, where &<$ * &> indicates that
  the length is arbitrary. and
  &<$ L &> is the length of the digest output in bytes.
</p>

<p>
  Traditionally, hash functions are built out of iterated compression
  functions. These compression functions are typically mode of operation
  of blockciphers. Take SHA-256 as an example, it first pad the message
  to make its length the multiple of the input block size of the compression
  function, then the compression function takes each message block in turn,
  transforming an initialization vector, towards the final digest.
  Mathematically:
</p>

<ul>
  <li> &<$ H(M) = h_n &> </li>
  <li> &<$ h_i = C(h_{i-1}, M_i) &> </li>
  <li> &<$ h_0 = IV &> </li>
  <li> &<$ C(h,m) = E_m(h) &oplus; h &> </li>
  <li> &<$ M_1 | M_2 | ... | M_n = pad(M) &> </li>
</ul>

<?= hc_H1("Public-Key Cryptography") ?>

<p>
  Public-key cryptography is the class of cryptography where algorithms
  have asymmetric key-pairs. One of the key in the pair is private - never
  disclosed, the other is public - known by unlimited number of parties.
  There are 2 important class of algorithms: digital signature, and
  public-key encryption, with key exchange as the functional equivalent
  of the latter.
</p>

<?= hc_H2("RSA") ?>

<p>
  RSA is one of the earliest practical public-key cryptosystems discovered by
  mathematicians, and is still being used to this day.
</p>

<p>
  In the original RSA <?= cite("ref-rsa78") ?>,
  a modulus &<$ N &> is calculated as the product of 2 primes:
  &<$ p &> and &<$ q &>. a public exponent &<$ e &> is chosen to be some
  small odd number, typically choices include: 3, 11, 17, 65537,
  a corresponding private exponent &<$ d &> is computed as the modular inverse:
  &<$ d = e^{-1} \mod (p - 1)(q - 1) &>, which can be calculated using the
  extended Euclidean algorithm.
</p>

<p>
  The RSA cryptosystem has the property of being a bijective permutation that's
  asymmetric, as such, the same set of algorithms can be used for both
  digital signature and public-key encryption.
  In the newer PKCS#1 v2.2 <?= cite("ref-pkcs1-v22") ?>
  standard for RSA cryptography, provisions for more than 2 primes are made,
  to allow for more efficient computation of decryption operation using
  Chinese Remainder Theroem.
</p>

<?= hc_H2("CRYSTALS: Kyber and Dilithium") ?>

<p>
  In 1995, Peter Shor discovered an algorithm <?= cite("ref-shor95") ?> that can
  factor large pseudo-primes in polynomial-time on quantum computers, as well
  as solving the "discrete logarithm problem" on cyclic groups. A discovery
  that motivated the industry and academics to search for "post-quantum"
  public-key algorithms.
</p>

<p>
  For the purpose of this section, we'll demonstrate the typesetting of
  relevant notations such as matrices, used in the linear algebra concepts
  that underlies the design of 2 post-quantum candidate algorithms from the
  CRYSTALS family:
  Kyber <?= cite("ref-kyber17") ?> and
  Dilithium <?= cite("ref-dilithium17") ?>.
</p>

<p>
  Both algorithms are based on module variant of LWE and SIS hard problems.
  In essence, using module-based scheme can increase the difficulty of lattice
  reduction attack at just fraction of the bandwidth cost, whilest minimizing
  the potential attackable structures when compared to ring-based schemes.
</p>

<p>
  Instead of working with full matrices (as is the case with unstructured
  lattice) or full polynomial rings, the cryptosystems work with matrices of
  polynomial rings at a moderate degree. With NTT the number-theoretic
  transformation which provides a way to accelerate the computation on
  the underlying polynomial, increasing security can be as easy as increasing
  the dimension of the matrix to add more polynomial elements without revising
  the code to work with anything radically different.
</p>

<p>
  The dimension (degree) of the ring module is 256 in Kyber and Dilithium.
  This makes arithmetic over a ring element
  &<# &<$ r(x) = &> &<mSum &<% i=0 &> &; 255 &; &<% r_i &middot; x^i &> &> &>
  equivalent to arithmetic over a (structured) matrix element:
</p>

&<# &<mMat 5 &; 5 &;
      &<array
        &<% r_0 &> &; &<% r_1 &> &; &<% r_2 &> &; &ctdot; &; &<% r_{255} &> &;
  &<% -r_{255} &> &; &<% r_0 &> &; &<% r_1 &> &; &ctdot; &; &<% r_{254} &> &;
  &<% -r_{254} &> &; &<% -r_{255} &> &; &<% r_0 &> &; &ctdot; &; &<% r_{253} &> &;
  &vellip; &; &vellip; &; &vellip; &; &dtdot; &; &vellip; &;
  &<% -r_1 &> &; &<% -r_2 &> &; &<% -r_3 &> &; &ctdot; &; &<% r_0 &>  &>
  &;
  paren &; 8 &> &>

  <p>
    The Kyber algorithm is a key encapsulation mechanism based on LPR-style
    encryption <?= cite("ref-lpr-2010") ?> , whereas Dilithium is a
    Fiat-Shamir-style digital signature scheme based on the framework of
    Vadim Lyubashevsky <?= cite("ref-Lyu-2011") ?>
    that uses only discrete uniform random sampling.
  </p>
