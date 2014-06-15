<?php
/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 + Created by:          Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	May 30, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/tables/search.php
 ^ 
 * Description: Table for a search
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');

class TableJobSearch extends JTable
{

/** @var int Primary key */
	var $id=null;
	var $uid=null;
	var $searchname=null;
	var $jobtitle=null;	
	var $category=null;
	var $jobtype=null;
	var $jobstatus=null;
	var $heighesteducation=null;
	var $salaryrange=null;
	var $shift=null;	
	var $experience=null;	
	var $durration=null;	
	var $startpublishing=null;	
	var $stoppublishing=null;	
	var $company=null;	
	var $country_istext=null;
	var $country=null;
	var $state_istext=null;
	var $state=null;
	var $county_istext=null;
	var $county=null;
	var $city_istext=null;
	var $city=null;
	var $zipcode_istext=null;
	var $zipcode=null;
	var $created=null;
	var $status=null;

	
	function __construct(&$db)
	{
		parent::__construct( '#__js_job_jobsearches', 'id' , $db );
	}
	
	/** 
	 * Validation
	 * 
	 * @return boolean True if buffer is valid
	 * 
	 */
	 function check()
	 {
		return true;
	 }
	 	 
}

?>
