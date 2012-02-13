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

/**
 * Search testcases files
 *
 * @param String $dir   path to directory containing testcases files
 *
 * @return Array
 */
function search_testsuites($dir) {
    $testsuites = array();
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if (!in_array($file, array('.', '..'))) {
                    if (!is_dir("$dir/$file")) {
                        $testsuites[] = $file;
                    }
                }
            }
            closedir($dh);
        }
    }
    return $testsuites;
}

?>
<html>
    <head>
        <title>Codex automatic validation</title>
        <link href="include/css/index.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <table width="100%">
            <tr>
                <td width="10%" nowrap="nowrap">
                    <form action="" method="POST">
                        <input type="hidden" name="run" />
                        <div id="submit_panel"><input type="submit" value="Run !" /></div>
                        <a href="cases">Manage testsuites</a>
                        <fieldset>
                            <legend>Testsuites</legend>
                            <ul id="menu">
                            <?php
                                $testsuites = search_testsuites('../testsuites');
                                foreach($testsuites as $t) {
                                    echo '<li><input type="radio" name="'.$t.'" />'.$t.'</li>';
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
                        if (isset($_REQUEST['run'])) {
                            // manage request
                            // TODO: Run testsuite
                            echo "Result file stored";
                        }
                        ?>
                        </pre>
                    </fieldset>
                </td>
            </tr>
        </table>
    </body>
</html>
