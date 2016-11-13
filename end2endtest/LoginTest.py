from selenium import webdriver
from unittest.case import TestCase
from UIProcedures import UIProcedures
import os

class LoginTest(TestCase, UIProcedures):
    @classmethod
    def setUpClass(cls):
        profile_directory = os.path.join(os.path.dirname(__file__),"..", "etc", "firefox-profile")
        profile = webdriver.FirefoxProfile(profile_directory)
        profile.accept_untrusted_certs = True
        cls.driver = webdriver.Firefox(firefox_profile=profile)
        cls.installJoomlaComponent()

    def tearDown(self):
        self.driver.close()

    def test_login(self):
        self.loginWithSSO()

