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
 * File Name:	views/jobseeker/tmpl/jobsearch.php
 ^ 
 * Description: template for job search
 ^ 
 * History:		NONE
 ^ 
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.pane');

$editor = & JFactory :: getEditor();

global $mainframe;
$version = new JVersion;
$joomla = $version->getShortVersion();
$jversion = substr($joomla,0,3);
$document = &JFactory::getDocument();
if($jversion=='1.5') $document->addScript( JURI::base() . 'includes/js/joomla.javascript.js');
$document->addStyleSheet('components/com_jsjobs/themes/'.$this->config['theme']);
$document->addStyleSheet('components/com_jsjobs/css/token-input-jsjobs.css');

	if($jversion < 3){
		JHtml::_('behavior.mootools');
		$document->addScript('components/com_jsjobs/js/jquery.js');
	}else{
		JHtml::_('behavior.framework');
		JHtml::_('jquery.framework');
	}	
	$document->addScript('components/com_jsjobs/js/jquery.tokeninput.js');


JHTML :: _('behavior.calendar');
$width_big = 40;
$width_med = 25;
$width_sml = 15;

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
?>
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
					echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_SEARCH_JOB');
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
					<a class="<?php if($lnk[2] == 1)echo 'first'; elseif($lnk[2] == -1)echo 'last';  ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
				<?php }
			}
			?>
		</div>
		<div id="tp_heading">
			<span id="tp_headingtext">
				<span id="tp_headingtext_left"></span>
				<span id="tp_headingtext_center"><?php echo JText::_('JS_SEARCH_JOB');  ?></span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>
<?php
	$printform = 1;
	if($this->canview == 0){
            $printform = 0;
            echo "<font color='red'><strong>" . JText::_('JS_YOU_ARE_NOT_ALLOWED_TO_VIEW') . "</strong></font>";
        }
        if (isset($this->userrole))
           if (isset($this->userrole->rolefor) && $this->userrole->rolefor == 1) { //employer
                    if($this->config['employerview_js_controlpanel'] == 1)
			$printform = true;
                    else{
                        $printform = false;
                        echo JText::_('JS_YOU_ARE_NOT_ALLOWED_TO_VIEW');
                    }

        }

