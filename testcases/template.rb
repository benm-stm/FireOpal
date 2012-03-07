#--- Start tags
# write
# site_admin
#--- End tags

#--- Start dependency list
# docman/CopyPasteMenu.rb
# trackerV3/tuleap.rb
# template.rb
#--- End dependency list

describe "Testcase name" do
    describe "#precondition" do
        it "Clear the first field" do
            @driver.find_element(:name, "form_element_example_1").clear
        end
        it "Fill the first field" do
            @driver.find_element(:name, "form_element_example_1").send_keys @setup['user']['value']
        end
        it "Fill another field" do
            @driver.find_element(:name, "form_element_example_2").send_keys @setup['password']['value']
        end
        it "Click on a button" do
            @driver.find_element(:name, "button_example").click
        end
    end
    describe "#regression" do
        it "Test the wrong title of the page" do
            (@driver.title).should == "wrong title"
        end
        it "Test the correct title of the page" do
            (@driver.title).should == "good tiltle"
        end
    end
end
