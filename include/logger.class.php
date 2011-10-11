<?php
/**
 * Copyright (c) STMicroelectronics 2011. All rights reserved
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

interface seleniumWriter {
    public function write();
}

interface seleniumFormatter {
    public function format($output);
}

class logger {

    private $file = null;

    public function __construct($file) {
        $this->file = $file;
    }
  
    public function write ($message) {
        file_put_contents($this->file, array(PHP_EOL, $message), FILE_APPEND);
    }
}


class Formatter_String implements seleniumFormatter {
    public function format($message) {
        $timestamp = time();
        $xml = XML_EOL."       
                <message>
                <time>$timestamp</time>
                <text>$message</text>
                </message>".XML_EOL;
        return $xml.PHP_EOL;
     }
}


?>
