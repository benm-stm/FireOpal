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
 
class testSuite implements SplSubject {

    private $_testCases;
    private $_observers;
    private $_currentTestCase;
	private $_result;

    public function __construct(array $testCases) {
        $this->_testCases = $testCases;
        $this->_observers = array();
        $this->_result   = array();
        //@TODO   Better prefer dependency injection here. use SplObjectStorage
        //$this->_observers = new SplObjectStorage();
    }

    /**
     * Launch test Suite...
     * exec('ruby file_treatement_written_ruby'. $testSuite.'2>&1' , $this->_result); 
     */
    public function runTestSuite() {
        }

    /**
     * Launch test Cases...
     *
     * @Deprecated
     */
    public function runTestCases() {
        foreach ($this->_testCases as $testCase) {
            $this->_currentTestCase = $testCase;
            //@TODO update here
            $this->_result = $testCase;
            exec('ruby '.$testCase.' 2>&1', $this->_result);
            $this->notify();
        }
        $this->_currentTestCase = null;
    }

    /**
     * Returns the testCase currently being updated
     *
     * @return $testCase
     */
    public function getCurrent() {
        return $this->_currentTestCase;
    }

    /**
     * Attach an observer
     *
     * @param SplObserver $observer
     */
    public function attach(SplObserver $observer) {
        array_push($this->_observers, $observer);
    }

    /**
     * Detach an observer
     *
     * @param SplObserver $observer
     */
    public function detach(SplObserver $observer) {
        foreach ($this->_observers as $key => $item)
        {
            if ($observer == $item) {
                unset($this->_observers[$key]);
            }
        }
    }

    /**
     * Send notification to all observers
     */
    public function notify() {
        foreach ($this->_observers as $key => $item) {
            $item->update($this);
        }
    }
} 
 ?>