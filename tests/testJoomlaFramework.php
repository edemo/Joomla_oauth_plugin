<?php
/**
* test framwork for joomla components unit test
*/
error_reporting(E_ALL & ~E_NOTICE);
define( '_JEXEC', 1 );
define( '_UNITTEST', 1 );
define( 'DS', DIRECTORY_SEPARATOR );
define('JPATH_BASE', 'repo');
define('JPATH_COMPONENT', 'repo/site');
define('JPATH_ADMINISTRATOR', 'repo/admin');

global $_SERVER;
global $mock_data;


/**
* set component name for test (without 'com_')
*/
$componentName = 'valami';

/**
* set input parameters for test
* $inputs['name1'] = 'value1', $inputs['name2'] = 'value2', .... 
*/
global $inputs;

/**
* set Database result, and errorNum, errorMsg for test
* $dbResults[0] = JSON_encode('{'field1":"value1", "field2":"value2"}'); 
* $dbResults[1] = JSON_encode([{'field1":"value1", "field2":"value2"}, {'field1":"value11", "field2":"value12"}])             
* set $dbErrorNum, $dbErrorMsg
*/
global $dbResults;
global $dbErrorNum;
global $dbErrorMsg;
global $dbIndex;

/**
* set remoteCall results for test
*/
global $remoteResults;
global $remoteIndex;

global $application;
global $document;
global $language;
global $table;
global $database;
global $user;
global $input;


class JFactory {
	public static function getApplication() {
		global $application;
		return $appliacation;
	}
	public static  function getDocument() {
		global $document;
		return $document;
	}
	public static  function getUser() {
		global $application;
		return $appliacation;
	}
	public static  function getLanguage() {
		global $language;
		return $language;
	}
	public static  function getDBO() {
		global $database;
		return $database;
	}
}
class JApplication {
	public $input;
    function __construct() {
		$this->input = new JInput();
	}	
}
class JDocument {
	public function getType() {
		return 'html';
	}
}
class JInput {
	public function get($name, $default='') {
		global $inputs;
		if (isset($inputs[$name])) 
			$result = $inputs[$name];
		else
			$result = $default;
		return $result;
	}
	public function set($name,$value,$dataType='') {
		global $inputs;
		$inputs[$name] = $value;
	}
}
class JRequest {
	public  static function getValue($name, $default='', $dataType='') {
		global $inputs;
		if (isset($inputs[$name])) 
			$result = $inputs[$name];
		else
			$result = $default;
		return $result;
	}
	public  static function getWord($name, $default='', $dataType='') {
		return $this->getValue($name, $default, $dataType);
	}
	public  static function getCmd($name, $default='', $dataType='') {
		return $this->getValue($name, $default, $dataType);
	}
	public  static function setValue($name,$value,$dataType) {
		global $inputs;
		$inputs[$name] = $value;
	}
}
class JURI {
	public  static function base() {
		return 'http://localhost/';
	}
	public  static function root() {
		return 'http://localhost/';
	}
}
class JText {
	public  static function _($token) {
		return $token;
	}
}
class JHTML {
	public  static function _($token) {
		return '<span>'.$token.'</span>';
	}
}
class JDatabase {
	public function setQuery($sql) {
		
	}
	public function getQuery() {
		return '';
	}
	public function loadObjectList() {
		
		
		global $dbResults, $dbIndex;
		if ($dbIndex < count($dbResults))
			$result = $dbResults[$dbIndex];
		else
			$result = false;
		$dbIndex++;
		return $result;
	}
	public function loadObject() {
		return $this->loadObjectList();
	}
	public function query() {
		return true;
	}
	public function getErrorNum() {
		global $dbErrorNum;
		return $dbErrorNum;
	}
	public function getErrorMsg() {
		global $dbErrorMsg;
		return $dbErrorMsg;
	}
}

class JUser {
	public $id = 0;
	public $username = '';
	public $name = '';
	public function save() {}
}

class JLanguage {
	
}
class JTable {
	
}
class JControllerLegacy {
	protected $redirectURI = '';
	
	function __construct($config) {}
	public function getView($viewName = 'default',$viewType='html') {
		global $componentName;
		require_once (JPATH_COMPONENT.DS.'views'.DS.$viewName.DS.'view.'.$viewType.'.php');
		$viewClassName = $componentName.'View'.ucfirst($viewName);
		return new $viewClassName ();
	}
	public function getModel($modelName = 'default') {
		global $componentName;
		require_once (JPATH_COMPONENT.DS.'models'.$modelName.'.php');
		$modelClassName = $componentName.'Model'.ucfirst($modelName);
		return new $modelClassName ();
	}
	public function setRedirect($uri) {
	  $this->redirectURI = $uri;	
	}
	public function redirect($message = '') {
		global $fake;
        $mock_data["redirectURI"] = $this->redirectURI;
		$mock_data["redirectMsg"] = $message;
	}
}
class JModelLegacy {
	public function getTable($tableName) {
		
	}
	public function getQuerySql() {
		
	}
	public function getTotal() {
		
	}
	public function getItems() {
		
	}
	public function getItem($id) {
		
	}
	public function save($data) {
		
	}
	public function delete($source) {
		
	}
	public function check($data) {
		
	}
	public function canDelete($data) {
		
	}
	public function setError($str) {
		
	}
	public function getError() {
		
	}
}
class JViewLegacy {
	public function setLayout($str) {
		
	}
	public function display($tmp) {
		
	}
}

// global functions
function jimport($str) {}

// init globals
$_SERVER['HTTP_SITE'] = 'localhost';
$_SERVER['REQUEST_URI'] = 'index.php';
$mock_data = array();
$inputs = array();
$dbResults = array();
$dbErrorNum = 0;
$dbErrorMsg = '';
$dbIndex = 0;
$application = new JApplication();
$document = new JDocument();
$language = new JLanguage();
$table = new JTable();
$database = new JDatabase();
$user = new JUser();
$input = new JInput();
$remoteResults = array();
$remoteIndex = 0;
?> 