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
 * File Name:	admin-----/views/applications/tmpl/jobs.php
 ^ 
 * Description: Default template for jobs view
 ^ 
 * History:		NONE
 ^ 
 */

defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.formvalidation');
$document = &JFactory::getDocument();
$document->addStyleSheet(JURI::root().'administrator/components/com_jsjobs/include/css/jsjobsadmin.css');

global $mainframe;
$document->addStyleSheet('../components/com_jsjobs/css/token-input-jsjobs.css');
	$version = new JVersion;
	$joomla = $version->getShortVersion();
	$jversion = substr($joomla,0,3);

	if($jversion < 3){
		JHtml::_('behavior.mootools');
		$document->addScript('../components/com_jsjobs/js/jquery.js');
	}else{
		JHtml::_('behavior.framework');
		JHtml::_('jquery.framework');
	}	
	$document->addScript('../components/com_jsjobs/js/jquery.tokeninput.js');
	$document->addScript('components/com_jsjobs/include/js/jquery_idTabs.js');


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
        } else {
                alert('<?php echo JText::_( 'JS_SOME_VALUES_ARE_NOT_ACCEPTABLE_PLEASE_CHECK_ALL_TABS');?>');
				return false;
        }
		return true;
}
</script>
	
<?php
 
$ADMINPATH = JPATH_BASE .'\components\com_jsjobs';

$theme = array(
	'0' => array('value' => 'black/css/jsjobsblack.css','text' => JText::_('JS_BLACK_THEME')),
	'1' => array('value' => 'pink/css/jsjobspink.css','text' => JText::_('JS_PINK_THEME')),
	'2' => array('value' => 'orange/css/jsjobsorange.css','text' => JText::_('JS_ORANGE_THEME')),
	'3' => array('value' => 'golden/css/jsjobsgolden.css','text' => JText::_('JS_GOLDEN_THEME')),
	'4' => array('value' => 'blue/css/jsjobsblue.css','text' => JText::_('JS_BLUE_THEME')),
	'5' => array('value' => 'green/css/jsjobsgreen.css','text' => JText::_('JS_GREEN_THEME')),
	'6' => array('value' => 'gray/css/jsjobsgray.css','text' => JText::_('JS_GREY_THEME')),
	'7' => array('value' => 'template/css/templatetheme.css','text' => JText::_('JS_TEMPLATE_THEME')),);

$date_format = array(
	'0' => array('value' => 'd-m-Y','text' => JText::_('JS_DD_MM_YYYY')),
	'1' => array('value' => 'm/d/Y','text' => JText::_('JS_MM_DD_YYYY')),
	'2' => array('value' => 'Y-m-d','text' => JText::_('JS_YYYY_MM_DD')),);
$joblistingstyle = array(
	'1' => array('value' => 'classic','text' => JText::_('JS_CLASSIC')),
	'2' => array('value' => 'july2011','text' => JText::_('JS_NEW')),);
$resumelistingstyle = array(
	'1' => array('value' => 'tabular','text' => JText::_('JS_TABULAR')),
	'2' => array('value' => 'sliding','text' => JText::_('JS_SLIDING')),);

$yesno = array(
	'0' => array('value' => 1,
					'text' => JText::_('Yes')),
	'1' => array('value' => 0,
					'text' => JText::_('No')),);

$yesnobackup = array(
	'0' => array('value' => 1,
					'text' => JText::_('JS_YES_RECOMMENDED')),
	'1' => array('value' => 0,
					'text' => JText::_('No')),);

$showhide = array(
	'0' => array('value' => 1,
					'text' => JText::_('Show')),
	'1' => array('value' => 0,
					'text' => JText::_('Hide')),);
$defaultradius = array(
	'0' => array('value' => 1, 'text' => JText::_('Meters')),
	'1' => array('value' => 2, 'text' => JText::_('Kilometers')),
	'2' => array('value' => 3, 'text' => JText::_('Miles')),
	'3' => array('value' => 4, 'text' => JText::_('Neutical Miles')),
	);

$paymentmethodsarray = array(
	'0' => array('value' => 'paypal','text' => JText::_('PAYPAL')),
	'1' => array('value' => 'fastspring','text' => JText::_('FASTSPRING')),
	'2' => array('value' => 'authorizenet','text' => JText::_('AUTHORIZE_NET')),
	'3' => array('value' => '2checkout','text' => JText::_('2CHECKOUT')),
	'4' => array('value' => 'pagseguro','text' => JText::_('PAGSEGURO')),
	'5' => array('value' => 'other','text' => JText::_('JS_OTHER')),
	'6' => array('value' => 'no','text' => JText::_('JS_NOT_USE')),);

$defaultaddressdisplaytype = array(
	'0' => array('value' => 'csc','text' => JText::_('JS_CITY_STATE_COUNTRY')),
	'1' => array('value' => 'cs','text' => JText::_('JS_CITY_STATE')),
	'2' => array('value' => 'cc','text' => JText::_('JS_CITY_COUNTRY')),
	'3' => array('value' => 'c','text' => JText::_('JS_CITY')),
    );

$themes = JHTML::_('select.genericList', $theme, 'theme', 'class="inputbox" '. '', 'value', 'text', $this->config['theme']);

$date_format = JHTML::_('select.genericList', $date_format, 'date_format', 'class="inputbox" '. '', 'value', 'text', $this->config['date_format']);


//rss

$big_field_width = 40;
$med_field_width = 25;
$sml_field_width = 15;

?>

<table width="100%" >
	<tr>
		<td align="left" width="188"  valign="top">
			<table width="100%" style="table-layout:fixed;"><tr><td style="vertical-align:top;">
			<?php
			include_once('components/com_jsjobs/views/menu.php');
			?>
			</td>
			</tr></table>
		</td>
		<td width="789" valign="top" align="left">
