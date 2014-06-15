<?php
/**
 * @Copyright Copyright (C) 2009-2011 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , info@burujsolutions.com
 * Created on:	Jan 9, 2011
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/tables/subcategory.php
 ^ 
 * Description: Table for a sub category
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');

// our table class for the application data
class TableSubCategory extends JTable
{
	var $id=null;
	var $categoryid=null;
	var $title=null;
	var $status=0;
	
	function __construct(&$db)
	{
		parent::__construct( '#__js_job_subcategories', 'id' , $db );
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
