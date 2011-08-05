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

class LookForItemTitle extends BrowserController {

    function testSearchItem() {
        $this->login();
        $this->open("/projects/".$GLOBALS['project']);
        $this->waitForPageToLoad("30000");
        $this->click("link=Documents");
        $this->waitForPageToLoad("30000");
        $this->open("/plugins/docman/?group_id=".$GLOBALS['project_id']."&action=details&id=".$GLOBALS['docman_root_id']."&section=actions");
        $this->waitForPageToLoad("30000");
        $hasPermission = $this->isTextPresent("You can create a new document in this folder.");
        $this->assertTrue($hasPermission, 'User don\'t have permission to create a document');
        if ($hasPermission) {
            $this->click("link=create a new document");
            $this->waitForPageToLoad("30000");
            $title = "empty".time();
            $this->type("title", $title);
            $this->type("description", "description ".time());
            $this->click("item_item_type_6");
            $this->click("//input[@value='Create document']");
            $this->waitForPageToLoad("30000");
            $this->assertTrue($this->isTextPresent("Document successfully created."), 'Folder not created');
            $this->click("docman_toggle_filters");
            $this->type("global_txt", $title);
            $this->click("docman_report_submit");
            $this->waitForPageToLoad("30000");
            $this->assertTrue($this->isTextPresent($title), 'Search didn\'t found "'.$title.'" with pattern"'.$title.'"');
            $pattern = "*".substr($title, 1);
            $this->type("global_txt", $pattern);
            $this->click("docman_report_submit");
            $this->waitForPageToLoad("30000");
            $this->assertTrue($this->isTextPresent($title), 'Search didn\'t found "'.$title.'" with pattern"'.$pattern.'"');
            $pattern = substr($title, 0, -1)."*";
            $this->type("global_txt", $pattern);
            $this->click("docman_report_submit");
            $this->waitForPageToLoad("30000");
            $this->assertTrue($this->isTextPresent($title), 'Search didn\'t found "'.$title.'" with pattern"'.$pattern.'"');
            $pattern = "*".substr(substr($title, 1), 0, -1)."*";
            $this->type("global_txt", $pattern);
            $this->click("docman_report_submit");
            $this->waitForPageToLoad("30000");
            $this->assertTrue($this->isTextPresent($title), 'Search didn\'t found "'.$title.'" with pattern"'.$pattern.'"');

            $this->click("//a[text()='".$title."']");
            $this->waitForPageToLoad("30000");
            $this->click("link=Edit properties");
            $this->waitForPageToLoad("30000");
            $this->click("link=Actions");
            $this->waitForPageToLoad("30000");
            $this->click("link=delete this document");
            $this->waitForPageToLoad("30000");
            $this->click("confirm");
            $this->waitForPageToLoad("30000");
            $this->assertTrue($this->isTextPresent("Item successfully deleted."), 'Document not deleted');
        }
    }

}
?>