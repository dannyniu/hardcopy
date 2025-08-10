<?php
 require_once(getenv("HARDCOPY_SRCINC_MAIN"));

 $Title = "Test Doc on the Topic of Cryptography";
 $Cover = "abs-cover";

 hcAddPages("ch01_02-crypto");
 hcAddPages("ch03-maths");

 hc_StartAnnexes();
 hcAddPages("ch_a-refs");

 hcFinish();
