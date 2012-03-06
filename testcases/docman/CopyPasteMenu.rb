#--- Start tags
# read_only
# docman_write
#--- End tags

describe "Display of paste in menu" do
    describe "#precondition:" do
        it "Go to my personal page" do
            @driver.find_element(:link, "My Personal Page").click
        end
        it "Enter the project" do
            @driver.find_element(:link, @setup['project_name']['value']).click
        end
        it "Enter the documents service" do
            @driver.find_element(:link, "Documents").click
        end
        it "Open menu of docman root" do
            @driver.find_element(:css, "img.docman_item_icon").click
        end
        it "User is document writer on docman root" do
            @driver.find_element(:class, "docman_item_option_newfolder").text.should == "New folder"
        end
        it "Close the menu" do
            @driver.find_element(:link, "[Close]").click
        end
        it "Find a folder other than docman root" do
            folder = @driver.find_element(:class, "docman_item_type_folder")
            $folder_id = folder[:href].split("&action=collapseFolder&id=")
            $folder_id = $folder_id[1]
        end
        it "Open menu of the selected folder" do
            @driver.find_element(:xpath, "//a[@id='docman_item_show_menu_"+$folder_id+"']/img").click
        end
        it "User is document writer on the selected folder" do
            @driver.find_element(:class, "docman_item_option_cut").text.should == "Cut"
        end
        it "Cut the selected folder" do
            @driver.find_element(:class, "docman_item_option_cut").click
        end
        it "Feedback of the cut is displayed" do
            # TODO
        end
        it "Open menu of the selected folder after cut" do
            @driver.find_element(:xpath, "//a[@id='docman_item_show_menu_"+$folder_id+"']/img").click
        end
        it "Menu of the selected folder is open" do
            @driver.find_element(:class, "docman_item_option_cut").text.should == "Cut"
        end
    end
    describe "#regression:" do
        it "Paste doesn't exist in the menu of the copied folder" do
            # TODO: Assert something not present
            #@driver.find_element(:class, "docman_item_option_paste")
        end
    end
    describe "#precondition:" do
        it "Collapse the selected folder" do
            # TODO
        end
        it "Find a children folder of the copied one" do
            # TODO
        end
        it "Open menu of the children folder" do
            # TODO
        end
        it "User is document writer on the children folder" do
            # TODO
        end
    end
    describe "#regression:" do
        it "Paste doesn't exist in the menu of a children folder of the copied folder" do
            # TODO
        end
    end
    describe "#precondition:" do
        it "Open menu of docman root" do
            @driver.find_element(:css, "img.docman_item_icon").click
        end
        it "Menu of docman root is open" do
            @driver.find_element(:class, "docman_item_option_newfolder").text.should == "New folder"
        end
    end
    describe "#regression:" do
        it "Paste exist in the menu of a parent of the copied folder" do
            @driver.find_element(:class, "docman_item_option_paste").text.should == "Paste"
        end
    end
end
