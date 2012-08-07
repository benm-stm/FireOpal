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

#--- Start test Summary
#this test Check that a project member who is not Files admin can NOT access to the administration page.
#--- End test Summary

#---Start config variables
# host
# project_id
# project_short_name
#---End config variables

#--- Start dependency list
#--- End dependency list

describe "FileAdmin" do
    describe "#Precondition" do
        it "log to the project link" do
            $link = @setup['host']['value'] + '/projects/' + @setup['project_short_name']['value']
            @driver.navigate.to $link
        end
        it "test if the user is a project member" do
            (@driver.find_element(:id, "feedback").text.include? "Permission Denied").should be_false
        end
    end
    describe "#Step" do
        it "try to access to file admin section" do
            $link = @setup['host']['value'] + '/file/admin/?group_id=' + @setup['project_id']['value']
            @driver.navigate.to $link
        end
        it "display an error message:don't have sufficient access right." do
            (@driver.find_element(:class, "feedback").text.include? "Permission Denied").should be_true
        end
    end
end