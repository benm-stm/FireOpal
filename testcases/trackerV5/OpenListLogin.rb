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
# Add a user login in an open list binded to users
#--- End summary

#--- Start conf params
# host
# artifact_id
# user
#--- End conf params

#--- Start tags
# tracker V5
#--- End tags

describe "Put user login in a user binded open list" do
    describe "#precondition" do
        it "Open the artifct" do
            $link = @params['host']['value'] + '/plugins/tracker/?aid='+ @params['artifact_id']['value']
            @runner.navigate.to $link
        end
        it "Clear the open list" do
            $more = true
            while $more
                begin
                    @runner.find_element(:css, "a.closebutton").click
                rescue
                    $more = false
                end
            end
            @runner.find_element(:class, "maininput").clear
        end
        it "Fill the open list" do
            @runner.find_element(:class, "maininput").send_keys @params['user']['value'] + ","
            begin
                @runner.find_element(:css, "a.closebutton")
            rescue
                @runner.find_element(:css, "em").click
            end
        end
        it "Verify that the open list is filled correctly" do
            @runner.find_element(:css, "a.closebutton")
        end
        it "Submit the artifact update" do
            @runner.find_element(:name, "submit_and_stay").click
        end
    end
    describe "#regression" do
        it "User login is accepted in open list" do
            @runner.find_element(:css, "a.closebutton")
        end
        it "Artifact successfully updated or no changes" do
            begin
                @runner.find_element(:class, "feedback_info").text.should include("Successfully Updated")
            rescue
                @runner.find_element(:class, "feedback_info").text.should include("No changes for artifact")
            end
        end
    end
end