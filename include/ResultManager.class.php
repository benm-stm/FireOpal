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

require_once 'common/db/connect.php';
require_once 'common/User.class.php';
require_once 'TestSuiteManager.class.php';
require_once 'TestCaseManager.class.php';

class ResultManager {

    const RESULTS_PATH = "../log/";

    const STATUS_FAILURE = "FAILURE";
    const STATUS_PASS    = "PASS";

    var $user;
    var $dbHandler;

    /**
     * Constructor of the class
     *
     * @param User $user The connected user
     *
     * @return Void
     */
    function __construct(User $user) {
        $this->user = $user;
        $this->dbHandler = DBHandler::getInstance();
    }

    /**
     * Store the execution result in DB
     *
     * @param Array  $output        Execution output
     * @param String $testSuiteName Name of the testsuite
     * @param String $testSuite     Snapshot of the testsuite that gave the output
     *
     * @return Boolean
     */
    function logNewResult($output, $testSuiteName, $testSuite) {
        try {
            $sql = "INSERT INTO result (user, name, output, testsuite, date) VALUES (".$this->user->getAtt('id').", ".$this->dbHandler->quote($testSuiteName).", ".$this->dbHandler->quote(join("\n", $output)).", ".$this->dbHandler->quote($testSuite).", ".time().")";
            return $this->dbHandler->query($sql);
        } catch (Exception $e) {
           echo $e->getMessage();
        }
    }

