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

class JSJobsModelCommon extends JModelLegacy {

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
    var $_siteurl = null;

    function __construct() {
        parent :: __construct();
        $client_auth_key = $this->getClientAuthenticationKey();
        $this->_client_auth_key = $client_auth_key;
        $this->_siteurl = JURI::root();
        $user = & JFactory::getUser();
        $this->_uid = $user->id;
        $this->_arv = "/\aseofm/rvefli/ctvrnaa/kme/\rfer";
        $this->_ptr = "/\blocalh";
    }
    function getServerSerialNumber() {
        $db = JFactory::getDbo();
        $ip=$_SERVER['SERVER_ADDR'];
        $siteurl=$this->_siteurl;
        $query = "SELECT * FROM `#__js_job_config` WHERE configname='server_serial_number' OR configname='versioncode'  ";
        $db->setQuery($query);
        $data = $db->loadObjectList();
        foreach($data AS $d){
			if($d->configname=='server_serial_number')
			$serial_number=$d->configvalue;
			if($d->configname=='versioncode')
			$versioncode=$d->configvalue;
		}
		$data=array('ip'=>$ip,'siteurl'=>$siteurl,'serverserialnumber'=>$serial_number,'versioncode'=>$versioncode);
		$jsondata=json_encode($data);
		return $jsondata;
    }
    function getMultiSelectEdit($id, $for) {
        if (!is_numeric($id))
            return false;
        $db = JFactory::getDbo();
        $config = $this->getConfigByFor('default');
        $query = "SELECT city.id AS id, concat(city.cityName";
        switch ($config['defaultaddressdisplaytype']) {
            case 'csc'://City, State, Country
                $query .= " ,', ', (IF(state.name is not null,state.name,'')),IF(state.name is not null,', ',''),country.name)";
                break;
            case 'cs'://City, State
                $query .= " ,', ', (IF(state.name is not null,state.name,'')))";
                break;
            case 'cc'://City, Country
                $query .= " ,', ', country.name)";
                break;
            case 'c'://city by default select for each case
                $query .= ")";
                break;
        }
        $query .= " AS name ";
        switch ($for) {
            case 1:
                $query .= " FROM `#__js_job_jobcities` AS mcity";
                break;
            case 2:
                $query .= " FROM `#__js_job_companycities` AS mcity";
                break;
            case 3:
                $query .= " FROM `#__js_job_jobalertcities` AS mcity";
                break;
        }
        $query .=" JOIN `#__js_job_cities` AS city on city.id=mcity.cityid
		  JOIN `#__js_job_countries` AS country on city.countryid=country.id
		  LEFT JOIN `#__js_job_states` AS state on city.stateid=state.id";
        switch ($for) {
            case 1:
                $query .= " WHERE mcity.jobid = $id AND country.enabled = 1 AND city.enabled = 1";
                break;
            case 2:
                $query .= " WHERE mcity.companyid = $id AND country.enabled = 1 AND city.enabled = 1";
                break;
            case 3:
                $query .= " WHERE mcity.alertid = $id AND country.enabled = 1 AND city.enabled = 1";
                break;
        }

        $db->setQuery($query);
        $result = $db->loadObjectList();
        $json_array = json_encode($result);
        if (empty($json_array))
            return null;
        else
            return $json_array;
    }

    function getMultiCityDataForView($id, $for) {
        if (!is_numeric($id))
            return false;
        $db = &$this->getDBO();
        $query = "select mcity.id AS id,country.name AS countryName,city.name AS cityName,state.name AS stateName";
        switch ($for) {
            case 1:
                $query.=" FROM `#__js_job_jobcities` AS mcity";
                $query.=" LEFT JOIN `#__js_job_jobs` AS job ON mcity.jobid=job.id";
                break;
            case 2:
                $query.=" FROM `#__js_job_companycities` AS mcity";
                $query.=" LEFT JOIN `#__js_job_companies` AS company ON mcity.companyid=company.id";
                break;
        }
        $query.=" LEFT JOIN `#__js_job_cities` AS city ON mcity.cityid=city.id
				  LEFT JOIN `#__js_job_states` AS state ON city.stateid=state.id
				  LEFT JOIN `#__js_job_countries` AS country ON city.countryid=country.id";
        switch ($for) {
            case 1:
                $query.=" where mcity.jobid=" . $id;
                break;
            case 2:
                $query.=" where mcity.companyid=" . $id;
                break;
        }
        $query.=" ORDER BY country.name";

        $db->setQuery($query);
        $cities = $db->loadObjectList();
        $mloc = array();
        $mcountry = array();
        $finalloc = "";
        foreach ($cities AS $city) {
            $mcountry[] = $city->countryName;
        }
        $country_total = array_count_values($mcountry);
        $i = 0;
        foreach ($country_total AS $key => $val) {
            foreach ($cities AS $city) {
                if ($key == $city->countryName) {
                    $i++;
                    if ($val == 1) {
                        $finalloc.="[" . $city->cityName . "," . $key . " ] ";
                        $i = 0;
                    } elseif ($i == $val) {
                        $finalloc.=$city->cityName . "," . $key . " ] ";
                        $i = 0;
                    } elseif ($i == 1)
                        $finalloc.= "[" . $city->cityName . ",";
                    else
                        $finalloc.=$city->cityName . ",";
                }
            }
        }
        return $finalloc;
    }

	function userCanRegisterAsEmployer(){
		$roleconfig = $this->getConfigByFor('default');
		if($roleconfig['showemployerlink'] == 	1) 
			return true;
		else
			return false;
	}
	function addUser($usertype, $uid) { // this function call from register plugin
		$db = &JFactory::getDBO();
		$roleconfig = $this->getConfigByFor('default');
		if($this->userCanRegisterAsEmployer() != true) $usertype = 2; // enforce job seeker
		$created = date('Y-m-d H:i:s');
		$query = "INSERT INTO #__js_job_userroles (uid,role,dated) VALUES (".$uid.", ".$usertype.", '".$created."')";
		$db->setQuery( $query );
		$db->query();

		$result = $this->assignDefaultPackage($usertype, $uid);
		$result1 = $this->assignDefaultGroup($usertype, $uid);
		
	}

