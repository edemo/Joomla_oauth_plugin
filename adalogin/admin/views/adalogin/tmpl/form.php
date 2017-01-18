<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

// Set toolbar items for the page
$edit		= JRequest::getVar('edit', true);
$text = !$edit ? JText::_( 'New' ) : JText::_( 'Edit' );
JToolBarHelper::title(   JText::_( 'Adalogin' ).': <small><small>[ ' . $text.' ]</small></small>' );
JToolBarHelper::apply();
JToolBarHelper::save();
if (!$edit) {
	JToolBarHelper::cancel();
} else {
	// for existing items the button is renamed `close`
	JToolBarHelper::cancel( 'cancel', 'Close' );
}
?>

<script language="javascript" type="text/javascript">


Joomla.submitbutton = function(task)
{
	if (task == 'cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
		Joomla.submitform(task, document.getElementById('adminForm'));
	}
}

</script>

	 	<form method="post" action="index.php" id="adminForm" name="adminForm">
	 	<div class="col <?php if(version_compare(JVERSION,'3.0','lt')):  ?>width-60  <?php endif; ?>span8 form-horizontal fltlft">
		  <fieldset class="adminform">
			<legend><?php echo JText::_( 'Details' ); ?></legend>
				<div>
				  <label><?php echo JText::_('ADA_REDIRECT_URI'); ?></label>
				  <var><?php echo str_replace('http:','https:',JURI::root()).'components/com_adalogin/index.php'; ?></var>
				</div>	
				<p> </p>	
				<?php echo $this->form->getLabel('ADA_AUTH_URI'); ?>
				<?php echo $this->form->getInput('ADA_AUTH_URI');  ?>
				<?php echo $this->form->getLabel('ADA_USER_URI'); ?>
				<?php echo $this->form->getInput('ADA_USER_URI');  ?>
				<?php echo $this->form->getLabel('ADA_TOKEN_URI'); ?>
				<?php echo $this->form->getInput('ADA_TOKEN_URI');  ?>
				<?php echo $this->form->getLabel('appkey'); ?>
				<?php echo $this->form->getInput('appkey');  ?>
				<?php echo $this->form->getLabel('secret'); ?>
				<?php echo $this->form->getInput('secret');  ?>
          </fieldset>                      
        </div>
        <div class="col <?php if(version_compare(JVERSION,'3.0','lt')):  ?>width-30  <?php endif; ?>span2 fltrgt">
        </div>                   
		<input type="hidden" name="jform[id]" value="1" />
		<input type="hidden" name="option" value="com_adalogin" />
	    <input type="hidden" name="cid[]" value="<?php echo $this->item->id ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="view" value="adalogin" />
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
	<p>Az ADA rendszerből történő kijelentkezéshez a http://adaserver_domain/ada/v1/logout' url-t kell aktivizálni, egy láthatatlan iframe-be irányitva az outputot.</p>