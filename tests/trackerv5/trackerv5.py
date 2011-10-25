from selenium import selenium
import unittest, time, re, urlparse

class tt(unittest.TestCase):
    def setUp(self):
        self.verificationErrors = []
        self.selenium = selenium("10.157.15.160", 4444, "*chrome", "http://tunlx107.tun.st.com:8999/")
        self.selenium.start()
    
    def test_tt(self):
        sel = self.selenium
        sel.open("/")
        sel.click("link=Login")
        sel.wait_for_page_to_load("30000")
        sel.type("name=form_loginname", "asma")
        sel.type("name=form_pw", "asmaasma")
        sel.click("name=login")
        sel.wait_for_page_to_load("30000")
        sel.open("/my")
        sel.wait_for_page_to_load("30000")
        sel.click("link=project 1")
        sel.wait_for_page_to_load("30000")
        sel.click("link=Trackers v5")
        sel.wait_for_page_to_load("30000")
        self.assertEqual("Trackers - CodendiV4.0", sel.get_title())
        sel.click("link=test")
        sel.wait_for_page_to_load("30000")
        self.assertEqual("test - CodendiV4.0", sel.get_title())
        sel.click("link=Submit new artifact")
        sel.wait_for_page_to_load("30000")
        self.assertEqual("test - test - CodendiV4.0", sel.get_title())
        sel.select("id=tracker_field_105", "label=Product2")
        sel.select("id=tracker_field_107", "label=1.0")
        sel.select("id=tracker_field_112", "label=Design")
        sel.select("id=tracker_field_114", "label=Quality")
        sel.click("name=submit_and_stay")
        sel.wait_for_page_to_load("30000")
        self.failUnless(sel.is_text_present("Artifact Successfully Created (test #"))
        print "Artifact created"
        sel.click("link=test")
        sel.wait_for_page_to_load("30000")
        self.assertEqual("test - CodendiV4.0", sel.get_title())
        sel.click("id=tracker_renderer_options_menu_handle")
        sel.click("link=Add to my dashboard")
        sel.wait_for_page_to_load("30000")
        self.assertEqual("Personal Page for asma (asma) - CodendiV4.0", sel.get_title())
        self.failUnless(sel.is_text_present("Results for Default"))
        time.sleep(10)
        self.failUnless(sel.is_text_present("Product2"))
        print "Widget added to my dashboard"
        sel.click("css=img[alt=\"Close\"]")
        sel.wait_for_page_to_load("30000")
        self.assertEqual("Personal Page for asma (asma) - CodendiV4.0", sel.get_title())
        self.failUnless(not sel.is_text_present("Results for Default"))
        self.failUnless(not sel.is_text_present("Product2"))
        print "Widget removed from my dashboard"
        sel.click("link=Logout")
        sel.wait_for_page_to_load("30000")
    
    def tearDown(self):
        self.selenium.stop()
        self.assertEqual([], self.verificationErrors)

if __name__ == "__main__":
    unittest.main()
