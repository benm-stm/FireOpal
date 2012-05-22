require "rubygems"
require "selenium-webdriver"
require "rspec/autorun"
require "rspec/expectations"



describe "test submit a message in the forum  " do

   # définir les paramètres de connexion
    before(:each) do
        @driver = Selenium::WebDriver.for :firefox
        @base_url = "https://codex-test.cro.st.com/"
        @driver.manage.timeouts.implicit_wait = 1
        @verification_errors = []
        @passed1,@passed2=true 
        @failed1,@failed2=false

    end
    after(:each) do
        @driver.quit
        @verification_errors.should == []
    end

#---------- Here Comes Rspec examples code ----------------
    describe "#Test " do
        it " should print a welcome message " do
            puts " Mon premier test avec ruby rspec (Webdriver) "
        end 
    end

# Test Case:1
    describe "LogOn" do
        context " Codex-test " do
            it " with Simplex Account  " do
               begin
                  begin
                      @driver.get(@base_url + "/account/login.php?return_to=%2Fmy%2F")
                      puts " Redirection to /**Login Account**/ "
                  rescue 
                      puts " Redirection to /**Login Account**/ was failed "
                  end
                  @driver.find_element(:name, "form_loginname").clear
                  @driver.find_element(:name, "form_loginname").send_keys "disciplus_simplex"
                  @driver.find_element(:name, "form_pw").clear
                  @driver.find_element(:name, "form_pw").send_keys "DSeevaT"
                  @driver.find_element(:name, "login").click
    
					 
		 begin 
                      @driver.find_element(:link, "Guinea Pig").click
                      puts "Redirection to /**Guinea Pig**/:1 link" 
                  rescue 
                      puts "Redirection to /**Guinea Pig**/:1 link was Failed"
                  end 
                  begin 
					@driver.find_element(:link, "Forums").click
					puts "Redirection to /**Forums**/:1 link" 
                  rescue 
                      puts "Can Not Redirect To /**Forums**/Link"
                  end 
		begin 
					@driver.find_element(:partial_link_text, "General").click
					puts "Redirection to /**General Discussion**/:1 link" 
                  rescue 
                      puts "Can Not Redirect To /**General Discussion**/Link"
                  end
		begin 
					@driver.find_element(:name, "subject").clear
					@driver.find_element(:name, "subject").send_keys "Testing new thread"
					puts "Putting thread subject" 
                  rescue 
                      puts "Can Not put thread subject"
                  end
		begin 
					@driver.find_element(:name, "body").clear
					@driver.find_element(:name, "body").send_keys "Test for message post"
					puts "Putting thread body" 
                  rescue 
                      puts "Can Not put thread body"
                  end
				

					
					begin

						@driver.find_element(:name, "SUBMIT").click
					rescue
						puts "Can Not submit new thread"
 
					end 
				


				  
					begin 
    
					(@driver.find_element(:css, "#feedback > ul.feedback_info >  li").text).should == "Message Posted - Email sent - people monitoring"

					rescue
						puts "Message not posted"					
					end
				end
			
			end 
		end	
   
	end	

end
