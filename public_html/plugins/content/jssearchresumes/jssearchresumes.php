<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
			www.joomsky.com, ahmad@joomsky.com
 * Created on:	Dec 11, 2009
 ^
 + Project: 		JS Jobs 
 * File Name:	Pplugin/jssearchjobs.php
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
class plgContentJSSearchResumes extends JPlugin
{
	// for joomla 1.5
	public function onPrepareContent( &$row, &$params, $page=0 )  {
		if ( JString::strpos( $row->text, 'jssearchresumes' ) === false ) {
                        return true;
		}
              // expression to search for
                $regex = '/{jssearchresumes\s*.*?}/i';
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
		public function onContentPrepare( $context, &$row, &$params, $page=0 )
        {
                if ( JString::strpos( $row->text, 'jssearchresumes' ) === false ) {
                        return true;
                }

              // expression to search for
                $regex = '/{jssearchresumes\s*.*?}/i';
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
                        $load = str_replace( 'jssearchresumes', '', $matches[0][$i] );
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
				$lang = & JFactory :: getLanguage();
				$lang->load('com_jsjobs');
				$sh_title = $this->params->get('shtitle', 1);
				$title = $this->params->get('title', 1);
				$sh_name = $this->params->get('name', 1);
				$sh_nationality = $this->params->get('natinality', 1);
				$sh_gender = $this->params->get('gender', 1);
				$sh_iamavailable = $this->params->get('iamavailable', 1);

				$sh_category = $this->params->get('category', 1);
				$sh_subcategory = $this->params->get('subcategory', 1);
				$sh_jobtype = $this->params->get('jobtype', 1);
				$sh_salaryrange = $this->params->get('salaryrange', 1);
				$sh_heighesteducation = $this->params->get('heighesteducation', 1);
				$sh_experience = $this->params->get('experience', 1);
				$colperrow = $this->params->get('colperrow', 3);


				$colwidth = Round(100/$colperrow,1);
				$colwidth = $colwidth.'%';
				$colcount = 1;

				//scs				
				if($this->params->get('Itemid')) $itemid = $this->params->get('Itemid');			
				else  $itemid =  JRequest::getVar('Itemid');
				$componentAdminPath = JPATH_ADMINISTRATOR.'/components/com_jsjobs';
				$componentPath =  JPATH_SITE.'/components/com_jsjobs';
				require_once $componentPath.'/models/mpjsjobs.php';
				$config = array( 'table_path' => $componentAdminPath.'/tables');
				$divclass=array('odd','even');
				$model = new JSJobsModelMpJsjobs($config);
				$result = $model->resumesearch($sh_gender,$sh_nationality,$sh_category,$sh_subcategory,$sh_jobtype,$sh_heighesteducation,$sh_salaryrange,1);

				$gender = $result[0];
				$nationality = $result[1];
				$job_categories = $result[2];
				$job_type = $result[3];
				$heighest_finisheducation= $result[4];
				$salary_range = $result[5];
				$currency = $result[6];
				$job_subcategories = $result[7];
				//sce

				$istr = 1;
				$contents = '<form action="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=resume_searchresults&Itemid='.$itemid.'" method="post" name="prsadminForm" id="prsadminForm">';
					$contents .= '<table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">';
				      if ($sh_title == 1)$contents .= '<tr><td colsapn="4"><h2><u>'.$title.'</u></h2></td></tr>';
					        $contents .= '<tr><td nowrap="nowrap" width="'.$colwidth.'"  align="right" >'.JText::_('JS_APPLICATION_TITLE') ; 
							$contents .= '<input class="inputbox" type="text" name="title" size="27" maxlength="255"  />';
					        $contents .= '</td>';
				      if ( $sh_name == 1 ) { if($colcount == $colperrow){ $contents .= '</tr><tr>'; $colcount = 0; } $colcount++; 
					        $contents .= '<td nowrap="nowrap" width="'.$colwidth.'"  align="right" >'. JText::_('JS_NAME'); 
							$contents .= '<input class="inputbox" type="text" name="name" size="27" maxlength="255"  />';
					        $contents .= '</td>';
				       }
				      if ( $sh_nationality == 1 ) { if($colcount == $colperrow){ $contents .= '</tr><tr>'; $colcount = 0; } $colcount++; 
					        $contents .= '<td nowrap="nowrap" width="'.$colwidth.'"  align="right" >'. JText::_('JS_NATIONALITY'); 
							$contents .=  $nationality;
					        $contents .= '</td>';
				       }
				      if ( $sh_gender == 1 ) { if($colcount == $colperrow){ $contents .= '</tr><tr>'; $colcount = 0; } $colcount++; 
						$contents .= '<td nowrap="nowrap" width="'.$colwidth.'"  align="right" >'. JText::_('JS_GENDER'); 
						$contents .=  $gender.'</td>';
				       }

				      if ( $sh_category == 1 ) { if($colcount == $colperrow){ $contents .= '</tr><tr>'; $colcount = 0; } $colcount++; 
					        $contents .= '<td nowrap="nowrap" width="'.$colwidth.'"  align="right" >'. JText::_('JS_CATEGORIES'); 
							$contents .=  $job_categories.'</td>';
				       }
				      if ( $sh_subcategory == 1 ) { if($colcount == $colperrow){ $contents .= '</tr><tr>'; $colcount = 0; } $colcount++;
					        $contents .= '<td id="plgresumefj_subcategory" nowrap="nowrap" width="'.$colwidth.'"  align="right" >'. JText::_('JS_SUB_CATEGORIES');
							$contents .=  $job_subcategories.'</td>';
				       }
				      if ( $sh_jobtype == 1 ) { if($colcount == $colperrow){ $contents .= '</tr><tr>'; $colcount = 0; } $colcount++; 
					        $contents .= '<td nowrap="nowrap" width="'.$colwidth.'"  align="right" >'. JText::_('JS_JOBTYPE'); 
							$contents .=  $job_type.'</td>';
				       }
				      if ( $sh_salaryrange == 1 ) { if($colcount == $colperrow){ $contents .= '</tr><tr>'; $colcount = 0; } $colcount++; 
					        $contents .= '<td nowrap="nowrap" width="'.$colwidth.'"  align="right" >'. JText::_('JS_SALARYRANGE'); 
							$contents .=  $currency.' '.$salary_range.'</td>';
				       }
				      if ( $sh_heighesteducation == 1 ) { if($colcount == $colperrow){ $contents .= '</tr><tr>'; $colcount = 0; } $colcount++; 
					        $contents .= '<td nowrap="nowrap" width="'.$colwidth.'"  align="right" >'. JText::_('JS_HEIGHTESTEDUCATION'); 
							$contents .=  $heighest_finisheducation.'</td>';
				       }
				      if ( $sh_experience == 1 ) { if($colcount == $colperrow){ $contents .= '</tr><tr>'; $colcount = 0; } $colcount++; 
					        $contents .= '<td nowrap="nowrap" width="'.$colwidth.'"  align="right" >'. JText::_('JS_EXPERIENCE'); 
							$contents .= '<input class="inputbox" type="text" name="experience" size="27" maxlength="25"  /></td>';
				       }

				      if ( $sh_iamavailable == 1 ) { if($colcount == $colperrow){ $contents .= '</tr><tr>'; $colcount = 0; } $colcount++; 
						$contents .= '<td nowrap="nowrap" width="'.$colwidth.'"  align="right" >'. JText::_('JS_AVAILABLE'); 
						$contents .= '<input type="checkbox" name="iamavailable" value="1"  /></td>';
				       }
					for($i = $colcount; $i < $colperrow; $i++){
						$contents .= '<td></td>';
					}
					$contents .= '</tr>';
					$colcount=0;

					$contents .= '<tr>';
						$contents .= '<td colspan="'.$colperrow.'" align="center">';
						$contents .= '<input id="button" type="submit" class="button" name="submit_app" onclick="document.prsadminForm.submit();" value="'. JText::_('JS_SEARCH_RESUME').'" />&nbsp;&nbsp;&nbsp;<span id="themeanchor"><a id="button" class="button minpad" href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=resumesearch&Itemid='.$itemid.'">'.JText::_('JS_ADVANCED_SEARCH').'</a></span>';
						$contents .= '</td>';
					$contents .= '</tr>';
				    $contents .= '</table>';

				 			$contents .= '<input type="hidden" name="isresumesearch" value="1" />';
							$contents .= '<input type="hidden" name="view" value="employer" />';
							$contents .= '<input type="hidden" name="layout" value="resume_searchresults" />';
							$contents .= '<input type="hidden" name="option" value="com_jsjobs" />';
							$contents .= '<input type="hidden" name="zipcode" value="" />';
							
						  
						  
							  
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
