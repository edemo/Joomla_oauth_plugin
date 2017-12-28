<?php
/**
  * com_pvoks
  * Licence: GNU/GPL
  * Author: Fogler Tibor   
  * Author-email: tibor.fogler@gmail.com
  * Author-web: github.com/utopszkij
  * Verzió: V1.00 
 */

defined("_JEXEC") or die("Restricted access");
require_once JPATH_COMPONENT.DS.'models'.DS.'model.php';

class PvoksController extends JControllerLegacy {
	protected $componentName = 'pvoks'; // controllers/pvoks.php -ben PvoksControllerNezet class
	protected $viewName;                //views/view.html.php -ben PvoksViewNezet class, és js/nezet.js és models/forms/nezet.xml 
    protected $modelName;               // models/nezet.php -ben PvoksModelNezet class
	protected $formName;                // form xml file name
	protected $defSort;                 // default rendezési mező 
	protected $defOrder;                // default rendezési rány
	protected $defLimit;                // böngésző sor méret
	protected $defTask;                 // default task
	protected $lngPre;                  // leng pre

    function __construct() {
		parent::__construct();
		$this->viewName = 'categories';
		$this->modelName = 'categories';
		$this->formName = 'categories';
		$this->defSort = 'a.id';        
		$this->defOrder = 'asc';        
		$this->defLimit = 20;           
		$this->defTask = 'browse';      
		$this->lngPre = 'PVOKS_';
	}
	
	public function setViewName($viewName) {
		$this->viewName = $viewName;
		$this->modelName = $viewName;
		$this->formName = $viewName;;
	}
	
	/**
	 * bejelentkezett usernek van joga a task használatára?
	 * @param string task
	 * @param record vagy ''
	 * @return boolean
	*/ 
	protected function accessControl($task,$item) {
		$result = true;
		$user = JFactory::getUser();
		return $result;
	}
	
	/**
	* echo browser action buttons
	* @params none
	* @return void
	*/
	protected function browseButtons() {
		?>
		<h2><?php echo JText::_($this->lngPre.'LIST'); ?></h2>
		<div class="buttons">
		  <?php if ($this->accessControl('add','')) : ?>
		  <button type="button" class="btn btn-add" onclick="submitbutton('add');">
			<i class="icon-new"></i><span><?php echo JText::_($this->lngPre.'BTN_ADD'); ?></span>
		  </button>
		  <?php endif; ?>
		  <?php if ($this->accessControl('edit','')) :?>
		  <button type="button" class="btn btn-edit" onclick="submitbutton('edit');">
			<i class="icon-edit"></i><span><?php echo JText::_($this->lngPre.'BTN_EDIT'); ?></span>
		  </button>
		  <?php endif; ?>
		  <?php if ($this->accessControl('delete','')) :?>
		  <button type="button" class="btn btn-danger btn-delete" onclick="submitbutton('delete');">
			<i class="icon-delete"></i><span><?php echo JText::_($this->lngPre.'BTN_DELETE'); ?></span>
		  </button>
		  <?php endif; ?>
		</div>
		<?php
	}
	
	/**
	* echo add form action buttons
	* @params none
	* @return void
	*/
	protected function addButtons() {	
			?>
			<h2><?php echo JText::_($this->lngPre.'ADD'); ?></h2>
			<div class="buttons">
			  <button type="button" class="btn btn-primary" onclick="submitbutton('save');">
				<i class="icon-save"></i><span><?php echo JText::_($this->lngPre.'BTN_SAVE'); ?></span>
			  </button>
			  <button type="button" class="btn btn-primary" onclick="submitbutton('savenew');">
				<i class="icon-save"></i><i class="icon-plus"></i><span><?php echo JText::_($this->lngPre.'BTN_SAVENEW'); ?><span>
			  </button>
			  <button type="button" class="btn" onclick="submitbutton('browse');">
				<i class="icon-cancel"></i><span><?php echo JText::_($this->lngPre.'BTN_CANCEL'); ?></span>
			  </button>
			</div>
			<?php
	}
			
	/**
	* echo edit form action buttons
	* @params none
	* @return void
	*/
	protected function editButtons() {
			?>
			<h2><?php echo JText::_($this->lngPre.'EDIT');; ?></h2>
			<div class="buttons">
			  <button type="button" class="btn btn-primary" onclick="submitbutton('save');">
				<i class="icon-save"></i><span><?php echo JText::_($this->lngPre.'BTN_SAVE'); ?></span>
			  </button>
			  <button type="button" class="btn" onclick="submitbutton('browse');">
				<i class="icon-cancel"></i><span><?php echo JText::_($this->lngPre.'BTN_CANCEL'); ?></span>
			  </button>
			</div>
			<?php
	}
	
	/**
	* inti jform'fields for save and New function
	*/
	protected function editNewInit(&$jform) {
	  $jform['id'] = 0;	
	}
	
