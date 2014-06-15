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
 * File Name:	views/employer/view.html.php
 ^ 
 * Description: HTML view class for employer
 ^ 
 * History:		NONE
 ^ 
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

class JSJobsViewEmployer extends JViewLegacy
{
	function display($tpl = null)
	{
		global $mainframe, $sorton, $sortorder, $option,$_client_auth_key,$socialconfig;

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

		$document	   =& JFactory::getDocument();
		
		$layout =  JRequest::getVar('layout');

		$model = &$this->getModel('Employer','JSJobsModel');
		$common_model = &$this->getModel('common','JSJobsModel');

		if($_client_auth_key=="") {
			$auth_key=$common_model->getClientAuthenticationKey();
			$_client_auth_key=$auth_key;
		}
		if(!$socialconfig) $socialconfig=$common_model->getConfigByFor('social');
		// get configurations
		//$config = Array();
                $session = JFactory::getSession();
                $config = $session->get('jsjobconfig_dft');
				$curuser = $session->get('jsjobcur_usr');
				if($curuser != $uid) unset($config);
				$session->set('jsjobcur_usr', $uid);
				if(isset($config))
				if($config['testing_mode'] == 1)	unset($config);
			    if(!isset($config)){
					$results =  $common_model->getConfig('');
					if ($results){ //not empty
						foreach ($results as $result){
							$config[$result->configname] = $result->configvalue;
						}
						$session->set('jsjobconfig_dft', $config);
					}

                }
		$needlogin = true;
                switch($layout){
                    case 'controlpanel': if ($config['visitorview_emp_conrolpanel'] == 1) $needlogin = false; break;
					case 'packages': 
						if ($config['visitorview_emp_packages'] == 1){
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
                    case 'package_details': if ($config['visitorview_emp_viewpackage'] == 1) $needlogin = false; break;
                    case 'resumesearch': if ($config['visitorview_emp_resumesearch'] == 1) $needlogin = false; break;
                    case 'resumesearchresult': if ($config['visitorview_emp_resumesearchresult'] == 1) $needlogin = false; break;
                    case 'view_company': if ($config['visitorview_emp_viewcompany'] == 1) $needlogin = false; break;
                    case 'view_job': if ($config['visitorview_emp_viewjob'] == 1) $needlogin = false; break;
                    case 'myjobs': if ($config['visitor_can_edit_job'] == 1 && $config['visitor_can_post_job'] == 1) $needlogin = false; break;
                    case 'formjob': if ($config['visitor_can_post_job'] == 1){
                        if($user->guest){
                            $link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formjob_visitor&Itemid='.$itemid;
                            $mainframe->redirect(JRoute::_($link));
                        }
                    }
                    break;
                    case 'formjob_visitor': if ($config['visitor_can_post_job'] == 1) $needlogin = false; break;
                    default : $needlogin = true; break;
                }
                if ($user->guest) { // redirect user if not login
                    if($needlogin){
                        $_SESSION['jsjobs_option'] = 'com_jsjobs';
                        $_SESSION['jsjobs_view'] = 'employer';
                        $_SESSION['jsjobs_red_layout'] = $layout;
                        if($layout == 'package_buynow'){
								$pb = JRequest::getVar('pb');
								$gd = JRequest::getVar('gd');
								if($pb) $_SESSION['pb'] = $pb;
								if($gd) $_SESSION['gd'] = $gd;
						}
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
		
		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart	= $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );
		$limitstart =  JRequest::getVar('limitstart',0);

		if (isset($_SESSION['jsjobs_layout'])){
			if ($layout != $_SESSION['jsjobs_layout']) {
				$_SESSION['jsjobs_layout'] = $layout;
				//$limitstart = 0;
			}
		}else $_SESSION['jsjobs_layout'] = $layout;

		$params = & $mainframe->getPageParameters('com_jsjobs');
		
		// get user role
		if (isset($_SESSION['jsuserrole'])) $userrole = $_SESSION['jsuserrole']; else $userrole=null;
		//$config = Array();
		if($curuser != $uid) unset($userrole);
		if (!isset($userrole)){
			$userrole =  $common_model->getUserRole($uid);	
			if (isset($userrole)){ //not empty
				$_SESSION['jsuserrole'] = $userrole;	
			}else{
				if ($layout != 'view_job') {
					if (! $user->guest){//echo '<br> new in jsjobs';
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
		if(isset($userrole->rolefor)) {
			if ($userrole->rolefor == 1) { // employer
				if($config['tmenu_emcontrolpanel'] == 1){$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=controlpanel&Itemid='.$itemid;
				$employerlinks [] = array($link, JText::_('JS_CONTROL_PANEL'),1);
				}if($config['tmenu_emnewjob'] == 1){
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formjob&Itemid='.$itemid;
				$employerlinks [] = array($link, JText::_('JS_NEW_JOB'),0);
				}if($config['tmenu_emmyjobs'] == 1){
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid='.$itemid;
				$employerlinks [] = array($link, JText::_('JS_MY_JOBS'),0);
				}if($config['tmenu_emmycompanies'] == 1){
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=mycompanies&Itemid='.$itemid;
				$employerlinks [] = array($link, JText::_('JS_MY_COMPANIES'),0);
				}if($config['tmenu_emappliedresume'] == 1){
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=alljobsappliedapplications&Itemid='.$itemid;
				$employerlinks [] = array($link, JText::_('JS_APPLIED_RESUME'),-1);
				}
			}else{

				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=controlpanel&Itemid='.$itemid;
				$jobseekerlinks [] = array($link, JText::_('JS_CONTROL_PANEL'),1);
				
				
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobcat&Itemid='.$itemid;
				$jobseekerlinks [] = array($link, JText::_('JS_JOB_CATEGORIES'),0);

				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobsearch&Itemid='.$itemid;
				$jobseekerlinks [] = array($link, JText::_('JS_SEARCH_JOB'),0);

				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=listnewestjobs&Itemid='.$itemid;
				$jobseekerlinks [] = array($link, JText::_('JS_NEWEST_JOBS'),0);
			
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myresumes&Itemid='.$itemid;
				$jobseekerlinks [] = array($link, JText::_('JS_MY_RESUMES'),-1);

			}
		}else{ // user not logined
				if($config['tmenu_vis_emcontrolpanel'] == 1){$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=controlpanel&Itemid='.$itemid;
				$employerlinks [] = array($link, JText::_('JS_CONTROL_PANEL'),1);
				}if($config['tmenu_vis_emnewjob'] == 1){
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formjob_visitor&Itemid='.$itemid;
				$employerlinks [] = array($link, JText::_('JS_NEW_JOB'),0);
				}if($config['tmenu_vis_emmyjobs'] == 1){
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid='.$itemid;
				$employerlinks [] = array($link, JText::_('JS_MY_JOBS'),0);
				}if($config['tmenu_vis_emmycompanies'] == 1){
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=mycompanies&Itemid='.$itemid;
				$employerlinks [] = array($link, JText::_('JS_MY_COMPANIES'),0);
				}if($config['tmenu_vis_emappliedresume'] == 1){
				$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=alljobsappliedapplications&Itemid='.$itemid;
				$employerlinks [] = array($link, JText::_('JS_APPLIED_RESUME'),-1);
				}	
			}
		
                $page_title = $params->get('page_title');
                $config['offline']=0;
                

		if($layout== 'myjobs'){ 							// my jobs
				$page_title .=  ' - ' . JText::_('JS_MY_JOBS');
				if (isset($_GET['sortby']))
				$sort = $_GET['sortby'];

				$sort =  JRequest::getVar('sortby','');
				//visitor jobid
				if (isset($_GET['email']))
				$vis_email = $_GET['email'];
				$vis_email =  JRequest::getVar('email','');
				if (isset($_GET['jobid']))
				$jobid = $_GET['jobid'];
				$jobid =  JRequest::getVar('jobid','');

				if (isset($sort)){
					if ($sort == '') 
					{$sort='createddesc';}
				}else
					{$sort='createddesc';}
			$sortby = $this->getJobListOrdering($sort);
			$result =  $model->getMyJobs($uid,$sortby,$limit,$limitstart,$vis_email,$jobid);
			
			$sortlinks = $this->getJobListSorting($sort);
			$sortlinks['sorton'] = $sorton;
			$sortlinks['sortorder'] = $sortorder;
			$this->assignRef('jobs', $result[0]);
			$this->assignRef('listjobconfig',$result[2]);
			if(isset($result[1])){
				if ( $result[1] <= $limitstart ) $limitstart = 0;
				$pagination = new JPagination($result[1], $limitstart, $limit );
				$this->assignRef('pagination', $pagination);
			}
			$this->assignRef('sortlinks', $sortlinks);
		}elseif($layout== 'my_stats'){ 							// my stats
                        $page_title .=  ' - ' . JText::_('JS_MY_STATS');
			$result =  $model->getMyStats_Employer($uid);
			$this->assignRef('companiesallow', $result[0]);
			$this->assignRef('totalcompanies', $result[1]);
			$this->assignRef('jobsallow', $result[2]);
			$this->assignRef('totaljobs', $result[3]);
			$this->assignRef('publishedjob', $result[14]);
			$this->assignRef('expiredjob', $result[15]);
			$this->assignRef('featuredcompainesallow', $result[4]);
			$this->assignRef('totalfeaturedcompanies', $result[5]);
			$this->assignRef('goldcompaniesallow', $result[6]);
			$this->assignRef('totalgoldcompanies', $result[7]);
			$this->assignRef('goldjobsallow', $result[8]);
			$this->assignRef('totalgoldjobs', $result[9]);
			$this->assignRef('publishedgoldjob', $result[16]);
			$this->assignRef('expiregoldjob', $result[17]);
			$this->assignRef('featuredjobsallow', $result[10]);
			$this->assignRef('totalfeaturedjobs', $result[11]);
			$this->assignRef('publishedfeaturedjob', $result[18]);
			$this->assignRef('expirefeaturedjob', $result[19]);
				if(isset($result[12])){
					$this->assignRef('package', $result[12]);
					$this->assignRef('packagedetail', $result[13]);
				}
			$this->assignRef('ispackagerequired', $result[20]);
			$this->assignRef('goldcompaniesexpire', $result[21]);
			$this->assignRef('featurescompaniesexpire', $result[22]);
		}elseif($layout== 'controlpanel'){
			$emcontrolpanel = $common_model->getConfigByFor('emcontrolpanel');
			if($uid){
				$packagedetail = $model->canAddNewJob($uid);
				$this->assignRef('packagedetail',$packagedetail[1]);
			}
			$this->assignRef('emcontrolpanel', $emcontrolpanel);
		}elseif($layout== 'mycompanies'){ 							// my companies
			$page_title .=  ' - ' . JText::_('JS_MY_COMPANIES');
			$result =  $model->getMyCompanies($uid,$limit,$limitstart);	
			$companies = $result[0];
			$this->assignRef('companies', $companies);
                        if ( $result[1] <= $limitstart ) $limitstart = 0;
                        $pagination = new JPagination($result[1], $limitstart, $limit );
			$this->assignRef('pagination', $pagination);
		}elseif($layout== 'mydepartments'){ 							// my departments
                        $page_title .=  ' - ' . JText::_('JS_MY_DEPARTMENTS');
			$result =  $model->getMyDepartments($uid,$limit,$limitstart);	
			$departments = $result[0];
			$totalresults = $result[1];
			$this->assignRef('departments', $departments);
                        if ( $result[1] <= $limitstart ) $limitstart = 0;
                        $pagination = new JPagination($result[1], $limitstart, $limit );
			$this->assignRef('pagination', $pagination);
		}elseif($layout== 'alljobsappliedapplications'){				 // all jobs applied application
                        $page_title .=  ' - ' . JText::_('JS_APPLIED_RESUME');
			if (isset($_GET['sortby']))
				$sort = $_GET['sortby'];
			
			$sort =  JRequest::getVar('sortby','');	
			if (isset($sort)){
				if ($sort == '') 
					{$sort='createddesc';}
			}else
				{$sort='createddesc';}
			$sortby = $this->getJobListOrdering($sort);
			$result =  $model->getJobsAppliedResume($uid,$sortby,$limit,$limitstart);	
			$sortlinks = $this->getJobListSorting($sort);
                        if ( $result[1] <= $limitstart ) $limitstart = 0;
                        $pagination = new JPagination($result[1], $limitstart, $limit );
			$this->assignRef('pagination', $pagination);
			$sortlinks['sorton'] = $sorton;
			$sortlinks['sortorder'] = $sortorder;
			$this->assignRef('jobs', $result[0]);
			$this->assignRef('sortlinks', $sortlinks);
		}elseif($layout== 'job_shortlistcandidates'){									 // job applied applications
			$page_title .=  ' - ' . JText::_('JS_SHORT_LIST_CANDIDATES');


			
			$sort =  JRequest::getVar('sortby','');	
			if (isset($sort)){
				if ($sort == '') 
					{$sort='apply_datedesc';}
			}else
				{$sort='apply_datedesc';}
			$sortby = $this->getEmpListOrdering($sort);
			//$jobid = $_GET['jobid'];

			//$result =  $model->getJobAppliedResume($uid,$jobid,$sortby,$limit,$limitstart);	

			$result =  $model->getShortListCandidate($jobid,$sortby ,$limit,$limitstart);	
			$sortlinks = $this->getEmpListSorting($sort);
			$sortlinks['sorton'] = $sorton;
			$sortlinks['sortorder'] = $sortorder;
			$this->assignRef('candidatelist', $result[0]);
                        if ( $result[1] <= $limitstart ) $limitstart = 0;
                        $pagination = new JPagination($result[1], $limitstart, $limit );
			$this->assignRef('pagination', $pagination);
			$this->assignRef('jobid', $jobid);
			$this->assignRef('sortlinks', $sortlinks);
			
		}elseif($layout== 'job_appliedapplications'){									 // job applied applications
			$page_title .=  ' - ' . JText::_('JS_JOB_APPLIED_APPLICATIONS');
			if (isset($_GET['sortby']))
				$sort = $_GET['sortby'];
			
			$sort =  JRequest::getVar('sortby','');	
			if (isset($sort)){
				if ($sort == '') 
					{$sort='apply_datedesc';}
			}else
				{$sort='apply_datedesc';}
			$sortby = $this->getEmpListOrdering($sort);
			//$jobid = $_GET['jobid'];
			if($router_mode_sef==2){
				$jobid=$common_model->parseId(JRequest::getVar('bd',''));
			}else{
				$jobid =  JRequest::getVar('bd','');	
			} 
			$tab_action =  JRequest::getVar('ta','');
			$job_applied_call =  JRequest::getVar('jacl','');
			$session = JFactory::getSession();
			$needle_array =$session->get('jsjobappliedresumefilter');			
			if(empty($tab_action)) $tab_action=1;	
			$needle_values=($needle_array ? $needle_array:"");
			$result =  $model->getJobAppliedResume($needle_values,$uid,$jobid,$tab_action,$sortby,$limit,$limitstart);	
			$result1 =  $model->getJobAppliedResumeSearchOption($uid);	
			$application = $result[0];
			$totalresults = $result[1];
			$jobtitle = $result[2];
			$sortlinks = $this->getEmpListSorting($sort);
			$sortlinks['sorton'] = $sorton;
			$sortlinks['sortorder'] = $sortorder;
			$this->assignRef('resume', $result[0]);
			$this->assignRef('jobsearches', $result[0]);
                        if ( $result[1] <= $limitstart ) $limitstart = 0;
                        $pagination = new JPagination($result[1], $limitstart, $limit );
			$this->assignRef('pagination', $pagination);
			$this->assignRef('sortlinks', $sortlinks);
			$this->assignRef('jobid', $jobid);
			$this->assignRef('tabaction', $tab_action);
			$this->assignRef('jobtitle', $jobtitle);
			$this->assignRef('job_applied_call', $job_applied_call);
			$this->assignRef('searchoptions', $result1[0]); // for advance search tab 
			$session->clear('jsjobappliedresumefilter');
		}elseif($layout== 'view_department'){ 															// view company
			if($router_mode_sef==2){
				$departmentid = $common_model->parseId(JRequest::getVar('pd',''));
			}else{
				if (isset($_GET['pd']))
					$departmentid = $_GET['pd'];
				if (!isset($departmentid)) $departmentid='';
				$departmentid =  JRequest::getVar('pd','');	
			}
		
			$department =  $model->getDepartmentbyId($departmentid);	
			$this->assignRef('department', $department);
			$this->assignRef('vp', JRequest::getVar('vp',''));
                        if (isset($department)){
                            $page_title .=  ' - ' . $department->name;
                            //if ($mainframe->getCfg('MetaTitle') == '1') $mainframe->addMetaTag('title', $department->name);
                        }
			
		}elseif($layout== 'view_company'){ 															// view company

			if($router_mode_sef==2){
				$companyid=$common_model->parseId(JRequest::getVar('md',''));
				if (!isset($companyid)) $companyid='';
			}else{
				if (isset($_GET['md']))
					$companyid = $_GET['md'];
				if (!isset($companyid)) $companyid='';
				$companyid =  JRequest::getVar('md','');	
			}

			$result =  $model->getCompanybyId($companyid);	
			$company = $result[0];
			$this->assignRef('company', $company);
			$this->assignRef('userfields', $result[2]);
			$this->assignRef('fieldsordering', $result[3]);
			$this->assignRef('vm', JRequest::getVar('vm',''));
			$this->assignRef('jobcat', JRequest::getVar('jobcat',''));
			if (isset($company)){
				$page_title .=  ' - ' . $company->name;
				//if ($mainframe->getCfg('MetaTitle') == '1') $mainframe->addMetaTag('title', $company->name);
			}

		}elseif($layout== 'view_job'){ 															// view job
			if($router_mode_sef==2){
				$jobid=$common_model->parseId(JRequest::getVar('oi',''));
			}else{
				if (isset($_GET['oi']))
					$jobid = $_GET['oi'];
				if (!isset($jobid)) $jobid='';
				$jobid =  JRequest::getVar('oi','');
			}
			$result =  $model->getJobbyId($jobid);
			$job = $result[0];
			$this->assignRef('job', $result[0]);
			$this->assignRef('userfields', $result[2]);
			$this->assignRef('fieldsordering', $result[3]);
			$this->assignRef('listjobconfig', $result[4]);
			$this->assignRef('vj', JRequest::getVar('vj',''));

			if (isset($job)){
				$page_title .=  ' - ' . $job->title;
				$document->setDescription( $job->metadescription );
				$document->setMetadata('keywords', $job->metakeywords);
				//if ($mainframe->getCfg('MetaTitle') == '1') $mainframe->addMetaTag('title', $job->title);
			}

	   }elseif($layout== 'formdepartment'){									//form department
			$page_title .=  ' - ' . JText::_('JS_DEPARTMENT_INFO');
			if($router_mode_sef==2){
				$departmentid = $common_model->parseId(JRequest::getVar('pd',''));
			}else{
				$departmentid =  JRequest::getVar('pd','');
				//$depid=2;
				
			}
			
			
			$result =$model->getDepartmentByIdForForm($departmentid,$uid);
			
			$this->assignRef('department', $result[0]);
			$this->assignRef('lists', $result[1]);
			JHTML::_('behavior.formvalidation');
		}elseif($layout== 'formjob'){												// form job

			$page_title .=  ' - ' . JText::_('JS_JOB_INFO');
			if($router_mode_sef==2){
				$jobid=$common_model->parseId(JRequest::getVar('bd',''));
			}else{
				if (isset($_GET['bd'])) $jobid = $_GET['bd'];
				else $jobid = JRequest::getVar('bd');
				if (!isset($jobid)) $jobid='';
			} 
			$result =  $model->getJobforForm($jobid, $uid,'','');
			if(is_array($result)){
				$this->assignRef('job', $result[0]);
				$this->assignRef('lists', $result[1]);
				$this->assignRef('userfields', $result[2]);
				$this->assignRef('fieldsordering', $result[3]);
				$this->assignRef('canaddnewjob', $result[4]);
				$this->assignRef('packagedetail', $result[5]);
				$this->assignRef('packagecombo', $result[6]);
				//echo '<pre>';print_r($result[6]);
				$this->assignRef('isuserhascompany',$result[7]);
				if(isset($result[8])) $this->assignRef('multiselectedit',$result[8]);
				JHTML::_('behavior.formvalidation');
			}elseif($result==3){
				$this->assignRef('isuserhascompany', $result);
			}
		}elseif($layout== 'formcompany'){											// form company
                        $page_title .=  ' - ' . JText::_('JS_COMPANY_INFO');
                        
			if($router_mode_sef==2){
				$companyid=$common_model->parseId(JRequest::getVar('md',''));
				if (!isset($companyid)) $companyid='';
			}else{
				if (isset($_GET['md']))
				$companyid = $_GET['md'];
				$companyid =  JRequest::getVar('md','');	
				if (!isset($companyid)) $companyid='';
			} 
			
			
			$result =  $model->getCompanybyIdforForm($companyid, $uid,'','','');
			$this->assignRef('company', $result[0]);
			$this->assignRef('lists', $result[1]);
			$this->assignRef('userfields', $result[2]);
			$this->assignRef('fieldsordering', $result[3]);
			$this->assignRef('canaddnewcompany', $result[4]);
			$this->assignRef('packagedetail', $result[5]);
			if(isset($result[6])) $this->assignRef('multiselectedit',$result[6]);
			JHTML::_('behavior.formvalidation');

		}elseif($layout == 'formjob_visitor'){
			if (isset($_GET['email']))
				$companyemail = $_GET['email'];
			$companyemail =  JRequest::getVar('email','');
			if (!isset($companyemail)) $companyemail='';

			if (isset($_GET['jobid']))
				$vis_jobid = $_GET['jobid'];
			$vis_jobid =  JRequest::getVar('jobid','');
			if (!isset($vis_jobid)) $vis_jobid='';


			$result =  $model->getCompanybyIdforForm('', $uid,1,$companyemail,$vis_jobid);
			$this->assignRef('company', $result[0]);
			$this->assignRef('companylists', $result[1]);
			$this->assignRef('companyuserfields', $result[2]);
			$this->assignRef('companyfieldsordering', $result[3]);
			$this->assignRef('canaddnewcompany', $result[4]);
			$this->assignRef('companypackagedetail', $result[5]);
			if(isset($result[6])) $this->assignRef('vmultiselecteditcompany',$result[6]);

			$result =  $model->getJobforForm('', $uid,$vis_jobid,1);
			$this->assignRef('job', $result[0]);
			$this->assignRef('lists', $result[1]);
			$this->assignRef('userfields', $result[2]);
			$this->assignRef('fieldsordering', $result[3]);
			$this->assignRef('canaddnewjob', $result[4]);
			$this->assignRef('packagedetail', $result[5]);
			$this->assignRef('packagedetail', $result[5]);
			if(isset($result[8])) $this->assignRef('vmultiselecteditjob',$result[8]);
			
			JHTML::_('behavior.formvalidation');
			$result1 =  $common_model->getCaptchaForForm();
			$this->assignRef('captcha', $result1);

		}elseif($layout== 'formfolder'){											// form company

			$page_title .=  ' - ' . JText::_('JS_FOLDERS_INFO');
			if($router_mode_sef==2){
				$folderid =$common_model->parseId(JRequest::getVar('fd',''));
			}else{
				$folderid = JRequest::getVar('fd','');
			}
			$result =  $model->getFolderbyIdforForm($folderid, $uid);
			$this->assignRef('folders', $result[0]);
			$this->assignRef('canaddnewfolder', $result[1]);
			$this->assignRef('packagedetail', $result[2]);
			JHTML::_('behavior.formvalidation');
		}elseif($layout== 'resumesearch'){											// resume search
			$page_title .=  ' - ' . JText::_('JS_RESUME_SEARCH');
			$result =  $model->getResumeSearchOptions($uid);	
			$this->assignRef('searchoptions', $result[0]);
			$this->assignRef('searchresumeconfig', $result[1]);
			$this->assignRef('canview', $result[2]);
		}elseif($layout== 'resume_searchresults'){ 															// resume search results
			$page_title .=  ' - ' . JText::_('JS_RESUME_SEARCH_RESULT');
			if (isset($_GET['sortby']))
				$sort = $_GET['sortby'];
			
			$sort =  JRequest::getVar('sortby','');	
			if (isset($sort)){
				if ($sort == '') 
					{$sort='create_datedesc';}
			}else
				{$sort='create_datedesc';}
			$sortby = $this->getResumeListOrdering($sort);
			if ($limit != '') {	$_SESSION['limit']=$limit;
			}else if ($limit == '') {$limit=$_SESSION['limit'];	}
			if (isset($_POST['isresumesearch'])){
				if ($_POST['isresumesearch'] == '1'){
					if(isset($_POST['title'])) $_SESSION['resumesearch_title'] = $_POST['title']; else $_SESSION['resumesearch_title']="";
					if(isset($_POST['name'])) $_SESSION['resumesearch_name'] = $_POST['name']; else $_SESSION['resumesearch_name']="";
					if(isset($_POST['nationality'])) $_SESSION['resumesearch_nationality'] = $_POST['nationality']; else $_SESSION['resumesearch_nationality']="";
					if(isset($_POST['gender'])) $_SESSION['resumesearch_gender'] = $_POST['gender']; else $_SESSION['resumesearch_gender']="";
					
					if (isset($_POST['iamavailable'])) $_SESSION['resumesearch_iamavailable'] = $_POST['iamavailable']; else $_SESSION['resumesearch_iamavailable']="";

					if(isset($_POST['jobcategory'])) $_SESSION['resumesearch_jobcategory'] = $_POST['jobcategory']; else $_SESSION['resumesearch_jobcategory']="";
					if(isset($_POST['jobsubcategory'])) $_SESSION['resumesearch_jobsubcategory'] = $_POST['jobsubcategory']; else $_SESSION['resumesearch_jobsubcategory']="";
					if(isset($_POST['jobtype'])) $_SESSION['resumesearch_jobtype'] = $_POST['jobtype']; else $_SESSION['resumesearch_jobtype']="";
					if(isset($_POST['jobsalaryrange'])) $_SESSION['resumesearch_jobsalaryrange'] = $_POST['jobsalaryrange']; else $_SESSION['resumesearch_jobsalaryrange']="";
					if(isset($_POST['heighestfinisheducation'])) $_SESSION['resumesearch_heighestfinisheducation'] = $_POST['heighestfinisheducation']; else $_SESSION['resumesearch_heighestfinisheducation']="";
					if(isset($_POST['experience'])) $_SESSION['resumesearch_experience'] = $_POST['experience']; else $_SESSION['resumesearch_experience']="";
					if(isset($_POST['currency'])) $_SESSION['resumesearch_currency'] = $_POST['currency']; else $_SESSION['resumesearch_currency']="";
					if(isset($_POST['zipcode'])) $_SESSION['resumesearch_zipcode'] = $_POST['zipcode']; else $_SESSION['resumesearch_zipcode']="";
					if(isset($_POST['keywords'])) $_SESSION['resumesearch_keywords'] = $_POST['keywords']; else $_SESSION['resumesearch_keywords']="";
				}
			}
			$jobstatus='';
			$title = $_SESSION['resumesearch_title'];
			$name = $_SESSION['resumesearch_name'];
			$nationality = $_SESSION['resumesearch_nationality'];
			$gender = $_SESSION['resumesearch_gender'];
			$iamavailable = '';//$_SESSION['resumesearch_iamavailable'];
			$jobcategory = $_SESSION['resumesearch_jobcategory'];
			$jobsubcategory = $_SESSION['resumesearch_jobsubcategory'];
			$jobtype = $_SESSION['resumesearch_jobtype'];
			$jobsalaryrange = $_SESSION['resumesearch_jobsalaryrange'];
			$education = $_SESSION['resumesearch_heighestfinisheducation'];
			$experience = $_SESSION['resumesearch_experience'];
			$currency = $_SESSION['resumesearch_currency'];
			$zipcode = $_SESSION['resumesearch_zipcode'];
			$keywords = $_SESSION['resumesearch_keywords'];

			$result =  $model->getResumeSearch($uid,$title,$name,$nationality,$gender,$iamavailable,$jobcategory,$jobsubcategory,$jobtype,$jobstatus,$currency,$jobsalaryrange,$education
			, $experience,$sortby,$limit,$limitstart,$zipcode,$keywords);	
			if($result != false){
			$options =  $this->get('Options');
			$sortlinks = $this->getResumeListSorting($sort);
			$sortlinks['sorton'] = $sorton;
			$sortlinks['sortorder'] = $sortorder;
                        if ( $result[1] <= $limitstart ) $limitstart = 0;
                        $pagination = new JPagination($result[1], $limitstart, $limit );
			$this->assignRef('pagination', $pagination);
			$this->assignRef('resumes', $result[0]);
			$this->assignRef('searchresumeconfig', $result[2]);
			$this->assignRef('canview', $result[3]);
			$this->assignRef('sortlinks', $sortlinks);
			$true = true;
			$this->assignRef('result', $true);
			}else{
				$this->assignRef('result', $result);
			}
		}elseif($layout== 'resume_bycategory'){ 															// resume by category
			$page_title .=  ' - ' . JText::_('JS_RESUME_BY_CATEGORY');
			if (isset($_GET['sortby']))
				$sort = $_GET['sortby'];
			
			$sort =  JRequest::getVar('sortby','');	
			if (isset($sort)){
				if ($sort == '') 
					{$sort='create_datedesc';}
			}else
				{$sort='create_datedesc';}
			$jobcategory=($router_mode_sef==2)? $common_model->parseId(JRequest::getVar('cat','')):JRequest::getVar('cat','');
				
			$sortby = $this->getResumeListOrdering($sort);
			
			$result =  $model->getResumeByCategoryId($uid,$jobcategory,$sortby,$limit,$limitstart);	
			$options =  $this->get('Options');
			$sortlinks = $this->getResumeListSorting($sort);
			$sortlinks['sorton'] = $sorton;
			$sortlinks['sortorder'] = $sortorder;
			if ( $result[1] <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination($result[1], $limitstart, $limit );
			$this->assignRef('pagination', $pagination);
			$this->assignRef('resumes', $result[0]);
			$this->assignRef('searchresumeconfig', $result[2]);
			$this->assignRef('categoryname', $result[3]);
			$this->assignRef('catid', $result[4]);
			$this->assignRef('subcategories',$result[5]);
			
			$this->assignRef('sortlinks', $sortlinks);
		}elseif($layout== 'resume_bysubcategory'){ 															// resume by category
			$page_title .=  ' - ' . JText::_('JS_RESUME_BY_SUBCATEGORY');
			if (isset($_GET['sortby']))
				$sort = $_GET['sortby'];
			
			$sort =  JRequest::getVar('sortby','');	
			if (isset($sort)){
				if ($sort == '') 
					{$sort='create_datedesc';}
			}else
				{$sort='create_datedesc';}
			$jobsubcategory=($router_mode_sef==2)? $common_model->parseId(JRequest::getVar('resumesubcat','')):JRequest::getVar('resumesubcat','');
			$sortby = $this->getResumeListOrdering($sort);
			
			$result =  $model->getResumeBySubCategoryId($uid,$jobsubcategory,$sortby,$limit,$limitstart);	
			$options =  $this->get('Options');
			$sortlinks = $this->getResumeListSorting($sort);
			$sortlinks['sorton'] = $sorton;
			$sortlinks['sortorder'] = $sortorder;
			if ( $result[1] <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination($result[1], $limitstart, $limit );
			$this->assignRef('pagination', $pagination);
			if(isset($result[0])) $this->assignRef('resume', $result[0]);
			if(isset($result[2])) $this->assignRef('subcategorytitle', $result[2]);
			$this->assignRef('resumesubcategory', $jobsubcategory);
			$this->assignRef('sortlinks', $sortlinks);
		}elseif($layout== 'my_resumesearches'){												// my resume searches
			$page_title .=  ' - ' . JText::_('JS_RESUME_SAVE_SEARCHES');
			$result =  $model->getMyResumeSearchesbyUid($uid,$limit,$limitstart);	
			$this->assignRef('jobsearches', $result[0]);
			if ( $result[1] <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination($result[1], $limitstart, $limit );
			$this->assignRef('pagination', $pagination);
		}elseif($layout== 'viewresumesearch'){												// view resume seach
			$page_title .=  ' - ' . JText::_('JS_VIEW_RESUME_SEARCH');
			$id =  JRequest::getVar('rs','');	
			$search =  $model->getResumeSearchebyId($id);	
			if (isset ($search)){
			$_SESSION['resumesearch_title'] = $search->application_title;
			if($search->nationality != 0) $_SESSION['resumesearch_nationality'] = $search->nationality;
			if($search->gender != 0)$_SESSION['resumesearch_gender'] = $search->gender;
			if($search->iamavailable != 0)$_SESSION['resumesearch_iamavailable'] = $search->iamavailable;
			if($search->category != 0)$_SESSION['resumesearch_jobcategory'] = $search->category;
			if($search->jobtype != 0) $_SESSION['resumesearch_jobtype'] = $search->jobtype;
			if($search->salaryrange != 0) $_SESSION['resumesearch_jobsalaryrange'] = $search->salaryrange;
			if($search->education != 0) $_SESSION['resumesearch_heighestfinisheducation'] = $search->education;
			$_SESSION['resumesearch_experience'] = $search->experience;
			
			}
			$mainframe->redirect( JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=resume_searchresults&Itemid='.$itemid));
		}elseif($layout== 'job_details'){												// job Details
			//--//
			$jobid =  JRequest::getVar('oi');
			$details =  $model->getJobDetails($jobid);
			$this->assignRef('details', $details);
			
		}elseif($layout== 'company_info'){												// job Details
			//--//
			$companyid =  JRequest::getVar('md');
			$result =  $model->getCompanyInfoById($companyid);
			$this->assignRef('info', $result[0]);
			$this->assignRef('jobs', $result[1]);
			$this->assignRef('company', $result[2]);
		}elseif($layout== 'jobs'){												// jobs
                    //--//
                    $result =  $model->getAllForDetails($limit,$limitstart);
			$this->assignRef('jobs', $result[0]);
			$this->assignRef('goldjobs', $result[1]);
			$this->assignRef('featuredcompanies', $result[2]);
			$this->assignRef('goldcompanies', $result[3]);
			
			//$this->assignRef('limit', $limit);
			//$this->assignRef('limitstart', $limitstart);
		}elseif($layout== 'packages'){												// my resume searches
                        $page_title .=  ' - ' . JText::_('JS_PACKAGES');
			$result =  $model->getEmployerPackages($limit,$limitstart);
			$this->assignRef('packages', $result[0]);
                        if ( $result[1] <= $limitstart ) $limitstart = 0;
                        $pagination = new JPagination($result[1], $limitstart, $limit );
			$this->assignRef('pagination', $pagination);
		}elseif($layout== 'package_buynow'){
			$page_title .=  ' - ' . JText::_('JS_BUY_NOW');
			$packageid =  JRequest::getVar('gd');
			$result =$model->getEmployerPackageById($packageid,$uid);
			$paymentmethod=  $common_model->getPaymentMethodsConfig();
			$ideal_data=  $common_model->getIdealPayment();
			$this->assignRef('package', $result[0]);
			$this->assignRef('payment_multicompanies', $result[1]);
			$this->assignRef('paymentmethod', $paymentmethod);
			$this->assignRef('pb', JRequest::getVar('pb',''));
			$this->assignRef('lists', $result[2]);
			$this->assignRef('idealdata', $ideal_data);
		}elseif($layout== 'package_details'){
                        $page_title .=  ' - ' . JText::_('JS_PACKAGE_DETAILS');
			$packageid =  JRequest::getVar('gd');
			
			$result =$model->getEmployerPackageById($packageid,$uid);
			$this->assignRef('package', $result[0]);
			$this->assignRef('payment_multicompanies', $result[1]);
			$this->assignRef('lists', $result[2]);
			
		}elseif($layout== 'purchasehistory'){												// my resume searches
                        //$page_title .=  ' - ' . JText::_('JS_PACKAGES');
			$result =  $model->getEmployerPurchaseHistory($uid,$limit,$limitstart);
			$this->assignRef('packages', $result[0]);
                        if ( $result[1] <= $limitstart ) $limitstart = 0;
                        $pagination = new JPagination($result[1], $limitstart, $limit );
			$this->assignRef('pagination', $pagination);
		}elseif($layout== 'successfullogin'){
                        if(isset($_SESSION['jsjobs_option'])){ $jsoption = $_SESSION['jsjobs_option']; unset($_SESSION['jsjobs_option']);}
                        if(isset($_SESSION['jsjobs_view'])){ $jsview = $_SESSION['jsjobs_view']; unset($_SESSION['jsjobs_view']);}
                        if(isset($_SESSION['jsjobs_red_layout'])){ $jslayout = $_SESSION['jsjobs_red_layout']; unset($_SESSION['jsjobs_red_layout']);}
                        if(isset($_SESSION['jsjobs_comp_url'])){ $compurl = $_SESSION['jsjobs_comp_url']; unset($_SESSION['jsjobs_comp_url']);}
						if($jslayout == 'successfullogin') $jslayout = 'controlpanel';
                        if($jsoption == '') $jsoption =JRequest::getVar('option');
                        if($jsoption == '') $jsoption ='com_jsjobs';
                        if($jsoption == 'com_jsjobs'){
							if($compurl != ''){
								$mainframe->redirect($compurl);                            
							}elseif($jsview != ''){
								if($jslayout == 'package_buynow'){
									if(isset($_SESSION['pb'])) $mainframe->redirect('index.php?option=com_jsjobs&c=jsjobs&view='.$jsview.'&layout='.$jslayout.'&pb='.$_SESSION['pb'].'&gd='.$_SESSION['gd'].'&Itemid='.$itemid);
									else $mainframe->redirect('index.php?option=com_jsjobs&c=jsjobs&view='.$jsview.'&layout='.$jslayout.'&gd='.$_SESSION['gd'].'&Itemid='.$itemid);
									unset($_SESSION['gd']);
									unset($_SESSION['pb']);
								}elseif($jslayout == 'job_apply'){
									$mainframe->redirect('index.php?option=com_jsjobs&c=jsjobs&view='.$jsview.'&layout='.$jslayout.'&aj='.$_SESSION['aj'].'&bi='.$_SESSION['bi'].'&Itemid='.$itemid);
									unset($_SESSION['aj']);unset($_SESSION['bi']);
								}else{
									$mainframe->redirect('index.php?option=com_jsjobs&c=jsjobs&view='.$jsview.'&layout='.$jslayout.'&Itemid='.$itemid);
								}
                            }else{ //get role of this user
                                if(isset($userrole->rolefor)) {
                                    if ($userrole->rolefor == 1) { // employer
                                        $mainframe->redirect('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=controlpanel&Itemid='.$itemid);
                                    }elseif ($userrole->rolefor == 2) { // jobseeker
                                        $mainframe->redirect('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=controlpanel&Itemid='.$itemid);
                                    }

                                }
                            }
                        }
			$result =  $model->getEmployerPurchaseHistory($uid,$limit,$limitstart);
			$this->assignRef('packages', $result[0]);
                        if ( $result[1] <= $limitstart ) $limitstart = 0;
                        $pagination = new JPagination($result[1], $limitstart, $limit );
			$this->assignRef('pagination', $pagination);
                }elseif($layout== 'empmessages'){ 							// emp messages
                        $page_title .=  ' - ' . JText::_('JS_MESSAGES');
			$result =  $model->getMessagesbyJobs($uid,$limit,$limitstart);
			$this->assignRef('messages', $result[0]);
                        if ( $result[1] <= $limitstart ) $limitstart = 0;
                        $pagination = new JPagination($result[1], $limitstart, $limit );
			$this->assignRef('pagination', $pagination);
                }elseif($layout== 'job_messages'){ 							// job messages
						$jobid=($router_mode_sef==2)? $common_model->parseId(JRequest::getVar('bd')):JRequest::getVar('bd');
                        $page_title .=  ' - ' . JText::_('JS_MESSAGES');
			$result =  $model->getMessagesbyJob($uid,$jobid,$limit,$limitstart);
			$this->assignRef('messages', $result[0]);
                        if ( $result[1] <= $limitstart ) $limitstart = 0;
                        $pagination = new JPagination($result[1], $limitstart, $limit );
			$this->assignRef('pagination', $pagination);
                }elseif($layout== 'send_message'){ 							// messages
                
                        $page_title .=  ' - ' . JText::_('JS_MESSAGES');
                        $jobid =  JRequest::getVar('bd');
						$resumeid=($router_mode_sef==2)? $common_model->parseId(JRequest::getVar('rd')):JRequest::getVar('rd');
			$result =  $model->getMessagesbyJobResume($uid,$jobid,$resumeid,$limit,$limitstart);
                        $pagination = new JPagination($result[1], $limitstart, $limit );
			$this->assignRef('pagination', $pagination);
			$this->assignRef('messages', $result[0]);
			$this->assignRef('totalresults', $result[1]);
			$this->assignRef('canadd',$result[2]);
			if(isset($result[3])) $this->assignRef('summary',$result[3]);
			$this->assignRef('bd', $jobid);
			$this->assignRef('rd', $resumeid);
			$this->assignRef('vm', JRequest::getVar('vm'));
		}elseif($layout== 'myfolders'){			// my folders
                        $page_title .=  ' - ' . JText::_('JS_MY_FOLDERS');
			$result =  $model->getMyFolders($uid, $limit,$limitstart);
                        if ( $result[1] <= $limitstart ) $limitstart = 0;
                        $pagination = new JPagination($result[1], $limitstart, $limit );
			$this->assignRef('pagination', $pagination);
			$this->assignRef('folders', $result[0]);
		}elseif($layout== 'viewfolder'){			// folders view
			$page_title .=  ' - ' . JText::_('JS_MY_FOLDERS');
			if($router_mode_sef==2){
				$fid =$common_model->parseId(JRequest::getVar('fd',''));
				
			}else{
				$fid = JRequest::getVar('fd','');
				
			}
			$result =  $model->getFolderDetail($uid,$fid);
			$this->assignRef('folder', $result);
		}elseif($layout== 'folder_resumes'){      // folder_resumes
			if (isset($_GET['sortby']))
				$sort = $_GET['sortby'];

			$sort =  JRequest::getVar('sortby','');
			if (isset($sort)){
				if ($sort == '')
					{$sort='apply_datedesc';}
			}else
				{$sort='apply_datedesc';}
				
			$sortby = $this->getEmpListOrdering($sort);
			if($router_mode_sef==2){
				$folderid = $common_model->parseId(JRequest::getVar('fd',''));
			}else{
				$folderid =  JRequest::getVar('fd','');
			}

			$result =  $model->getFolderResumebyFolderId($uid,$folderid,$sortby,$limit,$limitstart);
                        $this->assignRef('resume', $result[0] );
                        if ( $result[1] <= $limitstart ) $limitstart = 0;
                        $pagination = new JPagination($result[1], $limitstart, $limit );
			$this->assignRef('pagination', $pagination);
			$this->assignRef('fd', $folderid);
		}elseif($layout== 'resumebycategory'){      // Resume By category
			$result =  $model->getResumeByCategory($uid);
			$this->assignRef('categories',$result);
		}elseif($layout == 'norecordfound'){
				$Itemid = JRequest::getVar('Itemid');
				$this->assignRef('itemid', $Itemid);
		}
		
                $document->setTitle( $page_title);
                //if ($mainframe->getCfg('MetaAuthor') == '1') $mainframe->addMetaTag('author', 'JS Jobs 1.6');
		$this->assignRef('userrole', $userrole);
		$this->assignRef('config', $config);
		$this->assignRef('socailsharing', $socialconfig);		
		
		$this->assignRef('theme', $theme);
		$this->assignRef('option', $option);
		$this->assignRef('params', $params);
		$this->assignRef('viewtype', $viewtype);
		$this->assignRef('employerlinks', $employerlinks);
		$this->assignRef('jobseekerlinks', $jobseekerlinks);
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
		$sortlinks['salaryto'] = $this->getSortArg("salaryto",$sort);
		$sortlinks['salaryrange'] = $this->getSortArg("salaryrange",$sort);
		$sortlinks['country'] = $this->getSortArg("country",$sort);
		$sortlinks['created'] = $this->getSortArg("created",$sort);
		$sortlinks['apply_date'] = $this->getSortArg("apply_date",$sort);
		
		return $sortlinks;
	}

	function getResumeListSorting( $sort ) {
		$sortlinks['application_title'] = $this->getSortArg("application_title",$sort);
		$sortlinks['jobtype'] = $this->getSortArg("jobtype",$sort);
		$sortlinks['salaryrange'] = $this->getSortArg("salaryrange",$sort);
		$sortlinks['created'] = $this->getSortArg("created",$sort);
		
		return $sortlinks;
	}

	function getEmpListSorting( $sort ) {
		$sortlinks['name'] = $this->getSortArg("name",$sort);
		$sortlinks['category'] = $this->getSortArg("category",$sort);
		$sortlinks['jobtype'] = $this->getSortArg("jobtype",$sort);
		$sortlinks['jobsalaryrange'] = $this->getSortArg("jobsalaryrange",$sort);
		$sortlinks['apply_date'] = $this->getSortArg("apply_date",$sort);
		$sortlinks['email'] = $this->getSortArg("email",$sort);
		$sortlinks['gender'] = $this->getSortArg("gender",$sort);
		$sortlinks['age'] = $this->getSortArg("age",$sort);
		$sortlinks['total_experience'] = $this->getSortArg("total_experience",$sort);
		
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
			case "salarytodesc": $ordering = "salaryto DESC";  $sorton = "salaryrange"; $sortorder="DESC"; break;
			case "salarytoasc": $ordering = "salaryto ASC";  $sorton = "salaryrange"; $sortorder="ASC"; break;
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
			case "genderdesc": $ordering = "app.gender DESC";  $sorton = "gender"; $sortorder="DESC"; break;
			case "genderasc": $ordering = "app.gender ASC";  $sorton = "gender"; $sortorder="ASC"; break;
			case "jobtypedesc": $ordering = "app.jobtype DESC";  $sorton = "jobtype"; $sortorder="DESC"; break;
			case "jobtypeasc": $ordering = "app.jobtype ASC";  $sorton = "jobtype"; $sortorder="ASC"; break;
			case "jobsalaryrangedesc": $ordering = "salary.rangestart DESC";  $sorton = "jobsalaryrange"; $sortorder="DESC"; break;
			case "jobsalaryrangeasc": $ordering = "salary.rangestart ASC";  $sorton = "jobsalaryrange"; $sortorder="ASC"; break;
			case "apply_datedesc": $ordering = "apply.apply_date DESC";  $sorton = "apply_date"; $sortorder="DESC"; break;
			case "apply_dateasc": $ordering = "apply.apply_date ASC";  $sorton = "apply_date"; $sortorder="ASC"; break;
			case "emaildesc": $ordering = "app.email_address DESC";  $sorton = "email"; $sortorder="DESC"; break;
			case "emailasc": $ordering = "app.email_address ASC";  $sorton = "email"; $sortorder="ASC"; break;
			case "total_experiencedesc": $ordering = "app.total_experience DESC";  $sorton = "total_experience"; $sortorder="DESC"; break;
			case "total_experienceasc": $ordering = "app.total_experience ASC";  $sorton = "total_experience"; $sortorder="ASC"; break;
			case "agedesc": $ordering = "job.ageto DESC";  $sorton = "age"; $sortorder="DESC"; break;
			case "ageasc": $ordering = "job.agefrom ASC";  $sorton = "age"; $sortorder="ASC"; break;
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
