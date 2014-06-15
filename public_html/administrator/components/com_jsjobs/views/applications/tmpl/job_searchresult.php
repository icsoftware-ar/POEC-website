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
$document = &JFactory::getDocument();
$document->addStyleSheet(JURI::root().'administrator/components/com_jsjobs/include/css/jsjobsadmin.css');
$version = new JVersion;
$joomla = $version->getShortVersion();
if(substr($joomla,0,3) != '1.5'){
	JHtml::_('behavior.tooltip');
	JHtml::_('behavior.multiselect');
}


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
							
							<th class="title"><?php echo JText::_('JS_TITLE'); ?></th>
							<th  class="title" ><?php echo JText::_('JS_CATEGORY'); ?></th>
							<th  class="title"><?php echo JText::_('JS_JOBTYPE'); ?></th>
							<th  class="title"><?php echo JText::_('JS_JOB_STATUS'); ?></th>
							<th  class="title"><?php echo JText::_('JS_COMPANY'); ?></th>
							<th  class="title"><?php echo JText::_('JS_CITY'); ?></th>
							<th  class="title"><?php echo JText::_('JS_DATE_POSTED'); ?></th>
						</tr>
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
						<td><?php if($this->listjobconfig['lj_title'] == '1') {?>  
						<a href="index.php?option=com_jsjobs&view=applications&layout=view_job&oi=<?php echo $row->id;?>"><?php echo $row->title;?></a>
						<?php } ?></td>
						<td><?php if($this->listjobconfig['lj_category'] == '1') echo $row->cat_title; ?></td>
						<td><?php if($this->listjobconfig['lj_jobtype'] == '1') echo $row->jobtypetitle; ?></td>
						<td><?php if($this->listjobconfig['lj_jobstatus'] == '1') echo $row->jobstatustitle; ?></td>
						<td> <a href="index.php?option=com_jsjobs&view=applications&layout=view_company&md=<?php echo $row->companyid;?>"><?php echo $row->companyname; ?></td>
						<td><?php if($this->listjobconfig['lj_country'] == '1')echo $row->city; ?></td>
						<td><?php if($this->listjobconfig['lj_created'] == '1') echo date($this->config['date_format'],strtotime($row->created)); ?></td>
						
							
							
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

				<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
				<input type="hidden" name="task" value="" />
				<input type="hidden" name="layout" value="job_searchresult" />
				<input type="hidden" name="boxchecked" value="0" />
				<?php echo JHTML::_( 'form.token' ); ?>
			</form>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			
		</td>
	</tr>
	
</table>										
