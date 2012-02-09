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

class SetupManager {

    /*
     * Store the setup
     *
     * @param Array $set Setup to store
     *
     * @return Boolean
     */
    function store($set) {
        // TODO: Store $set into a file (could be into a DB in a near future)
        return true;
    }

    /*
     * Load the setup
     *
     * @return Array
     */
    function load() {
        // TODO: Loead the setup from /conf/set.php
        return array();
    }

    /*
     * Display setup form
     *
     * @param Array $set Setup to display
     *
     * @return String
     */
    function display($set) {
        // TODO: Make this thing dynamic
        return '<li class=""><label  for="host">Host:</label> <input  type="text" id="host" name="host" value="http://codex" /></li>
        <li class=""><label  for="client">Client:</label> <input  type="text" id="client" name="client" value="client" /></li>
        <li class=""><label  for="client">Browser:</label> <input  type="text" id="client" name="client" value="firefox" /></li>
        <li class=""><label  for="user">User:</label> <input  type="text" id="user" name="user" value="login" /></li>
        <li class=""><label  for="password">Password:</label> <input  type="password" id="password" name="password" value="password" /></li>
        <li class=""><label  for="project">Project:</label> <input  type="text" id="project" name="project" value="project" /></li>
        <li class=""><label  for="projectId">Project ID:</label> <input  type="text" id="projectId" name="projectId" value="1" /></li>
        <li class=""><label  for="tracker">Tracker:</label> <input  type="text" id="tracker" name="tracker" value="tracker" /></li>
        <li class=""><label  for="trackerName">Tracker name:</label> <input  type="text" id="trackerName" name="trackerName" value="trackerName" /></li>
        <li class=""><label  for="trackerShortName">Tracker short name:</label> <input  type="text" id="trackerShortName" name="trackerShortName" value="trk" /></li>
        <li class=""><label  for="docmanRootId">Docman root ID:</label> <input  type="text" id="docmanRootId" name="docmanRootId" value="1" /></li>';
    }

}

?>
