// TODO: Rename this file (and don't forget to update calls to the file)
// TODO: Add comments
function loadTestCases() {
    for( i = 0; i < document.LoadTestSuiteForm.load_testsuites.length; i++ ) {
        if(document.LoadTestSuiteForm.load_testsuites[i].checked == true ) {
            str = document.LoadTestSuiteForm.load_testsuites[i].value;
        }
    }
    testCases = str.split(',');
	document.EditTestSuiteForm.testcases_to_add.options.length=0;
    var i
    for (i = 0; i<testCases.length; i++) {
        var o = new Option(testCases[i],testCases[i]);
        document.EditTestSuiteForm.testcases_to_add.options[document.EditTestSuiteForm.testcases_to_add.options.length] = o;
    }
}

// TODO: Add comments
// TODO: Verify if really we need all params
function AddtestCases(l1,l2) {
    for(testCase = 0; testCase<l1.length; testCase++) {
        if(l1.options[testCase].selected == true) {
            var added = new Option(l1.options[testCase].text,l1.options[testCase].value);
            l2.options[l2.options.length] = added;
            l1.options[testCase].selected = null;
        }
    }
}

// TODO: Add comments
// TODO: Verify if really we need all params
// TODO: Delete all selected testcases in one click
function RemoveTestCase(l1) {
    l1.options[l1.options.selectedIndex] = null;
}

// TODO: Add comments
// TODO: Verify if really we need all params
function generateTestSuite(testcases_to_add) {
    var p = document.getElementById('testcases_to_add');
    var testCasesString = "";
    for(testCase = 0; testCase<p.length; testCase++) {
        if(testCase+1 == p.length) {
            testCasesString += p[testCase].value;
        } else {
            testCasesString += p[testCase].value+",";
        }
    }
    d           = document.getElementById("submit_panel_1");
    d.innerHTML = '<input type="hidden" id="testcases_to_add" name="testcases_to_add" value="' + testCasesString + '" />';
}
