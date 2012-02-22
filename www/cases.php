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

ini_set('display_errors', 'on');
ini_set('max_execution_time', 0);
ini_set('memory_limit', -1);
ini_set('include_path', ini_get('include_path').':'.dirname(__FILE__).'/../include/');

require_once 'Setup.class.php';
require_once 'TestSuite.class.php';
require_once 'TestSuiteManager.class.php';
require_once 'TestCaseManager.class.php';
$testSuiteManager = new TestSuiteManager();
$testCaseManager = new TestCaseManager();

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


$output   = '';
$messages = array();
if (isset($_REQUEST['testcases_to_add'])) {
    $testCasesToAdd = explode(',', $_REQUEST['testcases_to_add']);
    if (!empty($_REQUEST['testsuite_name'])) {
        require_once dirname(__FILE__).'/../include/TestSuite.class.php';
        require_once dirname(__FILE__).'/../include/TestCase.class.php';
        // manage request
        $files = $testCaseManager->prepare_files($testCasesToAdd, dirname(__FILE__).'/../testcases');
        if (!empty($files)) {
            $testSuite = new TestSuite($_REQUEST['testsuite_name']);
            $testSuiteManager->populateTestSuite($testSuite, $testCasesToAdd);
            $testSuite->storeTestSuiteDetails($_REQUEST);
            $testSuite->bindConfigurationElements($_REQUEST);
            $testSuite->loadTestSuite();
            $messages[] = "Testsuite stored";
        } else {
            $messages[] = "No testcases selected";
        }
    } else {
        $messages[] = "Empty name";
    }
}


if (isset($_REQUEST['delete_testsuites'])) {
    if ($testSuiteManager->delete($_REQUEST['delete_testsuites'])) {
        $messages[] = "Testsuite deleted";
    } else {
        $messages[] = "Tetsuite not deleted";
    }
}


if (isset($_REQUEST['load_testsuites'])) {
    $testSuite = new TestSuite(substr($_REQUEST['load_testsuites'], 0, -3));
    $testCases = $testSuite->getTestCases();
    $testCases_str = implode(',' , $testCases);


if (!empty($messages)) {
    $output   = '<ul class="feedback_info" >';
    foreach ($messages as $message) {
        $output .= "<li>".$message."</li>";
    }
    $output .= '</ul>';

}

echo '
<html>
    <head>
        <title>Codex automatic validation</title>
        <link href="include/css/index.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="scripts/prototype/prototype.js"></script>
        <script type="text/javascript" src="scripts/scriptaculous/scriptaculous.js"></script>
        <script type="text/javascript" src="scripts/tree.js"></script>
    </head>
    <body>
        <div id="header">';
echo $output;
echo '
            <a href="/" class="community"><< Go back</a>
            <a href="set" class="community">Update config</a>
        </div>
        <div id="body_skin">
            <table>
                <tr>
                    <td id="block_config">
                        <fieldset>
                            <legend><b>Config</b></legend>
                            <ul id="menu"><li class="">';
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
                        <form action="" method="POST">
                            <fieldset>
                                <legend><b>Delete testsuites</b></legend>
                                <table nowrap>';
    foreach($testsuites as $t) {
        echo '
                                    <tr>
                                        <td>'.$t.'</td>
                                        <td><input type="checkbox" name="delete_testsuites[]" value="'.$t.'" /></td>
                                    </tr>';
    }
    echo '
                                </table>
                            </fieldset>
                            <div id="submit_panel"><input type="submit" value="Delete !" /></div>
                        </form>';
}

if (!empty($testsuites)) {
    echo '
                        <form name="EditTestSuiteForm" action="" method="POST" onSubmit = "generateTestSuite(testcases_to_add)">
                            <fieldset>
                                <legend><b>Load testsuites</b></legend>
                                <table nowrap>';
    foreach($testsuites as $t) {
        echo '
                                    <tr>
                                        <td>'.$t.'</td>
                                        <td><input type="radio" name="load_testsuites" value="'.$t.'" /></td>
                                    </tr>';
    }
    echo '                                </table>
                            </fieldset>';

    echo '

                                <div id="submit_panel">';

    echo '  
                                           <input type="hidden" name="testcases_loaded" id="testcases_loaded" value="'.$testCases_str.'">
                                           <input type="Submit" name="load" value="Load" onClick="loadTestCases( this.form, this.form.testcases_to_add)">';
    echo '

                            
                                </div>';
}
echo '
                            <fieldset>
                                <legend><b>Testcases</b></legend>
                                <table>
                                    <tr>
                                        <td align="center"><b><font size="2">Availables test cases</font></b>';
$testCaseManager->displayFileSystem("../testcases");
echo '
                                        </td>

                                        <td style="vertical-align:middle">
                                            <input type="button" value="Add >>>" onClick="AddtestCases(this.form.testCases,this.form.testcases_to_add)">
                                        </td>

                                        <td align="center"><font size="2"><b>Dispatched test cases</b></font>
                                            <select align=top id="testcases_to_add" name="testcases_to_add" size=10  style="width:320px" multiple="multiple">
                                                <option value="10">----------------------</option>
                                            </select>
                                        </td>
                                        <td style="vertical-align:middle">
                                            <input type="button" value="Remove >>>" onClick="RemoveTestCase(this.form.testcases_to_add,this.form.testCases)">
                                        </td>
                                    </tr>
                                </table>

                                <script language="javascript">
                                    document.EditTestSuiteForm.testcases_to_add.options.length=0;
                                </script>';
echo '                          <script type="text/javascript">
                                    function generateTestSuite(testcases_to_add) {
                                        var p = document.getElementById(\'testcases_to_add\');
                                        var testCasesString = "";
                                        for(testCase=0;testCase<p.length;testCase++) {
                                            if(testCase+1 == p.length) {
                                                testCasesString += p[testCase].value;
                                            } else {
                                                testCasesString += p[testCase].value+",";
                                            }
                                        }
                                        alert(testCasesString);
                                        d = document.getElementById("submit_panel_1");';
echo "                                      d.innerHTML = '<input type=\"text\" id=\"testcases_to_add\" name=\"testcases_to_add\" value=\"' + testCasesString + '\" />';
                                    }
                                </script>";
echo '                          <table nowrap>
                                    <tr>
                                        <td>Name:</td>
                                        <td><input name="testsuite_name"/></td>
                                    </tr>
                                    <tr>
                                        <td>Description:</td>
                                        <td><textarea name="testsuite_description"></textarea></td>
                                    </tr>
                                </table>
                            </fieldset>';
echo '                      <div id="submit_panel_1"> </div><div id="submit_panel">
                                <input id="generate" type="submit" value="Generate !" />
                            </div>
                        </form>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>';
