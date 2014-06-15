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
 * File Name:	admin-----/tables/city.php
 ^ 
 * Description: Table for a city
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');

// our table class for the application data
class TableFieldsOrdering extends JTable
{
	var $id=null;
	var $field=null;
	var $fieldtitle=null;
	var $ordering=null;
	var $section=null;
	var $fieldfor=null;
	var $published=null;
	var $sys=null;
	var $cannotunpublish=null;
	var $required=null;
	function __construct(&$db)
	{
		parent::__construct( '#__js_job_fieldsordering', 'id' , $db );
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
