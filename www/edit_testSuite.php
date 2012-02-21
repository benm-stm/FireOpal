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
        <script type="text/javascript" src="scripts/tree.js"></script>
    </head>
    <body>
        <FORM name="EditTestSuiteForm">
       Load test suite:
       <input type="button" name="load" value="Load" onclick="loadTestCases(this.form,this.form.testSuite,this.form.testCases);"><br />
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