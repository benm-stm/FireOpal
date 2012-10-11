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

class DBHandler {

    const DB_SERVER          = 'localhost';
    const DB_SERVER_USERNAME = 'root';
    const DB_SERVER_PASSWORD = 'roottoor';
    const DB_DATABASE        = 'fireopal';

    private static $instance;

    /**
     * Class Const
     */
    private function __construct() {

    }

    /**
     * Retrieve a DBHandler instance
     *
     * @return $instance;
     */
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new PDO("mysql:host=".self::DB_SERVER.";dbname=".self::DB_DATABASE, self::DB_SERVER_USERNAME, self::DB_SERVER_PASSWORD);
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$instance;
    }
}
?>