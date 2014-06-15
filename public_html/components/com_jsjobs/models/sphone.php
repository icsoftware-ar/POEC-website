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

class JSJobsModelSphone extends JModelLegacy {

    var $_config = null;

    function __construct() {
        parent :: __construct();
        $this->_arv = "/\aseofm/rvefli/ctvrnaa/kme/\rfer";
        $this->_ptr = "/\blocalh";
    }

    function &getJsJobsModel() {
        $componentPath = 'components' . DS . 'com_jsjobs';
        require_once $componentPath . DS . 'models' . DS . 'jsjobs.php';
        $model = new JSJobsModelJsjobs();
        return $model;
    }

    /*     * ******************************for smart phone ********************************** */

    function & getGoldJobs($limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $curdate = date('Y-m-d H:i:s');

        $query = "SELECT COUNT(goldjob.id)
		FROM " . $db->nameQuote('#__js_job_goldjobs') . " AS goldjob 
		JOIN " . $db->nameQuote('#__js_job_jobs') . " AS job ON job.id=goldjob.jobid
		JOIN " . $db->nameQuote('#__js_job_employerpackages') . " AS package ON package.id = goldjob.packageid 
		WHERE goldjob.status = 1 AND job.status = 1 AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate) . "
		AND DATE_ADD(goldjob.created,INTERVAL package.goldjobsexpireindays DAY) >= CURDATE() ";

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT goldjob.packageid, goldjob.startdate, goldjob.enddate
		, job.id, job.title, job.created, job.country, job.state, job.county, job.city, cat.cat_title
		, company.id AS companyid, company.name AS companyname, company.logofilename
		, country.name AS countryname, state.name AS statename, county.name AS countyname, city.name AS cityname
		, job.state AS state,job.county AS county,job.city AS city		
		, jobtype.title AS jobtypetitle ,category.cat_title 
		, job.iseducationminimax, job.educationminimax, education.title AS educationtitle ,mineducation.title AS mineducationtitle, maxeducation.title AS maxeducationtitle
		, shift.title as shifttitle , job.isexperienceminimax, job.experienceminimax, experience.title AS experiencetitle ,minexperience.title AS minexperiencetitle, maxexperience.title AS maxexperiencetitle
		, salaryrangefrom.rangestart,salaryrangeto.rangeend,salaryrangetype.title AS salaryrangetype, currency.symbol as currencysymbol

