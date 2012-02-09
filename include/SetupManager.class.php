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

class SetupManager {

    /*
     * Extract the setup values from HTTP request and validate them
     *
     * @param Array $request Request containing setup values
     *
     * @return Array
     */
    function extractSetup($request) {
        $set            = $this->load();
        $newName        = null;
        $newType        = null;
        $newDescription = null;
        if ($request && is_array($request)) {
            foreach ($request as $name => $value) {
                if (isset($value)) {
                    switch ($name) {
                        case "new_name" :
                            if ($value != 'new_name' &&
                                $value != 'new_type' &&
                                $value != 'new_description' &&
                                !array_key_exists($value, $set)) {
                                $newName = $value;
                            }
                            break;
                        case "new_type" :
                            if ($value == 'text' || $value == 'password') {
                                $newType = $value;
                            }
                            break;
                        case "new_description" :
                            $newDescription = $value;
                            break;
                        case "host"   :
                            if (preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $value)) {
                                $set[$name]['value'] = $value;
                            }
                        case "client" :
                            if (preg_match('|^[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?$|i', $value)) {
                                $set[$name]['value'] = $value;
                            }
                            break;
                        case "browser" :
                            // TODO: add more possible browsers
                            if ($value == "firefox" || $value == "ie" || $value == "chrome") {
                                $set[$name]['value'] = $value;
                            }
                            break;
                        case "user"     :
                        case "password" :
                        case "project"  :
                            if (is_string($value) &&
                                strrpos($value,' ') === false &&
                                strspn($value,"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ") != 0 &&
                                strspn($value,"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_.") === strlen($value) &&
                                strpos($value, 0x0A) === false &&
                                strpos($value, 0x0D) === false &&
                                strpos($value, 0x00) === false) {
                                $set[$name]['value'] = $value;
                            }
                            break;
                        case "project_id" :
                            if (preg_match("/^\d+$/", $value)) {
                                $set[$name]['value'] = $value;
                            }
                            break;
                        default:
                            $set[$name]['value'] = $value;
                    }
                }
            }
        }
        if ($newName && $newType && $newDescription) {
            $set[$newName] = array("value" => "", "description" => $newDesciption, "type" => $newType);
        }
        return $set;
    }

    /*
     * Store the setup passed in HTTP request
     *
     * @param Array $request Request containing setup values
     *
     * @return Boolean
     */
    function store($request) {
        if ($set = $this->extractSetup($request)) {
            return file_put_contents(dirname(__FILE__).'/../conf/set.ini', json_encode($set));
        }
        return false;
    }

    /*
     * Load the setup
     *
     * @return Array
     */
    function load() {
        return json_decode(file_get_contents(dirname(__FILE__).'/../conf/set.ini'), true);
    }

    /*
     * Display setup form
     *
     * @param Boolean $readOnly Dsplay in read only if true
     *
     * @return String
     */
    function display($readOnly = false) {
        if ($readOnly) {
            $readOnly = 'readonly="readonly"';
        } else {
            $readonly = '';
        }
        $content = '';
        $set = $this->load();
        foreach ($set as $name => $element) {
            if (true) {
                $content .= '<li <span title="'.$element['description'].'"></span><label for="'.$name.'">'.$name.': </label><input id='.$name.' type='.$element['type'].' name="'.$name.'" value="'.$element['value'].'" '.$readOnly.' /></li>';
            }
        }
        return $content;
    }

}

?>
