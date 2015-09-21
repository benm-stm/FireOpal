describe "Fork Repository" do
        before(:all) do
            @runner.navigate.to @params['host']['value'] + '/my/'
        end
        describe "Fork repo" do
                it "Open project" do
                    @runner.find_element(:id, "navbar-project").click
                    @runner.find_element(:link, @params['project_name']['value']).click
                end
                it "Go To Git service" do
                    @runner.find_element(:id, "sidebar-plugin_git").click
                end
                it "fork repo " do
                   mySelect = @runner.find_element(:xpath,"/html/body/div[1]/div[4]/div/div/div[3]/form/select")
                   options=mySelect.find_elements(:tag_name=>"option")
                      options.each do |g|
                         if g.text == "Site Administrator (admin)"
                            g.click
                           break
                         end
                      end
                    verif = @runner.find_elements(:link, @params['repo_name']['value']).size
                  if verif > 0 
                   puts "forked repo exist"
                   else 
                    @runner.find_element(:link, "Repository list").click
                    @runner.find_element(:link, @params['repo_name']['value']).click
                    @runner.find_element(:link, "Fork repositories").click
                  mySelect = @runner.find_element(:xpath,"//select[@name='repos[]']")
                   options=mySelect.find_elements(:tag_name=>"option")
                      options.each do |g|
                         if g.text == "#{@params['repo_name']['value']}"
                            g.click
                           break
                         end
                      end

                    @runner.find_element(:xpath, "//*[@id='fork_repositories']/tbody/tr/td[4]/input").click
                    @runner.find_element(:xpath, "//form/input[9]").click
                    verif = @runner.find_element(:xpath, "//*[@id='feedback']/ul/li").text
                        if verif  == "Successfully forked"
                               puts "Successfully forked"
                        else 
                       puts "fork failure"
                        end       
                  end
               end 
     end
end         
 
