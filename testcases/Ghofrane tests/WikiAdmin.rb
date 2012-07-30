#--------Test Summary------------
# this test Check that a non-wiki admin can NOT access to the admin page.
#
#--------Config dependencies-----
# host
# project_id
# project_short_name
#--------Test(s) dependencies----
# 

describe "non-wiki admin can NOT do any administration action" do     
	describe "#---------precondition------------:" do
		it "go to  the project link" do
			$link = @setup['host']['value'] + '/projects/' + @setup['project_short_name']['value']
			@driver.navigate.to $link
		end
		it "test if the user is a project member" do
		(@driver.find_element(:class, "contenttable").text.include? "Permission Denied").should be_false
		end
	end
	describe "#--------steps-----------------:" do
		it "try to access to wiki admin section" do
			$link = @setup['host']['value'] + '/wiki/admin/index.php?group_id=' + @setup['project_id']['value']
			@driver.navigate.to $link
		end
		it "Display an error message" do
			(@driver.find_element(:class, "feedback").text.include? "Permission Denied").should be_true
		end
	end
end

