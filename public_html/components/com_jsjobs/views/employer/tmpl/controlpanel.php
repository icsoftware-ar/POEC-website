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
 * File Name:	views/employer/tmpl/controlpanel.php
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
$colperrow=2;
$colwidth = round(100/$colperrow,1);
$colwidth = $colwidth.'%';

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
					echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_EMPLOYER_C_P');
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
				<span id="tp_headingtext_center"><?php echo JText::_('JS_EMPLOYER_C_P');  ?></span>
				<span id="tp_headingtext_right"></span>				
			</span>
		</div>
	</div>
<?php
$userrole = $this->userrole;
$config = $this->config;
$emcontrolpanel = $this->emcontrolpanel;
if (isset($userrole->rolefor)){
        if ($userrole->rolefor == 1) // employer
            $allowed = true;
        else
            $allowed = false;
}else { if ($config['visitorview_emp_conrolpanel'] == 1) $allowed = true; else $allowed = false; } // user not logined
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
			$print = checkLinks('formcompany',$userrole,$config,$emcontrolpanel);
			if($print){ ?>
				<a id="cp_anchor" href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formcompany&Itemid=<?php echo $this->Itemid; ?>">
					<div id="cp_icon" name="cp_icon">
						<div class="cpicon">
							<div class="cpimage">
								<img  align="left" width="24" height="24" src="components/com_jsjobs/images/addcompany.png" alt="New Company" />
							</div>
						</div>
						<div id="cptext">
							<?php echo JText::_('JS_NEW_COMPANY'); ?>
						</div>
					</div>
				</a>
			<?php }
			$print = checkLinks('mycompanies',$userrole,$config,$emcontrolpanel);
			if($print){ ?>
				<a id="cp_anchor" href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=mycompanies&Itemid=<?php echo $this->Itemid; ?>">
					<div id="cp_icon" name="cp_icon">
						<div class="cpicon">
							<div class="cpimage">
								 <img  align="left" width="24" height="24" src="components/com_jsjobs/images/mycompanies.png" alt="My Companies" />
							</div>
						</div>
						<div id="cptext">
							<?php echo JText::_('JS_MY_COMPANIES'); ?>
						</div>
					</div>
				</a>
			<?php } 
			$print = checkLinks('formjob',$userrole,$config,$emcontrolpanel);
			if($print){ ?>
				<a id="cp_anchor" href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formjob&Itemid=<?php echo $this->Itemid; ?>">
					<div id="cp_icon" name="cp_icon">
						<div class="cpicon">
							<div class="cpimage">
								 <img  align="left" width="24" height="24" src="components/com_jsjobs/images/addjob.png" alt="New Job" />
							</div>
						</div>
						<div id="cptext">
							<?php echo JText::_('JS_NEW_JOB'); ?>
						</div>
					</div>
				</a>
			<?php }
			$print = checkLinks('myjobs',$userrole,$config,$emcontrolpanel);
			if($print){ ?>
				<a id="cp_anchor" href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myjobs&Itemid=<?php echo $this->Itemid; ?>">
					<div id="cp_icon" name="cp_icon">
						<div class="cpicon">
							<div class="cpimage">
								<img align="left" width="24" height="24" src="components/com_jsjobs/images/myjobs.png" alt="My Jobs" />
							</div>
						</div>
						<div id="cptext">
							<?php echo JText::_('JS_MY_JOBS'); ?>
						</div>
					</div>
				</a>
			<?php }
			$print = checkLinks('formdepartment',$userrole,$config,$emcontrolpanel);
			if($print){ ?>
				<a id="cp_anchor" href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formdepartment&Itemid=<?php echo $this->Itemid; ?>">
					<div id="cp_icon" name="cp_icon">
						<div class="cpicon">
							<div class="cpimage">
								<img  align="left" width="24" height="24"  src="components/com_jsjobs/images/adddepartment.png" alt="Form Department" />
							</div>
						</div>
						<div id="cptext">
							<?php echo JText::_('JS_NEW_DEPARTMENT'); ?>
						</div>
					</div>
				</a>
			<?php } 
			$print = checkLinks('mydepartment',$userrole,$config,$emcontrolpanel);
			if($print){ ?>
				<a id="cp_anchor" href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=mydepartments&Itemid=<?php echo $this->Itemid; ?>">
					<div id="cp_icon" name="cp_icon">
						<div class="cpicon">
							<div class="cpimage">
								<img  align="left" width="24" height="24" src="components/com_jsjobs/images/mydepartments.png" alt="My Department" />
							</div>
						</div>
						<div id="cptext">
							<?php echo JText::_('JS_MY_DEPARTMENTS'); ?>
						</div>
					</div>
				</a>
			<?php } 
			$print = checkLinks('newfolders',$userrole,$config,$emcontrolpanel);
			if($print){ ?>
				<a id="cp_anchor" href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=formfolder&Itemid=<?php echo $this->Itemid; ?>">
					<div id="cp_icon" name="cp_icon">
						<div class="cpicon">
							<div class="cpimage">
								 <img align="left" width="24" height="24" src="components/com_jsjobs/images/folders.png" alt="My Folders" />
							</div>
						</div>
						<div id="cptext">
							<?php echo JText::_('JS_NEW_FOLDER'); ?>
						</div>
					</div>
				</a>
			<?php } 
			$print = checkLinks('myfolders',$userrole,$config,$emcontrolpanel);
			if($print){ ?>
				<a id="cp_anchor" href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=myfolders&Itemid=<?php echo $this->Itemid; ?>">
					<div id="cp_icon" name="cp_icon">
						<div class="cpicon">
							<div class="cpimage">
								 <img align="left" width="24" height="24" src="components/com_jsjobs/images/folders.png" alt="My Folders" />
							</div>
						</div>
						<div id="cptext">
							<?php echo JText::_('JS_MY_FOLDERS'); ?>
						</div>
					</div>
				</a>
			<?php } 
			$print = checkLinks('empmessages',$userrole,$config,$emcontrolpanel);
			if($print){ ?>
				<a id="cp_anchor" href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=empmessages&Itemid=<?php echo $this->Itemid; ?>">
					<div id="cp_icon" name="cp_icon">
						<div class="cpicon">
							<div class="cpimage">
								 <img  align="left" width="24" height="24" src="components/com_jsjobs/images/messages.png" alt="Messages" />
							</div>
						</div>
						<div id="cptext">
							<?php echo JText::_('JS_MESSAGES'); ?>
						</div>
					</div>
				</a>
			<?php }
			if(isset($userrole->rolefor) && $userrole->rolefor == 1){//jobseeker
				$link = "index.php?option=com_users&view=profile&Itemid".$this->Itemid;
				$text = JText::_('JS_PROFILE');
				$icon = "profile.png";
			}else{
				$link = "index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=userregister&userrole=3&Itemid=".$this->Itemid;
				$text = JText::_('JS_REGISTER');
				$icon = "register.png";
			}
			$print = checkLinks('empregister',$userrole,$config,$emcontrolpanel);
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
					<span id="cp_heading_center"><?php echo JText::_('JS_RESUMES');?></span>
					<span id="cp_heading_right"></span>
				</span>
			</div>
			<?php
			$print = checkLinks('alljobsappliedapplications',$userrole,$config,$emcontrolpanel);
			if($print){ ?>
				<a id="cp_anchor" href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=alljobsappliedapplications&Itemid=<?php echo $this->Itemid; ?>">
					<div id="cp_icon" name="cp_icon">
						<div class="cpicon">
							<div class="cpimage">
								<img  align="left" width="24" height="24" src="components/com_jsjobs/images/appliedresumes.png" alt="Applied Resume" />
							</div>
						</div>
						<div id="cptext">
							<?php echo JText::_('JS_APPLIED_RESUME'); ?>
						</div>
					</div>
				</a>
			<?php } 
			$print = checkLinks('resumesearch',$userrole,$config,$emcontrolpanel);
			if($print){ ?>
				<a id="cp_anchor" href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=resumesearch&Itemid=<?php echo $this->Itemid; ?>">
					<div id="cp_icon" name="cp_icon">
						<div class="cpicon">
							<div class="cpimage">
								<img  align="left" width="24" height="24" src="components/com_jsjobs/images/resumesearch.png" alt="Search Resume" />
							</div>
						</div>
						<div id="cptext">
							<?php echo JText::_('JS_SEARCH_RESUME'); ?>
						</div>
					</div>
				</a>
			<?php } 
			$print = checkLinks('resumesearch',$userrole,$config,$emcontrolpanel);
			if($print){ ?>
				<a id="cp_anchor" href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=my_resumesearches&Itemid=<?php echo $this->Itemid; ?>">
					<div id="cp_icon" name="cp_icon">
						<div class="cpicon">
							<div class="cpimage">
								<img  align="left" width="24" height="24" src="components/com_jsjobs/images/resumesavesearch.png" alt="Search Resume" />
							</div>
						</div>
						<div id="cptext">
							<?php echo JText::_('JS_RESUME_SAVE_SEARCHES'); ?>
						</div>
					</div>
				</a>
			<?php } 
			$print = checkLinks('resumesearch',$userrole,$config,$emcontrolpanel);
			if($print){ ?>
				<a id="cp_anchor" href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=resumebycategory&Itemid=<?php echo $this->Itemid; ?>">
					<div id="cp_icon" name="cp_icon">
						<div class="cpicon">
							<div class="cpimage">
								<img  align="left" width="24" height="24" src="components/com_jsjobs/images/resumebycat.png" alt=" Resume By Category" />
							</div>
						</div>
						<div id="cptext">
							<?php echo JText::_('JS_RESUME_BY_CATEGORY'); ?>
						</div>
					</div>
				</a>
			<?php } 
			if($config['resume_rss'] == 1){
			$print = checkLinks('empresume_rss',$userrole,$config,$emcontrolpanel);
			if($print){ ?>
				<a id="cp_anchor" target="_blank" href="index.php?option=com_jsjobs&c=jsjobs&view=rss&layout=rssresumes&format=rss&Itemid=<?php echo $this->Itemid; ?>">
					<div id="cp_icon" name="cp_icon">
						<div class="cpicon">
							<div class="cpimage">
								<img align="left" width="24" height="24" src="components/com_jsjobs/images/rss.png" text="Resume RSS"alt="Resume RSS" />
							</div>
						</div>
						<div id="cptext">
							<?php echo JText::_('JS_RESUME_RSS'); ?>
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
			$print = checkLinks('packages',$userrole,$config,$emcontrolpanel);
			if($print){ ?>
				<a id="cp_anchor" href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=packages&Itemid=<?php echo $this->Itemid; ?>">
					<div id="cp_icon" name="cp_icon">
						<div class="cpicon">
							<div class="cpimage">
								 <img  align="left" width="24" height="24" src="components/com_jsjobs/images/packages.png" alt=" Packages" />
							</div>
						</div>
						<div id="cptext">
							<?php echo JText::_('JS_PACKAGES'); ?>
						</div>
					</div>
				</a>
			<?php } 
			$print = checkLinks('purchasehistory',$userrole,$config,$emcontrolpanel);
			if($print){ ?>
				<a id="cp_anchor" href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=purchasehistory&Itemid=<?php echo $this->Itemid; ?>">
					<div id="cp_icon" name="cp_icon">
						<div class="cpicon">
							<div class="cpimage">
								<img  align="left" width="24" height="24" src="components/com_jsjobs/images/purchase_history.png" alt=" Employer Purchase History" />
							</div>
						</div>
						<div id="cptext">
							<?php echo JText::_('JS_PURCHASE_HISTORY'); ?>
						</div>
					</div>
				</a>
			<?php }
			$print = checkLinks('my_stats',$userrole,$config,$emcontrolpanel);
			if($print){ ?>
				<a id="cp_anchor" href="index.php?option=com_jsjobs&c=jsjobs&view=employer&layout=my_stats&Itemid=<?php echo $this->Itemid; ?>">
					<div id="cp_icon" name="cp_icon">
						<div class="cpicon">
							<div class="cpimage">
								<img  align="left" width="24" height="24" src="components/com_jsjobs/images/mystats.png" alt="My Stats" />
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
	if($emcontrolpanel['empexpire_package_message'] == 1){
		$message = '';
		if(!empty($this->packagedetail[0]->packageexpiredays)){
			$days = $this->packagedetail[0]->packageexpiredays - $this->packagedetail[0]->packageexpireindays;
			if($days == 1) $days = $days.' '.JText::_('JS_DAY'); else $days = $days.' '.JText::_('JS_DAYS');
			$message = "<strong><font color='red'>".JText::_('JS_YOUR_PACKAGE').' &quot;'.$this->packagedetail[0]->packagetitle.'&quot; '.JText::_('JS_HAS_EXPIRED').' '.$days.' ' .JText::_('JS_AGO')." <a href='index.php?option=com_jsjobs&view=employer&layout=packages&Itemid=$this->Itemid'>".JText::_('JS_EMPLOYER_PACKAGES')."</a></font></strong>";
		} 
		if($message != ''){?>
			<div id="errormessage" class="errormessage">
			<div id="message"><?php echo $message;?></div>
			</div>
<?php 	}
	}?>
<?php
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
<?php
    function checkLinks($name,$userrole,$config,$emcontrolpanel){
        $print = false;
        if (isset($userrole->rolefor)){
            if ($userrole->rolefor == 1){
				if($name == 'empresume_rss'){
					if($config[$name] == 1) $print = true;
				}elseif ($emcontrolpanel[$name] == 1) $print = true;
            }
        }else{
            if($name == 'empmessages') $name = 'vis_emmessages';
            elseif($name == 'empresume_rss') $name = 'vis_resume_rss';
            else $name = 'vis_em'.$name;
            
            if($config[$name] == 1) $print = true;
        }
        return $print;
    }

?>
