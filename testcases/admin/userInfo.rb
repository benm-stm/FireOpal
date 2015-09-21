    describe "verify user" do

        before(:all) do
            @runner.navigate.to @params['host']['value'] + '/my/'
        end

        describe "search a user" do
            describe "#precondition" do
                it "Find my personal page" do
                    @runner.find_element(:link, "My Personal Page").click
                end
                it "Admin" do
                    @runner.find_element(:link, "Admin").click
                end
                it "Find input search" do
                    @runner.find_element(:name, "user_name_search").send_keys("benm");
                end

                   it  "Hint the button of search" do

                    @runner.find_element(:css, "input.btn").click
                    @runner.manage.timeouts.implicit_wait = 5
                end
           end
            describe "#regression" do
                it "Find my personal page" do
                    @runner.find_element(:link, "My Personal Page").click
                end
                it "Look for the widget on My dashboard" do
                    @runner.manage.timeouts.implicit_wait = 5
                end
            end
        end
    end

