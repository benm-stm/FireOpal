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
class TestSuite {

    const RSPEC_HTML_FORMATTER          = 1;
    const RSPEC_PROGRESS_FORMATTER      = 2;
    const RSPEC_DOCUMENTATION_FORMATTER = 6;
    const RSPEC_COLOR                   = 8;

    private $name;
    private $_testCases;
    private $_currentTestCase;
    private $_result;
    private $_testSuiteFile;

    /**
     * Constructor of the class
     *
     * @param Array  $testCases     Testcases composing the testsuite
     * @param String $testSuiteName Name of the testsuite
     *
     * @return Void
     */
    public function __construct(array $testCases, $testSuiteName) {
        if (!empty($testSuiteName)) {
            $this->name = $testSuiteName;
        } else {
            $this->name = 'noName';
        }
        $this->_testSuiteFile = new SplFileInfo(dirname(__FILE__).'/../testsuites/'.$this->name.'.rb');
        //@TODO   use test case objects collection instead
        $this->_testCases     = $testCases;
        $this->_result        = array();
        $this->_testCasesMap  = new SplObjectStorage();
    }

    /**
     * Launch test Suite
     *
     * @return Void
     */
    public function run() {
        exec('rspec '.$this->_testSuiteFile.' --format documentation --out ../log/resultFile_'.time().' 2>&1', $this->_result);
    }

    /**
     * Attach a Given test case to the test suite
     *
     * @param TestCase $testCase Testcase to be atached to testsuite
     *
     * @return Void
     */
    public function attach($testCase) {
        $this->_testCasesMap->attach($testCase);
    }

