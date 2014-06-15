<?php defined('JPATH_BASE') or die('Restricted access');

// Program: Fox Contact for Joomla
// Copyright (C): 2011 Demis Palma
// Documentation: http://www.fox.ra.it/forum/2-documentation.html
// License: Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html

jimport('joomla.form.formfield');

class JFormFieldFEnvironment extends JFormField
{
	protected $type = 'FEnvironment';


	public function __construct(JForm $form = null)
	{
		parent::__construct($form);

		/*
							 (include_once JPATH_ROOT . "/components/com_foxcontact/helpers/flogger.php") or die(JText::sprintf("JLIB_FILESYSTEM_ERROR_READ_UNABLE_TO_OPEN_FILE", "flogger.php"));
							 $log = new FLogger($this->type, "debug");
							 $log->Write($this->element["name"] . " getLabel()");
		*/

		static $resources = true;
		if ($resources)
		{
			$resources = false;
			$name = basename(realpath(dirname(__FILE__) . "/../.."));
			$document = JFactory::getDocument();

			// $this->element is not ready on the constructor
			//$type = (string)$this->element["type"];
			$type = strtolower($this->type);
			if (file_exists(JPATH_ADMINISTRATOR . "/components/" . $name . "/js/" . $type . ".js"))
			{
				$document->addScript(JUri::current() . "?option=" . $name . "&amp;view=loader&amp;filename=" . $type . "&amp;type=js");
			}

			if (file_exists(JPATH_ADMINISTRATOR . "/components/" . $name . "/css/" . $type . ".css"))
			{
				$document->addStyleSheet(JUri::base(true) . "/components/" . $name . "/css/" . $type . ".css");
			}

			$scope = JFactory::getApplication()->scope;
			if (file_exists(JPATH_ADMINISTRATOR . "/components/" . $name . "/js/" . $scope . ".js"))
			{
				$document->addScript(JUri::current() . "?option=" . $name . "&amp;view=loader&amp;filename=" . $scope . "&amp;type=js");
			}

			if (file_exists(JPATH_ADMINISTRATOR . "/components/" . $name . "/css/" . $scope . ".css"))
			{
				$document->addStyleSheet(JUri::base(true) . "/components/" . $name . "/css/" . $scope . ".css");
			}

			$GLOBALS["com_name"] = basename(realpath(dirname(__FILE__) . "/../.."));

			// If we are in module manager always load the language, since the default module admin language is empty
			$option = JFactory::getApplication()->input->get("option");
			// $option != "com_menus" includes "com_modules", "com_advancedmodules" and similar module manager
			$module = $option != "com_menus";
			$language = JFactory::getLanguage();
			$enGB = $language->get("tag") == $language->getDefault();

			// If we are within the component, don't waste our time reloading the same English language two more times.
			// Using less CPU power reduces the world carbon dioxide emissions.
			if (!$enGB || $module)
			{
				// The current language is already been loaded, so it is important for the following workaround to work, that the parameter $reload is set to true
				// Reload the default language (en-GB)
				$language->load($GLOBALS["com_name"], JPATH_ADMINISTRATOR, $language->getDefault(), true);
				// Reload current language, overwriting nearly all the strings, but keeping the english version for untranslated strings
				$language->load($GLOBALS["com_name"], JPATH_ADMINISTRATOR, null, true);
			}
		}

	}


	protected function getInput()
	{
		return "";
	}


	protected function getLabel()
	{
		return "";
	}
}
