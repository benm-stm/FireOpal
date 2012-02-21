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

class TestCaseManager {

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

    function displayFileSystem($directory) {
        $iter = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::KEY_AS_FILENAME)
        , RecursiveIteratorIterator::SELF_FIRST);
        $tokenHeader = '<SELECT align=top name="testCases" size=10  style="width:320px" multiple="multiple">';
        printf($tokenHeader);
        foreach ($iter as $entry) {
            if ($entry->isDir()) {
                $token = "<option disabled></b>%s</b></option>";
            } else {
                $token = "<option>&nbsp;&nbsp;&nbsp;&nbsp;%s</option>";
            }
            echo str_repeat("&nbsp;", 3*$iter->getDepth());
            printf($token, $entry);
        }
        $tokenFooter = '<SELECT>';
        printf($tokenFooter);
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
}

?>
