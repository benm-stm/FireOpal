#--- Start Conf in setup here
# host = http://codex-test.cro.st.com ( Web application to be tested )
# client = 10.157.12.139 ( Ip address of the client machine )
# browser = firefox ( Browser to be launched )
# user = disciplus_simplex ( Login of a user )
# project = codex-cc ( Unix name of a project )
# project_id = 1750 ( Id of the project )
# tracker = Test ( Name of a tracker )
# tracker_shortname = trk ( Shortname of the tracker )
# docman_root_id = 1 ( Id of the root of docman )
# project_name = Codex Competence Center ( The Project name )
# tracker_id = 4072 ( 791 )
#--- End Conf

#--- Test Cases list ---
# trackerV5/AddTrackerDateReminder.rb 
# trackerV5/UpdateTrackerDateReminder.rb 
# docman/CreateWiki.rb 
# docman/AddApprovalReminder.rb 
# docman/UpdateApprovalReminder.rb 
# docman/CopyPasteMenu.rb 
# trackerV5/DeleteTrackerDateReminder.rb 
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
        @driver.navigate.to @setup['host']['value'] + '/my/'
        @driver.find_element(:name, "form_loginname").send_keys @setup['user']['value']
        @driver.find_element(:name, "form_pw").send_keys @setup['password']['value']
        @driver.find_element(:name, "login").click
    end

    def getdriver
        @driver
    end

end


# Here Comes RSpec examples 


describe "Validation_STCodexQA_Tuleap5.3_NT" do

    before(:all) do
        @valid = Configuration.new
        @setup  = JSON.parse('{"host":{"value":"http:\/\/codex-test.cro.st.com","description":"Web application to be tested","type":"text"},"client":{"value":"10.157.12.139","description":"Ip address of the client machine","type":"text"},"browser":{"value":"firefox","description":"Browser to be launched","type":"text"},"user":{"value":"disciplus_simplex","description":"Login of a user","type":"text"},"password":{"value":"DSeevaT","description":"Password of the user","type":"password"},"project":{"value":"codex-cc","description":"Unix name of a project","type":"text"},"project_id":{"value":"1750","description":"Id of the project","type":"text"},"tracker":{"value":"Test","description":"Name of a tracker","type":"text"},"tracker_shortname":{"value":"trk","description":"Shortname of the tracker","type":"text"},"docman_root_id":{"value":"1","description":"Id of the root of docman","type":"text"},"project_name":{"value":"Codex Competence Center","description":"The Project name","type":"text"},"tracker_id":{"value":"4072","description":"791","type":"text"}}')
        @valid.setup(@setup)
        @valid.login()
        @driver = @valid.getdriver
    end

#---- Test case trackerV5/AddTrackerDateReminder ----
    describe "trackerV5/AddTrackerDateReminder" do

        before(:all) do
            @driver.navigate.to @setup['host']['value'] + '/my/'
        end

        describe "Add new tracker date reminder" do
            describe "#precondition" do
                it "Find my personal page" do
                    @driver.find_element(:link, "My Personal Page").click
                end
                it "Find project" do
                    @driver.find_element(:link, @setup['project_name']['value']).click
                end
                it "Find tracker service" do
                    @driver.find_element(:link, "Trackers").click
                end
                it "Find target tracker" do
                    @driver.find_element(:link, @setup['tracker']['value']).click
                end
                it "Find notifications management interface" do
                    @driver.find_element(:link, "Notifications").click
                end
            end
            describe "#step" do
                it "Hint add reminder button" do
                    @driver.find_element(:id, "add_reminder").click
                end
                it "Select Ugroups to be notified" do
                    ugroups      = @driver.find_element(:name, "reminder_ugroup[]")
                    ugroupsMSBox = Selenium::WebDriver::Support::Select.new(ugroups)
                    ugroupsMSBox.select_by(:text, 'project_members')
                end
                it "Specify distance in days" do
                    @driver.find_element(:name, "distance").clear
                    @driver.find_element(:name, "distance").send_keys Time.now.to_i
                end
                it "Select notification type" do
                    notificationType = @driver.find_element(:name, "notif_type")
                    notifTypeSelect  = Selenium::WebDriver::Support::Select.new(notificationType)
                    notifValues = ["After", "Before"]
                    $notif_type = notifValues[rand(notifValues.length)]
                    notifTypeSelect.select_by(:text, $notif_type)
                end
                it "Select the date field on which the reminder will be applied" do
                    fieldDate       = @driver.find_element(:name, "reminder_field_date")
                    fieldDateSelect = Selenium::WebDriver::Support::Select.new(fieldDate)
                    fieldDateSelect.select_by(:text, "Due date")
                end
                it "Submit new tracker date reminder" do
                    @driver.find_element(:css, "td > input[name=\"submit\"]").click
                end
                it "Find new reminder info feed back" do
                    @driver.find_element(:class, "feedback_info").text.should include("Date Reminder successfully added")
                end
            end
        end

    end
#---- End test case trackerV5/AddTrackerDateReminder ----

