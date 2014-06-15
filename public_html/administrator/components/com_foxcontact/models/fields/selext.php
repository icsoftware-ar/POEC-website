<?php defined('JPATH_BASE') or die('Restricted access');

// Program: Fox Contact for Joomla
// Copyright (C): 2011 Demis Palma
// Documentation: http://www.fox.ra.it/forum/2-documentation.html
// License: Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html

jimport("joomla.form.formfield");

JFormHelper::loadFieldClass("list");
class JFormFieldSelext extends JFormFieldList
{
	protected $type = "Selext";

	public function __construct($form = null)
	{
		parent::__construct($form);

		static $resources = true;
		if ($resources)
		{
			$resources = false;
			$this->com_name = basename(realpath(dirname(__FILE__) . "/../.."));
			$this->document = JFactory::getDocument();

			// $this->element is not ready on the constructor
			//$type = (string)$this->element["type"];

			$type = strtolower($this->type);
			if (file_exists(JPATH_ADMINISTRATOR . "/components/" . $this->com_name . "/js/" . $type . ".js"))
			{
				$this->document->addScript(JUri::base(true) . "/components/" . $this->com_name . "/js/" . $type . ".js");
			}

			if (file_exists(JPATH_ADMINISTRATOR . "/components/" . $this->com_name . "/css/" . $type . ".css"))
			{
				$this->document->addStyleSheet(JUri::base(true) . "/components/" . $this->com_name . "/css/" . $type . ".css");
			}
		}
	}


	protected function getInput()
	{
		if (!is_array($this->value))
		{
			// First time accessing the options. Default value passed needs to be converted into an array.
			$this->value = explode("|", $this->value);
			$this->value["text"] = $this->value[0];
			$this->value["select"] = $this->value[1];
		}

		$size = $this->element["size"] ? 'size="' . (int) $this->element["size"] . '" ' : '';

		$html =
		'<input ' .
		'type="text" ' .
		'name="' . $this->name . '[text]" ' .
		'id="' . $this->id . '_text" ' .
		'value="' . htmlspecialchars($this->value["text"], ENT_COMPAT, 'UTF-8') . '" ' .
		$size .
		'class="selext" />';

		$html .=
		'<select ' .
		'onchange="SelextSelectChange(this, \'' . $this->id . '\');" onkeyup="SelextSelectChange(this, \'' . $this->id . '\');" ' .
		'name="' . $this->name . '[select]" ' .
		'id="' . $this->id . '_select" ' .
		'class="selext">';

		// Get the field options.
		$options = (array)$this->getOptions();
		foreach ($options as $option)
		{
			$selected = ($option->value == $this->value["select"]) ? $selected = 'selected="selected"' : "";
			$html .= '<option value="' . $option->value . '" class="' . $option->class . '" ' . $selected . '>' . $option->text . '</option>';
		}

		$html .= '</select>';

		return $html;
	}

}
