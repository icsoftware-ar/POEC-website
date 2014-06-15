<?php defined('_JEXEC') or die('Restricted access');

// Program: Fox Contact for Joomla
// Copyright (C): 2011 Demis Palma
// Documentation: http://www.fox.ra.it/forum/2-documentation.html
// License: Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html

require_once(realpath(dirname(__FILE__) . '/foxinstall.php'));

class com_foxcontactInstallerScript extends FoxInstaller
{
	function update($parent)
	{
		parent::install($parent);
	}
}