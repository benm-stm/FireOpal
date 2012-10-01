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
# Delete a tracker date reminder
#--- End summary

#--- Start tags
# Tracker V5
# Admin
# write
#--- End tags

#--- Start dependency list
# trackerV5/UpdateTrackerDateReminder.rb
#--- End dependency list

#--- Start conf params
# host
# tracker_id
#--- End conf params

describe "Delete a tracker date reminder" do
    describe "#precondition" do
        it "Open notifications management interface" do
            $link = @params['host']['value'] + '/plugins/tracker/?tracker=' + @params['tracker_id']['value'] + '&func=notifications'
            @runner.navigate.to $link
        end
        it "Find a reminder to update" do
            @runner.find_element(:id, "delete_reminder")
        end
    end
    describe "#step" do
        it "Click on delete reminder button" do
            @runner.find_element(:id, "delete_reminder").click
        end
        it "Confirm the deletion" do
            @runner.find_element(:name, "confirm_delete").click
        end
        it "Verify feedback message" do
            @runner.find_element(:class, "feedback_info").text.should include("Date Reminder successfully deleted")
        end
    end
end