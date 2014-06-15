<?php



defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class JSJobsViewReport extends JViewLegacy
{
	function display($tpl = null)
	{
		$model		= &$this->getModel();
		$cur_layout = $_SESSION['cur_layout'];
		/*
		if ($results){ //not empty
			foreach ($results as $result){
				$config[$result->configname] = $result->configvalue;
			}
		}
                 * 
                 */
		if (isset($_SESSION['jsjobconfig_dft'])) $config = $_SESSION['jsjobconfig_dft']; else $config = null;
		$type='';		
		$config = Array();
		if (sizeof($config) == 0){
			$results =  $model->getConfig('');
			if (isset($results)){ //not empty
				foreach ($results as $result){
					$config[$result->configname] = $result->configvalue;
				}
				$_SESSION['jsjobconfig_dft'] = $config;
			}
		}

		if($cur_layout == 'resume1'){									
				$resumeid = $_GET['rd'];
				if (is_numeric($resumeid) == true) $result =  $model->getResumeViewbyId($resumeid);	
				$this->assignRef('resume', $result[0]);
				$this->assignRef('resume2', $result[1]);
				$this->assignRef('resume3', $result[2]);
				$this->assignRef('fieldsordering', $result[3]);
		}
	
		$this->assignRef('config', $config);
		
		$document = &JFactory::getDocument();
				$document->setTitle('Resume');
		parent :: display();
	}

}
?>
