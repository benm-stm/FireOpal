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
     * @param $output
     *
     * @return Boolean
     */
    function logNewResult($output) {
        $sql = "INSERT INTO result (output, date) VALUES ('".mysql_real_escape_string(join("\n", $output))."', ".time().")";
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
                $output .= "<tr><td>".date(DATE_RFC822, $row['date'])."</td><td><pre>".$row['output']."</pre></td></tr>";
            }
            $output .= '</table>';
        }
        return $output;
    }

}

?>