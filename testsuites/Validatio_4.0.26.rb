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
#  test.rb
#--- Test Cases End ---

require 'rubygems'
require 'selenium-webdriver'
require 'rspec/autorun'

class Configuration

    def setup
        @driver = Selenium::WebDriver.for :remote, :url => 'http://10.157.12.139:4444/wd/hub', :desired_capabilities => :firefox
        @driver.get 'http://codex-test.cro.st.com'
        @driver.manage.timeouts.implicit_wait = 30
    end

    def teardown
        @driver.quit
    end

    def login
        @driver.find_element(:name, "form_loginname").send_keys "login"
        @driver.find_element(:name, "form_pw").send_keys "password"
        @driver.find_element(:name, "login").click
    end

    def getdriver
        @driver
    end

end


# Here Comes RSpec examples 


class Thing
end

class Logging
end

class TuleapValidation
end


describe "Validatio_4.0.26" do

    before(:all) do
        @valid = Configuration.new
        @valid.setup()
        @l =Thing.new
        @valid.login()
        @driver = @valid.getdriver
    end


    describe Thing do
        it "compare 1 to 1" do
            1.should eq(1)
        end
    end
    
	describe Logging do
        it "Fill the form and submit" do
            @driver.find_element(:name, "form_loginname").send_keys "terzino"
            @driver.find_element(:name, "form_pw").send_keys "pwd"
            @driver.find_element(:name, "login").click
        end
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
    
	describe TuleapValidation do
        thing = @l.describe
        if thing.example.execution_result[:status] !='failed'
            it "should print test" do
                2.should eq(2)
                puts ("ooooooooooooooooup")
            end
        end
    end

    after(:all) do
        @valid.teardown()
    end

end
