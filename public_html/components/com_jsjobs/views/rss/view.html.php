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
		$document = &JFactory::getDocument();
		$common_model = $this->getModel('Common', 'JSJobsModel');
		//$cur_layout = $_SESSION['cur_layout'];
		$cur_layout = JRequest::getVar('layout','default');
		$user	=& JFactory::getUser();
		$uid=$user->id;
		
		if (isset($_SESSION['jsjobconfig_dft'])) $config = $_SESSION['jsjobconfig_dft']; else $config = null;
		$type='';		
		$config = Array();
		if (sizeof($config) == 0){
			$results =  $common_model->getConfig('');
			if (isset($results)){ //not empty
				foreach ($results as $result){
					$config[$result->configname] = $result->configvalue;
				}
				/*
				$result =  $model->getTypeStatus();	
				$type .= $result[0];
				$value = $result[1];
				$config[$type] = $value;
				$_SESSION['jsjobconfig_dft'] = $config;
				*/
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
		if($cur_layout == 'default'){									
				$document->setTitle(JText::_('JS_SUBSCRIBE_FOR_FEEDS'));
				/*
				//$resumeid = $_GET['rd'];
				$resumeid =  JRequest::getVar('rd','');
                $jobid = JRequest::getVar('bd','');
                $myresume = JRequest::getVar('ms','');
				if (is_numeric($resumeid) == true) 
				$result =  $model->getResumeViewbyId($uid, $jobid, $resumeid,$myresume);	
				sleep(10);
				$this->assignRef('resume', $result[0]);
				$this->assignRef('resume2', $result[1]);
				$this->assignRef('resume3', $result[2]);
				$this->assignRef('fieldsordering', $result[3]);
				*/
		}
		
		$this->assignRef('config', $config);
		$this->assignRef('theme', $theme);
		parent :: display();
		 }
}

?>
