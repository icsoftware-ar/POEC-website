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

$document =& JFactory::getDocument();
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

$document->addScript('components/com_jsjobs/include/js/jquery_idTabs.js');
$document->addStyleSheet(JURI::root().'administrator/components/com_jsjobs/include/css/jsjobsadmin.css');

 global $mainframe;

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
	'1' => array('value' => 'violet/css/jsjobsviolet.css','text' => JText::_('JS_VIOLET_THEME')),
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
$resumealert=array(
'0'=>array('value'=>'','text'=>JText::_('JS_SELECT_OPTION')),
'1'=>array('value'=>1,'text'=>JText::_('JS_SHOW_ALL_FIELDS')),
'2'=>array('value'=>2,'text'=>JText::_('JS_SHOW_ONLY_FILL_FIELDS')),
);


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
<div id="jsjobs_info_heading"><?php echo JText::_('JS_EMPLOYER_CONFIGURATION'); ?></div>	

                    <form action="index.php" method="POST" name="adminForm" id="adminForm">
						<input type="hidden" name="check" value="post"/>
						
					<div id="tabs_wrapper" class="tabs_wrapper">
						<div class="idTabs">
							<span><a class="selected" href="#emp_generalsetting"><?php echo JText::_('JS_GENERAL_SETTINGS');?></a></span> 
							<span><a  href="#emp_visitor"><?php echo JText::_('JS_VISITORS');?></a></span> 
							<span><a  href="#emp_listresume"><?php echo JText::_('JS_LIST_RESUME');?></a></span> 
							<span><a  href="#emp_company"><?php echo JText::_('JS_COMPANY');?></a></span> 
							<span><a  href="#emp_memberlinks"><?php echo JText::_('JS_MEMBERS_LINKS');?></a></span> 
							<span><a  href="#emp_visitorlinks"><?php echo JText::_('JS_VISITOR_LINKS');?></a></span> 
							<span><a  href="#ema1l"><?php echo JText::_('JS_EMAIL');?></a></span> 
						</div>
						<div id="emp_generalsetting">
                            <fieldset>
                            <legend><?php echo JText::_('JS_GENERAL_SETTINGS'); ?></legend>
                            <table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
				<tr>
						<td  class="key" width="25%"><?php echo JText::_('JS_PACKAGE_REQUIRED_FOR_EMPLOYER'); ?></td>
						<td ><?php echo JHTML::_('select.genericList', $yesno, 'newlisting_requiredpackage', 'class="inputbox" '. '', 'value', 'text', $this->config['newlisting_requiredpackage']);; ?></td>
						<td  class="key" width="25%"></td>
						<td></td>
				</tr>
								
                                <tr >
                                    <td  width="25%" class="key"><?php echo JText::_('JS_EMPLOYER_ALLOW'); ?></td>
                                    <td width="25%"><?php echo JHTML::_('select.genericList', $yesno, 'showemployerlink', 'class="inputbox" '. '', 'value', 'text', $this->config['showemployerlink']); ?></td>
                                    <td class="key"><?php echo JText::_('JS_EMPLOYER_CAN_VIEW_JOB_SEEKER_AREA'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $yesno, 'employerview_js_controlpanel', 'class="inputbox" '. '', 'value', 'text', $this->config['employerview_js_controlpanel']); ?> </td>
                                </tr>
                                <tr>
                                    <td  class="key"><?php echo JText::_('JS_COMPANY_AUTO_APPROVE'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $yesno, 'companyautoapprove', 'class="inputbox" '. '', 'value', 'text', $this->config['companyautoapprove']); ?></td>
                                    <td  class="key"><?php echo JText::_('JS_JOB_AUTO_APPROVE'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $yesno, 'jobautoapprove', 'class="inputbox" '. '', 'value', 'text', $this->config['jobautoapprove']); ?></td>
                                </tr>
                                <tr>
                                    <td class="key" ><?php echo JText::_('JS_DEPARTMENT_AUTO_APPROVE'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $yesno, 'department_auto_approve', 'class="inputbox" '. '', 'value', 'text', $this->config['department_auto_approve']); ?> </td>
                                    <td class="key" ><?php echo JText::_('JS_FOLDER_AUTO_APPROVE'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $yesno, 'folder_auto_approve', 'class="inputbox" '. '', 'value', 'text', $this->config['folder_auto_approve']); ?> </td>
                                </tr>
                                <tr >
                                    <td  class="key"><?php echo JText::_('JS_COMPANY_LOGO_MAXIMUM_SIZE'); ?></td>
                                    <td><input type="text" name="company_logofilezize" value="<?php echo $this->config['company_logofilezize']; ?>" class="inputbox validate-numeric" maxlength="6" size="<?php echo $med_field_width; ?>" /> &nbsp;KB</td>
                                </tr>
                            </table>
                            </fieldset>
						</div>
						<div id="emp_visitor">
                            <table  class="adminform">
                                <tr><td allign="center"><strong> <?php echo JText::_('JS_JOB_POSTING_OPTIONS'); ?></strong>
                                </td></tr>
                            </table>
                            <fieldset>
                            <table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
                                <tr >
                                    <td class="key" width="25%"><?php echo JText::_('JS_POST_JOB'); ?></td>
                                    <td  width="25%"><?php echo JHTML::_('select.genericList', $yesno, 'visitor_can_post_job', 'class="inputbox" '. '', 'value', 'text', $this->config['visitor_can_post_job']); ?><br clear="all"/>
                                    <small><?php echo JText::_('JS_VISITOR_CAN_POST_THIER_JOB'); ?></small></td>
                                    <td  width="25%" class="key"><?php echo JText::_('JS_EDIT_JOB'); ?></td>
                                    <td width="25%"><?php echo JHTML::_('select.genericList', $yesno, 'visitor_can_edit_job', 'class="inputbox" '. '', 'value', 'text', $this->config['visitor_can_edit_job']); ?><br clear="all"/>
                                    <small><?php echo JText::_('JS_VISITOR_CAN_EDIT_THIER_POSTED_JOB'); ?></small></td>
                                </tr>
                                <tr>
                                    <td class="key" width="25%"><?php echo JText::_('JS_FORM_CAPTCHA'); ?></td>
                                    <td  width="25%"><?php echo JHTML::_('select.genericList', $yesno, 'job_captcha', 'class="inputbox" '. '', 'value', 'text', $this->config['job_captcha']); ?><br clear="all"/>
                                    <small><?php echo JText::_('JS_SHOW_CAPTCHA_ON_VISITOR_FORM_JOB'); ?></small></td>
                                </tr>
                            </table>
                            </fieldset>
                            <table  class="adminform">
                                <tr><td align="center"><strong> <?php echo JText::_('JS_VISITORS_CAN_VIEW'); ?></strong>
                                </td></tr>
                            </table>
                            <fieldset>
                            <legend><?php echo JText::_('JS_EMPLOYER'); ?></legend>
                            <table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
                                <tr >
                                    <td class="key" width="25%"><?php echo JText::_('JS_CONTROL_PANEL'); ?></td>
                                    <td  width="25%"><?php echo JHTML::_('select.genericList', $showhide, 'visitorview_emp_conrolpanel', 'class="inputbox" '. '', 'value', 'text', $this->config['visitorview_emp_conrolpanel']); ?></td>
                                    <td  width="25%" class="key"><?php echo JText::_('JS_PACKAGES'); ?></td>
                                    <td width="25%"><?php echo JHTML::_('select.genericList', $showhide, 'visitorview_emp_packages', 'class="inputbox" '. '', 'value', 'text', $this->config['visitorview_emp_packages']); ?></td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_PACKAGE_DETAIL'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'visitorview_emp_viewpackage', 'class="inputbox" '. '', 'value', 'text', $this->config['visitorview_emp_viewpackage']); ?></td>
                                    <td class="key"></td>
                                    <td></td>
                                </tr>
                            </table>
                            </fieldset>
						</div>
						<div id="emp_listresume">
                            <fieldset>
                            <legend><?php echo JText::_('JS_SEARCH_RESUME_SETTINGS'); ?></legend>
                            <table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
                                <tr>
                                    <td class="key" width="25%"><?php echo JText::_('JS_ALLOW_SAVE_SEARCH'); ?></td>
                                    <td width="25%"><?php echo JHTML::_('select.genericList', $yesno, 'search_resume_showsave', 'class="inputbox" '. '', 'value', 'text', $this->config['search_resume_showsave']); ?></td>
                                    <td width="25%" class="key"><?php echo JText::_('JS_SALARY_RANGE'); ?></td>
                                    <td width="25%"><?php echo JHTML::_('select.genericList', $showhide, 'search_resume_salaryrange', 'class="inputbox" '. '', 'value', 'text', $this->config['search_resume_salaryrange']); ?></td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_TITLE'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'search_resume_title', 'class="inputbox" '. '', 'value', 'text', $this->config['search_resume_title']); ?></td>
                                    <td class="key"><?php echo JText::_('JS_GENDER'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'search_resume_gender', 'class="inputbox" '. '', 'value', 'text', $this->config['search_resume_gender']); ?></td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_NAME'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'search_resume_name', 'class="inputbox" '. '', 'value', 'text', $this->config['search_resume_name']); ?></td>
                                    <td class="key"><?php echo JText::_('JS_TYPE'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'search_resume_type', 'class="inputbox" '. '', 'value', 'text', $this->config['search_resume_type']); ?></td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_CATEGORY'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'search_resume_category', 'class="inputbox" '. '', 'value', 'text', $this->config['search_resume_category']); ?></td>
                                    <td class="key"><?php echo JText::_('JS_AVAILABLE'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'search_resume_available', 'class="inputbox" '. '', 'value', 'text', $this->config['search_resume_available']); ?></td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_EXPERIENCE'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'search_resume_experience', 'class="inputbox" '. '', 'value', 'text', $this->config['search_resume_experience']); ?></td>
                                    <td class="key"><?php echo JText::_('JS_NATIONALITY'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'search_resume_nationality', 'class="inputbox" '. '', 'value', 'text', $this->config['search_resume_nationality']); ?></td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_HEIGHEST_EDUCATION'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'search_resume_heighesteducation', 'class="inputbox" '. '', 'value', 'text', $this->config['search_resume_heighesteducation']); ?></td>
                                    <td class="key"><?php echo JText::_('JS_SUB_CATEGORY'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'search_resume_subcategory', 'class="inputbox" '. '', 'value', 'text', $this->config['search_resume_subcategory']); ?></td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_KEYWORDS'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'search_resume_keywords', 'class="inputbox" '. '', 'value', 'text', $this->config['search_resume_keywords']); ?></td>
                                    <td class="key"><?php echo JText::_('JS_ZIPCODE'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'search_resume_zipcode', 'class="inputbox" '. '', 'value', 'text', $this->config['search_resume_zipcode']); ?></td>
                                </tr>
                            </table>
                            </fieldset>
						</div>
						<div id="emp_company">
                            <fieldset>
                            <legend><?php echo JText::_('JS_COMPANY_SETTINGS'); ?></legend>
                            <table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
                                 <tr>
                                    <td class="key"><?php echo JText::_('JS_COMPANY_NAME'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'comp_name', 'class="inputbox" '. '', 'value', 'text', $this->config['comp_name']); ?></td>
                                    <td class="key"><?php echo JText::_('JS_COMPANY_EMAIL_ADDRESS'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'comp_email_address', 'class="inputbox" '. '', 'value', 'text', $this->config['comp_email_address']); ?></td>
                                 </tr>
                                <tr>
                                    <td width="25%" class="key"><?php echo JText::_('JS_CITY'); ?></td>
                                    <td width="25%"><?php echo JHTML::_('select.genericList', $showhide, 'comp_city', 'class="inputbox" '. '', 'value', 'text', $this->config['comp_city']); ?></td>
                                    <td class="key"><?php echo JText::_('JS_COMPANY_URL'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'comp_show_url', 'class="inputbox" '. '', 'value', 'text', $this->config['comp_show_url']); ?></td>
                                </tr>
                                <tr >
                                    <td class="key"><?php echo JText::_('JS_ZIP_CODE'); ?></td>
                                    <td><?php echo JHTML::_('select.genericList', $showhide, 'comp_zipcode', 'class="inputbox" '. '', 'value', 'text', $this->config['comp_zipcode']); ?></td>
                                </tr>
                            </table>
                            </fieldset>
						</div>
						<div id="emp_memberlinks">
                            <fieldset>
                            <legend><?php echo JText::_('JS_EMPLOYER_TOP_LINKS') ?></legend>
                            <table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
                                <tr >
                                    <td class="key" width="25%"><?php echo JText::_('JS_CONTROL_PANEL'); ?></td>
                                    <td  width="25%"><?php echo JHTML::_('select.genericList', $showhide, 'tmenu_emcontrolpanel', 'class="inputbox" '. '', 'value', 'text', $this->config['tmenu_emcontrolpanel']);; ?></td>
                                    <td  width="25%" class="key"><?php echo JText::_('JS_NEW_JOB'); ?></td>
                                    <td  width="25%"><?php echo JHTML::_('select.genericList', $showhide, 'tmenu_emnewjob', 'class="inputbox" '. '', 'value', 'text', $this->config['tmenu_emnewjob']);; ?></td>
                                </tr>
                                <tr>
                                    <td class="key" ><?php echo JText::_('JS_MY_JOBS'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'tmenu_emmyjobs', 'class="inputbox" '. '', 'value', 'text', $this->config['tmenu_emmyjobs']);; ?></td>
                                    <td class="key"><?php echo JText::_('JS_MY_COMPANIES'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'tmenu_emmycompanies', 'class="inputbox" '. '', 'value', 'text', $this->config['tmenu_emmycompanies']);; ?></td>
                                </tr>
                                <tr>
                                    <td class="key" ><?php echo JText::_('JS_APPLIED_RESUME'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'tmenu_emappliedresume', 'class="inputbox" '. '', 'value', 'text', $this->config['tmenu_emappliedresume']);; ?></td>
                                    <td class="key" width="25%"><?php echo JText::_('JS_NEW_COMPANY'); ?></td>
                                    <td  width="25%"><?php echo JHTML::_('select.genericList', $showhide, 'tmenu_emnewcompany', 'class="inputbox" '. '', 'value', 'text', $this->config['tmenu_emnewcompany']);; ?></td>
                                </tr>
                                <tr>
                                    <td class="key" ><?php echo JText::_('JS_NEW_DEPARTMENT'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'tmenu_emnewdepartment', 'class="inputbox" '. '', 'value', 'text', $this->config['tmenu_emnewdepartment']);; ?></td>
                                    <td class="key" width="25%"><?php echo JText::_('JS_NEW_FOLDER'); ?></td>
                                    <td  width="25%"><?php echo JHTML::_('select.genericList', $showhide, 'tmenu_emnewfolder', 'class="inputbox" '. '', 'value', 'text', $this->config['tmenu_emnewfolder']);; ?></td>
                                </tr>
                            </table>
                            </fieldset>
                            <fieldset>
                            <legend><?php echo JText::_('JS_EMPLOYER_CONTROL_PANEL_LINKS') ?></legend>
                            <table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
                                <tr >
                                    <td class="key" width="25%"><?php echo JText::_('JS_NEW_COMPANY'); ?></td>
                                    <td  width="25%"><?php echo JHTML::_('select.genericList', $showhide, 'formcompany', 'class="inputbox" '. '', 'value', 'text', $this->config['formcompany']);; ?></td>
                                    <td  width="25%" class="key"><?php echo JText::_('JS_APPLIED_RESUME'); ?></td>
                                    <td  width="25%"><?php echo JHTML::_('select.genericList', $showhide, 'alljobsappliedapplications', 'class="inputbox" '. '', 'value', 'text', $this->config['alljobsappliedapplications']);; ?></td>
                                </tr>
                                <tr>
                                    <td class="key" ><?php echo JText::_('JS_MY_COMPANIES'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'mycompanies', 'class="inputbox" '. '', 'value', 'text', $this->config['mycompanies']);; ?></td>
                                    <td class="key"><?php echo JText::_('JS_SEARCH_RESUME'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'resumesearch', 'class="inputbox" '. '', 'value', 'text', $this->config['resumesearch']);; ?></td>
                                </tr>
                                <tr>
                                    <td class="key" ><?php echo JText::_('JS_NEW_JOB'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'formjob', 'class="inputbox" '. '', 'value', 'text', $this->config['formjob']);; ?></td>
                                    <td class="key"><?php echo JText::_('JS_RESUME_SAVE_SEARCHES'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'my_resumesearches', 'class="inputbox" '. '', 'value', 'text', $this->config['my_resumesearches']);; ?></td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_MY_JOBS'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'myjobs', 'class="inputbox" '. '', 'value', 'text', $this->config['myjobs']);; ?></td>
                                    <td class="key"><?php echo JText::_('JS_RESUME_BY_CATEGORY'); ?></td>
                                    <td ><?php echo JText::_('JS_IF_RESUME_SEARCH_IS_ALLOWED_THEN_ITS_ALLOWED_ALSO');
												//JHTML::_('select.genericList', $showhide, 'emresumebycategory', 'class="inputbox" '. '', 'value', 'text', $this->config['emresumebycategory']);; ?></td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_NEW_DEPARTMENT'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'formdepartment', 'class="inputbox" '. '', 'value', 'text', $this->config['formdepartment']);; ?></td>
                                    <td class="key"><?php echo JText::_('JS_PACKAGES'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'packages', 'class="inputbox" '. '', 'value', 'text', $this->config['packages']);; ?></td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_MY_DEPARTMENTS'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'mydepartment', 'class="inputbox" '. '', 'value', 'text', $this->config['mydepartment']);; ?></td>
                                    <td class="key"><?php echo JText::_('JS_PURCHASE_HISTORY'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'purchasehistory', 'class="inputbox" '. '', 'value', 'text', $this->config['purchasehistory']);; ?></td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_MESSAGES'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'empmessages', 'class="inputbox" '. '', 'value', 'text', $this->config['empmessages']);; ?></td>
                                    <td class="key"><?php echo JText::_('JS_MY_STATS'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'my_stats', 'class="inputbox" '. '', 'value', 'text', $this->config['my_stats']);; ?></td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_RESUME_RSS'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'empresume_rss', 'class="inputbox" '. '', 'value', 'text', $this->config['empresume_rss']);; ?></td>
                                    <td class="key"><?php echo JText::_('JS_NEW_FOLDER'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'newfolders', 'class="inputbox" '. '', 'value', 'text', $this->config['newfolders']);; ?></td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_FOLDERS'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'myfolders', 'class="inputbox" '. '', 'value', 'text', $this->config['myfolders']);; ?></td>
                                    <td class="key"><?php echo JText::_('JS_EXPIRE_PACKAGE_MESSAGE'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'empexpire_package_message', 'class="inputbox" '. '', 'value', 'text', $this->config['empexpire_package_message']);; ?></td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_EMPLOYER_REGISTRATION'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'empregister', 'class="inputbox" '. '', 'value', 'text', $this->config['empregister']);; ?></td>
                                </tr>
                            </table>
                            </fieldset>
						</div>
						<div id="emp_visitorlinks">
                            <fieldset>
                            <legend><?php echo JText::_('JS_EMPLOYER_TOP_LINKS') ?></legend>
                            <table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
                                <tr >
                                    <td class="key" width="25%"><?php echo JText::_('JS_CONTROL_PANEL'); ?></td>
                                    <td  width="25%"><?php echo JHTML::_('select.genericList', $showhide, 'tmenu_vis_emcontrolpanel', 'class="inputbox" '. '', 'value', 'text', $this->config['tmenu_vis_emcontrolpanel']);; ?></td>
                                    <td  width="25%" class="key"><?php echo JText::_('JS_NEW_JOB'); ?></td>
                                    <td  width="25%"><?php echo JHTML::_('select.genericList', $showhide, 'tmenu_vis_emnewjob', 'class="inputbox" '. '', 'value', 'text', $this->config['tmenu_vis_emnewjob']);; ?></td>
                                </tr>
                                <tr>
                                    <td class="key" ><?php echo JText::_('JS_MY_JOBS'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'tmenu_vis_emmyjobs', 'class="inputbox" '. '', 'value', 'text', $this->config['tmenu_vis_emmyjobs']);; ?></td>
                                    <td class="key"><?php echo JText::_('JS_MY_COMPANIES'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'tmenu_vis_emmycompanies', 'class="inputbox" '. '', 'value', 'text', $this->config['tmenu_vis_emmycompanies']);; ?></td>
                                </tr>
                                <tr>
                                    <td class="key" ><?php echo JText::_('JS_APPLIED_RESUME'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'tmenu_vis_emappliedresume', 'class="inputbox" '. '', 'value', 'text', $this->config['tmenu_vis_emappliedresume']);; ?></td>
                                </tr>
                            </table>
                            </fieldset>
                            <fieldset>
                            <legend><?php echo JText::_('JS_EMPLOYER_CONTROL_PANEL_LINKS') ?></legend>
                            <table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
                                <tr >
                                    <td class="key" width="25%"><?php echo JText::_('JS_NEW_COMPANY'); ?></td>
                                    <td  width="25%"><?php echo JHTML::_('select.genericList', $showhide, 'vis_emformcompany', 'class="inputbox" '. '', 'value', 'text', $this->config['vis_emformcompany']);; ?></td>
                                    <td  width="25%" class="key"><?php echo JText::_('JS_APPLIED_RESUME'); ?></td>
                                    <td  width="25%"><?php echo JHTML::_('select.genericList', $showhide, 'vis_emalljobsappliedapplications', 'class="inputbox" '. '', 'value', 'text', $this->config['vis_emalljobsappliedapplications']);; ?></td>
                                </tr>
                                <tr>
                                    <td class="key" ><?php echo JText::_('JS_MY_COMPANIES'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'vis_emmycompanies', 'class="inputbox" '. '', 'value', 'text', $this->config['vis_emmycompanies']);; ?></td>
                                    <td class="key"><?php echo JText::_('JS_SEARCH_RESUME'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'vis_emresumesearch', 'class="inputbox" '. '', 'value', 'text', $this->config['vis_emresumesearch']);; ?></td>
                                </tr>
                                <tr>
                                    <td class="key" ><?php echo JText::_('JS_NEW_JOB'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'vis_emformjob', 'class="inputbox" '. '', 'value', 'text', $this->config['vis_emformjob']);; ?></td>
                                    <td class="key"><?php echo JText::_('JS_RESUME_SAVE_SEARCHES'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'vis_emmy_resumesearches', 'class="inputbox" '. '', 'value', 'text', $this->config['vis_emmy_resumesearches']);; ?></td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_MY_JOBS'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'vis_emmyjobs', 'class="inputbox" '. '', 'value', 'text', $this->config['vis_emmyjobs']);; ?></td>
                                    <td class="key"><?php echo JText::_('JS_RESUME_BY_CATEGORY'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'vis_emresumebycategory', 'class="inputbox" '. '', 'value', 'text', $this->config['vis_emresumebycategory']);; ?></td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_NEW_DEPARTMENT'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'vis_emformdepartment', 'class="inputbox" '. '', 'value', 'text', $this->config['vis_emformdepartment']);; ?></td>
                                    <td class="key"><?php echo JText::_('JS_PACKAGES'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'vis_empackages', 'class="inputbox" '. '', 'value', 'text', $this->config['vis_empackages']);; ?></td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_MY_DEPARTMENTS'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'vis_emmydepartment', 'class="inputbox" '. '', 'value', 'text', $this->config['vis_emmydepartment']);; ?></td>
                                    <td class="key"><?php echo JText::_('JS_PURCHASE_HISTORY'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'vis_empurchasehistory', 'class="inputbox" '. '', 'value', 'text', $this->config['vis_empurchasehistory']);; ?></td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_MESSAGES'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'vis_emmessages', 'class="inputbox" '. '', 'value', 'text', $this->config['vis_emmessages']);; ?></td>
                                    <td class="key"><?php echo JText::_('JS_MY_STATS'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'vis_emmy_stats', 'class="inputbox" '. '', 'value', 'text', $this->config['vis_emmy_stats']);; ?></td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_RESUME_RSS'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'vis_resume_rss', 'class="inputbox" '. '', 'value', 'text', $this->config['vis_resume_rss']);; ?></td>
                                    <td class="key"><?php echo JText::_('JS_FOLDERS'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'vis_emmyfolders', 'class="inputbox" '. '', 'value', 'text', $this->config['vis_emmyfolders']);; ?></td>
                                </tr>
                                <tr>
                                    <td class="key"><?php echo JText::_('JS_NEW_FOLDER'); ?></td>
                                    <td ><?php echo JHTML::_('select.genericList', $showhide, 'vis_emnewfolders', 'class="inputbox" '. '', 'value', 'text', $this->config['vis_emnewfolders']);; ?></td>
                                </tr>
                            </table>
                            </fieldset>
						</div>
						<div id="email">
                            <fieldset>
                            <legend><?php echo JText::_('JS_RESUME_APPLY_ALERT'); ?></legend>
                            <table cellpadding="5" cellspacing="1" border="0" width="100%" class="admintable" >
                                <tr>
                                    <td class="key" width="25%"><?php echo JText::_('JS_SHOW_RESUME_FIELDS'); ?></td>
                                    <td width="25%"><?php echo JHTML::_('select.genericList', $resumealert, 'employer_resume_alert_fields', 'class="inputbox" '. '', 'value', 'text', $this->config['employer_resume_alert_fields']); ?></td>
									<td><small><?php echo JText::_('JS_RESUME_APPLIED_ON_JOB_MAIL_SEND_TO_EMPLOYER_WITH_RESUME_DATA_DETATIL');?></small></td>
                                </tr>
                            </table>
                            </fieldset>
						</div>
						</div>
                                </td></tr>
                            </table>
                                    <input type="hidden" name="task" value="saveconf" />
                                    <input type="hidden" name="view" value="application" />
                                    <input type="hidden" name="notgeneralbuttonsubmit" value="1" />
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
    function paymentmethod_showhide(){
        paypal = document.getElementById('paymentmethod_paypal').style;
        pagseguro = document.getElementById('paymentmethod_pagseguro').style;
        packageform = document.getElementById('paymentmethod_packageform').style;
        element = document.getElementById('payment_method');
        val = element.options[element.selectedIndex].value;
        if(val == 'paypal'){
            paypal.display = 'block';
            pagseguro.display = 'none';
            packageform.display = 'none';
        }else if(val == 'pagseguro'){
            paypal.display = 'none';
            pagseguro.display = 'block';
            packageform.display = 'none';
        }else if((val == 'fastspring') || (val == 'authorizenet') || (val == '2checkout')){
            paypal.display = 'none';
            pagseguro.display = 'none';
            packageform.display = 'block';
        }else{
            paypal.display = 'none';
            pagseguro.display = 'none';
            packageform.display = 'none';
        }
    }

    paymentmethod_showhide(); //calling method

    function hideshowtables(table_id){
        var obj = document.getElementById(table_id);
        var bool = obj.style.display;
        if(bool == '')
            obj.style.display = "none";
        else
            obj.style.display = "";
       document.getElementById(table_id) = obj;
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
	top:159%;
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
