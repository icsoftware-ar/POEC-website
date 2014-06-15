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
			var isedit = document.getElementById("id");
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


<form action="index.php" method="post" name="adminForm" id="adminForm"   >
<input type="hidden" name="check" value="post"/>
    <table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
		<?php if($this->msg != ''){ ?>
		 <tr>
			<td colspan="2" align="center"><font color="red"><strong><?php echo JText::_($this->msg); ?></strong></font></td>
		  </tr>
		  <tr><td colspan="2" height="10"></td></tr>	
		<?php	}	?>
		<tr class="row0">
		  <td valign="top" align="right"><label id="titlemsg" for="title"><?php echo JText::_('JS_TITLE'); ?></label>&nbsp;<font color="red">*</font></td>
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
		  <td><input class="inputbox validate-discount" maxlength="6" type="text" name="discount" id="discount" size="40" maxlength="255" value="<?php if(isset($this->package)) echo $this->package->discount; ?>" /></td>
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
			<td><?php if($jversion == '1.5'){?><input class="inputbox " type="text" name="discountenddate" id="discountenddate" readonly size="10" maxlength="10" value="<?php if(isset($this->package)) echo date($this->config['date_format'],strtotime($this->package->discountenddate)); ?>" />
				<input type="reset" class="button" value="..." onclick="return showCalendar('discountenddate','<?php echo $js_dateformat; ?>');"  />
				<?php	}else 	echo JHTML::_('calendar', $stopdatevalue,'discountenddate', 'discountenddate',$js_dateformat,array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'19')); ?>
			</td>
		</tr>
		<tr class="row1">
		  <td valign="top" align="right"><label id="discountmessage_msg" for="discountmessage"><?php echo JText::_('JS_DISCOUNT_MESSAGE'); ?></label>&nbsp;</td>
		  <td><input class="inputbox" type="text" name="discountmessage" id="discountmessage" size="40" maxlength="255" value="<?php if(isset($this->package)) echo $this->package->discountmessage; ?>" /></td>
		</tr>
		
		<tr class="row0">
				<td align="right"><label id="discounttypemeg" for="discounttype"><?php echo JText::_('JS_DISCOUNT_TYPE'); ?></label></td>
				<td><?php  echo $this->lists['type']; ?>
				</td>
				
			  </tr>
		<tr class="row1">
		  <td valign="top" align="right"><label id="applyjobsmsg" for="applyjobs"><?php echo JText::_('JS_APPLY_JOBS'); ?></label>&nbsp;</td>
		  <td><input class="inputbox validate-numeric" type="text" name="applyjobs" id="applyjobs" size="40" maxlength="255" value="<?php if(isset($this->package)) echo $this->package->applyjobs; ?>" /><?php echo JText::_('MINUS_ONE_TO_UNLIMITED') ?></td>
		</tr>
		<tr class="row0">
		  <td valign="top" align="right"><label id="resumeallowmsg" for="resumeallow"><?php echo JText::_('JS_RESUME_ALLOW'); ?></label>&nbsp;</td>
		  <td><input class="inputbox validate-numeric" type="text" name="resumeallow" id="resumeallow" size="40" maxlength="255" value="<?php if(isset($this->package)) echo $this->package->resumeallow; ?>" /><?php echo JText::_('MINUS_ONE_TO_UNLIMITED') ?></td>
		</tr>
		<tr class="row1">
		  <td valign="top" align="right"><label id="coverlettersallowmsg" for="coverlettersallow"><?php echo JText::_('JS_COVERLETTER_ALLOW'); ?></label>&nbsp;</td>
		  <td><input class="inputbox validate-numeric" type="text" name="coverlettersallow" id="coverlettersallow" size="40" maxlength="255" value="<?php if(isset($this->package)) echo $this->package->coverlettersallow; ?>" /><?php echo JText::_('MINUS_ONE_TO_UNLIMITED') ?></td>
		</tr>
		<tr class="row0">
		  <td valign="top" align="right"><label id="goldresumemsg" for="goldresume"><?php echo JText::_('JS_GOLD_RESUME'); ?></label>&nbsp;</td>
		  <td><input class="inputbox validate-numeric" type="text" name="goldresume" id="goldresume" size="40" maxlength="255" value="<?php if(isset($this->package)) echo $this->package->goldresume; ?>" /><?php echo JText::_('MINUS_ONE_TO_UNLIMITED') ?></td>
		</tr>
		<tr class="row1">
		  <td valign="top" align="right"><label id="goldresumeexpireindaysmsg" for="goldresumeexpireindays"><?php echo JText::_('JS_GOLD_RESUME_EXPIRE_IN_DAYS'); ?></label>&nbsp;</td>
		  <td><input class="inputbox validate-numeric" type="text" name="goldresumeexpireindays" id="goldresumeexpireindays" size="40" maxlength="6" value="<?php if(isset($this->package)) echo $this->package->goldresumeexpireindays; ?>" /></td>
		</tr>
		<tr class="row0">
		  <td valign="top" align="right"><label id="featuredresumemsg" for="featuredresume"><?php echo JText::_('JS_FEATURED_RESUME'); ?></label>&nbsp;</td>
		  <td><input class="inputbox validate-numeric" type="text" name="featuredresume" id="featuredresume" size="40" maxlength="255" value="<?php if(isset($this->package)) echo $this->package->featuredresume; ?>" /><?php echo JText::_('MINUS_ONE_TO_UNLIMITED') ?></td>
		</tr>
		
		<tr class="row1">
		  <td valign="top" align="right"><label id="freaturedresumeexpireindaysmsg" for="freaturedresumeexpireindays"><?php echo JText::_('JS_FEATURED_RESUME_EXPIRE_IN_DAYS'); ?></label>&nbsp;</td>
		  <td><input class="inputbox validate-numeric" type="text" name="freaturedresumeexpireindays" id="freaturedresumeexpireindays" size="40" maxlength="6" value="<?php if(isset($this->package)) echo $this->package->freaturedresumeexpireindays; ?>" /></td>
		</tr>
		
		<tr class="row0">
				<td align="right"><label id="jobalertsettingmsg" for="jobalertsetting"><?php echo JText::_('JS_JOB_ALERT_SETTING'); ?></label></td>
				<td><?php  echo $this->lists['jobalertsetting']; ?></td>
		</tr>
		<tr class="row1">
				<td align="right"><label id="jobsearchmsg" for="jobsearch"><?php echo JText::_('JS_JOB_SEARCH'); ?></label></td>
				<td><?php  echo $this->lists['jobsearch']; ?></td>
		</tr>
		<tr class="row0">
		<td align="right"><label id="savejobsearchmsg" for="savejobsearch"><?php echo JText::_('JS_SAVE_JOB_SEARCH'); ?></label></td>
				<td><?php  echo $this->lists['savejobsearch']; ?></td>
		</tr>
		<tr class="row1">
		  <td valign="top" align="right"><label id="packageexpireindaysmsg" for="packageexpireindays"><strong><?php echo JText::_('JS_PACKAGE_EXPIRE_IN_DAYS'); ?></label></strong>&nbsp;<font color="red">*</font></td>
		  <td><input class="inputbox required validate-numeric" type="text" name="packageexpireindays" id="packageexpireindays" size="40" maxlength="6" value="<?php if(isset($this->package)) echo $this->package->packageexpireindays; ?>" /></td>
		</tr>
		<tr class="row0">
		<td align="right"><label id="shortdetailsmsg" for="shortdetails"><?php echo JText::_('JS_SHORT_DETAILS'); ?></label></td>
		<td><textarea class="inputbox " name="shortdetails" id="shortdetails" cols="60" rows="5"><?php if(isset($this->package)) echo $this->package->shortdetails; ?></textarea></td>
		</tr>
		<tr ><td height="10" colspan="2"></td></tr>
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
				<td align="right"><label id="statustype" for="status"><?php echo JText::_('JS_STATUS'); ?></label></td>
				<td><?php  echo $this->lists['status']; ?>
				</td>
				
			  </tr>
			
					
      <tr>
        <td colspan="2" height="5"></td>
      <tr>
	<tr class="row1">
		<td colspan="2" align="center">
		<input class="button" type="submit" onclick="return validate_form(document.adminForm)" name="submit_app" onClick="return myValidate();" value="<?php echo JText::_('JS_SAVE_JOBSEEKER_PACKAGE'); ?>" />
		</td>
	</tr>
    </table>


			<input type="hidden" name="id" value="<?php if(isset($this->package)) echo $this->package->id; ?>" />
			  <input type="hidden" name="layout" value="jobseekerpackages" />
			<input type="hidden" name="task" value="savejobseekerpackage" />
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			<input type="hidden" name="uid" value="<?php echo $uid; ?>" />
			<input type="hidden" name="j_dateformat" id="j_dateformat" value="<?php  echo $js_scriptdateformat; ?>" />
		
		  <input type="hidden" name="Itemid" id="Itemid" value="<?php echo $this->Itemid; ?>" />
		
			<input type="hidden" name="check" value="" />
		  
			

		</form>

		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			
		</td>
	</tr>
	
</table>				
