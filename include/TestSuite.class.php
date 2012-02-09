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
 
class testSuite implements SplSubject {

    private $_testCases;
    private $_observers;
    private $_currentTestCase;
    private $_result;
    private $_webDriverTestSuiteFile;

    public function __construct(array $testCases) {
        $this->_testCases = $testCases;
        $this->_observers = array();
        $this->_result   = array();
        //@TODO   Better prefer dependency injection here. use SplObjectStorage
        //$this->_observers = new SplObjectStorage();
    }

     /**
     * Launch test Suite
     *
     * @Deprecated
     */
    public function run() {
         $this->generateWebDriverTestSuite($this->_testCases);
         $rspecTestSuiteFile = $this->generateRspecTestSuite($this->_testCases);
         exec('rspec '.$rspecTestSuiteFile.' --format documentation --out '.$resultFile.' 2>&1', $this->_result);
    }

    public function generateWebDriverTestSuite($testCases = null) {
        try {
            $webDriverFile = new SplFileInfo(dirname(__FILE__).'/../tests/fooBar.rb');
        } catch (RuntimeException $e) {
            echo $e;
            // @TODO Specify here what i'm supposed to render if i'm not able to create the ruby file...
        }        
        $fileobj = $webDriverFile->openFile('a');
        if ($webDriverFile->isWritable()) {
           $fileobj->fwrite("require 'selenium-webdriver'");
        }
    }
    
   /**
         * Launch test Cases...
         * @Deprecated	 
         */
    public function runTestCases() {
        $this->generateWebDriverTestSuite();
        foreach ($this->_testCases as $testCase) {
            $this->_currentTestCase = $testCase;
            //@TODO update here
			$this->_result = $testCase;		
			exec('ruby '.$testCase.' 2>&1', $this->_result);		
            $this->notify();
        }
        $this->_currentTestCase = null;
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

    function addTestFile($path) {
        $this->files[] = $path;
    }

} 
 ?>
