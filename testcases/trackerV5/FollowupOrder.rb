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
# Check that the followup order is saved
#--- End summary

#--- Start conf params
# host
# artifact_id
#--- End conf params

#--- Start tags
# tracker V5
#--- End tags

describe "Order of followups is saved" do
    describe "#precondition" do
        it "Open the artifct" do
            @@artifactLink = @params['host']['value'] + '/plugins/tracker/?aid='+ @params['artifact_id']['value']
            @runner.navigate.to @@artifactLink
        end
        it "Get the followup order" do
            $trackerId = @runner.find_element(:id, "tracker_id").attribute("value")
            @@orderLink = @params['host']['value'] + '/plugins/tracker/comments_order.php?tracker_id='+ $trackerId
            @runner.navigate.to @@orderLink
            @@order = @runner.find_element(:tag_name => "body").text
        end
        it "Get back to the artifact" do
            @runner.navigate.to @@artifactLink
        end
        it "Invert the order" do
            @runner.find_element(:css, "img[alt=\"invert order of follow-up comments\"]").click
        end
    end
    describe "#regression" do
        it "The followup order is saved" do
            @runner.navigate.to @@orderLink
            @runner.find_element(:tag_name => "body").text.should_not eq(@@order)
        end
    end
end