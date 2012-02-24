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

ini_set('include_path', ini_get('include_path').PATH_SEPARATOR.dirname(__DIR__).DIRECTORY_SEPARATOR.'include');
require_once 'Setup.class.php';
$setup = new Setup();
if (!empty($_REQUEST)) {
    $setup->store($_REQUEST);
    if (isset($_REQUEST['delete']) && !empty($_REQUEST['delete'])) {
        $setup->delete($_REQUEST['delete']);
    }
}

$content = $setup->display();
$form    = $content['form'];

$error   = "";
if (!empty($content['error'])) {
    $error   = '<ul class="feedback_error" >';
    foreach ($content['error'] as $message) {
        $error .= "<li>".$message."</li>";
    }
    $error .= '</ul>';
}

echo '
<html>
    <head>
        <title>Codex automatic validation (update config)</title>
        <link href="include/css/index.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div id="header">';
echo $error;
echo '
            <a href="cases.php" class="community"><< Go back</a>
        </div>
        <div id="header_clear">
        </div>
        <div id="body_skin">
            <table>
                <tr>
                    <td nowrap>
                        <form action="" method="POST">
                            <fieldset>
                                <legend><b>Config</b></legend>
                                <ul id="menu"><li class="">
                                    '.$form.'
                                </ul> 
                            </fieldset>
                            <fieldset>
                                <legend><b>New value</b></legend>
                                <table>
                                    <tr>
                                        <td>Name</td>
                                        <td><input name="new_name" /></td>
                                    </tr>
                                    <tr>
                                        <td>Type</td>
                                        <td>
                                            <select name="new_type">
                                                <option value="text" >Text</option>
                                                <option value="password" >Password</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Description</td>
                                        <td><input name="new_description" /></td>
                                    </tr>
                                </table>
                            </fieldset>
                            <div id="submit_panel"><input type="submit" value="Update !" /></div>
                        </form>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>';

?>
