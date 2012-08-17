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

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

echo '
<html>
    <head>
        <title>Codex automatic validation</title>
        <link href="include/css/index.css" rel="stylesheet" type="text/css" />
';

$welcomeMessage = '';
$sign = '';
if(!isset($_SESSION['sess_idUser'])) {
	$sign = '<a href="../common/sign.php" class="lienvert">Sign In</a> ';  
	$Myitems = '<span class="lienlogo">|</span> <a href="http://codex.cro.st.com/" class="lienvert">Codex</a>  ';
 }

echo '
	<div id="header">
	    <div id="right">
			<div id="bloctext1" style="width:500px;" align="right" >
				<font class="lienvert0">'.$welcomeMessage.'</font> 
				'.$sign.'
				'.$Myitems.'
				<span class="lienlogo">|</span> <a href="http://rspec.info/" class="lienlogo">RSpec</a>
				<span class="lienlogo">|</span> <a href="http://seleniumhq.org/docs/03_webdriver.html" class="lienlogo">Selenium WebDriver Documentation</a>
			</div>
		</div>
	</div>
';

echo '
    </head>
    <body>
        <div id="header">
            <a href="cases.php" class="community">Manage testsuites</a>
        </div>
        <div id="body_skin">
            <table width="100%">
                <tr>
                    <td width="10%" nowrap="nowrap">
                        <form action="" method="POST">
                            <div id="submit_panel"><input type="submit" value="Run !" /></div>
                            <fieldset>
                                <legend><b>Testsuites</b></legend>
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
                        <fieldset>
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
                        <fieldset>
                            <legend>Results</legend>
                            <pre>';
if (isset($_REQUEST['run'])) {
    // manage request
    $testSuite = new TestSuite(substr($_REQUEST['run'], 0, -3));
    $testSuite->run();
    echo 'Result file stored';
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