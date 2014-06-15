<?php
/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 + Created by:          Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	views/jobseeker/tmpl/job_apply.php
 ^ 
 * Description: template view to apply for a job
 ^ 
 * History:		NONE
 ^ 
 */
 
 defined('_JEXEC') or die('Restricted access');
 global $mainframe;
 $user	=& JFactory::getUser();

$document =& JFactory::getDocument();
$document->addStyleSheet('components/com_jsjobs/themes/'.$this->config['theme']);

$isShowButton = 1;
?>
<?php if ($this->config['offline'] == '1'){ ?>
	<div id="toppanel">
		<div id="tp_header" <?php if($this->config['topimage'] == 0) echo 'style="background:none;"';?>>
			<span id="tp_title"><?php echo $this->config['title'];?></span>
		</div>
	</div>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<div id="message"><b><?php echo $this->config['offline_text']; ?></b></div>
	</div>
<?php }else{ ?>
	<div id="toppanel">
		<div id="tp_header" <?php if($this->config['topimage'] == 0) echo 'style="background:none;"';?>>
			<span id="tp_title"><?php echo $this->config['title'];?></span>
			<span id="tp_curloc">
				<?php if ($this->config['cur_location'] == 1) {
						if ($this->aj == '1'){ $vm=2;
							echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobcat&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk" ><?php echo JText::_('JS_JOB_CATEGORIES'); ?></a> > <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=list_jobs&jobcat=<?php echo $this->job->jobcategory; ?>&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk" ><?php echo JText::_('JS_JOBS_LIST_BY_CATEGORY'); ?></a> ><?php echo JText::_('JS_APPLYNOW');
						}else if ($this->aj == '2'){ $vm=3;
							echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobsearch&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_SEARCH_JOB'); ?></a> > <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=job_searchresults&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk" ><?php echo JText::_('JS_JOB_SEARCH_RESULT'); ?></a> > <?php echo JText::_('JS_APPLYNOW');
						}else if ($this->aj == '3'){ $vm=5;
							echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=listnewestjobs&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_NEWEST_JOBS'); ?></a> > <?php echo JText::_('JS_APPLYNOW');
						}else if ($this->aj == '4'){ $vm=8;
							echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=company_jobs&cd=<?php echo $this->job->companyid;?>&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo $this->job->companyname.' '.JText::_('JS_JOBS'); ?></a> > <?php echo JText::_('JS_APPLYNOW');
						}else if ($this->aj == '5'){ $vm=2;
							$catlink = JRoute::_("index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=list_jobs&jobcat=". $this->jobcat."&Itemid=".$this->Itemid);
							$jobcatlink = JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobcat&Itemid='.$this->Itemid);
							echo JText::_('JS_CUR_LOC'); ?> : <a href="<?php echo $jobcatlink; ?>" class="curloclnk"><?php echo JText::_('JS_JOB_CATEGORIES'); ?></a> > <a href="<?php echo $catlink; ?>" class="curloclnk"><?php echo JText::_('JS_JOBS_LIST_BY_CATEGORY'); ?></a> > <?php echo JText::_('JS_APPLYNOW');
						}
				} ?>
			</span>
		</div>
		<div id="tp_links">
			<?php 
			if (sizeof($this->jobseekerlinks) != 0){
				foreach($this->jobseekerlinks as $lnk)	{ ?>
					<a class="<?php if($lnk[2] == 1)echo 'first'; elseif($lnk[2] == -1)echo 'last';  ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
				<?php }
			}
			if (sizeof($this->employerlinks) != 0){
				foreach($this->employerlinks as $lnk)	{ ?>
					<a class="<?php if($lnk[2] == 1)echo 'first'; elseif($lnk[2] == -1)echo 'last';  ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
				<?php }
			}
			?>
		</div>
		<div id="tp_heading">
			<span id="tp_headingtext">
				<span id="tp_headingtext_left"></span>
				<span id="tp_headingtext_center"><?php echo JText::_('JS_APPLYNOW');  ?></span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>
<?php
$show_job_apply=1;
if($this->isjobsharing){
	if(empty($this->job)) $show_job_apply=0;	 
} 
if($show_job_apply==1){

if (isset($this->userrole->rolefor) && $this->userrole->rolefor == 2) { // job seeker


if ($this->totalresume > 0){ // Resume not empty

?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
	<table cellpadding="0" cellspacing="0" border="0" width="100%" >
		<tr><td colspan="4" height="15"></td></tr>
		<tr>
			<td align="center" class="sectionheadline" colspan="4">
					<span id="sectionheadline_text">
					<span id="sectionheadline_left"></span>
				<?php echo JText::_('JS_EMP_APP_INFO'); ?>
					<span id="sectionheadline_right"></span>
					</span>
			</td>
		</tr>
		<tr id="mc_field_row" class="<?php echo $this->theme['odd']; ?>">
			<td></td>
			<td  width="25%"><strong><?php echo JText::_('JS_MY_RESUME'); ?>	</strong></td>
			<td ><?php echo $this->myresumes; ?></td>
			<td></td>
		</tr>
		<tr id="mc_field_row" class="<?php echo $this->theme['odd']; ?>">
			<td></td>
			<td  width="25%"><strong><?php echo JText::_('JS_MY_COVER_LETTER'); ?>	</strong></td>
			<td ><?php echo $this->mycoverletters; ?></td>
			<td></td>
		</tr>
		<tr id="mc_field_row" class="<?php echo $this->theme['odd']; ?>">
			<td></td>
			<td  colspan="2" align="center"><font color="red"><strong>
			</strong></font></td>
			<td></td>
		</tr>
		<tr><td colspan="4" height="15"></td></tr>
		<tr>
			<td align="center" class="sectionheadline" colspan="4"><?php echo JText::_('JS_JOB_INFO'); ?></td>
		</tr>
		<tr id="mc_field_row" class="<?php echo $this->theme['odd']; ?>">
			<td></td>
			<td ><strong><?php echo JText::_('JS_TITLE'); ?>	</strong></td>
			<td ><?php echo $this->job->title; 
			$days = $this->config['newdays'];
			$isnew = date("Y-m-d H:i:s", strtotime("-$days days"));
			if ($this->job->created > $isnew)
				echo "<font color='red'> ".JText::_('JS_NEW')." </font>";
			?></td>
			<td></td>
		</tr>

		<?php if ($this->listjobconfig['lj_category'] == '1') { ?>
		
			<tr id="mc_field_row" class="<?php echo $this->theme['even']; ?>">
				<td></td>
				<td ><strong><?php echo JText::_('JS_CATEGORY'); ?>	</strong></td>
				<td ><?php echo $this->job->cat_title; ?></td>
				<td></td>
			</tr>
		<?php } 	
		if ($this->listjobconfig['lj_jobtype'] == '1') { ?>
		
		<tr id="mc_field_row" class="<?php echo $this->theme['odd']; ?>">
			<td></td>
			<td ><strong><?php echo JText::_('JS_JOBTYPE'); ?>	</strong></td>
			<td ><?php echo $this->job->jobtypetitle; ?></td>
			<td></td>
		</tr>
		<?php } 	
		if ($this->listjobconfig['lj_jobstatus'] == '1') { ?>
		<tr id="mc_field_row" class="<?php echo $this->theme['even']; ?>">
			<td></td>
			<td ><strong><?php echo JText::_('JS_JOBSTATUS'); ?>	</strong></td>
			<td ><?php
					echo "<font color='red'><strong>" .$this->job->jobstatustitle . "</strong></font>"; 
			?></td>
			<td></td>
		</tr>
		<?php } 	
		
		 if ($this->listjobconfig['lj_company'] == '1') {     ?>
		
			<tr id="mc_field_row" class="<?php echo $this->theme['odd']; ?>">
				<td></td>
				<td ><strong><?php echo JText::_('JS_COMPANY'); ?>	</strong></td>
				<td >
						<?php if (isset($_GET['jobcat'])) $jobcat = $_GET['jobcat']; else $jobcat=null;
							$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_company&vm='.$vm.'&md='.$this->job->companyaliasid.'&jobcat='.$jobcat.'&Itemid='.$this->Itemid; ?>
						<span id="anchor"><a class="anchor" href="<?php echo $link?>"><?php echo $this->job->companyname; ?></a></span>
				</td>
				<td></td>
			</tr>
		<?php } 	
		 if ($this->listjobconfig['lj_companysite'] == '1') {     ?>
		<tr id="mc_field_row" class="<?php echo $this->theme['even']; ?>">
			<td></td>
			<td ><strong><?php echo JText::_('JS_COMPANYURL'); ?>	</strong></td>
			<td ><span id="anchor"><a class="anchor" href='<?php $chkprotocol = isURL($this->job->url);if($chkprotocol == true) echo $this->job->url;else echo 'http://'.$this->job->url; ?>' target='_blank'><?php echo $this->job->url; ?></a></span></td>
			<td></td>
		</tr>
		<?php } 	
		 if ($this->listjobconfig['lj_companysite'] == '1') {     ?>
			<tr id="mc_field_row" class="<?php echo $this->theme['odd']; ?>">
				<td></td>
				<td ><strong><?php echo JText::_('JS_LOCATION'); ?>	</strong></td>
				<td >
					<?php if ($this->job->multicity != '') echo ',  '.$this->job->multicity; ?>
				</td>
				<td></td>
			</tr>
		<?php } ?>	
		<?php $trclass=$this->theme['odd'];
			if($this->job->jobsalaryrange != 0){ 
			$trclass=$this->theme['even'];
			?>
			<tr id="mc_field_row" class="<?php echo $this->theme['even']; ?>">
				<td></td>
				<td ><strong><?php echo JText::_('JS_SALARY_RANGE'); ?>	</strong></td>
				<td ><?php $salaryrange = $this->config['currency'] . $this->job->rangestart . ' - ' . $this->config['currency'] . $this->job->rangeend . ' /month';
					echo $salaryrange ?></td>
				<td></td>
			</tr>
		<?php 	}
		 if ($this->listjobconfig['lj_noofjobs'] == '1') {     ?>
		<?php if($this->job->noofjobs != 0){ 
			if ($trclass == $this->theme['even']) $trclass = $this->theme['odd']; else $trclass = $this->theme['even'];
			?>
			<tr id="mc_field_row" class="<?php echo $trclass ?>">
				<td></td>
				<td ><strong><?php echo JText::_('JS_NOOFJOBS'); ?>	</strong></td>
				<td ><?php echo $this->job->noofjobs ?></td>
				<td></td>
			</tr>
		<?php } 
		}
		if ($trclass == $this->theme['even']) $trclass = $this->theme['odd']; else $trclass = $this->theme['even'];
		?>
		<tr id="mc_field_row" class="<?php echo $trclass ?>">
			<td></td>
			<td ><strong><?php echo JText::_('JS_DATEPOSTED'); ?>	</strong></td>
			<td ><?php echo date($this->config['date_format'],strtotime($this->job->created)); ?></td>
			<td></td>
		</tr>
		<tr><td colspan="4" height="25"></td></tr>
		<tr>
			<td colspan="3" align="center">
			<?php if ($isShowButton == 1) { ?>
				<input type="submit" id="button" class="button" name="submit_app" onclick="document.adminForm.submit();"  value="<?php echo JText::_('JS_APPLYNOW'); ?>" /></td>
			<?php 
				}else if ($isShowButton == 2) { 
					echo "<font color='red'><strong>" . JText::_('JS_JOBSTATUS') . " : " . $jobstatus[$this->job->jobstatus-1] . "</strong></font>"; 
				}else if ($isShowButton == 3) { 
					echo "<font color='red'><strong>" . JText::_('JS_EMP_APP_WAIT_APPROVAL')  . "</strong></font>"; 
				}else if ($isShowButton == 4) { 
					echo "<font color='red'><strong>" . JText::_('JS_EMP_APP_REJECT')  . "</strong></font>"; 
				}
			?>
			</td>
			<td></td>
		</tr>
			<input type="hidden" name="view" value="application" />
			<input type="hidden" name="layout" value="static" />
			<input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			<input type="hidden" name="task" value="jobapply" />
			<input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
			<input type="hidden" name="jobid" value="<?php echo $this->job->id; ?>" />
			<input type="hidden" name="oldcvid" value="<?php if(isset($this->myapplication->id)) echo $this->myapplication->id; ?>" />
				<input type="hidden" name="apply_date" value="<?php echo date('Y-m-d H:i:s'); ?>" />
			<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
</form>
		
		
	</table>
<?php
}else{ // Employment application is empty ?>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<div id="message"><b><?php echo '<p align="center">'.JText::_('EA_EMP_APP_EMPTY'); ?></b></div>
	</div>
<?php

}
}elseif (!isset($this->userrole->rolefor )) {
    if($this->config['visitor_show_login_message'] == 1){
	    $version = new JVersion;
	    $joomla = $version->getShortVersion();
		$jversion = substr($joomla,0,3);
        $redirectUrl = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=successfullogin';
        $redirectUrl = '&amp;return='.base64_encode($redirectUrl);
        $finalUrl = 'index.php?option=com_user&view=login'. $redirectUrl;
        if($jversion == '1.5') $finalUrl = 'index.php?option=com_user&view=login'. $redirectUrl;
        else $finalUrl = 'index.php?option=com_users&view=login'. $redirectUrl;
        $finalUrl = JRoute::_($finalUrl);
        $formresumelink = JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=formresume');
        echo JTEXT::_('JS_PLEASE_LOGIN_TO_RECORD_YOUR_RESUME_FOR_FUTURE_USE');
        echo "<br><a href='".$finalUrl."'><strong>".JTEXT::_('JS_LOGIN')."</strong></a> ".JTEXT::_('JS_OR')."<strong><a href='".$formresumelink."'>".JTEXT::_('JS_JOB_APPLY_AS_A_VISITOR')."</a></strong>";
    }


} else{ // not allowed job posting ?>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<div id="message"><b><?php echo JText::_('EA_YOU_ARE_NOT_ALLOWED_TO_VIEW');?></b></div>
	</div>
<?php

}

}else{ 
		if (!isset($this->userrole->rolefor )) {
			if($this->config['visitor_show_login_message'] == 1){
				$version = new JVersion;
				$joomla = $version->getShortVersion();
				$jversion = substr($joomla,0,3);
				$redirectUrl = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=successfullogin';
				$redirectUrl = '&amp;return='.base64_encode($redirectUrl);
				$finalUrl = 'index.php?option=com_user&view=login'. $redirectUrl;
				if($jversion == '1.5') $finalUrl = 'index.php?option=com_user&view=login'. $redirectUrl;
				else $finalUrl = 'index.php?option=com_users&view=login'. $redirectUrl;
				$finalUrl = JRoute::_($finalUrl);
				$formresumelink = JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=formresume');
				echo JTEXT::_('JS_PLEASE_LOGIN_TO_RECORD_YOUR_RESUME_FOR_FUTURE_USE');
				echo "<br><a href='".$finalUrl."'><strong>".JTEXT::_('JS_LOGIN')."</strong></a> ".JTEXT::_('JS_OR')."<strong><a href='".$formresumelink."'>".JTEXT::_('JS_JOB_APPLY_AS_A_VISITOR')."</a></strong>";
			}
		 }else{ ?> 
				<div id="errormessagedown"></div>
				<div id="errormessage" class="errormessage">
					<div id="message"><b><?php echo JText::_('JS_ERROR_OCCURE_PLEASE_CONTACT_TO_ADMINISTRATOR');?></b></div>
				</div>
			 
		 <?php } ?>

<?php }

}//ol


