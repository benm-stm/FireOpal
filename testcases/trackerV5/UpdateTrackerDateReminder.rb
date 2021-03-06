########################################################################
# Copyright (c) STMicroelectronics 2012. All rights reserved           #
#                                                                      #
# FireOpal is free software; you can redistribute it and/or modify    #
# it under the terms of the GNU General Public License as published by #
# the Free Software Foundation; either version 2 of the License, or    #
# (at your option) any later version.                                  #
#                                                                      #
# FireOpal is distributed in the hope that it will be useful,         #
# but WITHOUT ANY WARRANTY; without even the implied warranty of       #
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        #
# GNU General Public License for more details.                         #
#                                                                      #
# You should have received a copy of the GNU General Public License    #
# along with FireOpal. If not, see <http://www.gnu.org/licenses/>.    #
########################################################################

#--- Start summary
# Update a tracker date reminder
#--- End summary

#--- Start tags
# Tracker V5
# Admin
# write
#--- End tags

#--- Start dependency list
# trackerV5/AddTrackerDateReminder.rb
#--- End dependency list

#--- Start conf params
# host
# tracker_id
#--- End conf params

describe "Update a tracker date reminder" do
    describe "#precondition" do
        it "Open notifications management interface" do
            $link = @params['host']['value'] + '/plugins/tracker/?tracker=' + @params['tracker_id']['value'] + '&func=notifications'
            @runner.navigate.to $link
        end
        it "Find a reminder to update" do
            @runner.find_element(:id, "update_reminder")
        end
    end
    describe "#regression" do
        it "Click on update reminder button" do
            @runner.find_element(:id, "update_reminder").click
        end
        it "Select Ugroups to be notified" do
            ugroups      = @runner.find_element(:name, "reminder_ugroup[]")
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
            @runner.find_element(:name, "distance").clear
            @runner.find_element(:name, "distance").send_keys rand(90)
        end
        it "Select notification type" do
            notificationType = @runner.find_element(:name, "notif_type")
            notifTypeSelect  = Selenium::WebDriver::Support::Select.new(notificationType)
            notifTypeSelect.select_by(:text, 'After')
        end
        it "Submit the updated values of the tracker date reminder" do
            @runner.find_element(:css, "td > input[name=\"submit\"]").click
        end
        it "Verify feedback message" do
            begin
                @runner.find_element(:class, "feedback_info").text.should include("Date Reminder successfully updated")
            rescue
                @runner.find_element(:class, "feedback_error").text.should include("Cannot duplicate Date Reminder")
            end
        end
    end
end
