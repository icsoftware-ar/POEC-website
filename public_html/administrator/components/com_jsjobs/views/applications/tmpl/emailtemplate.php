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
 * File Name:	admin-----/views/application/tmpl/emailtemplate.php
 ^ 
 * Description: Form template for a job
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access'); 
jimport('joomla.html.pane');

$editor = &JFactory::getEditor();
JHTML::_('behavior.calendar');
JHTML::_('behavior.formvalidation'); 
$version = new JVersion;
$joomla = $version->getShortVersion();
$jversion = substr($joomla,0,3);
$document = &JFactory::getDocument();
$document->addStyleSheet(JURI::root().'administrator/components/com_jsjobs/include/css/jsjobsadmin.css');

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
                alert('<?php echo JText::_('JS_SOME_VALUES_ARE_NOT_ACCEPTABLE_PLEASE_RETRY');?>');
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

				<div id="jsjobs_info_heading"><?php echo JText::_('JS_EMAIL_TEMPLATES'); ?></div>

<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" >
<input type="hidden" name="check" value="post"/>
    <table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
				      <tr class="row1">
				        <td width="50" colspan="3" align="right"><label id="subjectmsg" for="subject"><?php echo JText::_('JS_SUBJECT'); ?></label>&nbsp;<font color="red">*</font>&nbsp;:&nbsp;&nbsp;&nbsp;
				          <input class="inputbox required" type="text" name="subject" id="subject" size="135" maxlength="255" value="<?php if(isset($this->template)) echo $this->template->subject; ?>" />
				        </td>
				      </tr>
							<tr><td height="10" colspan="2"></td></tr>
							<tr class="row2">
								<td colspan="3" valign="top" align="center"><label id="descriptionmsg" for="body"><strong><?php echo JText::_('JS_BODY'); ?></strong></label>&nbsp;<font color="red">*</font></td>
							</tr>
							<tr>
								<td colspan="2" align="center" width="600">
								<?php
									
									$editor =& JFactory::getEditor();
									if(isset($this->template))
										echo $editor->display('body', $this->template->body, '550', '300', '60', '20', false);
									else
										echo $editor->display('body', '', '550', '300', '60', '20', false);
								?>	
								</td>
								<td width="35%" valign="top">
									<table  cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
										
										<tr class="row1"><td> <strong><u><?php echo JText::_('JS_PARAMETERS'); ?></u></strong></td>	</tr>
										<?php if(($this->template->templatefor == 'company-approval' ) || ($this->template->templatefor == 'company-rejecting' ) ) { ?>
											<tr><td>{COMPANY_NAME} :  <?php echo JText::_('JS_COMPANY_NAME'); ?></td></tr>
											<tr><td>{EMPLOYER_NAME} :  <?php echo JText::_('JS_EMPLOYER_NAME'); ?></td>	</tr>
										<?php } elseif(($this->template->templatefor == 'job-approval' ) || ($this->template->templatefor == 'job-rejecting' ) ) { ?>
											<tr><td>{JOB_TITLE} :  <?php echo JText::_('JS_JOB_TITLE'); ?></td></tr>
											<tr><td>{EMPLOYER_NAME} :  <?php echo JText::_('JS_EMPLOYER_NAME'); ?></td>	</tr>
										<?php } elseif(($this->template->templatefor == 'resume-approval' ) || ($this->template->templatefor == 'resume-rejecting' ) ) { ?>										
											<tr><td>{RESUME_TITLE} :  <?php echo JText::_('JS_RESUME_TITLE'); ?></td></tr>
											<tr><td>{JOBSEEKER_NAME} :  <?php echo JText::_('JS_JOBSEEKER_NAME'); ?></td>	</tr>
										<?php } elseif($this->template->templatefor == 'company-new' )  { ?>										
											<tr><td>{COMPANY_NAME} :  <?php echo JText::_('JS_COMPANY_NAME'); ?></td></tr>
											<tr><td>{EMPLOYER_NAME} :  <?php echo JText::_('JS_EMPLOYER_NAME'); ?></td>	</tr>
										<?php } elseif($this->template->templatefor == 'job-new' )  { ?>										
											<tr><td>{JOB_TITLE} :  <?php echo JText::_('JS_JOB_TITLE'); ?></td></tr>
											<tr><td>{EMPLOYER_NAME} :  <?php echo JText::_('JS_EMPLOYER_NAME'); ?></td>	</tr>
										<?php } elseif($this->template->templatefor == 'resume-new' )  { ?>										
											<tr><td>{RESUME_TITLE} :  <?php echo JText::_('JS_RESUME_TITLE'); ?></td></tr>
											<tr><td>{JOBSEEKER_NAME} :  <?php echo JText::_('JS_JOBSEEKER_NAME'); ?></td>	</tr>
										<?php } elseif($this->template->templatefor == 'message-email' )  { ?>
											<tr><td>{RESUME_TITLE} :  <?php echo JText::_('JS_RESUME_TITLE'); ?></td></tr>
											<tr><td>{JOBSEEKER_NAME} :  <?php echo JText::_('JS_JOBSEEKER_NAME'); ?></td>	</tr>
										<?php } elseif($this->template->templatefor == 'department-new' )  { ?>										
											<tr><td>{DEPARTMENT_TITLE} :  <?php echo JText::_('JS_DEPARTMENT_TITLE'); ?></td></tr>
											<tr><td>{COMPANY_NAME} :  <?php echo JText::_('JS_COMPANY_NAME'); ?></td></tr>
											<tr><td>{EMPLOYER_NAME} :  <?php echo JText::_('JS_EMPLOYER_NAME'); ?></td>	</tr>
										<?php } elseif($this->template->templatefor == 'employer-buypackage' )  { ?>										
											<tr><td>{PACKAGE_NAME} :  <?php echo JText::_('JS_PACKAGE_TITLE'); ?></td></tr>
											<tr><td>{EMPLOYER_NAME} :  <?php echo JText::_('JS_EMPLOYER_NAME'); ?></td>	</tr>
											<tr><td>{PACKAGE_PRICE} :  <?php echo JText::_('JS_PACKAGE_PRICE'); ?></td>	</tr>
										<?php } elseif($this->template->templatefor == 'jobseeker-buypackage' )  { ?>										
											<tr><td>{PACKAGE_NAME} :  <?php echo JText::_('JS_PACKAGE_TITLE'); ?></td></tr>
											<tr><td>{JOBSEEKER_NAME} :  <?php echo JText::_('JS_JOBSEEKER_NAME'); ?></td>	</tr>
											<tr><td>{PACKAGE_PRICE} :  <?php echo JText::_('JS_PACKAGE_PRICE'); ?></td>	</tr>
										<?php } elseif($this->template->templatefor == 'jobapply-jobapply' ) { ?>										
											<tr><td>{EMPLOYER_NAME} :  <?php echo JText::_('JS_EMPLOYER_NAME'); ?></td>	</tr>
											<tr><td>{JOBSEEKER_NAME} :  <?php echo JText::_('JS_JOBSEEKER_NAME'); ?></td>	</tr>
											<tr><td>{JOB_TITLE} :  <?php echo JText::_('JS_JOB_TITLE'); ?></td></tr>
										<?php } elseif($this->template->templatefor == 'message-email' ) { ?>
											<tr><td>{NAME} :  <?php echo JText::_('JS_NAME'); ?></td>	</tr>
											<tr><td>{SENDER_NAME} :  <?php echo JText::_('JS_SENDER_NAME'); ?></td>	</tr>
											<tr><td>{JOB_TITLE} :  <?php echo JText::_('JS_JOB_TITLE'); ?></td></tr>
											<tr><td>{COMPANY_NAME} :  <?php echo JText::_('JS_COMPANY_NAME'); ?></td></tr>
											<tr><td>{RESUME_TITLE} :  <?php echo JText::_('JS_RESUME_TITLE'); ?></td></tr>
										<?php } elseif($this->template->templatefor == 'job-alert' ) { ?>
											<tr><td>{JOBSEEKER_NAME} :  <?php echo JText::_('JS_JOBSEEKER_NAME'); ?></td>	</tr>
											<tr><td>{JOBS_INFO} :  <?php echo JText::_('JS_SHOW_JOBS'); ?></td>	</tr>
										<?php } elseif($this->template->templatefor == 'job-alert-visitor' ) { ?>
											<tr><td>{JOB_TITLE} :  <?php echo JText::_('JS_JOB_TITLE'); ?></td></tr>
											<tr><td>{COMPANY_NAME} :  <?php echo JText::_('JS_COMPANY_NAME'); ?></td>	</tr>
											<tr><td>{JOB_CATEGORY} :  <?php echo JText::_('JS_JOB_CATEGORY'); ?></td>	</tr>
											<tr><td>{JOB_STATUS} :  <?php echo JText::_('JS_JOB_STATUS'); ?></td>	</tr>
											<tr><td>{CONTACT_NAME} :  <?php echo JText::_('JS_CONTACT_NAME'); ?></td>	</tr>
											<tr><td>{JOB_LINK} :  <?php echo JText::_('JS_JOB_LINK'); ?></td>	</tr>
										<?php } elseif($this->template->templatefor == 'job-to-friend' ) { ?>
											<tr><td>{SENDER_NAME} :  <?php echo JText::_('JS_SENDER_NAME'); ?></td></tr>
											<tr><td>{SITE_NAME} :  <?php echo JText::_('JS_SITE_NAME'); ?></td></tr>
											<tr><td>{JOB_TITLE} :  <?php echo JText::_('JS_JOB_TITLE'); ?></td></tr>
											<tr><td>{JOB_CATEGORY} :  <?php echo JText::_('JS_JOB_CATEGORY'); ?></td>	</tr>
											<tr><td>{COMPANY_NAME} :  <?php echo JText::_('JS_COMPANY_NAME'); ?></td>	</tr>
											<tr><td>{CLICK_HERE_TO_VISIT} :  <?php echo JText::_('JS_CLICK_HERE_TO_VISIT'); ?></td>	</tr>
											<tr><td>{SENDER_MESSAGE} :  <?php echo JText::_('JS_SENDER_MESSAGE'); ?></td>	</tr>
										<?php } elseif($this->template->templatefor == 'applied-resume_status' ) { ?>
											<tr><td>{JOBSEEKER_NAME} :  <?php echo JText::_('JS_JOBSEEKER_NAME'); ?></td></tr>
											<tr><td>{RESUME_STATUS} :  <?php echo JText::_('JS_APPLIED_RESUME_STATUS'); ?></td></tr>
											<tr><td>{JOB_TITLE} :  <?php echo JText::_('JS_JOB_TITLE'); ?></td></tr>
										<?php } ?>
									</table>
								</td>
							</tr>
      <tr>
        <td colspan="2" height="5"></td>
      <tr>
    </table>


	<?php 
				if(isset($this->template)) {
					if (($this->template->created=='0000-00-00 00:00:00') || ($this->template->created==''))
						$curdate = date('Y-m-d H:i:s');
					else  
						$curdate = $this->template->created;
				}else
					$curdate = date('Y-m-d H:i:s');
				
			?>
			<input type="hidden" name="created" value="<?php echo $curdate; ?>" />
			<input type="hidden" name="view" value="jobposting" />
			<input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
			<input type="hidden" name="id" value="<?php echo $this->template->id; ?>" />
			<input type="hidden" name="templatefor" value="<?php echo $this->template->templatefor; ?>" />
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			<input type="hidden" name="task" value="saveemailtemplate" />
		 	<input type="hidden" name="Itemid" id="Itemid" value="<?php echo $this->Itemid; ?>" />
		  
		  

		</form>

		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			
		</td>
	</tr>
	
</table>				
