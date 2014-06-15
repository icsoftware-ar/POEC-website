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

jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

class JSJobsViewInstaller extends JViewLegacy
{

    function display($tpl = null)
        {

		global $mainframe, $sorton, $sortorder, $option;

		$mainframe = JFactory::getApplication();
		$user	=& JFactory::getUser();
		$uid=$user->id;
		$layout =  JRequest::getVar('layout','installer');

		$model = $this->getModel('installer');
		$option ='com_jsjobs';

		$viewtype = 'html';

		if($layout == 'installer'){ //Installation
			$versioncode = $model->getConfigByConfigName('versioncode');
			$this->assignRef('versioncode', $versioncode);
			$vtype = $model->getConfigByConfigName('vtype');
			$this->assignRef('vtype', $vtype);
			$count_config = $model->getConfigCount();
			$this->assignRef('count_config', $count_config);
			JToolBarHelper :: title(JText :: _('INSTALLATION'));
		}

		$this->assignRef('option', $option);
		$this->assignRef('uid', $uid);
		$this->assignRef('viewtype', $viewtype);
		parent :: display($tpl);
    }

}
?>