#---- Test case trackerV5/UpdateTrackerDateReminder ----
    describe "trackerV5/UpdateTrackerDateReminder" do

        before(:all) do
            @driver.navigate.to @setup['host']['value'] + '/my/'
        end

        describe "Update a tracker date reminder" do
            describe "#precondition:" do
                it "Open notifications management interface" do
                    $link = @setup['host']['value'] + '/plugins/tracker/?tracker=' + @setup['tracker_id']['value'] + '&func=notifications'
                    @driver.navigate.to $link
                end
                it "Find a reminder to update" do
                    @driver.find_element(:id, "update_reminder")
                end
            end
            describe "#regression:" do
                it "Click on update reminder button" do
                    @driver.find_element(:id, "update_reminder").click
                end
                it "Select Ugroups to be notified" do
                    ugroups      = @driver.find_element(:name, "reminder_ugroup[]")
                    ugroupsMSBox = Selenium::WebDriver::Support::Select.new(ugroups)
                    ugroupsMSBox.select_by(:text, 'project_members')
                end
                it "Specify distance in days" do
                    @driver.find_element(:name, "distance").clear
                    @driver.find_element(:name, "distance").send_keys Time.now.to_i
                end
                it "Select notification type" do
                    notificationType = @driver.find_element(:name, "notif_type")
                    notifTypeSelect  = Selenium::WebDriver::Support::Select.new(notificationType)
                    notifTypeSelect.select_by(:text, 'After')
                end
                it "Submit the updated values of the tracker date reminder" do
                    @driver.find_element(:css, "td > input[name=\"submit\"]").click
                end
                it "Verify feedback message" do
                    begin
                        @driver.find_element(:class, "feedback_info").text.should include("Date Reminder successfully updated")
                    rescue
                        @driver.find_element(:class, "feedback_error").text.should include("Cannot duplicate Date Reminder")
                    end
                end
            end
        end

    end
#---- End test case trackerV5/UpdateTrackerDateReminder ----

#---- Test case docman/CreateWiki ----
    describe "docman/CreateWiki" do

        before(:all) do
            @driver.navigate.to @setup['host']['value'] + '/my/'
        end

        describe "Create a new document wiki" do
            describe "#precondition" do
                it "Find Documents link" do
                    $link = @setup['host']['value'] + '/plugins/docman/?group_id=' + @setup['project_id']['value']
                    @driver.navigate.to $link
                end
            end
            describe "#step" do
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
                    @driver.find_element(:xpath, "(//input[@value='Create document'])").click
                end
                it "verify the text returned" do
                    ( @driver.find_element(:class, "feedback_info").text.include? "Permissions successfully updated.").should be_true  
                end
            end
        end

    end
#---- End test case docman/CreateWiki ----

#---- Test case docman/AddApprovalReminder ----
    describe "docman/AddApprovalReminder" do

        before(:all) do
            @driver.navigate.to @setup['host']['value'] + '/my/'
        end

        describe "Browse to approval table menu" do
            describe "#precondition" do
                it "Find my personal page" do
                    @driver.find_element(:link, "My Personal Page").click
                end
                it "Find project" do
                    @driver.find_element(:link, @setup['project']['value']).click
                end
                it "Find document service" do
                    @driver.find_element(:link, "Documents").click
                end
                it "Open menu of docman root" do
                    @driver.find_element(:css, "img.docman_item_icon").click
                end
                it "Find approval table link" do
                    @driver.find_element(:link, "Approval table").click
                end
                it "Edit the approval table" do
                    @driver.find_element(:css, "strong > a").click
                end
                it "Verify that the approval table is created" do
                    @driver.find_element(:tag_name => "body").text.should include("Approval table global settings")
                end
                it "Verify that the reminder is not set" do
                    @driver.find_element(:id, "approval_table_reminder_checkbox").attribute("value").should eq("off")
                end
            end
            describe "#step" do
                it "Hint add reminder button" do
                    if @driver.find_element(:id, "approval_table_reminder_checkbox").attribute("value") == "off"
                        @driver.find_element(:id, "approval_table_reminder_checkbox").click
                    end
                    $value = 2
                    @driver.find_element(:name, "occurence").clear
                    @driver.find_element(:name, "occurence").send_keys $value
                    @driver.find_element(:css, "p > input[type=\"submit\"]").click
                end
                it "Verify that the occurence has been added" do
                    $value = @driver.find_element(:name, "occurence").attribute("value").to_i.should == $value
                end
                it "Check update feedback" do
                    @driver.find_element(:class, "feedback_info").text.should include("Table settings updated.")
                end
            end
        end
    end
#---- End test case docman/AddApprovalReminder ----

