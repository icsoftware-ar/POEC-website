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
 * File Name:	admin-----/tables/job.php
 ^ 
 * Description: Table for a job 
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');

class TableFeaturedCompany extends JTable
{

/** @var int Primary key */
	var $id=null;
	var $uid=null;
	var $packageid=null;
	var $companyid=null;
	var $startdate=null;
	var $enddate=null;
	var $status=null;
	var $created=null;

	
	function __construct(&$db)
	{
		parent::__construct( '#__js_job_featuredcompanies', 'id' , $db );
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
	
	 function check()
	 {
	 	return true;
	 }
	 	 
}

?>