    /**
     * Include binded test files within the header of the ruby test Suite file.
     *
     * @param SplFileObject $rspecFileObj ???
     *
     * @return Void
     */
    public function bindTestSuiteRequirements($rspecFileObj) {
        if ($rspecFileObj->isWritable()) {
            foreach ($this->_testCases as $testCase) {
                try {
                    $testCaseFileObj = new SplFileObject($testCase);
                    $rspecFileObj->fwrite("    describe \"".$testCaseFileObj->getBasename('.rb')."\" do\n");
                    while ($testCaseFileObj->valid()) {
                        if ((strrpos($testCaseFileObj->current(), 'require') === false) && (strrpos($testCaseFileObj->current(), 'gem') === false)) {
                            $rspecFileObj->fwrite("        ".$testCaseFileObj->current());
                            $testCaseFileObj->next();
                        } else {
                            $testCaseFileObj->next();
                        }
                    }
                    $rspecFileObj->fwrite("\n    end\n\n");
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
        }
    }

    /**
     * Build RSpec code examples from binded test cases
     *
     * @param SplFileObject $rspecFileObj ???
     *
     * @return Void
     */
    public function bindTestCases($rspecFileObj) {
        if ($rspecFileObj->isWritable()) {
            $rspecFileObj->fwrite("\ndescribe \"".$this->name."\" do\n\n");
            $this->bindTestSuiteRequirements($rspecFileObj);
            $rspecFileObj->fwrite("end\n\n");
        }
    }

    /**
     * Build RSpec setup
     *
     * @param SplFileObject $rspecFileObj ???
     *
     * @return Void
     */
    public function bindRspecSetUp($rspecFileObj) {
        if ($rspecFileObj->isWritable()) {
            $content = "describe \"Configuration preprocess\" do\n";
            //$content .= "    it \"Should prepare test suite configuration\" do\n";
            $content .= "        before(:each) do\n";
            $content .= "            @valid = Configuration.new\n";
            $content .= "            @valid.setup()\n";
            $content .= "            @valid.login()\n";
            $content .= "        end\n";
            //$content .= "    end\n";
            $content .= "end\n\n";
            $rspecFileObj->fwrite($content);
        }
    }

    /**
     * Build RSpec teardown
     *
     * @param SplFileObject $rspecFileObj ???
     *
     * @return Void
     */
    public function bindRspecTearDown($rspecFileObj) {
        if ($rspecFileObj->isWritable()) {
            $content = "describe \"Teardown process\" do\n";
            $content .= "    it \"Should Cleanup after test suite runtime\" do\n";
            $content .= "        after(:each) do\n";
            $content .= "            #@valid.logout()\n";
            $content .= "            @valid.teardown()\n";
            $content .= "        end\n";
            $content .= "    end\n";
            $content .= "end\n\n";
            $rspecFileObj->fwrite($content);
        }
    }

    /**
     * Apply conf parameters to the generated test Suite,
     * Using Ruby syntax, add a conf class then  add Setup, teardown and login methods
     * @TODO Review the whole conf stuff within a suitable design pattern, we need some flexibility here :'(
     *
     * @param  String $request
     *
     * @return Void
     */
    public function bindConfigurationElements($request) {
        try {
            $testSuiteFileObj = $this->_testSuiteFile->openFile('a');
            if ($this->_testSuiteFile->isWritable()) {
                $content = "require 'rubygems'\n";
                $content .= "require 'selenium-webdriver'\n";
                $content .= "require 'rspec/autorun'\n\n";
                $setupManager = new SetupManager();
                if($set = $setupManager->extractSetup($request)) {
                    $content .= "class Configuration\n\n";
                    $content .= "    def setup\n";
                    $tearDown             = "    def teardown\n        @driver.quit\n    end\n\n";
                    $login                = "    def login\n";
                    $loginActionPerformed = "        @driver.find_element(:name, \"login\").click\n    end\n\n";
                    $driver               = "        @driver = Selenium::WebDriver.for :remote,";
                    foreach ($set as $name => $entry) {
                        switch ($name) {
                            case "host" :
                            //web application to be tested
                            $target = "        @driver.get '".$entry['value']."'\n";
                            break;
                            case "client" :
                            $driver .= " :url => 'http://".$entry['value'].":4444/wd/hub',";
                            break;
                            case "browser" :
                            //whatever you want, i'll launch ff
                            $driver .= " :desired_capabilities => :firefox\n";
                            break;
                            case "user" :
                            $login .= "        @driver.find_element(:name, \"form_loginname\").send_keys \"".$entry['value']."\"\n";
                            break;
                            case "password" :
                            //@TODO  ^__^"
                            $login .= "        @driver.find_element(:name, \"form_pw\").send_keys \"".$entry['value']."\"\n";
                            break;
                            case "project" :
                            break;
                            case "project_id" :
                            break;
                            default:
                        }
                    }
                    $content .= $driver;
                    $content .= $target;
                    $content .= "        @driver.manage.timeouts.implicit_wait = 30\n";
                    $content .= "    end\n\n";
                    $content .= $tearDown;
                    $content .= $login;
                    $content .= $loginActionPerformed;
                    $content .= "end\n\n";
                    $testSuiteFileObj->fwrite($content);
                }
            }
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Store conf in the correponding testsuite
     *
     * @param  String $request
     *
     * @return Void
     */
    function storeTestSuiteDetails($request) {
        try {
            $testSuiteFileObj = $this->_testSuiteFile->openFile('a');
            if ($this->_testSuiteFile->isWritable()) {
                //Conf storage
                $setupManager = new SetupManager();
                $setupManager->storeConf($request, $this->_testSuiteFile->getPathname());
                //Test Cases storage
                $content = "#--- Test Cases list ---\n";
                foreach ($this->_testCases as $testCase) {
                    $this->_currentTestCase = $testCase;
                    $content .= "# ".str_replace(substr(dirname(__FILE__), 0, -7).'www/../testcases/', ' ', $testCase)."\n";
                }
                $content .= "#--- Test Cases End ---\n\n";
                $testSuiteFileObj->fwrite($content);
            }
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Build a test suite from binded test cases and apply a given conf
     *
     * @return Void
     */
    public function loadTestSuite() {
        try {
            $fileObj = $this->_testSuiteFile->openFile('a');
            if ($this->_testSuiteFile->isWritable()) {
                $fileObj->fwrite("\n# Here Comes RSpec examples \n\n");
                $this->bindRspecSetUp($fileObj);
                $this->bindTestCases($fileObj);
                $this->bindRspecTearDown($fileObj);
            }

        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Returns the testCase currently being updated
     *
     * @return TestCase
     */
    public function getCurrent() {
        return $this->_currentTestCase;
    }

    /**
     * ???
     *
     * @param String $path Path of the file to add
     *
     * @return Void
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
        $inSetup = false;
        $content = "";
        $file    = $this->_testSuiteFile->openFile('r');
        while (!$file->eof()) {
            $line = $file->fgets();
            if ($inSetup && $line == "#--- Test Cases End ---\n") {
                $inSetup = false;
            }
            if ($inSetup) {
                $content .= $line;
            }
            if (!$inSetup && $line == "#--- Start Conf in setup here\n") {
                $inSetup = true;
            }
        }
        return $content;
     }
}

?>
