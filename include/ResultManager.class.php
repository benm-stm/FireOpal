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

require_once 'common/db/connect.php';

class ResultManager {

    const RESULTS_PATH = "../log/";

    /**
     * Store the execution result in DB
     *
     * @param Array $output Execution output
     *
     * @return Boolean
     */
    function logNewResult($output) {
        $sql = "INSERT INTO result (output, date) VALUES ('".mysql_real_escape_string(join("\n", $output))."', ".time().")";
        return mysql_query($sql);
    }

    /**
     * Delete an execution result from DB
     *
     * @param Integer $id Id of the result to delete
     *
     * @return Boolean
     */
    function deleteResult($id) {
        $sql = "DELETE FROM result WHERE id=".$id;
        return mysql_query($sql);
    }

    /**
     * Display the list of execution rsults
     *
     * @return String
     */
    function displayResults() {
        $output = '';
        $sql  = "SELECT * FROM result ORDER BY date";
        $result = mysql_query($sql);
        if(mysql_num_rows($result) > 0) {
            $output = '<table>';
            while ($row = mysql_fetch_array($result)) {
                $output .= '
<tr>
    <td>
        <a href="?delete_result='.$row['id'].'" >Delete</a>
    </td>
    <td>
        '.date("D M j, Y G:i:s T", $row['date']).'
    </td>
    <td>
        <fieldset>
            <legend class="toggler" onclick="toggle_visibility(\'result_output_'.$row['id'].'\'); if (this.innerHTML == \'+\') { this.innerHTML = \'-\'; } else { this.innerHTML = \'+\'; }">+</legend>
            <span id="result_output_'.$row['id'].'" style="display: none;" >
                <pre>'.$row['output'].'</pre>
            </span>
        </fieldset>
    </td>
    <td>
        <a href="?download_result='.$row['id'].'" >Download</a>
    </td>
</tr>';
            }
            $output .= '</table>';
        }
        return $output;
    }

    /**
     * Download an execution rsults
     *
     * @param Integer $id Id of the result to download
     *
     * @return Void
     */
    function downloadResult($id) {
        $sql  = "SELECT * FROM result WHERE id=".$id;
        $result = mysql_query($sql);
        if(mysql_num_rows($result) > 0) {
            $row = mysql_fetch_array($result);
            header("Content-Type: application/force-download");
            header('Content-Disposition: filename="result"');
            echo $row['output'];
            echo "\n\n";
            echo "Run in ".date("D M j, Y G:i:s T", $row['date']);
            exit;
        }
    }

}

?>