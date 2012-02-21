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
?>
<html>
    <head>
        <title>Codex automatic validation</title>
        <SCRIPT language="JavaScript">
            function loadTestCases(form,l1,l2){
                  var testCases = new Array("\docman\dummytest.rb","test.rb","tuleap.rb","dummytest.rb","yatest.rb","atest.rb","yatest.rb");
                     l1.options.length=0;
                     l2.options.length=0;
                     testCases.sort();
                     var i
                     for (i=0; i<6; i++) {
                         var o=new Option(testCases[i],testCases[i]);
                         l1.options[l1.options.length]=o;
                     }
            }
            function AddtestCases(l1,l2) {
                for(testCase=0;testCase<l1.length;testCase++){
                    if(l1.options[testCase].selected == true){
                        var added=new Option(l1.options[testCase].text,l1.options[testCase].value);
                        l2.options[l2.options.length]=added;
                        l1.options[testCase].selected = null;
                    }
                }
            }
           function RemoveTestCase(l1) {
                   l1.options[l1.options.selectedIndex]=null;
            }
        </SCRIPT>
    </head>
    <body>
        <FORM name="EditTestSuiteForm">
       Load available test cases:
       <input type="button" name="load" value="Initialisation" onclick="loadTestCases(this.form,this.form.testCases,this.form.testSuite);"><br />
       <br />
            <TABLE>
                <TR>
                    <TD align="center"><B><FONT size="2">Availables test cases</FONT></B><BR>
                        <SELECT align=top name="testCases" size=6  style="width:220px" multiple="multiple">
                            
                        </SELECT>
                    </TD>
                    <TD align="center">
                        <INPUT type="button" value="Add >>>" onClick="AddtestCases(this.form.testCases,this.form.testSuite)">
                        <BR><BR>
                        <INPUT type="button" value="&lt;&lt;&lt; Remove" onClick="RemoveTestCase(this.form.testSuite,this.form.testCases)">
                    </TD>
                    <TD align="center"><FONT size="2"><B>Dispatched test cases</B></FONT><BR>
                        <SELECT align=top name="testSuite" size=6 style="width:220px">
                            <OPTION value="10">----------------------</OPTION>
                        </SELECT>
                    </TD>
                </TR>
            </TABLE>
            <SCRIPT language="javascript">
                document.EditTestSuiteForm.testSuite.options.length=0;
            </SCRIPT>
        </FORM>
    </body>
</html>