<?php

require_once 'simpletest/autorun.php';
require_once 'BrowserController.class.php';
require_once('CustomHtmlReporter.class.php');
require_once 'set.php';

class AllTests extends TestSuite {

    function AllTests() {
        $this->TestSuite('Selenium Test Suite');
        $this->collectFiles('../tests');
    }

    function collectFiles($path) {
        $testFiles = new DirectoryIterator($path);
        foreach ($testFiles as $node) {
            if (!$node->isDot()) {
                if ($node->isDir()) {
                    $this->collectFiles($node->getPathName());
                } else {
                    $this->addFile($node->getPathName());
                }
            }
        }
    }

    function run($reporter) {
        parent::run($reporter);
        BrowserController::stop();
    }

}

?>
