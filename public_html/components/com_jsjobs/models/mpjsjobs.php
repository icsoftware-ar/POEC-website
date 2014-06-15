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

$option = JRequest :: getVar('option', 'com_jsjobs');

class JSJobsModelMpJsjobs extends JModelLegacy {

    var $_id = null;
    var $_uid = null;
    var $_stats = null;
    var $_client_auth_key = null;
    var $_siteurl = null;

    function __construct() {
        parent :: __construct();
        $user = & JFactory::getUser();
        $this->common_model = $this->getCommonModel();
        $client_auth_key = $this->common_model->getClientAuthenticationKey();
        $this->_client_auth_key = $client_auth_key;
        $this->_siteurl = JURI::root();
        $lang = JFactory::getLanguage();
        $lang->load('com_jsjobs');
    }

    function &getCommonModel() {
        $componentPath = 'components/com_jsjobs';
        require_once $componentPath . '/models/common.php';
        $common_model = new JSJobsModelCommon();
        return $common_model;
    }

    function getConfig() {
        $db = & JFactory::getDBO();
        $query = "SELECT * FROM `#__js_job_config` WHERE configname = 'theme' OR configname = 'date_format' OR configname = 'data_directory' OR configname = 'defaultcountry' ";
        $db->setQuery($query);
        $configs = $db->loadObjectList();
        foreach ($configs AS $config) {
            if ($config->configname == 'theme')
                $css = $config->configvalue;
            if ($config->configname == 'date_format')
                $dateformat = $config->configvalue;
            if ($config->configname == 'data_directory')
                $datadirectory = $config->configvalue;
            //if ($config->configname == 'defaultcountry')$default_country = $config->configvalue;
        }
        $result = array();
        $result[0] = $css;
        $result[1] = $dateformat;
        $result[2] = $datadirectory;
        return $result;
    }

