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

define('DB_SERVER', 'localhost'); 
define('DB_SERVER_USERNAME','root');
define('DB_SERVER_PASSWORD', 'roottoor');
define('DB_DATABASE', 'fireopal');
define('USE_PCONNECT', 'true');

function connect() {  
if(@mysql_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD) or die(utf8_encode("Failure while connecting to the server")) )
    if( @mysql_select_db(DB_DATABASE) or die (utf8_encode("Failure while connecting to the database")))
        return true;
    else
        return false;
}
connect();
?>