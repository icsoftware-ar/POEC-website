<?php
/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 + Created by:          Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jan 22, 2011
 ^
 + Project: 		JS Jobs
 */
 
defined('_JEXEC') or die('Restricted access');

// our table class for the application data
class Tableresumerating extends JTable
{
	var $id=null;
	var $uid=null;
	var $jobid=null;
	var $resumeid=null;
	var $rating=null;
	var $votes=null;
	var $meonly=1;
	var $created=null;
	
	function __construct(&$db)
	{
		parent::__construct( '#__js_job_resumerating', 'id' , $db );
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
