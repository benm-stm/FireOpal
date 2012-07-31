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
    describe "#precondition:" do
    #The project "gpig" should exist and "simplex" should be a document writer
        it "Find Documents link" do
            $link = @setup['host']['value'] + '/plugins/docman/?group_id=' + @setup['project_id']['value']
            @driver.navigate.to $link
        end
    end
    describe "#regression:" do
        it "Find Create a New Document link" do
            @driver.find_element(:class, "docman_item_icon").click
            @driver.find_element(:class, "docman_item_option_newdocument").click
        end
        it "Put the title" do
            @driver.find_element(:id, "title").clear
            @driver.find_element(:id, "title").send_keys "new_wiki"
        end
        it "Select the type wiki" do
            @driver.find_element(:id, "item_item_type_5").click
        end
        it "Put the wiki name" do
            @driver.find_element(:name, "item[wiki_page]").clear
            @driver.find_element(:name, "item[wiki_page]").send_keys "new_wiki"
        end
        it "Create the document" do
            #is there a better way to select this button?
            @driver.find_element(:xpath, "(//input[@value='Create document'])").click
        end
        it "verify the text returned" do
            ( @driver.find_element(:class, "feedback_info").text.include? "Permissions successfully updated.").should be_true  
        end
    end
end