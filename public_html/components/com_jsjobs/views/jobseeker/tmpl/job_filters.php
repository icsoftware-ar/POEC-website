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
 * File Name:	views/jobseeker/tmpl/filters.php
 ^ 
 * Description: template view for filters
 ^ 
 * History:		NONE
 ^ 
 */
 
 defined('_JEXEC') or die('Restricted access');
  $document =& JFactory::getDocument();
   $document->addStyleSheet('components/com_jsjobs/themes/'.$this->config['theme']);
	$document->addStyleSheet('components/com_jsjobs/css/token-input-jsjobs.css');
   

 global $mainframe;
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


jimport('joomla.filter.output');
$filter_address_fields_width = $this->config['filter_address_fields_width'];
?>

<?php if ($this->config['filter'] == 1) {
	$defaultradius = $this->config['defaultradius']; ?>
		<?php 	if($this->config['filter_map'] == 1){?>
				<div id="map" style="width:<?php echo $this->config['mapwidth'];?>px; height:<?php echo $this->config['mapheight'];?>px">
					<div id="closetag"><a href="Javascript: hidediv();"><?php echo JText::_('X');?></a></div>
					<?php $radius = JText::_('JS_COORDINATES_RADIUS');
					$style = "width:".$this->config['filter_map_fields_width']."px;";
					$style .= "color:#808080;";
							$longitude = JText::_('JS_LONGITUDE');
							$style .= "color:#808080;";
							if(isset($this->filtervalues)) if($this->filtervalues['longitude'] != ''){ $longitude = $this->filtervalues['longitude'];$style .= "color:black;";} ?>
							<input class="inputbox" type="text" id="filter_longitude" name="filter_longitude" value="<?php echo $longitude;?>" size="25" style="<?php echo $style;?>" onfocus="if(this.value == '<?php echo JText::_('JS_LONGITUDE');?>') { this.value = ''; this.style.color='black';}" onblur="if(this.value == '') { this.style.color='#808080';this.value='<?php echo JText::_('JS_LONGITUDE');?>';  }" />
					<?php 	$latitude = JText::_('JS_LATITTUDE');
							$style .= "color:#808080;";
							if(isset($this->filtervalues)) if($this->filtervalues['latitude'] != ''){ $latitude = $this->filtervalues['latitude'];$style .= "color:black;";} ?>
							<input class="inputbox" type="text" id="filter_latitude" name="filter_latitude" value="<?php echo $latitude;?>" size="25" style="<?php echo $style;?>" onfocus="if(this.value == '<?php echo JText::_('JS_LATITTUDE');?>') { this.value = ''; this.style.color='black';}" onblur="if(this.value == '') { this.style.color='#808080';this.value='<?php echo JText::_('JS_LATITTUDE');?>';  }" onchange="this.style.color='black';"/>
					<?php	if(isset($this->filtervalues)) if($this->filtervalues['radius'] != ''){ $radius = $this->filtervalues['radius'];$style .= "color:black;";} ?>
							<input class="inputbox" type="text" id="filter_radius" name="filter_radius" value="<?php echo $radius;?>" style="<?php echo $style;?>" size="25" onfocus="if(this.value == '<?php echo JText::_('JS_COORDINATES_RADIUS');?>') { this.value = ''; this.style.color='black';}" onblur="if(this.value == '') { this.style.color='#808080';this.value='<?php echo JText::_('JS_COORDINATES_RADIUS');?>';  }" />
							<select name="filter_radius_length_type" id="filter_radius_length_type" style="<?php echo $style;?>" class="inputbox">
								<option value="m" <?php if($defaultradius == 1) echo 'selected="selected"';?> ><?php echo JText::_('JS_METERS');?></option>
								<option value="km" <?php if($defaultradius == 2) echo 'selected="selected"';?> ><?php echo JText::_('JS_KILOMETERS');?></option>
								<option value="mile" <?php if($defaultradius == 3) echo 'selected="selected"';?> ><?php echo JText::_('JS_MILES');?></option>
								<option value="nacmiles" <?php if($defaultradius == 4) echo 'selected="selected"';?> ><?php echo JText::_('JS_NAUTICAL_MILES');?></option>
							</select>
					<div id="map_container"></div>
				</div>
		<?php } ?>
			<div id="tp_filter_in">
				<div id="tp_filter_in_div">
					<?php 
						if ($this->config['filter_category'] == 1)  echo $this->filterlists['jobcategory'];
						if ($this->config['filter_sub_category'] == 1)  echo '<span id="td_jobsubcategory">'.$this->filterlists['jobsubcategory'].'</span>';
						if ($this->config['filter_jobtype'] == 1) echo $this->filterlists['jobtype'];
					?>	
					<a style="display:block;float:right;" href="Javascript: showdiv();loadMap();"><img  src="components/com_jsjobs/images/mapIcon.png" title="<?php echo JText::_('JS_MAP');?>" alt="<?php echo JText::_('JS_MAP');?>" /></a>
				</div>
				<div id="tp_filter_in_div" style="text-align:right;">
					
					<?php if ($this->config['filter_address'] == 1) { 
							$style = "width:".$this->config['filter_address_fields_width']."px;";
							$fieldwidth=$this->config["filter_map_fields_width"]."px;";
							?>
							<?php if($this->isjobsharing){
									if ($this->config['filter_address_country'] == 1) {
										echo '<span style="float:left;" name="filter_country" id="filter_country">'.$this->filterlists['country'].'</span>';
									}
								//}
							}	?>
							<span id="jsjobs_object_jqueryautocomplete"> <img width="13px" height="22px" src="components/com_jsjobs/images/filternav.png" text="<?php echo JText::_('JS_LOCATION'); ?>" alt="<?php echo JText::_('JS_LOCATION'); ?>" /></span>
							<div id="jsjobs_object_jqueryautocomplete_left" style="<?php echo $style;  ?>">
								<input type="text" name="txtfilter_city"  id="txtfilter_city" style="<?php echo $style;  ?>" value="<?php echo $this->filtervalues['city'];?>" />
								<input type="hidden" name="txtfilter_citylocation" id="txtfilter_citylocation" value="<?php echo $this->filtervalues['location'];?>"  />
							</div>                                   
							     
					<?php }
						if(isset($this->userrole)){
							if (isset($this->uid) && (isset($this->userrole->rolefor))){
								if (isset($this->filterid)) { ?>
										<button id="tp_filter_button" name="btn_delete" onclick="deleteSearch();"><?php echo JText::_( 'JS_THIS_FILTER_DELETE' ); ?></button>
								<?php } else { ?>
										<button id="tp_filter_button" name="btn_save" onclick="saveSearch();"><?php echo JText::_( 'JS_THIS_FILTER_SAVE' ); ?></button>
								<?php }
							} 
					} ?>
					<button id="tp_filter_button" onclick="return checkmapcooridnate();"><?php echo JText::_( 'JS_GO' ); ?></button>
					&nbsp;<button id="tp_filter_button" onclick="myReset();"><?php echo JText::_( 'JS_RESET' ); ?></button>
				</div>
			</div>
		<input type="hidden" name="id" value="<?php echo $this->filterid; ?>">
		<input type="hidden" name="formaction" value="">
		<input type="hidden" id="default_longitude" name="default_longitude" value="<?php echo $this->config['default_longitude'];?>"/>
		<input type="hidden" id="default_latitude" name="default_latitude" value="<?php echo $this->config['default_latitude'];?>"/>
		

<script language=Javascript>
jQuery(document).ready(function() {
    var value = jQuery("#txtfilter_city").val();
    
    if(value != ""){
        jQuery("#txtfilter_city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
            theme: "jsjobs",
            width: '<?php echo $this->config['filter_address_fields_width']?>px',
            preventDuplicates: true,
            hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
            noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
            searchingText: "<?php echo JText::_('SEARCHING...');?>",
            tokenLimit: 1,
            prePopulate: [
                {id: "<?php  echo $this->filtervalues['city']; ?>", name: "<?php echo  $this->filtervalues['location'];?>"}
            ]
        });
        
    }else{
        jQuery("#txtfilter_city").tokenInput("<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=getaddressdatabycityname";?>", {
            theme: "jsjobs",
            width: '<?php echo $this->config['filter_address_fields_width']?>px',
            preventDuplicates: true,
            hintText: "<?php echo JText::_('TYPE_IN_A_SEARCH_TERM'); ?>",
            noResultsText: "<?php echo JText::_('NO_RESULTS'); ?>",
            searchingText: "<?php echo JText::_('SEARCHING...');?>",
            tokenLimit: 1

        });
    }


    jQuery("div.jsjobs-input-dropdown-jsjobs").css('width', jQuery("ul.jsjobs-input-list-jsjobs").css('width'));
});
	
	
	
	
	
