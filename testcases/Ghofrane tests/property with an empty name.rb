describe "property with an empty name" do
    describe "#precondition:" do
        it "Find Documents link" do
            $link = @setup['host']['value'] + '/plugins/docman/?group_id=' + @setup['project_id']['value']
            @driver.navigate.to $link
        end
    end
    describe "#regression:" do
        it "create a property with an empty name" do
            $link = @setup['host']['value'] + '/plugins/docman/?group_id=' + @setup['project_id']['value'] + '&action=admin_metadata'
            @driver.navigate.to $link
            $link = @setup['host']['value'] + '/plugins/docman/?group_id=' + @setup['project_id']['value'] + '&action=admin_create_metadata'
            @driver.navigate.to $link
            @driver.find_element(:class, "highlight").send_keys "    "
            @driver.find_element(:name, "submit").click
        end
        it "please fill this field" do
            (@driver.find_element(:class, "feedback_error").text.include? "Property name is required, please fill this field.").should be_true
        end
    end
end