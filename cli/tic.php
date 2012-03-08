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
$function    = '';
$parameters  = array();

for ($i = 1; $i < $argc; $i++) {
    if ($argv[$i] == "--help" || $argv[$i] == "-h") {
        $displayHelp = true;
    } elseif (!preg_match("/^-/", $argv[$i])) {
        // Not a parameter (does not start with "-") Then, it must be a name of a function
        if (empty($function)) {
            // Once function name is there it couldn't be replaced by another one
            $function = $argv[$i];
        }
    } elseif (preg_match("/^-/", $argv[$i])) {
        // A parameter for the given function
        $param = split("=", $argv[$i]);
        if (!isset($param[1])) {
            $param[1] = false;
        }
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
                echo "setup: Display setup.\nParameters:\n    --help or -h: Display this help\n";
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
                echo "testsuites: Display testsuites.\nParameters:\n    --help or -h: Display this help\n";
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
                echo "testsuite: Display testsuite details.\nParameters:\n    --testsuite : Name of the testsuite to display\n    --help or -h: Display this help\n";
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
                echo "testcases: Display testcases.\nParameters:\n    --numbered  : Display testcases indexed by a number\n    --help or -h: Display this help\n";
            } else {
                $output          = '';
                $testCaseManager = new TestCaseManager();
                $testcases       = $testCaseManager->listFileSystem("../testcases");
                foreach ($testcases as $index => $testcase) {
                    if (isset($parameters["numbered"])) {
                        $output .= " ".$index."-";
                    }
                    $output .= " ".$testcase."\n";
                }
                echo $output;
            }
            break;
        case 'generate' :
            if ($displayHelp) {
                echo "generate: Generate a testsuite.\nParameters:\n    --name          : Name of the new testsuite\n    --old_testsuite : Name of an old testsuite from which we import the list of testcases\n    --testcases     : List of indexes of testcases as obtained from \"testcases\" function\n    --help or -h    : Display this help\nNB: You can't use both --old_testsuite and --testcases\n";
            } else {
                if (isset($parameters["name"]) && !empty($parameters["name"])) {
                    if (isset($parameters["old_testsuite"]) && !empty($parameters["testcases"]) && !isset($parameters["testcases"])) {
                        $oldTestSuite = new TestSuite($parameters["old_testsuite"]);
                        $testCases    = $oldTestSuite->getTestCases();
                    } elseif (isset($parameters["testcases"]) && !empty($parameters["testcases"]) && !isset($parameters["old_testsuite"])) {
                        $testCasesNumbers = split(",", $parameters["testcases"]);
                        $testCaseManager  = new TestCaseManager();
                        $testCasesList    = $testCaseManager->listFileSystem("../testcases");
                        $testCases        = array();
                        foreach ($testCasesNumbers as $number) {
                            if (isset($testCasesList[$number])) {
                                $testCases[] = $testCasesList[$number];
                            } else {
                                echo "\"".$number."\" is not a valid testcase index, to verify your input, try\n>php tic.php testcases --numbered\n";
                            }
                        }
                    } else {
                        echo "You need to use --old_testsuite or --testcases parameters to pass list of testcases, you can't use both\n";
                    }
                    if (isset($testCases) && !empty($testCases)) {
                        $testSuite        = new TestSuite($parameters["name"]);
                        $testSuiteManager = new TestSuiteManager();
                        $populateResult = $testSuiteManager->populateTestSuite($testSuite, $testCases);
                        foreach ($populateResult['info'] as $message) {
                            echo "Info: ".$message."\n";
                        }
                        foreach ($populateResult['error'] as $message) {
                            echo "Error: ".$message."\n";
                        }
                        $testSuite->storeTestSuiteDetails();
                        $testSuite->bindConfigurationElements();
                        $testSuite->loadTestSuite();
                        echo "Testsuite \"".$parameters["name"]."\" stored\n";
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
    echo "TIC \"TIC is not CLI\"\nFunctions:    \n    * setup      : Display setup.\n    * testsuites : Display testsuites.\n    * testsuite  : Display testsuite details.\n    * testcases  : Display testcases.\n    * generate   : Generate a testsuite.\nTo display help about each function:\n> php tic.php <function_name> [--help|-h]\n";
}

?>
