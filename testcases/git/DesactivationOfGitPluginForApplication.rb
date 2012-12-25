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

describe "Desactivate Git Plugin By Admin" do

        before(:all) do
            @runner.navigate.to @params['host']['value'] + '/my/'
        end
        describe "Disactivate Git Plugin" do
                it "Go to  Admin" do
                    @runner.find_element(:link, "Admin").click
                end
                   it  "Click on Plugins Configuration link" do
                    @runner.find_element(:link, "Plugins Administration").click
                end
              it "verify the existance of link" do 
                verif = @runner.find_element(:xpath, "//td[span//text()[contains(., 'Git Plugin')]]/ancestor::tr/td[2]/span[@class='']").text
                        case verif
                           when "yes"
                                puts "the plugin is desactived"
                           when "no"
                                @runner.find_element(:xpath, "//td[span//text()[contains(., 'Git Plugin')]]/ancestor::tr/td[2]/span[@class='']/a").click
                                update_content = @runner.find_element(:xpath, "//*[@id='feedback']/ul[1]/li").text
                                update_content.should == "Git Plugin is now unavailable. Web space and CGI remain accessible!"
                                puts "Succes"
                      end
                   end
                end 
              end 
    


