
<?php
/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 + Created by:	Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Mar 25, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	views/applications/view.html.php
 ^ 
 * Description: HTML view of all applications 
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');
$document = &JFactory::getDocument();
$version = new JVersion;
$joomla = $version->getShortVersion();
$jversion = substr($joomla,0,3);
	if($jversion < 3){
		JHtml::_('behavior.mootools');
		$document->addScript('../components/com_jsjobs/js/jquery.js');
	}else{
		JHtml::_('behavior.framework');
		JHtml::_('jquery.framework');
	}	
	$document->addStyleSheet(JURI::root().'administrator/components/com_jsjobs/include/css/jsjobsadmin.css');
	$document->addStyleSheet(JURI::root().'administrator/components/com_jsjobs/include/css/graph.css');
	$document->addScript(JURI::root().'administrator/components/com_jsjobs/include/js/jquery.flot.js');
	$document->addScript(JURI::root().'administrator/components/com_jsjobs/include/js/jquery.flot.time.js');
	$document->addScript('components/com_jsjobs/include/js/jquery_idTabs.js');
	
?>

	<script type="text/javascript">
//	var myMenu;
	function mymenu(val) {
		myMenu = new SDMenu("my_menu");
myMenu.speed = 3;                     // Menu sliding speed (1 - 5 recomended)
myMenu.remember = true;               // Store menu states (expanded or collapsed) in cookie and restore later
myMenu.oneSmOnly = true;             // One expanded submenu at a time
myMenu.markCurrent = true;            // Mark current link / page (link.href == location.href)

myMenu.init();

// Additional methods...
var firstSubmenu = myMenu.submenus[val];
myMenu.expandMenu(firstSubmenu);      // Expand a submenu
	};


	</script>
