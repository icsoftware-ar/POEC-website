<?php defined('_JEXEC') or die('Restricted access');

// Program: Fox Contact for Joomla
// Copyright (C): 2011 Demis Palma
// Documentation: http://www.fox.ra.it/forum/2-documentation.html
// License: Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html

jimport('joomla.application.component.controller');

class FoxContactController extends JControllerLegacy
{
	function display($cachable = false, $urlparams = array())
	{
		// set default view if not set
		JRequest::setVar("view", JFactory::getApplication()->input->get("view", "Dashboard"));

		// call parent behavior
		parent::display($cachable, $urlparams);
	}
}
