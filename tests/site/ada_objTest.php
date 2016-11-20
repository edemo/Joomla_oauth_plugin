<?php
require_once "tests/testJoomlaFramework.php";

class adaloginTest extends PHPUnit_Framework_TestCase {
	public $ada;
	
	function __construct() {
		global $testData,$componentName,$viewName;
		define('JPATH_COMPONENT', 'adalogin/site');
		$componentName = 'adalogin';
		$viewName = 'adalogin';
		require_once "adalogin/site/models/ada_obj.php";
		parent::__construct();
	}
    protected function setUp() {
		global $testData,$componentName;
		$testData->clear();
		$componentName = 'Adalogin';
		$this->setupTestDataForCorrectCall();
		$this->ada = new AdaloginModelAda_obj($testData);
	    }
    public function test_getLoginURI_correctly()  {
		global $testData;
		$this->setupTestDataForCorrectCall();
        $resp = $this->ada->getloginURI();
        $this->assertEquals(
            "https://adatom.hu/ada/v1/oauth2/auth?response_type=code&client_id=APP_ID_COMES_HERE&redirect_uri=https%3A%2F%2Flocalhost%2Fcomponents%2Fcom_adalogin%2Findex.php",
            $resp);
		// assertEmpty($a), assertNoEmpty($a), assertGreaterThan, assertGreaterThanOrEqual, .......	
		// $this->expertOutputString('várt'); // hiba ha ez nem szerepel az outputba
		// $this->expertOutputRegex('várt'); // hiba ha ez illeszkedik az outputra
    }
	private function setupTestDataForCorrectCall() {
		global $testData;
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
		$testData->addRemoteResult('{"access_token":123,"other":456}');
		$testData->addRemoteResult('{"userid":1,"useremail":"proba@proba.hu","assurances":"[magyar]"}');
	}
    public function test_callback_correctly() {
		$this->setupTestDataForCorrectCall ();
        $this->ada->callback();
		$this->expectOutputRegex('/submit/');
    }
    public function test_callback_calls_correct_uri() {
		global $testData;
		$this->setupTestDataForCorrectCall();
        $this->ada->callback();
        $this->assertEquals(
        		"https://adatom.hu/ada/v1/users/me",
        		$testData->gotArgs["url"]
        );
    }
    public function test_callback_uses_correct_method() {
    	global $testData;
    	$this->setupTestDataForCorrectCall();
    	$this->ada->callback();
    	$this->assertEquals(
    			"GET",
    			$testData->gotArgs["method"]
    			);
    }
    public function test_callback_sends_authorization_header() {
    	global $testData;
    	$this->setupTestDataForCorrectCall();
    	$this->ada->callback();
    	$this->assertEquals(
    			"Authorization: Bearer 123",
    			$testData->gotArgs["extraHeader"]
    			);
    }
    
    public function test_callback_uses_correct_data() {
    	global $testData;
    	$this->setupTestDataForCorrectCall();
    	$this->ada->callback();
    	$this->assertEquals(
    			30,
    			$testData->gotArgs["data"]["timeout"] );
    	$this->assertEquals(
    			10,
    			$testData->gotArgs["data"]["redirection"] );
    	$this->assertEquals(
    			1.0,
    			$testData->gotArgs["data"]["httpversion"] );
    	$this->assertEquals(
    			true,
    			$testData->gotArgs["data"]["blocking"] );
    	$this->assertEquals(
    			array(),
    			$testData->gotArgs["data"]["cookies"] );
    	$this->assertEquals(
    			"yes",
    			$testData->gotArgs["data"]["sslverify"] );
    	$this->assertEquals(
    			30,
    			$testData->gotArgs["data"]["timeout"] );
    }
    
	public function test_callback_error() {
		global $testData;
		$testData->addRemoteResult('{"access_token":123,"other":456}');
		$testData->addRemoteResult('{"error":0121}');
        $this->ada->callback();
		$this->expectOutputRegex('/error/');
    }
}
?>
