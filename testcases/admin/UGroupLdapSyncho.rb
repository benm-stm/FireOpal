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
# Create then bind a given a Tuleap ugroup to an LDAP group.
# Enable daily synchronization.
#--- End summary

#--- Start tags
# admin
# write
#--- End tags

#--- Start conf params
# project_name
#--- End conf params

describe "Synchronize UGroup with LDAP group" do
    describe "#precondition" do
        it "Find my personal page" do
            @runner.find_element(:link, "My Personal Page").click
        end
        it "Find project" do
            @runner.find_element(:link, @setup['project_name']['value']).click
        end
        it "Find administration service" do
            @runner.get(@base_url + "/projects/admin/")
            @runner.find_element(:xpath, "(//a[contains(text(),'Admin')])[3]").click
        end
        it "Find UGroup Admin section" do
            @runner.find_element(:link, "User Groups Admin").click
        end
        it "Create a new empty UGroup" do
            @runner.find_element(:link, "Create a New User Group").click
            @runner.find_element(:name, "ugroup_name").clear
            @runner.find_element(:name, "ugroup_name").send_keys "testers"
            @runner.find_element(:name, "ugroup_description").clear
            @runner.find_element(:name, "ugroup_description").send_keys "Test UGroup"
            @runner.find_element(:xpath, "//input[@value='Create User Group']").click
            @runner.find_element(:link, "« Go back to the user groups list").click
        end
    end
    describe "#step" do
        it "Enable directory group binding" do
            @runner.find_element(:link, "- Set Directory group binding").click
            @runner.find_element(:id, "group_add").clear
            @runner.find_element(:id, "group_add").send_keys "CODEX_ST_TUNIS"
        end
        it "Enable Automatic LDAP synchronization" do
           @runner.find_element(:id, "synchronize").click
        end
        it "Submit UGroup details" do
            @runner.find_element(:name, "submit").click
        end
        it "Confirm Added/Removed users" do
            #@Todo check GUI elements here 
            @runner.find_element(:name, "submit").click
        end
        it "Confirm Added/Removed users" do
            #@Todo check GUI elements here 
            @runner.find_element(:link, "User Groups Admin").click
        end
    end
end
