#copyright (c) STMicroelectronics 2012. All rights reserved           #
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
# Push content in repo
#--- End summary

#--- Start tags
# Project
#--- End tags

#--- Start conf params
# host
#--- End conf params

describe "Add notified mail" do

        before(:all) do
            @runner.navigate.to @params['host']['value'] + '/my/'
        end
        describe "Add mail" do
                it "Open project" do
                    @runner.find_element(:id, "navbar-project").click
                    @runner.find_element(:link, @params['project_name']['value']).click
                end
                it "Go To Git service" do
                    @runner.find_element(:id, "sidebar-plugin_git").click
                end
                   it  "Find repo" do
                    @runner.find_element(:link,@params['repo_name']['value']).click
                end
                   it  "Find  repo settings"do
                    @runner.find_element(:link,"Settings").click
                end
                   it "Notifications" do
                    @runner.find_element(:link, "Notifications").click
                end
                   it  "Find mail adresses" do
		     #--- Needed params
		     ldap_user1 = @params['ldap_user1']['value']
		     ldap_user2 = @params['ldap_user2']['value']
                    @runner.find_element(:id, "add_mail").click
                    @runner.find_element(:id, "add_mail").clear
                    @runner.find_element(:id, "add_mail").send_keys "#{ldap_user1}, #{ldap_user2}"
                end
                   it  "Find  button submit" do
                    @runner.find_element(:css, "input.btn").click
                end
                   it "verify update" do
                    update_content = @runner.find_element(:xpath, "//*[@id='feedback']/ul/li").text
                    update_content.should == "Mail added"
                end
               
       end
    end
