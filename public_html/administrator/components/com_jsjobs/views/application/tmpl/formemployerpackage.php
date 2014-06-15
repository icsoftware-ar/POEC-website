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
$version = new JVersion;
$joomla = $version->getShortVersion();
$jversion = substr($joomla,0,3);

$editor = &JFactory::getEditor();
JHTML::_('behavior.calendar');
JHTML::_('behavior.formvalidation');  
$document = &JFactory::getDocument();
	if($jversion < 3){
		JHtml::_('behavior.mootools');
		$document->addScript('../components/com_jsjobs/js/jquery.js');
	}else{
		JHtml::_('behavior.framework');
		JHtml::_('jquery.framework');
	}	


	if($this->config['date_format']=='m/d/Y') $dash = '/';else $dash = '-';
	$dateformat = $this->config['date_format'];
	$firstdash = strpos($dateformat,$dash,0);
	$firstvalue = substr($dateformat, 0,$firstdash);
	$firstdash = $firstdash + 1;
	$seconddash = strpos($dateformat,$dash,$firstdash);
	$secondvalue = substr($dateformat, $firstdash,$seconddash-$firstdash);
	$seconddash = $seconddash + 1;
	$thirdvalue = substr($dateformat, $seconddash,strlen($dateformat)-$seconddash);
	$js_dateformat = '%'.$firstvalue.$dash.'%'.$secondvalue.$dash.'%'.$thirdvalue;
	$js_scriptdateformat = $firstvalue.$dash.$secondvalue.$dash.$thirdvalue;

?>

<script language="javascript">
function validate_start_stop_discount(){
		var date_start_make = new Array();
		var date_stop_make = new Array();
		var split_start_value=new Array();
		var split_stop_value=new Array();
		var returnvalue = true;
		var isedit = document.getElementById("id");
		var start_string = document.getElementById("discountstartdate").value;
		var stop_string = document.getElementById("discountenddate").value;
		var format_type = document.getElementById("j_dateformat").value;
			if(format_type=='d-m-Y'){
				split_start_value=start_string.split('-');

				date_start_make['year']=split_start_value[2];
				date_start_make['month']=split_start_value[1];
				date_start_make['day']=split_start_value[0];

				split_stop_value=stop_string.split('-');

				date_stop_make['year']=split_stop_value[2];
				date_stop_make['month']=split_stop_value[1];
				date_stop_make['day']=split_stop_value[0];

			}else if(format_type=='m/d/Y'){
				split_start_value=start_string.split('/');
				date_start_make['year']=split_start_value[2];
				date_start_make['month']=split_start_value[0];
				date_start_make['day']=split_start_value[1];

				split_stop_value=stop_string.split('/');

				date_stop_make['year']=split_stop_value[2];
				date_stop_make['month']=split_stop_value[0];
				date_stop_make['day']=split_stop_value[1];

			}else if(format_type=='Y-m-d'){

				split_start_value=start_string.split('-');

				date_start_make['year']=split_start_value[0];
				date_start_make['month']=split_start_value[1];
				date_start_make['day']=split_start_value[2];

				split_stop_value=stop_string.split('-');

				date_stop_make['year']=split_stop_value[0];
				date_stop_make['month']=split_stop_value[1];
				date_stop_make['day']=split_stop_value[2];

			}
			var start = new Date(date_start_make['year'],date_start_make['month']-1,date_start_make['day']);		
			var stop = new Date(date_stop_make['year'],date_stop_make['month']-1,date_stop_make['day']);
			if(start >= stop){
				returnvalue = false;
			}		
			return returnvalue;

}
window.addEvent('domready', function(){
   document.formvalidator.setHandler('discountstartdate', function(value) {
		
		var return_value=validate_start_stop_discount();
		return return_value;

   });
});	

