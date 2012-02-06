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
 
class testSuite extends SplObjectStorage {

    private $testSuiteMap = array();

    /**
     * Add a testCase to the testSuite
     *
     * @param testCase $testCase
     */
    public function attach($testCase) {
        /*if (!$testCase instanceof testCase) {
              throw new Exception('Unable to attach test case!');
        }*/
        return parent::attach($testCase);
    }

    /**
     * Remove a testCase from the testSuite
     *
     * @param testCase $testCase
     */
    public function detach($testCase) {
        /*if (!$testCase instanceof testCase) {
              throw new Exception('Unable to delete test case!');
        }*/
        return parent::detach($testCase);
    }
 }
 ?>