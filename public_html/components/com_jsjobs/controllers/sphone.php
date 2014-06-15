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
 * File Name:	controllers/jsjobs.php
 ^ 
 * Description: Controller class for application data
 ^ 
 * History:		NONE
 ^ 
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class JSJobsControllerSphone extends JControllerLegacy{

	function __construct(){
		$user	=& JFactory::getUser();
		if ($user->guest) { // redirect user if not login
			$link = 'index.php?option=com_user';
			$this->setRedirect($link);
		} 
		parent :: __construct();
	}
	/********************************************************for smart phone*****************************/

	function getfeaturedjobs(){
		$secondarykey =  $_SERVER['HTTP_KEY'];
		if(!$this->checksecondarykey($secondarykey)){ echo 'keynotmatched'; exit; }
		
		$limitstart = JRequest::getVar('limitstart');
		$limit = JRequest::getVar('limit');
		$model = $this->getModel('sphone', 'JSJobsModel');
		$return = $model->getFeaturedJobs($limitstart,$limit);
		print(json_encode($return));
		exit;
	}
	function getgoldjobs(){
		$secondarykey =  $_SERVER['HTTP_KEY'];
		if(!$this->checksecondarykey($secondarykey)){ echo 'keynotmatched'; exit; }
		$limitstart = JRequest::getVar('limitstart');
		$limit = JRequest::getVar('limit');
		$model = $this->getModel('sphone', 'JSJobsModel');
		$return = $model->getGoldJobs($limitstart,$limit);
		print(json_encode($return));
		exit;
	}
	function getlatestjobs(){
		$secondarykey =  $_SERVER['HTTP_KEY'];
		if(!$this->checksecondarykey($secondarykey)){ echo 'keynotmatched'; exit; }
		$limitstart = JRequest::getVar('limitstart');
		$limit = JRequest::getVar('limit');
		$model = $this->getModel('sphone', 'JSJobsModel');
		$return = $model->getLatestJobs($limitstart,$limit);
		print(json_encode($return));
		exit;
	}
	function gettopjobs(){
		$secondarykey =  $_SERVER['HTTP_KEY'];
		if(!$this->checksecondarykey($secondarykey)){ echo 'keynotmatched'; exit; }
		$limitstart = JRequest::getVar('limitstart');
		$limit = JRequest::getVar('limit');
		//$model = $this->getModel('mpjsjobs', 'JSJobsModel');
		$model = $this->getModel('sphone', 'JSJobsModel');
		$return = $model->getTopJobs($limitstart,$limit);
		print(json_encode($return));
		exit;
	}
	function getjobbyid(){
		$secondarykey =  $_SERVER['HTTP_KEY'];
		if(!$this->checksecondarykey($secondarykey)){ echo 'keynotmatched'; exit; }
		$jobid = JRequest::getVar('id');
		$model = $this->getModel('sphone', 'JSJobsModel');
		$return = $model->getJobbyId($jobid);
		print(json_encode($return));
		exit;
	}
	function getuser(){
		$secondarykey =  $_SERVER['HTTP_KEY'];
		if(!$this->checksecondarykey($secondarykey)){ echo 'keynotmatched'; exit; }
		$user	=& JFactory::getUser();
		$uid=$user->id;
		echo $uid;
		exit;
	}
	function login(){	 //loginrequest
		$secondarykey =  $_SERVER['HTTP_KEY'];
		if(!$this->checksecondarykey($secondarykey)){ echo 'keynotmatched'; exit; }
		$username =  $_SERVER['HTTP_USERNAME'];
		$password =  $_SERVER['HTTP_PASSWORD'];
		$app = JFactory::getApplication();

		$credentials = array();
		$credentials['username'] = $username;
		$credentials['password'] = $password;
		// Perform the log in.
		$error = $app->login($credentials);
		$user	=& JFactory::getUser();
		$result = array();
		// Check if the log in succeeded.
		if (!$user->guest) { 
			
			$model = $this->getModel('jsjobs', 'JSJobsModel');
			$userrole = $model->getUserRole($user->id);		
			$session = JFactory::getSession();
			$session->set('userrole', $userrole->rolefor);
			$userrole = $session->get('userrole');
			$result = array('login'=>1 ,'userid'=> $user->id,'userrole'=>$userrole); 
		}
		else{ 	$result = array('login'=>0 ,'userid'=> 0,'userrole'=>0);  }
		echo (json_encode($result));
		exit;
	}
	function getmyresumes(){ // ???      getMyResumesForEmployeeJob 
		$secondarykey =  $_SERVER['HTTP_KEY'];
		if(!$this->checksecondarykey($secondarykey)){ echo 'keynotmatched'; exit; }
		$user	=& JFactory::getUser();
		$uid = $user->id;
		$jobid =  $_SERVER['HTTP_JOBID'];
		$model = $this->getModel('sphone', 'JSJobsModel');
		$resumes = $model->getMyResumes($uid,$jobid);
		//$jobdetail = $model->getJobDetailById($jobid);
		$jobdetail = $model->getJobbyId($jobid);
		$result = array('login'=>$uid ,$resumes,$jobdetail); 
		print(json_encode($result));
		exit;
	}
	function jobapply(){  				//  jobapplyforandroiduser 
		$secondarykey =  $_SERVER['HTTP_KEY'];
		if(!$this->checksecondarykey($secondarykey)){ echo 'keynotmatched'; exit; }
		$user	=& JFactory::getUser();
		$uid = $user->id;
		$jobid =  $_SERVER['HTTP_JOBID'];
		$resumeid =  $_SERVER['HTTP_RESUMEID'];
		$model = $this->getModel('sphone', 'JSJobsModel');
		$return_value = $model->jobApply($uid,$jobid,$resumeid);  
		if ($return_value == 1)	{ $msg = JText :: _('APPLICATION_APPLIED'); }
		elseif ($return_value == 3){ 	$msg = JText :: _('JS_ALREADY_APPLY_JOB'); 	}
		else{ 	$msg = JText :: _('ERROR_APPLING_APPLICATION'); }
		print(json_encode($msg));
		exit;
	}
	function formjobdata() { 
		$secondarykey =  $_SERVER['HTTP_KEY'];
		if(!$this->checksecondarykey($secondarykey)){ echo 'keynotmatched'; exit; }
		$user	=& JFactory::getUser();
		$uid = $user->id;
		$jobid = JRequest::getVar('jobid');
		$model = $this->getModel('jsjobs', 'JSJobsModel');
		if (!isset($jobid) ){ $canaddreturn = $model->canAddNewJob($uid); } // here jsjobs method is directly called .
		$model = $this->getModel('sphone', 'JSJobsModel');
		$jobdata =  $model->getJobforForm($uid,$jobid);	
		if(isset($jobid)){
		
			$result = array('companies'=>$jobdata[0],'departments'=>$jobdata[1],'categories'=>$jobdata[2],'subcategories'=>$jobdata[3] 
			
			,'jobtype'=>$jobdata[4],'jobstatus'=>$jobdata[5],'gender'=>$jobdata[6],'ageto'=>$jobdata[7]
			
			,'currency'=>$jobdata[8],'salaryrangeto'=>$jobdata[9],'salarytype'=>$jobdata[10]
			
			,'shift'=>$jobdata[11],'educationminmax'=>$jobdata[12],'highesteducation'=>$jobdata[13],'experience'=>$jobdata[15],'careerlevel'=>$jobdata[16],'workpermit'=>$jobdata[17],'requiredtravel'=>$jobdata[18]
			
			,'countries'=>$jobdata[19]
			,'editjob'=>$jobdata[20]);
		}else{
		
			$result = array('companies'=>$jobdata[0],'categories'=>$jobdata[2],'subcategories'=>$jobdata[3] 
			
			,'jobtype'=>$jobdata[4],'jobstatus'=>$jobdata[5],'gender'=>$jobdata[6],'ageto'=>$jobdata[7]
			
			,'currency'=>$jobdata[8],'salaryrangeto'=>$jobdata[9],'salarytype'=>$jobdata[10]
			
			,'shift'=>$jobdata[11],'educationminmax'=>$jobdata[12],'highesteducation'=>$jobdata[13],'experience'=>$jobdata[15],'careerlevel'=>$jobdata[16],'workpermit'=>$jobdata[17],'requiredtravel'=>$jobdata[18]
			
			,'countries'=>$jobdata[19]
			,'usercanaddjob'=>$canaddreturn[0]
			,'packagedetail'=>$canaddreturn[1]);
		}
		print(json_encode($result));
		exit;
	}
	function getdepartmentsbycompanyid(){  // getdepartmentbycompanyid
		$secondarykey =  $_SERVER['HTTP_KEY'];
		if(!$this->checksecondarykey($secondarykey)){ echo 'keynotmatched'; exit; }
		$user	=& JFactory::getUser();
		$uid = $user->id;
		$companyid =  $_SERVER['HTTP_COMPANYID'];
		$model = $this->getModel('sphone', 'JSJobsModel');
		$departments =  $model->getDeptByCompanyId($uid,$companyid);	
		print(json_encode($departments));
		exit;
	}
	function getsubcategoriesbycategoryid(){ 
		$secondarykey =  $_SERVER['HTTP_KEY'];
		if(!$this->checksecondarykey($secondarykey)){ echo 'keynotmatched'; exit; }
		$categoryid =  $_SERVER['HTTP_CATEGORYID'];
		$model = $this->getModel('sphone', 'JSJobsModel');
		$departments =  $model->getSubcategoriesByCategoryId($categoryid);	
		print(json_encode($departments));
		exit;
	}
	function savejob(){  //saveandroidjob
		$secondarykey =  $_SERVER['HTTP_KEY'];
		if(!$this->checksecondarykey($secondarykey)){ echo 'keynotmatched'; exit; }
		$user	=& JFactory::getUser();
		
		$data = array();
		$data['title'] =  $_SERVER['HTTP_JOBTITLE'];
		$data['companyid'] =  $_SERVER['HTTP_COMPANYID'];
		$data['departmentid'] =  $_SERVER['HTTP_DEPARTMENTID'];
		$data['jobcategory'] =  $_SERVER['HTTP_CATEGORYID'];
		$data['subcategoryid'] =  $_SERVER['HTTP_SUBCATEGORYID'];
		$data['jobtype'] =  $_SERVER['HTTP_JOBTYPEID'];
		$data['shift'] =  $_SERVER['HTTP_JOBSHIFTID'];
		$data['salaryrangefrom'] =  $_SERVER['HTTP_SALARYRANGEFROMID'];
		$data['salaryrangeto'] =  $_SERVER['HTTP_SALARYRANGETOID'];
		$data['currencyid'] =  $_SERVER['HTTP_CURRENCYTYPEID']; //CURRENCY SYMBOL
		$data['salaryrangetype'] =  $_SERVER['HTTP_SALARYRANGETYPEID']; //PER WEEK OR MONTH
		$data['mineducationrange'] =  $_SERVER['HTTP_EDUCATIONMINID']; //MINIMUM  
		$data['maxeducationrange'] =  $_SERVER['HTTP_EDUCATIONMAXID']; //MINIMUM  
		$data['degreetitle'] =  $_SERVER['HTTP_DEGREETITLE'];
		$data['noofjobs'] =  $_SERVER['HTTP_NOOFJOBS'];
		$data['minexperiencerange'] =  $_SERVER['HTTP_EXPERIENCEMINID'];
		$data['maxexperiencerange'] =  $_SERVER['HTTP_EXPERIENCEMAXID'];
		$data['startpublishing'] =  $_SERVER['HTTP_STARTPUBLISH'];
		$data['stoppublishing'] =  $_SERVER['HTTP_STOPPUBLISH'];
		$data['agefrom'] =  $_SERVER['HTTP_AGEFROMID'];
		$data['ageto'] =  $_SERVER['HTTP_AGETOID'];
		$data['gender'] =  $_SERVER['HTTP_GENDERID'];
		$data['careerlevel'] =  $_SERVER['HTTP_CAREERLEVELID'];
		$data['workpermit'] =  $_SERVER['HTTP_WORKPERMITCODEID'];
		$data['requiredtravel'] =  $_SERVER['HTTP_REQUIREDTRAVELID'];
		$data['description'] =  $_SERVER['HTTP_DESCRIPTION'];
		$data['qualifications'] =  $_SERVER['HTTP_QUALIFICATION'];
		$data['prefferdskills'] =  $_SERVER['HTTP_PREFERREDSKILL'];
		$data['country'] =  $_SERVER['HTTP_COUNTRYCODEID']; //save country code .
		$data['state'] =  $_SERVER['HTTP_STATE'];
		$data['city'] =  $_SERVER['HTTP_CITY'];
		$data['uid'] =  $user->id;
		$data['duration'] = $_SERVER['HTTP_DURATION'];
		$data['created'] = date('Y-m-d H:i:s');
		$data['id'] = JRequest::getVar('jobid');
		if(!isset($data['id'])){
			$data['packageid'] = $_SERVER['HTTP_PACKAGEID'];
			$data['paymenthistoryid'] = $_SERVER['HTTP_PAYMENTHISTORYID'];
			$data['enforcestoppublishjob'] = $_SERVER['HTTP_ENFORCESTOPPUBLISHJOB'];
			$data['enforcestoppublishjobvalue'] = $_SERVER['HTTP_ENFORCESTOPPUBLISHJOBVALUE'];
			$data['enforcestoppublishjobtype'] = $_SERVER['HTTP_ENFORCESTOPPUBLISHJOBTYPE'];
			$data['userfields_total'] = 0;
		}
		$model = $this->getModel('sphone', 'JSJobsModel');
		$return_value =  $model->storeJob($data);	
		echo $return_value;
		exit;
	}
	function getmyjobs(){  //getallandroidjobs
		$secondarykey =  $_SERVER['HTTP_KEY'];
		if(!$this->checksecondarykey($secondarykey)){ echo 'keynotmatched'; exit; }
		$user	=& JFactory::getUser();
		$limitstart = JRequest::getVar('limitstart');
		$limit = JRequest::getVar('limit');
		$uid = $user->id;
		$model = $this->getModel('sphone', 'JSJobsModel');
		$record = $model->getMyJobs($uid,$limitstart,$limit);
		print(json_encode($record));
		exit;
	}
	function getappliedresumebyuid() { //androidappliedresume
		$secondarykey =  $_SERVER['HTTP_KEY'];
		if(!$this->checksecondarykey($secondarykey)){ echo 'keynotmatched'; exit; }
		$limitstart = JRequest::getVar('limitstart');
		$limit = JRequest::getVar('limit');
		$user	=& JFactory::getUser();
		$uid = $user->id;
		$model = $this->getModel('sphone', 'JSJobsModel');
		$result = $model->getAllAppliedResumeByUid($uid,$limit,$limitstart);
		print(json_encode($result));
		exit;
	}
	function getalljobappliedresume(){ //androidalljobappliedresume
		$secondarykey =  $_SERVER['HTTP_KEY'];
		if(!$this->checksecondarykey($secondarykey)){ echo 'keynotmatched'; exit; }
		$limitstart = JRequest::getVar('limitstart');
		$limit = JRequest::getVar('limit');
		$user	=& JFactory::getUser(); 
		$uid = $user->id;
		$jobid =  JRequest::getVar('jobid','');	
		$model = $this->getModel('sphone', 'JSJobsModel');
		$result = $model->getAllJobAppliedresume($uid,$jobid,$limit,$limitstart);
		print(json_encode($result));
		exit;
	}
	function getresumeviewbyid(){ //getandroidresumedetailbyidandjobid
		$secondarykey =  $_SERVER['HTTP_KEY'];
		if(!$this->checksecondarykey($secondarykey)){ echo 'keynotmatched'; exit; }
		$user	=& JFactory::getUser();
		$uid=$user->id;
		$jobid=JRequest::getVar( 'jobid');
		$resumeid=JRequest::getVar( 'resumeid');
		$myresume = 2;
		$model = $this->getModel('sphone', 'JSJobsModel');
		$result =  $model->getResumeViewbyId($uid, $jobid, $resumeid,$myresume);
		print(json_encode($result));
		exit;
		}
	public function logout(){ //androidlogout
		$secondarykey =  $_SERVER['HTTP_KEY'];
		if(!$this->checksecondarykey($secondarykey)){ echo 'keynotmatched'; exit; }
		$user	=& JFactory::getUser();
		$uid=$user->id;
		if(isset($uid)){
			$app = JFactory::getApplication();
			$error = $app->logout();
			if (!JError::isError($error)) 	echo 'You are successfully logout'; // frpm language file
			else echo 'You are not logout';
		}else{ 
			echo 'you are not login';
		}
		exit;
	}
	function checkuserrole(){
		$secondarykey =  $_SERVER['HTTP_KEY'];
		if(!$this->checksecondarykey($secondarykey)){ echo 'keynotmatched'; exit; }
		$user=& JFactory::getUser();
		$uid=$user->id;
		if (!$user->guest) { 
			$model = $this->getModel('jsjobs', 'JSJobsModel');
			$userrole = $model->getUserRole($uid);
			echo $userrole->role;
		}
		else{
			echo 0;
		}
		exit;
	}
	function getsecondarykey(){
		$primarykey =  $_SERVER['HTTP_KEY'];
		$model = $this->getModel('sphone', 'JSJobsModel');
		$result =  $model->getSecondaryKey($primarykey);
		if($result) echo $result;
		else echo 'false';
		exit;

	}	
	function checksecondarykey($secondarykey){
		$primarykey =  $_SERVER['HTTP_KEY'];
		$model = $this->getModel('sphone', 'JSJobsModel');
		$result =  $model->checkSecondaryKey($secondarykey);
		return $result;
		exit;

	}	
	/********************************************************smart phone code end *****************************/
	
	

}
?>
