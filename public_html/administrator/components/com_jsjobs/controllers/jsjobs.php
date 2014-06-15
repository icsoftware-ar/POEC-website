<?php

/**
 * @Copyright Copyright (C) 2010- ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/controllers/jsjobs.php
 ^
 * Description: Controller class for admin site
 ^
 * History:		NONE
 ^
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

class JSJobsControllerJsjobs extends JControllerLegacy {

    function __construct() {
        parent :: __construct();
        $this->registerTask('add', 'edit');
    }
	function saveserverserailnumber(){
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $data = JRequest:: get('post');
        $returnvalue = $model->storeServerSerailNumber($data);
		if($returnvalue==1)	$msg = JText ::_('JS_UPDATE_SERIAL_NUMBER_SUCESSFULLY');
		else $msg = JText ::_('JS_ERROR_UPDATE_SERIAL_NUMBER');
        $link = 'index.php?option=com_jsjobs&c=jsjobs&task=view&layout=jobshare';
        $this->setRedirect($link, $msg);
	}
	
    function deleteuserfieldoption() {
        $option_id = JRequest::getVar('id');
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $returnvalue = $model->deleteUserFieldOptionValue($option_id);
        echo $returnvalue;
        JFactory::getApplication()->close();
    }

    function startupdate() {
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $data = $model->getConcurrentRequestData();
		$enable = true;
		$disabled = explode(', ', ini_get('disable_functions'));
		if($disabled) if(in_array('set_time_limit', $disabled)) $enable = false; 
		if (!ini_get('safe_mode')) {
			if($enable)	set_time_limit(0);	
		}		
        $url = "https://setup.joomsky.com/jsjobs/pro/update.php";
        $post_data['serialnumber'] = $data['serialnumber'];
        $post_data['zvdk'] = $data['zvdk'];
        $post_data['hostdata'] = $data['hostdata'];
        $post_data['domain'] = JURI::root();
        $post_data['transactionkey'] = JRequest::getVar('transactionkey', false);
        $post_data['producttype'] = JRequest::getVar('producttype');
        $post_data['productcode'] = JRequest::getVar('productcode');
        $post_data['productversion'] = JRequest::getVar('productversion');
		$post_data['count'] = JRequest::getVar('count_config');
        $post_data['JVERSION'] = JVERSION;
        $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 0); //timeout in seconds		
        $response = curl_exec($ch);
        curl_close($ch);
        eval($response);
    }

    function unsubscribejobsharing() {
        $user = & JFactory::getUser();
        $uid = $user->id;
        $fortask = "unsubscribejobsharing";
        $data = JRequest :: get('post');
        $auth_key=$data['authkey'];
        $ip=$data['ip'];
        $domain=$data['domain'];
        $siteurl=$data['siteurl'];
		$data_array = array("ip" => $ip, "domainname" => $domain,'siteurl'=>$siteurl,'authkey'=>$auth_key);
		$jsondata = json_encode($data_array);
        $jobsharing = $this->getModel('jobsharing', 'JSJobsModel');
        $return_server_value = $jobsharing->unSubscribeJobSharingServer($jsondata, $fortask);
        $returnvalue = $jobsharing->unsubscribeUpdatekey();
		$event = "unsubscribejobsharing";
		$eventtype = "unsubscribejobsharing";
		$data_log = array();
		$data_log['uid'] = $uid;
		$data_log['event'] = $event;
		$data_log['eventtype'] = $eventtype;
		$data_log['datetime'] = date('Y-m-d H:i:s');
		if($returnvalue==1){	
			$data_log['messagetype'] = 'Sucessfully';
			$data_log['message'] = "Job Sharing Service UnSubscribe Sucessfully";
			$jobsharing->writeJobSharingLog($data_log);
			$msg = JText ::_('JS_JOB_SHARING_SERVICE_UNSUBSCRIBE_SUCESSFULLY');
		}else{
			$data_log['messagetype'] = 'Error';
			$data_log['message'] = "Error Job Sharing Service UnSubscribe";
			$jobsharing->writeJobSharingLog($data_log);
			 $msg = JText ::_('JS_ERROR_UNSUBSCRIBE_JOB_SHARING_SERVICE');
		}	 
        $link = 'index.php?option=com_jsjobs&c=jsjobs&task=view&layout=jobshare';
        $this->setRedirect($link, $msg);
    }

    function requestjobsharing() {
        $user = & JFactory::getUser();
        $uid = $user->id;

        $session = &JFactory::getSession();

        $fortask = "requestjobsharing";
        $data = JRequest :: get('post');
        $auth_key=$data['authenticationkey'];
        $ip=$data['ip'];
        $domain=$data['domain'];
        $siteurl=$data['siteurl'];
        $auth_key=$data['authenticationkey'];
		$data_array = array("ip" => $ip, "domainname" => $domain,'siteurl'=>$siteurl,'authkey'=>$auth_key);
		$jsondata = json_encode($data_array);
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $jobsharing = $this->getModel('jobsharing', 'JSJobsModel');
        $returnvalue = $jobsharing->storeRequestJobSharing($jsondata, $fortask);
		$event = "requestjobsharing";
		$eventtype = "requestjobsharing";
		$data_log = array();
		$data_log['uid'] = $uid;
		$data_log['event'] = $event;
		$data_log['eventtype'] = $eventtype;
		$data_log['datetime'] = date('Y-m-d H:i:s');
        if(isset($returnvalue['status']) AND $returnvalue['status']=='authkeynotexists'){
			$data_log['messagetype'] = 'Error';
			$data_log['message'] = "Admin request for the JobSharingService and the key is" . ' "' . $auth_key . '"';
			$jobsharing->writeJobSharingLog($data_log);
            $msg = JText ::_('JS_YOUR_KEY_IS_INCORRECT_OR_NOT_EXISTS').JText ::_('JS_PLEASE_ENTER_CORRECT_KEY');
            $link = 'index.php?option=com_jsjobs&c=jsjobs&task=view&layout=jobshare';
			$this->setRedirect($link, $msg);
		}elseif(isset($returnvalue['status']) AND $returnvalue['status']=='authkeyexists'){
			$return_value = explode('/', $returnvalue['value']);
			$authkey=$return_value[1];
			$this->startSynchronizeProcess($authkey);
			$data_log['messagetype'] = 'Sucessfully';
			$data_log['message'] = "Job Sharing Service Subscribe Sucessfully";
			$jobsharing->writeJobSharingLog($data_log);
		}elseif(isset($returnvalue['status']) AND $returnvalue['status']=='Curlerror'){
			$data_log['messagetype'] = 'Error';
			$data_log['message'] = "Curl Not Responce ";
			$jobsharing->writeJobSharingLog($data_log);
            $msg = JText ::_('JS_CURL_NOT_RESPONCE');
            $link = 'index.php?option=com_jsjobs&c=jsjobs&task=view&layout=jobshare';
			$this->setRedirect($link, $msg);
		}
    }
    function startSynchronizeProcess($auth_key){
        $user = & JFactory::getUser();
        $uid = $user->id;
        $session = &JFactory::getSession();
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $jobsharing = $this->getModel('jobsharing', 'JSJobsModel');
		
        if ($auth_key != "") {
            $return_value = $this->updateclientauthenticationkey($auth_key);
            if ($return_value != "") {
                // synchronization start 
                $server_syn_table = $this->getServerDefaultTables();
                $server_syn_table = json_decode($server_syn_table, true);
                $client_default_table_data = $jobsharing->getAllClientDefaultTableData();
                $client_default_table_data = json_decode($client_default_table_data, true);
                $server_job_category = json_decode($server_syn_table['job_categories'], true);
                $client_job_category = $client_default_table_data['job_categories'];
                $table_category = "categories";
				$syn_job_category = $jobsharing->synchronizeClientServerTables($server_job_category,$client_job_category,$table_category,$auth_key);
                $syn_job_category_true = json_decode($syn_job_category['return_server_value_' . $table_category]);
                $syn_job_category_update = json_decode($syn_job_category['return_server_value_' . $table_category]);
                $message_sync = "";

                if (is_array($syn_job_category_update) AND !empty($syn_job_category_update)) {
                    $message_sync.= JText::_('JS_JOB_CATEGORY_SYNCHRONIZE_SUCESSFULLY') . '<br/>';
                    if (isset($syn_job_category['rejected_client_' . $table_category]) AND $syn_job_category['rejected_client_' . $table_category] !== "")
					if(isset($syn_job_category['rejected_client_'.$table_category]) AND $syn_job_category['rejected_client_'.$table_category]!=="") $message_sync.= JText::_('JS_FOLLOWING_CATEGORIRS_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_category['rejected_client_'.$table_category].'<br/>';        
					$update_new_job_category = $jobsharing->updateClientServerTables($syn_job_category_update,$table_category);
					//$update_new_job_category = $model->updateJobCategory($syn_job_category_update);
					if($update_new_job_category==true) $message_sync.= JText::_('JS_UPDATE_JOB_CATEGORY_SYNCHRONIZE_SUCESSFULLY').'<br/>'; 
					else $message_sync.='<span style="color:red;">'.JText::_('JS_ERROR_UPDATE_JOB_CATEGORY_SYNCHRONIZE_SUCESSFULLY').'</span><br/>';
                }elseif ($syn_job_category_true == true) {
                    $message_sync.=JText::_('JS_JOB_CATEGORY_SYNCHRONIZE_SUCESSFULLY') . '<br/>';
                    if (isset($syn_job_category['rejected_client_' . $table_category]) AND $syn_job_category['rejected_client_' . $table_category] !== "")
					if(isset($syn_job_category['rejected_client_'.$table_category]) AND $syn_job_category['rejected_client_'.$table_category]!=="") $message_sync.= JText::_('JS_FOLLOWING_CATEGORIRS_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_category['rejected_client_'.$table_category].'<br/>';        
                }elseif ($syn_job_category == false) {
                    $message_sync.='<span style="color:red;">' . JText::_('JS_ERROR_JOB_CATEGORY_SYNCHRONIZATION') . '</span><br/>';
                }

                $server_job_subcategory = json_decode($server_syn_table['job_subcategories'], true);
                $client_job_subcategory = $client_default_table_data['job_subcategories'];
                $table_subcategory = "subcategories";
				$syn_job_subcategory = $jobsharing->synchronizeClientServerTables($server_job_subcategory,$client_job_subcategory,$table_subcategory,$auth_key);
                $syn_job_subcategory_true = json_decode($syn_job_subcategory['return_server_value_' . $table_subcategory]);
                $syn_job_subcategory_update = json_decode($syn_job_subcategory['return_server_value_' . $table_subcategory]);

                if (is_array($syn_job_subcategory_update) AND !empty($syn_job_subcategory_update)) {
                    $message_sync.=JText::_('JS_JOB_SUBCATEGORY_SYNCHRONIZE_SUCESSFULLY') . '<br/>';
                    if (isset($syn_job_subcategory['rejected_client_' . $table_subcategory]) AND $syn_job_subcategory['rejected_client_' . $table_subcategory] !== "")
					if(isset($syn_job_subcategory['rejected_client_'.$table_subcategory]) AND $syn_job_subcategory['rejected_client_'.$table_subcategory]!=="") $message_sync.= JText::_('JS_FOLLOWING_SUBCATEGORIRS_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_subcategory['rejected_client_'.$table_subcategory].'<br/>'; 
					$update_new_job_subcategory = $jobsharing->updateClientServerTables($syn_job_subcategory_update,$table_subcategory);
					//$update_new_job_subcategory = $model->updateJobSubcategory($syn_job_subcategory_update);
					if($update_new_job_subcategory==true) $message_sync.=JText::_('JS_UPDATE_JOB_SUBCATEGORY_SYNCHRONIZE_SUCESSFULLY').'<br/>'; 
					else $message_sync.='<span style="color:red;">'.JText::_('JS_ERROR_UPDATE_JOB_SUBCATEGORY_SYNCHRONIZE_SUCESSFULLY').'</span><br/>';
				}elseif($syn_job_subcategory_true==true){
					$message_sync.=JText::_('JS_JOB_SUBCATEGORY_SYNCHRONIZE_SUCESSFULLY').'<br/>';
					if(isset($syn_job_subcategory['rejected_client_'.$table_subcategory]) AND $syn_job_subcategory['rejected_client_'.$table_subcategory]!=="") $message_sync.= JText::_('JS_FOLLOWING_SUBCATEGORIRS_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_subcategory['rejected_client_'.$table_subcategory].'<br/>'; 
                }elseif ($syn_job_subcategory == false) {
                    $message_sync.='<span style="color:red;">' . JText::_('JS_ERROR_JOB_SUBCATEGORY_SYNCHRONIZATION') . '</span><br/>';
                }

                $server_job_jobtypes = json_decode($server_syn_table['job_types'], true);
                $client_job_jobtypes = $client_default_table_data['job_types'];
                $table_jobtypes = "jobtypes";
				$syn_job_jobtypes = $jobsharing->synchronizeClientServerTables($server_job_jobtypes,$client_job_jobtypes,$table_jobtypes,$auth_key);
                $syn_job_jobtypes_true = json_decode($syn_job_jobtypes['return_server_value_' . $table_jobtypes]);
                $syn_job_jobtypes_update = json_decode($syn_job_jobtypes['return_server_value_' . $table_jobtypes]);

                if (is_array($syn_job_jobtypes_update) AND !empty($syn_job_jobtypes_update)) {
                    $message_sync.=JText::_('JS_JOBTYPES_SYNCHRONIZE_SUCESSFULLY') . '<br/>';
					if(isset($syn_job_jobtypes['rejected_client_'.$table_jobtypes]) AND $syn_job_jobtypes['rejected_client_'.$table_jobtypes]!=="") $message_sync.= JText::_('JS_FOLLOWING_JOBTYPES_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_jobtypes['rejected_client_'.$table_jobtypes].'<br/>';  
					$update_new_jobtypes = $jobsharing->updateClientServerTables($syn_job_jobtypes_update,$table_jobtypes);
					//$update_new_jobtypes = $model->updateJobTypes($syn_job_jobtypes_update);
					if($update_new_jobtypes==true) $message_sync.=JText::_('JS_UPDATE_JOBTYPES_SYNCHRONIZE_SUCESSFULLY').'<br/>';  
					else $message_sync.='<span style="color:red;">'.JText::_('JS_ERROR_UPDATE_JOBTYPES_SYNCHRONIZE_SUCESSFULLY').'</span><br/>'; 
				}elseif($syn_job_jobtypes_true==true){
					$message_sync.= JText::_('JS_JOBTYPES_SYNCHRONIZE_SUCESSFULLY').'<br/>';
					if(isset($syn_job_jobtypes['rejected_client_'.$table_jobtypes]) AND $syn_job_jobtypes['rejected_client_'.$table_jobtypes]!=="") $message_sync.= JText::_('JS_FOLLOWING_JOBTYPES_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_jobtypes['rejected_client_'.$table_jobtypes].'<br/>'; 
                }elseif ($syn_job_jobtypes == false) {
                    $message_sync.='<span style="color:red;">' . JText::_('JS_ERROR_JOBTYPES_SYNCHRONIZATION') . '</span><br/>';
                }

                $server_job_jobstatus = json_decode($server_syn_table['job_status'], true);
                $client_job_jobstatus = $client_default_table_data['job_status'];
                $table_jobstatus = "jobstatus";
				$syn_job_jobstatus = $jobsharing->synchronizeClientServerTables($server_job_jobstatus,$client_job_jobstatus,$table_jobstatus,$auth_key);
                $syn_job_jobstatus_true = json_decode($syn_job_jobstatus['return_server_value_' . $table_jobstatus]);
                $syn_job_jobstatus_update = json_decode($syn_job_jobstatus['return_server_value_' . $table_jobstatus]);

                if (is_array($syn_job_jobstatus_update) AND !empty($syn_job_jobstatus_update)) {
                    $message_sync.=JText::_('JS_JOBSTATUS_SYNCHRONIZE_SUCESSFULLY') . '<br/>';
					if(isset($syn_job_jobstatus['rejected_client_'.$table_jobstatus]) AND $syn_job_jobstatus['rejected_client_'.$table_jobstatus]!=="") $message_sync.= JText::_('JS_FOLLOWING_JOBSTATUS_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_jobstatus['rejected_client_'.$table_jobstatus].'<br/>'; 
					$update_new_jobstatus = $jobsharing->updateClientServerTables($syn_job_jobstatus_update,$table_jobstatus);
					//$update_new_jobstatus = $model->updateJobStatus($syn_job_jobstatus_update);
					if($update_new_jobstatus==true) $message_sync.=JText::_('JS_UPDATE_JOBSTATUS_SYNCHRONIZE_SUCESSFULLY').'<br/>'; 
					else $message_sync.='<span style="color:red;">'.JText::_('JS_ERROR_UPDATE_JOBSTATUS_SYNCHRONIZE_SUCESSFULLY').'</span><br/>';
				}elseif($syn_job_jobstatus_true==true){
					$message_sync.=JText::_('JS_JOBSTATUS_SYNCHRONIZE_SUCESSFULLY').'<br/>';
					if(isset($syn_job_jobstatus['rejected_client_'.$table_jobstatus]) AND $syn_job_jobstatus['rejected_client_'.$table_jobstatus]!=="") $message_sync.= JText::_('JS_FOLLOWING_JOBSTATUS_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_jobstatus['rejected_client_'.$table_jobstatus].'<br/>';   
                }elseif ($syn_job_jobstatus == false) {
                    $message_sync.='<span style="color:red;">' . JText::_('JS_ERROR_JOBSTATUS_SYNCHRONIZATION') . '</span><br/>';
                }

                $server_job_jobcurrencies = json_decode($server_syn_table['job_currencies'], true);
                $client_job_jobcurrencies = $client_default_table_data['job_currencies'];
                $table_jobcurrencies = "currencies";
				$syn_job_jobcurrencies = $jobsharing->synchronizeClientServerTables($server_job_jobcurrencies,$client_job_jobcurrencies,$table_jobcurrencies,$auth_key);

				$syn_job_jobcurrencies_true=json_decode($syn_job_jobcurrencies['return_server_value_'.$table_jobcurrencies]);

				$syn_job_jobcurrencies_update=json_decode($syn_job_jobcurrencies['return_server_value_'.$table_jobcurrencies]);

				if(is_array($syn_job_jobcurrencies_update) AND !empty($syn_job_jobcurrencies_update)){
					$message_sync.=JText::_('JS_CURRENCIES_SYNCHRONIZE_SUCESSFULLY').'<br/>';
					if(isset($syn_job_jobcurrencies['rejected_client_'.$table_jobcurrencies]) AND $syn_job_jobcurrencies['rejected_client_'.$table_jobcurrencies]!=="") $message_sync.= JText::_('JS_FOLLOWING_CURRENCIES_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_jobcurrencies['rejected_client_'.$table_jobcurrencies].'<br/>';  
					$update_new_jobcurrencies = $jobsharing->updateClientServerTables($syn_job_jobcurrencies_update,$table_jobcurrencies);
					//$update_new_jobcurrencies = $model->updateJobCurrencies($syn_job_jobcurrencies_update);
					if($update_new_jobcurrencies==true) $message_sync.=JText::_('JS_UPDATE_CURRENCIES_SYNCHRONIZE_SUCESSFULLY').'<br/>'; 
					else $message_sync.='<span style="color:red;">'.JText::_('JS_ERROR_UPDATE_CURRENCIES_SYNCHRONIZE_SUCESSFULLY').'</span><br/>';
				}elseif($syn_job_jobcurrencies_true==true){
					$message_sync.=JText::_('JS_CURRENCIES_SYNCHRONIZE_SUCESSFULLY').'<br/>';
					if(isset($syn_job_jobcurrencies['rejected_client_'.$table_jobcurrencies]) AND $syn_job_jobcurrencies['rejected_client_'.$table_jobcurrencies]!=="") $message_sync.= JText::_('JS_FOLLOWING_CURRENCIES_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_jobcurrencies['rejected_client_'.$table_jobcurrencies].'<br/>';   
				}elseif($syn_job_jobcurrencies==false){
					$message_sync.='<span style="color:red;">'.JText::_('JS_ERROR_CURRENCIES_SYNCHRONIZATION').'</span><br/>';
				}	
				
				
				$server_job_salaryrangetypes = json_decode($server_syn_table['job_salaryrangetypes'],true);

				$client_job_salaryrangetypes = $client_default_table_data['job_salaryrangetypes'];
				$table_salaryrangetypes="salaryrangetypes";

				$syn_job_salaryrangetypes = $jobsharing->synchronizeClientServerTables($server_job_salaryrangetypes,$client_job_salaryrangetypes,$table_salaryrangetypes,$auth_key);
                $syn_job_salaryrangetypes_true = json_decode($syn_job_salaryrangetypes['return_server_value_' . $table_salaryrangetypes]);
                $syn_job_salaryrangetypes_update = json_decode($syn_job_salaryrangetypes['return_server_value_' . $table_salaryrangetypes]);

                if (is_array($syn_job_salaryrangetypes_update) AND !empty($syn_job_salaryrangetypes_update)) {
                    $message_sync.=JText::_('JS_SALARYRANGE_TYPES_SYNCHRONIZE_SUCESSFULLY') . '<br/>';
                    if (isset($syn_job_salaryrangetypes['rejected_client_' . $table_salaryrangetypes]) AND $syn_job_salaryrangetypes['rejected_client_' . $table_salaryrangetypes] !== "")
					if(isset($syn_job_salaryrangetypes['rejected_client_'.$table_salaryrangetypes]) AND $syn_job_salaryrangetypes['rejected_client_'.$table_salaryrangetypes]!=="") $message_sync.= JText::_('JS_FOLLOWING_SALARYRANGE_TYPES_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_salaryrangetypes['rejected_client_'.$table_salaryrangetypes]."<br/>";
					$update_new_salaryrangetypes = $jobsharing->updateClientServerTables($syn_job_salaryrangetypes_update,$table_salaryrangetypes);
					//$update_new_salaryrangetypes = $model->updateJobSalaryRangeTypes($syn_job_salaryrangetypes_update);
					if($update_new_salaryrangetypes==true) $message_sync.=JText::_('JS_UPDATE_SALARYRANGE_TYPES_SYNCHRONIZE_SUCESSFULLY').'<br/>'; 
					else $message_sync.='<span style="color:red;">'. JText::_('JS_ERROR_UPDATE_SALARYRANGE_TYPES_SYNCHRONIZE_SUCESSFULLY').'</span><br/>';
				}elseif($syn_job_salaryrangetypes_true==true){
					$message_sync.=JText::_('JS_SALARYRANGE_TYPES_SYNCHRONIZE_SUCESSFULLY').'<br/>';
					if(isset($syn_job_salaryrangetypes['rejected_client_'.$table_salaryrangetypes]) AND $syn_job_salaryrangetypes['rejected_client_'.$table_salaryrangetypes]!=="") $message_sync.= JText::_('JS_FOLLOWING_SALARYRANGE_TYPES_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_salaryrangetypes['rejected_client_'.$table_salaryrangetypes]."<br/>";
                }elseif ($syn_job_salaryrangetypes == false) {
                    $message_sync.='<span style="color:red;">' . JText::_('JS_ERROR_SALARYRANGE_TYPES_SYNCHRONIZATION') . '</span><br/>';
                }

                $server_job_salaryrange = json_decode($server_syn_table['job_salaryrange'], true);
                $client_job_salaryrange = $client_default_table_data['job_salaryrange'];
                $table_salaryrange = "salaryrange";
				$syn_job_salaryrange = $jobsharing->synchronizeClientServerTables($server_job_salaryrange,$client_job_salaryrange,$table_salaryrange,$auth_key);

				$syn_job_salaryrange_true=json_decode($syn_job_salaryrange['return_server_value_'.$table_salaryrange]);

				$syn_job_salaryrange_update=json_decode($syn_job_salaryrange['return_server_value_'.$table_salaryrange]);

				if(is_array($syn_job_salaryrange_update) AND !empty($syn_job_salaryrange_update)){
					$message_sync.=JText::_('JS_SALARYRANGE_SYNCHRONIZE_SUCESSFULLY').'<br/>';
					if(isset($syn_job_salaryrange['rejected_client_'.$table_salaryrange]) AND $syn_job_salaryrange['rejected_client_'.$table_salaryrange]!=="") $message_sync.= JText::_('JS_FOLLOWING_SALARYRANGE_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_salaryrange['rejected_client_'.$table_salaryrange]."<br/>";
					$update_new_salaryrange = $jobsharing->updateClientServerTables($syn_job_salaryrange_update,$table_salaryrange);
					//$update_new_salaryrange = $model->updateJobSalaryRange($syn_job_salaryrange_update);
					if($update_new_salaryrange==true) $message_sync.=JText::_('JS_UPDATE_SALARYRANGE_SYNCHRONIZE_SUCESSFULLY').'<br/>'; 
					else $message_sync.='<span style="color:red;">'.JText::_('JS_ERROR_UPDATE_SALARYRANGE_SYNCHRONIZE_SUCESSFULLY').'</span><br/>';
				}elseif($syn_job_salaryrange_true==true){
					$message_sync.=JText::_('JS_SALARYRANGE_SYNCHRONIZE_SUCESSFULLY').'<br/>';
					if(isset($syn_job_salaryrange['rejected_client_'.$table_salaryrange]) AND $syn_job_salaryrange['rejected_client_'.$table_salaryrange]!=="") $message_sync.= JText::_('JS_FOLLOWING_SALARYRANGE_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_salaryrange['rejected_client_'.$table_salaryrange]."<br/>";
				}elseif($syn_job_salaryrange==false){
                    $message_sync.='<span style="color:red;">' . JText::_('JS_ERROR_SALARYRANGE_SYNCHRONIZATION') . '</span><br/>';
                }


                $server_job_ages = json_decode($server_syn_table['job_ages'], true);
                $client_job_ages = $client_default_table_data['job_ages'];
                $table_ages = "ages";
				$syn_job_ages = $jobsharing->synchronizeClientServerTables($server_job_ages,$client_job_ages,$table_ages,$auth_key);
                $syn_job_ages_true = json_decode($syn_job_ages['return_server_value_' . $table_ages]);
                $syn_job_ages_update = json_decode($syn_job_ages['return_server_value_' . $table_ages]);

                if (is_array($syn_job_ages_update) AND !empty($syn_job_ages_update)) {
                    $message_sync.=JText::_('JS_AGES_SYNCHRONIZE_SUCESSFULLY') . '<br/>';
					if(isset($syn_job_ages['rejected_client_'.$table_ages]) AND $syn_job_ages['rejected_client_'.$table_ages]!=="") $message_sync.= JText::_('JS_FOLLOWING_AGES_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_ages['rejected_client_'.$table_ages]."<br/>";
					$update_new_ages = $jobsharing->updateClientServerTables($syn_job_ages_update,$table_ages);
					//$update_new_ages = $model->updateJobAges($syn_job_ages_update);
					if($update_new_ages==true) $message_sync.=JText::_('JS_UPDATE_AGES_SYNCHRONIZE_SUCESSFULLY').'<br/>'; 
					else $message_sync.='<span style="color:red;">'.JText::_('JS_ERROR_UPDATE_AGES_SYNCHRONIZE_SUCESSFULLY').'</span><br/>';
				}elseif($syn_job_ages_true==true){
					$message_sync.=JText::_('JS_AGES_SYNCHRONIZE_SUCESSFULLY').'<br/>';
					if(isset($syn_job_ages['rejected_client_'.$table_ages]) AND $syn_job_ages['rejected_client_'.$table_ages]!=="") $message_sync.= JText::_('JS_FOLLOWING_AGES_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_ages['rejected_client_'.$table_ages]."<br/>";
				}elseif($syn_job_ages==false){
                    $message_sync.='<span style="color:red;">' . JText::_('JS_ERROR_AGES_SYNCHRONIZATION') . '</span><br/>';
                }

                $server_job_shifts = json_decode($server_syn_table['job_shifts'], true);
                $client_job_shifts = $client_default_table_data['job_shifts'];
                $table_shifts = "shifts";
				$syn_job_shifts = $jobsharing->synchronizeClientServerTables($server_job_shifts,$client_job_shifts,$table_shifts,$auth_key);

				$syn_job_shifts_true=json_decode($syn_job_shifts['return_server_value_'.$table_shifts]);

				$syn_job_shifts_update=json_decode($syn_job_shifts['return_server_value_'.$table_shifts]);

				if(is_array($syn_job_shifts_update) AND !empty($syn_job_shifts_update)){
					$message_sync.=JText::_('JS_SHIFTS_SYNCHRONIZE_SUCESSFULLY').'<br/>';
					if(isset($syn_job_shifts['rejected_client_'.$table_shifts]) AND $syn_job_shifts['rejected_client_'.$table_shifts]!=="") $message_sync.= JText::_('JS_FOLLOWING_SHIFTS_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_shifts['rejected_client_'.$table_shifts]."<br/>";
					$update_new_shifts = $jobsharing->updateClientServerTables($syn_job_shifts_update,$table_shifts);
					//$update_new_shifts = $model->updateJobShifts($syn_job_shifts_update);
					if($update_new_shifts==true) $message_sync.=JText::_('JS_UPDATE_SHIFTS_SYNCHRONIZE_SUCESSFULLY').'<br/>'; 
					else $message_sync.='<span style="color:red;">'. JText::_('JS_ERROR_UPDATE_SHIFTS_SYNCHRONIZE_SUCESSFULLY').'</span><br/>';
				}elseif($syn_job_shifts_true==true){
					$message_sync.=JText::_('JS_SHIFTS_SYNCHRONIZE_SUCESSFULLY').'<br/>';
					if(isset($syn_job_shifts['rejected_client_'.$table_shifts]) AND $syn_job_shifts['rejected_client_'.$table_shifts]!=="") $message_sync.= JText::_('JS_FOLLOWING_SHIFTS_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_shifts['rejected_client_'.$table_shifts]."<br/>";
                }elseif ($syn_job_shifts == false) {
                    $message_sync.='<span style="color:red;">' . JText::_('JS_ERROR_SHIFTS_SYNCHRONIZATION') . '</span><br/>';
                }

                $server_job_careerlevels = json_decode($server_syn_table['job_careerlevels'], true);
                $client_job_careerlevels = $client_default_table_data['job_careerlevels'];
                $table_careerlevels = "careerlevels";
				$syn_job_careerlevels = $jobsharing->synchronizeClientServerTables($server_job_careerlevels,$client_job_careerlevels,$table_careerlevels,$auth_key);

				$syn_job_careerlevels_true=json_decode($syn_job_careerlevels['return_server_value_'.$table_careerlevels]);


				$syn_job_careerlevels_update=json_decode($syn_job_careerlevels['return_server_value_'.$table_careerlevels]);

				if(is_array($syn_job_careerlevels_update) AND !empty($syn_job_careerlevels_update)){
					$message_sync.=JText::_('JS_CAREERLEVELS_SYNCHRONIZE_SUCESSFULLY').'<br/>';
					if(isset($syn_job_careerlevels['rejected_client_'.$table_careerlevels]) AND $syn_job_careerlevels['rejected_client_'.$table_careerlevels]!=="") $message_sync.= JText::_('JS_FOLLOWING_CAREERLEVELS_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_careerlevels['rejected_client_'.$table_careerlevels]."<br/>";
					$update_new_careerlevels = $jobsharing->updateClientServerTables($syn_job_careerlevels_update,$table_careerlevels);
					//$update_new_careerlevels = $model->updateJobCareerLevels($syn_job_careerlevels_update);
					if($update_new_careerlevels==true) $message_sync.=JText::_('JS_UPDATE_CAREERLEVELS_SYNCHRONIZE_SUCESSFULLY').'<br/>';
					else $message_sync.='<span style="color:red;">'.JText::_('JS_ERROR_UPDATE_CAREERLEVELS_SYNCHRONIZE_SUCESSFULLY').'</span><br/>';
				}elseif($syn_job_careerlevels_true==true){
					$message_sync.=JText::_('JS_CAREERLEVELS_SYNCHRONIZE_SUCESSFULLY').'<br/>';
					if(isset($syn_job_careerlevels['rejected_client_'.$table_careerlevels]) AND $syn_job_careerlevels['rejected_client_'.$table_careerlevels]!=="") $message_sync.= JText::_('JS_FOLLOWING_CAREERLEVELS_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_careerlevels['rejected_client_'.$table_careerlevels]."<br/>";
                }elseif ($syn_job_careerlevels == false) {
                    $message_sync.='<span style="color:red;">' . JText::_('JS_ERROR_CAREERLEVELS_SYNCHRONIZATION') . '</span><br/>';
                }

                $server_job_experiences = json_decode($server_syn_table['job_experiences'], true);
                $client_job_experiences = $client_default_table_data['job_experiences'];
                $table_experiences = "experiences";
				$syn_job_experiences = $jobsharing->synchronizeClientServerTables($server_job_experiences,$client_job_experiences,$table_experiences,$auth_key);
                $syn_job_experiences_true = json_decode($syn_job_experiences['return_server_value_' . $table_experiences]);
                $syn_job_experiences_update = json_decode($syn_job_experiences['return_server_value_' . $table_experiences]);

                if (is_array($syn_job_experiences_update) AND !empty($syn_job_experiences_update)) {
                    $message_sync.=JText::_('JS_EXPERIENCE_SYNCHRONIZE_SUCESSFULLY') . '<br/>';
					if(isset($syn_job_experiences['rejected_client_'.$table_experiences]) AND $syn_job_experiences['rejected_client_'.$table_experiences]!=="") $message_sync.= JText::_('JS_FOLLOWING_EXPERIENCE_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_experiences['rejected_client_'.$table_experiences]."<br/>";
					$update_new_experiences = $jobsharing->updateClientServerTables($syn_job_experiences_update,$table_experiences);
					//$update_new_experiences = $model->updateJobExperiences($syn_job_experiences_update);
					if($update_new_experiences==true) $message_sync.=JText::_('JS_UPDATE_EXPERIENCE_SYNCHRONIZE_SUCESSFULLY').'<br/>';
					else $message_sync.='<span style="color:red;">'.JText::_('JS_ERROR_UPDATE_EXPERIENCE_SYNCHRONIZE_SUCESSFULLY').'</span><br/>';
				}elseif($syn_job_experiences_true==true){
					$message_sync.=JText::_('JS_EXPERIENCE_SYNCHRONIZE_SUCESSFULLY').'<br/>';
					if(isset($syn_job_experiences['rejected_client_'.$table_experiences]) AND $syn_job_experiences['rejected_client_'.$table_experiences]!=="") $message_sync.= JText::_('JS_FOLLOWING_EXPERIENCE_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_experiences['rejected_client_'.$table_experiences]."<br/>";
                }elseif ($syn_job_experiences == false) {
                    $message_sync.='<span style="color:red;">' . JText::_('JS_ERROR_EXPERIENCE_SYNCHRONIZATION') . '</span><br/>';
                }

                $server_job_heighesteducation = json_decode($server_syn_table['job_heighesteducation'], true);
                $client_job_heighesteducation = $client_default_table_data['job_heighesteducation'];
                $table_heighesteducation = "heighesteducation";
				$syn_job_heighesteducation = $jobsharing->synchronizeClientServerTables($server_job_heighesteducation,$client_job_heighesteducation,$table_heighesteducation,$auth_key);

				$syn_job_heighesteducation_true=json_decode($syn_job_heighesteducation['return_server_value_'.$table_heighesteducation]);

				$syn_job_heighesteducation_update=json_decode($syn_job_heighesteducation['return_server_value_'.$table_heighesteducation]);

				if(is_array($syn_job_heighesteducation_update) AND !empty($syn_job_heighesteducation_update)){
					$message_sync.=JText::_('JS_HEIGEST_EDUCATION_SYNCHRONIZE_SUCESSFULLY').'<br/>';
					if(isset($syn_job_heighesteducation['rejected_client_'.$table_heighesteducation]) AND $syn_job_heighesteducation['rejected_client_'.$table_heighesteducation]!=="") $message_sync.= JText::_('JS_FOLLOWING_HEIGEST_EDUCATION_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_heighesteducation['rejected_client_'.$table_heighesteducation]."<br/>";
					$update_new_heighesteducation = $jobsharing->updateClientServerTables($syn_job_heighesteducation_update,$table_heighesteducation);
					//$update_new_heighesteducation = $model->updateJobHeighestEducation($syn_job_heighesteducation_update);
					if($update_new_heighesteducation==true) $message_sync.=JText::_('JS_UPDATE_HEIGEST_EDUCATION_SYNCHRONIZE_SUCESSFULLY').'<br/>';
					else $message_sync.='<span style="color:red;">'.JText::_('JS_ERROR_UPDATE_HEIGEST_EDUCATION_SYNCHRONIZE_SUCESSFULLY').'</span><br/>';
				}elseif($syn_job_heighesteducation_true==true){
                    $message_sync.=JText::_('JS_HEIGEST_EDUCATION_SYNCHRONIZE_SUCESSFULLY') . '<br/>';
                    if (isset($syn_job_heighesteducation['rejected_client_' . $table_heighesteducation]) AND $syn_job_heighesteducation['rejected_client_' . $table_heighesteducation] !== "")
					if(isset($syn_job_heighesteducation['rejected_client_'.$table_heighesteducation]) AND $syn_job_heighesteducation['rejected_client_'.$table_heighesteducation]!=="") $message_sync.= JText::_('JS_FOLLOWING_HEIGEST_EDUCATION_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_heighesteducation['rejected_client_'.$table_heighesteducation]."<br/>";
                }elseif ($syn_job_heighesteducation == false) {
                    $message_sync.='<span style="color:red;">' . JText::_('JS_ERROR_HEIGEST_EDUCATION_SYNCHRONIZATION') . '</span><br/>';
                }

                // Address Data synchronize start  
                $client_syn_address_table_data = $jobsharing->getClientAddressData();
                $client_syn_address_table_data = json_decode($client_syn_address_table_data, true);
                $server_syn_address_table_data = $this->getServerAddressData();
                $server_syn_address_table_data = json_decode($server_syn_address_table_data, true);
                $server_job_countries = json_decode($server_syn_address_table_data['job_countries'], true);
                $client_job_countries = $client_syn_address_table_data['job_countries'];
                $table_countries = "countries";
				$syn_job_countries = $jobsharing->synchronizeClientServerTables($server_job_countries,$client_job_countries,$table_countries,$auth_key);
                $syn_job_countries_true = json_decode($syn_job_countries['return_server_value_' . $table_countries]);
                $syn_job_countries_update = json_decode($syn_job_countries['return_server_value_' . $table_countries]);

                if (is_array($syn_job_countries_update) AND !empty($syn_job_countries_update)) {
                    $message_sync.=JText::_('JS_COUNTRIES_SYNCHRONIZE_SUCESSFULLY') . '<br/>';
					if(isset($syn_job_countries['rejected_client_'.$table_countries]) AND $syn_job_countries['rejected_client_'.$table_countries]!=="") $message_sync.= JText::_('JS_FOLLOWING_COUNTRIES_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_countries['rejected_client_'.$table_countries]."<br/>";
					$update_new_countries = $jobsharing->updateClientServerTables($syn_job_countries_update,$table_countries);
					//$update_new_countries = $model->updateJobCountries($syn_job_countries_update);
					if($update_new_countries==true) $message_sync.=JText::_('JS_UPDATE_COUNTRIES_SYNCHRONIZE_SUCESSFULLY').'<br/>';
					else $message_sync.='<span style="color:red;">'.JText::_('JS_ERROR_UPDATE_COUNTRIES_SYNCHRONIZE_SUCESSFULLY').'</span><br/>';
				}elseif($syn_job_countries_true==true){
					$message_sync.=JText::_('JS_COUNTRIES_SYNCHRONIZE_SUCESSFULLY').'<br/>';
					if(isset($syn_job_countries['rejected_client_'.$table_countries]) AND $syn_job_countries['rejected_client_'.$table_countries]!=="") $message_sync.= JText::_('JS_FOLLOWING_COUNTRIES_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_countries['rejected_client_'.$table_countries]."<br/>";
                }elseif ($syn_job_countries == false) {
                    $message_sync.='<span style="color:red;">' . JText::_('JS_ERROR_COUNTRIES_SYNCHRONIZATION') . '</span><br/>';
                }

                $server_job_states = json_decode($server_syn_address_table_data['job_states'], true);
                $client_job_states = $client_syn_address_table_data['job_states'];
                $table_states = "states";
				$syn_job_states = $jobsharing->synchronizeClientServerTables($server_job_states,$client_job_states,$table_states,$auth_key);

				$syn_job_states_true=json_decode($syn_job_states['return_server_value_'.$table_states]);

				$syn_job_states_update=json_decode($syn_job_states['return_server_value_'.$table_states]);

				if(is_array($syn_job_states_update) AND !empty($syn_job_states_update)){
					$message_sync.=JText::_('JS_STATES_SYNCHRONIZE_SUCESSFULLY').'<br/>';
					if(isset($syn_job_states['rejected_client_'.$table_states]) AND $syn_job_states['rejected_client_'.$table_states]!=="") $message_sync.= JText::_('JS_FOLLOWING_STATES_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_states['rejected_client_'.$table_states]."<br/>";
					$update_new_states = $jobsharing->updateClientServerTables($syn_job_states_update,$table_states);
					//$update_new_states = $model->updateJobStates($syn_job_states_update);
					if($update_new_states==true) $message_sync.=JText::_('JS_UPDATE_STATES_SYNCHRONIZE_SUCESSFULLY').'<br/>';
					else $message_sync.='<span style="color:red;">'.JText::_('JS_ERROR_UPDATE_STATES_SYNCHRONIZE_SUCESSFULLY').'</span><br/>';
				}elseif($syn_job_states_true==true){
                    $message_sync.=JText::_('JS_STATES_SYNCHRONIZE_SUCESSFULLY') . '<br/>';
                    if (isset($syn_job_states['rejected_client_' . $table_states]) AND $syn_job_states['rejected_client_' . $table_states] !== "")
					if(isset($syn_job_states['rejected_client_'.$table_states]) AND $syn_job_states['rejected_client_'.$table_states]!=="") $message_sync.= JText::_('JS_FOLLOWING_STATES_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_states['rejected_client_'.$table_states]."<br/>";
                }elseif ($syn_job_states == false) {
                    $message_sync.='<span style="color:red;">' . JText::_('JS_ERROR_STATES_SYNCHRONIZATION') . '</span><br/>';
                }

				//$server_job_cities = json_decode($server_syn_address_table_data['job_cities'],true);
				$server_job_cities="";

				$client_job_cities = $client_syn_address_table_data['job_cities'];
				//$client_job_cities="";

				$table_cities ="cities";

				$syn_job_cities = $jobsharing->synchronizeClientServerTables($server_job_cities,$client_job_cities,$table_cities,$auth_key);
				

				$syn_job_cities_true=json_decode($syn_job_cities['return_server_value_'.$table_cities]);

				$syn_job_cities_update=json_decode($syn_job_cities['return_server_value_'.$table_cities]);

				if(is_array($syn_job_cities_update) AND !empty($syn_job_cities_update)){
					$message_sync.=JText::_('JS_CITIES_SYNCHRONIZE_SUCESSFULLY').'<br/>';
					if(isset($syn_job_cities['rejected_client_'.$table_cities]) AND $syn_job_cities['rejected_client_'.$table_cities]!=="") $message_sync.= JText::_('JS_FOLLOWING_CITIES_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_cities['rejected_client_'.$table_cities]."<br/>";
                    $update_new_cities = $jobsharing->updateClientServerTables($syn_job_cities_update, $table_cities);
                    //$update_new_cities = $model->updateJobCities($syn_job_cities_update);
                    if ($update_new_cities == true)
					if($update_new_cities==true) $message_sync.=JText::_('JS_UPDATE_CITIES_SYNCHRONIZE_SUCESSFULLY').'<br/>';
					else $message_sync.='<span style="color:red;">'.JText::_('JS_ERROR_UPDATE_CITIES_SYNCHRONIZE_SUCESSFULLY').'</span><br/>';
				}elseif($syn_job_cities_true==true){
					$message_sync.=JText::_('JS_CITIES_SYNCHRONIZE_SUCESSFULLY').'<br/>';
					if(isset($syn_job_cities['rejected_client_'.$table_cities]) AND $syn_job_cities['rejected_client_'.$table_cities]!=="") $message_sync.= JText::_('JS_FOLLOWING_CITIES_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_cities['rejected_client_'.$table_cities]."<br/>";
				}elseif($syn_job_cities==false){
					$message_sync.='<span style="color:red;">'.JText::_('JS_ERROR_CITIES_SYNCHRONIZATION').'</span><br/>';
				}	

				// Address Data synchronize end  


				$client_job_companies = $client_default_table_data['job_companies'];
				$syn_job_companies = $jobsharing->synchronizeClientServerCompanies($client_job_companies,$auth_key);

				$syn_job_companies_true=json_decode($syn_job_companies['return_server_value_companies']);
				if($syn_job_companies_true==true){
					$message_sync.=JText::_('JS_COMPANIES_SYNCHRONIZE_SUCESSFULLY').'<br/>';
					if(isset($syn_job_companies['rejected_client_companies']) AND $syn_job_companies['rejected_client_companies']!=="") $message_sync.= JText::_('JS_FOLLOWING_COMPANIES_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_companies['rejected_client_companies']."<br/>";
                }
                elseif ($syn_job_companies == false) {
                    $message_sync.='<span style="color:red;">' . JText::_('JS_ERROR_COMPANIES_SYNCHRONIZATION') . '</span><br/>';
                }

                $client_job_departments = $client_default_table_data['job_departments'];
                $syn_job_departments = $jobsharing->synchronizeClientServerDepartment($client_job_departments, $auth_key);
                
                $syn_job_departments_true = json_decode($syn_job_departments['return_server_value_departments']);

                if ($syn_job_departments_true == true) {
                    $message_sync.=JText::_('JS_DEPARTMENTS_SYNCHRONIZE_SUCESSFULLY') . '<br/>';
					if(isset($syn_job_departments['rejected_client_departments']) AND $syn_job_departments['rejected_client_departments']!=="") $message_sync.= JText::_('JS_FOLLOWING_DEPARTMENTS_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_departments['rejected_client_departments']."<br/>";
                }
                elseif ($syn_job_departments == false) {
                    $message_sync.='<span style="color:red;">' . JText::_('JS_ERROR_DEPARTMENTS_SYNCHRONIZATION') . '</span><br/>';
                }

                $client_job_jobs = $client_default_table_data['job_jobs'];
                $syn_job_jobs = $jobsharing->synchronizeClientServerJobs($client_job_jobs, $auth_key);
                $syn_job_jobs_true = json_decode($syn_job_jobs['return_server_value_jobs']);

                if ($syn_job_jobs_true == true) {
                    $message_sync.=JText::_('JS_JOBS_SYNCHRONIZE_SUCESSFULLY') . '<br/>';
                    $message_sync.=JText::_('JS_JOBS_USERFIELDS_SYNCHRONIZE_SUCESSFULLY') . '<br/>';
					if(isset($syn_job_jobs['rejected_client_jobs']) AND $syn_job_jobs['rejected_client_jobs']!=="") $message_sync.= JText::_('JS_FOLLOWING_JOBS_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_jobs['rejected_client_jobs']."<br/>";
                }
                elseif ($syn_job_jobs == false) {
                    $message_sync.='<span style="color:red;">' . JText::_('JS_ERROR_JOBS_SYNCHRONIZATION') . '</span><br/>';
                    $message_sync.='<span style="color:red;">' . JText::_('JS_ERROR_JOBS_USERFIELDS_SYNCHRONIZATION') . '</span><br/>';
                }

                $client_job_resume = $client_default_table_data['job_resume'];
                $syn_job_resume = $jobsharing->synchronizeClientServerResume($client_job_resume, $auth_key);
                $syn_job_resume_true = json_decode($syn_job_resume['return_server_value_resume']);

                if ($syn_job_resume_true == true) {
                    $message_sync.=JText::_('JS_RESUME_SYNCHRONIZE_SUCESSFULLY') . '<br/>';
					if(isset($syn_job_resume['rejected_client_resume']) AND $syn_job_resume['rejected_client_resume']!=="") $message_sync.= JText::_('JS_FOLLOWING_RESUME_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_resume['rejected_client_resume']."<br/>";
                }
                elseif ($syn_job_resume == false) {
                    $message_sync.='<span style="color:red;">' . JText::_('JS_ERROR_RESUME_SYNCHRONIZATION') . '</span><br/>';
                }
                $client_job_coverletters = $client_default_table_data['job_coverletter'];
                $syn_job_coverletters = $jobsharing->synchronizeClientServerCoverLetters($client_job_coverletters, $auth_key);
                $syn_job_coverletters_true = json_decode($syn_job_coverletters['return_server_value_coverletters']);

                if ($syn_job_coverletters_true == true) {
                    $message_sync.=JText::_('JS_COVERLETTERS_SYNCHRONIZE_SUCESSFULLY') . '<br/>';
					if(isset($syn_job_coverletters['rejected_client_coverletters']) AND $syn_job_coverletters['rejected_client_coverletters']!=="") $message_sync.= JText::_('JS_FOLLOWING_COVERLETTERS_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_coverletters['rejected_client_coverletters']."<br/>";
				} 
                elseif ($syn_job_coverletters == false) {
                    $message_sync.='<span style="color:red;">' . JText::_('JS_ERROR_COVERLETTERS_SYNCHRONIZATION') . '</span><br/>';
                }

                $client_job_jobapply = $client_default_table_data['job_jobapply'];
                $syn_job_jobapply = $jobsharing->synchronizeClientServerJobapply($client_job_jobapply, $auth_key);
                $syn_job_jobapply_true = json_decode($syn_job_jobapply['return_server_value_jobapply']);

                if ($syn_job_jobapply_true == true) {
                    $message_sync.=JText::_('JS_JOBAPPLY_SYNCHRONIZE_SUCESSFULLY') . '<br/>';
				} 
				elseif($syn_job_jobapply==false){
                    $message_sync.='<span style="color:red;">' . JText::_('JS_ERROR_JOBAPPLY_SYNCHRONIZATION') . '</span><br/>';
                }

                $client_resume_resumerating = $client_default_table_data['resume_resumerating'];
                $syn_resume_resumerating = $jobsharing->synchronizeClientServerResumeRating($client_resume_resumerating, $auth_key);
                $syn_resume_resumerating_true = json_decode($syn_resume_resumerating['return_server_value_resumerating']);

                if ($syn_resume_resumerating_true == true) {
                    $message_sync.=JText::_('JS_RESUME_RATING_SYNCHRONIZE_SUCESSFULLY') . '<br/>';
				} 
				elseif($syn_resume_resumerating==false){
					$message_sync.='<span style="color:red;">'.JText::_('JS_ERROR_RESUME_RATING_SYNCHRONIZATION').'</span><br/>';
                }

                $client_job_folders = $client_default_table_data['job_folders'];
                $syn_job_folders = $jobsharing->synchronizeClientServerFolders($client_job_folders, $auth_key);
                $syn_job_folders_true = json_decode($syn_job_folders['return_server_value_folders']);

                if ($syn_job_folders_true == true) {
                    $message_sync.=JText::_('JS_FOLDERS_SYNCHRONIZE_SUCESSFULLY') . '<br/>';
					if(isset($syn_job_folders['rejected_client_folders']) AND $syn_job_folders['rejected_client_folders']!=="") $message_sync.= JText::_('JS_FOLLOWING_FOLDERS_ARE_REJECTED_DUE_TO_IMPROPER_NAME').$syn_job_folders['rejected_client_folders']."<br/>";
                }
                elseif ($syn_job_folders == false) {
                    $message_sync.='<span style="color:red;">' . JText::_('JS_ERROR_FOLDERS_SYNCHRONIZATION') . '</span><br/>';
                }

                $client_job_folderesumes = $client_default_table_data['job_folderresumes'];
                $syn_job_folderresumes = $jobsharing->synchronizeClientServerFolderResumes($client_job_folderesumes, $auth_key);
                $syn_job_folderresumes_true = json_decode($syn_job_folderresumes['return_server_value_folderresumes']);

                if ($syn_job_folderresumes_true == true) {
                    $message_sync.=JText::_('JS_FOLDER_RESUME_SYNCHRONIZE_SUCESSFULLY') . '<br/>';
				} 
				elseif($syn_job_folderresumes==false){
                    $message_sync.='<span style="color:red;">' . JText::_('JS_ERROR_FOLDER_RESUME_SYNCHRONIZATION') . '</span><br/>';
                }

                $client_job_messages = $client_default_table_data['job_messages'];
                $syn_job_messages = $jobsharing->synchronizeClientServerMessages($client_job_messages, $auth_key);
                $syn_job_messages_true = json_decode($syn_job_messages['return_server_value_messages']);

                if ($syn_job_messages_true == true) {
                    $message_sync.=JText::_('JS_MESSAGES_SYNCHRONIZE_SUCESSFULLY') . '<br/>';
				} 
				elseif($syn_job_messages==false){
                    $message_sync.='<span style="color:red;">' . JText::_('JS_ERROR_MESSAGES_SYNCHRONIZATION') . '</span><br/>';
                }

                $client_job_alert = $client_default_table_data['job_jobalertsetting'];
                $syn_job_alert = $jobsharing->synchronizeClientServerAlert($client_job_alert, $auth_key);
                $syn_job_alert_true = json_decode($syn_job_alert['return_server_value_jobalertsetting']);

                if ($syn_job_alert_true == true) {
                    $message_sync.=JText::_('JS_JOB_ALERT_SYNCHRONIZE_SUCESSFULLY') . '<br/>';
				} 
				elseif($syn_job_alert==false){
                    $message_sync.='<span style="color:red;">' . JText::_('JS_ERROR_JOB_ALERT_SYNCHRONIZATION') . '</span><br/>';
                }
                
                $session = &JFactory::getSession();
                $session->set('synchronizedatamessage', $message_sync);
                $link = 'index.php?option=com_jsjobs&c=jsjobs&task=view&layout=jobshare';
                $msg=JText::_('JS_SYNCHRONIZE_COMPLETE_SUCESSFULLY');
                $this->setRedirect($link, $msg);
            } else {
                $msg = JText ::_('JS_PROBLEM_ACTIVE_JOB_SHARING_SERVICE_PLEASE_TRY_AGAIN_LATER');
                $link = 'index.php?option=com_jsjobs&c=jsjobs&task=view&layout=jobshare';
                $this->setRedirect($link, $msg);
            }
        } else {
            $msg = JText ::_('JS_PROBLEM_ACTIVE_JOB_SHARING_SERVICE_PLEASE_TRY_AGAIN_LATER');
            $link = 'index.php?option=com_jsjobs&c=jsjobs&task=view&layout=jobshare';
            $this->setRedirect($link, $msg);
        }
		
	}

    function updateclientauthenticationkey($clientkey) {
        $user = & JFactory::getUser();
        $uid = $user->id;
        $key = $clientkey;
        $event = "requestjobsharing";
        $eventtype = "requestjobsharing";
		$messagetype = "Sucessfully";
        $data = array();
        $data['uid'] = $uid;
        $data['event'] = $event;
        $data['eventtype'] = $eventtype;
        $data['message'] = "Admin request for the JobSharingService and the key is" . ' "' . $key . '"';
        $data['messagetype'] = $messagetype;
        $data['datetime'] = date('Y-m-d H:i:s');

        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $jobsharing = $this->getModel('jobsharing', 'JSJobsModel');
        $returnvalue = $jobsharing->updateClientAuthenticationKey($messagetype, $key);
        $jobsharing->writeJobSharingLog($data);
        if ($returnvalue == 1) {
            $result = JText::_('JS_JOB_SHARING_SERVICE_HAS_BEEN_ACTIVE_YOUR_ACTIVATION_KEY_IS') . "'" . $key . "'";
            return $result;
        } elseif ($returnvalue == 0) {
            $msg = JText::_('JS_YOUR_ACTIVATION_KEY_IS_GENERATE') . " ' " . $key . " ' " . JText::_('JS_BUT_NOT_UPDATE_PLEASE_TRY_AGAIN_LATER');
        } elseif ($returnvalue == 2) {
            $msg = JText::_('JS_PROBLEM_GENERATE_ACTIVATION_KEY');
        } elseif ($returnvalue == 3) {
            $msg = JText::_('JS_ERROR') . ':   ' . $key;
        }
        $link = 'index.php?option=com_jsjobs&c=jsjobs&task=view&layout=jobshare';
        $this->setRedirect($link, $msg);
    }

    function getServerDefaultTables() {
        $fortask = "synchronizedefaulttables";
        $jsondata = JRequest::getVar('data');
        $jobsharing = $this->getModel('jobsharing', 'JSJobsModel');
        $returnvalue = $jobsharing->getAllServerDefaultTables('', $fortask);
        return $returnvalue;
    }

    function getServerAddressData() {
        $fortask = "synchronizeaddressdata";
        $jsondata = JRequest::getVar('data');
        $jobsharing = $this->getModel('jobsharing', 'JSJobsModel');
        $returnvalue = $jobsharing->getAllServerAddressData('', $fortask);
        return $returnvalue;
    }

    function editsubcategories() {
        JRequest :: setVar('layout', 'formsubcategory');
        JRequest :: setVar('view', 'application');
        $this->display();
    }

    function editjobtype() {
        JRequest :: setVar('layout', 'formjobtype');
        JRequest :: setVar('view', 'application');
        $this->display();
    }

    function edijobstatus() {
        JRequest :: setVar('layout', 'formjobstatus');
        JRequest :: setVar('view', 'application');
        $this->display();
    }

    function edijobshift() {
        JRequest :: setVar('layout', 'formshift');
        JRequest :: setVar('view', 'application');
        $this->display();
    }

    function editjobhighesteducation() {
        JRequest :: setVar('layout', 'formhighesteducation');
        JRequest :: setVar('view', 'application');
        $this->display();
    }

    function editjobage() {
        JRequest :: setVar('layout', 'formages');
        JRequest :: setVar('view', 'application');
        $this->display();
    }

    function editcareerlevels() {
        JRequest :: setVar('layout', 'formcareerlevels');
        JRequest :: setVar('view', 'application');
        $this->display();
    }

    function editjobexperience() {
        JRequest :: setVar('layout', 'formexperience');
        JRequest :: setVar('view', 'application');
        $this->display();
    }

    function editjobcurrency() {
        JRequest :: setVar('layout', 'formcurrency');
        JRequest :: setVar('view', 'application');
        $this->display();
    }

    function editjobsalaryrange() {
        JRequest :: setVar('layout', 'formsalaryrange');
        JRequest :: setVar('view', 'application');
        $this->display();
    }

    function editjobsalaryrangrtype() {
        JRequest :: setVar('layout', 'formsalaryrangetype');
        JRequest :: setVar('view', 'application');
        $this->display();
    }

    function editjobcountry() {
        JRequest :: setVar('layout', 'formcountry');
        JRequest :: setVar('view', 'application');
        $this->display();
    }

    function editjobstate() {
        JRequest :: setVar('layout', 'formstate');
        JRequest :: setVar('view', 'application');
        $this->display();
    }

    function editjobcity() {
        JRequest :: setVar('layout', 'formcity');
        JRequest :: setVar('view', 'application');
        $this->display();
    }

    function editjobalert() {
        JRequest :: setVar('layout', 'formjobalert');
        JRequest :: setVar('view', 'application');
        $this->display();
    }

    function edit() {
        $cur_layout = $_SESSION['cur_layout'];
        JRequest :: setVar('view', 'application');
        JRequest :: setVar('hidemainmenu', 1);

		if ($cur_layout == 'categories') JRequest :: setVar('layout', 'formcategory');
        elseif (($cur_layout == 'companies') || ($cur_layout == 'companiesqueue'))
            JRequest :: setVar('layout', 'formcompany');
        elseif (($cur_layout == 'folders') || ($cur_layout == 'foldersqueue'))
            JRequest :: setVar('layout', 'formfolder');
        elseif (($cur_layout == 'jobs') || ($cur_layout == 'jobqueue'))
            JRequest :: setVar('layout', 'formjob');
        elseif (($cur_layout == 'empapps') || ($cur_layout == 'appqueue'))
            JRequest :: setVar('layout', 'formresume');
        elseif ($cur_layout == 'userfields')
            JRequest :: setVar('layout', 'formuserfield');
        elseif ($cur_layout == 'resumeuserfields')
            JRequest :: setVar('layout', 'formresumeuserfield');
		elseif ($cur_layout == 'roles')	JRequest :: setVar('layout', 'formrole');	
		elseif ($cur_layout == 'users')	JRequest :: setVar('layout', 'changerole');	
			
			
		elseif ($cur_layout == 'employerpackages')	JRequest :: setVar('layout', 'formemployerpackage');	
		elseif ($cur_layout == 'jobseekerpackages')	JRequest :: setVar('layout', 'formjobseekerpackage');
        elseif (($cur_layout == 'message_history') || ($cur_layout == 'messages') || ($cur_layout == 'jobappliedresume')) {
            JRequest :: setVar('layout', 'formmessage');
            JRequest :: setVar('sm', JRequest :: getVar('sm'));
                    }
		elseif (( $cur_layout == 'goldjobs')	|| ($cur_layout == 'goldjobsqueue')){
			if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];	else $c_id='';	
			if ($c_id == ''){$cids = JRequest :: getVar('cid', array (0), 'post', 'array'); $c_id= $cids[0]; }
			if ($c_id ==''){
				JRequest :: setVar('layout', 'addtogoldjobs');
				JRequest :: setVar('view', 'applications');
			}else JRequest :: setVar('layout', 'formgoldjob');
        }
        elseif (( $cur_layout == 'featuredjobs') || ($cur_layout == 'featuredjobsqueue')) {
			if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];	else $c_id='';	
			if ($c_id == ''){$cids = JRequest :: getVar('cid', array (0), 'post', 'array'); $c_id= $cids[0]; }
            if ($c_id == '') {
                JRequest :: setVar('layout', 'addtofeaturedjobs');
                JRequest :: setVar('view', 'applications');
			}else JRequest :: setVar('layout', 'formfeaturedjob');
        }
        elseif (( $cur_layout == 'featuredcompanies') || ($cur_layout == 'featuredcompaniesqueue')) {
			if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];	else $c_id='';	
			if ($c_id == ''){$cids = JRequest :: getVar('cid', array (0), 'post', 'array'); $c_id= $cids[0]; }
			if ($c_id ==''){
				JRequest :: setVar('layout', 'addtofeaturedcompanies');
				JRequest :: setVar('view', 'applications');
			}else JRequest :: setVar('layout', 'formfeaturedcompany');
		}elseif( ( $cur_layout == 'goldcompanies') || ($cur_layout == 'goldcompaniesqueue') )	{
			if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];	else $c_id='';	
			if ($c_id == ''){$cids = JRequest :: getVar('cid', array (0), 'post', 'array'); $c_id= $cids[0]; }
			if ($c_id ==''){
				JRequest :: setVar('layout', 'addtogoldcompanies');
				JRequest :: setVar('view', 'applications');
			}else JRequest :: setVar('layout', 'formgoldcompany');
		}elseif( ( $cur_layout == 'featuredresumes') || ($cur_layout == 'featuredresumesqueue') )	{
			if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];	else $c_id='';	
			if ($c_id == ''){$cids = JRequest :: getVar('cid', array (0), 'post', 'array'); $c_id= $cids[0]; }
			if ($c_id ==''){
				JRequest :: setVar('layout', 'addtofeaturedresumes');
				JRequest :: setVar('view', 'applications');
			}else JRequest :: setVar('layout', 'formfeaturedresume');
		}elseif( ( $cur_layout == 'goldresumes') || ($cur_layout == 'goldresumesqueue') )	{
			if (isset($_GET['cid'][0])) 	$c_id= $_GET['cid'][0];	else $c_id='';	
			if ($c_id == ''){$cids = JRequest :: getVar('cid', array (0), 'post', 'array'); $c_id= $cids[0]; }
			if ($c_id ==''){
				JRequest :: setVar('layout', 'addtogoldresumes');
				JRequest :: setVar('view', 'applications');
			}else JRequest :: setVar('layout', 'formgoldresume');
		}					
        elseif (($cur_layout == 'departments') || ($cur_layout == 'departmentqueue'))
            JRequest :: setVar('layout', 'formdepartment');
        elseif ($cur_layout == 'jobseekerpaymenthistory')
            JRequest :: setVar('layout', 'assignpackage');
        elseif ($cur_layout == 'employerpaymenthistory')
            JRequest :: setVar('layout', 'assignpackage');
        $this->display();
    }

    function getgraphdata() {
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $returnvalue = $model->getGraphData();
        echo $returnvalue;
        JFactory::getApplication()->close();
    }

    function getmyforlders() {
        $user = & JFactory::getUser();
        $uid = $user->id;
        $jobid = JRequest::getVar('jobid');
        $resumeid = JRequest::getVar('resumeid');
        $applyid = JRequest::getVar('applyid');
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $returnvalue = $model->getMyFoldersAJAX($uid, $jobid, $resumeid, $applyid);
        echo $returnvalue;
        JFactory::getApplication()->close();
    }

    function getresumecomments() {
        $jobapplyid = JRequest::getVar('jobapplyid');
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $returnvalue = $model->getResumeCommentsAJAX($jobapplyid);
        echo $returnvalue;
        JFactory::getApplication()->close();
    }

    function mailtocandidate() {
        $user = & JFactory::getUser();
        $uid = $user->id;
        $resumeid = JRequest::getVar('resumeid');
        $jobapplyid = JRequest::getVar('jobapplyid');
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $returnvalue = $model->getMailForm($uid, $resumeid, $jobapplyid);
        echo $returnvalue;
        JFactory::getApplication()->close();
    }

    function sendtocandidate() {
        $val = json_decode(JRequest::getVar('val'), true);
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $returnvalue = $model->sendToCandidate($val);
        echo $returnvalue;
        JFactory::getApplication()->close();
    }

    function getresumedetail() {
        $user = & JFactory::getUser();
        $uid = $user->id;
        $jobid = JRequest::getVar('jobid');
        $resumeid = JRequest::getVar('resumeid');
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $returnvalue = $model->getResumeDetail($uid, $jobid, $resumeid);
        echo $returnvalue;
        JFactory::getApplication()->close();
    }

    /* STRAT EXPORT RESUMES */
    function exportallresume() {
        $jobid = JRequest::getVar('bd');
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $return_value = $model->setAllExport($jobid);
        if ($return_value == true) {
            // Push the report now!
            $msg = JText ::_('JS_RESUME_EXPORT');
            $name = 'export-resumes';
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=" . $name . ".xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            header("Lacation: excel.htm?id=yes");
            print $return_value;
            JFactory::getApplication()->close();
        } else {
            //echo $return_value ;
            $msg = JText ::_('JS_RESUME_NOT_EXPORT');
        }
        $link = 'index.php?option=com_jsjobs&c=jsjobs&task=view&layout=jobappliedresume&oi=' . $jobid;
        $this->setRedirect($link, $msg);
    }

    function exportresume() {
        $jobid = JRequest::getVar('bd');
        $resumeid = JRequest::getVar('rd');
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $return_value = $model->setExport($jobid, $resumeid);
        if ($return_value == true) {
            $msg = JText ::_('JS_RESUME_EXPORT');
            // Push the report now!
            $this->name = 'export-resume';
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=" . $this->name . ".xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            header("Lacation: excel.htm?id=yes");
            print $return_value;
            JFactory::getApplication()->close();
        } else {
            $msg = JText ::_('JS_RESUME_NOT_EXPORT');
        }
        $link = 'index.php?option=com_jsjobs&c=jsjobs&task=view&layout=jobappliedresume&oi=' . $jobid;
        $this->setRedirect($link, $msg);
    }
    /* END EXPORT RESUMES */

    function savejobalert() { //save savejobalert
        $data = JRequest:: get('post');
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $jobsharing = $this->getModel('jobsharing', 'JSJobsModel');

        $Itemid = JRequest::getVar('Itemid');
        $return_value = $model->storeJobAlertSetting();
        $link = 'index.php?option=com_jsjobs&c=jsjobs&task=view&layout=jobalert';
        if (is_array($return_value)) {
            if ($return_value['isjobalertstore'] == 1) {
                if ($return_value['status'] == "Job Alert Edit") {
                    $jobalertstatus = "ok";
                } elseif ($return_value['status'] == "Job Alert Add") {
                    $jobalertstatus = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Job Alert";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                if (isset($return_value['alertcities'])) {
                    $jobsharing->updateMultiCityServerid($return_value['alertcities'], 'jobalertcities');
                }

                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($jobalertstatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'jobalertsetting');
            } elseif ($return_value['isjobalertstore'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $jobalertstatus = "Data not post on server";
                } elseif ($return_value['status'] == "Job Alert Saving Error") {
                    $jobalertstatus = "Error Job Alert Saving";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $jobalertstatus = "Authentication Fail";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Job Alert";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($jobalertstatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'jobalertsetting');
            }
            $msg = JText :: _('JS_JOB_ALERT_SETTING_SAVED');
        } else {

            if ($return_value == 1) {
                $msg = JText :: _('JS_JOB_ALERT_SETTING_SAVED');
            } else if ($return_value == 2) {
                $msg = JText :: _('JS_FILL_REQ_FIELDS');
            } else if ($return_value == 3) {
                $msg = JText :: _('JS_EMAIL_ALREADY_EXIST');
            } else {
                $msg = JText :: _('JS_ERROR_SAVING_JOB_ALERT_SETTING');
            }
        }
        $this->setRedirect($link, $msg);
    }

    function unsubscribeJobAlertSetting() {
        $data = JRequest :: get('post');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $alertid = $cid[0];
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $jobsharing = $this->getModel('jobsharing', 'JSJobsModel');
        $return_value = $model->unSubscribeJobAlert($alertid);
        if (is_array($return_value)) {
            if ($return_value['isunsubjobalert'] == 1) {
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Unsubscribe Job Alert";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
            } elseif ($return_value['isunsubjobalert'] == 0) {
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Unsubscribe Job Alert";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
            }
            $msg = JText :: _('JS_YOU_UNSUBSCRIBE_SUCCESSFULLY_JOBALERT');
        } else {
            if ($return_value == 1) {
                $msg = JText :: _('JS_YOU_UNSUBSCRIBE_SUCCESSFULLY_JOBALERT');
            } else {
                $msg = JText :: _('JS_ERROR_UNSUBSCRIBE_JOBALERT');
            }
        }
        $link = 'index.php?option=com_jsjobs&c=jsjobs&task=view&layout=jobalert';
        $this->setRedirect($link, $msg);
    }

    function saveresumerating() {
        $user = & JFactory::getUser();
        $uid = $user->id;
        $ratingid = JRequest::getVar('ratingid');
        $jobid = JRequest::getVar('jobid');
        $resumeid = JRequest::getVar('resumeid');
        $newrating = JRequest::getVar('newrating');
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $returnvalue = $model->storeResumeRating($uid, $ratingid, $jobid, $resumeid, $newrating);
        echo $returnvalue;
        JFactory::getApplication()->close();
    }

    function saveresumefolder() { // save folder
        $data = JRequest :: get('post');
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $data['jobid'] = JRequest::getVar('jobid');
        $data['resumeid'] = JRequest::getVar('resumeid');
        $data['applyid'] = JRequest::getVar('applyid');
        $data['folderid'] = JRequest::getVar('folderid');
        $return_value = $model->storeFolderResume($data);
        $link = 'index.php?option=com_jsjobs&c=jsjobs&task=view&layout=jobappliedresume&oi=' . $data['jobid'];
        if ($return_value == 1) {
            $msg = JText :: _('JS_RESUME_SAVE_FOLDER');
        } elseif ($return_value == 3) {
            $msg = JText::_('JS_RESUME_ALREADY_EXISTS_IN_FOLDER');
        } else {
            $msg = JText :: _('JS_ERROR_SAVING_RESUME_FOLDER');
        }
        echo $msg;
        JFactory::getApplication()->close();
    }

    function saveresumecomments() { // save resume comments
        $data = array();
        $data['id'] = JRequest::getVar('jobapplyid');
        $data['resumeid'] = JRequest::getVar('resumeid');
        $data['comments'] = JRequest::getVar('comments');
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $return_value = $model->storeResumeComments($data);
        if ($return_value == 1) {
            $msg = JText :: _('JS_RESUME_COMMENTS_SAVE');
        } else {
            $msg = JText :: _('JS_ERROR_SAVING_RESUME_RESUME_COMMENTS');
        }
        echo $msg;
        JFactory::getApplication()->close();
    }

    function aappliedresumetabactions() {
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $data = JRequest :: get('post');
        $Itemid = JRequest::getVar('Itemid');
        if ($data['tab_action'] == 6)
            $needle_array = json_encode($data);
        $session = JFactory::getSession();
        $session->set('jsjobappliedresumefilter', $needle_array);
        $link = 'index.php?option=com_jsjobs&c=jsjobs&view=applications&layout=jobappliedresume&oi=' . $data['jobid'] . '&ta=' . $data['tab_action'] . '&Itemid=' . $Itemid;
        $this->setRedirect($link);
    }

    function actionresume() { //save shortlist candidate
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $user = & JFactory::getUser();
        $uid = $user->id;
        $data = JRequest :: get('post');
        $jobid = $data['jobid'];
        $resumeid = $data['resumeid'];
        if ($data['action'] == 1) { // short list
            $return_value = $model->storeShortListCandidatee($uid);
            if ($return_value == 1)
                $msg = JText :: _('JS_SHORT_LIST_CANDIDATE_SAVED');
            elseif ($return_value == 2)
                $msg = JText :: _('JS_FILL_REQ_FIELDS');
            elseif ($return_value == 3)
                $msg = JText :: _('JS_ALLREADY_SHORTLIST_THIS_CANDIDATE');
            else
                $msg = JText :: _($return_value . 'JS_ERROR_SAVING_SHORT_LIST_CANDIDATE');
            $link = 'index.php?option=com_jsjobs&c=jsjobs&view=applications&layout=jobappliedresume&oi=' . $jobid;
        }elseif ($data['action'] == 2) { // send message
            $link = 'index.php?option=com_jsjobs&c=jsjobs&view=application&layout=formmessage&bd=' . $data['jobid'] . '&rd=' . $data['resumeid'];
        }
        $this->setRedirect($link, $msg);
    }

    function listsearchaddressdata() {
        $data = JRequest::getVar('data');
        $val = JRequest::getVar('val');
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $returnvalue = $model->listSearchAddressData($data, $val);
        echo $returnvalue;
        JFactory::getApplication()->close();
    }

    function jobenforcedelete() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $jobsharing = & $this->getModel('jobsharing', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $jobid = $cid[0];
        $user = & JFactory::getUser();
        $uid = $user->id;
        $returnvalue = $model->jobEnforceDelete($jobid, $uid);
		if(is_array($returnvalue)){
				if($returnvalue['isjobdelete']==1){
					$logarray['uid']=$model->_uid;
					$logarray['referenceid']=$returnvalue['referenceid'];
					$logarray['eventtype']=$returnvalue['eventtype'];
					$logarray['message']=$returnvalue['message'];
					$logarray['event']="Delete Job Enforce";
					$logarray['messagetype']="Sucessfully";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$jobsharing->writeJobSharingLog($logarray);
				}elseif($returnvalue['isjobdelete']==-1){
					$logarray['uid']=$model->_uid;
					$logarray['referenceid']=$returnvalue['referenceid'];
					$logarray['eventtype']=$returnvalue['eventtype'];
					$logarray['message']=$returnvalue['message'];
					$logarray['event']="Delete Job Enforce";
					$logarray['messagetype']="Error";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$jobsharing->writeJobSharingLog($logarray);
				}
				$msg = JText :: _('JOB_DELETED');
		}else{
			if ($returnvalue == 1) {
				$msg = JText::_('JS_JOB_DELETED');
			} elseif ($returnvalue == 2) {
				$msg = JText::_('JS_ERROR_IN_DELETING_JOB');
			} elseif ($returnvalue == 3) {
				$msg = JText::_('JS_THIS_JOB_IS_NOT_OF_THIS_USER');
			}
		}	
        $link = 'index.php?option=com_jsjobs&task=view&layout=jobs';
        $this->setRedirect($link, $msg);
    }

    function companyenforcedelete() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $jobsharing = & $this->getModel('jobsharing', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $companyid = $cid[0];
        $user = & JFactory::getUser();
        $uid = $user->id;
        $return_value = $model->companyEnforceDelete($companyid, $uid);
		if(is_array($return_value)){
				if($return_value['iscompanydelete']==1){
					$logarray['uid']=$model->_uid;
					$logarray['referenceid']=$return_value['referenceid'];
					$logarray['eventtype']=$return_value['eventtype'];
					$logarray['message']=$return_value['message'];
					$logarray['event']="Delete Company Enforce";
					$logarray['messagetype']="Sucessfully";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$jobsharing->writeJobSharingLog($logarray);
				}elseif($$return_value['iscompanydelete']==-1){
					$logarray['uid']=$model->_uid;
					$logarray['referenceid']=$return_value['referenceid'];
					$logarray['eventtype']=$return_value['eventtype'];
					$logarray['message']=$return_value['message'];
					$logarray['event']="Delete Company Enforce";
					$logarray['messagetype']="Error";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$jobsharing->writeJobSharingLog($logarray);
				}
				$msg = JText :: _('JS_COMPANY_DELETED');
		}else{
			
			if ($return_value == 1) {
				$msg = JText::_('JS_COMPANY_DELETED');
			} elseif ($return_value == 2) {
				$msg = JText::_('JS_ERROR_IN_DELETING_COMPANY');
			} elseif ($return_value == 3) {
				$msg = JText::_('JS_THIS_COMPANY_IS_NOT_OF_THIS_USER');
			}
		}	
        $link = 'index.php?option=com_jsjobs&task=view&layout=companies';
        $this->setRedirect($link, $msg);
    }

    function folderenforcedelete() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $folderid = $cid[0];
        $user = & JFactory::getUser();
        $uid = $user->id;
        $return_value = $model->folderEnforceDelete($folderid, $uid);
        if ($return_value == 1) {
            $msg = JText::_('JS_FOLDER_DELETED');
        } elseif ($return_value == 2) {
            $msg = JText::_('JS_ERROR_IN_DELETING_FOLDER');
        } elseif ($return_value == 3) {
            $msg = JText::_('JS_THIS_FOLDER_IS_NOT_OF_THIS_USER');
        }
        $link = 'index.php?option=com_jsjobs&task=view&layout=folders';
        $this->setRedirect($link, $msg);
    }

    function resumeenforcedelete() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $jobsharing = & $this->getModel('jobsharing', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $resumeid = $cid[0];
        $user = & JFactory::getUser();
        $uid = $user->id;
        $return_value = $model->resumeEnforceDelete($resumeid, $uid);
		if(is_array($return_value)){
				if($return_value['isresumedelete']==1){
					$logarray['uid']=$model->_uid;
					$logarray['referenceid']=$return_value['referenceid'];
					$logarray['eventtype']=$return_value['eventtype'];
					$logarray['message']=$return_value['message'];
					$logarray['event']="Delete Resume Enforce";
					$logarray['messagetype']="Sucessfully";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$jobsharing->writeJobSharingLog($logarray);
				}elseif($return_value['isfolderdelete']==-1){
					$logarray['uid']=$model->_uid;
					$logarray['referenceid']=$return_value['referenceid'];
					$logarray['eventtype']=$return_value['eventtype'];
					$logarray['message']=$return_value['message'];
					$logarray['event']="Delete Resume Enforce";
					$logarray['messagetype']="Error";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$jobsharing->writeJobSharingLog($logarray);
				}
				$msg = JText :: _('JS_RESUME_DELETED');
		}else{
			if ($return_value == 1) {
				$msg = JText::_('JS_RESUME_DELETED');
			} elseif ($return_value == 2) {
				$msg = JText::_('JS_ERROR_IN_DELETING_RESUME');
			} elseif ($return_value == 3) {
				$msg = JText::_('JS_THIS_RESUME_IS_NOT_OF_THIS_USER');
			}
		}	
        $link = 'index.php?option=com_jsjobs&task=view&layout=empapps';
        $this->setRedirect($link, $msg);
    }

    function deletecategoryandsubcategory() {       // delete category and subcategory
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $returnvalue = $model->deleteCategoryAndSubcategory();
        if ($returnvalue == 1) {
            $msg = JText::_('JS_CATEGORY_AND_SUBCATEGORY_DELETED');
        } else {
            $msg = $returnvalue - 1 . ' ' . JText::_('JS_ERROR_CATEGORY_AND_SUBCATEGORY_COULD_NOT_DELETE');
        }
        $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=categories', $msg);
    }

    function companyapprove() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $jobsharing = & $this->getModel('jobsharing', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $companyid = $cid[0];
        $return_value = $model->companyApprove($companyid);
        if (is_array($return_value)) {
            if ($return_value['iscompanyapprove'] == 1) {
                if ($return_value['status'] == "Company Approve") {
                    $servercompanytatus = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Company Approve";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($servercompanytatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'companies');
            } elseif ($return_value['iscompanyapprove'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $servercompanytatus = "Data not post on server";
                } elseif ($return_value['status'] == "Company Approve Error") {
                    $servercompanytatus = "Error Company Approve";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $servercompanytatus = "Authentication Fail";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Company Approve";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($servercompanytatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'companies');
            }
            $msg = JText :: _('JS_COMPANY_APPROVED');
        } else {
            if ($return_value == 1) {
                $msg = JText::_('JS_COMPANY_APPROVED');
            }
            else
                $msg = JText::_('JS_ERROR_IN_APPROVING_COMPANY');
        }
        $link = 'index.php?option=com_jsjobs&task=view&layout=companiesqueue';
        $this->setRedirect($link, $msg);
    }

    function companyreject() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $jobsharing = & $this->getModel('jobsharing', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $companyid = $cid[0];

        $return_value = $model->companyReject($companyid);
        if (is_array($return_value)) {
            if ($return_value['iscompanyreject'] == 1) {
                if ($return_value['status'] == "Company Reject") {
                    $servercompanytatus = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Company Reject";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($servercompanytatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'companies');
            } elseif ($return_value['iscompanyreject'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $servercompanytatus = "Data not post on server";
                } elseif ($return_value['status'] == "Company Reject Error") {
                    $servercompanytatus = "Error Company Reject";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $servercompanytatus = "Authentication Fail";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Company Reject";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($servercompanytatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'companies');
            }
            $msg = JText :: _('JS_COMPANY_REJECTED');
        } else {
            if ($return_value == 1) {
                $msg = JText::_('JS_COMPANY_REJECTED');
            }
            else
                $msg = JText::_('JS_ERROR_IN_REJECTING_COMPANY');
        }

        $link = 'index.php?option=com_jsjobs&task=view&layout=companiesqueue';
        $this->setRedirect($link, $msg);
    }

    function folderapprove() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $folderid = $cid[0];
        $return_value = $model->folderApprove($folderid);
        if ($return_value == 1) {
            $msg = JText::_('JS_FOLDER_APPROVED');
        }
        else
            $msg = JText::_('JS_ERROR_IN_APPROVING_FOLDER');

        $link = 'index.php?option=com_jsjobs&task=view&layout=foldersqueue';
        $this->setRedirect($link, $msg);
    }

    function folderreject() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $folderid = $cid[0];
        $return_value = $model->folderReject($folderid);
        if ($return_value == 1) {
            $msg = JText::_('JS_FOLDER_REJECTED');
        }
        else
            $msg = JText::_('JS_ERROR_IN_REJECTING_FOLDER');

        $link = 'index.php?option=com_jsjobs&task=view&layout=foldersqueue';
        $this->setRedirect($link, $msg);
    }

    function departmentapprove() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $jobsharing = & $this->getModel('jobsharing', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $departmentid = $cid[0];
        $return_value = $model->departmentApprove($departmentid);
        if (is_array($return_value)) {
            if ($return_value['isdepartmentapprove'] == 1) {
                if ($return_value['status'] == "Department Approve") {
                    $serverdepartmentstatus = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Department Approve";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverdepartmentstatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'departments');
            } elseif ($return_value['isdepartmentapprove'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $serverdepartmentstatus = "Data not post on server";
                } elseif ($return_value['status'] == "Department Approve Error") {
                    $serverdepartmentstatus = "Error Department Approve";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $serverdepartmentstatus = "Authentication Fail";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Department Approve";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverdepartmentstatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'departments');
            }
            $msg = JText :: _('JS_DEPARTMENT_APPROVED');
        } else {
            if ($return_value == 1) {
                $msg = JText::_('JS_DEPARTMENT_APPROVED');
            } else {
                $msg = JText::_('JS_ERROR_IN_APPROVING_DEPARTMENT');
            }
        }
        $link = 'index.php?option=com_jsjobs&task=view&layout=departmentqueue';
        $this->setRedirect($link, $msg);
    }

    function departmentreject() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $jobsharing = & $this->getModel('jobsharing', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $departmentid = $cid[0];
        $return_value = $model->departmentReject($departmentid);
        if (is_array($return_value)) {
            if ($return_value['isdepartmentreject'] == 1) {
                if ($return_value['status'] == "Department Reject") {
                    $serverdepartmentstatus = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Department Reject";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverdepartmentstatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'departments');
            } elseif ($return_value['isdepartmentreject'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $serverdepartmentstatus = "Data not post on server";
                } elseif ($return_value['status'] == "Department Reject Error") {
                    $serverdepartmentstatus = "Error Department Reject";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $serverdepartmentstatus = "Authentication Fail";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Department Reject";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverdepartmentstatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'departments');
            }
            $msg = JText :: _('JS_DEPARTMENT_REJECTED');
        } else {
            if ($return_value == 1) {
                $msg = JText::_('JS_DEPARTMENT_REJECTED');
            } else {
                $msg = JText::_('JS_ERROR_IN_REJECTING_DEPARTMENT');
            }
        }
        $link = 'index.php?option=com_jsjobs&task=view&layout=departmentqueue';
        $this->setRedirect($link, $msg);
    }

    function featuredcompanyapprove() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $companyid = $cid[0];
        $return_value = $model->featuredCompanyApprove($companyid);
        if ($return_value == 1) {
            $msg = JText::_('JS_FETAURED_COMPANY_APPROVED');
        }
        else
            $msg = JText::_('JS_ERROR_IN_APPROVING_FEATURED_COMPANY');

        $link = 'index.php?option=com_jsjobs&task=view&layout=featuredcompanies';
        $this->setRedirect($link, $msg);
    }

    function featuredcompanyreject() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $companyid = $cid[0];

        $return_value = $model->featuredCompanyReject($companyid);
        if ($return_value == 1) {
            $msg = JText::_('JS_FEATURED_COMPANY_REJECTED');
        }
        else
            $msg = JText::_('JS_ERROR_IN_REJECTING_FEATURED_COMPANY');

        $link = 'index.php?option=com_jsjobs&task=view&layout=featuredcompanies';
        $this->setRedirect($link, $msg);
    }

    function goldcompanyapprove() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $companyid = $cid[0];
        $return_value = $model->goldCompanyApprove($companyid);
        if ($return_value == 1) {
            $msg = JText::_('JS_GOLD_COMPANY_APPROVED');
        }
        else
            $msg = JText::_('JS_ERROR_IN_APPROVING_GOLD_COMPANY');

        $link = 'index.php?option=com_jsjobs&task=view&layout=goldcompanies';
        $this->setRedirect($link, $msg);
    }

    function goldcompanyreject() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $companyid = $cid[0];

        $return_value = $model->goldCompanyReject($companyid);
        if ($return_value == 1) {
            $msg = JText::_('JS_GOLD_COMPANY_REJECTED');
        }
        else
            $msg = JText::_('JS_ERROR_IN_REJECTING_GOLD_COMPANY');

        $link = 'index.php?option=com_jsjobs&task=view&layout=goldcompanies';
        $this->setRedirect($link, $msg);
    }

    function featuredjobapprove() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $jobid = $cid[0];
        $return_value = $model->featuredJobApprove($jobid);
        if ($return_value == 1) {
            $msg = JText::_('JS_FEATURED_JOB_APPROVED');
        }
        else
            $msg = JText::_('JS_ERROR_IN_APPROVING_FEATURED_JOB');

        $link = 'index.php?option=com_jsjobs&task=view&layout=featuredjobs';
        $this->setRedirect($link, $msg);
    }

    function featuredjobreject() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $jobid = $cid[0];
        $return_value = $model->featuredJobReject($jobid);
        if ($return_value == 1) {
            $msg = JText::_('JS_FEATURED_JOB_REJECTED');
        }
        else
            $msg = JText::_('JS_ERROR_IN_REJECTING_FEATURED_JOB');

        $link = 'index.php?option=com_jsjobs&task=view&layout=featuredjobs';
        $this->setRedirect($link, $msg);
    }

    function goldjobapprove() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $jobid = $cid[0];
        $return_value = $model->goldJobApprove($jobid);
        if ($return_value == 1) {
            $msg = JText::_('JS_GOLD_JOB_APPROVED');
        }
        else
            $msg = JText::_('JS_ERROR_IN_APPROVING_GOLD_JOB');

        $link = 'index.php?option=com_jsjobs&task=view&layout=goldjobs';
        $this->setRedirect($link, $msg);
    }

    function goldjobreject() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $jobid = $cid[0];
        $return_value = $model->goldJobReject($jobid);
        if ($return_value == 1) {
            $msg = JText::_('JS_GOLD_JOB_REJECTED');
        }
        else
            $msg = JText::_('JS_ERROR_IN_REJECTING_GOLD_JOB');

        $link = 'index.php?option=com_jsjobs&task=view&layout=goldjobs';
        $this->setRedirect($link, $msg);
    }

    function featuredresumeapprove() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $resumeid = $cid[0];
        $return_value = $model->featuredResumeApprove($resumeid);
        if ($return_value == 1) {
            $msg = JText::_('JS_GOLD_JOB_APPROVED');
        }
        else
            $msg = JText::_('JS_ERROR_IN_APPROVING_GOLD_JOB');

        $link = 'index.php?option=com_jsjobs&task=view&layout=featuredresumes';
        $this->setRedirect($link, $msg);
    }

    function featuredresumereject() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $resumeid = $cid[0];
        $return_value = $model->featuredResumeReject($resumeid);
        if ($return_value == 1) {
            $msg = JText::_('JS_FEATURED_RESUME_REJECTED');
        }
        else
            $msg = JText::_('JS_ERROR_IN_REJECTING_FEATURED_RESUME');

        $link = 'index.php?option=com_jsjobs&task=view&layout=featuredresumes';
        $this->setRedirect($link, $msg);
    }

    function goldresumeapprove() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $resumeid = $cid[0];
        $return_value = $model->goldResumeApprove($resumeid);
        if ($return_value == 1) {
            $msg = JText::_('JS_GOLD_RESUME_APPROVED');
        }
        else
            $msg = JText::_('JS_ERROR_IN_APPROVING_GOLD_RESUME');

        $link = 'index.php?option=com_jsjobs&task=view&layout=goldresumes';
        $this->setRedirect($link, $msg);
    }

    function goldresumereject() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $resumeid = $cid[0];
        $return_value = $model->goldResumeReject($resumeid);
        if ($return_value == 1) {
            $msg = JText::_('JS_GOLD_RESUME_REJECTED');
        }
        else
            $msg = JText::_('JS_ERROR_IN_REJECTING_GOLD_RESUME');

        $link = 'index.php?option=com_jsjobs&task=view&layout=goldresumes';
        $this->setRedirect($link, $msg);
    }

    function publishcategories() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $id = $cid[0];
        $return_value = $model->categoryChangeStatus($id, 1);
        if ($return_value != 1)
            $msg = JText::_('JS_ERROR_OCCUR');

        $link = 'index.php?option=com_jsjobs&task=view&layout=categories';
        $this->setRedirect($link, $msg);
    }

    function unpublishcategories() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $id = $cid[0];
        $return_value = $model->categoryChangeStatus($id, 0);
        if ($return_value != 1)
            $msg = JText::_('JS_ERROR_OCCUR');

        $link = 'index.php?option=com_jsjobs&task=view&layout=categories';
        $this->setRedirect($link, $msg);
    }

    function publishsubcategories() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $id = $cid[0];
        $return_value = $model->subCategoryChangeStatus($id, 1);
        if ($return_value != 1)
            $msg = JText::_('JS_ERROR_OCCUR');

        $session = JFactory::getSession();
        $categoryid = $session->set('sub_categoryid');
        $link = 'index.php?option=com_jsjobs&task=view&layout=subcategories&cd=' . $categoryid;
        $this->setRedirect($link, $msg);
    }

    function unpublishsubcategories() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $id = $cid[0];
        $return_value = $model->subCategoryChangeStatus($id, 0);
        if ($return_value != 1)
            $msg = JText::_('JS_ERROR_OCCUR');

        $session = JFactory::getSession();
        $categoryid = $session->set('sub_categoryid');
        $link = 'index.php?option=com_jsjobs&task=view&layout=subcategories&cd=' . $categoryid;
        $this->setRedirect($link, $msg);
    }

    function publishfolder() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $jobsharing = & $this->getModel('jobsharing', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $id = $cid[0];
        $return_value = $model->folderChangeStatus($id, 1);
        if (is_array($return_value)) {
            if ($return_value['isfolderapprove'] == 1) {
                if ($return_value['status'] == "Folder Approve") {
                    $serverfolderstatus = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Folder Approve";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverfolderstatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'folders');
            } elseif ($return_value['isfolderapprove'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $serverfolderstatus = "Data not post on server";
                } elseif ($return_value['status'] == "Folder Approve Error") {
                    $serverfolderstatus = "Error Approve Folder";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $serverfolderstatus = "Authentication Fail";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Folder Approve";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverfolderstatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'folders');
            }
            $msg = JText :: _('JS_FOLDER_APPROVED');
        } else {
            if ($return_value != 1) {
                $msg = JText::_('JS_ERROR_OCCUR');
            }
            $msg = JText::_('JS_FOLDER_APPROVED');
        }
        $link = 'index.php?option=com_jsjobs&task=view&layout=foldersqueue';
        $this->setRedirect($link, $msg);
    }

    function unpublishfolder() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $jobsharing = & $this->getModel('jobsharing', 'JSJobsModel');

        $cid = JRequest::getVar('cid', array(), '', 'array');
        $id = $cid[0];
        $return_value = $model->folderChangeStatus($id, -1);
        if (is_array($return_value)) {
            if ($return_value['isfolderreject'] == 1) {
                if ($return_value['status'] == "Folder Reject") {
                    $serverfolderstatus = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Folder Reject";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverfolderstatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'folders');
            } elseif ($return_value['isfolderreject'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $serverfolderstatus = "Data not post on server";
                } elseif ($return_value['status'] == "Folder Reject Error") {
                    $serverfolderstatus = "Error Reject Folder";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $serverfolderstatus = "Authentication Fail";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Folder Reject";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverfolderstatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'folders');
            }
            $msg = JText :: _('JS_FOLDER_REJECTED');
        } else {
            if ($return_value != 1) {
                $msg = JText::_('JS_ERROR_OCCUR');
            }
            $msg = JText::_('JS_FOLDER_REJECTED');
        }
        $link = 'index.php?option=com_jsjobs&task=view&layout=foldersqueue';
        $this->setRedirect($link, $msg);
    }

    function publishmessages() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $jobsharing = & $this->getModel('jobsharing', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $id = $cid[0];
        $return_value = $model->messageChangeStatus($id, 1);
        if (is_array($return_value)) {
            if ($return_value['ismessageapprove'] == 1) {
                if ($return_value['status'] == "Message Approve") {
                    $servermessagestatus = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Message Approve";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($servermessagestatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'messages');
            } elseif ($return_value['ismessageapprove'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $servermessagestatus = "Data not post on server";
                } elseif ($return_value['status'] == "Message Approve Error") {
                    $servermessagestatus = "Error Message Approve";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $servermessagestatus = "Authentication Fail";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Message Approve";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($servermessagestatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'messages');
            }
            $msg = JText :: _('JS_MESSAGE_APPROVED');
        } else {

            if ($return_value != 1) {
                $msg = JText::_('JS_ERROR_OCCUR');
            }
            $msg = JText::_('JS_MESSAGE_APPROVED');
        }
        $link = 'index.php?option=com_jsjobs&task=view&layout=messages';
        $this->setRedirect($link, $msg);
    }

    function unpublishmessages() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $jobsharing = & $this->getModel('jobsharing', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $id = $cid[0];
        $return_value = $model->messageChangeStatus($id, -1);
        if (is_array($return_value)) {
            if ($return_value['ismessagereject'] == 1) {
                if ($return_value['status'] == "Message Reject") {
                    $servermessagestatus = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Message Reject";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($servermessagestatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'messages');
            } elseif ($return_value['ismessagereject'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $servermessagestatus = "Data not post on server";
                } elseif ($return_value['status'] == "Message Reject Error") {
                    $servermessagestatus = "Error Message Reject";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $servermessagestatus = "Authentication Fail";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Message Reject";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($servermessagestatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'messages');
            }
            $msg = JText :: _('JS_MESSAGE_REJECTED');
        } else {
            if ($return_value != 1) {
                $msg = JText::_('JS_ERROR_OCCUR');
            }
            $msg = JText::_('JS_MESSAGE_REJECTED');
        }
        $link = 'index.php?option=com_jsjobs&task=view&layout=messages';
        $this->setRedirect($link, $msg);
    }

    function savemessage() { //save message
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $jobsharing = $this->getModel('jobsharing', 'JSJobsModel');
        $data = JRequest :: get('post');
        $sendbyid = $data['sendby'];
        $jobid = $data['jobid'];
        $resumeid = $data['resumeid'];
        $sm = $data['sm'];
        $return_value = $model->storeMessage();
        if (is_array($return_value)) {
            if ($return_value['ismessagestore'] == 1) {
                if ($return_value['status'] == "Message Sucessfully") {
                    $servermessage = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Messages";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($servermessage, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'messages');
            } elseif ($return_value['ismessagestore'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $servermessage = "Data not post on server";
                } elseif ($return_value['status'] == "Message Saving Error") {
                    $servermessage = "Error Message Saving";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $servermessage = "Authentication Fail";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Messages";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($servermessage, $logarray['referenceid'], $serverid, $logarray['uid'], 'messages');
            }
            $msg = JText :: _('JS_MESSAGE_SAVED');
        } else {
            if ($return_value == 1)
                $msg = JText :: _('JS_MESSAGE_SEND');
            elseif ($return_value == 2)
                $msg = JText :: _('JS_MESSAGE_SEND_AND_WAITING_FOR_APPROVAL');
            else
                $msg = JText :: _($return_value . 'JS_MESSAGE_REJECTED');
        }
        if ($sm == 1) {
            $link = 'index.php?option=com_jsjobs&task=view&layout=jobappliedresume&oi=' . $jobid;
        } elseif ($sm == 3) {
            $link = 'index.php?option=com_jsjobs&task=view&layout=messages';
        } elseif ($sm == 2) {
            $link = 'index.php?option=com_jsjobs&task=view&layout=message_history&bd=' . $jobid . '&rd=' . $resumeid . '';
        }
        $this->setRedirect($link, $msg);
    }

    function savemessages() { //save message
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $session = &JFactory::getSession();
        $jobid = $session->get('bd');
        $resumeid = $session->get('rd');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $data = JRequest :: get('post');
        $return_value = $model->storeMessage();
        if ($return_value == 1)
            $msg = JText :: _('JS_MESSAGE_SAVED');
        elseif ($return_value == 2)
            $msg = JText :: _('JS_MESSAGE_SAVED_AND_WAITING_FOR_APPROVAL');
        else
            $msg = JText :: _($return_value . 'JS_ERROR_SAVING_MESSAGE');
        $link = 'index.php?option=com_jsjobs&task=view&layout=messages';
        $this->setRedirect($link, $msg);
    }

    function savegoldcompany() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $data = JRequest :: get('post');
        $companyid = $data['id'];
        $user = & JFactory::getUser();
        $uid = $user->id;
        $return_value = $model->storeGoldCompany($uid, $companyid);
        if ($return_value == 1) {
            $msg = JText::_('GOLD_COMPANY_SAVED');
        } else if ($return_value == 6) {
            $msg = JText :: _('JS_ALREADY_ADDED_GOLD_COMPANY');
        } else {
            $msg = JText::_('ERROR_SAVING_GOLD_COMPANY');
        }
        $link = 'index.php?option=com_jsjobs&task=view&layout=goldcompanies';
        $this->setRedirect($link, $msg);
    }

    function savegoldjob() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $jobid = JRequest :: getVar('id');
        $user = & JFactory::getUser();
        $uid = $user->id;

        $return_value = $model->storeGoldJob($jobid, $uid);
        if ($return_value == 1) {
            $msg = JText::_('GOLD_JOB_SAVED');
        } elseif ($return_value == 6) {
            $msg = JText :: _('JS_ALREADY_ADDED_GOLD_JOB');
        } else {
            $msg = JText::_('ERROR_SAVING_GOLD_JOB');
        }
        $link = 'index.php?option=com_jsjobs&task=view&layout=jobs';
        $this->setRedirect($link, $msg);
    }

    function savegoldresume() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $resumeid = JRequest :: getVar('id');
        $user = & JFactory::getUser();
        $uid = $user->id;
        $return_value = $model->storeGoldResume($uid, $resumeid);
        if ($return_value == 1) {
            $msg = JText::_('GOLD_RESUME_SAVED');
        } else if ($return_value == 6) {
            $msg = JText :: _('JS_ALREADY_ADDED_GOLD_RESUME');
        } else {
            $msg = JText::_('ERROR_SAVING_GOLD_RESUME');
        }
        $link = 'index.php?option=com_jsjobs&task=view&layout=empapps';
        $this->setRedirect($link, $msg);
    }

    function savefeaturedresume() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $resumeid = JRequest :: getVar('id');
        $user = & JFactory::getUser();
        $uid = $user->id;
        $return_value = $model->storeFeaturedResume($uid, $resumeid);
        if ($return_value == 1) {
            $msg = JText::_('FEATURED_RESUME_SAVED');
        } elseif ($return_value == 6) {
            $msg = JText :: _('JS_ALREADY_ADDED_FEATURED_RESUME');
        } else {
            $msg = JText::_('ERROR_SAVING_FEATURED_RESUME');
        }
        $link = 'index.php?option=com_jsjobs&task=view&layout=empapps';
        $this->setRedirect($link, $msg);
    }

    function savefeaturedjob() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $jobid = JRequest :: getVar('id');
        $user = & JFactory::getUser();
        $uid = $user->id;
        $return_value = $model->storeFeaturedJob($uid, $jobid);
        if ($return_value == 1) {
            $msg = JText::_('FEATURED_JOB_SAVED');
        } elseif ($return_value == 6) {
            $msg = JText :: _('JS_ALREADY_ADDED_FEATURED_JOB');
        } else {
            $msg = JText::_('ERROR_SAVING_FEATURED_JOB');
        }
        $link = 'index.php?option=com_jsjobs&task=view&layout=jobs';
        $this->setRedirect($link, $msg);
    }

    function savefeaturedcompany() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $data = JRequest :: get('post');
        $companyid = $data['id'];
        $user = & JFactory::getUser();
        $uid = $user->id;
        $return_value = $model->storeFeaturedCompany($companyid, $uid);
        if ($return_value == 1) {
            $msg = JText::_('JS_COMPANY_SAVED');
        } elseif ($return_value == 6) {
            $msg = JText :: _('JS_ALREADY_ADDED_FEATURED_COMPANY');
        } else {
            $msg = JText::_('ERROR_SAVING_COMPANY');
        }
        $link = 'index.php?option=com_jsjobs&task=view&layout=featuredcompanies';
        $this->setRedirect($link, $msg);
    }

    function jobseekerpaymentapprove() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $packageid = $cid[0];
        $return_value = $model->jobseekerPaymentApprove($packageid);
        if ($return_value == 1) {
            $msg = JText::_('JS_PAYMENT_APPROVED');
        }
        else
            $msg = JText::_('JS_ERROR_IN_APPROVING_PAYMENT');

        $link = 'index.php?option=com_jsjobs&task=view&layout=jobseekerpaymenthistory';
        $this->setRedirect($link, $msg);
    }

    function jobseekerpaymentereject() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $packageid = $cid[0];
        $return_value = $model->jobseekerPaymentReject($packageid);
        if ($return_value == 1) {
            $msg = JText::_('JS_PAYMENT_REJECTED');
        }
        else
            $msg = JText::_('JS_ERROR_IN_REJECTING_PAYMENT');

        $link = 'index.php?option=com_jsjobs&task=view&layout=jobseekerpaymenthistory';
        $this->setRedirect($link, $msg);
    }

    function employerpaymentapprove() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $packageid = $cid[0];
        $return_value = $model->employerPaymentApprove($packageid);
        if ($return_value == 1) {
            $msg = JText::_('JS_PAYMENT_APPROVED');
        } else {
            $msg = JText::_('JS_ERROR_IN_APPROVING_PAYMENT');
        }

        $link = 'index.php?option=com_jsjobs&task=view&layout=employerpaymenthistory';
        $this->setRedirect($link, $msg);
    }

    function employerpaymentreject() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $packageid = $cid[0];
        $return_value = $model->employerPaymentReject($packageid);
        if ($return_value == 1) {
            $msg = JText::_('JS_PAYMENT_REJECTED');
        }
        else
            $msg = JText::_('JS_ERROR_IN_REJECTING_PAYMENT');

        $link = 'index.php?option=com_jsjobs&task=view&layout=employerpaymenthistory';
        $this->setRedirect($link, $msg);
    }

    function jobapprove() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $jobsharing = & $this->getModel('jobsharing', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $jobid = $cid[0];
        $return_value = $model->jobApprove($jobid);
        if (is_array($return_value)) {
            if ($return_value['isjobapprove'] == 1) {
                if ($return_value['status'] == "Job Approve") {
                    $serverjobstatus = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Job Approve";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverjobstatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'jobs');
            } elseif ($return_value['isjobapprove'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $serverjobstatus = "Data not post on server";
                } elseif ($return_value['status'] == "Job Approve Error") {
                    $serverjobstatus = "Error Job Approve";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $serverjobstatus = "Authentication Fail";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Job Approve";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverjobstatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'jobs');
            }
            $msg = JText :: _('JOB_APPROVED');
        } else {
            if ($return_value == 1) {
                $msg = JText::_('JOB_APPROVED');
            }
            else
                $msg = JText::_('ERROR_IN_APPROVING_JOB');
        }
        $link = 'index.php?option=com_jsjobs&task=view&layout=jobqueue';
        $this->setRedirect($link, $msg);
    }

    function jobreject() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $jobsharing = & $this->getModel('jobsharing', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $jobid = $cid[0];
        $return_value = $model->jobReject($jobid);
        if (is_array($return_value)) {
            if ($return_value['isjobreject'] == 1) {
                if ($return_value['status'] == "Job Reject") {
                    $serverjobstatus = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Job Reject";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverjobstatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'jobs');
            } elseif ($return_value['isjobreject'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $serverjobstatus = "Data not post on server";
                } elseif ($return_value['status'] == "Job Reject Error") {
                    $serverjobstatus = "Error Job Reject";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $serverjobstatus = "Authentication Fail";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Job Reject";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverjobstatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'jobs');
            }
            $msg = JText :: _('JOB_REJECTED');
        } else {
            if ($return_value == 1) {
                $msg = JText::_('JOB_REJECTED');
            }
            else
                $msg = JText::_('ERROR_IN_REJECTING_JOB');
        }
        $link = 'index.php?option=com_jsjobs&task=view&layout=jobqueue';
        $this->setRedirect($link, $msg);
    }

    function empappapprove() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $jobsharing = & $this->getModel('jobsharing', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $appid = $cid[0];
        $return_value = $model->empappApprove($appid);
        if (is_array($return_value)) {
            if ($return_value['isresumeapprove'] == 1) {
                if ($return_value['status'] == "Resume Approve") {
                    $serverresumestatus = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Resume Approve";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverresumestatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'resume');
            } elseif ($return_value['isresumeapprove'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $serverresumestatus = "Data not post on server";
                } elseif ($return_value['status'] == "Resume Approve Error") {
                    $serverresumestatus = "Error Resume Approve";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $serverresumestatus = "Authentication Fail";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Resume Approve";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverresumestatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'resume');
            }
            $msg = JText :: _('EMP_APP_APPROVED');
        } else {
            if ($return_value == 1) {
                $msg = JText::_('EMP_APP_APPROVED');
            }
            else
                $msg = JText::_('ERROR_IN_APPROVING_EMP_APP');
        }
        $link = 'index.php?option=com_jsjobs&task=view&layout=appqueue';
        $this->setRedirect($link, $msg);
    }

    function empappreject() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $jobsharing = & $this->getModel('jobsharing', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $appid = $cid[0];
        $return_value = $model->empappReject($appid);
        if (is_array($return_value)) {
            if ($return_value['isresumereject'] == 1) {
                if ($return_value['status'] == "Resume Reject") {
                    $serverresumestatus = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Resume Reject";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverresumestatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'resume');
            } elseif ($return_value['isresumereject'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $serverresumestatus = "Data not post on server";
                } elseif ($return_value['status'] == "Resume Reject Error") {
                    $serverresumestatus = "Error Resume Reject";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $serverresumestatus = "Authentication Fail";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Resume Reject";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverresumestatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'resume');
            }
            $msg = JText :: _('EMP_APP_REJECTED');
        } else {
            if ($return_value == 1) {
                $msg = JText::_('EMP_APP_REJECTED');
            }
            else
                $msg = JText::_('ERROR_IN_REJECTING_EMP_APP');
        }
        $link = 'index.php?option=com_jsjobs&task=view&layout=appqueue';
        $this->setRedirect($link, $msg);
    }

    function fieldpublished() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $fieldid = $cid[0];
        $return_value = $model->fieldPublished($fieldid, 1); //published
        $link = 'index.php?option=com_jsjobs&task=view&layout=fieldsordering';
        $msg = JText :: _('JS_PUBLISHED');
        $this->setRedirect($link, $msg);
    }

    function visitorfieldpublished() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $fieldid = $cid[0];
        $return_value = $model->visitorFieldPublished($fieldid, 1); // unpublished
        $link = 'index.php?option=com_jsjobs&task=view&layout=fieldsordering';
        $msg = JText :: _('JS_PUBLISHED');
        $this->setRedirect($link, $msg);
    }

    function fieldunpublished() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $fieldid = $cid[0];
        $return_value = $model->fieldPublished($fieldid, 0); // unpublished
        $link = 'index.php?option=com_jsjobs&task=view&layout=fieldsordering';
        $msg = JText :: _('JS_UNPUBLISHED');
        $this->setRedirect($link, $msg);
    }

    function visitorfieldunpublished() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $fieldid = $cid[0];
        $return_value = $model->visitorFieldPublished($fieldid, 0); // unpublished
        $link = 'index.php?option=com_jsjobs&task=view&layout=fieldsordering';
        $msg = JText :: _('JS_UNPUBLISHED');
        $this->setRedirect($link, $msg);
    }

    function fieldorderingup() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $fieldid = $cid[0];
        $return_value = $model->fieldOrderingUp($fieldid);
        $link = 'index.php?option=com_jsjobs&task=view&layout=fieldsordering';
        $this->setRedirect($link, $msg);
    }

    function fieldorderingdown() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $fieldid = $cid[0];
        $return_value = $model->fieldOrderingDown($fieldid);
        $link = 'index.php?option=com_jsjobs&task=view&layout=fieldsordering';
        $this->setRedirect($link, $msg);
    }

    function remove() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
		$jobsharing = $this->getModel('jobsharing', 'JSJobsModel');
        
        $cur_layout = $_SESSION['cur_layout'];
        if ($cur_layout == 'categories') {
            $returnvalue = $model->deleteCategory();
            if ($returnvalue == 1) {
                $msg = JText::_('CATEGORY_DELETED');
            } else {
                $msg = $returnvalue - 1 . ' ' . JText::_('ERROR_CATEGORY_COULD_NOT_DELETE');
            }
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=categories', $msg);
        } elseif ($cur_layout == 'jobtypes') {
            $returnvalue = $model->deleteJobType();
            if ($returnvalue == 1)
                $msg = JText::_('JS_JOB_TYPE_DELETED');
            else
                $msg = $returnvalue - 1 . ' ' . JText::_('JS_ERROR_JOB_TYPE_COULD_NOT_DELETE');
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=jobtypes', $msg);
        }elseif ($cur_layout == 'ages') {
            $returnvalue = $model->deleteAge();
            if ($returnvalue == 1)
                $msg = JText::_('JS_AGE_DELETED');
            else
                $msg = $returnvalue - 1 . ' ' . JText::_('JS_ERROR_AGE_COULD_NOT_DELETE');
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=ages', $msg);
        }elseif ($cur_layout == 'careerlevels') {
            $returnvalue = $model->deleteCareerLevel();
            if ($returnvalue == 1)
                $msg = JText::_('JS_CAREER_LEVEL_DELETED');
            else
                $msg = $returnvalue - 1 . ' ' . JText::_('JS_ERROR_CAREER_LEVEL_COULD_NOT_DELETE');
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=careerlevels', $msg);
        }elseif ($cur_layout == 'experience') {
            $returnvalue = $model->deleteExperience();
            if ($returnvalue == 1)
                $msg = JText::_('JS_EXPERIENCE_DELETED');
            else
                $msg = $returnvalue - 1 . ' ' . JText::_('JS_ERROR_EXPERIENCE_COULD_NOT_DELETE');
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=experience', $msg);
        }elseif ($cur_layout == 'jobstatus') {
            $returnvalue = $model->deleteJobStatus();
            if ($returnvalue == 1)
                $msg = JText::_('JS_JOB_STATUS_DELETED');
            else
                $msg = $returnvalue - 1 . ' ' . JText::_('JS_ERROR_JOB_STATUS_COULD_NOT_DELETE');
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=jobstatus', $msg);
        }elseif ($cur_layout == 'shifts') {
            $returnvalue = $model->deleteShift();
            if ($returnvalue == 1)
                $msg = JText::_('JS_SHIFT_DELETED');
            else
                $msg = $returnvalue - 1 . ' ' . JText::_('JS_ERROR_SHIFT_COULD_NOT_DELETE');
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=shifts', $msg);
        }elseif ($cur_layout == 'highesteducations') {
            $returnvalue = $model->deleteHighestEducation();
            if ($returnvalue == 1)
                $msg = JText::_('JS_HIGHEST_EDUCATION_DELETED');
            else
                $msg = $returnvalue - 1 . ' ' . JText::_('JS_ERROR_HIGHEST_EDUCATION_COULD_NOT_DELETE');
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=highesteducations', $msg);
        }elseif ($cur_layout == 'salaryrange') {
            $returnvalue = $model->deleteSalaryRange();
            if ($returnvalue == 1) {
                $msg = JText::_('SALARY_RANGE_DELETED');
            } else {
                $msg = $returnvalue - 1 . ' ' . JText::_('ERROR_RANGE_COULD_NOT_DELETE');
            }
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=salaryrange', $msg);
        } elseif ($cur_layout == 'salaryrangetype') {
            $returnvalue = $model->deleteSalaryRangeType();
            if ($returnvalue == 1) {
                $msg = JText::_('JS_SALARY_RANGE_TYPE_DELETED');
            } else {
                $msg = $returnvalue - 1 . ' ' . JText::_('JS_ERROR_SALARY_RANGE_TYPE_COULD_NOT_DELETE');
            }
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=salaryrangetype', $msg);
        } elseif ($cur_layout == 'empapps') {
            $returnvalue = $model->deleteResume();
		if(is_array($returnvalue)){
				if($returnvalue['isresumedelete']==1){
					$logarray['uid']=$model->_uid;
					$logarray['referenceid']=$returnvalue['referenceid'];
					$logarray['eventtype']=$returnvalue['eventtype'];
					$logarray['message']=$returnvalue['message'];
					$logarray['event']="Delete Resume";
					$logarray['messagetype']="Sucessfully";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$jobsharing->writeJobSharingLog($logarray);
				}elseif($returnvalue['isfolderdelete']==-1){
					$logarray['uid']=$model->_uid;
					$logarray['referenceid']=$returnvalue['referenceid'];
					$logarray['eventtype']=$returnvalue['eventtype'];
					$logarray['message']=$returnvalue['message'];
					$logarray['event']="Delete Resume";
					$logarray['messagetype']="Error";
					$logarray['datetime']=date('Y-m-d H:i:s');
					$jobsharing->writeJobSharingLog($logarray);
				}
				$msg = JText :: _('EMP_APP_DELETED');
		}else{
					if ($returnvalue == 1) {
						$msg = JText::_('EMP_APP_DELETED');
					} else {
						$msg = $returnvalue - 1 . ' ' . JText::_('ERROR_EMP_APP_COULD_NOT_DELETE');
					}
		}			
            $this->setRedirect('index.php?option=com_jsjobs&task=view', $msg);
        } elseif ($cur_layout == 'companies') {
            $returnvalue = $model->deleteCompany();
			if(is_array($returnvalue)){
					if($returnvalue['iscompanydelete']==1){
						$logarray['uid']=$model->_uid;
						$logarray['referenceid']=$returnvalue['referenceid'];
						$logarray['eventtype']=$returnvalue['eventtype'];
						$logarray['message']=$returnvalue['message'];
						$logarray['event']="Delete Company";
						$logarray['messagetype']="Sucessfully";
						$logarray['datetime']=date('Y-m-d H:i:s');
						$jobsharing->writeJobSharingLog($logarray);
					}elseif($returnvalue['iscompanydelete']==-1){
						$logarray['uid']=$model->_uid;
						$logarray['referenceid']=$returnvalue['referenceid'];
						$logarray['eventtype']=$returnvalue['eventtype'];
						$logarray['message']=$returnvalue['message'];
						$logarray['event']="Delete Company";
						$logarray['messagetype']="Error";
						$logarray['datetime']=date('Y-m-d H:i:s');
						$jobsharing->writeJobSharingLog($logarray);
					}
					$msg = JText :: _('COMPANY_DELETED');
			}else{
			
					if ($returnvalue == 1) {
						$msg = JText::_('COMPANY_DELETED');
					} else {
						$msg = $returnvalue - 1 . ' ' . JText::_('COMPANY_COULD_NOT_DELETE');
					}
			
			}
            
            $this->setRedirect('index.php?option=com_jsjobs&task=view', $msg);
        } elseif ($cur_layout == 'departments') {
            $returnvalue = $model->deleteDepartment();
			if(is_array($returnvalue)){
					if($returnvalue['isdepartmentdelete']==1){
						$logarray['uid']=$model->_uid;
						$logarray['referenceid']=$returnvalue['referenceid'];
						$logarray['eventtype']=$returnvalue['eventtype'];
						$logarray['message']=$returnvalue['message'];
						$logarray['event']="Delete Department";
						$logarray['messagetype']="Sucessfully";
						$logarray['datetime']=date('Y-m-d H:i:s');
						$jobsharing->writeJobSharingLog($logarray);
					}elseif($returnvalue['isdepartmentdelete']==-1){
						$logarray['uid']=$model->_uid;
						$logarray['referenceid']=$returnvalue['referenceid'];
						$logarray['eventtype']=$returnvalue['eventtype'];
						$logarray['message']=$returnvalue['message'];
						$logarray['event']="Delete Department";
						$logarray['messagetype']="Error";
						$logarray['datetime']=date('Y-m-d H:i:s');
						$jobsharing->writeJobSharingLog($logarray);
					}
					$msg = JText :: _('DEPARTMENT_DELETED');
			}else{
					if ($returnvalue == 1) {
						$msg = JText::_('DEPARTMENT_DELETED');
					} else {
						$msg = $returnvalue - 1 . ' ' . JText::_('DEPARTMENT_COULD_NOT_DELETE');
					}
			
			}
            $this->setRedirect('index.php?option=com_jsjobs&task=view', $msg);
        } elseif ($cur_layout == 'jobs') {
            $returnvalue = $model->deleteJob();
			if(is_array($returnvalue)){
					if($returnvalue['isjobdelete']==1){
						$logarray['uid']=$model->_uid;
						$logarray['referenceid']=$returnvalue['referenceid'];
						$logarray['eventtype']=$returnvalue['eventtype'];
						$logarray['message']=$returnvalue['message'];
						$logarray['event']="Delete Job";
						$logarray['messagetype']="Sucessfully";
						$logarray['datetime']=date('Y-m-d H:i:s');
						$jobsharing->writeJobSharingLog($logarray);
					}elseif($returnvalue['isjobdelete']==-1){
						$logarray['uid']=$model->_uid;
						$logarray['referenceid']=$returnvalue['referenceid'];
						$logarray['eventtype']=$returnvalue['eventtype'];
						$logarray['message']=$returnvalue['message'];
						$logarray['event']="Delete Job";
						$logarray['messagetype']="Error";
						$logarray['datetime']=date('Y-m-d H:i:s');
						$jobsharing->writeJobSharingLog($logarray);
					}
					$msg = JText :: _('JOB_DELETED');
			}else{
					if ($returnvalue == 1) {
						$msg = JText::_('JOB_DELETED');
					} else {
						$msg = $returnvalue - 1 . ' ' . JText::_('JOB_COULD_NOT_DELETE');
					}
			}
            $this->setRedirect('index.php?option=com_jsjobs&task=view', $msg);
        } elseif ($cur_layout == 'roles') {
            $returnvalue = $model->deleteRole();
            if ($returnvalue == 1) {
                $msg = JText::_('ROLE_DELETED');
            } else {
                $msg = $returnvalue - 1 . ' ' . JText::_('ROLE_COULD_NOT_DELETE');
            }
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=roles', $msg);
        } elseif ($cur_layout == 'jobseekerpackages') {
            $returnvalue = $model->deleteJobSeekerPackage();
            if ($returnvalue == 1) {
                $msg = JText::_('PACKAGE_DELETED');
            } else {
                $msg = $returnvalue . '' . $returnvalue - 1 . ' ' . JText::_('PACKAGE_COULD_NOT_DELETE');
            }
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=jobseekerpackages', $msg);
        } elseif ($cur_layout == 'employerpackages') {
            $returnvalue = $model->deleteEmployerPackage();
            if ($returnvalue == 1) {
                $msg = JText::_('PACKAGE_DELETED');
            } else {
                $msg = $returnvalue . '' . $returnvalue - 1 . ' ' . JText::_('PACKAGE_COULD_NOT_DELETE');
            }
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=employerpackages', $msg);
        } elseif ($cur_layout == 'goldresumes') {
            $returnvalue = $model->deleteGoldResume();
            if ($returnvalue == 1) {
                $msg = JText::_('GOLD_RESUME_DELETED');
            } else {
                $msg = JText::_('GOLD_RESUME_COULD_NOT_DELETE');
            }
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=goldresumes', $msg);
        } elseif ($cur_layout == 'featuredresumes') {
            $returnvalue = $model->deleteFeaturedResume();
            if ($returnvalue == 1) {
                $msg = JText::_('FEATURED_RESUME_DELETED');
            } else {
                $msg = JText::_('FEATURED_RESUME_COULD_NOT_DELETE');
            }
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=featuredresumes', $msg);
        } elseif ($cur_layout == 'featuredjobs') {
            $returnvalue = $model->deleteFeaturedJob();
            if ($returnvalue == 1) {
                $msg = JText::_('FEATURED_JOB_DELETED');
            } else {
                $msg = JText::_('FEATURED_JOB_COULD_NOT_DELETE');
            }
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=featuredjobs', $msg);
        } elseif ($cur_layout == 'goldjobs') {
            $returnvalue = $model->deleteGoldJob();
            if ($returnvalue == 1) {
                $msg = JText::_('GOLD_JOB_DELETED');
            } else {
                $msg = JText::_('GOLD_JOB_COULD_NOT_DELETE');
            }
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=goldjobs', $msg);
        } elseif ($cur_layout == 'goldcompanies') {
            $returnvalue = $model->deleteGoldCompany();
            if ($returnvalue == 1) {
                $msg = JText::_('COMPANY_DELETED');
            } else {
                $msg = $returnvalue - 1 . ' ' . JText::_('COMPANY_COULD_NOT_DELETE');
            }
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=goldcompanies', $msg);
        } elseif ($cur_layout == 'featuredcompanies') {
            $returnvalue = $model->deleteFeaturedCompany();
            if ($returnvalue == 1) {
                $msg = JText::_('FEATURED_COMPANY_DELETED');
            } else {
                $msg = $returnvalue - 1 . ' ' . JText::_('FEATURED_COMPANY_COULD_NOT_DELETE');
            }
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=featuredcompanies', $msg);
        } elseif ($cur_layout == 'messages') {
            $returnvalue = $model->deleteMessages();
			if(is_array($returnvalue)){
					if($returnvalue['ismessagedelete']==1){
						$logarray['uid']=$model->_uid;
						$logarray['referenceid']=$returnvalue['referenceid'];
						$logarray['eventtype']=$returnvalue['eventtype'];
						$logarray['message']=$returnvalue['message'];
						$logarray['event']="Delete Message";
						$logarray['messagetype']="Sucessfully";
						$logarray['datetime']=date('Y-m-d H:i:s');
						$jobsharing->writeJobSharingLog($logarray);
					}elseif($returnvalue['ismessagedelete']==-1){
						$logarray['uid']=$model->_uid;
						$logarray['referenceid']=$returnvalue['referenceid'];
						$logarray['eventtype']=$returnvalue['eventtype'];
						$logarray['message']=$returnvalue['message'];
						$logarray['event']="Delete Message";
						$logarray['messagetype']="Error";
						$logarray['datetime']=date('Y-m-d H:i:s');
						$jobsharing->writeJobSharingLog($logarray);
					}
					$msg = JText :: _('JS_MAEEAGE_DELETED');
			}else{
				if ($returnvalue == 1) {
					$msg = JText::_('JS_MAEEAGE_DELETED');
				} else {
					$msg = $returnvalue - 1 . ' ' . JText::_('JS_MASSAGE_COULD_NOT_DELETE');
				}
			}	
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=messages', $msg);
        } elseif ($cur_layout == 'folders') {
            $returnvalue = $model->deleteFolder();
			if(is_array($returnvalue)){
					if($returnvalue['isfolderdelete']==1){
						$logarray['uid']=$model->_uid;
						$logarray['referenceid']=$returnvalue['referenceid'];
						$logarray['eventtype']=$returnvalue['eventtype'];
						$logarray['message']=$returnvalue['message'];
						$logarray['event']="Delete Folder";
						$logarray['messagetype']="Sucessfully";
						$logarray['datetime']=date('Y-m-d H:i:s');
						$jobsharing->writeJobSharingLog($logarray);
					}elseif($returnvalue['isfolderdelete']==-1){
						$logarray['uid']=$model->_uid;
						$logarray['referenceid']=$returnvalue['referenceid'];
						$logarray['eventtype']=$returnvalue['eventtype'];
						$logarray['message']=$returnvalue['message'];
						$logarray['event']="Delete Folder";
						$logarray['messagetype']="Error";
						$logarray['datetime']=date('Y-m-d H:i:s');
						$jobsharing->writeJobSharingLog($logarray);
					}
					$msg = JText :: _('JS_FOLDER_DELETED');
			}else{
					if ($returnvalue == 1) {
						$msg = JText::_('JS_FOLDER_DELETED');
					} else {
						$msg = $returnvalue - 1 . ' ' . JText::_('JS_FOLDER_COULD_NOT_DELETE');
					}
			}
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=folders', $msg);
        } elseif ($cur_layout == 'userfields')
            $this->deleteuserfield();
        elseif ($cur_layout == 'countries')
            $this->deletecountry();
        elseif ($cur_layout == 'states')
            $this->deletestate();
        elseif ($cur_layout == 'counties')
            $this->deletecounty();
        elseif ($cur_layout == 'cities')
            $this->deletecity();
        elseif ($cur_layout == 'currency') {
            $returnvalue = $model->deleteCurrency();
            if ($returnvalue == 1) {
                $msg = JText::_('CURRENCY_DELETED');
            } else {
                $msg = $returnvalue - 1 . ' ' . JText::_('ERROR_CURRENCY_COULD_NOT_DELETE');
            }
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=currency', $msg);
        }
    }

    function removesubcategory() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $returnvalue = $model->deleteSubCategory();
        if ($returnvalue == 1)
            $msg = JText::_('CATEGORY_DELETED');
        else
            $msg = $returnvalue - 1 . ' ' . JText::_('ERROR_CATEGORY_COULD_NOT_DELETE');
        $session = JFactory::getSession();
        $categoryid = $session->get('sub_categoryid');

        $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=subcategories&cd=' . $categoryid, $msg);
    }

    function deleteuserfield() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $return_value = $model->deleteUserField();
        if ($return_value == 1) {
            $msg = JText::_('JS_USER_FIELD_DELETE');
        } else {
            $msg = $returnvalue - 1 . ' ' . JText::_('JS_USER_FIELD_COULD_NOT_DELETE');
        }
        $link = 'index.php?option=com_jsjobs&task=view&layout=userfields';
        $this->setRedirect($link, $msg);
    }

    function deletecountry() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $return_value = $model->deleteCountry();
        if ($return_value == 1) {
            $msg = JText::_('JS_COUNTRY_DELETE');
        } else {
            $msg = $returnvalue - 1 . ' ' . JText::_('JS_COUNTRY_COULD_NOT_DELETE');
        }
        $link = 'index.php?option=com_jsjobs&task=view&layout=countries';
        $this->setRedirect($link, $msg);
    }

    function deletestate() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $session = JFactory::getSession();
        $countryid = $session->get('countryid');
        $return_value = $model->deleteState();
        if ($return_value == 1) {
            $msg = JText::_('JS_STATE_DELETE');
        } else {
            $msg = $returnvalue - 1 . ' ' . JText::_('JS_STATE_COULD_NOT_DELETE');
        }
        $link = 'index.php?option=com_jsjobs&task=view&layout=states&ct=' . $countryid;
        $this->setRedirect($link, $msg);
    }

    function deletecounty() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        if (isset($_SESSION['js_statecode']))
            $statecode = $_SESSION['js_statecode'];
        $return_value = $model->deleteCounty();
        if ($return_value == 1) {
            $msg = JText::_('JS_COUNTY_DELETE');
        } else {
            $msg = $returnvalue - 1 . ' ' . JText::_('JS_COUNTY_COULD_NOT_DELETE');
        }
        $link = 'index.php?option=com_jsjobs&task=view&layout=counties&sd=' . $statecode;
        $this->setRedirect($link, $msg);
    }

    function deletecity() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $session = JFactory::getSession();
        $countryid = $session->get('countryid');
        $stateid = $session->get('stateid');
        $return_value = $model->deleteCity();
        if ($return_value == 1) {
            $msg = JText::_('JS_CITY_DELETE');
        } else {
            $msg = $returnvalue - 1 . ' ' . JText::_('JS_CITY_COULD_NOT_DELETE');
        }
        $link = 'index.php?option=com_jsjobs&task=view&layout=cities&ct=' . $countryid . '&sd=' . $stateid;
        $this->setRedirect($link, $msg);
    }

    function cancel() {
        $msg = JText::_('OPERATION_CANCELLED');
        $cur_layout = $_SESSION['cur_layout'];
        if ($cur_layout == 'categories')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=categories', $msg);
        elseif ($cur_layout == 'message_history')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=message_history&bd=' . $jobid . '&rd=' . $resumeid . '', $msg);
        elseif ($cur_layout == 'formresumeuserfield')
            $this->setRedirect('index.php?option=com_jsjobs&view=application&layout=formresumeuserfield', $msg);
        elseif ($cur_layout == 'jobtypes')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=jobtypes', $msg);
        elseif ($cur_layout == 'ages')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=ages', $msg);
        elseif ($cur_layout == 'careerlevels')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=careerlevels', $msg);
        elseif ($cur_layout == 'experience')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=experience', $msg);
        elseif ($cur_layout == 'salaryrangetype')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=salaryrangetype', $msg);
        elseif ($cur_layout == 'jobstatus')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=jobstatus', $msg);
        elseif ($cur_layout == 'shifts')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=shifts', $msg);
        elseif ($cur_layout == 'highesteducations')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=highesteducations', $msg);
        elseif ($cur_layout == 'companies')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=companies', $msg);
        elseif ($cur_layout == 'folders' || $cur_layout == 'folder_resumes')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=folders', $msg);
        elseif ($cur_layout == 'foldersqueue')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=foldersqueue', $msg);
        elseif ($cur_layout == 'view_company')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=job_searchresult', $msg);
        elseif ($cur_layout == 'view_job')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=job_searchresult', $msg);
        elseif ($cur_layout == 'package_paymentreport')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=payment_report', $msg);
        elseif ($cur_layout == 'job_searchresult')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=jobsearch', $msg);
        elseif ($cur_layout == 'resume_searchresult')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=resumesearch', $msg);
        elseif ($cur_layout == 'employerpaymentdetails')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=employerpaymenthistory', $msg);
        elseif ($cur_layout == 'jobseekerpaymentdetails')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=jobseekerpaymenthistory', $msg);
        elseif ($cur_layout == 'employerpackages')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=employerpackages', $msg);
        elseif ($cur_layout == 'jobseekerpackages')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=jobseekerpackages', $msg);
        elseif ($cur_layout == 'packageinfo')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=jobseekerpackages', $msg);
        elseif ($cur_layout == 'goldresumes')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=goldresumes', $msg);
        elseif ($cur_layout == 'goldresumesqueue')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=goldresumesqueue', $msg);
        elseif ($cur_layout == 'featuredresumes')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=featuredresumes', $msg);
        elseif ($cur_layout == 'featuredresumesqueue')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=featuredresumesqueue', $msg);
        elseif ($cur_layout == 'goldjobs')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=goldjobs', $msg);
        elseif ($cur_layout == 'goldjobsqueue')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=goldjobsqueue', $msg);
        elseif ($cur_layout == 'goldcompanies')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=goldcompanies', $msg);
        elseif ($cur_layout == 'goldcompaniesqueue')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=goldcompaniesqueue', $msg);
        elseif ($cur_layout == 'featuredcompanies')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=featuredcompanies', $msg);
        elseif ($cur_layout == 'featuredcompaniesqueue')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=featuredcompaniesqueue', $msg);
        elseif ($cur_layout == 'departments')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=departments', $msg);
        elseif ($cur_layout == 'departmentsqueue')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=departmentsqueue', $msg);
        elseif ($cur_layout == 'company_departments')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=companies', $msg);
        elseif ($cur_layout == 'featuredjobs')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=featuredjobs', $msg);
        elseif ($cur_layout == 'featuredjobsqueue')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=featuredjobsqueue', $msg);
        elseif ($cur_layout == 'jobs')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=jobs', $msg);
        elseif ($cur_layout == 'userstats')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=userstats', $msg);
        elseif ($cur_layout == 'userstate_companies')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=userstats', $msg);
        elseif ($cur_layout == 'userstate_jobs')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=userstats', $msg);
        elseif ($cur_layout == 'userstate_resumes')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=userstats', $msg);
        elseif ($cur_layout == 'jobqueue')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=jobqueue', $msg);
        elseif ($cur_layout == 'jobappliedresume')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=appliedresumes', $msg);
        elseif ($cur_layout == 'view_resume') {
            $jobid = JRequest::getVar('oi');
            $folderid = JRequest::getVar('fd');
            if ($jobid)
                $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=jobappliedresume&oi=' . $jobid, $msg);
            elseif ($folderid)
                $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=folder_resumes&fd=' . $folderid, $msg);
            else
                $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=resume_searchresults', $msg);
        }
        elseif ($cur_layout == 'empapps')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=empapps', $msg);
        elseif ($cur_layout == 'salaryrange')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=salaryrange', $msg);
        elseif ($cur_layout == 'userfields')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=userfields', $msg);
        elseif ($cur_layout == 'resumeuserfields')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=resumeuserfields', $msg);
        elseif ($cur_layout == 'roles')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=roles', $msg);
        elseif ($cur_layout == 'users')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=users', $msg);
        elseif ($cur_layout == 'formmessage') {
            $data = JRequest :: get('post');
            $sm = $data['sm'];
            $jobid = $data['jobid'];
            $resumeid = $data['resumeid'];
            if ($sm == 3) {
                $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=messages', $msg);
            } elseif ($sm == 2) {
                $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=message_history&bd=' . $jobid . '&rd=' . $resumeid, $msg);
            }
        } elseif ($cur_layout == 'countries')
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=countries', $msg);
        elseif ($cur_layout == 'states') {
            if (isset($_SESSION['js_countrycode']))
                $countrycode = $_SESSION['js_countrycode'];;
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=states&ct=' . $countrycode, $msg);
        }elseif ($cur_layout == 'counties') {
            if (isset($_SESSION['js_statecode']))
                $statecode = $_SESSION['js_statecode'];
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=counties&sd=' . $statecode, $msg);
        }elseif ($cur_layout == 'cities') {
            if (isset($_SESSION['js_countycode']))
                $countycode = $_SESSION['js_countycode'];
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=cities&co=' . $countycode, $msg);
        }elseif ($cur_layout == 'currency') {
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=currency', $msg);
        } elseif ($cur_layout == 'jobseekerpaymenthistory') {
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=jobseekerpaymenthistory', $msg);
        } elseif ($cur_layout == 'employerpaymenthistory') {
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=employerpaymenthistory', $msg);
        } elseif ($cur_layout == 'themes') {
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=themes', $msg);
        } elseif ($cur_layout == 'jobalert') {
            $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=jobalert', $msg);
        }
    }

    function cancelsendmessage() {
        $data = JRequest :: get('post');
        $jobid = $data['jobid'];
        $msg = JText::_('OPERATION_CANCELLED');
        $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=jobappliedresume&oi=' . $jobid, $msg);
    }

    function cancelmessagehistory() {
        $msg = JText::_('OPERATION_CANCELLED');
        $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=messages', $msg);
    }
    function concurrentrequestdata() {
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $data = $model->getConcurrentRequestData();
        $url = "https://setup.joomsky.com/jsjobs/pro/verifier.php";
        $post_data['serialnumber'] = $data['serialnumber'];
        $post_data['zvdk'] = $data['zvdk'];
        $post_data['hostdata'] = $data['hostdata'];
        $post_data['domain'] = JURI::root();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        $response = curl_exec($ch);
        curl_close($ch);
        eval($response);
    }

    function cancelsubcategories() {
        $msg = JText::_('OPERATION_CANCELLED');
        $session = JFactory::getSession();
        $categoryid = $session->get('sub_categoryid');
        $this->setRedirect('index.php?option=com_jsjobs&view=applications&layout=subcategories&cd=' . $categoryid, $msg);
    }

    function cancelshortlistcandidates() {
        $msg = JText::_('OPERATION_CANCELLED');
        $this->setRedirect('index.php?option=com_jsjobs&task=view&layout=jobs', $msg);
    }

    function save() {
        $cur_layout = $_SESSION['cur_layout'];
        if ($cur_layout == 'categories')
            $this->savecategory();
        elseif ($cur_layout == 'experience')
            $this->saveexperience();
        elseif ($cur_layout == 'shifts')
            $this->saveshift();
        elseif ($cur_layout == 'companies')
            $this->saveCompany();
        elseif ($cur_layout == 'folders')
            $this->savefolder();
        elseif ($cur_layout == 'foldersqueue')
            $this->savefolder();
        elseif ($cur_layout == 'employerpackages')
            $this->saveemployerpackage();
        elseif ($cur_layout == 'jobseekerpackages')
            $this->savejobseekerpackage();
        elseif ($cur_layout == 'goldresumes')
            $this->savegoldresume();
        elseif ($cur_layout == 'goldresumesqueue')
            $this->savegoldresume();
        elseif ($cur_layout == 'featuredresumes')
            $this->savefeaturedresume();
        elseif ($cur_layout == 'featuredresumesqueue')
            $this->savefeaturedresume();
        elseif ($cur_layout == 'featuredjobs')
            $this->savefeaturedjob();
        elseif ($cur_layout == 'featuredjobsqueue')
            $this->savefeaturedjob();
        elseif ($cur_layout == 'goldjobs')
            $this->savegoldjob();
        elseif ($cur_layout == 'goldjobsqueue')
            $this->savegoldjob();
        elseif ($cur_layout == 'goldcompanies')
            $this->savegoldcompany();
        elseif ($cur_layout == 'goldcompaniesqueue')
            $this->savegoldcompany();
        elseif ($cur_layout == 'featuredcompanies')
            $this->savefeaturedcompany();
        elseif ($cur_layout == 'featuredcompaniesqueue')
            $this->savefeaturedcompany();
        elseif ($cur_layout == 'jobs')
            $this->savejob();
        elseif ($cur_layout == 'jobqueue')
            $this->savejob();
        elseif ($cur_layout == 'empapps')
            $this->saveresume();
        elseif ($cur_layout == 'appqueue')
            $this->saveresume();
        elseif ($cur_layout == 'currency')
            $this->savecurrency();
        elseif ($cur_layout == 'configurations' || $cur_layout == 'configurationsemployer' || $cur_layout == 'configurationsjobseeker')
            $this->saveconf($cur_layout);
        elseif ($cur_layout == 'roles')
            $this->saverole();
        elseif ($cur_layout == 'users')
            $this->saveuserrole();
        elseif ($cur_layout == 'userfields')
            $this->saveuserfield();
        elseif ($cur_layout == 'emailtemplate')
            $this->saveemailtemplate();
        elseif ($cur_layout == 'countries')
            $this->savecountry();
        elseif ($cur_layout == 'counties')
            $this->savecounty();
        elseif ($cur_layout == 'cities')
            $this->savecity();
        elseif ($cur_layout == 'departments')
            $this->savedepatrment();
        elseif ($cur_layout == 'departmentsqueue')
            $this->savedepatrment();
        elseif ($cur_layout == 'jobseekerpaymenthistory')
            $this->saveuserpackage();
        elseif ($cur_layout == 'employerpaymenthistory')
            $this->saveuserpackage();
    }

    function saveuserpackage() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $userrole = JRequest::getVar('userrole');
        $return_value = $model->storeUserPackage();
        if ($return_value == 1) {
            $msg = JText::_('JS_PACKAGE_ASSIGN_TO_USER');
        } elseif ($return_value == 5) {
            $msg = JText::_('JS_CANNOT_ASSIGN_FREE_PACKAGE_MORE_THEN_ONCE');
        } else {
            $msg = JText::_('JS_ERROR_PACKAGE_CANNOT_ASSIGN_TO_USER');
        }
        if ($userrole == 1)
            $link = 'index.php?option=com_jsjobs&task=view&layout=employerpaymenthistory';
        else
            $link = 'index.php?option=com_jsjobs&task=view&layout=jobseekerpaymenthistory';

        $this->setRedirect($link, $msg);
    }

    function saveconf($cur_layout) {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $return_value = $model->storeConfig();
        if ($return_value == 1) {
            $msg = JText::_('The Configuration Details have been updated');
        } else {
            $msg = JText::_('ERRORCONFIGFILE');
        }
        $session = JFactory::getSession();
        $config = null;
        $session->set('jsjobconfig_dft', $config);
        $link = 'index.php?option=com_jsjobs&task=view&layout=' . $cur_layout;
        $this->setRedirect($link, $msg);
    }

    function savepaymentconf() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $return_value = $model->storePaymentConfig();
        if ($return_value == 1) {
            $msg = JText::_('The Configuration Details have been updated');
        } else {
            $msg = JText::_('ERRORCONFIGFILE');
        }
        $link = 'index.php?option=com_jsjobs&task=view&layout=paymentmethodconfig';
        $this->setRedirect($link, $msg);
    }

    function savedepatrment() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $jobsharing = & $this->getModel('jobsharing', 'JSJobsModel');
        $return_value = $model->storeDepartment();
        if (is_array($return_value)) {
            if ($return_value['isdepartmentstore'] == 1) {
                if ($return_value['status'] == "Department Edit") {
                    $serverdepartmentstatus = "ok";
                } elseif ($return_value['status'] == "Department Add") {
                    $serverdepartmentstatus = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Department";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverdepartmentstatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'departments');
            } elseif ($return_value['isdepartmentstore'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $serverdepartmentstatus = "Data not post on server";
                } elseif ($return_value['status'] == "Department Saving Error") {
                    $serverdepartmentstatus = "Error Department Saving";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $serverdepartmentstatus = "Authentication Fail";
                } elseif ($return_value['status'] == "Improper Department name") {
                    $serverdepartmentstatus = "Improper Department name";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Department";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverdepartmentstatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'departments');
            }
            $msg = JText :: _('DEPARTMENT_SAVED');
        } else {
            if ($return_value == 1) {
                $msg = JText::_('DEPARTMENT_SAVED');
            } else {
                $msg = JText::_('ERROR_SAVING_DEPARTMENT');
            }
        }
        $link = 'index.php?option=com_jsjobs&task=view&layout=departments';
        $this->setRedirect($link, $msg);
    }

    function savecompany() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $jobsharing = $this->getModel('jobsharing', 'JSJobsModel');
        $return_value = $model->storeCompany();
        if (is_array($return_value)) {
            if ($return_value['iscompanystore'] == 1) {
                if ($return_value['status'] == "Company Edit") {
                    $servercompanytatus = "ok";
                } elseif ($return_value['status'] == "Company Add") {
                    $servercompanytatus = "ok";
                } elseif ($return_value['status'] == "Company with logo Add") {
                    $servercompanytatus = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Company";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                if (isset($return_value['companycities'])) {
                    $jobsharing->updateMultiCityServerid($return_value['companycities'], 'companycities');
                }

                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($servercompanytatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'companies');
            } elseif ($return_value['iscompanystore'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $servercompanytatus = "Data not post on server";
                } elseif ($return_value['status'] == "Company Saving Error") {
                    $servercompanytatus = "Error Company Saving";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $servercompanytatus = "Authentication Fail";
                } elseif ($return_value['status'] == "Improper Company name") {
                    $servercompanytatus = "Improper Company name";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Company";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($servercompanytatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'companies');
            }
            $msg = JText :: _('COMPANY_SAVED');
        } else {
            if ($return_value == 1) {
                $msg = JText::_('COMPANY_SAVED');
            } elseif ($return_value == 6) {
                $msg = JText::_('JS_COMPANY_FILE_TYPE_ERROR');
            } else {
                $msg = JText::_('ERROR_SAVING_COMPANY');
            }
        }
        $link = 'index.php?option=com_jsjobs&task=view&layout=companies';
        $this->setRedirect($link, $msg);
    }

    function savefolder() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $jobsharing = & $this->getModel('jobsharing', 'JSJobsModel');
        $return_value = $model->storeFolder();
        if (is_array($return_value)) {
            if ($return_value['isfolderstore'] == 1) {
                if ($return_value['status'] == "Folder Edit") {
                    $serverfolderstatus = "ok";
                } elseif ($return_value['status'] == "Folder Add") {
                    $serverfolderstatus = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Folder";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverfolderstatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'folders');
            } elseif ($return_value['isfolderstore'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $serverfolderstatus = "Data not post on server";
                } elseif ($return_value['status'] == "Folder Saving Error") {
                    $serverfolderstatus = "Error Folder Saving";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $serverfolderstatus = "Authentication Fail";
                } elseif ($return_value['status'] == "Improper Folder name") {
                    $serverfolderstatus = "Improper Folder name";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Folder";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverfolderstatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'folders');
            }
            $msg = JText :: _('JS_FOLDER_SAVED');
        } else {
            if ($return_value == 1) {
                $msg = JText::_('JS_FOLDER_SAVED');
            } elseif ($return_value == 3) {
                $msg = JText::_('JS_FOLDER_ALREADY_EXIST');
            } else {
                $msg = JText::_('JS_ERROR_SAVING_FOLDER');
            }
        }
        $link = 'index.php?option=com_jsjobs&task=view&layout=folders';
        $this->setRedirect($link, $msg);
    }

    function saveemployerpackage() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $return_value = $model->storeEmployerPackage();
        if ($return_value == 1) {
            $msg = JText::_('PACKAGE_SAVED');
            $link = 'index.php?option=com_jsjobs&task=view&layout=employerpackages';
            $this->setRedirect($link, $msg);
        } else {
            $msg = JText::_('ERROR_SAVING_PACKAGE');
            $link = 'index.php?option=com_jsjobs&task=view&layout=employerpackages';
            $this->setRedirect($link, $msg);
        }
    }

    function savejobseekerpackage() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $return_value = $model->storeJobSeekerPackage();
        if ($return_value == 1) {
            $msg = JText::_('PACKAGE_SAVED');
            $link = 'index.php?option=com_jsjobs&task=view&layout=jobseekerpackages';
            $this->setRedirect($link, $msg);
        } else {
            $msg = JText::_('ERROR_SAVING_PACKAGE');
            $link = 'index.php?option=com_jsjobs&task=view&layout=jobseekerpackages';
            $this->setRedirect($link, $msg);
        }
    }

    function savejob() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $jobsharing = & $this->getModel('jobsharing', 'JSJobsModel');
        $return_data = $model->storeJob();
        if (is_array($return_data)) {
            if ($return_data['isjobstore'] == 1) {
                if ($return_data['status'] == "Job Edit") {
                    $serverjobstatus = "ok";
                } elseif ($return_data['status'] == "Job Add") {
                    $serverjobstatus = "ok";
                } elseif ($return_data['status'] == "Edit Job Userfield") {
                    $serverjobstatus = "ok";
                } elseif ($return_data['status'] == "Add Job Userfield") {
                    $serverjobstatus = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_data['referenceid'];
                $logarray['eventtype'] = $return_data['eventtype'];
                $logarray['message'] = $return_data['message'];
                $logarray['event'] = "job";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                if (isset($return_data['jobcities'])) {
                    $jobsharing->updateMultiCityServerid($return_data['jobcities'], 'jobcities');
                }
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverjobstatus, $logarray['referenceid'], $return_data['serverid'], $logarray['uid'], 'jobs');
            } elseif ($return_data['isjobstore'] == 0) {
                if ($return_data['status'] == "Data Empty") {
                    $serverjobstatus = "Data not post on server";
                } elseif ($return_data['status'] == "job Saving Error") {
                    $serverjobstatus = "Error Job Saving";
                } elseif ($return_data['status'] == "Auth Fail") {
                    $serverjobstatus = "Authentication Fail";
                } elseif ($return_data['status'] == "Error Save Job Userfield") {
                    $serverjobstatus = "Error Save Job Userfield";
                } elseif ($return_data['status'] == "Improper job name") {
                    $serverjobstatus = "Improper job name";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_data['referenceid'];
                $logarray['eventtype'] = $return_data['eventtype'];
                $logarray['message'] = $return_data['message'];
                $logarray['event'] = "job";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverjobstatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'jobs');
            }
            $msg = JText::_('JOB_POST_SAVED');
            $link = 'index.php?option=com_jsjobs&task=view&layout=jobs';
            $this->setRedirect($link, $msg);
        } else {
            if ($return_data == 1) {
                $msg = JText::_('JOB_POST_SAVED');
                $link = 'index.php?option=com_jsjobs&task=view&layout=jobs';
                $this->setRedirect($link, $msg);
            } else if ($return_data == 2) {
                $msg = JText::_('ALL_FIELD_MUST_BE_ENTERD');
                $link = 'index.php?option=com_jsjobs&view=application&layout=formjob';
                $this->setRedirect($link, $msg);
            } elseif ($return_data == 12) {
                $msg = JText::_('JS_DESCRIPTION_MUST_BE_ENTERD');
                $link = 'index.php?option=com_jsjobs&view=application&layout=formjob';
                $this->setRedirect($link, $msg);
            } else {
                $msg = JText::_($return_data . 'ERROR_SAVING_JOB');
                $link = 'index.php?option=com_jsjobs&task=view&layout=jobs';
                $this->setRedirect($link, $msg);
            }
        }
    }

    function saveresume() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $jobsharing = & $this->getModel('jobsharing', 'JSJobsModel');
        $return_value = $model->storeResume();
        if (is_array($return_value)) {
            if ($return_value['isresumestore'] == 1) {
                if ($return_value['status'] == "Resume Edit") {
                    $serverresumestatus = "ok";
                } elseif ($return_value['status'] == "Resume Add") {
                    $serverresumestatus = "ok";
                } elseif ($return_value['status'] == "Edit Resume Userfield") {
                    $serverresumestatus = "ok";
                } elseif ($return_value['status'] == "Add Resume Userfield") {
                    $serverresumestatus = "ok";
                } elseif ($return_value['status'] == "Resume with Picture") {
                    $serverresumestatus = "ok";
                } elseif ($return_value['status'] == "Resume with File") {
                    $serverresumestatus = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Resume";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverresumestatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'resume');
            } elseif ($return_value['isresumestore'] == 0) {
                if ($return_value['status'] == "Data Empty") {
                    $serverresumestatus = "Data not post on server";
                } elseif ($return_value['status'] == "Resume Saving Error") {
                    $serverresumestatus = "Error Resume Saving";
                } elseif ($return_value['status'] == "Auth Fail") {
                    $serverresumestatus = "Authentication Fail";
                } elseif ($return_value['status'] == "Improper Resume name") {
                    $serverresumestatus = "Improper Resume name";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_value['referenceid'];
                $logarray['eventtype'] = $return_value['eventtype'];
                $logarray['message'] = $return_value['message'];
                $logarray['event'] = "Resume";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverresumestatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'resume');
            }
            $msg = JText::_('EMP_APP_SAVED');
            $link = 'index.php?option=com_jsjobs&task=view&layout=empapps';
            $this->setRedirect($link, $msg);
        } else {
            if ($return_value == 1) {
                $msg = JText::_('EMP_APP_SAVED');
                $link = 'index.php?option=com_jsjobs&task=view&layout=empapps';
                $this->setRedirect($link, $msg);
            } elseif ($return_value == 2) {
                $msg = JText::_('ALL_FIELD_MUST_BE_ENTERD');
                $link = 'index.php?option=com_jsjobs&view=application&layout=formemp';
                $this->setRedirect($link, $msg);
            } elseif ($return_value == 6) { // file type mismatch
                $msg = JText :: _('JS_FILE_TYPE_ERROR');
                $link = 'index.php?option=com_jsjobs&task=view&layout=empapps';
                $this->setRedirect($link, $msg);
            } else {
                $msg = JText::_('ERROR_SAVING_EMP_APP');
                $link = 'index.php?option=com_jsjobs&task=view&layout=empapps';
                $this->setRedirect($link, $msg);
            }
        }
    }

    function updateactionstatus() {
        $jobid = JRequest::getVar('jobid');
        $resumeid = JRequest::getVar('resumeid');
        $applyid = JRequest::getVar('applyid');
        $action_status = JRequest::getVar('action_status');
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $return_value = $model->updateJobApplyActionStatus($jobid, $resumeid, $applyid, $action_status);
        echo $return_value;
        JFactory::getApplication()->close();
    }

    function saveshortlistcandiate() { //save shortlist candidate
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $session = &JFactory::getSession();
        $data = array();
		$Itemid =  JRequest::getVar('Itemid');
        $data['action'] = JRequest::getVar('action');
        $data['resumeid'] = JRequest::getVar('resumeid');
        $data['jobid'] = JRequest::getVar('jobid');
        $user = & JFactory::getUser();
        $uid = $user->id;
        $return_value = $model->storeShortListCandidate($uid, $data);
        if ($return_value == 1) {
            $msg = JText :: _('JS_SHORT_LIST_CANDIDATE_SAVED');
        } elseif ($return_value == 2) {
            $msg = JText :: _('JS_FILL_REQ_FIELDS');
        } elseif ($return_value == 3) {
            $msg = JText :: _('JS_ALLREADY_SHORTLIST_THIS_CANDIDATE');
        } else {
            $msg = JText :: _($return_value . 'JS_ERROR_SAVING_SHORT_LIST_CANDIDATE');
        }
        $link = 'index.php?option=com_jsjobs&view=application&layout=view_resume&rd=' . $data['resumeid'] . '&oi=' . $data['jobid'];
        $this->setRedirect($link, $msg);
    }

    function savecategory() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $return_value = $model->storeCategory();
        $link = 'index.php?option=com_jsjobs&task=view&layout=categories';
        if (is_array($return_value)) {
            if ($return_value['return_value'] == false) { // jobsharing return value 
                $msg = JText::_('CATEGORY_SAVED');
                if ($return_value['rejected_value'] != "")
                    $msg = JText::_('JS_CATEGORY_SAVED_BUT_SHARING_SERVER_NOT_ACCEPT_THE_JOB_OF_THESE_CATEGORY_DUE_TO_IMPROPER_NAME');
                if ($return_value['authentication_value'] != "")
                    $msg = JText::_('JS_CATEGORY_SAVED_BUT_AUTHENTICATION_FAILED_ON_SHARING_SERVER');
                if ($return_value['server_responce'] != "")
                    $msg = JText::_('JS_CATEGORY_SAVED_BUT_PROBLEM_SYNCHRONIZE_WITH_SHARING_SERVER');
                $this->setRedirect($link, $msg);
            }elseif ($return_value['return_value'] == true) { // jobsharing return value 
                $msg = JText::_('CATEGORY_SAVED');
                $this->setRedirect($link, $msg);
            }
        } else {
            if ($return_value == 1) {
                $msg = JText::_('CATEGORY_SAVED');
                $link = 'index.php?option=com_jsjobs&task=view&layout=categories';
                $this->setRedirect($link, $msg);
            } elseif ($return_value == 2) {
                $msg = JText::_('ALL_FIELD_MUST_BE_ENTERD');
                JRequest :: setVar('view', 'application');
                JRequest :: setVar('hidemainmenu', 1);
                JRequest :: setVar('layout', 'formcategory');
                JRequest :: setVar('msg', $msg);

                // Display based on the set variables
                $this->display();
            } elseif ($return_value == 3) {
                $msg = JText::_('CATEGORY_ALREADY_EXIST');
                JRequest :: setVar('view', 'application');
                JRequest :: setVar('hidemainmenu', 1);
                JRequest :: setVar('layout', 'formcategory');
                JRequest :: setVar('msg', $msg);
                $this->display();
            } else {
                $msg = JText::_('ERROR_SAVING_CATEGORY');
                $link = 'index.php?option=com_jsjobs&task=view&layout=categories';
                $this->setRedirect($link, $msg);
            }
        }
    }

    function savesubcategory() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $return_value = $model->storeSubCategory();
        $session = JFactory::getSession();
        $categoryid = $session->get('sub_categoryid');
        $link = 'index.php?option=com_jsjobs&task=view&layout=subcategories&cd=' . $categoryid;
        if (is_array($return_value)) {
            if ($return_value['return_value'] == false) { // jobsharing return value 
                $msg = JText::_('CATEGORY_SAVED');
                if ($return_value['rejected_value'] != "")
                    $msg = JText::_('JS_SUBCATEGORY_SAVED_BUT_SHARING_SERVER_NOT_ACCEPT_THE_JOB_OF_THESE_SUBCATEGORY_DUE_TO_IMPROPER_NAME');
                if ($return_value['authentication_value'] != "")
                    $msg = JText::_('JS_SUBCATEGORY_SAVED_BUT_AUTHENTICATION_FAILED_ON_SHARING_SERVER');
                if ($return_value['server_responce'] != "")
                    $msg = JText::_('JS_SUBCATEGORY_SAVED_BUT_PROBLEM_SYNCHRONIZE_WITH_SHARING_SERVER');
                $this->setRedirect($link, $msg);
            }elseif ($return_value['return_value'] == true) { // jobsharing return value 
                $msg = JText::_('CATEGORY_SAVED');
                $this->setRedirect($link, $msg);
            }
        } else {
            if ($return_value == 1) {
                $msg = JText::_('CATEGORY_SAVED');
                $link = 'index.php?option=com_jsjobs&task=view&layout=subcategories&cd=' . $categoryid;
                $this->setRedirect($link, $msg);
            } else if ($return_value == 2) {
                $msg = JText::_('ALL_FIELD_MUST_BE_ENTERD');
                JRequest :: setVar('view', 'application');
                JRequest :: setVar('hidemainmenu', 1);
                JRequest :: setVar('layout', 'formsubcategory');
                JRequest :: setVar('msg', $msg);
                // Display based on the set variables
                $this->display(); //parent :: display();
            } else if ($return_value == 3) {
                $msg = JText::_('CATEGORY_ALREADY_EXIST');
                JRequest :: setVar('view', 'application');
                JRequest :: setVar('hidemainmenu', 1);
                JRequest :: setVar('layout', 'formsubcategory');
                JRequest :: setVar('msg', $msg);
                $this->display(); //parent :: display();
            } else {
                $msg = JText::_('ERROR_SAVING_CATEGORY');
                $link = 'index.php?option=com_jsjobs&task=view&layout=subcategories&cd=' . $categoryid;
                $this->setRedirect($link, $msg);
            }
        }
    }

    function savejobtype() {
        $redirect = $this->storejobtype('saveclose');
    }

    function savejobtypesave() {
        $redirect = $this->storejobtype('save');
    }

    function savejobtypeandnew() {
        $redirect = $this->storejobtype('saveandnew');
    }

    function storejobtype($callfrom) {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $return_value = $model->storeJobType();
        $link = 'index.php?option=com_jsjobs&task=view&layout=jobtypes';
        if (is_array($return_value)) {
            if ($return_value['issharing'] == 1) {
                if ($return_value['return_value'] == false) { // jobsharing return value 
                    $msg = JText::_('JS_JOB_TYPE_SAVED');
                    if ($return_value['rejected_value'] != "")
                        $msg = JText::_('JS_JOB_TYPE_SAVED_BUT_SHARING_SERVER_NOT_ACCEPT_THE_JOB_OF_THESE_JOB_TYPE_DUE_TO_IMPROPER_NAME');
                    if ($return_value['authentication_value'] != "")
                        $msg = JText::_('JS_JOB_TYPE_SAVED_BUT_AUTHENTICATION_FAILED_ON_SHARING_SERVER');
                    if ($return_value['server_responce'] != "")
                        $msg = JText::_('JS_JOB_TYPE_SAVED_BUT_PROBLEM_SYNCHRONIZE_WITH_SHARING_SERVER');
                    $this->setRedirect($link, $msg);
                }elseif ($return_value == true) { // jobsharing return value 
                    $redirect = 1;
                }
            } elseif ($return_value['issharing'] == 0) {
                if ($return_value[1] == 1) {
                    $redirect = 1;
                } elseif ($return_value[1] == 3) {
                    $msg = JText::_('JS_JOB_TYPE_ALREADY_EXIST');
                    JRequest :: setVar('view', 'application');
                    JRequest :: setVar('hidemainmenu', 1);
                    JRequest :: setVar('layout', 'formjobtype');
                    JRequest :: setVar('msg', $msg);
                    $this->display();
                }
            }
            if ($redirect == 1) {
                $msg = JText::_('JS_JOB_TYPE_SAVED');
                if ($callfrom == 'saveclose') {
                    $link = 'index.php?option=com_jsjobs&task=view&layout=jobtypes';
                } elseif ($callfrom == 'save') {
                    $link = 'index.php?option=com_jsjobs&view=application&layout=formjobtype&cid[]=' . $return_value[2];
                } elseif ($callfrom == 'saveandnew') {
                    $link = 'index.php?option=com_jsjobs&view=application&layout=formjobtype';
                }
                $this->setRedirect($link, $msg);
            } elseif ($return_value == false) {
                $msg = JText::_('JS_ERROR_SAVING_JOB_TYPE');
                $this->setRedirect($link, $msg);
            }
        } else {
            $msg = JText::_('JS_ERROR_SAVING_JOB_TYPE');
            $this->setRedirect($link, $msg);
        }
    }

    function savejobcareerlevel() {
        $redirect = $this->savecareerlevel('saveclose');
    }

    function savejobcareerlevelsave() {
        $redirect = $this->savecareerlevel('save');
    }

    function savejobcareerlevelandnew() {
        $redirect = $this->savecareerlevel('saveandnew');
    }

    function savecareerlevel($callfrom) {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $return_value = $model->storeCareerLevel();
        $link = 'index.php?option=com_jsjobs&task=view&layout=careerlevels';
        if (is_array($return_value)) {
            if ($return_value['issharing'] == 1) {
                if ($return_value['return_value'] == false) { // jobsharing return value 
                    $msg = JText::_('JS_CAREER_LEVEL_SAVED');
                    if ($return_value['rejected_value'] != "")
                        $msg = JText::_('JS_CAREER_LEVEL_SAVED_BUT_SHARING_SERVER_NOT_ACCEPT_THE_JOB_OF_THESE_CAREER_LEVEL_DUE_TO_IMPROPER_NAME');
                    if ($return_value['authentication_value'] != "")
                        $msg = JText::_('JS_CAREER_LEVEL_SAVED_BUT_AUTHENTICATION_FAILED_ON_SHARING_SERVER');
                    if ($return_value['server_responce'] != "")
                        $msg = JText::_('JS_CAREER_LEVEL_SAVED_BUT_PROBLEM_SYNCHRONIZE_WITH_SHARING_SERVER');
                    $this->setRedirect($link, $msg);
                }elseif ($return_value['return_value'] == true) { // jobsharing return value 
                    $redirect = 1;
                }
            } elseif ($return_value['issharing'] == 0) {
                if ($return_value[1] == 1) {
                    $redirect = 1;
                } elseif ($return_value[1] == 3) {
                    $msg = JText::_('JS_CAREER_LEVEL_ALREADY_EXIST');
                    JRequest :: setVar('view', 'application');
                    JRequest :: setVar('hidemainmenu', 1);
                    JRequest :: setVar('layout', 'formcareerlevels');
                    JRequest :: setVar('msg', $msg);
                    $this->display();
                }
            }
            if ($redirect == 1) {
                $msg = JText::_('JS_CAREER_LEVEL_SAVED');
                if ($callfrom == 'saveclose') {
                    $link = 'index.php?option=com_jsjobs&task=view&layout=careerlevels';
                } elseif ($callfrom == 'save') {
                    $link = 'index.php?option=com_jsjobs&view=application&layout=formcareerlevels&cid[]=' . $return_value[2];
                } elseif ($callfrom == 'saveandnew') {
                    $link = 'index.php?option=com_jsjobs&view=application&layout=formcareerlevels';
                }
                $this->setRedirect($link, $msg);
            } elseif ($return_value == false) {
                $msg = JText::_('JS_ERROR_SAVING_CAREER_LEVEL');
                $this->setRedirect($link, $msg);
            }
        } else {

            $msg = JText::_('JS_ERROR_SAVING_CAREER_LEVEL');
            $this->setRedirect($link, $msg);
        }
    }

    function savejobexperience() {
        $redirect = $this->saveexperience('saveclose');
    }

    function savejobexperiencesave() {
        $redirect = $this->saveexperience('save');
    }

    function savejobexperienceandnew() {
        $redirect = $this->saveexperience('saveandnew');
    }

    function saveexperience($callfrom) {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $return_value = $model->storeExperience();
        $link = 'index.php?option=com_jsjobs&task=view&layout=experience';
        if (is_array($return_value)) {
            if ($return_value['issharing'] == 1) {
                if ($return_value['return_value'] == false) { // jobsharing return value 
                    $msg = JText::_('JS_EXPERIENCE_SAVED');
                    if ($return_value['rejected_value'] != "")
                        $msg = JText::_('JS_EXPERIENCE_SAVED_BUT_SHARING_SERVER_NOT_ACCEPT_THE_JOB_OF_THESE_EXPERIENCE_DUE_TO_IMPROPER_NAME');
                    if ($return_value['authentication_value'] != "")
                        $msg = JText::_('JS_EXPERIENCE_SAVED_BUT_AUTHENTICATION_FAILED_ON_SHARING_SERVER');
                    if ($return_value['server_responce'] != "")
                        $msg = JText::_('JS_EXPERIENCE_SAVED_BUT_PROBLEM_SYNCHRONIZE_WITH_SHARING_SERVER');
                    $this->setRedirect($link, $msg);
                }elseif ($return_value['return_value'] == true) { // jobsharing return value 
                    $redirect = 1;
                }
            } elseif ($return_value['issharing'] == 0) {
                if ($return_value[1] == 1) {
                    $redirect = 1;
                } elseif ($return_value[1] == 3) {
                    $msg = JText::_('JS_EXPERIENCE_ALREADY_EXIST');
                    JRequest :: setVar('view', 'application');
                    JRequest :: setVar('hidemainmenu', 1);
                    JRequest :: setVar('layout', 'formexperience');
                    JRequest :: setVar('msg', $msg);
                    $this->display();
                }
            }
            if ($redirect == 1) {
                $msg = JText::_('JS_EXPERIENCE_SAVED');
                if ($callfrom == 'saveclose') {
                    $link = 'index.php?option=com_jsjobs&task=view&layout=experience';
                } elseif ($callfrom == 'save') {
                    $link = 'index.php?option=com_jsjobs&view=application&layout=formexperience&cid[]=' . $return_value[2];
                } elseif ($callfrom == 'saveandnew') {
                    $link = 'index.php?option=com_jsjobs&view=application&layout=formexperience';
                }
                $this->setRedirect($link, $msg);
            } elseif ($return_value == false) {
                $msg = JText::_('JS_ERROR_SAVING_EXPERIENCE');
                $this->setRedirect($link, $msg);
            }
        } else {
            $msg = JText::_('JS_ERROR_SAVING_EXPERIENCE');
            $this->setRedirect($link, $msg);
        }
    }

    function savejobcurrency() {
        $redirect = $this->savecurrency('saveclose');
    }

    function savejobcurrencysave() {
        $redirect = $this->savecurrency('save');
    }

    function savejobcurrencyandnew() {
        $redirect = $this->savecurrency('saveandnew');
    }

    function savecurrency($callfrom) {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $return_value = $model->storeCurrency();
        $link = 'index.php?option=com_jsjobs&task=view&layout=currency';
        if (is_array($return_value)) {
            if ($return_value['issharing'] == 1) {
                if ($return_value['return_value'] == false) { // jobsharing return value 
                    $msg = JText::_('JS_CURRENCY_SAVED');
                    if ($return_value['rejected_value'] != "")
                        $msg = JText::_('JS_CURRENCY_SAVED_BUT_SHARING_SERVER_NOT_ACCEPT_THE_JOB_OF_THESE_CURRENCY_DUE_TO_IMPROPER_NAME');
                    if ($return_value['authentication_value'] != "")
                        $msg = JText::_('JS_CURRENCY_SAVED_BUT_AUTHENTICATION_FAILED_ON_SHARING_SERVER');
                    if ($return_value['server_responce'] != "")
                        $msg = JText::_('JS_CURRENCY_SAVED_BUT_PROBLEM_SYNCHRONIZE_WITH_SHARING_SERVER');
                    $this->setRedirect($link, $msg);
                }elseif ($return_value['return_value'] == true) { // jobsharing return value 
                    $redirect = 1;
                }
            } elseif ($return_value['issharing'] == 0) {
                if ($return_value[1] == 1) {
                    $redirect = 1;
                } elseif ($return_value[1] == 3) {
                    $msg = JText::_('JS_CURRENCY_ALREADY_EXIST');
                    JRequest :: setVar('view', 'application');
                    JRequest :: setVar('hidemainmenu', 1);
                    JRequest :: setVar('layout', 'formcurrency');
                    JRequest :: setVar('msg', $msg);
                    $this->display();
                }
            }
            if ($redirect == 1) {
                $msg = JText::_('JS_CURRENCY_SAVED');
                if ($callfrom == 'saveclose') {
                    $link = 'index.php?option=com_jsjobs&task=view&layout=currency';
                } elseif ($callfrom == 'save') {
                    $link = 'index.php?option=com_jsjobs&view=application&layout=formcurrency&cid[]=' . $return_value[2];
                } elseif ($callfrom == 'saveandnew') {
                    $link = 'index.php?option=com_jsjobs&view=application&layout=formcurrency';
                }
                $this->setRedirect($link, $msg);
            } elseif ($return_value == false) {
                $msg = JText::_('JS_ERROR_SAVING_CURRENCY');
                $this->setRedirect($link, $msg);
            }
        } else {

            $msg = JText::_('JS_ERROR_SAVING_CURRENCY');
            $this->setRedirect($link, $msg);
        }
    }

    function savejobsalaryrangetype() {
        $redirect = $this->savesalaryrangetype('saveclose');
    }

    function savejobsalaryrangetypesave() {
        $redirect = $this->savesalaryrangetype('save');
    }

    function savejobsalaryrangetypeandnew() {
        $redirect = $this->savesalaryrangetype('saveandnew');
    }

    function savesalaryrangetype($callfrom) {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $return_value = $model->storeSalaryRangeType();
        $link = 'index.php?option=com_jsjobs&task=view&layout=salaryrangetype';
        if (is_array($return_value)) {
            if ($return_value['issharing'] == 1) {
                if ($return_value['return_value'] == false) { // jobsharing return value 
                    $msg = JText::_('JS_SALARY_RANGE_TYPE_SAVED');
                    if ($return_value['rejected_value'] != "")
                        $msg = JText::_('JS_SALARY_RANGE_TYPE_SAVED_BUT_SHARING_SERVER_NOT_ACCEPT_THE_JOB_OF_THESE_SALARY_RANGE_TYPE_DUE_TO_IMPROPER_NAME');
                    if ($return_value['authentication_value'] != "")
                        $msg = JText::_('JS_SALARY_RANGE_TYPE_SAVED_BUT_AUTHENTICATION_FAILED_ON_SHARING_SERVER');
                    if ($return_value['server_responce'] != "")
                        $msg = JText::_('JS_SALARY_RANGE_TYPE_SAVED_BUT_PROBLEM_SYNCHRONIZE_WITH_SHARING_SERVER');
                    $this->setRedirect($link, $msg);
                }elseif ($return_value['return_value'] == true) { // jobsharing return value 
                    $redirect = 1;
                }
            } elseif ($return_value['issharing'] == 0) {
                if ($return_value[1] == 1) {
                    $redirect = 1;
                } elseif ($return_value[1] == 3) {
                    $msg = JText::_('JS_SALARY_RANGE_TYPE_ALREADY_EXIST');
                    JRequest :: setVar('view', 'application');
                    JRequest :: setVar('hidemainmenu', 1);
                    JRequest :: setVar('layout', 'formsalaryrangetype');
                    JRequest :: setVar('msg', $msg);
                    $this->display();
                }
            }
            if ($redirect == 1) {
                $msg = JText::_('JS_SALARY_RANGE_TYPE_SAVED');
                if ($callfrom == 'saveclose') {
                    $link = 'index.php?option=com_jsjobs&task=view&layout=salaryrangetype';
                } elseif ($callfrom == 'save') {
                    $link = 'index.php?option=com_jsjobs&view=application&layout=formsalaryrangetype&cid[]=' . $return_value[2];
                } elseif ($callfrom == 'saveandnew') {
                    $link = 'index.php?option=com_jsjobs&view=application&layout=formsalaryrangetype';
                }
                $this->setRedirect($link, $msg);
            } elseif ($return_value == false) {
                $msg = JText::_('JS_ERROR_SAVING_SALARY_RANGE_TYPE');
                $this->setRedirect($link, $msg);
            }
        } else {
            $msg = JText::_('JS_ERROR_SAVING_SALARY_RANGE_TYPE');
            $this->setRedirect($link, $msg);
        }
    }

    function savejobstatus() {
        $redirect = $this->storejobStatus('saveclose');
    }

    function savejobstatussave() {
        $redirect = $this->storejobStatus('save');
    }

    function savejobstatusandnew() {
        $redirect = $this->storejobStatus('saveandnew');
    }

    function storejobStatus($callfrom) {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $return_value = $model->storeJobStatus();
        $link = 'index.php?option=com_jsjobs&task=view&layout=jobstatus';
        if (is_array($return_value)) {
            if ($return_value['issharing'] == 1) {
                if ($return_value['return_value'] == false) { // jobsharing return value 
                    $msg = JText::_('JS_JOB_STATUS_SAVED');
                    if ($return_value['rejected_value'] != "")
                        $msg = JText::_('JS_JOB_STATUS_SAVED_BUT_SHARING_SERVER_NOT_ACCEPT_THE_JOB_OF_THESE_JOB_STATUS_DUE_TO_IMPROPER_NAME');
                    if ($return_value['authentication_value'] != "")
                        $msg = JText::_('JS_JOB_STATUS_SAVED_BUT_AUTHENTICATION_FAILED_ON_SHARING_SERVER');
                    if ($return_value['server_responce'] != "")
                        $msg = JText::_('JS_JOB_STATUS_SAVED_BUT_PROBLEM_SYNCHRONIZE_WITH_SHARING_SERVER');
                    $this->setRedirect($link, $msg);
                }elseif ($return_value['return_value'] == true) { // jobsharing return value 
                    $redirect = 1;
                }
            } elseif ($return_value['issharing'] == 0) {
                if ($return_value[1] == 1) {
                    $redirect = 1;
                } elseif ($return_value[1] == 3) {
                    $msg = JText::_('JS_JOB_STATUS_ALREADY_EXIST');
                    JRequest :: setVar('view', 'application');
                    JRequest :: setVar('hidemainmenu', 1);
                    JRequest :: setVar('layout', 'formjobstatus');
                    JRequest :: setVar('msg', $msg);
                    $this->display();
                }
            }
            if ($redirect == 1) {
                $msg = JText::_('JS_JOB_STATUS_SAVED');
                if ($callfrom == 'saveclose') {
                    $link = 'index.php?option=com_jsjobs&task=view&layout=jobstatus';
                } elseif ($callfrom == 'save') {
                    $link = 'index.php?option=com_jsjobs&view=application&layout=formjobstatus&cid[]=' . $return_value[2];
                } elseif ($callfrom == 'saveandnew') {
                    $link = 'index.php?option=com_jsjobs&view=application&layout=formjobstatus';
                }
                $this->setRedirect($link, $msg);
            } elseif ($return_value == false) {
                $msg = JText::_('JS_ERROR_SAVING_JOB_STATUS');
                $this->setRedirect($link, $msg);
            }
        } else {
            $msg = JText::_('JS_ERROR_SAVING_JOB_STATUS');
            $this->setRedirect($link, $msg);
        }
    }

    function savejobshift() {
        $redirect = $this->saveshift('saveclose');
    }

    function savejobshiftsave() {
        $redirect = $this->saveshift('save');
    }

    function savejobshiftandnew() {
        $redirect = $this->saveshift('saveandnew');
    }

    function saveshift($callfrom) {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $return_value = $model->storeShift();
        $link = 'index.php?option=com_jsjobs&task=view&layout=shifts';
        if (is_array($return_value)) {
            if ($return_value['issharing'] == 1) {
                if ($return_value['return_value'] == false) { // jobsharing return value 
                    $msg = JText::_('JS_SHIFT_SAVED');
                    if ($return_value['rejected_value'] != "")
                        $msg = JText::_('JS_SHIFT_SAVED_BUT_SHARING_SERVER_NOT_ACCEPT_THE_JOB_OF_THESE_SHIFT_DUE_TO_IMPROPER_NAME');
                    if ($return_value['authentication_value'] != "")
                        $msg = JText::_('JS_SHIFT_SAVED_BUT_AUTHENTICATION_FAILED_ON_SHARING_SERVER');
                    if ($return_value['server_responce'] != "")
                        $msg = JText::_('JS_SHIFT_SAVED_BUT_PROBLEM_SYNCHRONIZE_WITH_SHARING_SERVER');
                    $this->setRedirect($link, $msg);
                }elseif ($return_value['return_value'] == true) { // jobsharing return value 
                    $redirect = 1;
                }
            } elseif ($return_value['issharing'] == 0) {
                if ($return_value[1] == 1) {
                    $redirect = 1;
                } elseif ($return_value[1] == 3) {
                    $msg = JText::_('JS_JOB_SHIFT_ALREADY_EXIST');
                    JRequest :: setVar('view', 'application');
                    JRequest :: setVar('hidemainmenu', 1);
                    JRequest :: setVar('layout', 'formshift');
                    JRequest :: setVar('msg', $msg);
                    $this->display();
                }
            }
            if ($redirect == 1) {
                $msg = JText::_('JS_SHIFT_SAVED');
                if ($callfrom == 'saveclose') {
                    $link = 'index.php?option=com_jsjobs&task=view&layout=shifts';
                } elseif ($callfrom == 'save') {
                    $link = 'index.php?option=com_jsjobs&view=application&layout=formshift&cid[]=' . $return_value[2];
                } elseif ($callfrom == 'saveandnew') {
                    $link = 'index.php?option=com_jsjobs&view=application&layout=formshift';
                }
                $this->setRedirect($link, $msg);
            } elseif ($return_value == false) {
                $msg = JText::_('JS_ERROR_SAVING_SHIFT');
                $this->setRedirect($link, $msg);
            }
        } else {
            $msg = JText::_('JS_ERROR_SAVING_SHIFT');
            $this->setRedirect($link, $msg);
        }
    }

    function savejobhighesteducation() {
        $redirect = $this->savehighesteducation('saveclose');
    }

    function savejobhighesteducationsave() {
        $redirect = $this->savehighesteducation('save');
    }

    function savejobhighesteducationandnew() {
        $redirect = $this->savehighesteducation('saveandnew');
    }

    function savehighesteducation($callfrom) {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $return_value = $model->storeHighestEducation();
        $link = 'index.php?option=com_jsjobs&task=view&layout=highesteducations';
        if (is_array($return_value)) {
            if ($return_value['issharing'] == 1) {
                if ($return_value['return_value'] == false) { // jobsharing return value 
                    $msg = JText::_('JS_HIGHEST_EDUCATION_SAVED');
                    if ($return_value['rejected_value'] != "")
                        $msg = JText::_('JS_HIGHEST_EDUCATION_SAVED_BUT_SHARING_SERVER_NOT_ACCEPT_THE_JOB_OF_THESE_HEIGHEST_EDUCATION_DUE_TO_IMPROPER_NAME');
                    if ($return_value['authentication_value'] != "")
                        $msg = JText::_('JS_HIGHEST_EDUCATION_SAVED_BUT_AUTHENTICATION_FAILED_ON_SHARING_SERVER');
                    if ($return_value['server_responce'] != "")
                        $msg = JText::_('JS_HIGHEST_EDUCATION_SAVED_BUT_PROBLEM_SYNCHRONIZE_WITH_SHARING_SERVER');
                    $this->setRedirect($link, $msg);
                }elseif ($return_value['return_value'] == true) { // jobsharing return value 
                    $redirect = 1;
                }
            } elseif ($return_value['issharing'] == 0) {
                if ($return_value[1] == 1) {
                    $redirect = 1;
                } elseif ($return_value[1] == 3) {
                    $msg = JText::_('JS_HIGHEST_EDUCATION_ALREADY_EXIST');
                    JRequest :: setVar('view', 'application');
                    JRequest :: setVar('hidemainmenu', 1);
                    JRequest :: setVar('layout', 'formhighesteducation');
                    JRequest :: setVar('msg', $msg);
                    $this->display();
                }
            }
            if ($redirect == 1) {
                $msg = JText::_('JS_HIGHEST_EDUCATION_SAVED');
                if ($callfrom == 'saveclose') {
                    $link = 'index.php?option=com_jsjobs&task=view&layout=highesteducations';
                } elseif ($callfrom == 'save') {
                    $link = 'index.php?option=com_jsjobs&view=application&layout=formhighesteducation&cid[]=' . $return_value[2];
                } elseif ($callfrom == 'saveandnew') {
                    $link = 'index.php?option=com_jsjobs&view=application&layout=formhighesteducation';
                }
                $this->setRedirect($link, $msg);
            } elseif ($return_value == false) {
                $msg = JText::_('JS_ERROR_SAVING_HIGHEST_EDUCATION');
                $this->setRedirect($link, $msg);
            }
        } else {
            $msg = JText::_('JS_ERROR_SAVING_HIGHEST_EDUCATION');
            $this->setRedirect($link, $msg);
        }
    }

    function savejobage() {
        $redirect = $this->saveages('saveclose');
    }

    function savejobagesave() {
        $redirect = $this->saveages('save');
    }

    function savejobageandnew() {
        $redirect = $this->saveages('saveandnew');
    }

    function saveages($callfrom) {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $return_value = $model->storeAges();
        $link = 'index.php?option=com_jsjobs&task=view&layout=ages';
        if (is_array($return_value)) {
            if ($return_value['issharing'] == 1) {
                if ($return_value['return_value'] == false) { // jobsharing return value 
                    $msg = JText::_('JS_AGE_SAVED');
                    if ($return_value['rejected_value'] != "")
                        $msg = JText::_('JS_AGE_SAVED_BUT_SHARING_SERVER_NOT_ACCEPT_THE_JOB_OF_THESE_AGES_DUE_TO_IMPROPER_NAME');
                    if ($return_value['authentication_value'] != "")
                        $msg = JText::_('JS_AGE_SAVED_BUT_AUTHENTICATION_FAILED_ON_SHARING_SERVER');
                    if ($return_value['server_responce'] != "")
                        $msg = JText::_('JS_AGE_SAVED_BUT_PROBLEM_SYNCHRONIZE_WITH_SHARING_SERVER');
                    $this->setRedirect($link, $msg);
                }elseif ($return_value['return_value'] == true) { // jobsharing return value 
                    $redirect = 1;
                }
            } elseif ($return_value['issharing'] == 0) {
                if ($return_value[1] == 1) {
                    $redirect = 1;
                } elseif ($return_value[1] == 3) {
                    $msg = JText::_('JS_AGE_ALREADY_EXIST');
                    JRequest :: setVar('view', 'application');
                    JRequest :: setVar('hidemainmenu', 1);
                    JRequest :: setVar('layout', 'formages');
                    JRequest :: setVar('msg', $msg);
                    $this->display();
                }
            }
            if ($redirect == 1) {
                $msg = JText::_('JS_AGE_SAVED');
                if ($callfrom == 'saveclose') {
                    $link = 'index.php?option=com_jsjobs&task=view&layout=ages';
                } elseif ($callfrom == 'save') {
                    $link = 'index.php?option=com_jsjobs&view=application&layout=formages&cid[]=' . $return_value[2];
                } elseif ($callfrom == 'saveandnew') {
                    $link = 'index.php?option=com_jsjobs&view=application&layout=formages';
                }
                $this->setRedirect($link, $msg);
            } elseif ($return_value == false) {
                $msg = JText::_('JS_ERROR_SAVING_AGE');
                $this->setRedirect($link, $msg);
            }
        } else {
            $msg = JText::_('JS_ERROR_SAVING_AGE');
            $this->setRedirect($link, $msg);
        }
    }

    function savejobsalaryrange() {
        $redirect = $this->savesalaryrange('saveclose');
    }

    function savejobsalaryrangesave() {
        $redirect = $this->savesalaryrange('save');
    }

    function savejobsalaryrangeandnew() {
        $redirect = $this->savesalaryrange('saveandnew');
    }

    function savesalaryrange($callfrom) {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $return_value = $model->storeSalaryRange();
        $link = 'index.php?option=com_jsjobs&task=view&layout=salaryrange';
        if (is_array($return_value)) {
            if ($return_value['issharing'] == 1) {
                if ($return_value['return_value'] == false) { // jobsharing return value 
                    $msg = JText::_('SALARY_RANGE_SAVED');
                    if ($return_value['rejected_value'] != "")
                        $msg = JText::_('SALARY_RANGE_SAVED_BUT_SHARING_SERVER_NOT_ACCEPT_THE_JOB_OF_THESE_SALARYRANGE_DUE_TO_IMPROPER_NAME');
                    if ($return_value['authentication_value'] != "")
                        $msg = JText::_('SALARY_RANGE_SAVED_BUT_AUTHENTICATION_FAILED_ON_SHARING_SERVER');
                    if ($return_value['server_responce'] != "")
                        $msg = JText::_('SALARY_RANGE_SAVED_BUT_PROBLEM_SYNCHRONIZE_WITH_SHARING_SERVER');
                    $this->setRedirect($link, $msg);
                }elseif ($return_value['return_value'] == true) { // jobsharing return value 
                    $redirect = 1;

                    $msg = JText::_('SALARY_RANGE_SAVED');
                    $this->setRedirect($link, $msg);
                }
            } elseif ($return_value['issharing'] == 0) {
                if ($return_value[1] == 1) {
                    $redirect = 1;
                    $msg = JText::_('SALARY_RANGE_SAVED');
                    $link = 'index.php?option=com_jsjobs&task=view&layout=salaryrange';
                    $this->setRedirect($link, $msg);
                } else if ($return_value[1] == 2) {
                    $msg = JText::_('ALL_FIELD_MUST_BE_ENTERD');
                    JRequest :: setVar('view', 'application');
                    JRequest :: setVar('hidemainmenu', 1);
                    JRequest :: setVar('layout', 'formsalaryrange');
                    JRequest :: setVar('msg', $msg);
                    // Display based on the set variables
                    $this->display();
                } else if ($return_value[1] == 3) {
                    $msg = JText::_('RANGE_ALREADY_EXIST');
                    JRequest :: setVar('view', 'application');
                    JRequest :: setVar('hidemainmenu', 1);
                    JRequest :: setVar('layout', 'formsalaryrange');
                    JRequest :: setVar('msg', $msg);
                    $this->display();
                }
            }
            if ($redirect == 1) {
                $msg = JText::_('SALARY_RANGE_SAVED');
                if ($callfrom == 'saveclose') {
                    $link = 'index.php?option=com_jsjobs&task=view&layout=salaryrange';
                } elseif ($callfrom == 'save') {
                    $link = 'index.php?option=com_jsjobs&view=application&layout=formsalaryrange&cid[]=' . $return_value[2];
                } elseif ($callfrom == 'saveandnew') {
                    $link = 'index.php?option=com_jsjobs&view=application&layout=formsalaryrange';
                }
                $this->setRedirect($link, $msg);
            } elseif ($return_value == false) {
                $msg = JText::_('JS_ERROR_SAVING_AGE');
                $this->setRedirect($link, $msg);
            }
        } else {
            $msg = JText::_('ERROR_SAVING_RANGE');
            $link = 'index.php?option=com_jsjobs&task=view&layout=salaryrange';
            $this->setRedirect($link, $msg);
        }
    }

    function saveactivate() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $return_value = $model->storeActivate();
        if ($return_value == 1) {
            $msg = JText::_('JS_JOBS_ACTIVATED');
            $session = JFactory::getSession();
            $config = null;
            $session->set('jsjobconfig_dft', $config);
        } elseif ($return_value == 3) {
            $msg = JText::_('JS_INVALID_ACTIVATION_KEY');
        } elseif ($return_value == 4) {
            $msg = JText::_('ERROR_CAN_NOT_ACTIVATE_JS_JOBS');
        } else {
            $msg = JText::_('ERROR_CAN_NOT_ACTIVATE_JS_JOBS');
        }
        $link = 'index.php?option=com_jsjobs&task=view&layout=updates';
        $this->setRedirect($link, $msg);
    }

    function saverole() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $return_value = $model->storeRole();
        if ($return_value == 1) {
            $msg = JText::_('ROLE_SAVED');
            $link = 'index.php?option=com_jsjobs&task=view&layout=roles';
            $this->setRedirect($link, $msg);
        } else {
            $msg = JText::_('ERROR_SAVING_ROLE');
            $link = 'index.php?option=com_jsjobs&task=view&layout=roles';
            $this->setRedirect($link, $msg);
        }

        $link = 'index.php?option=com_jsjobs&c=application&layout=roles';
    }

    function saveuserrole() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $return_value = $model->storeUserRole();
        if ($return_value == 1) {
            $msg = JText::_('ROLE_SAVED');
            $link = 'index.php?option=com_jsjobs&task=view&layout=users';
            $this->setRedirect($link, $msg);
        } else {
            $msg = JText::_('ERROR_SAVING_ROLE');
            $link = 'index.php?option=com_jsjobs&task=view&layout=users';
            $this->setRedirect($link, $msg);
        }
    }

    function saveuserfield() {
        echo '<br/> save user field';
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $return_value = $model->storeUserField();
        if ($return_value == 1) {
            $msg = JText::_('USER_FIELD_SAVED');
            $link = 'index.php?option=com_jsjobs&task=view&layout=userfields';
            $this->setRedirect($link, $msg);
        } else if ($return_value == 2) {
            $msg = JText::_('ALL_FIELD_MUST_BE_ENTERD');
            JRequest :: setVar('view', 'application');
            JRequest :: setVar('hidemainmenu', 1);
            JRequest :: setVar('layout', 'formuserfield');
            JRequest :: setVar('msg', $msg);
            // Display based on the set variables
            parent :: display();
        } else {
            $msg = JText::_('ERROR_SAVING_USER_FIELD');
            $link = 'index.php?option=com_jsjobs&view=application&layout=formuserfield';
            $this->setRedirect($link, $msg);
        }
    }

    function publish() {
        $cur_layout = $_SESSION['cur_layout'];
        if ($cur_layout == 'countries')
            $this->publishcountries();
        elseif ($cur_layout == 'states')
            $this->publishstates();
        elseif ($cur_layout == 'counties')
            $this->publishcounties();
        elseif ($cur_layout == 'cities')
            $this->publishcities();
        elseif ($cur_layout == 'fieldsordering')
            $this->publishunpublishfields(1);
    }

    function publishunpublishfields($call) {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $ff = JRequest::getVar('ff');
        $return_value = $model->publishunpublishfields($call);
        if ($return_value == 1) {
            $msg = JText::_('JS_PUBLISHED');
        } else {
            $msg = JText::_('JS_ERROR_PUBLISHING');
        }
        $link = 'index.php?option=com_jsjobs&task=view&layout=fieldsordering&ff=' . $ff;
        $this->setRedirect($link, $msg);
    }

    function publishcountries() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $return_value = $model->publishcountries();
        if ($return_value == 1) {
            $msg = JText::_('JS_PUBLISHED');
        } else {
            $msg = JText::_('JS_ERROR_PUBLISHING');
        }

        $link = 'index.php?option=com_jsjobs&task=view&layout=countries';
        $this->setRedirect($link, $msg);
    }

    function publishstates() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $ct = JRequest::getVar('ct');
        $return_value = $model->publishstates();
        if ($return_value == 1) {
            $msg = JText::_('JS_PUBLISHED');
        } else {
            $msg = JText::_('JS_ERROR_PUBLISHING');
        }

        $link = 'index.php?option=com_jsjobs&task=view&layout=states&ct=' . $ct;
        $this->setRedirect($link, $msg);
    }

    function publishcounties() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $sd = JRequest::getVar('sd');
        $return_value = $model->publishcounties();
        if ($return_value == 1) {
            $msg = JText::_('JS_PUBLISHED');
        } else {
            $msg = JText::_('JS_ERROR_PUBLISHING');
        }

        $link = 'index.php?option=com_jsjobs&task=view&layout=counties&sd=' . $sd;
        $this->setRedirect($link, $msg);
    }

    function publishcities() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $session = JFactory::getSession();
        $country = $session->get('countryid');
        $stateid = $session->get('stateid');
        $return_value = $model->publishcities();
        if ($return_value == 1) {
            $msg = JText::_('JS_PUBLISHED');
        } else {
            $msg = JText::_('JS_ERROR_PUBLISHING');
        }

        $link = 'index.php?option=com_jsjobs&task=view&layout=cities&sd=' . $stateid . '&ct=' . $country;
        $this->setRedirect($link, $msg);
    }

    function unpublish() {
        $cur_layout = $_SESSION['cur_layout'];
        if ($cur_layout == 'countries')
            $this->unpublishcountries();
        elseif ($cur_layout == 'states')
            $this->unpublishstates();
        elseif ($cur_layout == 'counties')
            $this->unpublishcounties();
        elseif ($cur_layout == 'cities')
            $this->unpublishcities();

        elseif ($cur_layout == 'fieldsordering')
            $this->publishunpublishfields(2);
    }

    function unpublishcountries() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $return_value = $model->unpublishcountries();
        if ($return_value == 1) {
            $msg = JText::_('JS_UNPUBLISHED');
        } else {
            $msg = JText::_('JS_ERROR_UNPUBLISHING');
        }

        $link = 'index.php?option=com_jsjobs&task=view&layout=countries';
        $this->setRedirect($link, $msg);
    }

    function unpublishstates() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $ct = JRequest::getVar('ct');
        $return_value = $model->unpublishstates();
        if ($return_value == 1) {
            $msg = JText::_('JS_UNPUBLISHED');
        } else {
            $msg = JText::_('JS_ERROR_UNPUBLISHING');
        }

        $link = 'index.php?option=com_jsjobs&task=view&layout=states&ct=' . $ct;
        $this->setRedirect($link, $msg);
    }

    function unpublishcounties() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $sd = JRequest::getVar('sd');
        $return_value = $model->unpublishcounties();
        if ($return_value == 1) {
            $msg = JText::_('JS_UNPUBLISHED');
        } else {
            $msg = JText::_('JS_ERROR_UNPUBLISHING');
        }

        $link = 'index.php?option=com_jsjobs&task=view&layout=counties&sd=' . $sd;
        $this->setRedirect($link, $msg);
    }

    function unpublishcities() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $session = JFactory::getSession();
        $country = $session->get('countryid');
        $stateid = $session->get('stateid');
        $return_value = $model->unpublishcities();
        if ($return_value == 1) {
            $msg = JText::_('JS_UNPUBLISHED');
        } else {
            $msg = JText::_('JS_ERROR_UNPUBLISHING');
        }

        $link = 'index.php?option=com_jsjobs&task=view&layout=cities&sd=' . $stateid . '&ct=' . $country;
        $this->setRedirect($link, $msg);
    }

    function saveresumeuserfields() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $data = JRequest :: get('post');
        $fieldfor = $data['fieldfor'];
        $return_value = $model->storeResumeUserFields();
        if ($return_value == 1)
            $msg = JText::_('RESUME_USER_FIELD_SAVED');
        else
            $msg = JText::_('ERROR_SAVING_RESUME_USER_FIELD');

        $link = 'index.php?option=com_jsjobs&view=application&layout=formresumeuserfield&ff=' . $fieldfor;
        $this->setRedirect($link, $msg);
    }

    function saveemailtemplate() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $data = JRequest :: get('post');
        $templatefor = $data['templatefor'];
        $return_value = $model->storeEmailTemplate();
        switch ($templatefor) {
            case 'company-new' : $tempfor = 'ew-cm';
                break;
            case 'company-approval' : $tempfor = 'cm-ap';
                break;
            case 'company-rejecting' : $tempfor = 'cm-rj';
                break;
            case 'job-new' : $tempfor = 'ew-ob';
                break;
            case 'job-approval' : $tempfor = 'ob-ap';
                break;
            case 'job-rejecting' : $tempfor = 'ob-rj';
                break;
            case 'resume-new' : $tempfor = 'ew-rm';
                break;
            case 'message-email' : $tempfor = 'ew-ms';
                break;
            case 'resume-approval' : $tempfor = 'rm-ap';
                break;
            case 'resume-rejecting' : $tempfor = 'rm-rj';
                break;
            case 'applied-resume_status' : $tempfor = 'ap-rs';
                break;
            case 'jobapply-jobapply' : $tempfor = 'ba-ja';
                break;
            case 'department-new' : $tempfor = 'ew-md';
                break;
            case 'employer-buypackage' : $tempfor = 'ew-rp';
                break;
            case 'jobseeker-buypackage' : $tempfor = 'ew-js';
                break;
            case 'job-alert' : $tempfor = 'jb-at';
                break;
            case 'job-alert-visitor' : $tempfor = 'jb-at-vis';
                break;
            case 'job-to-friend' : $tempfor = 'jb-to-fri';
                break;
        }
        if ($return_value == 1) {
            $msg = JText::_('JS_EMAIL_TEMPATE_SAVED');
            $link = 'index.php?option=com_jsjobs&task=view&layout=emailtemplate&tf=' . $tempfor;
            $this->setRedirect($link, $msg);
        } else {
            $msg = JText::_('ERROR_SAVING_EMAIL_TEMPLATE');
            $link = 'index.php?option=com_jsjobs&task=view&layout=emailtemplate&tf=' . $tempfor;
            $this->setRedirect($link, $msg);
        }
    }

    function savecountry() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $data = JRequest :: get('post');
        $return_value = $model->storeCountry();
        $link = 'index.php?option=com_jsjobs&task=view&layout=countries';
        if (is_array($return_value)) {
            if ($return_value['return_value'] == false) { // jobsharing return value 
                $msg = JText::_('JS_COUNTRY_SAVED');
                if ($return_value['rejected_value'] != "")
                    $msg = JText::_('JS_COUNTRY_SAVED_BUT_SHARING_SERVER_NOT_ACCEPT_THE_JOB_OF_THESE_COUNTRY_DUE_TO_IMPROPER_NAME');
                if ($return_value['authentication_value'] != "")
                    $msg = JText::_('JS_COUNTRY_SAVED_BUT_AUTHENTICATION_FAILED_ON_SHARING_SERVER');
                if ($return_value['server_responce'] != "")
                    $msg = JText::_('JS_COUNTRY_SAVED_PROBLEM_SYNCHRONIZE_WITH_SHARING_SERVER');
                $this->setRedirect($link, $msg);
            }elseif ($return_value['return_value'] == true) { // jobsharing return value 
                $msg = JText::_('JS_COUNTRY_SAVED');
                $this->setRedirect($link, $msg);
            }
        } else {
            if ($return_value == 1) {
                $msg = JText::_('JS_COUNTRY_SAVED');
                $this->setRedirect($link, $msg);
            } elseif ($return_value == 3) {
                $msg = JText::_('JS_COUNTRY_EXIST');
                JRequest :: setVar('view', 'application');
                JRequest :: setVar('hidemainmenu', 1);
                JRequest :: setVar('layout', 'formcountry');
                JRequest :: setVar('msg', $msg);
                $this->display();
            } else {
                $msg = JText::_('JS_ERROR_SAVING_COUNTRY');
                $this->setRedirect($link, $msg);
            }
        }
    }

    function savestate() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $data = JRequest :: get('post');
        $session = JFactory::getSession();
        $countryid = $session->get('countryid');

        $return_value = $model->storeState($countryid);
        $link = 'index.php?option=com_jsjobs&task=view&layout=states&ct=' . $countryid;
        if (is_array($return_value)) {
            if ($return_value['return_value'] == false) { // jobsharing return value 
                $msg = JText::_('JS_STATE_SAVED');
                if ($return_value['rejected_value'] != "")
                    $msg = JText::_('JS_STATE_SAVED_BUT_SHARING_SERVER_NOT_ACCEPT_THE_JOB_OF_THESE_STATE_DUE_TO_IMPROPER_NAME');
                if ($return_value['authentication_value'] != "")
                    $msg = JText::_('JS_STATE_SAVED_BUT_AUTHENTICATION_FAILED_ON_SHARING_SERVER');
                if ($return_value['server_responce'] != "")
                    $msg = JText::_('JS_STATE_SAVED_BUT_PROBLEM_SYNCHRONIZE_WITH_SHARING_SERVER');
                $this->setRedirect($link, $msg);
            }elseif ($return_value['return_value'] == true) { // jobsharing return value 
                $msg = JText::_('JS_STATE_SAVED');
                $this->setRedirect($link, $msg);
            }
        } else {
            if ($return_value == 1) {
                $msg = JText::_('JS_STATE_SAVED');
                $this->setRedirect($link, $msg);
            } elseif ($return_value == 3) {
                $msg = JText::_('JS_STATE_EXIST');
                JRequest :: setVar('view', 'application');
                JRequest :: setVar('hidemainmenu', 1);
                JRequest :: setVar('layout', 'formstate');
                JRequest :: setVar('msg', $msg);
                $this->display();
            } else {
                $msg = JText::_('JS_ERROR_SAVING_STATE');
                $this->setRedirect($link, $msg);
            }
        }
    }

    function savecounty() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $data = JRequest :: get('post');
        $statecode = $data['statecode'];
        $return_value = $model->storeCounty();
        $link = 'index.php?option=com_jsjobs&task=view&layout=counties&sd=' . $statecode;
        if ($return_value == 1) {
            $msg = JText::_('JS_COUNTY_SAVED');
        } elseif ($return_value == 3) {
            $msg = JText::_('JS_COUNTY_EXIST');
        } else {
            $msg = JText::_('JS_ERROR_SAVING_COUNTY');
        }
        $this->setRedirect($link, $msg);
    }

    function savecity() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $session = JFactory::getSession();
        $countryid = $session->get('countryid');
        $stateid = $session->get('stateid');
        $data = JRequest :: get('post');

        $return_value = $model->storeCity($countryid, $stateid);
        $link = 'index.php?option=com_jsjobs&task=view&layout=cities&ct=' . $countryid . '&sd=' . $stateid;
        if (is_array($return_value)) {
            if ($return_value['return_value'] == false) { // jobsharing return value 
                $msg = JText::_('JS_CITY_SAVED');
                if ($return_value['rejected_value'] != "")
                    $msg = JText::_('JS_CITY_SAVED_BUT_SHARING_SERVER_NOT_ACCEPT_THE_JOB_OF_THESE_CITY_DUE_TO_IMPROPER_NAME');
                if ($return_value['authentication_value'] != "")
                    $msg = JText::_('JS_CITY_SAVED_BUT_AUTHENTICATION_FAILED_ON_SHARING_SERVER');
                if ($return_value['server_responce'] != "")
                    $msg = JText::_('JS_CITY_SAVED_BUT_PROBLEM_SYNCHRONIZE_WITH_SHARING_SERVER');
                $this->setRedirect($link, $msg);
            }elseif ($return_value['return_value'] == true) { // jobsharing return value 
                $msg = JText::_('JS_CITY_SAVED');
                $this->setRedirect($link, $msg);
            }
        } else {
            if ($return_value == 1) {
                $msg = JText::_('JS_CITY_SAVED');
                $this->setRedirect($link, $msg);
            } elseif ($return_value == 3) {
                $msg = JText::_('JS_CITY_EXIST');
                JRequest :: setVar('view', 'application');
                JRequest :: setVar('hidemainmenu', 1);
                JRequest :: setVar('layout', 'formcity');
                JRequest :: setVar('msg', $msg);
                $this->display();
            } else {
                $msg = JText::_('JS_ERROR_SAVING_CITY');
                $this->setRedirect($link, $msg);
            }
        }
    }

    function loadaddressdata() {
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        $data = JRequest :: get('post');
        $return_value = $model->loadAddressData();
        $link = 'index.php?option=com_jsjobs&task=view&layout=loadaddressdata&er=2';
        if ($return_value == 1) {
            $msg = JText::_('JS_ADDRESS_DATA_SAVED');
            $link = 'index.php?option=com_jsjobs&task=view&layout=loadaddressdata';
        } elseif ($return_value == 3) { // file mismatch
            $msg = JText::_('JS_ADDRESS_DATA_COULD_NOT_SAVE');
        } elseif ($return_value == 3) { // file mismatch
            $msg = JText::_('JS_FILE_TYPE_ERROR');
        } elseif ($return_value == 5) { // state alredy exist 
            $msg = JText::_('JS_STATES_EXIST');
        } elseif ($return_value == 8) { // county alredy exist 
            $msg = JText::_('JS_COUNTIES_EXIST');
        } elseif ($return_value == 11) { // state and county alredy exist 
            $msg = JText::_('JS_STATES_COUNTIES_EXIST');
        } elseif ($return_value == 7) { // city alredy exist 
            $msg = JText::_('JS_CITIES_EXIST');
        } elseif ($return_value == 6) { // state and city alredy exist 
            $msg = JText::_('JS_STATES_CITIES_EXIST');
        } elseif ($return_value == 9) { // county and city alredy exist 
            $msg = JText::_('JS_COUNTIES_CITIES_EXIST');
        } elseif ($return_value == 12) { // state, counnty and city alredy exist 
            $msg = JText::_('JS_STATES_COUNTIES_CITIES_EXIST');
        } else {
            $msg = JText::_('JS_ADDRESS_DATA_COULD_NOT_SAVE');
        }
        $this->setRedirect($link, $msg);
    }

    function listaddressdata() {
        $data = JRequest::getVar('data');
        $val = JRequest::getVar('val');
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $returnvalue = $model->listAddressData($data, $val);
        echo $returnvalue;
        JFactory::getApplication()->close();
    }

    function defaultaddressdatajobsharing() {
        $data = JRequest::getVar('data');
        $val = JRequest::getVar('val');
        $hasstate = JRequest::getVar('state', '');
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $returnvalue = $model->DefaultListAddressDataSharing($data, $val, $hasstate);
        echo $returnvalue;
        JFactory::getApplication()->close();
    }

    function listempaddressdata() {
        $name = JRequest::getVar('name');
        $myname = JRequest::getVar('myname');
        $nextname = JRequest::getVar('nextname');
        $data = JRequest::getVar('data');
        $val = JRequest::getVar('val');
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $returnvalue = $model->listEmpAddressData($name, $myname, $nextname, $data, $val);
        echo $returnvalue;
        JFactory::getApplication()->close();
    }

    function listdepartments() {
        $val = JRequest::getVar('val');
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $returnvalue = $model->listDepartments($val);
        echo $returnvalue;
        JFactory::getApplication()->close();
    }

    function listsubcategories() {
        $val = JRequest::getVar('val');
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $returnvalue = $model->listSubCategories($val);
        echo $returnvalue;
        JFactory::getApplication()->close();
    }

    function listsubcategoriesforsearch() {
        $val = JRequest::getVar('val');
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $returnvalue = $model->listSubCategoriesForSearch($val);
        echo $returnvalue;
        JFactory::getApplication()->close();
    }

    function listuserdataforpackage() {
        $val = JRequest::getVar('val');
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $returnvalue = $model->listUserDataForPackage($val);
        echo $returnvalue;
        JFactory::getApplication()->close();
    }

    function makedefaultcurrency() { // make default currency
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $defaultid = $cid[0];
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $return_value = $model->makeDefaultCurrency($defaultid, 1);
        if ($return_value == 1) {
            $msg = JText :: _('JS_DEFAULT_CURRENCY_SAVED');
        } else {
            $msg = JText :: _('JS_ERROR_MAKING_DEFAULT_CURRENCY');
        }
        $link = 'index.php?option=com_jsjobs&c=jsjobs&task=view&layout=currency';
        $this->setRedirect($link, $msg);
    }

    function makedefaulttheme() { // make default theme
        $cid = JRequest::getVar('cid', array(), '', 'array');
        $defaultid = $cid[0];
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $return_value = $model->makeDefaultTheme($defaultid, 1);
        if ($return_value == 1) {
            $msg = JText :: _('JS_DEFAULT_THEME_SET');
        } else {
            $msg = JText :: _('JS_ERROR_MAKING_DEFAULT_THEME');
        }
        $link = 'index.php?option=com_jsjobs&c=jsjobs&task=view&layout=themes';
        $this->setRedirect($link, $msg);
    }

    function display() {
        $document = & JFactory :: getDocument();

        $viewName = JRequest :: getVar('view', 'applications');
        $layoutName = JRequest :: getVar('layout', '');

        if ($layoutName == '')
            if (isset($_SESSION['cur_layout']))
                $layoutName = $_SESSION['cur_layout'];

        if ($layoutName == '') {
            $layoutName = 'controlpanel';
            $_SESSION['cur_layout'] = $layoutName;
        }
        if ($layoutName == 'formresumeuserfield') {
            $viewName = 'application';
        }
        $viewType = $document->getType();

        $view = & $this->getView($viewName, $viewType);
        $model = & $this->getModel('jsjobs', 'JSJobsModel');
        if (!JError :: isError($model)) {
            $view->setModel($model, true);
        }

        $view->setLayout($layoutName);
        $view->display();
    }

    function getcopyjob() {
        $val = JRequest::getVar('val');
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $jobsharing = $this->getModel('jobsharing', 'JSJobsModel');
        $return_data = $model->getCopyJob($val);
        if (is_array($return_data)) {
            if ($return_data['isjobstore'] == 1) {
                if ($return_data['status'] == "Job Edit") {
                    $serverjobstatus = "ok";
                } elseif ($return_data['status'] == "Job Add") {
                    $serverjobstatus = "ok";
                } elseif ($return_data['status'] == "Edit Job Userfield") {
                    $serverjobstatus = "ok";
                } elseif ($return_data['status'] == "Add Job Userfield") {
                    $serverjobstatus = "ok";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_data['referenceid'];
                $logarray['eventtype'] = $return_data['eventtype'];
                $logarray['message'] = $return_data['message'];
                $logarray['event'] = "job Copy";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverjobstatus, $logarray['referenceid'], $return_data['serverid'], $logarray['uid'], 'jobs');
            } elseif ($return_data['isjobstore'] == 0) {
                if ($return_data['status'] == "Data Empty") {
                    $serverjobstatus = "Data not post on server";
                } elseif ($return_data['status'] == "job Saving Error") {
                    $serverjobstatus = "Error Job Saving";
                } elseif ($return_data['status'] == "Auth Fail") {
                    $serverjobstatus = "Authentication Fail";
                } elseif ($return_data['status'] == "Error Save Job Userfield") {
                    $serverjobstatus = "Error Save Job Userfield";
                } elseif ($return_data['status'] == "Improper job name") {
                    $serverjobstatus = "Improper job name";
                }
                $logarray['uid'] = $model->_uid;
                $logarray['referenceid'] = $return_data['referenceid'];
                $logarray['eventtype'] = $return_data['eventtype'];
                $logarray['message'] = $return_data['message'];
                $logarray['event'] = "job Copy";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverid = 0;
                $jobsharing->writeJobSharingLog($logarray);
                $jobsharing->UpdateServerStatus($serverjobstatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'jobs');
            }
            echo true;
            JFactory::getApplication()->close();
        } else {
            echo $return_data;
            JFactory::getApplication()->close();
        }
    }

    function getaddressdata() {
        $val = JRequest::getVar('val');
        $model = $this->getModel('jsjobs', 'JSJobsModel');
        $returnvalue = $model->getAddressData($val);
        echo json_encode($returnvalue);
        JFactory::getApplication()->close();
    }

}

?>
