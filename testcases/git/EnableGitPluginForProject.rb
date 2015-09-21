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
# enable git plugin within the project
#--- End summary

#--- Start tags
# Project
#--- End tags

#--- Start conf params
# host
#--- End conf params

describe "Enable Git Service" do

        before(:all) do
            @runner.navigate.to @params['host']['value'] + '/my/'
        end
        describe "Enable service" do
                it "Open project" do
                    @runner.find_element(:id, "navbar-project").click
                    @runner.find_element(:link, @params['project_name']['value']).click
                end
                it "Go To Project Adminstration" do
                    @runner.find_element(:css, "span[title=\"Project Administration\"]").click
                end
                it  "Click on the Service Configuration link" do
                    @runner.find_element(:link, "Service Configuration").click
                end
                it "Click on the git link" do
                    @runner.find_element(:xpath, "/html/body/div[1]/div[4]/div/div/div[2]/table[3]/tbody/tr[17]/td[1]/a").click
                end
                it "verify the checkbox" do 
                   verif = @runner.find_element(:name, "is_used").selected?
                   case verif
                  when false
                    @runner.find_element(:name, "is_used").click
                    @runner.find_element(:name, "Update").click
                    update_content = @runner.find_element(:xpath, "//*[@id='feedback']/ul/li").text
                    if  update_content.should == "Successfully Updated Service"
                     puts "the plugin is Enabled"
                    else
                    puts "there is a bug"
                    end
                  when true
                    puts "the plugin is already Enabled"
                  end
                end
                  end
                end


