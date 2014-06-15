<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , info@burujsolutions.com
			www.joomsky.com, ahmad@joomsky.com
 * Created on:	Sep 30, 2009
 ^
 + Project: 		JS Jobs 
 * File Name:	module/newestjsjobs.php
 ^ 
 * Description: Module for JS Jobs
 ^ 
 * History:		1.0.2 - Nov 27, 2010
 ^ 
 */

defined('_JEXEC') or die('Restricted access');
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


$noofjobs = $params->get('noofjobs', 7);
$category = $params->get('category', 1);
$subcategory = $params->get('subcategory', 1);
$company = $params->get('company', 1);
$jobtype = $params->get('jobtype', 1);
$posteddate = $params->get('posteddate', 1);
$theme = $params->get('theme', 1);
$separator = $params->get('separator', 1);
$colperrow = $params->get('colperrow',3);


$colwidth = Round(100/$colperrow,1);
$colwidth = $colwidth.'%';
$colcount = 1;

/** scs */
if($params->get('Itemid')) $itemid = $params->get('Itemid');			
else $itemid =  JRequest::getVar('Itemid');
$lang = JFactory::getLanguage();
$lang->load('com_jsjobs');


$componentAdminPath = JPATH_ADMINISTRATOR .'/components/com_jsjobs';
$componentPath =  'components/com_jsjobs/';
require_once $componentPath.'/models/mpjsjobs.php';
$config = array( 'table_path' => $componentAdminPath.'/tables');
$model = new JSJobsModelMpJsjobs($config);

//scs
$sliding= $params->get('sliding','1');
$consecutivesliding= $params->get('consecutivesliding','3');
$slidingdirection= $params->get('slidingdirection','1'); // 0 = left  , 1=up
//sce
$result = $model->getNewestJobs($noofjobs,$theme);
$jobs = $result[0];
$trclass = $result[1];	
$dateformat = $result[2];	
$contents = '';
/** sce */
$lang = JFactory::getLanguage();
$lang->load('com_jsjobs');

if ($jobs) { 
    $contents .=  '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">';
	$isodd = 1;
	foreach ($jobs as $job) {
	    $isodd = 1 - $isodd;
		$contents .=  '<tr id="mc_field_row" class="'.$trclass[$isodd].'">'; $contents .= '<td width="'.$colwidth.'" >';
		$contents .=  '<span id="themeanchor"><a class="anchor" href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_job&vj=2&oi=' . $job->aliasid . '&Itemid='.$itemid.'"><u><strong>'
	        . $job->title . '</strong></u></a></span></td>';
		if ($company == 1) { 
			$c_l=JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_company&vm=5&md=' .$job->companyaliasid .  '&Itemid='.$itemid);
			 if($colcount == $colperrow){ $contents .=  '</tr><tr id="mc_field_row" class="'.$trclass[$isodd].'">'; $colcount = 0; } $colcount++; 
			$contents .= '<td width="'.$colwidth.'">';  $contents .=  '<small>'.JText::_('JS_COMPANY').': <span id="themeanchor"><a class="anchor" href='.$c_l.'>'.$job->companyname.'</a></span></small></td>';	
		}
		if ($category == 1){ if($colcount == $colperrow){ $contents .=  '</tr><tr id="mc_field_row" class="'.$trclass[$isodd].'">'; $colcount = 0; } $colcount++; 
			$contents .= '<td width="'.$colwidth.'" >';  $contents .=  '<small>'.JText::_('JS_CATEGORY').': '.$job->cat_title.'</small></td>';	
		}if ($subcategory == 1){ if($colcount == $colperrow){ $contents .=  '</tr><tr id="mc_field_row" class="'.$trclass[$isodd].'">'; $colcount = 0; } $colcount++;
			$contents .= '<td width="'.$colwidth.'" >';  $contents .=  '<small>'.JText::_('JS_SUB_CATEGORY').': '.$job->subcat_title.'</small></td>';
		}if ($jobtype == 1){ if($colcount == $colperrow){ $contents .=  '</tr><tr id="mc_field_row" class="'.$trclass[$isodd].'">'; $colcount = 0; } $colcount++; 
			$contents .= '<td width="'.$colwidth.'" >'; $contents .=   '<small>'.JText::_('JS_TYPE').': '.$job->jobtypetitle.'</small></td>';	
		}if ($posteddate == 1) { if($colcount == $colperrow){ $contents .=  '</tr><tr id="mc_field_row" class="'.$trclass[$isodd].'">'; $colcount = 0; } $colcount++; 
			$contents .= '<td width="'.$colwidth.'" >';  $contents .=   "<small>".JText::_('JS_POSTED').": ".date($dateformat,strtotime($job->created))."</small></td>\n"; 
		}
		if ($separator == 1) { if($colcount == $colperrow){ $contents .=  '</tr><tr id="mc_field_row" class="'.$trclass[$isodd].'">'; $colcount = 0; } $colcount++; 
			$contents .= '<td width="'.$colwidth.'" >';  $contents .=  '<hr style="border:dashed #C0C0C0; border-width:1px 0 0 0; height:0;line-height:0px;font-size:0;margin:0;padding:0;"></td>';
		}
			for($i = $colcount; $i < $colperrow; $i++){
				$contents .=  '<td></td>';
			}
			$contents .=  '</tr>';
			$colcount=1;
    }
	$contents .=  '</table>';
	if ($sliding == 1) {
		
		if($slidingdirection == 1 ){
		
			for ($a = 0; $a < $consecutivesliding; $a++){
				$contents .= $contents;
				}
			$contents =  '<marquee id="mod_hotjsjobs"  direction="up" scrollamount="1" onmouseover="this.stop();" onmouseout="this.start()";>'.$contents.'</marquee>';
			$contents = $contents.'<br clear="all">';
		}else{
			
			$tcontents = '<table cellpadding="0" cellspacing="0" border="1" width="100%" class="contentpane"> <tr>';
			for ($a = 0; $a < $consecutivesliding; $a++){
				$scontents .= '<td>'.$contents.'</td>';
			}
			$contents = $tcontents.$scontents.'</tr></table>';
			$contents =  '<marquee id="mod_hotjsjobs"  scrollamount="1" onmouseover="this.stop();" onmouseout="this.start()";>'.$contents.'</marquee>';
		}
			$contents .= '
			<script type="text/javascript" language=Javascript>
				jQuery(document).ready(function(){
					jQuery("marquee#mod_hotjsjobs").marquee("pointer").mouseover(function () {
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
	echo $contents;
	
}
?>

