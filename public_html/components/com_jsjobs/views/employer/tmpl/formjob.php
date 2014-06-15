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
 * File Name:	views/employer/tmpl/formjob.php
 ^ 
 * Description: template for form job
 ^ 
 * History:		NONE
 ^ 
 */
 

defined('_JEXEC') or die('Restricted access');

 global $mainframe;

JHTML::_('behavior.formvalidation');  
$editor = & JFactory :: getEditor();
JHTML :: _('behavior.calendar');
    $document = &JFactory::getDocument();
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
		clear:both;
	}
	#coordinatebutton .cbutton{
		padding:1;
		font-size: 1.08em;
	}
	#coordinatebutton .cbutton:hover{
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
   document.formvalidator.setHandler('selectpackage', function(value) {
		var multiselectpackage = document.getElementById("multipackage");
		if(typeof multiselectpackage !== 'undefined' && multiselectpackage !== null) {	
			var m_p_value = multiselectpackage.options[multiselectpackage.selectedIndex].value;
			if(m_p_value=="") return false;
		}		
		return true; 	  
   });
});	

window.addEvent('domready', function(){
   document.formvalidator.setHandler('checkstartdate', function(value) {
		var date_start_make = new Array();
		var split_start_value=new Array();
		f = document.adminForm;
		var isstartpubreadonly = document.getElementById("startpubreadonly");
		if(typeof isstartpubreadonly !== 'undefined' && isstartpubreadonly !== null) {			
			if(isstartpubreadonly.value!="" && isstartpubreadonly.value!=0  ) {	
				return true;
			}
		}else{
			var isedit = document.getElementById("id");
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
		}
		
   });
});	
window.addEvent('domready', function(){
   document.formvalidator.setHandler('checkstopdate', function(value) {
		var isstoppubreadonly = document.getElementById("stoppubreadonly");
		if(typeof isstoppubreadonly !== 'undefined' && isstoppubreadonly !== null) {			
			if(isstoppubreadonly.value!="" && isstoppubreadonly.value!=0  ) {	
				return true;
			}	
		}else{
				var return_value=check_start_stop_job_publishing_dates();
				return return_value;
		}
	   
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
	
	
	
	function hideShowRange(hideSrc, showSrc, showName, showVal){
		document.getElementById(hideSrc).style.visibility = "hidden";
		document.getElementById(showSrc).style.visibility = "visible";
		document.getElementById(showName).value = showVal;
	
	}
 
	function myValidate(f) {
		
		var msg = new Array();
		if (document.formvalidator.isValid(f)) {
                f.check.value='<?php if(($jversion == '1.5') || ($jversion == '2.5')) echo JUtility::getToken(); else echo  JSession::getFormToken(); ?>';//send token
                
        } else {
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
		var multiselectpackage = document.getElementById("package");
		if(typeof multiselectpackage !== 'undefined' && multiselectpackage !== null) {	
			var m_p_value = multiselectpackage.options[multiselectpackage.selectedIndex].value;
			if(m_p_value=="") {
				msg.push('<?php echo JText::_('JS_PLEASE_SELECT_PACKAGE_TO_ADD_NEW_JOB'); ?>');
				alert (msg.join('\n'));			
				return false;
			}
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

function hasClass(el, selector) {
   var className = " " + selector + " ";
  
   if ((" " + el.className + " ").replace(/[\n\t]/g, " ").indexOf(className) > -1) {
    return true;
   }
   return false;
  }
  
</script>

	<div id="toppanel">
		<div id="tp_header" <?php if($this->config['topimage'] == 0) echo 'style="background:none;"';?>>
			<span id="tp_title"><?php echo $this->config['title'];?></span>
			<span id="tp_curloc">
				<?php if ($this->config['cur_location'] == 1) {
						if (isset($this->job)){
							echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_MY_JOBS'); ?></a> > <?php echo JText::_('JS_EDIT_JOB_INFO');
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
				<span id="tp_headingtext_center"><?php echo JText::_('JS_JOB_INFO');  ?></span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>
<?php
if ($this->userrole->rolefor == 1) { // employer
if($this->isuserhascompany!=3){
if ($this->canaddnewjob == 1) { // add new job, in edit case always 1
?>
<form action="index.php" method="post" name="adminForm" id="adminForm"  onSubmit="return myValidate(this);">
    <table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
		<?php if($this->packagecombo != false) { ?>
		   <tr>
			<td valign="top" align="right"><label id="durationmsg" for="duration"><?php echo JText::_('JS_SELECT_PACKAGE'); ?></label></td>
			<td ><?php echo $this->packagecombo[0];?></td>
		  </tr>
		  <?php } ?>
		  <tr id="markasgold" >
			  <td valign="top" align="right"><div style="display:none;"><label id="goldjobmsg" for="goldjob"><?php echo JText::_('JS_GOLD_JOB'); ?></label></div></td>
			  <td><div style="display:none;"><input name="goldjob" id="goldjob" type="checkbox" value="1"/><?php echo JText::_('JS_GOLD_JOB'); ?></div></td>
		  </tr>
		  <tr id="markasfeatured">
			  <td valign="top" align="right"><div style="display:none"><label id="featuredjobmsg" for="featuredjob"><?php echo JText::_('JS_FEATURED_JOB'); ?></label></div></td>
			  <td><div style="display:none;"><input name="featuredjob" id="featuredjob" type="checkbox" value="1"/><?php echo JText::_('JS_FEATURED_JOB'); ?></div></td>
		  </tr>
		<?php
		$i = 0;
		foreach($this->fieldsordering as $field){ 
			switch ($field->field) {
				
				case "jobtitle": ?>
				  <tr>
			        <td width="17%" align="right"><label id="titlemsg" for="title"><?php echo JText::_('JS_JOB_TITLE'); ?></label>&nbsp;<font color="red">*</font></td>
			          <td ><input class="inputbox required" type="text" name="title" id="title" size="40" maxlength="255" value="<?php if(isset($this->job)) echo $this->job->title; ?>" />
			        </td>
			      </tr>
				<?php break;
				case "company": ?>
				  <tr>
			        <td valign="top" align="right"><label id="companymsg" for="company"><?php echo JText::_('JS_COMPANY'); ?></label>&nbsp;<font color="red">*</font></td>
			        <td ><?php echo $this->lists['companies']; ?></td>
			      </tr>
				<?php break;
				case "department": ?>
				  <tr>
			        <td valign="top" align="right"><label id="departmentmsg" for="department"><?php echo JText::_('JS_DEPARTMENT'); ?></label></td>
				    <td  id="department"><?php  if(isset($this->lists['departments'])) echo $this->lists['departments']; ?></td> 
			      </tr>
				<?php break;
				case "jobcategory": ?>
			      <tr>
			        <td valign="top" align="right"><?php echo JText::_('JS_CATEGORIES'); ?></td>
			        <td ><?php echo $this->lists['jobcategory']; ?></td>
			      </tr>
				<?php break;
				case "subcategory":   ?>
			      <tr >
			        <td valign="top" align="right"><?php echo JText::_('JS_SUB_CATEGORY'); ?></td>
			        <td id="fj_subcategory"><?php echo $this->lists['subcategory']; ?></td>
			      </tr>
				<?php break;
				case "jobtype": ?>
			      <?php if ( $field->published == 1 ) { ?>
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
			      <?php if ( $field->published == 1 ) { ?>
			      <tr>
			        <td valign="top" align="right"><?php echo JText::_('JS_SHIFT'); ?></td>
			        <td ><?php echo $this->lists['shift']; ?></td>
			      </tr>
				  <?php } ?>
				<?php break;
				case "jobsalaryrange": ?>
			      <?php if ( $field->published == 1 ) { ?>
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
			      <?php if ( $field->published == 1 ) { ?>
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
						</div>
					</td>
				      </tr>
					<tr>
					<td valign="top" align="right"><label id="degreetitlesmsg" for="degreetitle"><?php echo JText::_('JS_DEGREE_TITLE'); ?></label></td>
					<td ><input class="inputbox" type="text" name="degreetitle" id="degreetitle" size="30" maxlength="40" value="<?php if(isset($this->job)) echo $this->job->degreetitle; ?>" />
					</td>
					</tr>
					<?php } ?>
				<?php break;
				case "noofjobs": ?>
					<tr>
					<td valign="top" align="right"><label id="noofjobsmsg" for="noofjobs"><?php echo JText::_('JS_NOOFJOBS'); ?></label>&nbsp;<font color="red">*</font></td>
					<td ><input class="inputbox  required validate-numeric" type="text" name="noofjobs" id="noofjobs" size="10" maxlength="10" value="<?php if(isset($this->job)) echo $this->job->noofjobs; ?>" />
					</td>
					</tr>
				<?php break;
				case "experience": ?>
			      <?php if ( $field->published == 1 ) { ?>
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
					</div>
			        </td>
						
			      </tr>
			       <tr>
			        <td valign="top" align="right"></td>
			        <td height="31"   valign="top">
					<input class="inputbox" type="text" name="experiencetext" id="experiencetext" size="30" maxlength="150" value="<?php if(isset($this->job)) echo $this->job->experiencetext; ?>" />
					&nbsp;&nbsp;&nbsp;<?php echo JText::_('JS_ANY_OTHER_EXPERIENCE'); ?>
			        </td>
			      </tr>
				  <?php } ?>
				<?php break;
				case "duration": ?>
			      <?php if ( $field->published == 1 ) { ?>
			       <tr>
			        <td valign="top" align="right"><label id="durationmsg" for="duration"><?php echo JText::_('JS_DURATION'); ?></label></td>
			        <td ><input class="inputbox" type="text" name="duration" id="duration" size="10" maxlength="15" value="<?php if(isset($this->job)) echo $this->job->duration; ?>" />
			        <?php echo JText::_('JS_DURATION_DESC'); ?>
					</td>
			      </tr>
				  <?php } ?>
				<?php break;
				case "startpublishing": ?>
					<?php 
						$startdatevalue = '';
						if(isset($this->job)) $startdatevalue = date($this->config['date_format'],strtotime($this->job->startpublishing));
						?>
						<tr>
							<td valign="top" align="right"><label id="startpublishingmsg" for="startpublishing"><?php echo JText::_('JS_START_PUBLISHING'); ?></label>&nbsp;<font color="red">*</font></td>
							<td ><?php if(isset($this->job)){ //edit
								if($jversion == '1.5') { ?> 
											<input class="inputbox required validate-checkstartdate" type="text" name="startpublishing" id="job_startpublishing" readonly class="Shadow Bold" size="10" value="<?php if(isset($this->job)) echo  date($this->config['date_format'],strtotime($this->job->startpublishing)); ?>" />
									<?php } else {
										if(isset($this->packagedetail[2]) AND $this->packagedetail[2] == 1){
											echo JHTML::_('calendar', date($this->config['date_format'],  strtotime($this->job->startpublishing)),'startpublishing', 'job_startpublishing',$js_dateformat,array('class'=>'inputbox validate-checkstartdate', 'size'=>'10',  'maxlength'=>'19','readonly'=>'readonly'));
											echo '<input type="hidden" name="startpubreadonly" id="startpubreadonly" value="1" size=""/>';
										}else echo JHTML::_('calendar', date($this->config['date_format'],  strtotime($this->job->startpublishing)),'startpublishing', 'job_startpublishing',$js_dateformat,array('class'=>'inputbox validate-checkstartdate', 'size'=>'10',  'maxlength'=>'19'));?>
								<?php }
							}else { 
								if($jversion == '1.5'){ ?><input class="inputbox required validate-checkstartdate" type="text" name="startpublishing" id="job_startpublishing" readonly class="Shadow Bold" size="10" value="<?php if(isset($this->job)) echo  date($this->config['date_format'],strtotime($this->job->startpublishing)); ?>" />
								<input type="reset" class="button" value="..." onclick="return showCalendar('job_startpublishing','<?php echo $js_dateformat; ?>');"  />
								<?php 
								}else	echo JHTML::_('calendar', '','startpublishing', 'job_startpublishing',$js_dateformat,array('class'=>'inputbox required validate-checkstartdate', 'size'=>'10',  'maxlength'=>'19')); ?>

						<?php } ?>
							</td>
					</tr>
				<?php break;
				case "stoppublishing": ?>
					<?php 
						$stopdatevalue = '';
						?>
			       <tr>
			        <td valign="top" align="right"><label id="stoppublishingmsg" for="stoppublishing"><?php echo JText::_('JS_STOP_PUBLISHING'); ?></label>&nbsp;<font color="red">*</font></td>
			        <td id="stoppublishingdate">
						<?php  if(isset($this->packagedetail[2]) AND $this->packagedetail[2] == 1){
									if(isset($this->job)){ 
										if($jversion == '1.5') { ?> <input class="inputbox required validate-checkstopdate" type="text" name="stoppublishing" id="job_stoppublishing" readonly class="Shadow Bold" size="10" value="<?php if(isset($this->job)) echo  date($this->config['date_format'],strtotime($this->job->stoppublishing)); ?>" />
										<?php } 
										else { echo JHTML::_('calendar', date($this->config['date_format'],  strtotime($this->job->stoppublishing)),'stoppublishing', 'job_stoppublishing',$js_dateformat,array('class'=>'inputbox required validate-checkstopdate', 'size'=>'10',  'maxlength'=>'19','readonly'=>'readonly')); ?>
										<?php }
											echo '<input type="hidden" name="stoppubreadonly" id="stoppubreadonly" value="1" size=""/>';
										 ?>
								  <?php }else{
										if($this->packagedetail[4] == 1) $enforcetype = JText::_('JS_DAYS');
										elseif($this->packagedetail[4] == 2) $enforcetype = JText::_('JS_WEEKS');
										elseif($this->packagedetail[4] == 3) $enforcetype = JText::_('JS_MONTHS');
										echo $this->packagedetail[3].' '.$enforcetype;?>
											<input type="hidden" name="stoppublishing" id="stoppublishing" value="<?php echo $this->packagedetail[3]; ?>" size=""/>
									    <?php }
                                }else {
								if(isset($this->job->stoppublishing)){
									if($jversion == '1.5'){ ?><input class="inputbox required validate-checkstopdate" type="text" name="stoppublishing" id="job_stoppublishing" readonly class="Shadow Bold" size="10" value="<?php if(isset($this->job)) echo  date($this->config['date_format'],strtotime($this->job->stoppublishing)); ?>" />
									<input type="reset" class="button" value="..." onclick="return showCalendar('job_stoppublishing','<?php echo $js_dateformat; ?>');"  />
									<?php 
									}else echo JHTML::_('calendar', date($this->config['date_format'],  strtotime($this->job->stoppublishing)),'stoppublishing', 'job_stoppublishing',$js_dateformat,array('class'=>'inputbox required validate-checkstopdate', 'size'=>'10',  'maxlength'=>'19')); ?>
						<?php   }else{
									if($jversion == '1.5'){ ?><input class="inputbox required validate-checkstopdate" type="text" name="stoppublishing" id="job_stoppublishing" readonly class="Shadow Bold" size="10" value="" />
									<input type="reset" class="button" value="..." onclick="return showCalendar('job_stoppublishing','<?php echo $js_dateformat; ?>');"  />
									<?php 
									}else echo JHTML::_('calendar','','stoppublishing', 'job_stoppublishing',$js_dateformat,array('class'=>'inputbox required validate-checkstopdate', 'size'=>'10',  'maxlength'=>'19'));
								} ?>
						<?php   } ?>
				</td>
			      </tr>
				<?php break;
				case "age": ?>
				<?php if ( $field->published == 1 ) { ?>
					<tr>
					 <td valign="top" align="right"><label id="agefrommsg" for="agefrom"><?php echo JText::_('JS_AGE'); ?></label><?php if($field->required == 1) echo '&nbsp;<font color="red">*</font>'; ?></td>
					 <td ><?php echo $this->lists['agefrom']; ?>&nbsp;&nbsp;&nbsp;
					 <?php echo $this->lists['ageto']; ?>
					 </td>
				       </tr>
				<?php } ?>
				<?php break;
				case "gender": ?>
				<?php if ( $field->published == 1 ) { ?>
					<tr>
					 <td valign="top" align="right"><label id="gendermsg" for="gender"><?php echo JText::_('JS_GENDER'); ?><?php if($field->required == 1) echo '&nbsp;<font color="red">*</font>'; ?></label></td>
					 <td ><?php echo $this->lists['gender']; ?></td>
				       </tr>
				<?php } ?>
				<?php break;
				case "careerlevel": ?>
				<?php if ( $field->published == 1 ) { ?>
					<tr>
					 <td valign="top" align="right"><label id="careerlevelmsg" for="careerlevel"><?php echo JText::_('JS_CAREER_LEVEL'); ?></label></td>
					 <td ><?php echo $this->lists['careerlevel']; ?></td>
				       </tr>
				<?php } ?>

				<?php break;
				case "workpermit": ?>
				<?php if ( $field->published == 1 ) { ?>
					<tr>
					 <td valign="top" align="right"><label id="workpermitmsg" for="workpermit"><?php echo JText::_('JS_WORK_PERMIT'); ?></label></td>
					 <td ><?php echo $this->lists['workpermit']; ?></td>
				       </tr>
				<?php } ?>
				<?php break;
				case "requiredtravel": ?>
				<?php if ( $field->published == 1 ) { ?>
					<tr>
					 <td valign="top" align="right"><label id="requiredtravelmsg" for="requiredtravel"><?php echo JText::_('JS_REQUIRED_TRAVEL'); ?></label></td>
					 <td ><?php echo $this->lists['requiredtravel']; ?></td>
				       </tr>
				<?php } ?>

				<?php break;
				case "description": ?>
					<?php if ( $this->config['job_editor'] == 1 ) { ?>
							<tr><td height="10" colspan="54"></td></tr>
							<tr>
								<td colspan="54" valign="top" align="center"><label id="descriptionmsg" for="description"><strong><?php echo JText::_('JS_DESCRIPTION'); ?></strong></label>&nbsp;<font color="red">*</font></td>
							</tr>
							<tr>
								<td colspan="54" align="center">
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
							<tr><td height="10" colspan="54"></td></tr>
							<tr>
								<td colspan="2" valign="top" align="center"><label id="agreementmsg" for="agreement"><strong><?php echo JText::_('JS_AGREEMENT'); ?></strong></label></td>
							</tr>
							<tr>
								<td colspan="54" align="center">
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
						<?php if ( $field->published == 1 ) { ?>
					<?php if ( $this->config['job_editor'] == 1 ) { ?>
							<tr><td height="10" colspan="54"></td></tr>
							<tr>
								<td colspan="54" valign="top" align="center"><label id="qualificationsmsg" for="qualifications"><strong><?php echo JText::_('JS_QUALIFICATIONS'); ?></strong></label></td>
							</tr>
							<tr>
								<td colspan="54" align="center">
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
					<?php } }?>
				<?php break;
				case "prefferdskills": ?>
	 			    <?php if ( $this->config['job_editor'] == 1 ) { ?>
						<?php if ( $field->published == 1 ) { ?>
							<tr><td height="10" colspan="54"></td></tr>
							<tr>
								<td colspan="54" valign="top" align="center"><label id="prefferdskillsmsg" for="prefferdskills"><strong><?php echo JText::_('JS_PREFFERD_SKILLS'); ?></strong></label></td>
							</tr>
							<tr>
								<td colspan="54" align="center">
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
						<?php if ( $field->published == 1 ) { ?>
							<tr>
								<td valign="top" align="right"><label id="prefferdskillsmsg" for="prefferdskills"><?php echo JText::_('JS_PREFFERD_SKILLS'); ?></label></td>
								<td >
									<textarea class="inputbox" name="prefferdskills" id="prefferdskills" cols="60" rows="5"><?php if(isset($this->job)) echo $this->job->prefferdskills; ?></textarea>
								</td>
							</tr>
							<?php } ?>
					<?php } ?>
					<?php break;
				case "city": ?>
					  <?php if ( $field->published == 1 ) { ?>
						<tr>
							<td  align="right"><label id="citymsg" for="city"><?php echo JText::_('JS_CITY'); ?></label>&nbsp;<?php if($field->required == 1){ echo '&nbsp;<font color="red">*</font>';} ?></td>
							<td id="job_city">
								<input class="inputbox" type="text" name="city" id="city" size="40" maxlength="100" value="" />
								<input class="inputbox" type="hidden" name="citynameforedit" id="citynameforedit" size="40" maxlength="100" value="<?php if(isset($this->multiselectedit)) echo $this->multiselectedit; ?>" />
							</td>
						</tr>
					  
					  
					  
					  <?php } ?>
			<?php break;
			case "metadescription": ?>
				  <tr>
			       <td align="right" ><label id="metadescriptionmsg" for="metadescription"><?php echo JText::_('JS_META_DESCRIPTION'); ?></label></td>
				 <td>
				<textarea cols="45" rows="5" class="inputbox " name="metadescription" id="metadescription" ><?php if(isset($this->job)) echo $this->job->metadescription; ?></textarea>
				</td>
			      </tr>
                        <?php break;
			case "metakeywords": ?>
				  <tr>
					<td  align="right" ><label id="metakeywordsmsg" for="metakeywords"><?php echo JText::_('JS_META_KEYWORDS'); ?></label></td>
					
					<td>
					  <textarea cols="45" rows="5" class="inputbox" name="metakeywords" id="metakeywords" ><?php if(isset($this->job)) echo $this->job->metakeywords; ?></textarea>
					</td>
			    </tr>
                        <?php break;
			case "video": ?>
				  <tr>
			        <td width="3%" align="right"><label id="videomsg" for="video"><?php echo JText::_('JS_VIDEO'); ?></label></td>
			          <td ><input class="inputbox" type="text" name="video" id="video" size="40" maxlength="255" value="<?php if(isset($this->job)) echo $this->job->video; ?>" />
                                      YouTube video id

			        </td>
			      </tr>
				<?php break;
				
				case "map": ?>
				  <tr>
			        <td width="3%" align="right"><label id="mapmsg" for="map"><?php echo JText::_('JS_MAP'); ?></label></td>
			        <td>
						<div id="map" ><div id="map_container"></div></div>
						<br/><input type="text" id="longitude" name="longitude" value="<?php if(isset($this->job)) echo $this->job->longitude;?>"/><?php echo JText::_('JS_LONGITUDE');?><!--<div id="coordinatebutton"><input type="button" class="cbutton" value="<?php echo JText::_('JS_GET_ADDRESS_FROM_MARKER');?>" onclick="Javascript: loadMap(2,'country','state','city');"/></div>-->
						<br/><input type="text" id="latitude" name="latitude" value="<?php if(isset($this->job)) echo $this->job->latitude;?>"/><?php echo JText::_('JS_LATITTUDE');?><div id="coordinatebutton"><input type="button" class="cbutton" value="<?php echo JText::_('JS_SET_MARKER_FROM_ADDRESS');?>" onclick="Javascript: loadMap(3,'country','state','city');"/></div>
			        </td>
			      </tr>
				<?php break;
				  
				default:
					if ( $field->published == 1 ) { 
					
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
										} else echo JHTML::_('calendar', $fvalue,'userfields_'.$i, 'userfields_'.$i,$js_dateformat,array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'19')); 
										break;
									case 'textarea':
										echo '<textarea name="userfields_'.$i.'" id="userfields_'.$i.'_field" cols="'.$userfield->cols.'" rows="'.$userfield->rows.'" '.$readonly.'>'.$fvalue.'</textarea>';
										break;	
									case 'checkbox':
										echo '<input type="checkbox" name="userfields_'.$i.'" id="userfields_'.$i.'_field" value="1" '.  'checked="checked"' .'/>';
										break;	
									case 'select':
										$htm = '<select name="userfields_'.$i.'" id="userfields_'.$i.'" >';
										if (isset ($ufield[2])) {
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
	<tr><td colspan="54" height="10"></td></tr>	  
	<tr>
		<td valign="top" width="3%" align="right"><label id="filter" for="filter"><?php echo JText::_('JS_FILTERS'); ?></label></td>
		<td >
			<div id="resumeapplyfilter">
			<table cellpadding="5" cellspacing="0" border="1" width="100%" class="adminform">
			<tr>
				<td>
				<table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
					<tr>
						<td colspan="8" align="left">
							<span id="jobsapplyalertsettingheading"><?php echo JText::_('JS_THIS_FILRER_ARE_APPLY_ON_APPLIED_RESUME');?></span>
						</td>
					</tr>
					<tr>
						<td><label for="raf_gender"><?php  echo JText::_('JS_GENDER'); ?></label></td>
						<td ><input type='checkbox' name='raf_gender' id='raf_gender' value='1' <?php if(isset($this->job)) { echo ($this->job->raf_gender == 1) ? "checked='checked'" : ""; } ?> /><?php  echo JText::_('JS_YES'); ?></td>
						<td><label for="raf_location"><?php  echo JText::_('JS_LOCATION'); ?></label></td>
						<td ><input type='checkbox' name='raf_location' id='raf_location' value='1' <?php if(isset($this->job)) { echo ($this->job->raf_location == 1) ? "checked='checked'" : ""; } ?> /><?php  echo JText::_('JS_YES'); ?></td>
						<td><label for="raf_education"><?php  echo JText::_('JS_EDUCATION'); ?></label></td>
						<td ><input type='checkbox' name='raf_education' id='raf_education' value='1' <?php if(isset($this->job)) { echo ($this->job->raf_education == 1) ? "checked='checked'" : ""; } ?> /><?php  echo JText::_('JS_YES'); ?></td>
					</tr>
					<tr>
						<td><label for="raf_category"><?php  echo JText::_('JS_CATEGORY'); ?></label></td>
						<td ><input type='checkbox' name='raf_category' id='raf_category' value='1' <?php if(isset($this->job)) { echo ($this->job->raf_category == 1) ? "checked='checked'" : ""; } ?> /> <?php echo JText::_('JS_YES'); ?></td>
						<td><label for="raf_subcategory"><?php  echo JText::_('JS_SUB_CATEGORY'); ?></label></td>
						<td ><input type='checkbox' name='raf_subcategory' id='raf_subcategory' value='1' <?php if(isset($this->job)) { echo ($this->job->raf_subcategory == 1) ? "checked='checked'" : ""; } ?> /> <?php  echo JText::_('JS_YES'); ?></td>
						<td></td>
						<td></td>
					</tr>
				</table>	
				</td>
			</tr>	
			</table>
		</div>
	</td>
	</tr>	  
	<tr><td colspan="54" height="10"></td></tr>	  
	<tr>
		<td valign="top" width="3%" align="right"><label id="filter" for="filter"><?php echo JText::_('JS_EMAIL_SETTING'); ?></label></td>
		<td >
			<div id="resumeapplyfilter">
			<table cellpadding="5" cellspacing="0" border="1" width="100%" class="adminform">
			<tr>
				<td>
				<table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
					<tr><td align="left"><span id="jobsapplyalertsettingheading"><?php echo JText::_('JS_JOB_APPLY_ALERT_EMAIL_SETTING');?></span></td></tr>
					<tr>
						<td>
							<input type="radio" name="sendemail" value="0" class="radio" <?php if(isset($this->job)) { echo ($this->job->sendemail == 0) ? "checked='checked'" : ""; } ?> /> 							
							<label for="sendemail"><?php  echo JText::_('JS_DO_NOT_EMAIL_ME'); ?></label>
						</td>
					</tr>
					<tr>
						<td>
							<span id="formjobemailtext">
								<?php  echo JText::_('JS_NO_NOTIFICATION_WILL_BE_EMAIL_TO_YOU_REGARDING_JOB_APPLICATIONS')."."; echo JText::_('JS_YOU_CAN_ALSO_CHECK_YOUR_WORKSPACE_FOR_NEW_APPLICANT')."."; ?>
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<input type="radio" name="sendemail" value="1" class="radio" <?php if(isset($this->job)) { echo ($this->job->sendemail == 1) ? "checked='checked'" : ""; } ?> /> 							
							<label for="sendemail"><?php  echo JText::_('JS_EMAIL_ME_THE_DAILY_COUNT_OF_NEW_APPLICANTS'); ?></label>
						</td>
					</tr>
					<tr>
						<td>
							<span id="formjobemailtext">
								<?php  echo JText::_('JS_THE_TOTAL_NUMBER_OF_NEW_APPLICANTS_FOR_EACH_DAY_WILL_BE_EMAIL_TO_YOU_ON_A_DAILY_BASIS')."."; echo JText::_('JS_YOU_CAN_ALSO_CHECK_YOUR_WORKSPACE_FOR_NEW_APPLICANT')."."; ?>
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<input type="radio" name="sendemail" value="2" class="radio" <?php if(isset($this->job)) { echo ($this->job->sendemail == 2) ? "checked='checked'" : ""; } ?> /> 							
							<label for="sendemail"><?php  echo JText::_('JS_EMAIL_ME_CVS_OF_NEW_APPLICANTS'); ?></label>
						</td>
					</tr>
					<tr>
						<td>
							<span id="formjobemailtext">
								<?php  echo JText::_('JS_EACH_APPLICANTS_CV_WILL_BE_EMAIL_DIRECTILY_TO_YOU_AS_THEY_APPLY')."."; echo JText::_('JS_YOU_CAN_ALSO_CHECK_YOUR_WORKSPACE_FOR_NEW_APPLICANT')."."; ?>
							</span>
						</td>
					</tr>
				</table>	
				</td>
			</tr>	
			</table>
		</div>
	</td>
	</tr>	  
	
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
			<input type="hidden" name="view" value="jobposting" />
			<input type="hidden" name="layout" value="viewjob" />
			<input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			<input type="hidden" name="task" value="savejob" />
			<input type="hidden" name="check" value="" />
			<input type="hidden" id="packageid" name="packageid" value="<?php echo $this->packagedetail[0]; ?>" />
			<input type="hidden" name="paymenthistoryid" id="paymenthistoryid" value="<?php echo $this->packagedetail[1]; ?>" />
			<input type="hidden" name="enforcestoppublishjob" id="enforcestoppublishjob" value="<?php echo $this->packagedetail[2]; ?>" />
			<input type="hidden" name="enforcestoppublishjobvalue" id="enforcestoppublishjobvalue" value="<?php echo $this->packagedetail[3]; ?>" />
			<input type="hidden" name="enforcestoppublishjobtype" id="enforcestoppublishjobtype" value="<?php echo $this->packagedetail[4]; ?>" />
			
			<input type="hidden" name="packagearray" id="packagearray" value="" />

			<input type="hidden" name="Itemid" id="Itemid" value="<?php echo $this->Itemid; ?>" />
			<input type="hidden" name="id" id="id" value="<?php if(isset($this->job)) echo $this->job->id; ?>" />
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
				countyhtml = "<input class='inputbox' type='text' name='county' id='county' size='40' maxlength='100' onBlur=updateMap('country','state','county','',2) />";
				cityhtml = "<input class='inputbox' type='text' name='city' id='city' size='40' maxlength='100' onBlur=updateMap('country','state','county','city',2) />";
				document.getElementById('job_county').innerHTML=countyhtml; //retuen value
				document.getElementById('job_city').innerHTML=cityhtml; //retuen value
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
} else{ // can not add new job?>
<?php
	$message = '';
	$e_p_link=JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=packages&Itemid='.$this->Itemid);
	if(empty($this->packagedetail[0]->packageexpiredays) && $this->packagecombo != 2){ //$this->packagecombo == 2 means user have no package
		$message = "<strong><font color='orangered'>".JText::_('JS_JOB_LIMIT_EXCEED')." <a href=".$e_p_link.">".JText::_('JS_EMPLOYER_PACKAGES')."</a></font></strong>";
	}elseif(empty($this->packagedetail[0]->id) && $this->packagecombo == 2){
		$message = "<strong><font color='orangered'>".JText::_('JS_JOB_NO_PACKAGE')." <a href=".$e_p_link.">".JText::_('JS_EMPLOYER_PACKAGES')."</a></font></strong>";
	}else{
		$days="";
		if((isset($this->packagedetail[0]->packageexpiredays)) AND (isset($this->packagedetail[0]->packageexpireindays)))
			$days = $this->packagedetail[0]->packageexpiredays - $this->packagedetail[0]->packageexpireindays;
		if($days == 1) $days = $days.' '.JText::_('JS_DAY'); else $days = $days.' '.JText::_('JS_DAYS');
		$package_title="";
		if(isset($this->packagedetail[0]->packagetitle)) $package_title=$this->packagedetail[0]->packagetitle;
		$message = "<strong><font color='red'>".JText::_('JS_YOUR_PACKAGE').' &quot;'.$package_title.'&quot; '.JText::_('JS_HAS_EXPIRED').' '.$days.' ' .JText::_('JS_AGO')." <a href='index.php?option=com_jsjobs&view=employer&layout=packages&Itemid=$this->Itemid'>".JText::_('JS_EMPLOYER_PACKAGES')."</a></font></strong>";
	} 
	if($message != ''){?>
<div id="errormessage" class="errormessage">
	<div id="message"><?php echo $message;?></div>
</div>
<?php }
}
} else{ // user has not company  ?>  
<div id="errormessage" class="errormessage">
	<div id="message"><?php echo JText::_('JS_PLEASE_ADD_COMPANY_BRFORE_NEW_JOB');?> <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formcompany"><?php echo "  ".JText::_('JS_ADD_COMPANY'); ?></a></div>
</div>
<?php
}

} else{ // not allowed job posting ?>
<div id="errormessage" class="errormessage">
	<div id="message"><?php echo JText::_('JS_YOU_ARE_NOT_ALLOWED_TO_VIEW');?></div>
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
<script type="text/javascript"   src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
 
<script type="text/javascript">
	
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
                    prePopulate: <?php if(isset($this->multiselectedit)) echo $this->multiselectedit;else echo "''"; ?>
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
	
	
	
	
function changeDate(id){
	var packagearray = '<?php echo json_encode($this->packagecombo[1]);?>';
	var objarray = eval('('+packagearray+')');
	var currentpackage = objarray[id];
	if(currentpackage.availgoldjobs < currentpackage.goldjobs || currentpackage.goldjobs == "-1"){
		jQuery("tr#markasgold td div").slideDown( "slow");
	}else{
		jQuery("tr#markasgold td div").slideUp( "slow");
		document.getElementById('goldjob').checked = false;
	}
	if(currentpackage.availfeaturedjobs < currentpackage.featuredjobs || currentpackage.featuredjobs == "-1"){
		jQuery('tr#markasfeatured td div').slideDown('slow');
	}else{
		jQuery('tr#markasfeatured td div').slideUp('slow');
		document.getElementById('featuredjob').checked = false;
	}
	document.getElementById('packageid').value = currentpackage.packageid;
	if(currentpackage.enforcestoppublishjob == 1){
		document.getElementById('paymenthistoryid').value = currentpackage.paymentid;
		document.getElementById('enforcestoppublishjob').value = currentpackage.enforcestoppublishjob;
		document.getElementById('enforcestoppublishjobvalue').value = currentpackage.enforcestoppublishjobvalue;
		document.getElementById('enforcestoppublishjobtype').value = currentpackage.enforcestoppublishjobtype;
		switch(currentpackage.enforcestoppublishjobtype){
			case "1":var durationtype = 'Days';break;
			case "2":var durationtype = 'Week';break;
			case "3":var durationtype = 'Month';break;
		}
		var days = currentpackage.enforcestoppublishjobvalue.toString();
		var duration = days+' '+durationtype;
		var stoppublishing = document.getElementById('stoppublishingdate').innerHTML;
		document.getElementById('stoppublishingdate').innerHTML = duration;
	}else{
		var jversion = '<?php echo $jversion;?>';
		if(jversion == '1.5'){
			var td = '<input class="inputbox required validate-checkstopdate" type="text" name="stoppublishing" id="job_stoppublishing" readonly class="Shadow Bold" size="10" value="" /><input type="reset" class="button" value="..." onclick="return showCalendar(\'job_stoppublishing\',\'<?php echo $js_dateformat; ?>\');"/>';
		}else{
			var oInput,oImg;
			oInput=document.createElement("INPUT");
		    oInput.name="stoppublishing";
		    oInput.setAttribute('id',"job_stoppublishing");
		    oInput.setAttribute('size',"10");
		    oInput.setAttribute('class',"inputbox required validate-checkstopdate");

			oImg=document.createElement("IMG");
		    oImg.src="<?php echo $link = JURI::root();?>/media/system/images/calendar.png";
		    oImg.setAttribute('id',"job_stoppublishing_img");

		    document.getElementById('stoppublishingdate').innerHTML = '';
			document.getElementById('stoppublishingdate').appendChild(oInput);
			document.getElementById('stoppublishingdate').appendChild(oImg);
			
			Calendar.setup({
				inputField     :    "job_stoppublishing",     // id of the input field
				ifFormat       :    "<?php echo $js_dateformat;?>",      // format of the input field
				button         :    "job_stoppublishing_img",  // trigger for the calendar (button ID)
				align          :    "Tl",           // alignment (defaults to "Bl")
				singleClick    :    true,
				firstDay       :    1
			});
		}
	}
}
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

					//lastmarker = marker;
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
			//lastmarker.setMap(null);
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
		//var values = [];
		var value='';
		var zoom=4;
		//value = jQuery("td#job_city > ul > li > p").html().each();
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
			//values.push(jQuery(this).html());
			
		});
	}
	if(callfrom == 2){
		
		var latLng = new google.maps.LatLng(latitude,longitude);
		geocoder = new google.maps.Geocoder();
		geocoder.geocode( { 'latLng': latLng}, function(results, status) {
		  if (status == google.maps.GeocoderStatus.OK) {
				
			lastmarker.setMap(null);
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
