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
# Submit HTMl followup comment
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
        it "Find the first artifact" do
            @driver.find_element(:css, "img[alt=\"#1\"]").click
        end
    end
    describe "#step" do
        it "toggle WYSIWYG editor" do
            @driver.find_element(:id, "comment_format_htmlnew").click
            @driver.find_element(:id, "tracker_followup_comment_new").clear
            @driver.find_element(:id, "tracker_followup_comment_new").send_keys "<p><b>Une chaîne de caractères<b>"
            @driver.find_element(:name, "submit_and_stay").click
        end
        it "Find followup info feed back" do
            #Should be replace by :
            # wait = Selenium::WebDriver::Wait.new(:timeout => 30)
            # wait.until { @driver.find_element(:class => "feedback_info") }
            @driver.manage.timeouts.implicit_wait = 30
            @driver.find_element(:class, "feedback_info").text.should include("Successfully Updated")
        end
    end
end
