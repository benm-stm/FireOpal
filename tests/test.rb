require 'rubygems'
require 'selenium-webdriver'

begin
  driver = Selenium::WebDriver.for :remote, :url => "http://server:4444/wd/hub", :desired_capabilities => :firefox
  driver.get "http://codex"
  driver.save_screenshot "/tmp/test.png"
ensure
  driver.quit
end