    /**
	  * execute a task
	  * @param string  $task végrehajtandó task
	*/  
	public function execute($task = '') {
		$input = JFactory::getApplication()->input;
		if ($task == '') $task = $input->get('task');
		if ($task == '') $task = $this->defTask;
		$this->$task ();
	}


	public function getModel($modelName = '') {
		if ($modelName == '') $modelName = $this->modelName;
		return parent::getModel($modelName);
	}
	
	
	public function getView($viewName='', $viewType='html') {
		if ($viewName == '') $viewName = $this->viewName;
		return parent::getView($viewName, $viewType);
	}

	protected function getForm() {
		return new JForm($this->formName);
	}
	
	/**
	  * böngészés task
	  * @param string $msg  üzenet
	  * @param string $msgClass  üzent css class
	*/  
	public function browse($msg = '', $msgClass = 'info') {
		$session = JFactory::getSession();
		$input = JFactory::getApplication()->input;
		$document = JFactory::getDocument();
		
		if ($msg != '')
			echo '<div class="'.$msgClass.'">'.$msg.'</div>';

		if (file_exists(JPATH_COMPONENT.'/views/'.$this->viewName.'/tmpl/default.js'))	{
			echo '<script type="text/javascript">
		    ';
			include JPATH_COMPONENT.'/views/'.$this->viewName.'/tmpl/default.js';
			echo '</script>
			';
		}

		
		$model = $this->getModel($this->modelName);
		$status = $model->getState($this->componentName.'.'.$this->viewName);
		// ha új filter  akkor a limitstartot nullázni kell
		if ($input->get('newfilter')==1) $model->status->limitstart=0;
		if ($input->get('clrfilter')==1) $model->status->limitstart=0;
		$model->setState($this->componentName.'.'.$this->viewName);
		$items = $items = $model->getItems();
		$view = $this->getView($this->viewName, 'html');
		$view->set('items',$items);
		$view->set('total',$model->getTotal());
		$view->set('pagination', $model->getPagination());
		$view->setLayout('default');
		$this->browseButtons();
		$view->display();
		$this->browseButtons();
	}
	
	/**
	  * új felvitel képernyő
	  * fontos: a képernyőn MINDEN tábla mező legyen rajta (rejtett lehet),
	  * rejtett mezőkben legyen limit, limitstart, filter_str, filter_order, filter_order_Dir is
	  * hibaüzenet után $_GET['jform'] -ban form adatok is érkezhetnek
	  * @param string $msg  üzenet
	  * @param string $msgClass  üzent css class
	*/
	public function add($msg = '', $msgClass = 'info') {
		$input = JFactory::getApplication()->input;
		if ($this->accessControl('add','') == false) die(JText::_('ACCES_VIOLATION'));
		$document = JFactory::getDocument();
		$session = JFactory::getSession();
		$session->set('lastTask','add');	
		if ($msg != '')
			echo '<div class="'.$msgClass.'">'.$msg.'</div>';
		
		if (file_exists(JPATH_COMPONENT.'/models/forms/'.$this->formName.'.xml'))	
			$form = &JForm::getInstance('adminForm',            
                             JPATH_COMPONENT.'/models/forms/'.$this->formName.'.xml',
                             array('control' => 'jform')); 
		if (file_exists(JPATH_COMPONENT.'/views/'.$this->viewName.'/tmpl/form.js'))	{
			echo '<script type="text/javascript">
		    ';
			include JPATH_COMPONENT.'/views/'.$this->viewName.'/tmpl/form.js';
			echo '</script>
			';
		}

		$model = $this->getModel($this->modelName);
		$jform = $input->get('jform','','array');
		if ($jform == '')
		   $item = $model->getItem(0);
	    	else {
		  $item = new stdClass();	
		  foreach ($jform as $fn => $fv)
		    $item->$fn = $fv;
		}
		$form = $this->getForm();   
		$form->bind($item);
		$view = $this->getView($this->viewName, 'html');
		$view->set('item',$item);
		$view->set('form',$form);
		$view->setModel($model);
		$view->setLayout('form');
		$this->addButtons();
		$view->display();
		$this->addButtons();
	}
	
