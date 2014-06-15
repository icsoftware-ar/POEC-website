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
 * File Name:	admin-----/views/applications/tmpl/jobstatus.php
 ^ 
 * Description: Default template for job status
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');
JRequest :: setVar('layout', 'goldresumesqueue');
$_SESSION['cur_layout']='goldresumesqueue';
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
			<form action="index.php" method="post" name="adminForm" id="adminForm">
			
			<table>
				<tr>
					<td width="100%">
						<strong><?php echo JText::_( 'Filter' ); ?></strong>
					</td>
					<td nowrap>
						<?php echo JText::_( 'JS_TITLE' ); ?>:
						<input type="text" name="searchtitle" id="searchtitle" size="15" value="<?php if(isset($this->lists['searchtitle'])) echo $this->lists['searchtitle'];?>" class="text_area" onchange="document.adminForm.submit();" />
					&nbsp;</td>
					<td nowrap>
						<?php echo JText::_( 'JS_NAME' ); ?>:
						<input type="text" name="searchname" id="searchname" size="15" value="<?php if(isset($this->lists['searchname'])) echo $this->lists['searchname'];?>" class="text_area" onchange="document.adminForm.submit();" />
					&nbsp;</td>
					<td nowrap >
						<?php echo JText::_( 'JS_JOBSEEKER_PACKAGE' ); ?>:
						<input type="text" name="searchjobseekerpackage" id="searchjobseekerpackage" size="15" value="<?php if(isset($this->lists['searchjobseekerpackage'])) echo $this->lists['searchjobseekerpackage'];?>" class="text_area" onchange="document.adminForm.submit();" />
						<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
					&nbsp;</td>
					<td>
						<button onclick="document.getElementById('searchtitle').value='';document.getElementById('searchname').value='';document.getElementById('searchjobseekerpackage').value='';this.form.getElementById('searchjobtype').value='';this.form.getElementById('searchjobsalaryrange').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
					</td>
				</tr>
			</table>
			<table class="adminlist" border="0">
				<thead>
					<tr>
						<th width="20">
							<?php if(substr($joomla,0,3) < '3'){ ?>
								<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
							<?php }else{ ?>
								<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
							<?php } ?>
						</th>
						<th   class="title"><?php echo JText::_('JS_TITLE'); ?></th>
						<th><?php echo JText::_('JS_NAME'); ?></th>
						<th><?php echo JText::_('JS_EMPLOYER_PACKAGE'); ?></th>
						<th><?php echo JText::_('CREATED'); ?></th>
						<th><?php echo JText::_('JS_ACTION'); ?></th>
					</tr>
				</thead>
			<?php
			jimport('joomla.filter.output');
			$k = 0;
				$approvetask 	= 'goldresumeapprove';
				$approveimg 	= 'tick.png';
				$rejecttask 	= 'goldresumereject';
				$rejectimg 	= 'publish_x.png';
				$approvealt 	= JText::_( 'Approve' );
				$rejectalt 	= JText::_( 'Reject' );
				for ($i=0, $n=count( $this->items ); $i < $n; $i++)
				{
				$row =& $this->items[$i];
				$checked = JHTML::_('grid.id', $i, $row->id);
				$link = JFilterOutput::ampReplace('index.php?option='.$this->option.'&task=edit&cid[]='.$row->id);
				?>
				<tr valign="top" class="<?php echo "row$k"; ?>">
					<td>
						<?php echo $checked; ?>
					</td>
					<td>
						<?php echo $row->application_title; ?>
					</td>
					<td>
						<?php 
						echo $row->first_name . ' ' . $row->last_name;
						?>
					</td>
					<td style="text-align: center;">
						<?php echo $row->packagetitle; ?>
					</td>
					<td style="text-align: center;">
						<?php echo  date( $this->config['date_format'],strtotime($row->create_date)); ?>
					</td>
					<td style="text-align: center;">
							<a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $approvetask;?>')">
								<img src="../components/com_jsjobs/images/<?php echo $approveimg;?>" width="16" height="16" border="0" alt="<?php echo $approvealt; ?>" /></a>
							&nbsp;&nbsp; - &nbsp;&nbsp
							<a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $rejecttask;?>')">
								<img src="../components/com_jsjobs/images/<?php echo $rejectimg;?>" width="16" height="16" border="0" alt="<?php echo $rejectalt; ?>" /></a>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>
			<tr>
				<td colspan="9">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
			</table>
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			</form>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			
		</td>
	</tr>
	
</table>							
