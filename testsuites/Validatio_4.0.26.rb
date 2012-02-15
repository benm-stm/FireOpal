#--- Start Conf in setup here
# host = http://codex ( Web application to be tested )
# client = client ( Ip address of the client machine )
# browser = firefox ( Browser to be launched )
# user = login ( Login of a user )
# project = project ( Unix name of a project )
# project_id = 1 ( Id of the project )
# tracker_shortname = trk ( Shortname of the tracker )
# docman_root_id = 1 ( Id of the root of docman )
#--- End Conf

class Configuration

    def setup
        driver = Selenium::WebDriver.for :remote,:url => 'client',:desired_capabilities => :firefox
        driver.get 'http://codex'
        @driver.manage.timeouts.implicit_wait = 30
    end

    def teardown
        driver.quit
    end

    def login
        driver.find_element(:name, "form_loginname").send_keys "login"
        driver.find_element(:name, "form_pw").send_keys "password"
        driver.find_element(:name, "login").click
    end

end

require 'test'
require 'tuleap_spec'
require 'tuleap'

describe Validatio_4.0.26 do
    it "Run testcase test" do
        test_0 = test.new
        test_0.run()
    end

    it "Run testcase tuleap_spec" do
        test_1 = tuleap_spec.new
        test_1.run()
    end

    it "Run testcase tuleap" do
        test_2 = tuleap.new
        test_2.run()
    end

end
