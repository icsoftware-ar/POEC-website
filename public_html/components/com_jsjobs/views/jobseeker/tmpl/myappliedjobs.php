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
 * File Name:	views/jobseeker/tmpl/myappliedjobs.php
 ^ 
 * Description: template view for my applied jobs
 ^ 
 * History:		NONE
 ^ 
 */
 
 defined('_JEXEC') or die('Restricted access');
 
 global $mainframe;
 $document =& JFactory::getDocument();
 $document->addStyleSheet('components/com_jsjobs/themes/'.$this->config['theme']);
    
 $link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myappliedjobs&Itemid='.$this->Itemid;
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
					echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_MY_APPLIED_JOBS');
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
				<span id="tp_headingtext_center"><?php echo JText::_('JS_MY_APPLIED_JOBS');  ?></span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>
<?php
	if ($this->application){

if ($this->userrole->rolefor == 2) { // job seeker

	if ($this->sortlinks['sortorder'] == 'ASC')
		$img = "components/com_jsjobs/images/sort0.png";
	else
		$img = "components/com_jsjobs/images/sort1.png";
?>
	<div id="sortbylinks">
		<span id="sbl_title"><?php echo JText::_('JS_SORT_BY'); ?>&nbsp;:</span>
		<?php	if($this->listjobconfig['lj_title'] == '1') { ?>				
			<span id="sbl_links"><a href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['title']; ?>"><?php echo JText::_('JS_TITLE'); ?><?php if ($this->sortlinks['sorton'] == 'title') { ?> <img src="<?php echo $img ?>"> <?php } ?> |</a></span>
		<?php }
		if($this->listjobconfig['lj_jobtype'] == '1') { ?>				
			<span id="sbl_links"><a href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['jobtype']; ?>"><?php echo JText::_('JS_JOBTYPE'); ?><?php if ($this->sortlinks['sorton'] == 'jobtype') { ?> <img src="<?php echo $img ?>"> <?php } ?> |</a></span>
		<?php }
		if($this->listjobconfig['lj_jobstatus'] == '1') { ?>	
			<span id="sbl_links"><a href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['jobstatus']; ?>"><?php echo JText::_('JS_JOBSTATUS'); ?><?php if ($this->sortlinks['sorton'] == 'jobstatus') { ?> <img src="<?php echo $img ?>"> <?php } ?> |</a></span>
		<?php }
		if($this->listjobconfig['lj_company'] == '1') { ?>	
			<span id="sbl_links"><a href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['company']; ?>"><?php echo JText::_('JS_COMPANY'); ?><?php if ($this->sortlinks['sorton'] == 'company') { ?> <img src="<?php echo $img ?>"> <?php } ?> |</a></span>
		<?php }
		/*if($this->listjobconfig['lj_country'] == '1') { ?>	
			<span id="sbl_links"><a href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['country']; ?>"><?php echo JText::_('JS_COUNTRY'); ?><?php if ($this->sortlinks['sorton'] == 'country') { ?> <img src="<?php echo $img ?>"> <?php } ?> |</a></span>
		<?php }*/
		if($this->listjobconfig['lj_salary'] == '1') { ?>	
			<span id="sbl_links"><a href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['salaryrange']; ?>"><?php echo JText::_('JS_SALARY_RANGE'); ?><?php if ($this->sortlinks['sorton'] == 'salaryrange') { ?> <img src="<?php echo $img ?>"> <?php } ?> |</a></span>
		<?php }
		if($this->listjobconfig['lj_created'] == '1') { ?>	
			<span id="sbl_links"><a href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['created']; ?>"><?php echo JText::_('JS_DATEPOSTED'); ?><?php if ($this->sortlinks['sorton'] == 'created') { ?> <img src="<?php echo $img ?>"> <?php } ?> |</a></span>
		<?php } ?>
	</div>
		<?php
		$tdclass = array($this->theme['odd'], $this->theme['even']);
		$days = $this->config['newdays'];
		$isnew = date("Y-m-d H:i:s", strtotime("-$days days"));
		//$tdclass = array("odd", "even");
		$isodd =1;
		$istr=1;
		if ( isset($this->application) ){
		foreach($this->application as $job) {
                    $comma = "";
                    $isodd = 1 - $isodd; ?>
					<div id="jl_maindiv" class="<?php echo $tdclass[$isodd];?>">
						<div id="jl_leftdiv">
								<?php 
									$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_job&vj=4&oi='.$job->aliasid.'&Itemid='.$this->Itemid; 
								?>
								<span id="jl_title"><a href="<?php echo $link?>"><?php echo $job->title;?></a></span>
										<?php 
										if($this->config['show_applied_resume_status']==1){
											if($job->resumestatus==4) { ?>
												<span class="apply_job_resume_status rejected" ><span id="apply_job_resume_status_reject"></span><?php echo JText::_('JS_REJECTED');?></span>
											<?php }elseif($job->resumestatus==3) { ?>
												<span class="apply_job_resume_status hired" ><span id="apply_job_resume_status_hired"></span><?php echo JText::_('JS_HIRED');?></span>
											<?php }elseif($job->resumestatus==5) { ?>
												<span class="apply_job_resume_status shortlist" ><span id="apply_job_resume_status_shortlist"></span><?php echo JText::_('JS_SHORTLIST');?></span>
											<?php }elseif($job->resumestatus==2) { ?>
												<span class="apply_job_resume_status spam" ><span id="apply_job_resume_status_spam"></span><?php echo JText::_('JS_SPAM');?></span>
											<?php }	 
										}
										?>
								
								<span id="jl_applieddate">
									<?php 
										echo JText::_('JS_APPLIED_DATE').':&nbsp;'.date($this->config['date_format'],strtotime($job->apply_date));
									 ?>
								</span>
						</div>
						<div id="jl_rightdiv">
							<div id="jl_data">
								<?php if($this->listjobconfig['lj_company'] == '1') {
											echo "<span class='jl_data_value'>";
											if($this->config['labelinlisting'] == '1'){
												echo "<label class='jl_data_text'>".JText::_('JS_COMPANY').": </label>";
											}
											$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_company&vm=4&md='.$job->companyaliasid.'&jobcat='.$job->jobcategory.'&Itemid='.$this->Itemid; 
											?>
											<b><a class="jl_company_a" href="<?php echo $link?>"><?php echo $job->companyname; ?></a></b></span>
								<?php }
										if($this->listjobconfig['lj_category'] == '1') { 
											echo "<span class='jl_data_value'>";
											if($this->config['labelinlisting'] == '1'){
												echo "<label class='jl_data_text'>".JText::_('JS_CATEGORY').": </label>";
											}
											echo  $job->cat_title."</span>";
										} ?>
							</div>
							<div id="jl_data">
								<?php if($this->listjobconfig['lj_salary'] == '1') {
										$salary = $job->symbol . $job->rangestart . ' - ' . $job->symbol . $job->rangeend . ' /month ' ;
										if($job->rangestart){
											echo "<span class='jl_data_value'>";
											if($this->config['labelinlisting'] == '1'){
												echo "<label class='jl_data_text'>".JText::_('JS_SALARY').": </label>";
											}
											echo $salary."</span>";
										}
									}
								if($this->listjobconfig['lj_jobtype'] == '1') {
									echo "<span class='jl_data_value'>";
											if($this->config['labelinlisting'] == '1'){
												echo "<label class='jl_data_text'>".JText::_('JS_JOB_TYPE').": </label>";
											}
									echo $job->jobtypetitle;
									if($this->listjobconfig['lj_jobstatus'] == '1') echo ' - '.$job->jobstatustitle; 
									echo "</span>";
								} ?>
							</div>
							<div id="jl_lowerdiv">
								<?php  echo "<label class='jl_data_text'>".JText::_('JS_LOCATION').": </label>";
									if($this->listjobconfig['lj_city'] == '1') {
										if(isset($job->city) AND  !empty($job->city)) {
											if(strlen($job->city) > 35){  ?> <span class='jl_data_value'> <?php echo JText::_('JS_MULTI_CITY').$job->city; ?></span>
											<?php }else{ echo "<span class='jl_data_value'>".$job->city."</span>";}
										}		
									}
									 ?>
									<span class="jl_no_of_jobs">
										<?php 
										if($this->listjobconfig['lj_noofjobs'] == '1') {
												if ($job->noofjobs != 0){
													echo "<span class='jl_data_value'>".JText::_('JS_NOOFJOBS').": ";
													echo $job->noofjobs."</span>";
												}
										} ?>
									</span>
							</div>
						</div>
						<?php 
							$g_f_job = 0;
							if($job->isgold == 1) $g_f_job = 1; // gold job
							if($job->isfeatured == 1) $g_f_job = 2; // featured job
							if($job->isgold == 1 && $job->isfeatured == 1) $g_f_job = 3;//gold and featured job
							switch($g_f_job){
								case 1: //gold job
									echo '<div id="jl_image_gold"></div>';
								break;
								case 2: //featured job
									echo '<div id="jl_image_featured"></div>';
								break;
								case 3: //gold and featured job
									echo '<div id="jl_image_gold_featured"></div>';
								break;
							}
							if ($job->created > $isnew) {
								echo '<div id="jl_image_new"></div>';
							} ?>
					</div>
                    <?php }
					} ?>
<form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myappliedjobs&Itemid='.$this->Itemid); ?>" method="post">
	<div id="jl_pagination">
		<div id="jl_pagination_pageslink">
			<?php echo $this->pagination->getPagesLinks(); ?>
		</div>
		<div id="jl_pagination_box">
			<?php	
				echo JText::_('JS_DISPLAY_#');
				echo $this->pagination->getLimitBox();
			?>
		</div>
		<div id="jl_pagination_counter">
			<?php echo $this->pagination->getResultsCounter(); ?>
		</div>
	</div>
</form>	
<?php

} else{ // not allowed job posting?>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<div id="message"><b><?php echo JText::_('EA_YOU_ARE_NOT_ALLOWED_TO_VIEW');?></b></div>
	</div>
<?php
}
}else{ // no result found in this category ?>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<div id="message"><b><?php echo JText::_('JS_RESULT_NOT_FOUND');?></b></div>
	</div>
<?php
}
}//ol
?>	

