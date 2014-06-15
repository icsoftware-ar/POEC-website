<?php 
/**
 * @Copyright Copyright (C) 2010- ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
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

$status = array(
	'0' => JText::_('JS_PENDDING'),
	'1' => JText::_('JS_APPROVED'),
	'-1' => JText::_('JS_REJECTED'));

?>
<table width="100%">
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

			<form action="index.php?option=com_jsjobs" method="post" name="adminForm" id="adminForm">
				
				<table class="adminlist" cellpadding="1">
					<thead>
						<tr>
							<th width="2%" class="title">
								<?php echo JText::_( 'NUM' ); ?>
							</th>
							<th class="title"><?php echo JText::_('JS_NAME'); ?></th>
							<th  class="title" ><?php echo JText::_('JS_APPLICATION_TITLE'); ?></th>
							<th  class="title" ><?php echo JText::_('JS_CATEGORY'); ?></th>
							<th  class="title" ><?php echo JText::_('JS_CREATED'); ?></th>
							<th  class="title" ><?php echo JText::_('JS_STATUS'); ?></th>						</tr>
					</thead>
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
							<td><?php echo $row->first_name.' '.$row->last_name; ?></td>
							<td><?php echo $row->application_title; ?>	</td>
							<td><?php echo $row->cat_title; ?>	</td>
							<td align="center"><?php echo date($this->config['date_format'],strtotime($row->create_date)); ?></td>
							<td style="text-align: center;">
						<?php 
							if($row->status == 1) echo "<font color='green'>".$status[$row->status]."</font>";
							elseif($row->status == -1) echo "<font color='red'>".$status[$row->status]."</font>";
							else echo "<font color='blue'>".$status[$row->status]."</font>";
						?>
					</td>
						</tr>
						<?php
							$k = 1 - $k;
							}
						?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="10">
								<?php echo $this->pagination->getListFooter(); ?>
							</td>
						</tr>
					</tfoot>
				</table>

				<input type="hidden" name="option" value="com_jsjobs" />
				<input type="hidden" name="task" value="view" />
				<input type="hidden" name="boxchecked" value="0" />
				<input type="hidden" name="ruid" value="<?php echo $this->resumeuid; ?>" />
				<?php echo JHTML::_( 'form.token' ); ?>
			</form>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			
		</td>
	</tr>
	
</table>										
