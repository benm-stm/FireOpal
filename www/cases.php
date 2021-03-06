<?php
/**
 * Copyright (c) STMicroelectronics 2012. All rights reserved
 *
 * FireOpal is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * FireOpal is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with FireOpal. If not, see <http://www.gnu.org/licenses/>.
 */

// TODO: Kill every one that breaks HTML indentation

ini_set('display_errors', 'on');
ini_set('max_execution_time', 0);
ini_set('memory_limit', -1);
ini_set('include_path', ini_get('include_path').PATH_SEPARATOR.dirname(__DIR__).DIRECTORY_SEPARATOR.'include');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once 'Setup.class.php';
require_once 'TestSuite.class.php';
require_once 'TestSuiteManager.class.php';
require_once 'TestCaseManager.class.php';
require_once 'LogManager.class.php';
$logger = new LogManager( "../log/log.txt", LogManager::DEBUG);
$testSuiteManager = new TestSuiteManager();
$testSuiteManager->setLogger($logger);
$testCaseManager = new TestCaseManager();

$output = '';
$logViewer = '';
$info   = array();
$error  = array();
if (isset($_REQUEST['testcases_to_add'])) {
    if (!empty($_REQUEST['testcases_to_add'])) {
        $testCasesToAdd = explode(',', $_REQUEST['testcases_to_add']);
        if (!empty($_REQUEST['testsuite_name'])) {
            require_once dirname(__FILE__).'/../include/TestSuite.class.php';
            require_once dirname(__FILE__).'/../include/TestCase.class.php';
            try {
                $testSuite = new TestSuite($_REQUEST['testsuite_name']);
                try {
                    $populateResult = $testSuiteManager->populateTestSuite($testSuite, $testCasesToAdd);
                    $info           = $populateResult['info'];
                    $error          = $populateResult['error'];
                    $testSuite->storeTestSuiteDetails();
                    $testSuite->bindConfigurationElements();
                    $testSuite->loadTestSuite();
                } catch (RuntimeException $e) {
                    $logger->LogWarning($e->getMessage());
                    echo $e->getMessage();
                }
            } catch (InvalidArgumentException $e) {
                $logViewer .= $logger->LogWarning($e->getMessage(), LogManager::HTML);
                $logger->LogError($e->getTraceAsString());
            }
            $logViewer .= $logger->LogInfo("Testsuite \"".$_REQUEST['testsuite_name']."\" stored", LogManager::HTML);
        } else {
            $error[] = "Empty name";
        }
    } else {
        $error[] = "No testcases selected";
    }
}

if (isset($_REQUEST['delete_testsuites'])) {
    if ($testSuiteManager->delete($_REQUEST['delete_testsuites'])) {
        $info[] = "Testsuite(s) deleted";
    } else {
        $error[] = "Tetsuite(s) not deleted";
    }
}
 
 
if (isset($_REQUEST['load_testsuites'])) {
    $testSuite    = new TestSuite(substr($_REQUEST['load_testsuites'], 0, -3));
    $testCases    = $testSuite->getTestCases();
    $testCasesStr = implode(',' , $testCases);
   echo  $testCasesStr;
}


if (!empty($info)) {
    $output = '<ul class="feedback_info" >';
    foreach ($info as $message) {
        $output .= "<li>".$message."</li>";
    }
    $output .= '</ul>';
}

if (!empty($error)) {
    $output .= '<ul class="feedback_error" >';
    foreach ($error as $message) {
        $output .= "<li>".$message."</li>";
    }
    $output .= '</ul>';
}

echo '
<html>
    <head>
        <title>Fire Opal</title>
        <link href="include/css/index.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="include/scripts/script.js"></script>
    </head>
    <body>
        <div id="header">';
echo $output;
echo $logViewer;
echo '
            <a href="index.php" class="community"><< Go back</a>
            <a href="set.php" class="community">Update config</a>
        </div>
        <div id="body_skin">
            <table>
                <tr>
                    <td id="block_config">
                        <fieldset class="fieldset">
                            <legend class="toggler" onclick="toggle_visibility(\'menu\')">
                                <b>Config</b></legend>
                            <ul id="menu">';
