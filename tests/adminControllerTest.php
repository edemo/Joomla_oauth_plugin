<?php
/*
* admini/controllers/adalogin.php test
*/
require_once "tests/testJoomlaFramework.php";

class adaloginControllerAdaloginTest extends PHPUnit_Framework_TestCase {
	function __construct() {
		define('JPATH_COMPONENT', 'adalogin/admin');
		require_once "adalogin/admin/controller.php";
		require_once "adalogin/admin/controllers/adalogin.php";
		require_once "adalogin/admin/models/model.php";
	}
    protected function setupConfig() {
		global $testData,$componentName;
		$testData->clear();
		$componentName = 'Adalogin';
		$viewName = 'adalogin';
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

    public function test_edit()  {
		global $testData,$componentName;
		$this->setupConfig();
        $controller = new AdaloginControllerAdalogin();
        $controller->edit();
		$this->expectOutputRegex('/joomla default edit task/');   
        //$this->assertEquals(
        //    'https://adatom.hu/ada/v1/oauth2/auth?response_type=code&client_id=APP_ID_COMES_HERE&redirect_uri=https%3A%2F%2Flocalhost%2Fcomponents%2Fcom_adalogin%2Findex.php%3Fredi%3DaHR0cDovL2xvY2FsaG9zdC8%3D',
        //    $testData->mock_data['redirectURI']);
    }
    public function test_save()  {
		global $testData,$componentName;
		$this->setupConfig();
        $controller = new AdaloginControllerAdalogin();
        $controller->save();
        $this->assertEquals(
            'http://localhost/index.php',
            $testData->mock_data['redirectURI']);
    }
    public function test_apply()  {
		global $testData,$componentName;
		$this->setupConfig();
        $controller = new AdaloginControllerAdalogin();
        $controller->apply();
        $this->assertEquals(
            'http://localhost/index.php?option=com_adalogin',
            $testData->mock_data['redirectURI']);
    }
	
}
?>