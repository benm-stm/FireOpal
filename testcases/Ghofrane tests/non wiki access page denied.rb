describe "non-wiki admin can NOT do any administration action" do
    describe "#precondition:" do
        it "Find wiki link" do
            $link = @setup['host']['value'] + '/wiki/admin/index.php?group_id=' + @setup['project_id']['value']
            @driver.navigate.to $link
        end
    end
    describe "#regression:" do 
        it "is not an admin" do
            (@driver.find_element(:class, "feedback").text.include? "Permission Denied.").should be_true
        end
    end
end