    /**
     * Store the execution result a given testcase in DB
     *
     * @param String $testcase_id    Id of the testcase
     * @param String $testsuite_id   Id of the testsuite that gave the output
     * @param String $testCaseOutput Execution output
     * @param String $status         Status of the testcase
     *
     * @return Boolean
     */
    function logTestCaseResult($testcase_id, $testsuite_id, $testCaseOutput, $rspecLabel, $status) {

        try {
            $sql = "INSERT INTO testcase_result ( testcase_id, testsuite_id, rspec_label, status, output, date) VALUES (".$this->dbHandler->quote($testcase_id).", ".$this->dbHandler->quote($testsuite_id).", ".$this->dbHandler->quote($rspecLabel).", ".$this->dbHandler->quote($status).", ".$this->dbHandler->quote($testCaseOutput).", ".time().")";
            return $this->dbHandler->query($sql);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * retrieve the execution result of a given testcase
     *
     * @param String $tcName        Name the testcase
     * @param String $output        Execution output of the testsuite
     * @param String $testsuiteName Name of the testsuite that gave the output
     *
     * @return Boolean
     */
    function prepareTestCaseResult($tcName, $output, $testsuiteName) {
    $xmlOutput = join("\n", $output);
        //For the moment use testsuite name as Id...
        $testCase = new TestCase(substr($tcName, 0, -3));
        $rspecStructure = $testCase->retrieveRspecStructure();
        foreach ($rspecStructure as $key => $rspecLabel) {
            $rspecResult = $this->displayTestCaseResult($xmlOutput, $rspecLabel);
            $status      = $rspecResult['status'];
            $rspecOutput = $rspecResult['output'];
            $this->logTestCaseResult($testCase->id, $testsuiteName, $rspecOutput,  $rspecLabel, $status);
        }
    }

    /**
     * Delete an execution result from DB
     *
     * @param Integer $id Id of the result to delete
     *
     * @return Boolean
     */
    function deleteResult($id) {
        $sql = "DELETE FROM result
                WHERE id = ".$id."
                  AND user = ".$this->user->getAtt('id');
        return $this->dbHandler->query($sql);
    }

    /**
     * Display the list of execution rsults
     *
     * @return String
     */
    function displayResults() {
        $output = '';
        $sql    = "SELECT * FROM result
                   WHERE user = ".$this->user->getAtt('id')."
                   ORDER BY date";
        try {
            $result = $this->dbHandler->query($sql);
                if($result) {
                $output = '<div><br><table>
                <tr>
                <td class="resultHeader">Testsuite</td>
                <td class="resultHeader">Run date</td>
                <td class="resultHeader">Health</td>
                <td class="resultHeader">Output</td>
                <td class="resultHeader">Download output</td>
                <td class="resultHeader">Delete</td>
                </tr>';
                $result->setFetchMode(PDO::FETCH_OBJ);
                $row = $result->fetchAll();
                foreach ($row as $testsuite) {
                    $regression = $this->getTestsuiteRegression($testsuite->name);
            if (!empty($regression)) {
            echo "There is a regression within test suite ".$testsuite->name;
            }
                    $output    .= '<tr>
                    <td id="resultLink">
                        <a href="?download_testsuite='.$testsuite->id.'" >'.$testsuite->name.'</a>
                    </td>
                    <td id="resultDate">
                        '.date("D M j, Y G:i:s T", $testsuite->date).'
                    </td>
                    <td >'.$this->displayTestsuiteHealth($testsuite).'
                    </td>
                    <td>
                        <fieldset class="fieldset">
                            <legend class="toggler" onclick="toggle_visibility(\'result_output_'.$testsuite->id.'\'); if (this.innerHTML == \'+\') { this.innerHTML = \'-\'; } else { this.innerHTML = \'+\'; }">+</legend>
                            <span id="result_output_'.$testsuite->id.'" style="display: none;" >';
                    if ($this->validateXML($testsuite->output)) {
                        $output .= '<pre>'.$this->validateXML($testsuite->output).'</pre>';
                    } else  {
                            $output .=  '<pre>'.$this->processResult($testsuite->output).'</pre>';
                    }
                    $output .=  '</span>
                        </fieldset>
                    </td>
                    <td id="resultLink">
                        <a href="?download_result='.$testsuite->id.'" >Download</a>
                    </td>
                    <td id="resultLink">
                        <a href="?delete_result='.$testsuite->id.'" >Delete</a>
                    </td>
                    </tr>';

                }
                $output .= '</table></div>';
            }
        } catch(PDOException $e) {
            $this->error[] = $e->getMessage();
        }

        return $output;
    }

     /**
     * Retrieve execution trace for a given test case: status, output,...
     * $testCaseName would look like this: 'First_test widget/AddWidget Add a widget to My dashboard#precondition Find my personal page'
     *
     * @param String $output       Test suite JUNIT XML output
     * @param String $testCaseName The name of the test case name
     *
     * @return Array
     */
    function displayTestCaseResult($output, $testCaseName) {
        $result = array();
        if (!$this->validateXML($output)) {
            $xmlDoc = simplexml_load_string($output);
            //Cannot use classname node instead, i need to retrieve the Rspec stuff instead of file path
            $testCase = $xmlDoc->testcase['name'];
            //@todo propoerly compare strings
            if ($testCase = $testCaseName) {
                if ($xmlDoc->testcase->failure || $xmlDoc->testcase->error) {
                    //@todo check for regression here
                    $result['status'] = self::STATUS_FAILURE;
                } else {
                    $result['status'] = self::STATUS_PASS;
                }
                $result['output'] = $xmlDoc->testcase->asXML();
            }
        }
        return ($result);
    }

     /**
     * Retrieve testsuite health status
     *
     * @param PDOStatement A result row containing the JUNIT XML output
     *
     * @return String
     */
    function displayTestsuiteHealth($resultRow) {
        $output = $resultRow->output;
        $health = '<span>';
        if ($this->validateXML($output)) {
            /*@todo, this is just for debug, we need to hundle unvalid xml schema and display the right img*/
            $health .= '<img src="https://raw.github.com/jenkinsci/jenkins/master/war/src/main/webapp/images/48x48/health-20to39.png">';
        } else {
            $xmlDoc   = simplexml_load_string($output);
            $failures = "";
            $xmlDoc2  = $xmlDoc->xpath('/testsuite/testcase/failure');
            while(list( , $node) = each($xmlDoc2)) {
                $failures .= "<br/>".$node;
            }
            //if (intval($xmlDoc->failures)) {
            if (!empty($failures)) {
                $health .= '<img src="https://raw.github.com/jenkinsci/jenkins/master/war/src/main/webapp/images/48x48/health-00to19.png">';
            } else {
                $health .= '<img src="https://raw.github.com/jenkinsci/jenkins/master/war/src/main/webapp/images/48x48/health-80plus.png">';
            }
        }
        $health .= '</span>';
        return $health;
    }

    /**
     * Retrieve testsuite regression status
     *
     * @return Array
     */
    function getTestsuiteRegression($testSuiteName) {
        //@todo Clean up debug stuff
        $testSuiteManager = new TestSuiteManager();
        $testSuite        = $testSuiteManager->getTestSuite($testSuiteName);
        $testCasesArray   = $testSuite->getTestCases();
        $testCaseManager  = new TestCaseManager();
        $regressionArray  = array();
        //PDO::setAttribute("PDO::MYSQL_ATTR_USE_BUFFERED_QUERY", true);
        if(!empty($testCasesArray)) {
            foreach ($testCasesArray as $key => $testCaseName) {
                $regression     = FALSE;
                $testCase       = new TestCase(substr($testCaseName, 0, -3));
                $rspecStructure = $testCase->retrieveRspecStructure();
                foreach ($rspecStructure as $rspeckey => $rspecLabel) {
                    if (!empty($rspecLabel)) {
                        try {
                            unset($result);
                            $sqlString = "SELECT testsuite_id as Testsuite, FROM_UNIXTIME(date) as Date, status as Status FROM testcase_result
                                         WHERE rspec_label = ".$this->dbHandler->quote($rspecLabel)." ORDER BY date DESC";
                            $result   = $this->dbHandler->query($sqlString);
                            $result->setFetchMode(PDO::FETCH_OBJ);
                            $rows     = $result->fetchAll();
                            // echo "<br>".$rspecLabel." :  <br> ----> " ;
                            //echo $rows[0]->testsuite; echo  $rows[1]->Testsuite;
                            if ($rows[0]->Status == ResultManager::STATUS_FAILURE && $rows[1]->Status == ResultManager::STATUS_PASS) {
                                //echo $rows[0]->Date."-+-";
                                //echo $rows[0]->Status."<br>";
                                 /*echo $rows[1]->Date."-+-<br>";
                                   echo $rows[1]->Status."<br>";*/
                                $regression = TRUE;
                                $regressionArray[$testCaseName][$rspecLabel] = $regression;
                            }
                            //there would be a regression if execution at 'T-1' has Passed Status and execution at 'T' has Failure status
                        } catch (Exception $e) {
                           echo $e->getMessage();
                        }
                    }
                }
            }
        }
        /*if(!empty($testCasesArray)) {
                foreach ($testCasesArray as $key => $testCaseName) {
                    $regression = FALSE;
                    $testCase = new TestCase(substr($testCaseName, 0, -3));
                    $rspecStructure = $testCase->retrieveRspecStructure();
                    foreach ($rspecStructure as $rspeckey => $rspecLabel) {
                    $lastExec = $testCase->getLastOldExecutionStatusByRspecLabel($rspecLabel);
                     echo $rspecLabel." -- ".$lastExec."<br>";
                    if(!$lastExec) {
                        $regression = TRUE;
                        $regressionArray[$testCaseName][$rspecLabel] = $regression;
                        }
                    }
                }    */
        return $regressionArray;
    }

    /**
     * Render HTML output of a given testuite XML result
     * @todo clean up, retrieve xsl stuff from dedicated file
     *
     * @param String $output JUNIT XML output
     *
     * @return String
     */
    function processResult($output) {
$xslString = '<?xml version="1.0" encoding="UTF-8"?>
<html xsl:version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">
    <body style="width:650px;font-family:Arial;font-size:11pt;background-color:#EEEEEE">
        <h2>Summary</h2>
        <xsl:variable name="testCount" select="sum(testsuite/@tests)"/>
        <xsl:variable name="errorCount" select="sum(testsuite/@errors)"/>
        <xsl:variable name="failureCount" select="sum(testsuite/@failures)"/>
        <xsl:variable name="timeCount" select="sum(testsuite/@time)"/>
        <xsl:variable name="successRate" select="($testCount - $failureCount - $errorCount) div $testCount"/>
        <table border="0" cellpadding="5" cellspacing="2" width="95%">
            <tr style="background-color:#629628;color:white;valign:top;font-size:12pt">
                <td><strong>Tests</strong></td>
                <td><strong>Failures</strong></td>
                <td><strong>Errors</strong></td>
                <td><strong>Success rate</strong></td>
                <td><strong>Time</strong></td>
            </tr>
            <tr style="background-color:#629628;color:white;valign:top">
                <td><xsl:value-of select="$testCount"/></td>
                <td><xsl:value-of select="$failureCount"/></td>
                <td><xsl:value-of select="$errorCount"/></td>
                <td><xsl:value-of select="$successRate"/></td>
                <td><xsl:value-of select="$timeCount"/></td>
            </tr>
        </table>
    <br/>
        <xsl:for-each select="testsuite/testcase">
            <div style="color:white;padding-bottom:10px">
            <xsl:choose>
                <xsl:when test="failure"><br/><div style="background-color:#ff0000;font-weight:bold"><xsl:value-of select="@name"/> (<xsl:value-of select="@time"/>s)</div><div style="background-color:#000000;font-size:11pt;color:white"><b>Status:    Failure</b><br/></div></xsl:when>
                <xsl:otherwise><br/><div style="background-color:#4DBD33;font-weight:bold"><xsl:value-of select="@name"/> (<xsl:value-of select="@time"/>s)</div><div style="background-color:#000000;font-size:11pt;color:white"><b>Status:    Success</b><br/><br/> </div></xsl:otherwise>
            </xsl:choose>
            <xsl:for-each select="failure">
                 <div style="background-color:#000000;font-size:11pt"><br/><b>Type:    </b><xsl:value-of select="@type"/><br/></div>
                   <div style="background-color:#000000;font-size:11pt"><b>Message:    </b> <xsl:value-of select="@message"/><br/></div><br/>
            </xsl:for-each>
                <span style="font-style:italic">
                    <xsl:value-of select="error"/>
                </span>
            </div>
        </xsl:for-each>
    </body>
</html>
';
        $xmlDoc = simplexml_load_string($output);
        $xslDoc = simplexml_load_string($xslString);
        $proc   = new XSLTProcessor;
        $proc->importStyleSheet($xslDoc);
        return $proc->transformToXML($xmlDoc);
    }

    /**
     * Check if testsuite output is a  valid XML
     *
     * @param String $output JUNIT XML output
     *
     * @return string
     */
    function validateXML($output) {
        $errorString = "";
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($output);
        if (!$xml) {
            $errorString .= "There is something wrong with testsuite output: <br/>";
            foreach (libxml_get_errors() as $error) {
                $errorString .= "<br/>*) ".$error->message;
            }
        }
        return $errorString;
    }

    /**
     * Download an execution results
     *
     * @param Integer $id Id of the result to download
     *
     * @return Void
     */
    function downloadResult($id) {
        $sql  = "SELECT * FROM result
                 WHERE id = ".$id."
                   AND user = ".$this->user->getAtt('id');
        $result = $this->dbHandler->query($sql);
        if($result) {
            $result->setFetchMode(PDO::FETCH_OBJ);
            $row = $result->fetch();
            header("Content-Type: application/force-download");
            header('Content-Disposition: filename="'.$row->name.'_'.$row->date.'.txt"');
            echo $row->output;
            echo "\n\n";
            echo "Run in ".date("D M j, Y G:i:s T", $row->date);
            exit;
        }
    }

    /**
     * Download testsuite corresponding to a result
     *
     * @param Integer $id Id of the testsuit's result
     *
     * @return Void
     */
    function downloadTestSuite($id) {
        $sql    = "SELECT * FROM result
                   WHERE id = ".$id."
                   AND user = ".$this->user->getAtt('id');
        $result = $this->dbHandler->query($sql);
        if($result && $result->rowCount() > 0) {
            $result->setFetchMode(PDO::FETCH_OBJ);
            $row = $result->fetch();
            header("Content-Type: application/force-download");
            header('Content-Disposition: filename="'.$row->name.'_'.$row->date.'.rb"');
            echo $row->testsuite;
            exit;
        }
    }

}

?> 