#---- Test case docman/UpdateApprovalReminder ----
    describe "docman/UpdateApprovalReminder" do

        before(:all) do
            @driver.navigate.to @setup['host']['value'] + '/my/'
        end

        describe "Browse to approval table menu" do
            describe "#precondition" do
                it "Open the approval table" do
                    $link = @setup['host']['value'] + '/plugins/docman/?group_id=' + @setup['project_id']['value'] + '&action=approval_create&id=' + @setup['docman_root_id']['value']
                    @driver.navigate.to $link
                end
                it "Verify that user can update the aproval table" do
                end
            end
            describe "#step" do
                it "Reminder is on" do
                    @driver.find_element(:name, "reminder").attribute("value").should == "on"
                end
                it "Change form values" do
                    @driver.find_element(:name, "reminder").click
                    @driver.find_element(:name, "reminder").click
                    Selenium::WebDriver::Support::Select.new(@driver.find_element(:id, "period")).select_by(:text, "Weeks")
                    $value = @driver.find_element(:name, "occurence").attribute("value").to_i + 1
                    @driver.find_element(:name, "occurence").clear
                    @driver.find_element(:name, "occurence").send_keys $value
                    @driver.find_element(:xpath, "//input[@value='Update']").click
                end
                it "Verify that the occurence has been updated" do
                    $value = @driver.find_element(:name, "occurence").attribute("value").to_i.should == $value * 7
                end
                it "Check update feedback" do
                    @driver.find_element(:class, "feedback_info").text.should include("Table settings updated.")
                end
            end
        end
    end
#---- End test case docman/UpdateApprovalReminder ----

#---- Test case docman/CopyPasteMenu ----
    describe "docman/CopyPasteMenu" do

        before(:all) do
            @driver.navigate.to @setup['host']['value'] + '/my/'
        end

        describe "Display of paste in menu" do
            describe "#precondition" do
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
                    folder        = @driver.find_element(:xpath, '//a[contains(@class, "docman_item_type_folder")]')
                    $folder_class = folder[:class]
                    $folder_id    = folder[:href].split("&action=collapseFolder&id=")
                    $folder_id    = $folder_id[1]
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
                    text = " cut. You can now paste it wherever you want with 'Paste' action in popup menu."
                    (@driver.find_element(:id, "item_cut").text.include? text).should eq(true)
                end
                it "Open menu of the selected folder after cut" do
                    @driver.find_element(:xpath, "//a[@id='docman_item_show_menu_"+$folder_id+"']/img").click
                end
                it "Menu of the selected folder is open" do
                    @driver.find_element(:class, "docman_item_option_cut").text.should == "Cut"
                end
            end
            describe "#step" do
                it "Paste doesn't exist in the menu of the copied folder" do
                    begin
                        @driver.find_element(:class, "docman_item_option_paste")
                        true.should eq(false)
                    rescue
                        true.should eq(true)
                    end
                end
            end
            describe "#precondition" do
                it "Open the selected folder" do
                    @driver.find_element(:id, "docman_item_title_link_"+$folder_id+"").click
                end
                it "Find a children folder of the copied one" do
                    folder       = @driver.find_element(:xpath, '//a[contains(@class, "docman_item_type_folder")]')
                    $children_id = folder[:href].split("&action=collapseFolder&id=")
                    $children_id = $children_id[1]
                end
                it "Open menu of the children folder" do
                    @driver.find_element(:xpath, "//a[@id='docman_item_show_menu_"+$children_id+"']/img").click
                end
                it "User is document writer on the children folder" do
                    @driver.find_element(:class, "docman_item_option_newfolder").text.should == "New folder"
                end
            end
            describe "#step" do
                it "Paste doesn't exist in the menu of a children folder of the copied folder" do
                    begin
                        @driver.find_element(:class, "docman_item_option_paste")
                        true.should eq(false)
                    rescue
                        true.should eq(true)
                    end
                end
            end
            describe "#precondition" do
                it "Return to docman root" do
                    @driver.find_element(:link, "Documents").click
                end
                it "Open menu of docman root" do
                    @driver.find_element(:css, "img.docman_item_icon").click
                end
                it "Menu of docman root is open" do
                    @driver.find_element(:class, "docman_item_option_newfolder").text.should == "New folder"
                end
            end
            describe "#step" do
                it "Paste exist in the menu of a parent of the copied folder" do
                    @driver.find_element(:class, "docman_item_option_paste").text.should == "Paste"
                end
            end
        end

    end
#---- End test case docman/CopyPasteMenu ----

#---- Test case trackerV5/DeleteTrackerDateReminder ----
    describe "trackerV5/DeleteTrackerDateReminder" do

        before(:all) do
            @driver.navigate.to @setup['host']['value'] + '/my/'
        end

        describe "Delete a tracker date reminder" do
            describe "#precondition" do
                it "Open notifications management interface" do
                    $link = @setup['host']['value'] + '/plugins/tracker/?tracker=' + @setup['tracker_id']['value'] + '&func=notifications'
                    @driver.navigate.to $link
                end
                it "Find a reminder to update" do
                    @driver.find_element(:id, "delete_reminder")
                end
            end
            describe "#step" do
                it "Click on delete reminder button" do
                    @driver.find_element(:id, "delete_reminder").click
                end
                it "Confirm the deletion" do
                    @driver.find_element(:name, "confirm_delete").click
                end
                it "Verify feedback message" do
                    @driver.find_element(:class, "feedback_info").text.should include("Date Reminder successfully deleted")
                end
            end
        end
    end
#---- End test case trackerV5/DeleteTrackerDateReminder ----

    after(:all) do
        @valid.teardown()
    end

end

