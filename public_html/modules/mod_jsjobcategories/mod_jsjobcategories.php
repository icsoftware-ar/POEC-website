<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , info@burujsolutions.com
				www.joomsky.com, ahmad@joomsky.com
 * Created on:	Dec 30, 2010
 ^
 + Project: 		JS Jobs 
 * File Name:	module/jsjobcategories.php
 ^ 
 * Description: Module for JS Jobs
 ^ 
 * History:		1.0.0 - Dec 30, 2010
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

$noofcategories = $params->get('noofcategories', 7);
$jobsincategory = $params->get('jobsincategory', 1);
$allcategories = $params->get('allcategories', 0);
$theme = $params->get('theme', 1);
$colperrow = $params->get('colperrow',3);
//scs
$sliding= $params->get('sliding','1');
$consecutivesliding= $params->get('consecutivesliding','3');
$slidingdirection= $params->get('slidingdirection','1'); // 0 = left  , 1=up
//sce
$colwidth = Round(100/$colperrow,1);
$colwidth = $colwidth.'%';

/** scs */
if($params->get('Itemid')) $itemid = $params->get('Itemid');			
else $itemid =  JRequest::getVar('Itemid');
$componentAdminPath = JPATH_ADMINISTRATOR.'/components/com_jsjobs';
$componentPath =  'components/com_jsjobs';
require_once $componentPath.'/models/mpjsjobs.php';
$config = array( 'table_path' => $componentAdminPath.'/tables');
$model = new JSJobsModelMpJsjobs($config);
$result = $model->getJobCategories($theme);
$categories = $result[0];
$trclass = $result[1];	
$dateformat = $result[2];	
$contents = '';
/** sce */


if ($categories) { 
    $contents .= '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">';
	$isodd = 1;
	$count = 1;
	foreach ($categories as $category) {
		if ($allcategories == 0){ // show only those categories who have jobs
			if($category->catinjobs > 0 ) $printrecord = 1; else $printrecord = 0;
		}else $printrecord = 1;
		if ($noofcategories != -1){
			if ($count >= $noofcategories ) $printrecord = 0;
		}
		if ($printrecord == 1){	
			if ($count == 1){
				$isodd = 1 - $isodd;
				$contents .=  '<tr id="mc_field_row" class="'.$trclass[$isodd].'">';
			}
			$lnks = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=list_jobs&jobcat='. $category->aliasid .'&Itemid='.$itemid; 
			$lnks = JRoute::_($lnks);
			$contents .=  '<td width="'.$colwidth.'"><span id="themeanchor"><a class="anchor" href="'.$lnks.'" >'.$category->cat_title;
						if ($jobsincategory == 1) $contents .=  ' ('. $category->catinjobs.')';
					$contents .=  '</a></span></td>';
			if ($count == $colperrow){
				$contents .=  '</tr><tr id="mc_field_row" class="'.$trclass[$isodd].'">';
				$count = 0;
			}
			$count++;			
		}	
	}
	 if ($count-1 < $colperrow){
		for ($i = $count; $i <= $colperrow; $i++){
		    $contents .=  '<td></td>';
		}
		$contents .=  '</tr>';
	}
	
	$contents .=  '</table>';
	if ($sliding == 1) {
		
		
		if($slidingdirection == 1 ){

			for ($a = 0; $a < $consecutivesliding; $a++){
				$contents .= $contents;
				}
			$contents =  '<marquee id="mod_jsjobcategories"  direction="up" scrollamount="1" onmouseover="this.stop();" onmouseout="this.start()";>'.$contents.'</marquee>';
			$contents = $contents.'<br clear="all">';
	
		}else{
			$scontents="";
			$tcontents = '<table cellpadding="0" cellspacing="0" border="1" width="100%" class="contentpane"> <tr>';
			for ($a = 0; $a < $consecutivesliding; $a++){
				$scontents .= '<td>'.$contents.'</td>';
			}
			$contents = $tcontents.$scontents.'</tr></table>';
			$contents =  '<marquee id="mod_jsjobcategories"  scrollamount="1" onmouseover="this.stop();" onmouseout="this.start()";>'.$contents.'</marquee>';
		}
		$contents .= '
		<script type="text/javascript" language=Javascript>
			jQuery(document).ready(function(){
				jQuery("marquee#mod_jsjobcategories").marquee("pointer").mouseover(function () {
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
	}
	$viewall = '<div id="viewall" width="100%" align="center"><span id="themeanchor"><a id="button" class="button minpad" href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobcat&Itemid='.$itemid.'">'.JText::_('JS_VIEW_ALL').'</a></span></div>';
        $contents .=$viewall;
	echo $contents;
}
?>

