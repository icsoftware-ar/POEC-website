<?php
/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 + Created by:          Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	views/jobseeker/view.html.php
 ^ 
 * Description: HTML view class for jobseeker
 ^ 
 * History:		NONE
 ^ 
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

class JSJobsViewJobseeker extends JViewLegacy
{

	function display($tpl = null)
	{
		global $mainframe, $sorton, $sortorder, $option	, $_client_auth_key,$socialconfig;


		$document	   =& JFactory::getDocument();
		$user	=& JFactory::getUser();
		$uid=$user->id;
		$viewtype = 'html';
		$type = 'offl';
		$option = 'com_jsjobs';
		$userrole = Array();
		$mainframe = &JFactory::getApplication();
		
	    $version = new JVersion;
	    $joomla = $version->getShortVersion();
		$jversion = substr($joomla,0,3);

		$router = $mainframe->getRouter();		
		if($router->getMode() == JROUTER_MODE_SEF) {
			$router_mode_sef = 1; // sef true
		}else{
			$router_mode_sef = 2; // sef false
		}				

		$itemid =  JRequest::getVar('Itemid');
		$layout =  JRequest::getVar('layout');

		$model		= &$this->getModel('Jobseeker','JSJobsModel');
		$common_model		= &$this->getModel('common','JSJobsModel');
		if($_client_auth_key=="") {
			$auth_key=$common_model->getClientAuthenticationKey();
			$_client_auth_key=$auth_key;
		}
		// get configurations
		//$config = Array();
		$session = JFactory::getSession();
		$config = $session->get('jsjobconfig_dft');
		$curuser = $session->get('jsjobcur_usr');
		if($curuser != $uid) unset($config);
		$session->set('jsjobcur_usr', $uid);
		if(!$socialconfig) $socialconfig=$common_model->getConfigByFor('social');
		


		if(isset($config))
				if($config['testing_mode'] == 1)
				unset($config);

                if(!isset($config)){
					$results =  $model->getConfig('');
					if ($results){ //not empty
						foreach ($results as $result){
							$config[$result->configname] = $result->configvalue;
						}
						$session->set('jsjobconfig_dft', $config);
					}

                }
                $needlogin = true;
                switch($layout){
                    case 'controlpanel': if ($config['visitorview_js_controlpanel'] == 1) $needlogin = false; break;
					case 'packages': 
						if ($config['visitorview_js_packages'] == 1){ 
							if(!$user->guest){
								$userrole =  $common_model->getUserRole($uid);	
								if(!isset($userrole->rolefor)){
									$n_i_l=JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=new_injsjobs&Itemid='.$itemid);
									$mainframe->redirect($n_i_l);
								}
							}
							$needlogin = false; 
							break;
						}
                    case 'package_details': if ($config['visitorview_js_viewpackage'] == 1) $needlogin = false; break;
                    case 'jobcat': if ($config['visitorview_js_jobcat'] == 1) $needlogin = false; break;
                    case 'list_jobs': if ($config['visitorview_js_listjob'] == 1) $needlogin = false; break;
                    case 'list_subcategoryjobs': if ($config['visitorview_js_listjob'] == 1) $needlogin = false; break;
                    case 'listnewestjobs': if ($config['visitorview_js_newestjobs'] == 1) $needlogin = false; break;
                    case 'jobsearch': if ($config['visitorview_js_jobsearch'] == 1) $needlogin = false; break;
                    case 'job_searchresults': if ($config['visitorview_js_jobsearchresult'] == 1) $needlogin = false; break;
                    case 'jobalertsetting': if ($config['overwrite_jobalert_settings'] == 1) $needlogin = false; break;
                    case 'jobalertunsubscribe': if ($config['overwrite_jobalert_settings'] == 1) $needlogin = false; break;
                    case 'company_jobs': if ($config['visitorview_emp_viewcompany'] == 1) $needlogin = false; break;
                    case 'userregister': $needlogin = false; break;
                    case 'userlogin': $needlogin = false; break;
                    case 'job_apply':
                        if ($config['visitor_can_apply_to_job'] == 1){
							$_SESSION['jsjobs_view'] = 'jobseeker';
							$_SESSION['jsjobs_red_layout'] = $layout;
							$ai = JRequest::getVar('aj');
							$bi = JRequest::getVar('bi');
							if($ai) $_SESSION['aj'] = $ai;
							if($bi) $_SESSION['bi'] = $bi;
							$needlogin = false;
						}
                        break;
                    case 'formresume': 
                            if ($config['visitor_can_apply_to_job'] == 1){
                                $session = JFactory::getSession();
                                $visitor = $session->get('jsjob_jobapply');
                                if ($visitor['visitor'] == 1)$needlogin = false;
                            }
                            break;
                    case 'business_version': $needlogin = false; break;
                    default : $needlogin = true; break;
                }
				if ($user->guest) { // redirect user if not login
					if($needlogin){
								$_SESSION['jsjobs_option'] = 'com_jsjobs';
								$_SESSION['jsjobs_view'] = 'jobseeker';
								$_SESSION['jsjobs_red_layout'] = $layout;
								if($layout == 'job_apply'){
										$ai = JRequest::getVar('aj');
										$bi = JRequest::getVar('bi');
										if($ai) $_SESSION['aj'] = $ai;
										if($bi) $_SESSION['bi'] = $bi;
								}
								$cmpurl="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; 
								$_SESSION['jsjobs_comp_url'] = $cmpurl;

								$msg = JText::_('JS_LOGIN_DESCRIPTION');
								$redirectUrl = JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=successfullogin');
								$redirectUrl = '&amp;return='.base64_encode($redirectUrl);
									if($jversion == '1.5'){
										$finalUrl = 'index.php?option=com_user&view=login'. $redirectUrl;
									}else{
										$finalUrl = 'index.php?option=com_users&view=login'. $redirectUrl;
									}
									
								
								$mainframe->redirect($finalUrl,$msg);
					}			
				} 
		
		if($option == '')
			$option='com_jsjobs';
		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart	= $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );
		$limitstart =  JRequest::getVar('limitstart',0);
		if (isset($_SESSION['jsjobs_layout'])){
			if ($layout != $_SESSION['jsjobs_layout']) {
				$_SESSION['jsjobs_layout'] = $layout;
				$limitstart = 0;
			}
		}else $_SESSION['jsjobs_layout'] = $layout;

		$params = & $mainframe->getPageParameters('com_jsjobs');
		$id = & $this->get('Id');

		if($layout != 'new_injsjobs'){
			if (isset($_SESSION['jsuserrole'])) $userrole = $_SESSION['jsuserrole']; else $userrole=null;
			//$config = Array();
			if($curuser != $uid) unset($userrole);
			if (!isset($userrole)){
				$userrole =  $common_model->getUserRole($uid);	
				if (isset($userrole)){ //not empty
					$_SESSION['jsuserrole'] = $userrole;	
				}else{
					if(($needlogin == true) && $layout != 'new_injsjobs'){//echo '<br> new in jsjobs';
						$mainframe->redirect('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=new_injsjobs&Itemid='.$itemid);
					}	
				}
			}
		}	
		
		$themevalue = $config['theme'];
		if ($themevalue != 'templatetheme.css'){
			$theme['title'] = 'jppagetitle';
			$theme['heading'] = 'pageheadline';
			$theme['sectionheading'] = 'sectionheadline';
			$theme['sortlinks'] = 'sortlnks';
			$theme['odd'] = 'odd';
			$theme['even'] = 'even';
		}else{
			$theme['title'] = 'componentheading';
			$theme['heading'] = 'contentheading';
			$theme['sectionheading'] = 'sectiontableheader';
			$theme['sortlinks'] = 'sectiontableheader';
			$theme['odd'] = 'sectiontableentry1';
			$theme['even'] = 'sectiontableentry2';
		}

