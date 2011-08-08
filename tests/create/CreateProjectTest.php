<?php

require_once 'BrowserController.class.php';

class CreateProjectTest extends BrowserController
{

    public function testUserShouldBeAbleToCreateANewProject()
    {
        $this->login('project_admin_1', 'welcome0');

        $this->open("/my/index.php");
        $this->click("link=Register New Project");
        $this->waitForPageToLoad("30000");
        $this->click("id=register_tos_i_agree");
        $this->click("id=project_register_next");
        $this->waitForPageToLoad("30000");
        $this->type("name=form_full_name", "Public project 4");
        $this->type("name=form_unix_name", "public-project-4");
        $this->click("id=project_register_next");
        $this->waitForPageToLoad("30000");
        $this->click("id=project_register_next");
        $this->waitForPageToLoad("30000");
        $this->click("id=project_register_next");
        $this->waitForPageToLoad("30000");
        $this->type("name=form_short_description", "Public project 1");
        $this->type("name=form_101", "Public project 1");
        $this->click("id=project_register_next");
        $this->waitForPageToLoad("30000");
        $this->click("id=project_register_next");
        $this->waitForPageToLoad("30000");
        $this->click("id=project_register_next");
        $this->waitForPageToLoad("30000");
        $this->click("id=project_register_next");
        $this->waitForPageToLoad("30000");
        $this->click("id=project_register_next");
        $this->waitForPageToLoad("30000");
        $this->assertTrue($this->isTextPresent("Registration Complete"));

        // Validate as admin
        $this->login('admin', 'siteadmin');
        $this->open("/my/index.php");
        $this->waitForPageToLoad("30000");
        $this->click("link=Admin");
        $this->waitForPageToLoad("30000");
        $this->click("link=approval needed");
        $this->waitForPageToLoad("30000");
        $this->click("name=submit");
        $this->waitForPageToLoad("30000");
        $this->assertTrue($this->isTextPresent("No Pending Projects to Approve"));
    }

}

?>