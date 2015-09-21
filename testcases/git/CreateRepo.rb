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
# Repository Creation
#--- End summary

#--- Start tags
# Project 
#--- End tags

#--- Start conf params
# host
#--- End conf params

describe "Create repository" do

        before(:all) do
            @runner.navigate.to @params['host']['value'] + '/my/'
        end
        describe "Create repo" do
                it "Open project" do
					@runner.find_element(:id, "navbar-project").click
                    @runner.find_element(:link, project_name = @params['project_name']['value']).click
                end 
                it "Go To Git service" do
                    @runner.find_element(:link, "Git").click
                end
                it "verify of repo existance" do
					verif = @runner.find_elements(:link, @params['repo_name']['value']).size
					if verif > 0
						puts "repo exist"
					else 
						@runner.find_element(:id, "repo_name").clear
						@runner.find_element(:id, "repo_name").send_keys @params['repo_name']['value']
						@runner.find_element(:id, "repo_add").click
						puts "repo created"
					end
				end
        end 
end
    

