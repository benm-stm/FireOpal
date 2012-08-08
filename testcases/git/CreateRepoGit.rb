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

#--- Start summary
# This test create a Git repository from Codex interface.
#--- End summary

#--- Start dependency list
#--- End dependency list

#--- Start conf params
# host
# project_id
# project_short_name
#--- End conf params

$CreateRepoGit_rep_name = "TestingValidation1"

describe "Create Git repo" do
    describe "#precondition" do
        it "Go to the project link" do
            $link = @setup['host']['value'] + '/projects/' + @setup['project_short_name']['value']
            @driver.navigate.to $link
        end
        it "test if the user is a project member" do
            (@driver.find_element(:class, "contenttable").text.include? "Permission Denied").should be_false
        end
        it " Check if the user is project Admin " do
            @driver.find_element(:link, "Admin").displayed?
        end
        it "go to Git service" do
            $link = @setup['host']['value'] + '/plugins/git/?group_id=' + @setup['project_id']['value']
            @driver.navigate.to $link
        end
    end
    describe "#step" do
        it "insert TestingValidation1 " do
            @driver.find_element(:id, "repo_name").send_keys $CreateRepoGit_rep_name
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
            repo = @driver.find_element(:link, $CreateRepoGit_rep_name)
            repo if repo.displayed?
        }
        end
        it "feedback message for already existing repo name is not displayed " do
            ( @driver.find_element(:class,"feedback_error").text.include? "Repository TestingValidation1 already exists").should be_false
        end
    end
end