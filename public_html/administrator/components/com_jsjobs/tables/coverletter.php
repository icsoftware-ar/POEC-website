<?php
/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 + Created by:          Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	May 30, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/tables/coverletter.php
 ^ 
 * Description: Table for a cover letter
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');

class TableCoverLetter extends JTable
{

/** @var int Primary key */
	var $id=null;
	var $uid=null;
	var $title=null;
	var $alias=null;
	var $description=null;
	var $hits=null;
	var $published=null;
	var $searchable=null;
	var $status=null;
	var $created=null;
	var $packageid=null;
	var $paymenthistoryid=null;

	
	function __construct(&$db)
	{
		parent::__construct( '#__js_job_coverletters', 'id' , $db );
	}
	
	/** 
	 * Validation
	 * 
	 * @return boolean True if buffer is valid
	 * 
	 */
	 function check()
	 {
/*	    if (trim( $this->title) == '') {
	      $this->_error = "Title cannot be empty.";
	      return false;
	    }else if (trim( $this->description ) == '') {
	      $this->_error = "Description cannot be empty.";
	      return false;
	    }
*/
		return true;
	 }
	 	 
}

?>
