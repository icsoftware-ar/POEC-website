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
 * File Name:	views/jobseeker/tmpl/view_resume.php
 ^ 
 * Description: template for view resume
 ^ 
 * History:		NONE
 ^ 
 */

defined('_JEXEC') or die('Restricted access');
 global $mainframe;
$version = new JVersion;
$joomla = $version->getShortVersion();
$jversion = substr($joomla,0,3);
$resume_style = $this->config['resume_style'];

 $isodd = 0; 
$big_field_width = 40;
$med_field_width = 25;
$sml_field_width = 15;

$vm = $this->vm; 

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


if( isset($this->fieldsordering))
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
		case "section_languages" :	$section_languages = $field->published; break;
		case "section_sub_language" :	$section_sub_language = $field->published; break;
		case "section_sub_language1" :	$section_sub_language1 = $field->published; break;
		case "section_sub_language2" :	$section_sub_language2 = $field->published; break;
		case "section_sub_language3" :	$section_sub_language3 = $field->published; break;
		
	}
}
$document =& JFactory::getDocument();
	if($joomla < 3){
		JHtml::_('behavior.mootools');
		$document->addScript('components/com_jsjobs/js/jquery.js');
	}else{
		JHtml::_('behavior.framework');
		JHtml::_('jquery.framework');
	}	

$document->addStyleSheet('components/com_jsjobs/themes/'.$this->config['theme']);
$document->addScript('administrator/components/com_jsjobs/include/js/jquery_idTabs.js');
?>
<style type="text/css">
div#outermapdiv{
	position:relative;
	float:left;
	z-index:10000;
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
    left: 22px;
    position: absolute;
    overflow:true;
    top: 0px;
    visibility: hidden;
    width: 650px;
