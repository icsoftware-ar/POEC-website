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
 * File Name:	views/employer/tmpl/mycompanies.php
 ^ 
 * Description: template view for my companies
 ^ 
 * History:		NONE
 ^ 
 */
 
 defined('_JEXEC') or die('Restricted access');
 
 global $mainframe;
 $link = 'index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=my_stats&Itemid='.$this->Itemid;
  $document =& JFactory::getDocument();
   $document->addStyleSheet('components/com_jsjobs/themes/'.$this->config['theme']);
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
					echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_MY_STATS');
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
				<span id="tp_headingtext_center"><?php echo JText::_('JS_STATS');  ?></span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>
<?php
if ($this->userrole->rolefor == 1) { // employer
	$print = 1;
    $isodd =1;
    $tdclass = array($this->theme['odd'], $this->theme['even']);
if(isset($this->package) && $this->package == false) $print= 0;
?>

		<div id="stats_maindiv" >
			<div class="stats_mystats">
				<div class="sectionheadline stats_sectiontitle"><?php echo JText::_('JS_MY_STATS'); ?></div>	
					<?php $isodd = 1 - $isodd; ?>
					<span class="stats_data_title <?php echo $tdclass[$isodd]; ?>">	<?php echo JText::_('JS_COMPANIES'); ?></span>
					<span class="stats_data_value <?php echo $tdclass[$isodd]; ?>">	<?php echo $this->totalcompanies; ?></span>
					<?php $isodd = 1 - $isodd; ?>
					<span class="stats_data_title <?php echo $tdclass[$isodd]; ?>">	<?php echo JText::_('JS_JOBS'); ?></span>
					<span class="stats_data_value <?php echo $tdclass[$isodd]; ?>">	<?php echo $this->totaljobs; ?></span>
					<?php $isodd = 1 - $isodd; ?>
					<span class="stats_data_title <?php echo $tdclass[$isodd]; ?>">	<?php echo JText::_('JS_GOLD_COMPANIES'); ?></span>
					<span class="stats_data_value <?php echo $tdclass[$isodd]; ?>">	<?php echo $this->totalgoldcompanies; ?></span>
					<?php $isodd = 1 - $isodd; ?>
					<span class="stats_data_title <?php echo $tdclass[$isodd]; ?>">	<?php echo JText::_('JS_FEATURED_COMPANIES'); ?></span>
					<span class="stats_data_value <?php echo $tdclass[$isodd]; ?>">	<?php echo $this->totalfeaturedcompanies; ?></span>
					<?php $isodd = 1 - $isodd; ?>
					<span class="stats_data_title <?php echo $tdclass[$isodd]; ?>">	<?php echo JText::_('JS_GOLD_JOBS'); ?></span>
					<span class="stats_data_value <?php echo $tdclass[$isodd]; ?>">	<?php echo $this->totalgoldjobs; ?></span>
					<?php $isodd = 1 - $isodd; ?>
					<span class="stats_data_title <?php echo $tdclass[$isodd]; ?>">	<?php echo JText::_('JS_FEATURED_JOBS'); ?></span>
					<span class="stats_data_value <?php echo $tdclass[$isodd]; ?>">	<?php echo $this->totalfeaturedcompanies; ?></span>
					<?php $isodd = 1 - $isodd; ?>
					<span class="stats_data_title <?php echo $tdclass[$isodd]; ?>">	<?php echo JText::_('JS_FEATURED_COMPANIES'); ?></span>
					<span class="stats_data_value <?php echo $tdclass[$isodd]; ?>">	<?php echo $this->totalfeaturedjobs; ?></span>
				<div id="stats_mystats_left">
					<div class="sectionheadline stats_sectiontitle"><?php echo JText::_('JS_JOBS'); ?></div>
					<?php $isodd = 1 - $isodd; ?>
					<span class="stats_mystats_left_right_title <?php echo $tdclass[$isodd]; ?>">	<?php echo JText::_('JS_JOBS_ALLOW'); ?></span>
					<span class="stats_mystats_left_right_value <?php echo $tdclass[$isodd]; ?>">
						<?php 
							if($this->ispackagerequired != 1){ 
									echo JText::_('JS_UNLIMITED');
							}elseif($this->jobsallow == -1){ 
								echo JText::_('JS_UNLIMITED');
							}else echo $this->jobsallow; ?>
					</span>
					<?php $isodd = 1 - $isodd; ?>
					<span class="stats_mystats_left_right_title <?php echo $tdclass[$isodd]; ?>">	<?php echo JText::_('JS_PUBLISHED_JOBS'); ?></span>
					<span class="stats_mystats_left_right_value <?php echo $tdclass[$isodd]; ?>">
						<?php  echo $this->publishedjob; ?>
					</span>
					<?php $isodd = 1 - $isodd; ?>
					<span class="stats_mystats_left_right_title <?php echo $tdclass[$isodd]; ?>">	<?php echo JText::_('JS_EXPIRED_JOBS'); ?></span>
					<span class="stats_mystats_left_right_value <?php echo $tdclass[$isodd]; ?>">
						<?php  echo $this->expiredjob; ?>
					</span>
					<?php $isodd = 1 - $isodd; ?>
					<span class="stats_mystats_left_right_title <?php echo $tdclass[$isodd]; ?>">	<?php echo JText::_('JS_AVAILABLE_JOBS'); ?></span>
					<span class="stats_mystats_left_right_value <?php echo $tdclass[$isodd]; ?>">
						<?php 
							if($this->ispackagerequired != 1){ 
								echo JText::_('JS_UNLIMITED');
							}elseif($this->jobsallow == -1){ 
								echo JText::_('JS_UNLIMITED');
							}else{ 
								$available_jobs=$this->jobsallow-$this->totaljobs; 
								echo $available_jobs;
							} 
						?>
					</span>
					<?php $isodd = 1 - $isodd; ?>
					<span class="stats_mystats_left_right_title <?php echo $tdclass[$isodd]; ?>">	<?php echo JText::_('JS_GOLD_JOBS_ALLOW'); ?></span>
					<span class="stats_mystats_left_right_value <?php echo $tdclass[$isodd]; ?>">
						<?php 
							if($this->ispackagerequired != 1){ 
									echo JText::_('JS_UNLIMITED');
							}else if($this->goldjobsallow == -1){
								echo JText::_('JS_UNLIMITED');
							}else echo $this->goldjobsallow; ?>
					</span>
					<?php $isodd = 1 - $isodd; ?>
					<span class="stats_mystats_left_right_title <?php echo $tdclass[$isodd]; ?>">	<?php echo JText::_('JS_PUBLISHED_GOLD_JOBS'); ?></span>
					<span class="stats_mystats_left_right_value <?php echo $tdclass[$isodd]; ?>">
						<?php echo $this->publishedgoldjob;	?>
					</span>
					<?php $isodd = 1 - $isodd; ?>
					<span class="stats_mystats_left_right_title <?php echo $tdclass[$isodd]; ?>">	<?php echo JText::_('JS_EXPIRED_GOLD_JOBS'); ?></span>
					<span class="stats_mystats_left_right_value <?php echo $tdclass[$isodd]; ?>">
						<?php echo $this->expiregoldjob;	?>
					</span>
					<?php $isodd = 1 - $isodd; ?>
					<span class="stats_mystats_left_right_title <?php echo $tdclass[$isodd]; ?>">	<?php echo JText::_('JS_AVAILABLE_GOLD_JOBS'); ?></span>
					<span class="stats_mystats_left_right_value <?php echo $tdclass[$isodd]; ?>">
						<?php 
							if($this->ispackagerequired != 1){ 
								echo JText::_('JS_UNLIMITED');
							}elseif($this->goldjobsallow == -1){ 
								echo JText::_('JS_UNLIMITED');
							}else{ 
								$available_goldjobs=$this->goldjobsallow-$this->totalgoldjobs; 
								echo $available_goldjobs;
							} 
						?>
					</span>
					<?php $isodd = 1 - $isodd; ?>
					<span class="stats_mystats_left_right_title <?php echo $tdclass[$isodd]; ?>">	<?php echo JText::_('JS_FEATURED_JOBS_ALLOW'); ?></span>
					<span class="stats_mystats_left_right_value <?php echo $tdclass[$isodd]; ?>">
						<?php 
							if($this->ispackagerequired != 1){ 
									echo JText::_('JS_UNLIMITED');
							}elseif($this->featuredjobsallow == -1){
								echo JText::_('JS_UNLIMITED');
							}else echo $this->featuredjobsallow; ?>						
					</span>
					<?php $isodd = 1 - $isodd; ?>
					<span class="stats_mystats_left_right_title <?php echo $tdclass[$isodd]; ?>">	<?php echo JText::_('JS_PUBLISHED_FEATURED_JOBS'); ?></span>
					<span class="stats_mystats_left_right_value <?php echo $tdclass[$isodd]; ?>"> 
						<?php echo $this->publishedfeaturedjob; ?>
					</span>
					<?php $isodd = 1 - $isodd; ?>
					<span class="stats_mystats_left_right_title <?php echo $tdclass[$isodd]; ?>">	<?php echo JText::_('JS_EXPIRED_FEATURED_JOBS'); ?></span>
					<span class="stats_mystats_left_right_value <?php echo $tdclass[$isodd]; ?>"> 
						<?php echo $this->expirefeaturedjob; ?>
					</span>
					<?php $isodd = 1 - $isodd; ?>
					<span class="stats_mystats_left_right_title_last <?php echo $tdclass[$isodd]; ?>">	<?php echo JText::_('JS_AVAILABLE_FEATURED_JOBS'); ?></span>
					<span class="stats_mystats_left_right_value_last <?php echo $tdclass[$isodd]; ?>"> 
						<?php 
							if($this->ispackagerequired != 1){ 
								echo JText::_('JS_UNLIMITED');
							}elseif($this->featuredjobsallow == -1){ 
								echo JText::_('JS_UNLIMITED');
							}else{ 
								$available_featuredjobs=$this->featuredjobsallow-$this->totalfeaturedjobs; 
								echo $available_featuredjobs;
							} 
						?>
					</span>
				</div>
				<div id="stats_mystats_left">
					<div class="sectionheadline stats_sectiontitle"><?php echo JText::_('JS_COMPANIES'); ?></div>
					<span class="stats_mystats_left_right_title <?php echo $tdclass[$isodd]; ?>">	<?php echo JText::_('JS_COMPANIES_ALLOW'); ?></span>
					<span class="stats_mystats_left_right_value <?php echo $tdclass[$isodd]; ?>">
						<?php 
							if($this->ispackagerequired != 1){ 
								echo JText::_('JS_UNLIMITED');
							}elseif($this->companiesallow == -1){
								echo JText::_('JS_UNLIMITED');
							}else echo $this->companiesallow; ?>
					</span>
					<?php $isodd = 1 - $isodd; ?>
					<span class="stats_mystats_left_right_title <?php echo $tdclass[$isodd]; ?>">	<?php echo JText::_('JS_PUBLISHED_COMPANIES'); ?></span>
					<span class="stats_mystats_left_right_value <?php echo $tdclass[$isodd]; ?>"><?php echo $this->totalcompanies; ?></span>				
					<?php $isodd = 1 - $isodd; ?>
					<span class="stats_mystats_left_right_title <?php echo $tdclass[$isodd]; ?>">	<?php echo JText::_('JS_EXPIRED_COMPANIES'); ?></span>
					<span class="stats_mystats_left_right_value <?php echo $tdclass[$isodd]; ?>">
						<?php  echo '0'; ?>
					</span>

					<?php $isodd = 1 - $isodd; ?>
					<span class="stats_mystats_left_right_title <?php echo $tdclass[$isodd]; ?>">	<?php echo JText::_('JS_AVAILABLE_COMPANIES'); ?></span>
					<span class="stats_mystats_left_right_value <?php echo $tdclass[$isodd]; ?>">
						<?php 
							if($this->ispackagerequired != 1){ 
								echo JText::_('JS_UNLIMITED');
							}elseif($this->companiesallow == -1){ 
								echo JText::_('JS_UNLIMITED');
							}else{ 
								$available_companies=$this->companiesallow-$this->totalcompanies; 
								echo $available_companies;
							} 
						?>
					</span>				
					<?php $isodd = 1 - $isodd; ?>
					<span class="stats_mystats_left_right_title <?php echo $tdclass[$isodd]; ?>">	<?php echo JText::_('JS_GOLD_COMPANIES_ALLOW'); ?></span>
					<span class="stats_mystats_left_right_value <?php echo $tdclass[$isodd]; ?>">
						<?php 
						if($this->ispackagerequired != 1){ 
								echo JText::_('JS_UNLIMITED');
						}elseif($this->goldcompaniesallow == -1){ 
							echo JText::_('JS_UNLIMITED');
						}else echo $this->goldcompaniesallow; 
						?>
					</span>				
					<?php $isodd = 1 - $isodd; ?>
					<span class="stats_mystats_left_right_title <?php echo $tdclass[$isodd]; ?>">	<?php echo JText::_('JS_PUBLISHED_GOLD_COMPANIES'); ?></span>
					<span class="stats_mystats_left_right_value <?php echo $tdclass[$isodd]; ?>">
						<?php echo $this->totalgoldcompanies;?>
					</span>				
					<?php $isodd = 1 - $isodd; ?>
					<span class="stats_mystats_left_right_title <?php echo $tdclass[$isodd]; ?>">	<?php echo JText::_('JS_EXPIRED_GOLD_COMPANIES'); ?></span>
					<span class="stats_mystats_left_right_value <?php echo $tdclass[$isodd]; ?>">
						<?php echo $this->goldcompaniesexpire;?>
					</span>				
					<?php $isodd = 1 - $isodd; ?>
					<span class="stats_mystats_left_right_title <?php echo $tdclass[$isodd]; ?>">	<?php echo JText::_('JS_AVAILABLE_GOLD_COMPANIES'); ?></span>
					<span class="stats_mystats_left_right_value <?php echo $tdclass[$isodd]; ?>">
						<?php 
							if($this->ispackagerequired != 1){ 
								echo JText::_('JS_UNLIMITED');
							}elseif($this->goldcompaniesallow == -1){ 
								echo JText::_('JS_UNLIMITED');
							}else{ 
								$available_gold_companies=$this->goldcompaniesallow-$this->totalgoldcompanies; 
								echo $available_gold_companies;
							} 
						?>
					</span>				
					<?php $isodd = 1 - $isodd; ?>
					<span class="stats_mystats_left_right_title <?php echo $tdclass[$isodd]; ?>">	<?php echo JText::_('JS_FEATURED_COMPANIES_ALLOW'); ?></span>
					<span class="stats_mystats_left_right_value <?php echo $tdclass[$isodd]; ?>">
						<?php 
							if($this->ispackagerequired != 1){ 
									echo JText::_('JS_UNLIMITED');
							}elseif($this->featuredcompainesallow == -1){
								echo JText::_('JS_UNLIMITED');
							}else echo $this->featuredcompainesallow; 
						?>
					</span>				
					<?php $isodd = 1 - $isodd; ?>
					<span class="stats_mystats_left_right_title <?php echo $tdclass[$isodd]; ?>">	<?php echo JText::_('JS_PUBLISHED_FEATURED_COMPANIES'); ?></span>
					<span class="stats_mystats_left_right_value <?php echo $tdclass[$isodd]; ?>">
						<?php echo $this->totalfeaturedcompanies; ?>
					</span>				
					<?php $isodd = 1 - $isodd; ?>
					<span class="stats_mystats_left_right_title <?php echo $tdclass[$isodd]; ?>">	<?php echo JText::_('JS_EXPIRED_FEATURED_COMPANIES'); ?></span>
					<span class="stats_mystats_left_right_value <?php echo $tdclass[$isodd]; ?>">
						<?php echo $this->featurescompaniesexpire; ?>
					</span>				
				<?php $isodd = 1 - $isodd; ?>
					<span class="stats_mystats_left_right_title_last <?php echo $tdclass[$isodd]; ?>">	<?php echo JText::_('JS_AVAILABLE_FEATURED_COMPANIES'); ?></span>
					<span class="stats_mystats_left_right_value_last <?php echo $tdclass[$isodd]; ?>">	
							<?php 
								if($this->ispackagerequired != 1){ 
									echo JText::_('JS_UNLIMITED');
								}elseif($this->featuredcompainesallow == -1){ 
									echo JText::_('JS_UNLIMITED');
								}else{ 
									$available_gold_companies=$this->featuredcompainesallow-$this->totalfeaturedcompanies; 
									echo $available_gold_companies;
								} 
							?>
					</span>
				</div>
			</div>	
		</div>	


<?php
	if($this->ispackagerequired!=1){
		$message = "<strong>".JText::_('JS_PACKAGE_NOT_REQUIRED')."</strong>";?>
			<div id="stats_package_message">
				<?php echo $message; ?>
			</div>
		
	<?php }else{
		if($print == 0){
			$message = '';
			if(empty($this->packagedetail[0]->id)){
				$e_p_link=JRoute::_('index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=packages&Itemid='.$this->Itemid);
				$message = "<strong><font color='orangered'>".JText::_('JS_JOB_NO_PACKAGE')." <a href=".$e_p_link.">".JText::_('JS_EMPLOYER_PACKAGES')."</a></font></strong>";
			}else{
				$days = $this->packagedetail[0]->packageexpiredays - $this->packagedetail[0]->packageexpireindays;
				if($days == 1) $days = $days.' '.JText::_('JS_DAY'); else $days = $days.' '.JText::_('JS_DAYS');
				$message = "<strong><font color='red'>".JText::_('JS_YOUR_PACKAGE').' &quot;'.$this->packagedetail[0]->packagetitle.'&quot; '.JText::_('JS_HAS_EXPIRED').' '.$days.' ' .JText::_('JS_AGO')." <a href='index.php?option=com_jsjobs&view=employer&layout=packages&Itemid=$this->Itemid'>".JText::_('JS_EMPLOYER_PACKAGES')."</a></font></strong>";
			} ?>
			<?php if($message != ''){ ?>
			<div id="errormessagedown"></div>
			<div id="errormessage" class="errormessage">
				<div id="message"><?php echo $message;?></div>
			</div>
			<?php } 
		}
	}

} else{ // not allowed job posting ?>
	<div id="errormessagedown"></div>
	<div id="errormessage" class="errormessage">
		<div id="message"><b><?php echo JText::_('JS_YOU_ARE_NOT_ALLOWED_TO_VIEW');?></b></div>
	</div>
<?php

}	
}//ol
?>	
<div id="jsjobs_footer"><?php echo eval(base64_decode('aWYoJHRoaXMtPmNvbmZpZ1snZnJfY3JfdHhzaCddKSB7DQplY2hvIA0KJzx0YWJsZSB3aWR0aD0iMTAwJSIgc3R5bGU9InRhYmxlLWxheW91dDpmaXhlZDsiPg0KPHRyPjx0ZCBoZWlnaHQ9IjE1Ij48L3RkPjwvdHI+DQo8dHI+PHRkIHN0eWxlPSJ2ZXJ0aWNhbC1hbGlnbjp0b3A7IiBhbGlnbj0iY2VudGVyIj4NCjxhIGNsYXNzPSJpbWciIHRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIj48aW1nIHNyYz0iaHR0cDovL3d3dy5qb29tc2t5LmNvbS9sb2dvL2pzam9ic2NybG9nby5wbmciPjwvYT4NCjxicj4NCkNvcHlyaWdodCAmY29weTsgMjAwOCAtICcuZGF0ZSgnWScpLicsDQo8c3BhbiBpZD0idGhlbWVhbmNob3IiPiA8YSBjbGFzcz0iYW5jaG9yInRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSI+QnVydWogU29sdXRpb25zIDwvYT48L3NwYW4+PC90ZD48L3RyPg0KPC90YWJsZT4nOw0KfQ=='));?></div>
