#
# Copyright (c) STMicroelectronics 2011. All rights reserved
#

This is a solution to run automatic tests using Selenium webdriver


LICENSE
=======
FireOpal is distributed under the GPL v2 Licence. See the file COPYING for details.

INSTALLATION
============

Before installing, you need to understand that FireOpal deals with three different machines:
1- The machine from which you may launch tests having a webserver on which FireOpal is deployed. Let's call it "Launcher machine".
2- The machine on which web application to be tested is deployed. Let's call it "Server machine".
3- The machine from which tests will be executed on a browser, we use a web browser installed on it. Let's call it "Client machine".

On Launcher machine:

    Install a webserver that supports php
    Install ruby & rubygems
    Then install RSpec & selenium-webdriver by

    > gem install selenium-webdriver
    > gem install rspec
    > gem install rspec_junit_formatter
    > gem install builder
    
    You have to install mysql and its dependancies
    > gem install mysql -- --with-mysql-config=/usr/lib64/mysql/mysql_config 
    > gem install php5-mysql

    You may need to install other packages using "gem install <package_name>" like json or other.
    
      

    Put FireOpal on that machine and point the root of the webserver on "www" folder

On Client machine:

    You need to download selenium-server-standalone-x.y.z.jar from http://seleniumhq.org/download

    If you use firefox for tests then create a Firefox profile for selenium (you may need to delete extentions.rdf for addons popup)

    After that and before trying to run any tests you need to open the platform to be tested by the firefox profile and accept the certificate

    Then run Selenium by

    > java -jar selenium-server-standalone-x.y.z.jar -singlewindow -firefoxProfileTemplate "/path/to/firefox/profile/"
    Selenium RC must be always running on client machine as a service to make launching tests from launcher machine possible.

    For browsers other than firefox the first test may cause an SSL certificate issue.
    For that add the option -trustAllSSLCertificates when you run Selenium

    To keep the same session for all tests add the option -browserSessionReuse

RUN TESTS
=========

You probably need to update tests settings from web interface in this page "http(s)://<host>/set.php"
Then generate a testsuite from this web interface "http(s)://<host>/case.php"

You can Also generate a testsuite using TIC from command line interface, for that you need first to access to the Launcher machine

You can run a testsuite in command line by just typing:
> ruby /path/to/testsuite.rb

Or using web interface "http(s)://<host>/index.php" by selecting the testsuite you want to run then Run!
