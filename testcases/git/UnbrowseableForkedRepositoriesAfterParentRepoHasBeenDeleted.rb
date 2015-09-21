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
# UnbrowseableForkedRepositoriesAfterParentRepoHasBeenDeleted
#--- End summary

#--- Start tags
# Project
#--- End tags

#--- Start dependency list
#--- End dependency list

#--- Start conf params
# host
#--- End conf params

describe "UnbrowseableForkedRepositoriesAfterParentRepoHasBeenDeleted" do
        before(:all) do
            @runner.navigate.to @params['host']['value'] + '/my/'
        end
        describe "Create repo" do
                it "Open project" do
                    @runner.find_element(:id, "navbar-project").click
                    @runner.find_element(:link, @params['project_name']['value']).click
                end
                it "Go To Git service" do
                    @runner.find_element(:id, "sidebar-plugin_git").click
                end
        it  "Fork repo" do
                    @runner.find_element(:link, @params['repo_name']['value']).click
                    @runner.find_element(:link, "Fork repositories").click
                    @runner.find_element(:xpath, "//*[@id='fork_repositories_repo']/option[2]").click
                    @runner.find_element(:xpath, "//*[@id='fork_repositories']/tbody/tr/td[4]/input").click
                    @runner.find_element(:xpath, "/html/body/div[1]/div[4]/div/div/div[3]/form/input[9]").click
                    verif = @runner.find_element(:xpath, "//*[@id='feedback']/ul/li").text
                if verif  == "Successfully forked" 
                     puts "Successfully forked"
                     @runner.find_element(:link, "Repository list").click
                     @runner.find_element(:link, @params['repo_name']['value']).click
                     @runner.find_element(:xpath, "//*[@id='plugin_git_clone_url']/a").click
                     @runner.find_element(:link, "Delete").click
                     @runner.find_element(:xpath, "//*[@id='repoAction']/input[4]").click
                     @runner.find_element(:xpath, "//*[@id='submit']").click
                     @runner.find_element(:link, "Repository list").click
                       mySelect = @runner.find_element(:xpath, "/html/body/div[1]/div[4]/div/div/div[3]/form[1]/select")
                        options = mySelect.find_elements(:tag_name=>"option")
                         options.each do |g|
                           if g.text == "Site Administrator (admin)"
                           g.click
                           break
                           end
                                      end

                        verif = @runner.find_elements(:link, @params['repo_name']['value']).size()
                          if verif > 0 
                          puts "succes test" 
                        else 
                           puts "test failed" 
                          end 
                    else  
                    puts "repo not forked"   
               end
     
       end
    end
end 
