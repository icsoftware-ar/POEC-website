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

JHTML::_('behavior.calendar');
JHTML::_('behavior.formvalidation');  
$document = &JFactory::getDocument();
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


<form action="index.php" method="post" name="adminForm" id="adminForm"  onSubmit="return validate_form(this);" >
<input type="hidden" name="check" value="post"/>
    <table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
		<?php if($this->msg != ''){ ?>
		 <tr>
			<td colspan="2" align="center"><font color="red"><strong><?php echo JText::_($this->msg); ?></strong></font></td>
		  </tr>
		  <tr><td colspan="2" height="10"></td></tr>	
		<?php	}	?>
		
		<tr class="row0">
				<td align="right"><label id="titlemsg" for="title"><?php echo JText::_('JS_APPLICATION_TITLE'); ?></label></td>
				<td><?php  if(isset($this->goldresume)) echo $this->goldresume->application_title; else echo $this->application_title ; ?></td>
		</tr>
		
		 <tr class="row1">
				<td align="right"><label id="packageidmsg" for="packageid"><?php echo JText::_('JS_JOBSEEKER_PACKAGE'); ?></label></td>
				<td><?php  echo $this->lists['jobseekerpackage']; ?>
				</td>
				
		 </tr>
		
		
		
		
      <tr>
        <td colspan="2" height="5"></td>
      <tr>
	<tr>
		<td colspan="2" align="center">
		<input class="button" type="submit" onclick="return validate_form(document.adminForm)" name="submit_app" onClick="return myValidate();" value="<?php echo JText::_('JS_SAVE_GOLD_RESUME'); ?>" />
		</td>
	</tr>
    </table>


			<input type="hidden" name="id" value="<?php if(isset($this->goldresume)) echo $this->goldresume->id; ?>" />
			<input type="hidden" name="task" value="savegoldresume" />
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			<input type="hidden" name="uid" value="<?php echo $this->uid;  ?>" />
			<input type="hidden" name="status" value="1" />
		

		  <input type="hidden" name="Itemid" id="Itemid" value="<?php echo $this->Itemid; ?>" />
		  
		  
			

		</form>

		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			
		</td>
	</tr>
	
</table>				
