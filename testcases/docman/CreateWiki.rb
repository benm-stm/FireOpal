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
# Create a wiki type document
#--- End summary

#--- Start tags
# write
#--- End tags

#--- Start conf params
# host
# project_id
#--- End conf params

describe "Create a new document wiki" do
    describe "#precondition" do
    #The project "gpig" should exist and "simplex" should be a document writer
        it "Find Documents link" do
            $link = @params['host']['value'] + '/plugins/docman/?group_id=' + @params['project_id']['value']
            @runner.navigate.to $link
        end
    end
    describe "#step" do
        it "Find Create a New Document link" do
            @runner.find_element(:class, "docman_item_icon").click
            @runner.find_element(:class, "docman_item_option_newdocument").click
        end
        it "Put the title" do
            @runner.find_element(:id, "title").clear
            @runner.find_element(:id, "title").send_keys "new_wiki"
        end
        it "Select the type wiki" do
            @runner.find_element(:id, "item_item_type_5").click
        end
        it "Put the wiki name" do
            @runner.find_element(:name, "item[wiki_page]").clear
            @runner.find_element(:name, "item[wiki_page]").send_keys "new_wiki"
        end
        it "Create the document" do
            #is there a better way to select this button?
            @runner.find_element(:xpath, "(//input[@value='Create document'])").click
        end
        it "verify the returned text" do
            (@runner.find_element(:class, "feedback_info").text.include? "Permissions successfully updated.").should be_true
        end
    end
end