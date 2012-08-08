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
# This testcase PREVENT a non-project member to fork repositories
#--- End summary.

#--- Start dependency list
#--- End dependency list

#--- Start conf params
# host
# project2_id
# project2_short_name
#--- End conf params

describe "prevent a non-project admin to fork repositories" do
    describe "precondition" do
        it "Go to the project link" do
            $link = @setup['host']['value'] + '/projects/' + @setup['project2_short_name']['value']
            @driver.navigate.to $link
        end
        it "test if the user is a project member" do
            (@driver.find_element(:class, "contenttable").text.include? "Permission Denied").should be_false
        end
    end
    describe "step" do
        it "go to Git service" do
            $link = @setup['host']['value'] + '/plugins/git/?group_id=' + @setup['project2_id']['value']
            @driver.navigate.to $link
        end
        it "feedback message displayed" do
            (@driver.find_element(:class,"main_body_row").text.include? "No repository found").should be_true
        end
        it "feedback for repository list" do
            @driver.find_element(:link, "Repository list").click
            (@driver.find_element(:class,"main_body_row").text.include? "No repository found").should be_true
        end
        it "feedback for Fork repositories" do
            @driver.find_element(:link, "Fork repositories").click
            (@driver.find_element(:class,"main_body_row").text.include? "Fork repositories").should be_true
        end
    end
end