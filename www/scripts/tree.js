// TODO: Add comments
function loadTestCases() {
    for( i = 0; i < document.LoadTestSuiteForm.load_testsuites.length; i++ ) {
        if(document.LoadTestSuiteForm.load_testsuites[i].checked == true ) {
            str = document.LoadTestSuiteForm.load_testsuites[i].value;
        }
    }
    testCases = str.split(',');
	document.EditTestSuiteForm.testcases_to_add.options.length=0;
    testCases.sort();
    var i
    for (i=0; i<testCases.length; i++) {
        var o = new Option(testCases[i],testCases[i]);
        document.EditTestSuiteForm.testcases_to_add.options[document.EditTestSuiteForm.testcases_to_add.options.length] = o;
    }
}

// TODO: Add comments
// TODO: Verify if really we need all params
function AddtestCases(l1,l2) {
    for(testCase=0; testCase<l1.length; testCase++) {
        if(l1.options[testCase].selected == true) {
            var added=new Option(l1.options[testCase].text,l1.options[testCase].value);
            l2.options[l2.options.length] = added;
            l1.options[testCase].selected = null;
        }
    }
}

// TODO: Add comments
// TODO: Verify if really we need all params
function RemoveTestCase(l1) {
    l1.options[l1.options.selectedIndex]=null;
}
