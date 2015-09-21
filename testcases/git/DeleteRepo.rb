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
# Delete Repository
#--- End summary

#--- Start tags
# Project
#--- End tags

#--- Start conf params
# host
#--- End conf params

describe "Delete a repository" do

        before(:all) do
            @runner.navigate.to @params['host']['value'] + '/my/'
        end
        describe "Change repo description" do
                it "Open project" do
                    @runner.find_element(:id, "navbar-project").click
                    @runner.find_element(:link, @params['project_name']['value']).click
                end
                it "Go To Git service" do
                    @runner.find_element(:link, "Git").click
                end
               it "delete repo" do
                verif = @runner.find_elements(:link, @params['repo_name']['value']).size()
                 if verif > 0
                    @runner.find_element(:link, @params['repo_name']['value']).click
                    @runner.manage.timeouts.implicit_wait = 5
                    @runner.find_element(:link,"Settings").click
                    @runner.find_element(:link, "Delete").click
                    @runner.find_element(:name, "confirm_deletion").click
                    @runner.find_element(:id, "submit").click
                    puts "repo removed"
                else 
                 puts "this repo not exist" 
           end 
             end
             it "verify the deletion" do 
               @runner.find_element(:xpath, "//*[@id='feedback']/ul/li[1]").text.should == "Repository \'#{@params['repo_name']['value']}\' will be removed in a few seconds"
             end
        end
    end
