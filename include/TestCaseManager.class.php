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

    const TESTCASES_PATH = "../testcases";

    /**
     * Recursivly creates a basic HTML tree of a given directory.
     *
     * @param String $directory File system node considered as root for the exploration
     *
     * @return String
     */
    function displayFileSystem($directory) {
        $iter = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::KEY_AS_FILENAME | FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST);
        $output = '<select align=top name="testCases" size=10  style="width:320px" multiple="multiple">';
        foreach ($iter as $entry) {
            if ($entry->isDir()) {
                $output .= '<option value="'.substr($entry->getPathname(), strlen(TestCaseManager::TESTCASES_PATH)).'" disabled></b>'.$entry->getFilename().'</b></option>';
            } else {
                $output .= '<option value="'.substr($entry->getPathname(), strlen(TestCaseManager::TESTCASES_PATH)).'">&nbsp;&nbsp;&nbsp;&nbsp;'.$entry->getFilename().'</option>';
            }
        }
        $output .= '<select>';
        return $output;
    }

}

?>
