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
require_once 'ResultManager.class.php';

$resultManager = new ResultManager();

if (!empty($_REQUEST)) {
    if (isset($_REQUEST['delete_result']) && !empty($_REQUEST['delete_result'])) {
        $resultManager->deleteResult($_REQUEST['delete_result']);
    }
    if (isset($_REQUEST['download_result']) && !empty($_REQUEST['download_result'])) {
        $resultManager->downloadResult($_REQUEST['download_result']);
    }
}

echo '
<html>
    <head>
        <title>Codex automatic validation</title>
        <link href="include/css/index.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div id="header">
            <a href="index.php" class="community"> << Go back</a>
        </div>
        <div id="body_skin">
';

echo $resultManager->displayResults();

echo '
        </div>
    </body>
</html>
';

?>