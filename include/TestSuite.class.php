<?php
/**
 * Copyright (c) STMicroelectronics 2012. All rights reserved
 *
 * This code is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this code. If not, see <http://www.gnu.org/licenses/>.
 */

require_once 'SetupManager.class.php';
class testSuite implements SplSubject {

    const RSPEC_HTML_FORMATTER = 1;
    const RSPEC_PROGRESS_FORMATTER = 2;
    const RSPEC_DOCUMENTATION_FORMATTER = 6;
    const RSPEC_COLOR = 8;

    private $name;
    private $_testCases;
    private $_observers;
    private $_currentTestCase;
    private $_result;
    private $_testSuiteFile;

    /**
     *
     */
    public function __construct(array $testCases, $testSuiteName) {
        if (!empty($testSuiteName)) {
            $this->name = $testSuiteName;
        } else {
        $this->name     = 'noName';
        }
        $this->_testSuiteFile = new SplFileInfo(dirname(__FILE__).'/../testsuites/'.$this->name.'_'.time().'.rb');
        $this->_testCases = $testCases;
        $this->_observers = array();
        $this->_result    = array();
        //@TODO   Better prefer dependency injection here. use SplObjectStorage
        //$this->_observers = new SplObjectStorage();
    }

    /**
     * Launch test Suite
     *
     */
    public function run() {
        exec('rspec '.$this->_testSuiteFile.' --format documentation --out resultFile_'.time().' 2>&1', $this->_result);
    }

    /**
     * Include binded test files within the header of the ruby test Suite file.
     * @param SplFileObject $rspecFileObj
     *
     **/
    public function bindTestSuiteRequirements($rspecFileObj) {
        if ($rspecFileObj->isWritable()) {
            foreach ($this->_testCases as $testCase) {
                try {
                    $testCaseFileObj = new SplFileObject($testCase);
                    $rspecFileObj->fwrite("require '".$testCaseFileObj->getBasename('.rb')."'\n");
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
            $rspecFileObj->fwrite("\n");
            $rspecFileObj->fwrite("describe ".$this->name." do\n");
            //$rspecFileObj->fwrite("    it ".$this->name." do\n");
        }
    }

    /**
     * Build RSpec code examples from binded test cases
     * @param SplFileObject $rspecFileObj
     *
     **/
    public function bindTestCases($rspecFileObj) {
        if ($rspecFileObj->isWritable()) {
            foreach ($this->_testCases as $key => $testCase) {
                try {
                //For the moment, we suppose that the class name and the test file name are the same.
                    $testCaseFileObj = new SplFileObject($testCase);
                    $rspecFileObj->fwrite("    it \"Run testcase ".$testCaseFileObj->getBasename('.rb')."\" do\n");
                    $rspecFileObj->fwrite("        test_".$key." = ".$testCaseFileObj->getBasename('.rb').".new\n");
                    $rspecFileObj->fwrite("        test_".$key.".run()\n");
                    $rspecFileObj->fwrite("    end\n\n");
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
        }
    }

    /**
     * Store conf in the correponding testsuite
     * @param  String $request
     *
     **/
    function storeConfIntoTestSuite($request) {
        try {
            $testSuiteFileObj = $this->_testSuiteFile->openFile('a');
            if ($this->_testSuiteFile->isWritable()) {
                $setupManager = new SetupManager();
                $setupManager->storeConf($request, $this->_testSuiteFile->getPathname());
            }
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Build a test suite from binded test cases and apply a given conf
     *
     **/
    public function loadTestSuite() {
        try {
            $fileObj = $this->_testSuiteFile->openFile('a');
            if ($this->_testSuiteFile->isWritable()) {
                $this->bindTestSuiteRequirements($fileObj);
                $this->bindTestCases($fileObj);
                $fileObj->fwrite("end\n");
            }

        } catch (RuntimeException $e) {
            echo $e->getMessage();
            // @TODO Specify here what i'm supposed to render if i'm not able to create the ruby file...
        }
    }

    /**
     * Returns the testCase currently being updated
     *
     * @return $testCase
     */
    public function getCurrent() {
        return $this->_currentTestCase;
    }

    /**
     * Attach an observer
     *
     * @param SplObserver $observer
     */
    public function attach(SplObserver $observer) {
        array_push($this->_observers, $observer);
    }

    /**
     * Detach an observer
     *
     * @param SplObserver $observer
     */
    public function detach(SplObserver $observer) {
        foreach ($this->_observers as $key => $item)
        {
            if ($observer == $item) {
                unset($this->_observers[$key]);
            }
        }
    }

    /**
     * Send notification to all observers
     */
    public function notify() {
        foreach ($this->_observers as $key => $item) {
            $item->update($this);
        }
    }

    /**
     * Add given files to the test suite
     *
     * @param Array $files array of file path of tests to add to test suite
     *
     * @return void
     */
    function addFiles($files) {
        if (file_exists('../log/last_run')) {
            rename('../log/last_run', '../log/integration_tests_'.time());
        }
        $fp = fopen('../log/last_run', 'a');
        fwrite($fp, "Run on ".date('l jS \of F Y h:i:s A')."\n");
        foreach ($files as $file) {
            $this->files[] = $file;
            fwrite($fp, basename($file)."\n");
        }
        fclose($fp);
    }

    /**
     *
     */
    function addTestFile($path) {
        $this->files[] = $path;
    }

    /**
     * Display setup of the testsuite and assosciated testcases
     *
     * @return String
     */
    function displayDetails() {
        // TODO: Extract details from testsuite
        return "";
    }

}

?>

