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
 * File Name:	views/employer/tmpl/myjobs.php
 ^
 * Description: template view for my jobs
 ^
 * History:		NONE
 ^
 */

 defined('_JEXEC') or die('Restricted access');

 global $mainframe;
 $document =& JFactory::getDocument();
 $document->addStyleSheet('components/com_jsjobs/themes/'.$this->config['theme']);
 $link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid='.$this->Itemid;

?>

<script language="Javascript">
    function confirmdeletejob(){
        return confirm("<?php echo JText::_('JS_ARE_YOU_SURE_DELETE_THE_JOB'); ?>");
    }
</script>

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
					echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_MY_JOBS');
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
					<a class="<?php if($lnk[2] == 1)echo 'first'; elseif($lnk[2] == -1)echo 'last';  ?>" href="<?php echo $lnk[0]; ?>"> <?php echo $lnk[1]; ?></a>
				<?php }
			}
			?>
		</div>
		<div id="tp_heading">
			<span id="tp_headingtext">
				<span id="tp_headingtext_left"></span>
				<span id="tp_headingtext_center"><?php echo JText::_('JS_MY_JOBS');  ?></span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>

<?php
if($this->jobs){
$print = false; // check for access the page
if (isset($this->userrole->rolefor) && $this->userrole->rolefor == 1){
    $print = true;
}elseif($this->config['visitor_can_edit_job'] == 1){
    $print = true;
}

if($print == true){ // employer

	if ($this->sortlinks['sortorder'] == 'ASC')
		$img = "components/com_jsjobs/images/sort0.png";
	else
		$img = "components/com_jsjobs/images/sort1.png";

?>

<form action="index.php" method="post" name="adminForm">
	<div id="sortbylinks">
		<span id="sbl_title"><?php echo JText::_('JS_SORT_BY'); ?>&nbsp;:</span>
		<span id="sbl_links"><a href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['title']; ?>"><?php echo JText::_('JS_TITLE'); ?><?php if ($this->sortlinks['sorton'] == 'title') { ?> <img src="<?php echo $img ?>"> <?php } ?> |</a></span>
		<span id="sbl_links"><a href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['category']; ?>"><?php echo JText::_('JS_CATEGORY'); ?><?php if ($this->sortlinks['sorton'] == 'category') { ?> <img src="<?php echo $img ?>"> <?php } ?> |</a></span>
		<span id="sbl_links"><a href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['jobtype']; ?>"><?php echo JText::_('JS_JOBTYPE'); ?><?php if ($this->sortlinks['sorton'] == 'jobtype') { ?> <img src="<?php echo $img ?>"> <?php } ?> |</a></span>
		<span id="sbl_links"><a href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['jobstatus']; ?>"><?php echo JText::_('JS_JOBSTATUS'); ?><?php if ($this->sortlinks['sorton'] == 'jobstatus') { ?> <img src="<?php echo $img ?>"> <?php } ?> |</a></span>
		<span id="sbl_links"><a href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['company']; ?>"><?php echo JText::_('JS_COMPANY'); ?><?php if ($this->sortlinks['sorton'] == 'company') { ?> <img src="<?php echo $img ?>"> <?php } ?> |</a></span>
		<!--<span id="sbl_links"><a href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['country']; ?>"><?php echo JText::_('JS_COUNTRY'); ?><?php if ($this->sortlinks['sorton'] == 'country') { ?> <img src="<?php echo $img ?>"> <?php } ?> |</a></span>
		<span id="sbl_links"><a href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['salaryrange']; ?>"><?php echo JText::_('JS_SALARY_RANGE'); ?><?php if ($this->sortlinks['sorton'] == 'salaryrange') { ?> <img src="<?php echo $img ?>"> <?php } ?> |</a></span>-->
		<span id="sbl_links"><a href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['salaryto']; ?>"><?php echo JText::_('JS_SALARY_RANGE'); ?><?php if ($this->sortlinks['sorton'] == 'salaryrange') { ?> <img src="<?php echo $img ?>"> <?php } ?> |</a></span>
		<span id="sbl_links"><a href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['created']; ?>"><?php echo JText::_('JS_CREATED'); ?><?php if ($this->sortlinks['sorton'] == 'created') { ?> <img src="<?php echo $img ?>"> <?php } ?> |</a></span>
	</div>
	<?php
		$days = $this->config['newdays'];
		$isnew = date("Y-m-d H:i:s", strtotime("-$days days"));
		$tdclass = array("odd", "even");
		$isodd =1;
		$istr=1;
		if ( isset($this->jobs) ){
		foreach($this->jobs as $job) {
                    $comma = "";
                    $isodd = 1 - $isodd;?>
					<div id="jl_maindiv" class="<?php echo $tdclass[$isodd];?>">
						<div id="jl_leftdiv">
								<span id="jl_title"><?php 
								//$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_job&vj=1&oi='.$job->id.'&Itemid='.$this->Itemid;
								
								if($this->isjobsharing) $link='index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_job&vj=1&oi='.$job->saliasid.'&Itemid='.$this->Itemid; 
								else $link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_job&vj=1&oi='.$job->aliasid.'&Itemid='.$this->Itemid; 
								
								
								?>
								<a href="<?php echo $link;?>" class=''><?php echo $job->title;?></a></span>
								<span id="jl_jobposted">
									<?php 
										if($this->listjobconfig['lj_created'] == '1') {
											echo JText::_('JS_CREATED').':&nbsp;'.date($this->config['date_format'],strtotime($job->created));
										}
									 ?>
								</span>
								<div id="jl_button">
									<?php //if(!isset($job->visitor)){
									if ($job->status == '0') {
										echo '<span id="jobstatusmsg">'.JText::_('JS_STATUS') .' : </span><span id="jobstatusmsgapproval">' . JText::_('JS_APPROVALWAITING').'</span>';
									}elseif ($job->status == '-1') {
										echo '<span id="jobstatusmsg">'.JText::_('JS_STATUS') .' : </span><span id="jobstatusmsgforrejected">' . JText::_('JS_REJECTED').'</span>';
									}elseif ($job->status == '1') {
										$show_links=false;
										if($this->isjobsharing){
											if($job->serverstatus =="ok")  $show_links=true;
											else $show_links=false;
										}else{
											$show_links=true;
										}
										if($show_links){
											//check for is gold, featured or both
											$g_f_job = 0;
											if($job->isgold == 1) $g_f_job = 1; // gold job
											if($job->isfeatured == 1) $g_f_job = 2; // featured job
											if($job->isgold == 1 && $job->isfeatured == 1) $g_f_job = 3;//gold and featured job
											
											if(isset($job->visitor) && $job->visitor == 'visitor')
												$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formjob_visitor&email='.$job->contactemail.'&jobid='.$job->jobid.'&Itemid='.$this->Itemid;
											else
												$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formjob&bd='.$job->aliasid.'&Itemid='.$this->Itemid;
											if(isset($job->visitor) && $job->visitor == 'visitor'){
												if($this->config['visitor_can_edit_job'] == 1){ ?>
													<a href="<?php echo $link?>" class="employer" title="<?php echo JText::_('JS_EDIT'); ?>"><img width="17" height="17" src="components/com_jsjobs/images/edit.png" /></a>
												<?php   }
											}else{ ?>
												<a href="<?php echo $link?>" class="employer" title="<?php echo JText::_('JS_EDIT'); ?>"><img width="17" height="17" src="components/com_jsjobs/images/edit.png" /></a>
											<?php  } 

											if($this->isjobsharing) $link='index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_job&vj=1&oi='.$job->saliasid.'&Itemid='.$this->Itemid; 
											else $link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_job&vj=1&oi='.$job->aliasid.'&Itemid='.$this->Itemid; 
											
											?>
											
											<a href="<?php echo $link?>" class="employer" title="<?php echo JText::_('JS_VIEW'); ?>"><img width="17" height="17" src="components/com_jsjobs/images/view.png" /></a>
											<?php
											if(isset($job->visitor) && $job->visitor == 'visitor')
												$link = 'index.php?option=com_jsjobs&c=jsjobs&task=deletejob&email='.$job->contactemail.'&jobid='.$job->jobid.'&Itemid='.$this->Itemid;
											else
												$link = 'index.php?option=com_jsjobs&c=jsjobs&task=deletejob&bd='.$job->aliasid.'&Itemid='.$this->Itemid;
											?>
											<a href="<?php echo $link?>" class="employer" onclick=" return confirmdeletejob();"  title="<?php echo JText::_('JS_DELETE'); ?>"><img width="17" height="17" src="components/com_jsjobs/images/delete.png" /></a>
											<?php
											if(isset($this->uid) && $this->uid != 0) {
												$link = 'index.php?option=com_jsjobs&c=jsjobs&task=addtogoldjobs&oi='.$job->aliasid.'&Itemid='.$this->Itemid;
												if($g_f_job == 1 || $g_f_job ==3){ ?>
												<img width="17" height="17" src="components/com_jsjobs/images/gold.png" title="<?php echo JText::_('JS_GOLD_JOB');?>" />
												<?php }else{ ?>
												<a href="<?php echo $link?>" class="employer" title="<?php echo JText::_('JS_ADD_TO_GOLD_JOBS'); ?>"><img width="17" height="17" src="components/com_jsjobs/images/addgold.png" /></a>
												<?php } ?>
												<?php $link = 'index.php?option=com_jsjobs&c=jsjobs&task=addtofeaturedjobs&oi='.$job->aliasid.'&Itemid='.$this->Itemid;
												if($g_f_job == 2 || $g_f_job ==3){ ?>
												<img width="17" height="17" src="components/com_jsjobs/images/featured.png" title="<?php echo JText::_('JS_FEATURED_JOB');?>" />
												<?php }else{ ?>
												<a href="<?php echo $link?>" class="employer" title="<?php echo JText::_('JS_ADD_TO_FEATURED_JOBS'); ?>"><img width="17" height="17" src="components/com_jsjobs/images/addfeatured.png" /></a>
												<?php } ?>
												<a href="Javascript: void();" onclick="copyjob('<?php echo $job->id;?>');" class="employer"  title="<?php echo JText::_('JS_COPY_JOB'); ?>"><img width="17" height="17" src="components/com_jsjobs/images/copyjob.png" /></a>
												<?php $link = JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=job_appliedapplications&bd='.$job->id.'&ta=5&jacl=1&Itemid='.$this->Itemid); ?>
												<a href="<?php echo $link?>" class="employer" title="<?php echo JText::_('JS_SHORT_LIST_CANDIDATES'); ?>"><img width="17" height="17" src="components/com_jsjobs/images/shortlist.png" /></a>
											<?php }	?>
											
										<?php } ?>
										
									<?php } 
									
									//} ?>
								</div>
						</div>
						<div id="jl_rightdiv">
							<div id="jl_data">
								<?php if($this->listjobconfig['lj_company'] == '1') {
											echo "<span class='jl_data_value'>";
											if($this->config['labelinlisting'] == '1'){
												echo "<label class='jl_data_text'>".JText::_('JS_COMPANY').": </label>";
											}
											if($this->isjobsharing) $link='index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_company&vm=1&md='.$job->scompanyaliasid.'&jobcat='.$job->jobcategory.'&Itemid='.$this->Itemid;
											else $link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_company&vm=1&md='.$job->companyaliasid.'&jobcat='.$job->jobcategory.'&Itemid='.$this->Itemid;
											
											
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
										$salary = $job->symbol . $job->rangestart . ' - ' . $job->symbol . $job->rangeend. ' ' . $job->salarytypetitle  ;
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
							<?php 
								$curdate = date('Y-m-d');
								$startpublishing = date('Y-m-d',strtotime($job->startpublishing));
								$stoppublishing = date('Y-m-d',strtotime($job->stoppublishing));
								if($job->status == 1){
									if($this->isjobsharing){
										if($startpublishing <= $curdate){
											if($stoppublishing >= $curdate){
													if($job->serverstatus =="Error Save Job Userfield"){
														$jobstatus = "pending.png";
														$message="Due To Error Saving Job Userfields on Server";
													}elseif($job->serverstatus =="Error Job Saving"){
														$jobstatus = "pending.png";
														$message="Due To Error Saving Job on Server";
													}elseif($job->serverstatus =="Authentication Fail"){
														$jobstatus = "pending.png";
														$message="Due To Authentication Fail on Server";
													}elseif($job->serverstatus =="Data not post on server"){
														$jobstatus = "pending.png";
														$message="Due To Data not post on server";
													}elseif($job->serverstatus =="Curl Not Responce") {
														$jobstatus = "pending.png";
														$message="Due To Curl Not Responce";
													}elseif($job->serverstatus =="Improper job name") {
														$jobstatus = "pending.png";
														$message="Due To Improper job name";
													}elseif($job->serverstatus =="ok") {
															$jobstatus = "published_txt.png";
															$message="";
													}else{
														$jobstatus = "notpublish_txt.png";
														$message="";
													}		
											}else{
												$jobstatus = "expired_txt.png";
												$message="";
											}
										}
									}else{
												if($startpublishing <= $curdate){
													if($stoppublishing >= $curdate){
														$jobstatus = "published_txt.png";
														$message="";
													}else{
														$jobstatus = "expired_txt.png";
														$message="";
													}
												}else{
													$jobstatus = "notpublish_txt.png";
													$message="";
												}										
									}
							}elseif($job->status == -1){
									$jobstatus = "rejected.png";
									$message="";
							}else{
									$jobstatus = "pending.png";
									$message="";
								}
							?>
							<div id="mjl_txt"><img alt="Job Status" text="Job Status" src="components/com_jsjobs/images/<?php echo $jobstatus;?>"/></div>
							<div style="left: 55%;min-height: 15px;min-width: 50px; position: absolute;top: 40%;"><?php echo $message; ?></div>
							<div id="jl_lowerdiv">
								<?php 
									if($this->listjobconfig['lj_country'] == '1') {
										
										echo "<span class='jl_data_value'><label class='jl_data_text'>".JText::_('JS_LOCATION').": </label>";
										if(isset($job->city) AND  !empty($job->city)) {
											echo $job->city;

										} 
										echo "</span>";
									} ?>
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
<?php             }
				}
?>
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			<input type="hidden" name="task" value="deletejob" />
			<input type="hidden" id="id" name="id" value="" />
			<input type="hidden" name="boxchecked" value="0" />

	</form>

<form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid='.$this->Itemid); ?>" method="post">
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

} else{ // not allowed job posting ?>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<div id="message"><b><?php echo JText::_('JS_YOU_ARE_NOT_ALLOWED_TO_VIEW');?></b></div>
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
<script type="text/javascript" src="<?php echo JURI::root();?>components/com_jsjobs/js/tinybox.js"></script>
<link media="screen" rel="stylesheet" href="<?php echo JURI::root();?>components/com_jsjobs/js/style.css" />
<script type="text/javascript" language="Javascript">
	function copyjob(val){
		var xhr;
		try {  xhr = new ActiveXObject('Msxml2.XMLHTTP');   }
		catch (e){
			try {   xhr = new ActiveXObject('Microsoft.XMLHTTP');    }
			catch (e2) {
			  try {  xhr = new XMLHttpRequest();     }
			  catch (e3) {  xhr = false;   }
			}
		 }
		xhr.onreadystatechange = function(){
				if(xhr.readyState == 4 && xhr.status == 200){
					var result = xhr.responseText;
					if(result == true)
						TINY.box.show({html:"<?php echo JText::_('JS_JOB_HAS_BEEN_COPIED');?>",animate:true,boxid:'frameless',close:true});
					else
						TINY.box.show({html:"<?php echo JText::_('JS_CANNOT_ADD_NEW_JOB');?>",animate:true,boxid:'frameless',close:true});
					setTimeout(function(){window.location.reload();},3000);
				}
			}

		xhr.open("GET","index.php?option=com_jsjobs&c=jsjobs&task=getcopyjob&val="+val,true);
		xhr.send(null);
	}
</script>
