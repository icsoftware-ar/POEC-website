<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , info@burujsolutions.com
				www.joomsky.com, ahmad@joomsky.com
 * Created on:	Aug 25, 2010
 ^
 + Project: 		JS Jobs 
 * File Name:	Pplugin/jsfeaturedjobs.php
 ^ 
 * Description: Plugin for JS Jobs
 ^ 
 * History:		NONE
 ^ 
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
 
// Import Joomla! Plugin library file
jimport('joomla.plugin.plugin');
 if(!defined('DS')){
   define('DS',DIRECTORY_SEPARATOR);
}

//The Content plugin Loadmodule
class plgContentJSFeaturedJobs extends JPlugin
{		//for joomla 1.5
        public function onPrepareContent( &$row, &$params, $page=0 )
        {
                if ( JString::strpos( $row->text, 'jsfeaturedjobs' ) === false ) {
                        return true;
                }

              // expression to search for
                $regex = '/{jsfeaturedjobs\s*.*?}/i';
                if ( !$this->params->get( 'enabled', 1 ) ) {
                        $row->text = preg_replace( $regex, '', $row->text );
                        return true;
                }
                preg_match_all( $regex, $row->text, $matches );
                $count = count( $matches[0] );
                if ( $count ) {
                        // Get plugin parameters
                        $style = $this->params->def( 'style', -2 );
                        $this->_process( $row, $matches, $count, $regex, $style );
                }
        }
//	 for joomla 1.6
        public function onContentPrepare( $context, &$row, &$params, $page=0 )
        {
                if ( JString::strpos( $row->text, 'jsfeaturedjobs' ) === false ) {
                        return true;
                }

              // expression to search for
                $regex = '/{jsfeaturedjobs\s*.*?}/i';
                if ( !$this->params->get( 'enabled', 1 ) ) {
                        $row->text = preg_replace( $regex, '', $row->text );
                        return true;
                }
                preg_match_all( $regex, $row->text, $matches );
                $count = count( $matches[0] );
                if ( $count ) {
                        // Get plugin parameters
                        $style = $this->params->def( 'style', -2 );
                        $this->_process( $row, $matches, $count, $regex, $style );
                }
        }

        protected function _process( &$row, &$matches, $count, $regex, $style )
        {
                for ( $i=0; $i < $count; $i++ )
                {
                        $load = str_replace( 'jsfeaturedjobs', '', $matches[0][$i] );
                        $load = str_replace( '{', '', $load );
                        $load = str_replace( '}', '', $load );
                        $load = trim( $load );
 
                        $modules       = $this->_load( $load, $style );
                        $row->text         = preg_replace( '{'. $matches[0][$i] .'}', $modules, $row->text );
                }
                $row->text = preg_replace( $regex, '', $row->text );
        }

