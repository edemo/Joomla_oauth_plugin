<?php
/**
* @version		$Id: default_controller.php 96 2011-08-11 06:59:32Z michel $
* @package		Adalogin
* @subpackage 	Controllers
* @copyright	Copyright (C) 2016, Fogler Tibor. All rights reserved.
* @license #GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

/**
 * AdaloginAdalogin Controller
 *
 * @package    Adalogin
 * @subpackage Controllers
 */
class AdaloginControllerAdalogin extends AdaloginController
{
	/**
	 * Constructor
	 */
	protected $_viewname = 'adalogin'; 
	 
	public function __construct($config = array ()) 
	{
		parent :: __construct($config);
		JRequest :: setVar('view', $this->_viewname);

	}
	
    public function save() {
		$model = $this->getModel();
		if ($model->store(JRequest::getVar('jform'))) {
			$this->setMessage(JText::_('ADALOGIN_CONFIG_SAVED'));
		} else {
			$this->setMessage($model->getError());
		}	
		$this->setRedirect(JURI::base().'index.php');
		$this->redirect();
	}
	
    public function apply() {
		$model = $this->getModel();
		if ($model->store(JRequest::getVar('jform'))) {
			$this->setMessage(JText::_('ADALOGIN_CONFIG_SAVED'));
		} else {
			$this->setMessage($model->getError());
		}	
		$this->setRedirect(JURI::base().'index.php?option=com_adalogin');
		$this->redirect();
	}

    public function cancel() {
		$this->setRedirect(JURI::base().'index.php');
		$this->redirect();
	}
	
	
	
}// class
?>