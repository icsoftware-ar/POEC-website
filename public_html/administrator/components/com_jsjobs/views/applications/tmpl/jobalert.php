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
 * File Name:	admin-----/views/applications/tmpl/jobs.php
 ^ 
 * Description: Default template for jobs view
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');
JRequest :: setVar('layout', 'jobalert');
$_SESSION['cur_layout']='jobalert';
$version = new JVersion;
$joomla = $version->getShortVersion();
$document = &JFactory::getDocument();
$document->addStyleSheet(JURI::root().'administrator/components/com_jsjobs/include/css/jsjobsadmin.css');

if(substr($joomla,0,3) != '1.5'){
	JHtml::_('behavior.tooltip');
	JHtml::_('behavior.multiselect');
}

$status = array(
	'1' => JText::_('JS_APPROVED'),
	'-1' => JText::_('JS_REJECTED'));

?>
<script language=Javascript>
    function confirmdeletejobalert(id,task){
        if(confirm("<?php echo JText::_('JS_ARE_YOU_SURE'); ?>") == true){
            return listItemTask(id,task);
        }else return false;
    }
</script>

<table width="100%" >
	<tr>
		<td align="left" width="175"  valign="top">
			<table width="100%"><tr><td style="vertical-align:top;">
			<?php
			include_once('components/com_jsjobs/views/menu.php');
			?>
			</td>
			</tr></table>
		</td>
		<td width="100%" valign="top">
				<div id="jsjobs_info_heading"><?php echo JText::_('JS_JOB_ALERT'); ?></div>
			

			<form action="index.php" method="post" name="adminForm" id="adminForm">
			<table>
				<tr>
					<td width="100%">
						<strong><?php echo JText::_( 'Filter' ); ?></strong>
					</td>
					<td nowrap>
						<?php echo JText::_( 'JS_NAME' ); ?>:
						<input type="text" name="searchname" id="searchname" size="15" value="<?php if(isset($this->lists['searchname'])) echo $this->lists['searchname'];?>" class="text_area" onchange="document.adminForm.submit();" />
						<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
					&nbsp;</td>
					
					<td>
						<button onclick="document.getElementById('searchname').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
					</td>
				</tr>
			</table>
			<table class="adminlist">
				<thead>
					<tr>
						<th width="20">
							<?php if(substr($joomla,0,3) < '3'){ ?>
								<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
							<?php }else{ ?>
								<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
							<?php } ?>
						</th>
						<th class="title"><?php echo JText::_('JS_NAME'); ?></th>
						<th><?php echo JText::_('JS_CONTACT_EMAIL'); ?></th>
						<th><?php echo JText::_('JOB_CATEGORY'); ?></th>
						<th><?php echo JText::_('JS_SUBCATEGORY'); ?></th>
						<th><?php echo JText::_('JS_STATUS'); ?></th>
						<th><?php echo JText::_('CREATED'); ?></th>
						<th width="17"><?php echo JText::_('JS_UNSUBSCRIBE'); ?></th>
					</tr>
				</thead>
			<?php
			jimport('joomla.filter.output');
			$k = 0;
			
				$jobalertdeletetask 	= 'unsubscribeJobAlertSetting';
				$deleteimg 	= 'publish_x.png';
				$deletealt 	= JText::_( 'Delete' );
			
			
				for ($i=0, $n=count( $this->items ); $i < $n; $i++)
				{
				$row =& $this->items[$i];
				$checked = JHTML::_('grid.id', $i, $row->id);
				$link = JFilterOutput::ampReplace('index.php?option='.$this->option.'&task=editjobalert&cid[]='.$row->id);
				?>
				<tr valign="top" class="<?php echo "row$k"; ?>">
					<td>
						<?php echo $checked; ?>
					</td>
					<td style="text-align: center;">
						<a href="<?php echo $link; ?>">
						<?php echo $row->name; ?></a>
					</td>
					<td style="text-align: center;">
						<?php 
						echo $row->contactemail;
						?>
					</td>
					<td style="text-align: center;">
						<?php echo $row->cat_title; ?>
					</td>
					<td style="text-align: center;">
						<?php echo $row->title; ?>
					</td>
					<td style="text-align: center;">
						<?php 
							if($row->status == 1) echo "<font color='green'>".$status[$row->status]."</font>";
							else echo "<font color='red'>".$status[$row->status]."</font>";
						?>
					</td>
					<td style="text-align: center;">
						<?php echo  date( $this->config['date_format'],strtotime($row->created)); ?>
					</td>
					<td style="text-align: center;">
							<a href="javascript:void(0);" onclick=" return confirmdeletejobalert('cb<?php echo $i;?>','<?php echo $jobalertdeletetask;?>');">
							<img src="../components/com_jsjobs/images/<?php echo $deleteimg;?>" width="16" height="16" border="0" alt="<?php echo $deletealt; ?>" /></a>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>
			<tr>
				<td colspan="10">
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
