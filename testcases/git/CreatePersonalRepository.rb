describe "Create Personal Repository" do
        before(:all) do
            @runner.navigate.to @params['host']['value'] + '/my/'
        end
        describe "Create repo" do
                it "Open project" do
                    @runner.find_element(:id, "navbar-project").click
                    @runner.find_element(:link, "fakhri_tests").click
                end
                it "Go To Git service" do
                    @runner.find_element(:id, "sidebar-plugin_git").click
                end
  it  "Fork repo" do
                    @runner.find_element(:link, "git15").click
                    @runner.find_element(:link, "Fork repositories").click
                    @runner.find_element(:xpath, "//*[@id='fork_repositories_repo']/option[2]").click
                    @runner.find_element(:xpath, "//*[@id='fork_repositories']/tbody/tr/td[4]/input").click
                    @runner.find_element(:xpath, "/html/body/div[1]/div[4]/div/div/div[3]/form/input[9]").click
                    verif = @runner.find_element(:xpath, "//*[@id='feedback']/ul/li").text
                if verif  == "Successfully forked"
                     puts "Successfully forked"
     system "ssh-agent sh -c 'ssh-add /var/www/.ssh/id_rsa; cd ../tmp_tests; git clone ssh://gitolite@codex-dev.cro.st.com/ftests/u/admin/git15.git'"
        if $?.exitstatus == 0
                        puts "Cloning success"
                                               system "cd ../tmp_tests/git15/; touch test_fileb; echo 'test git creation' > test_filel"
                    if $?.exitstatus == 0
                                                system "ssh-agent sh -c 'ssh-add /var/www/.ssh/id_rsa;cd ../tmp_tests/git15/; git add *'"
                        if $?.exitstatus == 0
                                                        system "ssh-agent sh -c 'ssh-add /var/www/.ssh/id_rsa;cd ../tmp_tests/git15/; git commit -m\"test commit\"'"
                            if $?.exitstatus == 0
                                                                system "ssh-agent sh -c 'ssh-add /var/www/.ssh/id_rsa;cd ../tmp_tests/git15/; git push origin master'"
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
                else
                        puts "Cloning failure"
                end
      end
                  
                 it  "Find  repo settings"do
                    @runner.find_element(:link, "git15").click
                    @runner.find_element(:link,"Settings").click
                end
                   it "Notifications" do
                    @runner.find_element(:link, "Notifications").click
                end
                   it  "Find mail prefix" do
                    @runner.find_element(:id, "mail_prefix").click
                    @runner.find_element(:id, "mail_prefix").clear
                    @runner.find_element(:id, "mail_prefix").send_keys "[ST]"
                end
                   it  "Find  button submit" do
                    @runner.find_element(:css, "input.btn").click
                end
                   it "verify update" do
                    update_content = @runner.find_element(:xpath, "//*[@id='feedback']/ul/li").text
                    update_content.should == "Mail prefix updated"
                end
                it  "repo list"do
                    @runner.find_element(:link,"Repository list").click
                    @runner.find_element(:link,"git15").click
                end
                   it "Notifications" do
                   @runner.find_element(:link,"Settings").click
                    @runner.find_element(:link, "Notifications").click
                end
                   it  "Find mail prefix" do
                    @runner.find_element(:id, "mail_prefix").text.include? "[SCM]" 
                  end 
 end 
end 

