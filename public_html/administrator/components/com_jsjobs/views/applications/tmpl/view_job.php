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
defined('_JEXEC') or die('Restricted access');
 global $mainframe;
 JRequest :: setVar('layout', 'view_job');
$_SESSION['cur_layout']='view_job';
$document =& JFactory::getDocument();
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



?>
<style type="text/css">
div#map_container{
	width:100%;
	height:350px;
}
</style>


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
			<table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform" >
			<?php
		$trclass = array("row0", "row1");
		$i = 0;
		$isodd = 1;
		
		foreach($this->fieldsordering as $field){ 
			switch ($field->field) {
				case "jobtitle": $isodd = 1 - $isodd; ?>
				      <tr class="<?php echo $trclass[$isodd]; ?>"><td width="5%">&nbsp;</td>
				        <td width="30%"><b><?php echo JText::_('JS_TITLE'); ?></b></td>
						<td><?php echo $this->job->title; ?></td>
				      </tr>
				      <tr><td colspan="3" height="1"></td></tr>
				<?php break;
			 case "company": $isodd = 1 - $isodd; ?>
				      <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
				        <td><b><?php echo JText::_('JS_COMPANY'); ?></b></td>
						<td><?php echo $this->job->companyname; ?></td>
				      </tr>
				      <tr> <td colspan="3" height="1"></td> </tr>
				<?php break;
				case "department": $isodd = 1 - $isodd; ?>
				      	<tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
				        <td><b><?php echo JText::_('JS_DEPARTMENT'); ?></b></td>
						<td>
							<?php echo $this->job->departmentname; ?>
						</td>
				      </tr>
				      <tr> <td colspan="3" height="1"></td> </tr>
				<?php break;
				
				case "video": $isodd = 1 - $isodd; ?>
                                    <?php  if ($this->job->video == 1) { ?>
				      <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
				        <td><b><?php echo JText::_('JS_VIDEO'); ?></b></td>
						<td>
						<iframe title="YouTube video player" width="480" height="390" 
                                                        src="http://www.youtube.com/embed/<?php echo $this->job->video; ?>" frameborder="0" allowfullscreen>
                                                </iframe>

						</td>
				      </tr>
				      <tr> <td colspan="3" height="1"></td> </tr>
					  <?php } ?>
				<?php  break;
				case "map": $isodd = 1 - $isodd; ?>
				<?php  //if ($this->job->map == 1) { ?>
				      <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
						<td colspan="2">
						<div id="map"><div id="map_container"></div></div>
						</td>
				      </tr>
						<input type="hidden" id="longitude" name="longitude" value="<?php if(isset($this->job)) echo $this->job->longitude;?>"/>
						<input type="hidden" id="latitude" name="latitude" value="<?php if(isset($this->job)) echo $this->job->latitude;?>"/>
				      <tr> <td colspan="3" height="1"></td> </tr>
					<?php //} ?>

				<?php break;
				case "jobcategory": $isodd = 1 - $isodd; ?>
					 <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
						<td><b><?php echo JText::_('JS_CATEGORY'); ?></b></td>
						<td><?php echo $this->job->cat_title; ?></td>
					  </tr>
					  <tr><td colspan="3" height="1"></td></tr>
				<?php break;
				case "jobtype": $isodd = 1 - $isodd; ?>
				     <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
				        <td><b><?php echo JText::_('JS_JOBTYPE'); ?></b></td>
						<td><?php echo $this->job->jobtypetitle; ?></td>
				      </tr>
				      <tr><td colspan="3" height="1"></td> </tr>
				<?php break;
				case "jobstatus": $isodd = 1 - $isodd; ?>
				      <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
				        <td><b><?php echo JText::_('JS_JOBSTATUS'); ?></b></td>
						<td><?php echo $this->job->jobstatustitle; ?></td>
				      </tr>
				      <tr><td colspan="3" height="1"></td> </tr>
				<?php break;
				case "jobshift": $isodd = 1 - $isodd; ?>
			      <?php if ( $field->published == 1 ) { ?>
				     <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
				        <td><b><?php echo JText::_('JS_SHIFT'); ?></b></td>
						<td><?php echo $this->job->shifttitle; ?></td>
				      </tr>
				      <tr><td colspan="3" height="1"></td> </tr>
				  <?php } ?>
				<?php break;
				case "jobsalaryrange":  ?>
			      <?php if ( $field->published == 1 ) { ?>
					<?php if ( $this->job->hidesalaryrange != 1 ) { // show salary 
					$isodd = 1 - $isodd; ?>
				      <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
				        <td><b><?php echo JText::_('JS_SALARYRANGE'); ?></b></td>
						<td><?php
							if ($this->job->salaryfrom) echo JText::_('JS_S_FROM') .' '.$this->job->symbol. $this->job->salaryfrom;
							if ($this->job->salaryto) echo ' - ' . JText::_('JS_S_TO'). ' '.$this->job->symbol. $this->job->salaryto;;
							if ($this->job->salarytype) echo ' ' . $this->job->salarytype;;
							//echo $salaryrange; ?></td>
				      </tr>
				      <tr><td colspan="3" height="1"></td></tr>
					<?php } ?>
				  <?php } ?>
				<?php break;
				case "heighesteducation": $isodd = 1 - $isodd; ?>
			      <?php if ( $field->published == 1 ) { ?>
				      <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
				        <?php
						if($this->job->iseducationminimax == 1){
							if($this->job->educationminimax == 1) $title = JText::_('JS_MINIMUM_EDEDUCATION');
							else $title = JText::_('JS_MAXIMUM_EDEDUCATION');
							$educationtitle = $this->job->educationtitle;
						}else {
							$title = JText::_('JS_EDEDUCATION');
							$educationtitle = $this->job->mineducationtitle.' - '.$this->job->maxeducationtitle;
						}
					?>
					<td><b><?php echo $title; ?></b></td>
						<td><?php echo $educationtitle; ?></td>
				      </tr>
				      <tr><td colspan="3" height="1"></td> </tr>
				      <?php $isodd = 1 - $isodd; ?>
				      <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
					<td><b><?php echo JText::_('JS_DEGREE_TITLE'); ?></b></td>
						<td><?php echo $this->job->degreetitle; ?></td>
				      </tr>
				      <tr><td colspan="3" height="1"></td> </tr>
					<?php } ?>
				<?php break;
				case "noofjobs": $isodd = 1 - $isodd; ?>
				      <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
				        <td><b><?php echo JText::_('JS_NOOFJOBS'); ?></b></td>
						<td><?php echo $this->job->noofjobs; ?></td>
				      </tr>
				      <tr><td colspan="3" height="1"></td></tr>
				<?php break;
				case "experience": $isodd = 1 - $isodd; ?>
			      <?php if ( $field->published == 1 ) { ?>
				     <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
				        <?php
						if($this->job->isexperienceminimax == 1){
							if($this->job->experienceminimax == 1) $title = JText::_('JS_MINIMUM_EXPERIENCE');
							else $title = JText::_('JS_MAXIMUM_EXPERIENCE');
							$experiencetitle = $this->job->experiencetitle;
						}else {
							$title = JText::_('JS_EXPERIENCE');
							$experiencetitle = $this->job->minexperiencetitle.' - '.$this->job->maxexperiencetitle;
						}
						if($this->job->experiencetext) $experiencetitle .= ' ('.$this->job->experiencetext.')';
					?>
				        <td><b><?php echo $title; ?></b></td>
						<td><?php echo $experiencetitle; ?>
						</td>
					</tr>
				      <tr><td colspan="3" height="1"></td></tr>
				  <?php } ?>
				<?php break;
				case "duration": $isodd = 1 - $isodd; ?>
			      <?php if ( $field->published == 1 ) { ?>
				      <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
				        <td><b><?php echo JText::_('JS_DURATION'); ?></b></td>
						<td><?php echo $this->job->duration; ?>
					</td>
			      </tr>
				      <tr><td colspan="3" height="1"></td></tr>
				  <?php } ?>
				<?php break;
				case "startpublishing": $isodd = 1 - $isodd; ?>
				      <?php //if ($vj == '1'){ //my jobs ?> 
						  <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
					        <td><b><?php echo JText::_('JS_START_PUBLISHING'); ?></b></td>
							<td><?php echo date($this->config['date_format'],strtotime($this->job->startpublishing)); ?></td>
						  </tr>
					      <tr><td colspan="3" height="1"></td></tr>
					  <?php //} ?>
				<?php break;
				case "stoppublishing": $isodd = 1 - $isodd; ?>
				      <?php //if ($vj == '1'){ //my jobs ?> 
						    <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
						        <td><b><?php echo JText::_('JS_STOP_PUBLISHING'); ?></b></td>
								<td><?php echo  date($this->config['date_format'],strtotime($this->job->stoppublishing)); ?></td>
							</tr>
					      <tr><td colspan="3" height="1"></td></tr>
					  <?php //} ?>
				<?php break;
				case "agreement": $isodd = 1 - $isodd; ?>
				    <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
				        <td colspan="2"><b><?php echo JText::_('JS_AGREEMENT'); ?></b></td>
					</tr>
				    <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
						<td colspan="2"><?php echo $this->job->agreement; ?></td>
					</tr>
				      <tr><td colspan="3" height="1"></td></tr>
				<?php break;
				case "qualifications": $isodd = 1 - $isodd; ?>
				    <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
				        <td colspan="2"><b><?php echo JText::_('JS_QUALIFICATIONS'); ?></b></td>
					</tr>
				    <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
						<td colspan="2"><?php echo $this->job->qualifications; ?></td>
					</tr>
				      <tr><td colspan="3" height="1"></td></tr>
				<?php break;
				case "description": $isodd = 1 - $isodd; ?>
				    <tr  class="<?php echo $trclass[$isodd]; ?>"><td></td>
				        <td colspan="2"><b><?php echo JText::_('JS_DESCRIPTION'); ?></b></td>
					</tr>
				    <tr  class="<?php echo $trclass[$isodd]; ?>"><td></td>
						<td colspan="2"><?php echo $this->job->description; ?></td>
					</tr>
				      <tr><td colspan="3" height="1"></td></tr>
				<?php break;
				case "prefferdskills": $isodd = 1 - $isodd; ?>
				    <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
				        <td colspan="2"><b><?php echo JText::_('JS_PREFFERD_SKILLS'); ?></b></td>
					</tr>
				    <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
						<td colspan="2"><?php echo $this->job->prefferdskills; ?></td>
					</tr>
				      <tr><td colspan="3" height="1"></td></tr>
				<?php break;
				case "city": $isodd = 1 - $isodd; ?>
				   <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
				        <td><b><?php echo JText::_('JS_LOCATION'); ?></b></td>
						<td><?php if($this->job->multicity != '') echo $this->job->multicity;?></td>
					</tr>
				     <tr><td colspan="3" height="1"></td></tr>
				
				
					<?php } ?>	
				<?php } ?>	


		<?php 
		if(isset($this->userfields)){
			foreach($this->userfields as $ufield){ 
				if($ufield[0]->published == 1) {
					$isodd = 1 - $isodd; 
					$userfield = $ufield[0];
					echo '<tr class="'.$trclass[$isodd].'">';
					echo '<td></td>';
					echo '<td >'. $userfield->title .'</td>';
					if ($userfield->type == "checkbox"){
						if(isset($ufield[1])){ $fvalue = $ufield[1]->data; $userdataid = $ufield[1]->id;}  else {$fvalue=""; $userdataid = ""; }
						if ($fvalue == '1') $fvalue = "True"; else $fvalue = "false";
					}elseif ($userfield->type == "select"){
						if(isset($ufield[2])){ $fvalue = $ufield[2]->fieldtitle; $userdataid = $ufield[2]->id;} else {$fvalue=""; $userdataid = ""; }
					}else{
						if(isset($ufield[1])){ $fvalue = $ufield[1]->data; $userdataid = $ufield[1]->id;}  else {$fvalue=""; $userdataid = ""; }
					}
			//								echo '<td>'.$fvalue.'</td>';	
					echo '<td >'.$fvalue.'</td>';	
					echo '</tr>';
				}
			}	 
			
		}
		?>
		</table>	
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			
		</td>
	</tr>
</table>							
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
window.onload = loadMap();
  function loadMap() {
		var latedit=[];
		var longedit=[];
		var longitude = document.getElementById('longitude').value;
		var latitude = document.getElementById('latitude').value;
		latedit=latitude.split(",");
		longedit=longitude.split(",");
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
					var marker = new google.maps.Marker({
					  position: latlng, 
					  map: map, 
					  visible: true,					  
					});
					marker.setMap(map);
			}			
		}
}
</script>

