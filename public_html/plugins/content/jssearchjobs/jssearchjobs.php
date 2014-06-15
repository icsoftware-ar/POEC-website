<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , info@burujsolutions.com
			www.joomsky.com, ahmad@joomsky.com
 * Created on:	Dec 11, 2009
 ^
 + Project: 		JS Jobs 
 * File Name:	Pplugin/jssearchjobs.php
 ^ 
 * Description: Plugin for JS Jobs
 ^ 
 * History:		1.0.1 - Nov 28, 2010
 ^ 
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
 
// Import Joomla! Plugin library file
jimport('joomla.plugin.plugin');
if(!defined('DS')){
   define('DS',DIRECTORY_SEPARATOR);
}

$document = &JFactory::getDocument();
$document->addScript( JURI::base() . '/includes/js/joomla.javascript.js');
 JHTML :: _('behavior.calendar');

//The Content plugin Loadmodule
class plgContentJSSearchJobs extends JPlugin
{		
		// for joomla 1.5
		public function onPrepareContent( &$row, &$params, $page=0 )
        {
                if ( JString::strpos( $row->text, 'jssearchjobs' ) === false ) {
                        return true;
                }

              // expression to search for
                $regex = '/{jssearchjobs\s*.*?}/i';
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
                if ( JString::strpos( $row->text, 'jssearchjobs' ) === false ) {
                        return true;
                }

              // expression to search for
                $regex = '/{jssearchjobs\s*.*?}/i';
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
                        $load = str_replace( 'jssearchjobs', '', $matches[0][$i] );
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
                $renderer      = $document->loadRenderer('module');
                $params                = array('style'=>$style);
 
                $db = JFactory::getDBO();
				$version = new JVersion;
				$joomla = $version->getShortVersion();
				$jversion = substr($joomla,0,3);
				if($jversion < 3){
					JHtml::_('behavior.mootools');
					$document->addScript('components/com_jsjobs/js/jquery.js');
				}else{
					JHtml::_('behavior.framework');
					JHtml::_('jquery.framework');
				}	
				$lang = & JFactory :: getLanguage();
				$lang->load('com_jsjobs');

				// Language variable start
				$jobtitle_title = JText::_('JS_JOB_TITLE');
				$jobstatustitle = JText::_('JS_JOB_STATUS');
				$salaryrangetitle = JText::_('JS_SALARY_RANGE');
				$fromtitle = JText::_('JS_FROM');
				$totitle = JText::_('JS_TO');
				$shifttitle = JText::_('JS_SHIFT');
				$durationtitle = JText::_('JS_DURATION');
				$startpublishingtitle = JText::_('JS_START_PUBLISH');
				$stoppublishingtitle = JText::_('JS_STOP_PUBLISH');

				$companytitle = JText::_('JS_COMPANY');
				$categorytitle = JText::_('JS_CATEGORY');
				$subcategorytitle = JText::_('JS_SUB_CATEGORY');
				$typetitle = JText::_('JS_JOB_TYPE');
				$searchjobtitle = JText::_('JS_SEARCH_JOB');
				// Language variable end
				$sh_title = $this->params->get('shtitle', 1);
				$title = $this->params->get('title', 'Search Jobs');
				$sh_category = $this->params->get('category', 1);
				$sh_subcategory = $this->params->get('subcategory', 1);
				$sh_jobtype = $this->params->get('jobtype', 1);
				$sh_jobstatus = $this->params->get('jobstatus', 1);
				$sh_salaryrange = $this->params->get('salaryrange', 1);
				$sh_heighesteducation = $this->params->get('heighesteducation', 1);
				$sh_shift = $this->params->get('shift', 1);
				$sh_experience = $this->params->get('experience', 1);
				$sh_durration = $this->params->get('durration', 1);
				$sh_startpublishing = $this->params->get('startpublishing', 1);
				$sh_stoppublishing = $this->params->get('stoppublishing', 1);
				$sh_company = $this->params->get('company', 1);
				$sh_addresses = $this->params->get('addresses', 1);
				$colperrow = $this->params->get('colperrow', 3);


				$colwidth = Round(100/$colperrow,1);
				$colwidth = $colwidth.'%';
				$colcount = 1;
				//scs				
				if($this->params->get('Itemid')) $itemid = $this->params->get('Itemid');			
				else  $itemid =  JRequest::getVar('Itemid');
				//sce
				/** scs */
				$componentAdminPath = JPATH_ADMINISTRATOR.'/components/com_jsjobs';
				$componentPath =  JPATH_SITE.'/components/com_jsjobs';
				require_once $componentPath.'/models/mpjsjobs.php';
				$config = array( 'table_path' => $componentAdminPath.'/tables');
				$model = new JSJobsModelMpJsjobs($config);
				$result = $model->jobsearch($sh_category,$sh_subcategory,$sh_company,$sh_jobtype,$sh_jobstatus,$sh_shift,$sh_salaryrange,1);
		
				
				
				$js_dateformat = $result[0];
				$currency = $result[1];
				$job_categories = $result[2];
				$search_companies = $result[3];
				$job_type= $result[4];
				$job_status = $result[5];
				$search_shift = $result[6];
				$salaryrangefrom =$result[7];
				$salaryrangeto =$result[8];
				$salaryrangetypes =$result[9];
				$job_subcategories = $result[10];

				/** sce */
				
		/*
				$query = "SELECT * FROM ". $db->nameQuote('#__js_job_config')." WHERE configname = 'date_format' OR configname = 'currency' ";
				$db->setQuery($query);
				$configs = $db->loadObjectList();
				foreach($configs AS $config){
					if ($config->configname == 'date_format')$dateformat = $config->configvalue;
					if ($config->configname == 'currency')$currency = $config->configvalue;
				}	
				$firstdash = strpos($dateformat,'-',0);
				$firstvalue = substr($dateformat, 0,$firstdash);
				$firstdash = $firstdash + 1;
				$seconddash = strpos($dateformat,'-',$firstdash);
				$secondvalue = substr($dateformat, $firstdash,$seconddash-$firstdash);
				$seconddash = $seconddash + 1;
				$thirdvalue = substr($dateformat, $seconddash,strlen($dateformat)-$seconddash);
				$js_dateformat = '%'.$firstvalue.'-%'.$secondvalue.'-%'.$thirdvalue;
	// Categories *********************************************
	if ($sh_category == 1){
		$query = "SELECT * FROM ".$db->nameQuote('#__js_job_categories')." WHERE isactive = 1 ORDER BY cat_title ";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if($rows){
			$jobcategories = array();
			$jobcategories[] =  array('value' => JText::_(''),'text' => JText::_('Search All'));
			foreach($rows as $row)
				$jobcategories[] =  array('value' => JText::_($row->id),'text' => JText::_($row->cat_title));
		}	
		$job_categories = JHTML::_('select.genericList', $jobcategories, 'jobcategory', 'class="inputbox" style="width:160px;" '. '', 'value', 'text', '');
	}
	
	//Companies *********************************************
	if ($sh_company == 1){
		$query = "SELECT id, name FROM ".$db->nameQuote('#__js_job_companies')." ORDER BY name ASC ";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if(isset($rows)){
			$companies = array();
			$companies[] =  array('value' => JText::_(''),'text' => JText::_('Search All'));
			foreach($rows as $row)
				$companies[] =  array('value' => $row->id,'text' => $row->name);
		}	
		$search_companies = JHTML::_('select.genericList', $companies, 'company', 'class="inputbox" style="width:160px;" '. '', 'value', 'text', '');
	
	}
	//Job Types *********************************************
	if ($sh_jobtype == 1){
		$query = "SELECT id, title FROM ".$db->nameQuote('#__js_job_jobtypes')." WHERE isactive = 1 ORDER BY id ASC ";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if($rows){
			$jobtype = array();
			$jobtype[] =  array('value' => JText::_(''),'text' => JText::_('Search All'));
			foreach($rows as $row)
				$jobtype[] =  array('value' => JText::_($row->id),'text' => JText::_($row->title));
		}	
		$job_type = JHTML::_('select.genericList', $jobtype, 'jobtype', 'class="inputbox" style="width:160px;" '. '', 'value', 'text', '');

	}
	//Job Status *********************************************
	if ($sh_jobstatus == 1){
		$query = "SELECT id, title FROM ".$db->nameQuote('#__js_job_jobstatus')." WHERE isactive = 1 ORDER BY id ASC ";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if($rows){
			$jobstatus = array();
			$jobstatus[] =  array('value' => JText::_(''),'text' => JText::_('Search All'));
			foreach($rows as $row)	
				$jobstatus[] =  array('value' => JText::_($row->id),	'text' => JText::_($row->title));
		}	
		$job_status = JHTML::_('select.genericList', $jobstatus, 'jobstatus', 'class="inputbox" style="width:160px;" '. '', 'value', 'text', '');

	}
	//Shifts *********************************************
	if ($sh_shift == 1){
		$query = "SELECT id, title FROM ".$db->nameQuote('#__js_job_shifts')." WHERE isactive = 1 ORDER BY id ASC ";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if($rows){
			$shifts = array();
			$shifts[] =  array('value' => JText::_(''),'text' => JText::_('Search All'));
			foreach($rows as $row)	
				$shifts[] =  array('value' => JText::_($row->id),	'text' => JText::_($row->title));
		}						
		$search_shift = JHTML::_('select.genericList', $shifts, 'shift', 'class="inputbox" style="width:160px;" '. '', 'value', 'text', '');
		
	}
	// Salary Rnage *********************************************
	if ( $sh_salaryrange == 1 ) { 
		$query = "SELECT * FROM ".$db->nameQuote('#__js_job_salaryrange')." ORDER BY 'id' ";
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if ($rows){
			$salaryrangefrom = array();
			$salaryrangeto = array();
			$salaryrangefrom[] =  array('value' => JText::_(''),'text' => $fromtitle);
			$salaryrangeto[] =  array('value' => JText::_(''),'text' => $totitle);
			foreach($rows as $row){
				$salrange = $currency . $row->rangestart;//.' - '.$currency . $row->rangeend;
				$salaryrangefrom[] =  array('value' => JText::_($row->id),'text' => JText::_($salrange));
				$salaryrangeto[] =  array('value' => JText::_($row->id),'text' => JText::_($salrange));
			}
			$query = "SELECT id, title FROM ".$db->nameQuote('#__js_job_salaryrangetypes')." WHERE status = 1 ORDER BY id ASC ";
			$db->setQuery( $query );
			$rows = $db->loadObjectList();
			$types = array();
			foreach($rows as $row)	{
				$types[] =  array('value' => $row->id,	'text' => $row->title);
			}
		}	
		$salaryrangefrom = JHTML::_('select.genericList', $salaryrangefrom, 'salaryrangefrom', 'class="inputbox" '. '', 'value', 'text', '');
		$salaryrangeto = JHTML::_('select.genericList', $salaryrangeto, 'salaryrangeto', 'class="inputbox" '. '', 'value', 'text', '');
		$salaryrangetypes = JHTML::_('select.genericList', $types, 'salaryrangetype', 'class="inputbox" '. '', 'value', 'text', 2);
	}
	*/


				$slink = JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=job_searchresults&Itemid='.$itemid);
				    $contents = '<form action="'.$slink.'" method="post" name="pjsadminForm" id="pjsadminForm">';
					$contents .= '<input type="hidden" name="isjobsearch" value="1" />';
					$contents .= '<table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">';
				      if ($sh_title == 1)$contents .= '<tr><td colsapn="4"><h2><u>'.$title.'</u></h2></td></tr>';
				      $contents .= '<tr><td nowrap="nowrap" width="'.$colwidth.'" valign="top" align="right" >'.$jobtitle_title;
						$contents .= '<input class="inputbox" type="text" name="title" size="16" maxlength="255"  /> </td> ';
				       if ( $sh_category == 1 ) { if($colcount == $colperrow){ $contents .= '</tr><tr>'; $colcount = 0; } $colcount++; 
						$contents .= '<td  nowrap="nowrap" width="'.$colwidth.'" valign="top" align="right">'. $categorytitle;
						$contents .=  $job_categories.'</td>';
				       } 
				       if ( $sh_subcategory == 1 ) { if($colcount == $colperrow){ $contents .= '</tr><tr>'; $colcount = 0; } $colcount++;
						$contents .= '<td  id="plgfj_subcategory" nowrap="nowrap" width="'.$colwidth.'" valign="top" align="right">'. $subcategorytitle;
						$contents .=  $job_subcategories.'</td>';
				       }
				      if ( $sh_jobtype == 1 ) { if($colcount == $colperrow){ $contents .= '</tr><tr>'; $colcount = 0; } $colcount++; 
						$contents .= '<td nowrap="nowrap" width="'.$colwidth.'" valign="top" align="right">'. $typetitle;
						$contents .=  $job_type.'</td> ';
				       } 
				      if ( $sh_jobstatus == 1 ) { if($colcount == $colperrow){ $contents .= '</tr><tr>'; $colcount = 0; } $colcount++; 
						$contents .= '<td nowrap="nowrap" width="'.$colwidth.'" valign="top" align="right">'. $jobstatustitle;
						$contents .= $job_status.'</td> ';
				       } 
				      if ( $sh_salaryrange == 1 ) { if($colcount == $colperrow){ $contents .= '</tr><tr>'; $colcount = 0; } $colcount++; 
						$contents .= '<td nowrap="nowrap" width="'.$colwidth.'" valign="top" align="right">'. $salaryrangetitle;
						$contents .=  $currency.' '.$salaryrangefrom.$currency.' '.$salaryrangeto.$salaryrangetypes.'</td>';
				       } 
				      if ( $sh_shift == 1 ) { if($colcount == $colperrow){ $contents .= '</tr><tr>'; $colcount = 0; } $colcount++; 
						$contents .= '<td nowrap="nowrap" width="'.$colwidth.'" valign="top" align="right">'. $shifttitle;
						$contents .=  $search_shift.'</td>';
				       } 
				      if ( $sh_durration == 1 ) { if($colcount == $colperrow){ $contents .= '</tr><tr>'; $colcount = 0; } $colcount++; 
						$contents .= '<td nowrap="nowrap" width="'.$colwidth.'" valign="top" align="right">'. $durationtitle;
						$contents .= '<input class="inputbox" type="text" name="durration" size="10" maxlength="15"  /></td>';
				       } 
				      if ( $sh_startpublishing == 1 ) { if($colcount == $colperrow){ $contents .= '</tr><tr>'; $colcount = 0; } $colcount++; 
							
							$startdatevalue = '';	  
							$contents .= '<td nowrap="nowrap" width="'.$colwidth.'" valign="top" align="right">'. $startpublishingtitle;
							if($jversion == '1.5'){
								 $contents .= '<input class="inputbox" type="text" name="startpublishing" id="startpublishingpgsr" readonly class="Shadow Bold" size="10" value="" />';
								$contents .='<input type="reset" class="button" value="..." onclick="return showCalendar(\'startpublishingpgsr\',\''.$js_dateformat.'\');"  /></td>';
							}else{ 
								$contents .=  JHTML::_('calendar', $startdatevalue,'startpublishing', 'startpublishing',$js_dateformat,array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'19')); 
							}                    
							'</td>';
						} 
				      if ( $sh_stoppublishing == 1 ) { if($colcount == $colperrow){ $contents .= '</tr><tr>'; $colcount = 0; } $colcount++; 
							$stopdatevalue = '';	  
							$contents .= '<td nowrap="nowrap" width="'.$colwidth.'" valign="top" align="right">'. $stoppublishingtitle;
							if($jversion == '1.5'){ $contents .= '<input class="inputbox" type="text" name="stoppublishing" id="stoppublishingpgsr" readonly class="Shadow Bold" size="10" value="" />';
							$contents .='<input type="reset" class="button" value="..." onclick="return showCalendar(\'stoppublishingpgsr\',\''.$js_dateformat.'\');"  /></td>';
							}else{ $contents .=  JHTML::_('calendar', $stopdatevalue,'stoppublishing', 'stoppublishing',$js_dateformat,array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'19')); 
							}
							'</td>';
				       } 

				      if ( $sh_company == 1 ) { if($colcount == $colperrow){ $contents .= '</tr><tr>'; $colcount = 0; } $colcount++; 
				        $contents .= '<td nowrap="nowrap" width="'.$colwidth.'" align="right">'. $companytitle;
				        $contents .=  $search_companies.'';
				        $contents .= '</td>';
				       } 
					for($i = $colcount; $i < $colperrow; $i++){
						$contents .= '<td></td>';
					}
					$contents .= '</tr>';
					$colcount=0;
					$contents .= '<tr>';
						$contents .= '<td colspan="'.$colperrow.'" align="center">';
						$contents .= '<input id="button" type="submit" class="button" name="submit_app" onclick="document.pjsadminForm.submit();" value="'. $searchjobtitle.'" />&nbsp;&nbsp;&nbsp;<span id="themeanchor"><a id="button" class="button minpad" href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobsearch&Itemid='.$itemid.'">'.JText::_('JS_ADVANCED_SEARCH').'</a></span>';
						$contents .= '</td>';
					$contents .= '</tr>';
				    $contents .= '</table>';

							$contents .= '<input type="hidden" name="view" value="jobseeker" />';
							$contents .= '<input type="hidden" name="layout" value="job_searchresults" />';
							$contents .= '<input type="hidden" name="option" value="com_jsjobs" />';
							
						  
						  
							  
						$contents .= '</form>'."<script language=\"javascript\" type=\"text/javascript\"> function plgfj_getsubcategories(src, val){
                                                                            var xhr;
                                                                            try {  xhr = new ActiveXObject('Msxml2.XMLHTTP');   }
                                                                            catch (e){
                                                                                    try {   xhr = new ActiveXObject('Microsoft.XMLHTTP');    }
                                                                                    catch (e2) {
                                                                                      try {  xhr = new XMLHttpRequest();     }
                                                                                      catch (e3) {  xhr = false;   }
                                                                                    }
                                                                             }

                                                                            xhr.onreadystatechange = function(){
                                                                                if(xhr.readyState == 4 && xhr.status == 200){
                                                                                    document.getElementById(src).innerHTML=xhr.responseText; //retuen value
                                                                                }
                                                                            }

                                                                            xhr.open(\"GET\",\"index.php?option=com_jsjobs&task=listsubcategoriesForSearch&val=\"+val+\"&md=\"+1,true);
                                                                            xhr.send(null);
                                                                    }</script>";


	
               return $contents;
        }
}



?>
