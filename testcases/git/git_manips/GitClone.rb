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
# Push content in repo
#--- End summary

#--- Start tags
# Project
#--- End tags

#--- Start dependency list
# git/CreateRepo.rb
# gerrit/VerifySshkeySysEvent.rb
#--- End dependency list

#--- Start conf params
# host
#--- End conf params

describe "Clone content from a repo" do

	it "clone the repo " do
		#--- Needed params
		project = @params['project']['value']
		repo_name = @params['repo_name']['value']
		
		system "ssh-agent sh -c 'ssh-add /var/www/.ssh/id_rsa; cd ../tmp_tests; git clone ssh://gitolite@codex-dev.cro.st.com/#{project}/#{repo_name}.git'"
        if $?.exitstatus == 0
			puts "Cloning success"
		else
			puts "Cloning failure"
		end
    end
end
