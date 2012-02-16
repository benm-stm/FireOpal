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

class testCase {

    public $id;
    public $name;
    public $_dependenciesMap;
    public $_flagsMap;
    public $file;

    /**
     * Constructor
     */
    public function __construct($name, $fileinfo = null) {
        $this->id               = "";
        $this->name             = $name;
        $this->_dependenciesMap = array();
        $this->_flagsMap        = array();
        if (!empty($fileinfo)) {
            $this->_testCaseFile = $fileinfo;
        } else {
            $this->_testCaseFile = new SplFileInfo(dirname(__FILE__).'/../testcases/'.$this->name.'.rb');
        }
    }

    public function getContent() {
        return true;
    }

    public function retrieveRspecExampleGroup() {
        $exampleGroup = "    describe \"".$this->name."\" do\n\n";
        $exampleGroup .= $this->getContent();
        $exampleGroup .= "    end\n";
        return $exampleGroup;
    }

    protected function getDependencies() {
        return $this->_dependenciesMap;
    }

    protected function setDependencies($dependenciesArray) {
        $this->_dependenciesMap = $dependenciesArray;
    }

    protected function getFlags() {
        return $this->$_flagsMap;
    }

    protected function setFlags($flagsArray) {
        $this->$_flagsMap = $flagsArray;
    }

}

?>
