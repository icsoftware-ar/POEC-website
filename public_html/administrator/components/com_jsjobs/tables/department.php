<?php
/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 + Created by:          Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	May 21, 2010
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/tables/department.php
 ^ 
 * Description: Table for a department
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');

// our table class for the application data
class TableDepartment extends JTable
{
/** @var int Primary key */
	var $id=null;
	var $uid=null;
	var $companyid=null;
	var $name=null;
	var $alias=null;
	var $description=null;
	var $status=null;
	var $created=null;
	
	function __construct(&$db)
	{
		parent::__construct( '#__js_job_departments', 'id' , $db );
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
