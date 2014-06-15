<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , info@burujsolutions.com
				www.joomsky.com, ahmad@joomsky.com
 * Created on:	Nov 28, 2009
 ^
 + Project: 		JS Jobs 
 * File Name:	module/jsfeaturedjobs.php
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

$noofjobs = $params->get('noofjobs');
$noofcols = $params->get('noofcols');
$listingstyle = $params->get('listingstyle');
$title = $params->get('title');
$shtitle = $params->get('shtitle');
$company = $params->get('company');
$category= $params->get('category');
$subcategory= $params->get('subcategory');
$showlocation= $params->get('location');
$posteddate= $params->get('posteddate');
$separator= $params->get('separator');

$sliding= $params->get('sliding','1');
$consecutivesliding= $params->get('consecutivesliding','3');

$logo= $params->get('logo');
$logowidth= $params->get('logowidth');
$logoheight= $params->get('logoheight');

$jobtype = $params->get('jobtype', 1);
$theme = $params->get('theme', 1);
$colwidth = round(100 / $noofcols);

/** scs */
if($params->get('Itemid')) $itemid = $params->get('Itemid');			
else $itemid =  JRequest::getVar('Itemid');

$componentAdminPath = JPATH_ADMINISTRATOR.'/components/com_jsjobs';
$componentPath =  'components/com_jsjobs';
require_once $componentPath.'/models/mpjsjobs.php';
$config = array( 'table_path' => $componentAdminPath.'/tables');
$model = new JSJobsModelMpJsjobs($config);

$result = $model->getFeaturedJobs($noofjobs,$theme);
$jobs = $result[0];
$trclass = $result[1];	

$dateformat = $result[2];
$datadirectory	= $result[3];
$contents = '';
/** sce */
$lang = JFactory::getLanguage();
$lang->load('com_jsjobs');

