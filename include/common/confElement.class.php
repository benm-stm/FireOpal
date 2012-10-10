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
    public static $confTemplate = array('host' => 'http://localhost', 'client' => '107.0.0.1', 'browser' => 'firefox', 'user' => 'admin', 'password' => '*****', 'project' => 'Codex', 'project_id' => '101');

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
    }

    function saveElement($userId, $name, $value, $type = '', $description = '') {
        if (empty($type)) {
            $type = 'undefined';
        }
        if (!empty($description)) {
            $query  = "Insert into $this->tableName (`userId`, `name`, `type`, `value`, `Description`)";
            $values = " values ('".$userId."','".$name."', '".$type."', '".$value."','".$description."')";
        } else {
            $query  = "Insert into $this->tableName (`userId`, `name`, `type`, `value`)";
            $values = " values ('".$userId."','".$name."', '".$type."', '".$value."')";
        }
        $query .= $values;
        $this->dbHandler->query($query);
    }

    function updateElement($userId, $name, $value) {
        if (!empty($value)) {
            $query  = "UPDATE $this->tableName SET `value` = '$value' WHERE `userId` = $userId and `name` = '$name'";
            return $this->dbHandler->exec($query);
        }
    }

    /**
     * Load a configuration element given User Id
     *
     * @param Integer $userId
     */
    function getConfElementsByUserId($userId) {
        $whereClause = "WHERE `userId` = $userId ";
        $query       = "SELECT * FROM ".$this->tableName." ".$whereClause ;
        return $this->dbHandler->query($query);
    }

    /**
     * Delete a configuration element given its name and its user id
     *
     */
    function deleteElement($userId, $name) {
        $name  = $this->dbHandler->quote($name);
        $sql   = "DELETE FROM $this->tableName WHERE `userId` = :userId AND `name` = :name";
        $sth   = $this->dbHandler->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $count = $sth->execute(array(':userId' => $userId, ':name' => $name));
        if ($count > 0) {
          return TRUE;
        }
        return FALSE;
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