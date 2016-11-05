from selenium.common.exceptions import StaleElementReferenceException
from selenium.webdriver.common.by import By
from selenium.webdriver.support.wait import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import time

class element_to_be_useable(object):
    def __init__(self, locator):
        self.locator = locator

    def __call__(self, driver):
        try:
            element = driver.find_element(*self.locator)
        except:
            #print(sys.exc_info())
            element = None
        if element:
            try:
                displayValue=element.value_of_css_property('display')
                displayok = displayValue in ('block', 'inline','inline-block')
                displayed = element.is_displayed()
                enabled = element.is_enabled()
                if displayed and enabled and displayok:
                    return element
            except StaleElementReferenceException:
                pass
        return False

class UIActions(object):
    def waitUntilElementEnabled(self, fieldId):
        element = WebDriverWait(self.driver, 100).until(element_to_be_useable((By.ID,fieldId)))
        return element

    def wait_on_element_text(self, by_type, element, text, timeout=20):
        WebDriverWait(self.driver, timeout).until(
            EC.text_to_be_present_in_element(
                (by_type, element), text)
        )

    def fillInField(self, fieldId, value):
        element = self.waitUntilElementEnabled(fieldId)
        element.clear()
        element.send_keys(value)

    def setCheckBox(self, elemId):
        element = self.waitUntilElementEnabled(elemId)
        if not element.is_selected():
            self.click(elemId)
        tried=0
        while not element.is_selected() and tried<5:
            time.sleep(1)
            tried += 1

    def click(self, fieldId):
        element = self.waitUntilElementEnabled(fieldId)
        self.driver.execute_script("""
            window.scrollTo(
                0,
                document.getElementById('{0}').getBoundingClientRect().top-
                 document.body.getBoundingClientRect().top-
                 100)""".format(fieldId))
        return element.click()

    def assertFieldValue(self, fieldId, value):
        element = self.driver.find_element_by_id(fieldId)
        self.assertEqual(value,element.get_property('value'))

    def assertSelected(self, fieldId):
        element = self.driver.find_element_by_id(fieldId)
        self.assertEqual(True,element.is_selected())

