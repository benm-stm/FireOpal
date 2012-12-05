<?php
/**
 * Copyright (c) STMicroelectronics 2012. All rights reserved
 *
 * FireOpal is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * FireOpal is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with FireOpal. If not, see <http://www.gnu.org/licenses/>.
 */

require_once 'TestCase.class.php';

class TestCaseManager {

    const TESTCASES_PATH = "../testcases/";
    protected $dbHandler;

    /**
     * Create a recursive filesytem iterator
     *
     * @param String $directory Directory to be used
     *
     * @return RecursiveIteratorIterator
     */
    function getFileSystemIterator($directory) {
        return new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::KEY_AS_FILENAME | FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST);
    }

    /**
     * Recursivly creates a basic HTML tree of a given directory, then render a select input with the file system content starting from this given node.
     * Visited directories are in dispalyed as bold and disabled select options, if you remove SELF_FIRST option, visited directories are ignored:
     * Obviously, they are heading nodes and the iterator object doesn't return them automatically.
     * $entry is an SPLFileInfo object, default getFilename() method would return the absolute path of the file; Since you need only the filename,
     * you have to set the KEY_AS_FILENAME constant to the iterator constructor.
     *
     * @param String  $directory File system node considered as root for the exploration
     *
     * @return String
     */
    function displayFileSystem($directory) {
        $iter   = $this->getFileSystemIterator($directory);
        $output = '<select align=top name="testCases" size=10  style="width:320px" multiple="multiple">';
        foreach ($iter as $entry) {
            if ($entry->isDir()) {
                $output .= '<option value="'.substr($entry->getPathname(), strlen(TestCaseManager::TESTCASES_PATH)).'" disabled></b>'.$entry->getFilename().'</b></option>';
            } else {
                $output .= '<option value="'.substr($entry->getPathname(), strlen(TestCaseManager::TESTCASES_PATH)).'">&nbsp;&nbsp;&nbsp;&nbsp;<a href="lightbox/images/examples/image-2.jpg" rel="lightbox">'.$entry->getFilename().'</a></option>';
            }
        }
        $output .= '<select>';
        return $output;
    }

    /**
     * Generate a list of testcases in an array
     *
     * @param String $directory Directory to be used
     *
     * @return Array
     */
    function listFileSystem($directory) {
        $iter      = $this->getFileSystemIterator($directory);
        $testcases = array();
        foreach ($iter as $entry) {
            if (!$entry->isDir()) {
                $testcases[] = substr($entry->getPathname(), strlen(TestCaseManager::TESTCASES_PATH));
            }
        }
        return $testcases;
    }

    /**
     * Returns testcase given its hash
     *
     * @return TestCase
     */
    function getTestCaseByHash($testCaseHash) {
        $this->dbHandler = DBHandler::getInstance();
        $sql  = "SELECT * FROM testcase
                 WHERE id = ".$this->dbHandler->quote($testCaseHash);
        $result = $this->dbHandler->query($sql);
        if($result) {
            $result->setFetchMode(PDO::FETCH_OBJ);
            $row = $result->fetch();
            return new TestCase($row->filename, null, $testCaseHash);
        }
        return;
    }

    /**
     * Display test case as HTML output
     *
     * @param String $name     The test case name
     *
     * @return String
     */
    function rspecPrettyFormat($testCaseName) {
        $testCaseObj    = new TestCase($testCaseName);
        $rspecStructure = $testCaseObj->retrieveRspecStructure();
    $rspecOutput    = '<br><pre>ClassName     : '.$testCaseObj->name.'.rb';
    $rspecOutput   .= '<br><br>'.count($rspecStructure).' Rspec examples: <br>';
        foreach ($rspecStructure as $testString) {
        $rspecOutput .= '<span>'.$testString.'</span><br>';
    }
    $rspecOutput   .= '<br></pre>';
        return $rspecOutput;
    }
}

?>