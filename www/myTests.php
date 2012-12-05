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
require_once 'TestCaseManager.class.php';

echo '
<html>
    <head>
        <title>FireOpal</title>
        <link href="include/css/index.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="include/scripts/script.js"></script>
    </head>';
echo '
    <body>
        <div id="header">
            <a href="index.php" class="community"> << Go back</a>
        </div>
        <div id="body_skin">
            <div><br>';

$testCaseManager = new TestCaseManager();
$fsTestsArray    = $testCaseManager->listFileSystem("../testcases");
$fsIterator      = $testCaseManager->getFileSystemIterator("../testcases");
foreach ($fsIterator as $entry) {
    if (!$entry->isDir()) {
        $testCase       = substr($entry->getPathname(), strlen(TestCaseManager::TESTCASES_PATH));
        $testCaseString = substr($testCase, 0, strlen($testCase)-3);
        echo '<br><span class="testCaseDetailstoggler" onclick="toggle_visibility(\'test_output_'.$testCase.'\'); if (this.innerHTML == \'+\') { this.innerHTML = \'-\'; } else { this.innerHTML = \'+\'; }">+</span><span class="testCaseDetailsTitle">'.$testCaseString.'</span><br/>';

        $spanContent    = '<span class="testCaseDetail" id="test_output_'.$testCase.'" style="display: none;" >';
        $spanContent   .= $testCaseManager->rspecPrettyFormat($testCaseString);
        $spanContent   .= '</span>';
        echo $spanContent;
            }
}

echo '      </div>
        </div>
    </body>
</html>';
?>