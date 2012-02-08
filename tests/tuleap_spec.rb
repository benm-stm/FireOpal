require "rubygems"
gem "rspec"
require "rspec/autorun"
require 'tuleap'

describe TuleapValidation do

	before(:each) do
	@bowling = TuleapValidation.new
	@bowling.setup()
@bowling.login()
	end

	after(:each) do
	@bowling.teardown()
	end

	describe "test case 1" do
		  it "should add an artifact" do		    
		    @bowling.addArtifact()		   
		  end
	end

describe "test case 45" do
                 it "should add the widget" do		    
		    @bowling.addTrackerV5Widget()		   
		  end
	end
end