/*
	visibility:hidden;
	position:absolute;
	width:100%;
	height:35%;
	top:0%;
	left:0px;*/
}
</style>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
  function loadMap() {
		var default_latitude = document.getElementById('latitude').value;
		var default_longitude = document.getElementById('longitude').value;
		
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

	google.maps.event.addListener(map,"click", function(e){
		return false;
	});
//document.getElementById('map_container').innerHTML += "<a href='Javascript hidediv();'><?php echo JText::_('JS_CLOSE_MAP');?></a>";
}
</script>
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
	<div id="toppanel">
		<div id="tp_header" <?php if($this->config['topimage'] == 0) echo 'style="background:none;"';?>>
			<span id="tp_title"><?php echo $this->config['title'];?></span>
			<span id="tp_curloc">
				<?php if ($this->config['cur_location'] == 1) {
						if ($vm == '1'){ //my resume 
							echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myresumes&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk" ><?php echo JText::_('JS_MY_RESUME'); ?></a> > <?php echo JText::_('JS_VIEW_RESUME');
						}elseif ($vm == '2'){ //view resume
							echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=alljobsappliedapplications&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_APPLIED_RESUME'); ?></a> > <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=job_appliedapplications&bd=<?php echo $this->bd; ?>&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_JOB_APPLIED_APPLICATIONS'); ?></a> > <?php echo JText::_('JS_VIEW_EMP_APP');
						}elseif ($vm == '3'){ //search resume
							echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=resumesearch&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_SEARCH_RESUME'); ?></a> > <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=resume_searchresults&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_RESUME_SEARCH_RESULT'); ?></a> > <?php echo JText::_('JS_VIEW_RESUME');
						}elseif ($vm == '4'){ //short list
							echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_MY_JOBS'); ?></a> > <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=job_appliedapplications&bd=<?php echo $this->bd; ?>&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_SHORT_LIST_CANDIDATES'); ?></a> > <?php echo JText::_('JS_VIEW_RESUME');
						}elseif ($vm == '5'){ //folder resume
							echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myfolders&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_MY_FOLDERS'); ?></a> > <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=folder_resumes&fd=<?php echo $this->fd; ?>&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_VIEW_FOLDER_RESUME'); ?></a> > <?php echo JText::_('JS_VIEW_RESUME');
						}elseif ($vm == '10' OR $vm=="11"){ //resume by category 
							echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=resumebycategory&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_RESUME_BY_CATEGORY'); ?></a> > <a href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=resume_bycategory&cat=<?php echo $this->catid ; ?>&Itemid=<?php echo $this->Itemid;?>"class="curloclnk" > <?php echo JText::_('JS_RESUME_BY_CATEGORY'); ?></a> > <?php if($vm=="11")  echo JText::_('JS_SUB_CATEGORY') ; else echo JText::_('JS_VIEW_RESUME');
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
				<span id="tp_headingtext_center"><?php echo JText::_('JS_VIEW_RESUME');  ?></span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>
<?php
$printform = 1;
	$printform = 1;$canprint = 1;//canprint if the message is already print or not
	if ((isset($this->resume)) &&($this->resume->id != 0)) { // not new form

		if ($this->resume->status == 1) { // Employment Application is actve
			$printform = 1;
		}elseif($this->resume->status == 0){ // not allowed job posting
			$printform = 0;$canprint = 0;?>
			<div id="errormessagedown"></div>
			<div id="errormessage" class="errormessage">
				<div id="message"><b><?php echo "<font color='red'><strong>" . JText::_('JS_RESUME_WAIT_APPROVAL') . "</strong></font>";?></b></div>
			</div>
		<?php } else{ // not allowed job posting
			$printform = 0;$canprint = 0; ?>
			<div id="errormessagedown"></div>
			<div id="errormessage" class="errormessage">
				<div id="message"><b><?php echo "<font color='red'><strong>" . JText::_('JS_RESUME_REJECT') . "</strong></font>";?></b></div>
			</div>
		<?php }
		if ($vm == 1){
			if ($this->resume->uid != $this->uid){ // job seeker can't see other job seeker resume
				$printform = 0;$canprint = 0;?>
				<div id="errormessagedown"></div>
				<div id="errormessage" class="errormessage">
					<div id="message"><b><?php echo "<font color='red'><strong>" . JText::_('EA_YOU_ARE_NOT_ALLOWED_TO_VIEW') . "</strong></font>";?></b></div>
				</div>
			<?php }
		}
	}elseif($this->canview == 0){
			$printform = 0;$canprint = 0; ?>
			<div id="errormessagedown"></div>
			<div id="errormessage" class="errormessage">
				<div id="message"><b><?php echo "<font color='red'><strong>" . JText::_('JS_YOU_CAN_NOT_VIEW_RESUME_DETAIL') . "</strong></font>";?></b></div>
			</div>
        <?php }

if($canprint == 1)
if ($printform == 1) {
	if(isset($this->resume)){
?>

			<table cellpadding="5" cellspacing="0" border="0" width="100%"  >
			
			<tr></tr>
			<?php if (($vm == '2') || ($vm == '3') || ($vm == '5')) { ?>
			<?php  $jobid = $this->bd;
							if($vm != '3'){
                            ?>
			<tr>
                            <?php } ?>
                            <td align="right" valign="center" height="15"><span id="anchor"><a class="anchor" target="_blank" href="index.php?option=com_jsjobs&c=jsjobs&view=output&layout=resumepdf&format=pdf&rd=<?php echo $this->resume->aliasid; ?>&bd=<?php echo $this->bd; ?>&ms=<?php echo $this->ms; ?>">
                            <img src="components/com_jsjobs/images/pdf.png" width="36" height="36" /></a></span>
			    </a></td>
                        </tr>
			<?php } ?>
			</table>
			<div id="tabs_wrapper" class="tabs_wrapper">
			<div class="idTabs">
				<span><a class="selected" href="#personal_info_data"><?php echo JText::_('JS_PERSONAL');?></a></span> 
				<?php if($section_addresses) { ?><span><a  href="#addresses_data"><?php echo JText::_('JS_ADDRESSES');?></a></span>  <?php } ?>
				<?php if($section_education) { ?><span><a  href="#education_data"><?php echo JText::_('JS_EDUCATIONS');?></a></span> <?php } ?>
				<?php if($section_employer) { ?><span><a  href="#employer_data"><?php echo JText::_('JS_EMPLOYERS');?></a></span>  <?php } ?>
				<?php if($section_skills) { ?><span><a  href="#skills_data"><?php echo JText::_('JS_SKILLS');?></a></span>  <?php } ?>
				<?php if($section_resumeeditor) { ?><span><a  href="#resume_editor_data"><?php echo JText::_('JS_RESUME_EDITOR');?></a></span>  <?php } ?>
				<?php if($section_references) { ?><span><a  href="#references_data"><?php echo JText::_('JS_REFERENCES');?></a></span>  <?php } ?>
				<?php if($section_languages) { ?><span><a  href="#languages_data"><?php echo JText::_('JS_LANGUAGES');?></a></span>  <?php } ?>
			</div>
			<?php
				$trclass = array("odd", "even");
				$i = 0;
				foreach($this->fieldsordering as $field){ 
					switch ($field->field) {
						case "section_personal": ?>
									<div id="personal_info_data">
									<table cellpadding="5" cellspacing="0" border="0" width="100%" >
									<tr>
										<td width="100" colspan="2" align="center" class="rs_sectionheadline">
											<span id="rs_sectiontext"><?php echo JText::_('JS_PERSONAL_INFORMATION'); ?></span>
										</td>
									</tr>
						
						<?php break;
						case "applicationtitle": $isodd = 1 - $isodd; ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td width="150" align="right" class="textfieldtitle">
									<?php echo JText::_('JS_APPLICATION_TITLE'); ?>:
								</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->application_title;?>
								</td>
							</tr>
						<?php break;
						case "firstname":  $isodd = 1 - $isodd; ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_FIRST_NAME'); ?>:
								</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->first_name;?>
								</td>
							</tr>
						<?php break;
						case "middlename":  $isodd = 1 - $isodd; ?>
						<?php if ( $field->published == 1 ) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_MIDDLE_NAME'); ?>:
								</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->middle_name;?>
								</td>
							</tr>
						<?php } ?>
						<?php break;
						case "lastname":  $isodd = 1 - $isodd; ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_LAST_NAME'); ?>:
								</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->last_name;?>
								</td>
							</tr>
						<?php break;
						case "emailaddress":  $isodd = 1 - $isodd; ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_EMAIL_ADDRESS'); ?>:
								</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->email_address;?>
								</td>						
							</tr>
						<?php break;
						case "homephone":  $isodd = 1 - $isodd; ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_HOME_PHONE'); ?>:
								</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->home_phone;?>
								</td>						
							</tr>
						<?php break;
						case "workphone":  $isodd = 1 - $isodd; ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_WORK_PHONE'); ?>:
								</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->work_phone;?>
								</td>						
							</tr>
						<?php break;
						case "cell":  $isodd = 1 - $isodd; ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CELL'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->cell;?>
								</td>						
							</tr>
						<?php break;
						case "gender":  $isodd = 1 - $isodd; ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_GENDER');  ?>:
								</td>
								<td><?php if(isset($this->resume)) echo ($this->resume->gender == 1) ? JText::_('JS_MALE') : JText::_('JS_FEMALE'); ?>	</td>
							</tr>
						<?php break;
						case "Iamavailable":  $isodd = 1 - $isodd; ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td valign="top" align="right"><?php echo JText::_('JS_I_AM_AVAILABLE'); ?></td>
								<td><?php if(isset($this->resume)) echo ($this->resume->iamavailable == 1) ? JText::_('JS_A_YES') : JText::_('JS_A_NO'); ?> </td>
							</tr>
					<?php break;
					case "photo":  $isodd = 1 - $isodd; ?>
                                                        <?php if (isset($this->resume))
                                                            if($this->resume->photo != '') {?>
                                                                    <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>"><td valign="top" align="right"><?php echo JText::_('JS_PHOTO'); ?></td>
                                                                    <td  >
                                                                    <div style="max-width: 150px;max-height: 150px;">
																		<?php 
																			if($this->isjobsharing){ 
																				if($this->vm==2) {?>
																					<img  height="150" width="150" src="<?php echo $this->resume->image_path; ?>"  />
																				<?php }else { ?>
																					<img  height="150" width="150" src="<?php echo $this->config['data_directory'];?>/data/jobseeker/resume_<?php echo $this->resume->id.'/photo/'.$this->resume->photo; ?>"  />
																				  <?php } ?>	
                                                                         <?php }else{ ?>   
																					<img  height="150" width="150" src="<?php echo $this->config['data_directory'];?>/data/jobseeker/resume_<?php echo $this->resume->id.'/photo/'.$this->resume->photo; ?>"  />
																		  <?php } ?>	
                                                                    </div>
                                                                    </td></tr>
                                                        <?php } ?>
						<?php break;
						case "nationality":  $isodd = 1 - $isodd; ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_NATIONALITY_COUNTRY');  ?>:
								</td>
								<td><?php echo $this->resume->nationalitycountry; ?></td>
							</tr>
						<?php break;
						case "section_basic": ?>
							<tr height="21"><td colspan="2"></td></tr>
							<tr>
								<td width="100" colspan="2" align="center" class="rs_sectionheadline">
									<span id="rs_sectiontext"><?php echo JText::_('JS_BASIC_INFORMATION'); ?></span>
								</td>
							</tr>
						<?php break;
						case "category":  $isodd = 1 - $isodd; ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_CATEGORY');  ?>:
								</td>
								<td>
									<?php
										echo $this->resume->categorytitle;
									?>
								</td>
							</tr>
						<?php break;
						case "salary":  $isodd = 1 - $isodd; ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td width="100"align="right" class="textfieldtitle">
									<?php echo JText::_('JS_DESIRED_SALARY'); ?>:
								</td>
								<td colspan="2" >
									<?php  echo $this->resume->symbol.$this->resume->rangestart.' - '.$this->resume->symbol.$this->resume->rangeend .' '. JText::_('JS_PERMONTH'); ?>
								</td>
							</tr>
						<?php break;
						case "jobtype":  $isodd = 1 - $isodd; ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_WORK_PREFERENCE'); ?>:	</td>
								<td colspan="2" valign="top" >
									<?php echo $this->resume->jobtypetitle; ?>
								</td>
							</tr>
						<?php break;
						case "heighesteducation":  $isodd = 1 - $isodd; ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_HEIGHTESTFINISHEDEDUCATION'); ?>:</td>
								<td colspan="2" valign="top" >
									<?php
										//echo $this->resumelists['work_preferences'];
										echo $this->resume->heighesteducationtitle; 
									?>
								</td>
							</tr>
						<?php break;
						case "totalexperience":  $isodd = 1 - $isodd; ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_TOTAL_EXPERIENCE'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->total_experience;?>
								</td>						
							</tr>
						<?php break;
						case "startdate":  $isodd = 1 - $isodd; ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_DATE_CAN_START'); ?>:</td>
								<td>
									<?php if (isset($this->resume) && $this->resume->date_start != '0000-00-00 00:00:00') 
									echo date($this->config['date_format'],strtotime($this->resume->date_start));?>
								</td>						
							</tr>
					<?php break;
						case "date_of_birth":  $isodd = 1 - $isodd; ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_DATE_OF_BIRTH'); ?>:</td>
								<td>
									<?php if (isset($this->resume) && $this->resume->date_of_birth != '0000-00-00 00:00:00')
									echo date($this->config['date_format'],strtotime($this->resume->date_of_birth));?>
								</td>						
							</tr>
					<?php break;
					case "video": $isodd = 1 - $isodd;
						if (!empty($this->resume->video)) { ?>
				      <tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
				        <td class="textfieldtitle" align="right" valign="top"><?php echo JText::_('JS_VIDEO'); ?></td>
						<td >
						<iframe title="YouTube video player" width="380" height="290" 
                                                        src="http://www.youtube.com/embed/<?php echo $this->resume->video; ?>" frameborder="0" allowfullscreen>
                                                </iframe>
						</td>
				      </tr>
					  <?php } 
					  break;
					  case "section_userfields": ?>
							<tr>
								<td width="200" colspan="2" align="center" class="rs_sectionheadline">
									<span id="rs_sectiontext"><?php echo JText::_($field->fieldtitle); ?></span>
								</td>
							</tr>
			<?php				
				
				if($this->isjobsharing!="") {
					if(is_object($this->userfields)) {
						for($k = 0; $k < 15;$k++){
								$isodd = 1 - $isodd; 
								$field_title='fieldtitle_'.$k;
								$field_value='fieldvalue_'.$k;
								echo '<tr id="mc_field_row" class="'.$this->theme[$trclass[$isodd]] .'">';
								echo '<td align="right" class="textfieldtitle">'. $this->userfields->$field_title.':</td>';
								echo '<td class="ji_data2">'.$this->userfields->$field_value.'</td>';	
								echo '</tr>';
						}
					}
				}else{
					foreach($this->userfields as $ufield){ 
						if($ufield[0]->published==1) {
							$isodd = 1 - $isodd; 
							$userfield = $ufield[0];
							$i++;
							echo '<tr id="mc_field_row" class="'.$this->theme[$trclass[$isodd]] .'">';
							echo '<td align="right" class="textfieldtitle">'. $userfield->title .':</td>';
							if ($userfield->type == "checkbox"){
								if(isset($ufield[1])){ $fvalue = $ufield[1]->data; $userdataid = $ufield[1]->id;}  else {$fvalue=""; $userdataid = ""; }
								if ($fvalue == '1') $fvalue = "True"; else $fvalue = "false";
							}elseif ($userfield->type == "select"){
								if(isset($ufield[2])){ $fvalue = $ufield[2]->fieldtitle; $userdataid = $ufield[2]->id;} else {$fvalue=""; $userdataid = ""; }
							}else{
								if(isset($ufield[1])){ $fvalue = $ufield[1]->data; $userdataid = $ufield[1]->id;}  else {$fvalue=""; $userdataid = ""; }
							}
							echo '<td class="ji_data2">'.$fvalue.'</td>';	
							echo '</tr>';
						}
					
				}
			}	
		
		?>
					<?php break;
					
						case "section_addresses": ?>
							</table>
							</div>	
									<div id="addresses_data" >
									<table cellpadding="5" cellspacing="0" border="0" width="100%"  >
										<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
											<tr>
												<td width="100" colspan="2" align="center" class="rs_sectionheadline">
													<span id="rs_sectiontext"><?php echo JText::_('JS_ADDRESS'); ?></span>
												</td>
											</tr>
										<?php } ?>
					<?php break;
						case "address_country":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td width="150" align="right" class="textfieldtitle">
									<?php echo JText::_('JS_COUNTRY'); ?>
								</td>
								<td id="address_country">
								<?php echo $this->resume->address_country; ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address_state":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_STATE'); ?>:
								</td>
								<td id="address_state">
									<?php if ($this->resume->address_state2 !='') echo $this->resume->address_state2; else echo $this->resume->address_state;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address_city":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_CITY'); ?>:
								</td>
								<td id="address_city">
									<?php if ($this->resume->address_city2 !='') echo $this->resume->address_city2; else echo $this->resume->address_city;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address_zipcode":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_ZIPCODE'); ?>:
								</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->address_zipcode;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address_address":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_ADDRESS'); ?>:
								</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->address;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address_location":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_ADDRESS_LOCATION'); ?>:
								</td>
								<td>
									<input id="longitude" type="hidden" name="longitude" value="<?php echo $this->resume->longitude;?>" />
									<input id="latitude" type="hidden" name="latitude" value="<?php echo $this->resume->latitude;?>" />
									<div id="outermapdiv">
										<div id="map" style="width:<?php echo $this->config['mapwidth'];?>px; height:<?php echo $this->config['mapheight'];?>px">
											<div id="closetag"><a href="Javascript: hidediv();"><?php echo JText::_('X');?></a></div>
											<div id="map_container"></div>
										</div>
									</div>
								</td>
							</tr>
							<?php echo "<script language='javascript'>loadMap();</script>";} ?>
					<?php break;
						case "section_sub_address1":  ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address1 == 1)) { ?>
								<tr height="21"><td colspan="2"></td></tr>
								<tr>
									<td width="100" colspan="2" align="center" class="rs_sectionheadline">
										<span id="rs_sectiontext"><?php echo JText::_('JS_ADDRESS1'); ?></span>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "address1_country":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address1 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_COUNTRY'); ?>
								</td>
								<td id="address_country">
								<?php echo $this->resume->address1_country; ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address1_state":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address1 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_STATE'); ?>:
								</td>
								<td id="address1_state">
									<?php if ($this->resume->address1_state2 !='') echo $this->resume->address1_state2; else echo $this->resume->address1_state;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address1_city":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address1 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_CITY'); ?>:
								</td>
								<td id="address1_city">
									<?php if ($this->resume->address1_city2 !='') echo $this->resume->address1_city2; else echo $this->resume->address1_city;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address1_zipcode":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address1 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_ZIPCODE'); ?>:
								</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->address1_zipcode;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address1_address":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address1 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_ADDRESS'); ?>:
								</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->address1;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "section_sub_address2":  ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address2 == 1)) { ?>
								<tr height="21"><td colspan="2"></td></tr>
								<tr>
									<td width="100" colspan="2" align="center" class="rs_sectionheadline">
										<span id="rs_sectiontext"><?php echo JText::_('JS_ADDRESS2'); ?></span>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "address2_country":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address2 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_COUNTRY'); ?>
								</td>
								<td id="address_country">
								<?php echo $this->resume->address2_country; ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address2_state":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address2 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_STATE'); ?>:
								</td>
								<td id="address2_state">
									<?php if ($this->resume->address2_state2 !='') echo $this->resume->address2_state2; else echo $this->resume->address2_state;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address2_city":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address2 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_CITY'); ?>:
								</td>
								<td id="address2_city">
									<?php if ($this->resume->address2_city2 !='') echo $this->resume->address2_city2; else echo $this->resume->address2_city;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address2_zipcode":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address2 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_ZIPCODE'); ?>:
								</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->address2_zipcode;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "address2_address":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_addresses == 1) && ($section_sub_address2 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle">
									<?php echo JText::_('JS_ADDRESS'); ?>:
								</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->address2;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "section_education": ?>
							</table>
							</div>
									<div id="education_data">
									<table cellpadding="5" cellspacing="0" border="0" width="100%"  >
										<?php  if (($section_education == 1) && ($section_sub_institute == 1)) { ?>
											<tr>
												<td width="100" colspan="2" align="center" class="rs_sectionheadline">
													<span id="rs_sectiontext"><?php echo JText::_('JS_HIGH_SCHOOL'); ?></span>
												</td>
											</tr>
										<?php } ?>

										
					<?php break;
						case "institute_institute":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute == 1)) { ?>
								<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td width="150" align="right" class="textfieldtitle"><?php echo JText::_('JS_SCH_COL_UNI'); ?>:	</td>
									<td>
										<?php if (isset($this->resume)) echo $this->resume->institute;?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute_certificate":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute == 1)) { ?>
								<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CRT_DEG_OTH'); ?>:	</td>
									<td>
										<?php if (isset($this->resume)) echo $this->resume->institute_certificate_name;?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute_study_area":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute == 1)) { ?>
								<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_AREA_OF_STUDY'); ?>:</td>
									<td>
										<?php if (isset($this->resume)) echo $this->resume->institute_study_area;?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute_country":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute == 1)) { ?>
								<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?>:</td>
									<td id="institute_country">
									<?php echo $this->resume2->institute_country; ?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute_state":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute == 1)) { ?>
								<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
									<td id="institute_state">
										<?php if ($this->resume2->institute_state2 !='') echo $this->resume2->institute_state2; else echo $this->resume->institute_state;?>
								</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute_city":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute == 1)) { ?>
								<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
										<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
										<td id="institute_city">
											<?php if ($this->resume2->institute_city2 !='') echo $this->resume2->institute_city2; else echo $this->resume->institute_city;?>
										</td>
								</tr>
							<?php } ?>

					<?php break;
						case "section_sub_institute1":  ?>
							<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
								<tr height="21"><td colspan="2"></td></tr>
								<tr>
									<td width="100" colspan="2" align="center" class="rs_sectionheadline">
										<span id="rs_sectiontext"><?php echo JText::_('JS_UNIVERSITY'); ?></span>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute1_institute":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
								<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_SCH_COL_UNI'); ?>:	</td>
									<td>
										<?php if (isset($this->resume)) echo $this->resume->institute1;?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute1_certificate":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
								<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CRT_DEG_OTH'); ?>:	</td>
									<td>
										<?php if (isset($this->resume)) echo $this->resume->institute1_certificate_name;?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute1_study_area":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
								<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_AREA_OF_STUDY'); ?>:</td>
									<td>
										<?php if (isset($this->resume)) echo $this->resume->institute1_study_area;?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute1_country":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
								<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?>:</td>
									<td id="institute1_country">
									<?php echo $this->resume2->institute1_country; ?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute1_state":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
								<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
									<td id="institute1_state">
										<?php if ($this->resume2->institute1_state2 !='') echo $this->resume2->institute1_state2; else echo $this->resume->institute1_state;?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute1_city":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
								<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
										<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
										<td id="institute1_city">
											<?php if ($this->resume2->institute1_city2 !='') echo $this->resume2->institute1_city2; else echo $this->resume->institute1_city;?>
										</td>
								</tr>
							<?php } ?>
					<?php break;
						case "section_sub_institute2": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute2 == 1)) { ?>
								<tr height="21"><td colspan="2"></td></tr>
								<tr>
									<td width="100" colspan="2" align="center" class="rs_sectionheadline">
										<span id="rs_sectiontext"><?php echo JText::_('JS_GRADE_SCHOOL'); ?></span>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute2_institute":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute2 == 1)) { ?>
								<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_SCH_COL_UNI'); ?>:	</td>
									<td>
										<?php if (isset($this->resume)) echo $this->resume->institute2;?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute2_certificate":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute2 == 1)) { ?>
								<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CRT_DEG_OTH'); ?>:	</td>
									<td>
										<?php if (isset($this->resume)) echo $this->resume->institute2_certificate_name;?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute2_study_area":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute2 == 1)) { ?>
								<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_AREA_OF_STUDY'); ?>:</td>
									<td>
										<?php if (isset($this->resume)) echo $this->resume->institute2_study_area;?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute2_country":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute2 == 1)) { ?>
								<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?>:</td>
									<td id="institute2_country">
									<?php echo $this->resume2->institute2_country; ?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute2_state":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute2 == 1)) { ?>
								<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
									<td id="institute2_state">
										<?php if ($this->resume2->institute2_state2 !='') echo $this->resume2->institute2_state2; else echo $this->resume->institute2_state;?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute2_city":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute2 == 1)) { ?>
								<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
										<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
										<td id="institute2_city">
									<?php if ($this->resume2->institute2_city2 !='') echo $this->resume2->institute2_city2; else echo $this->resume->institute2_city;?>
										</td>
								</tr>
							<?php } ?>
					<?php break;
						case "section_sub_institute3": ?>
							<?php  if (($section_education == 1) && ($section_sub_institute3 == 1)) { ?>
								<tr height="21"><td colspan="2"></td></tr>
								<tr>
									<td width="100" colspan="2" align="center" class="rs_sectionheadline">
										<span id="rs_sectiontext"><?php echo JText::_('JS_OTHER_SCHOOL'); ?></span>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute3_institute":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute3 == 1)) { ?>
								<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_SCH_COL_UNI'); ?>:	</td>
									<td>
										<?php if (isset($this->resume)) echo $this->resume->institute3;?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute3_certificate":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute3 == 1)) { ?>
								<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CRT_DEG_OTH'); ?>:	</td>
									<td>
										<?php if (isset($this->resume)) echo $this->resume->institute3_certificate_name;?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute3_study_area":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute3 == 1)) { ?>
								<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_AREA_OF_STUDY'); ?>:</td>
									<td>
										<?php if (isset($this->resume)) echo $this->resume->institute3_study_area;?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute3_country":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute3 == 1)) { ?>
								<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?>:</td>
									<td id="institute3_country">
									<?php echo $this->resume2->institute3_country; ?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute3_state":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute3 == 1)) { ?>
								<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
									<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
									<td id="institute3_state">
									<?php if ($this->resume2->institute3_state2 !='') echo $this->resume2->institute3_state2; else echo $this->resume->institute3_state;?>
									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "institute3_city":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_education == 1) && ($section_sub_institute3 == 1)) { ?>
								<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
										<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
										<td id="institute3_city">
											<?php if ($this->resume2->institute3_city2 !='') echo $this->resume2->institute3_city2; else echo $this->resume->institute3_city;?>
										</td>
								</tr>
							<?php } ?>

							

					<?php break;
						case "section_employer": ?>
							</table>
							</div>
									<div id="employer_data">
									<table cellpadding="5" cellspacing="0" border="0" width="100%"  >
										<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
											<tr>
												<td width="100" colspan="2" align="center" class="rs_sectionheadline">
													<span id="rs_sectiontext"><?php echo JText::_('JS_RECENT_EMPLOYER'); ?></span>
												</td>
											</tr>
										<?php } ?>
					<?php break;
						case "employer_employer":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td width="150" align="right" class="textfieldtitle"><?php echo JText::_('JS_EMPLOYER'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_position":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_POSITION'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer_position;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_resp":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_RESPONSIBILITIES'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer_resp;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_pay_upon_leaving":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_PAY_UPON_LEAVING'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer_pay_upon_leaving;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_supervisor":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_SUPERVISOR'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer_supervisor;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_from_date":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_FROM_DATE'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer_from_date;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_to_date":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_TO_DATE'); ?>:	</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer_to_date;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_leave_reason":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_LEAVING_REASON'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer_leave_reason;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_country":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?>:	</td>
								<td id="employer_country">
									<?php echo $this->resume2->employer_country; ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_state":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
								<td id="employer_state">
										<?php if ($this->resume2->employer_state2 !='') echo $this->resume2->employer_state2; else echo $this->resume->employer_state;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_city":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
								<td id="employer_city">
										<?php if ($this->resume2->employer_city2 !='') echo $this->resume2->employer_city2; else echo $this->resume->employer_city;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_zip":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ZIPCODE'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer_zip;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer_address":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ADDRESS'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer_address;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "employer_phone":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_PHONE'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer_phone;?>
								</td>
							</tr>
							<?php } ?>


					<?php break;
						case "section_sub_employer1": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr height="21"><td colspan="2"></td></tr>
							<tr>
								<td width="100" colspan="2" align="center" class="rs_sectionheadline">
									<span id="rs_sectiontext"><?php echo JText::_('JS_PRIOR_EMPLOYER_1'); ?></span>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_employer":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td width="150" align="right" class="textfieldtitle"><?php echo JText::_('JS_EMPLOYER'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer1;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_position":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_POSITION'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer1_position;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_resp":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_RESPONSIBILITIES'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer1_resp;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_pay_upon_leaving":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_PAY_UPON_LEAVING'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer1_pay_upon_leaving;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_supervisor":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_SUPERVISOR'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer1_supervisor;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_from_date":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_FROM_DATE'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer1_from_date;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_to_date":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_TO_DATE'); ?>:	</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer1_to_date;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_leave_reason":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_LEAVING_REASON'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer1_leave_reason;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_country":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?>:	</td>
								<td id="employer1_country">
									<?php echo $this->resume2->employer1_country; ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_state":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
								<td id="employer1_state">
										<?php if ($this->resume2->employer1_state2 !='') echo $this->resume2->employer1_state2; else echo $this->resume->employer1_state;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_city":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
								<td id="employer1_city">
										<?php if ($this->resume2->employer1_city2 !='') echo $this->resume2->employer1_city2; else echo $this->resume->employer1_city;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_zip":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ZIPCODE'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer1_zip;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer1_address":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ADDRESS'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer1_address;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "employer1_phone":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer1 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_PHONE'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer1_phone;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "section_sub_employer2": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr height="21"><td colspan="2"></td></tr>
							<tr>
								<td width="100" colspan="2" align="center" class="rs_sectionheadline">
									<span id="rs_sectiontext"><?php echo JText::_('JS_PRIOR_EMPLOYER_2'); ?></span>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_employer":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td width="150" align="right" class="textfieldtitle"><?php echo JText::_('JS_EMPLOYER'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer2;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_position":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_POSITION'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer2_position;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_resp":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_RESPONSIBILITIES'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer2_resp;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_pay_upon_leaving":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_PAY_UPON_LEAVING'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer2_pay_upon_leaving;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_supervisor":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_SUPERVISOR'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer2_supervisor;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_from_date":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_FROM_DATE'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer2_from_date;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_to_date":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_TO_DATE'); ?>:	</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer2_to_date;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_leave_reason":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_LEAVING_REASON'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer2_leave_reason;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_country":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?>:	</td>
								<td id="employer2_country">
									<?php echo $this->resume2->employer2_country; ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_state":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
								<td id="employer2_state">
										<?php if ($this->resume2->employer2_state2 !='') echo $this->resume2->employer2_state2; else echo $this->resume->employer2_state;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_city":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
								<td id="employer2_city">
										<?php if ($this->resume2->employer2_city2 !='') echo $this->resume2->employer2_city2; else echo $this->resume->employer2_city;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_zip":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ZIPCODE'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer2_zip;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer2_address":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ADDRESS'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer2_address;?>
								</td>
							</tr>
						<?php } ?>	
				<?php break;
						case "employer2_phone":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer2 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_PHONE'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer2_phone;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "section_sub_employer3": ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr height="21"><td colspan="2"></td></tr>
							<tr>
								<td width="100" colspan="2" align="center" class="rs_sectionheadline">
									<span id="rs_sectiontext"><?php echo JText::_('JS_PRIOR_EMPLOYER_3'); ?></span>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_employer":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td width="150" align="right" class="textfieldtitle"><?php echo JText::_('JS_EMPLOYER'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer3;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_position":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_POSITION'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer3_position;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_resp":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_RESPONSIBILITIES'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer3_resp;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_pay_upon_leaving":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_PAY_UPON_LEAVING'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer3_pay_upon_leaving;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_supervisor":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_SUPERVISOR'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer3_supervisor;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_from_date":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_FROM_DATE'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer3_from_date;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_to_date":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_TO_DATE'); ?>:	</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer3_to_date;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_leave_reason":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_LEAVING_REASON'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer3_leave_reason;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_country":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?>:	</td>
								<td id="employer3_country">
									<?php echo $this->resume2->employer3_country; ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_state":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
								<td id="employer3_state">
										<?php if ($this->resume2->employer3_state2 !='') echo $this->resume2->employer3_state2; else echo $this->resume->employer3_state;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_city":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
								<td id="employer3_city">
										<?php if ($this->resume2->employer3_city2 !='') echo $this->resume2->employer3_city2; else echo $this->resume->employer3_city;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_zip":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ZIPCODE'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer3_zip;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "employer3_address":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ADDRESS'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer3_address;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "employer3_phone":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_employer == 1) && ($section_sub_employer3 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_PHONE'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->employer3_phone;?>
								</td>
							</tr>
							<?php } ?>
							

					<?php break;
						case "section_skills": ?>
							</table>
							</div>

								<div id="skills_data">
								<table cellpadding="5" cellspacing="0" border="0" width="100%"  >
									<tr>
										<td width="100" colspan="2" align="center" class="rs_sectionheadline">
											<span id="rs_sectiontext"><?php echo JText::_('JS_SKILLS'); ?></span>
										</td>
									</tr>
					<?php break;
						case "driving_license":  $isodd = 1 - $isodd; ?>
							<?php  if ($section_skills == 1) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td width="250" align="right" class="textfieldtitle"><?php echo JText::_('JS_HAVE_DRIVING_LICENSE'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->driving_license; ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "license_no":  $isodd = 1 - $isodd; ?>
							<?php  if ($section_skills == 1) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_YSE_LICENSE_NO'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->license_no;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "license_country":  $isodd = 1 - $isodd; ?>
							<?php  if ($section_skills == 1) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_YSE_LICENSE_COUNTRY'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->license_country;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "skills":  $isodd = 1 - $isodd; ?>
							<?php  if ($section_skills == 1) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td align="right" class="textfieldtitle"><?php echo JText::_('JS_SKILLS'); ?>:</td>
								<td>
									<?php if(isset($this->resume)) echo $this->resume->skills; ?>
								</td>
							</tr>
							<?php } ?>
										
										
					<?php break;
						case "section_resumeeditor": ?>
							</table>
							</div>	
								<div id="resume_editor_data" >
								<table cellpadding="5" cellspacing="0" border="0" width="100%" >
									<tr>
										<td width="100" colspan="2" align="center" class="rs_sectionheadline">
											<span id="rs_sectiontext"><?php echo JText::_('JS_RESUME'); ?></span>
										</td>
									</tr>

					<?php break;
						case "editor": ?>
							<?php  if ($section_resumeeditor == 1) { ?>
								<tr>
									<td colspan="2">
									    <?php
												echo $this->resume->resume;
			                                ?>

									</td>
								</tr>
							<?php } ?>
					<?php break;
						case "fileupload": ?>
							<?php  if ($section_resumeeditor == 1) { ?>
								<?php if (isset($this->resume)) 
											if($this->resume->filename != '') {?>
										<tr height="21"><td colspan="2"></td></tr>
											<tr>
												<td width="100" colspan="2" align="center" class="rs_sectionheadline">
													<span id="rs_sectiontext"><?php echo JText::_('JS_RESUME_FILE'); ?></span>
												</td>
											</tr>
												<?php 
												if($this->isjobsharing){
													if($this->vm==2){
														$link = $this->resume->file_url; 
													}else{
														if($jversion == '1.5')
															$link = $mainframe->getBasePath().$this->config['data_directory'].'/data/jobseeker/resume_'.$this->resume->id.'/resume/'.$this->resume->filename;
														else
															$link = $this->config['data_directory'].'/data/jobseeker/resume_'.$this->resume->id.'/resume/'.$this->resume->filename;
													}
												}else{
													if($jversion == '1.5')
														$link = $mainframe->getBasePath().$this->config['data_directory'].'/data/jobseeker/resume_'.$this->resume->id.'/resume/'.$this->resume->filename;
													else
														$link = $this->config['data_directory'].'/data/jobseeker/resume_'.$this->resume->id.'/resume/'.$this->resume->filename;
													
												}
													?>
												<tr><td colspan="2" align="center"><span id="button"><a class="button minpad" href="<?php echo $link ?>"><?php echo JText::_('JS_DOWNLOAD'); ?></a></span></td></tr>
								<?php } ?>				
							<?php } ?>
						
						
					<?php break;
						case "section_references": ?>
							</table>
							</div>
									<div id="references_data">
									<table cellpadding="5" cellspacing="0" border="0" width="100%"  >
										<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
										<tr>
											<td width="100" colspan="2" align="center" class="rs_sectionheadline">
												<span id="rs_sectiontext"><?php echo JText::_('JS_REFERENCE1'); ?></span>
											</td>
										</tr>
										<?php } ?>
							
					<?php break;
						case "reference_name":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td width="150" align="right" class="textfieldtitle"><?php echo JText::_('JS_NAME'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference_name;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "reference_country":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?></td>
								<td id="reference_country">
								<?php echo $this->resume3->reference_country; ?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "reference_state":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
								<td id="reference_state">
										<?php if ($this->resume3->reference_state2 !='') echo $this->resume3->reference_state2; else echo $this->resume->reference_state;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "reference_city":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
						        <td id="reference_city">
										<?php if ($this->resume3->reference_city2 !='') echo $this->resume3->reference_city2; else echo $this->resume->reference_city;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "reference_zipcode":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ZIPCODE'); ?>:</td>
						        <td >
									<?php if (isset($this->resume)) echo $this->resume->reference_zipcode;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference_address":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ADDRESS'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference_address;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "reference_phone":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_PHONE'); ?>:	</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference_phone;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "reference_relation":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_RELATION'); ?>:	</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference_relation;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "reference_years":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_YEARS'); ?>:	</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference_years;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "section_sub_reference1": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
								<tr height="21"><td colspan="2"></td></tr>
							<tr>
								<td width="100" colspan="2" align="center" class="rs_sectionheadline">
									<span id="rs_sectiontext"><?php echo JText::_('JS_REFERENCE2'); ?></span>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "reference1_name":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_NAME'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference1_name;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference1_country":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?></td>
								<td id="reference1_country">
								<?php echo $this->resume3->reference1_country; ?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference1_state":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
								<td id="reference1_state">
										<?php if ($this->resume3->reference1_state2 !='') echo $this->resume3->reference1_state2; else echo $this->resume->reference1_state;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference1_city":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
						        <td id="reference1_city">
										<?php if ($this->resume3->reference1_city2 !='') echo $this->resume3->reference1_city2; else echo $this->resume->reference1_city;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference1_zipcode":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ZIPCODE'); ?>:</td>
						        <td >
									<?php if (isset($this->resume)) echo $this->resume->reference1_zipcode;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference1_address":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ADDRESS'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference1_address;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference1_phone":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_PHONE'); ?>:	</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference1_phone;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference1_relation":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_RELATION'); ?>:	</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference1_relation;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference1_years":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_YEARS'); ?>:	</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference1_years;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "section_sub_reference2": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
								<tr height="21"><td colspan="2"></td></tr>
							<tr>
								<td width="100" colspan="2" align="center" class="rs_sectionheadline">
									<span id="rs_sectiontext"><?php echo JText::_('JS_REFERENCE3'); ?></span>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_name":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_NAME'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference2_name;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_country":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?></td>
								<td id="reference2_country">
								<?php echo $this->resume3->reference2_country; ?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_state":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
								<td id="reference2_state">
										<?php if ($this->resume3->reference2_state2 !='') echo $this->resume3->reference2_state2; else echo $this->resume->reference2_state;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_city":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
						        <td id="reference2_city">
										<?php if ($this->resume3->reference2_city2 !='') echo $this->resume3->reference2_city2; else echo $this->resume->reference2_city;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_zipcode":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ZIPCODE'); ?>:</td>
						        <td >
									<?php if (isset($this->resume)) echo $this->resume->reference2_zipcode;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_address":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ADDRESS'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference2_address;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_phone":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_PHONE'); ?>:	</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference2_phone;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_relation":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_RELATION'); ?>:	</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference2_relation;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference2_years":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference2 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_YEARS'); ?>:	</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference2_years;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "section_sub_reference3": ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
								<tr height="21"><td colspan="2"></td></tr>
							<tr>
								<td width="100" colspan="2" align="center" class="rs_sectionheadline">
									<span id="rs_sectiontext"><?php echo JText::_('JS_REFERENCE4'); ?></span>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_name":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_NAME'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference3_name;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_country":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_COUNTRY'); ?></td>
								<td id="reference3_country">
								<?php echo $this->resume3->reference3_country; ?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_state":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_STATE'); ?>:</td>
								<td id="reference3_state">
										<?php if ($this->resume3->reference3_state2 !='') echo $this->resume3->reference3_state2; else echo $this->resume->reference3_state;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_city":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_CITY'); ?>:</td>
						        <td id="reference3_city">
										<?php if ($this->resume3->reference3_city2 !='') echo $this->resume3->reference3_city2; else echo $this->resume->reference3_city;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_zipcode":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ZIPCODE'); ?>:</td>
						        <td >
									<?php if (isset($this->resume)) echo $this->resume->reference3_zipcode;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_address":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_ADDRESS'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference3_address;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_phone":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_PHONE'); ?>:	</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference3_phone;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_relation":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_RELATION'); ?>:	</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference3_relation;?>
								</td>
							</tr>
						<?php } ?>	
					<?php break;
						case "reference3_years":  $isodd = 1 - $isodd; ?>
							<?php  if (($section_references == 1) && ($section_sub_reference3 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_YEARS'); ?>:	</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->reference3_years;?>
								</td>
							</tr>
						<?php } 	
						break;	
						case "section_languages": ?>
							</table>
							</div>	
								<div id="languages_data">
									<table cellpadding="5" cellspacing="0" border="0" width="100%" >
										<?php  if (($section_languages == 1) && ($section_sub_language == 1)) { ?>
										<tr>
											<td width="100" colspan="2" align="center" class="rs_sectionheadline">
												<?php echo JText::_('JS_LANGUAGE1'); ?>
											</td>
										</tr>
										<?php } ?>
							
					<?php break;
						case "language_name": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_NAME'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->language;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "language_reading": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_READ'); ?></td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->language_reading;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "language_writing": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_WRITE'); ?></td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->language_writing;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "language_understading": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_UNDERSTAND'); ?></td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->language_understanding;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "language_where_learned": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_LEARN_INSTITUTE'); ?></td>
								<td>
									 <?php if (isset($this->resume)) echo $this->resume->language_where_learned;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
					case "section_sub_language1": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language1 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td width="100" colspan="2" align="center" class="rs_sectionheadline">
									<?php echo JText::_('JS_LANGUAGE2'); ?>
								</td>
							</tr>
							<?php } ?>

					<?php break;
						case "language1_name": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language1 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td width="150" align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_NAME'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->language1;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "language1_reading": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language1 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_READ'); ?></td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->language1_reading;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "language1_writing": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language1 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_WRITE'); ?></td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->language1_writing;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "language1_understading": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language1 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_UNDERSTAND'); ?></td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->language1_understanding;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "language1_where_learned": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language1 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_LEARN_INSTITUTE'); ?></td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->language1_where_learned;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
					case "section_sub_language2": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language2 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td width="100" colspan="2" align="center" class="rs_sectionheadline">
									<?php echo JText::_('JS_LANGUAGE3'); ?>
								</td>
							</tr>
							<?php } ?>

					<?php break;
						case "language2_name": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language2 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td width="150" align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_NAME'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->language2;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "language2_reading": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language2 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_READ'); ?></td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->language2_reading;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "language2_writing": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language2 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_WRITE'); ?></td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->language2_writing;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "language2_understading": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language2 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_UNDERSTAND'); ?></td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->language2_understanding;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "language2_where_learned": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language2 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_LEARN_INSTITUTE'); ?></td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->language2_where_learned;?>
								</td>
							</tr>
							<?php } 
					break;		
					case "section_sub_language3": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language3 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td width="100" colspan="2" align="center" class="rs_sectionheadline">
									<?php echo JText::_('JS_LANGUAGE4'); ?>
								</td>
							</tr>
							<?php } ?>

					<?php break;
						case "language3_name": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language3 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td width="150" align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_NAME'); ?>:</td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->language3;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "language3_reading": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language3 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_READ'); ?></td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->language3_reading;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "language3_writing": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language3 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_WRITE'); ?></td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->language3_writing;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "language3_understading": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language3 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_UNDERSTAND'); ?></td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->language3_understanding;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
						case "language3_where_learned": ?>
							<?php  if (($section_languages == 1) && ($section_sub_language3 == 1)) { ?>
							<tr id="mc_field_row" class="<?php echo $this->theme[$trclass[$isodd]]; ?>">
								<td  align="right" class="textfieldtitle"><?php echo JText::_('JS_LANGUAGE_LEARN_INSTITUTE'); ?></td>
								<td>
									<?php if (isset($this->resume)) echo $this->resume->language3_where_learned;?>
								</td>
							</tr>
							<?php } ?>
					<?php break;
					
				 } ?>	
			<?php } ?>	
							
						</table>
						</div>	
					</div>	
			<?php 
				if(isset($this->resume)) {
					if (($this->resume->create_date=='0000-00-00 00:00:00') || ($this->resume->create_date==''))
						$curdate = date('Y-m-d H:i:s');
					else  
						$curdate = $this->resume->create_date;
				}else
					$curdate = date('Y-m-d H:i:s');
				
			?>
