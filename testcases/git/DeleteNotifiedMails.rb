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
# This testcase allows you to delete notified mails.
#--- End summary

#--- Start dependency list
#AddNotifiedMails.rb
#--- End dependency list

#--- Start conf params
# host
# project_id
# project_short_name
#--- End conf params

$Git_AddNotifiedMails_mail= "user1@st.com"
$Git_AddNotifiedMails_correctlogin = "codex support"
$Git_AddNotifiedMails_wronglogin = "user1"
$CreateRepoGit_rep_name = "TestingValidation1"

describe "delete notified mail" do
    describe "#precondition" do 
        it "Go to the project link" do
            $link = @setup['host']['value'] + '/projects/' + @setup['project_short_name']['value']
            @driver.navigate.to $link
        end
        it "test if the user is a project member" do
            (@driver.find_element(:class, "contenttable").text.include? "Permission Denied").should be_false
        end
    end
     describe "#step" do
        it "go to Git service" do
            $link = @setup['host']['value'] + '/plugins/git/?group_id=' + @setup['project_id']['value']
            @driver.navigate.to $link
        end
        it "select a repository to manage"do
            @driver.find_element(:link, $CreateRepoGit_rep_name).click
        end
        it "Go to Repository management" do
            @driver.find_element(:link, "Repository management").click
        end
        it "select mails to be deleted" do
            @driver.find_element(:name,"mail[]").click
            @driver.find_element(:xpath, "(//input[@value='Delete'])").click
        end
        it "display a message: removed" do
            (@driver.find_element(:class,"feedback_info").text.include? "removed").should be_true
        end
    end
end