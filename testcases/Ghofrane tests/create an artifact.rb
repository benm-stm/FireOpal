describe "Create an artifact" do
    describe "#precondition:" do
        it "go to trakerV5" do
            $link = @setup['host']['value'] + '/plugins/tracker/?group_id=' + @setup['project_id']['value']
            @driver.navigate.to $link
        end
    end
    describe "#regression:" do
        it "clik to submit a new artifact" do
            $link = @setup['host']['value'] + '/plugins/tracker/?tracker=859&func=new-artifact'
            @driver.navigate.to $link
        end
        it "putt the artifact title" do
            @driver.find_element(:name, "artifact[2752]").clear
            @driver.find_element(:name, "artifact[2752]").send_keys"artifact 1"
        end  
        it "Create the interfact" do
            @driver.find_element(:xpath, "(//input[@value='Submit'])").click
            $assert =  @driver.find_element(:class, "feedback_info").text.include? "Artifact Successfully Created".should be_true
        end
    end
end
