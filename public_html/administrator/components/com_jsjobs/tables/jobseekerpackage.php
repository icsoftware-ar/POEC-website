<?php
/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 + Created by:          Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jun 05, 2010
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/tables/jobseekerpackage.php
 ^ 
 * Description: Table for a job seeker package
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');

// our table class for the application data
class TableJobSeekerPackage extends JTable
{
	var $id=null;
	var $title=null;
	var $currencyid=null;
	var $price=null;
	//var $discount` INT NULL ,
	var $discount=null;
	var $discounttype=null;
	var $discountmessage=null;
	var $discountstartdate=null;
	var $discountenddate=null;
	var $resumeallow=null;
	var $coverlettersallow=null;
	var $applyjobs=null;
	var $jobsearch=null;
	var $savejobsearch=null;
	var $featuredresume=null;
	var $goldresume=null;
	var $video=null;
	var $packageexpireindays=null;
	var $shortdetails=null;
	var $description=null;
	var $status=null;
	var $created=null;
	var $freaturedresumeexpireindays=null;
	var $goldresumeexpireindays=null;
	var $fastspringlink=null;
	var $otherpaymentlink=null;
	var $jobalertsetting=null;
	
	function __construct(&$db)
	{
		parent::__construct( '#__js_job_jobseekerpackages', 'id' , $db );
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
