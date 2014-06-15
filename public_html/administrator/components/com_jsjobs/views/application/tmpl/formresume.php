<?php
/**
 * @Copyright Copyright (C) 2009-2011 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	views/application/tmpl/formresume.php
 ^ 
 * Description: template for resume
 ^ 
 * History:		NONE
 ^ 
 */

defined('_JEXEC') or die('Restricted access');
//jimport('joomla.html.pane');
$document = &JFactory::getDocument();
$document->addStyleSheet('components/com_jsjobs/include/css/jsjobsadmin.css');
$document->addStyleSheet('../components/com_jsjobs/css/token-input-jsjobs.css');

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
	$document->addScript('components/com_jsjobs/include/js/jquery_idTabs.js');


 global $mainframe;
JHTML::_('behavior.calendar');
JHTML::_('behavior.formvalidation');

$version = new JVersion;
$joomla = $version->getShortVersion();
$jversion = substr($joomla,0,3);

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
	
 $ADMINPATH = JPATH_BASE .'\components\com_jsjobs';

 $isodd = 0;
//$pane =& JPane::getInstance('tabs');
$big_field_width = 40;
$med_field_width = 25;
$sml_field_width = 15;

$section_personal = 1;
$section_basic = 1;
$section_addresses = 0;
$section_sub_address = 0;
$section_sub_address1 = 0;
$section_sub_address2 = 0;
$section_education = 0;
$section_sub_institute = 0;
$section_sub_institute1 = 0;
$section_sub_institute2 = 0;
$section_sub_institute3 = 0;
$section_employer = 0;
$section_sub_employer = 0;
$section_sub_employer1 = 0;
$section_sub_employer2 = 0;
$section_sub_employer3 = 0;
$section_skills = 0;
$section_resumeeditor = 0;
$section_references = 0;
$section_sub_reference = 0;
$section_sub_reference1 = 0;
$section_sub_reference2 = 0;
$section_sub_reference3 = 0;
$section_languages = 0;
$section_sub_language1 = 0;
$section_sub_language2=0;
$section_sub_language3=0;

foreach($this->fieldsordering as $field){ 
	switch ($field->field){
		case "section_addresses" :	$section_addresses = $field->published;	break;
		case "section_sub_address" :	$section_sub_address = $field->published;	break;
		case "section_sub_address1" :	$section_sub_address1 = $field->published;	break;
		case "section_sub_address2" :	$section_sub_address2 = $field->published;	break;
		case "section_education" :	$section_education = $field->published;	break;
		case "section_sub_institute" :	$section_sub_institute = $field->published;	break;
		case "section_sub_institute1" : $section_sub_institute1 = $field->published; break;
		case "section_sub_institute2" :	$section_sub_institute2 = $field->published; break;
		case "section_sub_institute3" :	$section_sub_institute3 = $field->published; break;
		case "section_employer" :	$section_employer = $field->published; break;
		case "section_sub_employer" :	$section_sub_employer = $field->published; break;
		case "section_sub_employer1" :	$section_sub_employer1 = $field->published;	break;
		case "section_sub_employer2" :	$section_sub_employer2 = $field->published;	break;
		case "section_sub_employer3" :	$section_sub_employer3 = $field->published; break;
		case "section_skills" :	$section_skills = $field->published; break;
		case "section_resumeeditor" :	$section_resumeeditor = $field->published; break;
		case "section_references" :	$section_references = $field->published; break;
		case "section_sub_reference" :	$section_sub_reference = $field->published; break;
		case "section_sub_reference1" :	$section_sub_reference1 = $field->published; break;
		case "section_sub_reference2" :	$section_sub_reference2 = $field->published; break;
		case "section_sub_reference3" :	$section_sub_reference3 = $field->published; break;

		case "section_userfields" :	$section_userfields = $field->published; break;
		case "section_languages" :	$section_languages = $field->published; break;
		case "section_sub_language" :	$section_sub_language = $field->published; break;
		case "section_sub_language1" :	$section_sub_language1 = $field->published; break;
		case "section_sub_language2" :	$section_sub_language2 = $field->published; break;
		case "section_sub_language3" :	$section_sub_language3 = $field->published; break;
		

	}
}
?>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script language="javascript">


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

// for joomla 1.5
function myValidate() {
<!--
        <?php
                $editor =& JFactory::getEditor();
                echo $editor->save( 'resume' );
        ?>
//-->
	f = document.adminForm;
	if (f.application_title.value == ""){
		alert('Please enter Personal > Application Title');
		f.application_title.focus();
		return false;
	}else if (f.first_name.value == ""){
		alert('Please enter Personal > First Name');
		f.first_name.focus();
		return false;
	}else if (f.last_name.value == ""){
		alert('Please enter Personal > Last Name');
		f.last_name.focus();
		return false;
	}else if (f.email_address.value == ""){
		alert('Please enter Personal > Email Address');
		f.email_address.focus();
		return false;
	}
	if (echeck(f.email_address.value)==false){
		f.email_address.value=""
		f.email_address.focus()
		return false
	}

        return true;
}

function echeck(str) {

		var at="@"
		var dot="."
		var lat=str.indexOf(at)
		var lstr=str.length
		var ldot=str.indexOf(dot)
		if (str.indexOf(at)==-1){
		   alert("Invalid E-mail ID")
		   return false
		}

		if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
		   alert("Invalid E-mail ID")
		   return false
		}

		if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
		    alert("Invalid E-mail ID")
		    return false
		}

		 if (str.indexOf(at,(lat+1))!=-1){
		    alert("Invalid E-mail ID")
		    return false
		 }

		 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
		    alert("Invalid E-mail ID")
		    return false
		 }

		 if (str.indexOf(dot,(lat+2))==-1){
		    alert("Invalid E-mail ID")
		    return false
		 }

		 if (str.indexOf(" ")!=-1){
		    alert("Invalid E-mail ID")
		    return false
		 }

 		 return true
}

</script>

