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

$title = $params->get('title');
$shtitle = $params->get('shtitle');
$employer = $params->get('employer');
$jobseeker = $params->get('jobseeker');
$jobs = $params->get('jobs');
$companies = $params->get('companies');
$activejobs= $params->get('activejobs');
$resumes= $params->get('resumes');
$todaystats= $params->get('todaystats');

//$noofcols= $params->get('noofcols');
//scs
$sliding= $params->get('sliding','1');
$consecutivesliding= $params->get('consecutivesliding','3');
$slidingdirection= $params->get('slidingdirection','1'); // 0 = left  , 1=up
//sce
$noofcols=1;
$separator= $params->get('separator');
$colwidth = round(100 / $noofcols);
$itemid =  JRequest::getVar('Itemid');
$curdate = date('Y-m-d H:i:s');
/*
$css = 'jsjobs01.css'; 
$document =& JFactory::getDocument();
$document->addStyleSheet('components/com_jsjobs/css/'.$css);
*/

	$componentAdminPath = JPATH_ADMINISTRATOR.'/components/com_jsjobs';
	$componentPath =  'components/com_jsjobs';
	require_once $componentPath.'/models/mpjsjobs.php';
	$config = array( 'table_path' => $componentAdminPath.'/tables');
	$divclass=array('odd','even');

	$document->addScript('components/com_jsjobs/js/jquery.marquee.js');
	
	$model = new JSJobsModelMpJsjobs($config);
	$stats = $model->mpGetstats($employer,$jobseeker,$jobs,$companies,$activejobs,$resumes);
        $lang = & JFactory :: getLanguage();
        $lang->load('com_jsjobs');
	   $contents = '<div id="modplugwraper">';
		$isodd = 1;
		$count = 1;
		if ($shtitle == 1){
			$contents .=  '<div id="modplugtitle">';
			$contents .=  '<h2><u>'.$title.'</u></h2>';
			if ($separator == 1) $contents .= '<hr style="border:dashed #C0C0C0; border-width:1px 0 0 0; height:0;line-height:0px;font-size:0;margin:0;padding:0;">';
			$contents .= '</div>';
		}
                if(isset ($stats['employer'])){
                    $contents .=  '<div class="'.$divclass[0].'">';
                            $contents .= '<strong >'. JText::_('JS_EMPLOYERS'). '('.$stats['employer'].')'. '</strong>';
                    $contents .= '</div>';
                }
                if(isset ($stats['jobseeker'])){
                    $contents .=  '<div class="'.$divclass[1].'">';
                            $contents .= '<strong>'. JText::_('JS_JOBSEEKERS'). '('.$stats['jobseeker'].')'. '</strong>';
                    $contents .= '</div>';
                }
                if(isset ($stats['totaljobs'])){
                    $contents .=  '<div class="'.$divclass[0].'">';
                            $contents .= '<strong>'. JText::_('JS_JOBS'). '('.$stats['totaljobs'].')'. '</strong>';
                    $contents .= '</div>';
                }
                if(isset ($stats['totalcompanies'])){
                    $contents .=  '<div class="'.$divclass[1].'">';
                            $contents .= '<strong>'. JText::_('JS_COMPANIES'). '('.$stats['totalcompanies'].')'. '</strong>';
                    $contents .= '</div>';
                }
                if(isset ($stats['tatalactivejobs'])){
                    $contents .=  '<div class="'.$divclass[0].'">';
                            $contents .= '<strong>'. JText::_('JS_ACTIVEJOBS'). '('.$stats['tatalactivejobs'].')'. '</strong>';
                    $contents .= '</div>';
                }
                if(isset ($stats['totalresume'])){
                    $contents .=  '<div class="'.$divclass[1].'">';
                            $contents .= '<strong>'. JText::_('JS_RESUMES'). '('.$stats['totalresume'].')'. '</strong>';
                    $contents .= '</div>';
                }
                if($todaystats == 1){
                    $contents .=  '<div  id="modplugtitle">';
                    $contents .=  '<h2><u>'.JText::_('JS_TODAY_STATS').'</u></h2>';
                    if ($separator == 1) $contents .= '<hr style="border:dashed #C0C0C0; border-width:1px 0 0 0; height:0;line-height:0px;font-size:0;margin:0;padding:0;">';
                    $contents .= '</div>';
                    if(isset ($stats['todyemployer'])){
                        $contents .=  '<div class="'.$divclass[0].'">';
                                $contents .= '<strong>'. JText::_('JS_EMPLOYERS'). '('.$stats['todyemployer'].')'. '</strong>';
                        $contents .= '</div>';
                    }
                    if(isset ($stats['todyjobseeker'])){
                        $contents .=  '<div class="'.$divclass[1].'">';
                                $contents .= '<strong>'. JText::_('JS_JOBSEEKERS'). '('.$stats['todyjobseeker'].')'. '</strong>';
                        $contents .= '</div>';
                    }
                    if(isset ($stats['todayjobs'])){
                        $contents .=  '<div class="'.$divclass[0].'">';
                                $contents .= '<strong>'. JText::_('JS_JOBS'). '('.$stats['todayjobs'].')'. '</strong>';
                        $contents .= '</div>';
                    }
                    if(isset ($stats['todaycompanies'])){
                        $contents .=  '<div class="'.$divclass[1].'">';
                                $contents .= '<strong>'. JText::_('JS_COMPANIES'). '('.$stats['todaycompanies'].')'. '</strong>';
                        $contents .= '</div>';
                    }
                    if(isset ($stats['todayactivejobs'])){
                        $contents .=  '<div class="'.$divclass[0].'">';
                                $contents .= '<strong>'. JText::_('JS_ACTIVEJOBS'). '('.$stats['todayactivejobs'].')'. '</strong>';
                        $contents .= '</div>';
                    }
                    if(isset ($stats['todayresume'])){
                        $contents .=  '<div class="'.$divclass[1].'">';
                                $contents .= '<strong>'. JText::_('JS_RESUMES'). '('.$stats['todayresume'].')'. '</strong>';
                        $contents .= '</div>';
                    }
                }
            $contents .= '</div>';
			if ($sliding == 1) {
				
				if($slidingdirection == 1 ){

				
				for ($a = 0; $a < $consecutivesliding; $a++){
					$contents .= $contents;
					}
				$contents =  '<marquee id="mod_jsjobstats"  direction="up" scrollamount="1" onmouseover="this.stop();" onmouseout="this.start()";>'.$contents.'</marquee>';
				$contents = $contents.'<br clear="all">';
		
			}else{
				$scontents="";
				$tcontents = '<table cellpadding="0" cellspacing="0" border="1" width="100%" class="contentpane"> <tr>';
				for ($a = 0; $a < $consecutivesliding; $a++){
					$scontents .= '<td>'.$contents.'</td>';
				}
				$contents = $tcontents.$scontents.'</tr></table>';
				$contents =  '<marquee id="mod_jsjobstats" scrollamount="1" onmouseover="this.stop();" onmouseout="this.start()";>'.$contents.'</marquee>';
			}
			$contents .= '
			<script type="text/javascript" language=Javascript>
				jQuery(document).ready(function(){
					jQuery("marquee#mod_jsjobstats").marquee("pointer").mouseover(function () {
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

