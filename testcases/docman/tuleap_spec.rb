require "rubygems"
gem "rspec"
require "rspec/autorun"
require 'tuleap'

describe TuleapValidation do
    before(:each) do
        @testClass = TuleapValidation.new
        @testClass.setup()
        @testClass.login()
    end

    after(:each) do
        @testClass.teardown()
    end

    describe "test case 1" do
        it "should add an artifact" do
            @testClass.addArtifact()
        end
    end

    describe "test case 45" do
        it "should add the widget" do
            @testClass.addTrackerV5Widget()
        end
    end
end
