<?php
/**
 * @Copyright Copyright (C) 2009-2011 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	views/employer/tmpl/formcompany.php
 ^ 
 * Description: template for form company
 ^ 
 * History:		NONE
 ^ 
 */

defined('_JEXEC') or die('Restricted access');

 global $mainframe;

$editor = & JFactory :: getEditor();
JHTML :: _('behavior.calendar');
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
                if (task == 'savefolder'){
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
	if(pressbutton == 'savefolder'){
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

function validate_form(f) {
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

		<?php
		if(isset($this->folders)) ;?>
                  <tr class="row0">
                        <td width="20%" align="right"><label id="namemsg" for="name"><strong><?php echo JText::_('JS_FOLDER_NAME'); ?></strong></label>&nbsp;<font color="red">*</font></td>
                        <td width="60%"><input class="inputbox required" type="text" name="name" id="name" size="40" maxlength="255" value="<?php if(isset($this->folders)) echo $this->folders->name; ?>" />
                        </td>
                  </tr>
		<tr class="row1">
                        <td colspan="2" valign="top" align="center"><label id="decriptionmsg" for="decription"><strong><?php echo JText::_('JS_DESCRIPTION'); ?></strong><font color="red">*</font></label></td>
		</tr>
                <tr class="row0">
                    <td colspan="2" align="center" width="600">
                        <?php
                        $editor =& JFactory::getEditor();
                        if(isset($this->folders))
                        echo $editor->display('decription', $this->folders->decription, '550', '300', '60', '20', false);
                        else
                        echo $editor->display('decription', '', '550', '300', '60', '20', false);
                        ?>
                    </td>
                </tr>

                <tr class="row1">
				<td width="20%" align="right"><label id="statusmsg" for="status"><?php echo JText::_('JS_STATUS'); ?></label>&nbsp;<font color="red">*</font></td>
				<td width="60%"><?php  echo $this->lists['status']; ?>
				</td>
			  </tr>

    <tr>
        <td colspan="2" height="10"></td>
      </tr>
	<tr>
		<td colspan="2" align="center">
                    <input class="button" type="submit" name="submit_app" onclick="return myValidate(f)" value="<?php echo JText::_('JS_SAVE_FOLDER'); ?>" />
		</td>
	</tr>
    </table>


                        <?php if(isset($this->folders)) $curdate = $this->folders->created;
                        else $curdate = date('Y-m-d H:i:s');    ?>
			<input type="hidden" name="created" value="<?php echo $curdate; ?>" />
			<input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			<input type="hidden" name="global"  value="1" />
			<input type="hidden" name="task" value="savefolder" />
			<input type="hidden" name="id" value="<?php if(isset($this->folders)) echo $this->folders->id; ?>" />
		  
		  

		</form>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			
		</td>
	</tr>

</table>
