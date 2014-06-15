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

class JSJobsViewRss extends JViewLegacy
{
   /**
    * Displays a generic page
    * (for when there are no actions or selected registers)
    *
    * @param string $template  Optional name of the template to use
    */
 function display( $tpl = NULL )
   {
		global $_client_auth_key;
		$mainframe = &JFactory::getApplication();
		$document = &JFactory::getDocument();
		$common_model = $this->getModel('Common', 'JSJobsModel');
		$employer_model = $this->getModel('Employer', 'JSJobsModel');
		$jobseeker_model = $this->getModel('Jobseeker', 'JSJobsModel');
		//$cur_layout = $_SESSION['cur_layout'];
		if($_client_auth_key=="") {
			$auth_key=$common_model->getClientAuthenticationKey();
			$_client_auth_key=$auth_key;
		}
		$router = $mainframe->getRouter();		
		if($router->getMode() == JROUTER_MODE_SEF) {
			$router_mode_sef = 1; // sef true
		}else{
			$router_mode_sef = 2; // sef false
		}				
		$cur_layout = JRequest::getVar('layout','default');
		$user	=& JFactory::getUser();
		$uid=$user->id;
		if (isset($_SESSION['jsjobconfig_dft'])) $config = $_SESSION['jsjobconfig_dft']; else $config = null;
		$type='';		
		$config = Array();
		if (sizeof($config) == 0){
			$config =  $common_model->getConfigByFor('rss');
		}
		if($cur_layout == 'rssjobs'){
                    $document->setTitle(JText::_('JS_SUBSCRIBE_FOR_JOBS_FEEDS'));
                    $result = $jobseeker_model->getRssJobs($uid);
                    $this->assignRef('result', $result);
		}elseif($cur_layout == 'rssresumes'){
                    $document->setTitle(JText::_('JS_SUBSCRIBE_FOR_RESUMES_FEEDS'));
                    $result = $employer_model->getRssResumes();
                    $this->assignRef('result', $result);
                }
		
		$this->assignRef('config', $config);
		$this->assignRef('isjobsharing', $_client_auth_key);
		
		parent :: display();
                $mainframe->close();
                //die();
		 }
}

?>
