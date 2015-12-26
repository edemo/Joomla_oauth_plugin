<?php
/** 
 * SSO login integráció joomla 3.x rendszerhez
 * Licensz: GNU/GPL
 * Szerző: Tibor Fogler 
 * Szerző email: tibor.fogler@gmail.com
 * Szerző web: adatmagus.hu
 * Verzió: 1.00   2015.12.24
 *
 * Ennek a fájlnak a joomla root direktory alatt, ssologin aldirektoriban index.php néven kell lennie.
 * A szervernek https: -el is elérhetőnek kell lennie.
 *
 * Login link: http://sitedomain/ssologin/index.php, 
 * Ha a látogató belépet az SSO login képernyőn; akkor a Joomla homepage-ra kerül.
 *
 * Módositsad ennek a fájlnak a "config" részét!
*/ 

class sso_obj {

	protected $SSO_AUTH_URI = 'https://sso.edemokraciagep.org/v1/oauth2/auth';
	protected $SSO_USER_URI = 'https://sso.edemokraciagep.org/v1/users/me';
	protected $SSO_TOKEN_URI = 'https://sso.edemokraciagep.org/v1/oauth2/token';
	protected $sslverify = 'yes';
	
	// -------------- config ---------------------------------
	protected $appkey = '';
	protected $secret = '';
	protected $PSW = '';  // a Joomla rendszerben ez lesz az generált SSO accountok jelszava
	protected $home = 'http://joomla_site_domain.hu';  // tartalmazza a http:// vagy https:// -t, 
	         // de ne tartalmazza az /index.php -t! pl: http://li-de.tk
	// -------------- config ---------------------------------

	// Joomla rendszer interface rutinok	
	// =================================
	
	/**
	  * ellenörzi, hogy a paraméterben megaadott user a joomlában regisztrálva van-e ?
	  * @return integer Ha igen akkor user_id, ha nem akkor 0
	  * @param string user nick name
	  * @param string user e-mail
	*/  
	protected function checkUser($username, $email) {
		$result = 0;
		$res = false;	
		$db = JFactory::getDBO();
		$db->setQuery('select * from #__users where email = '.$db->quote($email));
		$res = $db->loadObject();
		if ($res) {
			$result = $res->id;
		} else {	
		  $db->setQuery('select * from #__users where username = '.$db->quote($username));
		  $res = $db->loadObject();
		  if ($res) {
			  $result = $res->id;
		  }
		}
		return $result;
	}
	
	/**
	  * Új user account létrehozása a Joomlába
	  * @return string  Ha sikeres akkor '', ha hibás akkor hibaüzenet
	*/  
	protected function registUser($username, $email) {
	  $result = '';
	  $data = array(
          "name"=>$username,
          "username"=>$username,
          "password"=>$this->PSW,
          "password2"=>$this->PSW,
          "email"=>$email,
          "block"=>0,
          "groups"=>array("1","2")
          );
          $user = new JUser;
          if (!$user->bind($data)) {
             $result = "Could not bind data. Error: " . $user->getError();
          }
          if (!$user->save()) {
             $result = "Could not save user. Error: " . $user->getError();
          }
	  return $result;	
	}

	/**
	  * login a joomla rendszerbe
	  * @param object userData {"userid":"xxxxxxxx", "email":"xxxxxxxx",......}
	  * @return object JUser   {"id":####, "username":"xxxxx", "email":"xxxxxx",.....}
	*/  
	public function loginToJoomla($userData,&$mainframe) {
	    $credentials = array();
	    $credentials['username'] = $userData->userid;
	    $credentials['password'] = $this->PSW;
	    $error = $mainframe->login($credentials);
	    $user = JFactory::getUser();
	    return $user;
	}

	// SSO szerver elérés  interface rutinok
	// =====================================
	
