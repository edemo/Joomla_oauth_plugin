<?php 
/**
* @version		4.00
* @package		Adalogin
* @subpackage 	Models
* @copyright	Copyright (C) 2016, Fogler Tibor. All rights reserved.
* @license #GNU/GPL
*
* 2016.12.15 V4.01 extradata kezelés támogatása, újabb joomla psw kezelési eljárás
*/
// no direct access
defined('_JEXEC') or die('Restricted access');
/**
 * Model
 * @author Michael Liebler
 */
 
jimport( 'joomla.application.component.model' ); 

/**
* this component not use model
*/
class AdaloginModelAdalogin extends JModelLegacy  { 
  protected $controller;
  protected $errorMsg;
  public $PSW;
  
  /**
  * get user object from jooma
  * @param string adaid
  * @param string adaemail
  * @return if exists JUser else {id:0}
  */
  public function getUser($adaid, $adaemail) {
	$usr = 0;
	$result = new stdClass();
	$result->id = 0;
	$db = JFactory::getDBO();
	$db->setQuery('select * from #__users where email = '.$db->quote($adaemail));
	$res = $db->loadObject();
	if ($res) {
		$result = JFactory::getUser($res->id);
	} else {	
	  $db->setQuery('select * from #__users where username = '.$db->quote($adaid));
	  $res = $db->loadObject();
	  if ($res) {
		$result = JFactory::getUser($res->id);
	  } else {
		$db->setQuery('select * from #__users where params like "{%\"ADA\":\"'.$adaid.'\"%"');
		$res = $db->loadObject();
		if ($res) {
		  $result = JFactory::getUser($res->id);
		}  
	  } 
    }	  
	if ($result->id == 0)
		$this->setError(JText::_('ADALOGIN_JOOMLA_LOGIN_ERROR'));
	return $result;
  }
  
  /**
  * check new user nick
  * @param string nick
  * @return boolean and set errorMsg
  */
  public function checkNewNick($nick) {
	$result = true;  
	$db = JFactory::getDBO();
	$db->setQuery('select * from #__users where username = '.$db->quote($nick));
	$res = $db->loadObject();
	if ($res) {
		$result = false;
		$this->setError(JText::_('ADALOGIN_NICK_USED'));
	}
	if ($nick == '') {
		$result = false;
		$this->setError(JText::_('ADALOGIN_NICK_REQUED'));
	}
	return $result;
  }	

  /**
  * create joomla account
  * @param string adaid
  * @param string nick
  * @param string adaemail
  * @param JSON_encoded assurance
  * @param string extradata    (2016.12.15 update)
  * @return boolean and set errorMsg  
  */
  public function save($adaid, $nick, $adaemail, $assurance, $extrafields=array()) {
	$result = true;
	$db = JFactory::getDBO();
	$params = new stdClass();
	$params->ADA = $adaid;
	$params->ASSURANCE = $assurance;
	$psw = md5($adaid.$this->PSW);
	$data = array(
          "name"=>$nick,
          "username"=>$nick,
          "password"=>$psw,
          "password2"=>$psw,
		  "params"=>$params,
		  "activation"=>"",
          "email"=>$adaemail,
          "block"=>0,
          "groups"=>array("1","2")
    );
	if (isset($extrafields['name'])) {
		if ($extrafields['name'] != '')
			$data['name'] = $extrafields['name'];
	}	
	if (isset($extrafields['email'])) {
		if ($extrafields['email'] != '')
			$data['email'] = $extrafields['email'];
	}
    $user = new JUser();
    if(!$user->bind($data)) {
		  $result = false;
          $this->setError(JText::_('ADALOGIN_ERROR').' '. $user->getError());
    }
    if (!$user->save()) {
		  $result = false;
          $this->setError(JText::_('ADALOGIN_ERROR').' '. $user->getError());
    } else {
		// save data to user_profile
		foreach ($extrafields as $fn => $fv) {
			if (($fn != 'name') & ($fn != 'email')) {
				$db->setQuery('insert into #__user_profiles values ('.$user->id.',"profile.'.$fn.'","'.$fv.'",0)');
				$db->query();
			}
		}
	}
	return $result;	
  }

  /**
  * login to joomla 
  * @param string adaid
  * @param string $adaemail
  * @return boolean and set errorMsg
  */
  public function loginToJoomla($adaid, $adaemail) {
    $user = $this->getUser($adaid, $adaemail); 
	$credentials = array();
	
	//1. próba az újabb (V4.01) eljárás szerinti psw -al
	$credentials['username'] = $user->username;
	$credentials['password'] = md5($adaid.$this->PSW);
	$user->id = 0; 
	$result = JFactory::getApplication()->login($credentials);
	if ($result == false) {
		//2. próba a régi psw eljárással
		$credentials['username'] = $user->username;
		$credentials['password'] = $this->PSW;
		$result = JFactory::getApplication()->login($credentials);
	}
	if ($result == false) {
	  $this->setError('Error in Joomla login');
	}  
	return $result;
  }	  
  
}
?> 