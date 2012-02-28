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

class TestCase {

    public $id;
    public $name;
    public $_dependenciesMap;
    public $_flagsMap;
    public $filePath;
    public $_testCaseFile;

    /**
     * Constructor
     *
     * @param String $name     The test case name
     * @param String $fileinfo The path to the physical ruby test case file
     *
     * @return Void
     */
    public function __construct($name, $fileinfo = null) {
        $this->id               = "";
        $this->name             = $name;
        $this->_dependenciesMap = array();
        $this->_flagsMap        = array();
        $this->filePath         = TestCaseManager::TESTCASES_PATH;
        if (!empty($fileinfo)) {
            $this->_testCaseFile = $fileinfo;
        } else {
            $this->_testCaseFile = new SplFileInfo($this->filePath.$this->name.'.rb');
        }
        if (!$this->_testCaseFile->isFile()) {
            throw new RuntimeException ("The test case file referenced by SplFileInfo object doesn't exist or is not a regular file.");
        }
    }

    /**
     * Retrive the test case file content
     *
     * @return String
     */
    public function getContent() {
        $testCaseFileObj = new SplFileObject($this->_testCaseFile);
        $testCaseFileContent = "";
        if ($testCaseFileObj->isReadable()) {
            while ($testCaseFileObj->valid()) {
                $line        = $testCaseFileObj->fgets();
                $trimmedLine = trim($line);
                if (!empty($trimmedLine) && !preg_match("/^#/", $trimmedLine)) {
                    $testCaseFileContent .= "        ".$line;
                }
            }
        } else {
            throw new LogicException('Unable to retrieve file content. Test case file "'.$testCaseFileObj.'" is not readable.');
        }
        return $testCaseFileContent;
    }

    /**
     * Wrapping the test case file content into one RSpec example code
     *
     * @return String
     */
    public function retrieveRspecExampleGroup() {
        $exampleGroup = "#---- Test case ".$this->name." ----\n";
        $exampleGroup .= "    describe \"".$this->name."\" do\n";
        try {
            $exampleGroup .= $this->getContent();
        } catch (LogicException $e) {
            echo $e->getMessage();
        }
        $exampleGroup .= "\n    end\n";
        $exampleGroup .= "#---- End test case ".$this->name." ----\n\n";
        return $exampleGroup;
    }

    /**
     * Obtain test case dependencies
     *
     * @return Array
     */
    function getDependencies() {
        if (empty($this->_dependenciesMap)) {
            $testCaseFileObj = new SplFileObject($this->_testCaseFile);
            $line            = "";
            $inDependencies  = false;
            if ($testCaseFileObj->isReadable()) {
                while ($testCaseFileObj->valid() && $line != "#--- End dependency list\n") {
                    $line = $testCaseFileObj->fgets();
                    if ($inDependencies && $line == "#--- End dependency list\n") {
                        $inDependencies = false;
                    }
                    if ($inDependencies) {
                        $this->_dependenciesMap[] = trim(str_replace("#", "", $line));
                    }
                    if (!$inDependencies && $line == "#--- Start dependency list\n") {
                        $inDependencies = true;
                    }
                }
            }
        }
        return $this->_dependenciesMap;
    }

    /**
     * Set test case dependencies
     *
     * @return Void
     */
    protected function setDependencies($dependenciesArray) {
        $this->_dependenciesMap = $dependenciesArray;
    }

    /**
     * Obtain test case flags
     *
     * @return Array
     */
    function getFlags() {
        return $this->$_flagsMap;
    }

    /**
     * Set test case flags
     *
     * @return Void
     */
    protected function setFlags($flagsArray) {
        $this->$_flagsMap = $flagsArray;
    }

}

?>
