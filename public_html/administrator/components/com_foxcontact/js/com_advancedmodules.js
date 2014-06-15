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
});
