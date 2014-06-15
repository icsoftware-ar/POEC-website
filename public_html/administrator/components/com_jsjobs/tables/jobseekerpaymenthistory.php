<?php
/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 + Created by:          Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jun 05, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/tables/jobseekerpaymenthistory.php
 ^ 
 * Description: Table for a job seeker payment history
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');

// our table class for the application data
class TableJobSeekerPaymentHistory extends JTable
{
	var $id=null;
	var $uid=null;
	var $packageid=null;
	var $packagetitle=null;
	var $packageprice=null;
	var $discountamount=null;
	var $paidamount=null;
	var $discountmessage=null;
	var $packagestartdate=null;
	var $packageenddate=null;
	var $resumeallow=null;
	var $coverlettersallow=null;
	var $applyjobs=null;
	var $jobsearch=null;
	var $jobsumesearch=null;
	var $featuredresume=null;
	var $goldresume=null;
	var $video=null;
	var $packageexpireindays=null;
	var $packagedescription=null;
	var $packageshortdetails=null;
	var $status=null;
	var $created=null;
	var $transactionverified=null;
	var $transactionautoverified=null;
	var $verifieddate=null;
	

	function __construct(&$db)
	{
		parent::__construct( '#__js_job_jobseekerpaymenthistory', 'id' , $db );
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
