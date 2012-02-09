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
     * Store the setup
     *
     * @param Array $set Setup to store
     *
     * @return Boolean
     */
    function store($set) {
        file_put_contents(dirname(__FILE__).'/../conf/set.ini', serialize($set));
        return true;
    }

    /*
     * Load the setup
     *
     * @return Array
     */
    function load() {
        $set = unserialize(file_get_contents(dirname(__FILE__).'/../conf/set.ini'));
        return $set;
    }

    /*
     * Display setup form
     *
     * @param Array $set Setup to display
     *
     * @return String
     */
    function display() {
        $content = '';
        $set = $this->load();
        foreach ($set as $element) {
            if (true) {
                $content .= '<li <span title="'.$element['description'].'"></span><label for="'.$element['name'].'">'.$element['name'].': </label><input id='.$element['name'].' type='.$element['type'].' name="'.$element['name'].'" value="'.$element['value'].'" /></li>';
            }
        }
        return $content;
    }

}

?>
