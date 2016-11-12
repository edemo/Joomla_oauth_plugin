#encoding: utf-8
from UIActions import UIActions
from selenium.webdriver.common.by import By
import pdb
import time

class UIProcedures(UIActions):

    def loginJoomlaAdmin(self):
        self.driver.get("https://localhost:8080/administrator")
        self.fillInField("mod-login-username", "joomlaAdminUsername")
        self.fillInField("mod-login-password", "joomlaAdminPassword")
        self.click("submit")

    def installComponent(self):
		self.driver.get("http://localhost:8080/administrator/index.php?option=com_installer#folder")
		button=self.driver.find_element_by_id("installbutton_directory")                                ????? van ilyen hogy find_e,emt_by_id ?
        button.click()
        self.waitUntilElementEnabled("content")
        self.assertEqual("sikeres,", self.waitUntilElementEnabled("system-message-container").text)    
		   ????? <div id="system-message-container" ez jó ide? és a megadott szöveg csk egy részlete az üzenetnek, angolban persze más.

    def componentAdminForm(self):
		self.driver.get("http://localhost:8080/administrator/index.php?option=com_adalogin")
		self.fillInField("jform_ADA_AUTH_URI","......")
		self.fillInField("jform_ADA_USER_URI","......")
		self.fillInField("jform_ADA_TOKEN_URI","......")
		self.fillInField("jform_appkey","......")
		self.fillInField("jform_secret","......")
		button=self.driver.find_element_by_xpath("//button[text()='Mentés és bezárás']")  ???? a button elemen belül span -ban van ez a szöveg és persze angolban más.
		button.click()
        self.waitUntilElementEnabled("content")
        self.assertEqual("tárolva,", self.waitUntilElementEnabled("system-message-container").text)    
		   ????? <div id="system-message-container" ez jó ide? és a megadott szöveg csk egy részlete az üzenetnek, angolban persze más.
		
    def componenFrontend(self):
		self.driver.get("http://localhost:8080/index.php?option=com_adalogin?redi="+base64_encode("sikeres_uri"))  ??? base64_encode ?
        self.fillInField("nick", "Teszt Elek")
        self.click("submit")
        self.waitUntilElementEnabled("content")
        self.assertEqual("Hi Teszt Elek,", self.waitUntilElementEnabled("login-form").text)
