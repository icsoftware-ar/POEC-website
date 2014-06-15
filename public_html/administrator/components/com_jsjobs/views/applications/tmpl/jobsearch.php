<?php
/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 + Created by:	Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/views/application/tmpl/formjobsearch.php
 ^ 
 * Description: Form template for a company
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access'); 
jimport('joomla.html.pane');

$document = &JFactory::getDocument();
$document->addScript( JURI::base() . '/includes/js/joomla.javascript.js');
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

JHTML :: _('behavior.calendar');
$width_big = 40;
$width_med = 25;
$width_sml = 15;

	$dateformat = $this->config['date_format'];
	$firstdash = strpos($dateformat,'-',0);
	$firstvalue = substr($dateformat, 0,$firstdash);
	$firstdash = $firstdash + 1;
	$seconddash = strpos($dateformat,'-',$firstdash);
	$secondvalue = substr($dateformat, $firstdash,$seconddash-$firstdash);
	$seconddash = $seconddash + 1;
	$thirdvalue = substr($dateformat, $seconddash,strlen($dateformat)-$seconddash);
	$js_dateformat = '%'.$firstvalue.'-%'.$secondvalue.'-%'.$thirdvalue;
$ADMINPATH = JPATH_BASE .'\components\com_jsjobs';

?>

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
        if (document.formvalidator.isValid(f)) {
                f.check.value='<?php if(($jversion == '1.5') || ($jversion == '2.5')) echo JUtility::getToken(); else echo  JSession::getFormToken(); ?>';//send token
        }
        else {
                alert('<?php echo JText::_( 'JS_SOME_VALUES_ARE_NOT_ACCEPTABLE_PLEASE_RETRY');?>');
				return false;
        }
		return true;
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
				<div id="jsjobs_info_heading"><?php echo JText::_('JS_JOB_SEARCH'); ?></div>