$setup = new Setup();
$content = $setup->display(true);
echo $content['form'];
echo '
                            </ul> 
                        </fieldset>
                    </td>
                    <td id="block_load">';
 
$testsuites = $testSuiteManager->searchTestsuites();

if (!empty($testsuites)) {
    echo '
                        <form name="LoadTestSuiteForm" action="" method="POST">
                            <fieldset class="fieldset">
                                <legend class="toggler" onclick="toggle_visibility(\'load_container\')"><b>Load testsuite</b></legend>
                                <table id="load_container" nowrap>';
    foreach($testsuites as $testsuite => $testcases) {
        echo '
                                    <tr>
                                        <td>'.$testsuite.'</td>
                                        <td><input type="radio" name="load_testsuites" value="'.$testcases.'" /></td>
                                    </tr>';
    }

    echo '
                                </table>
                            </fieldset>
                            <div id="submit_panel">
                                <input type="button" name="load" value="Load !" onclick="loadTestCases()" class="casesButton">
                            </div>
                        </form>';
}
echo '
                        <form name="EditTestSuiteForm" action="" method="POST" onSubmit="generateTestSuite()">
                            <fieldset class="fieldset">
                                <legend class="toggler" onclick="toggle_visibility(\'testsuite_container\')"><b>New testsuite</b></legend>
                                <table id="testsuite_container">
                                    <tr>
                                        <td align="center"><b><span class="greenLink">Availables test cases</span></b>';
echo $testCaseManager->displayFileSystem("../testcases");
echo '
                                        </td>

                                        <td style="vertical-align:middle">
                                            <input type="button" value="Add >>>" onClick="AddtestCases()"  class="testSuitesButton">
                                        </td>

                                        <td align="center"><b><span class="greenLink">Dispatched test cases</span></b></font>
                                            <select align=top id="testcases_to_add" name="testcases_to_add" size=10  style="width:320px" multiple="multiple">
                                                <option value="10">----------------------</option>
                                            </select>
                                        </td>
                                        <td style="vertical-align:middle">
                                            <input type="button" value="Remove >>>" onClick="RemoveTestCase()"  class="testSuitesButton">
                                        </td>
                                    </tr>
                                </table>
                                <script language="javascript">
                                    document.EditTestSuiteForm.testcases_to_add.options.length = 0;
                                </script>
                                <table nowrap>
                                <tr><td>&nbsp;</td></tr>
                                    <tr>
                                        <td class="greenLink"><b>Testsuite name:</b></td>
                                        <td><input name="testsuite_name" type="text" class="textField"/></td>
                                    </tr>
                                    <tr>
                                        <!-- For the moment we don\'t use the description
                                        <td>Description:</td>
                                        <td><textarea name="testsuite_description"></textarea></td>
                                        -->
                                    </tr>
                                </table>
                            </fieldset>
                            <div id="submit_panel_1"> </div><div id="submit_panel">
                                <input id="generate" type="submit" value="Generate !" class="casesButton"/>
                            </div>
                        </form>';

if (!empty($testsuites)) {
    echo '
                        <form action="" method="POST">
                            <fieldset class="fieldset">
                                <legend class="toggler" onclick="toggle_visibility(\'delete_container\')"><b>Delete testsuites</b></legend>
                                <table id="delete_container" nowrap>';
    foreach($testsuites as $testsuite => $testcases) {
        echo '
                                    <tr>
                                        <td>'.$testsuite.'</td>
                                        <td><input type="checkbox" name="delete_testsuites[]" value="'.$testsuite.'" /></td>
                                    </tr>';
    }
    echo '
                                </table>
                            </fieldset>
                            <div id="submit_panel"><input type="submit" value="Delete !" class="casesButton"/></div>
                        </form>';
}

echo '
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>';

?>