    function setTheme($theme, $css) {
        if ($theme == 1) { // js jobs theme
            $trclass = array("odd", "even");
            if ($css == 'templatetheme.css')
                $trclass = array("sectiontableentry1", "sectiontableentry2");
            $document = & JFactory::getDocument();
            $document->addStyleSheet('components/com_jsjobs/themes/' . $css);
        }elseif ($theme == 2) { // template theme
            $trclass = array("sectiontableentry1", "sectiontableentry2");
        }
        else
            $trclass = array("", ""); //no theme
        return $trclass;
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
        //echo $query;
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

    function getFeaturedResumes($noofresumes, $theme) {

        $db = & JFactory::getDBO();
        $config = $this->getConfig();
        $css = $config[0];
        $dateformat = $config[1];
        $trclass = $this->setTheme($theme, $css);

        $id = "resume.id AS id";
        $alias = ",CONCAT(resume.alias,'-',resume.id) AS aliasid ";
        $query = "SELECT resume.packageid,
		 $id, resume.application_title, resume.first_name, resume.last_name 
		, resume.gender, resume.iamavailable, resume.photo, resume.heighestfinisheducation
		, resume.total_experience, resume.create_date, resume.address_country, resume.address_state
		, resume.address_county, resume.address_city, cat.cat_title, jobtype.title AS jobtypetitle
		, country.name AS countryname, state.name AS statename,  city.name AS cityname, nationality.name AS nationalityname
		$alias
		 
		FROM `#__js_job_resume` AS resume 
		JOIN `#__js_job_categories` AS cat ON resume.job_category = cat.id 
		LEFT JOIN `#__js_job_jobseekerpackages` AS package ON package.id = resume.packageid 
		LEFT JOIN `#__js_job_jobtypes` AS jobtype ON resume.jobtype = jobtype.id 
		LEFT JOIN `#__js_job_cities` AS city ON resume.address_city= city.id
		LEFT JOIN `#__js_job_states` AS state ON city.stateid= state.id
		LEFT JOIN `#__js_job_countries` AS country ON city.countryid = country.id
		LEFT JOIN `#__js_job_countries` AS nationality ON nationality.id=resume.nationality 
		WHERE resume.isfeaturedresume = 1 AND resume.status = 1 
		ORDER BY resume.create_date DESC ";
        if ($noofresumes != -1)
            $query .=" LIMIT " . $noofresumes;
        $db->setQuery($query);

        $result[0] = $db->loadObjectList();
        $result[1] = $trclass;
        $result[2] = $dateformat;

        return $result;
    }

    function getFeaturedCompanies($noofcompanies, $theme) {

        $db = & JFactory::getDBO();
        $config = $this->getConfig();
        $css = $config[0];
        $dateformat = $config[1];
        $datadirectory = $config[2];
        $trclass = $this->setTheme($theme, $css);
        if ($this->_client_auth_key != "") {
            $aliasid = ", CONCAT(company.alias,'-',company.serverid) AS aliasid ";
        } else {
            $aliasid = ", CONCAT(company.alias,'-',company.id) AS aliasid ";
        }
        $query = "SELECT  company.*,cat.cat_title $aliasid
		FROM `#__js_job_companies` AS company 
		JOIN `#__js_job_categories` AS cat ON company.category = cat.id 
		LEFT JOIN `#__js_job_employerpackages` AS package ON package.id = company.packageid 
		WHERE company.status = 1 AND company.isfeaturedcompany = 1
		ORDER BY company.created DESC ";
        if ($noofcompanies != -1)
            $query .=" LIMIT " . $noofcompanies;
        $db->setQuery($query);

        $result[0] = $db->loadObjectList();
        foreach ($result[0] AS $fcdata) {
            $multicitydata = $this->getMultiCityDataForView($fcdata->id, 2);
            if ($multicitydata != "")
                $fcdata->multicity = $multicitydata;
        }
        $result[1] = $trclass;
        $result[2] = $dateformat;
        $result[3] = $datadirectory;

        return $result;
    }

    function getFeaturedJobs($noofjobs, $theme) {
        $db = & JFactory::getDBO();

        $config = $this->getConfig();
        $css = $config[0];
        $dateformat = $config[1];
        $datadirectory = $config[2];
        $trclass = $this->setTheme($theme, $css);

        if ($this->_client_auth_key != "") {
            $id = "job.serverid AS id";
            $alias = ",CONCAT(job.alias,'-',job.serverid) AS aliasid ";
            $companyaliasid = ", CONCAT(company.alias,'-',company.serverid) AS companyaliasid ";
        } else {
            $id = "job.id AS id";
            $alias = ",CONCAT(job.alias,'-',job.id) AS aliasid ";
            $companyaliasid = ", CONCAT(company.alias,'-',company.id) AS companyaliasid ";
        }
        $curdate = date('Y-m-d');
        $query = "SELECT job.packageid
		,$id, job.title, job.created, job.country, job.state, job.county, job.city, cat.cat_title
		, company.id AS companyid, company.name AS companyname, company.logofilename,subcat.title AS subcat_title
		,jobtype.title AS jobtypetitle
		$alias $companyaliasid
		FROM `#__js_job_jobs` AS job 
		JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype=jobtype.id
		JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id 
		LEFT JOIN `#__js_job_employerpackages` AS package ON package.id = job.packageid 
		LEFT JOIN `#__js_job_subcategories` AS subcat ON job.subcategoryid = subcat.id
		LEFT JOIN `#__js_job_companies` AS company ON company.id=job.companyid 
		WHERE job.status = 1 AND job.isfeaturedjob = 1 AND DATE(job.startpublishing) <= " . $db->Quote($curdate) . " AND DATE(job.stoppublishing) >= " . $db->Quote($curdate) . "
		ORDER BY created DESC ";
        if ($noofjobs != -1)
            $query .=" LIMIT " . $noofjobs;
        $db->setQuery($query);
        $result[0] = $db->loadObjectList();
        foreach ($result[0] AS $fjdata) {
            $multicitydata = $this->getMultiCityDataForView($fjdata->id, 1);
            if ($multicitydata != "")
                $fjdata->multicity = $multicitydata;
        }

        $result[1] = $trclass;
        $result[2] = $dateformat;
        $result[3] = $datadirectory;
        return $result;
    }

    function getGoldCompanies($noofcompanies, $theme) {

        $db = & JFactory::getDBO();
        $config = $this->getConfig();
        $css = $config[0];
        $dateformat = $config[1];
        $datadirectory = $config[2];
        $trclass = $this->setTheme($theme, $css);

        if ($this->_client_auth_key != "") {
            $aliasid = ", CONCAT(company.alias,'-',company.serverid) AS aliasid ";
        } else {
            $aliasid = ", CONCAT(company.alias,'-',company.id) AS aliasid ";
        }
        $query = "SELECT  company.*,cat.cat_title $aliasid
		FROM `#__js_job_companies` AS company 
		JOIN `#__js_job_categories` AS cat ON company.category = cat.id 
		LEFT JOIN `#__js_job_employerpackages` AS package ON package.id = company.packageid 
		WHERE company.status = 1 AND company.isgoldcompany = 1
		ORDER BY company.created DESC ";
        if ($noofcompanies != -1)
            $query .=" LIMIT " . $noofcompanies;
        $db->setQuery($query);

        $result[0] = $db->loadObjectList();
        foreach ($result[0] AS $gcdata) {
            $multicitydata = $this->getMultiCityDataForView($gcdata->id, 2);
            if ($multicitydata != "")
                $gcdata->multicity = $multicitydata;
        }

        $result[1] = $trclass;
        $result[2] = $dateformat;
        $result[3] = $datadirectory;
        return $result;
    }

    function getGoldJobs($noofjobs, $theme) {
        $db = & JFactory::getDBO();
        $config = $this->getConfig();
        $css = $config[0];
        $dateformat = $config[1];
        $datadirectory = $config[2];
        $trclass = $this->setTheme($theme, $css);

        if ($this->_client_auth_key != "") {
            $id = "job.serverid AS id";
            $alias = ",CONCAT(job.alias,'-',job.serverid) AS aliasid ";
            $companyaliasid = ", CONCAT(company.alias,'-',company.serverid) AS companyaliasid ";
        } else {
            $id = "job.id AS id";
            $alias = ",CONCAT(job.alias,'-',job.id) AS aliasid ";
            $companyaliasid = ", CONCAT(company.alias,'-',company.id) AS companyaliasid ";
        }
        $curdate = date('Y-m-d');
        $query = "SELECT job.packageid
		,$id, job.title, job.created
		, cat.cat_title 
		, company.id AS companyid, company.name AS companyname, company.logofilename,jobtype.title AS jobtypetitle
		$alias $companyaliasid
		
		FROM `#__js_job_jobs` AS job 
		JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id 
		LEFT JOIN `#__js_job_employerpackages` AS package ON package.id = job.packageid 
		LEFT JOIN `#__js_job_companies` AS company ON company.id=job.companyid 
		LEFT JOIN `#__js_job_jobtypes` AS jobtype ON jobtype.id = job.jobtype  
		WHERE job.status = 1 AND job.isgoldjob = 1 AND DATE(job.startpublishing) <= " . $db->Quote($curdate) . " AND DATE(job.stoppublishing) >= " . $db->Quote($curdate) . "
		ORDER BY created DESC ";
        if ($noofjobs != -1)
            $query .=" LIMIT " . $noofjobs;
        $db->setQuery($query);
        $result[0] = $db->loadObjectList();
        foreach ($result[0] AS $gjdata) {
            $multicitydata = $this->getMultiCityDataForView($gjdata->id, 1);
            if ($multicitydata != "")
                $gjdata->multicity = $multicitydata;
        }
        $result[1] = $trclass;
        $result[2] = $dateformat;
        $result[3] = $datadirectory;
        return $result;
    }

    function getGoldResumes($noofresumes, $theme) {
        $db = & JFactory::getDBO();
        $config = $this->getConfig();
        $css = $config[0];
        $dateformat = $config[1];
        $date_dir = $config[2];
        $trclass = $this->setTheme($theme, $css);

        $id = "resume.id AS id";
        $alias = ",CONCAT(resume.alias,'-',resume.id) AS aliasid ";
        $query = "SELECT resume.packageid
		,$id, resume.application_title, resume.first_name, resume.last_name
		, resume.gender, resume.iamavailable, resume.photo, resume.heighestfinisheducation
		, resume.total_experience, resume.create_date, resume.address_country, resume.address_state
		, resume.address_city, cat.cat_title, jobtype.title AS jobtypetitle
		, country.name AS countryname, state.name AS statename, city.name AS cityname, nationality.name AS nationalityname
		$alias
		
		FROM `#__js_job_resume` AS resume 
		JOIN `#__js_job_categories` AS cat ON resume.job_category = cat.id 
		LEFT JOIN `#__js_job_jobseekerpackages` AS package ON package.id = resume.packageid 
		LEFT JOIN `#__js_job_jobtypes` AS jobtype ON resume.jobtype = jobtype.id 
		LEFT JOIN `#__js_job_cities` AS city ON city.id=resume.address_city  
		LEFT JOIN `#__js_job_states` AS state ON city.stateid=state.id  
		LEFT JOIN `#__js_job_countries` AS country ON city.countryid=country.id 
		LEFT JOIN `#__js_job_countries` AS nationality ON nationality.id=resume.nationality 
		WHERE resume.isgoldresume = 1 AND resume.status = 1 
		ORDER BY resume.create_date DESC ";
        if ($noofresumes != -1)
            $query .=" LIMIT " . $noofresumes;
        $db->setQuery($query);
        $result[0] = $db->loadObjectList();
        $result[1] = $trclass;
        $result[2] = $dateformat;
        $result[3] = $date_dir;
        return $result;
    }

    function getJobCategories($theme) {
        $db = & JFactory::getDBO();
        $config = $this->getConfig();
        $css = $config[0];
        $dateformat = $config[1];
        $trclass = $this->setTheme($theme, $css);
        if ($this->_client_auth_key != "") {
            $alias = ", CONCAT(cat.alias,'-',cat.serverid) AS aliasid,";
        } else {
            $alias = ", CONCAT(cat.alias,'-',cat.id) AS aliasid,";
        }
        $curdate = date('Y-m-d');
        $inquery = " (SELECT COUNT(jobs.id) from `#__js_job_jobs` AS jobs 
                        WHERE cat.id = jobs.jobcategory AND jobs.status = 1 
                        AND DATE(jobs.startpublishing) <= " . $db->Quote($curdate) . " AND DATE(jobs.stoppublishing) >= " . $db->Quote($curdate) . " ) as catinjobs";
        $query = "SELECT  DISTINCT cat.id, cat.cat_title $alias";
        $query .= $inquery;
        $query .= " FROM `#__js_job_categories` AS cat 
                    LEFT JOIN `#__js_job_jobs` AS job ON cat.id = job.jobcategory                                                                                                                                                                                                                                                                                                                                                                                    
                    WHERE cat.isactive = 1 ";
        $query .= " ORDER BY cat.cat_title ";
        $db->setQuery($query);
        $result[0] = $db->loadObjectList();
        $result[1] = $trclass;
        $result[2] = $dateformat;
        return $result;
    }

