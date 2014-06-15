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

JRequest :: setVar('layout', 'messages');
$document = &JFactory::getDocument();
$document->addStyleSheet(JURI::root().'administrator/components/com_jsjobs/include/css/jsjobsadmin.css');

$_SESSION['cur_layout']='messages';
$version = new JVersion;
$joomla = $version->getShortVersion();
if(substr($joomla,0,3) != '1.5'){
	JHtml::_('behavior.tooltip');
	JHtml::_('behavior.multiselect');
}


$status = array(
	'1' => JText::_('JS_APPROVED'),
	'-1' => JText::_('JS_REJECTED'));

?>
<script language=Javascript>
    function confirmdeletecompany(id,task){
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
		<td width="100%" valign="top" >
				<div id="jsjobs_info_heading"><?php echo JText::_('JS_MESSAGES'); ?></div>

			<form action="index.php" method="post" name="adminForm" id="adminForm">
			<table  width="100%" valign="top" >
				<tr>
					<td >
						<strong><?php echo JText::_( 'Filter' ); ?></strong>
					</td>
					<td nowrap="nowrap" >
						<?php echo JText::_( 'JS_USERS' ); ?> :
						<input type="text" name="message_username" id="message_username" value="<?php if(isset($this->lists['username'])) echo $this->lists['username'];?>" class="inputbox"  />
						<?php echo $this->lists['usertype'];?>
					</td>
					<td >
						<?php echo JText::_( 'JS_COMPANY' ); ?> :
						<input type="text" name="message_company" id="message_company" value="<?php if(isset($this->lists['company'])) echo $this->lists['company'];?>" class="inputbox"  />
					</td>
					<td >
						<?php echo JText::_( 'JS_JOB_TITLE' ); ?> :
						<input type="text" name="message_jobtitle" id="message_jobtitle" value="<?php if(isset($this->lists['jobtitle'])) echo $this->lists['jobtitle'];?>" class="inputbox"  />
					</td>
					<td >
						<?php echo JText::_( 'JS_SUBJECT' ); ?> :
						<input type="text" name="message_subject" id="message_subject" value="<?php if(isset($this->lists['subject'])) echo $this->lists['subject'];?>" class="inputbox"  />
					</td>
					<td nowrap="nowrap" >
						<?php echo JText::_( 'JS_CONFLICTED' ); ?> :
                                                <?php echo $this->lists['conflict'];?>
					</td>
					<td nowrap="nowrap">
						<button onclick="document.getElementById('message_username').value='';this.form.getElementById('message_usertype').value='';this.form.getElementById('message_company').value='';this.form.getElementById('message_jobtitle').value='';this.form.getElementById('message_subject').value='';this.form.getElementById('message_conflicted').value='';this.form.getElementById('searchcountry').value=0;this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
						<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
					</td>
				</tr>
			</table>
			<table class="adminlist">
				<thead>
					<tr>
						<th width="10">
							<?php if(substr($joomla,0,3) < '3'){ ?>
								<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
							<?php }else{ ?>
								<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
							<?php } ?>
						</th>
						<th><?php echo JText::_('JS_SUBJECT'); ?></th>
						<th><?php echo JText::_('JS_EMPLOYER_NAME'); ?></th>
						<th><?php echo JText::_('JS_COMPANY'); ?></th>
						<th><?php echo JText::_('JS_JOBSEEKER_NAME'); ?></th>
						<th><?php echo JText::_('JS_JOB_TITLE'); ?></th>
						<th><?php echo JText::_('JS_RESUME_TITLE'); ?></th>
						<th><?php echo JText::_('CREATED'); ?></th>
						<th><?php echo JText::_('JS_CONFLICTED'); ?></th>
						<th><?php echo JText::_('JS_STATUS'); ?></th>
					</tr>
				</thead>
			<?php
			jimport('joomla.filter.output');
			$k = 0;
			
				$companydeletetask 	= 'companyenforcedelete';
				$deleteimg 	= 'publish_x.png';
				$deletealt 	= JText::_( 'Delete' );
			
				for ($i=0, $n=count( $this->items ); $i < $n; $i++){
                                    $row =& $this->items[$i];
                                    $checked = JHTML::_('grid.id', $i, $row->id);
                                    $link = JFilterOutput::ampReplace('index.php?option='.$this->option.'&view=applications&layout=message_history&bd='.$row->jobid.'&rd='.$row->resumeid.'&sm=3');
                                    //$link = JFilterOutput::ampReplace('index.php?option='.$option.'&view=application&layout=formmessage&cid='.$row->id.'&sm=3');
                                    //$link = JFilterOutput::ampReplace('index.php?option='.$option.'&view=application&layout=formmessage&cid='.$message->id.'&sm=2');

                                    ?>
                                    <tr valign="top" class="<?php echo "row$k"; ?>">
					<td>
						<?php echo $checked; ?>
					</td>
					<td>
						<a href="<?php echo $link; ?>">
						<?php echo $row->subject; ?>
						</a>
					</td>
					<td style="text-align: center;">
						<?php echo $row->employername; ?>
					</td>
					<td style="text-align: center;">
						<?php echo $row->companyname; ?>
					</td>
					<td style="text-align: center;">
						<?php echo $row->jobseekername; ?>
					</td>
					<td style="text-align: center;">
						<?php echo $row->jobtitle; ?>
					</td>
					<td style="text-align: center;">
						<?php echo $row->application_title; ?>
					</td>
					<td style="text-align: center;">
						<?php echo  date( $this->config['date_format'],strtotime($row->created)); ?>
					</td>
					<td style="text-align: center;">
                                                <?php
                                                if($row->isconflict == 1){	?>
                                                                <img src="../components/com_jsjobs/images/tick.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'PUBLISH' ); ?>" />

                                                <?php }else{	?>
                                                                <img src="../components/com_jsjobs/images/publish_x.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'UNPUBLISH' ); ?>" /></a>
                                                <?php }	?>
					</td>
					<td style="text-align: center;">
                                                <?php
                                                if($row->status == 1){	?>
                                                        <a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','unpublishmessages')">
                                                                <img src="../components/com_jsjobs/images/tick.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'PUBLISH' ); ?>" /></a>
                                                <?php }else{	?>
                                                        <a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','publishmessages')">
                                                                <img src="../components/com_jsjobs/images/publish_x.png" width="16" height="16" border="0" alt="<?php echo JText::_( 'UNPUBLISH' ); ?>" /></a>
                                                <?php }	?>
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
			<input type="hidden" name="jobid" id="jobid" value="<?php echo $row->jobid; ?>" />
			<input type="hidden" name="resumeid" id="resumeid" value="<?php echo $row->resumeid; ?>" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="layout" value="messages" />
			</form>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			
		</td>
	</tr>
	
</table>				




