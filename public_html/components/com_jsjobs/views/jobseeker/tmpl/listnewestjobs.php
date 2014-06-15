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
 * File Name:	views/jobseeker/tmpl/listnewestjobs.php
 ^ 
 * Description: template view for newest jobs
 ^ 
 * History:		NONE
 ^ 
 */
 
 defined('_JEXEC') or die('Restricted access');

 global $mainframe;
 $document =& JFactory::getDocument();
 $document->addStyleSheet('components/com_jsjobs/themes/'.$this->config['theme']);
 $link = JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=listnewestjobs&Itemid='.$this->Itemid);
 $showgoogleadds = $this->config['googleadsenseshowinnewestjobs'];
 $afterjobs = $this->config['googleadsenseshowafter'];
 $googleclient = $this->config['googleadsenseclient'];
 $googleslot = $this->config['googleadsenseslot'];
 $googleaddhieght = $this->config['googleadsenseheight'];
 $googleaddwidth = $this->config['googleadsensewidth'];
 $googleaddcss = $this->config['googleadsensecustomcss'];
	
 $jobcatlink = JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobcat&Itemid='.$this->Itemid);

 $version = new JVersion;
$joomla = $version->getShortVersion();
$jversion = substr($joomla,0,3);

	if($jversion < 3){
		JHtml::_('behavior.mootools');
		$document->addScript('components/com_jsjobs/js/jquery.js');
	}else{
		JHtml::_('behavior.framework');
		JHtml::_('jquery.framework');
	}	
	$document->addScript('components/com_jsjobs/js/jquery.tokeninput.js');
	$document->addStyleSheet('components/com_jsjobs/css/token-input-jsjobs.css');

if (isset($this->userrole->rolefor)){
	if ($this->userrole->rolefor != ''){
		if ($this->userrole->rolefor == 2) // job seeker
			$allowed = true;
		elseif($this->userrole->rolefor == 1){
                    if($this->config['employerview_js_controlpanel'] == 1)
			$allowed = true;
                    else
                        $allowed = false;
                }
	}else{
		$allowed = true;
	}
}else $allowed = true; // user not logined

