// TODO: Rename this file (and don't forget to update calls to the file)

// TODO: Add comments
// TODO: Make this work even if there is only one testsuite
function loadTestCases() {
    for(i = 0; i < document.LoadTestSuiteForm.load_testsuites.length; i++) {
        if(document.LoadTestSuiteForm.load_testsuites[i].checked == true ) {
            str = document.LoadTestSuiteForm.load_testsuites[i].value;
        }
    }
    testCases = str.split(',');
	document.EditTestSuiteForm.testcases_to_add.options.length = 0;
    var i
    for (i = 0; i < testCases.length; i++) {
        var o = new Option(testCases[i],testCases[i]);
        document.EditTestSuiteForm.testcases_to_add.options[document.EditTestSuiteForm.testcases_to_add.options.length] = o;
    }
}

// TODO: Add comments
function AddtestCases() {
    for(testCase = 0; testCase < document.EditTestSuiteForm.testCases.length; testCase++) {
        if(document.EditTestSuiteForm.testCases.options[testCase].selected == true) {
            var added = new Option(document.EditTestSuiteForm.testCases.options[testCase].text, document.EditTestSuiteForm.testCases.options[testCase].value);
            document.EditTestSuiteForm.testcases_to_add.options[document.EditTestSuiteForm.testcases_to_add.options.length] = added;
            document.EditTestSuiteForm.testCases.options[testCase].selected = null;
        }
    }
}

// TODO: Add comments
// TODO: Verify why this won't work all the time
function RemoveTestCase() {
    for(testCase = 0; testCase < document.EditTestSuiteForm.testcases_to_add.length; testCase++) {
        if(document.EditTestSuiteForm.testcases_to_add.options[testCase].selected == true) {
            document.EditTestSuiteForm.testcases_to_add.options[testCase] = null;
        }
    }
}

// TODO: Add comments
function generateTestSuite() {
    var p               = document.getElementById('testcases_to_add');
    var testCasesString = "";
    for(testCase = 0; testCase < p.length; testCase++) {
        if(testCase+1 == p.length) {
            testCasesString += p[testCase].value;
        } else {
            testCasesString += p[testCase].value+",";
        }
    }
    d           = document.getElementById("submit_panel_1");
    d.innerHTML = '<input type="hidden" id="testcases_to_add" name="testcases_to_add" value="' + testCasesString + '" />';
}
