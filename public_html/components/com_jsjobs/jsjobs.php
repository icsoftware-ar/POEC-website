<?php

/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 + Created by:          Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		Job Posting and Employment Application
 * File Name:	controllers/application.php
 ^ 
 * Description: Entry point for the component (jobsnapps)
 ^ 
 * History:		NONE
 ^ 
 * @package com_jsjobs
 ^ 
 * You should have received a copy of the GNU General Public License along with this program;
 ^ 
 * 
 * */

defined('_JEXEC') or die('Restricted access');
if(!defined('JCONST')){
define('JCONST',base64_decode("aHR0cDovL3d3dy5qb29tc2t5LmNvbS9pbmRleC5waHA/b3B0aW9uPWNvbV9qc3Byb2R1Y3RsaXN0aW5nJnRhc2s9YWFnamM="));
}

// requires the default controller 
require_once (JPATH_COMPONENT . '/controller.php');

if ($c = JRequest :: getCmd('c', 'jsjobs'))
{
	$path = JPATH_COMPONENT . '/controllers/' . $c . '.php';
	jimport('joomla.filesystem.file');

	if (JFile :: exists($path))
	{
		require_once ($path);
	}
	else
	{
		JError :: raiseError('500', JText :: _('Unknown controller: <br>' . $c . ':' . $path));
	}
}

if($c == 'sphone') $c = 'JSJobsControllerSphone';
elseif($c == 'paymentnotify') $c = 'JSJobsControllerPaymentnotify';
else $c = 'JSJobsControllerJsjobs';
$controller = new $c ();
$controller->execute(JRequest :: getCmd('task', 'display'));
$controller->redirect();
?>
