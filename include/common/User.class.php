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
require_once('confElement.class.php');

class  user {

    var $id;
    var $email;
    var $password; 
    var $surname;
    var $familyName;
    var $organisation;
    var $login;
    var $completeRecording;
    var $dbHandler;

    /**
     * Table name
     */
    var $tableName = 'user';

    var $attributs = array (
              "id"                => "id",
              "email"             => "email",
              "password"          => "password",
              "surname"           => "surname",
              "familyName"        => "familyName",
              "organisation"      => "organisation",
              "login"             => "login",
              "completeRecording" => "completeRecording" 
           );

    /**
     * Constructor
     *
     * @return user
     */

    function  user() {
        $this->dbHandler    = DBHandler::getInstance();
    }

    /**
     * Save user 
     *
     * @param Integer $id 
     */
    function save($id = '') {
    // update a given user
        if ($id != '') {
            $query = "Update $this->tableName  set ";
            $i     = 0;
            foreach ( $this->attributs as $att=>$bddatt) {
                if ($i != 0) {
                    if ($this->$att != '') {
                        $query .= " ".$bddatt."='".$this->$att."',";
                    }
                }
                $i++;
            }
            $query_temp = substr($query,0,strlen($query)-1);
            $query      = $query_temp."  where id='".$id."'";
            $this->dbHandler->query($query);
        } else {
        // insert new user given its id
        $query  = "Insert into $this->tableName (";
        $values = " values (";
        $i      = 0;
        foreach ( $this->attributs as $att=>$bddatt) {
            if ($this->$att != '') {
                $query .= $bddatt.",";
                if ($i != 0) {
                    $values .= "'".$this->$att."',";
                }
            }
            $i++;
        }
        $query_temp  = substr($query,0,strlen($query)-1);
        $query       = $query_temp.")  ";
        $values_temp = substr($values,0,strlen($values)-1);
        $values      = $values_temp.")  ";
        $query      .= $values ;    
        }
    $this->dbHandler->query($query);
    $this->initUserConfElements($this->dbHandler->lastInsertId());
    }

    /**
     * Init conf elements for a given user
     *
     * @param Integer $userId
     */
    private function initUserConfElements($userId) {
        $confElement = new confElement($userId);
        foreach ($confElement::$confTemplate as $element=>$value) {
            $confElement->saveElement($userId, $element, $value);
        }
    }

    /**
     * Load a user given its Id
     *
     * @param Integer $id
     */
    function loadFromId($Id) {
        $whereClause = "WHERE id=$Id ";
        $query       = "SELECT * FROM ".$this->tableName." ".$whereClause ;
        $result      = $this->dbHandler->query($query);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        if ($List = $result->fetch()) {
            foreach ($this->attributs as $classatt=>$bddatt) {
                $this->$classatt = $List[$bddatt];
            }
        }
    }

     function getAtt($att) {
        return $this->$att;
    }

    function setAtt($att,$value) {
        $this->$att=$value;
    }

    function userExist() {
        $req  = "select  * from $this->tableName where login = '".$this->login."' order by id";
        $rows = $this->dbHandler->query($req);
        if ( $rows->rowCount() > 0 ) {
            return true;
        } else {
            return false;
        }
     }

    function controlPassword($pseudo, $pass) {
        $pseudo = trim($pseudo);
        $pass   = trim($pass);
        if ($pass != '' and $pseudo != '') {
            $req    = "select * from $this->tableName where login = '".$pseudo."' and password = '".$pass."'";// and completeRecording = '1' ";
            $result = $this->dbHandler->query($req);
            if ( $result->rowCount() > 0 ) {
                $result->setFetchMode(PDO::FETCH_OBJ);
                $row = $result->fetch();
                $ID = $row->id;
                $this ->loadFromId($ID);
                return 1;
            } else {
                return 0;
            }
        } else {
        return 0;
        }
    }

    function initWorkSpace() {
        if ($this->userExist()) {
            if (@mkdir("../workspaces/".$this->familyName)) {
                echo "Workspace initiliazed successfully";
                return true;
            } else {
                echo "Something went wrong when trying to setup workspace";
                return false;
            }
        }
    }

    function sendMail($temp_passe, $max ) {
        $From    = "From:tester@codex.cro.st.com\n";
        $From   .= "MIME-version: 1.0\n";
        $From   .= "Content-type: text/html; charset= iso-8859-1\n";
        $Sujet   = 'Activate your account';
        $link    = ' <a href="http://crx2106.cro.st.com/confirm.php?id1='.$max.'&id2='.$temp_passe.'">Click here</a>';
        $Message = '
            <html>
            <head>
                <style type="text/css" >
                    .table_parcours{
                    border-top:#002222 1px solid;
                    border-left:#002222 1px solid;
                    border-right:#002222 1px solid;
                    border-bottom:#002222 1px solid;
                    font-family:Arial, Helvetica, sans-serif 12px;
                    }
                </style>
            </head>
            <body>
                <table cellspacing="0" cellpadding="0" class="table_parcours" align="center" >
                    <tr>
                        <td width="438"  align="left"  >
                        <img src="http://codex.cro.st.com/themes/STTab/images/organization_logo.png" border="0" /> </td>
                    </tr>
                    <tr>
                        <td width="438" bgcolor="#FF8000"  ><font color="#FFFFFF" >Welcome</font></td>
                    </tr>
                    <tr >
                        <td width="438" style="padding-left:5px;" >
                        <p class="texte">
                        Your E-mail : <strong>'.$this->email.'</strong><br>
                        Your username : <strong>'.$this->surname.'</strong><br>
                        Your password :<strong>'.$this->password.'</strong><br>
                        In order to activate your account, you should '.$link.'<br>
                        </p>
                        </td>
                    </tr> 
                </table>
            </body>
        </html>
        ';
    @mail($this->email,$Sujet,$Message,$From);
    }

}
?>