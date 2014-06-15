<?php
/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 + Created by:	Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/views/applications/tmpl/loadcountryformfile.php
 ^ 
 * Description: Load country, states, counties and cities form file
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');
JRequest :: setVar('layout', 'loadaddressdata');
$_SESSION['cur_layout']='loadaddressdata';

JHTML::_('behavior.formvalidation'); 
$version = new JVersion;
$joomla = $version->getShortVersion();
$jversion = substr($joomla,0,3);
$document = &JFactory::getDocument();
$document->addStyleSheet(JURI::root().'administrator/components/com_jsjobs/include/css/jsjobsadmin.css');
$joomla = $version->getShortVersion();
	if($joomla < 3){
		JHtml::_('behavior.mootools');
		$document->addScript('../components/com_jsjobs/js/jquery.js');
	}else{
		JHtml::_('behavior.framework');
		JHtml::_('jquery.framework');
	}	



?>
<script language="javascript">
// for joomla 1.6
Joomla.submitbutton = function(task){
        if (task == ''){
                return false;
        }else{
                if (task == 'save'){
                    returnvalue = validate_form(document.adminForm);
                }else returnvalue  = true;
                if (returnvalue){
                        Joomla.submitform(task);
                        return true;
                }else return false;
        }
}
// for joomla 1.5

function submitbutton(pressbutton) {
	if (pressbutton) {
		document.adminForm.task.value=pressbutton;
	}
	if(pressbutton == 'save'){
		returnvalue = validate_form(document.adminForm);
	}else returnvalue  = true;
	
	if (returnvalue == true){
		try {
			  document.adminForm.onsubmit();
	        }
		catch(e){}
		document.adminForm.submit();
	}
}

function validate_form(f)
{
        if (document.formvalidator.isValid(f)) {
                f.check.value='<?php if(($jversion == '1.5') || ($jversion == '2.5')) echo JUtility::getToken(); else echo  JSession::getFormToken(); ?>';//send token
        }
        else {
                alert('Some values are not acceptable.  Please retry.');
				return false;
        }
		return true;
}
</script>
<table width="100%">
	<tr>
		<td align="left" width="175" valign="top">
			<table width="100%"><tr><td style="vertical-align:top;">
			<?php
			include_once('components/com_jsjobs/views/menu.php');
			?>
			</td>
			</tr></table>
		</td>
		<td width="100%" valign="top">
				<div id="jsjobs_info_heading"><?php echo JText::_('JS_LOAD_ADDRESS_DATA'); ?></div>
			
			<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
                            <div id="loadaddressdata_wrapper">
                                <div id="loadaddressdata_upper">
                                    <img id="loadaddressdata_companylogo" src="components/com_jsjobs/include/images/logo.png" />
                                    <span id="loadaddressdata_slogon"><?php echo JText::_('DOWNLOAD_FROM_JOOMSKY');?></span>
                                    <a href="http://www.joomsky.com/index.php/download-buy/product/product/8/43" target="_blank" ><img id="loadaddressdata_downloadbutton" src="components/com_jsjobs/include/images/loadaddressdownloadbutton.png"/></a>
                                </div>
                                <span id="loadaddressdata_loadingheading"><?php echo JText::_('LOAD_ADDRESS_TITLE'); ?></span>
                                <div id="loadaddressdata_options">
                                    <div id="loadaddressdata_options_left"><?php echo JText::_('ACTION'); ?></div>
                                    <div id="loadaddressdata_options_right">
                                        <input type="radio" name="datakept" id="option1" value="1" /><label for="option1"><?php echo JText::_('KEPT_DATA'); ?></label>
                                        <input type="radio" name="datakept" id="option2" checked="checked" value="2" /><label for="option2"><?php echo JText::_('DISCARD_OLD_DATA'); ?></label>
                                    </div>
                                    <div id="loadaddressdata_options_left"><?php echo JText::_('FILE'); ?></div>
                                    <div id="loadaddressdata_options_right">
                                        <input type="radio" name="fileowner" id="fileowner1" value="1" /><label for="fileowner1"><?php echo JText::_('MY_FILE'); ?></label>
                                        <input type="radio" name="fileowner" id="fileowner2" checked="checked" value="2" /><label for="fileowner2"><?php echo JText::_('JOOMSKY_FILE'); ?></label>
                                    </div>
                                    <div id="loadaddressdata_options_left"><?php echo JText::_('DATA_CONTAIN'); ?></div>
                                    <div id="loadaddressdata_options_right">
                                        <input type="radio" name="datacontain" id="datacontain1" value="1" /><label for="datacontain1"><?php echo JText::_('STATES'); ?></label>
                                        <input type="radio" name="datacontain" id="datacontain2" value="2" /><label for="datacontain2"><?php echo JText::_('CITIES'); ?></label>
                                        <input type="radio" name="datacontain" id="datacontain3" checked="checked" value="3" /><label for="datacontain3"><?php echo JText::_('STATES_AND_CITIES'); ?></label>
                                    </div>
                                    <div id="loadaddressdata_file">
                                        <div id="loadaddressdata_msg" >
                                            <span id="loadaddressdata_msg"><?php echo JText::_('CONSIDER_OLD_COUNTRIES_WERE_NOT_EDIT_OTHERWISE_PROBLEM_MAY_OCCURED'); ?></span>
                                        </div>
                                        <?php echo JText::_('FILE'); ?> :&nbsp;<font color="red">*</font>&nbsp;<input type="file" class="inputbox  required" name="loadaddressdata" id="loadaddressdata" size="20" maxlenght='30'/>
                                        <input class="button" type="submit" name="submit_app" id="submitbutton" value="<?php echo JText::_('LOAD_ADDRESS_DATA'); ?>" onclick="return validate_form(document.adminForm)" />
                                    </div>
                                </div>
                            </div>
				

				
				
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			<input type="hidden" name="task" value="loadaddressdata" />
			<input type="hidden" name="check" value="" />
			</form>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			
		</td>
	</tr>
	
