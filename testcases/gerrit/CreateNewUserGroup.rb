
describe "Create a new user group" do
        before(:all) do
            @runner.navigate.to @params['host']['value'] + '/my/'
        end
        describe "Create Group" do
                it "select project" do 
                   @runner.find_element(:id, "navbar-project").click
                   @runner.find_element(:link, "TestCase").click
                end
                it "Project Adminstration" do
                   @runner.find_element(:css, "span[title=\"Project Administration\"]").click
                end
                 it "Users Group Admin" do
                    @runner.find_element(:link, "User Groups Admin").click
                end 
                 it "click on the add new user group button" do
                    @runner.find_element(:link, "Create a New User Group").click
                end
                 it "Name of the group" do
                    @runner.find_element(:name, "ugroup_name").click
                    @runner.find_element(:name, "ugroup_name").clear
                    @runner.find_element(:name, "ugroup_name").send_keys "QA"
                end 
                 it "Descritpion of the group" do
                    @runner.find_element(:name, "ugroup_description").clear
                    @runner.find_element(:name, "ugroup_description").send_keys "this is a description" 
                end 
                 it "Click on the submit button" do
                    @runner.find_element(:css, "input[type=\"submit\"]").click 
                end
                 it "verify Creation" do
                    creation_content = @runner.find_element(:xpath, "//*[@id='feedback']/ul/li").text
                    creation_content.should == "Successfully Created User Group"
                end

                 it "Click on the members link" do 
                    @runner.find_element(:link, "Members").click
                end    
                 it "Add an LDAP user" do 
                    @runner.find_element(:id, "ugroup_add_user").click
                    @runner.find_element(:id, "ugroup_add_user").clear
                    @runner.find_element(:id, "ugroup_add_user").send_keys "belgaief"
                    @runner.find_element(:css, "input.btn").click
                end
                 it "ADD a non LDAP user" do
                    @runner.find_element(:id, "ugroup_add_user").click
                    @runner.find_element(:id, "ugroup_add_user").clear
                    @runner.find_element(:id, "ugroup_add_user").send_keys "admin"
                    @runner.find_element(:css, "input.btn").click 
                end 
                 it "Delete a  non LDAP  user" do 
                    @runner.find_element(:css, "img[alt=\"remove\"]").click
                end
        end         
end  
