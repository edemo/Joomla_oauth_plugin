<?php

// No direct access
defined('_JEXEC') or die;

class JElementAdalogin extends JElement
{
	/**
	 * Element name
	 *
	 * @var		string
	 */
	var	$_name = 'Adalogin';

	function fetchElement($name, $value, &$node, $control_name)
	{
		JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_adalogin/tables');
		$app		= JFactory::getApplication();
		$db			= JFactory::getDbo();
		$doc		= JFactory::getDocument();
		$template	= $app->getTemplate();
		$fieldName	= $control_name.'['.$name.']';
		$item = JTable::getInstance('adalogin', 'Table');
		if ($value) {
			$item->load($value);
		} else {
			$item->title = JText::_('Select a Adalogin');
		}

		$js = "
		function jSelectAdalogin_".$name."(id, title, catid, object) {
			document.getElementById(object + '_id').value = id;
			document.getElementById(object + '_name').value = title;
			SqueezeBox.close();
		}";
		$doc->addScriptDeclaration($js);

		$link = 'index.php?option=com_adalogin&amp;view=adalogin&amp;task=element&amp;tmpl=component&amp;function=jSelectAdalogin_'.$name;

		JHtml::_('behavior.modal', 'a.modal');
		$html = "\n".'<div class="fltlft"><input type="text" id="'.$name.'_name" value="'.htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8').'" disabled="disabled" /></div>';

		$html .= '<div class="button2-left"><div class="blank"><a class="modal" title="'.JText::_('Select a Adalogin').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 650, y: 375}}">'.JText::_('JSELECT').'</a></div></div>'."\n";
		$html .= "\n".'<input type="hidden" id="'.$name.'_id" name="'.$fieldName.'" value="'.(int)$value.'" />';

		return $html;
	}
}