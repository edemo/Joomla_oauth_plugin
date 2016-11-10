<?php
/**
* @version		$Id:adalogin.php 1 2016-11-08 07:44:41Z FT $
* @package		Adalogin
* @subpackage 	Tables
* @copyright	Copyright (C) 2016, Fogler Tibor. All rights reserved.
* @license #GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

 
class AdaloginViewAdalogin  extends JViewLegacy {

	public function display($tpl = null) 
	{
		$app = &JFactory::getApplication('');
		
		if ($this->getLayout() == 'form') {
		
			$this->_displayForm($tpl);		
			return;
		}
		$context			= 'com_adalogin'.'.'.strtolower($this->getName()).'.list.';
		
		$filter_order = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', $this->get('DefaultFilter'), 'cmd');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '', 'word');
		$search = $app->getUserStateFromRequest($context . 'search', 'search', '', 'string');
		$search = JString :: strtolower($search);
		
		// Get data from the model
		$items = & $this->get('Data');
		$total = & $this->get('Total');
		$pagination = & $this->get('Pagination');
			
		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;
		// search filter
		$lists['search'] = $search;
		$items = $this->get('Data');
		
		//pagination
		$pagination = & $this->get( 'Pagination' );
		
		$this->assignRef('user', JFactory :: getUser());
		$this->assign('lists', $lists);	
		$this->assign('items', $items);		
		$this->assign('total', $total);
		$this->assign('pagination', $pagination);		
		parent::display();
	}
	
	/**
	 *  Displays the form
 	 * @param string $tpl   
     */
	public function _displayForm($tpl)
	{
		
		jimport('joomla.form.formvalidator');		
		JHTML::stylesheet( 'fields.css', 'administrator/components/com_adalogin/assets/' );

		$db			=& JFactory::getDBO();
		$uri 		=& JFactory::getURI();
		$user 		=& JFactory::getUser();
		$form		= $this->get('Form');
		
		$lists = array();

		$editor = & JFactory :: getEditor();

		//get the item
		$item	=& $this->get('item');
		
		if(!version_compare(JVERSION,'3.0','lt')) {
			$form->bind(JArrayHelper::fromObject($item));
		} else {
			$form->bind($item);
		}
		
		$isNew		= ($item->id < 1);			

		// Edit or Create?
		if ($isNew) {
			// initialise new record
			$item->published = 1;
		}
	
		$lists['published'] 		= JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $item->published );
		
	 	$this->assign('form', $form);
	 
		$this->assignRef('lists', $lists);
		$this->assignRef('editor', $editor);
		$this->assignRef('item', $item);
		$this->assignRef('isNew', $isNew);
	
		parent::display($tpl);
	}
}
?>