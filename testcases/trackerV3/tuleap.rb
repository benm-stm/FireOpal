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
