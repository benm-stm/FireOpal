#--- Start Conf in setup here
# host = http://codex ( Web application to be tested )
# client = client ( Ip address of the client machine )
# browser = firefox ( Browser to be launched )
# user = login ( Login of a user )
# project = project ( Unix name of a project )
# project_id = 1 ( Id of the project )
# tracker = tracker ( Name of a tracker )
# tracker_shortname = trk ( Shortname of the tracker )
# docman_root_id = 1 ( Id of the root of docman )
#--- End Conf

#--- Test Cases list ---
# docman/CopyPasteMenu.rb *
# trackerV3/tuleap.rb *
# template.rb 
#--- Test Cases End ---

require 'rubygems'
require 'selenium-webdriver'
require 'rspec/autorun'
require 'json'

class Configuration

    def setup(setup)
        @setup  = setup
        @driver = Selenium::WebDriver.for :remote, :url => "http://#{@setup['client']['value']}:4444/wd/hub", :desired_capabilities => @setup['browser']['value'].to_sym
        @driver.get @setup['host']['value']
        @driver.manage.timeouts.implicit_wait = 30
    end

    def teardown
        @driver.quit
    end

    def login
        @driver.find_element(:name, "form_loginname").send_keys @setup['user']['value']
        @driver.find_element(:name, "form_pw").send_keys @setup['password']['value']
        @driver.find_element(:name, "login").click
    end

    def getdriver
        @driver
    end

end


# Here Comes RSpec examples 


describe "docman" do

    before(:all) do
        @valid = Configuration.new
        @setup  = JSON.parse('{"host":{"value":"http:\/\/codex","description":"Web application to be tested","type":"text"},"client":{"value":"client","description":"Ip address of the client machine","type":"text"},"browser":{"value":"firefox","description":"Browser to be launched","type":"text"},"user":{"value":"login","description":"Login of a user","type":"text"},"password":{"value":"password","description":"Password of the user","type":"password"},"project":{"value":"project","description":"Unix name of a project","type":"text"},"project_id":{"value":"1","description":"Id of the project","type":"text"},"tracker":{"value":"tracker","description":"Name of a tracker","type":"text"},"tracker_shortname":{"value":"trk","description":"Shortname of the tracker","type":"text"},"docman_root_id":{"value":"1","description":"Id of the root of docman","type":"text"}}')
        @valid.setup(@setup)
        @valid.login()
        @driver = @valid.getdriver
    end

#---- Test case docman/CopyPasteMenu ----
    describe "docman/CopyPasteMenu" do

        before(:all) do
            @driver.navigate.to @setup['host']['value'] + '/my/'
        end

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
                end
            end
            describe "#precondition:" do
                it "Collapse the selected folder" do
                end
                it "Find a children folder of the copied one" do
                end
                it "Open menu of the children folder" do
                end
                it "User is document writer on the children folder" do
                end
            end
            describe "#regression:" do
                it "Paste doesn't exist in the menu of a children folder of the copied folder" do
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

    end
#---- End test case docman/CopyPasteMenu ----

#---- Test case trackerV3/tuleap ----
    describe "trackerV3/tuleap" do

        before(:all) do
            @driver.navigate.to @setup['host']['value'] + '/my/'
        end

        describe "Test logging in" do
            describe "#precondition" do
                it "Clear the login field" do
                    @driver.find_element(:name, "form_loginname").clear
                end
                it "Fill the login field" do
                    @driver.find_element(:name, "form_loginname").send_keys @setup['user']['value']
                end
                it "Fill the password field" do
                    @driver.find_element(:name, "form_pw").send_keys @setup['password']['value']
                end
                it "Click on submit button" do
                    @driver.find_element(:name, "login").click
                end
            end
            describe "#regression" do
                it "Test the wrong title of the page" do
                    (@driver.title).should == "wrong title"
                end
                it "Test the correct title of the page" do
                    (@driver.title).should == "good tiltle"
                end
                it "compare 1 to 1" do
                    1.should eq(1)
                end
            end
        end

    end
#---- End test case trackerV3/tuleap ----

#---- Test case template ----
    describe "template" do

        before(:all) do
            @driver.navigate.to @setup['host']['value'] + '/my/'
        end

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

    end
#---- End test case template ----

    after(:all) do
        @valid.teardown()
    end

end

