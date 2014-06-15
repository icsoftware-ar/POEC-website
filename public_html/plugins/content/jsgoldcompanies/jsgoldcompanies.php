<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , info@burujsolutions.com
				www.joomsky.com, ahmad@joomsky.com
 * Created on:	Aug 25, 2010
 ^
 + Project: 		JS Jobs 
 * File Name:	Pplugin/jsgoldcompanies.php
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
class plgContentjsgoldcompanies extends JPlugin
{		// for joomla 1.5
		public function onPrepareContent( &$row, &$params, $page=0 )
        {
                if ( JString::strpos( $row->text, 'jsgoldcompanies' ) === false ) {
                        return true;
                }

              // expression to search for
                $regex = '/{jsgoldcompanies\s*.*?}/i';
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
                if ( JString::strpos( $row->text, 'jsgoldcompanies' ) === false ) {
                        return true;
                }

              // expression to search for
                $regex = '/{jsgoldcompanies\s*.*?}/i';
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
                        $load = str_replace( 'jsgoldcompanies', '', $matches[0][$i] );
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
				$nametitle = JText::_('JS_NAME');
				$categorytitle = JText::_('JS_CATEGORY');
				$locationtitle = JText::_('JS_LOCATION');
				$postedtitle = JText::_('JS_POSTED');
				// Language variable end
				
				$curdate = date('Y-m-d H:i:s');
				$noofcompanies = $this->params->get('noofcompanies');
				$theme = $this->params->get('theme');
				/** scs */
				$componentAdminPath = JPATH_ADMINISTRATOR.'/components/com_jsjobs';
				$componentPath =  JPATH_SITE.'/components/com_jsjobs';
				require_once $componentPath.'/models/mpjsjobs.php';
				$config = array( 'table_path' => $componentAdminPath.'/tables');
				$model = new JSJobsModelMpJsjobs($config);
				$result = $model->getGoldCompanies($noofcompanies,$theme);
				$companies = $result[0];
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
				// params
				$noofcols = $this->params->get('noofcols');
				$listingstyle = $this->params->get('listingstyle');
				$title = $this->params->get('title');
				$shtitle = $this->params->get('shtitle');
				$category= $this->params->get('category');
				$showlocation= $this->params->get('location');
				$posteddate= $this->params->get('posteddate');
				$separator= $this->params->get('separator');
				
				$companyname= $this->params->get('companyname');
				$logo= $this->params->get('logo');
				$logowidth= $this->params->get('logowidth');
				$logoheight= $this->params->get('logoheight');
				$colwidth = round(100 / $noofcols);
				//sce
				$sliding= $this->params->get('sliding','1');
				$consecutivesliding= $this->params->get('consecutivesliding','3');
				//sce
				
				if (isset($companies)) { 
					if($listingstyle == 1){ //horizontal listing
						$contents =  '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">';
							$isodd = 1;

									if ($shtitle == 1){
										$contents .=  '<tr id="mc_field_row" class="'.$trclass[$isodd].'">';
										$contents .=  '<td  height="25" class="'.$headlinecss.'" colspan="8" align="center">'. $title.'</td>';
										$contents .=  '</tr><tr><td colspan="8" height="2"></td></tr>';
									}	
									$contents .=  '<tr id="mc_field_row" class="'.$trclass[$isodd].'">';
										$contents .=  '<th align="center" width="35%">'.$nametitle .'</th>';
										if ($category == 1)$contents .=  '<th align="center" width="20%">'.$categorytitle.'</th>';
										if ($showlocation == 1)$contents .=  '<th align="center" width="25%">'.$locationtitle.'</th>';
										if ($posteddate == 1)$contents .=  '<th align="center" width="20%">'.$postedtitle.'</th>';
									$contents .=  '</tr>';
									
									foreach($companies as $company)	{ 
										$c_l=JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_company&vm=1&md=' .$company->aliasid .'&Itemid='.$itemid);
										
										$contents .=  '<tr id="mc_field_row" class="'.$trclass[$isodd].'">';
													$contents .=  '<td ><span id="themeanchor"><a class="anchor" href='.$c_l.'>'.$company->name.'</a></span></td>';
													if ($category == 1)$contents .=  '<td >'.$company->cat_title.'</td>';
													if ($showlocation == 1){
														$contents .=  '<td >';
/*
															if($company->cityname) $location = $company->cityname;
															elseif($company->city) $location = $company->city;
															elseif($company->countyname) $location = $company->countyname;
															elseif($company->county) $location = $company->county;
															elseif($company->statename) $location = $company->statename;
															elseif($company->state) $location = $company->state;
															elseif($company->countryname) $location = $company->countryname;
*/
														if(isset($company->multicity)) $location=$company->multicity;
														else $location="";
														$contents .=  $location.'</td>';
													}	
													if ($posteddate == 1)$contents .=  '<td >'.date($dateformat,strtotime($company->created)).'</td>';
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
										jQuery("marquee#plg_jsgoldcompanies").marquee("pointer").mouseover(function () {
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
								
							$contents =  '<marquee id="plg_jsgoldcompanies"  direction="up" scrollamount="1" onmouseover="this.stop();" onmouseout="this.start()";>'.$contents.'</marquee>';
							$contents =$contents.'<br clear="all">';
						}
						
						//sce
						
					}else{ // vertical listing
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
/*
									if($company->cityname) $location = $company->cityname;
									elseif($company->city) $location = $company->city;
									elseif($company->countyname) $location = $company->countyname;
									elseif($company->county) $location = $company->county;
									elseif($company->statename) $location = $company->statename;
									elseif($company->state) $location = $company->state;
									elseif($company->countryname) $location = $company->countryname;
*/
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
									jQuery("marquee#plg_jsgoldcompanies").marquee("pointer").mouseover(function () {
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
							
							$contents =  '<marquee id="plg_jsgoldcompanies"  scrollamount="1" onmouseover="this.stop();" onmouseout="this.start()";>'.$contents.'</marquee>';
						}
						//sce
						
					}	
				}

               return $contents;
        }
}



?>
