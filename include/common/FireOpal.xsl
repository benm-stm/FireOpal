<?xml version="1.0" encoding="UTF-8"?>
<html xsl:version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">
    <body style="font-family:Arial;font-size:12pt;background-color:#EEEEEE">
        <h2>Summary</h2>
        <xsl:variable name="testCount" select="sum(testsuite/@tests)"/>
        <xsl:variable name="errorCount" select="sum(testsuite/@errors)"/>
        <xsl:variable name="failureCount" select="sum(testsuite/@failures)"/>
        <xsl:variable name="timeCount" select="sum(testsuite/@time)"/>
        <xsl:variable name="successRate" select="($testCount - $failureCount - $errorCount) div $testCount"/>
        <table border="0" cellpadding="5" cellspacing="2" width="95%">
            <tr bgcolor="#A6CAF0" valign="top">
                <td><strong>Tests</strong></td>
                <td><strong>Failures</strong></td>
                <td><strong>Errors</strong></td>
                <td><strong>Success rate</strong></td>
                <td><strong>Time</strong></td>
            </tr>
            <tr bgcolor="#FFEBCD" valign="top">
                <td><xsl:value-of select="$testCount"/></td>
                <td><xsl:value-of select="$failureCount"/></td>
                <td><xsl:value-of select="$errorCount"/></td>
                <td><xsl:value-of select="$successRate"/></td>
                <td><xsl:value-of select="$timeCount"/></td>
            </tr>
        </table>
        <xsl:for-each select="testsuite/testcase">
            <div style="background-color:teal;color:white;padding:4px">
                <span style="font-weight:bold"><xsl:value-of select="name"/></span>
                * <xsl:value-of select="failure"/>
            </div>
            <div style="margin-left:20px;margin-bottom:1em;font-size:10pt">
                <xsl:value-of select="failure"/>
                <span style="font-style:italic">
                    <xsl:value-of select="failure"/>
                </span>
            </div>
        </xsl:for-each>
    </body>
</html>