    function getJobsCity($showonlycityhavejobs = 0, $theme, $noofrecord = 20) {
        $db = & JFactory::getDBO();
        $config = $this->getConfig();
        $css = $config[0];
        $dateformat = $config[1];
        $trclass = $this->setTheme($theme, $css);
        $curdate = date('Y-m-d H:i:s');
        $havingquery = "";
        if ($showonlycityhavejobs == 1) {
            $havingquery = " HAVING totaljobsbycity > 0 ";
        }
       $cityid = "city.id AS cityid,";
       $query = "SELECT $cityid city.name AS cityname, COUNT(mcity.id) AS totaljobsbycity
                    FROM `#__js_job_cities` AS city
                    LEFT JOIN `#__js_job_countries` AS country ON country.id = city.countryid 
                    LEFT JOIN `#__js_job_jobcities` AS mcity ON mcity.cityid = city.id
                    LEFT JOIN `#__js_job_jobs` AS job ON job.id = mcity.jobid 
                    WHERE country.enabled = 1 AND job.status=1 AND job.stoppublishing >= CURDATE() 
                    GROUP BY cityid $havingquery ORDER BY totaljobsbycity DESC, cityname ASC";

        $db->setQuery($query, 0, $noofrecord);
        $result1 = $db->loadObjectList();

        $result[0] = $result1;
        $result[1] = $trclass;
        $result[2] = $dateformat;
        return $result;
    }

    function getJobsStates($showonlystatehavejobs = 0, $theme, $noofrecord = 20) {
        $db = & JFactory::getDBO();
        $config = $this->getConfig();
        $css = $config[0];
        $dateformat = $config[1];
        $trclass = $this->setTheme($theme, $css);
        $curdate = date('Y-m-d');
        $havingquery = "";
        if ($showonlystatehavejobs == 1) {
            $havingquery = " HAVING totaljobsbystate > 0 ";
        }
       $stateid = "state.id AS stateid,";
        $query = "SELECT $stateid state.name AS statename,COUNT(DISTINCT job.id) AS totaljobsbystate
					FROM `#__js_job_states` AS state
					LEFT JOIN `#__js_job_cities` AS city ON state.id = city.stateid 
					LEFT JOIN `#__js_job_countries` AS country ON country.id = city.countryid 
					LEFT JOIN `#__js_job_jobcities` AS mcity ON mcity.cityid = city.id
					LEFT JOIN `#__js_job_jobs` AS job ON (job.id = mcity.jobid AND job.status =1 AND job.stoppublishing>=CURDATE() )
					WHERE country.enabled = 1  
					GROUP BY stateid $havingquery ORDER BY totaljobsbystate DESC, cityname ASC";
        $db->setQuery($query, 0, $noofrecord);
        $result1 = $db->loadObjectList();

        $result[0] = $result1;
        $result[1] = $trclass;
        $result[2] = $dateformat;
        return $result;
    }