if ($printform == 1) {
$defaultradius = $this->config['defaultradius']; ?>
<form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=job_searchresults&Itemid='. $this->Itemid); ?>" method="post" name="adminForm" id="adminForm">
    <input type="hidden" name="isjobsearch" value="1" />
	<table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
      
	   <?php if ( $this->searchjobconfig['search_job_title'] == '1' ) { ?>
	 		 <tr>
       		 <td width="20%" align="right"><?php echo JText::_('JS_JOB_TITLE'); ?></td>
         	 <td width="60%"><input class="inputbox" type="text" name="title" size="40" maxlength="255"  />
       		 </td>
     		 </tr>
	  <?php } ?>
	 <?php if ( $this->searchjobconfig['search_job_category'] == '1' ) { ?>
			  <tr>
				<td valign="top" align="right"><?php echo JText::_('JS_CATEGORIES'); ?></td>
				<td><?php echo $this->searchoptions['jobcategory']; ?></td>
			  </tr>
	  <?php } ?>
	 <?php if ( $this->searchjobconfig['search_job_subcategory'] == '1' ) { ?>
			  <tr>
				<td valign="top" align="right"><?php echo JText::_('JS_SUB_CATEGORIES'); ?></td>
				<td id="fj_subcategory"><?php echo $this->searchoptions['jobsubcategory']; ?></td>
			  </tr>
	  <?php } ?>
	 <?php if ( $this->searchjobconfig['search_job_type'] == '1' ) { ?>
      <tr>
        <td valign="top" align="right"><?php echo JText::_('JS_JOBTYPE'); ?></td>
        <td><?php echo $this->searchoptions['jobtype']; ?></td>
      </tr>
	  <?php } ?>
	 <?php if ( $this->searchjobconfig['search_job_status'] == '1' ) { ?>
      <tr>
        <td valign="top" align="right"><?php echo JText::_('JS_JOBSTATUS'); ?></td>
        <td><?php echo $this->searchoptions['jobstatus']; ?></td>
      </tr>
	  <?php } ?>
      <?php if ( $this->searchjobconfig['search_job_salaryrange'] == '1' ) { ?>
	  <tr>
            <td valign="top" align="right"><?php echo JText::_('JS_SALARYRANGE'); ?></td>
            <td nowrap>
            <?php echo $this->searchoptions['currency']; ?>&nbsp;&nbsp;&nbsp;
            <?php echo $this->searchoptions['salaryrangefrom']; ?>&nbsp;&nbsp;&nbsp;
            <?php echo $this->searchoptions['salaryrangeto']; ?>&nbsp;&nbsp;&nbsp;
            <?php echo $this->searchoptions['salaryrangetypes']; ?>&nbsp;&nbsp;&nbsp;
        </td>
      </tr>
       <?php } ?>
      <?php if ( $this->searchjobconfig['search_job_shift'] == '1' ) { ?>
	   <tr>
        <td valign="top" align="right"><?php echo JText::_('JS_SHIFT'); ?></td>
        <td><?php echo $this->searchoptions['shift']; ?></td>
      </tr>
       <?php } ?>
      <?php if ( $this->searchjobconfig['search_job_durration'] == '1' ) { ?>
	   <tr>
        <td valign="top" align="right"><?php echo JText::_('JS_DURATION'); ?></td>
        <td><input class="inputbox" type="text" name="durration" size="10" maxlength="15"  /></td>
      </tr>
       <?php } ?>
      <?php if ( $this->searchjobconfig['search_job_startpublishing'] == '1' ) { ?>
	   <tr>
		<td valign="top" align="right" > <?php echo JText::_('JS_START_PUBLISHING'); ?></td>
		<td>
			<?php if($jversion == '1.5'){?><input class="inputbox" type="text" name="startpublishing" id="startpublishing" readonly class="Shadow Bold" size="10" value="" />
				<input type="reset" class="button" value="..." onclick="return showCalendar('startpublishing','<?php echo $js_dateformat; ?>');"  />
			<?php 
			}else echo JHTML::_('calendar', '','startpublishing', 'startpublishing',$js_dateformat,array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'19')); ?>
		</td>      
	   </tr>
       <?php } ?>
      <?php if ( $this->searchjobconfig['search_job_stoppublishing'] == '1' ) { ?>
	   <tr>
        <td valign="top" align="right"><?php echo JText::_('JS_STOP_PUBLISHING'); ?></td>
        <td>
			<?php if($jversion == '1.5'){?><input class="inputbox" type="text" name="stoppublishing" id="stoppublishing" readonly class="Shadow Bold" size="10" value="" />
			        <input type="reset" class="button" value="..." onclick="return showCalendar('stoppublishing','<?php echo $js_dateformat; ?>');"  />
			<?php 
			}else echo JHTML::_('calendar', '','stoppublishing','stoppublishing',$js_dateformat,array('class'=>'inputbox', 'size'=>'10', 'maxlenght'=>'19')); ?>
		</td>
      </tr>
	  <?php } ?>
	 <?php if ( $this->searchjobconfig['search_job_company'] == '1' ) { ?>

      <tr>
        <td align="right"><?php echo JText::_('JS_COMPANYNAME'); ?></td>
        <td><?php echo $this->searchoptions['companies']; ?>
        </td>
      </tr>
	  <?php } ?>
	  
	 <?php if ( $this->searchjobconfig['search_job_city'] == '1' ) { ?>
      <tr>
        <td align="right"><?php echo JText::_('JS_CITY'); ?></td>
        <td id="city">
						<input type="text" name="searchcity" size="40" id="searchcity"  value="" />
        </td>
      </tr>
	  <?php } ?>
	 <?php if ( $this->searchjobconfig['search_job_zipcode'] == '1' ) { ?>
      <tr>
        <td align="right"><?php echo JText::_('JS_ZIPCODE'); ?></td>
        <td><input class="inputbox" type="text" name="zipcode" size="40" maxlength="100"  />
        </td>
      </tr>
	  <?php } ?>
	 <?php if ( $this->searchjobconfig['search_job_coordinates'] == '1' ) { ?>
      <tr>
        <td align="right"><?php echo JText::_('JS_MAP_COORDINATES'); ?>
        </td>
        <td>
			<div id="outermapdiv">
				<div id="map" style="width:<?php echo $this->config['mapwidth'];?>px; height:<?php echo $this->config['mapheight'];?>px">
					<div id="closetag"><a href="Javascript: hidediv();"><?php echo JText::_('X');?></a></div>
					<div id="map_container"></div>
				</div>
			</div>

			<span id="anchor"><a class="anchor" href="Javascript: showdiv();loadMap();"><?php echo JText::_('JS_SHOW_MAP');?></a></span>
			<br/><input type="text" id="longitude" name="longitude" value=""/><?php echo JText::_('JS_LONGITUDE');?>
			<br/><input type="text" id="latitude" name="latitude" value=""/><?php echo JText::_('JS_LATITTUDE');?>
        </td>
      </tr>
      <tr>
		  <td align="right">
			  <?php echo JText::_('JS_COORDINATES_RADIUS'); ?>
		  </td>
		  <td>
			<input type="text" id="radius" name="radius" value=""/>
		  </td>
      </tr>
      <tr>
		  <td align="right">
			<?php echo JText::_('JS_RADIUS_LENGTH_TYPE');?>
		  </td>
		  <td>
			<select name="radius_length_type" id="radius_length_type">
				<option value="m" <?php if($defaultradius == 1) echo 'selected="selected"';?> ><?php echo JText::_('JS_METERS');?></option>
				<option value="km" <?php if($defaultradius == 2) echo 'selected="selected"';?> ><?php echo JText::_('JS_KILOMETERS');?></option>
				<option value="mile" <?php if($defaultradius == 3) echo 'selected="selected"';?> ><?php echo JText::_('JS_MILES');?></option>
				<option value="nacmiles" <?php if($defaultradius == 4) echo 'selected="selected"';?> ><?php echo JText::_('JS_NAUTICAL_MILES');?></option>
			</select>
		  </td>
      </tr>
	  <?php } ?>
	 <?php if ( $this->searchjobconfig['search_job_keywords'] == '1' ) { ?>
      <tr>
        <td align="right"><?php echo JText::_('JS_KEYWORDS'); ?></td>
        <td><input class="inputbox" type="text" name="keywords" size="40" maxlength="100"  />
        </td>
      </tr>
	  <?php } ?>
	  <tr>
		  <td colspan="2" height="5"></td>
	  </tr>
	<tr>
		<td colspan="2" align="center">
		<input type="submit" id="button" class="button" name="submit_app" onClick="return checkmapcooridnate();" value="<?php echo JText::_('JS_SEARCH_JOB'); ?>" />
		</td>
	</tr>
    </table>

			<input type="hidden" name="view" value="jobseeker" />
			<input type="hidden" name="layout" value="job_searchresults" />
			<input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			<input type="hidden" name="task11" value="view" />
			<input type="hidden" id="default_longitude" name="default_longitude" value="<?php echo $this->config['default_longitude'];?>"/>
			<input type="hidden" id="default_latitude" name="default_latitude" value="<?php echo $this->config['default_latitude'];?>"/>
			
		  
		  
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
 
        	document.getElementById(src).innerHTML=xhr.responseText; //retuen value

			if(src=='state'){
			countyhtml = "<input class='inputbox' type='text' name='county' size='40' maxlength='100'  />";
			cityhtml = "<input class='inputbox' type='text' name='city' size='40' maxlength='100'  />";
			document.getElementById('county').innerHTML=countyhtml; //retuen value
			document.getElementById('city').innerHTML=cityhtml; //retuen value
			}else if(src=='county'){
				cityhtml = "<input class='inputbox' type='text' name='city' size='40' maxlength='100'  />";
				document.getElementById('city').innerHTML=cityhtml; //retuen value
			}
 
      }
    }
 
	xhr.open("GET","index.php?option=com_jsjobs&task=listsearchaddressdata&data="+src+"&val="+val,true);
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

	xhr.open("GET","index.php?option=com_jsjobs&task=listsubcategoriesForSearch&val="+val,true);
	xhr.send(null);
}


