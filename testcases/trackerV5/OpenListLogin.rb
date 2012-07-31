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
# Add a user login in an open list binded to users
#--- End summary

#--- Start conf params
# host
# artifact_id
# open_list_id
# user
#--- End conf params

#--- Start tags
# tracker V5
#--- End tags

describe "Order of followups is saved" do
    describe "#precondition:" do
        it "Open the artifct" do
            $link = @setup['host']['value'] + '/plugins/tracker/?aid='+ @setup['artifact_id']['value']
            @driver.navigate.to $link
        end
        it "Clear the open list" do
            $more = true
            while $more
                begin
                    @driver.find_element(:css, "a.closebutton").click
                rescue
                    $more = false
                end
            end
            @driver.find_element(:id, @setup['open_list_id']['value']).clear
        end
        it "Fill the open list" do
            # @TODO: Make this work 100% of the time instead of exactly 50% of the time (which is suspect!)
            @driver.find_element(:id, @setup['open_list_id']['value']).send_keys @setup['user']['value'] + ","
        end
        it "Submit the artifact update" do
            @driver.find_element(:name, "submit_and_stay").click
        end
    end
    describe "#regression:" do
        it "User login is accepted in open list" do
            # @TODO: check the feedback message
        end
    end
end