function setWidthForFilter(){
	var totalwidth = document.getElementById("tp_filter_in_div").offsetWidth-35;
	var addressobjects = 0;
	if (testIsValidObject(document.adminForm.cmbfilter_country)) addressobjects=addressobjects+1;
	if (testIsValidObject(document.adminForm.cmbfilter_state)) addressobjects=addressobjects+1;
	//if (testIsValidObject(document.adminForm.cmbfilter_county)) addressobjects=addressobjects+1;
	if (testIsValidObject(document.adminForm.cmbfilter_city)) addressobjects=addressobjects+1;
	if (testIsValidObject(document.adminForm.txtfilter_country)) addressobjects=addressobjects+1;
	if (testIsValidObject(document.adminForm.txtfilter_state)) addressobjects=addressobjects+1;
	//if (testIsValidObject(document.adminForm.txtfilter_county)) addressobjects=addressobjects+1;
	if (testIsValidObject(document.adminForm.txtfilter_city)) addressobjects=addressobjects+1;
	var width = (totalwidth / addressobjects) - 10;
	if (testIsValidObject(document.adminForm.cmbfilter_country)) document.adminForm.cmbfilter_country.style.width = width+"px";
	if (testIsValidObject(document.adminForm.cmbfilter_state)) document.adminForm.cmbfilter_state.style.width = width+"px";
	//if (testIsValidObject(document.adminForm.cmbfilter_county)) document.adminForm.cmbfilter_county.style.width = width+"px";
	if (testIsValidObject(document.adminForm.cmbfilter_city)) document.adminForm.cmbfilter_city.style.width = width+"px";
	if (testIsValidObject(document.adminForm.txtfilter_country)) document.adminForm.txtfilter_country.style.width = width+"px";
	if (testIsValidObject(document.adminForm.txtfilter_state)) document.adminForm.txtfilter_state.style.width = width+"px";
	//if (testIsValidObject(document.adminForm.txtfilter_county)) document.adminForm.txtfilter_county.style.width = width+"px";
	if (testIsValidObject(document.adminForm.txtfilter_city)) document.adminForm.txtfilter_city.style.width = width+"px";
	var mapobjects = 0;
	if (testIsValidObject(document.adminForm.filter_longitude)) mapobjects=mapobjects+1;
	if (testIsValidObject(document.adminForm.filter_latitude)) mapobjects=mapobjects+1;
	if (testIsValidObject(document.adminForm.filter_radius)) mapobjects=mapobjects+1;
	var width = (totalwidth / mapobjects) - 45;
	if (testIsValidObject(document.adminForm.filter_longitude)) document.adminForm.filter_longitude.style.width = width+"px";
	if (testIsValidObject(document.adminForm.filter_latitude)) document.adminForm.filter_latitude.style.width = width+"px";
	if (testIsValidObject(document.adminForm.filter_radius)) document.adminForm.filter_radius.style.width=width+"px";
	var listobjects = 0;
	if (testIsValidObject(document.getElementsByName("btn_delete")[0])) listobjects = listobjects+1;
	if (testIsValidObject(document.getElementsByName("btn_save")[0])) listobjects = listobjects+1;
	if (testIsValidObject(document.adminForm.filter_jobcategory)) listobjects=listobjects+1;
	if (testIsValidObject(document.adminForm.filter_jobsubcategory)) listobjects=listobjects+1;
	if (testIsValidObject(document.adminForm.filter_jobtype)) listobjects=listobjects+1;
	var width = (totalwidth / listobjects) - 40;
	if (testIsValidObject(document.getElementsByName("btn_delete")[0])) document.getElementsByName("btn_delete")[0].style.width = width+"px";
	//if (testIsValidObject(document.getElementsByName("btn_save")[0])) document.getElementsByName("btn_save")[0].style.width = (width+16)+"px";
	if (testIsValidObject(document.adminForm.filter_jobcategory)) document.adminForm.filter_jobcategory.style.width = width+"px";
	if (testIsValidObject(document.adminForm.filter_jobsubcategory)) document.adminForm.filter_jobsubcategory.style.width = width+"px";
	if (testIsValidObject(document.adminForm.filter_jobtype)) document.adminForm.filter_jobtype.style.width = width+"px";
	
}
//setWidthForFilter();
function saveSearch(){
	document.adminForm.formaction.value = document.adminForm.action;
	document.adminForm.action = 'index.php?option=com_jsjobs&c=jsjobs&task=savefilter';
 document.adminForm.submit();
 
 }

