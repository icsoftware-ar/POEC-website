<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , info@burujsolutions.com
			www.joomsky.com, ahmad@joomsky.com
 * Created on:	Oct 29th 2009
 ^
 + Project: 		JS Jobs 
 * File Name:	module/newestjsresume.php
 ^ 
 * Description: Module for JS Jobs
 ^ 
 * History:		1.0.1 - Nov 27, 2010
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

$noofresumes = $params->get('noofresumes', 7);
$title = $params->get('title', 1);
$nationality = $params->get('nationality', 1);
$gender = $params->get('gender', 1);
$available = $params->get('available', 1);

$category = $params->get('category', 1);
$subcategory = $params->get('subcategory', 1);
$jobtype = $params->get('jobtype', 1);
$salaryrange = $params->get('salaryrange', 1);
$highesteducation = $params->get('highesteducation', 1);
$experience = $params->get('experience', 1);
$posteddate = $params->get('posteddate', 1);
$theme = $params->get('theme', 1);
$separator = $params->get('separator', 1);
$colperrow = $params->get('colperrow',3);
//scs
$sliding= $params->get('sliding','1');
$consecutivesliding= $params->get('consecutivesliding','3');
$slidingdirection= $params->get('slidingdirection','1'); // 0 = left  , 1=up
//sce
$colwidth = Round(100/$colperrow,1);
$colwidth = $colwidth.'%';
$colcount = 1;

/** scs */
if($params->get('Itemid')) $itemid = $params->get('Itemid');			
else $itemid =  JRequest::getVar('Itemid');
$componentAdminPath = JPATH_ADMINISTRATOR.'/components/com_jsjobs';
$componentPath =  'components/com_jsjobs';
require_once $componentPath.'/models/mpjsjobs.php';
$config = array( 'table_path' => $componentAdminPath.'/tables');
$model = new JSJobsModelMpJsjobs($config);
$result = $model->getNewestResumes($noofresumes,$theme);
$resumes = $result[0];
$trclass = $result[1];	
$dateformat = $result[2];	
//$cur = $result[3];	
$contents = '';
/** sce */

$lang = JFactory::getLanguage();
$lang->load('com_jsjobs');

