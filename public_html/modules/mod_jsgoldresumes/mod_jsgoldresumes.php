<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , info@burujsolutions.com
				www.joomsky.com, ahmad@joomsky.com
 * Created on:	Nov 29, 2009
 ^
 + Project: 		JS Jobs 
 * File Name:	module/jsgoldresume.php
 ^ 
 * Description: Module for JS Jobs
 ^ 
 * History:		NONE
 ^ 
 */

defined('_JEXEC') or die('Restricted access');
if(!defined('DS')){
   define('DS',DIRECTORY_SEPARATOR);
}
$document = &JFactory::getDocument();
$version = new JVersion;
$joomla = $version->getShortVersion();
$jversion = substr($joomla,0,3);
if($jversion < 3){
	$document->addScript('components/com_jsjobs/js/jquery.js');
	JHtml::_('behavior.mootools');
}else{
	JHtml::_('behavior.framework');
	JHtml::_('jquery.framework');
}	
$document->addScript('components/com_jsjobs/js/jquery.marquee.js');

$noofresumes = $params->get('noofresumes');
$noofcols = $params->get('noofcols');
$listingstyle = $params->get('listingstyle');
$title = $params->get('title');
$shtitle = $params->get('shtitle');
$applicationtitle = $params->get('applicationtitle');
$name = $params->get('name');
$category= $params->get('category');
$subcategory= $params->get('subcategory');
$jobtype = $params->get('jobtype');
$experience = $params->get('experience');
$available = $params->get('available');
$gender = $params->get('gender');
$nationality = $params->get('nationality');
$showlocation= $params->get('location');
$posteddate= $params->get('posteddate');
$separator= $params->get('separator');

$sliding= $params->get('sliding','1');
$consecutivesliding= $params->get('consecutivesliding','3');

$photo= $params->get('photo');
$photowidth= $params->get('photowidth');
$photoheight= $params->get('photoheight');

$theme = $params->get('theme', 1);

$totalcols = 0;
if ($applicationtitle == 1) $totalcols++;
if ($name == 1)$totalcols++;
if ($category == 1)$totalcols++;
if ($subcategory == 1)$totalcols++;
if ($jobtype == 1)$totalcols++;
if ($experience == 1)$totalcols++;
if ($available == 1)$totalcols++;
if ($gender == 1)$totalcols++;
if ($nationality == 1)$totalcols++;
if ($showlocation == 1)$totalcols++;
if ($posteddate == 1)$totalcols++;

$colwidth = round(100 / $noofcols);


//scs				
if($params->get('Itemid')) $itemid = $params->get('Itemid');			
else $itemid =  JRequest::getVar('Itemid');
//sce

// Language variable start
$applicationtitlecaption = JText::_('JS_TITLE');
$nametitle = JText::_('JS_NAME');
$categorytitle = JText::_('JS_CATEGORY');
$subcategorytitle = JText::_('JS_SUB_CATEGORY');
$jobtypetitle = JText::_('JS_WORK_PREFERENCE');
$experiencetitle = JText::_('JS_EXPERIENCE');
$availabletitle = JText::_('JS_AVAILABLITY');
$gendertitle = JText::_('JS_GENDER');
$nationalitytitle = JText::_('JS_NATIONALITY');
$locationtitle = JText::_('JS_LOCATION');
$postedtitle = JText::_('JS_POSTED');
$yes = JText::_('JS_YES');
$no = JText::_('JS_NO');
$male = JText::_('JS_MALE');
$female = JText::_('JS_FEMALE');
// Language variable end
/** scs */
$componentAdminPath = JPATH_ADMINISTRATOR.'/components/com_jsjobs';
$componentPath =  'components/com_jsjobs';
require_once $componentPath.'/models/mpjsjobs.php';
$config = array( 'table_path' => $componentAdminPath.'/tables');
$model = new JSJobsModelMpJsjobs($config);
$result = $model->getGoldResumes($noofresumes,$theme);

$resumes = $result[0];
$trclass = $result[1];	
$dateformat = $result[2];	
$datadirectory = $result[3];	

$contents = '';
/** sce */

