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
if (!isset($_SESSION)) {
    session_start();
}
//var_dump($_SESSION);

if (isset($_SESSION['sess_idUser'])) {
    if (isset($_COOKIE["rememberMe"])) { 
        setcookie ("rememberMe", "", time() - 36003600*24*7);
    }
    session_unregister('sess_idUser');
    header('Location: ../www/index.php'); 
}
?>