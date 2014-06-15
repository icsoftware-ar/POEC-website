<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , info@burujsolutions.com
			www.joomsky.com, ahmad@joomsky.com
 * Created on:	Dec 10, 2009
 ^
 + Project: 		JS Jobs 
 * File Name:	Pplugin/jstopjobs.php
 ^ 
 * Description: Plugin for JS Jobs
 ^ 
 * History:		1.0.1 - Nov 27, 2010
 ^ 
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
 
// Import Joomla! Plugin library file
jimport('joomla.plugin.plugin');
 if(!defined('DS')){
   define('DS',DIRECTORY_SEPARATOR);
}

//The Content plugin Loadmodule
class plgContentJSHotJobs extends JPlugin
{			// for joomla 1.5
			public function onPrepareContent( &$row, &$params, $page=0 )
			{
                if ( JString::strpos( $row->text, 'jshotjobs' ) === false ) {
                        return true;
                }

              // expression to search for
                $regex = '/{jshotjobs\s*.*?}/i';
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
                if ( JString::strpos( $row->text, 'jshotjobs' ) === false ) {
                        return true;
                }

              // expression to search for
                $regex = '/{jshotjobs\s*.*?}/i';
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
                        $load = str_replace( 'jshotjobs', '', $matches[0][$i] );
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
				$categorytitle = JText::_('JS_CATEGORY');
				$subcategorytitle = JText::_('JS_SUB_CATEGORY');
				$typetitle = JText::_('JS_TYPE');
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
				$result = $model->getHotJobs($noofjobs,$theme);
				$jobs = $result[0];
				$trclass = $result[1];	
				$dateformat = $result[2];	
				/** sce */
				
				//scs				
				if($this->params->get('Itemid')) $itemid = $this->params->get('Itemid');			
				else  $itemid =  JRequest::getVar('Itemid');
				//sce

				$contents = '';
				$noofcols = $this->params->get('noofcols');
				$shtitle = $this->params->get('shtitle');
				$title = $this->params->get('title');
				$company = $this->params->get('company');
				$category= $this->params->get('category');
				$subcategory= $this->params->get('subcategory');
				$jobtype= $this->params->get('jobtype');
				$posteddate= $this->params->get('posteddate');
				$separator= $this->params->get('separator');
				//sce
				$sliding= $this->params->get('sliding','1');
				$consecutivesliding= $this->params->get('consecutivesliding','3');
				$slidingdirection= $this->params->get('slidingdirection','1'); // 0 = left  , 1=up
				//sce
				$colwidth = 100 / $noofcols;
				if (isset($jobs)) { 
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
					foreach ($jobs as $job) {
					    $isodd = 1 - $isodd;
						if ($count == 1){
							$contents .=  '<tr id="mc_field_row" class="'.$trclass[$isodd].'">';
						}	
						$j_l=JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_job&vj=5&oi=' . $job->aliasid . '&Itemid='.$itemid);
						
						$contents .=  '<td width="'.$colwidth.'%">'
							. '<span id="themeanchor"><a class="anchor" href='.$j_l.'><u><strong>'
					        . $job->title . '</strong></u></a></span><br />';
						if ($company == 1) $contents .=  '<small>'.$companytitle.': <span id="themeanchor"><a class="anchor" href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_company&vm=5&md=' .$job->companyaliasid .  '&Itemid='.$itemid.'">'.$job->companyname.'</a></span></small><br />';	
						if ($category == 1) $contents .= '<small>'.$categorytitle.': '.$job->cat_title.'</small><br />';	
						if ($subcategory == 1) $contents .= '<small>'.$subcategorytitle.': '.$job->subcat_title.'</small><br />';
						if ($jobtype == 1) $contents .= '<small>'.$typetitle.': '.$job->jobtypetitle.'</small><br />';	
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
						}	
						$contents .= '</tr>';
					}	
					$contents .= '</table>';
				}
				
				if ($sliding == 1) {                    
					if($slidingdirection == 1 ){
						for ($a = 0; $a < $consecutivesliding; $a++){
							$contents .= $contents;
						}
						$contents =  '<marquee id="plg_hotjsjobs"  direction="up" scrollamount="1" onmouseover="this.stop();" onmouseout="this.start()";>'.$contents.'</marquee>';
						$contents = $contents.'<br clear="all">';
					}else{
						$tcontents = '<table cellpadding="0" cellspacing="0" border="1" width="100%" class="contentpane"> <tr>';
						$scontents="";
						for ($a = 0; $a < $consecutivesliding; $a++){
							$scontents .= '<td>'.$contents.'</td>';
						}
						$contents = $tcontents.$scontents.'</tr></table>';
						$contents =  '<marquee  id="plg_hotjsjobs" scrollamount="1" onmouseover="this.stop();" onmouseout="this.start()";>'.$contents.'</marquee>';
					}	
					$contents .= '
					<script type="text/javascript" language=Javascript>
						jQuery(document).ready(function(){
							jQuery("marquee#plg_hotjsjobs").marquee("pointer").mouseover(function () {
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
               return $contents;
        }
}



?>
