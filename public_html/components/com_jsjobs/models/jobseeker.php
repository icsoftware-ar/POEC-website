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

class JSJobsModelJobseeker extends JModelLegacy {

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
        $componentPath = 'components/com_jsjobs';
        require_once $componentPath . '/models/common.php';
        $common_model = new JSJobsModelCommon();
        return $common_model;
    }

    function &getEmpOptions() {
        if (!$this->_empoptions) {
            $this->_empoptions = array();
            $gender = array(
                '0' => array('value' => 1, 'text' => JText::_('JS_MALE')),
                '1' => array('value' => 2, 'text' => JText::_('JS_FEMALE')),);

            $job_type = $this->common_model->getJobType('');
            $heighesteducation = $this->common_model->getHeighestEducation('');
            $job_categories = $this->common_model->getCategories('');
            $job_salaryrange = $this->common_model->getJobSalaryRange('', '');
            $countries = $this->common_model->getCountries('');
            if (isset($this->_application)) {
                $job_subcategories = $this->common_model->getSubCategoriesforCombo($this->_application->job_category, '', $this->_application->job_subcategory);
            } else {
                $job_subcategories = $this->common_model->getSubCategoriesforCombo($job_categories[0]['value'], '', '');
            }
            if (isset($this->_application)) {
                $this->_empoptions['nationality'] = JHTML::_('select.genericList', $countries, 'nationality', 'class="inputbox" ' . '', 'value', 'text', $this->_application->nationality);
                $this->_empoptions['gender'] = JHTML::_('select.genericList', $gender, 'gender', 'class="inputbox" ' . '', 'value', 'text', $this->_application->gender);

                $this->_empoptions['job_category'] = JHTML::_('select.genericList', $job_categories, 'job_category', 'class="inputbox" ' . 'onChange="fj_getsubcategories(\'fj_subcategory\', this.value)"', 'value', 'text', $this->_application->job_category);
                $this->_empoptions['job_subcategory'] = JHTML::_('select.genericList', $job_subcategories, 'job_subcategory', 'class="inputbox" ' . '', 'value', 'text', $this->_application->job_subcategory);

                $this->_empoptions['jobtype'] = JHTML::_('select.genericList', $job_type, 'jobtype', 'class="inputbox" ' . '', 'value', 'text', $this->_application->jobtype);
                $this->_empoptions['heighestfinisheducation'] = JHTML::_('select.genericList', $heighesteducation, 'heighestfinisheducation', 'class="inputbox" ' . '', 'value', 'text', $this->_application->heighestfinisheducation);
                $this->_empoptions['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'jobsalaryrange', 'class="inputbox" ' . '', 'value', 'text', $this->_application->jobsalaryrange);
                $this->_empoptions['desired_salary'] = JHTML::_('select.genericList', $job_salaryrange, 'desired_salary', 'class="inputbox" ' . '', 'value', 'text', $this->_application->desired_salary);
                $this->_empoptions['currencyid'] = JHTML::_('select.genericList', $this->common_model->getCurrency(), 'currencyid', 'class="inputbox" ' . '', 'value', 'text', $this->_application->currencyid);
                $this->_empoptions['dcurrencyid'] = JHTML::_('select.genericList', $this->common_model->getCurrency(), 'dcurrencyid', 'class="inputbox" ' . '', 'value', 'text', $this->_application->dcurrencyid);

                $address_city = ($this->_application->address_city == "" OR $this->_application->address_city == 0 ) ? -1 : $this->_application->address_city;
                $this->_empoptions['address_city'] = $this->common_model->getAddressDataByCityName('', $address_city);
                $address1_city = ($this->_application->address1_city == "" OR $this->_application->address1_city == 0 ) ? -1 : $this->_application->address1_city;
                $this->_empoptions['address1_city'] = $this->common_model->getAddressDataByCityName('', $address1_city);
                $address2_city = ($this->_application->address2_city == "" OR $this->_application->address2_city == 0 ) ? -1 : $this->_application->address2_city;
                $this->_empoptions['address2_city'] = $this->common_model->getAddressDataByCityName('', $address2_city);
                $institute_city = ($this->_application->institute_city == "" OR $this->_application->institute_city == 0 ) ? -1 : $this->_application->institute_city;
                $this->_empoptions['institute_city'] = $this->common_model->getAddressDataByCityName('', $institute_city);
                $institute1_city = ($this->_application->institute1_city == "" OR $this->_application->institute1_city == 0 ) ? -1 : $this->_application->institute1_city;
                $this->_empoptions['institute1_city'] = $this->common_model->getAddressDataByCityName('', $institute1_city);
                $institute2_city = ($this->_application->institute2_city == "" OR $this->_application->institute2_city == 0 ) ? -1 : $this->_application->institute2_city;
                $this->_empoptions['institute2_city'] = $this->common_model->getAddressDataByCityName('', $institute2_city);
                $institute3_city = ($this->_application->institute3_city == "" OR $this->_application->institute3_city == 0 ) ? -1 : $this->_application->institute3_city;
                $this->_empoptions['institute3_city'] = $this->common_model->getAddressDataByCityName('', $institute3_city);
                $employer_city = ($this->_application->employer_city == "" OR $this->_application->employer_city == 0 ) ? -1 : $this->_application->employer_city;
                $this->_empoptions['employer_city'] = $this->common_model->getAddressDataByCityName('', $employer_city);
                $employer1_city = ($this->_application->employer1_city == "" OR $this->_application->employer1_city == 0 ) ? -1 : $this->_application->employer1_city;
                $this->_empoptions['employer1_city'] = $this->common_model->getAddressDataByCityName('', $employer1_city);
                $employer2_city = ($this->_application->employer2_city == "" OR $this->_application->employer2_city == 0 ) ? -1 : $this->_application->employer2_city;
                $this->_empoptions['employer2_city'] = $this->common_model->getAddressDataByCityName('', $employer2_city);
                $employer3_city = ($this->_application->employer3_city == "" OR $this->_application->employer3_city == 0 ) ? -1 : $this->_application->employer3_city;
                $this->_empoptions['employer3_city'] = $this->common_model->getAddressDataByCityName('', $employer3_city);
                $reference_city = ($this->_application->reference_city == "" OR $this->_application->reference_city == 0 ) ? -1 : $this->_application->reference_city;
                $this->_empoptions['reference_city'] = $this->common_model->getAddressDataByCityName('', $reference_city);
                $reference1_city = ($this->_application->reference1_city == "" OR $this->_application->reference1_city == 0 ) ? -1 : $this->_application->reference1_city;
                $this->_empoptions['reference1_city'] = $this->common_model->getAddressDataByCityName('', $reference1_city);
                $reference2_city = ($this->_application->reference2_city == "" OR $this->_application->reference2_city == 0 ) ? -1 : $this->_application->reference2_city;
                $this->_empoptions['reference2_city'] = $this->common_model->getAddressDataByCityName('', $reference2_city);
                $reference3_city = ($this->_application->reference3_city == "" OR $this->_application->reference3_city == 0 ) ? -1 : $this->_application->reference3_city;
                $this->_empoptions['reference3_city'] = $this->common_model->getAddressDataByCityName('', $reference3_city);
            } else {
                $this->_empoptions['nationality'] = JHTML::_('select.genericList', $countries, 'nationality', 'class="inputbox" ' . '', 'value', 'text', '');
                $this->_empoptions['gender'] = JHTML::_('select.genericList', $gender, 'gender', 'class="inputbox" ' . '', 'value', 'text', '');

                $this->_empoptions['job_category'] = JHTML::_('select.genericList', $job_categories, 'job_category', 'class="inputbox" ' . 'onChange="fj_getsubcategories(\'fj_subcategory\', this.value)"', 'value', 'text', '');
                $this->_empoptions['job_subcategory'] = JHTML::_('select.genericList', $job_subcategories, 'job_subcategory', 'class="inputbox" ' . '', 'value', 'text', '');

                $this->_empoptions['address_country'] = JHTML::_('select.genericList', $countries, 'address_country', 'class="inputbox" ' . 'onChange="dochange(\'address_state\', \'address_city\',\'address_city12\', \'state\', this.value)"', 'value', 'text', '');
                if (isset($address_states[1]))
                    if ($address_states[1] != '')
                        $this->_empoptions['address_state'] = JHTML::_('select.genericList', $address_states, 'address_state', 'class="inputbox" ' . 'onChange="dochange(\'address_city\, , , this.value)"', 'value', 'text', '');
                if (isset($address_cities[1]))
                    if ($address_cities[1] != '')
                        $this->_empoptions['address_city'] = JHTML::_('select.genericList', $address_cities, 'address_city', 'class="inputbox" ' . '', 'value', 'text', '');

                $this->_empoptions['address1_country'] = JHTML::_('select.genericList', $countries, 'address1_country', 'class="inputbox" ' . 'onChange="dochange(\'address1_state\', \'address1_city\',\'address1_city12\',\'state\', this.value)"', 'value', 'text', '');
                $this->_empoptions['address2_country'] = JHTML::_('select.genericList', $countries, 'address2_country', 'class="inputbox" ' . 'onChange="dochange(\'address2_state\', \'address2_city\',\'address2_city12\',\'state\', this.value)"', 'value', 'text', '');

                $this->_empoptions['institute_country'] = JHTML::_('select.genericList', $countries, 'institute_country', 'class="inputbox" ' . 'onChange="dochange(\'institute_state\', \'institute_city\',\'institute_city12\', \'state\', this.value)"', 'value', 'text', '');
                if (isset($institute_states[1]))
                    if ($institute_states[1] != '')
                        $this->_empoptions['institute_state'] = JHTML::_('select.genericList', $institute_states, 'institute_state', 'class="inputbox" ' . 'onChange="dochange(\'institute_city\, , , this.value)"', 'value', 'text', '');
                if (isset($institute_cities[1]))
                    if ($institute_cities[1] != '')
                        $this->_empoptions['institute_city'] = JHTML::_('select.genericList', $institute_cities, 'institute_city', 'class="inputbox" ' . '', 'value', 'text', '');
                $this->_empoptions['institute1_country'] = JHTML::_('select.genericList', $countries, 'institute1_country', 'class="inputbox" ' . 'onChange="dochange(\'institute1_state\', \'institute1_city\',\'institute1_city12\',\'state\', this.value)"', 'value', 'text', '');
                if (isset($institute1_states[1]))
                    if ($institute1_states[1] != '')
                        $this->_empoptions['institute1_state'] = JHTML::_('select.genericList', $institute1_states, 'institute1_state', 'class="inputbox" ' . 'onChange="dochange(\'institute1_city\, , , this.value)"', 'value', 'text', '');
                if (isset($institute1_cities[1]))
                    if ($institute1_cities[1] != '')
                        $this->_empoptions['institute1_city'] = JHTML::_('select.genericList', $institute1_cities, 'institute1_city', 'class="inputbox" ' . '', 'value', 'text', '');

                $this->_empoptions['institute2_country'] = JHTML::_('select.genericList', $countries, 'institute2_country', 'class="inputbox" ' . 'onChange="dochange(\'institute2_state\', \'institute2_city\',\'institute2_city12\',\'state\', this.value)"', 'value', 'text', '');
                $this->_empoptions['institute3_country'] = JHTML::_('select.genericList', $countries, 'institute3_country', 'class="inputbox" ' . 'onChange="dochange(\'institute3_state\', \'institute3_city\',\'institute3_city12\',\'state\', this.value)"', 'value', 'text', '');

                $this->_empoptions['employer_country'] = JHTML::_('select.genericList', $countries, 'employer_country', 'class="inputbox" ' . 'onChange="dochange(\'employer_state\', \'employer_city\',\'employer_city12\',\'state\', this.value)"', 'value', 'text', '');
                if (isset($employer_states[1]))
                    if ($employer_states[1] != '')
                        $this->_empoptions['employer_state'] = JHTML::_('select.genericList', $employer_states, 'employer_state', 'class="inputbox" ' . 'onChange="dochange(\'employer_city\, , , this.value)"', 'value', 'text', '');
                if (isset($employer_cities[1]))
                    if ($employer_cities[1] != '')
                        $this->_empoptions['employer_city'] = JHTML::_('select.genericList', $employer_cities, 'employer_city', 'class="inputbox" ' . '', 'value', 'text', '');

                $this->_empoptions['employer1_country'] = JHTML::_('select.genericList', $countries, 'employer1_country', 'class="inputbox" ' . 'onChange="dochange(\'employer1_state\', \'employer1_city\',\'employer1_city12\',\'state\', this.value)"', 'value', 'text', '');
                $this->_empoptions['employer2_country'] = JHTML::_('select.genericList', $countries, 'employer2_country', 'class="inputbox" ' . 'onChange="dochange(\'employer2_state\', \'employer2_city\',\'employer2_city12\',\'state\', this.value)"', 'value', 'text', '');
                $this->_empoptions['employer3_country'] = JHTML::_('select.genericList', $countries, 'employer3_country', 'class="inputbox" ' . 'onChange="dochange(\'employer3_state\', \'employer3_city\',\'employer3_city12\',\'state\', this.value)"', 'value', 'text', '');

                $this->_empoptions['reference_country'] = JHTML::_('select.genericList', $countries, 'reference_country', 'class="inputbox" ' . 'onChange="dochange(\'reference_state\', \'reference_city\',\'reference_city12\',\'state\', this.value)"', 'value', 'text', '');
                $this->_empoptions['reference1_country'] = JHTML::_('select.genericList', $countries, 'reference1_country', 'class="inputbox" ' . 'onChange="dochange(\'reference1_state\', \'reference1_city\',\'reference1_city12\',\'state\', this.value)"', 'value', 'text', '');
                $this->_empoptions['reference2_country'] = JHTML::_('select.genericList', $countries, 'reference2_country', 'class="inputbox" ' . 'onChange="dochange(\'reference2_state\', \'reference2_city\',\'reference2_city12\',\'state\', this.value)"', 'value', 'text', '');
                $this->_empoptions['reference3_country'] = JHTML::_('select.genericList', $countries, 'reference3_country', 'class="inputbox" ' . 'onChange="dochange(\'reference3_state\', \'reference3_city\',\'reference3_city12\',\'state\', this.value)"', 'value', 'text', '');


                $this->_empoptions['jobtype'] = JHTML::_('select.genericList', $job_type, 'jobtype', 'class="inputbox" ' . '', 'value', 'text', '');
                $this->_empoptions['heighestfinisheducation'] = JHTML::_('select.genericList', $heighesteducation, 'heighestfinisheducation', 'class="inputbox" ' . '', 'value', 'text', '');
                $this->_empoptions['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'jobsalaryrange', 'class="inputbox" ' . '', 'value', 'text', '');
                $this->_empoptions['desired_salary'] = JHTML::_('select.genericList', $job_salaryrange, 'desired_salary', 'class="inputbox" ' . '', 'value', 'text');
                $this->_empoptions['currencyid'] = JHTML::_('select.genericList', $this->common_model->getCurrency(), 'currencyid', 'class="inputbox" ' . '', 'value', 'text', '');
                $this->_empoptions['dcurrencyid'] = JHTML::_('select.genericList', $this->common_model->getCurrency(), 'dcurrencyid', 'class="inputbox" ' . '', 'value', 'text');
            }
        }
        return $this->_empoptions;
    }

    function &getRssJobs($uid) {
        $config = $this->common_model->getConfigByFor('default');
        if ($config['job_rss'] == 1) {
            $db = &$this->getDBO();
            $curdate = date('Y-m-d H:i:s');
            $listjobconfig = $this->common_model->getConfigByFor('listjob');

            $query = "SELECT job.title,job.noofjobs,job.id, cat.cat_title,company.logofilename AS logofilename,company.id AS companyid,jobcurrency.symbol AS currency,
                                company.name AS comp_title,jobtype.title AS jobtype,jobstatus.title AS jobstatus,jobsalaryfrom.rangestart AS jobsalaryfrom,jobsalaryto.rangestart AS jobsalaryto,
                                CONCAT(job.alias,'-',job.id) AS aliasid
                                FROM `#__js_job_jobs` AS job
				JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
				JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
				JOIN `#__js_job_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id
				JOIN `#__js_job_currencies` AS jobcurrency ON job.currencyid = jobcurrency.id
				LEFT JOIN `#__js_job_salaryrange` AS jobsalaryfrom ON job.salaryrangefrom = jobsalaryfrom.id
				LEFT JOIN `#__js_job_salaryrange` AS jobsalaryto ON job.salaryrangeto = jobsalaryto.id
				LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id
				WHERE job.status = 1 
				AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate);

            $query .= " ORDER BY  job.startpublishing DESC";
            $db->setQuery($query);
            $job = $db->loadObjectList();
            $result[0] = $job;
            $result[1] = $listjobconfig;
            return $result;
        }
        return false;
    }

    function &getJobsbyCategory($uid, $cat_id, $city_filter, $cmbfiltercountry, $filterjobcategory, $filterjobsubcategory, $filterjobtype, $txtfilterlongitude, $txtfilterlatitude, $txtfilterradius, $cmbfilterradiustype, $sortby, $limit, $limitstart) {
        $db = &$this->getDBO();
        $result = array();
        if (is_numeric($cat_id) == false)
            return false;
        if ($filterjobtype != '')
            if (is_numeric($filterjobtype) == false)
                return false;
        $curdate = date('Y-m-d H:i:s');
        if (!isset($this->_config)) {
            $this->_config = $this->common_model->getConfig('');
        }
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'filter_address_fields_width')
                $address_fields_width = $conf->configvalue;
            if ($conf->configname == 'defaultcountry')
                $defaultcountry = $conf->configvalue;
            if ($conf->configname == 'hidecountry')
                $hidecountry = $conf->configvalue;
            if ($conf->configname == 'noofgoldjobsinlisting')
                $noofgoldjobs = $conf->configvalue;
            if ($conf->configname == 'nooffeaturedjobsinlisting')
                $nooffeaturedjobs = $conf->configvalue;
            if ($conf->configname == 'showgoldjobsinlistjobs')
                $showgoldjobs = $conf->configvalue;
            if ($conf->configname == 'showfeaturedjobsinlistjobs')
                $showfeaturedjobs = $conf->configvalue;
        }

        $listjobconfig = $this->common_model->getConfigByFor('listjob');
        //for radius search
        switch ($cmbfilterradiustype) {
            case "m":$radiuslength = 6378137;
                break;
            case "km":$radiuslength = 6378.137;
                break;
            case "mile":$radiuslength = 3963.191;
                break;
            case "nacmiles":$radiuslength = 3441.596;
                break;
        }
        if ($this->_client_auth_key != "") {
            $selectdistance = " ";
            if ($txtfilterlongitude != '' && $txtfilterlatitude != '' && $txtfilterradius != '') {
                $radiussearch = " acos((SIN( PI()* $txtfilterlatitude /180 )*SIN( PI()*t.latitude/180 ))+(cos(PI()* $txtfilterlatitude /180)*COS( PI()*t.latitude/180) *COS(PI()*t.longitude/180-PI()* $txtfilterlongitude /180)))* $radiuslength <= $txtfilterradius";
            }

            $wherequery = '';
            $server_address = array();

            if ($city_filter != '') {
                $server_citiy_id = $this->common_model->getServerid('cities', $city_filter);
                $server_address['multicityid'] = $server_citiy_id;
                $server_country_id = $this->common_model->getSeverCountryid($city_filter);
                if ($server_country_id == false)
                    $cmbfiltercountry = '';
                else
                    $cmbfiltercountry = $server_country_id;
            }else {
                $default_sharing_loc = $this->getDefaultSharingLocation($server_address, $cmbfiltercountry);
                if (isset($default_sharing_loc['defaultsharingcity']) AND ($default_sharing_loc['defaultsharingcity'] != '')) {
                    $city_filter = $default_sharing_loc['defaultsharingcity'];
                    $server_address['multicityid'] = $default_sharing_loc['defaultsharingcity'];
                } elseif (isset($default_sharing_loc['defaultsharingstate']) AND ($default_sharing_loc['defaultsharingstate'] != '')) {
                    $server_address['defaultsharingstate'] = $default_sharing_loc['defaultsharingstate'];
                } elseif (isset($default_sharing_loc['filtersharingcountry']) AND ($default_sharing_loc['filtersharingcountry'] != '')) {
                    $server_address['filtersharingcountry'] = $default_sharing_loc['filtersharingcountry'];
                    $cmbfiltercountry = $default_sharing_loc['filtersharingcountry'];
                } elseif (isset($default_sharing_loc['defaultsharingcountry']) AND ($default_sharing_loc['defaultsharingcountry'] != '')) {
                    $server_address['defaultsharingcountry'] = $default_sharing_loc['defaultsharingcountry'];
                    $cmbfiltercountry = $default_sharing_loc['defaultsharingcountry'];
                }
            }
            if ($filterjobtype != '') {
                $serverjobtype = $this->common_model->getServerid('jobtypes', $filterjobtype);
                $wherequery .= " AND t.jobtype = " . $serverjobtype;
            }
            if ($filterjobcategory != '') {
                $serverjobcategory = $this->common_model->getServerid('categories', $filterjobcategory);
                $wherequery .= " AND t.jobcategory = " . $serverjobcategory;
            }
            if ($filterjobsubcategory != '') {
                $serverjobsubcategory = $this->common_model->getServerid('subcategories', $filterjobsubcategory);
                $wherequery .= " AND t.subcategoryid = " . $serverjobsubcategory;
            }
            if (isset($radiussearch))
                $wherequery .= " AND $radiussearch";
            $data['cat_id'] = $cat_id;
            $data['limitstart'] = $limitstart;
            $data['limit'] = $limit;
            $data['wherequery'] = $wherequery;
            $data['server_address'] = $server_address;
            $data['authkey'] = $this->_client_auth_key;
            $data['siteurl'] = $this->_siteurl;
            $data['sortby'] = $sortby;

            if ($listjobconfig['subcategories'] == 1) {
                $fortask = "jobscategoryofsubcategories";
                $jsjobsharingobject = new JSJobsModelJob_Sharing;
                $encodedata = json_encode($data);
                $listsubcategory = array();
                $return_server_value_subcat = $jsjobsharingobject->serverTask($encodedata, $fortask);
                foreach ($return_server_value_subcat['listjobbysubcategory'] AS $d_subcategory) {
                    $listsubcategory[] = (object) $d_subcategory;
                }
                $subcategories = $listsubcategory;
            }

            $fortask = "getjobsbycategory";
            $jsjobsharingobject = new JSJobsModelJob_Sharing;
            $encodedata = json_encode($data);
            $return_server_value = $jsjobsharingobject->serverTask($encodedata, $fortask);
            if (isset($return_server_value['jobsbycategory']) AND $return_server_value['jobsbycategory'] == -1) { // auth fail 
                $logarray['uid'] = $this->_uid;
                $logarray['referenceid'] = $return_server_value['referenceid'];
                $logarray['eventtype'] = $return_server_value['eventtype'];
                $logarray['message'] = $return_server_value['message'];
                $logarray['event'] = "Jobs By Category";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jsjobsharingobject->write_JobSharingLog($logarray);
                $jobs = (object) array();
                $total = 0;
            } else {
                $parsedata_jobsbycategory = array();
                $total = $return_server_value['total'];
                if ($total != 0) {
                    foreach ($return_server_value['jobsbycategory'] AS $data) {
                        $parsedata_jobsbycategory[] = (object) $data;
                    }
                    $jobs = $parsedata_jobsbycategory;
                } else {
                    $category = (object) $return_server_value['category'];
                }
            }
        } else {
            $selectdistance = " ";
            if ($txtfilterlongitude != '' && $txtfilterlatitude != '' && $txtfilterradius != '') {
                $radiussearch = " acos((SIN( PI()* $txtfilterlatitude /180 )*SIN( PI()*job.latitude/180 ))+(cos(PI()* $txtfilterlatitude /180)*COS( PI()*job.latitude/180) *COS(PI()*job.longitude/180-PI()* $txtfilterlongitude /180)))* $radiuslength <= $txtfilterradius";
            }
            $wherequery = '';

            if ($city_filter != '')
                $wherequery .= " AND mjob.cityid =" . $city_filter;

            if ($filterjobsubcategory != '')
                $wherequery .= " AND job.subcategoryid = " . $filterjobsubcategory;
            if ($filterjobtype != '')
                $wherequery .= " AND job.jobtype = " . $filterjobtype;
            if (isset($radiussearch))
                $wherequery .= " AND $radiussearch";


            // sub categories query
            if ($listjobconfig['subcategories'] == 1) {
                $inquery = " (SELECT COUNT(job.id) from `#__js_job_jobs`  AS job WHERE subcat.id = job.subcategoryid AND job.status = 1 AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate);
                $inquery .= $wherequery . " ) as jobsinsubcat";

                $query = "SELECT  DISTINCT subcat.id, subcat.title,CONCAT(subcat.alias,'-',subcat.id) AS aliasid, ";
                $query .= $inquery;
                $query .= " FROM `#__js_job_subcategories` AS subcat
                            LEFT JOIN `#__js_job_jobs`  AS job ON subcat.id = job.subcategoryid
							LEFT JOIN `#__js_job_jobcities` AS mjob ON job.id = mjob.jobid                            
                            WHERE subcat.status = 1 AND categoryid = " . $cat_id;
                $query .= " ORDER BY subcat.title ";
                $db->setQuery($query);
                $subcategories = $db->loadObjectList();
            }

            $query = "SELECT COUNT(DISTINCT job.id) FROM `#__js_job_jobs` AS job
                        JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
                        LEFT JOIN `#__js_job_jobcities` AS mjob ON mjob.jobid = job.id 
                        WHERE job.jobcategory = cat.id AND job.status = 1  AND cat.id = " . $cat_id . " 
                        AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate);
            $query .= $wherequery;
            $db->setQuery($query);
            $total = $db->loadResult();

            if ($total <= $limitstart)
                $limitstart = 0;
            if ($total != 0) {
                $query = "SELECT DISTINCT job.*, cat.cat_title, jobtype.title AS jobtype, jobstatus.title AS jobstatus
                            , company.id AS companyid, company.name AS companyname, company.url
                            , salaryfrom.rangestart AS salaryfrom, salaryto.rangeend AS salaryto, salarytype.title AS salaytype
                            ,currency.symbol
                            ,(TO_DAYS( CURDATE() ) - To_days( job.startpublishing ) ) AS jobdays
                            ,CONCAT(cat.alias,'-',cat.id) AS aliasid
                            ,CONCAT(job.alias,'-',job.id) AS jobaliasid
                            ,CONCAT(company.alias,'-',companyid) AS companyaliasid
                            FROM `#__js_job_jobs` AS job
                            JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
                            JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                            JOIN `#__js_job_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id
                            LEFT JOIN `#__js_job_jobcities` AS mjob ON job.id = mjob.jobid
                            LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id
                            LEFT JOIN `#__js_job_salaryrange` AS salaryfrom ON job.salaryrangefrom = salaryfrom.id
                            LEFT JOIN `#__js_job_salaryrange` AS salaryto ON job.salaryrangeto = salaryto.id
                            LEFT JOIN `#__js_job_salaryrangetypes` AS salarytype ON job.salaryrangetype = salarytype.id
                            LEFT JOIN `#__js_job_currencies` AS currency ON currency.id = job.currencyid
                            WHERE job.status = 1  AND cat.id = " . $cat_id . "
                            AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate);

                $query .= $wherequery . " ORDER BY  " . $sortby;
                $db->setQuery($query, $limitstart, $limit);
                $jobs = $db->loadObjectList();
                foreach ($jobs AS $jobdata) {   // for multicity select 
                    $multicitydata = $this->getMultiCityData($jobdata->id);
                    if ($multicitydata != "")
                        $jobdata->city = $multicitydata;
                }
            }else {
                $query = "SELECT cat.cat_title
                            FROM `#__js_job_categories` AS cat
                            WHERE cat.id = " . $cat_id;
                $db->setQuery($query);
                $category = $db->loadObject();
            }
        }
        //for goldjobs
        if ($showgoldjobs == 1) {
            if ($noofgoldjobs != 0) {
                $goldjoblimit = ($limitstart / $limit) * $noofgoldjobs;
                $query = "SELECT job.*, cat.cat_title, jobtype.title AS jobtype, jobstatus.title AS jobstatus
                                , company.id AS companyid, company.name AS companyname, company.url 
                                , company.serverid AS companyserverid,company.alias AS companyalias,job.alias AS jobalias
                                , salaryfrom.rangestart AS salaryfrom, salaryto.rangeend AS salaryto, salarytype.title AS salaytype
                                , country.name AS countryname,city.name AS cityname,state.name AS statename , currency.symbol
                                ,(TO_DAYS( CURDATE() ) - To_days( job.startpublishing ) ) AS jobdays
                                ,CONCAT(cat.alias,'-',cat.id) AS aliasid
                                ,CONCAT(job.alias,'-',job.id) AS jobaliasid
                                ,CONCAT(company.alias,'-',companyid) AS companyaliasid

                                FROM `#__js_job_jobs` AS job
                                JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
                                JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                                JOIN `#__js_job_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id
                                LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id 
                                LEFT JOIN `#__js_job_salaryrange` AS salaryfrom ON job.salaryrangefrom = salaryfrom.id
                                LEFT JOIN `#__js_job_salaryrange` AS salaryto ON job.salaryrangeto = salaryto.id
                                LEFT JOIN `#__js_job_salaryrangetypes` AS salarytype ON job.salaryrangetype = salarytype.id
                                LEFT JOIN `#__js_job_countries` AS country ON job.country = country.id 
                                LEFT JOIN `#__js_job_states` AS state ON job.state = state.id 
                                LEFT JOIN `#__js_job_cities` AS city ON job.city = city.id 
                                LEFT JOIN `#__js_job_currencies` AS currency ON job.currencyid = currency.id 
                                WHERE job.status = 1 AND job.isgoldjob = 1
                                AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate);
                $db->setQuery($query, $goldjoblimit, $noofgoldjobs);
                $goldjobs = $db->loadObjectList();
                foreach ($goldjobs AS $goldjobdata) {   // for multicity select 
                    $multicitydata = $this->getMultiCityData($goldjobdata->id);
                    if ($multicitydata != "")
                        $goldjobdata->city = $multicitydata;
                    if ($this->_client_auth_key != "") {
                        $goldjobdata->id = $goldjobdata->serverid;
                        $goldjobdata->jobaliasid = $goldjobdata->jobalias . '-' . $goldjobdata->id;
                        $goldjobdata->companyid = $goldjobdata->companyserverid;
                        $goldjobdata->companyaliasid = $goldjobdata->companyalias . '-' . $goldjobdata->companyid;
                    }
                }
            }
        }
        else
            $goldjobs = array();

        //for featuredjob
        if ($showfeaturedjobs == 1) {
            if ($nooffeaturedjobs != 0) {
                $featuredjoblimit = ($limitstart / $limit) * $nooffeaturedjobs;
                $query = "SELECT job.*, cat.cat_title, jobtype.title AS jobtype, jobstatus.title AS jobstatus
                                , company.id AS companyid, company.name AS companyname, company.url 
                                , company.serverid AS companyserverid,company.alias AS companyalias,job.alias as jobalias
                                , salaryfrom.rangestart AS salaryfrom, salaryto.rangeend AS salaryto, salarytype.title AS salaytype
                                , country.name AS countryname,city.name AS cityname,state.name AS statename , currency.symbol
                                ,(TO_DAYS( CURDATE() ) - To_days( job.startpublishing ) ) AS jobdays
                                ,CONCAT(cat.alias,'-',cat.id) AS aliasid
                                ,CONCAT(job.alias,'-',job.id) AS jobaliasid
                                ,CONCAT(company.alias,'-',companyid) AS companyaliasid
                                FROM `#__js_job_jobs` AS job
                                JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
                                JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                                JOIN `#__js_job_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id
                                LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id 
                                LEFT JOIN `#__js_job_salaryrange` AS salaryfrom ON job.salaryrangefrom = salaryfrom.id
                                LEFT JOIN `#__js_job_salaryrange` AS salaryto ON job.salaryrangeto = salaryto.id
                                LEFT JOIN `#__js_job_salaryrangetypes` AS salarytype ON job.salaryrangetype = salarytype.id
                                LEFT JOIN `#__js_job_countries` AS country ON job.country = country.id 
                                LEFT JOIN `#__js_job_states` AS state ON job.state = state.id 
                                LEFT JOIN `#__js_job_cities` AS city ON job.city = city.id 
                                LEFT JOIN `#__js_job_currencies` AS currency ON job.currencyid = currency.id 
                                WHERE job.status = 1 AND job.isfeaturedjob = 1
                                AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate);

                $db->setQuery($query, $featuredjoblimit, $nooffeaturedjobs);
                $featuredjobs = $db->loadObjectList();
                foreach ($featuredjobs AS $featuredjobsdata) {   // for multicity select 
                    $multicitydata = $this->getMultiCityData($featuredjobsdata->id);
                    if ($multicitydata != "")
                        $featuredjobsdata->city = $multicitydata;
                    if ($this->_client_auth_key != "") {
                        $featuredjobsdata->id = $featuredjobsdata->serverid;
                        $featuredjobsdata->jobaliasid = $featuredjobsdata->jobalias . '-' . $featuredjobsdata->id;
                        $featuredjobsdata->companyid = $featuredjobsdata->companyserverid;
                        $featuredjobsdata->companyaliasid = $featuredjobsdata->companyalias . '-' . $featuredjobsdata->companyid;
                    }
                }
            }
        }
        else
            $featuredjobs = array();









        $jobtype = $this->common_model->getJobType(JText::_('JS_SELECT_JOB_TYPE'));
        $jobstatus = $this->common_model->getJobStatus(JText::_('JS_SELECT_JOB_STATUS'));
        $heighesteducation = $this->common_model->getHeighestEducation(JText::_('JS_SELECT_EDUCATION'));

        $job_categories = $this->common_model->getCategories(JText::_('JS_SELECT_CATEGORY'));
        if ($cat_id == 0 || $cat_id == '')
            $flt_jobcatid = 1;
        else {
            if ($this->_client_auth_key != '') {
                $flt_jobcatid = $this->common_model->getClientId('categories', $cat_id);
            }
            else
                $flt_jobcatid = $cat_id;
        }
        $job_subcategories = $this->common_model->getSubCategoriesforCombo($flt_jobcatid, JText::_('JS_SELECT_CATEGORY'), $value = '');
        $job_salaryrange = $this->common_model->getJobSalaryRange(JText::_('JS_SELECT_SALARY'), '');
        $countries = $this->common_model->getSharingCountries(JText::_('JS_SELECT_COUNTRY'));

        $filterlists['country'] = JHTML::_('select.genericList', $countries, 'cmbfilter_country', 'class="inputbox"  style="width:' . $address_fields_width . 'px;" ' . '', 'value', 'text', $cmbfiltercountry);

        $filterlists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'filter_jobcategory', 'class="inputbox" ' . 'onChange="fj_getsubcategories(\'td_jobsubcategory\',this.value);"', 'value', 'text', $flt_jobcatid);
        $filterlists['jobsubcategory'] = JHTML::_('select.genericList', $job_subcategories, 'filter_jobsubcategory', 'class="inputbox" ' . '', 'value', 'text', $filterjobsubcategory);
        $filterlists['jobtype'] = JHTML::_('select.genericList', $jobtype, 'filter_jobtype', 'class="inputbox" ' . '', 'value', 'text', $filterjobtype);

        $location = $this->common_model->getAddressDataByCityName('', $city_filter);
        if (isset($location[0]->name))
            $filtervalues['location'] = $location[0]->name;
        else
            $filtervalues['location'] = "";

        $filtervalues['city'] = $city_filter;
        $filtervalues['radius'] = $txtfilterradius;
        $filtervalues['longitude'] = $txtfilterlongitude;
        $filtervalues['latitude'] = $txtfilterlatitude;

        $packageexpiry = $this->getJobSeekerPackageExpiry($uid);
        if ($packageexpiry == 1) { //package expire or user not login
            $listjobconfigs = array();
            $listjobconfigs['lj_title'] = $listjobconfig['visitor_lj_title'];
            $listjobconfigs['lj_category'] = $listjobconfig['visitor_lj_category'];
            $listjobconfigs['lj_jobtype'] = $listjobconfig['visitor_lj_jobtype'];
            $listjobconfigs['lj_jobstatus'] = $listjobconfig['visitor_lj_jobstatus'];
            $listjobconfigs['lj_company'] = $listjobconfig['visitor_lj_company'];
            $listjobconfigs['lj_companysite'] = $listjobconfig['visitor_lj_companysite'];
            $listjobconfigs['lj_country'] = $listjobconfig['visitor_lj_country'];
            $listjobconfigs['lj_state'] = $listjobconfig['visitor_lj_state'];
            $listjobconfigs['lj_city'] = $listjobconfig['visitor_lj_city'];
            $listjobconfigs['lj_salary'] = $listjobconfig['visitor_lj_salary'];
            $listjobconfigs['lj_created'] = $listjobconfig['visitor_lj_created'];
            $listjobconfigs['lj_noofjobs'] = $listjobconfig['visitor_lj_noofjobs'];
            $listjobconfigs['subcategories'] = $listjobconfig['subcategories'];
            $listjobconfigs['subcategories_all'] = $listjobconfig['subcategories_all'];
            $listjobconfigs['subcategories_colsperrow'] = $listjobconfig['subcategories_colsperrow'];
            $listjobconfigs['subcategoeis_max_hight'] = $listjobconfig['subcategoeis_max_hight'];
            $listjobconfigs['lj_description'] = $listjobconfig['visitor_lj_description'];
            $listjobconfigs['lj_shortdescriptionlenght'] = $listjobconfig['lj_shortdescriptionlenght'];
            $listjobconfigs['lj_joblistingstyle'] = $listjobconfig['lj_joblistingstyle'];
        }
        else
            $listjobconfigs = $listjobconfig; // user

        if (isset($jobs))
            $result[0] = $jobs;
        $result[1] = $total;
        $result[2] = $filterlists;
        $result[3] = $filtervalues;
        $result[4] = $listjobconfigs;
        $result[5] = $subcategories;
        if (isset($category))
            $result[6] = $category;
        $result[7] = $goldjobs;
        $result[8] = $featuredjobs;

        return $result;
    }

    function getDefaultSharingLocation($server_address, $filtersharingcountry) {
        $db = &$this->getDBO();
        $sharing_location_config = $this->common_model->getConfigByFor('jobsharing');
        if ($sharing_location_config['default_sharing_city'] != "" AND $sharing_location_config['default_sharing_city'] != 0) {
            $server_address['defaultsharingcity'] = $sharing_location_config['default_sharing_city'];
        } elseif ($sharing_location_config['default_sharing_state'] != "" AND $sharing_location_config['default_sharing_state'] != 0) {
            $server_address['defaultsharingstate'] = $sharing_location_config['default_sharing_state'];
        } elseif ($filtersharingcountry != "" AND $filtersharingcountry != 0) {
            $server_address['filtersharingcountry'] = $filtersharingcountry;
        } elseif ($sharing_location_config['default_sharing_country'] != "" AND $sharing_location_config['default_sharing_country'] != 0) {
            $server_address['defaultsharingcountry'] = $sharing_location_config['default_sharing_country'];
        }
        return $server_address;
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

    function &getJobAlertbyUidforForm($uid) {
        $db = &$this->getDBO();
        if ($uid)
            if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
                return false;
        if (!isset($this->_config)) {
            $this->_config = $this->common_model->getConfig('');
        }
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'overwrite_jobalert_settings')
                $overwrite_jobalert_settings = $conf->configvalue;
        }
        $jobalert = $overwrite_jobalert_settings;
        if ($jobalert == 0) {
            $jobalert = $this->canSetJobAlert($uid);
        }
        if ($jobalert == 1) {
            if (is_numeric($uid) == false)
                return false;
            if ($uid != 0) {
                $query = "SELECT jobset.*
	                FROM `#__js_job_jobalertsetting` AS jobset
					WHERE jobset.uid = " . $uid;
                $db->setQuery($query);
                $setting = $db->loadObject();
            }
            $alerttype = $this->getAlerttype('', '');
            $categories = $this->common_model->getCategories('');
            if (isset($setting)) {
                if ($setting->categoryid)
                    $categoryid = $setting->categoryid;
                else
                    $categoryid = $categories[0]['value'];
                if ($setting->subcategoryid)
                    $subcategoryid = $setting->subcategoryid;
                else
                    $subcategoryid = '';

                $lists['jobcategory'] = JHTML::_('select.genericList', $categories, 'categoryid', 'class="inputbox" ' . 'onChange="fj_getsubcategories(\'fj_subcategory\', this.value)"', 'value', 'text', $categoryid);
                $lists['subcategory'] = JHTML::_('select.genericList', $this->common_model->getSubCategoriesforCombo($categoryid, JText::_('JS_SUB_CATEGORY'), ''), 'subcategoryid', 'class="inputbox" ' . '', 'value', 'text', $subcategoryid);
                $lists['alerttype'] = JHTML::_('select.genericList', $alerttype, 'alerttype', 'class="inputbox required" ' . '', 'value', 'text', $setting->alerttype);
                $multi_lists = $this->common_model->getMultiSelectEdit($setting->id, 3);
            }else {
                $lists['jobcategory'] = JHTML::_('select.genericList', $categories, 'categoryid', 'class="inputbox" ' . 'onChange="fj_getsubcategories(\'fj_subcategory\', this.value)"', 'value', 'text', '');
                $lists['subcategory'] = JHTML::_('select.genericList', $this->common_model->getSubCategoriesforCombo($categories[0]['value'], JText::_('JS_SUB_CATEGORY'), ''), 'subcategoryid', 'class="inputbox" ' . '', 'value', 'text', '');
                $lists['alerttype'] = JHTML::_('select.genericList', $alerttype, 'alerttype', 'class="inputbox required" ' . '', 'value', 'text', '');
            }
        }
        if (isset($setting))
            $result[0] = $setting;
        $result[1] = $lists;
        $result[2] = $jobalert;
        if (isset($multi_lists) && $multi_lists != "")
            $result[3] = $multi_lists;

        return $result;
    }

    function getMyStats_JobSeeker($uid) {
        if (is_numeric($uid) == false)
            return false;
        if (($uid == 0) || ($uid == ''))
            return false;

        $db = &$this->getDBO();
        $results = array();
        if (!isset($this->_config)) {
            $this->_config = $this->common_model->getConfig('');
        }
        $ispackagerequired = 1;
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'js_newlisting_requiredpackage')
                $newlisting_required_package = $conf->configvalue;
        }
        if ($newlisting_required_package == 0) {
            $ispackagerequired = 0;
        }
        // resume
        $query = "SELECT package.resumeallow,package.coverlettersallow,package.featuredresume,package.goldresume
                    FROM #__js_job_jobseekerpackages AS package
                    JOIN #__js_job_paymenthistory AS payment ON (package.id = payment.packageid AND payment.packagefor=2 )
                    WHERE payment.uid = " . $uid . "
                    AND DATE_ADD(payment.created,INTERVAL package.packageexpireindays DAY) >= CURDATE()
                    AND payment.transactionverified = 1 AND payment.status = 1";
        $db->setQuery($query);
        $packages = $db->loadObjectList();
        if (empty($packages)) {
            $query = "SELECT package.id, package.resumeallow,package.title AS packagetitle, package.packageexpireindays, payment.id AS paymentid
                        , (TO_DAYS( CURDATE() ) - To_days( payment.created ) ) AS packageexpiredays
                       FROM `#__js_job_jobseekerpackages` AS package
                       JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=2 )
                       WHERE payment.uid = " . $uid . " 
                       AND payment.transactionverified = 1 AND payment.status = 1 ORDER BY payment.created DESC";
            $db->setQuery($query);
            $packagedetail = $db->loadObjectList();

            $results[8] = false;
            $results[9] = $packagedetail;

            $query = "SELECT package.resumeallow,package.coverlettersallow,package.featuredresume,package.goldresume
                    FROM #__js_job_jobseekerpackages AS package
                    JOIN #__js_job_paymenthistory AS payment ON (package.id = payment.packageid AND payment.packagefor=2)
                    WHERE payment.uid = " . $uid . "
                    AND payment.transactionverified = 1 AND payment.status = 1";
            $db->setQuery($query);
            $packages = $db->loadObjectList();
        }
        $unlimitedresume = 0;
        $unlimitedfeaturedresume = 0;
        $unlimitedgoldresume = 0;
        $unlimitedcoverletters = 0;
        $resumeallow = 0;
        $featuredresumeallow = 0;
        $goldresumeallow = 0;
        $coverlettersallow = 0;

        foreach ($packages AS $package) {
            if ($unlimitedresume == 0) {
                if ($package->resumeallow != -1) {
                    $resumeallow = $resumeallow + $package->resumeallow;
                }
                else
                    $unlimitedresume = 1;
            }
            if ($unlimitedfeaturedresume == 0) {
                if ($package->featuredresume != -1) {
                    $featuredresumeallow = $featuredresumeallow + $package->featuredresume;
                }
                else
                    $unlimitedfeaturedresume = 1;
            }
            if ($unlimitedgoldresume == 0) {
                if ($package->goldresume != -1) {
                    $goldresumeallow = $goldresumeallow + $package->goldresume;
                }
                else
                    $unlimitedgoldcompanies = 1;
            }
            if ($unlimitedcoverletters == 0) {
                if ($package->coverlettersallow != -1) {
                    $coverlettersallow = $coverlettersallow + $package->coverlettersallow;
                }
                else
                    $unlimitedcoverletters = 1;
            }
        }

        //resume
        $query = "SELECT COUNT(id) FROM #__js_job_resume WHERE  uid = " . $uid;
        $db->setQuery($query);
        $totalresume = $db->loadResult();

        //featured resume
        $query = "SELECT COUNT(id) FROM #__js_job_resume WHERE isfeaturedresume=1 AND uid = " . $uid;
        $db->setQuery($query);
        $totalfeaturedresume = $db->loadResult();

        //gold resume
        $query = "SELECT COUNT(id) FROM #__js_job_resume WHERE isgoldresume=1 AND uid = " . $uid;
        $db->setQuery($query);
        $totalgoldresume = $db->loadResult();

        //cover letter
        $query = "SELECT COUNT(id) FROM #__js_job_coverletters WHERE uid = " . $uid;
        $db->setQuery($query);
        $totalcoverletters = $db->loadResult();


        if ($unlimitedresume == 0)
            $results[0] = $resumeallow;
        elseif ($unlimitedresume == 1)
            $results[0] = -1;

        $results[1] = $totalresume;

        if ($unlimitedfeaturedresume == 0)
            $results[2] = $featuredresumeallow;
        elseif ($unlimitedfeaturedresume == 1)
            $results[2] = -1;
        $results[3] = $totalfeaturedresume;

        if ($unlimitedgoldresume == 0)
            $results[4] = $goldresumeallow;
        elseif ($unlimitedgoldresume == 1)
            $results[4] = -1;
        $results[5] = $totalgoldresume;

        if ($unlimitedcoverletters == 0)
            $results[6] = $coverlettersallow;
        elseif ($unlimitedcoverletters == 1)
            $results[6] = -1;
        $results[7] = $totalcoverletters;
        $results[10] = $ispackagerequired;

        return $results;
    }

    function &getJobSearch($uid, $title, $jobcategory, $jobsubcategory, $jobtype, $jobstatus, $currency, $salaryrangefrom, $salaryrangeto, $salaryrangetype, $shift, $experience, $durration, $startpublishing, $stoppublishing, $company, $city, $zipcode, $longitude, $latitude, $radius, $radius_length_type, $keywords, $sortby, $limit, $limitstart) {

        if (isset($uid))
            if (is_numeric($uid) == false)
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
        if ($jobstatus != '')
            if (is_numeric($jobstatus) == false)
                return false;
        if ($salaryrangefrom != '')
            if (is_numeric($salaryrangefrom) == false)
                return false;
        if ($salaryrangeto != '')
            if (is_numeric($salaryrangeto) == false)
                return false;
        if ($salaryrangetype != '')
            if (is_numeric($salaryrangetype) == false)
                return false;
        if ($shift != '')
            if (is_numeric($shift) == false)
                return false;
        if ($company != '')
            if (is_numeric($company) == false)
                return false;
        if ($currency != '')
            if (is_numeric($currency) == false)
                return false;

        $db = &$this->getDBO();
        if (!isset($this->_config)) {
            $this->_config = $this->common_model->getConfig('');
        }
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'filter_address_fields_width')
                $address_fields_width = $conf->configvalue;
            if ($conf->configname == 'date_format')
                $dateformat = $conf->configvalue;
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
                $stoppublishing = $arr[0] . '/' . $arr[1] . '/' . $arr[2];
            } elseif ($dateformat == 'd-m-Y') {
                $arr = explode('-', $stoppublishing);
                $stoppublishing = $arr[2] . '-' . $arr[1] . '-' . $arr[0];
            }
            $stoppublishing = date('Y-m-d', strtotime($stoppublishing));
        }
        if ($uid) {
            foreach ($this->_config as $conf) {
                if ($conf->configname == 'js_newlisting_requiredpackage')
                    $newlisting_required_package = $conf->configvalue;
            }
            if ($newlisting_required_package == 0) {
                $canview = 1;
            } else {
                $query = "SELECT package.savejobsearch, package.packageexpireindays, payment.created
                            FROM `#__js_job_jobseekerpackages` AS package
                            JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=2)
                            WHERE payment.uid = " . $uid . "
                            AND DATE_ADD(payment.created,INTERVAL package.packageexpireindays DAY) >= CURDATE()
                            AND payment.transactionverified = 1 AND payment.status = 1";
                //echo $query;
                $db->setQuery($query);
                $jobs = $db->loadObjectList();
                $canview = 0;

                foreach ($jobs AS $job) {
                    if ($job->savejobsearch == 1) {
                        $canview = 1;
                        break;
                    }
                    else
                        $canview = 0;
                }
            }
        }
        else
            $canview = 1; // visitor case

        $result = array();
        $searchjobconfig = $this->common_model->getConfigByFor('searchjob');
        $listjobconfig = $this->common_model->getConfigByFor('listjob');
        //for radius search
        switch ($radius_length_type) {
            case "m":$radiuslength = 6378137;
                break;
            case "km":$radiuslength = 6378.137;
                break;
            case "mile":$radiuslength = 3963.191;
                break;
            case "nacmiles":$radiuslength = 3441.596;
                break;
        }
        if ($this->_client_auth_key != "") {
            if ($keywords) {// For keyword Search
                $keywords = explode(' ', $keywords);
                $length = count($keywords);
                if ($length <= 5) {// For Limit keywords to 5
                    $i = $length;
                } else {
                    $i = 5;
                }
                for ($j = 0; $j < $i; $j++) {
                    $keys[] = " t.metakeywords Like '%$keywords[$j]%'";
                }
            }
            if ($title != '') {
                $title_keywords = explode(' ', $title);
                $tlength = count($title_keywords);
                if ($tlength <= 5) {// For Limit keywords to 5
                    $r = $tlength;
                } else {
                    $r = 5;
                }
                for ($k = 0; $k < $r; $k++) {
                    $titlekeys[] = " t.title LIKE '%" . str_replace("'", "", $db->Quote($title_keywords[$k])) . "%'";
                }
            }

            $selectdistance = " ";
            if ($longitude != '' && $latitude != '' && $radius != '') {
                $radiussearch = " acos((SIN( PI()* $latitude /180 )*SIN( PI()*t.latitude/180 ))+(cos(PI()* $latitude /180)*COS( PI()*t.latitude/180) *COS(PI()*t.longitude/180-PI()* $longitude /180)))* $radiuslength <= $radius";
                $selectdistance = " ,acos((sin(PI()*$latitude/180)*sin(PI()*t.latitude/180))+(cos(PI()*$latitude/180)*cos(PI()*t.latitude/180)*cose(PI()*t.longitude/180 - PI()*$longitude/180)))*$radiuslength AS distance ";
            }
            $wherequery = '';
            $issalary = '';
            if ($jobcategory != '') {
                $sj_serverjobcategory = $this->common_model->getServerid('categories', $jobcategory);
                $wherequery .= " AND t.jobcategory = " . $sj_serverjobcategory;
            }
            if (isset($keys))
                $wherequery .= " AND ( " . implode(' OR ', $keys) . " )";
            if (isset($titlekeys))
                $wherequery .= " AND ( " . implode(' OR ', $titlekeys) . " )";
            if ($jobsubcategory != '') {
                $sj_serverjobsubcategory = $this->common_model->getServerid('subcategories', $jobsubcategory);
                $wherequery .= " AND t.subcategoryid = " . $sj_serverjobsubcategory;
            }
            if ($jobtype != '') {
                $sj_serverjobtype = $this->common_model->getServerid('jobtypes', $jobtype);
                $wherequery .= " AND t.jobtype = " . $sj_serverjobtype;
            }
            if ($jobstatus != '') {
                $sj_serverjobstatus = $this->common_model->getServerid('jobstatus', $jobstatus);
                $wherequery .= " AND t.jobstatus = " . $sj_serverjobstatus;
            }
            if ($salaryrangefrom != '') {
                $query = "SELECT salfrom.rangestart
                    FROM `#__js_job_salaryrange` AS salfrom
                    WHERE salfrom.id = " . $salaryrangefrom;
                $db->setQuery($query);
                $sj_rangestart_value = $db->loadResult();
                $wherequery .= " AND job_salrangefrom.rangestart >= " . $sj_rangestart_value;
                $issalary = 1;
            }
            if ($salaryrangeto != '') {
                $query = "SELECT salto.rangestart
                    FROM `#__js_job_salaryrange` AS salto
                    WHERE salto.id = " . $salaryrangeto;
                $db->setQuery($query);
                $sj_rangeend_value = $db->loadResult();
                $wherequery .= " AND job_salrangeto.rangeend <= " . $sj_rangeend_value;
                $issalary = 1;
            }
            if (($issalary != '') && ($salaryrangetype != '')) {
                $sj_serverjobsalaryrangetype = $this->common_model->getServerid('salaryrangetypes', $salaryrangetype);
                $wherequery .= " AND t.salaryrangetype = " . $sj_serverjobsalaryrangetype;
            }
            if ($shift != '') {
                $sj_serverjobshifts = $this->common_model->getServerid('shifts', $shift);
                $wherequery .= " AND t.shift = " . $sj_serverjobshifts;
            }
            if ($experience != '') {
                $wherequery .= " AND t.experience LIKE " . $experience;
            }
            if ($durration != '')
                $wherequery .= " AND t.duration LIKE " . $db->Quote($durration);
            if ($startpublishing != '')
                $wherequery .= " AND t.startpublishing >= " . $db->Quote($startpublishing);
            if ($stoppublishing != '')
                $wherequery .= " AND t.stoppublishing <= " . $db->Quote($stoppublishing);
            if ($company != '') {
                $query = "SELECT company.serverid
                    FROM `#__js_job_companies` AS company
                    WHERE company.id = " . $company;
                $db->setQuery($query);
                $sj_serverjobcompany = $db->loadResult();
                $wherequery .= " AND t.companyid = " . $sj_serverjobcompany;
            }

            $server_address = "";
            if ($city != '') {
                $city_value = explode(',', $city);
                $server_city_id = array();
                $lenght = count($city_value);
                //echo '<br> lenght'.$lenght;
                for ($i = 0; $i < $lenght; $i++) {
                    $server_city_id[$i] = $this->common_model->getServerid('cities', $city_value[$i]);
                    if ($i == 0)
                        $server_address .= " AND ( job_jobcities.cityid=" . $server_city_id[$i];
                    else
                        $server_address .= " OR job_jobcities.cityid=" . $server_city_id[$i];
                }
                $server_address .= ") AND job_jobcities.jobid=t.id ";
            }
            if ($currency != '') {
                $sj_servercurrency = $this->common_model->getServerid('currencies', $currency);
                $wherequery .= " AND t.currencyid = " . $sj_servercurrency;
            }
            if ($zipcode != '')
                $wherequery .= " AND t.zipcode = " . $db->Quote($zipcode);
            if (isset($radiussearch) && $radiussearch != '')
                $wherequery .= " AND $radiussearch";

            $fortask = "getjobsearch";
            $jsjobsharingobject = new JSJobsModelJob_Sharing;
            $data['limitstart'] = $limitstart;
            $data['limit'] = $limit;
            $data['sortby'] = $sortby;
            $data['wherequery'] = $wherequery;
            $data['server_address'] = $server_address;
            $data['authkey'] = $this->_client_auth_key;
            $data['siteurl'] = $this->_siteurl;
            $encodedata = json_encode($data);
            $return_server_value = $jsjobsharingobject->serverTask($encodedata, $fortask);
            if (isset($return_server_value['jobsearch']) AND $return_server_value['jobsearch'] == -1) { // auth fail 
                $logarray['uid'] = $this->_uid;
                $logarray['referenceid'] = $return_server_value['referenceid'];
                $logarray['eventtype'] = $return_server_value['eventtype'];
                $logarray['message'] = $return_server_value['message'];
                $logarray['event'] = "Search Job";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jsjobsharingobject->write_JobSharingLog($logarray);
                $this->_applications = (object) array();
                $total = 0;
            } else {
                $s_search_result = array();
                foreach ($return_server_value['searchjob'] AS $search_job) {
                    $s_search_result[] = (object) $search_job;
                }
                $this->_applications = $s_search_result;
                $total = $return_server_value['total'];
            }
        } else {
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
            $issalary = '';

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
                $wherequery .= " AND job.jobcategory = " . $jobcategory;
            if (isset($keys))
                $wherequery .= " AND ( " . implode(' OR ', $keys) . " )";
            if (isset($titlekeys))
                $wherequery .= " AND ( " . implode(' OR ', $titlekeys) . " )";
            if ($jobsubcategory != '')
                $wherequery .= " AND job.subcategoryid = " . $jobsubcategory;
            if ($jobtype != '')
                $wherequery .= " AND job.jobtype = " . $jobtype;
            if ($jobstatus != '')
                $wherequery .= " AND job.jobstatus = " . $jobstatus;
            if ($salaryrangefrom != '') {
                $query = "SELECT salfrom.rangestart FROM `#__js_job_salaryrange` AS salfrom WHERE salfrom.id = " . $salaryrangefrom;
                $db->setQuery($query);
                $rangestart_value = $db->loadResult();
                $wherequery .= " AND salaryrangefrom.rangestart >= " . $rangestart_value;
                $issalary = 1;
            }
            if ($salaryrangeto != '') {
                $query = "SELECT salto.rangestart FROM `#__js_job_salaryrange` AS salto WHERE salto.id = " . $salaryrangeto;
                $db->setQuery($query);
                $rangeend_value = $db->loadResult();
                $wherequery .= " AND salaryrangeto.rangeend <= " . $rangeend_value;
                $issalary = 1;
            }
            if (($issalary != '') && ($salaryrangetype != '')) {
                $wherequery .= " AND job.salaryrangetype = " . $salaryrangetype;
            }
            if ($shift != '')
                $wherequery .= " AND job.shift = " . $shift;
            if ($experience != '')
                $wherequery .= " AND job.experience LIKE " . $db->Quote($experience);
            if ($durration != '')
                $wherequery .= " AND job.duration LIKE " . $db->Quote($durration);
            if ($startpublishing != '')
                $wherequery .= " AND job.startpublishing >= " . $db->Quote($startpublishing);
            if ($stoppublishing != '')
                $wherequery .= " AND job.stoppublishing <= " . $db->Quote($stoppublishing);
            if ($company != '')
                $wherequery .= " AND job.companyid = " . $company;
            if ($city != '') {
                $city_value = explode(',', $city);
                $lenght = count($city_value);
                for ($i = 0; $i < $lenght; $i++) {
                    if ($i == 0)
                        $wherequery .= " AND ( mjob.cityid=" . $city_value[$i];
                    else
                        $wherequery .= " OR mjob.cityid=" . $city_value[$i];
                }
                $wherequery .= ")";
            }
            if ($zipcode != '')
                $wherequery .= " AND job.zipcode = " . $db->Quote($zipcode);
            if (isset($radiussearch) && $radiussearch != '')
                $wherequery .= " AND $radiussearch";
            $curdate = date('Y-m-d H:i:s');
            $query = "SELECT count(DISTINCT job.id) FROM `#__js_job_jobs` AS job 
					  JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
					  LEFT JOIN `#__js_job_salaryrange` AS salaryrangefrom ON job.salaryrangefrom = salaryrangefrom.id
					  LEFT JOIN `#__js_job_salaryrange` AS salaryrangeto ON job.salaryrangeto = salaryrangeto.id";
            $query .= " LEFT JOIN `#__js_job_jobcities` AS mjob ON mjob.jobid = job.id ";
            $query .= "	WHERE job.status = 1 ";
            if ($startpublishing == '')
                $query .= " AND job.startpublishing <= " . $db->Quote($curdate);
            if ($stoppublishing == '')
                $query .= " AND job.stoppublishing >= " . $db->Quote($curdate);

            $query .= $wherequery;
            $db->setQuery($query);
            $total = $db->loadResult();
            if ($total <= $limitstart)
                $limitstart = 0;
            $query = "SELECT DISTINCT job.*, cat.cat_title, jobtype.title AS jobtypetitle, jobstatus.title AS jobstatustitle
                        , salaryrangefrom.rangestart AS salaryfrom, salaryrangeto.rangeend AS salaryend, salaryrangeto.rangeend AS salaryto
                        ,company.id AS companyid, company.name AS companyname, company.url, salaryrangetype.title AS salaytype";
            $query .= " ,job.isgoldjob AS isgold,job.isfeaturedjob AS isfeatured,(TO_DAYS( CURDATE() ) - To_days( job.startpublishing ) ) AS jobdays";
            $query .= " ,CONCAT(job.alias,'-',job.id) AS aliasid";
            $query .= " ,CONCAT(company.alias,'-',companyid) AS companyaliasid";
            $query .= " ,currency.symbol AS symbol";

            $query .= "	FROM `#__js_job_jobs` AS job
                        JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
                        JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                        JOIN `#__js_job_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id
                        LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id
                        LEFT JOIN `#__js_job_countries` AS country ON job.country = country.id
                        LEFT JOIN `#__js_job_salaryrange` AS salaryrangefrom ON job.salaryrangefrom = salaryrangefrom.id
                        LEFT JOIN `#__js_job_salaryrange` AS salaryrangeto ON job.salaryrangeto = salaryrangeto.id
                        LEFT JOIN `#__js_job_salaryrangetypes` AS salaryrangetype ON job.salaryrangetype = salaryrangetype.id";
            $query .= " LEFT JOIN `#__js_job_jobcities` AS mjob ON mjob.jobid = job.id ";
            $query .= " LEFT JOIN `#__js_job_currencies` AS currency ON currency.id = job.currencyid ";

            $query .= " WHERE job.status = 1";
            if ($startpublishing == '')
                $query .= " AND job.startpublishing <= " . $db->Quote($curdate);
            if ($stoppublishing == '')
                $query .= " AND job.stoppublishing >= " . $db->Quote($curdate);
            if ($currency != '')
                $query .= " AND job.currencyid = " . $currency;
            $query .= $wherequery;
            $query .= " ORDER BY  " . $sortby;
            $db->setQuery($query, $limitstart, $limit);
            $this->_applications = $db->loadObjectList();
            foreach ($this->_applications AS $searchdata) {  // for multicity select 
                $multicitydata = $this->getMultiCityData($searchdata->id);
                if ($multicitydata != "")
                    $searchdata->city = $multicitydata;
            }
        }
        $packageexpiry = $this->getJobSeekerPackageExpiry($uid);
        if ($packageexpiry == 1) { //package expire or user not login
            $listjobconfigs = array();
            $listjobconfigs['lj_title'] = $listjobconfig['visitor_lj_title'];
            $listjobconfigs['lj_category'] = $listjobconfig['visitor_lj_category'];
            $listjobconfigs['lj_jobtype'] = $listjobconfig['visitor_lj_jobtype'];
            $listjobconfigs['lj_jobstatus'] = $listjobconfig['visitor_lj_jobstatus'];
            $listjobconfigs['lj_company'] = $listjobconfig['visitor_lj_company'];
            $listjobconfigs['lj_companysite'] = $listjobconfig['visitor_lj_companysite'];
            $listjobconfigs['lj_country'] = $listjobconfig['visitor_lj_country'];
            $listjobconfigs['lj_state'] = $listjobconfig['visitor_lj_state'];
            $listjobconfigs['lj_city'] = $listjobconfig['visitor_lj_city'];
            $listjobconfigs['lj_salary'] = $listjobconfig['visitor_lj_salary'];
            $listjobconfigs['lj_created'] = $listjobconfig['visitor_lj_created'];
            $listjobconfigs['lj_noofjobs'] = $listjobconfig['visitor_lj_noofjobs'];
            $listjobconfigs['lj_description'] = $listjobconfig['visitor_lj_description'];
            $listjobconfigs['lj_shortdescriptionlenght'] = $listjobconfig['lj_shortdescriptionlenght'];
            $listjobconfigs['lj_joblistingstyle'] = $listjobconfig['lj_joblistingstyle'];
        }
        else
            $listjobconfigs = $listjobconfig; // user

        $result[0] = $this->_applications;
        $result[1] = $total;
        $result[2] = $listjobconfigs;
        $result[3] = $searchjobconfig;
        $result[4] = $canview;
        return $result;
    }

    function &getJobsbySubCategory($uid, $subcat_id, $city_filter, $cmbfiltercountry
    , $filterjobcategory, $filterjobsubcategory, $filterjobtype
    , $txtfilterlongitude, $txtfilterlatitude, $txtfilterradius, $cmbfilterradiustype
    , $sortby, $limit, $limitstart) {

        $db = &$this->getDBO();
        $result = array();
        if (is_numeric($subcat_id) == false)
            return false;
        if ($filterjobtype != '')
            if (is_numeric($filterjobtype) == false)
                return false;

        if (!isset($this->_config)) {
            $this->_config = $this->common_model->getConfig('');
        }
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'filter_address_fields_width')
                $address_fields_width = $conf->configvalue;
            if ($conf->configname == 'noofgoldjobsinlisting')
                $noofgoldjobs = $conf->configvalue;
            if ($conf->configname == 'nooffeaturedjobsinlisting')
                $nooffeaturedjobs = $conf->configvalue;
            if ($conf->configname == 'showgoldjobsinlistjobs')
                $showgoldjobs = $conf->configvalue;
            if ($conf->configname == 'showfeaturedjobsinlistjobs')
                $showfeaturedjobs = $conf->configvalue;
        }
        $listjobconfig = $this->common_model->getConfigByFor('listjob');
        //for radius search
        switch ($cmbfilterradiustype) {
            case "m":$radiuslength = 6378137;
                break;
            case "km":$radiuslength = 6378.137;
                break;
            case "mile":$radiuslength = 3963.191;
                break;
            case "nacmiles":$radiuslength = 3441.596;
                break;
        }
        $curdate = date('Y-m-d H:i:s');
        if ($this->_client_auth_key != "") {
            $selectdistance = " ";
            if ($txtfilterlongitude != '' && $txtfilterlatitude != '' && $txtfilterradius != '') {
                //$radiussearch = " acos((sin(PI()*$latitude/180)*sin(PI()*job.latitude/180))+(cos(PI()*$latitude/180)*cos(PI()*job.latitude/180)*cose(PI()*job.longitude/180 - PI()*$longitude/180)))*$radiuslength <= $radius";
                $radiussearch = " acos((SIN( PI()* $txtfilterlatitude /180 )*SIN( PI()*t.latitude/180 ))+(cos(PI()* $txtfilterlatitude /180)*COS( PI()*t.latitude/180) *COS(PI()*t.longitude/180-PI()* $txtfilterlongitude /180)))* $radiuslength <= $txtfilterradius";
                //$selectdistance = " ,acos((sin(PI()*$latitude/180)*sin(PI()*job.latitude/180))+(cos(PI()*$latitude/180)*cos(PI()*job.latitude/180)*cose(PI()*job.longitude/180 - PI()*$longitude/180)))*$radiuslength AS distance ";
            }

            $wherequery = '';
            $server_address = array();
            if ($city_filter != '') {
                $server_citiy_id = $this->common_model->getServerid('cities', $city_filter);
                $server_address['multicityid'] = $server_citiy_id;
                $server_country_id = $this->common_model->getSeverCountryid($city_filter);
                if ($server_country_id == false)
                    $cmbfiltercountry = '';
                else
                    $cmbfiltercountry = $server_country_id;
            }else {
                $default_sharing_loc = $this->getDefaultSharingLocation($server_address, $cmbfiltercountry);
                //$server_address=$default_sharing_loc;
                if (isset($default_sharing_loc['defaultsharingcity']) AND ($default_sharing_loc['defaultsharingcity'] != '')) {
                    $city_filter = $default_sharing_loc['defaultsharingcity'];
                    $server_address['multicityid'] = $default_sharing_loc['defaultsharingcity'];
                } elseif (isset($default_sharing_loc['defaultsharingstate']) AND ($default_sharing_loc['defaultsharingstate'] != '')) {
                    $server_address['defaultsharingstate'] = $default_sharing_loc['defaultsharingstate'];
                } elseif (isset($default_sharing_loc['filtersharingcountry']) AND ($default_sharing_loc['filtersharingcountry'] != '')) {
                    $server_address['filtersharingcountry'] = $default_sharing_loc['filtersharingcountry'];
                    $cmbfiltercountry = $default_sharing_loc['filtersharingcountry'];
                } elseif (isset($default_sharing_loc['defaultsharingcountry']) AND ($default_sharing_loc['defaultsharingcountry'] != '')) {
                    $server_address['defaultsharingcountry'] = $default_sharing_loc['defaultsharingcountry'];
                    $cmbfiltercountry = $default_sharing_loc['defaultsharingcountry'];
                }
            }


            if ($filterjobtype != '') {
                $serverjobtype = $this->common_model->getServerid('jobtypes', $filterjobtype);
                $wherequery .= " AND t.jobtype = " . $serverjobtype;
            }
            if ($filterjobcategory != '') {
                $serverjobcategory = $this->common_model->getServerid('categories', $filterjobcategory);
                $wherequery .= " AND t.jobcategory = " . $serverjobcategory;
            }
            if ($filterjobsubcategory != '') {
                $serverjobsubcategory = $this->common_model->getServerid('subcategories', $filterjobsubcategory);
                $wherequery .= " AND t.subcategoryid = " . $serverjobsubcategory;
            }
            if (isset($radiussearch))
                $wherequery .= " AND $radiussearch";
            $data['subcat_id'] = $subcat_id;
            $data['server_address'] = $server_address;
            $data['limitstart'] = $limitstart;
            $data['limit'] = $limit;
            $data['wherequery'] = $wherequery;
            $data['sortby'] = $sortby;
            $data['authkey'] = $this->_client_auth_key;
            $data['siteurl'] = $this->_siteurl;

            $fortask = "getjobsbysubcategory";
            $jsjobsharingobject = new JSJobsModelJob_Sharing;
            $encodedata = json_encode($data);
            $return_server_value = $jsjobsharingobject->serverTask($encodedata, $fortask);
            if (isset($return_server_value['jobsbysubcategory']) AND $return_server_value['jobsbysubcategory'] == -1) { // auth fail 
                $logarray['uid'] = $this->_uid;
                $logarray['referenceid'] = $return_server_value['referenceid'];
                $logarray['eventtype'] = $return_server_value['eventtype'];
                $logarray['message'] = $return_server_value['message'];
                $logarray['event'] = "Jobs By Subcategory";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jsjobsharingobject->write_JobSharingLog($logarray);
                $this->_applications = (object) array();
            } else {
                $parsedata_jobsbysubcategory = array();
                $total = $return_server_value['total'];

                foreach ($return_server_value['jobsbysubcategory'] AS $data) {
                    $parsedata_jobsbysubcategory[] = (object) $data;
                }
                $this->_applications = $parsedata_jobsbysubcategory;
            }
        } else {
            $selectdistance = " ";
            if ($txtfilterlongitude != '' && $txtfilterlatitude != '' && $txtfilterradius != '') {
                $radiussearch = " acos((SIN( PI()* $txtfilterlatitude /180 )*SIN( PI()*job.latitude/180 ))+(cos(PI()* $txtfilterlatitude /180)*COS( PI()*job.latitude/180) *COS(PI()*job.longitude/180-PI()* $txtfilterlongitude /180)))* $radiuslength <= $txtfilterradius";
            }
            $wherequery = '';
            if ($city_filter != '')
                $wherequery .= " AND mjob.cityid= " . $city_filter;

            if ($filterjobtype != '')
                $wherequery .= " AND job.jobtype = " . $filterjobtype;
            if (isset($radiussearch))
                $wherequery .= " AND $radiussearch";


            $query = "SELECT COUNT(DISTINCT job.id) FROM `#__js_job_jobs` AS job
                        JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
                        JOIN `#__js_job_subcategories` AS subcat ON job.subcategoryid = subcat.id
                        LEFT JOIN `#__js_job_jobcities` AS mjob ON job.id = mjob.jobid
                        WHERE job.status = 1  AND subcat.id = " . $subcat_id . "
                        AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate);
            $query .= $wherequery;
            $db->setQuery($query);
            $total = $db->loadResult();

            if ($total <= $limitstart)
                $limitstart = 0;

            if ($total != 0) {
                $query = "SELECT DISTINCT job.*, cat.id as cat_id,cat.cat_title, subcat.title as subcategory, jobtype.title AS jobtype, jobstatus.title AS jobstatus
                            , company.id AS companyid, company.name AS companyname, company.url
                            , salaryfrom.rangestart AS salaryfrom, salaryto.rangeend AS salaryto, salarytype.title AS salaytype
                            ,currency.symbol
                            ,(TO_DAYS( CURDATE() ) - To_days( job.startpublishing ) ) AS jobdays
                            ,CONCAT(subcat.alias,'-',subcat.id) AS aliasid
                            ,CONCAT(job.alias,'-',job.id) AS jobaliasid
                            ,CONCAT(company.alias,'-',company.id) AS companyaliasid

                            FROM `#__js_job_jobs` AS job
                            JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
                            JOIN `#__js_job_subcategories` AS subcat ON job.subcategoryid = subcat.id
                            JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                            JOIN `#__js_job_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id
                            LEFT JOIN `#__js_job_jobcities` AS mjob ON job.id = mjob.jobid
                            LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id
                            LEFT JOIN `#__js_job_salaryrange` AS salaryfrom ON job.salaryrangefrom = salaryfrom.id
                            LEFT JOIN `#__js_job_salaryrange` AS salaryto ON job.salaryrangeto = salaryto.id
                            LEFT JOIN `#__js_job_salaryrangetypes` AS salarytype ON job.salaryrangetype = salarytype.id
                            LEFT JOIN `#__js_job_currencies` AS currency ON currency.id = job.currencyid
                            WHERE job.status = 1  AND subcat.id = " . $subcat_id . "
                            AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate);

                $query .= $wherequery . " ORDER BY  " . $sortby;
            } else {
                $query = "SELECT cat.id as cat_id, cat.cat_title, subcat.title as subcategory
                            FROM `#__js_job_categories` AS cat
                            JOIN `#__js_job_subcategories` AS subcat ON subcat.categoryid = cat.id
                            WHERE subcat.id = " . $subcat_id;
            }
            $db->setQuery($query, $limitstart, $limit);
            $this->_applications = $db->loadObjectList();
            foreach ($this->_applications AS $jobdata) {   // for multicity select 
                if (isset($jobdata->id))
                    $multicitydata = $this->getMultiCityData($jobdata->id);
                if (isset($multicitydata) && $multicitydata != "")
                    $jobdata->city = $multicitydata;
            }
        }
        //for goldjobs
        if ($showgoldjobs == 1) {
            if ($noofgoldjobs != 0) {
                $goldjoblimit = ($limitstart / $limit) * $noofgoldjobs;
                $query = "SELECT job.*, cat.cat_title, jobtype.title AS jobtype, jobstatus.title AS jobstatus
                                , company.id AS companyid, company.name AS companyname, company.url 
                                , company.serverid AS companyserverid,company.alias AS companyalias,job.alias AS jobalias
                                , salaryfrom.rangestart AS salaryfrom, salaryto.rangeend AS salaryto, salarytype.title AS salaytype
                                , country.name AS countryname,city.name AS cityname,state.name AS statename , currency.symbol
                                ,(TO_DAYS( CURDATE() ) - To_days( job.startpublishing ) ) AS jobdays
                                ,CONCAT(job.alias,'-',job.id) AS jobaliasid
                                ,CONCAT(company.alias,'-',companyid) AS companyaliasid

                                FROM `#__js_job_jobs` AS job
                                JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
                                JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                                JOIN `#__js_job_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id
                                LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id 
                                LEFT JOIN `#__js_job_salaryrange` AS salaryfrom ON job.salaryrangefrom = salaryfrom.id
                                LEFT JOIN `#__js_job_salaryrange` AS salaryto ON job.salaryrangeto = salaryto.id
                                LEFT JOIN `#__js_job_salaryrangetypes` AS salarytype ON job.salaryrangetype = salarytype.id
                                LEFT JOIN `#__js_job_countries` AS country ON job.country = country.id 
                                LEFT JOIN `#__js_job_states` AS state ON job.state = state.id 
                                LEFT JOIN `#__js_job_cities` AS city ON job.city = city.id 
                                LEFT JOIN `#__js_job_currencies` AS currency ON job.currencyid = currency.id 
                                WHERE job.status = 1 AND job.isgoldjob = 1
                                AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate);

                $db->setQuery($query, $goldjoblimit, $noofgoldjobs);
                $goldjobs = $db->loadObjectList();
                foreach ($goldjobs AS $goldjobdata) {   // for multicity select 
                    $multicitydata = $this->getMultiCityData($goldjobdata->id);
                    if ($multicitydata != "")
                        $goldjobdata->city = $multicitydata;
                    if ($this->_client_auth_key != "") {
                        $goldjobdata->id = $goldjobdata->serverid;
                        $goldjobdata->jobaliasid = $goldjobdata->jobalias . '-' . $goldjobdata->id;
                        $goldjobdata->companyid = $goldjobdata->companyserverid;
                        $goldjobdata->companyaliasid = $goldjobdata->companyalias . '-' . $goldjobdata->companyid;
                    }
                }
            }
        }
        else
            $goldjobs = array();

        //for featuredjob
        if ($showfeaturedjobs == 1) {
            if ($nooffeaturedjobs != 0) {
                $featuredjoblimit = ($limitstart / $limit) * $nooffeaturedjobs;
                $query = "SELECT job.*, cat.cat_title, jobtype.title AS jobtype, jobstatus.title AS jobstatus
                                , company.id AS companyid, company.name AS companyname, company.url 
                                , company.serverid AS companyserverid,company.alias AS companyalias,job.alias AS jobalias
                                , salaryfrom.rangestart AS salaryfrom, salaryto.rangeend AS salaryto, salarytype.title AS salaytype
                                , country.name AS countryname,city.name AS cityname,state.name AS statename , currency.symbol
                                ,(TO_DAYS( CURDATE() ) - To_days( job.startpublishing ) ) AS jobdays
                                ,CONCAT(job.alias,'-',job.id) AS jobaliasid
                                ,CONCAT(company.alias,'-',companyid) AS companyaliasid

                                FROM `#__js_job_jobs` AS job
                                JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
                                JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                                JOIN `#__js_job_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id
                                LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id 
                                LEFT JOIN `#__js_job_salaryrange` AS salaryfrom ON job.salaryrangefrom = salaryfrom.id
                                LEFT JOIN `#__js_job_salaryrange` AS salaryto ON job.salaryrangeto = salaryto.id
                                LEFT JOIN `#__js_job_salaryrangetypes` AS salarytype ON job.salaryrangetype = salarytype.id
                                LEFT JOIN `#__js_job_countries` AS country ON job.country = country.id 
                                LEFT JOIN `#__js_job_states` AS state ON job.state = state.id 
                                LEFT JOIN `#__js_job_cities` AS city ON job.city = city.id
                                LEFT JOIN `#__js_job_currencies` AS currency ON job.currencyid = currency.id 
                                WHERE job.status = 1 AND job.isfeaturedjob = 1
                                AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate);

                $db->setQuery($query, $featuredjoblimit, $nooffeaturedjobs);
                $featuredjobs = $db->loadObjectList();
                foreach ($featuredjobs AS $featuredjobsdata) {   // for multicity select 
                    $multicitydata = $this->getMultiCityData($featuredjobsdata->id);
                    if ($multicitydata != "")
                        $featuredjobsdata->city = $multicitydata;
                    if ($this->_client_auth_key != "") {
                        $featuredjobsdata->id = $featuredjobsdata->serverid;
                        $featuredjobsdata->jobaliasid = $featuredjobsdata->jobalias . '-' . $featuredjobsdata->id;
                        $featuredjobsdata->companyid = $featuredjobsdata->companyserverid;
                        $featuredjobsdata->companyaliasid = $featuredjobsdata->companyalias . '-' . $featuredjobsdata->companyid;
                    }
                }
            }
        }
        else
            $featuredjobs = array();







        $jobtype = $this->common_model->getJobType(JText::_('JS_SELECT_JOB_TYPE'));
        $jobstatus = $this->common_model->getJobStatus(JText::_('JS_SELECT_JOB_STATUS'));

        if (isset($this->_applications[0]->jobcategory)) {
            if ($this->_client_auth_key != '') {
                $categoryid = $this->common_model->getClientId('categories', $this->_applications[0]->jobcategory);
            }
            else
                $categoryid = $this->_applications[0]->jobcategory;
        }
        else
            $categoryid = $filterjobcategory;


        if ($subcat_id == 0 || $subcat_id == '')
            $subcat_id = 1;
        else {
            if ($this->_client_auth_key != '') {
                $ssubcatid = $this->common_model->getClientId('subcategories', $subcat_id);
                $subcat_id = $ssubcatid;
            }
            else
                $subcat_id = $subcat_id;
        }

        $job_categories = $this->common_model->getCategories(JText::_('JS_SELECT_CATEGORY'));
        $job_subcategories = $this->common_model->getSubCategoriesforCombo($categoryid, JText::_('JS_SELECT_CATEGORY'), $value = '');
        $countries = $this->common_model->getSharingCountries(JText::_('JS_SELECT_COUNTRY'));

        $filterlists['country'] = JHTML::_('select.genericList', $countries, 'cmbfilter_country', 'class="inputbox"  style="width:' . $address_fields_width . 'px;" ' . '', 'value', 'text', $cmbfiltercountry);
        $filterlists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'filter_jobcategory', 'class="inputbox" ' . 'onChange=fj_getsubcategories(\'td_jobsubcategory\',this.value);', 'value', 'text', $categoryid);
        $filterlists['jobsubcategory'] = JHTML::_('select.genericList', $job_subcategories, 'filter_jobsubcategory', 'class="inputbox" ' . '', 'value', 'text', $subcat_id);
        $filterlists['jobtype'] = JHTML::_('select.genericList', $jobtype, 'filter_jobtype', 'class="inputbox" ' . '', 'value', 'text', $filterjobtype);

        $location = $this->common_model->getAddressDataByCityName('', $city_filter);
        if (isset($location[0]->name))
            $filtervalues['location'] = $location[0]->name;
        else
            $filtervalues['location'] = "";

        $filtervalues['city'] = $city_filter;
        $filtervalues['radius'] = $txtfilterradius;
        $filtervalues['longitude'] = $txtfilterlongitude;
        $filtervalues['latitude'] = $txtfilterlatitude;

        $packageexpiry = $this->getJobSeekerPackageExpiry($uid);
        if ($packageexpiry == 1) { //package expire or user not login
            $listjobconfigs = array();
            $listjobconfigs['lj_title'] = $listjobconfig['visitor_lj_title'];
            $listjobconfigs['lj_category'] = $listjobconfig['visitor_lj_category'];
            $listjobconfigs['lj_jobtype'] = $listjobconfig['visitor_lj_jobtype'];
            $listjobconfigs['lj_jobstatus'] = $listjobconfig['visitor_lj_jobstatus'];
            $listjobconfigs['lj_company'] = $listjobconfig['visitor_lj_company'];
            $listjobconfigs['lj_companysite'] = $listjobconfig['visitor_lj_companysite'];
            $listjobconfigs['lj_country'] = $listjobconfig['visitor_lj_country'];
            $listjobconfigs['lj_state'] = $listjobconfig['visitor_lj_state'];
            $listjobconfigs['lj_city'] = $listjobconfig['visitor_lj_city'];
            $listjobconfigs['lj_salary'] = $listjobconfig['visitor_lj_salary'];
            $listjobconfigs['lj_created'] = $listjobconfig['visitor_lj_created'];
            $listjobconfigs['lj_noofjobs'] = $listjobconfig['visitor_lj_noofjobs'];
            $listjobconfigs['subcategories'] = $listjobconfig['subcategories'];
            $listjobconfigs['subcategories_all'] = $listjobconfig['subcategories_all'];
            $listjobconfigs['subcategories_colsperrow'] = $listjobconfig['subcategories_colsperrow'];
            $listjobconfigs['subcategoeis_max_hight'] = $listjobconfig['subcategoeis_max_hight'];
            $listjobconfigs['lj_description'] = $listjobconfig['visitor_lj_description'];
            $listjobconfigs['lj_shortdescriptionlenght'] = $listjobconfig['lj_shortdescriptionlenght'];
            $listjobconfigs['lj_joblistingstyle'] = $listjobconfig['lj_joblistingstyle'];
        }
        else
            $listjobconfigs = $listjobconfig; // user

        $result[0] = $this->_applications;
        $result[1] = $total;
        $result[2] = $filterlists;
        $result[3] = $filtervalues;
        $result[4] = $listjobconfigs;
        $result[5] = $goldjobs;
        $result[6] = $featuredjobs;

        return $result;
    }

    function &getActiveJobsByCompany($uid, $companyid, $city_filter, $cmbfiltercountry, $filterjobcategory, $filterjobsubcategory, $filterjobtype, $sortby
    , $txtfilterlongitude, $txtfilterlatitude, $txtfilterradius, $cmbfilterradiustype
    , $limit, $limitstart) {
        $db = &$this->getDBO();
        $result = array();
        if (is_numeric($companyid) == false)
            return false;
        if ($filterjobcategory != '')
            if (is_numeric($filterjobcategory) == false)
                return false;
        if ($filterjobtype != '')
            if (is_numeric($filterjobtype) == false)
                return false;

        if (!isset($this->_config)) {
            $this->_config = $this->common_model->getConfig('');
        }
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'filter_address_fields_width')
                $address_fields_width = $conf->configvalue;
        }
        $listjobconfig = $this->common_model->getConfigByFor('listjob');
        //for radius search
        switch ($cmbfilterradiustype) {
            case "m":$radiuslength = 6378137;
                break;
            case "km":$radiuslength = 6378.137;
                break;
            case "mile":$radiuslength = 3963.191;
                break;
            case "nacmiles":$radiuslength = 3441.596;
                break;
        }
        if ($this->_client_auth_key != "") {

            $selectdistance = " ";
            if ($txtfilterlongitude != '' && $txtfilterlatitude != '' && $txtfilterradius != '') {
                $radiussearch = " acos((SIN( PI()* $txtfilterlatitude /180 )*SIN( PI()*t.latitude/180 ))+(cos(PI()* $txtfilterlatitude /180)*COS( PI()*t.latitude/180) *COS(PI()*t.longitude/180-PI()* $txtfilterlongitude /180)))* $radiuslength <= $txtfilterradius";
            }

            $wherequery = '';
            $server_address = array();
            if ($city_filter != '') {
                $server_citiy_id = $this->common_model->getServerid('cities', $city_filter);
                $server_address['multicityid'] = $server_citiy_id;
                $server_country_id = $this->common_model->getSeverCountryid($city_filter);
                if ($server_country_id == false)
                    $cmbfiltercountry = '';
                else
                    $cmbfiltercountry = $server_country_id;
            }else {
                $default_sharing_loc = $this->getDefaultSharingLocation($server_address, $cmbfiltercountry);
                if (isset($default_sharing_loc['defaultsharingcity']) AND ($default_sharing_loc['defaultsharingcity'] != '')) {
                    $city_filter = $default_sharing_loc['defaultsharingcity'];
                    $server_address['multicityid'] = $default_sharing_loc['defaultsharingcity'];
                } elseif (isset($default_sharing_loc['defaultsharingstate']) AND ($default_sharing_loc['defaultsharingstate'] != '')) {
                    $server_address['defaultsharingstate'] = $default_sharing_loc['defaultsharingstate'];
                } elseif (isset($default_sharing_loc['filtersharingcountry']) AND ($default_sharing_loc['filtersharingcountry'] != '')) {
                    $server_address['filtersharingcountry'] = $default_sharing_loc['filtersharingcountry'];
                    $cmbfiltercountry = $default_sharing_loc['filtersharingcountry'];
                } elseif (isset($default_sharing_loc['defaultsharingcountry']) AND ($default_sharing_loc['defaultsharingcountry'] != '')) {
                    $server_address['defaultsharingcountry'] = $default_sharing_loc['defaultsharingcountry'];
                    $cmbfiltercountry = $default_sharing_loc['defaultsharingcountry'];
                }
            }

            if ($filterjobtype != '') {
                $serverjobtype = $this->common_model->getServerid('jobtypes', $filterjobtype);
                $wherequery .= " AND t.jobtype = " . $serverjobtype;
            }
            if ($filterjobcategory != '') {
                $serverjobcategory = $this->common_model->getServerid('categories', $filterjobcategory);
                $wherequery .= " AND t.jobcategory = " . $serverjobcategory;
            }
            if ($filterjobsubcategory != '') {
                $serverjobsubcategory = $this->common_model->getServerid('subcategories', $filterjobsubcategory);
                $wherequery .= " AND t.subcategoryid = " . $serverjobsubcategory;
            }
            if (isset($radiussearch))
                $wherequery .= " AND $radiussearch";

            $fortask = "getactivejobsbycompany";
            $jsjobsharingobject = new JSJobsModelJob_Sharing;
            $data['sortby'] = $sortby;
            $data['companyid'] = $companyid;
            $data['limitstart'] = $limitstart;
            $data['limit'] = $limit;
            $data['wherequery'] = $wherequery;
            $data['server_address'] = $server_address;
            $data['authkey'] = $this->_client_auth_key;
            $data['siteurl'] = $this->_siteurl;
            $encodedata = json_encode($data);
            $return_server_value = $jsjobsharingobject->serverTask($encodedata, $fortask);
            if (isset($return_server_value['activejobsbycompany']) AND $return_server_value['activejobsbycompany'] == -1) { // auth fail 
                $logarray['uid'] = $this->_uid;
                $logarray['referenceid'] = $return_server_value['referenceid'];
                $logarray['eventtype'] = $return_server_value['eventtype'];
                $logarray['message'] = $return_server_value['message'];
                $logarray['event'] = "Jobs By Company";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jsjobsharingobject->write_JobSharingLog($logarray);
                $this->_applications = (object) array();
                $total = 0;
            } else {
                $parsedata = array();
                foreach ($return_server_value['jobsbycompany'] AS $data) {
                    $parsedata[] = (object) $data;
                }
                $this->_applications = $parsedata;
                $total = $return_server_value['total'];
            }
        } else {
            $selectdistance = " ";
            if ($txtfilterlongitude != '' && $txtfilterlatitude != '' && $txtfilterradius != '') {
                $radiussearch = " acos((SIN( PI()* $txtfilterlatitude /180 )*SIN( PI()*job.latitude/180 ))+(cos(PI()* $txtfilterlatitude /180)*COS( PI()*job.latitude/180) *COS(PI()*job.longitude/180-PI()* $txtfilterlongitude /180)))* $radiuslength <= $txtfilterradius";
            }
            $wherequery = '';
            if ($city_filter != '')
                $wherequery .= " AND mcity.cityid = " . $city_filter;
            if ($filterjobcategory != '')
                $wherequery .= " AND job.jobcategory = " . $filterjobcategory;
            if ($filterjobsubcategory != '')
                $wherequery .= " AND job.subcategoryid = " . $filterjobsubcategory;
            if ($filterjobtype != '')
                $wherequery .= " AND job.jobtype = " . $filterjobtype;
            if (isset($radiussearch))
                $wherequery .= " AND $radiussearch";

            $curdate = date('Y-m-d H:i:s');



            $query = "SELECT COUNT(job.id) FROM `#__js_job_jobs` AS job
                        JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
                        LEFT JOIN `#__js_job_jobcities` AS mcity ON job.id = mcity.jobid
                        WHERE job.jobcategory  = cat.id AND job.status = 1  AND job.companyid = " . $companyid . " 
                        AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate);
            $query .= $wherequery;
            $db->setQuery($query);
            $total = $db->loadResult();

            if ($total <= $limitstart)
                $limitstart = 0;
            $query = "SELECT DISTINCT job.*, cat.cat_title, company.name AS companyname, company.url, jobtype.title AS jobtype, jobstatus.title AS jobstatus
                        , salary.rangestart, salary.rangeend, salary.rangeend AS salaryto				
                        ,(TO_DAYS( CURDATE() ) - To_days( job.startpublishing ) ) AS jobdays
                        ,CONCAT(company.alias,'-',company.id) AS aliasid
                        ,CONCAT(job.alias,'-',job.id) AS jobaliasid
                        ,cur.symbol
                        FROM `#__js_job_jobs` AS job
                        JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
                        JOIN `#__js_job_companies` AS company ON job.companyid = company.id
                        JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                        JOIN `#__js_job_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id
                        LEFT JOIN `#__js_job_jobcities` AS mcity ON job.id = mcity.jobid
                        LEFT JOIN `#__js_job_salaryrange` AS salary ON job.jobsalaryrange = salary.id 
                        LEFT JOIN `#__js_job_currencies` AS cur ON cur.id = job.currencyid 
                        WHERE job.jobcategory = cat.id AND job.status = 1  AND job.companyid = " . $companyid . " 
                        AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate);

            $query .= $wherequery . " ORDER BY  " . $sortby;
            $db->setQuery($query, $limitstart, $limit);
            $this->_applications = $db->loadObjectList();
            foreach ($this->_applications AS $jobdata) {   // for multicity select 
                $multicitydata = $this->getMultiCityData($jobdata->id);
                if ($multicitydata != "")
                    $jobdata->city = $multicitydata;
            }
        }

        $jobtype = $this->common_model->getJobType(JText::_('JS_SELECT_JOB_TYPE'));
        $jobstatus = $this->common_model->getJobStatus(JText::_('JS_SELECT_JOB_STATUS'));

        $job_categories = $this->common_model->getCategories(JText::_('JS_SELECT_CATEGORY'));
        if ($filterjobcategory == '')
            $categoryid = 1;
        else
            $categoryid = $filterjobcategory;
        $job_subcategories = $this->common_model->getSubCategoriesforCombo($categoryid, JText::_('JS_SELECT_CATEGORY'), $value = '');
        $countries = $this->common_model->getSharingCountries(JText::_('JS_SELECT_COUNTRY'));

        $filterlists['country'] = JHTML::_('select.genericList', $countries, 'cmbfilter_country', 'class="inputbox"  style="width:' . $address_fields_width . 'px;" ' . '', 'value', 'text', $cmbfiltercountry);
        $filterlists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'filter_jobcategory', 'class="inputbox" ' . 'onChange=fj_getsubcategories(\'td_jobsubcategory\',this.value);', 'value', 'text', $filterjobcategory);
        $filterlists['jobsubcategory'] = JHTML::_('select.genericList', $job_subcategories, 'filter_jobsubcategory', 'class="inputbox" ' . '', 'value', 'text', $filterjobsubcategory);
        $filterlists['jobtype'] = JHTML::_('select.genericList', $jobtype, 'filter_jobtype', 'class="inputbox" ' . '', 'value', 'text', $filterjobtype);

        $location = $this->common_model->getAddressDataByCityName('', $city_filter);
        if (isset($location[0]->name))
            $filtervalues['location'] = $location[0]->name;
        else
            $filtervalues['location'] = "";


        $filtervalues['city'] = $city_filter;
        $filtervalues['radius'] = $txtfilterradius;
        $filtervalues['longitude'] = $txtfilterlongitude;
        $filtervalues['latitude'] = $txtfilterlatitude;

        $packageexpiry = $this->getJobSeekerPackageExpiry($uid);
        if ($packageexpiry == 1) { //package expire or user not login
            $listjobconfigs = array();
            $listjobconfigs['lj_title'] = $listjobconfig['visitor_lj_title'];
            $listjobconfigs['lj_category'] = $listjobconfig['visitor_lj_category'];
            $listjobconfigs['lj_jobtype'] = $listjobconfig['visitor_lj_jobtype'];
            $listjobconfigs['lj_jobstatus'] = $listjobconfig['visitor_lj_jobstatus'];
            $listjobconfigs['lj_company'] = $listjobconfig['visitor_lj_company'];
            $listjobconfigs['lj_companysite'] = $listjobconfig['visitor_lj_companysite'];
            $listjobconfigs['lj_country'] = $listjobconfig['visitor_lj_country'];
            $listjobconfigs['lj_state'] = $listjobconfig['visitor_lj_state'];
            $listjobconfigs['lj_county'] = $listjobconfig['visitor_lj_county'];
            $listjobconfigs['lj_city'] = $listjobconfig['visitor_lj_city'];
            $listjobconfigs['lj_salary'] = $listjobconfig['visitor_lj_salary'];
            $listjobconfigs['lj_created'] = $listjobconfig['visitor_lj_created'];
            $listjobconfigs['lj_noofjobs'] = $listjobconfig['visitor_lj_noofjobs'];
        }
        else
            $listjobconfigs = $listjobconfig; // user

        $result[0] = $this->_applications;
        $result[1] = $total;
        $result[2] = $filterlists;
        $result[3] = $filtervalues;
        $result[4] = $listjobconfigs;

        return $result;
    }

    function getJobsFromServerFilter($variables){
        $db = JFactory::getDbo();
        $selectdistance = " ";
        if ($variables['txtfilterlongitude'] != '' && $variables['txtfilterlatitude'] != '' && $variables['txtfilterradius'] != '') {
            $radiussearch = " acos((SIN( PI()* ".$variables['txtfilterlatitude']." /180 )*SIN( PI()*t.latitude/180 ))+(cos(PI()* ".$variables['txtfilterlatitude']." /180)*COS( PI()*t.latitude/180) *COS(PI()*t.longitude/180-PI()* ".$variables['txtfilterlongitude']." /180)))* ".$variables['radiuslength']." <= ".$variables['txtfilterradius'];
        }

        $wherequery = '';
        $server_address = array();
        if ($variables['city_filter'] != '') {
            $server_citiy_id = $this->common_model->getServerid('cities', $variables['city_filter']);
            $server_address['multicityid'] = $server_citiy_id;
            $server_country_id = $this->common_model->getSeverCountryid($variables['city_filter']);
            if ($server_country_id == false)
                $cmbfiltercountry = '';
            else
                $cmbfiltercountry = $server_country_id;
        }elseif ($variables['jobstate'] != '') { // calling from module & plugin
            $server_address['defaultsharingstate'] = $this->common_model->getServerid('states', $variables['jobstate']);
        } elseif ($variables['jobcountry'] != '') { // calling from module & plugin
            $server_address['filtersharingcountry'] = $this->common_model->getServerid('countries', $variables['jobcountry']);
            $cmbfiltercountry = $server_address['filtersharingcountry'];
        } else {
            $default_sharing_loc = $this->getDefaultSharingLocation($server_address, $cmbfiltercountry);
            if (isset($default_sharing_loc['defaultsharingcity']) AND ($default_sharing_loc['defaultsharingcity'] != '')) {
                $variables['city_filter'] = $default_sharing_loc['defaultsharingcity'];
                $server_address['multicityid'] = $default_sharing_loc['defaultsharingcity'];
                $server_country_id = $this->common_model->getSeverDefaultCountryid($variables['city_filter']);
                if ($server_country_id == false)
                    $cmbfiltercountry = '';
                else
                    $cmbfiltercountry = $server_country_id;
            } elseif (isset($default_sharing_loc['defaultsharingstate']) AND ($default_sharing_loc['defaultsharingstate'] != '')) {
                $server_address['defaultsharingstate'] = $default_sharing_loc['defaultsharingstate'];
            } elseif (isset($default_sharing_loc['filtersharingcountry']) AND ($default_sharing_loc['filtersharingcountry'] != '')) {
                $server_address['filtersharingcountry'] = $default_sharing_loc['filtersharingcountry'];
                $cmbfiltercountry = $default_sharing_loc['filtersharingcountry'];
            } elseif (isset($default_sharing_loc['defaultsharingcountry']) AND ($default_sharing_loc['defaultsharingcountry'] != '')) {
                $server_address['defaultsharingcountry'] = $default_sharing_loc['defaultsharingcountry'];
                $cmbfiltercountry = $default_sharing_loc['defaultsharingcountry'];
            }
        }

        if ($variables['filterjobtype'] != '') {
            $serverjobtype = $this->common_model->getServerid('jobtypes', $variables['filterjobtype']);
            $wherequery .= " AND t.jobtype = " . $serverjobtype;
        }
        if ($variables['filterjobcategory'] != '') {
            $serverjobcategory = $this->common_model->getServerid('categories', $variables['filterjobcategory']);
            $wherequery .= " AND t.jobcategory = " . $serverjobcategory;
        }
        if ($variables['filterjobsubcategory'] != '') {
            $serverjobsubcategory = $this->common_model->getServerid('subcategories', $variables['filterjobsubcategory']);
            $wherequery .= " AND t.subcategoryid = " . $serverjobsubcategory;
        }
        if (isset($radiussearch))
            $wherequery .= " AND $radiussearch";

        $fortask = "listjobs";
        $jsjobsharingobject = new JSJobsModelJob_Sharing;
        $data['limitstart'] = $variables['limitstart'];
        $data['limit'] = $variables['limit'];
        $data['server_address'] = $server_address;
        $data['wherequery'] = $wherequery;
        $data['authkey'] = $this->_client_auth_key;
        $data['siteurl'] = $this->_siteurl;
        $encodedata = json_encode($data);
        $return_server_value = $jsjobsharingobject->serverTask($encodedata, $fortask);
        if (isset($return_server_value['listnewestjobs']) AND $return_server_value['listnewestjobs'] == -1) { // auth fail 
            $logarray['uid'] = $this->_uid;
            $logarray['referenceid'] = $return_server_value['referenceid'];
            $logarray['eventtype'] = $return_server_value['eventtype'];
            $logarray['message'] = $return_server_value['message'];
            $logarray['event'] = "List Newest Jobs";
            $logarray['messagetype'] = "Error";
            $logarray['datetime'] = date('Y-m-d H:i:s');
            $jsjobsharingobject->write_JobSharingLog($logarray);
            // Authentication Failed get local Jobs
            $this->_applications = array();
            $total = 0;
        } else {
            $parsedata = array();
            foreach ($return_server_value['newestjobs'] AS $data) {
                $parsedata[] = (object) $data;
            }
            $total = $return_server_value['total'];
        }
        
        $return['jobs'] = $parsedata;
        $return['total'] = $total;
        
        return $return;
    }
    
    function getJobsFromServerAndFill($variables) {
        $db = JFactory::getDbo();
        $JConfig = new JConfig();
        $db_prefix = $JConfig->dbprefix;

        $query = "SELECT jobtemptime.* FROM `#__js_job_jobs_temp_time` AS jobtemptime";
        $db->setQuery($query);
        $time_data = $db->loadObject();
        if (empty($time_data)) {
            $lastcalltime = date("Y-m-d H:i:s");
            $expiretime = date("Y-m-d H:i:s", strtotime("+5 min"));
            $insert_time_query = 'INSERT INTO `#__js_job_jobs_temp_time` (lastcalltime,expiretime,is_request)
                                    VALUES(' . $db->quote($lastcalltime) . ',' . $db->quote($expiretime) . ',0)';
            $db->setQuery($insert_time_query);
            $db->query();

            $temp_job = $this->getTable('jobtemp');
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
                    // Authentication Failed get local Jobs
                    $return = $this->getLocalJobs($variables);
                    return $return;
                } else {
                    $session->set('totalserverjobs', $return_server_value['total']);
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
                }
            }
            // Get data from local table
            $return = $this->getDataFromLocalServer();
            return $return;
        } else {

            $lastcalltime = $time_data->lastcalltime;
            $expiretime = $time_data->expiretime;
        }
        $now_stamp = date("Y-m-d H:i:s");
        if ($now_stamp > $expiretime) {

            $update_request = "UPDATE #__js_job_jobs_temp_time as time1 ,(select max(id) AS id from #__js_job_jobs_temp_time ) time2 set is_request =is_request+1  where time1.id = time2.id";
            $db->setQuery($update_request);
            $db->query();
            $query1 = "SELECT jobtemptime.* FROM `#__js_job_jobs_temp_time` AS jobtemptime";
            $db->setQuery($query1);
            $time_data1 = $db->loadObject();
            if ($time_data1->is_request > 1) {
                // Get data from local table
                $return = $this->getDataFromLocalServer();
                return $return;
            }

            $temp_job = $this->getTable('jobtemp');
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
                    $update_request1 = "UPDATE #__js_job_jobs_temp_time as time1 ,(select max(id) AS id from #__js_job_jobs_temp_time ) time2 set is_request =0  where time1.id = time2.id";
                    $db->setQuery($update_request1);
                    $db->query();
                    // Authentication Failed get local Jobs
                    $return = $this->getLocalJobs($variables);
                    return $return;
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
                        if ($job_temp_in_use != 2){
                            $return = $this->getJobsFromServerFilter($variables);
                            return $return;
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
                    // Get data from local Server
                    $return = $this->getDataFromLocalServer();
                    return $return;
                }
            }
        } else {
            // Get data from local Server
            $return = $this->getDataFromLocalServer(0);
            return $return;
        }
    }
    function getDataFromLocalServer($updateTime = 1) {
        $db = JFactory::getDbo();
        if($updateTime == 1){
            $update_request1 = "UPDATE #__js_job_jobs_temp_time as time1 ,(select max(id) AS id from #__js_job_jobs_temp_time ) time2 set is_request =0  where time1.id = time2.id";
            $db->setQuery($update_request1);
            $db->query();
        }
        $query = "SELECT jobtemp.* FROM `#__js_job_jobs_temp` AS jobtemp ORDER BY  jobtemp.id DESC";
        $db->setQuery($query, $limitstart, $limit);
        $jobs = $db->loadObjectList();
        $total = $session->get('totalserverjobs');
        $return['jobs'] = $jobs;
        $return['total'] = $total;
        return $return;
    }
    function getLocalJobs($variables) {
        $db = JFactory::getDbo();
        $selectdistance = " ";
        if ($variables['txtfilterlongitude'] != '' && $variables['txtfilterlatitude'] != '' && $variables['txtfilterradius'] != '') {
            $radiussearch = " acos((SIN( PI()* ".$variables['txtfilterlatitude']." /180 )*SIN( PI()*job.latitude/180 ))+(cos(PI()* ".$variables['txtfilterlatitude']." /180)*COS( PI()*job.latitude/180) *COS(PI()*job.longitude/180-PI()* ".$variables['txtfilterlongitude']." /180)))* ".$variables['radiuslength']." <= ".$variables['txtfilterradius'];
        }

        $wherequery = '';

        if ($variables['filterjobtype'] != '')
            $wherequery .= " AND job.jobtype = " . $variables['filterjobtype'];
        if ($variables['filterjobcategory'] != '')
            $wherequery .= " AND job.jobcategory = " . $variables['filterjobcategory'];
        if ($variables['filterjobsubcategory'] != '')
            $wherequery .= " AND job.subcategoryid = " . $variables['filterjobsubcategory'];
        if ($variables['city_filter'] != '')
            $wherequery .= " AND mcity.cityid = " . $variables['city_filter'];
        if ($variables['jobcountry']) {
            $wherequery.=" AND city.countryid=" . $variables['jobcountry'];
        }
        if ($variables['jobstate']) {
            $wherequery.=" AND city.stateid=" . $variables['jobstate'];
        }
        if (isset($radiussearch))
            $wherequery .= " AND $radiussearch";

        $curdate = date('Y-m-d H:i:s');
        $query = "SELECT COUNT(DISTINCT job.id) FROM `#__js_job_jobs` AS job
                        LEFT JOIN `#__js_job_jobcities` AS mcity ON job.id = mcity.jobid
                        LEFT JOIN `#__js_job_cities` AS city ON city.id = mcity.cityid 
                        WHERE job.status = 1
                        AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate);
        $query .= $wherequery;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $variables['limitstart'])
            $variables['limitstart'] = 0;

        $query = "SELECT DISTINCT job.*, cat.cat_title, jobtype.title AS jobtype, jobstatus.title AS jobstatus
                        , company.id AS companyid, company.name AS companyname, company.url 
                        , salaryfrom.rangestart AS salaryfrom, salaryto.rangeend AS salaryto, salarytype.title AS salaytype
                        , currency.symbol
                        ,(TO_DAYS( CURDATE() ) - To_days( job.startpublishing ) ) AS jobdays
                        ,CONCAT(job.alias,'-',job.id) AS aliasid
                        ,CONCAT(company.alias,'-',company.id) AS companyaliasid
                        FROM `#__js_job_jobs` AS job
                        JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
                        JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                        JOIN `#__js_job_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id
                        LEFT JOIN `#__js_job_jobcities` AS mcity ON job.id = mcity.jobid
                        LEFT JOIN `#__js_job_cities` AS city ON city.id = mcity.cityid
                        LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id 
                        LEFT JOIN `#__js_job_salaryrange` AS salaryfrom ON job.salaryrangefrom = salaryfrom.id
                        LEFT JOIN `#__js_job_salaryrange` AS salaryto ON job.salaryrangeto = salaryto.id
                        LEFT JOIN `#__js_job_salaryrangetypes` AS salarytype ON job.salaryrangetype = salarytype.id
                        LEFT JOIN `#__js_job_currencies` AS currency ON job.currencyid = currency.id 
                        WHERE job.status = 1  
                        AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate);

        $query .= $wherequery . " ORDER BY  job.startpublishing DESC";
        //echo $query;
        $db->setQuery($query, $variables['limitstart'], $variables['limit']);
        $this->_applications = $db->loadObjectList();
        foreach ($this->_applications AS $jobdata) {   // for multicity select 
            $multicitydata = $this->getMultiCityData($jobdata->id);
            if ($multicitydata != "")
                $jobdata->city = $multicitydata;
        }
        $data['jobs'] = $this->_applications;
        $data['total'] = $total;
        return $data;
    }

    function getListNewestJobs($uid, $city_filter, $cmbfiltercountry, $filterjobcategory, $filterjobsubcategory, $filterjobtype, $txtfilterlongitude, $txtfilterlatitude, $txtfilterradius, $cmbfilterradiustype, $jobcountry, $jobstate, $limit, $limitstart) {
        if ($uid)
            if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
                return false;
        if ($filterjobtype != '')
            if (is_numeric($filterjobtype) == false)
                return false;
        if ($filterjobcategory != '')
            if (is_numeric($filterjobcategory) == false)
                return false;
        $db = $this->getDBO();
        $result = array();
        if (!isset($this->_config)) {
            $this->_config = $this->common_model->getConfig('');
        }
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'filter_address_fields_width')
                $address_fields_width = $conf->configvalue;
            if ($conf->configname == 'filter_cat_jobtype_fields_width')
                $cat_jobtype_fields_width = $conf->configvalue;
            if ($conf->configname == 'defaultcountry')
                $defaultcountry = $conf->configvalue;
            if ($conf->configname == 'hidecountry')
                $hidecountry = $conf->configvalue;
            if ($conf->configname == 'noofgoldjobsinlisting')
                $noofgoldjobs = $conf->configvalue;
            if ($conf->configname == 'nooffeaturedjobsinlisting')
                $nooffeaturedjobs = $conf->configvalue;
            if ($conf->configname == 'showgoldjobsinnewestjobs')
                $showgoldjobs = $conf->configvalue;
            if ($conf->configname == 'showfeaturedjobsinnewestjobs')
                $showfeaturedjobs = $conf->configvalue;
        }
        $radiuslength = '';
        switch ($cmbfilterradiustype) {
            case "m":$radiuslength = 6378137;
                break;
            case "km":$radiuslength = 6378.137;
                break;
            case "mile":$radiuslength = 3963.191;
                break;
            case "nacmiles":$radiuslength = 3441.596;
                break;
        }
        $curdate = date('Y-m-d H:i:s');
        $variables['txtfilterlongitude'] = $txtfilterlongitude;
        $variables['txtfilterlatitude'] = $txtfilterlatitude;
        $variables['txtfilterradius'] = $txtfilterradius;
        $variables['radiuslength'] = $radiuslength;
        $variables['city_filter'] = $city_filter;
        $variables['jobstate'] = $jobstate;
        $variables['jobcountry'] = $jobcountry;
        $variables['filterjobtype'] = $filterjobtype;
        $variables['filterjobcategory'] = $filterjobcategory;
        $variables['filterjobsubcategory'] = $filterjobsubcategory;
        $variables['limitstart'] = $limitstart;
        $variables['limit'] = $limit;

        if ($this->_client_auth_key == "") {
            $return = $this->getLocalJobs($variables);
            $this->_applications = $return['jobs'];
            $total = $return['total'];
        }else { // job sharing listing 
            $session = JFactory::getSession();
            if ($_POST) {
                $checkfilterdefaultvalue = 0;
                if (isset($_POST['filter_jobcategory']) AND $_POST['filter_jobcategory'] != "")
                    $checkfilterdefaultvalue = 1;
                if (isset($_POST['filter_jobsubcategory']) AND $_POST['filter_jobsubcategory'] != "")
                    $checkfilterdefaultvalue = 1;
                if (isset($_POST['filter_jobtype']) AND $_POST['filter_jobtype'] != "")
                    $checkfilterdefaultvalue = 1;
                if (isset($_POST['filter_longitude']) AND $_POST['filter_longitude'] != "")
                    $checkfilterdefaultvalue = 1;
                if (isset($_POST['filter_latitude']) AND $_POST['filter_latitude'] != "")
                    $checkfilterdefaultvalue = 1;
                if (isset($_POST['filter_radius']) AND $_POST['filter_radius'] != "")
                    $checkfilterdefaultvalue = 1;
                if (isset($_POST['cmbfilter_country']) AND $_POST['cmbfilter_country'] != "")
                    $checkfilterdefaultvalue = 1;
                if (isset($_POST['txtfilter_city']) AND $_POST['txtfilter_city'] != "")
                    $checkfilterdefaultvalue = 1;
                $postfilter = ($checkfilterdefaultvalue == 0 ? "" : 1);
            }
            else
                $postfilter = "";
            
            if ((empty($postfilter)) AND ($city_filter == "") AND ($cmbfiltercountry == "") AND ($filterjobcategory == "") AND ($filterjobsubcategory == "") AND ($filterjobtype == "") AND ($txtfilterlongitude == "") AND ($txtfilterlatitude == "") AND ($txtfilterradius == "") AND ($jobcountry == "") AND ($jobstate == "")) {   // filter is null 
                if ($limitstart < 100) { // within 100
                    $default_sharing_loc = $this->getDefaultSharingLocation('', '');
                    if (isset($default_sharing_loc['defaultsharingcity']) AND ($default_sharing_loc['defaultsharingcity'] != '')) {
                        $variables['city_filter'] = $default_sharing_loc['defaultsharingcity'];
                    } elseif (isset($default_sharing_loc['defaultsharingstate']) AND ($default_sharing_loc['defaultsharingstate'] != '')) {
                        $variables['jobstate'] = $default_sharing_loc['defaultsharingstate'];
                    } elseif (isset($default_sharing_loc['filtersharingcountry']) AND ($default_sharing_loc['filtersharingcountry'] != '')) {
                        $variables['jobcountry'] = $default_sharing_loc['filtersharingcountry'];
                    } elseif (isset($default_sharing_loc['defaultsharingcountry']) AND ($default_sharing_loc['defaultsharingcountry'] != '')) {
                        $variablesp['jobcountry'] = $default_sharing_loc['defaultsharingcountry'];
                    }
                    $data = $this->getJobsFromServerAndFill($variables);
                    $this->_applications = $data['jobs'];
                    $total = $data['total'];
                }else{ // above 100
                    $data = $this->getJobsFromServerFilter($variables);
                    $this->_applications = $data['jobs'];
                    $total = $data['total'];
                }
            }else{ // filter is not null
                $data = $this->getJobsFromServerFilter($variables);
                $this->_applications = $data['jobs'];
                $total = $data['total'];
            }
        }

        //for goldjobs
        if ($showgoldjobs == 1) {
            if ($noofgoldjobs != 0) {
                $goldjoblimit = ($limitstart / $limit) * $noofgoldjobs;
                $query = "SELECT job.*, cat.cat_title, jobtype.title AS jobtype, jobstatus.title AS jobstatus
                                , company.id AS companyid, company.name AS companyname, company.url 
                                , company.serverid AS companyserverid,company.alias AS companyalias
                                , salaryfrom.rangestart AS salaryfrom, salaryto.rangeend AS salaryto, salarytype.title AS salaytype
                                , currency.symbol
                                ,(TO_DAYS( CURDATE() ) - To_days( job.startpublishing ) ) AS jobdays
                                ,CONCAT(job.alias,'-',job.id) AS aliasid
                                ,CONCAT(company.alias,'-',company.id) AS companyaliasid

                                FROM `#__js_job_jobs` AS job
                                JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
                                JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                                JOIN `#__js_job_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id
                                LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id 
                                LEFT JOIN `#__js_job_salaryrange` AS salaryfrom ON job.salaryrangefrom = salaryfrom.id
                                LEFT JOIN `#__js_job_salaryrange` AS salaryto ON job.salaryrangeto = salaryto.id
                                LEFT JOIN `#__js_job_salaryrangetypes` AS salarytype ON job.salaryrangetype = salarytype.id
                                LEFT JOIN `#__js_job_currencies` AS currency ON job.currencyid = currency.id 
                                WHERE job.status = 1 AND job.isgoldjob = 1
                                AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate);
                $db->setQuery($query, $goldjoblimit, $noofgoldjobs);
                $goldjobs = $db->loadObjectList();
                foreach ($goldjobs AS $goldjobdata) {   // for multicity select 
                    $multicitydata = $this->getMultiCityData($goldjobdata->id);
                    if ($multicitydata != "")
                        $goldjobdata->city = $multicitydata;
                    if ($this->_client_auth_key != "") {
                        $goldjobdata->id = $goldjobdata->serverid;
                        $goldjobdata->aliasid = $goldjobdata->alias . '-' . $goldjobdata->id;
                        $goldjobdata->companyid = $goldjobdata->companyserverid;
                        $goldjobdata->companyaliasid = $goldjobdata->companyalias . '-' . $goldjobdata->companyid;
                    }
                }
            }
        }
        else
            $goldjobs = array();

        //for featuredjob
        if ($showfeaturedjobs == 1) {
            if ($nooffeaturedjobs != 0) {
                $featuredjoblimit = ($limitstart / $limit) * $nooffeaturedjobs;
                $query = "SELECT job.*, cat.cat_title, jobtype.title AS jobtype, jobstatus.title AS jobstatus
                                , company.id AS companyid, company.name AS companyname, company.url 
                                , company.serverid AS companyserverid,company.alias AS companyalias
                                , salaryfrom.rangestart AS salaryfrom, salaryto.rangeend AS salaryto, salarytype.title AS salaytype
                                , currency.symbol
                                ,(TO_DAYS( CURDATE() ) - To_days( job.startpublishing ) ) AS jobdays
                                ,CONCAT(job.alias,'-',job.id) AS aliasid
                                ,CONCAT(company.alias,'-',company.id) AS companyaliasid
                                FROM `#__js_job_jobs` AS job
                                JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
                                JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                                JOIN `#__js_job_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id
                                LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id 
                                LEFT JOIN `#__js_job_salaryrange` AS salaryfrom ON job.salaryrangefrom = salaryfrom.id
                                LEFT JOIN `#__js_job_salaryrange` AS salaryto ON job.salaryrangeto = salaryto.id
                                LEFT JOIN `#__js_job_salaryrangetypes` AS salarytype ON job.salaryrangetype = salarytype.id
                                LEFT JOIN `#__js_job_currencies` AS currency ON job.currencyid = currency.id 
                                WHERE job.status = 1 AND job.isfeaturedjob = 1
                                AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate);
                $db->setQuery($query, $featuredjoblimit, $nooffeaturedjobs);
                $featuredjobs = $db->loadObjectList();
                foreach ($featuredjobs AS $featuredjobsdata) {   // for multicity select 
                    $multicitydata = $this->getMultiCityData($featuredjobsdata->id);
                    if ($multicitydata != "")
                        $featuredjobsdata->city = $multicitydata;
                    if ($this->_client_auth_key != "") {
                        $featuredjobsdata->id = $featuredjobsdata->serverid;
                        $featuredjobsdata->aliasid = $featuredjobsdata->alias . '-' . $featuredjobsdata->id;
                        $featuredjobsdata->companyid = $featuredjobsdata->companyserverid;
                        $featuredjobsdata->companyaliasid = $featuredjobsdata->companyalias . '-' . $featuredjobsdata->companyid;
                    }
                }
            }
        }
        else
            $featuredjobs = array();

        $jobtype = $this->common_model->getJobType(JText::_('JS_SELECT_JOB_TYPE'));
        $jobstatus = $this->common_model->getJobStatus(JText::_('JS_SELECT_JOB_STATUS'));
        $heighesteducation = $this->common_model->getHeighestEducation(JText::_('JS_SELECT_EDUCATION'));

        $job_categories = $this->common_model->getCategories(JText::_('JS_SELECT_CATEGORY'));
        if ($filterjobcategory == '')
            $categoryid = 1;
        else
            $categoryid = $filterjobcategory;
        $job_subcategories = $this->common_model->getSubCategoriesforCombo($categoryid, JText::_('JS_SUB_CATEGORY'), $value = '');
        $job_salaryrange = $this->common_model->getJobSalaryRange(JText::_('JS_SELECT_SALARY'), '');
        $countries = $this->common_model->getSharingCountries(JText::_('JS_SELECT_COUNTRY'));

        $filterlists['country'] = JHTML::_('select.genericList', $countries, 'cmbfilter_country', 'class="inputbox"  style="width:' . $cat_jobtype_fields_width . 'px;" ' . '', 'value', 'text', $cmbfiltercountry);
        $filterlists['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'filter_jobcategory', 'class="inputbox" style="width:' . $cat_jobtype_fields_width . 'px;" ' . 'onChange=fj_getsubcategories(\'td_jobsubcategory\',this.value);', 'value', 'text', $filterjobcategory);
        $filterlists['jobsubcategory'] = JHTML::_('select.genericList', $job_subcategories, 'filter_jobsubcategory', 'class="inputbox" style="width:' . $cat_jobtype_fields_width . 'px;" ' . '', 'value', 'text', $filterjobsubcategory);
        $filterlists['jobtype'] = JHTML::_('select.genericList', $jobtype, 'filter_jobtype', 'class="inputbox" style="width:' . $cat_jobtype_fields_width . 'px;"  ' . '', 'value', 'text', $filterjobtype);

        $location = $this->common_model->getAddressDataByCityName('', $city_filter);
        if (isset($location[0]->name))
            $filtervalues['location'] = $location[0]->name;
        else
            $filtervalues['location'] = "";

        $filtervalues['city'] = $city_filter;
        $filtervalues['radius'] = $txtfilterradius;
        $filtervalues['longitude'] = $txtfilterlongitude;
        $filtervalues['latitude'] = $txtfilterlatitude;

        $listjobconfig = $this->common_model->getConfigByFor('listjob');

        $packageexpiry = $this->getJobSeekerPackageExpiry($uid);
        if ($packageexpiry == 1) { //package expire or user not login
            $listjobconfigs = array();
            $listjobconfigs['lj_title'] = $listjobconfig['visitor_lj_title'];
            $listjobconfigs['lj_category'] = $listjobconfig['visitor_lj_category'];
            $listjobconfigs['lj_jobtype'] = $listjobconfig['visitor_lj_jobtype'];
            $listjobconfigs['lj_jobstatus'] = $listjobconfig['visitor_lj_jobstatus'];
            $listjobconfigs['lj_company'] = $listjobconfig['visitor_lj_company'];
            $listjobconfigs['lj_companysite'] = $listjobconfig['visitor_lj_companysite'];
            $listjobconfigs['lj_country'] = $listjobconfig['visitor_lj_country'];
            $listjobconfigs['lj_state'] = $listjobconfig['visitor_lj_state'];
            $listjobconfigs['lj_city'] = $listjobconfig['visitor_lj_city'];
            $listjobconfigs['lj_salary'] = $listjobconfig['visitor_lj_salary'];
            $listjobconfigs['lj_created'] = $listjobconfig['visitor_lj_created'];
            $listjobconfigs['lj_noofjobs'] = $listjobconfig['visitor_lj_noofjobs'];
            $listjobconfigs['lj_description'] = $listjobconfig['visitor_lj_description'];
            $listjobconfigs['lj_shortdescriptionlenght'] = $listjobconfig['lj_shortdescriptionlenght'];
            $listjobconfigs['lj_joblistingstyle'] = $listjobconfig['lj_joblistingstyle'];
        }
        else
            $listjobconfigs = $listjobconfig; // user

        $result[0] = $this->_applications;
        $result[1] = $total;
        $result[2] = $filterlists;
        $result[3] = $filtervalues;
        $result[4] = $listjobconfigs;
        $result[5] = $goldjobs;
        $result[6] = $featuredjobs;

        return $result;
    }

    function &getMessagesbyJobsforJobSeeker($uid, $limit, $limitstart) {
        $result = array();
        $db = &$this->getDBO();

        if (is_numeric($uid) == false)
            return false;
        if (($uid == 0) || ($uid == ''))
            return false;
        $total = 0;
        if ($this->_client_auth_key != "") {
            $fortask = "getmessagesbyjobsforjobseeker";
            $jsjobsharingobject = new JSJobsModelJob_Sharing;
            $data['uid'] = $uid;
            $data['limit'] = $limit;
            $data['limitstart'] = $limitstart;
            $data['authkey'] = $this->_client_auth_key;
            $data['siteurl'] = $this->_siteurl;
            $encodedata = json_encode($data);
            $return_server_value = $jsjobsharingobject->serverTask($encodedata, $fortask);
            if (isset($return_server_value['messagejobseeker']) AND $return_server_value['messagejobseeker'] == -1) { // auth fail 
                $logarray['uid'] = $this->_uid;
                $logarray['referenceid'] = $return_server_value['referenceid'];
                $logarray['eventtype'] = $return_server_value['eventtype'];
                $logarray['message'] = $return_server_value['message'];
                $logarray['event'] = "Message By jobseeker";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jsjobsharingobject->write_JobSharingLog($logarray);
                $result[0] = (object) array();
                $result[1] = 0;
            } else {
                $parse_data = array();
                foreach ($return_server_value['jsmessages'] AS $rel_data) {
                    $parse_data[] = (object) $rel_data;
                }
                $result[0] = $parse_data;
                $result[1] = $return_server_value['total'];
            }
        } else {
            $query = "SELECT message.id
                        FROM `#__js_job_messages` AS message
                        JOIN `#__js_job_jobs` AS job ON job.id = message.jobid
                        JOIN `#__js_job_resume` AS resume ON resume.id = message.resumeid
                        JOIN `#__js_job_companies` AS company ON company.id = job.companyid
                        WHERE message.jobseekerid = " . $uid . "
                        GROUP BY message.jobid";
            $db->setQuery($query);
            $totobj = $db->loadObjectList();
            foreach ($totobj as $obj)
                $total++;

            if ($total <= $limitstart)
                $limitstart = 0;

            $query = "SELECT message.id, message.jobid, message.resumeid, job.title, job.created, company.id as companyid, company.name as companyname
                        ,(SELECT COUNT(id) FROM `#__js_job_messages` WHERE jobseekerid = " . $uid . " AND sendby != " . $uid . " AND jobid = message.jobid AND isread = 0) as unread
                        ,CONCAT(company.alias,'-',companyid) AS companyaliasid
                        ,CONCAT(resume.alias,'-',resume.id) AS resumealiasid
                        FROM `#__js_job_messages` AS message
                        JOIN `#__js_job_jobs` AS job ON job.id = message.jobid
                        JOIN `#__js_job_resume` AS resume ON resume.id = message.resumeid
                        JOIN `#__js_job_companies` AS company ON company.id = job.companyid
                        WHERE message.jobseekerid = " . $uid . "
                        GROUP BY message.jobid
                        ORDER BY message.created DESC ";
            $db->setQuery($query, $limitstart, $limit);
            $messages = $db->loadObjectList();
            $result[0] = $messages;
            $result[1] = $total;
        }

        return $result;
    }

    function &getJobbyIdforJobApply($job_id) {
        $db = &$this->getDBO();
        if (is_numeric($job_id) == false)
            return false;
        if ($this->_client_auth_key != "") {

            $fortask = "getjobapplybyidforjobapply";
            $jsjobsharingobject = new JSJobsModelJob_Sharing;
            $data['jobid'] = $job_id;
            $data['authkey'] = $this->_client_auth_key;
            $data['siteurl'] = $this->_siteurl;
            $encodedata = json_encode($data);
            $return_server_value = $jsjobsharingobject->serverTask($encodedata, $fortask);
            if (isset($return_server_value['jobapplybyid']) AND $return_server_value['jobapplybyid'] == -1) { // auth fail 
                $logarray['uid'] = $this->_uid;
                $logarray['referenceid'] = $return_server_value['referenceid'];
                $logarray['eventtype'] = $return_server_value['eventtype'];
                $logarray['message'] = $return_server_value['message'];
                $logarray['event'] = "Job Apply By Id";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jsjobsharingobject->write_JobSharingLog($logarray);
                $this->_application = array();
            } else {
                $this->_application = (object) $return_server_value['relationjsondata'];
            }
        } else {
            $query = "SELECT job.*, cat.cat_title , company.name as companyname, company.url
                        , jobtype.title AS jobtypetitle
                        , jobstatus.title AS jobstatustitle, shift.title as shifttitle
                        , salary.rangestart, salary.rangeend, education.title AS heighesteducationtitle
                        ,CONCAT(company.alias,'-',company.id) AS companyaliasid
                        FROM `#__js_job_jobs` AS job
                        JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id
                        JOIN `#__js_job_companies` AS company ON job.companyid = company.id
                        JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                        JOIN `#__js_job_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id
                        LEFT JOIN `#__js_job_salaryrange` AS salary ON job.jobsalaryrange = salary.id
                        LEFT JOIN `#__js_job_heighesteducation` AS education ON job.heighestfinisheducation = education.id
                        LEFT JOIN `#__js_job_shifts` AS shift ON job.shift = shift.id
                        WHERE  job.id = " . $job_id;
            $db->setQuery($query);
            $this->_application = $db->loadObject();
            $this->_application->multicity = $this->common_model->getMultiCityDataForView($job_id, 1);
        }

        $result[0] = $this->_application;
        $result[1] = $this->common_model->getConfigByFor('listjob'); // company fields

        return $result;
    }

    function &getMyAppliedJobs($u_id, $sortby, $limit, $limitstart) {
        $db = &$this->getDBO();
        if ($u_id)
            if ((is_numeric($u_id) == false) || ($u_id == 0) || ($u_id == ''))
                return false;
        $result = array();
        $listjobconfig = $this->common_model->getConfigByFor('listjob');

        if ($this->_client_auth_key != "") {
            $fortask = "myappliedjobs";
            $jsjobsharingobject = new JSJobsModelJob_Sharing;
            $data['uid'] = $u_id;
            $data['sortby'] = $sortby;
            $data['limitstart'] = $limitstart;
            $data['limit'] = $limit;
            $data['authkey'] = $this->_client_auth_key;
            $data['siteurl'] = $this->_siteurl;
            $encodedata = json_encode($data);
            $return_server_value = $jsjobsharingobject->serverTask($encodedata, $fortask);
            if (isset($return_server_value['getmyappliedjobs']) AND $return_server_value['getmyappliedjobs'] == -1) { // auth fail 
                $logarray['uid'] = $this->_uid;
                $logarray['referenceid'] = $return_server_value['referenceid'];
                $logarray['eventtype'] = $return_server_value['eventtype'];
                $logarray['message'] = $return_server_value['message'];
                $logarray['event'] = "Applied Jobs";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jsjobsharingobject->write_JobSharingLog($logarray);
                $this->_applications = array();
                $total = 0;
            } else {
                $parse_data = array();
                if (is_array($return_server_value))
                    foreach ($return_server_value['relationjsondata'] AS $rel_data) {
                        $parse_data[] = (object) $rel_data;
                    }
                $this->_applications = $parse_data;
                $total = $return_server_value['total'];
            }
        } else {
            $query = "SELECT COUNT(job.id) FROM `#__js_job_jobs` AS job, `#__js_job_jobapply` AS apply  
			WHERE apply.jobid = job.id AND apply.uid = " . $u_id;
            $db->setQuery($query);
            $total = $db->loadResult();
            if ($total <= $limitstart)
                $limitstart = 0;

            $query = "SELECT job.*, cat.cat_title, apply.apply_date, jobtype.title AS jobtypetitle, jobstatus.title AS jobstatustitle
                        , salaryfrom.rangestart,salaryto.rangeend, salaryto.rangeend AS salaryto
                        ,company.id AS companyid, company.name AS companyname, company.url,salarytype.title AS salaytype
                        , job.isgoldjob AS isgold
                        , job.isfeaturedjob AS isfeatured
                        ,CONCAT(job.alias,'-',job.id) AS aliasid
                        ,CONCAT(company.alias,'-',companyid) AS companyaliasid
                        ,cur.symbol,apply.action_status AS resumestatus
                        FROM `#__js_job_jobs` AS job
                        JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id 
                        JOIN `#__js_job_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id 
                        LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id 
                        LEFT JOIN `#__js_job_salaryrange` AS salaryfrom ON job.salaryrangefrom = salaryfrom.id
                        LEFT JOIN `#__js_job_salaryrange` AS salaryto ON job.salaryrangeto = salaryto.id
                        LEFT JOIN `#__js_job_salaryrangetypes` AS salarytype ON job.salaryrangetype = salarytype.id
                        LEFT JOIN `#__js_job_currencies` AS cur ON cur.id = job.currencyid
                        , `#__js_job_categories` AS cat
                        , `#__js_job_jobapply` AS apply  
                        WHERE job.jobcategory = cat.id AND apply.jobid = job.id AND apply.uid = " . $u_id . " ORDER BY  " . $sortby;

            $db->setQuery($query, $limitstart, $limit);
            $this->_applications = $db->loadObjectList();
            foreach ($this->_applications AS $jobdata) {   // for multicity select 
                $multicitydata = $this->getMultiCityData($jobdata->id);
                if ($multicitydata != "")
                    $jobdata->city = $multicitydata;
            }
        }

        $result[0] = $this->_applications;
        $result[1] = $total;
        $result[2] = $listjobconfig;

        return $result;
    }

    function &getMyResumesbyUid($u_id, $sortby, $limit, $limitstart) {
        $db = &$this->getDBO();
        if (is_numeric($u_id) == false)
            return false;
        $result = array();
        $resumeconfig = $this->common_model->getConfigByFor('searchresume');
        $query = "SELECT COUNT(id) FROM `#__js_job_resume` WHERE uid  = " . $u_id;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT resume.* , category.cat_title, jobtype.title AS jobtypetitle, salary.rangestart, salary.rangeend
                    , country.name AS countryname,city.name AS cityname,state.name AS statename
                    , currency.symbol		
                    , resume.isgoldresume AS isgold
                    , resume.isfeaturedresume AS isfeatured
                    ,CONCAT(resume.alias,'-',resume.id) AS aliasid
                    FROM `#__js_job_resume` AS resume
                    JOIN  `#__js_job_categories` AS category ON	resume.job_category = category.id
                    JOIN  `#__js_job_salaryrange` AS salary	ON	resume.jobsalaryrange = salary.id
                    JOIN  `#__js_job_jobtypes` AS jobtype ON resume.jobtype = jobtype.id
                    LEFT JOIN `#__js_job_cities` AS city ON resume.address_city= city.id
                    LEFT JOIN `#__js_job_states` AS state ON city.stateid= state.id
                    LEFT JOIN `#__js_job_countries` AS country ON city.countryid = country.id
                    LEFT JOIN `#__js_job_currencies` AS currency ON currency.id= resume.currencyid
                    WHERE resume.uid  = " . $u_id . "
                    ORDER BY " . $sortby;
        $db->setQuery($query);
        $db->setQuery($query, $limitstart, $limit);
        $this->_applications = $db->loadObjectList();

        $result[0] = $this->_applications;
        $result[1] = $total;
        $result[2] = $resumeconfig;

        return $result;
    }

    function &getUserType($u_id) {
        $db = &$this->getDBO();
        if (is_numeric($u_id) == false)
            return false;
        $query = "SELECT userrole.*, role.rolefor 
                    FROM `#__js_job_userroles` AS userrole
                    JOIN `#__js_job_roles` AS role ON userrole.role = role.id
                    WHERE  uid  = " . $u_id;
        $db->setQuery($query);
        $result[0] = $db->loadObject();

        $usertype = array(
            '0' => array('value' => 1, 'text' => JText::_('JS_EMPLOYER')),
            '1' => array('value' => 2, 'text' => JText::_('JS_JOB_SEEKER')),);

        if (isset($result[0]))
            $lists['usertype'] = JHTML::_('select.genericList', $usertype, 'usertype', 'class="inputbox" ' . '', 'value', 'text', $result[0]->rolefor);
        else
            $lists['usertype'] = JHTML::_('select.genericList', $usertype, 'usertype', 'class="inputbox" ' . '', 'value', 'text', 1);
        $result[1] = $lists;

        return $result;
    }

    function &getResumebyId($id, $u_id) {
        $db = &$this->getDBO();
        if (is_numeric($u_id) == false)
            return false;
        if (($id != '') && ($id != 0)) {
            if (is_numeric($id) == false)
                return false;
            $query = "SELECT * FROM `#__js_job_resume` WHERE id = " . $id . " AND uid  = " . $u_id;
            $db->setQuery($query);
            $this->_application = $db->loadObject();
            $result[0] = $this->_application;
        }
        if ($u_id != "" AND $u_id != 0)
            $result[3] = $this->common_model->getFieldsOrdering(3); // resume fields
        else
            $result[3] = $this->common_model->getFieldsOrdering(16); // resume visitor fields

        $result[2] = $this->common_model->getUserFields(3, $id); // job fields , ref id
        if ($id) { // not new
            $result[4] = 1;
            $result[5] = null;
        } else { // new
            $returnresult = $this->canAddNewResume($u_id);
            $result[4] = $returnresult[0];
            $result[5] = $returnresult[1];
        }

        return $result;
    }

    function &getJobSeekerPackages($limit, $limitstart) {
        $db = &$this->getDBO();
        $result = array();

        $query = "SELECT COUNT(id) FROM `#__js_job_jobseekerpackages` WHERE status = 1";
        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT package.* ,cur.symbol FROM `#__js_job_jobseekerpackages` AS package  LEFT JOIN `#__js_job_currencies` AS cur ON cur.id=package.currencyid WHERE package.status = 1";
        $db->setQuery($query, $limitstart, $limit);
        $packages = $db->loadObjectList();

        $result[0] = $packages;
        $result[1] = $total;

        return $result;
    }

    function &getJobSeekerPackageById($packageid) {
        if (is_numeric($packageid) == false)
            return false;

        $db = &$this->getDBO();
        $query = "SELECT package.* ,cur.symbol
                    FROM `#__js_job_jobseekerpackages` AS package 
                    LEFT JOIN `#__js_job_currencies` AS cur ON cur.id=package.currencyid
                    WHERE package.id = " . $packageid;
        $db->setQuery($query);
        $package = $db->loadObject();
        return $package;
    }

    function &getJobSeekerPurchaseHistory($uid, $limit, $limitstart) {
        if ($uid)
            if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
                return false;
        $db = &$this->getDBO();
        $result = array();
        $query = "SELECT COUNT(id) FROM `#__js_job_paymenthistory` WHERE uid = " . $uid . " AND status = 1 AND packagefor=2 ";
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;
        $query = "SELECT purchase.paidamount,purchase.transactionverified,purchase.created,
                    package.id,package.title,package.resumeallow,package.coverlettersallow,package.packageexpireindays,cur.symbol
                    FROM `#__js_job_paymenthistory` AS purchase 
                    JOIN `#__js_job_jobseekerpackages` AS package ON package.id = purchase.packageid
                    LEFT JOIN `#__js_job_currencies` AS cur ON cur.id = purchase.currencyid
                    WHERE purchase.uid = " . $uid . " AND purchase.status = 1 AND purchase.packagefor=2 ORDER BY purchase.created DESC ";
        $db->setQuery($query, $limitstart, $limit);
        $packages = $db->loadObjectList();
        $result[0] = $packages;
        $result[1] = $total;
        return $result;
    }

    function &getCoverLetterbyId($id, $u_id) {
        $db = &$this->getDBO();
        if ($u_id)
            if ($u_id)
                if ((is_numeric($u_id) == false) || ($u_id == 0) || ($u_id == ''))
                    return false;

        if (($id != '') && ($id != 0)) {
            if (is_numeric($id) == false)
                return false;
            $query = "SELECT * FROM `#__js_job_coverletters` WHERE id = " . $id;
            $db->setQuery($query);
            $this->_application = $db->loadObject();
            $result[0] = $this->_application;
        }
        if ($id) // not new
            $result[4] = 1;
        else // new
        if (isset($u_id)) {
            if (is_numeric($u_id) == false)
                return false;
            $canaddreturnvalue = $this->canAddNewCoverLetter($u_id);
            $result[4] = $canaddreturnvalue[0];
            $result[5] = $canaddreturnvalue[1];
        }
        return $result;
    }

    function &getMyCoverLettersbyUid($u_id, $limit, $limitstart) {
        $db = &$this->getDBO();
        if ((is_numeric($u_id) == false) || ($u_id == 0) || ($u_id == ''))
            return false;
        $result = array();
        $query = "SELECT COUNT(id) FROM `#__js_job_coverletters` WHERE uid  = " . $u_id;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT letter.*,CONCAT(letter.alias,'-',letter.id) aliasid 
                    FROM `#__js_job_coverletters` AS letter
                    WHERE letter.uid  = " . $u_id;
        $db->setQuery($query);
        $db->setQuery($query, $limitstart, $limit);
        $result[0] = $db->loadObjectList();
        $result[1] = $total;
        return $result;
    }

    function &getMyJobSearchesbyUid($u_id, $limit, $limitstart) {
        if ((is_numeric($u_id) == false) || ($u_id == 0) || ($u_id == ''))
            return false;
        $db = &$this->getDBO();
        $result = array();
        $query = "SELECT COUNT(id) FROM `#__js_job_jobsearches` WHERE uid  = " . $u_id;
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT search.* FROM `#__js_job_jobsearches` AS search WHERE search.uid  = " . $u_id;
        $db->setQuery($query);
        $db->setQuery($query, $limitstart, $limit);

        $result[0] = $db->loadObjectList();
        $result[1] = $total;

        return $result;
    }

    function &getJobSearchebyId($id) {
        $db = &$this->getDBO();
        if (is_numeric($id) == false)
            return false;
        $query = "SELECT search.* FROM `#__js_job_jobsearches` AS search WHERE search.id  = " . $id;
        $db->setQuery($query);
        return $db->loadObject();
    }

    function &getUserFilter($u_id) {
        if ($u_id)
            if ((is_numeric($u_id) == false) || ($u_id == 0) || ($u_id == ''))
                return false;
        $db = &$this->getDBO();

        $query = "SELECT filter.* FROM `#__js_job_filters` AS filter WHERE filter.uid  = " . $u_id;
        $db->setQuery($query);
        $userfields = $db->loadObject();
        return $userfields;
    }

    function &getEmpApplicationbyid($id) { // <<<--- this isn't used
        if (is_numeric($id) == false)
            return false;
        $db = &$this->getDBO();


        $query = "SELECT app.* , cat.cat_title AS job_category, salary.rangestart, salary.rangeend
                    , address_city.name AS address_city , address_county.name AS address_county , address_state.name AS address_state , address_country.name AS address_country 
                    , institute_city.name AS institute_city , institute_county.name AS institute_county , institute_state.name AS institute_state , institute_country.name AS institute_country 
                    , employer_city.name AS employer_city , employer_county.name AS employer_county , employer_state.name AS employer_state , employer_country.name AS employer_country 
                    FROM `#__js_job_resume` AS app 
                    JOIN `#__js_job_categories` AS cat ON app.job_category = cat.id 
                    LEFT JOIN `#__js_job_salaryrange` AS salary ON app.jobsalaryrange = salary.id 
                    LEFT JOIN `#__js_job_cities` AS address_city ON app.address_city = address_city.id
                    LEFT JOIN `#__js_job_counties` AS address_county ON app.address_county = address_county.id
                    LEFT JOIN `#__js_job_states` AS address_state ON app.address_state = address_state.id 
                    LEFT JOIN `#__js_job_countries` AS address_country ON app.address_country = address_country.id 
                    LEFT JOIN `#__js_job_cities` AS institute_city ON app.institute_city = institute_city.id
                    LEFT JOIN `#__js_job_counties` AS institute_county ON app.institute_county = institute_county.id
                    LEFT JOIN `#__js_job_states` AS institute_state ON app.institute_state = institute_state.id 
                    LEFT JOIN `#__js_job_countries` AS institute_country ON app.institute_country = institute_country.id 
                    LEFT JOIN `#__js_job_cities` AS employer_city ON app.employer_city = employer_city.id
                    LEFT JOIN `#__js_job_counties` AS employer_county ON app.employer_county = employer_county.id
                    LEFT JOIN `#__js_job_states` AS employer_state ON app.employer_state = employer_state.id 
                    LEFT JOIN `#__js_job_countries` AS employer_country ON app.employer_country = employer_country.id 
                    WHERE app.id = " . $db->Quote($id);
        $db->setQuery($query);
        $this->_application = $db->loadObject();
        return $this->_application;
    }

    function &getSearchOptions($uid) {
        if ($uid)
            if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
                return false;
        $db = &$this->getDBO();
        if (!isset($this->_config)) {
            $this->_config = $this->common_model->getConfig('');
        }
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'js_newlisting_requiredpackage')
                $newlisting_required_package = $conf->configvalue;
        }

        if ($newlisting_required_package == 0) {
            $canview = 1;
        } elseif ($uid == 0) { //guest
            $canview = 1;
        } else {
            $query = "SELECT package.jobsearch, package.packageexpireindays, payment.created
                    FROM `#__js_job_jobseekerpackages` AS package
                    JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=2)
                    WHERE payment.uid = " . $uid . "
                    AND DATE_ADD(payment.created,INTERVAL package.packageexpireindays DAY) >= CURDATE()
                    AND payment.transactionverified = 1 AND payment.status = 1";
            //echo $query;
            $db->setQuery($query);
            $jobs = $db->loadObjectList();
            $canview = 0;
            if (empty($jobs))
                $canview = 1; // for those who not get any role

            foreach ($jobs AS $job) {
                if ($job->jobsearch == 1) {
                    $canview = 1;
                    break;
                } else {
                    $canview = 0;
                }
            }
        }

        if ($canview == 1) {
            $searchjobconfig = $this->common_model->getConfigByFor('searchjob');

            if (!$this->_searchoptions) {
                $this->_searchoptions = array();
                $companies = $this->common_model->getAllCompanies(JText::_('JS_SEARCH_ALL'));
                $job_type = $this->common_model->getJobType(JText::_('JS_SEARCH_ALL'));
                $jobstatus = $this->common_model->getJobStatus(JText::_('JS_SEARCH_ALL'));
                $heighesteducation = $this->common_model->getHeighestEducation(JText::_('JS_SEARCH_ALL'));
                $job_categories = $this->common_model->getCategories(JText::_('JS_SEARCH_ALL'));
                $job_subcategories = $this->common_model->getSubCategoriesforCombo($job_categories[1]['value'], JText::_('JS_SEARCH_ALL'), '');
                $job_salaryrange = $this->common_model->getJobSalaryRange(JText::_('JS_SEARCH_ALL'), '');
                $shift = $this->common_model->getShift(JText::_('JS_SEARCH_ALL'));
                $currencies = $this->common_model->getCurrency(JText::_('JS_SEARCH_ALL'));
                $lists['heighesteducation'] = JHTML::_('select.genericList', $this->common_model->getHeighestEducation(''), 'heighestfinisheducation', 'class="inputbox" ' . '', 'value', 'text', '');
                $lists['shift'] = JHTML::_('select.genericList', $this->common_model->getShift(''), 'shift', 'class="inputbox" ' . '', 'value', 'text', '');
                $this->_searchoptions['educationminimax'] = JHTML::_('select.genericList', $this->common_model->getMiniMax(''), 'educationminimax', 'class="inputbox" ' . '', 'value', 'text', '');
                $this->_searchoptions['education'] = JHTML::_('select.genericList', $this->common_model->getHeighestEducation(''), 'educationid', 'class="inputbox" ' . '', 'value', 'text', '');
                $lists['minimumeducationrange'] = JHTML::_('select.genericList', $this->common_model->getHeighestEducation(JText::_('JS_MINIMUM')), 'mineducationrange', 'class="inputbox" ' . '', 'value', 'text', '');
                $lists['maximumeducationrange'] = JHTML::_('select.genericList', $this->common_model->getHeighestEducation(JText::_('JS_MAXIMUM')), 'maxeducationrange', 'class="inputbox" ' . '', 'value', 'text', '');
                $this->_searchoptions['jobsalaryrange'] = JHTML::_('select.genericList', $job_salaryrange, 'jobsalaryrange', 'class="inputbox" ' . '', 'value', 'text', '');
                $this->_searchoptions['salaryrangefrom'] = JHTML::_('select.genericList', $this->common_model->getJobSalaryRange(JText::_('JS_FROM'), 1), 'salaryrangefrom', 'class="inputbox" ' . '', 'value', 'text', '');
                $this->_searchoptions['salaryrangeto'] = JHTML::_('select.genericList', $this->common_model->getJobSalaryRange(JText::_('JS_TO'), 1), 'salaryrangeto', 'class="inputbox" ' . '', 'value', 'text', '');
                $this->_searchoptions['salaryrangetypes'] = JHTML::_('select.genericList', $this->common_model->getSalaryRangeTypes(''), 'salaryrangetype', 'class="inputbox" ' . '', 'value', 'text', 2);
                $this->_searchoptions['companies'] = JHTML::_('select.genericList', $companies, 'company', 'class="inputbox" ' . '', 'value', 'text', '');
                $this->_searchoptions['jobcategory'] = JHTML::_('select.genericList', $job_categories, 'jobcategory', 'class="inputbox" ' . 'onChange="fj_getsubcategories(\'fj_subcategory\', this.value)"', 'value', 'text', '');
                $this->_searchoptions['jobsubcategory'] = JHTML::_('select.genericList', $job_subcategories, 'jobsubcategory', 'class="inputbox" ' . '', 'value', 'text', '');
                $this->_searchoptions['jobstatus'] = JHTML::_('select.genericList', $jobstatus, 'jobstatus', 'class="inputbox" ' . '', 'value', 'text', '');
                $this->_searchoptions['jobtype'] = JHTML::_('select.genericList', $job_type, 'jobtype', 'class="inputbox" ' . '', 'value', 'text', '');
                $this->_searchoptions['heighestfinisheducation'] = JHTML::_('select.genericList', $heighesteducation, 'heighestfinisheducation', 'class="inputbox" ' . '', 'value', 'text', '');
                $this->_searchoptions['shift'] = JHTML::_('select.genericList', $shift, 'shift', 'class="inputbox" ' . '', 'value', 'text', '');
                $this->_searchoptions['currency'] = JHTML::_('select.genericList', $currencies, 'currency', 'class="inputbox" ' . '', 'value', 'text', '');
            }
            $result = array();
            $result[0] = $this->_searchoptions;
            $result[1] = $searchjobconfig;
            $result[2] = $canview;
        } else {
            $result[2] = $canview;
        }
        return $result;
    }

    function canAddNewResume($uid) {
        $db = &$this->getDBO();
        if ($uid)
            if ((is_numeric($uid) == false))
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
            if ($conf->configname == 'js_newlisting_requiredpackage')
                $newlisting_required_package = $conf->configvalue;
        }
        if ($newlisting_required_package == 0) {
            $packagedetail = '';
            $returnvalue[0] = true;
            $returnvalue[1] = $packagedetail;
            return $returnvalue;
        } else {
            $query = "SELECT package.id, package.resumeallow, package.packageexpireindays, payment.id AS paymentid, payment.created
			FROM `#__js_job_jobseekerpackages` AS package
			JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=2)
			WHERE payment.uid = " . $uid . "
			AND DATE_ADD(payment.created,INTERVAL package.packageexpireindays DAY) >= CURDATE()
			AND payment.transactionverified = 1 AND payment.status = 1";
            $db->setQuery($query);
            $resumes = $db->loadObjectList();
            if (empty($resumes)) {
                $query = "SELECT package.id, package.resumeallow,package.title AS packagetitle, package.packageexpireindays, payment.id AS paymentid
                            , (TO_DAYS( CURDATE() ) - To_days( payment.created ) ) AS packageexpiredays
                           FROM `#__js_job_jobseekerpackages` AS package
                           JOIN `#__js_job_paymenthistory` AS payment ON ( payment.packageid = package.id AND payment.packagefor=2)
                           WHERE payment.uid = " . $uid . " 
                           AND payment.transactionverified = 1 AND payment.status = 1 ORDER BY payment.created DESC";
                $db->setQuery($query);
                $packagedetail = $db->loadObjectList();

                $return_value[0] = false;
                $return_value[1] = $packagedetail;
                return $return_value;
            }
            $unlimited = 0;
            $resumeallow = 0;
            foreach ($resumes AS $resume) {
                if ($unlimited == 0) {
                    if ($resume->resumeallow != -1) {
                        $resumeallow = $resume->resumeallow + $resumeallow;
                    } else {
                        $unlimited = 1;
                    }
                    $packagedetail[0] = $resume->id;
                    $packagedetail[1] = $resume->paymentid;
                }
            }
            if ($unlimited == 0) {
                if ($resumeallow == 0) {
                    $returnvalue[0] = false;
                    $returnvalue[1] = $packagedetail;
                    return $returnvalue;
                } //can not add new resume
                $query = "SELECT COUNT(resume.id) AS totalresumes FROM `#__js_job_resume` AS resume WHERE resume.uid = " . $uid;
                $db->setQuery($query);
                $totalresume = $db->loadResult();

                if ($resumeallow <= $totalresume) {
                    $returnvalue[0] = false;
                    $returnvalue[1] = $packagedetail;
                    return $returnvalue;
                } //can not add new resume
                else {
                    $query = "SELECT payment.id AS paymentid, package.id, package.resumeallow
                                , (SELECT COUNT(id) FROM #__js_job_resume WHERE packageid = package.id AND paymenthistoryid = payment.id AND uid = " . $uid . ") AS resumeavail
                                FROM #__js_job_paymenthistory AS payment
                                JOIN #__js_job_jobseekerpackages AS package ON ( package.id = payment.packageid AND payment.packagefor=2)
                                WHERE uid = " . $uid . "
                                AND DATE_ADD(payment.created,INTERVAL package.packageexpireindays DAY) >= CURDATE()
                                AND payment.transactionverified = 1 AND payment.status = 1 ";
                    $db->setQuery($query);
                    $packages = $db->loadObjectList();
                    foreach ($packages AS $package) {
                        if ($package->resumeallow > $package->resumeavail) {
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

            $returnvalue[0] = false;
            $returnvalue[1] = $packagedetail;
            return $returnvalue;
        }
    }

    function canAddNewCoverLetter($uid) {
        $db = &$this->getDBO();
        if ($uid)
            if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
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
            if ($conf->configname == 'js_newlisting_requiredpackage')
                $newlisting_required_package = $conf->configvalue;
        }
        if ($newlisting_required_package == 0) {
            $packagedetail = '';
            $returnvalue[0] = true;
            $returnvalue[1] = $packagedetail;
            return $returnvalue;
        } else {
            $query = "SELECT package.id, package.coverlettersallow, package.packageexpireindays, payment.id AS paymentid, payment.created
			FROM `#__js_job_jobseekerpackages` AS package
			JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=2)
			WHERE payment.uid = " . $uid . "
			AND DATE_ADD(payment.created,INTERVAL package.packageexpireindays DAY) >= CURDATE()
			AND payment.transactionverified = 1 AND payment.status = 1";
            $db->setQuery($query);
            $resumes = $db->loadObjectList();
            if (empty($resumes)) {
                $query = "SELECT package.id, package.resumeallow,package.title AS packagetitle, package.packageexpireindays, payment.id AS paymentid
                                , (TO_DAYS( CURDATE() ) - To_days( payment.created ) ) AS packageexpiredays
                               FROM `#__js_job_jobseekerpackages` AS package
                               JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=2)
                               WHERE payment.uid = " . $uid . " 
                               AND payment.transactionverified = 1 AND payment.status = 1 ORDER BY payment.created DESC";
                $db->setQuery($query);
                $packagedetail = $db->loadObjectList();

                $return_value[0] = false;
                $return_value[1] = $packagedetail;
                return $return_value;
            }
            $unlimited = 0;
            $coverlettersallow = 0;
            foreach ($resumes AS $resume) {
                if ($unlimited == 0) {
                    if ($resume->coverlettersallow != -1) {
                        $coverlettersallow = $resume->coverlettersallow + $coverlettersallow;
                    } else {
                        $unlimited = 1;
                    }
                    $packagedetail[0] = $resume->id;
                    $packagedetail[1] = $resume->paymentid;
                }
            }
            if ($unlimited == 0) {
                if ($coverlettersallow == 0) {
                    $returnvalue[0] = false;
                    $returnvalue[1] = $packagedetail;
                    return $returnvalue;
                } //can not add new coverletter 
                $query = "SELECT COUNT(coverletter.id) AS totalcoverletters
				FROM `#__js_job_coverletters` AS coverletter
				WHERE coverletter.uid = " . $uid;
                $db->setQuery($query);
                $totalcoverletters = $db->loadResult();

                if ($coverlettersallow <= $totalcoverletters) {
                    $returnvalue[0] = false;
                    $returnvalue[1] = $packagedetail;
                    return $returnvalue;
                } //can not add new cover letter
                else {
                    $query = "SELECT payment.id AS paymentid, package.id, package.coverlettersallow
                                , (SELECT COUNT(id) FROM #__js_job_coverletters WHERE packageid = package.id AND paymenthistoryid = payment.id AND uid = " . $uid . ") AS coverlettersavail
                                FROM #__js_job_paymenthistory AS payment
                                JOIN #__js_job_jobseekerpackages AS package ON (package.id = payment.packageid AND payment.packagefor=2)
                                WHERE uid = " . $uid . "
                                AND DATE_ADD(payment.created,INTERVAL package.packageexpireindays DAY) >= CURDATE()
                                AND payment.transactionverified = 1 AND payment.status = 1 ";
                    $db->setQuery($query);
                    $packages = $db->loadObjectList();
                    foreach ($packages AS $package) {
                        if ($package->coverlettersallow > $package->coverlettersavail) {
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

            $returnvalue[0] = false;
            $returnvalue[1] = $packagedetail;
            return $returnvalue;
        }
    }

    function sendJobAlertJobseeker($jobid) {
        $db = &$this->getDBO();
        if ((is_numeric($jobid) == false) || ($jobid == 0) || ($jobid == ''))
            return false;
        $query = "SELECT job.title,job.jobcategory, category.cat_title AS categorytitle, subcategory.title AS subcategorytitle
                    ,subcategory.id AS subcategoryid, job.country, job.state, job.city
                    , country.name as countryname, state.name as statename, city.name as cityname
                    , job.metakeywords AS keywords

                    FROM `#__js_job_jobs` AS job
                    JOIN `#__js_job_categories` AS category ON job.jobcategory  = category.id
                    LEFT JOIN `#__js_job_subcategories` AS subcategory ON job.subcategoryid = subcategory.categoryid
                     JOIN `#__js_job_countries` AS country ON job.country = country.id
                    LEFT JOIN `#__js_job_states` AS state ON job.state = state.id
                    LEFT JOIN `#__js_job_cities` AS city ON job.city = city.id
                    WHERE job.id = " . $jobid;
        $db->setQuery($query);
        $job = $db->loadObject();
        if (isset($job->keywords)) {
            $keywords = explode(' ', $job->keywords);
            $metakeywords = array();
            foreach ($keywords AS $keyword) {
                $metakeywords[] = " jobalert.keywords LIKE LOWER('%" . $keyword . "%')";
            }
            $metakeywords[] = " jobalert.keywords = '' OR jobalert.keywords IS NULL";
        }
        $countryquery = "(SELECT jobalert.contactemail
                            FROM `#__js_job_jobalertsetting` AS jobalert
                            WHERE jobalert.categoryid = " . $job->jobcategory . " 
							AND jobalert.country = " . $job->country;
        if ($job->subcategoryid)
            $countryquery .= " AND jobalert.subcategoryid = " . $job->subcategoryid;
        if ($job->state)
            $countryquery .= " AND jobalert.state != " . $job->state;
        //if($job->county) $countryquery .= " AND LOWER(jobalert.county) != LOWER(".$db->quote($job->county).")";
        if ($job->city)
            $countryquery .= " AND jobalert.city != " . $job->city;
        if ($job->keywords)
            $countryquery .= " AND ( " . implode(' OR ', $metakeywords) . " )";
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
            if ($job->keywords)
                $statequery .= " AND ( " . implode(' OR ', $metakeywords) . " )";
            $statequery .= ")";
            $query .= " UNION " . $statequery;
        }
        if ($job->city) {
            $cityquery = "(SELECT jobalert.contactemail
                                FROM `#__js_job_jobalertsetting` AS jobalert
                                WHERE jobalert.categoryid = " . $job->jobcategory . " 
								AND jobalert.country) = " . $job->country;
            if ($job->subcategoryid)
                $cityquery .= " AND jobalert.subcategoryid = " . $job->subcategoryid;
            if ($job->state)
                $cityquery .= " AND jobalert.state = " . $job->state;
            if ($job->city)
                $cityquery .= " AND jobalert.city = " . $job->city;
            if ($job->keywords)
                $cityquery .= " AND ( " . implode(' OR ', $metakeywords) . " )";
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

        $mail_jobs = '<table width="100%" cellpadding="10px" cellspacing="0">
						<tr>
							<th>' . JText::_('JS_JOB_TITLE') . '</th>
							<th>' . JText::_('JS_JOB_CATEGORY') . '</th>
							<th>' . JText::_('JS_JOB_SUBCATEGORY') . '</th>
							<th>' . JText::_('JS_JOB_LOCATION') . '</th>
						</tr>';
        $path = JURI::root() . 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_job&vj=5&oi=' . $job->id . '&Itemid=105';
        $mail_jobs .= '<tr>
							<td><a href="' . $path . '" target="_blank">' . $job->title . '</a></td>
							<td>' . $job->categorytitle . '</td>
							<td>' . $job->subcategorytitle . '</td>
							<td>' . $location . '</td>
						</tr>';
        $mail_jobs .= '</table>';
        $msgBody = str_replace('{SHOW_JOBS}', $mail_jobs, $msgBody);

        $config = $this->common_model->getConfigByFor('email');

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

    function &getJobCat() {
        $db = &$this->getDBO();
        if (!isset($this->_config)) {
            $this->_config = $this->common_model->getConfig('');
        }
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'filter_address_fields_width')
                $address_fields_width = $conf->configvalue;
            if ($conf->configname == 'defaultcountry')
                $defaultcountry = $conf->configvalue;
            if ($conf->configname == 'hidecountry')
                $hidecountry = $conf->configvalue;
        }
        if ($this->_client_auth_key != "") {
            $wherequery = "";
            $server_address = "";
            $fortask = "listjobsbycategory";
            $jsjobsharingobject = new JSJobsModelJob_Sharing;
            $data['wherequery'] = $wherequery;
            $data['server_address'] = $server_address;
            $data['authkey'] = $this->_client_auth_key;
            $data['siteurl'] = $this->_siteurl;
            $encodedata = json_encode($data);
            $return_server_value = $jsjobsharingobject->serverTask($encodedata, $fortask);
            if (isset($return_server_value['jobsbycategory']) AND $return_server_value['jobsbycategory'] == -1) { // auth fail 
                $logarray['uid'] = $this->_uid;
                $logarray['referenceid'] = $return_server_value['referenceid'];
                $logarray['eventtype'] = $return_server_value['eventtype'];
                $logarray['message'] = $return_server_value['message'];
                $logarray['event'] = "List Jobs By Category";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jsjobsharingobject->write_JobSharingLog($logarray);
                $this->_applications = array();
            } else {
                $parse_data = array();
                foreach ($return_server_value['listjobbycategory'] AS $data) {
                    $parse_data[] = (object) $data;
                }
                $this->_applications = $parse_data;
            }
        } else {
            $wherequery = '';
            $curdate = date('Y-m-d H:i:s');
            $inquery = " (SELECT COUNT(job.id) from `#__js_job_jobs`  AS job WHERE cat.id = job.jobcategory AND job.status = 1 AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate);
            $inquery .= $wherequery . " ) as catinjobs";

            $query = "SELECT  DISTINCT cat.id, cat.cat_title,CONCAT(cat.alias,'-',cat.id) AS aliasid, ";
            $query .= $inquery;
            $query .= " FROM `#__js_job_categories` AS cat 
						LEFT JOIN `#__js_job_jobs` AS job ON cat.id = job.jobcategory
						WHERE cat.isactive = 1 ";
            $query .= " ORDER BY cat.cat_title ";
            //echo $query;
            $db->setQuery($query);
            $this->_applications = $db->loadObjectList();
        }
        $filterlists = "";
        $filtervalues = "";

        $result[0] = $this->_applications;
        $result[1] = '';
        $result[2] = $filterlists;
        $result[3] = $filtervalues;

        return $result;
    }

    function getJobSeekerPackageExpiry($uid) {
        $db = &$this->getDBO();
        if (($uid == 0) || ($uid == ''))
            return 1;
        $query = "SELECT package.id
		FROM `#__js_job_jobseekerpackages` AS package
		JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=2)
		WHERE payment.uid = " . $uid . "
		AND DATE_ADD(payment.created,INTERVAL package.packageexpireindays DAY) >= CURDATE()
		AND payment.transactionverified = 1 AND payment.status = 1";
        $db->setQuery($query);
        $packages = $db->loadObjectList();
        if (isset($packages))
            return 0;
        else
            return 1;
    }

    function &getMyResumes($u_id) {

        $db = &$this->getDBO();
        if ($u_id)
            if ((is_numeric($u_id) == false) || ($u_id == 0) || ($u_id == ''))
                return false;

        $totalresume = 0;

        $query = "SELECT id, application_title, create_date, status 
		FROM `#__js_job_resume` WHERE status = 1 AND uid = " . $u_id;
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $resumes = array();
        foreach ($rows as $row) {
            $resumes[] = array('value' => $row->id, 'text' => $row->application_title);
            $totalresume++;
        }
        $myresymes = JHTML::_('select.genericList', $resumes, 'cvid', 'class="inputbox required" ' . '', 'value', 'text', '');
        $mycoverletters = $this->getMyCoverLetters($u_id);
        $result[0] = $myresymes;
        $result[1] = $totalresume;
        $result[2] = $mycoverletters[0];
        return $result;
    }

    function &getMyCoverLetters($u_id) {

        $db = &$this->getDBO();
        if ($u_id)
            if ((is_numeric($u_id) == false) || ($u_id == 0) || ($u_id == ''))
                return false;

        $totalcoverletters = 0;

        $query = "SELECT id, title
		FROM `#__js_job_coverletters` WHERE uid = " . $u_id;
        //echo '<br> SQL '.$query;
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum()) {
            echo $db->stderr();
            return false;
        }
        $resumes = array();
        foreach ($rows as $row) {
            $resumes[] = array('value' => $row->id, 'text' => $row->title);
            $totalcoverletters++;
        }

        $mycoverletters = JHTML::_('select.genericList', $resumes, 'coverletterid', 'class="inputbox required" ' . '', 'value', 'text', '');


        $result[0] = $mycoverletters;
        $result[1] = $totalcoverletters;
        return $result;
    }

    function storeGoldResume($uid, $resumeid) {
        global $resumedata;
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if ((is_numeric($resumeid) == false) || ($resumeid == 0) || ($resumeid == ''))
            return false;
        $db = &$this->getDBO();
        $query = "SELECT COUNT(id)
				FROM `#__js_job_resume` 
				WHERE uid = " . $uid . " AND id = " . $resumeid . " AND status = 1";
        //echo '<br> SQL '.$query;
        $db->setQuery($query);
        $resumes = $db->loadResult();
        if ($resumes <= 0)
            return 3; // company not exsit or not approved

        if ($this->canAddNewGoldResume($uid) == false)
            return 5; // can not add new gold resume

        $result = $this->GoldResumeValidation($uid, $resumeid);
        if ($result == false) {
            return 6;
        } else {
            $query = "UPDATE `#__js_job_resume` SET isgoldresume = 1 WHERE id = " . $resumeid . " AND uid = " . $uid;
            $db->setQuery($query);
            if (!$db->query())
                return false;
            else
                return true;
        }
    }

    function GoldResumeValidation($uid, $resumeid) {

        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if ((is_numeric($resumeid) == false) || ($resumeid == 0) || ($resumeid == ''))
            return false;
        $db = & JFactory::getDBO();

        $query = "SELECT COUNT(resume.id)  
		FROM #__js_job_resume  AS resume
		WHERE resume.isgoldresume=1 AND resume.uid = " . $uid . " AND resume.id = " . $resumeid;
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return true;
        else
            return false;
    }

    function storeFeaturedResume($uid, $resumeid) {
        global $resumedata;
        if ((is_numeric($resumeid) == false) || ($resumeid == 0) || ($resumeid == ''))
            return false;
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        $db = &$this->getDBO();
        $query = "SELECT COUNT(id)
				FROM `#__js_job_resume` 
				WHERE uid = " . $uid . " AND id = " . $resumeid . " AND status = 1";
        $db->setQuery($query);
        $resumes = $db->loadResult();
        if ($resumes <= 0)
            return 3; // company not exsit or not approved

        $result = $this->featuredResumeValidation($uid, $resumeid);
        if ($result == false) {
            return 6;
        } else {
            $query = "UPDATE `#__js_job_resume` SET isfeaturedresume = 1 WHERE id = " . $resumeid . " AND uid = " . $uid;
            $db->setQuery($query);
            if (!$db->query())
                return false;
            else
                return true;
        }
    }

    function featuredResumeValidation($uid, $resumeid) {

        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if ((is_numeric($resumeid) == false) || ($resumeid == 0) || ($resumeid == ''))
            return false;

        $db = & JFactory::getDBO();
        $query = "SELECT COUNT(resume.id)  
		FROM #__js_job_resume  AS resume
		WHERE resume.isfeaturedresume=1 AND resume.uid = " . $uid . " AND resume.id = " . $resumeid;
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result == 0)
            return true;
        else
            return false;
    }

    function &getJobSeekerPackageInfoById($packageid) {
        if (is_numeric($packageid) == false)
            return false;
        $db = &$this->getDBO();


        $query = "SELECT package.* FROM `#__js_job_jobseekerpackages` AS package WHERE id = " . $packageid;
        $db->setQuery($query);
        $package = $db->loadObject();

        return $package;
    }

    function updateJobSeekerPackageHistory($firstname, $lastname, $email, $amount, $referenceid
    , $tx_token, $date, $paypalstatus, $status) {
        $db = &$this->getDBO();

        $query = "UPDATE `#__js_job_jobseekerpaymenthistory`
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
        //echo '<br>sql '.$query;
        $db->setQuery($query);
        $db->query();

        return true;
    }

    function storeJobAlertSetting() { //store job alert setting
        $row = &$this->getTable('jobalertsetting');

        $db = &$this->getDBO();
        $query = "SELECT configvalue FROM `#__js_job_config` WHERE configname='job_alert_captcha'";
        $db->setQuery($query);
        $result = $db->loadObject();
        $data = JRequest::get('post');
        if ($data['uid'] == 0 && $result->configvalue == 1)
            if (!$this->common_model->performChecks()) {
                $result = 8;
                return $result;
            }
        $email = $data['contactemail'];
        if ($data['id'] == '') { // only for new 
            if ($this->emailValidation($email) == true)
                return 3;
            $config = $this->common_model->getConfigByFor('jobalert');
            $data['status'] = $config['jobalert_auto_approve'];
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
            $query = "SELECT jobalert.* FROM `#__js_job_jobalertsetting` AS jobalert  
						WHERE jobalert.id = " . $row->id;
            //echo '<br> SQL '.$query;
            $db->setQuery($query);
            $data_jobalert = $db->loadObject();
            if ($data['id'] != "" AND $data['id'] != 0)
                $data_jobalert->id = $data['id']; // for edit case
            $data_jobalert->jobalert_id = $row->id;
            $data_jobalert->authkey = $this->_client_auth_key;
            $data_jobalert->task = 'storejobalert';
            $jsjobsharingobject = new JSJobsModelJob_Sharing;
            $return_value = $jsjobsharingobject->store_JobAlertSharing($data_jobalert);
            return $return_value;
        }else {
            return true;
        }
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

    function storeMultiJobAlertCities($city_id, $alertid) { // city id comma seprated 
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

    function unSubscribeJobAlert($email) {
        $db = &$this->getDBO();
        $row = &$this->getTable('jobalertsetting');
        $returnvalue = $this->jobAlertCanUnsubscribe($email);
        if ($returnvalue == 1) {
            if ($this->_client_auth_key != "") {
                $query = "SELECT jobalert.id,jobalert.serverid FROM `#__js_job_jobalertsetting` AS jobalert
							WHERE jobalert.contactemail = " . $db->Quote($email);
                //echo '<br> SQL '.$query;
                $db->setQuery($query);
                $alert_data = $db->loadObject();
                $data['authkey'] = $this->_client_auth_key;
                $data['task'] = 'unsunscribejobalert';
                $data['id'] = $alert_data->serverid;
                $data['jobalert_id'] = $alert_data->id;
                $jsjobsharingobject = new JSJobsModelJob_Sharing;
                $return_value = $jsjobsharingobject->unsubscribe_JobAlert($data);
            }
            $query = "DELETE jobalert,acity
                        FROM `#__js_job_jobalertsetting` AS jobalert
                        LEFT JOIN `#__js_job_jobalertcities` AS acity ON acity.alertid=jobalert.id 
                        WHERE jobalert.contactemail = " . $db->Quote($email);
            $db->setQuery($query);
            if (!$db->query()) {
                return false;
            }
            if ($this->_client_auth_key != "")
                return $return_value;
            else
                return true;
        }
        else
            return $returnvalue;
    }

    function jobAlertCanUnsubscribe($email) {
        $db = &$this->getDBO();
        $result = array();

        $query = "SELECT COUNT(jobalert.id) FROM `#__js_job_jobalertsetting` AS jobalert WHERE jobalert.contactemail = " . $db->Quote($email);
        $db->setQuery($query);
        $comtotal = $db->loadResult();
        if ($comtotal > 0)
            return 1;
        else
            return 3;
    }

    function jobapply() {
        $row = &$this->getTable('jobapply');
        $data = JRequest :: get('post');
        $db = & JFactory::getDBO();
        if ($this->_client_auth_key != "") {
            $query = "SELECT id FROM #__js_job_jobs WHERE serverid = " . $data['jobid'];
            $db->setQuery($query);
            $result = $db->loadResult();
            if (!$result)
                $is_own_job = 0;
            else {
                $is_own_job = 1;
                $data['jobid'] = $result;
            }
        } else {
            $is_own_job = 1;
        }
        if ($is_own_job == 1) {

            $query = "SELECT job.raf_gender AS filter_gender,
                        job.raf_education AS filter_education,	
                        job.raf_category AS filter_category,job.raf_subcategory AS filter_subcategory,
                        job.raf_location AS filter_location	
                        FROM #__js_job_jobs AS job 
			WHERE job.id = " . $data['jobid'];
            $db->setQuery($query);
            $apply_filter_values = $db->loadObject();
            $data['action_status'] = 1;
            if ($apply_filter_values) {
                $jobquery = "SELECT job.gender,job.educationid,job.jobcategory,job.subcategoryid,job.city
				FROM #__js_job_jobs AS job 
				WHERE job.id = " . $data['jobid'];
                $db->setQuery($jobquery);
                $job = $db->loadObject();

                $resumequery = "SELECT resume.gender,resume.heighestfinisheducation,resume.job_category,resume.job_subcategory,resume.address_city
				FROM #__js_job_resume AS resume
				WHERE resume.id = " . $data['cvid'];
                $db->setQuery($resumequery);
                $resume = $db->loadObject();
                if ($apply_filter_values->filter_gender == 1) {
                    if ($job->gender == $resume->gender)
                        $data['action_status'] = 1;
                    else
                        $data['action_status'] = 2;
                }
                if ($data['action_status'] != 2) {
                    if ($apply_filter_values->filter_education == 1) {
                        if ($job->educationid == $resume->heighestfinisheducation)
                            $data['action_status'] = 1;
                        else
                            $data['action_status'] = 2;
                    }
                }
                if ($data['action_status'] != 2) {
                    if ($apply_filter_values->filter_category == 1) {
                        if ($job->jobcategory == $resume->job_category)
                            $data['action_status'] = 1;
                        else
                            $data['action_status'] = 2;
                    }
                }
                if ($data['action_status'] != 2) {
                    if ($apply_filter_values->filter_subcategory == 1) {
                        if ($job->subcategoryid == $resume->job_subcategory)
                            $data['action_status'] = 1;
                        else
                            $data['action_status'] = 2;
                    }
                }
                if ($data['action_status'] != 2) {
                    if ($apply_filter_values->filter_location == 1) {
                        $joblocation = explode(',', $job->city);
                        if (in_array($resume->address_city, $joblocation)) {
                            $data['action_status'] = 1;
                        }
                        else
                            $data['action_status'] = 2;
                    }
                }
            }
            if (!$row->bind($data)) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }

            if (!$row->check()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }

            if ($data['id'] == '') { // only for new
                $result = $this->jobApplyValidation($data['uid'], $data['jobid']);
                if ($result == true) {
                    return 3;
                }
            }
            if (!$row->store()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
            if ($data['action_status'] == 1)
                $emailrerurn = $this->common_model->sendMail($data['jobid'], $data['uid'], $data['cvid']);
        }
        if ($this->_client_auth_key != "") {
            if ($data['cvid'] != "" AND $data['cvid'] != 0) {
                $query = "select resume.serverid AS resumeserverid 
						From #__js_job_resume AS resume
						WHERE resume.id=" . $data['cvid'];
                //echo 'query'.$query;
                $db->setQuery($query);
                $resume_serverid = $db->loadResult();
                if ($resume_serverid)
                    $data['cvid'] = $resume_serverid;
                else
                    $data['cvid'] = 0;
            }
            if ($data['coverletterid'] != "" AND $data['coverletterid'] != 0) {
                $query = "select coverletter.serverid AS coverletterserverid  From #__js_job_coverletters AS coverletter WHERE coverletter.id=" . $data['coverletterid'];
                $db->setQuery($query);
                $coverletter_serverid = $db->loadResult();
                if ($coverletter_serverid)
                    $data['coverletterid'] = $coverletter_serverid;
                else
                    $data['coverletterid'] = 0;
            }
            if ($is_own_job == 1) { // own job apply on job sharing 
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
                $data['jobapply_id'] = $row->id;
                $data['authkey'] = $this->_client_auth_key;
                $data['task'] = 'storeownjobapply';
                $isownjob = 1;
                $data['isownjob'] = $isownjob;
                $jsjobsharingobject = new JSJobsModelJob_Sharing;
                $return_value = $jsjobsharingobject->store_JobapplySharing($data);
                return $return_value;
            }else {  // server job apply on job sharing 
                $data['authkey'] = $this->_client_auth_key;
                $data['task'] = 'storeserverjobapply';
                $isownjob = 0;
                $data['isownjob'] = $isownjob;
                $jsjobsharingobject = new JSJobsModelJob_Sharing;
                $return_value = $jsjobsharingobject->store_JobapplySharing($data);
                return $return_value;
            }
        } else {
            return true;
        }
    }

    function visitorJobApply($jobid, $resumeid) {
        $db = & JFactory::getDBO();
        $jobsharing = new JSJobsModelJob_Sharing;
        $row = &$this->getTable('jobapply');
        $data = array();
        $data['uid'] = $this->_uid;
        if ($this->_client_auth_key != "") {
            $db = & JFactory::getDBO();
            $query = "SELECT id FROM #__js_job_jobs 
			WHERE serverid = " . $jobid;
            //echo $query;
            $db->setQuery($query);
            $result = $db->loadResult();
            if (!$result) {
                $is_own_job = 0;
            } else {
                $is_own_job = 1;
                $jobid = $result;
            }
        } else {
            $is_own_job = 1;
        }
        $data['action_status'] = 1;
        if ($is_own_job == 1) {
            $query = "SELECT job.raf_gender AS filter_gender,
                        job.raf_education AS filter_education,	
                        job.raf_category AS filter_category,job.raf_subcategory AS filter_subcategory,
                        job.raf_location AS filter_location	
                        FROM #__js_job_jobs AS job 
                        WHERE job.id = " . $jobid;
            $db->setQuery($query);
            $apply_filter_values = $db->loadObject();
            if ($apply_filter_values) {
                $jobquery = "SELECT job.gender,job.educationid,job.jobcategory,job.subcategoryid,job.city
				FROM #__js_job_jobs AS job 
				WHERE job.id = " . $jobid;
                $db->setQuery($jobquery);
                $job = $db->loadObject();

                $resumequery = "SELECT resume.gender,resume.heighestfinisheducation,resume.job_category,resume.job_subcategory,resume.address_city
				FROM #__js_job_resume AS resume
				WHERE resume.id = " . $resumeid;
                $db->setQuery($resumequery);
                $resume = $db->loadObject();
                if ($apply_filter_values->filter_gender == 1) {
                    if ($job->gender == $resume->gender)
                        $data['action_status'] = 1;
                    else
                        $data['action_status'] = 2;
                }
                if ($data['action_status'] != 2) {
                    if ($apply_filter_values->filter_education == 1) {
                        if ($job->educationid == $resume->heighestfinisheducation)
                            $data['action_status'] = 1;
                        else
                            $data['action_status'] = 2;
                    }
                }
                if ($data['action_status'] != 2) {
                    if ($apply_filter_values->filter_category == 1) {
                        if ($job->jobcategory == $resume->job_category)
                            $data['action_status'] = 1;
                        else
                            $data['action_status'] = 2;
                    }
                }
                if ($data['action_status'] != 2) {
                    if ($apply_filter_values->filter_subcategory == 1) {
                        if ($job->subcategoryid == $resume->job_subcategory)
                            $data['action_status'] = 1;
                        else
                            $data['action_status'] = 2;
                    }
                }
                if ($data['action_status'] != 2) {
                    if ($apply_filter_values->filter_location == 1) {
                        $joblocation = explode(',', $job->city);
                        if (in_array($resume->address_city, $joblocation)) {
                            $data['action_status'] = 1;
                        }
                        else
                            $data['action_status'] = 2;
                    }
                }
            }
            if ($data['jobid'] == "")
                $row->jobid = $jobid;
            else
                $row->jobid = $data['jobid'];
            $row->action_status = $data['action_status'];
            $row->cvid = $resumeid;
            $row->apply_date = date('Y-m-d H:i:s');
            $row->resumeview = 1;
            if (!$row->store()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
            if ($data['action_status'] == 1)
                $emailrerurn = $this->common_model->sendMail($row->jobid, $data['uid'], $resumeid);
            //$emailrerurn=$this->common_model->sendMail($data['jobid'], $data['uid'],$resumeid);
        }
        if ($this->_client_auth_key != "") {
            if ($resumeid != "" AND $resumeid != 0) {
                $query = "select resume.serverid AS resumeserverid From #__js_job_resume AS resume WHERE resume.id=" . $resumeid;
                $db->setQuery($query);
                $resume_serverid = $db->loadResult();
                if ($resume_serverid)
                    $data['cvid'] = $resume_serverid;
                else
                    $data['cvid'] = 0;
            }
            if ($is_own_job == 1) { // own job apply on job sharing 
                if ($jobid != "" AND $jobid != 0) {
                    $query = "select job.serverid AS serverid From #__js_job_jobs AS job WHERE job.id=" . $jobid;
                    $db->setQuery($query);
                    $job_serverid = $db->loadResult();
                    if ($job_serverid)
                        $data['jobid'] = $job_serverid;
                    else
                        $data['jobid'] = 0;
                }
                $data['jobapply_id'] = $row->id;
                $data['apply_date'] = date('Y-m-d H:i:s');
                $data['authkey'] = $this->_client_auth_key;
                $data['task'] = 'storeownjobapply';
                $isownjob = 1;
                $data['isownjob'] = $isownjob;
                $jsjobsharingobject = new JSJobsModelJob_Sharing;
                $return_value = $jsjobsharingobject->store_JobapplySharing($data);
            }else {  // server job apply on job sharing 
                $data['jobid'] = $jobid;
                $data['apply_date'] = date('Y-m-d H:i:s');
                $data['authkey'] = $this->_client_auth_key;
                $data['task'] = 'storeserverjobapply';
                $isownjob = 0;
                $data['isownjob'] = $isownjob;
                $jsjobsharingobject = new JSJobsModelJob_Sharing;
                $return_value = $jsjobsharingobject->store_JobapplySharing($data);
            }
            if (is_array($return_value) AND !empty($return_value)) {
                if ($return_value['isjobapplystore'] == 1) {
                    if ($return_value['status'] == "Jobapply Sucessfully") {
                        $serverjobapplystatus = "ok";
                    }
                    $logarray['uid'] = $this->_uid;
                    $logarray['referenceid'] = $return_value['referenceid'];
                    $logarray['eventtype'] = $return_value['eventtype'];
                    $logarray['message'] = $return_value['message'];
                    $logarray['event'] = "Visitor Jobapply";
                    $logarray['messagetype'] = "Sucessfully";
                    $logarray['datetime'] = date('Y-m-d H:i:s');
                    $jobsharing->write_JobSharingLog($logarray);
                    $jobsharing->Update_ServerStatus($serverjobapplystatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'jobapply');
                    return true;
                } elseif ($return_value['isjobapplystore'] == 0) {
                    if ($return_value['status'] == "Data Empty") {
                        $serverjobapplystatus = "Data not post on server";
                    } elseif ($return_value['status'] == "Jobapply Saving Error") {
                        $serverjobapplystatus = "Error Jobapply Saving";
                    } elseif ($return_value['status'] == "Auth Fail") {
                        $serverjobapplystatus = "Authentication Fail";
                    }
                    $logarray['uid'] = $this->_uid;
                    $logarray['referenceid'] = $return_value['referenceid'];
                    $logarray['eventtype'] = $return_value['eventtype'];
                    $logarray['message'] = $return_value['message'];
                    $logarray['event'] = "Visitor Jobapply";
                    $logarray['messagetype'] = "Error";
                    $logarray['datetime'] = date('Y-m-d H:i:s');
                    $serverid = 0;
                    $jobsharing->write_JobSharingLog($logarray);
                    $jobsharing->Update_ServerStatus($serverjobapplystatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'jobapply');
                    return false;
                }
            } else {
                return $return_value;
            }
        } else {
            return true;
        }
    }

    function jobApplyValidation($u_id, $jobid) {
        if ((is_numeric($u_id) == false) || ($u_id == 0) || ($u_id == ''))
            return false;
        if (is_numeric($jobid) == false)
            return false;
        $db = & JFactory::getDBO();

        $query = "SELECT COUNT(id) FROM #__js_job_jobapply 
		WHERE uid = " . $u_id . " AND jobid = " . $jobid;
        //echo '<br>sql '.$query;
        $db->setQuery($query);
        $result = $db->loadResult();
        //echo '<br>r'.$result;
        if ($result == 0)
            return false;
        else
            return true;
    }

    function &getResumeViewbyId($uid, $jobid, $id, $myresume) {
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if ($jobid)
            if (is_numeric($jobid) == false)
                return false;
        $db = &$this->getDBO();
        if ($myresume == 2)
            $resume_issharing_data = 1;
        elseif ($myresume == 5) {
            $resume_issharing_data = 1;
            $jobid = 0;
        } //folderresumeview
        else
            $resume_issharing_data = 0;
        if ($resume_issharing_data == 1) {
            if ($this->_client_auth_key != "") {
				if($myresume == 3){
					$query = "SELECT id FROM #__js_job_jobs WHERE serverid = " . $jobid;
					//echo $query;
					$db->setQuery($query);
					$server_jobid = $db->loadResult();
					if ($server_jobid)
						$jobid = $server_jobid;
					$query = "SELECT id FROM #__js_job_resume WHERE serverid = " . $id;
					//echo $query;
					$db->setQuery($query);
					$server_resumeid = $db->loadResult();
					if ($server_resumeid)
						$id = $server_resumeid;
				}
            }
        }

        if ($myresume == 1) {

            $query = "SELECT COUNT(id) FROM #__js_job_resume WHERE uid = " . $uid . " AND id = " . $id;
            //echo '<br>sql '.$query;
            $db->setQuery($query);
            $total = $db->loadResult();
            if ($total == 0)
                $canview = 0;
            else
                $canview = 1;
        }else {
            if (!isset($this->_config)) {
                $this->_config = $this->common_model->getConfig('');
            }
            foreach ($this->_config as $conf) {
                if ($conf->configname == 'js_newlisting_requiredpackage')
                    $newlisting_required_package = $conf->configvalue;
            }

            if ($newlisting_required_package == 0) {
                $unlimited = 1;
            } else {
                $query = "SELECT package.viewresumeindetails, package.packageexpireindays, package.resumesearch, payment.created
                            FROM `#__js_job_employerpackages` AS package
                            JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=1)
                            WHERE payment.uid = " . $uid . "
                            AND DATE_ADD(payment.created,INTERVAL package.packageexpireindays DAY) >= CURDATE()
                            AND payment.transactionverified = 1 AND payment.status = 1";
                //echo $query;
                $db->setQuery($query);
                $jobs = $db->loadObjectList();
                $unlimited = 0;
                $canview = 0;
                $resumesearch = 0;
                $viewresumeindetails = 0;

                foreach ($jobs AS $job) {
                    if ($unlimited == 0) {
                        if ($job->viewresumeindetails != -1) {
                            $viewresumeindetails = $viewresumeindetails + $job->viewresumeindetails;
                            $resumesearch = $resumesearch + $job->resumesearch;
                        }
                        else
                            $unlimited = 1;
                    }
                }
            }

            if ($unlimited == 0) {
                if ($viewresumeindetails == 0)
                    $canview = 0; //can not add new job
                if ($jobid != '') {
                    $query = "SELECT SUM(apply.resumeview) AS totalview FROM `#__js_job_jobapply` AS apply WHERE apply.jobid = " . $jobid;
                    //echo $query;
                    $db->setQuery($query);
                    $totalview = $db->loadResult();
                    if ($viewresumeindetails >= $totalview)
                        $canview = 1; //can not add new job
                    else
                        $canview = 0;
                    if ($myresume == 3)
                        $canview = 1; // search resume
                }else {
                    if ($resumesearch > 0)
                        $canview = 1;
                    else
                        $canview = 0;
                }
            }elseif ($unlimited == 1)
                $canview = 1; // unlimited
        }
        if ($canview == 0) { // check already view this resume
            if ($jobid != '') {
                $query = "SELECT resumeview FROM `#__js_job_jobapply` AS apply WHERE apply.jobid = " . $jobid . " AND cvid = " . $id;

                $db->setQuery($query);
                $apply = $db->loadObject();
                if ($apply->resumeview == 1)
                    $canview = 1; //already view this resume
                else
                    $canview = 0;
            }
            else
                $canview = 0;
        }
        if ($canview == 1) {

            if (is_numeric($id) == false)
                return false;
            //echo '<br> Table';
            if ($this->_client_auth_key != "" && $resume_issharing_data == 1) {

                $query = "SELECT serverid FROM #__js_job_jobs WHERE id = " . $jobid;
                //echo $query;
                $db->setQuery($query);
                $_jobid = $db->loadResult();
                //$jobid = $_jobid;

                $query = "SELECT serverid FROM #__js_job_resume WHERE id = " . $id;
                //echo $query;
                $db->setQuery($query);
                $_resumeid = $db->loadResult();
                //$id = $_resumeid;
                $data_resumedetail = array();
                $data_resumedetail['uid'] = $uid;
                $data_resumedetail['jobid'] = $jobid;
                $data_resumedetail['resumeid'] = $id;
                $data_resumedetail['authkey'] = $this->_client_auth_key;
                $data_resumedetail['siteurl'] = $this->_siteurl;
                $fortask = "getresumeviewbyid";
                $jsjobsharingobject = new JSJobsModelJob_Sharing;
                $encodedata = json_encode($data_resumedetail);
                $return_server_value = $jsjobsharingobject->serverTask($encodedata, $fortask);
                if (isset($return_server_value['resumeviewbyid']) AND $return_server_value['resumeviewbyid'] == -1) { // auth fail 
                    $logarray['uid'] = $this->_uid;
                    $logarray['referenceid'] = $return_server_value['referenceid'];
                    $logarray['eventtype'] = $return_server_value['eventtype'];
                    $logarray['message'] = $return_server_value['message'];
                    $logarray['event'] = "Resume View";
                    $logarray['messagetype'] = "Error";
                    $logarray['datetime'] = date('Y-m-d H:i:s');
                    $jsjobsharingobject->write_JobSharingLog($logarray);
                    $result[0] = (object) array('id' => 0);
                    $result[1] = (object) array();
                    $result[2] = (object) array();
                    $result[3] = $this->common_model->getFieldsOrdering(3); // resume fields
                    $result[4] = 0; // can view
                    $result[5] = (object) array();
                    $result[6] = array();
                } else {
                    $result[0] = (object) $return_server_value[0];
                    $result[1] = (object) $return_server_value[1];
                    $result[2] = (object) $return_server_value[2];
                    $result[3] = $this->common_model->getFieldsOrdering(3); // resume fields
                    $result[4] = 1; // can view
                    $result[5] = (object) $return_server_value[5];
                    $resumeuserfields = $return_server_value[6];
                    //$result[6] = json_decode($resumeuserfields['userfields']);
                    $result[6] = json_decode($resumeuserfields['userfields']);
                }
            } else {
                $query = "SELECT app.* , cat.cat_title AS categorytitle, salary.rangestart, salary.rangeend, jobtype.title AS jobtypetitle
                            ,heighesteducation.title AS heighesteducationtitle
                            , nationality_country.name AS nationalitycountry
                            , address_city.name AS address_city2 ,  address_state.name AS address_state2 , address_country.name AS address_country
                            , address1_city.name AS address1_city2 , address1_state.name AS address1_state2 , address1_country.name AS address1_country
                            , address2_city.name AS address2_city2 , address2_state.name AS address2_state2 , address2_country.name AS address2_country
                            , currency.symbol
                            , CONCAT(app.alias,'-',app.id) AS aliasid 
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
                if ($jobid != '') {
                    $query = "UPDATE `#__js_job_jobapply` SET resumeview = 1 WHERE jobid = " . $jobid . " AND cvid = " . $id;
                    $db->setQuery($query);
                    $db->query();
                }

                $query = "UPDATE `#__js_job_resume` SET hits = hits + 1 WHERE id = " . $id;
                $db->setQuery($query);
                if (!$db->query()) {
                    //return false;
                }
                $coverletter = null;
                if ($jobid != '') {
                    //Select the cover letter id
                    $query = "SELECT cl.coverletterid,cl.apply_date FROM `#__js_job_jobapply` AS cl WHERE cl.jobid = " . $jobid . " AND cl.cvid = " . $id;
                    $db->setQuery($query);
                    $coverletter = $db->loadObject();
                }
                $result[3] = $this->common_model->getFieldsOrdering(3); // resume fields
                $result[4] = 1; // can view
                $result[5] = $coverletter;
                $fieldfor = 3;
                $resume_userfields = $this->common_model->getUserFieldsForView($fieldfor, $id); // company fields, id
                $result[6] = $resume_userfields;
            }
        } else {
            $result[4] = 0; // can not view
        }
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

    function storeCoverLetter() {
        global $resumedata;
        $row = &$this->getTable('coverletter');
        $data = JRequest :: get('post');

        if (!empty($data['alias']))
            $c_l_alias = $data['alias'];
        else
            $c_l_alias = $data['title'];

        $c_l_alias = strtolower(str_replace(' ', '-', $c_l_alias));
        $data['alias'] = $c_l_alias;

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
            $query = "SELECT cvletter.* FROM `#__js_job_coverletters` AS cvletter  
						WHERE cvletter.id = " . $row->id;
            //echo '<br> SQL '.$query;
            $db->setQuery($query);
            $data_cvletter = $db->loadObject();
            if ($data['id'] != "" AND $data['id'] != 0)
                $data_cvletter->id = $data['id']; // for edit case
            $data_cvletter->coverletter_id = $row->id;
            $data_cvletter->authkey = $this->_client_auth_key;
            $data_cvletter->task = 'storecoverletter';
            $jsjobsharingobject = new JSJobsModelJob_Sharing;
            $return_value = $jsjobsharingobject->store_CoverLetterSharing($data_cvletter);
            return $return_value;
        }else {
            return true;
        }
    }

    function storeJobSearch($data) {
        global $resumedata;
        $row = &$this->getTable('jobsearch');

        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        $returnvalue = $this->canAddNewJobSearch($data['uid']);
        if ($returnvalue == 0)
            return 3; //not allowed save new search
        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());
            echo $this->_db->getErrorMsg();
            return false;
        }
        return true;
    }

    function canAddNewJobSearch($uid) {
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        $db = &$this->getDBO();
        if (!isset($this->_config)) {
            $this->_config = $this->common_model->getConfig('');
        }
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'js_newlisting_requiredpackage')
                $newlisting_required_package = $conf->configvalue;
        }

        if ($newlisting_required_package == 0) {
            return 1;
        } else {
            $query = "SELECT COUNT(search.id) AS totalsearches, role.savesearchjob
			FROM `#__js_job_roles` AS role
			JOIN `#__js_job_userroles` AS userrole ON userrole.role = role.id
			LEFT JOIN `#__js_job_jobsearches` AS search ON userrole.uid = search.uid 
			WHERE userrole.uid = " . $uid . " GROUP BY role.savesearchjob";
            //echo $query;
            $db->setQuery($query);
            $job = $db->loadObject();
            if ($job) {
                if ($job->savesearchjob == -1)
                    return 1;
                else {
                    if ($job->totalsearch < $job->savesearchjob)
                        return 1;
                    else
                        return 0;
                }
            }
            return 0;
        }
    }

    function deleteJobSearch($searchid, $uid) {
        $db = &$this->getDBO();
        $row = &$this->getTable('jobsearch');

        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (is_numeric($searchid) == false)
            return false;

        $query = "SELECT COUNT(search.id) FROM `#__js_job_jobsearches` AS search WHERE search.id = " . $searchid . " AND search.uid = " . $uid;
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

    function deleteCoverLetter($coverletterid, $uid) {
        $db = &$this->getDBO();
        $row = &$this->getTable('coverletter');
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (is_numeric($coverletterid) == false)
            return false;
        $server_coverletter_id = 0;
        if ($this->_client_auth_key != "") {
            $query = "SELECT letter.serverid AS id FROM `#__js_job_coverletters` AS letter 
						WHERE letter.id = " . $coverletterid;
            //echo '<br> SQL '.$query;
            $db->setQuery($query);
            $s_c_l_id = $db->loadResult();
            $server_coverletter_id = $s_c_l_id;
        }
        $query = "SELECT COUNT(letter.id) FROM `#__js_job_coverletters` AS letter WHERE letter.id = " . $coverletterid . " AND letter.uid = " . $uid;
        //echo '<br> SQL '.$query;
        $db->setQuery($query);
        $total = $db->loadResult();

        if ($total > 0) { // this search is same user
            if (!$row->delete($coverletterid)) {
                $this->setError($row->getErrorMsg());
                return false;
            }
            if ($server_coverletter_id != 0) {
                $data = array();
                $data['id'] = $server_coverletter_id;
                $data['referenceid'] = $coverletterid;
                $data['uid'] = $this->_uid;
                $data['authkey'] = $this->_client_auth_key;
                $data['siteurl'] = $this->_siteurl;
                $data['task'] = 'deletecoverletter';
                $jsjobsharingobject = new JSJobsModelJob_Sharing;
                $return_value = $jsjobsharingobject->delete_CoverletterSharing($data);
                return $return_value;
            }
        }
        else
            return 2;

        return true;
    }

    function deleteResume($resumeid, $uid) {
        $db = &$this->getDBO();
        $row = &$this->getTable('resume');
        $data = JRequest :: get('post');

        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (is_numeric($resumeid) == false)
            return false;
        $serverresumeid = 0;
        if ($this->_client_auth_key != "") {
            $query = "SELECT resume.serverid AS id 
						FROM `#__js_job_resume` AS resume
						WHERE resume.id = " . $resumeid;
            $db->setQuery($query);
            $s_resume_id = $db->loadResult();
            $serverresumeid = $s_resume_id;
        }
        $returnvalue = $this->resumeCanDelete($resumeid, $uid);
        if ($returnvalue == 1) {
            if (!$row->delete($resumeid)) {
                $this->setError($row->getErrorMsg());
                return false;
            }
            $this->common_model->deleteUserFieldData($resumeid);
            if ($serverresumeid != 0) {
                $data = array();
                $data['id'] = $serverresumeid;
                $data['referenceid'] = $resumeid;
                $data['uid'] = $this->_uid;
                $data['authkey'] = $this->_client_auth_key;
                $data['siteurl'] = $this->_siteurl;
                $data['task'] = 'deleteresume';
                $jsjobsharingobject = new JSJobsModelJob_Sharing;
                $return_value = $jsjobsharingobject->delete_ResumeSharing($data);
                return $return_value;
            }
        }
        else
            return $returnvalue;

        return true;
    }

    function resumeCanDelete($resumeid, $uid) {
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        $db = &$this->getDBO();

        $query = "SELECT COUNT(resume.id) FROM `#__js_job_resume` AS resume WHERE resume.id = " . $resumeid . " AND resume.uid = " . $uid;
        $db->setQuery($query);
        $resumetotal = $db->loadResult();

        if ($resumetotal > 0) { // this resume is same user
            $query = "SELECT 
                        ( SELECT COUNT(id) FROM `#__js_job_jobapply` WHERE cvid = " . $resumeid . ") 
                        + ( SELECT COUNT(id) FROM `#__js_job_resume` AS fr WHERE fr.isfeaturedresume=1 AND fr.id = " . $resumeid . ") 
                        + ( SELECT COUNT(id) FROM `#__js_job_resume` AS gr WHERE gr.isgoldresume=1 AND gr.id = " . $resumeid . ")
                        AS total ";
            //echo '<br> SQL '.$query;
            $db->setQuery($query);
            $total = $db->loadResult();

            if ($total > 0)
                return 2;
            else
                return 1;
        }
        else
            return 3; // 	this resume is not of this user		
    }

    function storeResume($jobid) {
        global $resumedata;
        $jobsharing = new JSJobsModelJob_Sharing;
        $row = &$this->getTable('resume');
        $resumedata = JRequest :: get('post');
        //	if ( !$resumedata['id'] ){
        if (!$this->_config)
            $this->_config = $this->common_model->getConfig('');
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'empautoapprove')
                if (!$resumedata['id'])
                    $resumedata['status'] = $conf->configvalue;
            if ($conf->configname == 'resume_photofilesize')
                $photofilesize = $conf->configvalue;
            if ($conf->configname == 'date_format')
                $dateformat = $conf->configvalue;
        }
        //	}
        //spam checking
        $config = $this->common_model->getConfigByFor('default');
        if ($resumedata['uid'] == 0 && $config['resume_captcha'] == 1)
            if (!$this->common_model->performChecks()) {
                $result = 8;
                return $result;
            }


        if ($dateformat == 'm-d-Y') {
            if ($resumedata['date_start'] != '') {
                $arr = explode('-', $resumedata['date_start']);
                $data['date_start'] = $arr[0] . '/' . $arr[1] . '/' . $arr[2];
            }
            if ($resumedata['date_of_birth'] != '') {
                $arr = explode('-', $resumedata['date_of_birth']);
                $resumedata['date_of_birth'] = $arr[0] . '/' . $arr[1] . '/' . $arr[2];
            }
        } elseif ($dateformat == 'd-m-Y') {
            if ($resumedata['date_start'] != '') {
                $arr = explode('-', $resumedata['date_start']);
                $resumedata['date_start'] = $arr[2] . '-' . $arr[1] . '-' . $arr[0];
            }
            if ($resumedata['date_of_birth'] != '') {
                $arr = explode('-', $resumedata['date_of_birth']);
                $resumedata['date_of_birth'] = $arr[2] . '-' . $arr[1] . '-' . $arr[0];
            }
        }
        if ($resumedata['date_start'] != '') {
            $resumedata['date_start'] = date('Y-m-d H:i:s', strtotime($resumedata['date_start']));
        }
        if ($resumedata['date_of_birth'] != '') {
            $resumedata['date_of_birth'] = date('Y-m-d H:i:s', strtotime($resumedata['date_of_birth']));
        }

        $resumedata['resume'] = JRequest::getVar('resume', '', 'post', 'string', JREQUEST_ALLOWRAW);
        if ($_FILES['photo']['size'] > 0) {
            $uploadfilesize = $_FILES['photo']['size'];
            $uploadfilesize = $uploadfilesize / 1024; //kb
            if ($uploadfilesize > $photofilesize) { // logo
                return 7; // file size error	
            }
        }

        if ($_FILES['resumefile']['size'] > 0) {
            $file_name = $_FILES['resumefile']['name']; // file name
            $file_size = $_FILES['resumefile']['size']; // file size
            $file_type = $_FILES['resumefile']['type']; // mime type of file determined by php

            $resumedata['filename'] = $file_name;
            $resumedata['filesize'] = $file_size;
            $resumedata['filetype'] = $file_type;

            $resumedata['filecontent'] = '';
        } else {
            if ($resumedata['deleteresumefile'] == 1) {
                $resumedata['filename'] = '';
                $resumedata['filecontent'] = '';
            }
        }
        if ($_FILES['photo']['size'] > 0) {
            $file_name = $_FILES['photo']['name']; // file name
            $resumedata['photo'] = $file_name;
        } else {
            if ($resumedata['deletephoto'] == 1) {
                $resumedata['photo'] = '';
            }
        }

        if (!empty($resumedata['alias']))
            $resumealias = $resumedata['alias'];
        else
            $resumealias = $resumedata['application_title'];

        $resumealias = strtolower(str_replace(' ', '-', $resumealias));
        $resumedata['alias'] = $resumealias;

        if (!$row->bind($resumedata)) {
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
        $this->common_model->storeUserFieldData($resumedata, $row->id);
        if ($resumedata['id'] == '')
            $this->common_model->sendMailtoAdmin($row->id, $resumedata['uid'], 3); //only for new


        if ($this->_client_auth_key != "") {
            $resume_picture = array();
            $resume_file = array();

            $db = &$this->getDBO();
            $query = "SELECT resume.* FROM `#__js_job_resume` AS resume WHERE resume.id = " . $row->id;
            //echo '<br> SQL '.$query;
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
            $jsjobsharingobject = new JSJobsModelJob_Sharing;
            $return_value = $jsjobsharingobject->store_ResumeSharing($data_resume);
            if ($return_value['isresumestore'] == 0)
                return $return_value;
            $status_resume_pic = "";
            if ($photomismatch != 1) {

                if ($_FILES['photo']['size'] > 0)
                    $return_value_resume_pic = $jsjobsharingobject->store_ResumePicSharing($data_resume, $resume_picture);
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
                    $return_value_resume_file = $jsjobsharingobject->store_ResumeFileSharing($data_resume, $resume_file);
                if (isset($return_value_resume_file)) {
                    if ($return_value_resume_file['isresumestore'] == 0 OR $return_value_resume_file == false)
                        $status_resume_file = -1;
                    else
                        $status_resume_file = 1;
                }
            }

            if (($status_resume_pic == -1 AND $status_resume_file == -1) OR ($filemismatch == 1 AND $photomismatch == 1)) {// file type mismatch 
                $return_value['message'] = "Resume Save But Error Uploading Resume File and Picture";
            } elseif (($status_resume_pic == -1) OR ($photomismatch == 1)) {// file type mismatch 
                $return_value['message'] = "Resume Save But Error Uploading Picture";
            } elseif (($status_resume_file == -1) OR ($filemismatch == 1)) { // file type mismatch 
                $return_value['message'] = "Resume Save But Error Uploading file";
            }
            if ($jobid) { // for visitor case 
                if ($return_value['isresumestore'] == 1) {
                    if ($return_value['status'] == "Resume Edit") {
                        $serverresumestatus = "ok";
                    } elseif ($return_value['status'] == "Resume Add") {
                        $serverresumestatus = "ok";
                    } elseif ($return_data['status'] == "Edit Resume Userfield") {
                        $serverresumestatus = "ok";
                    } elseif ($return_data['status'] == "Add Resume Userfield") {
                        $serverresumestatus = "ok";
                    }
                    $logarray['uid'] = $this->_uid;
                    $logarray['referenceid'] = $return_value['referenceid'];
                    $logarray['eventtype'] = $return_value['eventtype'];
                    $logarray['message'] = $return_value['message'];
                    $logarray['event'] = "Visitor Resume";
                    $logarray['messagetype'] = "Sucessfully";
                    $logarray['datetime'] = date('Y-m-d H:i:s');
                    $jobsharing->write_JobSharingLog($logarray);
                    $jobsharing->Update_ServerStatus($serverresumestatus, $logarray['referenceid'], $return_value['serverid'], $logarray['uid'], 'resume');
                    $resume_update = 1;
                } elseif ($return_value['isresumestore'] == 0) {
                    if ($return_value['status'] == "Data Empty") {
                        $serverresumestatus = "Data not post on server";
                    } elseif ($return_value['status'] == "Resume Saving Error") {
                        $serverresumestatus = "Error Resume Saving";
                    } elseif ($return_value['status'] == "Auth Fail") {
                        $serverresumestatus = "Authentication Fail";
                    } elseif ($return_data['status'] == "Error Save Resume Userfield") {
                        $serverresumestatus = "Error Save Resume Userfield";
                    } elseif ($return_value['status'] == "Improper Resume name") {
                        $serverresumestatus = "Improper Resume name";
                    }
                    $logarray['uid'] = $this->_uid;
                    $logarray['referenceid'] = $return_value['referenceid'];
                    $logarray['eventtype'] = $return_value['eventtype'];
                    $logarray['message'] = $return_value['message'];
                    $logarray['event'] = "Visitor Resume";
                    $logarray['messagetype'] = "Error";
                    $logarray['datetime'] = date('Y-m-d H:i:s');
                    $serverid = 0;
                    $jobsharing->write_JobSharingLog($logarray);
                    $jobsharing->Update_ServerStatus($serverresumestatus, $logarray['referenceid'], $serverid, $logarray['uid'], 'resume');
                    $resume_update = 0;
                }
                if ($resume_update == 1) {
                    if ($jobid)
                        $returnvalue = $this->visitorJobApply($jobid, $row->id);
                    return $returnvalue;
                }else {
                    return false;
                }
            } else {
                return $return_value;
            }
        } else {
            if ($jobid)
                $returnvalue = $this->visitorJobApply($jobid, $row->id);
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
        $iddir = 'resume_' . $id;
        if (!isset($this->_config))
            $this->_config = $this->common_model->getConfig('');

        foreach ($this->_config as $conf) {
            if ($conf->configname == 'data_directory')
                $datadirectory = $conf->configvalue;
        }
        if ($_FILES['resumefile']['size'] > 0) {
            $file_name = $_FILES['resumefile']['name']; // file name
            $file_tmp = $_FILES['resumefile']['tmp_name']; // actual location
            $file_size = $_FILES['resumefile']['size']; // file size
            $file_type = $_FILES['resumefile']['type']; // mime type of file determined by php
            $file_error = $_FILES['resumefile']['error']; // any error!. get reason here

            if (!empty($file_tmp)) { // only MS office and text file is accepted.
                $ext = $this->common_model->getExtension($file_name);
                if (($ext != "txt") && ($ext != "doc") && ($ext != "docx") && ($ext != "pdf") && ($ext != "opt") && ($ext != "rtf"))
                    return 6; //file type mistmathc
            }

            $path = JPATH_BASE . '/' . $datadirectory;
            if (!file_exists($path)) { // creating resume directory
                $this->common_model->makeDir($path);
            }
            $path = $path . '/data';
            if (!file_exists($path)) { // create user directory
                $this->common_model->makeDir($path);
            }
            $path = $path . '/jobseeker';
            if (!file_exists($path)) { // create user directory
                $this->common_model->makeDir($path);
            }
            $userpath = $path . '/' . $iddir;
            if (!file_exists($userpath)) { // create user directory
                $this->common_model->makeDir($userpath);
            }
            $userpath = $path . '/' . $iddir . '/resume';
            if (!file_exists($userpath)) { // create user directory
                $this->common_model->makeDir($userpath);
            }
            $files = glob($userpath . '/*.*');
            array_map('unlink', $files);  //delete all file in user directory

            move_uploaded_file($file_tmp, $userpath . '/' . $file_name);
            return $userpath . '/' . $file_name;
            return 1;
        } else {
            if ($resumedata['deleteresumefile'] == 1) {
                $path = JPATH_BASE . '/' . $datadirectory . '/data/jobseeker';
                //$path =JPATH_BASE.'/components/com_jsjobs/data/jobseeker';
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

    function uploadPhoto($id) {
        if (is_numeric($id) == false)
            return false;
        global $resumedata;
        $db = & JFactory::getDBO();
        $iddir = 'resume_' . $id;
        if (!isset($this->_config))
            $this->_config = $this->common_model->getConfig('');

        foreach ($this->_config as $conf) {
            if ($conf->configname == 'data_directory')
                $datadirectory = $conf->configvalue;
        }
        if ($_FILES['photo']['size'] > 0) {
            $file_name = $_FILES['photo']['name']; // file name
            $file_tmp = $_FILES['photo']['tmp_name']; // actual location
            $file_size = $_FILES['photo']['size']; // file size
            $file_type = $_FILES['photo']['type']; // mime type of file determined by php
            $file_error = $_FILES['photo']['error']; // any error!. get reason here

            if (!empty($file_tmp)) {
                $ext = $this->common_model->getExtension($file_name);
                if (($ext != "gif") && ($ext != "jpg") && ($ext != "jpeg") && ($ext != "png"))
                    return 6; //file type mistmathc
            }

            $path = JPATH_BASE . '/' . $datadirectory;
            if (!file_exists($path)) { // creating resume directory
                $this->common_model->makeDir($path);
            }
            $path = $path . '/data';
            if (!file_exists($path)) { // creating resume directory
                $this->common_model->makeDir($path);
            }
            $path = $path . '/jobseeker';
            if (!file_exists($path)) { // creating resume directory
                $this->common_model->makeDir($path);
            }
            $userpath = $path . '/' . $iddir;
            if (!file_exists($userpath)) { // create user directory
                $this->common_model->makeDir($userpath);
            }
            $userpath = $path . '/' . $iddir . '/photo';
            if (!file_exists($userpath)) { // create user directory
                $this->common_model->makeDir($userpath);
            }
            $files = glob($userpath . '/*.*');
            array_map('unlink', $files);  //delete all file in user directory

            move_uploaded_file($file_tmp, $userpath . '/' . $file_name);
            return $userpath . '/' . $file_name;
            return 1;
        } else {
            if ($resumedata['deletephoto'] == 1) {
                $path = JPATH_BASE . '/' . $datadirectory . '/data/jobseeker';
                $userpath = $path . '/' . $iddir . '/photo';
                $files = glob($userpath . '/*.*');
                array_map('unlink', $files);
                $resumedata['photo'] = '';
            } else {
                
            }
            return 1;
        }
    }

    function canSetJobAlert($uid) {
        $db = &$this->getDBO();
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (!isset($this->_config)) {
            $this->_config = $this->common_model->getConfig('');
        }
        $newlisting_required_package = 1;
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'js_newlisting_requiredpackage')
                $newlisting_required_package = $conf->configvalue;
        }
        if ($newlisting_required_package == 0) {
            $allow = 1;
            return $allow;
        } else {
            $query = "SELECT package.id AS packageid, package.jobalertsetting, package.packageexpireindays, payment.id AS paymentid, payment.created
                        FROM `#__js_job_jobseekerpackages` AS package
                        JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=2)
                        WHERE payment.uid = " . $uid . "
                        AND DATE_ADD(payment.created,INTERVAL package.packageexpireindays DAY) >= CURDATE()
                        AND payment.transactionverified = 1 AND payment.status = 1";
            $db->setQuery($query);
            $packages = $db->loadObjectList();
            $allow = 0;
            if (isset($packages)) {
                foreach ($packages AS $pack) {
                    if ($pack->jobalertsetting == 1)
                        $allow = 1;
                }
            }
            return $allow;
        }
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

    function setAllExport($jobid) {
        $db = &$this->getDBO();
        if (is_numeric($jobid) == false)
            return false;
        if (($jobid == 0) || ($jobid == ''))
            return false;
        //for job title
        if ($this->_client_auth_key != "") {

            $expdata['jobid'] = $jobid;
            $expdata['authkey'] = $this->_client_auth_key;
            $expdata['siteurl'] = $this->_siteurl;


            $fortask = "setexportallresume";
            $jsjobsharingobject = new JSJobsModelJob_Sharing;
            $encodedata = json_encode($expdata);
            $return_server_value = $jsjobsharingobject->serverTask($encodedata, $fortask);
            if (isset($return_server_value['exportallresume']) AND $return_server_value['exportallresume'] == -1) { // auth fail 
                $logarray['uid'] = $this->_uid;
                $logarray['referenceid'] = $return_server_value['referenceid'];
                $logarray['eventtype'] = $return_server_value['eventtype'];
                $logarray['message'] = $return_server_value['message'];
                $logarray['event'] = "Export All Resume";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jsjobsharingobject->write_JobSharingLog($logarray);
                $result[0] = "";
            } else {
                $result = array();
                if ($return_server_value) {
                    $result = $return_server_value['exportresumedata'];
                } else {
                    $result[0] = "";
                }

                // Empty data vars
                $data = "";
                // We need tabbed data
                $sep = "\t";
                $fields = (array_keys($result[0]));
                // Count all fields(will be the collumns
                $columns = count($fields);
                $data .= "Job Title" . $sep . $result[0]['job_title'] . "\n";
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
        } else {

            $query = "SELECT title FROM `#__js_job_jobs` WHERE id = " . $jobid;
            $db->setQuery($query);
            $jobtitle = $db->loadResult();
            $result = $this->getExportAllResumesByJobId($jobid);
            $result = $db->loadAssocList();




            if (!$result) {
                $this->setError($this->_db->getErrorMsg());
                //echo $this->_db->getErrorMsg();exit;
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
    }

    function setExport($jobid, $resumeid) {
        $db = &$this->getDBO();
        if (is_numeric($jobid) == false)
            return false;
        if (($jobid == 0) || ($jobid == ''))
            return false;
        if ($this->_client_auth_key != "") {

            $expdata['jobid'] = $jobid;
            $expdata['resumeid'] = $resumeid;
            $expdata['authkey'] = $this->_client_auth_key;
            $expdata['siteurl'] = $this->_siteurl;
            $fortask = "setexportresume";
            $jsjobsharingobject = new JSJobsModelJob_Sharing;
            $encodedata = json_encode($expdata);
            $return_server_value = $jsjobsharingobject->serverTask($encodedata, $fortask);
            if (isset($return_server_value['exportresume']) AND $return_server_value['exportresume'] == -1) { // auth fail 
                $logarray['uid'] = $this->_uid;
                $logarray['referenceid'] = $return_server_value['referenceid'];
                $logarray['eventtype'] = $return_server_value['eventtype'];
                $logarray['message'] = $return_server_value['message'];
                $logarray['event'] = "Export Resume";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jsjobsharingobject->write_JobSharingLog($logarray);
                $result[0] = "";
            } else {
                $result = array();
                if ($return_server_value) {
                    $result = $return_server_value['exportresumedata'];
                } else {
                    $result[0] = "";
                }

                // Empty data vars
                $data = "";
                // We need tabbed data
                $sep = "\t";
                $fields = (array_keys($result[0]));
                // Count all fields(will be the collumns
                $columns = count($fields);
                $data .= "Job Title" . $sep . $result[0]['job_title'] . "\n";
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
        } else {

            //for job title
            $query = "SELECT title FROM `#__js_job_jobs` WHERE id = " . $jobid;
            $db->setQuery($query);
            $jobtitle = $db->loadResult();

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
    }

    function makeArrayForExport($result) {
        foreach ($result as $r) {
            $myarr['Application Title'] = $r['application_title'];
            $myarr['First Name'] = $r['first_name'];
            $myarr['Last Name'] = $r['last_name'];
            $myarr['Middle Name'] = $r['middle_name'];
            $myarr['Range Start'] = $r['rangestart'];
            $myarr['Heighest Education Title'] = $r['heighesteducationtitle'];
            if ($r['gender'] == 1)
                $myarr['Gender'] = JText::_('JS_MALE'); elseif ($r['gender'] == 2)
                $myarr['Gender'] = JText::_('JS_FEMALE');
            else
                $myarr['Gender'] = JText::_('JS_DOES_NOT_MATTER');
            $myarr['Email Address'] = $r['email_address'];
            $myarr['Home Phone'] = $r['home_phone'];
            $myarr['Work Phone'] = $r['work_phone'];
            $myarr['Cell'] = $r['cell'];
            $myarr['I\'am Available'] = $r['iamavailable'];
            if ($r['searchable'] == 1)
                $myarr['Searchable'] = JText::_('JS_YES');
            else
                $myarr['Searchable'] = JText::_('JS_NO');
            $myarr['Job Category'] = $r['categorytitle'];
            $myarr['Job Salaryrange'] = $r['rangestart'] . '-' . $r['rangeend'];
            $myarr['Jobtype'] = $r['jobtypetitle'];
            if ($r['address_city2'])
                $myarr['Address City'] = $r['address_city2'];
            else
                $myarr['Address City'] = $r['address_city'];
            if ($r['address_state2'])
                $myarr['Address State'] = $r['address_state2'];
            else
                $myarr['Address State'] = $r['address_state'];
            if ($r['address_country2'])
                $myarr['Address Country'] = $r['address_country2'];
            else
                $myarr['Address Country'] = $r['address_country'];
            $myarr['Address Zipcode'] = $r['address_zipcode'];
            $myarr['Address'] = $r['address'];
            $myarr['Institute'] = $r['institute'];
            if ($r['institute_city2'])
                $myarr['Institute City'] = $r['institute_city2'];
            else
                $myarr['Institute City'] = $r['institute_city'];
            if ($r['institute_state2'])
                $myarr['Institute State'] = $r['institute_state2'];
            else
                $myarr['Institute State'] = $r['institute_state'];
            if ($r['institute_country2'])
                $myarr['Institute Country'] = $r['institute_country2'];
            else
                $myarr['Institute Country'] = $r['institute_country'];
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
            if ($r['employer_city2'])
                $myarr['Employer City'] = $r['employer_city2'];
            else
                $myarr['Employer City'] = $r['employer_city'];
            if ($r['employer_state2'])
                $myarr['Employer State'] = $r['employer_state2'];
            else
                $myarr['Employer State'] = $r['employer_state'];
            if ($r['employer_country2'])
                $myarr['Employer Country'] = $r['employer_country2'];
            else
                $myarr['Employer Country'] = $r['employer_country'];
            $myarr['Employer Zip'] = $r['employer_zip'];
            $myarr['Employer Phone'] = $r['employer_phone'];
            $myarr['Employer Address'] = $r['employer_address'];
            $myarr['Institute-1'] = $r['institute1'];
            if ($r['institute1_city2'])
                $myarr['Institute-1 City'] = $r['institute1_city2'];
            else
                $myarr['Institute-1 city'] = $r['institute1_city'];
            if ($r['institute1_state2'])
                $myarr['Institute-1 State'] = $r['institute1_state2'];
            else
                $myarr['Institute-1 State'] = $r['institute1_state'];
            if ($r['institute1_country2'])
                $myarr['Institute-1 Country'] = $r['institute1_country2'];
            else
                $myarr['Institute-1 Country'] = $r['institute1_country'];
            $myarr['Institute-1 Address'] = $r['institute1_address'];
            $myarr['Institute-1 Certificate Name'] = $r['institute1_certificate_name'];
            $myarr['Institute-2'] = $r['institute2'];
            if ($r['institute2_city2'])
                $myarr['Institute-2 City'] = $r['institute2_city2'];
            else
                $myarr['Institute-2 City'] = $r['institute2_city'];
            if ($r['institute2_state2'])
                $myarr['Institute-2 State'] = $r['institute2_state2'];
            else
                $myarr['Institute-2 State'] = $r['institute2_state'];
            if ($r['institute2_country2'])
                $myarr['Institute-2 Country'] = $r['institute2_country2'];
            else
                $myarr['Institute-2 Country'] = $r['institute2_country'];
            $myarr['Institute-2 Address'] = $r['institute2_address'];
            $myarr['Institute-2 Certificate Name'] = $r['institute2_certificate_name'];
            $myarr['Institute-2 Study Area'] = $r['institute2_study_area'];
            $myarr['Institute-3'] = $r['institute3'];
            if ($r['institute3_city2'])
                $myarr['Institute-3 City'] = $r['institute3_city2'];
            else
                $myarr['Institute-3 City'] = $r['institute3_city'];
            if ($r['institute3_state2'])
                $myarr['Institute-3 State'] = $r['institute3_state2'];
            else
                $myarr['Institute-3 State'] = $r['institute3_state'];
            if ($r['institute3_country2'])
                $myarr['Institute-3 Country'] = $r['institute3_country2'];
            else
                $myarr['Institute-3 Country'] = $r['institute3_country'];
            $myarr['Institute-3 Address'] = $r['institute3_address'];
            $myarr['Institute-3 Study Area'] = $r['institute3_study_area'];
            $myarr['Employer-1'] = $r['employer1'];
            $myarr['Employer-1 Position'] = $r['employer1_position'];
            $myarr['Employer-1 Resp'] = $r['employer1_resp'];
            $myarr['Employer-1 Pay Upon Leaving'] = $r['employer1_pay_upon_leaving'];
            $myarr['Employer-1 Supervisor'] = $r['employer1_supervisor'];
            $myarr['Employer-1 From Date'] = $r['employer1_from_date'];
            $myarr['Employer-1 To Date'] = $r['employer1_to_date'];
            if ($r['employer1_city2'])
                $myarr['Employer-1 City'] = $r['employer1_city2'];
            else
                $myarr['Employer-1 City'] = $r['employer1_city'];
            if ($r['employer1_state2'])
                $myarr['Employer-1 State'] = $r['employer1_state2'];
            else
                $myarr['Employer-1 State'] = $r['employer1_state'];
            if ($r['employer1_country2'])
                $myarr['Employer-1 Country'] = $r['employer1_country2'];
            else
                $myarr['Employer-1 Country'] = $r['employer1_country'];
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
            if ($r['employer2_city2'])
                $myarr['Employer-2 City'] = $r['employer2_city2'];
            else
                $myarr['Employer-2 City'] = $r['employer2_city'];
            if ($r['employer2_state2'])
                $myarr['Employer-2 State'] = $r['employer2_state2'];
            else
                $myarr['Employer-2 State'] = $r['employer2_state'];
            if ($r['employer2_country2'])
                $myarr['Employer-2 Country'] = $r['employer2_country2'];
            else
                $myarr['Employer-2 Country'] = $r['employer2_country'];
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
            if ($r['employer3_city2'])
                $myarr['Employer-3 City'] = $r['employer3_city2'];
            else
                $myarr['Employer-3 City'] = $r['employer3_city'];
            if ($r['employer3_state2'])
                $myarr['Employer-3 State'] = $r['employer3_state2'];
            else
                $myarr['Employer-3 State'] = $r['employer3_state'];
            if ($r['employer3_country2'])
                $myarr['Employer-3 Country'] = $r['employer3_country2'];
            else
                $myarr['Employer-3 Country'] = $r['employer3_country'];
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
            if ($r['date_start'] != '0000-00-00 00:00:00' || $r['date_start'] != '')
                $myarr['Date Start'] = $r['date_start'];
            else
                $myarr['Date Start'] = '';
            if ($r['date_of_birth'] != '0000-00-00 00:00:00' || $r['date_of_birth'] != '')
                $myarr['Date Of Birth'] = $r['date_of_birth'];
            else
                $myarr['Date Of Birth'] = '';
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
            if ($r['reference_city2'])
                $myarr['Reference City'] = $r['reference_city2'];
            else
                $myarr['Reference City'] = $r['reference_city'];
            if ($r['reference_state2'])
                $myarr['Reference State'] = $r['reference_state2'];
            else
                $myarr['Reference State'] = $r['reference_state'];
            if ($r['reference_country2'])
                $myarr['Reference Country'] = $r['reference_country2'];
            else
                $myarr['Reference Country'] = $r['reference_country'];
            $myarr['Reference Zipcode'] = $r['reference_zipcode'];
            $myarr['Reference Address'] = $r['reference_address'];
            $myarr['Reference Phone'] = $r['reference_phone'];
            $myarr['Reference Relation'] = $r['reference_relation'];
            $myarr['Reference Years'] = $r['reference_years'];
            $myarr['Reference-1'] = $r['reference1'];
            $myarr['Reference-1 Name'] = $r['reference1_name'];
            if ($r['reference1_city2'])
                $myarr['Reference-1 City'] = $r['reference1_city2'];
            else
                $myarr['Reference-1 City'] = $r['reference1_city'];
            if ($r['reference1_state2'])
                $myarr['Reference-1 State'] = $r['reference1_state2'];
            else
                $myarr['Reference-1 State'] = $r['reference1_state'];
            if ($r['reference1_country2'])
                $myarr['Reference-1 Country'] = $r['reference1_country2'];
            else
                $myarr['Reference-1 Country'] = $r['reference1_country'];
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
            if ($r['address1_city2'])
                $myarr['Address-1 City'] = $r['address1_city2'];
            else
                $myarr['Address-1 City'] = $r['address1_city'];
            if ($r['address1_state2'])
                $myarr['Address-1 State'] = $r['address1_state2'];
            else
                $myarr['Address-1 State'] = $r['address1_state'];
            if ($r['address1_country2'])
                $myarr['Address-1 Country'] = $r['address1_country2'];
            else
                $myarr['Address-1 Country'] = $r['address1_country'];
            $myarr['Address-1 Zipcode'] = $r['address1_zipcode'];
            $myarr['Address-1'] = $r['address1'];
            if ($r['address2_city2'])
                $myarr['Address-2 City'] = $r['address2_city2'];
            else
                $myarr['Address-2 City'] = $r['address2_city'];
            if ($r['address2_state2'])
                $myarr['Address-2 State'] = $r['address2_state2'];
            else
                $myarr['Address-2 State'] = $r['address2_state'];
            if ($r['address2_country2'])
                $myarr['Address-2 Country'] = $r['address2_country2'];
            else
                $myarr['Address-2 Country'] = $r['address2_country'];
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

    function getExportAllResumesByJobId($jobid) {
        if (!is_numeric($jobid))
            return false;
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
        if (!is_numeric($jobid))
            return false;
        if (!is_numeric($resumeid))
            return false;
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
                                WHERE resume.id =" . $resumeid;
        $db->setQuery($query);
        $resume = $db->loadObject();
        return $resume;
    }

    function canAddNewGoldResume($uid) {
        $db = &$this->getDBO();
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (!isset($this->_config)) {
            $this->_config = $this->common_model->getConfig('');
        }
        $newlisting_required_package = 1;
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'js_newlisting_requiredpackage')
                $newlisting_required_package = $conf->configvalue;
        }
        if ($newlisting_required_package == 0) {
            return 1;
        } else {
            $query = "SELECT package.goldresume, package.packageexpireindays, payment.created
			FROM `#__js_job_jobseekerpackages` AS package
			JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=2)
			WHERE payment.uid = " . $uid . " 
			AND DATE_ADD(payment.created,INTERVAL package.packageexpireindays DAY) >= CURDATE()
			AND payment.transactionverified = 1 AND payment.status = 1";
            $db->setQuery($query);
            $resumes = $db->loadObjectList();
            $unlimited = 0;
            $goldresume = 0;
            foreach ($resumes AS $resume) {
                if ($unlimited == 0) {
                    if ($resume->goldresume != -1) {
                        $goldresume = $goldresume + $resume->goldresume;
                    }
                    else
                        $unlimited = 1;
                }
            }
            if ($unlimited == 0) {
                if ($goldresume == 0)
                    return 0; //can not add new job
                $query = "SELECT COUNT(resume.id) 
				FROM `#__js_job_resume` AS resume
				WHERE resume.isgoldresume=1 AND resume.uid = " . $uid;
                $db->setQuery($query);
                $totalresumes = $db->loadResult();

                if ($goldresume <= $totalresumes)
                    return 0; //can not add new job
                else
                    return 1;
            }elseif ($unlimited == 1)
                return 1; // unlimited

            return 0;
        }
    }

    function canAddNewFeaturedResume($uid) {
        $db = &$this->getDBO();

        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (!isset($this->_config)) {
            $this->_config = $this->common_model->getConfig('');
        }
        $newlisting_required_package = 1;
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'js_newlisting_requiredpackage')
                $newlisting_required_package = $conf->configvalue;
        }
        if ($newlisting_required_package == 0) {
            return 1;
        } else {
            $query = "SELECT package.id, package.featuredresume, package.packageexpireindays, payment.created
			FROM `#__js_job_jobseekerpackages` AS package
			JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=2)
			WHERE payment.uid = " . $uid . " 
			AND DATE_ADD(payment.created,INTERVAL package.packageexpireindays DAY) >= CURDATE()
			AND payment.transactionverified = 1 AND payment.status = 1";
            $db->setQuery($query);
            $resumes = $db->loadObjectList();
            $unlimited = 0;
            foreach ($resumes AS $resume) {
                if ($unlimited == 0) {
                    if ($resume->featuredresume != -1) {
                        $featuredresume = $featuredresume + $resume->featuredresume;
                    }
                    else
                        $unlimited = 1;
                }
            }

            if ($unlimited == 0) {
                if ($featuredresume == 0)
                    return 0; //can not add new job

                $query = "SELECT COUNT(resume.id) 
				FROM `#__js_job_resume` AS resume
				WHERE resume.isfeaturedresume=1 AND resume.uid = " . $uid;
                $db->setQuery($query);
                $totalresumes = $db->loadResult();

                if ($featuredresume <= $totalresumes)
                    return 0; //can not add new job
                else
                    return 1;
            }elseif ($unlimited == 1)
                return 1; // unlimited
            return 0;
        }
    }

    function storeJobSeekerPackageHistory($referenceid, $autoassign, $data) {
        global $resumedata;
        $row = &$this->getTable('jobseekerpaymenthistory');
        if ($autoassign == 0)
            $data = JRequest :: get('post'); // get data from form

        if (is_numeric($data['packageid']) == false)
            return false;
        if (is_numeric($data['uid']) == false)
            return false;
        $db = &$this->getDBO();
        $result = array();
        $query = "SELECT package.* FROM `#__js_job_jobseekerpackages` AS package WHERE id = " . $data['packageid'];
        $db->setQuery($query);
        $package = $db->loadObject();
        if (isset($package)) {
            $packageconfig = $this->common_model->getConfigByFor('package');
            $row->uid = $data['uid'];
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
                if ($packageconfig['onlyonce_jobseeker_getfreepackage'] == '1') { // can't get free package more then once
                    $query = "SELECT COUNT(package.id) FROM `#__js_job_jobseekerpackages` AS package
                                    JOIN `#__js_job_jobseekerpaymenthistory` AS payment ON payment.packageid = package.id
                                    WHERE package.price = 0 AND payment.uid = " . $data['uid'];
                    $db->setQuery($query);
                    $freepackage = $db->loadResult();
                    if ($freepackage > 0)
                        return 5; // can't get free package more then once
                }
                $row->transactionverified = 1;
                $row->transactionautoverified = 1;
                $row->status = $packageconfig['jobseeker_freepackage_autoapprove'];
            }
            $row->discountamount = $discountamount;
            $row->paidamount = $paidamount;

            $row->discountmessage = $package->discountmessage;
            $row->packagestartdate = $package->discountstartdate;
            $row->packageenddate = $package->discountenddate;
            $row->resumeallow = $package->resumeallow;
            $row->coverlettersallow = $package->coverlettersallow;
            $row->applyjobs = $package->applyjobs;
            $row->jobsearch = $package->jobsearch;
            $row->savejobsearch = $package->savejobsearch;
            $row->featuredresume = $package->featuredresume;
            $row->goldresume = $package->goldresume;
            $row->video = $package->video;
            $row->packageexpireindays = $package->packageexpireindays;
            $row->packageshortdetails = $package->shortdetails;
            $row->packagedescription = $package->description;
            $row->created = date('Y-m-d H:i:s');
            $row->referenceid = $referenceid;
        }else {
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
        $this->sendMailtoAdmin($row->id, $data['uid'], 7);
        return true;
    }

    function &getResumeCommentsAJAX($uid, $id) {
        $db = &$this->getDBO();
        $data = array();
        if (is_numeric($id) == false)
            return false;
        if (is_numeric($uid) == false)
            return false;
        if ($this->_client_auth_key != "") {
            $data['uid'] = $uid;
            $data['id'] = $id;
            $data['authkey'] = $this->_client_auth_key;
            $data['siteurl'] = $this->_siteurl;
            $data['task'] = 'getResumeCommentsAJAX';
            $row = $this->getResumeCommentsAJAXSharing($data);
            $comments = $row['comments'];
            $id = $row['id'];
            $resumeid = $row['cvid'];
        } else {
            $query = "SELECT apply.comments,apply.cvid FROM `#__js_job_jobapply` AS apply JOIN `#__js_job_jobs` AS job ON apply.jobid = job.id WHERE apply.id = " . $id . " AND job.uid = " . $uid;
            $db->setQuery($query);
            $row = $db->loadObject();
            $comments = $row->comments;
            $resumeid = $row->cvid;
        }
        $option = 'com_jsjobs';
        $return_value = "<div id='resumeactioncomments'>\n";
        $return_value .= "<table id='resumeactioncommentstable' cellpadding='0' cellspacing='0' border='0' width='100%'>\n";
        $return_value .= "<tr >\n";
        $return_value .= "<td width='20%' align='right'><b>" . JText::_('JS_COMMENTS') . "</b></td>\n";
        $return_value .= "<td width='335' align='center'>\n";
        $return_value .= "<textarea name='comments' id='comments' rows='3' cols='55'>" . $comments . "</textarea>\n";
        $return_value .= "</td>\n";
        $return_value .= "<td align='left' ><input type='button' class='button' onclick='saveresumecomments(" . $id . "," . $resumeid . ")' value='" . JText::_('JS_SAVE') . "'> </td>\n";
        $return_value .= "</tr>\n";
        $return_value .= "</table>\n";
        $return_value .= "</div>\n";

        return $return_value;
    }

    function getResumeCommentsAJAXSharing($data) {
        $server_resumecomments_data_array = $data;
        $server_data_array = array('data' => $server_resumecomments_data_array);
        if (!empty($server_data_array)) {
            $fortask = "getResumeCommentsAJAX";
            $server_json_data_array = json_encode($server_data_array);
            $jsjobsharingobject = new JSJobsModelJob_Sharing;
            $return_server_value = $jsjobsharingobject->serverTask($server_json_data_array, $fortask);
            if (isset($return_server_value['resumecomments']) AND $return_server_value['resumecomments'] == -1) { // auth fail 
                $logarray['uid'] = $this->_uid;
                $logarray['referenceid'] = $return_server_value['referenceid'];
                $logarray['eventtype'] = $return_server_value['eventtype'];
                $logarray['message'] = $return_server_value['message'];
                $logarray['event'] = "Resume Comments";
                $logarray['messagetype'] = "Error";
                $logarray['datetime'] = date('Y-m-d H:i:s');
                $jsjobsharingobject->write_JobSharingLog($logarray);
                $return_array = array();
                $return_array['comments'] = "";
                $return_array['id'] = "";
                $return_array['cvid'] = "";
                return $return_array;
            } else {
                return $return_server_value;
            }
        } else {
            return true;
        }
    }

    function updateJobApplyActionStatus($jobid, $resumeid, $applyid, $action_status) {
        $db = & JFactory::getDBO();
        $row = &$this->getTable('jobapply');
        $config_email = $this->common_model->getConfigByFor('email');

        $comments_data = array();
        $data = JRequest :: get('post');
        if ($this->_client_auth_key != "") {
            $query = "SELECT id FROM `#__js_job_jobapply` where serverid=" . $applyid;
            $db->setQuery($query);
            $c_id = $db->loadResult();
            if ($c_id) {
                $applyid = $c_id;
                $isownapply = 1;
            }
        } else {
            $isownapply = 1;
        }
        if ($isownapply == 1) {
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
            }
            if ($config_email['jobseeker_resume_applied_status'] == 1)
                $this->sendMailtoJobseekerAppliedResumeUpdateStatus($jobid, $resumeid, $applyid, $action_status);
        }
        if ($this->_client_auth_key != "") {
            if ($isownapply == 1) {
                $query = "SELECT * FROM `#__js_job_jobapply` where id=" . $applyid;
                $db->setQuery($query);
                $s_data = $db->loadobject();
                if ($s_data)
                    $applyid = $s_data->serverid;
            }
            $apply_data['id'] = $applyid;
            $apply_data['action_status'] = $action_status;
            $apply_data['authkey'] = $this->_client_auth_key;
            $jsjobsharingobject = new JSJobsModelJob_Sharing;
            $return_value = $jsjobsharingobject->update_JobApplyActionStatus($apply_data);
            return JText :: _($return_value);
        }else {
            return $msg;
        }
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
        $config_email = $this->common_model->getConfigByFor('email');
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

    function storeResumeComments($data) {//store Folder
        $row = &$this->getTable('jobapply');
        $comments_data = array();

        if ($this->_client_auth_key != "") {
            $db = & JFactory::getDBO();
            $query = "SELECT id FROM #__js_job_jobapply 
			WHERE serverid = " . $data['id'];
            //echo $query;
            $db->setQuery($query);
            $result = $db->loadResult();
            if (!$result)
                $is_own_resume = 0;
            else {
                $is_own_resume = 1;
                $data['id'] = $result;
            }
        } else {
            $is_own_resume = 1;
        }
        if ($is_own_resume == 1) {
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
        }
        if ($this->_client_auth_key != "") {
            if ($is_own_resume == 1) { // own Resume Rating 
                if ($data['id'] != "" AND $data['id'] != 0) {
                    $query = "select jobapply.serverid AS serverid 
							From #__js_job_jobapply AS jobapply
							WHERE jobapply.id=" . $data['id'];
                    $db->setQuery($query);
                    $jobapply_serverid = $db->loadResult();
                    if ($jobapply_serverid)
                        $data['id'] = $jobapply_serverid;
                    else
                        $data['id'] = 0;
                }
                $comments_data['id'] = $data['id'];
                $comments_data['comments'] = $data['comments'];
                $comments_data['authkey'] = $this->_client_auth_key;
                $isownresumecomments = 1;
                $comments_data['isownresumecomments'] = $isownresumecomments;
                $jsjobsharingobject = new JSJobsModelJob_Sharing;
                $return_value = $jsjobsharingobject->store_ResumeCommentsSharing($comments_data);
                return $return_value;
            }else {  // server job apply on job sharing 
                $comments_data['id'] = $data['id'];
                $comments_data['comments'] = $data['comments'];
                $comments_data['authkey'] = $this->_client_auth_key;
                $isownresumecomments = 0;
                $comments_data['isownresumecomments'] = $isownresumecomments;
                $jsjobsharingobject = new JSJobsModelJob_Sharing;
                $return_value = $jsjobsharingobject->store_ResumeCommentsSharing($comments_data);
                return $return_value;
            }
        } else {
            return true;
        }
    }

    function storeResumeRating($uid, $ratingid, $jobid, $resumeid, $newrating) { //store Folder
        $row = &$this->getTable('resumerating');
        $db = &$this->getDBO();
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (is_numeric($jobid) == false)
            return false;
        if (is_numeric($resumeid) == false)
            return false;

        if ($this->_client_auth_key != "") {
            $db = & JFactory::getDBO();
            $query = "SELECT id FROM #__js_job_resume WHERE serverid = " . $resumeid;
            $db->setQuery($query);
            $result = $db->loadResult();
            if (!$result)
                $is_own_resume = 0;
            else {
                $is_own_resume = 1;
                $resumeid = $result;
            }

            $query = "SELECT id FROM #__js_job_jobs WHERE serverid = " . $jobid;
            $db->setQuery($query);
            $result1 = $db->loadResult();
            if (!$result1)
                $is_own_job = 0;
            else {
                $is_own_job = 1;
                $jobid = $result1;
            }
        } else {
            $is_own_resume = 1;
            $is_own_job = 1;
        }
        if ($is_own_resume == 1 AND $is_own_job == 1) {
            $query = "SELECT rating.id FROM `#__js_job_resumerating` AS rating WHERE rating.jobid = " . $jobid . " AND rating.resumeid = " . $resumeid;
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
        }
        if ($this->_client_auth_key != "") {
            if ($is_own_resume == 1 AND $is_own_job == 1) { // own Resume Rating 
                if ($jobid != "" AND $jobid != 0) {
                    $query = "select job.serverid AS serverid 
							From #__js_job_jobs AS job
							WHERE job.id=" . $jobid;
                    $db->setQuery($query);
                    $job_serverid = $db->loadResult();
                    if ($job_serverid)
                        $jobid = $job_serverid;
                    else
                        $jobid = 0;
                }
                if ($resumeid != "" AND $resumeid != 0) {
                    $query = "select resume.serverid AS serverid 
							From #__js_job_resume AS resume
							WHERE resume.id=" . $resumeid;
                    //echo 'query'.$query;
                    $db->setQuery($query);
                    $resume_serverid = $db->loadResult();
                    if ($resume_serverid)
                        $resumeid = $resume_serverid;
                    else
                        $resumeid = 0;
                }
                $data['uid'] = $uid;
                $data['jobid'] = $jobid;
                $data['resumeid'] = $resumeid;
                $data['rating'] = $newrating;
                $data['resumerating_id'] = $row->id;
                $data['created'] = date('Y-m-d H:i:s');
                $data['authkey'] = $this->_client_auth_key;
                $data['task'] = 'storeownresumerating';
                $isownresumerating = 1;
                $data['isownresumerating'] = $isownresumerating;
                $jsjobsharingobject = new JSJobsModelJob_Sharing;
                $return_value = $jsjobsharingobject->store_ResumeRatingSharing($data);
                return $return_value;
            }else {  // server job apply on job sharing 
                $data['uid'] = $uid;
                $data['jobid'] = $jobid;
                $data['resumeid'] = $resumeid;
                $data['rating'] = $newrating;
                $data['authkey'] = $this->_client_auth_key;
                $data['created'] = date('Y-m-d H:i:s');
                $data['task'] = 'storeserverjobapply';
                $isownresumerating = 0;
                $data['isownresumerating'] = $isownresumerating;
                $jsjobsharingobject = new JSJobsModelJob_Sharing;
                $return_value = $jsjobsharingobject->store_ResumeRatingSharing($data);
                return $return_value;
            }
        } else {
            return true;
        }
    }

    function storeFilter() {
        global $resumedata;
        $user = & JFactory::getUser();
        $row = &$this->getTable('filter');
        $data = JRequest :: get('post');

        $data['uid'] = $user->id;
        $data['status'] = 1;
        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        if ($data['txtfilter_country'] == 'Country')
            $data['txtfilter_country'] = '';
        if ($data['txtfilter_state'] == 'State')
            $data['txtfilter_state'] = '';
        if ($data['txtfilter_county'] == 'County')
            $data['txtfilter_county'] = '';
        if ($data['txtfilter_city'] == 'City')
            $data['txtfilter_city'] = '';

        if ($data['cmbfilter_country'] != '') {
            $row->country = $data['cmbfilter_country'];
            $row->country_istext = 0;
        } elseif ($data['txtfilter_country'] != '') {
            $row->country = $data['txtfilter_country'];
            $row->country_istext = 1;
        }
        if ($data['cmbfilter_state'] != '') {
            $row->state = $data['cmbfilter_state'];
            $row->state_istext = 0;
        } elseif ($data['txtfilter_state'] != '') {
            $row->state = $data['txtfilter_state'];
            $row->state_istext = 1;
        }
        if ($data['cmbfilter_county'] != '') {
            $row->county = $data['cmbfilter_county'];
            $row->county_istext = 0;
        } elseif ($data['txtfilter_county'] != '') {
            $row->county = $data['txtfilter_county'];
            $row->county_istext = 1;
        }
        if ($data['cmbfilter_city'] != '') {
            $row->city = $data['cmbfilter_city'];
            $row->city_istext = 0;
        } elseif ($data['txtfilter_city'] != '') {
            $row->city = $data['txtfilter_city'];
            $row->city_istext = 1;
        }

        $row->category = $data['filter_jobcategory'];
        $row->jobtype = $data['filter_jobtype'];
        $row->salaryrange = $data['filter_jobsalaryrange'];
        $row->heighesteducation = $data['filter_heighesteducation'];

        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());
            echo $this->_db->getErrorMsg();
            return false;
        }
        return true;
    }

    function deleteUserFilter() {
        $row = &$this->getTable('filter');
        $data = JRequest :: get('post');

        if (!$row->delete($data['id'])) {
            $this->setError($row->getErrorMsg());
            return false;
        }

        return true;
    }

    public function sendJobAlert($alerttype) {
        $admin_jobs = '';
        $message = & JFactory::getMailer();
        $config = $this->common_model->getConfigByFor('email');
        $db = &$this->getDBO();
        $curdate = date('Y-m-d H:i:s');
        if ($alerttype == 1)
            $days = 1;
        elseif ($alerttype == 2)
            $days = 7;
        elseif ($alerttype == 3)
            $days = 30;

        if ($alerttype == 1)
            $alerttitle = 'Daily';
        elseif ($alerttype == 2)
            $alerttitle = 'Weekly';
        elseif ($alerttype == 3)
            $alerttitle = 'Monthly';

        $query = "SELECT person.*
                    FROM `#__js_job_jobalertsetting` AS person
                    WHERE person.alerttype = " . $alerttype . " AND DATE(DATE_ADD(person.lastmailsend,INTERVAL " . $days . " DAY)) = CURDATE()";
        $db->setQuery($query);
        $persons = $db->loadObjectList();
        if (empty($persons))
            return false; // no person were selected for mail

        foreach ($persons AS $person) {
            $wherequery = "";
            $query = "SELECT malert.cityid
                        FROM `#__js_job_jobalertcities` AS malert
                        WHERE malert.alertid = " . $person->id;
            $db->setQuery($query);
            $alertcities = $db->loadObjectList();
            if (is_object($alertcities)) {
                $lenght = count($alertcities);
                for ($i = 0; $i < $lenght; $i++) {
                    if ($i == 0)
                        $wherequery .= " AND ( mjob.cityid=" . $alertcities[$i]->cityid;
                    else
                        $wherequery .= " OR mjob.cityid=" . $alertcities[$i]->cityid;
                }
                $wherequery .= ")";
            }

            if ($alerttype == 1)
                $wherequery .= " AND DATE(job.startpublishing) = CURDATE()";
            if ($alerttype == 2)
                $wherequery .= " AND job.startpublishing >= DATE_SUB(CURDATE(),INTERVAL 7 DAY)";
            if ($alerttype == 3)
                $wherequery .= " AND job.startpublishing >= DATE_SUB(CURDATE(),INTERVAL 30 DAY)";

            $wherequery .= " AND job.startpublishing BETWEEN '" . $person->lastmailsend . "' AND '" . $curdate . "'";


            $metakeywords = array();
            if (isset($person->keywords)) {
                $keywords = explode(' ', $person->keywords);
                $length = count($keywords);
                if ($length <= 5)
                    $i = $length;
                else
                    $i = 5;

                for ($j = 0; $j < $i; $j++) {
                    $metakeywords[] = " job.metakeywords LIKE LOWER ('%$keywords[$j]%')";
                }
                $metakeywords[] = " job.metakeywords = '' OR job.metakeywords IS NULL";
            }
            $query = "SELECT DISTINCT job.*,cat.cat_title AS categorytitle,subcat.title AS subcategorytitle
								FROM `#__js_job_jobs` AS job
								JOIN `#__js_job_categories` AS cat ON cat.id = job.jobcategory
								LEFT JOIN `#__js_job_subcategories` AS subcat ON subcat.id = job.subcategoryid
								LEFT JOIN `#__js_job_jobcities` AS mjob ON mjob.jobid = job.id
								WHERE job.jobcategory = " . $person->categoryid;

            if ($person->subcategoryid)
                $query .= " AND job.subcategoryid = " . $person->subcategoryid;
            if ($person->keywords)
                $query .= " AND ( " . implode(' OR ', $metakeywords) . " )";
            $query .=$wherequery;
            //echo '<br>'.$query;
            $db->setQuery($query);
            $jobs = $db->loadObjectList();
            foreach ($jobs AS $job) {  // for multicity select 
                $multicitydata = $this->common_model->getMultiCityDataForView($job->id, 1);
                if ($multicitydata != "")
                    $job->mcity = $multicitydata;
            }

            if (!empty($jobs)) {
                $query = "SELECT template.* FROM `#__js_job_emailtemplates` AS template	WHERE template.templatefor = 'job-alert'";
                $db->setQuery($query);
                $template = $db->loadObject();
                $msgSubject = $template->subject;
                $msgBody = $template->body;
                $message->addRecipient($person->contactemail); //to email
                $mail_jobs = '<table width="100%" cellpadding="10px" cellspacing="0">
								<tr>
									<th>' . JText::_('JS_JOB_TITLE') . '</th>
									<th>' . JText::_('JS_JOB_CATEGORY') . '</th>
									<th>' . JText::_('JS_SUB_CATEGORIES') . '</th>
									<th>' . JText::_('JS_JOB_LOCATION') . '</th>
								</tr>';
                foreach ($jobs AS $job) {
                    $comma = '';
                    $location = '';
                    if (isset($job->mcity))
                        $location = $job->mcity;
                    $path = JRoute::_(JURI::root() . 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_job&vj=5&oi=' . $job->id . '&Itemid=105');
                    $mail_jobs .= '<tr>
										<td><a href="' . $path . '" target="_blank">' . $job->title . '</a></td>
										<td>' . $job->categorytitle . '</td>
										<td>' . $job->subcategorytitle . '</td>
										<td>' . $location . '</td>
									</tr>';
                }
                $mail_jobs .= '</table>';
                $admin_jobs .= $mail_jobs . " <br/> To " . $person->name . " <br/><br/>";
                $msgBody = str_replace('{JOBSEEKER_NAME}', $person->name, $msgBody);
                $msgBody = str_replace('{JOBS_INFO}', $mail_jobs, $msgBody);
                $message->setSubject($msgSubject);
                $message->setBody($msgBody);
                $sender = array($config['mailfromaddress'], $config['mailfromname']);
                $message->setSender($sender);
                $message->IsHTML(true);
                $sent = $message->send();
            }
            // update last mail send
            $query = "UPDATE `#__js_job_jobalertsetting` SET lastmailsend = '" . $curdate . "' WHERE id = " . $person->id;
            $db->setQuery($query);
            $db->query();
        } // end of each persons foreach loop
        // mail send to admin
        $message1 = & JFactory::getMailer();
        $message1->addRecipient($config['adminemailaddress']); //to email
        $msgSubject = "Job Alert Information";
        $msgBody = "<p>Dear Admin</p><br/>Following jobs was succefully sent.<br/>Alert type: $alerttitle <br />" . $admin_jobs;
        $message1->setSubject($msgSubject);
        $message1->setBody($msgBody);
        $sender = array($config['mailfromaddress'], $config['mailfromname']);
        $message1->setSender($sender);
        $message1->IsHTML(true);
        $sent = $message1->send();
    }

    function &listFilterAddressData($data, $val) {
        $db = &$this->getDBO();

        if (!isset($this->_config)) {
            $this->_config = $this->common_model->getConfig('');
        }
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'filter_address_fields_width')
                $address_fields_width = $conf->configvalue;
        }

        if ($data == 'country') {  // country
            $query = "SELECT id AS code, name FROM `#__js_job_countries` WHERE enabled = 'Y'";
            if ($this->_client_auth_key != "")
                $query.=" AND serverid!='' AND serverid!=0";
            $query.=" ORDER BY name ASC";
            $db->setQuery($query);
            $result = $db->loadObjectList();

            if (empty($result)) {
                $return_value = "<input class='inputbox' style='width:" . $address_fields_width . "px;color:#808080;' type='text' id='txtfilter_country' name='txtfilter_country' size='25' maxlength='50' value='Country' />";
            } else {
                $return_value = "<select name='cmbfilter_country' id='cmbfilter_country' style='width:" . $address_fields_width . "px;' onChange=\"filter_dochange('filter_state', this.value)\">\n";
                $return_value .= "<option value=''>" . JText::_('JS_SEARCH_ALL') . "</option>\n";
                foreach ($result as $row) {
                    $return_value .= "<option value=\"$row->code\" >$row->name</option> \n";
                }
                $return_value .= "</select>\n";
            }
        } else if ($data == 'filter_state') {  // states
            $query = "SELECT id AS code, name from `#__js_job_states`  WHERE enabled = 'Y' AND countryid= " . $val;
            if ($this->_client_auth_key != "")
                $query.=" AND serverid!='' AND serverid!=0";
            $query.=" ORDER BY name ASC";
            $db->setQuery($query);
            $result = $db->loadObjectList();
            if (empty($result)) {
                $return_value = "<input class='inputbox' type='text' style='width:" . $address_fields_width . "px;color:#808080;' id='txtfilter_state' name='txtfilter_state' size='25' maxlength='50' value='State' onfocus='if(this.value == \"State\"){this.value = \"\";this.style.color=\"black\";};' onblur='if(this.value == \"\") { this.style.color=\"#808080\";this.value=\"State\";  }' />";
            } else {
                $return_value = "<select id='cmbfilter_state' name='cmbfilter_state' class='inputbox' style='width:" . $address_fields_width . "px;' onChange=\"filter_dochange('filter_city', this.value)\">\n";
                $return_value .= "<option value=''>" . JText::_('JS_SEARCH_ALL') . "</option>\n";

                foreach ($result as $row) {
                    $return_value .= "<option value=\"$row->code\" >$row->name</option> \n";
                }
                $return_value .= "</select>\n";
            }
        } else if ($data == 'filter_city') { // city
            $query = "SELECT id AS code, name from `#__js_job_cities`  WHERE enabled = 'Y' AND stateid= " . $val;
            if ($this->_client_auth_key != "")
                $query.=" AND serverid!='' AND serverid!=0";
            $query.=" ORDER BY name ASC";
            $db->setQuery($query);
            $result = $db->loadObjectList();
            //if (mysql_num_rows($result)== 0)
            if (empty($result)) {
                $return_value = "<input class='inputbox' type='text' style='width:" . $address_fields_width . "px;color:#808080;' name='txtfilter_city' id='txtfilter_city' size='25' maxlength='50' value='City' onfocus='if(this.value == \"City\"){this.value = \"\";this.style.color=\"black\";};' onblur='if(this.value == \"\") { this.style.color=\"#808080\";this.value=\"City\";  }'/>";
            } else {
                $return_value = "<select name='cmbfilter_city' id='cmbfilter_city' class='inputbox' style='width:" . $address_fields_width . "px;' onChange=\"filter_dochange('zipcode', this.value)\">\n";
                $return_value .= "<option value=''>" . JText::_('JS_SEARCH_ALL') . "</option>\n";
                foreach ($result as $row) {
                    $return_value .= "<option value=\"$row->code\" >$row->name</option> \n";
                }
                $return_value .= "</select>\n";
            }
        }
        return $return_value;
    }

    function &listFilterSubCategories($val) {
        $db = &$this->getDBO();
        $query = "SELECT id, title FROM `#__js_job_subcategories`  WHERE status = 1 AND categoryid = " . $val . " ORDER BY title ASC";
        $db->setQuery($query);
        $result = $db->loadObjectList();

        if (isset($result)) {
            $return_value = "<select name='filter_jobsubcategory' id='filter_jobsubcategory'  class='inputbox' >\n";
            $return_value .= "<option value='' >" . JText::_('JS_SUB_CATEGORY') . "</option> \n";
            foreach ($result as $row) {
                $return_value .= "<option value=\"$row->id\" >$row->title</option> \n";
            }
            $return_value .= "</select>\n";
        }
        return $return_value;
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
        $canview = 0;
        if ($this->_client_auth_key != "") {
            $query = "SELECT id FROM #__js_job_jobs 
					WHERE serverid = " . $jobid;
            $db->setQuery($query);
            $server_jobid = $db->loadResult();
            $jobid = $server_jobid;

            $query = "SELECT id FROM #__js_job_resume 
					WHERE serverid = " . $resumeid;
            $db->setQuery($query);
            $server_resumeid = $db->loadResult();
            $resumeid = $server_resumeid;
        }

        $query = "SELECT apply.resumeview FROM `#__js_job_jobapply` AS apply
                WHERE apply.jobid = " . $jobid . " AND apply.cvid = " . $resumeid;
        $db->setQuery($query);
        $alreadyview = $db->loadObject();

        if ($alreadyview->resumeview == 1)
            $canview = 1; //already view this resume
        if ($canview == 0) {
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
                $query = "SELECT package.viewresumeindetails, package.packageexpireindays, payment.created
							FROM `#__js_job_employerpackages` AS package
							JOIN `#__js_job_paymenthistory` AS payment ON (payment.packageid = package.id AND payment.packagefor=1 )
							WHERE payment.uid = " . $uid . "
							AND DATE_ADD(payment.created,INTERVAL package.packageexpireindays DAY) >= CURDATE()";
                //echo $query;
                $db->setQuery($query);
                $jobs = $db->loadObjectList();
                $unlimited = 0;
                $viewresumeindetails = 0;
                foreach ($jobs AS $job) {
                    if ($unlimited == 0) {
                        if ($job->viewresumeindetails != -1) {
                            $viewresumeindetails = $viewresumeindetails + $job->viewresumeindetails;
                        }
                        else
                            $unlimited = 1;
                    }
                }
                if ($unlimited == 0) {
                    if ($viewresumeindetails == 0)
                        $canview = 0; //can not add new job
                    $query = "SELECT SUM(apply.resumeview) AS totalview
									FROM `#__js_job_jobapply` AS apply
									WHERE apply.jobid = " . $jobid;
                    $db->setQuery($query);
                    $totalview = $db->loadResult();

                    if ($viewresumeindetails <= $totalview)
                        $canview = 0; //can not add new job
                    else
                        $canview = 1;
                }elseif ($unlimited == 1)
                    $canview = 1; // unlimited
            }
        }
        if ($canview == 1) {

            $query = "UPDATE `#__js_job_jobapply` SET resumeview = 1 WHERE jobid = " . $jobid . " AND cvid = " . $resumeid;
            $db->setQuery($query);
            $db->query();

            if ($this->_client_auth_key != "") {
                $query = "SELECT serverid FROM #__js_job_jobs WHERE id = " . $jobid;
                $db->setQuery($query);
                $_jobid = $db->loadResult();
                $jobid = $_jobid;

                $query = "SELECT serverid FROM #__js_job_resume WHERE id = " . $resumeid;
                $db->setQuery($query);
                $_resumeid = $db->loadResult();
                $resumeid = $_resumeid;
                $data_resumedetail = array();
                $data_resumedetail['uid'] = $uid;
                $data_resumedetail['jobid'] = $jobid;
                $data_resumedetail['resumeid'] = $resumeid;
                $data_resumedetail['authkey'] = $this->_client_auth_key;
                $data_resumedetail['siteurl'] = $this->_siteurl;
                $fortask = "getresumedetail";
                $jsjobsharingobject = new JSJobsModelJob_Sharing;
                $encodedata = json_encode($data_resumedetail);
                $return_server_value = $jsjobsharingobject->serverTask($encodedata, $fortask);
                if (isset($return_server_value['resumedetails']) AND $return_server_value['resumedetails'] == -1) { // auth fail 
                    $logarray['uid'] = $this->_uid;
                    $logarray['referenceid'] = $return_server_value['referenceid'];
                    $logarray['eventtype'] = $return_server_value['eventtype'];
                    $logarray['message'] = $return_server_value['message'];
                    $logarray['event'] = "Resume Details";
                    $logarray['messagetype'] = "Error";
                    $logarray['datetime'] = date('Y-m-d H:i:s');
                    $jsjobsharingobject->write_JobSharingLog($logarray);
                    //$resume = (object) array('name'=>'','decription'=>'','created'=>'');
                } else {
                    $resume = (object) $return_server_value['relationjsondata'];
                }
            } else {
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

                // ,app.address_county,county.name AS countyname LEFT JOIN `#__js_job_counties` AS county ON app.address_county  = county.id
                $db->setQuery($query);
                $resume = $db->loadObject();
            }
            $fieldsordering = $this->common_model->getFieldsOrdering(3); // resume fields ordering
            if (isset($resume)) {
                $trclass = array('odd', 'even');
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
                                if ($resume->iamavailable == 1)
                                    $return_value .= "<span id='resumedetail_data_value' >" . JText::_('JS_YES') . "</span>\n";
                                else
                                    $return_value .= "<span id='resumedetail_data_value' >" . JText::_('JS_NO') . "</span>\n";
                                $return_value .= "</div>\n";
                            }
                            break;
                        case 'salary':
                            if ($field->published == 1) {
                                $return_value .= "<div id='resumedetail_data'>\n";
                                $return_value .= "<span id='resumedetail_data_title' >" . JText::_('JS_CURRENT_SALARY') . "</span>\n";
                                //$currentsalary=$resume->symbol . $resume->rangestart . ' - ' . $resume->symbol.' '. $resume->rangeend; 
                                //$currentsalary="4000";
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

    function sendToFriend($data) {
        $recipient = array();
        $recipient[] = $data[2]; // 2 to 6 friend emails
        if ($data[3] != '')
            $recipient[] = $data[3];
        if ($data[4] != '')
            $recipient[] = $data[4];
        if ($data[5] != '')
            $recipient[] = $data[5];
        if ($data[6] != '')
            $recipient[] = $data[6];
        $sendername = $data[0];
        $senderemail = $data[1];
        $sendermessage = $data[7];
        $jobid = $data[8];
        if (!is_numeric($jobid))
            return false;
        $message = & JFactory::getMailer();
        $message->addRecipient($recipient); //to email
        $db = $this->getDbo();
        $templatefor = 'job-to-friend';
        $query = "SELECT template.* FROM `#__js_job_emailtemplates` AS template	WHERE template.templatefor = " . $db->Quote($templatefor);
        $db->setQuery($query);
        $template = $db->loadObject();
        $msgSubject = $template->subject;
        $msgBody = $template->body;
        $config = $this->common_model->getConfigByFor('default');
        $sitename = $config['title'];

        $jobquery = "SELECT  job.title AS jobtitle,cat.cat_title AS cattitle,comp.name AS companyname
                        FROM `#__js_job_jobs` AS job
                        JOIN `#__js_job_categories` AS cat ON cat.id = job.jobcategory
                        JOIN `#__js_job_companies` AS comp ON comp.id = job.companyid
                        WHERE job.id = " . $jobid;
        $db->setQuery($jobquery);
        $job = $db->loadObject();
        $CompanyName = $job->companyname;
        $CategoryTitle = $job->cattitle;
        $JobTitle = $job->jobtitle;
        $siteAddress = JURI::root();
        $link = $siteAddress . "index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_job&vj=5&oi=" . $jobid . "&Itemid=105";

        $msgSubject = str_replace('{JOB_TITLE}', $JobTitle, $msgSubject);
        $msgSubject = str_replace('{JOB_CATEGORY}', $CategoryTitle, $msgSubject);
        $msgSubject = str_replace('{SENDER_NAME}', $sendername, $msgSubject);
        $msgSubject = str_replace('{SITE_NAME}', $sitename, $msgSubject);
        $msgSubject = str_replace('{COMPANY_NAME}', $CompanyName, $msgSubject);
        $msgSubject = str_replace('{CLICK_HERE_TO_VISIT}', $link, $msgSubject);
        $msgSubject = str_replace('{SENDER_MESSAGE}', $sendermessage, $msgSubject);

        $msgBody = str_replace('{JOB_TITLE}', $JobTitle, $msgBody);
        $msgBody = str_replace('{JOB_CATEGORY}', $CategoryTitle, $msgBody);
        $msgBody = str_replace('{SENDER_NAME}', $sendername, $msgBody);
        $msgBody = str_replace('{SITE_NAME}', $sitename, $msgBody);
        $msgBody = str_replace('{COMPANY_NAME}', $CompanyName, $msgBody);
        $msgBody = str_replace('{CLICK_HERE_TO_VISIT}', $link, $msgBody);
        $msgBody = str_replace('{SENDER_MESSAGE}', $sendermessage, $msgBody);

        $message->setSubject($msgSubject);
        $message->setBody($msgBody);
        $sender = array($senderemail, $sendername);
        $message->setSender($sender);
        $message->IsHTML(true);
        if (!$message->send())
            $sent = $message->sent();
        else
            $sent = true;
        return $sent;
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

    function &getCurU() {
        $result = array();
        $result[0] = 'a' . substr($this->_arv, 16, 2) . substr($this->_arv, 24, 1);
        $result[1] = substr($this->_arv, 5, 2) . substr($this->_arv, 12, 3) . substr($this->_arv, 20, 1) . 'e';
        $result[2] = $_SERVER['SERVER_NAME'];
        return $result;
    }
    public function sendJobAlertByAlertType($ck) {
        $pass = $this->checkCronKey($ck);
        if ($pass == true) {
            $this->sendJobAlert(1); //For Daily Subscriber
            $this->sendJobAlert(2); //For Weekly Subscriber
            $this->sendJobAlert(3); //For Monthly Subscriber
            if ($this->_client_auth_key != "") {
                for ($i = 1; $i <= 3; $i++) {
                    $data['ck'] = $ck;
                    $data['authkey'] = $this->_client_auth_key;
                    $data['siteurl'] = $this->_siteurl;
                    $data['alerttype'] = $i;
                    $fortask = "sendjobalert";
                    $jsjobsharingobject = new JSJobsModelJob_Sharing;
                    $encodedata = json_encode($data);
                    $return_server_value = $jsjobsharingobject->serverTask($encodedata, $fortask);
                    if (isset($return_server_value['jobalerts']) AND $return_server_value['jobalerts'] == -1) { // auth fail 
                        $logarray['uid'] = $this->_uid;
                        $logarray['referenceid'] = $return_server_value['referenceid'];
                        $logarray['eventtype'] = $return_server_value['eventtype'];
                        $logarray['message'] = $return_server_value['message'];
                        $logarray['event'] = "Job Alerts for Jobseeker";
                        $logarray['messagetype'] = "Error";
                        $logarray['datetime'] = date('Y-m-d H:i:s');
                        $jsjobsharingobject->write_JobSharingLog($logarray);
                    }
                }
            }
        }
        else
            return false;
    }


    
    
    
    

}

?>
