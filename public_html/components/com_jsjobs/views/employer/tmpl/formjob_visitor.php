<?php
/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 + Created by:          Ahmad Bilal
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

$document =& JFactory::getDocument();
$document->addStyleSheet('components/com_jsjobs/themes/'.$this->config['theme']);
$document->addStyleSheet('components/com_jsjobs/css/token-input-jsjobs.css');

$version = new JVersion;
$joomla = $version->getShortVersion();
$jversion = substr($joomla,0,3);

	if($jversion < 3){
		JHtml::_('behavior.mootools');
		$document->addScript('components/com_jsjobs/js/jquery.js');
	}else{
		JHtml::_('behavior.framework');
		JHtml::_('jquery.framework');
	}	
	$document->addScript('components/com_jsjobs/js/jquery.tokeninput.js');


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
<style type="text/css">
	#coordinatebutton{
		float:right;
	}
	#coordinatebutton .cbutton{
		border-radius: 10px 10px 10px 10px;
		background: gray;
		color:ghostwhite;
		padding:1;
		font-size: 1.08em;
	}
	#coordinatebutton .cbutton:hover{
		background:black;
		color:ghostwhite;
		font-size: 1.08em;
		padding:1;
	}
</style>
<?php if ($this->config['offline'] == '1'){ ?>
	<div id="toppanel">
		<div id="tp_header" <?php if($this->config['topimage'] == 0) echo 'style="background:none;"';?>>
			<span id="tp_title"><?php echo $this->config['title'];?></span>
		</div>
	</div>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<div id="message"><b><?php echo $this->config['offline_text']; ?></b></div>
	</div>
<?php }else{ ?>
<script language="javascript">
	
function checkUrl(obj) {
		if(!obj.value.match(/^http[s]?\:\/\//))
			obj.value='http://'+obj.value;
}
window.addEvent('domready', function(){
   document.formvalidator.setHandler('url', function(value) {
		if(value.match(/^(http|https|ftp)\:\/\/\w+([\.\-]\w+)*\.\w{2,4}(\:\d+)*([\/\.\-\?\&\%\#]\w+)*\/?$/i) ||
		value.match(/^mailto\:\w+([\.\-]\w+)*\@\w+([\.\-]\w+)*\.\w{2,4}$/i))
		{
			return true;
		}
		else
		{
		return false;
		}	   
	   
   });
});	

window.addEvent('domready', function(){
   document.formvalidator.setHandler('since', function(value) {
		var date_since_make = new Array();
		var split_since_value=new Array();
	   
		f = document.adminForm;
		var returnvalue = true;
		var today=new Date();
			today.setHours(0,0,0,0);				
		
					var since_string = document.getElementById("companysince").value;
					var format_type = document.getElementById("j_dateformat").value;
					if(format_type=='d-m-Y'){
						split_since_value=since_string.split('-');

						date_since_make['year']=split_since_value[2];
						date_since_make['month']=split_since_value[1];
						date_since_make['day']=split_since_value[0];


					}else if(format_type=='m/d/Y'){
						split_since_value=since_string.split('/');
						date_since_make['year']=split_since_value[2];
						date_since_make['month']=split_since_value[0];
						date_since_make['day']=split_since_value[1];


					}else if(format_type=='Y-m-d'){

						split_since_value=since_string.split('-');

						date_since_make['year']=split_since_value[0];
						date_since_make['month']=split_since_value[1];
						date_since_make['day']=split_since_value[2];
					}
					var sincedate = new Date(date_since_make['year'],date_since_make['month']-1,date_since_make['day']);		
						
					if (sincedate > today ){
						returnvalue = false;
					}
					return returnvalue;
   });
});	
	
	
function hideShowRange(hideSrc, showSrc, showName, showVal){
    document.getElementById(hideSrc).style.visibility = "hidden";
    document.getElementById(showSrc).style.visibility = "visible";
    document.getElementById(showName).value = showVal;
}

function check_start_stop_job_publishing_dates(){
		var date_start_make = new Array();
		var date_stop_make = new Array();
		var split_start_value=new Array();
		var split_stop_value=new Array();
		var returnvalue = true;

			var start_string = document.getElementById("job_startpublishing").value;
			var stop_string = document.getElementById("job_stoppublishing").value;
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
   document.formvalidator.setHandler('checkstartdate', function(value) {
		var date_start_make = new Array();
		var split_start_value=new Array();
		f = document.adminForm;
			var isedit = document.getElementById("jobid");
			if(isedit.value!="" && isedit.value!=0  ) {
				var return_value=check_start_stop_job_publishing_dates();
				return return_value;
			}else{
				var returnvalue = true;
				var today=new Date()
					today.setHours(0,0,0,0);				
					
					var start_string = document.getElementById("job_startpublishing").value;
					var format_type = document.getElementById("j_dateformat").value;
					if(format_type=='d-m-Y'){
						split_start_value=start_string.split('-');

						date_start_make['year']=split_start_value[2];
						date_start_make['month']=split_start_value[1];
						date_start_make['day']=split_start_value[0];


					}else if(format_type=='m/d/Y'){
						split_start_value=start_string.split('/');
						date_start_make['year']=split_start_value[2];
						date_start_make['month']=split_start_value[0];
						date_start_make['day']=split_start_value[1];


					}else if(format_type=='Y-m-d'){

						split_start_value=start_string.split('-');

						date_start_make['year']=split_start_value[0];
						date_start_make['month']=split_start_value[1];
						date_start_make['day']=split_start_value[2];
					}
						
					var startpublishingdate = new Date(date_start_make['year'],date_start_make['month']-1,date_start_make['day']);		
						
					if (today > startpublishingdate ){
						returnvalue = false;
					}
					return returnvalue;
					
				
			}
		
   });
});	
window.addEvent('domready', function(){
   document.formvalidator.setHandler('checkstopdate', function(value) {
		var return_value=check_start_stop_job_publishing_dates();
		return return_value;
	   
   });
});	


window.addEvent('domready', function(){
   document.formvalidator.setHandler('checkagefrom', function(value) {
			var optionagefrom = document.getElementById("agefrom");
			var strUser = optionagefrom.options[optionagefrom.selectedIndex].text;	  
			var range_from_value = parseInt(strUser, 10); 

			var optionageto = document.getElementById("ageto");
			var strUserTo = optionageto.options[optionageto.selectedIndex].text;	  
			var range_from_to = parseInt(strUserTo, 10); 
			if(range_from_value > range_from_to ){
					return false;
			}else if(range_from_value == range_from_to ){
				return true;
			}
			return true;

   });
});	

window.addEvent('domready', function(){
   document.formvalidator.setHandler('salaryrangefrom', function(value) {
			var optionsalaryrangefrom = document.getElementById("salaryrangefrom");
			var strUser = optionsalaryrangefrom.options[optionsalaryrangefrom.selectedIndex].text;	  
			var salaryrange_from_value = parseInt(strUser, 10); 

			var optionsalaryrangeto = document.getElementById("salaryrangeto");
			var strUserTo = optionsalaryrangeto.options[optionsalaryrangeto.selectedIndex].text;	  
			var salaryrangerange_from_to = parseInt(strUserTo, 10); 
			if(salaryrange_from_value > salaryrangerange_from_to ){
					return false;
			}else if(salaryrange_from_value == salaryrangerange_from_to ){
				return true;
			}
			return true;

   });
});	

window.addEvent('domready', function(){
   document.formvalidator.setHandler('checkageto', function(value) {
			var optionagefrom = document.getElementById("agefrom");
			var strUser = optionagefrom.options[optionagefrom.selectedIndex].text;	  
			var range_from_value = parseInt(strUser, 10); 

			var optionageto = document.getElementById("ageto");
			var strUserTo = optionageto.options[optionageto.selectedIndex].text;	  
			var range_from_to = parseInt(strUserTo, 10); 
			if( range_from_to < range_from_value  ){
					return false;
			}else if( range_from_to == range_from_value  ){
				return true;
			}
			return true;

   });
});	
window.addEvent('domready', function(){
   document.formvalidator.setHandler('salaryrangeto', function(value) {
			var optionsalaryrangefrom = document.getElementById("salaryrangefrom");
			var strUser = optionsalaryrangefrom.options[optionsalaryrangefrom.selectedIndex].text;	  
			var salaryrange_from_value = parseInt(strUser, 10); 

			var optionsalaryrangeto = document.getElementById("salaryrangeto");
			var strUserTo = optionsalaryrangeto.options[optionsalaryrangeto.selectedIndex].text;	  
			var salaryrangerange_from_to = parseInt(strUserTo, 10); 
			if(salaryrangerange_from_to < salaryrange_from_value ){
					return false;
			}else if(salaryrangerange_from_to == salaryrange_from_value){
				return true;
			}
			return true;

   });
});	
	



function hasClass(el, selector) {
   var className = " " + selector + " ";
  
   if ((" " + el.className + " ").replace(/[\n\t]/g, " ").indexOf(className) > -1) {
    return true;
   }
   return false;
  }

function myValidate(f) {
	var msg = new Array();
    var returnvalue = true;

    if (document.formvalidator.isValid(f)) {
		f.check.value='<?php if(($jversion == '1.5') || ($jversion == '2.5')) echo JUtility::getToken(); else echo  JSession::getFormToken(); ?>';//send token
    } else {
			msg.push('<?php echo JText::_( 'JS_SOME_VALUES_ARE_NOT_ACCEPTABLE_PLEASE_RETRY');?>');
			var element_since = document.getElementById('companysince');                
			if(hasClass(element_since,'invalid')){
					msg.push('<?php echo JText::_('JS_COMPANY_START_DATE_MUST_BE_LESS_THEN_TODAY'); ?>');
            }
			var element_agefrom = document.getElementById('agefrom');                
			if(hasClass(element_agefrom,'invalid')){
					msg.push('<?php echo JText::_('JS_AGE_FROM_MUST_BE_LESS_THEN_AGE_TO'); ?>');
            }
            
			var element_ageto = document.getElementById('ageto');                
			if(hasClass(element_ageto,'invalid')){
					msg.push('<?php echo JText::_('JS_AGE_TO_MUST_BE_GREATER_THEN_AGE_FROM'); ?>');
            }
			var element_salaryrangefrom = document.getElementById('salaryrangefrom');                
			if(hasClass(element_salaryrangefrom,'invalid')){
					msg.push('<?php echo JText::_('JS_SALARY_RANGE_FROM_MUST_BE_LESS_THEN_SALARY_RANGE_TO'); ?>');
            }
			var element_salaryrangeto = document.getElementById('salaryrangeto');                
			if(hasClass(element_salaryrangeto,'invalid')){
					msg.push('<?php echo JText::_('JS_SALARY_RANGE_TO_MUST_BE_GREATER_THEN_SALARY_RANGE_FROM'); ?>');
            }
			var element_job_startpublishing = document.getElementById('job_startpublishing');                
			if(hasClass(element_job_startpublishing,'invalid')){
					msg.push('<?php echo JText::_('JS_PLEASE_ENTER_A_VALID_START_PUBLISHING_DATE'); ?>');
            }
			var element_job_stoppublishing = document.getElementById('job_stoppublishing');                
			if(typeof element_job_stoppublishing !== 'undefined' && element_job_stoppublishing !== null) {			
				if(hasClass(element_job_stoppublishing,'invalid')){
						msg.push('<?php echo JText::_('JS_PLEASE_ENTER_A_VALID_STOP_PUBLISHING_DATE'); ?>');
				}
			}

            alert (msg.join('\n'));			
            
			return false;
    }
	var comdescription = tinyMCE.get('companydescription').getContent();
	if(comdescription == '') {
			msg.push('<?php echo JText::_('JS_PLEASE_ENTER_COMPANY_DESCRIPTION'); ?>');
			alert (msg.join('\n'));			
			return false;
	}
	var jobdescription = tinyMCE.get('description').getContent();
	if(jobdescription == ''){
			msg.push('<?php echo JText::_('JS_PLEASE_ENTER_JOB_DESCRIPTION'); ?>');
			alert (msg.join('\n'));			
			return false;
	}
	return true;
}



function CheckDate() {
    //alert('date');
    f = document.adminForm;
    var returnvalue = true;
    var today=new Date()
    if ((today.getMonth()+1) < 10)
        var tomonth = "0"+(today.getMonth()+1);
    else
        var tomonth = (today.getMonth()+1);

    if ((today.getDate()) < 10)
        var day = "0"+(today.getDate());
    else
        var day = (today.getDate());

    var todate = (today.getYear()+1900)+"-"+tomonth+"-"+day;

//alert(todate);
    if(f.startpublishing.value != ""){
        if (todate > f.startpublishing.value ){
            alert('Please enter a valid start publishing date');
            f.startpublishing.value="";
            returnvalue = false;
        }
    }
    if(f.startpublishing.value >= f.stoppublishing.value){
        alert("Please enter a valid stop publishing date");
        f.stoppublishing.value="";
        returnvalue = false;
    }
    return returnvalue;

}
</script>

	<div id="toppanel">
		<div id="tp_header" <?php if($this->config['topimage'] == 0) echo 'style="background:none;"';?>>
			<span id="tp_title"><?php echo $this->config['title'];?></span>
			<span id="tp_curloc">
				<?php if ($this->config['cur_location'] == 1) {
						if (isset($this->company)){
							echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=mycompanies&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_MY_COMPANIES'); ?></a> > <?php echo JText::_('JS_COMPNAY_INFO');
						}else{
							echo JText::_('JS_CUR_LOC'); ?> :  <?php echo JText::_('JS_NEW_JOB_INFO');
						}
				} ?>
			</span>
		</div>
		<div id="tp_links">
			<?php 
			
			if (sizeof($this->jobseekerlinks) != 0){
				foreach($this->jobseekerlinks as $lnk)	{ ?>
					<a class="<?php if($lnk[2] == 1)echo 'first'; elseif($lnk[2] == -1)echo 'last';  ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
				<?php }
			}
			if (sizeof($this->employerlinks) != 0){
				foreach($this->employerlinks as $lnk)	{ ?>
					<a class="<?php if($lnk[2] == 1)echo 'first'; elseif($lnk[2] == -1)echo 'last';  ?>" href="<?php echo $lnk[0]; ?>"> <?php echo $lnk[1]; ?></a>
				<?php }
			}
			?>
		</div>
		<div id="tp_heading">
			<span id="tp_headingtext">
				<span id="tp_headingtext_left"></span>
				<span id="tp_headingtext_center"><?php echo JText::_('JS_COMPNAY_INFO');  ?></span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>
<?php
if ($this->config['visitor_can_post_job'] == 1) { // visitor can post job

?>
<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data"  onSubmit="return myValidate(this);">
    <table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform"><tr>
		<?php
		$i = 0;// for user field
		//if(!isset($this->job) AND !empty($this->job->jobid)){
		foreach($this->companyfieldsordering as $field){
			//echo '<br> uf'.$field->field;
			switch ($field->field) {
				case "jobcategory": ?>
					<tr>
				        <td valign="top" align="right"><label  id="jobcategorymsg" for="jobcategory"><?php echo JText::_('JS_CATEGORIES'); ?></label>&nbsp;<font color="red">*</font></td>
				        <td><?php echo $this->companylists['jobcategory']; ?></td>
					</tr>
				<?php break;
				case "name": ?>
					<tr>
				        <td width="20%" align="right"><label id="companynamemsg" for="companyname"><?php echo JText::_('JS_COMPANYNAME'); ?></label>&nbsp;<font color="red">*</font></td>
						<td width="60%"><input class="inputbox required" type="text" name="companyname" id="companyname" size="20" maxlength="255" value="<?php if(isset($this->company)) echo $this->company->name; ?>" /></td>
					</tr>
				<?php break;
				case "url": ?>
					  <?php if ( $field->isvisitorpublished == 1 ) { ?>
						<tr>
							<td align="right"><label id="companyurlmsg" for="companyurl"><?php echo JText::_('JS_URL'); ?></label></td>
							<td><input class="inputbox inputbox validate-url" type="text" name="companyurl" size="20" maxlength="100" onblur="checkUrl(this);" value="<?php if(isset($this->company)) echo trim ($this->company->url); ?>" /></td>
						</tr>
					  <?php } ?>
				<?php break;
				case "contactname": ?>
					<tr>
				        <td align="right"><label id="companycontactnamemsg" for="companycontactname"><?php echo JText::_('JS_CONTACTNAME'); ?></label>&nbsp;<font color="red">*</font></td>
				        <td><input class="inputbox required" type="text" name="companycontactname" id="companycontactname" size="20" maxlength="100" value="<?php if(isset($this->company)) echo $this->company->contactname; ?>" /></td>
				    </tr>
				<?php break;
				case "contactphone": ?>
					<?php if ( $field->isvisitorpublished == 1 ) { ?>
						<tr>
							<td align="right"><label id="companycontactphonemsg" for="companycontactphone"><?php echo JText::_('JS_CONTACTPHONE'); ?></label></td>
							<td><input class="inputbox" type="text" name="companycontactphone" size="20" maxlength="100" value="<?php if(isset($this->company)) echo $this->company->contactphone; ?>" /></td>
						</tr>
					<?php } ?>
				<?php break;
				case "contactfax": ?>
					  <?php if ( $field->isvisitorpublished == 1 ) { ?>
						<tr>
							<td align="right"><label id="companyfaxmsg" for="companyfax"><?php echo JText::_('JS_CONTACTFAX'); ?></label></td>
							<td><input class="inputbox" type="text" name="companyfax" size="20" maxlength="100" value="<?php if(isset($this->company)) echo $this->company->companyfax; ?>" /></td>
						</tr>
					  <?php } ?>
				<?php break;
				case "contactemail": ?>
					<tr>
				        <td align="right"><label id="companycontactemailmsg" for="companycontactemail"><?php echo JText::_('JS_CONTACTEMAIL');?></label>&nbsp;<font color="red">*</font></td>
				        <td><input class="inputbox required validate-email" type="text" name="companycontactemail" id="companycontactemail" size="20" maxlength="100" value="<?php if(isset($this->company)) echo $this->company->contactemail; ?>" /></td>
				    </tr>
				<?php break;
				case "since": ?>
					<?php if ( $field->isvisitorpublished == 1 ) { ?>
						<?php
							$startdatevalue = '';
							if(isset($this->company)) $startdatevalue = date($this->config['date_format'],strtotime($this->company->since)); ?>
							<tr>
							<td  valign="top" align="right"><?php echo JText::_('JS_SINCE'); ?>:</td>
								<td>
									<?php if($jversion == '1.5'){ ?>
										<input class="inputbox validate-since" type="text" name="companysince" id="companysince" readonly size="10" maxlength="10" value = "<?php if (isset($this->company)) echo date($this->config['date_format'],strtotime($this->company->since));?>" />
										<input type="reset" class="button" value="..." onclick="return showCalendar('companysince','<?php echo $js_dateformat; ?>');" onBlur="CheckDate('since');" />
									<?php }elseif(isset($this->company)){
												echo JHTML::_('calendar', date($this->config['date_format'],  strtotime($this->company->since)),'companysince', 'companysince',$js_dateformat,array('class'=>'inputbox validate-since', 'size'=>'10',  'maxlength'=>'19'));
										  }else
												echo JHTML::_('calendar','','companysince', 'companysince',$js_dateformat,array('class'=>'inputbox validate-since', 'size'=>'10',  'maxlength'=>'19'));
									?>
								</td>
							</tr>
					<?php } ?>
				<?php break;
				case "companysize": ?>
						<?php if ( $field->isvisitorpublished == 1 ) { ?>
							<tr>
								<td valign="top" align="right"><label id="companysize" for="companysize"><?php echo JText::_('JS_COMPANY_SIZE'); ?></label></td>
								<td><input class="inputbox" type="text" name="companysize" id="companysize" size="20" maxlength="20" value="<?php if(isset($this->company)) echo $this->company->companysize; ?>" /></td>
							</tr>
						<?php } ?>
				<?php break;
				case "income": ?>
					<?php if ( $field->isvisitorpublished == 1 ) { ?>
						<tr>
							<td valign="top" align="right"><label id="companyincomemsg" for="companyincome"><?php echo JText::_('JS_INCOME'); ?></label></td>
							<td><input class="inputbox validate-numeric" type="text" name="companyincome" id="companyincome" size="20" maxlength="10" value="<?php if(isset($this->company)) echo $this->company->income; ?>" /></td>
						</tr>
					<?php } ?>
				<?php break;
				case "description": ?>
					  <?php if ( $field->isvisitorpublished == 1 ) { ?>
						<?php if ( $this->config['comp_editor'] == '1' ) { ?>
							<tr><td height="10" colspan="2"></td></tr>
							<tr>
								<td colspan="2" valign="top" align="center"><label id="descriptionmsg" for="description"><strong><?php echo JText::_('JS_DESCRIPTION'); ?></strong></label>&nbsp;<font color="red">*</font></td>
							</tr>
							<tr>
								<td colspan="2" align="center">
								<?php
									$editor =& JFactory::getEditor();
									if(isset($this->company))
										echo $editor->display('companydescription', $this->company->description, '100%', '100%', '60', '20', false);
									else
										echo $editor->display('companydescription', '', '100%', '100%', '60', '20', false);
								?>
								</td>
							</tr>
					<?php } else {?>
								<tr>
									<td valign="top" align="right"><label id="companydescriptionmsg" for="companydescription"><?php echo JText::_('JS_DESCRIPTION'); ?></label>&nbsp;<font color="red">*</font></td>
									<td><textarea class="inputbox required" name="companydescription" id="companydescription" cols="60" rows="5"><?php if(isset($this->company)) echo $this->company->description; ?></textarea></td>
								</tr>
							<?php } ?>
					  <?php } ?>
				<?php break;
				case "city": ?>
					<?php if ($this->config['comp_city'] == 1) { ?>
						<?php if ( $field->isvisitorpublished == 1 ) { ?>
							<tr>
								<td align="right"><label id="companycitymsg" for="companycity"><?php echo JText::_('JS_CITY'); ?></label></td>
								<td id="company_city">
								<input class="inputbox" type="text" name="companycity" id="companycity" size="40" maxlength="100" value="" />
								<input class="inputbox" type="hidden" name="companycityforedit" id="companycityforedit" size="40" maxlength="100" value="<?php if(isset($this->vmultiselecteditcompany)) echo $this->vmultiselecteditcompany; ?>" />
								
								</td>
							</tr>
						<?php } ?>
					<?php } ?>
				<?php break;
				case "zipcode": ?>
						<?php if ($this->config['comp_zipcode'] == 1) { ?>
							<?php if ( $field->isvisitorpublished == 1 ) { ?>
								<tr>
									<td align="right"><label id="companyzipcodemsg" for="companyzipcode"><?php echo JText::_('JS_ZIPCODE'); ?></label></td>
									<td><input class="inputbox" type="text" name="companyzipcode" size="20" maxlength="100" value="<?php if(isset($this->company)) echo $this->company->zipcode; ?>" /></td>
								</tr>
							<?php } ?>
						<?php } ?>
				<?php break;
				case "address1": ?>
						<?php if ( $field->isvisitorpublished == 1 ) { ?>
							<tr>
								<td align="right"><label id="companyaddress1msg" for="companyaddress1"><?php echo JText::_('JS_ADDRESS1'); ?></label></td>
								<td><input class="inputbox" type="text" name="companyaddress1" size="20" maxlength="100" value="<?php if(isset($this->company)) echo $this->company->address1; ?>" /></td>
							</tr>
						<?php } ?>
				<?php break;
				case "address2": ?>
						<?php if ( $field->isvisitorpublished == 1 ) { ?>
							<tr>
								<td align="right"><label id="companyaddress2msg" for="companyaddress2"><?php echo JText::_('JS_ADDRESS2'); ?></label></td>
								<td><input class="inputbox" type="text" name="companyaddress2" size="20" maxlength="100" value="<?php if(isset($this->company)) echo $this->company->address2; ?>" /></td>
							</tr>
						<?php } ?>
				<?php break;
				case "logo": ?>
					  <?php if ( $field->isvisitorpublished == 1 ) { ?>
						<?php if (isset($this->company)){
							if($this->company->logofilename != '') {?>
								<tr>
									<td></td><td><input type='checkbox' name='companydeletelogo' value='1'><?php echo JText::_('JS_DELETE_LOGO_FILE') .'['.$this->company->logofilename.']'; ?></td>
								</tr>
							<?php }
						}?>
						<tr>
							<td align="right" ><label id="logomsg" for="logo">	<?php echo JText::_('JS_COMPANY_LOGO'); ?>	</label></td>
							<td><input type="file" class="inputbox" name="companylogo" size="20" maxlenght='30'/>
							<br><small><?php echo JText::_('JS_MAXIMUM_WIDTH');?> : 200px)</small>
							<br><small><?php echo JText::_('JS_MAXIMUM_FILE_SIZE').' ('.$this->config['company_logofilezize']; ?>KB)</small></td>
						</tr>
					  <?php } ?>
				<?php break;
				case "smalllogo": ?>
					  <?php if ( $field->isvisitorpublished == 1 ) { ?>
						<?php if (isset($this->company))
								if($this->company->smalllogofilename != '') {?>
									<tr>
										<td></td><td><input type='checkbox' name='companydeletesmalllogo' value='1'><?php echo JText::_('JS_DELETE_SMALL_LOGO_FILE') .'['.$this->company->smalllogofilename.']'; ?></td>
									</tr>
								<?php } ?>
							<tr>
								<td align="right" >	<label id="companysmalllogomsg" for="companysmalllogo"><?php echo JText::_('JS_COMPANY_SMALL_LOGO'); ?>	</label></td>
								<td><input type="file" class="inputbox" name="companysmalllogo" size="20" maxlenght='30'/></td>
							</tr>
					  <?php } ?>
				<?php break;
				case "aboutcompany": ?>
					  <?php if ( $field->isvisitorpublished == 1 ) { ?>
						<?php if (isset($this->company))
								if($this->company->aboutcompanyfilename != '') {?>
									<tr>
										<td></td><td><input type='checkbox' name='companydeleteaboutcompany' value='1'><?php echo JText::_('JS_DELETE_ABOUT_COMPANY_FILE') .'['.$this->company->aboutcompanyfilename.']'; ?></td>
									</tr>
								<?php } ?>
							<tr>
								<td align="right" >	<label id="companyaboutcompanymsg" for="companyaboutcompany"><?php echo JText::_('JS_ABOUT_COMPANY'); ?>	</label></td>
								<td><input type="file" class="inputbox" name="companyaboutcompany" size="20" maxlenght='30'/></td>
							</tr>
					  <?php } ?>
				<?php break;

				default:
					if ( $field->isvisitorpublished == 1 ) {
						if (isset($this->companyuserfields))
						foreach($this->companyuserfields as $ufield){
							if($field->field == $ufield[0]->id) {
								$userfield = $ufield[0];
								$i++;
								echo "<tr><td valign='top' align='right'>";
								if($userfield->required == 1){
									echo "<label id=".$userfield->name."msg for='companyuserfields_$i'>$userfield->title</label>&nbsp;<font color='red'>*</font>";
									if($userfield->type == 'emailaddress')
										$cssclass = "class ='inputbox required validate-email' ";
									else
										$cssclass = "class ='inputbox required' ";

								}else{
									echo $userfield->title; $cssclass = "class='inputbox' ";
								}
								echo "</td><td>"	;

//									echo '<br> ft '.$userfield->type;
								$readonly = $userfield->readonly ? ' readonly="readonly"' : '';
		   						$maxlength = $userfield->maxlength ? 'maxlength="'.$userfield->maxlength.'"' : '';
								if(isset($ufield[1])){ $fvalue = $ufield[1]->data; $userdataid = $ufield[1]->id;}  else {$fvalue=""; $userdataid = ""; }
								echo '<input type="hidden" id="companyuserfields_'.$i.'_id" name="companyuserfields_'.$i.'_id"  value="'.$userfield->id.'"  />';
								echo '<input type="hidden" id="companyuserdata_'.$i.'_id" name="companyuserdata_'.$i.'_id"  value="'.$userdataid.'"  />';
								switch( $userfield->type ) {
									case 'text':
										echo '<input type="text" id="companyuserfields_'.$i.'" name="companyuserfields_'.$i.'" size="'.$userfield->size.'" value="'. $fvalue .'" '.$cssclass .$maxlength . $readonly . ' />';
										break;
									case 'emailaddress':
										echo '<input type="text" id="companyuserfields_'.$i.'" name="companyuserfields_'.$i.'" size="'.$userfield->size.'" value="'. $fvalue .'" '.$cssclass .$maxlength . $readonly . ' />';
										break;
									case 'date':
										if($jversion == '1.5'){
											echo '<input type="text" id="companyuserfields_'.$i.'" name="companyuserfields_'.$i.'" size="'.$userfield->size.'" value="'. $fvalue .'" '.$cssclass .$maxlength . $readonly . ' />';
											?><input type="reset" class="button" value="..." onclick="return showCalendar('companyuserfields_<?php echo $i; ?>','%Y-%m-%d');" /><?php
										}else{
											if($cssclass == "class ='inputbox required' ") $css = 'inputbox required';else $css="";
											//echo JHTML::_('calendar', date($this->config['date_format'],  strtotime($fvalue)),'companyuserfields_'.$i, 'companyuserfields_'.$i,'%Y-%m-%d',array('class'=>$css, 'size'=>'10',  'maxlength'=>$maxlength));
											echo JHTML::_('calendar', $fvalue,'companyuserfields_'.$i, 'companyuserfields_'.$i,'%Y-%m-%d',array('class'=>$css, 'size'=>'10',  'maxlength'=>$maxlength));
										}
										break;
									case 'textarea':
										echo '<textarea name="companyuserfields_'.$i.'" id="companyuserfields_'.$i.'_field" cols="'.$userfield->cols.'" rows="'.$userfield->rows.'" '.$readonly.$cssclass.'>'.$fvalue.'</textarea>';
										break;
									case 'checkbox':
										echo '<input type="checkbox" name="companyuserfields_'.$i.'" id="companyuserfields_'.$i.'_field" value="1" '.  'checked="checked"' .'/>';
										break;
									case 'select':
										$htm = '<select name="companyuserfields_'.$i.'" id="companyuserfields_'.$i.'" >';
										if (isset ($ufield[2])){
											foreach($ufield[2] as $opt){
												if ($opt->id == $fvalue)
													$htm .= '<option value="'.$opt->id.'" selected="yes">'. $opt->fieldtitle .' </option>';
												else
													$htm .= '<option value="'.$opt->id.'">'. $opt->fieldtitle .' </option>';
											}
										}
										$htm .= '</select>';
										echo $htm;
										break;
									case 'editortext':
										$editor =& JFactory::getEditor();
										if(isset($this->company))
											echo $editor->display("companyuserfields_$i", $fvalue, '100%', '100%', '60', '20', false);
										else
											echo $editor->display("companyuserfields_$i", '', '100%', '100%', '60', '20', false);

								}
								echo '</td></tr>';
							}
						}

					}
			}

		}
		echo '<input type="hidden" id="companyuserfields_total" name="companyuserfields_total"  value="'.$i.'"  />';
		?>
    
    <tr>
        <td colspan="<?php echo $k*2;?>" height="10"></td>
    </tr>
    <tr>
        <td class="<?php echo $this->theme['heading']; ?>" colspan="2" align="center">
			<div id="tp_heading">
				<span id="tp_headingtext">
					<?php echo JText::_('JS_JOB_INFO');  ?>
				</span>
			</div>
        </td>
    </tr>
    </table>


	<?php
		if(isset($this->company)) {
			if (($this->company->created=='0000-00-00 00:00:00') || ($this->company->created==''))
				$curdate = date('Y-m-d H:i:s');
			else
				$curdate = $this->company->created;
		}else
			$curdate = date('Y-m-d H:i:s');
	?>
                <input type="hidden" name="created" value="<?php echo $curdate; ?>" />
                <input type="hidden" name="uid" value="0" />
                <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                <input type="hidden" name="task" value="savejobvisitor" />
                <input type="hidden" name="check" value="" />
                <?php if(!empty($this->companypackagedetail)) echo '<input type="hidden" name="packageid" value="'.$this->companypackagedetail[0].'" />';?>
                <?php if(!empty($this->companypackagedetail)) echo '<input type="hidden" name="paymenthistoryid" value="'.$this->companypackagedetail[1].'" />'; ?>

                <input type="hidden" name="Itemid" id="Itemid" value="<?php echo $this->Itemid; ?>" />
                <input type="hidden" name="companyid" value="<?php if(isset($this->company)) echo $this->company->id; ?>" />


    <table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform"><tr>
		<?php
		$i = 0;
		foreach($this->fieldsordering as $field){
			//echo '<br> uf'.$field->field;
			switch ($field->field) {

				case "jobtitle": ?>
					<tr>
						<td width="17%" align="right"><label id="titlemsg" for="title"><?php echo JText::_('JS_JOB_TITLE'); ?></label>&nbsp;<font color="red">*</font></td>
						<td ><input class="inputbox required" type="text" name="title" id="title" size="20" maxlength="255" value="<?php if(isset($this->job)) echo $this->job->title; ?>" /></td>
					</tr>
				<?php break;
				case "jobcategory": ?>
					<tr>
						<td valign="top" align="right"><?php echo JText::_('JS_CATEGORIES'); ?></td>
						<td ><?php echo $this->lists['jobcategory']; ?></td>
					</tr>
				<?php break;
				case "subcategory":   ?>
					<tr>
						<td valign="top" align="right"><?php echo JText::_('JS_SUB_CATEGORY'); ?></td>
						<td id="fj_subcategory"><?php echo $this->lists['subcategory']; ?></td>
					</tr>
				<?php break;
				case "jobtype": ?>
					<?php if ( $field->isvisitorpublished == 1 ) { ?>
						<tr>
							<td valign="top" align="right"><?php echo JText::_('JS_JOBTYPE'); ?></td>
							<td ><?php echo $this->lists['jobtype']; ?></td>
						</tr>
					<?php } ?>
				<?php break;
				case "jobstatus": ?>
					<tr>
						<td valign="top" align="right"><?php echo JText::_('JS_JOBSTATUS'); ?></td>
						<td ><?php echo $this->lists['jobstatus']; ?></td>
					</tr>
				<?php break;
				case "jobshift": ?>
					<?php if ( $field->isvisitorpublished == 1 ) { ?>
						<tr>
							<td valign="top" align="right"><?php echo JText::_('JS_SHIFT'); ?></td>
							<td ><?php echo $this->lists['shift']; ?></td>
						</tr>
					<?php } ?>
				<?php break;
				case "jobsalaryrange": ?>
					<?php if ( $field->isvisitorpublished == 1 ) { ?>
						<tr>
							<td valign="top" align="right"><?php echo JText::_('JS_SALARYRANGE'); ?></td>
							<td >
								<?php echo $this->lists['currencyid']; ?>&nbsp;&nbsp;&nbsp;
								<?php echo $this->lists['salaryrangefrom']; ?>&nbsp;&nbsp;&nbsp;
								<?php echo $this->lists['salaryrangeto']; ?>&nbsp;&nbsp;&nbsp;
								<?php echo $this->lists['salaryrangetypes']; ?>&nbsp;&nbsp;&nbsp;
							</td>
						</tr>
					<?php } ?>
				<?php break;
				case "heighesteducation": ?>
					<?php if ( $field->isvisitorpublished == 1 ) { ?>
						<tr>
							<td valign="top" align="right"><?php echo JText::_('JS_EDUCATION'); ?></td>
							<td height="31"   valign="top">
							<?php
								if(isset($this->job)) $iseducationminimax = $this->job->iseducationminimax; else $iseducationminimax = 1;
									if ($iseducationminimax == 1) {
										$educationminimaxdivstyle = "position:absolute;";
										$educationrangedivstyle = "visibility:hidden;position:absolute;";
									}else{
										$educationminimaxdivstyle = "visibility:hidden;position:absolute;";
										$educationrangedivstyle = "position:absolute;";
								}
							?>
							<input type="hidden" name="iseducationminimax" id="iseducationminimax" value="<?php echo $iseducationminimax; ?>">
							<div id="educationminimaxdiv" style="<?php echo $educationminimaxdivstyle; ?>">
								<?php echo $this->lists['educationminimax']; ?>&nbsp;&nbsp;&nbsp;
								<?php echo $this->lists['education']; ?>&nbsp;&nbsp;&nbsp;
								<a  onclick="hideShowRange('educationminimaxdiv','educationrangediv','iseducationminimax',0);" style="cursor:pointer;"><?php echo JText::_('JS_SPECIFY_RANGE'); ?></a>
							</div>
							<div id="educationrangediv" style="<?php echo $educationrangedivstyle; ?>">
								<?php echo $this->lists['minimumeducationrange']; ?>&nbsp;&nbsp;&nbsp;
								<?php echo $this->lists['maximumeducationrange']; ?>&nbsp;&nbsp;&nbsp;
								<a onclick="hideShowRange('educationrangediv','educationminimaxdiv','iseducationminimax',1);" style="cursor:pointer;"><?php echo JText::_('JS_CANCEL_RANGE'); ?></a>
							</div></td>
						</tr>
						<tr>
							<td valign="top" align="right"><label id="degreetitlesmsg" for="degreetitle"><?php echo JText::_('JS_DEGREE_TITLE'); ?></label></td>
							<td ><input class="inputbox" type="text" name="degreetitle" id="degreetitle" size="20" maxlength="40" value="<?php if(isset($this->job)) echo $this->job->degreetitle; ?>" /></td>
						</tr>
					<?php } ?>
				<?php break;
				case "noofjobs": ?>
					<tr>
						<td valign="top" align="right"><label id="noofjobsmsg" for="noofjobs"><?php echo JText::_('JS_NOOFJOBS'); ?></label>&nbsp;<font color="red">*</font></td>
						<td ><input class="inputbox  required validate-numeric" type="text" name="noofjobs" id="noofjobs" size="10" maxlength="10" value="<?php if(isset($this->job)) echo $this->job->noofjobs; ?>" /></td>
					</tr>
				<?php break;
				case "experience": ?>
						<?php if ( $field->isvisitorpublished == 1 ) { ?>
						<tr>
							<td valign="top" align="right"><label id="experiencesmsg" for="experience"><?php echo JText::_('JS_EXPERIENCE'); ?></label></td>
							<td height="31"   valign="top">
							<?php
								if(isset($this->job)) $isexperienceminimax = $this->job->isexperienceminimax; else $isexperienceminimax = 1;
								if ($isexperienceminimax == 1) {
									$experienceminimaxdivstyle = "position:absolute;";
									$experiencerangedivstyle = "visibility:hidden;position:absolute;";
								}else{
									$experienceminimaxdivstyle = "visibility:hidden;position:absolute;";
									$experiencerangedivstyle = "position:absolute;";
								}
							?>
							<input type="hidden" name="isexperienceminimax" id="isexperienceminimax" value="<?php echo $isexperienceminimax; ?>">
							<div id="experienceminimaxdiv" style="<?php echo $experienceminimaxdivstyle; ?>">
								<?php echo $this->lists['experienceminimax']; ?>&nbsp;&nbsp;&nbsp;
								<?php echo $this->lists['experience']; ?>&nbsp;&nbsp;&nbsp;
								<a  onclick="hideShowRange('experienceminimaxdiv','experiencerangediv','isexperienceminimax',0);" style="cursor:pointer;"><?php echo JText::_('JS_SPECIFY_RANGE'); ?></a>
							</div>
							<div id="experiencerangediv" style="<?php echo $experiencerangedivstyle; ?>">
								<?php echo $this->lists['minimumexperiencerange']; ?>&nbsp;&nbsp;&nbsp;
								<?php echo $this->lists['maximumexperiencerange']; ?>&nbsp;&nbsp;&nbsp;
								<a onclick="hideShowRange('experiencerangediv','experienceminimaxdiv','isexperienceminimax',1);" style="cursor:pointer;"><?php echo JText::_('JS_CANCEL_RANGE'); ?></a>
							</div></td>
						</tr>
						<tr>
							<td valign="top" align="right"></td>
							<td height="31"   valign="top">
								<input class="inputbox" type="text" name="experiencetext" id="experiencetext" size="20" maxlength="150" value="<?php if(isset($this->job)) echo $this->job->experiencetext; ?>" />
								&nbsp;&nbsp;&nbsp;<?php echo JText::_('JS_ANY_OTHER_EXPERIENCE'); ?></td>
						</tr>
						<?php } ?>
				<?php break;
				case "duration": ?>
						<?php if ( $field->isvisitorpublished == 1 ) { ?>
							<tr>
								<td valign="top" align="right"><label id="durationmsg" for="duration"><?php echo JText::_('JS_DURATION'); ?></label></td>
								<td ><input class="inputbox" type="text" name="duration" id="duration" size="10" maxlength="15" value="<?php if(isset($this->job)) echo $this->job->duration; ?>" />
								<?php echo JText::_('JS_DURATION_DESC'); ?></td>
							</tr>
						<?php } ?>
				<?php break;
				case "startpublishing": ?>
						<?php
							$startdatevalue = '';
							if(isset($this->job)) $startdatevalue = date($this->config['date_format'],strtotime($this->job->startpublishing)); ?>
							<tr>
								<td valign="top" align="right"><label id="startpublishingmsg" for="startpublishing"><?php echo JText::_('JS_START_PUBLISHING'); ?></label>&nbsp;<font color="red">*</font></td>
								<td ><?php //if(($this->packagedetail[2] == 1) && (isset($this->job))){ //edit
								if(isset($this->job)){ //edit
									if($jversion == '1.5') { ?> <input class="inputbox required validate-checkstartdate" type="text" name="startpublishing" id="job_startpublishing" readonly class="Shadow Bold" size="10" value="<?php if(isset($this->job)) echo  date($this->config['date_format'],strtotime($this->job->startpublishing)); ?>" />
									<?php } else echo JHTML::_('calendar', date($this->config['date_format'],  strtotime($this->job->startpublishing)),'startpublishing', 'job_startpublishing',$js_dateformat,array('class'=>'inputbox validate-checkstartdate', 'size'=>'10',  'maxlength'=>'19','readonly'=>'readonly')); ?>
									<?php //}
								}else {
									if($jversion == '1.5'){ ?><input class="inputbox required validate-checkstartdate" type="text" name="startpublishing" id="job_startpublishing" readonly class="Shadow Bold" size="10" value="<?php if(isset($this->job)) echo  date($this->config['date_format'],strtotime($this->job->startpublishing)); ?>" />
										<input type="reset" class="button" value="..." onclick="return showCalendar('job_startpublishing','<?php echo $js_dateformat; ?>');"  />
										<?php
									}else
										echo JHTML::_('calendar', '','startpublishing', 'job_startpublishing',$js_dateformat,array('class'=>'inputbox required validate-checkstartdate', 'size'=>'10',  'maxlength'=>'19')); ?>
								<?php } ?></td>
							</tr>
				<?php break;
				case "stoppublishing": ?>
						<?php $stopdatevalue = ''; ?>
						<tr>
							<td valign="top" align="right"><label id="stoppublishingmsg" for="stoppublishing"><?php echo JText::_('JS_STOP_PUBLISHING'); ?></label>&nbsp;<font color="red">*</font></td>
							<td >
							<?php if($jversion == '1.5'){ ?><input class="inputbox required validate-checkstopdate" type="text" name="stoppublishing" id="job_stoppublishing" readonly class="Shadow Bold" size="10" value="<?php if(isset($this->job)) echo  date($this->config['date_format'],strtotime($this->job->stoppublishing)); ?>" />
								<input type="reset" class="button" value="..." onclick="return showCalendar('job_stoppublishing','<?php echo $js_dateformat; ?>');"  />
							<?php }else{
								if(isset($this->job->stoppublishing))
									echo JHTML::_('calendar', date($this->config['date_format'],  strtotime($this->job->stoppublishing)),'stoppublishing', 'job_stoppublishing',$js_dateformat,array('class'=>'inputbox required validate-checkstopdate', 'size'=>'10',  'maxlength'=>'19'));
								else
									echo JHTML::_('calendar', '','stoppublishing', 'job_stoppublishing',$js_dateformat,array('class'=>'inputbox required validate-checkstopdate', 'size'=>'10',  'maxlength'=>'19'));
							} ?></td>
						</tr>
				<?php break;
				case "age": ?>
					<?php if ( $field->isvisitorpublished == 1 ) { ?>
						<tr>
							<td valign="top" align="right"><label id="agefrommsg" for="agefrom"><?php echo JText::_('JS_AGE'); ?></label><?php if($field->required == 1) echo '&nbsp;<font color="red">*</font>'; ?></td>
							<td ><?php echo $this->lists['agefrom']; ?>&nbsp;&nbsp;&nbsp;
							<?php echo $this->lists['ageto']; ?></td>
						</tr>
					<?php } ?>
				<?php break;
				case "gender": ?>
					<?php if ( $field->isvisitorpublished == 1 ) { ?>
						<tr>
							<td valign="top" align="right"><label id="gendermsg" for="gender"><?php echo JText::_('JS_GENDER'); ?><?php if($field->required == 1) echo '&nbsp;<font color="red">*</font>'; ?></label></td>
							<td ><?php echo $this->lists['gender']; ?></td>
						</tr>
					<?php } ?>
				<?php break;
				case "careerlevel": ?>
					<?php if ( $field->isvisitorpublished == 1 ) { ?>
						<tr>
							<td valign="top" align="right"><label id="careerlevelmsg" for="careerlevel"><?php echo JText::_('JS_CAREER_LEVEL'); ?></label></td>
							<td ><?php echo $this->lists['careerlevel']; ?></td>
						</tr>
					<?php } ?>
				<?php break;
				case "workpermit": ?>
					<?php if ( $field->isvisitorpublished == 1 ) { ?>
						<tr>
							<td valign="top" align="right"><label id="workpermitmsg" for="workpermit"><?php echo JText::_('JS_WORK_PERMIT'); ?></label></td>
							<td ><?php echo $this->lists['workpermit']; ?></td>
						</tr>
					<?php } ?>
				<?php break;
				case "requiredtravel": ?>
					<?php if ( $field->isvisitorpublished == 1 ) { ?>
						<tr>
							<td valign="top" align="right"><label id="requiredtravelmsg" for="requiredtravel"><?php echo JText::_('JS_REQUIRED_TRAVEL'); ?></label></td>
							<td ><?php echo $this->lists['requiredtravel']; ?></td>
						</tr>
					<?php } ?>
				<?php break;
				case "description": ?>
                                        <?php if ( $this->config['job_editor'] == 1 ) { ?>
                                                <tr><td height="10" colspan="2"></td></tr>
                                                <tr>
													<td colspan="2" valign="top" align="center"><label id="descriptionmsg" for="description"><strong><?php echo JText::_('JS_DESCRIPTION'); ?></strong></label>&nbsp;<font color="red">*</font></td>
													</tr>
													<tr>
													<td colspan="2" align="center">
													<?php
														$editor =& JFactory::getEditor();
														if(isset($this->job))
															echo $editor->display('description', $this->job->description, '100%', '100%', '60', '20', false);
														else
															echo $editor->display('description', '', '100%', '100%', '60', '20', false);
													?>
													<!--<textarea class="inputbox required" name="description" id="description" cols="60" rows="5"><?php if(isset($this->job)) echo $this->job->description; ?></textarea>-->
													</td>
                                                </tr>
                                        <?php }else{ ?>
											<tr>
                                                <td valign="top" align="right"><label id="descriptionmsg" for="description"><?php echo JText::_('JS_DESCRIPTION'); ?></label>&nbsp;<font color="red">*</font></td>
                                                <td ><textarea class="inputbox required" name="description" id="description" cols="60" rows="5"><?php if(isset($this->job)) echo $this->job->description; ?></textarea></td>
                                            </tr>
                                        <?php } ?>
				<?php break;
				case "agreement": ?>
					<?php if ( $this->config['job_editor'] == 1 ) { ?>
							<tr><td height="10" colspan="2"></td></tr>
							<tr>
								<td colspan="2" valign="top" align="center"><label id="agreementmsg" for="agreement"><strong><?php echo JText::_('JS_AGREEMENT'); ?></strong></label></td>
							</tr>
							<tr>
								<td colspan="2" align="center">
								<?php
									$editor =& JFactory::getEditor();
									if(isset($this->job))
										echo $editor->display('agreement', $this->job->agreement, '100%', '100%', '60', '20', false);
									else
										echo $editor->display('agreement', '', '100%', '100%', '60', '20', false);

								?>
								</td>
							</tr>
					<?php }else{ ?>
							<tr>
								<td valign="top" align="right"><label id="agreementmsg" for="agreement"><?php echo JText::_('JS_DESCRIPTION'); ?></label>&nbsp;<font color="red">*</font></td>
								<td ><textarea class="inputbox required" name="agreement" id="agreement" cols="60" rows="5"><?php if(isset($this->job)) echo $this->job->agreement; ?></textarea></td>
							</tr>
					<?php } ?>
				<?php break;
				case "qualifications": ?>
						<?php if ( $field->isvisitorpublished == 1 ) { ?>
						<?php if ( $this->config['job_editor'] == 1 ) { ?>
							<tr><td height="10" colspan="2"></td></tr>
							<tr>
								<td colspan="2" valign="top" align="center"><label id="qualificationsmsg" for="qualifications"><strong><?php echo JText::_('JS_QUALIFICATIONS'); ?></strong></label></td>
							</tr>
							<tr>
								<td colspan="2" align="center">
								<?php
									$editor =& JFactory::getEditor();
									if(isset($this->job))
										echo $editor->display('qualifications', $this->job->qualifications, '100%', '100%', '60', '20', false);
									else
										echo $editor->display('qualifications', '', '100%', '100%', '60', '20', false);

								?>
								</td>
							</tr>
					<?php }else{ ?>
								<tr>
									<td valign="top" align="right"><?php echo JText::_('JS_QUALIFICATIONS');?></td>
									<td ><textarea class="inputbox" name="qualifications" id="qualifications" cols="60" rows="5"><?php if(isset($this->job)) echo $this->job->qualifications; ?></textarea></td>
								</tr>
							<?php } ?>
					<?php } ?>
				<?php break;
				case "prefferdskills": ?>
	 			    <?php if ( $this->config['job_editor'] == 1 ) { ?>
						<?php if ( $field->isvisitorpublished == 1 ) { ?>
							<tr><td height="10" colspan="2"></td></tr>
							<tr>
								<td colspan="54" valign="top" align="center"><label id="prefferdskillsmsg" for="prefferdskills"><strong><?php echo JText::_('JS_PREFFERD_SKILLS'); ?></strong></label></td>
							</tr>
							<tr>
								<td colspan="2" align="center">
								<?php
									$editor =& JFactory::getEditor();
									if(isset($this->job))
										echo $editor->display('prefferdskills', $this->job->prefferdskills, '100%', '100%', '60', '20', false);
									else
										echo $editor->display('prefferdskills', '', '100%', '100%', '60', '20', false);
								?>
								</td>
							</tr>
							<?php } ?>

					<?php }else{ ?>
						<?php if ( $field->isvisitorpublished == 1 ) { ?>
								<tr>
									<td valign="top" align="right"><label id="prefferdskillsmsg" for="prefferdskills"><?php echo JText::_('JS_PREFFERD_SKILLS'); ?></label></td>
									<td ><textarea class="inputbox" name="prefferdskills" id="prefferdskills" cols="60" rows="5"><?php if(isset($this->job)) echo $this->job->prefferdskills; ?></textarea></td>
								</tr>
							<?php } ?>
					<?php } ?>
					<?php break;
				case "city": ?>
					<?php if ( $field->isvisitorpublished == 1 ) { ?>
						<tr>
							<td align="right"><label id="citymsg" for="city"><?php echo JText::_('JS_CITY'); ?></label></td>
							<td  id="job_city">
								<input class="inputbox" type="text" name="city" id="city" size="40" maxlength="100" value="" />
								<input class="inputbox" type="hidden" name="citynameforedit" id="citynameforedit" size="40" maxlength="100" value="<?php if(isset($this->vmultiselecteditjob)) echo $this->vmultiselecteditjob; ?>" />
								
							</td>
						</tr>
					<?php } ?>
				<?php break;
				case "metadescription": ?>
					<tr>
						<td align="right" ><label id="metadescriptionmsg" for="metadescription"><?php echo JText::_('JS_META_DESCRIPTION'); ?></label></td>
						<td><textarea cols="25" rows="5" class="inputbox " name="metadescription" size="20" id="metadescription" ><?php if(isset($this->job)) echo $this->job->metadescription; ?></textarea></td>
					</tr>
				<?php break;
				case "metakeywords": ?>
					<tr>
						<td  align="right" ><label id="metakeywordsmsg" for="metakeywords"><?php echo JText::_('JS_META_KEYWORDS'); ?></label></td>
						<td><textarea cols="25" rows="5" class="inputbox" name="metakeywords" size="20" id="metakeywords" ><?php if(isset($this->job)) echo $this->job->metakeywords; ?></textarea></td>
					</tr>
				<?php break;
				case "video": ?>
					<tr>
						<td width="3%" align="right"><label id="videomsg" for="video"><?php echo JText::_('JS_VIDEO'); ?></label></td>
						<td ><input class="inputbox" type="text" name="video" id="video" size="20" maxlength="255" value="<?php if(isset($this->job)) echo $this->job->video; ?>" /><?php echo JText::_('JS_YOUTUBE_VIDEO_ID');?></td>
					</tr>
				<?php break;
				case "map": ?>
					<tr>
						<td width="3%" align="right"><label id="mapmsg" for="map"><?php echo JText::_('JS_MAP'); ?></label></td>
						<td ><?php /*    <input class="inputbox " type="text" name="map" id="map" size="20" maxlength="500" value="<?php if(isset($this->job)) echo $this->job->map; ?>" />
							<?php echo JText::_('JS_GOOGLE_MAP_SOURCE');?>
							*/?>
						<div id="map"><div id="map_container"></div></div>
						<br/><input type="text" id="longitude" name="longitude" value="<?php if(isset($this->job)) echo $this->job->longitude;?>"/><?php echo JText::_('JS_LONGITUDE');?><!--<div id="coordinatebutton"><input type="button" class="cbutton" value="<?php echo JText::_('JS_GET_ADDRESS_FROM_MARKER');?>" onclick="Javascript: loadMap(2,'country','state','city');"/></div>-->
						<br/><input type="text" id="latitude" name="latitude" value="<?php if(isset($this->job)) echo $this->job->latitude;?>"/><?php echo JText::_('JS_LATITTUDE');?><div id="coordinatebutton"><input type="button" class="cbutton" value="<?php echo JText::_('JS_SET_MARKER_FROM_ADDRESS');?>" onclick="Javascript: loadMap(3,'country','state','city');"/></div>
							
						</td>
					</tr>
				<?php break;
				case "sendemail": ?>
					<tr>
						<td valign="top" align="right"><?php echo JText::_('JS_SEND_EMAIL'); ?></td>
						<td ><?php echo $this->lists['sendemail']; ?></td>
					</tr>
				<?php break;

				default:
					if ( $field->isvisitorpublished == 1 ) {

						foreach($this->userfields as $ufield){
							if($field->field == $ufield[0]->id) {
								$userfield = $ufield[0];
								$i++;
								echo "<tr><td valign='top' align='right'>";
								if($userfield->required == 1){
									echo "<label id=".$userfield->name."msg for=$userfield->name>$userfield->title</label>&nbsp;<font color='red'>*</font>";
									if($userfield->type == 'emailaddress') $cssclass = "class ='inputbox required validate-email' ";
									else $cssclass = "class ='inputbox required' ";
								}else{
									echo $userfield->title;
									if($userfield->type == 'emailaddress') $cssclass = "class ='inputbox validate-email' ";
									else  $cssclass = "class='inputbox' ";
								}
								echo "</td><td>"	;

								$readonly = $userfield->readonly ? ' readonly="readonly"' : '';
		   						$maxlength = $userfield->maxlength ? 'maxlength="'.$userfield->maxlength.'"' : '';
								if(isset($ufield[1])){ $fvalue = $ufield[1]->data; $userdataid = $ufield[1]->id;}  else {$fvalue=""; $userdataid = ""; }
								echo '<input type="hidden" id="userfields_'.$i.'_id" name="userfields_'.$i.'_id"  value="'.$userfield->id.'"  />';
								echo '<input type="hidden" id="userdata_'.$i.'_id" name="userdata_'.$i.'_id"  value="'.$userdataid.'"  />';
								switch( $userfield->type ) {
									case 'text':
										echo '<input type="text" id="userfields_'.$i.'" name="userfields_'.$i.'" size="'.$userfield->size.'" value="'. $fvalue .'" '.$cssclass .$maxlength . $readonly . ' />';
										break;
									case 'emailaddress':
										echo '<input type="text" id="userfields_'.$i.'" name="userfields_'.$i.'" size="'.$userfield->size.'" value="'. $fvalue .'" '.$cssclass .$maxlength . $readonly . ' />';
										break;
									case 'date':
										$userfieldid = 'userfields_'.$i;
										$userfieldid = "'".$userfieldid."'";
										if($jversion == '1.5') {
											echo '<input type="text" id="userfields_'.$i.'" name="userfields_'.$i.'" readonly size="'.$userfield->size.'" value="'. $fvalue .'" '.$cssclass .$maxlength . $readonly . ' />';
											echo '<input type="reset" class="button" value="..." onclick="return showCalendar('.$userfieldid.',\'%Y-%m-%d\');"  />';
										} else {
											//echo JHTML::_('calendar', date($this->config['date_format'],  strtotime($fvalue)),'userfields_'.$i, 'userfields_'.$i,$js_dateformat,array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'19'));
											echo JHTML::_('calendar', $fvalue,'userfields_'.$i, 'userfields_'.$i,$js_dateformat,array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'19'));
										}
										break;
									case 'textarea':
										echo '<textarea name="userfields_'.$i.'" id="userfields_'.$i.'_field" cols="'.$userfield->cols.'" rows="'.$userfield->rows.'" '.$readonly.'>'.$fvalue.'</textarea>';
										break;
									case 'checkbox':
										echo '<input type="checkbox" name="userfields_'.$i.'" id="userfields_'.$i.'_field" value="1" '.  'checked="checked"' .'/>';
										break;
									case 'select':
										$htm = '<select name="userfields_'.$i.'" id="userfields_'.$i.'" >';
										if (isset ($ufield[2])){
											foreach($ufield[2] as $opt){
												if ($opt->id == $fvalue)
													$htm .= '<option value="'.$opt->id.'" selected="yes">'. $opt->fieldtitle .' </option>';
												else
													$htm .= '<option value="'.$opt->id.'">'. $opt->fieldtitle .' </option>';
											}
										}
										$htm .= '</select>';
										echo $htm;
								}
								echo '</td></tr>';
							}
						}
					}

			}

		}
		echo '<input type="hidden" id="userfields_total" name="userfields_total"  value="'.$i.'"  />';
		?>
		<?php if ( $this->config['job_captcha'] == 1 ){ ?>
			<tr>
				<td valign="top" align="right"><label id="captchamsg" for="captcha"><?php echo JText::_('JS_CAPTCHA'); ?></label><?php if($field->required == 1){ echo '&nbsp;<font color="red">*</font>';} ?></td>
				<td colspan="3"><?php echo $this->captcha;  ?> </td>
			</tr>
		<?php } ?>

	<tr><td colspan="54" height="10"></td></tr>
	<tr>
		<td colspan="54" align="center">
			<input id="button" class="button" type="submit" name="submit_app" value="<?php echo JText::_('JS_SAVEJOB'); ?>" />
		</td>
	</tr>
    </table>
			<?php
				if(isset($this->job)) {
					if (($this->job->created=='0000-00-00 00:00:00') || ($this->job->created==''))
						$curdate = date('Y-m-d H:i:s');
					else
						$curdate = $this->job->created;
				}else
					$curdate = date('Y-m-d H:i:s');

			?>
			<input type="hidden" name="created" value="<?php echo $curdate; ?>" />
			<input type="hidden" name="jobid" id="jobid" value="<?php if(isset($this->job)) echo $this->job->jobid; ?>" />
			<input type="hidden" name="view" value="jobposting" />
			<input type="hidden" name="layout" value="viewjob" />
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			<input type="hidden" name="check" value="" />
			<input type="hidden" name="packageid" value="<?php echo $this->packagedetail[0]; ?>" />
			<input type="hidden" name="paymenthistoryid" value="<?php echo $this->packagedetail[1]; ?>" />
			<input type="hidden" name="enforcestoppublishjob" value="<?php echo $this->packagedetail[2]; ?>" />
			<input type="hidden" name="enforcestoppublishjobvalue" value="<?php echo $this->packagedetail[3]; ?>" />
			<input type="hidden" name="enforcestoppublishjobtype" value="<?php echo $this->packagedetail[4]; ?>" />

			<input type="hidden" name="Itemid" id="Itemid" value="<?php echo $this->Itemid; ?>" />
			<input type="hidden" name="id" value="<?php if(isset($this->job)) echo $this->job->id; ?>" />
			<input type="hidden" name="j_dateformat" id="j_dateformat" value="<?php  echo $js_scriptdateformat; ?>" />

			<input type="hidden" name="default_longitude" id="default_longitude" value="<?php echo $this->config['default_longitude']; ?>" />
			<input type="hidden" name="default_latitude" id="default_latitude" value="<?php  echo $this->config['default_latitude']; ?>" />

<script language=Javascript>
function dochange(src, val){
	var pagesrc = 'job_'+src;
	document.getElementById(pagesrc).innerHTML="Loading ...";
	var xhr;
	try {  xhr = new ActiveXObject('Msxml2.XMLHTTP');   }
	catch (e)
	{
		try {   xhr = new ActiveXObject('Microsoft.XMLHTTP');    }
		catch (e2)
		{
		  try {  xhr = new XMLHttpRequest();     }
		  catch (e3) {  xhr = false;   }
		}
	 }

	xhr.onreadystatechange = function(){
      if(xhr.readyState == 4 && xhr.status == 200){
        	document.getElementById(pagesrc).innerHTML=xhr.responseText; //retuen value

			if(src=='state'){
				cityhtml = "<input class='inputbox' type='text' name='city' id='city' size='20' maxlength='100'  />";
				document.getElementById('job_city').innerHTML=cityhtml; //retuen value
			}
      }
    }

	xhr.open("GET","index.php?option=com_jsjobs&task=listaddressdata&data="+src+"&val="+val,true);
	xhr.send(null);
}
function dochangecompany(src, val){
	var pagesrc = src;
	document.getElementById(pagesrc).innerHTML="Loading ...";
	var xhr;
	try {  xhr = new ActiveXObject('Msxml2.XMLHTTP');   }
	catch (e)
	{
		try {   xhr = new ActiveXObject('Microsoft.XMLHTTP');    }
		catch (e2)
		{
		  try {  xhr = new XMLHttpRequest();     }
		  catch (e3) {  xhr = false;   }
		}
	 }

	xhr.onreadystatechange = function(){
      if(xhr.readyState == 4 && xhr.status == 200){
        	document.getElementById(pagesrc).innerHTML=xhr.responseText; //retuen value
			if(src=='company_state'){
				cityhtml = "<input class='inputbox' type='text' name='companycity' size='20' maxlength='100'  />";
				document.getElementById('company_city').innerHTML=cityhtml; //retuen value
			}
      }
    }

	xhr.open("GET","index.php?option=com_jsjobs&task=listaddressdata&data="+src+"&val="+val,true);
	xhr.send(null);
}

function getdepartments(src, val){
	document.getElementById(src).innerHTML="Loading ...";
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
			document.getElementById(src).innerHTML=xhr.responseText; //retuen value
		}
	}

	xhr.open("GET","index.php?option=com_jsjobs&task=listdepartments&val="+val,true);
	xhr.send(null);
}
function fj_getsubcategories(src, val){
	var xhr;
	try {  xhr = new ActiveXObject('Msxml2.XMLHTTP');   }
	catch (e){
		try {   xhr = new ActiveXObject('Microsoft.XMLHTTP');    }
		catch (e2) {
		  try {  xhr = new XMLHttpRequest();     }
		  catch (e3) {  xhr = false;   }
		}
	 }

	xhr.onreadystatechange = function(){
            if(xhr.readyState == 4 && xhr.status == 200){
        	document.getElementById(src).innerHTML=xhr.responseText; //retuen value
            }
        }

	xhr.open("GET","index.php?option=com_jsjobs&task=listsubcategories&val="+val,true);
	xhr.send(null);
}

</script>


		</form>
<?php
} else{ // not allowed job posting ?>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<div id="message"><b><?php echo JText::_('JS_SORRY_WE_NOT_ALLOWED_VISITOR_TO_POST_THIER_JOB'); ?></b></div>
	</div>
<?php
}
}//ol
?>
<div id="jsjobs_footer"><?php echo eval(base64_decode('aWYoJHRoaXMtPmNvbmZpZ1snZnJfY3JfdHhzaCddKSB7DQplY2hvIA0KJzx0YWJsZSB3aWR0aD0iMTAwJSIgc3R5bGU9InRhYmxlLWxheW91dDpmaXhlZDsiPg0KPHRyPjx0ZCBoZWlnaHQ9IjE1Ij48L3RkPjwvdHI+DQo8dHI+PHRkIHN0eWxlPSJ2ZXJ0aWNhbC1hbGlnbjp0b3A7IiBhbGlnbj0iY2VudGVyIj4NCjxhIGNsYXNzPSJpbWciIHRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIj48aW1nIHNyYz0iaHR0cDovL3d3dy5qb29tc2t5LmNvbS9sb2dvL2pzam9ic2NybG9nby5wbmciPjwvYT4NCjxicj4NCkNvcHlyaWdodCAmY29weTsgMjAwOCAtICcuZGF0ZSgnWScpLicsDQo8c3BhbiBpZD0idGhlbWVhbmNob3IiPiA8YSBjbGFzcz0iYW5jaG9yInRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSI+QnVydWogU29sdXRpb25zIDwvYT48L3NwYW4+PC90ZD48L3RyPg0KPC90YWJsZT4nOw0KfQ=='));?></div>
<style type="text/css">
div#map_container{
	width:100%;
	height:350px;
}
</style>
<script type="text/javascript" 
   src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
        jQuery(document).ready(function() {
            var cityname = jQuery("#companycityforedit").val();
            if(cityname != ""){
                jQuery("#companycity").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
                    theme: "jsjobs",
                    preventDuplicates: true,
                    hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
                    noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
                    searchingText: "<?php echo JText::_('SEARCHING...');?>",
                    //tokenLimit: 1,
                    prePopulate: <?php if(isset($this->vmultiselecteditcompany)) echo $this->vmultiselecteditcompany;else echo "''"; ?>

                    
                });
            }else{
                jQuery("#companycity").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
                    theme: "jsjobs",
                    preventDuplicates: true,
                    hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
                    noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
                    searchingText: "<?php echo JText::_('SEARCHING...');?>",
                    //tokenLimit: 1

                });
            }
        });
        jQuery(document).ready(function() {
            var cityname = jQuery("#citynameforedit").val();
            if(cityname != ""){
                jQuery("#city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
                    theme: "jsjobs",
                    preventDuplicates: true,
                    hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
                    noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
                    searchingText: "<?php echo JText::_('SEARCHING...');?>",
                    //tokenLimit: 1,
                    prePopulate: <?php if(isset($this->vmultiselecteditjob)) echo $this->vmultiselecteditjob;else echo "''"; ?>
                });
            }else{
                jQuery("#city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
                    theme: "jsjobs",
                    preventDuplicates: true,
                    hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
                    noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
                    searchingText: "<?php echo JText::_('SEARCHING...');?>",
                    //tokenLimit: 1

                });
            }
        });
	
	window.onload = loadMap(1,'','','');
  function loadMap(callfrom,country,state,city) {
		var values_longitude = [];
		var values_latitude = [];
		var latedit=[];
		var longedit=[];
		var longitude = document.getElementById('longitude').value;
		var latitude = document.getElementById('latitude').value;
		latedit=latitude.split(",");
		longedit=longitude.split(",");
		
		var default_latitude = document.getElementById('default_latitude').value;
		var default_longitude = document.getElementById('default_longitude').value;
		
		if(latedit != '' && longedit != ''){ 
			for (var i = 0; i < latedit.length; i++) {
				var latlng = new google.maps.LatLng(latedit[i], longedit[i]); zoom = 4;
				var myOptions = {
				  zoom: zoom,
				  center: latlng,
				  mapTypeId: google.maps.MapTypeId.ROADMAP
				};
				if(i==0) var map = new google.maps.Map(document.getElementById("map_container"),myOptions);
				if(callfrom == 1){
					var marker = new google.maps.Marker({
					  position: latlng, 
					  map: map, 
					  visible: true,					  
					});

					document.getElementById('longitude').value = marker.position.lng();
					document.getElementById('latitude').value = marker.position.lat();
					marker.setMap(map);
					values_longitude.push(longedit[i]);
					values_latitude.push(latedit[i]);
					document.getElementById('latitude').value = values_latitude;
					document.getElementById('longitude').value = values_longitude;

				}

			}			
		}else {
			var latlng = new google.maps.LatLng(default_latitude, default_longitude); zoom=4;
			var myOptions = {
			  zoom: zoom,
			  center: latlng,
			  mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			var map = new google.maps.Map(document.getElementById("map_container"),myOptions);
			var lastmarker = new google.maps.Marker({
				postiion:latlng,
				map:map,
			});
			if(callfrom == 1){
				var marker = new google.maps.Marker({
				  position: latlng, 
				  map: map, 
				});
				document.getElementById('longitude').value = marker.position.lng();
				document.getElementById('latitude').value = marker.position.lat();
				marker.setMap(map);
				values_longitude.push(document.getElementById('longitude').value);
				values_latitude.push(document.getElementById('latitude').value);

				lastmarker = marker;
			}
		
		}

	google.maps.event.addListener(map,"click", function(e){
		var latLng = new google.maps.LatLng(e.latLng.lat(),e.latLng.lng());
		geocoder = new google.maps.Geocoder();
		geocoder.geocode( { 'latLng': latLng}, function(results, status) {
		  if (status == google.maps.GeocoderStatus.OK) {
			//if(lastmarker != '') lastmarker.setMap(null);
			var marker = new google.maps.Marker({
				position: results[0].geometry.location, 
				map: map, 
			});
			marker.setMap(map);
			//lastmarker = marker;
			
			document.getElementById('latitude').value = marker.position.lat();
			document.getElementById('longitude').value = marker.position.lng();
			values_longitude.push(document.getElementById('longitude').value);
			values_latitude.push(document.getElementById('latitude').value);
			document.getElementById('latitude').value = values_latitude;
			document.getElementById('longitude').value = values_longitude;
			
			
		  } else {
			alert("Geocode was not successful for the following reason: " + status);
		  }
		});
	}); 


	if(callfrom == 3){
		var value='';var zoom=4;
		jQuery("td#job_city > ul > li > p").each(function(){
			value=jQuery(this).html();
			if(value != ''){
				geocoder = new google.maps.Geocoder();
				geocoder.geocode( { 'address': value}, function(results, status) {
				  if (status == google.maps.GeocoderStatus.OK) {
					map.setCenter(results[0].geometry.location);
					document.getElementById('latitude').value = results[0].geometry.location.lat();
					document.getElementById('longitude').value = results[0].geometry.location.lng();
					map.setZoom(zoom);
					//lastmarker.setMap(null);
					var marker = new google.maps.Marker({
					position: results[0].geometry.location, 
					map: map, 
					});
					marker.setMap(map);
					values_longitude.push(document.getElementById('longitude').value);
					values_latitude.push(document.getElementById('latitude').value);
					document.getElementById('latitude').value = values_latitude;
					document.getElementById('longitude').value = values_longitude;
					
					//lastmarker = marker;
				  } else {
					alert("Geocode was not successful for the following reason: " + status);
				  }
				});
			}
			
			
		});
		
	}
	if(callfrom == 2){
		var latLng = new google.maps.LatLng(latitude,longitude);
		geocoder = new google.maps.Geocoder();
		geocoder.geocode( { 'latLng': latLng}, function(results, status) {
		  if (status == google.maps.GeocoderStatus.OK) {
			if(lastmarker != '') lastmarker.setMap(null);
			var marker = new google.maps.Marker({
				position: results[0].geometry.location, 
				map: map, 
			});
			map.setZoom(12);
			marker.setMap(map);
			lastmarker = marker;
			var address = results[1].formatted_address;
			var xhr;
			try {  xhr = new ActiveXObject('Msxml2.XMLHTTP');   }
			catch (e){
				try {   xhr = new ActiveXObject('Microsoft.XMLHTTP');    }
				catch (e2) {
				  try {  xhr = new XMLHttpRequest();     }
				  catch (e3) {  xhr = false;   }
				}
			 }
			xhr.onreadystatechange = function(){
					if(xhr.readyState == 4 && xhr.status == 200){
						var obj = eval("("+xhr.responseText+")");
						document.getElementById('country').value = obj.countrycode;
						document.getElementById('job_state').innerHTML = obj.states;
						//document.getElementById('job_county').innerHTML = obj.counties;
						document.getElementById('job_city').innerHTML = obj.city;
					}
				}

			xhr.open("GET","index.php?option=com_jsjobs&task=getaddressdata&val="+address,true);
			xhr.send(null);
		}
		});
	}
}
</script>
