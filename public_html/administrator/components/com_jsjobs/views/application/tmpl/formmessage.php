<?php
/**
 * @Copyright Copyright (C) 2009-2011 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Feb 09, 2011
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/views/application/tmpl/formmessage.php
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access'); 

$editor = &JFactory::getEditor();
JHTML::_('behavior.calendar');
JHTML::_('behavior.formvalidation');  
$document = &JFactory::getDocument();
JRequest :: setVar('layout', 'formmessage');
$_SESSION['cur_layout']='formmessage';
$version = new JVersion;
$joomla = $version->getShortVersion();
$jversion = substr($joomla,0,3);


?>
<script language="javascript">
// for joomla 1.6
Joomla.submitbutton = function(task){
        if (task == ''){
                return false;
        }else{
                if (task == 'save'){
                    returnvalue = validate_form(document.adminForm);
                }else returnvalue  = true;
                if (returnvalue){
                        Joomla.submitform(task);
                        return true;
                }else return false;
        }
}
// for joomla 1.5
function submitbutton(pressbutton) {
	if (pressbutton) {
		document.adminForm.task.value=pressbutton;
	}
	if(pressbutton == 'save'){
		returnvalue = validate_form(document.adminForm);
	}else returnvalue  = true;
	
	if (returnvalue == true){
		try {
			  document.adminForm.onsubmit();
	        }
		catch(e){}
		document.adminForm.submit();
	}
}

function validate_form(f)
{
        if (document.formvalidator.isValid(f)) {
                f.check.value='<?php if(($jversion == '1.5') || ($jversion == '2.5')) echo JUtility::getToken(); else echo  JSession::getFormToken(); ?>';//send token
        }
        else {
                alert('<?php echo JText::_( 'JS_SOME_VALUES_ARE_NOT_ACCEPTABLE_PLEASE_RETRY');?>');
				return false;
        }
		return true;
}
</script>

<table width="100%" >
	<tr>
		<td align="left" width="175"  valign="top">
			<table width="100%" ><tr><td style="vertical-align:top;">
			<?php
			include_once('components/com_jsjobs/views/menu.php');
			?>
			</td>
			</tr></table>
		</td>
		<td width="100%" valign="top" align="left">
<form action="index.php" method="POST" name="adminForm" id="adminForm" >
    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
            <tr class="row1">
                <?php if(isset($this->summary)){ ?>
                    <td ><strong><?php echo JText::_('JS_JOB'); ?>:</strong> <?php echo $this->summary->title;?>
                    <strong><?php echo JText::_('JS_JOB_SEEKER'); ?>: 
                    <?php echo $this->summary->first_name; if ($this->summary->middle_name) echo ' '.$this->summary->middle_name; echo ' '.$this->summary->last_name; ?>
                    </strong>
                    <strong><?php echo JText::_('JS_RESUME'); ?>: </strong><?php echo $this->summary->application_title; ?>
                    </td>
                <?php } else {}?>
            </tr>
            <tr  class="row0">
                <td  align="center" width="100%"><label id="namemsg" for="name"><strong><?php echo JText::_('JS_SUBJECT'); ?></strong></label>&nbsp;<font color="red">*</font>
                <input class="inputbox required" type="text" name="subject" id="name" size="40" maxlength="255" value="<?php if(isset($this->message)) echo $this->message->subject; ?>" />
                </td>
            </tr>
            <tr  class="row1">
                <td colspan="2" valign="top" align="center"><label id="messagemsg" for="message"><strong><?php echo JText::_('JS_MESSAGE'); ?></strong></label>&nbsp;<font color="red">*</font></td>
            </tr>
            <tr class="row0">
                <td align="center" >
                <?php
                $editor =& JFactory::getEditor();
                if(isset($this->message))
                echo $editor->display('message', $this->message->message, '550', '300', '60', '20', false);
                else
                echo $editor->display('message', '', '550', '300', '60', '20', false);
                ?>
                </td>
            </tr>
			<?php if(isset($this->message)){ ?>
				<tr class="row1">
					<td align="center"><label id="statusmsg" for="status"><strong><?php echo JText::_('JS_STATUS'); ?></strong> </label>
						<?php   echo  $this->lists['status']  ?>
						</td>
				</tr>
			<?php }else echo '<input type="hidden" name="status" value="1" />' ;?>
            <tr>
		<td  align="center">
		<input type="submit" class="button" name="submit_app" onclick="return validate_form(document.adminForm)" value="<?php echo JText::_('JS_SAVE'); ?>" />
		</td>
	</tr>

    </table>
                        <?php if(isset($this->message)) {
                                if (($this->message->created=='0000-00-00 00:00:00') || ($this->message->created==''))
                                        $curdate = date('Y-m-d H:i:s');
                                else
                                        $curdate = $this->message->created;
                        }else{
                                $uid = $this->uid;
                                $curdate = date('Y-m-d H:i:s');
                        }
			?>
                        <input type="hidden" name="created" value="<?php echo $curdate; ?>" />
						<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                        <input type="hidden" name="id" value="<?php if(isset($this->message)) echo $this->message->id; ?>" />
                        <input type="hidden" name="sendby" value="<?php if(isset($this->message)) echo $this->message->sendby; ?>" />
						<input type="hidden" name="task" value="savemessage" />
                        <input type="hidden" name="boxchecked" value="0" />
						<?php if(isset($this->summary)){ ?>
							<input type="hidden" id="employerid" name="employerid" value="<?php if(isset($this->summary)) echo $this->summary->employerid; ?>" />
							<input type="hidden" id="jobseekerid" name="jobseekerid" value="<?php if(isset($this->summary)) echo $this->summary->jobseekerid; ?>" />
							<input type="hidden" id="jobid" name="jobid" value="<?php if(isset($this->summary)) echo $this->summary->jobid; ?>" />
							<input type="hidden" id="resumeid" name="resumeid" value="<?php if(isset($this->summary)) echo $this->summary->resumeid; ?>" />
						<?php }else {if(isset($this->message)) ?>
							<input type="hidden" name="jobid" id="jobid" value="<?php echo $this->message->jobid; ?>" />
							<input type="hidden" name="resumeid" id="resumeid" value="<?php echo $this->message->resumeid; ?>" />
							<input type="hidden" id="employerid" name="employerid" value="<?php  echo $this->message->employerid; ?>" />
							<input type="hidden" id="jobseekerid" name="jobseekerid" value="<?php  echo $this->message->jobseekerid; ?>" />
						<?php } ?>
                        <input type="hidden" id="sm" name="sm" value="<?php echo $this->sm; ?>" />
		  
</form>
		</td>
</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			
		</td>
	</tr>

</table>
