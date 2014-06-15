<?php
/**
 + Created by:	Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , info@burujsolutions.com
				www.joomsky.com, ahmad@joomsky.com
 * Created on:	Dec 19, 2010
 ^
 + Project: 		JS Jobs 
 * File Name:	Pplugin/jsjobregister.php
 ^ 
 * Description: Plugin for JS Jobs
 ^ 
 * History:		NONE
 ^ 
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
if(!defined('DS')){
   define('DS',DIRECTORY_SEPARATOR);
}

class plgSystemJSJobsRegister extends JPlugin
{
		function onUserBeforeSave($user,$isnew){
			//echo'<pre>';print_r($user);echo '</pre>';
			if( $isnew )
			{
				if(isset($_POST['userrole'])){
					$mainframe = &JFactory::getApplication();
					$componentPath = JPATH_SITE.'/components/com_jsjobs';
					require_once $componentPath.'/models/common.php';
					$common_model = new JSJobsModelCommon();
					$db = &JFactory::getDBO();
					$query = "SELECT  configvalue FROM  #__js_job_config where configname='user_registration_captcha'" ;
					$db->setQuery( $query );
					$user_registration_captcha=$db->loadObject();
					if($user_registration_captcha->configvalue==1){
						   if(!$common_model->performChecks()){
								$msg = JText :: _('ERROR INCORRECT CAPTCHA CODE');
								$link = "index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=userregister&userrole=".$_POST['userrole'];
								$mainframe->redirect(JRoute::_($link), $msg);
						   }
					}
				   return true;
				}
			}
		}
		function onAfterStoreUser($user, $isnew, $success, $msg){ //j 1.5
			if( $isnew )
			{
				if(isset($_POST['userrole'])){
					$db = &JFactory::getDBO();
					$created = date('Y-m-d H:i:s');
					$query = "INSERT INTO #__js_job_userroles (uid,role,dated) VALUES (".$user['id'].", ".$user['userrole'].", '".$created."')";
					$db->setQuery( $query );
					$db->query();

					$componentAdminPath = JPATH_ADMINISTRATOR.'/components/com_jsjobs';
					$componentPath = JPATH_SITE.'/components/com_jsjobs';
					require_once $componentPath.'/models/common.php';
					$config = array( 'table_path' => $componentAdminPath.'/tables');
					//$model = new JSJobsModelJsjobs($config);
					$model_common = new JSJobsModelCommon();
					$result = $model_common->addUser($_POST['userrole'],$user['id']);
				}
			}
		}

		function onUserAfterSave($user, $isnew, $success, $msg){ //j 1.6 +
			if( $isnew ){
				if(isset($_POST['userrole'])){
					$db = &JFactory::getDBO();
					$created = date('Y-m-d H:i:s');
					$query = "INSERT INTO #__js_job_userroles (uid,role,dated) VALUES (".$user['id'].", ".$_POST['userrole'].", '".$created."')";
					$db->setQuery( $query );
					$db->query();
					$componentAdminPath = JPATH_ADMINISTRATOR.'/components/com_jsjobs';
					$componentPath = JPATH_SITE.'/components/com_jsjobs';
					require_once $componentPath.'/models/common.php';
					$config = array( 'table_path' => $componentAdminPath.'/tables');
					//$model = new JSJobsModelJsjobs($config);
					$model_common = new JSJobsModelCommon();
					$result = $model_common->addUser($_POST['userrole'],$user['id']);
				}
			}
		}

	   function onUserAfterDelete( $user, $success, $msg )
		{
			$db = &JFactory::getDBO();
			$query = 'DELETE FROM #__js_job_userroles WHERE uid ='.$user['id'];
			$db->setQuery( $query );
			$db->query();
			return true;
		}

		function onAfterDispatch()
        {
			$document = &JFactory::getDocument();
			$content = $document->getBuffer('component');
			$option = JRequest::getVar('option');
			$view = JRequest::getVar('view');
			$html = $this->getRoleHTML();
			$lang = & JFactory :: getLanguage();
			$lang->load('plg_content_jsjobsregister', JPATH_ADMINISTRATOR);

			$version = new JVersion;
			$joomla = $version->getShortVersion();
			$jversion = substr($joomla,0,3);


			$newcontent = "";
			if($option == 'com_user' || $option == 'com_users'){
				if($view == 'register' || $view == 'registration'){
					if($jversion == '1.5')	$checkcontent = '<button class="button validate" type="submit">'.JTEXT::_('REGISTER').'</button>';
					elseif($jversion == '2.5') $checkcontent = '<button type="submit" class="validate">';
					else $checkcontent = '<button type="submit" class="btn btn-primary validate">';

					$newcontent = str_replace($checkcontent,$html.$checkcontent,$content);
				}
			}
			if($newcontent!="")	{
				$document->setBuffer($newcontent,'component');
			}
        }
		function getRoleHTML()
		{
		
			jimport( 'joomla.html.parameter' );
			$plugin 	=& JPluginHelper::getPlugin('system', 'jsjobsregister');
			$version = new JVersion;
			$joomla = $version->getShortVersion();
			$jversion = substr($joomla,0,3);
			if($jversion == '2.5'){
				$params   	= json_decode($plugin->params);
				$this->params   	= new JParameter($plugin->params);
			}else{
				$this->params   	= new JRegistry();
				$this->params->loadString($plugin->params);
			} 
			JPlugin::loadLanguage( 'plg_system_jsjobsregister', JPATH_ADMINISTRATOR );

			$componentAdminPath = JPATH_ADMINISTRATOR.'/components/com_jsjobs';
			$componentPath = JPATH_SITE.'/components/com_jsjobs';
			require_once $componentPath.'/models/common.php';
			$config = array( 'table_path' => $componentAdminPath.'/tables');
			//$model = new JSJobsModelJsjobs($config);
			$model_common = new JSJobsModelCommon();
			$can_employer_register = $model_common->userCanRegisterAsEmployer();
			
			$userregisterinrole = $this->params->get('userregisterinrole');
			//$userregisterinrole = $params->userregisterinrole;
			
			$jsrole = JRequest::getVar('jsrole');
			if($userregisterinrole == 2) $jsrole = 1; // enforce employer
			elseif($userregisterinrole == 3) $jsrole = 2; // enforce employer
			
			if($can_employer_register!=true) $jsrole=2; // enforce jobseeker
				
			if($jsrole){
				if ($jsrole == 1){ // employer
					$rolehtml = "<input type='hidden' name='userrole' value='1'>".JText::_('JS_EMPLOYER');
				}else $rolehtml = "<input type='hidden' name='userrole' value='2'>".JText::_('JS_JOBSEEKER');
				
				$returnvalue = "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
								  <tr><td width=\"120\"  >Role:</td><td >".$rolehtml."</td></tr></table>";
			}else{
				$rolehtml = "<select name='userrole'>
							<option value='1'>".JText::_('JS_EMPLOYER')."</option>
							<option value='2'>".JText::_('JS_JOBSEEKER')."</option>
						</select>";
				$returnvalue = "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
								  <tr><td width=\"120\"  >Role:</td><td >".$rolehtml."</td></tr></table>";
			}					
			return $returnvalue;				
		}
		
}
?>
