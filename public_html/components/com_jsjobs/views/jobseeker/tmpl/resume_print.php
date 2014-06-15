<?php
/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 + Created by:          Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jan 11, 2010
 ^
 + Project: 		JS Jobs
 * File Name:	views/employer/view.html.php
 ^ 
 * Description: HTML view class for employer
 ^ 
 * History:		NONE
 ^ 
 */

defined('_JEXEC') or die('Restricted access');
 global $mainframe;
JRequest :: setVar('layout', 'resume_print');
$_SESSION['cur_layout']='resume_print';




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

if (!isset($this->fieldsordering)) echo 'Please refresh the page';
if (isset($this->fieldsordering)){
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
            }
    }
}
?>

<table cellpadding="5" cellspacing="0" border="0" width="100%" >

			<tr><td>
			<?php
                        if (isset($this->fieldsordering))
			foreach($this->fieldsordering as $field){ 
			switch ($field->field) {
			
			case "section_personal": ?>
			</table></td></tr><tr><td>
				<table  cellspacing="0" border="0" width="100%" >
				<tr>
				<td colspan="2" align="left">									
				<u><h2><?php echo JText::_('JS_PERSONAL'); ?></h2></u>
				</td>
				</tr>
				</table></td></tr><tr><td>
				<table cellpadding="5" cellspacing="0" border="0" width="100%" >
									<tr>
										<td colspan="2" align="left">
											<h3><?php echo JText::_('JS_PERSONAL_INFORMATION'); ?></h3>
										</td>
									</tr>
						<tr><td height="3" colspan="2"></td></tr>
						<?php break;
						case "applicationtitle":  ?>
							<tr>
							<td class="maintext"><b><?php echo JText::_('JS_APPLICATION_TITLE'); ?></b></td>
							<td class="maintext"><?php echo $this->resume->application_title; ?></td>
							</tr>
						<?php break;
						case "firstname": ?>
						<tr>
							 <td class="maintext"><b><?php echo JText::_('JS_FIRST_NAME'); ?></b></td>
							<td  class="maintext"><?php echo $this->resume->first_name; ?></td>
							</tr>
						<?php break;
						case "middlename": ?>
							<tr>
							<td class="maintext"><b><?php echo JText::_('JS_MIDDLE_NAME'); ?></b></td>
							<td  class="maintext"><?php echo $this->resume->middle_name; ?></td>
							</tr>
						<?php break;
						case "lastname": ?>
							<tr>
							<td class="maintext"><b><?php echo JText::_('JS_LAST_NAME'); ?></b></td>
							<td class="maintext"><?php echo $this->resume->last_name; ?></td>
							</tr>
						<?php break;
						case "emailaddress": ?>
							<tr>
							<td class="maintext"><b><?php echo JText::_('JS_EMAIL_ADDRESS'); ?></b></td>
							<td class="maintext"><?php echo $this->resume->email_address; ?></td>
													
							</tr>
						<?php break;
						case "homephone": ?>
							<tr>
							<td class="maintext"><b><?php echo JText::_('JS_HOME_PHONE'); ?></b></td>
							<td class="maintext"><?php echo $this->resume->home_phone; ?></td>
							</tr>
						<?php break;
						case "workphone":  ?>
							<tr>
							<td class="maintext"><b><?php echo JText::_('JS_WORK_PHONE'); ?></b></td>
							<td class="maintext"><?php echo $this->resume->work_phone; ?></td>
							</tr>
						<?php break;
						case "cell":?>
							<tr>
							<td class="maintext"><b><?php echo JText::_('JS_CELL'); ?></b></td>
							<td class="maintext"><?php echo $this->resume->cell; ?></td>
							</tr>
						<?php break;
						case "gender":  ?>
							<tr>
							<td class="maintext"><b><?php echo JText::_('JS_GENDER'); ?></b></td>
							<td class="maintext"><?php if(isset($this->resume)) echo ($this->resume->gender == 1) ? JText::_('JS_MALE') : JText::_('JS_FEMALE'); ?>	</td>
							</tr>
						<?php break;
						case "Iamavailable": ?>
							<tr>
							<td class="maintext"><b><?php echo JText::_('JS_I_AM_AVAILABLE'); ?></b></td>
							<td class="maintext"><?php if(isset($this->resume)) echo ($this->resume->iamavailable == 1) ? JText::_('JS_A_YES') : JText::_('JS_A_NO'); ?> </td>
							</tr>
						<?php break;
						case "nationality": ?>
						<tr>
							<td class="maintext"><b><?php echo JText::_('JS_NATIONALITY_COUNTRY'); ?></b></td>
							<td class="maintext"><?php echo $this->resume->nationalitycountry; ?></td>
							</tr>
						<?php break;
						case "photo":  ?>
								<?php if (isset($this->resume)) 
											if($this->resume->photo != '') {?>
												<tr><td class="maintext"><?php echo JText::_('JS_PHOTO'); ?></td><td style="max-width:150px;max-height:150px;overflow:hidden;text-overflow:ellipsis">
													<img src="<?php echo $this->config['data_directory'];?>/data/jobseeker/resume_<?php echo $this->resume->id.'/photo/'.$this->resume->photo; ?>"  />
												</td></tr>
								<?php } ?>				
						<?php break;
						case "section_basic":?>
						<tr height="21"><td colspan="2"></td></tr>
						
						
						
						</table></td></tr><tr><td>
						<table cellpadding="5" cellspacing="0" border="0" width="100%" >
							<tr>
										<td colspan="2" align="left">									
							<h3><?php echo JText::_('JS_BASIC_INFORMATION'); ?></h3>
							  </td>
							</tr>
						<?php break; 
						case "category":  ?>
						<tr>
							<td class="maintext"><b><?php echo JText::_('JS_CATEGORY'); ?></b></td>
							<td class="maintext"><?php echo $this->resume->categorytitle; ?></td>
							</tr>
						<?php break;
						case "salary":  ?>
						<tr>
							<td class="maintext"><b><?php echo JText::_('JS_DESIRED_SALARY'); ?></b></td>
							<td class="maintext"><?php echo $this->config['currency'].$this->resume->rangestart.' - '.$this->config['currency'].$this->resume->rangeend .' '. JText::_('JS_PERMONTH'); ?></td>
						</tr>
						<?php break; 
						case "jobtype":  ?>
						<tr>
							<td class="maintext"><b><?php echo JText::_('JS_WORK_PREFERENCE'); ?></b></td>
							<td class="maintext"><?php echo $this->resume->jobtypetitle; ?></td>
						</tr>
						<?php break;
						case "heighesteducation":  ?>
						<tr>
							<td class="maintext"><b><?php echo JText::_('JS_HEIGHTEST_EDUCATION'); ?></b></td>
							&nbsp;<td class="maintext"><?php echo $this->resume->heighesteducationtitle; ?></td>
						</tr>
						<?php break;
						case "totalexperience": ?>
						<tr>
							<td class="maintext"><b><?php echo JText::_('JS_TOTAL_EXPERIENCE'); ?></b></td>
							<td class="maintext"><?php echo $this->resume->total_experience; ?></td>
						</tr>
						<?php break;
						case "startdate": ?>
						<tr>
							<td class="maintext"><b><?php echo JText::_('JS_DATE_CAN_START'); ?></b></td>
							<td class="maintext"><?php echo date($this->config['date_format'],strtotime($this->resume->date_start)); ?></td>
						</tr>
					<?php break; 
				case "section_addresses": ?>
				<tr><td height="21" colspan="2"></td></tr>
				</table></td></tr><tr><td>
				<table  cellspacing="0" border="0" width="100%" >
				<tr>
				<td colspan="2" align="left">									
				<u><h2><?php echo JText::_('JS_ADDRESS'); ?></h2></u>
				</td>
				</tr>
				</table></td></tr><tr><td>
				<table cellpadding="5" cellspacing="0" border="0" width="100%" >
				<tr>
				<td colspan="2" align="left">									
				<h3><?php echo JText::_('JS_ADDRESS1'); ?></h3>
				</td>
				</tr>
				<tr><td height="3" colspan="2"></td></tr>
				<?php break; 
				case "address_city":  ?>
				<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_CITY'); ?></b></td>
				<td class="maintext"><?php if ($this->resume->address_city2 !='') echo $this->resume->address_city2; else echo $this->resume->address_city;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "address_county":  ?>
				<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_COUNTY'); ?></b></td>
				<td class="maintext"><?php if ($this->resume->address_county2 !='')
				 echo $this->resume->address_county2; else echo $this->resume->address_county;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "address_state":  ?>
				<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_STATE'); ?></b></td>
				<td class="maintext"><?php if ($this->resume->address_state2 !='') echo $this->resume->address_state2; else echo $this->resume->address_state;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "address_country":  ?>
					<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
					<tr><td class="maintext"><b><?php echo JText::_('JS_COUNTRY'); ?></b></td>
					<td class="maintext"><?php echo $this->resume->address_country; ?></td>
					</tr>
				<?php } ?>
				<?php break;
				case "address_address":  ?>
				<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_ADDRESS'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->address;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "address_zipcode":  ?>
				<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_ZIPCODE'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->address_zipcode;?></td>
				</tr>
				<?php } ?>
				<?php break; 
				case "section_sub_address1":  ?>
				<tr><td height="21" colspan="2"></td></tr>
				</table></td></tr><tr><td>
				<table cellpadding="5" cellspacing="0" border="0" width="100%" >
				<tr>
				<td colspan="2" align="left">	
				<br>
				<br>
				<h3><?php echo JText::_('JS_ADDRESS2'); ?></h3>
				</td>
				</tr>
				<tr><td height="3" colspan="2"></td></tr>
				<?php break; 
				case "address1_city":  ?>
				<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_CITY'); ?></b></td>
				<td class="maintext"><?php if ($this->resume->address1_city2 !='') echo $this->resume->address1_city2; else echo $this->resume->address1_city;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "address1_county":  ?>
				<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>

				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_COUNTY'); ?></b></td>
				<td class="maintext"><?php if ($this->resume->address1_county2 !='') 
				echo $this->resume->address1_county2; else echo $this->resume->address1_county;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "address1_state":  ?>
				<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_STATE'); ?></b></td>
				<td class="maintext"><?php if ($this->resume->address1_state2 !='') echo $this->resume->address1_state2; else echo $this->resume->address1_state;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "address1_country":  ?>
					<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
					<tr>
					<td class="maintext"><b><?php echo JText::_('JS_COUNTRY'); ?></b></td>
					<td class="maintext"><?php echo $this->resume->address1_country; ?></td>
					</tr>
				<?php } ?>
				<?php break;
				case "address1_address":  ?>
				<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_ADDRESS'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->address1;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "address1_zipcode":  ?>
				<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_ZIPCODE'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->address1_zipcode;?></td>
				</tr>
				<?php } ?>
				<?php break; 
				case "section_sub_address2":  ?>
				<tr height="21"><td colspan="2"></td></tr>
				</table></td></tr><tr><td>
				<table cellpadding="5" cellspacing="0" border="0" width="100%" >
				<tr>
				<td colspan="2" align="left">									
				<h3><?php echo JText::_('JS_ADDRESS3'); ?></h3>
				</td>
				</tr>
				<tr><td height="3" colspan="2"></td></tr>
				<?php break; 
				case "address2_city":  ?>
				<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_CITY'); ?></b></td>
				<td class="maintext"><?php if ($this->resume->address2_city2 !='') echo $this->resume->address2_city2; else echo $this->resume->address2_city;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "address2_county":  ?>
				<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_COUNTY'); ?></b></td>
				<td class="maintext"><?php if ($this->resume->address2_county2 !='') 
				echo $this->resume->address2_county2; else echo $this->resume->address2_county;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "address2_state":  ?>
				<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_STATE'); ?></b></td>
				<td class="maintext"><?php if ($this->resume->address2_state2 !='') echo $this->resume->address2_state2; else echo $this->resume->address2_state;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "address2_country":  ?>
					<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
					<tr>
					<td class="maintext"><b><?php echo JText::_('JS_COUNTRY'); ?></b></td>
					<td class="maintext"><?php echo $this->resume->address2_country; ?></td>
					</tr>
				<?php } ?>
				<?php break;
				case "address2_address":  ?>
				<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_ADDRESS'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->address2;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "address2_zipcode":  ?>
				<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_ZIPCODE'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->address2_zipcode;?></td>
				</tr>
				<?php } ?>
				<?php break; 
				case "section_education": ?>
			 	 <tr height="21"><td colspan="2"></td></tr>
				</table></td></tr><tr><td>
				<table cellpadding="5" cellspacing="0" border="0" width="100%" >
				<tr>
				<td colspan="2" align="left">									
				<U><h2><?php echo JText::_('JS_EDUCATIONS'); ?></h2></U>
				</td>
				</tr>
				</table></td></tr><tr><td>
				<table cellpadding="5" cellspacing="0" border="0" width="100%" >
				<tr>
				<td colspan="2" align="left">									
				<h3><?php echo JText::_('JS_HIGH_SCHOOL'); ?></h3>
				</td>
				</tr>
				<?php break; 
				case "institute_institute":?>
				<?php  if (($section_education == 1) && ($section_sub_institute == 1)) { ?>
							<tr><td class="maintext"><b><?php echo JText::_('JS_SCH_COL_UNI'); ?></b></td>
							<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->institute;?></td>
							</tr>
				<?php } ?>
				<?php break;
				case "institute_certificate":?>
				<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
			<tr><td class="maintext"><b><?php echo JText::_('JS_CRT_DEG_OTH'); ?></b></td>
			<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->institute_study_area;?></td>
			</tr>
				<?php } ?>
				<?php break;
				case "institute_study_area":   ?>
				<?php  if (($section_education == 1) && ($section_sub_institute == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_AREA_OF_STUDY'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->institute_study_area;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "institute_country":?>
				<?php  if (($section_education == 1) && ($section_sub_institute == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_COUNTRY'); ?></b></td>
				<td class="maintext"><?php echo $this->resume2->institute_country; ?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "institute_state": ?>
				<?php  if (($section_education == 1) && ($section_sub_institute == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_STATE'); ?></b></td>
				<td class="maintext"><?php if ($this->resume2->institute_state2 !='') 
				echo $this->resume2->institute_state2; else echo $this->resume->institute_state;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "institute_county":  ?>
				<?php  if (($section_education == 1) && ($section_sub_institute == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_COUNTY'); ?></b></td>
				<td class="maintext"><?php if ($this->resume2->institute_county2 !='') 
				echo $this->resume2->institute_county2; else echo $this->resume->institute_county;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "institute_city": ?>
				<?php  if (($section_education == 1) && ($section_sub_institute == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_CITY'); ?></b></td>
				<td class="maintext"><?php if ($this->resume2->institute_city2 !='') 
				echo $this->resume2->institute_city2; else echo $this->resume->institute_city;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "section_sub_institute1":  ?>
				<tr height="21"><td colspan="2"></td></tr>
				</table></td></tr><tr><td>
				<table cellpadding="5" cellspacing="0" border="0" width="100%" >
				<tr>
				<td colspan="2" align="left">									
				<h3><?php echo JText::_('JS_UNIVERSITY'); ?></h3>
				</td>
				</tr>
				<tr><td height="3" colspan="2"></td></tr>
				<?php break; 
				case "institute1_institute":?>
				<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
						<tr>
						<td class="maintext"><b><?php echo JText::_('JS_SCH_COL_UNI'); ?></b></td>
						<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->institute1;?></td>
						</tr>
				<?php } ?>
				<?php break;
				case "institute1_certificate":  ?>
				<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
			<tr><td class="maintext"><b><?php echo JText::_('JS_CRT_DEG_OTH'); ?></b></td>
			<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->institute1_certificate_name;?></td>
			</tr>
				<?php } ?>
				<?php break;
				case "institute1_study_area":?>
				<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_AREA_OF_STUDY'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->institute1_study_area;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "institute1_country":  ?>
				<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_COUNTRY'); ?></b></td>
				<td class="maintext" id="institute1_country"><?php echo $this->resume2->institute1_country; ?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "institute1_state": ?>
				<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_STATE'); ?></b></td>
				<td class="maintext" id="institute1_country"><?php if ($this->resume2->institute1_state2 !='')
				 echo $this->resume2->institute1_state2; else echo $this->resume->institute1_state;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "institute1_county":   ?>
				<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_COUNTY'); ?></b></td>
				<td class="maintext" id="institute1_county"><?php if ($this->resume2->institute1_county2 !='') 
				echo $this->resume2->institute1_county2; else echo $this->resume->institute1_county;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "institute1_city":?>
				<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_CITY'); ?></b></td>
				<td class="maintext" id="institute1_county"><?php if ($this->resume2->institute1_city2 !='') 
				echo $this->resume2->institute1_city2; else echo $this->resume->institute1_city;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "section_sub_institute2": ?>
							<tr height="21"><td colspan="2"></td></tr>
				</table></td></tr><tr><td>
				<table cellpadding="5" cellspacing="0" border="0" width="100%" >
				<tr>
				<td colspan="2" align="left">									
				<h3><?php echo JText::_('JS_GRADE_SCHOOL'); ?></h3>
				</td>
				</tr>
				<?php break; 
				case "institute2_institute":?>
				<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
						<tr>
						<td class="maintext"><b><?php echo JText::_('JS_SCH_COL_UNI'); ?></b></td>
						<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->institute2;?></td>
						</tr>
				<?php } ?>
				<?php break;
				case "institute2_certificate":  ?>
				<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
			<tr><td class="maintext"><b><?php echo JText::_('JS_CRT_DEG_OTH'); ?></b></td>
			<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->institute2_certificate_name;?></td>
			</tr>
				<?php } ?>
				<?php break;
				case "institute2_study_area":?>
				<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_AREA_OF_STUDY'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->institute2_study_area;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "institute2_country":  ?>
				<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_COUNTRY'); ?></b></td>
				<td class="maintext" id="institute2_country"><?php echo $this->resume2->institute2_country; ?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "institute2_state": ?>
				<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_STATE'); ?></b></td>
				<td class="maintext" id="institute2_state"><?php if ($this->resume2->institute2_state2 !='') 
				echo $this->resume2->institute2_state2; else echo $this->resume->institute2_state;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "institute2_county":   ?>
				<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_COUNTY'); ?></b></td>
				<td class="maintext" id="institute2_county"><?php if ($this->resume2->institute2_county2 !='') 
				echo $this->resume2->institute2_county2; else echo $this->resume->institute2_county;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "institute2_city":?>
				<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_CITY'); ?></b></td>
				<td class="maintext" id="institute2_city"><?php if ($this->resume2->institute2_city2 !='') 
				echo $this->resume2->institute2_city2; else echo $this->resume->institute2_city;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "section_sub_institute3": ?>
				<tr height="25"><td colspan="2"></td></tr>
				</table></td></tr><tr><td>
				<table cellpadding="5" cellspacing="0" border="0" width="100%" >
				<tr>
				<td colspan="2" align="left">									
				<h3><?php echo JText::_('JS_OTHER_SCHOOL'); ?></h3>
				</td>
				</tr>
				<tr><td height="3" colspan="2"></td></tr>
				<?php break; 
				case "institute3_institute":?>
				<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
						<tr>
						<td class="maintext"><b><?php echo JText::_('JS_SCH_COL_UNI'); ?></b></td>
						<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->institute3;?></td>
						</tr>
				<?php } ?>
				<?php break;
				case "institute3_certificate":  ?>
				<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
			<tr><td class="maintext"><b><?php echo JText::_('JS_CRT_DEG_OTH'); ?></b></td>
			<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->institute3_certificate_name;?></td>
			</tr>
				<?php } ?>
				<?php break;
				case "institute3_study_area":?>
				<?php  if (($section_addresses == 1) && ($section_sub_address == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_AREA_OF_STUDY'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->institute3_study_area;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "institute3_country":  ?>
				<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_COUNTRY'); ?></b></td>
				<td class="maintext" id="institute3_country"><?php echo $this->resume->institute3_country; ?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "institute3_state": ?>
				<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_STATE'); ?></b></td>
				<td class="maintext" id="institute3_state"><?php if ($this->resume->institute3_state2 !='') 
				echo $this->resume->institute3_state2; else echo $this->resume->institute3_state;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "institute3_county":   ?>
				<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_COUNTY'); ?></b></td>
				<td class="maintext" id="institute3_county"><?php if ($this->resume->institute3_county2 !='') 
				echo $this->resume->institute3_county2; else echo $this->resume->institute3_county;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "institute3_city":?>
				<?php  if (($section_education == 1) && ($section_sub_institute1 == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_CITY'); ?></b></td>
				<td class="maintext" id="institute3_city"><?php if ($this->resume->institute3_city2 !='') 
				echo $this->resume->institute3_city2; else echo $this->resume->institute3_city;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "section_employer": ?>
							<tr height="21"><td colspan="2"></td></tr>
				</table></td></tr><tr><td>
				<table cellpadding="5" cellspacing="0" border="0" width="100%" >
				<tr>
				<td colspan="2" align="left">									
				<u><h2><?php echo JText::_('JS_EMPLOYERS'); ?></h2></u>
				</td>
				</tr>
				</table></td></tr><tr><td>
				<table cellpadding="5" cellspacing="0" border="0" width="100%" >
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td colspan="2" align="left">									
				<h3><?php echo JText::_('JS_RECENT_EMPLOYER'); ?></h3>
				</td>
				</tr>
				<?php } ?>
				<tr><td height="3" colspan="2"></td></tr>
				<?php break; 
				case "employer_employer":  ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
							<tr><td class="maintext"><b><?php echo JText::_('JS_EMPLOYER'); ?></b></td>
							<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer;?></td>
							</tr>
				<?php } ?>
				<?php break;
				case "employer_position":?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_POSITION'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer_position;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer_resp": ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_RESPONSIBILITIES'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer_resp;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer_pay_upon_leaving":  ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_PAY_UPON_LEAVING'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer_pay_upon_leaving;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer_supervisor": ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_SUPERVISOR'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer_supervisor;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer_from_date":  ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_FROM_DATE'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer_from_date;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer_to_date":  ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_TO_DATE'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer_to_date;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer_leave_reason":   ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_LEAVING_REASON'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer_leave_reason;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer_country":   ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_COUNTRY'); ?></b></td>
				<td class="maintext"><?php echo $this->resume2->employer_country; ?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer_state":   ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_STATE'); ?></b></td>
				<td class="maintext"><?php if ($this->resume2->employer_state2 !='') 
				echo $this->resume2->employer_state2; else echo $this->resume->employer_state;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer_county":   ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_COUNTY'); ?></b></td>
				<td class="maintext"><?php if ($this->resume2->employer_county2 !='')
				 echo $this->resume2->employer_county; else echo $this->resume->employer_state;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer_city":  ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_CITY'); ?></b></td>
				<td class="maintext"><?php if ($this->resume2->employer_city2 !='') 
				echo $this->resume2->employer_city2; else echo $this->resume->employer_state;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer_zip":   ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_ZIPCODE'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer_zip;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer_address":  ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_ADDRESS'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer_address;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer_phone":   ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_PHONE'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer_phone;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "section_sub_employer1": ?>
				<tr height="21"><td colspan="2"></td></tr>
				</table></td></tr><tr><td>
				<table cellpadding="5" cellspacing="0" border="0" width="100%" >
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td colspan="2" align="left">									
				<h3><?php echo JText::_('JS_PRIOR_EMPLOYER_1'); ?></h3>
				</td>
				</tr>
				<?php } ?>
				<?php break; 
				case "employer1_employer":  ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_EMPLOYER'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer1;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer1_position":?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_POSITION'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer1_position;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer1_resp": ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_RESPONSIBILITIES'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer1_resp;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer1_pay_upon_leaving":  ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_PAY_UPON_LEAVING'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer1_pay_upon_leaving;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer1_supervisor": ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_SUPERVISOR'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer1_supervisor;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer1_from_date":  ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_FROM_DATE'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer1_from_date;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer1_to_date":  ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_TO_DATE'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer1_to_date;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer1_leave_reason":   ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_LEAVING_REASON'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer1_leave_reason;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer1_country":   ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_COUNTRY'); ?></b></td>
				<td class="maintext"><?php echo $this->resume2->employer1_country; ?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer1_state":   ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_STATE'); ?></b></td>
				<td class="maintext"><?php if ($this->resume2->employer1_state2 !='') 
				echo $this->resume2->employer1_state2; else echo $this->resume->employer1_state;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer1_county":   ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_COUNTY'); ?></b></td>
				<td class="maintext"><?php if ($this->resume2->employer1_county2 !='') 
				echo $this->resume2->employer1_county2; else echo $this->resume->employer1_county;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer1_city":  ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_CITY'); ?></b></td>
				<td class="maintext"><?php if ($this->resume2->employer1_city2 !='') 
				echo $this->resume2->employer1_city2; else echo $this->resume->employer1_city;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer1_zip":   ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_ZIPCODE'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer1_zip;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer1_address":  ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_ADDRESS'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer1_address;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer1_phone":   ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_PHONE'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer1_phone;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "section_sub_employer2": ?>
							<tr height="21"><td colspan="2"></td></tr>
				</table></td></tr><tr><td>
				<table cellpadding="5" cellspacing="0" border="0" width="100%" >
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td colspan="2" align="left">									
				<h3><?php echo JText::_('JS_PRIOR_EMPLOYER_2'); ?></h3>
				</td>
				</tr>
				<?php } ?>
				<?php break; 
				case "employer2_employer":  ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_EMPLOYER'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer2;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer2_position":?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_POSITION'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer2_position;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer2_resp": ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_RESPONSIBILITIES'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer2_resp;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer2_pay_upon_leaving":  ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_PAY_UPON_LEAVING'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer2_pay_upon_leaving;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer2_supervisor": ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_SUPERVISOR'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer2_supervisor;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer2_from_date":  ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_FROM_DATE'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer2_from_date;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer2_to_date":  ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_TO_DATE'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer2_to_date;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer2_leave_reason":   ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_LEAVING_REASON'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer2_leave_reason;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer2_country":   ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_COUNTRY'); ?></b></td>
				<td class="maintext"><?php echo $this->resume2->employer2_country; ?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer2_state":   ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_STATE'); ?></b></td>
				<td class="maintext"><?php if ($this->resume2->employer2_state2 !='') 
				echo $this->resume2->employer2_state2; else echo $this->resume->employer2_state;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer2_county":   ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_COUNTY'); ?></b></td>
				<td class="maintext"><?php if ($this->resume2->employer2_county2 !='') 
				echo $this->resume2->employer2_county2; else echo $this->resume->employer2_county;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer2_city":  ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_CITY'); ?></b></td>
				<td class="maintext"><?php if ($this->resume2->employer2_city2 !='') 
				echo $this->resume2->employer2_city2; else echo $this->resume->employer2_city;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer2_zip":   ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_ZIPCODE'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer2_zip;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer2_address":  ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_ADDRESS'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer2_address;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer2_phone":   ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_PHONE'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer2_phone;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "section_sub_employer3": ?>
							<tr height="21"><td colspan="2"></td></tr>
				</table></td></tr><tr><td>
				<table cellpadding="5" cellspacing="0" border="0" width="100%" >
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td colspan="2" align="left">
				<br><br><br><br><br><br>									
				<h3><?php echo JText::_('JS_PRIOR_EMPLOYER_3'); ?></h3>
				</td>
				</tr>
				<?php } ?>
				<?php break; 
				case "employer3_employer":  ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_EMPLOYER'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer3;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer3_position":?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_POSITION'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer3_position;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer3_resp": ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_RESPONSIBILITIES'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer3_resp;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer3_pay_upon_leaving":  ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_PAY_UPON_LEAVING'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer3_pay_upon_leaving;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer3_supervisor": ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_SUPERVISOR'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer3_supervisor;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer3_from_date":  ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_FROM_DATE'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer3_from_date;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer3_to_date":  ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_TO_DATE'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer3_to_date;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer3_leave_reason":   ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_LEAVING_REASON'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer3_leave_reason;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer3_country":   ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_COUNTRY'); ?></b></td>
				<td class="maintext"><?php echo $this->resume2->employer3_country; ?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer3_state":   ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_STATE'); ?></b></td>
				<td class="maintext"><?php if ($this->resume2->employer3_state2 !='') 
				echo $this->resume2->employer3_state2; else echo $this->resume->employer3_state;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer3_county":   ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_COUNTY'); ?></b></td>
				<td class="maintext"><?php if ($this->resume2->employer3_county2 !='')
				 echo $this->resume2->employer3_county2; else echo $this->resume->employer3_county;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer3_city":  ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_CITY'); ?></b></td>
				<td class="maintext"><?php if ($this->resume2->employer3_city2 !='') 
				echo $this->resume2->employer3_city2; else echo $this->resume->employer3_city;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer3_zip":   ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_ZIPCODE'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer3_zip;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer3_address":  ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_ADDRESS'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer3_address;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "employer3_phone":   ?>
				<?php  if (($section_employer == 1) && ($section_sub_employer == 1)) { ?>
				<tr>
				<td class="maintext"><b><?php echo JText::_('JS_PHONE'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->employer3_phone;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "section_skills": ?>
				<tr height="21"><td colspan="2"></td></tr>
				</table></td></tr><tr><td>
				<table cellpadding="5" cellspacing="0" border="0" width="100%" >
				<tr>
				<td colspan="2" align="left">									
				<u><h2><?php echo JText::_('JS_SKILLS'); ?></h2></u>
				</td>
				</tr>
				<?php break;
				case "driving_license": ?>
				<?php  if ($section_skills == 1) { ?>
						<tr>
						<td width="250" class="maintext"><b><?php echo JText::_('JS_HAVE_DRIVING_LICENSE'); ?></b></td>
				&nbsp;<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->driving_license; ?>
				</td>
				</tr>
				<?php } ?>
				<?php break;
				case "license_no":  ?>
				<?php  if ($section_skills == 1) { ?>
				<tr>
				<td  width="250" class="maintext"><b><?php echo JText::_('JS_YSE_LICENSE_NO'); ?></b>
				</td>
				<td><?php if (isset($this->resume)) echo $this->resume->license_no;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "license_country":   ?>
				<?php  if ($section_skills == 1) { ?>
				<tr>
				<td  width="250"class="maintext"><b><?php echo JText::_('JS_YSE_LICENSE_COUNTRY'); ?></b>
				</td>
				<td><?php if (isset($this->resume)) echo $this->resume->license_country;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "skills":  ?>
				<?php  if ($section_skills == 1) { ?>
				<tr>
				<td width="250" class="maintext"><b><?php echo JText::_('JS_SKILLS'); ?></b>
				</td>
				<td><?php if (isset($this->resume)) echo $this->resume->skills;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "section_resumeeditor": ?>
				<tr height="21"><td colspan="2"></td></tr>
				</table></td></tr><tr><td>
				<table cellpadding="5" cellspacing="0" border="0" width="100%" >
				<tr>
				<td colspan="2" align="left">									
				<u><h2><?php echo JText::_('JS_RESUME'); ?></h2></u>
				</td>
				</tr>
				<?php break;
				case "editor":  ?>
				<?php  if ($section_resumeeditor == 1) { ?>
				<tr>
				<td width="250" class="maintext"><b><?php echo JText::_('JS_RESUME'); ?></b>
				</td>
				<td><?php if (isset($this->resume)) echo $this->resume->resume;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "section_references": ?>
				<tr height="25"><td colspan="2"></td></tr>
				</table></td></tr><tr><td>
				<table cellpadding="5" cellspacing="0" border="0" width="100%" >
				<tr>
				<td colspan="2" align="left">									
				<u><h2><?php echo JText::_('JS_REFERENCES'); ?></h2></u>
				</td>
				</tr>
				</table></td></tr><tr><td>
				<table cellpadding="5" cellspacing="0" border="0" width="100%" >
				<tr>
				<td colspan="2" align="left">									
				<h3><?php echo JText::_('JS_REFERENCE1'); ?></h3>
				</td>
				</tr>
				<?php break; 
		case "reference_name":?>
		<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
		<tr><td class="maintext"><b><?php echo JText::_('JS_NAME'); ?></b></td>
		&nbsp;&nbsp;&nbsp;<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->reference_name;?></td>
		</tr>
				<?php } ?>
				<?php break;
				case "reference_country": ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_COUNTRY'); ?></b></td>
				<td class="maintext"><?php echo $this->resume3->reference_country; ?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference_state":  ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_STATE'); ?></b></td>
				<td class="maintext"><?php if ($this->resume3->reference_state2 !='') 
				echo $this->resume3->reference_state2; else echo $this->resume->reference_state;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference_county":   ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_COUNTY'); ?></b></td>
				<td class="maintext"><?php if ($this->resume3->reference_county2 !='') 
				echo $this->resume3->reference_county2; else echo $this->resume->reference_county;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference_city":   ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_CITY'); ?></b></td>
				<td class="maintext"><?php if ($this->resume3->reference_city2 !='') 
				echo $this->resume3->reference_city2; else echo $this->resume->reference_city;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference_zipcode":  ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_ZIPCODE'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->reference_zipcode;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference_address":   ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_ADDRESS'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->reference_address;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference_phone":   ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_PHONE'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->reference_phone;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference_relation":   ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_RELATION'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->reference_relation;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference_years":  ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_YEARS'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->reference_years;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "section_sub_reference1":?>
				<tr height="21"><td colspan="2"></td></tr>
				</table></td></tr><tr><td>
				<table cellpadding="5" cellspacing="0" border="0" width="100%" >
				<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
				<tr>
				<td colspan="2" align="left">				
				<br><br><br>					
				<h3><?php echo JText::_('JS_REFERENCE2'); ?></h3>
				</td>
				</tr>
				<tr height="3"><td colspan="2"></td></tr>
				<?php } ?>
				<?php break;
				case "reference1_name":   ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr><td  class="maintext"><b><?php echo JText::_('JS_NAME'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->reference1_name;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference1_country":  ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_COUNTRY'); ?></b></td>
				<td class="maintext"><?php echo $this->resume3->reference1_country; ?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference1_state":  ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_STATE'); ?></b></td>
				<td class="maintext"><?php if ($this->resume3->reference1_state2 !='') 
				echo $this->resume3->reference1_state2; else echo $this->resume->reference1_state;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference1_county":   ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_COUNTY'); ?></b></td>
				<td class="maintext"><?php if ($this->resume3->reference1_county2 !='') 
				echo $this->resume3->reference1_county2; else echo $this->resume->reference1_county;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference1_city":   ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_CITY'); ?></b></td>
				<td class="maintext"><?php if ($this->resume3->reference1_city2 !='') 
				echo $this->resume3->reference1_city2; else echo $this->resume->reference1_city;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference1_zipcode":   ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_ZIPCODE'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->reference1_zipcode;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference1_address":   ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_ADDRESS'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->reference1_address;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference1_phone":  ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_PHONE'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->reference1_phone;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference1_relation":   ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_RELATION'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->reference1_relation;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference1_years":  ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_YEARS'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->reference1_years;?></td>
				</tr>
				<br>
				<?php } ?>
				<?php break;
				case "section_sub_reference2": ?>
				<tr height="21"><td colspan="2"></td></tr>
				</table></td></tr><tr><td>
				<table cellpadding="5" cellspacing="0" border="0" width="100%" >
				<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
				<tr>
				<td colspan="2" align="left">
				<h3><?php echo JText::_('JS_REFERENCE3'); ?></h3>
				</td>
				</tr>
				<tr height="3"><td colspan="2"></td></tr>
				<?php } ?>
				<?php break;
				case "reference2_name":   ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr><td  class="maintext"><b><?php echo JText::_('JS_NAME'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->reference2_name;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference2_country":  ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_COUNTRY'); ?></b></td>
				<td class="maintext"><?php echo $this->resume3->reference1_country; ?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference2_state":  ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_STATE'); ?></b></td>
				<td class="maintext"><?php if ($this->resume3->reference1_state2 !='') 
				echo $this->resume3->reference1_state2; else echo $this->resume->reference1_state;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference2_county":   ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_COUNTY'); ?></b></td>
				<td class="maintext"><?php if ($this->resume3->reference1_county2 !='') 
				echo $this->resume3->reference1_county2; else echo $this->resume->reference1_county;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference2_city":   ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_CITY'); ?></b></td>
				<td class="maintext"><?php if ($this->resume3->reference1_city2 !='') 
				echo $this->resume3->reference1_city2; else echo $this->resume->reference1_city;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference2_zipcode":   ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_ZIPCODE'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->reference1_zipcode;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference2_address":   ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_ADDRESS'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->reference1_address;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference2_phone":  ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_PHONE'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->reference1_phone;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference2_relation":   ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_RELATION'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->reference1_relation;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference2_years":  ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_YEARS'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->reference1_years;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "section_sub_reference3":  ?>
							<tr height="21"><td colspan="2"></td></tr>
				</table></td></tr><tr><td>
				<table cellpadding="5" cellspacing="0" border="0" width="100%" >
				<?php  if (($section_references == 1) && ($section_sub_reference1 == 1)) { ?>
				<tr>
				<td colspan="2" align="left">									
				<h3><?php echo JText::_('JS_REFERENCE4'); ?></h3>
				</td>
				</tr>
				<tr height="3"><td colspan="2"></td></tr>
				<?php } ?>
				<?php break;
				case "reference3_name":   ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr><td  class="maintext"><b><?php echo JText::_('JS_NAME'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->reference3_name;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference3_country":  ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_COUNTRY'); ?></b></td>
				<td class="maintext"><?php echo $this->resume3->reference3_country; ?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference3_state":  ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_STATE'); ?></b></td>
				<td class="maintext"><?php if ($this->resume3->reference3_state2 !='') 
				echo $this->resume3->reference3_state2; else echo $this->resume->reference3_state;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference3_county":   ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_COUNTY'); ?></b></td>
				<td class="maintext"><?php if ($this->resume3->reference3_county2 !='')
				 echo $this->resume3->reference3_county2; else echo $this->resume->reference3_county;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference3_city":   ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_CITY'); ?></b></td>
				<td class="maintext"><?php if ($this->resume3->reference3_city2 !='') 
				echo $this->resume3->reference3_city2; else echo $this->resume->reference3_city;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference3_zipcode":   ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_ZIPCODE'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->reference3_zipcode;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference3_address":   ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_ADDRESS'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->reference3_address;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference3_phone":  ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_PHONE'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->reference3_phone;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference3_relation":   ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_RELATION'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->reference3_relation;?></td>
				</tr>
				<?php } ?>
				<?php break;
				case "reference3_years":  ?>
				<?php  if (($section_references == 1) && ($section_sub_reference == 1)) { ?>
				<tr>
				<td  class="maintext"><b><?php echo JText::_('JS_YEARS'); ?></b></td>
				<td class="maintext"><?php if (isset($this->resume)) echo $this->resume->reference3_years;?></td>
				</tr>
				<?php } ?>
</table>	
					<?php } ?>	
				<?php } ?>	
			</td></tr>
			</table>
			
			<script Language="Javascript">

			/*
			This script is written by Eric (Webcrawl@usa.net)
			For full source code, installation instructions,
			100's more DHTML scripts, and Terms Of
			Use, visit dynamicdrive.com
			*/

			function printit(){  
			if (window.print) {
			    window.print();  
			} else {
			    var WebBrowser = '<OBJECT ID="WebBrowser1" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>';
			document.body.insertAdjacentHTML('beforeEnd', WebBrowser);
			    WebBrowser1.ExecWB(6, 2);//Use a 1 vs. a 2 for a prompting dialog box    WebBrowser1.outerHTML = "";  
			}
			}
			printit();
			</script>
			