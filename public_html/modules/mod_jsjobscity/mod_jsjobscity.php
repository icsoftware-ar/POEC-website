<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , info@burujsolutions.com
				www.joomsky.com, ahmad@joomsky.com
 * Created on:	Dec 30, 2010
 ^
 + Project: 		JS Jobs 
 * File Name:	module/jsjobsstates.php
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

$noofrecord = $params->get('noofjobs', 10);
if($noofrecord>100) $noofrecord=100;
$showonlycityhavejobs = $params->get('schj', 1);
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
$result = $model->getJobsCity($showonlycityhavejobs,$theme,$noofrecord);
$cities = $result[0];
$trclass = $result[1];
$dateformat = $result[2];	
$contents = '';
/** sce */

    $contents .=  '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">';
	$isodd = 0;
	$count = 1;
        $contents .=  '<tr id="mc_field_row" class="'.$trclass[$isodd].'">';
if ($cities) { 

	foreach ($cities as $city) {
		
			$lnks = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=listnewestjobs&city='. $city->cityid .'&lt=1&Itemid='.$itemid; 
			$lnks = JRoute::_($lnks);
			$contents .=  '<td width="'.$colwidth.'"><span id="themeanchor"><a class="anchor" href="'.$lnks.'" >'.$city->cityname;
					 $contents .=  ' ('. $city->totaljobsbycity.')';
					$contents .=  '</a></span></td>';
			if ($count == $colperrow){
                                $isodd = 1 - $isodd;
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
			$contents =  '<marquee id="mod_jsjobscity"  direction="up" scrollamount="1" onmouseover="this.stop();" onmouseout="this.start()";>'.$contents.'</marquee>';
			$contents = $contents.'<br clear="all">';
		}else{
			
			$tcontents = '<table cellpadding="0" cellspacing="0" border="1" width="100%" class="contentpane"> <tr>';
			$scontents = '';
			for ($a = 0; $a < $consecutivesliding; $a++){
				$scontents .= '<td>'.$contents.'</td>';
			}
			$contents = $tcontents.$scontents.'</tr></table>';
			$contents =  '<marquee id="mod_jsjobscity" scrollamount="1" onmouseover="this.stop();" onmouseout="this.start()";>'.$contents.'</marquee>';
		}
		$contents .= '
		<script type="text/javascript" language=Javascript>
			jQuery(document).ready(function(){
				jQuery("marquee#mod_jsjobscity").marquee("pointer").mouseover(function () {
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
?>