	/**
	  * távoli szolgáltatás hívás
	  * @param string url
	  * @param string 'GET' vagy 'POST'
	  * @param array data  paraméterek ["név" => "érték",....]
	  * @param string extra header sor (elhagyható)
	  * @return string
	*/
	public function remoteCall($url,$method,$data,$extraHeader='') {
		$result = '';
		if ($extraHeader != '') {
			$extraHeader .= "\r\n";
		}	
		$options = array(
		    'http' => array(
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n".$extraHeader,
			'method'=> $method,
			'content' => http_build_query($data)
		    )
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		return $result;
	}
	
	/**
	  * token objektum lekérése az SSO szervertől
	  * @param string code
	  * @return object  token {"access_token":"xxxxxxxx",......}
	*/  
	protected function getSSOtoken($code) {
		$result = '';
		$token = new stdClass();
		$userdata = new stdClass();
		$url = $this->SSO_TOKEN_URI;
		$data = array('timeout' => 30,
						'redirection' => 10,
						'httpversion' => '1.0',
						'code' => $code, 
						'grant_type' => 'authorization_code',
						'client_id' => $this->appkey,
						'client_secret' => $this->secret,
						'redirect_uri' => str_replace('http:','https:',$this->home.'/ssologin/index.php')
						);
		$result = $this->remoteCall($url,'POST',$data);
		if ($result != '') {
		   $token = JSON_decode($result);
		}   
		return $token;
	}
	
	/**
	  * userData objektum lekérése az SSO szervertől
	  * @param object token  {"access_token":"xxxxxxxx",......}
	  * @return object  {"userid":"xxxxxxxx", "email":"xxxxxxxx",......}
	*/  
	protected function getSSOuserData($token) {
		$userData = new stdClass();
		$url = $this->SSO_USER_URI;
		$data = array('timeout' => 30,
				'redirection' => 10,
				'httpversion' => '1.0',
			   'blocking' => true,
				'cookies' => array(),
			  'sslverify' => $this->sslverify 
	        );
		$extraHeader = 'Authorization: Bearer '.$token->access_token;
		$result = $this->remoteCall($url,'GET',$data,$extraHeader);
		if ($result != '') {
			$userData = JSON_decode($result);
		}
		return $userData;	
	}
	
	// taskok
	// ======
	
	/**
	  * ugrás az SSO login képernyőre
	  * @return void
	*/  
	public function loginForm() {
	  $redirectURI = $this->home.'/ssologin/index.php';	
	  $redirectURI = str_replace('http:','https:',$redirectURI);
	  $url = $this->SSO_AUTH_URI.'?response_type=code&client_id='.$this->appkey.'&redirect_uri='.urlencode($redirectURI);
          header('Location: '.$url);
	}

	/**
	  * SSO tól érkező visszahívás feldolgozása  (a látogató bejelentkezett auz SSO-ban)
	  * @return void
	  * JRequest  string code    SSO auth code
	  * Ha sikeres login akkor redirect a joomla homapage-ra, ellenkező esetben hibaüzenet kiirása
	*/  
	public function doLogin(&$mainframe) {

	    // get token	
	    $token = $this->getSSOtoken(JRequest::getVar('code'));

	    // get user data
	    if (isset($token->access_token)) {
		$userData = $this->getSSOuserData($token);
	    }

	    if (isset($userData->userid)) {
		  // registered user?	
		  $userId = $this->checkUser($userData->userid, $userData->email);
		  if ($userId == 0) {
			  // create new user
			  $s = $this->registUser($userData->userid, $userData->email);
			  if ($s != '') {
				echo '<p>'.$s.'</p>';
			  }
			  // registered user?
		      $userId = $this->checkUser($userData->userid, $userData->email);
		  }
		  if ($userId > 0) {
			  // try login into joomla
			  $user = $this->loginToJoomla($userData, $mainframe);
			  if ($user->id > 0) {
				echo '<html><body><script language="javascript">'; 
				echo 'opener.location.href = "'.$this->home.'/index.php";';
				echo 'window.close();';	
				echo '</script></body></html>'; 
			  } else {
				  echo '<p>error in SSO login (3)</p>'; // error in joomla login process
			  }	  
		  } else {
			  echo '<p>error in SSO login (2)</p>';  // not check User, not succes regist new user
		  }
		} else {
			echo '<p>error in SSO login (1)</p>'; // error in get userData from SSO server
		}
		echo '<center><br /><br /><a href="'.$this->home.'">goto home page</a><br /><br /></center>';
	} // end doLogin function	
} // end sso_obj class

// main program
// ============

define( '_JEXEC', 1 );
define('JPATH_BASE', str_replace('/ssologin','',dirname(__FILE__) ));
define( 'DS', DIRECTORY_SEPARATOR );
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
require_once ( JPATH_BASE .DS.'libraries'.DS.'joomla'.DS.'factory.php' );
JDEBUG ? $_PROFILER->mark( 'afterLoad' ) : null;
$mainframe =& JFactory::getApplication('site');
$mainframe->initialise();
$params = &JComponentHelper::getParams('com_ammvc');
jimport('joomla.plugin.helper');
jimport('joomla.user.helper');

$sso = new sso_obj();

if (JRequest::getVar('code') != '') {
	$sso->doLogin($mainframe);
} else {
    $sso->loginForm();	
}
?>