function deleteSearch(){
	document.adminForm.formaction.value = document.adminForm.action;
	document.adminForm.action = 'index.php?option=com_jsjobs&c=jsjobs&task=deletefilter';
 document.adminForm.submit();
 }
function myReset(){
var cmbfilter_country_combo = document.getElementById("cmbfilter_country");
if(typeof cmbfilter_country_combo !== 'undefined' && cmbfilter_country_combo !== null) {	
	document.adminForm.cmbfilter_country.value = ''
}		
if (testIsValidObject(document.adminForm.txtfilter_city)) document.adminForm.txtfilter_city.value = '';
if (testIsValidObject(document.adminForm.filter_longitude)) document.adminForm.filter_longitude.value = '';
if (testIsValidObject(document.adminForm.filter_latitude)) document.adminForm.filter_latitude.value = '';
if (testIsValidObject(document.adminForm.filter_radius)) document.adminForm.filter_radius.value = '';

 document.adminForm.filter_jobcategory.value = '';
 document.adminForm.filter_jobsubcategory.value = '';
 document.adminForm.filter_jobtype.value = '';
 
 document.adminForm.submit();
 
 }

function testIsValidObject(objToTest) {
		if (null == objToTest) {
			return false;
		}
		if ("undefined" == typeof(objToTest) ) {
			return false;
		}
		return true;

}

