<?php
/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 + Created by:          Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jan 23, 2011
 ^
 + Project: 		JS Jobs
 * File Name:	views/employer/tmpl/myjobs.php
 ^ 
 * Description: template view for my jobs
 ^ 
 * History:		NONE
 ^ 
 */
 
 defined('_JEXEC') or die('Restricted access');
 
 jimport('joomla.application.component.model');
 global $mainframe;
 $document =& JFactory::getDocument();
 $document->addStyleSheet('components/com_jsjobs/themes/'.$this->config['theme']);
   
?>

<?php if ($this->config['offline'] == '1'){ ?>
	<div id="toppanel">
		<div id="tp_header" <?php if($this->config['topimage'] == 0) echo 'style="background:none;"';?>>
			<span id="tp_title"><?php echo $this->config['title'];?></span>
		</div>
	</div>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<div id="message"><b><?php echo $this->config['offline_text']; ?></b></div>
	</div>
<?php }else{ ?>
	<div id="toppanel">
		<div id="tp_header" <?php if($this->config['topimage'] == 0) echo 'style="background:none;"';?>>
			<span id="tp_title"><?php echo $this->config['title'];?></span>
			<span id="tp_curloc">
				<?php if ($this->config['cur_location'] == 1) {
					if($this->vm == 1){ // job_appliedapplications
						echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=alljobsappliedapplications&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_APPLIED_RESUME'); ?></a> > <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=job_appliedapplications&bd=<?php echo $this->bd; ?>&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_JOB_APPLIED_APPLICATIONS'); ?></a> > <?php echo JText::_('JS_SEND_MESSAGE');
					}elseif($this->vm == 2){ // job_job messages
						echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=empmessages&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_MESSAGES'); ?></a> > <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=job_messages&bd=<?php echo $this->bd; ?>&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_JOB_MESSAGES'); ?></a> > <?php echo JText::_('JS_SEND_MESSAGE');
					}elseif($this->vm == 3){ // js messages
						echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jsmessages&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_MESSAGES'); ?></a> > <?php echo JText::_('JS_SEND_MESSAGE');
					}else{
						echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_SEND_MESSAGE');
					}
				} ?>
			</span>
		</div>
		<div id="tp_links">
			<?php 
			if (sizeof($this->jobseekerlinks) != 0){
				foreach($this->jobseekerlinks as $lnk)	{ ?>
					<a class="<?php if($lnk[2] == 1)echo 'first'; elseif($lnk[2] == -1)echo 'last';  ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
				<?php }
			}
			$cutomlink = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myfolders&Itemid='.$this->Itemid;
			$cutomlinktext = JText::_('JS_MY_FOLDER');
			$count = 0;
			if (sizeof($this->employerlinks) != 0){
				foreach($this->employerlinks as $lnk)	{
					if ($count == 1) { ?>
						<a href="<?php echo $cutomlink; ?>"> <?php echo $cutomlinktext; ?></a>
					<?php }	?>
					<a class="<?php if($lnk[2] == 1)echo 'first'; elseif($lnk[2] == -1)echo 'last';  ?>" href="<?php echo $lnk[0]; ?>"> <?php echo $lnk[1]; ?></a>
				<?php $count++;
				}
			}
			?>
		</div>
		<div id="tp_heading">
			<span id="tp_headingtext">
				<span id="tp_headingtext_left"></span>
				<span id="tp_headingtext_center"><?php echo JText::_('JS_SEND_MESSAGE');  ?></span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>