<div id="jsjobs_info_heading"><?php echo JText::_('JS_GENERAL_CONFIGURATION'); ?></div>	

                    <form action="index.php" method="POST" name="adminForm" id="adminForm" >
						<input type="hidden" name="check" value="post"/>
					<div id="tabs_wrapper" class="tabs_wrapper">
						<div class="idTabs">
							<span><a class="selected" href="#site_setting"><?php echo JText::_('JS_SITE_SETTINGS');?></a></span> 
							<span><a  href="#listjobs"><?php echo JText::_('JS_LISTJOB');?></a></span> 
							<span><a  href="#listresumeoption"><?php echo JText::_('JS_LISTRESUME');?></a></span> 
							<span><a  href="#listjobsoption"><?php echo JText::_('JS_JOB_LISTING_OPTIONS');?></a></span> 
							<span><a  href="#filter"><?php echo JText::_('JS_FILTER');?></a></span> 
							<span><a  href="#package"><?php echo JText::_('JS_PACKAGE');?></a></span> 
							<!--<span><a  href="#payment"><?php echo JText::_('JS_PAYMENT');?></a></span> -->
							<span><a  href="#email"><?php echo JText::_('JS_EMAIL');?></a></span> 
							<span><a  href="#userregistration"><?php echo JText::_('JS_USER_REGISTRATION');?></a></span> 
							<span><a  href="#socialsharing"><?php echo JText::_('JS_JOB_SOCIAL_SHARING');?></a></span> 
							<span><a  href="#rss"><?php echo JText::_('JS_RSS_SETTING');?></a></span> 
							<span><a  href="#googlemapadsense"><?php echo JText::_('JS_GOOGLE_MAP_AND_ADSENSE');?></a></span> 
							<?php  if($this->isjobsharing) {?> 
								<span><a  href="#jobsharing"><?php echo JText::_('JS_JOB_SHARING');?></a></span> 
							<?php } ?>	
						</div>
						<div id="site_setting">
								<fieldset>
								<legend><?php echo JText::_('JS_SITE_SETTINGS'); ?></legend>
								<table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
                                <tr>
                                    <td  class="key" width="25%"><?php echo JText::_('JS_TITLE'); ?></td>
                                    <td  width="25%">
										<input type="text" name="title" value="<?php echo $this->config['title']; ?>" class="inputbox" size="<?php echo $med_field_width; ?>" maxlength="255" />
										
                                    </td>
                                    <td><span><small><?php echo JText::_('JS_TITLE_SHOW_ON_TOP');?></small></span></td>
								</tr>
								<tr>
                                    <td  class="key"><?php echo JText::_('JS_ITEMS_NEW_FOR'); ?></td>
                                    <td>
										<input type="text" name="newdays" value="<?php echo $this->config['newdays']; ?>" class="inputbox validate-numeric" maxlength="6" size="<?php echo $med_field_width; ?>" maxlength="5" /> &nbsp;<?php echo JText::_('JS_DAYS'); ?>
										
                                    </td>
                                    <td><span><small><?php echo JText::_('JS_JOBS_MARKED_AS_NEW_IN_LISTING');?></small></span></td>
                                </tr>
								<tr>
									<td class="key" ><?php echo JText::_('JS_OFFLINE'); ?></td>
									<td >
										<?php echo JHTML::_('select.genericList', $yesno, 'offline', 'class="inputbox" '. '', 'value', 'text', $this->config['offline']); ?>
									</td>
								</tr>
                                <tr>
                                    <td class="key" ><?php echo JText::_('JS_OFFLINE_MESSAGE'); ?></td>
                                    <td><textarea name="offline_text" cols="25" rows="3" class="inputbox"><?php echo $this->config['offline_text']; ?></textarea> </td>
								</tr>
								<tr>
                                    <td class="key" ><?php echo JText::_('JS_DATA_DIRECTORY'); ?></td>
                                    <td >
										<input type="text" name="data_directory" value="<?php echo $this->config['data_directory']; ?>" class="inputbox" size="<?php echo $med_field_width;?>"/>
										
									</td>
									<td><span><small><?php echo JText::_('JS_ALL_UPLOADED_FILES_WERE_SAVE_IN_THIS_DIRECTORY');?></small></span></td>
                                </tr>
								<tr>
                                    <td class="key"><?php echo JText::_('JS_DATE_FORMATE'); ?></td>
                                    <td>
										<?php echo $date_format; ?>
										
									</td>
									<td><span><small><?php echo JText::_('JS_DATE_FORMAT_WHICH_IS_USED_IN_WHOLE_APPLICATION');?></small></span></td>
                                </tr>
								<tr>
                                    <td  class="key"><?php echo JText::_('JS_SHOW_CUR_LOCATION'); ?></td>
                                    <td>
										<?php echo JHTML::_('select.genericList', $yesno, 'cur_location', 'class="inputbox" '. '', 'value', 'text', $this->config['cur_location']); ?>
									</td>
									<td><span><small><?php echo JText::_('JS_SHOW_CURRENT_LOCATION_IN_TITLE_AREA');?></small></span></td>
                                </tr>
                                <tr>
                                    <td class="key" ><?php echo JText::_('JS_CATEGORIES_COLS_PER_ROW'); ?></td>
                                    <td>
										<input type="text" name="categories_colsperrow" value="<?php echo $this->config['categories_colsperrow']; ?>" class="inputbox validate-numeric" maxlength="6" size="<?php echo $med_field_width; ?>" />
									</td>
									<td><span><small><?php echo JText::_('JS_SHOW_NUMBER_OF_CATEGORIES_IN_ONE_ROW_IN_JOBS_AND_RESUME');?></small></span></td>
								</tr>
								<tr>
                                    <td class="key"><?php echo JText::_('JS_MESSAGE_AUTO_APPROVE'); ?></td>
                                    <td>
										<?php echo JHTML::_('select.genericList', $yesno, 'message_auto_approve', 'class="inputbox" '. '', 'value', 'text', $this->config['message_auto_approve']); ?>
										
									</td>
									<td><span><small><?php echo JText::_('JS_AUTO_APPROVE_MESSAGES_FOR_JOBSEEKER_AND_EMPLOYER');?></small></span></td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_CONFLICT_MESSAGE_AUTO_APPROVE'); ?></td>
                                    <td>
										<?php echo JHTML::_('select.genericList', $yesno, 'conflict_message_auto_approve', 'class="inputbox" '. '', 'value', 'text', $this->config['conflict_message_auto_approve']); ?>
										
									</td>
									<td><span><small><?php echo JText::_('JS_AUTO_APPROVE_CONFLICTED_MESSAGES_FOR_JOBSEEKER_AND_EMPLOYER');?></small></span></td>
								</tr>
								<tr>
                                    <td  class="key" width="25%"><?php echo JText::_('JS_JOB_TESTING_MODE'); ?></td>
                                    <td >
										<?php echo JHTML::_('select.genericList', $yesno, 'testing_mode', 'class="inputbox" '. '', 'value', 'text', $this->config['testing_mode']);; ?>
										
									</td>
									<td><span><small><?php echo JText::_('JS_APPLICATION_RUN_IN_TESTING_MODE');?></small></span></td>
                                </tr>
								<tr>
                                    <td  class="key" width="25%"><?php echo JText::_('JS_DEFAULT_ADDRESS_DISPLAY_TYPE'); ?></td>
                                    <td >
										<?php echo JHTML::_('select.genericList', $defaultaddressdisplaytype, 'defaultaddressdisplaytype', 'class="inputbox" '. '', 'value', 'text', $this->config['defaultaddressdisplaytype']); ?>
										
									</td>
									<td><span><small></small></span></td>
                                </tr>
				<tr>
                                    <td  class="key" width="25%"><?php echo JText::_('JS_JOBSEEKER_DEFAULT_GROUP'); ?></td>
                                    <td ><?php echo $this->lists['jobseeker_group']; ?>	</td>
					<td><span><small></small></span></td>
                                </tr>
				<tr>
                                    <td  class="key" width="25%"><?php echo JText::_('JS_EMPLOYER_DEFAULT_GROUP'); ?></td>
                                    <td ><?php echo $this->lists['employer_group']; ?>	</td>
					<td><span><small></small></span></td>
                                </tr>
                            </table>
                            </fieldset>
                            <fieldset>
                            <legend><?php echo JText::_('JS_THEMES'); ?></legend>
                            <table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
                                <tr>
                                    <td width="20%" class="key"><?php echo JText::_('JS_TOP_IMAGE'); ?></td>
                                    <td width="30%">
										<?php echo JHTML::_('select.genericList', $showhide, 'topimage', 'class="inputbox" '. '', 'value', 'text', $this->config['topimage']); ?>
										
									</td>
									<td><span><small><?php echo JText::_('JS_TITLE_BACKGROUND_IMAGE_OF_SELECTED_THEME');?></small></span></td>
                                </tr>
                            </table>
                            </fieldset>
						</div>
						<div id="listjobs">
                            <fieldset>
                            <legend><?php echo JText::_('JS_LISTING_STYLE'); ?></legend>
                            <table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
                                <tr >
                                    <td class="key" width="25%"><?php echo JText::_('JS_SHOW_LABEL_IN_LISTING'); ?></td>
                                    <td  width="25%"><?php echo JHTML::_('select.genericList', $yesno, 'labelinlisting', 'class="inputbox" '. '', 'value', 'text', $this->config['labelinlisting']); ?><br clear="all"/></td>
                                    <td><small><?php echo JText::_('JS_SHOW_LABEL_IN_ALL_JOB_LISTING_MY_JOBS_AND_ETC');?></small></td>
                                </tr>
                                <tr>
                                    <td class="key" width="25%"><?php echo JText::_('JS_SHOW_GOLD_JOBS_IN_NEWEST_JOBS'); ?></td>
                                    <td  width="25%"><?php echo JHTML::_('select.genericList', $yesno, 'showgoldjobsinnewestjobs', 'class="inputbox" '. '', 'value', 'text', $this->config['showgoldjobsinnewestjobs']); ?><br clear="all"/></td>
                                </tr>
                                <tr>
                                    <td class="key" width="25%"><?php echo JText::_('JS_SHOW_FEATURED_JOBS_IN_NEWEST_JOBS'); ?></td>
                                    <td  width="25%"><?php echo JHTML::_('select.genericList', $yesno, 'showfeaturedjobsinnewestjobs', 'class="inputbox" '. '', 'value', 'text', $this->config['showfeaturedjobsinnewestjobs']); ?><br clear="all"/></td>
                                </tr>
                                <tr>
                                    <td class="key" width="25%"><?php echo JText::_('JS_SHOW_GOLD_JOBS_IN_LIST_JOBS'); ?></td>
                                    <td  width="25%"><?php echo JHTML::_('select.genericList', $yesno, 'showgoldjobsinlistjobs', 'class="inputbox" '. '', 'value', 'text', $this->config['showgoldjobsinlistjobs']); ?></td>
                                    <td><small><?php echo JText::_('JS_GOLD_JOBS_SHOWS_IN_JOBS_BY_CATEGORY_AND_SUBCATEGORY');?></small></td>
                                </tr>
                                <tr>
                                    <td class="key" width="25%"><?php echo JText::_('JS_SHOW_FEATURED_JOBS_IN_LIST_JOBS'); ?></td>
                                    <td  width="25%"><?php echo JHTML::_('select.genericList', $yesno, 'showfeaturedjobsinlistjobs', 'class="inputbox" '. '', 'value', 'text', $this->config['showfeaturedjobsinlistjobs']); ?></td>
                                    <td><small><?php echo JText::_('JS_FEATURED_JOBS_SHOWS_IN_JOBS_BY_CATEGORY_AND_SUBCATEGORY');?></small></td>
                                </tr>
                                <tr >
                                    <td class="key" width="25%"><?php echo JText::_('JS_SHOW_NO_OF_GOLD_JOBS'); ?></td>
                                    <td  width="25%">
										<input type="text" name="noofgoldjobsinlisting" value="<?php echo $this->config['noofgoldjobsinlisting']; ?>" class="inputbox validate-numeric" maxlength="6" size="<?php echo $med_field_width; ?>" />
									</td>
									<td>
										<small><?php echo JText::_('JS_SHOW_GOLD_JOB_IN_JOB_LISTING');?></small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="key" width="25%"><?php echo JText::_('JS_SHOW_NO_OF_FEATURED_JOBS'); ?></td>
                                    <td  width="25%">
										<input type="text" name="nooffeaturedjobsinlisting" value="<?php echo $this->config['nooffeaturedjobsinlisting']; ?>" class="inputbox validate-numeric" maxlength="6" size="<?php echo $med_field_width; ?>" />
									</td>
									<td>
										<small><?php echo JText::_('JS_SHOW_FEATURED_JOB_IN_JOB_LISTING');?></small>
                                    </td>
                                </tr>
                            </table>
                            </fieldset>
                            <fieldset>
                            <legend><?php echo JText::_('SUB_CATEGORIES'); ?></legend>
                            <table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
                                <tr >
                                    <td class="key" width="25%"><?php echo JText::_('SUB_CATEGORIES'); ?></td>
                                    <td  width="25%">
										<?php echo JHTML::_('select.genericList', $showhide, 'subcategories', 'class="inputbox" '. '', 'value', 'text', $this->config['subcategories']); ?>
										
									</td>
									<td><span><small><?php echo JText::_('JS_SHOW_SUB_CATEGORIES_IN_JOBS_BY_CATEGORIES_LIST');?></small></span></td>
								</tr>
								<tr>
                                    <td  width="25%" class="key"><?php echo JText::_('JS_ALL_SUBCATEGORIES'); ?></td>
                                    <td width="25%">
										<?php echo JHTML::_('select.genericList', $yesno, 'subcategories_all', 'class="inputbox" '. '', 'value', 'text', $this->config['subcategories_all']); ?>
										
									</td>
									<td><span><small><?php echo JText::_('JS_SHOW_ALL_SUB_CATEGORIES_OF_CATEGORY_OR_SHOW_THOSE_WHO_HAD_JOBS');?></small></span></td>
                                </tr>
                                <tr>
                                    <td class="key" ><?php echo JText::_('JS_COLS_PER_ROW'); ?></td>
                                    <td><input type="text" name="subcategories_colsperrow" value="<?php echo $this->config['subcategories_colsperrow']; ?>" class="inputbox validate-numeric" maxlength="6" size="<?php echo $med_field_width; ?>" /> </td>
								</tr>
								<tr>
                                    <td class="key"><?php echo JText::_('JS_MAX_HEIGHT'); ?></td>
                                    <td><input type="text" name="subcategoeis_max_hight" value="<?php echo $this->config['subcategoeis_max_hight']; ?>" class="inputbox validate-numeric" maxlength="6" size="<?php echo $med_field_width; ?>" /> &nbsp;px</td>
                                </tr>
                            </table>
                            </fieldset>
							</div>
							<div id="listresumeoption">
                            <fieldset>
                            <legend><?php echo JText::_('SUB_CATEGORIES'); ?></legend>
                            <table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
                                <tr >
                                    <td class="key" width="25%"><?php echo JText::_('SUB_CATEGORIES'); ?></td>
                                    <td  width="25%">
										<?php echo JHTML::_('select.genericList', $showhide, 'resume_subcategories', 'class="inputbox" '. '', 'value', 'text', $this->config['resume_subcategories']); ?>
										
									</td>
									<td><span><small><?php echo JText::_('JS_SHOW_SUB_CATEGORIES_IN_RESUME_BY_CATEGORIES_LIST');?></small></span></td>
								</tr>
								<tr>
                                    <td  width="25%" class="key"><?php echo JText::_('JS_ALL_SUBCATEGORIES'); ?></td>
                                    <td width="25%">
										<?php echo JHTML::_('select.genericList', $yesno, 'resume_subcategories_all', 'class="inputbox" '. '', 'value', 'text', $this->config['resume_subcategories_all']); ?>
										
									</td>
									<td><span><small><?php echo JText::_('JS_SHOW_ALL_SUB_CATEGORIES_OF_CATEGORY_OR_SHOW_THOSE_WHO_HAD_RESUME');?></small></span></td>
                                </tr>
                                <tr>
                                    <td class="key" ><?php echo JText::_('JS_COLS_PER_ROW'); ?></td>
                                    <td><input type="text" name="resume_subcategories_colsperrow" value="<?php echo $this->config['resume_subcategories_colsperrow']; ?>" class="inputbox validate-numeric" maxlength="6" size="<?php echo $med_field_width; ?>" /> </td>
								</tr>
								<tr>
                                    <td class="key"><?php echo JText::_('JS_MAX_HEIGHT'); ?></td>
                                    <td><input type="text" name="resume_subcategoeis_max_hight" value="<?php echo $this->config['resume_subcategoeis_max_hight']; ?>" class="inputbox validate-numeric" maxlength="6" size="<?php echo $med_field_width; ?>" /> &nbsp;px</td>
                                </tr>
                            </table>
                            </fieldset>
                            
							</div>
							<div id="listjobsoption">
                            <fieldset>
                            <legend><?php echo JText::_('JS_MEMBERS').' / '.JText::_('JS_ACTIVE_MEMBERS'); ?></legend>
                            <table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
                                <tr >
                                    <td class="key" width="25%"><?php echo JText::_('JS_TITLE'); ?></td>
                                    <td  width="25%"><?php echo JHTML::_('select.genericList', $showhide, 'lj_title', 'class="inputbox" '. '', 'value', 'text', $this->config['lj_title']); ?></td>
                                    <td class="key" ><?php echo JText::_('JS_CATEGORY'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'lj_category', 'class="inputbox" '. '', 'value', 'text', $this->config['lj_category']); ?></td>
                                </tr>
                                <tr>
                                    <td class="key" ><?php echo JText::_('JS_JOBTYPE'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'lj_jobtype', 'class="inputbox" '. '', 'value', 'text', $this->config['lj_jobtype']); ?></td>
                                    <td class="key"><?php echo JText::_('JS_JOB_STATUS'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'lj_jobstatus', 'class="inputbox" '. '', 'value', 'text', $this->config['lj_jobstatus']); ?></td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_CITY'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'lj_city', 'class="inputbox" '. '', 'value', 'text', $this->config['lj_city']); ?></td>
                                    <td class="key"><?php echo JText::_('JS_SALARY'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'lj_salary', 'class="inputbox" '. '', 'value', 'text', $this->config['lj_salary']); ?></td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_COMPANY'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'lj_company', 'class="inputbox" '. '', 'value', 'text', $this->config['lj_company']); ?></td>
                                    <td class="key"><?php echo JText::_('JS_NOOF_JOBS'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'lj_noofjobs', 'class="inputbox" '. '', 'value', 'text', $this->config['lj_noofjobs']); ?></td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_COMPANY_SITE'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'lj_companysite', 'class="inputbox" '. '', 'value', 'text', $this->config['lj_companysite']); ?></td>
                                    <td class="key"><?php echo JText::_('JS_CREATED'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'lj_created', 'class="inputbox" '. '', 'value', 'text', $this->config['lj_created']); ?></td>
                                </tr>
                            </table>
                            </fieldset>
                            <fieldset>
                            <legend><?php echo JText::_('JS_VISITORS').' / '.JText::_('JS_EXPIRED_MEMBERS_PACKAGE'); ?></legend>
                            <table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
                                <tr >
                                    <td class="key" width="25%"><?php echo JText::_('JS_TITLE'); ?></td>
                                    <td  width="25%"><?php echo JHTML::_('select.genericList', $showhide, 'visitor_lj_title', 'class="inputbox" '. '', 'value', 'text', $this->config['visitor_lj_title']); ?></td>
                                    <td class="key" ><?php echo JText::_('JS_CATEGORY'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'visitor_lj_category', 'class="inputbox" '. '', 'value', 'text', $this->config['visitor_lj_category']); ?></td>
                                </tr>
                                <tr>
                                    <td class="key" ><?php echo JText::_('JS_JOBTYPE'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'visitor_lj_jobtype', 'class="inputbox" '. '', 'value', 'text', $this->config['visitor_lj_jobtype']); ?></td>
                                    <td class="key"><?php echo JText::_('JS_JOB_STATUS'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'visitor_lj_jobstatus', 'class="inputbox" '. '', 'value', 'text', $this->config['visitor_lj_jobstatus']); ?></td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_CITY'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'visitor_lj_city', 'class="inputbox" '. '', 'value', 'text', $this->config['visitor_lj_city']); ?></td>
                                    <td class="key"><?php echo JText::_('JS_SALARY'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'visitor_lj_salary', 'class="inputbox" '. '', 'value', 'text', $this->config['visitor_lj_salary']); ?></td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_COMPANY'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'visitor_lj_company', 'class="inputbox" '. '', 'value', 'text', $this->config['visitor_lj_company']); ?></td>
                                    <td class="key"><?php echo JText::_('JS_NOOF_JOBS'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'visitor_lj_noofjobs', 'class="inputbox" '. '', 'value', 'text', $this->config['visitor_lj_noofjobs']); ?></td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_COMPANY_SITE'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'visitor_lj_companysite', 'class="inputbox" '. '', 'value', 'text', $this->config['visitor_lj_companysite']); ?></td>
                                    <td class="key"><?php echo JText::_('JS_CREATED'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'visitor_lj_created', 'class="inputbox" '. '', 'value', 'text', $this->config['visitor_lj_created']); ?></td>
                                </tr>
                            </table>
                            </fieldset>
							</div>
							<div id="filter">
                            <fieldset>
                            <legend><?php echo JText::_('JS_FILTER_SETTINGS'); ?></legend>
                            <table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
                                <tr>
                                    <td class="key" width="25%"><?php echo JText::_('JS_FILTER'); ?></td>
                                    <td width="25%"><?php echo JHTML::_('select.genericList', $showhide, 'filter', 'class="inputbox" '. '', 'value', 'text', $this->config['filter']); ?></td>
                                    <td width="25%" class="key"><?php echo JText::_('JS_JOB_TYPE'); ?></td>
                                    <td width="25%"><?php echo JHTML::_('select.genericList', $showhide, 'filter_jobtype', 'class="inputbox" '. '', 'value', 'text', $this->config['filter_jobtype']);; ?></td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_CATEGORY'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'filter_category', 'class="inputbox" '. '', 'value', 'text', $this->config['filter_category']); ?></td>
                                    <td class="key"><?php echo JText::_('JS_SUB_CATEGORY'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'filter_sub_category', 'class="inputbox" '. '', 'value', 'text', $this->config['filter_sub_category']); ?></td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_MAP');?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'filter_map', 'class="inputbox" '. '', 'value', 'text', $this->config['filter_map']);?></td>
                                    <td class="key"><?php echo JText::_('JS_FILTER_ADDRESS_FIELDS_WIDTH'); ?></td>
                                    <td><input type="text" name="filter_address_fields_width" value="<?php echo $this->config['filter_address_fields_width']; ?>" class="inputbox validate-numeric" maxlength="6" size="5" maxlength="5" /> &nbsp;px</td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_FILTER_MAP_FIELDS_WIDTH'); ?></td>
                                    <td><input type="text" name="filter_map_fields_width" value="<?php echo $this->config['filter_map_fields_width']; ?>" class="inputbox validate-numeric" maxlength="6" size="5" maxlength="5" /> &nbsp;px</td>
                                    <td class="key"><?php echo JText::_('JS_FILTER_CAT/JOBTYPE_FIELDS_WIDTH'); ?></td>
                                    <td><input type="text" name="filter_cat_jobtype_fields_width" value="<?php echo $this->config['filter_cat_jobtype_fields_width']; ?>" class="inputbox validate-numeric" maxlength="6" size="5" maxlength="5" /> &nbsp;px</td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_ADDRESS'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'filter_address', 'class="inputbox" '. '', 'value', 'text', $this->config['filter_address']); ?></td>
                                </tr>

                            <?php // sce ?>
                            </table>
                            </fieldset>
							</div>
							<div id="package">
                            <fieldset>
                            <legend><?php echo JText::_('JS_PACKAGE_SETTINGS'); ?></legend>
                            <table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_DEFAULT_EMPLOYER_PACKAGE'); ?></td>
                                    <td><?php echo $this->lists['employer_defaultpackage']; ?></td>
									<td><span><small><?php echo JText::_('JS_AUTO_ASSIGN_PACKAGE_TO');?>&nbsp;<b><?php echo JText::_('JS_NEW_USER');?></b></small></span></td>
								</tr>
								<tr>
                                    <td  class="key"><?php echo JText::_('JS_DEFAULT_JOBSEEKER_PACKAGE'); ?></td>
                                    <td><?php echo $this->lists['jobseeker_defaultpackage']; ?></td>
									<td><span><small><?php echo JText::_('JS_AUTO_ASSIGN_PACKAGE_TO');?>&nbsp;<b><?php echo JText::_('JS_NEW_USER');?></b></small></span></td>
                                </tr>
                                <tr>
                                    <td class="key" width="25%"><?php echo JText::_('JS_ONLY_ONCE_EMPLOYER_GET_FREE_PACKAGE'); ?></td>
                                    <td width="25%"><?php echo JHTML::_('select.genericList', $yesno, 'onlyonce_employer_getfreepackage', 'class="inputbox" '. '', 'value', 'text', $this->config['onlyonce_employer_getfreepackage']); ?></td>
								</tr>
								<tr>
                                    <td width="25%" class="key"><?php echo JText::_('JS_ONLY_ONCE_JOBSEEKER_GET_FREE_PACKAGE'); ?></td>
                                    <td width="25%"><?php echo JHTML::_('select.genericList', $yesno, 'onlyonce_jobseeker_getfreepackage', 'class="inputbox" '. '', 'value', 'text', $this->config['onlyonce_jobseeker_getfreepackage']); ?></td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_EMPLOYER_FREE_PACKAGE_AUTO_APPROVE'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $yesno, 'employer_freepackage_autoapprove', 'class="inputbox" '. '', 'value', 'text', $this->config['employer_freepackage_autoapprove']); ?></td>
								</tr>
								<tr>
                                    <td class="key"><?php echo JText::_('JS_JOBSEEKER_FREE_PACKAGE_AUTO_APPROVE'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $yesno, 'jobseeker_freepackage_autoapprove', 'class="inputbox" '. '', 'value', 'text', $this->config['jobseeker_freepackage_autoapprove']); ?></td>
                                </tr>
                            </table>
                            </fieldset>
							</div>
							<div id="email">
                            <fieldset>
                            <legend><?php echo JText::_('JS_EMAIL_SETTINGS'); ?></legend>
                            <table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
                                <tr >
                                    <td class="key" width="25%"><?php echo JText::_('JS_MAIL_FROM_ADDRESS'); ?></td>
                                    <td width="25%">
										<input type="text" name="mailfromaddress" value="<?php echo $this->config['mailfromaddress']; ?>" class="inputbox validate-email" size="<?php echo $big_field_width;?>"/>
										
									</td>
									<td><small><?php echo JText::_('JS_EMAIL_ADDRESS_USED_TO_SEND_EMAILS');?></small></td>
								</tr>
								<tr>
                                    <td width="25%" class="key"><?php echo JText::_('JS_EMAIL_ADMIN_NEW_RESUME'); ?></td>
                                    <td width="25%">
										<?php echo JHTML::_('select.genericList', $yesno, 'email_admin_new_resume', 'class="inputbox" '. '', 'value', 'text', $this->config['email_admin_new_resume']); ?>
										
									</td>
									<td><small><?php echo JText::_('JS_SEND_EMAIL_TO_ADMIN_WHEN_NEW_RESUME_IS_CREATED');?></small></td>
                                </tr>
                                <tr >
                                    <td class="key"><?php echo JText::_('JS_MAIL_ADMIN_ADDRESS'); ?></td>
                                    <td>
										<input type="text" name="adminemailaddress" value="<?php echo $this->config['adminemailaddress']; ?>" class="inputbox validate-email" size="<?php echo $med_field_width;?>" />
										
									</td>
									<td><small><?php echo JText::_('JS_ADMIN_EMAIL_ADDRESS_FOR_RECEIVE_EMAILS');?></small></td>
								</tr>
								<tr>
                                    <td class="key"><?php echo JText::_('JS_EMAIL_ADMIN_NEW_DEPARTMENT'); ?></td>
                                    <td>
										<?php echo JHTML::_('select.genericList', $yesno, 'email_admin_new_department', 'class="inputbox" '. '', 'value', 'text', $this->config['email_admin_new_department']); ?>
										
									</td>
									<td><small><?php echo JText::_('JS_SEND_EMAIL_TO_ADMIN_WHEN_NEW_DEPARTMENT_IS_CREATED');?></small></td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_MAIL_FROM_NAME'); ?></td>
                                    <td>
										<input type="text" name="mailfromname" value="<?php echo $this->config['mailfromname']; ?>" class="inputbox" size="<?php echo $med_field_width;?>" />
										
									</td>
									<td><small><?php echo JText::_('JS_EMAIL_SENDER_NAME_USED_WHEN_AN_EMAIL_IS_SEND');?></small></td>
								</tr>
								<tr>
                                    <td class="key"><?php echo JText::_('JS_EMAIL_ADMIN_JOB_APPLY'); ?></td>
                                    <td>
										<?php echo JHTML::_('select.genericList', $yesno, 'email_admin_job_apply', 'class="inputbox" '. '', 'value', 'text', $this->config['email_admin_job_apply']); ?>
										
									</td>
									<td><small><?php echo JText::_('JS_EMAIL_SEND_TO_ADMIN_WHEN_A_JOB_IS_APPLIED');?></small></td>
                                </tr>
                                <tr>
                                    <td class="key" ><?php echo JText::_('JS_EMAIL_ADMIN_NEW_COMPANY'); ?></td>
                                    <td>
										<?php echo JHTML::_('select.genericList', $yesno, 'email_admin_new_company', 'class="inputbox" '. '', 'value', 'text', $this->config['email_admin_new_company']); ?>
										
									</td>
									<td><small><?php echo JText::_('JS_EMAIL_SEND_TO_ADMIN_WHEN_NEW_COMPANY_IS_CREATED');?></small></td>
								</tr>
								<tr>
                                    <td class="key" width="30%"><?php echo JText::_('JS_EMAIL_ADMIN_EMPLOYER_PACKAGE_PURCHASE'); ?></td>
                                    <td>
										<?php echo JHTML::_('select.genericList', $yesno, 'email_admin_employer_package_purchase', 'class="inputbox" '. '', 'value', 'text', $this->config['email_admin_employer_package_purchase']); ?>
										
									</td>
									<td><small><?php echo JText::_('JS_EMAIL_SEND_TO_ADMIN_WHEN_EMPLOYER_PURCHASE_PACKAGE');?></small></td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_EMAIL_ADMIN_NEW_JOB'); ?></td>
                                    <td>
										<?php echo JHTML::_('select.genericList', $yesno, 'email_admin_new_job', 'class="inputbox" '. '', 'value', 'text', $this->config['email_admin_new_job']); ?>
										
									</td>
									<td><small><?php echo JText::_('JS_EMAIL_SEND_TO_ADMIN_WHEN_NEW_JOB_IS_POSTED');?></small></td>
								</tr>
								<tr>
                                    <td class="key"><?php echo JText::_('JS_EMAIL_ADMIN_JOBSEEKER_PACKAGE_PURCHASE'); ?></td>
                                    <td>
										<?php echo JHTML::_('select.genericList', $yesno, 'email_admin_jobseeker_package_purchase', 'class="inputbox" '. '', 'value', 'text', $this->config['email_admin_jobseeker_package_purchase']); ?>
										
									</td>
									<td><small><?php echo JText::_('JS_EMAIL_SEND_TO_ADMIN_WHEN_JOBSEEKER_PURCHASE_PACKAGE');?></small></td>
                                </tr>
                            </table>
                            </fieldset>
							</div>
							<div id="userregistration">
                            <fieldset>
                                <legend><?php echo JText::_('JS_USER_REGISTRATION'); ?></legend>
									<table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
										<tr>

											<td class="key" width="25%"><?php echo JText::_('JS_REGISTRATION_FORM_CAPTCHA'); ?></td>
											<td  width="25%"><?php echo JHTML::_('select.genericList', $yesno, 'user_registration_captcha', 'class="inputbox" '. '', 'value', 'text', $this->config['user_registration_captcha']); ?><br clear="all"/>
											<small><?php echo JText::_('JS_SHOW_CAPTCHA_ON_VISITOR_REGISTRATION_FORM'); ?></small></td>
										</tr>
									</table>		
                            </fieldset>
							</div>
							<div id="socialsharing">
                            <fieldset>
                                <legend><?php echo JText::_('JS_JOB_SOCIAL_SHARING'); ?></legend>
									<table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
										<th width="50%"></th>
										<th width="25%"><?php echo JText::_('JS_EMPLOYER') ?></th>
										<th width="25%"><?php echo JText::_('JS_JOBSEEKER') ?></th>
										<tr>
											<td colspan="3"><b><?php echo JText::_('JS_FACEBOOK') ?></b></td>
										</tr>
										<tr>
											<td><?php echo JText::_('JS_LIKES') ?></td>
											<td><input type="checkbox" value="1" name="employer_share_fb_like" id="employer_share_fb_like" <?php if($this->config['employer_share_fb_like'] == 1) echo 'checked="true"';else 'checked="false"'; ?> /></td>
											<td><input type="checkbox" value="1" name="jobseeker_share_fb_like" id="jobseeker_share_fb_like" <?php if($this->config['jobseeker_share_fb_like'] == 1) echo 'checked="true"';else 'checked="false"'; ?> /></td>
										</tr>
										<tr>
											<td><?php echo JText::_('JS_SHARE') ?></td>
											<td><input type="checkbox" value="1" name="employer_share_fb_share" id="employer_share_fb_share" <?php if($this->config['employer_share_fb_share'] == 1) echo 'checked="true"';else 'checked="false"'; ?> /></td>
											<td><input type="checkbox" value="1" name="jobseeker_share_fb_share" id="jobseeker_share_fb_share" <?php if($this->config['jobseeker_share_fb_share'] == 1) echo 'checked="true"';else 'checked="false"'; ?> /></td>
										</tr>
										<tr>
											<td><?php echo JText::_('JS_COMMENTS') ?></td>
											<td><input type="checkbox" value="1" name="employer_share_fb_comments" id="employer_share_fb_comments" <?php if($this->config['employer_share_fb_comments'] == 1) echo 'checked="true"';else 'checked="false"'; ?> /></td>
											<td><input type="checkbox" value="1" name="jobseeker_share_fb_comments" id="jobseeker_share_fb_comments" <?php if($this->config['jobseeker_share_fb_comments'] == 1) echo 'checked="true"';else 'checked="false"'; ?> /></td>
										</tr>
										<tr>
											<td colspan="3"><b><?php echo JText::_('JS_GOOGLE_PLUS') ?></b></td>
										</tr>
										<tr>
											<td><?php echo JText::_('JS_LIKES') ?></td>
											<td><input type="checkbox" value="1" name="employer_share_google_like" id="employer_share_google_like" <?php if($this->config['employer_share_google_like'] == 1) echo 'checked="true"';else 'checked="false"'; ?> /></td>
											<td><input type="checkbox" value="1" name="jobseeker_share_google_like" id="jobseeker_share_google_like" <?php if($this->config['jobseeker_share_google_like'] == 1) echo 'checked="true"';else 'checked="false"'; ?> /></td>
										</tr>
										<tr>
											<td><?php echo JText::_('JS_SHARE') ?></td>
											<td><input type="checkbox" value="1" name="employer_share_google_share" id="employer_share_google_share" <?php if($this->config['employer_share_google_share'] == 1) echo 'checked="true"';else 'checked="false"'; ?> /></td>
											<td><input type="checkbox" value="1" name="jobseeker_share_google_share" id="jobseeker_share_google_share" <?php if($this->config['jobseeker_share_google_share'] == 1) echo 'checked="true"';else 'checked="false"'; ?> /></td>
										</tr>
										<tr>
											<td><?php echo JText::_('JS_BLOGGER_SHARE') ?></td>
											<td><input type="checkbox" value="1" name="employer_share_blog_share" id="employer_share_blog_share" <?php if($this->config['employer_share_blog_share'] == 1) echo 'checked="true"';else 'checked="false"'; ?> /></td>
											<td><input type="checkbox" value="1" name="jobseeker_share_blog_share" id="jobseeker_share_blog_share" <?php if($this->config['jobseeker_share_blog_share'] == 1) echo 'checked="true"';else 'checked="false"'; ?> /></td>
										</tr>
										<tr>
											<td><?php echo JText::_('JS_FRIEND_FEED_SHARE') ?></td>
											<td><input type="checkbox" value="1" name="employer_share_friendfeed_share" id="employer_share_friendfeed_share" <?php if($this->config['employer_share_friendfeed_share'] == 1) echo 'checked="true"';else 'checked="false"'; ?> /></td>
											<td><input type="checkbox" value="1" name="jobseeker_share_friendfeed_share" id="jobseeker_share_friendfeed_share" <?php if($this->config['jobseeker_share_friendfeed_share'] == 1) echo 'checked="true"';else 'checked="false"'; ?> /></td>
										</tr>
										<tr>
											<td><?php echo JText::_('JS_LINKEDIN_SHARE') ?></td>
											<td><input type="checkbox" value="1" name="employer_share_linkedin_share" id="employer_share_linkedin_share" <?php if($this->config['employer_share_linkedin_share'] == 1) echo 'checked="true"';else 'checked="false"'; ?> /></td>
											<td><input type="checkbox" value="1" name="jobseeker_share_linkedin_share" id="jobseeker_share_linkedin_share" <?php if($this->config['jobseeker_share_linkedin_share'] == 1) echo 'checked="true"';else 'checked="false"'; ?> /></td>
										</tr>
										<tr>
											<td><?php echo JText::_('JS_DIGG_SHARE') ?></td>
											<td><input type="checkbox" value="1" name="employer_share_digg_share" id="employer_share_digg_share" <?php if($this->config['employer_share_digg_share'] == 1) echo 'checked="true"';else 'checked="false"'; ?> /></td>
											<td><input type="checkbox" value="1" name="jobseeker_share_digg_share" id="jobseeker_share_digg_share" <?php if($this->config['jobseeker_share_digg_share'] == 1) echo 'checked="true"';else 'checked="false"'; ?> /></td>
										</tr>
										<tr>
											<td><?php echo JText::_('JS_TWITTER_SHARE') ?></td>
											<td><input type="checkbox" value="1" name="employer_share_twitter_share" id="employer_share_twitter_share" <?php if($this->config['employer_share_twitter_share'] == 1) echo 'checked="true"';else 'checked="false"'; ?> /></td>
											<td><input type="checkbox" value="1" name="jobseeker_share_twiiter_share" id="jobseeker_share_twiiter_share" <?php if($this->config['jobseeker_share_twiiter_share'] == 1) echo 'checked="true"';else 'checked="false"'; ?> /></td>
										</tr>
										<tr>
											<td><?php echo JText::_('JS_MYSPACE_SHARE') ?></td>
											<td><input type="checkbox" value="1" name="employer_share_myspace_share" id="employer_share_myspace_share" <?php if($this->config['employer_share_myspace_share'] == 1) echo 'checked="true"';else 'checked="false"'; ?> /></td>
											<td><input type="checkbox" value="1" name="jobseeker_share_myspace_share" id="jobseeker_share_myspace_share" <?php if($this->config['jobseeker_share_myspace_share'] == 1) echo 'checked="true"';else 'checked="false"'; ?> /></td>
										</tr>
										<tr>
											<td><?php echo JText::_('JS_YAHOO_SHARE') ?></td>
											<td><input type="checkbox" value="1" name="employer_share_yahoo_share" id="employer_share_yahoo_share" <?php if($this->config['employer_share_yahoo_share'] == 1) echo 'checked="true"';else 'checked="false"'; ?> /></td>
											<td><input type="checkbox" value="1" name="jobseeker_share_yahoo_share" id="jobseeker_share_yahoo_share" <?php if($this->config['jobseeker_share_yahoo_share'] == 1) echo 'checked="true"';else 'checked="false"'; ?> /></td>
										</tr>
										
									</table>		
                            </fieldset>
							</div>
							<div id="rss">
                            
                            <fieldset>
                                <legend><?php echo JText::_('JS_RSS_JOBS_SETTINGS'); ?></legend>
                            <table id="jobsrss" >
                                <tr>
                                    <td>
                            <strong><?php echo JText::_('JS_MAIN_BLOCK');?></strong>
                            <table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" id="jobsrss">
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_JOBS_RSS'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'job_rss', 'class="inputbox" '. '', 'value', 'text', $this->config['job_rss']);; ?></td>
                                    <td></td><td></td>
                                </tr>
                                <tr>
                                    <td width="25%" class="key"><?php echo JText::_('JS_TITLE'); ?></td>
                                    <td  width="25%"><input type="text" name="rss_job_title" value="<?php echo $this->config['rss_job_title']; ?>" class="inputbox" size="<?php echo $med_field_width; ?>" maxlength="255" /><br clear="all">
                                    <small><?php echo JText::_('JS_MUST_PROVIDE_TITLE_FOR_JOB_FEED');?></small></td>
                                    <td width="25%" class="key"><?php echo JText::_('JS_DESCRIPTION'); ?></td>
                                    <td><textarea name="rss_job_description" cols="25" rows="3" class="inputbox"><?php echo $this->config['rss_job_description']; ?></textarea><br clear="all">
                                    <small><?php echo JText::_('JS_MUST_PROVIDE_DESCRIPTION_FOR_JOB_FEED');?></small></td>
                                </tr>
                                <tr>
                                    <td width="25%" class="key"><?php echo JText::_('JS_COPYRIGHT'); ?></td>
                                    <td  width="25%"><input type="text" name="rss_job_copyright" value="<?php echo $this->config['rss_job_copyright']; ?>" class="inputbox" size="<?php echo $med_field_width; ?>" maxlength="255" /><br clear="all">
                                    <small><?php echo JText::_('JS_LEAVE_BLANK_IF_NOT_SHOW');?></small></td>
                                    <td width="25%" class="key"><?php echo JText::_('JS_WEBMASTER'); ?></td>
                                    <td  width="25%"><input type="text" name="rss_job_webmaster" value="<?php echo $this->config['rss_job_webmaster']; ?>" class="inputbox validate-email" size="<?php echo $med_field_width; ?>" maxlength="255" /><br clear="all">
                                    <small><?php echo JText::_('JS_LEAVE_BLANK_IF_NOT_SHOW_WEBMASTER_USED_FOR_TECHNICAL_ISSUE');?></small></td>
                                </tr>
                                <tr>
                                    <td width="25%" class="key"><?php echo JText::_('JS_EDITOR'); ?></td>
                                    <td  width="25%"><input type="text" name="rss_job_editor" value="<?php echo $this->config['rss_job_editor']; ?>" class="inputbox validate-email" size="<?php echo $med_field_width; ?>" maxlength="255" /><br clear="all">
                                    <small><?php echo JText::_('JS_LEAVE_BLANK_IF_NOT_SHOW_EDITOR_USED_FOR_FEED_CONTENT_ISSUE');?></small></td>
                                    <td width="25%" class="key"><?php echo JText::_('JS_TIME_TO_LIVE'); ?></td>
                                    <td  width="25%"><input type="text" name="rss_job_ttl" value="<?php echo $this->config['rss_job_ttl']; ?>" class="inputbox validate-numeric"  maxlength="6"  size="<?php echo $med_field_width; ?>" maxlength="255" /><br clear="all">
                                    <small><?php echo JText::_('JS_TIME_TO_LIVE_FOR_JOB_FEED');?></small></td>
                                </tr>
                            </table>
                            <strong><?php echo JText::_('JS_JOB_BLOCK');?></strong>
                            <table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
                                <tr>
                                    <td width="25%" class="key"><?php echo JText::_('JS_SHOW_WITH_CATEGORIES'); ?></td>
                                    <td  width="25%"><?php echo JHTML::_('select.genericList', $showhide, 'rss_job_categories', 'class="inputbox" '. '', 'value', 'text', $this->config['rss_job_categories']);?><br clear="all">
                                    <small><?php echo JText::_('JS_USE_RSS_CATEGORIES_WITH_OUR_JOB_CATEGORIES');?></small></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td width="25%" class="key"><?php echo JText::_('JS_COMPANY_IMAGE'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'rss_job_image', 'class="inputbox" '. '', 'value', 'text', $this->config['rss_job_image']);?><br clear="all">
                                    <small><?php echo JText::_('JS_SHOW_COMPANY_LOGO_WITH_JOB_FEEDS');?></small></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </table>
                                    </td>
                                </tr>
                            </table>
                            </fieldset>
                            <fieldset>
                                <legend><?php echo JText::_('JS_RSS_RESUME_SETTINGS'); ?></legend>
                            <table id="resumerss">
                                <tr>
                                    <td>
                            <strong><?php echo JText::_('JS_MAIN_BLOCK');?></strong>
                            <table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" id="jobsrss">
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_RESUME_RSS'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'resume_rss', 'class="inputbox" '. '', 'value', 'text', $this->config['resume_rss']);; ?></td>
                                    <td></td><td></td>
                                </tr>
                                <tr>
                                    <td width="25%" class="key"><?php echo JText::_('JS_TITLE'); ?></td>
                                    <td  width="25%"><input type="text" name="rss_resume_title" value="<?php echo $this->config['rss_resume_title']; ?>" class="inputbox" size="<?php echo $med_field_width; ?>" maxlength="255" /><br clear="all">
                                    <small><?php echo JText::_('JS_MUST_PROVIDE_TITLE_FOR_RESUME_FEED');?></small></td>
                                    <td width="25%" class="key"><?php echo JText::_('JS_DESCRIPTION'); ?></td>
                                    <td><textarea name="rss_resume_description" cols="25" rows="3" class="inputbox"><?php echo $this->config['rss_resume_description']; ?></textarea><br clear="all">
                                    <small><?php echo JText::_('JS_MUST_PROVIDE_DESCRIPTION_FOR_RESUME_FEED');?></small></td>
                                </tr>
                                <tr>
                                    <td width="25%" class="key"><?php echo JText::_('JS_COPYRIGHT'); ?></td>
                                    <td  width="25%"><input type="text" name="rss_resume_copyright" value="<?php echo $this->config['rss_resume_copyright']; ?>" class="inputbox" size="<?php echo $med_field_width; ?>" maxlength="255" /><br clear="all">
                                    <small><?php echo JText::_('JS_LEAVE_BLANK_IF_NOT_SHOW');?></small></td>
                                    <td width="25%" class="key"><?php echo JText::_('JS_WEBMASTER'); ?></td>
                                    <td  width="25%"><input type="text" name="rss_resume_webmaster" value="<?php echo $this->config['rss_resume_webmaster']; ?>" class="inputbox validate-email" size="<?php echo $med_field_width; ?>" maxlength="255" /><br clear="all">
                                    <small><?php echo JText::_('JS_LEAVE_BLANK_IF_NOT_SHOW_WEBMASTER_USED_FOR_TECHNICAL_ISSUE');?></small></td>
                                </tr>
                                <tr>
                                    <td width="25%" class="key"><?php echo JText::_('JS_EDITOR'); ?></td>
                                    <td  width="25%"><input type="text" name="rss_resume_editor" value="<?php echo $this->config['rss_resume_editor']; ?>" class="inputbox validate-email" size="<?php echo $med_field_width; ?>" maxlength="255" /><br clear="all">
                                    <small><?php echo JText::_('JS_LEAVE_BLANK_IF_NOT_SHOW_EDITOR_USED_FOR_FEED_CONTENT_ISSUE');?></small></td>
                                    <td width="25%" class="key"><?php echo JText::_('JS_TIME_TO_LIVE'); ?></td>
                                    <td  width="25%"><input type="text" name="rss_resume_ttl" value="<?php echo $this->config['rss_resume_ttl']; ?>" class="inputbox validate-numeric"  maxlength="6" size="<?php echo $med_field_width; ?>" maxlength="255" /><br clear="all">
                                    <small><?php echo JText::_('JS_TIME_TO_LIVE_FOR_RESUME_FEED');?></small></td>
                                </tr>
                            </table>
                            <strong><?php echo JText::_('JS_RESUME_BLOCK');?></strong>
                            <table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
                                <tr>
                                    <td width="25%" class="key"><?php echo JText::_('JS_SHOW_WITH_CATEGORIES'); ?></td>
                                    <td  width="25%"><?php echo JHTML::_('select.genericList', $showhide, 'rss_resume_categories', 'class="inputbox" '. '', 'value', 'text', $this->config['rss_resume_categories']);?><br clear="all">
                                    <small><?php echo JText::_('JS_USE_RSS_CATEGORIES_WITH_OUR_RESUME_CATEGORIES');?></small></td>
                                    <td width="25%" class="key"><?php echo JText::_('JS_SHOW_RESUME_FILE'); ?></td>
                                    <td  width="25%"><?php echo JHTML::_('select.genericList', $showhide, 'rss_resume_file', 'class="inputbox" '. '', 'value', 'text', $this->config['rss_resume_file']);?><br clear="all">
                                    <small><?php echo JText::_('JS_SHOW_RESUME_FILE_TO_DOWNLOADABLE_FROM_FEED');?></small></td>
                                </tr>
                                <tr>
                                    <td width="25%" class="key"><?php echo JText::_('JS_RESUME_IMAGE'); ?></td>
                                    <td  width="25%"><?php echo JHTML::_('select.genericList', $showhide, 'rss_resume_image', 'class="inputbox" '. '', 'value', 'text', $this->config['rss_resume_image']);?><br clear="all">
                                    <small><?php echo JText::_('JS_SHOW_RESUME_IMAGE_WITH_JOB_FEEDS');?></small></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </table>
                                    </td>
                                </tr>
                            </table>
                            </fieldset>
							</div>
							<div id="googlemapadsense">
                            <fieldset>
                            <legend><?php echo JText::_('JS_MAP'); ?></legend>
                            <table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
								<tr>
                                    <td  class="key" width="25%"><?php echo JText::_('JS_MAP_HEIGHT'); ?></td>
                                    <td ><input class="inputbox validate-numeric"  maxlength="6" type="text" id="mapheight" name="mapheight" value="<?php echo $this->config['mapheight'];?>"/>px</td>
                                    <td  class="key" width="25%"><?php echo JText::_('JS_MAP_WIDTH'); ?></td>
                                    <td ><input class="inputbox validate-numeric" maxlength="6" type="text" id="mapwidth" name="mapwidth" value="<?php echo $this->config['mapwidth'];?>"/>px</td>
								</tr>
                                <tr>
                                    <td width="20%" valign="top"><?php echo JText::_('JS_DEFAULT_COORDINATES');?></td>
                                    <td width="30%">
										<a href="Javascript: showdiv();loadMap();"><?php echo JText::_('JS_SHOW_MAP');?></a>
										<br clear="all"/><input type="text" id="default_longitude" name="default_longitude" value="<?php echo $this->config['default_longitude'];?>"/>&nbsp;<?php echo JText::_('JS_LONGITUDE');?>
										<br clear="all"/><input type="text" id="default_latitude" name="default_latitude" value="<?php echo $this->config['default_latitude'];?>"/>&nbsp;<?php echo JText::_('JS_LATITTUDE');?>
										<br clear="all"/><?php echo JHTML::_('select.genericList', $defaultradius, 'defaultradius', 'class="inputbox" '. '', 'value', 'text', $this->config['defaultradius']); ?><br clear="all"/><?php echo JText::_('JS_DEFAULT_MAP_RADIUS_TYPE');?>
                                    </td>
								</tr>
                                <tr height="15"></tr>
                            </table>
                            </fieldset>
								<div id="map" style="width:<?php echo $this->config['mapwidth'];?>px; height:<?php echo $this->config['mapheight'];?>px"><div id="closetag"><a href="Javascript: hidediv();"><?php echo JText::_('X');?></a></div><div id="map_container"></div></div>
                            <fieldset>
                            <legend><?php echo JText::_('JS_GOOGLE_ADSENSE_SETTINGS'); ?></legend>
                            <table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
                                <tr>
                                    <td width="25%" class="key"><?php echo JText::_('JS_SHOW_GOOGLE_ADDS_IN_NEWEST_JOBS'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'googleadsenseshowinnewestjobs', 'class="inputbox" '. '', 'value', 'text', $this->config['googleadsenseshowinnewestjobs']);; ?></td>
                                    <td width="25%" class="key" valign="top"><?php echo JText::_('JS_SHOW_GOOGLE_ADDS_IN_LIST_JOBS'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'googleadsenseshowinlistjobs', 'class="inputbox" '. '', 'value', 'text', $this->config['googleadsenseshowinlistjobs']);; ?>
                                    <br clear="both"/><small><?php echo JText::_('JS_SHOW_GOOGLE_ADDS_IN_JOBS_BY_CATEGORY_AND_SUBCATEGORY');?></small></td>
                                </tr>
                                <tr >
                                    <td class="key"><?php echo JText::_('JS_GOOGLE_ADSENSE_CLIENT_ID'); ?></td>
                                    <td><input type="text" name="googleadsenseclient" value="<?php echo $this->config['googleadsenseclient']; ?>" class="inputbox" size="<?php echo $med_field_width; ?>" maxlength="255" /></td>
                                    <td class="key"><?php echo JText::_('JS_GOOGLE_ADSENSE_SLOT_ID'); ?></td>
                                    <td><input type="text" name="googleadsenseslot" value="<?php echo $this->config['googleadsenseslot']; ?>" class="inputbox" size="<?php echo $med_field_width; ?>" maxlength="255" /></td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_GOOGLE_ADDS_CUSTOM_CSS'); ?></td>
                                    <td><textarea name="googleadsensecustomcss" cols="25" rows="3" class="inputbox"><?php echo $this->config['googleadsensecustomcss']; ?></textarea></td>
                                    <td class="key"><?php echo JText::_('JS_GOOGLE_ADDS_SHOW_AFTER_NUMBER_OF_JOBS'); ?></td>
                                    <td><input type="text" name="googleadsenseshowafter" value="<?php echo $this->config['googleadsenseshowafter']; ?>" class="inputbox validate-numeric" maxlength="6" size="<?php echo $small_field_width; ?>" maxlength="255" /></td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_GOOGLE_ADDS_WIDTH'); ?></td>
                                    <td><input type="text" name="googleadsensewidth" value="<?php echo $this->config['googleadsensewidth']; ?>" class="inputbox validate-numeric" maxlength="6" size="<?php echo $small_field_width; ?>" maxlength="255" /></td>
                                    <td class="key"><?php echo JText::_('JS_GOOGLE_ADDS_HEIGHT'); ?></td>
                                    <td><input type="text" name="googleadsenseheight" value="<?php echo $this->config['googleadsenseheight']; ?>" class="inputbox validate-numeric" maxlength="6" size="<?php echo $small_field_width; ?>" maxlength="255" /></td>
                                </tr>
                            </table>
                            </fieldset>
							</div>
							<?php  if($this->isjobsharing) {?> 
								<div id="jobsharing">
									<fieldset>
										<legend><?php echo JText::_('JS_JOB_SHARING_DEFAULT_LOCATION'); ?></legend>
										<table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
											<tr>
												<td  class="key" width="25%"><?php echo JText::_('JS_COUNTRY'); ?></td>
												<td >
													<?php if((isset($this->lists['defaultsharingcountry'])) && ($this->lists['defaultsharingcountry']!=''))
														echo $this->lists['defaultsharingcountry']; 
													?>												
												</td>
											</tr>
											<tr>
												<td  class="key" width="25%"><?php echo JText::_('JS_STATE'); ?></td>
												<td id='defaultsharingstate'>
													<?php if((isset($this->lists['defaultsharingstate'])) && ($this->lists['defaultsharingstate']!='')){
														echo $this->lists['defaultsharingstate']; 
													} else{ ?>
														<input class="inputbox" type="text" name="default_sharing_state" id="default_sharing_state" size="40" maxlength="100" value="" />
													<?php }?>												
												</td>
											</tr>
											<tr>
												<td  class="key" width="25%"><?php echo JText::_('JS_CITY'); ?></td>
												<td id='defaultsharingcity'>
													<?php if((isset($this->lists['defaultsharingcity'])) && ($this->lists['defaultsharingcity']!='')){
														echo $this->lists['defaultsharingcity']; 
													} else{ ?>
														<input class="inputbox" type="text" name="default_sharing_city" id="default_sharing_city" size="40" maxlength="100" value="" />
													<?php }?>												
												</td>
											</tr>
											<tr height="15"></tr>
										</table>
									</fieldset>
								</div>
							<?php } ?>	
						</div>
                                    <input type="hidden" name="task" value="saveconf" />
                                    <input type="hidden" name="view" value="application" />
                                    <input type="hidden" name="layout" value="conf" />
                                    <input type="hidden" name="option" value="<?php echo $this->option; ?>" />


                        </form>
			
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left"  valign="top">
			
		</td>
	</tr>
	
