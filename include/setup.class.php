<?php
/**
 * Copyright (c) STMicroelectronics 2011. All rights reserved
 *
 * Tuleap is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Tuleap is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Tuleap. If not, see <http://www.gnu.org/licenses/>.
 */


    /**
    * class to be extended in order to define an element of configuration
    */
    abstract class confElement {
        public $title = 'Undefined';
        public abstract function doPost();
        public abstract function getIHM();
    }

    /**
    * Manage the edition of set.php
    *
    */
    class setup {
        private static $instance;
        private $_confElements = array();

    /**
     * Singleton ref
     */
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new setup();
            }
        return self::$instance;
    }

    /**
     * Define a configuration element
     */
    public function addConfigurationEelement(confElement $confElement) {
        $this->_confElements[] = $confElement;
    }

    /**
     * Retrieve configuration Element by index
     */
    public function getConfigurationEelement($index) {
        return $this->_confElements[$index];
    }    
    
    public function updateConfigFile() {
        $conf = file_get_contents(dirname(__File__).'/conf.inc.php');
            foreach($this->_confElements as $element) {
               $element->doPost();
               $conf = str_replace('@'.$element->title.'@',$_REQUEST[$element->title], $conf);
               }
        $fd = @fopen(dirname(__FILE__).'/set.php', 'w+');
        if(!$fd) {
            throw new Exception ('Unable to create configuration file.');
         }
        fputs($fd, $conf);
        fclose($fd);
   }
    
   public function __toString() {
       foreach($this->_confElements as $element)
       print $element->getIHM();
   }
}
?>
