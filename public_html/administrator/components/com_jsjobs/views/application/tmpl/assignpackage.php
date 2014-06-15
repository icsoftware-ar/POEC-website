<?php
/**
 * @Copyright Copyright (C) 2009-2011 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/views/application/tmpl/assignpackage.php
 *
 * Description: Form to set package to users
 *
 * History:		NONE
 *
 */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.pane');
JHTML::_('behavior.formvalidation');
JHTML::_('behavior.modal');
$version = new JVersion;
$joomla = $version->getShortVersion();
$jversion = substr($joomla,0,3);


?>

<script type="text/javascript">
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
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminlist">
            <?php
                $td=array('row0','row1');$k=0;
                //$td=array('','');
                $userlink='index.php?option=com_jsjobs&view=application&layout=users&tmpl=component&task=preview';
            ?>
            <tr class="<?php echo $td[$k];$k=1-$k; ?>">
                <td valign="top" align="right"><label id="usernamemsg" for="username"><?php echo JText::_('JS_USER_NAME'); ?></label><font color="red">*</font> </td>
                <td>
                    <input  class="inputbox required" type="text" name="username" id="username" value="<?php if(isset($this->user)) { echo $this->user->username; }else { echo ""; } ?>" />
                        <a class="modal" rel="{handler: 'iframe', size: {x: 870, y: 350}}" id="" href="<?php echo $userlink; ?>"><?php echo JText::_('JS_SELECT_USER') ?></a>
                </td>
           </tr>
            <tr class="<?php echo $td[$k];$k=1-$k; ?>">
                <td valign="top" align="right"><label id="userpackagemsg" for="packageid"><?php echo JText::_('JS_PACKAGE'); ?></label><font color="red">*</font> </td>
                <td id="package">
                </td>
           </tr>
	<tr><td colspan="2"  height="20"></td></tr>
	<tr>
		<td colspan="2" align="center">
		<input type="submit" class="button" name="submit_app" onclick="return validate_form(document.adminForm)" value="<?php echo JText::_('JS_SAVE'); ?>" />
		</td>
	</tr>

    </table>
			<input type="hidden" name="nisactive" value="1" />
			<input type="hidden" name="view" value="applications" />
			<input type="hidden" name="layout" value="jobseekerpaymenthistory" />
			<input type="hidden" name="check" value="" />
			<input type="hidden" name="task" value="saveuserpackage" />
			<input type="hidden" name="userrole" id="userrole" value="" />
			<input type="hidden" name="userid" id="userid" value="" />
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />



  </form>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			
		</td>
	</tr>

</table>
<script language="javascript">
function setuser(username,userid){
		var isexist;
		var xhr;
		try {  xhr = new ActiveXObject('Msxml2.XMLHTTP');   }
		catch (e) {
			try {   xhr = new ActiveXObject('Microsoft.XMLHTTP');    }
			catch (e2) {
			  try {  xhr = new XMLHttpRequest();     }
			  catch (e3) {  xhr = false;   }
			}
		 }

			xhr.onreadystatechange = function(){
				if(xhr.readyState == 4 && xhr.status == 200){
									var data = xhr.responseText;
									if(data != false){
                                    var obj = eval("("+xhr.responseText+")");//return Value
                                    document.getElementById('package').innerHTML = obj.list;
                                    document.getElementById('username').value = username;
                                    document.getElementById('userrole').value = obj.userrole;
                                    document.getElementById('userid').value = userid;
                                    window.setTimeout('closeme();', 300);
									}else{
										alert('<?php echo JText::_('JS_SELECTED_USERS_IS_NOT_THE_USER_OF_JSJOBS_SYSTEM')?>');
									}
				}
			}

		xhr.open("GET","index.php?option=com_jsjobs&task=listuserdataforpackage&val="+userid,true);
		xhr.send(null);

}
  function closeme() {
  parent.SqueezeBox.close();
  }
</script>
