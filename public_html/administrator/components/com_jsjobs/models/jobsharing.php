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
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 300);

class JSJobsModelJobSharing extends JModelLegacy {

    private $skey = "_EI_XRV_!*%@&*/+-~~";
    var $_uid;
	var $_siteurl;
    function __construct() {
        parent :: __construct();
        $user = & JFactory::getUser();
        $this->_uid = $user->id;
		$this->_siteurl = JURI::root();		
    }

    function serverTask($jsondata, $fortask) {
        $encoded_server_json_data_array = $this->encode($jsondata);
        $sitepath = 'https://jobs.joomsky.com/';

        switch ($fortask) {
            case "unsubscribejobsharing";
                $url = $sitepath . 'index.php?r=Client/unsubscribejobsharing';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);

                break;
            case "requestjobsharing":
                $url = $sitepath . 'index.php?r=Client/requestjobsharing';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                $return_value = explode('/', $return_value);
                $messagetype = $return_value[0];
				$return_data=array();
                if ($messagetype == "again") {
                    return "again/" . $return_value[1];
                } elseif ($messagetype == "Error" OR $messagetype == "empty") {
					$return_data['status']="authkeynotexists";
					$return_data['value']="error/" . $return_value[1];
                } elseif ($messagetype == "sucessfully") {
					$return_data['status']="authkeyexists";
					$return_data['value']="sucessfully/" . $return_value[1];
                } elseif ($messagetype == "Curl error") {
					$return_data['status']="Curlerror";
					$return_data['value']="Curl error/" . $return_value[1];
                }
                return $return_data;
                break;
            case "synchronizedefaulttables":
                $url = $sitepath . 'index.php?r=SynchronizeDB/getallserverdefaulttablesdata';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                $exp_return_value = explode('/', $return_value);
                $eventtype = $exp_return_value[0];
                if ($eventtype == "Curl error") {
                    $messagetype = "Error";
                    $message = $exp_return_value[1];
                } else {
                    $eventtype = "Sucessfully";
                    $messagetype = "Sucessfully";
                    $message = "Get Server Default Table Data Successfully";
                }

                $log_server_data_table = array();
                $log_server_data_table['uid'] = $this->_uid;
                $log_server_data_table['event'] = "synchronizedefaulttables";
                $log_server_data_table['eventtype'] = $eventtype;
                $log_server_data_table['message'] = $message;
                $log_server_data_table['messagetype'] = $messagetype;
                $log_server_data_table['datetime'] = date('Y-m-d H:i:s');
                $this->writeJobSharingLog($log_server_data_table);

                return $return_value;

                break;
            case "synchronizeaddressdata":
                $url = $sitepath . 'index.php?r=SynchronizeDB/getserveraddressdata';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);

                $exp_return_value = explode('/', $return_value);
                $eventtype = $exp_return_value[0];
                if ($eventtype == "Curl error") {
                    $messagetype = "Error";
                    $message = $exp_return_value[1];
                } else {
                    $eventtype = "Sucessfully";
                    $messagetype = "Sucessfully";
                    $message = "Get Address Data Successfully";
                }

                $log_server_data_table = array();
                $log_server_data_table['uid'] = $this->_uid;
                $log_server_data_table['event'] = "synchronizeaddresstables";
                $log_server_data_table['eventtype'] = $eventtype;
                $log_server_data_table['message'] = $message;
                $log_server_data_table['messagetype'] = $messagetype;
                $log_server_data_table['datetime'] = date('Y-m-d H:i:s');
                $this->writeJobSharingLog($log_server_data_table);

                return $return_value;

