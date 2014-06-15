<?php

/**
 * @Copyright Copyright (C) 2009-2010 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Al-Barr Technologies
  + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Dec 06, 2012
  ^
  + Project: 		Job Posting and Employment Application
 * File Name:	admin-----/models/jobsharing.php
  ^
 * Description: Model for application on admin site
  ^
 * History:		NONE
  ^
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');
jimport('joomla.html.html');
require_once(JPATH_SITE . '/administrator/components/com_jsjobs/models/jobsharing.php');

class JSJobsModelJob_Sharing extends JModelLegacy {

    private $skey = "_EI_XRV_!*%@&*/+-~~";
    var $filePath;
    var $uploadURL;
    var $formFileVariableName;
    var $_uid = null;
    var $_siteurl;
    var $postParams = array();

    function __construct() {
        parent :: __construct();
        $user = & JFactory::getUser();
        $this->_uid = $user->id;
        $this->_siteurl = JURI::root();
    }

    function serverTask($jsondata, $fortask) {
        $logarray = array();

        $k = 1;
        $encoded_server_json_data_array = $this->encode($jsondata);
        $sitepath = 'https://jobs.joomsky.com/';
        switch ($fortask) {
            case "listjobs";
                $url = $sitepath . 'index.php?r=Jobs/getListNewestJobs';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);

                break;
            case "viewjobbyid";
                $url = $sitepath . 'index.php?r=Jobs/getJobbyid';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getjobapplybyidforjobapply";
                $url = $sitepath . 'index.php?r=Jobs/getjobapplybyidforjobapply';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "listjobsbycategory";
                $url = $sitepath . 'index.php?r=JosJsJobCategories/listjobsbycategory';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "myappliedjobs";
                $url = $sitepath . 'index.php?r=Jobapply/getmyappliedjobs';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getjobappliedresume";
                $url = $sitepath . 'index.php?r=Jobapply/getjobappliedresume';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "alljobsappliedapplications";
                $url = $sitepath . 'index.php?r=Jobs/alljobsappliedapplications';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getResumeCommentsAJAX";
                $url = $sitepath . 'index.php?r=Jobapply/getresumecommentsajax';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getmyfolders";
                $url = $sitepath . 'index.php?r=Folders/getmyfolders';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getfolderdetail";
                $url = $sitepath . 'index.php?r=Folders/getfolderdetail';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getfolderbyidforform";
                $url = $sitepath . 'index.php?r=Folders/getfolderbyidforform';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getresumedetail";
                $url = $sitepath . 'index.php?r=Resume/getresumedetail';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "setexportresume";
                $url = $sitepath . 'index.php?r=Resume/setexportresume';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "setexportallresume";
                $url = $sitepath . 'index.php?r=Resume/setexportallresume';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getresumeviewbyid";
                $url = $sitepath . 'index.php?r=Resume/getresumeviewbyid';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getmessagesbyjobresume";
                $url = $sitepath . 'index.php?r=Messages/getmessagesbyjobresume';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);

                break;
            case "getmessagesbyjobsforjobseeker";
                $url = $sitepath . 'index.php?r=Messages/getmessagesbyjobsforjobseeker';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);

                break;
            case "getmessagesbyjobs";
                $url = $sitepath . 'index.php?r=Messages/getmessagesbyjobs';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getmessagesbyjob";
                $url = $sitepath . 'index.php?r=Messages/getmessagesbyjob';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getfolderresumebyfolderid";
                $url = $sitepath . 'index.php?r=Folderresumes/getfolderresumebyfolderid';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getjobsearch";
                $url = $sitepath . 'index.php?r=Jobs/getjobsearch';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                //echo $return_value;exit;
                break;
            case "jobscategoryofsubcategories"; // for count all subcategory in this category
                $url = $sitepath . 'index.php?r=JosJsJobSubcategories/jobscategoryofsubcategories';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getjobsbycategory";
                $url = $sitepath . 'index.php?r=Jobs/getjobsbycategory';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getjobsbysubcategory";
                $url = $sitepath . 'index.php?r=Jobs/getjobsbysubcategory';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getresumebycategory";
                $url = $sitepath . 'index.php?r=JosJsJobCategories/getresumebycategory';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getresumebycategoryid";
                $url = $sitepath . 'index.php?r=Resume/getresumebycategoryid';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getresumebysubcategorycount";
                $url = $sitepath . 'index.php?r=JosJsJobSubcategories/getresumebysubcategorycount';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getcompanybyid";
                $url = $sitepath . 'index.php?r=Companies/getcompanybyid';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "getactivejobsbycompany";
                $url = $sitepath . 'index.php?r=Jobs/getactivejobsbycompany';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);

                break;
            case "insertnewestjobsfromserver";
                $url = $sitepath . 'index.php?r=Jobs/getjobsforinsert';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "sendjobalert";
                $url = $sitepath . 'index.php?r=Jobs/sendjobalert';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            default:
                break;
        }
        $server_return_value = json_decode($return_value, true);
        $exp_return_value = explode('/', $return_value);
        $eventtype = $exp_return_value[0];
        $table_name = "";
        if ($eventtype == "Curl error") {
            $logarray['uid'] = $this->_uid;
            $logarray['referenceid'] = "";
            $logarray['eventtype'] = "Curl not Responce";
            $logarray['message'] = $return_value;
            $logarray['event'] = $fortask;
            $logarray['messagetype'] = "Error";
            $logarray['datetime'] = date('Y-m-d H:i:s');
            $serverjobstatus = "Curl Not Responce";
            $serverid = 0;
            $this->write_JobSharingLog($logarray);
            if ($table_name != "") {
                $this->UpdateServerStatus($serverjobstatus, $logarray['referenceid'], $serverid, $logarray['uid'], $table_name);
            }
            return true; // because not tell the user about what error occured 
        }
        return $server_return_value;
    }

    function unsubscribe_JobAlert($data) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->unsubscribeJobAlert($data);
        return $return_value;
    }

    function store_JobAlertSharing($data_jobalert) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->storeJobAlertSharing($data_jobalert);
        return $return_value;
    }

    function store_FolderSharing($data_folder) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->storeFolderSharing($data_folder);
        return $return_value;
    }

    function store_MessageSharing($data) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->storeMessageSharing($data);
        return $return_value;
    }

    function store_ResumeCommentsSharing($comments_data) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->storeResumeCommentsSharing($comments_data);
        return $return_value;
    }

    function store_ResumeFolderSharing($data) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->storeResumeFolderSharing($data);
        return $return_value;
    }

    function store_ShortlistcandidatesSharing($data) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->storeShortlistcandidatesSharing($data);
        return $return_value;
    }

    function store_ResumeRatingSharing($data) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->storeResumeRatingSharing($data);
        return $return_value;
    }

    function store_CoverLetterSharing($data_cvletter) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->storeCoverLetterSharing($data_cvletter);
        return $return_value;
    }

    function store_JobapplySharing($data) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->storeJobapplySharing($data);
        return $return_value;
    }

    function update_JobApplyActionStatus($data) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->updateJobApplyActionsStatus($data);
        return $return_value;
    }

    function store_ResumeFileSharing($data_resume, $resume_file) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->storeResumeFileSharing($data_resume, $resume_file);
        return $return_value;
    }

    function store_ResumePicSharing($data_resume, $resume_picture) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->storeResumePicSharing($data_resume, $resume_picture);
        return $return_value;
    }

    function store_ResumeSharing($data_resume) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->storeResumeSharing($data_resume);
        return $return_value;
    }

    function store_DepartmentSharing($data_department) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->storeDepartmentSharing($data_department);
        return $return_value;
    }

    function store_CompanyLogoSharing($data_company, $company_logo) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->storeCompanyLogoSharing($data_company, $company_logo);
        return $return_value;
    }

    function store_CompanySharing($data_company) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->storeCompanySharing($data_company);
        return $return_value;
    }

    function store_JobSharing($data) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->storeJobSharing($data);
        return $return_value;
    }

    function delete_CompanySharing($data) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->deleteCompanySharing($data);
        return $return_value;
    }

    function delete_JobSharing($data) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->deleteJobSharing($data);
        return $return_value;
    }

    function delete_DepartmentSharing($data) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->deleteDepartmentSharing($data);
        return $return_value;
    }

    function delete_FolderSharing($data) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->deleteFolderSharing($data);
        return $return_value;
    }

    function delete_ResumeSharing($data) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->deleteResumeSharing($data);
        return $return_value;
    }

    function delete_CoverletterSharing($data) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->deleteCoverletterSharing($data);
        return $return_value;
    }

    function write_JobSharingLog($data) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->writeJobSharingLog($data);
        return $return_value;
    }

    function Update_ServerStatus($serverstatus, $client_id, $server_id, $uid, $table) {
        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->UpdateServerStatus($serverstatus, $client_id, $server_id, $uid, $table);
        return $return_value;
    }

    function update_MultiCityServerid($serverids, $table) {

        $jsjobsharingobject_admin = new JSJobsModelJobSharing;
        $return_value = $jsjobsharingobject_admin->updateMultiCityServerid($serverids, $table);
        return $return_value;
    }

    function performTaskJobSharingService($jsondata, $url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
        curl_setopt($ch, CURLOPT_TIMEOUT, 600);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array('data' => $jsondata));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
       $result = curl_exec($ch);
        if ($result === false)
            return 'Curl error/' . curl_error($ch);
        else
            return $result;
        curl_close($ch);
        JFactory::getApplication()->close();
    }

    public function safe_b64encode($string) {
        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        return $data;
    }

    public function encode($value) {
        if (!$value) {
            return false;
        }
        $text = $value;
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->skey, $text, MCRYPT_MODE_ECB, $iv);
        $trim = trim($this->safe_b64encode($crypttext));
        return trim($this->safe_b64encode($crypttext));
    }

    function CurlFileUploader($filePath, $uploadURL, $formFileVariableName, /* assosiative array */ $otherParams = false) {
        $this->filePath = $filePath;
        $this->uploadURL = $uploadURL;
        if (is_array($otherParams) && $otherParams != false) {
            foreach ($otherParams as $fieldName => $fieldValue) {
                $this->postParams[$fieldName] = $fieldValue;
            }
        }
        $this->postParams[$formFileVariableName] = "@" . $filePath;
    }

    function curlUploadFile() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->uploadURL);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postParams);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        $postResult = curl_exec($ch);
        if ($postResult === false)
            return 'Curl error/' . curl_error($ch);
        else
            return $postResult;
        curl_close($ch);
        JFactory::getAppplication()->close();
    }

}
