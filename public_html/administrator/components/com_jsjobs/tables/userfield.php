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
 * File Name:	admin-----/tables/userfielddata.php
 ^ 
 * Description: Table for a user field data
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');

// our table class for the application data
class TableUserField extends JTable
{
	var $id=null;
	var $name=null;
	var $title=null;
	var $description=null;
	var $type=null;
	var $maxlength=null;
	var $size=null;
	var $required=null;
	var $ordering=null;
	var $cols=null;
	var $rows=null;
	var $value=null;
	var $default=null;
	var $published=null;
	var $fieldfor=null;
	var $readonly=null;
	var $calculated=null;
	var $sys=null;
	var $params=null;
	
	function __construct(&$db)
	{
		parent::__construct( '#__js_job_userfields', 'id' , $db );
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
