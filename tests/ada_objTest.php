<?php


require_once "adalogin/ada_obj.php";

class FakeInterface {

    public $mock_data = array();

    public function _construct() {
        $this->mock_data["headers"] = array();
    }

    public function header($header) {
        $this->mock_data["headers"][] = $header;
    }
}

class adaloginTest extends PHPUnit_Framework_TestCase
{

    public function test_loginForm_sets_the_Location_header_correctly()
    {
        $fake = new FakeInterface();
        $ada = new ada_obj($fake);
        $resp = $ada->loginForm();
        $this->assertEquals(
            "Location: https://adatom.hu/ada/v1/oauth2/auth?response_type=code&client_id=APP_ID_COMES_HERE&redirect_uri=https%3A%2F%2FBASE_URL_COMES_HERE%2Fadalogin%2Findex.php",
            $fake->mock_data["headers"][0]);
    }

}
