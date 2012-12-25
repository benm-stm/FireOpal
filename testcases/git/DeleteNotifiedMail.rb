
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
# Delete Notified Mail
#--- End summary

#--- Start tags
# Project
#--- End tags

#--- Start dependency list
# git/CreateRepo.rb
#--- End dependency list

#--- Start conf params
# host
#--- End conf params

describe "Delete notified mail" do

        before(:all) do
            @runner.navigate.to @params['host']['value'] + '/my/'
        end
        describe "Delete mail" do
                it "Open project" do
                    @runner.find_element(:id, "navbar-project").click
                    @runner.find_element(:link, params['project_name']['value']).click
                end
                it "Go To Git service" do
                    @runner.find_element(:id, "sidebar-plugin_git").click
                end
                   it  "Find repo" do
                    @runner.find_element(:link,params['repo_name']['value']).click
                   
                end
                   it  "Find  repo settings"do
                    @runner.find_element(:link,"Settings").click
                end
                   it "Notifications" do
                    @runner.find_element(:link, "Notifications").click
                end
                   it  "Delete mail adresses" do
                    @runner.find_element(:name, "remove_mail[]").click
                    @runner.find_element(:xpath, "(//input[@name='remove_mail[]'])[2]").click
                end
                   it  "Find  button submit" do
                    @runner.find_element(:css, "input.btn").click
                end
                   it "verify update" do
                    update_content = @runner.find_element(:xpath, "//*[@id='feedback']/ul/li[1]").text
                    update_content.should == "Mail #{params['ldap_user1']['value']} removed"
                    update_content1 = @runner.find_element(:xpath, "//*[@id='feedback']/ul/li[2]").text
                    update_content.should == "Mail #{params['ldap_user2']['value']} removed"
                end

       end
    end
