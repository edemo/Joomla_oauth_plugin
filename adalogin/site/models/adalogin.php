<?php 
/**
* @version		4.00
* @package		Adalogin
* @subpackage 	Models
* @copyright	Copyright (C) 2016, Fogler Tibor. All rights reserved.
* @license #GNU/GPL
*
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
	*
	* Baj van az ADA email cím nem alkalmas azonositásra, változhat!
  */
  public function getUser($adaid, $adaemail) {
		$usr = 0;
		$result = new stdClass();
		$result->id = 0;
		$db = JFactory::getDBO();
		//+ 2017.12.26
		$db->setQuery('create table if not exists #__adausers (
			adaid varchar(64),
			userid int(11),
			KEY index1 (adaid),
			KEY index2 (userid)
		)');
		$db->query();

		$db->setQuery('select userid as id from #__adausers where adaid = '.$db->quote($adaid));
		$res = $db->loadObject();
		if ($res) {
			// megvan a #__adausers táblában
			$result = JFactory::getUser($res->id);
		} else {
			// nincs meg a #__adausers táblában
			$db->setQuery('select * from #__users where email = '.$db->quote($adaemail));
			$res = $db->loadObject();
			if ($res) {
				// email cim alapján talált user rekordot
				$result = JFactory::getUser($res->id);
			} else {	
				$db->setQuery('select * from #__users where username = '.$db->quote($adaid));
				$res = $db->loadObject();
				if ($res) {
					// username alapján talált user rekordot
					$result = JFactory::getUser($res->id);
				} 
			}
			if ($result->id > 0) {
				// sikerült usert találni, de az adausers táblában nincs benne, tegyük most bele!
				$db->setQuery('insert into #__adausers values ('.$db->quote($adaid).','.$result->id.')');
				$db->query();
			}
			//- 2017.12.26 	  
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
  * @return boolean and set errorMsg  
  */
  public function save($adaid, $nick, $adaemail, $assurance) {
		$result = true;
		$db = JFactory::getDBO();
		$data = array(
          "name"=>$nick,
          "username"=>$nick,
          "password"=>$this->PSW,
          "password2"=>$this->PSW,
				  "params"=>JSON_decode('{"ADA":"'.$adaid.'","ASSURANCE":"'.$assurance.'"}'),
		 			"activation"=>"",
          "email"=>$adaemail,
          "block"=>0,
          "groups"=>array("1","2")
    );
    $user = new JUser();
    if(!$user->bind($data)) {
		  $result = false;
          $this->setError(JText::_('ADALOGIN_ERROR').' '. $user->getError());
    }
    if (!$user->save()) {
		  $result = false;
          $this->setError(JText::_('ADALOGIN_ERROR').' '. $user->getError());
    }
		if ($result) {
			$db->setQuery('insert into #__adausers values ('.$db->quote($adaid).','.$user->id.')');
			$db->query();
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
		$credentials['username'] = $user->username;
		$credentials['password'] = $this->PSW;
		$user->id = 0; 
		$result = JFactory::getApplication()->login($credentials);
		if ($result == false) $this->setError('Error in Joomla login');
	return $result;
  }
  /**
  * user assurance tárolása a joomla adatbázisba ($assurance a params -ba és usergroupsba)
  * @param JUser   params->ASSURANCE json_string
  * @param json_string assurance
  * return void  
  */  
  public function setUserAssurances($user, $assurance) {
		$db = JFactory::getDBO();  
		$canUpgrade = false;
		$assuranceArray = JSON_decode($assurance);	

		// assurances usergroupok beolvasása a $assuranceGroups -ba
		$assuranceGroups = array();  // key: group_id, value:groupTitle = assuranceName
		$db->setQuery('select id,title
		from #__usergroups
		where parent_id = 2');
		$res = $db->loadObjectList();
		foreach ($res as $res1) {
			$assuranceGroups[$res1->id] = $res1->title;
		}	

		// ha még nincs benne a $assurance valemeyik elem akkor most létrehozzuk
		foreach ($assuranceArray as $as) {
			if (in_array($as, $assuranceGroups) == false) {
				$canUpgrade = true;  
				$group = array('id'=>0, 'title'=> $as, 'parent_id'=>2);
				JLoader::import('joomla.application.component.model');
				JLoader::import('group', JPATH_ADMINISTRATOR.'/components/com_users/models');
				$groupModel = JModelLegacy::getInstance( 'Group', 'UsersModel' );
				$groupModel->save($group);
			}
		}
	
		// szükség esetén az assurances usergroupok ismételt beolvasása a $assuranceGroups -ba
		if ($canUpgrade) {
			$assuranceGroups = array();  // key: group_id, value:groupTitle = assuranceName
			$db->setQuery('select id,title
			from #__usergroups
			where parent_id = 2');
			$res = $db->loadObjectList();
			foreach ($res as $res1) $assuranceGroups[$res1->id] = $res1->title;
		}

		if (is_object($user)) {  

			/* JUserHelper metodusok
			setUserGroups(integer $userId, array $groups) : boolean
			addUserToGroup(integer $userId, integer $groupId) : boolean
			removeUserFromGroup(integer $userId, integer $groupId) : boolean
			*/

			//+ 2017 elovalasztok specialitas
			if ($assurance != $user->getParam('ASSURANCE')) {
				$user->setParam('ASSURANCE',$assurance);
				$user->save();
				// usergroups beállítása
				$groups = array(1,2);
				foreach ($assuranceArray as $as) {
					foreach ($assuranceGroups as $key => $value) {
						if ($value == $as) 
							$groups[] = $key;
					}
				}
				JUserHelper::setUserGroups($user->id, $groups); 
			}  
		}
  }	
}
?> 
