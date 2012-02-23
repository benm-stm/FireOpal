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

    // TODO: Add function comment
    function displayFileSystem($directory) {
        $iter = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::KEY_AS_FILENAME | FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST);
        $output = '<select align=top name="testCases" size=10  style="width:320px" multiple="multiple">';
        foreach ($iter as $entry) {
            // TODO: Don't put the absolute path on the server
            if ($entry->isDir()) {
                $output .= '<option value="'.$entry->getRealPath().'" disabled></b>'.$entry->getFilename().'</b></option>';
            } else {
                $output .= '<option value="'.$entry->getRealPath().'">&nbsp;&nbsp;&nbsp;&nbsp;'.$entry->getFilename().'</option>';
            }
        }
        $output .= '<select>';
        return $output;
    }

}

?>
