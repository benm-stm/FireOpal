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

class testSuiteManager {

    /**
     * Search testsuite files
     *
     * @return Array
     */
    function searchTestsuites() {
        $testsuites = array();
        $dir = dirname(__FILE__).'/../testsuites/';
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

    /**
     * Delete testsuites
     *
     * @param Array $testsuites Testsuites to delete
     *
     * @return Boolean
     */
    function delete($testsuites) {
        foreach ($testsuites as $testsuite) {
            // TODO: Handle errors
            unlink(dirname(__FILE__).'/../testsuites/'.$testsuite);
        }
    }

}

?>