</table>
<script language=Javascript>
	
			function dochange(src, val){
				if(src=='defaultsharingstate') var countryid=val;
				else if(src=='defaultsharingcity') var stateid=val;
				document.getElementById(src).innerHTML="Loading ...";
				var xhr; 
				try {  xhr = new ActiveXObject('Msxml2.XMLHTTP');   }
				catch (e) 
				{
					try {   xhr = new ActiveXObject('Microsoft.XMLHTTP');    }
					catch (e2) 
					{
					  try {  xhr = new XMLHttpRequest();     }
					  catch (e3) {  xhr = false;   }
					}
				 }

				xhr.onreadystatechange = function(){
			      if(xhr.readyState == 4 && xhr.status == 200){
			        	if(xhr.responseText==""){
							return_value ="<input class='inputbox' type='text' name='default_sharing_state' id='default_sharing_state' readonly='readonly' size='40' maxlength='100'  />" ;
							document.getElementById(src).innerHTML=return_value; //retuen value
							getcountrycity(val);
						}else{
							document.getElementById(src).innerHTML=xhr.responseText; //retuen value
							if(src=='defaultsharingstate'){
								cityhtml = "<input class='inputbox' type='text' name='default_sharing_city' readonly='readonly' size='40' maxlength='100'  />";
								document.getElementById('defaultsharingcity').innerHTML=cityhtml; //retuen value
							}	
						}
							
			      }
			    }
			 
				xhr.open("GET","index.php?option=com_jsjobs&task=defaultaddressdatajobsharing&data="+src+"&val="+val,true);
				xhr.send(null);
			}
	function getcountrycity(countryid){
			var src='defaultsharingcity'; 
			document.getElementById(src).innerHTML="Loading ...";
			var xhr; 
			try {  xhr = new ActiveXObject('Msxml2.XMLHTTP');   }
			catch (e) 
			{
				try {   xhr = new ActiveXObject('Microsoft.XMLHTTP');    }
				catch (e2) 
				{
				  try {  xhr = new XMLHttpRequest();     }
				  catch (e3) {  xhr = false;   }
				}
			 }
			xhr.onreadystatechange = function(){
			      if(xhr.readyState == 4 && xhr.status == 200){
							document.getElementById(src).innerHTML=xhr.responseText; //retuen value
				  }
				
			}
			xhr.open("GET","index.php?option=com_jsjobs&task=defaultaddressdatajobsharing&data="+src+"&state=-1&val="+countryid,true);
			xhr.send(null);
		
	}
	
	


    function hideshowtables(table_id){
        var obj = document.getElementById(table_id);
        var bool = obj.style.display;
        if(bool == '')
            obj.style.display = "none";
        else
            obj.style.display = "";
		//document.getElementById(table_id) = obj;
    }

