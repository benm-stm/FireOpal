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

class LogManager {

    const DEBUG   = 1;
    const INFO    = 2;
    const WARNING = 3;
    const ERROR   = 4;
    const OFF     = 5;

    const LOG_OPENED    = 1;
    const LOG_FAILURE   = 2;
    const LOG_CLOSED    = 3;

    public  $dateFormat = "Y-m-d G:i:s";
    public  $messageMap;
    private $logFile;
    private $severity;
    private $filObject;
    private $logStatus;

    public function __construct($fileInfo) {
        $this->logFile = new SplFileInfo($filepath);
        $this->messageMap = array();
        if ($this->logFile->isFile()) {
            if (!$this->logFile->isWritable()) {
                $this->messageMap[] = "'".$filepath."' exists, but could not be opened for writing. Check that appropriate permissions have been set.";
                return;
            }
        }
        if ($this->filObject = $this->logFile->openFile('a')) {
            $this->MessageQueue[] = "The log file was successfully opened.";
        } else {
            $this->MessageQueue[] = "A problem occurred when attempting to open log file.";
        }
        return;
    }

    public function LogInfo($line) {
        $this->Log( $line, LogManager::INFO);
    }

        public function LogDebug($line) {
    $this->Log( $line, LogManager::DEBUG);
    }

    public function LogWARNING($line) {
        $this->Log( $line, LogManager::WARNINGING);
    }

    public function LogError($line) {
        $this->Log( $line, LogManager::ERROR);
    }

    public function LogFatal($line) {
        $this->Log( $line, LogManager::FATAL);
    }

    public function Log($line, $severity) {
        return true;
    }

    private function getTimeTrace($logLevel) {
        $time = date($this->DateFormat);
        switch ($logLevel) {
            case LogManager::DEBUG:
                return "$time - DEBUG level : ";
            case LogManager::INFO:
                return "$time - INFO level : ";
            case LogManager::WARNING:
                return "$time - WARNING level : ";
            case LogManager::ERROR:
                return "$time - ERROR level : ";
            default:
                return "$time - LOG default level : ";
        }
    }

    public function addLine($line) {
        if ($this->severity != LogManager::OFF) {
            if (!$this->filObject->fwrite($line)) {
                $this->MessageQueue[] = "Failure occurred while attempting to write to log file. Check that appropriate permissions have been set.";
            }
        }
    }

}
?>