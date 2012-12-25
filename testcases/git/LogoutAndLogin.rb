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

#--- Start conf params
# host
#--- End conf params

describe "Logout and login" do

        before(:all) do
            @runner.navigate.to @params['host']['value'] + '/my/'
        end
         describe "logout and login with another ldap user" do
             it "logout" do 
    @runner.find_element(:id, "navbar-user-navigation").click
    @runner.find_element(:link, "Logout").click
             end 
             it "login" do 
    @runner.find_element(:name, "form_loginname").click
    @runner.find_element(:name, "form_loginname").clear
    @runner.find_element(:name, "form_loginname").send_keys "belgaief"
    @runner.find_element(:name, "form_pw").clear
    @runner.find_element(:name, "form_pw").send_keys "fakhri1992$$1992"         
    @driver.find_element(:name, "login").click
            end 
           
 end 
end 
