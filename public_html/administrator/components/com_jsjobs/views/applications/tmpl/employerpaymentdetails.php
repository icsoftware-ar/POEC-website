<?php
/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 + Created by:	Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Sep 11, 2010
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/views/application/tmpl/info.php
 ^
 * Description: JS Jobs Information
 ^
 * History:		NONE
 ^
 */

 JRequest :: setVar('layout', 'employerpaymentdetails');
$_SESSION['cur_layout']='employerpaymentdetails';


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
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			</form>

			<?php  $trclass = array("row0", "row1");
			$isodd = 1;

			?>
			    <table cellpadding="0" cellspacing="0" border="1" width="100%" class="adminform">

			      <tr align="center" valign="middle" class="row1">
			      <td align="center" colspan="2" height="55" valign="middle"><h1><center><?php echo $this->items->packagetitle; ?></h1></center>
			      </tr>
				  <tr align="left" valign="middle" class="row0" >
			         <td align="left" width="35%" valign="top"><?php echo JText::_('JS_PAYER_NAME') . ' :<strong> </td><td>' . $this->items->payer_firstname.' '.$this->items->payer_lastname.'</strong>'; ?></td>
			      </tr>
				  <tr align="left" valign="middle" class="row1">
			         <td align="left" valign="top"><?php echo JText::_('JS_PAYER_EMAIL') . ' :<strong> </td><td>' . $this->items->payer_email.'</strong>'; ?></td>
			      </tr>
				    <tr align="left" valign="middle" class="row0">
			         <td align="left" valign="top"><?php echo JText::_('JS_PAYER_AMOUNT') . ' :<strong> </td><td>' . $this->items->payer_amount.'</strong>'; ?></td>
			      </tr>
				  <tr align="left" valign="middle" class="row1">
			         <td align="left" valign="top"><?php echo JText::_('JS_PAYER_ITEMNAME') . ' :<strong> </td><td>' . $this->items->payer_itemname.'</strong>'; ?></td>
			      </tr>
				     <tr align="left" valign="middle" class="row0">
			         <td align="left" valign="top"><?php echo JText::_('JS_PAYER_ITEMNAME1') . ' :<strong> </td><td>' . $this->items->payer_itemname2.'</strong>'; ?></td>
			      </tr>
				  <tr  class="row1">
				  <td ><?php echo JText::_('JS_TRANSACTION_VERIFIED');
		  		 if($this->items->transactionverified == 1)  echo ' :<strong> </td><td>'. JText::_('JS_APPROVE') .'</strong>'; else  echo ' :<strong> </td><td>'.JText::_('JS_REJECT') .'</strong>'; ?></td>
			     </tr>
			      <tr align="left" valign="middle" class="row0">
			        <td align="left" valign="top"><?php echo JText::_('JS_TRANSACTION_AUTO_VERIFIED') ;
		  		 if($this->items->transactionautoverified == 1)  echo ' :<strong> </td><td>'. JText::_('JS_AUTO_APPROVE') .'</strong>'; else  echo ' :<strong> </td><td>'.JText::_('JS_MANUAL_APPROVE') .'</strong>'; ?></td>
			      </tr>
				   <tr align="left" valign="middle" class="row1">
			         <td align="left" valign="top"><?php echo JText::_('JS_VERIFY_DATE') . ' :<strong> </td><td>' . date( $this->config['date_format'],strtotime($this->items->verifieddate)).'</strong>'; ?></td>
			      </tr>
				   <tr align="left" valign="middle" class="row0">
			         <td align="left" valign="top"><?php echo JText::_('JS_PAID_AMOUNT') . ' :<strong> </td><td>' .$this->items->paidamount.'</strong>'; ?></td>
			      </tr>
				  <tr align="left" valign="middle" class="row1">
			         <td align="left" valign="top"><?php echo JText::_('JS_CREATED') . ' :<strong> </td><td>' . date( $this->config['date_format'],strtotime($this->items->created)).'</strong>'; ?></td>
			      </tr>
				  <tr align="left" valign="middle" class="row0">
			        <td align="left" valign="top"><?php echo JText::_('JS_STATUS') ;
		  			if($this->items->status == 1)  echo ' :<strong> </td><td>'. JText::_('JS_APPROVED') .'</strong>'; else  echo ' :<strong> </td><td>'.JText::_('JS_REJECTED') .'</strong>'; ?></td>
			     </tr>

			    </table>




		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			
		</td>
	</tr>
</table>
