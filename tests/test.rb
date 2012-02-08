require 'rubygems'
require 'selenium-webdriver'
require 'rspec/autorun'

class Test
    def run()
        driver = Selenium::WebDriver.for :remote, :url => "http://server:4444/wd/hub", :desired_capabilities => :firefox
        driver.get "http://codex"
        describe "Test logging in" do
            driver.find_element(:name, "form_loginname").send_keys "login"
            driver.find_element(:name, "form_pw").send_keys "password"
            driver.find_element(:name, "login").click
            it "Test the wrong title of the page" do 
                (driver.title).should == "wrong title"
            end
            it "Test the correct title of the page" do 
                (driver.title).should == "good tiltle"
            end
            it "compare 1 to 1" do
                1.should eq(1)
            end
            driver.save_screenshot "/tmp/test.png"
            driver.quit
        end
    end
end
