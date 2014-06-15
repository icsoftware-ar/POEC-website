<?php
/**
 * @Copyright Copyright (C) 2009-2011 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin/views/application/view.html.php
 ^ 
 * Description: View class for single record in the admin
 ^ 
 * History:		NONE
 * 
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

class JSJobsViewApplication extends JViewLegacy
{
	function display($tpl = null)
	{
		global $_client_auth_key;
		$model		= &$this->getModel();
		$msg = JRequest :: getVar('msg');
		$option = 'com_jsjobs';
		
		$mainframe = &JFactory::getApplication();
		
	    $version = new JVersion;
	    $joomla = $version->getShortVersion();
		$jversion = substr($joomla,0,3);
		if($_client_auth_key=="") {
			$auth_key=$model->getClientAuthenticationKey();
			$_client_auth_key=$auth_key;
		}
		
		$cur_layout = $_SESSION['cur_layout'];
		$layoutName = JRequest :: getVar('layout', '');
        $limit = $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart	= JREQUEST::getVar('limitstart', 0, 'int' );
		$isNew = true;
		$user	=& JFactory::getUser();
		$uid=$user->id;
		// get configurations
		$config = Array();
		if (isset($_SESSION['jsjobconfig'])) $config = $_SESSION['jsjobconfig']; else $config=null;
		//$config = Array();
		if (sizeof($config) == 0){
			$results =  $model->getConfig();	
		//	if ($results){ //not empty
				foreach ($results as $result){
					$config[$result->configname] = $result->configvalue;
				}
				$_SESSION['jsjobconfig'] = $config;	
		//	}
		}

		$theme['title'] = 'jppagetitle';
		$theme['heading'] = 'pageheadline';
		$theme['sectionheading'] = 'sectionheadline';
		$theme['sortlinks'] = 'sortlnks';
		$theme['odd'] = 'odd';
		$theme['even'] = 'even';
		if ($cur_layout == 'categories'){										// categories
			if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];	
			else $c_id='';	
			
			if ($c_id == ''){
				$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
				$c_id= $cids[0];				
			}
			if (is_numeric($c_id) == true AND $c_id!=0) $application =  $model->getCategorybyId($c_id);	
			if ( isset($application->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_CATEGORY') . ': <small><small>[ ' . $text . ' ]</small></small>');
                        JToolBarHelper :: save();
        		if ($isNew) JToolBarHelper :: cancel();	else JToolBarHelper :: cancel('cancel', 'Close');
		}elseif ($layoutName == 'formsubcategory'){										// categories
			if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];
			else $c_id='';

			if ($c_id == ''){
				$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
				$c_id= $cids[0];
			}
                        $session = JFactory::getSession();
                        $categoryid = $session->get('sub_categoryid');
			$subcategory =  $model->getSubCategorybyId($c_id,$categoryid);
			if ( isset($subcategory->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('SUB_CATEGORY') . ': <small><small>[ ' . $text . ' ]</small></small>');
			$this->assignRef('subcategory', $subcategory);
			$this->assignRef('categoryid', $categoryid);
                        JToolBarHelper :: save('savesubcategory');
        		if ($isNew) JToolBarHelper :: cancel('cancelsubcategories'); else JToolBarHelper :: cancel('cancelsubcategories', 'Close');

		}elseif ($layoutName == 'formresumeuserfield'){						// form resume user fields
	                $session = JFactory::getSession();
			$ff = JRequest::getVar('ff');
			if($ff=="")  $ff = $session->get('formresumeuserfield_ff');
			
                        $session->set('formresumeuserfield_ff',$ff);
			$result =  $model->getResumeUserFields($ff);

			$this->assignRef('userfields', $result);
			if($ff == 13)
				JToolBarHelper :: title(JText :: _('JS_VISITOR_USER_FIELDS'));
			else
				JToolBarHelper :: title(JText :: _('JS_USER_FIELDS'));
			JToolBarHelper :: save('saveresumeuserfields');
			JToolBarHelper :: cancel('cancel', 'Close');
			$this->assignRef('fieldfor',$ff);
		}elseif ($layoutName == 'formmessage' ) {
                        $cids = JRequest :: getVar('cid', array (0), 'post', 'array');
                        $c_id= $cids[0];
                        if(!$c_id)$c_id = JRequest :: getVar('cid');
                        if($c_id){
                            if (is_numeric($c_id) == true) $results =  $model->getMessagesbyId($c_id);
                            $sm = JRequest :: getVar('sm',3);
                            $this->assignRef('sm', $sm);
                            $this->assignRef('message', $results[0]);
                            $this->assignRef('lists', $results[1]);
                            if ( isset($results[0]->id) ) $isNew = false;
                            $text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
                            JToolBarHelper :: title(JText :: _('JS_MESSAGE') . ': <small><small>[ ' . $text . ' ]</small></small>');
                            JToolBarHelper :: save('savemessage');
                            if ($isNew) JToolBarHelper :: cancel();	else JToolBarHelper :: cancel('cancel', 'Close');
                        }else{
                            $jobid = JRequest :: getVar('bd');
                            $resumeid = JRequest :: getVar('rd');
                            $text =  JText :: _('JS_SEND') ;
                            JToolBarHelper :: title(JText :: _('JS_MESSAGE') . ': <small><small>[ ' . $text . ' ]</small></small>');
                            $results =  $model->getMessagesbyJobResumes($uid,$jobid,$resumeid);
                            JToolBarHelper :: save('savemessage');
                             JToolBarHelper :: cancel('cancelsendmessage');
                            $sm=1;
                            $this->assignRef('sm',$sm);
                            $this->assignRef('message', $results[0]);
                            $this->assignRef('lists', $results[2]);
                            $this->assignRef('summary', $results[1]);
                        }

			}elseif($layoutName == 'formcurrency'){
				if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];	
				else $c_id='';	
				if ($c_id == ''){
					 $cids = JRequest :: getVar('cid', array (0), 'post', 'array');
					 $c_id= $cids[0]; 
				}
				//if (is_numeric($c_id) == true)
				if (is_numeric($c_id) == true AND $c_id!=0) $currency =  $model->getCurrencybyId($c_id);
                $this->assignRef('currency', $currency);
				if ( isset($currency->id) ) $isNew = false;
				$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
				JToolBarHelper :: title(JText :: _('JS_CURRENCY') . ': <small><small>[ ' . $text . ' ]</small></small>');
				JToolBarHelper::apply('savejobcurrencysave','SAVE');
				if(JVERSION > 2)
					JToolBarHelper :: save2new('savejobcurrencyandnew');
				JToolBarHelper :: save('savejobcurrency');
				if ($isNew) JToolBarHelper :: cancel();	else JToolBarHelper :: cancel('cancel', 'Close');
			
			}elseif ($layoutName == 'formjobtype'){										// jobtypes
							if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];	
							else $c_id='';	
							
							if ($c_id == ''){
								$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
								$c_id= $cids[0];				
							}
							if (is_numeric($c_id) == true AND $c_id!=0) $application =  $model->getJobTypebyId($c_id);	
							if ( isset($application->id) ) $isNew = false;
							$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
							JToolBarHelper :: title(JText :: _('JS_JOB_TYPE') . ': <small><small>[ ' . $text . ' ]</small></small>');
							JToolBarHelper::apply('savejobtypesave','SAVE');
							if(JVERSION > 2)
								JToolBarHelper :: save2new('savejobtypeandnew');
							JToolBarHelper :: save('savejobtype');
							if ($isNew) JToolBarHelper :: cancel();	else JToolBarHelper :: cancel('cancel', 'Close');
			}elseif ($layoutName == 'formages'){										// jobtypes
							if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];
							else $c_id='';

							if ($c_id == ''){
								$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
								$c_id= $cids[0];
							}
							if (is_numeric($c_id) == true AND $c_id!=0) $application =  $model->getJobAgesbyId($c_id);
							if ( isset($application->id) ) $isNew = false;
							$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
							JToolBarHelper :: title(JText :: _('JS_JOB_AGES') . ': <small><small>[ ' . $text . ' ]</small></small>');
							JToolBarHelper::apply('savejobagesave','SAVE');
							if(JVERSION > 2)
								JToolBarHelper :: save2new('savejobageandnew');
							JToolBarHelper :: save('savejobage');
								if ($isNew) JToolBarHelper :: cancel();	else JToolBarHelper :: cancel('cancel', 'Close');
			}elseif ($layoutName == 'formcareerlevels'){										// jobtypes
							if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];
							else $c_id='';

							if ($c_id == ''){
								$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
								$c_id= $cids[0];
							}
							if (is_numeric($c_id) == true AND $c_id!=0) $application =  $model->getJobCareerLevelbyId($c_id);
							if ( isset($application->id) ) $isNew = false;
							$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
							JToolBarHelper :: title(JText :: _('JS_JOB_CAREER_LEVELS') . ': <small><small>[ ' . $text . ' ]</small></small>');
							JToolBarHelper::apply('savejobcareerlevelsave','SAVE');
							if(JVERSION > 2)
								JToolBarHelper :: save2new('savejobcareerlevelandnew');
							JToolBarHelper :: save('savejobcareerlevel');
								if ($isNew) JToolBarHelper :: cancel();	else JToolBarHelper :: cancel('cancel', 'Close');
			}elseif ($layoutName == 'formexperience'){										// jobtypes
							if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];
							else $c_id='';

							if ($c_id == ''){
								$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
								$c_id= $cids[0];
							}
							if (is_numeric($c_id) == true AND $c_id!=0) $application =  $model->getJobExperiencebyId($c_id);
							if ( isset($application->id) ) $isNew = false;
							$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
							JToolBarHelper :: title(JText :: _('JS_JOB_EXPERIENCE') . ': <small><small>[ ' . $text . ' ]</small></small>');
							JToolBarHelper::apply('savejobexperiencesave','SAVE');
							if(JVERSION > 2)
								JToolBarHelper :: save2new('savejobexperienceandnew');
							JToolBarHelper :: save('savejobexperience');
								if ($isNew) JToolBarHelper :: cancel();	else JToolBarHelper :: cancel('cancel', 'Close');
			}elseif ($layoutName == 'formsalaryrangetype'){										// jobtypes
							if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];
							else $c_id='';

							if ($c_id == ''){
								$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
								$c_id= $cids[0];
							}
							if (is_numeric($c_id) == true AND $c_id!=0) $application =  $model->getSalaryRangeTypebyId($c_id);
							if ( isset($application->id) ) $isNew = false;
							$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
							JToolBarHelper :: title(JText :: _('JS_SALARY_RANGE_TYPES') . ': <small><small>[ ' . $text . ' ]</small></small>');
							JToolBarHelper::apply('savejobsalaryrangetypesave','SAVE');
							if(JVERSION > 2)
								JToolBarHelper :: save2new('savejobsalaryrangetypeandnew');
							JToolBarHelper :: save('savejobsalaryrangetype');
								if ($isNew) JToolBarHelper :: cancel();	else JToolBarHelper :: cancel('cancel', 'Close');
		}elseif ($layoutName == 'formjobstatus'){										// job status
			if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];	
			else $c_id='';	
			
			if ($c_id == ''){
				$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
				$c_id= $cids[0];				
			}
			if (is_numeric($c_id) == true AND $c_id!=0) $application =  $model->getJobStatusbyId($c_id);	
			if ( isset($application->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_JOB_STATUS') . ': <small><small>[ ' . $text . ' ]</small></small>');
			JToolBarHelper::apply('savejobstatussave','SAVE');
			if(JVERSION > 2)
				JToolBarHelper :: save2new('savejobstatusandnew');
			JToolBarHelper :: save('savejobstatus');
			if ($isNew) JToolBarHelper :: cancel();	else JToolBarHelper :: cancel('cancel', 'Close');
		}elseif ($layoutName == 'formshift'){										// shifts
			if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];	
			else $c_id='';	
			
			if ($c_id == ''){
				$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
				$c_id= $cids[0];				
			}
			if (is_numeric($c_id) == true AND $c_id!=0) $application =  $model->getShiftbyId($c_id);	
			if ( isset($application->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_SHIFT') . ': <small><small>[ ' . $text . ' ]</small></small>');
			JToolBarHelper::apply('savejobshiftsave','SAVE');
			if(JVERSION > 2)
				JToolBarHelper :: save2new('savejobshiftandnew');
			JToolBarHelper :: save('savejobshift');
        		if ($isNew) JToolBarHelper :: cancel();	else JToolBarHelper :: cancel('cancel', 'Close');
		}elseif ($layoutName == 'formhighesteducation'){										// highest educations
			if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];	
			else $c_id='';	
			
			if ($c_id == ''){
				$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
				$c_id= $cids[0];				
			}
			if (is_numeric($c_id) == true AND $c_id!=0) $application =  $model->getHighestEducationbyId($c_id);	
			if ( isset($application->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_HIGHEST_EDUCATION') . ': <small><small>[ ' . $text . ' ]</small></small>');
			JToolBarHelper::apply('savejobhighesteducationsave','SAVE');
			if(JVERSION > 2)
				JToolBarHelper :: save2new('savejobhighesteducationandnew');
			JToolBarHelper :: save('savejobhighesteducation');
        		if ($isNew) JToolBarHelper :: cancel();	else JToolBarHelper :: cancel('cancel', 'Close');
		}elseif ($layoutName == 'formfolder' ){										// highest educations
			if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];
			else $c_id='';

			if ($c_id == ''){
				$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
				$c_id= $cids[0];
			}
			if (is_numeric($c_id) == true) $result =  $model->getFolderbyId($c_id);
                        $folders=$result[0];
                        $lists=$result[1];
			if ( isset($result[0]->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_FOLDER') . ': <small><small>[ ' . $text . ' ]</small></small>');
			JToolBarHelper :: save('savefolder');
        		if ($isNew) JToolBarHelper :: cancel();	else JToolBarHelper :: cancel('cancel', 'Close');
			$this->assignRef('folders', $folders);
			$this->assignRef('lists', $lists);
		}elseif ($layoutName == 'formcompany'){		// companies
			if (isset($_GET['cid'][0]))	$c_id= $_GET['cid'][0];	
			else	$c_id='';	
			
			if ($c_id == ''){
				$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
				$c_id= $cids[0];				
			}
			if (is_numeric($c_id) == true) $result =  $model->getCompanybyId($c_id);	
			$this->assignRef('company', $result[0]);
			$this->assignRef('lists', $result[1]);
			$this->assignRef('userfields', $result[2]);
			$this->assignRef('fieldsordering', $result[3]);
			if(isset($result[4])) $this->assignRef('multiselectedit',$result[4]);
			$this->assignRef('uid', $uid);
			if ( isset($result[0]->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_COMPANY') . ': <small><small>[ ' . $text . ' ]</small></small>');
                        JToolBarHelper :: save();
        		if ($isNew) JToolBarHelper :: cancel();	else JToolBarHelper :: cancel('cancel', 'Close');
		}elseif ($layoutName == 'formjob' ){	// jobs  or form job
			if (isset($_GET['cid'][0]))
				$c_id= $_GET['cid'][0];	
			else
				$c_id='';	
			if ($c_id == ''){
				$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
				$c_id= $cids[0];				
			}
			if (is_numeric($c_id) == true) $result =  $model->getJobbyId($c_id, $uid);	
			$this->assignRef('job', $result[0]);
			$this->assignRef('lists', $result[1]);
			$this->assignRef('userfields', $result[2]);
			$this->assignRef('fieldsordering', $result[3]);
			if(isset($result[4])) $this->assignRef('multiselectedit',$result[4]);
			
			if ( isset($result[0]->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_JOB') . ': <small><small>[ ' . $text . ' ]</small></small>');

                        JToolBarHelper :: save();
        		if ($isNew) JToolBarHelper :: cancel();	else JToolBarHelper :: cancel('cancel', 'Close');
		}elseif ($layoutName == 'formjobalert' ){	// formjobalert
			if (isset($_GET['cid'][0]))
				$c_id= $_GET['cid'][0];	
			else
				$c_id='';	
			if ($c_id == ''){
				$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
				$c_id= $cids[0];				
			}
			if (is_numeric($c_id) == true) $result =  $model->getJobAlertbyIdforForm($c_id);	
			$this->assignRef('jobalert', $result[0]);
			$this->assignRef('lists', $result[1]);
			if(isset($result[2])) $this->assignRef('multiselectedit',$result[2]);
			$text = JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_JOB_ALERT') . ': <small><small>[ ' . $text . ' ]</small></small>');
			JToolBarHelper :: save('savejobalert');
			if ($isNew) JToolBarHelper :: cancel();	else JToolBarHelper :: cancel('cancel', 'Close');
		}elseif ($layoutName == 'formresume'){			//resume            (form resume )
			if (isset($_GET['cid'][0]))
				$c_id= $_GET['cid'][0];	
			else
				$c_id='';	
			if ($c_id == ''){
				$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
				$c_id= $cids[0];				
			}
			if (is_numeric($c_id) == true) $result =  $model->getEmpAppbyId($c_id);	
			$this->assignRef('resume', $result[0]);
			$this->assignRef('userfields', $result[2]);
			
			$this->assignRef('fieldsordering', $result[3]);
			$resumelists =  $model->getEmpOptions();	
			$this->assignRef('resumelists', $resumelists);
			if ( isset($result[0]->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_RESUME') . ': <small><small>[ ' . $text . ' ]</small></small>');
                        JToolBarHelper :: save();
        		if ($isNew) JToolBarHelper :: cancel();	else JToolBarHelper :: cancel('cancel', 'Close');
		}elseif(($cur_layout == 'jobappliedresume') ||($cur_layout =='resume_searchresults') 
                    || ($cur_layout == 'resumeprint') || ($cur_layout == 'view_resume')|| ($cur_layout == 'folder_resumes') || ($cur_layout == 'shortlistcandidates') ){										// view resume
                $resumeid = JRequest::getVar('rd');
				$jobid = JRequest::getVar('oi');
				if(isset($_GET['fd'])) $folderid = $_GET['fd'];
				if (is_numeric($resumeid) == true) $result =  $model->getResumeViewbyId($resumeid);	
				$this->assignRef('resume', $result[0]);
				$this->assignRef('resume2', $result[1]);
				$this->assignRef('resume3', $result[2]);
				$this->assignRef('fieldsordering', $result[3]);
				$this->assignRef('lists', $result[4]);
				$this->assignRef('jobid', $jobid);
				$this->assignRef('fd', $folderid);
				$this->assignRef('resumeid', $resumeid);
                        if($cur_layout == 'jobappliedresume'){
                            JToolBarHelper :: title(JText :: _('JS_JOB_APPLIED_RESUME') );
                        }elseif($cur_layout == 'folder_resumes'){
                            JToolBarHelper :: title(JText :: _('JS_FOLDER_RESUME') );
                        }else JToolBarHelper :: title(JText :: _('JS_VIEW_RESUMES') );
			$isNew = false;
                        if ($cur_layout == 'shortlistcandidates'){
                            if ($isNew) JToolBarHelper :: cancel('cancelshortlistcandidates');
                            else JToolBarHelper :: cancel('cancelshortlistcandidates', 'Close');
                        }else
                             if ($isNew) JToolBarHelper :: cancel();	else JToolBarHelper :: cancel('cancel', 'Close');
		}elseif ($layoutName == 'formsalaryrange'){							// salary range
			if (isset($_GET['cid'][0]))
				$c_id= $_GET['cid'][0];	
			else
				$c_id='';	
			if ($c_id == ''){
				$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
				$c_id= $cids[0];				
			}
			if (is_numeric($c_id) == true AND $c_id!=0) $application =  $model->getSalaryRangebyId($c_id);
			// get configurations
			$config = Array();
			$results =  $model->getConfig();	
			if ($results){ //not empty
				foreach ($results as $result){
					$config[$result->configname] = $result->configvalue;
				}
			}
			$this->assignRef('config', $config);
			
			if ( isset($application->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_SALARY_RANGE') . ': <small><small>[ ' . $text . ' ]</small></small>');
			JToolBarHelper::apply('savejobsalaryrangesave','SAVE');
			if(JVERSION > 2)
				JToolBarHelper :: save2new('savejobsalaryrangeandnew');
			JToolBarHelper :: save('savejobsalaryrange');
        		if ($isNew) JToolBarHelper :: cancel();	else JToolBarHelper :: cancel('cancel', 'Close');
		}elseif ($layoutName == 'formrole'){							// roles
			if (isset($_GET['cid'][0]))	$c_id= $_GET['cid'][0];	
			else $c_id='';	
			
			if ($c_id == ''){
				$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
				$c_id= $cids[0];				
			}
			if (is_numeric($c_id) == true) $result =  $model->getRolebyId($c_id);
			$this->assignRef('role', $result[0]);
			$this->assignRef('lists', $result[1]);
			if ( isset($result[0]->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_ROLE') . ': <small><small>[ ' . $text . ' ]</small></small>');
                        JToolBarHelper :: save();
        		if ($isNew) JToolBarHelper :: cancel();	else JToolBarHelper :: cancel('cancel', 'Close');
		}elseif ($layoutName == 'changerole'){							// users - change role
			if (isset($_GET['cid'][0]))	$c_id= $_GET['cid'][0];	
			else $c_id='';	
			
			if ($c_id == ''){
				$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
				$c_id= $cids[0];				
			}
			if (is_numeric($c_id) == true) $result =  $model->getChangeRolebyId($c_id);
			$this->assignRef('role', $result[0]);
			$this->assignRef('lists', $result[1]);
			JToolBarHelper :: title(JText :: _('JS_CHANGE_ROLE'));
                        JToolBarHelper :: save();
        		if ($isNew) JToolBarHelper :: cancel();	else JToolBarHelper :: cancel('cancel', 'Close');
		}elseif ($layoutName == 'assignpackage'){							// users - change role
                        JToolBarHelper::title(JText::_('JS_ASSIGN_PACKAGE'));
                        JToolBarHelper :: save();
                        JToolBarHelper :: cancel();
                }elseif($layoutName == 'users'){										// users
                    JToolBarHelper :: title(JText::_('JS_USERS'));
                    JToolBarHelper :: editList();
                    $form = 'com_jstickets.users.list.';
                    $searchname	= $mainframe->getUserStateFromRequest( $form.'searchname', 'searchname','', 'string' );
                    $searchusername	= $mainframe->getUserStateFromRequest( $form.'searchusername', 'searchusername','', 'string' );
                    $searchrole	= $mainframe->getUserStateFromRequest( $form.'searchrole', 'searchrole','', 'string' );
                    $result =  $model->getAllUsers($searchname,$searchusername,'','',$searchrole, $limitstart, $limit);
                    $items = $result[0];
                    $total = $result[1];
                    $lists = $result[2];
                    if ( $total <= $limitstart ) $limitstart = 0;
                    $pagination = new JPagination( $total, $limitstart, $limit );
                    $this->assignRef('items', $items);
                    $this->assignRef('lists', $lists);
                    $this->assignRef('pagination', $pagination);
		}elseif ($layoutName == 'formuserfield'){						// user fields
			if (isset($_GET['cid'][0]))	$c_id= $_GET['cid'][0];	
			else $c_id='';	
			if ($c_id == ''){
				$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
				$c_id= $cids[0];				
			}
			if (is_numeric($c_id) == true) $result =  $model->getUserFieldbyId($c_id);

			if (isset($_GET['ff'])) $fieldfor = $_GET['ff']; else $fieldfor = '';
			if ($fieldfor) $_SESSION['ffusr'] = $fieldfor; else $fieldfor = $_SESSION['ffusr'];
			$this->assignRef('userfield', $result[0]);
			$this->assignRef('fieldvalues', $result[1]);
			$this->assignRef('fieldfor', $fieldfor);
			if ( isset($result[0]->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: save('saveuserfield');
			if ($isNew) JToolBarHelper :: cancel();	else JToolBarHelper :: cancel('cancel', 'Close');
		}elseif ($layoutName == 'formcountry'){										// countries
			if (isset($_GET['cid'][0])) $c_id= $_GET['cid'][0]; else $c_id='';
			if ($c_id == ''){
				$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
				$c_id= $cids[0];				
			}
			if (is_numeric($c_id) == true AND $c_id!=0) $country =  $model->getCountrybyId($c_id);	
			if ( isset($country->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_COUNTRY') . ': <small><small>[ ' . $text . ' ]</small></small>');
			$this->assignRef('country', $country);
			JToolBarHelper :: save('savecountry');
        		if ($isNew) JToolBarHelper :: cancel();	else JToolBarHelper :: cancel('cancel', 'Close');
		}elseif ($layoutName == 'formemployerpackage'){										// employer packages
			if (isset($_GET['cid'][0]))	$c_id= $_GET['cid'][0];	
			else	$c_id='';	
			
			if ($c_id == ''){
				$cids = JRequest :: getVar('cid', array (0), 'post', 'array');
				$c_id= $cids[0];				
			}
			if (is_numeric($c_id) == true) $result =  $model->getEmployerPackagebyId($c_id);	
			$paymentmethodlink=  $model->getPaymentMethodLinks($c_id,1);
			if ( isset($result[0]->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_EMPLOYER_PACKAGE') . ': <small><small>[ ' . $text . ' ]</small></small>');
			$this->assignRef('package', $result[0]);
			$this->assignRef('lists', $result[1]);
			$this->assignRef('paymentmethodlink', $paymentmethodlink);
			JToolBarHelper :: save();
			if ($isNew) JToolBarHelper :: cancel();	else JToolBarHelper :: cancel('cancel', 'Close');
		}elseif ($layoutName == 'formjobseekerpackage'){										// job seeker
			if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];	else $c_id='';	
			if ($c_id == ''){ $cids = JRequest :: getVar('cid', array (0), 'post', 'array'); $c_id= $cids[0]; }
			if (is_numeric($c_id) == true) $result =  $model->getJobSeekerPackagebyId($c_id);	
			$paymentmethodlink=  $model->getPaymentMethodLinks($c_id,2);
			if ( isset($result[0]->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_JOBSEEKER_PACKAGE') . ': <small><small>[ ' . $text . ' ]</small></small>');
			$this->assignRef('package', $result[0]);
			$this->assignRef('lists', $result[1]);
			$this->assignRef('paymentconfigs', $result[2]);
			$this->assignRef('paymentmethodlink', $paymentmethodlink);
                        JToolBarHelper :: save();
        		if ($isNew) JToolBarHelper :: cancel();	else JToolBarHelper :: cancel('cancel', 'Close');
			
		}elseif ( $layoutName == 'formgoldresume'){										
			$resumeid=JRequest :: getVar('rd');
			if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];	else $c_id='';	
			if ($c_id == ''){ $cids = JRequest :: getVar('cid', array (0), 'post', 'array'); $c_id= $cids[0]; }
                        if (!$resumeid) $resumeid = $c_id;
			if (is_numeric($resumeid) == true) $result =  $model->getGoldResumeById($resumeid,$c_id);
			if ( isset($result[0]->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_GOLD_RESUME') . ': <small><small>[ ' . $text . ' ]</small></small>');
			$this->assignRef('goldresume', $result[0]);
			$this->assignRef('lists', $result[1]);
			$this->assignRef('application_title', $result[2]);
			$this->assignRef('resumeid',$resumeid);
                        JToolBarHelper :: save();
        		if ($isNew) JToolBarHelper :: cancel();	else JToolBarHelper :: cancel('cancel', 'Close');
		}elseif( $layoutName == 'formfeaturedresume'){	
			$resumeid=JRequest :: getVar('rd');
			if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];	else $c_id='';	
			if ($c_id == ''){ $cids = JRequest :: getVar('cid', array (0), 'post', 'array'); $c_id= $cids[0]; }
                        if (!$resumeid) $resumeid = $c_id;
			if (is_numeric($resumeid) == true) $result =  $model->getFeaturedResumeById($resumeid,$c_id);
			if ( isset($result[0]->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_FEATURED_RESUME') . ': <small><small>[ ' . $text . ' ]</small></small>');
			$this->assignRef('featuredresume', $result[0]);
			$this->assignRef('lists', $result[1]);
			$this->assignRef('application_title', $result[2]);
			$this->assignRef('resumeid',$resumeid);
                        JToolBarHelper :: save();
        		if ($isNew) JToolBarHelper :: cancel();	else JToolBarHelper :: cancel('cancel', 'Close');
		}elseif ($layoutName == 'formfeaturedjob'){										
			$jobid=JRequest :: getVar('oi');
			if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];	else $c_id='';	
			if ($c_id == ''){ $cids = JRequest :: getVar('cid', array (0), 'post', 'array'); $c_id= $cids[0]; }
                        if (!$jobid) $jobid = $c_id;
			if (is_numeric($jobid) == true) $result =  $model->getFeaturedJobId($jobid,$c_id);
			if ( isset($result[0]->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_FEATURED_JOB') . ': <small><small>[ ' . $text . ' ]</small></small>');
			$this->assignRef('featuredjob', $result[0]);
			$this->assignRef('lists', $result[1]);
			$this->assignRef('jobtitle', $result[2]);
			$this->assignRef('jobid',$jobid);
                        JToolBarHelper :: save();
        		if ($isNew) JToolBarHelper :: cancel();	else JToolBarHelper :: cancel('cancel', 'Close');
		}elseif ($layoutName == 'formdepartment' ){										
			if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];	else $c_id='';	
			if ($c_id == ''){ $cids = JRequest :: getVar('cid', array (0), 'post', 'array'); $c_id= $cids[0]; }
			if (is_numeric($c_id) == true) $result =  $model->getDepartmentById($c_id,$uid);	
			if ( isset($result[0]->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_DEPARTMENT') . ': <small><small>[ ' . $text . ' ]</small></small>');
			$this->assignRef('department', $result[0]);
			$this->assignRef('lists', $result[1]);
			$this->assignRef('uid', $uid);
                        JToolBarHelper :: save();
        		if ($isNew) JToolBarHelper :: cancel();	else JToolBarHelper :: cancel('cancel', 'Close');
		}elseif( $layoutName == 'formgoldjob'  ){		
			$jobid=JRequest :: getVar('oi');
			if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];	else $c_id='';	
			if ($c_id == ''){ $cids = JRequest :: getVar('cid', array (0), 'post', 'array'); $c_id= $cids[0]; }
                        if (!$jobid) $jobid = $c_id;
			if (is_numeric($jobid) == true) $result =  $model->getGoldJobId($jobid,$c_id);
			if ( isset($result[0]->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_GOLD_JOB') . ': <small><small>[ ' . $text . ' ]</small></small>');
			$this->assignRef('goldjob', $result[0]);
			$this->assignRef('lists', $result[1]);
			$this->assignRef('jobtitle', $result[2]);
			$this->assignRef('jobid',$jobid);
                        JToolBarHelper :: save();
        		if ($isNew) JToolBarHelper :: cancel();	else JToolBarHelper :: cancel('cancel', 'Close');
		}elseif ($layoutName == 'formgoldcompany'){										
			$companyid=JRequest :: getVar('md');
			if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];	else $c_id='';	
			if ($c_id == ''){ $cids = JRequest :: getVar('cid', array (0), 'post', 'array'); $c_id= $cids[0]; }
                        if (!$companyid) $companyid = $c_id;
			if (is_numeric($companyid) == true) $result =  $model->getGoldCompanyId($companyid,$c_id);
			//print_r($result);
			if ( isset($result[0]->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_GOLD_COMPANY') . ': <small><small>[ ' . $text . ' ]</small></small>');
			$this->assignRef('goldcompany', $result[0]);
			$this->assignRef('lists', $result[1]);
			$this->assignRef('companyname',$result[2]);
			$this->assignRef('companyid',$companyid);
                        JToolBarHelper :: save();
        		if ($isNew) JToolBarHelper :: cancel();	else JToolBarHelper :: cancel('cancel', 'Close');
		}elseif ($layoutName == 'formfeaturedcompany'){			
			$companyid=JRequest :: getVar('md');
			if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];	else $c_id='';	
			if ($c_id == ''){ $cids = JRequest :: getVar('cid', array (0), 'post', 'array'); $c_id= $cids[0]; }
                        if (!$companyid) $companyid = $c_id;
			if (is_numeric($companyid) == true) $result =  $model->getFeaturedCompanyId($companyid,$c_id);
			if ( isset($result[0]->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_FEATURED_COMPANY') . ': <small><small>[ ' . $text . ' ]</small></small>');
			$this->assignRef('featuredcompany', $result[0]);
			$this->assignRef('lists', $result[1]);
			$this->assignRef('companyname',$result[2]);
			$this->assignRef('companyid',$companyid);
                        JToolBarHelper :: save();
        		if ($isNew) JToolBarHelper :: cancel();	else JToolBarHelper :: cancel('cancel', 'Close');
			
		}elseif ($layoutName == 'formstate'){										// states
			if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];	else $c_id='';	
			if ($c_id == ''){ $cids = JRequest :: getVar('cid', array (0), 'post', 'array'); $c_id= $cids[0]; }
			//if (isset($_SESSION['js_countrycode'])) $countrycode = $_SESSION['js_countrycode']; else $countrycode=null;
			if (is_numeric($c_id) == true) $state =  $model->getStatebyId($c_id);	
			if ( isset($state->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_STATE') . ': <small><small>[ ' . $text . ' ]</small></small>');
			$this->assignRef('state', $state);
			
			JToolBarHelper :: save('savestate');
        		if ($isNew) JToolBarHelper :: cancel();	else JToolBarHelper :: cancel('cancel', 'Close');
		}elseif ($layoutName == 'formcounty'){										// counties
			if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];	else $c_id='';	
			if ($c_id == ''){ $cids = JRequest :: getVar('cid', array (0), 'post', 'array'); $c_id= $cids[0]; }
			if (isset($_SESSION['js_countrycode'])) $countrycode = $_SESSION['js_countrycode']; else $countrycode=null;
			if (isset($_SESSION['js_statecode'])) $statecode = $_SESSION['js_statecode']; else $statecode=null;
			if (is_numeric($c_id) == true) $county =  $model->getCountybyId($c_id);	
			if ( isset($county->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_COUNTY') . ': <small><small>[ ' . $text . ' ]</small></small>');
			$this->assignRef('county', $county);
			$this->assignRef('countrycode', $countrycode);
			$this->assignRef('statecode', $statecode);
                        JToolBarHelper :: save();
        		if ($isNew) JToolBarHelper :: cancel();	else JToolBarHelper :: cancel('cancel', 'Close');
		}elseif ($layoutName == 'formcity'){										// cities
			if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];	else $c_id='';	
			if ($c_id == ''){ $cids = JRequest :: getVar('cid', array (0), 'post', 'array'); $c_id= $cids[0]; }
			if (isset($_SESSION['js_countrycode'])) $countrycode = $_SESSION['js_countrycode']; else $countrycode=null;
			if (isset($_SESSION['js_countryid'])) $countryid = $_SESSION['js_countryid']; else $countryid=null;
			if (isset($_SESSION['js_statecode'])) $statecode = $_SESSION['js_statecode']; else $statecode=null;
			if (isset($_SESSION['js_stateid'])) $stateid = $_SESSION['js_stateid']; else $stateid=null;
			//if (isset($_SESSION['js_countycode'])) $countycode = $_SESSION['js_countycode']; else $countycode=null;
			if (is_numeric($c_id) == true) $city =  $model->getCitybyId($c_id);	
			if ( isset($city->id) ) $isNew = false;
			$text = $isNew ? JText :: _('ADD') : JText :: _('EDIT');
			JToolBarHelper :: title(JText :: _('JS_CITY') . ': <small><small>[ ' . $text . ' ]</small></small>');
			$this->assignRef('city', $city);
			$this->assignRef('countrycode', $countrycode);
			$this->assignRef('countryid', $countryid);
			$this->assignRef('statecode', $statecode);
			$this->assignRef('stateid', $stateid);
                        JToolBarHelper :: save();
        		if ($isNew) JToolBarHelper :: cancel();	else JToolBarHelper :: cancel('cancel', 'Close');
		}
			
		if ($cur_layout == 'info')
			JToolBarHelper :: title(JText :: _('JS Jobs') );
		elseif ($cur_layout == 'userfields')
			JToolBarHelper :: title(JText :: _('JS_USER_FIELD') . ': <small><small>[ ' . $text . ' ]</small></small>');

		$this->assignRef('config', $config);
		$this->assignRef('application', $application);
		$this->assignRef('theme', $theme);
		$this->assignRef('option', $option);
		$this->assignRef('uid', $uid);
		$this->assignRef('msg', $msg);
		$this->assignRef('isjobsharing', $_client_auth_key);
		
		parent :: display($tpl);
	}

}
?>
