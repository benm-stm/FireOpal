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
# Update approval table reminder V2
#--- End summary

#--- Start tags
# write
#--- End tags

#--- Start dependency list
# docman/CopyPasteMenu.rb
# trackerV3/tuleap.rb
# template.rb
#--- End dependency list

#--- Start conf params
# user
# password
#--- End conf params

describe "Update docman approval table reminder" do
    describe "#precondition:" do
        it "Find my personal page" do
            @driver.find_element(:link, "My Personal Page").click
        end
        it "Find project" do
            @driver.find_element(:link, "Administration Project").click
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
    end
    describe "#regression:" do
        it "Update Reminder settings" do
                Selenium::WebDriver::Support::Select.new(@driver.find_element(:id, "period")).select_by(:text, "Weeks")
            @driver.find_element(:name, "occurence").clear
            @driver.find_element(:name, "occurence").send_keys "2"
        end
        it "Submit reminder form" do
            @driver.find_element(:css, "p > input[type=\"submit\"]").click
        end
        it "Check update feedback" do
            @driver.find_element(:class, "feedback_info").text.should include("Table settings updated.")
        end
    end
end