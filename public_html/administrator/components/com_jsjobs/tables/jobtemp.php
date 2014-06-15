<?php
/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 + Created by:          Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Mar 21, 2013
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/tables/jobtemp.php
 ^ 
 * Description: Table for a jobtemp 
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');

class TableJobTemp extends JTable
{

/** @var int Primary key */
	var $localid=null;
	var $id=null;
	var $title=null;
	var $aliasid=null;
	var $companyaliasid=null;
	var $country=null;
	var $state=null;
	var $city=null;
	var $jobdays=null;
	var $companyid=null;
	var $companyname=null;
	var $jobcategory=null;
	var $cat_title=null;
	var $symbol=null;
	var $salaryfrom=null;
	var $salaryto=null;
	var $salaytype=null;
	var $jobtype=null;
	var $jobstatus=null;
	var $cityname=null;
	var $statename=null;
	var $countryname=null;
	var $noofjobs=null;
	var $created=null;

        function __construct(&$db)
	{
		parent::__construct( '#__js_job_jobs_temp', 'localid' , $db );
	}
	
	/** 
	 * Validation
	 * 
	 * @return boolean True if buffer is valid
	 * 
	 */
	/*
	function bind( $array, $ignore = '' )
	{
		if (key_exists( 'jobcategory', $array ) && is_array( $array['jobcategory'] )) {
			$array['jobcategory'] = implode( ',', $array['jobcategory'] );
		}
		 return parent::bind( $array, $ignore );
	}
	*/
	 	 
}

?>
