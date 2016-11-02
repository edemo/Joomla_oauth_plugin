<?php
/** 
 * ADA login integráció joomla 3.x rendszerhez
 * Licensz: GNU/GPL
 * Szerző: Tibor Fogler 
 * Szerző email: tibor.fogler@gmail.com
 * Szerző web: adatmagus.hu
 * Verzió: 3.00   2016.09.17  
 *
 * Ennek a fájlnak a joomla root direktory alatt, adalogin aldirektoriban index.php néven kell lennie.
 * A szervernek https: -el is elérhetőnek kell lennie.
 *
 * Az ADA rndszer adminisztrátorának megadandó adatok:
 *   A Joomla rendszer domain neve ($home)
 *   Redirec link: https://yourdomain.hu/adalogin/index.php
 *   Egy általad választott ADA rendszerbeli jelszó  
 * Az ADA rendszer adminisztrátorától kapott adatok:
 *   application key ($appkey)
 *   secret ($secret)
 * Ha a látogató belépet az ADA login képernyőn; akkor a Joomla homepage-ra kerül.
 *
 * Módositsad ennek a fájlnak a "config" részét!
 * ============================================
 *
 * Változás történet
 * 2016.09.17  V 3.00
 *   Cross Site Request Forgey attack (CSRF) védelem beépítése   
*/ 

class ada_obj {
	protected $ADA_AUTH_URI = 'https://adatom.hu/ada/v1/oauth2/auth';
	protected $ADA_USER_URI = 'https://adatom.hu/ada/v1/users/me';
	protected $ADA_TOKEN_URI = 'https://adatom.hu/ada/v1/oauth2/token';
	protected $sslverify = 'yes';

	// -------------- config ---------------------------------
	protected $appkey = '';
	protected $secret = '';
	protected $PSW = '';
	protected $home = 'https://mydomain.hu';  // tartalmazza a http:// vagy https:// -t, 
	                                          // de ne tartalmazza az /index.php -t!
	protected $nickHelp = 'Ha gravatar képet töltesz fel, akkor ezt az email címet kell megadnod.<br />Válassz egy "álnevet"! Ebben a rendszerben a többi felhasználó ezen a néven fog téged ismerni, ez jelenik meg a tevékenységeidnél. (Természetesen a valódi neved is megadhatod itt)';
	protected $before_form = '<div class="tarto"><a href="../index.php"><img src="/images/stories/logo.png"></a>
';
	protected $after_form = '</div>';
	protected $TITLE = ' Első bejelentkezés ADA rendszerből';
	protected $ADA_ID = 'ADA azonosító';
	protected $ADA_EMAIL = 'ADA e-mail';
	protected $JOOMLA_NICK = 'Álnév';
	protected $OK = 'Rendben';
	protected $ERROR = 'Hiba lépett fel ';
	protected $NICK_FOGLALT = 'Ez az álnév már foglalt. Válassz másikat!';
	protected $GOTO_HOME = 'Vissza a kezdőlapra';
	// ----------------- config ---------------------------------


	// Joomla rendszer interface rutinok	
	// =================================
	/**
	  * ellenörzi, hogy a paraméterben megaadott user a joomlában regisztrálva van-e ?
	  * @return integer Ha igen akkor Joomla user_id, ha nem akkor 0
	  * @param string user ADAid 
	  * @param string user e-mail
	*/  
	protected function checkUser($adaid, $email) {
		$result = 0;
		$res = false;	
		$db = JFactory::getDBO();
		$db->setQuery('select * from #__users where email = '.$db->quote($email));
		$res = $db->loadObject();
		if ($res) {
			$result = $res->id;
		} else {	
		  $db->setQuery('select * from #__users where username = '.$db->quote($adaid));
		  $res = $db->loadObject();
		  if ($res) {
			  $result = $res->id;
		  } else {
			$db->setQuery('select * from #__users where params = "{\"ADA\":\"'.$db->quote($adaid).'\"}"');
			$res = $db->loadObject();
			if ($res) {
			  $result = $res->id;
			}  
		  }
		}
		return $result;
	}
	
	/**
	  * Új user account létrehozása a Joomlába
	  * @return string  Ha sikeres akkor '', ha hibás akkor hibaüzenet
	*/  
	protected function registUser($adaid,$username, $email, $assurance) {
	  $result = '';
	  $data = array(
          "name"=>$username,
          "username"=>$username,
          "password"=>$this->PSW,
          "password2"=>$this->PSW,
		  "params"=>JSON_decode('{"ADA":"'.$adaid.'"}'),
		  "activation"=>$assurance,
          "email"=>$email,
          "block"=>0,
          "groups"=>array("1","2")
      );
      $user = new JUser;
      if(!$user->bind($data)) {
          $result = "Could not bind data. Error: " . $user->getError();
      }
      if (!$user->save()) {
          $result = "Could not save user. Error: " . $user->getError();
      }
	   return $result;	
	}

