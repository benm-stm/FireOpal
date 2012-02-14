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

    private $name;
    private $_testCases;
    private $_observers;
    private $_currentTestCase;
    private $_result;
    private $_testSuiteFile;

    /**
     *
     */
    public function __construct(array $testCases) {
        $this->name       = "fuubar";
        $this->_testCases = $testCases;
        $this->_observers = array();
        $this->_result    = array();
        //@TODO   Better prefer dependency injection here. use SplObjectStorage
        //$this->_observers = new SplObjectStorage();
    }

    /**
     * Launch test Suite
     *
     * @Deprecated
     */
    public function run() {
        #$rspecTestSuiteFile = $this->generateRspecTestSuite();
        #exec('rspec '.$rspecTestSuiteFile.' --format documentation --out '.$resultFile.' 2>&1', $this->_result);
    }

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
            $rspecFileObj->fwrite("describe ".$this->name." do\n");
            //$rspecFileObj->fwrite("    it ".$this->name." do\n");
        }
    }

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
     * @param  String $testSuiteName
     *
     **/
    function storeConfIntoTestSuite($request, $testSuiteName) {
        try {
            $this->_testSuiteFile = new SplFileInfo(dirname(__FILE__).'/../testsuites/'.$testSuiteName.'_'.time().'.rb');
            $testSuiteFileObj = $this->_testSuiteFile->openFile('a');
            if ($this->_testSuiteFile->isWritable()) {
                $testSuiteFileObj->fwrite("#--- Start Conf in setup here\n");
                $testSuiteFileObj->fwrite("#");
                $setupManager = new SetupManager();
                $setupManager->store($request, $this->_testSuiteFile->getPathname());
                $testSuiteFileObj->fwrite("#--- End Conf\n");
            }
        } catch (RuntimeException $e) {
            echo $e->getMessage();
        }
    }

    public function loadTestSuite() {
        try {
            $webDriverFile = new SplFileInfo(dirname(__FILE__).'/../testcases/fooBar.rb');
        } catch (RuntimeException $e) {
            echo $e->getMessage();
            // @TODO Specify here what i'm supposed to render if i'm not able to create the ruby file...
        }
        $webDriverFileObj = $webDriverFile->openFile('a');
        if ($webDriverFile->isWritable()) {
            $webDriverFileObj->fwrite("#--- Put Conf in setup here\n");
        }
        $this->bindTestSuiteRequirements($webDriverFileObj);
        $this->bindTestCases($webDriverFileObj);
        $webDriverFileObj->fwrite("    end\n");
    }

    public function generateWebDriverTestSuite($testCases = null) {
        try {
            $webDriverFile = new SplFileInfo(dirname(__FILE__).'/../testsuites/fooBar.rb');
        } catch (RuntimeException $e) {
            echo $e->getMessage();
            // @TODO Specify here what i'm supposed to render if i'm not able to create the ruby file...
        }
        $webDriverFileObj = $webDriverFile->openFile('a');
        if ($webDriverFile->isWritable()) {
            $webDriverFileObj->fwrite("#--- Put Conf in setup here\n");
        }
        foreach ($this->_testCases as $testCase) {
            try {
                $testCaseFileObj = new SplFileObject($testCase);
                while($testCaseFileObj->valid()) {
                    //May be you need a verbose echo $file->current().'<br />';
                    $webDriverFileObj->fwrite($testCaseFileObj->current());
                    $testCaseFileObj->next();
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
    }

    /**
     * Generate RSPEC testSuite from Webdriver testSuite
     *
     */
    public function generateRspecTestSuite($testCases = null) {
        try {
            $webDriverFile = new SplFileInfo(dirname(__FILE__).'/../testcases/tuleap.rb');

            $className = $webDriverFile->getBasename('.rb');
            try {
                $rspecFile = new SplFileInfo(dirname(__FILE__).'/../testsuites/'.$className.'_spec.rb');
                $file = $webDriverFile->__toString();

                #### Include & require
                $fileContent = "require \"rubygems\"\n";
                $fileContent .= "gem \"rspec\"\n";
                $fileContent .= "require \"rspec/autorun\"\n";
                $fileContent .= "require '".$className."'\n";

                #### Class definition turned on describe
                #### setup, teardown & login call
                $pattern = '/class (\w+)/i';
                $replacement = '
describe ${1} do
    before(:each) do
        @testClass = ${1}.new 
        @testClass.setup() 
        @testClass.login()
    end

    after(:each) do
        @testClass.teardown()
    end';

                $fileContent .= preg_replace($pattern, $replacement, $file);

                $pattern      ='/def setup\s(.*)end/isUe';
                $fileContent  = preg_replace($pattern, '"\n"', $fileContent);

                #### teardown
                $pattern     ='/def teardown\s(.*)\send/isUe';
                $fileContent = preg_replace($pattern, '"\n"', $fileContent);

                #### login
                $pattern     ='/def login\s(.*)\send/isUe';
                $fileContent = preg_replace($pattern, '"\n"', $fileContent);

                $pattern     ='/def (\w+)\s(.*)\send/isUe';
                $replacement = '"
    describe \"test case 1\" do
        it \"should $1\" do
            @testClass.$1()
        end
    end"';
                $fileContent = preg_replace($pattern, $replacement, $fileContent);

                $fileContent = preg_replace('/\s+\n/', "\n", $fileContent);

                $rspecFileObj = $webDriverFile->openFile('a');
                $rspecFileObj->fwrite($fileContent);
            } catch (RuntimeException $e) {
                echo $e->getMessage();
            }
        } catch (RuntimeException $e) {
            echo $e->getMessage();
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

}

?>

