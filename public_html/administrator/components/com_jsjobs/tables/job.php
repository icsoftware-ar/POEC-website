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

class TableJob extends JTable
{

/** @var int Primary key */
	var $id=null;
	var $uid=null;
	var $companyid=null;
	var $jobid=null;
	var $title=null;
	var $alias=null;
	var $jobcategory=null;
	var $jobtype=null;
	var $jobstatus=null;
	var $jobsalaryrange=null;
	var $hidesalaryrange=0;
	var $description=null;
	var $qualifications=null;
	var $prefferdskills=null;
	var $applyinfo=null;
	var $company=null;
	var $country=null;
	var $state=null;
	var $county=null;
	var $city=null;
	var $zipcode=null;
	var $address1=null;
	var $address2=null;
	var $companyurl=null;
	var $contactname=null;
	var $contactphone=null;
	var $contactemail=null;
	var $showcontact=null;
	var $noofjobs=null;
	var $reference=null;
	var $duration=null;
	var $heighestfinisheducation=null;
	var $created=null;
	var $created_by=null;
	var $modified=null;
	var $modified_by=null;

	var $hits=null;
	var $experience=null;
	var $startpublishing=null;
	var $stoppublishing=null;
	var $departmentid=null;
	var $shift=null;
	var $sendemail=0;
	var $metadescription=null;
	var $metakeywords=null;

	var $ordering=null;
	var $status=null;

	var $educationminimax=null;
	var $educationid=null;
	var $mineducationrange=null;
	var $maxeducationrange=null;
	var $iseducationminimax=null;
	
	var $degreetitle=null;
	var $careerlevel=null;
	var $experienceminimax=null;
	var $experienceid=null;
	var $minexperiencerange=null;
	var $maxexperiencerange=null;
	var $isexperienceminimax=null;
	var $experiencetext=null;
	
	var $workpermit=null;
	var $requiredtravel=null;
	var $agefrom=null;
	var $ageto=null;

	var $salaryrangefrom=null;
	var $salaryrangeto=null;
	var $salaryrangetype=null;
	var $gender=null;
	
	var $agreement=null;
	var $video=null;
	var $map=null;
	
	var $packageid=null;
	var $paymenthistoryid=null;

	var $subcategoryid=null;
	var $currencyid =null;
	var $raf_gender =0;
	var $raf_degreelevel =0;
	var $raf_experience =0;
	var $raf_age =0;
	var $raf_education =0;
	var $raf_category =0;
	var $raf_subcategory =0;
	var $raf_location =0;
        function __construct(&$db)
	{
		parent::__construct( '#__js_job_jobs', 'id' , $db );
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
	    if (trim( $this->title ) == '') {
	      $this->_error = "Title cannot be empty.";
	      return 2;
	    }else if (trim( $this->description ) == '') {
	      $this->_error = "Description cannot be empty.";
	      return 2;
	    }

		return true;
	 }
	 	 
}

?>