<div id="jsjobs_footer"><?php echo eval(base64_decode('aWYoJHRoaXMtPmNvbmZpZ1snZnJfY3JfdHhzaCddKSB7DQplY2hvIA0KJzx0YWJsZSB3aWR0aD0iMTAwJSIgc3R5bGU9InRhYmxlLWxheW91dDpmaXhlZDsiPg0KPHRyPjx0ZCBoZWlnaHQ9IjE1Ij48L3RkPjwvdHI+DQo8dHI+PHRkIHN0eWxlPSJ2ZXJ0aWNhbC1hbGlnbjp0b3A7IiBhbGlnbj0iY2VudGVyIj4NCjxhIGNsYXNzPSJpbWciIHRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIj48aW1nIHNyYz0iaHR0cDovL3d3dy5qb29tc2t5LmNvbS9sb2dvL2pzam9ic2NybG9nby5wbmciPjwvYT4NCjxicj4NCkNvcHlyaWdodCAmY29weTsgMjAwOCAtICcuZGF0ZSgnWScpLicsDQo8c3BhbiBpZD0idGhlbWVhbmNob3IiPiA8YSBjbGFzcz0iYW5jaG9yInRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSI+QnVydWogU29sdXRpb25zIDwvYT48L3NwYW4+PC90ZD48L3RyPg0KPC90YWJsZT4nOw0KfQ=='));?></div>
