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
		$testData->addRemoteResult('{"userid":1,"email":"proba@proba.hu","assurances":"[magyar]"}');
	}
    public function test_callback_correctly() {
		$this->setupTestDataForCorrectCall ();
        $this->ada->callback();
		$this->expectOutputRegex('/submit/');
    }
	public function test_callback_error() {
		global $testData;
		$testData->clear();
		$testData->addRemoteResult('{"access_token":123,"other":456}');
		$testData->addRemoteResult('{"error":0121}');
        $this->ada->callback();
		$this->expectOutputRegex('/error/');
    }
}
?>
