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
require_once 'ResultManager.class.php';
require_once 'common/User.class.php';

if (!isset($_SESSION)) {
    session_start();
}

if (isset($_SESSION['sess_idUser'])) {
    $user = new user();
    $user->loadFromId($_SESSION['sess_idUser']);

    $resultManager = new ResultManager($user);

    if (!empty($_REQUEST)) {
        if (isset($_REQUEST['delete_result']) && !empty($_REQUEST['delete_result'])) {
            $resultManager->deleteResult($_REQUEST['delete_result']);
        }
        if (isset($_REQUEST['download_result']) && !empty($_REQUEST['download_result'])) {
            $resultManager->downloadResult($_REQUEST['download_result']);
        }
        if (isset($_REQUEST['download_testsuite']) && !empty($_REQUEST['download_testsuite'])) {
            $resultManager->downloadTestSuite($_REQUEST['download_testsuite']);
        }
    }

    $content = $resultManager->displayResults();
} else {
    $content = 'You must be logged in to access to this page';
}

echo '
<html>
    <head>
        <title>Fire Opal</title>
        <link href="include/css/index.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="include/scripts/script.js"></script>
    </head>
    <body>
        <div id="header">
            <a href="index.php" class="community"> << Go back</a>
        </div>
        <div id="body_skin">
';

echo $content;

echo '
        </div>
    </body>
</html>
';

?>