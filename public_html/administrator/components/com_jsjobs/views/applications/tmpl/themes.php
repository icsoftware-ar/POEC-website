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
 * File Name:	admin-----/views/applications/tmpl/jobs.php
 ^ 
 * Description: Default template for jobs view
 ^ 
 * History:		NONE
 ^ 
 */
 
defined('_JEXEC') or die('Restricted access');

JRequest :: setVar('layout', 'themes');

$_SESSION['cur_layout']='themes';
$document = &JFactory::getDocument();
$document->addStyleSheet(JURI::root().'administrator/components/com_jsjobs/include/css/jsjobsadmin.css');

$status = array(
	'1' => JText::_('JS_APPROVED'),
	'-1' => JText::_('JS_REJECTED'));

function checkDefaultTheme($themeno,$config){
	$img = '<img src="../components/com_jsjobs/images/notdefault.png" width="16" height="16" border="0" alt="Not Default" />';
	switch($themeno){
		case 1:
			if($config['theme'] == 'black/css/jsjobsblack.css') $img = '<img src="../components/com_jsjobs/images/default.png" width="16" height="16" border="0" alt="Default" />';
		break;
		case 2:
			if($config['theme'] == 'pink/css/jsjobspink.css') $img = '<img src="../components/com_jsjobs/images/default.png" width="16" height="16" border="0" alt="Default" />';
		break;
		case 3:
			if($config['theme'] == 'orange/css/jsjobsorange.css') $img = '<img src="../components/com_jsjobs/images/default.png" width="16" height="16" border="0" alt="Default" />';
		break;
		case 4:
			if($config['theme'] == 'golden/css/jsjobsgolden.css') $img = '<img src="../components/com_jsjobs/images/default.png" width="16" height="16" border="0" alt="Default" />';
		break;
		case 5:
			if($config['theme'] == 'blue/css/jsjobsblue.css') $img = '<img src="../components/com_jsjobs/images/default.png" width="16" height="16" border="0" alt="Default" />';
		break;
		case 6:
			if($config['theme'] == 'gray/css/jsjobsgray.css') $img = '<img src="../components/com_jsjobs/images/default.png" width="16" height="16" border="0" alt="Default" />';
		break;
		case 7:
			if($config['theme'] == 'green/css/jsjobsgreen.css') $img = '<img src="../components/com_jsjobs/images/default.png" width="16" height="16" border="0" alt="Default" />';
		break;
		case 8:
			if($config['theme'] == 'graywhite/css/jsjobsgraywhite.css') $img = '<img src="../components/com_jsjobs/images/default.png" width="16" height="16" border="0" alt="Default" />';
		break;
		case 9:
			if($config['theme'] == 'template/css/jsjobstemplate.css') $img = '<img src="../components/com_jsjobs/images/default.png" width="16" height="16" border="0" alt="Default" />';
		break;
	}
	return $img;
}
?>
<table width="100%" >
	<tr>
		<td align="left" width="175"  valign="top">
			<table width="100%"><tr><td style="vertical-align:top;">
			<?php
			include_once('components/com_jsjobs/views/menu.php');
			?>
			</td>
			</tr></table>
		</td>
		<td width="100%" valign="top">
				<div id="jsjobs_info_heading"><?php echo JText::_('JS_THEME'); ?></div>

<form action="index.php" method="POST" name="adminForm" id="adminForm">
		<table class="adminlist">
			<thead>
				<tr>
					<th width="5%"></th>
					<th><?php echo JText::_('JS_NAME'); ?></th>
					<th width="10%"><?php echo JText::_('JS_DEFAULT'); ?></th>
				</tr>
			</thead>
				<tr>
					<?php $checked = JHTML::_('grid.id', 1, 1);?>
					<td><?php echo $checked; ?></td>
					<td><?php echo JText::_('JS_BLACK_THEME');?></td>
					<td><a href="javascript:void(0);" onclick="return listItemTask('cb1',' makedefaulttheme')"><?php echo checkDefaultTheme(1,$this->config);?></a></td>
				</tr>
				<tr>
					<?php $checked = JHTML::_('grid.id', 2, 2);?>
					<td><?php echo $checked; ?></td>
					<td><?php echo JText::_('JS_PINK_THEME');?></td>
					<td><a href="javascript:void(0);" onclick="return listItemTask('cb2',' makedefaulttheme')"><?php echo checkDefaultTheme(2,$this->config);?></a></td>
				</tr>
				<tr>
					<?php $checked = JHTML::_('grid.id', 3, 3);?>
					<td><?php echo $checked; ?></td>
					<td><?php echo JText::_('JS_ORANGE_THEME');?></td>
					<td><a href="javascript:void(0);" onclick="return listItemTask('cb3',' makedefaulttheme')"><?php echo checkDefaultTheme(3,$this->config);?></a></td>
				</tr>
				<tr>
					<?php $checked = JHTML::_('grid.id', 4, 4);?>
					<td><?php echo $checked; ?></td>
					<td><?php echo JText::_('JS_GOLDEN_THEME');?></td>
					<td><a href="javascript:void(0);" onclick="return listItemTask('cb4',' makedefaulttheme')"><?php echo checkDefaultTheme(4,$this->config);?></a></td>
				</tr>
				<tr>
					<?php $checked = JHTML::_('grid.id', 5, 5);?>
					<td><?php echo $checked; ?></td>
					<td><?php echo JText::_('JS_BLUE_THEME');?></td>
					<td><a href="javascript:void(0);" onclick="return listItemTask('cb5',' makedefaulttheme')"><?php echo checkDefaultTheme(5,$this->config);?></a></td>
				</tr>
				<tr>
					<?php $checked = JHTML::_('grid.id', 6, 6);?>
					<td><?php echo $checked; ?></td>
					<td><?php echo JText::_('JS_GREY_THEME');?></td>
					<td><a href="javascript:void(0);" onclick="return listItemTask('cb6',' makedefaulttheme')"><?php echo checkDefaultTheme(6,$this->config);?></a></td>
				</tr>
				<tr>
					<?php $checked = JHTML::_('grid.id', 7, 7);?>
					<td><?php echo $checked; ?></td>
					<td><?php echo JText::_('JS_GREEN_THEME');?></td>
					<td><a href="javascript:void(0);" onclick="return listItemTask('cb7',' makedefaulttheme')"><?php echo checkDefaultTheme(7,$this->config);?></a></td>
				</tr>
				<tr>
					<?php $checked = JHTML::_('grid.id', 8, 8);?>
					<td><?php echo $checked; ?></td>
					<td><?php echo JText::_('JS_GRAYWHITE_THEME');?></td>
					<td><a href="javascript:void(0);" onclick="return listItemTask('cb8',' makedefaulttheme')"><?php echo checkDefaultTheme(8,$this->config);?></a></td>
				</tr>
				<tr>
					<?php $checked = JHTML::_('grid.id', 9, 9);?>
					<td><?php echo $checked; ?></td>
					<td><?php echo JText::_('JS_TEMPLATE_THEME');?></td>
					<td><a href="javascript:void(0);" onclick="return listItemTask('cb9',' makedefaulttheme')"><?php echo checkDefaultTheme(9,$this->config);?></a></td>
				</tr>
			
	<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
</form>
			<tr>
				<td colspan="3" align="left" width="100%"  valign="top">
			
				</td>
			</tr>
			
		</table>				
