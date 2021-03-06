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

# @TODO: Retrive project name before deletion then inject it to feedback matcher within the 7th example...
# @TODO: Extend test case for multiple deletion...

#--- Start summary
# This is a template for testcases to be written
#--- End summary

#--- Start tags
# write
# site_admin
#--- End tags

#--- Start dependency list
# admin/addProjectDiskQuota.rb
#--- End dependency list

#--- Start conf params
# disk_quota_project_id
#--- End conf params

describe "Deletes a given project disk quota" do
    describe "#precondition" do
        it "Find my personal page" do
            @runner.find_element(:link, "My Personal Page").click
        end
        it "Find Admin link" do
            @runner.find_element(:xpath, "//a[@href='/admin/']").click
        end
        it "Find Statistics link" do
            @runner.find_element(:link, "Statistics").click
        end
        it "Find project quota link" do
            @runner.find_element(:link, "Project quota").click
        end
    end
    describe "#regression" do
        it "Select Project disk quota to delete" do
            @runner.find_element(:xpath, "(//input[@value='"+@params['disk_quota_project_id']['value']+"'])").click
        end
        it "Perform project disk quota deletion" do
            @runner.find_element(:xpath, "(//input[@type='submit'])[3]").click
        end
        it "Find Project quota deletion feed back" do
            @runner.find_element(:class, "feedback_info").text.should include("Quota deleted for")
        end
    end
end