	/**
	  * login a joomla rendszerbe
	  * @param integer Joomla userId
	  * @return object JUser   {"id":####, "username":"xxxxx", "email":"xxxxxx",.....}
	*/  
	public function loginToJoomla($userId,&$mainframe) {
		$user = JFactory::getUser($userId);
	    $credentials = array();
		$credentials['username'] = $user->username;
		$credentials['password'] = $this->PSW;
		$user->id = 0; // biztos ami biztos...
		$error = $mainframe->login($credentials);
		$user = JFactory::getUser();
		return $user;
	}

	/**
	  * ADA regisstráció utáni első joomla loginkor megjelenő
	  * képernyő (nick név megadása)
	  * @return void
	  * @param string ada rendszerbeli id
	  * @param string ada rendszer beli email
	  * @param assurance ada hitelesitési szint
	  * @param string üzenet szöveg
	*/  
	public function registForm($adaid, $adaemail, $assurance, $msg) {
		echo '
		<!DOCTYPE html>
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu-hu" lang="hu-hu" dir="ltr">
		<head>
		  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
		  <script src="http://code.jquery.com/jquery-latest.js"></script>
			<style type="text/css">
			<!--
			body {
			background-color: #eeeeee;
			color: #555555;
			margin: 0px auto;
			text-align: center;
			padding: 0px;
			font-family: opensans, sans-serif;
			font-size: 15px;
			font-weight: normal;
			line-height: 120%;
			}
			.tarto {
			width: auto;
			margin: 5% 10%;
			text-align: left;
			background-color: #ffffff;
			padding: 30px;
			}
			h3 {
			display: block;
			background-color: #758ba0;
			color: #ffffff;
			font-size: 1.1em;
			font-weight: normal;
			padding: 10px 2%;
			margin: 25px 0px 10px 0px;
			}
			.adatok {
			padding: 15px 2%;
			background-color: #f5f5f5;
			}
			.adatok span {
			display: inline-block;
			width: 20%;
			margin-right: 2%;
			}
			.help {
			padding: 10px 2%;
			}
			.mezok {
			padding: 0px 2%;
			}
			var {
			font-style: normal;
			color: #fa2929;
			}
			input {
			font-family: sans-serif;
			font-size: 1.0em;
			width: 40%;
			background-color: #f0f0f0;
			color: #999999;
			border: 0px;
			line-height: 30px;
			padding: 0px 5px;
			}
			button {
			font-family: sans-serif;
			font-size: 1.0em;
			display: inline-block; line-height: 30px; padding: 0px 15px; background-color: #fa2929; color: #ffffff; text-decoration: none; margin-top: 0px; border: 0px; box-shadow: none;
			}
			.kiemelt {
			color: #758ba0;
			font-weight: bold;
			display: block;
			margin-top: 5px;
			}
			.tarto img {
			margin-top: 10px;
			margin-left: 5px;
			border: 0px;
			}
			-->
			</style>
		</head>
		<body>
		'.$this->before_form.'
		<form id="adaregist" method="post" action="'.$this->home.'/adalogin/index.php">
		  <h3>'.$this->TITLE.'</h3>
		  <div class="adaRegistMsg">'.$msg.'</div>
		  <input type="hidden" name="adaid" value="'.$adaid.'" />
		  <input type="hidden" name="adaemail" value="'.$adaemail.'" />
		  <input type="hidden" name="assurance" value="'.str_replace('"','',$assurance).'" />
		  <p>'.$this->ADA_ID.':&nbsp;&nbsp;<var>'.$adaid.'</var></p>
		  <p>'.$this->ADA_EMAIL.':&nbsp;&nbsp;&nbsp;<var>'.$adaemail.'</var></p>
		  <div class="help">'.$this->nickHelp.'</div>
		  <div class="mezok">
		    <p>'.$this->JOOMLA_NICK.':<input type="text" name="nick" value="" size="60" />
		      <button type="submit">'.$this->OK.'</button>
		    </p>
		  </div>
		  '.JHtml::_('form.token').'
		</form>
		'.$this->after_form.'
		<center>
		(c) ADA-Joomla integráció.&nbsp;|&nbsp;Szerző: Fogler Tibor (tibor.fogler@gmail.com)&nbsp;|&nbsp;Lecensz:GNU/GPL
		<br /><a href="https://github.com/edemo/Joomla_oauth_plugin">github.com</a>
		</center>
		</body>
		</html>
		';
	}

	// ADA szerver elérés  interface rutinok
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
	  * token objektum lekérése az ADA szervertől
	  * @param string code
	  * @return object  token {"access_token":"xxxxxxxx",......}
	*/  
	protected function getADAtoken($code) {
		$result = '';
		$token = new stdClass();
		$userdata = new stdClass();
		$url = $this->ADA_TOKEN_URI;
		$data = array('timeout' => 30,
						'redirection' => 10,
						'httpversion' => '1.0',
						'code' => $code, 
						'grant_type' => 'authorization_code',
						'client_id' => $this->appkey,
						'client_secret' => $this->secret,
						'redirect_uri' => $this->home.'/adalogin/index.php'
						);
		$result = $this->remoteCall($url,'POST',$data);
		if ($result != '') {
		   $token = JSON_decode($result);
		} 
		return $token;
	}
	