//function dochange(curscr, myname, nextname, src, val){
function filter_dochange(src, val){
	document.getElementById(src).innerHTML="Loading ...";
	var xhr; 
	<?php echo 'var field_width = '.$filter_address_fields_width; ?>;
	var cityvalue = "asd";

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

			if(src =='filter_state'){
				//countyhtml = "<input class='inputbox' type='text' name='txtfilter_county' style='width:"+field_width+"px;color:#808080;'  size='25' maxlength='50' value='County' onfocus='if(this.value == \"County\"){this.value = \"\";this.style.color=\"black\";};' onblur='if(this.value == \"\") { this.style.color=\"#808080\";this.value=\"County\";  }' />";
				cityhtml = "<input class='inputbox' type='text' name='txtfilter_city' style='width:"+field_width+"px;color:#808080;' size='25' maxlength='50' value='"+cityvalue+"' onfocus='if(this.value == \""+cityvalue+"\") { this.value = \"\"; this.style.color=\"black\";}' onblur='if(this.value == \"\") { this.style.color=\"#808080\";this.value=\""+cityvalue+"\";  }'/>";
				//document.getElementById('filter_county').innerHTML=countyhtml; //retuen value
				document.getElementById('filter_city').innerHTML=cityhtml; //retuen value
			}
      }
    }
 
	xhr.open("GET","index.php?option=com_jsjobs&task=listfilteraddressdata&data="+src+"&val="+val,true);
	xhr.send(null);
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
	xhr.open("GET","index.php?option=com_jsjobs&task=listfiltersubcategories&val="+val,true);
	xhr.send(null);
}

</script>

<?php } ?>
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
		var default_latitude = document.getElementById('default_latitude').value;
		var default_longitude = document.getElementById('default_longitude').value;
		
		var latitude = document.getElementById('filter_latitude').value;
		var longitude = document.getElementById('filter_longitude').value;
		
		if(!isNaN(latitude) && !isNaN(longitude)){
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
		document.getElementById('filter_latitude').value = marker.position.lat();
		document.getElementById('filter_longitude').value = marker.position.lng();

		document.getElementById('filter_latitude').style.color = "black";
		document.getElementById('filter_longitude').style.color = "black";

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
			document.getElementById('filter_latitude').value = marker.position.lat();
			document.getElementById('filter_longitude').value = marker.position.lng();
			
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
	var latitude = document.getElementById('filter_latitude').value;
	var longitude = document.getElementById('filter_longitude').value;
	var radius = document.getElementById('filter_radius').value;
	if(latitude != 'Latitude' && longitude != 'Longitude'){
		if(radius != 'Coordinates Radius'){
			this.form.submit();
		}else{
				alert('<?php echo JText::_("JS_PLEASE_ENTER_THE_COORIDNATE_RADIUS");?>');
			return false;
		}
	}
	
}
</script>