<form action="index.php?option=com_jsjobs&task=view&layout=job_searchresult" method="post" name="adminForm" id="adminForm"  >
    <input type="hidden" name="isjobsearch" value="1" />
    <table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
		<?php
		$trclass = array("row0", "row1");
		$isodd = 1;
		$defaultradius = $this->config['defaultradius'];
		?>
		 <?php if ( $this->searchjobconfig['search_job_title'] == '1' ) { $isodd = 1 - $isodd; ?>
			 <tr class="<?php echo $trclass[$isodd]; ?>">	       		 <td width="20%" align="right"><?php echo JText::_('JS_JOB_TITLE'); ?></td>
         	 <td width="60%"><input class="inputbox" type="text" name="title" size="40" maxlength="255"  />
       		 </td>
     		 </tr>
	  <?php } ?>
	 <?php if ( $this->searchjobconfig['search_job_category'] == '1' ) { $isodd = 1 - $isodd;?>
 				<tr class="<?php echo $trclass[$isodd]; ?>">				<td valign="top" align="right"><?php echo JText::_('JS_CATEGORIES'); ?></td>
				<td><?php echo $this->searchoptions['jobcategory']; ?></td>
			  </tr>
	  <?php } ?>
	 <?php if ( $this->searchjobconfig['search_job_subcategory'] == '1' ) { $isodd = 1 - $isodd;?>
 				<tr class="<?php echo $trclass[$isodd]; ?>">				<td valign="top" align="right"><?php echo JText::_('JS_SUB_CATEGORIES'); ?></td>
				<td id="fj_subcategory"><?php echo $this->searchoptions['jobsubcategory']; ?></td>
			  </tr>
	  <?php } ?>
	 <?php if ( $this->searchjobconfig['search_job_type'] == '1' ) { $isodd = 1 - $isodd; ?>
      <tr class="<?php echo $trclass[$isodd]; ?>">
        <td valign="top" align="right"><?php echo JText::_('JS_JOBTYPE'); ?></td>
        <td><?php echo $this->searchoptions['jobtype']; ?></td>
      </tr>
	  <?php } ?>
	 <?php if ( $this->searchjobconfig['search_job_status'] == '1' ) {	$isodd = 1 - $isodd; ?>
      <tr class="<?php echo $trclass[$isodd]; ?>">
        <td valign="top" align="right"><?php echo JText::_('JS_JOBSTATUS'); ?></td>
        <td><?php echo $this->searchoptions['jobstatus']; ?></td>
      </tr>
	  <?php } ?>
      <?php if ( $this->searchjobconfig['search_job_salaryrange'] == '1' ) { 	$isodd = 1 - $isodd;	?>
	  <tr class="<?php echo $trclass[$isodd]; ?>">
            <td valign="top" align="right"><?php echo JText::_('JS_SALARYRANGE'); ?></td>
            <td nowrap>
            <?php echo $this->searchoptions['currency']; ?>&nbsp;&nbsp;&nbsp;
            <?php echo $this->searchoptions['salaryrangefrom']; ?>&nbsp;&nbsp;&nbsp;
            <?php echo $this->searchoptions['salaryrangeto']; ?>&nbsp;&nbsp;&nbsp;
            <?php echo $this->searchoptions['salaryrangetypes']; ?>&nbsp;&nbsp;&nbsp;
        </td>
      </tr>
       <?php } ?>
      <?php if ( $this->searchjobconfig['search_job_shift'] == '1' ) { $isodd = 1 - $isodd;	?>
	    <tr class="<?php echo $trclass[$isodd]; ?>">
        <td valign="top" align="right"><?php echo JText::_('JS_SHIFT'); ?></td>
        <td><?php echo $this->searchoptions['shift']; ?></td>
      </tr>
       <?php } ?>
      <?php if ( $this->searchjobconfig['search_job_durration'] == '1' ) { $isodd = 1 - $isodd;	?>
	    <tr class="<?php echo $trclass[$isodd]; ?>">
        <td valign="top" align="right"><?php echo JText::_('JS_DURATION'); ?></td>
        <td><input class="inputbox" type="text" name="durration" size="10" maxlength="15"  /></td>
      </tr>
       <?php } ?>
      <?php if ( $this->searchjobconfig['search_job_startpublishing'] == '1' ) { $isodd = 1 - $isodd;	?>
	   <tr class="<?php echo $trclass[$isodd]; ?>">
        <td valign="top" align="right"><?php echo JText::_('JS_START_PUBLISHING'); ?></td>
        <td><?php if($jversion == '1.5'){?><input class="inputbox" type="text" name="startpublishing" id="startpublishing" readonly class="Shadow Bold" size="10" value="" />
			        <input type="reset" class="button" value="..." onclick="return showCalendar('startpublishing','<?php echo $js_dateformat; ?>');"  />
			<?php }else	echo JHTML::_('calendar', '','startpublishing', 'startpublishing',$js_dateformat,array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'19')); ?>
		</td>
      </tr>
       <?php } ?>
      <?php if ( $this->searchjobconfig['search_job_stoppublishing'] == '1' ) { $isodd = 1 - $isodd;	?>
	   <tr class="<?php echo $trclass[$isodd]; ?>">
        <td valign="top" align="right"><?php echo JText::_('JS_STOP_PUBLISHING'); ?></td>
        <td><?php if($jversion == '1.5'){?><input class="inputbox" type="text" name="stoppublishing" id="stoppublishing" readonly class="Shadow Bold" size="10" value="" />
			        <input type="reset" class="button" value="..." onclick="return showCalendar('stoppublishing','<?php echo $js_dateformat; ?>');"  />
			<?php }else echo JHTML::_('calendar', '','stoppublishing', 'stoppublishing',$js_dateformat,array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'19')); ?>
		</td>
      </tr>
	  <?php } ?>
	 <?php if ( $this->searchjobconfig['search_job_company'] == '1' ) { $isodd = 1 - $isodd;	?>

   		<tr class="<?php echo $trclass[$isodd]; ?>">
        <td align="right"><?php echo JText::_('JS_COMPANYNAME'); ?></td>
        <td><?php echo $this->searchoptions['companies']; ?>
        </td>
      </tr>
	  <?php } ?>
	
	 <?php if ( $this->searchjobconfig['search_job_city'] == '1' ) { $isodd = 1 - $isodd;	?>
      <tr class="<?php echo $trclass[$isodd]; ?>">
        <td align="right"><?php echo JText::_('JS_CITY'); ?></td>
        <td id="city">
						<input type="text" name="searchcity" size="40" id="searchcity"  value="" />
					 
		</td>
      </tr>
	  <?php } ?>
    
	 <?php if ( $this->searchjobconfig['search_job_zipcode'] == '1' ) { $isodd = 1 - $isodd;	?>
      <tr class="<?php echo $trclass[$isodd]; ?>">
        <td align="right"><?php echo JText::_('JS_ZIPCODE'); ?></td>
        <td><input class="inputbox" type="text" name="zipcode" size="40" maxlength="100"  />
        </td>
      </tr>
	  <?php } ?>
	  
	 <?php if ( $this->searchjobconfig['search_job_coordinates'] == '1' ) { $isodd = 1 - $isodd;	?>
		  <tr class="<?php echo $trclass[$isodd]; ?>">
			<td align="right"><?php echo JText::_('JS_MAP_COORDINATES'); ?></td>
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
		  <tr class="<?php echo $trclass[$isodd]; $isodd = 1 - $isodd; ?>">
			<td align="right"><?php echo JText::_('JS_COORDINATES_RADIUS'); ?></td>
			<td><input type="text" id="radius" name="radius" value=""/>    </td>
		  </tr>
		  <tr class="<?php echo $trclass[$isodd]; $isodd = 1 - $isodd; ?>">
			<td align="right"><?php echo JText::_('JS_RADIUS_LENGTH_TYPE'); ?></td>
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
	 <?php if ( $this->searchjobconfig['search_job_keywords'] == '1' ) { $isodd = 1 - $isodd;	?>
		  <tr class="<?php echo $trclass[$isodd]; ?>">
			<td align="right"><?php echo JText::_('JS_KEYWORDS'); ?></td>
			<td><input class="inputbox" type="text" name="keywords" size="40" maxlength="100"  /></td>
		  </tr>
	  <?php } ?>
      <tr>
        <td colspan="2" height="5"></td>
      <tr>
	<tr>
		<td colspan="2" align="center">
		<input class="button" type="submit" name="submit_app" onclick="return checkmapcooridnate();" value="<?php echo JText::_('JS_SEARCH_JOB'); ?>" />
		</td>
	</tr>
    </table>


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

	xhr.open("GET","index.php?option=com_jsjobs&task=listsubcategoriesforsearch&val="+val,true);
	xhr.send(null);
}

//window.onLoad=dochange('country', -1);  
			  
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
div#closetag{
	positon:reletive;
	float:right;
	height:20px;
	width:20px;
	
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
			document.adminForm.submit();
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
