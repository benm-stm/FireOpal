describe "non-project admin" do
    describe "#precondition:" do
        it "Find project link" do
            $link = @setup['host']['value'] + '/project/admin/?group_id=' + @setup['project_id']['value']
            @driver.navigate.to $link
        end
    end
    describe "#regression:" do
        it "is not an admin" do
            (@driver.find_element(:class, "feedback").text.include? "Insufficient Group Access.").should be_true
        end
    end
end