        protected function _load( $position, $style=-2 )
        {
                $document      = &JFactory::getDocument();
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
                $renderer      = $document->loadRenderer('module');
                $params                = array('style'=>$style);
                $db = JFactory::getDBO();
				$lang = & JFactory :: getLanguage();
				$lang->load('com_jsjobs');
				
				// Language variable start
				$companytitle = JText::_('JS_COMPANY');
				$titlecaption = JText::_('JS_TITLE');
				$categorytitle = JText::_('JS_CATEGORY');
				$subcategorytitle = JText::_('JS_SUB_CATEGORY');
				$locationtitle = JText::_('JS_LOCATION');
				$postedtitle = JText::_('JS_POSTED');
				// Language variable end
				
				$curdate = date('Y-m-d H:i:s');
				$noofjobs = $this->params->get('noofjobs');
				$theme = $this->params->get('theme');
				/** scs */
				$componentAdminPath = JPATH_ADMINISTRATOR.'/components/com_jsjobs';
				$componentPath =  JPATH_SITE.'/components/com_jsjobs';
				require_once $componentPath.'/models/mpjsjobs.php';
				$config = array( 'table_path' => $componentAdminPath.'/tables');
				$model = new JSJobsModelMpJsjobs($config);
				$result = $model->getFeaturedJobs($noofjobs,$theme);
				$jobs = $result[0];
				$trclass = $result[1];	
				$dateformat = $result[2];
				$datadirectory = $result[3];	
				
				/** sce */
				$headlinecss = 'contentheading';
				$sortlinks = 'sectiontableheader';
				if ($theme == 1){ // js jobs theme
					$trclass = array("odd", "even");
					$headlinecss = 'pageheadline';
					$sortlinks = 'sortlnks';
				}
				//scs				
				if($this->params->get('Itemid')) $itemid = $this->params->get('Itemid');			
				else  $itemid =  JRequest::getVar('Itemid');
				//sce

				$contents = '';
				$noofcols = $this->params->get('noofcols');
				$listingstyle = $this->params->get('listingstyle');
				$title = $this->params->get('title');
				$shtitle = $this->params->get('shtitle');
				$company = $this->params->get('company');
				$category= $this->params->get('category');
				$subcategory= $this->params->get('subcategory');
				$showlocation= $this->params->get('location');
				$posteddate= $this->params->get('posteddate');
				$separator= $this->params->get('separator');
				
				$logo= $this->params->get('logo');
				$logowidth= $this->params->get('logowidth');
				$logoheight= $this->params->get('logoheight');
				$sliding= $this->params->get('sliding','1');
				$consecutivesliding= $this->params->get('consecutivesliding','3');
				

				$colwidth = round(100 / $noofcols);
				if (isset($jobs)) { 
					if($listingstyle == 1){ //vertical listing
		
						$contents =  '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">';
							$isodd = 1;

									if ($shtitle == 1){
										$contents .=  '<tr id="mc_field_row" class="'.$trclass[$isodd].'">';
										$contents .=  '<td  height="25" class="'.$headlinecss.'" colspan="8" align="center">'. $title.'</td>';
										$contents .=  '</tr><tr><td colspan="8" height="2"></td></tr>';
									}	
									$contents .=  '<tr id="mc_field_row" class="'.$trclass[$isodd].'">';
										$contents .=  '<th align="center" width="20%">'.$titlecaption .'</th>';
										if ($company == 1)$contents .=  '<th align="center" width="20%">'.$companytitle.'</th>';
										if ($category == 1)$contents .=  '<th align="center" width="15%">'.$categorytitle.'</th>';
										if ($subcategory == 1)$contents .=  '<th align="center" width="15%">'.$subcategorytitle.'</th>';
										if ($showlocation == 1)$contents .=  '<th align="center" width="20%">'.$locationtitle.'</th>';
										if ($posteddate == 1)$contents .=  '<th align="center" width="15%">'.$postedtitle.'</th>';
									$contents .=  '</tr>';
									
									foreach($jobs as $job)	{ 
										$contents .=  '<tr id="mc_field_row" class="'.$trclass[$isodd].'">';
													$j_l=JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_job&vj=5&oi=' . $job->aliasid . '&Itemid='.$itemid);
													$c_l=JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_company&vm=5&md=' .$job->companyaliasid .  '&Itemid='.$itemid);
											      
													$contents .=  '<td ><span id="themeanchor"><a class="anchor" href='.$j_l.'>';
													$contents .=   $job->title.'</a></span></td>';
													if ($company == 1) $contents .=  '<td ><span id="themeanchor"><a class="anchor" href='.$c_l.'>'.$job->companyname.'</a></span></td>';
													if ($category == 1)$contents .=  '<td >'.$job->cat_title.'</td>';
													if ($subcategory == 1)$contents .=  '<td >'.$job->subcat_title.'</td>';
													if ($showlocation == 1){
														$contents .=  '<td >';
/*
															if($job->cityname) $location = $job->cityname;
															elseif($job->city) $location = $job->city;
															elseif($job->countyname) $location = $job->countyname;
															elseif($job->county) $location = $job->county;
															elseif($job->statename) $location = $job->statename;
															elseif($job->state) $location = $job->state;
															elseif($job->countryname) $location = $job->countryname;
*/
														if(isset($job->multicity)) $location=$job->multicity;
														else $location="";
														$contents .=  $location.'</td>';
													}	
													if ($posteddate == 1)$contents .=  '<td >'.date($dateformat,strtotime($job->created)).'</td>';
													$isodd=1-$isodd ;
										 $contents .=  '</tr>';
									}
							$contents .=  '<tr height="6"></tr>	';
						$contents .=  '</table>	';
						//scs
						if ($sliding == 1) {
							for ($a = 0; $a < $consecutivesliding; $a++){
								$contents .= $contents;
								}
								$contents .= '
								<script type="text/javascript" language=Javascript>
									jQuery(document).ready(function(){
										jQuery("marquee#plg_jsfeaturedjobs").marquee("pointer").mouseover(function () {
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
							$contents =  '<marquee id="plg_jsfeaturedjobs"  direction="up" scrollamount="1" onmouseover="this.stop();" onmouseout="this.start()";>'.$contents.'</marquee>';
							$contents =$contents.'<br clear="all">';
						}
						
						//sce
						
						
					}else{ // horizontal listing
					   $contents = '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">';
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
							if ($company == 1) $contents .=  '<small>'.$companytitle.': <span id="themeanchor"><a class="anchor" href='.$c_l.'>'.$job->companyname.'</a></span></small><br />';	
							if ($category == 1) $contents .= '<small>'.$categorytitle.': '.$job->cat_title.'</small><br />';	
							if ($subcategory == 1) $contents .= '<small>'.$subcategorytitle.': '.$job->subcat_title.'</small><br />';
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
								if(isset($job->multicity)) $location=$job->multicity;
								else $location="";
								$contents .= '<small>'.$locationtitle.': '.$location.'</small><br />';
							}	
							if ($posteddate == 1) $contents .= "<small>".$postedtitle.": ".date($dateformat,strtotime($job->created))."</small><br /><br />";
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
						//sce
						if ($sliding == 1) {
							$tcontents = '<table cellpadding="0" cellspacing="0" border="1" width="100%" class="contentpane"> <tr>';
							for ($a = 0; $a < $consecutivesliding; $a++){
								$scontents .= '<td>'.$contents.'</td>';
							}
							$contents = $tcontents.$scontents.'</tr></table>';
								$contents .= '
								<script type="text/javascript" language=Javascript>
									jQuery(document).ready(function(){
										jQuery("marquee#plg_jsfeaturedjobs").marquee("pointer").mouseover(function () {
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
							$contents =  '<marquee id="plg_jsfeaturedjobs" scrollamount="1" onmouseover="this.stop();" onmouseout="this.start()";>'.$contents.'</marquee>';
						}
						//sce
					}	
				}

               return $contents;
        }
}



?>
