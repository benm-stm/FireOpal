#--- Start test Summary
#this test Check that a non-project admin can NOT access to the admin page.
#--- End test Summary

#---Start config variables
# host
# project_id
# project_short_name
#---End config variables

#--- Start dependency list
#--- End dependency list

describe "non-project admin" do
    describe "#Precondition" do
        it "log to the project link" do
            $link = @setup['host']['value'] + '/projects/' + @setup['project_short_name']['value']
            @driver.navigate.to $link
        end
        it "test if the user is a project member" do
            (@driver.find_element(:class, "contenttable").text.include? "Permission Denied").should be_false
        end
    end
    describe "#Steps" do
        it "try to access to project admin section" do
            $link = @setup['host']['value'] + '/project/admin/?group_id=' + @setup['project_id']['value']
            @driver.navigate.to $link
        end
        it "display an error message: Insufficient Group Access" do
            (@driver.find_element(:class, "feedback").text.include? "Insufficient Group Access").should be_true
        end
    end
end