	/**
	  * userData objektum lekérése az ADA szervertől
	  * @param object token  {"access_token":"xxxxxxxx",......}
	  * @return object  {"userid":"xxxxxxxx", "email":"xxxxxxxx",......}
	*/  
	protected function getADAuserData($token) {
		$userData = new stdClass();
		$url = $this->ADA_USER_URI;
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
	  * ugrás az ADA login képernyőre
	  * @return void
	*/ 
	public function loginForm() {
	  $redirectURI = $this->home.'/adalogin/index.php';	
	  $redirectURI = str_replace('http:','https:',$redirectURI);
	  $url = $this->ADA_AUTH_URI.'?response_type=code&client_id='.$this->appkey.'&redirect_uri='.urlencode($redirectURI);
	  header('Location: '.$url);
	}

	/**
	  * ADA tól érkező visszahívás feldolgozása  (a látogató bejelentkezett az ADA-ban)
	  * @return void
	  * JRequest  string code    ADA auth code
	  * Ha sikeres login akkor redirect a joomla homapage-ra, ellenkező esetben hibaüzenet kiirása
	*/ 
	public function doLogin(&$mainframe) {
		echo '<html>
			  <head>
				<meta http-equiv="content-type" content="text/html; charset=utf-8" />
			  </head>	
			  <body>
		';	  
		// get token	
	    $token = $this->getADAtoken(JRequest::getVar('code'));
		// get user data
		if (isset($token->access_token)) {
			$userData = $this->getADAuserData($token);
		}
		if (isset($userData->userid)) {
		  // registered user?	
		  $userId = $this->checkUser($userData->userid, $userData->email);
		  if ($userId == 0) {
			  // create new user
			  $this->registForm($userData->userid, 
			                    $userData->email, 
								JSON_encode($userData->assurances), 
								'');

			  // registered user?
		      $userId = $this->checkUser($userData->userid, $userData->email);
		  } else {
			  // try login into joomla
			  $user = $this->loginToJoomla($userId, $mainframe);
			  if ($user->id > 0) {
				if ($user->activation != JSON_encode($userData->assurances)) {  
				  $user->activation = JSON_encode($userData->assurances);
				  $user->save();	
				}  
				echo '<script language="javascript">; 
					  if (opener) {
				          opener.location.href = "'.$this->home.'/index.php";
				          window.close();	
					  } else {
				          location = "'.$this->home.'/index.php";
					  }
				      </script>
					  </body>
					  </html>
					  '; 
			  } else {
				  echo '<p>'.$this->ERROR.' (3) userid='.$userId.'</p>'; // hiba a joomla.login eljárás közben
			  }
		  }	  
		} else {
			echo '<p>'.$this->ERROR.' (1)</p>'; // hiba az ADA szervertől történő  userData lekérés közben
		}
		echo '<center><br /><br /><a href="'.$this->home.'">'.$this->GOTO_HOME.'</a><br /><br /></center>
		</body>
		</html>
		';
	} // end doLogin function	
	
	/**
	  * create joomla account task (miután a user kitöltötte a nick nevet a regist formon)
	  * @return void
	  * @param object $mainframe
	  * @JREquest nick, adaid, adaemail, assurance
	*/  
	public function createJoomlaAccount($mainframe) {
		$nick = JRequest::getVar('nick');
		$adaid = JRequest::getVar('adaid');
		$adaemail = JRequest::getVar('adaemail');
		$assurance = JRequest::getVar('assurance');
		Jsession::checkToken() or die('invalid CSRF protect token');
		$db = JFactory::getDBO();
		if ($nick == '') {
			$this->registForm($adaid, $adaemail, $assurance, 'Az álnév nem lehet üres');
		} else {
			$db->setQuery('select * from #__users where username = "'.$nick.'"');
			$res = $db->loadObject();
			if ($res == false ) {
				$s = $this->registUser($adaid, $nick, $adaemail, $assurance);
				if ($s != '') {
					echo '<p>'.$s.'</p>';
					return;
				}
				$userId = $this->checkUser($adaid, $adaemail);
			    $user = $this->loginToJoomla($userId, $mainframe);
				if ($user->id > 0) {
				  echo '<html>
						<body>
						<script language="javascript">; 
						if (opener) {
				          opener.location.href = "'.$this->home.'/index.php";
				          window.close();	
						} else {
				          location.href = "'.$this->home.'/index.php";
						}
				        </script>
						</body>
						</html>
						'; 
				}  else {
				  echo '<p>'.$this->ERROR.' (5)</p>';
				  exit();	
				}
			} else {
			  $this->registForm($adaid, $adaemail, $assurance, $nick.' '.$this->NICK_FOGLALT);
			}
		}
	} // function
} // end ada_obj class


// main program
// ============

define( '_JEXEC', 1 );
define('JPATH_BASE', str_replace('/adalogin','',dirname(__FILE__) ));
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

$ada = new ada_obj();

if (JRequest::getVar('code') != '') {
	$ada->doLogin($mainframe);
} else 	if (JRequest::getVar('nick') != '') {
	$ada->createJoomlaAccount($mainframe);
} else {
    $ada->loginForm();	
}

?>
