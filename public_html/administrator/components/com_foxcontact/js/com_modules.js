/*<?php
$jversion = new JVersion();
// Old architecture working on Joomla 3.0.0 / 3.0.1 / 3.0.2
if (version_compare($jversion->RELEASE . '.' . $jversion->DEV_LEVEL, "3.0.3", "<")) { ?>*/

var JText = [];
JText['COM_FOXCONTACT_FIELDS_LBL'] = '<?php echo JText::_("COM_FOXCONTACT_FIELDS_LBL"); ?>';
JText['COM_FOXCONTACT_EVENTS_LBL'] = '<?php echo JText::_("COM_FOXCONTACT_EVENTS_LBL"); ?>';
JText['COM_FOXCONTACT_SECURITY_LBL'] = '<?php echo JText::_("COM_FOXCONTACT_SECURITY_LBL"); ?>';
JText['COM_FOXCONTACT_NEWSLETTER_INTEGRATION_LBL'] = '<?php echo JText::_("COM_FOXCONTACT_NEWSLETTER_INTEGRATION_LBL"); ?>';

jQuery(document).ready(function ()
{
	// Only works on Isis template.
	if (!jQuery('div#status').length) return;

	// Count existing tabs
	var tabs = jQuery('ul[class="nav nav-tabs"]').children();

	tabs.each(
		function (index)
		{
			var element = jQuery(this).children('a');
			var text = element.text().trim();
			// Try to translate. Fallback to the current text exactly as it is
			var caption = JText[text] || text;

			// Set the translated text to the child anchor
			element.text(caption);
		});

	// Remove the first 2 elements of the section "Basic"
	var basic = jQuery('div.tab-pane')[1];
	for (var f = 0; f < 2; ++f)
	{
		jQuery(basic.children[0]).remove();
	}

});

/*<?php
// New architecture working on Joomla 3.0.3 and newer
} else { ?>*/

jQuery(document).ready(function ()
{
	// Only works on Isis template.
	if (!jQuery('div#status').length) return;

	var options = jQuery("#moduleOptions");
	// Move the options
	jQuery("#details").append(options);

	// Remove the useless tab
	jQuery('a[href="\\#options"]').parent().remove();

	// Remove the first 2 elements from Basic Options
	for (var f = 0; f < 2; ++f)
	{
		jQuery(options[0].children[0].children[1].children[0].children[0]).remove();
	}
});

/*<?php } ?>*/