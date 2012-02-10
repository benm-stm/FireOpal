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

ini_set('include_path', ini_get('include_path').':'.dirname(__FILE__).'/../include/');
require_once 'SetupManager.class.php';
$setupManager = new SetupManager();
if (!empty($_REQUEST)) {
    $setupManager->store($_REQUEST);
    if (isset($_REQUEST['delete']) && !empty($_REQUEST['delete'])) {
        $setupManager->delete($_REQUEST['delete']);
    }
}

$content = $setupManager->display();
$form    = $content['form'];
$error   = implode("<br />", $content['error']);

echo '
<html>
    <head>
        <title>Codex integration tests (update config)</title>
        <link href="include/css/index.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <table>
            <tr>
                <td nowrap>
                    <font color="red">'.$error.'</font>
                </td>
            </tr>
            <tr>
                <td nowrap>
                    <a href="index"><< Go back</a>
                    <form action="" method="POST">
                        <fieldset>
                            <legend>Config</legend>
                            <ul id="menu"><li class="">
                                '.$form.'
                            </ul> 
                        </fieldset>
                        <fieldset>
                            <legend>New value</legend>
                            <table>
                                <tr>
                                    <td>Name</td>
                                    <td><input name="new_name" /></td>
                                </tr>
                                <tr>
                                    <td>Type</td>
                                    <td><input name="new_type" /></td>
                                </tr>
                                <tr>
                                    <td>Description</td>
                                    <td><input name="new_description" /></td>
                                </tr>
                            </table>
                        </fieldset>
                        <div id="submit_panel"><input type="submit" value="Update config !" /></div>
                    </form>
                </td>
            </tr>
        </table>
    </body>
</html>';

?>
