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
            function AddtestCase(l1,l2) {
               if (l1.options.selectedIndex>=0) {
                   o=new Option(l1.options[l1.options.selectedIndex].text,l1.options[l1.options.selectedIndex].value);
                   l2.options[l2.options.length]=o;
                   //Don't delete test case from left 
                   //l1.options[l1.options.selectedIndex]=null;
                   //alert(l2.options[l2.options.length-1].value);
               }/*else{
                   alert("Aucune activité sélectionnée");
               }*/
            }
           function RemoveTestCase(l1) {
                   l1.options[l1.options.selectedIndex]=null;
                   //alert(l1.options[l1.options.length-1].value);
            }
        </SCRIPT>
    </head>
    <body>
        <FORM name="EditTestSuiteForm">
            <TABLE>
                <TR>
                    <TD align="center"><B><FONT size="2">Availables test cases</FONT></B><BR>
                        <SELECT align=top name="testCases" size=6  style="width:220px">
                            <OPTION value="dummytest.rb">dummytest.rb</OPTION>
                            <OPTION value="test.rb">test.rb</OPTION>
                            <OPTION value="tuleap.rb">tuleap.rb</OPTION>
                            <OPTION value="dummytest.rb">dummytest.rb</OPTION>
                            <OPTION value="yatest.rb">yatest.rb</OPTION>
                            <OPTION value="atest.rb">atest.rb</OPTION>
                        </SELECT>
                    </TD>
                    <TD align="center">
                        <INPUT type="button" value="Add >>>" onClick="AddtestCase(this.form.testCases,this.form.testSuite)">
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