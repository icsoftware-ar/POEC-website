<?php 
/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 + Created by:	Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/views/applications/tmpl/users.php
 ^ 
 * Description: Template for users view
 ^ 
 * History:		NONE
 ^ 
 */

defined('_JEXEC') or die('Restricted access'); 

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
			<div id="jsjobs_info_heading"><?php echo JText::_('JS_FIELDS_ORDERING'); ?></div>

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
							<th width="25%" class="title" >	<?php echo JHTML::_('grid.sort',   'Field Title', 'a.username', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
							<th width="5%" class="title" nowrap="nowrap"><?php echo JHTML::_('grid.sort',   'Section', 'a.block', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
							<th width="5%" class="title"><?php echo JHTML::_('grid.sort',   'User Published', 'groupname', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>	</th>
							<th width="5%" class="title"><?php echo JHTML::_('grid.sort',   'Visitor Published', 'groupname', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>	</th>
							<th width="10%" class="title" nowrap="nowrap"><?php echo JHTML::_('grid.sort',   'Ordering', 'a.id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<td colspan="10">
								<?php //echo $this->pagination->getListFooter(); ?>
							</td>
						</tr>
					</tfoot>
					<tbody>
					<?php
						$k = 0;
						for ($i=0, $n=count( $this->items ); $i < $n; $i++)
						{
							$row 	=& $this->items[$i];
							$row1 	=& $this->items[$i+1];
							$uptask 	= 'fieldorderingup';
							$upimg 	= 'uparrow.png';
							$downtask 	= 'fieldorderingdown';
							$downimg 	= 'downarrow.png';

							$pubtask 	= $row->published ? 'fieldunpublished' : 'fieldpublished';
							$pubimg 	= $row->published ? 'tick.png' : 'publish_x.png';
							$alt 	= $row->published ? JText::_( 'Published' ) : JText::_( 'Unpublished' );

							$visitorpubtask 	= $row->isvisitorpublished ? 'visitorfieldunpublished' : 'visitorfieldpublished';
							$visitorpubimg 	= $row->isvisitorpublished ? 'tick.png' : 'publish_x.png';
							$visitoralt 	= $row->isvisitorpublished ? JText::_( 'Published' ) : JText::_( 'Unpublished' );


							$checked = JHTML::_('grid.id', $i, $row->id);
							$link = JFilterOutput::ampReplace('index.php?option='.$this->option.'&task=edit&cid[]='.$row->id);

						?>
						<tr class="<?php echo "row$k"; ?>">
							<td>
								<?php echo $i+1+$this->pagination->limitstart;?>
							</td>
							<td align="center">
								<?php echo JHTML::_('grid.id', $i, $row->id ); ?>
							</td>
							<?php 	$sec = substr($row->field, 0,8); //get section_
							if ($sec == 'section_') {
								$newsection = 1; 
								$subsec = substr($row->field, 0,12);
								if ($subsec == 'section_sub_') {	?>
									<td colspan="2" align="center"><strong><?php echo $row->fieldtitle;  ?></strong></td>
								<?php } else { ?>
									<td colspan="2" align="center"><strong><font size="2"><?php echo $row->fieldtitle;  ?></font></strong></td>
								<?php } ?>
								
								<td align="center">
									<?php if ($row->cannotunpublish == 1) { ?>
										<img src="../components/com_jsjobs/images/<?php echo $pubimg;?>" width="16" height="16" border="0" alt="<?php echo JText::_( 'CAN_NOT_UNPUBLISHED' ); ?>" />
									<?php }else { ?>
									<a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $pubtask;?>')">
										<img src="../components/com_jsjobs/images/<?php echo $pubimg;?>" width="16" height="16" border="0" alt="<?php echo $alt; ?>" /></a>
									<?php } ?>
								</td>
								<td align="center">
									<?php if ($row->cannotunpublish == 1) { ?>
										<img src="../components/com_jsjobs/images/<?php echo $visitorpubimg;?>" width="16" height="16" border="0" alt="<?php echo JText::_( 'CAN_NOT_UNPUBLISHED' ); ?>" />
									<?php }else { ?>
									<a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $visitorpubtask;?>')">
										<img src="../components/com_jsjobs/images/<?php echo $visitorpubimg;?>" width="16" height="16" border="0" alt="<?php echo $visitoralt; ?>" /></a>
									<?php } ?>
								</td>
								<td></td>
							<?php } else{  ?>
							<!--	<td ><?php //echo $row->name; ?></td> -->
								<td><?php if ($row->fieldtitle) echo $row->fieldtitle; else echo $row->userfieldtitle; ?></td>
								<td><?php echo $row->section; ?></td>
								<td align="center">
									<?php if ($row->cannotunpublish == 1) { ?>
										<img src="../components/com_jsjobs/images/<?php echo $pubimg;?>" width="16" height="16" border="0" alt="<?php echo JText::_( 'CAN_NOT_UNPUBLISHED' ); ?>" />
									<?php }else { ?>
									<a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $pubtask;?>')">
										<img src="../components/com_jsjobs/images/<?php echo $pubimg;?>" width="16" height="16" border="0" alt="<?php echo $alt; ?>" /></a>
									<?php } ?>
								</td>
								<td align="center">
									<?php if ($row->cannotunpublish == 1) { ?>
										<img src="../components/com_jsjobs/images/<?php echo $visitorpubimg;?>" width="16" height="16" border="0" alt="<?php echo JText::_( 'CAN_NOT_UNPUBLISHED' ); ?>" />
									<?php }else { ?>
									<a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $visitorpubtask;?>')">
										<img src="../components/com_jsjobs/images/<?php echo $visitorpubimg;?>" width="16" height="16" border="0" alt="<?php echo $visitoralt; ?>" /></a>
									<?php } ?>
								</td>
								<td>
									<?php if ($i != 0 ) { 
											if ($newsection != 1) { ?>		
												<a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $downtask;?>')">
													<img src="../components/com_jsjobs/images/<?php echo $upimg;?>" width="16" height="16" border="0" alt="Order Up" /></a>
										<?php } else echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
										} else echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';?>	
									&nbsp;&nbsp;<?php echo $row->ordering; ?>&nbsp;&nbsp;
									<?php if ($i < $n-1) { 
											if ($row->section == $row1->section) { ?>
												<a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $uptask;?>')">
													<img src="../components/com_jsjobs/images/<?php echo $downimg;?>" width="16" height="16" border="0" alt="Order Down" /></a>
									<?php 	} 
										}	?>	
								</td>
							<?php $newsection = 0; 
							} ?>
						</tr>
						<?php
							$k = 1 - $k;
							}
						?>

					<tr>
						<td colspan="7">
							<?php echo $this->pagination->getListFooter(); ?>
						</td>
					</tr>
					</tbody>
				</table>

				<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
				<input type="hidden" name="task" value="view" />
				<input type="hidden" name="boxchecked" value="0" />
				<input type="hidden" name="layout"  value="fieldsordering" />
				<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
				<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
				<?php echo JHTML::_( 'form.token' ); ?>
			</form>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			
		</td>
	</tr>
	
</table>										
