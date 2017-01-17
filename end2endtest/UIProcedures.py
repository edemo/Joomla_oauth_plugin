#encoding: utf-8
from UIActions import UIActions
from selenium.webdriver.common.by import By
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
        self.wait_on_element_text(By.ID,'login-form', "Hi TesztElek,")

    @classmethod
    def installJoomlaComponent(cls):
        self=cls()
        self.driver.get("https://localhost:8080/administrator")
        self.fillInField("mod-login-username", "admin")
        self.fillInField("mod-login-password", "admin")
        button=self.driver.find_element_by_xpath("//button")
        button.click()
        self.waitUntilElementEnabled('skiptarget')
        self.driver.get("https://localhost:8080/administrator/index.php?option=com_installer")
        self.waitUntilElementEnabled('myTabTabs')
        anchor=self.driver.find_element_by_xpath("//a[text()='Install from Folder']")
        anchor.click()
        self.click("installbutton_directory")
        self.wait_on_element_text(By.CLASS_NAME,'alert-message','Installation of the component was successful.')
        self.driver.get("https://localhost:8080/administrator/index.php?option=com_adalogin")
        self.fillInField("jform_ADA_AUTH_URI", "https://sso.edemokraciagep.org/ada/v1/oauth2/auth")
        self.fillInField("jform_ADA_USER_URI", "https://sso.edemokraciagep.org/ada/v1/users/me")
        self.fillInField("jform_ADA_TOKEN_URI", "https://sso.edemokraciagep.org/ada/v1/oauth2/token")
        self.fillInField("jform_appkey", "740a9102-d4e2-4988-96e4-d71b10dc8152")
        self.fillInField("jform_secret", "j00ml4t3st")
        self.click('toolbar-save')
        self.wait_on_element_text(By.CLASS_NAME,'alert-message','ADA configuration saved.')