<table width="100%" >
	<tr>
		<td align="left" width="125"  valign="top">
			<table width="100%" style="table-layout:fixed;"><tr><td style="vertical-align:top;">
			<?php
			include_once('components/com_jsjobs/views/menu.php');
			?>
			</td>
			</tr></table>
		</td>
		<td width="625" valign="top" align="left">


		<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" >
			<table cellpadding="5" cellspacing="0" border="0" width="100%" class="admintable" >
			<tr><td>
				<div id="rabs_wrapper">
					<div class="idTabs">
						
						<span><a class="selected" href="#personal_info_data"><?php echo JText::_('JS_PERSONAL');?></a></span> 
						<span><a href="#addresses_data"><?php echo JText::_('JS_ADDRESSES');?></a></span> 
						<span><a href="#education_data"><?php echo JText::_('JS_EDUCATIONS');?></a></span> 
						<span><a href="#employers_data"><?php echo JText::_('JS_EMPLOYERS');?></a></span> 
						<span><a href="#skills_data"><?php echo JText::_('JS_SKILLS');?></a></span> 
						<span><a href="#resume_editor_data"><?php echo JText::_('JS_RESUME_EDITOR');?></a></span> 
						<span><a href="#reference_data"><?php echo JText::_('JS_REFERENCES');?></a></span> 
						<span><a href="#language_data"><?php echo JText::_('JS_LANGUAGES');?></a></span> 
					</div>
				
			<?php
				$i = 0;
				foreach($this->fieldsordering as $field){ 
					//echo '<br> uf'.$field->field;
					switch ($field->field) {
						case "section_personal": ?>
								<div id="personal_info_data">
									<table cellpadding="5" cellspacing="0" border="0" width="100%" class="admintable" >
									<tr>
										<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
											<?php echo JText::_('JS_PERSONAL_INFORMATION'); ?>
										</td>
									</tr>
						
						<?php break;
						case "applicationtitle": ?>
							<tr>
								<td width="150" align="right" class="textfieldtitle">
									<label id="application_titlemsg" for="application_title"><?php echo JText::_('JS_APPLICATION_TITLE'); ?></label>&nbsp;<font color="red">*</font>:
								</td>
								<td>
									<input class="inputbox required" type="text" name="application_title" id="application_title" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->application_title;?>" />
								</td>
							</tr>
						<?php break;
						case "firstname": ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<label id="first_namemsg" for="first_name"><?php echo JText::_('JS_FIRST_NAME'); ?></label>&nbsp;<font color="red">*</font>:
								</td>
								<td>
									<input class="inputbox required" type="text" name="first_name" id="first_name" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->first_name;?>" />
								</td>
							</tr>
						<?php break;
						case "middlename": ?>
						<?php if ( $field->published == 1 ) { ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_MIDDLE_NAME'); ?>:
								</td>
								<td>
									<input class="inputbox" type="text"  name="middle_name" id="middle_name" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->middle_name;?>" />
								</td>
							</tr>
						<?php } ?>
						<?php break;
						case "lastname": ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<label id="last_namemsg" for="last_name"><?php echo JText::_('JS_LAST_NAME'); ?></label>&nbsp;<font color="red">*</font>:
								</td>
								<td>
									<input class="inputbox required" type="text" name="last_name" id="last_name" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->last_name;?>" />
								</td>
							</tr>
						<?php break;
						case "emailaddress": ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<label id="email_addressmsg" for="email_address"><?php echo JText::_('JS_EMAIL_ADDRESS'); ?></label>&nbsp;<font color="red">*</font>:
								</td>
								<td>
									<input class="inputbox required validate-email" type="text" name="email_address" id="email_address" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->email_address;?>" />
								</td>						
							</tr>
						<?php break;
						case "homephone": ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_HOME_PHONE'); ?>:
								</td>
								<td>
									<input class="inputbox" type="text" name="home_phone" id="home_phone" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->home_phone;?>" />
								</td>						
							</tr>
						<?php break;
						case "workphone": ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_WORK_PHONE'); ?>:
								</td>
								<td>
									<input class="inputbox" type="text" name="work_phone" id="work_phone" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->work_phone;?>" />
								</td>						
							</tr>
						<?php break;
						case "cell": ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CELL'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="cell" id="cell" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->cell;?>" />
								</td>						
							</tr>
						<?php break;
						case "gender": ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_GENDER');  ?>:
								</td>
								<td><?php echo $this->resumelists['gender'];	?>	</td>
							</tr>
						<?php break;
						case "Iamavailable": ?>
							<tr>
								<td valign="top" align="right"><?php echo JText::_('JS_I_AM_AVAILABLE'); ?></td>
								<td><input type='checkbox' name='iamavailable' value='1' <?php if(isset($this->resume)) echo ($this->resume->iamavailable == 1) ? "checked='checked'" : ""; ?> /></td>
							</tr>
						<?php break;
						case "searchable": ?>
							<tr>
								<td valign="top" align="right"><?php echo JText::_('JS_SEARCHABLE'); ?></td>
								<td><input type='checkbox' name='searchable' value='1' <?php if(isset($this->resume)) echo ($this->resume->searchable == 1) ? "checked='checked'" : ""; ?> /></td>
							</tr>
						<?php break;
						case "photo": ?>
							<tr>
								<?php if (isset($this->resume)) 
											if($this->resume->photo != '') {?>
												<tr><td></td><td style="max-width:150px;max-height:150px;overflow:hidden;text-overflow:ellipsis">
													<img src="../<?php echo $this->config['data_directory'];?>/data/jobseeker/resume_<?php echo $this->resume->id.'/photo/'.$this->resume->photo; ?>"  />
												</td></tr>
												<tr><td></td><td><input type='checkbox' name='deletephoto' value='1'><?php echo JText::_('JS_DELETE_PHOTO'); ?></td></tr>
								<?php } ?>				
								<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_PHOTO');  ?>:
								</td>
									<td>
										<input type="file" class="inputbox" name="photo" size="20" maxlenght='30'/><small><?php echo JText::_('JS_FILE_TYPE').' ('.JText::_('JS_GIF').' , '.JText::_('JS_JPG').' , '.JText::_('JS_JPEG').' , '.JText::_('JS_PNG').' )'; ?></small>
										
									</td>
							</tr>
						<?php break;
						case "nationality": ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_NATIONALITY_COUNTRY');  ?>:
								</td>
								<td><?php echo $this->resumelists['nationality']; ?></td>
							</tr>
						<?php break;
						case "section_basic": ?>
							<tr height="21"><td colspan="2"></td></tr>
							<tr>
								<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
									<?php echo JText::_('JS_BASIC_INFORMATION'); ?>
								</td>
							</tr>
						<?php break;
						case "category": ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_CATEGORY');  ?>:
								</td>
								<td>
									<?php
										echo $this->resumelists['job_category'];
									?>
								</td>
							</tr>
						<?php break;
						case "subcategory": ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_SUB_CATEGORY');  ?>:
								</td>
								<td id="fj_subcategory">
									<?php
										echo $this->resumelists['job_subcategory'];
									?>
								</td>
							</tr>
						<?php break;
						case "salary": ?>
							<tr>
								<td width="100"align="right" class="textfieldtitle">
									<?php echo JText::_('JS_DESIRED_SALARY'); ?>:
								</td>
								<td colspan="2" >
									<?php echo $this->resumelists['currencyid']; ?>
									<?php echo $this->resumelists['jobsalaryrange'] . JText::_('JS_PERMONTH'); ?>
								</td>
							</tr>
						<?php break;
						case "jobtype": ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_WORK_PREFERENCE'); ?>:	</td>
								<td colspan="2" valign="top" >
									<?php echo $this->resumelists['jobtype']; ?>
								</td>
							</tr>
						<?php break;
						case "heighesteducation": ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_HEIGHTESTFINISHEDEDUCATION'); ?>:</td>
								<td colspan="2" valign="top" >
									<?php
										//echo $this->resumelists['work_preferences'];
										echo $this->resumelists['heighestfinisheducation']; 
									?>
								</td>
							</tr>
						<?php break;
						case "totalexperience": ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_TOTAL_EXPERIENCE'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="total_experience" id="total_experience" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->total_experience;?>" />
								</td>						
							</tr>
						<?php break;
						case "startdate": 
								$startdatevalue = '';
								//if(isset($this->resume)) $startdatevalue = date($this->config['date_format'],strtotime($this->resume->date_start));
								?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_DATE_CAN_START'); ?>:</td>
								<td>
									<?php if($jversion == '1.5'){ ?><input class="inputbox " type="text" name="date_start" id="date_start" readonly size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo date($this->config['date_format'],strtotime($this->resume->date_start));?>" />
									<input type="reset" class="button" value="..." onclick="return showCalendar('date_start','<?php echo $js_dateformat; ?>');"  />

									<?php 
									}else{
										if($this->resume->date_start!='0000-00-00 00:00:00') echo JHTML::_('calendar', date($this->config['date_format'],  strtotime($this->resume->date_start)),'date_start', 'date_start',$js_dateformat,array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'19')); 										
										else echo JHTML::_('calendar', '','date_start', 'date_start',$js_dateformat,array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'19')); ?>										
									<?php } ?>
								</td>						
							</tr>

						<?php break;
						case "video": ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<label id="videomsg" for="video"><?php echo JText::_('JS_VIDEO'); ?></label>
								</td>
								<td><input class="inputbox" type="text" name="video" id="video" size="40" maxlength="255" value="<?php if(isset($this->resume)) echo $this->resume->video; ?>" />&nbsp;YouTube video id</td>
							</tr>
						<?php break;
						case "date_of_birth": 

								$startdatevalue = '';
								//if(isset($this->resume)) $startdatevalue = date($this->config['date_format'],strtotime($this->resume->date_start));
								?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_DATE_OF_BIRTH'); ?>:</td>
								<td>
									<?php if($jversion == '1.5'){ ?><input class="inputbox " type="text" name="date_of_birth" id="date_of_birth" readonly size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo date($this->config['date_format'],strtotime($this->resume->date_of_birth));?>" />
									<input type="reset" class="button" value="..." onclick="return showCalendar('date_of_birth','<?php echo $js_dateformat; ?>');"  />
									<?php 
									}elseif((isset($this->resume)) && ($this->resume->date_of_birth != "0000-00-00 00:00:00")) { echo JHTML::_('calendar', date($this->config['date_format'],  strtotime($this->resume->date_of_birth)),'date_of_birth', 'date_of_birth',$js_dateformat,array('class'=>'inputbox ', 'size'=>'10',  'maxlength'=>'19'));
									}else echo JHTML::_('calendar', '', 'date_of_birth','date_of_birth',$js_dateformat,array('class'=>'inputbox ', 'size'=>'10',  'maxlength'=>'19'));
									?> 

								</td>						
							</tr>

						<?php break;
						case "section_userfields": ?>
							<tr height="21"><td colspan="2"></td></tr>
							<tr>
								<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
									<?php echo JText::_($field->fieldtitle); ?>
								</td>
							</tr>
					<?php foreach($this->userfields as $ufield){ 
							foreach($this->fieldsordering as $field){
								if($field->published == 1) {
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
										//}
									}
								}
							}
						}	
						echo '<input type="hidden" id="userfields_total" name="userfields_total"  value="'.$i.'"  />';
						?> 
					<?php break;
							
						case "section_addresses": ?>
							</table>	
							</div>
								<div id="addresses_data">
									<table cellpadding="5" cellspacing="0" border="0" width="100%" class="admintable" >
										<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
											<tr>
												<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
													<?php echo JText::_('JS_ADDRESS'); ?>
												</td>
											</tr>
										<?php } ?>
					<?php break;
						case "address_city": ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_CITY'); ?>:
								</td>
								<td id="rddress_city">
									<input class="inputbox" type="text" name="address_city" id="address_city" size="40" maxlength="100" value="" />
									<input class="inputbox" type="hidden" name="addresscityforedit" id="addresscityforedit" size="40" maxlength="100" value="<?php if(isset($this->resume->address_city)) echo $this->resumelists['address_city']; ?>" />
									
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address_zipcode": ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_ZIPCODE'); ?>:
								</td>
								<td>
									<input class="inputbox" type="text" name="address_zipcode" id="address_zipcode" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->address_zipcode;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address_address": ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_ADDRESS'); ?>:
								</td>
								<td>
									<input class="inputbox" type="text" name="address" id="address" size="<?php echo $big_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->address;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address_location"://longitude and latitude ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_ADDRESS_LOCATION_LONGITUDE_AND_LATITUDE'); ?>:
								</td>
								<td>
									<div id="outermapdiv">
										<div id="map" style="width:<?php echo $this->config['mapwidth'];?>px; height:<?php echo $this->config['mapheight'];?>px">
											<div id="closetag"><a href="Javascript: hidediv();"><?php echo JText::_('X');?></a></div>
											<div id="map_container"></div>
										</div>
									</div>
									<input class="inputbox" type="text" id="longitude" name="longitude" size="25" />
									<input class="inputbox" type="text" id="latitude" name="latitude" size="25" />
									<span id="anchor"><a class="anchor" href="Javascript: showdiv();loadMap();"><?php echo JText::_('JS_MAP');?></a></span>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "section_sub_address1": ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address1 == 1)) { ?>
								<tr height="21"><td colspan="2"></td></tr>
								<tr>
									<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
										<?php echo JText::_('JS_ADDRESS1'); ?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "address1_city": ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_CITY'); ?>:
								</td>
								<td id="raddress1_city">
									<input class="inputbox" type="text" name="address1_city" id="address1_city" size="40" maxlength="100" value="" />
									<input class="inputbox" type="hidden" name="address1cityforedit" id="address1cityforedit" size="40" maxlength="100" value="<?php if(isset($this->resume->address1_city)) echo $this->resumelists['address1_city']; ?>" />
								<?php /*
									if((isset($this->resumelists['address1_city'])) && ($this->resumelists['address1_city']!='')){
										echo $this->resumelists['address1_city']; 
									} else{ ?>
									<input class="inputbox" type="text" name="address1_city" id="address1_city" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->address1_city;?>" />
								<?php }*/ ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address1_zipcode": ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_ZIPCODE'); ?>:
								</td>
								<td>
									<input class="inputbox" type="text" name="address1_zipcode" id="address1_zipcode" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->address1_zipcode;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address1_address": ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_ADDRESS'); ?>:
								</td>
								<td>
									<input class="inputbox" type="text" name="address1" id="address1" size="<?php echo $big_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->address1;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "section_sub_address2": ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address2 == 1)) { ?>
								<tr height="21"><td colspan="2"></td></tr>
								<tr>
									<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
										<?php echo JText::_('JS_ADDRESS2'); ?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "address2_city": ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_CITY'); ?>:
								</td>
								<td id="raddress2_city">
									<input class="inputbox" type="text" name="address2_city" id="address2_city" size="40" maxlength="100" value="" />
									<input class="inputbox" type="hidden" name="address2cityforedit" id="address2cityforedit" size="40" maxlength="100" value="<?php if(isset($this->resume->address2_city)) echo $this->resumelists['address2_city']; ?>" />
									
								<?php /*
									if((isset($this->resumelists['address2_city'])) && ($this->resumelists['address2_city']!='')){
										echo $this->resumelists['address2_city']; 
									} else{ ?>
									<input class="inputbox" type="text" name="address2_city" id="address2_city" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->address2_city;?>" />
								<?php }*/ ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address2_zipcode": ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_ZIPCODE'); ?>:
								</td>
								<td>
									<input class="inputbox" type="text" name="address2_zipcode" id="address2_zipcode" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->address2_zipcode;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address2_address": ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_ADDRESS'); ?>:
								</td>
								<td>
									<input class="inputbox" type="text" name="address2" id="address2" size="<?php echo $big_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->address2;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "section_education": ?>
							</table>	
							</div>							
								<div id="education_data">
									<table cellpadding="5" cellspacing="0" border="0" width="100%" class="admintable" >
										<?php  if (($section_education == 1) && ($section_sub_institute == 1)) { ?>
											<tr>
												<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
													<?php echo JText::_('JS_HIGH_SCHOOL'); ?>
												</td>
											</tr>
										<?php } ?>

										
					<?php break;
						case "institute_institute": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute == 1)) { ?>
								<tr>
									<td width="150" align="right" class="textfieldtitle"><?php echo JText::_('JS_SCH_COL_UNI'); ?>:	</td>
									<td>
										<input class="inputbox" type="text" name="institute" id="institute" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->institute;?>" />
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute_certificate": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute == 1)) { ?>
								<tr>
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CRT_DEG_OTH'); ?>:	</td>
									<td>
										<input class="inputbox" type="text" name="institute_certificate_name" id="institute_certificate_name" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->institute_certificate_name;?>" />
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute_study_area": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute == 1)) { ?>
								<tr>
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_AREA_OF_STUDY'); ?>:</td>
									<td>
										<input class="inputbox" type="text" name="institute_study_area" id="institute_study_area" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->institute_study_area;?>" />
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute_city": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute == 1)) { ?>
								<tr>
										<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
										<td id="rinstitute_city">
											<input class="inputbox" type="text" name="institute_city" id="institute_city" size="40" maxlength="100" value="" />
											<input class="inputbox" type="hidden" name="institutecityforedit" id="institutecityforedit" size="40" maxlength="100" value="<?php if(isset($this->resume->institute_city)) echo $this->resumelists['institute_city']; ?>" />
										</td>
								</tr>
							<?php } ?>

					<?php break;
						case "section_sub_institute1": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
								<tr height="21"><td colspan="2"></td></tr>
								<tr>
									<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
										<?php echo JText::_('JS_UNIVERSITY'); ?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute1_institute": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
								<tr>
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_SCH_COL_UNI'); ?>:	</td>
									<td>
										<input class="inputbox" type="text" name="institute1" id="institute1" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->institute1;?>" />
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute1_certificate": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
								<tr>
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CRT_DEG_OTH'); ?>:	</td>
									<td>
										<input class="inputbox" type="text" name="institute1_certificate_name" id="institute1_certificate_name" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->institute1_certificate_name;?>" />
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute1_study_area": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
								<tr>
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_AREA_OF_STUDY'); ?>:</td>
									<td>
										<input class="inputbox" type="text" name="institute1_study_area" id="institute1_study_area" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->institute1_study_area;?>" />
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute1_city": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
								<tr>
										<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
										<td id="rinstitute1_city">
											<input class="inputbox" type="text" name="institute1_city" id="institute1_city" size="40" maxlength="100" value="" />
											<input class="inputbox" type="hidden" name="institute1cityforedit" id="institute1cityforedit" size="40" maxlength="100" value="<?php if(isset($this->resume->institute1_city)) echo $this->resumelists['institute1_city']; ?>" />
										</td>
								</tr>
							<?php } ?>
					<?php break;
						case "section_sub_institute2": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute2 == 1)) { ?>
								<tr>
									<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
										<?php echo JText::_('JS_GRADE_SCHOOL'); ?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute2_institute": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute2 == 1)) { ?>
								<tr>
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_SCH_COL_UNI'); ?>:	</td>
									<td>
										<input class="inputbox" type="text" name="institute2" id="institute2" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->institute2;?>" />
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute2_certificate": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute2 == 1)) { ?>
								<tr>
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CRT_DEG_OTH'); ?>:	</td>
									<td>
										<input class="inputbox" type="text" name="institute2_certificate_name" id="institute2_certificate_name" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->institute2_certificate_name;?>" />
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute2_study_area": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute2 == 1)) { ?>
								<tr>
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_AREA_OF_STUDY'); ?>:</td>
									<td>
										<input class="inputbox" type="text" name="institute2_study_area" id="institute2_study_area" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->institute2_study_area;?>" />
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute2_city": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute2 == 1)) { ?>
								<tr>
										<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
										<td id="rinstitute2_city">
											<input class="inputbox" type="text" name="institute2_city" id="institute2_city" size="40" maxlength="100" value="" />
											<input class="inputbox" type="hidden" name="institute2cityforedit" id="institute2cityforedit" size="40" maxlength="100" value="<?php if(isset($this->resume->institute2_city)) echo $this->resumelists['institute2_city']; ?>" />
										<?php /*
											if((isset($this->resumelists['institute2_city'])) && ($this->resumelists['institute2_city']!='')){
												echo $this->resumelists['institute2_city']; 
											} else{ ?>
												<input class="inputbox" type="text" name="institute2_city" id="institute2_city" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->institute2_city;?>" />
										<?php } */ ?>
										</td>
								</tr>
							<?php } ?>
					<?php break;
						case "section_sub_institute3": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute3 == 1)) { ?>
								<tr>
									<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
										<?php echo JText::_('JS_OTHER_SCHOOL'); ?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute3_institute": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute3 == 1)) { ?>
								<tr>
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_SCH_COL_UNI'); ?>:	</td>
									<td>
										<input class="inputbox" type="text" name="institute3" id="institute3" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->institute3;?>" />
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute3_certificate": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute3 == 1)) { ?>
								<tr>
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CRT_DEG_OTH'); ?>:	</td>
									<td>
										<input class="inputbox" type="text" name="institute3_certificate_name" id="institute3_certificate_name" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->institute3_certificate_name;?>" />
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute3_study_area": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute3 == 1)) { ?>
								<tr>
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_AREA_OF_STUDY'); ?>:</td>
									<td>
										<input class="inputbox" type="text" name="institute3_study_area" id="institute3_study_area" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->institute3_study_area;?>" />
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute3_city": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute3 == 1)) { ?>
								<tr>
										<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
										<td id="rinstitute3_city">
											<input class="inputbox" type="text" name="institute3_city" id="institute3_city" size="40" maxlength="100" value="" />
											<input class="inputbox" type="hidden" name="institute3cityforedit" id="institute3cityforedit" size="40" maxlength="100" value="<?php if(isset($this->resume->institute3_city)) echo $this->resumelists['institute3_city']; ?>" />
										<?php /*
											if((isset($this->resumelists['institute3_city'])) && ($this->resumelists['institute3_city']!='')){
												echo $this->resumelists['institute3_city']; 
											} else{ ?>
												<input class="inputbox" type="text" name="institute3_city" id="institute3_city" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->institute3_city;?>" />
										<?php } */ ?>
										</td>
								</tr>
							<?php } ?>

							

					<?php break;
						case "section_employer": ?>
							</table>	
							</div>
								<div id="employers_data">
									<table cellpadding="5" cellspacing="0" border="0" width="100%" class="admintable" >
										<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
											<tr>
												<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
													<?php echo JText::_('JS_RECENT_EMPLOYER'); ?>
												</td>
											</tr>
										<?php } ?>
					<?php break;
						case "employer_employer": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr>
								<td width="150" align="right" class="textfieldtitle"><?php echo JText::_('JS_EMPLOYER'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer" id="employer" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_position": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_POSITION'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer_position" id="employer_position" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer_position;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_resp": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_RESPONSIBILITIES'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer_resp" id="employer_resp" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer_resp;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_pay_upon_leaving": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_PAY_UPON_LEAVING'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer_pay_upon_leaving" id="employer_pay_upon_leaving" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer_pay_upon_leaving;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_supervisor": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_SUPERVISOR'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer_supervisor" id="employer_supervisor" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer_supervisor;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_from_date": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_FROM_DATE'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer_from_date" id="employer_from_date" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->employer_from_date;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_to_date": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_TO_DATE'); ?>:	</td>
								<td>
									<input class="inputbox" type="text" name="employer_to_date" id="employer_to_date" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->employer_to_date;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_leave_reason": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_LEAVING_REASON'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer_leave_reason" id="employer_leave_reason" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer_leave_reason;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_city": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
								<td id="remployer_city">
											<input class="inputbox" type="text" name="employer_city" id="employer_city" size="40" maxlength="100" value="" />
											<input class="inputbox" type="hidden" name="employercityforedit" id="employercityforedit" size="40" maxlength="100" value="<?php if(isset($this->resume->employer_city)) echo $this->resumelists['employer_city']; ?>" />
									<?php /*
										if((isset($this->resumelists['employer_city'])) && ($this->resumelists['employer_city']!='')){
											echo $this->resumelists['employer_city']; 
										} else{ ?>
											<input class="inputbox" type="text" name="employer_city" id="employer_city" size="<?php echo $med_field_width; ?>" maxlength="100" value = "<?php if (isset($this->resume)) echo $this->resume->employer_city;?>" />
									<?php }*/ ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_zip": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ZIPCODE'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer_zip" id="employer_zip" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->employer_zip;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_address": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ADDRESS'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer_address" id="employer_address" size="<?php echo $big_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer_address;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "employer_phone": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_PHONE'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer_phone" id="employer_phone" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->employer_phone;?>" />
								</td>
							</tr>
							<?php } ?>


					<?php break;
						case "section_sub_employer1": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr height="21"><td colspan="2"></td></tr>
							<tr>
								<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
									<?php echo JText::_('JS_PRIOR_EMPLOYER_1'); ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_employer": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr>
								<td width="150" align="right" class="textfieldtitle"><?php echo JText::_('JS_EMPLOYER'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer1" id="employer1" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer1;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_position": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_POSITION'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer1_position" id="employer1_position" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer1_position;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_resp": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_RESPONSIBILITIES'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer1_resp" id="employer1_resp" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer1_resp;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_pay_upon_leaving": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_PAY_UPON_LEAVING'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer1_pay_upon_leaving" id="employer1_pay_upon_leaving" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer1_pay_upon_leaving;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_supervisor": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_SUPERVISOR'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer1_supervisor" id="employer1_supervisor" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer1_supervisor;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_from_date": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_FROM_DATE'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer1_from_date" id="employer1_from_date" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->employer1_from_date;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_to_date": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_TO_DATE'); ?>:	</td>
								<td>
									<input class="inputbox" type="text" name="employer1_to_date" id="employer1_to_date" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->employer1_to_date;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_leave_reason": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_LEAVING_REASON'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer1_leave_reason" id="employer1_leave_reason" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer1_leave_reason;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_city": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
								<td id="remployer1_city">
									<input class="inputbox" type="text" name="employer1_city" id="employer1_city" size="40" maxlength="100" value="" />
									<input class="inputbox" type="hidden" name="employer1cityforedit" id="employer1cityforedit" size="40" maxlength="100" value="<?php if(isset($this->resume->employer1_city)) echo $this->resumelists['employer1_city']; ?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_zip": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ZIPCODE'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer1_zip" id="employer1_zip" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->employer1_zip;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_address": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ADDRESS'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer1_address" id="employer1_address" size="<?php echo $big_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer1_address;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "employer1_phone": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_PHONE'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer1_phone" id="employer1_phone" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->employer1_phone;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "section_sub_employer2": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr height="21"><td colspan="2"></td></tr>
							<tr>
								<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
									<?php echo JText::_('JS_PRIOR_EMPLOYER_2'); ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_employer": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr>
								<td width="150" align="right" class="textfieldtitle"><?php echo JText::_('JS_EMPLOYER'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer2" id="employer2" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer2;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_position": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_POSITION'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer2_position" id="employer2_position" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer2_position;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_resp": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_RESPONSIBILITIES'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer2_resp" id="employer2_resp" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer2_resp;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_pay_upon_leaving": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_PAY_UPON_LEAVING'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer2_pay_upon_leaving" id="employer2_pay_upon_leaving" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer2_pay_upon_leaving;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_supervisor": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_SUPERVISOR'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer2_supervisor" id="employer2_supervisor" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer2_supervisor;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_from_date": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_FROM_DATE'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer2_from_date" id="employer2_from_date" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->employer2_from_date;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_to_date": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_TO_DATE'); ?>:	</td>
								<td>
									<input class="inputbox" type="text" name="employer2_to_date" id="employer2_to_date" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->employer2_to_date;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_leave_reason": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_LEAVING_REASON'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer2_leave_reason" id="employer2_leave_reason" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer2_leave_reason;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_city": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
								<td id="remployer2_city">
									<input class="inputbox" type="text" name="employer2_city" id="employer2_city" size="40" maxlength="100" value="" />
									<input class="inputbox" type="hidden" name="employer2cityforedit" id="employer2cityforedit" size="40" maxlength="100" value="<?php if(isset($this->resume->employer2_city)) echo $this->resumelists['employer2_city']; ?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_zip": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ZIPCODE'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer2_zip" id="employer2_zip" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->employer2_zip;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_address": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ADDRESS'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer2_address" id="employer2_address" size="<?php echo $big_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer2_address;?>" />
								</td>
							</tr>
						<?php } ?>	
				<?php break;
						case "employer2_phone": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_PHONE'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer2_phone" id="employer2_phone" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->employer2_phone;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "section_sub_employer3": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr height="21"><td colspan="2"></td></tr>
							<tr>
								<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
									<?php echo JText::_('JS_PRIOR_EMPLOYER_3'); ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_employer": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr>
								<td width="150" align="right" class="textfieldtitle"><?php echo JText::_('JS_EMPLOYER'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer3" id="employer3" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer3;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_position": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_POSITION'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer3_position" id="employer3_position" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer3_position;?>" />
								</td>

							</tr>
							<?php } ?>
					<?php break;
						case "employer3_resp": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_RESPONSIBILITIES'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer3_resp" id="employer3_resp" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer3_resp;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_pay_upon_leaving": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_PAY_UPON_LEAVING'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer3_pay_upon_leaving" id="employer3_pay_upon_leaving" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer3_pay_upon_leaving;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_supervisor": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_SUPERVISOR'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer3_supervisor" id="employer3_supervisor" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer3_supervisor;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_from_date": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_FROM_DATE'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer3_from_date" id="employer3_from_date" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->employer3_from_date;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_to_date": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_TO_DATE'); ?>:	</td>
								<td>
									<input class="inputbox" type="text" name="employer3_to_date" id="employer3_to_date" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->employer3_to_date;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_leave_reason": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_LEAVING_REASON'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer3_leave_reason" id="employer3_leave_reason" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer3_leave_reason;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_city": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
								<td id="remployer3_city">
									<input class="inputbox" type="text" name="employer3_city" id="employer3_city" size="40" maxlength="100" value="" />
									<input class="inputbox" type="hidden" name="employer3cityforedit" id="employer3cityforedit" size="40" maxlength="100" value="<?php if(isset($this->resume->employer3_city)) echo $this->resumelists['employer3_city']; ?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_zip": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ZIPCODE'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer3_zip" id="employer3_zip" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->employer3_zip;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_address": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ADDRESS'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer3_address" id="employer3_address" size="<?php echo $big_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->employer3_address;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "employer3_phone": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_PHONE'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="employer3_phone" id="employer3_phone" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->employer3_phone;?>" />
								</td>
							</tr>
							<?php } ?>
							

					<?php break;
						case "section_skills": ?>
							</table>	
							</div>
								<div id="skills_data">
								<table cellpadding="5" cellspacing="0" border="0" width="100%" class="admintable" >
									<tr>
										<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
											<?php echo JText::_('JS_SKILLS'); ?>
										</td>
									</tr>
					<?php break;
						case "driving_license": ?>
							<?php  if ($section_skills == 1) { ?>
							<tr>
								<td width="150" align="right" class="textfieldtitle"><?php echo JText::_('JS_HAVE_DRIVING_LICENSE'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="driving_license" id="driving_license" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->driving_license;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "license_no": ?>
							<?php  if ($section_skills == 1) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_YSE_LICENSE_NO'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="license_no" id="license_no" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->license_no;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "license_country": ?>
							<?php  if ($section_skills == 1) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_YSE_LICENSE_COUNTRY'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="license_country" id="license_country" size="<?php echo $med_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->license_country;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "skills": ?>
							<?php  if ($section_skills == 1) { ?>
							<tr>
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_SKILLS'); ?>:</td>
								<td>
									<textarea class="inputbox" name="skills" id="skills" cols="60" rows="9"><?php if(isset($this->resume)) echo $this->resume->skills; ?></textarea>
								</td>
							</tr>
							<?php } ?>
										
										
					<?php break;
						case "section_resumeeditor": ?>
							</table>
							</div>
								<div id="resume_editor_data">
								<table cellpadding="5" cellspacing="0" border="0" width="100%" class="admintable" >
									<tr>
										<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
											<?php echo JText::_('JS_RESUME'); ?>
										</td>
									</tr>

							
					<?php break;
						case "editor": ?>
							<?php  if ($section_resumeeditor == 1) { ?>
								<tr>
									<td colspan="2">
									    <?php
									               $editor =& JFactory::getEditor();
			                                        if(isset($this->resume))
														echo $editor->display('resume', $this->resume->resume, '550', '400', '60', '20', false);
													else
														echo $editor->display('resume', '', '550', '400', '60', '20', false);
			                                ?>

									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "fileupload": ?>
							<?php  if ($section_resumeeditor == 1) { ?>
								<tr height="21"><td colspan="2"></td></tr>
								<tr><td></td><td><strong><?php echo JText::_('JS_ALSO_RESUME_FILE'); ?></strong></td></tr>
								<?php if (isset($this->resume)) 
											if($this->resume->filename != '') {?>
												<?php $link = '../'.$this->config['data_directory'].'/data/jobseeker/resume_'.$this->resume->id.'/resume/'.$this->resume->filename; ?>
												<tr><td></td><td><a href="<?php echo $link;?>"><?php echo JText::_('JS_DOWNLOAD_RESUME_FILE') .'['.$this->resume->filename.']'; ?></a></td></tr>
												<br>
												<tr><td></td><td><input type='checkbox' name='deleteresumefile' value='1'><?php echo JText::_('JS_DELETE_RESUME_FILE') .'['.$this->resume->filename.']'; ?></td></tr>
												
								<?php } ?>				
								<tr>
									<td width="150" align="right" class="textfieldtitle">
										<?php echo JText::_('JS_RESUME_FILE'); ?>:
									</td>
									<td>
										<input type="file" class="inputbox" name="resumefile" size="20" maxlenght='30'/><small><?php echo JText::_('JS_FILE_TYPE').' ('.JText::_('JS_TXT').' , '.JText::_('JS_DOC').' , '.JText::_('JS_DOCX').' , '.JText::_('JS_PDF').' , '.JText::_('JS_OPT').' , '.JText::_('JS_RTF').' )'; ?></small>
										<input type='hidden' maxlenght=''/>
									</td>
								</tr>
							<?php } ?>
						
						
					<?php break;
						case "section_references": ?>
							</table>	
							</div>
								<div id="reference_data">
									<table cellpadding="5" cellspacing="0" border="0" width="100%" class="admintable" >
										<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
										<tr>
											<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
												<?php echo JText::_('JS_REFERENCE1'); ?>
											</td>
										</tr>
										<?php } ?>
							
					<?php break;
						case "reference_name": ?>
							<?php  if ($section_resumeeditor == 1) { ?>
							<tr>
								<td width="150" align="right" class="textfieldtitle"><?php echo JText::_('JS_NAME'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="reference_name" id="reference_name" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->reference_name;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "reference_city": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
						        <td id="rreference_city">
									<input class="inputbox" type="text" name="reference_city" id="reference_city" size="40" maxlength="100" value="" />
									<input class="inputbox" type="hidden" name="referencecityforedit" id="referencecityforedit" size="40" maxlength="100" value="<?php if(isset($this->resume->reference_city)) echo $this->resumelists['reference_city']; ?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "reference_zipcode": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ZIPCODE'); ?>:</td>
						        <td >
									<input class="inputbox" type="text" name="reference_zipcode" id="reference_zipcode" size="<?php echo $sml_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->reference_zipcode;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference_address": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ADDRESS'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="reference_address" id="reference_address" size="<?php echo $big_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->reference_address;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "reference_phone": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_PHONE'); ?>:	</td>
								<td>
									<input class="inputbox" type="text" name="reference_phone" id="reference_phone" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->reference_phone;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "reference_relation": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_RELATION'); ?>:	</td>
								<td>
									<input class="inputbox" type="text" name="reference_relation" id="reference_relation" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->reference_relation;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "reference_years": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_YEARS'); ?>:	</td>
								<td>
									<input class="inputbox" type="text" name="reference_years" id="reference_years" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->reference_years;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "section_sub_reference1": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr>
								<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
									<?php echo JText::_('JS_REFERENCE2'); ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "reference1_name": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_NAME'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="reference1_name" id="reference1_name" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->reference1_name;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference1_city": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
						        <td id="rreference1_city">
									<input class="inputbox" type="text" name="reference1_city" id="reference1_city" size="40" maxlength="100" value="" />
									<input class="inputbox" type="hidden" name="reference1cityforedit" id="reference1cityforedit" size="40" maxlength="100" value="<?php if(isset($this->resume->reference1_city)) echo $this->resumelists['reference1_city']; ?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference1_zipcode": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ZIPCODE'); ?>:</td>
						        <td >
									<input class="inputbox" type="text" name="reference1_zipcode" id="reference1_zipcode" size="<?php echo $sml_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->reference1_zipcode;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference1_address": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ADDRESS'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="reference1_address" id="reference1_address" size="<?php echo $big_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->reference1_address;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference1_phone": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_PHONE'); ?>:	</td>
								<td>
									<input class="inputbox" type="text" name="reference1_phone" id="reference1_phone" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->reference1_phone;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference1_relation": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_RELATION'); ?>:	</td>
								<td>
									<input class="inputbox" type="text" name="reference1_relation" id="reference1_relation" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->reference1_relation;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference1_years": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_YEARS'); ?>:	</td>
								<td>
									<input class="inputbox" type="text" name="reference1_years" id="reference1_years" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->reference1_years;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "section_sub_reference2": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr>
								<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
									<?php echo JText::_('JS_REFERENCE3'); ?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_name": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_NAME'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="reference2_name" id="reference2_name" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->reference2_name;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_city": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
						        <td id="rreference2_city">
									<input class="inputbox" type="text" name="reference2_city" id="reference2_city" size="40" maxlength="100" value="" />
									<input class="inputbox" type="hidden" name="reference2cityforedit" id="reference2cityforedit" size="40" maxlength="100" value="<?php if(isset($this->resume->reference2_city)) echo $this->resumelists['reference2_city']; ?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_zipcode": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ZIPCODE'); ?>:</td>
						        <td >
									<input class="inputbox" type="text" name="reference2_zipcode" id="reference2_zipcode" size="<?php echo $sml_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->reference2_zipcode;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_address": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ADDRESS'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="reference2_address" id="reference2_address" size="<?php echo $big_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->reference2_address;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_phone": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_PHONE'); ?>:	</td>
								<td>
									<input class="inputbox" type="text" name="reference2_phone" id="reference2_phone" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->reference2_phone;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_relation": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_RELATION'); ?>:	</td>
								<td>
									<input class="inputbox" type="text" name="reference2_relation" id="reference2_relation" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->reference2_relation;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_years": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_YEARS'); ?>:	</td>
								<td>
									<input class="inputbox" type="text" name="reference2_years" id="reference2_years" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->reference2_years;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "section_sub_reference3": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr>
								<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
									<?php echo JText::_('JS_REFERENCE4'); ?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_name": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_NAME'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="reference3_name" id="reference3_name" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->reference3_name;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_city": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
						        <td id="rreference3_city">
									<input class="inputbox" type="text" name="reference3_city" id="reference3_city" size="40" maxlength="100" value="" />
									<input class="inputbox" type="hidden" name="reference3cityforedit" id="reference3cityforedit" size="40" maxlength="100" value="<?php if(isset($this->resume->reference3_city)) echo $this->resumelists['reference3_city']; ?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_zipcode": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ZIPCODE'); ?>:</td>
						        <td >
									<input class="inputbox" type="text" name="reference3_zipcode" id="reference3_zipcode" size="<?php echo $sml_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->reference3_zipcode;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_address": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ADDRESS'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="reference3_address" id="reference3_address" size="<?php echo $big_field_width; ?>" maxlength="250" value = "<?php if (isset($this->resume)) echo $this->resume->reference3_address;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_phone": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_PHONE'); ?>:	</td>
								<td>
									<input class="inputbox" type="text" name="reference3_phone" id="reference3_phone" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->reference3_phone;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_relation": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_RELATION'); ?>:	</td>
								<td>
									<input class="inputbox" type="text" name="reference3_relation" id="reference3_relation" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->reference3_relation;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_years": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_YEARS'); ?>:	</td>
								<td>
									<input class="inputbox" type="text" name="reference3_years" id="reference3_years" size="<?php echo $sml_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->reference3_years;?>" />
								</td>
							</tr>
						<?php } ?>	
					<?php		
						break;	
						case "section_languages": ?>
							</table>	
							</div>
								<div id="language_data">
									<table cellpadding="5" cellspacing="0" border="0" width="100%" >
										<?php  if (($section_languages == 1) && ($section_sub_language == 1)) { ?>
							<tr>
								<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
												<?php echo JText::_('JS_LANGUAGE1'); ?>
											</td>
										</tr>
										<?php } ?>
							
					<?php break;
						case "language_name": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language == 1)) { ?>
							<tr>
								<td width="150" align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_NAME'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="language" id="language" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->language;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "language_reading": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_READ'); ?></td>
								<td>
									<input class="inputbox" type="text" name="language_reading" id="language_reading" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->language_reading;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "language_writing": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_WRITE'); ?></td>
								<td>
									<input class="inputbox" type="text" name="language_writing" id="language_writing" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->language_writing;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "language_understading": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_UNDERSTAND'); ?></td>
								<td>
									<input class="inputbox" type="text" name="language_understanding" id="language_understanding" size="<?php echo $med_field_width; ?>" maxlength="100" value = "<?php if (isset($this->resume)) echo $this->resume->language_understanding;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "language_where_learned": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_LEARN_INSTITUTE'); ?></td>
								<td>
									<input class="inputbox" type="text" name="language_where_learned" id="language_where_learned" size="<?php echo $med_field_width; ?>" maxlength="100" value = "<?php if (isset($this->resume)) echo $this->resume->language_where_learned;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
					case "section_sub_language1": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language1 == 1)) { ?>
							<tr>
								<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
									<?php echo JText::_('JS_LANGUAGE2'); ?>
								</td>
							</tr>
							<?php } ?>

					<?php break;
						case "language1_name": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language1 == 1)) { ?>
							<tr>
								<td width="150" align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_NAME'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="language1" id="language1" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->language1;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "language1_reading": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_READ'); ?></td>
								<td>
									<input class="inputbox" type="text" name="language1_reading" id="language1_reading" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->language1_reading;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "language1_writing": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_WRITE'); ?></td>
								<td>
									<input class="inputbox" type="text" name="language1_writing" id="language1_writing" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->language1_writing;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "language1_understading": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_UNDERSTAND'); ?></td>
								<td>
									<input class="inputbox" type="text" name="language1_understanding" id="language1_understanding" size="<?php echo $med_field_width; ?>" maxlength="100" value = "<?php if (isset($this->resume)) echo $this->resume->language1_understanding;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "language1_where_learned": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language1 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_LEARN_INSTITUTE'); ?></td>
								<td>
									<input class="inputbox" type="text" name="language1_where_learned" id="language1_where_learned" size="<?php echo $med_field_width; ?>" maxlength="100" value = "<?php if (isset($this->resume)) echo $this->resume->language1_where_learned;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
					case "section_sub_language2": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language2 == 1)) { ?>
							<tr>
								<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
									<?php echo JText::_('JS_LANGUAGE3'); ?>
								</td>
							</tr>
							<?php } ?>

					<?php break;
						case "language2_name": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language2 == 1)) { ?>
							<tr>
								<td width="150" align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_NAME'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="language2" id="language2" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->language2;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "language2_reading": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_READ'); ?></td>
								<td>
									<input class="inputbox" type="text" name="language2_reading" id="language2_reading" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->language2_reading;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "language2_writing": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_WRITE'); ?></td>
								<td>
									<input class="inputbox" type="text" name="language2_writing" id="language2_writing" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->language2_writing;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "language2_understading": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_UNDERSTAND'); ?></td>
								<td>
									<input class="inputbox" type="text" name="language2_understanding" id="language2_understanding" size="<?php echo $med_field_width; ?>" maxlength="100" value = "<?php if (isset($this->resume)) echo $this->resume->language2_understanding;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "language2_where_learned": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language2 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_LEARN_INSTITUTE'); ?></td>
								<td>
									<input class="inputbox" type="text" name="language2_where_learned" id="language2_where_learned" size="<?php echo $med_field_width; ?>" maxlength="100" value = "<?php if (isset($this->resume)) echo $this->resume->language2_where_learned;?>" />
								</td>
							</tr>
							<?php } 
					break;		
					case "section_sub_language3": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language3 == 1)) { ?>
							<tr>
								<td width="100" colspan="2" align="center" class="<?php echo $this->theme['sectionheading']; ?>">
									<?php echo JText::_('JS_LANGUAGE4'); ?>
								</td>
							</tr>
							<?php } ?>

					<?php break;
						case "language3_name": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language3 == 1)) { ?>
							<tr>
								<td width="150" align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_NAME'); ?>:</td>
								<td>
									<input class="inputbox" type="text" name="language3" id="language3" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->language3;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "language3_reading": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language3 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_READ'); ?></td>
								<td>
									<input class="inputbox" type="text" name="language3_reading" id="language3_reading" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->language3_reading;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "language3_writing": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language3 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_WRITE'); ?></td>
								<td>
									<input class="inputbox" type="text" name="language3_writing" id="language3_writing" size="<?php echo $med_field_width; ?>" maxlength="20" value = "<?php if (isset($this->resume)) echo $this->resume->language3_writing;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "language3_understading": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language3 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_UNDERSTAND'); ?></td>
								<td>
									<input class="inputbox" type="text" name="language3_understanding" id="language3_understanding" size="<?php echo $med_field_width; ?>" maxlength="100" value = "<?php if (isset($this->resume)) echo $this->resume->language3_understanding;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "language3_where_learned": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language3 == 1)) { ?>
							<tr>
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_LEARN_INSTITUTE'); ?></td>
								<td>
									<input class="inputbox" type="text" name="language3_where_learned" id="language3_where_learned" size="<?php echo $med_field_width; ?>" maxlength="100" value = "<?php if (isset($this->resume)) echo $this->resume->language3_where_learned;?>" />
								</td>
							</tr>
							<?php } ?>
					<?php break; ?>
										
					<?php } ?>	
				<?php } ?>	
							
						</table>	
						</div>
					</div>

			</td></tr>
			<tr><td>
				<table width="100%" >
					<?php if(isset($this->resume)) {  $isodd = 1 - $isodd; ?>
						<tr><td colspan="2" height="10"></td></tr>
					  <tr >
						<td width="50%" align="right"><label id="statusmsg" for="status"><?php echo JText::_('JS_STATUS'); ?></label>:</td>
						<td><?php  echo $this->resumelists['status']; ?>
						</td>
					  </tr>
					<?php }else { ?>
						<input type="hidden" name="status" value="1" />
					<?php } ?>	
					<tr><td colspan="2" height="10"></td></tr>
					<tr>
						<td colspan="2" align="center">
							<input type="submit" class="button validate"  name="save_app" onClick="return myValidate();"  value="<?php echo JText::_('JS_SAVE_RESUME'); ?>" />

				</td>
					</tr>
				</table>
			</td></tr>
			</table>
			<?php 
				if(isset($this->resume)) {
					$uid = $this->resume->uid;
					if (($this->resume->create_date=='0000-00-00 00:00:00') || ($this->resume->create_date==''))
						$curdate = date('Y-m-d H:i:s');
					else  
						$curdate = $this->resume->create_date;
				}else{
					$uid = $this->uid;
					$curdate = date('Y-m-d H:i:s');
				}	
				
			?>
			<input type="hidden" name="create_date" value="<?php echo $curdate; ?>" />
			<input type="hidden" name="id" value="<?php if (isset($this->resume)) echo $this->resume->id; ?>" />
			<input type="hidden" name="layout" value="empview" />
			<input type="hidden" name="uid" value="<?php echo $uid; ?>" />
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			<input type="hidden" name="task" value="saveresume" />
			<input type="hidden" name="check" value="" />
			<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
			<input type="hidden" id="default_longitude" name="default_longitude" value="<?php echo $this->config['default_longitude'];?>"/>
			<input type="hidden" id="default_latitude" name="default_latitude" value="<?php echo $this->config['default_latitude'];?>"/>

