<?php

require_once 'Testing/Selenium.php';

class BrowserController {

    static $instance;

    static $selenium;

    static $started = false;

    private function __construct() {
    }

    private function __clone() {
    }

    function getInstance($browser = "*firefox") {
        if (self::$instance === null) {
            self::$instance = new self();
            self::$selenium = new Testing_Selenium($browser, $GLOBALS['host']);
        }
        self::start();
        return self::$instance;
    }

    function getSelenium() {
        return self::$selenium;
    }

    function start() {
        if (!self::$started) {
            self::$selenium->start();
        }
        self::$started = true;
    }

    function stop() {
        if (self::$started) {
            self::$selenium->stop();
        }
        self::$started = false;
    }

    function login() {
        self::$selenium->open("/");
        self::$selenium->waitForPageToLoad("30000");
        $loggedIn = self::$selenium->isTextPresent("Logged In:");
        if (!$loggedIn) {
            self::$selenium->open("/account/login.php");
            self::$selenium->waitForPageToLoad("30000");
            self::$selenium->type("form_loginname", $GLOBALS['user']);
            self::$selenium->type("form_pw", $GLOBALS['password']);
            self::$selenium->click("login");
            self::$selenium->waitForPageToLoad("30000");
        }
    }

}

?>