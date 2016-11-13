#encoding: utf-8
from UIActions import UIActions
from selenium.webdriver.common.by import By
import pdb
import time

class UIProcedures(UIActions):

    def loginWithSSO(self):
        self.driver.get("https://localhost:8080/index.php?option=com_adalogin")
        self.fillInField("LoginForm_email_input", "mag+blog@magwas.rulez.org")
        self.fillInField("LoginForm_password_input", "3l3k Th3 T3st3r")
        button=self.driver.find_element_by_xpath("//button[text()='Bejelentkezés']")
        button.click()
        self.fillInField("nick", "Teszt Elek")
        self.click("submit")
        self.waitUntilElementEnabled("content")
        self.assertEqual("Hi Teszt Elek,", self.waitUntilElementEnabled("login-form").text)
