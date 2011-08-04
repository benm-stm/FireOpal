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

/**
 * Better view of the tests to run & results on the browser
 */
class CustomResultPrinter extends PHPUnit_TextUI_ResultPrinter {

    /**
     * @param  PHPUnit_Framework_TestResult $result
     */
    public function printResult(PHPUnit_Framework_TestResult $result)
    {
        $this->printHeader();

        if ($result->errorCount() > 0) {
            $this->printErrors($result);
        }

        if ($result->failureCount() > 0) {
            if ($result->errorCount() > 0) {
                print "\n--\n\n";
            }

            $this->printFailures($result);
        }

        if ($this->verbose) {
            if ($result->notImplementedCount() > 0) {
                if ($result->failureCount() > 0) {
                    print "\n--\n\n";
                }

                $this->printIncompletes($result);
            }

            if ($result->skippedCount() > 0) {
                if ($result->notImplementedCount() > 0) {
                    print "\n--\n\n";
                }

                $this->printSkipped($result);
            }
        }

        $this->printFooter($result);
    }

    /**
     * @param  array   $defects
     * @param  integer $count
     * @param  string  $type
     */
    protected function printDefects(array $defects, $count, $type)
    {
        static $called = FALSE;

        if ($count == 0) {
            return;
        }

        $this->write(
          sprintf(
            "%sThere %s %d %s%s:<br>",

            $called ? "<br>" : '',
            ($count == 1) ? 'was' : 'were',
            $count,
            $type,
            ($count == 1) ? '' : 's'
          )
        );

        $i = 1;

        foreach ($defects as $defect) {
            $this->printDefect($defect, $i++);
        }

        $called = TRUE;
    }

    /**
     * @param  PHPUnit_Framework_TestFailure $defect
     * @param  integer                       $count
     */
    protected function printDefect(PHPUnit_Framework_TestFailure $defect, $count)
    {
        $this->printDefectHeader($defect, $count);
        $this->printDefectTrace($defect);
    }

    /**
     * @param  PHPUnit_Framework_TestFailure $defect
     * @param  integer                       $count
     */
    protected function printDefectHeader(PHPUnit_Framework_TestFailure $defect, $count)
    {
        $failedTest = $defect->failedTest();

        if ($failedTest instanceof PHPUnit_Framework_SelfDescribing) {
            $testName = $failedTest->toString();
        } else {
            $testName = get_class($failedTest);
        }

        $this->write(
          sprintf(
            "<br>%d) %s<br>",

            $count,
            $testName
          )
        );
    }

    /**
     * @param  PHPUnit_Framework_TestFailure $defect
     */
    protected function printDefectTrace(PHPUnit_Framework_TestFailure $defect)
    {
        $this->write(
          $defect->getExceptionAsString() . "<br>" .
          PHPUnit_Util_Filter::getFilteredStacktrace(
            $defect->thrownException(),
            FALSE
          )
        );
    }

    /**
     * @param  PHPUnit_Framework_TestResult  $result
     */
    protected function printFooter(PHPUnit_Framework_TestResult $result)
    {
        if ($result->wasSuccessful() &&
            $result->allCompletlyImplemented() &&
            $result->noneSkipped()) {
            if ($this->colors) {
                $this->write("\x1b[30;42m\x1b[2K");
            }

            $this->write(
              sprintf(
                "OK (%d test%s, %d assertion%s)<br>",

                count($result),
                (count($result) == 1) ? '' : 's',
                $this->numAssertions,
                ($this->numAssertions == 1) ? '' : 's'
              )
            );

            if ($this->colors) {
                $this->write("\x1b[0m\x1b[2K");
            }
        }

        else if ((!$result->allCompletlyImplemented() ||
                  !$result->noneSkipped())&&
                 $result->wasSuccessful()) {
            if ($this->colors) {
                $this->write(
                  "\x1b[30;43m\x1b[2KOK, but incomplete or skipped tests!<br>" .
                  "\x1b[0m\x1b[30;43m\x1b[2K"
                );
            } else {
                $this->write("OK, but incomplete or skipped tests!<br>");
            }

            $this->write(
              sprintf(
                "Tests: %d, Assertions: %d%s%s.<br>",

                count($result),
                $this->numAssertions,
                $this->getCountString(
                  $result->notImplementedCount(), 'Incomplete'
                ),
                $this->getCountString(
                  $result->skippedCount(), 'Skipped'
                )
              )
            );

            if ($this->colors) {
                $this->write("\x1b[0m\x1b[2K");
            }
        }

        else {
            $this->write("<br>");

            if ($this->colors) {
                $this->write(
                  "\x1b[37;41m\x1b[2KFAILURES!\n\x1b[0m\x1b[37;41m\x1b[2K"
                );
            } else {
                $this->write("FAILURES!<br>");
            }

            $this->write(
              sprintf(
                "Tests: %d, Assertions: %s%s%s%s%s.<br>",

                count($result),
                $this->numAssertions,
                $this->getCountString($result->failureCount(), 'Failures'),
                $this->getCountString($result->errorCount(), 'Errors'),
                $this->getCountString(
                  $result->notImplementedCount(), 'Incomplete'
                ),
                $this->getCountString($result->skippedCount(), 'Skipped')
              )
            );

            if ($this->colors) {
                $this->write("\x1b[0m\x1b[2K");
            }
        }
    }

}

?>