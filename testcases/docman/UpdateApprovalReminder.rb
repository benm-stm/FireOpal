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
# Update approval table reminder
#--- End summary

#--- Start tags
# write
#--- End tags

#--- Start dependency list
# docman/AddApprovalReminder.rb
#--- End dependency list

#--- Start conf params
# host
# project_id
# docman_root_id
#--- End conf params

describe "Browse to approval table menu" do
    describe "#precondition" do
        it "Open the approval table" do
            $link = @params['host']['value'] + '/plugins/docman/?group_id=' + @params['project_id']['value'] + '&action=approval_create&id=' + @params['docman_root_id']['value']
            @runner.navigate.to $link
        end
        it "Verify that user can update the aproval table" do
            # @TODO
        end
    end
    describe "#step" do
        it "Reminder is on" do
            @runner.find_element(:name, "reminder").attribute("value").should == "on"
        end
        it "Change form values" do
            @runner.find_element(:name, "reminder").click
            @runner.find_element(:name, "reminder").click
            Selenium::WebDriver::Support::Select.new(@runner.find_element(:id, "period")).select_by(:text, "Weeks")
            $value = @runner.find_element(:name, "occurence").attribute("value").to_i + 1
            @runner.find_element(:name, "occurence").clear
            @runner.find_element(:name, "occurence").send_keys $value
            @runner.find_element(:xpath, "//input[@value='Update']").click
        end
        it "Verify that the occurence has been updated" do
            $value = @runner.find_element(:name, "occurence").attribute("value").to_i.should == $value * 7
        end
        it "Check update feedback" do
            @runner.find_element(:class, "feedback_info").text.should include("Table settings updated.")
        end
    end
end