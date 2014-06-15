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
 * File Name:	admin-----/tables/userallow.php
 * 
 * Description: Table for user allow
 * 
 * History:		NONE
 * 
 */
 
defined('_JEXEC') or die('Restricted access');

// our table class for the application data
class TableUserAllow extends JTable
{
/** @var int Primary key */
	var $id=null;
/** @var int*/
	var $uid=null;
/** @var int*/
	var $empallow=null;
/** @var int*/
	var $joballow=null;
	
	function __construct(&$db)
	{
		parent::__construct( '#__js_job_userallow', 'id' , $db );
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
