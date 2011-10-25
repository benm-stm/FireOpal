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
ini_set('include_path', ini_get('include_path').':'.dirname(__FILE__).'/../include/');

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require_once 'set.php';

$input = '<li class=""><label  for="host">host:</label> <input  type="text" id="host" name="host" value="'.$GLOBALS['host'].'" /></li>';
$input .= '<li class=""><label  for="client">client:</label> <input  type="text" id="client" name="client" value="'.$GLOBALS['client'].'" /></li>';
$input .= '<li class=""><label  for="client">client:</label> 
               <select type="text" id="browser" name="browser" />
               <option value="*firefox" selected="selected">Firefox</option>
               <option value="*iexplore">Internet Explorer</option>
               <option value="*chrome">Chrome</option>
               </select></li>';
$input .= '<li class=""><label  for="user">user:</label> <input  type="text" id="user" name="user" value="'.$GLOBALS['user'].'" /></li>';
$input .= '<li class=""><label  for="password">password:</label> <input  type="password" id="password" name="password" value="'.$GLOBALS['password'].'" /></li>';
$input .= '<li class=""><label  for="project">project:</label> <input  type="text" id="project" name="project" value="'.$GLOBALS['project'].'" /></li>';
$input .= '<li class=""><label  for="projectId">project ID:</label> <input  type="text" id="projectId" name="projectId" value="'.$GLOBALS['project_id'].'" /></li>';
$input .= '<li class=""><label  for="tracker">tracker:</label> <input  type="text" id="tracker" name="tracker" value="'.$GLOBALS['tracker'].'" /></li>';
$input .= '<li class=""><label  for="trackerName">tracker Name:</label> <input  type="text" id="trackerName" name="trackerName" value="'.$GLOBALS['trackerName'].'" /></li>';
$input .= '<li class=""><label  for="trackerShortName">tracker Short Name:</label> <input  type="text" id="trackerShortName" name="trackerShortName" value="'.$GLOBALS['trackerShortName'].'" /></li>';
$input .= '<li class=""><label  for="docmanRootId">docman Root ID:</label> <input  type="text" id="docmanRootId" name="docmanRootId" value="'.$GLOBALS['docman_root_id'].'" /></li>';

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
                        search_tests_rec("$dir/$file", $tab[($entry == '../tests'?'Tuleap':$entry)], $file);
                    } else {
                        $tab[($entry == '../tests'?'Tuleap':$entry)]['_tests'][] = $file;
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
 * Get tests of the last run
 *
 * @return Array
 */
function get_last_run() {
    $lastRun = array();
    if (file_exists('../log/last_run')) {
        $lastRunArray = file('../log/last_run');
        foreach ($lastRunArray as $line) {
            $lastRun[substr($line, 0, -1)] = true;
        }
    }
    return $lastRun;
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
function display_tests($tests, $categ, $params, $lastRun) {
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
            display_tests($t, $c, array('is_cat' => ($categ !== "_tests"), 'prefixe' => $prefixe, 'checked' => ($params['checked'] && $categ !== "_tests" ? $params['checked'][$categ] : $params['checked'])), $lastRun);
        }}
        
        if ($categ !== "_tests") {
            echo '</ul>';
            echo '</li>';   
        }
    } else {
        echo '<li>';
        echo '<input type="hidden"   name="'. $prefixe .'['. $tests .']" value="0" />';
        echo '<input type="checkbox" name="'. $prefixe .'['. $tests .']" value="1" '. (($params['checked'] && $params['checked'][$tests]) || (isset($lastRun[$tests]) && $lastRun[$tests]) ? 'checked="checked"' : '') .' />';
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
        if ($key == 'Tuleap') {
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

?>
<html>
    <head>
        <title>Tuleap integration tests</title>
        <style type="text/css">
        body {
            margin:0;
            padding:0;
        }
        body, th, td {
            font-family: Verdana, Arial, sans-serif;
            font-size:10pt;
        }
        #menu,
        #menu ul {
            list-style-type:none;
            padding-left:0px;
        }
        #menu ul li {
            padding-left:40px;
        }
        #menu ul li.categ {
            padding-left:24px;
        }
        #submit_panel {
            text-align:center;
        }
        #submit_panel input {
            font-size:2em;
            width:200px;;
        }
        label {
            display:block;
            width: 150px;
        }
        tr {
            vertical-align:top;
        }
        .fail { 
            color: red; 
        } 
        .pass { 
            color: green; 
        } 
        pre { 
            background-color: lightgray; 
        }
        a img {
            border:none;
        }
        </style>
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
        <table width="100%">
            <tr>
                <td width="10%" nowrap="nowrap">
                    <form action="" method="POST">
                        <div id="submit_panel"><input type="submit" value="Run !" /></div>
                       <fieldset>
                            <legend>Config</legend>
                            <ul id="menu"><li class="">
                            <?php
                                echo $input;
                            ?>
                            </ul> 
                        </fieldset> 
                        <fieldset>
                            <legend>Tests</legend>
                            <ul id="menu">
                            <?php
                                $tests = search_tests('../tests');
                                $lastRun = get_last_run();
                                foreach($tests as $c => $t) {
                                    display_tests($t, $c, array('is_cat' => true, 'prefixe' => 'tests_to_run', 'checked' => @$_REQUEST['tests_to_run']), $lastRun);
                                }
                            ?>
                            </ul>
                        </fieldset>
                    </form>
                </td>
                <td width="90%">
                    <fieldset>
                        <legend>Results</legend>
                        <pre><?php
                        //flush();
                        ob_start('flushHandler');
                        if (isset($_REQUEST['tests_to_run'])) {
                            // manage request
                            require_once 'IntegrationTests.class.php';
                            $suite = new IntegrationTests();
                            $files = prepare_files($_REQUEST['tests_to_run'], '../tests');
                            $suite->addFiles($files);
                            $result = $suite->run();
                            foreach ($result as $line) {
                                echo $line."\n";
                            }
                        }
                        ?>
                        </pre>
                    </fieldset>
                </td>
            </tr>
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
