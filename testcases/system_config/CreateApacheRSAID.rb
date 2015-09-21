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

#--- Start conf params
# host
#--- End conf params

describe "push content in repo" do
        describe "Create the RSA key" do
                 it "Issue the system rsa creation command after the rsa_id existence test" do
					key = File.exist?("/var/www/.ssh/id_rsa.pub")
					if !key
						key = File.exist?("/var/www/.ssh")
						if !key
							system "mkdir /var/www/.ssh"
						end
							system "chown -R apache:apache /var/www/.ssh"
							system "ssh-keygen -t rsa -f /var/www/.ssh/id_rsa"
                        if $?.exitstatus == 0
                                key = File.exist?("/var/www/.ssh/id_rsa.pub")
                                if !key
									puts "apache key creation error"
                                else
									puts "apache key creation success"
                                end
						else
						puts "rsa key creation error"		
                        end
					end
                end
        end
end
