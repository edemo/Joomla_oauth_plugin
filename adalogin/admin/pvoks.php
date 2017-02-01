<?php
/**
  * com_pvoks
  * Licence: GNU/GPL
  * Author: Fogler Tibor   
  * Author-email: tibor.fogler@gmail.com
  * Author-web: github.com/utopszkij
  * Verzió: V1.00 
  */
  include_once JPATH_ADMIN.'/components/com_pvoks/controllers/controller.php';
  $input = JFactory::getApplication()->input;  
  $task = strtolower($input->get('task','browse'));
  $view = strtolower($input->get('view','categories'));
  if (strpos($task,'.') {
	  $i = strpos($task,'.');
	  $view = substr($task,0,$i);
	  $task = substr($task,$i+1,100);
  }
  if (file_exists(JPATH_ADMIN.'/components/com_pvoks/controllers/'.$view.'.php') {
	include_once  JPATH_ADMIN.'/components/com_pvoks/controllers/'.$view.'.php';
	$controllerName = 'PvoksController'.ucfirst($view);
    $controller = new $controllerName ();
  } else {
    $controller = new PvoksController();
  }	
  $controller->setViewName($view);
  $controller->$task ();
?>
