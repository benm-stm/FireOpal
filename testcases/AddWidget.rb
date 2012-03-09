#--- Start tags
# write
#--- End tags
describe "add a widget to My dashbiard" do
    describe "#precondition:" do
        it "should find my personal page" do
            @driver.find_element(:link, "My Personal Page").click
        end
        it "should find customize" do
            @driver.find_element(:link, "Customize").click
        end
        it "should find widget category" do
            @driver.find_element(:link, @setup['widget_category']['value']).click
        end
    end
    describe "add the widget" do
        it "Hint the add button of the widget" do
            @driver.find_element(:name, "name["+@setup['widget_name']['value']+"][add]").click
            @driver.manage.timeouts.implicit_wait = 5
        end
    end
    describe "#regression:" do
        it "should find my personal page" do
            @driver.find_element(:link, "My Personal Page").click
        end
        it "look for the widget on My dashboard" do
            #@TODO find a way to check that the widget was added, you have probably to review widgets css/Html structure       
            @driver.manage.timeouts.implicit_wait = 5
        end
    end
end