<script language=Javascript>
function dochange(curscr, myname, nextname, src, val){
	document.getElementById(curscr).innerHTML="Loading ...";
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
        	document.getElementById(curscr).innerHTML=xhr.responseText; //retuen value
			cleanFields(curscr);
      }
    }

	xhr.open("GET","index.php?option=com_jsjobs&task=listempaddressdata&name="+curscr+"&myname="+myname+"&nextname="+nextname+"&data="+src+"&val="+val,true);
	xhr.send(null);
}

function cleanFields(curscr) {
	
	switch(curscr){
		case "address_state":
			countyhtml = "<input class='inputbox' type='text' name='address_county' size='40' maxlength='100'  />";
			cityhtml = "<input class='inputbox' type='text' name='address_city' size='40' maxlength='100'  />";
			document.getElementById('address_county').innerHTML=countyhtml; //retuen value
			document.getElementById('address_city').innerHTML=cityhtml; //retuen value
			break;
		case "address_county":
			cityhtml = "<input class='inputbox' type='text' name='address_city' size='40' maxlength='100'  />";
			document.getElementById('address_city').innerHTML=cityhtml; //retuen value
			break;
		case "address1_state":
			document.getElementById('address1_county').innerHTML = "<input class='inputbox' type='text' name='address1_county' size='40' maxlength='100'  />";
			document.getElementById('address1_city').innerHTML = "<input class='inputbox' type='text' name='address1_city' size='40' maxlength='100'  />";
			break;
		case "address1_county":
			document.getElementById('address1_city').innerHTML = "<input class='inputbox' type='text' name='address1_city' size='40' maxlength='100'  />";
			break;
		case "address2_state":
			document.getElementById('address2_county').innerHTML = "<input class='inputbox' type='text' name='address2_county' size='40' maxlength='100'  />";
			document.getElementById('address2_city').innerHTML = "<input class='inputbox' type='text' name='address2_city' size='40' maxlength='100'  />";
			break;
		case "address2_county":
			document.getElementById('address2_city').innerHTML = "<input class='inputbox' type='text' name='address2_city' size='40' maxlength='100'  />";
			break;
		case "institute_state":
			countyhtml = "<input class='inputbox' type='text' name='institute_county' size='40' maxlength='100'  />";
			cityhtml = "<input class='inputbox' type='text' name='institute_city' size='40' maxlength='100'  />";
			document.getElementById('institute_county').innerHTML=countyhtml; //retuen value
			document.getElementById('institute_city').innerHTML=cityhtml; //retuen value
			break;
		case "institute_county":
			cityhtml = "<input class='inputbox' type='text' name='institute_city' size='40' maxlength='100'  />";
			document.getElementById('institute_city').innerHTML=cityhtml; //retuen value
			break;
		case "institute1_state":
			countyhtml = "<input class='inputbox' type='text' name='institute1_county' size='40' maxlength='100'  />";
			cityhtml = "<input class='inputbox' type='text' name='institute1_city' size='40' maxlength='100'  />";
			document.getElementById('institute1_county').innerHTML=countyhtml; //retuen value
			document.getElementById('institute1_city').innerHTML=cityhtml; //retuen value
			break;
		case "institute1_county":
			cityhtml = "<input class='inputbox' type='text' name='institute1_city' size='40' maxlength='100'  />";
			document.getElementById('institute1_city').innerHTML=cityhtml; //retuen value
			break;
		case "institute2_state":
			countyhtml = "<input class='inputbox' type='text' name='institute2_county' size='40' maxlength='100'  />";
			cityhtml = "<input class='inputbox' type='text' name='institute2_city' size='40' maxlength='100'  />";
			document.getElementById('institute2_county').innerHTML=countyhtml; //retuen value
			document.getElementById('institute2_city').innerHTML=cityhtml; //retuen value
			break;
		case "institute2_county":
			cityhtml = "<input class='inputbox' type='text' name='institute2_city' size='40' maxlength='100'  />";
			document.getElementById('institute2_city').innerHTML=cityhtml; //retuen value
			break;
		case "institute3_state":
			countyhtml = "<input class='inputbox' type='text' name='institute3_county' size='40' maxlength='100'  />";
			cityhtml = "<input class='inputbox' type='text' name='institute3_city' size='40' maxlength='100'  />";
			document.getElementById('institute3_county').innerHTML=countyhtml; //retuen value
			document.getElementById('institute3_city').innerHTML=cityhtml; //retuen value
			break;
		case "institute3_county":
			cityhtml = "<input class='inputbox' type='text' name='institute3_city' size='40' maxlength='100'  />";
			document.getElementById('institute3_city').innerHTML=cityhtml; //retuen value
			break;
		case "employer_state":
			document.getElementById('employer_county').innerHTML = "<input class='inputbox' type='text' name='employer_county' size='40' maxlength='100'  />";
			document.getElementById('employer_city').innerHTML = "<input class='inputbox' type='text' name='employer_city' size='40' maxlength='100'  />";
			break;
		case "employer_county":
			document.getElementById('employer_city').innerHTML = "<input class='inputbox' type='text' name='employer_city' size='40' maxlength='100'  />";
			break;
		case "employer1_state":
			document.getElementById('employer1_county').innerHTML = "<input class='inputbox' type='text' name='employer1_county' size='40' maxlength='100'  />";
			document.getElementById('employer1_city').innerHTML = "<input class='inputbox' type='text' name='employer1_city' size='40' maxlength='100'  />";
			break;
		case "employer1_county":
			document.getElementById('employer1_city').innerHTML = "<input class='inputbox' type='text' name='employer1_city' size='40' maxlength='100'  />";
			break;
		case "employer2_state":
			document.getElementById('employer2_county').innerHTML = "<input class='inputbox' type='text' name='employer2_county' size='40' maxlength='100'  />";
			document.getElementById('employer2_city').innerHTML = "<input class='inputbox' type='text' name='employer2_city' size='40' maxlength='100'  />";
			break;
		case "employer2_county":
			document.getElementById('employer2_city').innerHTML = "<input class='inputbox' type='text' name='employer2_city' size='40' maxlength='100'  />";
			break;
		case "employer3_state":
			document.getElementById('employer3_county').innerHTML = "<input class='inputbox' type='text' name='employer3_county' size='40' maxlength='100'  />";
			document.getElementById('employer3_city').innerHTML = "<input class='inputbox' type='text' name='employer3_city' size='40' maxlength='100'  />";
			break;
		case "employer3_county":
			document.getElementById('employer3_city').innerHTML = "<input class='inputbox' type='text' name='employer3_city' size='40' maxlength='100'  />";
			break;
		case "reference_state":
			document.getElementById('reference_county').innerHTML = "<input class='inputbox' type='text' name='reference_county' size='40' maxlength='100'  />";
			document.getElementById('reference_city').innerHTML = "<input class='inputbox' type='text' name='reference_city' size='40' maxlength='100'  />";
			break;
		case "reference_county":
			document.getElementById('reference_city').innerHTML = "<input class='inputbox' type='text' name='reference_city' size='40' maxlength='100'  />";
			break;
		case "reference1_state":
			document.getElementById('reference1_county').innerHTML = "<input class='inputbox' type='text' name='reference1_county' size='40' maxlength='100'  />";
			document.getElementById('reference1_city').innerHTML = "<input class='inputbox' type='text' name='reference1_city' size='40' maxlength='100'  />";
			break;
		case "reference1_county":
			document.getElementById('reference1_city').innerHTML = "<input class='inputbox' type='text' name='reference1_city' size='40' maxlength='100'  />";
			break;
		case "reference2_state":
			document.getElementById('reference2_county').innerHTML = "<input class='inputbox' type='text' name='reference2_county' size='40' maxlength='100'  />";
			document.getElementById('reference2_city').innerHTML = "<input class='inputbox' type='text' name='reference2_city' size='40' maxlength='100'  />";
			break;
		case "reference2_county":
			document.getElementById('reference2_city').innerHTML = "<input class='inputbox' type='text' name='reference2_city' size='40' maxlength='100'  />";
			break;
		case "reference3_state":
			document.getElementById('reference3_county').innerHTML = "<input class='inputbox' type='text' name='reference3_county' size='40' maxlength='100'  />";
			document.getElementById('reference3_city').innerHTML = "<input class='inputbox' type='text' name='reference3_city' size='40' maxlength='100'  />";
			break;
		case "reference3_county":
			document.getElementById('reference3_city').innerHTML = "<input class='inputbox' type='text' name='reference3_city' size='40' maxlength='100'  />";
			break;
	}
}
//window.onLoad=dochange('country', -1);         // value in first dropdown
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
div#outermapdiv{
	width:100%;
	min-width:370px;
	position:relative
}
div#map_container{
	z-index:1000;
	position:relative;
	background:#000;
	width:100%;
	height:100%;
