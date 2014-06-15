<?php
/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 + Created by:          Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jan 11, 2010
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

class JSJobsViewOutput extends JViewLegacy
{
   /**
    * Displays a generic page
    * (for when there are no actions or selected registers)
    *
    * @param string $template  Optional name of the template to use
    */
 function display( $tpl = NULL )
   {
		global $mainframe,$_client_auth_key;
		$common_model = $this->getModel('Common', 'JSJobsModel');
		$employer_model = $this->getModel('Employer', 'JSJobsModel');
		$jobseeker_model = $this->getModel('Jobseeker', 'JSJobsModel');

		$mainframe = &JFactory::getApplication();
		$router = $mainframe->getRouter();		
		if($router->getMode() == JROUTER_MODE_SEF) {
			$router_mode_sef = 1; // sef true
		}else{
			$router_mode_sef = 2; // sef false
		}				
	   
  		 $model		= &$this->getModel();
		//$cur_layout = $_SESSION['cur_layout'];
		$cur_layout = 'resumepdf';
		$user	=& JFactory::getUser();
		$uid=$user->id;
		if($_client_auth_key=="") {
			$auth_key=$common_model->getClientAuthenticationKey();
			$_client_auth_key=$auth_key;
		}
		
		if (isset($_SESSION['jsjobconfig_dft'])) $config = $_SESSION['jsjobconfig_dft']; else $config = null;
		$type='';		
		$config = Array();
		if (sizeof($config) == 0){
			$results =  $common_model->getConfig('');
			if (isset($results)){ //not empty
				foreach ($results as $result){
					$config[$result->configname] = $result->configvalue;
				}
				$result =  $common_model->getTypeStatus();	
				$type .= $result[0];
				$value = $result[1];
				$config[$type] = $value;
				$_SESSION['jsjobconfig_dft'] = $config;
			}
		}
		if($cur_layout == 'resumepdf'){									
				//$resumeid = $_GET['rd'];
				if($router_mode_sef==2){
					$resumeid =$common_model->parseId(JRequest::getVar('rd',''));
				}else{
					$resumeid =  JRequest::getVar('rd','');
				}
                $jobid = JRequest::getVar('bd','');
                $myresume = JRequest::getVar('ms','');
				if (is_numeric($resumeid) == true) 
				$result =  $jobseeker_model->getResumeViewbyId($uid, $jobid, $resumeid,$myresume);	
				sleep(10);
				$this->assignRef('resume', $result[0]);
				$this->assignRef('resume2', $result[1]);
				$this->assignRef('resume3', $result[2]);
				$this->assignRef('ms', $myresume);
				$this->assignRef('fieldsordering', $result[3]);
				$this->assignRef('isjobsharing', $_client_auth_key);
				
		}
		
		$document = &JFactory::getDocument();
				$document->setTitle($result[0]->first_name.' '.$result[0]->last_name.' '. JText::_('JS_RESUME'));
				$this->assignRef('config', $config);
		parent :: display();
		 }
}

?>
