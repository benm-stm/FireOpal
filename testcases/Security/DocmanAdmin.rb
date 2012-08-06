#--- Start test Summary
#this test prevent a non document admin to open the admin page
#--- End test Summary

#---Start config variables
# host
# project_id
# project_short_name 
#---End config variables

#--- Start dependency list
#--- End dependency list 

describe "non-docman admin can NOT do any administration action" do
    describe "#Precondition" do
        it "log to the project link" do
            $link = @setup['host']['value'] + '/projects/' + @setup['project_short_name']['value']
            @driver.navigate.to $link
        end
    end
    describe "#Steps" do
        it "test if the user is a project member" do
            (@driver.find_element(:class, "contenttable").text.include? "Permission Denied").should be_false
        end
        it "try to access to docman admin section" do
            $link = @setup['host']['value'] + '/plugins/docman/?group_id=' + @setup ['project_id']['value'] + '&action=admin'
            @driver.navigate.to $link
        end
        it "Display an error message:You do not have sufficient access rights to administrate the document manager" do
            (@driver.find_element(:class, "feedback_error").text.include? "You do not have sufficient access rights to administrate the document manager").should be_true
        end
    end
end