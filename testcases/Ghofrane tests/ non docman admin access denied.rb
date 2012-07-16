describe "non-docman admin can NOT do any administration action" do
    describe "#precondition:" do
        it "Find Documents link" do
            $link = @setup['host']['value'] + '/plugins/docman/?group_id=' + @setup['project_id']['value']
            @driver.navigate.to $link
        end
    end
    describe "#regression:" do
        it "log on the administration URL" do
            $link = @setup['host']['value'] + '/plugins/docman/?group_id=' + @setup['project_id']['value'] + '&action=admin'
            @driver.navigate.to $link
        end
        it "is not an admin" do
            (@driver.find_element(:class, "feedback_error").text.include? "You do not have sufficient access rights to administrate the document manager").should be_true
        end
    end
end