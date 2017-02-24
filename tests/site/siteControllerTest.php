<?php
require_once "tests/testJoomlaFramework.php";

class adaloginControllerTest extends PHPUnit_Framework_TestCase {
    function __construct() {
		global $testData,$componentName,$viewName;
		$componentName = 'Adalogin';
		$viewName = 'adalogin';
		define('JPATH_COMPONENT', 'adalogin/site');
		require_once "adalogin/site/controller.php";
		parent::__construct();
	}
	protected function setupConfig() {
		global $testData,$componentName;
		$testData->clear();
		$testData->addDbResult(JSON_decode('{
		"id":1, 
		"ADA_AUTH_URI":"https://adatom.hu/ada/v1/oauth2/auth", 
		"ADA_USER_URI":"https://adatom.hu/ada/v1/users/me", 
		"ADA_TOKEN_URI":"https://adatom.hu/ada/v1/oauth2/token", 
		"appkey":"APP_ID_COMES_HERE", 
		"secret":"secret_comes_here", 
		"joomla_psw":"joomla_psw_comes_here"
		}'));
	}

	public function test_loginform()  {
		global $testData,$componentName;
		$this->setupConfig();
        $controller = new AdaloginController();
        $controller->loginform();
		$this->expectOutputRegex('|https://adatom.hu/ada/v1/oauth2/|');   
    }
}
?>