<?php //echo '<pre>';print_r($this->today_stats); ?>
<table width="100%">
	<tr>
		<td align="left" width="150" valign="top">
			<table width="100%"><tr><td style="vertical-align:top;">
			<?php
			include_once('components/com_jsjobs/views/menu.php');
			?>
			</td>
			</tr></table>
		</td>
		<td width="100%" valign="top">
			<div id="cp_wraper">
				<!--<div id="cp_main_title"><?php echo JText::_('JS_JOBS_ADMINISTRATION'); ?></div>	
				<div id="cp_main_bottom"></div>	-->
				<div id="cp_contant">
						<div id="jsjobs_info_heading"><?php echo JText::_('JS_CONTROL_PANEL'); ?></div>
						<div class="cp_sub_heading_bar">
							<span id="cp_sub_heading_bar_shortdetail_image" class="sharing_status"></span>
							<span class="cp_sub_heading_bar_text" ><?php echo JText::_('JOB_SHARING_STATUS'); ?></span>
						</div>
                                                <div id="cp_sharing_message_wrapper">
                                                    <span id="cp_sharing_message" class="<?php echo ($this->isjobsharing) ? 'enable':'disable';?>">
                                                        <?php 
                                                                echo JText::_('YOUR_JOB_SHARING_IS');
                                                                if($this->isjobsharing){
                                                                    echo '<span id="sharing_enable">'.JText::_('JS_ENABLE').'</span>';
                                                                }else{
                                                                    echo '<span id="sharing_disable">'.JText::_('JS_DISABLE').'</span>';
                                                                }
                                                        ?>
                                                    </span>
                                                </div>
						<div class="cp_sub_heading_bar">
							<span id="cp_sub_heading_bar_shortdetail_image" ></span>
							<span class="cp_sub_heading_bar_text" ><?php echo JText::_('JS_SHORT_HAND_DETAILS_OF_JOBS'); ?></span>
						</div>
						<div id="cp_statsoverview">
								<div id="cp_statsoverview_top">
									<span id="cp_statsoverview_top_image" ></span>
									<span class="cp_statsoverview_top_text" ><?php echo JText::_('JS_TODAY_STATS_OVERVIEW'); ?></span>
								</div>
								<div id="cp_statsoverview_data">	
									<div class="cp_statsoverview_details">
										<span id="cp_statsoverview_a_j_image"></span>
										<span class="cp_statsoverview_text"><?php echo JText::_('JS_JOBS'); ?></span>
										<span class="cp_statsoverview_text_count"><?php echo $this->today_stats[1]->totaljobs; ?></span>
									</div>
									<div class="cp_statsoverview_details">
										<span id="cp_statsoverview_s_j_image"></span>
										<span class="cp_statsoverview_text"><?php echo JText::_('JS_COMPANIES'); ?></span>
										<span class="cp_statsoverview_text_count"><?php echo $this->today_stats[0]->totalcompanies; ?></span>
									</div>
									<div class="cp_statsoverview_details">
										<span id="cp_statsoverview_h_j_image"></span>
										<span class="cp_statsoverview_text"><?php echo JText::_('JS_RESUME'); ?></span>
										<span class="cp_statsoverview_text_count"><?php echo  $this->today_stats[2]->totalresume; ?></span>
									</div>
									<div class="cp_statsoverview_details">
										<span id="cp_statsoverview_sl_j_image"></span>
										<span class="cp_statsoverview_text"><?php echo JText::_('JS_JOBSEEKER'); ?></span>
										<span class="cp_statsoverview_text_count"><?php echo  $this->today_stats[4]->totaljobseeker; ?></span>
									</div>
									<div class="cp_statsoverview_details">
										<span id="cp_statsoverview_r_j_image"></span>
										<span class="cp_statsoverview_text"><?php echo JText::_('JS_EMPLOYER'); ?></span>
										<span class="cp_statsoverview_text_count"><?php echo  $this->today_stats[3]->totalemployer; ?></span>
									</div>
									
								</div>
						</div>
						<div id="cp_graph">
							<div id="cp_graph_top">
								<span id="cp_graph_top_image" ></span>
								<span class="cp_graph_top_text" ><?php echo JText::_('JS_STATS_CHART'); ?></span>
							</div>
							<div id="cp_graph_data">
								<div id="content">
									<div class="demo-container">
										<div id="placeholder" class="demo-placeholder">
										</div>
									</div>
								</div>
							</div>
						</div>
						<div id="cp_jobs">
							<div id="cp_jobs_top">
								<span class="cp_jobs_text" ><?php echo JText::_('JS_TOP_LATEST_JOBS'); ?></span>
							</div>
							<div id="cp_jobs_data">
								<table id="cp_jobs_data_table"  cellspacing="0" cellpadding="0">
									<tr>
										<th class="cp_jobs_data_th" ><?php echo JText::_('JS_JOB_TITLE'); ?></th>
										<th class="cp_jobs_data_th"><?php echo JText::_('JS_COMPANY_NAME'); ?></th>
										<th class="cp_jobs_data_th"><?php echo JText::_('JS_CATEGORY'); ?></th>
										<th class="cp_jobs_data_th"><?php echo JText::_('JS_SALARY'); ?></th>
										<th class="cp_jobs_data_th"><?php echo JText::_('JS_STOP_PUBLISHING'); ?></th>
									</tr>
									<?php foreach($this->topjobs AS $tj){ ?>
											<tr>
												<td><a href="index.php?option=com_jsjobs&view=applications&layout=view_job&oi=<?php echo $tj->id ; ?>"><?php echo $tj->jobtitle; ?></a></td>
												<td><?php echo $tj->companyname; ?></td>
												<td><?php echo $tj->cattile; ?></td>
												
												<td><?php if($tj->salaryfrom) echo $tj->symbol.$tj->salaryfrom; ?><?php  if($tj->salaryto) echo $tj->symbol.$tj->salaryto; ?></td>
												<td><?php echo  date('D, d M Y',strtotime($tj->stoppublishing));?></td>
											<tr>
									<?php } ?>
								</table>
							</div>
						</div>
						<div class="cp_sub_heading_bar">
							<span id="cp_sub_heading_bar_controlpanel_image" ></span>
							<span class="cp_sub_heading_bar_text" ><?php echo JText::_('JS_CONTROL_PANEL'); ?></span>
						</div>
						<div id="cp_icon_main">
							<div class="cp_icon">
								<a href="index.php?option=com_jsjobs&task=view&layout=companies" onclick="mymenu(2)">
									<span id="cp_icon_company" class="cp_icon_comman"></span>
									<span class="cp_icon_text"><?php echo JText::_('JS_COMPANIES'); ?></span>
								</a>	
							</div>
							<div class="cp_icon">
								<a href="index.php?option=com_jsjobs&task=view&layout=jobs" onclick="mymenu(4)">
									<span id="cp_icon_job" class="cp_icon_comman"></span>
									<span class="cp_icon_text"><?php echo JText::_('JS_JOBS'); ?></span>
								</a>	
							</div>
							<div class="cp_icon">
								<a href="index.php?option=com_jsjobs&task=view&layout=empapps" onclick="mymenu(5)">
									<span id="cp_icon_resume" class="cp_icon_comman"></span>
									<span class="cp_icon_text"><?php echo JText::_('JS_RESUME'); ?></span>
								</a>	
							</div>
							<div class="cp_icon">
								<a href="index.php?option=com_jsjobs&task=view&layout=payment_report" onclick="mymenu(7)">
									<span id="cp_icon_payment_report" class="cp_icon_comman"></span>
									<span class="cp_icon_text_new_version"><?php echo JText::_('JS_PAYMENT_REPORT'); ?></span>
								</a>	
							</div>
							<div class="cp_icon">
								<a href="index.php?option=com_jsjobs&task=view&layout=jobsearch" onclick="mymenu(4)">
									<span id="cp_icon_job_search" class="cp_icon_comman"></span>
									<span class="cp_icon_text"><?php echo JText::_('JS_JOB_SEARCH'); ?></span>
								</a>	
							</div>
							<div class="cp_icon">
								<a href="index.php?option=com_jsjobs&task=view&layout=resumesearch" onclick="mymenu(5)">
									<span id="cp_icon_resume_search" class="cp_icon_comman"></span>
									<span class="cp_icon_text_new_version"><?php echo JText::_('JS_RESUME_SEARCH'); ?></span>
								</a>	
							</div>
							<div class="cp_icon">
								<a href="index.php?option=com_jsjobs&task=view&layout=userstats" onclick="mymenu(12)">
									<span id="cp_icon_user_stats" class="cp_icon_comman"></span>
									<span class="cp_icon_text"><?php echo JText::_('JS_USER_STATS'); ?></span>
								</a>	
							</div>
						</div>
						<div class="cp_sub_heading_bar">
							<span id="cp_sub_heading_bar_configuration_image" ></span>
							<span class="cp_sub_heading_bar_text" ><?php echo JText::_('JS_CONFIGURATION'); ?></span>
						</div>
						<div id="cp_icon_main">
							<div class="cp_icon">
								<a  href="index.php?option=com_jsjobs&task=view&layout=configurations" onclick="mymenu(1)">
									<span id="cp_icon_configuration" class="cp_icon_comman"></span>
									<span class="cp_icon_text"><?php echo JText::_('JS_GENERAL'); ?></span>
								</a>	
							</div>
							<div class="cp_icon">
								<a href="index.php?option=com_jsjobs&task=view&layout=configurationsemployer" onclick="mymenu(1)">
									<span id="cp_icon_emoloyer" class="cp_icon_comman"></span>
									<span class="cp_icon_text"><?php echo JText::_('JS_EMPLOYER'); ?></span>
								</a>	
							</div>
							<div class="cp_icon">
								<a href="index.php?option=com_jsjobs&task=view&layout=configurationsjobseeker" onclick="mymenu(1)">
									<span id="cp_icon_jobseeker" class="cp_icon_comman"></span>
									<span class="cp_icon_text"><?php echo JText::_('JS_JOBSEEKER'); ?></span>
								</a>	
							</div>
							<div class="cp_icon">
								<a href="index.php?option=com_jsjobs&task=view&layout=themes" onclick="mymenu(1)">
									<span id="cp_icon_themes" class="cp_icon_comman"></span>
									<span class="cp_icon_text"><?php echo JText::_('JS_THEMES'); ?></span>
								</a>	
							</div>
						</div>	
						<div class="cp_sub_heading_bar">
							<span id="cp_sub_heading_bar_information_image" ></span>
							<span class="cp_sub_heading_bar_text" ><?php echo JText::_('JS_INFORMATION'); ?></span>
						</div>
						<div id="cp_icon_main">
							<div class="cp_icon">
								<a  href="index.php?option=com_jsjobs&task=view&layout=info"  onclick="mymenu(0)" >
									<span id="cp_icon_about" class="cp_icon_comman"></span>
									<span class="cp_icon_text"><?php echo JText::_('JS_ABOUT'); ?></span>
								</a>	
							</div>
							<div class="cp_icon">
								<a href="index.php?option=com_jsjobs&task=view&layout=updates" onclick="mymenu(0)">
									<span id="cp_icon_remove_footer" class="cp_icon_comman"></span>
									<span class="cp_icon_text_new_version"><?php echo JText::_('JS_REMOVE_FOOTER'); ?></span>
								</a>	
							</div>
							<div class="cp_icon">
								<a href="index.php?option=com_jsjobs&task=view&layout=updates" onclick="mymenu(0)">
									<span id="cp_icon_updates" class="cp_icon_comman"></span>
									<span class="cp_icon_text"><?php echo JText::_('JS_UPDATES'); ?></span>
								</a>	
							</div>
							<div class="cp_icon">
							<?php

									$url = 'http://www.joomsky.com/jsjobssys/getlatestversion.php';
									$pvalue = "dt=".date('Y-m-d');
									if  (in_array  ('curl', get_loaded_extensions())) {
										$ch = curl_init();
										curl_setopt($ch,CURLOPT_URL,$url);
										curl_setopt($ch,CURLOPT_POST,8);
										curl_setopt($ch,CURLOPT_POSTFIELDS,$pvalue);
										curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
										curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
										curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
										$curl_errno = curl_errno($ch);
										$curl_error = curl_error($ch);
										$result = curl_exec($ch);
										curl_close($ch);
										if($result == $this->config['versioncode']){ ?>
													<span class="cp_icon_comman"> <img src="components/com_jsjobs/include/images/systemupdated.png" height="35" width="35" title="<?php echo JText::_('YOUR_SYSTEM_IS_UP_TO_DATE'); ?>"></span>
													<span class="cp_icon_text_new_version"><?php echo JText::_('YOUR_SYSTEM_IS_UP_TO_DATE'); ?></span>
													</a>
										<?php	
										}elseif($result){ ?>
													<span class="cp_icon_comman"> <img src="components/com_jsjobs/include/images/systemupdated.png" height="35" width="35" title="<?php echo JText::_('NEW_VERSION_AVAILABLE'); ?>"></span>
													<span class="cp_icon_text_new_version"><?php echo JText::_('NEW_VERSION_AVAILABLE'); ?></span>
										<?php			
										}else{ ?>
													<span class="cp_icon_comman"><img src="components/com_jsjobs/include/images/unabletoconnect.png" height="35" width="35" title="<?php echo JText::_('UNABLE_CONNECT_TO_SERVER'); ?>"></span>
													<span class="cp_icon_text_new_version"><?php echo JText::_('UNABLE_TO_CONNECT'); ?></span>
										<?php			
										}
									}else{ ?>
												<span class="cp_icon_comman"><img src="components/com_jsjobs/include/images/unabletoconnect.png" height="35" width="35" title="<?php echo JText::_('UNABLE_CONNECT_TO_SERVER'); ?>"></span>
												<span class="cp_icon_text_new_version"><?php echo JText::_('UNABLE_TO_CONNECT'); ?></span>
									<?php			
									}
							?>
							</div>
						</div>	
						<div class="cp_sub_heading_bar">
							<span id="cp_sub_heading_bar_support_image" ></span>
							<span class="cp_sub_heading_bar_text" ><?php echo JText::_('JS_SUPPORT'); ?></span>
						</div>
						<div id="cp_icon_main">
							<div class="cp_icon">
								<a  href="http://www.joomsky.com/jsjobssys/forum.php"  target="_blank">
									<span id="cp_icon_forum" class="cp_icon_comman"></span>
									<span class="cp_icon_text"><?php echo JText::_('JS_FORUM'); ?></span>
								</a>	
							</div>
							<div class="cp_icon">
								<a href="http://www.joomsky.com/jsjobssys/documentation.php" target="_blank">
									<span id="cp_icon_documentation" class="cp_icon_comman"></span>
									<span class="cp_icon_text"><?php echo JText::_('JS_DOCUMENTATION'); ?></span>
								</a>	
							</div>
							<div class="cp_icon">
								<a href="http://www.joomsky.com/jsjobssys/ticket.php" target="_blank">
									<span id="cp_icon_ticket" class="cp_icon_comman"></span>
									<span class="cp_icon_text_new_version"><?php echo JText::_('JS_OPEN_A_TICKET'); ?></span>
								</a>	
							</div>
						</div>	
						<div class="cp_sub_heading_bar">
							<span id="cp_sub_heading_bar_cron_image" ></span>
							<span class="cp_sub_heading_bar_text" ><?php echo JText::_('JS_CRON_JOB'); ?></span>
						</div>
						<?php $array = array('even','odd'); $k = 0; ?>
						<div id="cp_icon_main">
							<div id="tabs_wrapper" class="tabs_wrapper">
								<div class="idTabs controlpanel">
									<span><a class="selected" data-css="controlpanel" href="#webcrown"><?php echo JText::_('JS_WEB_CROWN_ORG');?></a></span> 
									<span><a  data-css="controlpanel" href="#wget"><?php echo JText::_('JS_WGET');?></a></span> 
									<span><a  data-css="controlpanel" href="#curl"><?php echo JText::_('JS_CURL');?></a></span> 
									<span><a  data-css="controlpanel" href="#phpscript"><?php echo JText::_('JS_PHP_SCRIPT');?></a></span> 
									<span><a  data-css="controlpanel" href="#url"><?php echo JText::_('JS_URL');?></a></span> 
								</div>
								<div id="webcrown">
									<div id="cron_job">
										<span class="crown_text">
											<?php echo JText::_('JS_CONFIGURATION_OF_A_BACKUP_JOB_WITH_WEBCRON_ORG'); ?>
										</span>
										<div id="cron_job_detail_wrapper" class="<?php echo $array[$k]; $k=1-$k;?>">
											<span class="crown_text_left">
												<?php echo JText::_('JS_NAME_OF_CRON_JOB'); ?>
											</span>
											<span class="crown_text_right">
												<?php echo JText::_('JS_LOG_IN_TO_WEBCRON_ORG_IN_THE_CRON_AREA_CLICK_ON'); ?>
											</span>
										</div>
										<div id="cron_job_detail_wrapper" class="<?php echo $array[$k]; $k=1-$k;?>">
											<span class="crown_text_left">
												<?php echo JText::_('JS_TIMEOUT'); ?>
											</span>
											<span class="crown_text_right">
												<?php echo JText::_('JS_180_SEC_IF_THE_BACKUP_DOESNOT_COMPLETE_INCREASE_IT_MOST_SITES_WILL_WORK_WITH_A_SETTING_OF_180_600'); ?>
											</span>
										</div>
										<div id="cron_job_detail_wrapper" class="<?php echo $array[$k]; $k=1-$k;?>">
											<span class="crown_text_left">
												<?php echo JText::_('JS_URL_YOU_WANT_TO_EXECUTE'); ?>
											</span>
											<span class="crown_text_right">
												<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=sendjobalert&ck=".$this->ck;?>
											</span>
										</div>
										<div id="cron_job_detail_wrapper" class="<?php echo $array[$k]; $k=1-$k;?>">
											<span class="crown_text_left">
												<?php echo JText::_('JS_LOGIN'); ?>
											</span>
											<span class="crown_text_right">
												<?php echo JText::_('JS_LEAVE_THIS_BLANK'); ?>
											</span>
										</div>
										<div id="cron_job_detail_wrapper" class="<?php echo $array[$k]; $k=1-$k;?>">
											<span class="crown_text_left">
												<?php echo JText::_('JS_PASSWORD'); ?>
											</span>
											<span class="crown_text_right">
												<?php echo JText::_('JS_LEAVE_THIS_BLANK'); ?>
											</span>
										</div>
										<div id="cron_job_detail_wrapper" class="<?php echo $array[$k]; $k=1-$k;?>">
											<span class="crown_text_left">
												<?php echo JText::_('JS_EXECUTION_TIME'); ?>
											</span>
											<span class="crown_text_right">
												<?php echo JText::_('JS_THAT_THE_GRID_BELOW_THE_OTHER_OPTIONS_SELECT_WHEN_AND_HOW'); ?>
											</span>
										</div>
										<div id="cron_job_detail_wrapper" class="<?php echo $array[$k]; $k=1-$k;?>">
											<span class="crown_text_left">
												<?php echo JText::_('JS_ALERTS'); ?>
											</span>
											<span class="crown_text_right">
												<?php echo JText::_('JS_IF_YOU_HAVE_ALREADY_SET_UP_ALERTS_METHODS_IN_WEBCRON_ORG_INTERFACE_WE_RECOMMEND_CHOOSING_AN_ALERT'); ?>
											</span>
										</div>
									</div>	
								</div>
								<div id="wget">
									<div id="cron_job">
										<span class="crown_text">
											<?php echo JText::_('JS_CRON_SCHEDULING_USING_WGET'); ?>
										</span>
										<div id="cron_job_detail_wrapper" class="even">
											<span class="crown_text_right fullwidth">
												<?php echo 'wget --max-redirect=10000 "'.JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=sendjobalert&ck=".$this->ck. '" -O - 1>/dev/null 2>/dev/null '; ?>
											</span>
										</div>
									</div>	
								</div>
								<div id="curl">
									<div id="cron_job">
										<span class="crown_text">
											<?php echo JText::_('JS_CRON_SCHEDULING_USING_CURL'); ?>
										</span>
										<div id="cron_job_detail_wrapper" class="even">
											<span class="crown_text_right fullwidth">
												<?php echo 'curl "'.JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=sendjobalert&ck=".$this->ck.'"<br>'.JText::_('OR').'<br>';?>
												<?php echo 'curl -L --max-redirs 1000 -v "'.JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=sendjobalert&ck=".$this->ck. '" 1>/dev/null 2>/dev/null '; ?>
											</span>
										</div>
									</div>	
								</div>
								<div id="phpscript">
									<div id="cron_job">
										<span class="crown_text">
											<?php echo JText::_('JS_CUSTOM_PHP_SCRIPT_TO_RUN_THE_FRONT_END_BACKUP'); ?>
										</span>
										<div id="cron_job_detail_wrapper" class="even">
											<span class="crown_text_right fullwidth">
												<?php 
													echo '	$curl_handle=curl_init();<br>
															curl_setopt($curl_handle, CURLOPT_URL, \''.JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=sendjobalert&ck=".$this->ck.'\');<br>
															curl_setopt($curl_handle,CURLOPT_FOLLOWLOCATION, TRUE);<br>
															curl_setopt($curl_handle,CURLOPT_MAXREDIRS, 10000);<br>
															curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER, 1);<br>
															$buffer = curl_exec($curl_handle);<br>
															curl_close($curl_handle);<br>
															if (empty($buffer))<br>
															&nbsp;&nbsp;echo "'.JText::_('JS_SORRY_THE_BACKUP_DIDNOT_WORK').'";<br>
															else<br>
															&nbsp;&nbsp;echo $buffer;<br>
																			
															';
												?>
											</span>
										</div>
									</div>	
								</div>
								<div id="url">
									<div id="cron_job">
										<span class="crown_text">
											<?php echo JText::_('JS_URL_FOR_USE_WITH_YOUR_OWN_SCRIPTS_AND_THIRD_PARTY'); ?>
										</span>
										<div id="cron_job_detail_wrapper" class="even">
											<span class="crown_text_right fullwidth">
												<?php echo JURI::root()."index.php?option=com_jsjobs&c=jsjobs&task=sendjobalert&ck=".$this->ck;?>
											</span>
										</div>
									</div>	
								</div>
								<div id="cron_job">
									<span style="float:left;margin-right:4px;"><?php echo JText::_('JS_RECOMENDED_RUN_SCRIPT_ONCE_A_DAY'); ?></span>									
								</div>	
						</div>
				</div>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			
		</td>
	</tr>
	