    function getJobsCountry($showonlycountryhavejobs = 0, $theme, $noofrecord = 20) {

        $db = &$this->getDBO();
        $config = $this->getConfig();
        $css = $config[0];
        $dateformat = $config[1];
        $trclass = $this->setTheme($theme, $css);
        $havingquery = '';
        if ($showonlycountryhavejobs == 1) {
            $havingquery = " HAVING totaljobsbycountry > 0 ";
        }

        $countryid = "country.id AS countryid,";
        $query = "SELECT $countryid country.name AS countryname,COUNT(DISTINCT job.id) AS totaljobsbycountry
                    FROM `#__js_job_countries` AS country
                    LEFT JOIN `#__js_job_cities` AS city ON country.id = city.countryid 
                    LEFT JOIN `#__js_job_jobcities` AS mcity ON mcity.cityid = city.id
                    LEFT JOIN `#__js_job_jobs` AS job ON (job.id = mcity.jobid AND job.status =1 AND job.stoppublishing>=CURDATE() )
                    WHERE country.enabled = 1 
                    GROUP BY countryname $havingquery ORDER BY totaljobsbycountry DESC, countryname ASC ";
        $db->setQuery($query, 0, $noofrecord);
        $result[0] = $db->loadObjectList();
        $result[1] = $trclass;
        $result[2] = $dateformat;
        return $result;
    }

    function getJobsState($defaultcountry, $theme) {

        $db = & JFactory::getDBO();
        $config = $this->getConfig();
        $css = $config[0];
        $dateformat = $config[1];
        $default_country = $config[3];
        $trclass = $this->setTheme($theme, $css);

        $curdate = date('Y-m-d');
        $inquery = " (SELECT COUNT(job.id) from `#__js_job_jobs` AS job WHERE state.code = job.state AND job.status = 1 AND DATE(job.startpublishing) <= " . $db->Quote($curdate) . " AND DATE(job.stoppublishing) >= " . $db->Quote($curdate) . " ) as jobsbystate";
        $query = "SELECT  DISTINCT state.id, state.name,state.code, state.countrycode, ";
        $query .= $inquery;
        $query .= " FROM `#__js_job_states` AS state 
                    LEFT JOIN `#__js_job_jobs`  AS job ON state.code = job.state                                                                                                                                                                                                                                                                                                                                                                                    
                    WHERE state.enabled = " . $db->Quote('Y');
        if ($defaultcountry)
            $query .= " AND state.countrycode = " . $db->quote($default_country);
        $query .= " ORDER BY state.name ";
        $db->setQuery($query);

        $states = $db->loadObjectList();
        $query2 = "SELECT job.state, job.country, count(job.id) AS jobsbystate FROM `#__js_job_jobs` AS job WHERE job.status = 1 AND DATE(job.startpublishing) <= " . $db->Quote($curdate) . " AND DATE(job.stoppublishing) >= " . $db->Quote($curdate) . " AND job.state != '' ";
        if ($defaultcountry)
            $query2 .= " AND job.country = " . $db->quote($default_country);
        $query2 .= " AND NOT EXISTS ( SELECT id FROM `#__js_job_states` WHERE code = job.state) ";
        $query2 .= " GROUP BY job.state";

        $db->setQuery($query2);
        $states2 = $db->loadObjectList();

        $result[0] = $states;
        $result[1] = $states2;
        $result[2] = $trclass;

        return $result;
    }

    function mpGetstats($employer, $jobseeker, $jobs, $companies, $activejobs, $resumes) {
        if (!$this->_stats) {
            $db = & JFactory::getDBO();
            $result = array();
            $curdate = date('Y-m-d');
            if ($employer) {
                $query = "SELECT count(userrole.id) AS totalemployer
                    FROM `#__js_job_userroles` AS userrole
                    WHERE userrole.role = 1";
                $db->setQuery($query);
                $employer = $db->loadResult();
                $result['employer'] = $employer;
            }
            if ($jobseeker) {
                $query = "SELECT count(userrole.id) AS totaljobseeker
                    FROM `#__js_job_userroles` AS userrole
                    WHERE userrole.role = 2";
                $db->setQuery($query);
                $jobseeker = $db->loadResult();
                $result['jobseeker'] = $jobseeker;
            }
            if ($jobs) {
                $query = "SELECT count(job.id) AS totaljobs
                    FROM `#__js_job_jobs` AS job
                    WHERE job.status = 1 ";
                $db->setQuery($query);
                $totaljobs = $db->loadResult();
                $result['totaljobs'] = $totaljobs;
            }
            if ($companies) {
                $query = "SELECT count(company.id) AS totalcomapnies
                    FROM `#__js_job_companies` AS company
                    WHERE company.status = 1 ";
                $db->setQuery($query);
                $totalcompanies = $db->loadResult();
                $result['totalcompanies'] = $totalcompanies;
            }
            if ($activejobs) {
                $query = "SELECT count(job.id) AS totalactivejobs
                    FROM `#__js_job_jobs` AS job
                    WHERE job.status = 1 AND DATE(job.startpublishing) <= " . $db->Quote($curdate) . " AND DATE(job.stoppublishing) >= " . $db->Quote($curdate);
                $db->setQuery($query);
                $tatalactivejobs = $db->loadResult();
                $result['tatalactivejobs'] = $tatalactivejobs;
            }
            if ($resumes) {
                $query = "SELECT count(resume.id) AS totalresume
                    FROM `#__js_job_resume` AS resume
                    WHERE resume.status = 1 ";
                $db->setQuery($query);
                $totalresume = $db->loadResult();
                $result['totalresume'] = $totalresume;
            }
            if ($employer) {
                $query = "SELECT count(userrole.id) AS todayemployer
                    FROM `#__js_job_userroles` AS userrole
                    WHERE userrole.role = 1 AND DATE(userrole.dated) >= " . $db->Quote($curdate);
                $db->setQuery($query);
                $todyemployer = $db->loadResult();
                $result['todyemployer'] = $todyemployer;
            }
            if ($jobseeker) {
                $query = "SELECT count(userrole.id) AS todayjobseeker
                    FROM `#__js_job_userroles` AS userrole
                    WHERE userrole.role = 2 AND DATE(userrole.dated) >= " . $db->Quote($curdate);
                $db->setQuery($query);
                $todyjobseeker = $db->loadResult();
                $result['todyjobseeker'] = $todyjobseeker;
            }
            if ($jobs) {
                $query = "SELECT count(job.id) AS todayjobs
                    FROM `#__js_job_jobs` AS job
                    WHERE job.status = 1 AND DATE(job.startpublishing) >= " . $db->Quote($curdate);
                $db->setQuery($query);
                $todayjobs = $db->loadResult();
                $result['todayjobs'] = $todayjobs;
            }
            if ($companies) {
                $query = "SELECT count(company.id) AS todaycomapnies
                    FROM `#__js_job_companies` AS company
                    WHERE company.status = 1 AND DATE(company.created) >= " . $db->Quote($curdate);
                $db->setQuery($query);
                $todaycompanies = $db->loadResult();
                $result['todaycompanies'] = $todaycompanies;
            }
            if ($activejobs) {
                $query = "SELECT count(job.id) AS todayactivejobs
                    FROM `#__js_job_jobs` AS job
                    WHERE job.status = 1 AND DATE(job.startpublishing) >= " . $db->Quote($curdate);
                $db->setQuery($query);
                $todayactivejobs = $db->loadResult();
                $result['todayactivejobs'] = $todayactivejobs;
            }
            if ($resumes) {
                $query = "SELECT count(resume.id) AS todayresume
                    FROM `#__js_job_resume` AS resume
                    WHERE resume.status = 1 AND DATE(resume.create_date) >= " . $db->Quote($curdate);
                $db->setQuery($query);
                $todayresume = $db->loadResult();
                $result['todayresume'] = $todayresume;
            }


            $this->_stats = $result;
        }
        return $this->_stats;
    }

