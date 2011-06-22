<?php

require_once('../include/TuleapIntegrationTests.class.php');

$dd = new AllTests();

$reporter = $dd->run(new CustomHtmlReporter());

?>