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

require_once '../include/Setup.class.php';

$setup = new Setup();
$set   = $setup->load();

for ($i = 1; $i <= $argc-1; $i++) {
    if ($argv[$i] == "--help" || $argv[$i] == "-h") {
        $display_help = true;
    } elseif ($argv[$i] == "--verbose" || $argv[$i] == "-v") {
        $verbose = true;
    } elseif (!preg_match("/^-/", $argv[$i])) {
        // Not a parameter (does not start with "-") Then, it must be a name of a function
        $function = $argv[$i];
        break;
    } elseif (preg_match("/^-/", $argv[$i])) {
        // A parameter for the given function
        $parameters[] = $argv[$i];
        break;
    } else {
        // Unknown parameter
        exit_error('Unknown parameter: "'.$argv[$i].'"');
    }
}

if (!empty($function)) {
    switch ($function) {
        case 'setup' :
            foreach ($set as $name => $entry) {
                if ($entry['type'] == 'password') {
                    echo " *".$name.": ******** (".$entry['description'].")\n";
                } else {
                    echo " *".$name.": ".$entry['value']." (".$entry['description'].")\n";
                }
            }
            break;
        default :
            echo "Function not implemented yet or unknown\n";
            break;
    }
} else {
    echo "TIC \"TIC is not CLI\"\nFunctions:\n *setup: Display setup.\n *testsuites: Display testsuites.\n *testcases: Display testcases.\n *generate: Generate a testsuite.\n";
}

?>
