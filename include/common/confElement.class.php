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
require_once('db/connect.php');
require_once('User.class.php');

class confElement {

    private $dbHandler;
    private $userId;

    /**
     * Table name
     */
    private $tableName = 'configuration';

    /**
     * Constructor
     *
     * @return confElement
     */

    function  confElement($userId) {
        $this->dbHandler = DBHandler::getInstance();
        $this->userId    = $userId;
        var_dump($userId);
    }

    /**
     * Save user configuration
     *
     * @param Integer $userId 
     */
    function save($userId = '') {
    }

    /**
     * Load a configuration element given its Id
     *
     * @param Integer $id
     */
    function getConfElemenyById($Id) {
        $whereClause = "WHERE id=$Id ";
        $query       = "SELECT * FROM ".$this->tableName." ".$whereClause ;
        return $this->dbHandler->query($query);
    }

    function getName() {
        return $this->name;
    }

    function getType() {
        return $this->type;
    }

    function getDescription() {
        return $this->type;
    }

    function setAtt($att,$value) {
        $this->$att=$value;
    }

    function isConfElementExistant($ConfEltName) {
        $req  = "select  * from $this->tableName where name = '".$ConfEltName."' and userId = '".$this->$userId."' order by id";
        $rows = $this->dbHandler->query($req);
        $nb_ligne = mysql_num_rows($rows);
        if ( $nb_ligne > 0 ) {
            return true;
        } else {
            return false;
        }
     }

}
?>