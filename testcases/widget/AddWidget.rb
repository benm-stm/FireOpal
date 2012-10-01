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
# Add my last svn commits widget to user personal page
#--- End summary

#--- Start tags
# write
#--- End tags

describe "Add a widget to My dashboard" do
    describe "#precondition" do
        it "Find my personal page" do
            @runner.find_element(:link, "My Personal Page").click
        end
        it "Find customize link" do
            @runner.find_element(:link, "Customize").click
        end
        it "Find widget category" do
            @runner.find_element(:link, "Source Code Management").click
        end
        it "Hint the add button of the widget" do
            @runner.find_element(:name, "name[mylatestsvncommits][add]").click
            @runner.manage.timeouts.implicit_wait = 5
        end
    end
    describe "#regression" do
        it "Find my personal page" do
            @runner.find_element(:link, "My Personal Page").click
        end
        it "Look for the widget on My dashboard" do
            #@TODO find a way to check that the widget was added, you have probably to review widgets css/Html structure       
            @runner.manage.timeouts.implicit_wait = 5
        end
    end
end