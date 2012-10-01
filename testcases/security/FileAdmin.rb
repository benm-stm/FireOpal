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
# This test Checks that a project member who is not Files admin can NOT access to the administration page.
#--- End summary

#--- Start conf params
# host
# project_id
# project
#--- End conf params

#--- Start dependency list
#--- End dependency list

describe "FileAdmin"do
    describe "#precondition"do
        it "Find my personal page"do
            @runner.find_element(:link, "My Personal Page").click
        end
        it "Find project"do
            @runner.find_element(:link, @params['project']['value']).click
        end
        it "Test if the user is a project member"do
            (@runner.find_element(:id, "feedback").text.include? "Permission Denied").should be_false
        end
    end
    describe "#regression"do
        it "Try to access to file admin section"do
            $link = @params['host']['value'] + '/file/admin/?group_id=' + @params['project_id']['value']
            @runner.navigate.to $link
        end
        it "Display an error message:don't have sufficient access right."do
            (@runner.find_element(:class, "feedback").text.include? "Permission Denied").should be_true
        end
    end
end