    function getTopResumes($noofresumes, $theme) {

        $db = & JFactory::getDBO();
        $config = $this->getConfig();
        $css = $config[0];
        $dateformat = $config[1];
        $trclass = $this->setTheme($theme, $css);

        $id = "resume.id AS id";
        $alias = ",CONCAT(resume.alias,'-',resume.id) AS aliasid ";
        $query = "SELECT $id, resume.application_title, resume.first_name, resume.middle_name, resume.last_name , resume.nationality as countryname
			, resume.gender, resume.job_category, resume.iamavailable, resume.jobsalaryrange, resume.jobtype, resume.heighestfinisheducation
			, resume.total_experience , resume.create_date, resume.nationality,cat.cat_title , jobtype.title AS jobtypetitle , currency.symbol
			, highesteducation.title AS educationtitle , salrange.rangestart AS rangestart,salrange.rangeend AS rangeend
			$alias
			FROM `#__js_job_resume` AS resume 
			JOIN `#__js_job_categories` AS cat ON resume.job_category = cat.id 
			JOIN `#__js_job_jobtypes` AS jobtype ON resume.jobtype = jobtype.id 
			JOIN `#__js_job_currencies` AS currency ON currency.id = resume.currencyid 
			JOIN `#__js_job_heighesteducation` AS highesteducation ON highesteducation.id = resume.heighestfinisheducation
			JOIN `#__js_job_salaryrange` AS salrange ON salrange.id = resume.jobsalaryrange
			WHERE resume.status = 1 
			ORDER BY resume.hits DESC LIMIT {$noofresumes}";
        $db->setQuery($query);
        $result[0] = $db->loadObjectList();
        $result[1] = $trclass;
        $result[2] = $dateformat;
        return $result;
    }

    function getHotJobs($noofjobs, $theme) {
        $db = & JFactory::getDBO();
        $config = $this->getConfig();
        $css = $config[0];
        $dateformat = $config[1];
        $trclass = $this->setTheme($theme, $css);
        $curdate = date('Y-m-d');
        if ($this->_client_auth_key != "") {
            $id = "job.serverid AS id";
            $alias = ",CONCAT(job.alias,'-',job.serverid) AS aliasid ";
            $companyaliasid = ", CONCAT(company.alias,'-',company.serverid) AS companyaliasid ";
        } else {
            $id = "job.id AS id";
            $alias = ",CONCAT(job.alias,'-',job.id) AS aliasid ";
            $companyaliasid = ", CONCAT(company.alias,'-',company.id) AS companyaliasid ";
        }
        $query = "SELECT COUNT(apply.jobid) as totalapply, $id, job.title, job.jobcategory, job.created, cat.cat_title
			, company.id AS companyid, company.name AS companyname, jobtype.title AS jobtypetitle,subcat.title AS subcat_title
			$alias $companyaliasid
			
			FROM `#__js_job_jobs` AS job 
			JOIN `#__js_job_jobapply` AS apply ON job.id = apply.jobid 
			JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id 
			LEFT JOIN `#__js_job_subcategories` AS subcat ON job.subcategoryid = subcat.id
			JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id 
			LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id 
			WHERE job.status = 1 AND DATE(job.startpublishing) <= " . $db->Quote($curdate) . " AND DATE(job.stoppublishing) >= " . $db->Quote($curdate) . "
			GROUP BY apply.jobid ORDER BY totalapply DESC LIMIT {$noofjobs}";
        //echo $query;
        $db->setQuery($query);
        $result[0] = $db->loadObjectList();
        $result[1] = $trclass;
        $result[2] = $dateformat;
        return $result;
    }

    function getNewestJobs($noofjobs, $theme) {

        $db = & JFactory::getDBO();
        $config = $this->getConfig();
        $css = $config[0];
        $dateformat = $config[1];
        $trclass = $this->setTheme($theme, $css);
        $curdate = date('Y-m-d');

        if ($this->_client_auth_key != "") {
            $id = "job.serverid AS id";
            $alias = ",CONCAT(job.alias,'-',job.serverid) AS aliasid ";
            $companyaliasid = ", CONCAT(company.alias,'-',company.serverid) AS companyaliasid ";
        } else {
            $id = "job.id AS id";
            $alias = ",CONCAT(job.alias,'-',job.id) AS aliasid ";
            $companyaliasid = ", CONCAT(company.alias,'-',company.id) AS companyaliasid ";
        }
        $query = "SELECT $id,job.title, job.jobcategory, job.created, cat.cat_title
			, company.id AS companyid, company.name AS companyname, jobtype.title AS jobtypetitle
			$alias $companyaliasid
			 
			FROM `#__js_job_jobs` AS job 
			JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id 
			JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id 
			LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id 
			WHERE job.status = 1 AND DATE(job.startpublishing) <= " . $db->Quote($curdate) . " AND DATE(job.stoppublishing) >= " . $db->Quote($curdate) . "
			ORDER BY created DESC LIMIT {$noofjobs}";
        $db->setQuery($query);
        $result[0] = $db->loadObjectList();
        $result[1] = $trclass;
        $result[2] = $dateformat;
        return $result;
    }

