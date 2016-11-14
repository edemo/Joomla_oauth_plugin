<?php
/**
* @version		4.00
* @package		Adalogin
* @subpackage 	Controllers
* @copyright	Copyright (C) 2016, Fogler Tibor. All rights reserved.
* @license #GNU/GPL
*
* ADA authoraze service integarttion
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');
require_once (JPATH_COMPONENT.DS.'models'.DS.'ada_obj.php');

/**
 * Variant Controller
 *
 * @package    
 * @subpackage Controllers
 */
class AdaloginController extends JControllerLegacy
{
	protected $redi;
	protected $_viewname = 'adalogin';
	protected $_mainmodel = 'adalogin';
	protected $_context = "com_adalogin";
	protected $ADA_AUTH_URI; 
	protected $ADA_USER_URI; 
	protected $ADA_TOKEN_URI; 
	protected $appkey; 
	protected $secret; 
	protected $joomla_psw;

	/**
	* Constructor
	*/
	public function __construct($config = array ()) {
		parent :: __construct($config);
		$input = JFactory::getApplication()->input;
		$this->redi = $config['redi'];
		if ($input->get('redi') != '') $this->redi = base64_decode($input->get('redi'));
		if ($this->redi == '') $this->redi = JURI::base();
		$input->set('view', $this->_viewname);
	}

	/**
	* only techical. Joomla MVC requed
	*/
	public function display() {
		$document =& JFactory::getDocument();
		$viewType	= $document->getType();
		$view = & $this->getView($this->_viewname,$viewType);
		$model = & $this->getModel($this->_mainmodel);
		$view->setModel($model,true);		
		$view->display();
	}

	/**
	* default task, redirect ADA server: get loginform
	*/
	public function loginform() {
	  $ada = new AdaloginModelAda_obj();	
	  $this->setRedirect($ada->getLoginURI($this->redi));
	  $this->redirect();
	}
	
	/**
	* display ada_user_regist form
	*/
	protected function displayRegistForm($view, $adaid, $adaemail, $assurance, $redi) {
		$view->set('adaid',$adaid);
		$view->set('adaemail',$adaemail);
		$view->set('assurance',$assurance);
		$view->set('redi',base64_encode($redi));
		$view->setLayout('regist');
		$view->display();
	}
	
	/**
	* process adaid, adaemail, assurance, redi , CSRF_token data from components/com_adalogin/index.php
	*/
    public function dologin() {
		$input = JFactory::getApplication()->input;
		JSession::checkToken() or die( 'Invalid Token' );		
		$adaid = $input->get('adaid');
		$adaemail = $input->get('adaemail','','string');
	    $assurance = $input->get('assurance','','string');
		$redi = base64_decode($input->get('redi','','string'));
		if ($redi == '') $redi = JURI::base();
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$view = $this->getView($this->_viewname,$viewType);
		$model = $this->getModel($this->_mainmodel);
	    $ada = new AdaloginModelAda_obj();	
		$model->set('PSW',$ada->joomla_psw);
		$view->setModel($model,true);
		$user = $model->getUser($adaid, $adaemail);
		if ($user->id > 0) {
			if ($assurance != $user->getParam('ASSURANCE')) {
				$user->setParam('ASSURANCE',$assurance);
				$user->save();
			}
			// login to joomla 
			if ($model->loginToJoomla($adaid, $adaemail)) {
				// goto $redi
				$this->setRedirect($redi);
				$this->redirect();
			} else {
				echo '<p class="errorMsg">'.$model->getError().'</p>';
			}
		} else {
			$this->displayRegistForm($view, $adaid, $adaemail, $assurance, $redi);
		}	
	}	// dologin
	
	/**
	* process registform  adaid, adaemail, nick, assurance, redi , CSRF_token data from components/com_adalogin/index.php
	*/
	public function processform() {
		JSession::checkToken() or die( 'Invalid Token' );		
		$input = JFactory::getApplication()->input;
		$adaid = $input->get('adaid');
		$adaemail = $input->get('adaemail','','string');
	    $assurance = $input->get('assurance','','string');
		$redi = base64_decode($input->get('redi','','string'));
		if ($redi == '') $redi = JURI::base();	
	    $nick = $input->get('nick');
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$view = $this->getView($this->_viewname,$viewType);
		$model = $this->getModel($this->_mainmodel);
	    $ada = new AdaloginModelAda_obj();	
		$model->set('PSW',$ada->joomla_psw);
		$view->setModel($model,true);
		if ($model->checkNewNick($nick)) {
			if ($model->userSave($adaid, $nick, $adaemail, $assurance)) {
				// login to joomla 
				if ($model->loginToJoomla($adaid, $adaemail)) {
					// goto $redi
					$this->setRedirect($redi);
					$this->redirect();
				} else {
					echo '<p class="errorMsg">'.$model->getError().'</p>';
				}
			} else {
				echo '<p class="errorMsg">'.$model->getError().'</p>';
			}		
		} else {
			// display regist form
			echo '<p class="errorMsg">'.$model->getError().'</p>';
			$this->displayRegistForm($view, $adaid, $adaemail, $assurance, $redi);
		}
	} // processform
}// class
?>