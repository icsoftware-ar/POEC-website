<?php defined('_JEXEC') or die('Restricted access');

// Program: Fox Contact for Joomla
// Copyright (C): 2011 Demis Palma
// Documentation: http://www.fox.ra.it/forum/2-documentation.html
// License: Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html

$inc_dir = realpath(dirname(__FILE__));
require_once($inc_dir . '/fdatapump.php');
require_once($inc_dir . '/fsession.php');

class FAjaxUploader extends FDataPump
{
	public function __construct(&$params, FoxMessageBoard &$messageboard)
	{
		parent::__construct($params, $messageboard);

		$this->Name = "FAjaxFilePump";
		$this->isvalid = true;
	}


	protected function LoadFields()
	{
		// Nothing to load for the moment
	}


	// Build a multiple upload field
	public function Show()
	{
		if (!(bool)$this->Params->get("uploaddisplay")) return "";

		$id = $this->GetId();
		//$cid = ((bool)$this->Application->mid) ? 0 : $this->GetComponentId();
		$action =
			JRoute::_("index.php?option=" . $GLOBALS["com_name"] .
			"&view=loader" .
			"&owner=" . $this->Application->owner .
			"&id=" . $this->Application->oid .
			"&root=none" .
			"&filename=none" .
			"&type=uploader");

		$label = "";
		$span = "";
		// Label beside: generates a label
		if ((bool)$this->Params->get("labelsdisplay"))
		{
			$label =
				'<label class="control-label">' .
					$this->Params->get('upload') .
					'</label>';
		}
		// Label inside: generates a little span vertical aligned to the button
		else
		{
			$span =
				'<span class="help-block">' .
					$this->Params->get('upload') .
					'</span>';
		}

		$result =
			// Open row container
			'<div class="control-group">' .
				$label .

				'<div class="controls">' .
				$span .
				// Upload button and list container
				'<div id="foxupload_' . $id . '"></div>' . // foxupload
				'<span class="help-block">' . JText::_($GLOBALS["COM_NAME"] . '_FILE_SIZE_LIMIT') . " " . $this->human_readable($this->Params->get("uploadmax_file_size") * 1024) . '</span>' .
				'</div>' . // controls
				"<script language=\"javascript\" type=\"text/javascript\">" .
				"jQuery(document).ready(function () {" .

"if (typeof Joomla == 'undefined')" .
"{" .
"	Joomla = {};" .
"	Joomla.JText =" .
"	{" .
"		strings:{}," .
"		'_':function (key, def)" .
"		{" .
"			return typeof this.strings[key.toUpperCase()] !== 'undefined' ? this.strings[key.toUpperCase()] : def;" .
"		}," .
"		load:function (object)" .
"		{" .
"			for (var key in object)" .
"			{" .
"				this.strings[key.toUpperCase()] = object[key];" .
"			}" .
"			return this;" .
"		}" .
"	};" .
"}" .

	"Joomla.JText.load(" .
		"{" .
			"\"COM_FOXCONTACT_BROWSE_FILES\":'" .  JText::_("COM_FOXCONTACT_BROWSE_FILES") . "'," .
			"\"JCANCEL\":'" . JText::_("JCANCEL") . "'," .
			"\"COM_FOXCONTACT_FAILED\":'" . JText::_("COM_FOXCONTACT_FAILED") . "'," .
			"\"COM_FOXCONTACT_SUCCESS\":'" . JText::_("COM_FOXCONTACT_SUCCESS") . "'," .
			"\"COM_FOXCONTACT_NO_RESULTS_MATCH\":'" . JText::_("COM_FOXCONTACT_NO_RESULTS_MATCH") . "'" .
		"}" .
	");" .
				"CreateUploadButton('foxupload_$id', '$action', " . $this->Application->cid . ", " . $this->Application->mid . ", '" . $this->Application->owner . "', " . $this->Application->oid . ");" .
				"});" .
				"</script>" .

				// for browsers without javascript support only
				'<noscript>' .
				// Standard file input
				'<input ' .
				'type="file" ' .
				// id raise a w3c error in case of more contact form in the same page: ID "foxstdupload" already defined
				//			'id="foxstdupload" ' .
				'name="foxstdupload"' .
				" />" .
				'</noscript>' .
				"</div>" . PHP_EOL; // control-group

		$jsession = JFactory::getSession();
		$fsession = new FSession($jsession->getId(), $this->Application->cid, $this->Application->mid);
		$data = $fsession->Load('filelist'); // Read the list from the session
		if ($data) $filelist = explode("|", $data);
		else $filelist = array();

		// List of files
		$result .= '<div class="control-group">' .
			'<div class="controls">';

			// Previuosly completed uploads
		$result .= '<ul id="uploadlist-' . $this->Application->owner . $this->Application->oid . '" class="qq-upload-list">';
			foreach ($filelist as &$file)
			{
				$result .=
					'<li class="qq-upload-success">' .
						'<span class="qq-upload-file">' . $this->format_filename(substr($file, 14)) . '</span>' .
						'<span class="qq-upload-success-text">' . JTEXT::_($GLOBALS["COM_NAME"] . '_SUCCESS') . '</span>' .
						'</li>';
			}
			$result .= '</ul>' . PHP_EOL;

		$result .= '</div>' . // controls
			'</div>' . PHP_EOL; // control-group

		return $result;
	}


	protected function human_readable($value)
	{
		for ($i = 0; $value >= 1000; ++$i) $value /= 1024;
		$powers = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
		return round($value, 1) . " " . $powers[$i];
	}


	protected function format_filename($value)
	{
		if (strlen($value) > 33)
		{
			$value = substr($value, 0, 19) . '...' . substr($value, -13);
		}
		return $value;
	}

}

?>
