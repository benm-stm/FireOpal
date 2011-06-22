<?php

require_once('../include/AllTests.class.php');

$dd = new AllTests();

$reporter = $dd->run(new CustomHtmlReporter());

?>