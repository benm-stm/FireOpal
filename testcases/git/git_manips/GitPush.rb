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
# git/git_manips/GitClone.rb
#--- End dependency list

#--- Start conf params
# host
#--- End conf params
		
describe "push content in repo" do
        describe "Push content" do
                 it "Pushs content to tuleap " do
					system "cd ../tmp_tests/#{@params['repo_name']['value']}/; touch test_file; echo ' test text' >> test_file"
                    if $?.exitstatus == 0
						system "ssh-agent sh -c 'ssh-add /var/www/.ssh/id_rsa;cd ../tmp_tests/#{@params['repo_name']['value']}/; git add *'"
                        if $?.exitstatus == 0
							system "ssh-agent sh -c 'ssh-add /var/www/.ssh/id_rsa;cd ../tmp_tests/#{@params['repo_name']['value']}/; git commit -m\"test commit\"'"
                            if $?.exitstatus == 0
								system "ssh-agent sh -c 'ssh-add /var/www/.ssh/id_rsa;cd ../tmp_tests/#{@params['repo_name']['value']}/; git push origin master'"
                                if $?.exitstatus == 0
									puts "git manips done"
								else
									puts "git push command error"
                                end
                            else
								puts "git commit command error"
                            end
                        else
							puts "git add command error"
                        end
                    else
						puts "file creation in git repo error"
                    end
                end
        end
end