if ($resumes) { 
    $contents .=  '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">';
	$isodd = 1;
	if ($salaryrange == 1){
		//if ($cur) $currency = $cur->configvalue;
		//else $currency = 'Rs';
	}	
	foreach ($resumes as $resume) {
	    $isodd = 1 - $isodd;
		$r_l=JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=view_resume&vm=3&rd='. $resume->aliasid . '&Itemid='.$itemid);		
		$contents .=  '<tr id="mc_field_row" class="'.$trclass[$isodd].'">'; $contents .= '<td width="'.$colwidth.'">';
			$contents .=  '<span id="themeanchor"><a class="anchor" href='.$r_l.'><u><strong>'
	        . $resume->first_name.' '.$resume->last_name . '</strong></u></a></span></td>';
		if ($title == 1) {  if($colcount == $colperrow) { $contents .=  '</tr><tr id="mc_field_row" class="'.$trclass[$isodd].'">'; $colcount = 0; } $colcount++; 
			$contents .= '<td width="'.$colwidth.'">';  $contents .=  '<small>'.JText::_('JS_TITLE').': '.$resume->application_title.'</small></td>';	
		}if ($category == 1) {  if($colcount == $colperrow) { $contents .=  '</tr><tr id="mc_field_row" class="'.$trclass[$isodd].'">'; $colcount = 0; } $colcount++; 
			$contents .= '<td width="'.$colwidth.'">';  $contents .=  '<small>'.JText::_('JS_CATEGORY').': '.$resume->cat_title.'</small></td>';	
		}if ($subcategory == 1) {  if($colcount == $colperrow) { $contents .=  '</tr><tr id="mc_field_row" class="'.$trclass[$isodd].'">'; $colcount = 0; } $colcount++;
			$contents .= '<td width="'.$colwidth.'">';  $contents .=  '<small>'.JText::_('JS_SUB_CATEGORY').': '.$resume->subcat_title.'</small></td>';
		}if ($jobtype == 1) {  if($colcount == $colperrow) { $contents .=  '</tr><tr id="mc_field_row" class="'.$trclass[$isodd].'">'; $colcount = 0; } $colcount++; 
			$contents .= '<td width="'.$colwidth.'">';  $contents .=   '<small>'.JText::_('JS_WORK_PREFERENCE').': '.$resume->jobtypetitle.'</small></td>';	
		}if ($highesteducation == 1) {  if($colcount == $colperrow) { $contents .=  '</tr><tr id="mc_field_row" class="'.$trclass[$isodd].'">'; $colcount = 0; } $colcount++; 
			$contents .= '<td width="'.$colwidth.'">';  $contents .=   '<small>'.JText::_('JS_HIGHEST_EDUCATION').': '.$resume->educationtitle.'</small></td>';	
		}if ($salaryrange == 1)	{  if($colcount == $colperrow) { $contents .=  '</tr><tr id="mc_field_row" class="'.$trclass[$isodd].'">'; $colcount = 0; } $colcount++; 
			$contents .= '<td width="'.$colwidth.'">';  $contents .=   '<small>'.JText::_('JS_SALARY').': '.$resume->symbol.$resume->rangestart.' - '.$resume->symbol.$resume->rangeend .'</small></td>';	
		}if ($experience == 1) {  if($colcount == $colperrow) { $contents .=  '</tr><tr id="mc_field_row" class="'.$trclass[$isodd].'">'; $colcount = 0; } $colcount++; 
			$contents .= '<td width="'.$colwidth.'">';  $contents .=   '<small>'.JText::_('JS_EXPERIENCE').': '.$resume->total_experience.'</small></td>';	
		}if ($available == 1) {  if($colcount == $colperrow) { $contents .=  '</tr><tr id="mc_field_row" class="'.$trclass[$isodd].'">'; $colcount = 0; } $colcount++; 
			 if ($resume->iamavailable == 1) $availabletext = JText::_('JS_YES'); 
			 else $availabletext = JText::_('JS_NO'); 
			$contents .= '<td width="'.$colwidth.'">';  $contents .=   '<small>'.JText::_('JS_AVAILABLE').': '.$availabletext.'</small></td>';	
		}if ($gender == 1) {  if($colcount == $colperrow) { $contents .=  '</tr><tr id="mc_field_row" class="'.$trclass[$isodd].'">'; $colcount = 0; } $colcount++; 
			 if ($resume->gender == 1) $gendertext=JText::_('JS_MALE'); 
			 else $gendertext=JText::_('JS_FEMALE'); 
			$contents .= '<td width="'.$colwidth.'">';  $contents .=   '<small>'.JText::_('JS_GENDER').': '.$gendertext.'</small></td>';	
		}if ($nationality == 1) {  if($colcount == $colperrow) { $contents .=  '</tr><tr id="mc_field_row" class="'.$trclass[$isodd].'">'; $colcount = 0; } $colcount++; 
			$contents .= '<td width="'.$colwidth.'">';  $contents .=   '<small>'.JText::_('JS_NATIONALITY').': '.$resume->countryname.'</small></td>';	
		}if ($posteddate == 1) {  if($colcount == $colperrow) { $contents .=  '</tr><tr id="mc_field_row" class="'.$trclass[$isodd].'">'; $colcount = 0; } $colcount++; 
			$contents .= '<td width="'.$colwidth.'">';  $contents .=   "<small>".JText::_('JS_POSTED').": ".date($dateformat,strtotime($resume->create_date))."</small></td>";
		}if ($separator == 1) {  if($colcount == $colperrow) { $contents .=  '</tr><tr id="mc_field_row" class="'.$trclass[$isodd].'">'; $colcount = 0; } $colcount++; 
			$contents .= '<td width="'.$colwidth.'">';  $contents .=  '<hr style="border:dashed #C0C0C0; border-width:1px 0 0 0; height:0;line-height:0px;font-size:0;margin:0;padding:0;"></td>';
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
			$contents =  '<marquee id="mod_newestresume"  direction="up" scrollamount="1" onmouseover="this.stop();" onmouseout="this.start()";>'.$contents.'</marquee>';
			$contents = $contents.'<br clear="all">';
		}else{
		
			$tcontents = '<table  cellpadding="0" cellspacing="0" border="1" width="100%" class="contentpane"> <tr>';
			$scontents="";
			for ($a = 0; $a < $consecutivesliding; $a++){
				$scontents .= '<td>'.$contents.'</td>';
			}
			$contents = $tcontents.$scontents.'</tr></table>';
			$contents =  '<marquee id="mod_newestresume"  scrollamount="1" onmouseover="this.stop();" onmouseout="this.start()";>'.$contents.'</marquee>';
		}
		$contents .= '
		<script type="text/javascript" language=Javascript>
			jQuery(document).ready(function(){
				jQuery("marquee#mod_newestresume").marquee("pointer").mouseover(function () {
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

