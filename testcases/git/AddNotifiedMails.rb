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
# This testcase allows you to add mails and check to notifications.
#--- End summary

#--- Start dependency list
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

describe "add notification" do
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
        it "user have no mail address" do
            # wrong login: 
            @driver.find_element(:id,"add_mail").clear
            @driver.find_element(:id,"add_mail").send_keys $Git_AddNotifiedMails_wronglogin
            @driver.find_element(:id,"add_mail_submit").click
            (@driver.find_element(:class,"feedback_error").text.include? "No user corresponding to entry").should be_true
        end
        it "user name will be autocompleted" do
            # correct autocomplited login:
            @driver.find_element(:id,"add_mail").clear
            @driver.find_element(:id,"add_mail").send_keys $Git_AddNotifiedMails_correctlogin
            @driver.find_element(:id,"add_mail_submit").click
            (@driver.find_element(:class,"feedback_info").text.include? "Mail added").should be_true
        end
        it "Add some mail addresses" do
            @driver.find_element(:id,"add_mail").clear
            @driver.find_element(:id,"add_mail").send_keys $Git_AddNotifiedMails_mail
            @driver.find_element(:id,"add_mail_submit").click
            (@driver.find_element(:class,"feedback_info").text.include? "Mail added").should be_true
        end
        it "add again the same mail" do
            @driver.find_element(:id,"add_mail").clear
            @driver.find_element(:id,"add_mail").send_keys $Git_AddNotifiedMails_mail
            @driver.find_element(:id,"add_mail_submit").click
            (@driver.find_element(:class,"feedback_info").text.include? "The notification is already enabled for this email").should be_true
        end
    end
end