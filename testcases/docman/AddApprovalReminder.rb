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
# docman_id
#--- End conf params

describe "Browse to approval table menu" do
    describe "#precondition:" do
        it "Open the approval table" do
            $link = @setup['host']['value'] + '/plugins/docman/?group_id='+ @setup['project_id']['value'] + '&action=approval_create&id='+ @setup['docman_id']['value']
            @driver.navigate.to $link
        end
        it "Verify that user can update the approval table" do
            @driver.find_element(:css, "strong > a").click 
        end
        it "Verify that the reminder is not set" do
            @driver.find_element(:id, "approval_table_reminder_checkbox").attribute("value").should == "on"    
        end
    end
    describe "#regression:" do
        it "Hint add reminder button" do
            @driver.find_element(:id, "approval_table_reminder_checkbox").click
            $value = 2
            @driver.find_element(:name, "occurence").send_keys $value 
            @driver.find_element(:css, "p > input[type=\"submit\"]").click
        end
        it "Verify that the occurence has been added" do
            $value = @driver.find_element(:name, "occurence").attribute("value").to_i.should == $value
        end
    end
end