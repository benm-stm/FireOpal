<?php
/**
 * Copyright (c) STMicroelectronics 2011. All rights reserved
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

require_once "PHPUnit/Autoload.php";

/**
 * Use SeleniumRC to control the browser
 * Uses singleton pattern
 */
class BrowserController extends PHPUnit_Extensions_SeleniumTestCase {

    static $started = false;

    /**
     * Set Parameters
     *
     * @return void
     */
    function setUp() {
        $this->setBrowserUrl($GLOBALS['host']);
        $this->setHost($GLOBALS['client']);
        $this->setBrowser($GLOBALS['browser']);
    }

    /**
     * Launch the browser
     *
     * @return void
     */
    function start() {
        if (!self::$started) {
            parent::start();
        }
        self::$started = true;
    }

    /**
     * Close the browser
     *
     * @return void
     */
    function stop() {
        if (self::$started) {
            parent::stop();
        }
        self::$started = false;
    }

    /**
     * In case there is no login for the current session authenticate
     *
     * @return void
     */
    function login() {
        $this->start();
        $this->open("/");
        $this->waitForPageToLoad("30000");
        $loggedIn = $this->isTextPresent("Logged In:");
        if (!$loggedIn) {
            $this->open("/account/login.php");
            $this->waitForPageToLoad("30000");
            $this->type("form_loginname", $GLOBALS['user']);
            $this->type("form_pw", $GLOBALS['password']);
            $this->click("login");
            $this->waitForPageToLoad("30000");
        }
    }

}

?>