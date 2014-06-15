<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , info@burujsolutions.com
				www.joomsky.com, ahmad@joomsky.com
 * Created on:	Nov 29, 2009
 ^
 + Project: 		JS Jobs 
 * File Name:	module/jsfeaturedcompanies.php
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


$noofcompanies = $params->get('noofcompanies');
$noofcols = $params->get('noofcols');
$listingstyle = $params->get('listingstyle');
$title = $params->get('title');
$shtitle = $params->get('shtitle');
$category= $params->get('category');
$showlocation= $params->get('location');
$posteddate= $params->get('posteddate');
$separator= $params->get('separator');

$companyname= $params->get('companyname');
$logo= $params->get('logo');
$logowidth= $params->get('logowidth');
$logoheight= $params->get('logoheight');

$sliding= $params->get('sliding','1');
$consecutivesliding= $params->get('consecutivesliding','3');

$jobtype = $params->get('jobtype', 1);
$theme = $params->get('theme', 1);
$colwidth = round(100 / $noofcols);

//scs				
if($params->get('Itemid')) $itemid = $params->get('Itemid');			
else $itemid =  JRequest::getVar('Itemid');
//sce
// Language variable start
$nametitle = JText::_('JS_NAME');
$categorytitle = JText::_('JS_CATEGORY');
$locationtitle = JText::_('JS_LOCATION');
$postedtitle = JText::_('JS_POSTED');
// Language variable end

/** scs */
$componentAdminPath = JPATH_ADMINISTRATOR.'/components/com_jsjobs';
$componentPath =  'components/com_jsjobs';
require_once $componentPath.'/models/mpjsjobs.php';
$config = array( 'table_path' => $componentAdminPath .'/tables');
$model = new JSJobsModelMpJsjobs($config);

$result = $model->getFeaturedCompanies($noofcompanies,$theme);
$companies = $result[0];
$trclass = $result[1];	
$dateformat = $result[2];	
$datadirectory = $result[3];
$contents = '';
/** sce */

$lang = JFactory::getLanguage();
$lang->load('com_jsjobs');

if ($companies){ 
	if($listingstyle == 1){ //horizontal listing
	
	
	
	
		$contents .= '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">';
		$isodd = 1;
		foreach ($companies as $company) {
			if ($shtitle == 1){
				$top =  '<tr id="mc_field_row" class="'.$trclass[$isodd].'">';
				$top .=  '<td colspan="'.$noofcols.'">';
				$top .=  '<h2><u>'.$title.'</u></h2>';	
				$top .= '</td>';
				$top .= '</tr>';
			}	
			
			$isodd = 1 - $isodd;
			$c_l=JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_company&vm=1&md=' .$company->aliasid .'&Itemid='.$itemid);
			$contents .= '<tr id="mc_field_row" class="'.$trclass[$isodd].'"><td>'
			
				. '<span id="themeanchor"><a class="anchor" href='.$c_l.'><u><strong>'
				. $company->name . '</strong></u></a></span><br />';
			if ($category == 1) $contents .= '<small>'.JText::_('JS_CATEGORY').': '.$company->cat_title.'</small><br />';	
			if ($showlocation == 1){
					/*if($company->cityname) $location = $company->cityname;
					elseif($company->city) $location = $company->city;
					elseif($company->countyname) $location = $company->countyname;
					elseif($company->county) $location = $company->county;
					elseif($company->statename) $location = $company->statename;
					elseif($company->state) $location = $company->state;
					elseif($company->countryname) $location = $company->countryname;*/
					if(isset($company->multicity)) $location=$company->multicity;
					else $location="";
				$contents .=  '<small>'.JText::_('JS_LOCATION').': '.$location.'</small><br />';
			}	
			if ($posteddate == 1) $contents .=  "<small>".JText::_('JS_POSTED').": ".date($dateformat,strtotime($company->created))."</small><br /><br />";
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
						jQuery("marquee#mod_jsfeaturedcompanies").marquee("pointer").mouseover(function () {
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
			$contents =  '<marquee id="mod_jsfeaturedcompanies" direction="up" scrollamount="1" onmouseover="this.stop();" onmouseout="this.start()";>'.$contents.'</marquee>';
			$contents =$top.$contents.'<br clear="all">';
		}





	}else{ // vertical listing
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
		foreach ($companies as $company) {
			$isodd = 1 - $isodd;
			if ($count == 1){
				$contents .=  '<tr id="mc_field_row" class="'.$trclass[$isodd].'">';
			}	
			
			$c_l=JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_company&vm=1&md=' .$company->aliasid .'&Itemid='.$itemid);
			if ($logo == 1){
				$contents .= '<td width="%"><span id="themeanchor"><a class="anchor" href='.$c_l.'>';
					if ($company->logofilename != '')
						$contents .= '<img width="'.$logowidth.'" height="'.$logoheight.'" src="'.$datadirectory.'/data/employer/comp_'.$company->id.'/logo/'.$company->logofilename.'" />';
					else
						$contents .= '<img width="'.$logowidth.'" height="'.$logoheight.'" src="components/com_jsjobs/images/blank_logo.png" />';
				$contents .= '</a></span></td>';								
			}	
			
			$contents .=  '<td width="'.$colwidth.'%">';
			if ($companyname == 1) $contents .= '<span id="themeanchor"><a class="anchor" href='.$c_l.'>'.$company->name.'</a></span>';
			if ($category == 1) $contents .= '<br /><small>'.$categorytitle.': '.$company->cat_title.'</small><br />';	
			if ($showlocation == 1){
					/*if($company->cityname) $location = $company->cityname;
					elseif($company->city) $location = $company->city;
					elseif($company->countyname) $location = $company->countyname;
					elseif($company->county) $location = $company->county;
					elseif($company->statename) $location = $company->statename;
					elseif($company->state) $location = $company->state;
					elseif($company->countryname) $location = $company->countryname;*/
					if(isset($company->multicity)) $location=$company->multicity;
					else $location="";
				$contents .= '<small>'.$locationtitle.': '.$location.'</small><br />';
			}	
			if ($posteddate == 1) $contents .= "<small>".$postedtitle.": ".date($dateformat,strtotime($company->created))."</small><br /><br />";
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
						jQuery("marquee#mod_jsfeaturedcompanies").marquee("pointer").mouseover(function () {
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
			
			$contents =  '<marquee id="mod_jsfeaturedcompanies" scrollamount="1" onmouseover="this.stop();" onmouseout="this.start()";>'.$contents.'</marquee>';
		}
	}
	echo $contents;	

}
?>

