<?php
/**
 * @Copyright Copyright (C) 2009-2011 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/views/application/tmpl/info.php
 ^ 
 * Description: JS Jobs Information
 ^ 
 * History:		NONE
 ^ 
 */

 JRequest :: setVar('layout', 'packageinfo');
$_SESSION['cur_layout']='packageinfo';


?>
<table width="100%">
	<tr>
		<td align="left" width="175" valign="top">
			<table width="100%"><tr><td style="vertical-align:top;">
			<?php
			include_once('components/com_jsjobs/views/menu.php');
			?>
			</td>
			</tr></table>
		</td>
		<td width="100%" valign="top">
			<form action="index.php" method="POST" name="adminForm" id="adminForm">
			  
			    <table cellpadding="2" cellspacing="4" border="1" width="100%" class="adminform">
			      <tr align="left" height="55" valign="middle" class="adminform">
			         <td align="left" valign="middle"><h1><?php echo JText::_('JS_DETAILS') ; ?></h1></td>
			      </tr>
			      <tr align="left" valign="middle">
			         <td align="left" valign="top"><?php echo JText::_('JS_PACKAGE_TITLE') . ' :<strong> ' . $this->items->packagetitle.'</strong>'; ?></td>
			      </tr>
			      <tr align="left" valign="middle">
			         <td align="left" valign="top"><?php echo JText::_('JS_PRICE') . ' :<strong> ' . $this->items->packageprice.'</strong>'; ?></td>
			      </tr>
			      <tr align="left" valign="middle">
			         <td align="left" valign="top"><?php echo JText::_('JS_DISCOUNT') . ' :<strong> ' . $this->items->discountamount.'</strong>'; ?></td>
			      </tr>
			      <tr align="left" valign="middle">
			         <td align="left" valign="top"><?php echo JText::_('JS_DISCOUNT_MESSAGE') . '  :<strong> ' . $this->items->discountmessage.'</strong>'; ?></td>
			      </tr>
			      <tr align="left" valign="middle">
			         <td align="left" valign="top"><?php echo JText::_('JS_DESCCRIPTION') . ' :<strong> ' . $this->items->packagedescription.'</strong>'; ?></td>
			      </tr>
			      
			    </table>
			  



			</form>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			
		</td>
	</tr>
</table>							
<script language="javascript" type="text/javascript">
	dhtml.cycleTab('tab1');
</script>