</script>
			  

		</form>
<?php

}
}//ol
?>	
<div id="jsjobs_footer"><?php echo eval(base64_decode('aWYoJHRoaXMtPmNvbmZpZ1snZnJfY3JfdHhzaCddKSB7DQplY2hvIA0KJzx0YWJsZSB3aWR0aD0iMTAwJSIgc3R5bGU9InRhYmxlLWxheW91dDpmaXhlZDsiPg0KPHRyPjx0ZCBoZWlnaHQ9IjE1Ij48L3RkPjwvdHI+DQo8dHI+PHRkIHN0eWxlPSJ2ZXJ0aWNhbC1hbGlnbjp0b3A7IiBhbGlnbj0iY2VudGVyIj4NCjxhIGNsYXNzPSJpbWciIHRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIj48aW1nIHNyYz0iaHR0cDovL3d3dy5qb29tc2t5LmNvbS9sb2dvL2pzam9ic2NybG9nby5wbmciPjwvYT4NCjxicj4NCkNvcHlyaWdodCAmY29weTsgMjAwOCAtICcuZGF0ZSgnWScpLicsDQo8c3BhbiBpZD0idGhlbWVhbmNob3IiPiA8YSBjbGFzcz0iYW5jaG9yInRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSI+QnVydWogU29sdXRpb25zIDwvYT48L3NwYW4+PC90ZD48L3RyPg0KPC90YWJsZT4nOw0KfQ=='));?></div>
<style type="text/css">
div#outermapdiv{
	position:relative;
	float:left;
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
    left: 0px;
    position: absolute;
    overflow:true;
    top: -94px;
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
		var default_latitude = document.getElementById('default_latitude').value;
		var default_longitude = document.getElementById('default_longitude').value;
		
		var latitude = document.getElementById('latitude').value;
		var longitude = document.getElementById('longitude').value;
		
		if(latitude != '' && longitude != ''){
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
function checkmapcooridnate(){
	var latitude = document.getElementById('latitude').value;
	var longitude = document.getElementById('longitude').value;
	var radius = document.getElementById('radius').value;
	if(latitude != '' && longitude != ''){
		if(radius != ''){
			this.form.submit();
		}else{
				alert('<?php echo JText::_("JS_PLEASE_ENTER_THE_COORIDNATE_RADIUS");?>');
			return false;
		}
	}
	
}
		jQuery(document).ready(function() {			
		jQuery("#searchcity").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
			theme: "jsjobs",
			preventDuplicates: true,
			hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
			noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
			searchingText: "<?php echo JText::_('SEARCHING...');?>",
		});
		});








</script>
