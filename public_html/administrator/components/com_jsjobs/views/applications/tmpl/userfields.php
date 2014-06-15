<?php 
/**
 * @Copyright Copyright (C) 2009-2011 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/views/applications/tmpl/userfieldss.php
 ^ 
 * Description: Template for user fields view
 ^ 
 * History:		NONE
 ^ 
 */

defined('_JEXEC') or die('Restricted access'); 
JRequest :: setVar('layout', 'userfields');
$_SESSION['cur_layout']='userfields';
$version = new JVersion;
$joomla = $version->getShortVersion();
$document = &JFactory::getDocument();
$document->addStyleSheet(JURI::root().'administrator/components/com_jsjobs/include/css/jsjobsadmin.css');

if(substr($joomla,0,3) != '1.5'){
	JHtml::_('behavior.tooltip');
	JHtml::_('behavior.multiselect');
}
?>
<table width="100%" border="0">
	<tr>
		<td align="left" width="175" valign="top">
			<table width="100%"><tr><td style="vertical-align:top;">
			<?php
			include_once('components/com_jsjobs/views/menu.php');
			?>
			</td>
			</tr></table>
		</td>
		<td width="100%" valign="top">
			<div id="jsjobs_info_heading"><?php echo JText::_('JS_USER_FIELDS'); ?></div>
			<form action="index.php?option=com_jsjobs" method="post" name="adminForm" id="adminForm">
				<table class="adminlist" cellpadding="1">
					<thead>
						<tr>
							<th width="2%" class="title">
								<?php echo JText::_( 'NUM' ); ?>
							</th>
							<th width="3%" class="title">
								<?php if(substr($joomla,0,3) < '3'){ ?>
									<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
								<?php }else{ ?>
									<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
								<?php } ?>
							</th>
							<th class="title">
								<?php echo JHTML::_('grid.sort',   'Field name', 'a.name', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
							</th>
							<th width="15%" class="title" >
								<?php echo JHTML::_('grid.sort',   'Field title', 'a.username', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
							</th>
							<th width="5%" class="title" nowrap="nowrap">
								<?php echo JHTML::_('grid.sort',   'Field type', 'a.block', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
							</th>
							<th width="15%" class="title">
								<?php echo JHTML::_('grid.sort',   'Required', 'groupname', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
							</th>
							<th width="1%" class="title" nowrap="nowrap">
								<?php echo JHTML::_('grid.sort',   'Readonly', 'a.id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
							</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<td colspan="10">
								<?php echo $this->pagination->getListFooter(); ?>
							</td>
						</tr>
					</tfoot>
					<tbody>
					<?php
						$k = 0;
						for ($i=0, $n=count( $this->items ); $i < $n; $i++)
						{
							$row 	=& $this->items[$i];
							$link 	= 'index.php?option=com_jsjobs&amp;view=user&amp;task=edit&amp;cid[]='. $row->id. '';

						?>
						<tr class="<?php echo "row$k"; ?>">
							<td>
								<?php echo $i+1+$this->pagination->limitstart;?>
							</td>
							<td>
								<?php echo JHTML::_('grid.id', $i, $row->id ); ?>
							</td>
							<td><a href="<?php echo $link; ?>"><?php echo $row->name; ?></a></td>
							<td><?php echo $row->title; ?></td>
							<td><?php echo $row->type; ?></td>
							<td><?php if($row->required==1) echo JText::_('JS_YES');else echo JText::_('JS_NO');  ?></td>
							<td><?php if($row->readonly==1) echo JText::_('JS_YES');else echo JText::_('JS_NO');  ?></td>
							
						</tr>
						<?php
							$k = 1 - $k;
							}
						?>

					</tbody>
				</table>

				<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
				<input type="hidden" name="task" value="view" />
				<input type="hidden" name="boxchecked" value="0" />
				<input type="hidden" name="filter_order" value="<?php if(isset($this->lists)) echo $this->lists['order']; ?>" />
				<input type="hidden" name="filter_order_Dir" value="<?php if(isset($this->lists)) echo $this->lists['order_Dir']; ?>" />
				<?php echo JHTML::_( 'form.token' ); ?>
			</form>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			
		</td>
	</tr>
	
</table>										
