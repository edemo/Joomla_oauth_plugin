<?php
require_once "tests/testJoomlaFramework.php";
require_once "adalogin/site/models/ada_obj.php";

class adaloginTest extends PHPUnit_Framework_TestCase {

    public function test_getLoginURI_correctly()  {
		global $testData;
		$testData->addDbResult(JSON_decode('{
		"id":1, 
		"ADA_AUTH_URI":"https://adatom.hu/ada/v1/oauth2/auth", 
		"ADA_USER_URI":"https://adatom.hu/ada/v1/users/me", 
		"ADA_TOKEN_URI":"https://adatom.hu/ada/v1/oauth2/token", 
		"appkey":"APP_ID_COMES_HERE", 
		"secret":"secret_comes_here", 
		"joomla_psw":"joomla_psw_comes_here"
		}'));
        $ada = new adaloginModelAda_obj();
        $resp = $ada->getloginURI();
        $this->assertEquals(
            "https://adatom.hu/ada/v1/oauth2/auth?response_type=code&client_id=APP_ID_COMES_HERE&redirect_uri=https%3A%2F%2Flocalhost%2Fcomponents%2Fcom_adalogin%2Findex.php",
            $resp);
		// assertEmpty($a), assertNoEmpty($a), assertGreaterThan, assertGreaterThanOrEqual, .......	
		// $this->expertOutputString('várt'); // hiba ha ez nem szerepel az outputba
		// $this->expertOutputRegex('várt'); // hiba ha ez illeszkedik az outputra
    }
	public function test_callback_correctly() {
		global $testData;
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
		$ada = new adaloginModelAda_obj();
        $ada->callback();
		$this->expercOutputRegex('/submit/');
    }
	public function test_callback_error() {
		global $testData;
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
		$testData->addRemoteResult('{"error":0121}');
		$ada = new adaloginModelAda_obj();
        $ada->callback();
		$this->expectOutputRegex('/error/');
    }
}
?>
