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
            @driver.find_element(:link, @setup['project_name']['value']).click
        end
        it "Find tracker service" do
            @driver.find_element(:link, "Trackers").click
        end
        it "Find target tracker" do
            @driver.find_element(:link, @setup['tracker_name']['value']).click
        end
        it "Find notifications management interface" do
            @driver.find_element(:link, "Notifications").click
        end
    end
    describe "#regression:" do
        it "Hint add reminder button" do
            @driver.find_element(:id, "add_reminder").click
        end
        it "Select Ugroups to be notified" do
            #@TODO Manage comma separated ugroups from conf
            ugroups      = @driver.find_element(:name, "reminder_ugroup[]")
            ugroupsMSBox = Selenium::WebDriver::Support::Select.new(ugroups)
            ugroupsMSBox.select_by(:text, @setup['ugroup_name']['value'])
        end
        it "Specify distance in days" do
            @driver.find_element(:name, "distance").clear
            @driver.find_element(:name, "distance").send_keys @setup['distance']['value']
        end
        it "Select notification type" do
            notificationType = @driver.find_element(:name, "notif_type")
            notifTypeSelect  = Selenium::WebDriver::Support::Select.new(notificationType)
            notifTypeSelect.select_by(:text, @setup['notif_type']['value'])
        end
        it "Select the date field on which the reminder will be applied" do
            fieldDate       = @driver.find_element(:name, "reminder_field_date")
            fieldDateSelect = Selenium::WebDriver::Support::Select.new(fieldDate)
            fieldDateSelect.select_by(:text, @setup['field_name']['value'])
        end
        it "Submit new tracker date reminder" do
            @driver.find_element(:css, "td > input[name=\"submit\"]").click
        end
        it "Find new reminder info feed back" do
            @driver.find_element(:class, "feedback_info").text.should include("Date Reminder successfully added")
        end
    end
end
