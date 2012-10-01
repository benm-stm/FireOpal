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
# Support HTML for masschange
#--- End summary

#--- Start tags
# Tracker V5
# write
#--- End tags

#--- Start conf params
# project_name
# tracker_name
#--- End conf params

describe "TV5 HTML Masschange" do
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
    end
    describe "#step" do
        it "Apply Masschange for all artifacts" do
            @runner.find_element(:xpath, "//form[@id='tracker_report_query_form']/div[2]").click
            @runner.find_element(:css, "#tracker_renderer_options_menu_handle > img[alt=\"/themes/Tuleap/images/ic/dropdown_panel_handler_button.png\"]").click
            @runner.find_element(:link, "Masschange").click
            @runner.find_element(:id, "masschange_btn_all").click
        end
        it "Check html format radio button" do
            @runner.find_element(:id, "comment_format_htmlmass_change").click
        end
        it "Write masschange followup content" do
            @runner.find_element(:id, "artifact_masschange_followup_comment").clear
            @runner.find_element(:id, "artifact_masschange_followup_comment").send_keys "<b>HTML Mass Change</b>"
        end
        it "Submit masschange" do
            @runner.find_element(:css, "#masschange_form > input[type=\"submit\"]").click
        end
        it "Find followup info feed back" do
            @runner.find_element(:class, "feedback_info").text.should include("Successfully Updated")
        end
    end
end