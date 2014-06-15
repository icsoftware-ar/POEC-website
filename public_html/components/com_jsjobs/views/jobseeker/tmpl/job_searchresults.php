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
 * File Name:	views/jobseeker/tmpl/jobsearchresults.php
 ^ 
 * Description: template view job search results
 ^ 
 * History:		NONE
 ^ 
 */
 
 defined('_JEXEC') or die('Restricted access');
 global $mainframe;
$document =& JFactory::getDocument();
$document->addStyleSheet('components/com_jsjobs/themes/'.$this->config['theme']);
JHTML::_('behavior.formvalidation'); 

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


 $link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=job_searchresults&Itemid='.$this->Itemid;
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

<script language="javascript">
function myValidate(f) {
		if (document.formvalidator.isValid(f)) {
        }
        else {
                alert('Search name is not acceptable.  Please retry.');
				return false;
        }
		return true;
}

function showsavesearch(val) {
	if(val == 1){
		document.getElementById('btn_savesearch').style.display = "none";
		document.getElementById('savesearch_form').style.display = "block";
	}

}
</script>
	<div id="toppanel">
		<div id="tp_header" <?php if($this->config['topimage'] == 0) echo 'style="background:none;"';?>>
			<span id="tp_title"><?php echo $this->config['title'];?></span>
			<span id="tp_curloc">
				<?php if ($this->config['cur_location'] == 1) {
						echo JText::_('JS_CUR_LOC'); ?> : <a href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobsearch&Itemid=<?php echo $this->Itemid; ?>" class="curloclnk"><?php echo JText::_('JS_SEARCH_JOB'); ?></a> > <?php echo JText::_('JS_JOB_SEARCH_RESULT');
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
		<div id="jsjobs_savesearch">
			<?php 
				if($this->canview==1){
					if ($this->searchjobconfig ['search_job_showsave'] == 1) {
						if (($this->uid) && ($this->userrole->rolefor)) {?>
							<div id="btn_savesearch">
								<input type="button" id="button" class="button" onclick="showsavesearch(1);" value="<?php echo JText::_('JS_SAVE_THIS_SEARCH'); ?>">
							</div>
					<?php }
					} ?>
			<?php
					if ($this->searchjobconfig['search_job_showsave'] == 1) {?>
					<?php if (($this->uid) && ($this->userrole->rolefor)) {?>
						<form action="index.php" method="post" name="adminForm" id="adminForm" onsubmit="return myValidate(this);">
							<div id="savesearch_form">
								<?php echo JText::_('JS_SAVE_THIS_SEARCH'); ?> &nbsp;: &nbsp;&nbsp;<input class="inputbox required" type="text" name="searchname" size="20" maxlength="30"  />
								&nbsp;&nbsp;&nbsp;<input type="submit" id="button" class="button validate" value="<?php echo JText::_('JS_SAVE'); ?>">
							</div>
							<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
							<input type="hidden" name="task" value="savejobsearch" />
						</form>	
					<?php } 
					} 
				}?>
			
		</div>
		<div id="tp_heading">
			<span id="tp_headingtext">
				<span id="tp_headingtext_left"></span>
				<span id="tp_headingtext_center"><?php echo JText::_('JS_JOB_SEARCH_RESULT');  ?></span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>

<?php
if ($this->application){

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
if ($allowed == true) { 

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
		if($this->listjobconfig['lj_category'] == '1') { ?>	
			<span id="sbl_links"><a href="<?php echo $link?>&sortby=<?php echo $this->sortlinks['category']; ?>"><?php echo JText::_('JS_CATEGORY'); ?><?php if ($this->sortlinks['sorton'] == 'category') { ?> <img src="<?php echo $img ?>"> <?php } ?> |</a></span>
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
 /*  if($this->listjobconfig['lj_joblistingstyle'] == 'classic'){ ?>
                <table  cellpadding="0" cellspacing="0" border="0" width="100%">
		<?php 
		$days = $this->config['newdays'];
		$isnew = date("Y-m-d H:i:s", strtotime("-$days days"));
		$tdclass = array($this->theme['odd'], $this->theme['even']);
		$istr=1;
		$isodd =1;
		foreach($this->application as $job)	{ 
		$isodd = 1 - $isodd; ?>
		<tr height="20" class="<?php echo $tdclass[$isodd]; ?>" > <td colspan="5">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr><td height="3"></td></tr>
				<?php if($this->listjobconfig['lj_title']=='1'){ ?>

				<tr>
					<td class="maintext">&nbsp;<strong><?php echo JText::_('JS_TITLE'); ?>	</strong></td>
					<td class="maintext" colspan="3"><?php echo $job->title;
					if ($job->created > $isnew)
						echo "<font color='red'> ".JText::_('JS_NEW')." </font>";
					?>
					</td>
				<?php }?>
				<?php if($istr){ echo '</tr><tr>';   $istr = 2;} ?>
				<?php	if($this->listjobconfig['lj_category'] == '1') { $istr++ ; ?>
					<td class="maintext" width="20%">&nbsp;<strong><?php echo JText::_('JS_CATEGORY'); ?>	</strong></td>
					<td class="maintext" width="30%"><?php echo $job->cat_title; ?></td>
				<?php	} ?>
				<?php if($istr == 4){ echo '</tr><tr>';   $istr = 2;} ?>
				<?php	if($this->listjobconfig['lj_jobtype'] == '1') { $istr++ ; ?>
					<td class="maintext" width="20%">&nbsp;<strong><?php echo JText::_('JS_JOBTYPE'); ?>	</strong></td>
					<td class="maintext" width="30%"><?php echo $job->jobtypetitle; ?></td>
				<?php	} ?>
				<?php if($istr == 4){ echo '</tr><tr>';   $istr = 2;} ?>
					<?php if($this->listjobconfig['lj_jobstatus'] == '1')   { $istr++; ?>
					<td class="maintext" width="20%">&nbsp;<strong><?php echo JText::_('JS_JOBSTATUS'); ?>	</strong></td>
					<td class="maintext" width="30%"><?php echo $job->jobstatustitle; ?></td>
				<?php } ?>
				<?php if($istr == 4){ echo '</tr><tr>';   $istr = 2;} ?>
					<?php if($this->listjobconfig['lj_company'] == '1') { $istr++; ?>
					<td class="maintext">&nbsp;<strong><?php echo JText::_('JS_COMPANY'); ?>	</strong></td>
					<td class="maintext">
						<?php $link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_company&vm=5&md='.$job->companyid.'&jobcat='.$job->jobcategory.'&Itemid='.$this->Itemid; ?>
						<a href="<?php echo $link?>"><strong><?php echo $job->companyname; ?></strong></a>
					</td>
					<?php	} ?>
				<?php if($istr == 4){ echo '</tr><tr>';  $istr = 2;} ?>
					<?php if($this->listjobconfig['lj_companysite'] == '1') { $istr++; ?>
					<td class="maintext">&nbsp;<strong><?php echo JText::_('JS_COMPANYURL'); ?>	</strong></td>
					<td class="maintext"><a class="jplnks" href='<?php echo $job->url; ?>' target='_blank'><?php echo $job->url; ?></a></td>
				<?php	} ?>
				<?php if($istr == 4){ echo '</tr><tr>';  $istr = 2;} ?>
				<?php	if($this->listjobconfig['lj_country'] == '1') {  $istr++; ?>
					<?php if($job->countryname != '') { ?>
						<td >&nbsp;<strong><?php echo JText::_('JS_COUNTRY'); ?>	</strong></td>
						<td ><?php echo $job->countryname; ?></td>
					<?php } ?>
				<?php	} ?>
				<?php if($istr == 4){ echo '</tr><tr>';  $istr = 2;} ?>
				<?php	if($this->listjobconfig['lj_state'] == '1') {  $istr++; ?>
					<?php if(isset($job->statename)){ ?>
					<?php if($job->statename != '') { ?>
						<td >&nbsp;<strong><?php echo JText::_('JS_STATE'); ?>	</strong></td>
						<td ><?php echo $job->statename; ?></td>
					<?php }}else{ ?>
						<td >&nbsp;<strong><?php echo JText::_('JS_STATE'); ?>	</strong></td>
						<td ><?php echo $job->state; ?></td>
					<?php } ?>
				<?php	} ?>
				<?php if($istr == 4){ echo '</tr><tr>';  $istr = 2;} ?>
				<?php	if($this->listjobconfig['lj_county'] == '1') {  $istr++; ?>
					<?php if(isset($job->countyname) && $job->countyname != '') { ?>
						<td >&nbsp;<strong><?php echo JText::_('JS_COUNTY'); ?>	</strong></td>
						<td ><?php echo $job->countyname; ?></td>
					<?php }else{ ?>
						<td >&nbsp;<strong><?php echo JText::_('JS_COUNTY'); ?>	</strong></td>
						<td ><?php echo $job->county; ?></td>
					<?php } ?>
				<?php	} ?>
				<?php if($istr == 4){ echo '</tr><tr>';  $istr = 2;} ?>
				<?php	if($this->listjobconfig['lj_city'] == '1') {  $istr++; ?>
					<?php if(isset($job->cityname) && $job->cityname != '') { ?>
						<td >&nbsp;<strong><?php echo JText::_('JS_CITY'); ?>	</strong></td>
						<td ><?php echo $job->cityname; ?></td>
					<?php }else{ ?>
						<td >&nbsp;<strong><?php echo JText::_('JS_CITY'); ?>	</strong></td>
						<td ><?php echo $job->city; ?></td>
					<?php } ?>
				<?php	} ?>
				<?php if($istr == 4){ echo '</tr><tr>';  $istr = 2;} ?>

				<?php	  if($this->listjobconfig['lj_salary'] == '1') { $istr++; ?>
								<td >&nbsp;<strong><?php echo JText::_('JS_SALARY_RANGE'); ?>	</strong></td>
								<td ><?php $salary = $this->config['currency'] . $job->salaryfrom . ' - ' . $this->config['currency'] . $job->salaryend . ' ' . $job->salaytype;
								if($job->salaryfrom){
									echo $salary;
								} ?>
							</td>
				<?php	} ?>
			<?php if($istr == 4){ echo '</tr><tr>';   $istr = 2;} ?>
					<?php if($this->listjobconfig['lj_created'] == '1') { $istr++; ?>
					<td class="maintext">&nbsp;<strong><?php echo JText::_('JS_DATEPOSTED'); ?>	</strong></td>
					<td class="maintext">
						<?php 
/*
							$publisheddate = date("Y-m-d",strtotime($job->startpublishing));
							$currentdate = date("Y-m-d");
							
							
							$date1 = new DateTime($currentdate);
							$date2 = new DateTime($publisheddate);
							$interval = $date1->diff($date2);
							$years = $interval->y;$months = $interval->m;$days = $interval->d;
							$dateposted = '';
							if($years != 0) if($years == 1) $dateposted = $years.' '.JText::_('JS_YEAR');else $dateposted = $years.' '.JText::_('JS_YEARS');
							if($months != 0) if($months == 1) $dateposted .= $months.' '.JText::_('JS_MONTH');else $dateposted .= $months.' '.JText::_('JS_MONTHS');
							if($days != 0) if($days == 1) $dateposted .= $days.' '.JText::_('JS_DAY');else $dateposted .= $days.' '.JText::_('JS_DAYS');
							echo $dateposted.' '.JText::_('JS_AGO');
							*//*
							if($job->jobdays == 0) echo JText::_('JS_POSTED').' '.JText::_('JS_TODAY');
							else echo JText::_('JS_POSTED').' '.$job->jobdays.' '.JText::_('JS_DAYS_AGO');
						?>
					</td>
					<?php	} ?>
					<?php if($istr == 4){ echo '</tr><tr>';  $istr = 2;} ?>
					<?php if($this->listjobconfig['lj_noofjobs'] == '1') { $istr++; ?>
					<?php if ($job->noofjobs != 0){ ?>
					<td class="maintext">&nbsp;<strong><?php echo JText::_('JS_NOOFJOBS'); ?>	</strong></td>
					<td class="maintext"><?php echo $job->noofjobs; ?></td>
					<?php	} ?>
					<?php } ?>
				<?php if($istr == 4) echo '</tr>'; else echo '<td></td></tr>'; ?>
				<tr>
					<td></td>
					<td class="maintext" align="right">
						<?php $link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_job&vj=5&oi='.$job->id.'&Itemid='.$this->Itemid; ?>
						<a href="<?php echo $link?>" class="pageLink"><strong><?php echo JText::_('JS_VIEW'); ?></strong></a>
					&nbsp;&nbsp;</td>
					<td class="maintext" align="left">&nbsp;&nbsp;
						<?php $link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=job_apply&aj=3&bi='.$job->id.'&Itemid='.$this->Itemid; ?>
						<a href="<?php echo $link?>" class="pageLink"><strong><?php echo JText::_('JS_APPLYNOW'); ?></strong></a>
					</td>
					<td>
						<a href="Javascript: void(0);" onclick="showtellafriend('<?php echo $job->id;?>','<?php echo $job->title;?>');" ><img width="32px" height="32px" src="components/com_jsjobs/images/tell_a_friend.png"/></a>
					</td>
				</tr>
				<tr><td height="3"></td></tr>
			</table>
		</td></tr>
                <?php  /*
					if($showgoogleadds == 1){
                    if($noofjobs%$afterjobs==0) { ?>
					<tr height="20" class="<?php echo $tdclass[$isodd]; ?>" > <td colspan="5">
						<table cellpadding="0" cellspacing="0" border="0" width="100%">
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
					</td></tr>
                <?php } $noofjobs++; } *//*?>
		<?php 
		}	
		 ?>		
	</table>
<?php } else if($this->listjobconfig['lj_joblistingstyle'] == 'july2011'){ */?>
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
									//$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_job&vj=3&oi='.$job->id.'&Itemid='.$this->Itemid; 
									$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_job&vj=3&oi='.$job->aliasid.'&Itemid='.$this->Itemid; 
								?>
								<span id="jl_title"><a href="<?php echo $link?>"><?php echo $job->title;?></a></span>
								<span id="jl_jobposted">
									<?php 
										if($job->jobdays == 0) echo JText::_('JS_POSTED').': '.JText::_('JS_TODAY');
										else echo JText::_('JS_POSTED').': '.$job->jobdays.' '.JText::_('JS_DAYS_AGO');
									?></span>
								<div id="jl_button">
									<?php 
										//$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=job_apply&aj=2&bi='.$job->id.'&Itemid='.$this->Itemid; 
										$link = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=job_apply&aj=2&bi='.$job->aliasid.'&Itemid='.$this->Itemid; 
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
											//$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_company&vm=3&md='.$job->companyid.'&jobcat='.$job->jobcategory.'&Itemid='.$this->Itemid; 
											$link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=view_company&vm=3&md='.$job->companyaliasid.'&jobcat='.$job->jobcategory.'&Itemid='.$this->Itemid; 
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
										$salary = $job->symbol . $job->salaryfrom . ' - ' . $job->symbol . $job->salaryend . ' ' . $job->salaytype;
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
									echo $job->jobtypetitle;
									if($this->listjobconfig['lj_jobstatus'] == '1') echo ' - '.$job->jobstatustitle; 
									echo "</span>";
								} ?>
							</div>
							<div id="jl_lowerdiv">
								<?php echo "<label class='jl_data_text'>".JText::_('JS_LOCATION').": </label>";
									if($this->listjobconfig['lj_city'] == '1') {
										if(isset($job->city) AND  !empty($job->city)) {
											echo "<span class='jl_data_value'>".$job->city."</span>";
										}		
										
									}
									/*$loc = "";
									if($this->listjobconfig['lj_city'] == '1') {
										if(isset($job->cityname) && $job->cityname != '') {
											$loc .= $comma.$job->cityname; $comma = ", " ;
										}elseif($job->city){
											$loc .= $comma.$job->city; $comma = ", " ;
										} 
									} 
									/*if($this->listjobconfig['lj_county'] == '1') {
										if(isset($job->countyname) && $job->countyname != '') {
											$loc .= $comma.$job->countyname; $comma = ", " ;
										}elseif($job->county != ''){
											$loc .= $comma.$job->county; $comma = ", " ;
										}
									}
									if($this->listjobconfig['lj_state'] == '1') {
										if(isset($job->statename) && $job->statename != '') {
											$loc .= $comma.$job->statename; $comma = ", " ;
										}elseif($job->state){
											$loc .= $comma.$job->state; $comma = ", " ;
										}
									}
									if($this->listjobconfig['lj_country'] == '1') {
										if($job->countryname != '') {
											$loc .= $comma.$job->countryname;
										}
									}
									echo "<span class='jl_data_value'>".$loc."</span>";
									*/ 
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

<form action="<?php echo JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=job_searchresults&Itemid='.$this->Itemid); ?>" method="post">
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
	<?php if($this->config['job_rss'] == 1){ ?>
		<div id="rss">
			<a href="index.php?option=com_jsjobs&c=jsjobs&view=rss&layout=rssjobs&format=rss" target="_blank"><img width="24" height="24" src="components/com_jsjobs/images/rss.png" text="Job RSS" alt="Job RSS" /></a>
		</div>
	<?php } ?>
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
?>	


<script language="javascript">
showsavesearch(0); 
</script>
<?php
}//ol
?>
<div id="jsjobs_footer"><?php echo eval(base64_decode('aWYoJHRoaXMtPmNvbmZpZ1snZnJfY3JfdHhzaCddKSB7DQplY2hvIA0KJzx0YWJsZSB3aWR0aD0iMTAwJSIgc3R5bGU9InRhYmxlLWxheW91dDpmaXhlZDsiPg0KPHRyPjx0ZCBoZWlnaHQ9IjE1Ij48L3RkPjwvdHI+DQo8dHI+PHRkIHN0eWxlPSJ2ZXJ0aWNhbC1hbGlnbjp0b3A7IiBhbGlnbj0iY2VudGVyIj4NCjxhIGNsYXNzPSJpbWciIHRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIj48aW1nIHNyYz0iaHR0cDovL3d3dy5qb29tc2t5LmNvbS9sb2dvL2pzam9ic2NybG9nby5wbmciPjwvYT4NCjxicj4NCkNvcHlyaWdodCAmY29weTsgMjAwOCAtICcuZGF0ZSgnWScpLicsDQo8c3BhbiBpZD0idGhlbWVhbmNob3IiPiA8YSBjbGFzcz0iYW5jaG9yInRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSI+QnVydWogU29sdXRpb25zIDwvYT48L3NwYW4+PC90ZD48L3RyPg0KPC90YWJsZT4nOw0KfQ=='));?></div>