		// get save filter
		if ($uid != ''){
			if (isset($_SESSION['jsuserfilter']))$userfilter = $_SESSION['jsuserfilter'];else $userfilter=null;
			if (sizeof($userfilter) == 0){
				$result =  $model->getUserFilter($uid);	
				if (isset($result)){ //not empty
					$userfilter[0] = 1;
					$userfilter[1] = $result;
					$_SESSION['jsuserfilter'] = $userfilter;	
					$filterid = $result->id;
				}else{
					$userfilter[0] = 1;
					$_SESSION['jsuserfilter'] = $userfilter;	
				}
			}else{
                            $userfilter= $_SESSION['jsuserfilter'];
                            if(isset($userfilter[1]))$filterid = $userfilter[1]->id;
                        }
		} else $userfilter = '';

		if(isset($userrole->rolefor)) {
			if ($userrole->rolefor == 1) { // employer
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=controlpanel&Itemid='.$itemid;
				$employerlinks [] = array($link, JText::_('JS_CONTROL_PANEL'),1);
                
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formjob&Itemid='.$itemid;
				$employerlinks [] = array($link, JText::_('JS_NEW_JOB'),0);
				
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid='.$itemid;
				$employerlinks [] = array($link, JText::_('JS_MY_JOBS'),0);

				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=mycompanies&Itemid='.$itemid;
				$employerlinks [] = array($link, JText::_('JS_MY_COMPANIES'),0);

				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=alljobsappliedapplications&Itemid='.$itemid;
				$employerlinks [] = array($link, JText::_('JS_APPLIED_RESUME'),-1);
			}else{

//		if ($cur_user_allow[1] == 1) { // Emp Allow
				if($config['tmenu_jscontrolpanel'] == 1){$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=controlpanel&Itemid='.$itemid;
				$jobseekerlinks [] = array($link, JText::_('JS_CONTROL_PANEL'),1);
				}
				
				if($config['tmenu_jsjobcategory'] == 1){$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobcat&Itemid='.$itemid;
				$jobseekerlinks [] = array($link, JText::_('JS_JOB_CATEGORIES'),0);
                }
				if($config['tmenu_jssearchjob'] == 1){$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobsearch&Itemid='.$itemid;
				$jobseekerlinks [] = array($link, JText::_('JS_SEARCH_JOB'),0);
                 }
				if($config['tmenu_jsnewestjob'] == 1){$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=listnewestjobs&Itemid='.$itemid;
				$jobseekerlinks [] = array($link, JText::_('JS_NEWEST_JOBS'),0);
			     }
				if($config['tmenu_jsmyresume'] == 1){$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myresumes&Itemid='.$itemid;
				$jobseekerlinks [] = array($link, JText::_('JS_MY_RESUMES'),-1);
				}
			}
		}else{ // user not logined

                            if($config['tmenu_vis_jscontrolpanel'] == 1){$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=controlpanel&Itemid='.$itemid;
                            $jobseekerlinks [] = array($link, JText::_('JS_CONTROL_PANEL'),1);
                            }

                            if($config['tmenu_vis_jsjobcategory'] == 1){$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobcat&Itemid='.$itemid;
                            $jobseekerlinks [] = array($link, JText::_('JS_JOB_CATEGORIES'),0);
                            }
                            if($config['tmenu_vis_jssearchjob'] == 1){$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobsearch&Itemid='.$itemid;
                            $jobseekerlinks [] = array($link, JText::_('JS_SEARCH_JOB'),0);
                            }
                            if($config['tmenu_vis_jsnewestjob'] == 1){$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=listnewestjobs&Itemid='.$itemid;
                            $jobseekerlinks [] = array($link, JText::_('JS_NEWEST_JOBS'),0);
                            }
                            if($config['tmenu_vis_jsmyresume'] == 1){$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myresumes&Itemid='.$itemid;
                            $jobseekerlinks [] = array($link, JText::_('JS_MY_RESUMES'),-1);
                            }
			}
		
                $page_title = $params->get('page_title');
                $config['offline']=0;
                


