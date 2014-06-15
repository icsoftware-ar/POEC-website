<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , info@burujsolutions.com
				www.joomsky.com, ahmad@joomsky.com
 * Created on:	Aug 25, 2010
 ^
 + Project: 		JS Jobs 
 * File Name:	Pplugin/jsgoldjobs.php
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
class plgContentJSGoldResumes extends JPlugin
{				// for joomla 1.5
		public function onPrepareContent( &$row, &$params, $page=0 )
        {
                if ( JString::strpos( $row->text, 'jsgoldresumes' ) === false ) {
                        return true;
                }

              // expression to search for
                $regex = '/{jsgoldresumes\s*.*?}/i';
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
		// for joomla 1.6
		public function onContentPrepare( $context, &$row, &$params, $page=0 )
        {
                if ( JString::strpos( $row->text, 'jsgoldresumes' ) === false ) {
                        return true;
                }

              // expression to search for
                $regex = '/{jsgoldresumes\s*.*?}/i';
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
                        $load = str_replace( 'jsgoldresumes', '', $matches[0][$i] );
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
				$applicationtitlecaption = JText::_('JS_TITLE');
				$nametitle = JText::_('JS_NAME');
				$categorytitle = JText::_('JS_CATEGORY');
				$subcategorytitle = JText::_('JS_SUB_CATEGORY');
				$jobtypetitle = JText::_('JS_WORK_PREFERENCE');
				$experiencetitle = JText::_('JS_EXPERIENCE');
				$availabletitle = JText::_('JS_AVAILABLE');
				$gendertitle = JText::_('JS_GENDER');
				$nationalitytitle = JText::_('JS_NATIONALITY');
				$locationtitle = JText::_('JS_LOCATION');
				$postedtitle = JText::_('JS_POSTED');
				$yes = JText::_('JS_YES');
				$no = JText::_('JS_NO');
				$male = JText::_('JS_MALE');
				$female = JText::_('JS_FEMALE');
				// Language variable end
				
				$curdate = date('Y-m-d H:i:s');
				$noofresumes = $this->params->get('noofresumes');
				$theme = $this->params->get('theme');
				/** scs */
				$componentAdminPath = JPATH_ADMINISTRATOR.'/components/com_jsjobs';
				$componentPath = JPATH_SITE.'/components/com_jsjobs';
				require_once $componentPath.'/models/mpjsjobs.php';
				$config = array( 'table_path' => $componentAdminPath.'/tables');
				$model = new JSJobsModelMpJsjobs($config);
				$result = $model->getGoldResumes($noofresumes,$theme);
				$resumes = $result[0];
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
				$applicationtitle = $this->params->get('applicationtitle');
				$name = $this->params->get('name');
				$category= $this->params->get('category');
				$subcategory= $this->params->get('subcategory');
				$jobtype = $this->params->get('jobtype');
				$experience = $this->params->get('experience');
				$available = $this->params->get('available');
				$gender = $this->params->get('gender');
				$nationality = $this->params->get('nationality');
				$showlocation= $this->params->get('location');
				$posteddate= $this->params->get('posteddate');
				$separator= $this->params->get('separator');
				
				$photo= $this->params->get('photo');
				$photowidth= $this->params->get('photowidth');
				$photoheight= $this->params->get('photoheight');
				//scs
				$sliding= $this->params->get('sliding','1');
				$consecutivesliding= $this->params->get('consecutivesliding','3');
				//sce
				
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
				if (isset($resumes)) { 
					if($listingstyle == 1){ //vertical listing
						$contents =  '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">';
							$isodd = 1;

									if ($shtitle == 1){
										$contents .=  '<tr id="mc_field_row" class="'.$trclass[$isodd].'">';
										$contents .=  '<td  height="25" class="'.$headlinecss.'" colspan="'.$totalcols.'" align="center">'. $title.'</td>';
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
														
															if(isset($location))$contents .=  $location;
														$contents .=  '</td>';
													}	
													if ($posteddate == 1)$contents .=  '<td >'.date($dateformat,strtotime($resume->create_date)).'</td>';
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
										jQuery("marquee#plg_jsgoldresumes").marquee("pointer").mouseover(function () {
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
								
							$contents =  '<marquee id="plg_jsgoldresumes"  direction="up" scrollamount="1" onmouseover="this.stop();" onmouseout="this.start()";>'.$contents.'</marquee>';
							$contents =$contents.'<br clear="all">';
						}
						
						//sce
						
					}else{ // horizontal listing
					   $contents = '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">';
						$isodd = 1;
						$count = 1;
						if ($shtitle == 1){
							$contents .=  '<tr id="mc_field_row" class="'.$trclass[$isodd].'">';
							if ($photo == 1) $colspan = $noofcols * 2; else $colspan = $noofcols;
							$contents .=  '<td colspan="'.$colspan.'">';
							$contents .=  '<h2><u>'.$title.'</u></h2>';	
							if ($separator == 1) $contents .= '<hr style="border:dashed #C0C0C0; border-width:1px 0 0 0; height:0;line-height:0px;font-size:0;margin:0;padding:0;">';
							$contents .= '</td>';
							$contents .= '</tr>';
						}	
						foreach ($resumes as $resume) {
						    $isodd = 1 - $isodd;
							if ($count == 1){
								$contents .=  '<tr id="mc_field_row" class="'.$trclass[$isodd].'">';
							}	
							$r_l=JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=view_resume&vm=3&rd='. $resume->aliasid . '&Itemid='.$itemid);		
							
							if ($photo == 1){
								$contents .= '<td width="%"><span id="themeanchor"><a class="anchor" href='.$r_l.'>';
									if ($resume->photo != '')
										$contents .= '<img width="'.$photowidth.'" height="'.$photoheight.'" src="'.$datadirectory.'/data/jobseeker/resume_'.$resume->id.'/photo/'.$resume->photo.'" />';
									else
										$contents .= '<img width="'.$photowidth.'" height="'.$photoheight.'" src="components/com_jsjobs/images/blank_logo.png" />';
								$contents .= '</a></span></td>';								
							}	
							$contents .=  '<td width="'.$colwidth.'%">';
							if ($applicationtitle == 1) $contents .=  '<small>'.$applicationtitlecaption.': <span id="themeanchor"><a class="anchor" href='.$r_l.'>'.$resume->application_title.'</a></span></small><br>';
							if ($name == 1) $contents .=  '<small>'.$nametitle.': <span id="themeanchor"><a class="anchor" href='.$r_l.'>'.$resume->first_name.' '.$resume->last_name.'</a></span></small><br>';
							if ($category == 1)$contents .=  '<small >'.$categorytitle.' : '.$resume->cat_title.'</small><br>';
							if ($subcategory == 1)$contents .=  '<small >'.$subcategorytitle.' : '.$resume->subcat_title.'</small><br>';
							if ($jobtype == 1)$contents .=  '<small >'.$jobtypetitle.' : '.$resume->jobtypetitle.'</small><br>';
							if ($experience == 1)$contents .=  '<small>'.$experiencetitle.' : '.$resume->total_experience.'</small><br>';
							if ($available == 1) if($resume->iamavailable == 1) $contents .=  '<small>'.$availabletitle.' : '.$yes.'</small><br>'; else $contents .=  '<small>'.$availabletitle.' : '.$no.'</small><br>';
							if ($gender == 1) if($resume->gender == 1) $contents .=  '<small>'.$gendertitle.' : '.$male.'</small><br>'; else $contents .=  '<small>'.$gendertitle.' : '.$female.'</small><br>';
							if ($nationality == 1)$contents .=  '<small>'.$nationalitytitle.' : '.$resume->nationalityname.'</small><br>';
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
								$contents .= '<small>'.$locationtitle.': '.$location.'</small><br />';
							}	
							if ($posteddate == 1) $contents .= "<small>".$postedtitle.": ".date($dateformat,strtotime($resume->create_date))."</small><br /><br />";
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
								if ($photo == 1)$contents .= '<td></td>';
							}	
							$contents .= '</tr>';
						}	
						$contents .= '</table>';
						//sce
						if ($sliding == 1) {
							$scontents="";
							$tcontents = '<table cellpadding="0" cellspacing="0" border="1" width="100%" class="contentpane"> <tr>';
							for ($a = 0; $a < $consecutivesliding; $a++){
								$scontents .= '<td>'.$contents.'</td>';
							}
							$contents = $tcontents.$scontents.'</tr></table>';
								$contents .= '
								<script type="text/javascript" language=Javascript>
									jQuery(document).ready(function(){
										jQuery("marquee#plg_jsgoldresumes").marquee("pointer").mouseover(function () {
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
							
							$contents =  '<marquee id="plg_jsgoldresumes"  scrollamount="1" onmouseover="this.stop();" onmouseout="this.start()";>'.$contents.'</marquee>';
						}
						//sce
						
					}	
				}

               return $contents;
        }
}



?>
