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
# Add a tracker date reminder
#--- End summary

#--- Start tags
# Tracker V5
# Admin
# write
#--- End tags

#--- Start conf params
# project_name
# tracker
#--- End conf params

describe "Add new tracker date reminder" do
    describe "#precondition" do
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
            @driver.find_element(:link, @setup['tracker']['value']).click
        end
        it "Find notifications management interface" do
            @driver.find_element(:link, "Notifications").click
        end
    end
    describe "#step" do
        it "Hint add reminder button" do
            @driver.find_element(:id, "add_reminder").click
        end
        it "Select Ugroups to be notified" do
            ugroups      = @driver.find_element(:name, "reminder_ugroup[]")
            ugroupsMSBox = Selenium::WebDriver::Support::Select.new(ugroups)
            ugroupsList  = Array.new
            ugroupsMSBox.options.each do |elm|
                #puts elm.attribute("value")
                ugroupsList.push elm.text
            end
            $ugroup_name = ugroupsList[rand(ugroupsList.length)]
            ugroupsMSBox.select_by(:text, $ugroup_name)
        end
        it "Specify distance in days" do
            @driver.find_element(:name, "distance").clear
            @driver.find_element(:name, "distance").send_keys rand(90)
        end
        it "Select notification type" do
            notificationType = @driver.find_element(:name, "notif_type")
            notifTypeSelect  = Selenium::WebDriver::Support::Select.new(notificationType)
            notifValues      = ["After", "Before"]
            $notif_type      = notifValues[rand(notifValues.length)]
            notifTypeSelect.select_by(:text, $notif_type)
        end
        it "Select the date field on which the reminder will be applied" do
            fieldDate   = @driver.find_element(:name, "reminder_field_date")
            optionCount = fieldDate.find_elements(:tag_name=>"option")
            optionList  = Array.new
            optionCount.each do |el|
                optionList.push el.text
            end
            fieldDateSelect = Selenium::WebDriver::Support::Select.new(fieldDate)
            $field_name     = optionList[rand(optionList.length)]
            fieldDateSelect.select_by(:text, $field_name)
        end
        it "Submit new tracker date reminder" do
            @driver.find_element(:css, "td > input[name=\"submit\"]").click
        end
        it "Find new reminder info feed back" do
            @driver.find_element(:class, "feedback_info").text.should include("Date Reminder successfully added")
        end
    end
end