<?php
if ($this->canadd == 1) { // employer

$trclass = array($this->theme['odd'], $this->theme['even']);
?>

<form action="index.php" method="post" name="adminForm">
	<table cellpadding="0" cellspacing="0" border="0" width="100%" >
			<tr><td colspan="3" height="5"></td></tr>
            <tr id="mc_field_row" class="<?php echo $trclass[1]; ?>">
                    <td >&nbsp;<strong><?php echo JText::_('JS_JOB'); ?>:</strong> <?php if(isset($this->summary)) echo $this->summary->title;?></td>
                    <td >&nbsp;<strong><?php echo JText::_('JS_JOB_SEEKER'); ?>: </strong>
                    <?php if(isset($this->summary->first_name)) echo $this->summary->first_name; if (isset($this->summary->middle_name)) echo ' '.$this->summary->middle_name; if (isset($this->summary->last_name)) echo ' '.$this->summary->last_name; ?></td>
                    <td >&nbsp;<strong><?php echo JText::_('JS_RESUME'); ?>: </strong><?php if(isset($this->summary)) echo $this->summary->application_title; ?></td>
            </tr>
            <tr><td height="15" colspan="3"></td></tr>
            <tr>
                <td colspan="3" align="center"><label id="titlemsg" for="title"><?php echo JText::_('JS_SUBJECT'); ?></label>&nbsp;<font color="red">*</font>
                <input class="inputbox required" type="text" name="subject" id="subject" size="40" maxlength="255" value="<?php if(isset($this->job)) echo $this->job->subject; ?>" />
                </td>
            </tr>
            <tr>
                    <td colspan="3" valign="top" align="center"><label id="messagemsg" for="message"><strong><?php echo JText::_('JS_MESSAGE'); ?></strong></label>&nbsp;<font color="red">*</font></td>
            </tr>
            <tr>
                    <td colspan="3" align="center">
                    <?php
                            $editor =& JFactory::getEditor();
                            if(isset($this->job))
                                    echo $editor->display('message', $this->job->message, '100%', '100%', '60', '20', false);
                            else
                                    echo $editor->display('message', '', '100%', '100%', '60', '20', false);

                    ?>
                    </td>
            </tr>
			<tr>
				<td colspan="3" align="center">
				<input id="button" class="button" type="submit" name="submit" onClick="return myValidate();" value="<?php echo JText::_('JS_SEND'); ?>" />
				</td>
			</tr>
            
        </table>
                        <?php if(isset($this->company)) {
                                if (($this->company->created=='0000-00-00 00:00:00') || ($this->company->created==''))
                                        $curdate = date('Y-m-d H:i:s');
                                else
                                        $curdate = $this->company->created;
                        }else{
                                $uid = $this->uid;
                                $curdate = date('Y-m-d H:i:s');
                        }
			?>
        <input type="hidden" name="created" value="<?php echo $curdate; ?>" />
		<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
        <input type="hidden" name="task" value="savemessage" />
        <input type="hidden" id="id" name="id" value="" />
        <input type="hidden" id="employerid" name="employerid" value="<?php if(isset($this->summary)) echo $this->summary->employerid; ?>" />
        <input type="hidden" id="jobseekerid" name="jobseekerid" value="<?php if(isset($this->summary)) echo $this->summary->jobseekerid; ?>" />
        <input type="hidden" id="jobid" name="jobid" value="<?php if(isset($this->summary)) echo $this->summary->jobid; ?>" />
        <input type="hidden" id="resumeid" name="resumeid" value="<?php if(isset($this->summary)) echo $this->summary->resumeid; ?>" />
       <input type="hidden" name="vm" value="<?php echo $this->vm; ?>" />
        <input type="hidden" name="boxchecked" value="0" />
    </form>

            <table cellpadding="0" cellspacing="0" border="0" width="100%" >
				<tr><td colspan="3" height="5"></td></tr>
                <tr id="mc_title_row" height="16"><td colspan="3" class="sectionheadline" align="center">
					<span id="sectionheadline_text">
					<span id="sectionheadline_left"></span>
                        <?php echo JText::_('JS_MESSAGE_HISTORY'); ?>
					<span id="sectionheadline_right"></span>
					</span>
                </td></tr>
                <tr><td colspan="3" height="3"></td></tr>
        	<?php if ($this->totalresults != 0) { ?>
		
		<?php 
		$tdclass = array($this->theme['odd'], $this->theme['even']);
		$isodd =1;
		if ( isset($this->messages) ){?>
                        <tr id="mc_field_row" class="<?php echo $trclass[1]; ?>">
                                <td width="33%">&nbsp;<strong><?php echo JText::_('JS_JOB'); ?>: </strong><?php echo $this->messages[0]->title;?></td>
                                <td width="33%" >&nbsp;<strong><?php echo JText::_('JS_JOB_SEEKER'); ?>: </strong>
                                <?php echo $this->messages[0]->first_name; if ($this->messages[0]->middle_name) echo ' '.$this->messages[0]->middle_name; echo ' '.$this->messages[0]->last_name; ?></td>
                                <td width="33%" >&nbsp;<strong><?php echo JText::_('JS_RESUME'); ?>: </strong><?php echo $this->messages[0]->application_title; ?></td>

                        </tr>
		<?php foreach($this->messages as $message)	{
			$isodd = 1 - $isodd; ?>
			<tr id="mc_field_row" colspan="3" class="<?php echo $tdclass[$isodd]; ?>"> <td colspan="5">
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<tr id="msg_sender_name">
						<td colspan="2" align="center"><span id="msg_sender_name">
                                                        <?php if ($this->uid == $message->sendby) echo JText::_('JS_YOU_SENT');
                                                              elseif($this->uid == $message->employerid) echo JText::_('JS_JOBSEEKER_SENT');
                                                              elseif($this->uid == $message->jobseekerid) echo JText::_('JS_EMPLOYER_SENT');
                                                        ?>
                                                    </span><hr></td>
					</tr>
					<tr>
						<td width="15%">&nbsp;<strong><?php echo JText::_('JS_SUBJECT'); ?>	</strong></td>
						<td><?php echo $message->subject;?></td>
					</tr>
					<tr>
						<td >&nbsp;<strong><?php echo JText::_('JS_MESSAGE'); ?>	</strong></td>
						<td><?php echo $message->message; ?></td>
						
					</tr>
					<tr>
						<td >&nbsp;<strong><?php echo JText::_('JS_CREATED'); ?>	</strong></td>
						<td>
							<?php $created=date($this->config['date_format'].' H:i:s',strtotime($message->created)); echo $created;?>
						</td>

					</tr>
					<tr><td height="3"></td></tr>
				</table>	
			</td></tr>
		<?php 
		}
		}
            }else{ // no result found in this category
                    echo '<tr><td>'.JText::_('JS_MESSAGE_HISTORY_NOT_FOUND').'</td></tr>';
            }
                ?>
	</table>

<form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=send_message&bd='.$this->bd.'&rd='.$this->rd.'&vm='.$this->vm.'&Itemid='.$this->Itemid); ?>" method="post">
	<div id="jl_pagination">
		<div id="jl_pagination_pageslink">
			<?php echo $this->pagination->getPagesLinks(); ?>
		</div>
		<div id="jl_pagination_box">
			<?php	
				echo JText::_('JS_DISPLAY_#');
				echo $this->pagination->getLimitBox();
			?>
		</div>
		<div id="jl_pagination_counter">
			<?php echo $this->pagination->getResultsCounter(); ?>
		</div>
	</div>
</form>	
<?php

} else{ // not allowed job posting ?>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<div id="message"><b><?php echo JText::_('JS_YOU_DONOT_HAVE_THIS_FEATURE');?></b></div>
	</div>
<?php

}	
}//ol
?>	
<div id="jsjobs_footer"><?php echo eval(base64_decode('aWYoJHRoaXMtPmNvbmZpZ1snZnJfY3JfdHhzaCddKSB7DQplY2hvIA0KJzx0YWJsZSB3aWR0aD0iMTAwJSIgc3R5bGU9InRhYmxlLWxheW91dDpmaXhlZDsiPg0KPHRyPjx0ZCBoZWlnaHQ9IjE1Ij48L3RkPjwvdHI+DQo8dHI+PHRkIHN0eWxlPSJ2ZXJ0aWNhbC1hbGlnbjp0b3A7IiBhbGlnbj0iY2VudGVyIj4NCjxhIGNsYXNzPSJpbWciIHRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIj48aW1nIHNyYz0iaHR0cDovL3d3dy5qb29tc2t5LmNvbS9sb2dvL2pzam9ic2NybG9nby5wbmciPjwvYT4NCjxicj4NCkNvcHlyaWdodCAmY29weTsgMjAwOCAtICcuZGF0ZSgnWScpLicsDQo8c3BhbiBpZD0idGhlbWVhbmNob3IiPiA8YSBjbGFzcz0iYW5jaG9yInRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSI+QnVydWogU29sdXRpb25zIDwvYT48L3NwYW4+PC90ZD48L3RyPg0KPC90YWJsZT4nOw0KfQ=='));?></div>
