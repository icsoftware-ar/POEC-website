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
$document->addStyleSheet('components/com_jsjobs/css/'.$this->config['theme']);
$comma = 0;
$colperrow=2;
$colwidth = round(100/$colperrow,1);
$colwidth = $colwidth.'%';

?>
<?php if ($this->config['offline'] == '1'){ ?>
<table cellpadding="0" cellspacing="0" border="0" width="100%" >
	<tr><td valign="top" class="<?php echo $this->theme['title']; ?>" >	<?php echo $this->config['title']; ?></td></tr>
	<tr><td height="25"></td></tr>
	<tr><td class="jsjobsmsg">
		<?php echo $this->config['offline_text']; ?>
	</td></tr>
</table>	
<?php }else{ ?>

<table cellpadding="0" cellspacing="0" border="0" width="100%" >
	<tr><td valign="top" class="<?php echo $this->theme['title']; ?>" >	
		<?php echo $this->config['title']; ?>
	</td>
	</tr>
	<tr><td height="23"></td></tr>
	<?php if ($this->config['cur_location'] == 1) {?>
	<tr><td height="0"></td></tr>
	<tr><td class="curloc">
		<?php echo JText::_('JS_CUR_LOC'); ?> : <?php echo JText::_('JS_RSS_CONTROL_PANEL'); ?>
	</td></tr>
	<?php } ?>
	<tr><td>
	</td></tr>	
	<tr><td height="3"></td></tr>
	
	<tr><td class="<?php echo $this->theme['heading']; ?>" align="center">
		<?php echo JText::_('JS_RSS_CONTROL_PANEL'); ?>
	</td></tr>

	<tr><td height="10"></td></tr>
</table>

	<table cellpadding="0" cellspacing="0" border="0" width="100%" >
		<tr>
			<td>
				<table cellpadding="0" cellspacing="0" border="0" width="100%" >
					<tr height="15">
						<td width="250"></td>
					</tr>
				</table>
			</td>
		</tr>	
		<tr>
			<td>
				<table cellpadding="0" cellspacing="0" border="0" width="100%" >
					<tr>
						<td width="47%" valign="top">
							<table cellpadding="0" cellspacing="0" border="0" width="100%" >
                                                            <tr>
                                                                <td>
																	<img width="24" height="24" src="components/com_jsjobs/images/rss.png" text="Job RSS" alt="Job RSS" />
                                                                    <a class="cplinks" target="_blank" href="index.php?option=com_jsjobs&c=jsjobs&view=rss&layout=rssjobs&format=rss"><?php echo JText::_('JS_SUBSCRIBE_FOR_JOBS');?></a>
                                                                </td>
                                                                <td>
																	<img width="24" height="24" src="components/com_jsjobs/images/rss.png" text="Resume RSS" alt="Resume RSS" />
                                                                    <a class="cplinks" target="_blank" href="index.php?option=com_jsjobs&c=jsjobs&view=rss&layout=rssresumes&format=rss"><?php echo JText::_('JS_SUBSCRIBE_FOR_RESUMES');?></a>
                                                                </td>
                                                            </tr>
							</table>	
						</td>		
					</tr>
					
			</table>	
			</td>
		</tr>	
		<tr>
			<td>
			</td>
		</tr>		
	</table>
<?php
}//ol
?>
<div width="100%">
<?php 
if($this->config['fr_cr_txsh']) {
	echo 
	'<table width="100%" style="table-layout:fixed;">
		<tr><td height="15"></td></tr>
		<tr><td style="vertical-align:top;" align="center">'.$this->config['fr_cr_txa'].$this->config['fr_cr_txb'].'</td></tr>
	</table>';
}	
?>
</div>

