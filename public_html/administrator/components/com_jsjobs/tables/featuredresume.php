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

class TableFeaturedResume extends JTable
{

/** @var int Primary key */
	var $id=null;
	var $uid=null;
	var $packageid=null;
	var $resumeid=null;
	var $startdate=null;
	var $enddate=null;
	var $status=null;
	var $created=null;

	
	function __construct(&$db)
	{
		parent::__construct( '#__js_job_featuredresumes', 'id' , $db );
	}
	
	
	
	 function check()
	 {
	 	return true;
	 }
	 	 
}

?>
