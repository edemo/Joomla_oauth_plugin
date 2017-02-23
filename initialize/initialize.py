from selenium import webdriver
from unittest.case import TestCase
from UIProcedures import UIProcedures
import os

class Initialize(UIProcedures):
    @classmethod
    def main(cls):
        profile_directory = os.path.join(os.path.dirname(__file__),"..", "etc", "firefox-profile")
        profile = webdriver.FirefoxProfile(profile_directory)
        profile.accept_untrusted_certs = True
        cls.driver = webdriver.Firefox(firefox_profile=profile)
        cls.installJoomlaComponent()
        cls.driver.close()

Initialize.main()