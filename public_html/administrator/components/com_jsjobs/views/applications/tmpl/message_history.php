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
$document = &JFactory::getDocument();
$document->addStyleSheet(JURI::root().'administrator/components/com_jsjobs/include/css/jsjobsadmin.css');

JRequest :: setVar('layout', 'message_history');
$_SESSION['cur_layout']='message_history';
$version = new JVersion;
$joomla = $version->getShortVersion();
if(substr($joomla,0,3) != '1.5'){
	JHtml::_('behavior.tooltip');
	JHtml::_('behavior.multiselect');
}

?>

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
                    <div style="float: left"  ><strong><?php echo JText::_('JS_JOB'); ?>:</strong> <?php echo $this->summary->title;?>
                        <strong><?php echo JText::_('JS_JOB_SEEKER'); ?>: </strong>
                        <?php echo $this->summary->first_name; if ($this->summary->middle_name) echo ' '.$this->summary->middle_name; echo ' '.$this->summary->last_name; ?>
                        &nbsp;<strong><?php echo JText::_('JS_RESUME'); ?>: </strong><?php echo $this->summary->application_title; ?>
                    </div>
                    <div style="clear: left"> </div>
					<form action="index.php" method="post" name="adminForm" id="adminForm">
                       <?php $trclass = array("row0", "row1");
                        $isodd = 1; ?>

                    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
                        
			<?php
                        $i = 0;
                       

			$k = 0;
				for ($i=0, $n=count( $this->messages ); $i < $n; $i++)
				{

                                $isodd = 1 - $isodd;
				$message =& $this->messages[$i];
				$link = JFilterOutput::ampReplace('index.php?option='.$this->option.'&view=application&layout=formmessage&cid='.$message->id.'&sm=2');
                                //$link = JFilterOutput::ampReplace('index.php?option='.$option.'&view=application&layout=formmessage&bd='.$message->jobid.'&rd='.$message->resumeid);
				?>
                                    <tr class="<?php echo $trclass[$isodd]; ?>"> <td>
                                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                            <tr  >
                                                <td colspan="3" style="text-align: center" >
                                                    <strong style="text-decoration: underline"  >
                                                    <?php if($message->sendby  == $message->employerid) echo JText::_('JS_EMPLOYER_SEND');
                                                        elseif($message->sendby == $message->jobseekerid) echo JText::_('JS_JOBSEEKER_SEND');
                                                    ?>
                                                    </strong>
                                                </td>
                                        </tr>
					<tr>
                                            <td width="15%">&nbsp;<strong><?php echo JText::_('JS_SUBJECT'); ?>	</strong></td>
                                                <td width="75%"><?php echo $message->subject;?></td>
                                                <td width="5%" ><a href="<?php echo $link; ?>"><?php echo  JText::_('EDIT') ?></a></td>
					</tr>
					<tr>
						<td >&nbsp;<strong><?php echo JText::_('JS_MESSAGE'); ?>	</strong></td>
						<td><?php echo $message->message; ?></td>
                                                <td></td>

					</tr>
					<tr>
						<td >&nbsp;<strong><?php echo JText::_('JS_CREATED'); ?>	</strong></td>
						<td>
							<?php $created=date($this->config['date_format'].' H:i:s',strtotime($message->created)); echo $created;?>
							</td>
                                                <td></td>

					</tr>
                                        </table>
                                   </td> </tr>
                          <?php
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
			<input type="hidden" name="bd" id="jobid" value="<?php echo $this->bd; ?>" />
			<input type="hidden" name="rd" id="resumeid" value="<?php echo $this->resumeid; ?>" />
			<input type="hidden" name="boxchecked" value="0" />
			</form>

		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			
		</td>
	</tr>

</table>				





















