describe "Delete user group" do
        before(:all) do
            @runner.navigate.to @params['host']['value'] + '/my/'
        end
        describe "Create Group" do
                it "select project" do
                   @runner.find_element(:id, "navbar-project").click
                   @runner.find_element(:link, "TestCase").click
                end
                it "Project Adminstration" do
                   @runner.find_element(:xpath, "//*[@id='sidebar-admin']/span").click
                end
                 it "Users Group Admin" do
                    @runner.find_element(:link, "User Groups Admin").click
                end
                 it "Delete user group" do
                    @runner.find_element(:xpath, "/html/body/div[1]/div[4]/div/div/div[2]/table[2]/tbody/tr[10]/td[4]/a").click
                    popup = @runner.switch_to.alert
                    popup.accept
                end
        end
end


