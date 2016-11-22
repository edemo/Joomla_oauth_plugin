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
        $this->assertEquals(
            'https://adatom.hu/ada/v1/oauth2/auth?response_type=code&client_id=APP_ID_COMES_HERE&redirect_uri=https%3A%2F%2Flocalhost%2Fcomponents%2Fcom_adalogin%2Findex.php%3Fredi%3DaHR0cDovL2xvY2FsaG9zdC8%3D',
            $testData->mock_data['redirectURI']);
    }
    public function test_dologin_new_user()  {
		global $testData,$componentName;
		$this->setupConfig();
		$testData->addDbResult(false);
	    $testData->setInput('adaid',123);
		$testData->setInput('adaemail','test@test.hu');
		$testData->setInput('assurance','["magyar"]');
		$testData->setInput('redi','');
        $controller = new AdaloginController();
        $controller->dologin();
		$this->expectOutputRegex('/testJoomlaFramwork view\.display regist/');   
    } 
    public function test_dologin_user_exists()  {
		global $testData,$componentName;
		$this->setupConfig();
		$testData->addDbResult(JSON_decode('{
		"id":1, 
		"username":"testElek" 
		}'));
	    $testData->setInput('adaid',123);
		$testData->setInput('adaemail','test@test.hu');
		$testData->setInput('assurance','["magyar"]');
		$testData->setInput('redi',base64_encode('http://localhost/redi.php'));
        $controller = new AdaloginController();
        $controller->dologin();
        $this->assertEquals(
            'http://localhost/redi.php',
            $testData->mock_data['redirectURI']);
    } 
    public function test_processform_ok()  {
		global $testData,$componentName;
		$this->setupConfig();
		$testData->addDbResult(false);
		
	    $testData->setInput('adaid',123);
		$testData->setInput('adaemail','test@test.hu');
		$testData->setInput('assurance','["magyar"]');
		$testData->setInput('nick','testElek');
		$testData->setInput('redi',base64_encode('http://localhost/redi.php'));
        $controller = new AdaloginController();
        $controller->processform();
        $this->assertEquals(
            'http://localhost/redi.php',
            $testData->mock_data['redirectURI']);
    } 
    public function test_processform_nick_empty()  {
		global $testData,$componentName;
		$this->setupConfig();
		$testData->addDbResult(false);
	    $testData->setInput('adaid',123);
		$testData->setInput('adaemail','test@test.hu');
		$testData->setInput('assurance','["magyar"]');
		$testData->setInput('nick','');
		$testData->setInput('redi',base64_encode('http://localhost/redi.php'));
        $controller = new AdaloginController();
        $controller->processform();
		$this->expectOutputRegex('/testJoomlaFramwork view\.display regist/');   
    } 
    public function test_processform_nick_used()  {
		global $testData,$componentName;
		$this->setupConfig();
		$testData->addDbResult(JSON_encode('{"id":2,"username":"testElek"}'));
	    $testData->setInput('adaid',123);
		$testData->setInput('adaemail','test@test.hu');
		$testData->setInput('assurance','["magyar"]');
		$testData->setInput('nick','testElek');
		$testData->setInput('redi',base64_encode('http://localhost/redi.php'));
        $controller = new AdaloginController();
        $controller->processform();
		$this->expectOutputRegex('/testJoomlaFramwork view\.display regist/');   
    } 
	
}
?>