<?php
 require_once(getenv("HARDCOPY_SRCINC_MAIN"));

 $Title = "Test Maths Doc";
 $Cover = "abs-cover";

 hcAddPages("ch1-numbers");
 hcAddPages("ch2-functions");
 hcAddPages("ch3-geometry");

 hcFinish();
