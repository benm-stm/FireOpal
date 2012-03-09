#--- Start tags
# write
#--- End tags
describe "Add a widget to My dashboard" do
    describe "#precondition:" do
        it "Find my personal page" do
            @driver.find_element(:link, "My Personal Page").click
        end
        it "Find customize link" do
            @driver.find_element(:link, "Customize").click
        end
        it "Find widget category" do
            @driver.find_element(:link, "Source Code Management").click
        end
        it "Hint the add button of the widget" do
            @driver.find_element(:name, "name[mylatestsvncommits][add]").click
            @driver.manage.timeouts.implicit_wait = 5
        end
    end
    describe "#regression:" do
        it "Find my personal page" do
            @driver.find_element(:link, "My Personal Page").click
        end
        it "Look for the widget on My dashboard" do
            #@TODO find a way to check that the widget was added, you have probably to review widgets css/Html structure       
            @driver.manage.timeouts.implicit_wait = 5
        end
    end
end