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
$testSuiteManager = new TestSuiteManager();

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

/**
 * Search test files recursively
 *
 * @param String $dir   path to directory containing test files
 * @param Array  $tab   Array of collected tests
 * @param String $entry path to test file
 *
 * @return void
 */
function search_tests_rec($dir, &$tab, $entry) {
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if (!in_array($file, array('.', '..'))) {
                    if (is_dir("$dir/$file")) {
                        search_tests_rec("$dir/$file", $tab[($entry == '../testcases'?'Codex':$entry)], $file);
                    } else {
                        $tab[($entry == '../testcases'?'Codex':$entry)]['_tests'][] = $file;
                    }
                }
            }
            closedir($dh);
        }
    }
}

/**
 * Search all available tests
 *
 * @param String $entry path to directory containing test files
 *
 * @return Array
 */
function search_tests($entry) {
    search_tests_rec($entry, $tests, $entry);
    return $tests;
}

/**
 * Show the list of collected test files with hierarchy
 *
 * @param Array  $tests  Array of collected tests
 * @param String $categ  Type of the node
 * @param Array  $params metadata
 *
 * @retrun void
 */
function display_tests($tests, $categ, $params) {
    $prefixe  = ($params['is_cat'] && $categ !== "_tests") ? $params['prefixe'] .'['. $categ .']' : $params['prefixe'];
    if ($params['is_cat']) {
        if ($categ !== "_tests") {
            echo '<li class="categ">';
            echo '<input type="hidden"   name="'. $prefixe .'[_do_all]" value="0" />';
            echo '<input type="checkbox" name="'. $prefixe .'[_do_all]" value="1" '. ($params['checked'] && $params['checked'][$categ]['_do_all'] ? 'checked="checked"' : '') .' />';
            echo '<b>'. $categ .'</b>';
            echo '<ul>';
        }
        if (is_array($tests)) {
        foreach($tests as $c => $t) {
            display_tests($t, $c, array('is_cat' => ($categ !== "_tests"), 'prefixe' => $prefixe, 'checked' => ($params['checked'] && $categ !== "_tests" ? $params['checked'][$categ] : $params['checked'])));
        }}
        
        if ($categ !== "_tests") {
            echo '</ul>';
            echo '</li>';   
        }
    } else {
        echo '<li>';
        echo '<input type="hidden"   name="'. $prefixe .'['. $tests .']" value="0" />';
        echo '<input type="checkbox" name="'. $prefixe .'['. $tests .']" value="1" '. ($params['checked'] && $params['checked'][$tests] ? 'checked="checked"' : '') .' />';
        echo $tests;
        echo '</li>';
    }
}

/**
 * Add javascript to the list of tests
 *
 * @param Array  $tests  Array of collected tests
 * @param String $categ  Type of the node
 * @param Array  $params metadata
 *
 * @return void
 */
function display_tests_as_javascript($tests, $categ, $params) {
    if ($params['is_cat']) {
        if ($categ !== "_tests") {
            echo "'$categ': {";
        }
        
        foreach($tests as $c => $t) {
            display_tests_as_javascript($t, $c, array('is_cat' => ($categ !== "_tests")));
        }
        if ($categ !== "_tests") {
            echo '},';  
        }
    } else {
        echo "'$tests':true,";
    }
}

/**
 * Collect selected files to be executed
 *
 * @param Array  $files  Array of selected tests
 * @param String $prefix Path to the directory containing the file
 * 
 * @return Array
 */
function prepare_files($filesArray, $prefix) {
    if (substr($prefix, -1) == '/') {
        $prefix = substr($prefix,0,-1); ;
    }
    $files = array();
    foreach ($filesArray as $key => $node) {
        if ($key == 'Codex') {
            $key = '';
        }
        if (is_array($node)) {
            $files = array_merge($files, prepare_files($node, $prefix."/".$key));
        } else {
            if ($node && $key != '_do_all') {
                $files = array_merge($files, array($prefix."/".$key));
            }
        }
    }
    return $files;
}

$output = '';
if (isset($_REQUEST['testcases_to_add'])) {
    if (!empty($_REQUEST['testsuite_name'])) {
        require_once dirname(__FILE__).'/../include/TestSuite.class.php';
        require_once dirname(__FILE__).'/../include/TestCase.class.php';
        // manage request
        $files = prepare_files($_REQUEST['testcases_to_add'], dirname(__FILE__).'/../testcases');
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
                    <td id="block_generate">
                        <form action="" method="POST">
                            <fieldset>
                                <legend><b>Testsuite</b></legend>
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
                            <fieldset>
                                <legend><b>Testcases</b></legend>
                                <ul id="menu">';
$tests = search_tests('../testcases');
foreach($tests as $c => $t) {
    display_tests($t, $c, array('is_cat' => true, 'prefixe' => 'testcases_to_add', 'checked' => @$_REQUEST['testcases_to_add']));
}
echo '
                                </ul>
                            </fieldset>
                            <div id="submit_panel">
                                <input id="generate" type="submit" value="Generate !"/>
                            </div>
                        </form>
                    </td>';
$testsuites = $testSuiteManager->searchTestsuites();
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
                                        <td><input type="checkbox" name="delete_testsuites[]'.$t.'" value="'.$t.'" /></td>
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
            </table>
        </div>
    </body>
    <script type="text/javascript">
    //<!--
    var testcases_to_add = {';
foreach($tests as $c => $t) {
    display_tests_as_javascript($t, $c, array('is_cat' => true));
}
echo '
    };
    //-->
    </script>
</html>';

?>
