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

ini_set('display_errors', 'on');
ini_set('max_execution_time', 0);
ini_set('memory_limit', -1);
ini_set('include_path', ini_get('include_path').PATH_SEPARATOR.dirname(__DIR__).DIRECTORY_SEPARATOR.'include');
require_once('TestSuite.class.php');
require_once('TestSuiteManager.class.php');
require_once('common/User.class.php');

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Connection: Keep-Alive");
header("Keep-Alive: timeout=300");

if (!isset($_SESSION)) {
    session_start();
}

echo '
<html>
    <head>
        <title>FireOpal</title>
        <link href="include/css/index.css" rel="stylesheet" type="text/css" />
        <link href="include/css/sign.css" rel="stylesheet" type="text/css" />
';

$welcomeMessage = '';
$sign           = '';
$Myitems        = '';
$connectedUser  = null;

if(!isset($_SESSION['sess_idUser'])) {
    $sign    = '<a href="sign.php" class="greenLink">Sign In</a> ';
    $Myitems = '<span class="signLink">|</span> <a href="http://codex.cro.st.com/" class="greenLink">Codex</a>  ';
} else {
    $connectedUser = new user();
    $connectedUser->loadFromId($_SESSION['sess_idUser']);
    $welcomeMessage = '<font color="#928A6E" >Welcome '.$connectedUser->getAtt('surname').' '.strtoupper($connectedUser->getAtt('familyName')).'</font>';
    $sign           = '<span class="signLink" >|</span> <a href="logout.php" class="greenLink">Sign Out</a> ';
}
echo '
    <div id="sign">
        <div id="sepcoin"></div>
        <div id="bloctop">
            <div id="right">
                <div id="bloctext1" style="width:500px;" align="right" >
                    <font class="welcomeLink">'.$welcomeMessage.'</font>
                    '.$sign.'
                    '.$Myitems.'
                    <span class="signLink">|</span> <a href="http://rspec.info/" class="signLink">RSpec</a>
                    <span class="signLink">|</span> <a href="http://seleniumhq.org/docs/03_webdriver.html" class="signLink">Selenium WebDriver Documentation</a>
                </div>
            </div>
        </div>
    </div>
';

echo '
        <script>
            function displayRunButton() {
                document.getElementById("submit_panel").innerHTML = \'<input type="button" name="button" value="Run !" onclick="formSubmit()" class="runButton"/>\';
            }
            function formSubmit() {
                document.getElementById("run").submit();
            }
        </script>
    </head>
    <body onLoad="displayRunButton()">
        <div id="header">
            <a href="cases.php" class="community">Manage testsuites</a>
            <a href="result.php" class="community">View execution results</a>
        </div>
        <div id="body_skin">
            <table width="100%">
                <tr>
                    <td width="10%" nowrap="nowrap">
                        <form id="run" action="" method="POST">
                            <div id="submit_panel">
                                <noscript><input type="submit" value="Run !" /></noscript>
                            </div>
                            <fieldset class="fieldset">
                                <legend><span class="fieldsetTitle">Testsuites</span></legend>
                                <ul id="menu">';

$testSuiteManager = new TestSuiteManager();
$testsuites = $testSuiteManager->searchTestsuites();
foreach($testsuites as $t => $cases) {
    echo '
                                    <li>
                                        <input type="radio" name="run" value="'.$t.'" />'.$t.'
                                        <a href="index.php?details='.$t.'"> Details</a>
                                    </li>';
}
echo '
                                </ul>
                            </fieldset>
                        </form>';

if (isset($_REQUEST['details'])) {
    echo '
                        <fieldset class="fieldset">
                            <legend><b>'.$_REQUEST['details'].' details</b></legend>
                            <pre>';
    $testSuite = new TestSuite(substr($_REQUEST['details'], 0, -3));
    echo $testSuite->displayDetails();
    echo '
                            </pre>
                        </fieldset>';
}
echo '
                    </td>
                    <td width="90%">
                        <fieldset class="fieldset">
                            <legend><span class="fieldsetTitle">Results</span></legend>
                            <pre>';
if (isset($_REQUEST['run'])) {
    // manage request
    $testSuite = new TestSuite(substr($_REQUEST['run'], 0, -3), $connectedUser);
    $result = $testSuite->run();
    if ($result) {
        header("Location: result.php");
    } else {
        echo "TestSuite not run\nYou must be logged in to run a testsuite";
    }
}
echo '
                            </pre>
                        </fieldset>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>';
?>