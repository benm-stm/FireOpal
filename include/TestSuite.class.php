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

require_once 'Setup.class.php';
class TestSuite {

    const RSPEC_HTML_FORMATTER          = 1;
    const RSPEC_PROGRESS_FORMATTER      = 2;
    const RSPEC_DOCUMENTATION_FORMATTER = 6;
    const RSPEC_COLOR                   = 8;

    private $name;
    private $_result;
    private $_testSuiteFile;
    private $_testCasesMap;

    /**
     * Constructor of the class
     *
     * @param String $testSuiteName Name of the testsuite
     *
     * @return Void
     */
    public function __construct($testSuiteName) {
        if (!empty($testSuiteName)) {
            $this->name = $testSuiteName;
            $testSuiteManager = new TestSuiteManager();
            $this->_testSuiteFile = new SplFileInfo($testSuiteManager->getTestSuitesLocation().$this->name.'.rb');
        } else {
            throw new InvalidArgumentException('TestSuite constructor needs a string parameter. Input was : '.$testSuiteName);
        }
        $this->_result        = array();
        $this->_testCasesMap  = new SplObjectStorage();
    }

    /**
     * Launch test Suite
     *
     * @return Void
     */
    public function run() {
        exec('ruby '.$this->_testSuiteFile.' --format documentation --out ../log/resultFile_'.time().' 2>&1', $this->_result);
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
     * Write into  to the test suite file the  RSpec code example of each binded test case.
     *
     * @param SplFileObject $rspecFileObj The file object of the test suite
     *
     * @return Void
     */
    public function bindTestSuiteRequirements($rspecFileObj) {
        if ($rspecFileObj->isWritable()) {
            foreach ($this->_testCasesMap as $testCase) {
                    $testCaseFileObj = new SplFileObject($testCase->_testCaseFile);
                    $rspecFileObj->fwrite($testCase->retrieveRspecExampleGroup());
            }
        } else {
            throw new BadMethodCallException('Something went wrong when trying to write to test suite file "'.$this->_testSuiteFile.'".');
        }
    }

    /**
     * Build RSpec code examples from binded test cases
     * @TODO: Handle errors
     *
     * @param SplFileObject $rspecFileObj The file object of the test suite
     *
     * @return Void
     */
    public function bindTestCases($rspecFileObj) {
        if ($rspecFileObj->isWritable()) {
            $rspecFileObj->fwrite("\ndescribe \"".$this->name."\" do\n\n");
            $this->bindRspecSetUp($rspecFileObj);
            $this->bindTestSuiteRequirements($rspecFileObj);
            $this->bindRspecTearDown($rspecFileObj);
            $rspecFileObj->fwrite("end\n\n");
        } 
    }

    /**
     * Build RSpec setup
     * @TODO: Complete function comment
     *
     * @param SplFileObject $rspecFileObj The file object of the test suite
     *
     * @return Void
     */
    public function bindRspecSetUp($rspecFileObj) {
        if ($rspecFileObj->isWritable()) {
            $content = "    before(:all) do\n";
            $content .= "        @valid = Configuration.new\n";
            $set     = "";
            $set     = file_get_contents(dirname(__FILE__).'/../conf/set.ini');
            $content .= "        @setup  = JSON.parse('".$set."')\n";
            $content .= "        @valid.setup(@setup)\n";
            $content .= "        @valid.login()\n";
            $content .= "        @driver = @valid.getdriver\n";
            $content .= "    end\n\n";
            $rspecFileObj->fwrite($content);
        } else {
            throw new BadMethodCallException('Something went wrong when trying to write to test suite file "'.$this->_testSuiteFile.'".');
        }
    }

    /**
     * Build RSpec teardown
     * @TODO: Complete function comment
     *
     * @param SplFileObject $rspecFileObj The file object of the test suite
     *
     * @return Void
     */
    public function bindRspecTearDown($rspecFileObj) {
        if ($rspecFileObj->isWritable()) {
            $content = "    after(:all) do\n";
            $content .= "        @valid.teardown()\n";
            $content .= "    end\n\n";
            $rspecFileObj->fwrite($content);
        } else {
            throw new BadMethodCallException('Something went wrong when trying to write to test suite file "'.$this->_testSuiteFile.'".');
        }
    }

    /**
     * Apply conf parameters to the generated test Suite,
     * Using Ruby syntax, add a conf class then  add Setup, teardown and login methods
     *
     * @return Void
     */
    public function bindConfigurationElements() {
        try {
            $testSuiteFileObj = $this->_testSuiteFile->openFile('a');
            if ($this->_testSuiteFile->isWritable()) {
                $content = "require 'rubygems'\n";
                $content .= "require 'selenium-webdriver'\n";
                $content .= "require 'rspec/autorun'\n";
                $content .= "require 'json'\n\n";
                $content .= "class Configuration\n\n";
                $content .= "    def setup(setup)\n";
                $content .= "        @setup  = setup\n";
                $content .= "        @driver = Selenium::WebDriver.for :remote,";
                $content .= " :url => \"http://#{@setup['client']['value']}:4444/wd/hub\",";
                $content .= " :desired_capabilities => @setup['browser']['value'].to_sym\n";
                $content .= "        @driver.get @setup['host']['value']\n";
                $content .= "        @driver.manage.timeouts.implicit_wait = 30\n";
                $content .= "    end\n\n";
                $content .= "    def teardown\n        @driver.quit\n    end\n\n";
                $content .= "    def login\n";
                $content .= "        @driver.find_element(:name, \"form_loginname\").send_keys @setup['user']['value']\n";
                $content .= "        @driver.find_element(:name, \"form_pw\").send_keys @setup['password']['value']\n";
                $content .= "        @driver.find_element(:name, \"login\").click\n    end\n\n";
                $content .= "    def getdriver\n        @driver\n    end\n\n";
                $content .= "end\n\n";
                $testSuiteFileObj->fwrite($content);
            }
        } catch (RuntimeException $e) {
            // TODO: Handle errors otherwise
            echo $e->getMessage();
        }
    }

    /**
     * Store conf in the correponding testsuite
     *
     * @return Void
     */
    function storeTestSuiteDetails() {
        try {
            $testSuiteFileObj = $this->_testSuiteFile->openFile('a');
            if ($this->_testSuiteFile->isWritable()) {
                //Conf storage
                $setup = new Setup();
                $setup->storeConf($this->_testSuiteFile->getPathname());
                //Test Cases storage
                $content = "#--- Test Cases list ---\n";
                foreach ($this->_testCasesMap as $testCase) {
                    $testCasePathInfo = new SplFileInfo($testCase->filePath);
                    $testCasePath     = $testCasePathInfo->getRealPath();
                    $testCaseFinder   = substr($testCase->_testCaseFile, strlen($testCasePath) + 1);
                    $content          .= "# ".$testCaseFinder."\n";
                }
                $content .= "#--- Test Cases End ---\n\n";
                $testSuiteFileObj->fwrite($content);
            }
        } catch (RuntimeException $e) {
            // TODO: Handle errors otherwise
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
                $this->bindTestCases($fileObj);
            }
        } catch (RuntimeException $e) {
            // TODO: Handle errors otherwise
            echo $e->getMessage();
        }
    }

    /**
     * Returns the testCases attached to this testSuite
     * @TODO: Handle errors
     *
     * @return Array
     */
    public function getTestCases() {
        $inTests = false;
        $testCases = array();
        if ($this->_testSuiteFile->isReadable()) {
            $file    = $this->_testSuiteFile->openFile('r');
            while (!$file->eof()) {
                $line = $file->fgets();
                if ($inTests && $line == "#--- Test Cases End ---\n") {
                    $inTests = false;
                }
                if ($inTests) {
                    $testCases[]= substr(substr($line, 2), 0, -1);
                }
                if (!$inTests && $line == "#--- Test Cases list ---\n") {
                    $inTests = true;
                }
            }
        }
        return $testCases;
    }

    /**
     * Display setup of the testsuite and associated testcases
     * @TODO: Handle errors
     *
     * @return String
     */
    function displayDetails() {
        $inSetup = false;
        $content = "";
        if ($this->_testSuiteFile->isReadable()) {
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
        } else {
            $content = "Could not Read testsuite file";
        }
        return $content;
    }

}

?>
