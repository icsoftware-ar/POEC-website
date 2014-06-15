<?php
/**
 * @Copyright Copyright (C) 2012 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	April 05, 2012
 ^
 + Project: 		JS Autoz
 ^ 
*/
 
defined('_JEXEC') or die('Restricted access');

// our table class for the application data
class Tablepaymentmethodlinks extends JTable
{
/** @var int Primary key */
  var $id = null;
  var $paymentmethodid = null;
  var $packageid = null;
  var $link = null;
  var $packagefor = null;

	function __construct(&$db)
	{
		parent::__construct( '#__js_job_paymentmethodlinks', 'id' , $db );
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
