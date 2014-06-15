<?php defined('_JEXEC') or die('Restricted access');

// Program: Fox Contact for Joomla
// Copyright (C): 2011 Demis Palma
// Documentation: http://www.fox.ra.it/forum/2-documentation.html
// License: Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html

abstract class FDataPump
{
	public $Params; // Component or module params
	public $Application;
	public $Name;
	public $Fields = array();
	public $Style = array();
	protected $Submitted;
	protected $Logger;
	protected $isvalid;
	protected $MessageBoard;
	protected $JInput;

	// Label contruction
	protected $FieldValue;
	protected $LabelHtmlCode;
	protected $JSCode;


	abstract protected function LoadFields();


	public function __construct(&$params, FoxMessageBoard &$messageboard)
	{
		$this->Params = & $params;
		$this->MessageBoard = & $messageboard;
		$this->Application = JFactory::getApplication();
		$this->Submitted = (bool)count($_POST) && isset($_POST[$this->GetId()]);
		$this->JInput = JFactory::getApplication()->input;
		$this->LoadFields();
	}


	public function IsValid()
	{
		return $this->isvalid;
	}


	// $js_name: javascript file name without path
	// $where can be 0 if you want the script as a return value, 1 if you want the script added to the <head> html section
	// $how can be 0 if you want the script source in the form <script>source</script>, 1 if you want an external script in the form <script src="name"></script>
	// $placeholders is an array of string to find in the source of the script
	// $values is an array of string used as replacements for occurrences of placeholders
	public function js_load($js_name, $where, $how, &$placeholders = array(), &$values = array())
	{
		// jimport( 'joomla.filesystem.path' );
		// JPATH_SITE represents the root path of the JSite application, just as
		// JPATH_ADMINISTRATOR represents the root path of the JAdministrator application.
		// JPATH_BASE is the root path for the current requested application.... so if you are in the administrator application, JPATH_BASE == JPATH_ADMINISTRATOR... if you are in the site application JPATH_BASE == JPATH_SITE... if you are in the installation application JPATH_BASE == JPATH_INSTALLATION.
		// JPATH_ROOT is the root path for the Joomla install and does not depend upon any application.
		// JUri::base() is http://localhost/joomla/
		// JUri::base(true) is "" or "/joomla"

		// Determine what will have to do
		$action = $where * 1 + $how * 10;

		// Complete the script name with its path
		$js_local_name = JPATH_ROOT . "/components/" . $GLOBALS["com_name"] . "/js/" . $js_name;
		$js_http_name = JUri::base(true) . "/components/" . $GLOBALS["com_name"] . "/js/" . $js_name;

		$document = JFactory::getDocument(); // Needed if '$where' is 1

		if (!$how) // we will use the source
		{
			// Open js file
			$handle = @fopen($js_local_name, 'r');
			// Read the content
			$js_source = "\n//<![CDATA[\n" . fread($handle, filesize($js_local_name)) . "\n//]]>\n";
			// Close js file
			fclose($handle);
			// swap variables values
			$js_source = str_replace($placeholders, $values, $js_source);
		}

		// Todo: Reset arrays to an empty one. Not really needed, but improves performance of the next js_load() call
		// $placeholders = $values = array();

		switch ($action)
		{
			case 0: // $where = 0, $how = 0
				return "\n" . '<script type="text/javascript">' . $js_source . "</script>\n";
			case 1: // $where = 1, $how = 0
				$document->addScriptDeclaration($js_source);
				break;
			case 10: // $where = 0, $how = 1
				return "\n" . '<script type="text/javascript" src="' . $js_http_name . '"></script>' . "\n";
			case 11: // $where = 1, $how = 1
				$document->addScript($js_http_name);
		}

		return "";
	}


	protected function LoadField($type, $name) // Example: 'text', 'text0'
	{
		$enabled = intval($this->Params->get($name . "display", "0"));
		// If not to be displayed, it's useless to continue reading other values
		if (!$enabled) return false;

		$this->Fields[$name]["Display"] = intval($this->Params->get($name . "display", "0"));
		$this->Fields[$name]["Type"] = $type;
		$this->Fields[$name]["Name"] = $this->Params->get($name, "");
		$this->Fields[$name]["PostName"] = $this->SafeName($this->Fields[$name]["Name"] . $this->Application->cid . $this->Application->mid);
		$this->Fields[$name]["Values"] = $this->Params->get($name . "values", "");
		$this->Fields[$name]["Width"] = intval($this->Params->get($type . "width", ""));
		$this->Fields[$name]["Height"] = intval($this->Params->get($type . "height", ""));
		$this->Fields[$name]["Unit"] = $this->Params->get($type . "unit", "");
		$this->Fields[$name]["Order"] = intval($this->Params->get($name . "order", 0));
		return true;
	}


