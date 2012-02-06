<?php
/**
 * Copyright (c) STMicroelectronics 2011. All rights reserved
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

/**
 * Class that launches selected tests
 */
class IntegrationTests {

    var $files = array();

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

    /**
     * Run the test suite then close the browser
     *
     * @params PHPUnit_Framework_TestResult $result
     *
     * @return Array
     */
    function run() {
        foreach ($this->files as $file) {
            $result[] = $file;
            exec('ruby '.$file.' 2>&1', $result);
        }
        return $result;
    }

    public function runTestSuite() {
        $testCasesLauncher = new testCasesLauncher();
        $testSuite = new testSuite($this->files);
        $testSuite->attach($testCasesLauncher);
        $testSuite->runTestCases();
    }
}

?>
