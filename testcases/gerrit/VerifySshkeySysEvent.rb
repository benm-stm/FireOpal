########################################################################
# Copyright (c) STMicroelectronics 2012. All rights reserved           #
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
# Delete Repository
#--- End summary

#--- Start tags
# Project
#--- End tags

#--- Start dependency list
# gerrit/AddNewSSHKey.rb
#--- End dependency list

#--- Start conf params
# host
#--- End conf params

describe "Verify SSH key System event" do
        before(:all) do
            @runner.navigate.to @params['host']['value'] + '/my/'
        end
        describe "Verify SSH-key system event" do
                it "Go to Admin" do
                    @runner.find_element(:link, "Admin").click
                end
                it "Go to system event monitor" do
                    @runner.find_element(:link, "System events monitor").click
                end
                it "Chooses the Edit key option" do
					@runner.find_element(:xpath,"//select[@name='filter_type[]']/option[@value='EDIT_SSH_KEYS']").click
                end
                it "Clicks on the filter button" do
					@runner.find_element(:name, "filter").click
                end
                it "Verify the consumption of the event" do
					event_id = @runner.find_element(:xpath, "//*[@id='admin-system-events']/form/table[1]/tbody/tr[1]/td[1]").text
					event_done = @runner.find_element(:xpath, "//*[@id='admin-system-events']/form/table[1]/tbody/tr[1]/td[4]").text
					while event_done != "DONE" do
						@runner.find_element(:name, "filter").click
						event_done = @runner.find_element(:xpath,"//td[text()='#{event_id}']/ancestor::tr/td[4]").text;
					end
                end
       end
end
