// TODO: Rename this file (and don't forget to update calls to the file)

// TODO: Add comments
function loadTestCases() {
    if (document.LoadTestSuiteForm.load_testsuites.length == undefined && document.LoadTestSuiteForm.load_testsuites.checked == true) {
        str = document.LoadTestSuiteForm.load_testsuites.value;
    } else {
        for(i = 0; i < document.LoadTestSuiteForm.load_testsuites.length; i++) {
            if(document.LoadTestSuiteForm.load_testsuites[i].checked == true ) {
                str = document.LoadTestSuiteForm.load_testsuites[i].value;
            }
        }
    }
    testCases = str.split(',');
	document.EditTestSuiteForm.testcases_to_add.options.length = 0;
    for (i = 0; i < testCases.length; i++) {
        var o = new Option(testCases[i],testCases[i]);
        document.EditTestSuiteForm.testcases_to_add.add(o, null);
    }
}

// TODO: Add comments
function AddtestCases() {
    for(testCase = 0; testCase < document.EditTestSuiteForm.testcases_to_add.length; testCase++) {
        if(document.EditTestSuiteForm.testcases_to_add.options[testCase].selected == true) {
            var selected = document.EditTestSuiteForm.testcases_to_add.options[testCase];
        }
    }
    for(testCase = 0; testCase < document.EditTestSuiteForm.testCases.length; testCase++) {
        if(document.EditTestSuiteForm.testCases.options[testCase].selected == true) {
            var added = new Option(document.EditTestSuiteForm.testCases.options[testCase].text.substr(4), document.EditTestSuiteForm.testCases.options[testCase].value);
            document.EditTestSuiteForm.testcases_to_add.add(added, selected);
            document.EditTestSuiteForm.testCases.options[testCase].selected = null;
        }
    }
}

// TODO: Add comments
function RemoveTestCase() {
    for(testCase = document.EditTestSuiteForm.testcases_to_add.length -1 ; testCase >= 0 ; testCase--) {
        if(document.EditTestSuiteForm.testcases_to_add.options[testCase].selected == true) {
            document.EditTestSuiteForm.testcases_to_add.remove(testCase);
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
