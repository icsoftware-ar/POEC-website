<?php
/**
 * @Copyright Copyright (C) 2012 ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	April 05, 2012
 ^
 + Project: 		JS Autoz
 ^ 
*/
 
defined('_JEXEC') or die('Restricted access'); 
$document =& JFactory::getDocument();
$document->addStyleSheet('components/com_jsjobs/include/installer.css');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<script>
function opendiv(){
	document.getElementById('jsjob_installer_waiting_div').style.display='block';
	document.getElementById('jsjob_installer_waiting_span').style.display='block';
}
</script>

<script type="text/javascript">
// for joomla 1.6
Joomla.submitbutton = function(task){
        if (task == ''){
                return false;
        }else{
                if (task == 'startinstallation'){
                    returnvalue = validate_form(document.adminForm);
                }else returnvalue  = true;
                if (returnvalue){
                        Joomla.submitform(task);
                        return true;
                }else return false;
        }
}

function validate_form(f)
{
        if (document.formvalidator.isValid(f)) {
                f.check.value='<?php if(JVERSION < 3) echo JUtility::getToken(); else echo  JSession::getFormToken(); ?>';//send token
        }
        else {
                alert('Some values are not acceptable.  Please retry.');
				return false;
        }
		opendiv();
		return true;
}
</script>
<span id="jsjob_installer_topheading"><?php echo JText::_('INSTALLATION');?></span>
<div id="jsjob_installer_msg">
	<?php echo JText::_('JS_JOBS_INSTALLER'); ?>
</div>
<form action="index.php" method="POST" name="adminForm" id="adminForm" >
<div id="jsjob_installer_waiting_div" style="display:none;"></div>
<span id="jsjob_installer_waiting_span" style="display:none;"><?php echo JText::_('PLEASE_WAIT_INSTALLATION_IN_PROGRESS');?></span>
<div id="jsjob_installer_outerwrap">
	<div id="jsjob_installer_leftimage">
		<span id="jsjob_installer_leftimage_logo"></span>
	</div>
	<div id="jsjob_installer_wrap">
	<?php if(in_array  ('curl', get_loaded_extensions())) { ?>
		<span id="jsjob_installer_helptext"><?php echo JText::_('PLEASE_FILL_THE_FORM_AND_PRESS_START');?></span>
		<div id="jsjob_installer_formlabel">
			<label id="transactionkeymsg" for="transactionkey"><?php echo JText::_('AUTHENTICATION_KEY'); ?></label>
		</div>
		<div id="jsjob_installer_forminput">
			<input style="height:25px;" id="transactionkey" name="transactionkey" class="inputbox required" value="" />
			<span style="float:right;display:block;" ><img src="components/com_jsjobs/include/images/quastionMark.png" title="<?php echo 'Get Activation Key From Joomsky.com -> My Products'; ?>" height="25px" width="19px"></span>
		</div>
		<div id="jsjob_installer_formsubmitbutton">
			<input type="submit" class="button" id="jsjob_instbutton" name="submit_app" onclick="return validate_form(document.adminForm)" value="<?php echo JText::_('NEXT'); ?>" />
		</div>
	<?php }else{ ?>
		<div id="jsjob_installer_warning"><?php echo JText::_('WARNING'); ?>!</div>
		<div id="jsjob_installer_warningmsg"><?php echo JText::_('CURL_IS_NOT_ENABLE_PLEASE_ENABLE_CURL'); ?></div>
	<?php } ?>
	</div>
</div>
<div id="jsjob_installer_lowerbar">
	<?php if(!in_array  ('curl', get_loaded_extensions())) { ?>
		<span id="jsjob_installer_arrow"><?php echo JText::_('REFRENCE_LINK'); ?></span>
		<span id="jsjob_installer_link"><a href="http://devilsworkshop.org/tutorial/enabling-curl-on-windowsphpapache-machine/702/"><?php echo JText::_('http://devilsworkshop.org/...'); ?></a></span>
		<span id="jsjob_installer_link"><a href="http://www.tomjepson.co.uk/enabling-curl-in-php-php-ini-wamp-xamp-ubuntu/"><?php echo JText::_('http://www.tomjepson.co.uk/...'); ?></a></span>
		<span id="jsjob_installer_link"><a href="http://www.joomlashine.com/blog/how-to-enable-curl-in-php.html"><?php echo JText::_('http://www.joomlashine.com/...'); ?></a></span>
	<?php }else{ ?>
		<span id="jsjob_installer_mintmsg"><?php echo JText::_('IT_MAY_TAKE_FEW_MINUTES...'); ?></span>
	<?php } ?>
</div>
	<input type="hidden" name="check" value="" />
	<input type="hidden" name="domain" value="<?php echo JURI::root(); ?>" />
	<input type="hidden" name="producttype" value="<?php echo $this->vtype->configvalue;?>" />
	<input type="hidden" name="productcode" value="jsjobs" />
	<input type="hidden" name="productversion" value="<?php echo $this->versioncode->configvalue;?>" />
	<input type="hidden" name="count_config" value="<?php echo $this->count_config;?>" />
	<input type="hidden" name="JVERSION" value="<?php echo JVERSION;?>" />
	<input type="hidden" name="c" value="installer" />
	<input type="hidden" name="task" value="startinstallation" />
	<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
</form>
<table width="100%" style="table-layout:fixed;"><tr><td style="vertical-align:top;"><?php echo eval(base64_decode('CQkJZWNobyAnPHRhYmxlIHdpZHRoPSIxMDAlIiBzdHlsZT0idGFibGUtbGF5b3V0OmZpeGVkOyI+DQo8dHI+PHRkIGhlaWdodD0iMTUiPjwvdGQ+PC90cj4NCjx0cj4NCjx0ZCBzdHlsZT0idmVydGljYWwtYWxpZ246bWlkZGxlOyIgYWxpZ249ImNlbnRlciI+DQo8YSBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIiB0YXJnZXQ9Il9ibGFuayI+PGltZyBzcmM9Imh0dHA6Ly93d3cuam9vbXNreS5jb20vbG9nby9qc2pvYnNjcmxvZ28ucG5nIiA+PC9hPg0KPGJyPg0KQ29weXJpZ2h0ICZjb3B5OyAyMDA4IC0gJy4gZGF0ZSgnWScpIC4nLCA8YSBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSIgdGFyZ2V0PSJfYmxhbmsiPkJ1cnVqIFNvbHV0aW9uczwvYT4gDQo8L3RkPg0KPC90cj4NCjwvdGFibGU+JzsNCg=='));	?>	</td></tr></table>