?>
<?php require_once( 'tellafriend.php' );
if ($this->config['offline'] == '1'){ ?>
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
					echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_NEWEST_JOBS');
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
		<?php if ($allowed == true) { 
			$flink=JRoute::_($link);?>
			<div id="tp_filter">
				<form action="<?php echo $flink; ?>" method="post" id="adminForm" name="adminForm">
				<?php require_once( 'job_filters.php' ); ?>	
				</form>
			</div>
		<?php } ?>
		<div id="tp_heading">
			<span id="tp_headingtext">
				<span id="tp_headingtext_left"></span>
				<span id="tp_headingtext_center"><?php echo JText::_('JS_NEWEST_JOBS');  ?></span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>
<?php
if ($allowed == true) { 

if (isset($this->jobs)) {

?>
	<?php 
		$tdclass = array($this->theme['odd'], $this->theme['even']);
		$days = $this->config['newdays'];
		$isnew = date("Y-m-d H:i:s", strtotime("-$days days"));
		//$tdclass = array("odd", "even");
		//for Gold Job
		$isodd =1;
		$istr=1;
		if ( isset($this->goldjobs) && !empty($this->goldjobs)){
			foreach($this->goldjobs as $job) {
                    $comma = "";
                    $isodd = 1 - $isodd; ?>
					<div id="jl_maindiv" class="<?php echo $tdclass[$isodd];?>">
						<div id="jl_leftdiv">
								<?php 
									$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_job&vj=5&oi='.$job->aliasid.'&Itemid='.$this->Itemid; 
								?>
								<span id="jl_title"><a href="<?php echo $link?>"><?php echo $job->title;?></a></span>
								<span id="jl_jobposted">
									<?php 
										if($job->jobdays == 0) echo JText::_('JS_POSTED').': '.JText::_('JS_TODAY');
										else echo JText::_('JS_POSTED').': '.$job->jobdays.' '.JText::_('JS_DAYS_AGO');
									?></span>
								<div id="jl_button">
									<?php 
										$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=job_apply&aj=3&bi='.$job->aliasid.'&Itemid='.$this->Itemid; 
									?>
									<span class="btn_right"><a href="<?php echo $link?>" class="jl_button" ><?php echo JText::_('JS_APPLYNOW'); ?></a></span>
									<span class="btn_right"><a href="Javascript: void(0);" class="jl_button" onclick="showtellafriend('<?php echo $job->id;?>','<?php echo $job->title;?>');" ><?php echo JText::_('JS_TELL_A_FRIEND'); ?></a></span>
								</div>
						</div>
						<div id="jl_rightdiv">
							<div id="jl_data">
								<?php if($this->listjobconfig['lj_company'] == '1') {
											echo "<span class='jl_data_value'>";
											if($this->config['labelinlisting'] == '1'){
												echo "<label class='jl_data_text'>".JText::_('JS_COMPANY').": </label>";
											}
											//$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_company&vm=5&md='.$job->companyid.'&jobcat='.$job->jobcategory.'&Itemid='.$this->Itemid; 
											$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_company&vm=5&md='.$job->companyaliasid.'&jobcat='.$job->jobcategory.'&Itemid='.$this->Itemid; 
											?>
											<b><a class="jl_company_a" href="<?php echo $link?>"><?php echo $job->companyname; ?></a></b></span>
								<?php } ?>
								<?php if($this->listjobconfig['lj_category'] == '1') { 
											echo "<span class='jl_data_value'>";
											if($this->config['labelinlisting'] == '1'){
												echo "<label class='jl_data_text'>".JText::_('JS_CATEGORY').": </label>";
											}
											echo  $job->cat_title."</span>";
										} ?>
							</div>
							<div id="jl_data">
								<?php if($this->listjobconfig['lj_salary'] == '1') {
									 $salary = $job->symbol . $job->salaryfrom . ' - ' . $job->symbol . $job->salaryto . ' ' . $job->salaytype;
									if($job->salaryfrom){ 
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
									echo $job->jobtype;
									if($this->listjobconfig['lj_jobstatus'] == '1') echo ' - '.$job->jobstatus; 
									echo "</span>";
								} ?>
							</div>
							<div id="jl_lowerdiv">
								<?php echo "<label class='jl_data_text'>".JText::_('JS_LOCATION').": </label>";
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
						<div id="jl_image_gold"></div>
						<?php if ($job->created > $isnew) { ?>
							<div id="jl_image_new"></div>
						<?php } ?>
					</div>
		<?php }
		}
		//for Featured Job
		$isodd =1;
		$istr=1;
		if ( isset($this->featuredjobs) && !empty($this->featuredjobs)){
			foreach($this->featuredjobs as $job) {
                    $comma = "";
                    $isodd = 1 - $isodd; ?>
					<div id="jl_maindiv" class="<?php echo $tdclass[$isodd];?>">
						<div id="jl_leftdiv">
								<?php 
									$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_job&vj=5&oi='.$job->aliasid.'&Itemid='.$this->Itemid; 
								?>
								<span id="jl_title"><a href="<?php echo $link?>"><?php echo $job->title;?></a></span>
								<span id="jl_jobposted">
									<?php 
										if($job->jobdays == 0) echo JText::_('JS_POSTED').': '.JText::_('JS_TODAY');
										else echo JText::_('JS_POSTED').': '.$job->jobdays.' '.JText::_('JS_DAYS_AGO');
									?></span>
								<div id="jl_button">
									<?php 
										$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=job_apply&aj=3&bi='.$job->aliasid.'&Itemid='.$this->Itemid; 
									?>
									<span class="btn_right"><a href="<?php echo $link?>" class="jl_button" ><?php echo JText::_('JS_APPLYNOW'); ?></a></span>
									<span class="btn_right"><a href="Javascript: void(0);" class="jl_button" onclick="showtellafriend('<?php echo $job->id;?>','<?php echo $job->title;?>');" ><?php echo JText::_('JS_TELL_A_FRIEND'); ?></a></span>
								</div>
						</div>
						<div id="jl_rightdiv">
							<div id="jl_data">
								<?php if($this->listjobconfig['lj_company'] == '1') {
											echo "<span class='jl_data_value'>";
											if($this->config['labelinlisting'] == '1'){
												echo "<label class='jl_data_text'>".JText::_('JS_COMPANY').": </label>";
											}
											$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_company&vm=5&md='.$job->companyaliasid.'&jobcat='.$job->jobcategory.'&Itemid='.$this->Itemid; 
											?>
											<b><a class="jl_company_a" href="<?php echo $link?>"><?php echo $job->companyname; ?></a></b></span>
								<?php } ?>
								<?php if($this->listjobconfig['lj_category'] == '1') { 
											echo "<span class='jl_data_value'>";
											if($this->config['labelinlisting'] == '1'){
												echo "<label class='jl_data_text'>".JText::_('JS_CATEGORY').": </label>";
											}
											echo  $job->cat_title."</span>";
										} ?>
							</div>
							<div id="jl_data">
								<?php if($this->listjobconfig['lj_salary'] == '1') {
									 $salary = $job->symbol . $job->salaryfrom . ' - ' . $job->symbol . $job->salaryto . ' ' . $job->salaytype;
									if($job->salaryfrom){ 
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
									echo $job->jobtype;
									if($this->listjobconfig['lj_jobstatus'] == '1') echo ' - '.$job->jobstatus; 
									echo "</span>";
								} ?>
							</div>
							<div id="jl_lowerdiv">
								<?php echo "<label class='jl_data_text'>".JText::_('JS_LOCATION').": </label>";
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
						<div id="jl_image_featured"></div>
						<?php if ($job->created > $isnew) { ?>
							<div id="jl_image_new"></div>
						<?php } ?>
					</div>
                <?php	} 
                }

		$isodd =1;
		$istr=1;
		if ( isset($this->jobs) ){
			$jobdata=$this->jobs;
			$noofjobs = 1;
		foreach($this->jobs as $job) {
                    $comma = "";
                    $isodd = 1 - $isodd; ?>
					<div id="jl_maindiv" class="<?php echo $tdclass[$isodd];?>">
						<div id="jl_leftdiv">
								<?php 
									$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_job&vj=5&oi='.$job->aliasid.'&Itemid='.$this->Itemid; 
								?>
								<span id="jl_title"><a href="<?php echo $link?>"><?php echo $job->title;?></a></span>
								<span id="jl_jobposted">
									<?php 
										if($job->jobdays == 0) echo JText::_('JS_POSTED').': '.JText::_('JS_TODAY');
										else echo JText::_('JS_POSTED').': '.$job->jobdays.' '.JText::_('JS_DAYS_AGO');
									?></span>
								<div id="jl_button">
									<?php 
										$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=job_apply&aj=3&bi='.$job->aliasid.'&Itemid='.$this->Itemid; 
									?>
									<span class="btn_right"><a href="<?php echo $link?>" class="jl_button" ><?php echo JText::_('JS_APPLYNOW'); ?></a></span>
									<span class="btn_right"><a href="Javascript: void(0);" class="jl_button" onclick="showtellafriend('<?php echo $job->id;?>','<?php echo $job->title;?>');" ><?php echo JText::_('JS_TELL_A_FRIEND'); ?></a></span>
								</div>
						</div>
						<div id="jl_rightdiv">
							<div id="jl_data">
								<?php if($this->listjobconfig['lj_company'] == '1') {
											echo "<span class='jl_data_value'>";
											if($this->config['labelinlisting'] == '1'){
												echo "<label class='jl_data_text'>".JText::_('JS_COMPANY').": </label>";
											}
											$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_company&vm=5&md='.$job->companyaliasid.'&jobcat='.$job->jobcategory.'&Itemid='.$this->Itemid; 
											?>
											<b><a class="jl_company_a" href="<?php echo $link?>"><?php echo $job->companyname; ?></a></b></span>
								<?php } ?>
								<?php if($this->listjobconfig['lj_category'] == '1') { 
											echo "<span class='jl_data_value'>";
											if($this->config['labelinlisting'] == '1'){
												echo "<label class='jl_data_text'>".JText::_('JS_CATEGORY').": </label>";
											}
											echo  $job->cat_title."</span>";
										} ?>
							</div>
							<div id="jl_data">
								<?php if($this->listjobconfig['lj_salary'] == '1') {
									 $salary = $job->symbol . $job->salaryfrom . ' - ' . $job->symbol . $job->salaryto . ' ' . $job->salaytype;
									if($job->salaryfrom){ 
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
									echo $job->jobtype;
									if($this->listjobconfig['lj_jobstatus'] == '1') echo ' - '.$job->jobstatus; 
									echo "</span>";
								} ?>
							</div>
							<div id="jl_lowerdiv">
								<?php echo "<label class='jl_data_text'>".JText::_('JS_LOCATION').": </label>";
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
						<?php if ($job->created > $isnew) { ?>
							<div id="jl_image_new"></div>
						<?php } ?>
					</div>
						<?php  
							if($showgoogleadds == 1){
							if($noofjobs%$afterjobs==0) { ?>
								<table cellpadding="0" cellspacing="0" border="0" width="100%" style="<?php echo $googleaddcss;?>">
									<tr>
										<td>
											<script type="text/javascript">
												google_ad_client = "<?php echo $googleclient; ?>";
												google_ad_slot = "<?php echo $googleslot; ?>";
												google_ad_width = "<?php echo $googleaddwidth; ?>";
												google_ad_height = "<?php echo $googleaddhieght; ?>";
											</script>
											<script type="text/javascript"
												src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
											</script>
										</td>
									</tr>
								</table>
						<?php } $noofjobs++; }
					} 
                } ?>
      
	<form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=listnewestjobs&Itemid='.$this->Itemid); ?>" method="post">
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
	
	<?php if($this->config['job_rss'] == 1){ ?>
	<div id="rss">
		<a href="index.php?option=com_jsjobs&c=jsjobs&view=rss&layout=rssjobs&format=rss" target="_blank"><img width="24" height="24" src="components/com_jsjobs/images/rss.png" text="Job RSS" alt="Job RSS" /></a>
	</div>
<?php }
}else{ // no result found in this category ?>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<div id="message"><b><?php echo JText::_('JS_RESULT_NOT_FOUND');?></b></div>
	</div>
<?php
	
}

} else{ // not allowed job posting ?>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<div id="message"><b><?php echo JText::_('EA_YOU_ARE_NOT_ALLOWED_TO_VIEW');?></b></div>
	</div>
<?php

}	
}//ol
?>	
<div id="jsjobs_footer"><?php echo eval(base64_decode('aWYoJHRoaXMtPmNvbmZpZ1snZnJfY3JfdHhzaCddKSB7DQplY2hvIA0KJzx0YWJsZSB3aWR0aD0iMTAwJSIgc3R5bGU9InRhYmxlLWxheW91dDpmaXhlZDsiPg0KPHRyPjx0ZCBoZWlnaHQ9IjE1Ij48L3RkPjwvdHI+DQo8dHI+PHRkIHN0eWxlPSJ2ZXJ0aWNhbC1hbGlnbjp0b3A7IiBhbGlnbj0iY2VudGVyIj4NCjxhIGNsYXNzPSJpbWciIHRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIj48aW1nIHNyYz0iaHR0cDovL3d3dy5qb29tc2t5LmNvbS9sb2dvL2pzam9ic2NybG9nby5wbmciPjwvYT4NCjxicj4NCkNvcHlyaWdodCAmY29weTsgMjAwOCAtICcuZGF0ZSgnWScpLicsDQo8c3BhbiBpZD0idGhlbWVhbmNob3IiPiA8YSBjbGFzcz0iYW5jaG9yInRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSI+QnVydWogU29sdXRpb25zIDwvYT48L3NwYW4+PC90ZD48L3RyPg0KPC90YWJsZT4nOw0KfQ=='));?></div>