</script>
<style type="text/css">
div#map_container{
	z-index:1000;
	position:relative;
	background:#000;
	width:100%;
	height:100%;
/*	opacity:0.55;
	-moz-opacity:0.45;
	filter:alpha(opacity=45);*/
}
div#map{
	visibility:hidden;
	position:absolute;
	width:73%;
	height:48%;
	top:282px;
}
div#closetag a{
	color:red;
}
div#closetag a{
	padding:2px 6px;
	background:red;
	color:white;
	float:right;
	text-decoration:none;
}
div#closetag a:hover{
	padding:2px 6px;
	background:red;
	color:ghostwhite;
	float:right;
	text-decoration:none;
}
</style>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
  function loadMap() {
		var default_latitude = document.getElementById('default_latitude').value;
		var default_longitude = document.getElementById('default_longitude').value;
		var latlng = new google.maps.LatLng(default_latitude, default_longitude); zoom=10;
		var myOptions = {
		  zoom: zoom,
		  center: latlng,
		  mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		var map = new google.maps.Map(document.getElementById("map_container"),myOptions);
		var lastmarker = new google.maps.Marker({
			postiion:latlng,
			map:map,
		});
		var marker = new google.maps.Marker({
		  position: latlng, 
		  map: map, 
		});
		marker.setMap(map);
		lastmarker = marker;

	google.maps.event.addListener(map,"click", function(e){
		var latLng = new google.maps.LatLng(e.latLng.lat(),e.latLng.lng());
		geocoder = new google.maps.Geocoder();
		geocoder.geocode( { 'latLng': latLng}, function(results, status) {

		  if (status == google.maps.GeocoderStatus.OK) {
			if(lastmarker != '') lastmarker.setMap(null);
			var marker = new google.maps.Marker({
				position: results[0].geometry.location, 
				map: map, 
			});
			marker.setMap(map);
			lastmarker = marker;
			document.getElementById('default_latitude').value = marker.position.lat();
			document.getElementById('default_longitude').value = marker.position.lng();
			
		  } else {
			alert("Geocode was not successful for the following reason: " + status);
		  }
		});
	});
//document.getElementById('map_container').innerHTML += "<a href='Javascript hidediv();'><?php echo JText::_('JS_CLOSE_MAP');?></a>";
}
function showdiv(){
	document.getElementById('map').style.visibility = 'visible';
}
function hidediv(){
	document.getElementById('map').style.visibility = 'hidden';
}
</script>
