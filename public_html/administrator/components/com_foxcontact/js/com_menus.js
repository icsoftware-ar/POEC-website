jQuery(document).ready(function ()
{
	// Only works on Isis template.
	if (!jQuery('div#status').length) return;

	// Check for component view
	// if (jQuery('input[type="hidden"][name="jform\\[type\\]"][value="component"]').length)

	var options = jQuery("#menuOptions");
	// Move the options
	jQuery("#details").append(options);

	// Remove the useless tab
	jQuery('a[href="\\#options"]').parent().remove();

	// Remove the first 2 elements from Basic Options
	// Better code, but by far slower
	//var container = options.children('div:first').children('div:nth-child(2)').children('div:first');
	//for (var f = 0; f < 2; ++f)
	//{
	//	container.children('div:first').remove();
	//}
	// Optimized code
	for (var f = 0; f < 2; ++f)
	{
		jQuery(options[0].children[0].children[1].children[0].children[0]).remove();
	}
});
