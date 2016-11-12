<?php
/** 
* Insert com_adalogin component into joomla test server
* 1. crete dir {joomla_root}/components/com_adalogin
* 2. crete dir {joomla_root}/administrator/components/com_adalogin
* 3. copy repistory/adalogin/site into {joomla_root}/components/com_adalogin
* 4. copy repistory/adalogin/admin into {joomla_root}/administrator/components/com_adalogin
* 5. copy this script to joomla_root
* 6. execute this sscript (in wget)
*/ 

// init joomla framwork
define( '_JEXEC', 1 );
define( 'DS', DIRECTORY_SEPARATOR );
$s = str_replace(DS.'components'.DS.'com_adalogin','',dirname(__FILE__) );
define('JPATH_BASE', $s);
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
require_once ( JPATH_BASE .DS.'libraries'.DS.'joomla'.DS.'factory.php' );
require_once ( JPATH_BASE .DS.'components'.DS.'com_adalogin'.DS.'models'.DS.'ada_obj.php');
JDEBUG ? $_PROFILER->mark( 'afterLoad' ) : null;
$mainframe = JFactory::getApplication('site');
$mainframe->initialise();
jimport('joomla.plugin.helper');
jimport('joomla.user.helper');
$input = JFactory::getApplication()->input;

$db = JFactory::getDBO();
$db->setQuery('
INSERT INTO #__extensions
(`extension_id`,`name`,`type`,`element`,`folder`,`client_id`,`enabled`,`access`,
 `protected`,`manifest_cache`,`params`,`custom_data`,`system_data`,`checked_out`,
 `checked_out_time`,`ordering`,`state`) 
VALUES 
(0,"Adalogin","component","com_adalogin","",1,1,0,0,
"{\"name\":\"Adalogin\",\"type\":\"component\",\"creationDate\":\"2016-11-08\",\"author\":\"Fogler Tibor\",\"copyright\":\"Copyright (C) 2016 Fogler Tibor Open Source Matters. All rights reserved.\",\"authorEmail\":\"tibor.fogler@gmail.com\",\"authorUrl\":\"http:\\/\\/adatmagus.hu\",\"version\":\"4.00\",\"description\":\"login into joomla use adataom.hu user authorization service.\",\"group\":\"\",\"filename\":\"com_adalogin\"}",
"{}","","",0,"0000-00-00 00:00:00",0,0);

CREATE TABLE IF NOT EXISTS `#__adalogin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ADA_AUTH_URI` varchar(128) COLLATE utf8_hungarian_ci NOT NULL,
  `ADA_USER_URI` varchar(128) COLLATE utf8_hungarian_ci NOT NULL,
  `ADA_TOKEN_URI` varchar(128) COLLATE utf8_hungarian_ci NOT NULL,
  `appkey` varchar(128) COLLATE utf8_hungarian_ci NOT NULL,
  `secret` varchar(128) COLLATE utf8_hungarian_ci NOT NULL,
  `joomla_psw` varchar(128) COLLATE utf8_hungarian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COLLATE=utf8_hungarian_ci;

INSERT IGNORE INTO #__adalogin 
	(`id`, 
	`ADA_AUTH_URI`, 
	`ADA_USER_URI`, 
	`ADA_TOKEN_URI`, 
	`appkey`, 
	`secret`, 
	`joomla_psw`
	)
	VALUES
	(1, 
	"https://adatom.hu/ada/v1/oauth2/auth", 
	"https://adatom.hu/ada/v1/users/me", 
	"https://adatom.hu/ada/v1/oauth2/token", 
	"appkey", 
	"secret", 
	ROUND(10000000 * RAND()) + 10000000
	); 
');
$db->query();

?>
