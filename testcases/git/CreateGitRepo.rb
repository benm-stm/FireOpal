########################################################################
# Copyright (c) STMicroelectronics 2012. All rights reserved           #
#                                                                      #
# This code is free software; you can redistribute it and/or modify    #
# it under the terms of the GNU General Public License as published by #
# the Free Software Foundation; either version 2 of the License, or    #
# (at your option) any later version.                                  #
#                                                                      #
# This code is distributed in the hope that it will be useful,         #
# but WITHOUT ANY WARRANTY; without even the implied warranty of       #
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        #
# GNU General Public License for more details.                         #
#                                                                      #
# You should have received a copy of the GNU General Public License    #
# along with this code. If not, see <http://www.gnu.org/licenses/>.    #
########################################################################

#--------Test Summary------------
# This test create a Git repository from Codex interface.
#
#--------Config dependencies-----
# project name: privateprjtest
# Git repository name: TestingValidation1
#
#--------Test(s) dependencies----
# CreateProject.rb
# ActivateGitService.rb
# 

describe "Create Git repo" do
    describe "-------Precondition--------" do
        it "Check if @setup['user']['value'] is member of project \"privateprjtest\" " do
        @driver.find_element(:link, "privateprjtest").displayed?
        end
        it " redirection to \"privateprjtest\" project then Git service" do
        @driver.find_element(:link, "privateprjtest").click
        @driver.find_element(:link, "Git").click
        end
        it " Check if the user is project Admin " do
        @driver.find_element(:link, "Admin").displayed?
        end
    end
    describe "--------Steps--------" do
        it "insert TestingValidation1 " do
        @driver.find_element(:id, "repo_name").send_keys "TestingValidation1"
        end
        it "submit repo creation" do
        @driver.find_element(:id, "repo_add").click
        end
        it "Assert: submit repo creation" do
        @driver.find_element(:id, "repo_add").click
        end
        it "should find TestingValidation1 repo" do
        wait = Selenium::WebDriver::Wait.new(:timeout => 15)
        assertRepoCreation = wait.until {
            repo = @driver.find_element(:link, "TestingValidation1")
            repo if repo.displayed?
        }
        end
    end
    describe "--------Post condition--------" do
        it "feedback message for already existing repo name is Not desplayed " do
            ( @driver.find_element(:id, "feedback").text.include? "Repository TestingValidation1 already exists").should be_false
        end
    end
end