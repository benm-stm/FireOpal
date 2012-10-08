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

ini_set('include_path', ini_get('include_path').PATH_SEPARATOR.dirname(__DIR__).DIRECTORY_SEPARATOR.'include');
require_once 'Setup.class.php';
if (!isset($_SESSION)) {
    session_start();
}
$setup = new Setup();
if (!empty($_REQUEST)) {
    $setup->store($_REQUEST);
    $setup->storeInDB($_SESSION['sess_idUser'], $_REQUEST);
    if (isset($_REQUEST['delete']) && !empty($_REQUEST['delete'])) {
        $setup->delete($_REQUEST['delete']);
    }
}

$content = $setup->display();
$form    = $content['form'];

$info = "";
if (!empty($content['info'])) {
    $info = '<ul class="feedback_info" >';
    foreach ($content['info'] as $message) {
        $info .= "<li>".$message."</li>";
    }
    $info .= '</ul>';
}

$error = "";
if (!empty($content['error'])) {
    $error = '<ul class="feedback_error" >';
    foreach ($content['error'] as $message) {
        $error .= "<li>".$message."</li>";
    }
    $error .= '</ul>';
}

echo '
<html>
    <head>
        <title>Fire Opal (update config)</title>
        <link href="include/css/index.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div id="header">';
echo $info;
echo $error;
echo '  <a href="cases.php" class="community"><< Go back</a>
        </div>
        <div id="header_clear">
        </div>
        <div id="body_skin">';

if(!isset($_SESSION['sess_idUser'])) {
    echo '<span class="signLink">You must be logged in to access to this page  >> </span> <a href="sign.php" class="greenLink">Sign In</a> ';
} else {
    echo '
            <table>
                <tr>
                    <td nowrap>
                        <form action="" method="POST">
                            <fieldset class="fieldset">
                                <legend><span class="fieldsetTitle">Config</span></legend>
                                <ul id="menu"><li class="">
                                    '.$form.'
                                </ul> 
                            </fieldset>
                            <fieldset class="fieldset">
                                <legend><span class="fieldsetTitle">New value</span></legend>
                                <table>
                                    <tr>
                                        <td class="confElement">Name</td>
                                        <td><input name="new_name" /></td>
                                    </tr>
                                    <tr>
                                        <td class="confElement">Type</td>
                                        <td>
                                            <select name="new_type">
                                                <option value="text" >Text</option>
                                                <option value="password" >Password</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="confElement">Description</td>
                                        <td><input name="new_description" /></td>
                                    </tr>
                                </table>
                            </fieldset>
                            <div id="submit_panel"><input type="submit" value="Update !" class="casesButton"/></div>
                        </form>
                    </td>
                </tr>
            </table>';
}
echo '
        </div>
    </body>
</html>';

?>