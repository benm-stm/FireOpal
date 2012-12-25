#copyright (c) STMicroelectronics 2012. All rights reserved           #
#                                                                      #
# FireOpal is free software; you can redistribute it and/or modify    #
# it under the terms of the GNU General Public License as published by #
# the Free Software Foundation; either version 2 of the License, or    #
# (at your option) any later version.                                  #
#                                                                      #
# FireOpal is distributed in the hope that it will be useful,         #
# but WITHOUT ANY WARRANTY; without even the implied warranty of       #
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        #
# GNU General Public License for more details.                         #
#                                                                      #
# You should have received a copy of the GNU General Public License    #
# along with FireOpal. If not, see <http://www.gnu.org/licenses/>.    #
########################################################################

#--- Start summary
# create the apache rsa id so it can handle git manipulations
#--- End summary

#--- Start tags
# Project
#--- End tags

#--- Start dependency list
# system_config/CreateApacheRSAID.rb
#--- End dependency list

#--- Start conf params
# host
#--- End conf params

describe "Add New SSH Key" do
	
        before(:all) do
            @runner.navigate.to @params['host']['value'] + '/my/'
        end
	describe "Add New SSH Key" do
		it "Go To My Account" do
			@runner.find_element(:id, "navbar-user-navigation").click
            @runner.find_element(:link, "My Account").click
        end
        it  "Click on the button add keys"do
			@runner.find_element(:xpath, "//*[@id='user-ssh-keys-action']/a").click
        end
        it "Put the SSh key  on the required textfield " do
			@runner.find_element(:name, "form_authorized_keys").clear
			key = IO.read("/var/www/.ssh/id_rsa.pub")
            @runner.find_element(:name, "form_authorized_keys").send_keys key
        end
        it  "Find  button submit" do
			@runner.find_element(:name, "add-keys").click
        end
        it "verify update" do
			update_content = @runner.find_element(:xpath, "//*[@id='feedback']/ul/li").text
            if update_content == "SSH key(s) updated in database, will be propagated on filesystem in a few minutes, please be patient."
				$verif = true
			else
				$verif = false
			end
        end

	it "Verify SSH key system event DONE" do
		if $verif
			#it "Go to Admin" do
				@runner.find_element(:link, "Admin").click
			#it "Go to system event monitor" do
				@runner.find_element(:link, "System events monitor").click
			#it "Chooses the Edit key option" do
				@runner.find_element(:xpath,"//select[@name='filter_type[]']/option[@value='EDIT_SSH_KEYS']").click
			#it "Clicks on the filter button" do
				@runner.find_element(:name, "filter").click
			#it "Verify the consumption of the event" do
				event_id = @runner.find_element(:xpath, "//*[@id='admin-system-events']/form/table[1]/tbody/tr[1]/td[1]").text
				event_done = @runner.find_element(:xpath, "//*[@id='admin-system-events']/form/table[1]/tbody/tr[1]/td[4]").text
				while event_done != "DONE" do
					@runner.find_element(:name, "filter").click
					event_done = @runner.find_element(:xpath,"//td[text()='#{event_id}']/ancestor::tr/td[4]").text;
				end
		end
	end
end
end
