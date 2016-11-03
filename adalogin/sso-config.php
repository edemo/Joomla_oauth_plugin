<?php

class sso_config {
	protected $ADA_AUTH_URI = 'https://adatom.hu/ada/v1/oauth2/auth';
	protected $ADA_USER_URI = 'https://adatom.hu/ada/v1/users/me';
	protected $ADA_TOKEN_URI = 'https://adatom.hu/ada/v1/oauth2/token';
	protected $sslverify = 'yes';
	protected $appkey = 'APP_ID_COMES_HERE';
	protected $secret = 'APP_SECRET_COMES_HERE';
	protected $PSW = 'PASSWORD_COMES_HERE';
	protected $home = 'https://BASE_URL_COMES_HERE';  // tartalmazza a http:// vagy https:// -t, 
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
}
?>
