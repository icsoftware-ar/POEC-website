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

jimport('joomla.application.component.model');
jimport('joomla.html.html');

$option = JRequest :: getVar('option', 'com_jsjobs');


class JSJobsModelInstaller extends JModelLegacy
{

    function __construct(){
            parent :: __construct();
    }
    function getConfigByConfigName($configname){
		$db = JFactory::getDBO();
		$query = "SELECT * FROM `#__js_job_config` WHERE configname = ".$db->quote($configname);
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result;
	}
    function getConfigCount(){
		$db = JFactory::getDBO();
		$query = "SELECT COUNT(*) FROM `#__js_job_config` ";
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}
						
}
?>

