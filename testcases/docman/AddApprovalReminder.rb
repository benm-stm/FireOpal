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
# Add a new approval table reminder
#--- End summary

#--- Start tags
# write
#--- End tags

#--- Start conf params
# host
# project_id
# docman_root_id
#--- End conf params

describe "Browse to approval table menu" do
    describe "#precondition" do
        it "Find my personal page" do
            @driver.find_element(:link, "My Personal Page").click
        end
        it "Find project" do
            @driver.find_element(:link, @setup['project']['value']).click
        end
        it "Find document service" do
            @driver.find_element(:link, "Documents").click
        end
        it "Open menu of docman root" do
            @driver.find_element(:css, "img.docman_item_icon").click
        end
        it "Find approval table link" do
            @driver.find_element(:link, "Approval table").click
        end
        it "Edit the approval table" do
            @driver.find_element(:css, "strong > a").click
        end
        it "Verify that the approval table is created" do
            @driver.find_element(:tag_name => "body").text.should include("Approval table global settings")
        end
        it "Verify that the reminder is not set" do
            @driver.find_element(:id, "approval_table_reminder_checkbox").attribute("value").should eq("off")
        end
    end
    describe "#step" do
        it "Hint add reminder button" do
            if @driver.find_element(:id, "approval_table_reminder_checkbox").attribute("value") == "off"
                @driver.find_element(:id, "approval_table_reminder_checkbox").click
            end
            $value = 2
            @driver.find_element(:name, "occurence").clear
            @driver.find_element(:name, "occurence").send_keys $value
            @driver.find_element(:css, "p > input[type=\"submit\"]").click
        end
        it "Verify that the occurence has been added" do
            $value = @driver.find_element(:name, "occurence").attribute("value").to_i.should == $value
        end
        it "Check update feedback" do
            @driver.find_element(:class, "feedback_info").text.should include("Table settings updated.")
        end
    end
end