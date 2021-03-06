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
# support HTML followups
# Add new html followup
# Update a given followup
# Masschange
#--- End summary

#--- Start tags
# Tracker V5
# write
#--- End tags

#--- Start conf params
# project_name
# tracker_name
#--- End conf params

describe "TV5 HTML followup" do
    describe "#precondition" do
        it "Find my personal page" do
            @runner.find_element(:link, "My Personal Page").click
        end
        it "Find project" do
            @runner.find_element(:link, @params['project_name']['value']).click
        end
        it "Find tracker service" do
            @runner.find_element(:link, "Trackers").click
        end
        it "Find target tracker" do
            @runner.find_element(:link, @params['tracker_name']['value']).click
        end
        it "Find the first artifact" do
            @runner.find_element(:css, "img[alt=\"#1\"]").click
        end
    end
    describe "#regression" do
        it "Check html format radio button" do
            @runner.find_element(:id, "comment_format_htmlnew").click
        end
        it "Write followup content" do
            @runner.find_element(:id, "tracker_followup_comment_new").clear
            @runner.find_element(:id, "tracker_followup_comment_new").send_keys "Yet another <b>html</b> followup"
        end
        it "Submit new followup" do
            @runner.find_element(:xpath, "(//input[@name='submit_and_stay'])[2]").click
        end
        it "Find new followup info feed back" do
            @runner.find_element(:class, "feedback_info").text.should include("Successfully Updated")
        end
    end
end