</table>	
<script type="text/javascript">
	jQuery(document).ready(function(){
		date_x_axises = new Array();
		data_count =new Array();
	jQuery.ajax({
	      url:"index.php?option=com_jsjobs&task=getgraphdata",  
	      success:function(data1) {
			var result=jQuery.parseJSON(data1);
			jQuery(result).each(function (key,val){
				date_x_axises.push(val[0]);
				data_count.push(val[1]);
			});

		var plot = jQuery.plot("#placeholder", [
			{ data: date_x_axises, label: "Jobs"},
			{ data: data_count, label: "Resume"}
		], {
			xaxis: {
				mode: "time",
				ticks: 15,
				labelWidth: 10,
				tickLength: 10,
			},
			selection: {
				mode: "x"
			},
			series: {
				lines: {
					show: true,
				},
				points: {
					show: true
				},
				shadowSize: 0
			},
			grid: {
				hoverable: true,
				clickable: true
			}
		});
		
		jQuery("<div id='tooltip'></div>").css({
			position: "absolute",
			display: "none",
			border: "1px solid #fdd",
			padding: "2px",
			"background-color": "#fee",
			opacity: 0.80
		}).appendTo("body");

		
		jQuery("#placeholder").bind("plothover", function (event, pos, item) {


			if (jQuery("#enableTooltip")) {
				if (item) {
					var x = item.datapoint[0].toFixed(2)/1000,
						y = item.datapoint[1].toFixed();
						var tool_tip_date = new Date(x * 1000);
						//var label_date = new Date(tool_tip_date);
						var dateString = tool_tip_date.toDateString();
					jQuery("#tooltip").html(item.series.label + " of " + dateString + " = " + y)
						.css({top: item.pageY+5, left: item.pageX+5})
						.fadeIn(200);
				} else {
					jQuery("#tooltip").hide();
				}
			}
		});
	  }
   });
		
});

</script>
