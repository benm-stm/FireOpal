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
require_once('common/confElement.class.php');

class Setup {

    private $error = array();
    private $info  = array();

    /**
     * Extract the setup values from HTTP request and validate them
     *
     * @param Array $request Request containing setup values
     *
     * @return Array
     */
    function extractSetup($request = null) {
        $set            = $this->load();
        $newName        = null;
        $newType        = null;
        $newDescription = null;
        if ($request && is_array($request)) {
            foreach ($request as $name => $value) {
                if (isset($value)) {
                    switch ($name) {
                        case "delete" :
                        case "testsuite_name" :
                        case "testsuite_description" :
                        case "testcases_to_add" :
                        case "testcases_to_add_str" :
                            break;
                        case "new_name" :
                            if ($value != 'new_name' &&
                                $value != 'new_type' &&
                                $value != 'new_description' &&
                                $value != 'delete' &&
                                !array_key_exists($value, $set)) {
                                $newName = $value;
                            } else {
                                $this->error[] = "Name is reserved or already exist";
                            }
                            break;
                        case "new_type" :
                            if (!empty($value)) {
                                if ($value == 'text' || $value == 'password') {
                                    $newType = $value;
                                } else {
                                    $this->error[] = "Type must be either 'text' or 'password' '".$value."' ".$name;
                                }
                            }
                            break;
                        case "new_description" :
                            $newDescription = $value;
                            break;
                        case "host"   :
                            if (preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $value)) {
                                $set[$name]['value'] = $value;
                            } else {
                                $this->error[] = "Invalid host";
                            }
                            break;
                        case "client" :
                            if (preg_match('|^[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?$|i', $value)) {
                                $set[$name]['value'] = $value;
                            } else {
                                $this->error[] = "Invalid client";
                            }
                            break;
                        case "browser" :
                            // TODO: add more possible browsers
                            if ($value == "firefox" || $value == "ie" || $value == "chrome") {
                                $set[$name]['value'] = $value;
                            } else {
                                $this->error[] = "Invalid browser, must be 'firefox', 'ie' or 'chrome'";
                            }
                            break;
                        case "user"     :
                        case "password" :
                        case "project"  :
                            if (!empty($value) &&
                                is_string($value) &&
                                strpos($value, 0x0A) === false &&
                                strpos($value, 0x0D) === false &&
                                strpos($value, 0x00) === false) {
                                $set[$name]['value'] = $value;
                            } elseif (empty($value)) {
                                // Do nothing
                            } else {
                                $this->error[] = "Invalid ".$name;
                            }
                            break;
                        case "project_id" :
                            if (preg_match("/^\d+$/", $value)) {
                                $set[$name]['value'] = $value;
                            } else {
                                $this->error[] = "Invalid ".$name;
                            }
                            break;
                        default:
                            if (!empty($value)) {
                                $set[$name]['value'] = $value;
                            } else {
                                $this->error[] = "Empty value for '".$name."'";
                            }
                    }
                } else {
                    $this->error[] = "No value for '".$name."'";
                }
            }
        } else {
            $this->error[] = "Invalid request";
        }
        if ($newName && $newType && $newDescription) {
            $set[$newName] = array("value" => "", "description" => $newDescription, "type" => $newType);
        } elseif ($newName || $newDescription) {
            $this->error[] = "Both Name & Description are mandatory for a new value";
        }
        return $set;
    }
    
    /**
     * Store the setup passed in HTTP request for the testSuite
     *
     * @param String $filePath
     *
     * @return Boolean
     */
    function storeConf($filePath) {
        if ($set = $this->extractSetup()) {
            $content = "#--- Start Conf in setup here\n";
            foreach ($set as $name => $entry) {
                if ($entry['type'] != 'password') {
                    $content .= "# ".$name." = ".$entry['value']." ( ".$entry['description']." )\n";
                }
            }
            $content .= "#--- End Conf\n\n";
            if (file_put_contents($filePath, $content) === false) {
                $this->error[] = "Impossible to save conf in testsuite";
                return false;
            }
            return true;
        }
        $this->error[] = "Empty setup";
        return false;
    }
    
    /**
     * Store the setup passed in HTTP request
     *
     * @param Array  $request Request containing setup values
     * @param String $filePath 
     *
     * @return Boolean
     */
    function store($request) {
        if ($set = $this->extractSetup($request)) {
            if(file_put_contents(dirname(__FILE__).'/../conf/set.ini', json_encode($set)) === false) {
                $this->error[] = "Impossible to save new conf";
                return false;
            }
            $this->info[] = "Setup stored";
            return true;
        }
        $this->error[] = "Empty setup";
        return false;
    }

    /**
     * Delete setup items
     *
     * @param Array $items Names of items to delete
     *
     * @return Boolean
     */
    function delete($names) {
        $set = $this->load();
        $mandatory = array('host', 'client', 'browser', 'user', 'password', 'project', 'project_id');
        foreach ($names as $name) {
            if (isset($set[$name]) && !(in_array($name, $mandatory))) {
                unset($set[$name]);
            } else {
                $this->error[] = "Impossible to delete ".$name;
            }
        }
        if(file_put_contents(dirname(__FILE__).'/../conf/set.ini', json_encode($set)) === false) {
            $this->error[] = "Impossible to save new conf";
            return false;
        }
        $this->info[] = "Entrie(s) deleted";
        return true;
    }

    /**
     * Load the setup
     *
     * @return Array
     */
    function load() {
        if ($set = json_decode(file_get_contents(dirname(__FILE__).'/../conf/set.ini'), true)) {
            return $set;
        }
        $this->error[] = "Impossible to load conf from file";
        return false;
    }

    /**
     * Load the setup for a given user
     *
     * @return Array
     */
    function loadUserConf($userId) {
        $fullConf = array();
        try {
            $confElement = new confElement($userId);
            $userConf    = $confElement->getConfElementsByUserId($userId);
            if($userConf && $userConf->rowCount() > 0) {
                $elements = $userConf->fetchAll(PDO::FETCH_OBJ);
                foreach ($elements as $element) {
                    $fullConf[$element->name]['label']       = $element->name;
                    $fullConf[$element->name]['value']       = $element->value;
                    $fullConf[$element->name]['type']        = $element->type;
                    $fullConf[$element->name]['description'] = $element->Description;
                }
            }
        } catch(PDOException $e) {
            $this->error[] = $e->getMessage();
        }
    return $fullConf;
    }

    /**
     * Display setup form
     *
     * @param Boolean $readOnly Dsplay in read only if true
     *
     * @return String
     */
    function display($readOnly = false) {
        if ($readOnly) {
            $readOnly = 'readonly="readonly"';
        }
        $content = '';
        $set = $this->load();
        $mandatory = array('host', 'client', 'browser', 'user', 'password', 'project', 'project_id');
        foreach ($set as $name => $element) {
            if ($element['type'] == 'password') {
                $element['value'] = '';
            }
            $content .= '<li><span title="'.$element['description'].'" class="confElement"><label for="'.$name.'">'.$name.':</label><input id="'.$name.'" type="'.$element['type'].'" name="'.$name.'" value="'.$element['value'].'" '.$readOnly.' /></span>';
            if (!$readOnly && (!in_array($name, $mandatory))) {
                $content .= ' delete<input type="checkbox" name="delete[]" value="'.$name.'" />';
            }
            $content .= '</li>';
        }
        return array("form" => $content, "error" => $this->error, "info" => $this->info);
    }

    /**
     * Display user setup form
     *
     * @param Boolean $readOnly Dsplay in read only if true
     *
     * @return String
     */
    function displayUserConf($userId, $readOnly = false) {
        $elements = $this->loadUserConf($userId);
        $content = '';
        if ($readOnly) {
            $readOnly = 'readonly="readonly"';
        }
        $mandatory = array('host', 'client', 'browser', 'user', 'password', 'project', 'project_id');
        foreach ($elements as $name => $element) {
            if ($element['type'] == 'password') {
                $element['value'] = '';
            }
            $content .= '<li><span title="'.$element['description'].'" class="confElement">';
            $content .= '<label for="'.$name.'">'.$name.':</label>';
            $content .= '<input id="'.$name.'" type="'.$element['type'].'" name="'.$name.'" value="'.$element['value'].'" '.$readOnly.' />';
            $content .= '</span>';
            if (!$readOnly && (!in_array($name, $mandatory))) {
                $content .= ' delete<input type="checkbox" name="delete[]" value="'.$name.'" />';
            }
            $content .= '</li>';

        }
        return $content;
    }

    function prepareNewSetupElement($request) {
        $newName        = null;
        $newType        = null;
        $newDescription = null;
        $newConfElement = array();
        if ($request && is_array($request)) {
            foreach ($request as $name => $value) {
                if (isset($value)) {
                    switch ($name) {
                        case "new_name" :
                            if ($value != 'new_name' &&
                                $value != 'new_type' &&
                                $value != 'new_description' &&
                                $value != 'delete') {
                                $newName = $value;
                            } else {
                                $this->error[] = "Name is reserved or already exist";
                            }
                            break;
                        case "new_type" :
                            if (!empty($value)) {
                                if ($value == 'text' || $value == 'password') {
                                    $newType = $value;
                                } else {
                                    $this->error[] = "Type must be either 'text' or 'password' '".$value."' ".$name;
                                }
                            }
                            break;
                        case "new_description" :
                            $newDescription = $value;
                            break;
                    }
                }
            }
        }
        if ($newName && $newType && $newDescription) {
            $newConfElement['name']        = $newName;
            $newConfElement['value']       = "";
            $newConfElement['description'] = $newDescription;
            $newConfElement['type']        = $newType;
        } elseif ($newName || $newDescription) {
            $this->error[] = "Both Name & Description are mandatory for a new value";
        }
        return $newConfElement;
    }

    function storeInDB($userId, $request) {
        $confElement = new confElement($userId);
        $newConfElement = $this->prepareNewSetupElement($request);
        if (!empty($newConfElement)) {
            try {
                $confElement->saveElement($userId, $newConfElement['name'], $newConfElement['value'], $newConfElement['type'], $newConfElement['description']);
            } catch(PDOException $e) {
                $this->error[] = $e->getMessage();
            }
        }
        if ($request && is_array($request)) {
            foreach ($request as $name => $value) {
                if (isset($value)) {
                    if ($value != 'new_name' && $value != 'new_type' && $value != 'new_description' && $value != 'delete') {
                        try {
                            $confElement->updateElement($userId, $name, $value);
                        } catch(PDOException $e) {
                            $this->error[] = $e->getMessage();
                        }
                    }
                }
            }
        }
    }

    /**
     * Delete setup items from DB
     *
     * @param Array $items Names of items to delete
     *
     * @return void
     */
    function deleteFromDB($userId, $names) {
        $confElement = new confElement($userId);
        $mandatory   = array('host', 'client', 'browser', 'user', 'password', 'project', 'project_id');
        foreach ($names as $name) {
            if (!(in_array($name, $mandatory))) {
                try {
                    $removedConfElement = $confElement->deleteElement($userId, $name);
                    if (!$removedConfElement) {
                        $this->error[] = "Impossible to delete ".$name;
                    } else {
                        $this->info[] = "Entrie(s) deleted";
                    }
                } catch(PDOException $e) {
                    $this->error[] = $e->getMessage();
                }
            }
        }
    }
}

?>