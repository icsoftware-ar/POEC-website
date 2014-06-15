<?php
/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 + Created by:          Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jun 14, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/tables/userfieldvalue.php
 ^ 
 * Description: Table for user field value
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');

// our table class for the application data
class TableUserFieldValue extends JTable
{
	var $id=null;
	var $field=null;
	var $fieldtitle=null;
	var $fieldvalue=null;
	var $ordering=null;
	var $sys=null;
	
	function __construct(&$db)
	{
		parent::__construct( '#__js_job_userfieldvalues', 'id' , $db );
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
