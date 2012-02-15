require "rubygems"
require "selenium-webdriver"

class TuleapValidation

  def setup
    Selenium::WebDriver::Firefox.path = "/usr/bin/firefox"
    @driver = Selenium::WebDriver.for :firefox
    @driver.manage.timeouts.implicit_wait = 30
    @driver.get "http://codex"
    @verification_errors = []
  end
  
  def teardown
    @driver.quit
    #assert_equal [], @verification_errors
  end

  def login
    @driver.find_element(:link, "My Personal Page").click
    @driver.find_element(:name, "form_loginname").clear
    @driver.find_element(:name, "form_loginname").send_keys "wrong_login"
    @driver.find_element(:name, "form_loginname").clear
    @driver.find_element(:name, "form_loginname").send_keys "login"
    @driver.find_element(:name, "form_pw").clear
    @driver.find_element(:name, "form_pw").send_keys "password"
    @driver.find_element(:name, "login").click
  end
  
  def addArtifact
    @driver.find_element(:link, "Public project 4").click
    @driver.find_element(:link, "Trackers v5").click
    @driver.find_element(:link, "Selenium_tracker1319472027").click
    @driver.find_element(:link, "Submit new artifact").click
    @driver.find_element(:name, "artifact[303]").clear
    @driver.find_element(:name, "artifact[303]").send_keys "New Ruby::Unit webdriver artifact"
    @driver.find_element(:name, "submit_and_continue").click
  end

  def addTrackerV5Widget
    @driver.find_element(:link, "My Personal Page").click
    @driver.find_element(:link, "Customize").click
    @driver.find_element(:link, "Trackers").click
    #@driver.find_element(:xpath, "//ul[@id='widget-categories']/li[6]/a/span").click
    #element_present?(:xpath, "name[plugin_tracker_myartifacts][azdd]")
    @driver.find_element(:name, "name[plugin_tracker_myartifacts][add]").click
  end

end
