describe "Add a user to an existing user group" do

        before(:all) do
            @runner.navigate.to @params['host']['value'] + '/my/'
        end
             describe "Add a user to group" do
                it "Open project" do
                    @runner.find_element(:id, "navbar-project").click
                    @runner.find_element(:link, "TestCase").click
                end
                it "Go To Project Adminstration" do
                    @runner.find_element(:id, "sidebar-admin").click
                end
                it  "Click on the User Groups Admin link" do
                    @runner.find_element(:link, "User Groups Admin").click
                end
                it "Click on the group link" do
                    @runner.find_element(:link, "QA").click
                end
                   it "Click on the member link" do
                    @runner.find_element(:link, "Members").click
                end
                   it "Add an Ldap user" do
                    @runner.find_element(:id, "ugroup_add_user").click
                    @runner.find_element(:id, "ugroup_add_user").clear
                    @runner.find_element(:id, "ugroup_add_user").send_keys "doghrim"
                    @runner.find_element(:css, "input.btn").click
                end      
                    it "Add a non  Ldap user" do
                    @runner.find_element(:id, "ugroup_add_user").click
                    @runner.find_element(:id, "ugroup_add_user").clear
                    @runner.find_element(:id, "ugroup_add_user").send_keys "admin"
                    @runner.find_element(:css, "input.btn").click
                end

       end
    end

