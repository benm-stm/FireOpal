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
# git plugin activation for tuleap
#--- End summary

#--- Start tags
#--- End tags

#--- Start dependency list
#--- End dependency list

#--- Start conf params
# host
#--- End conf params

describe "Activate Git Plugin By Admin" do

        before(:all) do
            @runner.navigate.to @params['host']['value'] + '/my/'
        end
        describe "Activate Git Plugin" do
                it "Go to  Admin" do
                    @runner.find_element(:link, "Admin").click
                end
                it  "Click on Plugins Configuration link" do
                    @runner.find_element(:link, "Plugins Administration").click
                end
		it "Activate the git plugin and vefrify the update" do
                      verif = @runner.find_element(:xpath, "//td[span//text()[contains(., 'Git Plugin')]]/ancestor::tr/td[2]/span[@class='']").text
                    	case verif 
                           when "yes"
                                @runner.find_element(:xpath, "//td[span//text()[contains(., 'Git Plugin')]]/ancestor::tr/td[2]/span[@class='']/a").click
                                update_content = @runner.find_element(:xpath, "//*[@id='feedback']/ul/li/code[1]").text
                                update_content.should == "/etc/tuleap/forgeupgrade/config.ini."
 
                           when "no"
                        	puts "the plugin is actived"
                      end
                end
        end 
end
