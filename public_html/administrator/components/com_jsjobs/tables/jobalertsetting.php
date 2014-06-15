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
 * File Name:	admin-----/tables/company.php
 ^ 
 * Description: Table for a company
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');

class TableJobAlertSetting extends JTable
{

/** @var int Primary key */
	var $id=null;
	var $uid=null; 
	var $categoryid=null;
	var $subcategoryid=null;
	var $contactemail=null;
	var $country=null;
	var $state=null;
	var $county=null;
	var $city=null;
	var $zipcode=null;
	var $sendtime=null;
	var $status=null;
	var $created=null;
	var $keywords=null;
	var $alerttype=null;

	function __construct(&$db)
	{
		parent::__construct( '#__js_job_jobalertsetting', 'id' , $db );
	}
	 	 
}

?>