/*	opacity:0.55;
	-moz-opacity:0.45;
	filter:alpha(opacity=45);*/
}
div#map{
	height: 300px;
    left: 5px;
    position: absolute;
    overflow:true;
    top: 0px;
    visibility: hidden;
    width: 100%;
/*
	visibility:hidden;
	position:absolute;
	width:100%;
	height:35%;
	top:0%;
	left:0px;*/
}
</style>
<script type="text/javascript">
		jQuery(document).ready(function() {
		var cityname = jQuery("#addresscityforedit").val();
		if(cityname != ""){
			jQuery("#address_city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
				theme: "jsjobs",
				preventDuplicates: true,
				hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
				noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
				searchingText: "<?php echo JText::_('SEARCHING...');?>",
				tokenLimit: 1,
				prePopulate: [
					{id: "<?php if(isset($this->resume->address_city)) echo $this->resume->address_city; ?>", name: "<?php if(isset($this->resumelists['address_city'][0]->name)) echo $this->resumelists['address_city'][0]->name;?>"}
				]
			});
		}else{
			jQuery("#address_city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
				theme: "jsjobs",
				preventDuplicates: true,
				hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
				noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
				searchingText: "<?php echo JText::_('SEARCHING...');?>",
				tokenLimit: 1,

			});
		}
		var address1city = jQuery("#address1cityforedit").val();
		if(address1city != ""){
			jQuery("#address1_city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
				theme: "jsjobs",
				preventDuplicates: true,
				hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
				noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
				searchingText: "<?php echo JText::_('SEARCHING...');?>",
				tokenLimit: 1,
				prePopulate: [
					{id: "<?php if(isset($this->resume->address1_city)) echo $this->resume->address1_city; ?>", name: "<?php if(isset($this->resumelists['address1_city'][0]->name)) echo $this->resumelists['address1_city'][0]->name;?>"}
				]
			});
		}else{
			jQuery("#address1_city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
				theme: "jsjobs",
				preventDuplicates: true,
				hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
				noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
				searchingText: "<?php echo JText::_('SEARCHING...');?>",
				tokenLimit: 1,

			});
		}
		var address2city = jQuery("#address2cityforedit").val();
		if(address2city != ""){
			jQuery("#address2_city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
				theme: "jsjobs",
				preventDuplicates: true,
				hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
				noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
				searchingText: "<?php echo JText::_('SEARCHING...');?>",
				tokenLimit: 1,
				prePopulate: [
					{id: "<?php if(isset($this->resume->address2_city)) echo $this->resume->address2_city; ?>", name: "<?php if(isset($this->resumelists['address2_city'][0]->name)) echo $this->resumelists['address2_city'][0]->name;?>"}
				]
			});
		}else{
			jQuery("#address2_city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
				theme: "jsjobs",
				preventDuplicates: true,
				hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
				noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
				searchingText: "<?php echo JText::_('SEARCHING...');?>",
				tokenLimit: 1,

			});
		}
		var institute_city = jQuery("#institutecityforedit").val();
		if(institute_city != ""){
			jQuery("#institute_city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
				theme: "jsjobs",
				preventDuplicates: true,
				hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
				noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
				searchingText: "<?php echo JText::_('SEARCHING...');?>",
				tokenLimit: 1,
				prePopulate: [
					{id: "<?php if(isset($this->resume->institute_city)) echo $this->resume->institute_city; ?>", name: "<?php if(isset($this->resumelists['institute_city'][0]->name)) echo $this->resumelists['institute_city'][0]->name;?>"}
				]
			});
		}else{
			jQuery("#institute_city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
				theme: "jsjobs",
				preventDuplicates: true,
				hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
				noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
				searchingText: "<?php echo JText::_('SEARCHING...');?>",
				tokenLimit: 1,

			});
		}
		var institute1_city = jQuery("#institute1cityforedit").val();
		if(institute1_city != ""){
			jQuery("#institute1_city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
				theme: "jsjobs",
				preventDuplicates: true,
				hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
				noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
				searchingText: "<?php echo JText::_('SEARCHING...');?>",
				tokenLimit: 1,
				prePopulate: [
					{id: "<?php if(isset($this->resume->institute1_city)) echo $this->resume->institute1_city; ?>", name: "<?php if(isset($this->resumelists['institute1_city'][0]->name)) echo $this->resumelists['institute1_city'][0]->name;?>"}
				]
			});
		}else{
			jQuery("#institute1_city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
				theme: "jsjobs",
				preventDuplicates: true,
				hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
				noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
				searchingText: "<?php echo JText::_('SEARCHING...');?>",
				tokenLimit: 1,

			});
		}
		var institute2_city = jQuery("#institute2cityforedit").val();
		if(institute2_city != ""){
			jQuery("#institute2_city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
				theme: "jsjobs",
				preventDuplicates: true,
				hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
				noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
				searchingText: "<?php echo JText::_('SEARCHING...');?>",
				tokenLimit: 1,
				prePopulate: [
					{id: "<?php if(isset($this->resume->institute2_city)) echo $this->resume->institute2_city; ?>", name: "<?php if(isset($this->resumelists['institute2_city'][0]->name)) echo $this->resumelists['institute2_city'][0]->name;?>"}
				]
			});
		}else{
			jQuery("#institute2_city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
				theme: "jsjobs",
				preventDuplicates: true,
				hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
				noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
				searchingText: "<?php echo JText::_('SEARCHING...');?>",
				tokenLimit: 1,

			});
		}
		var institute3_city = jQuery("#institute3cityforedit").val();
		if(institute2_city != ""){
			jQuery("#institute3_city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
				theme: "jsjobs",
				preventDuplicates: true,
				hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
				noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
				searchingText: "<?php echo JText::_('SEARCHING...');?>",
				tokenLimit: 1,
				prePopulate: [
					{id: "<?php if(isset($this->resume->institute3_city)) echo $this->resume->institute3_city; ?>", name: "<?php if(isset($this->resumelists['institute3_city'][0]->name)) echo $this->resumelists['institute3_city'][0]->name;?>"}
				]
			});
		}else{
			jQuery("#institute3_city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
				theme: "jsjobs",
				preventDuplicates: true,
				hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
				noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
				searchingText: "<?php echo JText::_('SEARCHING...');?>",
				tokenLimit: 1,

			});
		}
		var employer_city = jQuery("#employercityforedit").val();
		if(employer_city != ""){
			jQuery("#employer_city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
				theme: "jsjobs",
				preventDuplicates: true,
				hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
				noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
				searchingText: "<?php echo JText::_('SEARCHING...');?>",
				tokenLimit: 1,
				prePopulate: [
					{id: "<?php if(isset($this->resume->employer_city)) echo $this->resume->employer_city; ?>", name: "<?php if(isset($this->resumelists['employer_city'][0]->name)) echo $this->resumelists['employer_city'][0]->name;?>"}
				]
			});
		}else{
			jQuery("#employer_city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
				theme: "jsjobs",
				preventDuplicates: true,
				hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
				noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
				searchingText: "<?php echo JText::_('SEARCHING...');?>",
				tokenLimit: 1,

			});
		}
		var employer1_city = jQuery("#employer1cityforedit").val();
		if(employer1_city != ""){
			jQuery("#employer1_city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
				theme: "jsjobs",
				preventDuplicates: true,
				hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
				noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
				searchingText: "<?php echo JText::_('SEARCHING...');?>",
				tokenLimit: 1,
				prePopulate: [
					{id: "<?php if(isset($this->resume->employer1_city)) echo $this->resume->employer1_city; ?>", name: "<?php if(isset($this->resumelists['employer1_city'][0]->name)) echo $this->resumelists['employer1_city'][0]->name;?>"}
				]
			});
		}else{
			jQuery("#employer1_city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
				theme: "jsjobs",
				preventDuplicates: true,
				hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
				noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
				searchingText: "<?php echo JText::_('SEARCHING...');?>",
				tokenLimit: 1,

			});
		}
		var employer2_city = jQuery("#employer2cityforedit").val();
		if(employer2_city != ""){
			jQuery("#employer2_city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
				theme: "jsjobs",
				preventDuplicates: true,
				hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
				noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
				searchingText: "<?php echo JText::_('SEARCHING...');?>",
				tokenLimit: 1,
				prePopulate: [
					{id: "<?php if(isset($this->resume->employer2_city)) echo $this->resume->employer2_city; ?>", name: "<?php if(isset($this->resumelists['employer2_city'][0]->name)) echo $this->resumelists['employer2_city'][0]->name;?>"}
				]
			});
		}else{
			jQuery("#employer2_city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
				theme: "jsjobs",
				preventDuplicates: true,
				hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
				noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
				searchingText: "<?php echo JText::_('SEARCHING...');?>",
				tokenLimit: 1,

			});
		}
		var employer3_city = jQuery("#employer3cityforedit").val();
		if(employer3_city != ""){
			jQuery("#employer3_city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
				theme: "jsjobs",
				preventDuplicates: true,
				hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
				noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
				searchingText: "<?php echo JText::_('SEARCHING...');?>",
				tokenLimit: 1,
				prePopulate: [
					{id: "<?php if(isset($this->resume->employer3_city)) echo $this->resume->employer3_city; ?>", name: "<?php if(isset($this->resumelists['employer3_city'][0]->name)) echo $this->resumelists['employer3_city'][0]->name;?>"}
				]
			});
		}else{
			jQuery("#employer3_city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
				theme: "jsjobs",
				preventDuplicates: true,
				hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
				noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
				searchingText: "<?php echo JText::_('SEARCHING...');?>",
				tokenLimit: 1,

			});
		}
		var reference_city = jQuery("#referencecityforedit").val();
		if(reference_city != ""){
			jQuery("#reference_city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
				theme: "jsjobs",
				preventDuplicates: true,
				hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
				noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
				searchingText: "<?php echo JText::_('SEARCHING...');?>",
				tokenLimit: 1,
				prePopulate: [
					{id: "<?php if(isset($this->resume->reference_city)) echo $this->resume->reference_city; ?>", name: "<?php if(isset($this->resumelists['reference_city'][0]->name)) echo $this->resumelists['reference_city'][0]->name;?>"}
				]
			});
		}else{
			jQuery("#reference_city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
				theme: "jsjobs",
				preventDuplicates: true,
				hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
				noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
				searchingText: "<?php echo JText::_('SEARCHING...');?>",
				tokenLimit: 1,

			});
		}
		var reference1_city = jQuery("#reference1cityforedit").val();
		if(reference1_city != ""){
			jQuery("#reference1_city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
				theme: "jsjobs",
				preventDuplicates: true,
				hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
				noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
				searchingText: "<?php echo JText::_('SEARCHING...');?>",
				tokenLimit: 1,
				prePopulate: [
					{id: "<?php if(isset($this->resume->reference1_city)) echo $this->resume->reference1_city; ?>", name: "<?php if(isset($this->resumelists['reference1_city'][0]->name)) echo $this->resumelists['reference1_city'][0]->name;?>"}
				]
			});
		}else{
			jQuery("#reference1_city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
				theme: "jsjobs",
				preventDuplicates: true,
				hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
				noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
				searchingText: "<?php echo JText::_('SEARCHING...');?>",
				tokenLimit: 1,

			});
		}
		var reference2_city = jQuery("#reference2cityforedit").val();
		if(reference2_city != ""){
			jQuery("#reference2_city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
				theme: "jsjobs",
				preventDuplicates: true,
				hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
				noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
				searchingText: "<?php echo JText::_('SEARCHING...');?>",
				tokenLimit: 1,
				prePopulate: [
					{id: "<?php if(isset($this->resume->reference2_city)) echo $this->resume->reference2_city; ?>", name: "<?php if(isset($this->resumelists['reference2_city'][0]->name)) echo $this->resumelists['reference2_city'][0]->name;?>"}
				]
			});
		}else{
			jQuery("#reference2_city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
				theme: "jsjobs",
				preventDuplicates: true,
				hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
				noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
				searchingText: "<?php echo JText::_('SEARCHING...');?>",
				tokenLimit: 1,

			});
		}
		var reference3_city = jQuery("#reference3cityforedit").val();
		if(reference3_city != ""){
			jQuery("#reference3_city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
				theme: "jsjobs",
				preventDuplicates: true,
				hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
				noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
				searchingText: "<?php echo JText::_('SEARCHING...');?>",
				tokenLimit: 1,
				prePopulate: [
					{id: "<?php if(isset($this->resume->reference3_city)) echo $this->resume->reference3_city; ?>", name: "<?php if(isset($this->resumelists['reference3_city'][0]->name)) echo $this->resumelists['reference3_city'][0]->name;?>"}
				]
			});
		}else{
			jQuery("#reference3_city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
				theme: "jsjobs",
				preventDuplicates: true,
				hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
				noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
				searchingText: "<?php echo JText::_('SEARCHING...');?>",
				tokenLimit: 1,

			});
		}
	});
	
	
	
  function loadMap() {
		var default_latitude = document.getElementById('default_latitude').value;
		var default_longitude = document.getElementById('default_longitude').value;
		
		var latitude = document.getElementById('latitude').value;
		var longitude = document.getElementById('longitude').value;
		
		if((latitude != '') && (longitude != '')){
			default_latitude = latitude;
			default_longitude = longitude;
		}
		var latlng = new google.maps.LatLng(default_latitude, default_longitude); zoom=10;
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
		var marker = new google.maps.Marker({
		  position: latlng, 
		  map: map, 
		});
		marker.setMap(map);
		lastmarker = marker;
		document.getElementById('latitude').value = marker.position.lat();
		document.getElementById('longitude').value = marker.position.lng();

	google.maps.event.addListener(map,"click", function(e){
		var latLng = new google.maps.LatLng(e.latLng.lat(),e.latLng.lng());
		geocoder = new google.maps.Geocoder();
		geocoder.geocode( { 'latLng': latLng}, function(results, status) {
		  if (status == google.maps.GeocoderStatus.OK) {
			if(lastmarker != '') lastmarker.setMap(null);
			var marker = new google.maps.Marker({
				position: results[0].geometry.location, 
				map: map, 
			});
			marker.setMap(map);
			lastmarker = marker;
			document.getElementById('latitude').value = marker.position.lat();
			document.getElementById('longitude').value = marker.position.lng();
			
		  } else {
			alert("Geocode was not successful for the following reason: " + status);
		  }
		});
	});
//document.getElementById('map_container').innerHTML += "<a href='Javascript hidediv();'><?php echo JText::_('JS_CLOSE_MAP');?></a>";
}
function showdiv(){
	document.getElementById('map').style.visibility = 'visible';
}
function hidediv(){
	document.getElementById('map').style.visibility = 'hidden';
}
</script>
