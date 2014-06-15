<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , info@burujsolutions.com
				www.joomsky.com, ahmad@joomsky.com
 * Created on:	Dec 30, 2010
 ^
 + Project: 		JS Jobs 
 * File Name:	module/jsjobcategories.php
 ^ 
 * Description: Module for JS Jobs
 ^ 
 * History:		1.0.0 - Dec 30, 2010
 ^ 
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');
 if(!defined('DS')){
   define('DS',DIRECTORY_SEPARATOR);
}

//The Content plugin Loadmodule
class plgContentJSJobsCountries extends JPlugin
{
	// for joomla 1.5
	public function onPrepareContent( &$row, &$params, $page=0 )  {
		if ( JString::strpos( $row->text, 'jsjobscountries' ) === false ) {
                        return true;
		}
              // expression to search for
                $regex = '/{jsjobscountries\s*.*?}/i';
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
		// for joomla 1.5
        public function onContentPrepare( $context, &$row, &$params, $page=0 ){
			if ( JString::strpos( $row->text, 'jsjobscountries' ) === false ) {
                        return true;
		}
              // expression to search for
                $regex = '/{jsjobscountries\s*.*?}/i';
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
        protected function _process( &$row, &$matches, $count, $regex, $style )  {
                for ( $i=0; $i < $count; $i++ )
                {
                        $load = str_replace( 'jsjobscountries', '', $matches[0][$i] );
                        $load = str_replace( '{', '', $load );
                        $load = str_replace( '}', '', $load );
                        $load = trim( $load );

                        $modules       = $this->_load( $load, $style );
                        $row->text         = preg_replace( '{'. $matches[0][$i] .'}', $modules, $row->text );
                }
                $row->text = preg_replace( $regex, '', $row->text );
        }
        protected function _load( $position, $style=-2 ) {
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
		$db =& JFactory::getDBO();
		
		$noofrecord = $this->params->get('noofjobs', 10);
		if($noofrecord>100) $noofrecord=100;
		$showonlycountryhavejobs = $this->params->get('scohj', 1);
		$theme = $this->params->get('theme', 1);
		$shtitle = $this->params->get('shtitle');
		$title = $this->params->get('title');
		$colperrow = $this->params->get('colperrow',3);
		$separator= $this->params->get('separator');

		//sce
		$sliding= $this->params->get('sliding','1');
		$consecutivesliding= $this->params->get('consecutivesliding','3');
		$slidingdirection= $this->params->get('slidingdirection','1'); // 0 = left  , 1=up
		//sce

		$colwidth = Round(100/$colperrow,1);
		$colwidth = $colwidth.'%';
		/** scs */
		$componentAdminPath = JPATH_ADMINISTRATOR.'/components/com_jsjobs';
		$componentPath =  JPATH_SITE.'/components/com_jsjobs';
		require_once $componentPath.'/models/mpjsjobs.php';
		$config = array( 'table_path' => $componentAdminPath.'/tables');
		$model = new JSJobsModelMpJsjobs($config);
		$result = $model->getJobsCountry($showonlycountryhavejobs,$theme,$noofrecord);
		$countries = $result[0];
		$trclass = $result[1];	
		$dateformat = $result[2];
		/** sce */


		//scs				
		if($this->params->get('Itemid')) $itemid = $this->params->get('Itemid');			
		else  $itemid =  JRequest::getVar('Itemid');
		//sce
		if ($countries) { 
		    $contents = '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">';
			$isodd = 1;
			$count = 1;
			if ($shtitle == 1){
					$contents .=  '<tr id="mc_field_row" class="'.$trclass[$isodd].'">';
				$contents .=  '<td colspan="'.$colperrow.'">';
				$contents .=  '<h2><u>'.$title.'</u></h2>';	
				if ($separator == 1) $contents .= '<hr style="border:dashed #C0C0C0; border-width:1px 0 0 0; height:0;line-height:0px;font-size:0;margin:0;padding:0;">';
				$contents .= '</td>';
				$contents .= '</tr>';
			}	
			foreach ($countries as $country) {
					if ($count == 1){
						$isodd = 1 - $isodd;
						$contents .= '<tr id="mc_field_row" class="'.$trclass[$isodd].'">';
					}
					$lnks = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=listnewestjobs&country='. $country->countryid .'&lt=1&Itemid='.$itemid; 
					$lnks = JRoute::_($lnks);
					$contents .= '<td width="'.$colwidth.'"><span id="themeanchor"><a class="anchor" href="'.$lnks.'" >'.$country->countryname;
								$contents .= ' ('. $country->totaljobsbycountry.')';
							$contents .= '</a></span></td>';
					if ($count == $colperrow){
						$contents .= '</tr><tr id="mc_field_row" class="'.$trclass[$isodd].'">';
						$count = 0;
					}
					$count++;			
		    	}
			 if ($count-1 < $colperrow){
				for ($i = $count; $i <= $colperrow; $i++){
				    $contents .='<td></td>';
				}
				$contents .='</tr>';
			}
			
			$contents .= '</table>';
		}
	//scs		
	if ($sliding == 1) {                    
		
		if($slidingdirection == 1 ){
			for ($a = 0; $a < $consecutivesliding; $a++){ 	$contents .= $contents; }
			$contents =  '<marquee id="plg_jsjobscountry"  direction="up" scrollamount="1" onmouseover="this.stop();" onmouseout="this.start()";>'.$contents.'</marquee>';
			$contents = $contents.'<br clear="all">';
		}else{
			$scontents="";
			$tcontents = '<table cellpadding="0" cellspacing="0" border="1" width="100%" class="contentpane"> <tr>';
			for ($a = 0; $a < $consecutivesliding; $a++){ $scontents .= '<td>'.$contents.'</td>'; }
			$contents = $tcontents.$scontents.'</tr></table>';
			$contents =  '<marquee id="plg_jsjobscountry"  scrollamount="1" onmouseover="this.stop();" onmouseout="this.start()";>'.$contents.'</marquee>';
		}
		$contents .= '
		<script type="text/javascript" language=Javascript>
			jQuery(document).ready(function(){
				jQuery("marquee#plg_jsjobscountry").marquee("pointer").mouseover(function () {
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

