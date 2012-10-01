########################################################################
# Copyright (c) STMicroelectronics 2012. All rights reserved           #
#                                                                      #
# This code is free software; you can redistribute it and/or modify    #
# it under the terms of the GNU General Public License as published by #
# the Free Software Foundation; either version 2 of the License, or    #
# (at your option) any later version.                                  #
#                                                                      #
# This code is distributed in the hope that it will be useful,         #
# but WITHOUT ANY WARRANTY; without even the implied warranty of       #
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        #
# GNU General Public License for more details.                         #
#                                                                      #
# You should have received a copy of the GNU General Public License    #
# along with this code. If not, see <http://www.gnu.org/licenses/>.    #
########################################################################

#--- Start summary
# This is a template for testcases to be written
#--- End summary

#--- Start tags
# write
# site_admin
#--- End tags

#--- Start dependency list
# docman/CopyPasteMenu.rb
# trackerV3/tuleap.rb
# template.rb
#--- End dependency list

#--- Start conf params
# user
# password
#--- End conf params

describe "Testcase name" do
    describe "#precondition" do
        it "Clear the first field" do
            @runner.find_element(:name, "form_element_example_1").clear
        end
        it "Fill the first field" do
            @runner.find_element(:name, "form_element_example_1").send_keys @params['user']['value']
        end
        it "Fill another field" do
            @runner.find_element(:name, "form_element_example_2").send_keys @params['password']['value']
        end
        it "Click on a button" do
            @runner.find_element(:name, "button_example").click
        end
    end
    describe "#step" do
        it "Test the wrong title of the page" do
            (@runner.title).should == "wrong title"
        end
        it "Test the correct title of the page" do
            (@runner.title).should == "good tiltle"
        end
    end
end