if ($jobs) { 
		$isodd = 1;
		if ($shtitle == 1){
		$top =  '<tr id="mc_field_row" class="'.$trclass[$isodd].'">';
		$top .=  '<td colspan="'.$noofcols.'">';
		$top .=  '<h2><u>'.$title.'</u></h2>';	
		$top .= '</td>';
		$top .= '</tr>';
	}	

	if($listingstyle == 1){ //vertical listing
		
		
		$contents .= '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">';
		
		foreach ($jobs as $job) {
			$isodd = 1 - $isodd;
			$j_l=JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_job&vj=5&oi=' . $job->aliasid . '&Itemid='.$itemid);
			$contents .= '<tr id="mc_field_row" class="'.$trclass[$isodd].'"><td>'
				. '<span id="themeanchor"><a class="anchor" href='.$j_l.'><u><strong>'
				. $job->title . '</strong></u></a></span><br />';
			$c_l=JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_company&vm=5&md=' .$job->companyaliasid .  '&Itemid='.$itemid);
			if ($company == 1) 	$contents .=  '<small>'.JText::_('JS_COMPANY').': <span id="themeanchor"><a class="anchor" href='.$c_l.'>'.$job->companyname.'</a></span></small><br />';	
			if ($category == 1) $contents .=  '<small>'.JText::_('JS_CATEGORY').': '.$job->cat_title.'</small><br />';	
			if ($subcategory == 1) $contents .=  '<small>'.JText::_('JS_SUB_CATEGORY').': '.$job->subcat_title.'</small><br />';
			if ($jobtype == 1)  $contents .=  '<small>'.JText::_('JS_TYPE').': '.$job->jobtypetitle.'</small><br />';	
			if ($showlocation == 1){
					$location="";
					if(isset($job->multicity)) $location=$job->multicity;
					$contents .= '<small>'.JText::_('JS_LOCATION').': '.$location.'</small><br />';
			}	
			
			if ($posteddate == 1) $contents .= "<small>".JText::_('JS_POSTED').": ".date($dateformat,strtotime($job->created))."</small><br /><br />";
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
				jQuery("marquee#mod_jsfeaturedjob").marquee("pointer").mouseover(function () {
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
		$contents =  '<marquee id="mod_jsfeaturedjob" direction="up" scrollamount="1" onmouseover="this.stop();" onmouseout="this.start()";>'.$contents.'</marquee>';
		$contents = $top.$contents.'<br clear="all">';
		}

		echo $contents ;
		
	}else{ // horizontal listing
	
	   $contents .= '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">';
		$isodd = 1;
		$count = 1;
		if ($shtitle == 1){
			$contents .=  '<tr id="mc_field_row" class="'.$trclass[$isodd].'">';
			if ($logo == 1) $colspan = $noofcols * 2; else $colspan = $noofcols;
			$contents .=  '<td colspan="'.$colspan.'">';
			$contents .=  '<h2><u>'.$title.'</u></h2>';	
			if ($separator == 1) $contents .= '<hr style="border:dashed #C0C0C0; border-width:1px 0 0 0; height:0;line-height:0px;font-size:0;margin:0;padding:0;">';
			$contents .= '</td>';
			$contents .= '</tr>';
		}	
		foreach ($jobs as $job) {
			$isodd = 1 - $isodd;
			if ($count == 1){
				$contents .=  '<tr id="mc_field_row" class="'.$trclass[$isodd].'">';
			}	
			$j_l=JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_job&vj=5&oi=' . $job->aliasid . '&Itemid='.$itemid);
			$c_l=JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_company&vm=5&md=' .$job->companyaliasid .  '&Itemid='.$itemid);
			
			if ($logo == 1){
				$contents .= '<td width="%"><span id="themeanchor"><a class="anchor" href='.$c_l.'>';
					if ($job->logofilename != '')
						$contents .= '<img width="'.$logowidth.'" height="'.$logoheight.'" src="'.$datadirectory.'/data/employer/comp_'.$job->companyid.'/logo/'.$job->logofilename.'" />';
					else
						$contents .= '<img width="'.$logowidth.'" height="'.$logoheight.'" src="components/com_jsjobs/images/blank_logo.png" />';
				$contents .= '</a></span></td>';								
			}	
			$contents .=  '<td width="'.$colwidth.'%">'
				. '<span id="themeanchor"><a class="anchor" href='.$j_l.'><u><strong>'
				. $job->title . '</strong></u></a></span><br />';
			if ($company == 1) $contents .=  '<small>'.JText::_('JS_COMPANY').': <span id="themeanchor"><a class="anchor" href='.$j_l.'>'.$job->companyname.'</a></span></small><br />';	
			if ($category == 1) $contents .= '<small>'.JText::_('JS_CATEGORY').': '.$job->cat_title.'</small><br />';	
			if ($subcategory == 1) $contents .= '<small>'.JText::_('JS_SUB_CATEGORY').': '.$job->subcat_title.'</small><br />';
			if ($jobtype == 1) $contents .= '<small>'.JText::_('JS_TYPE').': '.$job->jobtypetitle.'</small><br />';	
			if ($showlocation == 1){
/*
					if($job->cityname) $location = $job->cityname;
					elseif($job->city) $location = $job->city;
					elseif($job->countyname) $location = $job->countyname;
					elseif($job->county) $location = $job->county;
					elseif($job->statename) $location = $job->statename;
					elseif($job->state) $location = $job->state;
					elseif($job->countryname) $location = $job->countryname;
*/
					$location="";
					if(isset($job->multicity)) $location=$job->multicity;
					
					
				$contents .= '<small>'.JText::_('JS_LOCATION').': '.$location.'</small><br />';
			}	
			if ($posteddate == 1) $contents .= "<small>".JText::_('JS_POSTED').": ".date($dateformat,strtotime($job->created))."</small><br /><br />";
			if ($separator == 1) $contents .= '<hr style="border:dashed #C0C0C0; border-width:1px 0 0 0; height:0;line-height:0px;font-size:0;margin:0;padding:0;">';
			$contents .= '</td>';
			if ($count == $noofcols){
				$contents .= '</tr>';
				$count = 0;
			}	
			$count = $count + 1;
		}
		if ($count-1 < $noofcols){
			for ($i = $count; $i <= $noofcols; $i++){
				$contents .= '<td></td>';
				if ($logo == 1)$contents .= '<td></td>';
			}	
			$contents .= '</tr>';
		}	
		$contents .= '</table>';
		if ($sliding == 1) {
			$tcontents = '<table cellpadding="0" cellspacing="0" border="1" width="100%" class="contentpane"> <tr>';
			for ($a = 0; $a < $consecutivesliding; $a++){
				$scontents .= '<td>'.$contents.'</td>';
			}
			$contents = $tcontents.$scontents.'</tr></table>';
			$contents .= '
			<script type="text/javascript" language=Javascript>
				jQuery(document).ready(function(){
					jQuery("marquee#mod_jsfeaturedjob").marquee("pointer").mouseover(function () {
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
			
			$contents =  '<marquee  id="mod_jsfeaturedjob" scrollamount="1" onmouseover="this.stop();" onmouseout="this.start()";>'.$contents.'</marquee>';
		}
	   
		echo $contents;



	}	
}
?>

