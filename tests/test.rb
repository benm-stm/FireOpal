require "rubygems"
require "selenium-webdriver"
require "test/unit"
require 'capybara'
require 'capybara/dsl'

class RubyUnitWebdriverAddartifact < Test::Unit::TestCase
include Capybara::DSL
  def setup
    Capybara.current_driver = :selenium
    Capybara.app_host = 'http://codex.cro.st.com'
    Capybara.run_server = false
    Capybara.register_driver :selenium do |app|
      Capybara::Selenium::Driver.new(app, {:browser => :remote, :url => "http://10.157.15.160:4444/wd/hub"})
    end
    visit('/')
  end
  
  def login
    find(:xpath, "//a[@href='/my/']").click
    fill_in('form_loginname', :with => 'blah')
    fill_in('form_pw', :with => 'bloh')
    find("input[type='submit']").click
    page.has_content?('Personal Page')
  end
  
  def test_
    self.login
  end
  
    #page.driver.browser.save_screenshot("/tmp/test.png")
end
