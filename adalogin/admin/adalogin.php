<?php
/**
 * @version 1.00
 * @package    joomla
 * @subpackage Adalogin
 * @author	   	Fogler Tibor
 *  @copyright  	Copyright (C) 2016, Fogler Tibor. All rights reserved.
 *  @license GNU/GPL
 */

//--No direct access
defined('_JEXEC') or die('Resrtricted Access');

// DS has removed from J 3.0
if(!defined('DS')) {
	define('DS','/');
}
// Require the base controller
require_once( JPATH_COMPONENT.'/controller.php' );

jimport('joomla.application.component.model');
require_once( JPATH_COMPONENT.'/models/model.php' );
// Component Helper
jimport('joomla.application.component.helper');

//add Helperpath to JHTML
JHTML::addIncludePath(JPATH_COMPONENT.'/helpers');

//include Helper
require_once(JPATH_COMPONENT.'/helpers/adalogin.php');

//set the default view
$controller = JRequest::getWord('view', 'adalogin');

//add submenu

AdaloginHelper::addSubmenu($controller);	

// set default task=edit cid[] = 1;
if (JRequest::getVar('task') == '') {
   JRequest::setVar('task','edit');
   JRequest::setVar('cid',array(1));
}   

$ControllerConfig = array();

// Require specific controller if requested
if ( $controller) {   
   $path = JPATH_COMPONENT.'/controllers/'.$controller.'.php';
   $ControllerConfig = array('viewname'=>strtolower($controller),'mainmodel'=>strtolower($controller),'itemname'=>ucfirst(strtolower($controller)));
   if ( file_exists($path)) {
       require_once $path;
   } else {       
	   $controller = '';	   
   }
}

// Create the controller
$classname    = 'AdaloginController'.$controller;
$controller   = new $classname($ControllerConfig );

// Perform the Request task
$controller->execute( JRequest::getVar( 'task' ) );

// Redirect if set by the controller
$controller->redirect();