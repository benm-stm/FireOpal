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

class TestCase {

    public $id;
    public $name;
    public $_dependenciesMap;
    public $_setupParamsMap;
    public $_tagsMap;
    public $filePath;
    public $_testCaseFile;

    /**
     * Constructor
     *
     * @param String $name     The test case name
     * @param String $fileinfo The path to the physical ruby test case file
     * @param String $id       The hash of test case file
     *
     * @return Void
     */
    public function __construct($name, $fileinfo = null, $id = null) {
        $this->name             = $name;
        $this->_dependenciesMap = array();
        $this->_setupParamsMap  = array();
        $this->_tagsMap         = array();
        $this->filePath         = TestCaseManager::TESTCASES_PATH;
        if (!empty($fileinfo)) {
            $this->_testCaseFile = $fileinfo;
        } else {
            $this->_testCaseFile = new SplFileInfo($this->filePath.$this->name.'.rb');
        }

        if (!empty($id)) {
            $this->id = $id;
        } else {
            $this->id               = md5_file($this->filePath.$this->name.'.rb');
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
    public function getContent($rspecStructure = NULL) {
    if(!empty($rspecStructure)){
        $rspecMap = array();
            $rspecElements = 0;
    }
        $testCaseFileObj = new SplFileObject($this->_testCaseFile);
        $testCaseFileContent = "";
        if ($testCaseFileObj->isReadable()) {
            while ($testCaseFileObj->valid()) {
                $line        = $testCaseFileObj->fgets();
                $trimmedLine = trim($line);
                if (!empty($trimmedLine) && !preg_match("/^#/", $trimmedLine)) {
                    $testCaseFileContent .= "        ".$line;
                if(!empty($rspecStructure)){
                //@todo manage this stuff in a dedicated method
                if (preg_match("/^describe/", strtolower($trimmedLine))) {
                /* This suppose that you are parsing something like:
                   describe "#precondition" do */
                $exampleLabel = explode('"', $trimmedLine);
                $rspecMap[$rspecElements]['key']   = 'describe';
                $rspecMap[$rspecElements]['label'] = $exampleLabel[1];
                $rspecElements++;
                } elseif (preg_match("/^it/", strtolower($trimmedLine))) {
                /* This suppose that you are parsing something like:
                   it "Create new wiki" do */
                $exampleLabel = explode('"', $trimmedLine);
                $rspecMap[$rspecElements]['key']   = 'it';
                $rspecMap[$rspecElements]['label'] = $exampleLabel[1];
                $rspecElements++;
                }
            }
                }
            }
        } else {
            throw new LogicException('Unable to retrieve file content. Test case file "'.$testCaseFileObj.'" is not readable.');
        }
    if(!empty($rspecStructure)){
        return $rspecMap;
    } else {
        return $testCaseFileContent;
    }
    }

    public function retrieveRspecStructure() {
    //Any refactoring is welcome, this is the worst piece of code i've ever wrote; #sick!
    //you can concat $this->name to the test case label if you need it...
    $rspecStructure  = true;
    $rspecTest       = array();
    $rspecTestCases  = $this->getContent($rspecStructure);
    $rspecTestString = '';
    $describeDepth   = 0;
        foreach ($rspecTestCases as $index => $content) {
            if ($content['key'] == 'describe') {
            if (!$describeDepth) {
                //just tp keep the index of the root describe statement
                $parentDescribeIndex = $index;
            }
        $describeDepth ++;
                if ($describeDepth > 1) {
            //prepare string for the next statement
            $rspecTestString = $rspecTestCases[$parentDescribeIndex]['label'];
        }
        $rspecTestString .= $content['label'];
        } elseif ($content['key'] == 'it') {
        $rspecTest[] = $rspecTestString.' '.$content['label'];
        }
    }
    return $rspecTest;
    }

    /**
     * Wrapping the test case file content into one RSpec example code
     *
     * @return String
     */
    public function retrieveRspecExampleGroup() {
        $exampleGroupHeader = "#---- Test case ".$this->name." ----\n";
        $exampleGroupFooter = "#---- End test case ".$this->name." ----\n\n";
        $exampleGroup       = $exampleGroupHeader."    describe \"".$this->name."\" do\n\n";
        $exampleGroup       .= "        before(:all) do\n";
        $exampleGroup       .= "            @runner.navigate.to @params['host']['value'] + '/my/'\n";
        $exampleGroup       .= "        end\n\n";
        try {
            $exampleGroup .= $this->getContent();
        } catch (LogicException $e) {
            echo $e->getMessage();
            //On Windows, newlines are actually \r\n, not just \n.
            $exampleGroup = "#".$e->getMessage()."\n";
            return $exampleGroupHeader.$exampleGroup.$exampleGroupFooter;
        }
        $exampleGroup .= "\n    end\n".$exampleGroupFooter;
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
     * Obtain test case setup params
     *
     * @return Array
     */
    function getSetupParams() {
        if (empty($this->_setupParamsMap)) {
            $testCaseFileObj = new SplFileObject($this->_testCaseFile);
            $line            = "";
            if ($testCaseFileObj->isReadable()) {
                while ($testCaseFileObj->valid()) {
                    $line = $testCaseFileObj->fgets();
                    // get all usages of setup params in $line
                    // call to a setup param is done using this syntax @params['param']['value']
                    $found = preg_match_all('/params\[["\']([^w+]*)["\']\]\[["\']value["\']\]/', $line, $matches);
                    if ($found) {
                        foreach ($matches[1] as $match) {
                            $this->_setupParamsMap[] = $match;
                        }
                    }
                }
            }
        }
        return $this->_setupParamsMap;
    }

    /**
     * Check if all params needed by the testcase are present in setup
     *
     * @return Array
     */
    function checkSetupParams() {
        $setupManager  = new Setup();
        $setup         = $setupManager->load();
        $setupParams   = $this->getSetupParams();
        $missingParams = array();
        foreach ($setupParams as $param) {
            if (!array_key_exists($param, $setup)) {
                $missingParams[] = $param;
            }
        }
        return $missingParams;
    }

    /**
     * Obtain test case tags
     *
     * @return Array
     */
    function getTags() {
        if (empty($this->_tagsMap)) {
            $testCaseFileObj = new SplFileObject($this->_testCaseFile);
            $line            = "";
            $inTags          = false;
            if ($testCaseFileObj->isReadable()) {
                while ($testCaseFileObj->valid() && $line != "#--- End tags\n") {
                    $line = $testCaseFileObj->fgets();
                    if ($inTags && $line == "#--- End tags\n") {
                        $inTags = false;
                    }
                    if ($inTags) {
                        $this->_tagsMap[] = trim(str_replace("#", "", $line));
                    }
                    if (!$inTags && $line == "#--- Start tags\n") {
                        $inTags = true;
                    }
                }
            }
        }
        return $this->_tagsMap;
    }

    /**
     * Check if the current setup are compatible with testcase tags
     *
     * @return Boolean
     */
    function checkTags() {
        $setupManager = new Setup();
        $setup        = $setupManager->load();
        $tags         = $this->getTags();
        // TODO: Check tags compatibility
        return true;
    }

    /**
     * Returns if a given testcase had already an execution
     *
     * @param TestCase $testcase
     *
     * @return Boolean
     */
    function hasAnExecution() {
        $this->dbHandler = DBHandler::getInstance();
        $sql  = "SELECT NULL FROM testcase_result
                 WHERE testcase_id = ".$this->dbHandler->quote($this->id);
        $result = $this->dbHandler->query($sql);
        if($result && $result->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

     /**
     * Returns the status of a given testcase hash
     *
     * @param TestCase $testcase
     *
     * @return Boolean
     */
    function getLastOldExecutionStatus() {
        $this->dbHandler = DBHandler::getInstance();
        $sql  = "SELECT status FROM testcase_result
                 WHERE testcase_id = ".$this->dbHandler->quote($this->id)." ORDER BY date DESC";
        $result = $this->dbHandler->query($sql);
        if($result && $result->rowCount() > 0) {
            $result->setFetchMode(PDO::FETCH_OBJ);
            $row = $result->fetch();
            if ($row->status == ResultManager::STATUS_FAILURE) {
                return false;
            }
        }
        return true;
    }

    function getLastOldExecutionStatusByRspecLabel($rspecLabel) {
        $this->dbHandler = DBHandler::getInstance();
        $sql  = "SELECT status FROM testcase_result
                 WHERE rspec_label = ".$this->dbHandler->quote($rspecLabel)." ORDER BY date DESC";
        $result = $this->dbHandler->query($sql);
        if($result) {
            $result->setFetchMode(PDO::FETCH_OBJ);
            $row = $result->fetch();
            //if ($row->status == ResultManager::STATUS_FAILURE) {
        if ($row->status == "FAILURE") {
        return false;
            }
        }
        return true;
    }
}

?>