                if($layout== 'jobcat'){									//job cat
			
						$page_title .=  ' - ' . JText::_('JS_JOB_CATEGORIES');
						
						$result = $model->getJobCat();	


						$application = $result[0];
						$filterlists =  $result[2];
						$filtervalues =  $result[3];
			//			$this->assignRef('jobs', $jobs);
						$this->assignRef('filterlists', $filterlists);
						$this->assignRef('filtervalues', $filtervalues);
						$this->assignRef('filterid', $filterid);
		
		}elseif($layout== 'company_jobs'){		
                        if (isset($_GET['sortby']))
				$sort = $_GET['sortby'];
			$sort =  JRequest::getVar('sortby','');
			if (isset($sort)){
				if ($sort == '') 
					{$sort='createddesc';}
			}else
				{$sort='createddesc';}
			$sortby = $this->getJobListOrdering($sort);							
				
			$cmbfiltercountry = $mainframe->getUserStateFromRequest( $option.'cmbfilter_country', 'cmbfilter_country',	'',	'string' );

			$cmbfilterradiustype = $mainframe->getUserStateFromRequest( $option.'filter_radius_length_type', 'filter_radius_length_type',	'',	'string' );

			$city_filter = $mainframe->getUserStateFromRequest( $option.'txtfilter_city', 'txtfilter_city',	'',	'string' );

			$txtfilterlongitude = $mainframe->getUserStateFromRequest( $option.'filter_longitude', 'filter_longitude',	'',	'string' );
			$txtfilterlatitude = $mainframe->getUserStateFromRequest( $option.'filter_latitude', 'filter_latitude',	'',	'string' );
			$txtfilterradius = $mainframe->getUserStateFromRequest( $option.'filter_radius', 'filter_radius',	'',	'string' );

                        if ($txtfilterlongitude == JText::_('JS_LONGITUDE')) $txtfilterlongitude = '';
                        if ($txtfilterlatitude == JText::_('JS_LATITTUDE')) $txtfilterlatitude = '';
                        if ($txtfilterradius == JText::_('JS_COORDINATES_RADIUS')) $txtfilterradius = '';

			$filterjobtype = $mainframe->getUserStateFromRequest( $option.'filter_jobtype', 'filter_jobtype',	'',	'string' );


			if (isset($_POST['filter_jobcategory']))$filterjobcategory =$_POST['filter_jobcategory'];else $filterjobcategory='';
			if (isset($_POST['filter_jobsubcategory']))$filterjobsubcategory =$_POST['filter_jobsubcategory'];else $filterjobsubcategory='';


				$companyid=($router_mode_sef==2)? $common_model->parseId(JRequest::getVar('cd','')):JRequest::getVar('cd','');
				$result =  $model->getActiveJobsByCompany($uid,$companyid,$city_filter,$cmbfiltercountry
											,$filterjobcategory,$filterjobsubcategory,$filterjobtype,$sortby
											,$txtfilterlongitude,$txtfilterlatitude,$txtfilterradius,$cmbfilterradiustype
											 ,$limit,$limitstart);
		
		
		
			$jobs = $result[0];
			
			$filterlists =  $result[2];
			$filtervalues =  $result[3];
			
			$this->assignRef('listjobconfig',$result[4]);
			$this->assignRef('jobs', $jobs);
			$sortlinks = $this->getJobListSorting($sort);
			$sortlinks['sorton'] = $sorton;
			$sortlinks['sortorder'] = $sortorder;
                        if ( $result[1] <= $limitstart ) $limitstart = 0;
                        $pagination = new JPagination($result[1], $limitstart, $limit );
			$this->assignRef('pagination', $pagination);
			$this->assignRef('filterlists', $filterlists);
			$this->assignRef('filtervalues', $filtervalues);
			$this->assignRef('sortlinks', $sortlinks);
			$this->assignRef('companyid', $companyid);
			if (!empty($jobs)){
				$page_title .=  ' - ' . $jobs[0]->companyname.' '.JText::_('JS_JOBS');
				//if ($mainframe->getCfg('MetaTitle') == '1') $mainframe->addMetaTag('title', $jobs[0]->companyname.' '.JText::_('JS_JOBS'));
			}
	
		}elseif($layout== 'list_jobs'){ 											// list jobs
			$catid='';
			if (isset($_GET['sortby']))
				$sort = $_GET['sortby'];
			$sort =  JRequest::getVar('sortby','');
			if (isset($sort)){
				if ($sort == '') 
					{$sort='createddesc';}
			}else
				{$sort='createddesc';}
			$sortby = $this->getJobListOrdering($sort);
			
			$cmbfiltercountry = $mainframe->getUserStateFromRequest( $option.'cmbfilter_country', 'cmbfilter_country',	'',	'string' );
			$city_filter = $mainframe->getUserStateFromRequest( $option.'txtfilter_city', 'txtfilter_city',	'',	'string' );

			$txtfilterlongitude = $mainframe->getUserStateFromRequest( $option.'filter_longitude', 'filter_longitude',	'',	'string' );
			$txtfilterlatitude = $mainframe->getUserStateFromRequest( $option.'filter_latitude', 'filter_latitude',	'',	'string' );
			$txtfilterradius = $mainframe->getUserStateFromRequest( $option.'filter_radius', 'filter_radius',	'',	'string' );


			if ($txtfilterlongitude == JText::_('JS_LONGITUDE')) $txtfilterlongitude = '';
			if ($txtfilterlatitude == JText::_('JS_LATITTUDE')) $txtfilterlatitude = '';
			if ($txtfilterradius == JText::_('JS_COORDINATES_RADIUS')) $txtfilterradius = '';

			$filterjobtype = $mainframe->getUserStateFromRequest( $option.'filter_jobtype', 'filter_jobtype',	'',	'string' );

			if (isset($_POST['filter_jobcategory']))$filterjobcategory =$_POST['filter_jobcategory'];else $filterjobcategory='';
			if (isset($_POST['filter_jobsubcategory']))$filterjobsubcategory =$_POST['filter_jobsubcategory'];else $filterjobsubcategory='';
			
			if($_client_auth_key=="") {if ($filterjobcategory) $catid = $filterjobcategory;}

						$cat_id=($router_mode_sef==2)? $common_model->parseId(JRequest::getVar('jobcat','')):JRequest::getVar('jobcat','');
                        if ($catid == 0 )  $catid = $cat_id;
                        $result =  $model->getJobsbyCategory($uid,$catid,$city_filter,$cmbfiltercountry
															,$filterjobcategory,$filterjobsubcategory,$filterjobtype 
															,$txtfilterlongitude,$txtfilterlatitude,$txtfilterradius,$cmbfilterradiustype
															,$sortby,$limit,$limitstart);	
			
			if(isset($result[0]))$jobs = $result[0];else $jobs = false;
			$filterlists =  $result[2];
			$filtervalues =  $result[3];
			$sortlinks = $this->getJobListSorting($sort);
			$sortlinks['sorton'] = $sorton;
			$sortlinks['sortorder'] = $sortorder;
                        if ( $result[1] <= $limitstart ) $limitstart = 0;
                        $pagination = new JPagination($result[1], $limitstart, $limit );
						//$pagination->getAdditionalUrlParam('&fr&jobcat&Itemid');                        
			$this->assignRef('pagination', $pagination);
			$this->assignRef('jobs', $jobs);
			$this->assignRef('category', $result[6]);
			$this->assignRef('filterlists', $filterlists);
			$this->assignRef('filtervalues', $filtervalues);
			$this->assignRef('listjobconfig',$result[4]);
			$this->assignRef('subcategories',$result[5]);
			$this->assignRef('sortlinks', $sortlinks);
			$this->assignRef('categoryid', $catid);
			$this->assignRef('companyid', $companyid);
			$this->assignRef('filterid', $filterid);
			$this->assignRef('goldjobs', $result[7]);
			$this->assignRef('featuredjobs', $result[8]);
			$this->assignRef('cm', JRequest::getVar('cm',''));
                        if ($jobs){
                            $page_title .=  ' - ' . $jobs[0]->cat_title;
                            //if ($mainframe->getCfg('MetaTitle') == '1') $mainframe->addMetaTag('title', $jobs[0]->cat_title);
                        }
            // Check where use list for fr 
			$listfor = 1;
			$this->assignRef('listfor', $listfor);
			/*----------------------*/
		}elseif($layout== 'list_subcategoryjobs'){ 											// list jobs
			$subcatid='';
			if (isset($_GET['sortby']))
				$sort = $_GET['sortby'];
			$sort =  JRequest::getVar('sortby','');
			if (isset($sort)){
				if ($sort == '')
					{$sort='createddesc';}
			}else
				{$sort='createddesc';}
			$sortby = $this->getJobListOrdering($sort);

			$cmbfiltercountry = $mainframe->getUserStateFromRequest( $option.'cmbfilter_country', 'cmbfilter_country',	'',	'string' );
			
			$cmbfilterradiustype = $mainframe->getUserStateFromRequest( $option.'filter_radius_length_type', 'filter_radius_length_type',	'',	'string' );

			$city_filter = $mainframe->getUserStateFromRequest( $option.'txtfilter_city', 'txtfilter_city',	'',	'string' );

			$txtfilterlongitude = $mainframe->getUserStateFromRequest( $option.'filter_longitude', 'filter_longitude',	'',	'string' );
			$txtfilterlatitude = $mainframe->getUserStateFromRequest( $option.'filter_latitude', 'filter_latitude',	'',	'string' );
			$txtfilterradius = $mainframe->getUserStateFromRequest( $option.'filter_radius', 'filter_radius',	'',	'string' );

			if ($txtfilterlongitude == JText::_('JS_LONGITUDE')) $txtfilterlongitude = '';
			if ($txtfilterlatitude == JText::_('JS_LATITTUDE')) $txtfilterlatitude = '';
			if ($txtfilterradius == JText::_('JS_COORDINATES_RADIUS')) $txtfilterradius = '';

			$filterjobtype = $mainframe->getUserStateFromRequest( $option.'filter_jobtype', 'filter_jobtype',	'',	'string' );

			if (isset($_POST['filter_jobcategory']))$filterjobcategory =$_POST['filter_jobcategory'];else $filterjobcategory='';
			if (isset($_POST['filter_jobsubcategory']))$filterjobsubcategory =$_POST['filter_jobsubcategory'];else $filterjobsubcategory='';
			if($_client_auth_key=="") {
				if ($filterjobsubcategory != '') $subcatid = $filterjobsubcategory;	
			}
						$subcat_id=($router_mode_sef==2)? $common_model->parseId(JRequest::getVar('jobsubcat','')):JRequest::getVar('jobsubcat','');
                        if ($subcatid == 0 )  $subcatid = $subcat_id;
                        $result =  $model->getJobsbySubCategory($uid,$subcatid,$city_filter,$cmbfiltercountry
															,$filterjobcategory,$filterjobsubcategory,$filterjobtype
															,$txtfilterlongitude,$txtfilterlatitude,$txtfilterradius,$cmbfilterradiustype
															,$sortby,$limit,$limitstart);


			$jobs = $result[0];

			$filterlists =  $result[2];
			$filtervalues =  $result[3];
			$sortlinks = $this->getJobListSorting($sort);
			$sortlinks['sorton'] = $sorton;
			$sortlinks['sortorder'] = $sortorder;
                        if ( $result[1] <= $limitstart ) $limitstart = 0;
                        $pagination = new JPagination($result[1], $limitstart, $limit );
			$this->assignRef('pagination', $pagination);
			$this->assignRef('jobs', $jobs);
			$this->assignRef('totalrecords', $result[1]);
			$this->assignRef('filterlists', $filterlists);
			$this->assignRef('filtervalues', $filtervalues);
			$this->assignRef('listjobconfig',$result[4]);
			$this->assignRef('sortlinks', $sortlinks);
			$this->assignRef('categoryid', $catid);
			$this->assignRef('companyid', $companyid);
			$this->assignRef('jobsubcat', $subcat_id);
			$this->assignRef('filterid', $filterid);
			$this->assignRef('goldjobs', $result[5]);
			$this->assignRef('featuredjobs', $result[6]);
			$this->assignRef('cm', JRequest::getVar('cm',''));
			if (isset($jobs)){
				$page_title .=  ' - ' . $jobs[0]->cat_title;
				//if ($mainframe->getCfg('MetaTitle') == '1') $mainframe->addMetaTag('title', $jobs[0]->cat_title);
			}
		}elseif($layout == 'listnewestjobs'){ 											// list newest job
						
		   $page_title .=  ' - ' . JText::_('JS_NEWEST_JOBS');
			$listtype =  JRequest::getVar('lt'); // for list job by address
			$jobcountry = JRequest::getVar('country','');
			$jobstate = JRequest::getVar('state','');
			if($listtype == 1){
				$jobcity =  JRequest::getVar('city','');
				$mainframe->setUserState($option.'txtfilter_city', $jobcity);
			}
			
			
			$cmbfiltercountry = $mainframe->getUserStateFromRequest( $option.'cmbfilter_country', 'cmbfilter_country',	'',	'string' );
			$cmbfilterradiustype = $mainframe->getUserStateFromRequest( $option.'filter_radius_length_type', 'filter_radius_length_type',	'',	'string' );
			
			$city_filter = $mainframe->getUserStateFromRequest( $option.'txtfilter_city', 'txtfilter_city',	'',	'string' );

			$txtfilterlongitude = $mainframe->getUserStateFromRequest( $option.'filter_longitude', 'filter_longitude',	'',	'string' );
			$txtfilterlatitude = $mainframe->getUserStateFromRequest( $option.'filter_latitude', 'filter_latitude',	'',	'string' );
			$txtfilterradius = $mainframe->getUserStateFromRequest( $option.'filter_radius', 'filter_radius',	'',	'string' );


			if ($txtfilterlongitude == JText::_('JS_LONGITUDE')) $txtfilterlongitude = '';
			if ($txtfilterlatitude == JText::_('JS_LATITTUDE')) $txtfilterlatitude = '';
			if ($txtfilterradius == JText::_('JS_COORDINATES_RADIUS')) $txtfilterradius = '';

			$filterjobcategory = $mainframe->getUserStateFromRequest( $option.'filter_jobcategory', 'filter_jobcategory',	'',	'string' );
			$filterjobsubcategory = $mainframe->getUserStateFromRequest( $option.'filter_jobsubcategory', 'filter_jobsubcategory',	'',	'string' );
			$filterjobtype = $mainframe->getUserStateFromRequest( $option.'filter_jobtype', 'filter_jobtype',	'',	'string' );

			$result =  $model->getListNewestJobs($uid,$city_filter,$cmbfiltercountry,$filterjobcategory,$filterjobsubcategory,$filterjobtype
											,$txtfilterlongitude,$txtfilterlatitude,$txtfilterradius,$cmbfilterradiustype
											,$jobcountry,$jobstate,$limit,$limitstart);	
			$jobs = $result[0];
			$filterlists =  $result[2];
			$filtervalues =  $result[3];
			
                        if ( $result[1] <= $limitstart ) $limitstart = 0;
                        $pagination = new JPagination($result[1], $limitstart, $limit );
			$this->assignRef('pagination', $pagination);
			$this->assignRef('listjobconfig',$result[4]);
			$this->assignRef('jobs', $jobs);
			$this->assignRef('filterlists', $filterlists);
			$this->assignRef('filtervalues', $filtervalues);
			$this->assignRef('goldjobs',$result[5]);
			$this->assignRef('featuredjobs',$result[6]);
			$this->assignRef('filterid', $filterid);
			
		}elseif($layout== 'jobsearch'){ 														// job search 
			$page_title .=  ' - ' . JText::_('JS_SEARCH_JOB');
			$result =  $model->getSearchOptions($uid);	
			$this->assignRef('searchoptions', $result[0]);
			$this->assignRef('searchjobconfig', $result[1]);
			$this->assignRef('canview', $result[2]);
		}elseif($layout== 'jobalertsetting'){											// form company
            $page_title .=  ' - ' . JText::_('JS_JOB_ALERT_INFO');
			$result =  $model->getJobAlertbyUidforForm($uid);
			if(!$uid){
				$result1 =  $common_model->getCaptchaForForm();
				$this->assignRef('captcha', $result1);
			}	
			$this->assignRef('jobsetting', $result[0]);
			$this->assignRef('lists', $result[1]);
			$this->assignRef('cansetjobalert', $result[2]);
			if(isset($result[3])) $this->assignRef('multiselectedit',$result[3]);
			
			JHTML::_('behavior.formvalidation');
		}elseif($layout== 'job_searchresults'){ 															// job search results
                        $page_title .=  ' - ' . JText::_('JS_JOB_SEARCH_RESULT');
			if (isset($_GET['sortby']))
				$sort = $_GET['sortby'];
			
			if (isset($sort)){
				if ($sort == '') 
					{$sort='createddesc';}
			}else
				{$sort='createddesc';}
			$sortby = $this->getJobListOrdering($sort);
			if (isset($_POST['isjobsearch'])){
				if ($_POST['isjobsearch'] == '1'){
					if(isset($_POST['title'])) $_SESSION['jobsearch_title'] = $_POST['title']; else $_SESSION['jobsearch_title']="";
					if(isset($_POST['jobcategory'])) $_SESSION['jobsearch_jobcategory'] = $_POST['jobcategory']; else $_SESSION['jobsearch_jobcategory']="";
					if(isset($_POST['jobsubcategory'])) $_SESSION['jobsearch_jobsubcategory'] = $_POST['jobsubcategory']; else $_SESSION['jobsearch_jobsubcategory']="";
					if(isset($_POST['jobtype'])) $_SESSION['jobsearch_jobtype'] = $_POST['jobtype']; else $_SESSION['jobsearch_jobtype']="";
					if(isset($_POST['jobstatus'])) $_SESSION['jobsearch_jobstatus'] = $_POST['jobstatus']; else $_SESSION['jobsearch_jobstatus']="";
					if(isset($_POST['salaryrangefrom'])) $_SESSION['jobsearch_salaryrangefrom'] = $_POST['salaryrangefrom']; else $_SESSION['jobsearch_salaryrangefrom'] = "";
					if(isset($_POST['salaryrangeto'])) $_SESSION['jobsearch_salaryrangeto'] = $_POST['salaryrangeto']; else $_SESSION['jobsearch_salaryrangeto'] ="";
					if(isset($_POST['salaryrangetype'])) $_SESSION['jobsearch_salaryrangetype'] = $_POST['salaryrangetype']; else $_SESSION['jobsearch_salaryrangetype'] ="";
					if(isset($_POST['education'])) $_SESSION['jobsearch_education'] = $_POST['education']; else $_SESSION['jobsearch_education'] ="";
					if(isset($_POST['heighestfinisheducation']))$_SESSION['jobsearch_heighestfinisheducation'] = $_POST['heighestfinisheducation'];else $_SESSION['jobsearch_heighestfinisheducation'] ="";
					if(isset($_POST['shift']))$_SESSION['jobsearch_shift'] = $_POST['shift'];else $_SESSION['jobsearch_shift'] ="";
					if(isset($_POST['experience']))$_SESSION['jobsearch_experience'] = $_POST['experience']; else $_SESSION['jobsearch_experience']="";
					if(isset($_POST['durration']))$_SESSION['jobsearch_durration'] = $_POST['durration']; else $_SESSION['jobsearch_durration']="";
					if(isset($_POST['startpublishing']))$_SESSION['jobsearch_startpublishing'] = $_POST['startpublishing']; else $_SESSION['jobsearch_startpublishing']="";
					if(isset($_POST['stoppublishing']))$_SESSION['jobsearch_stoppublishing'] = $_POST['stoppublishing']; else $_SESSION['jobsearch_stoppublishing']="";
					if(isset($_POST['jobsearch_company']))$_SESSION['jobsearch_company'] = $_POST['jobsearch_company']; else $_SESSION['jobsearch_company']="";
					if(isset($_POST['searchcity'])) $_SESSION['jobsearch_city'] = $_POST['searchcity']; else $_SESSION['jobsearch_city']="";
					if(isset($_POST['zipcode']))$_SESSION['jobsearch_zipcode'] = $_POST['zipcode'];
					if(isset($_POST['currency']))$_SESSION['jobsearch_currency'] = $_POST['currency'];
					if(isset($_POST['longitude']))$_SESSION['jobsearch_longitude'] = $_POST['longitude'];else $_SESSION['jobsearch_longitude']="";
					if(isset($_POST['latitude']))$_SESSION['jobsearch_latitude'] = $_POST['latitude'];else $_SESSION['jobsearch_latitude']="";
					if(isset($_POST['radius']))$_SESSION['jobsearch_radius'] = $_POST['radius'];else $_SESSION['jobsearch_radius']="";
					if(isset($_POST['radius_length_type']))$_SESSION['jobsearch_radius_length_type'] = $_POST['radius_length_type']; else $_SESSION['jobsearch_radius_length_type']="";
					if(isset($_POST['keywords']))$_SESSION['jobsearch_keywords'] = $_POST['keywords'];else $_SESSION['jobsearch_keywords']="";
				}
			}	
			$title="";
			if(isset($_SESSION['jobsearch_title'])) $title =$_SESSION['jobsearch_title'] ;
			$jobcategory="";
			if(isset($_SESSION['jobsearch_jobcategory'])) $jobcategory =$_SESSION['jobsearch_jobcategory'] ;
			$jobsubcategory="";
			if(isset($_SESSION['jobsearch_jobsubcategory'])) $jobsubcategory =$_SESSION['jobsearch_jobsubcategory'] ;
			$jobtype="";
			if(isset($_SESSION['jobsearch_jobtype'])) $jobtype =$_SESSION['jobsearch_jobtype'] ;
			$jobstatus="";
			if(isset($_SESSION['jobsearch_jobstatus'])) $jobstatus =$_SESSION['jobsearch_jobstatus'] ;
			$salaryrangefrom="";
			if(isset($_SESSION['jobsearch_salaryrangefrom'])) $salaryrangefrom =$_SESSION['jobsearch_salaryrangefrom'] ;
			$salaryrangeto="";
			if(isset($_SESSION['jobsearch_salaryrangeto'])) $salaryrangeto =$_SESSION['jobsearch_salaryrangeto'] ;
			$salaryrangetype="";
			if(isset($_SESSION['jobsearch_salaryrangetype'])) $salaryrangetype =$_SESSION['jobsearch_salaryrangetype'] ;
			$shift="";
			if(isset($_SESSION['jobsearch_shift'])) $shift =$_SESSION['jobsearch_shift'] ;
			if(isset($_SESSION['jobsearch_experience']))  $experience = $_SESSION['jobsearch_experience'];  else $experience = '';
			$durration="";
			if(isset($_SESSION['jobsearch_durration'])) $durration =$_SESSION['jobsearch_durration'] ;
			$startpublishing="";
			if(isset($_SESSION['jobsearch_startpublishing'])) $startpublishing =$_SESSION['jobsearch_startpublishing'] ;
			$stoppublishing="";
			if(isset($_SESSION['jobsearch_stoppublishing'])) $stoppublishing =$_SESSION['jobsearch_stoppublishing'] ;
			$company="";
			if(isset($_SESSION['jobsearch_company'])) $company =$_SESSION['jobsearch_company'] ;

			$city="";
			if(isset($_SESSION['jobsearch_city'])) $city =$_SESSION['jobsearch_city'] ;
			if(isset($_SESSION['jobsearch_zipcode'])) $zipcode = $_SESSION['jobsearch_zipcode']; else $zipcode = '';
			$currency="";
			if(isset($_SESSION['jobsearch_currency'])) $currency =$_SESSION['jobsearch_currency'] ;
			$longitude="";
			if(isset($_SESSION['jobsearch_longitude'])) $longitude =$_SESSION['jobsearch_longitude'] ;
			$latitude="";
			if(isset($_SESSION['jobsearch_latitude'])) $latitude =$_SESSION['jobsearch_latitude'] ;
			$radius="";
			if(isset($_SESSION['jobsearch_radius'])) $radius =$_SESSION['jobsearch_radius'] ;
			$radius_length_type="";
			if(isset($_SESSION['jobsearch_radius_length_type'])) $radius_length_type =$_SESSION['jobsearch_radius_length_type'] ;

			$keywords="";
			if(isset($_SESSION['jobsearch_keywords'])) $keywords =$_SESSION['jobsearch_keywords'] ;

			$result =  $model->getJobSearch($uid,$title,$jobcategory,$jobsubcategory,$jobtype,$jobstatus,$currency,$salaryrangefrom,$salaryrangeto,$salaryrangetype
											,$shift, $experience, $durration, $startpublishing, $stoppublishing	
											,$company,/*$country,$state,$county,*/$city,$zipcode,$longitude,$latitude,$radius,$radius_length_type,$keywords,$sortby,$limit,$limitstart);
			$options =  $this->get('Options');
			$sortlinks = $this->getJobListSorting($sort);
			$sortlinks['sorton'] = $sorton;
			$sortlinks['sortorder'] = $sortorder;
			$application = $result[0];
                        if ( $result[1] <= $limitstart ) $limitstart = 0;
                        $pagination = new JPagination($result[1], $limitstart, $limit );
			$this->assignRef('application', $application);
			$this->assignRef('pagination', $pagination);
			$this->assignRef('listjobconfig',$result[2]);
			$this->assignRef('searchjobconfig',$result[3]);
			$this->assignRef('canview', $result[4]);
			$this->assignRef('sortlinks', $sortlinks);
		}elseif($layout== 'job_apply'){ 											// job apply
			$result = null;
			$page_title .=  ' - ' . JText::_('JS_APPLYNOW');
			$jobid=($router_mode_sef==2)? $common_model->parseId(JRequest::getVar('bi','')):JRequest::getVar('bi','');
			if($uid){
				$jobresult =  $model->getJobbyIdforJobApply($jobid);
				$result =  $model->getMyResumes($uid);
			}else{
				$session = JFactory::getSession();
				$visitor['visitor'] = 1;
				$visitor['bi'] = $jobid;
				$session->set('jsjob_jobapply', $visitor);
				if ($config['visitor_show_login_message'] != 1){ 
					$formresumelink = JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=formresume');
					 $mainframe->redirect($formresumelink);
				}

			}
			$this->assignRef('job', $jobresult[0]);
			$this->assignRef('listjobconfig', $jobresult[1]);
			$this->assignRef('myresumes', $result[0]);
			$this->assignRef('mycoverletters', $result[2]);
			$this->assignRef('totalresume', $result[1]);
			$this->assignRef('jobcat', JRequest::getVar('jobcat',''));
			$this->assignRef('aj', JRequest::getVar('aj',''));
			$this->assignRef('companyid', JRequest::getVar('cd',''));
		}elseif($layout== 'myappliedjobs'){											//my applied jobs
                        $page_title .=  ' - ' . JText::_('JS_MY_APPLIED_JOBS');
			if (isset($_GET['sortby']))
				$sort = $_GET['sortby'];
			
			$sort =  JRequest::getVar('sortby','');	
			if (isset($sort)){
				if ($sort == '') 
					{$sort='createddesc';}
			}else
				{$sort='createddesc';}
			$sortby = $this->getJobListOrdering($sort);
			$result =  $model->getMyAppliedJobs($uid,$sortby,$limit,$limitstart);	
			$application = $result[0];
			$sortlinks = $this->getJobListSorting($sort);
			$sortlinks['sorton'] = $sorton;
			$sortlinks['sortorder'] = $sortorder;
                        if ( $result[1] <= $limitstart ) $limitstart = 0;
                        $pagination = new JPagination($result[1], $limitstart, $limit );
			$this->assignRef('pagination', $pagination);
			$this->assignRef('totalresults', $totalresults);
			$this->assignRef('sortlinks', $sortlinks);
			$this->assignRef('listjobconfig',$result[2]);
		}elseif($layout== 'myresumes'){												// my resumes
			$page_title .=  ' - ' . JText::_('JS_MY_RESUMES');
			$sort =  JRequest::getVar('sortby','');	
			if (isset($sort)){	if ($sort == '') 	{$sort='createddesc';}
			}else	{$sort='createddesc';}
			$sortby = $this->getResumeListOrdering($sort);
			$result =  $model->getMyResumesbyUid($uid,$sortby,$limit,$limitstart);	
			$this->assignRef('resumes', $result[0]);
			$this->assignRef('resumestyle', $result[2]);
                        if ( $result[1] <= $limitstart ) $limitstart = 0;
                        $pagination = new JPagination($result[1], $limitstart, $limit );
			$this->assignRef('pagination', $pagination);
			$sortlinks = $this->getResumeListSorting($sort);
			$sortlinks['sorton'] = $sorton;
			$sortlinks['sortorder'] = $sortorder;
			$this->assignRef('sortlinks', $sortlinks);
		}elseif($layout== 'formresume'){												// form resume
			$page_title .=  ' - ' . JText::_('JS_RESUME_FORM');
			if (isset($_GET['rd'])) $resumeid = $_GET['rd']; else $resumeid = '';
			$resumeid=($router_mode_sef==2)? $common_model->parseId(JRequest::getVar('rd','')):JRequest::getVar('rd','');
			$result =  $model->getResumebyId($resumeid, $uid);	
			$resumelists =  $model->getEmpOptions();
			//$resumelists =  $model->get('EmpOptions');
			if(!$uid){
				
				$session = JFactory::getSession();
				$visitor = $session->get('jsjob_jobapply');
				$this->assignRef('visitor', $visitor);
			}
			$this->assignRef('resume', $result[0]);
			$this->assignRef('userfields', $result[2]);
			$this->assignRef('fieldsordering', $result[3]);
			$this->assignRef('canaddnewresume', $result[4]);
			$this->assignRef('packagedetail', $result[5]);
			$this->assignRef('resumelists', $resumelists);
			$this->assignRef('vm', JRequest::getVar('vm',''));
			JHTML::_('behavior.formvalidation');
			if(!$uid){
				$result1 =  $common_model->getCaptchaForForm();
				$this->assignRef('captcha', $result1);
			}	
		}elseif($layout== 'mycoverletters'){												// my cover letters
                        $page_title .=  ' - ' . JText::_('JS_MY_COVER_LETTERS');
			$result =  $model->getMyCoverLettersbyUid($uid,$limit,$limitstart);	
			$this->assignRef('coverletters', $result[0]);
                        if ( $result[1] <= $limitstart ) $limitstart = 0;
                        $pagination = new JPagination($result[1], $limitstart, $limit );
			$this->assignRef('pagination', $pagination);
		}elseif($layout== 'view_coverletters'){												// view cover letters
			$page_title .=  ' - ' . JText::_('JS_VIEW_COVER_LETTERS');
			$userid = $_GET['clu'];
			$userid =  JRequest::getVar('clu','');	
			$result =  $model->getMyCoverLettersbyUid($userid,$limit,$limitstart);	
			$this->assignRef('coverletters', $result[0]);
                        if ( $result[1] <= $limitstart ) $limitstart = 0;
                        $pagination = new JPagination($result[1], $limitstart, $limit );
			$this->assignRef('pagination', $pagination);
			$this->assignRef('vts', JRequest::getVar('vts',''));
			$this->assignRef('rd', JRequest::getVar('rd',''));
			$this->assignRef('bd', JRequest::getVar('bd',''));
		}elseif($layout== 'my_jobsearches'){												// my job searches
                        $page_title .=  ' - ' . JText::_('JS_JOB_SAVE_SEARCHES');
			$result =  $model->getMyJobSearchesbyUid($uid,$limit,$limitstart);	
			$this->assignRef('jobsearches', $result[0]);
                        if ( $result[1] <= $limitstart ) $limitstart = 0;
                        $pagination = new JPagination($result[1], $limitstart, $limit );
			$this->assignRef('pagination', $pagination);
		}elseif($layout== 'viewjobsearch'){												// view job seach
			$page_title .=  ' - ' . JText::_('JS_VIEW_JOB_SEARCHES');
			$id =  JRequest::getVar('js','');	
			$search =  $model->getJobSearchebyId($id);	
			if (isset ($search)){
				$_SESSION['jobsearch_title'] = $search->jobtitle;
				if ($search->category != 0) $_SESSION['jobsearch_jobcategory'] = $search->category; else $_SESSION['jobsearch_jobcategory'] = '';
				if ($search->jobtype != 0) $_SESSION['jobsearch_jobtype'] = $search->jobtype; else $_SESSION['jobsearch_jobtype'] = '';
				if ($search->jobstatus != 0) $_SESSION['jobsearch_jobstatus'] = $search->jobstatus; else $_SESSION['jobsearch_jobstatus'] = '';
				if ($search->salaryrange != 0) $_SESSION['jobsearch_jobsalaryrange'] = $search->salaryrange; else $_SESSION['jobsearch_jobsalaryrange'] = '';
				if ($search->heighesteducation != 0) $_SESSION['jobsearch_heighestfinisheducation'] = $search->heighesteducation; else $_SESSION['jobsearch_heighestfinisheducation'] = '';
				if ($search->shift != 0) $_SESSION['jobsearch_shift'] = $search->shift; else $_SESSION['jobsearch_shift'] = '';
				$_SESSION['jobsearch_experience'] = $search->experience;
				$_SESSION['jobsearch_durration'] = $search->durration;
				if ($search->startpublishing != '0000-00-00 00:00:00') $_SESSION['jobsearch_startpublishing'] = $search->startpublishing; else $_SESSION['jobsearch_startpublishing'] = '';
				if ($search->stoppublishing != '0000-00-00 00:00:00') $_SESSION['jobsearch_stoppublishing'] = $search->stoppublishing; else $_SESSION['jobsearch_stoppublishing'] = '';
				if ($search->company != 0) $_SESSION['jobsearch_company'] = $search->company; else $_SESSION['jobsearch_company'] = '';
				$_SESSION['jobsearch_country'] = $search->country;
				$_SESSION['jobsearch_state'] = $search->state;
				$_SESSION['jobsearch_county'] = $search->county;
				$_SESSION['jobsearch_city'] = $search->city;
				$_SESSION['jobsearch_zipcode'] = $search->zipcode;
			
			}
			$mainframe->redirect(JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=job_searchresults&Itemid='.$itemid));
		}elseif($layout== 'formcoverletter'){												// form cover letter
                        $page_title .=  ' - ' . JText::_('JS_COVER_LETTER_FORM');
			if (isset($_GET['cl'])) $letterid = $_GET['cl']; else $letterid = null;
			$letterid=($router_mode_sef==2)? $common_model->parseId(JRequest::getVar('cl','')):JRequest::getVar('cl','');
			$result =  $model->getCoverLetterbyId($letterid, $uid);	
			$this->assignRef('coverletter', $result[0]);
			$this->assignRef('canaddnewcoverletter', $result[4]);
			$this->assignRef('packagedetail', $result[5]);
			JHTML::_('behavior.formvalidation');
		}elseif($layout== 'view_coverletter'){												// view cover letter
                        $page_title .=  ' - ' . JText::_('JS_VIEW_COVER_LETTER');
			$letterid=($router_mode_sef==2)? $common_model->parseId(JRequest::getVar('cl','')):JRequest::getVar('cl','');
			$result =  $model->getCoverLetterbyId($letterid,null);	
			$this->assignRef('coverletter', $result[0]);
			$this->assignRef('vct', JRequest::getVar('vct',''));
		}elseif(($layout== 'view_resume') or ($layout== 'resume_print') ){										// view resume
			if (isset($_GET['id']))
				$empid = $_GET['id'];
			else
				$empid = '';
			if ($empid != ''){
				$application =  $model->getEmpApplicationbyid($empid);	
			}else{
				//$resumeid = $_GET['rd'];
				$resumeid=($router_mode_sef==2)? $common_model->parseId(JRequest::getVar('rd','')):JRequest::getVar('rd','');
				$myresume =  JRequest::getVar('vm','');
				$jobid = JRequest::getVar('bd','');
				$folderid = JRequest::getVar('fd','');
				$catid = JRequest::getVar('cat','');
				$resumesubcat = JRequest::getVar('resumesubcat','');
				if ($jobid == '0') $jobid ='';

				$result =  $model->getResumeViewbyId($uid, $jobid, $resumeid,$myresume);
				$this->assignRef('resume', $result[0]);
				$this->assignRef('resume2', $result[1]);
				$this->assignRef('resume3', $result[2]);
				$this->assignRef('fieldsordering', $result[3]);
				$this->assignRef('canview', $result[4]);
				$this->assignRef('coverletter', $result[5]);// for new feature coverletter
				$this->assignRef('userfields', $result[6]);
				
				$this->assignRef('vm', JRequest::getVar('vm',''));
				if(!$jobid)$jobid =0;
				$this->assignRef('bd', $jobid);
				$this->assignRef('fd', $folderid);
				$this->assignRef('ms', $myresume);
				$this->assignRef('catid', $catid);
				$this->assignRef('subcatid', $resumesubcat);

			}
		}elseif(($layout== 'resume_download') || ($layout== 'resume_view')){	// resume view & download
			$empid = $_GET['rq'];
			$application =  $model->getEmpApplicationbyid($empid);	
		}elseif($layout== 'new_injsjobs'){												// new in jsjobs
                        $page_title .=  ' - ' . JText::_('JS_WELCOME_JSJOBS');
			$result =  $model->getUserType($uid);	
			$this->assignRef('usertype', $result[0]);
			$this->assignRef('lists', $result[1]);
		}elseif($layout== 'packages'){												// job seeker package
                        $page_title .=  ' - ' . JText::_('JS_PACKAGES');
			$result =  $model->getJobSeekerPackages($limit,$limitstart);
			$this->assignRef('packages', $result[0]);
                        if ( $result[1] <= $limitstart ) $limitstart = 0;
                        $pagination = new JPagination($result[1], $limitstart, $limit );
			$this->assignRef('pagination', $pagination);
		}elseif($layout== 'package_buynow'){
                        $page_title .=  ' - ' . JText::_('JS_BUY_NOW');
			$packageid =  JRequest::getVar('gd');
			$package =$model->getJobSeekerPackageById($packageid);
			$paymentmethod=  $common_model->getPaymentMethodsConfig();
			$ideal_data=  $common_model->getIdealPayment();
			$this->assignRef('package', $package);
			$this->assignRef('pb', JRequest::getVar('pb',''));
			$this->assignRef('paymentmethod', $paymentmethod);
			$this->assignRef('idealdata', $ideal_data);
			
		}elseif($layout== 'package_details'){
                        $page_title .=  ' - ' . JText::_('JS_PACKAGE_DETAILS');
			$packageid =  JRequest::getVar('gd');
			
			$package =$model->getJobSeekerPackageById($packageid);
			$this->assignRef('package', $package);
			
		}elseif($layout== 'purchasehistory'){												// my resume searches
                        //$page_title .=  ' - ' . JText::_('JS_PACKAGES');
			$result =  $model->getJobSeekerPurchaseHistory($uid,$limit,$limitstart);
			$this->assignRef('packages', $result[0]);
                        if ( $result[1] <= $limitstart ) $limitstart = 0;
                        $pagination = new JPagination($result[1], $limitstart, $limit );
			$this->assignRef('pagination', $pagination);
		}elseif($layout== 'my_stats'){ 							// my stats
                        $page_title .=  ' - ' . JText::_('JS_MY_STATS');
			$result =  $model->getMyStats_JobSeeker($uid);
				$this->assignRef('resumeallow', $result[0]);
				$this->assignRef('totalresume', $result[1]);
				$this->assignRef('featuredresumeallow', $result[2]);
				$this->assignRef('totalfeaturedresume', $result[3]);
				$this->assignRef('goldresumeallow', $result[4]);
				$this->assignRef('totalgoldresume', $result[5]);
				$this->assignRef('coverlettersallow', $result[6]);
				$this->assignRef('totalcoverletters', $result[7]);
				if(isset($result[8])){
					$this->assignRef('package', $result[8]);
					$this->assignRef('packagedetail', $result[9]);
				}
				$this->assignRef('ispackagerequired', $result[10]);
		}elseif($layout== 'jsmessages'){ 							// emp messages
                        $page_title .=  ' - ' . JText::_('JS_MESSAGES');
			$result =  $model->getMessagesbyJobsforJobSeeker($uid,$limit,$limitstart);
			$this->assignRef('messages', $result[0]);
                        if ( $result[1] <= $limitstart ) $limitstart = 0;
                        $pagination = new JPagination($result[1], $limitstart, $limit );
			$this->assignRef('pagination', $pagination);
		}elseif($layout== 'controlpanel'){
			$jscontrolpanel = $common_model->getConfigByFor('jscontrolpanel');
			if($uid){
				$packagedetail = $model->canAddNewResume($uid);
				$this->assignRef('packagedetail',$packagedetail[1]);
			}
			$this->assignRef('jscontrolpanel', $jscontrolpanel);
		}elseif($layout== 'jobalertunsubscribe'){
			$email =  JRequest::getVar('email');	
			$this->assignRef('email', $email);
		}elseif($layout == 'userregister'){
			if(!$uid){
				$userrole = JRequest::getVar('userrole');
				$this->assignRef('userrole',$userrole);
				$result1 =  $common_model->getCaptchaForForm();
				$this->assignRef('captcha', $result1);
			}else{
				$mainframe->redirect('index.php?option=com_users&view=profile&Itemid='.$itemid);
			}	
		}elseif($layout == 'userlogin'){
			$userrole = JRequest::getVar('ur');
			$return = JRequest::getVar('return');
			$this->assignRef('userrole',$userrole);
			$this->assignRef('loginreturn',$return);
		}
		
                $document->setTitle( $page_title);
                //if ($mainframe->getCfg('MetaAuthor') == '1') $mainframe->addMetaTag('author', 'JS Jobs 1.6');

		$this->assignRef('application', $application);
		$this->assignRef('config', $config);
		$this->assignRef('socailsharing', $socialconfig);
		
		$this->assignRef('theme', $theme);
		$this->assignRef('userrole', $userrole);
//		$option =  $this->get('Option');
		$this->assignRef('option', $option);
		$this->assignRef('params', $params);
		$this->assignRef('viewtype', $viewtype);
		$this->assignRef('jobseekerlinks', $jobseekerlinks);
		$this->assignRef('employerlinks', $employerlinks);
		$this->assignRef('uid', $uid);
		$this->assignRef('id', $id);
		$this->assignRef('Itemid', $itemid);
		$this->assignRef('pdflink', $pdflink);
		$this->assignRef('printlink', $printlink);
		$this->assignRef('isjobsharing', $_client_auth_key);
			
		
		parent :: display($tpl);	
	
		}
	function getJobListSorting( $sort ) {
		$sortlinks['title'] = $this->getSortArg("title",$sort);
		$sortlinks['category'] = $this->getSortArg("category",$sort);
		$sortlinks['jobtype'] = $this->getSortArg("jobtype",$sort);
		$sortlinks['jobstatus'] = $this->getSortArg("jobstatus",$sort);
		$sortlinks['company'] = $this->getSortArg("company",$sort);
		$sortlinks['salaryrange'] = $this->getSortArg("salaryto",$sort);
		$sortlinks['country'] = $this->getSortArg("country",$sort);
		$sortlinks['created'] = $this->getSortArg("created",$sort);
		$sortlinks['apply_date'] = $this->getSortArg("apply_date",$sort);
		
		return $sortlinks;
	}