		FROM " . $db->nameQuote('#__js_job_jobs') . " AS job 
		JOIN " . $db->nameQuote('#__js_job_goldjobs') . " AS goldjob  ON job.id=goldjob.jobid
		JOIN " . $db->nameQuote('#__js_job_jobtypes') . " AS jobtype ON job.jobtype=jobtype.id
		JOIN " . $db->nameQuote('#__js_job_categories') . " AS cat ON job.jobcategory = cat.id 
		JOIN " . $db->nameQuote('#__js_job_employerpackages') . " AS package ON package.id = goldjob.packageid 
		LEFT JOIN " . $db->nameQuote('#__js_job_companies') . " AS company ON company.id=job.companyid 
		LEFT JOIN " . $db->nameQuote('#__js_job_categories') . " AS category ON category.id=job.jobcategory  
		LEFT JOIN " . $db->nameQuote('#__js_job_heighesteducation') . " AS education ON job.educationid = education.id
		LEFT JOIN " . $db->nameQuote('#__js_job_heighesteducation') . " AS mineducation ON job.mineducationrange = mineducation.id
		LEFT JOIN " . $db->nameQuote('#__js_job_heighesteducation') . " AS maxeducation ON job.maxeducationrange = maxeducation.id
		LEFT JOIN " . $db->nameQuote('#__js_job_shifts') . " AS shift ON shift.id=job.shift  
		LEFT JOIN " . $db->nameQuote('#__js_job_experiences') . " AS experience ON job.experienceid = experience.id
		LEFT JOIN " . $db->nameQuote('#__js_job_experiences') . " AS minexperience ON job.minexperiencerange = minexperience.id
		LEFT JOIN " . $db->nameQuote('#__js_job_experiences') . " AS maxexperience ON job.maxexperiencerange = maxexperience.id
		LEFT JOIN " . $db->nameQuote('#__js_job_salaryrange') . " AS salaryrangefrom ON salaryrangefrom.id=job.salaryrangefrom  
		LEFT JOIN " . $db->nameQuote('#__js_job_salaryrange') . " AS salaryrangeto ON salaryrangeto.id=job.salaryrangeto  
		LEFT JOIN " . $db->nameQuote('#__js_job_salaryrangetypes') . " AS salaryrangetype ON salaryrangetype.id=job.salaryrangetype  
		LEFT JOIN " . $db->nameQuote('#__js_job_currencies') . " AS currency ON currency.id=job.currencyid  
		LEFT JOIN " . $db->nameQuote('#__js_job_countries') . " AS country ON country.code=job.country 
		LEFT JOIN " . $db->nameQuote('#__js_job_states') . " AS state ON state.code=job.state  
		LEFT JOIN " . $db->nameQuote('#__js_job_counties') . " AS county ON county.code=job.county 
		LEFT JOIN " . $db->nameQuote('#__js_job_cities') . " AS city ON city.code=job.city  
		WHERE goldjob.status = 1 AND job.status = 1 AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate) . "
		AND DATE_ADD(goldjob.created,INTERVAL package.goldjobsexpireindays DAY) >= CURDATE()
		ORDER BY created DESC ";

        //echo $limitstart;
        //echo '<br>'.$limit;
        //echo $query;
        $db->setQuery($query, $limitstart, $limit);
        $goldjobs = $db->loadObjectList();

        $location = '';
        $darray = Array();
        foreach ($goldjobs AS $job) {
            $location = '';
            $salaryrange = '';
            $educationtitle = '';
            $experiencetitle = '';
            if (($job->cityname))
                $location = $job->cityname; elseif (($job->city))
                $location = $job->city;
            elseif (($job->countyname))
                $location = $job->countyname; elseif (($job->county))
                $location = $job->county;
            elseif (($job->statename))
                $location = $job->statename; elseif (($job->state))
                $location = $job->state;
            elseif (($job->countryname))
                $location = $job->countryname;

            if ($job->rangestart)
                $salaryrange = $job->currencysymbol . $job->rangestart . ' - ' . $job->currencysymbol . $job->rangeend . ' ' . $job->salaryrangetype;

            if ($job->iseducationminimax == 1) {
                if ($job->educationminimax == 1)
                    $title = JText::_('JS_MIN');
                else
                    $title = JText::_('JS_MAX');
                $educationtitle = $title . ' ' . $job->educationtitle;
            }
            else
                $educationtitle = $job->mineducationtitle . ' - ' . $job->maxeducationtitle;

            if ($job->isexperienceminimax == 1) {
                if ($job->experienceminimax == 1)
                    $title = JText::_('JS_MIN');
                else
                    $title = JText::_('JS_MAX');
                $experiencetitle = $title . ' ' . $job->experiencetitle;
            }
            else
                $experiencetitle = $job->minexperiencetitle . ' - ' . $job->maxexperiencetitle;

            $darray[] = array('id' => $job->id, 'jobtitle' => $job->title, 'company' => $job->companyname, 'category' => $job->cat_title
                , 'salaryrange' => $salaryrange, 'education' => $educationtitle, 'shift' => $job->shifttitle, 'experience' => $experiencetitle, 'location' => $location);
        }
        $result[0] = $darray;
        //print_r($darray);
        $result['totalrecords'] = $total;
        return $result;
    }

    function & getFeaturedJobs($limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $curdate = date('Y-m-d H:i:s');

        $query = "SELECT COUNT(featuredjob.id)
		FROM " . $db->nameQuote('#__js_job_featuredjobs') . " AS featuredjob 
		JOIN " . $db->nameQuote('#__js_job_jobs') . " AS job ON job.id=featuredjob.jobid
		JOIN " . $db->nameQuote('#__js_job_employerpackages') . " AS package ON package.id = featuredjob.packageid 
		WHERE featuredjob.status = 1 AND job.status = 1 AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate) . "
		AND DATE_ADD(featuredjob.created,INTERVAL package.featuredjobsexpireindays DAY) >= CURDATE() ";

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT featuredjob.packageid, featuredjob.startdate, featuredjob.enddate
		, job.id, job.title, job.created, job.country, job.state, job.county, job.city, cat.cat_title
		, company.id AS companyid, company.name AS companyname, company.logofilename
		, country.name AS countryname, state.name AS statename, county.name AS countyname, city.name AS cityname
		, job.state AS state,job.county AS county,job.city AS city		
		, jobtype.title AS jobtypetitle ,category.cat_title 
		, job.iseducationminimax, job.educationminimax, education.title AS educationtitle ,mineducation.title AS mineducationtitle, maxeducation.title AS maxeducationtitle
		, shift.title as shifttitle , job.isexperienceminimax, job.experienceminimax, experience.title AS experiencetitle ,minexperience.title AS minexperiencetitle, maxexperience.title AS maxexperiencetitle
		, salaryrangefrom.rangestart,salaryrangeto.rangeend,salaryrangetype.title AS salaryrangetype, currency.symbol as currencysymbol

		FROM " . $db->nameQuote('#__js_job_jobs') . " AS job 
		JOIN " . $db->nameQuote('#__js_job_featuredjobs') . " AS featuredjob  ON job.id=featuredjob.jobid
		JOIN " . $db->nameQuote('#__js_job_jobtypes') . " AS jobtype ON job.jobtype=jobtype.id
		JOIN " . $db->nameQuote('#__js_job_categories') . " AS cat ON job.jobcategory = cat.id 
		JOIN " . $db->nameQuote('#__js_job_employerpackages') . " AS package ON package.id = featuredjob.packageid 
		LEFT JOIN " . $db->nameQuote('#__js_job_companies') . " AS company ON company.id=job.companyid 
		LEFT JOIN " . $db->nameQuote('#__js_job_categories') . " AS category ON category.id=job.jobcategory  
		LEFT JOIN " . $db->nameQuote('#__js_job_heighesteducation') . " AS education ON job.educationid = education.id
		LEFT JOIN " . $db->nameQuote('#__js_job_heighesteducation') . " AS mineducation ON job.mineducationrange = mineducation.id
		LEFT JOIN " . $db->nameQuote('#__js_job_heighesteducation') . " AS maxeducation ON job.maxeducationrange = maxeducation.id
		LEFT JOIN " . $db->nameQuote('#__js_job_shifts') . " AS shift ON shift.id=job.shift  
		LEFT JOIN " . $db->nameQuote('#__js_job_experiences') . " AS experience ON job.experienceid = experience.id
		LEFT JOIN " . $db->nameQuote('#__js_job_experiences') . " AS minexperience ON job.minexperiencerange = minexperience.id
		LEFT JOIN " . $db->nameQuote('#__js_job_experiences') . " AS maxexperience ON job.maxexperiencerange = maxexperience.id
		LEFT JOIN " . $db->nameQuote('#__js_job_salaryrange') . " AS salaryrangefrom ON salaryrangefrom.id=job.salaryrangefrom  
		LEFT JOIN " . $db->nameQuote('#__js_job_salaryrange') . " AS salaryrangeto ON salaryrangeto.id=job.salaryrangeto  
		LEFT JOIN " . $db->nameQuote('#__js_job_salaryrangetypes') . " AS salaryrangetype ON salaryrangetype.id=job.salaryrangetype  
		LEFT JOIN " . $db->nameQuote('#__js_job_currencies') . " AS currency ON currency.id=job.currencyid  
		LEFT JOIN " . $db->nameQuote('#__js_job_countries') . " AS country ON country.code=job.country 
		LEFT JOIN " . $db->nameQuote('#__js_job_states') . " AS state ON state.code=job.state  
		LEFT JOIN " . $db->nameQuote('#__js_job_counties') . " AS county ON county.code=job.county 
		LEFT JOIN " . $db->nameQuote('#__js_job_cities') . " AS city ON city.code=job.city  
		WHERE featuredjob.status = 1 AND job.status = 1 AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate) . "
		AND DATE_ADD(featuredjob.created,INTERVAL package.featuredjobsexpireindays DAY) >= CURDATE()
		ORDER BY created DESC ";

        //echo $limitstart;
        //echo '<br>'.$limit;
        //echo $query;
        $db->setQuery($query, $limitstart, $limit);
        $featuredjobs = $db->loadObjectList();

        $location = '';
        $darray = Array();
        foreach ($featuredjobs AS $job) {
            $location = '';
            $salaryrange = '';
            $educationtitle = '';
            $experiencetitle = '';
            if (($job->cityname))
                $location = $job->cityname; elseif (($job->city))
                $location = $job->city;
            elseif (($job->countyname))
                $location = $job->countyname; elseif (($job->county))
                $location = $job->county;
            elseif (($job->statename))
                $location = $job->statename; elseif (($job->state))
                $location = $job->state;
            elseif (($job->countryname))
                $location = $job->countryname;

            if ($job->rangestart)
                $salaryrange = $job->currencysymbol . $job->rangestart . ' - ' . $job->currencysymbol . $job->rangeend . ' ' . $job->salaryrangetype;

            if ($job->iseducationminimax == 1) {
                if ($job->educationminimax == 1)
                    $title = JText::_('JS_MIN');
                else
                    $title = JText::_('JS_MAX');
                $educationtitle = $title . ' ' . $job->educationtitle;
            }
            else
                $educationtitle = $job->mineducationtitle . ' - ' . $job->maxeducationtitle;

            if ($job->isexperienceminimax == 1) {
                if ($job->experienceminimax == 1)
                    $title = JText::_('JS_MIN');
                else
                    $title = JText::_('JS_MAX');
                $experiencetitle = $title . ' ' . $job->experiencetitle;
            }
            else
                $experiencetitle = $job->minexperiencetitle . ' - ' . $job->maxexperiencetitle;

            $darray[] = array('id' => $job->id, 'jobtitle' => $job->title, 'company' => $job->companyname, 'category' => $job->cat_title
                , 'salaryrange' => $salaryrange, 'education' => $educationtitle, 'shift' => $job->shifttitle, 'experience' => $experiencetitle, 'location' => $location);
        }
        $result[0] = $darray;
        //print_r($darray);
        $result['totalrecords'] = $total;
        return $result;
    }

    function & getLatestJobs($limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $curdate = date('Y-m-d H:i:s');

        $query = "SELECT COUNT(job.id)
		FROM  " . $db->nameQuote('#__js_job_jobs') . " AS job 
		WHERE job.status = 1 AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate);

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT job.id, job.title, job.created, job.country, job.state, job.county, job.city, cat.cat_title
		, company.id AS companyid, company.name AS companyname, company.logofilename
		, country.name AS countryname, state.name AS statename, county.name AS countyname, city.name AS cityname
		, job.state AS state,job.county AS county,job.city AS city		
		, jobtype.title AS jobtypetitle ,category.cat_title 
		, job.iseducationminimax, job.educationminimax, education.title AS educationtitle ,mineducation.title AS mineducationtitle, maxeducation.title AS maxeducationtitle
		, shift.title as shifttitle , job.isexperienceminimax, job.experienceminimax, experience.title AS experiencetitle ,minexperience.title AS minexperiencetitle, maxexperience.title AS maxexperiencetitle
		, salaryrangefrom.rangestart,salaryrangeto.rangeend,salaryrangetype.title AS salaryrangetype, currency.symbol as currencysymbol

		FROM " . $db->nameQuote('#__js_job_jobs') . " AS job 
		JOIN " . $db->nameQuote('#__js_job_jobtypes') . " AS jobtype ON job.jobtype=jobtype.id
		JOIN " . $db->nameQuote('#__js_job_categories') . " AS cat ON job.jobcategory = cat.id 
		LEFT JOIN " . $db->nameQuote('#__js_job_companies') . " AS company ON company.id=job.companyid 
		LEFT JOIN " . $db->nameQuote('#__js_job_categories') . " AS category ON category.id=job.jobcategory  
		LEFT JOIN " . $db->nameQuote('#__js_job_heighesteducation') . " AS education ON job.educationid = education.id
		LEFT JOIN " . $db->nameQuote('#__js_job_heighesteducation') . " AS mineducation ON job.mineducationrange = mineducation.id
		LEFT JOIN " . $db->nameQuote('#__js_job_heighesteducation') . " AS maxeducation ON job.maxeducationrange = maxeducation.id
		LEFT JOIN " . $db->nameQuote('#__js_job_shifts') . " AS shift ON shift.id=job.shift  
		LEFT JOIN " . $db->nameQuote('#__js_job_experiences') . " AS experience ON job.experienceid = experience.id
		LEFT JOIN " . $db->nameQuote('#__js_job_experiences') . " AS minexperience ON job.minexperiencerange = minexperience.id
		LEFT JOIN " . $db->nameQuote('#__js_job_experiences') . " AS maxexperience ON job.maxexperiencerange = maxexperience.id
		LEFT JOIN " . $db->nameQuote('#__js_job_salaryrange') . " AS salaryrangefrom ON salaryrangefrom.id=job.salaryrangefrom  
		LEFT JOIN " . $db->nameQuote('#__js_job_salaryrange') . " AS salaryrangeto ON salaryrangeto.id=job.salaryrangeto  
		LEFT JOIN " . $db->nameQuote('#__js_job_salaryrangetypes') . " AS salaryrangetype ON salaryrangetype.id=job.salaryrangetype  
		LEFT JOIN " . $db->nameQuote('#__js_job_currencies') . " AS currency ON currency.id=job.currencyid  
		LEFT JOIN " . $db->nameQuote('#__js_job_countries') . " AS country ON country.code=job.country 
		LEFT JOIN " . $db->nameQuote('#__js_job_states') . " AS state ON state.code=job.state  
		LEFT JOIN " . $db->nameQuote('#__js_job_counties') . " AS county ON county.code=job.county 
		LEFT JOIN " . $db->nameQuote('#__js_job_cities') . " AS city ON city.code=job.city  
		WHERE  job.status = 1 AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate) . "
		ORDER BY created DESC ";

        //echo $limitstart;
        //echo '<br>'.$limit;
        //echo $query;
        $db->setQuery($query, $limitstart, $limit);
        $latestjobs = $db->loadObjectList();

        $location = '';
        $darray = Array();
        foreach ($latestjobs AS $job) {
            $location = '';
            $salaryrange = '';
            $educationtitle = '';
            $experiencetitle = '';
            if (($job->cityname))
                $location = $job->cityname; elseif (($job->city))
                $location = $job->city;
            elseif (($job->countyname))
                $location = $job->countyname; elseif (($job->county))
                $location = $job->county;
            elseif (($job->statename))
                $location = $job->statename; elseif (($job->state))
                $location = $job->state;
            elseif (($job->countryname))
                $location = $job->countryname;

            if ($job->rangestart)
                $salaryrange = $job->currencysymbol . $job->rangestart . ' - ' . $job->currencysymbol . $job->rangeend . ' ' . $job->salaryrangetype;

            if ($job->iseducationminimax == 1) {
                if ($job->educationminimax == 1)
                    $title = JText::_('JS_MIN');
                else
                    $title = JText::_('JS_MAX');
                $educationtitle = $title . ' ' . $job->educationtitle;
            }
            else
                $educationtitle = $job->mineducationtitle . ' - ' . $job->maxeducationtitle;

            if ($job->isexperienceminimax == 1) {
                if ($job->experienceminimax == 1)
                    $title = JText::_('JS_MIN');
                else
                    $title = JText::_('JS_MAX');
                $experiencetitle = $title . ' ' . $job->experiencetitle;
            }
            else
                $experiencetitle = $job->minexperiencetitle . ' - ' . $job->maxexperiencetitle;

            $darray[] = array('id' => $job->id, 'jobtitle' => $job->title, 'company' => $job->companyname, 'category' => $job->cat_title
                , 'salaryrange' => $salaryrange, 'education' => $educationtitle, 'shift' => $job->shifttitle, 'experience' => $experiencetitle, 'location' => $location);
        }
        $result[0] = $darray;
        //print_r($darray);
        $result['totalrecords'] = $total;
        return $result;
    }

    function & getTopJobs($limitstart, $limit) {
        $db = & JFactory :: getDBO();
        $result = array();
        $curdate = date('Y-m-d H:i:s');

        $query = "SELECT COUNT(job.id)
		FROM  " . $db->nameQuote('#__js_job_jobs') . " AS job 
		WHERE job.status = 1 AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate);

        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total <= $limitstart)
            $limitstart = 0;

        $query = "SELECT job.id, job.title, job.created, job.country, job.state, job.county, job.city, cat.cat_title
		, company.id AS companyid, company.name AS companyname, company.logofilename
		, country.name AS countryname, state.name AS statename, county.name AS countyname, city.name AS cityname
		, job.state AS state,job.county AS county,job.city AS city		
		, jobtype.title AS jobtypetitle ,category.cat_title 
		, job.iseducationminimax, job.educationminimax, education.title AS educationtitle ,mineducation.title AS mineducationtitle, maxeducation.title AS maxeducationtitle
		, shift.title as shifttitle , job.isexperienceminimax, job.experienceminimax, experience.title AS experiencetitle ,minexperience.title AS minexperiencetitle, maxexperience.title AS maxexperiencetitle
		, salaryrangefrom.rangestart,salaryrangeto.rangeend,salaryrangetype.title AS salaryrangetype, currency.symbol as currencysymbol

		FROM " . $db->nameQuote('#__js_job_jobs') . " AS job 
		JOIN " . $db->nameQuote('#__js_job_jobtypes') . " AS jobtype ON job.jobtype=jobtype.id
		JOIN " . $db->nameQuote('#__js_job_categories') . " AS cat ON job.jobcategory = cat.id 
		LEFT JOIN " . $db->nameQuote('#__js_job_companies') . " AS company ON company.id=job.companyid 
		LEFT JOIN " . $db->nameQuote('#__js_job_categories') . " AS category ON category.id=job.jobcategory  
		LEFT JOIN " . $db->nameQuote('#__js_job_heighesteducation') . " AS education ON job.educationid = education.id
		LEFT JOIN " . $db->nameQuote('#__js_job_heighesteducation') . " AS mineducation ON job.mineducationrange = mineducation.id
		LEFT JOIN " . $db->nameQuote('#__js_job_heighesteducation') . " AS maxeducation ON job.maxeducationrange = maxeducation.id
		LEFT JOIN " . $db->nameQuote('#__js_job_shifts') . " AS shift ON shift.id=job.shift  
		LEFT JOIN " . $db->nameQuote('#__js_job_experiences') . " AS experience ON job.experienceid = experience.id
		LEFT JOIN " . $db->nameQuote('#__js_job_experiences') . " AS minexperience ON job.minexperiencerange = minexperience.id
		LEFT JOIN " . $db->nameQuote('#__js_job_experiences') . " AS maxexperience ON job.maxexperiencerange = maxexperience.id
		LEFT JOIN " . $db->nameQuote('#__js_job_salaryrange') . " AS salaryrangefrom ON salaryrangefrom.id=job.salaryrangefrom  
		LEFT JOIN " . $db->nameQuote('#__js_job_salaryrange') . " AS salaryrangeto ON salaryrangeto.id=job.salaryrangeto  
		LEFT JOIN " . $db->nameQuote('#__js_job_salaryrangetypes') . " AS salaryrangetype ON salaryrangetype.id=job.salaryrangetype  
		LEFT JOIN " . $db->nameQuote('#__js_job_currencies') . " AS currency ON currency.id=job.currencyid  
		LEFT JOIN " . $db->nameQuote('#__js_job_countries') . " AS country ON country.code=job.country 
		LEFT JOIN " . $db->nameQuote('#__js_job_states') . " AS state ON state.code=job.state  
		LEFT JOIN " . $db->nameQuote('#__js_job_counties') . " AS county ON county.code=job.county 
		LEFT JOIN " . $db->nameQuote('#__js_job_cities') . " AS city ON city.code=job.city  
		WHERE  job.status = 1 AND job.startpublishing <= " . $db->Quote($curdate) . " AND job.stoppublishing >= " . $db->Quote($curdate) . "
		ORDER BY job.hits DESC  ";

        //echo $limitstart;
        //echo '<br>'.$limit;
        //echo $query;
        $db->setQuery($query, $limitstart, $limit);
        $topjobs = $db->loadObjectList();

        $location = '';
        $darray = Array();
        foreach ($topjobs AS $job) {
            $location = '';
            $salaryrange = '';
            $educationtitle = '';
            $experiencetitle = '';
            if (($job->cityname))
                $location = $job->cityname; elseif (($job->city))
                $location = $job->city;
            elseif (($job->countyname))
                $location = $job->countyname; elseif (($job->county))
                $location = $job->county;
            elseif (($job->statename))
                $location = $job->statename; elseif (($job->state))
                $location = $job->state;
            elseif (($job->countryname))
                $location = $job->countryname;

            if ($job->rangestart)
                $salaryrange = $job->currencysymbol . $job->rangestart . ' - ' . $job->currencysymbol . $job->rangeend . ' ' . $job->salaryrangetype;

            if ($job->iseducationminimax == 1) {
                if ($job->educationminimax == 1)
                    $title = JText::_('JS_MIN');
                else
                    $title = JText::_('JS_MAX');
                $educationtitle = $title . ' ' . $job->educationtitle;
            }
            else
                $educationtitle = $job->mineducationtitle . ' - ' . $job->maxeducationtitle;

            if ($job->isexperienceminimax == 1) {
                if ($job->experienceminimax == 1)
                    $title = JText::_('JS_MIN');
                else
                    $title = JText::_('JS_MAX');
                $experiencetitle = $title . ' ' . $job->experiencetitle;
            }
            else
                $experiencetitle = $job->minexperiencetitle . ' - ' . $job->maxexperiencetitle;

            $darray[] = array('id' => $job->id, 'jobtitle' => $job->title, 'company' => $job->companyname, 'category' => $job->cat_title
                , 'salaryrange' => $salaryrange, 'education' => $educationtitle, 'shift' => $job->shifttitle, 'experience' => $experiencetitle, 'location' => $location);
        }
        $result[0] = $darray;
        //print_r($darray);
        $result['totalrecords'] = $total;
        return $result;
    }

    function &getJobbyId($job_id) {
        $db = &$this->getDBO();
        if (is_numeric($job_id) == false)
            return false;

        $query = "SELECT job.*, cat.cat_title, subcat.title as subcategory, company.name as companyname, jobtype.title AS jobtypetitle
				, jobstatus.title AS jobstatustitle, shift.title as shifttitle
				, department.name AS departmentname ,salaryfrom.rangestart ,  salaryfrom.rangeend , salarytype.title as salaryrangetype
				, salaryfrom.rangestart AS salaryfrom, salaryto.rangestart AS salaryto, salarytype.title AS salarytype
				, education.title AS educationtitle ,mineducation.title AS mineducationtitle, maxeducation.title AS maxeducationtitle
				, experience.title AS experiencetitle ,minexperience.title AS minexperiencetitle, maxexperience.title AS maxexperiencetitle
				, country.name AS countryname, state.name AS statename, county.name AS countyname, city.name AS cityname
				, currency.symbol AS currencysymbol , careerlevel.title as careerlevel
				, country.name AS workpermit , ageto.title AS ageto , agefrom.title AS agefrom
		FROM " . $db->nameQuote('#__js_job_jobs') . " AS job
		JOIN " . $db->nameQuote('#__js_job_categories') . " AS cat ON job.jobcategory = cat.id
		LEFT JOIN " . $db->nameQuote('#__js_job_subcategories') . " AS subcat ON job.subcategoryid = subcat.id
		JOIN " . $db->nameQuote('#__js_job_companies') . " AS company ON job.companyid = company.id
		JOIN " . $db->nameQuote('#__js_job_jobtypes') . " AS jobtype ON job.jobtype = jobtype.id
		JOIN " . $db->nameQuote('#__js_job_jobstatus') . " AS jobstatus ON job.jobstatus = jobstatus.id
		LEFT JOIN " . $db->nameQuote('#__js_job_departments') . " AS department ON job.departmentid = department.id
		LEFT JOIN " . $db->nameQuote('#__js_job_salaryrange') . " AS salaryfrom ON job.salaryrangefrom = salaryfrom.id
		LEFT JOIN " . $db->nameQuote('#__js_job_salaryrange') . " AS salaryto ON job.salaryrangeto = salaryto.id
		LEFT JOIN " . $db->nameQuote('#__js_job_salaryrangetypes') . " AS salarytype ON job.salaryrangetype = salarytype.id
		LEFT JOIN " . $db->nameQuote('#__js_job_heighesteducation') . " AS education ON job.educationid = education.id
		LEFT JOIN " . $db->nameQuote('#__js_job_heighesteducation') . " AS mineducation ON job.mineducationrange = mineducation.id
		LEFT JOIN " . $db->nameQuote('#__js_job_heighesteducation') . " AS maxeducation ON job.maxeducationrange = maxeducation.id
		LEFT JOIN " . $db->nameQuote('#__js_job_experiences') . " AS experience ON job.experienceid = experience.id
		LEFT JOIN " . $db->nameQuote('#__js_job_experiences') . " AS minexperience ON job.minexperiencerange = minexperience.id
		LEFT JOIN " . $db->nameQuote('#__js_job_experiences') . " AS maxexperience ON job.maxexperiencerange = maxexperience.id
		LEFT JOIN " . $db->nameQuote('#__js_job_shifts') . " AS shift ON job.shift = shift.id
		LEFT JOIN " . $db->nameQuote('#__js_job_countries') . " AS country ON job.country = country.code
		LEFT JOIN " . $db->nameQuote('#__js_job_states') . " AS state ON job.state = state.code
		LEFT JOIN " . $db->nameQuote('#__js_job_counties') . " AS county ON job.county = county.code
		LEFT JOIN " . $db->nameQuote('#__js_job_cities') . " AS city ON job.city = city.code
		LEFT JOIN " . $db->nameQuote('#__js_job_currencies') . "AS currency ON currency.id = job.currencyid

		LEFT JOIN " . $db->nameQuote('#__js_job_careerlevels') . "AS careerlevel ON careerlevel.id = job.careerlevel
		LEFT JOIN " . $db->nameQuote('#__js_job_ages') . "AS ageto ON ageto.id = job.ageto
		LEFT JOIN " . $db->nameQuote('#__js_job_ages') . "AS agefrom ON agefrom.id = job.agefrom

		WHERE  job.id = " . $job_id;
        //echo '<br> SQL '.$query;
        $db->setQuery($query);
        $job = $db->loadObject();

        $query = "UPDATE " . $db->nameQuote('#__js_job_jobs') . " SET hits = hits + 1 WHERE id = " . $job_id;
        //echo '<br>sql '.$query;
        $db->setQuery($query);
        if (!$db->query()) {
            //return false;
        }
        /*
          $model = $this->getJsJobsModel();
          $result=$model->getJobbyId($job_id);
          $job = $result[0];
         */
        $location = '';
        $salaryrange = '';
        $educationtitle = '';
        $experiencetitle = '';
        $requiredtravel = '';
        $age = '';
        $gender = '';

        if (($job->cityname))
            $location = $job->cityname; elseif (($job->city))
            $location = $job->city;
        elseif (($job->countyname))
            $location = $job->countyname; elseif (($job->county))
            $location = $job->county;
        elseif (($job->statename))
            $location = $job->statename; elseif (($job->state))
            $location = $job->state;
        elseif (($job->countryname))
            $location = $job->countryname;

        if ($job->rangestart)
            $salaryrange = $job->currencysymbol . $job->rangestart . ' - ' . $job->currencysymbol . $job->rangeend . ' ' . $job->salaryrangetype;

        if ($job->iseducationminimax == 1) {
            if ($job->educationminimax == 1)
                $title = JText::_('JS_MIN');
            else
                $title = JText::_('JS_MAX');
            $educationtitle = $title . ' ' . $job->educationtitle;
        }
        else
            $educationtitle = $job->mineducationtitle . ' - ' . $job->maxeducationtitle;

        if ($job->isexperienceminimax == 1) {
            if ($job->experienceminimax == 1)
                $title = JText::_('JS_MIN');
            else
                $title = JText::_('JS_MAX');
            $experiencetitle = $title . ' ' . $job->experiencetitle;
        }
        else
            $experiencetitle = $job->minexperiencetitle . ' - ' . $job->maxexperiencetitle;

        if ($job->requiredtravel == 1)
            $requiredtravel = JText::_('JS_NOT_REQUIRED');
        elseif ($job->requiredtravel == 2)
            $requiredtravel = JText::_('JS_25_PER');
        elseif ($job->requiredtravel == 3)
            $requiredtravel = JText::_('JS_50_PER');
        elseif ($job->requiredtravel == 4)
            $requiredtravel = JText::_('JS_75_PER');
        elseif ($job->requiredtravel == 5)
            $requiredtravel = JText::_('JS_100_PER');

        $age = $job->agefrom . '-' . $job->ageto;

        if ($job->gender == 1)
            $gender = JText::_('JS_MALE');
        elseif ($job->gender == 2)
            $gender = JText::_('JS_FEMALE');
        elseif ($job->gender == 0)
            $gender = JText::_('JS_DOES_NOT_MATTER');


        $darray[] = array('id' => $job->id, 'jobtitle' => $job->title, 'company' => $job->companyname, 'category' => $job->cat_title
            , 'salaryrange' => $salaryrange, 'education' => $educationtitle, 'shift' => $job->shifttitle, 'experience' => $experiencetitle, 'location' => $location
            , 'address1' => $job->address1, 'department' => $job->departmentname, 'noofjobs' => $job->noofjobs, 'duration' => $job->duration, 'created' => $job->created
            , 'careerlevel' => $job->careerlevel, 'workpermit' => $job->workpermit, 'requiredtravel' => $requiredtravel, 'age' => $age, 'gender' => $gender);
        return $darray;
    }

    function &getMyResumes($u_id, $jobid) {

        $db = &$this->getDBO();
        if (is_numeric($u_id) == false)
            return false;
        $query = "SELECT * FROM " . $db->nameQuote('#__js_job_resume') . " WHERE uid  = " . $u_id . " AND status = 1 ";
        //echo '<br> sql '.$query;
        $db->setQuery($query);
        $resumeresult = $db->loadObjectList();
        foreach ($resumeresult AS $resume) {
            $result[] = array($resume->id, $resume->application_title);
        }
        return $result;
    }

    /* 	function &getJobDetailById($jobid){

      $db = &$this->getDBO();
      if (is_numeric($jobid) == false) return false;
      $query = " SELECT job.*,company.name AS companyname ,category.cat_title as categorytitle

      , salaryfrom.rangestart , salaryfrom.rangeend , salarytype.title as salaryrangetype
      , salaryfrom.rangestart AS salaryfrom, salaryto.rangestart AS salaryto, salarytype.title AS salarytype
      , salarytype.title AS salarytype ,currency.symbol AS currencysymbol
      , country.name AS countryname, state.name AS statename, county.name AS countyname, city.name AS cityname
      , job.state AS state,job.county AS county,job.city AS city

      FROM ".$db->nameQuote('#__js_job_jobs')."   AS job

      LEFT JOIN ".$db->nameQuote('#__js_job_companies')." AS company ON job.companyid = company.id
      LEFT JOIN ".$db->nameQuote('#__js_job_categories')." AS category ON job.jobcategory = category.id
      LEFT JOIN ".$db->nameQuote('#__js_job_salaryrange')." AS salaryfrom ON job.salaryrangefrom = salaryfrom.id
      LEFT JOIN ".$db->nameQuote('#__js_job_salaryrange')." AS salaryto ON job.salaryrangeto = salaryto.id
      LEFT JOIN ".$db->nameQuote('#__js_job_salaryrangetypes')." AS salarytype ON job.salaryrangetype = salarytype.id
      LEFT JOIN ".$db->nameQuote('#__js_job_countries')." AS country ON job.country = country.code
      LEFT JOIN ".$db->nameQuote('#__js_job_states')." AS state ON job.state = state.code
      LEFT JOIN ".$db->nameQuote('#__js_job_counties')." AS county ON job.county = county.code
      LEFT JOIN ".$db->nameQuote('#__js_job_cities')." AS city ON job.city = city.code
      LEFT JOIN ".$db->nameQuote('#__js_job_currencies')."AS currency ON currency.id = job.currencyid
      WHERE job.id  = ".$jobid." AND job.status = 1  ";

      //echo '<br> sql '.$query;
      $db->setQuery($query);
      $job = $db->loadObject();


      $salaryrange = '';
      $location = '';


      if(($job->cityname)) $location = $job->cityname; elseif(($job->city)) $location = $job->city;
      elseif(($job->countyname)) $location = $job->countyname; elseif(($job->county)) $location = $job->county;
      elseif(($job->statename)) $location = $job->statename; elseif(($job->state)) $location = $job->state;
      elseif(($job->countryname)) $location = $job->countryname;


      if($job->rangestart) $salaryrange = $job->currencysymbol. $job->rangestart.' - '.$job->currencysymbol.$job->rangeend.' '.$job->salaryrangetype;
      $darray[] = array('jobid' => $job->id,'jobtitle' => $job->title,'company'=>$job->companyname,'category'=>$job->categorytitle ,'salaryrange'=>$salaryrange,'location'=>$location);
      return $darray;
      }
     */

    function jobApply($userid, $jobid, $resumeid) {
        $row = &$this->getTable('jobapply');
        $model = $this->getJsJobsModel();

        $data['uid'] = $userid;
        $data['jobid'] = $jobid;
        $data['cvid'] = $resumeid;
        $data['apply_date'] = date('Y-m-d H:i:s');
        ;

        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        if (!$row->check()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        if ($data['uid'] != '') { // only for new
            $result = $model->jobApplyValidation($data['uid'], $data['jobid']);
            if ($result == true) {
                return 3;
                break;
            }
        }
        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        //$model = $this->getModel('jsjobs', 'JSJobsModel');
        //$emailrerurn = $model->sendMail($data['jobid'], $data['uid'],$data['cvid']);

        $emailrerurn = $model->sendMail($data['jobid'], $data['uid'], $data['cvid']);
        return true;
    }

    function getJobforForm($uid, $job_id) {
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        $db = & JFactory::getDBO();

        if (($job_id != '') && ($job_id != 0)) {

            if (is_numeric($job_id) == false)
                return false;
            $query = "SELECT job.*, cat.cat_title, salary.rangestart, salary.rangeend
			FROM " . $db->nameQuote('#__js_job_jobs') . " AS job 
			JOIN " . $db->nameQuote('#__js_job_categories') . " AS cat ON job.jobcategory = cat.id 
			LEFT JOIN " . $db->nameQuote('#__js_job_salaryrange') . " AS salary ON job.jobsalaryrange = salary.id 
			LEFT JOIN " . $db->nameQuote('#__js_job_currencies') . " AS currency On currency.id = job.currencyid
			WHERE job.id = " . $job_id . " AND job.uid = " . $uid;
            //echo $query; 
            $db->setQuery($query);
            $editjobdata = $db->loadObject();
        }
        $query = "SELECT id, name FROM " . $db->nameQuote('#__js_job_companies') . " WHERE uid = " . $uid . " AND status = 1 ORDER BY name ASC ";
        $db->setQuery($query);
        $companies = $db->loadObjectList();
        foreach ($companies as $company) {
            $companydata[] = array($company->id, $company->name);
        }

        if (isset($job_id)) {
            if (isset($companies)) {

                $query = "SELECT id, name FROM " . $db->nameQuote('#__js_job_departments') . " WHERE status = 1 AND companyid = " . $editjobdata->companyid . " AND uid = " . $uid;
                //echo $query; die();
                $db->setQuery($query);
                $departments = $db->loadObjectList();
                foreach ($departments as $department) {
                    $departmentdata[] = array($department->id, $department->name);
                }
            }
        }
        $query = "SELECT id,cat_title FROM " . $db->nameQuote('#__js_job_categories') . " WHERE isactive = 1 ORDER BY cat_title ";
        $db->setQuery($query);
        $categories = $db->loadObjectList();
        foreach ($categories as $cat) {
            $jobcategoriesdata[] = array($cat->id, $cat->cat_title);
        }

        if (isset($job_id)) {
            if (isset($categories[0])) {

                $query = "SELECT id, title FROM " . $db->nameQuote('#__js_job_subcategories') . " WHERE status = 1 AND categoryid = " . $editjobdata->jobcategory;
                $db->setQuery($query);
                $subcategories = $db->loadObjectlist();
                foreach ($subcategories as $subcat) {
                    $jobsubcategoriesdata[] = array($subcat->id, $subcat->title);
                }
            }
        } else {

            $query = "SELECT id, title FROM " . $db->nameQuote('#__js_job_subcategories') . " WHERE status = 1 AND categoryid = " . $categories[0]->id;
            $db->setQuery($query);
            $subcategories = $db->loadObjectlist();
            foreach ($subcategories as $subcat) {
                $jobsubcategoriesdata[] = array($subcat->id, $subcat->title);
            }
        }
        $query = "SELECT id, title FROM " . $db->nameQuote('#__js_job_jobtypes') . " WHERE isactive = 1 ORDER BY id ASC ";
        $db->setQuery($query);
        $jobtypes = $db->loadObjectList();
        foreach ($jobtypes as $jobtype) {
            $jobtypedata[] = array($jobtype->id, $jobtype->title);
        }

        $query = "SELECT id, title FROM " . $db->nameQuote('#__js_job_jobstatus') . " WHERE isactive = 1 ORDER BY id ASC ";
        $db->setQuery($query);
        $jobstatus = $db->loadObjectList();
        foreach ($jobstatus as $jobstat) {
            $jobtypestatusdata[] = array($jobstat->id, $jobstat->title);
        }

        $gender[] = array(0, JText::_('JS_DOES_NOT_MATTER'));
        $gender[] = array(1, JText::_('JS_MALE'));
        $gender[] = array(2, JText::_('JS_FEMALE'));

        $query = "SELECT id, title FROM " . $db->nameQuote('#__js_job_ages') . " WHERE status = 1 ORDER BY id ASC ";
        $db->setQuery($query);
        $jobage = $db->loadObjectList();
        foreach ($jobage as $age) {
            $agedata[] = array($age->id, $age->title);
        }

        $q = "SELECT * FROM " . $db->nameQuote('#__js_job_currencies') . " WHERE status = 1  ";
        $db->setQuery($q);
        $currencies = $db->loadObjectList();
        foreach ($currencies as $currency) {
            $currenciesdata[] = array($currency->id, $currency->symbol, $currency->title);
        }

        $query = "SELECT * FROM " . $db->nameQuote('#__js_job_salaryrange') . " ORDER BY 'id' ";
        $db->setQuery($query);
        $salaryrange = $db->loadObjectList();
        foreach ($salaryrange as $salary) {
            $salarydata[] = array($salary->id, $salary->rangestart/* ,$salary->rangeend */);
        }

        $query = "SELECT id, title FROM " . $db->nameQuote('#__js_job_salaryrangetypes') . " WHERE status = 1 ORDER BY id ASC ";
        $db->setQuery($query);
        $salarytype = $db->loadObjectList();
        foreach ($salarytype as $type) {
            $salarytypedata[] = array($type->id, $type->title);
        }

        $query = "SELECT id, title FROM " . $db->nameQuote('#__js_job_shifts') . " WHERE isactive = 1 ORDER BY id ASC ";
        $db->setQuery($query);
        $jobshift = $db->loadObjectList();
        foreach ($jobshift as $shift) {
            $shiftdata[] = array($shift->id, $shift->title);
        }

        $educationminimax[] = array(JText::_('JS_MINIMUM'));
        $educationminimax[] = array(JText::_('JS_MAXIMUM'));

        $query = "SELECT id, title FROM " . $db->nameQuote('#__js_job_heighesteducation') . " WHERE isactive = 1 ORDER BY id ASC ";
        $db->setQuery($query);
        $highesteducation = $db->loadObjectList();
        foreach ($highesteducation as $education) {
            $educationdata[] = array($education->id, $education->title);
        }

        $query = "SELECT id, title FROM " . $db->nameQuote('#__js_job_experiences') . " WHERE status = 1 ORDER BY id ASC ";
        $db->setQuery($query);
        $jobexperience = $db->loadObjectList();
        foreach ($jobexperience as $experience) {
            $experiencedata[] = array($experience->id, $experience->title);
        }

        $query = "SELECT id, title FROM " . $db->nameQuote('#__js_job_careerlevels') . " WHERE status = 1 ORDER BY id ASC ";
        $db->setQuery($query);
        $jobcareerlevel = $db->loadObjectList();
        foreach ($jobcareerlevel as $career) {
            $careerleveldata[] = array($career->id, $career->title);
        }

        $query = "SELECT * FROM " . $db->nameQuote('#__js_job_countries') . " WHERE enabled = 'Y' ORDER BY name ASC ";
        $db->setQuery($query);
        $jobworkpermit = $db->loadObjectList();
        foreach ($jobworkpermit as $workpermit) {
            $workpermitdata[] = array($workpermit->id, $workpermit->code, $workpermit->name);
        }

        $requiredtravel[] = array(1, JText::_('JS_NOT_REQUIRED'));
        $requiredtravel[] = array(2, JText::_('JS_25_PER'));
        $requiredtravel[] = array(3, JText::_('JS_50_PER'));
        $requiredtravel[] = array(4, JText::_('JS_75_PER'));
        $requiredtravel[] = array(5, JText::_('JS_100_PER'));

        $result[0] = $companydata;
        if (isset($departmentdata))
            $result[1] = $departmentdata;
        $result[2] = $jobcategoriesdata;
        if (isset($jobsubcategoriesdata))
            $result[3] = $jobsubcategoriesdata;
        $result[4] = $jobtypedata;
        $result[5] = $jobtypestatusdata;
        $result[6] = $gender;
        $result[7] = $agedata;
        $result[8] = $currenciesdata;
        $result[9] = $salarydata;
        $result[10] = $salarytypedata;
        $result[11] = $shiftdata;
        $result[12] = $educationminimax;
        $result[13] = $educationdata;
        $result[14] = $educationminimax;
        $result[15] = $experiencedata;
        $result[16] = $careerleveldata;
        $result[17] = $workpermitdata;
        $result[18] = $requiredtravel;
        $result[19] = $workpermitdata;
        if (isset($editjobdata))
            $result[20] = $editjobdata;
        else
            $result[20] = '';
        return $result;
    }

    function getDeptByCompanyId($uid, $companyid) {
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if (is_numeric($companyid) == false)
            return false;
        $db = &$this->getDBO();
        $query = "SELECT id, name FROM " . $db->nameQuote('#__js_job_departments') . " WHERE status = 1 AND companyid = " . $companyid . " AND uid = " . $uid . " ORDER BY name ASC ";
        $db->setQuery($query);
        $departments = $db->loadObjectList();
        foreach ($departments as $department) {
            $departmentdata[] = array($department->id, $department->name);
        }
        return $departmentdata;
    }

    function getSubcategoriesByCategoryId($id) {
        if (is_numeric($id) == false)
            return false;
        $db = &$this->getDBO();
        $query = "SELECT id,title FROM " . $db->nameQuote('#__js_job_subcategories') . " WHERE status = 1 AND categoryid = " . $id . " ORDER BY title ";
        $db->setQuery($query);
        $subcategories = $db->loadObjectList();
        $subcategoriesdata = array();
        foreach ($subcategories as $subcat) {
            $subcategoriesdata[] = array($subcat->id, $subcat->title);
        }
        return $subcategoriesdata;
    }

    function storeJob($data) {
        $row = &$this->getTable('job');
        $model = $this->getJsJobsModel();

        if (isset($this_config) == false)
            $this->_config = $model->getConfig('');
        foreach ($this->_config as $conf) {
            if ($conf->configname == 'jobautoapprove')
                $configvalue = $conf->configvalue;
            if ($conf->configname == 'date_format')
                $dateformat = $conf->configvalue;
        }

        if ($data['id'] == '') { // only for new job
            $data['status'] = $configvalue;
        }

        if ($dateformat == 'm-d-Y') {
            $arr = explode('-', $data['startpublishing']);
            $data['startpublishing'] = $arr[1] . '/' . $arr[2] . '/' . $arr[0];
            $arr = explode('-', $data['stoppublishing']);
            $data['stoppublishing'] = $arr[1] . '/' . $arr[2] . '/' . $arr[0];
        } elseif ($dateformat == 'd-m-Y') {
            $arr = explode('-', $data['startpublishing']);
            $data['startpublishing'] = $arr[2] . '-' . $arr[1] . '-' . $arr[0];
            $arr = explode('-', $data['stoppublishing']);
            $data['stoppublishing'] = $arr[2] . '-' . $arr[1] . '-' . $arr[0];
        }
        if (!isset($data['id'])) {
            if (($data['enforcestoppublishjob'] == 1) && ($data['id'] == '')) {
                if ($data['enforcestoppublishjobtype'] == 1) {
                    $data['stoppublishing'] = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($data['startpublishing'])) . " +" . $data['enforcestoppublishjobvalue'] . " day"));
                } elseif ($data['enforcestoppublishjobtype'] == 2) {
                    $data['stoppublishing'] = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($data['startpublishing'])) . " +" . $data['enforcestoppublishjobvalue'] . " week"));
                } elseif ($data['enforcestoppublishjobtype'] == 3) {
                    $data['stoppublishing'] = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime($data['startpublishing'])) . " +" . $data['enforcestoppublishjobvalue'] . " month"));
                }
            }
        }
        $data['startpublishing'] = date('Y-m-d H:i:s', strtotime($data['startpublishing']));
        $data['stoppublishing'] = date('Y-m-d H:i:s', strtotime($data['stoppublishing']));
        /*
          if ($this->_job_editor == 1){
          $data['description'] = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);
          $data['qualifications'] = JRequest::getVar('qualifications', '', 'post', 'string', JREQUEST_ALLOWRAW);
          $data['prefferdskills'] = JRequest::getVar('prefferdskills', '', 'post', 'string', JREQUEST_ALLOWRAW);
          //$data['agreement'] = JRequest::getVar('agreement', '', 'post', 'string', JREQUEST_ALLOWRAW);
          }
         */
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
        }/*
          if(!isset($data['id'])){
          $this->storeUserFieldData($data, $row->id);
          } */
        if ($data['id'] == '') { // only for new job
            $model->sendMailtoAdmin($row->id, $data['uid'], 2);
            /* 	if ($data['status'] == 1) { // if job approved
              //	$this->sendJobAlertJobseeker($row->id);
              //	register_shutdown_function(array($this,'sendJobAlertJobseeker'),$row->id);

              } */
        }

        return true;
    }

    function getSecondaryKey($primarykey) {
        $model = $this->getJsJobsModel();
        $result = $model->getConfigByFor('api');
        $db = &$this->getDBO();
        if (strcmp($primarykey, $result['api_primary']) == 0) {
            if ($result['api_secondary'])
                return $result['api_secondary'];
            else {
                $secondarykey = substr(md5(md5(date('Y-m-d H:i:s'))), 0, 25);
                $query = "UPDATE " . $db->nameQuote('#__js_job_config') . " SET configvalue = '" . $secondarykey . "' WHERE configname = 'api_secondary'";
                //echo '<br>sql '.$query;
                $db->setQuery($query);
                if ($db->query()) {
                    return $secondarykey;
                }
            }
        }
    }

    function checkSecondaryKey($secondarykey) {
        $model = $this->getJsJobsModel();
        $result = $model->getConfigByFor('api');
        if (strcmp($secondarykey, $result['api_secondary']) == 0)
            return true;
        else
            return false;
    }

    function getMyJobs($uid, $limitstart, $limit) {
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        $model = $this->getJsJobsModel();
        $result = $model->getMyJobs($uid, "job.id DESC", $limit, $limitstart);
        foreach ($result[0] as $job) {
            $darray[] = array('id' => $job->id, 'jobtitle' => $job->title, 'description' => $job->description, 'category' => $job->cat_title, 'qualifications' => $job->qualifications, 'prefferdskills' => $job->prefferdskills, 'noofjobs' => $job->noofjobs, 'degreetitle' => $job->degreetitle, 'countryname' => $job->countryname, 'jobstatustitle' => $job->jobstatustitle, 'companyname' => $job->companyname, 'url' => $job->url);
        }
        $record['0'] = $darray;
        $record['totalrecords'] = $result[1];
        return $record;
    }

    function getAllAppliedResumeByUid($uid, $limit, $limitstart) {
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        $model = $this->getJsJobsModel();
        $jobdata = $model->getJobsAppliedResume($uid, "job.id DESC", $limit, $limitstart);

        foreach ($jobdata[0] as $job) {
            $result[] = array('id' => $job->id, 'companyname' => $job->name, 'jobtitle' => $job->title, 'category' => $job->cat_title, 'jobtype' => $job->jobtypetitle, 'jobstatus' => $job->jobstatustitle, 'dateposted' => $job->created, 'appinjob' => $job->appinjob);
        }
        $result1 = array('0' => $result, 'totalrecords' => $jobdata[1]);
        return $result1;
    }

    function getAllJobAppliedresume($uid, $jobid, $limit, $limitstart) {
        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if ((is_numeric($jobid) == false) || ($jobid == ''))
            return false;
        $model = $this->getJsJobsModel();
        $appliedresumedata = $model->getJobAppliedResume($uid, $jobid, "job.id DESC", $limit, $limitstart);



        foreach ($appliedresumedata[0] as $resume) {
            $result[] = array('id' => $resume->resumeid, 'name' => $resume->first_name . ' ' . $resume->last_name, 'gender' => $resume->gender, 'salary' => $resume->symbol . $resume->rangestart . ' - ' . $resume->symbol . $resume->rangeend, 'applieddate' => $resume->apply_date, 'experience' => $resume->total_experience, 'resumeview' => $resume->resumeview);
        }




        $result1 = array('0' => $result, 'totalrecords' => $appliedresumedata[1]);
        return $result1;
    }

    function getResumeViewbyId($uid, $jobid, $resumeid, $myresume) {

        if ((is_numeric($uid) == false) || ($uid == 0) || ($uid == ''))
            return false;
        if ((is_numeric($jobid) == false) || ($jobid == ''))
            return false;
        if ((is_numeric($resumeid) == false) || ($resumeid == ''))
            return false;
        if ((is_numeric($myresume) == false) || ($myresume == ''))
            return false;

        $model = $this->getJsJobsModel();
        $result = $model->getResumeViewbyId($uid, $jobid, $resumeid, $myresume);

        $personal = array('applicationtitle' => $result[0]->application_title, 'firstname' => $result[0]->first_name, 'middlename' => $result[0]->middle_name, 'lastname' => $result[0]->last_name, 'emailaddress' => $result[0]->email_address, 'homephone' => $result[0]->home_phone, 'workphone' => $result[0]->work_phone, 'cell' => $result[0]->cell, 'nationality' => $result[0]->nationalitycountry, 'gender' => $result[0]->gender, 'iamavailable' => $result[0]->iamavailable);
        $salary = $result[0]->symbol . $result[0]->rangestart . ' - ' . $result[0]->symbol . $result[0]->rangeend;
        $basicinformation = array('category' => $result[0]->job_category, 'salary' => $salary, 'workpreference' => $result[0]->jobtypetitle, 'highestfinishededucation' => $result[0]->heighesteducationtitle, 'totalexperience' => $result[0]->total_experience, 'dateyoucanstart' => $result[0]->date_start);

        if (isset($result[0]->address_country)) {

            $country = $result[0]->address_country;
            $county = $result[0]->address_county;
            $state = $result[0]->address_state;
            $city = $result[0]->address_city;
            $zipcode = $result[0]->address_zipcode;
            $address = $result[0]->address;
        } elseif (isset($result[0]->address_country)) {

            $country = $result[0]->address1_country;
            $county = $result[0]->address1_county;
            $state = $result[0]->address1_state;
            $city = $result[0]->address1_city;
            $zipcode = $result[0]->address1_zipcode;
            $address = $result[0]->address1_address;
        } elseif (isset($result[0]->address_country)) {

            $country = $result[0]->address2_country;
            $county = $result[0]->address2_county;
            $state = $result[0]->address2_state;
            $city = $result[0]->address2_city;
            $zipcode = $result[0]->address2_zipcode;
            $address = $result[0]->address2_address;
        } else {

            $country = '';
            $county = '';
            $state = '';
            $city = '';
            $zipcode = '';
            $address = '';
        }
        $addresssection = array('country' => $country, 'county' => $county, 'state' => $state, 'city' => $city, 'zipcode' => $zipcode, 'address' => $address);

        if (isset($result[0]->institute1) && $result[0]->institute1 != '') {

            $institutionname = $result[0]->institute1;
            $institutioncountry = $result[0]->institute1_country;
            $institutioncounty = $result[0]->institute1_county;
            $institutionstate = $result[0]->institute1_state;
            $institutioncity = $result[0]->institute1_city;
            $deg = $result[0]->institute1_certificate_name;
            $areaofstudy = $result[0]->institute1_study_area;
        } elseif (isset($result[0]->institute) && $result[0]->institute != '') {

            $institutionname = $result[0]->institute;
            $institutioncountry = $result[0]->institute_country;
            $institutioncounty = $result[0]->institute_county;
            $institutionstate = $result[0]->institute_state;
            $institutioncity = $result[0]->institute_city;
            $deg = $result[0]->institute2_certificate_name;
            $areaofstudy = $result[0]->institute_study_area;
        } elseif (isset($result[0]->institute2) && $result[0]->institute2 != '') {

            $institutionname = $result[0]->institute2;
            $institutioncountry = $result[0]->institute2_country;
            $institutioncounty = $result[0]->institute2_county;
            $institutionstate = $result[0]->institute2_state;
            $institutioncity = $result[0]->institute2_city;
            $deg = $result[0]->institute2_certificate_name;
            $areaofstudy = $result[0]->institute2_study_area;
        } elseif (isset($result[0]->institute3) && $result[0]->institute3 != '') {

            $institutionname = $result[0]->institute3;
            $institutioncountry = $result[0]->institute3_country;
            $institutioncounty = $result[0]->institute3_county;
            $institutionstate = $result[0]->institute3_state;
            $institutioncity = $result[0]->institute3_city;
            $deg = $result[0]->institute3_certificate_name;
            $areaofstudy = $result[0]->institute3_study_area;
        } else {

            $institutionname = '';
            $institutioncountry = '';
            $institutioncounty = '';
            $institutionstate = '';
            $institutioncity = '';
            $deg = '';
            $areaofstudy = '';
        }

        $education = array('institutename' => $institutionname, 'institutioncountry' => $institutioncountry, 'institutioncounty' => $institutioncounty, 'institutionstate' => $institutionstate, 'institutioncity' => $institutioncity, 'deg' => $deg, 'areaofstudy' => $areaofstudy);
        if (isset($result[0]->employer) && $result[0]->employer != '') {

            $employer = $result[0]->employer;
            $employerposition = $result[0]->employer_position;
            $employerresponsibility = $result[0]->employer_resp;
            $employerpayuponleaving = $result[0]->employer_pay_upon_leaving;
            $employersupervisor = $result[0]->employer_supervisor;
            $employerfromdate = $result[0]->employer_from_date;
            $employertodate = $result[0]->employer_to_date;
            $employerreasonforleaving = $result[0]->employer_leave_reason;
            $employercountry = $result[0]->employer_country;
            $employerstate = $result[0]->employer_state;
            $employercounty = $result[0]->employer_county;
            $employercity = $result[0]->employer_city;
            $employerzipcode = $result[0]->employer_zip;
            $employerphone = $result[0]->employer_phone;
            $employeraddress = $result[0]->employer_address;
        } elseif (isset($result[0]->employer1) && $result[0]->employer1 != '') {

            $employer = $result[0]->employer1;
            $employerposition = $result[0]->employer1_position;
            $employerresponsibility = $result[0]->employer1_resp;
            $employerpayuponleaving = $result[0]->employer1_pay_upon_leaving;
            $employersupervisor = $result[0]->employer1_supervisor;
            $employerfromdate = $result[0]->employer1_from_date;
            $employertodate = $result[0]->employer1_to_date;
            $employerreasonforleaving = $result[0]->employer1_leave_reason;
            $employercountry = $result[0]->employer1_country;
            $employerstate = $result[0]->employer1_state;
            $employercounty = $result[0]->employer1_county;
            $employercity = $result[0]->employer1_city;
            $employerzipcode = $result[0]->employer1_zip;
            $employerphone = $result[0]->employer1_phone;
            $employeraddress = $result[0]->employer1_address;
        } elseif (isset($result[0]->employer2) && $result[0]->employer2 != '') {

            $employer = $result[0]->employer2;
            $employerposition = $result[0]->employer2_position;
            $employerresponsibility = $result[0]->employer2_resp;
            $employerpayuponleaving = $result[0]->employer2_pay_upon_leaving;
            $employersupervisor = $result[0]->employer2_supervisor;
            $employerfromdate = $result[0]->employer2_from_date;
            $employertodate = $result[0]->employer2_to_date;
            $employerreasonforleaving = $result[0]->employer2_leave_reason;
            $employercountry = $result[0]->employer2_country;
            $employerstate = $result[0]->employer2_state;
            $employercounty = $result[0]->employer2_county;
            $employercity = $result[0]->employer2_city;
            $employerzipcode = $result[0]->employer2_zip;
            $employerphone = $result[0]->employer2_phone;
            $employeraddress = $result[0]->employer2_address;
        } elseif (isset($result[0]->employer3) && $result[0]->employer3 != '') {

            $employer = $result[0]->employer3;
            $employerposition = $result[0]->employer3_position;
            $employerresponsibility = $result[0]->employer3_resp;
            $employerpayuponleaving = $result[0]->employer3_pay_upon_leaving;
            $employersupervisor = $result[0]->employer3_supervisor;
            $employerfromdate = $result[0]->employer3_from_date;
            $employertodate = $result[0]->employer3_to_date;
            $employerreasonforleaving = $result[0]->employer3_leave_reason;
            $employercountry = $result[0]->employer3_country;
            $employerstate = $result[0]->employer3_state;
            $employercounty = $result[0]->employer3_county;
            $employercity = $result[0]->employer3_city;
            $employerzipcode = $result[0]->employer3_zip;
            $employerphone = $result[0]->employer3_phone;
            $employeraddress = $result[0]->employer3_address;
        } else {

            $employer = '';
            $employerposition = '';
            $employerresponsibility = '';
            $employerpayuponleaving = '';
            $employersupervisor = '';
            $employerfromdate = '';
            $employertodate = '';
            $employerreasonforleaving = '';
            $employercountry = '';
            $employerstate = '';
            $employercounty = '';
            $employercity = '';
            $employerzipcode = '';
            $employerphone = '';
            $employeraddress = '';
        }

        $employer1 = array('employer' => $employer, 'employerposition' => $employerposition, 'employerresponsibility' => $employerresponsibility, 'employerpayuponleaving' => $employerpayuponleaving, 'employersupervisor' => $employersupervisor, 'employerfromdate' => $employerfromdate, 'employertodate' => $employertodate, 'employerreasonforleaving' => $employerreasonforleaving, 'employercountry' => $employercountry, 'employerstate' => $employerstate, 'employercounty' => $employercounty, 'employercity' => $employercity, 'employerzipcode' => $employerzipcode, 'employerphone' => $employerphone, 'employeraddress' => $employeraddress);
        $result1 = array('personal' => $personal, 'basicinformation' => $basicinformation, 'addresssection' => $addresssection, 'education' => $education, 'employer' => $employer1);
        return $result1;
    }

    //********************************************************for smart phone ************************************************	
    /*     * sce */
}

?>