$lang = JFactory::getLanguage();
$lang->load('com_jsjobs');
if (isset($resumes)) { 
	if($listingstyle == 1){ //vertical listing
	
		$top = "";
		$contents .= '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">';
		$isodd = 1;
		foreach($resumes as $resume)	{ 
			$isodd = 1 - $isodd;

			if ($shtitle == 1){
				$top =  '<tr id="mc_field_row" class="'.$trclass[$isodd].'">';
				$top .=  '<td colspan="'.$noofcols.'">';
				$top .=  '<h2><u>'.$title.'</u></h2>';	
				$top .= '</td>';
				$top .= '</tr>';
			}	
			
			$contents .= '<tr id="mc_field_row" class="'.$trclass[$isodd].'"><td>';
			$r_l=JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=view_resume&vm=3&rd='. $resume->aliasid . '&Itemid='.$itemid);		
			
			if ($applicationtitle == 1) $contents .=  '<small>'.$applicationtitlecaption.': <span id="themeanchor"><a class="anchor" href='.$r_l.'>'.$resume->application_title.'</a></span></small><br>';
			if ($name == 1) $contents .=  '<small>'.$nametitle.': <span id="themeanchor"><a class="anchor" href='.$r_l.'>'.$resume->first_name.' '.$resume->last_name.'</a></span></small><br>';
			if ($category == 1)$contents .=  '<small >'.$categorytitle.' : '.$resume->cat_title.'</small><br>';
			if ($subcategory == 1)$contents .=  '<small >'.$subcategorytitle.' : '.$resume->subcat_title.'</small><br>';
			if ($jobtype == 1)$contents .= '<small >'.$jobtypetitle.' : '.$resume->jobtypetitle.'</small><br>';
			if ($experience == 1)$contents .=  '<small>'.$experiencetitle.' : '.$resume->total_experience.'</small><br>';
			if ($available == 1) if($resume->iamavailable == 1) $contents .=  '<small>'.$availabletitle.' : '.$yes.'</small><br>'; else $contents .=  '<small>'.$availabletitle.' : '.$no.'</small><br>';
			if ($gender == 1) if($resume->gender == 1) $contents .=  '<small>'.$gendertitle.' : '.$male.'</small><br>'; else $contents .=  '<small>'.$gendertitle.' : '.$female.'</small><br>';
			if ($nationality == 1)$contents .= '<small>'.$nationalitytitle.' : '.$resume->nationalityname.'</small><br>';
			if ($showlocation == 1){
					$location = '';
					$comma='';
					if($resume->cityname != ''){
						$location .= $comma.$resume->cityname; $comma = " ," ;
					}elseif($resume->address_city != ''){
						$location .= $comma.$resume->address_city; $comma = " ," ;
					}
					
					if($resume->statename != ''){
						$location .= $comma.$resume->statename; $comma = " ," ;
					}elseif($resume->address_state != ''){
						$location .= $comma.$resume->address_state; $comma = " ,";
					}
					
					if($resume->countryname != '') $location .= $comma.$resume->countryname;
				
					if(isset($location))
						$contents .= '<small>'.$locationtitle.': '.$location.'</small><br />';
			}	
			if ($posteddate == 1) $contents .= "<small>".$postedtitle.": ".date($dateformat,strtotime($resume->create_date))."</small><br /><br />";
			if ($separator == 1) $contents .= '<hr style="border:dashed #C0C0C0; border-width:1px 0 0 0; height:0;line-height:0px;font-size:0;margin:0;padding:0;">';
			$contents .= '</td></tr>';
		}
		$contents .= '</table>';
		
		if ($sliding == 1) {
			for ($a = 0; $a < $consecutivesliding; $a++){
				$contents .= $contents;
				}
			$contents .= '
			<script type="text/javascript" language=Javascript>
				jQuery(document).ready(function(){
					jQuery("marquee#mod_jsgoldresumes").marquee("pointer").mouseover(function () {
					  jQuery(this).trigger("stop");
					}).mouseout(function () {
					  jQuery(this).trigger("start");
					}).mousemove(function (event) {
					  if (jQuery(this).data("drag") == true) {
						this.scrollLeft = jQuery(this).data("scrollX") + (jQuery(this).data("x") - event.clientX);
					  }
					}).mousedown(function (event) {
					  jQuery(this).data("drag", true).data("x", event.clientX).data("scrollX", this.scrollLeft);
					}).mouseup(function () {
					  jQuery(this).data("drag", false);
					});
				});
			</script>';			
				
			$contents =  '<marquee id="mod_jsgoldresumes"  direction="up" scrollamount="1" onmouseover="this.stop();" onmouseout="this.start()";>'.$contents.'</marquee>';
			$contents =$top.$contents.'<br clear="all">';
		}
		
		
		
	}else{ // horizontal listing
		$contents =  '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">';
			$isodd = 1;

					if ($shtitle == 1){
						$contents .=  '<tr id="mc_field_row" class="'.$trclass[$isodd].'">';
						$contents .=  '<td  height="25" class="'.$trclass[$isodd].'" colspan="'.$totalcols.'" align="center">'. $title.'</td>';
						$contents .=  '</tr><tr><td colspan="'.$totalcols.'" height="2"></td></tr>';
					}	
					$contents .=  '<tr id="mc_field_row" class="'.$trclass[$isodd].'">';
						if ($applicationtitle == 1)$contents .=  '<th align="center" width="20%">'.$applicationtitlecaption.'</th>';
						if ($name == 1)$contents .=  '<th align="center" width="20%">'.$nametitle.'</th>';
						if ($category == 1)$contents .=  '<th align="center" width="20%">'.$categorytitle.'</th>';
						if ($subcategory == 1)$contents .=  '<th align="center" width="20%">'.$subcategorytitle.'</th>';
						if ($jobtype == 1)$contents .=  '<th align="center" width="20%">'.$jobtypetitle.'</th>';
						if ($experience == 1)$contents .=  '<th align="center" width="20%">'.$experiencetitle.'</th>';
						if ($available == 1)$contents .=  '<th align="center" width="15%">'.$availabletitle.'</th>';
						if ($gender == 1)$contents .=  '<th align="center" width="15%">'.$gendertitle.'</th>';
						if ($nationality == 1)$contents .=  '<th align="center" width="15%">'.$nationalitytitle.'</th>';
						if ($showlocation == 1)$contents .=  '<th align="center" width="20%">'.$locationtitle.'</th>';
						if ($posteddate == 1)$contents .=  '<th align="center" width="15%">'.$postedtitle.'</th>';
					$contents .=  '</tr>';
					
					foreach($resumes as $resume)	{ 
						$contents .=  '<tr id="mc_field_row" class="'.$trclass[$isodd].'">';
									$r_l=JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=view_resume&vm=3&rd='. $resume->aliasid . '&Itemid='.$itemid);		
								  
									if ($applicationtitle == 1) $contents .=  '<td ><span id="themeanchor"><a class="anchor" href='.$r_l.'>'.$resume->application_title.'</a></span></td>';
									if ($name == 1) $contents .=  '<td ><span id="themeanchor"><a class="anchor" href='.$r_l.'>'.$resume->first_name.' '.$resume->last_name.'</a></span></td>';
									if ($category == 1)$contents .=  '<td >'.$resume->cat_title.'</td>';
									if ($subcategory == 1)$contents .=  '<td >'.$resume->subcat_title.'</td>';
									if ($jobtype == 1)$contents .=  '<td >'.$resume->jobtypetitle.'</td>';
									if ($experience == 1)$contents .=  '<td >'.$resume->total_experience.'</td>';
									if ($available == 1) if($resume->iamavailable == 1) $contents .=  '<td >'.$yes.'</td>'; else $contents .=  '<td >'.$no.'</td>';
									if ($gender == 1) if($resume->gender == 1) $contents .=  '<td >'.$male.'</td>'; else $contents .=  '<td >'.$female.'</td>';
									if ($nationality == 1)$contents .=  '<td >'.$resume->nationalityname.'</td>';
									if ($showlocation == 1){
										$contents .=  '<td >';
												$location = '';
												$comma='';
												if($resume->cityname != ''){
													$location .= $comma.$resume->cityname; $comma = " ," ;
												}elseif($resume->address_city != ''){
													$location .= $comma.$resume->address_city; $comma = " ," ;
												}
												
												if($resume->statename != ''){
													$location .= $comma.$resume->statename; $comma = " ," ;
												}elseif($resume->address_state != ''){
													$location .= $comma.$resume->address_state; $comma = " ,";
												}
												
												if($resume->countryname != '') $location .= $comma.$resume->countryname;
											
												if(isset($location))
													$contents .= '<small>'.$locationtitle.': '.$location.'</small><br />';
									}	
									if ($posteddate == 1)$contents .=  '<td >'.date($dateformat,strtotime($resume->create_date)).'</td>';
									$isodd=1-$isodd;
						 $contents .=  '</tr>';
					}
			$contents .=  '<tr height="6"></tr>	';
		$contents .=  '</table>	';
		
		if($sliding == 1){
			$tcontents = '<table cellpadding="0" cellspacing="0" border="1" width="100%" class="contentpane"> <tr>';
			for ($a = 0; $a < $consecutivesliding; $a++){
				$scontents .= '<td>'.$contents.'</td>';
			}
			$contents = $tcontents.$scontents.'</tr></table>';
			$contents .= '
			<script type="text/javascript" language=Javascript>
				jQuery(document).ready(function(){
					jQuery("marquee#mod_jsgoldresumes").marquee("pointer").mouseover(function () {
					  jQuery(this).trigger("stop");
					}).mouseout(function () {
					  jQuery(this).trigger("start");
					}).mousemove(function (event) {
					  if (jQuery(this).data("drag") == true) {
						this.scrollLeft = jQuery(this).data("scrollX") + (jQuery(this).data("x") - event.clientX);
					  }
					}).mousedown(function (event) {
					  jQuery(this).data("drag", true).data("x", event.clientX).data("scrollX", this.scrollLeft);
					}).mouseup(function () {
					  jQuery(this).data("drag", false);
					});
				});
			</script>';			
			
			$contents =  '<marquee  id="mod_jsgoldresumes" scrollamount="2" onmouseover="this.stop();" onmouseout="this.start()";>'.$contents.'</marquee>';
		}
		
		
	}	
	echo $contents;
		
}
?>