	function getEmpListSorting( $sort ) {
		$sortlinks['name'] = $this->getSortArg("name",$sort);
		$sortlinks['category'] = $this->getSortArg("category",$sort);
		$sortlinks['jobtype'] = $this->getSortArg("jobtype",$sort);
		$sortlinks['jobsalaryrange'] = $this->getSortArg("jobsalaryrange",$sort);
		$sortlinks['apply_date'] = $this->getSortArg("apply_date",$sort);
		$sortlinks['email'] = $this->getSortArg("email",$sort);
		
		return $sortlinks;
	}

	function getResumeListSorting( $sort ) {
		$sortlinks['application_title'] = $this->getSortArg("application_title",$sort);
		$sortlinks['jobtype'] = $this->getSortArg("jobtype",$sort);
		$sortlinks['salaryrange'] = $this->getSortArg("salaryrange",$sort);
		$sortlinks['created'] = $this->getSortArg("created",$sort);
		
		return $sortlinks;
	}

	function getJobListOrdering( $sort ) {
		global $sorton, $sortorder;
		switch ( $sort ) {
			case "titledesc": $ordering = "job.title DESC"; $sorton = "title"; $sortorder="DESC"; break;
			case "titleasc": $ordering = "job.title ASC";  $sorton = "title"; $sortorder="ASC"; break;
			case "categorydesc": $ordering = "cat.cat_title DESC"; $sorton = "category"; $sortorder="DESC"; break;
			case "categoryasc": $ordering = "cat.cat_title ASC";  $sorton = "category"; $sortorder="ASC"; break;
			case "jobtypedesc": $ordering = "job.jobtype DESC";  $sorton = "jobtype"; $sortorder="DESC"; break;
			case "jobtypeasc": $ordering = "job.jobtype ASC";  $sorton = "jobtype"; $sortorder="ASC"; break;
			case "jobstatusdesc": $ordering = "job.jobstatus DESC";  $sorton = "jobstatus"; $sortorder="DESC"; break;
			case "jobstatusasc": $ordering = "job.jobstatus ASC";  $sorton = "jobstatus"; $sortorder="ASC"; break;
			case "companydesc": $ordering = "job.company DESC";  $sorton = "company"; $sortorder="DESC"; break;
			case "companyasc": $ordering = "job.company ASC";  $sorton = "company"; $sortorder="ASC"; break;
			case "salarytoasc": $ordering = "salaryto ASC";  $sorton = "salaryrange"; $sortorder="ASC"; break;
			case "salarytodesc": $ordering = "salaryto DESC";  $sorton = "salaryrange"; $sortorder="DESC"; break;
			case "salaryrangedesc": $ordering = "salary.rangeend DESC";  $sorton = "salaryrange"; $sortorder="DESC"; break;
			case "salaryrangeasc": $ordering = "salary.rangestart ASC";  $sorton = "salaryrange"; $sortorder="ASC"; break;
			case "countrydesc": $ordering = "country.name DESC";  $sorton = "country"; $sortorder="DESC"; break;
			case "countryasc": $ordering = "country.name ASC";  $sorton = "country"; $sortorder="ASC"; break;
			case "createddesc": $ordering = "job.created DESC";  $sorton = "created"; $sortorder="DESC"; break;
			case "createdasc": $ordering = "job.created ASC";  $sorton = "created"; $sortorder="ASC"; break;
			case "apply_datedesc": $ordering = "apply.apply_date DESC";  $sorton = "apply_date"; $sortorder="DESC"; break;
			case "apply_dateasc": $ordering = "apply.apply_date ASC";  $sorton = "apply_date"; $sortorder="ASC"; break;
			default: $ordering = "job.id DESC";
		}
		return $ordering;
	}

