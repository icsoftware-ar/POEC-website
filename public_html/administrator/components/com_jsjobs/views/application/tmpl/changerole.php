<?php
/**
 * @Copyright Copyright (C) 2009-2011 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/views/application/tmpl/formrole.php
 * 
 * Description: Form template for change role
 * 
 * History:		NONE
 * 
 */
 
defined('_JEXEC') or die('Restricted access'); 

$version = new JVersion;
$joomla = $version->getShortVersion();
$jversion = substr($joomla,0,3);
JHTML::_('behavior.formvalidation'); 
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
                alert('Some values are not acceptable.  Please retry.');
				return false;
        }
		return true;
}
</script>
<table width="100%" border="0">
	<tr>
		<td align="left" width="175" valign="top">
		<!-- table-layout:fixed; -->
			<table style="width:100%;"><tr><td style="vertical-align:top;">
			<?php
			include_once('components/com_jsjobs/views/menu.php');
			?>
			</td>
			</tr></table>
		</td>
		<td width="100%" valign="top">

	<form action="index.php" method="POST" name="adminForm" id="adminForm" >
	<table cellpadding="0" cellspacing="0" border="1" width="100%" class="adminform">
	<?php
	if($this->msg != ''){
	?>
	 <tr>
        <td align="center"><font color="red"><strong><?php echo JText::_($this->msg); ?></strong></font></td>
      </tr>
	  <tr><td  height="10"></td></tr>	
	<?php
	}
	?>
	 <tr class="row1">
        <td width="40%" align="right"><label id="titlemsg" for="title"><?php echo JText::_('Name'); ?> : </label></td>
		<td><?php if(isset($this->role)) echo $this->role->name; ?>
        </td>
      </tr>
	 <tr class="row2">
        <td align="right"><?php echo JText::_('Username'); ?> : </td>
		<td><?php if(isset($this->role)) echo $this->role->username; ?>
        </td>
      </tr>
	  <?php 
			$img 	= $this->role->block ? 'publish_x.png' : 'tick.png';
			$task 	= $this->role->block ? 'unblock' : 'block';
			$alt 	= $this->role->block ? JText::_( 'Enabled' ) : JText::_( 'Blocked' );
	  ?>
	 <tr class="row1">
        <td align="right"><?php echo JText::_('Enabled'); ?> : </td>
		<td><img src="../components/com_jsjobs/images/<?php echo $img;?>" width="16" height="16" border="0" alt="<?php echo $alt; ?>" />
        </td>
      </tr>
	 <tr class="row2">
        <td align="right"><?php echo JText::_('Group'); ?> : </td>
		<td><?php if(isset($this->role)) echo $this->role->groupname; ?>
        </td>
      </tr>
	 <tr class="row1">
        <td align="right"><?php echo JText::_('ID'); ?> : </td>
		<td><?php if(isset($this->role)) echo $this->role->id; ?>
        </td>
      </tr>
	 <tr class="row2">
        <td align="right"><?php echo JText::_('JS_ROLE'); ?> : </td>
		<td><?php echo $this->lists['roles']; ?>
        </td>
      </tr>

    </table>
			<?php
				if(isset($this->role)) {
					if (($this->role->dated=='0000-00-00 00:00:00') || ($this->role->dated==''))
						$curdate = date('Y-m-d H:i:s');
					else
						$curdate = $this->role->dated;
				}else{
					$curdate = date('Y-m-d H:i:s');
				}

			?>
			<input type="hidden" name="dated" value="<?php echo $curdate; ?>" />
			<input type="hidden" name="id" value="<?php if(isset($this->role)) echo $this->role->userroleid; ?>" />
			<input type="hidden" name="uid" value="<?php if(isset($this->role)) echo $this->role->id; ?>" />
			<input type="hidden" name="view" value="application" />
			<input type="hidden" name="layout" value="roles" />
			<input type="hidden" name="check" value="" />
			<input type="hidden" name="task" value="saveuserrole" />
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />

		
	
  </form>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			
		</td>
	</tr>
	
</table>							
