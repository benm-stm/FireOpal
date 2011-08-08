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

require_once 'BrowserController.class.php';

class CreateFolderTest extends BrowserController {

    function testCreateFolder() {
        $this->login();
        $this->open("/projects/".$GLOBALS['project']);
        $this->waitForPageToLoad("30000");
        $this->click("link=Documents");
        $this->waitForPageToLoad("30000");
        $this->open("/plugins/docman/?group_id=".$GLOBALS['project_id']."&action=details&id=".$GLOBALS['docman_root_id']."&section=actions");
        $this->waitForPageToLoad("30000");
        $hasPermission = $this->isTextPresent("You can create a new folder in this folder.");
        $this->assertTrue($hasPermission, 'User don\'t have permission to create a folder');
        if ($hasPermission) {
            $this->click("link=create a new folder");
            $this->waitForPageToLoad("30000");
            $title1 = "folder1 ".time();
            $this->type("title", $title1);
            $this->type("description", "description1 ".time());
            $this->click("//input[@value='Create folder']");
            $this->waitForPageToLoad("30000");
            $this->assertTrue($this->isTextPresent("Document successfully created."), 'Folder not created');
            $this->click("//a[@id='docman_item_show_menu_".$GLOBALS['docman_root_id']."']/img");
            $this->click("link=New folder");
            $this->waitForPageToLoad("30000");
            $title2 = "folder2 ".time();
            $this->type("title", $title2);
            $this->type("description", "description2 ".time());
            $this->click("//input[@value='Create folder']");
            $this->waitForPageToLoad("30000");
            $this->assertTrue($this->isTextPresent("Document successfully created."), 'Folder not created');

            preg_match('/docman_item_title_link_([0-9]+)/', $this->getAttribute("xpath=(//a[text()='".$title1."'])[1]@id"), $id1);
            preg_match('/docman_item_title_link_([0-9]+)/', $this->getAttribute("xpath=(//a[text()='".$title2."'])[1]@id"), $id2);
            $this->click("//a[@id='docman_item_show_menu_".$id1[1]."']/img");
            $this->click("link=Delete");
            $this->waitForPageToLoad("30000");
            $this->click("confirm");
            $this->waitForPageToLoad("30000");
            $this->click("//a[@id='docman_item_show_menu_".$id2[1]."']/img");
            $this->click("link=Delete");
            $this->waitForPageToLoad("30000");
            $this->click("confirm");
            $this->waitForPageToLoad("30000");
        }
    }

}
?>