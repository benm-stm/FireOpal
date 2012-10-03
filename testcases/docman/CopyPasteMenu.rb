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
# Verify that the paste menu is displayed only for folders other than the copied folder or its decendency
#--- End summary

#--- Start tags
# read_only
# docman_write
#--- End tags

#--- Start conf params
# project_name
#--- End conf params

describe "Display of paste in menu" do
    describe "#precondition" do
        it "Go to my personal page" do
            @runner.find_element(:link, "My Personal Page").click
        end
        it "Enter the project" do
            @runner.find_element(:link, @params['project_name']['value']).click
        end
        it "Enter the documents service" do
            @runner.find_element(:link, "Documents").click
        end
        it "Open menu of docman root" do
            @runner.find_element(:css, "img.docman_item_icon").click
        end
        it "User is document writer on docman root" do
            @runner.find_element(:class, "docman_item_option_newfolder").text.should == "New folder"
        end
        it "Close the menu" do
            @runner.find_element(:link, "[Close]").click
        end
        it "Find a folder other than docman root" do
            folder        = @runner.find_element(:xpath, '//a[contains(@class, "docman_item_type_folder")]')
            $folder_class = folder[:class]
            $folder_id    = folder[:href].split("&action=collapseFolder&id=")
            $folder_id    = $folder_id[1]
        end
        it "Open menu of the selected folder" do
            @runner.find_element(:xpath, "//a[@id='docman_item_show_menu_"+$folder_id+"']/img").click
        end
        it "User is document writer on the selected folder" do
            @runner.find_element(:class, "docman_item_option_cut").text.should == "Cut"
        end
        it "Cut the selected folder" do
            @runner.find_element(:class, "docman_item_option_cut").click
        end
        it "Feedback of the cut is displayed" do
            text = " cut. You can now paste it wherever you want with 'Paste' action in popup menu."
            (@runner.find_element(:id, "item_cut").text.include? text).should eq(true)
        end
        it "Open menu of the selected folder after cut" do
            @runner.find_element(:xpath, "//a[@id='docman_item_show_menu_"+$folder_id+"']/img").click
        end
        it "Menu of the selected folder is open" do
            @runner.find_element(:class, "docman_item_option_cut").text.should == "Cut"
        end
    end
    describe "#regression" do
        it "Paste doesn't exist in the menu of the copied folder" do
            begin
                @runner.find_element(:class, "docman_item_option_paste")
                # TODO: Find a better way to detect this as a regression
                true.should eq(false)
            rescue
                true.should eq(true)
            end
        end
    end
    describe "#precondition" do
        it "Open the selected folder" do
            @runner.find_element(:id, "docman_item_title_link_"+$folder_id+"").click
        end
        it "Find a children folder of the copied one" do
            folder       = @runner.find_element(:xpath, '//a[contains(@class, "docman_item_type_folder")]')
            $children_id = folder[:href].split("&action=collapseFolder&id=")
            $children_id = $children_id[1]
        end
        it "Open menu of the children folder" do
            @runner.find_element(:xpath, "//a[@id='docman_item_show_menu_"+$children_id+"']/img").click
        end
        it "User is document writer on the children folder" do
            @runner.find_element(:class, "docman_item_option_newfolder").text.should == "New folder"
        end
    end
    describe "#regression" do
        it "Paste doesn't exist in the menu of a children folder of the copied folder" do
            begin
                @runner.find_element(:class, "docman_item_option_paste")
                # TODO: Find a better way to detect this as a regression
                true.should eq(false)
            rescue
                true.should eq(true)
            end
        end
    end
    describe "#precondition" do
        it "Return to docman root" do
            @runner.find_element(:link, "Documents").click
        end
        it "Open menu of docman root" do
            @runner.find_element(:css, "img.docman_item_icon").click
        end
        it "Menu of docman root is open" do
            @runner.find_element(:class, "docman_item_option_newfolder").text.should == "New folder"
        end
    end
    describe "#regression" do
        it "Paste exist in the menu of a parent of the copied folder" do
            @runner.find_element(:class, "docman_item_option_paste").text.should == "Paste"
        end
    end
end