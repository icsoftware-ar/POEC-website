<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , info@burujsolutions.com
			www.joomsky.com, ahmad@joomsky.com
 * Created on:	Dec 10, 2009
 ^
 + Project: 		JS Jobs 
 * File Name:	Pplugin/jsnewestresume.php
 ^ 
 * Description: Plugin for JS Jobs
 ^ 
 * History:		1.0.1 - Nov 27, 2010
 ^ 
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
 
// Import Joomla! Plugin library file
jimport('joomla.plugin.plugin');
 
//The Content plugin Loadmodule
class plgContentJSNewestResume extends JPlugin
{		// for joomla 1.5
		public function onPrepareContent( &$row, &$params, $page=0 )
        {
                if ( JString::strpos( $row->text, 'jsnewestresume' ) === false ) {
                        return true;
                }

              // expression to search for
                $regex = '/{jsnewestresume\s*.*?}/i';
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
                if ( JString::strpos( $row->text, 'jsnewestresume' ) === false ) {
                        return true;
                }

              // expression to search for
                $regex = '/{jsnewestresume\s*.*?}/i';
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
                        $load = str_replace( 'jsnewestresume', '', $matches[0][$i] );
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
				$highesteducationtitle = JText::_('JS_HEIGHTESTEDUCATION');
				$salarytitle = JText::_('JS_SALARY_RANGE');
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

				$noofresumes = $this->params->get('noofresumes');
				$theme = $this->params->get('theme');
				$contents = '';
				$noofcols = $this->params->get('noofcols', 3);
				$shtitle = $this->params->get('shtitle');
				$title = $this->params->get('title');
				$applicationtitle = $this->params->get('applicationtitle', 1);
				$nationality = $this->params->get('nationality', 1);
				$gender = $this->params->get('gender', 1);
				$available = $this->params->get('available', 1);

				$category = $this->params->get('category', 1);
				$subcategory = $this->params->get('subcategory', 1);
				$jobtype = $this->params->get('jobtype', 1);
				$salaryrange = $this->params->get('salaryrange', 0);
				$highesteducation = $this->params->get('highesteducation', 0);
				$experience = $this->params->get('experience', 0);
				$posteddate = $this->params->get('posteddate', 1);
				$separator = $this->params->get('separator', 1);
				$colwidth = 100 / $noofcols;
				//sce
				$sliding= $this->params->get('sliding','1');
				$consecutivesliding= $this->params->get('consecutivesliding','3');
				$slidingdirection= $this->params->get('slidingdirection','1'); // 0 = left  , 1=up
				//sce
				
				/** scs */
				if($this->params->get('Itemid')) $itemid = $this->params->get('Itemid');			
				else  $itemid =  JRequest::getVar('Itemid');
				$componentAdminPath = JPATH_ADMINISTRATOR.'/components/com_jsjobs';
				$componentPath =  JPATH_SITE.'/components/com_jsjobs';
				require_once $componentPath.'/models/mpjsjobs.php';
				$config = array( 'table_path' => $componentAdminPath.'/tables');
				$model = new JSJobsModelMpJsjobs($config);
				$result = $model->getNewestResumes($noofresumes,$theme);
				$resumes = $result[0];
				$trclass = $result[1];	
				$dateformat = $result[2];
				/** sce */
				
				if (isset($resumes)) { 
				    $contents = '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">';
					$isodd = 1;
					$count = 1;
					if ($shtitle == 1){
							$contents .=  '<tr id="mc_field_row" class="'.$trclass[$isodd].'">';
						$contents .=  '<td colspan="'.$noofcols.'">';
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
						
						$contents .=  '<td width="'.$colwidth.'%">'
							. '<span id="themeanchor"><a class="anchor" href='.$r_l.'><u><strong>'
					        . $resume->first_name.' '.$resume->last_name . '</strong></u></a></span><br />';
						if ($title == 1) $contents .= '<small>'.$applicationtitlecaption.': '.$resume->application_title.'</small><br />';	
						if ($category == 1) $contents .= '<small>'.$categorytitle.': '.$resume->cat_title.'</small><br />';	
						if ($subcategory == 1) $contents .= '<small>'.$subcategorytitle.': '.$resume->subcat_title.'</small><br />';
						if ($jobtype == 1) $contents .= '<small>'.$jobtypetitle.': '.$resume->jobtypetitle.'</small><br />';	

						if ($highesteducation == 1) $contents .= '<small>'.$highesteducationtitle.': '.$resume->educationtitle.'</small><br />';	
						if ($salaryrange == 1)	$contents .= '<small>'.$salarytitle.': '.$resume->symbol.' '.$resume->rangestart.' - '.$resume->symbol.' '.$resume->rangeend .'</small><br />';	
						if ($experience == 1) $contents .= '<small>'.$experiencetitle.': '.$resume->total_experience.'</small><br />';	
						if ($available == 1) { if ($resume->iamavailable == 1) $availabletext = $yes; else $availabletext = $no; $contents .= '<small>'.$availabletitle.': '.$availabletext.'</small><br />';}	
						if ($gender == 1) { if ($resume->gender == 1) $gendertext=$male; else $gendertext=$female; $contents .= '<small>'.$gendertitle.': '.$gendertext.'</small><br />';}	
						if ($nationality == 1) $contents .= '<small>'.$nationalitytitle.': '.$resume->countryname.'</small><br />';	

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
						}	
						$contents .= '</tr>';
					}	
					$contents .= '</table>';
				}
				//scs		
				if ($sliding == 1) {                    
					
					if($slidingdirection == 1 ){
						for ($a = 0; $a < $consecutivesliding; $a++){ 	$contents .= $contents; }
						$contents =  '<marquee id="plg_newestresume"  direction="up" scrollamount="1" onmouseover="this.stop();" onmouseout="this.start()";>'.$contents.'</marquee>';
						$contents = $contents.'<br clear="all">';
					}else{
						$scontents="";	
						$tcontents = '<table cellpadding="0" cellspacing="0" border="1" width="100%" class="contentpane"> <tr>';
						for ($a = 0; $a < $consecutivesliding; $a++){ $scontents .= '<td>'.$contents.'</td>'; }
						$contents = $tcontents.$scontents.'</tr></table>';
						$contents =  '<marquee  id="plg_newestresume" scrollamount="1" onmouseover="this.stop();" onmouseout="this.start()";>'.$contents.'</marquee>';
					}
					$contents .= '
					<script type="text/javascript" language=Javascript>
						jQuery(document).ready(function(){
							jQuery("marquee#plg_newestresume").marquee("pointer").mouseover(function () {
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
				//sce

               return $contents;
        }
}



?>