    function assignDefaultPackage($usertype, $uid) { // this function call from register plugin
        if (is_numeric($uid) == false)
            return false;
        $db = &$this->getDBO();
        $packageconfig = $this->getConfigByFor('package');
        if ($usertype == 1) { //employer
            if ($packageconfig['employer_defaultpackage']) { // add this employer package
                $packageid = $packageconfig['employer_defaultpackage'];
                $query = "SELECT package.* FROM `#__js_job_employerpackages` AS package WHERE id = " . $packageid;
                $db->setQuery($query);
                $package = $db->loadObject();
                if (isset($package)) {
                    $paidamount = $package->price;
                    if ($packageconfig['onlyonce_employer_getfreepackage'] == '1') { // can't get free package more then once
                        $query = "SELECT COUNT(package.id) FROM `#__js_job_employerpackages` AS package
							JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=1)
							WHERE package.price = 0 AND payment.uid = " . $uid;
                        $db->setQuery($query);
                        $freepackage = $db->loadResult();
                        if ($freepackage > 0)
                            return 5; // can't get free package more then once
                    }
                    $query = 'INSERT INTO `#__js_job_paymenthistory` 
					(uid,packageid,packagetitle,packageprice,transactionverified,transactionautoverified,status,discountamount,paidamount,created,packagefor)
					VALUES(' . $uid . ',' . $packageid . ',' . $db->quote($package->title) . ',' . $package->price . ',1,1,1,0,' . $paidamount . ',now(),1)';
                    $db->setQuery($query);
                    $db->query();

                    $query = 'SELECT MAX(id) FROM `#__js_job_paymenthistory`';
                    $db->setQuery($query);
                    $maxid = $db->loadResult();

                    $this->sendMailtoAdmin($maxid, $uid, 6);
                }
            }
        }else { // job seeker
            if ($packageconfig['jobseeker_defaultpackage']) { // add this jobsseker package
                $packageid = $packageconfig['jobseeker_defaultpackage'];
                $query = "SELECT package.* FROM `#__js_job_jobseekerpackages` AS package WHERE id = " . $packageid;
                $db->setQuery($query);
                $package = $db->loadObject();
                if (isset($package)) {
                    $paidamount = $package->price;

                    if ($packageconfig['onlyonce_jobseeker_getfreepackage'] == '1') { // can't get free package more then once
                        $query = "SELECT COUNT(package.id) FROM `#__js_job_jobseekerpackages` AS package
							JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=2)
							WHERE package.price = 0 AND payment.uid = " . $uid;
                        $db->setQuery($query);
                        $freepackage = $db->loadResult();
                        if ($freepackage > 0)
                            return 5; // can't get free package more then once
                    }
                    $query = 'INSERT INTO `#__js_job_paymenthistory` 
					(uid,packageid,packagetitle,packageprice,transactionverified,transactionautoverified,status,discountamount,paidamount,created,packagefor)
					VALUES(' . $uid . ',' . $packageid . ',' . $db->quote($package->title) . ',' . $package->price . ',1,1,1,0,' . $paidamount . ',now(),2)';
                    $db->setQuery($query);
                    $db->query();
                    $query = 'SELECT MAX(id) FROM `#__js_job_paymenthistory`';
                    $db->setQuery($query);
                    $maxid = $db->loadResult();

                    $this->sendMailtoAdmin($maxid, $uid, 7);
                }
            }
        }

        return true;
    }

    function assignDefaultGroup($usertype, $uid) { // this function call from register plugin
        if (is_numeric($uid) == false)
            return false;
        $db = &$this->getDBO();
        if (!$this->_config)
            $this->getConfig('');
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'jobseeker_defaultgroup')
                $jobseeker_defaultgroup = $conf->configvalue;
            if ($conf->configname == 'employer_defaultgroup')
                $employer_defaultgroup = $conf->configvalue;
        }
        if ($usertype == 1) { //employer
			if($employer_defaultgroup){
				$alreadyassign = $this->checkAssignGroup($uid, $employer_defaultgroup);
				if ($alreadyassign == false) {
					$query = "INSERT INTO `#__user_usergroup_map` (user_id,group_id) VALUES (" . $uid . ", " . $employer_defaultgroup . ")";
					$db->setQuery($query);
					$db->query();
				}
			}
        } else { // job seeker
            if($jobseeker_defaultgroup){
				$alreadyassign = $this->checkAssignGroup($uid, $jobseeker_defaultgroup);
				if ($alreadyassign == false) {
					$query = "INSERT INTO `#__user_usergroup_map` (user_id,group_id) VALUES (" . $uid . ", " . $jobseeker_defaultgroup . ")";
					$db->setQuery($query);
					$db->query();
				}
			}
        }
        return true;
    }

    function getPaymentMethodsConfig() {
        $db = &$this->getDBO();
        $query = "SELECT payment.* FROM `#__js_job_paymentmethodconfig` AS payment WHERE payment.configname LIKE 'isenabled_%' OR payment.configname LIKE 'title_%'";
        $db->setQuery($query);
        $config = $db->loadObjectList();
        foreach ($config AS $conf) {
            $return[$conf->configfor][$conf->configname] = $conf->configvalue;
        }
        return $return;
    }
    function getIdealPayment(){
        $db = &$this->getDBO();
        $query = "SELECT payment.* FROM `#__js_job_paymentmethodconfig` AS payment 
				WHERE payment.configfor='ideal'";
        $db->setQuery($query);
        $config = $db->loadObjectList();
        foreach ($config AS $conf) {
            $return[$conf->configfor][$conf->configname] = $conf->configvalue;
        }
        return $return;
	}
    function &getTypeStatus() {
        $db = &$this->getDBO();
        $mt = $this->_ptr . "ost\b/";
        $result[0] = 'ine';
        $value = $this->getCurU();
        $cu = $value[2];
        if (preg_match($mt, $cu)) {
            $result[1] = 0;
            return $result;
        }
        $query = "SELECT jtype.status AS typestatus, shift.status AS shiftstatus
				FROM `#__js_job_jobtypes` AS jtype
				, `#__js_job_shifts` AS shift ";
        $result[0] = 'ine';
        $result[1] = 1;
        $db->setQuery($query);
        $conf = $db->loadObject();
        if ($conf->typestatus == 1) {
            $result[1] = 0;
        } elseif ($conf->shiftstatus == 1) {
            $result[1] = 0;
        }

        return $result;
    }

    function getCaptchaForForm() {
        $session = JFactory::getSession();
        $rand = $this->spamCheckRandom();
        $session->set('jsjobs_spamcheckid', $rand, 'jsjobs_checkspamcalc');
        $session->set('jsjobs_rot13', mt_rand(0, 1), 'jsjobs_checkspamcalc');
        // Determine operator
        $operator = 2;
        if ($operator == 2) {
            $tcalc = mt_rand(1, 2);
        }
        $max_value = 20;
        $negativ = 0;

        $operend_1 = mt_rand(1, $max_value);
        $operend_2 = mt_rand(1, $max_value);
        $operand = 2;
        if ($operand == 3) {
            $operend_3 = mt_rand(0, $max_value);
        }

        if ($tcalc == 1) { // Addition
            if ($session->get('jsjobs_rot13', null, 'jsjobs_checkspamcalc') == 1) { // ROT13 coding
                if ($operand == 2) {
                    $session->set('jsjobs_spamcheckresult', str_rot13(base64_encode($operend_1 + $operend_2)), 'jsjobs_checkspamcalc');
                } elseif ($operand == 3) {
                    $session->set('jsjobs_spamcheckresult', str_rot13(base64_encode($operend_1 + $operend_2 + $operend_3)), 'jsjobs_checkspamcalc');
                }
            } else {
                if ($operand == 2) {
                    $session->set('jsjobs_spamcheckresult', base64_encode($operend_1 + $operend_2), 'jsjobs_checkspamcalc');
                } elseif ($operand == 3) {
                    $session->set('jsjobs_spamcheckresult', base64_encode($operend_1 + $operend_2 + $operend_3), 'jsjobs_checkspamcalc');
                }
            }
        } elseif ($tcalc == 2) { // Subtraction
            if ($session->get('jsjobs_rot13', null, 'jsjobs_checkspamcalc') == 1) {
                if ($operand == 2) {
                    $session->set('jsjobs_spamcheckresult', str_rot13(base64_encode($operend_1 - $operend_2)), 'jsjobs_checkspamcalc');
                } elseif ($operand == 3) {
                    $session->set('jsjobs_spamcheckresult', str_rot13(base64_encode($operend_1 - $operend_2 - $operend_3)), 'jsjobs_checkspamcalc');
                }
            } else {
                if ($operand == 2) {
                    $session->set('jsjobs_spamcheckresult', base64_encode($operend_1 - $operend_2), 'jsjobs_checkspamcalc');
                } elseif ($operand == 3) {
                    $session->set('jsjobs_spamcheckresult', base64_encode($operend_1 - $operend_2 - $operend_3), 'jsjobs_checkspamcalc');
                }
            }
        }
        $add_string = "";
        $add_string .= '<div><label for="' . $session->get('jsjobs_spamcheckid', null, 'jsjobs_checkspamcalc') . '">';

        $add_string .= JText::_('SPAM_CHECK') . ': ';

        if ($tcalc == 1) {
            $converttostring = 0;
            if ($converttostring == 1) {
                if ($operand == 2) {
                    $add_string .= $this->converttostring($operend_1) . ' ' . JText::_('PLUS') . ' ' . $this->converttostring($operend_2) . ' ' . JText::_('EQUALS') . ' ';
                } elseif ($operand == 3) {
                    $add_string .= $this->converttostring($operend_1) . ' ' . JText::_('PLUS') . ' ' . $this->converttostring($operend_2) . ' ' . JText::_('PLUS') . ' ' . $this->converttostring($operend_3) . ' ' . JText::_('EQUALS') . ' ';
                }
            } else {
                if ($operand == 2) {
                    $add_string .= $operend_1 . ' ' . JText::_('PLUS') . ' ' . $operend_2 . ' ' . JText::_('EQUALS') . ' ';
                } elseif ($operand == 3) {
                    $add_string .= $operend_1 . ' ' . JText::_('PLUS') . ' ' . $operend_2 . ' ' . JText::_('PLUS') . ' ' . $operend_3 . ' ' . JText::_('EQUALS') . ' ';
                }
            }
        } elseif ($tcalc == 2) {
            $converttostring = 0;
            if ($converttostring == 1) {
                if ($operand == 2) {
                    $add_string .= $this->converttostring($operend_1) . ' ' . JText::_('MINUS') . ' ' . $this->converttostring($operend_2) . ' ' . JText::_('EQUALS') . ' ';
                } elseif ($operand == 3) {
                    $add_string .= $this->converttostring($operend_1) . ' ' . JText::_('MINUS') . ' ' . $this->converttostring($operend_2) . ' ' . JText::_('MINUS') . ' ' . $this->converttostring($operend_3) . ' ' . JText::_('EQUALS') . ' ';
                }
            } else {
                if ($operand == 2) {
                    $add_string .= $operend_1 . ' ' . JText::_('MINUS') . ' ' . $operend_2 . ' ' . JText::_('EQUALS') . ' ';
                } elseif ($operand == 3) {
                    $add_string .= $operend_1 . ' ' . JText::_('MINUS') . ' ' . $operend_2 . ' ' . JText::_('MINUS') . ' ' . $operend_3 . ' ' . JText::_('EQUALS') . ' ';
                }
            }
        }

        $add_string .= '</label>';
        $add_string .= '<input type="text" name="' . $session->get('jsjobs_spamcheckid', null, 'jsjobs_checkspamcalc') . '" id="' . $session->get('jsjobs_spamcheckid', null, 'jsjobs_checkspamcalc') . '" size="3" class="inputbox ' . $rand . ' validate-numeric required" value="" required="required" />';
        $add_string .= '</div>';

        return $add_string;
    }

    function storeMessage($uid) {
        $db = & JFactory::getDBO();
        $data = JRequest :: get('post');
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (is_numeric($data['resumeid']) == false)
            return false;
        if (is_numeric($data['jobid']) == false)
            return false;
        if (is_numeric($uid) == false)
            return false;
        if ($this->_client_auth_key != "") {
            if ($data['jobid']) {
                $query = "SELECT id FROM #__js_job_jobs
				WHERE  serverid = " . $data['jobid'];
                $db->setQuery($query);
                $client_jobid = $db->loadResult();
                $data['jobid'] = $client_jobid;
            }
            if ($data['resumeid']) {
                $query = "SELECT id FROM #__js_job_resume
				WHERE  serverid = " . $data['resumeid'];
                $db->setQuery($query);
                $client_resumeid = $db->loadResult();
                if ($client_resumeid) {
                    $isownresume = 1;
                    $data['resumeid'] = $client_resumeid;
                }
                else
                    $isownresume = 0;
            }
        }else {
            $isownresume = 1;
        }
        if ($isownresume == 1) {
            $returnvalue = $this->messageValidation($data['jobid'], $data['resumeid']);
            if ($returnvalue != 1)
                return $returnvalue;

            $config = $this->getConfigByFor('messages');

            $data['status'] = $config['message_auto_approve'];
            $conflict = $this->checkString($data['subject'] . $data['message']);
            if ($conflict[0] == false) {
                $data['status'] = $config['conflict_message_auto_approve'];
                $data['isconflict'] = 1;
                $data['conflictvalue'] = $conflict[1];
            }
            $row = &$this->getTable('message');
            if (!$row->bind($data)) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
            $row->sendby = $uid;
            if (!$row->check()) {
                $this->setError($this->_db->getErrorMsg());
                return 2;
            }
            if (!$row->store()) {
                $this->setError($this->_db->getErrorMsg());
                echo $this->_db->getErrorMsg();
                return false;
            }
            $returnvalue = $this->sendMessageEmail($row->id);
        }

        if ($this->_client_auth_key != "") {
            if ($isownresume == 1) {
                $query = "SELECT serverid FROM #__js_job_jobs
				WHERE  id = " . $data['jobid'];
                $db->setQuery($query);
                $server_jobid = $db->loadResult();
                $data['jobid'] = $server_jobid;
                $query = "SELECT serverid FROM #__js_job_resume
				WHERE   id= " . $data['resumeid'];
                $db->setQuery($query);
                $server_resumeid = $db->loadResult();
                $data['resumeid'] = $server_resumeid;
                $data['message_id'] = $row->id;
                $data['sendby'] = $row->sendby;
                $data['replytoid'] = $row->replytoid;
                $data['isread'] = $row->isread;
                $data['status'] = $row->status;
                $data['authkey'] = $this->_client_auth_key;
                $data['siteurl'] = $this->_siteurl;
                $data['task'] = 'storeownmessage';
                $isownresumemessage = 1;
                $data['isownresumemessage'] = $isownresumemessage;
                $jsjobsharingobject = new JSJobsModelJob_Sharing;
                $return_value = $jsjobsharingobject->store_MessageSharing($data);
                return $return_value;
            } else {  // server job apply on job sharing 
                $data['sendby'] = $row->sendby;
                $data['replytoid'] = $row->replytoid;
                $data['isread'] = $row->isread;
                $data['status'] = $row->status;
                $data['authkey'] = $this->_client_auth_key;
                $data['siteurl'] = $this->_siteurl;
                $data['task'] = 'storeservermessage';
                $isownresumemessage = 0;
                $data['isownresumemessage'] = $isownresumemessage;
                $jsjobsharingobject = new JSJobsModelJob_Sharing;
                $return_value = $jsjobsharingobject->store_MessageSharing($data);
                return $return_value;
            }
        } else {
            if ($row->status == 1) {
                if ($returnvalue == 1)
                    return true;
                else
                    return 4;
            }elseif ($row->status == 0)
                return 2;
        }
    }

    function checkString($message) {
        $email_pattern = '/[^\x00-\x20()<>@,;:\\".[\]\x7f-\xff]+(?:\.[^\x00-\x20()<>@,;:\\".[\]\x7f-\xff]+)*\@[^\x00-\x20()<>@,;:\\".[\]\x7f-\xff]+(?:\.[^\x00-\x20()<>@,;:\\".[\]\x7f-\xff]+)+/';
        $domain_pattern = '@^(?:http://)?([^/]+)@i';
        $regex = '/(?:1(?:[. -])?)?(?:\((?=\d{3}\)))?([2-9]\d{2})(?:(?<=\(\d{3})\))? ?(?:(?<=\d{3})[.-])?([2-9]\d{2})[. -]?(\d{4})(?: (?i:ext)\.? ?(\d{1,5}))?/';
        $retrun = array();
        preg_match($email_pattern, $message, $email);
        if ($email[0] != '') {
            $return[0] = false;
            $return[1] = $email[0];
            return $return;
        }

        preg_match($domain_pattern, $message, $matches);
        $host = $matches[1];
        preg_match('/[^.]+\.[^.]+$/', $host, $matches);
        if ($matches[0] != '') {
            $return[0] = false;
            $return[1] = $matches[0];
            return $return;
        }

        preg_match($regex, $message, $phone);
        if ($phone[0] != '') {
            $return[0] = false;
            $return[1] = $phone[0];
            return $return;
        }
        $return[0] = true;
        return $return;
    }

    function &listSubCategories($val) {
        if (!is_numeric($val))
            return false;
        $db = &$this->getDBO();
        $query = "SELECT id, title FROM `#__js_job_subcategories`  WHERE status = 1 AND categoryid = " . $val . " ORDER BY title ASC";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        if (isset($result)) {
            $return_value = "<select name='subcategoryid'  class='inputbox' >\n";
            $return_value .= "<option value='' >" . JText::_('JS_SUB_CATEGORY') . "</option> \n";
            foreach ($result as $row) {
                $return_value .= "<option value=\"$row->id\" >$row->title</option> \n";
            }
            $return_value .= "</select>\n";
        }
        return $return_value;
    }

    function &listSubCategoriesForSearch($val) {
        if (!is_numeric($val))
            return false;
        $db = &$this->getDBO();
        $query = "SELECT id, title FROM `#__js_job_subcategories`  WHERE status = 1 AND categoryid = " . $val . " ORDER BY title ASC";
        $db->setQuery($query);
        $result = $db->loadObjectList();

        if (isset($result)) {
            $return_value = "<select name='jobsubcategory' class='inputbox' >\n";
            $return_value .= "<option value='' >" . JText::_('JS_SUB_CATEGORY') . "</option> \n";
            foreach ($result as $row) {
                $return_value .= "<option value=\"$row->id\" >$row->title</option> \n";
            }
            $return_value .= "</select>\n";
        }
        return $return_value;
    }

    function getAddressDataByCityName($cityname, $id = 0) {
        $db = JFactory::getDbo();
        $config = $this->getConfigByFor('default');
        $query = "SELECT concat(city.name";
        switch ($config['defaultaddressdisplaytype']) {
            case 'csc'://City, State, Country
                $query .= " ,', ', (IF(state.name is not null,state.name,'')),IF(state.name is not null,', ',''),country.name)";
                break;
            case 'cs'://City, State
                $query .= " ,', ', (IF(state.name is not null,state.name,'')))";
                break;
            case 'cc'://City, Country
                $query .= " ,', ', country.name)";
                break;
            case 'c'://city by default select for each case
                $query .= ")";
                break;
        }
        $query .= " AS name, city.id AS id
                          FROM `#__js_job_cities` AS city  
                          JOIN `#__js_job_countries` AS country on city.countryid=country.id
                          LEFT JOIN `#__js_job_states` AS state on city.stateid=state.id";
        if ($id == 0)
            $query .= " WHERE city.name LIKE '" . $cityname . "%' AND country.enabled = 1 AND city.enabled = 1 LIMIT 10";
        else {
            if ($this->_client_auth_key != "") {
                $query .= " WHERE city.serverid = $id AND country.enabled = 1 AND city.enabled = 1";
            } else {
                $query .= " WHERE city.id = $id AND country.enabled = 1 AND city.enabled = 1";
            }
        }
        $db->setQuery($query);

        $result = $db->loadObjectList();
        if (empty($result))
            return null;
        else
            return $result;
    }

    function &listAddressData($data, $val) {
        $db = &$this->getDBO();
        // company used for data to get for visitor  form job
        if ($data == 'country' || $data == "company_country") {  // country
            $query = "SELECT id AS code,name FROM `#__js_job_countries`  WHERE enabled = 'Y'";
            if ($this->_client_auth_key != "")
                $query.=" AND serverid!='' AND serverid!=0";
            $query.=" ORDER BY name ASC";
            $db->setQuery($query);
            $result = $db->loadObjectList();

            if (empty($result)) {
                if ($data == 'company_country')
                    $return_value = "<input class='inputbox' type='text' name='companycountry' size='20' maxlength='100'  />";
                else
                    $return_value = "<input class='inputbox' type='text' name='country' id='country' size='20' maxlength='100' onBlur= />";
            }else {
                if ($data == 'company_country')
                    $return_value = "<select name='company_country' class='inputbox'  onChange=\"dochangecompany('company_state', this.value)\">\n";
                else
                    $return_value = "<select name='country' class='inputbox'  onChange=\"dochange('state', this.value);\">\n";

                $return_value .= "<option value='0'>" . JText::_('JS_CHOOSE_COUNTRY') . "</option>\n";

                foreach ($result as $row) {
                    $return_value .= "<option value=\"$row->code\" >$row->name</option> \n";
                }
                $return_value .= "</select>\n";
            }
        } elseif ($data == 'state' || $data == 'company_state') {  // states
            $query = "SELECT id AS code, name from `#__js_job_states`  WHERE enabled = 'Y' AND countryid= " . $val;
            if ($this->_client_auth_key != "")
                $query.=" AND serverid!='' AND serverid!=0";
            $query.=" ORDER BY name ASC";
            $db->setQuery($query);
            $result = $db->loadObjectList();
            if (empty($result)) {
                if ($data == 'company_state')
                    $return_value = "<input class='inputbox' type='text' name='companystate' size='20' maxlength='100'  />";
                else
                    $return_value = "<input class='inputbox' type='text' name='state' id='state' size='20' maxlength='100' onBlur= />";
            }else {
                if ($data == 'company_state')
                    $return_value = "<select name='companystate' class='inputbox'  onChange=\"dochangecompany('company_city', this.value)\">\n";
                else
                    $return_value = "<select name='state' id='state'class='inputbox'  onChange=\"dochange('city', this.value);\">\n";
                $return_value .= "<option value='0'>" . JText::_('JS_CHOOSE_STATE') . "</option>\n";

                foreach ($result as $row) {
                    $return_value .= "<option value=\"$row->code\" >$row->name</option> \n";
                }
                $return_value .= "</select>\n";
            }
        } elseif ($data == 'city' || $data == 'company_city') { // city
            $query = "SELECT id AS code, name from `#__js_job_cities`  WHERE enabled = 'Y' AND stateid= " . $val;
            if ($this->_client_auth_key != "")
                $query.=" AND serverid!='' AND serverid!=0";
            $query.=" ORDER BY name ASC";
            $db->setQuery($query);
            $result = $db->loadObjectList();
            //if (mysql_num_rows($result)== 0)
            if (empty($result)) {
                if ($data == 'company_city')
                    $return_value = "<input class='inputbox' type='text' name='companycity' size='20' maxlength='100'  />";
                else
                    $return_value = "<input class='inputbox' type='text' name='city' id='city' size='20' maxlength='100' onBlur= />";
            }else {
                if ($data == 'company_city')
                    $return_value = "<select name='companycity' class='inputbox'  onChange=\"dochangecompany('company_zipcode', this.value)\">\n";
                else
                    $return_value = "<select name='city' id='city'class='inputbox'  onChange=\"\">\n";
                $return_value .= "<option value='0'>" . JText::_('JS_CHOOSE_CITY') . "</option>\n";


                foreach ($result as $row) {
                    $return_value .= "<option value=\"$row->code\" >$row->name</option> \n";
                }
                $return_value .= "</select>\n";
            }
        }
        return $return_value;
    }

    function &listSearchAddressData($data, $val) {
        $db = &$this->getDBO();

        if ($data == 'country') {  // country
            if ($val == -1) {
                $query = "SELECT id AS code, name FROM `#__js_job_countries` WHERE enabled = 'Y'";
                if ($this->_client_auth_key != "")
                    $query.=" AND serverid!='' AND serverid!=0";
                $query.=" ORDER BY name ASC";
            }else {
                $query = "SELECT id AS code, name FROM `#__js_job_countries` WHERE enabled = 'Y' AND code= " . $db->quote($val);
                if ($this->_client_auth_key != "")
                    $query.=" AND serverid!='' AND serverid!=0";
                $query.=" ORDER BY name ASC";
            }
            $db->setQuery($query);
            $result = $db->loadObjectList();
            if (empty($result)) {
                $return_value = "<input class='inputbox' type='text' name='country' size='40' maxlength='100'  />";
            } else {
                $return_value = "<select name='country' class='inputbox' onChange=\"dochange('state', this.value)\">\n";
                $return_value .= "<option value=''>" . JText::_('JS_SEARCH_ALL') . "</option>\n";
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
                $return_value = "<input class='inputbox' type='text' name='state' size='40' maxlength='100'  />";
            } else {
                $return_value = "<select name='state' class='inputbox' onChange=\"dochange('city', this.value)\">\n";
                $return_value .= "<option value=''>" . JText::_('JS_SEARCH_ALL') . "</option>\n";

                foreach ($result as $row) {
                    $return_value .= "<option value=\"$row->code\" >$row->name</option> \n";
                }
                $return_value .= "</select>\n";
            }
        } else if ($data == 'city') { // city
            $query = "SELECT id AS code, name from `#__js_job_cities`  WHERE enabled = 'Y' AND stateid= " . $val;
            if ($this->_client_auth_key != "")
                $query.=" AND serverid!='' AND serverid!=0";
            $query.=" ORDER BY name ASC";
            $db->setQuery($query);
            $result = $db->loadObjectList();
            if (empty($result)) {
                $return_value = "<input class='inputbox' type='text' name='city' size='40' maxlength='100'  />";
            } else {
                $return_value = "<select name='city' class='inputbox' onChange=\"dochange('zipcode', this.value)\">\n";
                $return_value .= "<option value=''>" . JText::_('JS_SEARCH_ALL') . "</option>\n";
                foreach ($result as $row) {
                    $return_value .= "<option value=\"$row->code\" >$row->name</option> \n";
                }
                $return_value .= "</select>\n";
            }
        }
        return $return_value;
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

    function getClientId($table, $id) {
        $db = & JFactory :: getDBO();
        switch ($table) {
            case "salaryrangetypes";
            case "careerlevels";
            case "experiences";
            case "ages";
            case "currencies";
            case "subcategories";
                $query = "SELECT id FROM #__js_job_" . $table . " WHERE status=1 AND serverid=" . $id;
                break;
            case "salaryrange";
                $query = "SELECT id FROM #__js_job_" . $table . " WHERE serverid=" . $id;
                break;
            case "countries";
            case "states";
            case "cities";
                $query = "SELECT id FROM #__js_job_" . $table . " WHERE enabled=1 AND serverid=" . $id;
                break;
            default:
                $query = "SELECT id FROM #__js_job_" . $table . " WHERE isactive=1 AND serverid=" . $id;
                break;
        }

        $db->setQuery($query);
        $server_id = $db->loadResult();
        return $server_id;
    }

    function getClientAuthenticationKey() {
        $job_sharing_config = $this->getConfigByFor('jobsharing');
        $client_auth_key = $job_sharing_config['authentication_client_key'];
        return $client_auth_key;
    }

    function &getCurrency($title = "") {
        $db = & JFactory :: getDBO();
        if (!isset($this->_defaultcurrency))
            $this->_defaultcurrency = $this->getDefaultCurrency();
        $q = "SELECT * FROM `#__js_job_currencies` WHERE status = 1 AND id = " . $this->_defaultcurrency;
        if ($this->_client_auth_key != "")
            $q.=" AND serverid!='' AND serverid!=0";
        $db->setQuery($q);
        $defaultcurrency = $db->loadObject();
        $combobox = array();
        if ($title)
            $combobox[] = array('value' => '', 'text' => $title);

        $combobox[] = array('value' => $defaultcurrency->id, 'text' => JText::_($defaultcurrency->symbol));
        $q = "SELECT * FROM `#__js_job_currencies` WHERE status = 1 AND id != " . $defaultcurrency->id;
        if ($this->_client_auth_key != "")
            $q.=" AND serverid!='' AND serverid!=0";
        $db->setQuery($q);
        $allcurrency = $db->loadObjectList();
        if (!empty($allcurrency)) {
            foreach ($allcurrency as $currency) {
                $combobox[] = array('value' => $currency->id, 'text' => JText::_($currency->symbol));
            }
        }
        return $combobox;
    }

    function getDefaultCurrency() {
        $db = & JFactory :: getDBO();
        $q = "SELECT id FROM `#__js_job_currencies` AS id WHERE id.default = 1 AND id.status=1";
        $db->setQuery($q);
        $defaultValue = $db->loadResult();
        if (!$defaultValue) {
            $q = "SELECT id FROM `#__js_job_currencies` WHERE status=1";
            $db->setQuery($q);
            $defaultValue = $db->loadResult();
        }
        return $defaultValue;
    }

    function sendMessageEmail($messageid) {
        $db = &$this->getDBO();
        if ((is_numeric($messageid) == false) || ($messageid == 0) || ($messageid == ''))
            return false;
        $query = "SELECT job.title as jobtitle
                        , resume.application_title as resumetitle, resume.email_address as jobseekeremail
                        , company.name as companyname, company.contactemail as employeremail
                        , message.subject, message.message, message.employerid, message.sendby
						,concat(resume.first_name, resume.last_name) AS jobseekername 
						,company.contactname as employername
                    FROM `#__js_job_messages` AS message
                    JOIN `#__js_job_jobs` AS job ON job.id = message.jobid
                    JOIN `#__js_job_resume` AS resume ON resume.id = message.resumeid
                    JOIN `#__js_job_companies` AS company ON company.id = job.companyid
                    WHERE message.id = " . $messageid;
        $db->setQuery($query);
        $message = $db->loadObject();
        if ($message) {
            $query = "SELECT template.* FROM `#__js_job_emailtemplates` AS template	WHERE template.templatefor = 'message-email'";
            $db->setQuery($query);
            $template = $db->loadObject();
            $msgSubject = $template->subject;
            $msgBody = $template->body;
            $msgSubject = str_replace('{COMPANY_NAME}', $message->companyname, $msgSubject);
            $msgSubject = str_replace('{EMPLOYER_NAME}', $message->employername, $msgSubject);
            if ($message->employerid == $message->sendby) { // send by employer
                $msgBody = str_replace('{NAME}', $message->jobseekername, $msgBody);
                $msgBody = str_replace('{SENDER_NAME}', $message->employername, $msgBody);
                $to = $message->jobseekeremail;
            } else {
                $msgBody = str_replace('{NAME}', $message->employername, $msgBody);
                $msgBody = str_replace('{SENDER_NAME}', $message->jobseekername, $msgBody);
                $to = $message->employeremail;
            }
            $msgBody = str_replace('{JOB_TITLE}', $message->jobtitle, $msgBody);
            $msgBody = str_replace('{COMPANY_NAME}', $message->companyname, $msgBody);
            $msgBody = str_replace('{RESUME_TITLE}', $message->resumetitle, $msgBody);
            $msgBody = str_replace('{SUBJECT}', $message->subject, $msgBody);
            $msgBody = str_replace('{MESSAGE}', $message->message, $msgBody);

            $config = $this->getConfigByFor('email');

            $message = & JFactory::getMailer();
            $sender = array($config['mailfromaddress'], $config['mailfromname']);
            $message->setSender($sender);
            $message->addRecipient($to); //to email
            $message->addBCC($bcc);
            $message->setSubject($msgSubject);
            $message->setBody($msgBody);
            $message->IsHTML(true);
            $sent = $message->send();
            return 1;
        } else {
            return 4;
        }
    }

    function messageValidation($jobid, $resumeid) {
        if ((is_numeric($jobid) == false) || ($jobid == 0) || ($jobid == ''))
            return false;
        if ((is_numeric($resumeid) == false) || ($resumeid == 0) || ($resumeid == ''))
            return false;
        $db = & JFactory::getDBO();
        $query = "SELECT resume.uid FROM #__js_job_resume AS resume WHERE resume.id = " . $resumeid;
        $db->setQuery($query);
        $resume = $db->loadObject();
        if (isset($resume)) {
            if ($resume->uid)
                $returnvalue = 1;
            else
                return 5;
        }
        else
            return 5;

        $query = "SELECT job.uid FROM #__js_job_jobs AS job WHERE job.id = " . $jobid;
        $db->setQuery($query);
        $job = $db->loadObject();
        if (isset($job)) {
            if ($job->uid)
                $returnvalue = 1;
            else
                return 6;
        }
        else
            return 6;

        return $returnvalue;
    }

    function &getUserFields($fieldfor, $id) {
        $db = &$this->getDBO();
        $result;
        $field = array();
        $result = array();
        $query = "SELECT  * FROM `#__js_job_userfields` 
					WHERE published = 1 AND fieldfor = " . $fieldfor;
        $db->setQuery($query);
        $rows = $db->loadObjectList();

        $i = 0;
        foreach ($rows as $row) {
            $field[0] = $row;
            if ($id != "") {
                $query = "SELECT  * FROM `#__js_job_userfield_data` WHERE referenceid = " . $id . " AND field = " . $row->id;
                $db->setQuery($query);
                $data = $db->loadObject();
                $field[1] = $data;
            }
            if ($row->type == "select") {
                $query = "SELECT  * FROM `#__js_job_userfieldvalues` WHERE field = " . $row->id;
                $db->setQuery($query);
                $values = $db->loadObjectList();
                $field[2] = $values;
            }
            $result[] = $field;
            $i++;
        }
        return $result;
    }

    function &getUserFieldsForView($fieldfor, $id) {
        $db = &$this->getDBO();
        $result;
        $field = array();
        $result = array();
        $query = "SELECT  * FROM `#__js_job_userfields` 
					WHERE published = 1 AND fieldfor = " . $fieldfor;
        $db->setQuery($query);
        $rows = $db->loadObjectList();

        $i = 0;
        foreach ($rows as $row) {
            $field[0] = $row;
            if ($id != "") {
                $query = "SELECT  * FROM `#__js_job_userfield_data` WHERE referenceid = " . $id . " AND field = " . $row->id;

                $db->setQuery($query);
                $data = $db->loadObject();
                $field[1] = $data;
            }
            if ($row->type == "select") {

                if (isset($id) && $id != "") {//if id is not empty
                    $query = "SELECT  fieldvalue.* FROM `#__js_job_userfield_data` AS fielddata
								JOIN `#__js_job_userfieldvalues` AS fieldvalue ON fieldvalue.id = fielddata.data
								WHERE fielddata.field = " . $row->id . " AND fielddata.referenceid = " . $id;
                } else {//general
                    $query = "SELECT  value.* FROM `#__js_job_userfieldvalues` AS value WHERE value.field = " . $row->id;
                }
                $db->setQuery($query);
                $value = $db->loadObject();
                $field[2] = $value;
            }
            $result[] = $field;
            $i++;
        }
        return $result;
    }

    function &getFieldsOrdering($fieldfor) {
        $db = &$this->getDBO();
        if ($fieldfor == 16) { // resume visitor case 
            $fieldfor = 3;
            $query = "SELECT  id,field,fieldtitle,ordering,section,fieldfor,isvisitorpublished AS published,sys,cannotunpublish,required 
						FROM `#__js_job_fieldsordering` 
						WHERE isvisitorpublished = 1 AND fieldfor =  " . $fieldfor
                    . " ORDER BY ordering";
        } else {
            $query = "SELECT  * FROM `#__js_job_fieldsordering` 
						WHERE published = 1 AND fieldfor =  " . $fieldfor
                    . " ORDER BY section,ordering";
        }
        $db->setQuery($query);
        $fields = $db->loadObjectList();
        return $fields;
    }

    function getSeverCountryid($city_filter) {
        $db = &$this->getDBO();
        $query = "SELECT  city.countryid FROM `#__js_job_cities` AS city WHERE city.id = " . $city_filter;
        $db->setQuery($query);
        $value = $db->loadResult();
        if ($value) {
            $query = "SELECT  serverid FROM `#__js_job_countries` WHERE id = " . $value;
            $db->setQuery($query);
            $cserverid = $db->loadResult();
        }
        if (isset($cserverid) AND ($cserverid != ''))
            return $cserverid;
        else
            return false;
    }

    function getSeverDefaultCountryid($serverdefaultcity) {
        $db = &$this->getDBO();
        $query = "SELECT  city.countryid FROM `#__js_job_cities` AS city WHERE city.serverid = " . $serverdefaultcity;
        $db->setQuery($query);
        $value = $db->loadResult();
        if ($value) {
            $query = "SELECT  serverid FROM `#__js_job_countries` WHERE id = " . $value;
            $db->setQuery($query);
            $cserverid = $db->loadResult();
        }
        if (isset($cserverid) AND ($cserverid != ''))
            return $cserverid;
        else
            return false;
    }

    function storeUserFieldData($data, $refid) { //store  user field data
        $row = &$this->getTable('userfielddata');
        for ($i = 1; $i <= $data['userfields_total']; $i++) {
            $fname = "userfields_" . $i;
            $fid = "userfields_" . $i . "_id";
            $dataid = "userdata_" . $i . "_id";
            $fielddata['id'] = $data[$dataid];
            $fielddata['referenceid'] = $refid;
            $fielddata['field'] = $data[$fid];
            $fielddata['data'] = isset($data[$fname]) ? $data[$fname] : 0;

            if (!$row->bind($fielddata)) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }

            if (!$row->store()) {
                $this->setError($this->_db->getErrorMsg());
                echo $this->_db->getErrorMsg();
                exit;
                return false;
            }
        }
        return true;
    }

    function storeNewinJSJobs() {
        global $resumedata;
        $row = &$this->getTable('userrole');
        $data = JRequest :: get('post');
        $data['dated'] = date('Y-m-d H:i:s');
        $uid = $data['uid'];
        $usertype =  $data['usertype'];
		if($this->userCanRegisterAsEmployer() != true) $usertype = 2; // enforce job seeker

        if ($data['id'])
            return false; // can not edit
        $data['role'] = $usertype;
        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            echo $this->_db->getErrorMsg();
            return false;
        }
        if (!$row->check()) {
            $this->setError($this->_db->getErrorMsg());
            echo $this->_db->getErrorMsg();
            return 2;
        }
        if (!$row->store()) {

            $this->setError($this->_db->getErrorMsg());
            echo $this->_db->getErrorMsg();
            return false;
        }
		$result = $this->assignDefaultPackage($usertype, $uid);
		$result1 = $this->assignDefaultGroup($usertype, $uid);
        return true;
    }

    function checkAssignGroup($uid, $groupval) {
        $db = &$this->getDBO();
        $query = "SELECT  COUNT(user_id) FROM `#__user_usergroup_map` WHERE user_id=" . $uid . " AND group_id=" . $groupval;
        $db->setQuery($query);
        $alreadyassign = $db->loadResult();
        if ($alreadyassign > 0)
            return true;
        else
            return false;
    }

    function &getUserRole($u_id) {
        if ((is_numeric($u_id) == false) || ($u_id == 0) || ($u_id == ''))
            return $false;
        $db = &$this->getDBO();
        if ($u_id != 0) {
            $query = "SELECT userrole.*, role.* 
					FROM `#__js_job_userroles` AS userrole
					JOIN  `#__js_job_roles` AS role ON	userrole.role = role.id
					WHERE userrole.uid  = " . $u_id;
            $db->setQuery($query);
            $role = $db->loadObject();
        }
        return $role;
    }

    function &getConfig($configfor) {
        if (isset($this->_config) == false) {
            $db = &$this->getDBO();
            if ($configfor) {
                $query = "SELECT * FROM `#__js_job_config` WHERE configfor = " . $db->quote($configfor);
                $db->setQuery($query);
                $this->_config = $db->loadObjectList();
            } else {
                $query = "SELECT * FROM `#__js_job_config` WHERE configfor = 'default' ";
                $db->setQuery($query);
                $this->_config = $db->loadObjectList();
                $ct = $this->_ptr . "ost\b/";
                $result = $this->getCurU();
                $cu = $result[2];
                $cvalue = $result[0];
                $evalue = $result[1];
                foreach ($this->_config as $conf) {
                    if ($conf->configname == "defaultcountry") {
                        $defaultcountryid = $this->getIDDefaultCountry($conf->configvalue);
                        $this->_defaultcountry = $defaultcountryid;
                    } elseif ($conf->configname == "job_editor")
                        $this->_job_editor = $conf->configvalue;
                    elseif ($conf->configname == "comp_editor")
                        $this->_comp_editor = $conf->configvalue;
                    elseif ($conf->configname == $cvalue) {
                        if ($conf->configvalue == '0') {
                            foreach ($this->_config as $confg)
                                if ($confg->configname == $evalue)
                                    if (preg_match($ct, $cu))
                                        $confg->configvalue = 0;
                                    else
                                        $confg->configvalue = 1;
                        }
                    }
                }
            }
        }
        return $this->_config;
    }

    function getIDDefaultCountry($defaultcountrycode) {
        $db = &$this->getDBO();
        $query = "SELECT id FROM `#__js_job_countries` WHERE code = " . $db->quote($defaultcountrycode);
        $db->setQuery($query);
        $default_country_id = $db->loadResult();
        return $default_country_id;
    }

    function &getConfigByFor($configfor) {
        $db = &$this->getDBO();
        $query = "SELECT * FROM `#__js_job_config` WHERE configfor = " . $db->quote($configfor);
        $db->setQuery($query);
        $config = $db->loadObjectList();
        $configs = array();
        foreach ($config as $conf) {
            $configs[$conf->configname] = $conf->configvalue;
        }

        return $configs;
    }

    function getMiniMax($title) {
        $minimax = array();
        if ($title)
            $minimax[] = array('value' => JText::_(''), 'text' => $title);
        $minimax[] = array('value' => 1, 'text' => JText::_('JS_MINIMUM'));
        $minimax[] = array('value' => 2, 'text' => JText::_('JS_MAXIMUM'));

        return $minimax;
    }

    function deleteUserFieldData($refid) { //delete user field data
        $db = & JFactory::getDBO();

        $query = "DELETE FROM #__js_job_userfield_data WHERE referenceid = " . $refid;
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        return true;
    }

    function &sendMailtoAdmin($id, $uid, $for) {
        $db = & JFactory::getDBO();
        if ((is_numeric($id) == false) || ($id == 0) || ($id == ''))
            return false;
        if ($uid)
            if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
                return false;
        if (!isset($this->_config))
            $this->getConfig('');
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'currency')
                $currency = $conf->configvalue;
        }
        $emailconfig = $this->getConfigByFor('email');
        $senderName = $emailconfig['mailfromname'];
        $senderEmail = $emailconfig['mailfromaddress'];
        $adminEmail = $emailconfig['adminemailaddress'];
        $newCompany = $emailconfig['email_admin_new_company'];
        $newJob = $emailconfig['email_admin_new_job'];
        $newResume = $emailconfig['email_admin_new_resume'];
        $jobApply = $emailconfig['email_admin_job_apply'];
        $newDepartment = $emailconfig['email_admin_new_department'];
        $newEmployerPackage = $emailconfig['email_admin_employer_package_purchase'];
        $newJobSeekerPackage = $emailconfig['email_admin_jobseeker_package_purchase'];
        switch ($for) {
            case 1: // new company
                $templatefor = 'company-new';
                $issendemail = $newCompany;
                break;
            case 2: // new job
                $templatefor = 'job-new';
                $issendemail = $newJob;
                break;
            case 3: // new resume
                $templatefor = 'resume-new';
                $issendemail = $newResume;
                break;
            case 4: // job apply
                $templatefor = 'jobapply-jobapply';
                $issendemail = $jobApply;
                break;
            case 5: // new department
                $templatefor = 'department-new';
                $issendemail = $newDepartment;
                break;
            case 6: // new employer package
                $templatefor = 'employer-buypackage';
                $issendemail = $newEmployerPackage;
                break;
            case 7: // new job seeker package
                $templatefor = 'jobseeker-buypackage';
                $issendemail = $newJobSeekerPackage;
                break;
        }
        if ($issendemail == 1) {
            $query = "SELECT template.* FROM `#__js_job_emailtemplates` AS template	WHERE template.templatefor = " . $db->Quote($templatefor);
            $db->setQuery($query);
            $template = $db->loadObject();
            $msgSubject = $template->subject;
            $msgBody = $template->body;

            switch ($for) {
                case 1: // new company
                    $jobquery = "SELECT company.name AS companyname, 
                                            company.contactname AS name, company.contactemail AS email 
                                            FROM `#__js_job_companies` AS company
                                            WHERE company.uid = " . $uid . "  AND company.id = " . $id;

                    $db->setQuery($jobquery);
                    $user = $db->loadObject();
                    $EmployerEmail = $user->email;
                    $EmployerName = $user->name;
                    $CompanyName = $user->companyname;

                    $msgSubject = str_replace('{COMPANY_NAME}', $CompanyName, $msgSubject);
                    $msgSubject = str_replace('{EMPLOYER_NAME}', $EmployerName, $msgSubject);
                    $msgBody = str_replace('{COMPANY_NAME}', $CompanyName, $msgBody);
                    $msgBody = str_replace('{EMPLOYER_NAME}', $EmployerName, $msgBody);
                    break;
                case 2: // new job
                    $jobquery = "SELECT job.title, 
                                            company.contactname AS name, company.contactemail AS email 
                                            FROM `#__js_job_jobs` AS job
                                            JOIN `#__js_job_companies` AS company ON company.id = job.companyid
                                            WHERE job.uid = " . $uid . "  AND job.id = " . $id;
                    $db->setQuery($jobquery);
                    $user = $db->loadObject();
                    $EmployerEmail = $user->email;
                    $EmployerName = $user->name;
                    $JobTitle = $user->title;

                    $msgSubject = str_replace('{JOB_TITLE}', $JobTitle, $msgSubject);
                    $msgSubject = str_replace('{EMPLOYER_NAME}', $EmployerName, $msgSubject);
                    $msgBody = str_replace('{JOB_TITLE}', $JobTitle, $msgBody);
                    $msgBody = str_replace('{EMPLOYER_NAME}', $EmployerName, $msgBody);
                    break;
                case 3: // new resume
                    if ($uid) {
                        $jobquery = "SELECT resume.application_title 
                                            ,concat(resume.first_name,' ',resume.last_name) AS name
                                            ,resume.email_address as email
                                            FROM `#__js_job_resume` AS resume
                                            WHERE resume.uid = " . $uid . "  AND resume.id = " . $id;
                    } else {
                        $jobquery = "SELECT resume.application_title, 'Guest' AS name, resume.email_address AS email FROM `#__js_job_resume` AS resume 
									WHERE resume.id = " . $id;
                    }

                    $db->setQuery($jobquery);
                    $user = $db->loadObject();
                    $EmployerEmail = $user->email;
                    $JobSeekerName = $user->name;
                    $ApplicationTitle = $user->application_title;

                    $msgSubject = str_replace('{RESUME_TITLE}', $ApplicationTitle, $msgSubject);
                    $msgSubject = str_replace('{JOBSEEKER_NAME}', $JobSeekerName, $msgSubject);
                    $msgBody = str_replace('{RESUME_TITLE}', $ApplicationTitle, $msgBody);
                    $msgBody = str_replace('{JOBSEEKER_NAME}', $JobSeekerName, $msgBody);
                    break;
                case 4: // not use 

                    $jobquery = "SELECT job.title, employer.name AS employername, employer.email AS employeremail,jobseeker.name AS jobseekername
                                            FROM `#__js_job_jobs` AS job
                                            JOIN `#__users` AS employer ON employer.id = job.uid
                                            JOIN `#__users` AS jobseeker ON jobseeker.id = " . $uid . "
                                            WHERE job.id = " . $id;


                    $db->setQuery($jobquery);
                    $user = $db->loadObject();
                    $EmployerEmail = $user->employeremail;
                    $EmployerName = $user->employername;
                    $JobseekerName = $user->jobseekername;
                    $JobTitle = $user->title;

                    $msgSubject = str_replace('{JOB_TITLE}', $JobTitle, $msgSubject);
                    $msgSubject = str_replace('{EMPLOYER_NAME}', $EmployerName, $msgSubject);
                    $msgSubject = str_replace('{JOBSEEKER_NAME}', $JobseekerName, $msgSubject);
                    $msgBody = str_replace('{JOB_TITLE}', $JobTitle, $msgBody);
                    $msgBody = str_replace('{EMPLOYER_NAME}', $EmployerName, $msgBody);
                    $msgBody = str_replace('{JOBSEEKER_NAME}', $JobseekerName, $msgBody);
                    break;
                case 5: // new department
                    $jobquery = "SELECT department.name AS departmentname, company.name AS companyname 
											,company.contactname as name,company.contactemail as email
                                            FROM `#__js_job_departments` AS department
                                            JOIN `#__js_job_companies` AS company ON company.id = department.companyid
                                            WHERE department.uid = " . $uid . "  AND department.id = " . $id;



                    $db->setQuery($jobquery);
                    $user = $db->loadObject();
                    $EmployerEmail = $user->email;
                    $EmployerName = $user->name;
                    $CompanyName = $user->companyname;
                    $DepartmentTitle = $user->departmentname;

                    $msgSubject = str_replace('{COMPANY_NAME}', $CompanyName, $msgSubject);
                    $msgSubject = str_replace('{DEPARTMENT_NAME}', $DepartmentTitle, $msgSubject);
                    $msgSubject = str_replace('{EMPLOYER_NAME}', $EmployerName, $msgSubject);
                    $msgBody = str_replace('{COMPANY_NAME}', $CompanyName, $msgBody);
                    $msgBody = str_replace('{DEPARTMENT_NAME}', $DepartmentTitle, $msgBody);
                    $msgBody = str_replace('{EMPLOYER_NAME}', $EmployerName, $msgBody);
                    break;
                case 6: // new employer package purchase
                    $jobquery = "SELECT package.title, package.price, user.name, user.email
                                            FROM `#__users` AS user
                                            JOIN `#__js_job_paymenthistory` AS payment ON payment.uid = user.id
                                            JOIN `#__js_job_employerpackages` AS package ON package.id = payment.packageid
                                            WHERE user.id = " . $uid . "  AND payment.id = " . $id . " AND payment.packagefor=1 ";

                    $db->setQuery($jobquery);
                    $user = $db->loadObject();
                    $EmployerEmail = $user->email;
                    $EmployerName = $user->name;
                    $PackageTitle = $user->title;
                    $packagePrice = $user->price;

                    $msgSubject = str_replace('{PACKAGE_NAME}', $PackageTitle, $msgSubject);
                    $msgSubject = str_replace('{EMPLOYER_NAME}', $EmployerName, $msgSubject);
                    $msgBody = str_replace('{PACKAGE_NAME}', $PackageTitle, $msgBody);
                    $msgBody = str_replace('{EMPLOYER_NAME}', $EmployerName, $msgBody);
                    $msgBody = str_replace('{CURRENCY}', $currency, $msgBody);
                    $msgBody = str_replace('{PACKAGE_PRICE}', $packagePrice, $msgBody);
                    break;
                case 7: // new job seeker package purchase
                    $jobquery = "SELECT package.title, package.price, user.name, user.email
                                            FROM `#__users` AS user
                                            JOIN `#__js_job_paymenthistory` AS payment ON payment.uid = user.id 
                                            JOIN `#__js_job_jobseekerpackages` AS package ON package.id = payment.packageid
                                            WHERE user.id = " . $uid . "  AND payment.id = " . $id . " AND payment.packagefor=2 ";

                    $db->setQuery($jobquery);
                    $user = $db->loadObject();
                    $JobSeekerEmail = $user->email;
                    $JobSeekerName = $user->name;
                    $PackageTitle = $user->title;
                    $packagePrice = $user->price;

                    $msgSubject = str_replace('{PACKAGE_NAME}', $PackageTitle, $msgSubject);
                    $msgSubject = str_replace('{JOBSEEKER_NAME}', $JobSeekerName, $msgSubject);
                    $msgBody = str_replace('{PACKAGE_NAME}', $PackageTitle, $msgBody);
                    $msgBody = str_replace('{JOBSEEKER_NAME}', $JobSeekerName, $msgBody);
                    $msgBody = str_replace('{CURRENCY}', $currency, $msgBody);
                    $msgBody = str_replace('{PACKAGE_PRICE}', $packagePrice, $msgBody);
                    break;
            }

            $message = & JFactory::getMailer();
            $message->addRecipient($adminEmail); //to email
            $message->setSubject($msgSubject);
            $siteAddress = JURI::base();
            $message->setBody($msgBody);
            $sender = array($senderEmail, $senderName);
            $message->setSender($sender);
            $message->IsHTML(true);
            $sent = $message->send();
            return $sent;
        }
        return true;
    }

    function sendMail($jobid, $uid, $resumeid) {
        if ($uid)
            if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
                return false;
        if ($jobid)
            if ((is_numeric($jobid) == false) || ($jobid == 0) || ($jobid == ''))
                return false;
        if ($resumeid)
            if ((is_numeric($resumeid) == false) || ($resumeid == 0) || ($resumeid == ''))
                return false;
        $db = & JFactory::getDBO();
        $templatefor = 'jobapply-jobapply';
        $query = "SELECT template.* FROM `#__js_job_emailtemplates` AS template	WHERE template.templatefor = " . $db->Quote($templatefor);
        $db->setQuery($query);
        $template = $db->loadObject();
        $msgSubject = $template->subject;
        $msgBody = $template->body;

        $jobquery = "SELECT company.contactname AS name, company.contactemail AS email, job.title, job.sendemail 
			FROM `#__js_job_companies` AS company
			JOIN `#__js_job_jobs` AS job ON job.companyid = company.id  
			WHERE job.id = " . $jobid;


        $db->setQuery($jobquery);
        $jobuser = $db->loadObject();

        if ($jobuser->sendemail != 0) {
            $userquery = "SELECT name, email FROM `#__users`
			WHERE id = " . $db->Quote($uid);
            $db->setQuery($userquery);
            $user = $db->loadObject();

            $ApplicantName = $user->name;
            $EmployerEmail = $jobuser->email;
            $EmployerName = $jobuser->name;
            $JobTitle = $jobuser->title;

            $msgSubject = str_replace('{JOBSEEKER_NAME}', $ApplicantName, $msgSubject);
            $msgSubject = str_replace('{EMPLOYER_NAME}', $EmployerName, $msgSubject);
            $msgSubject = str_replace('{JOB_TITLE}', $JobTitle, $msgSubject);
            $msgBody = str_replace('{JOBSEEKER_NAME}', $ApplicantName, $msgBody);
            $msgBody = str_replace('{EMPLOYER_NAME}', $EmployerName, $msgBody);
            $msgBody = str_replace('{JOB_TITLE}', $JobTitle, $msgBody);

            $emailconfig = $this->getConfigByFor('email');
            $senderName = $emailconfig['mailfromname'];
            $senderEmail = $emailconfig['mailfromaddress'];
            $check_fields_send = $emailconfig['employer_resume_alert_fields'];

            $message = & JFactory::getMailer();
            $message->addRecipient($EmployerEmail); //to email
            $message->setSubject($msgSubject);
            $siteAddress = JURI::base();
            if ($jobuser->sendemail == 2) { // email with attachment
                if ($check_fields_send) {
                    $this->sendJobApplyResumeAlertEmployer($resumeid, $check_fields_send, $EmployerEmail, $msgSubject, $msgBody, $senderEmail, $senderName);
                } else {
                    $resumequery = "SELECT resume.id, resume.filename FROM `#__js_job_resume` AS resume WHERE resume.id = " . $resumeid;

                    $db->setQuery($resumequery);
                    $resume = $db->loadObject();
                    if ($resume->filename != '') {
                        $iddir = 'resume_' . $resume->id;
                        if (!isset($this->_config))
                            $this->getConfig('');
                        foreach ($this->_config as $conf) {
                            if ($conf->configname == 'data_directory')
                                $datadirectory = $conf->configvalue;
                        }
                        $path = JPATH_BASE . '/' . $datadirectory;
                        $path = $path . '/data/jobseeker/' . $iddir . '/resume/' . $resume->filename;
                        $message->addAttachment($path);
                    }
                }
            }
            $message->setBody($msgBody);
            $sender = array($senderEmail, $senderName);
            $message->setSender($sender);
            $message->IsHTML(true);
            $sent = $message->send();
            return $sent;
        }
    }

    function sendJobApplyResumeAlertEmployer($resumeid, $check_fields_send, $EmployerEmail, $msgSubject, $msgBody, $senderEmail, $senderName) {

        $db = & JFactory::getDBO();
        $user = & JFactory::getUser();
        $uid = $user->id;
        $myresume = 1;
        $jobid = "";
        $message = & JFactory::getMailer();
        $message->addRecipient($EmployerEmail); //to email
        $result = array();

        $message->setSubject($msgSubject);
        $siteAddress = JURI::base();
        $jobseeker_model_object = new JSJobsModelJobseeker;
        $result = $jobseeker_model_object->getResumeViewbyId($uid, $jobid, $resumeid, $myresume);
        $resume = $result[0];
        $resume2 = $result[1];
        $resume3 = $result[2];
        $userfields = $result[6];

        $msgBody .= "<table cellpadding='5' style='border-color: #666;' cellspacing='0' border='0' width='100%'>";
        foreach ($result[3] as $field) {
            switch ($field->field) {
                case "section_personal":
                    $msgBody .= "<tr style='background: #eee;'>";
                    $msgBody .= "<td colspan='2' align='center'><strong>" . JText::_('JS_PERSONAL_INFORMATION') . "</strong></td></tr>";
                    break;
                case "applicationtitle":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->application_title) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_APPLICATION_TITLE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->application_title . "</td></tr>";
                    }
                    break;
                case "firstname":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->first_name) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_FIRST_NAME') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->first_name . "</td></tr>";
                    }
                    break;
                case "middlename":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->middle_name) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_MIDDLE_NAME') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->middle_name . "</td></tr>";
                    }
                    break;
                case "lastname":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->last_name) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_LAST_NAME') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->last_name . "</td></tr>";
                    }
                    break;
                case "emailaddress":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->email_address) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_EMAIL_ADDRESS') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->email_address . "</td></tr>";
                    }
                    break;
                case "homephone":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->home_phone) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_HOME_PHONE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->home_phone . "</td></tr>";
                    }
                    break;
                case "workphone":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->work_phone) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_WORK_PHONE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->work_phone . "</td></tr>";
                    }
                    break;
                case "cell":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->cell) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_CELL') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->cell . "</td></tr>";
                    }
                    break;
                case "gender":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->gender) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_GENDER') . "</strong></td>";
                        $genderText = ($resume->gender == 1) ? JText::_('JS_MALE') : JText::_('JS_FEMALE');
                        $msgBody .= "<td>" . $genderText . "</td></tr>";
                    }
                    break;
                case "Iamavailable":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->iamavailable) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_I_AM_AVAILABLE') . "</strong></td>";
                        $availableText = ($resume->iamavailable == 1) ? JText::_('JS_A_YES') : JText::_('JS_A_NO');
                        $msgBody .= "<td>" . $availableText . "</td></tr>";
                    }
                    break;
                case "nationality":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->nationalitycountry) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_NATIONALITY_COUNTRY') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->nationalitycountry . "</td></tr>";
                    }
                    break;
                case "section_basic":
                    $msgBody .= "<tr style='background: #ee;'>";
                    $msgBody .= "<td colspan='2' align='center'><strong>" . JText::_('JS_BASIC_INFORMATION') . "</strong></td></tr>";
                    break;
                case "category":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->categorytitle) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_CATEGORY') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->categorytitle . "</td></tr>";
                    }
                    break;
                case "salary":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->rangestart) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_DESIRED_SALARY') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->symbol . $resume->rangestart . ' - ' . $resume->symbol . $resume->rangeend . ' ' . JText::_('JS_PERMONTH') . "</td></tr>";
                    }
                    break;
                case "jobtype":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->jobtypetitle) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_WORK_PREFERENCE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->jobtypetitle . "</td></tr>";
                    }
                    break;
                case "heighesteducation":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->heighesteducationtitle) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_HEIGHTESTFINISHEDEDUCATION') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->heighesteducationtitle . "</td></tr>";
                    }
                    break;
                case "totalexperience":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->total_experience) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_TOTAL_EXPERIENCE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->total_experience . "</td></tr>";
                    }
                    break;
                case "startdate":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->date_start) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_DATE_CAN_START') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->date_start . "</td></tr>";
                    }
                    break;
                case "date_of_birth":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->date_of_birth) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_DATE_OF_BIRTH') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->date_of_birth . "</td></tr>";
                    }
                    break;
                case "section_userfields":
                    $msgBody .= "<tr style='background: #ee;'>";
                    $msgBody .= "<td colspan='2' align='center'><strong>" . JText::_($field->fieldtitle) . "</strong></td></tr>";
                    if ($this->_client_auth_key != "") {
                        if (is_object($userfields)) {
                            for ($k = 0; $k < 15; $k++) {
                                $field_title = 'fieldtitle_' . $k;
                                $field_value = 'fieldvalue_' . $k;
                                $style = ($k % 2 == 0) ? 'background: #eee;' : 'background: #ee;';
                                $msgBody .= "<tr style='" . $style . "'>";
                                $msgBody .= "<td><strong>" . $userfields->$field_title . "</strong></td>";
                                $msgBody .= "<td>" . $userfields->$field_value . "</td></tr>";
                            }
                        }
                    } else {
                        $i = 0;
                        foreach ($userfields as $ufield) {
                            //if($field->field == $ufield[0]->id) {
                            if ($ufield[0]->id) {
                                $userfield = $ufield[0];
                                $style = ($i % 2 == 0) ? 'background: #eee;' : 'background: #ee;';
                                $msgBody .= "<tr style='" . $style . "'>";
                                $msgBody .= "<td><strong>" . $userfield->title . "</strong></td>";
                                if ($userfield->type == "checkbox") {
                                    if (isset($ufield[1])) {
                                        $fvalue = $ufield[1]->data;
                                        $userdataid = $ufield[1]->id;
                                    } else {
                                        $fvalue = "";
                                        $userdataid = "";
                                    }
                                    if ($fvalue == '1')
                                        $fvalue = "True";
                                    else
                                        $fvalue = "false";
                                }elseif ($userfield->type == "select") {
                                    if (isset($ufield[2])) {
                                        $fvalue = $ufield[2]->fieldtitle;
                                        $userdataid = $ufield[2]->id;
                                    } else {
                                        $fvalue = "";
                                        $userdataid = "";
                                    }
                                } else {
                                    if (isset($ufield[1])) {
                                        $fvalue = $ufield[1]->data;
                                        $userdataid = $ufield[1]->id;
                                    } else {
                                        $fvalue = "";
                                        $userdataid = "";
                                    }
                                }
                                $msgBody .= "<td>" . $fvalue . "</td></tr>";
                                $i++;
                            }
                        }
                    }
                    break;
                case "section_addresses":
                    $msgBody .= "<tr style='background: #eee;'>";
                    $msgBody .= "<td colspan='2' align='center'><strong>" . JText::_('JS_ADDRESSES') . "</strong></td></tr>";
                    break;
                case "address_country":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->address_country) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_COUNTRY') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->address_country . "</td></tr>";
                    }
                    break;
                case "address_state":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->address_state2) ? 1 : 0;
                        if ($showrow == 0)
                            $showrow = ($resume->address_state) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_STATE') . "</strong></td>";
                        $address_state = ($resume->address_state2) ? $resume->address_state2 : $resume->address_state;
                        $msgBody .= "<td>" . $address_state . "</td></tr>";
                    }
                    break;
                case "address_city":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->address_city2) ? 1 : 0;
                        if ($showrow == 0)
                            $showrow = ($resume->address_city) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_CITY') . "</strong></td>";
                        $address_city = ($resume->address_city2) ? $resume->address_city2 : $resume->address_city;
                        $msgBody .= "<td>" . $address_city . "</td></tr>";
                    }
                    break;
                case "address_zipcode":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->address_zipcode) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_ZIPCODE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->address_zipcode . "</td></tr>";
                    }
                    break;
                case "address_address":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->address) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_ADDRESS') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->address . "</td></tr>";
                    }
                    break;
                case "section_sub_address1":
                    $msgBody .= "<tr style='background: #eee;'>";
                    $msgBody .= "<td colspan='2' align='center'><strong>" . JText::_('JS_ADDRESS1') . "</strong></td></tr>";
                    break;
                case "address1_country":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->address1_country) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_COUNTRY') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->address1_country . "</td></tr>";
                    }
                    break;
                case "address1_state":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->address1_state2) ? 1 : 0;
                        if ($showrow == 0)
                            $showrow = ($resume->address1_state) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_STATE') . "</strong></td>";
                        $address1_state = ($resume->address1_state2) ? $resume->address1_state2 : $resume->address1_state;
                        $msgBody .= "<td>" . $address1_state . "</td></tr>";
                    }
                    break;
                case "address1_city":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->address1_city2) ? 1 : 0;
                        if ($showrow == 0)
                            $showrow = ($resume->address1_city) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_CITY') . "</strong></td>";
                        $address1_city = ($resume->address1_city2) ? $resume->address1_city2 : $resume->address1_city;
                        $msgBody .= "<td>" . $address1_city . "</td></tr>";
                    }
                    break;
                case "address1_zipcode":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->address1_zipcode) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_ZIPCODE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->address1_zipcode . "</td></tr>";
                    }
                    break;
                case "address1_address":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->address1) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_ADDRESS') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->address1 . "</td></tr>";
                    }
                    break;
                case "section_sub_address2":
                    $msgBody .= "<tr style='background: #eee;'>";
                    $msgBody .= "<td colspan='2' align='center'><strong>" . JText::_('JS_ADDRESS2') . "</strong></td></tr>";
                    break;
                case "address2_country":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->address2_country) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_COUNTRY') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->address2_country . "</td></tr>";
                    }
                    break;
                case "address2_state":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->address2_state2) ? 1 : 0;
                        if ($showrow == 0)
                            $showrow = ($resume->address2_state) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_STATE') . "</strong></td>";
                        $address2_state = ($resume->address2_state2) ? $resume->address2_state2 : $resume->address2_state;
                        $msgBody .= "<td>" . $address2_state . "</td></tr>";
                    }
                    break;
                case "address2_city":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->address2_city2) ? 1 : 0;
                        if ($showrow == 0)
                            $showrow = ($resume->address2_city) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_CITY') . "</strong></td>";
                        $address2_city = ($resume->address2_city2) ? $resume->address2_city2 : $resume->address2_city;
                        $msgBody .= "<td>" . $address2_city . "</td></tr>";
                    }
                    break;
                case "address2_zipcode":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->address2_zipcode) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_ZIPCODE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->address2_zipcode . "</td></tr>";
                    }
                    break;
                case "address2_address":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->address2) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_ADDRESS') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->address2 . "</td></tr>";
                    }
                    break;
                case "section_education":
                    $msgBody .= "<tr style='background: #eee;'>";
                    $msgBody .= "<td colspan='2' align='center'><strong>" . JText::_('JS_EDUCATIONS') . "</strong></td></tr>";
                    break;
                case "institute_institute":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->institute) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_SCH_COL_UNI') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->institute . "</td></tr>";
                    }
                    break;
                case "institute_certificate":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->institute_certificate_name) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_CRT_DEG_OTH') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->institute_certificate_name . "</td></tr>";
                    }
                    break;
                case "institute_study_area":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->institute_study_area) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_AREA_OF_STUDY') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->institute_study_area . "</td></tr>";
                    }
                    break;
                case "institute_country":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume2->institute_country) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_COUNTRY') . "</strong></td>";
                        $msgBody .= "<td>" . $resume2->institute_country . "</td></tr>";
                    }
                    break;
                case "institute_state":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume2->institute_state2) ? 1 : 0;
                        if ($showrow == 0)
                            $showrow = ($resume2->institute_state) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_STATE') . "</strong></td>";
                        $institute_state = ($resume2->institute_state2) ? $resume2->institute_state2 : $resume2->institute_state;
                        $msgBody .= "<td>" . $institute_state . "</td></tr>";
                    }
                    break;
                case "institute_city":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume2->institute_city2) ? 1 : 0;
                        if ($showrow == 0)
                            $showrow = ($resume2->institute_city) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_CITY') . "</strong></td>";
                        $institute_city = ($resume2->institute_city2) ? $resume2->institute_city2 : $resume2->institute_city;
                        $msgBody .= "<td>" . $institute_city . "</td></tr>";
                    }
                    break;
                case "section_sub_institute1":
                    $msgBody .= "<tr style='background: #ee;'>";
                    $msgBody .= "<td colspan='2' align='center'><strong>" . JText::_('JS_UNIVERSITY') . "</strong></td></tr>";
                    break;
                case "institute1_institute":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->institute1) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_SCH_COL_UNI') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->institute1 . "</td></tr>";
                    }
                    break;
                case "institute1_certificate":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->institute1_certificate_name) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_CRT_DEG_OTH') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->institute1_certificate_name . "</td></tr>";
                    }
                    break;
                case "institute1_study_area":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->institute1_study_area) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_AREA_OF_STUDY') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->institute1_study_area . "</td></tr>";
                    }
                    break;
                case "institute1_country":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume2->institute1_country) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_COUNTRY') . "</strong></td>";
                        $msgBody .= "<td>" . $resume2->institute1_country . "</td></tr>";
                    }
                    break;
                case "institute1_state":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume2->institute1_state2) ? 1 : 0;
                        if ($showrow == 0)
                            $showrow = ($resume2->institute1_state) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_STATE') . "</strong></td>";
                        $institute1_state = ($resume2->institute1_state2) ? $resume2->institute1_state2 : $resume2->institute1_state;
                        $msgBody .= "<td>" . $institute1_state . "</td></tr>";
                    }
                    break;
                case "institute1_city":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume2->institute1_city2) ? 1 : 0;
                        if ($showrow == 0)
                            $showrow = ($resume2->institute1_city) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_CITY') . "</strong></td>";
                        $institute1_city = ($resume2->institute1_city2) ? $resume2->institute1_city2 : $resume2->institute1_city;
                        $msgBody .= "<td>" . $institute1_city . "</td></tr>";
                    }
                    break;
                case "section_sub_institute2":
                    $msgBody .= "<tr style='background: #ee;'>";
                    $msgBody .= "<td colspan='2' align='center'><strong>" . JText::_('JS_GRADE_SCHOOL') . "</strong></td></tr>";
                    break;
                case "institute2_institute":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->institute2) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_SCH_COL_UNI') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->institute2 . "</td></tr>";
                    }
                    break;
                case "institute2_certificate":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->institute2_certificate_name) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_CRT_DEG_OTH') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->institute2_certificate_name . "</td></tr>";
                    }
                    break;
                case "institute2_study_area":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->institute2_study_area) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_AREA_OF_STUDY') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->institute2_study_area . "</td></tr>";
                    }
                    break;
                case "institute2_country":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume2->institute2_country) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_COUNTRY') . "</strong></td>";
                        $msgBody .= "<td>" . $resume2->institute2_country . "</td></tr>";
                    }
                    break;
                case "institute2_state":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume2->institute2_state2) ? 1 : 0;
                        if ($showrow == 0)
                            $showrow = ($resume2->institute2_state) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_STATE') . "</strong></td>";
                        $institute2_state = ($resume2->institute2_state2) ? $resume2->institute2_state2 : $resume2->institute2_state;
                        $msgBody .= "<td>" . $institute2_state . "</td></tr>";
                    }
                    break;
                case "institute2_city":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume2->institute2_city2) ? 1 : 0;
                        if ($showrow == 0)
                            $showrow = ($resume2->institute2_city) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_CITY') . "</strong></td>";
                        $institute2_city = ($resume2->institute2_city2) ? $resume2->institute2_city2 : $resume2->institute2_city;
                        $msgBody .= "<td>" . $institute2_city . "</td></tr>";
                    }
                    break;
                case "section_sub_institute3":
                    $msgBody .= "<tr style='background: #ee;'>";
                    $msgBody .= "<td colspan='2' align='center'><strong>" . JText::_('JS_OTHER_SCHOOL') . "</strong></td></tr>";
                    break;
                case "institute3_institute":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->institute3) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_SCH_COL_UNI') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->institute3 . "</td></tr>";
                    }
                    break;
                case "institute3_certificate":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->institute3_certificate_name) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_CRT_DEG_OTH') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->institute3_certificate_name . "</td></tr>";
                    }
                    break;
                case "institute3_study_area":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->institute3_study_area) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_AREA_OF_STUDY') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->institute3_study_area . "</td></tr>";
                    }
                    break;
                case "institute3_country":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume2->institute3_country) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_COUNTRY') . "</strong></td>";
                        $msgBody .= "<td>" . $resume2->institute3_country . "</td></tr>";
                    }
                    break;
                case "institute3_state":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume2->institute3_state2) ? 1 : 0;
                        if ($showrow == 0)
                            $showrow = ($resume2->institute3_state) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_STATE') . "</strong></td>";
                        $institute3_state = ($resume2->institute3_state2) ? $resume2->institute3_state2 : $resume2->institute3_state;
                        $msgBody .= "<td>" . $institute3_state . "</td></tr>";
                    }
                    break;
                case "institute3_city":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume2->institute3_city2) ? 1 : 0;
                        if ($showrow == 0)
                            $showrow = ($resume2->institute3_city) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_CITY') . "</strong></td>";
                        $institute3_city = ($resume2->institute3_city2) ? $resume2->institute3_city2 : $resume2->institute3_city;
                        $msgBody .= "<td>" . $institute3_city . "</td></tr>";
                    }
                    break;
                case "section_employer":
                    $msgBody .= "<tr style='background: #ee;'>";
                    $msgBody .= "<td colspan='2' align='center'><strong>" . JText::_('JS_EMPLOYERS') . "</strong></td></tr>";
                    break;
                case "employer_employer":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_EMPLOYER') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer . "</td></tr>";
                    }
                    break;
                case "employer_position":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer_position) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_POSITION') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer_position . "</td></tr>";
                    }
                    break;
                case "employer_resp":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer_resp) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_RESPONSIBILITIES') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer_resp . "</td></tr>";
                    }
                    break;
                case "employer_pay_upon_leaving":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer_pay_upon_leaving) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_PAY_UPON_LEAVING') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer_pay_upon_leaving . "</td></tr>";
                    }
                    break;
                case "employer_supervisor":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer_supervisor) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_SUPERVISOR') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer_supervisor . "</td></tr>";
                    }
                    break;
                case "employer_from_date":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer_from_date) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_FROM_DATE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer_from_date . "</td></tr>";
                    }
                    break;
                case "employer_to_date":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer_to_date) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_TO_DATE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer_to_date . "</td></tr>";
                    }
                    break;
                case "employer_leave_reason":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer_leave_reason) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_LEAVING_REASON') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer_leave_reason . "</td></tr>";
                    }
                    break;
                case "employer_country":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume2->employer_country) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_COUNTRY') . "</strong></td>";
                        $msgBody .= "<td>" . $resume2->employer_country . "</td></tr>";
                    }
                    break;
                case "employer_state":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume2->employer_state2) ? 1 : 0;
                        if ($showrow == 0)
                            $showrow = ($resume2->employer_state) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_STATE') . "</strong></td>";
                        $employer_state = ($resume2->employer_state2) ? $resume2->employer_state2 : $resume2->employer_state;
                        $msgBody .= "<td>" . $employer_state . "</td></tr>";
                    }
                    break;
                case "employer_city":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume2->employer_city2) ? 1 : 0;
                        if ($showrow == 0)
                            $showrow = ($resume2->employer_city) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_CITY') . "</strong></td>";
                        $employer_city = ($resume2->employer_city2) ? $resume2->employer_city2 : $resume2->employer_city;
                        $msgBody .= "<td>" . $employer_city . "</td></tr>";
                    }
                    break;
                case "employer_zip":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer_zip) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_ZIPCODE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer_zip . "</td></tr>";
                    }
                    break;
                case "employer_address":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer_address) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_ADDRESS') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer_address . "</td></tr>";
                    }
                    break;
                case "employer_phone":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer_phone) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_PHONE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer_phone . "</td></tr>";
                    }
                    break;
                case "section_sub_employer1":
                    $msgBody .= "<tr style='background: #ee;'>";
                    $msgBody .= "<td colspan='2' align='center'><strong>" . JText::_('JS_PRIOR_EMPLOYER_1') . "</strong></td></tr>";
                    break;
                case "employer1_employer":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer1) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_EMPLOYER') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer1 . "</td></tr>";
                    }
                    break;
                case "employer1_position":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer1_position) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_POSITION') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer1_position . "</td></tr>";
                    }
                    break;
                case "employer1_resp":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer1_resp) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_RESPONSIBILITIES') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer1_resp . "</td></tr>";
                    }
                    break;
                case "employer1_pay_upon_leaving":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer1_pay_upon_leaving) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_PAY_UPON_LEAVING') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer1_pay_upon_leaving . "</td></tr>";
                    }
                    break;
                case "employer1_supervisor":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer1_supervisor) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_SUPERVISOR') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer1_supervisor . "</td></tr>";
                    }
                    break;
                case "employer1_from_date":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer1_from_date) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_FROM_DATE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer1_from_date . "</td></tr>";
                    }
                    break;
                case "employer1_to_date":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer1_to_date) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_TO_DATE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer1_to_date . "</td></tr>";
                    }
                    break;
                case "employer1_leave_reason":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer1_leave_reason) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_LEAVING_REASON') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer1_leave_reason . "</td></tr>";
                    }
                    break;
                case "employer1_country":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume2->employer1_country) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_COUNTRY') . "</strong></td>";
                        $msgBody .= "<td>" . $resume2->employer1_country . "</td></tr>";
                    }
                    break;
                case "employer1_state":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume2->employer1_state2) ? 1 : 0;
                        if ($showrow == 0)
                            $showrow = ($resume2->employer1_state) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_STATE') . "</strong></td>";
                        $employer1_state = ($resume2->employer1_state2) ? $resume2->employer1_state2 : $resume2->employer1_state;
                        $msgBody .= "<td>" . $employer1_state . "</td></tr>";
                    }
                    break;
                case "employer1_city":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume2->employer1_city2) ? 1 : 0;
                        if ($showrow == 0)
                            $showrow = ($resume2->employer1_city) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_CITY') . "</strong></td>";
                        $employer1_city = ($resume2->employer1_city2) ? $resume2->employer1_city2 : $resume2->employer1_city;
                        $msgBody .= "<td>" . $employer1_city . "</td></tr>";
                    }
                    break;
                case "employer1_zip":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer1_zip) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_ZIPCODE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer1_zip . "</td></tr>";
                    }
                    break;
                case "employer1_address":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer1_address) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_ADDRESS') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer1_address . "</td></tr>";
                    }
                    break;
                case "employer1_phone":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer1_phone) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_PHONE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer1_phone . "</td></tr>";
                    }
                    break;
                case "section_sub_employer2":
                    $msgBody .= "<tr style='background: #ee;'>";
                    $msgBody .= "<td colspan='2' align='center'><strong>" . JText::_('JS_PRIOR_EMPLOYER_2') . "</strong></td></tr>";
                    break;
                case "employer2_employer":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer2) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_EMPLOYER') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer2 . "</td></tr>";
                    }
                    break;
                case "employer2_position":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer2_position) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_POSITION') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer2_position . "</td></tr>";
                    }
                    break;
                case "employer2_resp":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer2_resp) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_RESPONSIBILITIES') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer2_resp . "</td></tr>";
                    }
                    break;
                case "employer2_pay_upon_leaving":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer2_pay_upon_leaving) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_PAY_UPON_LEAVING') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer2_pay_upon_leaving . "</td></tr>";
                    }
                    break;
                case "employer2_supervisor":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer2_supervisor) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_SUPERVISOR') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer2_supervisor . "</td></tr>";
                    }
                    break;
                case "employer2_from_date":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer2_from_date) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_FROM_DATE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer2_from_date . "</td></tr>";
                    }
                    break;
                case "employer2_to_date":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer2_to_date) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_TO_DATE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer2_to_date . "</td></tr>";
                    }
                    break;
                case "employer2_leave_reason":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer2_leave_reason) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_LEAVING_REASON') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer2_leave_reason . "</td></tr>";
                    }
                    break;
                case "employer2_country":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume2->employer2_country) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_COUNTRY') . "</strong></td>";
                        $msgBody .= "<td>" . $resume2->employer2_country . "</td></tr>";
                    }
                    break;
                case "employer2_state":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume2->employer2_state2) ? 1 : 0;
                        if ($showrow == 0)
                            $showrow = ($resume2->employer2_state) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_STATE') . "</strong></td>";
                        $employer2_state = ($resume2->employer2_state2) ? $resume2->employer2_state2 : $resume2->employer2_state;
                        $msgBody .= "<td>" . $employer2_state . "</td></tr>";
                    }
                    break;
                case "employer2_city":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume2->employer2_city2) ? 1 : 0;
                        if ($showrow == 0)
                            $showrow = ($resume2->employer2_city) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_CITY') . "</strong></td>";
                        $employer2_city = ($resume2->employer2_city2) ? $resume2->employer2_city2 : $resume2->employer2_city;
                        $msgBody .= "<td>" . $employer2_city . "</td></tr>";
                    }
                    break;
                case "employer2_zip":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer2_zip) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_ZIPCODE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer2_zip . "</td></tr>";
                    }
                    break;
                case "employer2_address":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer2_address) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_ADDRESS') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer2_address . "</td></tr>";
                    }
                    break;
                case "employer2_phone":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer2_phone) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_PHONE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer2_phone . "</td></tr>";
                    }
                    break;
                case "section_sub_employer3":
                    $msgBody .= "<tr style='background: #ee;'>";
                    $msgBody .= "<td colspan='2' align='center'><strong>" . JText::_('JS_PRIOR_EMPLOYER_3') . "</strong></td></tr>";
                    break;
                case "employer3_employer":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer3) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_EMPLOYER') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer3 . "</td></tr>";
                    }
                    break;
                case "employer3_position":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer3_position) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_POSITION') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer3_position . "</td></tr>";
                    }
                    break;
                case "employer3_resp":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer3_resp) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_RESPONSIBILITIES') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer3_resp . "</td></tr>";
                    }
                    break;
                case "employer3_pay_upon_leaving":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer3_pay_upon_leaving) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_PAY_UPON_LEAVING') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer3_pay_upon_leaving . "</td></tr>";
                    }
                    break;
                case "employer3_supervisor":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer3_supervisor) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_SUPERVISOR') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer3_supervisor . "</td></tr>";
                    }
                    break;
                case "employer3_from_date":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer3_from_date) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_FROM_DATE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer3_from_date . "</td></tr>";
                    }
                    break;
                case "employer3_to_date":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer3_to_date) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_TO_DATE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer3_to_date . "</td></tr>";
                    }
                    break;
                case "employer3_leave_reason":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer3_leave_reason) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_LEAVING_REASON') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer3_leave_reason . "</td></tr>";
                    }
                    break;
                case "employer3_country":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume2->employer3_country) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_COUNTRY') . "</strong></td>";
                        $msgBody .= "<td>" . $resume2->employer3_country . "</td></tr>";
                    }
                    break;
                case "employer3_state":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume2->employer3_state2) ? 1 : 0;
                        if ($showrow == 0)
                            $showrow = ($resume2->employer3_state) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_STATE') . "</strong></td>";
                        $employer3_state = ($resume2->employer3_state2) ? $resume2->employer3_state2 : $resume2->employer3_state;
                        $msgBody .= "<td>" . $employer3_state . "</td></tr>";
                    }
                    break;
                case "employer3_city":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume2->employer3_city2) ? 1 : 0;
                        if ($showrow == 0)
                            $showrow = ($resume2->employer3_city) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_CITY') . "</strong></td>";
                        $employer3_city = ($resume2->employer3_city2) ? $resume2->employer3_city2 : $resume2->employer3_city;
                        $msgBody .= "<td>" . $employer3_city . "</td></tr>";
                    }
                    break;
                case "employer3_zip":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer3_zip) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_ZIPCODE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer3_zip . "</td></tr>";
                    }
                    break;
                case "employer3_address":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer3_address) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_ADDRESS') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer3_address . "</td></tr>";
                    }
                    break;
                case "employer3_phone":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->employer3_phone) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_PHONE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->employer3_phone . "</td></tr>";
                    }
                    break;
                case "section_skills":
                    $msgBody .= "<tr style='background: #eee;'>";
                    $msgBody .= "<td colspan='2' align='center'><strong>" . JText::_('JS_SKILLS') . "</strong></td></tr>";
                    break;
                case "driving_license":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->driving_license) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_HAVE_DRIVING_LICENSE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->driving_license . "</td></tr>";
                    }
                    break;
                case "license_no":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->license_no) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_YSE_LICENSE_NO') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->license_no . "</td></tr>";
                    }
                    break;
                case "license_country":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->license_country) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_YSE_LICENSE_COUNTRY') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->license_country . "</td></tr>";
                    }
                    break;
                case "skills":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->skills) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_SKILLS') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->skills . "</td></tr>";
                    }
                    break;
                case "section_resumeeditor":
                    $msgBody .= "<tr style='background: #ee;'>";
                    $msgBody .= "<td colspan='2' align='center'><strong>" . JText::_('JS_RESUME_EDITOR') . "</strong></td></tr>";
                    break;
                case "editor":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->resume) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_RESUME') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->resume . "</td></tr>";
                    }
                    break;
                case "section_references":
                    $msgBody .= "<tr style='background: #ee;'>";
                    $msgBody .= "<td colspan='2' align='center'><strong>" . JText::_('JS_REFERENCE1') . "</strong></td></tr>";
                    break;
                case "reference_name":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->reference_name) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_NAME') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->reference_name . "</td></tr>";
                    }
                    break;
                case "reference_country":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume3->reference_country) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_COUNTRY') . "</strong></td>";
                        $msgBody .= "<td>" . $resume3->reference_country . "</td></tr>";
                    }
                    break;
                case "reference_state":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume3->reference_state2) ? 1 : 0;
                        if ($showrow == 0)
                            $showrow = ($resume3->reference_state) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_STATE') . "</strong></td>";
                        $reference_state = ($resume3->reference_state2) ? $resume3->reference_state2 : $resume3->reference_state;
                        $msgBody .= "<td>" . $reference_state . "</td></tr>";
                    }
                    break;
                case "reference_city":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume3->reference_city2) ? 1 : 0;
                        if ($showrow == 0)
                            $showrow = ($resume3->reference_city) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_CITY') . "</strong></td>";
                        $reference_city = ($resume3->reference_city2) ? $resume3->reference_city2 : $resume3->reference_city;
                        $msgBody .= "<td>" . $reference_city . "</td></tr>";
                    }
                    break;
                case "reference_zipcode":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->reference_zipcode) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_ZIPCODE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->reference_zipcode . "</td></tr>";
                    }
                    break;
                case "reference_address":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->reference_address) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_ADDRESS') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->reference_address . "</td></tr>";
                    }
                    break;
                case "reference_phone":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->reference_phone) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_PHONE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->reference_phone . "</td></tr>";
                    }
                    break;
                case "reference_relation":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->reference_relation) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_RELATION') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->reference_relation . "</td></tr>";
                    }
                    break;
                case "reference_years":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->reference_years) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_YEARS') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->reference_years . "</td></tr>";
                    }
                    break;
                case "section_sub_reference1":
                    $msgBody .= "<tr style='background: #ee;'>";
                    $msgBody .= "<td colspan='2' align='center'><strong>" . JText::_('JS_REFERENCE2') . "</strong></td></tr>";
                    break;
                case "reference1_name":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->reference1_name) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_NAME') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->reference1_name . "</td></tr>";
                    }
                    break;
                case "reference1_country":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume3->reference1_country) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_COUNTRY') . "</strong></td>";
                        $msgBody .= "<td>" . $resume3->reference1_country . "</td></tr>";
                    }
                    break;
                case "reference1_state":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume3->reference1_state2) ? 1 : 0;
                        if ($showrow == 0)
                            $showrow = ($resume3->reference1_state) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_STATE') . "</strong></td>";
                        $reference1_state = ($resume3->reference1_state2) ? $resume3->reference1_state2 : $resume3->reference1_state;
                        $msgBody .= "<td>" . $reference1_state . "</td></tr>";
                    }
                    break;
                case "reference1_city":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume3->reference1_city2) ? 1 : 0;
                        if ($showrow == 0)
                            $showrow = ($resume3->reference1_city) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_CITY') . "</strong></td>";
                        $reference1_city = ($resume3->reference1_city2) ? $resume3->reference1_city2 : $resume3->reference1_city;
                        $msgBody .= "<td>" . $reference1_city . "</td></tr>";
                    }
                    break;
                case "reference1_zipcode":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->reference1_zipcode) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_ZIPCODE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->reference1_zipcode . "</td></tr>";
                    }
                    break;
                case "reference1_address":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->reference1_address) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_ADDRESS') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->reference1_address . "</td></tr>";
                    }
                    break;
                case "reference1_phone":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->reference1_phone) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_PHONE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->reference1_phone . "</td></tr>";
                    }
                    break;
                case "reference1_relation":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->reference1_relation) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_RELATION') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->reference1_relation . "</td></tr>";
                    }
                    break;
                case "reference1_years":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->reference1_years) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_YEARS') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->reference1_years . "</td></tr>";
                    }
                    break;
                case "section_sub_reference2":
                    $msgBody .= "<tr style='background: #ee;'>";
                    $msgBody .= "<td colspan='2' align='center'><strong>" . JText::_('JS_REFERENCE3') . "</strong></td></tr>";
                    break;
                case "reference2_name":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->reference2_name) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_NAME') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->reference2_name . "</td></tr>";
                    }
                    break;
                case "reference2_country":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume3->reference2_country) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_COUNTRY') . "</strong></td>";
                        $msgBody .= "<td>" . $resume3->reference2_country . "</td></tr>";
                    }
                    break;
                case "reference2_state":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume3->reference2_state2) ? 1 : 0;
                        if ($showrow == 0)
                            $showrow = ($resume3->reference2_state) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_STATE') . "</strong></td>";
                        $reference2_state = ($resume3->reference2_state2) ? $resume3->reference2_state2 : $resume3->reference2_state;
                        $msgBody .= "<td>" . $reference2_state . "</td></tr>";
                    }
                    break;
                case "reference2_city":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume3->reference2_city2) ? 1 : 0;
                        if ($showrow == 0)
                            $showrow = ($resume3->reference2_city) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_CITY') . "</strong></td>";
                        $reference2_city = ($resume3->reference2_city2) ? $resume3->reference2_city2 : $resume3->reference2_city;
                        $msgBody .= "<td>" . $reference2_city . "</td></tr>";
                    }
                    break;
                case "reference2_zipcode":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->reference2_zipcode) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_ZIPCODE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->reference2_zipcode . "</td></tr>";
                    }
                    break;
                case "reference2_address":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->reference2_address) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_ADDRESS') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->reference2_address . "</td></tr>";
                    }
                    break;
                case "reference2_phone":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->reference2_phone) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_PHONE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->reference2_phone . "</td></tr>";
                    }
                    break;
                case "reference2_relation":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->reference2_relation) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_RELATION') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->reference2_relation . "</td></tr>";
                    }
                    break;
                case "reference2_years":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->reference2_years) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_YEARS') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->reference2_years . "</td></tr>";
                    }
                    break;
                case "section_sub_reference3":
                    $msgBody .= "<tr style='background: #ee;'>";
                    $msgBody .= "<td colspan='2' align='center'><strong>" . JText::_('JS_REFERENCE4') . "</strong></td></tr>";
                    break;

                case "reference3_name":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->reference3_name) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_NAME') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->reference3_name . "</td></tr>";
                    }
                    break;
                case "reference3_country":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume3->reference3_country) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_COUNTRY') . "</strong></td>";
                        $msgBody .= "<td>" . $resume3->reference3_country . "</td></tr>";
                    }
                    break;
                case "reference3_state":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume3->reference3_state2) ? 1 : 0;
                        if ($showrow == 0)
                            $showrow = ($resume3->reference3_state) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_STATE') . "</strong></td>";
                        $reference3_state = ($resume3->reference3_state2) ? $resume3->reference3_state2 : $resume3->reference3_state;
                        $msgBody .= "<td>" . $reference3_state . "</td></tr>";
                    }
                    break;
                case "reference3_city":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume3->reference3_city2) ? 1 : 0;
                        if ($showrow == 0)
                            $showrow = ($resume3->reference3_city) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_CITY') . "</strong></td>";
                        $reference3_city = ($resume3->reference3_city2) ? $resume3->reference3_city2 : $resume3->reference3_city;
                        $msgBody .= "<td>" . $reference3_city . "</td></tr>";
                    }
                    break;
                case "reference3_zipcode":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->reference3_zipcode) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_ZIPCODE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->reference3_zipcode . "</td></tr>";
                    }
                    break;
                case "reference3_address":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->reference3_address) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_ADDRESS') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->reference3_address . "</td></tr>";
                    }
                    break;
                case "reference3_phone":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->reference3_phone) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_PHONE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->reference3_phone . "</td></tr>";
                    }
                    break;
                case "reference_relation":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->reference3_relation) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_RELATION') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->reference3_relation . "</td></tr>";
                    }
                    break;
                case "reference_years":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->reference3_years) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_YEARS') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->reference3_years . "</td></tr>";
                    }
                    break;
                case "section_languages":
                    $msgBody .= "<tr style='background: #ee;'>";
                    $msgBody .= "<td colspan='2' align='center'><strong>" . JText::_('JS_LANGUAGE1') . "</strong></td></tr>";
                    break;
                case "language_name":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->language) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_LANGUAGE_NAME') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->language . "</td></tr>";
                    }
                    break;
                case "language_reading":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->language_reading) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_LANGUAGE_READ') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->language_reading . "</td></tr>";
                    }
                    break;
                case "language_writing":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->language_writing) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_LANGUAGE_WRITE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->language_writing . "</td></tr>";
                    }
                    break;
                case "language_understading":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->language_understanding) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_LANGUAGE_UNDERSTAND') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->language_understanding . "</td></tr>";
                    }
                    break;
                case "language_where_learned":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->language_where_learned) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_LANGUAGE_LEARN_INSTITUTE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->language_where_learned . "</td></tr>";
                    }
                    break;
                case "section_sub_language1":
                    $msgBody .= "<tr style='background: #ee;'>";
                    $msgBody .= "<td colspan='2' align='center'><strong>" . JText::_('JS_LANGUAGE2') . "</strong></td></tr>";
                    break;
                case "language1_name":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->language1) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_LANGUAGE_NAME') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->language1 . "</td></tr>";
                    }
                    break;
                case "language1_reading":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->languag1e_reading) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_LANGUAGE_READ') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->language1_reading . "</td></tr>";
                    }
                    break;
                case "language1_writing":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->language1_writing) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_LANGUAGE_WRITE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->language1_writing . "</td></tr>";
                    }
                    break;
                case "language1_understading":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->language1_understanding) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_LANGUAGE_UNDERSTAND') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->language1_understanding . "</td></tr>";
                    }
                    break;
                case "language1_where_learned":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->language1_where_learned) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_LANGUAGE_LEARN_INSTITUTE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->language1_where_learned . "</td></tr>";
                    }
                    break;
                case "section_sub_language2":
                    $msgBody .= "<tr style='background: #ee;'>";
                    $msgBody .= "<td colspan='2' align='center'><strong>" . JText::_('JS_LANGUAGE3') . "</strong></td></tr>";
                    break;
                case "language2_name":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->language2) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_LANGUAGE_NAME') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->language2 . "</td></tr>";
                    }
                    break;
                case "language2_reading":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->language2_reading) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_LANGUAGE_READ') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->language2_reading . "</td></tr>";
                    }
                    break;
                case "language2_writing":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->language2_writing) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_LANGUAGE_WRITE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->language2_writing . "</td></tr>";
                    }
                    break;
                case "language2_understading":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->language2_understanding) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_LANGUAGE_UNDERSTAND') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->language2_understanding . "</td></tr>";
                    }
                    break;
                case "language2_where_learned":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->language2_where_learned) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_LANGUAGE_LEARN_INSTITUTE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->language2_where_learned . "</td></tr>";
                    }
                    break;
                case "section_sub_language3":
                    $msgBody .= "<tr style='background: #ee;'>";
                    $msgBody .= "<td colspan='2' align='center'><strong>" . JText::_('JS_LANGUAGE4') . "</strong></td></tr>";
                    break;
                case "language3_name":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->language3) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_LANGUAGE_NAME') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->language3 . "</td></tr>";
                    }
                    break;
                case "language3_reading":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->language3_reading) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_LANGUAGE_READ') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->language3_reading . "</td></tr>";
                    }
                    break;
                case "language3_writing":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->language3_writing) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_LANGUAGE_WRITE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->language3_writing . "</td></tr>";
                    }
                    break;
                case "language3_understading":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->language3_understading) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #ee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_LANGUAGE_UNDERSTAND') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->language3_understanding . "</td></tr>";
                    }
                    break;
                case "language3_where_learned":
                    $showrow = 1;
                    if ($check_fields_send == 2) {
                        $showrow = ($resume->language3_where_learned) ? 1 : 0;
                    }
                    if ($showrow == 1) {
                        $msgBody .= "<tr style='background: #eee;'>";
                        $msgBody .= "<td><strong>" . JText::_('JS_LANGUAGE_LEARN_INSTITUTE') . "</strong></td>";
                        $msgBody .= "<td>" . $resume->language3_where_learned . "</td></tr>";
                    }
                    break;
            }
        }
        $msgBody .= "</table>";
        if ($resume->filename != '') {
            $iddir = 'resume_' . $resumeid;
            if (!isset($this->_config))
                $this->_config = $this->getConfig('');
            foreach ($this->_config as $conf) {
                if ($conf->configname == 'data_directory')
                    $datadirectory = $conf->configvalue;
            }
            $path = JPATH_BASE . '/' . $datadirectory;
            $path = $path . '/data/jobseeker/' . $iddir . '/resume/' . $resume->filename;
            $message->addAttachment($path);
        }

        $message->setBody($msgBody);
        $sender = array($senderEmail, $senderName);
        $message->setSender($sender);
        $message->IsHTML(true);
        $sent = $message->send();
        return 1;
    }

    function sendMailtoVisitor($jobid) {
        if ($jobid)
            if ((is_numeric($jobid) == false) || ($jobid == 0) || ($jobid == ''))
                return false;
        $db = & JFactory::getDBO();
        $templatefor = 'job-alert-visitor';
        $query = "SELECT template.* FROM `#__js_job_emailtemplates` AS template	WHERE template.templatefor = " . $db->Quote($templatefor);
        $db->setQuery($query);
        $template = $db->loadObject();
        $msgSubject = $template->subject;
        $msgBody = $template->body;
        $jobquery = "SELECT job.title, job.jobstatus,job.jobid AS jobid, company.name AS companyname, cat.cat_title AS cattitle,job.sendemail,company.contactemail,company.contactname
                              FROM `#__js_job_jobs` AS job
                              JOIN `#__js_job_companies` AS company ON company.id = job.companyid
                              JOIN `#__js_job_categories` AS cat ON cat.id = job.jobcategory
                              WHERE job.id = " . $jobid;
        $db->setQuery($jobquery);
        $jobuser = $db->loadObject();
        if ($jobuser->jobstatus == 1) {

            $CompanyName = $jobuser->companyname;
            $JobCategory = $jobuser->cattitle;
            $JobTitle = $jobuser->title;
            if ($jobuser->jobstatus == 1)
                $JobStatus = JText::_('JS_APPROVED');
            else
                $JobStatus = JText::_('JS_WAITING_FOR_APPROVEL');
            $EmployerEmail = $jobuser->contactemail;
            $ContactName = $jobuser->contactname;


            $msgSubject = str_replace('{COMPANY_NAME}', $CompanyName, $msgSubject);
            $msgSubject = str_replace('{CONTACT_NAME}', $ContactName, $msgSubject);
            $msgSubject = str_replace('{JOB_CATEGORY}', $JobCategory, $msgSubject);
            $msgSubject = str_replace('{JOB_TITLE}', $JobTitle, $msgSubject);
            $msgSubject = str_replace('{JOB_STATUS}', $JobStatus, $msgSubject);
            $msgBody = str_replace('{COMPANY_NAME}', $CompanyName, $msgBody);
            $msgBody = str_replace('{CONTACT_NAME}', $ContactName, $msgBody);
            $msgBody = str_replace('{JOB_CATEGORY}', $JobCategory, $msgBody);
            $msgBody = str_replace('{JOB_TITLE}', $JobTitle, $msgBody);
            $msgBody = str_replace('{JOB_STATUS}', $JobStatus, $msgBody);

            $config = $this->getConfigByFor('default');
            if ($config['visitor_can_edit_job'] == 1) {
                $path = JURI::base();
                $path .= 'index.php?option=com_jsjobs&view=employer&layout=myjobs&email=' . $jobuser->contactemail . '&jobid=' . $jobuser->jobid;
                $text = '<br><a href="' . $path . '" target="_blank" >' . JText::_('JS_CLICK_HERE_TO_EDIT_JOB') . '</a>';
                $msgBody = str_replace('{JOB_LINK}', $text, $msgBody);
            } else {// delete {JOB_LINK} if not allowed to edit job
                $msgBody = str_replace('{JOB_LINK}', '', $msgBody);
            }

            $emailconfig = $this->getConfigByFor('email');
            $senderName = $emailconfig['mailfromname'];
            $senderEmail = $emailconfig['mailfromaddress'];

            $message = & JFactory::getMailer();
            $message->addRecipient($EmployerEmail); //to email

            $message->setSubject($msgSubject);
            $siteAddress = JURI::base();
            $message->setBody($msgBody);
            $sender = array($senderEmail, $senderName);
            $message->setSender($sender);
            $message->IsHTML(true);
            $sent = $message->send();
            return $sent;
            //if ($sent != 1) echo 'Error sending email';
        }
    }

    function &listModuleSearchAddressData($data, $val, $for) {
        $db = &$this->getDBO();
        $methodname = $for . 'dochange';

        if ($data == 'country') {  // country
            $query = "SELECT is AS code, name FROM `#__js_job_countries` WHERE enabled = 'Y'";
            if ($this->_client_auth_key != "")
                $query.=" AND serverid!='' AND serverid!=0";
            $query.=" ORDER BY name ASC";
            $db->setQuery($query);
            $result = $db->loadObjectList();

            if (empty($result)) {
                $return_value = "<input class='inputbox' type='text' name='country' size='27' maxlength='100'  />";
            } else {
                $return_value = "<select name='country' class='inputbox'  onChange=\"$methodname('state', this.value)\">\n";
                $return_value .= "<option value=''>" . JText::_('JS_SEARCH_ALL') . "</option>\n";

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
                $return_value = "<input class='inputbox' type='text' name='state' size='27' maxlength='100'  />";
            } else {
                $return_value = "<select name='state' class='inputbox'  onChange=\"$methodname('city', this.value)\">\n";
                $return_value .= "<option value=''>" . JText::_('JS_SEARCH_ALL') . "</option>\n";

                foreach ($result as $row) {
                    $return_value .= "<option value=\"$row->code\" >$row->name</option> \n";
                }
                $return_value .= "</select>\n";
            }
        } else if ($data == 'city') { // city
            $query = "SELECT id AS code, name from `#__js_job_cities`  WHERE enabled = 'Y' AND stateid= " . $val;
            if ($this->_client_auth_key != "")
                $query.=" AND serverid!='' AND serverid!=0";
            $query.=" ORDER BY name ASC";
            $db->setQuery($query);
            $result = $db->loadObjectList();
            //if (mysql_num_rows($result)== 0)
            if (empty($result)) {
                $return_value = "<input class='inputbox' type='text' name='city' size='27' maxlength='100'  />";
            } else {
                $return_value = "<select name='city' class='inputbox'  onChange=\"$methodname('zipcode', this.value)\">\n";
                $return_value .= "<option value=''>" . JText::_('JS_SEARCH_ALL') . "</option>\n";


                foreach ($result as $row) {
                    $return_value .= "<option value=\"$row->code\" >$row->name</option> \n";
                }
                $return_value .= "</select>\n";
            }
        }
        return $return_value;
    }

    function getMailForm($uid, $resumeid, $jobapplyid) {
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (is_numeric($resumeid) == false)
            return false;
        $db = &$this->getDBO();
        $query = "SELECT resume.email_address
                    FROM `#__js_job_resume` AS resume
                    WHERE resume.id = " . $resumeid;

        $db->setQuery($query);
        $jobseeker_email = $db->loadResult();
        $return_value = "<div  id='resumeactioncandidate'>\n";
        $return_value.= "<table id='resumeactioncandidatetable' cellpadding='0' cellspacing='0' border='0' width='100%'>\n";
        $return_value .= "<tr >\n";
        $return_value .= "<td width='20%' valign='top' ><b>" . JText::_('JS_JOBSEEKER') . ":</b></td>\n";
        $return_value .= "<td width='50%' align='left'>\n";
        $return_value .= "<input name='jsmailaddress' id='jsmailaddress' value='$jobseeker_email' readonly='readonly'/>\n";
        $return_value .= "</td>\n";
        $return_value .= "</tr>\n";
        $return_value .= "<tr >\n";
        $return_value .= "<td width='20%' valign='top' ><b>" . JText::_('JS_SUBJECT_LINE') . ":</b></td>\n";
        $return_value .= "<td width='50%' align='left'>\n";
        $return_value .= "<input type='text' name='jssubject' id='jssubject'/>\n";
        $return_value .= "</td>\n";
        $return_value .= "<tr >\n";
        $return_value .= "<td width='20%' valign='top' ><b>" . JText::_('JS_EMAIL_SENDER') . ":</b></td>\n";
        $return_value .= "<td width='50%' align='left'>\n";
        $return_value .= "<input name='emmailaddress' id='emmailaddress' class='email validate'/>\n";
        $return_value .= "</td>\n";
        $return_value .= "</tr>\n";
        $return_value .= "</table>\n";
        $return_value .= "</div>\n";
        $return_value .= "<div id='resumeactioncandidatecomments'>\n";
        $return_value.= "<table id='resumeactioncandidatecommentstable' cellpadding='0' cellspacing='0' border='0' width='100%'>\n";
        $return_value .= "<tr >\n";
        $return_value .= "<td width='335' align='center'>\n";
        $return_value .= "<textarea name='candidatemessage' id='candidatemessage' rows='5' cols='38'></textarea>\n";
        $return_value .= "</td>\n";
        $return_value .= "<td align='left' ><input type='button' class='button' onclick='sendmailtocandidate(" . $jobapplyid . ")' value='" . JText::_('JS_SEND') . "'> </td>\n";
        $return_value .= "</tr>\n";
        $return_value .= "</table>\n";
        $return_value .= "</table>\n";

        $return_value .= "</div>\n";

        return $return_value;
    }

    function getCategories($title) {
        $db = & JFactory::getDBO();
        $query = "SELECT id,cat_title FROM `#__js_job_categories` WHERE isactive = 1";
        if ($this->_client_auth_key != "")
            $query.=" AND serverid!='' AND serverid!=0";
        $query.=" ORDER BY cat_title ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $jobcategories = array();
        if ($title)
            $jobcategories[] = array('value' => JText::_(''), 'text' => $title);

        foreach ($rows as $row) {
            $jobcategories[] = array('value' => $row->id,
                'text' => JText::_($row->cat_title));
        }
        return $jobcategories;
    }

    function getSubCategoriesforCombo($categoryid, $title, $value) {
        if (!is_numeric($categoryid))
            return false;
        $db = & JFactory::getDBO();
        $query = "SELECT id, title FROM `#__js_job_subcategories` WHERE status = 1 AND categoryid = " . $categoryid;
        if ($this->_client_auth_key != "")
            $query.=" AND serverid!='' AND serverid!=0";
        $query.=" ORDER BY title ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $subcategories = array();
        if ($title)
            $subcategories[] = array('value' => JText::_($value), 'text' => JText::_($title));
        foreach ($rows as $row) {
            $subcategories[] = array('value' => $row->id, 'text' => JText::_($row->title));
        }
        return $subcategories;
    }

    function &getCurU() {
        $result = array();
        $result[0] = 'a' . substr($this->_arv, 16, 2) . substr($this->_arv, 24, 1);
        $result[1] = substr($this->_arv, 5, 2) . substr($this->_arv, 12, 3) . substr($this->_arv, 20, 1) . 'e';
        $result[2] = $_SERVER['SERVER_NAME'];
        return $result;
    }

    function getJobSalaryRange($title, $jobdata) {
        $db = & JFactory::getDBO();
        if (!$this->_jobsalaryrange) {
            $query = "SELECT id, rangestart, rangeend FROM `#__js_job_salaryrange`";
            if ($this->_client_auth_key != "")
                $query.=" WHERE serverid!='' AND serverid!=0";
            $query.=" ORDER BY 'id' ";
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            if ($db->getErrorNum()) {
                echo $db->stderr();
                return false;
            }
            $this->_jobsalaryrange = $rows;
        }
        $jobsalaryrange = array();
        if ($title)
            $jobsalaryrange[] = array('value' => JText::_(''), 'text' => $title);

        foreach ($this->_jobsalaryrange as $row) {
            if ($jobdata == 1)
                $salrange = $row->rangestart; //.' - '.$currency . $row->rangeend;
            else
                $salrange = $row->rangestart . ' - ' . $row->rangeend;
            $jobsalaryrange[] = array('value' => $row->id, 'text' => $salrange);
        }
        return $jobsalaryrange;
    }

    function jobTypes($id, $val, $fild) {
        if (is_numeric($val) == false)
            return false;
        if (!$this->_config)
            $this->getConfig('');

        foreach ($this->_config as $conf) {
            if ($conf->configname == $fild)
                $value = $conf->configvalue;
        }
        $value = $this->getSubVal($value);
        if ($value != $id)
            return 3;
        $db = & JFactory::getDBO();
        $query = "UPDATE `#__js_job_jobtypes` SET status = " . $val;
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        return true;
    }

    function getSubVal($value) {
        $mdr = '';
        $mdrg9 = '';
        $mdrt11 = '';
        $val = '';
        for ($i = 0; $i < strlen(substr($value, 1, 3)); $i++)
            $mdr += ord($value[$i]);
        for ($i = 0; $i < strlen(substr($value, 7, 3)); $i++)
            $mdrg9 += ord($value[$i]);
        for ($i = 0; $i < strlen(substr($value, 11, 3)); $i++)
            $mdrt11 += ord($value[$i]);
        $val = substr($value, 0, 3) . $mdrg9 . substr($value, 3, 4) . $mdrt11 . substr($value, 6, 5) . $mdr . substr($value, 11, 3);
        return $val;
    }

    function jobShifts($id, $val, $fild) {
        if (is_numeric($val) == false)
            return false;
        if (!$this->_config)
            $this->getConfig('');
        foreach ($this->_config as $conf) {
            if ($conf->configname == $fild)
                $value = $conf->configvalue;
        }
        if ($value != $id)
            return 3;
        $db = & JFactory::getDBO();
        $query = "UPDATE `#__js_job_shifts` SET status = " . $val;
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        return true;
    }

    function getCountries($title) {
        if (!$this->_countries) {
            $db = & JFactory::getDBO();
            $query = "SELECT * FROM `#__js_job_countries` WHERE enabled = 1";
            if ($this->_client_auth_key != "")
                $query.=" AND serverid!='' AND serverid!=0";
            $query.=" ORDER BY name ASC ";
            //echo '<br>sql '.$query;
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            if ($db->getErrorNum()) {
                echo $db->stderr();
                return false;
            }
            $this->_countries = $rows;
        }
        $countries = array();
        if ($title)
            $countries[] = array('value' => JText::_(''), 'text' => $title);
        else
            $countries[] = array('value' => JText::_(''), 'text' => JText::_('JS_CHOOSE_COUNTRY'));

        foreach ($this->_countries as $row) {
            $countries[] = array('value' => $row->id, 'text' => JText::_($row->name));
        }
        return $countries;
    }

    function getStates($countryid, $title) {
        $db = & JFactory::getDBO();
        if (empty($countryid))
            $countryid = 0;
        $query = "SELECT * FROM `#__js_job_states` WHERE enabled = 'Y' AND countryid = " . $countryid;
        if ($this->_client_auth_key != "")
            $query.=" AND serverid!='' AND serverid!=0";
        $query.=" ORDER BY name ASC ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $states = array();
        if ($title)
            $states[] = array('value' => JText::_(''), 'text' => $title);
        else
            $states[] = array('value' => JText::_(''), 'text' => JText::_('JS_CHOOSE_STATE'));

        foreach ($rows as $row) {
            $states[] = array('value' => $row->id,
                'text' => JText::_($row->name));
        }
        return $states;
    }

    function getCities($stateid, $title) {
        $db = & JFactory::getDBO();
        if (empty($stateid))
            $stateid = 0;
        if (is_string($stateid))
            $stateid = $db->Quote($stateid);
        $query = "SELECT * FROM `#__js_job_cities` WHERE enabled = 'Y' AND stateid = " . $stateid;

        if ($this->_client_auth_key != "")
            $query.=" AND serverid!='' AND serverid!=0";
        $query.=" ORDER BY name ASC ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $cities = array();
        if ($title)
            $cities[] = array('value' => JText::_(''), 'text' => $title);
        else
            $cities[] = array('value' => JText::_(''), 'text' => JText::_('JS_CHOOSE_CITY'));

        foreach ($rows as $row) {
            $cities[] = array('value' => $row->id,
                'text' => JText::_($row->name));
        }
        return $cities;
    }

    function getCompanies($uid) {
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        $db = & JFactory::getDBO();
        $query = "SELECT id, name FROM `#__js_job_companies` WHERE uid = " . $uid . " AND status = 1";
        if ($this->_client_auth_key != "")
            $query.=" AND serverstatus='ok'";
        $query.=" ORDER BY name ASC ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $companies = array();
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $companies[] = array('value' => $row->id, 'text' => $row->name);
            }
        } else {
            $companies[] = array('value' => '', 'text' => '');
        }
        return $companies;
    }

    function getAllCompanies($title) {
        $db = & JFactory::getDBO();
        $query = "SELECT id, name FROM `#__js_job_companies` ORDER BY name ASC ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $companies = array();
        if ($title)
            $companies[] = array('value' => JText::_(''), 'text' => $title);
        foreach ($rows as $row) {
            $companies[] = array('value' => $row->id, 'text' => $row->name);
        }
        return $companies;
    }

    function getJobType($title) {
        $db = & JFactory::getDBO();
        $query = "SELECT id, title FROM `#__js_job_jobtypes` WHERE isactive = 1";
        if ($this->_client_auth_key != "")
            $query.=" AND serverid!='' AND serverid!=0";
        $query.=" ORDER BY id ASC ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $this->_jobtype = array();
        if ($title)
            $this->_jobtype[] = array('value' => JText::_(''), 'text' => $title);

        foreach ($rows as $row) {
            $this->_jobtype[] = array('value' => $row->id,
                'text' => JText::_($row->title));
        }

        return $this->_jobtype;
    }

    function getJobStatus($title) {
        $db = & JFactory::getDBO();
        $query = "SELECT id, title FROM `#__js_job_jobstatus` WHERE isactive = 1";
        if ($this->_client_auth_key != "")
            $query.=" AND serverid!='' AND serverid!=0";
        $query.=" ORDER BY id ASC ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $this->_jobstatus = array();
        if ($title)
            $this->_jobstatus[] = array('value' => JText::_(''), 'text' => $title);

        foreach ($rows as $row) {
            $this->_jobstatus[] = array('value' => $row->id, 'text' => JText::_($row->title));
        }
        return $this->_jobstatus;
    }

    function getHeighestEducation($title) {
        if (!$this->_heighesteducation) {
            $db = & JFactory::getDBO();
            $query = "SELECT id, title FROM `#__js_job_heighesteducation` WHERE isactive = 1";
            if ($this->_client_auth_key != "")
                $query.=" AND serverid!='' AND serverid!=0";
            $query.=" ORDER BY id ASC ";
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            if ($db->getErrorNum()) {
                echo $db->stderr();
                return false;
            }
            $this->_heighesteducation = $rows;
        }
        $heighesteducation = array();
        if ($title)
            $heighesteducation[] = array('value' => JText::_(''), 'text' => $title);

        foreach ($this->_heighesteducation as $row) {
            $heighesteducation[] = array('value' => $row->id, 'text' => JText::_($row->title));
        }
        return $heighesteducation;
    }

    function getShift($title) {
        if (!$this->_shifts) {
            $db = & JFactory::getDBO();
            $query = "SELECT id, title FROM `#__js_job_shifts` WHERE isactive = 1";
            if ($this->_client_auth_key != "")
                $query.=" AND serverid!='' AND serverid!=0";
            $query.=" ORDER BY id ASC ";
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            if ($db->getErrorNum()) {
                echo $db->stderr();
                return false;
            }
            $this->_shifts = array();
            if ($title)
                $this->_shifts[] = array('value' => JText::_(''), 'text' => $title);
            foreach ($rows as $row) {
                $this->_shifts[] = array('value' => $row->id, 'text' => JText::_($row->title));
            }
        }
        return $this->_shifts;
    }

    function getSalaryRangeTypes($title) {
        $db = & JFactory::getDBO();
        $query = "SELECT id, title FROM `#__js_job_salaryrangetypes` WHERE status = 1";
        if ($this->_client_auth_key != "")
            $query.=" AND serverid!='' AND serverid!=0";
        $query.=" ORDER BY id ASC ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $types = array();
        if ($title)
            $types[] = array('value' => JText::_(''), 'text' => $title);
        foreach ($rows as $row) {
            $types[] = array('value' => $row->id, 'text' => JText::_($row->title));
        }
        return $types;
    }

    function getCareerLevels($title) {
        if (!$this->_careerlevels) {
            $db = & JFactory::getDBO();
            $query = "SELECT id, title FROM `#__js_job_careerlevels` WHERE status = 1";
            if ($this->_client_auth_key != "")
                $query.=" AND serverid!='' AND serverid!=0";
            $query.=" ORDER BY id ASC ";
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            if ($db->getErrorNum()) {
                echo $db->stderr();
                return false;
            }
            $this->_careerlevels = array();
            if ($title)
                $this->_careerlevels[] = array('value' => JText::_(''), 'text' => $title);
            foreach ($rows as $row) {
                $this->_careerlevels[] = array('value' => $row->id, 'text' => JText::_($row->title));
            }
        }
        return $this->_careerlevels;
    }

    function getExperiences($title) {
        if (!$this->_experiences) {
            $db = & JFactory::getDBO();
            $query = "SELECT id, title FROM `#__js_job_experiences` WHERE status = 1";
            if ($this->_client_auth_key != "")
                $query.=" AND serverid!='' AND serverid!=0";
            $query.=" ORDER BY id ASC ";
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            if ($db->getErrorNum()) {
                echo $db->stderr();
                return false;
            }
            $this->_experiences = $rows;
        }

        $experiences = array();
        if ($title)
            $experiences[] = array('value' => JText::_(''), 'text' => $title);
        foreach ($this->_experiences as $row) {
            $experiences[] = array('value' => $row->id, 'text' => JText::_($row->title));
        }
        return $experiences;
    }

    function getCareerLevel($title) {
        if (!$this->_careerlevel) {
            $db = & JFactory::getDBO();
            $query = "SELECT id, title FROM `#__js_job_careerlevels` WHERE status = 1";
            if ($this->_client_auth_key != "")
                $query.=" AND serverid!='' AND serverid!=0";
            $query.=" ORDER BY id ASC ";

            $db->setQuery($query);
            $rows = $db->loadObjectList();
            if ($db->getErrorNum()) {
                echo $db->stderr();
                return false;
            }
            $this->_careerlevel = $rows;
        }

        $careerlevel = array();
        if ($title)
            $careerlevel[] = array('value' => JText::_(''), 'text' => $title);
        foreach ($this->_careerlevel as $row) {
            $careerlevel[] = array('value' => $row->id, 'text' => JText::_($row->title));
        }
        return $careerlevel;
    }

    function getSharingCountries($title) {
        if (!$this->_countries) {
            $db = & JFactory::getDBO();
            $query = "SELECT serverid as id,name FROM `#__js_job_countries` WHERE enabled = 1";
            if ($this->_client_auth_key != "")
                $query.=" AND serverid!='' AND serverid!=0";
            $query.=" ORDER BY name ASC ";
            //echo '<br>sql '.$query;
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            if ($db->getErrorNum()) {
                echo $db->stderr();
                return false;
            }
            $this->_countries = $rows;
        }
        $countries = array();
        if ($title)
            $countries[] = array('value' => JText::_(''), 'text' => $title);
        else
            $countries[] = array('value' => JText::_(''), 'text' => JText::_('JS_CHOOSE_COUNTRY'));

        foreach ($this->_countries as $row) {
            $countries[] = array('value' => $row->id, 'text' => JText::_($row->name));
        }
        return $countries;
    }

    function &listDepartments($val) {
        $db = &$this->getDBO();
        if (is_numeric($val) == false)
            return false;
        $query = "SELECT id, name FROM `#__js_job_departments`  WHERE status = 1 AND companyid = " . $val . "
				ORDER BY name ASC";
        $db->setQuery($query);
        $result = $db->loadObjectList();

        if (isset($result)) {
            $return_value = "<select name='departmentid' class='inputbox' >\n";

            foreach ($result as $row) {
                $return_value .= "<option value=\"$row->id\" >$row->name</option> \n";
            }
            $return_value .= "</select>\n";
        }

        return $return_value;
    }

    function getRequiredTravel($title) {
        $requiredtravel = array();
        if ($title)
            $requiredtravel[] = array('value' => '', 'text' => $title);
        $requiredtravel[] = array('value' => 1, 'text' => JText::_('JS_NOT_REQUIRED'));
        $requiredtravel[] = array('value' => 2, 'text' => JText::_('JS_25_PER'));
        $requiredtravel[] = array('value' => 3, 'text' => JText::_('JS_50_PER'));
        $requiredtravel[] = array('value' => 4, 'text' => JText::_('JS_75_PER'));
        $requiredtravel[] = array('value' => 5, 'text' => JText::_('JS_100_PER'));
        return $requiredtravel;
    }

    function getGender($title) {
        $gender = array();
        if ($title)
            $gender[] = array('value' => '', 'text' => $title);
        $gender[] = array('value' => 1, 'text' => JText::_('JS_MALE'));
        $gender[] = array('value' => 2, 'text' => JText::_('JS_FEMALE'));
        return $gender;
    }

    function getSendEmail() {
        $values = array();
        $values[] = array('value' => 0, 'text' => JText::_('JS_NO'));
        $values[] = array('value' => 1, 'text' => JText::_('JS_YES'));
        $values[] = array('value' => 2, 'text' => JText::_('JS_YES_WITH_RESUME'));
        return $values;
    }

    function getExtension($str) {
        $i = strrpos($str, ".");
        if (!$i) {
            return "";
        }
        $l = strlen($str) - $i;
        $ext = substr($str, $i + 1, $l);
        return $ext;
    }

    public function spamCheckRandom() {
        $pw = '';
        // first character has to be a letter
        $characters = range('a', 'z');
        $pw .= $characters[mt_rand(0, 25)];
        // other characters arbitrarily
        $numbers = range(0, 9);
        $characters = array_merge($characters, $numbers);
        $pw_length = mt_rand(4, 12);
        for ($i = 0; $i < $pw_length; $i++) {
            $pw .= $characters[mt_rand(0, 35)];
        }
        return $pw;
    }

    private function converttostring($x) {
        // Probability 2/3 for conversion
        $random = mt_rand(1, 3);
        if ($random != 1) {
            if ($x > 20) {
                return $x;
            } else {
                // Names of the numbers are read from language file
                $names = array(JText::_('JSAUTOZ_NULL'), JText::_('ONE'), JText::_('TWO'), JText::_('THREE'), JText::_('FOUR'),
                    JText::_('FIVE'), JText::_('SIX'), JText::_('SEVEN'), JText::_('EIGHT'), JText::_('NINE'),
                    JText::_('TEN'), JText::_('ELEVEN'), JText::_('TWELVE'), JText::_('THIRTEEN'),
                    JText::_('FOURTEEN'), JText::_('FIFTEEN'), JText::_('SIXTEEN'), JText::_('SEVENTEEN'),
                    JText::_('EIGHTEEN'), JText::_('NINETEEN'), JText::_('TWENTY'));
                return $names[$x];
            }
        } else {
            return $x;
        }
    }

    function performChecks() {
        $request = JRequest::get();
        $session = JFactory::getSession();
        $type_calc = true;
        if ($type_calc) {
            if ($session->get('jsjobs_rot13', null, 'jsjobs_checkspamcalc') == 1) {
                $spamcheckresult = base64_decode(str_rot13($session->get('jsjobs_spamcheckresult', null, 'jsjobs_checkspamcalc')));
            } else {
                $spamcheckresult = base64_decode($session->get('jsjobs_spamcheckresult', null, 'jsjobs_checkspamcalc'));
            }
            $spamcheck = JRequest::getInt($session->get('jsjobs_spamcheckid', null, 'jsjobs_checkspamcalc'), '', 'post');
            $session->clear('jsjobs_rot13', 'jsjobs_checkspamcalc');
            $session->clear('jsjobs_spamcheckid', 'jsjobs_checkspamcalc');
            $session->clear('jsjobs_spamcheckresult', 'jsjobs_checkspamcalc');

            if (!is_numeric($spamcheckresult) || $spamcheckresult != $spamcheck) {
                return false; // Failed
            }
        }
        // Hidden field
        $type_hidden = 0;
        if ($type_hidden) {
            $hidden_field = $session->get('hidden_field', null, 'checkspamcalc');
            $session->clear('hidden_field', 'checkspamcalc');

            if (JRequest::getVar($hidden_field, '', 'post')) {
                return false; // Hidden field was filled out - failed
            }
        }
        // Time lock
        $type_time = 0;
        if ($type_time) {
            $time = $session->get('time', null, 'checkspamcalc');
            $session->clear('time', 'checkspamcalc');

            if (time() - $this->params->get('type_time_sec') <= $time) {
                return false; // Submitted too fast - failed
            }
        }
        // Own Question
        // Conversion to lower case
        $session->clear('ip', 'jsjobs_checkspamcalc');
        $session->clear('saved_data', 'jsjobs_checkspamcalc');

        return true;
    }

    function getAddressData($value) {
        $array = explode(', ', $value);
        $count = count($array);
        $count--;
        if ($count != -1) {
            $country = $array[$count];
            $count--;
        }
        if ($count != -1) {
            $state = $array[$count];
            $count--;
        }
        if ($count != -1)
            $city = $array[$count];

        $db = $this->getDbo();
        $query = "SELECT id FROM `#__js_job_countries` WHERE name = " . $db->quote($country);
        $db->setQuery($query);
        $countryid = $db->loadResult();
        if (isset($state)) {
            $query = "SELECT id,stateid FROM `#__js_job_cities` WHERE countryid = " . $countryid . " AND name = " . $db->quote($state);

            $db->setQuery($query);
            $statedata = $db->loadObject();
        }
        if (!$statedata->stateid) {
            if (isset($city)) {
                $query = "SELECT id,stateid FROM `#__js_job_cities` WHERE countryid = " . $countryid . " AND name = " . $db->quote($city);
                $db->setQuery($query);
                $statedata = $db->loadObject();
            }
        }
        if (isset($statedata->stateid) && !empty($statedata->stateid)) {
            $query = "SELECT id AS code,name FROM `#__js_job_states` WHERE countryid = " . $countryid;
            $db->setQuery($query);
            $states = $db->loadObjectList();

            $liststates = "<select name=state id=state class=inputbox onchange=\"dochange('city', this.value);\" >\n";
            foreach ($states AS $st) {
                if ($statedata->stateid == $st->code)
                    $liststates .= "<option value=" . $st->code . " selected=selected>" . $st->name . "</option>";
                else
                    $liststates .="<option value=" . $st->code . ">" . $st->name . "</option>";
            }
            $liststates .= "</select>";

            if (isset($city)) {
                if (isset($statedata->stateid)) {
                    $query = "SELECT id AS code,name FROM `#__js_job_cities` WHERE countryid = " . $countryid . " AND stateid = " . $statedata->stateid;
                    $db->setQuery($query);
                    $cities = $db->loadObjectList();

                    $listcity = "<select name=city id=city class=inputbox onchange= >\n";
                    $listcity .= "<option value=''>" . JText::_('JS_SELECT_CITY') . "</option>";
                    foreach ($cities AS $st) {
                        if ($statedata->id == $st->code)
                            $listcity .= "<option value=" . $st->code . " selected=selected>" . $st->name . "</option>";
                        else
                            $listcity .="<option value=" . $st->code . ">" . $st->name . "</option>";
                    }
                    $listcity .= "</select>";
                }
                else
                    $listcity = "<input name=city id=city onBlur= />";
            }elseif (isset($state)) {
                $query = "SELECT id AS code,name FROM `#__js_job_cities` WHERE countryid = " . $countryid . " AND name = " . $db->quote($state);
                $db->setQuery($query);
                $cities = $db->loadObjectList();

                $listcity = "<select name=city id=city class=inputbox onchange= >\n";
                $listcity .= "<option value=''>" . JText::_('JS_SELECT_CITY') . "</option>";
                foreach ($cities AS $st) {
                    if ($state == $st->name)
                        $listcity .= "<option value=" . $st->code . " selected=selected>" . $st->name . "</option>";
                    else
                        $listcity .="<option value=" . $st->code . ">" . $st->name . "</option>";
                }
                $listcity .= "</select>";
            }
            else
                $listcity = "<input name=city id=city onBlur= />";
        }else {

            $liststates = "<input name=state id=state value='' onBlur= />";
            if (isset($state))
                $listcity = "<input name=city id=city value=" . $state . " onBlur= />";
            else
                $listcity = "<input name=city id=city onBlur= />";
        }
        $return['countrycode'] = $countryid;
        $return['states'] = $liststates;
        $return['city'] = $listcity;
        return $return;
    }

    function checkCronKey($passkey) {
        $db = $this->getDbo();
        $query = "SELECT COUNT(configvalue) FROM `#__js_job_config` WHERE configname = " . $db->quote('cron_job_alert_key') . " AND configvalue = " . $db->quote($passkey);
        //echo '<br>'.$query;
        $db->setQuery($query);
        $key = $db->loadResult();
        if ($key == 1)
            return true;
        else
            return false;
    }

    function makeDir($path) {
        if (!file_exists($path)) { // create directory
            mkdir($path, 0755);
            $ourFileName = $path . '/index.html';
            $ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
            fclose($ourFileHandle);
        }
    }

    function checkUserDetail($val, $for) {
        $db = $this->getDBO();
        $query = "SELECT COUNT(id) FROM `#__users` WHERE $for =" . $db->quote($val);
        $db->setQuery($query);
        $total = $db->loadResult();
        return $total;
    }

    function parseId($value) {
        $id = explode("-", $value);
        $count = count($id);
        $id = (int) $id[($count - 1)];
        return $id;
    }

    function storePackageHistory($autoassign, $data) {
        $row = &$this->getTable('paymenthistory');

        if ($autoassign == 0)
            $data = JRequest :: get('post'); // get data from form

        if (is_numeric($data['packageid']) == false)
            return false;
        if (is_numeric($data['uid']) == false)
            return false;
        $db = &$this->getDBO();
        $result = array();

        if ($data['packagefor'] == 1)
            $query = "SELECT package.* FROM `#__js_job_employerpackages` AS package WHERE id = " . $data['packageid'];
        elseif ($data['packagefor'] == 2)
            $query = "SELECT package.* FROM `#__js_job_jobseekerpackages` AS package WHERE id = " . $data['packageid'];
        $db->setQuery($query);
        $package = $db->loadObject();
        if (isset($package)) {
            $packageconfig = $this->getConfigByFor('package');
            $row->uid = $data['uid'];
            $row->packageid = $data['packageid'];

            $row->packagetitle = $package->title;
            $row->packageprice = $package->price;
            $paidamount = $package->price;
            $discountamount = 0;
            $currency = "SELECT currency.id FROM `#__js_job_currencies` AS currency WHERE `default` = 1 AND status=1 ";
            $db->setQuery($currency);
            $c_id = $db->loadResult();
            $row->currencyid = $c_id;
            $row->packagefor = $data['packagefor'];
            if ($package->price != 0) {
                $curdate = date('Y-m-d H:i:s');
                if (($package->discountstartdate <= $curdate) && ($package->discountenddate >= $curdate)) {
                    if ($package->discounttype == 2) { //%
                        $discountamount = ($package->price * $package->discount) / 100;
                        $paidamount = $package->price - $discountamount;
                    } else { // amount
                        $discountamount = $package->discount;
                        $paidamount = $package->price - $package->discount;
                    }
                }
                $row->transactionverified = 0;
                $row->transactionautoverified = 0;
                $row->status = 1;
            } else { //free
                $packagefor = 0;
                if ($data['packagefor'] == 1) {
                    $query = "SELECT COUNT(package.id) FROM `#__js_job_employerpackages` AS package";
                    if ($packageconfig['onlyonce_employer_getfreepackage'] == 1)
                        $packagefor = 1;
                    $row->status = $packageconfig['employer_freepackage_autoapprove'];
                }elseif ($data['packagefor'] == 2) {
                    $query = "SELECT COUNT(package.id) FROM `#__js_job_jobseekerpackages` AS package";
                    if ($packageconfig['onlyonce_jobseeker_getfreepackage'] == 1)
                        $packagefor = 1;
                    $row->status = $packageconfig['jobseeker_freepackage_autoapprove'];
                }
                if ($packagefor == 1) { // can't get free package more then once
                    $query .=" JOIN `#__js_job_paymenthistory` AS payment ON payment.packageid = package.id
                                    WHERE package.price = 0 AND payment.uid = " . $data['uid'] . " AND payment.packagefor=" . $data['packagefor'];
                    $db->setQuery($query);
                    $freepackage = $db->loadResult();
                    if ($freepackage > 0)
                        return 'cantgetpackagemorethenone'; // can't get free package more then once
                }
                $row->transactionverified = 1;
                $row->transactionautoverified = 1;
            }
            $row->discountamount = $discountamount;
            $row->paidamount = $paidamount;
            $row->discountmessage = $package->discountmessage;
            $row->packagediscountstartdate = $package->discountstartdate;
            $row->packagediscountenddate = $package->discountenddate;
            $row->packageexpireindays = $package->packageexpireindays;
            $row->packageshortdetails = $package->shortdetails;
            $row->packagedescription = $package->description;
            $row->created = date('Y-m-d H:i:s');
        }else {
            return false;
        }

        if (!$row->check()) {
            echo $this->setError($this->_db->getErrorMsg());
            return false;
        }
        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());
            echo $this->_db->getErrorMsg();
            return false;
        }
        if ($data['packagefor'] == 1)
            $this->sendMailtoAdmin($row->id, $data['uid'], 6);
        elseif ($data['packagefor'] == 2)
            $this->sendMailtoAdmin($row->id, $data['uid'], 7);

        $orderid = $row->id;
        return $orderid;
    }

    function getAges($title) {
        if (!$this->_ages) {// make problem with age from, age to
            $db = & JFactory::getDBO();
            $query = "SELECT id, title FROM `#__js_job_ages` WHERE status = 1";
            if ($this->_client_auth_key != "")
                $query.=" AND serverid!='' AND serverid!=0";
            $query.=" ORDER BY id ASC ";
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            if ($db->getErrorNum()) {
                echo $db->stderr();
                return false;
            }
            $this->_ages = $rows;
        }
        $ages = array();
        if ($title)
            $ages[] = array('value' => JText::_(''), 'text' => $title);
        foreach ($this->_ages as $row) {
            $ages[] = array('value' => $row->id, 'text' => JText::_($row->title));
        }
        return $ages;
    }

    function uploadFile($id, $action, $isdeletefile) {
        if (is_numeric($id) == false)
            return false;
        $db = & JFactory::getDBO();
        if (!isset($this->_config))
            $this->getConfig('');
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'data_directory')
                $datadirectory = $conf->configvalue;
        }
        $path = JPATH_BASE . '/' . $datadirectory;
        if (!file_exists($path)) { // create user directory
            $this->makeDir($path);
        }
        $isupload = false;
        $path = $path . '/data';
        if (!file_exists($path)) { // create user directory
            $this->makeDir($path);
        }
        $path = $path . '/employer';
        if (!file_exists($path)) { // create user directory
            $this->makeDir($path);
        }
        if ($action == 1) { //Company logo
            if ($_FILES['logo']['size'] > 0) {
                $file_name = $_FILES['logo']['name']; // file name
                $file_tmp = $_FILES['logo']['tmp_name']; // actual location
            } elseif ($_FILES['companylogo']['size'] > 0) { //for visitor 
                $file_name = $_FILES['companylogo']['name']; // file name
                $file_tmp = $_FILES['companylogo']['tmp_name']; // actual location
            }
            if ($file_name != '' AND $file_tmp != "") {
                $ext = $this->getExtension($file_name);
                $ext = strtolower($ext);
                if (($ext != "gif") && ($ext != "jpg") && ($ext != "jpeg") && ($ext != "png"))
                    return 6; //file type mistmathc

                $userpath = $path . '/comp_' . $id;
                if (!file_exists($userpath)) { // create user directory
                    $this->makeDir($userpath);
                }
                $userpath = $userpath . '/logo';
                if (!file_exists($userpath)) { // create logo directory
                    $this->makeDir($userpath);
                }
                $isupload = true;
            }
        } elseif ($action == 2) { //Company small logo
            if ($_FILES['smalllogo']['size'] > 0) {
                $file_name = $_FILES['smalllogo']['name']; // file name
                $file_tmp = $_FILES['smalllogo']['tmp_name']; // actual location

                $ext = $this->getExtension($file_name);
                $ext = strtolower($ext);
                if (($ext != "gif") && ($ext != "jpg") && ($ext != "jpeg") && ($ext != "png"))
                    return 6; //file type mistmathc

                $userpath = $path . '/comp_' . $id;
                if (!file_exists($userpath)) { // create user directory
                    $this->makeDir($userpath);
                }
                $userpath = $userpath . '/smalllogo';
                if (!file_exists($userpath)) { // create logo directory
                    $this->makeDir($userpath);
                }
                $isupload = true;
            }
        } elseif ($action == 3) { //About Company
            if ($_FILES['aboutcompany']['size'] > 0) {
                $file_name = $_FILES['aboutcompany']['name']; // file name
                $file_tmp = $_FILES['aboutcompany']['tmp_name']; // actual location

                $ext = $this->getExtension($file_name);
                $ext = strtolower($ext);
                if (($ext != "txt") && ($ext != "doc") && ($ext != "docx") && ($ext != "pdf") && ($ext != "opt") && ($ext != "rtf"))
                    return 6; //file type mistmathc

                $userpath = $path . '/comp_' . $id;
                if (!file_exists($userpath)) { // create user directory
                    $this->makeDir($userpath);
                }
                $userpath = $userpath . '/aboutcompany';
                if (!file_exists($userpath)) { // create logo directory
                    $this->makeDir($userpath);
                }
                $isupload = true;
            }
        }

        if ($isupload) {
            $files = glob($userpath . '/*.*');
            array_map('unlink', $files);  //delete all file in directory

            move_uploaded_file($file_tmp, $userpath . '/' . $file_name);

            return $userpath . '/' . $file_name;
            return 1;
        } else { // DELETE FILES
            if ($action == 1) { // company logo
                if ($isdeletefile == 1) {
                    $userpath = $path . '/comp_' . $id . '/logo';
                    $files = glob($userpath . '/*.*');
                    array_map('unlink', $files); // delete all file in the direcoty 
                }
            } elseif ($action == 2) { // company small logo
                if ($isdeletefile == 1) {
                    $userpath = $path . '/comp_' . $id . '/smalllogo';
                    $files = glob($userpath . '/*.*');
                    array_map('unlink', $files); // delete all file in the direcoty 
                }
            } elseif ($action == 3) { // about company 
                if ($isdeletefile == 1) {
                    $userpath = $path . '/comp_' . $id . '/aboutcompany';
                    $files = glob($userpath . '/*.*');
                    array_map('unlink', $files); // delete all file in the direcoty 
                }
            }
            return 1;
        }
    }

}

?>
