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

class TestSuiteManager {

    private $info  = array();
    private $error = array();
    protected $logger;

    public static $testSuitesLocation = "testsuites";

    /**
     * Set logger object for testSuiteManager
     * @TODO May be you should add a class constructor and inject there logger object dependency?
     *
     * @param LogManager $logger Injected LogManager instance
     *
     * @return Void
     */
    public function setLogger($logger) {
        $this->logger = $logger;
  }

    /**
     * Retrieves test suites location
     *
     * @return String
     */
    public function getTestSuitesLocation() {
        return dirname(__DIR__).DIRECTORY_SEPARATOR.self::$testSuitesLocation.DIRECTORY_SEPARATOR;
    }

    /**
     * Search testsuite files
     *
     * @return Array
     */
    function searchTestsuites() {
        $testsuites = array();
        $dir = $this->getTestSuitesLocation();
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if (!in_array($file, array('.', '..'))) {
                        if (!is_dir("$dir/$file")) {
                            $testSuite = new TestSuite(substr($file, 0, -3));
                            $testCases = $testSuite->getTestCases();
                            $testCasesStr = implode(',', $testCases);
                            $testsuites[$file] = $testCasesStr;
                        }
                    }
                }
                closedir($dh);
            }
        }
        return $testsuites;
    }

    /**
     * Delete testsuites
     *
     * @param Array $testsuites Testsuites to delete
     *
     * @return Boolean
     */
    function delete($testsuites) {
        $deleteStatus = true;
        $testSuitesPath = $this->getTestSuitesLocation();
        foreach ($testsuites as $testsuite) {
            $deleteStatus = unlink($testSuitesPath.$testsuite) && $deleteStatus;
        }
        return $deleteStatus;
    }

    /**
     * Populate the collection of test case objects of a given test Suite
     * from an array that contains test cased path
     *
     * @param TestSuite $testSuite     Target test suite to populate
     * @param Array     $testCaseArray List of testcases to attach to the testsuite
     * @param Boolean   $isDependency  Is the testcases added by the user or added as dependency
     *
     * @return Array
     */
    function populateTestSuite($testSuite, $testCasesArray, $isDependency = false) {
        foreach ($testCasesArray as $test) {
            try {
                $testCase = new TestCase(substr($test, 0, -3));
                if ($testCase->checkTags()) {
                    $missingParams = $testCase->checkSetupParams();
                    if (!empty($missingParams)) {
                        $this->error[] = 'Testcase "'.$test.'" requeires missing conf param(s): '.join(', ', $missingParams);
                    }
                    $dependencies = $testCase->getDependencies();
                    try {
                        $this->attachDependencies($testSuite, $dependencies, $test);
                    } catch (OutOfRangeException $exception) {
                        $this->error[] = $exception->getMessage();
                    }
                    $testSuite->attach($testCase, $isDependency);
                } else {
                    $this->error[] = 'Testcase "'.$test.'" were removed due to an incompatible tag';
                }
            } catch (RuntimeException $e) {
                $this->logger->LogWarning($e->getMessage());
                $this->error[] = $e->getMessage();
            }
        }
        return array("info" => $this->info, "error" => $this->error);
    }

    /**
     * Recursively attach dependencies of a testcases
     *
     * @param TestSuite $testSuite    Target test suite to populate
     * @param Array     $dependencies Array of testcase dependencies to attach
     * @param String    $entryPoint   Name of the testCase originating the dependency
     *
     * @return Void
     */
    function attachDependencies($testSuite, $dependencies, $entryPoint) {
        foreach($dependencies as $dependency) {
            if (!$testSuite->isAttached(substr($dependency, 0, -3))) {
                if (strcmp($entryPoint, $dependency) !== 0) {
                    $this->populateTestSuite($testSuite, array($dependency), true);
                    $this->info[] = 'Testcase "'.$dependency.'" were added as dependency of "'.$entryPoint.'"';
                } else {
                    throw new OutOfRangeException('An error occured while applying dependencies: Test cases "'.$entryPoint.'" and "'.$dependency.'" dependencies may lead to a deadlock situation.');
                }
            }
        }
    }

}

?>