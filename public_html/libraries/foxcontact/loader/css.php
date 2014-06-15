<?php defined('_JEXEC') or die('Restricted access');

// Program: Fox Contact for Joomla
// Copyright (C): 2011 Demis Palma
// Documentation: http://www.fox.ra.it/forum/2-documentation.html
// License: Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html

require_once "loader.php";

class cssLoader extends Loader
{
	protected function type()
	{
		return "css";
	}

	protected function http_headers()
	{
		header('Content-Type: text/css; charset=utf-8');
	}

	protected function content_header()
	{
		// echo "/* css generator begin */\n";
	}

	protected function content_footer()
	{
		// echo "\n/* css generator end */";
	}
}


