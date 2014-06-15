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
 * File Name:	admin-----/tables/role.php
 ^ 
 * Description: Table for a role
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');

// our table class for the application data
class TableRole extends JTable
{
/** @var int Primary key */
	var $id = null;
  	var $title = null;
  	var $rolefor = null;
  	var $companies = null;
  	var $jobs = null;
  	var $resumes = null;
  	var $coverletters = null;
  	var $searchjob = null;
  	var $savesearchjob = null;
  	var $searchresume = null;
  	var $savesearchresume = null;
  	var $published = 0;
	
	function __construct(&$db)
	{
		parent::__construct( '#__js_job_roles', 'id' , $db );
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
