<?php
/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 + Created by:	Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Jan 11, 2009
 ^
 + Project: 		JS Jobs
 * File Name:	admin-----/views/application/tmpl/info.php
 ^ 
 * Description: JS Jobs Information
 ^ 
 * History:		NONE
 ^ 
 */

 JRequest :: setVar('layout', 'info');
$_SESSION['cur_layout']='info';
$document = &JFactory::getDocument();
$document->addStyleSheet(JURI::root().'administrator/components/com_jsjobs/include/css/jsjobsadmin.css');


?>
<table width="100%">
	<tr>
		<td align="left" width="175" valign="top">
			<table width="100%"><tr><td style="vertical-align:top;">
			<?php
			include_once('components/com_jsjobs/views/menu.php');
			?>
			</td>
			</tr></table>
		</td>
		<td width="100%" valign="top">
<div id="jsjobs_info_heading"><?php echo JText::_('JS_INFORMATION'); ?></div>	
                    <div id="jsjob_info_container">
                        <div id="jsjob_info">
                            <span id="jsjob_subheading"><?php echo JText::_('COMPONENT_DETAILS'); ?></span>
                            <span id="jsjob_data">
                                <span id="jsjob_data_left"><?php echo JText::_('JS_CREATE_BY');?></span>
                                <span id="jsjob_data_right"><?php echo JText::_('Ahmed Bilal');?></span>
                            </span>
                            <span id="jsjob_data">
                                <span id="jsjob_data_left"><?php echo JText::_('JS_COMPANY');?></span>
                                <span id="jsjob_data_right"><?php echo JText::_('Joom Sky');?></span>
                            </span>
                            <span id="jsjob_data">
                                <span id="jsjob_data_left"><?php echo JText::_('JS_PROJECT_NAME');?></span>
                                <span id="jsjob_data_right"><?php echo JText::_('com_jsjobs');?></span>
                            </span>
                            <span id="jsjob_data">
                                <span id="jsjob_data_left"><?php echo JText::_('JS_VIRSION');?></span>
                                <span id="jsjob_data_right"><?php echo JText::_('1.0.9.0 - b');?></span>
                            </span>
                            <span id="jsjob_data">
                                <span id="jsjob_data_left"><?php echo JText::_('JS_DESCCRIPTION');?></span>
                                <span id="jsjob_data_right"><?php echo JText::_('A component for job posting and resume submission.');?></span>
                            </span>
                            <span id="jsjob_data_web"><?php echo '<a href="www.joomsky.com" target="_blank" >'.JText::_('www.joomsky.com').'</a>';?></span>
                            <div id="jsjob_data_bottom">
                                <span id="jsjob_data_bottom_heading"><?php echo JText::_('OTHER_PRODUCTS'); ?></span>
                                <span id="jsjob_data_bottom_heading_subtext"><?php echo JText::_('SUB_TEXT_INFO_PAGE');?></span>
                                <div id="jsjob_data_box1">
                                    <span id="jsjob_data_box_title"><?php echo JText::_('Vehicle Showroom');?></span>
                                    <span id="jsjob_data_box_title1"><?php echo JText::_('JS Autoz');?></span>
                                    <span id="jsjob_data_box_description"><?php echo JText::_('JS Autoz is free version for an online Vehicles Showroom.');?></span>
                                </div>
                                <div id="jsjob_data_box2">
                                    <span id="jsjob_data_box_title"><?php echo JText::_('Vehicle Showroom');?></span>
                                    <span id="jsjob_data_box_title1"><?php echo JText::_('JS Autoz Pro');?></span>
                                    <span id="jsjob_data_box_description"><?php echo JText::_('JS Autoz pro is the extend version of JS Autoz.');?></span>
                                </div>
                                <div id="jsjob_data_box3">
                                    <span id="jsjob_data_box_title"><?php echo JText::_('Jobs Portal');?></span>
                                    <span id="jsjob_data_box_title1"><?php echo JText::_('JS Jobs');?></span>
                                    <span id="jsjob_data_box_description"><?php echo JText::_('JS Jobs is free version for managing a job portal website.');?></span>
                                </div>
                                <div id="jsjob_data_box4">
                                    <span id="jsjob_data_box_title"><?php echo JText::_('Jobs Portal');?></span>
                                    <span id="jsjob_data_box_title1"><?php echo JText::_('JS Jobs Pro');?></span>
                                    <span id="jsjob_data_box_description"><?php echo JText::_('JS Jobs pro is the extend version of JS Jobs.');?></span>
                                </div>
                            </div>
                        </div>
                    </div>
			
			  
<!--			    <table cellpadding="2" cellspacing="4" border="1" width="100%" class="adminform">
			      <tr align="left" height="55" valign="middle" class="adminform">
			         <td align="left" valign="middle"><h1><?php echo JText::_('JS Jobs') ; ?></h1></td>
			      </tr>
			      <tr align="left" valign="middle">
			         <td align="left" valign="top"><?php echo JText::_('JS_CREATE_BY') . ' :<strong> ' . JText::_('Ahmad Bilal').'</strong>'; ?></td>
			      </tr>
			      <tr align="left" valign="middle">
			         <td align="left" valign="top"><?php echo JText::_('JS_COMPANY') . ' :<strong> ' . JText::_('Joom Sky').'</strong>'; ?></td>
			      </tr>
			      <tr align="left" valign="middle">
			         <td align="left" valign="top"><?php echo JText::_('JS_PROJECT_NAME') . ' :<strong> ' . JText::_('com_jsjobs').'</strong>'; ?></td>
			      </tr>
			      <tr align="left" valign="middle">
			         <td align="left" valign="top"><?php echo JText::_('JS_VIRSION') . ' : <strong>1.0.8.7 - b</strong>'; ?></td>
			      </tr>
			      <tr align="left" valign="middle">
			         <td align="left" valign="top"><?php echo JText::_('JS_DESCCRIPTION') . ' :<strong> ' . JText::_('A component for job posting and resume submission.').'</strong>'; ?></td>
			      </tr>
			      <tr align="left" valign="middle">
			         <td align="left" valign="top">
			         <?php echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='mailto:ahmad@joomsky.com'><strong>" . JText::_('ahmad@joomsky.com') . "</strong></a>"; ?>
			         <?php echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='http://www.joomsky.com' target='_blank'><strong>" . JText::_('www.joomsky.com') ."</strong></a>"; ?>
					 </td>
			      </tr>
			    </table>-->
		</td>
	</tr>
	<tr>
		<td colspan="2" align="left" width="100%"  valign="top">
			
		</td>
	</tr>
</table>							
<script language="javascript" type="text/javascript">
	dhtml.cycleTab('tab1');
</script>
