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
# This test Delete existing Git repository from Codex interface.
#
#--------Config dependencies-----
# project name: privateprjtest
# Git repository name: TestingValidation1
#
#--------Test(s) dependencies----
# CreateProject.rb
# ActivateGitService.rb
# CreateRepoGit.rb

describe "Delete existing Git repo" do
    describe "-------Precondition--------" do
        it " redirection to \"privateprjtest\" project then Git service"do
        @driver.find_element(:link, "privateprjtest").click
        @driver.find_element(:link, "Git").click
        end
    end
    describe "--------------Steps---------------" do
        it "select the repositry" do
            @driver.find_element(:link, "TestingValidation1").click
        end
        it "enter to repository management" do
            @driver.find_element(:link, "Repository management").click
        end
        it "click on delete this repository" do
            @driver.find_element(:name, "confirm_deletion").click
        end
        it "confirm deletation" do
            @driver.find_element(:id, "submit").click
        end
    end
    describe "--------Post condition--------" do
        it "feedback message displayed" do
            ( @driver.find_element(:id, "feedback").text.include? "Repository has been deleted").should be_true
        end
    end
end