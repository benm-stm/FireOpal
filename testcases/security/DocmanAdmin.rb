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
# This test prevent a non document admin to open the admin page
#--- End summary

#--- Start conf params
# host
# project_id
# project
#--- End conf params

#--- Start dependency list
#--- End dependency list

describe "Non-docman admin can NOT do any administration action" do
    describe "#precondition" do
        it "Find my personal page"do
            @runner.find_element(:link, "My Personal Page").click
        end
        it "Find project"do
            @runner.find_element(:link, @setup['project']['value']).click
        end
        it "Test if the user is a project member" do
            (@runner.find_element(:class, "contenttable").text.include? "Permission Denied").should be_false
        end
        it "Try to access to docman admin section" do
            $link = @setup['host']['value'] + '/plugins/docman/?group_id=' + @setup ['project_id']['value'] + '&action=admin'
            @runner.navigate.to $link
        end
    end
    describe "#step" do
        it "Display an error message:You do not have sufficient access rights to administrate the document manager" do
            (@runner.find_element(:class, "feedback_error").text.include? "You do not have sufficient access rights to administrate the document manager").should be_true
        end
    end
end