<?php defined('_JEXEC') or die('Restricted access');

// Program: Fox Contact for Joomla
// Copyright (C): 2011 Demis Palma
// Documentation: http://www.fox.ra.it/forum/2-documentation.html
// License: Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html

abstract class Loader
{
	abstract protected function type();
	abstract protected function http_headers();
	abstract protected function content_header();
	abstract protected function content_footer();


	public function Show()
	{
		$this->headers();
		$this->http_headers();
		$this->content_header();
		$this->load();
		$this->content_footer();

		//die();
		JFactory::getApplication()->close();
	}


	private function headers()
	{
		// Prepare some useful headers
		header("Expires: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		// must not be cached by the client browser or any proxy
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
	}


	protected function load()
	{
		// Prepares the unique id value ($uid) to be used by the script
		$input = JFactory::getApplication()->input;
		$owner = $input->get("owner", "component");
		$id = $input->get("id", "0");
		// Variables usable by the resource files
		// Unique id.
		$uid = "_" . $owner[0] . $id;
		// $left and $right directions
		$language = JFactory::getLanguage();
		$direction = intval($language->get('rtl', 0));
		$left = $direction ? "right" : "left";
		$right = $direction ? "left" : "right";
		$juri_root = JURI::root(true);

		// Complete the script name with its path
		$filename = JFactory::getApplication()->input->get("filename", "");
		// Only admit lowercase a-z, underscore and minus. Forbid numbers, symbols, slashes and other stuff.
		preg_match('/^[a-z_-]+$/', $filename) or $filename = "invalid";

		require_once $this->IncludePath . "/" . $this->type() . "/" . $filename . "." . $this->type();
	}

}

