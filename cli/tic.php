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

require_once '../include/Setup.class.php';
require_once '../include/TestSuite.class.php';
require_once '../include/TestSuiteManager.class.php';
require_once '../include/TestCase.class.php';
require_once '../include/TestCaseManager.class.php';

$displayHelp = false;
$verbose     = false;
$function    = '';
$parameters  = array();

for ($i = 1; $i < $argc; $i++) {
    if ($argv[$i] == "--help" || $argv[$i] == "-h") {
        $displayHelp = true;
    } elseif ($argv[$i] == "--verbose" || $argv[$i] == "-v") {
        $verbose = true;
    } elseif (!preg_match("/^-/", $argv[$i])) {
        // Not a parameter (does not start with "-") Then, it must be a name of a function
        $function = $argv[$i];
    } elseif (preg_match("/^-/", $argv[$i])) {
        // A parameter for the given function
        $param = split("=", $argv[$i]);
        $parameters[str_replace("-", "", $param[0])] = $param[1];
    } else {
        // Unknown parameter
        exit("Unknown parameter: \"".$argv[$i]."\"\n");
    }
}

if (!empty($function)) {
    switch ($function) {
        case 'setup' :
            if ($displayHelp) {
                echo "Display setup.\n";
            } else {
                $setup = new Setup();
                $set   = $setup->load();
                foreach ($set as $name => $entry) {
                    if ($entry['type'] == 'password') {
                        echo " * ".$name.": ******** (".$entry['description'].")\n";
                    } else {
                        echo " * ".$name.": ".$entry['value']." (".$entry['description'].")\n";
                    }
                }
            }
            break;
        case 'testsuites' :
            if ($displayHelp) {
                echo "Display testsuites.\n";
            } else {
                $testSuiteManager = new TestSuiteManager();
                $testsuites       = $testSuiteManager->searchTestsuites();
                foreach ($testsuites as $testsuite => $testcases) {
                    echo " * ".str_replace(".rb", "", $testsuite).": \"".$testcases."\"\n";
                }
            }
            break;
        case 'testsuite' :
            if ($displayHelp) {
                echo "Display testsuite details.\n";
            } else {
                if (isset($parameters["testsuite"])) {
                    $testSuite = new TestSuite($parameters["testsuite"]);
                    echo $testSuite->displayDetails()."\n";
                } else {
                    echo "--testsuite parameter is mandatory\n";
                }
            }
            break;
        case 'testcases' :
            if ($displayHelp) {
                echo "Display testcases.\n";
            } else {
                $testCaseManager = new TestCaseManager();
                echo $testCaseManager->displayFileSystem("../testcases");
            }
            break;
        case 'generate' :
            if ($displayHelp) {
                echo "Generate a testsuite.\n";
            } else {
                if (isset($parameters["name"])) {
                    if (isset($parameters["old_testsuite"])) {
                        $oldTestSuite = new TestSuite($parameters["old_testsuite"]);
                        $testCases    = $oldTestSuite->getTestCases();
                        echo "Testsuite \"".$parameters["name"]."\" stored\n";
                    } elseif (isset($parameters["testcases"])) {
                        $testCases = split(",", $parameters["testcases"]);
                    } else {
                        echo "You need to use --old_testsuite or --testcases parameters to pass list of testcases\n";
                    }
                    if (isset($testCases) && !empty($testCases)) {
                        $testSuite        = new TestSuite($parameters["name"]);
                        $testSuiteManager = new TestSuiteManager();
                        $testSuiteManager->populateTestSuite($testSuite, $testCases);
                        $testSuite->storeTestSuiteDetails();
                        $testSuite->bindConfigurationElements();
                        $testSuite->loadTestSuite();
                    } else {
                        echo "No testcases to add\n";
                    }
                } else {
                    echo "--name parameter is mandatory\n";
                }
            }
            break;
        default :
            echo "Unknown function\n";
            break;
    }
} else {
    echo "TIC \"TIC is not CLI\"\nFunctions:
    * setup      : Display setup.
    * testsuites : Display testsuites.
    * testsuite  : Display testsuite details.
    * testcases  : Display testcases.
    * generate   : Generate a testsuite.
    \n";
}

?>
