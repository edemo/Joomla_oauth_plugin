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

require "ada_obj.php";

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
