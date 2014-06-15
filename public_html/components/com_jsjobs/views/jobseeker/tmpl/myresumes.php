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
 * File Name:	views/jobseeker/tmpl/myresume.php
 ^ 
 * Description: template view for my resume
 ^ 
 * History:		NONE
 ^ 
 */
 
 defined('_JEXEC') or die('Restricted access');

 global $mainframe;
 $document =& JFactory::getDocument();
 $document->addStyleSheet('components/com_jsjobs/themes/'.$this->config['theme']);

 $link = "index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myresumes&Itemid=".$this->Itemid;
 $resumecatlink = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobcat&Itemid='.$this->Itemid;
?>
<script language=Javascript>
    function confirmdeleteresume(){
        return confirm("<?php echo JText::_('JS_ARE_YOU_SURE_DELETE_THE_RESUME'); ?>");
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
					echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_MY_RESUME');
				} ?>
			</span>
		</div>
		<div id="tp_links">
			<?php 
			$cutomlink = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=formresume&vm=2&Itemid='.$this->Itemid;
			$cutomlinktext = JText::_('JS_ADD_RESUME');
			$count = 0;
			if (sizeof($this->jobseekerlinks) != 0){
				foreach($this->jobseekerlinks as $lnk)	{ 
				 if ($this->config['tmenu_jsaddresume'] == 1) {
					if ($count == 1) { ?>
						<a class="<?php if($lnk[2] == 1)echo 'first'; elseif($lnk[2] == -1)echo 'last';  ?>" href="<?php echo $cutomlink; ?>"> <?php echo $cutomlinktext; ?></a>
					<?php }
				 }	?>
					<a class="<?php if($lnk[2] == 1)echo 'first'; elseif($lnk[2] == -1)echo 'last';  ?>" href="<?php echo $lnk[0]; ?>"> <?php echo $lnk[1]; ?></a>
				<?php $count++;	
				}
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
				<span id="tp_headingtext_center"><?php echo JText::_('JS_MY_RESUME');  ?></span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>
<?php
		if ($this->resumes){

if ($this->userrole->rolefor == 2) { // job seeker

	if ($this->sortlinks['sortorder'] == 'ASC')
		$img = "components/com_jsjobs/images/sort0.png";
	else
		$img = "components/com_jsjobs/images/sort1.png";
?>
	<form action="index.php" method="post" name="adminForm">
	<div id="sortbylinks">
		<span id="sbl_title"><?php echo JText::_('JS_SORT_BY'); ?>&nbsp;:</span>
		<span id="sbl_links"><a href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['application_title']; ?>"><?php echo JText::_('JS_TITLE'); ?><?php if ($this->sortlinks['sorton'] == 'application_title') { ?> <img src="<?php echo $img ?>"> <?php } ?> |</a></span>
		<span id="sbl_links"><a href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['jobtype']; ?>"><?php echo JText::_('JS_JOBTYPE'); ?><?php if ($this->sortlinks['sorton'] == 'jobtype') { ?> <img src="<?php echo $img ?>"> <?php } ?> |</a></span>
		<span id="sbl_links"><a href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['salaryrange']; ?>"><?php echo JText::_('JS_SALARY_RANGE'); ?><?php if ($this->sortlinks['sorton'] == 'salaryrange') { ?> <img src="<?php echo $img ?>"> <?php } ?> |</a></span>
		<span id="sbl_links"><a href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['created']; ?>"><?php echo JText::_('JS_DATEPOSTED'); ?><?php if ($this->sortlinks['sorton'] == 'created') { ?> <img src="<?php echo $img ?>"> <?php } ?> |</a></span>
	</div>
	<?php
		$tdclass = array($this->theme['odd'], $this->theme['even']);
		$isnew = date("Y-m-d H:i:s", strtotime("-".$this->config['newdays']." days"));
		//$tdclass = array("odd", "even");
		$isodd =1;
		foreach($this->resumes as $resume)	{
				$comma = "";
				$isodd = 1 - $isodd; ?>
					<div id="rl_maindiv" class="<?php echo $tdclass[$isodd];$isodd = 1 - $isodd;?>">
						<div id="rl_imagediv" name="rl_imagediv">
							<?php 
								if($resume->photo != '') { ?>
									<img  name="rl_image" src="<?php echo $this->config['data_directory']."/data/jobseeker/resume_".$resume->id."/photo/".$resume->photo;?>" title='picture'  alt='Resume Photo'/>
								<?php }else{
									echo '<img  name="rl_image" src="components/com_jsjobs/images/jsjobs_logo.png" title="picture"  alt="Default"/>';
								} 
							?>
						</div>
						<div id="rl_datadiv">
							<div id="rl_title">
								<span id="rl_title_span"><?php echo $resume->first_name.' '.$resume->last_name; ?></span>
								<span id="rl_title_application">(<?php echo $resume->application_title; ?>)</span>
							</div>
							<div id="rl_buttondatediv">
								<div id="rl_button">
									<?php 
										$g_f_resume = 0;
										if($resume->isgold == 1) $g_f_resume = 1; // gold resume
										if($resume->isfeatured == 1) $g_f_resume = 2; // featured resume
										if($resume->isgold == 1 && $resume->isfeatured == 1) $g_f_resume = 3;//gold and featured resume
									
										if ($resume->status == '0') {
											echo '<span id="resumestatusmsg">'.JText::_('JS_STATUS') .' : </span><span id="resumestatusmsgapprove">' . JText::_('JS_APPROVALWAITING').'</span>';
										}elseif ($resume->status == '-1') {
											echo '<span id="resumestatusmsg">'.JText::_('JS_STATUS') .' : </span><span id="resumestatusmsgreject">' . JText::_('JS_NOTAPPROVED').'</span>';
										}elseif ($resume->status == '1') {
											$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=formresume&vm=1&rd='.$resume->aliasid.'&Itemid='.$this->Itemid; 
											?>
											<span id="icon"><a class="icon" href="<?php echo $link?>" title="<?php echo JText::_('JS_EDIT'); ?>"><img width="17" height="17" src="components/com_jsjobs/images/edit.png" /></a></span>

											<?php 
													$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=view_resume&vm=1&rd='.$resume->aliasid.'&Itemid='.$this->Itemid; 
												
											?>
											<span id="icon"><a class="icon" href="<?php echo $link?>" title="<?php echo JText::_('JS_VIEW'); ?>"><img width="17" height="17" src="components/com_jsjobs/images/view.png" /></a></span>
											
											<?php 
												$link = 'index.php?option=com_jsjobs&c=jsjobs&task=deleteresume&rd='.$resume->aliasid.'&Itemid='.$this->Itemid; 
											?>
											<span id="icon"><a href="<?php echo $link?>" onclick=" return confirmdeleteresume();" class="icon" title="<?php echo JText::_('JS_DELETE'); ?>"><img width="17" height="17" src="components/com_jsjobs/images/delete.png" /></a></span>
											<?php 
												$link = 'index.php?option=com_jsjobs&c=jsjobs&task=addtogoldresumes&rd='.$resume->aliasid.'&Itemid='.$this->Itemid; 
												?>
											<span id="icon">
												<?php if($g_f_resume == 1 || $g_f_resume == 3){ ?>
												<img width="15" height="15" src="components/com_jsjobs/images/gold.png" title="<?php echo JText::_('JS_GOLD_RESUME');?>"/>
												<?php }else{ ?>
												<a href="<?php echo $link?>" class="icon" title="<?php echo JText::_('JS_ADD_TO_GOLD_RESUMES'); ?>"><img width="17" height="17" src="components/com_jsjobs/images/addgold.png" /></a>
												<?php } ?>
											</span>
											<?php 
												$link = 'index.php?option=com_jsjobs&c=jsjobs&task=addtofeaturedresumes&rd='.$resume->aliasid.'&Itemid='.$this->Itemid; 
											?>
											<span id="icon">
												<?php if($g_f_resume == 2 || $g_f_resume == 3){ ?>
												<img width="15" height="15" src="components/com_jsjobs/images/featured.png" title="<?php echo JText::_('JS_FEATURED_RESUME');?>"/>
												<?php }else{ ?>
												<a href="<?php echo $link?>" class="icon" title="<?php echo JText::_('JS_ADD_TO_FEATURED_RESUMES'); ?>"><img width="17" height="17" src="components/com_jsjobs/images/addfeatured.png" /></a>
												<?php } ?>
											</span>
									<?php } ?>
								</div>
								<div id="rl_emaildiv"><?php echo $resume->email_address; ?></div>
							</div>
							<div id="jl_data">
								<?php 
									echo "<span class='rl_data_value'><label class='rl_data_text'>".JText::_('JS_TOTAL_EXPERIENCE').":&nbsp;</label>";
									if(!empty($resume->total_experience)) echo $resume->total_experience;
									else echo JText::_('JS_NO_WORK_EXPERIENCE');
									echo "</span>";
									echo "<span class='rl_data_value'><label class='rl_data_text'>".JText::_('JS_CATEGORY').":&nbsp;</label>";
									echo  $resume->cat_title."</span>";
								 ?>
							</div>
							<div id="jl_data">
								<?php
									$salary = $resume->symbol. $resume->rangestart . ' - ' . $resume->symbol . $resume->rangeend .' '. JText::_('JS_PERMONTH');
									echo "<span class='rl_data_value'><label class='rl_data_text'>".JText::_('JS_SALARY').": </label>";
									echo $salary."</span>";
									
									echo "<span class='rl_data_value'><label class='rl_data_text'>".JText::_('JS_JOB_TYPE').": </label>";
									echo $resume->jobtypetitle."</span>";
								?>
							</div>
							<div id="jl_lowerdiv">
								<?php
									$address = '';
									$comma='';
									if($resume->cityname != ''){
										$address = $comma.$resume->cityname; $comma = " ," ;
									}elseif($resume->employer_city != ''){
										$address .= $comma.$resume->employer_city; $comma = " ," ;
									}

									if($resume->statename != ''){
										$address .= $comma.$resume->statename; $comma = " ," ;
									}elseif($resume->employer_state != ''){
										$address .= $comma.$resume->employer_state; $comma = " ,";
									}
									
									if($resume->countryname != '') $address .= $comma.$resume->countryname;

									echo "<span class='rl_data_value'><label class='rl_data_text'>".JText::_('JS_ADDRESS').": </label>";
									echo $address."</span>";
								?>
								<span class="jl_no_of_jobs">
									<?php 
										echo "<span class='rl_data_value'>".JText::_('JS_CREATED').": ";
										echo date($this->config['date_format'],strtotime($resume->create_date))."</span>";
									?>
								</span>
							</div>
						</div>
					</div>
		<?php
		}
		 ?>
			<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
			<input type="hidden" name="task" value="deleteresume" />
			<input type="hidden" id="id" name="id" value="" />
			<input type="hidden" name="boxchecked" value="0" />

	</form>

	<form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myresumes&Itemid='.$this->Itemid); ?>" method="post">
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
<script type="text/javascript" language="javascript">
	function setLayoutSize(){
		var totalwidth = document.getElementById("rl_maindiv").offsetWidth;
		var per_width = (totalwidth*0.23)-10;
		var totalimagesdiv = document.getElementsByName("rl_imagediv").length;
		for(var i = 0;i<totalimagesdiv;i++){
			document.getElementsByName("rl_imagediv")[i].style.minWidth = per_width+"px";
			document.getElementsByName("rl_imagediv")[i].style.width = per_width+"px";
		}
		var totalimages = document.getElementsByName("rl_image").length;
		for(var i = 0;i<totalimages;i++){
			//document.getElementsByName("rl_image")[i].style.minWidth = per_width+"px";
			document.getElementsByName("rl_image")[i].style.width = per_width+"px";
			document.getElementsByName("rl_image")[i].style.maxWidth = per_width+"px";
		}
	}
	setLayoutSize();
</script>
