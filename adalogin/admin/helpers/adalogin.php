<?php
/**
 * @version		$Id: #component#.php 125 2012-10-09 11:09:48Z michel $
 * @package		Joomla.Framework
 * @subpackage		HTML
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;



class AdaloginHelper
{
	
	/*
	 * Submenu for Joomla 1.6
	 */
	public static function addSubmenu($vName = 'adalogin')
	{
        
		JSubMenuHelper::addEntry(
			JText::_('Adalogin'),
			'index.php?option=com_adalogin&view=adalogin',
			($vName == 'adalogin')
		);

	}
	
	/**
	 * 
	 * Get the Extensions for Categories
	 */
	public static function getExtensions() 
	{
						
		jimport('joomla.utilities.xmlelement');
		
		$xml = simplexml_load_file(JPATH_ADMINISTRATOR.'/components/com_adalogin/elements/extensions.xml', 'JXMLElement');		        
		$elements = $xml->xpath('extensions');
		$extensions = $xml->extensions->xpath('descendant-or-self::extension');
		
		return $extensions;
	} 	
}

/**
 * Utility class for categories
 *
 * @static
 * @package 	Joomla.Framework
 * @subpackage	HTML
 * @since		1.5
 */
abstract class JHtmlAdalogin
{
	/**
	 * @var	array	Cached array of the category items.
	 */
	protected static $items = array();
	
	/**
	 * Returns the options for extensions list
	 * 
	 * @param string $ext - the extension
	 */
	public static function extensions($ext) 
	{
		$extensions = AdaloginHelper::getExtensions();
		$options = array();
		
		foreach ($extensions as $extension) {   
		
			$option = new stdClass();
			$option->text = JText::_(ucfirst($extension->name));
			$option->value = 'com_adalogin.'.$extension->name;
			$options[] = $option;			
		}		
		return JHtml::_('select.options', $options, 'value', 'text', $ext, true);
	}
	
	/**
	 * Returns an array of categories for the given extension.
	 *
	 * @param	string	The extension option.
	 * @param	array	An array of configuration options. By default, only published and unpulbished categories are returned.
	 *
	 * @return	array
	 */
	public static function categories($extension, $cat_id,$name="categories",$title="Select Category", $config = array('attributes'=>'class="inputbox"','filter.published' => array(0,1)))
	{

			$config	= (array) $config;
			$db		= &JFactory::getDbo();

			$query = $db->getQuery(true);

			$query->select('a.id, a.title, a.level');
			$query->from('#__adalogin_categories AS a');
			$query->where('a.parent_id > 0');

			// Filter on extension.
			if($extension)
			    $query->where('extension = '.$db->quote($extension));
			
			$attributes = "";
			
			if (isset($config['attributes'])) {
				$attributes = $config['attributes'];
			}
			
			// Filter on the published state
			if (isset($config['filter.published'])) {
				
				if (is_numeric($config['filter.published'])) {
					
					$query->where('a.published = '.(int) $config['filter.published']);
					
				} else if (is_array($config['filter.published'])) {
					
					JArrayHelper::toInteger($config['filter.published']);
					$query->where('a.published IN ('.implode(',', $config['filter.published']).')');
					
				}
			}

			$query->order('a.lft');

			$db->setQuery($query);
			$items = $db->loadObjectList();
			
			// Assemble the list options.
			self::$items = array();
			self::$items[] = JHtml::_('select.option', '', JText::_($title));
			foreach ($items as &$item) {
								
				$item->title = str_repeat('- ', $item->level - 1).$item->title;
				self::$items[] = JHtml::_('select.option', $item->id, $item->title);
			}

		return  JHtml::_('select.genericlist', self::$items, $name, $attributes, 'value', 'text', $cat_id, $name);
		//return self::$items;
	}
}