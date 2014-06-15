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
 * File Name:	models/jsjobs.php
  ^
 * Description: Model class for jsjobs data
  ^
 * History:		NONE
  ^
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');
jimport('joomla.html.html');
require_once('job_sharing.php');
$option = JRequest :: getVar('option', 'com_jsjobs');

class JSJobsModelEmployer extends JModelLegacy {

    var $_id = null;
    var $_uid = null;
    var $_job = null;
    var $_application = null;
    var $_applications = array();
    var $_options = null;
    var $_empoptions = null;
    var $_searchoptions = null;
    var $_config = null;
    var $_jobtype = null;
    var $_jobstatus = null;
    var $_heighesteducation = null;
    var $_shifts = null;
    var $_defaultcountry = null;
    var $_defaultcurrency = null;
    var $_job_editor = null;
    var $_comp_editor = null;
    var $_filterlists = null;
    var $_careerlevels = null;
    var $_experiences = null;
    var $_ages = null;
    var $_careerlevel = null;
    var $_countries = null;
    var $_jobsalaryrange = null;
    var $_client_auth_key = null;
    var $_defaultcountryid = null;
    var $common_model = null;
    var $_siteurl = null;

    function __construct() {
        parent :: __construct();
        $this->common_model = $this->getCommonModel();
        $client_auth_key = $this->common_model->getClientAuthenticationKey();
        $this->_client_auth_key = $client_auth_key;
        $this->_siteurl = JURI::root();

        $user = & JFactory::getUser();
        $this->_uid = $user->id;
        $this->_arv = "/\aseofm/rvefli/ctvrnaa/kme/\rfer";
        $this->_ptr = "/\blocalh";
    }

    function &getCommonModel() {
        $componentPath = JPATH_SITE.'/components/com_jsjobs';
        require_once $componentPath . '/models/common.php';
        $common_model = new JSJobsModelCommon();
        return $common_model;
    }

    function &getRssResumes() {
        $config = $this->common_model->getConfigByFor('default');
        if ($config['resume_rss'] == 1) {
            $db = &$this->getDBO();
            $curdate = date('Y-m-d H:i:s');
            $query = "SELECT resume.id,resume.filetype,resume.filesize,resume.application_title,resume.photo,resume.filename,resume.first_name,resume.last_name,
                                                resume.email_address,resume.total_experience,cat.cat_title,resume.gender,edu.title AS education
                                                FROM `#__js_job_resume` AS resume
						JOIN `#__js_job_categories` AS cat ON resume.job_category = cat.id
						JOIN `#__js_job_heighesteducation` AS edu ON resume.heighestfinisheducation = edu.id
						WHERE resume.status = 1";
            $db->setQuery($query);
            $result = $db->loadObjectList();
            return $result;
        }
        return false;
    }

    function &getFolderResumebyFolderId($uid, $folderid, $sortby, $limit, $limitstart) {
        $db = &$this->getDBO();
        if ($uid)
            if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
                return false;
        if (is_numeric($folderid) == false)
            return false;
        $result = array();

        if ($this->_client_auth_key != "") {
            $data['uid'] = $uid;
            $data['folderid'] = $folderid;
            $data['sortby'] = $sortby;
            $data['limit'] = $limit;
            $data['limitstart'] = $limitstart;
            $data['authkey'] = $this->_client_auth_key;
            $data['siteurl'] = $this->_siteurl;
            $fortask = "getfolderresumebyfolderid";
            $jsjobsharingobject = new JSJobsModelJob_Sharing;
            $encodedata = json_encode($data);
            $return_server_value = $jsjobsharingobject->serverTask($encodedata, $fortask);
            if (isset($return_server_value['folderresume']) AND $return_server_value['folderresume'] == -1) { // auth fail 
                $logarray['uid'] = $this->_uid;
                $logarray['referenceid'] = $return_server_value['referenceid'];
                $logarray['eventtype'] = $return_server_value['eventtype'];
                $logarray['message'] = $return_server_value['message'];
                $logarray['event'] = "Folder Resume";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jsjobsharingobject->write_JobSharingLog($logarray);
                $result[0] = (object) array();
                $result[1] = 0;
            } else {
                $parse_data = array();
                foreach ($return_server_value['folderresume'] AS $rel_data) {
                    $parse_data[] = (object) $rel_data;
                }
                $result[0] = $parse_data;
                $result[1] = $return_server_value['total'];
            }
        } else {
            $query = "SELECT COUNT(folderresume.id)
                                FROM `#__js_job_folderresumes` AS folderresume
				JOIN `#__js_job_resume` AS resume ON folderresume.resumeid = resume.id
                                WHERE folderresume.folderid = " . $folderid . "
                                AND resume.published = 1 ";
            $db->setQuery($query);
            $total = $db->loadResult();
            if ($total <= $limitstart)
                $limitstart = 0;
            $query = "SELECT  fres.jobid AS jobid,apply.comments,apply.id, cat.cat_title ,apply.apply_date, jobtype.title AS jobtypetitle
                            , app.id AS appid, app.first_name, app.last_name, app.email_address, app.jobtype,app.gender
                            ,app.total_experience, app.jobsalaryrange, salary.rangestart, salary.rangeend
                            ,rating.id AS ratingid, rating.rating
                            ,app.address_city, app.address_state
                            ,country.name AS countryname,state.name AS statename
                            ,city.name AS cityname
                            ,CONCAT(app.alias,'-',app.id) AS aliasid
                            ,cur.symbol
                            FROM `#__js_job_resume` AS app
                            JOIN `#__js_job_jobtypes` AS jobtype ON app.jobtype = jobtype.id
                            JOIN `#__js_job_categories` AS cat ON app.job_category = cat.id
                            JOIN `#__js_job_jobapply` AS apply  ON apply.cvid = app.id
                            LEFT JOIN  `#__js_job_resumerating` AS rating ON (app.id=rating.resumeid AND apply.jobid=rating.jobid)
                            LEFT JOIN  `#__js_job_salaryrange` AS salary ON app.jobsalaryrange=salary.id
                            LEFT JOIN  `#__js_job_currencies` AS cur ON app.currencyid=cur.id
                            LEFT JOIN  `#__js_job_folderresumes` AS fres ON (app.id=fres.resumeid AND apply.jobid=fres.jobid)
                            LEFT JOIN `#__js_job_cities` AS city ON app.address_city = city.id
                            LEFT JOIN `#__js_job_countries` AS country ON city.countryid  = country.id
                            LEFT JOIN `#__js_job_states` AS state ON city.stateid = state.id
                            WHERE fres.folderid = " . $folderid;
            $db->setQuery($query, $limitstart, $limit);
            $folderresume = $db->loadObjectList();
            $result[0] = $folderresume;
            $result[1] = $total;
        }
        return $result;
    }

    function &getMyFolders($uid, $limit, $limitstart) {
        $result = array();
        $db = &$this->getDBO();

        if (is_numeric($uid) == false)
            return false;
        if (($uid == 0) || ($uid == ''))
            return false;
        if ($this->_client_auth_key != "") {
            $data['uid'] = $uid;
            $data['limit'] = $limit;
            $data['limitstart'] = $limitstart;
            $data['authkey'] = $this->_client_auth_key;
            $data['siteurl'] = $this->_siteurl;
            $fortask = "getmyfolders";
            $jsjobsharingobject = new JSJobsModelJob_Sharing;
            $encodedata = json_encode($data);
            $return_server_value = $jsjobsharingobject->serverTask($encodedata, $fortask);
            if (isset($return_server_value['myfolders']) AND $return_server_value['myfolders'] == -1) { // auth fail 
                $logarray['uid'] = $this->_uid;
                $logarray['referenceid'] = $return_server_value['referenceid'];
                $logarray['eventtype'] = $return_server_value['eventtype'];
                $logarray['message'] = $return_server_value['message'];
                $logarray['event'] = "My Folders";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jsjobsharingobject->write_JobSharingLog($logarray);
                $result[0] = array();
                $result[1] = 0;
            } else {
                $parse_data = array();
                foreach ($return_server_value['folderdata'] AS $rel_data) {
                    $parse_data[] = (object) $rel_data;
                }
                $result[0] = $parse_data;
                $result[1] = $return_server_value['total'];
            }
        } else {
            $query = "SELECT count(folder.id)
                        FROM `#__js_job_folders` AS folder
                        WHERE folder.uid = " . $uid;
            $db->setQuery($query);
            $total = $db->loadResult();
            if ($total <= $limitstart)
                $limitstart = 0;

            $query = "SELECT folder.*,CONCAT(folder.alias,'-',folder.id) AS aliasid
                        , ( SELECT count(id) FROM `#__js_job_folderresumes` WHERE folder.id = folderid) AS noofresume
                        FROM `#__js_job_folders` AS folder
                        WHERE folder.uid = " . $uid;
            $db->setQuery($query, $limitstart, $limit);
            $result[0] = $db->loadObjectList();
            $result[1] = $total;
        }

        return $result;
    }

    function &getFolderDetail($uid, $fid) {
        $result = array();
        $db = &$this->getDBO();
        if (is_numeric($uid) == false)
            return false;
        if (!is_numeric($fid))
            return false;
        if (($uid == 0) || ($uid == ''))
            return false;
        if ($this->_client_auth_key != "") {
            $data['uid'] = $uid;
            $data['fid'] = $fid;
            $data['authkey'] = $this->_client_auth_key;
            $data['siteurl'] = $this->_siteurl;
            $fortask = "getfolderdetail";
            $jsjobsharingobject = new JSJobsModelJob_Sharing;
            $encodedata = json_encode($data);
            $return_server_value = $jsjobsharingobject->serverTask($encodedata, $fortask);
            if (isset($return_server_value['folderdetail']) AND $return_server_value['folderdetail'] == -1) { // auth fail 
                $logarray['uid'] = $this->_uid;
                $logarray['referenceid'] = $return_server_value['referenceid'];
                $logarray['eventtype'] = $return_server_value['eventtype'];
                $logarray['message'] = $return_server_value['message'];
                $logarray['event'] = "Folder Detail";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jsjobsharingobject->write_JobSharingLog($logarray);
                $result = (object) array('name' => '', 'decription' => '');
            } else {
                $result = (object) $return_server_value[0];
            }
        } else {
            $query = "SELECT folder.*
                        FROM `#__js_job_folders` AS folder
                        WHERE folder.uid = " . $uid . " AND folder.id = " . $fid;
            $db->setQuery($query);
            $result = $db->loadObject();
        }


        return $result;
    }

    function getMyJobsForCombo($uid, $title) {
        if (!is_numeric($uid))
            return $uid;
        $db = & JFactory::getDBO();
        $query = "SELECT  id, title FROM `#__js_job_jobs` WHERE jobstatus = 1 AND uid = " . $uid . " ORDER BY title ASC ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $jobs = array();
        if ($title)
            $jobs[] = array('value' => JText::_(''), 'text' => $title);
        foreach ($rows as $row) {
            $jobs[] = array('value' => $row->id, 'text' => $row->title);
        }
        return $jobs;
    }

    function &getFolderbyIdforForm($id, $uid) {
        $db = &$this->getDBO();
        if (is_numeric($uid) == false)
            return false;
        if ($this->_client_auth_key != "") {
            $data['uid'] = $uid;
            $data['fid'] = $id;
            $data['authkey'] = $this->_client_auth_key;
            $data['siteurl'] = $this->_siteurl;
            $fortask = "getfolderbyidforform";
            $jsjobsharingobject = new JSJobsModelJob_Sharing;
            $encodedata = json_encode($data);
            $return_server_value = $jsjobsharingobject->serverTask($encodedata, $fortask);
            if (isset($return_server_value['folderforform']) AND $return_server_value['folderforform'] == -1) { // auth fail 
                $logarray['uid'] = $this->_uid;
                $logarray['referenceid'] = $return_server_value['referenceid'];
                $logarray['eventtype'] = $return_server_value['eventtype'];
                $logarray['message'] = $return_server_value['message'];
                $logarray['event'] = "Folder Form";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jsjobsharingobject->write_JobSharingLog($logarray);
                $folder = (object) array('name' => '', 'decription' => '', 'created' => '');
            } else {
                if ($return_server_value != false)
                    $folder = (object) $return_server_value[0];
            }
        }else {
            if (($id != '') && ($id != 0)) {
                if (is_numeric($id) == false)
                    return false;
                $query = "SELECT folder.*
				FROM `#__js_job_folders` AS folder
				WHERE folder.id = " . $id;
                $db->setQuery($query);
                $folder = $db->loadObject();
            }
        }

        if (isset($folder))
            $result[0] = $folder;
        else
            $result[0] = null;

        if ($id) // not new
            $result[1] = 1;
        else { // new
            $returnvalue = $this->canAddNewFolder($uid);
            $result[1] = $returnvalue[0];
            $result[2] = $returnvalue[1];
        }

        return $result;
    }

    function &getCompanybyIdforForm($id, $uid, $visitor, $vis_email, $jobid) {
        $db = &$this->getDBO();
        if (is_numeric($uid) == false)
            return false;
        if (($id != '') && ($id != 0)) {
            if (is_numeric($id) == false)
                return false;
            $query = "SELECT company.*
			FROM `#__js_job_companies` AS company 
			WHERE company.id = " . $id;
            $db->setQuery($query);
            $company = $db->loadObject();
        }
        if (isset($vis_email) && ($vis_email != '') && ($jobid != '')) {
            $query = "SELECT company.*
			FROM `#__js_job_jobs` AS job
			JOIN `#__js_job_companies` AS company ON company.id = job.companyid
			WHERE job.jobid = " . $db->quote($jobid) . " AND company.contactemail = " . $db->quote($vis_email);

            $db->setQuery($query);
            $company = $db->loadObject();
        }
        if (isset($visitor) && $visitor == 1) {
            if (isset($company)) {
                $lists['jobcategory'] = JHTML::_('select.genericList', $this->common_model->getCategories(''), 'companycategory', 'class="inputbox required" ' . '', 'value', 'text', $company->category);
                $multi_lists = $this->common_model->getMultiSelectEdit($company->id, 2);
            } else {
                if (!isset($this->_config)) {
                    $this->_config = $this->common_model->getConfig('');
                }
                $comapnies = $this->common_model->getCompanies($uid);
                if (isset($this->_defaultcountry))
                    $states = $this->getStates($this->_defaultcountry, '');
                $lists['jobcategory'] = JHTML::_('select.genericList', $this->common_model->getCategories(''), 'companycategory', 'class="inputbox required" ' . '', 'value', 'text', '');
                if (isset($comapnies[0]))
                    $lists['companies'] = JHTML::_('select.genericList', $this->common_model->getCompanies($uid), 'companycompany', 'class="inputbox required" ' . '', 'value', 'text', '');
            }
        }else {
            if (isset($company)) {
                $lists['jobcategory'] = JHTML::_('select.genericList', $this->common_model->getCategories(''), 'category', 'class="inputbox required" ' . '', 'value', 'text', $company->category);
                $multi_lists = $this->common_model->getMultiSelectEdit($id, 2);
            } else {
                if (!isset($this->_config)) {
                    $this->_config = $this->common_model->getConfig('');
                }
                $lists['jobcategory'] = JHTML::_('select.genericList', $this->common_model->getCategories(''), 'category', 'class="inputbox required" ' . '', 'value', 'text', '');
                $lists['companies'] = JHTML::_('select.genericList', $this->common_model->getCompanies($uid), 'company', 'class="inputbox required" ' . '', 'value', 'text', '');
            }
        }
        if (isset($company))
            $result[0] = $company;
        else
            $result[0] = null;
        $result[1] = $lists;
        if (isset($visitor) && $visitor == 1) {
            if (isset($company))
                $id = $company->id;
            $result[2] = $this->common_model->getUserFields(1, $id); // company fields, id
        }else {
            $result[2] = $this->common_model->getUserFields(1, $id); // company fields, id
        }
        $result[3] = $this->common_model->getFieldsOrdering(1); // company fields

        if ($id) // not new
            $result[4] = 1;
        else { // new
            $returnvalue = $this->canAddNewCompany($uid);
            $result[4] = $returnvalue[0];
            $result[5] = $returnvalue[1];
        }
        if (isset($multi_lists))
            $result[6] = $multi_lists;
        return $result;
    }