    function getNewestResumes($noofresumes, $theme) {
        $db = & JFactory::getDBO();
        $config = $this->getConfig();
        $css = $config[0];
        $dateformat = $config[1];
        $trclass = $this->setTheme($theme, $css);

        $id = "resume.id AS id";
        $alias = ",CONCAT(resume.alias,'-',resume.id) AS aliasid ";
        $query = "SELECT $id, resume.application_title, resume.first_name,resume.last_name 
				,resume.nationality, resume.gender, resume.iamavailable, resume.job_category 
				,resume.jobsalaryrange, resume.jobtype, resume.heighestfinisheducation, resume.total_experience 
				,resume.create_date, cat.cat_title, jobtype.title AS jobtypetitle, education.title as educationtitle
				,country.name AS countryname, salary.rangestart, salary.rangeend,currency.symbol
				$alias
			FROM `#__js_job_resume` AS resume 
			JOIN `#__js_job_categories` AS cat ON resume.job_category = cat.id 
			JOIN `#__js_job_jobtypes` AS jobtype ON resume.jobtype = jobtype.id 

			LEFT JOIN `#__js_job_heighesteducation` AS education ON resume.heighestfinisheducation = education.id 
			LEFT JOIN `#__js_job_salaryrange` AS salary ON resume.jobsalaryrange = salary.id 
			LEFT JOIN `#__js_job_countries` AS country ON resume.nationality = country.id 
			LEFT JOIN `#__js_job_currencies` AS currency ON currency.id = resume.currencyid 

			WHERE resume.status = 1 ORDER BY create_date DESC LIMIT {$noofresumes}";
        $db->setQuery($query);
        $resume = $db->loadObjectList();

        $result[0] = $resume;
        $result[1] = $trclass;
        $result[2] = $dateformat;

        return $result;
    }

    function getTopJobs($noofjobs, $theme) {
        $db = & JFactory::getDBO();
        $config = $this->getConfig();
        $css = $config[0];
        $dateformat = $config[1];
        $trclass = $this->setTheme($theme, $css);
        if ($this->_client_auth_key != "") {
            $id = "job.serverid AS id";
            $alias = ",CONCAT(job.alias,'-',job.serverid) AS aliasid ";
            $companyaliasid = ", CONCAT(company.alias,'-',company.serverid) AS companyaliasid ";
        } else {
            $id = "job.id AS id";
            $alias = ",CONCAT(job.alias,'-',job.id) AS aliasid ";
            $companyaliasid = ", CONCAT(company.alias,'-',company.id) AS companyaliasid ";
        }
        $curdate = date('Y-m-d');
        $query = "SELECT $id, job.title, job.jobcategory, job.created, cat.cat_title
			, company.id AS companyid, company.name AS companyname, jobtype.title AS jobtypetitle 
			$alias $companyaliasid
			
			FROM `#__js_job_jobs` AS job 
			JOIN `#__js_job_categories` AS cat ON job.jobcategory = cat.id 
			JOIN `#__js_job_jobtypes` AS jobtype ON job.jobtype = jobtype.id 
			LEFT JOIN `#__js_job_companies` AS company ON job.companyid = company.id 
			WHERE job.status = 1 AND DATE(job.startpublishing) <= " . $db->Quote($curdate) . " AND DATE(job.stoppublishing) >= " . $db->Quote($curdate) . "
			ORDER BY job.hits DESC LIMIT {$noofjobs}";
        //echo $query;
        $db->setQuery($query);
        $result[0] = $db->loadObjectList();
        ;
        $result[1] = $trclass;
        $result[2] = $dateformat;
        return $result;
    }

