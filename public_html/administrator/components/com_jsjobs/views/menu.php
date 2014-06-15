<?php 
/**
 * @Copyright Copyright (C) 2010- ... Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:		Al-Barr Technologies
 * Contact:		www.al-barr.com , info@al-barr.com
 * Created on:	Jan 11, 2009
 *
 * Project: 		JS Jobs
 */

if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );

global $mainframe;
	$document =& JFactory::getDocument();
	$document->addStyleSheet('components/com_jsjobs/include/admin_menu/sdmenu/sdmenu.css');
	$document->addScript('components/com_jsjobs/include/admin_menu/sdmenu/sdmenu.js');

?>


	<script type="text/javascript">
	// <![CDATA[
	var myMenu;
	window.onload = function() {
		myMenu = new SDMenu("my_menu");
		myMenu.oneSmOnly = true;  // One expanded submenu at a time
		myMenu.init();
	};
	// ]]>
	</script>

    <div>
		<img src="components/com_jsjobs/include/images/jsjobs_logo.png" width="175">
	</div>
	<div style="float: left" id="my_menu" class="sdmenu">
      <div class="collapsed">
        <span><?php echo JText::_('JS_ADMIN'); ?></span>
		<a href="index.php?option=com_jsjobs&task=view&layout=controlpanel"><?php echo JText::_('JS_CONTROL_PANEL'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=jobtypes"><?php echo JText::_('JS_JOB_TYPES'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=jobstatus"><?php echo JText::_('JS_JOB_STATUS'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=shifts"><?php echo JText::_('JS_SHIFTS'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=highesteducations"><?php echo JText::_('JS_HIGHEST_EDUCATIONS'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=ages"><?php echo JText::_('JS_AGES'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=careerlevels"><?php echo JText::_('JS_CAREER_LEVELS'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=experience"><?php echo JText::_('JS_EXPERIENCE'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=info"><?php echo JText::_('JS_INFORMATION'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=updates"><?php echo JText::_('JS_JOB_UPDATE'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=jsjobsstats"><?php echo JText::_('JS_JOBS_STATS'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=currency"><?php echo JText::_('JS_CURRENCY'); ?></a>
	  </div>
    <div class="collapsed">
        <span><?php echo JText::_('SHARING_SERVICE'); ?></span>
        <a href="index.php?option=com_jsjobs&task=view&layout=jobshare"><?php echo JText::_('JS_JOB_SHARE'); ?></a>
        <a href="index.php?option=com_jsjobs&task=view&layout=jobsharelog"><?php echo JText::_('JS_JOB_SHARE_LOG'); ?></a>
    </div>

      <div class="collapsed">
        <span><?php echo JText::_('JS_CONFIGURATIONS'); ?></span>
		<a href="index.php?option=com_jsjobs&task=view&layout=configurations"><?php echo JText::_('JS_GENERAL'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=configurationsemployer"><?php echo JText::_('JS_EMPLOYER'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=configurationsjobseeker"><?php echo JText::_('JS_JOBSEEKER'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=paymentmethodconfig"><?php echo JText::_('PAYMENT_METHODS_CONFIGURATION'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=themes"><?php echo JText::_('JS_THEMES'); ?></a>
	  </div>

      <div class="collapsed">
        <span><?php echo JText::_('JS_COMPANIES');?></span>
		<a href="index.php?option=com_jsjobs&task=view&layout=companies"><?php echo JText::_('JS_COMPANIES'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=companiesqueue"><?php echo JText::_('JS_APPROVAL_QUEUE'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=goldcompanies"><?php echo JText::_('JS_GOLD_COMPANIES'); ?></a>
		<!--<a href="index.php?option=com_jsjobs&task=view&layout=goldcompaniesqueue"><?php echo JText::_('JS_GOLD_APPROVAL_QUEUE'); ?></a>-->
		<a href="index.php?option=com_jsjobs&task=view&layout=featuredcompanies"><?php echo JText::_('JS_FEATURED_COMPANIES'); ?></a>
		<!--<a href="index.php?option=com_jsjobs&task=view&layout=featuredcompaniesqueue"><?php echo JText::_('JS_FEATURED_APPROVAL_QUEUE'); ?></a>-->
		<a href="index.php?option=com_jsjobs&task=view&layout=userfields&ff=1"><?php echo JText::_('JS_USER_FIELDS'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=fieldsordering&ff=1"><?php echo JText::_('JS_FIELDS'); ?></a>
		<!--<a href="index.php?option=com_jsjobs&task=view&layout=userfields&ff=11"><?php echo JText::_('JS_VISITOR_USER_FIELDS'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=fieldsordering&ff=11"><?php echo JText::_('JS_VISITOR_FIELDS'); ?></a>-->
	  </div>

	<div class="collapsed">
        <span><?php echo JText::_('JS_DEPARTMENTS');?></span>
		<a href="index.php?option=com_jsjobs&task=view&layout=departments"><?php echo JText::_('JS_DEPARTMENTS'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=departmentqueue"><?php echo JText::_('JS_APPROVAL_QUEUE'); ?></a>
	  </div>
      <div class="collapsed">
        <span><?php echo JText::_('JS_JOBS');?></span>
		<a href="index.php?option=com_jsjobs&task=view&layout=jobs"><?php echo JText::_('JS_JOBS'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=jobqueue"><?php echo JText::_('JS_APPROVAL_QUEUE'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=appliedresumes"><?php echo JText::_('JS_APPLIED_RESUME'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=goldjobs"><?php echo JText::_('JS_GOLD_JOBS'); ?></a>
		<!--<a href="index.php?option=com_jsjobs&task=view&layout=goldjobsqueue"><?php echo JText::_('JS_GOLD_APPROVAL_QUEUE'); ?></a>-->
		<a href="index.php?option=com_jsjobs&task=view&layout=featuredjobs"><?php echo JText::_('JS_FEATURED_JOBS'); ?></a>
		<!--<a href="index.php?option=com_jsjobs&task=view&layout=featuredjobsqueue"><?php echo JText::_('JS_FEATURED_APPROVAL_QUEUE'); ?></a>-->
		<a href="index.php?option=com_jsjobs&task=view&layout=userfields&ff=2"><?php echo JText::_('JS_USER_FIELDS'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=fieldsordering&ff=2"><?php echo JText::_('JS_FIELDS'); ?></a>
		<!--<a href="index.php?option=com_jsjobs&task=view&layout=userfields&ff=12"><?php echo JText::_('JS_VISITOR_USER_FIELDS'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=fieldsordering&ff=12"><?php echo JText::_('JS_VISITOR_FIELDS'); ?></a>-->	
		<a href="index.php?option=com_jsjobs&task=view&layout=jobsearch"><?php echo JText::_('JS_SEARCH'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=jobalert"><?php echo JText::_('JS_JOB_ALERT'); ?></a>
	  </div>
    <div class="collapsed">
        <span><?php echo JText::_('JS_RESUME');?></span>
		<a href="index.php?option=com_jsjobs&task=view&layout=empapps"><?php echo JText::_('JS_RESUME'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=appqueue"><?php echo JText::_('JS_APPROVAL_QUEUE'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=goldresumes"><?php echo JText::_('JS_GOLD_RESUME'); ?></a>
		<!--<a href="index.php?option=com_jsjobs&task=view&layout=goldresumesqueue"><?php echo JText::_('JS_GOLD_APPROVAL_QUEUE'); ?></a>-->
		<a href="index.php?option=com_jsjobs&task=view&layout=featuredresumes"><?php echo JText::_('JS_FEATURED_RESUME'); ?></a>
		<!--<a href="index.php?option=com_jsjobs&task=view&layout=featuredresumesqueue"><?php echo JText::_('JS_FEATURED_APPROVAL_QUEUE'); ?></a>-->
		<a href="index.php?option=com_jsjobs&task=view&layout=userfields&ff=3"><?php echo JText::_('JS_USER_FIELDS'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=fieldsordering&ff=3"><?php echo JText::_('JS_FIELDS'); ?></a>
		<!--<a href="index.php?option=com_jsjobs&view=application&layout=formresumeuserfield&ff=13"><?php echo JText::_('JS_VISITOR_USER_FIELDS'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=fieldsordering&ff=13"><?php echo JText::_('JS_VISITOR_FIELDS'); ?></a>-->
		<a href="index.php?option=com_jsjobs&task=view&layout=resumesearch"><?php echo JText::_('JS_SEARCH'); ?></a>
	  </div>

      <div class="collapsed">
        <span><?php echo JText::_('JS_PACKAGES');?></span>
		<a href="index.php?option=com_jsjobs&task=view&layout=employerpackages"><?php echo JText::_('JS_EMPLOYER_PACKAGES'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=jobseekerpackages"><?php echo JText::_('JS_JOBSEEKER_PACKAGES'); ?></a>
	  </div>
      <div class="collapsed">
        <span><?php echo JText::_('JS_PAYMENTS');?></span>
		<a href="index.php?option=com_jsjobs&task=view&layout=employerpaymenthistory"><?php echo JText::_('JS_EMPLOYER_HISTORY'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=jobseekerpaymenthistory"><?php echo JText::_('JS_JOBSEEKER_HISTORY'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=payment_report"><?php echo JText::_('JS_PAYMENT_REPORT'); ?></a>
	  </div>

      <div class="collapsed">
        <span><?php echo JText::_('JS_MESSAGES');?></span>
		<a href="index.php?option=com_jsjobs&task=view&layout=messages"><?php echo JText::_('JS_MESSAGES'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=messagesqueue"><?php echo JText::_('JS_APPROVAL_QUEUE'); ?></a>
	  </div>
      <div class="collapsed">
        <span><?php echo JText::_('JS_FOLDERS');?></span>
		<a href="index.php?option=com_jsjobs&task=view&layout=folders"><?php echo JText::_('JS_FOLDERS'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=foldersqueue"><?php echo JText::_('JS_APPROVAL_QUEUE'); ?></a>
	  </div>


      <div class="collapsed">
        <span><?php echo JText::_('JS_CATEGORIES');?></span>
		<a href="index.php?option=com_jsjobs&task=view&layout=categories"><?php echo JText::_('JS_CATEGORIES'); ?></a>
      </div>

      <div class="collapsed">
        <span><?php echo JText::_('JS_SALARYRANGE');?></span>
		<a href="index.php?option=com_jsjobs&task=view&layout=salaryrange"><?php echo JText::_('JS_SALARYRANGE'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=salaryrangetype"><?php echo JText::_('JS_SALARY_RANGE_TYPES'); ?></a>
      </div>

      <div class="collapsed">
        <span><?php echo JText::_('JS_USER_ROLES');?></span>
		<a href="index.php?option=com_jsjobs&task=view&layout=users"><?php echo JText::_('JS_USERS'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=userstats"><?php echo JText::_('JS_USER_STATS'); ?></a>
	  </div>

      <div class="collapsed">
        <span><?php echo JText::_('JS_EMAIL_TEMPLATES');?></span>
		<a href="index.php?option=com_jsjobs&task=view&layout=emailtemplate&tf=ew-cm"><?php echo JText::_('JS_NEW_COMPANY'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=emailtemplate&tf=cm-ap"><?php echo JText::_('JS_COMPANY_APPROVAL'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=emailtemplate&tf=cm-rj"><?php echo JText::_('JS_COMPANY_REJECTING'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=emailtemplate&tf=ew-ob"><?php echo JText::_('JS_NEW_JOB'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=emailtemplate&tf=ob-ap"><?php echo JText::_('JS_JOB_APPROVAL'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=emailtemplate&tf=ob-rj"><?php echo JText::_('JS_JOB_REJECTING'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=emailtemplate&tf=ap-rs"><?php echo JText::_('JS_APPLIED_RESUME_STATUS'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=emailtemplate&tf=ew-rm"><?php echo JText::_('JS_NEW_RESUME'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=emailtemplate&tf=ew-ms"><?php echo JText::_('JS_NEW_MESSAGE'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=emailtemplate&tf=rm-ap"><?php echo JText::_('JS_RESUME_APPROVAL'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=emailtemplate&tf=rm-rj"><?php echo JText::_('JS_RESUME_REJECTING'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=emailtemplate&tf=ba-ja"><?php echo JText::_('JS_JOB_APPLY'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=emailtemplate&tf=ew-md"><?php echo JText::_('JS_NEW_DEPARTMENT'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=emailtemplate&tf=ew-rp"><?php echo JText::_('JS_NEW_EMPLOYER_PACKAGE'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=emailtemplate&tf=ew-js"><?php echo JText::_('JS_NEW_JOBSEEKER_PACKAGE'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=emailtemplate&tf=ms-sy"><?php echo JText::_('JS_MESSAGE'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=emailtemplate&tf=jb-at"><?php echo JText::_('JS_JOB_ALERT'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=emailtemplate&tf=jb-at-vis"><?php echo JText::_('JS_EMPLOYER_VISITOR_JOB'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=emailtemplate&tf=jb-to-fri"><?php echo JText::_('JS_JOB_TO_FRIEND'); ?></a>
      </div>

	  
      <div class="collapsed">
        <span><?php echo JText::_('JS_COUNTRIES');?></span>
		<a href="index.php?option=com_jsjobs&task=view&layout=countries"><?php echo JText::_('JS_COUNTRIES'); ?></a>
		<a href="index.php?option=com_jsjobs&task=view&layout=loadaddressdata"><?php echo JText::_('JS_LOAD_ADDRESS_DATA'); ?></a>
      </div>
    </div>


