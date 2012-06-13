#--------Test Summary------------
# This test Delete existing Git repository from Codex interface.
#
#--------Config dependencies-----
# project name: privateprjtest
# Git repository name: TestingValidation1
#
#--------Test(s) dependencies----
# CreateProject.rb
# ActivateGitService.rb
# CreateRepoGit.rb
describe "Delete existing Git repo" do
    describe "-------Precondition--------" do
        it " redirection to \"privateprjtest\" project then Git service"do
        @driver.find_element(:link, "privateprjtest").click
        @driver.find_element(:link, "Git").click
        end
    end
    describe "--------------Steps---------------" do
        it "select the repositry" do
            @driver.find_element(:link, "TestingValidation1").click
        end
        it "enter to repository management" do
            @driver.find_element(:link, "Repository management").click
        end
        it "click on delete this repository" do
            @driver.find_element(:name, "confirm_deletion").click
        end
        it "confirm deletation" do
            @driver.find_element(:id, "submit").click
        end
    end
    describe "--------Post condition--------" do
        it "feedback message displayed" do
            ( @driver.find_element(:id, "feedback").text.include? "Repository has been deleted").should be_true
        end
    end
end
