#--------Test Summary------------
#this test Check that a project member who is not Files admin can NOT access to the administration page.
#
#--------Config dependencies-----
# host
# project_id
# project_short_name
#--------Test(s) dependencies----
# 

describe "FileAdmin" do
	describe "#---------precondition------------:" do
		it "log to the project link" do
			$link = @setup['host']['value'] + '/projects/' + @setup['project_short_name']['value']
		@driver.navigate.to $link
		end
		it "test if the user is a project member" do
			(@driver.find_element(:id, "feedback").text.include? "Permission Denied").should be_false
		end
	end
	describe "#--------steps-----------------:" do
		it "try to access to file admin section" do
			$link = @setup['host']['value'] + '/file/admin/?group_id=' + @setup['project_id']['value']
		@driver.navigate.to $link
		end
		it "display an error message:don't have sufficient access right." do
			(@driver.find_element(:class, "feedback").text.include? "Permission Denied").should be_true
		end
	end
end
