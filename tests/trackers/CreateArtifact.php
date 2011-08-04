<?php
/**
 * Copyright (c) STMicroelectronics 2011. All rights reserved
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

class CreateArtifact extends UnitTestCase {

    function testCreateArtifact() {
        $this->login();
        $this->open("/projects/".$GLOBALS['project']);
        $this->waitForPageToLoad("30000");
        $this->click("link=Trackers");
        $this->waitForPageToLoad("30000");
        $this->click("link=".$GLOBALS['tracker']);
        $this->waitForPageToLoad("30000");
        $this->click("link=Submit A New ".$GLOBALS['trackerName']);
        $this->waitForPageToLoad("30000");
        $this->select("severity", "label=9 - Critical");
        $this->type("summary", "selenium test ".time());
        $this->type("tracker_details", "some text");
        $this->click("SUBMIT");
        $this->waitForPageToLoad("30000");
        $this->assertTrue($this->isTextPresent("Artifact Successfully Created (".$GLOBALS['trackerShortName']." #"), "Artifact not created");
    }

}
?>