    function &getResumeSearch($uid, $title, $name, $nationality, $gender, $iamavailable, $jobcategory, $jobsubcategory, $jobtype, $jobstatus, $currency, $jobsalaryrange, $education
    , $experience, $sortby, $limit, $limitstart, $zipcode, $keywords) {
        $db = &$this->getDBO();

        if (is_numeric($uid) == false)
            return false;
        if (($uid == 0) || ($uid == ''))
            return false;
        if (!isset($this->_config)) {
            $this->_config = $this->common_model->getConfig('');
        }
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'newlisting_requiredpackage')
                $newlisting_required_package = $conf->configvalue;
        }
        if ($newlisting_required_package == 0) {
            $cansearch = -1;
        } else {
            $query = "SELECT  package.resumesearch
                        FROM `#__js_job_employerpackages` AS package
                        JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=1)
                        WHERE payment.uid = " . $uid . "
                        AND DATE_ADD(payment.created,INTERVAL package.packageexpireindays DAY) >= CURDATE()
                        AND payment.transactionverified = 1 AND payment.status = 1";
            $db->setQuery($query);
            $results = $db->loadObjectList();
            $cansearch = 0;
            foreach ($results AS $result) {
                if ($result->resumesearch != -1) {
                    $cansearch += $result->resumesearch;
                }
            }
            if ($cansearch == 0) {
                $result = false;
                return $result;
            }
        }

        if ($gender != '')
            if (is_numeric($gender) == false)
                return false;
        if ($iamavailable != '')
            if (is_numeric($iamavailable) == false)
                return false;
        if ($jobcategory != '')
            if (is_numeric($jobcategory) == false)
                return false;
        if ($jobsubcategory != '')
            if (is_numeric($jobsubcategory) == false)
                return false;
        if ($jobtype != '')
            if (is_numeric($jobtype) == false)
                return false;
        if ($jobsalaryrange != '')
            if (is_numeric($jobsalaryrange) == false)
                return false;
        if ($education != '')
            if (is_numeric($education) == false)
                return false;
        if ($currency != '')
            if (is_numeric($currency) == false)
                return false;
        if ($zipcode != '')
            if (is_numeric($zipcode) == false)
                return false;


        if ($newlisting_required_package == 0) {
            $canview = 1;
        } else {

            $query = "SELECT package.saveresumesearch, package.packageexpireindays, payment.created
			FROM `#__js_job_employerpackages` AS package
			JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=1)
			WHERE payment.uid = " . $uid . "
			AND DATE_ADD(payment.created,INTERVAL package.packageexpireindays DAY) >= CURDATE()";
            $db->setQuery($query);
            $jobs = $db->loadObjectList();
            $canview = 0;
            foreach ($jobs AS $job) {
                if ($job->saveresumesearch == 1) {
                    $canview = 1;
                    break;
                }
                else
                    $canview = 0;
            }
        }

        $result = array();
        $searchresumeconfig = $this->common_model->getConfigByFor('searchresume');
        $wherequery = '';

        if ($title != '') { // For title  Search
            $titlekeywords = explode(' ', $title);
            $length = count($titlekeywords);
            if ($length <= 5) {// For Limit keywords to 5
                $i = $length;
            } else {
                $i = 5;
            }
            for ($j = 0; $j < $i; $j++) {
                $titlekeys[] = " resume.application_title Like '%$titlekeywords[$j]%'";
            }
        }
        if (isset($titlekeys))
            $wherequery .= " AND ( " . implode(' OR ', $titlekeys) . " )";

        if ($keywords != '') { // For title  Search
            $keywords = explode(' ', $keywords);
            $length = count($keywords);
            if ($length <= 5) {// For Limit keywords to 5
                $i = $length;
            } else {
                $i = 5;
            }
            for ($j = 0; $j < $i; $j++) {
                $keys[] = " resume.keywords Like '%$keywords[$j]%'";
            }
        }
        if (isset($keys))
            $wherequery .= " AND ( " . implode(' OR ', $keys) . " )";

        if ($name != '') {
            $wherequery .= " AND (";
            $wherequery .= " LOWER(resume.first_name) LIKE " . $db->Quote('%' . $name . '%', false);
            $wherequery .= " OR LOWER(resume.last_name) LIKE " . $db->Quote('%' . $name . '%', false);
            $wherequery .= " OR LOWER(resume.middle_name) LIKE " . $db->Quote('%' . $name . '%', false);
            $wherequery .= " )";
        }

        if ($nationality != '')
            $wherequery .= " AND resume.nationality = " . $nationality;
        if ($gender != '')
            $wherequery .= " AND resume.gender = " . $gender;
        if ($iamavailable != '')
            $wherequery .= " AND resume.iamavailable = " . $iamavailable;
        if ($jobcategory != '')
            $wherequery .= " AND resume.job_category = " . $jobcategory;
        if ($jobsubcategory != '')
            $wherequery .= " AND resume.job_subcategory = " . $jobsubcategory;
        if ($jobtype != '')
            $wherequery .= " AND resume.jobtype = " . $jobtype;
        if ($jobsalaryrange != '')
            $wherequery .= " AND resume.jobsalaryrange = " . $jobsalaryrange;
        if ($education != '')
            $wherequery .= " AND resume.heighestfinisheducation = " . $education;
        if ($currency != '')
            $wherequery .= " AND resume.currencyid = " . $currency;
        if ($experience != '')
            $wherequery .= " AND resume.total_experience LIKE " . $db->Quote($experience);


        if ($zipcode != '')
            $wherequery .= " AND resume.address_zipcode =" . $zipcode;

        $query = "SELECT count(resume.id) 
                    FROM `#__js_job_resume` AS resume, 
                    `#__js_job_categories` AS cat
                    WHERE resume.job_category = cat.id AND resume.status = 1 AND resume.searchable = 1  ";
        $query .= $wherequery;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT  resume.*, cat.cat_title, jobtype.title AS jobtypetitle
                    , salary.rangestart, salary.rangeend , country.name AS countryname
                    , city.name AS cityname,state.name AS statename
                    , currency.symbol as symbol	
                    ,CONCAT(resume.alias,'-',resume.id) AS aliasid
                    FROM `#__js_job_resume` AS resume
                    LEFT JOIN `#__js_job_categories` AS cat ON resume.job_category = cat.id
                    LEFT JOIN `#__js_job_jobtypes` AS jobtype ON resume.jobtype = jobtype.id
                    LEFT JOIN `#__js_job_salaryrange` AS salary ON resume.jobsalaryrange = salary.id
                    LEFT JOIN `#__js_job_cities` AS city ON resume.address_city= city.id
                    LEFT JOIN `#__js_job_countries` AS country ON city.countryid = country.id
                    LEFT JOIN `#__js_job_states` AS state ON city.stateid = state.id
                    LEFT JOIN `#__js_job_currencies` AS currency ON currency.id = resume.currencyid";
        $query .=" WHERE resume.status = 1 AND resume.searchable = 1";
        $query .= $wherequery;
        $query .= " ORDER BY  " . $sortby;
        $db->setQuery($query, $limitstart, $limit);

        $result[0] = $db->loadObjectList();
        $result[1] = $total;
        $result[2] = $searchresumeconfig;
        $result[3] = $canview;

        return $result;
    }

    function getResumeBySubCategoryId($uid, $jobsubcategory, $sortby, $limit, $limitstart) {
        $db = &$this->getDBO();

        if (is_numeric($uid) == false)
            return false;
        if (is_numeric($jobsubcategory) == false)
            return false;
        if (($uid == 0) || ($uid == ''))
            return false;
        $result = array();

        $query = "SELECT count(resume.id) 
                    FROM `#__js_job_resume` AS resume
                    JOIN `#__js_job_subcategories` AS subcat ON resume.job_subcategory=subcat.id
                    WHERE subcat.id = " . $jobsubcategory . " AND resume.status = 1 AND resume.searchable = 1  ";

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;
        if ($total != 0) {

            $query = "SELECT  resume.*,cat.id as cat_id,cat.cat_title, subcat.title as subcategory,jobtype.title AS jobtypetitle
                        , salary.rangestart, salary.rangeend , country.name AS countryname
                        , city.name AS cityname,state.name AS statename
                        , currency.symbol as symbol	
                        ,CONCAT(subcat.alias,'-',subcat.id) AS aliasid
                        ,CONCAT(resume.alias,'-',resume.id) AS resumealiasid
                        FROM `#__js_job_resume` AS resume
                        JOIN `#__js_job_categories` AS cat ON resume.job_category = cat.id
                        JOIN `#__js_job_subcategories` AS subcat ON resume.job_subcategory =" . $jobsubcategory . " 
                        JOIN `#__js_job_jobtypes` AS jobtype ON resume.jobtype = jobtype.id
                        LEFT JOIN `#__js_job_salaryrange` AS salary ON resume.jobsalaryrange = salary.id
                        LEFT JOIN `#__js_job_cities` AS city ON resume.address_city= city.id
                        LEFT JOIN `#__js_job_countries` AS country ON city.countryid = country.id
                        LEFT JOIN `#__js_job_states` AS state ON city.stateid = state.id
                        LEFT JOIN `#__js_job_currencies` AS currency ON currency.id = resume.currencyid ";
            $query .= " WHERE subcat.id = " . $jobsubcategory . " AND resume.status = 1 AND resume.searchable = 1";
            $query .= " ORDER BY  " . $sortby;

            $db->setQuery($query, $limitstart, $limit);
            $resumebysubcategorydata = $db->loadObjectList();
        } else {
            $query = "SELECT cat.id as cat_id, cat.cat_title, subcat.title as subcategory
                        FROM `#__js_job_categories` AS cat
                        JOIN `#__js_job_subcategories` AS subcat ON subcat.categoryid = cat.id
                        WHERE subcat.id = " . $jobsubcategory;
            $db->setQuery($query);
            $subcategorydata = $db->loadObject();
        }

        if (isset($resumebysubcategorydata))
            $result[0] = $resumebysubcategorydata;
        if (isset($subcategorydata))
            $result[2] = $subcategorydata;
        $result[1] = $total;
        return $result;
    }

    function &getResumeByCategoryId($uid, $jobcategory, $sortby, $limit, $limitstart) {
        $db = &$this->getDBO();

        if (is_numeric($uid) == false)
            return false;
        if (is_numeric($jobcategory) == false)
            return false;
        if (($uid == 0) || ($uid == ''))
            return false;
        $searchresumeconfig = $this->common_model->getConfigByFor('searchresume');
        $result = array();
        $query = "SELECT count(resume.id) 
						FROM `#__js_job_resume` AS resume
						JOIN `#__js_job_categories` AS cat ON resume.job_category = cat.id
						WHERE cat.id = " . $jobcategory . " AND resume.status = 1 AND resume.searchable = 1  ";
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT  resume.*, cat.cat_title, jobtype.title AS jobtypetitle
                    , salary.rangestart, salary.rangeend , country.name AS countryname
                    , city.name AS cityname,state.name AS statename
                    , currency.symbol as symbol	
                    ,CONCAT(cat.alias,'-',cat.id) AS aliasid
                    ,CONCAT(resume.alias,'-',resume.id) AS resumealiasid
                    FROM `#__js_job_resume` AS resume
                    JOIN `#__js_job_categories` AS cat ON resume.job_category = cat.id
                    JOIN `#__js_job_jobtypes` AS jobtype ON resume.jobtype = jobtype.id
                    LEFT JOIN `#__js_job_salaryrange` AS salary ON resume.jobsalaryrange = salary.id
                    LEFT JOIN `#__js_job_cities` AS city ON resume.address_city = city.id
                    LEFT JOIN `#__js_job_countries` AS country ON city.countryid = country.id
                    LEFT JOIN `#__js_job_states` AS state ON city.stateid = state.id
                    LEFT JOIN `#__js_job_currencies` AS currency ON currency.id = resume.currencyid ";
        $query .= " WHERE cat.id = " . $jobcategory . " AND resume.status = 1 AND resume.searchable = 1";
        $query .= " ORDER BY  " . $sortby;
        $db->setQuery($query, $limitstart, $limit);
        $resume = $db->loadObjectList();

        if ($searchresumeconfig['resume_subcategories'] == 1) {
            $inquery = " (SELECT COUNT(resume.id) from `#__js_job_resume` AS resume WHERE subcat.id = resume.job_subcategory AND resume.status = 1 AND resume.searchable = 1 ) as resumeinsubcat";

            $query = "SELECT  DISTINCT subcat.id, subcat.title,CONCAT(subcat.alias,'-',subcat.id) AS aliasid, ";
            $query .= $inquery;
            $query .= " FROM `#__js_job_subcategories` AS subcat
                        LEFT JOIN `#__js_job_resume` AS resume ON subcat.id = resume.job_subcategory
                        LEFT JOIN `#__js_job_cities` AS city ON resume.address_city = city.id
                        LEFT JOIN `#__js_job_countries` AS country ON city.countryid = country.id
                        LEFT JOIN `#__js_job_states` AS state ON city.stateid = state.id
                        WHERE subcat.status = 1 AND categoryid = " . $jobcategory;
            $query .= " ORDER BY subcat.title ";

            $db->setQuery($query);
            $resumesubcategory = $db->loadObjectList();
        }

        //for categroy title
        $query = "SELECT cat_title FROM `#__js_job_categories` WHERE id = " . $jobcategory;
        $db->setQuery($query);
        $cat_title = $db->loadResult();

        $result[0] = $resume;
        $result[1] = $total;
        $result[2] = $searchresumeconfig;
        $result[3] = $cat_title;
        $result[4] = $jobcategory;
        $result[5] = $resumesubcategory;
        return $result;
    }

    function getMyStats_Employer($uid) {
        if (is_numeric($uid) == false)
            return false;
        if (($uid == 0) || ($uid == ''))
            return false;

        if (!isset($this->_config)) {
            $this->_config = $this->common_model->getConfig('');
        }
        $ispackagerequired = 1;
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'newlisting_requiredpackage')
                $newlisting_required_package = $conf->configvalue;
        }
        if ($newlisting_required_package == 0) {
            $ispackagerequired = 0;
        }


        $db = &$this->getDBO();
        $results = array();

        // companies
        $query = "SELECT package.companiesallow,package.jobsallow,package.featuredcompaines
                    ,package.goldcompanies,package.goldjobs,package.featuredjobs
                    FROM #__js_job_paymenthistory AS payment
                    JOIN #__js_job_employerpackages AS package ON (package.id = payment.packageid AND payment.packagefor=1)
                    WHERE payment.uid = " . $uid . "
                    AND DATE_ADD(payment.created,INTERVAL package.packageexpireindays DAY) >= CURDATE()
                    AND payment.transactionverified = 1 AND payment.status = 1";
        $db->setQuery($query);
        $packages = $db->loadObjectList();
        if (empty($packages)) {
            $query = "SELECT package.id, package.resumeallow,package.title AS packagetitle, package.packageexpireindays, payment.id AS paymentid
                        , (TO_DAYS( CURDATE() ) - To_days( payment.created ) ) AS packageexpiredays
                       FROM `#__js_job_employerpackages` AS package
                       JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=1)
                       WHERE payment.uid = " . $uid . " 
                       AND payment.transactionverified = 1 AND payment.status = 1 ORDER BY payment.created DESC";

            $db->setQuery($query);
            $packagedetail = $db->loadObjectList();

            $results[12] = false;
            $results[13] = $packagedetail;

            $query = "SELECT package.resumeallow,package.coverlettersallow,package.featuredresume,package.goldresume
                    FROM #__js_job_employerpackages AS package
                    JOIN #__js_job_paymenthistory AS payment ON (package.id = payment.packageid AND payment.packagefor=1)
                    WHERE payment.uid = " . $uid . "
                    AND payment.transactionverified = 1 AND payment.status = 1";
            $db->setQuery($query);
            $packages = $db->loadObjectList();
        }
        $companiesunlimited = 0;
        $unlimitedjobs = 0;
        $unlimitedfeaturedcompaines = 0;
        $unlimitedgoldcompanies = 0;
        $unlimitedgoldjobs = 0;
        $unlimitedfeaturedjobs = 0;
        $jobsallow = 0;
        $companiesallow = 0;
        $goldcompaniesallow = 0;
        $goldjobsallow = 0;
        $featuredcompainesallow = 0;
        $featuredjobsallow = 0;
        if (!empty($packages)) {
            foreach ($packages AS $package) {
                if ($companiesunlimited == 0) {
                    if ($package->companiesallow != -1) {
                        $companiesallow = $companiesallow + $package->companiesallow;
                    }
                    else
                        $companiesunlimited = 1;
                }
                if ($unlimitedjobs == 0) {
                    if ($package->jobsallow != -1) {
                        $jobsallow = $jobsallow + $package->jobsallow;
                    }
                    else
                        $unlimitedjobs = 1;
                }
                if ($unlimitedfeaturedcompaines == 0) {
                    if ($package->featuredcompaines != -1) {
                        $featuredcompainesallow = $featuredcompainesallow + $package->featuredcompaines;
                    }
                    else
                        $unlimitedfeaturedcompaines = 1;
                }
                if ($unlimitedgoldcompanies == 0) {
                    if ($package->goldcompanies != -1) {
                        $goldcompaniesallow = $goldcompaniesallow + $package->goldcompanies;
                    }
                    else
                        $unlimitedgoldcompanies = 1;
                }
                if ($unlimitedgoldjobs == 0) {
                    if ($package->goldjobs != -1) {
                        $goldjobsallow = $goldjobsallow + $package->goldjobs;
                    }
                    else
                        $unlimitedgoldjobs = 1;
                }
                if ($unlimitedfeaturedjobs == 0) {
                    if ($package->featuredjobs != -1) {
                        $featuredjobsallow = $featuredjobsallow + $package->featuredjobs;
                    }
                    else
                        $unlimitedfeaturedjobs = 1;
                }
            }
        }

        //companies
        $query = "SELECT COUNT(company.id) FROM #__js_job_companies AS company WHERE  uid = " . $uid;
        $db->setQuery($query);
        $totalcompanies = $db->loadResult();

        //featured companies
        $query = "SELECT COUNT(id) FROM #__js_job_companies WHERE isfeaturedcompany=1 AND uid = " . $uid;
        $db->setQuery($query);
        $totalfeaturedcompanies = $db->loadResult();

        //featured companies expire
        $query = "SELECT COUNT(com.id) AS total_f_c_expire ,e_p.featuredcompaniesexpireindays 
                    FROM `#__js_job_companies` AS com
                    JOIN `#__js_job_employerpackages` AS e_p ON e_p.id=com.packageid 
                    WHERE DATE_ADD(com.startfeatureddate,INTERVAL e_p.featuredcompaniesexpireindays DAY) < CURDATE()
                    AND com.isfeaturedcompany=1 AND com.uid = " . $uid;
        $db->setQuery($query);
        $result = $db->loadObject();
        $totalfeaturedcompaniesexpire = $result->total_f_c_expire;

        //gold companies
        $query = "SELECT COUNT(id) FROM #__js_job_companies WHERE isgoldcompany=1 AND uid = " . $uid;
        $db->setQuery($query);
        $totalgoldcompanies = $db->loadResult();

        //Gold companies expire
        $query = "SELECT COUNT(com.id) AS total_g_c_expire ,e_p.goldcompaniesexpireindays 
                    FROM `#__js_job_companies` AS com
                    JOIN `#__js_job_employerpackages` AS e_p ON e_p.id=com.packageid 
                    WHERE DATE_ADD(com.startgolddate,INTERVAL e_p.goldcompaniesexpireindays DAY) < CURDATE()
                    AND com.isgoldcompany=1 AND com.uid = " . $uid;
        $db->setQuery($query);
        $result = $db->loadObject();
        $totalgoldcompaniesexpire = $result->total_g_c_expire;

        //jobs
        $query = "SELECT COUNT(id) FROM #__js_job_jobs WHERE uid = " . $uid;
        $db->setQuery($query);
        $totaljobs = $db->loadResult();

        //publishedjob
        $query = "SELECT COUNT(id) FROM #__js_job_jobs WHERE uid = " . $uid . " AND stoppublishing > CURDATE() ";
        $db->setQuery($query);
        $publishedjob = $db->loadResult();

        //expiredjob
        $query = "SELECT COUNT(id) FROM #__js_job_jobs WHERE uid = " . $uid . " AND stoppublishing < CURDATE() ";
        $db->setQuery($query);
        $expiredjob = $db->loadResult();


        //gold jobs
        $query = "SELECT COUNT(id) FROM #__js_job_jobs WHERE isgoldjob=1 AND uid = " . $uid;
        $db->setQuery($query);
        $totalgoldjobs = $db->loadResult();

        //publishedgoldjob
        $query = "SELECT COUNT(id) FROM #__js_job_jobs WHERE uid = " . $uid . " AND isgoldjob=1 AND stoppublishing > CURDATE() ";
        $db->setQuery($query);
        $publishedgoldjob = $db->loadResult();

        //expiregoldjob
        $query = "SELECT COUNT(id) FROM #__js_job_jobs WHERE uid = " . $uid . " AND isgoldjob=1 AND stoppublishing < CURDATE() ";
        $db->setQuery($query);
        $expiregoldjob = $db->loadResult();

        //featured jobs
        $query = "SELECT COUNT(id) FROM #__js_job_jobs WHERE isfeaturedjob=1 AND uid = " . $uid;
        $db->setQuery($query);
        $totalfeaturedjobs = $db->loadResult();

        //publishedfeaturedjob
        $query = "SELECT COUNT(id) FROM #__js_job_jobs WHERE isfeaturedjob=1 AND uid = " . $uid . " AND isfeaturedjob=1 AND stoppublishing > CURDATE() ";
        $db->setQuery($query);
        $publishedfeaturedjob = $db->loadResult();

        //expirefeaturedjob
        $query = "SELECT COUNT(id) FROM #__js_job_jobs WHERE isfeaturedjob=1 AND uid = " . $uid . " AND isfeaturedjob=1 AND stoppublishing < CURDATE() ";
        $db->setQuery($query);
        $expirefeaturedjob = $db->loadResult();


        if ($companiesunlimited == 0)
            $results[0] = $companiesallow;
        elseif ($companiesunlimited == 1)
            $results[0] = -1;
        $results[1] = $totalcompanies;

        if ($unlimitedjobs == 0)
            $results[2] = $jobsallow;
        elseif ($unlimitedjobs == 1)
            $results[2] = -1;
        $results[3] = $totaljobs;
        $results[14] = $publishedjob;
        $results[15] = $expiredjob;

        if ($unlimitedfeaturedcompaines == 0)
            $results[4] = $featuredcompainesallow;
        elseif ($unlimitedfeaturedcompaines == 1)
            $results[4] = -1;
        $results[5] = $totalfeaturedcompanies;

        if ($unlimitedgoldcompanies == 0)
            $results[6] = $goldcompaniesallow;
        elseif ($unlimitedgoldcompanies == 1)
            $results[6] = -1;
        $results[7] = $totalgoldcompanies;

        if ($unlimitedgoldjobs == 0)
            $results[8] = $goldjobsallow;
        elseif ($unlimitedgoldjobs == 1)
            $results[8] = -1;
        $results[9] = $totalgoldjobs;
        $results[16] = $publishedgoldjob;
        $results[17] = $expiregoldjob;

        if ($unlimitedfeaturedjobs == 0)
            $results[10] = $featuredjobsallow;
        elseif ($unlimitedfeaturedjobs == 1)
            $results[10] = -1;
        $results[11] = $totalfeaturedjobs;
        $results[18] = $publishedfeaturedjob;
        $results[19] = $expirefeaturedjob;

        $results[20] = $ispackagerequired;
        $results[21] = $totalgoldcompaniesexpire;
        $results[22] = $totalfeaturedcompaniesexpire;
        return $results;
    }

    function &getMyCompanies($u_id, $limit, $limitstart) {
        $result = array();
        $db = &$this->getDBO();

        if (is_numeric($u_id) == false)
            return false;
        if (($u_id == 0) || ($u_id == ''))
            return false;
        $query = "SELECT count(company.id)
                        FROM `#__js_job_companies` AS company
                        WHERE company.uid = " . $u_id;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT company.*, cat.cat_title,CONCAT(company.alias,'-',company.id) AS aliasid,CONCAT(company.alias,'-',company.serverid) AS saliasid, ";

        $query .= " company.isgoldcompany AS isgold,company.isfeaturedcompany AS isfeatured		
			FROM `#__js_job_companies` AS company
			JOIN `#__js_job_categories` AS cat ON company.category = cat.id
			WHERE company.uid = " . $u_id;

        $db->setQuery($query, $limitstart, $limit);
        $result[0] = $db->loadObjectList();
        $result[1] = $total;

        return $result;
    }

    function &getMyDepartments($u_id, $limit, $limitstart) {
        $result = array();
        $db = &$this->getDBO();

        if (is_numeric($u_id) == false)
            return false;
        if (($u_id == 0) || ($u_id == ''))
            return false;
        $query = "SELECT count(department.id) 
                        FROM `#__js_job_departments` AS department
                        JOIN `#__js_job_companies` AS company ON company.id = department.companyid
                        WHERE department.uid = " . $u_id;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT department.*, company.name as companyname
                ,CONCAT(department.alias,'-',department.id) AS aliasid
			FROM `#__js_job_departments` AS department 
			JOIN `#__js_job_companies` AS company ON company.id = department.companyid
			WHERE department.uid = " . $u_id;
        $db->setQuery($query, $limitstart, $limit);
        $result[0] = $db->loadObjectList();
        $result[1] = $total;

        return $result;
    }

    function &getCompanybyId($companyid) {
        $db = &$this->getDBO();
        if (is_numeric($companyid) == false)
            return false;
        if ($this->_client_auth_key != "") {
            $fortask = "getcompanybyid";
            $jsjobsharingobject = new JSJobsModelJob_Sharing;
            $data['companyid'] = $companyid;
            $data['authkey'] = $this->_client_auth_key;
            $data['siteurl'] = $this->_siteurl;
            $encodedata = json_encode($data);
            $return_server_value = $jsjobsharingobject->serverTask($encodedata, $fortask);
            if (isset($return_server_value['companybyid']) AND $return_server_value['companybyid'] == -1) { // auth fail 
                $logarray['uid'] = $this->_uid;
                $logarray['referenceid'] = $return_server_value['referenceid'];
                $logarray['eventtype'] = $return_server_value['eventtype'];
                $logarray['message'] = $return_server_value['message'];
                $logarray['event'] = "Company View";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jsjobsharingobject->write_JobSharingLog($logarray);
                $result[0] = (object) array();
                $result[2] = (object) array();
                $fieldfor = 1;
            } else {
                $result[0] = (object) $return_server_value[0];
                if ($result[0]->uid == 0 || $result[0]->uid == '')
                    $fieldfor = 11;
                else
                    $fieldfor = 1;
                $result[2] = (object) $return_server_value[1];
            }
        }else {
            $query = "SELECT company.*, cat.cat_title ,CONCAT(company.alias,'-',company.id) AS aliasid
			FROM `#__js_job_companies` AS company
			JOIN `#__js_job_categories` AS cat ON company.category = cat.id
			WHERE  company.id = " . $companyid;
            $db->setQuery($query);
            $result[0] = $db->loadObject();
            $result[0]->multicity = $this->common_model->getMultiCityDataForView($companyid, 2);


            $query = "UPDATE `#__js_job_companies` SET hits = hits+1 WHERE id = " . $companyid;
            $db->setQuery($query);
            if (!$db->query()) {
                //return false;
            }
            if ($result[0]->uid == 0 || $result[0]->uid == '')
                $fieldfor = 11;
            else
                $fieldfor = 1;
            $result[2] = $this->common_model->getUserFieldsForView($fieldfor, $companyid); // company fields, id
        }
        $result[3] = $this->common_model->getFieldsOrdering($fieldfor); // company fields
        return $result;
    }

    function &getShortListCandidate($jobid, $sortby, $limit, $limitstart) {
        $db = &$this->getDBO();
        if (is_numeric($jobid) == false)
            return false;
        $result = array();
        $query = "SELECT COUNT(job.id)
		FROM `#__js_job_jobs` AS job 
		JOIN `#__js_job_shortlistcandidates` AS candidate ON job.id=candidate.jobid  
		JOIN `#__js_job_resume` AS resume ON candidate.cvid = resume.id 
		WHERE  job.id=" . $jobid;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;
        $query = "SELECT apply.comments,apply.id AS jobapplyid ,job.id,job.agefrom,job.ageto, 
                    cat.cat_title ,apply.apply_date, apply.resumeview, jobtype.title AS jobtypetitle,
                    app.iamavailable, app.id AS appid, app.first_name, app.last_name, app.email_address, 
                    app.jobtype,app.gender	,app.total_experience, app.jobsalaryrange,rating.id AS ratingid, rating.rating
                    ,app.address_city, app.address_county, app.address_state
                    ,country.name AS countryname,state.name AS statename
                    ,county.name AS countyname,city.name AS cityname
                    , salary.rangestart, salary.rangeend,education.title AS educationtitle
                    FROM `#__js_job_jobs` AS job
                    JOIN `#__js_job_shortlistcandidates` AS candidate ON job.id=candidate.jobid
                    JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                    JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
                    JOIN `#__js_job_jobapply` AS apply  ON candidate.cvid = apply.cvid AND candidate.jobid = apply.jobid
                    JOIN `#__js_job_resume` AS app ON apply.cvid = app.id
                    LEFT JOIN  `#__js_job_resumerating` AS rating ON (app.id=rating.resumeid AND apply.jobid=rating.jobid)
                    LEFT JOIN `#__js_job_heighesteducation` AS  education  ON app.heighestfinisheducation=education.id
                    LEFT JOIN  `#__js_job_salaryrange` AS salary	ON	app.jobsalaryrange=salary.id
                    LEFT JOIN `#__js_job_countries` AS country ON app.address_country  = country.id
                    LEFT JOIN `#__js_job_states` AS state ON app.address_state = state.id
                    LEFT JOIN `#__js_job_counties` AS county ON app.address_county  = county.id
                    LEFT JOIN `#__js_job_cities` AS city ON app.address_city = city.id
                    WHERE apply.jobid = " . $jobid . " ORDER BY  " . $sortby;
        $db->setQuery($query, $limitstart, $limit);
        $this->_applications = $db->loadObjectList();

        $result[0] = $this->_applications;
        $result[1] = $total;

        return $result;
    }

    function &getMessagesbyJobs($uid, $limit, $limitstart) {
        $result = array();
        $db = &$this->getDBO();

        if (is_numeric($uid) == false)
            return false;
        if (($uid == 0) || ($uid == ''))
            return false;
        $total = 0;
        if ($this->_client_auth_key != "") {
            $fortask = "getmessagesbyjobs";
            $jsjobsharingobject = new JSJobsModelJob_Sharing;
            $data['uid'] = $uid;
            $data['limit'] = $limit;
            $data['limitstart'] = $limitstart;
            $data['authkey'] = $this->_client_auth_key;
            $data['siteurl'] = $this->_siteurl;
            $encodedata = json_encode($data);
            $return_server_value = $jsjobsharingobject->serverTask($encodedata, $fortask);
            if (isset($return_server_value['messageemployer']) AND $return_server_value['messageemployer'] == -1) { // auth fail 
                $logarray['uid'] = $this->_uid;
                $logarray['referenceid'] = $return_server_value['referenceid'];
                $logarray['eventtype'] = $return_server_value['eventtype'];
                $logarray['message'] = $return_server_value['message'];
                $logarray['event'] = "Message By Employer";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jsjobsharingobject->write_JobSharingLog($logarray);
                $result[0] = (object) array();
                $result[1] = 0;
            } else {
                $parse_data = array();
                foreach ($return_server_value['empmessages'] AS $rel_data) {
                    $parse_data[] = (object) $rel_data;
                }
                $result[0] = $parse_data;
                $result[1] = $return_server_value['total'];
            }
        } else {
            $query = "SELECT message.id
                        FROM `#__js_job_messages` AS message
                        JOIN `#__js_job_jobs` AS job ON job.id = message.jobid
                        JOIN `#__js_job_companies` AS company ON company.id = job.companyid
                        WHERE message.employerid = " . $uid . "
                        GROUP BY message.jobid
                        ";
            $db->setQuery($query);
            $totobj = $db->loadObjectList();
            foreach ($totobj as $obj)
                $total++;

            if ($total <= $limitstart)
                $limitstart = 0;

            $query = "SELECT message.id, message.jobid, job.title, job.created, company.id as companyid, company.name as companyname
                        ,(SELECT COUNT(id) FROM `#__js_job_messages` WHERE employerid = " . $uid . " AND sendby != " . $uid . " AND isread = 0 AND jobid=message.jobid) as unread
                        ,CONCAT(company.alias,'-',companyid) AS companyaliasid
                        ,CONCAT(job.alias,'-',job.id) AS jobaliasid
                        FROM `#__js_job_messages` AS message
                        JOIN `#__js_job_jobs` AS job ON job.id = message.jobid
                        JOIN `#__js_job_companies` AS company ON company.id = job.companyid
                        WHERE message.employerid = " . $uid . "
                        GROUP BY message.jobid
                        ORDER BY message.created DESC";

            $db->setQuery($query, $limitstart, $limit);
            $messages = $db->loadObjectList();
            $result[0] = $messages;
            $result[1] = $total;
        }
        return $result;
    }

    function &getMessagesbyJob($uid, $jobid, $limit, $limitstart) {
        $result = array();
        $db = &$this->getDBO();

        if (is_numeric($uid) == false)
            return false;
        if (($uid == 0) || ($uid == ''))
            return false;
        if ($this->_client_auth_key != "") {
            $fortask = "getmessagesbyjob";
            $jsjobsharingobject = new JSJobsModelJob_Sharing;
            $data['uid'] = $uid;
            $data['jobid'] = $jobid;
            $data['limit'] = $limit;
            $data['limitstart'] = $limitstart;
            $data['authkey'] = $this->_client_auth_key;
            $data['siteurl'] = $this->_siteurl;
            $encodedata = json_encode($data);
            $return_server_value = $jsjobsharingobject->serverTask($encodedata, $fortask);
            if (isset($return_server_value['messagebyjobs']) AND $return_server_value['messagebyjobs'] == -1) { // auth fail 
                $logarray['uid'] = $this->_uid;
                $logarray['referenceid'] = $return_server_value['referenceid'];
                $logarray['eventtype'] = $return_server_value['eventtype'];
                $logarray['message'] = $return_server_value['message'];
                $logarray['event'] = "Messages By Job";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jsjobsharingobject->write_JobSharingLog($logarray);
                $result[0] = (object) array();
                $result[1] = 0;
            } else {
                $parse_data = array();
                foreach ($return_server_value['messages'] AS $rel_data) {
                    $parse_data[] = (object) $rel_data;
                }
                $result[0] = $parse_data;
                $result[1] = $return_server_value['total'];
            }
        } else {
            $query = "SELECT count(message.id)
                        FROM `#__js_job_messages` AS message
                        JOIN `#__js_job_jobs` AS job ON job.id = message.jobid
                        JOIN `#__js_job_resume` AS resume ON resume.id = message.resumeid
                        WHERE message.employerid = " . $uid . " AND message.jobid = " . $jobid . "
                        GROUP BY message.jobseekerid";
            $db->setQuery($query);

            $msgs = $db->loadObjectList();
            $total = 0;
            foreach ($msgs AS $msg)
                $total++;
            if ($total <= $limitstart)
                $limitstart = 0;

            $query = "SELECT message.id, message.jobid, message.resumeid, job.title, job.created, resume.id as resumeidid, resume.application_title, resume.first_name, resume.middle_name, resume.last_name
                        ,(SELECT COUNT(id) FROM `#__js_job_messages` WHERE employerid=" . $uid . " AND jobid = " . $jobid . " AND sendby != " . $uid . " AND isread = 0 AND resume.id = resumeid) as unread
                        ,CONCAT(resume.alias,'-',resume.id) AS resumealiasid
                        FROM `#__js_job_messages` AS message
                        JOIN `#__js_job_jobs` AS job ON job.id = message.jobid
                        JOIN `#__js_job_resume` AS resume ON resume.id = message.resumeid
                        WHERE message.employerid = " . $uid . " AND message.jobid = " . $jobid . "
                        GROUP BY message.jobseekerid
                        ORDER BY message.created DESC ";

            $db->setQuery($query, $limitstart, $limit);
            $messages = $db->loadObjectList();
            $result[0] = $messages;
            $result[1] = $total;
        }


        return $result;
    }

    function getMessagesbyJobResume($uid, $jobid, $resumeid, $limit, $limitstart) {
        $result = array();
        $db = &$this->getDBO();
        if (is_numeric($uid) == false)
            return false;
        if (($uid == 0) || ($uid == ''))
            return false;
        if (is_numeric($jobid) == false)
            return false;
        if (is_numeric($resumeid) == false)
            return false;
        $listjobconfig = $this->common_model->getConfigByFor('listjob');

        if ($this->_client_auth_key != "") {

            $query = "SELECT id FROM #__js_job_resume 
			WHERE serverid = " . $resumeid;

            $db->setQuery($query);
            $result_jobid = $db->loadResult();
            $resumeid = $result_jobid;

            $query = "SELECT id FROM #__js_job_jobs 
			WHERE serverid = " . $jobid;

            $db->setQuery($query);
            $result_resumeid = $db->loadResult();
            $jobid = $result_resumeid;
        }

        $query = "SELECT count(message.id)
                    FROM `#__js_job_messages` AS message
                    WHERE message.status = 1 AND message.jobid =" . $jobid . " AND message.resumeid = " . $resumeid;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($this->_client_auth_key != "") {
            $limitstart = $limitstart;
        } else {
            if ($total <= $limitstart)
                $limitstart = 0;
        }

        $query = "SELECT message.*, job.title, resume.application_title, resume.first_name, resume.middle_name, resume.last_name
                        FROM `#__js_job_messages` AS message
                        JOIN `#__js_job_jobs` AS job ON job.id = message.jobid
                        JOIN `#__js_job_resume` AS resume ON resume.id = message.resumeid
                        WHERE message.status = 1 AND message.jobid =" . $jobid . " AND message.resumeid = " . $resumeid . " ORDER BY  message.created DESC";

        $db->setQuery($query, $limitstart, $limit);
        $messages = $db->loadObjectList();
        if ($total > 0)
            $canadd = true;
        else
            $canadd = $this->canAddMessage($uid);
        if ($canadd) {
            if ($this->_client_auth_key != "") {
                $query = "select job.serverid AS serverid 
                            From #__js_job_jobs AS job
                            WHERE job.id=" . $jobid;
                $db->setQuery($query);
                $job_serverid = $db->loadResult();
                $jobid = $job_serverid;

                $query = "select resume.serverid AS serverid 
                            From #__js_job_resume AS resume
                            WHERE resume.id=" . $resumeid;
                $db->setQuery($query);
                $resume_serverid = $db->loadResult();
                $resumeid = $resume_serverid;

                $fortask = "getmessagesbyjobresume";
                $jsjobsharingobject = new JSJobsModelJob_Sharing;
                $data['uid'] = $uid;
                $data['jobid'] = $jobid;
                $data['resumeid'] = $resumeid;
                $data['limitstart'] = $limitstart;
                $data['limit'] = $limit;
                $data['authkey'] = $this->_client_auth_key;
                $data['siteurl'] = $this->_siteurl;
                $encodedata = json_encode($data);
                $return_server_value = $jsjobsharingobject->serverTask($encodedata, $fortask);
                if (isset($return_server_value['messagebyjobresume']) AND $return_server_value['messagebyjobresume'] == -1) { // auth fail 
                    $logarray['uid'] = $this->_uid;
                    $logarray['referenceid'] = $return_server_value['referenceid'];
                    $logarray['eventtype'] = $return_server_value['eventtype'];
                    $logarray['message'] = $return_server_value['message'];
                    $logarray['event'] = "Message By jobs Resume";
                    $logarray['messagetype'] = "Error";
                    $logarray['datetime'] = date('Y-m-d H:i:s');
                    $jsjobsharingobject->write_JobSharingLog($logarray);
                } else {
                    $summary = array();
                    $parse_data = array();
                    foreach ($return_server_value['messages'] AS $rel_data) {
                        $parse_data[] = (object) $rel_data;
                    }
                    $messages = $parse_data;
                    $total = $return_server_value['total'];
                    if (isset($return_server_value['summery']['summery']))
                        $summary = (object) $return_server_value['summery']['summery'];
                }
            }else {
                $query = "SELECT job.id as jobid, job.uid as employerid, job.title, resume.id as resumeid, resume.uid as jobseekerid, resume.application_title, resume.first_name, resume.middle_name, resume.last_name
                            FROM `#__js_job_jobs` AS job
                            JOIN `#__js_job_resume` AS resume ON resume.id = " . $resumeid . "
                            WHERE job.id = " . $jobid;
                $db->setQuery($query);
                $summary = $db->loadObject();
            }
        }
        $result[0] = $messages;
        $result[1] = $total;
        $result[2] = $canadd;
        if (isset($summary))
            $result[3] = $summary;

        return $result;
    }

    function &getDepartmentbyId($departmentid) {
        $db = &$this->getDBO();
        if (is_numeric($departmentid) == false)
            return false;
        $query = "SELECT department.*,company.name as companyname 
                ,CONCAT(company.alias,'-',company.id) AS companyaliasid
                ,CONCAT(company.alias,'-',company.serverid) AS scompanyaliasid
		FROM `#__js_job_departments` AS department
		JOIN `#__js_job_companies` AS company ON company.id = department.companyid
		WHERE  department.id = " . $departmentid;
        $db->setQuery($query);
        $department = $db->loadObject();


        return $department;
    }

    function &getDepartmentByIdForForm($departmentid, $uid) {
        $db = &$this->getDBO();
        if (is_numeric($uid) == false)
            return false;
        if (($departmentid != '') && ($departmentid != 0)) {
            if (is_numeric($departmentid) == false)
                return false;
            $query = "SELECT department.*
			FROM `#__js_job_departments` AS department 
			WHERE department.id = " . $departmentid;

            $db->setQuery($query);
            $department = $db->loadObject();
        }
        $companies = $this->common_model->getCompanies($uid);

        if (isset($department)) {
            $lists['companies'] = JHTML::_('select.genericList', $companies, 'companyid', 'class="inputbox required" ' . '', 'value', 'text', $department->companyid);
        } else {
            $lists['companies'] = JHTML::_('select.genericList', $companies, 'companyid', 'class="inputbox required" ' . '', 'value', 'text', '');
        }
        if (isset($department))
            $result[0] = $department;
        $result[1] = $lists;

        return $result;
    }

    function &getCompanyInfoById($companyid) { // this may not use
        if (is_numeric($companyid) == false)
            return false;
        $db = &$this->getDBO();
        $query = "SELECT company.name
		FROM `#__js_job_companies` AS company
		WHERE company.id = " . $companyid;
        $db->setQuery($query);
        $company = $db->loadObject();
        $query = "SELECT count(featuredjobs.id)
				
		FROM `#__js_job_featuredjobs` AS featuredjobs
		JOIN `#__js_job_jobs` AS job ON featuredjobs.jobid = job.id
		JOIN `#__js_job_companies` AS company ON job.companyid = company.id
		LEFT JOIN `#__js_job_countries` AS country ON job.country = country.id
		LEFT JOIN `#__js_job_cities` AS city ON job.city = city.id
		WHERE  job.companyid = " . $companyid;
        $db->setQuery($query);
        $jobs = $db->loadResult();

        $query = "SELECT featuredjobs.*,job.created as jobcreated , job.title,company.name as companyname, country.name AS countryname, city.name AS cityname
				
		FROM `#__js_job_featuredjobs` AS featuredjobs
		JOIN `#__js_job_jobs` AS job ON featuredjobs.jobid = job.id
		JOIN `#__js_job_companies` AS company ON job.companyid = company.id
		LEFT JOIN `#__js_job_countries` AS country ON job.country = country.id
		LEFT JOIN `#__js_job_cities` AS city ON job.city = city.id
		WHERE  job.companyid = " . $companyid;
        $db->setQuery($query);
        $info = $db->loadObjectList();
        $result[0] = $info;
        $result[1] = $jobs;
        $result[2] = $company;
        return $result;
    }

    function &getJobDetails($jobid) { // this may not use
        if (is_numeric($jobid) == false)
            return false;

        $db = &$this->getDBO();

        $query = "SELECT job.*, cat.cat_title , company.name as companyname, jobtype.title AS jobtypetitle
                ,  shift.title as shifttitle
                , salaryfrom.rangestart AS salaryfrom, salaryto.rangestart AS salaryto
                , salarytype.title AS salarytype
                ,mineducation.title AS mineducationtitle
                , minexperience.title AS minexperiencetitle,agefrom.title AS agefrom,ageto.title AS ageto
                , country.name AS countryname, city.name AS cityname,careerlevel.title AS careerleveltitle
		FROM `#__js_job_jobs` AS job
		JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
		JOIN `#__js_job_companies` AS company ON job.companyid = company.id
		JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
		LEFT JOIN `#__js_job_salaryrange` AS salaryfrom ON job.salaryrangefrom = salaryfrom.id
		LEFT JOIN `#__js_job_salaryrange` AS salaryto ON job.salaryrangeto = salaryto.id
		LEFT JOIN `#__js_job_salaryrangetypes` AS salarytype ON job.salaryrangetype = salarytype.id
		LEFT JOIN `#__js_job_heighesteducation` AS mineducation ON job.mineducationrange = mineducation.id
		LEFT JOIN `#__js_job_experiences` AS minexperience ON job.minexperiencerange = minexperience.id
		LEFT JOIN `#__js_job_shifts` AS shift ON job.shift = shift.id
		LEFT JOIN `#__js_job_countries` AS country ON job.country = country.id
		LEFT JOIN `#__js_job_cities` AS city ON job.city = city.id
		LEFT JOIN `#__js_job_ages` AS ageto ON job.ageto = ageto.id
		LEFT JOIN `#__js_job_ages` AS agefrom ON job.agefrom = agefrom.id
		LEFT JOIN `#__js_job_careerlevels` AS careerlevel ON job.careerlevel = careerlevel.id
		WHERE  job.id = " . $jobid;
        $db->setQuery($query);
        $details = $db->loadObject();

        return $details;
    }

    function &getEmployerPackages($limit, $limitstart) {
        $db = &$this->getDBO();
        $result = array();

        $query = "SELECT COUNT(id) FROM `#__js_job_employerpackages` WHERE status = 1";
        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT package.*,cur.symbol
				FROM `#__js_job_employerpackages` AS package 
				LEFT JOIN `#__js_job_currencies` AS cur ON cur.id=package.currencyid
		WHERE package.status = 1";
        $db->setQuery($query, $limitstart, $limit);
        $packages = $db->loadObjectList();

        $result[0] = $packages;
        $result[1] = $total;

        return $result;
    }

    function &getEmployerPackageById($packageid, $uid) {
        if (is_numeric($packageid) == false)
            return false;
        if ($uid)
            if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
                return false;
        $db = &$this->getDBO();
        $result = array();
        $query = "SELECT package.* ,cur.symbol
			FROM `#__js_job_employerpackages` AS package 
			LEFT JOIN `#__js_job_currencies` AS cur ON cur.id=package.currencyid
			WHERE package.id = " . $packageid;
        $db->setQuery($query);
        $package = $db->loadObject();
        $lists = '';
        $payment_multicompanies = '';
        if (!isset($this->_config)) {
            $this->_config = $this->common_model->getConfig('payment');
        }
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'payment_multicompanies')
                $payment_multicompanies = $conf->configvalue;
        }
        if ($payment_multicompanies == '0') {
            $companies = $this->common_model->getCompanies($uid);
            $lists['companies'] = JHTML::_('select.genericList', $companies, 'companyid', 'class="inputbox required" ' . '', 'value', 'text', '');
        }
        $result[0] = $package;
        $result[1] = $payment_multicompanies;
        $result[2] = $lists;


        return $result;
    }

    function &getEmployerPurchaseHistory($uid, $limit, $limitstart) {
        if ($uid)
            if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
                return false;

        $db = &$this->getDBO();
        $result = array();

        $query = "SELECT COUNT(id) FROM `#__js_job_paymenthistory` WHERE uid = " . $uid . " AND status = 1 AND packagefor=1";
        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT purchase.paidamount, purchase.transactionverified,purchase.created,cur.symbol
                            ,package.id, package.title,package.companiesallow, package.jobsallow, package.packageexpireindays
                            FROM `#__js_job_paymenthistory` AS purchase
                            JOIN `#__js_job_employerpackages` AS package ON package.id = purchase.packageid
                            LEFT JOIN `#__js_job_currencies` AS cur ON package.currencyid = cur.id
                            WHERE purchase.uid = " . $uid . " AND purchase.status = 1 AND purchase.packagefor=1 ORDER BY purchase.created DESC";
        $db->setQuery($query, $limitstart, $limit);
        $packages = $db->loadObjectList();

        $result[0] = $packages;
        $result[1] = $total;

        return $result;
    }

    //Payment system end
    function &getMyResumeSearchesbyUid($u_id, $limit, $limitstart) {
        $db = &$this->getDBO();
        if ((is_numeric($u_id) == false) || ($u_id == 0) || ($u_id == ''))
            return false;
        $result = array();
        $query = "SELECT COUNT(id) FROM `#__js_job_resumesearches` WHERE uid  = " . $u_id;
        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT search.* 
					FROM `#__js_job_resumesearches` AS search
					WHERE search.uid  = " . $u_id;
        $db->setQuery($query);
        $db->setQuery($query, $limitstart, $limit);

        $result[0] = $db->loadObjectList();
        $result[1] = $total;

        return $result;
    }

    function &getResumeSearchebyId($id) {
        $db = &$this->getDBO();
        if (is_numeric($id) == false)
            return false;
        $query = "SELECT search.* 
                    FROM `#__js_job_resumesearches` AS search
                    WHERE search.id  = " . $id;
        $db->setQuery($query);
        return $db->loadObject();
    }

    function getJobAppliedResumeSearchOption($uid) {
        $gender = array(
            '0' => array('value' => '', 'text' => JText::_('JS_SEARCH_ALL')),
            '1' => array('value' => 1, 'text' => JText::_('JS_MALE')),
            '2' => array('value' => 2, 'text' => JText::_('JS_FEMALE')),);

        $nationality = $this->common_model->getCountries(JText::_('JS_SEARCH_ALL'));
        $job_type = $this->common_model->getJobType(JText::_('JS_SEARCH_ALL'));
        $heighesteducation = $this->common_model->getHeighestEducation(JText::_('JS_SEARCH_ALL'));
        $job_categories = $this->common_model->getCategories(JText::_('JS_SEARCH_ALL'));
        $job_subcategories = $this->common_model->getSubCategoriesforCombo($job_categories[1]['value'], JText::_('JS_SEARCH_ALL'), '');
        $job_salaryrange = $this->common_model->getJobSalaryRange(JText::_('JS_SEARCH_ALL'), '');
        $job_currency = $this->getCurrencyResumeApplied(JText::_('JS_SELECT'));

        $searchoptions['nationality'] = JHTML::_('select.genericList', $nationality, 'nationality', 'class="inputbox" ' . '', 'value', 'text', '');
        $searchoptions['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'jobcategory', 'class="inputbox" ' . 'onChange="fj_getsubcategories(\'fj_subcategory\', this.value)"', 'value', 'text', '');
        $searchoptions['jobsubcategory'] = JHTML::_('select.genericList', $job_subcategories, 'jobsubcategory', 'class="inputbox" ' . '', 'value', 'text', '');
        $searchoptions['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'jobsalaryrange', 'class="inputbox" ' . '', 'value', 'text', '');
        $searchoptions['jobtype'] = JHTML::_('select.genericList', $job_type, 'jobtype', 'class="inputbox" ' . '', 'value', 'text', '');
        $searchoptions['heighestfinisheducation'] = JHTML::_('select.genericList', $heighesteducation, 'heighestfinisheducation', 'class="inputbox" ' . '', 'value', 'text', '');
        $searchoptions['gender'] = JHTML::_('select.genericList', $gender, 'gender', 'class="inputbox" ' . '', 'value', 'text', '');
        $searchoptions['currency'] = JHTML::_('select.genericList', $job_currency, 'currency', 'class="inputbox" ' . '', 'value', 'text', '');
        $result = array();
        $result[0] = $searchoptions;
        return $result;
    }

    function getCurrencyResumeApplied($title) {
        $db = & JFactory :: getDBO();
        $q = "SELECT * FROM `#__js_job_currencies` WHERE status = 1";
        if ($this->_client_auth_key != "")
            $q.=" AND serverid!='' AND serverid!=0";
        $db->setQuery($q);
        $allcurrency = $db->loadObjectList();
        $combobox = array();
        if ($title)
            $combobox[] = array('value' => JText::_(''), 'text' => $title);
        if (!empty($allcurrency)) {
            foreach ($allcurrency as $currency) {
                $combobox[] = array('value' => $currency->id, 'text' => JText::_($currency->symbol));
            }
        }
        return $combobox;
    }

    function &getResumeSearchOptions($uid) {
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == '')) {
            $rv = false;
            return $rv;
        }
        $db = &$this->getDBO();

        if (!isset($this->_config)) {
            $this->_config = $this->common_model->getConfig('');
        }
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'newlisting_requiredpackage')
                $newlisting_required_package = $conf->configvalue;
        }
        if ($newlisting_required_package == 0) {
            $canview = 1;
        } else {
            $query = "SELECT package.resumesearch, package.packageexpireindays, payment.created
			FROM `#__js_job_employerpackages` AS package
			JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=1)
			WHERE payment.uid = " . $uid . "
			AND DATE_ADD(payment.created,INTERVAL package.packageexpireindays DAY) >= CURDATE()
			AND payment.transactionverified = 1 AND payment.status = 1";

            $db->setQuery($query);
            $jobs = $db->loadObjectList();
            //$unlimited = 0;
            $canview = 0;
            foreach ($jobs AS $job) {
                if ($job->resumesearch == 1) {
                    $canview = 1;
                    break;
                } else {
                    $canview = 0;
                }
            }
        }
        if ($canview == 1) {
            $searchresumeconfig = $this->common_model->getConfigByFor('searchresume');
            $gender = array(
                '0' => array('value' => '', 'text' => JText::_('JS_SEARCH_ALL')),
                '1' => array('value' => 1, 'text' => JText::_('JS_MALE')),
                '2' => array('value' => 2, 'text' => JText::_('JS_FEMALE')),);

            $nationality = $this->common_model->getCountries(JText::_('JS_SEARCH_ALL'));
            $job_type = $this->common_model->getJobType(JText::_('JS_SEARCH_ALL'));
            $heighesteducation = $this->common_model->getHeighestEducation(JText::_('JS_SEARCH_ALL'));
            $job_categories = $this->common_model->getCategories(JText::_('JS_SEARCH_ALL'));
            $job_subcategories = $this->common_model->getSubCategoriesforCombo($job_categories[1]['value'], JText::_('JS_SEARCH_ALL'), '');
            $job_salaryrange = $this->common_model->getJobSalaryRange(JText::_('JS_SEARCH_ALL'), '');
            $currencies = $this->common_model->getCurrency(JText::_('JS_SEARCH_ALL'));

            $searchoptions['nationality'] = JHTML::_('select.genericList', $nationality, 'nationality', 'class="inputbox" ' . '', 'value', 'text', '');
            $searchoptions['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'jobcategory', 'class="inputbox" ' . 'onChange="fj_getsubcategories(\'fj_subcategory\', this.value)"', 'value', 'text', '');
            $searchoptions['jobsubcategory'] = JHTML::_('select.genericList', $job_subcategories, 'jobsubcategory', 'class="inputbox" ' . '', 'value', 'text', '');
            $searchoptions['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'jobsalaryrange', 'class="inputbox" ' . '', 'value', 'text', '');
            $searchoptions['jobtype'] = JHTML::_('select.genericList', $job_type, 'jobtype', 'class="inputbox" ' . '', 'value', 'text', '');
            $searchoptions['heighestfinisheducation'] = JHTML::_('select.genericList', $heighesteducation, 'heighestfinisheducation', 'class="inputbox" ' . '', 'value', 'text', '');
            $searchoptions['gender'] = JHTML::_('select.genericList', $gender, 'gender', 'class="inputbox" ' . '', 'value', 'text', '');
            $searchoptions['currency'] = JHTML::_('select.genericList', $currencies, 'currency', 'class="inputbox" ' . '', 'value', 'text', '');
            $result = array();
            $result[0] = $searchoptions;
            $result[1] = $searchresumeconfig;
            $result[2] = $canview;
        } else {
            $result[2] = $canview;
        }
        return $result;
    }

    function getResumeByCategory($uid) {
        if (!is_numeric($uid))
            return false;
        $db = $this->getDbo();
        if (!isset($this->_config)) {
            $this->_config = $this->common_model->getConfig('');
        }
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'newlisting_requiredpackage')
                $newlisting_required_package = $conf->configvalue;
        }

        if ($newlisting_required_package == 0) {
            $cansearch = 1;
        } else {
            $query = "SELECT  package.resumesearch
                        FROM `#__js_job_employerpackages` AS package
                        JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=1)
                        WHERE payment.uid = " . $uid . "
                        AND DATE_ADD(payment.created,INTERVAL package.packageexpireindays DAY) >= CURDATE()
                        AND payment.transactionverified = 1 AND payment.status = 1";

            $db->setQuery($query);
            $results = $db->loadObjectList();
            $cansearch = 0;
            foreach ($results AS $result) {
                if ($result->resumesearch != -1) {
                    $cansearch += $result->resumesearch;
                }
            }
        }
        if ($cansearch != 0) {
            $query = "SELECT DISTINCT cat.id AS catid, cat.cat_title AS cattitle, 
                        (SELECT COUNT(id) FROM `#__js_job_resume` WHERE job_category = cat.id  AND status=1 AND searchable=1 ) AS total
                        ,CONCAT(cat.alias,'-',cat.id) AS aliasid
                        FROM `#__js_job_categories` AS cat
                        WHERE cat.isactive = 1";

            $db->setQuery($query);
            $result = $db->loadObjectList();
            return $result;
        }
        else
            return false;
    }

    function getMyJobs($u_id, $sortby, $limit, $limitstart, $vis_email, $jobid) {
        $result = array();
        $db = &$this->getDBO();

        if (is_numeric($u_id) == false)
            return false;
        if (($vis_email == '') || ($jobid == ''))
            if (($u_id == 0) || ($u_id == ''))
                return false; //check if not visitor
                $listjobconfig = $this->common_model->getConfigByFor('listjob');
        //visitor jobs
        if (isset($jobid) && ($jobid != '')) {// if the jobid and email address is valid or not
            $query = "SELECT job.companyid
                                FROM `#__js_job_jobs` AS job
                                WHERE job.jobid = " . $db->quote($jobid);

            $db->setQuery($query);
            $companyid = $db->loadResult();
            if (!$companyid)
                return false;
            $query = "SELECT count(company.id)
                                FROM `#__js_job_companies` AS company
                                WHERE company.id = " . $companyid;
            $db->setQuery($query);
            $company = $db->loadResult();
            if ($company == 0)
                return false; // means no company exist
        }
        if (isset($vis_email) && ($vis_email != '')) {
            $query = "SELECT count(job.id)
                                FROM `#__js_job_companies` AS company
                                JOIN `#__js_job_jobs` AS job ON job.companyid = company.id
                                WHERE company.contactemail = " . $db->quote($vis_email);
        } else {
            $query = "SELECT count(job.id)
                                FROM `#__js_job_jobs` AS job
                                WHERE job.uid = " . $u_id;
        }
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        if ((isset($vis_email) && isset($jobid)) && ($vis_email != '' && $jobid != '')) {
            $query = "SELECT job.*, cat.cat_title,'visitor' AS visitor,company.contactemail AS contactemail,salarytype.title AS salarytypetitle
                        , jobtype.title AS jobtypetitle, jobstatus.title AS jobstatustitle
                        , company.name AS companyname, company.url
                        , salaryfrom.rangestart, salaryto.rangeend, country.name AS countryname
                        ,job.isgoldjob AS isgold,job.isfeaturedjob AS isfeatured
                        ,currency.symbol ,salaryto.rangeend AS salaryto
                        ,CONCAT(company.alias,'-',company.id) AS companyaliasid
                        ,CONCAT(company.alias,'-',company.serverid) AS scompanyaliasid
                        FROM `#__js_job_companies` AS company
                        JOIN `#__js_job_jobs` AS job ON job.companyid = company.id
                        JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                        JOIN `#__js_job_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id
                        JOIN `#__js_job_categories` AS cat ON cat.id = job.jobcategory
                        LEFT JOIN `#__js_job_salaryrange` AS salaryfrom ON job.salaryrangefrom = salaryfrom.id
                        LEFT JOIN `#__js_job_salaryrange` AS salaryto ON job.salaryrangeto = salaryto.id
                        LEFT JOIN `#__js_job_salaryrangetypes` AS salarytype ON job.salaryrangetype = salarytype.id
                        LEFT JOIN `#__js_job_countries` AS country ON job.country = country.id
                        LEFT JOIN `#__js_job_currencies` AS currency ON currency.id = job.currencyid
                        WHERE company.contactemail = " . $db->quote($vis_email) . " ORDER BY  " . $sortby;
        } else {
            $query = "SELECT job.*, cat.cat_title
                            , jobtype.title AS jobtypetitle, jobstatus.title AS jobstatustitle,salarytype.title AS salarytypetitle
                            , company.name AS companyname, company.url
                            , salaryfrom.rangestart, salaryto.rangeend, country.name AS countryname
                            ,job.isgoldjob AS isgold,job.isfeaturedjob AS isfeatured
                            ,currency.symbol ,salaryto.rangeend AS salaryto
                            ,CONCAT(job.alias,'-',job.id) AS aliasid
                            ,CONCAT(job.alias,'-',job.serverid) AS saliasid
                            ,CONCAT(company.alias,'-',company.id) AS companyaliasid
                            ,CONCAT(company.alias,'-',company.serverid) AS scompanyaliasid
                            FROM `#__js_job_jobs` AS job
                            JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                            JOIN `#__js_job_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id
                            LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id
                            LEFT JOIN `#__js_job_salaryrange` AS salaryfrom ON job.salaryrangefrom = salaryfrom.id
                            LEFT JOIN `#__js_job_salaryrange` AS salaryto ON job.salaryrangeto = salaryto.id
                            LEFT JOIN `#__js_job_salaryrangetypes` AS salarytype ON job.salaryrangetype = salarytype.id
                            LEFT JOIN `#__js_job_countries` AS country ON job.country = country.id
                            LEFT JOIN `#__js_job_currencies` AS currency ON currency.id = job.currencyid
                            , `#__js_job_categories` AS cat
                            WHERE job.jobcategory = cat.id AND job.uid = " . $u_id . " ORDER BY  " . $sortby;
        }
        $db->setQuery($query, $limitstart, $limit);
        $this->_applications = $db->loadObjectList();
        foreach ($this->_applications AS $jobdata) {   // for multicity select 
            $multicitydata = $this->getMultiCityData($jobdata->id);
            if ($multicitydata != "")
                $jobdata->city = $multicitydata;
        }
        $result[0] = $this->_applications;
        $result[1] = $total;
        $result[2] = $listjobconfig;

        return $result;
    }

    function &getJobforForm($job_id, $uid, $vis_jobid, $visitor) {
        $db = &$this->getDBO();
        if ($visitor != 1) {
            $query = "SELECT count(company.id)
			FROM `#__js_job_companies` AS company  
			WHERE company.uid = " . $uid;

            $db->setQuery($query);
            $user_has_company = $db->loadResult();
            if ($user_has_company == 0) {
                $user_not_company = 3;
                return $user_not_company;
            }
        }
        if (is_numeric($uid) == false)
            return false;
        if (($job_id != '') && ($job_id != 0)) {
            if (is_numeric($job_id) == false)
                return false;
            $query = "SELECT job.*, cat.cat_title, salary.rangestart, salary.rangeend
			FROM `#__js_job_jobs` AS job 
			JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id 
			LEFT JOIN `#__js_job_salaryrange` AS salary ON job.jobsalaryrange = salary.id 
			LEFT JOIN `#__js_job_currencies` AS currency On currency.id = job.currencyid
			WHERE job.id = " . $job_id . " AND job.uid = " . $uid;
            $db->setQuery($query);
            $this->_job = $db->loadObject();
        }
        // Getting data for visitor job
        if (isset($vis_jobid) && ($vis_jobid != '')) {
            $query = "SELECT job.*, cat.cat_title, salary.rangestart, salary.rangeend
			FROM `#__js_job_jobs` AS job
			JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
			LEFT JOIN `#__js_job_salaryrange` AS salary ON job.jobsalaryrange = salary.id
			LEFT JOIN `#__js_job_currencies` AS currency On currency.id = job.currencyid
			WHERE job.jobid = " . $db->quote($vis_jobid);
            $db->setQuery($query);
            $this->_job = $db->loadObject();
        }
        $fieldOrdering = $this->common_model->getFieldsOrdering(2);

        foreach ($fieldOrdering AS $field) {
            switch ($field->field) {
                case "gender" : if ($field->required == 1)
                        $gernderreq = "required";
                    else
                        $gernderreq = ""; break;
            }
        }
        //$countries = $this->getCountries('');
        if (empty($visitor))
            $companies = $this->common_model->getCompanies($uid);

        $categories = $this->common_model->getCategories('');
        if (isset($this->_job)) {
            if (empty($visitor))
                $lists['companies'] = JHTML::_('select.genericList', $companies, 'companyid', 'class="inputbox required" ' . '', 'value', 'text', $this->_job->companyid);
            $lists['departments'] = JHTML::_('select.genericList', $this->getDepartmentsByCompanyId($this->_job->companyid, ''), 'departmentid', 'class="inputbox" ' . '', 'value', 'text', $this->_job->departmentid);
            $lists['jobcategory'] = JHTML::_('select.genericList', $categories, 'jobcategory', 'class="inputbox" ' . 'onChange="fj_getsubcategories(\'fj_subcategory\', this.value)"', 'value', 'text', $this->_job->jobcategory);
            $lists['subcategory'] = JHTML::_('select.genericList', $this->common_model->getSubCategoriesforCombo($this->_job->jobcategory, JText::_('JS_SUB_CATEGORY'), ''), 'subcategoryid', 'class="inputbox" ' . '', 'value', 'text', $this->_job->subcategoryid);
            $lists['jobtype'] = JHTML::_('select.genericList', $this->common_model->getJobType(''), 'jobtype', 'class="inputbox" ' . '', 'value', 'text', $this->_job->jobtype);
            $lists['jobstatus'] = JHTML::_('select.genericList', $this->common_model->getJobStatus(''), 'jobstatus', 'class="inputbox required" ' . '', 'value', 'text', $this->_job->jobstatus);
            $lists['heighesteducation'] = JHTML::_('select.genericList', $this->common_model->getHeighestEducation(''), 'heighestfinisheducation', 'class="inputbox" ' . '', 'value', 'text', $this->_job->heighestfinisheducation);
            $lists['shift'] = JHTML::_('select.genericList', $this->common_model->getShift(''), 'shift', 'class="inputbox" ' . '', 'value', 'text', $this->_job->shift);

            $lists['educationminimax'] = JHTML::_('select.genericList', $this->common_model->getMiniMax(''), 'educationminimax', 'class="inputbox" ' . '', 'value', 'text', $this->_job->educationminimax);
            $lists['education'] = JHTML::_('select.genericList', $this->common_model->getHeighestEducation(''), 'educationid', 'class="inputbox" ' . '', 'value', 'text', $this->_job->educationid);
            $lists['minimumeducationrange'] = JHTML::_('select.genericList', $this->common_model->getHeighestEducation(JText::_('JS_MINIMUM')), 'mineducationrange', 'class="inputbox" ' . '', 'value', 'text', $this->_job->mineducationrange);
            $lists['maximumeducationrange'] = JHTML::_('select.genericList', $this->common_model->getHeighestEducation(JText::_('JS_MAXIMUM')), 'maxeducationrange', 'class="inputbox" ' . '', 'value', 'text', $this->_job->maxeducationrange);

            $lists['salaryrangefrom'] = JHTML::_('select.genericList', $this->common_model->getJobSalaryRange(JText::_('JS_FROM'), 1), 'salaryrangefrom', 'class="inputbox validate-salaryrangefrom" ' . '', 'value', 'text', $this->_job->salaryrangefrom);
            $lists['salaryrangeto'] = JHTML::_('select.genericList', $this->common_model->getJobSalaryRange(JText::_('JS_TO'), 1), 'salaryrangeto', 'class="inputbox validate-salaryrangeto" ' . '', 'value', 'text', $this->_job->salaryrangeto);
            $lists['salaryrangetypes'] = JHTML::_('select.genericList', $this->common_model->getSalaryRangeTypes(''), 'salaryrangetype', 'class="inputbox" ' . '', 'value', 'text', $this->_job->salaryrangetype);

            $lists['agefrom'] = JHTML::_('select.genericList', $this->common_model->getAges(JText::_('JS_FROM')), 'agefrom', 'class="inputbox validate-checkagefrom" ' . '', 'value', 'text', $this->_job->agefrom);
            $lists['ageto'] = JHTML::_('select.genericList', $this->common_model->getAges(JText::_('JS_TO')), 'ageto', 'class="inputbox validate-checkageto" ' . '', 'value', 'text', $this->_job->ageto);
            $lists['experienceminimax'] = JHTML::_('select.genericList', $this->common_model->getMiniMax(''), 'experienceminimax', 'class="inputbox" ' . '', 'value', 'text', $this->_job->experienceminimax);
            $lists['experience'] = JHTML::_('select.genericList', $this->common_model->getExperiences(JText::_('JS_SELECT')), 'experienceid', 'class="inputbox" ' . '', 'value', 'text', $this->_job->experienceid);
            $lists['minimumexperiencerange'] = JHTML::_('select.genericList', $this->common_model->getExperiences(JText::_('JS_MINIMUM')), 'minexperiencerange', 'class="inputbox" ' . '', 'value', 'text', $this->_job->minexperiencerange);
            $lists['maximumexperiencerange'] = JHTML::_('select.genericList', $this->common_model->getExperiences(JText::_('JS_MAXIMUM')), 'maxexperiencerange', 'class="inputbox" ' . '', 'value', 'text', $this->_job->maxexperiencerange);

            $lists['gender'] = JHTML::_('select.genericList', $this->common_model->getGender(JText::_('JS_DOES_NOT_MATTER')), 'gender', 'class="inputbox ' . $gernderreq . '" ' . '', 'value', 'text', $this->_job->gender);
            $lists['careerlevel'] = JHTML::_('select.genericList', $this->common_model->getCareerLevel(JText::_('JS_SELECT')), 'careerlevel', 'class="inputbox" ' . '', 'value', 'text', $this->_job->careerlevel);
            $lists['workpermit'] = JHTML::_('select.genericList', $this->common_model->getCountries(JText::_('JS_SELECT')), 'workpermit', 'class="inputbox" ' . '', 'value', 'text', $this->_job->workpermit);
            $lists['requiredtravel'] = JHTML::_('select.genericList', $this->common_model->getRequiredTravel(JText::_('JS_SELECT')), 'requiredtravel', 'class="inputbox" ' . '', 'value', 'text', $this->_job->requiredtravel);
            $lists['sendemail'] = JHTML::_('select.genericList', $this->common_model->getSendEmail(), 'sendemail', 'class="inputbox" ' . '', 'value', 'text', $this->_job->sendemail);
            $lists['currencyid'] = JHTML::_('select.genericList', $this->common_model->getCurrency(), 'currencyid', 'class="inputbox" ' . '', 'value', 'text', $this->_job->currencyid);

            $multi_lists = $this->common_model->getMultiSelectEdit($this->_job->id, 1);
        }else {
            if (!isset($this->_config)) {
                $this->_config = $this->common_model->getConfig('');
            }
            if (isset($companies[0]))
                $lists['companies'] = JHTML::_('select.genericList', $companies, 'companyid', 'class="inputbox required" ' . 'onChange="getdepartments(\'department\', this.value)"' . '', 'value', 'text', '');
            if (isset($companies[0]['value']))
                $lists['departments'] = JHTML::_('select.genericList', $this->getDepartmentsByCompanyId($companies[0]['value'], ''), 'departmentid', 'class="inputbox" ' . '', 'value', 'text', '');

            $lists['jobcategory'] = JHTML::_('select.genericList', $categories, 'jobcategory', 'class="inputbox" ' . 'onChange="fj_getsubcategories(\'fj_subcategory\', this.value)"', 'value', 'text', '');
            $lists['subcategory'] = JHTML::_('select.genericList', $this->common_model->getSubCategoriesforCombo($categories[0]['value'], JText::_('JS_SUB_CATEGORY'), ''), 'subcategoryid', 'class="inputbox" ' . '', 'value', 'text', '');
            $lists['jobtype'] = JHTML::_('select.genericList', $this->common_model->getJobType(''), 'jobtype', 'class="inputbox" ' . '', 'value', 'text', '');
            $lists['jobstatus'] = JHTML::_('select.genericList', $this->common_model->getJobStatus(''), 'jobstatus', 'class="inputbox required" ' . '', 'value', 'text', '');
            $lists['shift'] = JHTML::_('select.genericList', $this->common_model->getShift(''), 'shift', 'class="inputbox" ' . '', 'value', 'text', '');

            $lists['educationminimax'] = JHTML::_('select.genericList', $this->common_model->getMiniMax(''), 'educationminimax', 'class="inputbox" ' . '', 'value', 'text', '');
            $lists['education'] = JHTML::_('select.genericList', $this->common_model->getHeighestEducation(''), 'educationid', 'class="inputbox" ' . '', 'value', 'text', '');
            $lists['minimumeducationrange'] = JHTML::_('select.genericList', $this->common_model->getHeighestEducation(JText::_('JS_MINIMUM')), 'mineducationrange', 'class="inputbox" ' . '', 'value', 'text', '');
            $lists['maximumeducationrange'] = JHTML::_('select.genericList', $this->common_model->getHeighestEducation(JText::_('JS_MAXIMUM')), 'maxeducationrange', 'class="inputbox" ' . '', 'value', 'text', '');


            $lists['salaryrangefrom'] = JHTML::_('select.genericList', $this->common_model->getJobSalaryRange(JText::_('JS_FROM'), 1), 'salaryrangefrom', 'class="inputbox validate-salaryrangefrom" ' . '', 'value', 'text', '');
            $lists['salaryrangeto'] = JHTML::_('select.genericList', $this->common_model->getJobSalaryRange(JText::_('JS_TO'), 1), 'salaryrangeto', 'class="inputbox validate-salaryrangeto" ' . '', 'value', 'text', '');
            $lists['salaryrangetypes'] = JHTML::_('select.genericList', $this->common_model->getSalaryRangeTypes(''), 'salaryrangetype', 'class="inputbox" ' . '', 'value', 'text', '2');

            $lists['agefrom'] = JHTML::_('select.genericList', $this->common_model->getAges(JText::_('JS_FROM')), 'agefrom', 'class="inputbox validate-checkagefrom" ' . '', 'value', 'text', '');
            $lists['ageto'] = JHTML::_('select.genericList', $this->common_model->getAges(JText::_('JS_TO')), 'ageto', 'class="inputbox validate-checkageto" ' . '', 'value', 'text', '');
            $lists['experienceminimax'] = JHTML::_('select.genericList', $this->common_model->getMiniMax(''), 'experienceminimax', 'class="inputbox" ' . '', 'value', 'text', '');
            $lists['experience'] = JHTML::_('select.genericList', $this->common_model->getExperiences(JText::_('JS_SELECT')), 'experienceid', 'class="inputbox" ' . '', 'value', 'text', '');
            $lists['minimumexperiencerange'] = JHTML::_('select.genericList', $this->common_model->getExperiences(JText::_('JS_MINIMUM')), 'minexperiencerange', 'class="inputbox" ' . '', 'value', 'text', '');
            $lists['maximumexperiencerange'] = JHTML::_('select.genericList', $this->common_model->getExperiences(JText::_('JS_MAXIMUM')), 'maxexperiencerange', 'class="inputbox" ' . '', 'value', 'text', '');

            $lists['gender'] = JHTML::_('select.genericList', $this->common_model->getGender(JText::_('JS_DOES_NOT_MATTER')), 'gender', 'class="inputbox" ' . $gernderreq . '" ' . '', 'value', 'text', '');
            $lists['careerlevel'] = JHTML::_('select.genericList', $this->common_model->getCareerLevel(JText::_('JS_SELECT')), 'careerlevel', 'class="inputbox" ' . '', 'value', 'text', '');
            $lists['workpermit'] = JHTML::_('select.genericList', $this->common_model->getCountries(JText::_('JS_SELECT')), 'workpermit', 'class="inputbox" ' . '', 'value', 'text', $this->_defaultcountry);
            $lists['requiredtravel'] = JHTML::_('select.genericList', $this->common_model->getRequiredTravel(JText::_('JS_SELECT')), 'requiredtravel', 'class="inputbox" ' . '', 'value', 'text', '');
            $lists['sendemail'] = JHTML::_('select.genericList', $this->common_model->getSendEmail(), 'sendemail', 'class="inputbox" ' . '', 'value', 'text', '$this->_job->sendemail', '');
            $lists['currencyid'] = JHTML::_('select.genericList', $this->common_model->getCurrency(), 'currencyid', 'class="inputbox" ' . '', 'value', 'text', '');
        }

        $result[0] = $this->_job;
        $result[1] = $lists;
        if (isset($visitor) && ($visitor == 1)) {
            if (isset($this->_job))
                $vis_jobid = $this->_job->id;
            $result[2] = $this->common_model->getUserFields(2, $vis_jobid); // job fields , ref id
        }else {
            $result[2] = $this->common_model->getUserFields(2, $job_id); // job fields , ref id
        }
        $result[3] = $fieldOrdering; // job fields
        if ($job_id) { // not new
            $canaddreturn = $this->canAddNewJob($uid);
            $result[4] = 1;
            $result[5] = $canaddreturn[1]; // package id
        } else { // new
            $canaddreturn = $this->canAddNewJob($uid);
            $result[4] = $canaddreturn[0]; // can add
            $result[5] = $canaddreturn[1]; // package id
        }
        if (isset($uid) && $uid != 0)
            $result[6] = $this->getAllPackagesByUid($uid, $job_id);
        $result[7] = 1; // for company check when add job

        if (isset($multi_lists) && $multi_lists != "")
            $result[8] = $multi_lists;
        return $result;
    }

    function getAllPackagesByUid($uid, $job_id) {
        if (!is_numeric($uid))
            return false;
        $db = $this->getDbo();
        $query = "SELECT payment.id AS paymentid, payment.packagetitle AS packagetitle, package.id AS packageid, package.jobsallow, package.enforcestoppublishjob, package.enforcestoppublishjobvalue, package.enforcestoppublishjobtype
                        , package.featuredjobs AS featuredjobs,package.goldjobs AS goldjobs, (SELECT COUNT(id) FROM #__js_job_jobs WHERE packageid = package.id AND paymenthistoryid = payment.id AND uid = " . $uid . ") AS jobavail
                        , (SELECT COUNT(id) FROM `#__js_job_jobs` WHERE isfeaturedjob=1 AND uid = " . $uid . " AND packageid = package.id ) AS availfeaturedjobs
                        , (SELECT COUNT(id) FROM `#__js_job_jobs` WHERE isgoldjob=1 AND uid = " . $uid . " AND packageid = package.id ) AS availgoldjobs
                        FROM #__js_job_paymenthistory AS payment
                        JOIN #__js_job_employerpackages AS package ON (package.id = payment.packageid AND payment.packagefor=1)
                        WHERE uid = " . $uid . "
                        AND DATE_ADD(payment.created,INTERVAL package.packageexpireindays DAY) >= CURDATE()
                        AND payment.transactionverified = 1 AND payment.status = 1";

        $db->setQuery($query);
        $result = $db->loadObjectList();
        $count = count($result); //check packages more then once or not
        if (isset($job_id) && $job_id != '') {
            $query = "SELECT packageid,paymenthistoryid FROM `#__js_job_jobs` WHERE id = " . $job_id;
            $db->setQuery($query);
            $job = $db->loadObject();
        }
        if ($count > 1) {

            $packagecombo = '<select id="package" class="inputbox " name="package" onChange="Javascript: changeDate(this.value);">';
            $packagecombo .= "<option value=''>" . JText::_('JS_SELECT_PACKAGE') . "</option>";

            foreach ($result AS $package) {
                if ($package->jobsallow != -1)
                    $jobleft = ($package->jobsallow - $package->jobavail) . ' ' . JText::_('JS_JOBS_LEFT');
                else
                    $jobleft = JText::_('JS_UNLIMITED_JOBS');
                if ($package->enforcestoppublishjob == 1) {
                    switch ($package->enforcestoppublishjobtype) {
                        case 1:$timetype = JText::_('JS_DAYS');
                            break;
                        case 2:$timetype = JText::_('JS_WEEKS');
                            break;
                        case 3:$timetype = JText::_('JS_MONTHS');
                            break;
                    }
                    $jobduration = $package->enforcestoppublishjobvalue . ' ' . $timetype;
                } else {
                    $jobduration = JText::_('JS_MANAUL_SELECT');
                }
                $title = '"' . $package->packagetitle . '"  ' . $jobleft . ', ' . JText::_('JS_JOB_DURATION') . ' ' . $jobduration;
                if (isset($job) && $job->packageid == $package->packageid) {
                    $packagecombo .= "<option value='$package->packageid' selected=\"selected\">$title</option>";
                    $combobox[] = array('value' => $package->packageid, 'text' => $title);
                } else {
                    $packagecombo .= "<option value='$package->packageid'>$title</option>";
                    $combobox[] = array('value' => $package->packageid, 'text' => $title);
                }
                $packagedetail["$package->packageid"] = $package;
            }
            $packagecombo .= "</select>";
            if (isset($job_id) && $job_id != '') {
                $lists['packages'] = JHTML::_('select.genericList', $combobox, 'multipackage', 'class="inputbox "' . 'onChange="changeDate(this.value)"' . '', 'value', 'text', $job->packageid);
            } else {
                $lists['packages'] = JHTML::_('select.genericList', $combobox, 'multipackage', 'class="inputbox "' . 'onChange="changeDate(this.value)"' . '', 'value', 'text', '');
            }

            //$lists['packages'] = JHTML::_('select.genericList', $combobox, 'multipackage', 'class="inputbox validate-selectpackage"'. '', 'value', 'text','' );

            $return[0] = $packagecombo;
            //$return[0] = $lists;
            $return[1] = $packagedetail;
        } elseif ($count == 1)
            $return = false;
        elseif ($count == 0)
            $return = 2; //no package
        return $return;
    }

    function canAddNewJob($uid) {
        $db = &$this->getDBO();
        if ($uid)
            if (is_numeric($uid) == false)
                return false;
        $returnvalue = array();
        $packagedetail = array();
        if (($uid == 0) || ($uid == '')) {
            $returnvalue[0] = false;
            $returnvalue[1] = $packagedetail;
            return $returnvalue;
        }
        if (!isset($this->_config)) {
            $this->_config = $this->common_model->getConfig('');
        }
        $newlisting_required_package = 1;
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'newlisting_requiredpackage')
                $newlisting_required_package = $conf->configvalue;
        }
        if ($newlisting_required_package == 0) {
            $packagedetail = '';
            $returnvalue[0] = true;
            $returnvalue[1] = $packagedetail;
            return $returnvalue;
        } else {
            $query = "SELECT package.id, package.jobsallow, package.packageexpireindays, payment.id AS paymentid, payment.created
                        , package.enforcestoppublishjob, package.enforcestoppublishjobvalue, package.enforcestoppublishjobtype
                       FROM `#__js_job_employerpackages` AS package
                       JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=1)
                       WHERE payment.uid = " . $uid . "
                       AND DATE_ADD(payment.created,INTERVAL package.packageexpireindays DAY) >= CURDATE()
                       AND payment.transactionverified = 1 AND payment.status = 1";

            $db->setQuery($query);
            $jobs = $db->loadObjectList();
            if (empty($jobs)) {
                $query = "SELECT package.id, package.jobsallow,package.title AS packagetitle, package.packageexpireindays, payment.id AS paymentid
                            , package.enforcestoppublishjob, package.enforcestoppublishjobvalue, package.enforcestoppublishjobtype
                            , (TO_DAYS( CURDATE() ) - To_days( payment.created ) ) AS packageexpiredays
                           FROM `#__js_job_employerpackages` AS package
                           JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=1)
                           WHERE payment.uid = " . $uid . " 
                           AND payment.transactionverified = 1 AND payment.status = 1 ORDER BY payment.created DESC";

                $db->setQuery($query);
                $packagedetail = $db->loadObjectList();
                $return_value[0] = false;
                $return_value[1] = $packagedetail;
                return $return_value;
            }
            $unlimited = 0;
            $jobsallow = "";
            foreach ($jobs AS $job) {
                if ($unlimited == 0) {
                    if ($job->jobsallow != -1) {
                        $jobsallow = $job->jobsallow + $jobsallow;
                    } else {
                        $unlimited = 1;
                    }
                    $packagedetail[0] = $job->id;
                    $packagedetail[1] = $job->paymentid;
                    $packagedetail[2] = $job->enforcestoppublishjob;
                    $packagedetail[3] = $job->enforcestoppublishjobvalue;
                    $packagedetail[4] = $job->enforcestoppublishjobtype;
                }
            }
            if ($unlimited == 0) {
                if ($jobsallow == 0) {
                    $returnvalue[0] = false;
                    $returnvalue[1] = $packagedetail;
                    return $returnvalue;
                } //can not add new job
                $query = "SELECT COUNT(jobs.id) AS totaljobs
				FROM `#__js_job_jobs` AS jobs
				WHERE jobs.uid = " . $uid;

                $db->setQuery($query);
                $totlajob = $db->loadResult();

                if ($jobsallow <= $totlajob) {
                    $returnvalue[0] = false;
                    $returnvalue[1] = $packagedetail;
                    return $returnvalue;
                } //can not add new job
                else {
                    $query = "SELECT payment.id AS paymentid, package.id, package.jobsallow, package.enforcestoppublishjob, package.enforcestoppublishjobvalue, package.enforcestoppublishjobtype
                                , (SELECT COUNT(id) FROM #__js_job_jobs WHERE packageid = package.id AND paymenthistoryid = payment.id AND uid = " . $uid . ") AS jobavail
                                FROM #__js_job_paymenthistory AS payment
                                JOIN #__js_job_employerpackages AS package ON (package.id = payment.packageid AND payment.packagefor=1)
                                WHERE uid = " . $uid . "
                                AND DATE_ADD(payment.created,INTERVAL package.packageexpireindays DAY) >= CURDATE()
                                AND payment.transactionverified = 1 AND payment.status = 1 ";

                    $db->setQuery($query);
                    $packages = $db->loadObjectList();
                    foreach ($packages AS $package) {
                        if ($package->jobsallow > $package->jobavail) {
                            $packagedetail[0] = $package->id;
                            $packagedetail[1] = $package->paymentid;
                            $packagedetail[2] = $package->enforcestoppublishjob;
                            $packagedetail[3] = $package->enforcestoppublishjobvalue;
                            $packagedetail[4] = $package->enforcestoppublishjobtype;
                        }
                    }
                    $returnvalue[0] = true;
                    $returnvalue[1] = $packagedetail;
                    return $returnvalue;
                }
            } elseif ($unlimited == 1) {
                $returnvalue[0] = true;
                $returnvalue[1] = $packagedetail;
                return $returnvalue;
            } // unlimited

            $returnvalue[0] = false;
            $returnvalue[1] = $packagedetail;
            return $returnvalue;
        }
    }

    function canAddNewCompany($uid) {
        $db = &$this->getDBO();
        if ($uid)
            if (is_numeric($uid) == false)
                return false;
        $returnvalue = array();
        $packagedetail = array();
        if (($uid == 0) || ($uid == '')) {
            $returnvalue[0] = false;
            $returnvalue[1] = $packagedetail;
            return $returnvalue;
        }
        if (!isset($this->_config)) {
            $this->_config = $this->common_model->getConfig('');
        }

        $newlisting_required_package = 1;
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'newlisting_requiredpackage')
                $newlisting_required_package = $conf->configvalue;
        }
        if ($newlisting_required_package == 0) {
            $packagedetail = '';
            $returnvalue[0] = true;
            $returnvalue[1] = $packagedetail;
            return $returnvalue;
        } else {
            $query = "SELECT package.id AS packageid, package.companiesallow, package.packageexpireindays, payment.id AS paymentid, payment.created
                        FROM `#__js_job_employerpackages` AS package
                        JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=1)
                        WHERE payment.uid = " . $uid . "
                        AND DATE_ADD(payment.created,INTERVAL package.packageexpireindays DAY) >= CURDATE()
                        AND payment.transactionverified = 1 AND payment.status = 1";
            $db->setQuery($query);
            $companies = $db->loadObjectList();
            if (empty($companies)) {
                $query = "SELECT package.id, package.jobsallow,package.title AS packagetitle, package.packageexpireindays, payment.id AS paymentid
                            , package.enforcestoppublishjob, package.enforcestoppublishjobvalue, package.enforcestoppublishjobtype
                            , (TO_DAYS( CURDATE() ) - To_days( payment.created ) ) AS packageexpiredays
                           FROM `#__js_job_employerpackages` AS package
                           JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=1)
                           WHERE payment.uid = " . $uid . " 
                           AND payment.transactionverified = 1 AND payment.status = 1 ORDER BY payment.created DESC";

                $db->setQuery($query);
                $packagedetail = $db->loadObjectList();

                $return_value[0] = false;
                $return_value[1] = $packagedetail;
                return $return_value;
            }
            $unlimited = 0;
            $companiesallow = "";
            foreach ($companies AS $company) {
                if ($unlimited == 0) {
                    if ($company->companiesallow != -1) {
                        $companiesallow = $companiesallow + $company->companiesallow;
                    } else {
                        $unlimited = 1;
                    }
                    $packagedetail[0] = $company->packageid;
                    $packagedetail[1] = $company->paymentid;
                }
            }
            if ($unlimited == 0) {
                if ($companiesallow == 0) {
                    $returnvalue[0] = false;
                    $returnvalue[1] = $packagedetail;
                    return $returnvalue;
                } //can not add new company
                $query = "SELECT COUNT(company.id) AS totalcompanies
				FROM `#__js_job_companies` AS company
				WHERE company.uid = " . $uid;

                $db->setQuery($query);
                $totalcompanies = $db->loadResult();

                if ($companiesallow <= $totalcompanies) {
                    $returnvalue[0] = false;
                    $returnvalue[1] = $packagedetail;
                    return $returnvalue;
                } //can not add new company
                else {
                    $query = "SELECT payment.id AS paymentid, package.id, package.companiesallow,package.jobsallow
                                , (SELECT COUNT(id) FROM #__js_job_companies WHERE packageid = package.id AND paymenthistoryid = payment.id AND uid = " . $uid . ") AS jobavail
                                FROM #__js_job_paymenthistory AS payment
                                JOIN #__js_job_employerpackages AS package ON (package.id = payment.packageid AND payment.packagefor=1)
                                WHERE payment.uid = " . $uid . "
                                AND DATE_ADD(payment.created,INTERVAL package.packageexpireindays DAY) >= CURDATE()
                                AND payment.transactionverified = 1 AND payment.status = 1";

                    $db->setQuery($query);
                    $packages = $db->loadObjectList();
                    foreach ($packages AS $package) {
                        if ($package->jobsallow > $package->jobavail) {
                            $packagedetail[0] = $package->id;
                            $packagedetail[1] = $package->paymentid;
                        }
                    }
                    $returnvalue[0] = true;
                    $returnvalue[1] = $packagedetail;
                    return $returnvalue;
                }
            } elseif ($unlimited == 1) {
                $returnvalue[0] = true;
                $returnvalue[1] = $packagedetail;
                return $returnvalue;
            } // unlimited

            return 0;
        }
    }

    function canAddNewFolder($uid) {
        $db = &$this->getDBO();
        if (is_numeric($uid) == false)
            return false;
        $returnvalue = array();
        $packagedetail = array();
        if (($uid == 0) || ($uid == '')) {
            $returnvalue[0] = false;
            $returnvalue[1] = $packagedetail;
            return $returnvalue;
        }
        if (!isset($this->_config)) {
            $this->_config = $this->common_model->getConfig('');
        }
        $newlisting_required_package = 1;
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'newlisting_requiredpackage')
                $newlisting_required_package = $conf->configvalue;
        }
        if ($newlisting_required_package == 0) {
            $packagedetail = '';
            $returnvalue[0] = true;
            $returnvalue[1] = $packagedetail;
            return $returnvalue;
        } else {
            $query = "SELECT package.id AS packageid, package.folders, package.packageexpireindays, payment.id AS paymentid, payment.created
                            FROM `#__js_job_employerpackages` AS package
                            JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=1)
                            WHERE payment.uid = " . $uid . "
                            AND DATE_ADD(payment.created,INTERVAL package.packageexpireindays DAY) >= CURDATE()
                            AND payment.transactionverified = 1 AND payment.status = 1";
            $db->setQuery($query);
            $companies = $db->loadObjectList();
            $unlimited = 0;
            $folders = "";
            foreach ($companies AS $company) {
                if ($unlimited == 0) {
                    if ($company->folders != -1) {
                        $folders = $folders + $company->folders;
                    } else {
                        $unlimited = 1;
                    }
                    $packagedetail[0] = $company->packageid;
                    $packagedetail[1] = $company->paymentid;
                }
            }
            if ($unlimited == 0) {
                if ($folders == 0) {
                    $returnvalue[0] = false;
                    $returnvalue[1] = $packagedetail;
                    return $returnvalue;
                } //can not add new folder
                $query = "SELECT COUNT(folder.id) AS totalfolders
				FROM `#__js_job_folders` AS folder
				WHERE folder.uid = " . $uid;

                $db->setQuery($query);
                $totalfolders = $db->loadResult();

                if ($folders <= $totalfolders) {
                    $returnvalue[0] = false;
                    $returnvalue[1] = $packagedetail;
                    return $returnvalue;
                } //can not add new folder
                else {
                    $query = "SELECT payment.id AS paymentid, package.id, package.folders
                                , (SELECT COUNT(id) FROM #__js_job_folders WHERE packageid = package.id AND paymenthistoryid = payment.id AND uid = " . $uid . ") AS folderavail
                                FROM #__js_job_paymenthistory AS payment
                                JOIN #__js_job_employerpackages AS package ON (package.id = payment.packageid AND payment.packagefor=1)
                                WHERE payment.uid = " . $uid . "
                                AND DATE_ADD(payment.created,INTERVAL package.packageexpireindays DAY) >= CURDATE()
                                AND payment.transactionverified = 1 AND payment.status = 1";

                    $db->setQuery($query);
                    $packages = $db->loadObjectList();
                    foreach ($packages AS $package) {
                        if ($package->folders > $package->folderavail) {
                            $packagedetail[0] = $package->id;
                            $packagedetail[1] = $package->paymentid;
                        }
                    }
                    $returnvalue[0] = true;
                    $returnvalue[1] = $packagedetail;
                    return $returnvalue;
                }
            } elseif ($unlimited == 1) {
                $returnvalue[0] = true;
                $returnvalue[1] = $packagedetail;
                return $returnvalue;
            } // unlimited

            return 0;
        }
    }

    function canAddNewGoldCompany($uid) {
        $db = &$this->getDBO();

        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (!isset($this->_config)) {
            $this->_config = $this->common_model->getConfig('');
        }
        $newlisting_required_package = 1;
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'newlisting_requiredpackage')
                $newlisting_required_package = $conf->configvalue;
        }
        if ($newlisting_required_package == 0) {
            return 1;
        } else {
            $query = "SELECT package.goldcompanies, package.packageexpireindays, payment.created
                        FROM `#__js_job_employerpackages` AS package
                        JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=1)
                        WHERE payment.uid = " . $uid . "
                        AND DATE_ADD(payment.created,INTERVAL package.packageexpireindays DAY) >= CURDATE()
                        AND payment.transactionverified = 1";
            $db->setQuery($query);
            $companies = $db->loadObjectList();
            $unlimited = 0;
            $goldcompanies = 0;
            foreach ($companies AS $company) {
                if ($unlimited == 0) {
                    if ($company->goldcompanies != -1) {
                        $goldcompanies = $goldcompanies + $company->goldcompanies;
                    }
                    else
                        $unlimited = 1;
                }
            }

            if ($unlimited == 0) {
                if ($goldcompanies == 0)
                    return 0; //can not add new gold company

                $query = "SELECT COUNT(company.id) 
				FROM `#__js_job_companies` AS company
				WHERE company.isgoldcompany=1 AND company.uid = " . $uid;
                $db->setQuery($query);
                $totalcompanies = $db->loadResult();

                if ($goldcompanies <= $totalcompanies)
                    return 0; //can not add new job
                else
                    return 1;
            }elseif ($unlimited == 1)
                return 1; // unlimited

            return 0;
        }
    }

    function canAddNewFeaturedCompany($uid) {
        $db = &$this->getDBO();

        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (!isset($this->_config)) {
            $this->_config = $this->common_model->getConfig('');
        }
        $newlisting_required_package = 1;
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'newlisting_requiredpackage')
                $newlisting_required_package = $conf->configvalue;
        }
        if ($newlisting_required_package == 0) {
            return 1;
        } else {
            $query = "SELECT package.featuredcompaines, package.packageexpireindays, payment.created
                        FROM `#__js_job_employerpackages` AS package
                        JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=1)
                        WHERE payment.uid = " . $uid . "
                        AND DATE_ADD(payment.created,INTERVAL package.packageexpireindays DAY) >= CURDATE()
                        AND payment.transactionverified = 1";
            $db->setQuery($query);
            $companies = $db->loadObjectList();
            $unlimited = 0;
            $featuredcompaines = 0;
            foreach ($companies AS $company) {
                if ($unlimited == 0) {
                    if ($company->featuredcompaines != -1) {
                        $featuredcompaines = $featuredcompaines + $company->featuredcompaines;
                    }
                    else
                        $unlimited = 1;
                }
            }
            if ($unlimited == 0) {
                if ($featuredcompaines == 0)
                    return 0; //can not add new job
                $query = "SELECT COUNT(company.id) 
				FROM `#__js_job_companies` AS company
				WHERE company.isfeaturedcompany=1 AND company.uid = " . $uid;

                $db->setQuery($query);
                $totalcompanies = $db->loadResult();

                if ($featuredcompaines <= $totalcompanies)
                    return 0; //can not add new company
                else
                    return 1;
            }elseif ($unlimited == 1)
                return 1; // unlimited

            return 0;
        }
    }

    function canAddNewFeaturedJob($uid) {
        $db = &$this->getDBO();

        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (!isset($this->_config)) {
            $this->_config = $this->common_model->getConfig('');
        }
        $newlisting_required_package = 1;
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'newlisting_requiredpackage')
                $newlisting_required_package = $conf->configvalue;
        }
        if ($newlisting_required_package == 0) {
            return 1;
        } else {
            $query = "SELECT package.featuredjobs, package.packageexpireindays, payment.created
			FROM `#__js_job_employerpackages` AS package
			JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=1)
			WHERE payment.uid = " . $uid . " 
			AND DATE_ADD(payment.created,INTERVAL package.packageexpireindays DAY) >= CURDATE() 
			AND payment.transactionverified = 1";

            $db->setQuery($query);
            $jobs = $db->loadObjectList();
            $unlimited = 0;
            $featuredjobs = 0;
            foreach ($jobs AS $job) {
                if ($unlimited == 0) {
                    if ($job->featuredjobs != -1) {
                        $featuredjobs = $featuredjobs + $job->featuredjobs;
                    }
                    else
                        $unlimited = 1;
                }
            }
            if ($unlimited == 0) {
                if ($featuredjobs == 0)
                    return 0; //can not add new job
                $query = "SELECT COUNT(job.id) 
				FROM `#__js_job_jobs` AS job
				WHERE job.isfeaturedjob=1 AND job.uid = " . $uid;

                $db->setQuery($query);
                $totaljobs = $db->loadResult();

                if ($featuredjobs <= $totaljobs)
                    return 0; //can not add new job
                else
                    return 1;
            }elseif ($unlimited == 1)
                return 1; // unlimited

            return 0;
        }
    }

    function canAddNewGoldJob($uid) {
        $db = &$this->getDBO();

        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (!isset($this->_config)) {
            $this->_config = $this->common_model->getConfig('');
        }
        $newlisting_required_package = 1;
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'newlisting_requiredpackage')
                $newlisting_required_package = $conf->configvalue;
        }
        if ($newlisting_required_package == 0) {
            return 1;
        } else {
            $query = "SELECT package.goldjobs, package.packageexpireindays, payment.created
			FROM `#__js_job_employerpackages` AS package
			JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=1)
			WHERE payment.uid = " . $uid . " 
			AND DATE_ADD(payment.created,INTERVAL package.packageexpireindays DAY) >= CURDATE() 
			AND payment.transactionverified = 1";
            $db->setQuery($query);
            $jobs = $db->loadObjectList();
            $unlimited = 0;
            $goldjobs = 0;
            foreach ($jobs AS $job) {
                if ($unlimited == 0) {
                    if ($job->goldjobs != -1) {
                        $goldjobs = $goldjobs + $job->goldjobs;
                    }
                    else
                        $unlimited = 1;
                }
            }
            if ($unlimited == 0) {
                if ($goldjobs == 0)
                    return 0; //can not add new job

                $query = "SELECT COUNT(job.id) 
				FROM `#__js_job_jobs` AS job
				WHERE job.isgoldjob=1 AND job.uid = " . $uid;

                $db->setQuery($query);
                $totaljobs = $db->loadResult();

                if ($goldjobs <= $totaljobs)
                    return 0; //can not add new job
                else
                    return 1;
            }elseif ($unlimited == 1)
                return 1; // unlimited

            return 0;
        }
    }

    function &getJobbyId($job_id) {
        $db = &$this->getDBO();
        if (is_numeric($job_id) == false)
            return false;
        if ($this->_client_auth_key != "") {
            $fortask = "viewjobbyid";
            $jsjobsharingobject = new JSJobsModelJob_Sharing;
            $data['jobid'] = $job_id;
            $data['authkey'] = $this->_client_auth_key;
            $data['siteurl'] = $this->_siteurl;
            $encodedata = json_encode($data);
            $return_server_value = $jsjobsharingobject->serverTask($encodedata, $fortask);
            if (isset($return_server_value['viewjobbyid']) AND $return_server_value['viewjobbyid'] == -1) { // auth fail 
                $logarray['uid'] = $this->_uid;
                $logarray['referenceid'] = $return_server_value['referenceid'];
                $logarray['eventtype'] = $return_server_value['eventtype'];
                $logarray['message'] = $return_server_value['message'];
                $logarray['event'] = "View Job By Id";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jsjobsharingobject->write_JobSharingLog($logarray);
                $this->_applications = array();
                $job_userfields = array();
            } else {
                $relation_data_array = json_decode($return_server_value['relationjsondata']);
                $job_userfields_array = "";
                if (isset($return_server_value['userfields']))
                    $job_userfields_array = json_decode($return_server_value['userfields'], true);
                $parsedata = array();
                $parsedata = (object) $relation_data_array;
                $this->_application = $parsedata;
                $job_userfields = $job_userfields_array;
            }
        }else {

            $query = "SELECT job.*, cat.cat_title, subcat.title as subcategory, company.name as companyname, jobtype.title AS jobtypetitle
                        , jobstatus.title AS jobstatustitle, shift.title as shifttitle
                        , department.name AS departmentname
                        , salaryfrom.rangestart AS salaryfrom, salaryto.rangestart AS salaryto, salarytype.title AS salarytype
                        , education.title AS educationtitle ,mineducation.title AS mineducationtitle, maxeducation.title AS maxeducationtitle
                        , experience.title AS experiencetitle ,minexperience.title AS minexperiencetitle, maxexperience.title AS maxexperiencetitle
                        , currency.symbol
                        ,CONCAT(job.alias,'-',job.id) AS aliasid
                        ,CONCAT(company.alias,'-',company.id) AS companyaliasid
			FROM `#__js_job_jobs` AS job
			JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
			LEFT JOIN `#__js_job_subcategories` AS subcat ON job.subcategoryid = subcat.id
			JOIN `#__js_job_companies` AS company ON job.companyid = company.id
			JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
			JOIN `#__js_job_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id
			LEFT JOIN `#__js_job_departments` AS department ON job.departmentid = department.id
			LEFT JOIN `#__js_job_salaryrange` AS salaryfrom ON job.salaryrangefrom = salaryfrom.id
			LEFT JOIN `#__js_job_salaryrange` AS salaryto ON job.salaryrangeto = salaryto.id
			LEFT JOIN `#__js_job_salaryrangetypes` AS salarytype ON job.salaryrangetype = salarytype.id
			LEFT JOIN `#__js_job_heighesteducation` AS education ON job.educationid = education.id
			LEFT JOIN `#__js_job_heighesteducation` AS mineducation ON job.mineducationrange = mineducation.id
			LEFT JOIN `#__js_job_heighesteducation` AS maxeducation ON job.maxeducationrange = maxeducation.id
			LEFT JOIN `#__js_job_experiences` AS experience ON job.experienceid = experience.id
			LEFT JOIN `#__js_job_experiences` AS minexperience ON job.minexperiencerange = minexperience.id
			LEFT JOIN `#__js_job_experiences` AS maxexperience ON job.maxexperiencerange = maxexperience.id
			LEFT JOIN `#__js_job_shifts` AS shift ON job.shift = shift.id
			LEFT JOIN `#__js_job_currencies` AS currency ON currency.id = job.currencyid
			WHERE  job.id = " . $job_id;

            $db->setQuery($query);
            $this->_application = $db->loadObject();
            $this->_application->multicity = $this->common_model->getMultiCityDataForView($job_id, 1);

            $query = "UPDATE `#__js_job_jobs` SET hits = hits + 1 WHERE id = " . $job_id;

            $db->setQuery($query);
            if (!$db->query()) {
                //return false;
            }
            $job_userfields = $this->common_model->getUserFieldsForView(2, $job_id); // company fields, id
        }
        $result[0] = $this->_application;
        $result[2] = $job_userfields; // job userfields
        $result[3] = $this->common_model->getFieldsOrdering(2); // company fields
        $result[4] = $this->common_model->getConfigByFor('listjob'); // company fields

        return $result;
    }

    function &getJobsAppliedResume($u_id, $sortby, $limit, $limitstart) {
        $db = &$this->getDBO();
        if ($u_id)
            if ((is_numeric($u_id) == false) || ($u_id == 0) || ($u_id == ''))
                return false;
        $result = array();
        if ($this->_client_auth_key != "") {
            $fortask = "alljobsappliedapplications";
            $jsjobsharingobject = new JSJobsModelJob_Sharing;
            $data['uid'] = $u_id;
            $data['sortby'] = $sortby;
            $data['limitstart'] = $limitstart;
            $data['limit'] = $limit;
            $data['authkey'] = $this->_client_auth_key;
            $data['siteurl'] = $this->_siteurl;
            $encodedata = json_encode($data);
            $return_server_value = $jsjobsharingobject->serverTask($encodedata, $fortask);
            if (isset($return_server_value['alljobsappliedresume']) AND $return_server_value['alljobsappliedresume'] == -1) { // auth fail 
                $logarray['uid'] = $this->_uid;
                $logarray['referenceid'] = $return_server_value['referenceid'];
                $logarray['eventtype'] = $return_server_value['eventtype'];
                $logarray['message'] = $return_server_value['message'];
                $logarray['event'] = "All Applied Resume on Jobs";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jsjobsharingobject->write_JobSharingLog($logarray);
                $this->_applications = array();
                $total = 0;
            } else {
                $parse_data = array();
                foreach ($return_server_value['relationjsondata'] AS $rel_data) {
                    $parse_data[] = (object) $rel_data;
                }
                $this->_applications = $parse_data;
                $total = $return_server_value['total'];
            }
        } else {
            $query = "SELECT COUNT(job.id)
			FROM `#__js_job_jobs` AS job, `#__js_job_categories` AS cat 
			WHERE job.jobcategory = cat.id AND job.uid= " . $u_id;
            $db->setQuery($query);
            $total = $db->loadResult();

            //$limit = $limit ? $limit : 5;
            if ($total <= $limitstart)
                $limitstart = 0;

            $query = "SELECT DISTINCT job.*, cat.cat_title , company.name ,jobtype.title AS jobtypetitle, jobstatus.title AS jobstatustitle
					, (SELECT COUNT(apply.id) FROM `#__js_job_jobapply` AS apply WHERE apply.jobid = job.id ) as appinjob
					,CONCAT(job.alias,'-',job.id) AS aliasid
					FROM `#__js_job_jobs` AS job
					JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
					JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
					JOIN `#__js_job_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id
					JOIN `#__js_job_companies` AS company ON job.companyid = company.id
				WHERE job.uid= " . $u_id . " ORDER BY  " . $sortby;
            $db->setQuery($query, $limitstart, $limit);
            $this->_applications = $db->loadObjectList();
        }


        $result[0] = $this->_applications;
        $result[1] = $total;

        return $result;
    }

    function &getJobAppliedResume($needle_array, $u_id, $jobid, $tab_action, $sortby, $limit, $limitstart) {
        $db = &$this->getDBO();
        if (is_numeric($u_id) == false)
            return false;
        if (is_numeric($jobid) == false)
            return false;
        $result = array();
        if ($this->_client_auth_key != "") {
            $fortask = "getjobappliedresume";
            $jsjobsharingobject = new JSJobsModelJob_Sharing;
            $data['uid'] = $u_id;
            $data['jobid'] = $jobid;
            $data['sortby'] = $sortby;
            $data['limitstart'] = $limitstart;
            $data['limit'] = $limit;
            $data['authkey'] = $this->_client_auth_key;
            $data['siteurl'] = $this->_siteurl;
            $data['tab_action'] = $tab_action;
            if (!empty($needle_array)) {
                $needle_array = json_decode($needle_array, true);
                $data['tab_action'] = "";
            }
            $server_needle_query = "";
            if (isset($needle_array['title']) AND $needle_array['title'] != '')
                $server_needle_query.=" AND job_resume.application_title LIKE '%" . str_replace("'", "", $db->Quote($needle_array['title'])) . "%'";
            if (isset($needle_array['name']) AND $needle_array['name'] != '')
                $server_needle_query.=" AND LOWER(job_resume.first_name) LIKE " . $db->Quote('%' . $needle_array['name'] . '%', false);
            if (isset($needle_array['nationality']) AND $needle_array['nationality'] != '')
                $server_needle_query .= " AND job_resume.nationality = " . $needle_array['nationality'];
            if (isset($needle_array['gender']) AND $needle_array['gender'] != '')
                $server_needle_query .= " AND job_resume.gender = " . $needle_array['gender'];
            if (isset($needle_array['jobtype']) AND $needle_array['jobtype'] != '') {
                $server_jobtype_id = $this->common_model->getServerid('jobtypes', $needle_array['jobtype']);
                $server_needle_query .= " AND job_resume.jobtype = " . $server_jobtype_id;
            }
            if (isset($needle_array['currency']) AND $needle_array['currency'] != '') {
                $server_currency_id = $this->common_model->getServerid('currencies', $needle_array['currency']);
                $server_needle_query .= " AND job_resume.currencyid = " . $server_currency_id;
            }
            if (isset($needle_array['jobsalaryrange']) AND $needle_array['jobsalaryrange'] != '') {
                $server_jobsalaryrange = $this->common_model->getServerid('salaryrange', $needle_array['jobsalaryrange']);
                $server_needle_query .= " AND job_resume.jobsalaryrange = " . $server_jobsalaryrange;
            }
            if (isset($needle_array['heighestfinisheducation']) AND $needle_array['heighestfinisheducation'] != '') {
                $server_heighestfinisheducation = $this->common_model->getServerid('heighesteducation', $needle_array['heighestfinisheducation']);
                $server_needle_query .= " AND job_resume.heighestfinisheducation = " . $server_heighestfinisheducation;
            }
            if (isset($needle_array['iamavailable']) AND $needle_array['iamavailable'] != '') {
                $available = ($needle_array['iamavailable'] == "yes") ? 1 : 0;
                $server_needle_query .= " AND job_resume.iamavailable = " . $available;
            }
            if (isset($needle_array['jobcategory']) AND $needle_array['jobcategory'] != '') {
                $server_jobcategory = $this->common_model->getServerid('categories', $needle_array['jobcategory']);
                $server_needle_query .= " AND job_resume.job_category = " . $server_jobcategory;
            }
            if (isset($needle_array['jobsubcategory']) AND $needle_array['jobsubcategory'] != '') {
                $server_jobsubcategory = $this->common_model->getServerid('subcategories', $needle_array['jobsubcategory']);
                $server_needle_query .= " AND job_resume.job_subcategory = " . $server_jobsubcategory;
            }
            if (isset($needle_array['experience']) AND $needle_array['experience'] != '') {
                $server_needle_query .= " AND job_resume.total_experience LIKE " . $db->Quote($needle_array['experience']);
            }
            if (!empty($server_needle_query)) {
                $data['server_needle_query'] = $server_needle_query;
            }
            $encodedata = json_encode($data);
            $return_server_value = $jsjobsharingobject->serverTask($encodedata, $fortask);
            if (isset($return_server_value['jobappliedresume']) AND $return_server_value['jobappliedresume'] == -1) { // auth fail 
                $logarray['uid'] = $this->_uid;
                $logarray['referenceid'] = $return_server_value['referenceid'];
                $logarray['eventtype'] = $return_server_value['eventtype'];
                $logarray['message'] = $return_server_value['message'];
                $logarray['event'] = "Job Applied Resume";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jsjobsharingobject->write_JobSharingLog($logarray);
                $this->_applications = array();
                $total = 0;
                $jobtitle = "";
            } else {
                $parse_data = array();
                foreach ($return_server_value['relationjsondata'] AS $rel_data) {
                    $parse_data[] = (object) $rel_data;
                }
                $this->_applications = $parse_data;
                $total = $return_server_value['total'];
                $jobtitle = $return_server_value['jobtitle'];
            }
        } else {
            if (!empty($needle_array)) {
                $needle_array = json_decode($needle_array, true);
                $tab_action = "";
            }
            $query = "SELECT COUNT(job.id)
			FROM `#__js_job_jobs` AS job
			   , `#__js_job_jobapply` AS apply  
			   , `#__js_job_resume` AS app  
			   
			WHERE apply.jobid = job.id AND apply.cvid = app.id AND apply.jobid = " . $jobid;
            if ($tab_action)
                $query.=" AND apply.action_status=" . $tab_action;
            if (isset($needle_array['title']) AND $needle_array['title'] != '')
                $query.=" AND app.application_title LIKE '%" . str_replace("'", "", $db->Quote($needle_array['title'])) . "%'";
            if (isset($needle_array['name']) AND $needle_array['name'] != '')
                $query.=" AND LOWER(app.first_name) LIKE " . $db->Quote('%' . $needle_array['name'] . '%', false);
            if (isset($needle_array['nationality']) AND $needle_array['nationality'] != '')
                $query .= " AND app.nationality = " . $needle_array['nationality'];
            if (isset($needle_array['gender']) AND $needle_array['gender'] != '')
                $query .= " AND app.gender = " . $needle_array['gender'];
            if (isset($needle_array['jobtype']) AND $needle_array['jobtype'] != '')
                $query .= " AND app.jobtype = " . $needle_array['jobtype'];
            if (isset($needle_array['currency']) AND $needle_array['currency'] != '')
                $query .= " AND app.currencyid = " . $needle_array['currency'];
            if (isset($needle_array['jobsalaryrange']) AND $needle_array['jobsalaryrange'] != '')
                $query .= " AND app.jobsalaryrange = " . $needle_array['jobsalaryrange'];
            if (isset($needle_array['heighestfinisheducation']) AND $needle_array['heighestfinisheducation'] != '')
                $query .= " AND app.heighestfinisheducation = " . $needle_array['heighestfinisheducation'];
            if (isset($needle_array['iamavailable']) AND $needle_array['iamavailable'] != '') {
                $available = ($needle_array['iamavailable'] == "yes") ? 1 : 0;
                $query .= " AND app.iamavailable = " . $available;
            }
            if (isset($needle_array['jobcategory']) AND $needle_array['jobcategory'] != '')
                $query .= " AND app.job_category = " . $needle_array['jobcategory'];
            if (isset($needle_array['jobsubcategory']) AND $needle_array['jobsubcategory'] != '')
                $query .= " AND app.job_subcategory = " . $needle_array['jobsubcategory'];
            if (isset($needle_array['experience']) AND $needle_array['experience'] != '')
                $query .= " AND app.total_experience LIKE " . $db->Quote($needle_array['experience']);

            $db->setQuery($query);
            $total = $db->loadResult();

            if ($total <= $limitstart)
                $limitstart = 0;

            $query = "SELECT apply.comments,apply.id AS jobapplyid ,job.id,job.agefrom,job.ageto, cat.cat_title ,apply.apply_date, apply.resumeview, jobtype.title AS jobtypetitle,app.iamavailable
                        , app.id AS appid, app.first_name, app.last_name, app.email_address, app.jobtype,app.gender
                        , app.total_experience, app.jobsalaryrange,rating.id AS ratingid, rating.rating
                        , app.address_city, app.address_county, app.address_state ,app.id as resumeid
                        , country.name AS countryname,state.name AS statename
                        ,city.name AS cityname
                        , salary.rangestart, salary.rangeend,education.title AS educationtitle
                        , currency.symbol AS symbol
                        ,dcurrency.symbol AS dsymbol ,dsalary.rangestart AS drangestart, dsalary.rangeend AS drangeend  
                        ,app.institute1_study_area AS education
                        ,app.photo AS photo,app.application_title AS applicationtitle
                        ,CONCAT(app.alias,'-',app.id) resumealiasid
                        FROM `#__js_job_jobs` AS job
                        JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                        JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
                        JOIN `#__js_job_jobapply` AS apply  ON apply.jobid = job.id 
                        JOIN `#__js_job_resume` AS app ON apply.cvid = app.id 
                        LEFT JOIN  `#__js_job_resumerating` AS rating ON (app.id=rating.resumeid AND apply.jobid=rating.jobid)
                        LEFT JOIN `#__js_job_heighesteducation` AS  education  ON app.heighestfinisheducation=education.id
                        LEFT OUTER JOIN  `#__js_job_salaryrange` AS salary	ON	app.jobsalaryrange=salary.id
                        LEFT OUTER JOIN  `#__js_job_salaryrange` AS dsalary ON app.desired_salary=dsalary.id 
                        LEFT JOIN `#__js_job_cities` AS city ON app.address_city = city.id
                        LEFT JOIN `#__js_job_countries` AS country ON city.countryid  = country.id
                        LEFT JOIN `#__js_job_states` AS state ON city.stateid = state.id
                        LEFT JOIN `#__js_job_currencies` AS currency ON currency.id = app.currencyid
                        LEFT JOIN `#__js_job_currencies` AS dcurrency ON dcurrency.id = app.dcurrencyid 
			WHERE apply.jobid = " . $jobid;
            if ($tab_action)
                $query.=" AND apply.action_status=" . $tab_action;
            if (isset($needle_array['title']) AND $needle_array['title'] != '')
                $query.=" AND app.application_title LIKE '%" . str_replace("'", "", $db->Quote($needle_array['title'])) . "%'";
            if (isset($needle_array['name']) AND $needle_array['name'] != '')
                $query.=" AND LOWER(app.first_name) LIKE " . $db->Quote('%' . $needle_array['name'] . '%', false);
            if (isset($needle_array['nationality']) AND $needle_array['nationality'] != '')
                $query .= " AND app.nationality = " . $needle_array['nationality'];
            if (isset($needle_array['gender']) AND $needle_array['gender'] != '')
                $query .= " AND app.gender = " . $needle_array['gender'];
            if (isset($needle_array['jobtype']) AND $needle_array['jobtype'] != '')
                $query .= " AND app.jobtype = " . $needle_array['jobtype'];
            if (isset($needle_array['currency']) AND $needle_array['currency'] != '')
                $query .= " AND app.currencyid = " . $needle_array['currency'];
            if (isset($needle_array['jobsalaryrange']) AND $needle_array['jobsalaryrange'] != '')
                $query .= " AND app.jobsalaryrange = " . $needle_array['jobsalaryrange'];
            if (isset($needle_array['heighestfinisheducation']) AND $needle_array['heighestfinisheducation'] != '')
                $query .= " AND app.heighestfinisheducation = " . $needle_array['heighestfinisheducation'];
            if (isset($needle_array['iamavailable']) AND $needle_array['iamavailable'] != '') {
                $available = ($needle_array['iamavailable'] == "yes") ? 1 : 0;
                $query .= " AND app.iamavailable = " . $available;
            }
            if (isset($needle_array['jobcategory']) AND $needle_array['jobcategory'] != '')
                $query .= " AND app.job_category = " . $needle_array['jobcategory'];
            if (isset($needle_array['jobsubcategory']) AND $needle_array['jobsubcategory'] != '')
                $query .= " AND app.job_subcategory = " . $needle_array['jobsubcategory'];
            if (isset($needle_array['experience']) AND $needle_array['experience'] != '')
                $query .= " AND app.total_experience LIKE " . $db->Quote($needle_array['experience']);

            $query.=" ORDER BY  " . $sortby;
            $db->setQuery($query, $limitstart, $limit);
            $this->_applications = $db->loadObjectList();
            $query = "SELECT title FROM `#__js_job_jobs` WHERE id = " . $jobid;
            $db->setQuery($query);
            $jobtitle = $db->loadResult();
        }

        $result[0] = $this->_applications;
        $result[1] = $total;
        $result[2] = $jobtitle;

        return $result;
    }

    function featuredCompanyValidation($uid, $companyid) {

        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if ((is_numeric($companyid) == false) || ($companyid == 0) || ($companyid == ''))
            return false;

        $db = & JFactory::getDBO();

        $query = "SELECT COUNT(company.id)  
		FROM #__js_job_companies  AS company
		WHERE company.isfeaturedcompany=1 AND company.uid = " . $uid . " AND company.id = " . $companyid;

        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return true;
        else
            return false;
    }

    function goldCompanyValidation($uid, $companyid) {

        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if ((is_numeric($companyid) == false) || ($companyid == 0) || ($companyid == ''))
            return false;

        $db = & JFactory::getDBO();

        $query = "SELECT COUNT(company.id)  
		FROM #__js_job_companies  AS company
		WHERE company.isgoldcompany=1 AND company.uid = " . $uid . " AND company.id = " . $companyid;
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return true;
        else
            return false;
    }

    function storeFeaturedJobs($uid, $jobid) {
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if ((is_numeric($jobid) == false) || ($jobid == 0) || ($jobid == ''))
            return false;
        $db = &$this->getDBO();
        $query = "SELECT COUNT(id)
				FROM `#__js_job_jobs` 
				WHERE uid = " . $uid . " AND id = " . $jobid . " AND status = 1";

        $db->setQuery($query);
        $jobs = $db->loadResult();
        if ($jobs <= 0)
            return 3; // job not exsit or not approved
        if ($this->canAddNewFeaturedJob($uid) == false)
            return 5;

        $result = $this->featuredJobValidation($uid, $jobid);
        if ($result == false) {
            return 6;
        } else {
            $query = "UPDATE `#__js_job_jobs` SET isfeaturedjob = 1 WHERE id = " . $jobid . " AND uid = " . $uid;
            $db->setQuery($query);
            if (!$db->query())
                return false;
            else
                return true;
        }
    }

    function featuredJobValidation($uid, $jobid) {
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if ((is_numeric($jobid) == false) || ($jobid == 0) || ($jobid == ''))
            return false;

        $db = & JFactory::getDBO();
        $query = "SELECT COUNT(job.id)  
		FROM #__js_job_jobs  AS job
		WHERE job.isfeaturedjob=1 AND job.uid = " . $uid . " AND job.id = " . $jobid
        ;

        $db->setQuery($query);
        $result = $db->loadResult();

        if ($result == 0)
            return true;
        else
            return false;
    }

    function storeGoldJobs($uid, $jobid) {
        global $resumedata;
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if ((is_numeric($jobid) == false) || ($jobid == 0) || ($jobid == ''))
            return false;
        $db = &$this->getDBO();
        $query = "SELECT COUNT(id)
				FROM `#__js_job_jobs` 
				WHERE uid = " . $uid . " AND id = " . $jobid . " AND status = 1";

        $db->setQuery($query);
        $jobs = $db->loadResult();
        if ($jobs <= 0)
            return 3; // job not exsit or not approved


        if ($this->canAddNewGoldJob($uid) == false)
            return 5; // can not add new gold job

        $result = $this->goldJobValidation($uid, $jobid);
        if ($result == false) {
            return 6;
        } else {
            $query = "UPDATE `#__js_job_jobs` SET isgoldjob = 1 WHERE id = " . $jobid . " AND uid = " . $uid;
            $db->setQuery($query);
            if (!$db->query())
                return false;
            else
                return true;
        }
    }

    function goldJobValidation($uid, $jobid) {
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if ((is_numeric($jobid) == false) || ($jobid == 0) || ($jobid == ''))
            return false;

        $db = & JFactory::getDBO();

        $query = "SELECT COUNT(job.id)  
		FROM #__js_job_jobs  AS job
		WHERE job.isgoldjob=1 AND job.uid = " . $uid . " AND job.id = " . $jobid;

        $db->setQuery($query);
        $result = $db->loadResult();

        if ($result == 0)
            return true;
        else
            return false;
    }

    function &getEmployerPackageInfoById($packageid) {
        if (is_numeric($packageid) == false)
            return false;
        $db = &$this->getDBO();
        $query = "SELECT package.* FROM `#__js_job_employerpackages` AS package WHERE id = " . $packageid;

        $db->setQuery($query);
        $package = $db->loadObject();

        return $package;
    }

    function updateEmployerPackageHistory($firstname, $lastname, $email, $amount, $referenceid
    , $tx_token, $date, $paypalstatus, $status) {
        $db = &$this->getDBO();

        $query = "UPDATE `#__js_job_employerpaymenthistory`
                    SET payer_firstname = " . $db->quote($firstname) . "
                    , payer_lastname = " . $db->quote($lastname) . "
                    , payer_email = " . $db->quote($email) . "
                    , payer_amount = " . $amount . "    
                    , payer_tx_token = " . $db->quote($tx_token) . "
                    , transactionverified = " . $status . "
                    , transactionautoverified = 1
                    , verifieddate = " . $db->quote($date) . "
                    , payer_status = " . $db->quote($paypalstatus) . "
                    WHERE referenceid = " . $db->quote($referenceid);

        $db->setQuery($query);
        $db->query();

        return true;
    }

    function storeDepartment() {
        global $resumedata;
        $row = &$this->getTable('department');
        $data = JRequest :: get('post');

        if (!empty($data['alias']))
            $departmentalias = $data['alias'];
        else
            $departmentalias = $data['name'];

        $departmentalias = strtolower(str_replace(' ', '-', $departmentalias));
        $data['alias'] = $departmentalias;

        if ($data['id'] == '') { // only for new 
            $config = $this->common_model->getConfigByFor('department');
            $data['status'] = $config['department_auto_approve'];
        }
        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        if (!$row->check()) {
            $this->setError($this->_db->getErrorMsg());
            return 2;
        }
        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());
            echo $this->_db->getErrorMsg();
            return false;
        }
        if ($data['id'] == '')
            $this->common_model->sendMailtoAdmin($row->id, $data['uid'], 5); //only for new
        if ($this->_client_auth_key != "") {
            $db = &$this->getDBO();
            $query = "SELECT department.* FROM `#__js_job_departments` AS department  
						WHERE department.id = " . $row->id;
            //echo '<br> SQL '.$query;
            $db->setQuery($query);
            $data_department = $db->loadObject();
            if ($data['id'] != "" AND $data['id'] != 0)
                $data_department->id = $data['id']; // for edit case
            $data_department->department_id = $row->id;
            $data_department->authkey = $this->_client_auth_key;
            $data_department->task = 'storedepartment';
            $jsjobsharingobject = new JSJobsModelJob_Sharing;
            $return_value = $jsjobsharingobject->store_DepartmentSharing($data_department);
            return $return_value;
        }else {
            return true;
        }
    }

    function storeFolder() { //store Folder
        $row = &$this->getTable('folder');
        $db = &$this->getDBO();
        $data = JRequest :: get('post');

        if ($this->_client_auth_key != "") {
            if ($data['id'] != "" AND $data['id'] != 0) {
                $query = "select folder.id AS id 
                            From #__js_job_folders AS folder
                            WHERE folder.serverid=" . $data['id'];

                $db->setQuery($query);
                $folder_id = $db->loadResult();
                if ($folder_id) {
                    $data['id'] = $folder_id;
                    $isownfolder = 1;
                }
                else
                    $isownfolder = 0;
            }
        }

        if ($data['id'] == '') { // only for new 
            $config = $this->common_model->getConfigByFor('folder');
            $data['status'] = $config['folder_auto_approve'];
        }
        $data['decription'] = JRequest::getVar('decription', '', 'post', 'string', JREQUEST_ALLOWRAW);
        if (!empty($data['alias']))
            $folderalias = $data['alias'];
        else
            $folderalias = $data['name'];

        $folderalias = strtolower(str_replace(' ', '-', $folderalias));
        $data['alias'] = $folderalias;

        if ($data['id'] == '') {
            $name = $data['name'];
            if ($this->folderValidation($name))
                return 3;
        }
        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        if (!$row->check()) {
            $this->setError($this->_db->getErrorMsg());
            return 2;
        }
        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        if ($this->_client_auth_key != "") {
            $db = &$this->getDBO();
            $query = "SELECT folder.* FROM `#__js_job_folders` AS folder  
						WHERE folder.id = " . $row->id;
            //echo '<br> SQL '.$query;
            $db->setQuery($query);
            $data_folder = $db->loadObject();
            if ($data['id'] != "" AND $data['id'] != 0) {
                $query = "select folder.serverid AS serverid 
                                From #__js_job_folders AS folder
                                WHERE folder.id=" . $data['id'];
                //echo 'query'.$query;
                $db->setQuery($query);
                $serverfolder_id = $db->loadResult();
                $data_folder->id = $serverfolder_id; // for edit case
            }
            $data_folder->folder_id = $row->id;
            $data_folder->authkey = $this->_client_auth_key;
            $data_folder->task = 'storefolder';
            $jsjobsharingobject = new JSJobsModelJob_Sharing;
            $return_value = $jsjobsharingobject->store_FolderSharing($data_folder);
            return $return_value;
        } else {
            return true;
        }
    }

    function folderValidation($foldername) {
        $db = & JFactory:: getDBO();
        $query = "SELECT COUNT(id) FROM `#__js_job_folders` WHERE name = " . $db->Quote($foldername);
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result > 0)
            return true;
        else
            return false;
    }

    function storeFolderResume($data) { //store Folder
        $row = &$this->getTable('folderresume');
        $curdate = date('Y-m-d H:i:s');
        $data['uid'] = $this->_uid;
        $data['created'] = $curdate;
        if ($this->_client_auth_key != "") {
            if ($data['resumeid']) {
                $db = & JFactory::getDBO();
                $query = "SELECT id FROM #__js_job_resume 
				WHERE serverid = " . $data['resumeid'];

                $db->setQuery($query);
                $result = $db->loadResult();
                if (!$result)
                    $is_own_resume = 0;
                else {
                    $is_own_resume = 1;
                    $data['resumeid'] = $result;
                }

                if ($is_own_resume == 1) {
                    $query = "SELECT id FROM #__js_job_jobs 
					WHERE serverid = " . $data['jobid'];

                    $db->setQuery($query);
                    $job_id = $db->loadResult();
                    if ($job_id)
                        $data['jobid'] = $job_id;

                    $query = "SELECT id FROM #__js_job_folders 
					WHERE serverid = " . $data['folderid'];

                    $db->setQuery($query);
                    $folder_id = $db->loadResult();
                    if ($folder_id)
                        $data['folderid'] = $folder_id;
                }
            }
        }else {
            $is_own_resume = 1;
        }
        if ($is_own_resume == 1) {
            $jobid = $data['jobid'];
            $resumeid = $data['resumeid'];
            $folderid = $data['folderid'];

            if ($this->resumeFolderValidation($jobid, $resumeid, $folderid))
                return 3;

            if (!$row->bind($data)) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
            if (!$row->check()) {
                $this->setError($this->_db->getErrorMsg());
                return 2;
            }
            if (!$row->store()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
        if ($this->_client_auth_key != "") {
            if ($is_own_resume == 1) { // own Resume  
                if ($data['jobid'] != "" AND $data['jobid'] != 0) {
                    $query = "select job.serverid AS serverid 
							From #__js_job_jobs AS job
							WHERE job.id=" . $data['jobid'];
                    //echo 'query'.$query;
                    $db->setQuery($query);
                    $job_serverid = $db->loadResult();
                    if ($job_serverid)
                        $data['jobid'] = $job_serverid;
                    else
                        $data['jobid'] = 0;
                }
                if ($data['resumeid'] != "" AND $data['resumeid'] != 0) {
                    $query = "select resume.serverid AS serverid 
							From #__js_job_resume AS resume
							WHERE resume.id=" . $data['resumeid'];
                    //echo 'query'.$query;
                    $db->setQuery($query);
                    $resume_serverid = $db->loadResult();
                    if ($resume_serverid)
                        $data['resumeid'] = $resume_serverid;
                    else
                        $data['resumeid'] = 0;
                }
                if ($data['folderid'] != "" AND $data['folderid'] != 0) {
                    $query = "select folder.serverid AS serverid 
							From #__js_job_folders AS folder
							WHERE folder.id=" . $data['folderid'];
                    //echo 'query'.$query;
                    $db->setQuery($query);
                    $folder_serverid = $db->loadResult();
                    if ($folder_serverid)
                        $data['folderid'] = $folder_serverid;
                    else
                        $data['folderid'] = 0;
                }
                $data['folderresume_id'] = $row->id;
                $data['authkey'] = $this->_client_auth_key;
                $data['task'] = 'storeownresumefolder';
                $isownresumefolder = 1;
                $data['isownresumefolder'] = $isownresumefolder;
                $jsjobsharingobject = new JSJobsModelJob_Sharing;
                $return_value = $jsjobsharingobject->store_ResumeFolderSharing($data);
                return $return_value;
            }else {  // server job apply on job sharing 
                $data['authkey'] = $this->_client_auth_key;
                $data['task'] = 'storeserverresumefolder';
                $isownresumefolder = 0;
                $data['isownresumefolder'] = $isownresumefolder;
                $jsjobsharingobject = new JSJobsModelJob_Sharing;
                $return_value = $jsjobsharingobject->store_ResumeFolderSharing($data);
                return $return_value;
            }
        } else {
            return true;
        }
    }

    function resumeFolderValidation($jobid, $resumeid, $folderid) {
        $db = & JFactory:: getDBO();
        if ((is_numeric($jobid) == false) || ($jobid == 0) || ($jobid == ''))
            return false;
        if ((is_numeric($resumeid) == false) || ($resumeid == 0) || ($resumeid == ''))
            return false;
        $query = "SELECT COUNT(id) FROM #__js_job_folderresumes
		WHERE jobid = " . $jobid . " AND resumeid =" . $resumeid . " AND folderid = " . $folderid;
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result > 0)
            return true;
    }

    function deleteCompany($companyid, $uid) {
        $db = &$this->getDBO();
        $row = &$this->getTable('company');
        $data = JRequest :: get('post');
        if (is_numeric($companyid) == false)
            return false;
        if (is_numeric($uid) == false)
            return false;
        $servercompanyid = 0;
        if ($this->_client_auth_key != "") {
            $query = "SELECT company.serverid AS serverid FROM `#__js_job_companies` AS company  WHERE company.id = " . $companyid;
            $db->setQuery($query);
            $c_s_id = $db->loadResult();
            if ($c_s_id)
                $servercompanyid = $c_s_id;
        }
        $returnvalue = $this->companyCanDelete($companyid, $uid);
        if ($returnvalue == 1) {
            if (!$row->delete($companyid)) {
                $this->setError($row->getErrorMsg());
                return false;
            }
            $query = "DELETE FROM `#__js_job_companycities` WHERE companyid = " . $companyid;
            $db->setQuery($query);
            if (!$db->query()) {
                return false;
            }
            $this->common_model->deleteUserFieldData($companyid);
            if ($servercompanyid != 0) {
                $data = array();
                $data['id'] = $servercompanyid;
                $data['referenceid'] = $companyid;
                $data['uid'] = $this->_uid;
                $data['authkey'] = $this->_client_auth_key;
                $data['siteurl'] = $this->_siteurl;
                $data['task'] = 'deletecompany';
                $jsjobsharingobject = new JSJobsModelJob_Sharing;
                $return_value = $jsjobsharingobject->delete_CompanySharing($data);
                return $return_value;
            }
        }
        else
            return $returnvalue; // company can not delete	

        return true;
    }

    function companyCanDelete($companyid, $uid) {
        $db = &$this->getDBO();
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if ((is_numeric($companyid) == false) || ($companyid == 0) || ($companyid == ''))
            return false;
        $result = array();

        $query = "SELECT COUNT(company.id) FROM `#__js_job_companies` AS company  
					WHERE company.id = " . $companyid . " AND company.uid = " . $uid;
        //echo '<br> SQL '.$query;
        $db->setQuery($query);
        $comtotal = $db->loadResult();

        if ($comtotal > 0) { // this company is same user
            $query = "SELECT 
                        ( SELECT COUNT(id) FROM `#__js_job_jobs` WHERE companyid = " . $companyid . ") 
                        + ( SELECT COUNT(id) FROM `#__js_job_departments` WHERE companyid = " . $companyid . ")
                        + ( SELECT COUNT(id) FROM `#__js_job_companies` AS fc WHERE fc.isfeaturedcompany=1 AND fc.id = " . $companyid . ") 
                        + ( SELECT COUNT(id) FROM `#__js_job_companies` AS gc WHERE gc.isgoldcompany=1 AND gc.id = " . $companyid . ")
                        AS total ";
            $db->setQuery($query);
            $total = $db->loadResult();

            if ($total > 0)
                return 2;
            else
                return 1;
        }
        else
            return 3; // 	this company is not of this user		
    }

    function deleteFolder($folderid, $uid) {
        $row = &$this->getTable('folder');
        $db = &$this->getDBO();
        $data = JRequest :: get('post');
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (is_numeric($folderid) == false)
            return false;
        $serverfolderid = 0;
        if ($this->_client_auth_key != '') {
            $serverfolderid = $folderid;
            $query = "SELECT folder.id AS id FROM `#__js_job_folders` AS folder  
						WHERE folder.serverid = " . $folderid;
            $db->setQuery($query);
            $c_folder_id = $db->loadResult();
            $folderid = $c_folder_id;
        }
        $returnvalue = $this->folderCanDelete($folderid, $uid);
        if ($returnvalue == 1) {
            if (!$row->delete($folderid)) {
                $this->setError($row->getErrorMsg());
                return false;
            }
            if ($serverfolderid != 0) {
                $data = array();
                $data['id'] = $serverfolderid;
                $data['referenceid'] = $folderid;
                $data['uid'] = $this->_uid;
                $data['authkey'] = $this->_client_auth_key;
                $data['siteurl'] = $this->_siteurl;
                $data['task'] = 'deletefolder';
                $jsjobsharingobject = new JSJobsModelJob_Sharing;
                $return_value = $jsjobsharingobject->delete_FolderSharing($data);
                return $return_value;
            }
        }
        else
            return $returnvalue; // company can not delete	

        return true;
    }

    function folderCanDelete($folderid, $uid) {
        $db = &$this->getDBO();
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (is_numeric($folderid) == false)
            return false;
        $result = array();

        $query = "SELECT COUNT(folder.id) FROM `#__js_job_folders` AS folder
					WHERE folder.id = " . $folderid . " AND folder.uid = " . $uid;

        $db->setQuery($query);
        $comtotal = $db->loadResult();


        if ($comtotal > 0) { // this department is same user
            $query = "SELECT COUNT(folderresume.id) FROM `#__js_job_folderresumes` AS folderresume
						WHERE folderresume.folderid = " . $folderid;

            $db->setQuery($query);
            $total = $db->loadResult();

            if ($total > 0)
                return 2;
            else
                return 1;
        }
        else
            return 3; // 	this department is not of this user
    }

    function deleteDepartment($departmentid, $uid) {
        $db = &$this->getDBO();
        $row = &$this->getTable('department');
        $data = JRequest :: get('post');
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (is_numeric($departmentid) == false)
            return false;
        $serverdepartmentid = 0;
        if ($this->_client_auth_key != "") {
            $query = "SELECT dep.serverid AS id FROM `#__js_job_departments` AS dep  
						WHERE dep.id = " . $departmentid;
            $db->setQuery($query);
            $s_dep_id = $db->loadResult();
            $serverdepartmentid = $s_dep_id;
        }
        $returnvalue = $this->departmentCanDelete($departmentid, $uid);
        if ($returnvalue == 1) {
            if (!$row->delete($departmentid)) {
                $this->setError($row->getErrorMsg());
                return false;
            }
            if ($serverdepartmentid != 0) {
                $data = array();
                $data['id'] = $serverdepartmentid;
                $data['referenceid'] = $departmentid;
                $data['uid'] = $this->_uid;
                $data['authkey'] = $this->_client_auth_key;
                $data['siteurl'] = $this->_siteurl;
                $data['task'] = 'deletedepartment';
                $jsjobsharingobject = new JSJobsModelJob_Sharing;
                $return_value = $jsjobsharingobject->delete_DepartmentSharing($data);
                return $return_value;
            }
        }
        else
            return $returnvalue; // department can not delete	

        return true;
    }

    function departmentCanDelete($departmentid, $uid) {
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        $db = &$this->getDBO();
        $result = array();

        $query = "SELECT COUNT(department.id) FROM `#__js_job_departments` AS department  
					WHERE department.id = " . $departmentid . " AND department.uid = " . $uid;
        //echo '<br> SQL '.$query;
        $db->setQuery($query);
        $comtotal = $db->loadResult();

        if ($comtotal > 0) { // this department is same user
            $query = "SELECT COUNT(job.id) FROM `#__js_job_jobs` AS job  
						WHERE job.departmentid = " . $departmentid;
            //echo '<br> SQL '.$query;
            $db->setQuery($query);
            $total = $db->loadResult();

            if ($total > 0)
                return 2;
            else
                return 1;
        }
        else
            return 3; // 	this department is not of this user		
    }

    function storeJob() { //store job
        $row = &$this->getTable('job');
        $data = JRequest :: get('post');
        $curdate = date('Y-m-d H:i:s');
        $db = &$this->getDBO();

        if (isset($this_config) == false)
            $this->_config = $this->common_model->getConfig('');
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'jobautoapprove')
                $configvalue = $conf->configvalue;
            if ($conf->configname == 'date_format')
                $dateformat = $conf->configvalue;
        }
        if ($data['id'] == '') { // only for new job
            $data['status'] = $configvalue;
        }

        if (($data['enforcestoppublishjob'] == 1)) {
            if ($data['enforcestoppublishjobtype'] == 1) {
                $data['stoppublishing'] = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($data['startpublishing'])) . " +" . $data['enforcestoppublishjobvalue'] . " day"));
            } elseif ($data['enforcestoppublishjobtype'] == 2) {
                $data['stoppublishing'] = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($data['startpublishing'])) . " +" . $data['enforcestoppublishjobvalue'] . " week"));
            } elseif ($data['enforcestoppublishjobtype'] == 3) {
                $data['stoppublishing'] = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($data['startpublishing'])) . " +" . $data['enforcestoppublishjobvalue'] . " month"));
            }
        }

        if ($dateformat == 'm/d/Y') {
            $arr = explode('/', $data['startpublishing']);
            $data['startpublishing'] = $arr[2] . '/' . $arr[0] . '/' . $arr[1];
            $arr = explode('/', $data['stoppublishing']);
            $data['stoppublishing'] = $arr[2] . '/' . $arr[0] . '/' . $arr[1];
        } elseif ($dateformat == 'd-m-Y' OR $dateformat == 'Y-m-d') {
            $arr = explode('-', $data['startpublishing']);
            $data['startpublishing'] = $arr[2] . '-' . $arr[1] . '-' . $arr[0];
            $arr = explode('-', $data['stoppublishing']);
            $data['stoppublishing'] = $arr[2] . '-' . $arr[1] . '-' . $arr[0];
        }
	   
	   $data['startpublishing'] = date('Y-m-d H:i:s', strtotime($data['startpublishing']));
        $data['stoppublishing'] = date('Y-m-d H:i:s', strtotime($data['stoppublishing']));

        // add time
        $spdate = explode("-", $data['startpublishing']);
        if ($spdate[2])
            $spdate[2] = explode(' ', $spdate[2]);
        $spdate[2] = $spdate[2][0];

        $curtime = explode(":", date('H:i:s'));

        $datetime = mktime($curtime[0], $curtime[1], $curtime[2], $spdate[1], $spdate[2], $spdate[0]);
        $data['startpublishing'] = date('Y-m-d H:i:s', $datetime);

        if ($this->_job_editor == 1) {
            $data['description'] = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);
            $data['qualifications'] = JRequest::getVar('qualifications', '', 'post', 'string', JREQUEST_ALLOWRAW);
            $data['prefferdskills'] = JRequest::getVar('prefferdskills', '', 'post', 'string', JREQUEST_ALLOWRAW);
            $data['agreement'] = JRequest::getVar('agreement', '', 'post', 'string', JREQUEST_ALLOWRAW);
        }

        // random generated jobid
        if (!empty($data['alias']))
            $jobalias = $data['alias'];
        else
            $jobalias = $data['title'];

        $jobalias = strtolower(str_replace(' ', '-', $jobalias));
        $data['alias'] = $jobalias;
        $data['jobid'] = $this->getJobId();

        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            echo $this->_db->getErrorMsg();
            return false;
        }

        $check_return = $row->check();

        if ($check_return != 1) {
            $this->setError($this->_db->getErrorMsg());
            echo $this->_db->getErrorMsg();
            return $check_return;
        }

        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());
            echo $this->_db->getErrorMsg();
            return false;
        }

        if (isset($data['goldjob']) && $data['goldjob'] == true) {
            $this->storeGoldJobs($data['uid'], $row->id);
        }
        if (isset($data['featuredjob']) && $data['featuredjob'] == true) {
            $this->storeFeaturedJobs($data['uid'], $row->id);
        }
        if ($data['city'])
            $storemulticity = $this->storeMultiCitiesJob($data['city'], $row->id);
        if (isset($storemulticity) AND ($storemulticity == false))
            return false;
        $this->common_model->storeUserFieldData($data, $row->id);


        if ($data['id'] == '') { // only for new job
            $this->common_model->sendMailtoAdmin($row->id, $data['uid'], 2);
            if ($data['status'] == 1) { // if job approved
            }
        }

        if ($this->_client_auth_key != "") {
            $query = "SELECT job.* FROM `#__js_job_jobs` AS job  
						WHERE job.id = " . $row->id;
            $db->setQuery($query);
            $data_job = $db->loadObject();
            if ($data['id'] != "" AND $data['id'] != 0)
                $data_job->id = $data['id']; // for edit case
            $data_job->job_id = $row->id;
            $data_job->authkey = $this->_client_auth_key;

            $data_job->task = 'storejob';
            $jsjobsharingobject = new JSJobsModelJob_Sharing;
            $return_value = $jsjobsharingobject->store_JobSharing($data_job);
            $this->updateJobTemp();
            return $return_value;
        }else {
            return true;
        }
    }

    function storeMultiCitiesJob($city_id, $jobid) { // city id comma seprated 
        $db = & JFactory::getDBO();
        if (!is_numeric($jobid))
            return false;
        $query = "SELECT cityid FROM #__js_job_jobcities WHERE jobid = " . $jobid;
        $db->setQuery($query);
        $old_cities = $db->loadObjectList();
        $id_array = explode(",", $city_id);
        $row = &$this->getTable('jobcities');
        $error = array();
        foreach ($old_cities AS $oldcityid) {
            $match = false;
            foreach ($id_array AS $cityid) {
                if ($oldcityid->cityid == $cityid) {
                    $match = true;
                    break;
                }
            }
            if ($match == false) {
                $query = "DELETE FROM #__js_job_jobcities WHERE jobid = " . $jobid . " AND cityid=" . $oldcityid->cityid;
                $db->setQuery($query);
                if (!$db->query()) {
                    $err = $this->setError($this->_db->getErrorMsg());
                    $error[] = $err;
                }
            }
        }
        foreach ($id_array AS $cityid) {
            $insert = true;
            foreach ($old_cities AS $oldcityid) {
                if ($oldcityid->cityid == $cityid) {
                    $insert = false;
                    break;
                }
            }
            if ($insert) {
                $row->id = "";
                $row->jobid = $jobid;
                $row->cityid = $cityid;
                if (!$row->store()) {
                    $err = $this->setError($this->_db->getErrorMsg());
                    $error[] = $err;
                }
            }
        }
        if (!empty($error))
            return false;

        return true;
    }

    function storeResumeSearch($data) {
        global $resumedata;
        $row = &$this->getTable('resumesearch');
        $data['date_start'] = date('Y-m-d H:i:s', strtotime($data['date_start']));
        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        $returnvalue = $this->canAddNewResumeSearch($data['uid']);

        if ($returnvalue == 0)
            return 3; //not allowed save new search
        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());
            echo $this->_db->getErrorMsg();
            return false;
        }
        return true;
    }

    function canAddNewResumeSearch($uid) {
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (!isset($this->_config)) {
            $this->_config = $this->common_model->getConfig('');
        }
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'newlisting_requiredpackage')
                $newlisting_required_package = $conf->configvalue;
        }

        if ($newlisting_required_package == 0) {
            return 1;
        } else {
            $db = &$this->getDBO();
            $query = "SELECT COUNT(search.id) AS totalsearches, role.savesearchresume
			FROM `#__js_job_roles` AS role
			JOIN `#__js_job_userroles` AS userrole ON userrole.role = role.id
			LEFT JOIN `#__js_job_resumesearches` AS search ON userrole.uid = search.uid 
			WHERE userrole.uid = " . $uid . " GROUP BY role.savesearchresume ";

            $db->setQuery($query);
            $resume = $db->loadObject();
            if ($resume) {
                if ($resume->savesearchresume == -1)
                    return 1;
                else {
                    if ($resume->totalsearch < $resume->savesearchresume)
                        return 1;
                    else
                        return 0;
                }
            }
            return 0;
        }
    }

    function deleteResumeSearch($searchid, $uid) {

        $db = &$this->getDBO();
        $row = &$this->getTable('resumesearch');
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (is_numeric($searchid) == false)
            return false;

        $query = "SELECT COUNT(search.id) FROM `#__js_job_resumesearches` AS search  
					WHERE search.id = " . $searchid . " AND search.uid = " . $uid;
        $db->setQuery($query);
        $searchtotal = $db->loadResult();

        if ($searchtotal > 0) { // this search is same user
            if (!$row->delete($searchid)) {
                $this->setError($row->getErrorMsg());
                return false;
            }
        }
        else
            return 2;

        return true;
    }

    function deleteJob($jobid, $uid, $vis_email, $vis_jobid) {
        $db = &$this->getDBO();
        $row = &$this->getTable('job');
        $serverjodid = 0;
        if (($vis_email == '') || ($vis_jobid == '')) { // if jobseeker try to delete their job
            if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
                return false;
            if (is_numeric($jobid) == false)
                return false;
            if ($this->_client_auth_key != "") {
                $query = "SELECT job.serverid AS id 
								FROM `#__js_job_jobs` AS job
								WHERE job.id = " . $jobid;
                $db->setQuery($query);
                $s_job_id = $db->loadResult();
                $serverjodid = $s_job_id;
            }
        } else {
            if ($this->_client_auth_key != "") {
                $query = "SELECT job.serverid AS id FROM `#__js_job_jobs` AS job
							JOIN `#__js_job_companies` AS company ON company.id = job.companyid AND company.contactemail = " . $db->quote($vis_email) . "
							WHERE job.jobid = " . $db->quote($vis_jobid);
                $db->setQuery($query);
                $s_job_id = $db->loadResult();
                $serverjodid = $s_job_id;
            }
        }
        $returnvalue = $this->jobCanDelete($jobid, $uid, $vis_email, $vis_jobid);
        if ($returnvalue == 1) {
            if (($vis_email == '') || ($vis_jobid == '')) { // if jobseeker try to delete their job
                if (!$row->delete($jobid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
                $this->common_model->deleteUserFieldData($jobid);
            } else {
                $query = "SELECT job.id AS id FROM `#__js_job_jobs` AS job
						JOIN `#__js_job_companies` AS company ON company.id = job.companyid AND company.contactemail = " . $db->quote($vis_email) . "
						WHERE job.jobid = " . $db->quote($vis_jobid);
                $db->setQuery($query);
                $jobid = $db->loadResult();
                $query = "DELETE FROM `#__js_job_jobs` WHERE jobid = " . $db->quote($vis_jobid);
                $db->setQuery($query);
                if (!$db->query()) {
                    return false;
                }
                $this->common_model->deleteUserFieldData($jobid);
            }
            $query = "DELETE FROM `#__js_job_jobcities` WHERE jobid = " . $jobid;
            $db->setQuery($query);
            if (!$db->query()) {
                return false;
            }
            if ($serverjodid != 0) {
                $data = array();
                $data['id'] = $serverjodid;
                $data['referenceid'] = $jobid;
                $data['uid'] = $this->_uid;
                $data['authkey'] = $this->_client_auth_key;
                $data['siteurl'] = $this->_siteurl;
                $data['task'] = 'deletejob';
                $jsjobsharingobject = new JSJobsModelJob_Sharing;
                $return_value = $jsjobsharingobject->delete_JobSharing($data);
				$this->updateJobTemp();
                return $return_value;
            }
        }
        else
            return $returnvalue;

        return true;
    }

    function jobCanDelete($jobid, $uid, $vis_email, $vis_jobid) {
        if (is_numeric($uid) == false)
            return false;
        $db = &$this->getDBO();
        if ($jobid)
            if (is_numeric($jobid) == false)
                return false;
        if ((isset($vis_email) && $vis_email != '') && (isset($vis_jobid) && $vis_jobid != '')) {
            $query = "SELECT COUNT(job.id) FROM `#__js_job_jobs` AS job
                                JOIN `#__js_job_companies` AS company ON company.id = job.companyid AND company.contactemail = " . $db->quote($vis_email) . "
                                WHERE job.jobid = " . $db->quote($vis_jobid);
        } else {
            $query = "SELECT COUNT(job.id) FROM `#__js_job_jobs` AS job
                                WHERE job.id = " . $jobid . " AND job.uid = " . $uid;
        }
        $db->setQuery($query);
        $jobtotal = $db->loadResult();

        if ($jobtotal > 0) { // this job is same user
            $query = "SELECT COUNT(apply.id) FROM `#__js_job_jobapply` AS apply
                                    WHERE apply.jobid = " . $jobid;

            $query = "SELECT
                                    ( SELECT COUNT(id) FROM `#__js_job_jobapply` WHERE jobid = " . $jobid . ")
                                    + ( SELECT COUNT(id) FROM `#__js_job_jobs` AS fj WHERE fj.isfeaturedjob=1 AND fj.id = " . $jobid . ")
                                    + ( SELECT COUNT(id) FROM `#__js_job_jobs` AS gj WHERE gj.isgoldjob=1 AND gj.id = " . $jobid . ")
                                    AS total ";
            $db->setQuery($query);
            $total = $db->loadResult();

            if ($total > 0)
                return 2;
            else
                return 1;
        }
        else
            return 3; // 	this job is not of this user		
    }

    function canAddMessage($uid) {
        $db = &$this->getDBO();
        $returnvalue = array();
        $packagedetail = array();
        if (($uid == 0) || ($uid == '')) {
            return false;
        }
        if (!isset($this->_config)) {
            $this->_config = $this->common_model->getConfig('');
        }
        $newlisting_required_package = 1;
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'newlisting_requiredpackage')
                $newlisting_required_package = $conf->configvalue;
        }
        if ($newlisting_required_package == 0) {
            return true;
        } else {
            $query = "SELECT package.id, package.messageallow, package.packageexpireindays, payment.id AS paymentid, payment.created
			FROM `#__js_job_employerpackages` AS package
			JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=1)
			WHERE payment.uid = " . $uid . "
			AND DATE_ADD(payment.created,INTERVAL package.packageexpireindays DAY) >= CURDATE()
			AND payment.transactionverified = 1 AND payment.status = 1";
            $db->setQuery($query);
            $packages = $db->loadObjectList();
            $allow = 0;
            foreach ($packages AS $package) {
                if ($allow == 0) {
                    if ($package->messageallow == 1) {
                        $allow = true;
                        return true;
                    }
                }
            }
            return $allow;
        }
    }

    function getMultiCityData($jobid) {
        $db = &$this->getDBO();
        $query = "select mjob.*,city.id AS cityid,city.name AS cityname ,state.name AS statename,country.name AS countryname
                    from #__js_job_jobcities AS mjob
                    LEFT join #__js_job_cities AS city on mjob.cityid=city.id  
                    LEFT join #__js_job_states AS state on city.stateid=state.id  
                    LEFT join #__js_job_countries AS country on city.countryid=country.id 
                    WHERE mjob.jobid=" . $jobid;
        $db->setQuery($query);
        $data = $db->loadObjectList();
        if (is_array($data) AND !empty($data)) {
            $i = 0;
            $multicitydata = "";
            foreach ($data AS $multicity) {
                $last_index = count($data) - 1;
                if ($i == $last_index)
                    $multicitydata.=$multicity->cityname;
                else
                    $multicitydata.=$multicity->cityname . " ,";
                $i++;
            }
            if ($multicitydata != "") {
                $multicity = (strlen($multicitydata) > 35) ? substr($multicitydata, 0, 35) . '...' : $multicitydata;
                return $multicity;
            }
            else
                return;
        }
    }

    function storeShortListCandidate($uid, $data) {
        global $resumedata;
        if (is_numeric($data['resumeid']) == false)
            return false;
        if (is_numeric($data['jobid']) == false)
            return false;
        if (is_numeric($uid) == false)
            return false;
        if ($this->_client_auth_key != "") {
            $db = & JFactory::getDBO();
            $query = "SELECT id FROM #__js_job_resume 
			WHERE serverid = " . $data['resumeid'];

            $db->setQuery($query);
            $result = $db->loadResult();
            if (!$result)
                $is_own_resume = 0;
            else {
                $is_own_resume = 1;
                $data['resumeid'] = $result;
            }

            $query = "SELECT id FROM #__js_job_jobs 
			WHERE serverid = " . $data['jobid'];

            $db->setQuery($query);
            $result1 = $db->loadResult();
            if (!$result1)
                $is_own_job = 0;
            else {
                $is_own_job = 1;
                $data['jobid'] = $result1;
            }
        } else {
            $is_own_resume = 1;
            $is_own_job = 1;
        }
        if ($is_own_resume == 1 AND $is_own_job == 1) {
            if ($this->shortListCandidateValidation($uid, $data['jobid'], $data['resumeid']) == false)
                return 3;
            $row = &$this->getTable('shortlistcandidate');
            $row->uid = $uid;
            $row->jobid = $data['jobid'];
            $row->cvid = $data['resumeid'];
            $row->status = 0;
            $row->created = date('Y-m-d H:i:s');
            if (!$row->check()) {
                $this->setError($this->_db->getErrorMsg());
                return 2;
            }
            if (!$row->store()) {
                $this->setError($this->_db->getErrorMsg());
                echo $this->_db->getErrorMsg();
                return false;
            }
        }
        if ($this->_client_auth_key != "") {
            if ($is_own_resume == 1 AND $is_own_job == 1) { // own Resume Rating 
                //if($is_own_job==1){  
                if ($data['jobid'] != "" AND $data['jobid'] != 0) {
                    $query = "select job.serverid AS serverid 
							From #__js_job_jobs AS job
							WHERE job.id=" . $data['jobid'];
                    $db->setQuery($query);
                    $job_serverid = $db->loadResult();
                    if ($job_serverid)
                        $data['jobid'] = $job_serverid;
                    else
                        $data['jobid'] = 0;
                }
                if ($data['resumeid'] != "" AND $data['resumeid'] != 0) {
                    $query = "select resume.serverid AS serverid 
							From #__js_job_resume AS resume
							WHERE resume.id=" . $data['resumeid'];
                    //echo 'query'.$query;
                    $db->setQuery($query);
                    $resume_serverid = $db->loadResult();
                    if ($resume_serverid)
                        $data['cvid'] = $resume_serverid;
                    else
                        $data['cvid'] = 0;
                }
                $data['uid'] = $uid;
                $data['shortlistcandidate_id'] = $row->id;
                $data['status'] = 0;
                $data['created'] = date('Y-m-d H:i:s');
                $data['authkey'] = $this->_client_auth_key;
                $data['task'] = 'storeownshortlistcandidates';
                $shortlistcandidates = 1;
                $data['isownshortlistcandidates'] = $shortlistcandidates;
                $jsjobsharingobject = new JSJobsModelJob_Sharing;
                $return_value = $jsjobsharingobject->store_ShortlistcandidatesSharing($data);
                return $return_value;
            }else {  // server storeShortlistcandidatesSharing on job sharing 
                $data['uid'] = $uid;
                $data['cvid'] = $data['resumeid'];
                $data['status'] = 0;
                $data['authkey'] = $this->_client_auth_key;
                $data['created'] = date('Y-m-d H:i:s');
                $data['task'] = 'storeservershortlistcandidates';
                $isownshortlistcandidates = 0;
                $data['isownshortlistcandidates'] = $isownshortlistcandidates;
                $jsjobsharingobject = new JSJobsModelJob_Sharing;
                $return_value = $jsjobsharingobject->store_ShortlistcandidatesSharing($data);
                return $return_value;
            }
        } else {
            return true;
        }
    }

    function shortListCandidateValidation($uid, $jobid, $resumeid) {
        $db = & JFactory::getDBO();
        $query = "SELECT COUNT(id) FROM #__js_job_shortlistcandidates
		WHERE  jobid = " . $jobid . " AND cvid = " . $resumeid;
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return true;
        else
            return false;
    }

    function storeFeaturedCompany($uid, $companyid) {
        global $resumedata;
        if ((is_numeric($companyid) == false) || ($companyid == 0) || ($companyid == ''))
            return false;
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;

        $db = &$this->getDBO();
        $query = "SELECT COUNT(id)
				FROM `#__js_job_companies`
				WHERE uid = " . $uid . " AND id = " . $companyid . " AND status = 1";
        $db->setQuery($query);
        $jobs = $db->loadResult();
        if ($jobs <= 0)
            return 3; // company not exsit or not approved

        if ($this->canAddNewFeaturedCompany($uid) == false)
            return 5; // can not add new gold comapuny

        $result = $this->featuredCompanyValidation($uid, $companyid);
        if ($result == false) {
            return 6;
        } else {
            $query = "UPDATE `#__js_job_companies` SET isfeaturedcompany = 1,startfeatureddate=CURDATE() WHERE id = " . $companyid . " AND uid = " . $uid;
            $db->setQuery($query);
            if (!$db->query())
                return false;
            else
                return true;
        }
    }

    function storeGoldCompany($uid, $companyid) {
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if ((is_numeric($companyid) == false) || ($companyid == 0) || ($companyid == ''))
            return false;
        $db = &$this->getDBO();
        $query = "SELECT COUNT(id)
				FROM `#__js_job_companies`
				WHERE uid = " . $uid . " AND id = " . $companyid . " AND status = 1";
        $db->setQuery($query);
        $jobs = $db->loadResult();
        if ($jobs <= 0)
            return 3; // company not exsit or not approved

        if ($this->canAddNewGoldCompany($uid) == false)
            return 5; // can not add new gold comapuny

        $result = $this->goldCompanyValidation($uid, $companyid);
        if ($result == false) {
            return 6;
        } else {
            $query = "UPDATE `#__js_job_companies` SET isgoldcompany = 1,startgolddate=CURDATE() WHERE id = " . $companyid . " AND uid = " . $uid;
            $db->setQuery($query);
            if (!$db->query())
                return false;
            else
                return true;
        }
    }

    function storeCompany() { //store company
        $row = &$this->getTable('company');
        $data = JRequest :: get('post');
        $filerealpath = "";
        if (!$this->_config)
            $this->_config = $this->common_model->getConfig('');
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'companyautoapprove')
                $data['status'] = $conf->configvalue;
            if ($conf->configname == 'company_logofilezize')
                $logofilesize = $conf->configvalue;
            if ($conf->configname == 'date_format')
                $dateformat = $conf->configvalue;
        }

        if ($dateformat == 'm-d-Y') {
            $arr = explode('-', $data['since']);
            $data['since'] = $arr[0] . '/' . $arr[1] . '/' . $arr[2];
        } elseif ($dateformat == 'd-m-Y') {
            $arr = explode('-', $data['since']);
            $data['since'] = $arr[2] . '-' . $arr[1] . '-' . $arr[0];
        }
        $data['since'] = date('Y-m-d H:i:s', strtotime($data['since']));

        if ($this->_comp_editor == 1) {
            $data['description'] = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);
        }
        if ($_FILES['logo']['size'] > 0) { // logo
            $uploadfilesize = $_FILES['logo']['size'];
            $uploadfilesize = $uploadfilesize / 1024; //kb
            if ($uploadfilesize > $logofilesize) { // logo
                return 5; // file size error	
            }
        }
        if ($_FILES['logo']['size'] > 0) { // logo
            $data['logofilename'] = $_FILES['logo']['name']; // file name
            $data['logoisfile'] = 1; // logo store in file system
        }
        if (isset($data['deletelogo']) AND $data['deletelogo'] == 1) { // delete logo
            $data['logofilename'] = ''; // file name
            $data['logoisfile'] = -1; // no logo
        }

        if (isset($_FILES['smalllogo']['size']) AND $_FILES['smalllogo']['size'] > 0) { //small logo
            $data['smalllogofilename'] = $_FILES['smalllogo']['name']; // file name
            $data['smalllogoisfile'] = 1; // logo store in file system
        }
        if (isset($data['deletesmalllogo']) AND $data['deletesmalllogo'] == 1) { //delete small logo
            $data['smalllogofilename'] = ''; // file name
            $data['smalllogoisfile'] = -1; // no logo
        }

        if (isset($_FILES['aboutcompany']['size']) AND $_FILES['aboutcompany']['size'] > 0) { //about company
            $data['aboutcompanyfilename'] = $_FILES['aboutcompany']['name']; // file name
            $data['aboutcompanyisfile'] = 1; // logo store in file system
        }
        if (isset($data['deleteaboutcompany']) AND $data['deleteaboutcompany'] == 1) { // delete about company
            $data['aboutcompanyfilename'] = ''; // file name
            $data['aboutcompanyisfile'] = -1; // no logo
        }
        if (!empty($data['alias']))
            $companyalias = $data['alias'];
        else
            $companyalias = $data['name'];

        $companyalias = strtolower(str_replace(' ', '-', $companyalias));
        $data['alias'] = $companyalias;

        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        if (!$row->check()) {
            $this->setError($this->_db->getErrorMsg());
            return 2;
        }
        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        $this->common_model->storeUserFieldData($data, $row->id);

        $companyid = $row->id;
        $filemismatch = 0;
        if ($_FILES['logo']['size'] > 0) { // logo
            $data['logofilename'] = $_FILES['logo']['name']; // file name
            $data['logoisfile'] = 1; // logo store in file system
            $returnvalue = $this->common_model->uploadFile($companyid, 1, 0);
            if ($returnvalue == 6)
                $filemismatch = 1;
            $filerealpath = $returnvalue;
        }
        if (isset($data['deletelogo']) AND $data['deletelogo'] == 1) { // delete logo
            $data['logofilename'] = ''; // file name
            $data['logoisfile'] = -1; // no logo
            $returnvalue = $this->common_model->uploadFile($companyid, 1, 1);
            if ($returnvalue == 6)
                $filemismatch = 1;
        }

        if (isset($_FILES['smalllogo']['size']) AND $_FILES['smalllogo']['size'] > 0) { //small logo
            $data['smalllogofilename'] = $_FILES['smalllogo']['name']; // file name
            $data['smalllogoisfile'] = 1; // logo store in file system
            $returnvalue = $this->common_model->uploadFile($companyid, 2, 0);
            if ($returnvalue == 6)
                $filemismatch = 1;
        }
        if (isset($data['deletesmalllogo']) AND $data['deletesmalllogo'] == 1) { //delete small logo
            $data['smalllogofilename'] = ''; // file name
            $data['smalllogoisfile'] = -1; // no logo
            $returnvalue = $this->common_model->uploadFile($companyid, 2, 1);
            if ($returnvalue == 6)
                $filemismatch = 1;
        }

        if (isset($_FILES['aboutcompany']['size']) AND $_FILES['aboutcompany']['size'] > 0) { //about company
            $data['aboutcompanyfilename'] = $_FILES['aboutcompany']['name']; // file name
            $data['aboutcompanyisfile'] = 1; // logo store in file system
            $returnvalue = $this->common_model->uploadFile($companyid, 3, 0);
            if ($returnvalue == 6)
                $filemismatch = 1;
        }
        if (isset($data['deleteaboutcompany']) AND $data['deleteaboutcompany'] == 1) { // delete about company
            $data['aboutcompanyfilename'] = ''; // file name
            $data['aboutcompanyisfile'] = -1; // no logo
            $returnvalue = $this->common_model->uploadFile($companyid, 3, 1);
            if ($returnvalue == 6)
                $filemismatch = 1;
        }

        if ($data['city'])
            $storemulticity = $this->storeMultiCitiesCompany($data['city'], $row->id);
        if (isset($storemulticity) AND ($storemulticity == false))
            return false;
        if ($data['id'] == '')
            $this->common_model->sendMailtoAdmin($companyid, $data['uid'], 1); //only for new


        if ($this->_client_auth_key != "") {

            $company_logo = array();

            $db = &$this->getDBO();
            $query = "SELECT company.* FROM `#__js_job_companies` AS company  
						WHERE company.id = " . $row->id;
            $db->setQuery($query);
            $data_company = $db->loadObject();
            if ($data['id'] != "" AND $data['id'] != 0)
                $data_company->id = $data['id']; // for edit case
            if ($_FILES['logo']['size'] > 0)
                $company_logo['logofilename'] = $filerealpath;
            $data_company->company_id = $row->id;
            $data_company->authkey = $this->_client_auth_key;
            $data_company->task = 'storecompany';
            $jsjobsharingobject = new JSJobsModelJob_Sharing;
            $return_value = $jsjobsharingobject->store_CompanySharing($data_company);
            if ($return_value['iscompanystore'] == 0)
                return $return_value;
            if ($filemismatch != 1) {
                if ($_FILES['logo']['size'] > 0)
                    $return_value_company_logo = $jsjobsharingobject->store_CompanyLogoSharing($data_company, $company_logo);
            }
            if (is_array($return_value) AND !empty($return_value) AND is_array($return_value_company_logo) AND !empty($return_value_company_logo)) {
                $company_logo_return_value = (array_merge($return_value, $return_value_company_logo));
                return $company_logo_return_value;
            } else {
                return $return_value;
            }
        } else {
            if ($filemismatch == 1) {
                $row->id = $row->id;
                $row->logofilename = "";
                $row->logoisfile = "";
                if (!$row->store()) {
                    $this->setError($this->_db->getErrorMsg());
                }
                return 6;
            }
            return true;
        }
    }

    function storeMultiCitiesCompany($city_id, $companyid) { // city id comma seprated 
        if (!is_numeric($companyid))
            return false;
        $db = & JFactory::getDBO();
        $query = "SELECT cityid FROM #__js_job_companycities WHERE companyid = " . $companyid;
        $db->setQuery($query);
        $old_cities = $db->loadObjectList();
        $id_array = explode(",", $city_id);
        $row = &$this->getTable('companycities');
        $error = array();

        foreach ($old_cities AS $oldcityid) {
            $match = false;
            foreach ($id_array AS $cityid) {
                if ($oldcityid->cityid == $cityid) {
                    $match = true;
                    break;
                }
            }
            if ($match == false) {
                $query = "DELETE FROM #__js_job_companycities WHERE companyid = " . $companyid . " AND cityid=" . $oldcityid->cityid;
                $db->setQuery($query);
                if (!$db->query()) {
                    $err = $this->setError($this->_db->getErrorMsg());
                    $error[] = $err;
                }
            }
        }
        foreach ($id_array AS $cityid) {
            $insert = true;
            foreach ($old_cities AS $oldcityid) {
                if ($oldcityid->cityid == $cityid) {
                    $insert = false;
                    break;
                }
            }
            if ($insert) {
                $row->id = "";
                $row->companyid = $companyid;
                $row->cityid = $cityid;
                if (!$row->store()) {
                    $err = $this->setError($this->_db->getErrorMsg());
                    $error[] = $err;
                }
            }
        }
        if (!empty($error))
            return false;

        return true;
    }

    function &listEmpAddressData($name, $myname, $nextname, $data, $val) {
        $db = &$this->getDBO();
        if ($data == 'country') {  // country
            $query = "SELECT id AS code, name FROM `#__js_job_countries`  WHERE enabled = 'Y'";
            if ($this->_client_auth_key != "")
                $query.=" AND serverid!='' AND serverid!=0";
            $query.=" ORDER BY name ASC";
            $db->setQuery($query);
            $result = $db->loadObjectList();

            if (empty($result)) {
                $return_value = "<input class='inputbox' type='text' name='$name' size='40' maxlength='100'  />";
            } else {

                $return_value = "<select name='$name' onChange=\"dochange(\"$myname\",'state', this.value)\">\n";
                $return_value .= "<option value='0'>" . JText::_('JS_CHOOSE_COUNTRY') . "</option>\n";

                foreach ($result as $row) {
                    $return_value .= "<option value=\"$row->code\" >$row->name</option> \n";
                }
                $return_value .= "</select>\n";
            }
        } else if ($data == 'state') {  // states
            $query = "SELECT id AS code, name from `#__js_job_states`  WHERE enabled = 'Y' AND countryid= " . $val;
            if ($this->_client_auth_key != "")
                $query.=" AND serverid!='' AND serverid!=0";
            $query.=" ORDER BY name ASC";
            $db->setQuery($query);

            $result = $db->loadObjectList();
            if (empty($result)) {
                $return_value = "<input class='inputbox' type='text' name='$name' size='40' maxlength='100'  />";
            } else {
                //$return_value = "<select name='$name' class='inputbox' onChange=\"dochange('$myname','$nextname','','city', this.value)\">\n";
                $return_value = "<select name='$name' class='inputbox' onChange=\"dochange('$myname','','','city', this.value)\">\n";
                $return_value .= "<option value='0'>" . JText::_('JS_CHOOSE_STATE') . "</option>\n";

                foreach ($result as $row) {
                    $return_value .= "<option value=\"$row->code\" >$row->name</option> \n";
                }
                $return_value .= "</select>\n";
            }
        } else if ($data == 'city') { // second dropdown
            $query = "SELECT id AS code, name from `#__js_job_cities`  WHERE enabled = 'Y' AND stateid= " . $val;
            if ($this->_client_auth_key != "")
                $query.=" AND serverid!='' AND serverid!=0";
            $query.=" ORDER BY name ASC";
            $db->setQuery($query);
            $result = $db->loadObjectList();
            if (empty($result)) {
                $return_value = "<input class='inputbox' type='text' name='$name' size='40' maxlength='100'  />";
            } else {
                $return_value = "<select name='$name' class='inputbox' onChange=\"dochange('zipcode', this.value)\">\n";
                $return_value .= "<option value='0'>" . JText::_('JS_CHOOSE_CITY') . "</option>\n";

                foreach ($result as $row) {
                    $return_value .= "<option value=\"$row->code\" >$row->name</option> \n";
                }
                $return_value .= "</select>\n";
            }
        }
        return $return_value;
    }

    function &getMyFoldersAJAX($uid, $jobid, $resumeid, $applyid) {
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (is_numeric($jobid) == false)
            return false;
        if (is_numeric($resumeid) == false)
            return false;
        $option = 'com_jsjobs';
        $db = &$this->getDBO();
        $canview = 0;

        $canadd = $this->canResumeAddintoFolder($uid, $jobid);
        if ($canadd == 1) {
            $myfolders = $this->getMyFoldersForCombo($uid, '');
            if ($myfolders)
                $folders = JHTML::_('select.genericList', $myfolders, 'folderid', 'class="inputbox required" ' . '', 'value', 'text', '');
            else
                $folders = JText::_('YOU_DO_NOT_HAVE_FOLDERS');

            $return_value = "<div id='resumeactionfolder'>\n";
            $return_value .= "<table id='resumeactionfoldertable' cellpadding='0' cellspacing='0'  width='100%'>\n";
            $return_value .= "<tr><td>\n";
            $return_value .= "<table id='resumeactionfoldertable' cellpadding='0' cellspacing='0' border='0' width='100%'>\n";
            $return_value .= "<tr >\n";
            $return_value .= "<td width='30%' align='right' ><b>" . JText::_('JS_FOLDER') . "</b></td>\n";
            $return_value .= "<td width='20%'>" . $folders . " </td>\n";
            if ($myfolders) {
                $return_value .= "<td  align='left'><input type='button' class='button' onclick='saveaddtofolder(" . $applyid . "," . $jobid . "," . $resumeid . ")' value='" . JText::_('JS_ADD') . "'> </td>\n";
            }
            $return_value .= "</tr>\n";
            $return_value .= "</table>\n";
            $return_value .= "</td></tr>\n";
            $return_value .= "</table>\n";
            $return_value .= "</div>\n";
        } else {
            $return_value = "<div id='resumeactionfolder'>\n";
            $return_value .= "<table id='resumeactionfoldertable' cellpadding='0' cellspacing='0'  width='100%'>\n";
            $return_value .= "<tr><td>\n";
            $return_value .= "<table id='resumeactionfoldertable' cellpadding='0' cellspacing='0' border='0' width='100%'>\n";
            $return_value .= "<tr >\n";
            $return_value .= "<td ><b>" . JText::_('JS_YOU_DO_NOT_HAVE_RIGHT') . "</b></td>\n";
            $return_value .= "<td width='20'><input type='button' class='button' onclick='clsaddtofolder(\"resumeaction_$applyid\")' value=" . JText::_('JS_CLOSE') . "> </td>\n";
            $return_value .= "</tr>\n";
            $return_value .= "</table>\n";
            $return_value .= "</td></tr>\n";
            $return_value .= "</table>\n";
            $return_value .= "</div>\n";
        }

        return $return_value;
    }

    function canResumeAddintoFolder($uid, $jobid) {
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (is_numeric($jobid) == false)
            return false;
        $db = &$this->getDBO();
        if ($this->_client_auth_key != "") {
            $query = "SELECT job.id
                FROM `#__js_job_jobs` AS job 
                WHERE job.serverid = " . $jobid;

            $db->setQuery($query);
            $client_jobid = $db->loadResult();
            if ($client_jobid)
                $jobid = $client_jobid;
        }
        if (!isset($this->_config)) {
            $this->_config = $this->common_model->getConfig('');
        }
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'newlisting_requiredpackage')
                $newlisting_required_package = $conf->configvalue;
        }

        if ($newlisting_required_package == 0) {
            return 1;
        } else {
            $canadd = 0;
            $query = "SELECT package.folders, package.packageexpireindays, payment.created
                FROM `#__js_job_employerpackages` AS package
                JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=1)
                JOIN `#__js_job_jobs` AS job ON job.packageid = package.id
                WHERE payment.uid = " . $uid . " AND job.id = " . $jobid;

            $db->setQuery($query);
            $package = $db->loadObject();

            if ($package->folders == -1)
                return 1;
            if ($package->folders > 0)
                return 1;
            else
                return 0;
        }
    }

    function getDepartmentsByCompanyId($companyid, $title) {
        if (!is_numeric($companyid))
            return false;
        $db = & JFactory::getDBO();
        $departments = array();
        if ($companyid) {
            $query = "SELECT id, name FROM `#__js_job_departments` WHERE status = 1 AND companyid = " . $companyid;
            if ($this->_client_auth_key != "")
                $query.=" AND serverstatus='ok'";
            $query.=" ORDER BY name ASC ";
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            if ($db->getErrorNum()) {
                echo $db->stderr();
                return false;
            }

            if ($title)
                $departments[] = array('value' => JText::_(''), 'text' => $title);
            foreach ($rows as $row) {
                $departments[] = array('value' => $row->id, 'text' => JText::_($row->name));
            }
        }
        return $departments;
    }

    function getMyFoldersForCombo($uid, $title) {
        if (!is_numeric($uid))
            return false;
        $db = & JFactory::getDBO();
        $folders = array();
        if ($this->_client_auth_key != "") {
            $query = "SELECT serverid AS id, name FROM `#__js_job_folders` WHERE status = 1 AND uid = " . $uid . " ORDER BY name ASC ";
        } else {
            $query = "SELECT id, name FROM `#__js_job_folders` WHERE status = 1 AND uid = " . $uid . " ORDER BY name ASC ";
        }

        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }

        if ($title)
            $folders[] = array('value' => JText::_(''), 'text' => $title);
        foreach ($rows as $row) {
            $folders[] = array('value' => $row->id, 'text' => JText::_($row->name));
        }
        return $folders;
    }

    function storeCompanyJobForVisitor() {
        $data = JRequest::get('Post');
        $row = $this->getTable('company');
        $jobsharing = new JSJobsModelJob_Sharing;
        $config = $this->common_model->getConfigByFor('default');
        if ($config['job_captcha'] == 1) {
            if (!$this->common_model->performChecks()) {
                $result = 2;
                return $result;
            }
        }

        if (!$this->_config)
            $this->_config = $this->common_model->getConfig('');
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'companyautoapprove')
                $data['companystatus'] = $conf->configvalue;
            if ($conf->configname == 'company_logofilezize')
                $logofilesize = $conf->configvalue;
            if ($conf->configname == 'date_format')
                $dateformat = $conf->configvalue;
        }

        if ($dateformat == 'm-d-Y') {
            $arr = explode('-', $data['companysince']);
            $data['companysince'] = $arr[0] . '/' . $arr[1] . '/' . $arr[2];
        } elseif ($dateformat == 'd-m-Y') {
            $arr = explode('-', $data['companysince']);
            $data['companysince'] = $arr[2] . '-' . $arr[1] . '-' . $arr[0];
        }
        $data['companysince'] = date('Y-m-d H:i:s', strtotime($data['companysince']));

        if (!empty($data['alias']))
            $calias = $data['alias'];
        else
            $calias = $data['companyname'];

        $calias = strtolower(str_replace(' ', '-', $calias));
        $data['alias'] = $calias;

        if ($_FILES['companylogo']['size'] > 0) { // logo
            $uploadfilesize = $_FILES['companylogo']['size'];
            $uploadfilesize = $uploadfilesize / 1024; //kb
            if ($uploadfilesize > $logofilesize) { // logo
                return 5; // file size error
            }
        }
        if ($_FILES['companylogo']['size'] > 0) { // logo
            $data['companylogofilename'] = $_FILES['companylogo']['name']; // file name
            $data['companylogoisfile'] = 1; // logo store in file system
        }
        if ($data['companydeletelogo'] == 1) { // delete logo
            $data['companylogofilename'] = ''; // file name
            $data['companylogoisfile'] = -1; // no logo
        }

        if ($_FILES['companysmalllogo']['size'] > 0) { //small logo
            $data['companysmalllogofilename'] = $_FILES['companysmalllogo']['name']; // file name
            $data['companysmalllogoisfile'] = 1; // logo store in file system
        }
        if ($data['companydeletesmalllogo'] == 1) { //delete small logo
            $data['companysmalllogofilename'] = ''; // file name
            $data['companysmalllogoisfile'] = -1; // no logo
        }

        if ($_FILES['companyaboutcompany']['size'] > 0) { //about company
            $data['companyaboutcompanyfilename'] = $_FILES['companyaboutcompany']['name']; // file name
            $data['companyaboutcompanyisfile'] = 1; // logo store in file system
        }
        if ($data['companydeleteaboutcompany'] == 1) { // delete about company
            $data['companyaboutcompanyfilename'] = ''; // file name
            $data['companyaboutcompanyisfile'] = -1; // no logo
        }
        if ($data['companyid'] != "" AND $data['companyid'] != 0)
            $visitor_company_id = $data['companyid']; //for edit case
        if (!$row->bindCompany($data, $visitor_company_id)) {
            $this->setError($this->_db->getErrorMsg());
            echo $this->_db->getErrorMsg();
            return false;
        }
        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());
            echo $this->_db->getErrorMsg();
            return false;
        }
        if (isset($visitor_company_id) AND $visitor_company_id != '' AND $visitor_company_id != 0)
            $companyid = $visitor_company_id;
        else
            $companyid = $row->id;
        if ($data['companycity'])
            $storemulticity = $this->storeMultiCitiesCompany($data['companycity'], $companyid);
        if (isset($storemulticity) && $storemulticity == false)
            return false;

        if ($_FILES['companylogo']['size'] > 0) { // logo
            $data['companylogofilename'] = $_FILES['companylogo']['name']; // file name
            $data['companylogoisfile'] = 1; // logo store in file system
            $returnvalue = $this->common_model->uploadFile($companyid, 1, 0);
            if ($returnvalue == 6)
                return $returnvalue;
            $filerealpath = $returnvalue;
        }
        if (isset($data['companydeletelogo']) AND $data['companydeletelogo'] == 1) { // delete logo
            $data['companylogofilename'] = ''; // file name
            $data['companylogoisfile'] = -1; // no logo
            $returnvalue = $this->common_model->uploadFile($companyid, 1, 1);
            if ($returnvalue == 6)
                return $returnvalue;
        }

        if (isset($_FILES['companysmalllogo']['size']) AND $_FILES['companysmalllogo']['size'] > 0) { //small logo
            $data['companysmalllogofilename'] = $_FILES['companysmalllogo']['name']; // file name
            $data['smalllogoisfile'] = 1; // logo store in file system
            $returnvalue = $this->common_model->uploadFile($companyid, 2, 0);
            if ($returnvalue == 6)
                return $returnvalue;
        }
        if (isset($data['companydeletesmalllogo']) AND $data['companydeletesmalllogo'] == 1) { //delete small logo
            $data['companysmalllogofilename'] = ''; // file name
            $data['companysmalllogoisfile'] = -1; // no logo
            $returnvalue = $this->common_model->uploadFile($companyid, 2, 1);
            if ($returnvalue == 6)
                return $returnvalue;
        }

        if (isset($_FILES['companyaboutcompany']['size']) AND $_FILES['companyaboutcompany']['size'] > 0) { //about company
            $data['companyaboutcompanyfilename'] = $_FILES['companyaboutcompany']['name']; // file name
            $data['companyaboutcompanyisfile'] = 1; // logo store in file system
            $returnvalue = $this->common_model->uploadFile($companyid, 3, 0);
            if ($returnvalue == 6)
                return $returnvalue;
        }
        if (isset($data['companydeleteaboutcompany']) AND $data['companydeleteaboutcompany'] == 1) { // delete about company
            $data['companyaboutcompanyfilename'] = ''; // file name
            $data['companyaboutcompanyisfile'] = -1; // no logo
            $returnvalue = $this->common_model->uploadFile($companyid, 3, 1);
            if ($returnvalue == 6)
                return $returnvalue;
        }

        $companyfield['userfields_total'] = $data['companyuserfields_total'];
        for ($i = 1; $i <= $companyfield['userfields_total']; $i++) {
            $companyfield['userfields_' . $i] = $data['companyuserfields_' . $i];
            $companyfield['userfields_' . $i . '_id'] = $data['companyuserfields_' . $i . '_id'];
            $companyfield['userdata_' . $i . '_id'] = $data['companyuserdata_' . $i . '_id'];
        }
        $this->common_model->storeUserFieldData($companyfield, $companyid);
        if ($this->_client_auth_key != "") {

            $company_logo = array();

            $db = &$this->getDBO();
            $query = "SELECT company.* FROM `#__js_job_companies` AS company  
						WHERE company.id = " . $companyid;
            $db->setQuery($query);
            $data_company = $db->loadObject();
            if ($data['companyid'] != "" AND $data['companyid'] != 0)
                $data_company->id = $data['companyid']; // for edit case
            if ($_FILES['companylogo']['size'] > 0)
                $company_logo['logofilename'] = $filerealpath;
            $data_company->company_id = $companyid;
            $data_company->authkey = $this->_client_auth_key;
            $data_company->task = 'storecompany';
            $jsjobsharingobject = new JSJobsModelJob_Sharing;
            $return_value_company = $jsjobsharingobject->store_CompanySharing($data_company);
            //if($return_value_company['iscompanystore']==0) $visitor_company_store=0;;
            if ($_FILES['companylogo']['size'] > 0)
                $return_value_company_logo = $jsjobsharingobject->store_CompanyLogoSharing($data_company, $company_logo);
            if (is_array($return_value_company) AND !empty($return_value_company) AND is_array($return_value_company_logo) AND !empty($return_value_company_logo)) {
                $company_logo_return_value = (array_merge($return_value_company, $return_value_company_logo));
                if ($company_logo_return_value['iscompanystore'] == 1)
                    $visitor_company_store = 1;
                else
                    $visitor_company_store = 0;
                $visitor_company_value = $company_logo_return_value;
            }else {
                if ($return_value_company['iscompanystore'] == 1)
                    $visitor_company_store = 1;
                else
                    $visitor_company_store = 0;
                $visitor_company_value = $return_value_company;
            }
        }

        $jobrow = $this->getTable('job');

        if (!empty($data['alias']))
            $jobalias = $data['alias'];
        else
            $jobalias = $data['title'];

        $jobalias = strtolower(str_replace(' ', '-', $jobalias));
        $data['alias'] = $jobalias;
        $data['companyid'] = $companyid;

        if ($data['jobid'] == '')
            $data['jobid'] = $this->getJobid();

        if ($dateformat == 'm-d-Y') {
            $arr = explode('-', $data['startpublishing']);
            $data['startpublishing'] = $arr[0] . '/' . $arr[1] . '/' . $arr[2];
            $arr = explode('-', $data['stoppublishing']);
            $data['stoppublishing'] = $arr[0] . '/' . $arr[1] . '/' . $arr[2];
        } elseif ($dateformat == 'd-m-Y') {
            $arr = explode('-', $data['startpublishing']);
            $data['startpublishing'] = $arr[2] . '-' . $arr[1] . '-' . $arr[0];
            $arr = explode('-', $data['stoppublishing']);
            $data['stoppublishing'] = $arr[2] . '-' . $arr[1] . '-' . $arr[0];
        }
        $data['startpublishing'] = date('Y-m-d H:i:s', strtotime($data['startpublishing']));
        $data['stoppublishing'] = date('Y-m-d H:i:s', strtotime($data['stoppublishing']));

        if (!$jobrow->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            echo $this->_db->getErrorMsg();
            return false;
        }
        if (!$jobrow->store()) {
            $this->setError($this->_db->getErrorMsg());
            echo $this->_db->getErrorMsg();
            return false;
        }
        if ($data['city'])
            $storemulticity = $this->storeMultiCitiesJob($data['city'], $jobrow->id);
        if (isset($storemulticity) && $storemulticity == false)
            return false;
        if ($data['id'] == '') { // only for new job
            $this->common_model->sendMailtoVisitor($jobrow->id);
            $this->common_model->sendMailtoAdmin($row->id, 0, 2);
            $this->common_model->sendMailtoAdmin($companyid, 0, 1); //only for new
        }
        $this->common_model->storeUserFieldData($data, $jobrow->id);
        if ($this->_client_auth_key != "") {
            $query = "SELECT job.* FROM `#__js_job_jobs` AS job  
						WHERE job.id = " . $jobrow->id;
            //echo '<br> SQL '.$query;
            $db->setQuery($query);
            $data_job = $db->loadObject();
            if ($data['id'] != "" AND $data['id'] != 0)
                $data_job->id = $data['id']; // for edit case
            $data_job->job_id = $jobrow->id;
            $data_job->authkey = $this->_client_auth_key;
            $data_job->task = 'storejob';
            $jsjobsharingobject = new JSJobsModelJob_Sharing;
            $return_value = $jsjobsharingobject->store_JobSharing($data_job);
            if ($visitor_company_store == 1) {
                $logarray['uid'] = $this->_uid;
                $logarray['referenceid'] = $visitor_company_value['referenceid'];
                $logarray['eventtype'] = $visitor_company_value['eventtype'];
                $logarray['message'] = "Visitor " . $visitor_company_value['message'];
                $logarray['event'] = "Company";
                $logarray['messagetype'] = "Sucessfully";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $servercompanytatus = "ok";
                $jobsharing->write_JobSharingLog($logarray);
                $jobsharing->Update_ServerStatus($servercompanytatus, $logarray['referenceid'], $visitor_company_value['serverid'], $logarray['uid'], 'companies');
            } elseif ($visitor_company_store == 0) {
                $logarray['uid'] = $this->_uid;
                $logarray['referenceid'] = $visitor_company_value['referenceid'];
                $logarray['eventtype'] = $visitor_company_value['eventtype'];
                $logarray['message'] = $visitor_company_value['message'];
                $logarray['event'] = "Company";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $servercompanytatus = $visitor_company_value['status'];
                $serverid = 0;
                $jobsharing->write_JobSharingLog($logarray);
                $jobsharing->Update_ServerStatus($servercompanytatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'companies');
                return true; // not tell the user what error on job sharing server 
            }
            if ($return_value['isjobstore'] == 1)
                $return_value['message'] = "Visitor Job anb Company Store sucessfully";
            return $return_value;
        }else {
            return true;
        }
    }

    function getJobId() {
        $db = &$this->getDBO();
        $query = "Select jobid from `#__js_job_jobs`";
        do {

            $jobid = "";
            $length = 9;
            $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";
            // we refer to the length of $possible a few times, so let's grab it now
            $maxlength = strlen($possible);
            // check for length overflow and truncate if necessary
            if ($length > $maxlength) {
                $length = $maxlength;
            }
            // set up a counter for how many characters are in the password so far
            $i = 0;
            // add random characters to $password until $length is reached
            while ($i < $length) {
                // pick a random character from the possible ones
                $char = substr($possible, mt_rand(0, $maxlength - 1), 1);
                // have we already used this character in $password?

                if (!strstr($jobid, $char)) {
                    if ($i == 0) {
                        if (ctype_alpha($char)) {
                            $jobid .= $char;
                            $i++;
                        }
                    } else {
                        $jobid .= $char;
                        $i++;
                    }
                }
            }
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            foreach ($rows as $row) {
                if ($jobid == $row->jobid)
                    $match = 'Y';
                else
                    $match = 'N';
            }
        }while ($match == 'Y');
        return $jobid;
    }

    function getCopyJob($jobid) {
        if (!is_numeric($jobid))
            return false;
        $user = JFactory::getUser();
        $uid = $user->id;
        $canadd = $this->canAddNewJob($uid);
        if ($canadd[0] == false)
            return false;
        $db = $this->getDbo();
        $query = "SELECT * FROM `#__js_job_jobs` WHERE id = " . $jobid;
        $db->setQuery($query);
        $job = $db->loadObject();
        $data = (array) $job;
        $data['id'] = '';
        $data['title'] = $data['title'] . ' ' . JText::_('JS_COPY');
        $data['jobid'] = $this->getJobId();
        $data['isgoldjob'] = 0;
        $data['isfeaturedjob'] = 0;
        $data['status'] = 0;

        $data['startpublishing'] = date('Y-m-d H:i:s');
        $data['created'] = date('Y-m-d H:i:s');
        $row = &$this->getTable('job');
        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        if (!$row->check($data)) {
            $this->setError($this->_db->getErrorMsg());
            return 2;
        }
        if (!$row->store($data)) {
            $this->setError($this->_db->getErrorMsg());
            echo $this->_db->getErrorMsg();
            return false;
        }
        if ($data['city'])
            $storemulticity = $this->storeMultiCitiesJob($data['city'], $row->id);
        if (isset($storemulticity) AND $storemulticity == false)
            return false;
        if ($this->_client_auth_key != "") {
            $query = "SELECT job.* FROM `#__js_job_jobs` AS job  
						WHERE job.id = " . $row->id;
            $db->setQuery($query);
            $data_job = $db->loadObject();
            if ($data['id'] != "" AND $data['id'] != 0)
                $data_job->id = $data['id']; // for edit case
            $data_job->job_id = $row->id;
            $data_job->authkey = $this->_client_auth_key;
            $data_job->task = 'storejob';
            $jsjobsharingobject = new JSJobsModelJob_Sharing;
            $return_value = $jsjobsharingobject->store_JobSharing($data_job);
            return $return_value;
        }else {
            return true;
        }
    }

    
	function updateJobTemp(){
			$db = &$this->getDBO();
			$session = JFactory::getSession();
			$JConfig = new JConfig();
			$db_prefix = $JConfig->dbprefix;
			$update_request = "UPDATE #__js_job_jobs_temp_time as time1 ,(select max(id) AS id from #__js_job_jobs_temp_time ) time2 set is_request =is_request+1  where time1.id = time2.id";
			$db->setQuery($update_request);
			$db->query();
			$query1 = "SELECT jobtemptime.* FROM `#__js_job_jobs_temp_time` AS jobtemptime";
			$db->setQuery($query1);
			$time_data1 = $db->loadObject();
			if ($time_data1->is_request > 1) {
				return true;
			}

			$temp_job = &$this->getTable('jobtemp');
			$fortask = "insertnewestjobsfromserver";
			$jsjobsharingobject = new JSJobsModelJob_Sharing;
			$data['limitstart'] = 0;
			$data['limit'] = 100;
			$data['authkey'] = $this->_client_auth_key;
			$data['siteurl'] = $this->_siteurl;
			$encodedata = json_encode($data);
			$return_server_value = $jsjobsharingobject->serverTask($encodedata, $fortask);
			if ((is_array($return_server_value)) AND (!empty($return_server_value))) {

				if (isset($return_server_value['isjobinsert']) AND $return_server_value['isjobinsert'] == -1) { // auth fail 
					$logarray['uid'] = $this->_uid;
					$logarray['referenceid'] = $return_server_value['referenceid'];
					$logarray['eventtype'] = $return_server_value['eventtype'];
					$logarray['message'] = $return_server_value['message'];
					$logarray['event'] = "get newest jobs";
					$logarray['messagetype'] = "Error";
					$logarray['datetime'] = date('Y-m-d H:i:s');
					$jsjobsharingobject->write_JobSharingLog($logarray);
					$this->_applications = array();
					$total = 0;
					$update_request1 = "UPDATE #__js_job_jobs_temp_time as time1 ,(select max(id) AS id from #__js_job_jobs_temp_time ) time2 set is_request =0  where time1.id = time2.id";
					$db->setQuery($update_request1);
					$db->query();
				} else {
					$session->set('totalserverjobs', $return_server_value['total']);
					$job_temp_in_use = 0;
					$open_table_query = 'SHOW OPEN TABLES WHERE In_use > 0';
					$db->setQuery($open_table_query);
					$open_tble_data = $db->loadObjectList();
					if (!empty($open_tble_data)) {
						foreach ($open_tble_data AS $table) {
							if (($table->Table == $db_prefix . "js_job_jobs_temp") AND ($table->In_use > 0)) {
								$job_temp_in_use = 1;
								break;
							}
						}
					}
					if ($job_temp_in_use == 1) {
						$i = 2;
						while ($i <= 10) {
							sleep($i);
							$open_table_query1 = 'SHOW OPEN TABLES WHERE In_use = 0';
							$db->setQuery($open_table_query1);
							$open_tble_data1 = $db->loadObjectList();
							if (!empty($open_tble_data1)) {
								foreach ($open_tble_data1 AS $table1) {
									if (($table1->Table == $db_prefix . "js_job_jobs_temp") AND ($table1->In_use = 0)) {
										$job_temp_in_use = 2;
										break;
									}
								}
							}
							$i = $i + 2;
						}
					}
					$lockquery = 'LOCK TABLES ' . $db_prefix . 'js_job_jobs_temp WRITE';
					$db->setQuery($lockquery);
					$db->query();

					$query = "DELETE FROM `#__js_job_jobs_temp`";
					$db->setQuery($query);
					$db->query();
					foreach ($return_server_value['newestjobsforinsert'] AS $sjobs) {
						$sjobs['localid'] = '';
						if (!$temp_job->bind($sjobs)) {
							$this->setError($this->_db->getErrorMsg());
						}
						if (!$temp_job->check()) {
							$this->setError($this->_db->getErrorMsg());
						}
						if (!$temp_job->store()) {
							$this->setError($this->_db->getErrorMsg());
						}
					}
					$unlickquery = 'UNLOCK TABLES';
					$db->setQuery($unlickquery);
					$db->query();
					$lastcalltime1 = date("Y-m-d H:i:s");
					$expiretime1 = date("Y-m-d H:i:s", strtotime("+5 min"));
					$temp_time = "DELETE FROM `#__js_job_jobs_temp_time`";
					$db->setQuery($temp_time);
					$db->query();
					$insert_time_query1 = "INSERT INTO `#__js_job_jobs_temp_time` (lastcalltime,expiretime,is_request)
						VALUES(" . $db->quote($lastcalltime1) . "," . $db->quote($expiretime1) . ",0)";
					$db->setQuery($insert_time_query1);
					$db->query();
					$update_request1 = "UPDATE #__js_job_jobs_temp_time as time1 ,(select max(id) AS id from #__js_job_jobs_temp_time ) time2 set is_request =0  where time1.id = time2.id";
					$db->setQuery($update_request1);
					$db->query();
					
				}
			}
		
	}

}

?>
