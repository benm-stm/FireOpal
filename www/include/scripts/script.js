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

/**
 * Load testcases to add from an old testsuite
 */
function loadTestCases() {
    if (document.LoadTestSuiteForm.load_testsuites.length == undefined && document.LoadTestSuiteForm.load_testsuites.checked == true) {
        str = document.LoadTestSuiteForm.load_testsuites.value;
    } else {
        for (i = 0; i < document.LoadTestSuiteForm.load_testsuites.length; i++) {
            if (document.LoadTestSuiteForm.load_testsuites[i].checked == true ) {
                str = document.LoadTestSuiteForm.load_testsuites[i].value;
            }
        }
    }
    testCases = str.split(',');
	document.EditTestSuiteForm.testcases_to_add.options.length = 0;
    for (i = 0; i < testCases.length; i++) {
        var o = new Option(testCases[i], testCases[i]);
        document.EditTestSuiteForm.testcases_to_add.add(o, null);
    }
    p = document.getElementById('testcases_to_add');
    for (testCase = 0; testCase < p.length && !(p[testCase].value); testCase++);
    if (testCase == p.length) {
        alert('Test suite loading failure:\nThis test suite seems to be empty !!');
        document.getElementById('generate').disabled = true;
    } else {
        document.getElementById('generate').disabled = false;
    }
}

/**
 * Add selected testcase(s) to the testcases to add
 */
function AddtestCases() {
    for (testCase = 0; testCase < document.EditTestSuiteForm.testcases_to_add.length; testCase++) {
        if (document.EditTestSuiteForm.testcases_to_add.options[testCase].selected == true) {
            var selected = document.EditTestSuiteForm.testcases_to_add.options[testCase];
            break;
        }
    }
    for (testCase = 0; testCase < document.EditTestSuiteForm.testCases.length; testCase++) {
        if (document.EditTestSuiteForm.testCases.options[testCase].selected == true) {
            var added = new Option(document.EditTestSuiteForm.testCases.options[testCase].value, document.EditTestSuiteForm.testCases.options[testCase].value);
            document.EditTestSuiteForm.testcases_to_add.add(added, selected);
            document.EditTestSuiteForm.testCases.options[testCase].selected = null;
        }
    }
    p = document.getElementById('testcases_to_add');
    for (testCase = 0; testCase < p.length && !(p[testCase].value); testCase++);
    (testCase == p.length) ? document.getElementById('generate').disabled = true : document.getElementById('generate').disabled = false;
}

/**
 * Remove selected testcase(s) from testcases to add
 */
function RemoveTestCase() {
    for (testCase = document.EditTestSuiteForm.testcases_to_add.length -1 ; testCase >= 0 ; testCase--) {
        if (document.EditTestSuiteForm.testcases_to_add.options[testCase].selected == true) {
            document.EditTestSuiteForm.testcases_to_add.remove(testCase);
        }
    }
    if (document.EditTestSuiteForm.testcases_to_add.length < 1) {
        document.getElementById('generate').disabled = true;
    }
}

/**
 * Compile the selected testcases in a hidden field
 */
function generateTestSuite() {
    var p               = document.getElementById('testcases_to_add');
    var testCasesString = "";
    for (testCase = 0; testCase < p.length; testCase++) {
        if (testCase+1 == p.length) {
            testCasesString += p[testCase].value;
        } else {
            testCasesString += p[testCase].value+",";
        }
    }
    d           = document.getElementById("submit_panel_1");
    d.innerHTML = '<input type="hidden" id="testcases_to_add" name="testcases_to_add" value="' + testCasesString + '" />';
}

/**
 * Hide or display given item
 */
function toggle_visibility(id) {
    var e = document.getElementById(id);
    if(e.style.display == "none")
        e.style.display = "block";
    else
        e.style.display = "none";
}
