<?php
/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 + Created by:	Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	views/applications/view.html.php
 ^ 
 * Description: HTML view of all applications 
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class JSJobsViewApplications extends JViewLegacy
{
	/*private $_syn_client_with_server="";
	function synchronizeClientWithServer($server_syncdata){
		JRequest::setVar( 'view', 'applications' );
		JRequest::setVar('layout','jobshare');
		$this->_syn_client_with_server=$server_syncdata;
		$this->display();
	}*/
	
	function display($tpl = null)
	{
		$model		= &$this->getModel();
		global $mainframe, $option,$_client_auth_key;
		
		$option = 'com_jsjobs';
		$session = &JFactory::getSession();
		
		$mainframe = &JFactory::getApplication();
				
        $version = new JVersion;
        $joomla = $version->getShortVersion();
        $jversion = substr($joomla,0,3);
		
		if($_client_auth_key=="") {
			$auth_key=$model->getClientAuthenticationKey();
			$_client_auth_key=$auth_key;
		}

		
	    $user	=& JFactory::getUser();
		$uid=$user->id;
		// get configurations
		$config = Array();
		$results =  $model->getConfig();	
		if ($results){ //not empty
			foreach ($results as $result){
				$config[$result->configname] = $result->configvalue;
			}
		}

		$layoutName = JRequest :: getVar('layout', '');
		if ($layoutName == ''){
				$layoutName = $_SESSION['cur_layout'];
                }
		$_SESSION['cur_layout']=$layoutName;
		if(($layoutName == 'controlpanel') || ($layoutName == 'featuredcompaniesqueue')  || ($layoutName == 'goldcompaniesqueue') ||  ($layoutName == 'featuredjobsqueue')  ||  ($layoutName == 'goldjobsqueue')
		 	||  ($layoutName == 'goldresumesqueue') ||  ($layoutName == 'featuredresumesqueue') || ($layoutName == 'companiesqueue') || ($layoutName == 'departmentqueue') || ($layoutName == 'jobqueue')
			|| ($layoutName == 'appqueue') || ($layoutName == 'updates')  || ($layoutName == 'fieldsordering')  || ($layoutName == 'loadaddressdata')
			|| ($layoutName == 'appliedresumes')|| ($layoutName == 'resumesearch') || ($layoutName == 'addtofeaturedjobs')|| ($layoutName == 'addtogoldresumes') || ($layoutName == 'addtofeaturedresumes') || ($layoutName == 'addtofeaturedcompanies')|| ($layoutName == 'jsjobsstats')|| ($layoutName == 'addtogoldcompanies')|| ($layoutName == 'addtogoldjobs')|| ($layoutName == 'jobsearch') || ($layoutName=='employerpaymenthistory')  || 
			($layoutName=='jobseekerpaymenthistory')|| ($layoutName == 'payment_report') || ($layoutName == 'info') )
			$layoutName =$layoutName; //do nothing
		elseif(( $layoutName == 'jobappliedresume')|| ($layoutName == 'view_company')|| ($layoutName == 'view_job') ||($layoutName == 'company_departments') ||  ($layoutName == 'job_searchresult') || 
			  ($layoutName == 'resume_searchresults')||($layoutName=='jobseekerpaymentdetails') || ($layoutName == 'userstate_companies') || ($layoutName == 'userstate_jobs') || ($layoutName == 'userstate_resumes') || ($layoutName == 'employerpaymentdetails')
			  || ( $layoutName == 'userstats') ||($layoutName == 'package_paymentreport') ){ // only cancel
		}elseif(( $layoutName == 'conf')  || ( $layoutName == 'configurations')  ||($layoutName == 'emailtemplate')){
		}elseif( $layoutName == 'users')  {
		}else{
		}
		JToolBarHelper :: spacer(10);

		jimport('joomla.html.pagination');
		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart	= $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );
		if (isset($_SESSION['js_cur_page'])) $cur_page = $_SESSION['js_cur_page']; else $cur_page = null;

		if($layoutName == 'controlpanel'){								//control panel
			JToolBarHelper :: title('JS Jobs');
			$model = $this->getModel('jsjobs', 'JSJobsModel');
			$ck = $model->getCheckCronKey();
			if($ck == false){
				$model->genearateCronKey();
			}
			$ck = $model->getCronKey(md5(date('Y-m-d')));
			$this->assignRef('ck',$ck);
			$today_stats=$model->getTodayStats();
			$topjobs=$model->getTopJobs();
			$this->assignRef('today_stats',$today_stats);
			$this->assignRef('topjobs',$topjobs);
		}elseif($layoutName == 'jobshare'){								//resume search
			JToolBarHelper :: title(JText::_('JS_JOB_SHARING_SERVICE'));
			
			$synchronizedata=$session->get('synchronizedatamessage');
			$session->clear('synchronizedatamessage');
			$empty='empty';
			$this->assignRef('result',$empty);
			if($synchronizedata!=""){
				$this->assignRef('result',$synchronizedata);
			}
			
		}elseif($layoutName == 'jobsharelog'){								//resume search
			JToolBarHelper :: title(JText::_('JS_JOB_SHARE_LOG'));
			$searchuid = $mainframe->getUserStateFromRequest( $option.'searchuid', 'searchuid',	'',	'string' );
			$searchusername = $mainframe->getUserStateFromRequest( $option.'searchusername', 'searchusername',	'',	'string' );
			$searchrefnumber = $mainframe->getUserStateFromRequest( $option.'searchrefnumber', 'searchrefnumber',	'',	'string' );
			$searchstartdate = $mainframe->getUserStateFromRequest( $option.'searchstartdate', 'searchstartdate',	'',	'string' );
			$searchenddate = $mainframe->getUserStateFromRequest( $option.'searchenddate', 'searchenddate',	'',	'string' );

			$result =  $model->getAllSharingServiceLog($searchuid,$searchusername,$searchrefnumber,$searchstartdate,$searchenddate,$limitstart, $limit);	
			$this->assignRef('servicelog', $result[0]);
			$this->assignRef('lists', $result[2]);
			$total = $result[1];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			
		}elseif($layoutName == 'resumesearch'){								//resume search
			JToolBarHelper :: title(JText::_('JS_RESUME_SEARCH'));
			$result =  $model->getResumeSearchOptions();	
			$this->assignRef('searchoptions', $result[0]);
			$this->assignRef('searchresumeconfig', $result[1]);
		}elseif($layoutName=='resume_searchresults'){
			JToolBarHelper:: title(JText::_('JS_RESUME_SEARCHRESULTS'));
			JToolBarHelper :: cancel();
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

				}
			}
				$jobstatus='';
				$title = $_SESSION['resumesearch_title'];
				$name = $_SESSION['resumesearch_name'];
				$nationality = $_SESSION['resumesearch_nationality'];
				$gender = $_SESSION['resumesearch_gender'];
				$iamavailable = '';//$_SESSION['resumesearch_iamavailable'];
				$jobcategory = $_SESSION['resumesearch_jobcategory'];
				$jobtype = $_SESSION['resumesearch_jobtype'];
				$jobsalaryrange = $_SESSION['resumesearch_jobsalaryrange'];
				$education = $_SESSION['resumesearch_heighestfinisheducation'];
				$experience = $_SESSION['resumesearch_experience'];
				$currency = $_SESSION['resumesearch_currency'];
				$zipcode = $_SESSION['resumesearch_zipcode'];
			$result =  $model->getResumeSearch($uid,$title,$name,$nationality,$gender,$iamavailable,$jobcategory,$jobtype,$jobstatus,$jobsalaryrange,$education
			, $experience,$limit,$limitstart,$currency,$zipcode);	
			$items = $result[0];
			$total = $result[1];
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('searchresumeconfig', $result[2]);
		
		}elseif($layoutName == 'jobsearch'){								//job search
			JToolBarHelper :: title(JText::_('JS_JOB_SEARCH'));
			$result =  $model->getSearchOptions();	
			$this->assignRef('searchoptions', $result[0]);
			$this->assignRef('searchjobconfig', $result[1]);
		}elseif($layoutName == 'job_searchresult'){								//job asearch results
			JToolBarHelper :: title(JText::_('JS_JOB_SEARCHREULTS'));
			JToolBarHelper :: cancel();
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
					if(isset($_POST['shift']))$_SESSION['jobsearch_shift'] = $_POST['shift'];else $_SESSION['jobsearch_shift'] ="";
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

					if(isset($_POST['zipcode']))$_SESSION['jobsearch_zipcode'] = $_POST['zipcode'];
					$_SESSION['jobsearch_currency'] = $_POST['currency'];                                        
				}
			}	
			$title = $_SESSION['jobsearch_title'];
			$jobcategory = $_SESSION['jobsearch_jobcategory'];
			$jobsubcategory = $_SESSION['jobsearch_jobsubcategory'];
			$jobtype = $_SESSION['jobsearch_jobtype'];
			$jobstatus = $_SESSION['jobsearch_jobstatus'];
			$salaryrangefrom = $_SESSION['jobsearch_salaryrangefrom'];
			$salaryrangeto = $_SESSION['jobsearch_salaryrangeto'];
			$salaryrangetype = $_SESSION['jobsearch_salaryrangetype'];
			$shift = $_SESSION['jobsearch_shift'];
			$durration = $_SESSION['jobsearch_durration'];
			$startpublishing = $_SESSION['jobsearch_startpublishing'];
			$stoppublishing = $_SESSION['jobsearch_stoppublishing'];
			$company = $_SESSION['jobsearch_company'];

			$city = $_SESSION['jobsearch_city'];
			if(isset($_SESSION['jobsearch_zipcode'])) $zipcode = $_SESSION['jobsearch_zipcode']; else $zipcode = '';
			$currency = $_SESSION['jobsearch_currency'];
			$longitude = $_SESSION['jobsearch_longitude'];
			$latitude = $_SESSION['jobsearch_latitude'];
			$radius = $_SESSION['jobsearch_radius'];
			$radius_length_type = $_SESSION['jobsearch_radius_length_type'];
			$keywords = $_SESSION['jobsearch_keywords'];
			$result =  $model->getJobSearch($title,$jobcategory,$jobsubcategory,$jobtype,$jobstatus,$salaryrangefrom,$salaryrangeto,$salaryrangetype
			,$shift,  $durration, $startpublishing, $stoppublishing	
			,$company,$city,$zipcode,$currency,$longitude,$latitude,$radius,$radius_length_type,$keywords,$limit,$limitstart);	
			$items= $result[0];
			$total = $result[1];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('listjobconfig', $result[2]);
		}elseif($layoutName == 'view_job'){								//view job
			JToolBarHelper :: title(JText::_('JS_JOB_DETAILS'));
			JToolBarHelper :: cancel();
			$jobid = $_GET['oi'];
			$result =  $model->getJobbyIdForView($jobid);	
			$this->assignRef('job', $result[0]);
			$this->assignRef('userfields', $result[2]);
			$this->assignRef('fieldsordering', $result[3]);
		}elseif($layoutName == 'view_company'){								//job search
			JToolBarHelper :: title(JText::_('JS_COMPANY_DETAILS'));
			JToolBarHelper :: cancel();
			$companyid = $_GET['md'];
			$result =  $model->getCompanybyIdForView($companyid);	
			$this->assignRef('company', $result[0]);
			$this->assignRef('fieldsordering', $result[3]);
		}elseif($layoutName == 'categories'){								//categories
			JToolBarHelper :: title(JText::_('JS_CATEGORIES'));
            		JToolBarHelper :: addNew();
			JToolBarHelper :: editList();
			JToolBarHelper :: deleteList();
			JToolBarHelper :: cancel();
			JToolBarHelper :: deleteList(JText::_('JS_ARE_YOU_SURE'),'deletecategoryandsubcategory','Delete Cat & Sub-Cat');
			$sortby =  JRequest::getVar('sortby','asc');
			$changesort =  JRequest::getVar('changesort','0');
			$form = 'com_jsjobs.countries.list.';
			$searchname = $mainframe->getUserStateFromRequest( $form.'searchname', 'searchname','', 'string' );
			if($changesort == 1){
				$sortby = $this->getSortArg($sortby);
			}
			$result =  $model->getAllCategories($searchname,$sortby,$limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists',$lists);
			$this->assignRef('sort',$sortby);
		}elseif($layoutName == 'subcategories'){								//sub categories
                        $categoryid = JRequest :: getVar('cd', '');
                        $session = JFactory::getSession();
                        $session->set('sub_categoryid', $categoryid);
			$result =  $model->getSubCategories($categoryid,$limitstart, $limit);
                        JToolBarHelper :: title(JText::_('SUB_CATEGORIES').' ['.$result[2]->cat_title.']');
                        JToolBarHelper :: addNew('editsubcategories');
			JToolBarHelper :: editList('editsubcategories');
			JToolBarHelper :: deleteList(JText::_('JS_ARE_YOU_SURE'),'removesubcategory');
			$items = $result[0];
			$total = $result[1];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'jobtypes'){								//job types
			JToolBarHelper :: title(JText::_('JS_JOB_TYPES'));
			JToolBarHelper :: addNew('editjobtype');
			JToolBarHelper :: editList('editjobtype');
			JToolBarHelper :: deleteList();
			$result =  $model->getAllJobTypes($limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'ages'){								//job types
			JToolBarHelper :: title(JText::_('JS_JOB_AGES'));
			JToolBarHelper :: addNew('editjobage');
			JToolBarHelper :: editList('editjobage');
			JToolBarHelper :: deleteList();
			$result =  $model->getAllAges($limitstart, $limit);
			$items = $result[0];
			$total = $result[1];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'careerlevels'){								//job types
			JToolBarHelper :: title(JText::_('JS_JOB_CAREER_LEVELS'));
			JToolBarHelper :: addNew('editcareerlevels');
			JToolBarHelper :: editList('editcareerlevels');
			JToolBarHelper :: deleteList();
			$result =  $model->getAllCareerLevels($limitstart, $limit);
			$items = $result[0];
			$total = $result[1];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'experience'){								//job types
			JToolBarHelper :: title(JText::_('JS_JOB_EXPERIENCE'));
			JToolBarHelper :: addNew('editjobexperience');
			JToolBarHelper :: editList('editjobexperience');
			JToolBarHelper :: deleteList();
			$result =  $model->getAllExperience($limitstart, $limit);
			$items = $result[0];
			$total = $result[1];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'salaryrangetype'){								//job types
			JToolBarHelper :: title(JText::_('JS_SALARY_RANGE_TYPES'));
			JToolBarHelper :: addNew('editjobsalaryrangrtype');
			JToolBarHelper :: editList('editjobsalaryrangrtype');
			JToolBarHelper :: deleteList();
			$result =  $model->getAllSalaryRangeType($limitstart, $limit);
			$items = $result[0];
			$total = $result[1];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
	}elseif($layoutName== 'message_history'){
			JToolBarHelper :: title(JText::_('JS_MESSAGES_HISTORY'));
			$jobid =  JRequest::getVar('bd');
			$resumeid =  JRequest::getVar('rd');
			$result =  $model->getMessagesbyJobResume($uid,$jobid,$resumeid,$limit,$limitstart);
			$this->assignRef('messages', $result[0]);
			$this->assignRef('totalresults', $result[1]);
			$this->assignRef('summary',$result[3]);
			if ( $result[1] <= $limitstart ) $limitstart = 0;
			$this->assignRef('limit', $limit);
			$this->assignRef('limitstart', $limitstart);
			$this->assignRef('bd', $jobid);
			$this->assignRef('resumeid', $resumeid);
			$pagination = new JPagination( $result[1], $limitstart, $limit );
        		JToolBarHelper :: cancel('cancelmessagehistory');
		}elseif($layoutName == 'jobstatus'){								//job status
			JToolBarHelper :: title(JText::_('JS_JOB_STATUS'));
			JToolBarHelper :: addNew('edijobstatus');
			JToolBarHelper :: editList('edijobstatus');
			JToolBarHelper :: deleteList();
			$result =  $model->getAllJobStatus($limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'shifts'){								//shifts
			JToolBarHelper :: title(JText::_('JS_SHIFTS'));
			JToolBarHelper :: addNew('edijobshift');
			JToolBarHelper :: editList('edijobshift');
			JToolBarHelper :: deleteList();
			$result =  $model->getAllShifts($limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'highesteducations'){								//highest educations
			JToolBarHelper :: title(JText::_('JS_HIGHEST_EDUCATIONS'));
			JToolBarHelper :: addNew('editjobhighesteducation');
			JToolBarHelper :: editList('editjobhighesteducation');
			JToolBarHelper :: deleteList();
			$result =  $model->getAllHighestEducations($limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'companies'){				//companies
			JToolBarHelper :: title(JText::_('JS_COMPANIES'));
			JToolBarHelper :: addNew();
			JToolBarHelper :: editList();
			JToolBarHelper :: deleteList();
			JToolBarHelper :: cancel();
			if ($cur_page != 'companies'){	$limitstart = 0;	$_SESSION['js_cur_page'] = 'companies';	$mainframe->setUserState( $option.'.limitstart', $limitstart );	}
			$searchcompany = $mainframe->getUserStateFromRequest( $option.'searchcompany', 'searchcompany',	'',	'string' );
			$searchjobcategory = $mainframe->getUserStateFromRequest( $option.'searchjobcategory', 'searchjobcategory',	'',	'string' );
			$searchcountry = $mainframe->getUserStateFromRequest( $option.'searchcountry', 'searchcountry',	'',	'string' );

			$result =  $model->getAllCompanies($searchcompany, $searchjobcategory, $searchcountry, $limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists', $lists);
		}elseif($layoutName == 'companiesqueue'){				//companies queue
			JToolBarHelper :: title(JText::_('JS_COMPANIES_QUEUE'));
			$searchcompany = $mainframe->getUserStateFromRequest( $option.'searchcompany', 'searchcompany',	'',	'string' );
			$searchjobcategory = $mainframe->getUserStateFromRequest( $option.'searchjobcategory', 'searchjobcategory',	'',	'string' );
			$searchcountry = $mainframe->getUserStateFromRequest( $option.'searchcountry', 'searchcountry',	'',	'string' );

			$result =  $model->getAllUnapprovedCompanies($searchcompany, $searchjobcategory, $searchcountry, $limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists', $lists);
		}elseif($layoutName == 'folders'){				//folders
			JToolBarHelper :: title(JText::_('JS_FOLDERS'));
                        JToolBarHelper :: addNew();
			JToolBarHelper :: editList();
			JToolBarHelper :: deleteList();
        		JToolBarHelper :: cancel();
			if ($cur_page != 'folders'){	$limitstart = 0;	$_SESSION['js_cur_page'] = 'folders';	$mainframe->setUserState( $option.'.limitstart', $limitstart );	}
			$result =  $model->getAllFolders($uid,$limitstart, $limit);
			$items = $result[0];
			$total = $result[1];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'foldersqueue'){				//folders queue
			JToolBarHelper :: title(JText::_('JS_FOLDERS_QUEUE'));

			$result =  $model->getAllUnapprovedFolders( $limitstart, $limit);
			$items = $result[0];
			$total = $result[1];
			$this->assignRef('items', $items);
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'jobs'){								//jobs
			JToolBarHelper :: title(JText::_('JS_JOBS'));
			JToolBarHelper :: addNew();
			JToolBarHelper :: editList();
			JToolBarHelper :: deleteList();
			JToolBarHelper :: cancel();
			$form = 'com_jsjobs.jobs.list.';
			$searchtitle = $mainframe->getUserStateFromRequest( $form.'searchtitle', 'searchtitle',	'',	'string' );
			$searchcompany = $mainframe->getUserStateFromRequest( $form.'searchcompany', 'searchcompany',	'',	'string' );
			$searchjobcategory = $mainframe->getUserStateFromRequest( $form.'searchjobcategory', 'searchjobcategory',	'',	'string' );
			$searchjobtype = $mainframe->getUserStateFromRequest( $form.'searchjobtype', 'searchjobtype',	'',	'string' );
			$searchjobstatus = $mainframe->getUserStateFromRequest( $form.'searchjobstatus', 'searchjobstatus',	'',	'string' );
			$result =  $model->getAllJobs($searchtitle, $searchcompany, $searchjobcategory, $searchjobtype, $limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists', $lists);
		}elseif($layoutName == 'jobalert'){				//jobalert
			JToolBarHelper :: title(JText::_('JS_JOB_ALERT'));
			$form = 'com_jsjobs.jobalert.list.';
			$searchname = $mainframe->getUserStateFromRequest( $form.'searchname', 'searchname',	'',	'string' );

			$result =  $model->getAllJobAlerts($searchname,$limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists', $lists);
		}elseif($layoutName == 'jobqueue'){										// job queue
			JToolBarHelper :: title(JText::_('JS_JOBS_APPROVAL_QUEUE'));
			$form = 'com_jsjobs.jobqueue.list.';
			$searchtitle = $mainframe->getUserStateFromRequest( $form.'searchtitle', 'searchtitle',	'',	'string' );
			$searchcompany = $mainframe->getUserStateFromRequest( $form.'searchcompany', 'searchcompany',	'',	'string' );
			$searchjobcategory = $mainframe->getUserStateFromRequest( $form.'searchjobcategory', 'searchjobcategory',	'',	'string' );
			$searchjobtype = $mainframe->getUserStateFromRequest( $form.'searchjobtype', 'searchjobtype',	'',	'string' );
			$searchjobstatus = $mainframe->getUserStateFromRequest( $form.'searchjobstatus', 'searchjobstatus',	'',	'string' );
			$result =  $model->getAllUnapprovedJobs($searchtitle, $searchcompany, $searchjobcategory, $searchjobtype, $searchjobstatus, $limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists', $lists);
		}elseif($layoutName == 'appliedresumes'){								//applied resumes
			JToolBarHelper :: title(JText::_('JS_APPLIED_RESUME'));
			$form = 'com_jsjobs.appliedresumes.list.';
			$searchtitle = $mainframe->getUserStateFromRequest( $form.'searchtitle', 'searchtitle',	'',	'string' );
			$searchcompany = $mainframe->getUserStateFromRequest( $form.'searchcompany', 'searchcompany',	'',	'string' );
			$searchjobcategory = $mainframe->getUserStateFromRequest( $form.'searchjobcategory', 'searchjobcategory',	'',	'string' );
			$searchjobtype = $mainframe->getUserStateFromRequest( $form.'searchjobtype', 'searchjobtype',	'',	'string' );
			$searchjobstatus = $mainframe->getUserStateFromRequest( $form.'searchjobstatus', 'searchjobstatus',	'',	'string' );
			$result =  $model->getAppliedResume($searchtitle, $searchcompany, $searchjobcategory, $searchjobtype, $searchjobstatus, $limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists', $lists);
		}elseif($layoutName == 'jobappliedresume'){								//job applied resume
			JToolBarHelper :: title(JText::_('JS_APPLIED_RESUME'));
			JToolBarHelper :: cancel();
			//$jobid = $_GET['oi'];
			$jobid = JRequest::getVar( 'oi');
			$tab_action =  JRequest::getVar('ta','');
			$session = JFactory::getSession();
			$needle_array =$session->get('jsjobappliedresumefilter');			
			if(empty($tab_action)) $tab_action=1;	
			$needle_values=($needle_array ? $needle_array:"");
			
			$form = 'com_jsjobs.jobappliedresume.list.';
			$result =  $model->getJobAppliedResume($needle_array,$tab_action,$jobid,$limitstart, $limit);	
			$result1 =  $model->getJobAppliedResumeSearchOption();	
			$items = $result[0];
			$total = $result[1];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('oi', $jobid);
			$this->assignRef('tabaction', $tab_action);
			$this->assignRef('searchoptions', $result1[0]); // for advance search tab 
			$session->clear('jsjobappliedresumefilter');
		}elseif($layoutName == 'folder_resumes'){								//job applied resume
			JToolBarHelper :: title(JText::_('JS_FOLDER_RESUME'));
			//$jobid = $_GET['oi'];
			$folderid = JRequest::getVar( 'fd');
			$form = 'com_jsjobs.jobappliedresume.list.';
			$searchname = $mainframe->getUserStateFromRequest( $form.'searchname', 'searchname',	'',	'string' );
			$searchjobtype = $mainframe->getUserStateFromRequest( $form.'searchjobtype', 'searchjobtype',	'',	'string' );
			$result =  $model->getFolderResume($folderid, $searchname, $searchjobtype, $limitstart, $limit);
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists', $lists);
			$this->assignRef('fd', $folderid);
			JToolBarHelper :: cancel();
		}elseif($layoutName == 'shortlistcandidates'){								//short list candidates
			JToolBarHelper :: title(JText::_('JS_SHORT_LIST_CANDIDATES'));
			$jobid = JRequest::getVar( 'oi');
			$form = 'com_jsjobs.jobappliedresume.list.';
			$searchname = $mainframe->getUserStateFromRequest( $form.'searchname', 'searchname',	'',	'string' );
			$searchjobtype = $mainframe->getUserStateFromRequest( $form.'searchjobtype', 'searchjobtype',	'',	'string' );
			$result =  $model->getJobAppliedResume($jobid, $searchname, $searchjobtype, $limitstart, $limit);
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists', $lists);
			$this->assignRef('oi', $jobid);
		}elseif($layoutName == 'empapps'){								//employment applications
			JToolBarHelper :: title(JText::_('JS_RESUME'));
			JToolBarHelper :: editList();
			JToolBarHelper :: deleteList();
        		JToolBarHelper :: cancel();
			$form = 'com_jsjobs.empapps.list.';
			$searchtitle = $mainframe->getUserStateFromRequest( $form.'searchtitle', 'searchtitle',	'',	'string' );
			$searchname = $mainframe->getUserStateFromRequest( $form.'searchname', 'searchname',	'',	'string' );
			$searchjobcategory = $mainframe->getUserStateFromRequest( $form.'searchjobcategory', 'searchjobcategory',	'',	'string' );
			$searchjobtype = $mainframe->getUserStateFromRequest( $form.'searchjobtype', 'searchjobtype',	'',	'string' );
			$searchjobsalaryrange = $mainframe->getUserStateFromRequest( $form.'searchjobsalaryrange', 'searchjobsalaryrange',	'',	'string' );
			$result =  $model->getAllEmpApps($searchtitle, $searchname, $searchjobcategory, $searchjobtype, $searchjobsalaryrange, $limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists', $lists);
		}elseif($layoutName == 'appqueue'){		//app queue
			JToolBarHelper :: title(JText::_('JS_RESUME_APPROVAL_QUEUE'));
			$form = 'com_jsjobs.appqueue.list.';
			$searchtitle = $mainframe->getUserStateFromRequest( $form.'searchtitle', 'searchtitle',	'',	'string' );
			$searchname = $mainframe->getUserStateFromRequest( $form.'searchname', 'searchname',	'',	'string' );
			$searchjobcategory = $mainframe->getUserStateFromRequest( $form.'searchjobcategory', 'searchjobcategory',	'',	'string' );
			$searchjobtype = $mainframe->getUserStateFromRequest( $form.'searchjobtype', 'searchjobtype',	'',	'string' );
			$searchjobsalaryrange = $mainframe->getUserStateFromRequest( $form.'searchjobsalaryrange', 'searchjobsalaryrange',	'',	'string' );
			$result =  $model->getAllUnapprovedEmpApps($searchtitle, $searchname, $searchjobcategory, $searchjobtype, $searchjobsalaryrange, $limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists', $lists);
		}elseif($layoutName == 'salaryrange'){									// salary range
			JToolBarHelper :: title(JText::_('JS_SALARY_RANGE'));
                        JToolBarHelper :: addNew('editjobsalaryrange');
			JToolBarHelper :: editList('editjobsalaryrange');
			JToolBarHelper :: deleteList();
			$result =  $model->getAllSalaryRange($limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'updates'){										// roles
			JToolBarHelper :: title(JText::_('JS_JOB_UPDATE'));
			$configur =  $model->getConfigur();	
			$this->assignRef('configur', $configur);
			$config_count =  $model->getConfigCount();	
			$this->assignRef('config_count', $config_count);
		}elseif($layoutName == 'roles'){										// roles
			JToolBarHelper :: title(JText::_('JS_ROLES'));
                        JToolBarHelper :: addNew();
			JToolBarHelper :: editList();
			JToolBarHelper :: deleteList();
        		JToolBarHelper :: cancel();
			$result =  $model->getAllRoles($limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'users'){										// users
			JToolBarHelper :: title(JText::_('USERS'));
			JToolBarHelper :: editList();
			$form = 'com_jsjobs.users.list.';
			$searchname	= $mainframe->getUserStateFromRequest( $form.'searchname', 'searchname','', 'string' );
			$searchusername	= $mainframe->getUserStateFromRequest( $form.'searchusername', 'searchusername','', 'string' );
			$searchcompany	= $mainframe->getUserStateFromRequest( $form.'searchcompany', 'searchcompany','', 'string' );
			$searchresume	= $mainframe->getUserStateFromRequest( $form.'searchresume', 'searchresume','', 'string' );
			$searchrole	= $mainframe->getUserStateFromRequest( $form.'searchrole', 'searchrole','', 'string' );
			$result =  $model->getAllUsers($searchname,$searchusername,$searchcompany,$searchresume,$searchrole, $limitstart, $limit);
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists', $lists);
		}elseif($layoutName == 'userstats'){										// users
			JToolBarHelper :: title(JText::_('JS_USER_STATS'));
			JToolBarHelper :: cancel();
			$form = 'com_jsjobs.users.list.';
			$searchname	= $mainframe->getUserStateFromRequest( $form.'searchname', 'searchname','', 'string' );
			$searchusername	= $mainframe->getUserStateFromRequest( $form.'searchusername', 'searchusername','', 'string' );
			$result =  $model->getUserStats($searchname,$searchusername, $limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists', $lists);
		}elseif($layoutName == 'userstate_companies'){										// users
			JToolBarHelper :: title(JText::_('JS_USER_STATS_COMPANIES'));
			JToolBarHelper :: cancel();
			$companyuid=JRequest::getVar('md');
			$result =  $model->getUserStatsCompanies($companyuid,$limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$this->assignRef('companyuid', $companyuid);
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'userstate_jobs'){										// users
			JToolBarHelper :: title(JText::_('JS_USER_STATS_JOBS'));
			JToolBarHelper :: cancel();
			$jobuid=JRequest::getVar('bd');
			$result =  $model->getUserStatsJobs($jobuid,$limitstart, $limit);
			$items = $result[0];
			$total = $result[1];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('jobuid', $jobuid);
		}elseif($layoutName == 'userstate_resumes'){										// users
			JToolBarHelper :: title(JText::_('JS_USER_STATS_RESUMES'));
			JToolBarHelper :: cancel();
			$resumeuid=JRequest::getVar('ruid');
			$result =  $model->getUserStatsResumes($resumeuid,$limitstart, $limit);
			$items = $result[0];
			$total = $result[1];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('resumeuid', $resumeuid);
		}elseif($layoutName == 'jsjobsstats'){										// users
			JToolBarHelper :: title(JText::_('JS_JOBS_STATS'));
			$result =  $model->getJSJobsStats();	
			$this->assignRef('companies', $result[0]);
			$this->assignRef('jobs', $result[1]);
			$this->assignRef('resumes', $result[2]);
			$this->assignRef('featuredcompanies', $result[3]);
			$this->assignRef('goldcompanies', $result[4]);
			$this->assignRef('featuredjobs', $result[5]);
			$this->assignRef('goldjobs', $result[6]);
			$this->assignRef('featuredresumes', $result[7]);
			$this->assignRef('goldresumes', $result[8]);
			$this->assignRef('totalpaidamount', $result[9]);
			$this->assignRef('totalemployer', $result[10]);
			$this->assignRef('totaljobseeker', $result[11]);
		}elseif($layoutName == 'userfields'){										// user field
			$fieldfor = JRequest::getVar('ff');
			
			JToolBarHelper :: addNew();
			JToolBarHelper :: editList();
			JToolBarHelper :: deleteList();
			JToolBarHelper :: cancel();
			//$searchname	= $mainframe->getUserStateFromRequest( $form.'searchname', 'searchname','', 'string' );
			if ($fieldfor) $_SESSION['ffusr'] = $fieldfor; else $fieldfor = $_SESSION['ffusr'];

			if($fieldfor == 11 || $fieldfor == 12 || $fieldfor ==13)
				JToolBarHelper :: title(JText::_('JS_VISITOR_USER_FIELDS'));
			else
				JToolBarHelper :: title(JText::_('JS_USER_FIELDS'));
			$result =  $model->getUserFields($fieldfor, $limitstart, $limit);	// 1 for company
			$items = $result[0];
			$total = $result[1];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'configurations' || $layoutName == 'configurationsemployer' || $layoutName == 'configurationsjobseeker'){
			if($layoutName == 'configurations') $ptitle = JText::_('JS_CONFIGURATIONS');
			elseif($layoutName == 'configurationsemployer') $ptitle = JText::_('JS_EMPLOYER_CONFIGURATIONS');
			else $ptitle = JText::_('JS_JOBSEEKER_CONFIGURATIONS');
			JToolBarHelper :: title($ptitle);
			JToolBarHelper :: save();
			$result =  $model->getConfigurationsForForm();	
			$this->assignRef('lists', $result[1]);
		}elseif($layoutName == 'info'){
			JToolBarHelper :: title(JText::_('Information'));
		}elseif($layoutName == 'conf'){											// configurations
			JToolBarHelper :: title(JText::_('CONFIG'));
                        JToolBarHelper :: save();
			$result =  $model->getConfigurationsForForm();	
			$this->assignRef('lists', $result[1]);
		}elseif($layoutName == 'fieldsordering'){										// field ordering
			$fieldfor = JRequest::getVar('ff',0);
                        $session = JFactory::getSession();
                        $session->set('fieldfor',$fieldfor);
                        $fieldfor= $session->get('fieldfor');

            JToolBarHelper :: publishlist();
            JToolBarHelper :: unpublishlist();

			if ($fieldfor) $_SESSION['fford'] = $fieldfor; else $fieldfor = $_SESSION['fford'];

			if($fieldfor == 11 || $fieldfor == 12 || $fieldfor == 13)
				JToolBarHelper :: title(JText::_('JS_VISITOR_FIELDS'));
			else
				JToolBarHelper :: title(JText::_('JS_FIELDS'));

			$result =  $model->getFieldsOrdering($fieldfor, $limitstart, $limit);	// 1 for company
			$items = $result[0];
			$total = $result[1];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'emailtemplate'){										// email template
			$templatefor = JRequest::getVar('tf');
			switch($templatefor){
				case 'ew-cm' : $text = JText::_('JS_NEW_COMPANY'); break;
				case 'cm-ap' : $text = JText::_('JS_COMPANY_APPROVAL'); break;
				case 'cm-rj' : $text = JText::_('JS_COMPANY_REJECTING'); break;
				case 'ew-ob' : $text = JText::_('JS_NEW_JOB'); break;
				case 'ob-ap' : $text = JText::_('JS_JOB_APPROVAL'); break;
				case 'ob-rj' : $text = JText::_('JS_JOB_REJECTING'); break;
				case 'ap-rs' : $text = JText::_('JS_APPLIED_RESUME_STATUS'); break;
				case 'ew-rm' : $text = JText::_('JS_NEW_RESUME'); break;
				case 'ew-ms' : $text = JText::_('JS_NEW_MESSAGE'); break;
				case 'rm-ap' : $text = JText::_('JS_RESUME_APPROVAL'); break;
				case 'rm-rj' : $text = JText::_('JS_RESUME_REJECTING'); break;
				case 'ba-ja' : $text = JText::_('JS_JOB_APPLY'); break;
				case 'ew-md' : $text = JText::_('JS_NEW_DEPARTMENT'); break;
				case 'ew-rp' : $text = JText::_('JS_EMPLOYER_BUY_PACKAGE'); break;
				case 'ew-js' : $text = JText::_('JS_JOBSEEKER_BUY_PACKAGE'); break;
				case 'ms-sy' : $text = JText::_('JS_MESSAGE'); break;
				case 'jb-at' : $text = JText::_('JS_JOB_ALERT'); break;
				case 'jb-at-vis' : $text = JText::_('JS_EMPLOYER_VISITOR_JOB'); break;
				case 'jb-to-fri' : $text = JText::_('JS_JOB_TO_FRIEND'); break;
			}
			JToolBarHelper :: title(JText::_('JS_EMAIL_TEMPLATES').' <small><small>['.$text.'] </small></small>');
                        JToolBarHelper :: save();
			$template =  $model->getTemplate($templatefor);	
			$this->assignRef('template', $template);
		}elseif($layoutName == 'countries'){										// countries
			JToolBarHelper :: title(JText::_('JS_COUNTRIES'));
			JToolBarHelper :: addNew('editjobcountry');
			JToolBarHelper :: editList('editjobcountry');
            JToolBarHelper :: publishlist('publishcountries');
            JToolBarHelper :: unpublishlist('unpublishcountries');
			JToolBarHelper :: deleteList();
			//if ($cur_page != 'countries'){	$limitstart = 0;	$_SESSION['js_cur_page'] = 'countries';	$mainframe->setUserState( $option.'.limitstart', $limitstart );	}
                        
			$form = 'com_jsjobs.countries.list.';
			$searchname = $mainframe->getUserStateFromRequest( $form.'searchname', 'searchname','', 'string' );

			$result =  $model->getAllCountries($searchname,$limitstart, $limit);
			$items = $result[0];
			$total = $result[1];
            if(isset($result[2]))
			$this->assignRef('lists', $result[2]);
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'states'){										// states
            $countryid = JRequest::getVar('ct');
			$session = JFactory::getSession();
			if(!$countryid) $countryid = $session->set('countryid');
			$session->set('countryid', $countryid);
			JToolBarHelper :: title(JText::_('JS_STATES'));
			JToolBarHelper :: addNew('editjobstate');
			JToolBarHelper :: editList('editjobstate');
			JToolBarHelper :: deleteList();
			//if ($cur_page != 'states'){	$limitstart = 0;	$_SESSION['js_cur_page'] = 'states';	$mainframe->setUserState( $option.'.limitstart', $limitstart );	}

            $form = 'com_jsjobs.states.list.';
			$searchname = $mainframe->getUserStateFromRequest( $form.'searchname', 'searchname','', 'string' );

			$result =  $model->getAllCountryStates($searchname,$countryid, $limitstart, $limit);
			$items = $result[0];
			$total = $result[1];
            if(isset($result[2]))
                $this->assignRef('lists',$result[2]);
                $this->assignRef('ct', $countryid);
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'counties'){										// counties
			$statecode = JRequest::getVar('sd');
			$_SESSION['js_statecode'] = $statecode;
			JToolBarHelper :: title(JText::_('JS_COUNTIES'));
            JToolBarHelper :: addNew();
            JToolBarHelper :: publishlist();
            JToolBarHelper :: unpublishlist();
			JToolBarHelper :: deleteList();
			if ($cur_page != 'counties'){	$limitstart = 0;	$_SESSION['js_cur_page'] = 'counties';	$mainframe->setUserState( $option.'.limitstart', $limitstart );	}

            $form = 'com_jsjobs.counties.list.';
			$searchname = $mainframe->getUserStateFromRequest( $form.'searchname', 'searchname','', 'string' );

            $result =  $model->getAllStateCounties($searchname,$statecode, $limitstart, $limit);
			$items = $result[0];
			$total = $result[1];
            if(isset($result[2]))
                $this->assignRef('lists',$result[2]);
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'cities'){										// cities
			$stateid = JRequest::getVar('sd');
			$countryid = JRequest::getVar('ct');
			$session = JFactory::getSession();
			$session->set('countryid', $countryid);
			$session->set('stateid', $stateid);

			JToolBarHelper :: title(JText::_('JS_CITIES'));
			//if ($cur_page != 'cities'){	$limitstart = 0;	$_SESSION['js_cur_page'] = 'cities';	$mainframe->setUserState( $option.'.limitstart', $limitstart );	}

            $form = 'com_jsjobs.counties.list.';
			$searchname = $mainframe->getUserStateFromRequest( $form.'searchname', 'searchname','', 'string' );

            //$result =  $model->getAllCountyCities($searchname,$countycode, $limitstart, $limit);
            $result =  $model->getAllStatesCities($searchname,$stateid,$countryid, $limitstart, $limit);
			$items = $result[0];
			$total = $result[1];
            if(isset($result[2]))
                $this->assignRef('lists',$result[2]);
                $this->assignRef('sd', $stateid);
                $this->assignRef('ct', $countryid);
                
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			JToolBarHelper :: addNew('editjobcity');
			JToolBarHelper :: editList('editjobcity');
			JToolBarHelper :: publishList('publishcities');
			JToolBarHelper :: unpublishList('unpublishcities');
			JToolBarHelper :: deleteList();
		}elseif($layoutName == 'loadaddressdata'){										// load address data
			JToolBarHelper :: title(JText::_('JS_LOAD_ADDRESS_DATA'));
			$error = 0;
			if (isset($_GET['er'])) $error = $_GET['er'];
			$this->assignRef('error', $error);
		}elseif($layoutName == 'goldresumes'){								//employer packages
			JToolBarHelper :: title(JText::_('JS_GOLD_RESUMES'));
			$form = 'com_jsjobs.empapps.list.';
			$searchtitle = $mainframe->getUserStateFromRequest( $form.'searchtitle', 'searchtitle',	'',	'string' );
			$searchname = $mainframe->getUserStateFromRequest( $form.'searchname', 'searchname',	'',	'string' );
			$searchjobseekerpackage = $mainframe->getUserStateFromRequest( $form.'searchjobseekerpackage', 'searchjobseekerpackage',	'',	'string' );
			$result =  $model->getGoldResumes($searchtitle,$searchname,$searchjobseekerpackage,$limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists',$lists);
			JToolBarHelper :: deleteList();
		}elseif($layoutName == 'goldresumesqueue'){				//companies queue
			JToolBarHelper :: title(JText::_('JS_GOLD_RESUMES_QUEUE'));
			$form = 'com_jsjobs.empapps.list.';
			$searchtitle = $mainframe->getUserStateFromRequest( $form.'searchtitle', 'searchtitle',	'',	'string' );
			$searchname = $mainframe->getUserStateFromRequest( $form.'searchname', 'searchname',	'',	'string' );
			$searchjobseekerpackage = $mainframe->getUserStateFromRequest( $form.'searchjobseekerpackage', 'searchjobseekerpackage',	'',	'string' );
			$result =  $model->getAllUnapprovedGoldResume($searchtitle,$searchname,$searchjobseekerpackage,$limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists',$lists);
		}elseif($layoutName == 'addtogoldresumes'){								//employment applications
			JToolBarHelper :: title(JText::_('JS_ADD_TO_GOLD_RESUMES'));
			$result =  $model->getAllEmpAppsListing($limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'featuredresumes'){								//featured resume 
			JToolBarHelper :: title(JText::_('JS_FEATURED_RESUMES'));
			$form = 'com_jsjobs.empapps.list.';
			$searchtitle = $mainframe->getUserStateFromRequest( $form.'searchtitle', 'searchtitle',	'',	'string' );
			$searchname = $mainframe->getUserStateFromRequest( $form.'searchname', 'searchname',	'',	'string' );
			$searchemployerpackage = $mainframe->getUserStateFromRequest( $form.'searchemployerpackage', 'searchemployerpackage',	'',	'string' );
			$result =  $model->getFeaturedResumes($searchtitle,$searchname,$searchemployerpackage,$limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists',$lists);
			JToolBarHelper :: deleteList();
		}elseif($layoutName == 'featuredresumesqueue'){				//featuredresumequeue
			JToolBarHelper :: title(JText::_('JS_FEATURED_RESUMES_QUEUE'));
			$form = 'com_jsjobs.empapps.list.';
			$searchtitle = $mainframe->getUserStateFromRequest( $form.'searchtitle', 'searchtitle',	'',	'string' );
			$searchname = $mainframe->getUserStateFromRequest( $form.'searchname', 'searchname',	'',	'string' );
			$searchjobseekerpackage = $mainframe->getUserStateFromRequest( $form.'searchjobseekerpackage', 'searchjobseekerpackage',	'',	'string' );
			$result =  $model->getAllUnapprovedFeaturedResume($searchtitle,$searchname,$searchjobseekerpackage,$limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists',$lists);
		}elseif($layoutName == 'addtofeaturedresumes'){								//employment applications
			JToolBarHelper :: title(JText::_('JS_ADD_TO_FEATURED_RESUMES'));
			$result =  $model->getAllEmpAppsListing( $limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'featuredjobs'){								//employer packages
			JToolBarHelper :: title(JText::_('JS_FEATURED_JOBS'));
			$form = 'com_jsjobs.jobs.list.';
			$searchtitle = $mainframe->getUserStateFromRequest( $form.'searchtitle', 'searchtitle',	'',	'string' );
			$searchcompany = $mainframe->getUserStateFromRequest( $form.'searchcompany', 'searchcompany',	'',	'string' );
			$searchemployerpackage = $mainframe->getUserStateFromRequest( $form.'searchemployerpackage', 'searchemployerpackage',	'',	'string' );
			$result =  $model->getFeaturedJobs($searchtitle,$searchcompany,$searchemployerpackage,$limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists' , $lists);
			JToolBarHelper :: deleteList();
		}elseif($layoutName == 'featuredjobsqueue'){				//companies queue
			JToolBarHelper :: title(JText::_('JS_FEATURED_JOBS_QUEUE'));
			$form = 'com_jsjobs.jobs.list.';
			$searchtitle = $mainframe->getUserStateFromRequest( $form.'searchtitle', 'searchtitle',	'',	'string' );
			$searchcompany = $mainframe->getUserStateFromRequest( $form.'searchcompany', 'searchcompany',	'',	'string' );
			$searchemployerpackage = $mainframe->getUserStateFromRequest( $form.'searchemployerpackage', 'searchemployerpackage',	'',	'string' );
			$result =  $model->getAllUnapprovedFeaturedJobs($searchtitle,$searchcompany,$searchemployerpackage,$limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists' , $lists);
		}elseif($layoutName == 'goldjobs'){								//employer packages
			JToolBarHelper :: title(JText::_('JS_GOLD_JOBS'));
			$form = 'com_jsjobs.jobs.list.';
			$searchtitle = $mainframe->getUserStateFromRequest( $form.'searchtitle', 'searchtitle',	'',	'string' );
			$searchcompany = $mainframe->getUserStateFromRequest( $form.'searchcompany', 'searchcompany',	'',	'string' );
			$searchemployerpackage = $mainframe->getUserStateFromRequest( $form.'searchemployerpackage', 'searchemployerpackage',	'',	'string' );
			$result =  $model->getGoldJobs($searchtitle,$searchcompany,$searchemployerpackage,$limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists' , $lists);
			JToolBarHelper :: deleteList();
		}elseif($layoutName == 'addtogoldjobs'){								//jobs
			JToolBarHelper :: title(JText::_('JS_ADD_TO_GOLD_JOBS'));
			$result =  $model->getAllJobListings(1,$limitstart, $limit);	// 1 for gold job
			$items = $result[0];
			$total = $result[1];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'addtofeaturedjobs'){								//jobs
			JToolBarHelper :: title(JText::_('JS_ADD_TO_FEATURED_JOBS'));
			$result =  $model->getAllJobListings(2,$limitstart, $limit);	// 2 for featured jobs
			$items = $result[0];
			$total = $result[1];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'goldjobsqueue'){				//companies queue
			JToolBarHelper :: title(JText::_('JS_GOLD_JOBS_QUEUE'));
			$form = 'com_jsjobs.jobs.list.';
			$searchtitle = $mainframe->getUserStateFromRequest( $form.'searchtitle', 'searchtitle',	'',	'string' );
			$searchcompany = $mainframe->getUserStateFromRequest( $form.'searchcompany', 'searchcompany',	'',	'string' );
			$searchemployerpackage = $mainframe->getUserStateFromRequest( $form.'searchemployerpackage', 'searchemployerpackage',	'',	'string' );
			$result =  $model->getAllUnapprovedGoldJobs($searchtitle,$searchcompany,$searchemployerpackage,$limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists' , $lists);
		}elseif($layoutName == 'departments'){								//employer packages
			JToolBarHelper :: title(JText::_('JS_DEPARTMENTS'));
			JToolBarHelper :: addNew();
			JToolBarHelper :: editList();
			JToolBarHelper :: deleteList();
			$searchcompany = $mainframe->getUserStateFromRequest( $option.'searchcompany', 'searchcompany',	'',	'string' );
			$searchdepartment = $mainframe->getUserStateFromRequest( $option.'searchdepartment', 'searchdepartment',	'',	'string' );
			$result =  $model->getDepartments($searchcompany,$searchdepartment,$limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists', $lists);
		}elseif($layoutName == 'company_departments'){								//employer packages
			JToolBarHelper :: title(JText::_('JS_COMPANY_DEPARTMENTS'));
			JToolBarHelper :: cancel();
			$companyid = JRequest::getVar('md');
			$_SESSION['companyid'] = $companyid;
			$searchcompany = $mainframe->getUserStateFromRequest( $option.'searchcompany', 'searchcompany',	'',	'string' );
			$searchdepartment = $mainframe->getUserStateFromRequest( $option.'searchdepartment', 'searchdepartment',	'',	'string' );
			$result =  $model->getCompanyDepartments($companyid,$searchcompany,$searchdepartment,$limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			if(isset($result[2])) $lists = $result[2];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			 if(isset($lists))  $this->assignRef('lists', $lists);
			$this->assignRef('companyid', $companyid);
		}
		elseif($layoutName == 'departmentqueue'){				//companies queue
			JToolBarHelper :: title(JText::_('JS_DEPARTMENT_QUEUE'));
			$searchcompany = $mainframe->getUserStateFromRequest( $option.'searchcompany', 'searchcompany',	'',	'string' );
			$searchdepartment = $mainframe->getUserStateFromRequest( $option.'searchdepartment', 'searchdepartment',	'',	'string' );
			$result =  $model->getAllUnapprovedDepartments($searchcompany,$searchdepartment,$limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists', $lists);
		}elseif($layoutName == 'goldcompanies'){								//employer packages
			JToolBarHelper :: title(JText::_('JS_GOLD_COMPANIES'));
			$searchcompany = $mainframe->getUserStateFromRequest( $option.'searchcompany', 'searchcompany',	'',	'string' );
			$searchemployerpackage = $mainframe->getUserStateFromRequest( $option.'searchemployerpackage', 'searchemployerpackage',	'',	'string' );
			$searchcountry = $mainframe->getUserStateFromRequest( $option.'searchcountry', 'searchcountry',	'',	'string' );

			$result =  $model->getGoldCompanies($searchcompany,$searchemployerpackage,$searchcountry,$limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];

			$lists = $result[2];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists', $lists);
			JToolBarHelper :: addNew();
			JToolBarHelper :: deleteList();
		}elseif($layoutName == 'goldcompaniesqueue'){				//companies queue
			JToolBarHelper :: title(JText::_('JS_GOLD_COMPANIES_QUEUE'));
			$searchcompany = $mainframe->getUserStateFromRequest( $option.'searchcompany', 'searchcompany',	'',	'string' );
			$searchemployerpackage = $mainframe->getUserStateFromRequest( $option.'searchemployerpackage', 'searchemployerpackage',	'',	'string' );
			$searchcountry = $mainframe->getUserStateFromRequest( $option.'searchcountry', 'searchcountry',	'',	'string' );
			$result =  $model->getAllUnapprovedGoldCompanies($searchcompany,$searchemployerpackage,$searchcountry,$limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists', $lists);
		}elseif($layoutName == 'addtogoldcompanies'){				//companies
			JToolBarHelper :: title(JText::_('JS_ADD_TO_GOLD_COMPANIES'));
			$result =  $model->getAllCompaniesListing( 1, $limitstart, $limit);	// 1 for gold 
			$items = $result[0];
			$total = $result[1];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'featuredcompanies'){								//employer packages
			JToolBarHelper :: title(JText::_('JS_FEATURED_COMPANIES'));
			$searchcompany = $mainframe->getUserStateFromRequest( $option.'searchcompany', 'searchcompany',	'',	'string' );
			$searchemployerpackage = $mainframe->getUserStateFromRequest( $option.'searchemployerpackage', 'searchemployerpackage',	'',	'string' );
			$searchcountry = $mainframe->getUserStateFromRequest( $option.'searchcountry', 'searchcountry',	'',	'string' );
			$result =  $model->getFeaturedCompanies($searchcompany,$searchemployerpackage,$searchcountry,$limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists', $lists);
			JToolBarHelper :: addNew();
			JToolBarHelper :: deleteList();
		}elseif($layoutName == 'featuredcompaniesqueue'){				//companies queue
			JToolBarHelper :: title(JText::_('JS_FETAURED_COMPANIES_QUEUE'));
			$searchcompany = $mainframe->getUserStateFromRequest( $option.'searchcompany', 'searchcompany',	'',	'string' );
			$searchemployerpackage = $mainframe->getUserStateFromRequest( $option.'searchemployerpackage', 'searchemployerpackage',	'',	'string' );
			$searchcountry = $mainframe->getUserStateFromRequest( $option.'searchcountry', 'searchcountry',	'',	'string' );
			$result =  $model->getAllUnapprovedFeaturedCompanies($searchcompany,$searchemployerpackage,$searchcountry,$limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists', $lists);
		}elseif($layoutName == 'addtofeaturedcompanies'){				//companies
			JToolBarHelper :: title(JText::_('JS_ADD_TO_FEATURED_COMPANIES'));
			$result =  $model->getAllCompaniesListing(2, $limitstart, $limit);	// 2 for featured 
			$items = $result[0];
			$total = $result[1];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'employerpaymenthistory'){
			$packagefor=1;								
			JToolBarHelper :: title(JText::_('JS_EMPLOYER_PAYMENT_HISTORY'));
            JToolBarHelper::addNew();
			$form = 'com_jsjobs.jobs.list.';
			$searchtitle = $mainframe->getUserStateFromRequest( $form.'searchtitle', 'searchtitle',	'',	'string' );
			$searchprice = $mainframe->getUserStateFromRequest( $form.'searchprice', 'searchprice',	'',	'string' );
			$searchpaymentstatus = $mainframe->getUserStateFromRequest( $form.'searchpaymentstatus', 'searchpaymentstatus',	'',	'string' );

			$result =  $model->getEmployerPaymentHistory($searchtitle,$searchprice,$searchpaymentstatus,$packagefor,$limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			$this->assignRef('lists', $lists);
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'payment_report'){								
			JToolBarHelper :: title(JText::_('JS_PAYMENT_REPORT'));
			$form = 'com_jsjobs.jobs.list.';
			$buyername = $mainframe->getUserStateFromRequest( $form.'buyername', 'buyername',	'',	'string' );
			$paymentfor = $mainframe->getUserStateFromRequest( $form.'paymentfor', 'paymentfor',	'',	'string' );
			$searchpaymentstatus = $mainframe->getUserStateFromRequest( $form.'searchpaymentstatus', 'searchpaymentstatus',	'',	'string' );
			$searchstartdate = $mainframe->getUserStateFromRequest( $form.'prsearchstartdate', 'prsearchstartdate',	'',	'string' );
			$searchenddate = $mainframe->getUserStateFromRequest( $form.'prsearchenddate', 'prsearchenddate',	'',	'string' );
			$result =  $model->getPaymentReport($buyername,$paymentfor,$searchpaymentstatus,$searchstartdate,$searchenddate,$limitstart, $limit);
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			$this->assignRef('lists', $lists);
			$this->assignRef('paymentfor', $result[3]);
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'package_paymentreport'){								
			$packageid=JRequest::getVar('pk');
			if ($packageid) $_SESSION['pk'] = $packageid; else $packageid = $_SESSION['pk'];
			$paymentfor=JRequest::getVar('pf');
			if ($paymentfor) $_SESSION['pf'] = $paymentfor; else $paymentfor = $_SESSION['pf'];
			$form = 'com_jsjobs.jobs.list.';
			$searchpaymentstatus = $mainframe->getUserStateFromRequest( $form.'searchpaymentstatus', 'searchpaymentstatus',	'',	'string' );

			$searchstartdate = $mainframe->getUserStateFromRequest( $form.'searchstartdate', 'searchstartdate',	'',	'string' );
			$searchenddate = $mainframe->getUserStateFromRequest( $form.'searchenddate', 'searchenddate',	'',	'string' );
			$result =  $model->getPackagePaymentReport($packageid,$paymentfor,$searchpaymentstatus,$searchstartdate,$searchenddate,$limitstart, $limit);	
			JToolBarHelper :: title($result[0][0]->packagetitle.' '.JText::_('JS_REPORT'));
			JToolBarHelper :: cancel();
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			$this->assignRef('lists', $lists);
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'jobseekerpaymenthistory'){								//employer packages
			JToolBarHelper :: title(JText::_('JS_JOBSEEKER_PAYMENT_HISTORY'));
		            JToolBarHelper::addNew();
			$form = 'com_jsjobs.jobs.list.';
			$packagefor=2;

			$searchtitle = $mainframe->getUserStateFromRequest( $form.'searchtitle', 'searchtitle',	'',	'string' );
			$searchprice = $mainframe->getUserStateFromRequest( $form.'searchprice', 'searchprice',	'',	'string' );
			$searchpaymentstatus = $mainframe->getUserStateFromRequest( $form.'searchpaymentstatus', 'searchpaymentstatus',	'',	'string' );
			$result =  $model->getJobseekerPaymentHistory($searchtitle,$searchprice,$searchpaymentstatus,$packagefor,$limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			$this->assignRef('lists', $lists);
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'employerpaymentdetails'){										// employer package info
			JToolBarHelper :: cancel();
			$paymentid = $_GET['pk'];
			$_SESSION['js_paymentid'] = $paymentid;
			JToolBarHelper :: title(JText::_('JS_PAYMENT_HISTORY_DETAILS'));
			//if ($cur_page != 'states'){	$limitstart = 0;	$_SESSION['js_cur_page'] = 'states';	$mainframe->setUserState( $option.'.limitstart', $limitstart );	}
			$result =  $model->getEmployerPaymentHistorybyId($paymentid);
			$items = $result[0];
		}elseif($layoutName == 'jobseekerpaymentdetails'){										// employer package info
			$packageid = $_GET['pk'];
			$_SESSION['js_packageid'] = $packageid;
			JToolBarHelper :: title(JText::_('JS_PAYMENT_HISTORY_DETAILS'));
			JToolBarHelper :: cancel();
			//if ($cur_page != 'states'){	$limitstart = 0;	$_SESSION['js_cur_page'] = 'states';	$mainframe->setUserState( $option.'.limitstart', $limitstart );	}
			$result =  $model->getJobseekerPaymentHistorybyId($packageid);	
			$items = $result[0];
		}elseif($layoutName == 'employerpackages'){								//employer packages
			JToolBarHelper :: title(JText::_('JS_EMPLOYER_PACKAGES'));
                        JToolBarHelper :: addNew();
			JToolBarHelper :: editList();
			JToolBarHelper :: deleteList();
			$result =  $model->getEmployerPackages($limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
		}elseif($layoutName == 'jobseekerpackages'){								//job seeker packages
			JToolBarHelper :: title(JText::_('JS_JOBSEEKER_PACKAGES'));
                        JToolBarHelper :: addNew();
			JToolBarHelper :: editList();
			JToolBarHelper :: deleteList();
			$result =  $model->getJobSeekerPackages($limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
	}elseif($layoutName == 'currency'){

			JToolBarHelper :: title(JText::_('JS_CURRENCY'));
            JToolBarHelper :: addNew('editjobcurrency');
			JToolBarHelper :: editList('editjobcurrency');
			JToolBarHelper :: deleteList();

			$result =  $model->getAllCurrencies($limitstart, $limit);	
			$items = $result[0];
			$total = $result[1];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			
	}elseif(($layoutName == 'messages') || ($layoutName == 'messagesqueue')){				//messages
			if($layoutName == 'messages'){
                            $statusoperator = "<>";
                            JToolBarHelper :: title(JText::_('JS_MESSAGES'));
                            JToolBarHelper :: editList();
                            JToolBarHelper :: deleteList();
                        }else{
                            $statusoperator = "=";
                            JToolBarHelper :: title(JText::_('JS_MESSAGES_APPROVAL_QUEUE'));
                        }
			if ($cur_page != 'messages'){	$limitstart = 0;	$_SESSION['js_cur_page'] = 'messages';	$mainframe->setUserState( $option.'.limitstart', $limitstart );	}
			$username = $mainframe->getUserStateFromRequest( $option.'message_username', 'message_username',	'',	'string' );
			$usertype = $mainframe->getUserStateFromRequest( $option.'message_usertype', 'message_usertype',	'',	'string' );
			$conflict= $mainframe->getUserStateFromRequest( $option.'message_conflicted', 'message_conflicted',	'',	'string' );
			$company = $mainframe->getUserStateFromRequest( $option.'message_company', 'message_company',	'',	'string' );
			$jobtitle = $mainframe->getUserStateFromRequest( $option.'message_jobtitle', 'message_jobtitle',	'',	'string' );
			$subject = $mainframe->getUserStateFromRequest( $option.'message_subject', 'message_subject',	'',	'string' );
			$result =  $model->getAllMessages($statusoperator,$username, $usertype, $company, $jobtitle, $subject, $limitstart, $limit);
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists', $lists);
		}elseif($layoutName == 'companiesqueue'){				//companies queue
			JToolBarHelper :: title(JText::_('JS_COMPANIES_QUEUE'));
			$searchcompany = $mainframe->getUserStateFromRequest( $option.'searchcompany', 'searchcompany',	'',	'string' );
			$searchjobcategory = $mainframe->getUserStateFromRequest( $option.'searchjobcategory', 'searchjobcategory',	'',	'string' );
			$searchcountry = $mainframe->getUserStateFromRequest( $option.'searchcountry', 'searchcountry',	'',	'string' );
			$result =  $model->getAllUnapprovedCompanies($searchcompany, $searchjobcategory, $searchcountry, $limitstart, $limit);
			$items = $result[0];
			$total = $result[1];
			$lists = $result[2];
			if ( $total <= $limitstart ) $limitstart = 0;
			$pagination = new JPagination( $total, $limitstart, $limit );
			$this->assignRef('lists', $lists);
		}elseif($layoutName == 'paymentmethodconfig'){										// employer package info
			JToolBarHelper :: save('savepaymentconf');
			JToolBarHelper :: title(JText::_('PAYMENT_METHODS_CONFIGURATION'));
			$result =  $model->getPaymentMethodsConfig();
			$this->assignRef('paymentmethodconfig',$result);
		}elseif($layoutName == 'themes'){				//Themes
			JToolBarHelper :: title(JText::_('JS_THEMES'));
			JToolBarHelper :: cancel();
		}else{
			JToolBarHelper :: title(JText::_('JS_JOBS'));
		}
		$this->assignRef('items', $items);
		$this->assignRef('config', $config);
		$this->assignRef('option', $option);
		$this->assignRef('pagination', $pagination);
		$this->assignRef('isjobsharing', $_client_auth_key);
		
		parent :: display($tpl);
	}
	function getSortArg($sort) {
		if($sort == 'asc') return "desc";
		else return "asc";
	}
}
?>
