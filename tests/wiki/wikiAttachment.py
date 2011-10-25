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
        sel.click("link=Wiki")
        sel.wait_for_page_to_load("30000")
        sel.click("link=Attach a file")
        sel.wait_for_pop_up("HelpWindow", "30000")
        sel.select_window("name=HelpWindow")
        sel.type("name=userfile", "c:\\test1.txt")
        sel.click("//input[@value='Upload']")
        sel.wait_for_page_to_load("30000")
        self.failUnless(sel.is_text_present("Attach:test1.txt"))
        self.failUnless(sel.is_text_present("File successfully uploaded."))
        print "Wiki attachment uploaded"
        sel.close()
        sel.select_window("null")
        sel.click("//a[contains(@href, '/wiki/admin/index.php?group_id=104')]")
        sel.wait_for_page_to_load("30000")
        sel.click("link=Manage Wiki Attachments")
        sel.wait_for_page_to_load("30000")
        link = sel.get_attribute("//a[text()='test1.txt']/@href")
        url_data = urlparse.urlparse(link)
        query = urlparse.parse_qs(url_data.query)
        id = query["id"][0]
        sel.click("//input[@name='attachments_to_delete[]' and @value='"+id+"']")
        sel.click("//input[@value='Delete']")
        sel.wait_for_page_to_load("30000")
        self.failUnless(sel.is_text_present("Wiki attachment(s) Deleted"))
        print "Wiki attachment deleted"
        sel.click("link=Logout")
        sel.wait_for_page_to_load("30000")
    
    def tearDown(self):
        self.selenium.stop()
        self.assertEqual([], self.verificationErrors)

if __name__ == "__main__":
    unittest.main()