    function jobsearch($sh_category, $sh_subcategory, $sh_company, $sh_jobtype, $sh_jobstatus, $sh_shift, $sh_salaryrange, $plugin) {
        $db = & JFactory::getDBO();

        // Configurations *********************************************
        $query = "SELECT * FROM `#__js_job_config` WHERE configname = 'date_format' ";
        $db->setQuery($query);
        $configs = $db->loadObjectList();
        foreach ($configs AS $config) {
            if ($config->configname == 'date_format')
                $dateformat = $config->configvalue;
        }
        $firstdash = strpos($dateformat, '-', 0);
        $firstvalue = substr($dateformat, 0, $firstdash);
        $firstdash = $firstdash + 1;
        $seconddash = strpos($dateformat, '-', $firstdash);
        $secondvalue = substr($dateformat, $firstdash, $seconddash - $firstdash);
        $seconddash = $seconddash + 1;
        $thirdvalue = substr($dateformat, $seconddash, strlen($dateformat) - $seconddash);
        $js_dateformat = '%' . $firstvalue . '-%' . $secondvalue . '-%' . $thirdvalue;


        // Categories *********************************************
        if ($sh_category == 1) {
            $query = "SELECT * FROM `#__js_job_categories` WHERE isactive = 1 ORDER BY cat_title ";
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            if (isset($rows)) {
                $jobcategories = array();
                $jobcategories[] = array('value' => JText::_(''), 'text' => JText::_('JS_SEARCH_ALL'));
                foreach ($rows as $row)
                    $jobcategories[] = array('value' => JText::_($row->id), 'text' => JText::_($row->cat_title));
            }
            if (isset($plugin) && $plugin == 1)
                $job_categories = JHTML::_('select.genericList', $jobcategories, 'jobcategory', 'class="inputbox" style="width:160px;" ' . 'onChange="plgfj_getsubcategories(\'plgfj_subcategory\', this.value)"', 'value', 'text', '');
            else
                $job_categories = JHTML::_('select.genericList', $jobcategories, 'jobcategory', 'class="inputbox" style="width:160px;" ' . 'onChange="modfj_getsubcategories(\'modfj_subcategory\', this.value)"', 'value', 'text', '');
        }
        // Sub Categories *********************************************
        if ($sh_subcategory == 1) {
            $jobsubcategories = array();
            $jobsubcategories[] = array('value' => JText::_(''), 'text' => JText::_('JS_SEARCH_ALL'));
            $job_subcategories = JHTML::_('select.genericList', $jobsubcategories, 'jobsubcategory', 'class="inputbox" style="width:160px;" ' . '', 'value', 'text', '');
        }

        //Companies *********************************************
        if ($sh_company == 1) {
            $query = "SELECT id, name FROM `#__js_job_companies` ORDER BY name ASC ";
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            if (isset($rows)) {
                $companies = array();
                $companies[] = array('value' => JText::_(''), 'text' => JText::_('JS_SEARCH_ALL'));
                foreach ($rows as $row)
                    $companies[] = array('value' => $row->id, 'text' => $row->name);
            }
            $search_companies = JHTML::_('select.genericList', $companies, 'company', 'class="inputbox" style="width:160px;" ' . '', 'value', 'text', '');
        }
        //Job Types *********************************************
        if ($sh_jobtype == 1) {
            $query = "SELECT id, title FROM `#__js_job_jobtypes` WHERE isactive = 1 ORDER BY id ASC ";
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            if (isset($rows)) {
                $jobtype = array();
                $jobtype[] = array('value' => JText::_(''), 'text' => JText::_('JS_SEARCH_ALL'));
                foreach ($rows as $row)
                    $jobtype[] = array('value' => JText::_($row->id), 'text' => JText::_($row->title));
            }
            $job_type = JHTML::_('select.genericList', $jobtype, 'jobtype', 'class="inputbox" style="width:160px;" ' . '', 'value', 'text', '');
        }
        //Job Status *********************************************
        if ($sh_jobstatus == 1) {
            $query = "SELECT id, title FROM `#__js_job_jobstatus` WHERE isactive = 1 ORDER BY id ASC ";
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            if (isset($rows)) {
                $jobstatus = array();
                $jobstatus[] = array('value' => JText::_(''), 'text' => JText::_('JS_SEARCH_ALL'));
                foreach ($rows as $row)
                    $jobstatus[] = array('value' => JText::_($row->id), 'text' => JText::_($row->title));
            }
            $job_status = JHTML::_('select.genericList', $jobstatus, 'jobstatus', 'class="inputbox" style="width:160px;" ' . '', 'value', 'text', '');
        }

        //Shifts *********************************************
        if ($sh_shift == 1) {
            $query = "SELECT id, title FROM `#__js_job_shifts` WHERE isactive = 1 ORDER BY id ASC ";
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            if (isset($rows)) {
                $shifts = array();
                $shifts[] = array('value' => JText::_(''), 'text' => JText::_('JS_SEARCH_ALL'));
                foreach ($rows as $row)
                    $shifts[] = array('value' => JText::_($row->id), 'text' => JText::_($row->title));
            }
            $search_shift = JHTML::_('select.genericList', $shifts, 'shift', 'class="inputbox" style="width:160px;" ' . '', 'value', 'text', '');
        }
        // Salary Rnage *********************************************
        if ($sh_salaryrange == 1) {
            $query = "SELECT * FROM `#__js_job_salaryrange` ORDER BY id ";
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            if (isset($rows)) {
                $salaryrangefrom = array();
                $salaryrangeto = array();
                $salaryrangefrom[] = array('value' => JText::_(''), 'text' => JText::_('JS_FROM'));
                $salaryrangeto[] = array('value' => JText::_(''), 'text' => JText::_('JS_TO'));
                foreach ($rows as $row) {
                    //$salrange = $currency . $row->rangestart.' - '.$currency . $row->rangeend;
                    $salrange = $row->rangestart; //.' - '.$currency . $row->rangeend;
                    $salaryrangefrom[] = array('value' => JText::_($row->id), 'text' => JText::_($salrange));
                    $salaryrangeto[] = array('value' => JText::_($row->id), 'text' => JText::_($salrange));
                }
                $query = "SELECT id, title FROM `#__js_job_salaryrangetypes` WHERE status = 1 ORDER BY id ASC ";
                $db->setQuery($query);
                $rows = $db->loadObjectList();
                $types = array();
                foreach ($rows as $row) {
                    $types[] = array('value' => $row->id, 'text' => $row->title);
                }
            }
            $salaryrangefrom = JHTML::_('select.genericList', $salaryrangefrom, 'salaryrangefrom', 'class="inputbox" ' . '', 'value', 'text', '');
            $salaryrangeto = JHTML::_('select.genericList', $salaryrangeto, 'salaryrangeto', 'class="inputbox" ' . '', 'value', 'text', '');
            $salaryrangetypes = JHTML::_('select.genericList', $types, 'salaryrangetype', 'class="inputbox" ' . '', 'value', 'text', 2);

            // get combo of currencies 
            $currencycombo = $this->getCurrencyCombo();
        }


        if (isset($js_dateformat))
            $result[0] = $js_dateformat;
        if (isset($currencycombo))
            $result[1] = $currencycombo;
        if (isset($job_categories))
            $result[2] = $job_categories;

        if (isset($search_companies))
            $result[3] = $search_companies;
        if (isset($job_type))
            $result[4] = $job_type;

        if (isset($job_status))
            $result[5] = $job_status;
        if (isset($search_shift))
            $result[6] = $search_shift;
        if (isset($salaryrangefrom))
            $result[7] = $salaryrangefrom;

        if (isset($salaryrangeto))
            $result[8] = $salaryrangeto;
        if (isset($salaryrangetypes))
            $result[9] = $salaryrangetypes;
        if (isset($job_subcategories))
            $result[10] = $job_subcategories;

        return $result;
    }

