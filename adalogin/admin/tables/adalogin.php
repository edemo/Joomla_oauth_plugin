<?php
/**
* @version		$Id:adalogin.php  1 2016-11-08 07:44:41Z FT $
* @package		Adalogin
* @subpackage 	Tables
* @copyright	Copyright (C) 2016, Fogler Tibor. All rights reserved.
* @license #GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
* Jimtawl TableAdalogin class
*
* @package		Adalogin
* @subpackage	Tables
*/
class TableAdalogin extends JTable
{
	
   /** @var int id- Primary Key  **/
   public $id = null;

   /** @var varchar ADA_AUTH_URI  **/
   public $ADA_AUTH_URI = null;

   /** @var varchar ADA_USER_URI  **/
   public $ADA_USER_URI = null;

   /** @var varchar ADA_TOKEN_URI  **/
   public $ADA_TOKEN_URI = null;

   /** @var varchar appkey  **/
   public $appkey = null;

   /** @var varchar secret  **/
   public $secret = null;

   /** @var varchar joomla_psw  **/
   public $joomla_psw = null;




	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	public function __construct(& $db) 
	{
		parent::__construct('#__adalogin', 'id', $db);
	}

	/**
	* Overloaded bind function
	*
	* @acces public
	* @param array $hash named array
	* @return null|string	null is operation was satisfactory, otherwise returns an error
	* @see JTable:bind
	* @since 1.5
	*/
	public function bind($array, $ignore = '')
	{ 
		
		return parent::bind($array, $ignore);		
	}

	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 * @return boolean True on success
	 * @since 1.0
	 */
	public function check()
	{



		/** check for valid name */
		/**
		if (trim($this->ADA_AUTH_URI) == '') {
			$this->setError(JText::_('Your Adalogin must contain a ADA_AUTH_URI.')); 
			return false;
		}
		**/		

		return true;
	}
}