window.addEvent('domready', function(){
   document.formvalidator.setHandler('discount', function(value) {

		var price = document.getElementById("price").value;
		var price_value=parseFloat(price);	
		if(isNaN(price_value)) return false;
		var discount = document.getElementById("discount").value;
		var  discount_value=parseFloat( discount);	
		if(isNaN(discount_value)) return false;
		var discount_obj = document.getElementById("discounttype");
		var discount_type = discount_obj.options[discount_obj.selectedIndex].value;
		if(discount_type==1){ // 1 for amount 
			if(discount_value > price) return false;
		}else if(discount_type==2){ // 2 for persent 
			if(discount_value > 100) return false;
		}
		return true;
   });
});	



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
	var msg = new Array();

        if (document.formvalidator.isValid(f)) {
                f.check.value='<?php if(($jversion == '1.5') || ($jversion == '2.5')) echo JUtility::getToken(); else echo  JSession::getFormToken(); ?>';//send token
        }
        else {
                msg.push('<?php echo JText::_( 'JS_SOME_VALUES_ARE_NOT_ACCEPTABLE_PLEASE_RETRY');?>');
		var element_package_discountstart = document.getElementById('discountstartdate');                
		if(hasClass(element_package_discountstart,'invalid')){
			msg.push('<?php echo JText::_('JS_DISCOUNT_START_DATE_MUST_BE_LESS_THEN_DISCOUNT_END_DATE'); ?>'); 
			
		}	
		var element_discount = document.getElementById('discount');                
		if(hasClass(element_discount,'invalid')){
			msg.push('<?php echo JText::_('JS_ENTER_CORRECT_DISCOUNT_VALUE_ACCORDING_TO_DISCOUNT_TYPE'); ?>'); 
			
		}	
	alert (msg.join('\n'));			
	return false;
        }
	return true;
}
function hasClass(el, selector) {
   var className = " " + selector + " ";
  
   if ((" " + el.className + " ").replace(/[\n\t]/g, " ").indexOf(className) > -1) {
    return true;
   }
   return false;
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
		  <td valign="top"  align="right"><label id="titlemsg" for="title"><?php echo JText::_('JS_TITLE'); ?></label>&nbsp;<font color="red">*</font></td>
		  <td width="70%"><input class="inputbox required" type="text" name="title" id="title" size="40" maxlength="255" value="<?php if(isset($this->package)) echo $this->package->title; ?>" /></td>
		</tr>
		<tr class="row1">
		  <td valign="top" align="right"><label id="pricemsg" for="price"><?php echo JText::_('JS_PRICE'); ?></label>&nbsp;<font color="red">*</font></td>
		  <td width="70%">
			<?php  echo $this->lists['currency']; ?>
			<input class="inputbox required validate-numeric" maxlength="6" type="text" name="price" id="price" size="40" maxlength="255" value="<?php if(isset($this->package)) echo $this->package->price; ?>" />
		</td>
		</tr>
		<tr class="row0">
		  <td valign="top" align="right"><label id="discountmsg" for="discount"><?php echo JText::_('JS_DISCOUNT'); ?></label>&nbsp;</td>
		  <td width="70%"><input class="inputbox validate-discount" maxlength="6" type="text" name="discount" id="discount" size="40" maxlength="255" value="<?php if(isset($this->package)) echo $this->package->discount; ?>" /></td>
		</tr>
		<tr class="row1">
			<?php 
			$startdatevalue = '';
			if(isset($this->package)) $startdatevalue = date($this->config['date_format'],strtotime($this->package->discountstartdate));
			?>
			<td valign="top" align="right"><label id="discountstartdate_msg" for="discountstartdate"><?php echo JText::_('JS_DISCOUNT_START_DATE'); ?></label>&nbsp;</td>
			<td><?php if($jversion == '1.5'){?><input class="inputbox validate-discountstartdate" type="text" name="discountstartdate" id="discountstartdate" readonly size="10" maxlength="10" value="<?php if(isset($this->package)) echo date($this->config['date_format'],strtotime($this->package->discountstartdate)); ?>" />
				<input type="reset" class="button" value="..." onclick="return showCalendar('discountstartdate','<?php echo $js_dateformat; ?>');"  />
				<?php	}else echo JHTML::_('calendar', $startdatevalue,'discountstartdate', 'discountstartdate',$js_dateformat,array('class'=>'inputbox validate-discountstartdate', 'size'=>'10',  'maxlength'=>'19')); ?>
			</td>
				       
		</tr>
		<tr class="row0">
			<?php 
				$stopdatevalue = '';
				if(isset($this->package)) $stopdatevalue = date($this->config['date_format'],strtotime($this->package->discountenddate));
			?>
			<td valign="top" align="right"><label id="discountenddate_msg" for="discountenddate"><?php echo JText::_('JS_DISCOUNT_END_DATE'); ?></label>&nbsp;</td>
			<td><?php if($jversion == '1.5'){?><input class="inputbox validate-discountenddate" type="text" name="discountenddate" id="discountenddate" readonly size="10" maxlength="10" value="<?php if(isset($this->package)) echo date($this->config['date_format'],strtotime($this->package->discountenddate)); ?>" />
				<input type="reset" class="button" value="..." onclick="return showCalendar('discountenddate','<?php echo $js_dateformat; ?>');"  />
				<?php	}else echo JHTML::_('calendar', $stopdatevalue,'discountenddate', 'discountenddate',$js_dateformat,array('class'=>'inputbox validate-discountenddate', 'size'=>'10',  'maxlength'=>'19')); ?>
			</td>
		</tr>
		<tr class="row1">
		  <td valign="top" align="right"><label id="discountmessage_msg" for="discountmessage"><?php echo JText::_('JS_DISCOUNT_MESSAGE'); ?></label></td>
		  <td><input class="inputbox" type="text" name="discountmessage" id="discountmessage" size="40" maxlength="255" value="<?php if(isset($this->package)) echo $this->package->discountmessage; ?>" /></td>
		</tr>
		<tr class="row0">
				<td align="right"><label id="discounttypemsg" for="discounttype"><?php echo JText::_('JS_DISCOUNT_TYPE'); ?></label></td>
				<td><?php  echo $this->lists['type']; ?>
				</td>
				
			  </tr>
		<tr class="row1">
		  <td valign="top" align="right"><label id="companiesallowmsg" for="companiesallow"><?php echo JText::_('JS_COMPANIES_ALLOW'); ?></label>&nbsp;</td>
		  <td><input class="inputbox validate-numeric" type="text" name="companiesallow" id="companiesallow" size="40" maxlength="255" value="<?php if(isset($this->package)) echo $this->package->companiesallow; ?>" /><?php echo JText::_('MINUS_ONE_TO_UNLIMITED') ?> </td>
		</tr>
		<tr class="row0">
		  <td valign="top" align="right"><label id="jobsallowmsg" for="jobsallow"><?php echo JText::_('JS_JOBS_ALLOW'); ?></label>&nbsp;</td>
		  <td><input class="inputbox validate-numeric" type="text" name="jobsallow" id="jobsallow" size="40" maxlength="255" value="<?php if(isset($this->package)) echo $this->package->jobsallow; ?>" /><?php echo JText::_('MINUS_ONE_TO_UNLIMITED') ?></td>
		</tr>
		<tr class="row1">
		  <td valign="top" align="right"><label id="viewresumeindetailsmsg" for="viewresumeindetails"><?php echo JText::_('JS_VIEW_RESUME_IN_DETAILS'); ?></label>&nbsp;</td>
		  <td><input class="inputbox validate-numeric" type="text" name="viewresumeindetails" id="viewresumeindetails" size="40" maxlength="255" value="<?php if(isset($this->package)) echo $this->package->viewresumeindetails; ?>" /><?php echo JText::_('MINUS_ONE_TO_UNLIMITED') ?><?php echo ' ( ' .JText::_('JS_EFFECT_ON_APPLIED_RESUME').' ) ' ?></td>
		</tr>
		<tr class="row0">
		  <td valign="top" align="right"><label id="goldcompaniesmsg" for="goldcompanies"><?php echo JText::_('JS_GOLD_COMPANIES'); ?></label>&nbsp;</td>
		  <td><input class="inputbox validate-numeric" type="text" name="goldcompanies" id="goldcompanies" size="40" maxlength="255" value="<?php if(isset($this->package)) echo $this->package->goldcompanies; ?>" /><?php echo JText::_('MINUS_ONE_TO_UNLIMITED') ?></td>
		</tr>
		<tr class="row1">
		  <td valign="top" align="right"><label id="goldcompaniesexpireindaysmsg" for="goldcompaniesexpireindays"><?php echo JText::_('JS_GOLD_COMPANIES_EXPIRE_IN_DAYS'); ?></label>&nbsp;</td>
		  <td><input class="inputbox validate-numeric" type="text" name="goldcompaniesexpireindays" id="goldcompaniesexpireindays" size="40" maxlength="255" value="<?php if(isset($this->package)) echo $this->package->goldcompaniesexpireindays; ?>" /></td>
		</tr>
		<tr class="row0">
		  <td valign="top" align="right"><label id="featuredcompainessg" for="featuredcompaines"><?php echo JText::_('JS_FEATURED_COMPANIES'); ?></label>&nbsp;</td>
		  <td><input class="inputbox validate-numeric" type="text" name="featuredcompaines" id="featuredcompaines" size="40" maxlength="255" value="<?php if(isset($this->package)) echo $this->package->featuredcompaines; ?>" /><?php echo JText::_('MINUS_ONE_TO_UNLIMITED') ?></td>
		</tr>
		<tr class="row1">
		  <td valign="top" align="right"><label id="featuredcompaniesexpireindaysmsg" for="featuredcompaniesexpireindays"><?php echo JText::_('JS_FEATURED_COMPANIES_EXPIRE_IN_DAYS'); ?></label>&nbsp;</td>
		  <td><input class="inputbox validate-numeric" type="text" name="featuredcompaniesexpireindays" id="featuredcompaniesexpireindays" size="40" maxlength="255" value="<?php if(isset($this->package)) echo $this->package->featuredcompaniesexpireindays; ?>" /></td>
		</tr>
		<tr class="row0">
		  <td valign="top" align="right"><label id="goldjobsmsg" for="goldjobs"><?php echo JText::_('JS_GOLD_JOBS'); ?></label>&nbsp;</td>
		  <td><input class="inputbox validate-numeric" type="text" name="goldjobs" id="goldjobs" size="40" maxlength="255" value="<?php if(isset($this->package)) echo $this->package->goldjobs; ?>" /><?php echo JText::_('MINUS_ONE_TO_UNLIMITED') ?></td>
		</tr>
		<tr class="row1">
		  <td valign="top" align="right"><label id="featuredjobsmsg" for="featuredjobs"><?php echo JText::_('JS_FEATURED_JOBS'); ?></label>&nbsp;</td>
		  <td><input class="inputbox validate-numeric" type="text" name="featuredjobs" id="featuredjobs" size="40" maxlength="255" value="<?php if(isset($this->package)) echo $this->package->featuredjobs; ?>" /><?php echo JText::_('MINUS_ONE_TO_UNLIMITED') ?></td>
		</tr>
		<tr class="row0">
		  <td valign="top" align="right"><label id="foldersmsg" for="folders"><?php echo JText::_('JS_FOLDERS_ALLOW'); ?></label>&nbsp;</td>
		  <td><input class="inputbox validate-numeric" type="text" name="folders" id="folders" size="40" maxlength="255" value="<?php if(isset($this->package)) echo $this->package->folders; ?>" /><?php echo JText::_('MINUS_ONE_TO_UNLIMITED') ?></td>
		</tr>
		<tr class="row1">
				<td align="right"><label id="messageallowmsg" for="messageallow"><?php echo JText::_('JS_MESSAGES_ALLOW'); ?></label></td>
				<td><?php  echo $this->lists['messages']; ?></td>
		</tr>
		
		<tr class="row0">
				<td align="right"><label id="resumesearchmsg" for="resumesearch"><?php echo JText::_('JS_RESUME_SEARCH'); ?></label></td>
				<td><?php  echo $this->lists['resumesearch']; ?></td>
		</tr>
		<tr class="row1">
				<td align="right"><label id="saveresumesearchmsg" for="saveresumesearch"><?php echo JText::_('JS_SAVE_RESUME_SEARCH'); ?></label></td>
				<td><?php  echo $this->lists['saveresumesearch']; ?></td>
		</tr>
		<tr class ="row0">
       			<td align="right"><?php echo JText::_('JS_ENFORCE_UNPUBLISH_JOB'); ?>  </td>
				<td><input type="checkbox" name="enforcestoppublishjob" value="1" <?php if(isset($this->package))  {if ($this->package->enforcestoppublishjob == '1') echo 'checked';} ?>/></td>
      	</tr>
		<tr class="row1">
		  <td valign="top" align="right"><label id="enforcestoppublishjobvaluemsg" for="enforcestoppublishjobvalue"><?php echo JText::_('JS_ENFORCE_UNPUBLISH_VALUE'); ?></label>&nbsp;</td>
		  <td><input class="inputbox validate-numeric" type="text" name="enforcestoppublishjobvalue" id="enforcestoppublishjobvalue" size="40" maxlength="255" value="<?php if(isset($this->package)) echo $this->package->enforcestoppublishjobvalue; ?>" /></td>
		</tr>
		<tr class="row0">
				<td align="right"><label id="enforcestoppublishjobtypemsg" for="enforcestoppublishjobtype"><?php echo JText::_('JS_ENFORCE_UNPUBLISH_JOB_TYPE'); ?></label></td>
				<td><?php  echo $this->lists['unpublishjobtype']; ?></td>
		</tr>
		<tr class="row1">
		  <td valign="top" align="right"><label id="packageexpireindaysmsg" for="packageexpireindays"><strong><?php echo JText::_('JS_PACKAGE_EXPIRE_IN_DAYS'); ?></label></strong>&nbsp;<font color="red">*</font></td>
		  <td><input class="inputbox required validate-numeric" type="text" name="packageexpireindays" id="packageexpireindays" size="40" maxlength="6" value="<?php if(isset($this->package)) echo $this->package->packageexpireindays; ?>" /></td>
		</tr>
		<tr class="row0">
			<td colspan="2" valign="top" align="center"><label id="descriptionmsg" for="description"><strong><?php echo JText::_('JS_DESCRIPTION'); ?></strong></label>&nbsp;<font color="red">*</font></td>
		</tr>
		
		<tr class="row1">
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
                <?php
                if(isset($this->paymentmethodlink)){
                    foreach($this->paymentmethodlink AS $paymethodlink) { ?>
                        <tr class="<?php echo $td[$k];$k=1-$k;?>">
                                <td colspan="1" valign="top" align="center">
									<strong>
										<?php echo $paymethodlink->title; ?> <?php if($paymethodlink->title=="Paypal"){echo JText::_('ACCOUNT');}elseif($paymethodlink->title=="Pagseguro"){echo JText::_('ACCOUNT');}else{echo JText::_('LINK');} ?>
									</strong>
									</td>
									<td>
										<?php if($paymethodlink->title=="Paypal") { ?>
											<input class="inputbox " type="hidden" name="link[]"  size="50" maxlength="255" value=""/>
										<?php }elseif($paymethodlink->title=="Pagseguro"){ ?>
											<input class="inputbox " type="hidden" name="link[]"  size="50" maxlength="255" value=""/>
										<?php }else { ?>
											<input class="inputbox " type="text" name="link[]"  size="50" maxlength="255" value="<?php echo $paymethodlink->link;?>"/>
										<?php } ?>
										
									</td>
                        </tr>
						<input type="hidden" name="paymentmethodids[]" value="<?php echo $paymethodlink->paymentmethod_id; ?>" />
						<input type="hidden" name="linkids[]" value="<?php echo $paymethodlink->linkid; ?>" />


                    <?php

                    }
                }
                ?>
                    
		<tr class="row0">
				<td align="right"><label id="statusmsg" for="status"><?php echo JText::_('JS_STATUS'); ?></label></td>
				<td><?php  echo $this->lists['status']; ?></td>				
		</tr>
      <tr>
        <td colspan="2" height="5"></td>
      <tr>
	<tr class="row1">
		<td colspan="2" align="center">
		<input class="button" type="submit" onclick="return validate_form(document.adminForm)" name="submit_app" onClick="return myValidate();" value="<?php echo JText::_('JS_SAVE_EMPLOYER_PACKAGE'); ?>" />
		</td>
	</tr>
    </table>


			<input type="hidden" name="id" id="id" value="<?php if(isset($this->package)) echo $this->package->id; ?>" />
			
			<input type="hidden" name="task" value="saveemployerpackage" />
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			<input type="hidden" name="uid" value="<?php echo $uid; ?>" />
			<input type="hidden" name="j_dateformat" id="j_dateformat" value="<?php  echo $js_scriptdateformat; ?>" />
  			<input type="hidden" name="Itemid" id="Itemid" value="<?php echo $this->Itemid; ?>" />
		  
		  
			

		</form>

		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			
		</td>
	</tr>
	
</table>				
