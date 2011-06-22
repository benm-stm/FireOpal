<?php

require_once('CustomHtmlReporter.class.php');
require_once 'BrowserController.class.php';
require_once 'simpletest/unit_tester.php';
require_once 'set.php';

class TuleapIntegrationTests extends TestSuite {

    function addFiles($files) {
        foreach ($files as $file) {
                    $this->addFile($file);
        }
    }

    function run($reporter) {
        parent::run($reporter);
        BrowserController::stop();
    }

}

?>