?>
<div id="jsjobs_footer"><?php echo eval(base64_decode('aWYoJHRoaXMtPmNvbmZpZ1snZnJfY3JfdHhzaCddKSB7DQplY2hvIA0KJzx0YWJsZSB3aWR0aD0iMTAwJSIgc3R5bGU9InRhYmxlLWxheW91dDpmaXhlZDsiPg0KPHRyPjx0ZCBoZWlnaHQ9IjE1Ij48L3RkPjwvdHI+DQo8dHI+PHRkIHN0eWxlPSJ2ZXJ0aWNhbC1hbGlnbjp0b3A7IiBhbGlnbj0iY2VudGVyIj4NCjxhIGNsYXNzPSJpbWciIHRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIj48aW1nIHNyYz0iaHR0cDovL3d3dy5qb29tc2t5LmNvbS9sb2dvL2pzam9ic2NybG9nby5wbmciPjwvYT4NCjxicj4NCkNvcHlyaWdodCAmY29weTsgMjAwOCAtICcuZGF0ZSgnWScpLicsDQo8c3BhbiBpZD0idGhlbWVhbmNob3IiPiA8YSBjbGFzcz0iYW5jaG9yInRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSI+QnVydWogU29sdXRpb25zIDwvYT48L3NwYW4+PC90ZD48L3RyPg0KPC90YWJsZT4nOw0KfQ=='));?></div>
<?php 

function isURL($url = NULL) {
        if($url==NULL) return false;

        $protocol = '(http://|https://)';
        if(ereg($protocol, $url)==true) return true;
        else return false;
}

?>