    function resumesearch($sh_gender, $sh_nationality, $sh_category, $sh_subcategory, $sh_jobtype, $sh_heighesteducation, $sh_salaryrange, $plugin) {
        $db = & JFactory::getDBO();
        // Gender *********************************************
        if ($sh_gender == 1) {
            $genders = array(
                '0' => array('value' => '', 'text' => JText::_('JS_SEARCH_ALL')),
                '1' => array('value' => 1, 'text' => JText::_('JS_MALE')),
                '2' => array('value' => 2, 'text' => JText::_('JS_FEMALE')),);
            $gender = JHTML::_('select.genericList', $genders, 'gender', 'class="inputbox" style="width:160px;" ' . '', 'value', 'text', '');
        }
        // Natinality *********************************************
        if ($sh_nationality == 1) {
            $query = "SELECT * FROM `#__js_job_countries` WHERE enabled = 1 ORDER BY name ASC ";
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            if (isset($rows)) {
                $countries = array();
                $countries[] = array('value' => JText::_(''), 'text' => JText::_('JS_CHOOSE_COUNTRY'));
                foreach ($rows as $row) {
                    $countries[] = array('value' => $row->id, 'text' => JText::_($row->name));
                }
            }
            $nationality = JHTML::_('select.genericList', $countries, 'nationality', 'class="inputbox" style="width:160px;" ' . '', 'value', 'text', '');
        }

        // Categories *********************************************
        if ($sh_category == 1) {
            $query = "SELECT * FROM `#__js_job_categories` WHERE isactive = 1 ORDER BY cat_title ";
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            if (isset($rows)) {
                $jobcategories = array();
                $jobcategories[] = array('value' => JText::_(''), 'text' => JText::_('JS_SEARCH_ALL'));
                foreach ($rows as $row)
                    $jobcategories[] = array('value' => JText::_($row->id), 'text' => JText::_($row->cat_title));
            }
            if (isset($plugin) && $plugin == 1)
                $job_categories = JHTML::_('select.genericList', $jobcategories, 'jobcategory', 'class="inputbox" style="width:160px;" ' . 'onChange="plgfj_getsubcategories(\'plgresumefj_subcategory\', this.value)"', 'value', 'text', '');
            else
                $job_categories = JHTML::_('select.genericList', $jobcategories, 'jobcategory', 'class="inputbox" style="width:160px;" ' . 'onChange="modfj_getsubcategories(\'modresumefj_subcategory\', this.value)"', 'value', 'text', '');
        }
        // Sub Categories *********************************************
        if ($sh_subcategory == 1) {
            $jobsubcategories = array();
            $jobsubcategories[] = array('value' => JText::_(''), 'text' => JText::_('JS_SEARCH_ALL'));
            $job_subcategories = JHTML::_('select.genericList', $jobsubcategories, 'jobsubcategory', 'class="inputbox" style="width:160px;" ' . '', 'value', 'text', '');
        }

        //Job Types *********************************************
        if ($sh_jobtype == 1) {
            $query = "SELECT id, title FROM `#__js_job_jobtypes` WHERE isactive = 1 ORDER BY id ASC ";
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            if (isset($rows)) {
                $jobtype = array();
                $jobtype[] = array('value' => JText::_(''), 'text' => JText::_('JS_SEARCH_ALL'));
                foreach ($rows as $row)
                    $jobtype[] = array('value' => JText::_($row->id), 'text' => JText::_($row->title));
            }
            $job_type = JHTML::_('select.genericList', $jobtype, 'jobtype', 'class="inputbox" style="width:160px;" ' . '', 'value', 'text', '');
        }
        //Job Heighest Education  *********************************************
        if ($sh_heighesteducation == 1) {
            $query = "SELECT id, title FROM `#__js_job_heighesteducation` WHERE isactive = 1 ORDER BY id ASC ";
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            if (isset($rows)) {
                $heighesteducation = array();
                $heighesteducation[] = array('value' => JText::_(''), 'text' => JText::_('JS_SEARCH_ALL'));
                foreach ($rows as $row)
                    $heighesteducation[] = array('value' => JText::_($row->id), 'text' => JText::_($row->title));
            }
            $heighest_finisheducation = JHTML::_('select.genericList', $heighesteducation, 'heighestfinisheducation', 'class="inputbox" style="width:160px;" ' . '', 'value', 'text', '');
        }

        // Salary Rnage *********************************************
        if ($sh_salaryrange == 1) {
            $query = "SELECT * FROM `#__js_job_salaryrange` ORDER BY 'id' ";
            $db->setQuery($query);
            $rows = $db->loadObjectList();
            if (isset($rows)) {
                $jobsalaryrange = array();
                $jobsalaryrange[] = array('value' => JText::_(''), 'text' => JText::_('JS_SEARCH_ALL'));
                foreach ($rows as $row) {
                    $salrange = $row->rangestart . ' - ' . $row->rangeend;
                    $jobsalaryrange[] = array('value' => JText::_($row->id), 'text' => JText::_($salrange));
                }
            }
            $salary_range = JHTML::_('select.genericList', $jobsalaryrange, 'jobsalaryrange', 'class="inputbox" style="width:160px;" ' . '', 'value', 'text', '');
            // currencies 
            $currencycombo = $this->getCurrencyCombo();
        }
        if (isset($gender))
            $result[0] = $gender;
        if (isset($nationality))
            $result[1] = $nationality;
        if (isset($job_categories))
            $result[2] = $job_categories;
        if (isset($job_type))
            $result[3] = $job_type;
        if (isset($heighest_finisheducation))
            $result[4] = $heighest_finisheducation;
        if (isset($salary_range))
            $result[5] = $salary_range;
        if (isset($currencycombo))
            $result[6] = $currencycombo;
        if (isset($job_subcategories))
            $result[7] = $job_subcategories;
        return $result;
    }

    function getCurrencyCombo() {
        $db = & JFactory::getDBO();
        $query = "SELECT id, symbol FROM `#__js_job_currencies` WHERE status = 1 ORDER BY id ASC ";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $currency = array();
        $currency[] = array('value' => JText::_(''), 'text' => JText::_('JS_SEARCH_ALL'));
        foreach ($rows as $row) {
            $currency[] = array('value' => $row->id, 'text' => $row->symbol);
        }
        $currencycombo = JHTML::_('select.genericList', $currency, 'currency', 'class="inputbox" ' . '', 'value', 'text', '');
        return $currencycombo;
    }

}

?>