<?php
	}else { ?>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<div id="message"><b><?php echo JText::_('JS_RESULT_NOT_FOUND'); ?></b></div>
	</div>
<?php
	
}	
} else{ // not allowed job posting ?>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<div id="message"><b><?php echo JText::_('JS_YOU_ARE_NOT_ALLOWED_TO_VIEW');?></b></div>
	</div>
<?php

}	

}//ol

?>
<div id="jsjobs_footer"><?php echo eval(base64_decode('aWYoJHRoaXMtPmNvbmZpZ1snZnJfY3JfdHhzaCddKSB7DQplY2hvIA0KJzx0YWJsZSB3aWR0aD0iMTAwJSIgc3R5bGU9InRhYmxlLWxheW91dDpmaXhlZDsiPg0KPHRyPjx0ZCBoZWlnaHQ9IjE1Ij48L3RkPjwvdHI+DQo8dHI+PHRkIHN0eWxlPSJ2ZXJ0aWNhbC1hbGlnbjp0b3A7IiBhbGlnbj0iY2VudGVyIj4NCjxhIGNsYXNzPSJpbWciIHRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIj48aW1nIHNyYz0iaHR0cDovL3d3dy5qb29tc2t5LmNvbS9sb2dvL2pzam9ic2NybG9nby5wbmciPjwvYT4NCjxicj4NCkNvcHlyaWdodCAmY29weTsgMjAwOCAtICcuZGF0ZSgnWScpLicsDQo8c3BhbiBpZD0idGhlbWVhbmNob3IiPiA8YSBjbGFzcz0iYW5jaG9yInRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSI+QnVydWogU29sdXRpb25zIDwvYT48L3NwYW4+PC90ZD48L3RyPg0KPC90YWJsZT4nOw0KfQ=='));?></div>
<script language=Javascript>

	function hideshowtables(table_id){
			hideall();
			document.getElementById(table_id).style.display = "block";

	}
	function hideall(){
		document.getElementById('personal_info_data').style.display = "none";
		document.getElementById('addresses_data').style.display = "none";
		document.getElementById('education_data').style.display = "none";
		document.getElementById('employer_data').style.display = "none";
		document.getElementById('skills_data').style.display = "none";
		document.getElementById('resume_editor_data').style.display = "none";
		document.getElementById('references_data').style.display = "none";
		document.getElementById('languages_data').style.display = "none";
	}
//window.onLoad=dochange('country', -1);         // value in first dropdown
<?php if($resume_style == 'sliding'){ ?>
	hideshowtables('personal_info_data');
<?php } ?>

</script>