</table>							
<script>
    jQuery("input[type=radio]").change(function(){
        var keptdata = jQuery("#option1").is(':checked');
        var discarddata = jQuery("#option2").is(':checked');
        var myfile = jQuery("#fileowner1").is(':checked');
        var joomskyfile = jQuery("#fileowner2").is(':checked');
        var states = jQuery("#datacontain1").is(':checked');
        var cities = jQuery("#datacontain2").is(':checked');
        var statesandcities = jQuery("#datacontain3").is(':checked');
        var msg = '';
        if(keptdata == true){
            if(myfile == true){
                if(states == true){
                    msg = "<?php echo JText::_('STATE_ID_GREATER_COUNTRY_ID_IS_YOUR_RESPONSIBILITY')?>";
                }else if(cities == true){
                    msg = "<?php echo JText::_('CITIES_HAVE_NO_IDS_ALSO_STATE_ID_AND_COUNTRY_ID_IS_YOUR_RESPONSIBILITY')?>";
                }else if(statesandcities == true){
                    msg = "<?php echo JText::_('STATE_ID_GREATER_AND_CITIES_HAVE_NO_IDS_ALSO_STATE_ID_AND_COUNTRY_ID_IS_YOUR_RESPONSIBILITY')?>";
                }
            }else if(joomskyfile == true){
                if(states == true){
                    msg = "";
                }else if(cities == true){
                    msg = "<?php echo JText::_('CONSIDER_OLD_COUNTRIES_AND_STATES_WERE_NOT_EDIT_OTHERWISE_PROBLEM_MAY_OCCURED')?>";
                }else if(statesandcities == true){
                    msg = "<?php echo JText::_('CONSIDER_OLD_COUNTRIES_WERE_NOT_EDIT_OTHERWISE_PROBLEM_MAY_OCCURED')?>";
                }
            }
        }else if(discarddata == true){
            if(myfile == true){
                if(states == true){
                    msg = "<?php echo JText::_('STATE_ID_GREATER_COUNTRY_ID_IS_YOUR_RESPONSIBILITY')?>";
                }else if(cities == true){
                    msg = "<?php echo JText::_('CITIES_HAVE_NO_IDS_ALSO_STATE_ID_AND_COUNTRY_ID_IS_YOUR_RESPONSIBILITY')?>";
                }else if(statesandcities == true){
                    msg = "<?php echo JText::_('STATE_ID_GREATER_AND_CITIES_HAVE_NO_IDS_ALSO_STATE_ID_AND_COUNTRY_ID_IS_YOUR_RESPONSIBILITY')?>";
                }
            }else if(joomskyfile == true){
                if(states == true){
                    msg = "";
                }else if(cities == true){
                    msg = "<?php echo JText::_('CONSIDER_OLD_COUNTRIES_AND_STATES_WERE_NOT_EDIT_OTHERWISE_PROBLEM_MAY_OCCURED')?>";
                }else if(statesandcities == true){
                    msg = "<?php echo JText::_('CONSIDER_OLD_COUNTRIES_WERE_NOT_EDIT_OTHERWISE_PROBLEM_MAY_OCCURED')?>";
                }
            }
        }
        
        if(msg != ""){
            jQuery("span#loadaddressdata_msg").html(msg);
            jQuery("div#loadaddressdata_msg").slideDown("slow");
        }else
            jQuery("div#loadaddressdata_msg").slideUp("slow");
    });
</script>


