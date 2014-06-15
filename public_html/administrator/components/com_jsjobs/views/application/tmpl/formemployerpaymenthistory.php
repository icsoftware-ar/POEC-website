<?php
/**
 * @Copyright Copyright (C) 2009-2011 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jun 05, 2010
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/views/application/tmpl/formemployerpackage.php
 * 
 * Description: Form template for a employer package
 * 
 * History:		NONE
 * 
 */
 
defined('_JEXEC') or die('Restricted access'); 
jimport('joomla.html.pane');

$editor = &JFactory::getEditor();
JHTML::_('behavior.calendar');
JHTML::_('behavior.formvalidation');  
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


<form action="index.php" method="post" name="adminForm" id="adminForm"  >
<input type="hidden" name="check" value="post"/>
    <table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
		<?php if($this->msg != ''){ ?>
		 <tr>
			<td colspan="2" align="center"><font color="red"><strong><?php echo JText::_($this->msg); ?></strong></font></td>
		  </tr>
		  <tr><td colspan="2" height="10"></td></tr>	
		<?php	}	?>
		<tr class="row0">
		  <td valign="top" align="right"><label id="packagetitlemsg" for="packagetitle"><?php echo JText::_('JS_TITLE'); ?></label>&nbsp;<font color="red">*</font></td>
		  <td><input class="inputbox required" type="text" name="packagetitle" id="packagetitle" size="40" maxlength="255" value="<?php if(isset($this->package)) echo $this->package->packagetitle; ?>" /></td>
		</tr>
		<tr class="row1">
		  <td valign="top" align="right"><label id="packagepricemsg" for="packageprice"><?php echo JText::_('JS_PRICE'); ?></label>&nbsp;<font color="red">*</font></td>
		  <td><input class="inputbox required validate-numeric" type="text" name="packageprice" id="packageprice" size="40" maxlength="255" value="<?php if(isset($this->package)) echo $this->package->packageprice; ?>" /></td>
		</tr>
		<tr class="row0">
		  <td valign="top" align="right"><label id="discountamountmsg" for="discountamount"><?php echo JText::_('JS_DISCOUNT'); ?></label>&nbsp;<font color="red">*</font></td>
		  <td><input class="inputbox required validate-numeric" type="text" name="discountamount" id="discountamount" size="40" maxlength="255" value="<?php if(isset($this->package)) echo $this->package->discountamount; ?>" /></td>
		</tr>
		<tr class="row1">
		  <td valign="top" align="right"><label id="discountstartdate_msg" for="discountstartdate"><?php echo JText::_('JS_DISCOUNT_START_DATE'); ?></label>&nbsp;</td>
		 <td><input class="inputbox " type="text" name="discountstartdate" id="discountstartdate" readonly size="10" maxlength="10" value="<?php if(isset($this->package)) echo $this->package->discountstartdate; ?>" />
			<input type="reset" class="button" value="..." onclick="return showCalendar('discountstartdate','%Y-%m-%d');"  /></td>
				       
		</tr>
		<tr class="row0">
		 <td valign="top" align="right"><label id="discountenddate_msg" for="discountenddate"><?php echo JText::_('JS_DISCOUNT_END_DATE'); ?></label>&nbsp;</td>
		 <td><input class="inputbox " type="text" name="discountenddate" id="discountenddate" readonly size="10" maxlength="10" value="<?php if(isset($this->package)) echo $this->package->discountenddate; ?>" />
			<input type="reset" class="button" value="..." onclick="return showCalendar('discountenddate','%Y-%m-%d');"  /></td>
		</tr>
		<tr class="row1">
		  <td valign="top" align="right"><label id="discountmessage_msg" for="discountmessage"><?php echo JText::_('JS_DISCOUNT_MESSAGE'); ?></label>&nbsp;<font color="red">*</font></td>
		  <td><input class="inputbox required" type="text" name="discountmessage" id="discountmessage" size="40" maxlength="255" value="<?php if(isset($this->package)) echo $this->package->discountmessage; ?>" /></td>
		</tr>
		<tr class="row0">
				<td align="right"><label id="discounttypemsg" for="discounttype"><?php echo JText::_('JS_DISCOUNT_TYPE'); ?></label></td>
				<td><?php  echo $this->lists['type']; ?>
				</td>
				
			  </tr>
		
		<tr class="row1">
			<td colspan="2" valign="top" align="center"><label id="descriptionmsg" for="description"><strong><?php echo JText::_('JS_DESCRIPTION'); ?></strong></label>&nbsp;<font color="red">*</font></td>
		</tr>
		
		<tr class="row0">
			<td colspan="2" align="center">
			<?php
				$editor =& JFactory::getEditor();
				if(isset($this->package))
					echo $editor->display('description', $this->package->description, '550', '300', '60', '20', false);
				else
					echo $editor->display('description', '', '550', '300', '60', '20', false);
			?>	
			</td>
		</tr>
		<tr class="row1">
		  <td valign="top" align="right"><label id="resumeallowmsg" for="resumeallow"><?php echo JText::_('JS_COMPANIES_ALLOW'); ?></label>&nbsp;</td>
		  <td><input class="inputbox " type="text" name="resumeallow" id="resumeallow" size="40" maxlength="255" value="<?php if(isset($this->package)) echo $this->package->resumeallow; ?>" /></td>
		</tr>
		<tr class="row0">
		  <td valign="top" align="right"><label id="coverlettersallowmsg" for="coverlettersallow"><?php echo JText::_('JS_JOBS_ALLOW'); ?></label>&nbsp;</td>
		  <td><input class="inputbox " type="text" name="coverlettersallow" id="coverlettersallow" size="40" maxlength="255" value="<?php if(isset($this->package)) echo $this->package->coverlettersallow; ?>" /></td>
		</tr>
		<tr class="row1">
		  <td valign="top" align="right"><label id="featuredresumemsg" for="featuredresume"><?php echo JText::_('JS_FEATURED_COMPANIES'); ?></label>&nbsp;</td>
		  <td><input class="inputbox " type="text" name="featuredresume" id="featuredresume" size="40" maxlength="255" value="<?php if(isset($this->package)) echo $this->package->featuredresume; ?>" /></td>
		</tr>
		<tr class="row0">
		  <td valign="top" align="right"><label id="goldresumemsg" for="goldresume"><?php echo JText::_('JS_GOLD_COMPANIES'); ?></label>&nbsp;</td>
		  <td><input class="inputbox " type="text" name="goldresume" id="goldresume" size="40" maxlength="255" value="<?php if(isset($this->package)) echo $this->package->goldresume; ?>" /></td>
		</tr>
		<tr class="row1">
				<td align="right"><label id="jobsearchmsg" for="jobsearch"><?php echo JText::_('JS_RESUME_SEARCH'); ?></label></td>
				<td><?php  echo $this->lists['yesNo']; ?></td>
		</tr>
		<tr class="row0">
		<td align="right"><label id="savejobsearchmsg" for="savejobsearch"><?php echo JText::_('SAVE_RESUME_SEARCH'); ?></label></td>
				<td><?php  echo $this->lists['yesNo']; ?></td>
		</tr>
				
				
			<tr class="row1">
				<td align="right"><label id="statusmsg" for="status"><?php echo JText::_('JS_STATUS'); ?></label></td>
				<td><?php  echo $this->lists['status']; ?>
				</td>
				
			  </tr>
      <tr>
        <td colspan="2" height="5"></td>
      <tr>
	<tr>
		<td colspan="2" align="center">
		<input class="button" type="submit" onclick="return validate_form(document.adminForm)" name="submit_app" onClick="return myValidate();" value="<?php echo JText::_('JS_SAVE_EMPLOYER_PACKAGE'); ?>" />
		</td>
	</tr>
    </table>


			<input type="hidden" name="id" value="<?php if(isset($this->package)) echo $this->package->id; ?>" />
			
			<input type="hidden" name="task" value="saveemployerpaymenthistory" />
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			<input type="hidden" name="uid" value="<?php echo $uid; ?>" />
		
		  <input type="hidden" name="Itemid" id="Itemid" value="<?php echo $this->Itemid; ?>" />
		  
		  
			

		</form>

		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			
		</td>
	</tr>
	
</table>				
