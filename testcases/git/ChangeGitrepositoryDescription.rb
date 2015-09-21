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
# Change Repository Description
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
  
 describe "Change a git repository description" do

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
                   it  "Find repo" do
                    @runner.find_element(:link,@params['repo_name']['value']).click
                    @runner.manage.timeouts.implicit_wait = 5
                end
                   it  "Find  repo settings"do
                    @runner.find_element(:link,"Settings").click 
                end
                   it  "Find repo descrip" do
                    @runner.find_element(:id, "repo_desc").click
                    @runner.find_element(:id, "repo_desc").clear
                    @runner.find_element(:id, "repo_desc").send_keys "Some description"
                end
                   it  "Find repo button save" do
                    @runner.find_element(:name,"save").click    
                end          
       end
    end
