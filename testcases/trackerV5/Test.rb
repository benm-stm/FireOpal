#---start summary
# Delete a tracker date reminder
#--- End summary

#--- Start tags
# Tracker V5
# Admin
# write
#--- End tags

#--- Start dependency list
# trackerV5/AddTrackerDateReminder.rb
#--- End dependency list

#--- Start conf params
# host
# tracker_id
#--- End conf params

describe "Delete a tracker date reminder" do
    describe "#precondition" do
        it "Open notifications management interface" do
            $link = @params['host']['value'] + '/plugins/tracker/?tracker=' + @params['tracker_id']['value'] + '&func=notifications'
            @runner.navigate.to $link
        end
        it "Find a reminder to update" do
            @runner.find_element(:id, "delete_reminder")
        end
    end
    describe "#regression" do
        it "Click on delete reminder button" do
            @runner.find_element(:id, "delete_reminder").click
        end
        it "Confirm the deletion" do
            @runner.find_element(:name, "confirm_delete").click
        end
        it "Verify feedback message" do
            @runner.find_element(:class, "feedback_info").text.should include("Date Reminder successfully deleted")
        end
    end
end

