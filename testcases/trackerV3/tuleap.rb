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

describe "Test logging in" do
    describe "#precondition" do
        it "Clear the login field" do
            @driver.find_element(:name, "form_loginname").clear
        end
        it "Fill the login field" do
            @driver.find_element(:name, "form_loginname").send_keys @setup['user']['value']
        end
        it "Fill the password field" do
            @driver.find_element(:name, "form_pw").send_keys @setup['password']['value']
        end
        it "Click on submit button" do
            @driver.find_element(:name, "login").click
        end
    end
    describe "#regression" do
        it "Test the wrong title of the page" do
            (@driver.title).should == "wrong title"
        end
        it "Test the correct title of the page" do
            (@driver.title).should == "good tiltle"
        end
        it "compare 1 to 1" do
            1.should eq(1)
        end
    end
end
