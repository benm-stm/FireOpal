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


$output = '';
if (isset($_REQUEST['testcases_to_add'])) {
    if (!empty($_REQUEST['testsuite_name'])) {
        require_once dirname(__FILE__).'/../include/TestSuite.class.php';
        require_once dirname(__FILE__).'/../include/TestCase.class.php';
        // manage request
        $files = $testCaseManager->prepare_files($_REQUEST['testcases_to_add'], dirname(__FILE__).'/../testcases');
        if (!empty($files)) {
            $testSuite = new TestSuite($_REQUEST['testsuite_name']);
            $testSuiteManager->populateTestSuite($testSuite, $files);
            $testSuite->storeTestSuiteDetails($_REQUEST);
            $testSuite->bindConfigurationElements($_REQUEST);
            $testSuite->loadTestSuite();
            $output = "Testsuite stored";
        } else {
            $output = "No testcases selected";
        }
    } else {
        $output = "Empty name";
    }
}

if (isset($_REQUEST['load_testsuites'])) {
 
}

if (isset($_REQUEST['delete_testsuites'])) {
    if ($testSuiteManager->delete($_REQUEST['delete_testsuites'])) {
        $output = "Testsuite deleted";
    } else {
        $output = "Tetsuite not deleted";
    }
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
        <div id="header">
            <p>
                <font color="red">'.$output.'</font>
            </p>
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
                        <form name="EditTestSuiteForm" action="" method="POST">
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
    echo '
                                </table>
                            </fieldset>
                            <div id="submit_panel">
                                <input type="button" name="load" value="Load" onclick="loadTestCases(this.form,this.form.testSuite,this.form.testCases);">
                            </div>';
}

echo '
                            <fieldset>
                                <legend><b>Testcases</b></legend>
                                <table>
                                    <tr>
                                        <td align="center"><B><FONT size="2">Availables test cases</FONT></B>';
$testCaseManager->displayFileSystem("../testcases");
echo '
                                        </td>

                                        <td style="vertical-align:middle">
                                            <input type="button" value="Add >>>" onClick="AddtestCases(this.form.testCases,this.form.testSuite)">
                                        </td>

                                        <td align="center"><FONT size="2"><B>Dispatched test cases</B></FONT>
                                            <select align=top name="testSuite" size=6 style="width:220px">
                                                <option value="10">----------------------</option>
                                            </select>
                                        </td>
                                        <td style="vertical-align:middle">
                                            <input type="button" value="Remove >>>" onClick="RemoveTestCase(this.form.testSuite,this.form.testCases)">
                                        </td>
                                    </tr>
                                </table>

                                <SCRIPT language="javascript">
                                    document.EditTestSuiteForm.testSuite.options.length=0;
                                </SCRIPT>

                                <table nowrap>
                                    <tr>
                                        <td>Name:</td>
                                        <td><input name="testsuite_name"/></td>
                                    </tr>
                                    <tr>
                                        <td>Description:</td>
                                        <td><textarea name="testsuite_description"></textarea></td>
                                    </tr>
                                </table>
                            </fieldset>
                            <div id="submit_panel">
                                <input id="generate" type="submit" value="Generate !"/>
                            </div>
                        </form>
                    </td>';

if (!empty($testsuites)) {
    echo '
                    <td id="block_delete">
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
                        </form>
                    </td>';
}

echo '
                </tr>
            </table>';

echo '
    </body>
    <script type="text/javascript">
    //<!--
    var testcases_to_add = {';
foreach($tests as $c => $t) {
    $testCaseManager->display_tests_as_javascript($t, $c, array('is_cat' => true));
}
echo '
    };
    //-->
    </script>
</html>';
