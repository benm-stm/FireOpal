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

class  clsUsers {

    var $id;
    var $email;
    var $password; 
    var $surname;
    var $familyName;
    var $organisation;
    var $title;
    var $country;
    var $city;
    var $photo;
    var $logo;
    var $typeOrganisation;
    var $activityFocus1;
    var $activityFocus2;
    var $activityFocus3;
    var $titleQualification1;
    var $titleQualification2;
    var $englishLanguage;
    var $sex;
    var $born;
    var $position;
    var $check1;
    var $check2;
    var $check3;
    var $check4;
    var $completeRecording;   
 
    /**
     * Table name
     */
    var $tableName = 'users';

    /**
     * Liste des champs de la table Boutique
     *
     * @var Liste
     */

    var $attributs = array (
              "id" =>"id" ,
              "email"   =>"email" ,
              "password"   =>"password" ,
              "surname"     =>"surname" ,
              "familyName"  =>"familyName" ,
              "organisation"  =>"organisation" ,
              "title"  =>"title" ,
              "country"  =>"country" ,
              "city"  =>"city" ,
              "photo"  =>"photo" ,
              "logo" => "logo",
              "typeOrganisation"  =>"typeOrganisation" ,
              "activityFocus1"  =>"activityFocus1" ,
              "activityFocus2"  =>"activityFocus2" ,
              "activityFocus3"  =>"activityFocus3" ,
              "titleQualification1"  =>"titleQualification1" ,
              "titleQualification2"  =>"titleQualification2" ,
              "englishLanguage"  =>"englishLanguage" ,
              "sex"  =>"sex" ,
              "born"  =>"born" ,
              "check1"  =>"check1" ,
              "check2"  =>"check2" ,
              "check3"  =>"check3" ,
              "check4"  =>"check4" ,
              "position" => "position",
              "completeRecording"  =>"completeRecording" 
           );

    /**
     * Constructor
     *
     * @return clsUser
     */

    function  clsUsers() {
     }

    /**
     * Save user 
     *
     * @param Integer $id 
     */
    function save($id = '') {
    // update a given user
        if($id!='') {
            $query="Update $this->tableName  set ";
            $i=0;
            foreach( $this->attributs as $att=>$bddatt) {
                if($i!=0) {
                    if($this->$att!='') {
                        $query.=" ".$bddatt."='".$this->$att."',";
                    }
                }
                $i++;
            }
            $query_temp = substr($query,0,strlen($query)-1);
            $query      = $query_temp."  where id='".$id."'";
            mysql_query($query);
        } else {
        // insert new user given its id
        $query  = "Insert into $this->tableName (";
        $values = " values (";
        $i = 0;
        foreach( $this->attributs as $att=>$bddatt) {
            if($this->$att != '') {
                $query .= $bddatt.",";
                if($i != 0) {
                    $values .= "'".$this->$att."',";
                }
            }
            $i++;
        }
        $query_temp = substr($query,0,strlen($query)-1);
        $query = $query_temp.")  ";
        $values_temp = substr($values,0,strlen($values)-1);
        $values = $values_temp.")  ";
        $query .= $values ;    
        }
    mysql_query($query);
    }

    /**
     * Load a user given its Id
     *
     * @param Integer $id
     */
    function loadFromId($Id) {
        $field_names=" * ";
        $whereClause="WHERE id=$Id ";
        $query  = "SELECT * FROM ".$this->tableName." ".$whereClause ;
        $result = mysql_query($query);
        if($List = mysql_fetch_array($result)) {
            foreach($this->attributs as $classatt=>$bddatt) {
                $this->$classatt=$List[$bddatt];
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
        $req = "select  * from users where email='".$this->email."' order by id";
        $rows = mysql_query($req);
        $nb_ligne = mysql_num_rows($rows);
        if( $nb_ligne > 0 ) {
            return true;
        } else {
            return false;
        }

    function controlPassword($pseudo, $pass) {
        $pseudo = trim($pseudo);
        $pass   = trim($pass);
        if( $pass != '' and $pseudo != '' ) {
            $req = "select * from users where email = '".$pseudo."' and password = '".$pass."' and completeRecording = '1' ";
            $result = mysql_query($req);
            if( mysql_num_rows($result) > 0 ) {
                $row = mysql_fetch_array($result);
                $ID = $row['id'];
                $this ->loadFromId($ID);
                return 1;
            } else {
                return 0;
            }
        } else {
        return 0;
    }

}
?>