                break;
            case "synchronizecategories":
                $url = $sitepath . 'index.php?r=JosJsJobCategories/insertnewjobcategory';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "synchronizesubcategories":
                $url = $sitepath . 'index.php?r=JosJsJobSubcategories/insertnewjobsubcategory';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "synchronizejobtypes":
                $url = $sitepath . 'index.php?r=JosJsJobJobtypes/insertnewjobtypes';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "synchronizejobstatus":
                $url = $sitepath . 'index.php?r=Jobstatus/insertnewjobstatus';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "synchronizecurrencies":
                $url = $sitepath . 'index.php?r=Currencies/insertnewjobcurrencies';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "synchronizesalaryrangetypes":
                $url = $sitepath . 'index.php?r=JosJsJobSalaryrangetypes/insertnewsalaryrangetypes';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "synchronizesalaryrange":
                $url = $sitepath . 'index.php?r=JosJsJobSalaryrange/insertnewsalaryrange';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);

                break;
            case "synchronizeages":
                $url = $sitepath . 'index.php?r=Ages/insertnewages';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "synchronizeshifts":
                $url = $sitepath . 'index.php?r=Shifts/insertnewshifts';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "synchronizecareerlevels":
                $url = $sitepath . 'index.php?r=Careerlevels/insertnewcareerlevels';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "synchronizeexperiences":
                $url = $sitepath . 'index.php?r=Experiences/insertnewexperiences';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "synchronizeheighesteducation":
                $url = $sitepath . 'index.php?r=Heighesteducation/insertnewheighesteducation';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "synchronizecountries";
                $url = $sitepath . 'index.php?r=JosJsJobCountries/insertnewcountries';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "synchronizestates";
                $url = $sitepath . 'index.php?r=JosJsJobStates/insertnewstates';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "synchronizecities";
                $url = $sitepath . 'index.php?r=JosJsJobCities/insertnewcities';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;

            case "synchronizejobsanduserfields";
                $url = $sitepath . 'index.php?r=Jobs/synchronizeclientjobs';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "synchronizecompanies";
                $url = $sitepath . 'index.php?r=Companies/synchronizeclientcompanies';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "synchronizedepartments";
                $url = $sitepath . 'index.php?r=Departments/synchronizeclientdepartments';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "synchronizeresume";
                $url = $sitepath . 'index.php?r=Resume/synchronizeresume';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "synchronizecoverletter";
                $url = $sitepath . 'index.php?r=Coverletters/synchronizecoverletters';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "synchronizejobapply";
                $url = $sitepath . 'index.php?r=Jobapply/synchronizejobapply';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "synchronizeresumerating";
                $url = $sitepath . 'index.php?r=Resumerating/synchronizeresumerating';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "synchronizeresumeshortlistcandidates";
                $url = $sitepath . 'index.php?r=Shortlistcandidates/synchronizeresumeshortlistcandidates';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "synchronizefolders";
                $url = $sitepath . 'index.php?r=Folders/synchronizefolders';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "synchronizefolderresumes";
                $url = $sitepath . 'index.php?r=Folderresumes/synchronizefolderresumes';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "synchronizemessages";
                $url = $sitepath . 'index.php?r=Messages/synchronizemessages';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "synchronizejobalert";
                $url = $sitepath . 'index.php?r=Jobalertsetting/synchronizejobalert';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "storejobtypes":
                $url = $sitepath . 'index.php?r=JosJsJobJobtypes/savejobtypes';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "storejobstatus":
                $url = $sitepath . 'index.php?r=Jobstatus/savejobstatus';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "storeshifts";
                $url = $sitepath . 'index.php?r=Shifts/saveshifts';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "storeheighesteducation";
                $url = $sitepath . 'index.php?r=Heighesteducation/saveheighesteducation';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "storeages";
                $url = $sitepath . 'index.php?r=Ages/saveages';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "storecareerlevels";
                $url = $sitepath . 'index.php?r=Careerlevels/savecareerlevels';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "storeexperiences";
                $url = $sitepath . 'index.php?r=Experiences/saveexperiences';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "storecurrencies";
                $url = $sitepath . 'index.php?r=Currencies/savecurrencies';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "storesalaryrange";
                $url = $sitepath . 'index.php?r=JosJsJobSalaryrange/storesalaryrange';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "storesalaryrangetypes";
                $url = $sitepath . 'index.php?r=JosJsJobSalaryrangetypes/savesalaryrangetypes';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "storecategories";
                $url = $sitepath . 'index.php?r=JosJsJobCategories/savecategory';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "storesubcategories";
                $url = $sitepath . 'index.php?r=JosJsJobSubcategories/savesubcategory';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "storecountries";
                $url = $sitepath . 'index.php?r=JosJsJobCountries/savecountries';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "storestates";
                $url = $sitepath . 'index.php?r=JosJsJobStates/savestates';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "storecities";
                $url = $sitepath . 'index.php?r=JosJsJobCities/savecities';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "storecompany";
                $url = $sitepath . 'index.php?r=Companies/savecompany';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);

                break;
            case "companyapprove";
                $url = $sitepath . 'index.php?r=Companies/companyapprove';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "companyreject";
                $url = $sitepath . 'index.php?r=Companies/companyreject';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "storedepartment";
                $url = $sitepath . 'index.php?r=Departments/savedepartment';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);

                break;
            case "departmentapprove";
                $url = $sitepath . 'index.php?r=Departments/departmentapprove';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "departmentreject";
                $url = $sitepath . 'index.php?r=Departments/departmentreject';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "storejob";
                $url = $sitepath . 'index.php?r=Jobs/savejob';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "jobapprove";
                $url = $sitepath . 'index.php?r=Jobs/jobapprove';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "jobreject";
                $url = $sitepath . 'index.php?r=Jobs/jobreject';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "storeresume";
                $url = $sitepath . 'index.php?r=Resume/saveresume';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "resumeapprove";
                $url = $sitepath . 'index.php?r=Resume/resumeapprove';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "resumereject";
                $url = $sitepath . 'index.php?r=Resume/resumereject';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "storecoverletter";
                $url = $sitepath . 'index.php?r=Coverletters/savecoverletter';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "storejobapply";
                $url = $sitepath . 'index.php?r=Jobapply/savejobapply';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "updatejobapplyactionstatus";
                $url = $sitepath . 'index.php?r=Jobapply/updatejobapplyactionstatus';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "storeresumerating";
                $url = $sitepath . 'index.php?r=Resumerating/saveresumerating';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "storeshortlistcandidates";
                $url = $sitepath . 'index.php?r=Shortlistcandidates/saveshortlistcandidates';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "storeresumefolder";
                $url = $sitepath . 'index.php?r=Folderresumes/saveresumefolder';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "storeresumecomments";
                $url = $sitepath . 'index.php?r=Jobapply/saveresumecomments';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "storemessage";
                $url = $sitepath . 'index.php?r=Messages/savemessage';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);

                break;
            case "messageapprove";
                $url = $sitepath . 'index.php?r=Messages/messagechangestatus';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "messagereject";
                $url = $sitepath . 'index.php?r=Messages/messagechangestatus';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "storefolder";
                $url = $sitepath . 'index.php?r=Folders/savefolder';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "folderapprove";
                $url = $sitepath . 'index.php?r=Folders/folderchangestatus';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "folderreject";
                $url = $sitepath . 'index.php?r=Folders/folderchangestatus';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "storejobalert";
                $url = $sitepath . 'index.php?r=Jobalertsetting/savejobalert';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "unsubscribejobalert";
                $url = $sitepath . 'index.php?r=Jobalertsetting/unsubscribejobalert';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "deletecompany";
                $url = $sitepath . 'index.php?r=Companies/deletecompany';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "deletejob";
                $url = $sitepath . 'index.php?r=Jobs/deletejob';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "deletedepartment";
                $url = $sitepath . 'index.php?r=Departments/deletedepartment';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "deletefolder";
                $url = $sitepath . 'index.php?r=Folders/deletefolder';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "deleteresume";
                $url = $sitepath . 'index.php?r=Resume/deleteresume';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "deletecoverletter";
                $url = $sitepath . 'index.php?r=Coverletters/deletecoverletter';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            case "deletemessage";
                $url = $sitepath . 'index.php?r=Messages/deletemessage';
                $return_value = $this->performTaskJobSharingService($encoded_server_json_data_array, $url);
                break;
            default:
                return false;
                break;
        }
        $exp_return_value = explode('/', $return_value);
        $eventtype = $exp_return_value[0];
        $table_name = "";
        switch ($fortask) {
            case"storejob";
                $table_name = "jobs";
                break;
            case"storeresume";
                $table_name = "resume";
                break;
            case"storecompany";
                $table_name = "companies";
                break;
            case"storedepartment";
                $table_name = "departments";
                break;
            case"storecoverletter";
                $table_name = "coverletters";
                break;
            case "storejobapply";
                $table_name = "jobapply";
                break;
            case "storefolder";
                $table_name = "folders";
                break;
            case "storeresumefolder";
                $table_name = "folderresumes";
                break;
            case "storemessage";
                $table_name = "messages";
                break;
        }
        if ($eventtype == "Curl error") {
            $logarray = array();
            $logarray['eventtype'] = $fortask;
            $logarray['message'] = $exp_return_value[1];
            $logarray['messagetype'] = "error";
            $logarray['event'] = $fortask;
            $logarray['uid'] = $this->_uid;
            $logarray['datetime'] = date('Y-m-d H:i:s');
            $serverjobstatus = "Curl Not Responce";
            $serverid = 0;
            $this->writeJobSharingLog($logarray);
            if ($table_name != "") {
                $this->UpdateServerStatus($serverjobstatus, $logarray['referenceid'], $serverid, $logarray['uid'], $table_name);
            }
            return false;
        }
        return $return_value;
    }

    function storeDefaultTables($data, $table_name) {
        $result = array();
        $db = & JFactory::getDBO();
        $badwords = array("test", "temp");
        $rejected_value = "";
        $rejected = 0;
        foreach ($badwords AS $bwords) {
            switch ($table_name) {
                case "jobtypes";
                case "jobstatus";
                case "shifts";
                case "heighesteducation";
                case "ages";
                case "careerlevels";
                case "experiences";
                case "currencies";
                case "salaryrangetypes";
                case "subcategories";
                    if (strpos($data['title'], $bwords) === 0 OR strpos($data['title'], $bwords) !== false) {
                        $rejected_value = $data['title'];
                        $rejected = 1;
                        break;
                    }
                    break;
                case "categories";
                    if (strpos($data['cat_title'], $bwords) === 0 OR strpos($data['cat_title'], $bwords) !== false) {
                        $rejected_value = $data['cat_title'];
                        $rejected = 1;
                        break;
                    }
                    break;
                case "salaryrange";
                    if ((strpos($data['rangestart'], $bwords) === 0 OR strpos($data['rangestart'], $bwords) !== false OR (strpos($data['rangeend'], $bwords) === 0 OR strpos($data['rangeend'], $bwords) !== false))) {
                        $rejected_value = $data['rangestart'] . " " . $data['rangeend'];
                        $rejected = 1;
                        break;
                    }
                    break;
                case "countries";
                case "states";
                case "cities";
                    if (strpos($data['name'], $bwords) === 0 OR strpos($data['name'], $bwords) !== false) {
                        $rejected_value = $data['name'];
                        $rejected = 1;
                        break;
                    }
                    break;
            }
        }
        if ($rejected != 1) {
            $fortask = "store" . $table_name;
            $jsondata = json_encode($data);
            $return_server_value = $this->serverTask($jsondata, $fortask);
            if ($return_server_value === false) {
                $result['return_value'] = false;
                $result['server_responce'] = "return false";
                return $result;
            }
            $result['return_value_' . $table_name] = $return_server_value;

            $return_value_true = json_decode($result['return_value_' . $table_name]);

            $return_value_update = json_decode($result['return_value_' . $table_name], true);

            if (is_array($return_value_update) AND !empty($return_value_update)) {

                if ((isset($return_value_update['status'])) AND ($return_value_update['status'] == "Auth Fail")) {
                    // write log 
                    $log_job_item_array = array();
                    $log_job_item_array['eventtype'] = $return_value_update['eventtype'];
                    $log_job_item_array['message'] = $return_value_update['message'];
                    $log_job_item_array['messagetype'] = $return_value_update['messagetype'];
                    $log_job_item_array['event'] = $return_value_update['event'];
                    $log_job_item_array['uid'] = $this->_uid;
                    $log_job_item_array['datetime'] = date('Y-m-d H:i:s');
                    $this->writeJobSharingLog($log_job_item_array);
                    $result['return_value'] = false;
                    $result['authentication_value'] = "Authentication Failed";
                    return $result;
                }
                $update_new_item = $this->updateClientServerTables($return_value_update, $table_name);
                if ($update_new_item == true) {
                    $result['return_value'] = true;
                    return $result;
                } else {
                    $result['return_value'] = false;
                    return $result;
                }
            } elseif ($return_value_true == true) {
                $result['return_value'] = true;
                return $result;
            } elseif ($return_server_value == false) {
                $result['return_value'] = false;
                return $result;
            }
        } elseif ($rejected == 1) {
            $result['return_value'] = false;
            $result['rejected_value'] = $rejected_value;
            return $result;
        }
    }

	function synchronizeClientServerTables($server_array,$client_array,$table_name,$auth_key){
		if((!is_array($server_array)) OR (empty($server_array))){
			if((!is_array($client_array)) OR (empty($client_array))) return true;
		} 
		if((!is_array($client_array)) OR (empty($client_array))){
			if((!is_array($server_array)) OR (empty($server_array))) return true;
        }
        $user = & JFactory::getUser();
        $uid = $user->id;
        $error = array();
        $new_client_array = array(); // add new client values  to the server 
        $rejected_client_array = array();
        $badwords = array("test", "temp");

        $db = & JFactory::getDBO();

        foreach ($client_array AS $c_d) {
            $rejected = 0;
            foreach ($badwords AS $bwords) {
                switch ($table_name) {
                    case "categories";
                        if (strpos($c_d['cat_title'], $bwords) === 0 OR strpos($c_d['cat_title'], $bwords) !== false) {
                            $rejected_client_array[] = $c_d['cat_title'];
                            $rejected = 1;
                            break;
                        }
                        break;
                    case "subcategories";
                    case "jobtypes";
                    case "jobstatus";
                    case "currencies";
                    case "salaryrangetypes";
                    case "ages";
                    case "shifts";
                    case "careerlevels";
                    case "experiences";
                    case "heighesteducation";
                        if (strpos($c_d['title'], $bwords) === 0 OR strpos($c_d['title'], $bwords) !== false) {
                            $rejected_client_array[] = $c_d['title'];
                            $rejected = 1;
                            break;
                        }
                        break;
                    case "salaryrange";
                        if ((strpos($c_d['rangestart'], $bwords) === 0 OR strpos($c_d['rangestart'], $bwords) !== false OR (strpos($c_d['rangeend'], $bwords) === 0 OR strpos($c_d['rangeend'], $bwords) !== false))) {
                            $rejected_client_array[] = $c_d['rangestart'] . $c_d['rangeend'];
                            $rejected = 1;
                            break;
                        }
                        break;
                    case "countries";
                    case "states";
                    case "cities";
                        if (strpos($c_d['name'], $bwords) === 0 OR strpos($c_d['name'], $bwords) !== false) {
                            $rejected_client_array[] = $c_d['name'];
                            $rejected = 1;
                            break;
                        }
                        break;
                }
            }
            if ($rejected != 1) {
                $match = 0;
                if(is_array($server_array) AND (!empty($server_array))){
					foreach ($server_array AS $s_d) {
						switch ($table_name) {
							case "categories";
								if (strcasecmp($c_d['cat_title'], $s_d['cat_title']) == 0) {
									$query = "UPDATE #__js_job_" . $table_name . " SET serverid =" . $s_d['id'] . " WHERE cat_title = '" . $s_d['cat_title'] . "'";
									$db->setQuery($query);
									if (!$db->query()) {
										$error[] = $this->_db->getErrorMsg();
									}
									$match = 1;
									break;
								}
								break;
							case "subcategories";
								if (strcasecmp($c_d['title'], $s_d['title']) == 0) {
									$query = "UPDATE #__js_job_" . $table_name . " SET serverid =" . $s_d['id'] . " WHERE title = '" . $s_d['title'] . "'";
									$db->setQuery($query);
									if (!$db->query()) {
										$error[] = $this->_db->getErrorMsg();
									}
									$match = 1;
									break;
								}
								break;
							case "salaryrange";
								if (strcasecmp($c_d['rangestart'], $s_d['rangestart']) == 0 AND strcasecmp($c_d['rangeend'], $s_d['rangeend']) == 0) {

									$query = "UPDATE #__js_job_" . $table_name . " SET serverid =" . $s_d['id'] . " WHERE rangestart = '" . $s_d['rangestart'] . "' AND rangeend ='" . $s_d['rangeend'] . "' ";
									$db->setQuery($query);
									if (!$db->query()) {
										$error[] = $this->_db->getErrorMsg();
									}
									$match = 1;
									break;
								}


								break;
							case "jobtypes";
							case "jobstatus";
							case "currencies";
							case "salaryrangetypes";
							case "ages";
							case "shifts";
							case "careerlevels";
							case "experiences";
							case "heighesteducation";
								if (strcasecmp($c_d['title'], $s_d['title']) == 0) {
									$query = "UPDATE #__js_job_" . $table_name . " SET serverid =" . $s_d['id'] . " WHERE title = '" . $s_d['title'] . "'";
									$db->setQuery($query);
									if (!$db->query()) {
										$error[] = $this->_db->getErrorMsg();
									}
									$match = 1;
									break;
								}
								break;
							case "countries";
							case "states";
							case "cities";
								if (strcasecmp($c_d['name'], $s_d['name']) == 0) {
									$query = "UPDATE #__js_job_" . $table_name . " SET serverid =" . $s_d['id'] . " WHERE name = '" . $s_d['name'] . "'";
									$db->setQuery($query);
									if (!$db->query()) {
										$error[] = $this->_db->getErrorMsg();
									}
									$match = 1;
									break;
								}

								break;
						}
					}
				}
                if ($match == 0) {
					$c_d['authkey']=$auth_key;
                    $new_client_array[] = $c_d;
                }
            }
        }
        $check_error = 0;
        if (!empty($error)) {
            $error_message = json_encode($error);
            $log_array = array();
            $log_array['eventtype'] = "synchronize_" . $table_name;
            $log_array['message'] = $error_message;
            $log_array['messagetype'] = "error";
            $log_array['event'] = "synchronize_" . $table_name;
            $log_array['uid'] = $uid;
            $log_array['datetime'] = date('Y-m-d H:i:s');
            $this->writeJobSharingLog($log_array);
            $check_error = 1;
        }
        if (!empty($new_client_array)) {
            if ($check_error == 1)
                return false;
            $fortask = "synchronize" . $table_name;
            if ($table_name == "subcategories") {
                $total = count($new_client_array);
                for ($i = 0; $i < $total; $i++) {
                    $query = "SELECT cat.serverid AS servercatid FROM  #__js_job_categories AS cat WHERE cat.id = " . $new_client_array[$i]['categoryid'];
                    $db->setQuery($query);
                    $servercatid = $db->loadResult();
                    if ($servercatid)
                        $new_client_array[$i]['categoryid'] = $servercatid;
                    else
                        $new_client_array[$i]['categoryid'] = 0;
                }
                $json_client_data = json_encode($new_client_array);
            }elseif ($table_name == "states") {
                $total = count($new_client_array);
                for ($i = 0; $i < $total; $i++) {
                    $query = "SELECT country.serverid AS servercountryid FROM  #__js_job_countries AS country WHERE country.id = " . $new_client_array[$i]['countryid'];
                    $db->setQuery($query);
                    $servercountryid = $db->loadResult();
                    if ($servercountryid)
                        $new_client_array[$i]['countryid'] = $servercountryid;
                    else
                        $new_client_array[$i]['countryid'] = 0;
                }
                $json_client_data = json_encode($new_client_array);
            }elseif ($table_name == "cities") {
                $total = count($new_client_array);
                for ($i = 0; $i < $total; $i++) {
                    $query = "SELECT country.serverid AS servercountryid FROM  #__js_job_countries AS country WHERE country.id = " . $new_client_array[$i]['countryid'];
                    $db->setQuery($query);
                    $servercountryid = $db->loadResult();
                    if ($servercountryid)
                        $new_client_array[$i]['countryid'] = $servercountryid;
                    else
                        $new_client_array[$i]['countryid'] = 0;

                    $query = "SELECT state.serverid AS serverstateid FROM  #__js_job_states AS state WHERE state.id = " . $new_client_array[$i]['stateid'];
                    //echo '<br>sql '.$query;
                    $db->setQuery($query);
                    $serverstateid = $db->loadResult();
                    if ($serverstateid)
                        $new_client_array[$i]['stateid'] = $serverstateid;
                    else
                        $new_client_array[$i]['stateid'] = 0;
                }
                $json_client_data = json_encode($new_client_array);
            }else {
                $json_client_data = json_encode($new_client_array);
            }
            $return_server_value = $this->serverTask($json_client_data, $fortask);
            if ($return_server_value == false)
                return false;
            if (!empty($rejected_client_array)) {
                $result['rejected_client_' . $table_name] = json_encode($rejected_client_array);
            }
            $result['return_server_value_' . $table_name] = $return_server_value;
            return $result;
        } else {
            if ($check_error == 1)
                return false;
            else {
                if (!empty($rejected_client_array)) {
                    $result['rejected_client_' . $table_name] = json_encode($rejected_client_array);
                }
                $result['return_server_value_' . $table_name] = true;
                return $result;
            }
        }
    }

    function updateClientServerTables($dataarray, $table) {

        $user = & JFactory::getUser();
        $uid = $user->id;
        $db = & JFactory::getDBO();
        $error = array();
        $total = count($dataarray);
        switch ($table) {
            case "categories";
                for ($i = 0; $i < $total; $i++) {
                    $c_cat = json_decode($dataarray[$i], true);
                    $query = "UPDATE #__js_job_" . $table . " SET serverid =" . $c_cat[0]['id'] . " WHERE cat_title = '" . $c_cat[0]['cat_title'] . "'";
                    $db->setQuery($query);
                    if (!$db->query()) {
                        $error[] = $this->_db->getErrorMsg();
                    }
                }
                break;
            case "subcategories";
                for ($i = 0; $i < $total; $i++) {
                    $c_scat = json_decode($dataarray[$i], true);
                    $query = "UPDATE #__js_job_" . $table . " SET serverid =" . $c_scat[0]['id'] . " WHERE title = '" . $c_scat[0]['title'] . "'";
                    $db->setQuery($query);
                    if (!$db->query()) {
                        $error[] = $this->_db->getErrorMsg();
                    }
                }
                break;
            case "jobtypes";
            case "jobstatus";
            case "currencies";
            case "salaryrangetypes";
            case "ages";
            case "shifts";
            case "careerlevels";
            case "experiences";
            case "heighesteducation";
                for ($i = 0; $i < $total; $i++) {
                    $d_arr = json_decode($dataarray[$i], true);
                    $query = "UPDATE #__js_job_" . $table . " SET serverid =" . $d_arr[0]['id'] . " WHERE title = '" . $d_arr[0]['title'] . "'";
                    $db->setQuery($query);
                    if (!$db->query()) {
                        $error[] = $this->_db->getErrorMsg();
                    }
                }
                break;
            case "countries";
            case "states";
            case "cities";
                for ($i = 0; $i < $total; $i++) {
                    $c_address_data = json_decode($dataarray[$i], true);
                    $query = "UPDATE #__js_job_" . $table . " SET serverid =" . $c_address_data[0]['id'] . " WHERE name = '" . $c_address_data[0]['name'] . "'";
                    $db->setQuery($query);
                    if (!$db->query()) {
                        $error[] = $this->_db->getErrorMsg();
                    }
                }
                break;
            case "salaryrange";
                for ($i = 0; $i < $total; $i++) {
                    $c_jobsr = json_decode($dataarray[$i], true);
                    $query = "UPDATE #__js_job_salaryrange SET serverid =" . $c_jobsr[0]['id'] . " WHERE rangestart = '" . $c_jobsr[0]['rangestart'] . "' AND rangeend = '" . $c_jobsr[0]['rangeend'] . "' ";
                    $db->setQuery($query);
                    if (!$db->query()) {
                        $error[] = $this->_db->getErrorMsg();
                    }
                }
                break;
        }
        if (!empty($error)) {
            $error_message = json_encode($error);
            $log_job_array = array();
            $log_job_array['eventtype'] = "update_synchronize_" . $table;
            $log_job_array['message'] = $error_message;
            $log_job_array['messagetype'] = "error";
            $log_job_array['event'] = "update_synchronize_" . $table;
            $log_job_array['uid'] = $uid;
            $log_job_array['datetime'] = date('Y-m-d H:i:s');
            $this->writeJobSharingLog($log_job_array);
            return false;
        } else {
            return true;
        }
    }

    function synchronizeClientServerCompanies($client_job_companies, $auth_key) {
		if(empty($client_job_companies)) return $result['return_server_value_companies']=true;

        $server_data_array = array();
        $server_company_data_array = array();
        $rejected_client_array = array();
        $userfieldarray = array();
        $badwords = array("test", "temp");

        if ((is_array($client_job_companies)) AND (!empty($client_job_companies))) {
            foreach ($client_job_companies AS $company) {
                $rejected = 0;
                foreach ($badwords AS $bwords) {
                    if (strpos($company['name'], $bwords) === 0 OR strpos($company['name'], $bwords) !== false) {
                        $rejected_client_array[] = $company['name'];
                        $rejected = 1;
                        break;
                    }
                }
                if ($rejected != 1) {
                    if ($company['category'] == 0 OR $company['category'] == "") {
                        $comp_cat_s_id = 0;
                        break;
                    } else {
                        if ($company['category'])
                            $comp_cat_s_id = $this->getServerid('categories', $company['category']);
                        else
                            $comp_cat_s_id = 0;
                    }
                    $server_company_data_array = array('uid' => $company['uid'], 'category' => $comp_cat_s_id, 'name' => $company['name'], 'alias' => $company['alias'],
                        'url' => $company['url'], 'logofilename' => $company['logofilename'], 'logoisfile' => $company['logoisfile'], 'logo' => $company['logo'],
                        'smalllogofilename' => $company['smalllogofilename'], 'smalllogoisfile' => $company['smalllogoisfile'], 'smalllogo' => $company['smalllogo'],
                        'aboutcompanyfilename' => $company['aboutcompanyfilename'], 'aboutcompanyisfile' => $company['aboutcompanyisfile'], 'aboutcompanyfilesize' => $company['aboutcompanyfilesize'], 'aboutcompany' => $company['aboutcompany'],
                        'contactname' => $company['contactname'], 'contactphone' => $company['contactphone'], 'companyfax' => $company['companyfax'], 'contactemail' => $company['contactemail'],
                        'since' => $company['since'], 'companysize' => $company['companysize'], 'income' => $company['income'], 'description' => $company['description'],
                        'country' => $company['country'], 'state' => $company['state']/* ,'county'=>$company['county'] */, 'city' => $company['city'],
                        'zipcode' => $company['zipcode'], 'address1' => $company['address1'], 'address2' => $company['address2'], 'created' => $company['created'],
                        'modified' => $company['modified'], 'hits' => $company['hits'], 'metadescription' => $company['metadescription'],
                        'metakeywords' => $company['metakeywords'], 'status' => $company['status'], 'packageid' => $company['packageid'], 'paymenthistoryid' => $company['paymenthistoryid'],
                        'company_id' => $company['id'],
					'authkey'=>$auth_key,'siteurl'=>$this->_siteurl
                    );


                    $db = &$this->getDBO();
                    $query = "select userfielddata.id AS fieldid, userfieldtitle.title AS userfieldtitle, userfielddata.data as userfieldvalue,
						userfieldtitle.type AS fieldtype,userfieldvalue.fieldtitle AS fieldtitle ,userfieldvalue.id AS fieldtitleid 
						From `#__js_job_userfield_data` AS userfielddata
						JOIN `#__js_job_userfields` AS userfieldtitle ON userfieldtitle.id=userfielddata.field
						JOIN `#__js_job_userfieldvalues` AS userfieldvalue ON userfieldvalue.field=userfielddata.field
						WHERE userfielddata.referenceid=" . $company['id'];
                    $db->setQuery($query);
                    $userfielddata = $db->loadObjectList();
                    $v = 0;
                    foreach ($userfielddata AS $fdata) {
                        if ($v <= 15) {
                            if ($fdata->fieldtype == "select") {
                                if ($fdata->userfieldvalue == $fdata->fieldtitleid) {
                                    $ftitle = $fdata->userfieldtitle;
                                    $fvalue = $fdata->fieldtitle;
                                    $userfieldarray['fieldtitle_' . $v] = $ftitle;
                                    $userfieldarray['fieldvalue_' . $v] = $fvalue;
                                    $v++;
                                }
                            } else {
                                $ftitle = $fdata->userfieldtitle;
                                $fvalue = $fdata->userfieldvalue;
                                $userfieldarray['fieldtitle_' . $v] = $ftitle;
                                $userfieldarray['fieldvalue_' . $v] = $fvalue;
                                $v++;
                            }
                        }
                    }
                    $query = "select id,companyid,cityid 
						From `#__js_job_companycities` AS ccity
						WHERE ccity.companyid=" . $company['id'];
                    $db->setQuery($query);
                    $companycities = $db->loadObjectList();
                    $server_company_cites = array();
                    if (is_array($companycities)) {
                        foreach ($companycities AS $c_m_city) {
                            $comcity = $this->getServerid('cities', $c_m_city->cityid);
                            $server_company_cites[] = array('id' => $c_m_city->id, 'companyid' => $c_m_city->companyid, 'cityid' => $comcity);
                        }
                    }
                    $server_data_array[] = array('companydata' => $server_company_data_array, 'companyuserfields' => $userfieldarray, 'companycities' => $server_company_cites);
                }
            }
            if (!empty($server_data_array)) {

                $fortask = "synchronizecompanies";
                $json_client_data = json_encode($server_data_array);

                $return_server_value = $this->serverTask($json_client_data, $fortask);
                if ($return_server_value == false)
                    return false;
                $is_update_serverstatus_ids = json_decode($return_server_value);

                if (is_array($is_update_serverstatus_ids)) {
                    $return_server_value = $this->updatesynchronizeserverstatus($is_update_serverstatus_ids, 'companies');
                }

                if (!empty($rejected_client_array)) {
                    $result['rejected_client_companies'] = json_encode($rejected_client_array);
                }
                $result['return_server_value_companies'] = $return_server_value;
                return $result;
            } else {
                // no data get to synchronize from jobs table 
                if (!empty($rejected_client_array)) {
                    $result['rejected_client_companies'] = json_encode($rejected_client_array);
                }
                $result['return_server_value_companies'] = true;
                return $result;
            }
        }
    }

    function synchronizeClientServerDepartment($client_job_departments, $auth_key) {
        if (empty($client_job_departments))
            return $result['return_server_value_departments'] = true;
        $rejected_client_array = array();
        $server_department_data_array = array();

        $badwords = array("test", "temp");

        if ((is_array($client_job_departments)) AND (!empty($client_job_departments))) {
            foreach ($client_job_departments AS $department) {
                $rejected = 0;
                foreach ($badwords AS $bwords) {
                    if (strpos($department['name'], $bwords) === 0 OR strpos($department['name'], $bwords) !== false) {
                        $rejected_client_array[] = $department['name'];
                        $rejected = 1;
                        break;
                    }
                }
                if ($rejected != 1) {
                    $server_department_data_array[] = array('uid' => $department['uid'], 'companyid' => $department['companyid'], 'name' => $department['name'], 'alias' => $department['alias'],
                        'description' => $department['description'], 'status' => $department['status'], 'created' => $department['created'],
                        'department_id' => $department['id'],
					'authkey'=>$auth_key,'siteurl'=>$this->_siteurl);
					
                }
            }
            if (!empty($server_department_data_array)) {
                $fortask = "synchronizedepartments";
                $json_client_data = json_encode($server_department_data_array);

                $return_server_value = $this->serverTask($json_client_data, $fortask);

                if ($return_server_value == false)
                    return false;
                $is_update_serverstatus_ids = json_decode($return_server_value);

                if (is_array($is_update_serverstatus_ids)) {
                    $return_server_value = $this->updatesynchronizeserverstatus($is_update_serverstatus_ids, 'departments');
                }

                if (!empty($rejected_client_array)) {
                    $result['rejected_client_departments'] = json_encode($rejected_client_array);
                }
                $result['return_server_value_departments'] = $return_server_value;
                return $result;
            } else {
                // no data get to synchronize from jobs table 
                if (!empty($rejected_client_array)) {
                    $result['rejected_client_departments'] = json_encode($rejected_client_array);
                }
                $result['return_server_value_departments'] = true;
                return $result;
            }
        }
    }

    function synchronizeClientServerJobs($client_job_jobs, $auth_key) {
		if(empty($client_job_jobs)) return $result['return_server_value_jobs']=true;

        $server_data_array = array();
        $server_job_data_array = array();
        $rejected_client_array = array();
        $userfieldarray = array();
        $badwords = array("test", "temp");

        if ((is_array($client_job_jobs)) AND (!empty($client_job_jobs))) {
            foreach ($client_job_jobs AS $job) {
                $rejected = 0;
                foreach ($badwords AS $bwords) {
                    if (strpos($job['title'], $bwords) === 0 OR strpos($job['title'], $bwords) !== false) {
                        $rejected_client_array[] = $job['title'];
                        $rejected = 1;
                        break;
                    }
                }
                if ($rejected != 1) {

                    if ($job['jobcategory'] == 0 OR $job['jobcategory'] == "") {
                        $cat_s_id = 0;
                        break;
                    } else {
                        if ($job['jobcategory'])
                            $cat_s_id = $this->getServerid('categories', $job['jobcategory']);
                        else
                            $cat_s_id = 0;
                    }

                    if ($job['subcategoryid'])
                        $jsubcat_s_id = $this->getServerid('subcategories', $job['subcategoryid']);
                    else
                        $jsubcat_s_id = 0;


                    if ($job['jobtype'])
                        $jtype_s_id = $this->getServerid('jobtypes', $job['jobtype']);
                    else
                        $jtype_s_id = 0;

                    if ($job['jobstatus'])
                        $js_s_id = $this->getServerid('jobstatus', $job['jobstatus']);
                    else
                        $js_s_id = 0;

                    if ($job['salaryrangetype'])
                        $jsrty_s_id = $this->getServerid('salaryrangetypes', $job['salaryrangetype']);
                    else
                        $jsrty_s_id = 0;

                    if ($job['shift'])
                        $jsh_s_id = $this->getServerid('shifts', $job['shift']);
                    else
                        $jsh_s_id = 0;

                    if ($job['educationid'])
                        $jed_s_id = $this->getServerid('heighesteducation', $job['educationid']);
                    else
                        $jed_s_id = 0;

                    if ($job['mineducationrange'])
                        $jmed_s_id = $this->getServerid('heighesteducation', $job['mineducationrange']);
                    else
                        $jmed_s_id = 0;

                    if ($job['maxeducationrange'])
                        $jmxed_s_id = $this->getServerid('heighesteducation', $job['maxeducationrange']);
                    else
                        $jmxed_s_id = 0;

                    if ($job['careerlevel'])
                        $jcl_s_id = $this->getServerid('careerlevels', $job['careerlevel']);
                    else
                        $jcl_s_id = 0;

                    if ($job['experienceid'])
                        $jex_s_id = $this->getServerid('experiences', $job['experienceid']);
                    else
                        $jex_s_id = 0;

                    if ($job['minexperiencerange'])
                        $jmex_s_id = $this->getServerid('experiences', $job['minexperiencerange']);
                    else
                        $jmex_s_id = 0;

                    if ($job['maxexperiencerange'])
                        $jmxex_s_id = $this->getServerid('experiences', $job['maxexperiencerange']);
                    else
                        $jmxex_s_id = 0;

                    if ($job['agefrom'])
                        $jagf_s_id = $this->getServerid('ages', $job['agefrom']);
                    else
                        $jagf_s_id = 0;

                    if ($job['ageto'])
                        $jagt_s_id = $this->getServerid('ages', $job['ageto']);
                    else
                        $jagt_s_id = 0;

                    if ($job['salaryrangefrom'])
                        $jsrf_s_id = $this->getServerid('salaryrange', $job['salaryrangefrom']);
                    else
                        $jsrf_s_id = 0;

                    if ($job['salaryrangeto'])
                        $jsrt_s_id = $this->getServerid('salaryrange', $job['salaryrangeto']);
                    else
                        $jsrt_s_id = 0;


                    if ($job['currencyid'])
                        $jcur_s_id = $this->getServerid('currencies', $job['currencyid']);
                    else
                        $jcur_s_id = 0;

                    $server_job_data_array = array('uid' => $job['uid'], 'companyid' => $job['companyid'], 'title' => $job['title'], 'alias' => $job['alias'],
                        'jobcategory' => $cat_s_id, 'jobtype' => $jtype_s_id, 'jobstatus' => $js_s_id, 'salaryrangetype' => $jsrty_s_id,
                        'description' => $job['description'], 'qualifications' => $job['qualifications'], 'prefferdskills' => $job['prefferdskills'],
                        'country' => $job['country'], 'state' => $job['state']/* ,'county'=>$job['county'] */, 'city' => $job['city'],
                        'showcontact' => $job['showcontact'], 'noofjobs' => $job['noofjobs'], 'duration' => $job['duration'], 'created' => $job['created'],
                        'created_by' => $job['created_by'], 'modified' => $job['modified'], 'modified_by' => $job['modified_by'], 'hits' => $job['hits'],
                        'experience' => $job['experience'], 'startpublishing' => $job['startpublishing'], 'stoppublishing' => $job['stoppublishing'], 'departmentid' => $job['departmentid'],
                        'shift' => $jsh_s_id, 'sendemail' => $job['sendemail'], 'metadescription' => $job['metadescription'], 'metakeywords' => $job['metakeywords'],
                        'agreement' => $job['agreement'], 'ordering' => $job['ordering'], 'status' => $job['status'],
                        'educationminimax' => $job['educationminimax'], 'educationid' => $jed_s_id, 'mineducationrange' => $jmed_s_id, 'maxeducationrange' => $jmxed_s_id,
                        'iseducationminimax' => $job['iseducationminimax'], 'degreetitle' => $job['degreetitle'],
                        'careerlevel' => $jcl_s_id, 'experienceminimax' => $job['experienceminimax'], 'experienceid' => $jex_s_id,
                        'minexperiencerange' => $jmex_s_id, 'maxexperiencerange' => $jmxex_s_id, 'isexperienceminimax' => $job['isexperienceminimax'],
                        'experiencetext' => $job['experiencetext'], 'workpermit' => $job['workpermit'], 'requiredtravel' => $job['requiredtravel'],
                        'agefrom' => $jagf_s_id, 'ageto' => $jagt_s_id, 'salaryrangefrom' => $jsrf_s_id, 'salaryrangeto' => $jsrt_s_id,
                        'gender' => $job['gender'], 'video' => $job['video'], 'map' => $job['map'], 'packageid' => $job['packageid'],
                        'paymenthistoryid' => $job['paymenthistoryid'], 'subcategoryid' => $jsubcat_s_id, 'currencyid' => $jcur_s_id,
                        'jobid' => $job['jobid'], 'longitude' => $job['longitude'], 'latitude' => $job['latitude'],
                        'isgoldjob' => $job['isgoldjob'], 'isfeaturedjob' => $job['isfeaturedjob'], 'raf_gender' => $job['raf_gender'],
                        'raf_degreelevel' => $job['raf_degreelevel'], 'raf_experience' => $job['raf_experience'], 'raf_age' => $job['raf_age'],
                        'raf_education' => $job['raf_education'], 'raf_category' => $job['raf_category'], 'raf_subcategory' => $job['raf_subcategory'], 'raf_location' => $job['raf_location'],
                        'job_id' => $job['id'],
					'authkey'=>$auth_key,'siteurl'=>$this->_siteurl);

					
					
					$db = &$this->getDBO();
					$query="select userfielddata.id AS fieldid, userfieldtitle.title AS userfieldtitle, userfielddata.data as userfieldvalue,
						userfieldtitle.type AS fieldtype,userfieldvalue.fieldtitle AS fieldtitle ,userfieldvalue.id AS fieldtitleid 
						From `#__js_job_userfield_data` AS userfielddata
						JOIN `#__js_job_userfields` AS userfieldtitle ON userfieldtitle.id=userfielddata.field
						JOIN `#__js_job_userfieldvalues` AS userfieldvalue ON userfieldvalue.field=userfielddata.field
						WHERE userfielddata.referenceid=" . $job['id'];
                    $db->setQuery($query);
                    $userfielddata = $db->loadObjectList();
                    $v = 0;
                    foreach ($userfielddata AS $fdata) {
                        if ($v <= 15) {
                            if ($fdata->fieldtype == "select") {
                                if ($fdata->userfieldvalue == $fdata->fieldtitleid) {
                                    $ftitle = $fdata->userfieldtitle;
                                    $fvalue = $fdata->fieldtitle;
                                    $userfieldarray['fieldtitle_' . $v] = $ftitle;
                                    $userfieldarray['fieldvalue_' . $v] = $fvalue;
                                    $v++;
                                }
                            } else {
                                $ftitle = $fdata->userfieldtitle;
                                $fvalue = $fdata->userfieldvalue;
                                $userfieldarray['fieldtitle_' . $v] = $ftitle;
                                $userfieldarray['fieldvalue_' . $v] = $fvalue;
                                $v++;
                            }
                        }
                    }
                    $query = "select id,jobid,cityid 
						From `#__js_job_jobcities` AS jcity
						WHERE jcity.jobid=" . $job['id'];
                    $db->setQuery($query);
                    $jobcities = $db->loadObjectList();
                    $server_job_cites = array();
                    if (is_array($jobcities)) {
                        foreach ($jobcities AS $j_m_city) {
                            $jobcity = $this->getServerid('cities', $j_m_city->cityid);
                            $server_job_cites[] = array('id' => $j_m_city->id, 'jobid' => $j_m_city->jobid, 'cityid' => $jobcity);
                        }
                    }
                    $server_data_array[] = array('jobdata' => $server_job_data_array, 'jobuserfields' => $userfieldarray, 'jobcities' => $server_job_cites);
                }
            }

            if (!empty($server_data_array)) {
                $fortask = "synchronizejobsanduserfields";
                $json_client_data = json_encode($server_data_array);

                $return_server_value = $this->serverTask($json_client_data, $fortask);
                if ($return_server_value == false)
                    return false;
                $is_update_serverstatus_ids = json_decode($return_server_value);

                if (is_array($is_update_serverstatus_ids)) {
                    $return_server_value = $this->updatesynchronizeserverstatus($is_update_serverstatus_ids, 'jobs');
                }


                if (!empty($rejected_client_array)) {
                    $result['rejected_client_jobs'] = json_encode($rejected_client_array);
                }
                $result['return_server_value_jobs'] = $return_server_value;
                return $result;
            } else {
                // no data get to synchronize from jobs table 
                if (!empty($rejected_client_array)) {
                    $result['rejected_client_jobs'] = json_encode($rejected_client_array);
                }
                $result['return_server_value_jobs'] = true;
                return $result;
            }
        }
    }

    function synchronizeClientServerResume($client_job_resume, $auth_key) {
        if (empty($client_job_resume))
            return $result['return_server_value_resume'] = true;
        $server_data_array = array();
        $server_resume_data_array = array();
        $userfieldarray = array();
        $rejected_client_array = array();
        $badwords = array("test", "temp");
        if ((is_array($client_job_resume)) AND (!empty($client_job_resume))) {
            foreach ($client_job_resume AS $resume) {
                $rejected = 0;
                foreach ($badwords AS $bwords) {
                    if (strpos($resume['application_title'], $bwords) === 0 OR strpos($resume['application_title'], $bwords) !== false) {
                        $rejected_client_array[] = $resume['application_title'];
                        $rejected = 1;
                        break;
                    }
                }
                if ($rejected != 1) {

                    if ($resume['nationality'])
                        $rnationality_s_id = $this->getServerid('countries', $resume['nationality']);
                    else
                        $rnationality_s_id = 0;
                    $resume['nationality'] = $rnationality_s_id;


                    if ($resume['job_category'])
                        $rcat_s_id = $this->getServerid('categories', $resume['job_category']);
                    else
                        $rcat_s_id = 0;
                    $resume['job_category'] = $rcat_s_id;

                    if ($resume['jobsalaryrange'])
                        $rjobsalaryrange_s_id = $this->getServerid('salaryrange', $resume['jobsalaryrange']);
                    else
                        $rjobsalaryrange_s_id = 0;
                    $resume['jobsalaryrange'] = $rjobsalaryrange_s_id;


                    if ($resume['jobtype'])
                        $rjobtype_s_id = $this->getServerid('jobtypes', $resume['jobtype']);
                    else
                        $rjobtype_s_id = 0;
                    $resume['jobtype'] = $rjobsalaryrange_s_id;

                    if ($resume['heighestfinisheducation'])
                        $rheighestfinisheducation_s_id = $this->getServerid('heighesteducation', $resume['heighestfinisheducation']);
                    else
                        $rheighestfinisheducation_s_id = 0;
                    $resume['heighestfinisheducation'] = $rheighestfinisheducation_s_id;

                    if ($resume['address_city'])
                        $r_address_city_s_id = $this->getServerid('cities', $resume['address_city']);
                    else
                        $r_address_city_s_id = 0;

                    $resume['address_city'] = $r_address_city_s_id;

                    if ($resume['address1_city'])
                        $r_address1_city_s_id = $this->getServerid('cities', $resume['address1_city']);
                    else
                        $r_address1_city_s_id = 0;

                    $resume['address1_city'] = $r_address1_city_s_id;

                    if ($resume['address2_city'])
                        $r_address2_city_s_id = $this->getServerid('cities', $resume['address2_city']);
                    else
                        $r_address2_city_s_id = 0;

                    $resume['address2_city'] = $r_address2_city_s_id;

                    if ($resume['institute_city'])
                        $r_institute_city_s_id = $this->getServerid('cities', $resume['institute_city']);
                    else
                        $r_institute_city_s_id = 0;

                    $resume['institute_city'] = $r_institute_city_s_id;

                    if ($resume['institute1_city'])
                        $r_institute1_city_s_id = $this->getServerid('cities', $resume['institute1_city']);
                    else
                        $r_institute1_city_s_id = 0;

                    $resume['institute1_city'] = $r_institute1_city_s_id;

                    if ($resume['institute2_city'])
                        $r_institute2_city_s_id = $this->getServerid('cities', $resume['institute2_city']);
                    else
                        $r_institute2_city_s_id = 0;

                    $resume['institute2_city'] = $r_institute2_city_s_id;

                    if ($resume['institute3_city'])
                        $r_institute3_city_s_id = $this->getServerid('cities', $resume['institute3_city']);
                    else
                        $r_institute3_city_s_id = 0;

                    $resume['institute3_city'] = $r_institute3_city_s_id;

                    if ($resume['employer_city'])
                        $r_employer_city_s_id = $this->getServerid('cities', $resume['employer_city']);
                    else
                        $r_employer_city_s_id = 0;

                    $resume['employer_city'] = $r_employer_city_s_id;

                    if ($resume['employer1_city'])
                        $r_employer1_city_s_id = $this->getServerid('cities', $resume['employer1_city']);
                    else
                        $r_employer1_city_s_id = 0;

                    $resume['employer1_city'] = $r_employer1_city_s_id;

                    if ($resume['employer2_city'])
                        $r_employer2_city_s_id = $this->getServerid('cities', $resume['employer2_city']);
                    else
                        $r_employer2_city_s_id = 0;


                    $resume['employer2_city'] = $r_employer2_city_s_id;

                    if ($resume['employer3_city'])
                        $r_employer3_city_s_id = $this->getServerid('cities', $resume['employer3_city']);
                    else
                        $r_employer3_city_s_id = 0;

                    $resume['employer3_city'] = $r_employer3_city_s_id;

                    if ($resume['reference_city'])
                        $r_reference_city_s_id = $this->getServerid('cities', $resume['reference_city']);
                    else
                        $r_reference_city_s_id = 0;

                    $resume['reference_city'] = $r_reference_city_s_id;

                    if ($resume['reference1_city'])
                        $r_reference1_city_s_id = $this->getServerid('cities', $resume['reference1_city']);
                    else
                        $r_reference1_city_s_id = 0;

                    $resume['reference1_city'] = $r_reference1_city_s_id;

                    if ($resume['reference2_city'])
                        $r_reference2_city_s_id = $this->getServerid('cities', $resume['reference2_city']);
                    else
                        $r_reference2_city_s_id = 0;

                    $resume['reference2_city'] = $r_reference2_city_s_id;

                    if ($resume['reference3_city'])
                        $r_reference3_city_s_id = $this->getServerid('cities', $resume['reference3_city']);
                    else
                        $r_reference3_city_s_id = 0;
                    
                    $resume['reference3_city'] = $r_reference3_city_s_id;

                    if ($resume['currencyid'])
                        $r_cur_s_id = $this->getServerid('currencies', $resume['currencyid']);
                    else
                        $r_cur_s_id = 0;
                    $resume['currencyid'] = $r_cur_s_id;

                    if ($resume['job_subcategory'])
                        $r_subcat_s_id = $this->getServerid('subcategories', $resume['job_subcategory']);
                    else
                        $r_subcat_s_id = 0;
                    $resume['job_subcategory'] = $r_subcat_s_id;

                    $resume['resume_id'] = $resume['id'];
                    $resume['authkey'] = $auth_key;
						$resume['siteurl']=$this->_siteurl;

                    $server_resume_data_array = $resume;

                    $db = &$this->getDBO();
                    $query = "select userfielddata.id AS fieldid, userfieldtitle.title AS userfieldtitle, userfielddata.data as userfieldvalue,
							userfieldtitle.type AS fieldtype,userfieldvalue.fieldtitle AS fieldtitle ,userfieldvalue.id AS fieldtitleid 
							From `#__js_job_userfield_data` AS userfielddata
							JOIN `#__js_job_userfields` AS userfieldtitle ON userfieldtitle.id=userfielddata.field
							JOIN `#__js_job_userfieldvalues` AS userfieldvalue ON userfieldvalue.field=userfielddata.field
							WHERE userfielddata.referenceid=" . $resume['id'];
                    $db->setQuery($query);
                    $userfielddata = $db->loadObjectList();
                    $v = 0;
                    foreach ($userfielddata AS $fdata) {
                        if ($v <= 15) {
                            if ($fdata->fieldtype == "select") {
                                if ($fdata->userfieldvalue == $fdata->fieldtitleid) {
                                    $ftitle = $fdata->userfieldtitle;
                                    $fvalue = $fdata->fieldtitle;
                                    $userfieldarray['fieldtitle_' . $v] = $ftitle;
                                    $userfieldarray['fieldvalue_' . $v] = $fvalue;
                                    $v++;
                                }
                            } else {
                                $ftitle = $fdata->userfieldtitle;
                                $fvalue = $fdata->userfieldvalue;
                                $userfieldarray['fieldtitle_' . $v] = $ftitle;
                                $userfieldarray['fieldvalue_' . $v] = $fvalue;
                                $v++;
                            }
                        }
                    }

                    $server_data_array[] = array('resumedata' => $server_resume_data_array, 'resumeuserfields' => $userfieldarray);
                }
            }
            if (!empty($server_data_array)) {
                $fortask = "synchronizeresume";
                $json_client_data = json_encode($server_data_array);

                $return_server_value = $this->serverTask($json_client_data, $fortask);
                if ($return_server_value == false)
                    return false;
                $is_update_serverstatus_ids = json_decode($return_server_value);

                if (is_array($is_update_serverstatus_ids)) {
                    $return_server_value = $this->updatesynchronizeserverstatus($is_update_serverstatus_ids, 'resume');
                }

                if (!empty($rejected_client_array)) {
                    $result['rejected_client_resume'] = json_encode($rejected_client_array);
                }
                $result['return_server_value_resume'] = $return_server_value;
                return $result;
            } else {
                // no data get to synchronize from jobs table 
                if (!empty($rejected_client_array)) {
                    $result['rejected_client_resume'] = json_encode($rejected_client_array);
                }
                $result['return_server_value_resume'] = true;
                return $result;
            }
        }
    }

    function synchronizeClientServerCoverLetters($client_job_coverletters, $auth_key) {
        if (empty($client_job_coverletters))
            return $result['return_server_value_coverletter'] = true;
        $server_data_array = array();
        $server_coverletter_data_array = array();
        $rejected_client_array = array();
        $badwords = array("test", "temp");
        if ((is_array($client_job_coverletters)) AND (!empty($client_job_coverletters))) {
            foreach ($client_job_coverletters AS $coverletter) {
                $rejected = 0;
                foreach ($badwords AS $bwords) {
                    if (strpos($coverletter['title'], $bwords) === 0 OR strpos($coverletter['title'], $bwords) !== false) {
                        $rejected_client_array[] = $coverletter['title'];
                        $rejected = 1;
                        break;
                    }
                }
                if ($rejected != 1) {
                    $coverletter['coverletter_id'] = $coverletter['id'];
                    $coverletter['authkey'] = $auth_key;
					$coverletter['siteurl']=$this->_siteurl;

                    $server_coverletter_data_array = $coverletter;
                    $server_data_array[] = array('coverletterdata' => $server_coverletter_data_array);
                }
            }
            if (!empty($server_data_array)) {
                $fortask = "synchronizecoverletter";
                $json_client_data = json_encode($server_data_array);

                $return_server_value = $this->serverTask($json_client_data, $fortask);
                if ($return_server_value == false)
                    return false;
                $is_update_serverstatus_ids = json_decode($return_server_value);

                if (is_array($is_update_serverstatus_ids)) {
                    $return_server_value = $this->updatesynchronizeserverstatus($is_update_serverstatus_ids, 'coverletters');
                }
                if (!empty($rejected_client_array)) {
                    $result['rejected_client_coverletters'] = json_encode($rejected_client_array);
                }
                $result['return_server_value_coverletters'] = $return_server_value;
                return $result;
            } else {
                // no data get to synchronize from jobs table 
                if (!empty($rejected_client_array)) {
                    $result['rejected_client_coverletters'] = json_encode($rejected_client_array);
                }
                $result['return_server_value_coverletters'] = true;
                return $result;
            }
        }
    }

    function synchronizeClientServerJobapply($client_job_jobapply, $auth_key) {
		if(empty($client_job_jobapply)) return $result['return_server_value_jobapply']=true;
        $server_data_array = array();
        $db = &$this->getDBO();
        if ((is_array($client_job_jobapply)) AND (!empty($client_job_jobapply))) {
            foreach ($client_job_jobapply AS $jobapply) {
                if ($jobapply['jobid'] != "" AND $jobapply['jobid'] != 0) {
                    $query = "select job.serverid AS serverid 
							From #__js_job_jobs AS job
							WHERE job.id=" . $jobapply['jobid'];
                    $db->setQuery($query);
                    $job_serverid = $db->loadResult();
                    if ($job_serverid)
                        $jobapply['jobid'] = $job_serverid;
                    else
                        $jobapply['jobid'] = 0;
                }

                if ($jobapply['cvid'] != "" AND $jobapply['cvid'] != 0) {
                    $query = "select resume.serverid AS resumeserverid 
							From #__js_job_resume AS resume
							WHERE resume.id=" . $jobapply['cvid'];
                    $db->setQuery($query);
                    $resume_serverid = $db->loadResult();
                    if ($resume_serverid)
                        $jobapply['cvid'] = $resume_serverid;
                    else
                        $jobapply['cvid'] = 0;
                }
                if ($jobapply['coverletterid'] != "" AND $jobapply['coverletterid'] != 0) {
                    $query = "select coverletter.serverid AS coverletterserverid 
							From #__js_job_coverletters AS coverletter
							WHERE coverletter.id=" . $jobapply['coverletterid'];
                    $db->setQuery($query);
                    $coverletter_serverid = $db->loadResult();
                    if ($coverletter_serverid)
                        $jobapply['coverletterid'] = $coverletter_serverid;
                    else
                        $jobapply['coverletterid'] = 0;
                }
                $jobapply['jobapply_id'] = $jobapply['id'];
                $jobapply['authkey'] = $auth_key;
				$jobapply['siteurl']=$this->_siteurl;

                $server_data_array[] = $jobapply;
            }
            if (!empty($server_data_array)) {
                $fortask = "synchronizejobapply";
                $json_client_data = json_encode($server_data_array);

                $return_server_value = $this->serverTask($json_client_data, $fortask);
                if ($return_server_value == false)
                    return false;
                $is_update_serverstatus_ids = json_decode($return_server_value);

                if (is_array($is_update_serverstatus_ids)) {
                    $return_server_value = $this->updatesynchronizeserverstatus($is_update_serverstatus_ids, 'jobapply');
                }

                $result['return_server_value_jobapply'] = $return_server_value;
                return $result;
            } else {
                // no data get to synchronize from jobs table 
                $result['return_server_value_jobapply'] = true;
                return $result;
            }
        }
    }

    function synchronizeClientServerResumeRating($client_resume_resumerating, $auth_key) {
        if (empty($client_resume_resumerating))
            return $result['return_server_value_resumerating'] = true;
        $server_data_array = array();
        $db = &$this->getDBO();
        if ((is_array($client_resume_resumerating)) AND (!empty($client_resume_resumerating))) {
            foreach ($client_resume_resumerating AS $resumerating) {
                if ($resumerating['jobid'] != "" AND $resumerating['jobid'] != 0) {
                    $query = "select job.serverid AS serverid 
							From #__js_job_jobs AS job
							WHERE job.id=" . $resumerating['jobid'];
                    //echo 'query'.$query;
                    $db->setQuery($query);
                    $job_serverid = $db->loadResult();
                    if ($job_serverid)
                        $resumerating['jobid'] = $job_serverid;
                    else
                        $resumerating['jobid'] = 0;
                }

                if ($resumerating['resumeid'] != "" AND $resumerating['resumeid'] != 0) {
                    $query = "select resume.serverid AS resumeserverid 
							From #__js_job_resume AS resume
							WHERE resume.id=" . $resumerating['resumeid'];
                    $db->setQuery($query);
                    $resume_serverid = $db->loadResult();
                    if ($resume_serverid)
                        $resumerating['resumeid'] = $resume_serverid;
                    else
                        $resumerating['resumeid'] = 0;
                }
                $resumerating['resumerating_id'] = $resumerating['id'];
                $resumerating['authkey'] = $auth_key;
				$resumerating['siteurl']=$this->_siteurl;

                $server_data_array[] = $resumerating;
            }
            if (!empty($server_data_array)) {
                $fortask = "synchronizeresumerating";
                $json_client_data = json_encode($server_data_array);

                $return_server_value = $this->serverTask($json_client_data, $fortask);
                if ($return_server_value == false)
                    return false;
                $is_update_serverstatus_ids = json_decode($return_server_value);

                if (is_array($is_update_serverstatus_ids)) {
                    $return_server_value = $this->updatesynchronizeserverstatus($is_update_serverstatus_ids, 'resumerating');
                }

                $result['return_server_value_resumerating'] = $return_server_value;
                return $result;
            } else {
                // no data get to synchronize from jobs table 
                $result['return_server_value_resumerating'] = true;
                return $result;
            }
        }
    }

    function synchronizeClientServerResumeShortlistcandidates($client_resume_shortlistcandidates, $auth_key) {
        if (empty($client_resume_shortlistcandidates))
            return $result['return_server_value_shortlistcandidates'] = true;
        $server_data_array = array();
        $db = &$this->getDBO();
        if ((is_array($client_resume_shortlistcandidates)) AND (!empty($client_resume_shortlistcandidates))) {
            foreach ($client_resume_shortlistcandidates AS $shortlistcandidate) {
                if ($shortlistcandidate['jobid'] != "" AND $shortlistcandidate['jobid'] != 0) {
                    $query = "select job.serverid AS serverid 
							From #__js_job_jobs AS job
							WHERE job.id=" . $shortlistcandidate['jobid'];
                    $db->setQuery($query);
                    $job_serverid = $db->loadResult();
                    if ($job_serverid)
                        $shortlistcandidate['jobid'] = $job_serverid;
                    else
                        $shortlistcandidate['jobid'] = 0;
                }

                if ($shortlistcandidate['cvid'] != "" AND $shortlistcandidate['cvid'] != 0) {
                    $query = "select resume.serverid AS resumeserverid 
							From #__js_job_resume AS resume
							WHERE resume.id=" . $shortlistcandidate['cvid'];
                    $db->setQuery($query);
                    $resume_serverid = $db->loadResult();
                    if ($resume_serverid)
                        $shortlistcandidate['cvid'] = $resume_serverid;
                    else
                        $shortlistcandidate['cvid'] = 0;
                }
                $shortlistcandidate['shortlistcandidate_id'] = $shortlistcandidate['id'];
                $shortlistcandidate['authkey'] = $auth_key;
				$shortlistcandidate['siteurl']=$this->_siteurl;

                $server_data_array[] = $shortlistcandidate;
            }
            if (!empty($server_data_array)) {
                $fortask = "synchronizeresumeshortlistcandidates";
                $json_client_data = json_encode($server_data_array);

                $return_server_value = $this->serverTask($json_client_data, $fortask);
                if ($return_server_value == false)
                    return false;
                $is_update_serverstatus_ids = json_decode($return_server_value);

                if (is_array($is_update_serverstatus_ids)) {
                    $return_server_value = $this->updatesynchronizeserverstatus($is_update_serverstatus_ids, 'shortlistcandidates');
                }

                $result['return_server_value_shortlistcandidates'] = $return_server_value;
                return $result;
            } else {
                // no data get to synchronize from jobs table 
                $result['return_server_value_shortlistcandidates'] = true;
                return $result;
            }
        }
    }

    function synchronizeClientServerFolders($client_job_folders, $auth_key) {
        if (empty($client_job_folders))
            return $result['return_server_value_folders'] = true;
        $server_data_array = array();
        $server_folder_data_array = array();
        $rejected_client_array = array();
        $badwords = array("test", "temp");
        if ((is_array($client_job_folders)) AND (!empty($client_job_folders))) {
            foreach ($client_job_folders AS $folder) {
                $rejected = 0;
                foreach ($badwords AS $bwords) {
                    if (strpos($folder['name'], $bwords) === 0 OR strpos($folder['name'], $bwords) !== false) {
                        $rejected_client_array[] = $folder['name'];
                        $rejected = 1;
                        break;
                    }
                }
                if ($rejected != 1) {
                    $folder['folder_id'] = $folder['id'];
                    $folder['authkey'] = $auth_key;
					$folder['siteurl']=$this->_siteurl;
                    $server_data_array[] = $folder;
                }
            }
            if (!empty($server_data_array)) {
                $fortask = "synchronizefolders";
                $json_client_data = json_encode($server_data_array);

                $return_server_value = $this->serverTask($json_client_data, $fortask);
                if ($return_server_value == false)
                    return false;
                $is_update_serverstatus_ids = json_decode($return_server_value);

                if (is_array($is_update_serverstatus_ids)) {
                    $return_server_value = $this->updatesynchronizeserverstatus($is_update_serverstatus_ids, 'folders');
                }


                if (!empty($rejected_client_array)) {
                    $result['rejected_client_folders'] = json_encode($rejected_client_array);
                }
                $result['return_server_value_folders'] = $return_server_value;
                return $result;
            } else {
                // no data get to synchronize from jobs table 
                if (!empty($rejected_client_array)) {
                    $result['rejected_client_folders'] = json_encode($rejected_client_array);
                }
                $result['return_server_value_folders'] = true;
                return $result;
            }
        }
    }

    function synchronizeClientServerFolderResumes($client_job_folderesumes, $auth_key) {
        if (empty($client_job_folderesumes))
            return $result['return_server_value_folderresumes'] = true;
        $server_data_array = array();
        $db = &$this->getDBO();
        if ((is_array($client_job_folderesumes)) AND (!empty($client_job_folderesumes))) {
            foreach ($client_job_folderesumes AS $folderresume) {
                if ($folderresume['jobid'] != "" AND $folderresume['jobid'] != 0) {
                    $query = "select job.serverid AS serverid 
							From #__js_job_jobs AS job
							WHERE job.id=" . $folderresume['jobid'];
                    $db->setQuery($query);
                    $job_serverid = $db->loadResult();
                    if ($job_serverid)
                        $folderresume['jobid'] = $job_serverid;
                    else
                        $folderresume['jobid'] = 0;
                }

                if ($folderresume['resumeid'] != "" AND $folderresume['resumeid'] != 0) {
                    $query = "select resume.serverid AS resumeserverid 
							From #__js_job_resume AS resume
							WHERE resume.id=" . $folderresume['resumeid'];
                    $db->setQuery($query);
                    $resume_serverid = $db->loadResult();
                    if ($resume_serverid)
                        $folderresume['resumeid'] = $resume_serverid;
                    else
                        $folderresume['resumeid'] = 0;
                }
                if ($folderresume['folderid'] != "" AND $folderresume['folderid'] != 0) {
                    $query = "select folder.serverid AS folderserverid 
							From #__js_job_folders AS folder
							WHERE folder.id=" . $folderresume['folderid'];
                    $db->setQuery($query);
                    $folder_serverid = $db->loadResult();
                    if ($folder_serverid)
                        $folderresume['folderid'] = $folder_serverid;
                    else
                        $folderresume['folderid'] = 0;
                }

                $folderresume['folderresume_id'] = $folderresume['id'];
                $folderresume['authkey'] = $auth_key;
				$folderresume['siteurl']=$this->_siteurl;

                $server_data_array[] = $folderresume;
            }
            if (!empty($server_data_array)) {
                $fortask = "synchronizefolderresumes";
                $json_client_data = json_encode($server_data_array);

                $return_server_value = $this->serverTask($json_client_data, $fortask);
                if ($return_server_value == false)
                    return false;
                $is_update_serverstatus_ids = json_decode($return_server_value);

                if (is_array($is_update_serverstatus_ids)) {
                    $return_server_value = $this->updatesynchronizeserverstatus($is_update_serverstatus_ids, 'folderresumes');
                }

                $result['return_server_value_folderresumes'] = $return_server_value;
                return $result;
            } else {
                // no data get to synchronize from jobs table 
                $result['return_server_value_folderresumes'] = true;
                return $result;
            }
        }
    }

    function synchronizeClientServerMessages($client_job_messages, $auth_key) {

        if (empty($client_job_messages))
            return $result['return_server_value_messages'] = true;
        $server_data_array = array();
        $db = &$this->getDBO();
        if ((is_array($client_job_messages)) AND (!empty($client_job_messages))) {
            foreach ($client_job_messages AS $messages) {
                if ($messages['jobid'] != "" AND $messages['jobid'] != 0) {
                    $query = "select job.serverid AS serverid 
							From #__js_job_jobs AS job
							WHERE job.id=" . $messages['jobid'];
                    $db->setQuery($query);
                    $job_serverid = $db->loadResult();
                    if ($job_serverid)
                        $messages['jobid'] = $job_serverid;
                    else
                        $messages['jobid'] = 0;
                }

                if ($messages['resumeid'] != "" AND $messages['resumeid'] != 0) {
                    $query = "select resume.serverid AS resumeserverid 
							From #__js_job_resume AS resume
							WHERE resume.id=" . $messages['resumeid'];
                    $db->setQuery($query);
                    $resume_serverid = $db->loadResult();
                    if ($resume_serverid)
                        $messages['resumeid'] = $resume_serverid;
                    else
                        $messages['resumeid'] = 0;
                }
                $messages['message_id'] = $messages['id'];
                $messages['authkey'] = $auth_key;
				$messages['siteurl']=$this->_siteurl;

                $server_data_array[] = $messages;
            }
            if (!empty($server_data_array)) {
                $fortask = "synchronizemessages";
                $json_client_data = json_encode($server_data_array);

                $return_server_value = $this->serverTask($json_client_data, $fortask);
                if ($return_server_value == false)
                    return false;
                $is_update_serverstatus_ids = json_decode($return_server_value);

                if (is_array($is_update_serverstatus_ids)) {
                    $return_server_value = $this->updatesynchronizeserverstatus($is_update_serverstatus_ids, 'messages');
                }

                $result['return_server_value_messages'] = $return_server_value;
                return $result;
            } else {
                // no data get to synchronize from jobs table 
                $result['return_server_value_messages'] = true;
                return $result;
            }
        }
    }

    function synchronizeClientServerAlert($client_job_alert, $auth_key) {
        $db = & JFactory::getDBO();
        if (empty($client_job_alert))
            return $result['return_server_value_jobalertsetting'] = true;
        $server_data_array = array();
        if ((is_array($client_job_alert)) AND (!empty($client_job_alert))) {
            foreach ($client_job_alert AS $jobalert) {

                if ($jobalert['categoryid'])
                    $ja_cat_s_id = $this->getServerid('categories', $jobalert['categoryid']);
                else
                    $ja_cat_s_id = 0;
                $jobalert['categoryid'] = $ja_cat_s_id;

                if ($jobalert['subcategoryid'])
                    $ja_subcat_s_id = $this->getServerid('subcategories', $jobalert['subcategoryid']);
                else
                    $ja_subcat_s_id = 0;
                $jobalert['subcategoryid'] = $ja_subcat_s_id;
                $jobalert['jobalert_id'] = $jobalert['id'];
                $jobalert['authkey'] = $auth_key;
						$jobalert['siteurl']=$this->_siteurl;

                $server_jobalert_data_array = $jobalert;
                $query = "select id,alertid,cityid 
							From `#__js_job_jobalertcities` AS jacity
							WHERE jacity.alertid=" . $jobalert['id'];
                $db->setQuery($query);
                $jobalertcities = $db->loadObjectList();
                $server_jobalert_cites = array();
                if (is_array($jobalertcities)) {
                    foreach ($jobalertcities AS $j_a_m_city) {
                        $jaccity = $this->getServerid('cities', $j_a_m_city->cityid);
                        $server_jobalert_cites[] = array('id' => $j_a_m_city->id, 'alertid' => $j_a_m_city->alertid, 'cityid' => $jaccity);
                    }
                }

                $server_data_array[] = array('jobalert' => $server_jobalert_data_array, 'jobalertcities' => $server_jobalert_cites);
            }
            if (!empty($server_data_array)) {
                $fortask = "synchronizejobalert";
                $json_client_data = json_encode($server_data_array);
                $return_server_value = $this->serverTask($json_client_data, $fortask);
                if ($return_server_value == false)
                    return false;
                $is_update_serverstatus_ids = json_decode($return_server_value);

                if (is_array($is_update_serverstatus_ids)) {
                    $return_server_value = $this->updatesynchronizeserverstatus($is_update_serverstatus_ids, 'jobalertsetting');
                }

                $result['return_server_value_jobalertsetting'] = $return_server_value;
                return $result;
            } else {
                // no data get to synchronize from jobs table 
                $result['return_server_value_jobalertsetting'] = true;
                return $result;
            }
        }
    }

    function updatesynchronizeserverstatus($is_update_serverstatus_ids, $table) {
        $user = & JFactory::getUser();
        $uid = $user->id;
        $db = & JFactory::getDBO();
        $error = array();
        foreach ($is_update_serverstatus_ids AS $data) {
            $query = "UPDATE #__js_job_" . $table . " SET serverstatus ='ok' , serverid=" . $data->server_id . " WHERE id = " . $data->client_id;
            $db->setQuery($query);
            if (!$db->query()) {
                $error[] = $this->_db->getErrorMsg();
            }
            switch ($table) {
                case "companies";
                    if (isset($data->companycities)) {
                        foreach ($data->companycities AS $companycity) {
                            $query = "UPDATE #__js_job_companycities SET serverid=" . $companycity->server_id . " WHERE id = " . $companycity->client_id;
                            $db->setQuery($query);
                            if (!$db->query()) {
                                $error[] = $this->_db->getErrorMsg();
                            }
                        }
                    }
                    break;
                case "jobs";
                    if (isset($data->jobcities)) {
                        foreach ($data->jobcities AS $jobcity) {
                            $query = "UPDATE #__js_job_jobcities SET serverid=" . $jobcity->server_id . " WHERE id = " . $jobcity->client_id;
                            $db->setQuery($query);
                            if (!$db->query()) {
                                $error[] = $this->_db->getErrorMsg();
                            }
                        }
                    }
                    break;
                case "jobalertsetting";
                    if (isset($data->alertcities)) {
                        foreach ($data->alertcities AS $jobalertcity) {
                            $query = "UPDATE #__js_job_jobalertcities SET serverid=" . $jobalertcity->server_id . " WHERE id = " . $jobalertcity->client_id;
                            $db->setQuery($query);
                            if (!$db->query()) {
                                $error[] = $this->_db->getErrorMsg();
                            }
                        }
                    }
                    break;
            }
        }
        if (!empty($error)) {
            $error_message = json_encode($error);
            $log_job_serverstatus_array = array();
            $log_job_serverstatus_array['eventtype'] = "update_" . $table . "serverstatus";
            $log_job_serverstatus_array['message'] = $error_message;
            $log_job_serverstatus_array['messagetype'] = "error";
            $log_job_serverstatus_array['event'] = "update_" . $table . "serverstatus";
            $log_job_serverstatus_array['uid'] = $uid;
            $log_job_serverstatus_array['datetime'] = date('Y-m-d H:i:s');
            $this->writeJobSharingLog($log_job_serverstatus_array);
            return false;
        } else {
            return true;
        }
    }

    function getAllServerAddressData($jsondata, $fortask) {
        $return_server_value = $this->serverTask($jsondata, $fortask);
        return $return_server_value;
    }

    function getAllServerDefaultTables($jsondata, $fortask) {
        $return_server_value = $this->serverTask($jsondata, $fortask);
        return $return_server_value;
    }

    function updateClientAuthenticationKey($messagetype, $clientkey) {
        if (empty($clientkey))
            return 2;
        if ($messagetype == "Error")
            return 3;
        $db = & JFactory::getDBO();

        $query = "UPDATE #__js_job_config SET  configvalue ='" . $clientkey . "' WHERE configname = 'authentication_client_key' AND configfor='jobsharing'";
        $db->setQuery($query);
        if (!$db->query()) {
            return 0;
        }
        return 1;
    }

    function storeRequestJobSharing($jsondata, $fortask) {
        $return_server_value = $this->serverTask($jsondata, $fortask);
        return $return_server_value;
    }

    function unSubscribeJobSharingServer($jsondata, $fortask) {
        $return_server_value = $this->serverTask($jsondata, $fortask);
        return $return_server_value;
    }

    function unsubscribeUpdatekey() {
        $db = & JFactory::getDBO();
        $query = "UPDATE #__js_job_config SET  configvalue ='' WHERE configname = 'authentication_client_key' AND configfor='jobsharing'";
        $db->setQuery($query);
        if (!$db->query()) {
            return 0;
        }
        return 1;
    }

    function storeFolderSharing($data) {
		$data->siteurl=$this->_siteurl;
        $server_data_array = array();
        $rejected_folder = array();
        $badwords = array("test", "temp");
		$data->siteurl=$this->_siteurl;
        $rejected = 0;
        foreach ($badwords AS $bwords) {
            if (strpos($data->name, $bwords) === 0 OR strpos($data->name, $bwords) !== false) {
                $rejected_folder[] = $data->name;
                $rejected = 1;
                break;
            }
        }
        if ($rejected != 1) {

            $server_folder_data_array = $data;

            $server_data_array = array('data' => $server_folder_data_array);
            if (!empty($server_data_array)) {
                $fortask = "storefolder";
                $server_json_data_array = json_encode($server_data_array);
                $return_server_value = $this->serverTask($server_json_data_array, $fortask);
                return json_decode($return_server_value, true);
            }
        } else {
            if (!empty($rejected_folder)) {
                $foldername = json_encode($rejected_folder);
            }
            $returnarray['isfolderstore'] = 0;
            $returnarray['referenceid'] = $data->id;
            $returnarray['message'] = 'Folder store sucessfully but job sharing server rejected this Folder due to improper name' . $foldername;
            $returnarray['eventtype'] = 'Add Folder';
            $returnarray['status'] = 'Improper Folder name';
            return $returnarray;
        }
    }

    function storeMessageSharing($data) {
		$data->siteurl=$this->_siteurl;
        $server_message_data_array = $data;
        $server_data_array = array('data' => $server_message_data_array);
        if (!empty($server_data_array)) {
            $fortask = "storemessage";
            $server_json_data_array = json_encode($server_data_array);
            $return_server_value = $this->serverTask($server_json_data_array, $fortask);
            return json_decode($return_server_value, true);
        } else {
            return true;
        }
    }

    function storeResumeCommentsSharing($comments_data) {
		$comments_data['siteurl']=$this->_siteurl;
        $server_resumecomments_data_array = $comments_data;
        $server_data_array = array('data' => $server_resumecomments_data_array);
        if (!empty($server_data_array)) {
            $fortask = "storeresumecomments";
            $server_json_data_array = json_encode($server_data_array);
            $return_server_value = $this->serverTask($server_json_data_array, $fortask);
            return json_decode($return_server_value, true);
        } else {
            return true;
        }
    }

    function storeResumeFolderSharing($data) {
		$data['siteurl']=$this->_siteurl;
        $server_resumefolder_data_array = $data;
        $server_data_array = array('data' => $server_resumefolder_data_array);
        if (!empty($server_data_array)) {
            $fortask = "storeresumefolder";
            $server_json_data_array = json_encode($server_data_array);
            $return_server_value = $this->serverTask($server_json_data_array, $fortask);
            return json_decode($return_server_value, true);
        } else {
            return true;
        }
    }

    function storeShortlistcandidatesSharing($data) {
		$data['siteurl']=$this->_siteurl;
        $server_shortlistcandidate_data_array = $data;
        $server_data_array = array('data' => $server_shortlistcandidate_data_array);
        if (!empty($server_data_array)) {
            $fortask = "storeshortlistcandidates";
            $server_json_data_array = json_encode($server_data_array);
            $return_server_value = $this->serverTask($server_json_data_array, $fortask);
            return json_decode($return_server_value, true);
        } else {
            return true;
        }
    }

    function storeResumeRatingSharing($data) {
		$data['siteurl']=$this->_siteurl;
        $server_resumerating_data_array = $data;
        $server_data_array = array('data' => $server_resumerating_data_array);
        if (!empty($server_data_array)) {
            $fortask = "storeresumerating";
            $server_json_data_array = json_encode($server_data_array);
            $return_server_value = $this->serverTask($server_json_data_array, $fortask);
            return json_decode($return_server_value, true);
        } else {
            return true;
        }
    }

    function storeJobapplySharing($data) {
		$data['siteurl']=$this->_siteurl;
        $server_jobapply_data_array = $data;
        $server_data_array = array('data' => $server_jobapply_data_array);
        if (!empty($server_data_array)) {
            $fortask = "storejobapply";
            $server_json_data_array = json_encode($server_data_array);
            $return_server_value = $this->serverTask($server_json_data_array, $fortask);
            return json_decode($return_server_value, true);
        } else {
            return true;
        }
    }

    function deleteCompanySharing($data) {
        $server_data_array = array('data' => $data);
        if (!empty($server_data_array)) {
            $fortask = "deletecompany";
            $server_json_data_array = json_encode($server_data_array);
            $return_server_value = $this->serverTask($server_json_data_array, $fortask);
            return json_decode($return_server_value, true);
        } else {
            return true;
        }
    }

    function deleteJobSharing($data) {
        $server_data_array = array('data' => $data);
        if (!empty($server_data_array)) {
            $fortask = "deletejob";
            $server_json_data_array = json_encode($server_data_array);
            $return_server_value = $this->serverTask($server_json_data_array, $fortask);
            return json_decode($return_server_value, true);
        } else {
            return true;
        }
    }

    function deleteDepartmentSharing($data) {
        $server_data_array = array('data' => $data);
        if (!empty($server_data_array)) {
            $fortask = "deletedepartment";
            $server_json_data_array = json_encode($server_data_array);
            $return_server_value = $this->serverTask($server_json_data_array, $fortask);
            return json_decode($return_server_value, true);
        } else {
            return true;
        }
    }

    function deleteFolderSharing($data) {
        $server_data_array = array('data' => $data);
        if (!empty($server_data_array)) {
            $fortask = "deletefolder";
            $server_json_data_array = json_encode($server_data_array);
            $return_server_value = $this->serverTask($server_json_data_array, $fortask);
            return json_decode($return_server_value, true);
        } else {
            return true;
        }
    }

    function deleteResumeSharing($data) {
        $server_data_array = array('data' => $data);
        if (!empty($server_data_array)) {
            $fortask = "deleteresume";
            $server_json_data_array = json_encode($server_data_array);
            $return_server_value = $this->serverTask($server_json_data_array, $fortask);
            return json_decode($return_server_value, true);
        } else {
            return true;
        }
    }

    function deleteCoverletterSharing($data) {
        $server_data_array = array('data' => $data);
        if (!empty($server_data_array)) {
            $fortask = "deletecoverletter";
            $server_json_data_array = json_encode($server_data_array);
            $return_server_value = $this->serverTask($server_json_data_array, $fortask);
            return json_decode($return_server_value, true);
        } else {
            return true;
        }
    }
    function deleteMessageSharing($data){
        $server_data_array = array('data' => $data);
        if (!empty($server_data_array)) {
            $fortask = "deletemessage";
            $server_json_data_array = json_encode($server_data_array);
            $return_server_value = $this->serverTask($server_json_data_array, $fortask);
            return json_decode($return_server_value, true);
        } else {
            return true;
        }
    }

    function updateJobApplyActionsStatus($data) {
		$data['siteurl']=$this->_siteurl;
        $server_jobapply_data_array = $data;
        $server_data_array = array('data' => $server_jobapply_data_array);
        if (!empty($server_data_array)) {
            $fortask = "updatejobapplyactionstatus";
            $server_json_data_array = json_encode($server_data_array);
            $return_server_value = $this->serverTask($server_json_data_array, $fortask);
            return $return_server_value;
        } else {
            return true;
        }
    }

    function storeCoverLetterSharing($data) {
		$data->siteurl=$this->_siteurl;
        $server_data_array = array();
        $rejected_coverletter = array();
        $badwords = array("test", "temp");

        $rejected = 0;
        foreach ($badwords AS $bwords) {
            if (strpos($data->title, $bwords) === 0 OR strpos($data->title, $bwords) !== false) {
                $rejected_coverletter[] = $data->title;
                $rejected = 1;
                break;
            }
        }
        if ($rejected != 1) {

            $server_coverletter_data_array = $data;

            $server_data_array = array('data' => $server_coverletter_data_array);
            if (!empty($server_data_array)) {
                $fortask = "storecoverletter";
                $server_json_data_array = json_encode($server_data_array);
                $return_server_value = $this->serverTask($server_json_data_array, $fortask);
                return json_decode($return_server_value, true);
            }
        } else {
            if (!empty($rejected_coverletter)) {
                $coverlettername = json_encode($rejected_coverletter);
            }
            $returnarray['iscoverletterstore'] = 0;
            $returnarray['referenceid'] = $data->id;
            $returnarray['message'] = 'Coverletter store sucessfully but job sharing server rejected this coverletter due to improper name' . $coverlettername;
            $returnarray['eventtype'] = 'Add Coverletter';
            $returnarray['status'] = 'Improper Coverletter name';
            return $returnarray;
        }
    }

    function unsubscribeJobAlert($data) {
		$data['siteurl']=$this->_siteurl;
        $server_jobalert_data_array = $data;
        $server_data_array = array('data' => $server_jobalert_data_array);
        if (!empty($server_data_array)) {
            $fortask = "unsubscribejobalert";
            $server_json_data_array = json_encode($server_data_array);
            $return_server_value = $this->serverTask($server_json_data_array, $fortask);
            return json_decode($return_server_value, true);
        } else {
            return true;
        }
    }

    function storeJobAlertSharing($data) {
        $server_data_array = array();
        if ($data->subcategoryid)
            $ja_subcat_s_id = $this->getServerid('subcategories', $data->subcategoryid);
        else
            $ja_subcat_s_id = 0;
        $data->subcategoryid = $ja_subcat_s_id;
        if ($data->categoryid)
            $ja_cat_s_id = $this->getServerid('categories', $data->categoryid);
        else
            $ja_cat_s_id = 0;
        $data->categoryid = $ja_cat_s_id;
        $data->siteurl = $this->_siteurl;

        $db = & JFactory::getDBO();
        $query = "select id,alertid,cityid 
			From `#__js_job_jobalertcities` AS jacity
			WHERE jacity.alertid=" . $data->id;
        $db->setQuery($query);
        $jobalertcities = $db->loadObjectList();
        $server_jobalert_cites = array();
        if (is_array($jobalertcities)) {
            foreach ($jobalertcities AS $j_a_m_city) {
                $jaccity = $this->getServerid('cities', $j_a_m_city->cityid);
                $server_jobalert_cites[] = array('id' => $j_a_m_city->id, 'alertid' => $j_a_m_city->alertid, 'cityid' => $jaccity, 'siteurl' => $this->_siteurl);
            }
        }
        $server_data_array = array('data' => $data, 'jobalertcities' => $server_jobalert_cites);
        if (!empty($server_data_array)) {
            $fortask = "storejobalert";
            $server_json_data_array = json_encode($server_data_array);
            $return_server_value = $this->serverTask($server_json_data_array, $fortask);
            return json_decode($return_server_value, true);
        } else {
            return false;
        }
    }

    function storeResumeSharing($data) {
        $server_data_array = array();
        $userfieldarray = array();
        $rejected_resume = array();
        $badwords = array("test", "temp");

        $rejected = 0;
        foreach ($badwords AS $bwords) {
            if (strpos($data->application_title, $bwords) === 0 OR strpos($data->application_title, $bwords) !== false) {
                $rejected_resume[] = $data->application_title;
                $rejected = 1;
                break;
            }
        }
        if ($rejected != 1) {

            if ($data->nationality)
                $rnationality_s_id = $this->getServerid('countries', $data->nationality);
            else
                $rnationality_s_id = 0;
            $data->nationality = $rnationality_s_id;


            if ($data->job_category)
                $rcat_s_id = $this->getServerid('categories', $data->job_category);
            else
                $rcat_s_id = 0;
            $data->job_category = $rcat_s_id;

            if ($data->jobsalaryrange)
                $rjobsalaryrange_s_id = $this->getServerid('salaryrange', $data->jobsalaryrange);
            else
                $rjobsalaryrange_s_id = 0;
            $data->jobsalaryrange = $rjobsalaryrange_s_id;


            if ($data->jobtype)
                $rjobtype_s_id = $this->getServerid('jobtypes', $data->jobtype);
            else
                $rjobtype_s_id = 0;
            $data->jobtype = $rjobsalaryrange_s_id;

            if ($data->heighestfinisheducation)
                $rheighestfinisheducation_s_id = $this->getServerid('heighesteducation', $data->heighestfinisheducation);
            else
                $rheighestfinisheducation_s_id = 0;
            $data->heighestfinisheducation = $rheighestfinisheducation_s_id;
            if ($data->address_city)
                $r_address_city_s_id = $this->getServerid('cities', $data->address_city);
            else
                $r_address_city_s_id = 0;

            $data->address_city = $r_address_city_s_id;

            if ($data->address1_city)
                $r_address1_city_s_id = $this->getServerid('cities', $data->address1_city);
            else
                $r_address1_city_s_id = 0;

            $data->address1_city = $r_address1_city_s_id;

            if ($data->address2_city)
                $r_address2_city_s_id = $this->getServerid('cities', $data->address2_city);
            else
                $r_address2_city_s_id = 0;

            $data->address2_city = $r_address2_city_s_id;

            if ($data->institute_city)
                $r_institute_city_s_id = $this->getServerid('cities', $data->institute_city);
            else
                $r_institute_city_s_id = 0;

            $data->institute_city = $r_institute_city_s_id;

            if ($data->institute1_city)
                $r_institute1_city_s_id = $this->getServerid('cities', $data->institute1_city);
            else
                $r_institute1_city_s_id = 0;

            $data->institute1_city = $r_institute1_city_s_id;

            if ($data->institute2_city)
                $r_institute2_city_s_id = $this->getServerid('cities', $data->institute2_city);
            else
                $r_institute2_city_s_id = 0;

            $data->institute2_city = $r_institute2_city_s_id;

            if ($data->institute3_city)
                $r_institute3_city_s_id = $this->getServerid('cities', $data->institute3_city);
            else
                $r_institute3_city_s_id = 0;

            $data->institute3_city = $r_institute3_city_s_id;

            if ($data->employer_city)
                $r_employer_city_s_id = $this->getServerid('cities', $data->employer_city);
            else
                $r_employer_city_s_id = 0;

            $data->employer_city = $r_employer_city_s_id;

            if ($data->employer1_city)
                $r_employer1_city_s_id = $this->getServerid('cities', $data->employer1_city);
            else
                $r_employer1_city_s_id = 0;

            $data->employer1_city = $r_employer1_city_s_id;

            if ($data->employer2_city)
                $r_employer2_city_s_id = $this->getServerid('cities', $data->employer2_city);
            else
                $r_employer2_city_s_id = 0;

            $data->employer2_city = $r_employer2_city_s_id;

            if ($data->employer3_city)
                $r_employer3_city_s_id = $this->getServerid('cities', $data->employer3_city);
            else
                $r_employer3_city_s_id = 0;

            $data->employer3_city = $r_employer3_city_s_id;

            if ($data->reference_city)
                $r_reference_city_s_id = $this->getServerid('cities', $data->reference_city);
            else
                $r_reference_city_s_id = 0;

            $data->reference_city = $r_reference_city_s_id;

            if ($data->reference1_city)
                $r_reference1_city_s_id = $this->getServerid('cities', $data->reference1_city);
            else
                $r_reference1_city_s_id = 0;

            $data->reference1_city = $r_reference1_city_s_id;

            if ($data->reference2_city)
                $r_reference2_city_s_id = $this->getServerid('cities', $data->reference2_city);
            else
                $r_reference2_city_s_id = 0;

            $data->reference2_city = $r_reference2_city_s_id;

            if ($data->reference3_city)
                $r_reference3_city_s_id = $this->getServerid('cities', $data->reference3_city);
            else
                $r_reference3_city_s_id = 0;

            $data->reference3_city = $r_reference3_city_s_id;

            if ($data->currencyid)
                $r_cur_s_id = $this->getServerid('currencies', $data->currencyid);
            else
                $r_cur_s_id = 0;
            $data->currencyid = $r_cur_s_id;

            if ($data->job_subcategory)
                $r_subcat_s_id = $this->getServerid('subcategories', $data->job_subcategory);
            else
                $r_subcat_s_id = 0;
            $data->job_subcategory = $r_subcat_s_id;

			$data->siteurl=$this->_siteurl;
            $db = &$this->getDBO();
            $query = "select userfielddata.id AS fieldid, userfieldtitle.title AS userfieldtitle, userfielddata.data as userfieldvalue,
					userfieldtitle.type AS fieldtype,userfieldvalue.fieldtitle AS fieldtitle ,userfieldvalue.id AS fieldtitleid 
					From #__js_job_userfield_data AS userfielddata
					JOIN #__js_job_userfields AS userfieldtitle ON userfieldtitle.id=userfielddata.field
					JOIN #__js_job_userfieldvalues AS userfieldvalue ON userfieldvalue.field=userfielddata.field
					WHERE userfielddata.referenceid=" . $data->resume_id;
            $db->setQuery($query);
            $userfielddata = $db->loadObjectList();

            foreach ($userfielddata AS $fdata) {
                if ($fdata->fieldtype == "select") {
                    if ($fdata->userfieldvalue == $fdata->fieldtitleid) {
                        $ftitle = $fdata->userfieldtitle;
                        $fvalue = $fdata->fieldtitle;
                        $userfieldarray[] = array('fieldtitle' => $ftitle, 'fieldvalue' => $fvalue);
                    }
                } else {
                    $ftitle = $fdata->userfieldtitle;
                    $fvalue = $fdata->userfieldvalue;
                    $userfieldarray[] = array('fieldtitle' => $ftitle, 'fieldvalue' => $fvalue);
                }
            }

            $userfielddataarray = array();
            $z = 0;
            foreach ($userfieldarray AS $array) {   // Limit User Field Store On server is 15
                if ($z <= 15) {
                    $userfielddataarray[] = array('fieldtitle_' . $z => $array['fieldtitle'], 'fieldvalue_' . $z => $array['fieldvalue']);
                    $z++;
                }
            }

            $server_data_array = array('data' => $data, 'userfields' => $userfielddataarray);

            if (!empty($server_data_array)) {
                $fortask = "storeresume";
                $server_json_data_array = json_encode($server_data_array);

                $return_server_value = $this->serverTask($server_json_data_array, $fortask);
                return json_decode($return_server_value, true);
            }
        } else {
            if (!empty($rejected_resume)) {
                $resumename = json_encode($rejected_resume);
            }
            $returnarray['isresumestore'] = 0;
            $returnarray['referenceid'] = $data->id;
            $returnarray['message'] = 'Resume store sucessfully but job sharing server rejected this Resume due to improper name' . $resumename;
            $returnarray['eventtype'] = 'Add Resume';
            $returnarray['status'] = 'Improper Resume name';
            return $returnarray;
        }
    }

    function storeResumePicSharing($data, $resume_picture) {
        $sitepath = 'https://jobs.joomsky.com/';
        if (is_array($resume_picture) AND $resume_picture['picfilename'] != "") {
            $this->CurlFileUploader($resume_picture['picfilename'], $sitepath."/index.php?r=Resume/uploadresumepicture", 'picfilename', array('resume_id' => $data->resume_id, 'authkey' => $data->authkey,'siteurl'=>$this->_siteurl));
            $return_data = $this->curlUploadFile();
            $return_value = json_decode($return_data, true);
            $exp_return_value = explode('/', $return_data);
            $eventtype = $exp_return_value[0];
            if ($eventtype == "Curl error") {
                $logarray['uid'] = $this->_uid;
                $logarray['referenceid'] = "";
                $logarray['eventtype'] = "Curl not Responce";
                $logarray['message'] = $return_data;
                $logarray['event'] = "Resume Picture";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverjobstatus = "Curl Not Responce";
                $this->writeJobSharingLog($logarray);
                return true; // not tell the user what error occured on jobsharing server
            }
            return $return_value;
        }
    }

    function storeResumeFileSharing($data, $resume_file) {
        $sitepath = 'https://jobs.joomsky.com/';
        if (is_array($resume_file) AND $resume_file['resume_file'] != "") {
            $this->CurlFileUploader($resume_file['resume_file'], $sitepath."index.php?r=Resume/uploadresumefile", 'resume_file', array('resume_id' => $data->resume_id, 'authkey' => $data->authkey,'siteurl'=>$this->_siteurl));
            $return_data = $this->curlUploadFile();
            $return_value = json_decode($return_data, true);
            $exp_return_value = explode('/', $return_data);
            $eventtype = $exp_return_value[0];
            if ($eventtype == "Curl error") {
                $logarray['uid'] = $this->_uid;
                $logarray['referenceid'] = "";
                $logarray['eventtype'] = "Curl not Responce";
                $logarray['message'] = $return_data;
                $logarray['event'] = "Resume File";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverjobstatus = "Curl Not Responce";
                $this->writeJobSharingLog($logarray);
                return true; // not tell the user what error occured on jobsharing server
            }
            return $return_value;
        }
    }

    function storeDepartmentSharing($data) {
        $server_data_array = array();
        $rejected_department = array();
        $badwords = array("test", "temp");
        $rejected = 0;
        foreach ($badwords AS $bwords) {
            if (strpos($data->name, $bwords) === 0 OR strpos($data->name, $bwords) !== false) {
                $rejected_department[] = $data->name;
                $rejected = 1;
                break;
            }
        }
        if ($rejected != 1) {
            $server_department_data_array = array('uid' => $data->uid, 'companyid' => $data->companyid, 'name' => $data->name, 'alias' => $data->alias,
                'description' => $data->description, 'status' => $data->status,
                'created' => $data->created,
                'department_id' => $data->department_id,
					'authkey'=>$data->authkey,'id'=>$data->id,'siteurl'=>$this->_siteurl
            );

            $server_data_array = array('data' => $server_department_data_array);
            if (!empty($server_data_array)) {
                $fortask = "storedepartment";
                $server_json_data_array = json_encode($server_data_array);
                $return_server_value = $this->serverTask($server_json_data_array, $fortask);
                return json_decode($return_server_value, true);
            }
        } else {
            if (!empty($rejected_department)) {
                $departmentname = json_encode($rejected_department);
            }
            $returnarray['isdepartmentstore'] = 0;
            $returnarray['referenceid'] = $data->id;
            $returnarray['message'] = 'Department store sucessfully but job sharing server rejected this department due to improper name' . $departmentname;
            $returnarray['eventtype'] = 'Add Department';
            $returnarray['status'] = 'Improper Department name';
            return $returnarray;
        }
    }

    function storeCompanySharing($data) {
        $server_data_array = array();
        $userfieldarray = array();
        $rejected_company = array();
        $badwords = array("test", "temp");
        $rejected = 0;
        foreach ($badwords AS $bwords) {
            if (strpos($data->name, $bwords) === 0 OR strpos($data->name, $bwords) !== false) {
                $rejected_company[] = $data->name;
                $rejected = 1;
                break;
            }
        }
        if ($rejected != 1) {
			if($data->category) $cat_s_id=$this->getServerid('categories',$data->category);
			else $cat_s_id=0;

            $server_company_data_array = array('uid' => $data->uid, 'category' => $cat_s_id, 'name' => $data->name, 'alias' => $data->alias,
                'url' => $data->url, 'logofilename' => $data->logofilename, 'logoisfile' => $data->logoisfile, 'logo' => $data->logo,
                'smalllogofilename' => $data->smalllogofilename, 'smalllogoisfile' => $data->smalllogoisfile, 'smalllogo' => $data->smalllogo,
                'aboutcompanyfilename' => $data->aboutcompanyfilename, 'aboutcompanyisfile' => $data->aboutcompanyisfile, 'aboutcompanyfilesize' => $data->aboutcompanyfilesize, 'aboutcompany' => $data->aboutcompany,
                'contactname' => $data->contactname, 'contactphone' => $data->contactphone, 'companyfax' => $data->companyfax, 'contactemail' => $data->contactemail,
                'modified' => $data->modified, 'hits' => $data->hits,
                'since' => $data->since, 'companysize' => $data->companysize, 'income' => $data->income, 'description' => $data->description,
                'zipcode' => $data->zipcode, 'address1' => $data->address1, 'address2' => $data->address2,
                'created' => $data->created, 'modified' => $data->modified, 'hits' => $data->hits, 'metadescription' => $data->metadescription,
                'metakeywords' => $data->metakeywords, 'status' => $data->status,
                'packageid' => $data->packageid, 'paymenthistoryid' => $data->paymenthistoryid,
                'company_id' => $data->company_id,
					'authkey'=>$data->authkey,'id'=>$data->id,'siteurl'=>$this->_siteurl);
				

            $db = &$this->getDBO();
            $query = "select userfielddata.id AS fieldid, userfieldtitle.title AS userfieldtitle, userfielddata.data as userfieldvalue,
					userfieldtitle.type AS fieldtype,userfieldvalue.fieldtitle AS fieldtitle ,userfieldvalue.id AS fieldtitleid 
					From #__js_job_userfield_data AS userfielddata
					JOIN #__js_job_userfields AS userfieldtitle ON userfieldtitle.id=userfielddata.field
					JOIN #__js_job_userfieldvalues AS userfieldvalue ON userfieldvalue.field=userfielddata.field";
					if($data->company_id!="") $wherequery =" WHERE userfielddata.referenceid=".$data->company_id;
            $query.=$wherequery;
            $db->setQuery($query);
            $userfielddata = $db->loadObjectList();

            foreach ($userfielddata AS $fdata) {
                if ($fdata->fieldtype == "select") {
                    if ($fdata->userfieldvalue == $fdata->fieldtitleid) {
                        $ftitle = $fdata->userfieldtitle;
                        $fvalue = $fdata->fieldtitle;
                        $userfieldarray[] = array('fieldtitle' => $ftitle, 'fieldvalue' => $fvalue);
                    }
                } else {
                    $ftitle = $fdata->userfieldtitle;
                    $fvalue = $fdata->userfieldvalue;
                    $userfieldarray[] = array('fieldtitle' => $ftitle, 'fieldvalue' => $fvalue);
                }
            }

            $userfielddataarray = array();
            $z = 0;
            foreach ($userfieldarray AS $array) {   // Limit User Field Store On server is 15
                if ($z <= 15) {
                    $userfielddataarray[] = array('fieldtitle_' . $z => $array['fieldtitle'], 'fieldvalue_' . $z => $array['fieldvalue']);
                    $z++;
                }
            }
            $query = "select id,companyid,cityid 
					From `#__js_job_companycities` AS ccity
					WHERE ccity.companyid=" . $data->company_id;
            $db->setQuery($query);
            $companycities = $db->loadObjectList();
            $server_company_cites = array();
            if (is_array($companycities)) {
                foreach ($companycities AS $c_m_city) {
                    $comcity = $this->getServerid('cities', $c_m_city->cityid);
                    $server_company_cites[] = array('id' => $c_m_city->id, 'companyid' => $c_m_city->companyid, 'cityid' => $comcity);
                }
            }
            $server_data_array = array('data' => $server_company_data_array, 'userfields' => $userfielddataarray, 'companycities' => $server_company_cites);
            if (!empty($server_data_array)) {
                $fortask = "storecompany";
                $server_json_data_array = json_encode($server_data_array);
                $return_server_value = $this->serverTask($server_json_data_array, $fortask);
                return json_decode($return_server_value, true);
            }
        } else {
            if (!empty($rejected_company)) {
                $companyname = json_encode($rejected_company);
            }
            $returnarray['iscompanystore'] = 0;
            $returnarray['referenceid'] = $data->id;
            $returnarray['message'] = 'Company store sucessfully but job sharing server rejected this company due to improper name' . $companyname;
            $returnarray['eventtype'] = 'Add Company';
            $returnarray['status'] = 'Improper Company name';
            return $returnarray;
        }
    }

    function storeCompanyLogoSharing($data, $company_logo) {
        $sitepath = 'https://jobs.joomsky.com/';
        if (is_array($company_logo) AND $company_logo['logofilename'] != "") {
            $this->CurlFileUploader($company_logo['logofilename'], $sitepath."/index.php?r=Companies/uploadcompanylogo", 'logofilename', array('company_id' => $data->company_id, 'authkey' => $data->authkey,'siteurl'=>$this->siteurl));
            $return_data = $this->curlUploadFile();
            $return_value = json_decode($return_data, true);
            $exp_return_value = explode('/', $return_data);
            $eventtype = $exp_return_value[0];
            if ($eventtype == "Curl error") {
                $logarray['uid'] = $this->_uid;
                $logarray['referenceid'] = "";
                $logarray['eventtype'] = "Curl not Responce";
                $logarray['message'] = $return_data;
                $logarray['event'] = "Company Logo";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $serverjobstatus = "Curl Not Responce";
                $this->writeJobSharingLog($logarray);
                return true; // not tell the user what error occured on jobsharing server
            }
            return $return_value;
        }
    }

    function storeJobSharing($data) {
        $server_data_array = array();
        $userfieldarray = array();
        $rejected_job = array();
        $badwords = array("test", "temp");

        $rejected = 0;
        foreach ($badwords AS $bwords) {
            if (strpos($data->title, $bwords) === 0 OR strpos($data->title, $bwords) !== false) {
                $rejected_job[] = $data->title;
                $rejected = 1;
                break;
            }
        }
        if ($rejected != 1) {


            if ($data->jobcategory == 0 OR $data->jobcategory == "") {
                $cat_s_id = 0;
                break;
            } else {
						if($data->jobcategory) $cat_s_id=$this->getServerid('categories',$data->jobcategory);
						else $cat_s_id=0;
					}

					if($data->subcategoryid) $jsubcat_s_id=$this->getServerid('subcategories',$data->subcategoryid);
					else $jsubcat_s_id=0;


					if($data->jobtype) $jtype_s_id=$this->getServerid('jobtypes',$data->jobtype);
					else $jtype_s_id=0;
						
					if($data->jobstatus) $js_s_id=$this->getServerid('jobstatus',$data->jobstatus);
					else $js_s_id=0;

					if($data->salaryrangetype) $jsrty_s_id=$this->getServerid('salaryrangetypes',$data->salaryrangetype);
					else $jsrty_s_id=0;

					if($data->shift) $jsh_s_id=$this->getServerid('shifts',$data->shift);
					else $jsh_s_id=0;

					if($data->educationid) $jed_s_id=$this->getServerid('heighesteducation',$data->educationid);
					else $jed_s_id=0;

					if($data->mineducationrange) $jmed_s_id=$this->getServerid('heighesteducation',$data->mineducationrange);
					else $jmed_s_id=0;
					
					if($data->maxeducationrange) $jmxed_s_id=$this->getServerid('heighesteducation',$data->maxeducationrange);
					else $jmxed_s_id=0;

					if($data->careerlevel) $jcl_s_id=$this->getServerid('careerlevels',$data->careerlevel);
					else $jcl_s_id=0;

					if($data->experienceid) $jex_s_id=$this->getServerid('experiences',$data->experienceid);
					else $jex_s_id=0;

					if($data->minexperiencerange) $jmex_s_id=$this->getServerid('experiences',$data->minexperiencerange);
					else $jmex_s_id=0;
					
					if($data->maxexperiencerange) $jmxex_s_id=$this->getServerid('experiences',$data->maxexperiencerange);
					else $jmxex_s_id=0;

					if($data->agefrom) $jagf_s_id=$this->getServerid('ages',$data->agefrom);
					else $jagf_s_id=0;
					
					if($data->ageto) $jagt_s_id=$this->getServerid('ages',$data->ageto);
					else $jagt_s_id=0;

					if($data->salaryrangefrom) $jsrf_s_id=$this->getServerid('salaryrange',$data->salaryrangefrom);
					else $jsrf_s_id=0;
					
					if($data->salaryrangeto) $jsrt_s_id=$this->getServerid('salaryrange',$data->salaryrangeto);
					else $jsrt_s_id=0;
					
					
					if($data->currencyid) $jcur_s_id=$this->getServerid('currencies',$data->currencyid);
					else $jcur_s_id=0;
					
            $server_job_data_array = array('uid' => $data->uid, 'companyid' => $data->companyid, 'title' => $data->title, 'alias' => $data->alias,
                'jobcategory' => $cat_s_id, 'jobtype' => $jtype_s_id, 'jobstatus' => $js_s_id, 'hidesalaryrange' => $data->hidesalaryrange,
                'salaryrangetype' => $jsrty_s_id,
                'description' => $data->description, 'qualifications' => $data->qualifications, 'prefferdskills' => $data->prefferdskills,
                'country' => $data->country, 'state' => $data->state/* ,'county'=>$data->county */, 'city' => $data->city,
                'showcontact' => $data->showcontact, 'noofjobs' => $data->noofjobs, 'duration' => $data->duration, 'created' => $data->created,
                'created_by' => $data->created_by, 'modified' => $data->modified, 'modified_by' => $data->modified_by, 'hits' => $data->hits,
                'experience' => $data->experience, 'startpublishing' => $data->startpublishing, 'stoppublishing' => $data->stoppublishing, 'departmentid' => $data->departmentid,
                'shift' => $jsh_s_id, 'sendemail' => $data->sendemail, 'metadescription' => $data->metadescription, 'metakeywords' => $data->metakeywords,
                'agreement' => $data->agreement, 'ordering' => $data->ordering, 'status' => $data->status,
                'educationminimax' => $data->educationminimax, 'educationid' => $jed_s_id, 'mineducationrange' => $jmed_s_id, 'maxeducationrange' => $jmxed_s_id,
                'iseducationminimax' => $data->iseducationminimax, 'degreetitle' => $data->degreetitle,
                'careerlevel' => $jcl_s_id, 'experienceminimax' => $data->experienceminimax, 'experienceid' => $jex_s_id,
                'minexperiencerange' => $jmex_s_id, 'maxexperiencerange' => $jmxex_s_id, 'isexperienceminimax' => $data->isexperienceminimax,
                'experiencetext' => $data->experiencetext, 'workpermit' => $data->workpermit, 'requiredtravel' => $data->requiredtravel,
                'agefrom' => $jagf_s_id, 'ageto' => $jagt_s_id, 'salaryrangefrom' => $jsrf_s_id, 'salaryrangeto' => $jsrt_s_id,
                'gender' => $data->gender, 'video' => $data->video, 'map' => $data->map, 'packageid' => $data->packageid,
                'paymenthistoryid' => $data->paymenthistoryid, 'subcategoryid' => $jsubcat_s_id, 'currencyid' => $jcur_s_id,
                'jobid' => $data->jobid, 'longitude' => $data->longitude, 'latitude' => $data->latitude,
                'isgoldjob' => $data->isgoldjob, 'isfeaturedjob' => $data->isfeaturedjob,
                'raf_gender' => $data->raf_gender, 'raf_degreelevel' => $data->raf_degreelevel, 'raf_experience' => $data->raf_experience,
                'raf_age' => $data->raf_age, 'raf_education' => $data->raf_education, 'raf_category' => $data->raf_category, 'raf_subcategory' => $data->raf_subcategory, 'raf_location' => $data->raf_location,
                'job_id' => $data->job_id,
					'authkey'=>$data->authkey,'id'=>$data->id,'siteurl'=>$this->_siteurl);

            $db = &$this->getDBO();
            $query = "select userfielddata.id AS fieldid, userfieldtitle.title AS userfieldtitle, userfielddata.data as userfieldvalue,
					userfieldtitle.type AS fieldtype,userfieldvalue.fieldtitle AS fieldtitle ,userfieldvalue.id AS fieldtitleid 
					From #__js_job_userfield_data AS userfielddata
					JOIN #__js_job_userfields AS userfieldtitle ON userfieldtitle.id=userfielddata.field
					JOIN #__js_job_userfieldvalues AS userfieldvalue ON userfieldvalue.field=userfielddata.field
					WHERE userfielddata.referenceid=" . $data->job_id;
            $db->setQuery($query);
            $userfielddata = $db->loadObjectList();

            foreach ($userfielddata AS $fdata) {
                if ($fdata->fieldtype == "select") {
                    if ($fdata->userfieldvalue == $fdata->fieldtitleid) {
                        $ftitle = $fdata->userfieldtitle;
                        $fvalue = $fdata->fieldtitle;
                        $userfieldarray[] = array('fieldtitle' => $ftitle, 'fieldvalue' => $fvalue);
                    }
                } else {
                    $ftitle = $fdata->userfieldtitle;
                    $fvalue = $fdata->userfieldvalue;
                    $userfieldarray[] = array('fieldtitle' => $ftitle, 'fieldvalue' => $fvalue);
                }
            }

            $userfielddataarray = array();
            $z = 0;
            foreach ($userfieldarray AS $array) {   // Limit User Field Store On server is 15
                if ($z <= 15) {
                    $userfielddataarray[] = array('fieldtitle_' . $z => $array['fieldtitle'], 'fieldvalue_' . $z => $array['fieldvalue']);
                    $z++;
                }
            }

            $query = "select id,jobid,cityid 
					From `#__js_job_jobcities` AS jcity
					WHERE jcity.jobid=" . $data->job_id;
            $db->setQuery($query);
            $jobcities = $db->loadObjectList();
            $server_job_cites = array();
            if (is_array($jobcities)) {
                foreach ($jobcities AS $j_m_city) {
                    $jobcity = $this->getServerid('cities', $j_m_city->cityid);
                    $server_job_cites[] = array('id' => $j_m_city->id, 'jobid' => $j_m_city->jobid, 'cityid' => $jobcity);
                }
            }
            $server_data_array = array('data' => $server_job_data_array, 'userfields' => $userfielddataarray, 'jobcities' => $server_job_cites);
            if (!empty($server_data_array)) {
                $fortask = "storejob";
                $server_json_data_array = json_encode($server_data_array);
                $return_server_value = $this->serverTask($server_json_data_array, $fortask);
                return json_decode($return_server_value, true);
            }
        } else {
            if (!empty($rejected_job)) {
                $jobname = json_encode($rejected_job);
            }
            $returnarray['isjobstore'] = 0;
            $returnarray['referenceid'] = $data->id;
            $returnarray['message'] = 'Job store sucessfully but job sharing server rejected this job due to improper name' . $jobname;
            $returnarray['eventtype'] = 'Add Job';
            $returnarray['status'] = 'Improper job name';
            return $returnarray;
        }
    }

    function getServerid($table, $id) {
        $db = & JFactory :: getDBO();
        switch ($table) {
            case "salaryrangetypes";
            case "careerlevels";
            case "experiences";
            case "ages";
            case "currencies";
            case "subcategories";
                $query = "SELECT serverid FROM #__js_job_" . $table . " WHERE status=1 AND id=" . $id;
                break;
            case "salaryrange";
                $query = "SELECT serverid FROM #__js_job_" . $table . " WHERE id=" . $id;
                break;
            case "countries";
            case "states";
            case "cities";
                $query = "SELECT serverid FROM #__js_job_" . $table . " WHERE enabled=1 AND id=" . $id;
                break;
            default:
                $query = "SELECT serverid FROM #__js_job_" . $table . " WHERE isactive=1 AND id=" . $id;
                break;
        }
        $db->setQuery($query);
        $server_id = $db->loadResult();
        return $server_id;
    }

    function getAllClientDefaultTableData() {
        $user = & JFactory::getUser();
        $uid = $user->id;
        $defalut_table_syn = array();
        $job_categories = $this->getAllCategoriesSynToServer();
        $defalut_table_syn['job_categories'] = $job_categories;
        $job_subcategories = $this->getAllSubcategoriesSynToServer();
        $defalut_table_syn['job_subcategories'] = $job_subcategories;
        $job_types = $this->getAllJobTypesSynToServer();
        $defalut_table_syn['job_types'] = $job_types;
        $job_status = $this->getAllJobStatusSynToServer();
        $defalut_table_syn['job_status'] = $job_status;
        $job_currencies = $this->getAllCurrenciesSynToServer();
        $defalut_table_syn['job_currencies'] = $job_currencies;
        $job_salaryrangetypes = $this->getAllSalaryRangeTypesSynToServer();
        $defalut_table_syn['job_salaryrangetypes'] = $job_salaryrangetypes;
        $job_salaryrange = $this->getAllSalaryRangeSynToServer();
        $defalut_table_syn['job_salaryrange'] = $job_salaryrange;
        $job_ages = $this->getAllAgesSynToServer();
        $defalut_table_syn['job_ages'] = $job_ages;
        $job_shifts = $this->getAllShiftsSynToServer();
        $defalut_table_syn['job_shifts'] = $job_shifts;
        $job_careerlevels = $this->getAllCareerLevelsSynToServer();
        $defalut_table_syn['job_careerlevels'] = $job_careerlevels;
        $job_experiences = $this->getAllExperiencesSynToServer();
        $defalut_table_syn['job_experiences'] = $job_experiences;
        $job_higheducation = $this->getAllHighestEducationsSynToServer();
        $defalut_table_syn['job_heighesteducation'] = $job_higheducation;
        $job_jobs = $this->getAllJobsSynToServer();
        $defalut_table_syn['job_jobs'] = $job_jobs;
        $job_companies = $this->getAllCompaniesSynToServer();
        $defalut_table_syn['job_companies'] = $job_companies;
        $job_departments = $this->getAllDepartmentsSynToServer();
        $defalut_table_syn['job_departments'] = $job_departments;
        $job_resume = $this->getAllResumeSynToServer();
        $defalut_table_syn['job_resume'] = $job_resume;
        $job_coverletters = $this->getAllCoverLettersSynToServer();
        $defalut_table_syn['job_coverletter'] = $job_coverletters;
        $job_jobapply = $this->getAllJobApplySynToServer();
        $defalut_table_syn['job_jobapply'] = $job_jobapply;
        $resume_resumerating = $this->getAllResumeRatingSynToServer();
        $defalut_table_syn['resume_resumerating'] = $resume_resumerating;
        $job_folders = $this->getAllFoldersSynToServer();
        $defalut_table_syn['job_folders'] = $job_folders;
        $job_folderresume = $this->getAllFolderResumeSynToServer();
        $defalut_table_syn['job_folderresumes'] = $job_folderresume;
        $job_messages = $this->getAllMessagesSynToServer();
        $defalut_table_syn['job_messages'] = $job_messages;
        $job_alert = $this->getAllJobseekerAlerts();
        $defalut_table_syn['job_jobalertsetting'] = $job_alert;
        $encode_defalt_tables = json_encode($defalut_table_syn);
        return $encode_defalt_tables;
    }

    function getClientAddressData() {
        $address_data_syn = array();
        $job_countries = $this->getAllCountriesSynToServer();
        $address_data_syn['job_countries'] = $job_countries;
        $job_states = $this->getAllStatesSynToServer();
        $address_data_syn['job_states'] = $job_states;
        $job_city = $this->getAllCitiesSynToServer();
        $address_data_syn['job_cities'] = $job_city;
        $address_data_syn = json_encode($address_data_syn);
        return $address_data_syn;
    }

    function getAllCategoriesSynToServer() {
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_categories WHERE isactive=1";
        $db->setQuery($query);
        $this->_application = $db->loadObjectList();
        return $this->_application;
    }

    function getAllSubcategoriesSynToServer() {
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_subcategories WHERE status=1";
        $db->setQuery($query);
        $this->_application = $db->loadObjectList();
        return $this->_application;
    }

    function getAllJobTypesSynToServer() {
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_jobtypes WHERE isactive=1";
        $db->setQuery($query);
        $this->_application = $db->loadObjectList();
        return $this->_application;
    }

    function getAllJobStatusSynToServer() {
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_jobstatus WHERE isactive=1";
        $db->setQuery($query);
        $this->_application = $db->loadObjectList();
        return $this->_application;
    }

    function getAllCurrenciesSynToServer() {
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_currencies WHERE status=1";
        $db->setQuery($query);
        $this->_application = $db->loadObjectList();
        return $this->_application;
    }

    function getAllCompaniesSynToServer() {
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_companies WHERE status=1";
        $db->setQuery($query);
        $this->_application = $db->loadObjectList();
        return $this->_application;
    }

    function getAllDepartmentsSynToServer() {
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_departments WHERE status=1";
        $db->setQuery($query);
        $this->_application = $db->loadObjectList();
        return $this->_application;
    }

    function getAllResumeSynToServer() {
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_resume WHERE status=1";
        $db->setQuery($query);
        $this->_application = $db->loadObjectList();
        return $this->_application;
    }

    function getAllCoverLettersSynToServer() {
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_coverletters";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

    function getAllJobApplySynToServer() {
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_jobapply";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

    function getAllResumeRatingSynToServer() {
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_resumerating";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

    
    function getAllFoldersSynToServer() {
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_folders";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

    function getAllFolderResumeSynToServer() {
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_folderresumes";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

    function getAllMessagesSynToServer() {
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_messages";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

    function getAllJobseekerAlerts() {
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_jobalertsetting WHERE status=1";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

    function getAllJobsSynToServer() {
        $db = & JFactory :: getDBO();
        $wherequery = "";
        $curdate = date('Y-m-d H:i:s');
        $query = "SELECT * FROM #__js_job_jobs AS job ";
        $wherequery .= " WHERE  job.status = 1 ";
        $query .= $wherequery;
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

    function getAllSalaryRangeTypesSynToServer() {
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_salaryrangetypes WHERE status=1";
        $db->setQuery($query);
        $this->_application = $db->loadObjectList();
        return $this->_application;
    }

    function getAllSalaryRangeSynToServer() {
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_salaryrange WHERE id!=''";
        $db->setQuery($query);
        $this->_application = $db->loadObjectList();
        return $this->_application;
    }

    function getAllAgesSynToServer() {
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_ages WHERE status=1";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

    function getAllShiftsSynToServer() {
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_shifts WHERE isactive=1";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

    function getAllCareerLevelsSynToServer() {
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_careerlevels WHERE status=1";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

    function getAllExperiencesSynToServer() {
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_experiences WHERE status=1";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

    function getAllHighestEducationsSynToServer() {
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_heighesteducation WHERE isactive=1";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

    function getAllCountriesSynToServer() {
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_countries WHERE enabled=1";
        $db->setQuery($query);
        $this->_application = $db->loadObjectList();
        return $this->_application;
    }

    function getAllStatesSynToServer() {
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_states WHERE enabled=1";
        $db->setQuery($query);
        $this->_application = $db->loadObjectList();
        return $this->_application;
    }

    function getAllCountiesSynToServer() {
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_counties WHERE enabled=1";
        $db->setQuery($query);
        $this->_application = $db->loadObjectList();
        return $this->_application;
    }

    function getAllCitiesSynToServer() {
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_cities WHERE enabled=1 AND isedit=1";
        $db->setQuery($query);
        $this->_application = $db->loadObjectList();
        return $this->_application;
    }

    function performTaskJobSharingService($jsondata, $url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
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

    function UpdateServerStatus($serverstatus, $client_id, $server_id, $uid, $table) {
        $db = &$this->getDBO();
        if ($client_id == "") {
            $query = "SELECT max(id) AS latid FROM `#__js_job_" . $table . "`";
            $db->setQuery($query);
            $client_id = $db->loadResult();
        }
        $query = "UPDATE `#__js_job_" . $table . "` SET serverstatus = '" . $serverstatus . "', serverid=" . $server_id . " WHERE id = " . $client_id;
        if ($table == "messages")
            $wherequery = " AND sendby = " . $uid;
        else
            $wherequery = " AND uid = " . $uid;
        $query.=$wherequery;
        $db->setQuery($query);
        if (!$db->query()) {
            echo $this->_db->getErrorMsg();
            return false;
        }
        return true;
    }

    function updateMultiCityServerid($serverids, $table) {
        $db = &$this->getDBO();
        $error = array();
        if (is_array($serverids) AND (!empty($serverids))) {
            foreach ($serverids AS $id) {
                $query = "UPDATE #__js_job_" . $table . " SET serverid=" . $id['server_id'] . " WHERE id = " . $id['client_id'];
                $db->setQuery($query);
                if (!$db->query()) {
                    $error[] = $this->_db->getErrorMsg();
                }
            }
            if (is_array($error) AND (!empty($error))) {
                return false;
            }
            return true;
        }
    }

    function writeJobSharingLog($data) {
        $row = & $this->getTable('jobsharingserviclog');
        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());
            echo $this->_db->getErrorMsg();
            return false;
        }
        return true;
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
        JFactory::getApplication()->close();
    }
}