	/**
	  * módosítás képernyő
	  * fontos: a képernyőn MINDEN tábla mező legyen rajta (rejtett lehet),
	  * rejtett mezőkben legyen limit, limitstart, filter_str, filter_order, filter_order_Dir is
	  * az id paraméter érkezhet GET['id'] -ben, vagy $_GET['cid'] egy elemü tömbben
	  * hibaüzenet után $_GET['jform'] -ban form adatok is érkezhetnek
	  * @param string $msg  üzenet
	  * @param string $msgClass  üzent css class
	*/
	public function edit($msg = '', $msgClass = 'info') {
		$input = JFactory::getApplication()->input;
		$document = JFactory::getDocument();
		$session = JFactory::getSession();
		$session->set('lastTask','edit');	
		$usr = JFactory::getUser();
		if ($msg != '')
			echo '<div class="'.$msgClass.'">'.$msg.'</div>';
		$ids = array();
		
		if (file_exists(JPATH_COMPONENT.'/models/forms/'.$this->formName.'.xml'))	
			$form = &JForm::getInstance('adminForm',            
                             JPATH_COMPONENT.'/models/forms/'.$this->formName.'.xml',
                             array('control' => 'jform')); 
		if (file_exists(JPATH_COMPONENT.'/views/'.$this->viewName.'/tmpl/form.js'))	{
			echo '<script type="text/javascript">
		    ';
			include JPATH_COMPONENT.'/views/'.$this->viewName.'/tmpl/form.js';
			echo '</script>
			';
		}

		if ($input->get('id') != '')
		   $ids[0] = $input->get('id');
		else	
		   $ids = $input->get('cid',array(),'array');
		if (count($ids) == 1) {
			$model = $this->getModel($this->modelName);
			$jform = $input->get('jform','','array');
			if ($jform == '')
				$item = $model->getItem($ids[0]);
			else {
		      		$item = new stdClass();	
		      		foreach ($jform as $fn => $fv)
		        	$item->$fn = $fv;
			}
			if ($item == false) {
				$this->browse(JText::_($this->lngPre.'NOT_FOUND'),'error');
				return;	
			}	
			if ($this->accessControl('edit',$item) == false) die(JText::_('ACCES_VIOLATION'));
			$form = $this->getForm();
			$form->bind($item);
			$view = $this->getView($this->viewName, 'html');
			$view->set('form',$form);
			$view->set('item',$item);
			$view->setModel($model);
			$view->setLayout('form');
			$this->editButtons();
			$view->display();
			$this->editButtons();
		} else {
			$this->browse(JText::_($this->lngPre.'SELECT_PLEASE'), 'hibaUzenet');
		}
	}
	
	/**
	  * adat tárolás
	*/
	public function save() {
		$input = JFactory::getApplication()->input;
		$session = JFactory::getSession();
		if ($session->get('lastTask') == 'save') {
			// a user refrest nyomott a böngészőben save után!
			$this->browse('','');
			return;
		}
		$session->set('lastTask','save');	
		JSession::checkToken( 'post' ) or die( 'Invalid Token' );	
		$model = $this->getModel($this->modelName);

		$jform  = $input->get('jform', array(), 'array');
		$item = new stdClass();
		foreach ($jform as $fn => $fv) $item->$fn = $fv;

		if ($this->accessControl('save',$item) == false) die(JText::_('ACCES_VIOLATION'));
		$result = $model->save($item);  
		if ($result) {
			$this->browse(JText::_($this->lngPre.'SAVED'),'infoUzenet');
		} else {
			if ($item->id == 0) {
			  $this->add($model->getError(),'hibaUzenet');
			} else {
			  $input->set('id',$item->id);	
			  $this->edit($model->getError(),'hibaUzenet');
			}
		}
	}
		
	/**
	  * rekord tölés
	*/
	public function delete() {
		$input = JFactory::getApplication()->input;
		$session = JFactory::getSession();
		$session->set('lastTask','delete');	
		$session = JFactory::getSession();
		JSession::checkToken( 'get' ) or die( 'Invalid Token' );	
		$model = $this->getModel($this->modelName);
		$model->set('viewName',$this->viewName);
		$model->set('viewName',$this->viewName);
		$ids = $input->get('cid');
		if (count($ids) == 1) {
				$item = $model->getItem($ids[0]);
				if ($this->accessControl('delete',$item) == false) die(JText::_('ACCES_VIOLATION'));
				$result = $model->delete($ids);  
				if ($result) {
					$this->browse(JText::_($this->lngPre.'DELETED'),'infoUzenet');
				} else {
					$this->browse($model->getError(),'hibaUzenet');
				}
		} else {
			$this->browse(JText::_($this->lngPre.'SELECT_PLEASE'), 'hibaUzenet');
		}
	}

    /**
      * rekord tárolás és új felvitel
	*/  
	public function savenew() {
		$input = JFactory::getApplication()->input;
		$session = JFactory::getSession();
		if ($session->get('lastTask') == 'save') {
			// a user refrest nyomott a böngészőben save után!
			$this->browse('','');
			return;
		}
		$session->set('lastTask','save');	
		JSession::checkToken( 'post' ) or die( 'Invalid Token' );	
		$model = $this->getModel($this->modelName);
		$jform  = $input->post->get('jform', array(), 'array');
		$item = new stdClass();
		foreach ($jform as $fn => $fv) $item->$fn = $fv;
		if ($this->accessControl('save',$item) == false) die(JText::_('ACCES_VIOLATION'));
		$result = $model->save($item);  
		if ($result) {
			$this->editNewInit($jform);
			$input->post->set('jform', $jform, 'array');
			$this->add(JText::_($this->lngPre.'SAVED'),'infoUzenet');
		} else {
			if ($item->id == 0) {
			  $this->add($model->getError(),'hibaUzenet');
			} else {
			  $input->set('id',$item->id);	
			  $this->edit($model->getError(),'hibaUzenet');
			}
		}
	}
}
?>
