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

#--- Start tags
# Tracker V5
# Admin
# write
#--- End tags

describe "Add new tracker date reminder" do
    describe "#precondition:" do
        it "Find my personal page" do
            @driver.find_element(:link, "My Personal Page").click
        end
        it "Find project" do
            @driver.find_element(:link, "Administration Project").click
        end
        it "Find tracker service" do
            @driver.find_element(:link, "Trackers").click
        end
        it "Find My tracker" do
            @driver.find_element(:link, "zerazr").click
        end
        it "Find Notifications interface" do
            @driver.find_element(:link, "Notifications").click
        end
    end
    describe "#regression:" do
        it "Hint add reminder button" do
            @driver.find_element(:id, "add_reminder").click
        end
        it "Select Ugroups to be notified" do
            Selenium::WebDriver::Support::Select.new(@driver.find_element(:name, "reminder_ugroup[]")).select_by(:text, "project_admins")
        end
        it "Specify distance in days" do
            @driver.find_element(:name, "distance").clear
            @driver.find_element(:name, "distance").send_keys "11"
        end
        it "Select notification type" do
            Selenium::WebDriver::Support::Select.new(@driver.find_element(:name, "notif_type")).select_by(:text, "After")
        end
        it "Select the date field on which the reminder will be applied" do
            Selenium::WebDriver::Support::Select.new(@driver.find_element(:name, "reminder_field_date")).select_by(:text, "Submitted on")
        end
        it "Submit new Reminder form" do
            @driver.find_element(:css, "td > input[name=\"submit\"]").click
        end
        it "Find new reminder info feed back" do
            @driver.find_element(:class, "feedback_info").text.should include("Date Reminder successfully added")
        end
    end
end
