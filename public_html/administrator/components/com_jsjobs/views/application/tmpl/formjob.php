<?php
/**
 * @Copyright Copyright (C) 2009-2011 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/views/application/tmpl/formjob.php
 ^ 
 * Description: Form template for a job
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access'); 
    $document = &JFactory::getDocument();
	$document->addStyleSheet('../components/com_jsjobs/css/token-input-jsjobs.css');
	$document->addStyleSheet(JURI::root().'administrator/components/com_jsjobs/include/css/jsjobsadmin.css');

$version = new JVersion;
$joomla = $version->getShortVersion();
$jversion = substr($joomla,0,3);
	if($jversion < 3){
		JHtml::_('behavior.mootools');
		$document->addScript('../components/com_jsjobs/js/jquery.js');
	}else{
		JHtml::_('behavior.framework');
		JHtml::_('jquery.framework');
	}	
	$document->addScript('../components/com_jsjobs/js/jquery.tokeninput.js');
$editor = &JFactory::getEditor();
JHTML::_('behavior.calendar');
JHTML::_('behavior.formvalidation');  


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
	#coordinatebutton .button{
		border-radius: 20px 20px 20px 20px;
		background: gray;
		color:ghostwhite;
	}
	#coordinatebutton .button:hover{
		background:black;
		color:ghostwhite;
	}
</style>
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
   document.formvalidator.setHandler('checkstartdate', function(value) {

		f = document.adminForm;
		var date_start_make = new Array();
		var split_start_value=new Array();
		var returnvalue = true;
		var isedit = document.getElementById("id");
		if(isedit.value!="" && isedit.value!=0  ) {
			var return_value=check_start_stop_job_publishing_dates();
			return return_value;
		}else{
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


	
function hideShowRange(hideSrc, showSrc, showName, showVal){
		document.getElementById(hideSrc).style.visibility = "hidden";
		document.getElementById(showSrc).style.visibility = "visible";
		document.getElementById(showName).value = showVal;
	
	}
// for joomla 1.6
Joomla.submitbutton = function(task){
        if (task == ''){
                return false;
        }else{
                if (task == 'save' ){
                    returnvalue = validate_form(document.adminForm);
                }else returnvalue  = true;
                if (returnvalue){
                        Joomla.submitform(task);
                        return true;
                }else return false;
        }
}
	
	
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
				var isedit = document.getElementById("id");
				if(isedit.value!="" && isedit.value!=0  ) { // for edit case
					msg.push('<?php echo JText::_('JS_START_PUBLISHING_DATE_MUST_BE_LESS_THEN_STOP_PUBLISHING_DATE'); ?>'); 

				}else{ // for add case
					msg.push('<?php echo JText::_('JS_PLEASE_ENTER_A_VALID_START_PUBLISHING_DATE'); ?>');

				}
					
            }
			var element_job_stoppublishing = document.getElementById('job_stoppublishing');                
			if(hasClass(element_job_stoppublishing,'invalid')){
			msg.push('<?php echo JText::_('JS_PLEASE_ENTER_A_VALID_STOP_PUBLISHING_DATE'); ?>');
            }
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


			<form action="index.php" method="POST" name="adminForm" id="adminForm">
				<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
				<?php
				if($this->msg != ''){
				?>
				 <tr>
			        <td colspan="2" align="center"><font color="red"><strong><?php echo JText::_($this->msg); ?></strong></font></td>
			      </tr>
				  <tr><td colspan="2" height="10"></td></tr>	
				<?php
				}
				?>
		<?php
		$trclass = array("row0", "row1");
		$isodd = 1;
		$i = 0;
		foreach($this->fieldsordering as $field){ 
			switch ($field->field) {
				case "jobtitle":  $isodd = 1 - $isodd; ?>
				  <tr class="<?php echo $trclass[$isodd]; ?>">
			        <td width="20%" align="right"><label id="titlemsg" for="title"><?php echo JText::_('JS_JOB_TITLE'); ?></label>&nbsp;<font color="red">*</font></td>
			          <td width="60%"><input class="inputbox required" type="text" name="title" id="title" size="40" maxlength="255" value="<?php if(isset($this->job)) echo $this->job->title; ?>" />
			        </td>
			      </tr>
				<?php break;
				case "company":
                                    $showcompany = 1;
                                    if (isset($this->job)) if ($this->job->uid != $this->uid) $showcompany = 0;
                                    if ($showcompany == 1) {
                                            $isodd = 1 - $isodd;  ?>
                                          <tr class="<?php echo $trclass[$isodd]; ?>">
                                        <td valign="top" align="right"><label id="companymsg" for="company"><?php echo JText::_('JS_COMPANY'); ?></label>&nbsp;<font color="red">*</font></td>
                                        <td><?php echo $this->lists['companies']; ?></td>
                                      </tr>
				<?php } break;
				case "department":  $isodd = 1 - $isodd; ?>
				  <tr  class="<?php echo $trclass[$isodd]; ?>">
			        <td valign="top" align="right"><label id="departmentmsg" for="department"><?php echo JText::_('JS_DEPARTMENT'); ?></label></td>
			        <td id="department" ><?php if(isset($this->lists['departments'])) echo $this->lists['departments']; ?></td>
				</tr>
				<?php break;
				case "jobcategory":  $isodd = 1 - $isodd; ?>
			      <tr class="<?php echo $trclass[$isodd]; ?>">
			        <td valign="top" align="right"><?php echo JText::_('JS_CATEGORY'); ?></td>
			        <td><?php echo $this->lists['jobcategory']; ?></td>
			      </tr>
				<?php break;
				case "subcategory":  $isodd = 1 - $isodd; ?>
			      <tr class="<?php echo $trclass[$isodd]; ?>">
			        <td valign="top" align="right"><?php echo JText::_('JS_SUB_CATEGORY'); ?></td>
			        <td id="fj_subcategory"><?php echo $this->lists['subcategory']; ?></td>
			      </tr>
				<?php break;
				case "jobtype":  $isodd = 1 - $isodd; ?>
				  <tr class="<?php echo $trclass[$isodd]; ?>">
			        <td valign="top" align="right"><?php echo JText::_('JS_JOBTYPE'); ?></td>
			        <td><?php echo $this->lists['jobtype']; ?></td>
			      </tr>
				<?php break;
				case "jobstatus": $isodd = 1 - $isodd;  ?>
				  <tr class="<?php echo $trclass[$isodd]; ?>">
			        <td valign="top" align="right"><?php echo JText::_('JS_JOBSTATUS'); ?></td>
			        <td><?php echo $this->lists['jobstatus']; ?></td>
			      </tr>
				<?php break;
				case "heighesteducation":  ?>
			      <?php if ( $field->published == 1 ) { $isodd = 1 - $isodd; ?>
				  <tr class="<?php echo $trclass[$isodd]; ?>">
					<td valign="top" align="right"><?php echo JText::_('JS_EDUCATION'); ?></td>
					<td height="31" colspan="53"  valign="top">
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
							<a  onclick="hideShowRange('educationminimaxdiv','educationrangediv','iseducationminimax',0);"><?php echo JText::_('JS_SPECIFY_RANGE'); ?></a>
						</div>
						<div id="educationrangediv" style="<?php echo $educationrangedivstyle; ?>">
							<?php echo $this->lists['minimumeducationrange']; ?>&nbsp;&nbsp;&nbsp;
							<?php echo $this->lists['maximumeducationrange']; ?>&nbsp;&nbsp;&nbsp;
							<a onclick="hideShowRange('educationrangediv','educationminimaxdiv','iseducationminimax',1);"><?php echo JText::_('JS_CANCEL_RANGE'); ?></a>						
						</div>
					</td>
				      </tr>
					<?php $isodd = 1 - $isodd;?>
					<tr class="<?php echo $trclass[$isodd]; ?>">
					<td valign="top" align="right"><label id="degreetitlesmsg" for="degreetitle"><?php echo JText::_('JS_DEGREE_TITLE'); ?></label></td>
					<td colspan="53"><input class="inputbox" type="text" name="degreetitle" id="degreetitle" size="30" maxlength="40" value="<?php if(isset($this->job)) echo $this->job->degreetitle; ?>" />
					</td>
					</tr>
					<?php } ?>
				<?php break;
				case "jobshift": ?>
				  <?php if ( $field->published == 1 ) {   $isodd = 1 - $isodd;?>
			      <tr class="<?php echo $trclass[$isodd]; ?>">
			        <td valign="top" align="right"><?php echo JText::_('JS_SHIFT'); ?></td>
			        <td><?php echo $this->lists['shift']; ?></td>
			      </tr>
				   <?php } ?>
				<?php break;
				case "jobsalaryrange":  ?>
			      <?php if ( $field->published == 1 ) { $isodd = 1 - $isodd;?>
				  <tr class="<?php echo $trclass[$isodd]; ?>">
			        <td valign="top" align="right"><?php echo JText::_('JS_SALARYRANGE'); ?></td>
			        <td colspan="53">
					<?php echo $this->lists['currencyid']; ?>&nbsp;&nbsp;&nbsp;
					<?php echo $this->lists['salaryrangefrom']; ?>&nbsp;&nbsp;&nbsp;
					<?php echo $this->lists['salaryrangeto']; ?>&nbsp;&nbsp;&nbsp;
					<?php echo $this->lists['salaryrangetypes']; ?>&nbsp;&nbsp;&nbsp;
				</td>
			      </tr>
				  <?php } ?>
				<?php break;
				case "heighesteducation":  ?>
				    <?php if ( $field->published == 1 ) { $isodd = 1 - $isodd; ?>
					<tr class="<?php echo $trclass[$isodd]; ?>">
					<td valign="top" align="right"><?php echo JText::_('JS_HEIGHEST_EDUCATION'); ?></td>
					<td><?php echo $this->lists['heighesteducation']; ?></td>
					</tr>
					<?php } ?>
				<?php break;
				case "noofjobs":  $isodd = 1 - $isodd; ?>
					<tr class="<?php echo $trclass[$isodd]; ?>">
					<td valign="top" align="right"><label id="noofjobsmsg" for="noofjobs"><?php echo JText::_('JS_NOOFJOBS'); ?></label>&nbsp;<font color="red">*</font></td>
					<td><input class="inputbox  required validate-numeric" type="text" name="noofjobs" id="noofjobs" size="10" maxlength="10" value="<?php if(isset($this->job)) echo $this->job->noofjobs; ?>" />
					</td>
					</tr>
				<?php break;
				case "experience":  ?>
			      <?php if ( $field->published == 1 ) { $isodd = 1 - $isodd;?>
			       <tr class="<?php echo $trclass[$isodd]; ?>">
			        <td valign="top" align="right"><label id="experiencesmsg" for="experience"><?php echo JText::_('JS_EXPERIENCE'); ?></label></td>
			        <td height="31" colspan="53"  valign="top">
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
						<a  onclick="hideShowRange('experienceminimaxdiv','experiencerangediv','isexperienceminimax',0);"><?php echo JText::_('JS_SPECIFY_RANGE'); ?></a>
					</div>
					<div id="experiencerangediv" style="<?php echo $experiencerangedivstyle; ?>">
						<?php echo $this->lists['minimumexperiencerange']; ?>&nbsp;&nbsp;&nbsp;
						<?php echo $this->lists['maximumexperiencerange']; ?>&nbsp;&nbsp;&nbsp;
						<a onclick="hideShowRange('experiencerangediv','experienceminimaxdiv','isexperienceminimax',1);"><?php echo JText::_('JS_CANCEL_RANGE'); ?></a>						
					</div>
			        </td>
						
			      </tr>
				<?php $isodd = 1 - $isodd;?>
			       <tr class="<?php echo $trclass[$isodd]; ?>">
			        <td valign="top" align="right"></td>
			        <td height="31" colspan="53"  valign="top">
					<input class="inputbox" type="text" name="experiencetext" id="experiencetext" size="30" maxlength="150" value="<?php if(isset($this->job)) echo $this->job->experiencetext; ?>" />
					&nbsp;&nbsp;&nbsp;<?php echo JText::_('JS_ANY_OTHER_EXPERIENCE'); ?>
			        </td>
			      </tr>
				  <?php } ?>
				<?php break;
				case "duration":  ?>
			      <?php if ( $field->published == 1 ) { $isodd = 1 - $isodd; ?>
			       <tr class="<?php echo $trclass[$isodd]; ?>">
			        <td valign="top" align="right"><label id="durationmsg" for="duration"><?php echo JText::_('JS_DURATION'); ?></label></td>
			        <td><input class="inputbox" type="text" name="duration" id="duration" size="10" maxlength="15" value="<?php if(isset($this->job)) echo $this->job->duration; ?>" />
			        <?php echo JText::_('JS_DURATION_DESC'); ?>
					</td>
			      </tr>
				  <?php } ?>
				<?php break;
				case "map":  $isodd = 1 - $isodd;?>
				  <tr class="<?php echo $trclass[$isodd]; ?>">
			        <td width="3%" align="right"><label id="mapmsg" for="map"><?php echo JText::_('JS_MAP'); ?></label></td>
			        <td>
						<div id="map_container"></div>
						<br/><input type="text" id="longitude" name="longitude" value="<?php if(isset($this->job)) echo $this->job->longitude;?>"/><?php echo JText::_('JS_LONGITUDE');?><!--<div id="coordinatebutton"><input type="button" class="button" value="<?php echo JText::_('JS_GET_ADDRESS_FROM_MARKER');?>" onclick="Javascript: loadMap(2,'country','state','county','city');"/></div>-->
						<br/><input type="text" id="latitude" name="latitude" value="<?php if(isset($this->job)) echo $this->job->latitude;?>"/><?php echo JText::_('JS_LATITTUDE');?><div id="coordinatebutton"><input type="button" class="button" value="<?php echo JText::_('JS_SET_MARKER_FROM_ADDRESS');?>" onclick="Javascript: loadMap(3);"/></div>
			        </td>
			      </tr>
				<?php break;
				case "startpublishing":  $isodd = 1 - $isodd; ?>
					<?php 
						$startdatevalue = '';
						if(isset($this->job)) $startdatevalue = date($this->config['date_format'],strtotime($this->job->startpublishing));
						?>
						<tr class="<?php echo $trclass[$isodd]; ?>">
							<td valign="top" align="right"><label id="startpublishingmsg" for="startpublishing"><?php echo JText::_('JS_START_PUBLISHING'); ?></label>&nbsp;<font color="red">*</font></td>
							<td ><?php if(isset($this->job)){ //edit
								if($jversion == '1.5') { ?> <input class="inputbox required validate-checkstartdate" type="text" name="startpublishing" id="job_startpublishing" readonly class="Shadow Bold" size="10" value="<?php if(isset($this->job)) echo  date($this->config['date_format'],strtotime($this->job->startpublishing)); ?>" />
								<input type="reset" class="button" value="..." onclick="return showCalendar('job_startpublishing','<?php echo $js_dateformat; ?>');"  />
									<?php } else echo JHTML::_('calendar', $startdatevalue,'startpublishing', 'job_startpublishing',$js_dateformat,array('class'=>'inputbox required validate-checkstartdate', 'size'=>'10',  'maxlength'=>'19' )); ?>
								<?php //}
							}else { 
								if($jversion == '1.5'){ ?><input class="inputbox required validate-checkstartdate" type="text" name="startpublishing" id="job_startpublishing" readonly class="Shadow Bold" size="10" value="<?php if(isset($this->job)) echo  date($this->config['date_format'],strtotime($this->job->startpublishing)); ?>" />
								<input type="reset" class="button" value="..." onclick="return showCalendar('job_startpublishing','<?php echo $js_dateformat; ?>');"  />

								<?php 
								}else	echo JHTML::_('calendar', $startdatevalue,'startpublishing', 'job_startpublishing',$js_dateformat,array('class'=>'inputbox required validate-checkstartdate', 'size'=>'10',  'maxlength'=>'19')); ?>

						<?php } ?>
					</td>
					</tr>
				<?php break;
				case "stoppublishing":  $isodd = 1 - $isodd; ?>
					<?php 
						$stopdatevalue = '';
						if(isset($this->job)) $stopdatevalue = date($this->config['date_format'],strtotime($this->job->stoppublishing));
						?>
			       <tr class="<?php echo $trclass[$isodd]; ?>">
			        <td valign="top" align="right"><label id="stoppublishingmsg" for="stoppublishing"><?php echo JText::_('JS_STOP_PUBLISHING'); ?></label>&nbsp;<font color="red">*</font></td>
			        <td ><?php if(isset($this->job)){
                                        if($jversion == '1.5') { ?> <input class="inputbox required validate-checkstopdate" type="text" name="stoppublishing" id="job_stoppublishing" readonly class="Shadow Bold" size="10" value="<?php if(isset($this->job)) echo  date($this->config['date_format'],strtotime($this->job->stoppublishing)); ?>" />
                                        <input type="reset" class="button" value="..." onclick="return showCalendar('job_stoppublishing','<?php echo $js_dateformat; ?>');"  />
                                        <?php }
                                        else  { echo JHTML::_('calendar', $stopdatevalue,'stoppublishing', 'job_stoppublishing',$js_dateformat,array('class'=>'inputbox required validate-checkstopdate', 'size'=>'10',  'maxlength'=>'19')); ?>
                                        <?php }
                                }else {
                                        if($jversion == '1.5'){ ?><input class="inputbox required validate-checkstopdate" type="text" name="stoppublishing" id="job_stoppublishing" readonly class="Shadow Bold" size="10" value="<?php if(isset($this->job)) echo  date($this->config['date_format'],strtotime($this->job->stoppublishing)); ?>" />
                                                <input type="reset" class="button" value="..." onclick="return showCalendar('job_stoppublishing','<?php echo $js_dateformat; ?>');"  />

                                        <?php
                                        }else echo JHTML::_('calendar', $stopdatevalue,'stoppublishing', 'job_stoppublishing',$js_dateformat,array('class'=>'inputbox required validate-checkstopdate', 'size'=>'10',  'maxlength'=>'19')); ?>

                            <?php   } ?>
				</td>
			      </tr>
				<?php break;
				case "age":  ?>
				<?php if ( $field->published == 1 ) { $isodd = 1 - $isodd;?>
					<tr class="<?php echo $trclass[$isodd]; ?>">
					 <td valign="top" align="right"><label id="agefrommsg" for="agefrom"><?php echo JText::_('JS_AGE'); ?></label><?php if($field->required == 1) echo '&nbsp;<font color="red">*</font>'; ?></td>
					 <td colspan="53"><?php echo $this->lists['agefrom']; ?>&nbsp;&nbsp;&nbsp;
					 <?php echo $this->lists['ageto']; ?>
					 </td>
				       </tr>
				<?php } ?>
				<?php break;
				case "gender":  ?>
				<?php if ( $field->published == 1 ) { $isodd = 1 - $isodd; ?>
					<tr class="<?php echo $trclass[$isodd]; ?>">
					 <td valign="top" align="right"><label id="gendermsg" for="gender"><?php echo JText::_('JS_GENDER'); ?><?php if($field->required == 1) echo '&nbsp;<font color="red">*</font>'; ?></label></td>
					 <td colspan="53"><?php echo $this->lists['gender']; ?></td>
				       </tr>
				<?php } ?>
				<?php break;
				case "careerlevel":  ?>
				<?php if ( $field->published == 1 ) { $isodd = 1 - $isodd;?>
					<tr class="<?php echo $trclass[$isodd]; ?>">
					 <td valign="top" align="right"><label id="careerlevelmsg" for="careerlevel"><?php echo JText::_('JS_CAREER_LEVEL'); ?></label></td>
					 <td colspan="53"><?php echo $this->lists['careerlevel']; ?></td>
				       </tr>
				<?php } ?>

				<?php break;
				case "workpermit":  ?>
				<?php if ( $field->published == 1 ) { $isodd = 1 - $isodd;?>
					<tr class="<?php echo $trclass[$isodd]; ?>">
					 <td valign="top" align="right"><label id="workpermitmsg" for="workpermit"><?php echo JText::_('JS_WORK_PERMIT'); ?></label></td>
					 <td colspan="53"><?php echo $this->lists['workpermit']; ?></td>
				       </tr>
				<?php } ?>
				<?php break;
				case "requiredtravel":  ?>
				<?php if ( $field->published == 1 ) { $isodd = 1 - $isodd;?>
					<tr class="<?php echo $trclass[$isodd]; ?>">
					 <td valign="top" align="right"><label id="requiredtravelmsg" for="requiredtravel"><?php echo JText::_('JS_REQUIRED_TRAVEL'); ?></label></td>
					 <td colspan="53"><?php echo $this->lists['requiredtravel']; ?></td>
				       </tr>
				<?php } ?>

				<?php break;
				case "description": $isodd = 1 - $isodd; ?>
					<?php if ( $this->config['job_editor'] == 1 ) { ?>
							<tr class="<?php echo $trclass[$isodd]; ?>"><td height="10" colspan="54"></td></tr>
							<tr class="<?php echo $trclass[$isodd]; ?>">
								<td colspan="54" valign="top" align="center"><label id="descriptionmsg" for="description"><strong><?php echo JText::_('JS_DESCRIPTION'); ?></strong></label>&nbsp;<font color="red">*</font></td>
							</tr>
							<tr class="<?php echo $trclass[$isodd]; ?>">
								<td colspan="54" align="center">
								<?php
									$editor =& JFactory::getEditor();
									if(isset($this->job))
										echo $editor->display('description', $this->job->description, '550', '300', '60', '20', false);
									else
										echo $editor->display('description', '', '550', '300', '60', '20', false);

								?>	
								</td>
							</tr>
					<?php }else{ ?>
							<tr class="<?php echo $trclass[$isodd]; ?>">
								<td valign="top" align="right"><label id="descriptionmsg" for="description"><?php echo JText::_('JS_DESCRIPTION'); ?></label>&nbsp;<font color="red">*</font></td>
								<td colspan="53"><textarea class="inputbox required" name="description" id="description" cols="60" rows="5"><?php if(isset($this->job)) echo $this->job->description; ?></textarea></td>
							</tr>
					<?php } ?>
					
				<?php break;
				case "qualifications":  $isodd = 1 - $isodd; ?>
	 			    <?php if ( $this->config['job_editor'] == 1 ) { ?>
							<?php if ( $field->published == 1 ) { ?>
							<tr><td height="10" colspan="2"></td></tr>
							<tr class="<?php echo $trclass[$isodd]; ?>">
								<td colspan="2" valign="top" align="center"><label id="qualificationsmsg" for="qualifications"><strong><?php echo JText::_('JS_QUALIFICATIONS'); ?></strong></label></td>
							</tr>
							<tr class="<?php echo $trclass[$isodd]; ?>">
								<td colspan="2" align="center">
								<?php
									$editor =& JFactory::getEditor();
									if(isset($this->job))
										echo $editor->display('qualifications', $this->job->qualifications, '550', '300', '60', '20', false);
									else
										echo $editor->display('qualifications', '', '550', '300', '60', '20', false);

								?>	
								</td>
							</tr>
							<?php } ?>
					<?php }else{ ?>
							<?php if ( $field->published == 1 ) { ?>
							<tr class="<?php echo $trclass[$isodd]; ?>">
								<td valign="top" align="right"><?php echo JText::_('JS_QUALIFICATIONS');?></td>
								<td><textarea class="inputbox" name="qualifications" id="qualifications" cols="60" rows="5"><?php if(isset($this->job)) echo $this->job->qualifications; ?></textarea></td>
							</tr>
							<?php } ?>
					<?php } ?>
				<?php break;
				case "prefferdskills":  $isodd = 1 - $isodd; ?>
	 			    <?php if ( $this->config['job_editor'] == 1 ) { ?>
							<?php if ( $field->published == 1 ) { ?>
							<tr><td height="10" colspan="2"></td></tr>
							<tr class="<?php echo $trclass[$isodd]; ?>">
								<td colspan="2" valign="top" align="center"><label id="prefferdskillsmsg" for="prefferdskills"><strong><?php echo JText::_('JS_PREFFERD_SKILLS'); ?></strong></label></td>
							</tr>
							<tr class="<?php echo $trclass[$isodd]; ?>">
								<td colspan="2" align="center">
								<?php
									$editor =& JFactory::getEditor();
									if(isset($this->job))
										echo $editor->display('prefferdskills', $this->job->prefferdskills, '550', '300', '60', '20', false);
									else
										echo $editor->display('prefferdskills', '', '550', '300', '60', '20', false);
								?>	
								</td>
							</tr>
							<?php } ?>

					<?php }else{ ?>
							<?php if ( $field->published == 1 ) { ?>
							<tr class="<?php echo $trclass[$isodd]; ?>">
								<td valign="top" align="right"><label id="prefferdskillsmsg" for="prefferdskills"><?php echo JText::_('JS_PREFFERD_SKILLS'); ?></label></td>
								<td>
									<textarea class="inputbox" name="prefferdskills" id="prefferdskills" cols="60" rows="5"><?php if(isset($this->job)) echo $this->job->prefferdskills; ?></textarea>
								</td>
							</tr>
							<?php } ?>
					<?php } ?>
				<?php break;
				case "city":   ?>
					  <?php if ( $field->published == 1 ) { $isodd = 1 - $isodd;?>
				      <tr class="<?php echo $trclass[$isodd]; ?>">
				        <td align="right"><label id="citymsg" for="city"><?php echo JText::_('JS_CITY'); ?></label></td>
				        <td id="citytd">
								<input class="inputbox" type="text" name="city" id="city" size="40" maxlength="100" value="" />
								<input class="inputbox" type="hidden" name="citynameforedit" id="citynameforedit" size="40" maxlength="100" value="<?php if(isset($this->multiselectedit)) echo $this->multiselectedit; ?>" />
							<?php /*
							if((isset($this->lists['city'])) && ($this->lists['city']!='')){
								echo $this->lists['city']; 
							} else{ ?>
								<input class="inputbox" type="text" name="city" id="city" size="40" maxlength="100" value="<?php if(isset($this->job)) echo $this->job->city; ?>" />
							<?php } */ ?>
				        </td>
				      </tr>
					  <?php } ?>
				<?php break;
				case "video": $isodd=1-$isodd; ?>
					<tr class="<?php echo $trclass[$isodd];?>">
					<td valign="top" align="right"><?php echo JText::_('JS_VIDEO');?></td>
			          <td width="60%"><input type="text" name="video" id="video" size="40" maxlength="255" value="<?php if(isset($this->job)) echo $this->job->video; ?>" />
										</tr>
				 <?php break; 
				 case "map": $isodd=1-$isodd;?>
				 <tr class="<?php echo $trclass[$isodd];?>">
				 <td valign="top" align="right"><?php echo JText::_('JS_MAP');?></td>
			          <td width="60%"><input type="text" name="map" id="map" size="40" maxlength="255" value="<?php if(isset($this->job)) echo $this->job->map; ?>" />
				 
				 </tr>
				 <?php break;
				 case "agreement": $isodd=1-$isodd;?>
				 				<?php //echo 'job editor'.$this->config['job_editor'];
					 if ( $this->config['job_editor'] == 1 ) { ?>
							<tr><td height="10" colspan="2"></td></tr>
							<tr class="<?php echo $trclass[$isodd]; ?>">
								<td colspan="2" valign="top" align="center"><label id="agreementmsg" for="agreement"><strong><?php echo JText::_('JS_AGREEMENT'); ?></strong></label></td>
							</tr>
							<tr class="<?php echo $trclass[$isodd]; ?>">
								<td colspan="2" align="center">
								<?php
									$editor =& JFactory::getEditor();
									if(isset($this->job))
										echo $editor->display('agreement', $this->job->agreement, '550', '300', '60', '20', false);
									else
										echo $editor->display('agreement', '', '550', '300', '60', '20', false);

								?>	
								</td>
							</tr>
					<?php }else{ ?>
							<tr class="<?php echo $trclass[$isodd]; ?>">
								<td valign="top" align="right"><label id="agreementmsg" for="agreement"><?php echo JText::_('JS_AGREEMENT'); ?></label></td>
								<td><textarea class="inputbox " name="agreement" id="agreement" cols="60" rows="5"><?php if(isset($this->job)) echo $this->job->agreement; ?></textarea></td>
							</tr>
					<?php } ?>
				 <?php break;
				 case "metadescription": $isodd=1-$isodd;?>
							<tr class="<?php echo $trclass[$isodd]; ?>">
								<td valign="top" align="right"><label id="metadescriptionmsg" for="metadescription"><?php echo JText::_('JS_META_DESCRIPTION'); ?></label></td>
								<td><textarea class="inputbox " name="metadescription" id="metadescription" cols="60" rows="5"><?php if(isset($this->job)) echo $this->job->metadescription; ?></textarea></td>
							</tr>
					
				 <?php break;
				  case "metakeywords": $isodd=1-$isodd;?>
				  				
							<tr class="<?php echo $trclass[$isodd]; ?>">
								<td valign="top" align="right"><label id="metakeywordsmsg" for="metakeywords"><?php echo JText::_('JS_META_KEYWORDS'); ?></label></td>
								<td><textarea class="inputbox " name="metakeywords" id="metakeywords" cols="60" rows="5"><?php if(isset($this->job)) echo $this->job->metakeywords; ?></textarea></td>
							</tr>
					
					 <?php break;
				default:
					//echo '<br> default uf '.$filed->field;
			        if ( $field->published == 1 ) { 
						foreach($this->userfields as $ufield){ 
							if($field->field == $ufield[0]->id) {
								$userfield = $ufield[0];
								$i++;
								$isodd = 1 - $isodd; 
								echo "<tr class='".$trclass[$isodd]."'><td valign='top' align='right'>";
								if($userfield->required == 1){
									echo "<label id=".$userfield->name."msg for=$userfield->name>$userfield->title</label>&nbsp;<font color='red'>*</font>";
									$cssclass = "class ='inputbox required' ";
								}else{
									echo $userfield->title; $cssclass = "class='inputbox' ";
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
	<tr><td colspan="54" height="10"></td></tr>	  
	<tr class="<?php echo $trclass[$isodd];$isodd=1-$isodd; ?>">
		<td valign="top" width="3%" align="right"><label id="filter" for="filter"><?php echo JText::_('JS_FILTERS'); ?></label></td>
		<td >
			<div >
			<table id="filteradmintable" cellpadding="5" cellspacing="0" border="0" width="100%" >
			<tr>
				<td>
				<table cellpadding="5" cellspacing="0" border="0" width="100%" >
					<tr>
						<td colspan="8" align="left">
							<span ><?php echo JText::_('JS_THIS_FILRER_ARE_APPLY_ON_APPLIED_RESUME');?></span>
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
						<td ><input type='checkbox' name='raf_experience' id='raf_experience' value='1' <?php if(isset($this->job)) { echo ($this->job->raf_experience == 1) ? "checked='checked'" : ""; } ?> /><?php echo JText::_('JS_YES'); ?></td>-->
					</tr>
				</table>	
				</td>
			</tr>	
			</table>
		</div>
	</td>
	</tr>	  

	<tr><td colspan="54" height="10"></td></tr>	  
	<tr class="<?php echo $trclass[$isodd];$isodd=1-$isodd; ?>">
		<td valign="top" width="3%" align="right"><label id="filter" for="filter"><?php echo JText::_('JS_EMAIL_SETTING'); ?></label></td>
		<td >
			<div >
			<table cellpadding="5" cellspacing="0" border="0" width="100%" >
			<tr>
				<td>
				<table cellpadding="5" cellspacing="0" border="0" width="100%" >
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







	  
			<?php  if(isset($this->job)) {  $isodd = 1 - $isodd; ?>
			  <tr class="<?php echo $trclass[$isodd]; ?>">
				<td align="right"><label id="statusmsg" for="status"><?php echo JText::_('JS_STATUS'); ?></label></td>
				<td><?php  echo $this->lists['status']; ?>
				</td>
			  </tr>
			<?php }else { ?>
				<input type="hidden" name="status" value="1" />
			<?php } ?>	
	<tr>
		<td colspan="2" align="center">
		<input class="button" type="submit" name="submit_app" onclick="return validate_form(document.adminForm)" value="<?php echo JText::_('JS_SAVE_JOB'); ?>" />
		</td>
	</tr>

			    </table>
			<?php 	
				if(isset($this->job)) {
					$uid = $this->job->uid;
					if (($this->job->created=='0000-00-00 00:00:00') || ($this->job->created==''))
						$curdate = date('Y-m-d H:i:s');
					else  
						$curdate = $this->job->created;
				}else{
					$uid = $this->uid;
					$curdate = date('Y-m-d H:i:s');
				}	
				
			?>
			<input type="hidden" name="created" value="<?php echo $curdate; ?>" />
			<input type="hidden" name="view" value="jobposting" />
			<input type="hidden" name="check" value="" />
			<input type="hidden" name="layout" value="viewjob" />
			<input type="hidden" name="uid" value="<?php echo $uid; ?>" />
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			<input type="hidden" name="task" value="savejob" />
			
		  <input type="hidden" name="id" id="id" value="<?php if(isset($this->job)) echo $this->job->id; ?>" />
		  <input type="hidden" name="default_longitude" id="default_longitude" value="<?php echo $this->config['default_longitude']; ?>" />
		  <input type="hidden" name="default_latitude" id="default_latitude" value="<?php  echo $this->config['default_latitude']; ?>" />
		  <input type="hidden" name="j_dateformat" id="j_dateformat" value="<?php  echo $js_scriptdateformat; ?>" />


<script language=Javascript>
function dochange(src, val){
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
        	document.getElementById(src+'td').innerHTML=xhr.responseText; //retuen value

			if(src=='state'){
				cityhtml = "<input class='inputbox' type='text' name='city' id='city' size='40' maxlength='100'  />";
				document.getElementById('citytd').innerHTML=cityhtml; //retuen value
			}
      }
    }
 
	xhr.open("GET","index.php?option=com_jsjobs&task=listaddressdata&data="+src+"&val="+val,true);
	xhr.send(null);
}
			//window.onLoad=dochange('country', -1);         // value in first dropdown
			
			
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
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			
		</td>
	</tr>
	
</table>				
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
  window.onload = loadMap(1);        
  function loadMap(callfrom) {
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
				/*var lastmarker = new google.maps.Marker({
					postiion:latlng,
					map:map,
				});*/
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

				//alert(document.getElementById('latitude').value+document.getElementById('longitude').value);
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
		jQuery("td#citytd > ul > li > p").each(function(){
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
						document.getElementById('statetd').innerHTML = obj.states;
						document.getElementById('countytd').innerHTML = obj.counties;
						document.getElementById('citytd').innerHTML = obj.city;
					}
				}

			xhr.open("GET","index.php?option=com_jsjobs&task=getaddressdata&val="+address,true);
			xhr.send(null);
		}
		});
	}

}
</script>
