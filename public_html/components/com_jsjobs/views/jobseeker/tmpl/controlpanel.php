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
 * File Name:	views/jobseeker/tmpl/controlpanel.php
 ^ 
 * Description: template view for control panel
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');
global $mainframe;
$document =& JFactory::getDocument();
$document->addStyleSheet('components/com_jsjobs/themes/'.$this->config['theme']);
$comma = 0;
$colperrow=2;
$colwidth = round(100/$colperrow,1);
$colwidth = $colwidth.'%';

?>
<?php if ($this->config['offline'] == '1'){ ?>
	<div id="toppanel">
		<div id="tp_header" style="<?php if($this->config['topimage'] == 0) echo 'background:none;';?>">
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
						echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_JOB_SEEKER_C_P');
				} ?>
			</span>
		</div>
		<div id="tp_links">
			<?php
			if (sizeof($this->jobseekerlinks) != 0){
				foreach($this->jobseekerlinks as $lnk){?>
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
				<span id="tp_headingtext_center"><?php echo JText::_('JS_JOB_SEEKER_C_P');  ?></span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>
<?php
$userrole = $this->userrole;
$config = $this->config;
$jscontrolpanel = $this->jscontrolpanel;
if (isset($userrole->rolefor)){
	if ($userrole->rolefor != ''){
		if ($userrole->rolefor == 2) // job seeker
			$allowed = true;
		elseif($userrole->rolefor == 1){
                    if($config['employerview_js_controlpanel'] == 1) $allowed = true;
                    else $allowed = false;
                }
	}else{
		$allowed = true;
	}
}else $allowed = true; // user not logined
if ($allowed == true) { 
?>
<div id="cp_main">
		<div id="cp_icon_row">
			<div id="cp_icon_heading">
				<span id="cp_heading_text">
					<span id="cp_heading_left"></span>
					<span id="cp_heading_center"><?php echo JText::_('JS_MY_STUFF');?></span>
					<span id="cp_heading_right"></span>
				</span>
			</div>
			<?php
			$print = checkLinks('formresume',$userrole,$config,$jscontrolpanel);
			if($print){ ?>
				<a id="cp_anchor" href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=formresume&vm=2&Itemid=<?php echo $this->Itemid; ?>">
					<div id="cp_icon" name="cp_icon">
						<div class="cpicon">
							<div class="cpimage">
								<img align="left" width="24" height="24" src="components/com_jsjobs/images/addresume.png" alt="<?php echo JText::_('JS_ADD_RESUME'); ?>" />
							</div>
						</div>
						<div id="cptext">
							<?php echo JText::_('JS_ADD_RESUME'); ?>
						</div>
					</div>
				</a>
			<?php }
			$print = checkLinks('myresumes',$userrole,$config,$jscontrolpanel);
			if($print){ ?>
				<a id="cp_anchor" href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myresumes&Itemid=<?php echo $this->Itemid; ?>">
					<div id="cp_icon" name="cp_icon">
						<div class="cpicon">
							<div class="cpimage">
								<img align="left" width="24" height="24" src="components/com_jsjobs/images/myresumes.png" alt="<?php echo JText::_('JS_MY_RESUMES'); ?>" />
							</div>
						</div>
						<div id="cptext">
							<?php echo JText::_('JS_MY_RESUMES'); ?>
						</div>
					</div>
				</a>
			<?php } 
			$print = checkLinks('formcoverletter',$userrole,$config,$jscontrolpanel);
			if($print){ ?>
				<a id="cp_anchor" href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=formcoverletter&Itemid=<?php echo $this->Itemid; ?>">
					<div id="cp_icon" name="cp_icon">
						<div class="cpicon">
							<div class="cpimage">
								<img align="left" width="24" height="24" src="components/com_jsjobs/images/addcoverletter.png" alt="<?php echo JText::_('JS_ADD_COVER_LETTER'); ?>" />
							</div>
						</div>
						<div id="cptext">
							<?php echo JText::_('JS_ADD_COVER_LETTER'); ?>
						</div>
					</div>
				</a>
			<?php }
			$print = checkLinks('mycoverletters',$userrole,$config,$jscontrolpanel);
			if($print){ ?>
				<a id="cp_anchor" href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=mycoverletters&Itemid=<?php echo $this->Itemid; ?>">
					<div id="cp_icon" name="cp_icon">
						<div class="cpicon">
							<div class="cpimage">
								<img align="left" width="24" height="24" src="components/com_jsjobs/images/mycoverletters.png" alt="<?php echo JText::_('JS_MY_COVER_LETTERS'); ?>" />
							</div>
						</div>
						<div id="cptext">
							<?php echo JText::_('JS_MY_COVER_LETTERS'); ?>
						</div>
					</div>
				</a>
			<?php }
			$print = checkLinks('jsmessages',$userrole,$config,$jscontrolpanel);
			if($print){ ?>
				<a id="cp_anchor" href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jsmessages&Itemid=<?php echo $this->Itemid; ?>">
					<div id="cp_icon" name="cp_icon">
						<div class="cpicon">
							<div class="cpimage">
								<img align="left" width="24" height="24" src="components/com_jsjobs/images/messages.png" alt="Messages" />
							</div>
						</div>
						<div id="cptext">
							<?php echo JText::_('JS_MESSAGES'); ?>
						</div>
					</div>
				</a>
			<?php } 
			if(isset($userrole->rolefor) && $userrole->rolefor == 2){//jobseeker
				$link = "index.php?option=com_users&view=profile&Itemid".$this->Itemid;
				$text = JText::_('JS_PROFILE');
				$icon = "profile.png";
			}else{
				$link = "index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=userregister&userrole=2&Itemid=".$this->Itemid;
				$text = JText::_('JS_REGISTER');
				$icon = "register.png";
			}
			$print = checkLinks('jsregister',$userrole,$config,$jscontrolpanel);
			if($print){ ?>
				<a id="cp_anchor" href="<?php echo $link; ?>">
					<div id="cp_icon" name="cp_icon">
						<div class="cpicon">
							<div class="cpimage">
								<img align="left" width="24" height="24" src="components/com_jsjobs/images/<?php echo $icon;?>" alt="Messages" />
							</div>
						</div>
						<div id="cptext">
							<?php echo $text; ?>
						</div>
					</div>
				</a>
			<?php } ?>
		</div>
		<div id="cp_icon_row">
			<div id="cp_icon_heading">
				<span id="cp_heading_text">
					<span id="cp_heading_left"></span>
					<span id="cp_heading_center"><?php echo JText::_('JS_JOBS');?></span>
					<span id="cp_heading_right"></span>
				</span>
			</div>
			<?php
			$print = checkLinks('jobcat',$userrole,$config,$jscontrolpanel);
			if($print){ ?>
				<a id="cp_anchor" href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobcat&Itemid=<?php echo $this->Itemid; ?>">
					<div id="cp_icon" name="cp_icon">
						<div class="cpicon">
							<div class="cpimage">
								<img align="left" width="24" height="24" src="components/com_jsjobs/images/jobcat.png" alt="<?php echo JText::_('JS_JOBS_BY_CATEGORIES'); ?>" />
							</div>
						</div>
						<div id="cptext">
							<?php echo JText::_('JS_JOBS_BY_CATEGORIES'); ?>
						</div>
					</div>
				</a>
			<?php } 
			$print = checkLinks('listnewestjobs',$userrole,$config,$jscontrolpanel);
			if($print){ ?>
				<a id="cp_anchor" href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=listnewestjobs&Itemid=<?php echo $this->Itemid; ?>">
					<div id="cp_icon" name="cp_icon">
						<div class="cpicon">
							<div class="cpimage">
								<img align="left" width="24" height="24" src="components/com_jsjobs/images/newestjobs.png" alt="<?php echo JText::_('JS_NEWEST_JOBS'); ?>" />
							</div>
						</div>
						<div id="cptext">
							<?php echo JText::_('JS_NEWEST_JOBS'); ?>
						</div>
					</div>
				</a>
			<?php } 
			$print = checkLinks('myappliedjobs',$userrole,$config,$jscontrolpanel);
			if($print){ ?>
				<a id="cp_anchor" href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=myappliedjobs&Itemid=<?php echo $this->Itemid; ?>">
					<div id="cp_icon" name="cp_icon">
						<div class="cpicon">
							<div class="cpimage">
								<img align="left" width="24" height="24" src="components/com_jsjobs/images/myappliedjobs.png" alt="<?php echo JText::_('JS_MY_APPLIED_JOBS'); ?>" />
							</div>
						</div>
						<div id="cptext">
							<?php echo JText::_('JS_MY_APPLIED_JOBS'); ?>
						</div>
					</div>
				</a>
			<?php } 
			$print = checkLinks('jobsearch',$userrole,$config,$jscontrolpanel);
			if($print){ ?>
				<a id="cp_anchor" href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobsearch&Itemid=<?php echo $this->Itemid; ?>">
					<div id="cp_icon" name="cp_icon">
						<div class="cpicon">
							<div class="cpimage">
								<img align="left" width="24" height="24" src="components/com_jsjobs/images/jobsearch.png" alt="<?php echo JText::_('JS_SEARCH_JOB'); ?>" />
							</div>
						</div>
						<div id="cptext">
							<?php echo JText::_('JS_SEARCH_JOB'); ?>
						</div>
					</div>
				</a>
			<?php } 
			$print = checkLinks('my_jobsearches',$userrole,$config,$jscontrolpanel);
			if($print){ ?>
				<a id="cp_anchor" href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=my_jobsearches&Itemid=<?php echo $this->Itemid; ?>">
					<div id="cp_icon" name="cp_icon">
						<div class="cpicon">
							<div class="cpimage">
								<img align="left" width="24" height="24" src="components/com_jsjobs/images/jobsavesearch.png" alt="<?php echo JText::_('JS_JOB_SAVE_SEARCHES'); ?>" />
							</div>
						</div>
						<div id="cptext">
							<?php echo JText::_('JS_JOB_SAVE_SEARCHES'); ?>
						</div>
					</div>
				</a>
			<?php }
			$print = checkLinks('jobalertsetting',$userrole,$config,$jscontrolpanel);
			if($print){ ?>
				<a id="cp_anchor" href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=jobalertsetting&Itemid=<?php echo $this->Itemid; ?>">
					<div id="cp_icon" name="cp_icon">
						<div class="cpicon">
							<div class="cpimage">
								<img align="left" width="24" height="24" src="components/com_jsjobs/images/jobalert.png" alt="Job Alert" />
							</div>
						</div>
						<div id="cptext">
							<?php echo JText::_('JS_JOB_ALERT'); ?>
						</div>
					</div>
				</a>
			<?php } 	
			if($config['job_rss'] == 1){
			$print = checkLinks('jsjob_rss',$userrole,$config,$jscontrolpanel);
				if($print){ ?>
				<a id="cp_anchor" target="_blank" href="index.php?option=com_jsjobs&c=jsjobs&view=rss&layout=rssjobs&format=rss&Itemid=<?php echo $this->Itemid; ?>">
					<div id="cp_icon" name="cp_icon">
						<div class="cpicon">
							<div class="cpimage">
								<img align="left" width="24" height="24" src="components/com_jsjobs/images/rss.png" text="Job RSS"alt="Job RSS" />
							</div>
						</div>
						<div id="cptext">
							<?php echo JText::_('JS_JOB_RSS'); ?>
						</div>
					</div>
				</a>
			<?php } 
			} ?>
		</div>
		<div id="cp_icon_row">
			<div id="cp_icon_heading">
				<span id="cp_heading_text">
					<span id="cp_heading_left"></span>
					<span id="cp_heading_center"><?php echo JText::_('JS_STATISTICS');?></span>
					<span id="cp_heading_right"></span>
				</span>
			</div>
			<?php
			$print = checkLinks('jspackages',$userrole,$config,$jscontrolpanel);
			if($print){ ?>
				<a id="cp_anchor" href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=packages&Itemid=<?php echo $this->Itemid; ?>">
					<div id="cp_icon" name="cp_icon">
						<div class="cpicon">
							<div class="cpimage">
								<img align="left" width="24" height="24" src="components/com_jsjobs/images/packages.png" alt=" Packages" />
							</div>
						</div>
						<div id="cptext">
							<?php echo JText::_('JS_PACKAGES'); ?>
						</div>
					</div>
				</a>
			<?php } 
			$print = checkLinks('jspurchasehistory',$userrole,$config,$jscontrolpanel);
			if($print){ ?>
				<a id="cp_anchor" href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=purchasehistory&Itemid=<?php echo $this->Itemid; ?>">
					<div id="cp_icon" name="cp_icon">
						<div class="cpicon">
							<div class="cpimage">
								<img align="left" width="24" height="24" src="components/com_jsjobs/images/purchase_history.png" alt=" Employer Purchase History" />
							</div>
						</div>
						<div id="cptext">
							<?php echo JText::_('JS_PURCHASE_HISTORY'); ?>
						</div>
					</div>
				</a>
			<?php }
			$print = checkLinks('jsmy_stats',$userrole,$config,$jscontrolpanel);
			if($print){ ?>
				<a id="cp_anchor" href="index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=my_stats&Itemid=<?php echo $this->Itemid; ?>">
					<div id="cp_icon" name="cp_icon">
						<div class="cpicon">
							<div class="cpimage">
								<img align="left" width="24" height="24" src="components/com_jsjobs/images/mystats.png" alt="My Stats" />
							</div>
						</div>
						<div id="cptext">
							<?php echo JText::_('JS_MY_STATS'); ?>
						</div>
					</div>
				</a>
			<?php } ?>
		</div>
	</div>
<?php
	$message = '';
	if($jscontrolpanel['jsexpire_package_message'] == 1){
		if(!empty($this->packagedetail[0]->packageexpiredays)){
			$days = $this->packagedetail[0]->packageexpiredays - $this->packagedetail[0]->packageexpireindays;
			if($days == 1) $days = $days.' '.JText::_('JS_DAY'); else $days = $days.' '.JText::_('JS_DAYS');
			$message = "<strong><font color='red'>".JText::_('JS_YOUR_PACKAGE').' &quot;'.$this->packagedetail[0]->packagetitle.'&quot; '.JText::_('JS_HAS_EXPIRED').' '.$days.' ' .JText::_('JS_AGO')." <a href='index.php?option=com_jsjobs&view=jobseeker&layout=packages&Itemid=$this->Itemid'>".JText::_('JS_JOBSEEKER_PACKAGES')."</a></font></strong>";
		}
		if($message != ''){?>
			<div id="errormessage" class="errormessage">
				<div id="message"><?php echo $message;?></div>
			</div>
		<?php }
	}?>
<?php
} else{ // not allowed job posting ?>
	<div id="errormessage" class="errormessage">
		<div id="message"><?php echo JText::_('EA_YOU_ARE_NOT_ALLOWED_TO_VIEW');?></div>
	</div>
<?php
}
}//ol
?>
<div id="jsjobs_footer"><?php echo eval(base64_decode('aWYoJHRoaXMtPmNvbmZpZ1snZnJfY3JfdHhzaCddKSB7DQplY2hvIA0KJzx0YWJsZSB3aWR0aD0iMTAwJSIgc3R5bGU9InRhYmxlLWxheW91dDpmaXhlZDsiPg0KPHRyPjx0ZCBoZWlnaHQ9IjE1Ij48L3RkPjwvdHI+DQo8dHI+PHRkIHN0eWxlPSJ2ZXJ0aWNhbC1hbGlnbjp0b3A7IiBhbGlnbj0iY2VudGVyIj4NCjxhIGNsYXNzPSJpbWciIHRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIj48aW1nIHNyYz0iaHR0cDovL3d3dy5qb29tc2t5LmNvbS9sb2dvL2pzam9ic2NybG9nby5wbmciPjwvYT4NCjxicj4NCkNvcHlyaWdodCAmY29weTsgMjAwOCAtICcuZGF0ZSgnWScpLicsDQo8c3BhbiBpZD0idGhlbWVhbmNob3IiPiA8YSBjbGFzcz0iYW5jaG9yInRhcmdldD0iX2JsYW5rIiBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSI+QnVydWogU29sdXRpb25zIDwvYT48L3NwYW4+PC90ZD48L3RyPg0KPC90YWJsZT4nOw0KfQ=='));?></div>
<?php
    function checkLinks($name,$userrole,$config,$jscontrolpanel){
        $print = false;
        switch ($name) {
            case 'jspackages': $visname = 'vis_jspackages';break;
            case 'jspurchasehistory': $visname = 'vis_jspurchasehistory';break;
            case 'jsmy_stats': $visname = 'vis_jsmy_stats';break;
            case 'jsmessages': $visname = 'vis_jsmessages';break;
            case 'jsjob_rss': $visname = 'vis_job_rss';break;
            case 'jsregister': $visname = 'vis_jsregister';break;

            default:$visname = 'vis_js'.$name;break;
        }
        if (isset($userrole->rolefor)){
            if ($userrole->rolefor == 2){
				if($name == 'jsjob_rss'){
					if($config[$name] == 1) $print = true;
				}elseif ($jscontrolpanel[$name] == 1) $print = true;
            }elseif ($userrole->rolefor == 1){
                if($config['employerview_js_controlpanel'] == 1)
                    if($config[$visname] == 1) $print = true;
            }
        }else{
            if($config[$visname] == 1) $print = true;
        }
        return $print;
    }
?>
<script type="text/javascript" language="javascript">
	function setwidth(){
		var totalwidth = document.getElementById("cp_icon_row").offsetWidth;
		var width = totalwidth - 317;
		width = (width/3)/3;
		document.getElementById("cp_icon_row").style.marginLeft = width+"px";
		var totalicons =document.getElementsByName("cp_icon").length;
        for(var i = 0; i < totalicons; i++)
        {
            document.getElementsByName("cp_icon")[i].style.marginLeft = width+"px";
            document.getElementsByName("cp_icon")[i].style.marginRight = width+"px";
        } 		
	}
	//setwidth();
	function setwidthheadline(){
		var totalwidth = document.getElementById("tp_heading").offsetWidth;
		var textwidth = document.getElementById("tp_headingtext").offsetWidth;
		var width = totalwidth - textwidth;
		width = width/2;
		document.getElementById("left_image").style.width = width+"px";
		document.getElementById("right_image").style.width = width+"px";
	}
	//setwidthheadline();
</script>
