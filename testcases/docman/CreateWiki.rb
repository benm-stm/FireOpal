#--- Start tags
# This testcase allows you to create a document with type wiki.
#--- End tags
describe "Create a new document wiki" do
    describe "#precondition:" do
	#The project "gpig" should exist and "simplex" should be a document writer
        it "Find Guinea Pig link" do
            @driver.find_element(:partial_link_text, @setup['project']['value']).click
        end
        it "Find Documents link" do
            @driver.find_element(:link, "Documents").click
        end
	end
	describe "Steps" do
        it "Find Create a New Document link" do
            @driver.find_element(:class, "docman_item_icon").click
            @driver.find_element(:class, "docman_item_option_newdocument").click
        end
		
		it "Put the title" do
			@driver.find_element(:id, "title").clear
			@driver.find_element(:id, "title").send_keys "new_wiki"
		end
		it "Select the type wiki" do
			@driver.find_element(:id, "item_item_type_5").click
		end
		it "Put the wiki name" do
			@driver.find_element(:name, "item[wiki_page]").clear
			@driver.find_element(:name, "item[wiki_page]").send_keys "new_wiki"
		end
		it "Create the document" do
			#is there a better way to select this button?
			@driver.find_element(:xpath, "(//input[@value='Create document'])").click
		end
	end
	describe "--------Post condition--------" do
		it "verify the text returned" do
			( @driver.find_element(:class, "feedback_info").text.include? "Permissions successfully updated.").should be_true  
		end
	end
	
end
