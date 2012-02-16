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
if (isset($_REQUEST['tests_to_run'])) {
    require_once dirname(__FILE__).'/../include/TestSuite.class.php';
	require_once dirname(__FILE__).'/../include/TestCase.class.php';
    // manage request
    $files = prepare_files($_REQUEST['tests_to_run'], dirname(__FILE__).'/../testcases');
    //@TODO: validate params here
    // TODO: Generate test suite
    $testSuite = new TestSuite($files, $_REQUEST['testsuite_name']);
	// populating test cases map
/*    foreach ($files as $test) {
        $testCaseFile = new SplFileInfo($test);
        $testCase     = new testCase($testCaseFile->getBasename('.rb'),$testCaseFile);
        $testSuite->attach($testCase);
    }*/
	$testSuiteManager->populateTestSuite($testSuite, $files);
    $testSuite->storeTestSuiteDetails($_REQUEST);
    $testSuite->bindConfigurationElements($_REQUEST);
    $result = $testSuite->loadTestSuite();
    $output = "Testsuite stored";
}

if (isset($_REQUEST['delete_testsuites'])) {
    $testSuiteManager->delete($_REQUEST['delete_testsuites']);
}

?>
<html>
    <head>
        <title>Codex automatic validation</title>
        <link href="include/css/index.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="/scripts/prototype/prototype.js"></script>
        <script type="text/javascript" src="/scripts/scriptaculous/scriptaculous.js"></script>
        <script type="text/javascript">
        function uncheck(element) {
            if (element.id != 'menu') {
                var len = element.childNodes.length;
                var found = false;
                for (var i = 0 ; i < len && !found; ++i) {
                    if (element.childNodes[i].tagName == 'INPUT' && element.childNodes[i]['type'] == 'checkbox') {
                        element.childNodes[i].checked = false;
                        found = true;
                    }
                }
                uncheck(element.parentNode);
            }
        }
        function register_events(element) {
            if (element.childNodes) {
                $A(element.childNodes).each(function (child) {
                    var found = false;
                    if (child.tagName == 'INPUT' && child['type'] == 'checkbox') {
                        Event.observe(child, 'change', (function (evt) {
                            var checked = this.checked;
                            var col = this.parentNode.getElementsByTagName('input');
                            var len = col.length;
                            for (var i = 0 ; i < len ; ++i) {
                                if (col[i]['type'] == 'checkbox') {
                                    col[i].checked = checked;
                                }
                            }
                            //On remonte
                            if (!checked && this.parentNode.id != 'menu') {
                                uncheck(this.parentNode.parentNode.parentNode);
                            }
                        }).bind(child));
                        found = true;
                    } else {
                        register_events(child);
                    }
                });
            }
        }
        function init() {
            var plus = 0;
            $$('li.categ').each(function (element) {
                    register_events(element);
                    plus++;
                    new Insertion.Top(element, '<a href="" id="plus_' + plus +'"><img src="minus.png" /></a>');
                    var uls = $A(element.childNodes).findAll(function (element) {
                            return element.tagName == 'UL';
                    });
                    var matchPlus = new RegExp("plus.png$");
                    Event.observe($('plus_'+plus), 'click', function (evt) {
                            uls.each(function (element) {
                                    Element.toggle(element);
                            });
                            if (Event.element(evt).src.match(matchPlus)) {
                                Event.element(evt).src = 'minus.png';
                            } else {
                                Event.element(evt).src = 'plus.png';
                            }
                            Event.stop(evt);
                            return false;
                    });
            });
        }
        Event.observe(window, 'load', init, true);
        </script>
    </head>
    <body>
        <table>
            <tr>
                <td nowrap>
                    <font color="red"><?php echo $output ?></font>
                </td>
            </tr>
            <tr>
                <td nowrap>
                    <a href="/"><< Go back</a>
                    <a href="set">Update config</a>
                    <fieldset>
                        <legend>Config</legend>
                        <ul id="menu"><li class="">
                        <?php
                            require_once 'SetupManager.class.php';
                            $setupManager = new SetupManager();
                            $content = $setupManager->display(true);
                            echo $content['form'];
                        ?>
                        </ul> 
                    </fieldset>
                </td>
                <td>
                    <form action="" method="POST">
                        <fieldset>
                            <legend>Testsuite</legend>
                            <table nowrap>
                                <tr>
                                    <td>Name:</td>
                                    <td><input name="testsuite_name" /></td>
                                </tr>
                                <tr>
                                    <td>Description:</td>
                                    <td><textarea name="testsuite_description"></textarea></td>
                                </tr>
                            </table>
                        </fieldset>
                        <fieldset>
                            <legend>Testcases</legend>
                            <ul id="menu">
                            <?php
                                $tests = search_tests('../testcases');
                                foreach($tests as $c => $t) {
                                    display_tests($t, $c, array('is_cat' => true, 'prefixe' => 'tests_to_run', 'checked' => @$_REQUEST['tests_to_run']));
                                }
                            ?>
                            </ul>
                        </fieldset>
                        <div id="submit_panel">
                            <input type="submit" value="Generate !" />
                        </div>
                    </form>
                </td>
            <?php
            $testsuites = $testSuiteManager->searchTestsuites();
            if (!empty($testsuites)) {
            echo '
                <td>
                    <form action="" method="POST">
                        <fieldset>
                            <legend>Delete testsuites</legend>
                            <table nowrap>';
                                foreach($testsuites as $t) {
                                    echo '<tr>
                                              <td>'.$t.'</td>
                                              <td><input type="checkbox" name="delete_testsuites[]'.$t.'" value="'.$t.'" /></td>
                                          </tr>';
                                }
            echo '
                            </table>
                        </fieldset>
                        <div id="submit_panel"><input type="submit" value="Delete !" /></div>
                    </form>
                </td>
            </tr>';
            }
            ?>
        </table>
    </body>
    <script type="text/javascript">
    //<!--
    var tests_to_run = {
    <?php foreach($tests as $c => $t) {
        display_tests_as_javascript($t, $c, array('is_cat' => true));
    } ?>
    };
    //-->
    </script>
</html>
