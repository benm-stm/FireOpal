<?php

class CreateArtifact extends UnitTestCase {

    function testCreateArtifact() {
        $selenium = new Testing_Selenium("*firefox", $GLOBALS['host']);
        $selenium->start();
        $selenium->open("/");
        $selenium->click("link=My Personal Page");
        $selenium->waitForPageToLoad("30000");
        $selenium->click("expertContentHeading");
        $selenium->type("form_loginname", $GLOBALS['user']);
        $selenium->type("form_pw", $GLOBALS['password']);
        $selenium->click("login");
        $selenium->waitForPageToLoad("30000");
        $selenium->open("/projects/".$GLOBALS['project']);
        $selenium->waitForPageToLoad("30000");
        $selenium->click("link=Trackers");
        $selenium->waitForPageToLoad("30000");
        $selenium->click("link=".$GLOBALS['tracker']);
        $selenium->waitForPageToLoad("30000");
        $selenium->click("link=Submit A New ".$GLOBALS['trackerName']);
        $selenium->waitForPageToLoad("30000");
        $selenium->select("severity", "label=9 - Critical");
        $selenium->type("summary", "selenium test ".time());
        $selenium->type("tracker_details", "some text");
        $selenium->click("SUBMIT");
        $selenium->waitForPageToLoad("30000");
        $this->assertTrue($selenium->isTextPresent("Artifact Successfully Created (".$GLOBALS['trackerShortName']." #"), "Artifact not created");
        $selenium->stop();
    }

}
?>
