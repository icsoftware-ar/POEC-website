<?php
/**
 * @Copyright Copyright (C) 2009-2011 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jan 11, 2011
 ^
 + Project: 		JS Jobs
  
 * Description: Form template for county
 * 
 * History:		NONE
 * 
 */
 
defined('_JEXEC') or die('Restricted access'); 
jimport('joomla.html.pane');

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


<form action="index.php" method="post" name="adminForm" id="adminForm" >
<input type="hidden" name="check" value="post"/>
    <table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
		<?php if($this->msg != ''){ ?>
		 <tr>
			<td colspan="2" align="center"><font color="red"><strong><?php echo JText::_($this->msg); ?></strong></font></td>
		  </tr>
		  <tr><td colspan="2" height="10"></td></tr>	
		<?php	}	?>
		
		
		<tr class="row0">
				<td width="20%" align="right"><label id="companyidmsg" for="companyid"><?php echo JText::_('JS_COMPANY'); ?></label>&nbsp;<font color="red">*</font></td>
				<td width="60%"><?php  echo $this->lists['companies']; ?>
				</td>
			  </tr>
		 <tr class="row1">
				        <td valign="top" align="right"><label id="namemsg" for="name"><?php echo JText::_('JS_DEPARTMENT_NAME'); ?></label>&nbsp;<font color="red">*</font></td>
				        <td><input class="inputbox required" type="text" name="name" id="name" size="20"  value="<?php if(isset($this->department)) echo $this->department->name; ?>" />
				        </td>
		 </tr>
		<tr class="row0">
								<td colspan="2" valign="top" align="center"><label id="descriptionmsg" for="description"><strong><?php echo JText::_('JS_DESCRIPTION'); ?></strong></label></td>
		</tr>
                <tr class="row1">
                    <td colspan="2" align="center" width="600">
                    <?php
                    $editor =& JFactory::getEditor();
                    if(isset($this->department))
                    echo $editor->display('description', $this->department->description, '550', '300', '60', '20', false);
                    else
                    echo $editor->display('description', '', '550', '300', '60', '20', false);
                    ?>
                    </td>
                </tr>
						
			  <tr class="row0" >
				<td align="right"><label id="statusmsg" for="status"><?php echo JText::_('JS_STATUS'); ?></label></td>
				<td><?php  echo $this->lists['status']; ?>
				</td>
			  </tr>
		
      <tr>
        <td colspan="2" height="5"></td>
      <tr>
	<tr>
		<td colspan="2" align="center">
		<input class="button" type="submit" onclick="return validate_form(document.adminForm)" name="submit_app" onClick="return myValidate();" value="<?php echo JText::_('JS_SAVE_DEPARTMENT'); ?>" />
		</td>
	</tr>
    </table>


			<input type="hidden" name="id" value="<?php if(isset($this->department)) echo $this->department->id; ?>" />
			<input type="hidden" name="task" value="savedepatrment" />
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			<input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
		

		  <input type="hidden" name="Itemid" id="Itemid" value="<?php echo $this->Itemid; ?>" />
		  
		  
			

		</form>

		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			
		</td>
	</tr>
	
</table>				
