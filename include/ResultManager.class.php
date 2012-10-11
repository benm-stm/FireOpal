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

class ResultManager {

    const RESULTS_PATH = "../log/";

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
            var_dump($e);
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
        $sql  = "SELECT * FROM result
                 WHERE user = ".$this->user->getAtt('id')."
                 ORDER BY date";
        try {
            $result = $this->dbHandler->query($sql);
                if($result && $result->rowCount() > 0) {
                $output = '<div><br><table>
                <tr>
                <td class="resultHeader">Testsuite</td>
                <td class="resultHeader">Run date</td>
                <td class="resultHeader">Output</td>
                <td class="resultHeader">Download output</td>
                <td class="resultHeader">Delete</td>
                </tr>';
                $result->setFetchMode(PDO::FETCH_OBJ);
                while ($row = $result->fetch()) {
                    $output    .= '
    <tr>
        <td id="resultLink">
            <a href="?download_testsuite='.$row->id.'" >'.$row->name.'</a>
        </td>
        <td id="resultDate">
            '.date("D M j, Y G:i:s T", $row->date).'
        </td>
        <td>
            <fieldset class="fieldset">
                <legend class="toggler" onclick="toggle_visibility(\'result_output_'.$row->id.'\'); if (this.innerHTML == \'+\') { this.innerHTML = \'-\'; } else { this.innerHTML = \'+\'; }">+</legend>
                <span id="result_output_'.$row->id.'" style="display: none;" >
                    <pre>'.$row->output.'</pre>
                </span>
            </fieldset>
        </td>
        <td id="resultLink">
            <a href="?download_result='.$row->id.'" >Download</a>
        </td>
        <td id="resultLink">
            <a href="?delete_result='.$row->id.'" >Delete</a>
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
        if($result && $result->rowCount() > 0) {
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