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

# @TODO: try to test autocomplete stuff

#--- Start summary
# Add disk quota for a project
#--- End summary

#--- Start tags
# write
# site_admin
#--- End tags

#--- Start conf params
# disk_quota_project_id
# disk_quota_requester
#--- End conf params

describe "Add disk quota for a given project" do
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
        it "Clear the project name field" do
            @runner.find_element(:id, "project").clear
        end
        it "Write the project name" do
            @runner.find_element(:id, "project").send_keys @params['disk_quota_project_id']['value']
        end
        #        it "Select project from autcomplete suggestions" do
        #            @runner.find_element(:css, "li.selected").click
        #        end
        it "Clear the content of the disk quota reuqester input" do
            @runner.find_element(:id, "requester").clear
        end
        it "Write the disk quota requester name" do
            @runner.find_element(:id, "requester").send_keys @params['disk_quota_requester']['value']
        end
        #it "Select requester from autcomplete suggestions" do
        #    @runner.find_element(:xpath, "//body/div[4]/ul/li").click
        #end
        it "Clear the content of quota field" do
            @runner.find_element(:name, "quota").clear
        end
        it "Write new disk quota value" do
            @runner.find_element(:name, "quota").send_keys "12"
        end
        it "Clear the content of motivation field" do
            @runner.find_element(:name, "motivation").clear
        end
        it "write motivation text" do
            @runner.find_element(:name, "motivation").send_keys "this is a motivation"
        end
        it "Perform project disk quota submission" do
            @runner.find_element(:xpath, "(//input[@type='submit'])[4]").click
        end
        it "Find Project quota insertion feed back" do
            @runner.find_element(:class, "feedback_info").text.should include("Quota added for")
        end
    end
end