	function getResumeListOrdering( $sort ) {
		global $sorton, $sortorder;
		switch ( $sort ) {
			case "application_titledesc": $ordering = "resume.application_title DESC"; $sorton = "application_title"; $sortorder="DESC"; break;
			case "application_titleasc": $ordering = "resume.application_title ASC";  $sorton = "application_title"; $sortorder="ASC"; break;
			case "jobtypedesc": $ordering = "resume.jobtype DESC";  $sorton = "jobtype"; $sortorder="DESC"; break;
			case "jobtypeasc": $ordering = "resume.jobtype ASC";  $sorton = "jobtype"; $sortorder="ASC"; break;
			case "salaryrangedesc": $ordering = "salary.rangeend DESC";  $sorton = "salaryrange"; $sortorder="DESC"; break;
			case "salaryrangeasc": $ordering = "salary.rangestart ASC";  $sorton = "salaryrange"; $sortorder="ASC"; break;
			case "createddesc": $ordering = "resume.create_date DESC";  $sorton = "created"; $sortorder="DESC"; break;
			case "createdasc": $ordering = "resume.create_date ASC";  $sorton = "created"; $sortorder="ASC"; break;
			default: $ordering = "resume.id DESC";
		}
		return $ordering;
	}

	function getEmpListOrdering( $sort ) {
		global $sorton, $sortorder;
		switch ( $sort ) {
			case "namedesc": $ordering = "app.first_name DESC"; $sorton = "name"; $sortorder="DESC"; break;
			case "nameasc": $ordering = "app.first_name ASC";  $sorton = "name"; $sortorder="ASC"; break;
			case "categorydesc": $ordering = "cat.cat_title DESC"; $sorton = "category"; $sortorder="DESC"; break;
			case "categoryasc": $ordering = "cat.cat_title ASC";  $sorton = "category"; $sortorder="ASC"; break;
			case "jobtypedesc": $ordering = "app.jobtype DESC";  $sorton = "jobtype"; $sortorder="DESC"; break;
			case "jobtypeasc": $ordering = "app.jobtype ASC";  $sorton = "jobtype"; $sortorder="ASC"; break;
			case "jobsalaryrangedesc": $ordering = "salary.rangestart DESC";  $sorton = "jobsalaryrange"; $sortorder="DESC"; break;
			case "jobsalaryrangeasc": $ordering = "salary.rangestart ASC";  $sorton = "jobsalaryrange"; $sortorder="ASC"; break;
			case "apply_datedesc": $ordering = "apply.apply_date DESC";  $sorton = "apply_date"; $sortorder="DESC"; break;
			case "apply_dateasc": $ordering = "apply.apply_date ASC";  $sorton = "apply_date"; $sortorder="ASC"; break;
			case "emaildesc": $ordering = "app.email_address DESC";  $sorton = "email"; $sortorder="DESC"; break;
			case "emailasc": $ordering = "app.email_address ASC";  $sorton = "email"; $sortorder="ASC"; break;
			default: $ordering = "job.id DESC";
		}
		return $ordering;
	}

	function getSortArg( $type, $sort ) {
		$mat = array();
		if ( preg_match( "/(\w+)(asc|desc)/i", $sort, $mat ) ) {
			if ( $type == $mat[1] ) {
				return ( $mat[2] == "asc" ) ? "{$type}desc" : "{$type}asc";
			} else {
				return $type . $mat[2];
			}
		}
		return "iddesc";
	}
	
	
	


}
?>