	protected function MakeText($key)
	{
		$text = $this->Params->get($key, "");
		if (empty($text)) return "";
		return
			'<div class="foxmessage" style="clear:both;">' .
			$text .
			'</div>';
	}


	protected function AdditionalDescription($display)
	{
		return ($display == 2) ? ("<span class=\"required\"></span>") : "";
	}


	protected function SafeName($name)
	{
		// In $_POST[names], spaces are replaced with underscores. The reason is that PHP used to create a local variable
		// for each form value (now it's optional an deprecated) and you can't have a variable with spaces on its name.
		// Other characters than spaces, are not invalid. So, it's better replace all of them

		// In addition, a valid variable name starts with a letter or underscore, followed by any number of letters, numbers, or underscores.
		// As a regular expression, it would be expressed thus: '[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*
		// In case field name starts with a number, it's better to put an underscore before it
		// "/[^a-zA-Z0-9\s]/" this allows spaces
		// "/[^a-zA-Z0-9]/" this doesn't allow spaces
		// This code doesn't work for non latin charsets, because builds a name with only underscores
		//$name = "_" . preg_replace("/[^a-zA-Z0-9]/", "_", $name);
		// Truncate to 64 char
		//$name = substr($name, 0, 64);
		//return $name;
		return "_" . md5($name);
	}


	protected function GetComponentId()
	{
		global $app;
		if (strpos($app->scope, "com_") !== 0) return 0;

		$wholemenu = $this->Application->getMenu();
		$targetmenu = $wholemenu->getActive();
		return $targetmenu->id;
	}


	protected function GetId($separator = "_")
	{
		$id = substr($this->Application->scope, 0, 1); // can be "c" or "m"
		switch ($id)
		{
			case "c":
				$wholemenu = $this->Application->getMenu();
				$activemenu = $wholemenu->getActive();
				$id .= "id" . $separator . $activemenu->id;
				break;

			case "m":
				//$id .= "id_" . $module->id;
				$id .= "id" . $separator . $this->Application->mid;
				break;

			default:
				$id = "";
		}

		return $id;
	}


	protected function CreateStandardLabel($field)
	{
		// This is a standard label, such as in Text or Textarea fields
		// It doesn't depend on the layout, but it only depends on the "labelsdisplay" parameter
		// When labelsdisplay is "beside fields" the label has the field name
		// When labelsdisplay is "inside fields" the label isn't drawn at all

		if ((bool)$this->Params->get("labelsdisplay"))
		{
			// Labels beside
			$this->FieldValue = $field["Value"];
			$this->LabelHtmlCode = '<label class="control-label">' . $field["Name"] . $this->AdditionalDescription($field["Display"]) . '</label>';
			$this->JSCode = "";
		}
		else
		{
			// Labels inside
			// If a value was submitted use it as text, otherwise use the field name
			$this->FieldValue = $field["Value"] ? $field["Value"] : $field["Name"];
			$this->LabelHtmlCode = "";
			$this->JSCode = "onfocus=\"if(this.value==this.title) this.value='';\" onblur=\"if(this.value=='') this.value=this.title;\" ";
		}
	}


	protected function CreateSpacerLabel()
	{
		// This is a spacer label, such as in Checkbox fields or submit button
		// It depends on the "labelsdisplay" parameter, which need to be "beside fields" for this spacer to be drown,
		// but in addition it depends on the layout
		// When layout is compact or extended the label assumes a blank but valid value in order to align fields even if it isn't visible.
		//
		// Text |________|
		// .... [] Checbox
		//
		// When layout is stacked or inline, the label is useless and annoying.
		//
		// == Inline ==
		// Text |________| .... [] Checkbox
		//
		// == Stacked ==
		// Text
		// |________|
		// ....
		// [] Checkbox
		//
		// In no cases the label assumes the field name. It is only a spacer.

		$layout = $this->Params->get("form_layout", "extended");

		if ((bool)$this->Params->get("labelsdisplay") && ($layout == "compact" || $layout == "extended"))
		{
			$this->LabelHtmlCode = '<label class="control-label">&nbsp;</label>';
		}
		else
		{
			$this->LabelHtmlCode = "";
		}

	}

}

?>
