<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

  JToolBarHelper::title( JText::_( 'Adalogin' ), 'generic.png' );
  JToolBarHelper::addNew();
  JToolBarHelper::editList();
  JToolBarHelper::publishList();
  JToolBarHelper::unpublishList();  
  JToolBarHelper::deleteList();  
  JToolBarHelper::preferences('com_adalogin', '550');  
?>

<form action="index.php?option=com_adalogin&amp;view=adalogin" method="post" name="adminForm" id="adminForm">
	<table>
		<tr>
			<td align="left" width="100%">
				<div id="filter-bar" class="btn-toolbar">
					<div class="filter-search btn-group pull-left">
						<label class="element-invisible" for="filter_search"><?php echo JText::_( 'Filter' ); ?>:</label>
						<input type="text" name="search" id="search" value="<?php  echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
					</div>
					<div class="btn-group pull-left">
						<button class="btn" onclick="this.form.submit();"><?php if(version_compare(JVERSION,'3.0','lt')): echo JText::_( 'Go' ); else: ?><i class="icon-search"></i><?php endif; ?></button>
						<button type="button" class="btn" onclick="document.getElementById('search').value='';this.form.submit();"><?php if(version_compare(JVERSION,'3.0','lt')): echo JText::_( 'Reset' ); else: ?><i class="icon-remove"></i><?php endif; ?></button>
					</div>
				</div>					
			</td>
			<td nowrap="nowrap">
  				
			</td>
		</tr>		
	</table>
<div id="editcell">
	<table class="adminlist table table-striped">
		<thead>
			<tr>
				<th width="5">
					<?php echo JText::_( 'NUM' ); ?>
				</th>
				<th width="20">				
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
				</th>			

				<th class="title">
					<?php echo JHTML::_('grid.sort', 'ADA_AUTH_URI', 'a.ADA_AUTH_URI', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				</th>								<th class="title">
					<?php echo JHTML::_('grid.sort', 'ADA_USER_URI', 'a.ADA_USER_URI', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				</th>								<th class="title">
					<?php echo JHTML::_('grid.sort', 'ADA_TOKEN_URI', 'a.ADA_TOKEN_URI', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				</th>								<th class="title">
					<?php echo JHTML::_('grid.sort', 'Appkey', 'a.appkey', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				</th>								<th class="title">
					<?php echo JHTML::_('grid.sort', 'Secret', 'a.secret', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				</th>								<th class="title">
					<?php echo JHTML::_('grid.sort', 'Joomla_psw', 'a.joomla_psw', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				</th>								<th class="title">
					<?php echo JHTML::_('grid.sort', 'Id', 'a.id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				</th>				
			</tr>
		</thead>
		<tfoot>
		<tr>
			<td colspan="12">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
<?php
  $k = 0;
  if (count( $this->items ) > 0 ):
  
  for ($i=0, $n=count( $this->items ); $i < $n; $i++):
  
  	$row = &$this->items[$i];
 	$onclick = "";
  	
    if (JRequest::getVar('function', null)) {
    	$onclick= "onclick=\"window.parent.jSelectAdalogin_id('".$row->id."', '".$this->escape($row->ADA_AUTH_URI)."', '','id')\" ";
    }  	
    
 	$link = JRoute::_( 'index.php?option=com_adalogin&view=adalogin&task=edit&cid[]='. $row->id );
 	$row->id = $row->id; 	
 	$checked = JHTML::_('grid.id', $i, $row->id); 	
  	$published = JHTML::_('grid.published', $row, $i ); 	
 	
  ?>
	<tr class="<?php echo "row$k"; ?>">
		
		<td align="center"><?php echo $this->pagination->getRowOffset($i); ?>.</td>
        
        <td><?php echo $checked  ?></td>	

        <td>
							
							<a <?php echo $onclick; ?>href="<?php echo $link; ?>"><?php echo $row->ADA_AUTH_URI; ?></a>
 									
		</td>
        <td><?php echo $row->ADA_USER_URI ?></td>
        <td><?php echo $row->ADA_TOKEN_URI ?></td>
        <td><?php echo $row->appkey ?></td>
        <td><?php echo $row->secret ?></td>
        <td><?php echo $row->joomla_psw ?></td>
        <td><?php echo $row->id ?></td>		
	</tr>
<?php
  $k = 1 - $k;
  endfor;
  else:
  ?>
	<tr>
		<td colspan="12">
			<?php echo JText::_( 'There are no items present' ); ?>
		</td>
	</tr>
	<?php
  endif;
  ?>
</tbody>
</table>
</div>
<input type="hidden" name="option" value="com_adalogin" />
<input type="hidden" name="task" value="adalogin" />
<input type="hidden" name="view" value="adalogin" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>  	