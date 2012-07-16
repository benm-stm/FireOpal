describe "files admin" do
    describe "#precondition:" do
        it "Find files link" do
            $link = @setup['host']['value'] + '/file/admin/?group_id=' + @setup['project_id']['value']
            @driver.navigate.to $link
        end
    end
    describe "#regression:" do   
        it "is not an admin" do
            (@driver.find_element(:class, "feedback").text.include? "Permission Denied.").should be_true
        end
    end
end